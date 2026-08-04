<?php
/**
 * Fix menu items - update URLs and titles
 */
require_once 'C:\laragon\www\uniasia\wp-load.php';

$menu = wp_get_nav_menu_object('Menu chính - Tiếng Việt');
if (!$menu) {
    die("Menu not found\n");
}

$items = wp_get_nav_menu_items($menu->term_id);

echo "=== Fixing menu items ===\n";

$menu_data = [
    [
        'title' => 'Trang chủ',
        'url'   => home_url('/'),
    ],
    [
        'title' => 'Giới thiệu',
        'url'   => home_url('/about-us/'),
    ],
    [
        'title' => 'Bác sĩ',
        'url'   => home_url('/doctors/'),
    ],
    [
        'title' => 'Kỹ thuật',
        'url'   => home_url('/technologies/'),
    ],
    [
        'title' => 'Câu chuyện',
        'url'   => home_url('/patient-stories/'),
    ],
    [
        'title' => 'FAQ',
        'url'   => home_url('/faqs/'),
    ],
    [
        'title' => 'Liên hệ',
        'url'   => '#contact-form',
    ],
];

// Update each item
foreach ($items as $index => $item) {
    if (isset($menu_data[$index])) {
        $new = $menu_data[$index];
        $updated = wp_update_nav_menu_item($menu->term_id, $item->ID, [
            'menu-item-title'  => $new['title'],
            'menu-item-url'    => $new['url'],
            'menu-item-status' => 'publish',
            'menu-item-type'   => 'custom',
        ]);
        if (!is_wp_error($updated)) {
            echo "  [OK] Item $index -> {$new['title']} -> {$new['url']}\n";
        } else {
            echo "  [FAIL] $index: " . $updated->get_error_message() . "\n";
        }
    }
}

// Verify
echo "\n=== After fix ===\n";
$items = wp_get_nav_menu_items($menu->term_id);
foreach ($items as $item) {
    echo "  [{$item->ID}] {$item->title} -> {$item->url}\n";
}