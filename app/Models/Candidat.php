<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Models\User;
use App\Models\DynamicFormSubmission;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\TracksUserActivity;
use App\Notifications\CandidatVerifyEmail;

class Candidat extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, SoftDeletes, TracksUserActivity;
    
    protected $table = 'candidat';

    protected $fillable = [
        'matricule',
        'login',
        'password',
        'nom',
        'prenom',
        'age',
        'profile_image',
        'gender',
        'address',
        'selected_region',
        'selected_city',
        'selected_prefecture',
        'morocco_location_id',
        'address_detail',
        'email',
        'phone',
        'date_naissance',
        'cv_path',
        'is_active',
        'last_ip_address',
        'last_user_agent',
        'last_browser',
        'last_platform',
        'last_device',
        'last_login_at',
        'login_count',
        'reviewed_by',
        'reviewed_at',
        'review_notes',
        'review_status',
        'email_verified_at',
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
            'last_login_at' => 'datetime',
            'login_count'  => 'integer',
            'reviewed_at'        => 'datetime',
            'email_verified_at'  => 'datetime',
        ];
    }

    /**
     * Send the email verification notification using the candidat-specific route.
     */
    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new CandidatVerifyEmail);
    }

    /**
     * Send the password reset notification using the custom branded notification.
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new \App\Notifications\CandidatResetPassword($token));
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function moroccoLocation()
    {
        return $this->belongsTo(MoroccoLocation::class, 'morocco_location_id');
    }

    public function projects()
    {
        return $this->hasMany(DynamicFormSubmission::class);
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
