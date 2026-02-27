<?php
// simple standalone check for buildArticleExportHtml without loading vendor
function e($text) { return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8'); }

function buildArticleExportHtml(array $payload) {
    $baseUrl = 'https://example.com';
    $siteTitle = 'Test Site';
    $title = trim((string)($payload['title'] ?? ''));
    $slug = trim((string)($payload['slug'] ?? ''));
    $excerpt = trim((string)($payload['excerpt'] ?? ''));
    $content = (string)($payload['content'] ?? '');
    $published = trim((string)($payload['published_at'] ?? ''));
    $category = trim((string)($payload['category'] ?? ''));

    $pageTitle = $title !== '' ? $title . ' | ' . $siteTitle : $siteTitle;
    $pageDescription = $excerpt !== '' ? $excerpt : mb_substr(strip_tags($content), 0, 160);
    $canonical = rtrim($baseUrl, '/') . '/index.php?slug=' . rawurlencode($slug);

    $ogImage = trim((string)($payload['image'] ?? '')) ?: trim((string)($payload['image2'] ?? ''));
    $ogImageTag = $ogImage !== '' ? "<meta property=\"og:image\" content=\"" . e($ogImage) . "\">" : '';

    $html = '<!DOCTYPE html>\n';
    $html .= '<html lang="en">\n';
    $html .= '<head>\n';
    $html .= '    <meta charset="UTF-8">\n';
    $html .= '    <title>' . e($pageTitle) . '</title>\n';
    $html .= '    <meta name="description" content="' . e($pageDescription) . '">\n';
    $html .= '    <link rel="canonical" href="' . e($canonical) . '">\n';
    $html .= '    <meta property="og:title" content="' . e($pageTitle) . '">\n';
    $html .= '    <meta property="og:description" content="' . e($pageDescription) . '">\n';
    if ($ogImageTag) {
        $html .= '    ' . $ogImageTag . "\n";
    }
    $html .= '</head>\n';
    $html .= '<body>\n';
    $html .= '<article>\n';
    $html .= '<h1>' . e($title) . '</h1>\n';
    if ($published !== '') {
        $html .= '<time datetime="' . e($published) . '">' . e($published) . '</time>\n';
    }
    if ($category !== '') {
        $html .= '<p><strong>Category:</strong> ' . e($category) . '</p>\n';
    }
    $html .= $content . '\n';
    $html .= '</article>\n';
    $html .= '</body>\n';
    $html .= '</html>\n';

    return $html;
}

$sample = [
    'title' => 'Sample Car Review',
    'slug' => 'sample-car-review',
    'content' => '<p>This is the sample article body.</p>',
    'excerpt' => 'Short description of sample car.',
    'image' => 'https://example.com/img1.jpg',
    'image2' => '',
    'published_at' => date('Y-m-d H:i:s'),
    'category' => 'Test',
];

echo buildArticleExportHtml($sample);
