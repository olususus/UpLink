<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StatusCheck extends Model
{
    protected $fillable = [
        'service_id',
        'status',
        'response_time',
        'http_status',
        'error_message',
        'checked_at'
    ];

    protected $casts = [
        'checked_at' => 'datetime',
        'response_time' => 'integer',
        'http_status' => 'integer'
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
