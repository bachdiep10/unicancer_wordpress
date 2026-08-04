<?php
$css = file_get_contents('C:\unicancer\original-css\Layout.css');
echo "Total CSS size: " . strlen($css) . " chars\n\n";

// Extract first 2000 chars
echo "FIRST 2000 CHARS:\n";
echo substr($css, 0, 2000) . "\n\n";

// Last 1500
echo "LAST 1500 CHARS:\n";
echo substr($css, -1500) . "\n";

// Extract class names that appear most often
preg_match_all('/\.([a-zA-Z][a-zA-Z0-9_-]*)/', $css, $m);
$counts = array_count_values($m[1]);
arsort($counts);
echo "\nTOP 30 CLASSES by frequency:\n";
$i = 0;
foreach ($counts as $cls => $cnt) {
    if ($cnt > 3) {
        echo "  .$cls = $cnt\n";
        $i++;
        if ($i > 30) break;
    }
}