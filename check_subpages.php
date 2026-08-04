<?php
/**
 * Check subpage actual content - what loads
 */
$pages = [
    'doctors' => 'http://127.0.0.1:9000/doctors/',
    'patient-stories' => 'http://127.0.0.1:9000/patient-stories/',
    'cancer-types' => 'http://127.0.0.1:9000/cancer-types/',
    'technologies' => 'http://127.0.0.1:9000/technologies/',
    'faqs' => 'http://127.0.0.1:9000/faqs/',
    'about-us' => 'http://127.0.0.1:9000/about-us/',
];

foreach ($pages as $name => $url) {
    echo "=== $name ===\n";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $html = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    echo "URL: $url -> $code, size: " . strlen($html) . "\n";

    // Extract title
    preg_match('/<title>(.*?)<\/title>/i', $html, $m);
    echo "Title: " . ($m[1] ?? 'N/A') . "\n";

    // Check for actual content
    $content = '';
    if (preg_match('/<main[^>]*>(.*?)<\/main>/is', $html, $m)) $content = $m[1];

    if (strlen($content) < 100) {
        echo "*** Very little content! ***\n";
    } else {
        echo "Content size: " . strlen($content) . " bytes\n";
    }

    // Check for error indicators
    foreach (['404', 'Not Found', 'page-not-found'] as $err) {
        if (stripos($html, $err) !== false) {
            echo "Contains error indicator: '$err'\n";
        }
    }
    echo "\n";
}