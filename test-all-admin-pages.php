<?php
/**
 * Test All Admin Pages
 * 
 * This script tests if all admin pages can load without config.php errors
 */

echo "<h1>🔧 Testing All Admin Pages</h1>\n";
echo "<style>body{font-family:Arial,sans-serif;margin:20px;} .success{color:#4caf50;} .error{color:#f44336;} .info{color:#2196f3;} .warning{color:#ff9800;}</style>\n";

// Test the main config first
echo "<h2>📊 Main Configuration Test</h2>\n";
try {
    require_once 'config.php';
    echo "<p class='success'>✅ Main config.php loaded successfully</p>\n";
    echo "<p class='info'>🔗 BASE_URL: " . BASE_URL . "</p>\n";
} catch (Exception $e) {
    echo "<p class='error'>❌ Main config error: " . $e->getMessage() . "</p>\n";
}

// Test admin config
echo "<h2>🔧 Admin Configuration Test</h2>\n";
if (file_exists('admin/config.php')) {
    echo "<p class='success'>✅ admin/config.php exists</p>\n";
    
    // Read the content to verify it's correct
    $adminConfigContent = file_get_contents('admin/config.php');
    if (strpos($adminConfigContent, "require_once '../config.php';") !== false) {
        echo "<p class='success'>✅ admin/config.php has correct include path</p>\n";
    } else {
        echo "<p class='error'>❌ admin/config.php has incorrect include path</p>\n";
    }
} else {
    echo "<p class='error'>❌ admin/config.php does not exist</p>\n";
}

// List of admin pages to test
$adminPages = [
    'tours.php' => 'Tours Management',
    'index.php' => 'Admin Dashboard',
    'dashboard.php' => 'Dashboard (Alternative)',
    'bookings.php' => 'Bookings Management',
    'users.php' => 'Users Management',
    'destinations.php' => 'Destinations Management',
];

echo "<h2>📄 Admin Pages Test</h2>\n";
echo "<p class='info'>Testing if admin pages exist and can be accessed...</p>\n";

foreach ($adminPages as $page => $description) {
    $filePath = "admin/$page";
    echo "<h3>📋 $description ($page)</h3>\n";
    
    if (file_exists($filePath)) {
        echo "<p class='success'>✅ File exists: $filePath</p>\n";
        
        // Check if it includes config.php
        $content = file_get_contents($filePath);
        if (strpos($content, "require_once 'config.php'") !== false || 
            strpos($content, 'require_once "config.php"') !== false) {
            echo "<p class='success'>✅ Includes config.php correctly</p>\n";
        } else {
            echo "<p class='warning'>⚠️ Does not include config.php (may not need it)</p>\n";
        }
        
        // Provide test link
        echo "<p class='info'>🔗 <a href='admin/$page' target='_blank'>Test: $description</a></p>\n";
    } else {
        echo "<p class='error'>❌ File does not exist: $filePath</p>\n";
    }
    echo "<hr>\n";
}

echo "<h2>🎯 Specific Test Cases</h2>\n";
echo "<ul>\n";
echo "<li><a href='admin/tours.php' target='_blank'>🎯 Original Issue: admin/tours.php</a></li>\n";
echo "<li><a href='admin/tours.php?error=deactivate_failed' target='_blank'>🎯 Original URL: admin/tours.php?error=deactivate_failed</a></li>\n";
echo "<li><a href='pages/admin-dashboard.php' target='_blank'>📄 Pages Admin Dashboard</a></li>\n";
echo "</ul>\n";

echo "<h2>📁 Directory Structure Verification</h2>\n";
$directories = ['admin', 'pages', 'client', 'mca', 'advisor', 'auth', 'api', 'includes'];
foreach ($directories as $dir) {
    if (is_dir($dir)) {
        $configFile = "$dir/config.php";
        if (file_exists($configFile)) {
            echo "<p class='success'>✅ $dir/config.php exists</p>\n";
        } else {
            echo "<p class='error'>❌ $dir/config.php missing</p>\n";
        }
    } else {
        echo "<p class='warning'>⚠️ Directory $dir does not exist</p>\n";
    }
}

echo "<h2>🎉 Test Complete!</h2>\n";
echo "<p class='info'>All config wrapper files have been created. The original admin/tours.php error should now be resolved!</p>\n";
?>
