-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Hôte : shareddb-n.hosting.stackcp.net
-- Généré le : ven. 27 fév. 2026 à 10:46
-- Version du serveur : 10.6.18-MariaDB-log
-- Version de PHP : 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `dev_iuhm_zettat-3130373f8f`
--
CREATE DATABASE IF NOT EXISTS `dev_iuhm_zettat-3130373f8f` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `dev_iuhm_zettat-3130373f8f`;

-- --------------------------------------------------------

--
-- Structure de la table `addresses`
--

CREATE TABLE `addresses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `address_line1` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) DEFAULT NULL,
  `postal_code` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `admin_activity_logs`
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

-- --------------------------------------------------------

--
-- Structure de la table `association_parameters`
--

CREATE TABLE `association_parameters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category` varchar(255) NOT NULL,
  `key` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'text',
  `options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`options`)),
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `bilan_competences`
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

-- --------------------------------------------------------

--
-- Structure de la table `blog_posts`
--

CREATE TABLE `blog_posts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `title_ar` varchar(255) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `excerpt` text DEFAULT NULL,
  `content` longtext NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tags`)),
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `published_at` timestamp NULL DEFAULT NULL,
  `author_id` bigint(20) UNSIGNED NOT NULL,
  `views_count` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `bmcs`
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

-- --------------------------------------------------------

--
-- Structure de la table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `candidat`
--

CREATE TABLE `candidat` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `matricule` varchar(50) DEFAULT NULL,
  `login` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `gender` enum('homme','femme') DEFAULT NULL,
  `address` enum('Hay Mohamadi','Ain Sbaa','Roches Noires') DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `date_naissance` date DEFAULT NULL,
  `cv_path` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `last_ip_address` varchar(45) DEFAULT NULL,
  `last_user_agent` text DEFAULT NULL,
  `last_browser` varchar(255) DEFAULT NULL,
  `last_platform` varchar(255) DEFAULT NULL,
  `last_device` varchar(255) DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `login_count` int(11) NOT NULL DEFAULT 0,
  `reviewed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `review_notes` text DEFAULT NULL,
  `review_status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `dynamic_forms`
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
  `has_introduction` tinyint(1) NOT NULL DEFAULT 0,
  `introduction_title` text DEFAULT NULL,
  `introduction_title_ar` text DEFAULT NULL,
  `introduction_content` longtext DEFAULT NULL,
  `introduction_content_ar` longtext DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `dynamic_form_answers`
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
-- Structure de la table `dynamic_form_fields`
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
-- Structure de la table `dynamic_form_steps`
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
-- Structure de la table `dynamic_form_submissions`
--

