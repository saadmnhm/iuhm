<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class ProjectsList extends Model
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
        'allowed_location_ids',
        'candidature_types',
        'eligibility_criteria',
        'form_attached_id',
        'sort_order',
        'is_active',
        'created_by',
        'logo1',
        'logo2',
        'logo3',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'allowed_location_ids' => 'array',
        'candidature_types' => 'array',
        'eligibility_criteria' => 'array',
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
                    ->withPivot('order', 'status', 'is_required', 'unlock_on_status')
                    ->withTimestamps()
                    ->orderByPivot('order');
    }

    public function dynamicForm()
    {
        return $this->belongsTo(DynamicForm::class, 'dynamic_form_id');
    }

    public function ProjectsSubmissions(): HasMany
    {
        return $this->hasMany(ProjectsSubmission::class, 'programe_id');
    }
    


}
