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
        Schema::create('hms_rooms', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('hms_room_type_id');
            $table->foreign('hms_room_type_id')
                ->references('id')
                ->on('hms_room_types')
                ->onDelete('cascade');

            $table->string('room_number');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hms_rooms');
    }
};
