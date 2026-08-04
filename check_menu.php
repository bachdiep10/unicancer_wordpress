<?php
require_once 'C:\laragon\www\uniasia\wp-load.php';

echo "=== Menu items check ===\n";
$menus = wp_get_nav_menus();
foreach ($menus as $menu) {
    echo "\nMenu: {$menu->name} (term_id: {$menu->term_id})\n";
    $items = wp_get_nav_menu_items($menu->term_id);
    foreach ($items as $item) {
        echo "  [{$item->ID}] {$item->title} -> {$item->url}\n";
    }
}

echo "\n=== Site URL config ===\n";
echo "home_url: " . home_url() . "\n";
echo "site_url: " . site_url() . "\n";
echo "option siteurl: " . get_option('siteurl') . "\n";
echo "option home: " . get_option('home') . "\n";