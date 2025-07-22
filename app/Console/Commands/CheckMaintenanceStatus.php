<?php

namespace App\Console\Commands;

use App\Models\Service;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;

class CheckMaintenanceStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'status:check-maintenance {--service=dbusworld-website}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check maintenance status for DBusWorld website using the API';

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
        $serviceSlug = $this->option('service');
        $service = Service::where('slug', $serviceSlug)->first();

        if (!$service) {
            $this->error("Service '{$serviceSlug}' not found.");
            return 1;
        }

        if (!$service->url) {
            $this->error("Service '{$serviceSlug}' has no URL configured.");
            return 1;
        }

        $this->info("Checking maintenance status for: {$service->name}");
        $this->info("URL: {$service->url}");

        $maintenanceStatus = $this->checkMaintenanceAPI($service->url);

        if ($maintenanceStatus) {
            $this->warn("🔧 MAINTENANCE DETECTED");
            $this->table(['Property', 'Value'], [
                ['Active', $maintenanceStatus['active'] ? 'Yes' : 'No'],
                ['Title', $maintenanceStatus['title'] ?? 'N/A'],
                ['Message', $maintenanceStatus['message'] ?? 'N/A'],
                ['Estimated End', $maintenanceStatus['estimated_end'] ?? 'N/A'],
                ['Time Remaining', $maintenanceStatus['time_remaining'] ?? 'N/A'],
                ['Progress', ($maintenanceStatus['progress_percentage'] ?? 0) . '%'],
            ]);

            // Update the service status
            $oldStatus = $service->status;
            $service->update([
                'status' => 'maintenance',
                'status_message' => $maintenanceStatus['message'] ?? 'Website is under maintenance'
            ]);

            $this->info("✅ Service status updated: {$oldStatus} → maintenance");
        } else {
            $this->info("✅ No maintenance detected - checking regular HTTP status...");
            
            try {
                $response = $this->httpClient->get($service->url);
                $httpStatus = $response->getStatusCode();
                $this->info("HTTP Status: {$httpStatus}");
                
                if ($httpStatus >= 200 && $httpStatus < 300) {
                    $service->update([
                        'status' => 'operational',
                        'status_message' => "Service is running normally (HTTP {$httpStatus})"
                    ]);
                    $this->info("✅ Service status updated to: operational");
                }
            } catch (GuzzleException $e) {
                $this->error("❌ HTTP check failed: " . $e->getMessage());
            }
        }

        return 0;
    }

    /**
     * Check DBusWorld maintenance API
     */
    private function checkMaintenanceAPI(string $baseUrl): ?array
    {
        try {
            $maintenanceUrl = rtrim($baseUrl, '/') . '/technical-break/status';
            $this->info("Checking maintenance API: {$maintenanceUrl}");
            
            $response = $this->httpClient->get($maintenanceUrl, [
                'timeout' => 5,
                'headers' => [
                    'Accept' => 'application/json',
                    'User-Agent' => 'DBusWorld-Status-Monitor/1.0'
                ]
            ]);

            $this->info("API Response Status: " . $response->getStatusCode());

            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody()->getContents(), true);
                $this->info("API Response: " . json_encode($data, JSON_PRETTY_PRINT));
                
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
            $this->error("❌ Could not check maintenance API: " . $e->getMessage());
            return null; // Fallback to regular HTTP check
        }
    }
}
