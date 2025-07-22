<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            // Content-based monitoring
            $table->json('content_checks')->nullable()->after('expected_status_codes');
            $table->json('ssl_monitoring')->nullable()->after('content_checks');
            $table->json('dns_monitoring')->nullable()->after('ssl_monitoring');
            $table->json('port_monitoring')->nullable()->after('dns_monitoring');
            
            // Response time and performance
            $table->integer('response_time_threshold')->default(5000)->after('port_monitoring');
            $table->json('performance_thresholds')->nullable()->after('response_time_threshold');
            
            // Retry and failure handling
            $table->integer('retry_attempts')->default(3)->after('performance_thresholds');
            $table->integer('retry_delay')->default(5)->after('retry_attempts');
            $table->integer('consecutive_failures_threshold')->default(3)->after('retry_delay');
            
            // Geographic monitoring
            $table->json('monitoring_regions')->nullable()->after('consecutive_failures_threshold');
            
            // Authentication
            $table->json('auth_config')->nullable()->after('monitoring_regions');
            
            // Custom scripts and webhooks
            $table->json('custom_scripts')->nullable()->after('auth_config');
            $table->json('webhook_config')->nullable()->after('custom_scripts');
            
            // Maintenance windows
            $table->json('maintenance_windows')->nullable()->after('webhook_config');
            
            // Advanced alerting
            $table->json('alert_escalation')->nullable()->after('maintenance_windows');
            $table->json('notification_preferences')->nullable()->after('alert_escalation');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn([
                'content_checks',
                'ssl_monitoring',
                'dns_monitoring', 
                'port_monitoring',
                'response_time_threshold',
                'performance_thresholds',
                'retry_attempts',
                'retry_delay',
                'consecutive_failures_threshold',
                'monitoring_regions',
                'auth_config',
                'custom_scripts',
                'webhook_config',
                'maintenance_windows',
                'alert_escalation',
                'notification_preferences'
            ]);
        });
    }
};
