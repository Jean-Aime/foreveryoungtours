<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Main Configuration File for Forever Young Tours
 * 
 * INSTRUCTIONS:
 * 1. Copy this file to config.php
 * 2. Update the detectBaseUrl() function for your environment
 * 3. Never commit config.php to version control
 */

// Detect environment and set BASE_URL accordingly
function detectBaseUrl() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    
    // Check if we're on a subdomain
    if (preg_match('/^(visit-[a-z]{2,3}|[a-z-]+)\\./', $host)) {
        // For subdomains, point to the main domain where images are stored
        if (strpos($host, 'localhost') !== false || strpos($host, '.local') !== false) {
            // Local development - UPDATE THIS PATH FOR YOUR SETUP
            return 'http://localhost/ForeverYoungTours';
        } else {
            // Live server - extract main domain from subdomain
            $main_domain = preg_replace('/^[a-z-]+\\./', '', $host);
            return $protocol . '://' . $main_domain;
        }
    } else {
        // Main domain
        if (strpos($host, 'localhost') !== false || strpos($host, 'xampp') !== false) {
            // Local development - UPDATE THIS PATH FOR YOUR SETUP
            return 'http://localhost/ForeverYoungTours';
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
 */
function getAbsoluteUrl($path) {
    if (empty($path)) {
        return BASE_URL . '/assets/images/default-tour.jpg';
    }
    
    if (strpos($path, 'http') === 0) {
        return $path;
    }
    
    $cleanPath = ltrim($path, './');
    $cleanPath = preg_replace('/^\.\.\/+/', '', $cleanPath);
    $cleanPath = ltrim($cleanPath, '/');
    
    return BASE_URL . '/' . $cleanPath;
}

/**
 * Get image URL with fallback
 */
function getImageUrl($imagePath, $fallback = 'assets/images/default-tour.jpg') {
    if (empty($imagePath)) {
        return getAbsoluteUrl($fallback);
    }
    
    $cleanPath = $imagePath;
    
    if (!strpos($cleanPath, '/') && !strpos($cleanPath, '\\')) {
        $cleanPath = 'uploads/tours/' . $cleanPath;
    }
    
    if (strpos($cleanPath, 'uploads/') === 0) {
        return getAbsoluteUrl($cleanPath);
    }
    
    if (strpos($cleanPath, 'uploads/tours/') !== false) {
        $pos = strpos($cleanPath, 'uploads/tours/');
        $cleanPath = substr($cleanPath, $pos);
        return getAbsoluteUrl($cleanPath);
    }
    
    if (preg_match('/^\d+_(cover|main|gallery)_\d+_\d+\.(jpg|jpeg|png|gif)$/i', $cleanPath)) {
        return getAbsoluteUrl('uploads/tours/' . $cleanPath);
    }
    
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
?>
