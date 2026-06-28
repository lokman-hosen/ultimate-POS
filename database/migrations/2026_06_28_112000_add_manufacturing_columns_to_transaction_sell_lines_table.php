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
        Schema::table('transaction_sell_lines', function (Blueprint $table) {
            $table->decimal('mfg_waste_percent', 22, 4)
                ->default(0)
                ->after('lot_no_line_id');
            $table->unsignedBigInteger('woocommerce_line_items_id')
                ->nullable()
                ->after('lot_no_line_id');
        });
    }

    public function down(): void
    {
        Schema::table('transaction_sell_lines', function (Blueprint $table) {
            $table->dropColumn('mfg_waste_percent');
            $table->dropColumn('woocommerce_line_items_id');
        });
    }
};
