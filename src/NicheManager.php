<?php

namespace App;

class NicheManager
{
    protected string $tableNiches = 'niches';
    protected string $tableSources = 'niche_sources';

    public static function listNiches(): array
    {
        $pdo = \db_connect();
        $stmt = $pdo->query("SELECT id, slug, name, description FROM niches ORDER BY id");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
    }

    public static function getNicheBySlug(string $slug): ?array
    {
        $pdo = \db_connect();
        $stmt = $pdo->prepare("SELECT id, slug, name, description FROM niches WHERE slug = ? LIMIT 1");
        $stmt->execute([trim($slug)]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row === false ? null : $row;
    }

    public static function createNiche(string $slug, string $name, string $description = ''): int
    {
        $pdo = \db_connect();
        $stmt = $pdo->prepare("INSERT OR IGNORE INTO niches (slug, name, description) VALUES (?, ?, ?)");
        $stmt->execute([trim($slug), trim($name), trim($description)]);
        $id = (int)$pdo->lastInsertId();
        if ($id === 0) {
            // fetch existing id
            $existing = self::getNicheBySlug($slug);
            return $existing['id'] ?? 0;
        }
        return $id;
    }

    public static function addSource(int $nicheId, string $type, string $url): bool
    {
        $type = $type === 'web' ? 'web' : 'rss';
        $pdo = \db_connect();
        $stmt = $pdo->prepare("INSERT OR IGNORE INTO niche_sources (niche_id, type, url) VALUES (?, ?, ?)");
        return $stmt->execute([$nicheId, $type, trim($url)]);
    }

    public static function getSourcesForNiche(int $nicheId, string $type = ''): array
    {
        $pdo = \db_connect();
        if ($type === '') {
            $stmt = $pdo->prepare("SELECT type, url FROM niche_sources WHERE niche_id = ? ORDER BY id");
            $stmt->execute([$nicheId]);
        } else {
            $stmt = $pdo->prepare("SELECT type, url FROM niche_sources WHERE niche_id = ? AND type = ? ORDER BY id");
            $stmt->execute([$nicheId, $type]);
        }
        return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
    }

    public static function seedDefaults(): void
    {
        // small set of sample niches and sources
        $defaults = [
            'general' => [
                'name' => 'General Automotive',
                'description' => 'General car news and reviews.',
                'rss' => [
                    'https://www.caranddriver.com/rss/all.xml'
                ],
                'web' => [
                    'https://www.autoblog.com/news/'
                ]
            ],
            'ev' => [
                'name' => 'Electric Vehicles',
                'description' => 'EV news, reviews and charging guides.',
                'rss' => [
                    'https://insideevs.com/rss'
                ],
                'web' => [
                    'https://insideevs.com/news/'
                ]
            ],
            'motorcycles' => [
                'name' => 'Motorcycles',
                'description' => 'Motorcycle news and reviews.',
                'rss' => [
                    'https://www.motorcyclenews.com/rss/'
                ],
                'web' => []
            ],

            'auto-mobile' => [
                'name' => 'Auto Mobile',
                'description' => 'Automotive mobile trends, cars and transport updates.',
                'rss' => [],
                'web' => []
            ],
            'cuisine' => [
                'name' => 'Cuisine',
                'description' => 'Food, recipes, and restaurant-related content.',
                'rss' => [],
                'web' => []
            ],
            'eran-money' => [
                'name' => 'Eran Money',
                'description' => 'Business, money and personal finance content.',
                'rss' => [],
                'web' => []
            ]
        ];

        foreach ($defaults as $slug => $cfg) {
            $id = self::createNiche($slug, $cfg['name'], $cfg['description']);
            foreach ($cfg['rss'] as $r) {
                if (trim($r) !== '') self::addSource($id, 'rss', $r);
            }
            foreach ($cfg['web'] as $w) {
                if (trim($w) !== '') self::addSource($id, 'web', $w);
            }
        }
    }
}
