<?php
$continents = ['asia', 'europe', 'south-america', 'oceania', 'north-america'];

foreach ($continents as $continent) {
    $file_path = __DIR__ . "/continents/{$continent}/index.php";
    
    if (file_exists($file_path)) {
        $content = file_get_contents($file_path);
        
        // Fix the PHP syntax error
        $content = str_replace(
            "include 'includes/continent-header.php';\n\n\n<!-- Hero Section -->",
            "include 'includes/continent-header.php';\n?>\n\n<!-- Hero Section -->",
            $content
        );
        
        file_put_contents($file_path, $content);
        echo "Fixed: {$continent}/index.php\n";
    }
}

echo "All continent syntax errors fixed!\n";
?>