<?php
/**
 * Script to enable required PHP extensions in php.ini
 */
$phpIni = 'C:\\laragon\\bin\\php\\php-8.3.30-Win32-vs16-x64\\php.ini';
$extDir = 'C:\\laragon\\bin\\php\\php-8.3.30-Win32-vs16-x64\\ext';

if (!file_exists($phpIni)) {
    die("php.ini not found\n");
}

$content = file_get_contents($phpIni);
$original = $content;

// Replace extension_dir
$pattern = '/^;\s*extension_dir\s*=.*$/m';
if (preg_match($pattern, $content)) {
    $content = preg_replace($pattern, 'extension_dir = "' . $extDir . '"', $content);
    echo "Set extension_dir\n";
}

// Enable required extensions
$extensions = ['curl', 'gd', 'mbstring', 'mysqli', 'openssl', 'pdo_mysql', 'xml', 'zip', 'intl', 'soap', 'exif', 'opcache'];
foreach ($extensions as $ext) {
    $pattern = '/^;\s*extension\s*=\s*' . preg_quote($ext, '/') . '\s*$/m';
    $replacement = 'extension=' . $ext;
    if (preg_match($pattern, $content)) {
        $content = preg_replace($pattern, $replacement, $content, 1);
        echo "Enabled: $ext\n";
    }
}

// Set recommended php settings
$settings = [
    'post_max_size' => '128M',
    'upload_max_filesize' => '128M',
    'max_execution_time' => '300',
    'memory_limit' => '512M',
    'max_input_vars' => '3000',
];

foreach ($settings as $key => $value) {
    $pattern = '/^' . preg_quote($key, '/') . '\s*=.*$/m';
    if (preg_match($pattern, $content, $matches)) {
        $content = preg_replace($pattern, $key . ' = ' . $value, $content, 1);
        echo "Set $key = $value\n";
    }
}

if ($content !== $original) {
    file_put_contents($phpIni, $content);
    echo "\nphp.ini updated!\n";
} else {
    echo "\nNo changes needed\n";
}