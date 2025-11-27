<?php
// Apply slug-based URLs to subdomain packages.php and tour-detail.php

$base_dir = dirname(__DIR__);
$countries_dir = $base_dir . '/countries';
$continents_dir = $base_dir . '/continents';

$all_files = [];

// Collect packages.php files
if (is_dir($countries_dir)) {
    $folders = array_diff(scandir($countries_dir), ['.', '..']);
    foreach ($folders as $folder) {
        $packages = $countries_dir . '/' . $folder . '/pages/packages.php';
        $tour_detail = $countries_dir . '/' . $folder . '/pages/tour-detail.php';
        if (file_exists($packages)) $all_files[] = $packages;
        if (file_exists($tour_detail)) $all_files[] = $tour_detail;
    }
}

if (is_dir($continents_dir)) {
    $folders = array_diff(scandir($continents_dir), ['.', '..']);
    foreach ($folders as $folder) {
        $packages = $continents_dir . '/' . $folder . '/pages/packages.php';
        $tour_detail = $continents_dir . '/' . $folder . '/pages/tour-detail.php';
        if (file_exists($packages)) $all_files[] = $packages;
        if (file_exists($tour_detail)) $all_files[] = $tour_detail;
    }
}

echo "Found " . count($all_files) . " files\n\n";

foreach ($all_files as $file_path) {
    echo "Processing: $file_path\n";
    
    $content = file_get_contents($file_path);
    $original = $content;
    
    // Replace tour-detail.php?id= with /tour/slug
    $content = preg_replace(
        '/<a href="tour-detail\.php\?id=<\?php echo \$tour\[\'id\'\]; \?>"/s',
        '<a href="../../../tour/<?php echo $tour[\'slug\']; ?>"',
        $content
    );
    
    $content = preg_replace(
        '/<a href="tour-detail\.php\?id=<\?php echo \$related\[\'id\'\]; \?>"/s',
        '<a href="../../../tour/<?php echo $related[\'slug\']; ?>"',
        $content
    );
    
    $content = preg_replace(
        '/<a href="\.\.\/\.\.\/\.\.\/pages\/tour-detail\.php\?id=<\?php echo \$tour\[\'id\'\]; \?>"/s',
        '<a href="../../../tour/<?php echo $tour[\'slug\']; ?>"',
        $content
    );
    
    if ($content !== $original) {
        file_put_contents($file_path, $content);
        echo "  ✓ Updated\n";
    } else {
        echo "  - No changes\n";
    }
}

echo "\n✓ Done!\n";
