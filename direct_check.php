<?php
echo "=== Direct CSS/JS access check ===\n\n";

$urls = array(
    'http://127.0.0.1:9000/wp-content/themes/uniasia-cancer-theme/assets/css/main.css',
    'http://127.0.0.1:9000/wp-content/themes/uniasia-cancer-theme/assets/css/responsive.css',
    'http://127.0.0.1:9000/wp-content/themes/uniasia-cancer-theme/assets/css/elementor-overrides.css',
    'http://127.0.0.1:9000/wp-content/themes/uniasia-cancer-theme/assets/js/main.js',
    'http://127.0.0.1:9000/wp-content/themes/uniasia-cancer-theme/assets/js/swiper-init.js',
    'http://127.0.0.1:9000/wp-content/themes/uniasia-cancer-theme/assets/js/faq-accordion.js',
    'http://127.0.0.1:9000/wp-content/themes/uniasia-cancer-theme/assets/images/logo.png',
);

foreach ($urls as $url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
    $type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    curl_close($ch);
    $name = basename($url);
    echo "  [$code] $name - $size bytes ($type)\n";
}

echo "\n=== Homepage Final Check ===\n";
$home = curl_init('http://127.0.0.1:9000/');
curl_setopt($home, CURLOPT_RETURNTRANSFER, true);
$content = curl_exec($home);
$homeCode = curl_getinfo($home, CURLINFO_HTTP_CODE);
curl_close($home);

echo "Status: $homeCode\n";
echo "Size: " . strlen($content) . " chars\n";

echo "\n=== CSS in HTML ===\n";
preg_match_all('/<link[^>]+href="([^"]+\.css[^"]*)"[^>]*>/i', $content, $m);
echo "CSS files: " . count($m[1]) . "\n";
foreach ($m[1] as $css) {
    echo "  $css\n";
}

echo "\n=== JS in HTML ===\n";
preg_match_all('/<script[^>]+src="([^"]+\.js[^"]*)"[^>]*>/i', $content, $m);
echo "JS files: " . count($m[1]) . "\n";
foreach ($m[1] as $js) {
    echo "  $js\n";
}

echo "\n=== Sections ===\n";
preg_match_all('/<section class="([^"]+)">/', $content, $m);
echo count($m[1]) . " sections\n";
foreach (array_unique($m[1]) as $s) {
    echo "  - $s\n";
}