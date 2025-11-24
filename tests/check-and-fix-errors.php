<?php
/**
 * Comprehensive Error Checker and Fixer for Forever Young Tours
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=" . str_repeat("=", 100) . "\n";
echo "FOREVER YOUNG TOURS - COMPREHENSIVE ERROR CHECK & FIX\n";
echo "=" . str_repeat("=", 100) . "\n\n";

$errors = [];
$warnings = [];
$fixes = [];

// 1. Check Database Connection
echo "1. CHECKING DATABASE CONNECTION\n";
echo str_repeat("-", 100) . "\n";
try {
    require_once 'config/database.php';
    if (isset($pdo) && $pdo instanceof PDO) {
        echo "   ✅ Database connection successful\n";
    } else {
        $errors[] = "Database connection failed - \$pdo not initialized";
        echo "   ❌ Database connection failed\n";
    }
} catch (Exception $e) {
    $errors[] = "Database error: " . $e->getMessage();
    echo "   ❌ Database error: " . $e->getMessage() . "\n";
}

// 2. Check Critical Files
echo "\n2. CHECKING CRITICAL FILES\n";
echo str_repeat("-", 100) . "\n";
$critical_files = [
    'index.php',
    'config/database.php',
    'subdomain-handler.php',
    'booking-handler.php',
    'assets/js/booking.js',
    '.htaccess'
];

foreach ($critical_files as $file) {
    if (file_exists($file)) {
        echo "   ✅ $file exists\n";
        
        // Check PHP syntax for PHP files
        if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
            $output = [];
            $return_var = 0;
            exec("php -l \"$file\" 2>&1", $output, $return_var);
            if ($return_var !== 0) {
                $errors[] = "Syntax error in $file: " . implode("\n", $output);
                echo "      ❌ Syntax error detected\n";
            }
        }
    } else {
        $errors[] = "Missing critical file: $file";
        echo "   ❌ $file missing\n";
    }
}

// 3. Check Country Folders
echo "\n3. CHECKING COUNTRY FOLDERS\n";
echo str_repeat("-", 100) . "\n";
$countries = [
    'rwanda', 'kenya', 'tanzania', 'uganda', 'south-africa',
    'egypt', 'morocco', 'botswana', 'namibia', 'zimbabwe',
    'ghana', 'nigeria', 'ethiopia', 'senegal', 'tunisia',
    'cameroon', 'dr-congo'
];

$country_issues = 0;
foreach ($countries as $country) {
    $country_dir = "countries/$country";
    if (!is_dir($country_dir)) {
        $warnings[] = "Country folder missing: $country_dir";
        $country_issues++;
        continue;
    }
    
    // Check required files
    $required_files = [
        "$country_dir/index.php",
        "$country_dir/pages/packages.php",
        "$country_dir/pages/enhanced-booking-modal.php",
        "$country_dir/includes/header.php",
        "$country_dir/includes/footer.php"
    ];
    
    foreach ($required_files as $file) {
        if (!file_exists($file)) {
            $warnings[] = "Missing file: $file";
            $country_issues++;
        }
    }
}

if ($country_issues === 0) {
    echo "   ✅ All country folders are complete\n";
} else {
    echo "   ⚠️  Found $country_issues issues in country folders\n";
}

// 4. Check Continent Folders
echo "\n4. CHECKING CONTINENT FOLDERS\n";
echo str_repeat("-", 100) . "\n";
$continents = ['africa', 'north-america', 'south-america'];
$continent_issues = 0;

foreach ($continents as $continent) {
    $continent_dir = "continents/$continent";
    if (!is_dir($continent_dir)) {
        $warnings[] = "Continent folder missing: $continent_dir";
        $continent_issues++;
        continue;
    }
    
    $required_files = [
        "$continent_dir/pages/packages.php",
        "$continent_dir/pages/enhanced-booking-modal.php"
    ];
    
    foreach ($required_files as $file) {
        if (!file_exists($file)) {
            $warnings[] = "Missing file: $file";
            $continent_issues++;
        }
    }
}

if ($continent_issues === 0) {
    echo "   ✅ All continent folders are complete\n";
} else {
    echo "   ⚠️  Found $continent_issues issues in continent folders\n";
}

// 5. Check Database Tables
echo "\n5. CHECKING DATABASE TABLES\n";
echo str_repeat("-", 100) . "\n";
if (isset($pdo)) {
    $required_tables = ['countries', 'regions', 'tours', 'bookings', 'users'];
    foreach ($required_tables as $table) {
        try {
            $stmt = $pdo->query("SELECT COUNT(*) FROM $table");
            $count = $stmt->fetchColumn();
            echo "   ✅ Table '$table' exists ($count rows)\n";
        } catch (Exception $e) {
            $errors[] = "Table '$table' missing or inaccessible";
            echo "   ❌ Table '$table' missing or inaccessible\n";
        }
    }
}

// 6. Check for Common Issues
echo "\n6. CHECKING FOR COMMON ISSUES\n";
echo str_repeat("-", 100) . "\n";

// Check for missing config.php in country folders
$missing_configs = 0;
foreach ($countries as $country) {
    $config_file = "countries/$country/pages/config.php";
    if (!file_exists($config_file)) {
        $missing_configs++;
    }
}
if ($missing_configs > 0) {
    echo "   ⚠️  $missing_configs country folders missing config.php\n";
    $warnings[] = "$missing_configs country folders missing config.php";
} else {
    echo "   ✅ All country folders have config.php\n";
}

// Summary
echo "\n";
echo "=" . str_repeat("=", 100) . "\n";
echo "SUMMARY\n";
echo "=" . str_repeat("=", 100) . "\n";
echo "Errors: " . count($errors) . "\n";
echo "Warnings: " . count($warnings) . "\n";
echo "\n";

if (count($errors) > 0) {
    echo "ERRORS:\n";
    foreach ($errors as $i => $error) {
        echo ($i + 1) . ". $error\n";
    }
    echo "\n";
}

if (count($warnings) > 0) {
    echo "WARNINGS:\n";
    foreach ($warnings as $i => $warning) {
        echo ($i + 1) . ". $warning\n";
    }
    echo "\n";
}

if (count($errors) === 0 && count($warnings) === 0) {
    echo "✅ NO ERRORS OR WARNINGS FOUND!\n";
    echo "✅ SYSTEM IS HEALTHY!\n";
}

echo "\n";
?>

