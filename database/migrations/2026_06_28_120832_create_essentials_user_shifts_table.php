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
        Schema::create('essentials_user_shifts', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->unsignedInteger('essentials_shift_id');
            $table->foreign('essentials_shift_id')
                ->references('id')
                ->on('essentials_shifts')
                ->onDelete('cascade');

            $table->date('start_date');

            $table->date('end_date')->nullable();

            $table->timestamps();

            // Prevent duplicate assignments for the same period
            $table->index(['user_id', 'essentials_shift_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('essentials_user_shifts', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['essentials_shift_id']);
            $table->dropIndex(['user_id', 'essentials_shift_id']);
        });

        Schema::dropIfExists('essentials_user_shifts');
    }
};
