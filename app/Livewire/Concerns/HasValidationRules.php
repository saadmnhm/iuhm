<?php

namespace App\Livewire\Concerns;

trait HasValidationRules
{

    protected function step1Rules(): array
    {
        return [
            'profile_image' => 'nullable|image|max:2048',
            'age' => 'nullable|integer|min:18|max:100',
            'gender' => 'nullable|in:homme,femme',
            'address_house' => 'nullable|in:hay_mohamadi,ain_sbaa,rochnoir',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|regex:/^[0-9]{10}$/',            
            'project_name' => 'nullable|string',
            'ceo_name' => 'nullable|string',
            'description' => 'nullable|string',
            'legal_structure' => 'nullable|string',
            'resume_executif' => 'nullable|string'
        ];
    }

    protected function step2Rules(): array
    {
        return [
            'table1Rows.*.product_name' => 'nullable|string',
            'table1Rows.*.description' => 'nullable|string',
            'public_cible' => 'nullable|string',
            'concurrent' => 'nullable|string',
            'volume_produits_locaux' => 'nullable|string',
            'volume_demande' => 'nullable|string',
            'demande_offre' => 'nullable|string',
            'motivations_achat' => 'nullable|string',
            'raison_choix_client' => 'nullable|string'
        ];
    }

    protected function step3Rules(): array
    {
        return [
            'méthodes_marketing' => 'nullable|string',
            'adaptation_methodes' => 'nullable|string',
            'differenciation_marketing' => 'nullable|string',
            'table2Rows.*.item' => 'nullable|string',
            'table2Rows.*.price_1' => 'nullable|numeric|min:0',
            'table2Rows.*.price_2' => 'nullable|numeric|min:0',
            'plan_affaires' => 'nullable|string', 
            'obtention_financement' => 'nullable|string',
            'ouverture_proces' => 'nullable|string',
            'lancement_recrutement' => 'nullable|string',
            'ouverture_definitive' => 'nullable|string',
            'duree' => 'nullable|string'
        ];
    }

    protected function step4Rules(): array
    {
        return [
            'lieu_projet' => 'nullable|string',
            'adaptation_lieu' => 'nullable|string',
            'table3Rows.*.product_name_presentation' => 'nullable|string',
            'table3Rows.*.presentation_methode' => 'nullable|string',
            'table4Rows.*.product_name_livraison' => 'nullable|string',
            'table4Rows.*.livraison_methode' => 'nullable|string',
            'benefices_from_projet' => 'nullable|string',
            'valeur_projet' => 'nullable|string'
        ];
    }

    protected function step5Rules(): array
    {
        return [
            // Capacités
            'step_8_1' => 'nullable|string',
            'step_8_2' => 'nullable|string',
            'step_8_3' => 'nullable|string',
            'step_8_4' => 'nullable|string',
            
            // Programme d'investissement
            'couts_creation' => 'nullable|numeric|min:0',
            'preparation_entreprise' => 'nullable|numeric|min:0',
            'achat_machines' => 'nullable|numeric|min:0',
            'achat_matieres_premieres' => 'nullable|numeric|min:0',
            'autres_couts' => 'nullable|numeric|min:0',
            'total' => 'nullable|numeric|min:0',
            
            // Prévisions de revenus
            'ventes_premiere_annee' => 'nullable|numeric|min:0',
            'ventes_deuxieme_annee' => 'nullable|numeric|min:0',
            'ventes_troisieme_annee' => 'nullable|numeric|min:0',
            'services_premiere_annee' => 'nullable|numeric|min:0',
            'services_deuxieme_annee' => 'nullable|numeric|min:0',
            'services_troisieme_annee' => 'nullable|numeric|min:0',
            'aide_financiere_premiere_annee' => 'nullable|numeric|min:0',
            'aide_financiere_deuxieme_annee' => 'nullable|numeric|min:0',
            'aide_financiere_troisieme_annee' => 'nullable|numeric|min:0',
            'revenus_financiers_premiere_annee' => 'nullable|numeric|min:0',
            'revenus_financiers_deuxieme_annee' => 'nullable|numeric|min:0',
            'revenus_financiers_troisieme_annee' => 'nullable|numeric|min:0',
            'autres_revenus_premiere_annee' => 'nullable|numeric|min:0',
            'autres_revenus_deuxieme_annee' => 'nullable|numeric|min:0',
            'autres_revenus_troisieme_annee' => 'nullable|numeric|min:0',
            'total_revenus_premiere_annee' => 'nullable|numeric|min:0',
            'total_revenus_deuxieme_annee' => 'nullable|numeric|min:0',
            'total_revenus_troisieme_annee' => 'nullable|numeric|min:0',
        ];
    }

