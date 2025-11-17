<?php
/**
 * Clone Rwanda Master Theme to All Countries
 * This script copies the Rwanda theme structure and customizes it for each country
 */

require_once 'config/database.php';

// Countries mapping from subdomain handler
$country_mappings = [
    'KEN' => ['slug' => 'visit-ke', 'folder' => 'kenya', 'name' => 'Kenya'],
    'TZA' => ['slug' => 'visit-tz', 'folder' => 'tanzania', 'name' => 'Tanzania'],
    'UGA' => ['slug' => 'visit-ug', 'folder' => 'uganda', 'name' => 'Uganda'],
    'ZAF' => ['slug' => 'visit-za', 'folder' => 'south-africa', 'name' => 'South Africa'],
    'EGY' => ['slug' => 'visit-eg', 'folder' => 'egypt', 'name' => 'Egypt'],
    'MAR' => ['slug' => 'visit-ma', 'folder' => 'morocco', 'name' => 'Morocco'],
    'BWA' => ['slug' => 'visit-bw', 'folder' => 'botswana', 'name' => 'Botswana'],
    'NAM' => ['slug' => 'visit-na', 'folder' => 'namibia', 'name' => 'Namibia'],
    'ZWE' => ['slug' => 'visit-zw', 'folder' => 'zimbabwe', 'name' => 'Zimbabwe'],
    'GHA' => ['slug' => 'visit-gh', 'folder' => 'ghana', 'name' => 'Ghana'],
    'NGA' => ['slug' => 'visit-ng', 'folder' => 'nigeria', 'name' => 'Nigeria'],
    'ETH' => ['slug' => 'visit-et', 'folder' => 'ethiopia', 'name' => 'Ethiopia']
];

// Additional countries from database
$additional_countries = [
    'CMR' => ['slug' => 'visit-cm', 'folder' => 'cameroon', 'name' => 'Cameroon'],
    'COD' => ['slug' => 'visit-cd', 'folder' => 'democratic-republic-of-congo', 'name' => 'Democratic Republic of Congo'],
    'SEN' => ['slug' => 'visit-sn', 'folder' => 'senegal', 'name' => 'Senegal'],
    'TUN' => ['slug' => 'visit-tn', 'folder' => 'tunisia', 'name' => 'Tunisia']
];

$all_countries = array_merge($country_mappings, $additional_countries);

$rwanda_source = 'countries/rwanda/';
$countries_dir = 'countries/';

echo "<h1>Cloning Rwanda Theme to All Countries</h1>\n";
echo "<pre>\n";

foreach ($all_countries as $code => $country_info) {
    $target_dir = $countries_dir . $country_info['folder'] . '/';
    
    echo "Processing {$country_info['name']} ({$code})...\n";
    
    // Create target directory if it doesn't exist
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
        echo "  ✓ Created directory: {$target_dir}\n";
    }
    
    // Copy Rwanda theme structure
    copyRwandaTheme($rwanda_source, $target_dir, $country_info, $code);
    
    echo "  ✓ Completed {$country_info['name']}\n\n";
}

echo "All countries have been updated with Rwanda theme!\n";
echo "</pre>";

function copyRwandaTheme($source, $target, $country_info, $code) {
    // Read Rwanda index.php template
    $rwanda_template = file_get_contents($source . 'index.php');
    
    // Customize for specific country
    $customized_content = customizeForCountry($rwanda_template, $country_info, $code);
    
    // Write to target country
    file_put_contents($target . 'index.php', $customized_content);
    echo "  ✓ Created index.php for {$country_info['name']}\n";
    
    // Copy assets directory if it doesn't exist
    $target_assets = $target . 'assets/';
    if (!is_dir($target_assets)) {
        mkdir($target_assets, 0755, true);
        
        // Copy basic structure from Rwanda
        $source_assets = $source . 'assets/';
        if (is_dir($source_assets)) {
            copyDirectory($source_assets, $target_assets);
            echo "  ✓ Copied assets directory\n";
        }
    }
    
    // Copy includes directory if it doesn't exist
    $target_includes = $target . 'includes/';
    if (!is_dir($target_includes)) {
        mkdir($target_includes, 0755, true);
        
        // Copy basic structure from Rwanda
        $source_includes = $source . 'includes/';
        if (is_dir($source_includes)) {
            copyDirectory($source_includes, $target_includes);
            echo "  ✓ Copied includes directory\n";
        }
    }
    
    // Copy pages directory if it doesn't exist
    $target_pages = $target . 'pages/';
    if (!is_dir($target_pages)) {
        mkdir($target_pages, 0755, true);
        
        // Copy basic structure from Rwanda
        $source_pages = $source . 'pages/';
        if (is_dir($source_pages)) {
            copyDirectory($source_pages, $target_pages);
            echo "  ✓ Copied pages directory\n";
        }
    }
}

