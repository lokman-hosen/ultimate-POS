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
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {

            $table->dateTime('hms_booking_arrival_date_time')->nullable()->after('transaction_date');
            $table->dateTime('hms_booking_departure_date_time')->nullable();

            $table->unsignedInteger('hms_coupon_id')->nullable();
            $table->foreign('hms_coupon_id')
                ->references('id')
                ->on('hms_coupons')
                ->nullOnDelete();

            $table->string('hms_place_of_origin')->nullable();
            $table->string('hms_final_destination')->nullable();
            $table->string('hms_means_of_transport')->nullable();
            $table->string('hms_vehicle_registration_number')->nullable();

            $table->text('hms_reason_for_trip')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {

            $table->dropForeign(['hms_coupon_id']);

            $table->dropColumn([
                'hms_booking_arrival_date_time',
                'hms_booking_departure_date_time',
                'hms_coupon_id',
                'hms_place_of_origin',
                'hms_final_destination',
                'hms_means_of_transport',
                'hms_vehicle_registration_number',
                'hms_reason_for_trip',
            ]);
        });
    }
};
