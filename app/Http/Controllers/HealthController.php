<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class HealthController extends Controller
{
    public function check(): JsonResponse
    {
        $checks = [];
        $healthy = true;

        // Database check
        try {
            DB::connection()->getPdo();
            $checks['database'] = 'healthy';
        } catch (\Exception $e) {
            $checks['database'] = 'unhealthy';
            $healthy = false;
        }

        // Check if any services are configured
        try {
            $serviceCount = Service::count();
            $checks['services_configured'] = $serviceCount > 0 ? 'healthy' : 'warning';
            $checks['services_count'] = $serviceCount;
        } catch (\Exception $e) {
            $checks['services_configured'] = 'unhealthy';
            $healthy = false;
        }

        // Check storage permissions
        $checks['storage_writable'] = is_writable(storage_path()) ? 'healthy' : 'unhealthy';
        if (!is_writable(storage_path())) {
            $healthy = false;
        }

        return response()->json([
            'status' => $healthy ? 'healthy' : 'unhealthy',
            'timestamp' => now()->toISOString(),
            'checks' => $checks,
            'version' => config('app.version', '1.0.0'),
        ], $healthy ? 200 : 503);
    }
}
