<?php
/**
 * Test all images and pages now
 */

echo "=== TEST 1: Image URLs in HTML ===\n\n";

$ch = curl_init('http://127.0.0.1:9000/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$home = curl_exec($ch);
curl_close($ch);

// Find all img URLs
preg_match_all('/<img[^>]+src=["\']([^"\']+)["\']/i', $home, $m);
$imgs = array_unique($m[1]);
echo "Images in homepage: " . count($imgs) . "\n";
foreach ($imgs as $img) {
    $ch = curl_init($img);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
    $type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    curl_close($ch);
    $status = ($code == 200) ? 'OK' : "FAIL($code)";
    echo "  [$status] " . str_pad($size ?: 0, 8) . " $img\n";
}

echo "\n=== TEST 2: Background images (inline styles) ===\n";
preg_match_all('/background-image:[^"\']*url\(([^)]+)\)/i', $home, $m);
$bgImgs = array_unique($m[1]);
echo "Background images: " . count($bgImgs) . "\n";
foreach ($bgImgs as $bg) {
    $bg = trim($bg, "\"' ");
    $ch = curl_init($bg);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
    curl_close($ch);
    $status = ($code == 200) ? 'OK' : "FAIL($code)";
    echo "  [$status] " . str_pad($size ?: 0, 8) . " $bg\n";
}

echo "\n=== TEST 3: All subpages ===\n\n";

$pages = ['/', '/doctors/', '/patient-stories/', '/cancer-types/', '/technologies/', '/faqs/', '/about-us/'];
foreach ($pages as $url) {
    $ch = curl_init('http://127.0.0.1:9000' . $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $content = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($code != 200) {
        echo "  [$code] FAIL $url\n";
        continue;
    }

    preg_match('/<main[^>]*>(.*?)<\/main>/is', $content, $m);
    $main = $m[1] ?? '';

    // Find content sections
    $sections = array();
    preg_match_all('/<section[^>]*class=["\']([^"\']+)["\']/', $main, $sm);
    foreach (array_unique($sm[1]) as $s) {
        $sections[] = $s;
    }

    // Find images count
    preg_match_all('/<img[^>]+src=["\']([^"\']+)["\']/i', $main, $im);
    $imgCount = count($im[1]);

    echo "  [OK 200] $url - main: " . strlen($main) . " chars, sections: " . count($sections) . ", images: $imgCount\n";

    // Check for first few images
    foreach (array_slice(array_unique($im[1]), 0, 3) as $iurl) {
        $ch = curl_init($iurl);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_exec($ch);
        $icode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        echo "      [$icode] $iurl\n";
    }
}