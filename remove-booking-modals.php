<?php
// Remove booking modals from all continent homepages

$continents = ['asia', 'europe', 'south-america', 'oceania', 'north-america'];
$source_file = __DIR__ . "/continents/africa/index.php";

foreach ($continents as $continent) {
    $dest_file = __DIR__ . "/continents/{$continent}/index.php";
    
    if (file_exists($source_file)) {
        copy($source_file, $dest_file);
        echo "Removed booking modal: {$continent}/index.php\n";
    }
}

echo "\nBooking modals removed from all continent homepages!\n";
?>