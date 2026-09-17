<?php

namespace Modules\Superadmin\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Modules\Superadmin\Entities\Package;
use Modules\Superadmin\Entities\StripeWebhookEvent;
use Modules\Superadmin\Entities\Subscription;

class StripeWebhookController extends Controller
{
    public function handle(Request $request): Response
    {
        $secret = config('services.stripe.webhook_secret');
        if (empty($secret)) {
            Log::error('Stripe webhook secret is not configured.');

            return response('Stripe webhook is not configured.', 500);
        }

        try {
            $event = \Stripe\Webhook::constructEvent(
                $request->getContent(),
                $request->header('Stripe-Signature'),
                $secret
            );
        } catch (\UnexpectedValueException|\Stripe\Exception\SignatureVerificationException $exception) {
            Log::warning('Invalid Stripe webhook received.', ['message' => $exception->getMessage()]);

            return response('Invalid webhook.', 400);
        }

        $storedEvent = StripeWebhookEvent::firstOrCreate(
            ['stripe_event_id' => $event->id],
            ['event_type' => $event->type]
        );

        if ($storedEvent->processed_at) {
            return response('Already processed.', 200);
        }

        try {
            $this->processEvent($event);
            $storedEvent->forceFill(['processed_at' => now()])->save();
        } catch (\Throwable $exception) {
            Log::error('Stripe webhook processing failed.', [
                'event_id' => $event->id,
                'event_type' => $event->type,
                'message' => $exception->getMessage(),
            ]);

            return response('Webhook processing failed.', 500);
        }

        return response('Webhook received.', 200);
    }

    protected function processEvent(object $event): void
    {
        $object = $event->data->object;

        match ($event->type) {
            'checkout.session.completed' => $this->checkoutCompleted($object),
            'customer.subscription.created',
            'customer.subscription.updated' => $this->subscriptionUpdated($object),
            'customer.subscription.deleted' => $this->subscriptionDeleted($object),
            'invoice.paid' => $this->invoicePaid($object),
            'invoice.payment_failed' => $this->invoicePaymentFailed($object),
            default => null,
        };
    }

    protected function checkoutCompleted(object $session): void
    {
        if (($session->mode ?? null) !== 'subscription' || empty($session->subscription)) {
            return;
        }

        $this->syncSubscription($session->subscription, [
            'customer' => $session->customer ?? null,
            'payment_intent' => $session->payment_intent ?? null,
        ]);
    }

    protected function subscriptionUpdated(object $stripeSubscription, array $extra = []): void
    {
        $this->syncSubscription($stripeSubscription, $extra);
    }

    protected function subscriptionDeleted(object $stripeSubscription): void
    {
        $subscription = Subscription::where('stripe_subscription_id', $stripeSubscription->id)->first();
        if (!$subscription) {
            return;
        }

        $subscription->forceFill([
            'stripe_status' => 'canceled',
            'cancel_at_period_end' => false,
            'status' => 'declined',
            'end_date' => $this->dateFromTimestamp($stripeSubscription->ended_at ?? $stripeSubscription->current_period_end),
        ])->save();
    }

    protected function invoicePaid(object $invoice): void
    {
        $subscription = Subscription::where('stripe_subscription_id', $invoice->subscription)->first();
        if (!$subscription) {
            return;
        }

        $subscription->forceFill([
            'status' => 'approved',
            'stripe_status' => 'active',
            'stripe_invoice_id' => $invoice->id,
            'stripe_payment_intent_id' => $invoice->payment_intent ?? $subscription->stripe_payment_intent_id,
        ])->save();
    }

    protected function invoicePaymentFailed(object $invoice): void
    {
        $subscription = Subscription::where('stripe_subscription_id', $invoice->subscription)->first();
        if (!$subscription) {
            return;
        }

        $subscription->forceFill([
            'stripe_status' => 'past_due',
            'stripe_invoice_id' => $invoice->id,
            'stripe_payment_intent_id' => $invoice->payment_intent ?? $subscription->stripe_payment_intent_id,
        ])->save();
    }

    protected function syncSubscription(string $stripeSubscriptionId, array $extra = []): void
    {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret_key'));
        $stripeSubscription = \Stripe\Subscription::retrieve($stripeSubscriptionId);
        $metadata = $stripeSubscription->metadata ?? [];
        $businessId = $metadata->business_id ?? null;
        $packageId = $metadata->package_id ?? null;

        if (!$businessId || !$packageId) {
            Log::warning('Stripe subscription is missing business metadata.', [
                'stripe_subscription_id' => $stripeSubscription->id,
            ]);

            return;
        }

        $package = Package::withTrashed()->find($packageId);
        if (!$package) {
            throw new \RuntimeException('Stripe subscription package does not exist.');
        }

        $active = in_array($stripeSubscription->status, ['active', 'trialing'], true);
        $subscription = Subscription::firstOrNew([
            'stripe_subscription_id' => $stripeSubscription->id,
        ]);

        $subscription->forceFill([
            'business_id' => $businessId,
            'package_id' => $package->id,
            'package_price' => $metadata->price ?? $package->price,
            'original_price' => $package->price,
            'coupon_code' => $metadata->coupon_code ?? null,
            'paid_via' => 'stripe',
            'payment_transaction_id' => $stripeSubscription->latest_invoice ?? $subscription->payment_transaction_id,
            'start_date' => $this->dateFromTimestamp($stripeSubscription->start_date ?? now()->timestamp),
            'end_date' => $this->dateFromTimestamp($stripeSubscription->current_period_end),
            'trial_end_date' => !empty($stripeSubscription->trial_end)
                ? $this->dateFromTimestamp($stripeSubscription->trial_end)
                : null,
            'status' => $active ? 'approved' : 'waiting',
            'stripe_customer_id' => $extra['customer'] ?? $stripeSubscription->customer ?? $subscription->stripe_customer_id,
            'stripe_price_id' => $stripeSubscription->items->data[0]->price->id ?? $subscription->stripe_price_id,
            'stripe_payment_method_id' => $stripeSubscription->default_payment_method ?? $subscription->stripe_payment_method_id,
            'stripe_invoice_id' => $stripeSubscription->latest_invoice ?? $subscription->stripe_invoice_id,
            'stripe_payment_intent_id' => $extra['payment_intent'] ?? $subscription->stripe_payment_intent_id,
            'stripe_status' => $stripeSubscription->status,
            'cancel_at_period_end' => (bool) $stripeSubscription->cancel_at_period_end,
            'package_details' => $this->packageDetails($package),
            'created_id' => $metadata->user_id ?? $subscription->created_id ?? 0,
        ])->save();
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

    protected function dateFromTimestamp(?int $timestamp): ?Carbon
    {
        return $timestamp ? Carbon::createFromTimestamp($timestamp) : null;
    }
}
