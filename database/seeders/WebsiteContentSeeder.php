<?php

namespace Database\Seeders;

use App\Models\Actualite;
use App\Models\Article;
use App\Models\AssociationParameter;
use App\Models\NavigationItem;
use App\Models\Page;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

/**
 * Seeds realistic French demo content for the public website.
 *
 * Every record is keyed by a stable slug and created with firstOrCreate,
 * so running the seeder several times never duplicates nor overwrites
 * content that already exists in production.
 */
class WebsiteContentSeeder extends Seeder
{
    private const ARTICLE_IMAGES = [
        'uploads/article/article_6a0394bc021ba.png',
        'uploads/article/article_6a03a15d7293a.png',
        'uploads/article/article_6a051b5fde326.png',
        'uploads/article/article_6adcd843afdaf.jpg',
        'assets/web_site/images/act.png',
    ];

    private const NEWS_IMAGES = [
        'uploads/actualite/actualite_6a03a38c1fc2a.png',
        'uploads/actualite/actualite_6a04fa9813a30.png',
        'uploads/actualite/actualite_6a051b4e57592.png',
        'uploads/actualite/actualite_6adcd85ee6d93.jpg',
        'assets/web_site/images/assa_ct.png',
    ];

    private const PROJECT_IMAGES = [
        'uploads/project/project_6adcf18930a8e.jpg',
        'assets/web_site/images/act.png',
        'assets/web_site/images/assa_ct.png',
    ];

    public function run(): void
    {
        $authorId = User::query()->orderBy('id')->value('id');

        if (! $authorId) {
            $this->command?->warn('WebsiteContentSeeder: no user found, skipping content seeding.');

            return;
        }

        $this->seedArticles($authorId);
        $this->seedActualites($authorId);
        $this->seedProjects($authorId);
        $this->seedPages();
        $this->seedNavigation();
        $this->seedSettings();
    }

