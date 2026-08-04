<?php
$ch = curl_init('http://127.0.0.1:9000/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$html = curl_exec($ch);
curl_close($ch);

// Look for our specific CSS
echo "=== All CSS links in HTML ===\n";
preg_match_all('/<link[^>]+href="([^"]+\.css[^"]*)"[^>]*>/i', $html, $m);
foreach ($m[1] as $f) {
    echo "  $f\n";
}

echo "\n=== Body class ===\n";
preg_match('/<body class="([^"]+)"/', $html, $m);
if (!empty($m[1])) {
    echo "  " . $m[1] . "\n";
}

echo "\n=== Body min-height ===\n";
preg_match('/<main id="main-content"[^>]*>/', $html, $m);
if (!empty($m)) {
    echo "  Found main\n";
    // count sections
    preg_match_all('/<section class="([^"]+)">/', $html, $m);
    echo "\n=== Sections ===\n";
    foreach ($m[1] as $s) {
        echo "  $s\n";
    }
}