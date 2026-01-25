<?php

namespace App\Livewire\Concerns;

trait HasValidationRules
{

    protected function step1Rules(): array
    {
        return [
            'profile_image' => 'required|image|max:2048',
            'age' => 'required|integer|min:18|max:100',
            'gender' => 'required|in:homme,femme',
            'address_house' => 'required|in:Hay Mohamadi,Ain Sbaa,Roches Noires',
            'email' => 'required|email|max:255',
            'phone' => 'required|regex:/^[0-9]{10}$/',            
            'project_name' => 'required|string',
            'ceo_name' => 'required|string',
            'description' => 'required|string',
            'legal_structure' => 'required|string',
            'resume_executif' => 'required|string'
        ];
    }

    protected function step2Rules(): array
    {
        return [
            'table1Rows.*.product_name' => 'required|string',
            'table1Rows.*.description' => 'required|string',
            'public_cible' => 'required|string',
            'concurrent' => 'required|string',
            'volume_produits_locaux' => 'required|string',
            'volume_demande' => 'required|string',
            'demande_offre' => 'required|string',
            'motivations_achat' => 'required|string',
            'raison_choix_client' => 'required|string'
        ];
    }

    protected function step3Rules(): array
    {
        return [
            'méthodes_marketing' => 'required|string',
            'adaptation_methodes' => 'required|string',
            'differenciation_marketing' => 'required|string',
            'table2Rows.*.item' => 'required|string',
            'table2Rows.*.total_employee_1' => 'required|numeric|min:0',
            'table2Rows.*.total_employee_2' => 'required|numeric|min:0',
            'plan_affaires' => 'required|string', 
            'obtention_financement' => 'required|string',
            'ouverture_proces' => 'required|string',
            'lancement_recrutement' => 'required|string',
            'ouverture_definitive' => 'required|string',
            'duree' => 'required|string'
        ];
    }

    protected function step4Rules(): array
    {
        return [
            'lieu_projet' => 'required|string',
            'adaptation_lieu' => 'required|string',
            'table3Rows.*.product_name_presentation' => 'required|string',
            'table3Rows.*.presentation_methode' => 'required|string',
            'table4Rows.*.product_name_livraison' => 'required|string',
            'table4Rows.*.livraison_methode' => 'required|string',
            'benefices_from_projet' => 'required|string',
            'valeur_projet' => 'required|string'
        ];
    }

    protected function step5Rules(): array
    {
        return [
            // Capacités
            'step_8_1' => 'required|string',
            'step_8_2' => 'required|string',
            'step_8_3' => 'required|string',
            'step_8_4' => 'required|string',
            
            // Programme d'investissement
            'couts_creation' => 'required|numeric|min:0',
            'preparation_entreprise' => 'required|numeric|min:0',
            'achat_machines' => 'required|numeric|min:0',
            'achat_matieres_premieres' => 'required|numeric|min:0',
            'autres_couts' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            
            // Prévisions de revenus
            'ventes_premiere_annee' => 'required|numeric|min:0',
            'ventes_deuxieme_annee' => 'required|numeric|min:0',
            'ventes_troisieme_annee' => 'required|numeric|min:0',
            'services_premiere_annee' => 'required|numeric|min:0',
            'services_deuxieme_annee' => 'required|numeric|min:0',
            'services_troisieme_annee' => 'required|numeric|min:0',
            'aide_financiere_premiere_annee' => 'required|numeric|min:0',
            'aide_financiere_deuxieme_annee' => 'required|numeric|min:0',
            'aide_financiere_troisieme_annee' => 'required|numeric|min:0',
            'revenus_financiers_premiere_annee' => 'required|numeric|min:0',
            'revenus_financiers_deuxieme_annee' => 'required|numeric|min:0',
            'revenus_financiers_troisieme_annee' => 'required|numeric|min:0',
            'autres_revenus_premiere_annee' => 'required|numeric|min:0',
            'autres_revenus_deuxieme_annee' => 'required|numeric|min:0',
            'autres_revenus_troisieme_annee' => 'required|numeric|min:0',
            'total_revenus_premiere_annee' => 'required|numeric|min:0',
            'total_revenus_deuxieme_annee' => 'required|numeric|min:0',
            'total_revenus_troisieme_annee' => 'required|numeric|min:0',
        ];
    }

