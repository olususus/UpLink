<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StatusCheck;
use App\Models\Service;
use App\Services\AdvancedMonitoringService;
use App\Services\SmartSchedulerService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    /**
     * Show monitoring dashboard with recent checks
     */
    public function index()
    {
        $recentChecks = StatusCheck::with('service')
            ->orderBy('checked_at', 'desc')
            ->limit(50)
            ->get();

        $services = Service::where('is_active', true)->get();

        return view('admin.monitoring.index', compact('recentChecks', 'services'));
    }

    /**
     * Test maintenance API for a specific service
     */
    public function testMaintenanceAPI(Request $request)
    {
        $service = Service::findOrFail($request->service_id);
        
        if (!$service->url) {
            return response()->json(['error' => 'Service has no URL configured'], 400);
        }

        $client = new Client([
            'timeout' => 10,
            'verify' => false, // Disable SSL verification for development
            'curl' => [
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
            ]
        ]);
        $result = [
            'service' => $service->name,
            'base_url' => $service->url,
            'timestamp' => now()->toISOString(),
        ];

        try {
            // Test maintenance API
            $maintenanceUrl = rtrim($service->url, '/') . '/technical-break/status';
            $result['maintenance_api_url'] = $maintenanceUrl;

            try {
                $response = $client->get($maintenanceUrl, [
                    'timeout' => 5,
                    'verify' => false,
                    'curl' => [
                        CURLOPT_SSL_VERIFYPEER => false,
                        CURLOPT_SSL_VERIFYHOST => false,
                    ],
                    'headers' => [
                        'Accept' => 'application/json',
                        'User-Agent' => 'DBusWorld-Status-Monitor/1.0'
                    ]
                ]);

                $result['maintenance_api'] = [
                    'status_code' => $response->getStatusCode(),
                    'success' => true,
                    'response' => json_decode($response->getBody()->getContents(), true),
                    'error' => null
                ];

                // Determine if maintenance is active
                $data = $result['maintenance_api']['response'];
                $result['maintenance_active'] = isset($data['active']) && $data['active'] === true;
                
            } catch (GuzzleException $e) {
                $result['maintenance_api'] = [
                    'status_code' => null,
                    'success' => false,
                    'response' => null,
                    'error' => $e->getMessage()
                ];
                $result['maintenance_active'] = false;
            }

            // Test main URL if maintenance is not active
            if (!$result['maintenance_active']) {
                try {
                    $response = $client->get($service->url);
                    $result['main_url'] = [
                        'status_code' => $response->getStatusCode(),
                        'success' => true,
                        'error' => null
                    ];
                } catch (GuzzleException $e) {
                    $result['main_url'] = [
                        'status_code' => null,
                        'success' => false,
                        'error' => $e->getMessage()
                    ];
                }
            }

            // Determine final status
            if ($result['maintenance_active']) {
                $result['determined_status'] = 'maintenance';
                $result['status_reason'] = 'Maintenance API reports active maintenance';
            } elseif (isset($result['main_url'])) {
                $httpStatus = $result['main_url']['status_code'];
                if ($httpStatus >= 200 && $httpStatus < 300) {
                    $result['determined_status'] = 'operational';
                    $result['status_reason'] = "HTTP {$httpStatus} - Service operational";
                } elseif ($httpStatus === 503) {
                    $result['determined_status'] = 'maintenance';
                    $result['status_reason'] = "HTTP 503 - Service under maintenance";
                } elseif ($httpStatus >= 400 && $httpStatus < 500) {
                    $result['determined_status'] = 'degraded';
                    $result['status_reason'] = "HTTP {$httpStatus} - Service experiencing issues";
                } else {
                    $result['determined_status'] = 'outage';
                    $result['status_reason'] = "HTTP {$httpStatus} - Service down";
                }
            } else {
                $result['determined_status'] = 'unknown';
                $result['status_reason'] = 'Could not determine status';
            }

        } catch (\Exception $e) {
            $result['error'] = $e->getMessage();
            $result['determined_status'] = 'error';
            $result['status_reason'] = 'Unexpected error occurred';
        }

        return response()->json($result);
    }

    /**
     * Run a manual check for a service
     */
    public function manualCheck(Request $request, SmartSchedulerService $scheduler)
    {
        $service = Service::findOrFail($request->service_id);
        
        if ($service->type !== 'automatic' || !$service->url) {
            return response()->json(['error' => 'Service is not configured for automatic monitoring'], 400);
        }

        try {
            // Use the smart scheduler for manual checks
            $scheduler->triggerManualCheck($service);

            return response()->json([
                'success' => true,
                'status' => 'triggered',
                'message' => 'Manual check triggered successfully. Check will be processed ' . 
                           ($service->use_queue ? 'in background queue' : 'immediately'),
                'queued' => $service->use_queue
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => 'Failed to trigger manual check: ' . $e->getMessage()
            ], 500);
        }
    }

    private function checkMaintenanceAPI(string $baseUrl, Client $client): ?array
    {
        try {
            $maintenanceUrl = rtrim($baseUrl, '/') . '/technical-break/status';
            $response = $client->get($maintenanceUrl, [
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
            
            return null;
            
        } catch (GuzzleException $e) {
            return null;
        }
    }
}
