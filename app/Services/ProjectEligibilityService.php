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

        $rawAllowedLocationIds = $project->allowed_location_ids ?? [];
        if (is_string($rawAllowedLocationIds)) {
            $decoded = json_decode($rawAllowedLocationIds, true);
            $rawAllowedLocationIds = is_array($decoded) ? $decoded : explode(',', $rawAllowedLocationIds);
        }

        $allowedLocationIds = collect($rawAllowedLocationIds)
            ->map(static fn ($id) => (int) $id)
            ->filter(static fn ($id) => $id > 0)
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
