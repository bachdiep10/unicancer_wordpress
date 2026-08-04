<?php
$url = 'http://127.0.0.1:9000/';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
$html = curl_exec($ch);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if (!$html) {
    die("Failed\n");
}

echo "Status: $status\n";
echo "Length: " . strlen($html) . " chars\n\n";

// Title
if (preg_match('/<title[^>]*>(.*?)<\/title>/is', $html, $m)) {
    echo "Title: " . $m[1] . "\n";
}

// Sections
$sections = array();
preg_match_all('/class="(hero-section|why-choose-section|mdt-section|cancer-types-section|treatment-tech-section|patient-stories-section|international-guide-section|faq-section|contact-form-section|footer-main|hero-stats|why-choose-grid)"/', $html, $m);
if (!empty($m[1])) {
    echo "\nSections found:\n";
    foreach (array_unique($m[1]) as $s) {
        echo "  - $s\n";
    }
}

// Doctors in MDT
preg_match_all('/<h3 class="doctor-card-name[^"]*"[^>]*>(.*?)<\/h3>/is', $html, $m);
if (!empty($m[1])) {
    echo "\nDoctors shown:\n";
    foreach ($m[1] as $name) {
        echo "  - " . trim(strip_tags($name)) . "\n";
    }
}

// Stats
preg_match_all('/<div class="hero-stat-number">([^<]+)<\/div>/', $html, $m);
if (!empty($m[1])) {
    echo "\nStats:\n";
    foreach ($m[1] as $s) {
        echo "  - " . trim($s) . "\n";
    }
}

// FAQs
preg_match_all('/<span class="faq-question-text[^"]*"[^>]*>(.*?)<\/span>/is', $html, $m);
if (!empty($m[1])) {
    echo "\nFAQs shown:\n";
    foreach ($m[1] as $q) {
        echo "  - " . trim(strip_tags($q)) . "\n";
    }
}

// Files loaded (CSS/JS)
preg_match_all('/(href|src)="(\/wp-content\/themes\/uniasia-cancer-theme\/assets\/[^"]+)"/', $html, $m);
if (!empty($m[2])) {
    echo "\nTheme assets:\n";
    foreach ($m[2] as $f) {
        echo "  - $f\n";
    }
}

echo "\n=== DONE ===\n";