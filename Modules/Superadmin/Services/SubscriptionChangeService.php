<?php

namespace Modules\Superadmin\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Superadmin\Entities\Package;
use Modules\Superadmin\Entities\Subscription;
use Stripe\InvoiceItem;
use Stripe\Invoice;
use Stripe\Stripe;
use Stripe\Subscription as StripeSubscription;

class SubscriptionChangeService
{
    public function __construct(
        protected SubscriptionPricingService $pricing,
        protected StripePriceService $prices
    ) {
        Stripe::setApiKey(config('services.stripe.secret_key'));
    }

    public function change(Subscription $subscription, Package $newPackage): Subscription
    {
        if (!$subscription->stripe_subscription_id || !$subscription->stripe_customer_id || !$subscription->package) {
            throw new \RuntimeException('This subscription cannot be changed through Stripe.');
        }

        if ((int) $subscription->package_id === (int) $newPackage->id) {
            throw new \RuntimeException('You are already subscribed to this package.');
        }

        $stripeSubscription = StripeSubscription::retrieve($subscription->stripe_subscription_id);
        $item = $stripeSubscription->items->data[0] ?? null;
        if (!$item) {
            throw new \RuntimeException('The Stripe subscription has no subscription item.');
        }

        $periodStartTimestamp = $item->current_period_start ?? null;
        $periodEndTimestamp = $item->current_period_end ?? null;
        if (!$periodStartTimestamp || !$periodEndTimestamp) {
            throw new \RuntimeException('The Stripe subscription item has no billing period dates.');
        }

        $periodStart = Carbon::createFromTimestamp($periodStartTimestamp);
        $periodEnd = Carbon::createFromTimestamp($periodEndTimestamp);
        $at = now();
        $old = $this->pricing->resolve($subscription->business, $subscription->package, $periodStart);
        $new = $this->pricing->resolve($subscription->business, $newPackage, $periodStart);
        $oldRemaining = $this->pricing->proratedAmount($old['total_amount'], $periodStart, $periodEnd, $at);
        $newRemaining = $this->pricing->proratedAmount($new['total_amount'], $periodStart, $periodEnd, $at);
        $adjustment = round($newRemaining - $oldRemaining, 2);
        $changeType = $adjustment >= 0 ? 'upgrade' : 'downgrade';
        $stripePriceId = $this->prices->forRecurringPackage($newPackage, $new);
        $vatShare = $new['total_amount'] > 0 ? $new['vat_amount'] / $new['total_amount'] : 0;
        $adjustmentVat = round($adjustment * $vatShare, 2);
        $adjustmentBase = round($adjustment - $adjustmentVat, 2);

        DB::transaction(function () use ($subscription, $newPackage, $stripeSubscription, $item, $stripePriceId, $adjustment, $adjustmentBase, $adjustmentVat, $changeType, $periodStart, $periodEnd, $new) {
            if ($adjustment !== 0.0) {
                InvoiceItem::create([
                    'customer' => $subscription->stripe_customer_id,
                    'amount' => $this->minorAmount($adjustment, $new['currency']),
                    'currency' => $new['currency'],
                    'description' => ucfirst($changeType).' adjustment for '.$newPackage->name,
                    'metadata' => [
                        'subscription_id' => (string) $subscription->id,
                        'package_change' => 'true',
                        'base_price' => (string) $adjustmentBase,
                        'vat_amount' => (string) $adjustmentVat,
                    ],
                ]);

                if ($adjustment > 0) {
                    Invoice::create([
                        'customer' => $subscription->stripe_customer_id,
                        'subscription' => $stripeSubscription->id,
                        'auto_advance' => true,
                        'metadata' => [
                            'subscription_id' => (string) $subscription->id,
                            'package_change' => 'true',
                            'base_price' => (string) $adjustmentBase,
                            'vat_amount' => (string) $adjustmentVat,
                        ],
                    ]);
                }
            }

            StripeSubscription::update($stripeSubscription->id, [
                'items' => [['id' => $item->id, 'price' => $stripePriceId]],
                'proration_behavior' => 'none',
                'cancel_at_period_end' => false,
                'metadata' => [
                    'business_id' => (string) $subscription->business_id,
                    'business_name' => optional($subscription->business)->name,
                    'user_id' => (string) $subscription->created_id,
                    'package_id' => (string) $newPackage->id,
                    'package_name' => $newPackage->name,
                    'coupon_code' => (string) $subscription->coupon_code,
                    'price' => (string) $new['total_amount'],
                    'base_price' => (string) $new['base_amount'],
                    'vat_amount' => (string) $new['vat_amount'],
                ],
            ]);

            DB::table('subscription_changes')->insert([
                'subscription_id' => $subscription->id,
                'old_package_id' => $subscription->package_id,
                'new_package_id' => $newPackage->id,
                'old_price' => $subscription->package_price,
                'new_price' => $new['total_amount'],
                'change_type' => $changeType,
                'adjustment_type' => $adjustment >= 0 ? 'charge' : 'credit',
                'adjustment_amount' => abs($adjustment),
                'effective_at' => now(),
                'billing_period_start' => $periodStart->toDateString(),
                'billing_period_end' => $periodEnd->toDateString(),
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $subscription->forceFill([
                'package_id' => $newPackage->id,
                'package_price' => $new['total_amount'],
                'original_price' => $newPackage->price,
                'stripe_price_id' => $stripePriceId,
                'cancel_at_period_end' => false,
                'package_details' => $this->packageDetails($newPackage),
            ])->save();
        });

        return $subscription->fresh();
    }

    protected function minorAmount(float $amount, string $currency): int
    {
        $zeroDecimal = ['bif', 'clp', 'djf', 'gnf', 'jpy', 'kmf', 'krw', 'mga', 'pyg', 'rwf', 'ugx', 'vnd', 'vuv', 'xaf', 'xof', 'xpf'];

        return in_array($currency, $zeroDecimal, true) ? (int) round($amount) : (int) round($amount * 100);
    }

    protected function packageDetails(Package $package): array
    {
        return array_merge([
            'location_count' => $package->location_count,
            'user_count' => $package->user_count,
            'product_count' => $package->product_count,
            'invoice_count' => $package->invoice_count,
            'name' => $package->name,
        ], $package->custom_permissions ?? []);
    }
}
