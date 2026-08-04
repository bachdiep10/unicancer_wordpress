<?php
/**
 * Extract CSS URLs from local homepage and compare with original site
 */
$ch = curl_init('http://127.0.0.1:9000/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$html = curl_exec($ch);
curl_close($ch);

// Find all CSS files
echo "=== CSS files in LOCAL page ===\n";
preg_match_all('/<link[^>]+stylesheet[^>]+href="([^"]+)"/i', $html, $matches);
foreach ($matches[1] as $css) {
    echo "  $css\n";
}

echo "\n=== Inline <style> blocks ===\n";
preg_match_all('/<style[^>]*>(.*?)<\/style>/is', $html, $styles);
echo "Total: " . count($styles[0]) . " blocks\n";

// Look for theme style.css
echo "\n=== Theme style.css inspection ===\n";
$themeCss = @file_get_contents('C:\laragon\www\uniasia\wp-content\themes\uniasia-cancer-theme\style.css');
if ($themeCss) {
    echo "Size: " . strlen($themeCss) . " bytes\n";
    echo "First 30 lines:\n";
    $lines = explode("\n", $themeCss);
    for ($i = 0; $i < min(30, count($lines)); $i++) {
        echo "  " . $lines[$i] . "\n";
    }
}

// Original site CSS path
echo "\n=== Original site CSS files ===\n";
$origHtml = @file_get_contents('https://uniasiacancer.com');
preg_match_all('/<link[^>]+stylesheet[^>]+href="([^"]+)"/i', $origHtml, $origMatches);
foreach ($origMatches[1] as $css) {
    echo "  $css\n";
}