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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:services,slug',
            'description' => 'nullable|string',
            'url' => 'nullable|url',
            'type' => 'required|in:automatic,manual',
            'status' => 'required|in:operational,degraded,maintenance,outage',
            'status_message' => 'nullable|string|max:255',
            'check_interval' => 'required|integer|min:60|max:3600',
            'timeout' => 'nullable|integer|min:1|max:60',
            'expected_status_codes' => 'nullable|string|max:255',
            'follow_redirects' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'error_patterns' => 'nullable|array',
            'error_patterns.*' => 'nullable|string|max:500',
            'http_headers_keys' => 'nullable|array',
            'http_headers_keys.*' => 'nullable|string|max:255',
            'http_headers_values' => 'nullable|array',
            'http_headers_values.*' => 'nullable|string|max:500',
        ]);

        // Process HTTP headers
        $httpHeaders = [];
        if ($request->has('http_headers_keys') && $request->has('http_headers_values')) {
            $keys = array_filter($request->http_headers_keys ?? []);
            $values = array_filter($request->http_headers_values ?? []);
            
            foreach ($keys as $index => $key) {
                if (!empty($key) && isset($values[$index])) {
                    $httpHeaders[$key] = $values[$index];
                }
            }
        }

        // Process error patterns
        $errorPatterns = array_filter($request->error_patterns ?? []);

        $service = Service::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'url' => $request->url,
            'type' => $request->type,
            'status' => $request->status,
            'status_message' => $request->status_message,
            'check_interval' => $request->check_interval,
            'timeout' => $request->timeout ?? 10,
            'expected_status_codes' => $request->expected_status_codes ?? '200-299',
            'follow_redirects' => $request->boolean('follow_redirects', true),
            'is_active' => $request->boolean('is_active', true),
            'error_patterns' => $errorPatterns,
            'http_headers' => $httpHeaders,
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
            'slug' => 'required|string|max:255|unique:services,slug,' . $service->id,
            'description' => 'nullable|string',
            'url' => 'nullable|url',
            'type' => 'required|in:automatic,manual',
            'status' => 'required|in:operational,degraded,maintenance,outage',
            'status_message' => 'nullable|string|max:255',
            'check_interval' => 'required|integer|min:60|max:3600',
            'timeout' => 'nullable|integer|min:1|max:60',
            'expected_status_codes' => 'nullable|string|max:255',
            'follow_redirects' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'error_patterns' => 'nullable|array',
            'error_patterns.*' => 'nullable|string|max:500',
            'http_headers_keys' => 'nullable|array',
            'http_headers_keys.*' => 'nullable|string|max:255',
            'http_headers_values' => 'nullable|array',
            'http_headers_values.*' => 'nullable|string|max:500',
        ]);

        // Process HTTP headers
        $httpHeaders = [];
        if ($request->has('http_headers_keys') && $request->has('http_headers_values')) {
            $keys = array_filter($request->http_headers_keys ?? []);
            $values = array_filter($request->http_headers_values ?? []);
            
            foreach ($keys as $index => $key) {
                if (!empty($key) && isset($values[$index])) {
                    $httpHeaders[$key] = $values[$index];
                }
            }
        }

        // Process error patterns
        $errorPatterns = array_filter($request->error_patterns ?? []);

        $service->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'url' => $request->url,
            'type' => $request->type,
            'status' => $request->status,
            'status_message' => $request->status_message,
            'check_interval' => $request->check_interval,
            'timeout' => $request->timeout ?? 10,
            'expected_status_codes' => $request->expected_status_codes ?? '200-299',
            'follow_redirects' => $request->boolean('follow_redirects', true),
            'is_active' => $request->boolean('is_active', true),
            'error_patterns' => $errorPatterns,
            'http_headers' => $httpHeaders,
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
