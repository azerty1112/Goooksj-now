<?php
// Direct test script that avoids loading vendor autoload to bypass platform checks
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../src/NicheManager.php';

use App\NicheManager;

// Seed defaults (idempotent)
NicheManager::seedDefaults();

$niches = NicheManager::listNiches();
if (empty($niches)) {
    echo "No niches found.\n";
    exit(1);
}

echo "Available niches:\n";
foreach ($niches as $n) {
    echo sprintf("- %s (%s)\n", $n['name'], $n['slug']);
}

$active = 'general';
echo "Active niche (default): $active\n";

// show sources for each niche
foreach ($niches as $n) {
    echo "Sources for {$n['slug']}:\n";
    $sources = NicheManager::getSourcesForNiche((int)$n['id']);
    foreach ($sources as $s) {
        echo sprintf(" - [%s] %s\n", $s['type'], $s['url']);
    }
}
