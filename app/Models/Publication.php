<?php

namespace App\Models;

use App\Models\Concerns\ResolvesMediaUrls;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Publication extends Model
{
    use ResolvesMediaUrls, SoftDeletes;

    protected $table = 'publication';

    protected $fillable = [
        'title',
        'title_ar',
        'slug',
        'description',
        'description_ar',
        'file_url',
        'file_type',
        'category',
        'status',
        'due_date',
        'is_published',
        'published_at',
        'author_id',
        'downloads_count',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'due_date' => 'datetime',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    /**
     * Get the localized value of a field based on the current locale.
     */
    public function getLocalized(string $field): ?string
    {
        $locale = app()->getLocale();
        $localizedField = $field.'_ar';

        if ($locale === 'ar' && filled($this->{$localizedField})) {
            return $this->{$localizedField};
        }

        return $this->{$field};
    }

    /**
     * Public URL for downloading the stored file.
     */
    public function getFileDownloadUrlAttribute(): ?string
    {
        return $this->resolveMediaUrl($this->file_url);
    }

    public static function generateSlug(string $title): string
    {
        $slug = Str::slug($title);
        $count = static::where('slug', 'like', $slug.'%')->count();

        return $count ? "{$slug}-{$count}" : $slug;
    }
}
