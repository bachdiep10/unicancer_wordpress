<?php
$url = 'http://127.0.0.1:9000/';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
$html = curl_exec($ch);
curl_close($ch);

// Find all CSS/JS references
echo "=== Theme assets ===\n";
preg_match_all('/<link[^>]+href="([^"]*uniasia[^"]*)"[^>]*>/i', $html, $m);
foreach ($m[1] as $f) {
    $ch = curl_init($f);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
    echo "  CSS $code ($size bytes): $f\n";
    curl_close($ch);
}

preg_match_all('/<script[^>]+src="([^"]*uniasia[^"]*)"[^>]*>/i', $html, $m);
foreach ($m[1] as $f) {
    $ch = curl_init($f);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
    echo "  JS  $code ($size bytes): $f\n";
    curl_close($ch);
}

// Other important assets
preg_match_all('/<link[^>]+href="([^"]*\.(css|ico|png|jpg))"[^>]*>/i', $html, $m);
foreach ($m[1] as $f) {
    if (strpos($f, 'uniasia') !== false) continue;
    $ch = curl_init($f);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    echo "  OTHER $code: $f\n";
    curl_close($ch);
}

echo "\n=== Subpages ===\n";
$pages = ['doctors', 'patient-stories', 'cancer-types', 'technologies', 'faqs', 'about-us', 'wp-admin'];
foreach ($pages as $p) {
    $ch = curl_init("http://127.0.0.1:9000/$p/");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $content = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    $size = strlen($content);
    echo "  [$code] /$p/ - $size chars\n";
}

echo "\n=== Try admin ===\n";
$ch = curl_init('http://127.0.0.1:9000/wp-admin/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
$content = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo "  Admin login: $code\n";