<?php
require_once __DIR__ . '/../functions.php';

// seed defaults if needed
seedDefaultNiches();

$niches = listNiches();
if (empty($niches)) {
    echo "No niches found.\n";
    exit(1);
}

echo "Available niches:\n";
foreach ($niches as $n) {
    echo sprintf("- %s (%s)\n", $n['name'], $n['slug']);
}

$active = getActiveNicheSlug();
echo "Active niche (from settings or ?niche=): $active\n";

// list sources for active niche
$mgrClass = 'App\\NicheManager';
if (class_exists($mgrClass)) {
    $n = $mgrClass::getNicheBySlug($active);
    if ($n) {
        $sources = $mgrClass::getSourcesForNiche((int)$n['id']);
        echo "Sources for $active:\n";
        foreach ($sources as $s) {
            echo sprintf(" - [%s] %s\n", $s['type'], $s['url']);
        }
    } else {
        echo "Active niche not found in DB.\n";
    }
} else {
    echo "NicheManager class not available.\n";
}
