<?php

require_once 'config.php';
/**
 * Continent Theme Inheritance - Africa
 * This file provides continent-level theming for Egypt
 */

// Africa continent configuration
define("CONTINENT_THEME", "africa");
define("CONTINENT_NAME", "Africa");
define("CONTINENT_COLOR_PRIMARY", "#F59E0B");
define("CONTINENT_COLOR_SECONDARY", "#EA580C");

// Africa-specific features
$africa_features = [
    "wildlife_safaris" => true,
    "cultural_experiences" => true,
    "luxury_lodges" => true,
    "conservation_focus" => true,
    "adventure_activities" => true
];

// Africa-specific navigation
$africa_navigation = [
    ["name" => "Safari Tours", "url" => "/safaris"],
    ["name" => "Cultural Experiences", "url" => "/culture"],
    ["name" => "Luxury Lodges", "url" => "/lodges"],
    ["name" => "Conservation", "url" => "/conservation"]
];

// Africa-specific contact info
$africa_contact = [
    "regional_office" => "Africa Regional Office",
    "phone" => "+1-737-443-9646",
    "email" => "africa@iforeveryoungtours.com",
    "whatsapp" => "17374439646"
];

// Load continent-specific assets
function loadAfricaAssets() {
    echo '<link rel="stylesheet" href="assets/css/africa-theme.css">';
    echo '<script src="' . getImageUrl('assets/js/africa-theme.js') . '"></script>';
}

// Africa theme customization
function getAfricaThemeColors() {
    return [
        "primary" => "#F59E0B",
        "secondary" => "#EA580C", 
        "accent" => "#10B981",
        "text" => "#1F2937"
    ];
}
?>