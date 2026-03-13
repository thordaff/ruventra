<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SystemLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'description',
        'metadata',
        'ip_address',
        'user_agent',
        'severity',
        'label_color',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    // ── Relationships ──────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ── Helpers ────────────────────────────────────────────────────────────

    /**
     * Map severity to a sensible default label color when not explicitly set.
     */
    public static function colorForSeverity(string $severity): string
    {
        return match ($severity) {
            'info'     => 'blue',
            'warning'  => 'yellow',
            'error'    => 'orange',
            'critical' => 'red',
            default    => 'gray',
        };
    }
}