CREATE TABLE `dynamic_form_submissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dynamic_form_id` bigint(20) UNSIGNED NOT NULL,
  `candidat_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `programe_id` bigint(20) UNSIGNED DEFAULT NULL,
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
-- Structure de la table `dynamic_form_tables`
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
-- Structure de la table `dynamic_form_table_answers`
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
-- Structure de la table `dynamic_form_table_columns`
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
-- Structure de la table `dynamic_form_table_rows`
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
-- Structure de la table `etude_marches`
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

-- --------------------------------------------------------

--
-- Structure de la table `evaluation_idees`
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

-- --------------------------------------------------------

--
-- Structure de la table `failed_jobs`
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
-- Structure de la table `jobs`
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
-- Structure de la table `job_batches`
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
-- Structure de la table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_12_25_000001_create_projects_table', 1),
(5, '2025_12_26_120116_add_role_to_users_table', 2),
(6, '2025_12_26_130000_add_status_to_users_table', 2),
(7, '2026_01_30_234031_create_candidat_table', 2),
(8, '2026_01_31_143356_add_candidat_to_project_table', 2),
(9, '2026_02_01_000001_update_projects_table_add_status_fields', 2),
(10, '2026_02_01_000002_create_admin_activity_logs_table', 2),
(11, '2026_02_06_000001_add_form_type_to_projects_table', 2),
(12, '2026_02_06_000002_create_support_tickets_table', 2),
(13, '2026_02_06_100001_create_etude_marches_table', 2),
(14, '2026_02_06_200001_create_evaluation_idees_table', 2),
(15, '2026_02_06_200002_create_bmcs_table', 2),
(16, '2026_02_06_200003_create_bilan_competences_table', 2),
(17, '2026_02_06_205000_add_form_type_to_form_tables', 2),
(18, '2026_02_06_210000_populate_form_type_values', 2),
(19, '2026_02_07_000001_create_dynamic_forms_tables', 2),
(20, '2026_02_07_133403_add_tracking_info_to_users_table', 2),
(21, '2026_02_07_133414_add_tracking_info_to_candidat_table', 2),
(22, '2026_02_08_212936_create_addresses_table', 2),
(23, '2026_02_08_214406_create_programe_list_table', 2),
(24, '2026_02_08_220000_update_programe_list_for_multiple_addresses', 2),
(25, '2026_02_08_231925_add_created_by_to_programe_list_table', 2),
(26, '2026_02_21_000001_create_programe_formulaire_table', 2),
(27, '2026_02_21_000002_add_introduction_to_dynamic_forms', 2),
(28, '2026_02_21_000003_add_programe_id_to_submissions', 2),
(29, '2026_02_21_200427_make_candidat_id_nullable_in_dynamic_form_submissions', 2),
(30, '2026_02_22_034839_fix_dynamic_form_submissions_unique_constraint_to_include_programe', 2),
(31, '2026_02_26_000001_add_reviewer_fields_to_candidat_table', 2),
(32, '2026_02_26_234511_add_matricule_to_candidat_table', 2),
(33, '2026_02_26_234827_create_rh_employees_table', 2),
(34, '2026_02_26_235455_create_association_parameters_table', 2),
(35, '2026_02_26_235653_create_blog_posts_table', 2),
(36, '2026_02_27_000829_create_submission_histories_table', 2),
(37, '2026_02_27_100000_alter_users_role_to_varchar', 2),
(38, '2026_02_27_100001_create_roles_table', 2),
(39, '2026_02_27_100002_create_role_permissions_table', 2);

-- --------------------------------------------------------

--
-- Structure de la table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `programe_formulaire`
--

CREATE TABLE `programe_formulaire` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `programe_id` bigint(20) UNSIGNED NOT NULL,
  `formulaire_id` bigint(20) UNSIGNED NOT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `status` enum('active','inactive','draft') NOT NULL DEFAULT 'active',
  `is_required` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `programe_list`
--

CREATE TABLE `programe_list` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `color` varchar(7) DEFAULT NULL,
  `bg_color` varchar(7) DEFAULT NULL,
  `min_age` int(11) DEFAULT NULL,
  `max_age` int(11) DEFAULT NULL,
  `allowed_address_id` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`allowed_address_id`)),
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `form_attached_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `projects`
--

CREATE TABLE `projects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `candidat_id` bigint(20) UNSIGNED DEFAULT NULL,
  `form_type` varchar(255) NOT NULL DEFAULT 'business_plan',
  `project_name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
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

-- --------------------------------------------------------

--
-- Structure de la table `project_products`
--

