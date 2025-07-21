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
        'check_interval'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'check_interval' => 'integer'
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
}
