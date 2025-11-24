<?php
/**
 * Update All Home Links
 * 
 * This script finds and updates all references to index.php to use /Home instead
 */

echo "<h1>🔗 Updating All Home Links</h1>\n";
echo "<style>body{font-family:Arial,sans-serif;margin:20px;} .success{color:#4caf50;} .error{color:#f44336;} .info{color:#2196f3;} .warning{color:#ff9800;}</style>\n";

$updated_count = 0;
$skipped_count = 0;
$error_count = 0;

// Directories to scan
$directories = ['pages', 'includes', 'countries'];

// Patterns to replace
$patterns = [
    // For pages directory (relative path)
    'pages' => [
        '/href=["\']\.\.\/index\.php["\']/i' => 'href="../Home"',
        '/href=["\']\.\.\/foreveryoungtours\/index\.php["\']/i' => 'href="../Home"',
    ],
    // For includes directory (relative path)
    'includes' => [
        '/href=["\']index\.php["\']/i' => 'href="Home"',
        '/href=["\']\.\/index\.php["\']/i' => 'href="./Home"',
    ],
    // For countries directory (relative path)
    'countries' => [
        '/href=["\']\.\.\/\.\.\/index\.php["\']/i' => 'href="../../Home"',
        '/href=["\']\.\.\/\.\.\/foreveryoungtours\/index\.php["\']/i' => 'href="../../Home"',
    ],
];

echo "<h2>📁 Scanning Directories</h2>\n";

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        echo "<p class='warning'>⚠️ Directory $dir does not exist, skipping</p>\n";
        continue;
    }
    
    echo "<h3>📂 Processing: $dir</h3>\n";
    
    // Get all PHP files in the directory
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS)
    );
    
    foreach ($iterator as $file) {
        if ($file->getExtension() !== 'php') {
            continue;
        }
        
        $filePath = $file->getPathname();
        $relativePath = str_replace('\\', '/', $filePath);
        
        echo "<p class='info'>🔍 Checking: $relativePath</p>\n";
        
        try {
            $content = file_get_contents($filePath);
            $originalContent = $content;
            
            // Check if file contains index.php references
            if (!preg_match('/href=["\'][^"\']*index\.php[^"\']*["\']/i', $content)) {
                echo "<p style='color:#6b7280;margin-left:20px;'>⏭️ No index.php links found</p>\n";
                $skipped_count++;
                continue;
            }
            
            // Apply patterns based on directory
            $patternsToApply = [];
            if (strpos($relativePath, 'pages/') === 0) {
                $patternsToApply = $patterns['pages'];
            } elseif (strpos($relativePath, 'includes/') === 0) {
                $patternsToApply = $patterns['includes'];
            } elseif (strpos($relativePath, 'countries/') === 0) {
                $patternsToApply = $patterns['countries'];
            }
            
            // Apply replacements
            $replacements = 0;
            foreach ($patternsToApply as $pattern => $replacement) {
                $newContent = preg_replace($pattern, $replacement, $content, -1, $count);
                if ($count > 0) {
                    $content = $newContent;
                    $replacements += $count;
                }
            }
            
            if ($replacements > 0) {
                if (file_put_contents($filePath, $content)) {
                    echo "<p class='success' style='margin-left:20px;'>✅ Updated $replacements link(s)</p>\n";
                    $updated_count++;
                } else {
                    echo "<p class='error' style='margin-left:20px;'>❌ Failed to write file</p>\n";
                    $error_count++;
                }
            } else {
                echo "<p style='color:#6b7280;margin-left:20px;'>⏭️ No changes needed</p>\n";
                $skipped_count++;
            }
            
        } catch (Exception $e) {
            echo "<p class='error' style='margin-left:20px;'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</p>\n";
            $error_count++;
        }
    }
}

echo "<h2>📊 Summary</h2>\n";
echo "<div style='background:#f0fdf4;padding:20px;border-radius:8px;border-left:4px solid #10b981;'>\n";
echo "<p class='success'>✅ Files updated: $updated_count</p>\n";
echo "<p style='color:#6b7280;'>⏭️ Files skipped: $skipped_count</p>\n";
if ($error_count > 0) {
    echo "<p class='error'>❌ Errors: $error_count</p>\n";
}
echo "</div>\n";

echo "<h2>🧪 Test the Changes</h2>\n";
echo "<ul>\n";
echo "<li><a href='/foreveryoungtours/Home' target='_blank'>Test: /foreveryoungtours/Home</a></li>\n";
echo "<li><a href='/foreveryoungtours/index.php' target='_blank'>Test: /foreveryoungtours/index.php (should redirect)</a></li>\n";
echo "<li><a href='test-home-url.php' target='_blank'>View URL Rewriting Test Page</a></li>\n";
echo "</ul>\n";
?>
