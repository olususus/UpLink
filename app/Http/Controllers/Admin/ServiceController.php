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
            'description' => 'required|string',
            'url' => 'nullable|url',
            'type' => 'required|in:automatic,manual',
            'check_interval' => 'required|integer|min:60',
        ]);

        $service = Service::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'url' => $request->url,
            'type' => $request->type,
            'check_interval' => $request->check_interval,
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
            'description' => 'required|string',
            'url' => 'nullable|url',
            'type' => 'required|in:automatic,manual',
            'check_interval' => 'required|integer|min:60',
        ]);

        $service->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'url' => $request->url,
            'type' => $request->type,
            'check_interval' => $request->check_interval,
        ]);

        return redirect()->route('admin.services.index')
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
