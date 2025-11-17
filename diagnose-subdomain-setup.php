<?php
echo "<h1>🔍 Subdomain Setup Diagnosis</h1>";

echo "<h2>1. Current Environment</h2>";
echo "<table border='1' cellpadding='5'>";
echo "<tr><td><strong>HTTP_HOST</strong></td><td>" . $_SERVER['HTTP_HOST'] . "</td></tr>";
echo "<tr><td><strong>REQUEST_URI</strong></td><td>" . $_SERVER['REQUEST_URI'] . "</td></tr>";
echo "<tr><td><strong>SCRIPT_NAME</strong></td><td>" . $_SERVER['SCRIPT_NAME'] . "</td></tr>";
echo "<tr><td><strong>DOCUMENT_ROOT</strong></td><td>" . $_SERVER['DOCUMENT_ROOT'] . "</td></tr>";
echo "</table>";

echo "<h2>2. Subdomain Detection</h2>";
$host = $_SERVER['HTTP_HOST'];
$is_subdomain = strpos($host, 'visit-') === 0;
$is_foreveryoung_domain = strpos($host, 'foreveryoungtours.local') !== false;

echo "<table border='1' cellpadding='5'>";
echo "<tr><td><strong>Is subdomain (visit-*)</strong></td><td>" . ($is_subdomain ? "✅ YES" : "❌ NO") . "</td></tr>";
echo "<tr><td><strong>Is foreveryoungtours.local domain</strong></td><td>" . ($is_foreveryoung_domain ? "✅ YES" : "❌ NO") . "</td></tr>";
echo "</table>";

echo "<h2>3. Apache Rewrite Test</h2>";
if ($is_subdomain && $is_foreveryoung_domain) {
    echo "<p>✅ <strong>Subdomain detected correctly!</strong></p>";
    echo "<p>Apache rewrite rules are working.</p>";
} elseif ($is_subdomain) {
    echo "<p>⚠️ <strong>Subdomain detected but wrong domain.</strong></p>";
    echo "<p>Expected: visit-rw.foreveryoungtours.local</p>";
    echo "<p>Got: " . $host . "</p>";
} else {
    echo "<p>ℹ️ <strong>Main domain access.</strong></p>";
    echo "<p>This is normal for: localhost/foreveryoungtours/</p>";
}

echo "<h2>4. Session & Constants Check</h2>";
session_start();
echo "<table border='1' cellpadding='5'>";
echo "<tr><td><strong>Subdomain session data</strong></td><td>" . (isset($_SESSION['subdomain_country_name']) ? "✅ " . $_SESSION['subdomain_country_name'] : "❌ Not set") . "</td></tr>";
echo "<tr><td><strong>COUNTRY_SUBDOMAIN constant</strong></td><td>" . (defined('COUNTRY_SUBDOMAIN') ? "✅ " . (COUNTRY_SUBDOMAIN ? 'true' : 'false') : "❌ Not defined") . "</td></tr>";
echo "</table>";

echo "<h2>5. File System Check</h2>";
$critical_files = [
    '.htaccess' => '.htaccess',
    'subdomain-handler.php' => 'subdomain-handler.php',
    'config/database.php' => 'config/database.php',
    'countries/rwanda/pages/tour-detail.php' => 'countries/rwanda/pages/tour-detail.php'
];

echo "<table border='1' cellpadding='5'>";
foreach ($critical_files as $desc => $file) {
    echo "<tr><td><strong>$desc</strong></td><td>" . (file_exists($file) ? "✅ EXISTS" : "❌ MISSING") . "</td></tr>";
}
echo "</table>";

echo "<h2>6. Image Path Test</h2>";
$test_image = 'uploads/tours/29_cover_1763240404_7030.png';
echo "<table border='1' cellpadding='5'>";
echo "<tr><td><strong>Test image</strong></td><td>$test_image</td></tr>";
echo "<tr><td><strong>Direct path exists</strong></td><td>" . (file_exists($test_image) ? "✅ YES" : "❌ NO") . "</td></tr>";
echo "<tr><td><strong>Relative path exists</strong></td><td>" . (file_exists("../../../$test_image") ? "✅ YES" : "❌ NO") . "</td></tr>";
echo "</table>";

echo "<h2>7. Setup Status</h2>";
$setup_complete = true;
$issues = [];

if (!$is_foreveryoung_domain && strpos($host, 'localhost') === false) {
    $setup_complete = false;
    $issues[] = "Domain not configured in hosts file";
}

if ($is_subdomain && !defined('COUNTRY_SUBDOMAIN')) {
    $setup_complete = false;
    $issues[] = "Subdomain handler not working";
}

if (!file_exists('.htaccess')) {
    $setup_complete = false;
    $issues[] = ".htaccess file missing";
}

if ($setup_complete) {
    echo "<p style='color: green; font-size: 18px;'>✅ <strong>Setup appears to be working correctly!</strong></p>";
} else {
    echo "<p style='color: red; font-size: 18px;'>❌ <strong>Setup issues detected:</strong></p>";
    echo "<ul>";
    foreach ($issues as $issue) {
        echo "<li style='color: red;'>$issue</li>";
    }
    echo "</ul>";
}

echo "<h2>8. Next Steps</h2>";
if ($setup_complete) {
    echo "<ol>";
    echo "<li><strong>Test tour detail page:</strong> <a href='http://visit-rw.foreveryoungtours.local/pages/tour-detail?id=29'>http://visit-rw.foreveryoungtours.local/pages/tour-detail?id=29</a></li>";
    echo "<li><strong>Check browser console</strong> for any image loading errors</li>";
    echo "<li><strong>Compare with main domain:</strong> <a href='http://localhost/foreveryoungtours/pages/tour-detail?id=29'>http://localhost/foreveryoungtours/pages/tour-detail?id=29</a></li>";
    echo "</ol>";
} else {
    echo "<ol>";
    echo "<li><strong>Follow setup instructions:</strong> See SUBDOMAIN_SETUP_INSTRUCTIONS.md</li>";
    echo "<li><strong>Add to hosts file:</strong> 127.0.0.1 visit-rw.foreveryoungtours.local</li>";
    echo "<li><strong>Configure XAMPP virtual host</strong></li>";
    echo "<li><strong>Restart Apache</strong></li>";
    echo "<li><strong>Test again</strong></li>";
    echo "</ol>";
}

echo "<h2>9. Test URLs</h2>";
echo "<ul>";
echo "<li><strong>Main Domain:</strong> <a href='http://localhost/foreveryoungtours/diagnose-subdomain-setup.php'>http://localhost/foreveryoungtours/diagnose-subdomain-setup.php</a></li>";
echo "<li><strong>Subdomain:</strong> <a href='http://visit-rw.foreveryoungtours.local/diagnose-subdomain-setup.php'>http://visit-rw.foreveryoungtours.local/diagnose-subdomain-setup.php</a></li>";
echo "</ul>";
?>
