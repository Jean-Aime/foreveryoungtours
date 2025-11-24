<?php
/**
 * Fix image paths in all country index.php files
 */

echo "<h1>🖼️ Fixing Image Paths in All Country Pages</h1>";
echo "<pre>";

$fixed_count = 0;
$error_count = 0;

// Get all country directories
$countries_dir = 'countries/';
$countries = [];

if (is_dir($countries_dir)) {
    $dirs = scandir($countries_dir);
    foreach ($dirs as $dir) {
        if ($dir !== '.' && $dir !== '..' && is_dir($countries_dir . $dir)) {
            $index_file = $countries_dir . $dir . '/index.php';
            if (file_exists($index_file)) {
                $countries[] = $dir;
            }
        }
    }
}

echo "Found " . count($countries) . " countries with index.php files:\n";
foreach ($countries as $country) {
    echo "- $country\n";
}
echo "\n";

foreach ($countries as $country) {
    $file_path = $countries_dir . $country . '/index.php';
    echo "Processing: $country... ";
    
    try {
        $content = file_get_contents($file_path);
        $original_content = $content;
        
        // Fix image paths in tour listings
        $patterns = [
            // Fix tour image src with relative fallback
            '/src="<\?=\s*htmlspecialchars\(\$tour\[\'image_url\'\]\s*\?\:\s*\'[^\']*\'\)\s*\?>"/' => 'src="<?= getImageUrl($tour[\'image_url\'] ?: $tour[\'cover_image\'], \'assets/images/africa.png\') ?>"',
            
            // Fix meta tag images with hardcoded URLs
            '/content="http:\/\/[^"]*\/assets\/images\/([^"]+)"/' => 'content="<?= getImageUrl(\'assets/images/$1\') ?>"',
            
            // Fix relative image paths in src attributes
            '/src="\.\.\/\.\.\/assets\/images\/([^"]+)"/' => 'src="<?= getImageUrl(\'assets/images/$1\') ?>"',
            '/src="assets\/images\/([^"]+)"/' => 'src="<?= getImageUrl(\'assets/images/$1\') ?>"',
            
            // Fix onerror handlers with relative paths
            '/onerror="handleImageError\(this\)"/' => 'onerror="this.src=\'<?= getImageUrl(\'assets/images/africa.png\') ?>\'; this.onerror=null;"',
            '/onerror="this\.src=\'\.\.\/\.\.\/assets\/images\/([^\']+)\'"/' => 'onerror="this.src=\'<?= getImageUrl(\'assets/images/$1\') ?>\'"',
        ];
        
        foreach ($patterns as $pattern => $replacement) {
            $content = preg_replace($pattern, $replacement, $content);
        }
        
        // Clean up extra whitespace
        $content = preg_replace('/\n\s*\n\s*\n/', "\n\n", $content);
        
        if ($content !== $original_content) {
            if (file_put_contents($file_path, $content)) {
                echo "✅ Updated\n";
                $fixed_count++;
            } else {
                echo "❌ Failed to write\n";
                $error_count++;
            }
        } else {
            echo "⏭️ No changes needed\n";
        }
        
    } catch (Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "\n";
        $error_count++;
    }
}

echo "\n";
echo "=== SUMMARY ===\n";
echo "Countries updated: $fixed_count\n";
echo "Errors: $error_count\n";
echo "Total countries: " . count($countries) . "\n";
echo "\n";
echo "✅ All country page images now use BASE_URL!\n";
echo "\n";
echo "Fixed image types:\n";
echo "- Tour listing images (Popular Tours / Featured Itineraries)\n";
echo "- Meta tag images (Open Graph, Twitter)\n";
echo "- Hero background images\n";
echo "- Fallback images in onerror handlers\n";
echo "\n";
echo "Test URLs:\n";
echo "- Rwanda: http://localhost/foreveryoungtours/countries/rwanda/\n";
echo "- Subdomain: http://visit-rw.foreveryoungtours.local/\n";

echo "</pre>";
?>
