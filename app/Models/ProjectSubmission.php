<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class ProjectSubmission extends Model
{
    protected $fillable = [
        'candidat_id',
        'programe_id',
        'is_finished',
        'assigned_admin_id',
        'review_status',
        'reviewed_by',
        'reviewed_at',
        'review_notes',
        'last_activity', 'require_formation_review', 'formation_review_rating', 'formation_review_feedback', 'formation_review_files',
    ];

    protected $casts = [
        'is_finished' => 'boolean',
        'formation_review_files' => 'array',
        'reviewed_at' => 'datetime',
        'last_activity' => 'datetime',
    ];

    public static function syncFinishedStatusFor(?int $candidatId, ?int $programeId): void
    {
        if (!$candidatId || !$programeId) {
            return;
        }

        $activeFormIds = DB::table('programe_formulaire')
            ->join('dynamic_forms', 'programe_formulaire.formulaire_id', '=', 'dynamic_forms.id')
            ->where('programe_formulaire.programe_id', $programeId)
            ->where('programe_formulaire.is_required', true)
            ->whereNull('programe_formulaire.deleted_at')
            ->whereNull('dynamic_forms.deleted_at')
            ->where('dynamic_forms.is_active', true)
            ->where(function ($q) {
                $q->whereNull('programe_formulaire.status')
                  ->orWhereRaw('LOWER(programe_formulaire.status) = ?', ['active']);
            })
            ->pluck('programe_formulaire.formulaire_id')
            ->unique()
            ->values();

        $requiredFormsCount = $activeFormIds->count();
        $isFinished = false;

        if ($requiredFormsCount > 0) {
            $submittedFormsCount = DynamicFormSubmission::query()
                ->where('candidat_id', $candidatId)
                ->where('programe_id', $programeId)
                ->where(function ($q) {
                    $q->where('is_submitted', true)
                      ->orWhereIn('status', ['submitted', 'in_review', 'approved']);
                })
                ->whereIn('dynamic_form_id', $activeFormIds->all())
                ->distinct('dynamic_form_id')
                ->count('dynamic_form_id');

            $isFinished = $submittedFormsCount >= $requiredFormsCount;
        }

        static::where('candidat_id', $candidatId)
            ->where('programe_id', $programeId)
            ->update(['is_finished' => $isFinished]);
    }

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
