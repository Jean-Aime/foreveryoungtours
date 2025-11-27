<?php
/**
 * Auto-clone country subdomain structure
 * Call this when a new country is added to the database
 */

function autoCloneCountrySubdomain($country_slug, $country_name, $country_code) {
    $base_dir = __DIR__;
    $template_dir = $base_dir . '/visit-rw'; // Use Rwanda as template
    
    // Extract 2-letter code from country_code (e.g., KEN -> ke, RWA -> rw)
    $short_code = strtolower(substr($country_code, 0, 2));
    $new_dir = $base_dir . '/visit-' . $short_code;
    
    // Check if template exists
    if (!is_dir($template_dir)) {
        return ['success' => false, 'message' => 'Template directory not found'];
    }
    
    // Check if country subdomain already exists
    if (is_dir($new_dir)) {
        return ['success' => false, 'message' => 'Country subdomain already exists'];
    }
    
    // Copy entire directory structure
    if (!recursiveCopy($template_dir, $new_dir)) {
        return ['success' => false, 'message' => 'Failed to copy directory structure'];
    }
    
    // Update config.php with new country code
    $config_file = $new_dir . '/config.php';
    if (file_exists($config_file)) {
        $config_content = file_get_contents($config_file);
        $config_content = str_replace("'rw'", "'" . strtolower($country_code) . "'", $config_content);
        $config_content = str_replace("'RW'", "'" . strtoupper($country_code) . "'", $config_content);
        file_put_contents($config_file, $config_content);
    }
    
    // Update pages/config.php
    $pages_config = $new_dir . '/pages/config.php';
    if (file_exists($pages_config)) {
        $config_content = file_get_contents($pages_config);
        $config_content = str_replace("'rw'", "'" . strtolower($country_code) . "'", $config_content);
        $config_content = str_replace("'RW'", "'" . strtoupper($country_code) . "'", $config_content);
        file_put_contents($pages_config, $config_content);
    }
    
    return ['success' => true, 'message' => 'Country subdomain created successfully', 'path' => $new_dir];
}

function recursiveCopy($src, $dst) {
    $dir = opendir($src);
    @mkdir($dst);
    
    while (($file = readdir($dir)) !== false) {
        if ($file != '.' && $file != '..') {
            if (is_dir($src . '/' . $file)) {
                recursiveCopy($src . '/' . $file, $dst . '/' . $file);
            } else {
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    }
    
    closedir($dir);
    return true;
}

// Example usage (call this after adding country to database):
// $result = autoCloneCountrySubdomain('kenya', 'Kenya', 'ke');
// if ($result['success']) {
//     echo "Success: " . $result['message'];
// } else {
//     echo "Error: " . $result['message'];
// }
?>
