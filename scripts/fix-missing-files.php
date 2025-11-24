<?php
/**
 * Fix Missing Files in DR Congo and Other Countries
 */

require_once 'includes/theme-generator.php';
require_once 'config/database.php';

echo "=" . str_repeat("=", 100) . "\n";
echo "FIXING MISSING FILES\n";
echo "=" . str_repeat("=", 100) . "\n\n";

// 1. Fix DR Congo
echo "1. FIXING DR CONGO THEME\n";
echo str_repeat("-", 100) . "\n";

$dr_congo_data = [
    'id' => 18,
    'name' => 'DR Congo',
    'slug' => 'visit-cd',
    'country_code' => 'COD',
    'folder' => 'dr-congo',
    'currency' => '$',
    'description' => 'Discover the heart of Africa in the Democratic Republic of Congo'
];

try {
    $result = generateCountryTheme($dr_congo_data);
    if ($result['success']) {
        echo "   ✅ DR Congo theme generated successfully\n";
        echo "   📁 Path: " . $result['path'] . "\n";
    } else {
        echo "   ❌ Failed to generate DR Congo theme\n";
    }
} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
}

// 2. Check and fix missing config.php files
echo "\n2. FIXING MISSING CONFIG.PHP FILES\n";
echo str_repeat("-", 100) . "\n";

$countries = [
    'rwanda', 'kenya', 'tanzania', 'uganda', 'south-africa',
    'egypt', 'morocco', 'botswana', 'namibia', 'zimbabwe',
    'ghana', 'nigeria', 'ethiopia', 'senegal', 'tunisia',
    'cameroon', 'dr-congo'
];

$config_template = <<<'PHP'
<?php
/**
 * Country-specific configuration
 */

// Get the absolute path to the root directory
define('ROOT_PATH', dirname(dirname(dirname(__FILE__))) . '/');

// Include main config if needed
if (file_exists(ROOT_PATH . 'config/database.php')) {
    require_once ROOT_PATH . 'config/database.php';
}

// Helper function to get correct image URLs
function getImageUrl($path) {
    // Remove leading slash if present
    $path = ltrim($path, '/');
    
    // If path starts with 'assets/', go up 3 levels
    if (strpos($path, 'assets/') === 0) {
        return '../../../' . $path;
    }
    
    // If path starts with 'countries/', go up 3 levels
    if (strpos($path, 'countries/') === 0) {
        return '../../../' . $path;
    }
    
    // Default: assume it's relative to root
    return '../../../' . $path;
}

// Set timezone
date_default_timezone_set('Africa/Kigali');
?>
PHP;

$fixed_count = 0;
foreach ($countries as $country) {
    $config_file = "countries/$country/pages/config.php";
    
    if (!file_exists($config_file)) {
        // Create pages directory if it doesn't exist
        $pages_dir = "countries/$country/pages";
        if (!is_dir($pages_dir)) {
            mkdir($pages_dir, 0755, true);
        }
        
        // Write config file
        if (file_put_contents($config_file, $config_template)) {
            echo "   ✅ Created config.php for $country\n";
            $fixed_count++;
        } else {
            echo "   ❌ Failed to create config.php for $country\n";
        }
    }
}

if ($fixed_count > 0) {
    echo "   ✅ Fixed $fixed_count missing config.php files\n";
} else {
    echo "   ✅ All config.php files already exist\n";
}

// 3. Verify all fixes
echo "\n3. VERIFYING FIXES\n";
echo str_repeat("-", 100) . "\n";

$all_good = true;

// Check DR Congo
if (file_exists('countries/dr-congo/index.php')) {
    echo "   ✅ DR Congo index.php exists\n";
} else {
    echo "   ❌ DR Congo index.php still missing\n";
    $all_good = false;
}

if (file_exists('countries/dr-congo/includes/header.php')) {
    echo "   ✅ DR Congo header.php exists\n";
} else {
    echo "   ❌ DR Congo header.php still missing\n";
    $all_good = false;
}

if (file_exists('countries/dr-congo/includes/footer.php')) {
    echo "   ✅ DR Congo footer.php exists\n";
} else {
    echo "   ❌ DR Congo footer.php still missing\n";
    $all_good = false;
}

// Check all config files
$missing_configs = 0;
foreach ($countries as $country) {
    if (!file_exists("countries/$country/pages/config.php")) {
        $missing_configs++;
    }
}

if ($missing_configs === 0) {
    echo "   ✅ All config.php files exist\n";
} else {
    echo "   ❌ Still missing $missing_configs config.php files\n";
    $all_good = false;
}

echo "\n";
echo "=" . str_repeat("=", 100) . "\n";
if ($all_good) {
    echo "✅ ALL FIXES APPLIED SUCCESSFULLY!\n";
} else {
    echo "⚠️  SOME ISSUES REMAIN - MANUAL INTERVENTION MAY BE NEEDED\n";
}
echo "=" . str_repeat("=", 100) . "\n";
echo "\n";
?>