function customizeForCountry($template, $country_info, $code) {
    $name = $country_info['name'];
    $slug = $country_info['slug'];
    $folder = $country_info['folder'];
    
    // Replace Rwanda-specific content
    $template = str_replace('Rwanda', $name, $template);
    $template = str_replace('rwanda', strtolower($name), $template);
    $template = str_replace('visit-rw', $slug, $template);
    $template = str_replace('RWA', $code, $template);
    
    // Update page title and meta description
    $template = str_replace(
        'Discover Rwanda | Luxury Group Travel, Primate Safaris, Culture | Forever Young Tours',
        "Discover {$name} | Luxury Group Travel & Safari Adventures | Forever Young Tours",
        $template
    );
    
    $template = str_replace(
        'Premium Rwanda travel. Gorillas, chimps, volcanoes, canopy walks, culture. Curated 6–10 day programs, premium lodges, seamless logistics. Request dates via WhatsApp or email.',
        "Premium {$name} travel experiences. Luxury safaris, cultural immersion, and adventure tours. Curated itineraries with premium lodges and seamless logistics.",
        $template
    );
    
    // Update canonical URL
    $template = str_replace(
        'https://visit-rw.iforeveryoungtours.com/',
        "https://{$slug}.iforeveryoungtours.com/",
        $template
    );
    
    // Update Open Graph images
    $template = str_replace(
        'rwanda-og.jpg',
        strtolower($name) . '-og.jpg',
        $template
    );
    
    // Update hero image
    $template = str_replace(
        'rwanda-gorilla-hero.png',
        strtolower($name) . '-hero.png',
        $template
    );
    
    // Update schema.org structured data
    $template = str_replace(
        '"name": "Rwanda"',
        '"name": "' . $name . '"',
        $template
    );
    
    $template = str_replace(
        'Premium Rwanda travel with gorilla',
        "Premium {$name} travel with wildlife",
        $template
    );
    
    // Update contact information for different countries
    if ($code === 'KEN') {
        $template = str_replace('Norrsken House Kigali', 'Nairobi Operations Center', $template);
        $template = str_replace('Kigali', 'Nairobi', $template);
        $template = str_replace('RW', 'KE', $template);
    } elseif ($code === 'TZA') {
        $template = str_replace('Norrsken House Kigali', 'Arusha Operations Center', $template);
        $template = str_replace('Kigali', 'Arusha', $template);
        $template = str_replace('RW', 'TZ', $template);
    } elseif ($code === 'UGA') {
        $template = str_replace('Norrsken House Kigali', 'Kampala Operations Center', $template);
        $template = str_replace('Kigali', 'Kampala', $template);
        $template = str_replace('RW', 'UG', $template);
    } else {
        // Generic update for other countries
        $template = str_replace('Norrsken House Kigali', $name . ' Operations Center', $template);
        $template = str_replace('Kigali', $name, $template);
    }
    
    // Update WhatsApp links
    $template = str_replace(
        'Rwanda%20Inquiry',
        $name . '%20Inquiry',
        $template
    );
    
    $template = str_replace(
        'Rwanda%20Dates%20Request',
        $name . '%20Dates%20Request',
        $template
    );
    
    $template = str_replace(
        'Rwanda%20Pricing%20Request',
        $name . '%20Pricing%20Request',
        $template
    );
    
    // Update modal titles
    $template = str_replace(
        'Request Rwanda Dates',
        "Request {$name} Dates",
        $template
    );
    
    // Update form values
    $template = str_replace(
        'Rwanda Dates Request',
        "{$name} Dates Request",
        $template
    );
    
    return $template;
}

function copyDirectory($src, $dst) {
    if (!is_dir($src)) return;
    
    if (!is_dir($dst)) {
        mkdir($dst, 0755, true);
    }
    
    $files = scandir($src);
    foreach ($files as $file) {
        if ($file != "." && $file != "..") {
            $srcFile = $src . $file;
            $dstFile = $dst . $file;
            
            if (is_dir($srcFile)) {
                copyDirectory($srcFile . '/', $dstFile . '/');
            } else {
                copy($srcFile, $dstFile);
            }
        }
    }
}
?>
