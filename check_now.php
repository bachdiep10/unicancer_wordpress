<?php
/**
 * Quick check: current homepage HTML to see what's rendered
 */
$ch = curl_init('http://127.0.0.1:9000/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$content = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP: $code, Size: " . strlen($content) . " bytes\n";

// Check structure
$markers = ['<header', '<nav', '<main', 'class="site-header"', 'class="primary-menu"', 'top-bar', 'hero'];
foreach ($markers as $m) {
    $count = substr_count($content, $m);
    echo "  '$m': $count\n";
}

// Save for inspection
file_put_contents(__DIR__ . '/homepage_current.html', $content);
echo "\nSaved to homepage_current.html\n";

// Check subpage URLs status
$pages = ['/doctors/', '/patient-stories/', '/cancer-types/', '/technologies/', '/faqs/', '/about-us/'];
echo "\n=== Subpages ===\n";
foreach ($pages as $url) {
    $ch = curl_init('http://127.0.0.1:9000' . $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $content = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    echo "  [$code] $url (" . strlen($content) . " bytes)\n";
}