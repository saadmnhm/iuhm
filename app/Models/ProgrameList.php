<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class ProgrameList extends Model
{
    use SoftDeletes;

    protected $table = 'programe_list';
    
    protected $fillable = [
        'project_name',
        'description',
        'slug',
        'icon',
        'color',
        'bg_color',
        'min_age',
        'max_age',
        'allowed_address_id',
        'form_attached_id',
        'sort_order',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function ($projectList) {
            if (empty($projectList->slug)) {
                $projectList->slug = Str::slug($projectList->project_name);
            }
        });
    }

    protected static function AdressList(): HasMany
    {
        return $this->hasMany(Address::class, 'id', 'allowed_address');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function candidat()
    {
        return $this->hasMany(Candidat::class);
    }

    public function formulaires(): BelongsToMany
    {
        return $this->belongsToMany(DynamicForm::class, 'programe_formulaire', 'programe_id', 'formulaire_id')
                    ->withPivot('order', 'status', 'is_required')
                    ->withTimestamps()
                    ->orderByPivot('order');
    }


}
