<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');

            $table->text('description')->nullable();

            $table->decimal('price', 15, 4)->default(0);

            $table->enum('interval', [
                'days',
                'weeks',
                'months',
                'years'
            ])->default('months');

            $table->unsignedInteger('interval_count')->default(1);

            $table->unsignedInteger('trial_days')->default(0);

            $table->unsignedInteger('location_count')->default(1);

            $table->unsignedInteger('user_count')->default(1);

            $table->unsignedInteger('product_count')->default(0);

            $table->unsignedInteger('invoice_count')->default(0);

            $table->boolean('is_active')->default(true);

            $table->unsignedInteger('sort_order')->default(0);

            // Restaurant module
            $table->boolean('tables')->default(false);
            $table->boolean('kitchen')->default(false);
            $table->boolean('order_screen')->default(false);

            // Booking module
            $table->boolean('bookings')->default(false);

            // Optional module permissions
            $table->text('custom_permissions')->nullable();

            $table->unsignedInteger('created_by')->nullable();

            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('packages');
    }
};
