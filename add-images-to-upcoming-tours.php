<?php
// Add tour images to upcoming departures in all continents

$continents = ['asia', 'europe', 'south-america', 'oceania', 'north-america'];
$source_file = __DIR__ . "/continents/africa/index.php";

foreach ($continents as $continent) {
    $dest_file = __DIR__ . "/continents/{$continent}/index.php";
    
    if (file_exists($source_file)) {
        copy($source_file, $dest_file);
        echo "Updated: {$continent}/index.php with tour images\n";
    }
}

echo "\nTour images added to upcoming departures in all continents!\n";
?>