<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ExampleServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Only seed if no services exist (fresh installation)
        if (Service::count() > 0) {
            return;
        }

        // Example website monitoring
        Service::create([
            'name' => 'Main Website',
            'slug' => 'main-website',
            'description' => 'Company main website monitoring',
            'url' => 'https://httpstat.us/200',
            'type' => 'automatic',
            'status' => 'operational',
            'status_message' => 'All systems operational',
            'check_interval' => 300,
            'timeout' => 10,
            'expected_status_codes' => '200-299,301,302',
            'follow_redirects' => true,
            'is_active' => true,
            'error_patterns' => [],
            'http_headers' => [],
        ]);

        // Example API monitoring with authentication
        Service::create([
            'name' => 'User API',
            'slug' => 'user-api',
            'description' => 'REST API for user management',
            'url' => 'https://jsonplaceholder.typicode.com/users/1',
            'type' => 'automatic',
            'status' => 'operational',
            'status_message' => 'API responding normally',
            'check_interval' => 180,
            'timeout' => 15,
            'expected_status_codes' => '200',
            'follow_redirects' => true,
            'is_active' => true,
            'error_patterns' => ['error', 'failed', 'unavailable'],
            'http_headers' => [
                'User-Agent' => 'StatusMonitor/1.0',
                'Accept' => 'application/json'
            ],
        ]);

        // Example manual service
        Service::create([
            'name' => 'Database Cluster',
            'slug' => 'database-cluster',
            'description' => 'Primary database cluster status',
            'url' => null,
            'type' => 'manual',
            'status' => 'operational',
            'status_message' => 'All database nodes healthy',
            'check_interval' => 300,
            'is_active' => true,
            'error_patterns' => [],
            'http_headers' => [],
        ]);

        // Example service with error patterns
        Service::create([
            'name' => 'External Payment Gateway',
            'slug' => 'payment-gateway',
            'description' => 'Third-party payment processing service',
            'url' => 'https://httpstat.us/503',
            'type' => 'automatic',
            'status' => 'maintenance',
            'status_message' => 'Scheduled maintenance in progress',
            'check_interval' => 600,
            'timeout' => 20,
            'expected_status_codes' => '200,202',
            'follow_redirects' => false,
            'is_active' => false, // Disabled for demo
            'error_patterns' => [
                'maintenance',
                'temporarily unavailable',
                '/5\d{2}.*server.*error/i'
            ],
            'http_headers' => [
                'Authorization' => 'Bearer demo-token-12345',
                'Content-Type' => 'application/json'
            ],
        ]);
    }
}
