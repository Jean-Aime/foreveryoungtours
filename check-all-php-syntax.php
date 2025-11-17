<?php
/**
 * Check PHP Syntax for All Files
 */

echo "=" . str_repeat("=", 100) . "\n";
echo "PHP SYNTAX CHECK - ALL FILES\n";
echo "=" . str_repeat("=", 100) . "\n\n";

$errors = [];
$checked = 0;
$passed = 0;

// Function to check PHP syntax
function checkPhpSyntax($file) {
    $output = [];
    $return_var = 0;
    exec("php -l \"$file\" 2>&1", $output, $return_var);
    return [
        'success' => $return_var === 0,
        'output' => implode("\n", $output)
    ];
}

// Function to recursively find PHP files
function findPhpFiles($dir, &$files = []) {
    if (!is_dir($dir)) {
        return $files;
    }
    
    $items = scandir($dir);
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }
        
        $path = $dir . '/' . $item;
        
        if (is_dir($path)) {
            // Skip vendor, node_modules, .git directories
            if (in_array($item, ['vendor', 'node_modules', '.git', '.vscode'])) {
                continue;
            }
            findPhpFiles($path, $files);
        } elseif (pathinfo($path, PATHINFO_EXTENSION) === 'php') {
            $files[] = $path;
        }
    }
    
    return $files;
}

// Find all PHP files
echo "Finding PHP files...\n";
$php_files = findPhpFiles('.');
echo "Found " . count($php_files) . " PHP files\n\n";

// Check each file
echo "Checking syntax...\n";
echo str_repeat("-", 100) . "\n";

foreach ($php_files as $file) {
    $checked++;
    $result = checkPhpSyntax($file);
    
    if ($result['success']) {
        $passed++;
        // Only show errors, not successes (too verbose)
    } else {
        $errors[] = [
            'file' => $file,
            'error' => $result['output']
        ];
        echo "❌ $file\n";
        echo "   " . str_replace("\n", "\n   ", $result['output']) . "\n\n";
    }
}

// Summary
echo "\n";
echo "=" . str_repeat("=", 100) . "\n";
echo "SUMMARY\n";
echo "=" . str_repeat("=", 100) . "\n";
echo "Total files checked: $checked\n";
echo "Passed: $passed\n";
echo "Failed: " . count($errors) . "\n";
echo "\n";

if (count($errors) === 0) {
    echo "✅ ALL PHP FILES HAVE VALID SYNTAX!\n";
} else {
    echo "❌ FOUND " . count($errors) . " FILES WITH SYNTAX ERRORS\n";
    echo "\nFiles with errors:\n";
    foreach ($errors as $i => $error) {
        echo ($i + 1) . ". " . $error['file'] . "\n";
    }
}

echo "\n";
?>

