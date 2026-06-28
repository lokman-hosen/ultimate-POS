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
        Schema::create('hms_room_type_pricings', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('hms_room_type_id');
            $table->foreign('hms_room_type_id')
                ->references('id')
                ->on('hms_room_types')
                ->onDelete('cascade');

            $table->unsignedTinyInteger('adults')->nullable();
            $table->unsignedTinyInteger('childrens')->nullable();

            $table->decimal('default_price_per_night', 15, 2);

            $table->decimal('price_monday', 15, 2)->nullable();
            $table->decimal('price_tuesday', 15, 2)->nullable();
            $table->decimal('price_wednesday', 15, 2)->nullable();
            $table->decimal('price_thursday', 15, 2)->nullable();
            $table->decimal('price_friday', 15, 2)->nullable();
            $table->decimal('price_saturday', 15, 2)->nullable();
            $table->decimal('price_sunday', 15, 2)->nullable();

            $table->string('season_type')->default('standard');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hms_room_type_pricings');
    }
};