CREATE TABLE `project_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `candidat_id` bigint(20) UNSIGNED NOT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `rh_employees`
--

CREATE TABLE `rh_employees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `matricule` varchar(50) DEFAULT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `cin` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `poste` varchar(255) DEFAULT NULL,
  `departement` varchar(255) DEFAULT NULL,
  `contrat_type` enum('CDI','CDD','Stage','Freelance','Autre') NOT NULL DEFAULT 'CDI',
  `date_embauche` date DEFAULT NULL,
  `date_fin_contrat` date DEFAULT NULL,
  `salaire` decimal(10,2) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `gender` enum('homme','femme') DEFAULT NULL,
  `date_naissance` date DEFAULT NULL,
  `status` enum('active','inactive','en_conge','quitte') NOT NULL DEFAULT 'active',
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `label` varchar(100) NOT NULL,
  `color` varchar(30) NOT NULL DEFAULT 'blue',
  `is_system` tinyint(1) NOT NULL DEFAULT 0,
  `can_access_admin` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_name` varchar(50) NOT NULL,
  `module_key` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sessions`
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
-- Déchargement des données de la table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('sVHJA0HZzcJ4qD7K2kE4TADYvjLF5Ia0BJLQliBn', 1, '41.141.234.39', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZ3hqd3JObklxZ0p0aFFhTEJXY2NaMjJpYzlRdkZmYXJ0S0lVMTVoNCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHBzOi8vZGV2Lml1aG0ub3JnL2FkbWluL2Rhc2hib2FyZCI7czo1OiJyb3V0ZSI7czoxNToiYWRtaW4uZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1772188061);

-- --------------------------------------------------------

--
-- Structure de la table `submission_histories`
--

CREATE TABLE `submission_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `subject_type` varchar(255) NOT NULL,
  `subject_id` bigint(20) UNSIGNED NOT NULL,
  `action` varchar(255) NOT NULL,
  `old_value` varchar(255) DEFAULT NULL,
  `new_value` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `changed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `support_tickets`
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

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'user',
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
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `is_active`, `remember_token`, `last_ip_address`, `last_user_agent`, `last_browser`, `last_platform`, `last_device`, `last_login_at`, `login_count`, `created_at`, `updated_at`) VALUES
(1, 'saad', 'admin@admin.com', NULL, '$2y$12$acce67vErQVY/YXgFCmHq.Usnjr3HTakwzfcxTPZObjMXYigAb7My', 'super_admin', 1, NULL, '41.141.234.39', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'Chrome 145.0.0.0', 'Windows 10.0', 'Desktop', '2026-02-27 10:27:41', 3, '2025-12-26 09:47:27', '2026-02-27 10:27:41');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `admin_activity_logs`
--
ALTER TABLE `admin_activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_activity_logs_user_id_index` (`user_id`),
  ADD KEY `admin_activity_logs_action_index` (`action`),
  ADD KEY `admin_activity_logs_subject_type_subject_id_index` (`subject_type`,`subject_id`),
  ADD KEY `admin_activity_logs_created_at_index` (`created_at`);

--
-- Index pour la table `association_parameters`
--
ALTER TABLE `association_parameters`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `association_parameters_key_unique` (`key`),
  ADD KEY `association_parameters_updated_by_foreign` (`updated_by`);

--
-- Index pour la table `bilan_competences`
--
ALTER TABLE `bilan_competences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bilan_competences_candidat_id_foreign` (`candidat_id`);

--
-- Index pour la table `blog_posts`
--
ALTER TABLE `blog_posts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `blog_posts_slug_unique` (`slug`),
  ADD KEY `blog_posts_author_id_foreign` (`author_id`);

--
-- Index pour la table `bmcs`
--
ALTER TABLE `bmcs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bmcs_candidat_id_foreign` (`candidat_id`);

--
-- Index pour la table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Index pour la table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Index pour la table `candidat`
--
ALTER TABLE `candidat`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `candidat_login_unique` (`login`),
  ADD UNIQUE KEY `candidat_email_unique` (`email`),
  ADD UNIQUE KEY `candidat_matricule_unique` (`matricule`),
  ADD KEY `candidat_reviewed_by_foreign` (`reviewed_by`);

--
-- Index pour la table `dynamic_forms`
--
ALTER TABLE `dynamic_forms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dynamic_forms_slug_unique` (`slug`);

--
-- Index pour la table `dynamic_form_answers`
--
ALTER TABLE `dynamic_form_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dynamic_form_answers_dynamic_form_field_id_foreign` (`dynamic_form_field_id`),
  ADD KEY `dfa_submission_field_idx` (`dynamic_form_submission_id`,`field_key`);

--
-- Index pour la table `dynamic_form_fields`
--
ALTER TABLE `dynamic_form_fields`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dynamic_form_fields_dynamic_form_step_id_foreign` (`dynamic_form_step_id`);

