<?php
// Script to fix all header includes in pages directory
// This will change include './header.php'; to include '../includes/header.php';

$pagesDir = __DIR__ . '/pages/';
$files = glob($pagesDir . '*.php');

$fixedCount = 0;
$errorCount = 0;

echo "Starting header include fix...\n";

foreach ($files as $file) {
    $filename = basename($file);
    
    // Skip the header.php file itself
    if ($filename === 'header.php') {
        continue;
    }
    
    $content = file_get_contents($file);
    
    // Check if file contains the wrong include
    if (strpos($content, "include './header.php';") !== false) {
        // Replace the wrong include with the correct one
        $newContent = str_replace("include './header.php';", "include '../includes/header.php';", $content);
        
        // Write back to file
        if (file_put_contents($file, $newContent)) {
            echo "✅ Fixed: $filename\n";
            $fixedCount++;
        } else {
            echo "❌ Error fixing: $filename\n";
            $errorCount++;
        }
    } else {
        echo "⏭️  Skipped: $filename (already correct or no header include)\n";
    }
}

echo "\n=== SUMMARY ===\n";
echo "Files fixed: $fixedCount\n";
echo "Errors: $errorCount\n";
echo "Fix completed!\n";

// Optional: Delete this script after running
// unlink(__FILE__);
?>
