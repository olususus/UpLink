<?php

namespace App\Console\Commands;

use App\Models\Incident;
use Illuminate\Console\Command;

class CleanupOldIncidents extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'incidents:cleanup {--days= : Number of days to retain incidents (overrides config)}';

    /**
     * The console command description.
     */
    protected $description = 'Clean up old incidents based on retention policy';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $retentionDays = $this->option('days') ?? config('status.incident_retention_days', 90);
        
        if (!is_numeric($retentionDays) || $retentionDays <= 0) {
            $this->error('Invalid retention days. Must be a positive number.');
            return Command::FAILURE;
        }

        $cutoffDate = now()->subDays($retentionDays);
        
        $this->info("Cleaning up incidents older than {$retentionDays} days (before {$cutoffDate->format('Y-m-d H:i:s')})...");

        $deletedCount = Incident::where('created_at', '<', $cutoffDate)
            ->where('status', '!=', 'investigating') // Keep ongoing incidents
            ->delete();

        if ($deletedCount > 0) {
            $this->info("Successfully deleted {$deletedCount} old incidents.");
        } else {
            $this->info('No old incidents found to delete.');
        }

        return Command::SUCCESS;
    }
}
