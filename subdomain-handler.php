<?php
// Subdomain Handler - Detects country subdomains and filters all content for that country
// Format: visit-{country_code}.iforeveryoungtours.com or visit-{country_code}.localhost:8000

session_start();

$host = $_SERVER['HTTP_HOST'];
$country_code = '';

// Extract subdomain (2 or 3 letter codes)
if (preg_match('/^visit-([a-z]{2,3})\./', $host, $matches)) {
    $extracted_code = strtoupper($matches[1]);
    
    // Map 2-letter codes to 3-letter codes for database lookup
    $code_mapping = [
        'RW' => 'RWA',  // Rwanda
        'KE' => 'KEN',  // Kenya
        'TZ' => 'TZA',  // Tanzania
        'UG' => 'UGA',  // Uganda
        'ZA' => 'ZAF',  // South Africa
        'EG' => 'EGY',  // Egypt
        'MA' => 'MAR',  // Morocco
        'BW' => 'BWA',  // Botswana
        'NA' => 'NAM',  // Namibia
        'ZW' => 'ZWE',  // Zimbabwe
        'GH' => 'GHA',  // Ghana
        'NG' => 'NGA',  // Nigeria
        'ET' => 'ETH'   // Ethiopia
    ];
    
    // Use mapping if it's a 2-letter code, otherwise use as-is
    $country_code = $code_mapping[$extracted_code] ?? $extracted_code;
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
        
        // Load country-specific page
        // Map database slug to actual folder name
        $folder_mapping = [
            'visit-rw' => 'rwanda',
            'visit-ke' => 'kenya', 
            'visit-tz' => 'tanzania',
            'visit-ug' => 'uganda',
            'visit-za' => 'south-africa',
            'visit-eg' => 'egypt',
            'visit-ma' => 'morocco',
            'visit-bw' => 'botswana',
            'visit-na' => 'namibia',
            'visit-zw' => 'zimbabwe',
            'visit-gh' => 'ghana',
            'visit-ng' => 'nigeria',
            'visit-et' => 'ethiopia'
        ];
        
        $folder_name = $folder_mapping[$country['slug']] ?? $country['slug'];
        $country_page = "countries/{$folder_name}/index.php";
        
        if (file_exists($country_page)) {
            require_once $country_page;
        } else {
            // Fallback to main homepage with country filter if country page doesn't exist
            require_once 'index.php';
        }
        exit;
    }
}

// Not a country subdomain, continue normal flow
define('COUNTRY_SUBDOMAIN', false);
?>
