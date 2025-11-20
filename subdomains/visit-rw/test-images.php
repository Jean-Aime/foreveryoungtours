<?php
session_start();

// Subdomain structure - adjust path based on your setup
$base_dir = dirname(dirname(__DIR__)); // Go up to main directory
require_once $base_dir . '/config.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Subdomain Image Test - Rwanda</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .test-box { border: 1px solid #ccc; margin: 10px 0; padding: 10px; }
        .success { border-color: green; background: #f0fff0; }
        .error { border-color: red; background: #fff0f0; }
        img { max-width: 200px; height: auto; margin: 10px 0; }
    </style>
</head>
<body>
    <h1>Rwanda Subdomain Image Test</h1>
    
    <p><strong>Current Host:</strong> <?= $_SERVER['HTTP_HOST'] ?></p>
    <p><strong>BASE_URL:</strong> <?= BASE_URL ?></p>
    <p><strong>Is Subdomain:</strong> <?= preg_match('/^visit-([a-z]{2,3})\./', $_SERVER['HTTP_HOST']) ? 'YES' : 'NO' ?></p>
    
    <h2>Image Tests</h2>
    
    <div class="test-box">
        <p><strong>Main Domain Default Image:</strong></p>
        <img src="<?= BASE_URL ?>/assets/images/default-tour.jpg" alt="Default" 
             onload="this.parentElement.className='test-box success'" 
             onerror="this.parentElement.className='test-box error'; this.alt='FAILED';">
        <p>URL: <?= BASE_URL ?>/assets/images/default-tour.jpg</p>
    </div>
    
    <div class="test-box">
        <p><strong>Using getImageUrl Function:</strong></p>
        <img src="<?= getImageUrl('assets/images/africa.png') ?>" alt="Function Test" 
             onload="this.parentElement.className='test-box success'" 
             onerror="this.parentElement.className='test-box error'; this.alt='FAILED';">
        <p>URL: <?= getImageUrl('assets/images/africa.png') ?></p>
    </div>
    
    <div class="test-box">
        <p><strong>Tour Upload Image:</strong></p>
        <img src="<?= getImageUrl('uploads/tours/28_cover_1763207330_5662.jpeg') ?>" alt="Tour Image" 
             onload="this.parentElement.className='test-box success'" 
             onerror="this.parentElement.className='test-box error'; this.src='https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?w=400';">
        <p>URL: <?= getImageUrl('uploads/tours/28_cover_1763207330_5662.jpeg') ?></p>
    </div>
    
    <div class="test-box">
        <p><strong>Unsplash Fallback:</strong></p>
        <img src="https://images.unsplash.com/photo-1609198092357-8e51c4b1d9f9?w=400" alt="Fallback" 
             onload="this.parentElement.className='test-box success'" 
             onerror="this.parentElement.className='test-box error'; this.alt='FAILED';">
        <p>External image (should always work)</p>
    </div>
    
    <h2>Links</h2>
    <ul>
        <li><a href="index.php">← Back to Rwanda Homepage</a></li>
        <li><a href="<?= BASE_URL ?>/test-subdomain-images.php">Main Domain Test</a></li>
    </ul>
</body>
</html>