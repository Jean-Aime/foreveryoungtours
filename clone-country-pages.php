<?php
/**
 * ============================================
 * COUNTRY PAGE CLONING SCRIPT
 * ForeverYoung Tours - Automated Page Generation
 * ============================================
 * 
 * This script clones the master country template
 * to all countries in the database.
 * 
 * Usage: php clone-country-pages.php
 */

echo "\n";
echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║                                                              ║\n";
echo "║         🌍 COUNTRY PAGE CLONING SCRIPT                      ║\n";
echo "║         ForeverYoung Tours                                   ║\n";
echo "║                                                              ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n";
echo "\n";

// Database connection
require_once 'config/database.php';

// Master template path
$masterTemplate = 'subdomains/visit-rw/index.php';

// Check if master template exists
if (!file_exists($masterTemplate)) {
    echo "❌ ERROR: Master template not found at: $masterTemplate\n";
    echo "   Please ensure the Rwanda template exists first.\n\n";
    exit(1);
}

echo "✅ Master template found: $masterTemplate\n\n";

// Read master template
$templateContent = file_get_contents($masterTemplate);

if (!$templateContent) {
    echo "❌ ERROR: Could not read master template\n\n";
    exit(1);
}

echo "📖 Master template loaded successfully\n\n";

// Get all active countries from database
try {
    $stmt = $pdo->query("
        SELECT id, name, slug, region_id 
        FROM countries 
        WHERE status = 'active' 
        ORDER BY name ASC
    ");
    $countries = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($countries)) {
        echo "⚠️  WARNING: No active countries found in database\n\n";
        exit(0);
    }
    
    echo "📊 Found " . count($countries) . " active countries in database\n\n";
    
} catch (PDOException $e) {
    echo "❌ DATABASE ERROR: " . $e->getMessage() . "\n\n";
    exit(1);
}

// Statistics
$created = 0;
$updated = 0;
$skipped = 0;
$errors = 0;

echo "🚀 Starting cloning process...\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

// Clone to each country
foreach ($countries as $country) {
    $countrySlug = $country['slug'];
    $countryName = $country['name'];
    
    // Skip the master template itself
    if ($countrySlug === 'visit-rw') {
        echo "⏭️  Skipping master template: $countryName ($countrySlug)\n";
        $skipped++;
        continue;
    }
    
    // Create subdomain directory
    $subdomainDir = "subdomains/$countrySlug";
    
    if (!is_dir($subdomainDir)) {
        if (!mkdir($subdomainDir, 0755, true)) {
            echo "❌ ERROR: Could not create directory: $subdomainDir\n";
            $errors++;
            continue;
        }
        echo "📁 Created directory: $subdomainDir\n";
    }
    
    // Target file path
    $targetFile = "$subdomainDir/index.php";
    
    // Check if file already exists
    $fileExists = file_exists($targetFile);
    
    // Replace the country slug in template
    $newContent = preg_replace(
        "/\\\$country_slug = 'visit-rw';/",
        "\$country_slug = '$countrySlug';",
        $templateContent
    );
    
    // Verify replacement worked
    if (strpos($newContent, "\$country_slug = '$countrySlug';") === false) {
        echo "❌ ERROR: Failed to replace slug for: $countryName\n";
        $errors++;
        continue;
    }
    
    // Write file
    if (file_put_contents($targetFile, $newContent)) {
        if ($fileExists) {
            echo "♻️  Updated: $countryName ($countrySlug) → $targetFile\n";
            $updated++;
        } else {
            echo "✅ Created: $countryName ($countrySlug) → $targetFile\n";
            $created++;
        }
    } else {
        echo "❌ ERROR: Could not write file: $targetFile\n";
        $errors++;
    }
}

echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🎉 CLONING COMPLETE!\n\n";

// Display statistics
echo "📊 STATISTICS:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "✅ Created:  $created new pages\n";
echo "♻️  Updated:  $updated existing pages\n";
echo "⏭️  Skipped:  $skipped pages (master template)\n";
echo "❌ Errors:   $errors pages\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📝 Total:    " . ($created + $updated + $skipped + $errors) . " countries processed\n\n";

// Success message
if ($errors === 0) {
    echo "✨ SUCCESS: All country pages cloned successfully!\n\n";
    echo "🌐 You can now access your country pages:\n";
    echo "   • http://localhost/ForeverYoungTours/subdomains/visit-ke/\n";
    echo "   • http://localhost/ForeverYoungTours/subdomains/visit-tz/\n";
    echo "   • http://localhost/ForeverYoungTours/subdomains/visit-ug/\n";
    echo "   • etc.\n\n";
} else {
    echo "⚠️  WARNING: Some errors occurred during cloning\n";
    echo "   Please check the error messages above\n\n";
}

// Optional: Create a summary report
$reportFile = 'country-pages-clone-report.txt';
$report = "Country Pages Cloning Report\n";
$report .= "Generated: " . date('Y-m-d H:i:s') . "\n";
$report .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
$report .= "Statistics:\n";
$report .= "- Created: $created new pages\n";
$report .= "- Updated: $updated existing pages\n";
$report .= "- Skipped: $skipped pages\n";
$report .= "- Errors: $errors pages\n";
$report .= "- Total: " . ($created + $updated + $skipped + $errors) . " countries\n\n";

$report .= "Countries Cloned:\n";
$report .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
foreach ($countries as $country) {
    if ($country['slug'] !== 'visit-rw') {
        $report .= "✅ " . $country['name'] . " (" . $country['slug'] . ")\n";
    }
}

file_put_contents($reportFile, $report);
echo "📄 Report saved to: $reportFile\n\n";

echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║                                                              ║\n";
echo "║              🎉 CLONING PROCESS COMPLETE                    ║\n";
echo "║                                                              ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n";
echo "\n";

exit(0);
?>
