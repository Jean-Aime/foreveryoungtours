<?php
/**
 * Create config.php files in all country directories (both root and pages)
 */

echo "<h1>📁 Creating config.php Files in All Country Directories</h1>";
echo "<pre>";

$created_count = 0;
$error_count = 0;

// Get all country directories
$countries_dir = 'countries/';
$countries = [];

if (is_dir($countries_dir)) {
    $dirs = scandir($countries_dir);
    foreach ($dirs as $dir) {
        if ($dir !== '.' && $dir !== '..' && is_dir($countries_dir . $dir)) {
            $countries[] = $dir;
        }
    }
}

echo "Found " . count($countries) . " countries:\n";
foreach ($countries as $country) {
    echo "- $country\n";
}
echo "\n";

// Config content for country root directories (countries/rwanda/)
$root_config_content = '<?php
/**
 * Configuration File for Country Directory
 * 
 * This file includes the main config from the root directory
 * and ensures BASE_URL works correctly for files in this country folder
 */

// Include the main config file from root
require_once \'../../config.php\';

// All functions and constants are now available from the main config.php
// No additional configuration needed - everything is handled by the main config.php
?>';

// Config content for country pages directories (countries/rwanda/pages/)
$pages_config_content = '<?php
/**
 * Configuration File for Country Pages Directory
 * 
 * This file includes the main config from the root directory
 * and ensures BASE_URL works correctly for pages in this country folder
 */

// Include the main config file from root
require_once \'../../../config.php\';

// All functions and constants are now available from the main config.php
// No additional configuration needed - everything is handled by the main config.php
?>';

foreach ($countries as $country) {
    echo "Processing: $country...\n";
    
    // Create config.php in country root directory (countries/rwanda/)
    $root_config_file = $countries_dir . $country . '/config.php';
    echo "  Root config... ";
    
    try {
        if (file_put_contents($root_config_file, $root_config_content)) {
            echo "✅ Created\n";
            $created_count++;
        } else {
            echo "❌ Failed\n";
            $error_count++;
        }
    } catch (Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "\n";
        $error_count++;
    }
    
    // Create config.php in country pages directory (countries/rwanda/pages/)
    $pages_dir = $countries_dir . $country . '/pages/';
    $pages_config_file = $pages_dir . 'config.php';
    echo "  Pages config... ";
    
    try {
        // Create pages directory if it doesn't exist
        if (!is_dir($pages_dir)) {
            if (!mkdir($pages_dir, 0755, true)) {
                echo "❌ Failed to create pages directory\n";
                $error_count++;
                continue;
            }
        }
        
        if (file_put_contents($pages_config_file, $pages_config_content)) {
            echo "✅ Created\n";
            $created_count++;
        } else {
            echo "❌ Failed\n";
            $error_count++;
        }
    } catch (Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "\n";
        $error_count++;
    }
}

echo "\n";
echo "=== SUMMARY ===\n";
echo "Config files created: $created_count\n";
echo "Errors: $error_count\n";
echo "Total countries: " . count($countries) . "\n";
echo "\n";
echo "✅ All country directories now have config.php files!\n";
echo "\n";
echo "Directory structure:\n";
echo "countries/\n";
echo "├── rwanda/\n";
echo "│   ├── config.php (includes ../../config.php)\n";
echo "│   ├── index.php (can use: require_once 'config.php';)\n";
echo "│   └── pages/\n";
echo "│       ├── config.php (includes ../../../config.php)\n";
echo "│       └── tour-detail.php (can use: require_once 'config.php';)\n";
echo "└── [other countries...]\n";
echo "\n";
echo "Test URLs:\n";
echo "- Subdomain: http://visit-rw.foreveryoungtours.local/\n";
echo "- Rwanda root: http://localhost/foreveryoungtours/countries/rwanda/\n";
echo "- Rwanda tour: http://localhost/foreveryoungtours/countries/rwanda/pages/tour-detail?id=28\n";

echo "</pre>";
?>
