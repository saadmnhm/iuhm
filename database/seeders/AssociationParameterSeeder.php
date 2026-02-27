<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AssociationParameter;

class AssociationParameterSeeder extends Seeder
{
    public function run(): void
    {
        $params = [
            // General
            ['category' => 'general', 'key' => 'association_name',       'label' => 'Nom de l\'association',        'type' => 'text',     'sort_order' => 1],
            ['category' => 'general', 'key' => 'association_acronym',    'label' => 'Acronyme',                     'type' => 'text',     'sort_order' => 2],
            ['category' => 'general', 'key' => 'association_description','label' => 'Description',                  'type' => 'textarea', 'sort_order' => 3],
            ['category' => 'general', 'key' => 'association_logo',       'label' => 'Logo URL',                     'type' => 'url',      'sort_order' => 4],
            ['category' => 'general', 'key' => 'association_founded',    'label' => 'Année de création',            'type' => 'number',   'sort_order' => 5],
            ['category' => 'general', 'key' => 'association_address',    'label' => 'Adresse du siège',             'type' => 'textarea', 'sort_order' => 6],
            ['category' => 'general', 'key' => 'association_city',       'label' => 'Ville',                        'type' => 'text',     'sort_order' => 7],
            ['category' => 'general', 'key' => 'association_rc',         'label' => 'Registre de Commerce (RC)',    'type' => 'text',     'sort_order' => 8],
            ['category' => 'general', 'key' => 'association_if',         'label' => 'Identifiant Fiscal (IF)',      'type' => 'text',     'sort_order' => 9],
            ['category' => 'general', 'key' => 'association_ice',        'label' => 'ICE',                          'type' => 'text',     'sort_order' => 10],

            // Contact
            ['category' => 'contact', 'key' => 'contact_email',         'label' => 'Email principal',              'type' => 'email',    'sort_order' => 1],
            ['category' => 'contact', 'key' => 'contact_phone',         'label' => 'Téléphone',                    'type' => 'text',     'sort_order' => 2],
            ['category' => 'contact', 'key' => 'contact_whatsapp',      'label' => 'WhatsApp',                     'type' => 'text',     'sort_order' => 3],
            ['category' => 'contact', 'key' => 'contact_website',       'label' => 'Site web',                     'type' => 'url',      'sort_order' => 4],
            ['category' => 'contact', 'key' => 'contact_facebook',      'label' => 'Facebook',                     'type' => 'url',      'sort_order' => 5],
            ['category' => 'contact', 'key' => 'contact_instagram',     'label' => 'Instagram',                    'type' => 'url',      'sort_order' => 6],
            ['category' => 'contact', 'key' => 'contact_linkedin',      'label' => 'LinkedIn',                     'type' => 'url',      'sort_order' => 7],
            ['category' => 'contact', 'key' => 'contact_youtube',       'label' => 'YouTube',                      'type' => 'url',      'sort_order' => 8],

            // Finance
            ['category' => 'finance', 'key' => 'finance_bank',          'label' => 'Banque',                       'type' => 'text',     'sort_order' => 1],
            ['category' => 'finance', 'key' => 'finance_rib',           'label' => 'RIB',                          'type' => 'text',     'sort_order' => 2],
            ['category' => 'finance', 'key' => 'finance_iban',          'label' => 'IBAN',                         'type' => 'text',     'sort_order' => 3],
            ['category' => 'finance', 'key' => 'finance_swift',         'label' => 'Code SWIFT',                   'type' => 'text',     'sort_order' => 4],
            ['category' => 'finance', 'key' => 'finance_budget_annuel', 'label' => 'Budget annuel (MAD)',          'type' => 'number',   'sort_order' => 5],

            // RH
            ['category' => 'rh',      'key' => 'rh_president',          'label' => 'Président',                    'type' => 'text',     'sort_order' => 1],
            ['category' => 'rh',      'key' => 'rh_directeur',          'label' => 'Directeur',                    'type' => 'text',     'sort_order' => 2],
            ['category' => 'rh',      'key' => 'rh_secretaire',         'label' => 'Secrétaire Général',           'type' => 'text',     'sort_order' => 3],
            ['category' => 'rh',      'key' => 'rh_tresorier',          'label' => 'Trésorier',                    'type' => 'text',     'sort_order' => 4],
            ['category' => 'rh',      'key' => 'rh_nb_employes',        'label' => 'Nombre d\'employés',           'type' => 'number',   'sort_order' => 5],
            ['category' => 'rh',      'key' => 'rh_nb_benevoles',       'label' => 'Nombre de bénévoles',          'type' => 'number',   'sort_order' => 6],

            // SEO
            ['category' => 'seo',     'key' => 'seo_meta_title',        'label' => 'Meta Title',                   'type' => 'text',     'sort_order' => 1],
            ['category' => 'seo',     'key' => 'seo_meta_description',  'label' => 'Meta Description',             'type' => 'textarea', 'sort_order' => 2],
            ['category' => 'seo',     'key' => 'seo_meta_keywords',     'label' => 'Mots-clés',                    'type' => 'text',     'sort_order' => 3],
            ['category' => 'seo',     'key' => 'seo_google_analytics',  'label' => 'Google Analytics ID',          'type' => 'text',     'sort_order' => 4],
        ];

        foreach ($params as $param) {
            AssociationParameter::updateOrCreate(
                ['key' => $param['key']],
                $param
            );
        }
    }
}
