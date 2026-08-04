<?php
require_once 'C:\laragon\www\uniasia\wp-load.php';
echo "Active theme: " . wp_get_theme()->get('Name') . "\n";
echo "Stylesheet dir: " . get_stylesheet_directory() . "\n";
echo "Stylesheet uri: " . get_stylesheet_directory_uri() . "\n";
echo "\nFunctions exists check:\n";
echo "  uniasia_setup: " . (function_exists('uniasia_setup') ? 'YES' : 'NO') . "\n";
echo "  uniasia_scripts: " . (function_exists('uniasia_scripts') ? 'YES' : 'NO') . "\n";
echo "  get_field (ACF): " . (function_exists('get_field') ? 'YES' : 'NO') . "\n";
echo "\nEnqueue check:\n";
$wp_styles = wp_styles();
echo "  main.css registered: " . (isset($wp_styles->registered['uniasia-main']) ? 'YES' : 'NO') . "\n";
echo "  responsive.css registered: " . (isset($wp_styles->registered['uniasia-responsive']) ? 'YES' : 'NO') . "\n";
echo "  google-fonts registered: " . (isset($wp_styles->registered['uniasia-google-fonts']) ? 'YES' : 'NO') . "\n";
if (isset($wp_styles->registered['uniasia-main'])) {
    $src = $wp_styles->registered['uniasia-main']->src;
    echo "  main.css src: $src\n";
}