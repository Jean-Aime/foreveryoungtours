<?php
// Subdomain Handler - Detects country subdomains and filters all content for that country
// Format: visit-{country_code}.iforeveryoungtours.com or visit-{country_code}.localhost:8000

session_start();

$host = $_SERVER['HTTP_HOST'];
$country_code = '';

// Extract subdomain
if (preg_match('/^visit-([a-z]{3})\./', $host, $matches)) {
    $country_code = strtoupper($matches[1]);
}

// If country subdomain detected, set country filter for entire site
if ($country_code) {
    require_once 'config/database.php';
    
    // Get country by code
    $stmt = $pdo->prepare("SELECT * FROM countries WHERE country_code = ? AND status = 'active'");
    $stmt->execute([$country_code]);
    $country = $stmt->fetch();
    
    if ($country) {
        // Set country context in session and constants
        $_SESSION['subdomain_country_id'] = $country['id'];
        $_SESSION['subdomain_country_code'] = $country_code;
        $_SESSION['subdomain_country_name'] = $country['name'];
        $_SESSION['subdomain_country_slug'] = $country['slug'];
        
        define('COUNTRY_SUBDOMAIN', true);
        define('CURRENT_COUNTRY_ID', $country['id']);
        define('CURRENT_COUNTRY_CODE', $country_code);
        define('CURRENT_COUNTRY_NAME', $country['name']);
        define('CURRENT_COUNTRY_SLUG', $country['slug']);
        
        // Load homepage with country filter
        require_once 'index.php';
        exit;
    }
}

// Not a country subdomain, continue normal flow
define('COUNTRY_SUBDOMAIN', false);
?>
