<?php
// Subdomain Handler - Detects country subdomains and loads the appropriate country page
// Format: visit-{country_code}.foreveryoungtours.local

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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

// If country subdomain detected, load the appropriate country page
if ($country_code) {
    // Map country codes to folder names (use lowercase country codes without 'visit-' prefix)
    $country_mapping = [
        'RWA' => 'rwanda',
        'KEN' => 'kenya',
        'TZA' => 'tanzania',
        'UGA' => 'uganda',
        'ZAF' => 'south-africa',
        'EGY' => 'egypt',
        'MAR' => 'morocco',
        'BWA' => 'botswana',
        'NAM' => 'namibia',
        'ZWE' => 'zimbabwe',
        'GHA' => 'ghana',
        'NGA' => 'nigeria',
        'ETH' => 'ethiopia'
    ];
    
    // Get the folder name from the mapping or use the country code in lowercase
    $country_folder = $country_mapping[$country_code] ?? strtolower($country_code);
    
    // Set the path to the country's index.php
    $country_index = __DIR__ . "/countries/{$country_folder}/index.php";
    
    // Debug information
    error_log("Attempting to load country page: " . $country_index);
    
    if (file_exists($country_index)) {
        // Set the base URL for the country
        define('BASE_URL', "/countries/{$country_folder}/");
        
        // Set country context in session
        $_SESSION['subdomain_country_code'] = $country_code;
        $_SESSION['subdomain_country_name'] = ucfirst($country_folder);
        $_SESSION['subdomain_country_slug'] = $country_folder;
        
        // Set a constant with the application root path
        if (!defined('APP_ROOT')) {
            // Use the absolute path to the project directory
            define('APP_ROOT', 'C:/xampp/htdocs/foreveryoungtours');
        }
        
        // Debug: Log the paths for verification
        error_log("APP_ROOT: " . APP_ROOT);
        error_log("Country index: " . $country_index);
        
        // Include the database configuration first
        $db_config_path = str_replace('\\', '/', APP_ROOT . '/config/database.php');
        error_log("Looking for database config at: " . $db_config_path);
        
        if (file_exists($db_config_path)) {
            require_once $db_config_path;
        } else {
            // Try an alternative path
            $alt_path = str_replace('\\', '/', dirname(dirname(dirname(__DIR__))) . '/config/database.php');
            error_log("Trying alternative path: " . $alt_path);
            
            if (file_exists($alt_path)) {
                require_once $alt_path;
            } else {
                die("Database configuration not found. Tried:\n" . 
                    "1. " . $db_config_path . "\n" .
                    "2. " . $alt_path);
            }
        }
        
        // Set the include path to include the main application directory and the country's directory
        set_include_path(implode(PATH_SEPARATOR, [
            APP_ROOT,                   // Main application root
            dirname($country_index),    // Country directory
            get_include_path()          // Existing include paths
        ]));
        
        // Include the country's index.php
        require_once $country_index;
        exit;
    } else {
        // Log the error
        error_log("Country page not found: " . $country_index);
        
        // If the country page doesn't exist, show a 404
        header("HTTP/1.0 404 Not Found");
        echo "Country page not found. Please check the URL and try again.";
        exit;
    }
}

// Not a country subdomain, continue normal flow
define('COUNTRY_SUBDOMAIN', false);

// If we get here, the subdomain wasn't handled, so redirect to the main site
header("Location: /");
exit;
?>
