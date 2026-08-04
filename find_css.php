<?php
/**
 * Get CSS and HTML structure from original site
 */

$ch = curl_init('https://uniasiacancer.com/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
$html = curl_exec($ch);
curl_close($ch);

echo "Length: " . strlen($html) . " chars\n\n";

// Find CSS files
preg_match_all('/<link[^>]+href=["\']([^"\']+\.css[^"\']*)/i', $html, $m);
echo "CSS files:\n";
foreach ($m[1] as $c) {
    echo "  $c\n";
}

// Find inline CSS
preg_match_all('/<style[^>]*>(.*?)<\/style>/is', $html, $m2);
echo "\nInline styles: " . count($m2[1]) . "\n";

// Find header HTML
echo "\n=== Header HTML structure ===\n";
if (preg_match('/<header[^>]*>(.*?)<\/header>/is', $html, $m)) {
    echo substr($m[1], 0, 3000);
}

echo "\n\n=== Top bar structure ===\n";
// look for topbar
if (preg_match('/<div[^>]*class=["\']top-bar[^>]*>(.*?)<\/div>\s*<header/is', $html, $m)) {
    echo substr($m[0], 0, 2000);
}