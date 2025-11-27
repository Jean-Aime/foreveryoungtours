<?php
// Apply price hiding to all subdomain packages.php files

$base_dir = dirname(__DIR__);

// Get all country directories
$countries_dir = $base_dir . '/countries';
$continents_dir = $base_dir . '/continents';

$all_dirs = [];

// Collect all country subdomain directories
if (is_dir($countries_dir)) {
    $country_folders = array_diff(scandir($countries_dir), ['.', '..']);
    foreach ($country_folders as $folder) {
        $packages_path = $countries_dir . '/' . $folder . '/pages/packages.php';
        if (file_exists($packages_path)) {
            $all_dirs[] = $packages_path;
        }
    }
}

// Collect all continent subdomain directories
if (is_dir($continents_dir)) {
    $continent_folders = array_diff(scandir($continents_dir), ['.', '..']);
    foreach ($continent_folders as $folder) {
        $packages_path = $continents_dir . '/' . $folder . '/pages/packages.php';
        if (file_exists($packages_path)) {
            $all_dirs[] = $packages_path;
        }
    }
}

echo "Found " . count($all_dirs) . " packages.php files to update\n\n";

foreach ($all_dirs as $file_path) {
    echo "Processing: $file_path\n";
    
    $content = file_get_contents($file_path);
    $original_content = $content;
    
    // Remove price badge from tour cards
    $content = preg_replace(
        '/(<img[^>]*>)\s*<div class="absolute top-4 right-4 bg-golden-500 text-black px-3 py-1 rounded-full text-sm font-semibold">\s*From \$<\?php echo number_format\(\$tour\[\'price\'\]\); \?>\s*<\/div>\s*(<\?php if \(\$tour\[\'featured\'\]\): \?>)/s',
        '$1$2',
        $content
    );
    
    if ($content !== $original_content) {
        file_put_contents($file_path, $content);
        echo "  ✓ Updated successfully\n";
    } else {
        echo "  - No changes needed\n";
    }
}

echo "\n✓ All packages.php files processed!\n";
