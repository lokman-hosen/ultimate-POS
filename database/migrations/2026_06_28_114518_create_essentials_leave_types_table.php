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
        Schema::create('essentials_leave_types', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('business_id');
            $table->foreign('business_id')
                ->references('id')
                ->on('business')
                ->onDelete('cascade');

            $table->string('leave_type');

            $table->enum('leave_count_interval', [
                'day',
                'week',
                'month',
                'year'
            ])->nullable();

            $table->unsignedInteger('max_leave_count')->nullable();

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
        Schema::table('essentials_leave_types', function (Blueprint $table) {
            $table->dropForeign(['business_id']);
        });

        Schema::dropIfExists('essentials_leave_types');
    }
};
