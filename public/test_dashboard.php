<?php
echo "Testing dashboard (requires auth)...\n";

$start = microtime(true);
$ch = curl_init('http://localhost:8080/dashboard');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false); // Don't follow redirects
$result = curl_exec($ch);
$time = microtime(true) - $start;
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $code\n";
echo "Load time: " . number_format($time * 1000, 2) . "ms\n";

if ($code == 302 || $code == 301) {
    echo "✓ Correctly redirecting to login (not authenticated)\n";
} elseif ($code == 200) {
    echo "✓ Dashboard loaded (user is authenticated)\n";
} else {
    echo "✗ Unexpected response code\n";
}
