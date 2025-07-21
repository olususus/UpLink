<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Incident extends Model
{
    protected $fillable = [
        'service_id',
        'title',
        'description',
        'status',
        'impact',
        'started_at',
        'resolved_at',
        'is_resolved'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'resolved_at' => 'datetime',
        'is_resolved' => 'boolean'
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function getImpactColorAttribute(): string
    {
        return match($this->impact) {
            'minor' => 'yellow',
            'major' => 'orange',
            'critical' => 'red',
            default => 'gray'
        };
    }

    public function getImpactTextAttribute(): string
    {
        return match($this->impact) {
            'minor' => 'Minor',
            'major' => 'Major',
            'critical' => 'Critical',
            default => 'Unknown'
        };
    }

    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'investigating' => 'Investigating',
            'identified' => 'Identified',
            'monitoring' => 'Monitoring',
            'resolved' => 'Resolved',
            default => 'Unknown'
        };
    }

    public function getDurationAttribute(): string
    {
        $start = $this->started_at;
        $end = $this->resolved_at ?? now();
        
        $diff = $start->diff($end);
        
        if ($diff->d > 0) {
            return $diff->d . ' day' . ($diff->d > 1 ? 's' : '') . ', ' . $diff->h . ' hour' . ($diff->h != 1 ? 's' : '');
        } elseif ($diff->h > 0) {
            return $diff->h . ' hour' . ($diff->h > 1 ? 's' : '') . ', ' . $diff->i . ' minute' . ($diff->i != 1 ? 's' : '');
        } else {
            return $diff->i . ' minute' . ($diff->i != 1 ? 's' : '');
        }
    }
}
