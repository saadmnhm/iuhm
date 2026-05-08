<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Deliverable extends Model
{
    use SoftDeletes;

    protected $table = 'deliverables';

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

    public static function generateSlug(string $title): string
    {
        $slug = Str::slug($title);
        $count = static::where('slug', 'like', $slug . '%')->count();
        return $count ? "{$slug}-{$count}" : $slug;
    }
}
