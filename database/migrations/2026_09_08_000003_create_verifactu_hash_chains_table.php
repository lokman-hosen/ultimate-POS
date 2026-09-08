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
        Schema::create('verifactu_hash_chains', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('record_id');
            $table->string('previous_hash')->nullable();
            $table->string('current_hash')->index();
            $table->longText('signed_data')->nullable();
            $table->timestamps();

            $table->foreign('record_id')->references('id')->on('verifactu_records')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verifactu_hash_chains');
    }
};
