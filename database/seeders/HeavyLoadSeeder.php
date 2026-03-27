<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HeavyLoadSeeder extends Seeder
{
    private int $target = 4000;

    public function run(): void
    {
        DB::disableQueryLog();

        $this->command?->warn('Starting heavy load seeding...');

        $this->bootstrapCoreData();
        $this->seedConstrainedTables();

        $tables = $this->listTables();
        $excluded = [
            'migrations',
            'cache',
            'cache_locks',
            'jobs',
            'job_batches',
            'failed_jobs',
            'password_reset_tokens',
            'sessions',
        ];

        foreach ($tables as $table) {
            if (in_array($table, $excluded, true)) {
                continue;
            }

            $this->topUpTable($table, $this->target);
        }

        $this->command?->info('Heavy load seeding completed.');
        $this->printSummary();
    }

    private function seedConstrainedTables(): void
    {
        foreach (['users', 'candidat', 'programe_list', 'dynamic_forms'] as $baseTable) {
            if ($this->tableExists($baseTable)) {
                $this->topUpTable($baseTable, $this->target);
            }
        }

        $this->seedCandidatDemographics();

        $this->seedMoroccoLocations();
        $this->seedRhEmployees();
        $this->seedBmcs();
        $this->seedFinanceData();
        $this->seedMaterialData();
        $this->seedProjectSubmissions();
        $this->seedDynamicFormSubmissions();
        $this->seedProgrameFormulaire();
        $this->seedCandidatFormulaireOrders();
        $this->seedCandidatEvaluationGrids();
        $this->seedBroadcastReads();
        $this->seedDynamicFormSteps();
        $this->seedCandidatProjectAgreements();
        $this->seedRolePermissions();
    }

    private function seedCandidatDemographics(): void
    {
        if (!$this->tableExists('candidat')) {
            return;
        }

        $candidats = DB::table('candidat')->select('id')->orderBy('id')->get();
        if ($candidats->isEmpty()) {
            return;
        }

        $genders = $this->getSupportedCandidatGenders();
        $locations = [];
        if ($this->tableExists('morocco_locations')) {
            $locations = DB::table('morocco_locations')
                ->select('region', 'city', 'prefecture')
                ->orderBy('id')
                ->limit(max($this->target, 300))
                ->get()
                ->all();
        }

        foreach ($candidats as $index => $candidat) {
            $payload = [];

            if (!empty($genders) && $this->hasColumn('candidat', 'gender')) {
                $payload['gender'] = $genders[$index % count($genders)];
            }

            if (!empty($locations)) {
                $loc = $locations[$index % count($locations)];

                if ($this->hasColumn('candidat', 'selected_region')) {
                    $payload['selected_region'] = $loc->region;
                }
                if ($this->hasColumn('candidat', 'selected_city')) {
                    $payload['selected_city'] = $loc->city;
                }
                if ($this->hasColumn('candidat', 'selected_prefecture')) {
                    $payload['selected_prefecture'] = $loc->prefecture;
                }
                if ($this->hasColumn('candidat', 'address')) {
                    $payload['address'] = $loc->city . ' - ' . $loc->prefecture;
                }
            }

            if (!empty($payload)) {
                DB::table('candidat')->where('id', $candidat->id)->update($payload);
            }
        }
    }

    private function getSupportedCandidatGenders(): array
    {
        if (!$this->hasColumn('candidat', 'gender')) {
            return [];
        }

        $rows = DB::select("SHOW COLUMNS FROM `candidat` LIKE 'gender'");
        $type = strtolower((string) ($rows[0]->Type ?? ''));

        if (str_contains($type, "'autre'")) {
            return ['homme', 'femme', 'autre'];
        }

        return ['homme', 'femme'];
    }

    private function tableExists(string $table): bool
    {
        $db = DB::getDatabaseName();

        $rows = DB::select(
            'SELECT 1 FROM information_schema.tables WHERE table_schema = ? AND table_name = ? LIMIT 1',
            [$db, $table]
        );

        return !empty($rows);
    }

    private function hasColumn(string $table, string $column): bool
    {
        $safeColumn = str_replace("'", "''", $column);
        $rows = DB::select("SHOW COLUMNS FROM `{$table}` LIKE '{$safeColumn}'");

        return !empty($rows);
    }

    private function insertChunks(string $table, array $rows, int $size = 500): void
    {
        foreach (array_chunk($rows, $size) as $chunk) {
            DB::table($table)->insertOrIgnore($chunk);
        }
    }

    private function seedMoroccoLocations(): void
    {
        if (!$this->tableExists('morocco_locations')) {
            return;
        }

        $count = (int) DB::table('morocco_locations')->count();
        if ($count >= $this->target) {
            return;
        }

        $now = now();
        $rows = [];
        for ($i = $count + 1; $i <= $this->target; $i++) {
            $rows[] = [
                'region' => 'Load Region ' . (int) ceil($i / 40),
                'city' => 'Load City ' . $i,
                'prefecture' => 'Load Prefecture ' . $i,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        $this->insertChunks('morocco_locations', $rows);
    }

    private function seedRhEmployees(): void
    {
        if (!$this->tableExists('rh_employees')) {
            return;
        }

        $count = (int) DB::table('rh_employees')->count();
        if ($count >= $this->target) {
            return;
        }

        $createdBy = DB::table('users')->value('id');
        $now = now();
        $rows = [];

        for ($i = $count + 1; $i <= $this->target; $i++) {
            $rows[] = [
                'matricule' => 'EMP-' . str_pad((string) $i, 5, '0', STR_PAD_LEFT),
                'nom' => 'Employee' . $i,
                'prenom' => 'Load',
                'cin' => 'CINEMP' . str_pad((string) $i, 5, '0', STR_PAD_LEFT),
                'email' => 'employee' . $i . '@example.test',
                'phone' => '061' . str_pad((string) (($i * 37) % 10000000), 7, '0', STR_PAD_LEFT),
                'poste' => 'Agent',
                'departement' => 'Operations',
                'contrat_type' => 'CDI',
                'date_embauche' => now()->subDays($i % 1000)->toDateString(),
                'salaire' => 4000 + ($i % 50) * 100,
                'status' => 'active',
                'created_by' => $createdBy,
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ];
        }

        $this->insertChunks('rh_employees', $rows);
    }

    private function seedBmcs(): void
    {
        if (!$this->tableExists('bmcs')) {
            return;
        }

        $count = (int) DB::table('bmcs')->count();
        if ($count >= $this->target) {
            return;
        }

        $candidatIds = DB::table('candidat')->pluck('id')->all();
        if (empty($candidatIds)) {
            return;
        }

        $now = now();
        $rows = [];
        for ($i = $count + 1; $i <= $this->target; $i++) {
            $candidateId = $candidatIds[($i - 1) % count($candidatIds)];
            $rows[] = [
                'candidat_id' => $candidateId,
                'partenaires_cles' => 'Partenaires ' . $i,
                'activites_cles' => 'Activites ' . $i,
                'proposition_valeur' => 'Valeur ' . $i,
                'relations_clients' => 'Relations ' . $i,
                'segments_clientele' => 'Segments ' . $i,
                'ressources_cles' => 'Ressources ' . $i,
                'canaux' => 'Canaux ' . $i,
                'structure_couts' => 'Structure couts ' . $i,
                'flux_revenus' => 'Flux revenus ' . $i,
                'status' => 'submitted',
                'current_step' => 9,
                'submitted_at' => $now,
                'reviewed_at' => null,
                'review_notes' => null,
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ];
        }

        $this->insertChunks('bmcs', $rows);
    }

    private function seedFinanceData(): void
    {
        if (
            !$this->tableExists('finance_caisse') ||
            !$this->tableExists('finance_categories') ||
            !$this->tableExists('finance_transactions') ||
            !$this->tableExists('finance_attachments') ||
            !$this->tableExists('finance_charges')
        ) {
            return;
        }

        $userIds = DB::table('users')->pluck('id')->all();
        $creatorId = $userIds[0] ?? null;
        $now = now();

        if ((int) DB::table('finance_caisse')->count() < $this->target) {
            $rows = [];
            $start = (int) DB::table('finance_caisse')->count() + 1;
            for ($i = $start; $i <= $this->target; $i++) {
                $rows[] = [
                    'label' => 'Caisse ' . $i,
                    'solde_initial' => 10000 + $i,
                    'description' => 'Load caisse ' . $i,
                    'created_by' => $creatorId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            $this->insertChunks('finance_caisse', $rows);
        }

        if ((int) DB::table('finance_categories')->count() < $this->target) {
            $rows = [];
            $start = (int) DB::table('finance_categories')->count() + 1;
            for ($i = $start; $i <= $this->target; $i++) {
                $rows[] = [
                    'type' => $i % 2 === 0 ? 'revenue' : 'depense',
                    'name' => 'Finance Category ' . $i,
                    'icon' => 'ri-money-dollar-circle-line',
                    'color' => '#1f6b4f',
                    'sort_order' => $i,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            $this->insertChunks('finance_categories', $rows);
        }

        $caisseIds = DB::table('finance_caisse')->pluck('id')->all();
        $categoryIds = DB::table('finance_categories')->pluck('id')->all();

        if ((int) DB::table('finance_transactions')->count() < $this->target && !empty($caisseIds)) {
            $rows = [];
            $start = (int) DB::table('finance_transactions')->count() + 1;
            for ($i = $start; $i <= $this->target; $i++) {
                $caisseId = $caisseIds[($i - 1) % count($caisseIds)];
                $catId = !empty($categoryIds) ? $categoryIds[($i - 1) % count($categoryIds)] : null;
                $rows[] = [
                    'caisse_id' => $caisseId,
                    'category_id' => $catId,
                    'type' => $i % 2 === 0 ? 'revenue' : 'depense',
                    'reference' => 'TRX-' . str_pad((string) $i, 6, '0', STR_PAD_LEFT),
                    'label' => 'Transaction ' . $i,
                    'description' => 'Load transaction ' . $i,
                    'amount' => 100 + ($i % 1000),
                    'date_transaction' => now()->subDays($i % 365)->toDateString(),
                    'beneficiaire' => 'Beneficiaire ' . $i,
                    'mode_paiement' => 'espece',
                    'status' => 'valide',
                    'created_by' => $creatorId,
                    'validated_by' => $creatorId,
                    'created_at' => $now,
                    'updated_at' => $now,
                    'deleted_at' => null,
                ];
            }
            $this->insertChunks('finance_transactions', $rows);
        }

        $transactionIds = DB::table('finance_transactions')->pluck('id')->all();

        if ((int) DB::table('finance_attachments')->count() < $this->target && !empty($transactionIds)) {
            $rows = [];
            $start = (int) DB::table('finance_attachments')->count() + 1;
            for ($i = $start; $i <= $this->target; $i++) {
                $transactionId = $transactionIds[($i - 1) % count($transactionIds)];
                $rows[] = [
                    'transaction_id' => $transactionId,
                    'file_path' => 'uploads/finance/file-' . $i . '.pdf',
                    'file_name' => 'finance-file-' . $i . '.pdf',
                    'file_type' => 'facture',
                    'mime_type' => 'application/pdf',
                    'file_size' => 2048 + $i,
                    'notes' => 'Load attachment ' . $i,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            $this->insertChunks('finance_attachments', $rows);
        }

        if ((int) DB::table('finance_charges')->count() < $this->target && !empty($caisseIds)) {
            $rows = [];
            $start = (int) DB::table('finance_charges')->count() + 1;
            for ($i = $start; $i <= $this->target; $i++) {
                $caisseId = $caisseIds[($i - 1) % count($caisseIds)];
                $rows[] = [
                    'caisse_id' => $caisseId,
                    'label' => 'Charge ' . $i,
                    'montant' => 200 + ($i % 300),
                    'frequence' => 'mensuel',
                    'fournisseur' => 'Fournisseur ' . $i,
                    'date_echeance' => now()->addDays($i % 60)->toDateString(),
                    'is_active' => true,
                    'notes' => 'Load charge ' . $i,
                    'created_by' => $creatorId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            $this->insertChunks('finance_charges', $rows);
        }
    }

    private function seedMaterialData(): void
    {
        if (
            !$this->tableExists('material_categories') ||
            !$this->tableExists('materials') ||
            !$this->tableExists('material_attachments') ||
            !$this->tableExists('material_movements') ||
            !$this->tableExists('material_maintenances')
        ) {
            return;
        }

        $userId = DB::table('users')->value('id');
        $now = now();

        if ((int) DB::table('material_categories')->count() < $this->target) {
            $rows = [];
            $start = (int) DB::table('material_categories')->count() + 1;
            for ($i = $start; $i <= $this->target; $i++) {
                $rows[] = [
                    'name' => 'Material Category ' . $i,
                    'icon' => 'ri-archive-line',
                    'color' => '#336699',
                    'sort_order' => $i,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            $this->insertChunks('material_categories', $rows);
        }

        $categoryIds = DB::table('material_categories')->pluck('id')->all();

        if ((int) DB::table('materials')->count() < $this->target) {
            $rows = [];
            $start = (int) DB::table('materials')->count() + 1;
            for ($i = $start; $i <= $this->target; $i++) {
                $rows[] = [
                    'category_id' => !empty($categoryIds) ? $categoryIds[($i - 1) % count($categoryIds)] : null,
                    'reference' => 'MAT-' . str_pad((string) $i, 6, '0', STR_PAD_LEFT),
                    'name' => 'Material ' . $i,
                    'description' => 'Load material ' . $i,
                    'quantity' => 1 + ($i % 20),
                    'quantity_min' => 1,
                    'prix_unitaire' => 100 + ($i % 500),
                    'valeur_totale' => 300 + ($i % 1000),
                    'emplacement' => 'Stock ' . (($i % 10) + 1),
                    'etat' => 'bon',
                    'status' => 'disponible',
                    'fournisseur' => 'Supplier ' . $i,
                    'date_acquisition' => now()->subDays($i % 500)->toDateString(),
                    'date_garantie' => now()->addDays(365 + ($i % 365))->toDateString(),
                    'numero_serie' => 'SN' . str_pad((string) $i, 8, '0', STR_PAD_LEFT),
                    'notes' => 'Load notes ' . $i,
                    'created_by' => $userId,
                    'created_at' => $now,
                    'updated_at' => $now,
                    'deleted_at' => null,
                ];
            }
            $this->insertChunks('materials', $rows);
        }

        $materialIds = DB::table('materials')->pluck('id')->all();
        if (empty($materialIds)) {
            return;
        }

        if ((int) DB::table('material_attachments')->count() < $this->target) {
            $rows = [];
            $start = (int) DB::table('material_attachments')->count() + 1;
            for ($i = $start; $i <= $this->target; $i++) {
                $rows[] = [
                    'material_id' => $materialIds[($i - 1) % count($materialIds)],
                    'file_path' => 'uploads/materials/photo-' . $i . '.jpg',
                    'file_name' => 'photo-' . $i . '.jpg',
                    'file_type' => 'photo',
                    'mime_type' => 'image/jpeg',
                    'is_primary' => $i % 15 === 0,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            $this->insertChunks('material_attachments', $rows);
        }

        if ((int) DB::table('material_movements')->count() < $this->target) {
            $rows = [];
            $start = (int) DB::table('material_movements')->count() + 1;
            $types = ['entree', 'sortie', 'transfert', 'ajustement'];
            for ($i = $start; $i <= $this->target; $i++) {
                $rows[] = [
                    'material_id' => $materialIds[($i - 1) % count($materialIds)],
                    'type' => $types[$i % 4],
                    'quantity' => 1 + ($i % 15),
                    'motif' => 'Load movement ' . $i,
                    'destination' => 'Destination ' . (($i % 12) + 1),
                    'notes' => 'Notes ' . $i,
                    'created_by' => $userId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            $this->insertChunks('material_movements', $rows);
        }

        if ((int) DB::table('material_maintenances')->count() < $this->target) {
            $rows = [];
            $start = (int) DB::table('material_maintenances')->count() + 1;
            $statuses = ['planifie', 'en_cours', 'termine'];
            for ($i = $start; $i <= $this->target; $i++) {
                $date = now()->subDays($i % 200)->toDateString();
                $rows[] = [
                    'material_id' => $materialIds[($i - 1) % count($materialIds)],
                    'type_maintenance' => 'preventive',
                    'description' => 'Maintenance ' . $i,
                    'cout' => 80 + ($i % 300),
                    'date_maintenance' => $date,
                    'prochaine_maintenance' => now()->addDays(($i % 120) + 30)->toDateString(),
                    'prestataire' => 'Prestataire ' . $i,
                    'status' => $statuses[$i % 3],
                    'created_by' => $userId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            $this->insertChunks('material_maintenances', $rows);
        }
    }

    private function seedProjectSubmissions(): void
    {
        if (!$this->tableExists('project_submissions')) {
            return;
        }

        $count = (int) DB::table('project_submissions')->count();
        if ($count >= $this->target) {
            return;
        }

        $candidateIds = DB::table('candidat')->pluck('id')->all();
        $projectIds = DB::table('programe_list')->pluck('id')->all();
        $adminIds = DB::table('users')->where('role', 'admin')->pluck('id')->all();
        if (empty($adminIds)) {
            $adminIds = DB::table('users')->pluck('id')->all();
        }

        if (empty($candidateIds) || empty($projectIds)) {
            return;
        }

        $existing = DB::table('project_submissions')
            ->select('candidat_id', 'programe_id')
            ->get()
            ->mapWithKeys(fn ($row) => [((string) $row->candidat_id . '|' . (string) $row->programe_id) => true])
            ->all();

        $now = now();
        $rows = [];

        foreach ($candidateIds as $candidateId) {
            foreach ($projectIds as $projectId) {
                if (($count + count($rows)) >= $this->target) {
                    break 2;
                }

                $key = (string) $candidateId . '|' . (string) $projectId;
                if (isset($existing[$key])) {
                    continue;
                }

                $adminId = !empty($adminIds) ? $adminIds[(count($rows)) % count($adminIds)] : null;
                $rows[] = [
                    'candidat_id' => $candidateId,
                    'programe_id' => $projectId,
                    'assigned_admin_id' => $adminId,
                    'review_status' => 'pending',
                    'reviewed_by' => null,
                    'reviewed_at' => null,
                    'review_notes' => null,
                    'last_activity' => $now,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                $existing[$key] = true;
            }
        }

        if (!empty($rows)) {
            $this->insertChunks('project_submissions', $rows);
        }
    }

    private function seedDynamicFormSubmissions(): void
    {
        if (!$this->tableExists('dynamic_form_submissions')) {
            return;
        }

        $count = (int) DB::table('dynamic_form_submissions')->count();
        if ($count >= $this->target) {
            return;
        }

        $formIds = DB::table('dynamic_forms')->pluck('id')->all();
        $candidateIds = DB::table('candidat')->pluck('id')->all();
        $projectIds = DB::table('programe_list')->pluck('id')->all();
        $userIds = DB::table('users')->pluck('id')->all();

        if (empty($formIds) || empty($candidateIds)) {
            return;
        }

        $now = now();
        $rows = [];
        $needed = $this->target - $count;
        $generated = 0;

        foreach ($formIds as $formId) {
            foreach ($candidateIds as $candidateId) {
                if ($generated >= $needed) {
                    break 2;
                }

                $projectId = !empty($projectIds) ? $projectIds[$generated % count($projectIds)] : null;
                $row = [
                    'dynamic_form_id' => $formId,
                    'candidat_id' => $candidateId,
                    'status' => 'submitted',
                    'current_step' => 1,
                    'submitted_at' => $now,
                    'reviewed_at' => null,
                    'review_notes' => null,
                    'reviewed_by' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                    'deleted_at' => null,
                ];

                if ($this->hasColumn('dynamic_form_submissions', 'user_id')) {
                    $row['user_id'] = !empty($userIds) ? $userIds[$generated % count($userIds)] : null;
                }
                if ($this->hasColumn('dynamic_form_submissions', 'programe_id')) {
                    $row['programe_id'] = $projectId;
                }
                if ($this->hasColumn('dynamic_form_submissions', 'is_submitted')) {
                    $row['is_submitted'] = true;
                }

                $rows[] = $row;
                $generated++;
            }
        }

        $this->insertChunks('dynamic_form_submissions', $rows);
    }

    private function seedProgrameFormulaire(): void
    {
        if (!$this->tableExists('programe_formulaire')) {
            return;
        }

        $count = (int) DB::table('programe_formulaire')->count();
        if ($count >= $this->target) {
            return;
        }

        $projectIds = DB::table('programe_list')->pluck('id')->all();
        $formIds = DB::table('dynamic_forms')->pluck('id')->all();

        if (empty($projectIds) || empty($formIds)) {
            return;
        }

        $existing = DB::table('programe_formulaire')
            ->select('programe_id', 'formulaire_id')
            ->get()
            ->mapWithKeys(fn ($row) => [((string) $row->programe_id . '|' . (string) $row->formulaire_id) => true])
            ->all();

        $now = now();
        $rows = [];

        foreach ($projectIds as $projectId) {
            foreach ($formIds as $formId) {
                if (($count + count($rows)) >= $this->target) {
                    break 2;
                }

                $key = (string) $projectId . '|' . (string) $formId;
                if (isset($existing[$key])) {
                    continue;
                }

                $row = [
                    'programe_id' => $projectId,
                    'formulaire_id' => $formId,
                    'order' => ((count($rows)) % 5) + 1,
                    'status' => 'active',
                    'is_required' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                if ($this->hasColumn('programe_formulaire', 'unlock_on_status')) {
                    $row['unlock_on_status'] = 'approved';
                }
                if ($this->hasColumn('programe_formulaire', 'deleted_at')) {
                    $row['deleted_at'] = null;
                }

                $rows[] = $row;
                $existing[$key] = true;
            }
        }

        if (!empty($rows)) {
            $this->insertChunks('programe_formulaire', $rows);
        }
    }

    private function seedCandidatFormulaireOrders(): void
    {
        if (!$this->tableExists('candidat_formulaire_orders')) {
            return;
        }

        $count = (int) DB::table('candidat_formulaire_orders')->count();
        if ($count >= $this->target) {
            return;
        }

        $candidateIds = DB::table('candidat')->pluck('id')->all();
        $projectIds = DB::table('programe_list')->pluck('id')->all();
        $formIds = DB::table('dynamic_forms')->pluck('id')->all();
        if (empty($candidateIds) || empty($projectIds) || empty($formIds)) {
            return;
        }

        $now = now();
        $rows = [];
        $needed = $this->target - $count;
        $generated = 0;

        foreach ($candidateIds as $candidateId) {
            foreach ($projectIds as $projectId) {
                foreach ($formIds as $formId) {
                    if ($generated >= $needed) {
                        break 3;
                    }

                    $rows[] = [
                        'candidat_id' => $candidateId,
                        'programe_id' => $projectId,
                        'formulaire_id' => $formId,
                        'order' => ($generated % 10) + 1,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                    $generated++;
                }
            }
        }

        $this->insertChunks('candidat_formulaire_orders', $rows);
    }

    private function seedCandidatEvaluationGrids(): void
    {
        if (!$this->tableExists('candidat_evaluation_grids')) {
            return;
        }

        $count = (int) DB::table('candidat_evaluation_grids')->count();
        if ($count >= $this->target) {
            return;
        }

        $candidateIds = DB::table('candidat')->pluck('id')->all();
        $projectIds = DB::table('programe_list')->pluck('id')->all();
        $adminIds = DB::table('users')->where('role', 'admin')->pluck('id')->all();
        if (empty($adminIds)) {
            $adminIds = DB::table('users')->pluck('id')->all();
        }

        if (empty($candidateIds)) {
            return;
        }

        $now = now();
        $rows = [];
        for ($i = $count + 1; $i <= $this->target; $i++) {
            $motivation = ($i % 20) + 1;
            $profile = ($i % 20) + 1;
            $viability = ($i % 20) + 1;
            $rows[] = [
                'candidat_id' => $candidateIds[($i - 1) % count($candidateIds)],
                'project_id' => !empty($projectIds) ? $projectIds[($i - 1) % count($projectIds)] : null,
                'admin_id' => !empty($adminIds) ? $adminIds[($i - 1) % count($adminIds)] : null,
                'motivation_score' => $motivation,
                'profile_score' => $profile,
                'viability_score' => $viability,
                'total_score' => $motivation + $profile + $viability,
                'comment' => 'Evaluation ' . $i,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        $this->insertChunks('candidat_evaluation_grids', $rows);
    }

    private function seedBroadcastReads(): void
    {
        if (!$this->tableExists('admin_broadcasts') || !$this->tableExists('broadcast_reads')) {
            return;
        }

        if ((int) DB::table('admin_broadcasts')->count() === 0) {
            $adminId = DB::table('users')->value('id');
            if ($adminId) {
                DB::table('admin_broadcasts')->insert([
                    'admin_id' => $adminId,
                    'title' => 'Load Broadcast',
                    'message' => 'Generated by HeavyLoadSeeder',
                    'target_type' => 'all',
                    'target_candidat_ids' => null,
                    'target_candidat_id' => null,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->topUpTable('admin_broadcasts', $this->target);

        $count = (int) DB::table('broadcast_reads')->count();
        if ($count >= $this->target) {
            return;
        }

        $broadcastIds = DB::table('admin_broadcasts')->pluck('id')->all();
        $candidateIds = DB::table('candidat')->pluck('id')->all();
        if (empty($broadcastIds) || empty($candidateIds)) {
            return;
        }

        $existing = DB::table('broadcast_reads')
            ->select('broadcast_id', 'candidat_id')
            ->get()
            ->mapWithKeys(fn ($row) => [((string) $row->broadcast_id . '|' . (string) $row->candidat_id) => true])
            ->all();

        $now = now();
        $rows = [];
        foreach ($broadcastIds as $broadcastId) {
            foreach ($candidateIds as $candidateId) {
                if (($count + count($rows)) >= $this->target) {
                    break 2;
                }

                $key = (string) $broadcastId . '|' . (string) $candidateId;
                if (isset($existing[$key])) {
                    continue;
                }

                $rows[] = [
                    'broadcast_id' => $broadcastId,
                    'candidat_id' => $candidateId,
                    'read_at' => $now,
                ];
                $existing[$key] = true;
            }
        }

        if (!empty($rows)) {
            $this->insertChunks('broadcast_reads', $rows);
        }
    }

    private function seedDynamicFormSteps(): void
    {
        if (!$this->tableExists('dynamic_form_steps')) {
            return;
        }

        $count = (int) DB::table('dynamic_form_steps')->count();
        if ($count >= $this->target) {
            return;
        }

        $formIds = DB::table('dynamic_forms')->pluck('id')->all();
        if (empty($formIds)) {
            return;
        }

        $existing = DB::table('dynamic_form_steps')
            ->select('dynamic_form_id', 'step_number')
            ->get()
            ->mapWithKeys(fn ($row) => [((string) $row->dynamic_form_id . '|' . (string) $row->step_number) => true])
            ->all();

        $now = now();
        $rows = [];
        for ($step = 1; $step <= 4; $step++) {
            foreach ($formIds as $formId) {
                if (($count + count($rows)) >= $this->target) {
                    break 2;
                }

                $key = (string) $formId . '|' . (string) $step;
                if (isset($existing[$key])) {
                    continue;
                }

                $rows[] = [
                    'dynamic_form_id' => $formId,
                    'title' => 'Step ' . $step,
                    'title_ar' => null,
                    'description' => 'Generated step ' . $step,
                    'step_number' => $step,
                    'sort_order' => $step,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                $existing[$key] = true;
            }
        }

        if (!empty($rows)) {
            $this->insertChunks('dynamic_form_steps', $rows);
        }
    }

    private function seedCandidatProjectAgreements(): void
    {
        if (!$this->tableExists('candidat_project_agreements')) {
            return;
        }

        $count = (int) DB::table('candidat_project_agreements')->count();
        if ($count >= $this->target) {
            return;
        }

        $candidateIds = DB::table('candidat')->pluck('id')->all();
        $projectIds = DB::table('programe_list')->pluck('id')->all();
        if (empty($candidateIds) || empty($projectIds)) {
            return;
        }

        $existing = DB::table('candidat_project_agreements')
            ->select('candidat_id', 'project_id')
            ->get()
            ->mapWithKeys(fn ($row) => [((string) $row->candidat_id . '|' . (string) $row->project_id) => true])
            ->all();

        $now = now();
        $rows = [];
        foreach ($candidateIds as $candidateId) {
            foreach ($projectIds as $projectId) {
                if (($count + count($rows)) >= $this->target) {
                    break 2;
                }

                $key = (string) $candidateId . '|' . (string) $projectId;
                if (isset($existing[$key])) {
                    continue;
                }

                $rows[] = [
                    'candidat_id' => $candidateId,
                    'project_id' => $projectId,
                    'agreed_at' => $now,
                    'agreed_ip' => '127.0.0.1',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                $existing[$key] = true;
            }
        }

        if (!empty($rows)) {
            $this->insertChunks('candidat_project_agreements', $rows);
        }
    }

    private function seedRolePermissions(): void
    {
        if (!$this->tableExists('role_permissions')) {
            return;
        }

        $count = (int) DB::table('role_permissions')->count();
        if ($count >= $this->target) {
            return;
        }

        $roleNames = DB::table('roles')->pluck('name')->all();
        if (empty($roleNames)) {
            $roleNames = ['admin', 'manager', 'agent'];
        }

        $existing = DB::table('role_permissions')
            ->select('role_name', 'module_key')
            ->get()
            ->mapWithKeys(fn ($row) => [((string) $row->role_name . '|' . (string) $row->module_key) => true])
            ->all();

        $now = now();
        $rows = [];
        $module = 1;
        while (($count + count($rows)) < $this->target && $module <= 500) {
            foreach ($roleNames as $roleName) {
                if (($count + count($rows)) >= $this->target) {
                    break;
                }

                $moduleKey = 'module_load_' . $module;
                $key = (string) $roleName . '|' . $moduleKey;
                if (isset($existing[$key])) {
                    continue;
                }

                $rows[] = [
                    'role_name' => $roleName,
                    'module_key' => $moduleKey,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                $existing[$key] = true;
            }
            $module++;
        }

        if (!empty($rows)) {
            $this->insertChunks('role_permissions', $rows);
        }
    }

    private function listTables(): array
    {
        $dbName = DB::getDatabaseName();
        $rows = DB::select('SHOW TABLES');
        $key = 'Tables_in_' . $dbName;

        return array_values(array_map(static fn ($row) => $row->$key, $rows));
    }

    private function topUpTable(string $table, int $target): void
    {
        $count = (int) DB::table($table)->count();
        if ($count >= $target) {
            return;
        }

        if ($count === 0) {
            // If a table is empty and no bootstrap exists for it, skip gracefully.
            $this->command?->line("[skip] {$table}: empty table with no clone source");
            return;
        }

        $columnsMeta = DB::select("SHOW COLUMNS FROM `{$table}`");
        $insertableColumns = [];
        $types = [];

        foreach ($columnsMeta as $col) {
            $name = $col->Field;
            $type = strtolower((string) $col->Type);
            $extra = strtolower((string) $col->Extra);

            if (str_contains($extra, 'auto_increment')) {
                continue;
            }

            $insertableColumns[] = $name;
            $types[$name] = $type;
        }

        $singleUniqueColumns = $this->getSingleUniqueColumns($table);

        $attempts = 0;
        while ($count < $target && $attempts < 12) {
            $need = $target - $count;
            $batchSize = min(max(20, $need), 120);

            $sourceRows = DB::table($table)->inRandomOrder()->limit($batchSize)->get();
            if ($sourceRows->isEmpty()) {
                break;
            }

            $now = now();
            $suffix = Str::lower(Str::random(6));
            $payload = [];

            foreach ($sourceRows as $idx => $row) {
                $data = [];

                foreach ($insertableColumns as $column) {
                    $value = $row->$column ?? null;

                    if ($column === 'created_at' || $column === 'updated_at') {
                        $data[$column] = $now;
                        continue;
                    }

                    if ($column === 'deleted_at') {
                        $data[$column] = null;
                        continue;
                    }

                    if (in_array($column, $singleUniqueColumns, true)) {
                        $data[$column] = $this->mutateUniqueValue($column, $value, $types[$column], $suffix, $idx);
                        continue;
                    }

                    if (is_string($value) && strlen($value) > 0 && $this->looksLikeUniqueName($column)) {
                        $data[$column] = $this->mutateUniqueValue($column, $value, $types[$column], $suffix, $idx);
                        continue;
                    }

                    $data[$column] = $value;
                }

                $payload[] = $data;
            }

            if (!empty($payload)) {
                DB::table($table)->insertOrIgnore($payload);
            }

            $count = (int) DB::table($table)->count();
            $attempts++;
        }

        $this->command?->line("[ok] {$table}: {$count} rows");
    }

    private function mutateUniqueValue(string $column, mixed $value, string $type, string $suffix, int $idx): mixed
    {
        if ($value === null) {
            return null;
        }

        if (preg_match('/int|bigint|smallint|tinyint/', $type)) {
            $base = (int) $value;
            return $base + 100000 + $idx;
        }

        $val = trim((string) $value);
        if ($val === '') {
            return $val;
        }

        if ($column === 'email' || str_ends_with($column, '_email')) {
            $parts = explode('@', $val);
            if (count($parts) === 2) {
                return $parts[0] . '+' . $suffix . $idx . '@' . $parts[1];
            }
            return $val . '+' . $suffix . $idx . '@example.test';
        }

        if (str_contains($column, 'slug')) {
            return Str::slug($val) . '-' . $suffix . $idx;
        }

        if (strlen($val) > 240) {
            $val = substr($val, 0, 220);
        }

        return $val . '_' . $suffix . $idx;
    }

    private function looksLikeUniqueName(string $column): bool
    {
        $keys = [
            'slug',
            'email',
            'username',
            'cin',
            'matricule',
            'field_key',
            'table_key',
            'column_key',
            'phone',
            'uuid',
            'code',
        ];

        foreach ($keys as $key) {
            if (str_contains($column, $key)) {
                return true;
            }
        }

        return false;
    }

    private function getSingleUniqueColumns(string $table): array
    {
        $db = DB::getDatabaseName();
        $rows = DB::select(
            'SELECT index_name, GROUP_CONCAT(column_name ORDER BY seq_in_index) AS cols
             FROM information_schema.statistics
             WHERE table_schema = ? AND table_name = ? AND non_unique = 0 AND index_name <> "PRIMARY"
             GROUP BY index_name',
            [$db, $table]
        );

        $columns = [];
        foreach ($rows as $row) {
            $cols = explode(',', (string) $row->cols);
            if (count($cols) === 1) {
                $columns[] = $cols[0];
            }
        }

        return array_values(array_unique($columns));
    }

    private function bootstrapCoreData(): void
    {
        // Ensure some source rows exist for clone-based top-up.
        if (DB::table('users')->count() === 0) {
            DB::table('users')->insert([
                'name' => 'Load Admin',
                'email' => 'load-admin@example.test',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (DB::table('candidat')->count() === 0) {
            $candidatRow = [
                'nom' => 'Load',
                'prenom' => 'Candidate',
                'email' => 'load-candidat@example.test',
                'password' => bcrypt('password'),
                'phone' => '0600000000',
                'cin' => 'LOAD12345',
                'gender' => in_array('autre', $this->getSupportedCandidatGenders(), true) ? 'autre' : 'homme',
                'address' => 'Casablanca - Aïn Chock',
                'selected_region' => 'Casablanca-Settat',
                'selected_city' => 'Casablanca',
                'selected_prefecture' => 'Aïn Chock',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if ($this->hasColumn('candidat', 'login')) {
                $candidatRow['login'] = 'load-candidat';
            }

            DB::table('candidat')->insert($candidatRow);
        }

        if (DB::table('programe_list')->count() === 0) {
            DB::table('programe_list')->insert([
                'project_name' => 'Load Test Project',
                'description' => 'Generated for heavy load tests',
                'slug' => 'load-test-project',
                'icon' => 'ri-file-list-3-line',
                'color' => '#2f5496',
                'bg_color' => '#ffffff',
                'min_age' => 18,
                'max_age' => 45,
                'allowed_address_id' => json_encode([]),
                'allowed_location_ids' => json_encode([]),
                'candidature_types' => json_encode(['individuel']),
                'is_active' => 1,
                'created_by' => DB::table('users')->value('id'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (DB::table('dynamic_forms')->count() === 0) {
            $formId = DB::table('dynamic_forms')->insertGetId([
                'title' => 'Load Test Form',
                'slug' => 'load-test-form',
                'icon' => 'ri-file-list-3-line',
                'color' => '#2f5496',
                'bg_color' => '#ffffff',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $stepId = DB::table('dynamic_form_steps')->insertGetId([
                'dynamic_form_id' => $formId,
                'title' => 'Step 1',
                'step_number' => 1,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('dynamic_form_fields')->insert([
                'dynamic_form_step_id' => $stepId,
                'label' => 'Name',
                'field_key' => 'name',
                'type' => 'text',
                'is_required' => 1,
                'is_full_width' => 1,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $projectId = DB::table('programe_list')->value('id');
            if ($projectId) {
                DB::table('programe_formulaire')->insertOrIgnore([
                    'programe_id' => $projectId,
                    'formulaire_id' => $formId,
                    'order' => 1,
                    'status' => 'active',
                    'is_required' => 1,
                    'unlock_on_status' => 'approved',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    private function printSummary(): void
    {
        $tables = $this->listTables();
        $this->command?->info('--- Table counts summary ---');

        foreach ($tables as $table) {
            $count = (int) DB::table($table)->count();
            $this->command?->line(str_pad($table, 40, ' ') . ' : ' . $count);
        }
    }
}
