<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Projet {{ $project->id }} - {{ $project->project_name }}</title>
    <style>
        @page {
            margin: 15mm;
        }
        body { 
            font-family: DejaVu Sans, sans-serif; 
            font-size: 11px;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .header-container {
            border-bottom: 3px solid #10b981;
            margin-bottom: 20px;
            padding-bottom: 15px;
        }
        .header-top {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        .logo-section {
            display: table-cell;
            width: 30%;
            vertical-align: middle;
        }
        .logo {
            max-width: 150px;
            height: auto;
        }
        .title-section {
            display: table-cell;
            width: 70%;
            vertical-align: middle;
            text-align: center;
        }
        .main-title {
            font-size: 22px;
            font-weight: bold;
            color: #10b981;
            margin: 0 0 5px 0;
        }
        .project-id {
            font-size: 12px;
            color: #666;
            margin: 5px 0;
        }
        .registration-box {
            background-color: #fef3c7;
            border: 2px solid #f59e0b;
            padding: 8px 15px;
            margin: 10px 0;
            text-align: center;
            font-weight: bold;
            color: #92400e;
        }
        .section { 
            margin-bottom: 20px; 
            page-break-inside: avoid; 
        }
        .section-title { 
            background-color: #10b981; 
            color: white;
            padding: 10px 15px; 
            font-weight: bold; 
            font-size: 13px;
            margin-bottom: 10px;
        }
        .section-content {
            padding: 10px;
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin: 0;
        }
        th, td { 
            border: 1px solid #d1d5db; 
            padding: 8px; 
            text-align: left; 
        }
        th { 
            background-color: #f3f4f6; 
            font-weight: bold;
            color: #1f2937;
            font-size: 11px;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
        }
        .info-table td {
            padding: 8px;
            border: 1px solid #e5e7eb;
        }
        .info-label { 
            font-weight: bold; 
            width: 35%; 
            background-color: #f3f4f6;
            color: #374151;
        }
        .info-value { 
            width: 65%;
            color: #4b5563;
        }
        .subsection-title {
            font-weight: bold;
            font-size: 12px;
            color: #059669;
            margin: 15px 0 8px 0;
            padding-bottom: 5px;
            border-bottom: 1px solid #d1d5db;
        }
        .total-row {
            background-color: #dbeafe !important;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #e5e7eb;
            color: #6b7280;
            font-size: 9px;
        }
        .two-column {
            width: 100%;
        }
        .two-column td {
            width: 50%;
            vertical-align: top;
        }
        .profile-photo {
            text-align: center;
            margin: 15px 0;
        }
        .profile-photo img {
            max-width: 150px;
            max-height: 150px;
            border: 3px solid #10b981;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header-container">
        <table class="header-top">
            <tr>
                <td class="logo-section">
                    <img src="{{ public_path('assets/admin/image/iuhm_logo.png') }}" alt="Logo" class="logo">
                </td>
                <td class="title-section">
                    <div class="main-title">{{ $project->project_name ?? $project->project_title ?? 'Dossier de Projet' }}</div>
                    <div class="project-id">Date: {{ $project->created_at->format('d/m/Y') }}</div>
                </td>
            </tr>
        </table>
        
        @if($project->registration)
        <div class="registration-box">
            NUMÉRO DE MATRICULATION: {{ $project->registration }}
        </div>
        @endif
    </div>

    <!-- I. INFORMATIONS PERSONNELLES -->
    <div class="section">
        <div class="section-title">I. INFORMATIONS PERSONNELLES DU PORTEUR DE PROJET</div>
        <div class="section-content">
            <div class="profile-photo">
                <img src="{{ base_path('uploads/' . $project->profile_image) }}" alt="Photo de profil">
            </div>
            
            <table class="info-table">
                <tr>
                    <td class="info-label">Nom du porteur du projet</td>
                    <td class="info-value">{{ $project->ceo_name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="info-label">Âge</td>
                    <td class="info-value">{{ $project->age ?? 'N/A' }} ans</td>
                </tr>
                <tr>
                    <td class="info-label">Genre</td>
                    <td class="info-value">{{ ucfirst($project->gender ?? 'N/A') }}</td>
                </tr>
                <tr>
                    <td class="info-label">Adresse</td>
                    <td class="info-value">{{ $project->address ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="info-label">Email</td>
                    <td class="info-value">{{ $project->email ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="info-label">Téléphone</td>
                    <td class="info-value">{{ $project->phone ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- II. DESCRIPTION DU PROJET -->
    <div class="section">
        <div class="section-title">III. DESCRIPTION DU PROJET</div>
        <div class="section-content">
            <div class="subsection-title">Résumé Exécutif</div>
            <p style="text-align: justify; line-height: 1.6; margin-bottom: 15px;">{{ $project->resume_executif }}</p>
            
            <div class="subsection-title">Description Détaillée</div>
            <p style="text-align: justify; line-height: 1.6;">{{ $project->description }}</p>
        </div>
    </div>

    <!-- III. INFORMATIONS DU PROJET -->
    <div class="section">
        <div class="section-title">II. INFORMATIONS GÉNÉRALES DU PROJET</div>
        <div class="section-content">
            <table class="info-table">
                <tr>
                    <td class="info-label">Nom du projet</td>
                    <td class="info-value">{{ $project->project_name ?? $project->project_title ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="info-label">Structure juridique</td>
                    <td class="info-value">{{ $project->legal_structure ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="info-label">Lieu du projet</td>
                    <td class="info-value">{{ $project->lieu_projet ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="info-label">public cible </td>
                    <td class="info-value">{{ $project->public_cible ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="info-label">les concurrents potentiels</td>
                    <td class="info-value">{{ $project->concurrent ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="info-label">produits locaux similaires sur le marché</td>
                    <td class="info-value">{{ $project->volume_produits_locaux ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="info-label">Le volume de la demande des années précédentes</td>
                    <td class="info-value">{{ $project->volume_demande   ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="info-label">La demande est-elle supérieure à l'offre, ou inversement</td>
                    <td class="info-value">{{ $project->demande_offre ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="info-label">les principales motivations d'achat de vos clients</td>
                    <td class="info-value">{{ $project->motivations_achat ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="info-label">les raisons pour lesquelles les clients choisiraient vos produits</td>
                    <td class="info-value">{{ $project->raison_choix_client ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="info-label">Comment percevrez-vous les bénéfices</td>
                    <td class="info-value">{{ $project->benefices_from_projet ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="info-label">valeur en retire</td>
                    <td class="info-value">{{ $project->valeur_projet ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- V. ÉTUDE DE MARCHÉ -->

    <div class="section">
        <div class="section-title">V. STRATÉGIE MARKETING</div>
        <div class="section-content">
            <table class="info-table">
                <tr>
                    <td class="info-label">méthodes marketing</td>
                    <td class="info-value">{{ $project->méthodes_marketing ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="info-label">méthodes sont-elles adaptées</td>
                    <td class="info-value">{{ $project->adaptation_methodes ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="info-label">différencier de vos concurrents</td>
                    <td class="info-value">{{ $project->differenciation_marketing ?? 'N/A' }}</td>
               
            </table>
        </div>
    </div>
    <!-- IV. PRODUITS ET SERVICES -->
    <div class="section">
        <div class="section-title">IV. PRODUITS ET SERVICES</div>
        <div class="section-content">
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;">N°</th>
                        <th style="width: 30%;">Nom du produit/service</th>
                        <th style="width: 45%;">Description</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($project->products as $index => $product)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td>{{ $product->product_name ?? 'N/A' }}</td>
                        <td>{{ $product->description ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>



    <!-- VI. RESSOURCES HUMAINES -->
    <div class="section">
        <div class="section-title">VI. PLAN DE RESSOURCES HUMAINES</div>
        <div class="section-content">
            <table>
                <thead>
                    <tr>
                        <th style="width: 40%;">Poste / Fonction</th>
                        <th style="width: 20%;">Année 1</th>
                        <th style="width: 20%;">Année 2</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($project->employees as $employee)
                    <tr>
                        <td>{{ $employee->item ?? $employee->job_title ?? 'N/A' }}</td>
                        <td style="text-align: center;">{{ $employee->total_employee_1 ?? $employee->year_1 ?? 0 }}</td>
                        <td style="text-align: center;">{{ $employee->total_employee_2 ?? $employee->year_2 ?? 0 }}</td>
                    </tr>
                    @endforeach
                    <tr class="total-row">
                        <td><strong>TOTAL EMPLOYÉS</strong></td>
                        <td style="text-align: center;"><strong>{{ $project->employees->sum('total_employee_1') }}</strong></td>
                        <td style="text-align: center;"><strong>{{ $project->employees->sum('total_employee_2') }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- XI. CALENDRIER DE RÉALISATION -->
    <div class="section">
        <div class="section-title">XI. CALENDRIER DE RÉALISATION</div>
        <div class="section-content">
            <table class="info-table">
                <tr>
                    <td class="info-label">Finalisation du plan d'affaires</td>
                    <td class="info-value">{{ $project->plan_affaires ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="info-label">Obtention du financement</td>
                    <td class="info-value">{{ $project->obtention_financement ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="info-label">L'ouverture du procès</td>
                    <td class="info-value">{{ ucfirst($project->ouverture_proces ?? 'N/A') }}</td>
                </tr>
                <tr>
                    <td class="info-label">Lancement du recrutement</td>
                    <td class="info-value">{{ $project->lancement_recrutement ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="info-label">Ouverture définitive</td>
                    <td class="info-value">{{ $project->ouverture_definitive ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="info-label">Durée</td>
                    <td class="info-value">{{ $project->duree ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Section technique</div>
        <div class="section-content">
            <table class="info-table">
                <tr>
                    <td class="info-label">Où se situe le projet</td>
                    <td class="info-value">{{ $project->lieu_projet ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="info-label"> Le lieu du projet est-il adapté au public ciblé</td>
                    <td class="info-value">{{ $project->adaptation_lieu ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Presentation de produit</div>
        <div class="section-content">
            <table class="info-table">
                    <thead>
                        <th>ID</th>
                        <th>Nom du produit</th>
                        <th>Mode de presentation</th>
                    </thead>
                    <tbody>
                    @foreach($project->presentations as $index => $product)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="info-value">{{ $product->product_name_presentation ?? 'N/A' }}</td>
                        <td class="info-label">{{ $product->presentation_methode ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    


        <!-- IX. MATIÈRES PREMIÈRES -->
    <div class="section">
        <div class="section-title">IX. MATIÈRES PREMIÈRES ET CONSOMMABLES</div>
        <div class="section-content">
            <table>
                <thead>
                    <tr>
                        <th >N°</th>
                        <th>Nom du produit</th>
                        <th>Mode de livraison</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($project->deliveries as $index => $material)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td>{{ $material->product_name_livraison ?? 'N/A' }}</td>
                        <td style="text-align: center;">{{ $material->livraison_methode ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="section">
        <div class="section-title">Capacités</div>
        <div class="section-content">
            <table class="info-table">
                <tr>
                    <td class="info-label">les compétences nécessaires à la réalisation du projet</td>
                    <td class="info-value">{{ $project->step_8_1 ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="info-label">matériel et les outils nécessaires à la réalisation du projet </td>
                    <td class="info-value">{{ $project->step_8_2 ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="info-label">l'expérience nécessaire pour mener à bien le projet </td>
                    <td class="info-value">{{ $project->step_8_3 ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="info-label">les fonds nécessaires au démarrage du projet</td>
                    <td class="info-value">{{ $project->step_8_4 ?? 'N/A' }}</td>
                </tr>

            </table>
        </div>
    </div>


    <div class="section">
        <div class="section-title">Prévisions financières</div>
        <div class="section-content">
            <table class="info-table">
                <tr>
                    <td class="info-label">Coûts de création d'entreprise</td>
                    <td class="info-value">{{ $project->couts_creation ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="info-label">Préparation de l'entreprise </td>
                    <td class="info-value">{{ $project->preparation_entreprise ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="info-label">Achat de machines</td>
                    <td class="info-value">{{ $project->achat_machines ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="info-label">Achat de matières premières (six mois)</td>
                    <td class="info-value">{{ $project->achat_matieres_premieres ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="info-label">Autres coûts (six mois)</td>
                    <td class="info-value">{{ $project->autres_couts ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="info-label">Total</td>
                    <td class="info-value">{{ $project->total ?? 'N/A' }}</td>
                </tr>

            </table>
        </div>
    </div>


        <!-- II. DESCRIPTION DU PROJET -->
    <div class="section">
        <div class="section-title">III. DESCRIPTION DU PROJET</div>
        <div class="section-content">
            <div class="subsection-title">Quand le projet commencera-t-il à générer des profits</div>
            <p style="text-align: justify; line-height: 1.6; margin-bottom: 15px;">{{ $project->generer_profits }}</p>
            
            <div class="subsection-title">Dans quelle mesure le projet est-il durable et stable</div>
            <p style="text-align: justify; line-height: 1.6;">{{ $project->projet_durable}}</p>
        </div>
    </div>

    <!-- VIII. ÉQUIPEMENTS ET INVESTISSEMENTS -->
        <div class="section-title">VIII. ÉQUIPEMENTS ET INVESTISSEMENTS</div>
        <div class="section-content">
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;">N°</th>
                        <th style="width: 40%;">Désignation</th>
                        <th style="width: 20%;">Référence</th>
                        <th style="width: 15%;">Quantité</th>
                        <th style="width: 20%;">Montant (DH)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($project->equipment as $index => $item)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td>{{ $item->equipement ?? 'N/A' }}</td>
                        <td>{{ $item->reference ?? 'N/A' }}</td>
                        <td style="text-align: center;">{{ $item->quantity ?? 1 }}</td>
                        <td style="text-align: right;">{{ number_format($item->prix_equipement ?? 0, 2) }}</td>
                    </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="4"><strong>TOTAL INVESTISSEMENT ÉQUIPEMENT</strong></td>
                        <td style="text-align: right;"><strong>{{ number_format($project->equipment->sum('prix_equipement'), 2) }} DH</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>


    <!-- XII. RISQUES ET OPPORTUNITÉS -->
        <div class="section-title">XII. Programme d'investissement</div>
            @if($project->financials->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                            Prévisions financières
                        </h3>
                    </div>
                    @php
                        $financial = $project->financials->first();
                    @endphp
                    @if($financial)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class=" uppercase">Description</th>
                                    <th class="">Première année</th>
                                    <th class="">Deuxième année</th>
                                    <th class="">Troisième année</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <!-- Revenus Section -->
                                <tr class="bg-green-50">
                                    <td colspan="4" class="border px-6 py-3 text-sm font-bold text-gray-900" style="background-color: #e0f2fe;">FRAIS PRÉVUS</td>
                                </tr>
                                <tr>
                                    <td class="">Achats Prévus</td>
                                    <td class=" text-center">{{ number_format($financial->achat_prevue_premiere_annee ?? 0, 2) }} DH</td>
                                    <td class=" text-center">{{ number_format($financial->achat_prevue_deuxieme_annee ?? 0, 2) }} DH</td>
                                    <td class=" text-center">{{ number_format($financial->achat_prevue_troisieme_annee ?? 0, 2) }} DH</td>
                                </tr>
                                <tr>
                                    <td class="">Frais de Fonctionnement</td>
                                    <td class=" text-center">{{ number_format($financial->frais_fonctionnement_premiere_annee ?? 0, 2) }} DH</td>
                                    <td class=" text-center">{{ number_format($financial->frais_fonctionnement_deuxieme_annee ?? 0, 2) }} DH</td>
                                    <td class=" text-center">{{ number_format($financial->frais_fonctionnement_troisieme_annee ?? 0, 2) }} DH</td>
                                </tr>   
                                <tr>
                                    <td class="">Charges de Personnel</td>
                                    <td class=" text-center">{{ number_format($financial->charges_personnel_premiere_annee ?? 0, 2) }} DH</td>
                                    <td class=" text-center">{{ number_format($financial->charges_personnel_deuxieme_annee ?? 0, 2) }} DH</td>
                                    <td class=" text-center">{{ number_format($financial->charges_personnel_troisieme_annee ?? 0, 2) }} DH</td>
                                </tr>
                                <tr>
                                    <td class="">Dettes</td>
                                    <td class=" text-center">{{ number_format($financial->dettes_premiere_annee ?? 0, 2) }} DH</td>
                                    <td class=" text-center">{{ number_format($financial->dettes_deuxieme_annee ?? 0, 2) }} DH</td>
                                    <td class=" text-center">{{ number_format($financial->dettes_troisieme_annee ?? 0, 2) }} DH</td>
                                </tr> 
                                <tr>
                                    <td class="">Établissement bancaire</td>
                                    <td class=" text-center">{{ number_format($financial->etablissement_bancaire_premiere_annee ?? 0, 2) }} DH</td>
                                    <td class=" text-center">{{ number_format($financial->etablissement_bancaire_deuxieme_annee ?? 0, 2) }} DH</td>
                                    <td class=" text-center">{{ number_format($financial->etablissement_bancaire_troisieme_annee ?? 0, 2) }} DH</td>
                                </tr>
                                <tr>
                                    <td class="">Fournisseurs</td>
                                    <td class=" text-center">{{ number_format($financial->fournisseurs_premiere_annee ?? 0, 2) }} DH</td>
                                    <td class=" text-center">{{ number_format($financial->fournisseurs_deuxieme_annee ?? 0, 2) }} DH</td>
                                    <td class=" text-center">{{ number_format($financial->fournisseurs_troisieme_annee ?? 0, 2) }} DH</td>
                                </tr>   
                                <tr>
                                    <td class="">Autres dettes</td>
                                    <td class=" text-center">{{ number_format($financial->autres_dettes_premiere_annee ?? 0, 2) }} DH</td>
                                    <td class=" text-center">{{ number_format($financial->autres_dettes_deuxieme_annee ?? 0, 2) }} DH</td>
                                    <td class=" text-center">{{ number_format($financial->autres_dettes_troisieme_annee ?? 0, 2) }} DH</td>
                                </tr>
                                <tr>
                                    <td class="">Autres Charges</td>
                                    <td class=" text-center">{{ number_format($financial->autres_charges_premiere_annee ?? 0, 2) }} DH</td>
                                    <td class=" text-center">{{ number_format($financial->autres_charges_deuxieme_annee ?? 0, 2) }} DH</td>
                                    <td class=" text-center">{{ number_format($financial->autres_charges_troisieme_annee ?? 0, 2) }} DH</td>
                                </tr>                                                                                                                       



                                <tr style="background-color: #e0f2fe;">
                                    <td class="">Total Revenus</td>
                                    <td class=" text-center">{{ number_format($financial->total_prevus_premiere_annee ?? 0, 2) }} DH</td>
                                    <td class=" text-center">{{ number_format($financial->total_prevus_deuxieme_annee ?? 0, 2) }} DH</td>
                                    <td class=" text-center">{{ number_format($financial->total_prevus_troisieme_annee ?? 0, 2) }} DH</td>
                                </tr>

                                <!-- Dépenses Section -->
                                <tr style="background-color: #fee2e2;">
                                    <td colspan="4" class="border px-6 py-3 text-sm font-bold text-gray-900">PROGRAMME D'INVESTISSEMENT</td>
                                </tr>
                                <tr>
                                    <td class="">Ventes</td>
                                    <td class=" text-center">{{ number_format($financial->ventes_premiere_annee ?? 0, 2) }} DH</td>
                                    <td class=" text-center">{{ number_format($financial->ventes_deuxieme_annee ?? 0, 2) }} DH</td>
                                    <td class=" text-center">{{ number_format($financial->ventes_troisieme_annee ?? 0, 2) }} DH</td>
                                </tr>
                                <tr>
                                    <td class="">Services</td>
                                    <td class=" text-center">{{ number_format($financial->services_premiere_annee ?? 0, 2) }} DH</td>
                                    <td class=" text-center">{{ number_format($financial->services_deuxieme_annee ?? 0, 2) }} DH</td>
                                    <td class=" text-center">{{ number_format($financial->services_troisieme_annee ?? 0, 2) }} DH</td>
                                </tr>
                                <tr>
                                    <td class="">Aide Financière</td>
                                    <td class=" text-center">{{ number_format($financial->aide_financiere_premiere_annee ?? 0, 2) }} DH</td>
                                    <td class=" text-center">{{ number_format($financial->aide_financiere_deuxieme_annee ?? 0, 2) }} DH</td>
                                    <td class=" text-center">{{ number_format($financial->aide_financiere_troisieme_annee ?? 0, 2) }} DH</td>
                                </tr>
                                <tr>
                                    <td class="">Revenus Financiers</td>
                                    <td class=" text-center">{{ number_format($financial->revenus_financiers_premiere_annee ?? 0, 2) }} DH</td>
                                    <td class=" text-center">{{ number_format($financial->revenus_financiers_deuxieme_annee ?? 0, 2) }} DH</td>
                                    <td class=" text-center">{{ number_format($financial->revenus_financiers_troisieme_annee ?? 0, 2) }} DH</td>
                                </tr>
                                <tr>
                                    <td class="">Autres Revenus</td>
                                    <td class=" text-center">{{ number_format($financial->autres_revenus_premiere_annee ?? 0, 2) }} DH</td>
                                    <td class=" text-center">{{ number_format($financial->autres_revenus_deuxieme_annee ?? 0, 2) }} DH</td>
                                    <td class=" text-center">{{ number_format($financial->autres_revenus_troisieme_annee ?? 0, 2) }} DH</td>
                                </tr>
                                <tr style="background-color: #fee2e2;">
                                    <td class="">Total Dépenses</td>
                                    <td class=" text-center">{{ number_format($financial->total_investissements_premiere_annee ?? 0, 2) }} DH</td>
                                    <td class=" text-center">{{ number_format($financial->total_investissements_deuxieme_annee ?? 0, 2) }} DH</td>
                                    <td class=" text-center">{{ number_format($financial->total_investissements_troisieme_annee ?? 0, 2) }} DH</td>
                                </tr>

                                <!-- RÉSULTAT Section -->
                                <tr style="background-color: #e0f2fe;">
                                    <td colspan="4" class="border px-6 py-3 text-sm font-bold text-gray-900">RÉSULTAT</td>
                                </tr>      
                                <tr >
                                    <td class="">Revenus</td>
                                    <td class=" text-center">{{ number_format($financial->revenus_premiere_annee ?? 0, 2) }} DH</td>
                                    <td class=" text-center">{{ number_format($financial->revenus_deuxieme_annee ?? 0, 2) }} DH</td>
                                    <td class=" text-center">{{ number_format($financial->revenus_troisieme_annee ?? 0, 2) }} DH</td>
                                </tr>                                 
                                <tr >
                                    <td class="">Dépenses</td>
                                    <td class=" text-center">{{ number_format($financial->depenses_premiere_annee ?? 0, 2) }} DH</td>
                                    <td class=" text-center">{{ number_format($financial->depenses_deuxieme_annee ?? 0, 2) }} DH</td>
                                    <td class=" text-center">{{ number_format($financial->depenses_troisieme_annee ?? 0, 2) }} DH</td>
                                </tr>                   

                                <!-- Résultat Section -->
                                <tr style="background-color: #e0f2fe;">
                                    <td class="">Résultat (Revenus - Dépenses) RÉSULTAT (REVENUS - DÉPENSES)</td>
                                    <td class="border px-6 py-4 text-sm font-bold text-blue-700 text-center">{{ number_format($financial->resultat_premiere_annee ?? 0, 2) }} DH</td>
                                    <td class="border px-6 py-4 text-sm font-bold text-blue-700 text-center">{{ number_format($financial->resultat_deuxieme_annee ?? 0, 2) }} DH</td>
                                    <td class="border px-6 py-4 text-sm font-bold text-blue-700 text-center">{{ number_format($financial->resultat_troisieme_annee ?? 0, 2) }} DH</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            @endif
    
    <div class="section" style="margin-top: 20px;">
        <div class="section-title">XIII. machines et équipements</div>
        <div class="section-content">
            <table>
                <thead>
                    <tr>
                        <th></th>
                        <th style="width: 5%;">Machines et équipements</th>
                        <th style="width: 30%;">Référence</th>
                        <th style="width: 45%;">Prix</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($project->equipment as $index => $product)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td>{{ $product->equipement ?? 'N/A' }}</td>
                        <td>{{ $product->reference ?? 'N/A' }}</td>
                        <td>{{ $product->prix_equipement ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

        <div class="section" style="margin-top: 20px;">
        <div class="section-title">XIII. machines et équipements</div>
        <div class="section-content">
            <table>
                <thead>
                    <tr>
                        <th></th>
                        <th style="width: 5%;">Matières premières</th>
                        <th style="width: 30%;">Comment se procurer</th>
                        <th style="width: 45%;">Fournisseur</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($project->rawMaterials as $index => $product)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td>{{ $product->matiere_premiere ?? 'N/A' }}</td>
                        <td>{{ $product->comment_procurer ?? 'N/A' }}</td>
                        <td>{{ $product->fournisseur_matiere ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p><strong>Document confidentiel - Usage strictement professionnel</strong></p>
        <p>Document généré le {{ now()->format('d/m/Y à H:i') }}</p>
        <p>© {{ now()->year }}- Tous droits réservés</p>
    </div>

</body>
</html>