<?php

namespace Modules\Superadmin\Console;

use Illuminate\Console\Command;
use Modules\Superadmin\Entities\Subscription;
use Modules\Superadmin\Services\StripePriceService;
use Modules\Superadmin\Services\SubscriptionPricingService;
use Stripe\Stripe;
use Stripe\Subscription as StripeSubscription;

class SyncRenewalPrices extends Command
{
    protected $signature = 'subscriptions:sync-renewal-prices';
    protected $description = 'Synchronize Stripe prices before subscription renewals';

    public function handle(SubscriptionPricingService $pricing, StripePriceService $prices): int
    {
        Stripe::setApiKey(config('services.stripe.secret_key'));

        Subscription::with(['package', 'business.currency'])
            ->whereNotNull('stripe_subscription_id')
            ->whereIn('stripe_status', ['active', 'trialing', 'past_due'])
            ->whereDate('end_date', '<=', now()->addDays(3))
            ->chunkById(100, function ($subscriptions) use ($pricing, $prices) {
                foreach ($subscriptions as $subscription) {
                    $this->sync($subscription, $pricing, $prices);
                }
            });

        return self::SUCCESS;
    }

    protected function sync(Subscription $subscription, SubscriptionPricingService $pricing, StripePriceService $prices): void
    {
        if (!$subscription->package || !$subscription->business) {
            return;
        }

        $periodStart = $subscription->end_date->copy()->startOfDay();
        $resolved = $pricing->resolve($subscription->business, $subscription->package, $periodStart);
        $stripePriceId = $prices->forRecurringPackage($subscription->package, $resolved);

        if ($stripePriceId === $subscription->stripe_price_id) {
            return;
        }

        $stripeSubscription = StripeSubscription::retrieve($subscription->stripe_subscription_id);
        $item = $stripeSubscription->items->data[0] ?? null;
        if (!$item) {
            return;
        }

        StripeSubscription::update($subscription->stripe_subscription_id, [
            'items' => [['id' => $item->id, 'price' => $stripePriceId]],
            'proration_behavior' => 'none',
            'metadata' => [
                'package_id' => (string) $subscription->package_id,
                'price' => (string) $resolved['total_amount'],
                'base_price' => (string) $resolved['base_amount'],
                'vat_amount' => (string) $resolved['vat_amount'],
                'renewal_price' => (string) $resolved['total_amount'],
                'renewal_at' => $periodStart->toIso8601String(),
            ],
        ]);

        $subscription->forceFill([
            'stripe_price_id' => $stripePriceId,
            'next_renewal_price' => $resolved['total_amount'],
            'next_renewal_at' => $periodStart,
        ])->save();
    }
}
