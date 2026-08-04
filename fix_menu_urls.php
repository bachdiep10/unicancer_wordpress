<?php
/**
 * Fix all menu URLs: localhost -> 127.0.0.1:9000
 */
define('WP_USE_THEMES', false);
require __DIR__ . '/../../laragon/www/uniasia/wp-load.php';

$old_host = 'http://localhost/uniasia';
$new_host = 'http://127.0.0.1:9000/uniasia';

$menus = wp_get_nav_menus();
$fixed = 0;

foreach ($menus as $m) {
    $items = wp_get_nav_menu_items($m->term_id);
    foreach ($items as $it) {
        if (strpos($it->url, $old_host) === 0) {
            $new_url = str_replace($old_host, $new_host, $it->url);
            $result = wp_update_nav_menu_item($m->term_id, $it->ID, array(
                'menu-item-url' => $new_url,
                'menu-item-status' => 'publish',
            ));
            echo "Fixed: {$it->url} -> {$new_url}\n";
            $fixed++;
        }
    }
}

// Also check siteurl / home
echo "\nCurrent siteurl: " . get_option('siteurl') . "\n";
echo "Current home: " . get_option('home') . "\n";

if ($fixed > 0) {
    echo "\n$fixed menu items fixed. Now testing...\n";

    // Re-test
    foreach ($items as $it) {
        echo "  - {$it->title} -> {$it->url}\n";
    }
}

echo "\nDone.\n";