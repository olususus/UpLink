<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Incident;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function index()
    {
        try {
            $services = Service::where('is_active', true)
                ->orderBy('id')
                ->get();

            $activeIncidents = Incident::where('is_resolved', false)
                ->with('service')
                ->orderBy('started_at', 'desc')
                ->get();

            $pastIncidents = Incident::where('is_resolved', true)
                ->with('service')
                ->orderBy('resolved_at', 'desc')
                ->limit(10)
                ->get();

            $overallStatus = $this->calculateOverallStatus($services);

            return view('status.index', compact('services', 'activeIncidents', 'pastIncidents', 'overallStatus'));
        } catch (\Exception $e) {
            // Fallback for when database isn't set up yet
            $services = collect([]);
            $activeIncidents = collect([]);
            $pastIncidents = collect([]);
            $overallStatus = 'operational';
            
            return view('status.index', compact('services', 'activeIncidents', 'pastIncidents', 'overallStatus'))
                ->with('error', 'Database not configured yet. Please run migrations: php artisan migrate --seed');
        }
    }

    private function calculateOverallStatus($services)
    {
        if ($services->isEmpty()) {
            return 'operational';
        }

        $statuses = $services->pluck('status');

        if ($statuses->contains('outage')) {
            return 'outage';
        }

        if ($statuses->contains('maintenance')) {
            return 'maintenance';
        }

        if ($statuses->contains('degraded')) {
            return 'degraded';
        }

        return 'operational';
    }
}
