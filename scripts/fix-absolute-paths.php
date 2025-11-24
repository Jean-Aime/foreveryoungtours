<?php
/**
 * Fix image paths to use absolute paths instead of relative paths for subdomain context
 */

echo "<h1>Converting to Absolute Paths for Subdomain Images</h1>";
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
        
        // Check if already using absolute paths
        if (strpos($content, "return '/foreveryoungtours/' . \$imagePath;") !== false) {
            echo "✓ Already using absolute paths\n";
            continue;
        }
        
        // Replace the entire fixImagePath function with absolute path version
        $old_function_pattern = '/\/\/ Function to fix image paths for subdomain context\s*function fixImagePath\(\$imagePath\) \{.*?\}/s';
        
        $new_function = "// Function to fix image paths for subdomain context
        function fixImagePath(\$imagePath) {
            if (empty(\$imagePath)) {
                return '/foreveryoungtours/assets/images/default-tour.jpg';
            }

            // If it's an upload path, use absolute path from web root
            if (strpos(\$imagePath, 'uploads/') === 0) {
                return '/foreveryoungtours/' . \$imagePath;
            }

            // If it's already a relative path starting with ../
            if (strpos(\$imagePath, '../') === 0) {
                // Check if it's the wrong depth (../../ instead of ../../../)
                if (strpos(\$imagePath, '../../assets/') === 0) {
                    return '/foreveryoungtours/assets/' . substr(\$imagePath, strlen('../../assets/'));
                }
                // Convert any relative path to absolute
                \$cleanPath = str_replace(['../../../', '../../', '../'], '', \$imagePath);
                return '/foreveryoungtours/' . \$cleanPath;
            }

            // If it's an assets path
            if (strpos(\$imagePath, 'assets/') === 0) {
                return '/foreveryoungtours/' . \$imagePath;
            }

            // If it's an external URL, return as-is
            if (strpos(\$imagePath, 'http') === 0) {
                return \$imagePath;
            }

            // Default case - assume it needs the full absolute path
            return '/foreveryoungtours/' . \$imagePath;
        }";
        
        $updated_content = preg_replace($old_function_pattern, $new_function, $content);
        
        if ($updated_content !== $content && $updated_content !== null) {
            if (file_put_contents($file_path, $updated_content)) {
                echo "✓ Converted to absolute paths\n";
                $fixed_count++;
            } else {
                echo "✗ Failed to write\n";
                $error_count++;
            }
        } else {
            echo "✓ No changes needed or pattern not found\n";
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
    echo "\n✅ All country tour detail pages now use absolute paths!\n";
    echo "Images should now display correctly on subdomains.\n";
}

echo "</pre>";
?>
