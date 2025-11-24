<?php
// Fix calendar pages in all continents - remove header and fix images

$continents = ['asia', 'europe', 'south-america', 'oceania', 'north-america'];
$source_file = __DIR__ . "/continents/africa/pages/calendar.php";

foreach ($continents as $continent) {
    $dest_file = __DIR__ . "/continents/{$continent}/pages/calendar.php";
    
    if (file_exists($source_file)) {
        copy($source_file, $dest_file);
        echo "Fixed: {$continent}/pages/calendar.php\n";
    }
}

echo "\nCalendar pages fixed - header removed, images fixed!\n";
?>