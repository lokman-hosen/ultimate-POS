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
        Schema::create('verifactu_records', function (Blueprint $table) {
            $table->id();

            // Business & Location references
            $table->unsignedInteger('business_id')->nullable()->index();
            $table->unsignedInteger('location_id')->nullable()->index();

            // Invoice / Transaction reference
            $table->unsignedInteger('transaction_id')->index();
            $table->unsignedInteger('invoice_id')->nullable()->index();
            $table->string('serie', 50)->nullable();
            $table->string('numero', 50);
            $table->date('fecha_expedicion');

            // Fiscal record data
            $table->longText('xml_content')->nullable();
            $table->string('hash_anterior')->nullable();
            $table->string('hash_registro')->nullable()->index();
            $table->string('uuid')->nullable()->index();
            $table->string('csv')->nullable(); // Secure Verification Code (CSV)

            // Status tracking
            $table->string('aeat_status')->default('Pendiente'); // Pendiente, Correcta, AceptadaConErrores, Anulada, Rechazada
            $table->string('submission_status')->default('pending'); // pending, processing, success, failed
            $table->text('error_message')->nullable();
            $table->json('response_payload')->nullable();

            // Audit
            $table->string('ip_address', 45)->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('status_checked_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
            $table->index(['serie', 'numero', 'fecha_expedicion']);
            $table->index(['business_id', 'location_id', 'serie']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verifactu_records');
    }
};
