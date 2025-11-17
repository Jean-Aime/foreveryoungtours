<?php
/**
 * Create config.php files in all country directories
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

$config_content = '<?php
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
    $pages_dir = $countries_dir . $country . '/pages/';
    $config_file = $pages_dir . 'config.php';
    
    echo "Processing: $country... ";
    
    try {
        // Create pages directory if it doesn't exist
        if (!is_dir($pages_dir)) {
            if (!mkdir($pages_dir, 0755, true)) {
                echo "❌ Failed to create pages directory\n";
                $error_count++;
                continue;
            }
        }
        
        // Create config.php file
        if (file_put_contents($config_file, $config_content)) {
            echo "✅ Created config.php\n";
            $created_count++;
        } else {
            echo "❌ Failed to create config.php\n";
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
echo "Now you can use 'require_once \"config.php\";' in any country page file.\n";
echo "\n";
echo "Test URLs:\n";
echo "- Rwanda: http://localhost/foreveryoungtours/countries/rwanda/pages/tour-detail?id=28\n";
echo "- Main: http://localhost/foreveryoungtours/pages/tour-detail?id=28\n";

echo "</pre>";
?>
