<?php
/**
 * Subdomain Verification Script
 * Tests all continent and country pages
 */

require_once 'config.php';
require_once 'config/database.php';

echo "<!DOCTYPE html>
<html>
<head>
    <title>Subdomain Verification</title>
    <script src='https://cdn.tailwindcss.com'></script>
</head>
<body class='bg-gray-50 p-8'>
<div class='max-w-6xl mx-auto'>
    <h1 class='text-4xl font-bold mb-8 text-gray-900'>Subdomain Verification Report</h1>
";

// Test Continents
echo "<div class='bg-white rounded-lg shadow-lg p-6 mb-8'>
    <h2 class='text-2xl font-bold mb-4 text-blue-600'>Continent Pages</h2>
    <div class='space-y-2'>";

$continents = ['africa', 'asia', 'europe', 'north-america', 'south-america', 'oceania', 'caribbean'];
foreach ($continents as $continent) {
    $path = __DIR__ . "/continents/$continent/index.php";
    $url = BASE_URL . "/continents/$continent/";
    
    if (file_exists($path)) {
        $size = filesize($path);
        $modified = date('Y-m-d H:i:s', filemtime($path));
        
        // Check if file contains required elements
        $content = file_get_contents($path);
        $has_config = strpos($content, 'config.php') !== false;
        $has_database = strpos($content, 'database.php') !== false;
        $has_base_url = strpos($content, 'BASE_URL') !== false;
        
        $status = ($has_config && $has_database && $has_base_url) ? 'text-green-600' : 'text-red-600';
        $icon = ($has_config && $has_database && $has_base_url) ? '✓' : '✗';
        
        echo "<div class='flex items-center justify-between p-3 bg-gray-50 rounded'>
            <div>
                <span class='$status font-bold text-xl mr-2'>$icon</span>
                <a href='$url' target='_blank' class='text-blue-600 hover:underline font-semibold'>$continent</a>
                <span class='text-gray-500 text-sm ml-2'>($size bytes, modified: $modified)</span>
            </div>
            <div class='text-sm text-gray-600'>
                Config: " . ($has_config ? '✓' : '✗') . " | 
                DB: " . ($has_database ? '✓' : '✗') . " | 
                BASE_URL: " . ($has_base_url ? '✓' : '✗') . "
            </div>
        </div>";
    } else {
        echo "<div class='flex items-center p-3 bg-red-50 rounded'>
            <span class='text-red-600 font-bold text-xl mr-2'>✗</span>
            <span class='text-red-600'>$continent - FILE NOT FOUND</span>
        </div>";
    }
}

echo "</div></div>";

// Test Countries
echo "<div class='bg-white rounded-lg shadow-lg p-6 mb-8'>
    <h2 class='text-2xl font-bold mb-4 text-green-600'>Country Pages</h2>
    <div class='grid grid-cols-1 md:grid-cols-2 gap-4'>";

$countries_dir = __DIR__ . '/countries';
$country_folders = array_diff(scandir($countries_dir), ['.', '..', 'index.php', 'template-country.php']);

$working = 0;
$total = 0;

foreach ($country_folders as $country) {
    if (is_dir("$countries_dir/$country")) {
        $total++;
        $index_path = "$countries_dir/$country/index.php";
        $config_path = "$countries_dir/$country/config.php";
        $url = BASE_URL . "/countries/$country/";
        
        $has_index = file_exists($index_path);
        $has_config = file_exists($config_path);
        
        if ($has_index && $has_config) {
            $content = file_get_contents($index_path);
            $has_base_url = strpos($content, 'BASE_URL') !== false;
            $has_getImageUrl = strpos($content, 'getImageUrl') !== false;
            
            if ($has_base_url && $has_getImageUrl) {
                $working++;
                $status = 'bg-green-50 border-green-200';
                $icon = '✓';
                $color = 'text-green-600';
            } else {
                $status = 'bg-yellow-50 border-yellow-200';
                $icon = '⚠';
                $color = 'text-yellow-600';
            }
        } else {
            $status = 'bg-red-50 border-red-200';
            $icon = '✗';
            $color = 'text-red-600';
        }
        
        echo "<div class='$status border-2 rounded p-3'>
            <div class='flex items-center justify-between mb-2'>
                <span class='$color font-bold text-xl'>$icon</span>
                <a href='$url' target='_blank' class='text-blue-600 hover:underline font-semibold'>$country</a>
            </div>
            <div class='text-xs text-gray-600'>
                Index: " . ($has_index ? '✓' : '✗') . " | 
                Config: " . ($has_config ? '✓' : '✗') . "
            </div>
        </div>";
    }
}

echo "</div>
    <div class='mt-6 p-4 bg-blue-50 rounded'>
        <p class='text-lg font-semibold text-blue-900'>
            Summary: $working / $total countries working properly
        </p>
    </div>
