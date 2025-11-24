<?php
// Revert to working Unsplash images to fix infinite loading

$continents = ['asia', 'europe', 'south-america', 'oceania', 'north-america'];

foreach ($continents as $continent) {
    copy(__DIR__ . "/continents/africa/index.php", __DIR__ . "/continents/{$continent}/index.php");
    echo "Reverted: {$continent}/index.php\n";
}

echo "\nReverted to working Unsplash images - pages should load now!\n";
?>