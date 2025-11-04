<?php
// Subdomain Configuration

// Main domain
define('MAIN_DOMAIN', 'iforeveryoungtours.com');

// Generate country subdomain URL
function getCountrySubdomainUrl($country_code) {
    $code = strtolower($country_code);
    return "http://visit-{$code}." . MAIN_DOMAIN;
}

// Check if current request is from country subdomain
function isCountrySubdomain() {
    $host = $_SERVER['HTTP_HOST'];
    return preg_match('/^visit-[a-z]{3}\./', $host);
}

// Get current country code from subdomain
function getCurrentCountryCode() {
    $host = $_SERVER['HTTP_HOST'];
    if (preg_match('/^visit-([a-z]{3})\./', $host, $matches)) {
        return strtoupper($matches[1]);
    }
    return null;
}
?>
