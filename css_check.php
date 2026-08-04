<?php
$ch = curl_init('http://127.0.0.1:9000/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$html = curl_exec($ch);
curl_close($ch);

// Search all CSS
preg_match_all('/stylesheet[^>]+\.css[^>]+/i', $html, $m);
echo "Stylesheets in HTML:\n";
echo count($m[0]) . " matches\n\n";
foreach ($m[0] as $s) {
    echo "  $s\n";
}

// WordPress sometimes output CSS with quote variations
preg_match_all("/stylesheet[^>]+\.css[^']+/i", $html, $m2);
echo "\nAlt search:\n";
echo count($m2[0]) . " matches\n";
foreach ($m2[0] as $s) {
    echo "  $s\n";
}

// Check what's in <head> entirely
if (preg_match('/<head[^>]*>(.*?)<\/head>/is', $html, $m)) {
    $head = $m[1];
    echo "\n<HEAD> length: " . strlen($head) . " chars\n";
    // Count <link> tags
    preg_match_all('/<link[^>]*>/i', $head, $m3);
    echo count($m3[0]) . " link tags in head\n";
    foreach ($m3[0] as $l) {
        if (stripos($l, 'stylesheet') !== false || stripos($l, '.css') !== false) {
            echo "  CSS-LINK: $l\n";
        }
    }
}