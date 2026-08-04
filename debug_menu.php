<?php
/**
 * Check actual HTTP response codes (follow redirects)
 */
$pages = [
    'http://127.0.0.1:9000/bac-si/',
    'http://127.0.0.1:9000/doctors/',
    'http://127.0.0.1:9000/cau-chuyen-benh-nhan/',
    'http://127.0.0.1:9000/patient-stories/',
    'http://127.0.0.1:9000/loai-ung-thu/',
    'http://127.0.0.1:9000/cancer-types/',
    'http://127.0.0.1:9000/ky-thuat-dieu-tri/',
    'http://127.0.0.1:9000/technologies/',
    'http://127.0.0.1:9000/ve-chung-toi/',
    'http://127.0.0.1:9000/about-us/',
];

echo "=== Direct URL check ===\n\n";
foreach ($pages as $url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
    $content = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $final = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
    curl_close($ch);

    $status = $code == 200 ? "OK" : "404/BROKEN";
    echo "[$code] $url\n";
    if ($final != $url) {
        echo "    Redirected to: $final\n";
    }
    echo "    [$status]\n\n";
}