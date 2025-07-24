<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class SetupController extends Controller
{
    public function index()
    {
        // If setup is complete, redirect to home
        if (env('APP_SETUP_COMPLETE', false)) {
            return redirect('/');
        }
        return view('setup.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:100',
            'admin_email' => 'required|email',
            'admin_password' => 'required|string|min:8',
            // Add more validation as needed
        ]);

        $this->updateEnv([
            'APP_NAME' => '"' . $request->app_name . '"',
            'ADMIN_EMAIL' => $request->admin_email,
            'ADMIN_PASSWORD' => $request->admin_password,
            'APP_SETUP_COMPLETE' => 'true',
        ]);

        // Run migrations and seeders if needed
        Artisan::call('migrate', ['--force' => true]);
        Artisan::call('db:seed', ['--force' => true]);

        // Optionally, create the admin user here
        // ...

        return redirect('/')->with('success', 'Setup complete!');
    }

    private function updateEnv(array $data)
    {
        $envPath = base_path('.env');
        $env = File::exists($envPath) ? File::get($envPath) : '';
        foreach ($data as $key => $value) {
            $pattern = "/^{$key}=.*$/m";
            if (preg_match($pattern, $env)) {
                $env = preg_replace($pattern, "{$key}={$value}", $env);
            } else {
                $env .= "\n{$key}={$value}";
            }
        }
        File::put($envPath, $env);
    }
}