    protected function step6Rules(): array
    {
        return [
            // Frais prévus
            'achat_prevue_premiere_annee' => 'required|numeric|min:0',
            'achat_prevue_deuxieme_annee' => 'required|numeric|min:0',
            'achat_prevue_troisieme_annee' => 'required|numeric|min:0',
            'frais_fonctionnement_premiere_annee' => 'required|numeric|min:0',
            'frais_fonctionnement_deuxieme_annee' => 'required|numeric|min:0',
            'frais_fonctionnement_troisieme_annee' => 'required|numeric|min:0',
            'charges_personnel_premiere_annee' => 'required|numeric|min:0',
            'charges_personnel_deuxieme_annee' => 'required|numeric|min:0',
            'charges_personnel_troisieme_annee' => 'required|numeric|min:0',
            'dettes_premiere_annee' => 'required|numeric|min:0',
            'dettes_deuxieme_annee' => 'required|numeric|min:0',
            'dettes_troisieme_annee' => 'required|numeric|min:0',
            'etablissement_bancaire_premiere_annee' => 'required|numeric|min:0',
            'etablissement_bancaire_deuxieme_annee' => 'required|numeric|min:0',
            'etablissement_bancaire_troisieme_annee' => 'required|numeric|min:0',
            'fournisseurs_premiere_annee' => 'required|numeric|min:0',
            'fournisseurs_deuxieme_annee' => 'required|numeric|min:0',
            'fournisseurs_troisieme_annee' => 'required|numeric|min:0',
            'autres_dettes_premiere_annee' => 'required|numeric|min:0',
            'autres_dettes_deuxieme_annee' => 'required|numeric|min:0',
            'autres_dettes_troisieme_annee' => 'required|numeric|min:0',
            'autres_charges_premiere_annee' => 'required|numeric|min:0',
            'autres_charges_deuxieme_annee' => 'required|numeric|min:0',
            'autres_charges_troisieme_annee' => 'required|numeric|min:0',
            'total_frais_premiere_annee' => 'required|numeric|min:0',
            'total_frais_deuxieme_annee' => 'required|numeric|min:0',
            'total_frais_troisieme_annee' => 'required|numeric|min:0',
            
            // Résultat
            'revenus_premiere_annee' => 'required|numeric|min:0',
            'revenus_deuxieme_annee' => 'required|numeric|min:0',
            'revenus_troisieme_annee' => 'required|numeric|min:0',
            'depenses_premiere_annee' => 'required|numeric|min:0',
            'depenses_deuxieme_annee' => 'required|numeric|min:0',
            'depenses_troisieme_annee' => 'required|numeric|min:0',
            'resultat_premiere_annee' => 'required|numeric',
            'resultat_deuxieme_annee' => 'required|numeric',
            'resultat_troisieme_annee' => 'required|numeric',
            
            // Text fields
            'generer_profits' => 'required|string',
            'projet_durable' => 'required|string',
        ];
    }

    protected function step7Rules(): array
    {
        return [
            'table5Rows.*.equipement' => 'required|string',
            'table5Rows.*.reference' => 'required|string',
            'table5Rows.*.prix_equipement' => 'required|numeric|min:0',
        ];
    }

    protected function step8Rules(): array
    {
        return [
            'table6Rows.*.matiere_premiere' => 'required|string',
            'table6Rows.*.comment_procurer' => 'required|string',
            'table6Rows.*.fournisseur_matiere' => 'required|string',
        ];
    }
}
