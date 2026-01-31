<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Candidat extends Authenticatable
{
    use SoftDeletes;
    
    protected $table = 'candidat';

    protected $fillable = [
        'project_id',
        'login',
        'password',
        'nom',
        'prenom',
        'age',
        'profile_image',
        'gender',
        'address',
        'email',
        'phone',
        'date_naissance',
        'cv_path',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_active' => 'boolean',
            'date_naissance' => 'date',
        ];
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
