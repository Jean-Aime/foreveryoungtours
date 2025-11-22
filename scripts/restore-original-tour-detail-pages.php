<?php
/**
 * Restore all country tour detail pages to their original state
 * Remove the environment detection function and restore simple relative paths
 */

echo "<h1>Restoring Original Tour Detail Pages</h1>";
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
        
        // Skip Rwanda as it's already restored
        if ($country === 'rwanda') {
            echo "✓ Already restored (Rwanda)\n";
            continue;
        }
        
        // Remove the environment detection function
        $pattern = '/\/\/ Function to fix image paths for subdomain context\s*function fixImagePath\(\$imagePath\) \{.*?\}/s';
        $updated_content = preg_replace($pattern, '', $content);
        
        // Replace fixImagePath() calls with simple relative path logic
        $patterns_and_replacements = [
            // Background image
            [
                'pattern' => '/\$bg_image = fixImagePath\(\$tour\[\'cover_image\'\] \?\: \$tour\[\'image_url\'\]\);/',
                'replacement' => '$bg_image = $tour[\'cover_image\'] ?: $tour[\'image_url\'] ?: \'../../../assets/images/default-tour.jpg\';
        if (strpos($bg_image, \'uploads/\') === 0) {
            $bg_image = \'../../../\' . $bg_image;
        }'
            ],
            // Gallery images
            [
                'pattern' => '/\$image_src = fixImagePath\(\$image\);/',
                'replacement' => '$image_src = $image;
                if (strpos($image, \'uploads/\') === 0) {
                    $image_src = \'../../../\' . $image;
                }'
            ],
            // Related tour images
            [
                'pattern' => '/\$related_image = fixImagePath\(\$related\[\'cover_image\'\] \?\: \$related\[\'image_url\'\]\);/',
                'replacement' => '$related_image = $related[\'cover_image\'] ?: $related[\'image_url\'] ?: \'../../../assets/images/default-tour.jpg\';
                    if (strpos($related_image, \'uploads/\') === 0) {
                        $related_image = \'../../../\' . $related_image;
                    }'
            ],
            // Environment detection onerror fallbacks - restore to simple relative paths
            [
                'pattern' => '/onerror="this\.src=\'<\?php echo \(strpos\(\$_SERVER\[\'HTTP_HOST\'\], \'visit-\'\) === 0 \|\| strpos\(\$_SERVER\[\'HTTP_HOST\'\], \'\.foreveryoungtours\.\'\) !== false\) \? \'\.\.\/\.\.\/\.\.\/assets\/images\/default-tour\.jpg\' : \'\/foreveryoungtours\/assets\/images\/default-tour\.jpg\'; \?\>\'; this\.onerror=null;"/',
                'replacement' => 'onerror="this.src=\'../../../assets/images/default-tour.jpg\'; this.onerror=null;"'
            ]
        ];
        
        $changes_made = false;
        foreach ($patterns_and_replacements as $pr) {
            $new_content = preg_replace($pr['pattern'], $pr['replacement'], $updated_content);
            if ($new_content !== $updated_content && $new_content !== null) {
                $updated_content = $new_content;
                $changes_made = true;
            }
        }
        
        if ($changes_made || $updated_content !== $content) {
            if (file_put_contents($file_path, $updated_content)) {
                echo "✓ Restored to original state\n";
                $fixed_count++;
            } else {
                echo "✗ Failed to write\n";
                $error_count++;
            }
        } else {
            echo "✓ No changes needed\n";
        }
        
    } catch (Exception $e) {
        echo "✗ Error: " . $e->getMessage() . "\n";
        $error_count++;
    }
}

echo "\n=== SUMMARY ===\n";
echo "Restored: $fixed_count countries\n";
echo "Errors: $error_count countries\n";
echo "Total: " . count($countries) . " countries\n";

if ($fixed_count > 0) {
    echo "\n✅ All countries restored to original state!\n";
    echo "Simple relative paths (../../../) are now used consistently.\n";
}

echo "</pre>";
?>
