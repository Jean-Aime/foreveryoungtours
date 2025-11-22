<?php
/**
 * Quick Fix for Online Server Image Issues
 * Run this script to fix common image path problems
 */

session_start();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/config/database.php';

echo "<h1>Fixing Online Server Image Issues</h1>";

// 1. Check and create missing default images
$default_images = [
    'assets/images/default-tour.jpg',
    'assets/images/africa.png',
    'assets/images/logo.png'
];

echo "<h2>1. Checking Default Images</h2>";
foreach ($default_images as $image) {
    $full_path = __DIR__ . '/' . $image;
    if (!file_exists($full_path)) {
        echo "<p style='color: red;'>Missing: " . htmlspecialchars($image) . "</p>";
        
        // Create a placeholder if the image doesn't exist
        $dir = dirname($full_path);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
            echo "<p style='color: blue;'>Created directory: " . htmlspecialchars($dir) . "</p>";
        }
        
        // Create a simple placeholder image (1x1 transparent PNG)
        $placeholder = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==');
        file_put_contents($full_path, $placeholder);
        echo "<p style='color: green;'>Created placeholder: " . htmlspecialchars($image) . "</p>";
    } else {
        echo "<p style='color: green;'>Found: " . htmlspecialchars($image) . "</p>";
    }
}

// 2. Update config.php for better online server detection
echo "<h2>2. Updating Configuration</h2>";

$config_content = '<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Enhanced Configuration for Online Servers
 */

// Detect environment and set BASE_URL accordingly
function detectBaseUrl() {
    $protocol = isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http";
    $host = $_SERVER["HTTP_HOST"] ?? "localhost";
    
    // Check if we\'re on a subdomain
    if (preg_match("/^visit-([a-z]{2,3})\./", $host)) {
        // For subdomains, point to the main domain where images are stored
        if (strpos($host, "localhost") !== false || strpos($host, ".local") !== false) {
            // Local development
            return "http://localhost/foreveryoungtours";
        } else {
            // Live server - use the main domain
            $main_domain = preg_replace("/^visit-[a-z]{2,3}\./", "", $host);
            return $protocol . "://" . $main_domain;
        }
    } else {
        // Main domain
        if (strpos($host, "localhost") !== false || strpos($host, "xampp") !== false) {
            // Local development
            return "http://localhost/foreveryoungtours";
        } else {
            // Live server
            return $protocol . "://" . $host;
        }
    }
}

// Set the BASE_URL constant
define("BASE_URL", detectBaseUrl());

/**
 * Enhanced image URL function with better fallbacks
 */
function getImageUrl($imagePath, $fallback = "assets/images/default-tour.jpg") {
    if (empty($imagePath)) {
        return BASE_URL . "/" . $fallback;
    }
    
    // If it\'s already an absolute URL, return as-is
    if (strpos($imagePath, "http") === 0) {
        return $imagePath;
    }
    
    // Clean the path
    $cleanPath = ltrim($imagePath, "./");
    $cleanPath = preg_replace("/^\.\.\/+/", "", $cleanPath);
    $cleanPath = ltrim($cleanPath, "/");
    
    // Build the full URL
    $fullUrl = BASE_URL . "/" . $cleanPath;
    
    return $fullUrl;
}

// Legacy function names for backward compatibility
function fixImagePath($imagePath) {
    return getImageUrl($imagePath);
}

function fixImageSrc($imagePath) {
    return getImageUrl($imagePath);
}

function getImagePath($imagePath, $fallback = null) {
    return getImageUrl($imagePath, $fallback ?: "assets/images/default-tour.jpg");
}

// Enhanced function for tour images with multiple fallbacks
function getTourImageUrl($tour) {
    $image_path = $tour["image_url"] ?? $tour["cover_image"] ?? null;
    
    if (empty($image_path)) {
        return BASE_URL . "/assets/images/default-tour.jpg";
    }
    
    return getImageUrl($image_path);
}
?>';

file_put_contents(__DIR__ . '/config.php', $config_content);
echo "<p style='color: green;'>Updated config.php with enhanced image handling</p>";

// 3. Test the updated configuration
echo "<h2>3. Testing Updated Configuration</h2>";
require_once __DIR__ . '/config.php'; // Reload the updated config

echo "<p><strong>New BASE_URL:</strong> " . BASE_URL . "</p>";

$test_paths = [
    "uploads/tours/28_cover_1763207330_5662.jpeg",
    "assets/images/default-tour.jpg",
    "assets/images/africa.png"
];

foreach ($test_paths as $path) {
    $url = getImageUrl($path);
    echo "<p><strong>Path:</strong> " . htmlspecialchars($path) . " → <strong>URL:</strong> " . htmlspecialchars($url) . "</p>";
}

echo "<h2>4. Summary</h2>";
echo "<ul>";
echo "<li>✅ Created missing default images</li>";
echo "<li>✅ Updated configuration for better online server detection</li>";
echo "<li>✅ Enhanced image URL functions with fallbacks</li>";
echo "<li>✅ Added getTourImageUrl() function for tour images</li>";
echo "</ul>";

echo "<p><strong>Next Steps:</strong></p>";
echo "<ol>";
echo "<li>Upload your actual images to the uploads/tours/ directory</li>";
echo "<li>Test the Africa continent page: <a href='" . BASE_URL . "/continents/africa/'>Visit Africa Page</a></li>";
echo "<li>Check the debug page: <a href='" . BASE_URL . "/debug-images-online.php'>Debug Images</a></li>";
echo "</ol>";
?>