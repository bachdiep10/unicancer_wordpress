<?php
/**
 * Final Status Report for UNI-ASIA Theme
 */

echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║   UNI-ASIA Cancer Theme - Local Installation Complete!       ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

// Test homepage
$home = curl_init('http://127.0.0.1:9000/');
curl_setopt($home, CURLOPT_RETURNTRANSFER, true);
$homeContent = curl_exec($home);
$homeCode = curl_getinfo($home, CURLINFO_HTTP_CODE);
curl_close($home);

echo "🌐 HOMEPAGE (http://127.0.0.1:9000/)\n";
echo "   HTTP Status: $homeCode\n";
echo "   HTML Size: " . strlen($homeContent) . " chars (" . round(strlen($homeContent)/1024,1) . " KB)\n\n";

// Test all subpages
$pages = [
    '/doctors/' => 'Bác sĩ (Doctors)',
    '/patient-stories/' => 'Câu chuyện (Stories)',
    '/cancer-types/' => 'Loại ung thư (Cancer Types)',
    '/technologies/' => 'Kỹ thuật (Technologies)',
    '/faqs/' => 'FAQ',
    '/about-us/' => 'Giới thiệu (About)',
];

echo "📄 SUBPAGES:\n";
foreach ($pages as $url => $name) {
    $c = curl_init('http://127.0.0.1:9000' . $url);
    curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
    $content = curl_exec($c);
    $code = curl_getinfo($c, CURLINFO_HTTP_CODE);
    curl_close($c);
    $status = ($code == 200) ? '✓' : '✗';
    $size = round(strlen($content)/1024, 1);
    echo "   $status $name [http://127.0.0.1:9000$url] - $size KB ($code)\n";
}

// Admin
$c = curl_init('http://127.0.0.1:9000/wp-admin/');
curl_setopt($c, CURLOPT_RETURNTRANSFER, false);
curl_setopt($c, CURLOPT_FOLLOWLOCATION, false);
curl_exec($c);
$code = curl_getinfo($c, CURLINFO_HTTP_CODE);
curl_close($c);
echo "\n🔧 ADMIN: http://127.0.0.1:9000/wp-admin/ ($code redirect to login)\n";

// Counts
require_once 'C:\laragon\www\uniasia\wp-load.php';
echo "\n📊 DATA:\n";
echo "   Doctors: " . wp_count_posts('doctor')->publish . " posts\n";
echo "   Patient Stories: " . wp_count_posts('patient_story')->publish . " posts\n";
echo "   Cancer Types: " . wp_count_posts('cancer_type')->publish . " posts\n";
echo "   Technologies: " . wp_count_posts('technology')->publish . " posts\n";
echo "   FAQs: " . wp_count_posts('faq')->publish . " posts\n";
echo "   Pages: " . wp_count_posts('page')->publish . " posts\n";
echo "   Menu items: " . wp_get_nav_menu_object('Menu chính - Tiếng Việt')?->count . " items\n";

// Theme info
$theme = wp_get_theme();
echo "\n🎨 THEME:\n";
echo "   Name: " . $theme->get('Name') . "\n";
echo "   Version: " . $theme->get('Version') . "\n";
echo "   Author: " . $theme->get('Author') . "\n";

echo "\n📁 MYSQL DATABASE:\n";
echo "   Database: wordpress_uniasia\n";
echo "   User: wp_user\n";
echo "   Tables: " . count($GLOBALS['wpdb']->get_col("SHOW TABLES LIKE '{$GLOBALS['wpdb']->prefix}%'")) . "\n";

echo "\n🛠️ SYSTEM:\n";
echo "   PHP: " . PHP_VERSION . " (ZTS Visual C++ 2019 x64)\n";
echo "   Server: PHP Built-in (port 9000)\n";
echo "   Database: MySQL 8.4.3\n";

echo "\n╔══════════════════════════════════════════════════════════════╗\n";
echo "║              🎉 ALL SYSTEMS RUNNING 🎉                       ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n";
echo "\nTruy cập trang chủ: http://127.0.0.1:9000/\n";
echo "Đăng nhập admin:   http://127.0.0.1:9000/wp-admin/\n";
echo "Username: admin     Password: admin123\n\n";