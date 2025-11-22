<?php
// Fix tour images to use actual tour images from uploads folder

$continents = ['asia', 'europe', 'south-america', 'oceania', 'north-america'];

// Update homepages
foreach ($continents as $continent) {
    copy(__DIR__ . "/continents/africa/index.php", __DIR__ . "/continents/{$continent}/index.php");
    echo "Updated homepage images: {$continent}/index.php\n";
}

// Update calendar and packages pages
$all_continents = ['africa', 'asia', 'europe', 'south-america', 'oceania', 'north-america'];

foreach ($all_continents as $continent) {
    // Update calendar pages
    $calendar_file = __DIR__ . "/continents/{$continent}/pages/calendar.php";
    if (file_exists($calendar_file)) {
        $content = file_get_contents($calendar_file);
        $content = str_replace(
            'src="https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=200&q=80"',
            'src="<?php echo BASE_URL; ?>/uploads/tours/<?php echo $tour[\'cover_image\'] ?: $tour[\'image_url\']; ?>" onerror="this.src=\'<?php echo BASE_URL; ?>/assets/images/default-tour.jpg\';"',
            $content
        );
        file_put_contents($calendar_file, $content);
        echo "Updated calendar images: {$continent}/pages/calendar.php\n";
    }
    
    // Update packages pages
    $packages_file = __DIR__ . "/continents/{$continent}/pages/packages.php";
    if (file_exists($packages_file)) {
        $content = file_get_contents($packages_file);
        $content = str_replace(
            '<?= getImageUrl($tour[\'cover_image\'] ?: $tour[\'image_url\'], \'assets/images/default-tour.jpg\') ?>',
            '<?php echo BASE_URL; ?>/uploads/tours/<?php echo $tour[\'cover_image\'] ?: $tour[\'image_url\']; ?>',
            $content
        );
        $content = str_replace(
            'onerror="this.src=\'<?= getImageUrl(\'assets/images/africa.png\') ?>\'; this.onerror=function(){this.src=\'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=800&q=80\';};',
            'onerror="this.src=\'<?php echo BASE_URL; ?>/assets/images/default-tour.jpg\';',
            $content
        );
        file_put_contents($packages_file, $content);
        echo "Updated packages images: {$continent}/pages/packages.php\n";
    }
}

echo "\nAll tour images now use actual tour images from uploads folder!\n";
?>