<?php
echo "<h1>Subdomain Routing Test</h1>";
echo "<pre>";

echo "=== BASIC ENVIRONMENT ===\n";
echo "HTTP_HOST: " . $_SERVER['HTTP_HOST'] . "\n";
echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "\n";
echo "DOCUMENT_ROOT: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo "Current working directory: " . getcwd() . "\n";
echo "\n";

echo "=== SUBDOMAIN DETECTION ===\n";
$host = $_SERVER['HTTP_HOST'];
$is_subdomain = strpos($host, 'visit-') === 0;
echo "Is subdomain (visit-*): " . ($is_subdomain ? "YES" : "NO") . "\n";

if ($is_subdomain) {
    if (preg_match('/^visit-([a-z]{2,3})\./', $host, $matches)) {
        echo "Country code extracted: " . $matches[1] . "\n";
    }
}

echo "\n=== SESSION DATA ===\n";
if (isset($_SESSION['subdomain_country_name'])) {
    echo "Subdomain country: " . $_SESSION['subdomain_country_name'] . "\n";
    echo "Country ID: " . $_SESSION['subdomain_country_id'] . "\n";
    echo "Country code: " . $_SESSION['subdomain_country_code'] . "\n";
} else {
    echo "No subdomain session data found\n";
}

echo "\n=== CONSTANTS ===\n";
if (defined('COUNTRY_SUBDOMAIN')) {
    echo "COUNTRY_SUBDOMAIN: " . (COUNTRY_SUBDOMAIN ? 'true' : 'false') . "\n";
    echo "CURRENT_COUNTRY_NAME: " . CURRENT_COUNTRY_NAME . "\n";
} else {
    echo "No country subdomain constants defined\n";
}

echo "\n=== FILE SYSTEM TEST ===\n";
$test_files = [
    'config/database.php',
    'subdomain-handler.php',
    'countries/rwanda/pages/tour-detail.php',
    'uploads/tours/29_cover_1763240404_7030.png'
];

foreach ($test_files as $file) {
    echo "$file: " . (file_exists($file) ? "✅ EXISTS" : "❌ NOT FOUND") . "\n";
}

echo "\n=== IMAGE PATH TESTS ===\n";
$test_image = 'uploads/tours/29_cover_1763240404_7030.png';

echo "Direct path: $test_image\n";
echo "  Exists: " . (file_exists($test_image) ? "✅ YES" : "❌ NO") . "\n";

echo "Relative path: ../../../$test_image\n";
echo "  Exists: " . (file_exists("../../../$test_image") ? "✅ YES" : "❌ NO") . "\n";

echo "Absolute web path: /foreveryoungtours/$test_image\n";
echo "  (Cannot test file existence for web paths)\n";

echo "</pre>";

echo "<h2>🔍 DIAGNOSIS</h2>";
echo "<ul>";
if ($is_subdomain) {
    echo "<li>✅ <strong>Subdomain detected:</strong> " . $host . "</li>";
    if (defined('COUNTRY_SUBDOMAIN')) {
        echo "<li>✅ <strong>Subdomain handler working:</strong> Country context set</li>";
    } else {
        echo "<li>❌ <strong>Subdomain handler not working:</strong> No country context</li>";
    }
} else {
    echo "<li>ℹ️ <strong>Main domain:</strong> " . $host . "</li>";
}
echo "</ul>";

echo "<h2>🎯 TEST URLS</h2>";
echo "<ul>";
echo "<li><strong>Main Domain:</strong> <a href='http://localhost/foreveryoungtours/test-subdomain-routing-simple.php'>http://localhost/foreveryoungtours/test-subdomain-routing-simple.php</a></li>";
echo "<li><strong>Subdomain:</strong> <a href='http://visit-rw.foreveryoungtours.local/test-subdomain-routing-simple.php'>http://visit-rw.foreveryoungtours.local/test-subdomain-routing-simple.php</a></li>";
echo "</ul>";

echo "<h2>📋 NEXT STEPS</h2>";
echo "<ol>";
echo "<li>Test both URLs above</li>";
echo "<li>Compare the results</li>";
echo "<li>Check if subdomain handler is being called</li>";
echo "<li>Verify Apache virtual host configuration</li>";
echo "</ol>";
?>
