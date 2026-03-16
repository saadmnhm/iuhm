<?php

/**
 * Module Definitions
 *
 * Each key is the module identifier used in:
 *   - Route middleware:  ->middleware('module:rh')
 *   - Blade directive:   @canModule('rh') ... @endCanModule
 *   - DB table:          role_permissions.module_key
 *
 * To add a new module: add one entry here, protect its route,
 * and wrap its sidebar link. Permissions are managed via the
 * Gestion des Rôles UI — no code changes needed.
 */

return [
    'definitions' => [
        // ── Top nav ───────────────────────────────────────────────────────────
        'programmes'             => ['label' => 'Programmes (soumissions)',  'icon' => 'ri-group-line'],
        'support'                => ['label' => 'Support Tickets',           'icon' => 'ri-customer-service-2-line'],
        'chat'                   => ['label' => 'Chat & Broadcast',          'icon' => 'ri-chat-3-line'],
        'my_submissions'         => ['label' => 'Mes Assignations',          'icon' => 'ri-task-line'],
        'all_submissions'        => ['label' => 'Toutes Soumissions',        'icon' => 'ri-file-list-3-line'],
        'history_audit'          => ['label' => 'Historique & Audit',        'icon' => 'ri-git-commit-line'],
        // ── Referential ───────────────────────────────────────────────────────
        'programe'               => ['label' => 'Gestion Programmes',        'icon' => 'ri-trello-fill'],
        'users'                  => ['label' => 'Gestion Admin',             'icon' => 'ri-admin-line'],
        'candidats'              => ['label' => 'Base de Bénéficiaires',          'icon' => 'ri-user-community-line'],
        'addresses'              => ['label' => 'Addresses',                 'icon' => 'ri-map-pin-line'],
        'formulaires'            => ['label' => 'Formulaires',               'icon' => 'ri-file-list-3-line'],
        'activity_logs'          => ['label' => 'Activity Logs',             'icon' => 'ri-history-line'],
        'rh'                     => ['label' => 'Gestion RH',                'icon' => 'ri-team-line'],
        'gestion_roles'          => ['label' => 'Gestion des Rôles',         'icon' => 'ri-key-line'],
        'association_parameters' => ['label' => 'Paramètres Association',    'icon' => 'ri-settings-3-line'],
        'blog'                   => ['label' => 'Blog & Actualités',         'icon' => 'ri-article-line'],
        'dev_tools'              => ['label' => 'Dev Tools',                 'icon' => 'ri-code-s-slash-line'],
        "association"            => ['label' => 'Association',               'icon' => 'ri-building-2-line'],
        'finance'                => ['label' => 'Finance',                   'icon' => 'ri-wallet-line'],
        'material'               => ['label' => 'Matériel & Inventaire',     'icon' => 'ri-box-line'],
        ],
];
