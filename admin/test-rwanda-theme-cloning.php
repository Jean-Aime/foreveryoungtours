<?php
/**
 * Test Rwanda Theme Cloning System
 * 
 * This script tests the automatic theme generation system that clones
 * the Rwanda design to new countries
 */

session_start();
require_once '../config/database.php';
require_once '../includes/theme-generator.php';

echo "<h1>🎨 Rwanda Theme Cloning Test</h1>\n";
echo "<style>
body{font-family:Arial,sans-serif;margin:20px;background:#f5f5f5;} 
.success{color:#4caf50;background:#e8f5e9;padding:10px;border-radius:5px;margin:10px 0;} 
.error{color:#f44336;background:#ffebee;padding:10px;border-radius:5px;margin:10px 0;} 
.info{color:#2196f3;background:#e3f2fd;padding:10px;border-radius:5px;margin:10px 0;} 
.warning{color:#ff9800;background:#fff3e0;padding:10px;border-radius:5px;margin:10px 0;}
table{border-collapse:collapse;width:100%;background:white;margin:20px 0;}
th,td{padding:12px;border:1px solid #ddd;text-align:left;}
th{background:#2196f3;color:white;}
.badge{padding:5px 10px;border-radius:3px;font-size:12px;font-weight:bold;}
.badge-success{background:#4caf50;color:white;}
.badge-error{background:#f44336;color:white;}
.badge-warning{background:#ff9800;color:white;}
</style>\n";

echo "<h2>📋 System Check</h2>\n";

// Check if Rwanda master theme exists
$rwanda_path = __DIR__ . '/../countries/rwanda/';
if (is_dir($rwanda_path)) {
    echo "<div class='success'>✅ Rwanda master theme found at: $rwanda_path</div>\n";
    
    // Check Rwanda structure
    $required_files = [
        'index.php' => 'Main landing page',
        'config.php' => 'Configuration file',
        'includes/header.php' => 'Header template',
        'includes/footer.php' => 'Footer template',
        'pages/packages.php' => 'Packages page',
        'pages/tour-detail.php' => 'Tour detail page',
        'pages/enhanced-booking-modal.php' => 'Booking modal',
        'pages/inquiry-modal.php' => 'Inquiry modal',
        'assets/css' => 'CSS directory',
        'assets/images' => 'Images directory'
    ];
    
    echo "<h3>📁 Rwanda Theme Structure</h3>\n";
    echo "<table>\n";
    echo "<tr><th>File/Directory</th><th>Description</th><th>Status</th></tr>\n";
    
    foreach ($required_files as $file => $description) {
        $full_path = $rwanda_path . $file;
        $exists = file_exists($full_path) || is_dir($full_path);
        $status = $exists ? "<span class='badge badge-success'>✓ Exists</span>" : "<span class='badge badge-error'>✗ Missing</span>";
        echo "<tr><td><code>$file</code></td><td>$description</td><td>$status</td></tr>\n";
    }
    echo "</table>\n";
    
} else {
    echo "<div class='error'>❌ Rwanda master theme NOT found! Cannot clone theme.</div>\n";
    exit;
}

// Get all countries from database
echo "<h2>🌍 Countries in Database</h2>\n";
$stmt = $pdo->query("SELECT c.*, r.name as continent_name FROM countries c LEFT JOIN regions r ON c.region_id = r.id ORDER BY c.name");
$countries = $stmt->fetchAll();

echo "<table>\n";
echo "<tr><th>Country</th><th>Slug</th><th>Code</th><th>Continent</th><th>Theme Status</th><th>Folder</th><th>Actions</th></tr>\n";

$themed_count = 0;
$total_count = count($countries);

foreach ($countries as $country) {
    $folder_name = generateFolderName($country['slug']);
    $country_path = __DIR__ . '/../countries/' . $folder_name . '/';
    $theme_exists = file_exists($country_path . 'index.php');

    if ($theme_exists) {
        $themed_count++;
    }

    $status_badge = $theme_exists
        ? "<span class='badge badge-success'>✓ Theme Ready</span>"
        : "<span class='badge badge-warning'>⚠ No Theme</span>";

    $action_button = !$theme_exists
        ? "<form method='post' style='display:inline;'><input type='hidden' name='country_id' value='{$country['id']}'><button type='submit' name='generate_theme' style='background:#4caf50;color:white;border:none;padding:5px 10px;border-radius:3px;cursor:pointer;'>Generate Theme</button></form>"
        : "<form method='post' style='display:inline;'><input type='hidden' name='country_id' value='{$country['id']}'><button type='submit' name='regenerate_theme' style='background:#2196f3;color:white;border:none;padding:5px 10px;border-radius:3px;cursor:pointer;'>Regenerate</button></form>";

    echo "<tr>";
    echo "<td><strong>{$country['name']}</strong></td>";
    echo "<td><code>{$country['slug']}</code></td>";
    echo "<td><code>{$country['country_code']}</code></td>";
    echo "<td>{$country['continent_name']}</td>";
    echo "<td>$status_badge</td>";
    echo "<td><code>countries/$folder_name/</code></td>";
    echo "<td>$action_button</td>";
    echo "</tr>\n";
}
echo "</table>\n";

// Show summary
echo "<div class='info'>\n";
echo "<h3>📊 Summary</h3>\n";
echo "<p><strong>Total Countries:</strong> $total_count</p>\n";
echo "<p><strong>Countries with Themes:</strong> $themed_count</p>\n";
echo "<p><strong>Countries without Themes:</strong> " . ($total_count - $themed_count) . "</p>\n";
$percentage = $total_count > 0 ? round(($themed_count / $total_count) * 100) : 0;
echo "<p><strong>Completion:</strong> $percentage%</p>\n";
echo "</div>\n";

// Handle theme generation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['generate_theme']) || isset($_POST['regenerate_theme'])) {
        $country_id = $_POST['country_id'];
        
        // Get country data
        $stmt = $pdo->prepare("SELECT * FROM countries WHERE id = ?");
        $stmt->execute([$country_id]);
        $country = $stmt->fetch();
        
        if ($country) {
            echo "<h2>🔄 Generating Theme for {$country['name']}</h2>\n";
            
            try {
                $folder_name = generateFolderName($country['slug']);
                
                $result = generateCountryTheme([
                    'id' => $country['id'],
                    'name' => $country['name'],
                    'slug' => $country['slug'],
                    'country_code' => $country['country_code'],
                    'folder' => $folder_name,
                    'currency' => $country['currency'],
                    'description' => $country['description']
                ]);
                
                // Update subdomain handler
                updateSubdomainHandler($country['country_code'], $country['slug'], $folder_name);
                
                echo "<div class='success'>✅ Theme generated successfully for {$country['name']}!</div>\n";
                echo "<div class='info'>📁 Theme location: countries/$folder_name/</div>\n";
                echo "<div class='info'>🌐 Subdomain: <a href='http://{$country['slug']}.localhost/foreveryoungtours/' target='_blank'>{$country['slug']}.iforeveryoungtours.com</a></div>\n";
                
                // Refresh page to show updated status
                echo "<script>setTimeout(function(){ window.location.reload(); }, 2000);</script>\n";
                
            } catch (Exception $e) {
                echo "<div class='error'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</div>\n";
            }
        }
    }
}

echo "<h2>📖 How It Works</h2>\n";
echo "<div class='info'>\n";
echo "<h3>Automatic Theme Cloning Process:</h3>\n";
echo "<ol>\n";
echo "<li><strong>Master Template:</strong> Rwanda is the master template with the complete design</li>\n";
echo "<li><strong>Auto-Clone:</strong> When admin adds a new country, the system automatically clones Rwanda's design</li>\n";
echo "<li><strong>Customization:</strong> Country-specific data (name, currency, descriptions) is automatically replaced</li>\n";
echo "<li><strong>Subdomain Setup:</strong> Subdomain handler is automatically updated</li>\n";
echo "<li><strong>Image Paths:</strong> All image paths are fixed to work with subdomain context</li>\n";
echo "</ol>\n";
echo "</div>\n";

echo "<h2>🎯 Next Steps</h2>\n";
echo "<div class='warning'>\n";
echo "<ul>\n";
echo "<li>Click 'Generate Theme' for any country without a theme</li>\n";
echo "<li>Click 'Regenerate' to update existing themes with latest Rwanda design</li>\n";
echo "<li>Test the subdomain links to verify the theme works correctly</li>\n";
echo "<li>Add country-specific images to <code>countries/{folder}/assets/images/</code></li>\n";
echo "</ul>\n";
echo "</div>\n";
?>
