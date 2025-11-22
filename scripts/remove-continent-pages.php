<?php
// Remove unwanted pages from all continents

$continents = ['africa', 'asia', 'europe', 'south-america', 'oceania', 'north-america'];
$pages_to_remove = ['contact.php', 'destinations.php', 'resources.php', 'blog.php'];

foreach ($continents as $continent) {
    foreach ($pages_to_remove as $page) {
        $file_path = __DIR__ . "/continents/{$continent}/pages/{$page}";
        
        if (file_exists($file_path)) {
            unlink($file_path);
            echo "Removed: {$continent}/pages/{$page}\n";
        }
    }
}

echo "\nUnwanted pages removed from all continents!\n";
echo "Remaining pages: packages.php, experiences.php, calendar.php, booking-engine.php\n";
?>