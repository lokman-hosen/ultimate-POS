<?php

namespace App\Console\Commands;

use App\Jobs\SubmitInvoiceToVerifactu;
use App\Services\Verifactu\VerifactuService;
use App\Transaction;
use Exception;
use Illuminate\Console\Command;

class SendVerifactuInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'verifactu:send {--batch=50} {--force} {--business_id=} {--sync}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send pending finalized invoice transactions to AEAT VERI*FACTU';

    /**
     * Execute the console command.
     */
    public function handle(VerifactuService $verifactu)
    {
        $batchSize = (int) ($this->option('batch') ?: 50);
        $force = (bool) $this->option('force');
        $businessId = $this->option('business_id');
        $sync = (bool) $this->option('sync');

        $query = Transaction::whereIn('type', ['sell', 'sell_return'])
            ->where('status', 'final')
            ->whereDoesntHave('verifactuRecords', function ($q) {
                $q->where('submission_status', 'success');
            });

        if ($businessId) {
            $query->where('business_id', $businessId);
        }

        if (! $force) {
            // Only send invoices older than 5 minutes to ensure completion
            $query->where('created_at', '<', now()->subMinutes(5));
        }

        $invoices = $query->orderBy('id', 'asc')->limit($batchSize)->get();

        $count = $invoices->count();
        $this->info("Found {$count} invoice transaction(s) to send to AEAT VERI*FACTU.");

        if ($count === 0) {
            return 0;
        }

        $bar = $this->output->createProgressBar($count);
        $bar->start();

        foreach ($invoices as $invoice) {
            if ($sync) {
                try {
                    $record = $verifactu->submitInvoice($invoice);
                    $this->line(" [OK] Transaction #{$invoice->id} (Invoice: {$invoice->invoice_no}) -> {$record->aeat_status}");
                } catch (Exception $e) {
                    $this->error(" [ERR] Transaction #{$invoice->id} (Invoice: {$invoice->invoice_no}): {$e->getMessage()}");
                }
            } else {
                SubmitInvoiceToVerifactu::dispatch($invoice);
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info($sync ? 'All transactions processed.' : 'All invoice transactions queued for AEAT submission.');

        return 0;
    }
}
