<?php
// Subdomain Test File - Upload this to your Hostinger root directory
echo "<h1>Subdomain Test</h1>";
echo "<p><strong>Current Host:</strong> " . $_SERVER['HTTP_HOST'] . "</p>";
echo "<p><strong>Request URI:</strong> " . $_SERVER['REQUEST_URI'] . "</p>";
echo "<p><strong>Server Name:</strong> " . $_SERVER['SERVER_NAME'] . "</p>";
echo "<p><strong>Document Root:</strong> " . $_SERVER['DOCUMENT_ROOT'] . "</p>";

// Check if subdomain is detected
$host = $_SERVER['HTTP_HOST'];
if (preg_match('/^visit-([a-z]{2})\.iforeveryoungtours\.com$/', $host, $matches)) {
    echo "<p style='color: green;'><strong>✓ Country Subdomain Detected:</strong> " . strtoupper($matches[1]) . "</p>";
} elseif (preg_match('/^(africa|asia|europe|caribbean|north-america|south-america)\.iforeveryoungtours\.com$/', $host, $matches)) {
    echo "<p style='color: green;'><strong>✓ Continent Subdomain Detected:</strong> " . ucfirst($matches[1]) . "</p>";
} elseif ($host === 'iforeveryoungtours.com' || $host === 'www.iforeveryoungtours.com') {
    echo "<p style='color: blue;'><strong>Main Domain</strong></p>";
} else {
    echo "<p style='color: red;'><strong>✗ Unknown Domain:</strong> " . $host . "</p>";
}

// Check GET parameters
if (!empty($_GET)) {
    echo "<h3>GET Parameters:</h3>";
    echo "<pre>" . print_r($_GET, true) . "</pre>";
}

// Check if .htaccess is working
if (function_exists('apache_get_modules')) {
    $modules = apache_get_modules();
    echo "<h3>Apache Modules:</h3>";
    echo "<p>mod_rewrite: " . (in_array('mod_rewrite', $modules) ? '✓ Enabled' : '✗ Disabled') . "</p>";
}

echo "<hr>";
echo "<h3>Instructions:</h3>";
echo "<ol>";
echo "<li>Upload this file to your Hostinger public_html directory</li>";
echo "<li>Access it via: https://visit-rw.iforeveryoungtours.com/subdomain-test.php</li>";
echo "<li>Check if subdomain is detected correctly</li>";
echo "<li>If it shows 'Main Domain' instead, your DNS is redirecting</li>";
echo "</ol>";
?>
