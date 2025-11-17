<?php
/**
 * Theme Generator Functions
 * Automatically generates country themes based on Rwanda master template
 */

/**
 * Generate folder name from slug
 */
function generateFolderName($slug) {
    // Convert visit-xx format to folder name
    $folder_mappings = [
        'visit-rw' => 'rwanda',
        'visit-ke' => 'kenya', 
        'visit-tz' => 'tanzania',
        'visit-ug' => 'uganda',
        'visit-za' => 'south-africa',
        'visit-eg' => 'egypt',
        'visit-ma' => 'morocco',
        'visit-bw' => 'botswana',
        'visit-na' => 'namibia',
        'visit-zw' => 'zimbabwe',
        'visit-gh' => 'ghana',
        'visit-ng' => 'nigeria',
        'visit-et' => 'ethiopia'
    ];
    
    if (isset($folder_mappings[$slug])) {
        return $folder_mappings[$slug];
    }
    
    // Generate folder name from slug
    return str_replace('visit-', '', $slug);
}

/**
 * Generate complete country theme
 */
function generateCountryTheme($country_data) {
    $rwanda_source = __DIR__ . '/../countries/rwanda/';
    $target_dir = __DIR__ . '/../countries/' . $country_data['folder'] . '/';

    // Create target directory
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    // Copy Rwanda theme structure (complete design clone)
    copyRwandaThemeStructure($rwanda_source, $target_dir);

    // Customize theme for specific country
    customizeCountryTheme($target_dir, $country_data);

    // Add continent inheritance if African country
    if (isAfricanCountry($country_data['country_code'])) {
        addAfricaContinentInheritance($target_dir, $country_data);
    }

    // Verify theme integrity
    verifyThemeIntegrity($target_dir, $country_data['name']);

    return [
        'success' => true,
        'message' => "Theme generated successfully for {$country_data['name']}. Complete Rwanda design cloned and customized.",
        'path' => $target_dir,
        'folder' => $country_data['folder']
    ];
}

/**
 * Copy Rwanda theme structure to target directory
 */
function copyRwandaThemeStructure($source, $target) {
    if (!is_dir($source)) {
        throw new Exception("Rwanda master theme not found at: $source");
    }

    // Copy index.php
    if (file_exists($source . 'index.php')) {
        copy($source . 'index.php', $target . 'index.php');
    }

    // Copy config.php
    if (file_exists($source . 'config.php')) {
        copy($source . 'config.php', $target . 'config.php');
    }

    // Copy continent-theme.php if exists
    if (file_exists($source . 'continent-theme.php')) {
        copy($source . 'continent-theme.php', $target . 'continent-theme.php');
    }

    // Copy assets directory (includes CSS, images, JS)
    if (is_dir($source . 'assets/')) {
        copyDirectory($source . 'assets/', $target . 'assets/');
    }

    // Copy includes directory (header, footer)
    if (is_dir($source . 'includes/')) {
        copyDirectory($source . 'includes/', $target . 'includes/');
    }

    // Copy pages directory (all page templates)
    if (is_dir($source . 'pages/')) {
        copyDirectory($source . 'pages/', $target . 'pages/');

        // Fix image paths in tour-detail.php after copying
        fixTourDetailImagePaths($target . 'pages/tour-detail.php');
    }

    // Create README file for images
    createImageReadme($target . 'assets/images/');
}

/**
 * Customize theme for specific country
 */
