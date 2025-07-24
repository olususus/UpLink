<?php

namespace App\Services;

use App\Models\Service;
use App\Models\StatusCheck;
use App\Services\AdvancedMonitoringService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SmartSchedulerService
{
    private AdvancedMonitoringService $monitoringService;

    public function __construct()
    {
        $this->monitoringService = app(AdvancedMonitoringService::class);
    }

    /**
     * Initialize monitoring for all active services
     */
    public function initializeMonitoring(): void
    {
        Log::info('Initializing smart monitoring scheduler...');

        $services = Service::where('type', 'automatic')
            ->where('is_active', true)
            ->whereNotNull('url')
            ->get();

        foreach ($services as $service) {
            $this->updateNextCheckTime($service);
        }

        Log::info("Initialized monitoring for {$services->count()} services");
    }

    /**
     * Process all services due for checking
     */
    public function processScheduledChecks(): void
    {
        $dueServices = $this->getServicesDueForCheck();
        
        Log::info("Processing {$dueServices->count()} services due for checking");
        
        foreach ($dueServices as $service) {
            $this->performServiceCheck($service);
        }
    }

    /**
     * Perform monitoring check for a specific service
     */
    public function performServiceCheck(Service $service, bool $isManual = false): void
    {
        Log::info("Monitoring service: {$service->name} " . ($isManual ? '(manual)' : '(scheduled)'));

        try {
            // Skip if service is not active
            if (!$service->is_active) {
                Log::info("Skipping inactive service: {$service->name}");
                return;
            }

            // Skip if in maintenance window (unless manual check)
            if (!$isManual && $this->isInMaintenanceWindow($service)) {
                Log::info("Skipping service in maintenance window: {$service->name}");
                return;
            }

            // Perform the monitoring check
            $result = $this->monitoringService->performCheck($service);
            
            $status = $result['status'];
            $responseTime = $result['response_time'];
            $errors = $result['errors'];
            $metadata = $result['metadata'] ?? [];

            // Create status message
            $statusMessage = '';
            if (!empty($errors)) {
                $statusMessage = implode('; ', array_slice($errors, 0, 3));
            }

            // Update service status
            $oldStatus = $service->status;
            $service->update([
                'status' => $status,
                'status_message' => $statusMessage,
                'last_checked_at' => now(),
                'next_check_at' => $this->calculateNextCheckTime($service)
            ]);

            // Log the check
            StatusCheck::create([
                'service_id' => $service->id,
                'status' => $status,
                'response_time' => $responseTime,
                'http_status' => $metadata['http_status'] ?? null,
                'error_message' => !empty($errors) ? implode('; ', $errors) : null,
                'checked_at' => now()
            ]);

            // Log status changes
            if ($oldStatus !== $status) {
                Log::warning("Status changed for {$service->name}: {$oldStatus} -> {$status}");
                if (!empty($errors)) {
                    Log::error("Errors detected: " . implode('; ', $errors));
                }
            }

            Log::info("Completed monitoring for {$service->name}: {$status}");

        } catch (\Exception $e) {
            Log::error("Failed to monitor service {$service->name}: " . $e->getMessage());
            
            // Update service with error status
            $service->update([
                'status' => 'outage',
                'status_message' => 'Monitoring error: ' . $e->getMessage(),
                'last_checked_at' => now(),
                'next_check_at' => $this->calculateNextCheckTime($service)
            ]);

            // Log error check
            StatusCheck::create([
                'service_id' => $service->id,
                'status' => 'outage',
                'response_time' => null,
                'error_message' => 'Monitoring error: ' . $e->getMessage(),
                'checked_at' => now()
            ]);
        }
    }

    /**
     * Trigger immediate manual check for a service
     */
    public function triggerManualCheck(Service $service): void
    {
        Log::info("Triggering manual check for {$service->name}");
        $this->performServiceCheck($service, true);
    }

    /**
     * Update next check time for a service
     */
    public function updateNextCheckTime(Service $service): void
    {
        $nextCheckTime = $this->calculateNextCheckTime($service);
        
        $service->update([
            'next_check_at' => $nextCheckTime
        ]);

        Log::info("Next check for {$service->name} scheduled at {$nextCheckTime->format('Y-m-d H:i:s')}");
    }

    /**
     * Get all services due for checking
     */
    public function getServicesDueForCheck(): \Illuminate\Database\Eloquent\Collection
    {
        return Service::where('type', 'automatic')
            ->where('is_active', true)
            ->whereNotNull('url')
            ->where(function ($query) {
                $query->whereNull('next_check_at')
                      ->orWhere('next_check_at', '<=', now());
            })
            ->get();
    }

    /**
     * Check if service is in maintenance window
     */
    private function isInMaintenanceWindow(Service $service): bool
    {
        $maintenanceConfig = $service->maintenance_config ?? [];
        
        if (empty($maintenanceConfig)) {
            return false;
        }

        $now = Carbon::now();
        
        // Check for active maintenance windows
        foreach ($maintenanceConfig as $window) {
            $start = Carbon::parse($window['start'] ?? null);
            $end = Carbon::parse($window['end'] ?? null);
            
            if ($start && $end && $now->between($start, $end)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Calculate next check time based on service configuration
     */
    private function calculateNextCheckTime(Service $service): Carbon
    {
        $now = Carbon::now();

        switch ($service->schedule_type ?? 'interval') {
            case 'cron':
                return $this->parseCronExpression($service, $now);
                
            case 'adaptive':
                return $this->calculateAdaptiveSchedule($service, $now);
                
            case 'interval':
            default:
                return $now->addSeconds($service->check_interval ?? 300);
        }
    }

    /**
     * Parse cron expression for scheduling
     */
    private function parseCronExpression(Service $service, Carbon $now): Carbon
    {
        $cron = $service->cron_expression;
        
        if (!$cron) {
            return $now->addSeconds($service->check_interval ?? 300);
        }

        // Simple cron parsing for common patterns
        if ($cron === '* * * * *') {
            return $now->addMinute();
        }
        
        if (str_starts_with($cron, '*/')) {
            $parts = explode(' ', $cron);
            $minute = $parts[0];
            $interval = (int)substr($minute, 2);
            return $now->addMinutes($interval);
        }

        // For more complex cron expressions, fall back to interval
        Log::warning("Complex cron expression not supported for {$service->name}, using interval");
        return $now->addSeconds($service->check_interval ?? 300);
    }

    /**
     * Calculate adaptive schedule based on service health
     */
    private function calculateAdaptiveSchedule(Service $service, Carbon $now): Carbon
    {
        // Get recent failures
        $recentFailures = StatusCheck::where('service_id', $service->id)
            ->where('checked_at', '>=', $now->copy()->subHour())
            ->where('status', '!=', 'operational')
            ->count();

        // Adjust interval based on health
        $baseInterval = $service->check_interval ?? 300;
        
        if ($recentFailures > 3) {
            // More frequent checks if failing
            $interval = max(60, $baseInterval / 4);
        } elseif ($recentFailures > 0) {
            // Slightly more frequent if some issues
            $interval = max(120, $baseInterval / 2);
        } else {
            // Normal interval if healthy
            $interval = $baseInterval;
        }

        return $now->addSeconds($interval);
    }
}
