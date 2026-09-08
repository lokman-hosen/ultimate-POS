<?php

namespace App\Console\Commands;

use App\Models\VerifactuRecord;
use App\Services\Verifactu\VerifactuService;
use Exception;
use Illuminate\Console\Command;

class CheckVerifactuStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'verifactu:check-status {--batch=100} {--business_id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check status of pending Verifactu AEAT submissions';

    /**
     * Execute the console command.
     */
    public function handle(VerifactuService $verifactu)
    {
        $batchSize = (int) ($this->option('batch') ?: 100);
        $businessId = $this->option('business_id');

        $query = VerifactuRecord::where('submission_status', 'success')
            ->where(function ($q) {
                $q->whereIn('aeat_status', ['Pendiente', ''])
                    ->orWhereNull('aeat_status');
            });

        if ($businessId) {
            $query->where('business_id', $businessId);
        }

        $records = $query->limit($batchSize)->get();

        $count = $records->count();
        $this->info("Checking AEAT status for {$count} pending record(s)...");

        if ($count === 0) {
            return 0;
        }

        $bar = $this->output->createProgressBar($count);
        $bar->start();

        foreach ($records as $record) {
            try {
                $statusData = $verifactu->checkStatus($record);
                $this->line(" [OK] Record #{$record->id} (Invoice {$record->numero}): {$record->aeat_status}");
            } catch (Exception $e) {
                $this->error(" [ERR] Failed to check status for record #{$record->id}: {$e->getMessage()}");
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Status check process completed.');

        return 0;
    }
}
