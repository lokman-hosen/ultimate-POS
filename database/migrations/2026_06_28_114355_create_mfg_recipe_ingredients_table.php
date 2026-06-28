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
        Schema::create('mfg_recipe_ingredients', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('mfg_recipe_id');
            $table->foreign('mfg_recipe_id')
                ->references('id')
                ->on('mfg_recipes')
                ->onDelete('cascade');

            $table->unsignedInteger('variation_id');
            $table->foreign('variation_id')
                ->references('id')
                ->on('variations')
                ->onDelete('cascade');

            $table->unsignedInteger('sub_unit_id')->nullable();

            $table->decimal('quantity', 22, 4);

            $table->decimal('waste_percent', 22, 4)->default(0);

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
        Schema::table('mfg_recipe_ingredients', function (Blueprint $table) {
            $table->dropForeign(['mfg_recipe_id']);
            $table->dropForeign(['variation_id']);
        });

        Schema::dropIfExists('mfg_recipe_ingredients');
    }
};
