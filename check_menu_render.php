<?php
/**
 * Check menu rendering in HTML
 */
$ch = curl_init('http://127.0.0.1:9000/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$html = curl_exec($ch);
curl_close($ch);

echo "=== Header & Menu HTML ===\n";
if (preg_match('/<header[^>]*>(.*?)<\/header>/is', $html, $m)) {
    $header = $m[1];
    echo "Header length: " . strlen($header) . " chars\n\n";

    // Menu items
    preg_match_all('/<li[^>]*class="menu-item[^"]*"[^>]*>.*?<a[^>]+href="([^"]+)"[^>]*>(.*?)<\/a>/is', $header, $items);
    echo "Menu items found: " . count($items[1]) . "\n";
    foreach ($items[1] as $idx => $url) {
        $title = trim(strip_tags($items[2][$idx]));
        echo "  - $title -> $url\n";
    }

    // Logo
    preg_match('/<img[^>]+class="[^"]*site-logo[^"]*"[^>]+src="([^"]+)"/i', $header, $lm);
    if (!empty($lm[1])) {
        echo "\nLogo: {$lm[1]}\n";
    } else {
        echo "\nNo logo image found\n";
    }
}

echo "\n=== CSS files loaded ===\n";
preg_match_all("/stylesheet[^>]*id='([^']*)'/", $html, $m);
foreach ($m[1] as $c) {
    echo "  - $c\n";
}