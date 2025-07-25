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
            // Check if database tables exist
            if (!\Schema::hasTable('services')) {
                return $this->showSetupPage('Database tables not found. Please run migrations.');
            }

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

            return view('status.index', compact('services', 'activeIncidents', 'pastIncidents', 'overallStatus'))
                ->with('supportEmail', function_exists('support_email') ? support_email() : config('status.support_email'));
            
        } catch (\Exception $e) {
            // Fallback for any database errors
            return $this->showSetupPage('Database connection failed: ' . $e->getMessage());
        }
    }

    private function showSetupPage($message)
    {
        $services = collect([]);
        $activeIncidents = collect([]);
        $pastIncidents = collect([]);
        $overallStatus = 'operational';
        
        return view('status.index', compact('services', 'activeIncidents', 'pastIncidents', 'overallStatus'))
            ->with('error', $message)
            ->with('supportEmail', function_exists('support_email') ? support_email() : config('status.support_email'));
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
