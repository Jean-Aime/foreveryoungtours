<?php
// Fix upcoming tour images in all continents

$continents = ['asia', 'europe', 'south-america', 'oceania', 'north-america'];
$source_file = __DIR__ . "/continents/africa/index.php";

foreach ($continents as $continent) {
    $dest_file = __DIR__ . "/continents/{$continent}/index.php";
    
    if (file_exists($source_file)) {
        copy($source_file, $dest_file);
        echo "Fixed images: {$continent}/index.php\n";
    }
}

echo "\nUpcoming tour images fixed in all continents!\n";
?>