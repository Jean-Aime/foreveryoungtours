<?php
/**
 * Clone Africa Continent Theme to All Countries
 * This creates continent-level theming inheritance
 */

require_once 'config/database.php';

$africa_source = 'continents/africa/';
$countries_dir = 'countries/';

// All African countries
$african_countries = [
    'rwanda', 'kenya', 'tanzania', 'uganda', 'south-africa', 'egypt', 
    'morocco', 'botswana', 'namibia', 'zimbabwe', 'ghana', 'nigeria', 
    'ethiopia', 'cameroon', 'democratic-republic-of-congo', 'senegal', 'tunisia'
];

echo "<h1>Cloning Africa Continent Theme to African Countries</h1>\n";
echo "<pre>\n";

// Read Africa continent theme
if (!file_exists($africa_source . 'index.php')) {
    echo "Error: Africa continent theme not found!\n";
    exit;
}

foreach ($african_countries as $country_folder) {
    $target_dir = $countries_dir . $country_folder . '/';
    
    if (is_dir($target_dir)) {
        echo "Processing {$country_folder}...\n";
        
        // Create continent-specific assets and includes
        createContinentInheritance($africa_source, $target_dir, $country_folder);
        
        echo "  ✓ Added Africa continent inheritance to {$country_folder}\n\n";
    }
}

echo "All African countries now inherit from Africa continent theme!\n";
echo "</pre>";

function createContinentInheritance($africa_source, $target_dir, $country_folder) {
    // Copy Africa-specific assets if they don't exist
    $africa_assets = $africa_source . 'assets/';
    $target_assets = $target_dir . 'assets/';
    
    if (is_dir($africa_assets)) {
        // Copy continent-specific CSS
        if (file_exists($africa_assets . 'css/africa-theme.css')) {
            if (!is_dir($target_assets . 'css/')) {
                mkdir($target_assets . 'css/', 0755, true);
            }
            copy($africa_assets . 'css/africa-theme.css', $target_assets . 'css/africa-theme.css');
            echo "  ✓ Copied Africa theme CSS\n";
        }
        
        // Copy continent-specific images
        if (is_dir($africa_assets . 'images/')) {
            if (!is_dir($target_assets . 'images/')) {
                mkdir($target_assets . 'images/', 0755, true);
            }
            copyDirectory($africa_assets . 'images/', $target_assets . 'images/');
            echo "  ✓ Copied Africa theme images\n";
        }
    }
    
    // Copy Africa-specific includes
    $africa_includes = $africa_source . 'includes/';
    $target_includes = $target_dir . 'includes/';
    
    if (is_dir($africa_includes)) {
        if (file_exists($africa_includes . 'africa-header.php')) {
            copy($africa_includes . 'africa-header.php', $target_includes . 'africa-header.php');
            echo "  ✓ Copied Africa header component\n";
        }
        
        if (file_exists($africa_includes . 'africa-footer.php')) {
            copy($africa_includes . 'africa-footer.php', $target_includes . 'africa-footer.php');
            echo "  ✓ Copied Africa footer component\n";
        }
    }
    
    // Create continent inheritance file
    $inheritance_content = createContinentInheritanceFile($country_folder);
    file_put_contents($target_dir . 'continent-theme.php', $inheritance_content);
    echo "  ✓ Created continent inheritance file\n";
}

function createContinentInheritanceFile($country_folder) {
    return '<?php
/**
 * Continent Theme Inheritance - Africa
 * This file provides continent-level theming for ' . ucfirst($country_folder) . '
 */

// Africa continent configuration
define("CONTINENT_THEME", "africa");
define("CONTINENT_NAME", "Africa");
define("CONTINENT_COLOR_PRIMARY", "#F59E0B");
define("CONTINENT_COLOR_SECONDARY", "#EA580C");

// Africa-specific features
$africa_features = [
    "wildlife_safaris" => true,
    "cultural_experiences" => true,
    "luxury_lodges" => true,
    "conservation_focus" => true,
    "adventure_activities" => true
];

// Africa-specific navigation
$africa_navigation = [
    ["name" => "Safari Tours", "url" => "/safaris"],
    ["name" => "Cultural Experiences", "url" => "/culture"],
    ["name" => "Luxury Lodges", "url" => "/lodges"],
    ["name" => "Conservation", "url" => "/conservation"]
];

// Africa-specific contact info
$africa_contact = [
    "regional_office" => "Africa Regional Office",
    "phone" => "+1-737-443-9646",
    "email" => "africa@iforeveryoungtours.com",
    "whatsapp" => "17374439646"
];

// Load continent-specific assets
function loadAfricaAssets() {
    echo \'<link rel="stylesheet" href="assets/css/africa-theme.css">\';
    echo \'<script src="assets/js/africa-theme.js"></script>\';
}

// Africa theme customization
function getAfricaThemeColors() {
    return [
        "primary" => "#F59E0B",
        "secondary" => "#EA580C", 
        "accent" => "#10B981",
        "text" => "#1F2937"
    ];
}
?>';
}

function copyDirectory($src, $dst) {
    if (!is_dir($src)) return;
    
    if (!is_dir($dst)) {
        mkdir($dst, 0755, true);
    }
    
    $files = scandir($src);
    foreach ($files as $file) {
        if ($file != "." && $file != "..") {
            $srcFile = $src . $file;
            $dstFile = $dst . $file;
            
            if (is_dir($srcFile)) {
                copyDirectory($srcFile . '/', $dstFile . '/');
            } else {
                copy($srcFile, $dstFile);
            }
        }
    }
}
?>