--
-- Index pour la table `dynamic_form_steps`
--
ALTER TABLE `dynamic_form_steps`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dynamic_form_steps_dynamic_form_id_step_number_unique` (`dynamic_form_id`,`step_number`);

--
-- Index pour la table `dynamic_form_submissions`
--
ALTER TABLE `dynamic_form_submissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dynamic_form_submissions_unique_per_project` (`dynamic_form_id`,`candidat_id`,`programe_id`),
  ADD KEY `dynamic_form_submissions_candidat_id_foreign` (`candidat_id`),
  ADD KEY `dynamic_form_submissions_reviewed_by_foreign` (`reviewed_by`),
  ADD KEY `dynamic_form_submissions_user_id_foreign` (`user_id`),
  ADD KEY `dynamic_form_submissions_programe_id_index` (`programe_id`);

--
-- Index pour la table `dynamic_form_tables`
--
ALTER TABLE `dynamic_form_tables`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dynamic_form_tables_dynamic_form_step_id_foreign` (`dynamic_form_step_id`);

--
-- Index pour la table `dynamic_form_table_answers`
--
ALTER TABLE `dynamic_form_table_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dynamic_form_table_answers_dynamic_form_table_id_foreign` (`dynamic_form_table_id`),
  ADD KEY `dfta_submission_table_row_idx` (`dynamic_form_submission_id`,`table_key`,`row_index`);

--
-- Index pour la table `dynamic_form_table_columns`
--
ALTER TABLE `dynamic_form_table_columns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dynamic_form_table_columns_dynamic_form_table_id_foreign` (`dynamic_form_table_id`);

--
-- Index pour la table `dynamic_form_table_rows`
--
ALTER TABLE `dynamic_form_table_rows`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dynamic_form_table_rows_dynamic_form_table_id_foreign` (`dynamic_form_table_id`);

--
-- Index pour la table `etude_marches`
--
ALTER TABLE `etude_marches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `etude_marches_candidat_id_status_index` (`candidat_id`,`status`);

--
-- Index pour la table `evaluation_idees`
--
ALTER TABLE `evaluation_idees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `evaluation_idees_candidat_id_foreign` (`candidat_id`);

--
-- Index pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Index pour la table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Index pour la table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Index pour la table `programe_formulaire`
--
ALTER TABLE `programe_formulaire`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `programe_formulaire_programe_id_formulaire_id_unique` (`programe_id`,`formulaire_id`),
  ADD KEY `programe_formulaire_formulaire_id_foreign` (`formulaire_id`);

--
-- Index pour la table `programe_list`
--
ALTER TABLE `programe_list`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `programe_list_slug_unique` (`slug`),
  ADD KEY `programe_list_form_attached_id_foreign` (`form_attached_id`),
  ADD KEY `programe_list_created_by_foreign` (`created_by`);

--
-- Index pour la table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `projects_status_index` (`status`),
  ADD KEY `projects_created_at_index` (`created_at`),
  ADD KEY `projects_reviewed_by_foreign` (`reviewed_by`),
  ADD KEY `projects_form_type_index` (`form_type`),
  ADD KEY `projects_candidat_form_type_index` (`candidat_id`,`form_type`);

--
-- Index pour la table `project_products`
--
ALTER TABLE `project_products`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `rh_employees`
--
ALTER TABLE `rh_employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rh_employees_matricule_unique` (`matricule`),
  ADD KEY `rh_employees_created_by_foreign` (`created_by`);

--
-- Index pour la table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`);

