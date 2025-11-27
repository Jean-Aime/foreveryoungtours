<?php
$base_dir = dirname(__DIR__);
$countries_dir = $base_dir . '/countries';

$files = glob($countries_dir . '/*/index.php');

echo "Found " . count($files) . " files\n\n";

foreach ($files as $file) {
    echo "Processing: $file\n";
    
    $content = file_get_contents($file);
    $original = $content;
    
    // Remove calendar section
    $content = preg_replace(
        '/<!-- Calendar Section -->.*?<\/section>/s',
        '',
        $content
    );
    
    // Remove tour dates code
    $content = preg_replace(
        '/\/\/ Get tour dates for calendar highlighting.*?}\s*}/s',
        '',
        $content
    );
    
    // Remove calendar CSS
    $content = preg_replace(
        '/\.calendar-day-with-tour.*?}\s*}/s',
        '',
        $content
    );
    
    // Remove calendar JS
    $content = preg_replace(
        '/const tourDates.*?renderCalendar\(\);/s',
        '',
        $content
    );
    
    if ($content !== $original) {
        file_put_contents($file, $content);
        echo "  ✓ Removed calendar\n";
    } else {
        echo "  - No calendar found\n";
    }
}

echo "\n✓ Done!\n";
