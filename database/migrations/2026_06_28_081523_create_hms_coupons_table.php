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
        Schema::create('hms_coupons', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('business_id');
            $table->foreign('business_id')
                ->references('id')
                ->on('business')
                ->onDelete('cascade');

            $table->unsignedInteger('hms_room_type_id');
            $table->foreign('hms_room_type_id')
                ->references('id')
                ->on('hms_room_types')
                ->onDelete('cascade');

            $table->string('coupon_code')->unique();

            $table->enum('discount_type', [
                'percentage',
                'fixed'
            ])->default('percentage');

            $table->decimal('discount', 15, 2);

            $table->date('start_date');
            $table->date('end_date');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hms_coupons');
    }
};
