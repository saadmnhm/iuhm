<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NavigationItem extends Model
{
    use SoftDeletes;

    public const TYPE_PAGE_LINK = 'page_link';
    public const TYPE_EXTERNAL_LINK = 'external_link';
    public const TYPE_DROPDOWN = 'dropdown';
    public const TYPE_DIVIDER = 'divider';

    protected $fillable = [
        'label',
        'label_ar',
        'type',
        'page_id',
        'url',
        'parent_id',
        'sort_order',
        'is_active',
        'is_visible',
        'icon',
        'icon_color',
        'settings',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_visible' => 'boolean',
        'sort_order' => 'integer',
        'settings' => 'json',
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function parent()
    {
        return $this->belongsTo(NavigationItem::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(NavigationItem::class, 'parent_id')->where('is_visible', true)->orderBy('sort_order');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('is_visible', true);
    }

    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    public function getUrlAttribute(): string
    {
        if ($this->type === self::TYPE_PAGE_LINK && $this->page) {
            return $this->page->slug ? "/{$this->page->slug}" : '/';
        }

        return $this->url ?? '#';
    }

    public function getHasChildrenAttribute(): bool
    {
        return $this->children()->exists();
    }
}