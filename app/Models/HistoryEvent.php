<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class HistoryEvent extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'title_ar',
        'slug',
        'description',
        'description_ar',
        'event_date',
        'end_date',
        'category',
        'tags',
        'image',
        'image_ar',
        'is_published',
        'published_at',
        'author_id',
        'views_count',
        'metadata', // JSON for additional flexible fields
    ];

    protected $casts = [
        'tags' => 'array',
        'metadata' => 'json',
        'is_published' => 'boolean',
        'event_date' => 'datetime',
        'end_date' => 'datetime',
        'published_at' => 'datetime',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('event_date', [$startDate, $endDate]);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>', now());
    }

    public function scopePast($query)
    {
        return $query->where('event_date', '<=', now());
    }

    public static function generateSlug(string $title): string
    {
        $slug = Str::slug($title);
        $count = static::where('slug', 'like', $slug . '%')->count();
        return $count ? "{$slug}-{$count}" : $slug;
    }

    public function getFormattedDate(): string
    {
        return $this->event_date->format(app()->getLocale() === 'ar' ? 'd/m/Y' : 'd/m/Y');
    }

    public function getFormattedDateTime(): string
    {
        return $this->event_date->format(app()->getLocale() === 'ar' ? 'd/m/Y H:i' : 'd/m/Y H:i');
    }

    protected static function booted()
    {
        static::creating(function ($event) {
            if (empty($event->slug)) {
                $event->slug = self::generateUniqueSlug($event->title);
            }
        });
    }
}