<?php
// Final verification: menu in HTML + CSS loaded + all 7 routes
$routes = ['/', '/doctors/', '/patient-stories/', '/cancer-types/', '/technologies/', '/faqs/', '/about-us/'];
$labels = ['Home', 'About Us', 'Doctors', 'Treatments', 'Patient Stories', 'FAQ', 'Contact Us'];

$ch = curl_init('http://127.0.0.1:9000/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$home = curl_exec($ch);
curl_close($ch);

echo "=== Homepage menu labels ===\n";
foreach ($labels as $l) {
    $found = strpos($home, $l) !== false ? 'OK' : 'MISSING';
    echo "  [$found] $l\n";
}

echo "\n=== CSS files loaded on homepage ===\n";
preg_match_all('/<link[^>]+stylesheet[^>]+href="([^"]+)"/i', $home, $m);
foreach ($m[1] as $css) {
    echo "  $css\n";
}

echo "\n=== Subpage status ===\n";
foreach ($routes as $url) {
    $ch = curl_init('http://127.0.0.1:9000' . $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $c = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    echo "  [$code] $url (" . strlen($c) . " bytes)\n";
}