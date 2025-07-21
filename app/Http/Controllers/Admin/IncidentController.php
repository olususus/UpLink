<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Incident;
use App\Models\Service;
use Illuminate\Http\Request;

class IncidentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $incidents = Incident::with('service')
            ->orderBy('started_at', 'desc')
            ->paginate(20);
        
        return view('admin.incidents.index', compact('incidents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $services = Service::where('is_active', true)->get();
        return view('admin.incidents.create', compact('services'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'impact' => 'required|in:minor,major,critical',
            'status' => 'required|in:investigating,identified,monitoring,resolved',
        ]);

        $incident = Incident::create([
            'service_id' => $request->service_id,
            'title' => $request->title,
            'description' => $request->description,
            'impact' => $request->impact,
            'status' => $request->status,
            'started_at' => now(),
        ]);

        return redirect()->route('admin.incidents.index')
            ->with('success', 'Incident created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Incident $incident)
    {
        $incident->load('service');
        return view('admin.incidents.show', compact('incident'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Incident $incident)
    {
        $services = Service::where('is_active', true)->get();
        return view('admin.incidents.edit', compact('incident', 'services'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Incident $incident)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'impact' => 'required|in:minor,major,critical',
            'status' => 'required|in:investigating,identified,monitoring,resolved',
        ]);

        $incident->update($request->only([
            'service_id', 'title', 'description', 'impact', 'status'
        ]));

        return redirect()->route('admin.incidents.index')
            ->with('success', 'Incident updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Incident $incident)
    {
        $incident->delete();

        return redirect()->route('admin.incidents.index')
            ->with('success', 'Incident deleted successfully.');
    }

    /**
     * Mark incident as resolved.
     */
    public function resolve(Incident $incident)
    {
        $incident->update([
            'status' => 'resolved',
            'is_resolved' => true,
            'resolved_at' => now(),
        ]);

        return back()->with('success', 'Incident marked as resolved.');
    }
}