    private function seedArticles(int $authorId): void
    {
        $articles = [
            [
                'slug' => 'rehabilitation-medinas-ville-durable',
                'title' => 'La réhabilitation des médinas : un levier pour la ville durable',
                'excerpt' => 'Entre mémoire collective et exigences écologiques, la rénovation des médinas offre un modèle de développement urbain respectueux des habitants.',
                'category' => 'urbanisme',
                'tags' => ['patrimoine', 'médina', 'durabilité'],
                'body' => '<p>Les médinas marocaines ne sont pas des vestiges : elles sont des quartiers vivants, habités et traversés par des usages que la ville moderne a parfois oubliés. Leur réhabilitation représente aujourd’hui une opportunité unique de concilier patrimoine et transition écologique.</p><h2>Réhabiliter plutôt que reconstruire</h2><p>À Casablanca comme à Fès, les expériences de réhabilitation montrent qu’une intervention fine — confortement des structures, reprise des réseaux, végétalisation des impasses — coûte souvent moins cher qu’une opération de table rase.</p><ul><li>Préserver la trame urbaine existante ;</li><li>Améliorer l’accès à l’eau et à l’assainissement ;</li><li>Favoriser les matériaux locaux et les savoir-faire artisanaux.</li></ul><blockquote>La ville durable ne se construit pas contre son histoire, mais avec elle.</blockquote>',
            ],
            [
                'slug' => 'casablanca-mobilite-douce',
                'title' => 'Casablanca face au défi de la mobilité douce',
                'excerpt' => 'Le vélo et la marche progressent, mais les aménagements restent fragiles. Comment faire de la mobilité active une vraie alternative ?',
                'category' => 'mobilite',
                'tags' => ['vélo', 'transport', 'sécurité routière'],
                'body' => '<p>La mobilité douce n’est pas une mode : c’est une réponse concrète aux embouteillages, à la pollution de l’air et au coût croissant des déplacements. Pourtant, à Casablanca, pédaler reste un acte de courage.</p><h2>Trois conditions pour réussir</h2><p>Les expériences internationales convergent : sans continuité des itinéraires, sans stationnement sécurisé et sans respect mutuel entre usagers, les aménagements cyclables restent symboliques.</p><ul><li>Un réseau continu et lisible ;</li><li>Des parkings vélo aux abords des stations de tramway ;</li><li>Une campagne durable de sensibilisation des automobilistes.</li></ul><p>Notre association plaide pour une première liaison structurante reliant les quartiers périphériques au centre-ville.</p>',
            ],
            [
                'slug' => 'ecoles-de-quartier-investir-avenir',
                'title' => 'Écoles de quartier : investir dans l’avenir',
                'excerpt' => 'Le bâti scolaire conditionne la réussite des élèves. Petit tour d’horizon des priorités pour des établissements dignes et accueillants.',
                'category' => 'education',
                'tags' => ['école', 'jeunesse', 'égalité'],
                'body' => '<p>Une école dégradée, ce sont des élèves qui apprennent dans l’inconfort et des enseignants qui travaillent sans moyens. La rénovation du bâti scolaire est donc une question d’égalité des chances.</p><h2>Des priorités claires</h2><ul><li>Sanitaires séparés et fonctionnels ;</li><li>Cour de récréation ombragée et sécurisée ;</li><li>Bibliothèque et salle informatique accessibles.</li></ul><p>Nous accompagnons les associations de parents d’élèves dans le diagnostic de leurs établissements et la préparation de dossiers de financement.</p>',
            ],
            [
                'slug' => 'economie-sociale-cooperatives-feminines',
                'title' => 'Économie sociale et solidaire : les coopératives féminines',
                'excerpt' => 'Dans les quartiers populaires, les coopératives offrent aux femmes un revenu, une formation et un réseau de solidarité.',
                'category' => 'solidarite',
                'tags' => ['femmes', 'coopérative', 'emploi'],
                'body' => '<p>Les coopératives féminines de couture, de transformation alimentaire ou d’artisanat transforment des savoir-faire domestiques en activités économiques viables.</p><h2>Accompagner plutôt qu’assister</h2><p>Le rôle de l’association est de sécuriser les premiers pas : formalités administratives, gestion de trésorerie, accès aux marchés et communication.</p><blockquote>Un revenu régulier change non seulement la vie de la femme, mais aussi celle de toute sa famille.</blockquote><p>Douze coopératives ont déjà bénéficié de notre programme d’accompagnement à Hay Mohammadi.</p>',
            ],
            [
                'slug' => 'art-espace-public-casablanca',
                'title' => 'Quand l’art investit l’espace public',
                'excerpt' => 'Fresques, installations et performances transforment le regard des habitants sur leur quartier et renforcent le lien social.',
                'category' => 'culture',
                'tags' => ['art', 'espace public', 'quartier'],
                'body' => '<p>Une fresque sur un mur aveugle, une installation sonore sur une place : l’art urbain modifie la perception d’un lieu et invite à le fréquenter autrement.</p><h2>Une démarche participative</h2><p>Nous travaillons avec les artistes et les habitants pour que chaque œuvre raconte réellement le quartier, sans imposer un discours venu d’ailleurs.</p><ul><li>Ateliers de co-création avec les jeunes ;</li><li>Chantiers ouverts aux bénévoles ;</li><li>Programme culturel de restitution.</li></ul>',
            ],
            [
                'slug' => 'gestion-dechets-quartier-zero-dechet',
                'title' => 'Gestion des déchets : vers un quartier zéro déchet',
                'excerpt' => 'Tri, compostage et réemploi : les habitants peuvent agir dès aujourd’hui pour réduire leurs déchets.',
                'category' => 'environnement',
                'tags' => ['déchets', 'compostage', 'écologie'],
                'body' => '<p>La gestion des déchets est l’un des défis les plus visibles de la ville. Pourtant, des solutions simples existent à l’échelle du quartier.</p><h2>Agir à la source</h2><ul><li>Installer des points de tri accessibles ;</li><li>Organiser des ateliers de compostage collectif ;</li><li>Encourager la réparation et le réemploi.</li></ul><p>Notre projet de collecte sélective a permis de détourner plusieurs tonnes de déchets organiques de la décharge.</p>',
            ],
            [
                'slug' => 'jeunesse-emploi-nouveaux-metiers-ville',
                'title' => 'Jeunesse et emploi : les nouveaux métiers de la ville',
                'excerpt' => 'Data, énergie, médiation numérique : des métiers émergent et exigent une formation adaptée aux réalités locales.',
                'category' => 'jeunesse',
                'tags' => ['formation', 'emploi', 'numérique'],
                'body' => '<p>Les jeunes des quartiers populaires ne manquent pas de talents, mais trop souvent d’accès aux formations qui correspondent aux emplois de demain.</p><h2>Des parcours courts et qualifiants</h2><p>Nous privilégions des formations de trois à six mois, co-construites avec des entreprises locales et débouchant sur un stage rémunéré.</p><ul><li>Métiers de la transition énergétique ;</li><li>Médiation numérique et assistance ;</li><li>Maintenance des équipements urbains.</li></ul>',
            ],
            [
                'slug' => 'sante-de-proximite-casablanca',
                'title' => 'Santé de proximité : rapprocher les soins des habitants',
                'excerpt' => 'Consultations, dépistage et prévention : la santé communautaire constitue le premier rempart contre les inégalités.',
                'category' => 'sante',
                'tags' => ['santé', 'prévention', 'accès aux soins'],
                'body' => '<p>Dans certains quartiers, l’hôpital le plus proche est à plus d’une heure de transport. La santé de proximité est donc une nécessité.</p><h2>Une réponse de terrain</h2><p>Nos caravanes médicales proposent des consultations généralistes, un dépistage du diabète et de l’hypertension, ainsi qu’un accompagnement administratif.</p><blockquote>Prévenir coûte toujours moins que guérir dans l’urgence.</blockquote>',
            ],
            [
                'slug' => 'habitat-inclusif-ville-pour-tous',
                'title' => 'Habitat inclusif : penser la ville pour tous',
                'excerpt' => 'Accessibilité, mixité et services de proximité : repenser le logement pour qu’il accompagne tous les âges de la vie.',
                'category' => 'inclusion',
                'tags' => ['habitat', 'accessibilité', 'inclusion'],
                'body' => '<p>La ville inclusive ne se limite pas à installer des rampes d’accès. Elle suppose de penser les logements, les espaces publics et les services pour des parcours de vie variés.</p><h2>Trois principes</h2><ul><li>La mixité intergénérationnelle ;</li><li>La proximité des services essentiels ;</li><li>L’accessibilité universelle.</li></ul><p>Nous plaidons pour l’intégration de ces critères dans les futurs programmes de logement social.</p>',
            ],
            [
                'slug' => 'eau-dans-la-ville-sobriete-innovation',
                'title' => 'L’eau dans la ville : sobriété et innovation',
                'excerpt' => 'Récupération des eaux de pluie, gestion des fuites et espaces perméables : l’eau doit redevenir une ressource précieuse et partagée.',
                'category' => 'environnement',
                'tags' => ['eau', 'sobriété', 'urbanisme'],
                'body' => '<p>Le stress hydrique menace durablement les villes marocaines. La gestion de l’eau ne peut plus être uniquement technique : elle devient un projet de société.</p><h2>Des gestes qui comptent</h2><ul><li>Récupérer les eaux de pluie pour l’arrosage ;</li><li>Désimperméabiliser les cours d’école ;</li><li>Réduire les pertes des réseaux d’eau potable.</li></ul>',
            ],
            [
                'slug' => 'places-publiques-democratie-locale',
                'title' => 'Les places publiques comme lieux de démocratie locale',
                'excerpt' => 'Un banc, une ombre, un espace libre : les places publiques sont les premiers laboratoires de la vie citoyenne.',
                'category' => 'urbanisme',
                'tags' => ['espace public', 'citoyenneté', 'urbanisme'],
                'body' => '<p>La place publique est un révélateur : on y observe la mixité sociale, la place des femmes, l’accès des enfants et l’expression politique.</p><h2>Concevoir avec les habitants</h2><p>Les démarches participatives débouchent souvent sur des aménagements plus sobres et mieux appropriés que les plans dessinés hors-sol.</p><ul><li>Ateliers de diagnostic ;</li><li>Maquettes collaboratives ;</li><li>Chantiers participatifs.</li></ul>',
            ],
            [
                'slug' => 'tourisme-urbain-responsable',
                'title' => 'Tourisme urbain responsable : opportunité ou menace ?',
                'excerpt' => 'Le succès touristique peut fragiliser les quartiers. Comment organiser un accueil qui profite d’abord aux habitants ?',
                'category' => 'culture',
                'tags' => ['tourisme', 'patrimoine', 'économie locale'],
                'body' => '<p>Le tourisme urbain génère des revenus et valorise le patrimoine, mais il peut aussi accélérer la hausse des loyers et la standardisation des commerces.</p><h2>Une régulation nécessaire</h2><p>Nous proposons de soutenir les circuits de proximité, les guides du quartier et les hébergements intégrés au tissu local.</p><blockquote>Un quartier accueillant est d’abord un quartier habitable.</blockquote>',
            ],
        ];

        foreach ($articles as $index => $article) {
            $publishedAt = Carbon::now()->subDays(6 + $index * 5)->setTime(9 + ($index % 6), 30);

            Article::firstOrCreate(
                ['slug' => $article['slug']],
                [
                    'title' => $article['title'],
                    'title_ar' => null,
                    'excerpt' => $article['excerpt'],
                    'content' => $article['body'].'<p>Publié par l’équipe d’'.__('ui.brand_name').' dans le cadre de son programme d’accompagnement des quartiers.</p>',
                    'image' => self::ARTICLE_IMAGES[$index % count(self::ARTICLE_IMAGES)],
                    'category' => $article['category'],
                    'tags' => $article['tags'],
                    'is_published' => true,
                    'published_at' => $publishedAt,
                    'author_id' => $authorId,
                    'views_count' => 40 + $index * 7,
                ]
            );
        }
    }

