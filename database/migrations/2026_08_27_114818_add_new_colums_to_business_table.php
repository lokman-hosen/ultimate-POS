<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('business', function (Blueprint $table) {
            $table->string('legal_name')->after('name')->nullable();
            $table->string('business_sector')->after('legal_name')->nullable();
            $table->string('business_activity')->after('business_sector');
            $table->enum('business_type', ['self_employed', 'company'])->after('business_activity');
            $table->string('referred_by')->after('business_type')->nullable()->comment('Who referred the business');
            $table->tinyInteger('accept_tc')->default(0);
            $table->tinyInteger('accept_marketing')->default(0);
        });
    }

    /**accept_marketing
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('business', function (Blueprint $table) {
            $table->dropColumn(['legal_name', 'business_sector', 'business_activity', 'business_type', 'referred_by', 'accept_tc', 'accept_marketing']);
        });
    }
};
