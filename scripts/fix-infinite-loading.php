<?php
// Fix infinite loading by using relative paths

$continents = ['asia', 'europe', 'south-america', 'oceania', 'north-america'];

foreach ($continents as $continent) {
    copy(__DIR__ . "/continents/africa/index.php", __DIR__ . "/continents/{$continent}/index.php");
    echo "Fixed: {$continent}/index.php\n";
}

echo "\nInfinite loading fixed - using relative paths!\n";
?>