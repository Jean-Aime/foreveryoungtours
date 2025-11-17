<?php
/**
 * Main Configuration File for Forever Young Tours
 * 
 * This file contains the base URL configuration that ensures
 * all image paths work correctly across:
 * - Main domain (localhost/foreveryoungtours)
 * - Country subdomains (visit-rw.foreveryoungtours.local)
 * - Live server deployment
 */

// Detect environment and set BASE_URL accordingly
function detectBaseUrl() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    
    // Check if we're on a subdomain
    if (preg_match('/^visit-([a-z]{2,3})\./', $host)) {
        // For subdomains, point to the main domain where images are stored
        if (strpos($host, 'localhost') !== false || strpos($host, '.local') !== false) {
            // Local development
            return 'http://localhost/foreveryoungtours';
        } else {
            // Live server - adjust this to your live domain
            return 'https://foreveryoungtours.com';
        }
    } else {
        // Main domain
        if (strpos($host, 'localhost') !== false) {
            // Local development
            return 'http://localhost/foreveryoungtours';
        } else {
            // Live server - adjust this to your live domain
            return $protocol . '://' . $host;
        }
    }
}

// Set the BASE_URL constant
define('BASE_URL', detectBaseUrl());

/**
 * Get absolute URL for any path
 * 
 * @param string $path - Path relative to web root (e.g., 'uploads/tours/image.jpg')
 * @return string - Absolute URL
 */
function getAbsoluteUrl($path) {
    if (empty($path)) {
        return BASE_URL . '/assets/images/default-tour.jpg';
    }
    
    // If it's already an absolute URL, return as-is
    if (strpos($path, 'http') === 0) {
        return $path;
    }
    
    // Remove any leading slashes or relative path prefixes
    $cleanPath = ltrim($path, './');
    $cleanPath = preg_replace('/^\.\.\/+/', '', $cleanPath);
    $cleanPath = ltrim($cleanPath, '/');
    
    return BASE_URL . '/' . $cleanPath;
}

/**
 * Get image URL with fallback
 * 
 * @param string $imagePath - Image path from database
 * @param string $fallback - Fallback image path
 * @return string - Absolute image URL
 */
function getImageUrl($imagePath, $fallback = 'assets/images/default-tour.jpg') {
    if (empty($imagePath)) {
        return getAbsoluteUrl($fallback);
    }
    
    return getAbsoluteUrl($imagePath);
}

// Legacy function names for backward compatibility
function fixImagePath($imagePath) {
    return getImageUrl($imagePath);
}

function fixImageSrc($imagePath) {
    return getImageUrl($imagePath);
}

function getImagePath($imagePath, $fallback = null) {
    return getImageUrl($imagePath, $fallback ?: 'assets/images/default-tour.jpg');
}

// Debug information (remove in production)
if (isset($_GET['debug_config'])) {
    echo "<h2>Configuration Debug</h2>";
    echo "<p><strong>BASE_URL:</strong> " . BASE_URL . "</p>";
    echo "<p><strong>HTTP_HOST:</strong> " . ($_SERVER['HTTP_HOST'] ?? 'NOT SET') . "</p>";
    echo "<p><strong>REQUEST_URI:</strong> " . ($_SERVER['REQUEST_URI'] ?? 'NOT SET') . "</p>";
    echo "<h3>Test URLs:</h3>";
    $test_paths = [
        'uploads/tours/28_cover_1763207330_5662.jpeg',
        'assets/images/default-tour.jpg',
        '../../../uploads/tours/image.jpg',
        'http://external.com/image.jpg'
    ];
    foreach ($test_paths as $path) {
        echo "<p><strong>Input:</strong> " . htmlspecialchars($path) . "<br>";
        echo "<strong>Output:</strong> " . htmlspecialchars(getImageUrl($path)) . "</p>";
    }
}
?>
