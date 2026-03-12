<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdminBroadcast extends Model
{
    protected $fillable = [
        'admin_id',
        'title',
        'message',
        'target_type',
        'target_candidat_ids',
        'target_candidat_id',
        'is_active',
    ];

    protected $casts = [
        'target_candidat_ids' => 'array',
        'is_active'           => 'boolean',
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function reads(): HasMany
    {
        return $this->hasMany(BroadcastRead::class, 'broadcast_id');
    }

    /**
     * Returns all active broadcasts that this candidat has NOT yet dismissed.
     */
    public static function unreadForCandidat(int $candidatId): \Illuminate\Database\Eloquent\Collection
    {
            $readIds = BroadcastRead::where('candidat_id', $candidatId)
                ->pluck('broadcast_id')
                ->toArray();

            return static::where('is_active', true)
                ->when(!empty($readIds), fn($q) => $q->whereNotIn('id', $readIds))
                ->where(function ($q) use ($candidatId) {
                $q->where('target_type', 'all')
                  ->orWhere(function ($q2) use ($candidatId) {
                      $q2->where('target_type', 'single')
                         ->where('target_candidat_id', $candidatId);
                  })
                  ->orWhere(function ($q3) use ($candidatId) {
                      $q3->where('target_type', 'selected')
                             ->whereJsonContains('target_candidat_ids', (int) $candidatId);
                  });
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
