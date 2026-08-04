<?php
require_once 'C:\laragon\www\uniasia\wp-load.php';

echo "===== Configured page_on_front =====\n";
echo get_option('page_on_front') . "\n";
echo get_option('show_on_front') . "\n\n";

$page = get_post(42);
echo "Page ID 42: " . ($page ? $page->post_title : 'NULL') . "\n";
echo "Template: " . ($page ? get_page_template_slug(42) : 'n/a') . "\n\n";

echo "===== Check which template WP uses for homepage =====\n";
echo "Front page template: " . get_template_part('template-parts/section', 'hero', false) . "\n\n";

echo "===== Body class check via WP =====\n";
$url = 'http://127.0.0.1:9000/';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$html = curl_exec($ch);
curl_close($ch);

preg_match('/<body class="([^"]+)"/', $html, $m);
echo "Body: " . ($m[1] ?? 'n/a') . "\n\n";

echo "===== First 3000 chars of <main> =====\n";
if (preg_match('/<main id="main-content"[^>]*>(.*?)<\/main>/is', $html, $m)) {
    echo substr($m[1], 0, 3000);
} else {
    echo "Main not found\n";
}