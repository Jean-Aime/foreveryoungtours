<?php
// Fix sidebar booking button in all subdomain tour-detail.php files

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

echo "Found " . count($all_dirs) . " files to fix\n\n";

foreach ($all_dirs as $file_path) {
    echo "Processing: $file_path\n";
    
    $content = file_get_contents($file_path);
    $original_content = $content;
    
    // Fix the sidebar button that still has old onclick
    $content = preg_replace(
        '/<button onclick="if\(typeof openBookingModal === \'function\'\) \{ openBookingModal\(<\?php echo \$tour\[\'id\'\]; \?>, \'<\?php echo addslashes\(\$tour\[\'name\'\]\); \?>\', <\?php echo \$tour\[\'price\'\]; \?>, \'\'\); \} else \{ alert\(\'Booking system loading\.\.\. Please refresh the page\.\'\); \}" \s*class="w-full py-4 bg-yellow-500 text-black rounded-lg font-bold text-lg mb-3 hover:bg-yellow-600 transition-colors">Book This Tour<\/button>/s',
        '<button onclick="openLoginModal(<?php echo $tour[\'id\']; ?>, \'<?php echo addslashes($tour[\'name\']); ?>\', \'<?php echo addslashes($tour[\'description\']); ?>\', \'<?php echo htmlspecialchars($bg_image); ?>\')" class="w-full py-4 bg-yellow-500 text-black rounded-lg font-bold text-lg mb-3 hover:bg-yellow-600 transition-colors">Book This Tour</button>',
        $content
    );
    
    if ($content !== $original_content) {
        file_put_contents($file_path, $content);
        echo "  ✓ Fixed\n";
    } else {
        echo "  - Already fixed\n";
    }
}

echo "\n✓ Done!\n";
