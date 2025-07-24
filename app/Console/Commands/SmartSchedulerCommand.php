<?php

namespace App\Console\Commands;

use App\Services\SmartSchedulerService;
use Illuminate\Console\Command;

class SmartSchedulerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scheduler:smart {action=run : Action to perform (run, init, status)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage smart monitoring scheduler';

    /**
     * Execute the console command.
     */
    public function handle(SmartSchedulerService $scheduler)
    {
        $action = $this->argument('action');
        
        switch ($action) {
            case 'init':
                $this->info('Initializing smart monitoring scheduler...');
                $scheduler->initializeMonitoring();
                $this->info('✅ Smart scheduler initialized');
                break;
                
            case 'run':
                $this->info('Processing scheduled checks...');
                $scheduler->processScheduledChecks();
                $this->info('✅ Scheduled checks processed');
                break;
                
            case 'status':
                $this->showStatus($scheduler);
                break;
                
            default:
                $this->error("Unknown action: {$action}");
                $this->info('Available actions: init, run, status');
                return 1;
        }
        
        return 0;
    }
    
    private function showStatus(SmartSchedulerService $scheduler)
    {
        $dueServices = $scheduler->getServicesDueForCheck();
        
        $this->info('Smart Scheduler Status');
        $this->info('====================');
        $this->info("Services due for checking: {$dueServices->count()}");
        
        if ($dueServices->count() > 0) {
            $this->info("\nDue services:");
            foreach ($dueServices as $service) {
                $lastCheck = $service->last_checked_at ? 
                    $service->last_checked_at->diffForHumans() : 'Never';
                $this->line("- {$service->name} (last check: {$lastCheck})");
            }
        }
    }
}
