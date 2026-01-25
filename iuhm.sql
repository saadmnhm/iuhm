-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 25, 2026 at 01:42 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `iuhm`
--

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
('laravel-cache-356a192b7913b04c54574d18c28d46e6395428ab', 'i:1;', 1769299260),
('laravel-cache-356a192b7913b04c54574d18c28d46e6395428ab:timer', 'i:1769299260;', 1769299260);

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
(14, '2025_12_29_091931_add_registration_to_project_table', 1);

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
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `gender` enum('homme','femme') DEFAULT NULL,
  `address` enum('Hay Mohamadi','Ain Sbaa','Roches Noires') DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `project_name` varchar(255) DEFAULT NULL,
  `ceo_name` varchar(255) DEFAULT NULL,
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
  `status` enum('draft','submitted','approved','rejected') NOT NULL DEFAULT 'draft',
  `current_step` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `profile_image`, `age`, `gender`, `address`, `email`, `phone`, `project_name`, `ceo_name`, `description`, `registration`, `legal_structure`, `resume_executif`, `public_cible`, `concurrent`, `volume_produits_locaux`, `volume_demande`, `demande_offre`, `motivations_achat`, `raison_choix_client`, `méthodes_marketing`, `adaptation_methodes`, `differenciation_marketing`, `plan_affaires`, `obtention_financement`, `ouverture_proces`, `lancement_recrutement`, `ouverture_definitive`, `duree`, `lieu_projet`, `adaptation_lieu`, `benefices_from_projet`, `valeur_projet`, `step_8_1`, `step_8_2`, `step_8_3`, `step_8_4`, `couts_creation`, `preparation_entreprise`, `achat_machines`, `achat_matieres_premieres`, `autres_couts`, `total`, `generer_profits`, `projet_durable`, `status`, `current_step`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, NULL, 25, 'homme', 'Ain Sbaa', 'test@example.com', '0612345678', 'Mon Projet Test', 'ts saad', 'Description du projet de test', NULL, 'SARL', 'Résumé exécutif du projet', 'Jeunes entrepreneurs', 'Concurrent A, Concurrent B', 'Volume moyen', 'Forte demande', 'Équilibrée', 'Qualité et prix', 'Meilleur rapport qualité-prix', 'Réseaux sociaux, publicité locale', 'Adaptation selon le budget', 'Prix compétitifs', 'Janvier 2026', 'Février 2026', 'Mars 2026', 'Avril 2026', 'Mai 2026', '6 mois', 'Casablanca, Hay Mohamadi', 'Oui, très adapté', 'Revenus mensuels stables', 'Bénéfices + expérience + réseau', 'Oui, compétences acquises', 'Oui, matériel disponible', 'Oui, 5 ans d\'expérience', 'Oui, fonds disponibles', 10000.00, 5000.00, 20000.00, 8000.00, 3000.00, NULL, 'Le projet générera des profits à partir de la deuxième année', 'Le projet est durable grâce à la croissance constante', 'submitted', 8, '2026-01-24 21:36:48', '2026-01-24 21:36:48', NULL),