function customizeCountryTheme($target_dir, $country_data) {
    $index_file = $target_dir . 'index.php';
    
    if (!file_exists($index_file)) {
        throw new Exception("Theme index file not found");
    }
    
    $content = file_get_contents($index_file);
    
    // Get country-specific customization data
    $customization = getCountryCustomization($country_data);
    
    // Replace Rwanda-specific content
    $content = str_replace('Rwanda', $country_data['name'], $content);
    $content = str_replace('visit-rw', $country_data['slug'], $content);
    $content = str_replace('RWA', $country_data['country_code'], $content);
    
    // Update page title
    $content = preg_replace(
        '/\$page_title = ".*?";/',
        '$page_title = "Discover ' . $country_data['name'] . ' | Luxury Group Travel & Safari Adventures | Forever Young Tours";',
        $content
    );
    
    // Update meta description
    $description = $customization['description'] ?: "Premium {$country_data['name']} travel experiences. Luxury safaris, cultural immersion, and adventure tours.";
    $content = preg_replace(
        '/\$meta_description = ".*?";/',
        '$meta_description = "' . $description . '";',
        $content
    );
    
    // Update database query
    $content = str_replace(
        "c.slug = 'visit-rw'",
        "c.slug = '{$country_data['slug']}'",
        $content
    );
    
    // Ensure error handling exists
    if (strpos($content, 'if (!$country)') === false) {
        $pattern = '/(\$country = \$stmt->fetch\(\);)\s*\n\s*\/\/ Get featured tours/';
        $replacement = '$1

// Handle case where country is not found
if (!$country) {
    // Redirect to main site or show error
    header(\'Location: ../../index.php\');
    exit;
}

// Get featured tours';
        
        $content = preg_replace($pattern, $replacement, $content);
    }
    
    // Update URLs
    $content = str_replace(
        'https://visit-rw.iforeveryoungtours.com/',
        'https://' . $country_data['slug'] . '.iforeveryoungtours.com/',
        $content
    );
    
    // Ensure booking functionality is included
    if (strpos($content, 'enhanced-booking-modal.php') === false) {
        $content = str_replace(
            '</script>

</body>
</html>',
            '</script>

<!-- Include Enhanced Booking Modal -->
<?php include \'../../pages/enhanced-booking-modal.php\'; ?>

</body>
</html>',
            $content
        );
    }
    
    // Ensure Book Now buttons use booking modal
    $content = str_replace(
        'onclick="openRequestModal(\'<?= htmlspecialchars($tour[\'name\']) ?>\')"',
        'onclick="openBookingModal(<?= $tour[\'id\'] ?>, \'<?= htmlspecialchars($tour[\'name\']) ?>\', <?= $tour[\'price\'] ?>)"',
        $content
    );
    
    // Ensure image paths use relative paths that work from subdomain context
    $content = str_replace(
        "'/ForeverYoungTours/assets/images/africa.png'",
        "'../../assets/images/africa.png'",
        $content
    );
    
    // Add image error handling with relative paths
    if (strpos($content, 'handleImageError') === false) {
        $error_handler = '
<script>
function handleImageError(img) {
    if (img.src !== "../../assets/images/africa.png") {
        img.src = "../../assets/images/africa.png";
        img.onerror = null; // Prevent infinite loop
    }
}
</script>';
        
        $content = str_replace('</body>', $error_handler . '</body>', $content);
        
        // Add onerror handler to tour images
        $content = str_replace(
            'class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500">',
            'class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500" onerror="handleImageError(this)">',
            $content
        );
    }
    
    // Fix Open Graph images to use main domain for social media
    $og_image = strtolower($country_data['name']) . '-og.jpg';
    $og_url = "http://foreveryoungtours.local/assets/images/{$og_image}";
    
    $content = preg_replace(
        '/property="og:image" content="[^"]*"/',
        'property="og:image" content="' . $og_url . '"',
        $content
    );
    
    $content = preg_replace(
        '/property="twitter:image" content="[^"]*"/',
        'property="twitter:image" content="' . $og_url . '"',
        $content
    );
    
    // Update images
    $content = str_replace(
        'rwanda-og.jpg',
        strtolower($country_data['name']) . '-og.jpg',
        $content
    );
    
    $content = str_replace(
        'rwanda-gorilla-hero.png',
        strtolower($country_data['name']) . '-hero.png',
        $content
    );
    
    // Update main heading
    $content = str_replace(
        '<span class="block bg-gradient-to-r from-amber-200 via-amber-400 to-orange-400 bg-clip-text text-transparent drop-shadow-2xl">
                    Rwanda
                </span>',
        '<span class="block bg-gradient-to-r from-amber-200 via-amber-400 to-orange-400 bg-clip-text text-transparent drop-shadow-2xl">
                    ' . $country_data['name'] . '
                </span>',
        $content
    );
    
    // Update highlights
    $content = str_replace(
        'Gorillas. Volcanoes. Culture. Premium by design.',
        $customization['highlights'],
        $content
    );
    
    // Update stats
    $content = str_replace(
        '<div class="text-xl sm:text-2xl font-black text-white mb-1">Kigali</div>',
        '<div class="text-xl sm:text-2xl font-black text-white mb-1">' . $customization['capital'] . '</div>',
        $content
    );
    
    $content = str_replace(
        '<div class="text-xl sm:text-2xl font-black text-white mb-1">13.5M</div>',
        '<div class="text-xl sm:text-2xl font-black text-white mb-1">' . $customization['population'] . '</div>',
        $content
    );
    
    $content = str_replace(
        '<div class="text-xl sm:text-2xl font-black text-white mb-1">RWF</div>',
        '<div class="text-xl sm:text-2xl font-black text-white mb-1">' . $country_data['currency'] . '</div>',
        $content
    );
    
    // Update contact info
    $content = str_replace(
        'Norrsken House Kigali',
        $customization['operations'],
        $content
    );
    
    $content = str_replace(
        '"addressLocality": "Kigali"',
        '"addressLocality": "' . $customization['capital'] . '"',
        $content
    );
    
    $content = str_replace(
        '"addressCountry": "RW"',
        '"addressCountry": "' . $customization['country_code'] . '"',
        $content
    );
    
    // Update WhatsApp links
    $content = str_replace(
        'Rwanda%20Inquiry',
        $country_data['name'] . '%20Inquiry',
        $content
    );
    
    $content = str_replace(
        'Rwanda%20Dates%20Request',
        $country_data['name'] . '%20Dates%20Request',
        $content
    );
    
    $content = str_replace(
        'Request Rwanda Dates',
        'Request ' . $country_data['name'] . ' Dates',
        $content
    );
    
    // Save customized content
    file_put_contents($index_file, $content);
}

/**
 * Get country-specific customization data
 */
function getCountryCustomization($country_data) {
    $customizations = [
        'KEN' => [
            'description' => 'Premium Kenya travel. Big 5 safaris, Maasai culture, coastal adventures. Curated itineraries with luxury lodges.',
            'highlights' => 'Safaris. Beaches. Culture. Premium by design.',
            'capital' => 'Nairobi',
            'population' => '54M',
            'operations' => 'Nairobi Operations Center',
            'country_code' => 'KE'
        ],
        'TZA' => [
            'description' => 'Premium Tanzania travel. Serengeti migration, Kilimanjaro, Zanzibar beaches. Curated adventures with luxury lodges.',
            'highlights' => 'Migration. Kilimanjaro. Zanzibar. Premium by design.',
            'capital' => 'Dodoma',
            'population' => '62M',
            'operations' => 'Arusha Operations Center',
            'country_code' => 'TZ'
        ],
        'UGA' => [
            'description' => 'Premium Uganda travel. Mountain gorillas, chimpanzees, Nile adventures. Curated primate experiences.',
            'highlights' => 'Gorillas. Chimps. Nile. Premium by design.',
            'capital' => 'Kampala',
            'population' => '47M',
            'operations' => 'Kampala Operations Center',
            'country_code' => 'UG'
        ],
        'ZAF' => [
            'description' => 'Premium South Africa travel. Big 5 safaris, wine regions, Cape Town adventures. Curated luxury experiences.',
            'highlights' => 'Safaris. Wine. Cape Town. Premium by design.',
            'capital' => 'Cape Town',
            'population' => '60M',
            'operations' => 'Cape Town Operations Center',
            'country_code' => 'ZA'
        ],
        'EGY' => [
            'description' => 'Premium Egypt travel. Pyramids, Nile cruises, ancient temples. Curated historical adventures.',
            'highlights' => 'Pyramids. Nile. History. Premium by design.',
            'capital' => 'Cairo',
            'population' => '104M',
            'operations' => 'Cairo Operations Center',
            'country_code' => 'EG'
        ],
        'MAR' => [
            'description' => 'Premium Morocco travel. Sahara desert, imperial cities, Atlas mountains. Curated cultural experiences.',
            'highlights' => 'Desert. Cities. Atlas. Premium by design.',
            'capital' => 'Rabat',
            'population' => '37M',
            'operations' => 'Marrakech Operations Center',
            'country_code' => 'MA'
        ]
    ];
    
    $code = $country_data['country_code'];
    
    if (isset($customizations[$code])) {
        return $customizations[$code];
    }
    
    // Default customization
    return [
        'description' => "Premium {$country_data['name']} travel experiences. Luxury tours, cultural immersion, and adventure activities.",
        'highlights' => 'Adventure. Culture. Luxury. Premium by design.',
        'capital' => $country_data['name'],
        'population' => 'N/A',
        'operations' => $country_data['name'] . ' Operations Center',
        'country_code' => substr($country_data['country_code'], 0, 2)
    ];
}

/**
 * Add Africa continent inheritance
 */
function addAfricaContinentInheritance($target_dir, $country_data) {
    $africa_source = __DIR__ . '/../continents/africa/';
    
    // Copy Africa-specific assets
    if (is_dir($africa_source . 'assets/')) {
        if (is_dir($africa_source . 'assets/images/')) {
            if (!is_dir($target_dir . 'assets/images/')) {
                mkdir($target_dir . 'assets/images/', 0755, true);
            }
            copyDirectory($africa_source . 'assets/images/', $target_dir . 'assets/images/');
        }
    }
    
    // Create continent inheritance file
    $inheritance_content = '<?php
/**
 * Continent Theme Inheritance - Africa
 * Auto-generated for ' . $country_data['name'] . '
 */

define("CONTINENT_THEME", "africa");
define("CONTINENT_NAME", "Africa");
define("CONTINENT_COLOR_PRIMARY", "#F59E0B");
define("CONTINENT_COLOR_SECONDARY", "#EA580C");

$africa_features = [
    "wildlife_safaris" => true,
    "cultural_experiences" => true,
    "luxury_lodges" => true,
    "conservation_focus" => true,
    "adventure_activities" => true
];

$africa_contact = [
    "regional_office" => "Africa Regional Office",
    "phone" => "+1-737-443-9646",
    "email" => "africa@iforeveryoungtours.com"
];
?>';
    
    file_put_contents($target_dir . 'continent-theme.php', $inheritance_content);
}

/**
 * Fix image paths in tour detail pages for subdomain context
 */
function fixTourDetailImagePaths($tour_detail_file) {
    if (!file_exists($tour_detail_file)) {
        return;
    }

    $content = file_get_contents($tour_detail_file);

    // Check if function already exists
    if (strpos($content, 'function fixImagePath') !== false) {
        return; // Already fixed
    }

    // The image handling function to add
    $image_function = '
// Function to fix image paths for subdomain context
function fixImagePath($imagePath) {
    if (empty($imagePath)) {
        return \'/foreveryoungtours/assets/images/default-tour.jpg\';
    }

    // If it\'s an upload path, use absolute path from web root
    if (strpos($imagePath, \'uploads/\') === 0) {
        return \'/foreveryoungtours/\' . $imagePath;
    }

    // If it\'s already a relative path starting with ../
    if (strpos($imagePath, \'../\') === 0) {
        // Check if it\'s the wrong depth (../../ instead of ../../../)
        if (strpos($imagePath, \'../../assets/\') === 0) {
            return \'/foreveryoungtours/assets/\' . substr($imagePath, strlen(\'../../assets/\'));
        }
        // Convert any relative path to absolute
        $cleanPath = str_replace([\'../../../\', \'../../\', \'../\'], \'\', $imagePath);
        return \'/foreveryoungtours/\' . $cleanPath;
    }

    // If it\'s an assets path
    if (strpos($imagePath, \'assets/\') === 0) {
        return \'/foreveryoungtours/\' . $imagePath;
    }

    // If it\'s an external URL, return as-is
    if (strpos($imagePath, \'http\') === 0) {
        return $imagePath;
    }

    // Default case - assume it needs the full absolute path
    return \'/foreveryoungtours/\' . $imagePath;
}
';

    // Add the function after the database require
    $pattern = '/(require_once \'\.\.\/\.\.\/\.\.\/config\/database\.php\';)/';
    $replacement = '$1' . $image_function;
    $content = preg_replace($pattern, $replacement, $content);

    // Fix the main background image
    $content = preg_replace(
        '/\$bg_image = \$tour\[\'cover_image\'\] \?: \$tour\[\'image_url\'\] \?: \'\.\.\/\.\.\/\.\.\/assets\/images\/default-tour\.jpg\';[\s\S]*?if \(strpos\(\$bg_image, \'uploads\/\'\) === 0\) \{[\s\S]*?\$bg_image = \'\.\.\/\.\.\/\.\.\/' . '\' \. \$bg_image;[\s\S]*?\}/',
        '$bg_image = fixImagePath($tour[\'cover_image\'] ?: $tour[\'image_url\']);',
        $content
    );

    // Fix gallery images
    $content = preg_replace(
        '/\$image_src = \$image;[\s\S]*?if \(strpos\(\$image, \'uploads\/\'\) === 0\) \{[\s\S]*?\$image_src = \'\.\.\/\.\.\/\.\.\/' . '\' \. \$image;[\s\S]*?\}/',
        '$image_src = fixImagePath($image);',
        $content
    );

    // Fix related tour images
    $content = preg_replace(
        '/\$related_image = \$related\[\'cover_image\'\] \?: \$related\[\'image_url\'\] \?: \'\.\.\/\.\.\/\.\.\/assets\/images\/default-tour\.jpg\';[\s\S]*?if \(strpos\(\$related_image, \'uploads\/\'\) === 0\) \{[\s\S]*?\$related_image = \'\.\.\/\.\.\/\.\.\/' . '\' \. \$related_image;[\s\S]*?\}/',
        '$related_image = fixImagePath($related[\'cover_image\'] ?: $related[\'image_url\']);',
        $content
    );

    // Write the updated content
    file_put_contents($tour_detail_file, $content);
}

/**
 * Check if country is African
 */
function isAfricanCountry($country_code) {
    $african_countries = [
        'RWA', 'KEN', 'TZA', 'UGA', 'ZAF', 'EGY', 'MAR', 'BWA', 'NAM', 'ZWE', 
        'GHA', 'NGA', 'ETH', 'CMR', 'COD', 'SEN', 'TUN', 'DZA', 'LBY', 'SDN'
    ];
    
    return in_array($country_code, $african_countries);
}

/**
 * Update subdomain handler with new country
 */
function updateSubdomainHandler($country_code, $slug, $folder_name) {
    $handler_file = __DIR__ . '/../subdomain-handler.php';
    
    if (!file_exists($handler_file)) {
        return false;
    }
    
    $content = file_get_contents($handler_file);
    
    // Add to code mapping if not exists
    $two_letter_code = substr($country_code, 0, 2);
    $mapping_line = "        '{$two_letter_code}' => '{$country_code}',  // Country";
    
    if (strpos($content, "'{$two_letter_code}' =>") === false) {
        // Add before the closing bracket of code_mapping
        $content = str_replace(
            "        'ET' => 'ETH'   // Ethiopia\n    ];",
            "        'ET' => 'ETH',  // Ethiopia\n        {$mapping_line}\n    ];",
            $content
        );
    }
    
    // Add to folder mapping if not exists
    $folder_mapping_line = "            '{$slug}' => '{$folder_name}',";
    
    if (strpos($content, "'{$slug}' =>") === false) {
        // Add before the closing bracket of folder_mapping
        $content = str_replace(
            "            'visit-et' => 'ethiopia'\n        ];",
            "            'visit-et' => 'ethiopia',\n            {$folder_mapping_line}\n        ];",
            $content
        );
    }
    
    file_put_contents($handler_file, $content);
    return true;
}

/**
 * Copy directory recursively
 */
function copyDirectory($src, $dst) {
    if (!is_dir($src)) return;

    if (!is_dir($dst)) {
        mkdir($dst, 0755, true);
    }

    $files = scandir($src);
    foreach ($files as $file) {
        if ($file != "." && $file != "..") {
            $srcFile = $src . $file;
            $dstFile = $dst . $file;

            if (is_dir($srcFile)) {
                copyDirectory($srcFile . '/', $dstFile . '/');
            } else {
                copy($srcFile, $dstFile);
            }
        }
    }
}

/**
 * Create README file for country images directory
 */
function createImageReadme($images_dir) {
    if (!is_dir($images_dir)) {
        mkdir($images_dir, 0755, true);
    }

    $readme_content = "# Country Images Directory

This directory contains country-specific images for the theme.

## Required Images

1. **hero-{country}.jpg** - Main hero/banner image for the homepage
2. **{country}-og.jpg** - Open Graph image for social media sharing (1200x630px recommended)
3. **logo.png** - Country-specific logo (if applicable)

## Optional Images

- Additional hero images for variety
- Landmark images
- Cultural images
- Wildlife images

## Image Guidelines

- **Format:** JPG for photos, PNG for logos/graphics
- **Size:** Optimize images for web (compress without losing quality)
- **Hero Images:** Minimum 1920x1080px
- **OG Images:** 1200x630px for best social media display

## Fallback

If country-specific images are not available, the system will use:
1. Rwanda images as fallback
2. Generic Africa images
3. Default placeholder images

## Adding Images

Simply upload your images to this directory with the correct naming convention.
The theme will automatically detect and use them.
";

    file_put_contents($images_dir . 'README.txt', $readme_content);
}

/**
 * Verify theme integrity after generation
 */
function verifyThemeIntegrity($target_dir, $country_name) {
    $required_files = [
        'index.php',
        'config.php',
        'includes/header.php',
        'includes/footer.php',
        'pages/packages.php',
        'pages/tour-detail.php'
    ];

    $missing_files = [];
    foreach ($required_files as $file) {
        if (!file_exists($target_dir . $file)) {
            $missing_files[] = $file;
        }
    }

    if (!empty($missing_files)) {
        throw new Exception("Theme generation incomplete for $country_name. Missing files: " . implode(', ', $missing_files));
    }

    return true;
}
?>
