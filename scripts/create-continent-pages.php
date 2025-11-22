<?php
// Script to create essential tourism pages for all continents

$continents = ['asia', 'europe', 'south-america', 'oceania', 'north-america'];
$source_continent = 'africa';

$pages = [
    'destinations.php',
    'experiences.php', 
    'calendar.php',
    'resources.php',
    'contact.php',
    'blog.php'
];

foreach ($continents as $continent) {
    $continent_dir = __DIR__ . "/continents/{$continent}";
    $pages_dir = "{$continent_dir}/pages";
    
    // Create pages directory if it doesn't exist
    if (!is_dir($pages_dir)) {
        mkdir($pages_dir, 0755, true);
        echo "Created directory: {$pages_dir}\n";
    }
    
    // Copy each page from Africa to this continent
    foreach ($pages as $page) {
        $source_file = __DIR__ . "/continents/{$source_continent}/pages/{$page}";
        $dest_file = "{$pages_dir}/{$page}";
        
        if (file_exists($source_file)) {
            if (!file_exists($dest_file)) {
                copy($source_file, $dest_file);
                echo "Created: {$continent}/pages/{$page}\n";
            } else {
                echo "Already exists: {$continent}/pages/{$page}\n";
            }
        } else {
            echo "Source file not found: {$source_file}\n";
        }
    }
}

echo "\nContinent pages creation completed!\n";
echo "\nEach continent now has the following pages:\n";
foreach ($pages as $page) {
    echo "- {$page}\n";
}

echo "\nAll pages are dynamically configured based on the continent folder structure.\n";
?>