<?php
// Auto-clone continent/country subdomain folders when admin adds new entries

function cloneContinentFolder($slug) {
    $source = __DIR__ . '/../continents/africa';
    $destination = __DIR__ . '/../continents/' . $slug;
    
    if (file_exists($destination)) {
        return true; // Already exists
    }
    
    // Create directory
    if (!mkdir($destination, 0755, true)) {
        return false;
    }
    
    // Copy index.php
    copy($source . '/index.php', $destination . '/index.php');
    
    // Copy .htaccess
    if (file_exists($source . '/.htaccess')) {
        copy($source . '/.htaccess', $destination . '/.htaccess');
    }
    
    return true;
}

function cloneCountryFolder($slug) {
    $source = __DIR__ . '/../countries/rwanda';
    $destination = __DIR__ . '/../countries/' . $slug;
    
    if (file_exists($destination)) {
        return true; // Already exists
    }
    
    // Create directory
    if (!mkdir($destination, 0755, true)) {
        return false;
    }
    
    // Copy index.php
    copy($source . '/index.php', $destination . '/index.php');
    
    // Create config.php
    $config_content = "<?php\nrequire_once __DIR__ . '/../../config.php';\n";
    file_put_contents($destination . '/config.php', $config_content);
    
    return true;
}
?>
