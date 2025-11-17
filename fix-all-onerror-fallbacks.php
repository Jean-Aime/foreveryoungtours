<?php
/**
 * Fix all onerror fallbacks in country tour detail pages to use absolute paths
 */

echo "<h1>Fixing All onerror Fallbacks to Use Absolute Paths</h1>";
echo "<pre>";

// Get all country directories
$countries_dir = 'countries/';
$countries = [];

if (is_dir($countries_dir)) {
    $dirs = scandir($countries_dir);
    foreach ($dirs as $dir) {
        if ($dir !== '.' && $dir !== '..' && is_dir($countries_dir . $dir)) {
            $tour_detail_file = $countries_dir . $dir . '/pages/tour-detail.php';
            if (file_exists($tour_detail_file)) {
                $countries[] = $dir;
            }
        }
    }
}

echo "Found " . count($countries) . " countries with tour-detail.php files:\n";
foreach ($countries as $country) {
    echo "- $country\n";
}
echo "\n";

$fixed_count = 0;
$error_count = 0;

foreach ($countries as $country) {
    $file_path = $countries_dir . $country . '/pages/tour-detail.php';
    echo "Processing: $country... ";
    
    try {
        $content = file_get_contents($file_path);
        
        // Check if already using absolute paths in onerror
        if (strpos($content, "onerror=\"this.src='/foreveryoungtours/assets/images/default-tour.jpg'") !== false) {
            echo "✓ Already using absolute paths in onerror\n";
            continue;
        }
        
        // Replace all onerror fallbacks with absolute paths
        $patterns = [
            "/onerror=\"this\.src='\.\.\/\.\.\/\.\.\/assets\/images\/default-tour\.jpg'/",
            "/onerror=\"this\.src='\.\.\/\.\.\/assets\/images\/default-tour\.jpg'/",
            "/onerror=\"this\.src='\.\.\/assets\/images\/default-tour\.jpg'/"
        ];
        
        $replacement = "onerror=\"this.src='/foreveryoungtours/assets/images/default-tour.jpg'";
        
        $updated_content = $content;
        $changes_made = false;
        
        foreach ($patterns as $pattern) {
            $new_content = preg_replace($pattern, $replacement, $updated_content);
            if ($new_content !== $updated_content) {
                $updated_content = $new_content;
                $changes_made = true;
            }
        }
        
        if ($changes_made) {
            if (file_put_contents($file_path, $updated_content)) {
                echo "✓ Fixed onerror fallbacks\n";
                $fixed_count++;
            } else {
                echo "✗ Failed to write\n";
                $error_count++;
            }
        } else {
            echo "✓ No onerror fallbacks found or already correct\n";
        }
        
    } catch (Exception $e) {
        echo "✗ Error: " . $e->getMessage() . "\n";
        $error_count++;
    }
}

echo "\n=== SUMMARY ===\n";
echo "Fixed: $fixed_count countries\n";
echo "Errors: $error_count countries\n";
echo "Total: " . count($countries) . " countries\n";

if ($fixed_count > 0) {
    echo "\n✅ All onerror fallbacks now use absolute paths!\n";
    echo "Broken images will now fallback to the correct default image.\n";
}

echo "</pre>";
?>
