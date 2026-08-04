<?php
/**
 * Test exact URLs from menu as configured
 */
$urls = [
    'http://localhost/uniasia/doctors/',
    'http://localhost/uniasia/patient-stories/',
    'http://localhost/uniasia/cancer-types/',
    'http://localhost/uniasia/technologies/',
    'http://localhost/uniasia/faqs/',
    'http://localhost/uniasia/about-us/',
];

echo "=== Testing exact menu URLs (via localhost) ===\n\n";
foreach ($urls as $url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
    $content = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $final = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
    curl_close($ch);

    // Extract title from content
    $title = '';
    if (preg_match('/<title>(.*?)<\/title>/i', $content, $m)) $title = $m[1];

    echo "[$code] $url\n";
    if ($final != $url) echo "    -> $final\n";
    echo "    Title: $title\n";
    echo "    Size: " . strlen($content) . " bytes\n\n";
}