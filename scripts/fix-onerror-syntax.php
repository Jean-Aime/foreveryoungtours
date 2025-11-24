<?php
/**
 * Fix onerror syntax issues in all PHP files
 */

echo "<h1>🔧 Fixing onerror Syntax Issues</h1>";
echo "<pre>";

$fixed_count = 0;
$error_count = 0;

// Get all PHP files to process
$files_to_process = [];

// Add main pages
$main_dirs = ['pages', 'admin', 'client', 'mca', 'advisor'];
foreach ($main_dirs as $dir) {
    if (is_dir($dir)) {
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $files_to_process[] = $file->getPathname();
            }
        }
    }
}

// Add country pages
if (is_dir('countries')) {
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('countries'));
    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $files_to_process[] = $file->getPathname();
        }
    }
}

echo "Checking " . count($files_to_process) . " PHP files for onerror syntax issues...\n\n";

foreach ($files_to_process as $file_path) {
    try {
        $content = file_get_contents($file_path);
        $original_content = $content;
        
        // Fix escaped quotes in onerror handlers
        $patterns = [
            // Fix getImageUrl with escaped quotes
            '/onerror="this\.src=\'<\?=\s*getImageUrl\(\\\'([^\\\']+)\\\'\)\s*\?>\';/' => 'onerror="this.src=\'<?= getImageUrl(\'$1\') ?>\';',
            
            // Fix other common onerror patterns with escaped quotes
            '/onerror="([^"]*\\\\\'[^"]*)"/' => function($matches) {
                return 'onerror="' . str_replace('\\\'', '\'', $matches[1]) . '"';
            }
        ];
        
        $has_changes = false;
        
        foreach ($patterns as $pattern => $replacement) {
            if (is_callable($replacement)) {
                $new_content = preg_replace_callback($pattern, $replacement, $content);
            } else {
                $new_content = preg_replace($pattern, $replacement, $content);
            }
            
            if ($new_content !== $content) {
                $content = $new_content;
                $has_changes = true;
            }
        }
        
        if ($has_changes) {
            echo "Processing: " . $file_path . "... ";
            
            if (file_put_contents($file_path, $content)) {
                echo "✅ Fixed\n";
                $fixed_count++;
            } else {
                echo "❌ Failed to write\n";
                $error_count++;
            }
        }
        
    } catch (Exception $e) {
        echo "❌ Error processing " . $file_path . ": " . $e->getMessage() . "\n";
        $error_count++;
    }
}

echo "\n";
echo "=== SUMMARY ===\n";
echo "Files fixed: $fixed_count\n";
echo "Errors: $error_count\n";
echo "Total checked: " . count($files_to_process) . "\n";
echo "\n";

if ($fixed_count > 0) {
    echo "✅ onerror syntax issues fixed!\n";
} else {
    echo "✅ No onerror syntax issues found!\n";
}

echo "\n";
echo "Test your subdomain now:\n";
echo "🎯 http://visit-rw.foreveryoungtours.local/pages/tour-detail?id=28\n";

echo "</pre>";
?>
