<?php
// Add modal.css to all subdomain tour-detail.php files

$base_dir = dirname(__DIR__);
$countries_dir = $base_dir . '/countries';
$continents_dir = $base_dir . '/continents';

$all_dirs = [];

if (is_dir($countries_dir)) {
    $country_folders = array_diff(scandir($countries_dir), ['.', '..']);
    foreach ($country_folders as $folder) {
        $tour_detail_path = $countries_dir . '/' . $folder . '/pages/tour-detail.php';
        if (file_exists($tour_detail_path)) {
            $all_dirs[] = $tour_detail_path;
        }
    }
}

if (is_dir($continents_dir)) {
    $continent_folders = array_diff(scandir($continents_dir), ['.', '..']);
    foreach ($continent_folders as $folder) {
        $tour_detail_path = $continents_dir . '/' . $folder . '/pages/tour-detail.php';
        if (file_exists($tour_detail_path)) {
            $all_dirs[] = $tour_detail_path;
        }
    }
}

echo "Found " . count($all_dirs) . " files to update\n\n";

foreach ($all_dirs as $file_path) {
    echo "Processing: $file_path\n";
    
    $content = file_get_contents($file_path);
    $original_content = $content;
    
    // Add modal.css if not already present
    if (strpos($content, 'modal.css') === false) {
        $content = preg_replace(
            '/(<link rel="stylesheet" href="<\?php echo \$css_path; \?>">\s*<style>)/s',
            '<link rel="stylesheet" href="../../../assets/css/modal.css">$1',
            $content
        );
    }
    
    if ($content !== $original_content) {
        file_put_contents($file_path, $content);
        echo "  ✓ Added modal.css\n";
    } else {
        echo "  - Already has modal.css\n";
    }
}

echo "\n✓ Done!\n";
