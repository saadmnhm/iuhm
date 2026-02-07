<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DynamicFormSubmission extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'dynamic_form_id', 'candidat_id', 'status', 'current_step',
        'submitted_at', 'reviewed_at', 'review_notes', 'reviewed_by',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public function form(): BelongsTo
    {
        return $this->belongsTo(DynamicForm::class, 'dynamic_form_id');
    }

    public function candidat(): BelongsTo
    {
        return $this->belongsTo(Candidat::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(DynamicFormAnswer::class);
    }

    public function tableAnswers(): HasMany
    {
        return $this->hasMany(DynamicFormTableAnswer::class);
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isSubmitted(): bool
    {
        return in_array($this->status, ['submitted', 'in_review', 'approved', 'rejected']);
    }

    public function canBeEdited(): bool
    {
        return $this->status === 'draft';
    }

    public function getAnswer(string $fieldKey): ?string
    {
        return $this->answers->where('field_key', $fieldKey)->first()?->value;
    }

    public function getTableData(string $tableKey): array
    {
        $rows = [];
        $tableAnswers = $this->tableAnswers->where('table_key', $tableKey);
        foreach ($tableAnswers as $answer) {
            $rows[$answer->row_index][$answer->column_key] = $answer->value;
        }
        ksort($rows);
        return $rows;
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'yellow',
            'submitted' => 'blue',
            'in_review' => 'purple',
            'approved' => 'green',
            'rejected' => 'red',
            default => 'gray',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'Brouillon',
            'submitted' => 'Soumis',
            'in_review' => 'En révision',
            'approved' => 'Approuvé',
            'rejected' => 'Rejeté',
            default => $this->status,
        };
    }
}
