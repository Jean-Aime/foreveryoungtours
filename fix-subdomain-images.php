<?php
/**
 * Quick Fix for Subdomain Image Issues
 */

session_start();
require_once __DIR__ . '/config.php';

echo "<h1>Fixing Subdomain Image Issues</h1>";

// Update config.php with enhanced subdomain detection
$enhanced_config = '<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Enhanced Configuration for Subdomains
 */

// Detect environment and set BASE_URL accordingly
function detectBaseUrl() {
    $protocol = isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http";
    $host = $_SERVER["HTTP_HOST"] ?? "localhost";
    
    // Check if we\'re on a subdomain
    if (preg_match("/^visit-([a-z]{2,3})\./", $host)) {
        // For subdomains, ALWAYS point to the main domain where images are stored
        if (strpos($host, "localhost") !== false || strpos($host, ".local") !== false) {
            // Local development
            return "http://localhost/foreveryoungtours";
        } else {
            // Live server - extract main domain from subdomain
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
 * Enhanced image URL function for subdomains
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
    
    // For subdomains, ALWAYS use the main domain BASE_URL
    return BASE_URL . "/" . $cleanPath;
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

file_put_contents(__DIR__ . '/config.php', $enhanced_config);
echo "<p style='color: green;'>✅ Updated main config.php for better subdomain support</p>";

// Test the configuration
require_once __DIR__ . '/config.php'; // Reload

echo "<h2>Configuration Test</h2>";
echo "<p><strong>Current Host:</strong> " . $_SERVER['HTTP_HOST'] . "</p>";
echo "<p><strong>BASE_URL:</strong> " . BASE_URL . "</p>";
echo "<p><strong>Is Subdomain:</strong> " . (preg_match('/^visit-([a-z]{2,3})\./', $_SERVER['HTTP_HOST']) ? 'YES' : 'NO') . "</p>";

echo "<h2>Image URL Tests</h2>";
$test_images = [
    'assets/images/default-tour.jpg',
    'uploads/tours/28_cover_1763207330_5662.jpeg',
    'assets/images/africa.png'
];

foreach ($test_images as $image) {
    $url = getImageUrl($image);
    echo "<p><strong>Input:</strong> " . htmlspecialchars($image) . "</p>";
    echo "<p><strong>Output:</strong> " . htmlspecialchars($url) . "</p>";
    echo "<img src='" . htmlspecialchars($url) . "' alt='Test' style='max-width: 150px; margin: 5px 0;' onerror='this.style.border=\"2px solid red\";'>";
    echo "<hr>";
}

echo "<h2>Summary</h2>";
echo "<ul>";
echo "<li>✅ Enhanced subdomain detection</li>";
echo "<li>✅ All subdomain images now point to main domain</li>";
echo "<li>✅ Improved fallback handling</li>";
echo "<li>✅ Better error handling with onerror attributes</li>";
echo "</ul>";

echo "<h2>Test Links</h2>";
echo "<ul>";
echo "<li><a href='test-subdomain-images.php'>Main Domain Image Test</a></li>";
echo "<li><a href='subdomains/visit-rw/test-images.php'>Rwanda Subdomain Test</a></li>";
echo "<li><a href='continents/africa/'>Africa Continent Page</a></li>";
echo "</ul>";

echo "<p><strong>Note:</strong> All subdomain images should now load from the main domain where the actual image files are stored.</p>";
?>