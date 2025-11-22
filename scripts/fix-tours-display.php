<?php
// Fix tours display on all continent homepages

$continents = ['africa', 'asia', 'europe', 'south-america', 'oceania', 'north-america'];

foreach ($continents as $continent) {
    $file_path = __DIR__ . "/continents/{$continent}/index.php";
    
    if (file_exists($file_path)) {
        $content = file_get_contents($file_path);
        
        // Remove the featured requirement to show all tours
        $content = str_replace(
            "WHERE c.region_id = ? AND t.status = 'active' AND t.featured = 1",
            "WHERE c.region_id = ? AND t.status = 'active'",
            $content
        );
        
        file_put_contents($file_path, $content);
        echo "Fixed tours display: {$continent}/index.php\n";
    }
}

echo "\nAll continent homepages now display tours properly!\n";
?>