    private function seedActualites(int $authorId): void
    {
        $news = [
            [
                'slug' => 'inauguration-espace-citoyen-hay-mohammadi',
                'title' => 'Initiative Urbaine inaugure son nouvel espace citoyen à Hay Mohammadi',
                'excerpt' => 'Un lieu ouvert à tous pour se former, s’informer et monter des projets collectifs a ouvert ses portes en présence des habitants et des partenaires.',
                'category' => 'evenement',
                'tags' => ['inauguration', 'quartier', 'citoyenneté'],
                'body' => '<p>Samedi matin, l’espace citoyen a accueilli ses premiers visiteurs dans une ambiance chaleureuse. Ateliers, permanence d’accompagnement et espace de coworking y sont désormais accessibles gratuitement.</p><h2>Un lieu pensé avec les habitants</h2><p>L’aménagement a été co-construit lors de trois ateliers participatifs. Les habitants ont notamment choisi la bibliothèque de rue et l’espace dédié aux enfants.</p><p>Merci à nos partenaires et à toutes les personnes qui ont contribué à ce projet.</p>',
            ],
            [
                'slug' => '300-jeunes-formes-metiers-numerique',
                'title' => 'Plus de 300 jeunes formés aux métiers du numérique cette année',
                'excerpt' => 'Le programme de formation court a permis à 312 jeunes d’acquérir des compétences recherchées par les entreprises locales.',
                'category' => 'jeunesse',
                'tags' => ['formation', 'numérique', 'emploi'],
                'body' => '<p>Bilan positif pour le programme « Compétences numériques pour tous » : 312 jeunes ont suivi une formation de trois mois, dont 68 % ont trouvé un stage ou un emploi dans les six mois.</p><h2>Une nouvelle promotion dès janvier</h2><p>Les inscriptions pour la prochaine promotion ouvriront le 15 janvier. Les candidats sont invités à préparer un projet personnel qui servira de fil rouge à leur formation.</p>',
            ],
            [
                'slug' => 'campagne-reboisement-quartiers',
                'title' => 'Lancement de la campagne de reboisement des quartiers',
                'excerpt' => 'Avec les écoles et les associations de quartier, 1 200 arbres seront plantés cette saison.',
                'category' => 'environnement',
                'tags' => ['reboisement', 'environnement', 'bénévolat'],
                'body' => '<p>La campagne annuelle de reboisement démarre ce mois-ci. Essences adaptées au climat, fosses préparées avec les services municipaux et suivi d’arrosage assuré par les habitants : tout est pensé pour maximiser la reprise des plants.</p><h2>Chaque arbre compte</h2><p>Les bénévoles peuvent s’inscrire auprès de leur association de quartier. Des ateliers de plantation sont prévus chaque samedi.</p>',
            ],
            [
                'slug' => 'atelier-participatif-parc-demain',
                'title' => 'Atelier participatif : imaginer ensemble le parc de demain',
                'excerpt' => 'Les habitants sont invités à dessiner les usages du futur parc de la rue Ibn Sina lors d’un atelier ouvert à tous.',
                'category' => 'urbanisme',
                'tags' => ['participation', 'parc', 'urbanisme'],
                'body' => '<p>Quels équipements, quelles essences, quels horaires ? L’atelier participatif permettra de recueillir les attentes des différents publics, des enfants aux personnes âgées.</p><h2>Comment participer</h2><p>Rendez-vous samedi à 10 h à la maison de quartier. L’atelier est gratuit et ouvert sans inscription.</p>',
            ],
            [
                'slug' => 'kits-scolaires-500-familles',
                'title' => 'Distribution de kits scolaires à 500 familles',
                'excerpt' => 'Cartables, cahiers et fournitures : la rentrée a été facilitée pour 500 familles du quartier.',
                'category' => 'education',
                'tags' => ['rentrée', 'solidarité', 'éducation'],
                'body' => '<p>Grâce à la mobilisation de nos partenaires et de nos bénévoles, 500 kits scolaires complets ont été distribués avant la rentrée.</p><h2>Un soutien qui ne s’arrête pas aux fournitures</h2><p>L’association propose également un accompagnement à la scolarité tout au long de l’année, avec des séances de soutien encadrées par des bénévoles.</p>',
            ],
            [
                'slug' => 'partenariat-commune-casablanca',
                'title' => 'Signature d’un partenariat avec la commune de Casablanca',
                'excerpt' => 'Ce partenariat renforce notre action dans les domaines de l’éducation, de la santé de proximité et de la participation citoyenne.',
                'category' => 'solidarite',
                'tags' => ['partenariat', 'institution', 'coopération'],
                'body' => '<p>La convention signée fixe un cadre de coopération pour trois ans. Elle prévoit la mise à disposition de locaux et un soutien aux programmes de terrain.</p><h2>Une étape importante</h2><p>Ce partenariat reconnaît le travail mené depuis plusieurs années par les équipes et les bénévoles de l’association.</p>',
            ],
            [
                'slug' => 'journee-portes-ouvertes-programmes',
                'title' => 'Journée portes ouvertes : découvrez nos programmes',
                'excerpt' => 'Formations, accompagnement des femmes, soutien scolaire : venez rencontrer nos équipes et vous inscrire.',
                'category' => 'evenement',
                'tags' => ['portes ouvertes', 'programmes', 'information'],
                'body' => '<p>Toute la journée, les responsables de programmes présenteront leurs activités et répondront aux questions. Des ateliers de démonstration sont prévus pour les jeunes et les familles.</p><h2>Programme</h2><ul><li>9 h — Accueil et présentation générale ;</li><li>11 h — Atelier numérique pour les jeunes ;</li><li>14 h — Rencontre avec les coopératives féminines ;</li><li>16 h — Échanges avec les bénévoles.</li></ul>',
            ],
            [
                'slug' => 'permanence-accompagnement-administratif',
                'title' => 'Nouvelle permanence d’accompagnement administratif',
                'excerpt' => 'Un temps d’aide gratuit pour comprendre ses démarches, compléter un dossier ou contacter un service public.',
                'category' => 'solidarite',
                'tags' => ['accompagnement', 'démarches', 'services'],
                'body' => '<p>Chaque mardi de 14 h à 17 h, une permanence d’accompagnement est ouverte aux habitants. Elle est animée par des bénévoles formés et un travailleur social.</p><h2>Quels dossiers ?</h2><p>Aide sociale, logement, retraite, assurance maladie : l’accompagnement vise à rendre les démarches plus lisibles et accessibles.</p>',
            ],
            [
                'slug' => 'retour-caravane-medicale',
                'title' => 'Retour sur la caravane médicale de quartier',
                'excerpt' => 'Plus de 400 consultations ont été réalisées lors de la dernière caravane médicale.',
                'category' => 'sante',
                'tags' => ['santé', 'caravane', 'prévention'],
                'body' => '<p>Médecins généralistes, infirmiers et spécialistes se sont relayés pour proposer des consultations gratuites et un dépistage du diabète et de l’hypertension.</p><h2>Prévention et suivi</h2><p>Les personnes identifiées comme à risque ont été orientées vers les centres de santé partenaires pour un suivi régulier.</p>',
            ],
            [
                'slug' => 'appel-benevoles-rentree',
                'title' => 'Appel à bénévoles pour la rentrée',
                'excerpt' => 'Soutien scolaire, alphabétisation, accueil : plusieurs missions sont ouvertes aux bénévoles dès septembre.',
                'category' => 'inclusion',
                'tags' => ['bénévolat', 'engagement', 'rentrée'],
                'body' => '<p>Vous avez quelques heures par semaine à offrir ? Rejoignez l’équipe des bénévoles pour accompagner les enfants, les jeunes ou les adultes en apprentissage.</p><h2>Une réunion d’information</h2><p>Une réunion est organisée en fin de mois pour présenter les missions, les formations et l’organisation du bénévolat au sein de l’association.</p>',
            ],
            [
                'slug' => 'laureats-concours-innovation-sociale',
                'title' => 'Nos lauréats du concours d’innovation sociale',
                'excerpt' => 'Trois projets portés par des jeunes du quartier ont été récompensés pour leur impact social.',
                'category' => 'jeunesse',
                'tags' => ['innovation', 'jeunesse', 'concours'],
                'body' => '<p>Le jury a salué la qualité et la faisabilité des projets présentés. Les lauréats bénéficieront d’un accompagnement technique et d’une bourse de démarrage.</p><h2>Les projets primés</h2><ul><li>Une application de partage d’outils entre artisans ;</li><li>Un atelier de réparation de vélos ;</li><li>Une ressourcerie de quartier.</li></ul>',
            ],
            [
                'slug' => 'ouverture-bibliotheque-quartier',
                'title' => 'Ouverture de la bibliothèque de quartier',
                'excerpt' => 'Un nouvel espace de lecture ouvre ses portes avec plus de 2 000 ouvrages et des activités pour les enfants.',
                'category' => 'culture',
                'tags' => ['bibliothèque', 'culture', 'lecture'],
                'body' => '<p>Romans, albums jeunesse, ouvrages pratiques et bandes dessinées : la bibliothèque accueille les habitants du lundi au samedi.</p><h2>Des animations régulières</h2><p>Des séances de lecture pour enfants et des ateliers d’écriture seront proposés chaque semaine, en partenariat avec les écoles du quartier.</p>',
            ],
        ];

        foreach ($news as $index => $item) {
            $publishedAt = Carbon::now()->subDays(2 + $index * 4)->setTime(10 + ($index % 5), 15);

            Actualite::firstOrCreate(
                ['slug' => $item['slug']],
                [
                    'title' => $item['title'],
                    'title_ar' => null,
                    'excerpt' => $item['excerpt'],
                    'excerpt_ar' => null,
                    'content' => $item['body'],
                    'content_ar' => null,
                    'image' => self::NEWS_IMAGES[$index % count(self::NEWS_IMAGES)],
                    'category' => $item['category'],
                    'tags' => $item['tags'],
                    'is_published' => true,
                    'published_at' => $publishedAt,
                    'author_id' => $authorId,
                    'views_count' => 25 + $index * 5,
                ]
            );
        }
    }

