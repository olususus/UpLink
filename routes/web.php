<?php

use App\Http\Controllers\StatusController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\IncidentController;
use App\Http\Controllers\Admin\MonitoringController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Public status page
Route::get('/', [StatusController::class, 'index'])->name('status.index');

// Authentication routes
require __DIR__.'/auth.php';

// Admin routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('services', ServiceController::class);
    Route::resource('incidents', IncidentController::class);
    Route::patch('services/{service}/status', [ServiceController::class, 'updateStatus'])->name('services.status');
    Route::patch('incidents/{incident}/resolve', [IncidentController::class, 'resolve'])->name('incidents.resolve');
    
    // Monitoring routes
    Route::get('/monitoring', [MonitoringController::class, 'index'])->name('monitoring.index');
    Route::post('/monitoring/test-api', [MonitoringController::class, 'testMaintenanceAPI'])->name('monitoring.test-api');
    Route::post('/monitoring/manual-check', [MonitoringController::class, 'manualCheck'])->name('monitoring.manual-check');
});

// Breeze dashboard route (redirect to admin)
Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
require __DIR__.'/auth.php';
