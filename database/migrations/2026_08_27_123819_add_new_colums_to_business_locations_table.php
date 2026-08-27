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
        Schema::table('business_locations', function (Blueprint $table) {
            $table->string('contact_email')->after('mobile')->nullable();
            $table->string('whatsapp_number')->after('contact_email')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('business_locations', function (Blueprint $table) {
            $table->dropColumn(['contact_email', 'whatsapp_number']);
        });
    }
};
