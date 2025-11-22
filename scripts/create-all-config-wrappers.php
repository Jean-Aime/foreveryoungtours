<?php
/**
 * Create All Config Wrapper Files
 * 
 * This script creates config.php wrapper files in all directories that need them
 */

echo "<h1>🔧 Creating All Config Wrapper Files</h1>\n";
echo "<style>body{font-family:Arial,sans-serif;margin:20px;} .success{color:#4caf50;} .error{color:#f44336;} .info{color:#2196f3;} .warning{color:#ff9800;}</style>\n";

// Define directories that need config.php wrapper files
$directories = [
    'admin' => '../config.php',
    'pages' => '../config.php',
    'client' => '../config.php',
    'mca' => '../config.php',
    'advisor' => '../config.php',
    'auth' => '../config.php',
    'api' => '../config.php',
    'includes' => '../config.php',
];

// Also add country directories
if (is_dir('countries')) {
    $countries = scandir('countries');
    foreach ($countries as $country) {
        if ($country !== '.' && $country !== '..' && is_dir('countries/' . $country)) {
            $directories['countries/' . $country] = '../../config.php';
            
            // Also add pages subdirectories
            if (is_dir('countries/' . $country . '/pages')) {
                $directories['countries/' . $country . '/pages'] = '../../../config.php';
            }
            
            // Also add admin subdirectories if they exist
            if (is_dir('countries/' . $country . '/admin')) {
                $directories['countries/' . $country . '/admin'] = '../../../config.php';
            }
        }
    }
}

echo "<p class='info'>📁 Found " . count($directories) . " directories to process</p>\n";

foreach ($directories as $dir => $configPath) {
    echo "<h3>📂 Processing: $dir</h3>\n";
    
    // Check if directory exists
    if (!is_dir($dir)) {
        echo "<p class='warning'>⚠️ Directory $dir does not exist, skipping</p>\n";
        continue;
    }
    
    $configFile = $dir . '/config.php';
    
    // Check if config.php already exists
    if (file_exists($configFile)) {
        echo "<p class='info'>ℹ️ Config file already exists: $configFile</p>\n";
        continue;
    }
    
    // Create the config wrapper content
    $configContent = "<?php\n";
    $configContent .= "/**\n";
    $configContent .= " * Configuration File for " . ucfirst(str_replace('/', ' ', $dir)) . " Directory\n";
    $configContent .= " * \n";
    $configContent .= " * This file includes the main config from the root directory\n";
    $configContent .= " * and ensures BASE_URL works correctly for files in this directory\n";
    $configContent .= " */\n\n";
    $configContent .= "// Include the main config file from root\n";
    $configContent .= "require_once '$configPath';\n\n";
    $configContent .= "// All functions and constants are now available from the main config.php\n";
    $configContent .= "// No additional configuration needed - everything is handled by the main config.php\n";
    $configContent .= "?>";
    
    // Write the config file
    if (file_put_contents($configFile, $configContent)) {
        echo "<p class='success'>✅ Created: $configFile</p>\n";
    } else {
        echo "<p class='error'>❌ Failed to create: $configFile</p>\n";
    }
}

echo "<h2>🎉 Config Wrapper Creation Complete!</h2>\n";

// Test the admin config specifically
echo "<h3>🧪 Testing Admin Config</h3>\n";
if (file_exists('admin/config.php')) {
    try {
        // Test include without affecting current scope
        $testOutput = shell_exec('php -r "require_once \'admin/config.php\'; echo \'SUCCESS\';"');
        if (strpos($testOutput, 'SUCCESS') !== false) {
            echo "<p class='success'>✅ Admin config test passed</p>\n";
        } else {
            echo "<p class='error'>❌ Admin config test failed</p>\n";
        }
    } catch (Exception $e) {
        echo "<p class='error'>❌ Admin config test error: " . $e->getMessage() . "</p>\n";
    }
}

echo "<h3>🔗 Test Links</h3>\n";
echo "<ul>\n";
echo "<li><a href='admin/tours.php' target='_blank'>Admin Tours Page (Original Issue)</a></li>\n";
echo "<li><a href='test-admin-config.php' target='_blank'>Admin Config Test</a></li>\n";
echo "<li><a href='pages/admin-dashboard.php' target='_blank'>Pages Admin Dashboard</a></li>\n";
echo "</ul>\n";
?>
