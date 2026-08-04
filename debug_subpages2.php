<?php
/**
 * Check what's actually in the rendered page (not just first 500 chars)
 */

$pages = [
    '/doctors/' => 'Bác sĩ',
    '/patient-stories/' => 'Stories',
    '/cancer-types/' => 'Cancer Types',
    '/technologies/' => 'Technologies',
    '/faqs/' => 'FAQs',
    '/about-us/' => 'About',
];

echo "=== Detailed page content check ===\n\n";

foreach ($pages as $url => $name) {
    echo "=== $name ($url) ===\n";

    $ch = curl_init('http://127.0.0.1:9000' . $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $content = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Look for actual content
    preg_match('/<main[^>]*>(.*?)<\/main>/is', $content, $m);
    if (!empty($m[1])) {
        $main = $m[1];
        echo "Main length: " . strlen($main) . " chars\n";

        // Check for actual post titles
        preg_match_all('/<h[123][^>]*>(.*?)<\/h[123]>/is', $main, $titles);
        echo "Titles found: " . count($titles[1]) . "\n";
        foreach (array_slice($titles[1], 0, 5) as $t) {
            echo "  - " . trim(strip_tags($t)) . "\n";
        }
    } else {
        echo "NO MAIN TAG FOUND!\n";
        echo "Looking for <body>...</body>\n";
        preg_match('/<body[^>]*>(.*?)<\/body>/is', $content, $b);
        if (!empty($b[1])) {
            echo "Body length: " . strlen($b[1]) . " chars\n";
            // Show first 1000 chars
            echo substr($b[1], 0, 1000) . "\n";
        }
    }
    echo "\n";
}