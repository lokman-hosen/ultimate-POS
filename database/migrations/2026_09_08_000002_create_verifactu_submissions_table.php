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
        Schema::create('verifactu_submissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('record_id');
            $table->string('operation_type', 50); // send, cancel, amend, status_check
            $table->longText('request_xml')->nullable();
            $table->longText('response_xml')->nullable();
            $table->json('response_data')->nullable();
            $table->integer('http_status')->nullable();
            $table->string('soap_action')->nullable();
            $table->string('status_code', 50)->nullable(); // AEAT response code
            $table->text('error_message')->nullable();
            $table->integer('attempt')->default(1);
            $table->boolean('success')->default(false);
            $table->timestamps();

            $table->foreign('record_id')->references('id')->on('verifactu_records')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verifactu_submissions');
    }
};
