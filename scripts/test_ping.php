<?php
// simple test for pingSearchEngines without loading vendor
function pingSearchEngines($sitemapUrl) {
    $sitemapUrl = trim((string)$sitemapUrl);
    if ($sitemapUrl === '') {
        return false;
    }
    $targets = [
        'https://www.google.com/ping?sitemap=' . rawurlencode($sitemapUrl),
        'https://www.bing.com/ping?sitemap=' . rawurlencode($sitemapUrl),
    ];
    foreach ($targets as $u) {
        echo "requesting $u\n";
        // not actually performing for tests to avoid network
        //echo file_get_contents($u);
    }
    return true;
}

$testUrl = 'https://example.com/sitemap.php';
$result = pingSearchEngines($testUrl);
var_dump($result);
