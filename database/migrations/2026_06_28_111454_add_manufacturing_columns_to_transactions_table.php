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
        Schema::table('transactions', function (Blueprint $table) {

            $table->boolean('mfg_is_final')->default(false);

            $table->unsignedInteger('mfg_parent_production_purchase_id')->nullable();

            $table->decimal('mfg_production_cost', 22, 4)->default(0);

            $table->decimal('mfg_wasted_units', 22, 4)->default(0);

            $table->text('mfg_production_note')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {

            $table->dropColumn([
                'mfg_is_final',
                'mfg_parent_production_purchase_id',
                'mfg_wasted_units',
                'mfg_production_cost',
                'mfg_production_note',
            ]);
        });
    }
};
