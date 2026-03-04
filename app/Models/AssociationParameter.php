<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssociationParameter extends Model
{
    use  SoftDeletes;
    protected $fillable = [
        'category', 'key', 'label', 'value', 'type', 'options', 'sort_order', 'updated_by', 'deleted_at',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public static function get(string $key, $default = null)
    {
        return static::where('key', $key)->value('value') ?? $default;
    }

    public static function set(string $key, $value): void
    {
        static::where('key', $key)->update(['value' => $value, 'updated_by' => auth()->id()]);
    }

    public static function getByCategory(string $category)
    {
        return static::where('category', $category)->orderBy('sort_order')->get();
    }

    public const CATEGORIES = [
        'general'  => 'Informations Générales',
        'contact'  => 'Contact & Réseaux',
        'finance'  => 'Finance',
        'rh'       => 'Ressources Humaines',
        'seo'      => 'SEO & Visibilité',
    ];
}
