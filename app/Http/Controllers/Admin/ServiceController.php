<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = Service::withCount(['incidents', 'statusChecks'])->get();
        return view('admin.services.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.services.create');
    }

    /**
     * Show the advanced form for creating a new resource.
     */
    public function advancedCreate()
    {
        return view('admin.services.advanced-create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'url' => 'nullable|url',
            'type' => 'required|in:automatic,manual',
            'status' => 'required|in:operational,degraded,maintenance,outage',
            'status_message' => 'nullable|string|max:255',
            'check_interval' => 'required|integer|min:60|max:3600',
            'is_active' => 'nullable|boolean',
            'forbidden_text' => 'nullable|string',
            'required_text' => 'nullable|string',
            'timeout' => 'nullable|integer|min:1|max:60',
            'expected_status_codes' => 'nullable|string|max:100',
            'ssl_verify' => 'nullable|boolean',
            'headers' => 'nullable|array',
            'headers.*.key' => 'required_with:headers.*|string|max:100',
            'headers.*.value' => 'required_with:headers.*|string|max:500',
            'response_time_threshold' => 'nullable|integer|min:100|max:30000',
            'retry_attempts' => 'nullable|integer|min:0|max:3',
            'consecutive_failures_threshold' => 'nullable|integer|min:1|max:10',
            'ssl_enabled' => 'nullable|boolean',
            'ssl_check_expiry' => 'nullable|boolean',
            'ssl_warning_days' => 'nullable|integer|min:1|max:365',
            'auth_type' => 'nullable|in:none,basic,bearer,api_key',
            'auth_username' => 'nullable|string|max:100',
            'auth_password' => 'nullable|string|max:255',
            'auth_token' => 'nullable|string|max:1000',
            'auth_key' => 'nullable|string|max:100',
            'auth_value' => 'nullable|string|max:1000',
            'enable_maintenance_windows' => 'nullable|boolean',
            'maintenance_windows' => 'nullable|array',
            'maintenance_windows.*.day' => 'required_with:maintenance_windows.*|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'maintenance_windows.*.start' => 'required_with:maintenance_windows.*|date_format:H:i',
            'maintenance_windows.*.end' => 'required_with:maintenance_windows.*|date_format:H:i',
        ]);

        // Generate slug from name
        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $counter = 1;
        
        while (Service::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Process content checks
        $contentChecks = [];
        
        if ($request->forbidden_text) {
            $forbiddenLines = array_filter(array_map('trim', explode("\n", $request->forbidden_text)));
            if (!empty($forbiddenLines)) {
                $contentChecks['forbidden_text'] = $forbiddenLines;
            }
        }
        
        if ($request->required_text) {
            $requiredLines = array_filter(array_map('trim', explode("\n", $request->required_text)));
            if (!empty($requiredLines)) {
                $contentChecks['required_text'] = $requiredLines;
            }
        }

        // Process headers
        $headers = [];
        if ($request->has('headers') && is_array($request->headers)) {
            foreach ($request->headers as $header) {
                if (!empty($header['key']) && !empty($header['value'])) {
                    $headers[$header['key']] = $header['value'];
                }
            }
        }

        // Process SSL monitoring
        $sslMonitoring = [];
        if ($request->boolean('ssl_enabled')) {
            $sslMonitoring = [
                'enabled' => true,
                'check_expiry' => $request->boolean('ssl_check_expiry'),
                'warning_days' => $request->ssl_warning_days ?? 30,
            ];
        }

        // Process authentication
        $authConfig = [];
        if ($request->auth_type && $request->auth_type !== 'none') {
            $authConfig['type'] = $request->auth_type;
            
            switch ($request->auth_type) {
                case 'basic':
                    if ($request->auth_username && $request->auth_password) {
                        $authConfig['username'] = $request->auth_username;
                        $authConfig['password'] = $request->auth_password;
                    }
                    break;
                case 'bearer':
                    if ($request->auth_token) {
                        $authConfig['token'] = $request->auth_token;
                    }
                    break;
                case 'api_key':
                    if ($request->auth_key && $request->auth_value) {
                        $authConfig['key'] = $request->auth_key;
                        $authConfig['value'] = $request->auth_value;
                    }
                    break;
            }
        }

        // Process maintenance windows
        $maintenanceWindows = [];
        if ($request->boolean('enable_maintenance_windows') && $request->has('maintenance_windows')) {
            $maintenanceWindows = array_values($request->maintenance_windows);
        }

        $service = Service::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'url' => $request->url,
            'type' => $request->type,
            'status' => $request->status,
            'status_message' => $request->status_message,
            'check_interval' => $request->check_interval,
            'is_active' => $request->boolean('is_active', true),
            'content_checks' => !empty($contentChecks) ? $contentChecks : null,
            'timeout' => $request->timeout ?? 10,
            'expected_status_codes' => $request->expected_status_codes ?? '200-299',
            'ssl_verify' => $request->boolean('ssl_verify', true),
            'headers' => !empty($headers) ? $headers : null,
            'response_time_threshold' => $request->response_time_threshold ?? 5000,
            'retry_attempts' => $request->retry_attempts ?? 1,
            'consecutive_failures_threshold' => $request->consecutive_failures_threshold ?? 2,
            'ssl_monitoring' => !empty($sslMonitoring) ? $sslMonitoring : null,
            'auth_config' => !empty($authConfig) ? $authConfig : null,
            'maintenance_windows' => !empty($maintenanceWindows) ? $maintenanceWindows : null,
        ]);

        return redirect()->route('admin.services.index')
            ->with('success', 'Service created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        $service->load(['incidents' => function($query) {
            $query->orderBy('started_at', 'desc')->limit(10);
        }, 'statusChecks' => function($query) {
            $query->orderBy('checked_at', 'desc')->limit(20);
        }]);

        return view('admin.services.show', compact('service'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'url' => 'nullable|url',
            'type' => 'required|in:automatic,manual',
            'status' => 'required|in:operational,degraded,maintenance,outage',
            'status_message' => 'nullable|string|max:255',
            'check_interval' => 'required|integer|min:60|max:3600',
            'is_active' => 'nullable|boolean',
            'forbidden_text' => 'nullable|string',
            'required_text' => 'nullable|string',
            'timeout' => 'nullable|integer|min:1|max:60',
            'expected_status_codes' => 'nullable|string|max:100',
            'ssl_verify' => 'nullable|boolean',
            'headers' => 'nullable|array',
            'headers.*.key' => 'required_with:headers.*|string|max:100',
            'headers.*.value' => 'required_with:headers.*|string|max:500',
            'response_time_threshold' => 'nullable|integer|min:100|max:30000',
            'retry_attempts' => 'nullable|integer|min:0|max:3',
            'consecutive_failures_threshold' => 'nullable|integer|min:1|max:10',
            'ssl_enabled' => 'nullable|boolean',
            'ssl_check_expiry' => 'nullable|boolean',
            'ssl_warning_days' => 'nullable|integer|min:1|max:365',
            'auth_type' => 'nullable|in:none,basic,bearer,api_key',
            'auth_username' => 'nullable|string|max:100',
            'auth_password' => 'nullable|string|max:255',
            'auth_token' => 'nullable|string|max:1000',
            'auth_key' => 'nullable|string|max:100',
            'auth_value' => 'nullable|string|max:1000',
            'enable_maintenance_windows' => 'nullable|boolean',
            'maintenance_windows' => 'nullable|array',
            'maintenance_windows.*.day' => 'required_with:maintenance_windows.*|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'maintenance_windows.*.start' => 'required_with:maintenance_windows.*|date_format:H:i',
            'maintenance_windows.*.end' => 'required_with:maintenance_windows.*|date_format:H:i',
        ]);

        // Process content checks
        $contentChecks = [];
        
        if ($request->forbidden_text) {
            $forbiddenLines = array_filter(array_map('trim', explode("\n", $request->forbidden_text)));
            if (!empty($forbiddenLines)) {
                $contentChecks['forbidden_text'] = $forbiddenLines;
            }
        }
        
        if ($request->required_text) {
            $requiredLines = array_filter(array_map('trim', explode("\n", $request->required_text)));
            if (!empty($requiredLines)) {
                $contentChecks['required_text'] = $requiredLines;
            }
        }

        // Process headers
        $headers = [];
        if ($request->has('headers') && is_array($request->headers)) {
            foreach ($request->headers as $header) {
                if (!empty($header['key']) && !empty($header['value'])) {
                    $headers[$header['key']] = $header['value'];
                }
            }
        }

        // Process SSL monitoring
        $sslMonitoring = [];
        if ($request->boolean('ssl_enabled')) {
            $sslMonitoring = [
                'enabled' => true,
                'check_expiry' => $request->boolean('ssl_check_expiry'),
                'warning_days' => $request->ssl_warning_days ?? 30,
            ];
        }

        // Process authentication
        $authConfig = [];
        if ($request->auth_type && $request->auth_type !== 'none') {
            $authConfig['type'] = $request->auth_type;
            
            switch ($request->auth_type) {
                case 'basic':
                    if ($request->auth_username && $request->auth_password) {
                        $authConfig['username'] = $request->auth_username;
                        $authConfig['password'] = $request->auth_password;
                    }
                    break;
                case 'bearer':
                    if ($request->auth_token) {
                        $authConfig['token'] = $request->auth_token;
                    }
                    break;
                case 'api_key':
                    if ($request->auth_key && $request->auth_value) {
                        $authConfig['key'] = $request->auth_key;
                        $authConfig['value'] = $request->auth_value;
                    }
                    break;
            }
        }

        // Process maintenance windows
        $maintenanceWindows = [];
        if ($request->boolean('enable_maintenance_windows') && $request->has('maintenance_windows')) {
            $maintenanceWindows = array_values($request->maintenance_windows);
        }

        $service->update([
            'name' => $request->name,
            'description' => $request->description,
            'url' => $request->url,
            'type' => $request->type,
            'status' => $request->status,
            'status_message' => $request->status_message,
            'check_interval' => $request->check_interval,
            'is_active' => $request->boolean('is_active', true),
            'content_checks' => !empty($contentChecks) ? $contentChecks : null,
            'timeout' => $request->timeout ?? 10,
            'expected_status_codes' => $request->expected_status_codes ?? '200-299',
            'ssl_verify' => $request->boolean('ssl_verify', true),
            'headers' => !empty($headers) ? $headers : null,
            'response_time_threshold' => $request->response_time_threshold ?? 5000,
            'retry_attempts' => $request->retry_attempts ?? 1,
            'consecutive_failures_threshold' => $request->consecutive_failures_threshold ?? 2,
            'ssl_monitoring' => !empty($sslMonitoring) ? $sslMonitoring : null,
            'auth_config' => !empty($authConfig) ? $authConfig : null,
            'maintenance_windows' => !empty($maintenanceWindows) ? $maintenanceWindows : null,
        ]);

        return redirect()->route('admin.services.show', $service)
            ->with('success', 'Service updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->route('admin.services.index')
            ->with('success', 'Service deleted successfully.');
    }

    /**
     * Update service status manually.
     */
    public function updateStatus(Request $request, Service $service)
    {
        $request->validate([
            'status' => 'required|in:operational,degraded,maintenance,outage',
            'status_message' => 'nullable|string|max:255',
        ]);

        $service->update([
            'status' => $request->status,
            'status_message' => $request->status_message,
        ]);

        return back()->with('success', 'Service status updated successfully.');
    }
}
