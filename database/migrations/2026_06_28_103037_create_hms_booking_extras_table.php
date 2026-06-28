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
        Schema::create('hms_booking_extras', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('transaction_id');
            $table->foreign('transaction_id')
                ->references('id')
                ->on('transactions')
                ->onDelete('cascade');

            $table->unsignedInteger('hms_extra_id');
            $table->foreign('hms_extra_id')
                ->references('id')
                ->on('hms_extras')
                ->onDelete('cascade');

            $table->decimal('price', 15, 2)->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hms_booking_extras', function (Blueprint $table) {
            $table->dropForeign(['transaction_id']);
            $table->dropForeign(['hms_extra_id']);
        });

        Schema::dropIfExists('hms_booking_extras');
    }
};
