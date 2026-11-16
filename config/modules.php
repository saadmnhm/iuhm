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

        // ── Dashboard & Administration ─────────────────────────
        'users' => [
            'label' => 'Gestion Admin',
            'icon' => 'ri-admin-line',
        ],
        'gestion_roles' => [
            'label' => 'Gestion des Rôles',
            'icon' => 'ri-key-line',
        ],
        'addresses' => [
            'label' => 'Gestion des Adresses',
            'icon' => 'ri-map-pin-line',
        ],
        'association_parameters' => [
            'label' => 'Paramètres Association',
            'icon' => 'ri-settings-3-line',
        ],

        // ── Beneficiaries & Forms ───────────────────────────────
        'candidats' => [
            'label' => 'Base de Bénéficiaires',
            'icon' => 'ri-user-community-line',
        ],
        'formulaires' => [
            'label' => 'Gestion des Formulaires',
            'icon' => 'ri-file-list-3-line',
        ],

        // ── Programmes & Projects ───────────────────────────────
        'programe' => [
            'label' => 'Gestion Programmes',
            'icon' => 'ri-trello-fill',
        ],
        'programmes' => [
            'label' => 'Soumissions des Programmes',
            'icon' => 'ri-group-line',
        ],
        'project' => [
            'label' => 'Gestion Projets',
            'icon' => 'ri-folder-line',
        ],
        'clc' => [
            'label' => 'Gestion CLC',
            'icon' => 'ri-community-line',
        ],
        'zettat' => [
            'label' => 'Gestion ZETTAT',
            'icon' => 'ri-map-pin-2-line',
        ],

        // ── Submissions ─────────────────────────────────────────
        'my_submissions' => [
            'label' => 'Mes Assignations',
            'icon' => 'ri-task-line',
        ],
        'all_submissions' => [
            'label' => 'Toutes les Soumissions',
            'icon' => 'ri-file-list-3-line',
        ],
        'history_audit' => [
            'label' => 'Historique & Audit',
            'icon' => 'ri-git-commit-line',
        ],

        // ── Human Resources ─────────────────────────────────────
        'rh' => [
            'label' => 'Gestion RH',
            'icon' => 'ri-team-line',
        ],

        // ── Finance & Inventory ─────────────────────────────────
        'finance' => [
            'label' => 'Finance',
            'icon' => 'ri-wallet-line',
        ],
        'material' => [
            'label' => 'Matériel & Inventaire',
            'icon' => 'ri-box-line',
        ],

        // ── Website & Media Management ──────────────────────────
        'media' => [
            'label' => 'Gestion Média',
            'icon' => 'ri-image-line',
        ],
        'article' => [
            'label' => 'Gestion des Articles',
            'icon' => 'ri-article-line',
        ],
        'actualite' => [
            'label' => 'Gestion des Actualités',
            'icon' => 'ri-newspaper-line',
        ],
        'publication' => [
            'label' => 'Gestion des Publications',
            'icon' => 'ri-file-text-line',
        ],
        'blog' => [
            'label' => 'Newsletters & Blog',
            'icon' => 'ri-mail-line',
        ],
        'contact' => [
            'label' => 'Gestion des Contacts',
            'icon' => 'ri-contacts-line',
        ],
        'apropos' => [
            'label' => 'Page À Propos',
            'icon' => 'ri-information-line',
        ],
        'statistics' => [
            'label' => 'Statistiques du Site',
            'icon' => 'ri-bar-chart-line',
        ],

        // ── Communication & Support ─────────────────────────────
        'chat' => [
            'label' => 'Chat & Broadcast',
            'icon' => 'ri-chat-3-line',
        ],
        'support' => [
            'label' => 'Support Tickets',
            'icon' => 'ri-customer-service-2-line',
        ],

        // ── System Administration ───────────────────────────────
        'activity_logs' => [
            'label' => 'Activity Logs',
            'icon' => 'ri-history-line',
        ],
        'dev_tools' => [
            'label' => 'Developer Tools',
            'icon' => 'ri-code-s-slash-line',
        ],

    ],
];
