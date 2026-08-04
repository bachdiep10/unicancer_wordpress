<?php
/**
 * Inspect all menus and what URLs they contain
 */
define('WP_USE_THEMES', false);
require __DIR__ . '/../../laragon/www/uniasia/wp-load.php';

$menus = wp_get_nav_menus();
$locations = get_theme_mod('nav_menu_locations');
echo "=== Menus in DB ===\n";
foreach ($menus as $m) {
    $assigned = [];
    if (is_array($locations)) {
        foreach ($locations as $loc => $mid) {
            if ($mid == $m->term_id) $assigned[] = $loc;
        }
    }
    echo "Menu: {$m->name} (id={$m->term_id}, slug: {$m->slug}, locations: " . implode(',', $assigned) . ")\n";

    $items = wp_get_nav_menu_items($m->term_id);
    foreach ($items as $it) {
        echo "  - [{$it->type}] {$it->title} -> {$it->url}\n";
    }
    echo "\n";
}

echo "\n=== Post Type slugs (rewrite) ===\n";
foreach (get_post_types([], 'objects') as $pt) {
    if ($pt->public && !in_array($pt->name, ['attachment', 'revision', 'nav_menu_item'])) {
        $slug = ($pt->rewrite && isset($pt->rewrite['slug'])) ? $pt->rewrite['slug'] : $pt->name;
        echo "  {$pt->name} -> /{$slug}/\n";
    }
}

$locs = get_theme_mod('nav_menu_locations');
if ($locs && is_array($locs)) {
    foreach ($locs as $loc => $mid) {
        echo "  $loc => menu_id=$mid\n";
    }
}