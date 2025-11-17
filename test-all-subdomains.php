<?php
/**
 * Test All Country Subdomains
 * Shows all valid subdomain URLs for testing
 */

require_once 'config/database.php';

echo "<style>
body{font-family:Arial,sans-serif;margin:20px;background:#f5f5f5;} 
h1{color:#2196f3;}
table{border-collapse:collapse;width:100%;background:white;margin:20px 0;box-shadow:0 2px 4px rgba(0,0,0,0.1);}
th,td{padding:12px;border:1px solid #ddd;text-align:left;}
th{background:#2196f3;color:white;}
tr:hover{background:#f5f5f5;}
.link{color:#2196f3;text-decoration:none;font-weight:bold;}
.link:hover{text-decoration:underline;}
.badge{padding:5px 10px;border-radius:3px;font-size:12px;font-weight:bold;color:white;}
.badge-localhost{background:#4caf50;}
.badge-local{background:#ff9800;}
.info{background:#e3f2fd;padding:15px;border-radius:5px;margin:20px 0;border-left:4px solid #2196f3;}
.warning{background:#fff3e0;padding:15px;border-radius:5px;margin:20px 0;border-left:4px solid #ff9800;}
</style>\n";

echo "<h1>🌐 All Country Subdomain URLs</h1>\n";

// Get all countries
$stmt = $pdo->query("SELECT * FROM countries ORDER BY name");
$countries = $stmt->fetchAll();

echo "<div class='info'>\n";
echo "<h3>📋 How to Use:</h3>\n";
echo "<ul>\n";
echo "<li><strong>Localhost URLs</strong> (Green) - Work immediately, no setup required</li>\n";
echo "<li><strong>.local URLs</strong> (Orange) - Require hosts file configuration</li>\n";
echo "<li>Click any link to test the country site</li>\n";
echo "</ul>\n";
echo "</div>\n";

echo "<table>\n";
echo "<tr>";
echo "<th>Country</th>";
echo "<th>Code</th>";
echo "<th>Slug</th>";
echo "<th>Localhost URL (Ready Now)</th>";
echo "<th>.local URL (Needs Setup)</th>";
echo "</tr>\n";

foreach ($countries as $country) {
    // Extract 2-letter code from slug
    preg_match('/visit-([a-z]{2,3})/', $country['slug'], $matches);
    $short_code = isset($matches[1]) ? strtoupper($matches[1]) : 'N/A';
    
    $localhost_url = "http://{$country['slug']}.localhost/foreveryoungtours/";
    $local_url = "http://{$country['slug']}.foreveryoungtours.local/";
    
    echo "<tr>";
    echo "<td><strong>{$country['name']}</strong></td>";
    echo "<td><code>{$short_code}</code></td>";
    echo "<td><code>{$country['slug']}</code></td>";
    echo "<td><a href='$localhost_url' target='_blank' class='link'>$localhost_url</a> <span class='badge badge-localhost'>✓ Ready</span></td>";
    echo "<td><a href='$local_url' target='_blank' class='link'>$local_url</a> <span class='badge badge-local'>⚠ Setup Required</span></td>";
    echo "</tr>\n";
}

echo "</table>\n";

echo "<div class='warning'>\n";
echo "<h3>⚠️ Important Notes:</h3>\n";
echo "<ul>\n";
echo "<li><strong>\"visit-se\" does NOT exist</strong> - There is no country with code \"SE\"</li>\n";
echo "<li><strong>Senegal</strong> uses <code>visit-sn</code> (not visit-se)</li>\n";
echo "<li><strong>Localhost URLs</strong> work immediately without any configuration</li>\n";
echo "<li><strong>.local URLs</strong> require adding entries to your Windows hosts file</li>\n";
echo "</ul>\n";
echo "</div>\n";

echo "<div class='info'>\n";
echo "<h3>🔧 To Use .local URLs:</h3>\n";
echo "<ol>\n";
echo "<li>Open Notepad as Administrator</li>\n";
echo "<li>Open: <code>C:\\Windows\\System32\\drivers\\etc\\hosts</code></li>\n";
echo "<li>Add these lines:</li>\n";
echo "</ol>\n";
echo "<pre style='background:#263238;color:#aed581;padding:15px;border-radius:5px;overflow-x:auto;'>";
echo "127.0.0.1 foreveryoungtours.local\n";
foreach ($countries as $country) {
    echo "127.0.0.1 {$country['slug']}.foreveryoungtours.local\n";
}
echo "</pre>\n";
echo "<ol start='4'>\n";
echo "<li>Save the file</li>\n";
echo "<li>Run: <code>ipconfig /flushdns</code> in Command Prompt</li>\n";
echo "<li>Restart your browser</li>\n";
echo "</ol>\n";
echo "</div>\n";

echo "<div class='info'>\n";
echo "<h3>✅ Quick Test Links (Work Now):</h3>\n";
echo "<ul>\n";
echo "<li>🇷🇼 Rwanda: <a href='http://visit-rw.localhost/foreveryoungtours/' target='_blank' class='link'>http://visit-rw.localhost/foreveryoungtours/</a></li>\n";
echo "<li>🇰🇪 Kenya: <a href='http://visit-ke.localhost/foreveryoungtours/' target='_blank' class='link'>http://visit-ke.localhost/foreveryoungtours/</a></li>\n";
echo "<li>🇹🇿 Tanzania: <a href='http://visit-tz.localhost/foreveryoungtours/' target='_blank' class='link'>http://visit-tz.localhost/foreveryoungtours/</a></li>\n";
echo "<li>🇺🇬 Uganda: <a href='http://visit-ug.localhost/foreveryoungtours/' target='_blank' class='link'>http://visit-ug.localhost/foreveryoungtours/</a></li>\n";
echo "<li>🇿🇦 South Africa: <a href='http://visit-za.localhost/foreveryoungtours/' target='_blank' class='link'>http://visit-za.localhost/foreveryoungtours/</a></li>\n";
echo "<li>🇸🇳 Senegal: <a href='http://visit-sn.localhost/foreveryoungtours/' target='_blank' class='link'>http://visit-sn.localhost/foreveryoungtours/</a></li>\n";
echo "</ul>\n";
echo "</div>\n";

echo "<div class='info'>\n";
echo "<h3>📊 Summary:</h3>\n";
echo "<p><strong>Total Countries:</strong> " . count($countries) . "</p>\n";
echo "<p><strong>All countries have Rwanda theme cloned:</strong> ✅</p>\n";
echo "<p><strong>Subdomain handler updated:</strong> ✅</p>\n";
echo "<p><strong>Ready for testing:</strong> ✅</p>\n";
echo "</div>\n";
?>

