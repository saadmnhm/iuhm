-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: shareddb-n.hosting.stackcp.net
-- Generation Time: Feb 07, 2026 at 07:26 PM
-- Server version: 10.6.18-MariaDB-log
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dev_iuhm_zettat-3130373f8f`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_activity_logs`
--

CREATE TABLE `admin_activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `action` varchar(255) NOT NULL,
  `subject_type` varchar(255) DEFAULT NULL,
  `subject_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` text DEFAULT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`properties`)),
  `ip_address` varchar(255) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_activity_logs`
--

INSERT INTO `admin_activity_logs` (`id`, `user_id`, `action`, `subject_type`, `subject_id`, `description`, `properties`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(1, 1, 'project_status_changed', 'App\\Models\\Project', 11, 'Changed project status from submitted to approved', '{\"old_status\":\"submitted\",\"new_status\":\"approved\",\"review_notes\":null}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-01 01:59:56', '2026-02-01 01:59:56'),
(2, 1, 'project_status_changed', 'App\\Models\\Project', 11, 'Changed project status from approved to draft', '{\"old_status\":\"approved\",\"new_status\":\"draft\",\"review_notes\":null}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-01 02:00:24', '2026-02-01 02:00:24'),
(3, 1, 'project_status_changed', 'App\\Models\\Project', 11, 'Changed project status from draft to submitted', '{\"old_status\":\"draft\",\"new_status\":\"submitted\",\"review_notes\":null}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-01 02:00:33', '2026-02-01 02:00:33'),
(4, 1, 'project_status_changed', 'App\\Models\\Project', 11, 'Changed project status from submitted to rejected', '{\"old_status\":\"submitted\",\"new_status\":\"rejected\",\"review_notes\":null}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-01 02:00:38', '2026-02-01 02:00:38'),
(5, 1, 'registration_added', 'App\\Models\\Project', 11, 'Added registration number: MAT-2223454', '{\"old_registration\":null,\"new_registration\":\"MAT-2223454\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-01 02:04:50', '2026-02-01 02:04:50'),
(6, 1, 'candidat_status_toggled', 'App\\Models\\Candidat', 2, 'Candidat saad saad désactivé', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-06 22:48:16', '2026-02-06 22:48:16'),
(7, 1, 'candidat_status_toggled', 'App\\Models\\Candidat', 2, 'Candidat saad saad activé', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-06 22:48:18', '2026-02-06 22:48:18'),
(8, 1, 'project_status_changed', 'App\\Models\\BusinessPlan', 31, 'Changed project status from submitted to in_review', '{\"old_status\":\"submitted\",\"new_status\":\"in_review\",\"review_notes\":null}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-06 23:21:57', '2026-02-06 23:21:57'),
(9, 1, 'support_ticket_responded', 'App\\Models\\SupportTicket', 1, 'Responded to support ticket #1', '{\"status\":\"in_progress\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-06 23:43:14', '2026-02-06 23:43:14'),
(10, 1, 'support_ticket_responded', 'App\\Models\\SupportTicket', 1, 'Responded to support ticket #1', '{\"status\":\"closed\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-06 23:43:30', '2026-02-06 23:43:30'),
(11, 1, 'candidat_status_toggled', 'App\\Models\\Candidat', 4, 'Candidat saad saad désactivé', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-07 00:06:54', '2026-02-07 00:06:54'),
(12, 1, 'project_status_changed', 'App\\Models\\BusinessPlan', 31, 'Changed project status from in_review to submitted', '{\"old_status\":\"in_review\",\"new_status\":\"submitted\",\"review_notes\":null}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-07 00:08:43', '2026-02-07 00:08:43');

-- --------------------------------------------------------

--
-- Table structure for table `bilan_competences`
--

CREATE TABLE `bilan_competences` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `candidat_id` bigint(20) UNSIGNED NOT NULL,
  `form_type` varchar(255) DEFAULT NULL,
  `qualites_defauts` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`qualites_defauts`)),
  `qualites_contribution` text DEFAULT NULL,
  `defauts_freins` text DEFAULT NULL,
  `loisirs` text DEFAULT NULL,
  `niveau_etude` text DEFAULT NULL,
  `diplomes_obtenus` text DEFAULT NULL,
  `annee_obtention` text DEFAULT NULL,
  `etablissement_obtention` text DEFAULT NULL,
  `competences_formation` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`competences_formation`)),
  `besoin_formations` varchar(255) DEFAULT NULL,
  `type_formations` text DEFAULT NULL,
  `environnement_professionnel` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`environnement_professionnel`)),
  `secteurs_activite` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`secteurs_activite`)),
  `fonctions_envisagees` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`fonctions_envisagees`)),
  `representation_travail` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`representation_travail`)),
  `contraintes_acceptees` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`contraintes_acceptees`)),
  `exigences` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`exigences`)),
  `reflexions_personnelles` text DEFAULT NULL,
  `stage_societe` text DEFAULT NULL,
  `stage_lieu` text DEFAULT NULL,
  `stage_secteur` text DEFAULT NULL,
  `stage_duree` text DEFAULT NULL,
  `stage_responsabilites` text DEFAULT NULL,
  `stage_competences` text DEFAULT NULL,
  `stage_obstacles` text DEFAULT NULL,
  `stage_reflexions` text DEFAULT NULL,
  `stage_plu` text DEFAULT NULL,
  `stage_deplu` text DEFAULT NULL,
  `stage_appris` text DEFAULT NULL,
  `exp_societe` text DEFAULT NULL,
  `exp_lieu` text DEFAULT NULL,
  `exp_secteur` text DEFAULT NULL,
  `exp_duree` text DEFAULT NULL,
  `exp_responsabilites` text DEFAULT NULL,
  `exp_competences` text DEFAULT NULL,
  `exp_obstacles` text DEFAULT NULL,
  `exp_integration` text DEFAULT NULL,
  `exp_depart` text DEFAULT NULL,
  `exp_reflexions` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'draft',
  `current_step` int(11) NOT NULL DEFAULT 1,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `review_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bilan_competences`
--

