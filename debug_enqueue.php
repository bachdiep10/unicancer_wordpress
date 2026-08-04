<?php
require_once 'C:\laragon\www\uniasia\wp-load.php';

// Force run enqueue
echo "WP_DEBUG: " . (defined('WP_DEBUG') && WP_DEBUG ? 'YES' : 'NO') . "\n";
echo "WP_ENV: " . (defined('WP_ENV') ? WP_ENV : 'n/a') . "\n";
echo "Script debug: " . (defined('SCRIPT_DEBUG') ? SCRIPT_DEBUG : 'n/a') . "\n";

// Check wp_styles
global $wp_styles;

echo "\n=== Before wp_head ===\n";
echo "Styles registered count: " . count($wp_styles->registered) . "\n";
echo "Has uniasia-main: " . (isset($wp_styles->registered['uniasia-main']) ? 'YES' : 'NO') . "\n";

// Run wp_head
ob_start();
wp_head();
$head = ob_get_clean();

echo "\n=== After wp_head ===\n";
echo "Styles registered count: " . count($wp_styles->registered) . "\n";
echo "Has uniasia-main: " . (isset($wp_styles->registered['uniasia-main']) ? 'YES' : 'NO') . "\n";

// Check queues
echo "\nQueues: ";
print_r($wp_styles->queue);

// Look in head HTML
echo "\n\n=== uniasia links in head ===\n";
preg_match_all('/<link[^>]+uniasia[^>]*>/i', $head, $m);
echo count($m[0]) . " uniasia links\n";
foreach ($m[0] as $link) {
    echo "  $link\n";
}