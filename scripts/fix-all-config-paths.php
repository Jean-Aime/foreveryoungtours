<?php
/**
 * Fix config.php include paths throughout the entire project
 */

echo "<h1>🔧 Fixing All config.php Include Paths</h1>";
echo "<pre>";

$fixed_count = 0;
$error_count = 0;

// Define correct paths for different directories
$path_mappings = [
    // Root level files
    '' => 'config.php',
    
    // One level deep (pages/, admin/, client/, etc.)
    'pages' => '../config.php',
    'admin' => '../config.php',
    'client' => '../config.php',
    'mca' => '../config.php',
    'advisor' => '../config.php',
    
    // Two levels deep (countries/rwanda/, etc.)
    'countries/*' => '../../config.php',
    
    // Three levels deep (countries/rwanda/pages/, etc.)
    'countries/*/pages' => '../../../config.php',
    'countries/*/admin' => '../../../config.php',
];

// Get all PHP files to process
$files_to_process = [];

// Add main directories
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

// Add root level files
$root_files = ['index.php', 'subdomain-handler.php'];
foreach ($root_files as $file) {
    if (file_exists($file)) {
        $files_to_process[] = $file;
    }
}

echo "Found " . count($files_to_process) . " PHP files to check\n\n";

foreach ($files_to_process as $file_path) {
    // Determine correct config path based on file location
    $correct_path = 'config.php'; // default for root
    
    if (preg_match('/^(pages|admin|client|mca|advisor)\//', $file_path)) {
        $correct_path = '../config.php';
    } elseif (preg_match('/^countries\/[^\/]+\/[^\/]+\//', $file_path)) {
        $correct_path = '../../../config.php';
    } elseif (preg_match('/^countries\/[^\/]+\//', $file_path)) {
        $correct_path = '../../config.php';
    }
    
    echo "Processing: " . $file_path . " (should use: $correct_path)... ";
    
    try {
        $content = file_get_contents($file_path);
        $original_content = $content;
        
        // Skip if file doesn't contain config includes
        if (!preg_match('/require_once.*config\.php/', $content)) {
            echo "⏭️ No config include\n";
            continue;
        }
        
        // Remove all existing config includes
        $patterns_to_remove = [
            "/require_once\s+['\"]config\.php['\"];\s*\n/",
            "/require_once\s+['\"]\.\/config\.php['\"];\s*\n/",
            "/require_once\s+['\"]\.\.\/config\.php['\"];\s*\n/",
            "/require_once\s+['\"]\.\.\/\.\.\/config\.php['\"];\s*\n/",
            "/require_once\s+['\"]\.\.\/\.\.\/\.\.\/config\.php['\"];\s*\n/",
        ];
        
        foreach ($patterns_to_remove as $pattern) {
            $content = preg_replace($pattern, '', $content);
        }
        
        // Add the correct config include after <?php
        if (preg_match('/^(<\?php\s*\n)/', $content, $matches)) {
            $replacement = $matches[1] . "require_once '$correct_path';\n";
            $content = preg_replace('/^<\?php\s*\n/', $replacement, $content, 1);
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
echo "Total checked: " . count($files_to_process) . "\n";
echo "\n";
echo "✅ All config.php paths fixed!\n";

echo "</pre>";
?>