(2, 'profile-images/XyZ3jhQ1VQKOZHedGw1H5ELW3yfKmSzp447QzUDK.png', 25, 'homme', 'Hay Mohamadi', 'test@example.com', '0612345678', 'Mon Projet Test', 'Ahmed Hassan', 'Description du projet de test', 'MAT-2223454', 'SARL', 'Résumé exécutif du projet', 'Jeunes entrepreneurs', 'Concurrent A, Concurrent B', 'Volume moyen', 'Forte demande', 'Équilibrée', 'Qualité et prix', 'Meilleur rapport qualité-prix', 'Réseaux sociaux, publicité locale', 'Adaptation selon le budget', 'Prix compétitifs', 'Janvier 2026', 'Février 2026', 'Mars 2026', 'Avril 2026', 'Mai 2026', '6 mois', 'Casablanca, Hay Mohamadi', 'Oui, très adapté', 'Revenus mensuels stables', 'Bénéfices + expérience + réseau', 'Oui, compétences acquises', 'Oui, matériel disponible', 'Oui, 5 ans d\'expérience', 'Oui, fonds disponibles', 10000.00, 5000.00, 20000.00, 8000.00, 3000.00, NULL, 'Le projet générera des profits à partir de la deuxième année', 'Le projet est durable grâce à la croissance constante', 'submitted', 8, '2026-01-24 21:38:29', '2026-01-24 22:17:55', NULL),
(3, 'profile-images/jcpnTh1XLbolaK4eh7MzqJqKtYBwanppl08xSsVB.png', 25, 'homme', 'Hay Mohamadi', 'test@example.com', '0612345678', 'Mon Projet Test', 'Ahmed Hassan', 'Description du projet de test', NULL, 'SARL', 'Résumé exécutif du projet', 'Jeunes entrepreneurs', 'Concurrent A, Concurrent B', 'Volume moyen', 'Forte demande', 'Équilibrée', 'Qualité et prix', 'Meilleur rapport qualité-prix', 'Réseaux sociaux, publicité locale', 'Adaptation selon le budget', 'Prix compétitifs', 'Janvier 2026', 'Février 2026', 'Mars 2026', 'Avril 2026', 'Mai 2026', '6 mois', 'Casablanca, Hay Mohamadi', 'Oui, très adapté', 'Revenus mensuels stables', 'Bénéfices + expérience + réseau', 'Oui, compétences acquises', 'Oui, matériel disponible', 'Oui, 5 ans d\'expérience', 'Oui, fonds disponibles', 10000.00, 5000.00, 20000.00, 8000.00, 30009.00, 73009.00, 'Le projet générera des profits à partir de la deuxième année', 'Le projet est durable grâce à la croissance constante', 'submitted', 8, '2026-01-24 22:04:42', '2026-01-24 22:04:42', NULL),
(4, 'profile-images/9xiIvLH7WJ1t3t3BVS2lN9ICvQkBBDupVrm9qdRS.png', 25, 'femme', 'Roches Noires', 'test@example.com', '0612345678', 'Mon Projet Test', 'test 4', 'Description du projet de test', NULL, 'SARL', 'Résumé exécutif du projet', 'Jeunes entrepreneurs', 'Concurrent A, Concurrent B', 'Volume moyen', 'Forte demande', 'Équilibrée', 'Qualité et prix', 'Meilleur rapport qualité-prix', 'Réseaux sociaux, publicité locale', 'Adaptation selon le budget', 'Prix compétitifs', 'Janvier 2026', 'Février 2026', 'Mars 2026', 'Avril 2026', 'Mai 2026', '6 mois', 'Casablanca, Hay Mohamadi', 'Oui, très adapté', 'Revenus mensuels stables', 'Bénéfices + expérience + réseau', 'Oui, compétences acquises', 'Oui, matériel disponible', 'Oui, 5 ans d\'expérience', 'Oui, fonds disponibles', 10000.00, 5000.00, 20000.00, 8000.00, 30004.00, 73004.00, 'Le projet générera des profits à partir de la deuxième année', 'Le projet est durable grâce à la croissance constante', 'submitted', 8, '2026-01-24 23:00:11', '2026-01-24 23:00:11', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `project_deliveries`
--

CREATE TABLE `project_deliveries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `product_name_livraison` varchar(255) DEFAULT NULL,
  `livraison_methode` text DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `project_deliveries`
--

INSERT INTO `project_deliveries` (`id`, `project_id`, `product_name_livraison`, `livraison_methode`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 1, 'Produit A', 'Livraison à domicile', 0, '2026-01-24 21:36:48', '2026-01-24 21:36:48'),
(2, 1, 'Produit B', 'Retrait en magasin', 1, '2026-01-24 21:36:48', '2026-01-24 21:36:48'),
(3, 2, 'Produit A', 'Livraison à domicile', 0, '2026-01-24 21:38:29', '2026-01-24 21:38:29'),
(4, 2, 'Produit B', 'Retrait en magasin', 1, '2026-01-24 21:38:29', '2026-01-24 21:38:29'),
(5, 3, 'Produit A', 'Livraison à domicile', 0, '2026-01-24 22:04:42', '2026-01-24 22:04:42'),
(6, 3, 'Produit B', 'Retrait en magasin', 1, '2026-01-24 22:04:42', '2026-01-24 22:04:42'),
(7, 4, 'poduit 4', 'Livraison à domicile', 0, '2026-01-24 23:00:11', '2026-01-24 23:00:11'),
(8, 4, 'Produit B', 'Retrait en magasin', 1, '2026-01-24 23:00:11', '2026-01-24 23:00:11');

-- --------------------------------------------------------

--
-- Table structure for table `project_employees`
--

CREATE TABLE `project_employees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
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

INSERT INTO `project_employees` (`id`, `project_id`, `item`, `total_employee_1`, `total_employee_2`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 1, 'Directeur', 5000.00, 6000.00, 0, '2026-01-24 21:36:48', '2026-01-24 21:36:48'),
(2, 1, 'Employé', 3000.00, 3500.00, 1, '2026-01-24 21:36:48', '2026-01-24 21:36:48'),
(3, 2, 'Directeur', 5000.00, 6000.00, 0, '2026-01-24 21:38:29', '2026-01-24 21:38:29'),
(4, 2, 'Employé', 3000.00, 3500.00, 1, '2026-01-24 21:38:29', '2026-01-24 21:38:29'),
(5, 3, 'Directeur', 5000.00, 6000.00, 0, '2026-01-24 22:04:42', '2026-01-24 22:04:42'),
(6, 3, 'Employé', 3000.00, 3500.00, 1, '2026-01-24 22:04:42', '2026-01-24 22:04:42'),
(7, 4, 'Directeur', 5000.00, 6000.00, 0, '2026-01-24 23:00:11', '2026-01-24 23:00:11'),
(8, 4, 'Employé', 3000.00, 3500.00, 1, '2026-01-24 23:00:11', '2026-01-24 23:00:11');

-- --------------------------------------------------------

--
-- Table structure for table `project_equipment`
--

CREATE TABLE `project_equipment` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
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

INSERT INTO `project_equipment` (`id`, `project_id`, `equipement`, `reference`, `prix_equipement`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 1, 'Machine A', 'REF001', 15000.00, 0, '2026-01-24 21:36:48', '2026-01-24 21:36:48'),
(2, 1, 'Machine B', 'REF002', 8000.00, 1, '2026-01-24 21:36:48', '2026-01-24 21:36:48'),
(3, 2, 'Machine A', 'REF001', 15000.00, 0, '2026-01-24 21:38:29', '2026-01-24 21:38:29'),
(4, 2, 'Machine B', 'REF002', 8000.00, 1, '2026-01-24 21:38:29', '2026-01-24 21:38:29'),
(5, 3, 'Machine A', 'REF001', 15000.00, 0, '2026-01-24 22:04:42', '2026-01-24 22:04:42'),
(6, 3, 'Machine B', 'REF002', 8000.00, 1, '2026-01-24 22:04:42', '2026-01-24 22:04:42'),
(7, 4, 'Machine A', 'REF001', 15000.00, 0, '2026-01-24 23:00:11', '2026-01-24 23:00:11'),
(8, 4, 'Machine B', 'REF002', 8000.00, 1, '2026-01-24 23:00:11', '2026-01-24 23:00:11');

-- --------------------------------------------------------

--
-- Table structure for table `project_financials`
--

CREATE TABLE `project_financials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
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

INSERT INTO `project_financials` (`id`, `project_id`, `ventes_premiere_annee`, `ventes_deuxieme_annee`, `ventes_troisieme_annee`, `services_premiere_annee`, `services_deuxieme_annee`, `services_troisieme_annee`, `aide_financiere_premiere_annee`, `aide_financiere_deuxieme_annee`, `aide_financiere_troisieme_annee`, `revenus_financiers_premiere_annee`, `revenus_financiers_deuxieme_annee`, `revenus_financiers_troisieme_annee`, `autres_revenus_premiere_annee`, `autres_revenus_deuxieme_annee`, `autres_revenus_troisieme_annee`, `total_revenus_premiere_annee`, `total_revenus_deuxieme_annee`, `total_revenus_troisieme_annee`, `achat_prevue_premiere_annee`, `achat_prevue_deuxieme_annee`, `achat_prevue_troisieme_annee`, `frais_fonctionnement_premiere_annee`, `frais_fonctionnement_deuxieme_annee`, `frais_fonctionnement_troisieme_annee`, `charges_personnel_premiere_annee`, `charges_personnel_deuxieme_annee`, `charges_personnel_troisieme_annee`, `dettes_premiere_annee`, `dettes_deuxieme_annee`, `dettes_troisieme_annee`, `etablissement_bancaire_premiere_annee`, `etablissement_bancaire_deuxieme_annee`, `etablissement_bancaire_troisieme_annee`, `fournisseurs_premiere_annee`, `fournisseurs_deuxieme_annee`, `fournisseurs_troisieme_annee`, `autres_dettes_premiere_annee`, `autres_dettes_deuxieme_annee`, `autres_dettes_troisieme_annee`, `autres_charges_premiere_annee`, `autres_charges_deuxieme_annee`, `autres_charges_troisieme_annee`, `total_frais_premiere_annee`, `total_frais_deuxieme_annee`, `total_frais_troisieme_annee`, `revenus_premiere_annee`, `revenus_deuxieme_annee`, `revenus_troisieme_annee`, `depenses_premiere_annee`, `depenses_deuxieme_annee`, `depenses_troisieme_annee`, `resultat_premiere_annee`, `resultat_deuxieme_annee`, `resultat_troisieme_annee`, `created_at`, `updated_at`) VALUES
(1, 1, 50000.00, 75000.00, 100000.00, 20000.00, 30000.00, 40000.00, 10000.00, 5000.00, 0.00, 2000.00, 3000.00, 4000.00, 1000.00, 2000.00, 3000.00, 1.00, 115000.00, 147000.00, 30000.00, 40000.00, 50000.00, 12000.00, 15000.00, 18000.00, 24000.00, 30000.00, 36000.00, 5000.00, 3000.00, 1000.00, 2000.00, 2000.00, 2000.00, 8000.00, 10000.00, 12000.00, 1000.00, 500.00, 0.00, 3000.00, 4000.00, 5000.00, 85000.00, 104500.00, 124000.00, 1.00, 115000.00, 147000.00, 85000.00, 104500.00, 124000.00, -2000.00, 10500.00, 23000.00, '2026-01-24 21:36:48', '2026-01-24 23:30:13'),
(2, 2, 50000.00, 75000.00, 100000.00, 20000.00, 30000.00, 40000.00, 10000.00, 5000.00, 0.00, 2000.00, 3000.00, 4000.00, 1000.00, 2000.00, 3000.00, 1.00, 115000.00, 147000.00, 30000.00, 40000.00, 50000.00, 12000.00, 15000.00, 18000.00, 24000.00, 30000.00, 36000.00, 5000.00, 3000.00, 1000.00, 2000.00, 2000.00, 2000.00, 8000.00, 10000.00, 12000.00, 1000.00, 500.00, 0.00, 3000.00, 4000.00, 5000.00, 85000.00, 104500.00, 124000.00, 83000.00, 115000.00, 147000.00, 85000.00, 104500.00, 124000.00, -2000.00, 10500.00, 23000.00, '2026-01-24 21:38:29', '2026-01-24 23:30:13'),
(3, 3, 50000.00, 75000.00, 100000.00, 20000.00, 30000.00, 40000.00, 10000.00, 5000.00, 0.00, 2000.00, 3000.00, 4000.00, 1000.00, 2000.00, 3000.00, 1.00, 115000.00, 147000.00, 30000.00, 40000.00, 50000.00, 12000.00, 15000.00, 18000.00, 24000.00, 30000.00, 36000.00, 5000.00, 3000.00, 1000.00, 2000.00, 2000.00, 2000.00, 8000.00, 10000.00, 12000.00, 1000.00, 500.00, 0.00, 3000.00, 4000.00, 5000.00, 85000.00, 104500.00, 124000.00, 83000.00, 115000.00, 147000.00, 850000.00, 104500.00, 124000.00, -767000.00, 10500.00, 23000.00, '2026-01-24 22:04:42', '2026-01-24 23:30:13'),
(4, 4, 50000.00, 75000.00, 100000.00, 20000.00, 30000.00, 40000.00, 10000.00, 5000.00, 0.00, 2000.00, 3000.00, 4000.00, 1000.00, 2000.00, 3000.00, 1.00, 115000.00, 147000.00, 30000.00, 40000.00, 50000.00, 12000.00, 15000.00, 18000.00, 24000.00, 30000.00, 36000.00, 5000.00, 3000.00, 1000.00, 2000.00, 2000.00, 2000.00, 8000.00, 10000.00, 12000.00, 1000.00, 500.00, 0.00, 3000.00, 4000.00, 5000.00, 85000.00, 104500.00, 124000.00, 2.00, 115000.00, 147000.00, 85000.00, 104500.00, 124000.00, 1.00, 10500.00, 23000.00, '2026-01-24 23:00:11', '2026-01-24 23:30:13');

-- --------------------------------------------------------

--
-- Table structure for table `project_presentations`
--

CREATE TABLE `project_presentations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `product_name_presentation` varchar(255) DEFAULT NULL,
  `presentation_methode` text DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `project_presentations`
--

INSERT INTO `project_presentations` (`id`, `project_id`, `product_name_presentation`, `presentation_methode`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 1, 'Produit A', 'En magasin', 0, '2026-01-24 21:36:48', '2026-01-24 21:36:48'),
(2, 1, 'Produit B', 'En ligne', 1, '2026-01-24 21:36:48', '2026-01-24 21:36:48'),
(3, 2, 'Produit A', 'En magasin', 0, '2026-01-24 21:38:29', '2026-01-24 21:38:29'),
(4, 2, 'Produit B', 'En ligne', 1, '2026-01-24 21:38:29', '2026-01-24 21:38:29'),
(5, 3, 'Produit A', 'En magasin', 0, '2026-01-24 22:04:42', '2026-01-24 22:04:42'),
(6, 3, 'Produit B', 'En ligne', 1, '2026-01-24 22:04:42', '2026-01-24 22:04:42'),
(7, 4, 'Produit A', 'En magasin', 0, '2026-01-24 23:00:11', '2026-01-24 23:00:11'),
(8, 4, 'Produit B', 'En ligne', 1, '2026-01-24 23:00:11', '2026-01-24 23:00:11');

-- --------------------------------------------------------

--
-- Table structure for table `project_products`
--

CREATE TABLE `project_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `project_products`
--

INSERT INTO `project_products` (`id`, `project_id`, `product_name`, `description`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 1, 'Produit A', 'Description produit A', 0, '2026-01-24 21:36:48', '2026-01-24 21:36:48'),
(2, 1, 'Produit B', 'Description produit B', 1, '2026-01-24 21:36:48', '2026-01-24 21:36:48'),
(3, 2, 'Produit A', 'Description produit A', 0, '2026-01-24 21:38:29', '2026-01-24 21:38:29'),
(4, 2, 'Produit B', 'Description produit B', 1, '2026-01-24 21:38:29', '2026-01-24 21:38:29'),
(5, 3, 'Produit A', 'Description produit A', 0, '2026-01-24 22:04:42', '2026-01-24 22:04:42'),
(6, 3, 'Produit B', 'Description produit B', 1, '2026-01-24 22:04:42', '2026-01-24 22:04:42'),
(7, 4, 'Produit A', 'Description produit A', 0, '2026-01-24 23:00:11', '2026-01-24 23:00:11'),
(8, 4, 'Produit B', 'Description produit B', 1, '2026-01-24 23:00:11', '2026-01-24 23:00:11');

-- --------------------------------------------------------

--
-- Table structure for table `project_raw_materials`
--

CREATE TABLE `project_raw_materials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
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

INSERT INTO `project_raw_materials` (`id`, `project_id`, `matiere_premiere`, `comment_procurer`, `fournisseur_matiere`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 1, 'Matière A', 'Fournisseur local', 'Fournisseur 1', 0, '2026-01-24 21:36:48', '2026-01-24 21:36:48'),
(2, 1, 'Matière B', 'Import', 'Fournisseur 2', 1, '2026-01-24 21:36:48', '2026-01-24 21:36:48'),
(3, 2, 'Matière A', 'Fournisseur local', 'Fournisseur 1', 0, '2026-01-24 21:38:29', '2026-01-24 21:38:29'),
(4, 2, 'Matière B', 'Import', 'Fournisseur 2', 1, '2026-01-24 21:38:29', '2026-01-24 21:38:29'),
(5, 3, 'Matière A', 'Fournisseur local', 'Fournisseur 1', 0, '2026-01-24 22:04:42', '2026-01-24 22:04:42'),
(6, 3, 'Matière B', 'Import', 'Fournisseur 2', 1, '2026-01-24 22:04:42', '2026-01-24 22:04:42'),
(7, 3, 'Matière s', 'Import', 'Fournisseur local', 2, '2026-01-24 22:04:42', '2026-01-24 22:04:42'),
(8, 4, 'Matière A', 'Fournisseur local', 'Fournisseur 1', 0, '2026-01-24 23:00:11', '2026-01-24 23:00:11'),
(9, 4, 'Matière B', 'Import', 'Fournisseur 2', 1, '2026-01-24 23:00:11', '2026-01-24 23:00:11');

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
('ehJjHtX31T8UHfRl9m4UJ3HJmeE5SGMSonW0Y6gH', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiN0FFSDFHaTRHNmxGSG1FNTdFZmhqMFJQNFhBenNvVTZOOGJlSUpmOCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoxMToiYWRtaW4ubG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1769344474),
('kIHQRcRz28x2mqplOW7e4hzN7ruNplOg3QXDix1d', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidklCNFVyYUpQUUpuY3g5QmxRTzVmYTU4bjVvTXNMSUtMOWpVa20zSiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1769344473);

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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `is_active`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'saad', 'admin@admin.com', NULL, '$2y$12$acce67vErQVY/YXgFCmHq.Usnjr3HTakwzfcxTPZObjMXYigAb7My', 'super_admin', 1, NULL, '2025-12-26 09:47:27', '2025-12-26 10:03:05'),
(2, 'simple', 'superadmin@admin.com', NULL, '$2y$12$nklBWtXlfXTfiTh9qyV5uuF3OzoPPZXMjk3ejZ7n8PGibVS7v01fS', 'super_admin', 1, NULL, '2025-12-26 10:02:55', '2025-12-26 10:03:05');

