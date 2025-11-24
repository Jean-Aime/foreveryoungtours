<?php
/**
 * CSRF Token Protection
 * Prevents Cross-Site Request Forgery attacks
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Generate CSRF token
 */
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Validate CSRF token
 */
function validateCSRFToken($token) {
    if (!isset($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Get CSRF token input field
 */
function csrfField() {
    return '<input type="hidden" name="csrf_token" value="' . generateCSRFToken() . '">';
}

/**
 * Verify CSRF token from POST request
 */
function verifyCSRF() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_POST['csrf_token']) || !validateCSRFToken($_POST['csrf_token'])) {
            http_response_code(403);
            die('CSRF token validation failed. Please refresh the page and try again.');
        }
    }
}
?>
