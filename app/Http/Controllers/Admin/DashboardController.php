<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Incident;
use App\Models\StatusCheck;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $services = Service::withCount(['incidents', 'statusChecks'])->get();
        $activeIncidents = Incident::where('is_resolved', false)->with('service')->count();
        $totalServices = Service::count();
        $totalIncidents = Incident::count();
        $recentChecks = StatusCheck::with('service')
            ->orderBy('checked_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'services',
            'activeIncidents', 
            'totalServices',
            'totalIncidents',
            'recentChecks'
        ));
    }
}
