<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->decimal('next_renewal_price', 22, 4)->nullable()->after('package_price');
            $table->timestamp('next_renewal_at')->nullable()->after('next_renewal_price');
        });

        Schema::create('stripe_package_prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('package_id');
            $table->string('stripe_price_id')->unique();
            $table->string('currency', 3);
            $table->unsignedBigInteger('unit_amount');
            $table->string('interval');
            $table->unsignedInteger('interval_count');
            $table->timestamps();
            $table->unique(['package_id', 'currency', 'unit_amount', 'interval', 'interval_count'], 'stripe_package_prices_lookup');
        });

        Schema::create('subscription_changes', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('subscription_id');
            $table->unsignedInteger('old_package_id')->nullable();
            $table->unsignedInteger('new_package_id')->nullable();
            $table->decimal('old_price', 22, 4)->nullable();
            $table->decimal('new_price', 22, 4)->nullable();
            $table->string('change_type');
            $table->string('adjustment_type')->nullable();
            $table->decimal('adjustment_amount', 22, 4)->default(0);
            $table->timestamp('effective_at')->nullable();
            $table->date('billing_period_start')->nullable();
            $table->date('billing_period_end')->nullable();
            $table->string('stripe_invoice_id')->nullable();
            $table->string('stripe_invoice_item_id')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
            $table->index(['subscription_id', 'status']);
        });

        Schema::create('stripe_invoice_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('subscription_id');
            $table->unsignedInteger('package_id')->nullable();
            $table->string('stripe_invoice_id')->unique();
            $table->string('stripe_payment_intent_id')->nullable();
            $table->decimal('base_amount', 22, 4)->default(0);
            $table->decimal('vat_amount', 22, 4)->default(0);
            $table->decimal('total_amount', 22, 4)->default(0);
            $table->string('currency', 3)->nullable();
            $table->timestamp('billing_period_start')->nullable();
            $table->timestamp('billing_period_end')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->string('status');
            $table->timestamps();
            $table->index('subscription_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_changes');
        Schema::dropIfExists('stripe_invoice_records');
        Schema::dropIfExists('stripe_package_prices');
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn(['next_renewal_price', 'next_renewal_at']);
        });
    }
};
