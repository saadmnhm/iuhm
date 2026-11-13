<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Keeps track of old slugs so public URLs keep working after a slug change.
 */
class SeoRedirect extends Model
{
    protected $fillable = [
        'model_type',
        'model_id',
        'old_slug',
        'new_slug',
        'status_code',
    ];

    protected $casts = [
        'status_code' => 'integer',
    ];

    /**
     * Resolve the current target of an old slug for a given content type.
     */
    public static function targetFor(string $modelType, string $oldSlug): ?self
    {
        return static::query()
            ->where('model_type', $modelType)
            ->where('old_slug', $oldSlug)
            ->first();
    }
}