    private function seedProjects(int $authorId): void
    {
        $projects = [
            [
                'slug' => 'maison-des-jeunes-et-de-la-culture',
                'title' => 'Maison des Jeunes et de la Culture',
                'short' => 'Unéquipement de proximité pour la formation, la culture et l’engagement des jeunes.',
                'status' => 'in_progress',
                'location' => 'Hay Mohammadi, Casablanca',
                'partner' => 'Commune de Casablanca',
                'tags' => ['jeunesse', 'culture', 'équipement'],
                'body' => '<p>La Maison des Jeunes et de la Culture sera un espace de 600 m² dédié aux jeunes du quartier : salle de formation, studio d’enregistrement, espace de coworking et salle polyvalente.</p><h2>État d’avancement</h2><ul><li>Études architecturales terminées ;</li><li>Travaux de gros œuvre en cours ;</li><li>Ouverture prévue en 2027.</li></ul><p>Le chantier est suivi par un comité composé de jeunes du quartier et de nos équipes.</p>',
            ],
            [
                'slug' => 'reseau-pistes-cyclables-quartier',
                'title' => 'Réseau de pistes cyclables du quartier',
                'short' => 'Une première liaison cyclable sécurisée pour relier les quartiers périphériques au centre.',
                'status' => 'planned',
                'location' => 'Casablanca',
                'partner' => 'Collectif Vélo Casablanca',
                'tags' => ['mobilité', 'vélo', 'aménagement'],
                'body' => '<p>Le projet prévoit 7 km de pistes cyclables protégées, des stationnements sécurisés et une signalétique lisible.</p><h2>Objectifs</h2><ul><li>Réduire les déplacements en voiture ;</li><li>Améliorer la sécurité des cyclistes ;</li><li>Relier les écoles et les équipements publics.</li></ul>',
            ],
            [
                'slug' => 'renovation-ecole-al-farabi',
                'title' => 'Rénovation de l’école Al Farabi',
                'short' => 'Réhabilitation complète des sanitaires, des classes et de la cour de récréation.',
                'status' => 'completed',
                'location' => 'Hay Mohammadi, Casablanca',
                'partner' => 'Association des parents d’élèves',
                'tags' => ['éducation', 'école', 'rénovation'],
                'body' => '<p>Après six mois de travaux, l’école Al Farabi dispose désormais de sanitaires rénovés, de classes repeintes et d’une cour ombragée.</p><h2>Résultats</h2><ul><li>420 élèves bénéficiaires ;</li><li>Une bibliothèque de classe dans chaque niveau ;</li><li>Une cour végétalisée et sécurisée.</li></ul>',
            ],
            [
                'slug' => 'jardin-partage-hay-mohammadi',
                'title' => 'Jardin partagé de Hay Mohammadi',
                'short' => 'Un terrain vacant transformé en jardin collectif pédagogique et productif.',
                'status' => 'in_progress',
                'location' => 'Hay Mohammadi, Casablanca',
                'partner' => 'Réseau des jardins solidaires',
                'tags' => ['environnement', 'alimentation', 'jardin'],
                'body' => '<p>Le jardin partagé accueille déjà plusieurs parcelles cultivées par des familles du quartier. Un espace pédagogique est réservé aux écoles.</p><h2>Ce qui a été réalisé</h2><ul><li>Préparation du sol et installation de l’irrigation goutte-à-goutte ;</li><li>Construction d’un composteur collectif ;</li><li>Plantation des premiers arbres fruitiers.</li></ul>',
            ],
            [
                'slug' => 'alphabetisation-adultes',
                'title' => 'Programme d’alphabétisation des adultes',
                'short' => 'Des cours du soir pour permettre aux adultes de lire, écrire et gagner en autonomie.',
                'status' => 'completed',
                'location' => 'Casablanca',
                'partner' => 'Direction provinciale de l’éducation',
                'tags' => ['éducation', 'adultes', 'alphabétisation'],
                'body' => '<p>Quatre-vingt-dix apprenants ont suivi le programme pendant neuf mois, avec un taux de réussite de 82 % à l’évaluation finale.</p><h2>Une méthode adaptée</h2><p>Les cours s’appuient sur des situations concrètes de la vie quotidienne : lire une ordonnance, remplir un formulaire, comprendre un contrat.</p>',
            ],
            [
                'slug' => 'centre-sante-communautaire',
                'title' => 'Centre de santé communautaire',
                'short' => 'Un centre de proximité pour les consultations généralistes et la prévention.',
                'status' => 'planned',
                'location' => 'Sidi Bernoussi, Casablanca',
                'partner' => 'Réseau des centres de santé',
                'tags' => ['santé', 'prévention', 'équipement'],
                'body' => '<p>Le centre proposera des consultations généralistes, un suivi des maladies chroniques et des ateliers de prévention.</p><h2>Prochaines étapes</h2><ul><li>Finalisation du dossier de financement ;</li><li>Identification du local ;</li><li>Recrutement de l’équipe soignante.</li></ul>',
            ],
            [
                'slug' => 'marche-solidaire-productrices',
                'title' => 'Marché solidaire des productrices',
                'short' => 'Un marché mensuel pour valoriser les produits des coopératives féminines.',
                'status' => 'in_progress',
                'location' => 'Casablanca',
                'partner' => 'Coopératives locales',
                'tags' => ['femmes', 'économie', 'solidarité'],
                'body' => '<p>Chaque premier dimanche du mois, les coopératives féminines installent leurs étals sur la place du quartier : produits alimentaires, cosmétiques naturels et artisanat.</p><h2>Un impact concret</h2><p>Le marché permet aux productrices de vendre directement, sans intermédiaire, et de se faire connaître auprès des habitants.</p>',
            ],
            [
                'slug' => 'bibliotheque-de-rue',
                'title' => 'Bibliothèque de rue',
                'short' => 'Des livres accessibles gratuitement dans les espaces publics du quartier.',
                'status' => 'completed',
                'location' => 'Hay Mohammadi, Casablanca',
                'partner' => 'Écoles du quartier',
                'tags' => ['culture', 'lecture', 'enfance'],
                'body' => '<p>Des petites bibliothèques en libre accès ont été installées dans trois espaces publics. Elles fonctionnent grâce aux dons de livres et à l’engagement de bénévoles.</p><h2>Bilan</h2><p>Plus de 1 500 ouvrages ont été mis à disposition et des séances de lecture sont organisées chaque semaine.</p>',
            ],
            [
                'slug' => 'formation-jeunes-metiers-verts',
                'title' => 'Formation des jeunes aux métiers verts',
                'short' => 'Une formation qualifiante aux métiers de l’énergie et de la gestion des déchets.',
                'status' => 'in_progress',
                'location' => 'Casablanca',
                'partner' => 'Entreprises de l’économie verte',
                'tags' => ['jeunesse', 'formation', 'environnement'],
                'body' => '<p>La formation combine cours techniques, ateliers pratiques et immersion en entreprise. Elle s’adresse aux jeunes de 18 à 30 ans sans qualification.</p><h2>Débouchés</h2><ul><li>Technicien en installation solaire ;</li><li>Agent de collecte et de tri ;</li><li>Animateur de sensibilisation environnementale.</li></ul>',
            ],
            [
                'slug' => 'amenagement-place-publique',
                'title' => 'Aménagement de la place publique',
                'short' => 'Une place réaménagée avec les habitants pour favoriser les rencontres et les activités de plein air.',
                'status' => 'planned',
                'location' => 'Hay Mohammadi, Casablanca',
                'partner' => 'Services municipaux',
                'tags' => ['espace public', 'urbanisme', 'participation'],
                'body' => '<p>Le projet prévoit des espaces ombragés, des bancs, un terrain multisports et une aire de jeux pour les enfants.</p><h2>Une conception participative</h2><p>Trois ateliers ont permis de recueillir les besoins des habitants et de prioriser les aménagements selon le budget disponible.</p>',
            ],
            [
                'slug' => 'collecte-valorisation-dechets',
                'title' => 'Collecte et valorisation des déchets',
                'short' => 'Une filière locale de tri et de compostage pour réduire les déchets du quartier.',
                'status' => 'in_progress',
                'location' => 'Casablanca',
                'partner' => 'Coopérative de recyclage',
                'tags' => ['environnement', 'déchets', 'économie circulaire'],
                'body' => '<p>Le projet organise la collecte sélective auprès des commerces et des ménages volontaires, puis valorise les déchets organiques en compost.</p><h2>Résultats attendus</h2><ul><li>Réduction de 30 % des déchets envoyés à la décharge ;</li><li>Création de six emplois locaux ;</li><li>Production de compost pour les jardins du quartier.</li></ul>',
            ],
            [
                'slug' => 'accompagnement-femmes-entrepreneures',
                'title' => 'Accompagnement des femmes entrepreneures',
                'short' => 'Un parcours de formation et de mentorat pour structurer et développer les activités portées par des femmes.',
                'status' => 'completed',
                'location' => 'Casablanca',
                'partner' => 'Réseau des femmes entrepreneures',
                'tags' => ['femmes', 'entrepreneuriat', 'formation'],
                'body' => '<p>Quarante femmes ont suivi un parcours de six mois : gestion, marketing, accès au financement et mentorat individuel.</p><h2>Des résultats durables</h2><p>Vingt-huit activités ont été formalisées et plusieurs participantes ont obtenu leur premier financement bancaire.</p>',
            ],
        ];

        foreach ($projects as $index => $project) {
            $publishedAt = Carbon::now()->subDays(10 + $index * 6)->setTime(11, 0);

            Project::firstOrCreate(
                ['slug' => $project['slug']],
                [
                    'title' => ['fr' => $project['title'], 'ar' => null],
                    'description' => ['fr' => $project['body'], 'ar' => null],
                    'short_description' => ['fr' => $project['short'], 'ar' => null],
                    'featured_image' => self::PROJECT_IMAGES[$index % count(self::PROJECT_IMAGES)],
                    'gallery' => null,
                    'start_date' => $publishedAt->copy()->toDateString(),
                    'end_date' => $project['status'] === 'completed' ? $publishedAt->copy()->addMonths(6)->toDateString() : null,
                    'status' => $project['status'],
                    'location' => $project['location'],
                    'partner' => $project['partner'],
                    'tags' => $project['tags'],
                    'sort_order' => $index,
                    'is_published' => true,
                    'is_featured' => $index < 3,
                    'published_at' => $publishedAt,
                    'author_id' => $authorId,
                    'views_count' => 30 + $index * 9,
                ]
            );
        }
    }

