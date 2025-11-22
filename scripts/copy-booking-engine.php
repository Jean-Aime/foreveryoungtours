<?php
// Copy booking engine to all continents

$continents = ['asia', 'europe', 'south-america', 'oceania', 'north-america'];
$source_file = __DIR__ . "/continents/africa/pages/booking-engine.php";

foreach ($continents as $continent) {
    $dest_file = __DIR__ . "/continents/{$continent}/pages/booking-engine.php";
    
    if (file_exists($source_file)) {
        copy($source_file, $dest_file);
        echo "Created: {$continent}/pages/booking-engine.php\n";
    }
}

echo "\nBooking engine copied to all continents!\n";
?>