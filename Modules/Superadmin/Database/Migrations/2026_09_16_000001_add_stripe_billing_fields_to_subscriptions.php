<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->string('stripe_customer_id')->nullable()->index();
            $table->string('stripe_subscription_id')->nullable()->unique();
            $table->string('stripe_price_id')->nullable();
            $table->string('stripe_payment_method_id')->nullable();
            $table->string('stripe_invoice_id')->nullable();
            $table->string('stripe_payment_intent_id')->nullable();
            $table->string('stripe_status')->nullable();
            $table->boolean('cancel_at_period_end')->default(false);
        });

        Schema::create('stripe_webhook_events', function (Blueprint $table) {
            $table->id();
            $table->string('stripe_event_id')->unique();
            $table->string('event_type');
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stripe_webhook_events');

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropUnique(['stripe_subscription_id']);
            $table->dropColumn([
                'stripe_customer_id',
                'stripe_subscription_id',
                'stripe_price_id',
                'stripe_payment_method_id',
                'stripe_invoice_id',
                'stripe_payment_intent_id',
                'stripe_status',
                'cancel_at_period_end',
            ]);
        });
    }
};
