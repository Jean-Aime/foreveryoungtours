<?php
// Fix all View Details links to use full subdomain URLs

$continents = ['asia', 'europe', 'south-america', 'oceania', 'north-america'];

// Copy updated files to all continents
foreach ($continents as $continent) {
    // Update homepage
    copy(__DIR__ . "/continents/africa/index.php", __DIR__ . "/continents/{$continent}/index.php");
    echo "Updated: {$continent}/index.php\n";
    
    // Update calendar page
    copy(__DIR__ . "/continents/africa/pages/calendar.php", __DIR__ . "/continents/{$continent}/pages/calendar.php");
    echo "Updated: {$continent}/pages/calendar.php\n";
    
    // Update packages page
    copy(__DIR__ . "/continents/africa/pages/packages.php", __DIR__ . "/continents/{$continent}/pages/packages.php");
    echo "Updated: {$continent}/pages/packages.php\n";
}

echo "\nAll View Details links now use full subdomain URLs!\n";
echo "Format: http://africa.foreveryoungtours.local/pages/tour-detail.php?id=30\n";
?>