--
-- Indexes for dumped tables
--

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
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `projects_status_index` (`status`),
  ADD KEY `projects_email_index` (`email`),
  ADD KEY `projects_created_at_index` (`created_at`);

--
-- Indexes for table `project_deliveries`
--
ALTER TABLE `project_deliveries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_deliveries_project_id_index` (`project_id`),
  ADD KEY `project_deliveries_sort_order_index` (`sort_order`);

--
-- Indexes for table `project_employees`
--
ALTER TABLE `project_employees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_employees_project_id_index` (`project_id`),
  ADD KEY `project_employees_sort_order_index` (`sort_order`);

--
-- Indexes for table `project_equipment`
--
ALTER TABLE `project_equipment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_equipment_project_id_index` (`project_id`),
  ADD KEY `project_equipment_sort_order_index` (`sort_order`);

--
-- Indexes for table `project_financials`
--
ALTER TABLE `project_financials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_financials_project_id_index` (`project_id`);

--
-- Indexes for table `project_presentations`
--
ALTER TABLE `project_presentations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_presentations_project_id_index` (`project_id`),
  ADD KEY `project_presentations_sort_order_index` (`sort_order`);

--
-- Indexes for table `project_products`
--
ALTER TABLE `project_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_products_project_id_index` (`project_id`),
  ADD KEY `project_products_sort_order_index` (`sort_order`);

