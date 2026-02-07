<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BusinessPlan;
use App\Models\BusinessPlanProduct;
use App\Models\BusinessPlanEmployee;
use App\Models\BusinessPlanEquipment;
use App\Models\BusinessPlanRawMaterial;
use App\Models\BusinessPlanDelivery;
use App\Models\BusinessPlanPresentation;
use App\Models\BusinessPlanFinancial;
use App\Models\Candidat;
use Illuminate\Support\Facades\DB;

class GenerateTestBusinessPlan extends Command
{
    protected $signature = 'test:business-plan {candidat_id?}';
    protected $description = 'Generate a complete test business plan with all related data';

    public function handle()
    {
        $candidatId = $this->argument('candidat_id');
        
        if (!$candidatId) {
            $candidat = Candidat::first();
            if (!$candidat) {
                $this->error('No candidat found. Please create a candidat first.');
                return 1;
            }
            $candidatId = $candidat->id;
        } else {
            $candidat = Candidat::find($candidatId);
            if (!$candidat) {
                $this->error("Candidat with ID {$candidatId} not found.");
                return 1;
            }
        }

        $this->info("Creating test business plan for: {$candidat->nom} {$candidat->prenom}");

        DB::beginTransaction();
        try {
            // Create Business Plan
            $businessPlan = BusinessPlan::create([
                'candidat_id' => $candidatId,
                'form_type' => 'business_plan',
                'status' => 'submitted',
                'current_step' => 10,
                
                // Step 1 - Project Info
                'project_name' => 'Café Bio & Restaurant Écologique',
                'description' => 'Un concept innovant de café-restaurant proposant uniquement des produits biologiques et locaux, avec un engagement fort pour l\'environnement et la durabilité.',
                'registration' => 'RC-' . rand(100000, 999999),
                'legal_structure' => 'SARL',
                'resume_executif' => 'Notre projet vise à créer un espace convivial proposant une cuisine saine et responsable. Nous nous engageons à travailler avec des producteurs locaux et à minimiser notre impact environnemental.',
                
                // Step 2 - Market Analysis
                'public_cible' => 'Jeunes professionnels (25-45 ans), familles soucieuses de leur santé, étudiants, et personnes sensibles à l\'écologie.',
                'concurrent' => 'Actuellement, 3 restaurants bio dans un rayon de 5km, mais aucun n\'offre notre concept complet (café + restaurant + espace coworking).',
                'volume_produits_locaux' => 'Nous prévoyons d\'acheter 80% de nos produits auprès de producteurs locaux dans un rayon de 50km.',
                'volume_demande' => 'Estimation de 150-200 clients par jour en semaine, 250-300 le weekend.',
                'demande_offre' => 'La demande pour l\'alimentation bio et locale est en croissance de 15% par an dans notre région. L\'offre actuelle ne répond qu\'à 40% de cette demande.',
                'motivations_achat' => 'Santé, qualité des produits, engagement environnemental, proximité, ambiance chaleureuse.',
                'raison_choix_client' => '1. Produits 100% bio et locaux. 2. Prix compétitifs. 3. Espace moderne et confortable. 4. Engagement social (emploi local, formation).',
                
                // Step 3 - Marketing & Timeline
                'méthodes_marketing' => 'Réseaux sociaux (Instagram, Facebook), partenariats avec entreprises locales, événements communautaires, programme de fidélité, marketing d\'influence.',
                'adaptation_methodes' => 'Nous utilisons des influenceurs locaux spécialisés en alimentation saine, organisons des ateliers culinaires mensuels, et maintenons une présence active sur les réseaux sociaux avec du contenu authentique.',
                'differenciation_marketing' => 'Notre communication met en avant notre transparence totale : visite des fournisseurs en vidéo, recettes en ligne, impact environnemental mesuré et partagé mensuellement.',
                'plan_affaires' => '2026-03-01',
                'obtention_financement' => '2026-04-15',
                'ouverture_proces' => '2026-06-01',
                'lancement_recrutement' => '2026-05-01',
                'ouverture_definitive' => '2026-07-01',
                'duree' => '18 mois',
                
                // Step 4 - Location & Distribution
                'lieu_projet' => 'Centre-ville de Casablanca, quartier Maarif, 200m² avec terrasse de 50m²',
                'adaptation_lieu' => 'Le quartier Maarif est un quartier dynamique avec forte concentration de bureaux, écoles et résidences. Excellente accessibilité (métro, parking, pistes cyclables).',
                'benefices_from_projet' => 'Création de 15 emplois directs, soutien à 20 producteurs locaux, réduction des déchets de 80%, contribution à l\'éducation nutritionnelle.',
                'valeur_projet' => 'Valeur sociale : emploi et formation. Valeur environnementale : réduction de l\'empreinte carbone. Valeur économique : dynamisation du commerce local.',
                
                // Step 5 - Capacities
                'step_8_1' => 'Équipe fondatrice avec 15 ans d\'expérience cumulée en restauration et gestion.',
                'step_8_2' => 'Partenariats confirmés avec 12 producteurs locaux et 2 coopératives bio.',
                'step_8_3' => 'Local commercial sécurisé avec bail de 5 ans. Équipement professionnel commandé.',
                'step_8_4' => 'Financement initial de 500,000 MAD (fonds propres + prêts confirmés).',
                
                // Step 6 - Investment Program
                'couts_creation' => 150000.00,
                'preparation_entreprise' => 50000.00,
                'achat_machines' => 200000.00,
                'achat_matieres_premieres' => 75000.00,
                'autres_couts' => 25000.00,
                'total' => 500000.00,
                
                // Step 7 - Financial Questions
                'generer_profits' => 'Année 1 : Break-even. Année 2 : 15% de marge nette. Année 3 : 25% de marge nette. Prévision de CA : 2.5M MAD la première année.',
                'projet_durable' => 'Oui. Contrats long-terme avec fournisseurs, base de clients fidèles via programme de fidélité, diversification des revenus (restaurant, traiteur, formations culinaires).',
                
                'submitted_at' => now(),
            ]);

            $this->info("✓ Business Plan created (ID: {$businessPlan->id})");

            // Create Products
            $products = [
                ['product_name' => 'Menu Déjeuner Bio', 'description' => 'Plat du jour + dessert + boisson (prix: 85 MAD, quantité: 50/jour)'],
                ['product_name' => 'Salade Composée', 'description' => 'Salade fraîche avec légumes de saison (prix: 65 MAD, quantité: 30/jour)'],
                ['product_name' => 'Burger Végétarien', 'description' => 'Burger maison avec galette de légumes (prix: 75 MAD, quantité: 25/jour)'],
                ['product_name' => 'Café & Pâtisserie', 'description' => 'Café bio équitable + pâtisserie artisanale (prix: 35 MAD, quantité: 100/jour)'],
                ['product_name' => 'Jus Frais Pressé', 'description' => 'Jus de fruits et légumes de saison (prix: 25 MAD, quantité: 80/jour)'],
            ];

            foreach ($products as $index => $product) {
                BusinessPlanProduct::create([
                    'business_plan_id' => $businessPlan->id,
                    'product_name' => $product['product_name'],
                    'description' => $product['description'],
                    'sort_order' => $index + 1,
                ]);
            }
            $this->info("✓ Created {$businessPlan->products()->count()} products");

            // Create Employees
            $employees = [
                ['item' => 'Gérant - 15,000 MAD', 'total_1' => '1', 'total_2' => '15000'],
                ['item' => 'Chef Cuisinier - 12,000 MAD', 'total_1' => '1', 'total_2' => '12000'],
                ['item' => 'Sous-Chef - 8,000 MAD', 'total_1' => '1', 'total_2' => '8000'],
                ['item' => 'Serveurs - 4,500 MAD', 'total_1' => '3', 'total_2' => '13500'],
                ['item' => 'Barista - 5,000 MAD', 'total_1' => '1', 'total_2' => '5000'],
                ['item' => 'Plongeur - 3,500 MAD', 'total_1' => '1', 'total_2' => '3500'],
                ['item' => 'Responsable Marketing - 10,000 MAD', 'total_1' => '1', 'total_2' => '10000'],
            ];

            foreach ($employees as $index => $employee) {
                BusinessPlanEmployee::create([
                    'business_plan_id' => $businessPlan->id,
                    'item' => $employee['item'],
                    'total_employee_1' => $employee['total_1'],
                    'total_employee_2' => $employee['total_2'],
                    'sort_order' => $index + 1,
                ]);
            }
            $this->info("✓ Created {$businessPlan->employees()->count()} employees");

            // Create Equipment
            $equipment = [
                ['equipement' => 'Four professionnel', 'reference' => 'REF-001', 'prix_equipement' => '35000'],
                ['equipement' => 'Frigo professionnel (x2)', 'reference' => 'REF-002', 'prix_equipement' => '30000'],
                ['equipement' => 'Machine à café professionnelle', 'reference' => 'REF-003', 'prix_equipement' => '45000'],
                ['equipement' => 'Batterie de cuisine complète', 'reference' => 'REF-004', 'prix_equipement' => '25000'],
                ['equipement' => 'Tables et chaises (40 places)', 'reference' => 'REF-005', 'prix_equipement' => '40000'],
                ['equipement' => 'Système de caisse POS (x2)', 'reference' => 'REF-006', 'prix_equipement' => '25000'],
            ];

            foreach ($equipment as $index => $item) {
                BusinessPlanEquipment::create([
                    'business_plan_id' => $businessPlan->id,
                    'equipement' => $item['equipement'],
                    'reference' => $item['reference'],
                    'prix_equipement' => $item['prix_equipement'],
                    'sort_order' => $index + 1,
                ]);
            }
            $this->info("✓ Created {$businessPlan->equipment()->count()} equipment items");

            // Create Raw Materials
            $rawMaterials = [
                ['matiere_premiere' => 'Légumes bio de saison - 500 kg', 'comment_procurer' => 'Livraison hebdomadaire', 'fournisseur_matiere' => 'Coopérative Beni Mellal'],
                ['matiere_premiere' => 'Fruits bio - 300 kg', 'comment_procurer' => 'Livraison bi-hebdomadaire', 'fournisseur_matiere' => 'Ferme El Jadida'],
                ['matiere_premiere' => 'Viande bio - 150 kg', 'comment_procurer' => 'Commande mensuelle', 'fournisseur_matiere' => 'Élevage Atlas'],
                ['matiere_premiere' => 'Café bio équitable - 50 kg', 'comment_procurer' => 'Importation mensuelle', 'fournisseur_matiere' => 'Importateur Maroc Bio'],
                ['matiere_premiere' => 'Farine bio - 100 kg', 'comment_procurer' => 'Livraison hebdomadaire', 'fournisseur_matiere' => 'Moulin traditionnel Fès'],
                ['matiere_premiere' => 'Produits laitiers bio - 200 L', 'comment_procurer' => 'Livraison quotidienne', 'fournisseur_matiere' => 'Ferme laitière Ifrane'],
            ];

            foreach ($rawMaterials as $index => $material) {
                BusinessPlanRawMaterial::create([
                    'business_plan_id' => $businessPlan->id,
                    'matiere_premiere' => $material['matiere_premiere'],
                    'comment_procurer' => $material['comment_procurer'],
                    'fournisseur_matiere' => $material['fournisseur_matiere'],
                    'sort_order' => $index + 1,
                ]);
            }
            $this->info("✓ Created {$businessPlan->rawMaterials()->count()} raw materials");

            // Create Delivery Methods
            $deliveries = [
                ['product_name' => 'Livraison à domicile', 'livraison_methode' => 'Service via flotte de vélos électriques - 15 MAD'],
                ['product_name' => 'Click & Collect', 'livraison_methode' => 'Commande en ligne, retrait sur place - Gratuit'],
                ['product_name' => 'Partenariat Glovo/Jumia', 'livraison_methode' => 'Livraison via plateformes - 20 MAD'],
            ];

            foreach ($deliveries as $index => $delivery) {
                BusinessPlanDelivery::create([
                    'business_plan_id' => $businessPlan->id,
                    'product_name_livraison' => $delivery['product_name'],
                    'livraison_methode' => $delivery['livraison_methode'],
                    'sort_order' => $index + 1,
                ]);
            }
            $this->info("✓ Created {$businessPlan->deliveries()->count()} delivery methods");

            // Create Presentation Methods
            $presentations = [
                ['product_name' => 'Réseaux sociaux', 'presentation_methode' => 'Instagram, Facebook, TikTok - contenu quotidien'],
                ['product_name' => 'Site web', 'presentation_methode' => 'Site vitrine + blog + e-commerce permanent'],
                ['product_name' => 'Événements locaux', 'presentation_methode' => 'Participation aux marchés bio - mensuel'],
                ['product_name' => 'Partenariats entreprises', 'presentation_methode' => 'Offres spéciales employés - trimestriel'],
            ];

            foreach ($presentations as $index => $presentation) {
                BusinessPlanPresentation::create([
                    'business_plan_id' => $businessPlan->id,
                    'product_name_presentation' => $presentation['product_name'],
                    'presentation_methode' => $presentation['presentation_methode'],
                    'sort_order' => $index + 1,
                ]);
            }
            $this->info("✓ Created {$businessPlan->presentations()->count()} presentation methods");

            // Create Financial Data
            BusinessPlanFinancial::create([
                'business_plan_id' => $businessPlan->id,
                'ventes_premiere_annee' => '2000000',
                'ventes_deuxieme_annee' => '2800000',
                'ventes_troisieme_annee' => '3600000',
                'services_premiere_annee' => '500000',
                'services_deuxieme_annee' => '700000',
                'services_troisieme_annee' => '900000',
            ]);
            $this->info("✓ Created financial projections");

            DB::commit();

            $this->info("\n========================================");
            $this->info("✓✓✓ TEST BUSINESS PLAN CREATED SUCCESSFULLY! ✓✓✓");
            $this->info("========================================");
            $this->info("Business Plan ID: {$businessPlan->id}");
            $this->info("Candidat: {$candidat->nom} {$candidat->prenom} (ID: {$candidat->id})");
            $this->info("Project: {$businessPlan->project_name}");
            $this->info("\nYou can view it at:");
            $this->info("Admin: /admin/projects/{$businessPlan->id}");
            $this->info("Or: /admin/candidat/{$candidat->id}/submissions");
            $this->info("========================================\n");

            return 0;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Error creating test business plan: " . $e->getMessage());
            $this->error($e->getTraceAsString());
            return 1;
        }
    }
}
