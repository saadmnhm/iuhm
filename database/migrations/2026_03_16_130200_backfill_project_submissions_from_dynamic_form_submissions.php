<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        $pairs = DB::table('dynamic_form_submissions')
            ->select('candidat_id', 'programe_id')
            ->whereNotNull('candidat_id')
            ->whereNotNull('programe_id')
            ->distinct()
            ->get();

        foreach ($pairs as $pair) {
            $existing = DB::table('project_submissions')
                ->where('candidat_id', $pair->candidat_id)
                ->where('programe_id', $pair->programe_id)
                ->first();

            if (!$existing) {
                DB::table('project_submissions')->insert([
                    'candidat_id' => $pair->candidat_id,
                    'programe_id' => $pair->programe_id,
                    'review_status' => 'pending',
                    'last_activity' => $now,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        $ProjectSubmissionMap = DB::table('project_submissions')
            ->select('id', 'candidat_id', 'programe_id')
            ->get()
            ->mapWithKeys(function ($row) {
                return [$row->candidat_id . ':' . $row->programe_id => $row->id];
            });

        DB::table('dynamic_form_submissions')
            ->select('id', 'candidat_id', 'programe_id')
            ->whereNull('project_submission_id')
            ->whereNotNull('candidat_id')
            ->whereNotNull('programe_id')
            ->orderBy('id')
            ->chunkById(500, function ($submissions) use ($ProjectSubmissionMap) {
                foreach ($submissions as $submission) {
                    $key = $submission->candidat_id . ':' . $submission->programe_id;
                    $ProjectSubmissionId = $ProjectSubmissionMap->get($key);

                    if ($ProjectSubmissionId) {
                        DB::table('dynamic_form_submissions')
                            ->where('id', $submission->id)
                            ->update(['project_submission_id' => $ProjectSubmissionId]);
                    }
                }
            });
    }

    public function down(): void
    {
        DB::table('dynamic_form_submissions')->update(['project_submission_id' => null]);
        DB::table('project_submissions')->truncate();
    }
};
