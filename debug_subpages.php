<?php
/**
 * Debug subpages - what's the actual response?
 */

$pages = [
    '/doctors/',
    '/patient-stories/',
    '/cancer-types/',
    '/technologies/',
    '/faqs/',
    '/about-us/',
];

echo "=== Subpage Status Check ===\n\n";

foreach ($pages as $url) {
    $ch = curl_init('http://127.0.0.1:9000' . $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    $content = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    $redirect = curl_getinfo($ch, CURLINFO_REDIRECT_URL);
    curl_close($ch);

    echo "[$code] $url\n";
    if ($code == 301 || $code == 302) {
        echo "    Redirect to: $redirect\n";
    } elseif ($code != 200) {
        echo "    ERROR: $error\n";
        echo "    First 500 chars:\n";
        echo substr($content, 0, 500) . "\n";
    } else {
        echo "    Size: " . strlen($content) . " chars\n";
    }
    echo "\n";
}

// Check latest error log
echo "\n=== Recent PHP Errors ===\n";
$errFile = 'C:\laragon\tmp\php-err2.txt';
if (file_exists($errFile)) {
    $lines = file($errFile);
    $errors = array_filter($lines, function($l) {
        return stripos($l, 'PHP Fatal') !== false || stripos($l, 'PHP Warning') !== false;
    });
    $errors = array_slice($errors, -20);
    foreach ($errors as $e) {
        echo "  " . trim($e) . "\n";
    }
}