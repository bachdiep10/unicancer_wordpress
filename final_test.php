<?php
/**
 * Final test - all pages, all images, all components
 */

echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║   FINAL TEST - UNI-ASIA Theme on Local                       ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

$pages = [
    '/' => 'Trang chủ (Homepage)',
    '/doctors/' => 'Bác sĩ',
    '/patient-stories/' => 'Câu chuyện bệnh nhân',
    '/cancer-types/' => 'Loại ung thư',
    '/technologies/' => 'Kỹ thuật điều trị',
    '/faqs/' => 'FAQ',
    '/about-us/' => 'Giới thiệu',
];

$totalImages = 0;
$totalImagesOk = 0;
$totalPages = 0;
$totalPagesOk = 0;
$totalSections = 0;
$totalContent = 0;
$allImages = [];

foreach ($pages as $url => $label) {
    $ch = curl_init('http://127.0.0.1:9000' . $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    $raw = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $size = curl_getinfo($ch, CURLINFO_SIZE_DOWNLOAD);
    curl_close($ch);

    // Find body
    $html = $raw;
    if (preg_match('/<body[^>]*>(.*?)<\/body>/is', $raw, $b)) {
        $html = $b[1];
    }

    // Sections
    preg_match_all('/<section[^>]*class=["\']([^"\']+)["\']/', $html, $sm);
    $sections = count(array_unique($sm[1]));

    // Images
    preg_match_all('/<img[^>]+src=["\']([^"\']+)["\']/i', $html, $im);
    $images = array_unique($im[1]);

    preg_match_all('/background-image:[^"\']*url\(([^)]+)\)/i', $html, $bgm);
    $bgImages = array_unique($bgm[1]);

    $allImgs = array_unique(array_merge($images, $bgImages));

    $status = ($code == 200) ? '✓' : '✗';
    echo "$status [$code] $label ($url) - " . round($size/1024, 1) . " KB, $sections sections, " . count($allImgs) . " images\n";

    $totalPages++;
    if ($code == 200) $totalPagesOk++;
    $totalSections += $sections;
    $totalContent += strlen($html);

    foreach ($allImgs as $img) {
        $img = trim($img, "\"' ");
        $allImages[] = $img;
    }
    $totalImages += count($allImgs);
}

echo "\n=== Image Verification ===\n";
$allImages = array_unique($allImages);
$ok = 0;
$fail = 0;
foreach ($allImages as $img) {
    if (empty($img)) continue;
    $ch = curl_init($img);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($code == 200) $ok++;
    else $fail++;
}

echo "Total unique images: " . count($allImages) . "\n";
echo "OK (200): $ok\n";
echo "FAIL: $fail\n";

echo "\n=== Summary ===\n";
echo "Pages: $totalPagesOk/$totalPages OK\n";
echo "Total HTML content: " . round($totalContent/1024, 1) . " KB\n";
echo "Total sections: $totalSections\n";
echo "Image success rate: " . ($totalImages > 0 ? round($ok/count($allImages)*100, 1) : 0) . "%\n";

echo "\n=== Now check CSS Variables ===\n";
$ch = curl_init('http://127.0.0.1:9000/wp-content/themes/uniasia-cancer-theme/assets/css/main.css');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$css = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo "main.css: $code, " . strlen($css) . " chars\n";
echo "Variables defined: " . (preg_match_all('/--uniasia-/', $css, $m) ? count($m[0]) : 0) . " occurrences\n";

echo "\n=== Menu Items ===\n";
$ch = curl_init('http://127.0.0.1:9000/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$home = curl_exec($ch);
curl_close($ch);
preg_match_all('/<li[^>]+class="menu-item[^>]+><a[^>]+href="([^"]+)"[^>]*>([^<]+)/', $home, $mm);
echo "Menu items: " . count($mm[1]) . "\n";
foreach ($mm[1] as $i => $url) {
    echo "  - " . trim($mm[2][$i]) . " -> $url\n";
}

echo "\n=== Logo Check ===\n";
preg_match('/<img[^>]+src="([^"]*logo[^"]*)"/i', $home, $lm);
if (!empty($lm[1])) {
    echo "Logo: {$lm[1]}\n";
}