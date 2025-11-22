<?php
// Update all continent pages with professional design

$continents = ['africa', 'asia', 'europe', 'south-america', 'oceania', 'north-america'];
$pages = ['packages.php', 'destinations.php', 'experiences.php', 'calendar.php', 'resources.php', 'contact.php', 'blog.php', 'booking-engine.php'];

foreach ($continents as $continent) {
    foreach ($pages as $page) {
        $file_path = __DIR__ . "/continents/{$continent}/pages/{$page}";
        
        if (file_exists($file_path)) {
            $content = file_get_contents($file_path);
            
            // Update header include
            $content = str_replace(
                ['$base_path = \'../../\';\n$css_path = \'../assets/css/modern-styles.css\';\ninclude \'../includes/header.php\';'],
                ['include \'../includes/continent-header.php\';'],
                $content
            );
            
            // Update footer include
            $content = str_replace(
                ['<?php include \'../includes/footer.php\'; ?>'],
                ['<?php include \'../../../includes/footer.php\'; ?>'],
                $content
            );
            
            // Update color classes for professional design
            $content = str_replace(
                ['bg-golden-500', 'text-golden-600', 'hover:bg-golden-600', 'bg-slate-200', 'text-slate-700', 'bg-slate-300'],
                ['bg-gold', 'text-gold', 'hover:bg-gold-dark', 'bg-gray-100', 'text-gray-700', 'bg-gray-200'],
                $content
            );
            
            // Update button classes
            $content = str_replace(
                ['class="bg-golden-500 text-white py-3 rounded-lg font-semibold hover:bg-golden-600 transition-colors"'],
                ['class="btn-gold py-3 rounded-lg font-semibold"'],
                $content
            );
            
            // Update card classes
            $content = str_replace(
                ['class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300"'],
                ['class="card-professional overflow-hidden"'],
                $content
            );
            
            // Update form action to use continent booking handler
            $content = str_replace(
                ['action="<?= BASE_URL ?>/pages/submit-booking.php"'],
                ['action="../submit-booking.php"'],
                $content
            );
            
            file_put_contents($file_path, $content);
            echo "Updated: {$continent}/pages/{$page}\n";
        }
    }
}

echo "\nAll continent pages updated with professional design!\n";
?>