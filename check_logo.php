<?php
$ch = curl_init('http://127.0.0.1:9000/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$html = curl_exec($ch);
curl_close($ch);

if (preg_match('/<header[^>]*>(.*?)<\/header>/is', $html, $m)) {
    $h = $m[1];
    echo "Header first 800 chars:\n";
    echo substr($h, 0, 800) . "\n";
}