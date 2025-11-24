<?php
/**
 * Fix image path depth issue in all country tour detail pages
 * Handles cases where database has ../../assets/ but needs ../../../assets/
 */

echo "<h1>Fixing Image Path Depth Issue</h1>";
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
        
        // Check if the fix is already applied
        if (strpos($content, "strpos(\$imagePath, '../../assets/') === 0") !== false) {
            echo "✓ Already has depth fix\n";
            continue;
        }
        
        // Find and replace the fixImagePath function
        $old_pattern = '/\/\/ If it\'s already a relative path starting with \.\.\/\s*if \(strpos\(\$imagePath, \'\.\.\/\'\) === 0\) \{\s*return \$imagePath;\s*\}/s';
        
        $new_code = "// If it's already a relative path starting with ../
            if (strpos(\$imagePath, '../') === 0) {
                // Check if it's the wrong depth (../../ instead of ../../../)
                if (strpos(\$imagePath, '../../assets/') === 0) {
                    return '../../../assets/' . substr(\$imagePath, strlen('../../assets/'));
                }
                return \$imagePath;
            }";
        
        $updated_content = preg_replace($old_pattern, $new_code, $content);
        
        if ($updated_content !== $content) {
            if (file_put_contents($file_path, $updated_content)) {
                echo "✓ Fixed depth issue\n";
                $fixed_count++;
            } else {
                echo "✗ Failed to write\n";
                $error_count++;
            }
        } else {
            // Try alternative approach - find the specific line and replace it
            $lines = explode("\n", $content);
            $updated = false;
            
            for ($i = 0; $i < count($lines); $i++) {
                if (strpos($lines[$i], "if (strpos(\$imagePath, '../') === 0) {") !== false) {
                    // Found the line, check if next line is just "return $imagePath;"
                    if (isset($lines[$i + 1]) && strpos($lines[$i + 1], "return \$imagePath;") !== false) {
                        // Replace the simple return with the enhanced logic
                        $indent = str_repeat(' ', 16); // Match indentation
                        $lines[$i + 1] = $indent . "// Check if it's the wrong depth (../../ instead of ../../../)";
                        array_splice($lines, $i + 2, 0, [
                            $indent . "if (strpos(\$imagePath, '../../assets/') === 0) {",
                            $indent . "    return '../../../assets/' . substr(\$imagePath, strlen('../../assets/'));",
                            $indent . "}",
                            $indent . "return \$imagePath;"
                        ]);
                        $updated = true;
                        break;
                    }
                }
            }
            
            if ($updated) {
                $updated_content = implode("\n", $lines);
                if (file_put_contents($file_path, $updated_content)) {
                    echo "✓ Fixed depth issue (alternative method)\n";
                    $fixed_count++;
                } else {
                    echo "✗ Failed to write (alternative method)\n";
                    $error_count++;
                }
            } else {
                echo "✓ No changes needed\n";
            }
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
    echo "\n✅ Image path depth issue fixed in all country tour detail pages!\n";
    echo "Now ../../assets/ paths will be converted to ../../../assets/ automatically.\n";
}

echo "</pre>";
?>
