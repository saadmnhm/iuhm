<?php

namespace App\Support\Seo;

use Illuminate\Support\Str;

/**
 * Lightweight, framework-independent SEO analyser used by the admin editor.
 *
 * It returns a list of checks with a status (good / improvement / error),
 * a recommendation for each one and an overall score from 0 to 100.
 */
class SeoAnalyzer
{
    public const STATUS_GOOD = 'good';

    public const STATUS_IMPROVEMENT = 'improvement';

    public const STATUS_ERROR = 'error';

    /**
     * @param  array{
     *     title?: ?string,
     *     seo_title?: ?string,
     *     description?: ?string,
     *     slug?: ?string,
     *     keyword?: ?string,
     *     content?: ?string,
     *     image?: ?string,
     *     canonical_url?: ?string
     * }  $data
     * @return array{
     *     score: int,
     *     status: string,
     *     label: string,
     *     checks: list<array{id: string, label: string, status: string, message: string, weight: int}>
     * }
     */
    public function analyze(array $data): array
    {
        $keyword = trim((string) ($data['keyword'] ?? ''));
        $title = trim((string) ($data['seo_title'] ?? $data['title'] ?? ''));
        $description = trim((string) ($data['description'] ?? ''));
        $slug = trim((string) ($data['slug'] ?? ''));
        $content = (string) ($data['content'] ?? '');
        $plainContent = $this->plainText($content);
        $image = trim((string) ($data['image'] ?? ''));
        $canonical = trim((string) ($data['canonical_url'] ?? ''));

        $checks = [];

        $checks[] = $this->check(
            'focus_keyword',
            'Mot-clé principal défini',
            $keyword !== '' ? self::STATUS_GOOD : self::STATUS_ERROR,
            $keyword !== ''
                ? 'Un mot-clé principal est défini : « '.$keyword.' ».'
                : 'Définissez un mot-clé principal pour guider l’analyse SEO.',
            3
        );

        $keywordIn = fn (string $haystack): bool => $keyword !== ''
            && Str::contains(Str::lower($haystack), Str::lower($keyword));

        $checks[] = $this->check(
            'keyword_in_title',
            'Mot-clé dans le titre SEO',
            $keyword === '' ? self::STATUS_ERROR : ($keywordIn($title) ? self::STATUS_GOOD : self::STATUS_IMPROVEMENT),
            $keyword === ''
                ? 'Ajoutez d’abord un mot-clé principal.'
                : ($keywordIn($title)
                    ? 'Le mot-clé apparaît dans le titre SEO.'
                    : 'Ajoutez le mot-clé principal dans le titre SEO.'),
            3
        );

        $checks[] = $this->check(
            'keyword_in_description',
            'Mot-clé dans la méta description',
            $keyword === '' ? self::STATUS_ERROR : ($keywordIn($description) ? self::STATUS_GOOD : self::STATUS_IMPROVEMENT),
            $keyword === ''
                ? 'Ajoutez d’abord un mot-clé principal.'
                : ($keywordIn($description)
                    ? 'Le mot-clé apparaît dans la méta description.'
                    : 'Insérez le mot-clé principal dans la méta description.'),
            2
        );

        $checks[] = $this->check(
            'keyword_in_slug',
            'Mot-clé dans l’URL',
            $keyword === '' ? self::STATUS_ERROR : ($keywordIn(str_replace('-', ' ', $slug)) ? self::STATUS_GOOD : self::STATUS_IMPROVEMENT),
            $keyword === ''
                ? 'Ajoutez d’abord un mot-clé principal.'
                : ($keywordIn(str_replace('-', ' ', $slug))
                    ? 'Le mot-clé apparaît dans le slug de l’URL.'
                    : 'Ajoutez le mot-clé principal dans le slug de l’URL.'),
            2
        );

        $checks[] = $this->check(
            'keyword_in_content',
            'Mot-clé dans le contenu',
            $keyword === '' ? self::STATUS_ERROR : ($keywordIn($plainContent) ? self::STATUS_GOOD : self::STATUS_IMPROVEMENT),
            $keyword === ''
                ? 'Ajoutez d’abord un mot-clé principal.'
                : ($keywordIn($plainContent)
                    ? 'Le mot-clé est présent dans le contenu.'
                    : 'Utilisez le mot-clé principal au moins une fois dans le contenu.'),
            3
        );

        $titleLength = mb_strlen($title);

        if ($titleLength === 0) {
            $checks[] = $this->check('title_length', 'Longueur du titre SEO', self::STATUS_ERROR, 'Le titre SEO est vide.', 2);
        } elseif ($titleLength < 30) {
            $checks[] = $this->check('title_length', 'Longueur du titre SEO', self::STATUS_IMPROVEMENT, 'Le titre est trop court ('.$titleLength.' caractères). Visez 30 à 60 caractères.', 2);
        } elseif ($titleLength <= 60) {
            $checks[] = $this->check('title_length', 'Longueur du titre SEO', self::STATUS_GOOD, 'Longueur idéale ('.$titleLength.' caractères).', 2);
        } elseif ($titleLength <= 70) {
            $checks[] = $this->check('title_length', 'Longueur du titre SEO', self::STATUS_IMPROVEMENT, 'Le titre risque d’être tronqué ('.$titleLength.' caractères). Visez 60 caractères maximum.', 2);
        } else {
            $checks[] = $this->check('title_length', 'Longueur du titre SEO', self::STATUS_ERROR, 'Le titre est trop long ('.$titleLength.' caractères) et sera tronqué par Google.', 2);
        }

        $descriptionLength = mb_strlen($description);

        if ($descriptionLength === 0) {
            $checks[] = $this->check('description_length', 'Longueur de la méta description', self::STATUS_ERROR, 'La méta description est vide.', 2);
        } elseif ($descriptionLength < 70) {
            $checks[] = $this->check('description_length', 'Longueur de la méta description', self::STATUS_IMPROVEMENT, 'La description est trop courte ('.$descriptionLength.' caractères). Visez 120 à 156 caractères.', 2);
        } elseif ($descriptionLength < 120) {
            $checks[] = $this->check('description_length', 'Longueur de la méta description', self::STATUS_IMPROVEMENT, 'La description pourrait être plus riche ('.$descriptionLength.' caractères). Visez 120 à 156 caractères.', 2);
        } elseif ($descriptionLength <= 156) {
            $checks[] = $this->check('description_length', 'Longueur de la méta description', self::STATUS_GOOD, 'Longueur idéale ('.$descriptionLength.' caractères).', 2);
        } else {
            $checks[] = $this->check('description_length', 'Longueur de la méta description', self::STATUS_IMPROVEMENT, 'La description est longue ('.$descriptionLength.' caractères) et pourrait être tronquée.', 1);
        }

        $wordCount = Str::wordCount($plainContent);

        if ($wordCount === 0) {
            $checks[] = $this->check('content_length', 'Volume de contenu', self::STATUS_ERROR, 'Le contenu est vide.', 2);
        } elseif ($wordCount < 150) {
            $checks[] = $this->check('content_length', 'Volume de contenu', self::STATUS_IMPROVEMENT, 'Le contenu est court ('.$wordCount.' mots). Visez au moins 300 mots.', 2);
        } elseif ($wordCount < 300) {
            $checks[] = $this->check('content_length', 'Volume de contenu', self::STATUS_IMPROVEMENT, 'Contenu correct ('.$wordCount.' mots). 300 mots ou plus renforceront le référencement.', 2);
        } else {
            $checks[] = $this->check('content_length', 'Volume de contenu', self::STATUS_GOOD, 'Contenu suffisant ('.$wordCount.' mots).', 2);
        }

        $images = $this->extractImages($content);
        $missingAlt = collect($images)->filter(fn (?string $alt) => blank($alt))->count();

        if (count($images) === 0) {
            $checks[] = $this->check('images_alt', 'Attributs ALT des images', self::STATUS_IMPROVEMENT, 'Aucune image dans le contenu. Ajoutez des visuels avec un texte alternatif descriptif.', 2);
        } elseif ($missingAlt > 0) {
            $checks[] = $this->check('images_alt', 'Attributs ALT des images', self::STATUS_ERROR, $missingAlt.' image(s) sans attribut ALT descriptif.', 2);
        } else {
            $checks[] = $this->check('images_alt', 'Attributs ALT des images', self::STATUS_GOOD, 'Toutes les images disposent d’un texte alternatif.', 2);
        }

        if (blank($image)) {
            $checks[] = $this->check('seo_image', 'Image de partage', self::STATUS_IMPROVEMENT, 'Aucune image de partage n’est définie. Ajoutez une image de couverture ou une image SEO.', 1);
        } else {
            $checks[] = $this->check('seo_image', 'Image de partage', self::STATUS_GOOD, 'Une image de partage est disponible.', 1);
        }

        $links = $this->extractLinks($content);
        $internalLinks = $links['internal'];
        $externalLinks = $links['external'];

        $checks[] = $this->check(
            'internal_links',
            'Liens internes',
            $internalLinks > 0 ? self::STATUS_GOOD : self::STATUS_IMPROVEMENT,
            $internalLinks > 0
                ? $internalLinks.' lien(s) interne(s) détecté(s).'
                : 'Ajoutez au moins un lien vers une autre page du site.',
            2
        );

        $checks[] = $this->check(
            'external_links',
            'Liens externes',
            $externalLinks > 0 ? self::STATUS_GOOD : self::STATUS_IMPROVEMENT,
            $externalLinks > 0
                ? $externalLinks.' lien(s) externe(s) détecté(s).'
                : 'Ajoutez une source externe de qualité (étude, partenaire, référence).',
            1
        );

        if ($canonical === '') {
            $checks[] = $this->check('canonical', 'URL canonique', self::STATUS_GOOD, 'Aucune URL canonique saisie : elle sera générée automatiquement.', 1);
        } elseif (! filter_var($canonical, FILTER_VALIDATE_URL)) {
            $checks[] = $this->check('canonical', 'URL canonique', self::STATUS_ERROR, 'L’URL canonique n’est pas une URL absolue valide.', 1);
        } else {
            $checks[] = $this->check('canonical', 'URL canonique', self::STATUS_GOOD, 'URL canonique valide et configurée.', 1);
        }

        $checks[] = $this->check(
            'slug',
            'Slug SEO',
            $slug === '' ? self::STATUS_ERROR : (mb_strlen($slug) <= 75 ? self::STATUS_GOOD : self::STATUS_IMPROVEMENT),
            $slug === ''
                ? 'Le slug de l’URL est vide.'
                : (mb_strlen($slug) <= 75 ? 'Slug propre et court.' : 'Le slug est long, raccourcissez-le pour améliorer la lisibilité.'),
            1
        );

        $totalWeight = array_sum(array_column($checks, 'weight'));
        $earned = 0;

        foreach ($checks as $check) {
            $earned += match ($check['status']) {
                self::STATUS_GOOD => $check['weight'],
                self::STATUS_IMPROVEMENT => $check['weight'] * 0.5,
                default => 0,
            };
        }

        $score = $totalWeight > 0 ? (int) round(($earned / $totalWeight) * 100) : 0;

        $status = match (true) {
            $score >= 80 => self::STATUS_GOOD,
            $score >= 50 => self::STATUS_IMPROVEMENT,
            default => self::STATUS_ERROR,
        };

        return [
            'score' => $score,
            'status' => $status,
            'label' => match ($status) {
                self::STATUS_GOOD => 'Bon',
                self::STATUS_IMPROVEMENT => 'À améliorer',
                default => 'Critique',
            },
            'checks' => $checks,
        ];
    }

