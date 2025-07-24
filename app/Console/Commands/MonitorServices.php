<?php

namespace App\Console\Commands;

use App\Models\Service;
use App\Services\SmartSchedulerService;
use Illuminate\Console\Command;

class MonitorServices extends Command
{
    protected $signature = 'status:monitor';
    protected $description = 'Monitor automatic services and update their status';

    private SmartSchedulerService $schedulerService;

    public function __construct()
    {
        parent::__construct();
        $this->schedulerService = new SmartSchedulerService();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $services = Service::where('type', 'automatic')
            ->where('is_active', true)
            ->whereNotNull('url')
            ->get();

        if ($services->isEmpty()) {
            $this->info('No automatic services to monitor.');
            return;
        }

        foreach ($services as $service) {
            $this->info("Monitoring {$service->name}...");
            $this->schedulerService->performServiceCheck($service);
        }

        $this->info('Monitoring completed.');
    }
}