</div>";

// Database Check
echo "<div class='bg-white rounded-lg shadow-lg p-6 mb-8'>
    <h2 class='text-2xl font-bold mb-4 text-purple-600'>Database Status</h2>
    <div class='space-y-3'>";

try {
    // Check regions
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM regions WHERE status = 'active'");
    $regions_count = $stmt->fetch()['count'];
    echo "<div class='flex items-center p-3 bg-green-50 rounded'>
        <span class='text-green-600 font-bold text-xl mr-2'>✓</span>
        <span class='text-gray-700'>Regions (Continents): <strong>$regions_count active</strong></span>
    </div>";
    
    // Check countries
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM countries WHERE status = 'active'");
    $countries_count = $stmt->fetch()['count'];
    echo "<div class='flex items-center p-3 bg-green-50 rounded'>
        <span class='text-green-600 font-bold text-xl mr-2'>✓</span>
        <span class='text-gray-700'>Countries: <strong>$countries_count active</strong></span>
    </div>";
    
    // Check tours
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM tours WHERE status = 'active'");
    $tours_count = $stmt->fetch()['count'];
    echo "<div class='flex items-center p-3 bg-green-50 rounded'>
        <span class='text-green-600 font-bold text-xl mr-2'>✓</span>
        <span class='text-gray-700'>Tours: <strong>$tours_count active</strong></span>
    </div>";
    
    // Check featured tours
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM tours WHERE status = 'active' AND featured = 1");
    $featured_count = $stmt->fetch()['count'];
    echo "<div class='flex items-center p-3 bg-green-50 rounded'>
        <span class='text-green-600 font-bold text-xl mr-2'>✓</span>
        <span class='text-gray-700'>Featured Tours: <strong>$featured_count</strong></span>
    </div>";
    
} catch (Exception $e) {
    echo "<div class='flex items-center p-3 bg-red-50 rounded'>
        <span class='text-red-600 font-bold text-xl mr-2'>✗</span>
        <span class='text-red-600'>Database Error: " . htmlspecialchars($e->getMessage()) . "</span>
    </div>";
}

echo "</div></div>";

// Configuration Check
echo "<div class='bg-white rounded-lg shadow-lg p-6 mb-8'>
    <h2 class='text-2xl font-bold mb-4 text-orange-600'>Configuration</h2>
    <div class='space-y-2'>
        <div class='p-3 bg-gray-50 rounded'>
            <strong>BASE_URL:</strong> <code class='bg-gray-200 px-2 py-1 rounded'>" . BASE_URL . "</code>
        </div>
        <div class='p-3 bg-gray-50 rounded'>
            <strong>HTTP_HOST:</strong> <code class='bg-gray-200 px-2 py-1 rounded'>" . $_SERVER['HTTP_HOST'] . "</code>
        </div>
        <div class='p-3 bg-gray-50 rounded'>
            <strong>Document Root:</strong> <code class='bg-gray-200 px-2 py-1 rounded'>" . __DIR__ . "</code>
        </div>
    </div>
</div>";

// Quick Links
echo "<div class='bg-gradient-to-r from-blue-500 to-purple-500 rounded-lg shadow-lg p-6 text-white'>
    <h2 class='text-2xl font-bold mb-4'>Quick Test Links</h2>
    <div class='grid grid-cols-2 md:grid-cols-4 gap-3'>
        <a href='" . BASE_URL . "/continents/africa/' target='_blank' class='bg-white/20 hover:bg-white/30 p-3 rounded text-center transition'>Africa</a>
        <a href='" . BASE_URL . "/continents/asia/' target='_blank' class='bg-white/20 hover:bg-white/30 p-3 rounded text-center transition'>Asia</a>
        <a href='" . BASE_URL . "/countries/rwanda/' target='_blank' class='bg-white/20 hover:bg-white/30 p-3 rounded text-center transition'>Rwanda</a>
        <a href='" . BASE_URL . "/countries/kenya/' target='_blank' class='bg-white/20 hover:bg-white/30 p-3 rounded text-center transition'>Kenya</a>
        <a href='" . BASE_URL . "/countries/egypt/' target='_blank' class='bg-white/20 hover:bg-white/30 p-3 rounded text-center transition'>Egypt</a>
        <a href='" . BASE_URL . "/countries/morocco/' target='_blank' class='bg-white/20 hover:bg-white/30 p-3 rounded text-center transition'>Morocco</a>
        <a href='" . BASE_URL . "/countries/south-africa/' target='_blank' class='bg-white/20 hover:bg-white/30 p-3 rounded text-center transition'>South Africa</a>
        <a href='" . BASE_URL . "/countries/nigeria/' target='_blank' class='bg-white/20 hover:bg-white/30 p-3 rounded text-center transition'>Nigeria</a>
    </div>
</div>";

echo "</div></body></html>";
?>
