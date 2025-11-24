<?php
/**
 * Test Admin Config
 * 
 * This script tests if the admin config.php file is working correctly
 */

echo "<h1>🔧 Admin Config Test</h1>\n";
echo "<style>body{font-family:Arial,sans-serif;margin:20px;} .success{color:#4caf50;} .error{color:#f44336;} .info{color:#2196f3;}</style>\n";

echo "<h2>📁 Testing Admin Config File</h2>\n";

// Test if admin/config.php exists
if (file_exists('admin/config.php')) {
    echo "<p class='success'>✅ admin/config.php exists</p>\n";
    
    // Test if we can include it
    try {
        require_once 'admin/config.php';
        echo "<p class='success'>✅ admin/config.php included successfully</p>\n";
        
        // Test if BASE_URL is defined
        if (defined('BASE_URL')) {
            echo "<p class='success'>✅ BASE_URL is defined: " . BASE_URL . "</p>\n";
        } else {
            echo "<p class='error'>❌ BASE_URL is not defined</p>\n";
        }
        
        // Test if getImageUrl function exists
        if (function_exists('getImageUrl')) {
            echo "<p class='success'>✅ getImageUrl function is available</p>\n";
            
            // Test the function
            $testUrl = getImageUrl('assets/images/test.jpg');
            echo "<p class='info'>🔗 Test URL: $testUrl</p>\n";
        } else {
            echo "<p class='error'>❌ getImageUrl function is not available</p>\n";
        }
        
    } catch (Exception $e) {
        echo "<p class='error'>❌ Error including admin/config.php: " . $e->getMessage() . "</p>\n";
    }
} else {
    echo "<p class='error'>❌ admin/config.php does not exist</p>\n";
}

echo "<h2>🔗 Test Links</h2>\n";
echo "<ul>\n";
echo "<li><a href='admin/tours.php' target='_blank'>Admin Tours Page</a></li>\n";
echo "<li><a href='admin/index.php' target='_blank'>Admin Dashboard</a></li>\n";
echo "<li><a href='admin/dashboard.php' target='_blank'>Admin Dashboard (Alternative)</a></li>\n";
echo "</ul>\n";

echo "<h2>📊 Admin Directory Contents</h2>\n";
if (is_dir('admin')) {
    $adminFiles = scandir('admin');
    echo "<ul>\n";
    foreach ($adminFiles as $file) {
        if ($file !== '.' && $file !== '..' && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
            echo "<li>📄 $file</li>\n";
        }
    }
    echo "</ul>\n";
} else {
    echo "<p class='error'>❌ Admin directory not found</p>\n";
}
?>