    protected function step6Rules(): array
    {
        return [
            // Frais prévus
            'achat_prevue_premiere_annee' => 'nullable|numeric|min:0',
            'achat_prevue_deuxieme_annee' => 'nullable|numeric|min:0',
            'achat_prevue_troisieme_annee' => 'nullable|numeric|min:0',
            'frais_fonctionnement_premiere_annee' => 'nullable|numeric|min:0',
            'frais_fonctionnement_deuxieme_annee' => 'nullable|numeric|min:0',
            'frais_fonctionnement_troisieme_annee' => 'nullable|numeric|min:0',
            'charges_personnel_premiere_annee' => 'nullable|numeric|min:0',
            'charges_personnel_deuxieme_annee' => 'nullable|numeric|min:0',
            'charges_personnel_troisieme_annee' => 'nullable|numeric|min:0',
            'dettes_premiere_annee' => 'nullable|numeric|min:0',
            'dettes_deuxieme_annee' => 'nullable|numeric|min:0',
            'dettes_troisieme_annee' => 'nullable|numeric|min:0',
            'etablissement_bancaire_premiere_annee' => 'nullable|numeric|min:0',
            'etablissement_bancaire_deuxieme_annee' => 'nullable|numeric|min:0',
            'etablissement_bancaire_troisieme_annee' => 'nullable|numeric|min:0',
            'fournisseurs_premiere_annee' => 'nullable|numeric|min:0',
            'fournisseurs_deuxieme_annee' => 'nullable|numeric|min:0',
            'fournisseurs_troisieme_annee' => 'nullable|numeric|min:0',
            'autres_dettes_premiere_annee' => 'nullable|numeric|min:0',
            'autres_dettes_deuxieme_annee' => 'nullable|numeric|min:0',
            'autres_dettes_troisieme_annee' => 'nullable|numeric|min:0',
            'autres_charges_premiere_annee' => 'nullable|numeric|min:0',
            'autres_charges_deuxieme_annee' => 'nullable|numeric|min:0',
            'autres_charges_troisieme_annee' => 'nullable|numeric|min:0',
            'total_frais_premiere_annee' => 'nullable|numeric|min:0',
            'total_frais_deuxieme_annee' => 'nullable|numeric|min:0',
            'total_frais_troisieme_annee' => 'nullable|numeric|min:0',
            
            // Résultat
            'revenus_premiere_annee' => 'nullable|numeric|min:0',
            'revenus_deuxieme_annee' => 'nullable|numeric|min:0',
            'revenus_troisieme_annee' => 'nullable|numeric|min:0',
            'depenses_premiere_annee' => 'nullable|numeric|min:0',
            'depenses_deuxieme_annee' => 'nullable|numeric|min:0',
            'depenses_troisieme_annee' => 'nullable|numeric|min:0',
            'resultat_premiere_annee' => 'nullable|numeric',
            'resultat_deuxieme_annee' => 'nullable|numeric',
            'resultat_troisieme_annee' => 'nullable|numeric',
            
            // Text fields
            'generer_profits' => 'nullable|string',
            'projet_durable' => 'nullable|string',
        ];
    }

    protected function step7Rules(): array
    {
        return [
            'table5Rows.*.equipement' => 'nullable|string',
            'table5Rows.*.reference' => 'nullable|string',
            'table5Rows.*.prix_equipement' => 'nullable|numeric|min:0',
        ];
    }

    protected function step8Rules(): array
    {
        return [
            'table6Rows.*.matiere_premiere' => 'nullable|string',
            'table6Rows.*.comment_procurer' => 'nullable|string',
            'table6Rows.*.fournisseur_matiere' => 'nullable|string',
        ];
    }
}
