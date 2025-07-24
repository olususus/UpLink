<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'type',
        'url',
        'status',
        'status_message',
        'is_active',
        'check_interval',
        'timeout',
        'ssl_verify',
        'expected_status_codes',
        'redirect_count',
        'content_checks',
        'headers',
        'http_method',
        'maintenance_config',
        'notification_config',
        'incident_threshold',
        'custom_endpoint',
        'last_checked_at',
        'next_check_at',
        'schedule_type',
        'cron_expression',
        'schedule_config',
        'priority',
        'response_time_threshold',
        'retry_attempts',
        'consecutive_failures_threshold',
        'ssl_monitoring',
        'auth_config',
        'maintenance_windows',
        'tags',
        'custom_fields',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'check_interval' => 'integer',
        'error_patterns' => 'array',
        'http_headers' => 'array',
        'headers' => 'array',
        'timeout' => 'integer',
        'follow_redirects' => 'boolean',
        'content_checks' => 'array',
        'ssl_monitoring' => 'array',
        'auth_config' => 'array',
        'maintenance_windows' => 'array',
        'dns_monitoring' => 'array',
        'port_monitoring' => 'array',
        'response_time_threshold' => 'integer',
        'performance_thresholds' => 'array',
        'retry_attempts' => 'integer',
        'retry_delay' => 'integer',
        'consecutive_failures_threshold' => 'integer',
        'monitoring_regions' => 'array',
        'custom_scripts' => 'array',
        'webhook_config' => 'array',
        'alert_escalation' => 'array',
        'notification_preferences' => 'array',
        'last_checked_at' => 'datetime',
        'next_check_at' => 'datetime',
        'priority' => 'integer',
        'schedule_config' => 'array',
        'tags' => 'array',
        'custom_fields' => 'array',
    ];

    public function incidents(): HasMany
    {
        return $this->hasMany(Incident::class);
    }

    public function statusChecks(): HasMany
    {
        return $this->hasMany(StatusCheck::class);
    }

    public function currentIncident()
    {
        return $this->incidents()->where('is_resolved', false)->latest()->first();
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'operational' => 'green',
            'degraded' => 'yellow',
            'maintenance' => 'blue',
            'outage' => 'red',
            default => 'gray'
        };
    }

    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'operational' => 'Operational',
            'degraded' => 'Degraded Performance',
            'maintenance' => 'Under Maintenance',
            'outage' => 'Major Outage',
            default => 'Unknown'
        };
    }

    /**
     * Calculate uptime percentage for the last N days
     */
    public function getUptimePercentage(int $days = 30): float
    {
        if (!config('status.show_uptime_percentage', false)) {
            return 0.0;
        }

        $startDate = now()->subDays($days);
        $totalChecks = $this->statusChecks()
            ->where('checked_at', '>=', $startDate)
            ->count();

        // If no checks, assume 100% uptime (new service or no data)
        if ($totalChecks === 0) {
            return 100.0;
        }

        $successfulChecks = $this->statusChecks()
            ->where('checked_at', '>=', $startDate)
            ->where('status', 'operational')
            ->count();

        return round(($successfulChecks / $totalChecks) * 100, 2);
    }
}
