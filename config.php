<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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
            // Live server - extract main domain from subdomain
            $main_domain = preg_replace('/^visit-[a-z]{2,3}\./', '', $host);
            return $protocol . '://' . $main_domain;
        }
    } else {
        // Main domain
        if (strpos($host, 'localhost') !== false || strpos($host, 'xampp') !== false) {
            // Local development
            return 'http://localhost/foreveryoungtours';
        } else {
            // Live server
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
    
    // Handle different path formats that might be in the database
    $cleanPath = $imagePath;
    
    // If it's just a filename, assume it's in uploads/tours/
    if (!strpos($cleanPath, '/') && !strpos($cleanPath, '\\')) {
        $cleanPath = 'uploads/tours/' . $cleanPath;
    }
    
    // If it starts with uploads/ but not with BASE_URL, it's correct
    if (strpos($cleanPath, 'uploads/') === 0) {
        return getAbsoluteUrl($cleanPath);
    }
    
    // If it contains uploads/tours/ anywhere, extract that part
    if (strpos($cleanPath, 'uploads/tours/') !== false) {
        $pos = strpos($cleanPath, 'uploads/tours/');
        $cleanPath = substr($cleanPath, $pos);
        return getAbsoluteUrl($cleanPath);
    }
    
    // If it looks like a tour image filename pattern (ID_type_timestamp_random.ext)
    if (preg_match('/^\d+_(cover|main|gallery)_\d+_\d+\.(jpg|jpeg|png|gif)$/i', $cleanPath)) {
        return getAbsoluteUrl('uploads/tours/' . $cleanPath);
    }
    
    // Default: try as-is first, then fallback
    return getAbsoluteUrl($cleanPath);
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
