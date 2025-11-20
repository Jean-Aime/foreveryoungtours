<?php
// Add calendar section to all continent homepages

$continents = ['asia', 'europe', 'south-america', 'oceania', 'north-america'];
$source_file = __DIR__ . "/continents/africa/index.php";

foreach ($continents as $continent) {
    $dest_file = __DIR__ . "/continents/{$continent}/index.php";
    
    if (file_exists($source_file)) {
        copy($source_file, $dest_file);
        echo "Updated: {$continent}/index.php with calendar\n";
    }
}

echo "\nCalendar section added to all continent homepages!\n";
?>