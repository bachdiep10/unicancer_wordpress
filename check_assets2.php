<?php
$ch = curl_init('http://127.0.0.1:9000/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$html = curl_exec($ch);
curl_close($ch);

// Find all link/script tags
preg_match_all('/<(link|script)[^>]*(href|src)="([^"]+)"[^>]*>/i', $html, $m);
echo "All loaded resources:\n";
foreach ($m[0] as $tag) {
    echo "  $tag\n";
}