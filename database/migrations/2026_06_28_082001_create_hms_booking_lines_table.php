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
        Schema::create('hms_booking_lines', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('transaction_id');
            $table->foreign('transaction_id')
                ->references('id')
                ->on('transactions')
                ->onDelete('cascade');

            $table->unsignedInteger('hms_room_type_id');
            $table->foreign('hms_room_type_id')
                ->references('id')
                ->on('hms_room_types')
                ->onDelete('cascade');

            $table->unsignedInteger('hms_room_id')->nullable();
            $table->foreign('hms_room_id')
                ->references('id')
                ->on('hms_rooms')
                ->nullOnDelete();

            $table->unsignedTinyInteger('adults')->default(1);
            $table->unsignedTinyInteger('childrens')->default(0);

            $table->decimal('price', 15, 2)->default(0);
            $table->decimal('total_price', 15, 2)->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hms_booking_lines', function (Blueprint $table) {
            $table->dropForeign(['transaction_id']);
            $table->dropForeign(['hms_room_type_id']);
            $table->dropForeign(['hms_room_id']);
        });

        Schema::dropIfExists('hms_booking_lines');
    }
};
