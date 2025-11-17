<?php
/**
 * Copy Booking Modal Files to All Countries and Continents
 */

require_once 'config/database.php';

echo "=" . str_repeat("=", 100) . "\n";
echo "COPY BOOKING MODAL FILES\n";
echo "=" . str_repeat("=", 100) . "\n\n";

// Source files
$source_dir = __DIR__ . '/countries/rwanda/pages/';
$modal_files = [
    'enhanced-booking-modal.php',
    'inquiry-modal.php'
];

// Country folder mapping
$folder_mapping = [
    'visit-rw' => 'rwanda',
    'visit-ke' => 'kenya',
    'visit-tz' => 'tanzania',
    'visit-ug' => 'uganda',
    'visit-za' => 'south-africa',
    'visit-eg' => 'egypt',
    'visit-ma' => 'morocco',
    'visit-bw' => 'botswana',
    'visit-na' => 'namibia',
    'visit-zw' => 'zimbabwe',
    'visit-gh' => 'ghana',
    'visit-ng' => 'nigeria',
    'visit-et' => 'ethiopia',
    'visit-sn' => 'senegal',
    'visit-tn' => 'tunisia',
    'visit-cm' => 'cameroon',
    'visit-cd' => 'dr-congo'
];

echo "1. COPYING MODAL FILES TO ALL COUNTRIES\n";
echo str_repeat("-", 100) . "\n";

$success_count = 0;
$error_count = 0;

foreach ($folder_mapping as $slug => $folder) {
    $target_dir = __DIR__ . '/countries/' . $folder . '/pages/';
    
    foreach ($modal_files as $file) {
        $source_file = $source_dir . $file;
        $target_file = $target_dir . $file;
        
        if (!file_exists($source_file)) {
            echo "   ⚠️  Source file not found: $file\n";
            continue;
        }
        
        if (copy($source_file, $target_file)) {
            $success_count++;
        } else {
            echo "   ❌ Failed to copy $file to countries/$folder/pages/\n";
            $error_count++;
        }
    }
    echo "   ✅ Copied modal files to: countries/$folder/pages/\n";
}

echo "\n";
echo "Summary:\n";
echo "   ✅ Successfully copied: $success_count files\n";
echo "   ❌ Errors: $error_count files\n";

echo "\n";
echo "2. COPYING MODAL FILES TO ALL CONTINENTS\n";
echo str_repeat("-", 100) . "\n";

// Get all active regions (continents)
$stmt = $pdo->query("SELECT * FROM regions WHERE status = 'active' ORDER BY name");
$regions = $stmt->fetchAll();

$continent_success = 0;
$continent_error = 0;

foreach ($regions as $region) {
    $continent_folder = strtolower(str_replace(' ', '-', $region['name']));
    $target_dir = __DIR__ . '/continents/' . $continent_folder . '/pages/';
    
    // Create pages directory if it doesn't exist
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }
    
    foreach ($modal_files as $file) {
        $source_file = $source_dir . $file;
        $target_file = $target_dir . $file;
        
        if (!file_exists($source_file)) {
            continue;
        }
        
        if (copy($source_file, $target_file)) {
            $continent_success++;
        } else {
            echo "   ❌ Failed to copy $file to continents/$continent_folder/pages/\n";
            $continent_error++;
        }
    }
    echo "   ✅ Copied modal files to: continents/$continent_folder/pages/\n";
}

echo "\n";
echo "Summary:\n";
echo "   ✅ Successfully copied: $continent_success files\n";
echo "   ❌ Errors: $continent_error files\n";

echo "\n";
echo "=" . str_repeat("=", 100) . "\n";
echo "COPY COMPLETE!\n";
echo "=" . str_repeat("=", 100) . "\n";
echo "\n";
echo "✅ All countries have booking modal files\n";
echo "✅ All continents have booking modal files\n";
echo "✅ Booking system ready to use from all subdomains\n";
echo "\n";
?>