    private function check(string $id, string $label, string $status, string $message, int $weight = 1): array
    {
        return compact('id', 'label', 'status', 'message', 'weight');
    }

    private function plainText(?string $html): string
    {
        $text = html_entity_decode(strip_tags((string) $html), ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return trim((string) preg_replace('/\s+/', ' ', $text));
    }

    /**
     * @return list<string|null>
     */
    private function extractImages(string $html): array
    {
        preg_match_all('/<img\b[^>]*>/i', $html, $matches);

        return array_map(function (string $tag): ?string {
            if (! preg_match('/\balt\s*=\s*(["\'])(.*?)\1/is', $tag, $alt)) {
                return null;
            }

            return trim(html_entity_decode($alt[2], ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        }, $matches[0] ?? []);
    }

    /**
     * @return array{internal: int, external: int}
     */
    private function extractLinks(string $html): array
    {
        preg_match_all('/<a\b[^>]*\bhref\s*=\s*(["\'])(.*?)\1/is', $html, $matches);

        $host = parse_url((string) config('app.url'), PHP_URL_HOST);
        $internal = 0;
        $external = 0;

        foreach ($matches[2] ?? [] as $href) {
            $href = trim(html_entity_decode($href, ENT_QUOTES | ENT_HTML5, 'UTF-8'));

            if ($href === '' || Str::startsWith($href, ['#', 'mailto:', 'tel:', 'javascript:'])) {
                continue;
            }

            if (Str::startsWith($href, ['/', './', '../'])) {
                $internal++;

                continue;
            }

            $linkHost = parse_url($href, PHP_URL_HOST);

            if ($linkHost === null) {
                $internal++;

                continue;
            }

            if ($host !== null && strcasecmp($linkHost, $host) === 0) {
                $internal++;
            } else {
                $external++;
            }
        }

        return ['internal' => $internal, 'external' => $external];
    }
}
