<?php

namespace App\Services;

use App\Models\Candidat;
use App\Models\ProjectsList;
use Carbon\Carbon;

class ProjectEligibilityService
{
    public function evaluate(Candidat $candidat, ProjectsList $project): array
    {
        $reasons = [];

        $age = $candidat->age;
        if (!$age && $candidat->date_naissance) {
            $age = Carbon::parse($candidat->date_naissance)->age;
        }

        if (!is_null($project->min_age) && !is_null($age) && $age < (int) $project->min_age) {
            $reasons[] = "Âge requis: minimum {$project->min_age} ans.";
        }

        if (!is_null($project->max_age) && !is_null($age) && $age > (int) $project->max_age) {
            $reasons[] = "Âge requis: maximum {$project->max_age} ans.";
        }

        $allowedLocationIds = collect($project->allowed_location_ids ?? [])
            ->filter()
            ->map(static fn ($id) => (int) $id)
            ->unique()
            ->values();

        if ($allowedLocationIds->isNotEmpty()) {
            if (!$candidat->morocco_location_id) {
                $reasons[] = 'Votre localisation (région / ville / préfecture) est manquante.';
            } elseif (!$allowedLocationIds->contains((int) $candidat->morocco_location_id)) {
                $reasons[] = 'ville/préfecture ne fait pas partie des zones autorisées pour ce projet.';
            }
        }

        return [
            'eligible' => empty($reasons),
            'reasons' => $reasons,
        ];
    }
}
