<?php

namespace App\Console\Commands;

use App\Models\Service;
use App\Models\StatusCheck;
use App\Mail\ServiceStatusChanged;
use App\Services\DiscordNotificationService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class MonitorServices extends Command
{
    protected $signature = 'status:monitor';
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
            
            // Build HTTP options
            $options = [
                'timeout' => $service->timeout ?? 10,
                'allow_redirects' => $service->follow_redirects ?? true,
                'headers' => $service->http_headers ?? []
            ];
            
            // Regular HTTP status check
            $response = $this->httpClient->get($service->url, $options);
            $responseTime = (int)((microtime(true) - $start) * 1000);

            $httpStatus = $response->getStatusCode();
            $responseBody = $response->getBody()->getContents();
            
            // Check for expected status codes
            $expectedCodes = $this->parseStatusCodes($service->expected_status_codes ?? '200-299');
            $hasValidStatus = in_array($httpStatus, $expectedCodes);
            
            // Check for error patterns in response body
            $errorDetected = $this->checkForErrorPatterns($responseBody, $service->error_patterns ?? []);
            
            // Determine final status
            $status = $this->determineServiceStatus($httpStatus, $hasValidStatus, $errorDetected);
            $statusMessage = $this->getStatusMessage($status, $httpStatus, $errorDetected);
            
            // Update service status and send notifications if changed
            $oldStatus = $service->status;
            $this->updateServiceStatus($service, $status, $statusMessage);

            // Log the check
            StatusCheck::create([
                'service_id' => $service->id,
                'status' => $status,
                'response_time' => $responseTime,
                'http_status' => $httpStatus,
                'error_message' => $errorDetected ? 'Error pattern detected in response' : null,
                'checked_at' => now()
            ]);

            if ($oldStatus !== $status) {
                $this->warn("Status changed for {$service->name}: {$oldStatus} -> {$status}");
            }

        } catch (GuzzleException $e) {
            $this->error("Failed to monitor {$service->name}: " . $e->getMessage());
            
            $this->updateServiceStatus($service, 'outage', 'Service unreachable');

            StatusCheck::create([
                'service_id' => $service->id,
                'status' => 'outage',
                'error_message' => $e->getMessage(),
                'checked_at' => now()
            ]);
        }
    }

    /**
     * Update service status and send notification if status changed
     */
    private function updateServiceStatus(Service $service, string $newStatus, string $statusMessage = ''): void
    {
        $previousStatus = $service->status;
        
        $service->update([
            'status' => $newStatus,
            'status_message' => $statusMessage
        ]);

        // Send notification if status changed and notifications are enabled
        if ($previousStatus !== $newStatus && config('status.enable_email_notifications', false)) {
            $this->sendStatusChangeNotification($service, $previousStatus, $newStatus);
        }
    }

    private function sendStatusChangeNotification(Service $service, string $previousStatus, string $currentStatus): void
    {
        try {
            $notificationEmail = config('status.notification_email');
            
            if ($notificationEmail && config('status.enable_email_notifications', false)) {
                Mail::to($notificationEmail)->send(
                    new ServiceStatusChanged($service, $previousStatus, $currentStatus)
                );
                
                $this->info("Email notification sent for {$service->name} status change: {$previousStatus} → {$currentStatus}");
            }
        } catch (\Exception $e) {
            $this->error("Failed to send email notification for {$service->name}: " . $e->getMessage());
        }

        // Send Discord notification
        try {
            if (config('status.notifications.discord_enabled', false)) {
                $discordService = new DiscordNotificationService();
                $success = $discordService->sendStatusChangeNotification($service, $previousStatus, $currentStatus);
                
                if ($success) {
                    $this->info("Discord notification sent for {$service->name} status change: {$previousStatus} → {$currentStatus}");
                } else {
                    $this->warn("Discord notification failed for {$service->name}");
                }
            }
        } catch (\Exception $e) {
            $this->error("Failed to send Discord notification for {$service->name}: " . $e->getMessage());
        }
    }

    private function parseStatusCodes(string $statusCodes): array
    {
        $codes = [];
        $ranges = explode(',', $statusCodes);
        
        foreach ($ranges as $range) {
            $range = trim($range);
            if (strpos($range, '-') !== false) {
                [$start, $end] = explode('-', $range);
                for ($i = (int)$start; $i <= (int)$end; $i++) {
                    $codes[] = $i;
                }
            } else {
                $codes[] = (int)$range;
            }
        }
        
        return $codes;
    }

    private function checkForErrorPatterns(string $responseBody, array $patterns): bool
    {
        if (empty($patterns)) {
            return false;
        }
        
        foreach ($patterns as $pattern) {
            if (empty($pattern)) continue;
            
            // Support both regex and plain text patterns
            if (str_starts_with($pattern, '/') && str_ends_with($pattern, '/')) {
                // Regex pattern
                if (preg_match($pattern, $responseBody)) {
                    return true;
                }
            } else {
                // Plain text pattern (case-insensitive)
                if (stripos($responseBody, $pattern) !== false) {
                    return true;
                }
            }
        }
        
        return false;
    }

    private function determineServiceStatus(int $httpStatus, bool $hasValidStatus, bool $errorDetected): string
    {
        if ($errorDetected) {
            return 'degraded';
        }
        
        if (!$hasValidStatus) {
            return match(true) {
                $httpStatus === 503 => 'maintenance',
                $httpStatus >= 500 => 'outage',
                $httpStatus >= 400 => 'degraded',
                default => 'degraded'
            };
        }
        
        return 'operational';
    }

    private function getStatusMessage(string $status, int $httpStatus, bool $errorDetected = false): string
    {
        if ($errorDetected) {
            return "Error pattern detected in response (HTTP {$httpStatus})";
        }
        
        return match($status) {
            'operational' => "Service is running normally (HTTP {$httpStatus})",
            'maintenance' => 'Service is under maintenance',
            'degraded' => "Service experiencing issues (HTTP {$httpStatus})",
            'outage' => "Service is down (HTTP {$httpStatus})",
            default => 'Status unknown'
        };
    }
}
