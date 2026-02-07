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
        'business_plan_id',
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

    public function projects()
    {
        return $this->hasMany(BusinessPlan::class);
    }

    public function businessPlans()
    {
        return $this->hasMany(BusinessPlan::class);
    }

    public function etudeMarches()
    {
        return $this->hasMany(EtudeMarche::class);
    }

    public function evaluationIdees()
    {
        return $this->hasMany(EvaluationIdee::class);
    }

    public function bmcs()
    {
        return $this->hasMany(Bmc::class);
    }

    public function bilanCompetences()
    {
        return $this->hasMany(BilanCompetence::class);
    }

    /**
     * Get the latest project for this candidat
     */
    public function latestProject()
    {
        return $this->hasOne(BusinessPlan::class)->latestOfMany();
    }

    /**
     * Get projects by form type
     */
    public function projectsByType(string $formType)
    {
        return $this->projects()->where('form_type', $formType);
    }

    /**
     * Support tickets
     */
    public function supportTickets()
    {
        return $this->hasMany(SupportTicket::class);
    }
}
