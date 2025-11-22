<?php
/**
 * Update All Country Hero Images
 * 
 * This script updates all country index.php files to use their local hero images
 * instead of the generic africa.png fallback.
 */

// Include the main config
require_once 'config.php';

echo "<h1>🌍 Updating All Country Hero Images</h1>\n";
echo "<style>body{font-family:Arial,sans-serif;margin:20px;} .success{color:#4caf50;} .error{color:#f44336;} .info{color:#2196f3;} .warning{color:#ff9800;}</style>\n";

// Get all country directories
$countriesDir = 'countries';
$countries = [];

if (is_dir($countriesDir)) {
    $items = scandir($countriesDir);
    foreach ($items as $item) {
        if ($item !== '.' && $item !== '..' && is_dir($countriesDir . '/' . $item)) {
            $countries[] = $item;
        }
    }
}

echo "<p class='info'>📁 Found " . count($countries) . " country directories</p>\n";

foreach ($countries as $country) {
    echo "<h2>🏳️ Processing: " . ucfirst(str_replace('-', ' ', $country)) . "</h2>\n";
    
    $countryDir = $countriesDir . '/' . $country;
    $indexFile = $countryDir . '/index.php';
    $assetsDir = $countryDir . '/assets/images';
    
    // Check if index.php exists
    if (!file_exists($indexFile)) {
        echo "<p class='warning'>⚠️ No index.php found for $country</p>\n";
        continue;
    }
    
    // Check if assets/images directory exists
    if (!is_dir($assetsDir)) {
        echo "<p class='warning'>⚠️ No assets/images directory found for $country</p>\n";
        continue;
    }
    
    // Look for hero images in the assets directory
    $heroImages = [];
    $imageFiles = glob($assetsDir . '/*');
    
    foreach ($imageFiles as $imageFile) {
        $filename = basename($imageFile);
        if (preg_match('/hero.*\.(jpg|jpeg|png|webp)$/i', $filename)) {
            $heroImages[] = $filename;
        }
    }
    
    if (empty($heroImages)) {
        echo "<p class='warning'>⚠️ No hero images found in $assetsDir</p>\n";
        continue;
    }
    
    echo "<p class='info'>🖼️ Found hero images: " . implode(', ', $heroImages) . "</p>\n";
    
    // Read the current index.php content
    $content = file_get_contents($indexFile);
    if ($content === false) {
        echo "<p class='error'>❌ Failed to read $indexFile</p>\n";
        continue;
    }
    
    // Primary hero image (prefer hero-[country].jpg, then first hero image found)
    $primaryHero = null;
    $secondaryHero = null;
    
    // Look for country-specific hero image first
    foreach ($heroImages as $heroImage) {
        if (strpos($heroImage, $country) !== false || strpos($heroImage, 'hero-') === 0) {
            $primaryHero = $heroImage;
            break;
        }
    }
    
    // If no country-specific hero found, use the first hero image
    if (!$primaryHero) {
        $primaryHero = $heroImages[0];
    }
    
    // Use second hero image as fallback if available
    if (count($heroImages) > 1) {
        foreach ($heroImages as $heroImage) {
            if ($heroImage !== $primaryHero) {
                $secondaryHero = $heroImage;
                break;
            }
        }
    }
    
    echo "<p class='info'>🎯 Primary hero: $primaryHero</p>\n";
    if ($secondaryHero) {
        echo "<p class='info'>🔄 Secondary hero: $secondaryHero</p>\n";
    }
    
    // Update the hero section image
    $heroImagePath = "countries/$country/assets/images/$primaryHero";
    $fallbackPath = $secondaryHero ? "countries/$country/assets/images/$secondaryHero" : "assets/images/africa.png";
    
    // Pattern to match the hero section image
    $pattern = '/(<img\s+src="[^"]*getImageUrl\([^)]*\)"\s+alt="[^"]*"\s+class="[^"]*"[^>]*>)/';
    
    if (preg_match($pattern, $content)) {
        // Build the new image tag
        $newImageTag = '<img src="<?= getImageUrl(\'' . $heroImagePath . '\') ?>" alt="' . ucfirst($country) . ' Hero" class="w-full h-full object-cover scale-110" onerror="this.src=\'<?= getImageUrl(\'' . $fallbackPath . '\') ?>\'; this.onerror=function(){this.src=\'<?= getImageUrl(\'assets/images/africa.png\') ?>\';};">';
        
        // Replace the first image in hero section
        $updatedContent = preg_replace($pattern, $newImageTag, $content, 1);
        
        if ($updatedContent !== $content) {
            // Write the updated content back to the file
            if (file_put_contents($indexFile, $updatedContent)) {
                echo "<p class='success'>✅ Successfully updated hero image for $country</p>\n";
            } else {
                echo "<p class='error'>❌ Failed to write updated content to $indexFile</p>\n";
            }
        } else {
            echo "<p class='warning'>⚠️ No changes made to $country (pattern didn't match)</p>\n";
        }
    } else {
        echo "<p class='warning'>⚠️ Hero image pattern not found in $indexFile</p>\n";
    }
    
    echo "<hr>\n";
}

echo "<h2>🎉 Country Hero Image Update Complete!</h2>\n";
echo "<p class='info'>📝 All country pages have been processed. Check the results above for any issues.</p>\n";

// Create test links
echo "<h3>🔗 Test Links</h3>\n";
echo "<ul>\n";
foreach ($countries as $country) {
    $countryName = ucfirst(str_replace('-', ' ', $country));
    echo "<li><a href='countries/$country/' target='_blank'>$countryName</a></li>\n";
}
echo "</ul>\n";
?>
