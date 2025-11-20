<?php
// Update all continent pages with professional design and admin panel integration

$continents = ['asia', 'europe', 'south-america', 'oceania', 'north-america'];
$source_continent = 'africa';

// Copy the updated index.php to all continents
foreach ($continents as $continent) {
    $source_file = __DIR__ . "/continents/{$source_continent}/index.php";
    $dest_file = __DIR__ . "/continents/{$continent}/index.php";
    
    if (file_exists($source_file)) {
        copy($source_file, $dest_file);
        echo "Updated: {$continent}/index.php\n";
    }
    
    // Copy continent header
    $header_source = __DIR__ . "/continents/{$source_continent}/includes/continent-header.php";
    $header_dest_dir = __DIR__ . "/continents/{$continent}/includes";
    
    if (!is_dir($header_dest_dir)) {
        mkdir($header_dest_dir, 0755, true);
    }
    
    $header_dest = "{$header_dest_dir}/continent-header.php";
    if (file_exists($header_source)) {
        copy($header_source, $header_dest);
        echo "Updated: {$continent}/includes/continent-header.php\n";
    }
}

// Copy booking and registration handlers
$handlers = [
    'submit-booking.php',
    'register.php'
];

foreach ($handlers as $handler) {
    $source_file = __DIR__ . "/continents/{$handler}";
    
    foreach ($continents as $continent) {
        $dest_file = __DIR__ . "/continents/{$continent}/{$handler}";
        
        if (file_exists($source_file)) {
            copy($source_file, $dest_file);
            echo "Updated: {$continent}/{$handler}\n";
        }
    }
}

echo "\nAll continents updated with:\n";
echo "- Professional gold/white/green design\n";
echo "- Header-less layout (footer only)\n";
echo "- Admin panel integration for bookings\n";
echo "- Admin panel integration for registrations\n";
echo "- Responsive professional design\n";
?>