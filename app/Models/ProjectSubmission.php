<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectSubmission extends Model
{
    protected $fillable = [
        'candidat_id',
        'programe_id',
        'assigned_admin_id',
        'review_status',
        'reviewed_by',
        'reviewed_at',
        'review_notes',
        'last_activity', 'require_formation_review', 'formation_review_rating', 'formation_review_feedback',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
        'last_activity' => 'datetime',
    ];

    public function candidat(): BelongsTo
    {
        return $this->belongsTo(Candidat::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(ProgrameList::class, 'programe_id');
    }

    public function assignedAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_admin_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function formSubmissions(): HasMany
    {
        return $this->hasMany(DynamicFormSubmission::class);
    }
}
