<?php
/**
 * Find all image URLs in uniasiacancer.com homepage
 */

$ch = curl_init('https://uniasiacancer.com/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$html = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Status: $code\n";
echo "Length: " . strlen($html) . " chars\n\n";

// Find all unique image URLs
preg_match_all('/https?:\/\/[^"\'\s]+\.(jpg|jpeg|png|gif|webp|svg|ico)/i', $html, $m);
$images = array_unique($m[0]);

echo "Unique images found: " . count($images) . "\n\n";

// Test each one
foreach ($images as $img) {
    $ch = curl_init($img);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
    $type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    curl_close($ch);
    $status = ($code == 200) ? 'OK' : "FAIL($code)";
    echo "  [$status] " . str_pad($size, 8) . " $img\n";
}