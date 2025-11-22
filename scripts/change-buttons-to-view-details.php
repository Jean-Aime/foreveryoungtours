<?php
// Change Book Now buttons to View Details on all continent pages

$continents = ['asia', 'europe', 'south-america', 'oceania', 'north-america'];

// Update homepages
foreach ($continents as $continent) {
    $file_path = __DIR__ . "/continents/{$continent}/index.php";
    
    if (file_exists($file_path)) {
        copy(__DIR__ . "/continents/africa/index.php", $file_path);
        echo "Updated homepage: {$continent}/index.php\n";
    }
}

// Update calendar pages
foreach ($continents as $continent) {
    $file_path = __DIR__ . "/continents/{$continent}/pages/calendar.php";
    
    if (file_exists($file_path)) {
        copy(__DIR__ . "/continents/africa/pages/calendar.php", $file_path);
        echo "Updated calendar: {$continent}/pages/calendar.php\n";
    }
}

// Update packages pages
$continents_all = ['africa', 'asia', 'europe', 'south-america', 'oceania', 'north-america'];
foreach ($continents_all as $continent) {
    $file_path = __DIR__ . "/continents/{$continent}/pages/packages.php";
    
    if (file_exists($file_path)) {
        $content = file_get_contents($file_path);
        
        $content = str_replace(
            'Book Now',
            'View Details',
            $content
        );
        
        $content = str_replace(
            'onclick="openBookingModal(',
            'href="tour-detail.php?id=<?php echo $tour[\'id\']; ?>" onclick="return false;" data-onclick="openBookingModal(',
            $content
        );
        
        file_put_contents($file_path, $content);
        echo "Updated packages: {$continent}/pages/packages.php\n";
    }
}

echo "\nAll Book Now buttons changed to View Details!\n";
?>