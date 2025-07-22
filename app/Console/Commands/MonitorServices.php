<?php

namespace App\Console\Commands;

use App\Models\Service;
use App\Models\StatusCheck;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;

class MonitorServices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'status:monitor';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Monitor automatic services and update their status';

    private Client $httpClient;

    public function __construct()
    {
        parent::__construct();
        $this->httpClient = new Client(['timeout' => 10]);
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
            $this->monitorService($service);
        }

        $this->info('Monitoring completed.');
    }

    private function monitorService(Service $service): void
    {
        try {
            $start = microtime(true);
            
            // Special handling for DBusWorld website - check maintenance API first
            if ($service->slug === 'dbusworld-website') {
                $maintenanceStatus = $this->checkMaintenanceAPI($service->url);
                if ($maintenanceStatus) {
                    $responseTime = (int)((microtime(true) - $start) * 1000);
                    
                    $oldStatus = $service->status;
                    $service->update([
                        'status' => 'maintenance',
                        'status_message' => $maintenanceStatus['message'] ?? 'Website is under maintenance'
                    ]);

                    StatusCheck::create([
                        'service_id' => $service->id,
                        'status' => 'maintenance',
                        'response_time' => $responseTime,
                        'http_status' => 503,
                        'checked_at' => now()
                    ]);

                    if ($oldStatus !== 'maintenance') {
                        $this->warn("Status changed for {$service->name}: {$oldStatus} -> maintenance");
                    }
                    return;
                }
            }
            
            // Regular HTTP status check
            $response = $this->httpClient->get($service->url);
            $responseTime = (int)((microtime(true) - $start) * 1000);

            $httpStatus = $response->getStatusCode();
            $status = $this->determineStatus($httpStatus);
            
            // Update service status
            $oldStatus = $service->status;
            $service->update([
                'status' => $status,
                'status_message' => $this->getStatusMessage($status, $httpStatus)
            ]);

            // Log the check
            StatusCheck::create([
                'service_id' => $service->id,
                'status' => $status,
                'response_time' => $responseTime,
                'http_status' => $httpStatus,
                'checked_at' => now()
            ]);

            if ($oldStatus !== $status) {
                $this->warn("Status changed for {$service->name}: {$oldStatus} -> {$status}");
            }

        } catch (GuzzleException $e) {
            $this->error("Failed to monitor {$service->name}: " . $e->getMessage());
            
            $service->update([
                'status' => 'outage',
                'status_message' => 'Service unreachable'
            ]);

            StatusCheck::create([
                'service_id' => $service->id,
                'status' => 'outage',
                'error_message' => $e->getMessage(),
                'checked_at' => now()
            ]);
        }
    }

    private function determineStatus(int $httpStatus): string
    {
        return match(true) {
            $httpStatus >= 200 && $httpStatus < 300 => 'operational',
            $httpStatus === 503 => 'maintenance', // Special case for dbusworld.com
            $httpStatus >= 400 && $httpStatus < 500 => 'degraded',
            default => 'outage'
        };
    }

    private function getStatusMessage(string $status, int $httpStatus): string
    {
        return match($status) {
            'operational' => "Service is running normally (HTTP {$httpStatus})",
            'maintenance' => 'Service is under maintenance',
            'degraded' => "Service experiencing issues (HTTP {$httpStatus})",
            'outage' => "Service is down (HTTP {$httpStatus})",
            default => 'Status unknown'
        };
    }

    /**
     * Check DBusWorld maintenance API
     */
    private function checkMaintenanceAPI(string $baseUrl): ?array
    {
        try {
            $maintenanceUrl = rtrim($baseUrl, '/') . '/technical-break/status';
            $response = $this->httpClient->get($maintenanceUrl, [
                'timeout' => 5,
                'headers' => [
                    'Accept' => 'application/json',
                    'User-Agent' => 'DBusWorld-Status-Monitor/1.0'
                ]
            ]);

            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody()->getContents(), true);
                
                if (isset($data['active']) && $data['active'] === true) {
                    return [
                        'active' => true,
                        'message' => $data['message'] ?? 'Website is under maintenance',
                        'title' => $data['title'] ?? 'Maintenance',
                        'estimated_end' => $data['estimated_end'] ?? null,
                        'time_remaining' => $data['time_remaining'] ?? null,
                        'progress_percentage' => $data['progress_percentage'] ?? 0
                    ];
                }
            }
            
            return null; // No maintenance active
            
        } catch (GuzzleException $e) {
            $this->info("Could not check maintenance API for {$baseUrl}: " . $e->getMessage());
            return null; // Fallback to regular HTTP check
        }
    }
}