    private function seedPages(): void
    {
        $pages = [
            [
                'slug' => 'projects',
                'title' => ['fr' => 'Nos projets', 'ar' => null],
                'excerpt' => ['fr' => 'Des initiatives concrètes au service des habitants et des quartiers de Casablanca.', 'ar' => null],
                'content' => ['fr' => null, 'ar' => null],
                'sort_order' => 1,
            ],
            [
                'slug' => 'history',
                'title' => ['fr' => 'Notre histoire', 'ar' => null],
                'excerpt' => ['fr' => 'Depuis 2009, au plus près des habitants.', 'ar' => null],
                'content' => ['fr' => '<p>Depuis sa création, '.e(__('ui.brand_name')).' agit aux côtés des habitants de Casablanca pour construire une ville plus inclusive, plus solidaire et plus durable. Chaque programme est né d’une rencontre et d’une écoute attentive des besoins du quartier.</p><p>Aujourd’hui, nos équipes et nos bénévoles accompagnent les familles, les jeunes et les porteurs de projets à travers des actions concrètes d’éducation, de santé, de culture et d’aménagement urbain.</p>', 'ar' => null],
                'sort_order' => 2,
            ],
        ];

        foreach ($pages as $page) {
            Page::firstOrCreate(
                ['slug' => $page['slug']],
                [
                    'title' => $page['title'],
                    'excerpt' => $page['excerpt'],
                    'content' => $page['content'],
                    'hero_image' => null,
                    'is_published' => true,
                    'published_at' => Carbon::now()->subDays(30),
                    'sort_order' => $page['sort_order'],
                ]
            );
        }
    }