--
-- Index pour la table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_permissions_role_name_module_key_unique` (`role_name`,`module_key`);

--
-- Index pour la table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Index pour la table `submission_histories`
--
ALTER TABLE `submission_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `submission_histories_changed_by_foreign` (`changed_by`),
  ADD KEY `submission_histories_subject_type_subject_id_index` (`subject_type`,`subject_id`);

--
-- Index pour la table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `support_tickets_assigned_to_foreign` (`assigned_to`),
  ADD KEY `support_tickets_status_index` (`status`),
  ADD KEY `support_tickets_priority_index` (`priority`),
  ADD KEY `support_tickets_candidat_id_index` (`candidat_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `admin_activity_logs`
--
ALTER TABLE `admin_activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `association_parameters`
--
ALTER TABLE `association_parameters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `bilan_competences`
--
ALTER TABLE `bilan_competences`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `blog_posts`
--
ALTER TABLE `blog_posts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `bmcs`
--
ALTER TABLE `bmcs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `candidat`
--
ALTER TABLE `candidat`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `dynamic_forms`
--
ALTER TABLE `dynamic_forms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `dynamic_form_answers`
--
ALTER TABLE `dynamic_form_answers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `dynamic_form_fields`
--
ALTER TABLE `dynamic_form_fields`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `dynamic_form_steps`
--
ALTER TABLE `dynamic_form_steps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `dynamic_form_submissions`
--
ALTER TABLE `dynamic_form_submissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `dynamic_form_tables`
--
ALTER TABLE `dynamic_form_tables`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `dynamic_form_table_answers`
--
ALTER TABLE `dynamic_form_table_answers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `dynamic_form_table_columns`
--
ALTER TABLE `dynamic_form_table_columns`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `dynamic_form_table_rows`
--
ALTER TABLE `dynamic_form_table_rows`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `etude_marches`
--
ALTER TABLE `etude_marches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `evaluation_idees`
--
ALTER TABLE `evaluation_idees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT pour la table `programe_formulaire`
--
ALTER TABLE `programe_formulaire`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `programe_list`
--
ALTER TABLE `programe_list`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `project_products`
--
ALTER TABLE `project_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `rh_employees`
--
ALTER TABLE `rh_employees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `role_permissions`
--
ALTER TABLE `role_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `submission_histories`
--
ALTER TABLE `submission_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `admin_activity_logs`
--
ALTER TABLE `admin_activity_logs`
  ADD CONSTRAINT `admin_activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `association_parameters`
--
ALTER TABLE `association_parameters`
  ADD CONSTRAINT `association_parameters_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `bilan_competences`
--
ALTER TABLE `bilan_competences`
  ADD CONSTRAINT `bilan_competences_candidat_id_foreign` FOREIGN KEY (`candidat_id`) REFERENCES `candidat` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `blog_posts`
--
ALTER TABLE `blog_posts`
  ADD CONSTRAINT `blog_posts_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `bmcs`
--
ALTER TABLE `bmcs`
  ADD CONSTRAINT `bmcs_candidat_id_foreign` FOREIGN KEY (`candidat_id`) REFERENCES `candidat` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `candidat`
--
ALTER TABLE `candidat`
  ADD CONSTRAINT `candidat_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `dynamic_form_answers`
--
ALTER TABLE `dynamic_form_answers`
  ADD CONSTRAINT `dynamic_form_answers_dynamic_form_field_id_foreign` FOREIGN KEY (`dynamic_form_field_id`) REFERENCES `dynamic_form_fields` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `dynamic_form_answers_dynamic_form_submission_id_foreign` FOREIGN KEY (`dynamic_form_submission_id`) REFERENCES `dynamic_form_submissions` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `dynamic_form_fields`
--
ALTER TABLE `dynamic_form_fields`
  ADD CONSTRAINT `dynamic_form_fields_dynamic_form_step_id_foreign` FOREIGN KEY (`dynamic_form_step_id`) REFERENCES `dynamic_form_steps` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `dynamic_form_steps`
--
ALTER TABLE `dynamic_form_steps`
  ADD CONSTRAINT `dynamic_form_steps_dynamic_form_id_foreign` FOREIGN KEY (`dynamic_form_id`) REFERENCES `dynamic_forms` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `dynamic_form_submissions`
--
ALTER TABLE `dynamic_form_submissions`
  ADD CONSTRAINT `dynamic_form_submissions_candidat_id_foreign` FOREIGN KEY (`candidat_id`) REFERENCES `candidat` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dynamic_form_submissions_dynamic_form_id_foreign` FOREIGN KEY (`dynamic_form_id`) REFERENCES `dynamic_forms` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dynamic_form_submissions_programe_id_foreign` FOREIGN KEY (`programe_id`) REFERENCES `programe_list` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dynamic_form_submissions_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `dynamic_form_submissions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `dynamic_form_tables`
--
ALTER TABLE `dynamic_form_tables`
  ADD CONSTRAINT `dynamic_form_tables_dynamic_form_step_id_foreign` FOREIGN KEY (`dynamic_form_step_id`) REFERENCES `dynamic_form_steps` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `dynamic_form_table_answers`
--
ALTER TABLE `dynamic_form_table_answers`
  ADD CONSTRAINT `dynamic_form_table_answers_dynamic_form_submission_id_foreign` FOREIGN KEY (`dynamic_form_submission_id`) REFERENCES `dynamic_form_submissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dynamic_form_table_answers_dynamic_form_table_id_foreign` FOREIGN KEY (`dynamic_form_table_id`) REFERENCES `dynamic_form_tables` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `dynamic_form_table_columns`
--
ALTER TABLE `dynamic_form_table_columns`
  ADD CONSTRAINT `dynamic_form_table_columns_dynamic_form_table_id_foreign` FOREIGN KEY (`dynamic_form_table_id`) REFERENCES `dynamic_form_tables` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `dynamic_form_table_rows`
--
ALTER TABLE `dynamic_form_table_rows`
  ADD CONSTRAINT `dynamic_form_table_rows_dynamic_form_table_id_foreign` FOREIGN KEY (`dynamic_form_table_id`) REFERENCES `dynamic_form_tables` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `etude_marches`
--
ALTER TABLE `etude_marches`
  ADD CONSTRAINT `etude_marches_candidat_id_foreign` FOREIGN KEY (`candidat_id`) REFERENCES `candidat` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `evaluation_idees`
--
ALTER TABLE `evaluation_idees`
  ADD CONSTRAINT `evaluation_idees_candidat_id_foreign` FOREIGN KEY (`candidat_id`) REFERENCES `candidat` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `programe_formulaire`
--
ALTER TABLE `programe_formulaire`
  ADD CONSTRAINT `programe_formulaire_formulaire_id_foreign` FOREIGN KEY (`formulaire_id`) REFERENCES `dynamic_forms` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `programe_formulaire_programe_id_foreign` FOREIGN KEY (`programe_id`) REFERENCES `programe_list` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `programe_list`
--
ALTER TABLE `programe_list`
  ADD CONSTRAINT `programe_list_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `programe_list_form_attached_id_foreign` FOREIGN KEY (`form_attached_id`) REFERENCES `dynamic_forms` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_candidat_id_foreign` FOREIGN KEY (`candidat_id`) REFERENCES `candidat` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `projects_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `rh_employees`
--
ALTER TABLE `rh_employees`
  ADD CONSTRAINT `rh_employees_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `submission_histories`
--
ALTER TABLE `submission_histories`
  ADD CONSTRAINT `submission_histories_changed_by_foreign` FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD CONSTRAINT `support_tickets_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `support_tickets_candidat_id_foreign` FOREIGN KEY (`candidat_id`) REFERENCES `candidat` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
