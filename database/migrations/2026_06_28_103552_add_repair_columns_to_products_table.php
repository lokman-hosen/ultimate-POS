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
        Schema::table('products', function (Blueprint $table) {

            $table->unsignedInteger('repair_model_id')
                ->nullable()
                ->after('sub_category_id');
            $table->boolean('woocommerce_disable_sync')
                ->default(false)
                ->after('repair_model_id');

            // Optional: many UltimatePOS versions also include this.
            $table->unsignedBigInteger('woocommerce_media_id')
                ->nullable()
                ->after('woocommerce_disable_sync');
            $table->unsignedBigInteger('woocommerce_product_id')
                ->nullable()
                ->after('woocommerce_media_id');

            // Uncomment if you have a repair_device_models table.
            /*
            $table->foreign('repair_model_id')
                ->references('id')
                ->on('repair_device_models')
                ->nullOnDelete();
            */
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {

            // Uncomment if the foreign key exists.
            /*
            $table->dropForeign(['repair_model_id']);
            */

            $table->dropColumn([
                'repair_model_id',
                'woocommerce_disable_sync',
                'woocommerce_media_id',
                'woocommerce_product_id',
            ]);
        });
    }
};