--
-- Indexes for table `project_raw_materials`
--
ALTER TABLE `project_raw_materials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_raw_materials_project_id_index` (`project_id`),
  ADD KEY `project_raw_materials_sort_order_index` (`sort_order`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `project_deliveries`
--
ALTER TABLE `project_deliveries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `project_employees`
--
ALTER TABLE `project_employees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `project_equipment`
--
ALTER TABLE `project_equipment`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `project_financials`
--
ALTER TABLE `project_financials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `project_presentations`
--
ALTER TABLE `project_presentations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `project_products`
--
ALTER TABLE `project_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `project_raw_materials`
--
ALTER TABLE `project_raw_materials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `project_deliveries`
--
ALTER TABLE `project_deliveries`
  ADD CONSTRAINT `project_deliveries_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `project_employees`
--
ALTER TABLE `project_employees`
  ADD CONSTRAINT `project_employees_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `project_equipment`
--
ALTER TABLE `project_equipment`
  ADD CONSTRAINT `project_equipment_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `project_financials`
--
ALTER TABLE `project_financials`
  ADD CONSTRAINT `project_financials_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `project_presentations`
--
ALTER TABLE `project_presentations`
  ADD CONSTRAINT `project_presentations_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `project_products`
--
ALTER TABLE `project_products`
  ADD CONSTRAINT `project_products_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `project_raw_materials`
--
ALTER TABLE `project_raw_materials`
  ADD CONSTRAINT `project_raw_materials_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
