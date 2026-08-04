<?php
/**
 * Find all image URLs - use full headers
 */

$ctx = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36\r\n",
        'timeout' => 30,
        'ignore_errors' => true,
        'follow_location' => 1,
    ],
    'ssl' => [
        'verify_peer' => false,
    ],
]);

$html = @file_get_contents('https://uniasiacancer.com/', false, $ctx);
echo "Length: " . strlen($html ?: '') . " chars\n";

if (!$html) {
    // Try via curl
    $ch = curl_init('https://uniasiacancer.com/');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $html = curl_exec($ch);
    curl_close($ch);
}

echo "After curl length: " . strlen($html ?: '') . "\n";

if (!$html) die("Failed\n");

// Find images
preg_match_all('/https?:\/\/[^"\'\s>]+\.(jpg|jpeg|png|webp|svg)/i', $html, $m);
$images = array_unique($m[0]);
echo "Images: " . count($images) . "\n\n";

foreach ($images as $img) {
    $ch = curl_init($img);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
    curl_close($ch);
    echo "  [$code] " . str_pad($size ?: 0, 10) . " $img\n";
}

// Also find relative paths
echo "\n=== Relative paths ===\n";
preg_match_all('/(?:href|src|data-src|data-bg|background-image: ?url\()=?"?\'?([^"\')\s>]+\.(jpg|jpeg|png|webp|svg))/i', $html, $m2);
echo "Relative images: " . count($m2[1]) . "\n";
foreach (array_slice(array_unique($m2[1]), 0, 30) as $r) {
    echo "  $r\n";
}