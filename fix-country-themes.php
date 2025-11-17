<?php
/**
 * Fix Country Theme Customization
 * This script properly customizes the cloned themes for each country
 */

require_once 'config/database.php';

// Countries mapping with proper customization data
$country_mappings = [
    'KEN' => [
        'slug' => 'visit-ke', 
        'folder' => 'kenya', 
        'name' => 'Kenya',
        'description' => 'Premium Kenya travel. Big 5 safaris, Maasai culture, coastal adventures. Curated itineraries with luxury lodges and seamless logistics.',
        'highlights' => 'Safaris. Beaches. Culture. Premium by design.',
        'capital' => 'Nairobi',
        'population' => '54M',
        'currency' => 'KES',
        'operations' => 'Nairobi Operations Center',
        'country_code' => 'KE'
    ],
    'TZA' => [
        'slug' => 'visit-tz', 
        'folder' => 'tanzania', 
        'name' => 'Tanzania',
        'description' => 'Premium Tanzania travel. Serengeti migration, Kilimanjaro, Zanzibar beaches. Curated adventures with luxury lodges and expert guides.',
        'highlights' => 'Migration. Kilimanjaro. Zanzibar. Premium by design.',
        'capital' => 'Dodoma',
        'population' => '62M',
        'currency' => 'TZS',
        'operations' => 'Arusha Operations Center',
        'country_code' => 'TZ'
    ],
    'UGA' => [
        'slug' => 'visit-ug', 
        'folder' => 'uganda', 
        'name' => 'Uganda',
        'description' => 'Premium Uganda travel. Mountain gorillas, chimpanzees, Nile adventures. Curated primate experiences with luxury lodges.',
        'highlights' => 'Gorillas. Chimps. Nile. Premium by design.',
        'capital' => 'Kampala',
        'population' => '47M',
        'currency' => 'UGX',
        'operations' => 'Kampala Operations Center',
        'country_code' => 'UG'
    ],
    'ZAF' => [
        'slug' => 'visit-za', 
        'folder' => 'south-africa', 
        'name' => 'South Africa',
        'description' => 'Premium South Africa travel. Big 5 safaris, wine regions, Cape Town adventures. Curated luxury experiences.',
        'highlights' => 'Safaris. Wine. Cape Town. Premium by design.',
        'capital' => 'Cape Town',
        'population' => '60M',
        'currency' => 'ZAR',
        'operations' => 'Cape Town Operations Center',
        'country_code' => 'ZA'
    ],
    'EGY' => [
        'slug' => 'visit-eg', 
        'folder' => 'egypt', 
        'name' => 'Egypt',
        'description' => 'Premium Egypt travel. Pyramids, Nile cruises, ancient temples. Curated historical adventures with luxury accommodations.',
        'highlights' => 'Pyramids. Nile. History. Premium by design.',
        'capital' => 'Cairo',
        'population' => '104M',
        'currency' => 'EGP',
        'operations' => 'Cairo Operations Center',
        'country_code' => 'EG'
    ],
    'MAR' => [
        'slug' => 'visit-ma', 
        'folder' => 'morocco', 
        'name' => 'Morocco',
        'description' => 'Premium Morocco travel. Sahara desert, imperial cities, Atlas mountains. Curated cultural experiences with luxury riads.',
        'highlights' => 'Desert. Cities. Atlas. Premium by design.',
        'capital' => 'Rabat',
        'population' => '37M',
        'currency' => 'MAD',
        'operations' => 'Marrakech Operations Center',
        'country_code' => 'MA'
    ]
];

echo "<h1>Fixing Country Theme Customization</h1>\n";
echo "<pre>\n";

foreach ($country_mappings as $code => $country_info) {
    $target_file = "countries/{$country_info['folder']}/index.php";
    
    if (file_exists($target_file)) {
        echo "Fixing {$country_info['name']} theme...\n";
        
        $content = file_get_contents($target_file);
        $content = customizeCountryTheme($content, $country_info, $code);
        file_put_contents($target_file, $content);
        
        echo "  ✓ Updated {$country_info['name']} theme\n\n";
    }
}

echo "All country themes have been properly customized!\n";
echo "</pre>";

