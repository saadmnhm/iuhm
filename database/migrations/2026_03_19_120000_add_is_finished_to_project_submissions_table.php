<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_submissions', function (Blueprint $table) {
            $table->boolean('is_finished')->default(false)->after('review_notes');
        });

        $activeFormIdsByProject = [];

        DB::table('project_submissions')
            ->select('id', 'candidat_id', 'programe_id')
            ->orderBy('id')
            ->chunkById(200, function ($rows) use (&$activeFormIdsByProject) {
                foreach ($rows as $row) {
                    $projectId = (int) $row->programe_id;

                    if (!array_key_exists($projectId, $activeFormIdsByProject)) {
                        $activeFormIdsByProject[$projectId] = DB::table('programe_formulaire')
                            ->where('programe_id', $projectId)
                            ->whereNull('deleted_at')
                            ->where(function ($q) {
                                $q->whereNull('status')
                                  ->orWhereRaw('LOWER(status) = ?', ['active']);
                            })
                            ->pluck('formulaire_id')
                            ->unique()
                            ->values()
                            ->all();
                    }

                    $activeFormIds = $activeFormIdsByProject[$projectId];
                    $requiredFormsCount = count($activeFormIds);
                    $isFinished = false;

                    if ($requiredFormsCount > 0) {
                        $submittedFormsCount = DB::table('dynamic_form_submissions')
                            ->whereNull('deleted_at')
                            ->where('candidat_id', $row->candidat_id)
                            ->where('programe_id', $projectId)
                            ->where(function ($q) {
                                $q->where('is_submitted', 1)
                                  ->orWhereIn('status', ['submitted', 'in_review', 'approved']);
                            })
                            ->whereIn('dynamic_form_id', $activeFormIds)
                            ->distinct()
                            ->count('dynamic_form_id');

                        $isFinished = $submittedFormsCount >= $requiredFormsCount;
                    }

                    DB::table('project_submissions')
                        ->where('id', $row->id)
                        ->update([
                            'is_finished' => $isFinished,
                            'updated_at' => now(),
                        ]);
                }
            }, 'id');
    }

    public function down(): void
    {
        Schema::table('project_submissions', function (Blueprint $table) {
            $table->dropColumn('is_finished');
        });
    }
};
