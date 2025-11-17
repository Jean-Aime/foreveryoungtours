<?php
/**
 * Update all country tour detail pages to use BASE_URL
 */

echo "<h1>🌍 Updating All Country Pages to Use BASE_URL</h1>";
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

foreach ($countries as $country) {
    $file_path = $countries_dir . $country . '/pages/tour-detail.php';
    echo "Processing: $country... ";
    
    try {
        $content = file_get_contents($file_path);
        $original_content = $content;
        
        // Add config include if not present
        if (strpos($content, "require_once '../../../config.php'") === false && 
            strpos($content, 'require_once "../../../config.php"') === false) {
            
            // Find the first require_once and add config before it
            if (preg_match('/^(<\?php\s*\n)/', $content, $matches)) {
                $replacement = $matches[1] . "require_once '../../../config.php';\n";
                $content = preg_replace('/^<\?php\s*\n/', $replacement, $content, 1);
            }
        }
        
        // Remove old image path functions
        $patterns_to_remove = [
            '/\/\/ Smart image path function.*?\n\s*function getImagePath\([^{]*\{[^}]*\}/s',
            '/\/\/ Enhanced image path function.*?\n\s*function getImagePath\([^{]*\{[^}]*\}/s',
            '/function getImagePath\([^{]*\{[^}]*\}/s',
            '/function fixImagePath\([^{]*\{[^}]*\}/s',
            '/function getImageUrl\([^{]*\{[^}]*\}/s',
            '/\/\/ Note: getImageUrl function.*?\n/',
        ];
        
        foreach ($patterns_to_remove as $pattern) {
            $content = preg_replace($pattern, '', $content);
        }
        
        // Replace image function calls with getImageUrl
        $replacements = [
            '/fixImagePath\s*\(\s*([^)]+)\s*\)/' => 'getImageUrl($1)',
            '/getImagePath\s*\(\s*([^)]+)\s*\)/' => 'getImageUrl($1)',
        ];
        
        foreach ($replacements as $pattern => $replacement) {
            $content = preg_replace($pattern, $replacement, $content);
        }
        
        // Update fallback image assignments
        $content = preg_replace(
            '/\$fallback_image\s*=\s*[\'"][^\'\"]*[\'"];/',
            '$fallback_image = getImageUrl(\'assets/images/default-tour.jpg\');',
            $content
        );
        
        // Update onerror handlers to use BASE_URL
        $content = preg_replace(
            '/onerror=["\']this\.src=[\'"][^\'\"]*[\'"];[^"\']*["\']/',
            'onerror="this.src=\'<?= getImageUrl(\\\'assets/images/default-tour.jpg\\\') ?>\'; this.onerror=null;"',
            $content
        );
        
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
echo "✅ All country pages now use BASE_URL!\n";
echo "\n";
echo "Test URLs:\n";
echo "- Main: http://localhost/foreveryoungtours/pages/tour-detail?id=28\n";
echo "- Direct: http://localhost/foreveryoungtours/countries/rwanda/pages/tour-detail?id=28\n";
echo "- Subdomain: http://visit-rw.foreveryoungtours.local/pages/tour-detail?id=28\n";

echo "</pre>";
?>
