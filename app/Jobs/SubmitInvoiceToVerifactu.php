<?php

namespace App\Jobs;

use App\Services\Verifactu\VerifactuService;
use App\Transaction;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SubmitInvoiceToVerifactu implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = [300, 900, 1800]; // 5 min, 15 min, 30 min

    public $invoice;

    /**
     * Create a new job instance
     *
     * @param  Transaction|int  $invoice
     */
    public function __construct($invoice)
    {
        $this->invoice = $invoice;
        $this->onQueue('verifactu');
    }

    /**
     * Execute the job
     */
    public function handle(VerifactuService $verifactu): void
    {
        $invoiceId = is_object($this->invoice) ? ($this->invoice->id ?? 0) : $this->invoice;

        Log::info('Starting Verifactu submission job', ['transaction_id' => $invoiceId]);

        try {
            $record = $verifactu->submitInvoice($this->invoice);

            Log::info('Verifactu submission completed', [
                'transaction_id' => $invoiceId,
                'record_id' => $record->id,
                'status' => $record->aeat_status,
            ]);

        } catch (Exception $e) {
            Log::error('Verifactu submission job failed', [
                'transaction_id' => $invoiceId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            if ($this->attempts() < $this->tries) {
                $delayMinutes = $this->backoff[min($this->attempts() - 1, count($this->backoff) - 1)];
                $this->release(now()->addMinutes($delayMinutes));
            }

            throw $e;
        }
    }
}
