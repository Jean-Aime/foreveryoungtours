<?php
/**
 * Test Home URL Rewriting
 * 
 * This script tests if the /Home URL rewriting is working correctly
 */

echo "<h1>🔗 Testing Home URL Rewriting</h1>\n";
echo "<style>body{font-family:Arial,sans-serif;margin:20px;} .success{color:#4caf50;} .error{color:#f44336;} .info{color:#2196f3;} .warning{color:#ff9800;}</style>\n";

echo "<h2>📊 URL Rewriting Test</h2>\n";

// Check if .htaccess exists
if (file_exists('.htaccess')) {
    echo "<p class='success'>✅ .htaccess file exists</p>\n";
    
    // Read .htaccess content
    $htaccessContent = file_get_contents('.htaccess');
    
    // Check for Home rewrite rule
    if (strpos($htaccessContent, 'RewriteRule ^Home') !== false) {
        echo "<p class='success'>✅ Home rewrite rule found in .htaccess</p>\n";
    } else {
        echo "<p class='error'>❌ Home rewrite rule NOT found in .htaccess</p>\n";
    }
    
    // Check for index.php redirect rule
    if (strpos($htaccessContent, 'index\.php') !== false && strpos($htaccessContent, '/Home') !== false) {
        echo "<p class='success'>✅ index.php redirect rule found in .htaccess</p>\n";
    } else {
        echo "<p class='warning'>⚠️ index.php redirect rule may not be configured</p>\n";
    }
    
    // Display the relevant rules
    echo "<h3>📄 Relevant .htaccess Rules</h3>\n";
    echo "<pre style='background:#f5f5f5;padding:15px;border-radius:5px;overflow-x:auto;'>";
    $lines = explode("\n", $htaccessContent);
    foreach ($lines as $line) {
        if (stripos($line, 'home') !== false || stripos($line, 'index.php') !== false) {
            echo htmlspecialchars($line) . "\n";
        }
    }
    echo "</pre>\n";
    
} else {
    echo "<p class='error'>❌ .htaccess file does not exist</p>\n";
}

echo "<h2>🧪 Test Links</h2>\n";
echo "<p class='info'>Click these links to test the URL rewriting:</p>\n";
echo "<div style='background:#f0f9ff;padding:20px;border-radius:8px;margin:20px 0;'>\n";
echo "<h3 style='margin-top:0;'>✅ Recommended URLs (Clean URLs)</h3>\n";
echo "<ul style='list-style:none;padding:0;'>\n";
echo "<li style='margin:10px 0;'><a href='/foreveryoungtours/Home' target='_blank' style='color:#2563eb;text-decoration:none;font-weight:bold;'>🏠 /foreveryoungtours/Home</a> <span style='color:#059669;'>← Use this!</span></li>\n";
echo "<li style='margin:10px 0;'><a href='/foreveryoungtours/home' target='_blank' style='color:#2563eb;text-decoration:none;font-weight:bold;'>🏠 /foreveryoungtours/home</a> <span style='color:#6b7280;'>(lowercase, should also work)</span></li>\n";
echo "</ul>\n";
echo "</div>\n";

echo "<div style='background:#fef3c7;padding:20px;border-radius:8px;margin:20px 0;'>\n";
echo "<h3 style='margin-top:0;'>⚠️ Old URLs (Should Redirect)</h3>\n";
echo "<ul style='list-style:none;padding:0;'>\n";
echo "<li style='margin:10px 0;'><a href='/foreveryoungtours/index.php' target='_blank' style='color:#d97706;text-decoration:none;font-weight:bold;'>📄 /foreveryoungtours/index.php</a> <span style='color:#6b7280;'>(should redirect to /Home)</span></li>\n";
echo "</ul>\n";
echo "</div>\n";

echo "<h2>🔍 Current Request Information</h2>\n";
echo "<table style='border-collapse:collapse;width:100%;max-width:600px;'>\n";
echo "<tr style='background:#f5f5f5;'><td style='padding:10px;border:1px solid #ddd;font-weight:bold;'>Request URI</td><td style='padding:10px;border:1px solid #ddd;'>" . htmlspecialchars($_SERVER['REQUEST_URI'] ?? 'N/A') . "</td></tr>\n";
echo "<tr><td style='padding:10px;border:1px solid #ddd;font-weight:bold;'>Script Name</td><td style='padding:10px;border:1px solid #ddd;'>" . htmlspecialchars($_SERVER['SCRIPT_NAME'] ?? 'N/A') . "</td></tr>\n";
echo "<tr style='background:#f5f5f5;'><td style='padding:10px;border:1px solid #ddd;font-weight:bold;'>HTTP Host</td><td style='padding:10px;border:1px solid #ddd;'>" . htmlspecialchars($_SERVER['HTTP_HOST'] ?? 'N/A') . "</td></tr>\n";
echo "<tr><td style='padding:10px;border:1px solid #ddd;font-weight:bold;'>Server Name</td><td style='padding:10px;border:1px solid #ddd;'>" . htmlspecialchars($_SERVER['SERVER_NAME'] ?? 'N/A') . "</td></tr>\n";
echo "</table>\n";

echo "<h2>📋 Instructions</h2>\n";
echo "<div style='background:#f0fdf4;padding:20px;border-radius:8px;border-left:4px solid #10b981;'>\n";
echo "<h3 style='margin-top:0;color:#065f46;'>✅ What's Been Done</h3>\n";
echo "<ol style='color:#047857;'>\n";
echo "<li>Added URL rewrite rule to redirect <code>/Home</code> to <code>index.php</code></li>\n";
echo "<li>Added redirect rule to redirect <code>index.php</code> requests to <code>/Home</code></li>\n";
echo "<li>Updated navigation links in <code>pages/header.php</code> to use <code>/Home</code></li>\n";
echo "<li>Main header (<code>includes/header.php</code>) already uses <code>home</code> (lowercase)</li>\n";
echo "</ol>\n";
echo "</div>\n";

echo "<div style='background:#fef2f2;padding:20px;border-radius:8px;border-left:4px solid #ef4444;margin-top:20px;'>\n";
echo "<h3 style='margin-top:0;color:#991b1b;'>⚠️ Important Notes</h3>\n";
echo "<ul style='color:#7f1d1d;'>\n";
echo "<li>Make sure Apache's <code>mod_rewrite</code> module is enabled in XAMPP</li>\n";
echo "<li>Make sure <code>AllowOverride All</code> is set in your Apache configuration</li>\n";
echo "<li>You may need to restart Apache after making .htaccess changes</li>\n";
echo "<li>The URL rewriting is case-insensitive, so both <code>/Home</code> and <code>/home</code> will work</li>\n";
echo "</ul>\n";
echo "</div>\n";

echo "<h2>🎯 Next Steps</h2>\n";
echo "<ol>\n";
echo "<li>Click the test links above to verify the URL rewriting works</li>\n";
echo "<li>If <code>/foreveryoungtours/Home</code> doesn't work, check Apache's mod_rewrite is enabled</li>\n";
echo "<li>If <code>/foreveryoungtours/index.php</code> doesn't redirect, check the .htaccess file</li>\n";
echo "<li>Update any other internal links throughout the site to use <code>/Home</code> instead of <code>index.php</code></li>\n";
echo "</ol>\n";
?>
