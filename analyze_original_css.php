<?php
$css = file_get_contents('C:\unicancer\original-css\Layout.css');
// Find specific sections
$patterns = [
    'header' => '/[^{}]*\.site-header[^{}]*\{[^}]+\}/i',
    'top-bar' => '/[^{}]*\.top-bar[^{}]*\{[^}]+\}/i',
    'nav-menu' => '/[^{}]*\.nav-menu[^{}]*\{[^}]+\}/i',
    'hero' => '/[^{}]*\.hero[^{}]*\{[^}]+\}/i',
    'section' => '/[^{}]*\.section[^{}]*\{[^}]+\}/i',
    'container' => '/[^{}]*\.container[^{}]*\{[^}]+\}/i',
];

foreach ($patterns as $name => $p) {
    echo "\n=== $name ===\n";
    preg_match_all($p, $css, $m);
    foreach ($m[0] as $rule) {
        echo $rule . "\n";
    }
    if (empty($m[0])) echo "(not found)\n";
}

// CSS variables (root)
echo "\n=== :root variables ===\n";
preg_match('/:root\s*\{[^}]+\}/s', $css, $m);
if ($m) {
    echo substr($m[0], 0, 2000) . "\n";
}