function customizeCountryTheme($content, $country_info, $code) {
    // Fix page title
    $content = preg_replace(
        '/\$page_title = ".*?";/',
        '$page_title = "Discover ' . $country_info['name'] . ' | Luxury Group Travel & Safari Adventures | Forever Young Tours";',
        $content
    );
    
    // Fix meta description
    $content = preg_replace(
        '/\$meta_description = ".*?";/',
        '$meta_description = "' . $country_info['description'] . '";',
        $content
    );
    
    // Fix database query slug
    $content = str_replace(
        "c.slug = 'visit-rw'",
        "c.slug = '" . $country_info['slug'] . "'",
        $content
    );
    
    // Fix canonical URL
    $content = str_replace(
        'https://visit-rw.iforeveryoungtours.com/',
        'https://' . $country_info['slug'] . '.iforeveryoungtours.com/',
        $content
    );
    
    // Fix Open Graph URLs
    $content = str_replace(
        'https://visit-rw.iforeveryoungtours.com/',
        'https://' . $country_info['slug'] . '.iforeveryoungtours.com/',
        $content
    );
    
    // Fix Open Graph images
    $content = str_replace(
        'rwanda-og.jpg',
        strtolower($country_info['name']) . '-og.jpg',
        $content
    );
    
    // Fix hero image
    $content = str_replace(
        'rwanda-gorilla-hero.png',
        strtolower($country_info['name']) . '-hero.png',
        $content
    );
    
    // Fix main heading
    $content = str_replace(
        '<span class="block bg-gradient-to-r from-amber-200 via-amber-400 to-orange-400 bg-clip-text text-transparent drop-shadow-2xl">
                    Rwanda
                </span>',
        '<span class="block bg-gradient-to-r from-amber-200 via-amber-400 to-orange-400 bg-clip-text text-transparent drop-shadow-2xl">
                    ' . $country_info['name'] . '
                </span>',
        $content
    );
    
    // Fix description
    $content = str_replace(
        'Gorillas. Volcanoes. Culture. Premium by design.',
        $country_info['highlights'],
        $content
    );
    
    // Fix capital city
    $content = str_replace(
        '<div class="text-xl sm:text-2xl font-black text-white mb-1">Kigali</div>',
        '<div class="text-xl sm:text-2xl font-black text-white mb-1">' . $country_info['capital'] . '</div>',
        $content
    );
    
    // Fix population
    $content = str_replace(
        '<div class="text-xl sm:text-2xl font-black text-white mb-1">13.5M</div>',
        '<div class="text-xl sm:text-2xl font-black text-white mb-1">' . $country_info['population'] . '</div>',
        $content
    );
    
    // Fix currency
    $content = str_replace(
        '<div class="text-xl sm:text-2xl font-black text-white mb-1">RWF</div>',
        '<div class="text-xl sm:text-2xl font-black text-white mb-1">' . $country_info['currency'] . '</div>',
        $content
    );
    
    // Fix operations center
    $content = str_replace(
        'Norrsken House Kigali',
        $country_info['operations'],
        $content
    );
    
    // Fix address locality
    $content = str_replace(
        '"addressLocality": "Kigali"',
        '"addressLocality": "' . $country_info['capital'] . '"',
        $content
    );
    
    // Fix address country
    $content = str_replace(
        '"addressCountry": "RW"',
        '"addressCountry": "' . $country_info['country_code'] . '"',
        $content
    );
    
    // Fix schema.org name
    $content = str_replace(
        '"name": "Rwanda"',
        '"name": "' . $country_info['name'] . '"',
        $content
    );
    
    // Fix schema.org description
    $content = str_replace(
        'Premium Rwanda travel with gorilla, chimp, and golden monkey encounters',
        'Premium ' . $country_info['name'] . ' travel with luxury safari and cultural experiences',
        $content
    );
    
    // Fix WhatsApp links
    $content = str_replace(
        'Rwanda%20Inquiry',
        $country_info['name'] . '%20Inquiry',
        $content
    );
    
    $content = str_replace(
        'Rwanda%20Dates%20Request',
        $country_info['name'] . '%20Dates%20Request',
        $content
    );
    
    $content = str_replace(
        'Rwanda%20Pricing%20Request',
        $country_info['name'] . '%20Pricing%20Request',
        $content
    );
    
    // Fix modal titles
    $content = str_replace(
        'Request Rwanda Dates',
        'Request ' . $country_info['name'] . ' Dates',
        $content
    );
    
    // Fix form values
    $content = str_replace(
        'Rwanda Dates Request',
        $country_info['name'] . ' Dates Request',
        $content
    );
    
    return $content;
}
?>
