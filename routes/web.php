// Fallback route for 404 errors
use Illuminate\Support\Facades\View;
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
<?php

use App\Http\Controllers\StatusController;
use App\Http\Controllers\SetupController;
use App\Http\Controllers\HealthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\IncidentController;
use App\Http\Controllers\Admin\MonitoringController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;




// Public status page
Route::get('/', [StatusController::class, 'index'])->name('status.index');

// Credits page
Route::view('/credits', 'credits')->name('credits');

// Health check endpoint
Route::get('/health', [HealthController::class, 'check'])->name('health.check');

// Authentication routes
require __DIR__.'/auth.php';

// Admin routes
Route::middleware(['auth', 'role:administrator,service_manager,status_manager,incident_creator'])->prefix('admin')->name('admin.')->group(function () {
    // User management (admin only)
    Route::middleware('role:administrator')->group(function () {
        Route::post('/users', [\App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
        // Settings routes
        Route::get('/settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    });
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Services routes (custom routes before resource)
    Route::get('/services/advanced-create', [ServiceController::class, 'advancedCreate'])->name('services.advanced-create');
    // Override the default create route to use advancedCreate (fixes missing view error)
    Route::get('/services/create', [ServiceController::class, 'advancedCreate'])->name('services.create');
    Route::get('/services/advanced-create-json', [ServiceController::class, 'advancedCreateJson'])->name('services.advanced-create-json');
    Route::resource('services', ServiceController::class);
    Route::patch('services/{service}/status', [ServiceController::class, 'updateStatus'])->name('services.status');
    Route::resource('incidents', IncidentController::class);
    Route::patch('incidents/{incident}/resolve', [IncidentController::class, 'resolve'])->name('incidents.resolve');
    // Monitoring routes
    Route::get('/monitoring', [MonitoringController::class, 'index'])->name('monitoring.index');
    Route::post('/monitoring/test-api', [MonitoringController::class, 'testMaintenanceAPI'])->name('monitoring.test-api');
    Route::post('/monitoring/manual-check', [MonitoringController::class, 'manualCheck'])->name('monitoring.manual-check');
    // Notification routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/test-discord', [NotificationController::class, 'testDiscord'])->name('notifications.test-discord');
    Route::patch('/notifications/settings', [NotificationController::class, 'updateSettings'])->name('notifications.update-settings');

    // Analytics route
    Route::get('/analytics', function () {
        $services = \App\Models\Service::withCount(['incidents', 'statusChecks'])->get();
        return view('admin.services.analytics', compact('services'));
    })->name('analytics');
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
