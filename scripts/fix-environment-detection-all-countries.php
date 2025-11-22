<?php
/**
 * Apply environment detection to all country tour detail pages
 * This will make images work on both main domain and subdomains
 */

echo "<h1>Applying Environment Detection to All Countries</h1>";
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

// The new fixImagePath function with environment detection
$new_function = '        // Function to fix image paths for subdomain context
        function fixImagePath($imagePath) {
            if (empty($imagePath)) {
                // Detect if we\'re on a subdomain
                $is_subdomain = strpos($_SERVER[\'HTTP_HOST\'], \'visit-\') === 0 || strpos($_SERVER[\'HTTP_HOST\'], \'.foreveryoungtours.\') !== false;
                if ($is_subdomain) {
                    return \'../../../assets/images/default-tour.jpg\';
                } else {
                    return \'/foreveryoungtours/assets/images/default-tour.jpg\';
                }
            }

            // Detect if we\'re on a subdomain
            $is_subdomain = strpos($_SERVER[\'HTTP_HOST\'], \'visit-\') === 0 || strpos($_SERVER[\'HTTP_HOST\'], \'.foreveryoungtours.\') !== false;
            
            if ($is_subdomain) {
                // On subdomain, use relative paths from the country page context
                if (strpos($imagePath, \'uploads/\') === 0) {
                    return \'../../../\' . $imagePath;
                }
                
                if (strpos($imagePath, \'../\') === 0) {
                    // Already relative, but ensure correct depth
                    if (strpos($imagePath, \'../../assets/\') === 0) {
                        return \'../../../assets/\' . substr($imagePath, strlen(\'../../assets/\'));
                    }
                    return $imagePath;
                }
                
                if (strpos($imagePath, \'assets/\') === 0) {
                    return \'../../../\' . $imagePath;
                }
                
                // External URLs unchanged
                if (strpos($imagePath, \'http\') === 0) {
                    return $imagePath;
                }
                
                // Default case for subdomain
                return \'../../../\' . $imagePath;
            } else {
                // On main domain, use absolute paths
                if (strpos($imagePath, \'uploads/\') === 0) {
                    return \'/foreveryoungtours/\' . $imagePath;
                }
                
                if (strpos($imagePath, \'../\') === 0) {
                    $cleanPath = str_replace([\'../../../\', \'../../\', \'../\'], \'\', $imagePath);
                    return \'/foreveryoungtours/\' . $cleanPath;
                }
                
                if (strpos($imagePath, \'assets/\') === 0) {
                    return \'/foreveryoungtours/\' . $imagePath;
                }
                
                // External URLs unchanged
                if (strpos($imagePath, \'http\') === 0) {
                    return $imagePath;
                }
                
                // Default case for main domain
                return \'/foreveryoungtours/\' . $imagePath;
            }
        }';

$fixed_count = 0;
$error_count = 0;

foreach ($countries as $country) {
    $file_path = $countries_dir . $country . '/pages/tour-detail.php';
    echo "Processing: $country... ";
    
    try {
        $content = file_get_contents($file_path);
        
        // Skip Rwanda as it's already updated
        if ($country === 'rwanda') {
            echo "✓ Already updated (Rwanda)\n";
            continue;
        }
        
        // Replace the fixImagePath function
        $pattern = '/\/\/ Function to fix image paths for subdomain context\s*function fixImagePath\(\$imagePath\) \{.*?\}/s';
        $updated_content = preg_replace($pattern, $new_function, $content);
        
        if ($updated_content !== $content && $updated_content !== null) {
            if (file_put_contents($file_path, $updated_content)) {
                echo "✓ Updated with environment detection\n";
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
    echo "\n✅ All countries now use environment detection!\n";
    echo "Images should work on both main domain and subdomains.\n";
}

echo "</pre>";
?>
