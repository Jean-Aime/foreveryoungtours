<?php
// Update calendar design in all continents

$continents = ['asia', 'europe', 'south-america', 'oceania', 'north-america'];
$source_file = __DIR__ . "/continents/africa/pages/calendar.php";

foreach ($continents as $continent) {
    $dest_file = __DIR__ . "/continents/{$continent}/pages/calendar.php";
    
    if (file_exists($source_file)) {
        copy($source_file, $dest_file);
        echo "Updated: {$continent}/pages/calendar.php design\n";
    }
}

echo "\nCalendar design updated in all continents!\n";
?>