    private function seedNavigation(): void
    {
        if (NavigationItem::query()->whereNull('parent_id')->exists()) {
            return;
        }

        $links = [
            ['label' => 'Accueil', 'url' => '/', 'sort_order' => 1],
            ['label' => 'Actualités', 'url' => '/actualite', 'sort_order' => 2],
            ['label' => 'Articles', 'url' => '/articles', 'sort_order' => 3],
            ['label' => 'Projets', 'url' => '/projects', 'sort_order' => 4],
            ['label' => 'Histoire', 'url' => '/history', 'sort_order' => 5],
            ['label' => 'Contact', 'url' => '/contact', 'sort_order' => 6],
        ];

        foreach ($links as $link) {
            NavigationItem::firstOrCreate(
                ['url' => $link['url'], 'parent_id' => null],
                [
                    'label' => $link['label'],
                    'label_ar' => null,
                    'type' => NavigationItem::TYPE_EXTERNAL_LINK,
                    'sort_order' => $link['sort_order'],
                    'is_active' => true,
                    'is_visible' => true,
                ]
            );
        }
    }

    private function seedSettings(): void
    {
        $settings = [
            'association_name' => 'Initiative Urbaine',
            'association_acronym' => 'IU',
            'association_description' => 'Association engagée aux côtés des habitants de Casablanca pour une ville inclusive, solidaire et durable.',
            'association_address' => 'Hay Mohammadi',
            'association_city' => 'Casablanca',
            'contact_email' => 'contact@initiative-urbaine.ma',
            'contact_phone' => '+212 522 00 00 00',
            'contact_whatsapp' => '+212 600 00 00 00',
            'contact_website' => 'https://www.initiative-urbaine.ma',
            'footer_description' => 'Nous agissons chaque jour aux côtés des habitants pour construire une ville plus inclusive, plus solidaire et plus durable.',
            'footer_copyright' => 'Tous droits réservés. Association Initiative Urbaine.',
        ];

        foreach ($settings as $key => $value) {
            $parameter = AssociationParameter::firstOrNew(['key' => $key]);

            if (! $parameter->exists) {
                $parameter->fill([
                    'category' => str_starts_with($key, 'contact_') ? 'contact' : 'general',
                    'label' => $parameter->label ?: $key,
                    'type' => in_array($key, ['contact_email'], true) ? 'email' : (in_array($key, ['contact_website'], true) ? 'url' : 'text'),
                    'sort_order' => 50,
                ]);
            }

            if (blank($parameter->value)) {
                $parameter->value = $value;
            }

            $parameter->save();
        }
    }
}
