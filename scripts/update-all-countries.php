<?php
/**
 * Bulk Update Script for Country Pages
 * Updates all country pages with improved spacing and responsive design
 */

$countries = [
    'botswana',
    'cameroon',
    'democratic-republic-of-congo',
    'egypt',
    'ethiopia',
    'ghana',
    'morocco',
    'namibia',
    'tanzania',
    'uganda',
    'zimbabwe'
];

$oldPattern = <<<'EOD'
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-5xl md:text-7xl font-bold text-white mb-6">
            <?= htmlspecialchars($country['name']) ?>
        </h1>
        <p class="text-xl md:text-2xl text-gray-200 mb-4">
            <?= htmlspecialchars($country['continent_name']) ?>
        </p>
        <p class="text-lg text-gray-300 mb-8 max-w-3xl mx-auto">
EOD;

$newPattern = <<<'EOD'
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold text-white mb-1 sm:mb-2">
            <?= htmlspecialchars($country['name']) ?>
        </h1>
        <p class="text-base sm:text-lg md:text-xl text-gray-200 mb-3 sm:mb-4">
            <?= htmlspecialchars($country['continent_name']) ?>
        </p>
        <p class="text-sm sm:text-base md:text-lg text-gray-300 mb-6 sm:mb-8 max-w-2xl mx-auto">
EOD;

foreach ($countries as $country) {
    $filePath = "c:/xampp/htdocs/foreveryoungtours/countries/$country/index.php";
    
    if (file_exists($filePath)) {
        $content = file_get_contents($filePath);
        $newContent = str_replace($oldPattern, $newPattern, $content);
        
        if ($content !== $newContent) {
            file_put_contents($filePath, $newContent);
            echo "✓ Updated: $country\n";
        } else {
            echo "- No changes needed: $country\n";
        }
    } else {
        echo "✗ File not found: $country\n";
    }
}

echo "\nDone!\n";
?>
