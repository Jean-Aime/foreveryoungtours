<?php
// Fix subdomain index.php files: hide prices and use slug URLs

$base_dir = dirname(__DIR__);
$countries_dir = $base_dir . '/countries';
$continents_dir = $base_dir . '/continents';

$all_files = [];

if (is_dir($countries_dir)) {
    $folders = array_diff(scandir($countries_dir), ['.', '..']);
    foreach ($folders as $folder) {
        $index_path = $countries_dir . '/' . $folder . '/index.php';
        if (file_exists($index_path)) {
            $all_files[] = $index_path;
        }
    }
}

if (is_dir($continents_dir)) {
    $folders = array_diff(scandir($continents_dir), ['.', '..']);
    foreach ($folders as $folder) {
        $index_path = $continents_dir . '/' . $folder . '/index.php';
        if (file_exists($index_path)) {
            $all_files[] = $index_path;
        }
    }
}

echo "Found " . count($all_files) . " index.php files\n\n";

foreach ($all_files as $file_path) {
    echo "Processing: $file_path\n";
    
    $content = file_get_contents($file_path);
    $original = $content;
    
    // Remove price display
    $content = preg_replace(
        '/<span class="text-2xl font-bold text-yellow-600">\$<\?= number_format\(\$tour\[\'price\'\], 0\) \?><\/span>/s',
        '',
        $content
    );
    
    // Change URL to use slug
    $content = preg_replace(
        '/<a href="<\?= BASE_URL \?>\/pages\/tour-detail\.php\?id=<\?= \$tour\[\'id\'\] \?>"/s',
        '<a href="<?= BASE_URL ?>/tour/<?= $tour[\'slug\'] ?>"',
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
