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
        Schema::create('essentials_shifts', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('business_id');
            $table->foreign('business_id')
                ->references('id')
                ->on('business')
                ->onDelete('cascade');

            $table->string('name');

            $table->enum('type', [
                'fixed_shift',
                'flexible_shift',
            ])->default('fixed_shift');

            $table->time('start_time')->nullable();

            $table->time('end_time')->nullable();

            // Stores JSON like ["sunday","saturday"]
            $table->json('holidays')->nullable();

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
        Schema::table('essentials_shifts', function (Blueprint $table) {
            $table->dropForeign(['business_id']);
        });

        Schema::dropIfExists('essentials_shifts');
    }
};
