<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubmissionHistory extends Model
{
    protected $fillable = [
        'subject_type', 'subject_id', 'action', 'old_value', 'new_value', 'notes', 'changed_by',
    ];

    public function changedByUser()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    /**
     * Log a history entry.
     */
    public static function log(string $subjectType, int $subjectId, string $action, ?string $oldValue = null, ?string $newValue = null, ?string $notes = null): self
    {
        return static::create([
            'subject_type' => $subjectType,
            'subject_id'   => $subjectId,
            'action'       => $action,
            'old_value'    => $oldValue,
            'new_value'    => $newValue,
            'notes'        => $notes,
            'changed_by'   => auth()->id(),
        ]);
    }

    public static function forSubject(string $type, int $id)
    {
        return static::where('subject_type', $type)
            ->where('subject_id', $id)
            ->with('changedByUser')
            ->latest()
            ->get();
    }

    public const ACTION_LABELS = [
        'status_changed'     => 'Changement de statut',
        'reviewer_assigned'  => 'Responsable assigné',
        'review_submitted'   => 'Révision soumise',
        'submission_created' => 'Soumission créée',
        'submission_updated' => 'Soumission mise à jour',
        'candidat_review'    => 'Révision candidat',
    ];
}
