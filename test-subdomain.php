<?php
// Test subdomain detection
$host = $_SERVER['HTTP_HOST'];
echo "Host: " . $host . "<br>";

if (preg_match('/^visit-([a-z]{3})\\./', $host, $matches)) {
    echo "Country Code Detected: " . strtoupper($matches[1]) . "<br>";
    echo "Subdomain working!";
} else {
    echo "No subdomain detected<br>";
    echo "Pattern not matching";
}
?>
