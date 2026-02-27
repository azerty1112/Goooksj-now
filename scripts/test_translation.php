<?php
require_once __DIR__ . '/../functions.php';

// quick smoke test for translation and auto-tag
$text = 'This is a test article title';
$trans = translateText($text, 'ar');
echo "Translation of '$text' to ar: $trans\n";

$tags = generateAutoTags('Best Hybrid SUVs 2026', 'A quick review of the best hybrid SUVs for families.');
echo "Auto tags: " . implode(', ', $tags) . "\n";

// simulate saving and retrieving
$dummy = saveArticle('Test article for translation', ['content' => '<p>Sample body</p>', 'image' => '', 'image2' => '', 'excerpt' => '']);
if ($dummy) {
    echo "Article saved successfully\n";
} else {
    echo "Failed to save article (maybe duplicate?)\n";
}
