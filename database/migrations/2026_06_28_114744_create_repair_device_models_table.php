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
        Schema::create('repair_device_models', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('business_id');
            $table->foreign('business_id')
                ->references('id')
                ->on('business')
                ->onDelete('cascade');

            $table->unsignedInteger('device_id');
            $table->foreign('device_id')
                ->references('id')
                ->on('categories')
                ->onDelete('cascade');

            $table->unsignedInteger('brand_id')->nullable();
            $table->foreign('brand_id')
                ->references('id')
                ->on('brands')
                ->onDelete('set null');

            $table->string('name');

            $table->text('repair_checklist')->nullable();

            $table->unsignedInteger('created_by')->nullable();
            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

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
        Schema::table('repair_device_models', function (Blueprint $table) {
            $table->dropForeign(['business_id']);
            $table->dropForeign(['device_id']);
            $table->dropForeign(['brand_id']);
            $table->dropForeign(['created_by']);
        });

        Schema::dropIfExists('repair_device_models');
    }
};
