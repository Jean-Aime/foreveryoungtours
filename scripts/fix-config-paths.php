<?php
/**
 * Fix config.php include paths in all country pages
 */

echo "<h1>🔧 Fixing config.php Include Paths</h1>";
echo "<pre>";

$fixed_count = 0;
$error_count = 0;

// Get all country tour detail pages
$countries_dir = 'countries/';
$files_to_fix = [];

if (is_dir($countries_dir)) {
    $dirs = scandir($countries_dir);
    foreach ($dirs as $dir) {
        if ($dir !== '.' && $dir !== '..' && is_dir($countries_dir . $dir)) {
            $tour_detail_file = $countries_dir . $dir . '/pages/tour-detail.php';
            if (file_exists($tour_detail_file)) {
                $files_to_fix[] = $tour_detail_file;
            }
        }
    }
}

echo "Found " . count($files_to_fix) . " country tour detail files to check:\n";
foreach ($files_to_fix as $file) {
    echo "- $file\n";
}
echo "\n";

foreach ($files_to_fix as $file_path) {
    echo "Processing: " . basename(dirname(dirname($file_path))) . "... ";
    
    try {
        $content = file_get_contents($file_path);
        $original_content = $content;
        
        // Fix incorrect config.php includes
        $patterns = [
            // Remove duplicate config includes
            "/require_once 'config\.php';\s*\n/" => '',
            '/require_once "config\.php";\s*\n/' => '',
            
            // Fix wrong path includes
            "/require_once '\.\.\/config\.php';\s*\n/" => '',
            '/require_once "\.\.\/config\.php";\s*\n/' => '',
            "/require_once '\.\.\/\.\.\/config\.php';\s*\n/" => '',
            '/require_once "\.\.\/\.\.\/config\.php";\s*\n/' => '',
        ];
        
        foreach ($patterns as $pattern => $replacement) {
            $content = preg_replace($pattern, $replacement, $content);
        }
        
        // Ensure correct config include exists
        if (strpos($content, "require_once '../../../config.php'") === false && 
            strpos($content, 'require_once "../../../config.php"') === false) {
            
            // Add the correct include after <?php
            if (preg_match('/^(<\?php\s*\n)/', $content, $matches)) {
                $replacement = $matches[1] . "require_once '../../../config.php';\n";
                $content = preg_replace('/^<\?php\s*\n/', $replacement, $content, 1);
            }
        }
        
        // Clean up extra whitespace
        $content = preg_replace('/\n\s*\n\s*\n/', "\n\n", $content);
        
        if ($content !== $original_content) {
            if (file_put_contents($file_path, $content)) {
                echo "✅ Fixed\n";
                $fixed_count++;
            } else {
                echo "❌ Failed to write\n";
                $error_count++;
            }
        } else {
            echo "⏭️ Already correct\n";
        }
        
    } catch (Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "\n";
        $error_count++;
    }
}

echo "\n";
echo "=== SUMMARY ===\n";
echo "Files fixed: $fixed_count\n";
echo "Errors: $error_count\n";
echo "Total checked: " . count($files_to_fix) . "\n";
echo "\n";
echo "✅ Config include paths fixed!\n";
echo "\n";
echo "Test URLs:\n";
echo "- Rwanda: http://localhost/foreveryoungtours/countries/rwanda/pages/tour-detail?id=28\n";
echo "- Kenya: http://localhost/foreveryoungtours/countries/kenya/pages/tour-detail?id=28\n";
echo "- Tanzania: http://localhost/foreveryoungtours/countries/tanzania/pages/tour-detail?id=28\n";

echo "</pre>";
?>
