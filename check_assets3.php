<?php
$ch = curl_init('http://127.0.0.1:9000/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$curlHeaders = array();
curl_setopt($ch, CURLOPT_HEADERFUNCTION,
    function($curl, $header) use (&$curlHeaders) {
        $len = strlen($header);
        $colon = strpos($header, ':');
        if ($colon !== false) {
            $name = trim(substr($header, 0, $colon));
            $value = trim(substr($header, $colon + 1, $len - $colon - 2));
            $curlHeaders[$name] = $value;
        }
        return $len;
    }
);
$html = curl_exec($ch);
curl_close($ch);

echo "Response headers:\n";
foreach ($curlHeaders as $k => $v) {
    echo "  $k: $v\n";
}

echo "\n=== First 1500 chars of <head> ===\n";
if (preg_match('/<head[^>]*>(.*?)<\/head>/is', $html, $m)) {
    $head = $m[1];
    echo substr($head, 0, 1500);
    echo "\n\nLast 500 chars of <head>:\n";
    echo substr($head, -500);
}