INSERT INTO `bilan_competences` (`id`, `candidat_id`, `form_type`, `qualites_defauts`, `qualites_contribution`, `defauts_freins`, `loisirs`, `niveau_etude`, `diplomes_obtenus`, `annee_obtention`, `etablissement_obtention`, `competences_formation`, `besoin_formations`, `type_formations`, `environnement_professionnel`, `secteurs_activite`, `fonctions_envisagees`, `representation_travail`, `contraintes_acceptees`, `exigences`, `reflexions_personnelles`, `stage_societe`, `stage_lieu`, `stage_secteur`, `stage_duree`, `stage_responsabilites`, `stage_competences`, `stage_obstacles`, `stage_reflexions`, `stage_plu`, `stage_deplu`, `stage_appris`, `exp_societe`, `exp_lieu`, `exp_secteur`, `exp_duree`, `exp_responsabilites`, `exp_competences`, `exp_obstacles`, `exp_integration`, `exp_depart`, `exp_reflexions`, `status`, `current_step`, `submitted_at`, `reviewed_at`, `review_notes`, `created_at`, `updated_at`, `deleted_at`) VALUES
(4, 3, 'bilan_competence', '[{\"qualite\":\"Pers\\u00e9v\\u00e9rant et d\\u00e9termin\\u00e9\",\"defaut\":\"Parfois trop perfectionniste\"},{\"qualite\":\"Bon communicateur\",\"defaut\":\"Manque de patience\"},{\"qualite\":\"Cr\\u00e9atif et innovant\",\"defaut\":\"Difficult\\u00e9 \\u00e0 d\\u00e9l\\u00e9guer\"}]', 'Ma persévérance me permet de surmonter les obstacles et ma créativité m\'aide à trouver des solutions innovantes', 'Mon perfectionnisme peut ralentir l\'avancement des projets et mon impatience peut créer des tensions', 'Lecture, sport (football), voyages, photographie, bénévolat associatif', 'Bac+3 Licence en Gestion des Entreprises', 'Baccalauréat Sciences Économiques, Licence en Gestion', '2023', 'Université Hassan II - Casablanca', '[{\"acquise\":\"Comptabilit\\u00e9 g\\u00e9n\\u00e9rale\",\"lacune\":\"Comptabilit\\u00e9 analytique avanc\\u00e9e\",\"a_developper\":\"Fiscalit\\u00e9 des entreprises\"},{\"acquise\":\"Marketing de base\",\"lacune\":\"Marketing digital\",\"a_developper\":\"SEO et publicit\\u00e9 en ligne\"}]', 'oui', 'Formation en marketing digital et en gestion de projet (PMP)', '{\"travail_bureau\":\"oui\",\"travail_exterieur\":\"non\",\"travail_equipe\":\"oui\",\"travail_independant\":\"oui\",\"horaires_fixes\":\"non\",\"horaires_flexibles\":\"oui\",\"deplacement_frequent\":\"oui\"}', '[\"Commerce\",\"Services\",\"Artisanat\",\"Technologie\",\"G\\u00e9nie civil et travaux public\",\"Textile et habillement\",\"Industrie alimentaire\"]', '[\"Direction g\\u00e9n\\u00e9rale\",\"Marketing et vente\",\"Gestion de projet\",\"Conseil\"]', '[\"\\u00c9panouissement personnel\",\"Ind\\u00e9pendance financi\\u00e8re\",\"Contribution sociale\",\"Cr\\u00e9ativit\\u00e9\",\"Le moyen de pr\\u00e9parer l\'avenir\",\"Un moyen de me valoriser\"]', '{\"deplacement\":\"oui\",\"horaires_variables\":\"oui\",\"travail_weekend\":\"non\",\"travail_nuit\":\"non\",\"port_charges\":\"non\",\"travail_exterieur_meteo\":\"non\",\"travail_repetitif\":\"non\",\"pression_resultats\":\"oui\"}', '[\"Autonomie\",\"Cr\\u00e9ativit\\u00e9\",\"Responsabilit\\u00e9\",\"Bon salaire\",\"\\u00c9volution de carri\\u00e8re\"]', 'Je souhaite créer ma propre entreprise dans le secteur artisanal pour valoriser le patrimoine local', 'Artisanat Moderne SARL', 'Casablanca, Zone Industrielle Ain Sebaa', 'Artisanat et production', '3 mois (Juin - Août 2022)', 'Suivi de production, gestion des stocks, relation fournisseurs', 'Gestion de stock, négociation fournisseurs, contrôle qualité', 'Ruptures de stock fréquentes, communication difficile avec certains fournisseurs', 'Ce stage m\'a confirmé mon intérêt pour l\'entrepreneuriat dans l\'artisanat', 'L\'ambiance de travail collaborative et la créativité des artisans', 'Le manque d\'organisation administrative et de digitalisation', 'J\'ai appris que je suis capable de gérer une équipe et que j\'aime résoudre des problèmes concrets', 'Maroc Artisan Express', 'Rabat, Médina', 'E-commerce artisanal', '6 mois (CDD)', 'Gestion de la boutique en ligne, service client, coordination des livraisons', 'E-commerce, service client, logistique, réseaux sociaux', 'Retards de livraison, gestion des retours clients', 'Formation initiale d\'une semaine, tutorat par le responsable', 'Fin de CDD, souhait de créer mon propre projet', 'Cette expérience m\'a donné une vision complète de la chaîne de valeur artisanale', 'submitted', 6, '2026-02-06 23:34:04', NULL, NULL, '2026-02-06 23:28:58', '2026-02-06 23:34:04', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `bmcs`
--

CREATE TABLE `bmcs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `candidat_id` bigint(20) UNSIGNED NOT NULL,
  `form_type` varchar(255) DEFAULT NULL,
  `partenaires_cles` text DEFAULT NULL,
  `activites_cles` text DEFAULT NULL,
  `proposition_valeur` text DEFAULT NULL,
  `relations_clients` text DEFAULT NULL,
  `segments_clientele` text DEFAULT NULL,
  `ressources_cles` text DEFAULT NULL,
  `canaux` text DEFAULT NULL,
  `structure_couts` text DEFAULT NULL,
  `flux_revenus` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'draft',
  `current_step` int(11) NOT NULL DEFAULT 1,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `review_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bmcs`
--

INSERT INTO `bmcs` (`id`, `candidat_id`, `form_type`, `partenaires_cles`, `activites_cles`, `proposition_valeur`, `relations_clients`, `segments_clientele`, `ressources_cles`, `canaux`, `structure_couts`, `flux_revenus`, `status`, `current_step`, `submitted_at`, `reviewed_at`, `review_notes`, `created_at`, `updated_at`, `deleted_at`) VALUES
(3, 3, 'bmc', 'Fournisseurs locaux de matières premières, coopératives artisanales, agences de transport, plateformes e-commerce', 'Production artisanale, contrôle qualité, marketing digital, gestion des commandes, service client', 'Produits artisanaux authentiques de haute qualité, personnalisables, à prix justes, soutenant l\'économie locale', 'Service personnalisé, suivi après-vente, programme de fidélité, newsletter mensuelle, SAV réactif', 'Touristes (nationaux et internationaux), décorateurs d\'intérieur, boutiques de cadeaux, collectionneurs d\'art', 'Artisans qualifiés, atelier de production, matières premières de qualité, plateforme en ligne, réseau de distribution', 'Boutique physique, site e-commerce, réseaux sociaux (Instagram, Facebook), marchés artisanaux, partenariats hôteliers', 'Matières premières (30%), main d\'œuvre (25%), loyer atelier (15%), marketing (10%), logistique (10%), autres (10%)', 'Ventes directes (60%), commandes personnalisées (25%), ventes en gros aux boutiques (15%)', 'submitted', 1, '2026-02-06 23:44:12', NULL, NULL, '2026-02-06 23:44:03', '2026-02-06 23:44:12', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `business_plans`
--

CREATE TABLE `business_plans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `candidat_id` bigint(20) UNSIGNED DEFAULT NULL,
  `form_type` varchar(255) NOT NULL DEFAULT 'business_plan',
  `project_name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `registration` varchar(255) DEFAULT NULL,
  `legal_structure` varchar(255) DEFAULT NULL,
  `resume_executif` text DEFAULT NULL,
  `public_cible` text DEFAULT NULL,
  `concurrent` text DEFAULT NULL,
  `volume_produits_locaux` text DEFAULT NULL,
  `volume_demande` text DEFAULT NULL,
  `demande_offre` text DEFAULT NULL,
  `motivations_achat` text DEFAULT NULL,
  `raison_choix_client` text DEFAULT NULL,
  `méthodes_marketing` text DEFAULT NULL,
  `adaptation_methodes` text DEFAULT NULL,
  `differenciation_marketing` text DEFAULT NULL,
  `plan_affaires` text DEFAULT NULL,
  `obtention_financement` text DEFAULT NULL,
  `ouverture_proces` text DEFAULT NULL,
  `lancement_recrutement` text DEFAULT NULL,
  `ouverture_definitive` text DEFAULT NULL,
  `duree` varchar(255) DEFAULT NULL,
  `lieu_projet` text DEFAULT NULL,
  `adaptation_lieu` text DEFAULT NULL,
  `benefices_from_projet` text DEFAULT NULL,
  `valeur_projet` text DEFAULT NULL,
  `step_8_1` text DEFAULT NULL,
  `step_8_2` text DEFAULT NULL,
  `step_8_3` text DEFAULT NULL,
  `step_8_4` text DEFAULT NULL,
  `couts_creation` decimal(12,2) DEFAULT NULL,
  `preparation_entreprise` decimal(12,2) DEFAULT NULL,
  `achat_machines` decimal(12,2) DEFAULT NULL,
  `achat_matieres_premieres` decimal(12,2) DEFAULT NULL,
  `autres_couts` decimal(12,2) DEFAULT NULL,
  `total` decimal(12,2) DEFAULT NULL,
  `generer_profits` text DEFAULT NULL,
  `projet_durable` text DEFAULT NULL,
  `status` enum('draft','submitted','in_review','approved','rejected') NOT NULL DEFAULT 'draft',
  `current_step` int(11) NOT NULL DEFAULT 0,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `reviewed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `review_notes` text DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `business_plans`
--

INSERT INTO `business_plans` (`id`, `candidat_id`, `form_type`, `project_name`, `description`, `registration`, `legal_structure`, `resume_executif`, `public_cible`, `concurrent`, `volume_produits_locaux`, `volume_demande`, `demande_offre`, `motivations_achat`, `raison_choix_client`, `méthodes_marketing`, `adaptation_methodes`, `differenciation_marketing`, `plan_affaires`, `obtention_financement`, `ouverture_proces`, `lancement_recrutement`, `ouverture_definitive`, `duree`, `lieu_projet`, `adaptation_lieu`, `benefices_from_projet`, `valeur_projet`, `step_8_1`, `step_8_2`, `step_8_3`, `step_8_4`, `couts_creation`, `preparation_entreprise`, `achat_machines`, `achat_matieres_premieres`, `autres_couts`, `total`, `generer_profits`, `projet_durable`, `status`, `current_step`, `submitted_at`, `created_at`, `updated_at`, `deleted_at`, `reviewed_by`, `review_notes`, `reviewed_at`) VALUES
(31, 3, 'business_plan', 'Mon Projet Test', 'Description du projet de test', NULL, 'SARL', 'Résumé exécutif du projet', 'Jeunes entrepreneurs', 'Concurrent A, Concurrent B', 'Volume moyen', 'Forte demande', 'Équilibrée', 'Qualité et prix', 'Meilleur rapport qualité-prix', 'Réseaux sociaux, publicité locale', 'Adaptation selon le budget', 'Prix compétitifs', 'Janvier 2026', 'Février 2026', 'Mars 2026', 'Avril 2026', 'Mai 2026', '6 mois', 'Casablanca, Hay Mohamadi', 'Oui, très adapté', 'Revenus mensuels stables', 'Bénéfices + expérience + réseau', 'Oui, compétences acquises', 'Oui, matériel disponible', 'Oui, 5 ans d\'expérience', 'Oui, fonds disponibles', 10000.00, 5000.00, 20000.00, 8000.00, 3000.00, 46000.00, 'Le projet générera des profits à partir de la deuxième année', 'Le projet est durable grâce à la croissance constante', 'submitted', 8, '2026-02-06 23:19:19', '2026-02-06 23:18:52', '2026-02-07 00:08:43', NULL, 1, NULL, '2026-02-07 00:08:43');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('association-initiative-urbaine-cache-356a192b7913b04c54574d18c28d46e6395428ab', 'i:1;', 1770422538),
('association-initiative-urbaine-cache-356a192b7913b04c54574d18c28d46e6395428ab:timer', 'i:1770422538;', 1770422538),
('association-initiative-urbaine-cache-5c785c036466adea360111aa28563bfd556b5fba', 'i:1;', 1770426224),
('association-initiative-urbaine-cache-5c785c036466adea360111aa28563bfd556b5fba:timer', 'i:1770426224;', 1770426224),
('laravel-cache-5c785c036466adea360111aa28563bfd556b5fba', 'i:1;', 1770471863),
('laravel-cache-5c785c036466adea360111aa28563bfd556b5fba:timer', 'i:1770471863;', 1770471863);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `candidat`
--

CREATE TABLE `candidat` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `login` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `age` int(11) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `gender` enum('homme','femme') DEFAULT NULL,
  `address` enum('Hay Mohamadi','Ain Sbaa','Roches Noires') DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `date_naissance` date DEFAULT NULL,
  `cv_path` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `last_ip_address` varchar(45) DEFAULT NULL,
  `last_user_agent` text DEFAULT NULL,
  `last_browser` varchar(255) DEFAULT NULL,
  `last_platform` varchar(255) DEFAULT NULL,
  `last_device` varchar(255) DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `login_count` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `candidat`
--

INSERT INTO `candidat` (`id`, `login`, `password`, `nom`, `prenom`, `age`, `profile_image`, `gender`, `address`, `email`, `phone`, `date_naissance`, `cv_path`, `is_active`, `created_at`, `updated_at`, `deleted_at`, `last_ip_address`, `last_user_agent`, `last_browser`, `last_platform`, `last_device`, `last_login_at`, `login_count`) VALUES
(3, 'saadmnhm@gmail.com', '$2y$12$pv1bcoVYUkVKeNKjCA0xguFn8eNtCcD7/Pcr/zslgqyt4dVv3F7cy', 'saad', 'saad', 24, 'profile-images/1770422496_698680e0a8a5a.jpg', 'homme', 'Ain Sbaa', 'saadmnhm@gmail.com', '0638640423', NULL, NULL, 1, '2026-02-06 23:00:17', '2026-02-07 13:54:20', NULL, '105.188.107.12', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Chrome 144.0.0.0', 'Windows 10.0', 'Desktop', '2026-02-07 13:54:20', 1),
(5, 'test@example.com', '$2y$12$TeC3vTqX9deWzziAMbZxkunTLRoqOFGD1c8tmJ5NQPXQtolQ.J0Xu', 'saad', 'saad', 33, 'profile-images/1770471814_69874186ab4ae.jpg', 'homme', 'Hay Mohamadi', 'test@example.com', '0612345678', NULL, NULL, 1, '2026-02-07 12:42:32', '2026-02-07 12:43:34', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Chrome 144.0.0.0', 'Windows 10.0', 'Desktop', '2026-02-07 12:42:32', 1);

-- --------------------------------------------------------

--
-- Table structure for table `dynamic_forms`
--

CREATE TABLE `dynamic_forms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `title_ar` varchar(255) DEFAULT NULL,
  `introduction` text DEFAULT NULL,
  `introduction_ar` text DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL DEFAULT 'ri-file-list-3-line',
  `color` varchar(255) NOT NULL DEFAULT '#2f5496',
  `bg_color` varchar(255) NOT NULL DEFAULT '#ffffff',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `has_steps` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dynamic_form_answers`
--

CREATE TABLE `dynamic_form_answers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dynamic_form_submission_id` bigint(20) UNSIGNED NOT NULL,
  `dynamic_form_field_id` bigint(20) UNSIGNED DEFAULT NULL,
  `field_key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dynamic_form_fields`
--

CREATE TABLE `dynamic_form_fields` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dynamic_form_step_id` bigint(20) UNSIGNED NOT NULL,
  `label` varchar(255) NOT NULL,
  `label_ar` varchar(255) DEFAULT NULL,
  `field_key` varchar(255) NOT NULL,
  `type` enum('text','textarea','number','email','date','select','radio','checkbox','file','table','heading','paragraph') NOT NULL DEFAULT 'text',
  `placeholder` text DEFAULT NULL,
  `help_text` text DEFAULT NULL,
  `options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`options`)),
  `is_required` tinyint(1) NOT NULL DEFAULT 0,
  `is_full_width` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dynamic_form_steps`
--

CREATE TABLE `dynamic_form_steps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dynamic_form_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `title_ar` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `step_number` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dynamic_form_submissions`
--

CREATE TABLE `dynamic_form_submissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dynamic_form_id` bigint(20) UNSIGNED NOT NULL,
  `candidat_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('draft','submitted','in_review','approved','rejected') NOT NULL DEFAULT 'draft',
  `current_step` int(11) NOT NULL DEFAULT 1,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `review_notes` text DEFAULT NULL,
  `reviewed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dynamic_form_tables`
--

CREATE TABLE `dynamic_form_tables` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dynamic_form_step_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `title_ar` varchar(255) DEFAULT NULL,
  `table_key` varchar(255) NOT NULL,
  `has_dynamic_rows` tinyint(1) NOT NULL DEFAULT 0,
  `has_total_row` tinyint(1) NOT NULL DEFAULT 0,
  `min_rows` int(11) NOT NULL DEFAULT 1,
  `max_rows` int(11) NOT NULL DEFAULT 20,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dynamic_form_table_answers`
--

CREATE TABLE `dynamic_form_table_answers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dynamic_form_submission_id` bigint(20) UNSIGNED NOT NULL,
  `dynamic_form_table_id` bigint(20) UNSIGNED DEFAULT NULL,
  `table_key` varchar(255) NOT NULL,
  `row_index` int(11) NOT NULL,
  `column_key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dynamic_form_table_columns`
--

CREATE TABLE `dynamic_form_table_columns` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dynamic_form_table_id` bigint(20) UNSIGNED NOT NULL,
  `header` varchar(255) NOT NULL,
  `header_ar` varchar(255) DEFAULT NULL,
  `column_key` varchar(255) NOT NULL,
  `input_type` enum('text','number','checkbox','select','readonly','label') NOT NULL DEFAULT 'text',
  `options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`options`)),
  `is_totaled` tinyint(1) NOT NULL DEFAULT 0,
  `width` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dynamic_form_table_rows`
--

CREATE TABLE `dynamic_form_table_rows` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dynamic_form_table_id` bigint(20) UNSIGNED NOT NULL,
  `label` varchar(255) NOT NULL,
  `label_ar` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `etude_marches`
--

CREATE TABLE `etude_marches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `candidat_id` bigint(20) UNSIGNED NOT NULL,
  `form_type` varchar(255) DEFAULT NULL,
  `produit_service` text DEFAULT NULL,
  `description_offre` text DEFAULT NULL,
  `benefices_clients` text DEFAULT NULL,
  `prix_marche` text DEFAULT NULL,
  `controle_prix` text DEFAULT NULL,
  `type_clients` text DEFAULT NULL,
  `caracteristiques_clientele` text DEFAULT NULL,
  `frequence_consommation` text DEFAULT NULL,
  `localisation_clients` text DEFAULT NULL,
  `exigences_principales` text DEFAULT NULL,
  `nombre_concurrents_directs` text DEFAULT NULL,
  `concurrents_indirects` text DEFAULT NULL,
  `taille_concurrents` text DEFAULT NULL,
  `informations_concurrents` text DEFAULT NULL,
  `communication_concurrents` text DEFAULT NULL,
  `nombre_fournisseurs` text DEFAULT NULL,
  `origine_fournisseurs` text DEFAULT NULL,
  `prix_fournisseurs` text DEFAULT NULL,
  `delais_livraison` text DEFAULT NULL,
  `stabilite_marche` text DEFAULT NULL,
  `status` enum('draft','submitted','in_review','approved','rejected') NOT NULL DEFAULT 'draft',
  `current_step` int(11) NOT NULL DEFAULT 1,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `review_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `etude_marches`
--

INSERT INTO `etude_marches` (`id`, `candidat_id`, `form_type`, `produit_service`, `description_offre`, `benefices_clients`, `prix_marche`, `controle_prix`, `type_clients`, `caracteristiques_clientele`, `frequence_consommation`, `localisation_clients`, `exigences_principales`, `nombre_concurrents_directs`, `concurrents_indirects`, `taille_concurrents`, `informations_concurrents`, `communication_concurrents`, `nombre_fournisseurs`, `origine_fournisseurs`, `prix_fournisseurs`, `delais_livraison`, `stabilite_marche`, `status`, `current_step`, `submitted_at`, `reviewed_at`, `review_notes`, `created_at`, `updated_at`, `deleted_at`) VALUES
(5, 3, 'etude_marche', 'Produits artisanaux locaux - bijoux, poterie, textiles', 'Offre de produits artisanaux de haute qualité, fabriqués localement avec des matériaux durables', 'Qualité supérieure, produits uniques, soutien aux artisans locaux', 'Prix moyens: 50-500 DH selon le produit', 'Prix contrôlable basé sur les coûts de production', 'Particuliers, touristes, boutiques de décoration', 'Âge 25-55 ans, revenus moyens à élevés', 'Occasionnelle pour les particuliers, régulière pour les boutiques', 'Casablanca, Rabat, Marrakech, international', 'Qualité artisanale, authenticité, délais respectés', '15-20 ateliers artisanaux dans la région', 'Oui, produits industriels à bas prix', 'Petites structures (2-10 employés)', 'Concurrent A: CA 500K DH/an, 10 ans d\'expérience', 'Réseaux sociaux, foires artisanales', '8-10 fournisseurs de matières premières', 'Principalement nationaux (Fès, Marrakech)', 'Prix raisonnables et négociables', 'Délais fiables: 1-2 semaines', 'Marché stable avec variation saisonnière', 'submitted', 3, '2026-02-06 23:28:35', NULL, NULL, '2026-02-06 23:28:30', '2026-02-06 23:28:35', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_idees`
--

CREATE TABLE `evaluation_idees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `candidat_id` bigint(20) UNSIGNED NOT NULL,
  `form_type` varchar(255) DEFAULT NULL,
  `idee_projet` text DEFAULT NULL,
  `resume_idee` text DEFAULT NULL,
  `besoin_projet` text DEFAULT NULL,
  `produits_services` text DEFAULT NULL,
  `clients_identifies` text DEFAULT NULL,
  `idee_existe_marche` text DEFAULT NULL,
  `valeur_ajoutee` text DEFAULT NULL,
  `resultats_prevus` text DEFAULT NULL,
  `proches_comprennent` varchar(255) DEFAULT NULL,
  `reactions_positives` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'draft',
  `current_step` int(11) NOT NULL DEFAULT 1,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `review_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `evaluation_idees`
--

INSERT INTO `evaluation_idees` (`id`, `candidat_id`, `form_type`, `idee_projet`, `resume_idee`, `besoin_projet`, `produits_services`, `clients_identifies`, `idee_existe_marche`, `valeur_ajoutee`, `resultats_prevus`, `proches_comprennent`, `reactions_positives`, `status`, `current_step`, `submitted_at`, `reviewed_at`, `review_notes`, `created_at`, `updated_at`, `deleted_at`) VALUES
(4, 3, 'evaluation_idee', 'Application mobile de livraison de repas traditionnels', 'Une plateforme qui connecte les cuisiniers locaux avec les clients pour la livraison de plats maison traditionnels', 'Besoin de repas faits maison de qualité, accessibles rapidement pour les travailleurs et familles pressées', 'Plateforme mobile + service de livraison + programme de fidélité', 'Oui: employés de bureau (25-45 ans), familles urbaines, étudiants universitaires', 'Des services de livraison existent (Glovo, Jumia Food) mais aucun ne se spécialise dans les repas traditionnels faits maison', 'Authenticité des plats, soutien aux cuisiniers locaux, prix abordables, circuit court', '500 commandes/mois la première année, 50 cuisiniers partenaires, CA de 600K DH/an', 'oui', 'oui', 'submitted', 1, '2026-02-06 23:28:08', NULL, NULL, '2026-02-06 23:24:41', '2026-02-06 23:28:08', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_12_25_000001_create_projects_table', 1),
(5, '2025_12_25_000002_create_project_products_table', 1),
(6, '2025_12_25_000003_create_project_employees_table', 1),
(7, '2025_12_25_000004_create_project_presentations_table', 1),
(8, '2025_12_25_000005_create_project_deliveries_table', 1),
(9, '2025_12_25_000006_create_project_equipment_table', 1),
(10, '2025_12_25_000007_create_project_raw_materials_table', 1),
(11, '2025_12_25_000008_create_project_financials_table', 1),
(12, '2025_12_26_120116_add_role_to_users_table', 1),
(13, '2025_12_26_130000_add_status_to_users_table', 1),
(14, '2025_12_29_091931_add_registration_to_project_table', 1),
(16, '2026_01_30_234031_create_candidat_table', 2),
(17, '2026_01_31_143356_add_candidat_to_project_table', 3),
(18, '2026_02_01_000001_update_projects_table_add_status_fields', 4),
(19, '2026_02_01_000002_create_admin_activity_logs_table', 4),
(20, '2026_02_06_000001_add_form_type_to_projects_table', 5),
(21, '2026_02_06_000002_create_support_tickets_table', 5),
(22, '2026_02_06_100001_create_etude_marches_table', 6),
(23, '2026_02_06_200001_create_evaluation_idees_table', 7),
(24, '2026_02_06_200002_create_bmcs_table', 7),
(25, '2026_02_06_200003_create_bilan_competences_table', 7),
(26, '2026_02_06_120000_add_form_type_to_form_tables', 8),
(27, '2026_02_06_120001_populate_form_type_values', 9),
(28, '2026_02_07_133403_add_tracking_info_to_users_table', 10),
(29, '2026_02_07_133414_add_tracking_info_to_candidat_table', 11),
(30, '2026_02_06_205000_add_form_type_to_form_tables', 12),
(31, '2026_02_06_210000_populate_form_type_values', 12),
(32, '2026_02_07_000001_create_dynamic_forms_tables', 12);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_deliveries`
--

CREATE TABLE `project_deliveries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `business_plan_id` bigint(20) UNSIGNED NOT NULL,
  `product_name_livraison` varchar(255) DEFAULT NULL,
  `livraison_methode` text DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `project_deliveries`
--

INSERT INTO `project_deliveries` (`id`, `business_plan_id`, `product_name_livraison`, `livraison_methode`, `sort_order`, `created_at`, `updated_at`) VALUES
(83, 31, 'Produit A', 'Livraison à domicile', 0, '2026-02-06 23:19:19', '2026-02-06 23:19:19'),
(84, 31, 'Produit B', 'Retrait en magasin', 1, '2026-02-06 23:19:19', '2026-02-06 23:19:19');

-- --------------------------------------------------------

--
-- Table structure for table `project_employees`
--

CREATE TABLE `project_employees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `business_plan_id` bigint(20) UNSIGNED NOT NULL,
  `item` varchar(255) DEFAULT NULL,
  `total_employee_1` decimal(12,2) DEFAULT NULL,
  `total_employee_2` decimal(12,2) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `project_employees`
--

INSERT INTO `project_employees` (`id`, `business_plan_id`, `item`, `total_employee_1`, `total_employee_2`, `sort_order`, `created_at`, `updated_at`) VALUES
(81, 31, 'Directeur', 5000.00, 6000.00, 0, '2026-02-06 23:19:19', '2026-02-06 23:19:19'),
(82, 31, 'Employé', 3000.00, 3500.00, 1, '2026-02-06 23:19:19', '2026-02-06 23:19:19');

-- --------------------------------------------------------

--
-- Table structure for table `project_equipment`
--

CREATE TABLE `project_equipment` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `business_plan_id` bigint(20) UNSIGNED NOT NULL,
  `equipement` varchar(255) DEFAULT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `prix_equipement` decimal(12,2) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `project_equipment`
--

INSERT INTO `project_equipment` (`id`, `business_plan_id`, `equipement`, `reference`, `prix_equipement`, `sort_order`, `created_at`, `updated_at`) VALUES
(75, 31, 'Machine A', 'REF001', 15000.00, 0, '2026-02-06 23:19:19', '2026-02-06 23:19:19'),
(76, 31, 'Machine B', 'REF002', 8000.00, 1, '2026-02-06 23:19:19', '2026-02-06 23:19:19');

-- --------------------------------------------------------

--
-- Table structure for table `project_financials`
--

CREATE TABLE `project_financials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `business_plan_id` bigint(20) UNSIGNED NOT NULL,
  `ventes_premiere_annee` decimal(12,2) DEFAULT NULL,
  `ventes_deuxieme_annee` decimal(12,2) DEFAULT NULL,
  `ventes_troisieme_annee` decimal(12,2) DEFAULT NULL,
  `services_premiere_annee` decimal(12,2) DEFAULT NULL,
  `services_deuxieme_annee` decimal(12,2) DEFAULT NULL,
  `services_troisieme_annee` decimal(12,2) DEFAULT NULL,
  `aide_financiere_premiere_annee` decimal(12,2) DEFAULT NULL,
  `aide_financiere_deuxieme_annee` decimal(12,2) DEFAULT NULL,
  `aide_financiere_troisieme_annee` decimal(12,2) DEFAULT NULL,
  `revenus_financiers_premiere_annee` decimal(12,2) DEFAULT NULL,
  `revenus_financiers_deuxieme_annee` decimal(12,2) DEFAULT NULL,
  `revenus_financiers_troisieme_annee` decimal(12,2) DEFAULT NULL,
  `autres_revenus_premiere_annee` decimal(12,2) DEFAULT NULL,
  `autres_revenus_deuxieme_annee` decimal(12,2) DEFAULT NULL,
  `autres_revenus_troisieme_annee` decimal(12,2) DEFAULT NULL,
  `total_revenus_premiere_annee` decimal(12,2) DEFAULT NULL,
  `total_revenus_deuxieme_annee` decimal(12,2) DEFAULT NULL,
  `total_revenus_troisieme_annee` decimal(12,2) DEFAULT NULL,
  `achat_prevue_premiere_annee` decimal(12,2) DEFAULT NULL,
  `achat_prevue_deuxieme_annee` decimal(12,2) DEFAULT NULL,
  `achat_prevue_troisieme_annee` decimal(12,2) DEFAULT NULL,
  `frais_fonctionnement_premiere_annee` decimal(12,2) DEFAULT NULL,
  `frais_fonctionnement_deuxieme_annee` decimal(12,2) DEFAULT NULL,
  `frais_fonctionnement_troisieme_annee` decimal(12,2) DEFAULT NULL,
  `charges_personnel_premiere_annee` decimal(12,2) DEFAULT NULL,
  `charges_personnel_deuxieme_annee` decimal(12,2) DEFAULT NULL,
  `charges_personnel_troisieme_annee` decimal(12,2) DEFAULT NULL,
  `dettes_premiere_annee` decimal(12,2) DEFAULT NULL,
  `dettes_deuxieme_annee` decimal(12,2) DEFAULT NULL,
  `dettes_troisieme_annee` decimal(12,2) DEFAULT NULL,
  `etablissement_bancaire_premiere_annee` decimal(12,2) DEFAULT NULL,
  `etablissement_bancaire_deuxieme_annee` decimal(12,2) DEFAULT NULL,
  `etablissement_bancaire_troisieme_annee` decimal(12,2) DEFAULT NULL,
  `fournisseurs_premiere_annee` decimal(12,2) DEFAULT NULL,
  `fournisseurs_deuxieme_annee` decimal(12,2) DEFAULT NULL,
  `fournisseurs_troisieme_annee` decimal(12,2) DEFAULT NULL,
  `autres_dettes_premiere_annee` decimal(12,2) DEFAULT NULL,
  `autres_dettes_deuxieme_annee` decimal(12,2) DEFAULT NULL,
  `autres_dettes_troisieme_annee` decimal(12,2) DEFAULT NULL,
  `autres_charges_premiere_annee` decimal(12,2) DEFAULT NULL,
  `autres_charges_deuxieme_annee` decimal(12,2) DEFAULT NULL,
  `autres_charges_troisieme_annee` decimal(12,2) DEFAULT NULL,
  `total_frais_premiere_annee` decimal(12,2) DEFAULT NULL,
  `total_frais_deuxieme_annee` decimal(12,2) DEFAULT NULL,
  `total_frais_troisieme_annee` decimal(12,2) DEFAULT NULL,
  `revenus_premiere_annee` decimal(12,2) DEFAULT NULL,
  `revenus_deuxieme_annee` decimal(12,2) DEFAULT NULL,
  `revenus_troisieme_annee` decimal(12,2) DEFAULT NULL,
  `depenses_premiere_annee` decimal(12,2) DEFAULT NULL,
  `depenses_deuxieme_annee` decimal(12,2) DEFAULT NULL,
  `depenses_troisieme_annee` decimal(12,2) DEFAULT NULL,
  `resultat_premiere_annee` decimal(12,2) DEFAULT NULL,
  `resultat_deuxieme_annee` decimal(12,2) DEFAULT NULL,
  `resultat_troisieme_annee` decimal(12,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `project_financials`
--

INSERT INTO `project_financials` (`id`, `business_plan_id`, `ventes_premiere_annee`, `ventes_deuxieme_annee`, `ventes_troisieme_annee`, `services_premiere_annee`, `services_deuxieme_annee`, `services_troisieme_annee`, `aide_financiere_premiere_annee`, `aide_financiere_deuxieme_annee`, `aide_financiere_troisieme_annee`, `revenus_financiers_premiere_annee`, `revenus_financiers_deuxieme_annee`, `revenus_financiers_troisieme_annee`, `autres_revenus_premiere_annee`, `autres_revenus_deuxieme_annee`, `autres_revenus_troisieme_annee`, `total_revenus_premiere_annee`, `total_revenus_deuxieme_annee`, `total_revenus_troisieme_annee`, `achat_prevue_premiere_annee`, `achat_prevue_deuxieme_annee`, `achat_prevue_troisieme_annee`, `frais_fonctionnement_premiere_annee`, `frais_fonctionnement_deuxieme_annee`, `frais_fonctionnement_troisieme_annee`, `charges_personnel_premiere_annee`, `charges_personnel_deuxieme_annee`, `charges_personnel_troisieme_annee`, `dettes_premiere_annee`, `dettes_deuxieme_annee`, `dettes_troisieme_annee`, `etablissement_bancaire_premiere_annee`, `etablissement_bancaire_deuxieme_annee`, `etablissement_bancaire_troisieme_annee`, `fournisseurs_premiere_annee`, `fournisseurs_deuxieme_annee`, `fournisseurs_troisieme_annee`, `autres_dettes_premiere_annee`, `autres_dettes_deuxieme_annee`, `autres_dettes_troisieme_annee`, `autres_charges_premiere_annee`, `autres_charges_deuxieme_annee`, `autres_charges_troisieme_annee`, `total_frais_premiere_annee`, `total_frais_deuxieme_annee`, `total_frais_troisieme_annee`, `revenus_premiere_annee`, `revenus_deuxieme_annee`, `revenus_troisieme_annee`, `depenses_premiere_annee`, `depenses_deuxieme_annee`, `depenses_troisieme_annee`, `resultat_premiere_annee`, `resultat_deuxieme_annee`, `resultat_troisieme_annee`, `created_at`, `updated_at`) VALUES
(47, 31, 50000.00, 75000.00, 100000.00, 20000.00, 30000.00, 40000.00, 10000.00, 5000.00, 0.00, 2000.00, 3000.00, 4000.00, 1000.00, 2000.00, 3000.00, 83000.00, 115000.00, 147000.00, 30000.00, 40000.00, 50000.00, 12000.00, 15000.00, 18000.00, 24000.00, 30000.00, 36000.00, 5000.00, 3000.00, 1000.00, 2000.00, 2000.00, 2000.00, 8000.00, 10000.00, 12000.00, 1000.00, 500.00, 0.00, 3000.00, 4000.00, 5000.00, 85000.00, 104500.00, 124000.00, 83000.00, 115000.00, 147000.00, 84998.00, 104500.00, 124000.00, -1998.00, 10500.00, 23000.00, '2026-02-06 23:19:19', '2026-02-06 23:19:19');

-- --------------------------------------------------------

--
-- Table structure for table `project_presentations`
--

CREATE TABLE `project_presentations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `business_plan_id` bigint(20) UNSIGNED NOT NULL,
  `product_name_presentation` varchar(255) DEFAULT NULL,
  `presentation_methode` text DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `project_presentations`
--

INSERT INTO `project_presentations` (`id`, `business_plan_id`, `product_name_presentation`, `presentation_methode`, `sort_order`, `created_at`, `updated_at`) VALUES
(78, 31, 'Produit A', 'En magasin', 0, '2026-02-06 23:19:19', '2026-02-06 23:19:19'),
(79, 31, 'Produit B', 'En ligne', 1, '2026-02-06 23:19:19', '2026-02-06 23:19:19');

-- --------------------------------------------------------

--
-- Table structure for table `project_products`
--

CREATE TABLE `project_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `business_plan_id` bigint(20) UNSIGNED NOT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `project_products`
--

INSERT INTO `project_products` (`id`, `business_plan_id`, `product_name`, `description`, `sort_order`, `created_at`, `updated_at`) VALUES
(81, 31, 'Produit A', 'Description produit A', 0, '2026-02-06 23:19:19', '2026-02-06 23:19:19'),
(82, 31, 'Produit B', 'Description produit B', 1, '2026-02-06 23:19:19', '2026-02-06 23:19:19');

-- --------------------------------------------------------

--
-- Table structure for table `project_raw_materials`
--

CREATE TABLE `project_raw_materials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `business_plan_id` bigint(20) UNSIGNED NOT NULL,
  `matiere_premiere` varchar(255) DEFAULT NULL,
  `comment_procurer` text DEFAULT NULL,
  `fournisseur_matiere` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `project_raw_materials`
--

INSERT INTO `project_raw_materials` (`id`, `business_plan_id`, `matiere_premiere`, `comment_procurer`, `fournisseur_matiere`, `sort_order`, `created_at`, `updated_at`) VALUES
(75, 31, 'Matière A', 'Fournisseur local', 'Fournisseur 1', 0, '2026-02-06 23:19:19', '2026-02-06 23:19:19'),
(76, 31, 'Matière B', 'Import', 'Fournisseur 2', 1, '2026-02-06 23:19:19', '2026-02-06 23:19:19');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('3yHnpJchwxmIAoSZOuprjAiwA6zuNcWYBD5tZdpb', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiVURLcWJPdVlZdTZuVVRPYjFPbzBDMTE2V3JscDF5MG1KQ1ZXWDZZQyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9mb3JtL2Rhc2hib2FyZCI7czo1OiJyb3V0ZSI7czoxNDoiZm9ybS5kYXNoYm9hcmQiO31zOjU1OiJsb2dpbl9jYW5kaWRhdF81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjM7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1770426527),
('7rBXcXJNwjg2O3b9xSDVFb73YqzHx9m8ZgAXL8iK', 5, '105.188.107.12', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoidDB2ZGZoRllJbzB1dm1zMlNROWtscUNrRGo4OEZIYXZ0ZDZqUDZ5YyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDA6Imh0dHBzOi8vZGV2Lml1aG0ub3JnL2FkbWluL3Byb2plY3RzX3ZpZXciO3M6NToicm91dGUiO3M6MTk6ImFkbWluLnByb2plY3RzX3ZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo1O30=', 1770492174),
('crC6oaS67Ka9mg9ApfYmQ2DUVBv8asVxsS7N2NGy', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiS2Q0dzd6VHB5V3JJZjZvSlVwTXN3NnhLdXRydTIyQ3FMUm5FMUY0aiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoxMToiYWRtaW4ubG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1770455060),
('IDimSptuKA6AawfQzzGfvhzNzfZFtpuYDTCMsTjy', NULL, '105.188.107.12', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiY2pUODZwbTAySTJOaThtVWQ3djA4V0tpT2VMTVBDUVlRSFdQQjRQbyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzU6Imh0dHBzOi8vZGV2Lml1aG0ub3JnL2Zvcm0vZGFzaGJvYXJkIjtzOjU6InJvdXRlIjtzOjE0OiJmb3JtLmRhc2hib2FyZCI7fXM6NTU6ImxvZ2luX2NhbmRpZGF0XzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mzt9', 1770472460),
('VFGUAKR1NPyxMbHhHqk9wUlXmwsRDDlRyPTT1mIU', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiblpOVVpJbWhOZXFIdVlobUpsaVI2M3JadXM5MW1wOFR0Z0tCRENWeCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC91cGxvYWRzL3Byb2ZpbGUtaW1hZ2VzLzE3NzA0NzE4MTRfNjk4NzQxODZhYjRhZS5qcGciO3M6NToicm91dGUiO3M6MTI6InVwbG9hZHMuc2hvdyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTU6ImxvZ2luX2NhbmRpZGF0XzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NTt9', 1770471818);

-- --------------------------------------------------------

--
-- Table structure for table `support_tickets`
--

CREATE TABLE `support_tickets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `candidat_id` bigint(20) UNSIGNED DEFAULT NULL,
  `assigned_to` bigint(20) UNSIGNED DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `admin_response` text DEFAULT NULL,
  `status` enum('open','in_progress','resolved','closed') NOT NULL DEFAULT 'open',
  `priority` enum('low','medium','high','urgent') NOT NULL DEFAULT 'medium',
  `category` varchar(255) NOT NULL DEFAULT 'general',
  `responded_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `support_tickets`
--

INSERT INTO `support_tickets` (`id`, `candidat_id`, `assigned_to`, `subject`, `message`, `admin_response`, `status`, `priority`, `category`, `responded_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 3, 1, 'test test', 'testtesttestssssssssss', 'nnooo', 'closed', 'urgent', 'technical', '2026-02-06 23:43:30', '2026-02-06 23:42:51', '2026-02-06 23:43:30', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin','super_admin') NOT NULL DEFAULT 'user',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `last_ip_address` varchar(45) DEFAULT NULL,
  `last_user_agent` text DEFAULT NULL,
  `last_browser` varchar(255) DEFAULT NULL,
  `last_platform` varchar(255) DEFAULT NULL,
  `last_device` varchar(255) DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `login_count` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `is_active`, `remember_token`, `last_ip_address`, `last_user_agent`, `last_browser`, `last_platform`, `last_device`, `last_login_at`, `login_count`, `created_at`, `updated_at`) VALUES
(1, 'saad', 'admin@admin.com', NULL, '$2y$12$acce67vErQVY/YXgFCmHq.Usnjr3HTakwzfcxTPZObjMXYigAb7My', 'super_admin', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-12-26 09:47:27', '2025-12-26 10:03:05'),
(5, 'test', 'test@example.com', NULL, '$2y$12$pyK7ItmYF9yZBFFK9lyQu.MzS2OozYepLKCpGRbzsnUstJejcz/EG', 'super_admin', 1, NULL, '105.188.107.12', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Chrome 144.0.0.0', 'Windows 10.0', 'Desktop', '2026-02-07 19:18:26', 1, '2026-02-07 00:08:00', '2026-02-07 19:18:26');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_activity_logs`
--
ALTER TABLE `admin_activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_activity_logs_user_id_index` (`user_id`),
  ADD KEY `admin_activity_logs_action_index` (`action`),
  ADD KEY `admin_activity_logs_subject_type_subject_id_index` (`subject_type`,`subject_id`),
  ADD KEY `admin_activity_logs_created_at_index` (`created_at`);

--
-- Indexes for table `bilan_competences`
--
ALTER TABLE `bilan_competences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bilan_competences_candidat_id_foreign` (`candidat_id`);

--
-- Indexes for table `bmcs`
--
ALTER TABLE `bmcs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bmcs_candidat_id_foreign` (`candidat_id`);

--
-- Indexes for table `business_plans`
--
ALTER TABLE `business_plans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `projects_status_index` (`status`),
  ADD KEY `projects_created_at_index` (`created_at`),
  ADD KEY `projects_reviewed_by_foreign` (`reviewed_by`),
  ADD KEY `projects_form_type_index` (`form_type`),
  ADD KEY `projects_candidat_form_type_index` (`candidat_id`,`form_type`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `candidat`
--
ALTER TABLE `candidat`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `candidat_login_unique` (`login`),
  ADD UNIQUE KEY `candidat_email_unique` (`email`);

--
-- Indexes for table `dynamic_forms`
--
ALTER TABLE `dynamic_forms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dynamic_forms_slug_unique` (`slug`);

--
-- Indexes for table `dynamic_form_answers`
--
ALTER TABLE `dynamic_form_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dynamic_form_answers_dynamic_form_field_id_foreign` (`dynamic_form_field_id`),
  ADD KEY `dfa_submission_field_idx` (`dynamic_form_submission_id`,`field_key`);

--
-- Indexes for table `dynamic_form_fields`
--
ALTER TABLE `dynamic_form_fields`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dynamic_form_fields_dynamic_form_step_id_foreign` (`dynamic_form_step_id`);

--
-- Indexes for table `dynamic_form_steps`
--
ALTER TABLE `dynamic_form_steps`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dynamic_form_steps_dynamic_form_id_step_number_unique` (`dynamic_form_id`,`step_number`);

--
-- Indexes for table `dynamic_form_submissions`
--
ALTER TABLE `dynamic_form_submissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dynamic_form_submissions_dynamic_form_id_candidat_id_unique` (`dynamic_form_id`,`candidat_id`),
  ADD KEY `dynamic_form_submissions_candidat_id_foreign` (`candidat_id`),
  ADD KEY `dynamic_form_submissions_reviewed_by_foreign` (`reviewed_by`);

--
-- Indexes for table `dynamic_form_tables`
--
ALTER TABLE `dynamic_form_tables`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dynamic_form_tables_dynamic_form_step_id_foreign` (`dynamic_form_step_id`);

--
-- Indexes for table `dynamic_form_table_answers`
--
ALTER TABLE `dynamic_form_table_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dynamic_form_table_answers_dynamic_form_table_id_foreign` (`dynamic_form_table_id`),
  ADD KEY `dfta_submission_table_row_idx` (`dynamic_form_submission_id`,`table_key`,`row_index`);

--
-- Indexes for table `dynamic_form_table_columns`
--
ALTER TABLE `dynamic_form_table_columns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dynamic_form_table_columns_dynamic_form_table_id_foreign` (`dynamic_form_table_id`);

--
-- Indexes for table `dynamic_form_table_rows`
--
ALTER TABLE `dynamic_form_table_rows`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dynamic_form_table_rows_dynamic_form_table_id_foreign` (`dynamic_form_table_id`);

--
-- Indexes for table `etude_marches`
--
ALTER TABLE `etude_marches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `etude_marches_candidat_id_status_index` (`candidat_id`,`status`);

--
-- Indexes for table `evaluation_idees`
--
ALTER TABLE `evaluation_idees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `evaluation_idees_candidat_id_foreign` (`candidat_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `project_deliveries`
--
ALTER TABLE `project_deliveries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_deliveries_project_id_index` (`business_plan_id`),
  ADD KEY `project_deliveries_sort_order_index` (`sort_order`);

--
-- Indexes for table `project_employees`
--
ALTER TABLE `project_employees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_employees_project_id_index` (`business_plan_id`),
  ADD KEY `project_employees_sort_order_index` (`sort_order`);

--
-- Indexes for table `project_equipment`
--
ALTER TABLE `project_equipment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_equipment_project_id_index` (`business_plan_id`),
  ADD KEY `project_equipment_sort_order_index` (`sort_order`);

--
-- Indexes for table `project_financials`
--
ALTER TABLE `project_financials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_financials_project_id_index` (`business_plan_id`);

--
-- Indexes for table `project_presentations`
--
ALTER TABLE `project_presentations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_presentations_project_id_index` (`business_plan_id`),
  ADD KEY `project_presentations_sort_order_index` (`sort_order`);

--
-- Indexes for table `project_products`
--
ALTER TABLE `project_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_products_project_id_index` (`business_plan_id`),
  ADD KEY `project_products_sort_order_index` (`sort_order`);

--
-- Indexes for table `project_raw_materials`
--
ALTER TABLE `project_raw_materials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_raw_materials_project_id_index` (`business_plan_id`),
  ADD KEY `project_raw_materials_sort_order_index` (`sort_order`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `support_tickets_assigned_to_foreign` (`assigned_to`),
  ADD KEY `support_tickets_status_index` (`status`),
  ADD KEY `support_tickets_priority_index` (`priority`),
  ADD KEY `support_tickets_candidat_id_index` (`candidat_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_activity_logs`
--
ALTER TABLE `admin_activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `bilan_competences`
--
ALTER TABLE `bilan_competences`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `bmcs`
--
ALTER TABLE `bmcs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `business_plans`
--
ALTER TABLE `business_plans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `candidat`
--
ALTER TABLE `candidat`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `dynamic_forms`
--
ALTER TABLE `dynamic_forms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dynamic_form_answers`
--
ALTER TABLE `dynamic_form_answers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dynamic_form_fields`
--
ALTER TABLE `dynamic_form_fields`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dynamic_form_steps`
--
ALTER TABLE `dynamic_form_steps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dynamic_form_submissions`
--
ALTER TABLE `dynamic_form_submissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dynamic_form_tables`
--
ALTER TABLE `dynamic_form_tables`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dynamic_form_table_answers`
--
ALTER TABLE `dynamic_form_table_answers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dynamic_form_table_columns`
--
ALTER TABLE `dynamic_form_table_columns`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dynamic_form_table_rows`
--
ALTER TABLE `dynamic_form_table_rows`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `etude_marches`
--
ALTER TABLE `etude_marches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `evaluation_idees`
--
ALTER TABLE `evaluation_idees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `project_deliveries`
--
ALTER TABLE `project_deliveries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `project_employees`
--
ALTER TABLE `project_employees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `project_equipment`
--
ALTER TABLE `project_equipment`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `project_financials`
--
ALTER TABLE `project_financials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `project_presentations`
--
ALTER TABLE `project_presentations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `project_products`
--
ALTER TABLE `project_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `project_raw_materials`
--
ALTER TABLE `project_raw_materials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_activity_logs`
--
ALTER TABLE `admin_activity_logs`
  ADD CONSTRAINT `admin_activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bilan_competences`
--
ALTER TABLE `bilan_competences`
  ADD CONSTRAINT `bilan_competences_candidat_id_foreign` FOREIGN KEY (`candidat_id`) REFERENCES `candidat` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bmcs`
--
ALTER TABLE `bmcs`
  ADD CONSTRAINT `bmcs_candidat_id_foreign` FOREIGN KEY (`candidat_id`) REFERENCES `candidat` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `business_plans`
--
ALTER TABLE `business_plans`
  ADD CONSTRAINT `projects_candidat_id_foreign` FOREIGN KEY (`candidat_id`) REFERENCES `candidat` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `projects_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `dynamic_form_answers`
--
ALTER TABLE `dynamic_form_answers`
  ADD CONSTRAINT `dynamic_form_answers_dynamic_form_field_id_foreign` FOREIGN KEY (`dynamic_form_field_id`) REFERENCES `dynamic_form_fields` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `dynamic_form_answers_dynamic_form_submission_id_foreign` FOREIGN KEY (`dynamic_form_submission_id`) REFERENCES `dynamic_form_submissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `dynamic_form_fields`
--
ALTER TABLE `dynamic_form_fields`
  ADD CONSTRAINT `dynamic_form_fields_dynamic_form_step_id_foreign` FOREIGN KEY (`dynamic_form_step_id`) REFERENCES `dynamic_form_steps` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `dynamic_form_steps`
--
ALTER TABLE `dynamic_form_steps`
  ADD CONSTRAINT `dynamic_form_steps_dynamic_form_id_foreign` FOREIGN KEY (`dynamic_form_id`) REFERENCES `dynamic_forms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `dynamic_form_submissions`
--
ALTER TABLE `dynamic_form_submissions`
  ADD CONSTRAINT `dynamic_form_submissions_candidat_id_foreign` FOREIGN KEY (`candidat_id`) REFERENCES `candidat` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dynamic_form_submissions_dynamic_form_id_foreign` FOREIGN KEY (`dynamic_form_id`) REFERENCES `dynamic_forms` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dynamic_form_submissions_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `dynamic_form_tables`
--
ALTER TABLE `dynamic_form_tables`
  ADD CONSTRAINT `dynamic_form_tables_dynamic_form_step_id_foreign` FOREIGN KEY (`dynamic_form_step_id`) REFERENCES `dynamic_form_steps` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `dynamic_form_table_answers`
--
ALTER TABLE `dynamic_form_table_answers`
  ADD CONSTRAINT `dynamic_form_table_answers_dynamic_form_submission_id_foreign` FOREIGN KEY (`dynamic_form_submission_id`) REFERENCES `dynamic_form_submissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dynamic_form_table_answers_dynamic_form_table_id_foreign` FOREIGN KEY (`dynamic_form_table_id`) REFERENCES `dynamic_form_tables` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `dynamic_form_table_columns`
--
ALTER TABLE `dynamic_form_table_columns`
  ADD CONSTRAINT `dynamic_form_table_columns_dynamic_form_table_id_foreign` FOREIGN KEY (`dynamic_form_table_id`) REFERENCES `dynamic_form_tables` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `dynamic_form_table_rows`
--
ALTER TABLE `dynamic_form_table_rows`
  ADD CONSTRAINT `dynamic_form_table_rows_dynamic_form_table_id_foreign` FOREIGN KEY (`dynamic_form_table_id`) REFERENCES `dynamic_form_tables` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `etude_marches`
--
ALTER TABLE `etude_marches`
  ADD CONSTRAINT `etude_marches_candidat_id_foreign` FOREIGN KEY (`candidat_id`) REFERENCES `candidat` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `evaluation_idees`
--
ALTER TABLE `evaluation_idees`
  ADD CONSTRAINT `evaluation_idees_candidat_id_foreign` FOREIGN KEY (`candidat_id`) REFERENCES `candidat` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `project_deliveries`
--
ALTER TABLE `project_deliveries`
  ADD CONSTRAINT `project_deliveries_project_id_foreign` FOREIGN KEY (`business_plan_id`) REFERENCES `business_plans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `project_employees`
--
ALTER TABLE `project_employees`
  ADD CONSTRAINT `project_employees_project_id_foreign` FOREIGN KEY (`business_plan_id`) REFERENCES `business_plans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `project_equipment`
--
ALTER TABLE `project_equipment`
  ADD CONSTRAINT `project_equipment_project_id_foreign` FOREIGN KEY (`business_plan_id`) REFERENCES `business_plans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `project_financials`
--
ALTER TABLE `project_financials`
  ADD CONSTRAINT `project_financials_project_id_foreign` FOREIGN KEY (`business_plan_id`) REFERENCES `business_plans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `project_presentations`
--
ALTER TABLE `project_presentations`
  ADD CONSTRAINT `project_presentations_project_id_foreign` FOREIGN KEY (`business_plan_id`) REFERENCES `business_plans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `project_products`
--
ALTER TABLE `project_products`
  ADD CONSTRAINT `project_products_project_id_foreign` FOREIGN KEY (`business_plan_id`) REFERENCES `business_plans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `project_raw_materials`
--
ALTER TABLE `project_raw_materials`
  ADD CONSTRAINT `project_raw_materials_project_id_foreign` FOREIGN KEY (`business_plan_id`) REFERENCES `business_plans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD CONSTRAINT `support_tickets_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `support_tickets_candidat_id_foreign` FOREIGN KEY (`candidat_id`) REFERENCES `candidat` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
