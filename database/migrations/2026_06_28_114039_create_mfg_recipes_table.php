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
        Schema::create('mfg_recipes', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('product_id');
            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade');

            $table->unsignedInteger('variation_id');
            $table->foreign('variation_id')
                ->references('id')
                ->on('variations')
                ->onDelete('cascade');

            $table->unsignedInteger('sub_unit_id')->nullable();
            // Uncomment if sub_units table exists
            /*
            $table->foreign('sub_unit_id')
                ->references('id')
                ->on('units')
                ->nullOnDelete();
            */

            $table->decimal('total_quantity', 22, 4)->default(1);

            $table->decimal('ingredients_cost', 22, 4)->default(0);

            $table->decimal('extra_cost', 22, 4)->default(0);

            $table->decimal('final_price', 22, 4)->default(0);

            $table->decimal('waste_percent', 22, 4)->default(0);

            $table->longText('instructions')->nullable();

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
        Schema::table('mfg_recipes', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropForeign(['variation_id']);
            // $table->dropForeign(['sub_unit_id']);
        });

        Schema::dropIfExists('mfg_recipes');
    }
};
