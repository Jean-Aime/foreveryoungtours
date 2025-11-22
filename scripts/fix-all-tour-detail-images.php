<?php
/**
 * Fix image paths in all country tour detail pages
 */

echo "<h1>Fixing Tour Detail Image Paths</h1>";
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

// The image handling function to add
$image_function = '
// Function to fix image paths for subdomain context
function fixImagePath($imagePath) {
    if (empty($imagePath)) {
        return \'../../../assets/images/default-tour.jpg\';
    }
    
    // If it\'s an upload path, prepend the correct relative path
    if (strpos($imagePath, \'uploads/\') === 0) {
        return \'../../../\' . $imagePath;
    }
    
    // If it\'s already a relative path starting with ../
    if (strpos($imagePath, \'../\') === 0) {
        return $imagePath;
    }
    
    // If it\'s an assets path
    if (strpos($imagePath, \'assets/\') === 0) {
        return \'../../../\' . $imagePath;
    }
    
    // If it\'s an external URL, return as-is
    if (strpos($imagePath, \'http\') === 0) {
        return $imagePath;
    }
    
    // Default case - assume it needs the full relative path
    return \'../../../\' . $imagePath;
}
';

$fixed_count = 0;
$error_count = 0;

foreach ($countries as $country) {
    $file_path = $countries_dir . $country . '/pages/tour-detail.php';
    echo "Processing: $country... ";
    
    try {
        $content = file_get_contents($file_path);
        
        // Check if function already exists
        if (strpos($content, 'function fixImagePath') !== false) {
            echo "✓ Already has fixImagePath function\n";
            continue;
        }
        
        // Add the function after the database require
        $pattern = '/(require_once \'\.\.\/\.\.\/\.\.\/config\/database\.php\';)/';
        $replacement = '$1' . $image_function;
        $content = preg_replace($pattern, $replacement, $content);
        
        // Fix the main background image
        $content = preg_replace(
            '/\$bg_image = \$tour\[\'cover_image\'\] \?: \$tour\[\'image_url\'\] \?: \'\.\.\/\.\.\/\.\.\/assets\/images\/default-tour\.jpg\';[\s\S]*?if \(strpos\(\$bg_image, \'uploads\/\'\) === 0\) \{[\s\S]*?\$bg_image = \'\.\.\/\.\.\/\.\.\/' . '\' \. \$bg_image;[\s\S]*?\}/',
            '$bg_image = fixImagePath($tour[\'cover_image\'] ?: $tour[\'image_url\']);',
            $content
        );
        
        // Fix gallery images
        $content = preg_replace(
            '/\$image_src = \$image;[\s\S]*?if \(strpos\(\$image, \'uploads\/\'\) === 0\) \{[\s\S]*?\$image_src = \'\.\.\/\.\.\/\.\.\/' . '\' \. \$image;[\s\S]*?\}/',
            '$image_src = fixImagePath($image);',
            $content
        );
        
        // Fix related tour images
        $content = preg_replace(
            '/\$related_image = \$related\[\'cover_image\'\] \?: \$related\[\'image_url\'\] \?: \'\.\.\/\.\.\/\.\.\/assets\/images\/default-tour\.jpg\';[\s\S]*?if \(strpos\(\$related_image, \'uploads\/\'\) === 0\) \{[\s\S]*?\$related_image = \'\.\.\/\.\.\/\.\.\/' . '\' \. \$related_image;[\s\S]*?\}/',
            '$related_image = fixImagePath($related[\'cover_image\'] ?: $related[\'image_url\']);',
            $content
        );
        
        // Write the updated content
        if (file_put_contents($file_path, $content)) {
            echo "✓ Fixed\n";
            $fixed_count++;
        } else {
            echo "✗ Failed to write\n";
            $error_count++;
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
    echo "\n✅ Image paths have been fixed in all country tour detail pages!\n";
    echo "Now all subdomain tour detail pages should display images correctly.\n";
}

echo "</pre>";
?>
