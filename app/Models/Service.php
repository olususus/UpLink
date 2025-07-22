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
        'url',
        'type',
        'status',
        'status_message',
        'is_active',
        'check_interval',
        'error_patterns',
        'http_headers',
        'timeout',
        'follow_redirects',
        'expected_status_codes',
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
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'check_interval' => 'integer',
        'error_patterns' => 'array',
        'http_headers' => 'array',
        'timeout' => 'integer',
        'follow_redirects' => 'boolean',
        'content_checks' => 'array',
        'ssl_monitoring' => 'array',
        'dns_monitoring' => 'array',
        'port_monitoring' => 'array',
        'response_time_threshold' => 'integer',
        'performance_thresholds' => 'array',
        'retry_attempts' => 'integer',
        'retry_delay' => 'integer',
        'consecutive_failures_threshold' => 'integer',
        'monitoring_regions' => 'array',
        'auth_config' => 'array',
        'custom_scripts' => 'array',
        'webhook_config' => 'array',
        'maintenance_windows' => 'array',
        'alert_escalation' => 'array',
        'notification_preferences' => 'array'
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

        if ($totalChecks === 0) {
            return 0.0;
        }

        $successfulChecks = $this->statusChecks()
            ->where('checked_at', '>=', $startDate)
            ->where('status', 'operational')
            ->count();

        return round(($successfulChecks / $totalChecks) * 100, 2);
    }
}
