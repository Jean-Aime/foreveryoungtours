<?php
// Restore original better design to all continents

$continents = ['asia', 'europe', 'south-america', 'oceania', 'north-america'];
$source_file = __DIR__ . "/continents/africa/index.php";

foreach ($continents as $continent) {
    $dest_file = __DIR__ . "/continents/{$continent}/index.php";
    
    if (file_exists($source_file)) {
        copy($source_file, $dest_file);
        echo "Restored: {$continent}/index.php\n";
    }
}

// Update packages pages to show "Featured [Continent] Tours"
$continents_all = ['africa', 'asia', 'europe', 'south-america', 'oceania', 'north-america'];

foreach ($continents_all as $continent) {
    $packages_file = __DIR__ . "/continents/{$continent}/pages/packages.php";
    
    if (file_exists($packages_file)) {
        $content = file_get_contents($packages_file);
        
        // Update title and description
        $content = str_replace(
            'Explore Tours in <?php echo htmlspecialchars($region[\'name\']); ?>',
            'Featured <?php echo htmlspecialchars($region[\'name\']); ?> Tours',
            $content
        );
        
        $content = str_replace(
            'Discover amazing destinations across <?php echo count($countries); ?> countries',
            'Discover our most popular experiences',
            $content
        );
        
        file_put_contents($packages_file, $content);
        echo "Updated: {$continent}/pages/packages.php\n";
    }
}

echo "\nOriginal better design restored to all continents!\n";
echo "Packages pages updated with 'Featured [Continent] Tours' titles!\n";
?>