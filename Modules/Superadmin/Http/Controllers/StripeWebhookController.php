<?php

namespace Modules\Superadmin\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Modules\Superadmin\Entities\Package;
use Modules\Superadmin\Entities\StripeInvoiceRecord;
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
            'invoice.payment_succeeded' => $this->invoicePaid($object),
            'invoice.payment_failed' => $this->invoicePaymentFailed($object),
            'payment_intent.succeeded' => $this->paymentIntentSucceeded($object),
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
        $this->syncSubscription($stripeSubscription->id, $extra);
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
        $subscriptionId = $this->stripeId($invoice->subscription ?? null);
        $paymentIntentId = $this->paymentIntentFromInvoice($invoice);
        $subscription = Subscription::where('stripe_subscription_id', $subscriptionId)->first();

        if (!$subscription && $subscriptionId) {
            $this->syncSubscription($subscriptionId, [
                'payment_intent' => $paymentIntentId,
            ]);
            $subscription = Subscription::where('stripe_subscription_id', $subscriptionId)->first();
        }

        if (!$subscription) {
            return;
        }

        $paidAmount = ((int) ($invoice->amount_paid ?? $invoice->total ?? 0)) / 100;
        $subscription->forceFill([
            'status' => 'approved',
            'stripe_status' => 'active',
            'stripe_invoice_id' => $invoice->id,
            'payment_transaction_id' => $paymentIntentId ?: $subscription->payment_transaction_id,
            'stripe_payment_intent_id' => $paymentIntentId ?: $subscription->stripe_payment_intent_id,
            'package_price' => $paidAmount > 0 ? $paidAmount : $subscription->package_price,
            'start_date' => $this->dateFromTimestamp($invoice->period_start ?? null) ?: $subscription->start_date,
            'end_date' => $this->dateFromTimestamp($invoice->period_end ?? null) ?: $subscription->end_date,
        ])->save();

        $amount = $paidAmount;
        $metadata = $invoice->metadata ?? [];
        StripeInvoiceRecord::updateOrCreate(
            ['stripe_invoice_id' => $invoice->id],
            [
                'subscription_id' => $subscription->id,
                'package_id' => $subscription->package_id,
                'stripe_payment_intent_id' => $paymentIntentId,
                'base_amount' => (float) ($metadata->base_price ?? $subscription->package_price),
                'vat_amount' => (float) ($metadata->vat_amount ?? 0),
                'total_amount' => $amount,
                'currency' => $invoice->currency ?? null,
                'billing_period_start' => $this->dateFromTimestamp($invoice->period_start ?? null),
                'billing_period_end' => $this->dateFromTimestamp($invoice->period_end ?? null),
                'paid_at' => now(),
                'status' => 'paid',
            ]
        );
    }

    protected function invoicePaymentFailed(object $invoice): void
    {
        $subscriptionId = $this->stripeId($invoice->subscription ?? null);
        $paymentIntentId = $this->paymentIntentFromInvoice($invoice);
        $subscription = Subscription::where('stripe_subscription_id', $subscriptionId)->first();

        if (!$subscription && $subscriptionId) {
            $this->syncSubscription($subscriptionId);
            $subscription = Subscription::where('stripe_subscription_id', $subscriptionId)->first();
        }

        if (!$subscription) {
            return;
        }

        $subscription->forceFill([
            'stripe_status' => 'past_due',
            'stripe_invoice_id' => $invoice->id,
            'stripe_payment_intent_id' => $paymentIntentId ?: $subscription->stripe_payment_intent_id,
        ])->save();
    }

    protected function paymentIntentSucceeded(object $paymentIntent): void
    {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret_key'));

        $paymentIntentId = $this->stripeId($paymentIntent);
        $invoiceId = $this->stripeId($paymentIntent->invoice ?? null);
        $subscriptionId = null;

        $checkoutSessionId = $paymentIntent->payment_details->order_reference ?? null;
        if ($checkoutSessionId && str_starts_with($checkoutSessionId, 'cs_')) {
            $checkoutSession = \Stripe\Checkout\Session::retrieve($checkoutSessionId);
            $subscriptionId = $this->stripeId($checkoutSession->subscription ?? null);
            $invoiceId = $invoiceId ?: $this->stripeId($checkoutSession->invoice ?? null);
        }

        if (!$invoiceId && !$subscriptionId) {
            return;
        }

        $subscription = $subscriptionId
            ? Subscription::where('stripe_subscription_id', $subscriptionId)->first()
            : null;

        $subscription = $subscription ?: Subscription::where('stripe_invoice_id', $invoiceId)->first();
        if (!$subscription) {
            if (!$invoiceId) {
                return;
            }

            $invoice = \Stripe\Invoice::retrieve($invoiceId);
            $subscriptionId = $this->stripeId($invoice->subscription ?? null);
            $subscription = Subscription::where('stripe_subscription_id', $subscriptionId)->first();
        }

        if (!$subscription) {
            return;
        }

        $subscription->forceFill([
            'stripe_invoice_id' => $invoiceId ?: $subscription->stripe_invoice_id,
            'payment_transaction_id' => $paymentIntentId,
            'stripe_payment_intent_id' => $paymentIntentId,
            'status' => 'approved',
            'stripe_status' => 'active',
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

        $latestInvoice = null;
        $startTimestamp = $stripeSubscription->current_period_start
            ?? ($stripeSubscription->start_date ?? now()->timestamp);
        $endTimestamp = $stripeSubscription->current_period_end
            ?? ($stripeSubscription->items->data[0]->current_period_end ?? null);
        if (!empty($stripeSubscription->latest_invoice)) {
            $latestInvoice = \Stripe\Invoice::retrieve($this->stripeId($stripeSubscription->latest_invoice));
            if (!$endTimestamp) {
                $endTimestamp = $latestInvoice->period_end
                    ?? ($latestInvoice->lines->data[0]->period->end ?? null);
            }
        }

        if (!$endTimestamp || $endTimestamp <= $startTimestamp) {
            $endTimestamp = $this->packageEndTimestamp(
                Carbon::createFromTimestamp($startTimestamp),
                $package
            );
        }

        $paymentIntentId = $this->stripeId($extra['payment_intent'] ?? null);
        if (!$paymentIntentId && $latestInvoice) {
            $paymentIntentId = $this->paymentIntentFromInvoice($latestInvoice);
        }

        $active = in_array($stripeSubscription->status, ['active', 'trialing'], true);
        $subscription = Subscription::firstOrNew([
            'stripe_subscription_id' => $stripeSubscription->id,
        ]);

        $subscription->forceFill([
            'business_id' => $businessId,
            'package_id' => $package->id,
            'package_price' => $metadata->price ?? $subscription->package_price ?? $package->price,
            'next_renewal_price' => $metadata->renewal_price ?? $subscription->next_renewal_price,
            'next_renewal_at' => !empty($metadata->renewal_at)
                ? Carbon::parse($metadata->renewal_at)
                : $subscription->next_renewal_at,
            'original_price' => $package->price,
            'coupon_code' => $metadata->coupon_code ?? null,
            'paid_via' => 'stripe',
            'payment_transaction_id' => $paymentIntentId ?: $subscription->payment_transaction_id,
            'start_date' => $this->dateFromTimestamp($startTimestamp),
            'end_date' => $this->dateFromTimestamp($endTimestamp),
            'trial_end_date' => !empty($stripeSubscription->trial_end)
                ? $this->dateFromTimestamp($stripeSubscription->trial_end)
                : null,
            'status' => $active ? 'approved' : 'waiting',
            'stripe_customer_id' => $extra['customer'] ?? $stripeSubscription->customer ?? $subscription->stripe_customer_id,
            'stripe_price_id' => $stripeSubscription->items->data[0]->price->id ?? $subscription->stripe_price_id,
            'stripe_payment_method_id' => $stripeSubscription->default_payment_method ?? $subscription->stripe_payment_method_id,
            'stripe_invoice_id' => $this->stripeId($stripeSubscription->latest_invoice) ?: $subscription->stripe_invoice_id,
            'stripe_payment_intent_id' => $paymentIntentId ?: $subscription->stripe_payment_intent_id,
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

    protected function packageEndTimestamp(Carbon $startDate, Package $package): int
    {
        return match ($package->interval) {
            'days' => $startDate->copy()->addDays((int) $package->interval_count)->timestamp,
            'months' => $startDate->copy()->addMonths((int) $package->interval_count)->timestamp,
            'years' => $startDate->copy()->addYears((int) $package->interval_count)->timestamp,
            default => throw new \InvalidArgumentException('Unsupported package billing interval.'),
        };
    }

    protected function stripeId($value): ?string
    {
        if (is_string($value)) {
            return $value;
        }

        return is_object($value) ? ($value->id ?? null) : null;
    }

    protected function paymentIntentFromInvoice(?object $invoice): ?string
    {
        if (!$invoice) {
            return null;
        }

        $paymentIntentId = $this->stripeId($invoice->payment_intent ?? null);
        if ($paymentIntentId) {
            return $paymentIntentId;
        }

        foreach (($invoice->payments->data ?? []) as $payment) {
            $paymentIntentId = $this->stripeId($payment->payment_intent ?? null)
                ?? $this->stripeId($payment->payment->payment_intent ?? null);

            if ($paymentIntentId) {
                return $paymentIntentId;
            }
        }

        return null;
    }
}
