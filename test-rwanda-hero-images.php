<?php
/**
 * Test Rwanda Hero Images
 * 
 * This script tests the Rwanda-specific hero images to ensure they display correctly
 * on both main domain and subdomain access.
 */

// Include the main config
require_once 'config.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rwanda Hero Images Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .info { background: #e3f2fd; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .image-test { margin: 20px 0; padding: 20px; border: 2px solid #ddd; border-radius: 10px; }
        .image-test h3 { margin-top: 0; color: #333; }
        .hero-image { width: 100%; max-width: 600px; height: 300px; object-fit: cover; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.2); }
        .image-info { margin-top: 10px; font-size: 14px; color: #666; }
        .success { color: #4caf50; font-weight: bold; }
        .error { color: #f44336; font-weight: bold; }
        .url-display { background: #f8f9fa; padding: 10px; border-radius: 5px; font-family: monospace; word-break: break-all; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🇷🇼 Rwanda Hero Images Test</h1>
        
        <div class="info">
            <h3>📊 Current Configuration</h3>
            <p><strong>BASE_URL:</strong> <span class="url-display"><?= BASE_URL ?></span></p>
            <p><strong>HTTP_HOST:</strong> <span class="url-display"><?= $_SERVER['HTTP_HOST'] ?? 'Not set' ?></span></p>
            <p><strong>Current Access Method:</strong> 
                <?php if (strpos($_SERVER['HTTP_HOST'] ?? '', 'visit-rw.') !== false): ?>
                    <span class="success">✅ Subdomain Access (visit-rw.foreveryoungtours.local)</span>
                <?php else: ?>
                    <span class="success">✅ Direct Access (localhost/foreveryoungtours)</span>
                <?php endif; ?>
            </p>
        </div>

        <div class="image-test">
            <h3>🏔️ Primary Hero Image: hero-rwanda.jpg</h3>
            <img src="<?= getImageUrl('countries/rwanda/assets/images/hero-rwanda.jpg') ?>" 
                 alt="Rwanda Hero" 
                 class="hero-image"
                 onload="this.nextElementSibling.innerHTML = '<span class=success>✅ Image loaded successfully!</span>'"
                 onerror="this.nextElementSibling.innerHTML = '<span class=error>❌ Failed to load image</span>'">
            <div class="image-info">Loading status will appear here...</div>
            <div class="url-display"><?= getImageUrl('countries/rwanda/assets/images/hero-rwanda.jpg') ?></div>
        </div>

        <div class="image-test">
            <h3>🦍 Secondary Hero Image: rwanda-gorilla-hero.png</h3>
            <img src="<?= getImageUrl('countries/rwanda/assets/images/rwanda-gorilla-hero.png') ?>" 
                 alt="Rwanda Gorilla Hero" 
                 class="hero-image"
                 onload="this.nextElementSibling.innerHTML = '<span class=success>✅ Image loaded successfully!</span>'"
                 onerror="this.nextElementSibling.innerHTML = '<span class=error>❌ Failed to load image</span>'">
            <div class="image-info">Loading status will appear here...</div>
            <div class="url-display"><?= getImageUrl('countries/rwanda/assets/images/rwanda-gorilla-hero.png') ?></div>
        </div>

        <div class="image-test">
            <h3>🌋 Volcano Image: volucano.png</h3>
            <img src="<?= getImageUrl('countries/rwanda/assets/images/volucano.png') ?>" 
                 alt="Rwanda Volcano" 
                 class="hero-image"
                 onload="this.nextElementSibling.innerHTML = '<span class=success>✅ Image loaded successfully!</span>'"
                 onerror="this.nextElementSibling.innerHTML = '<span class=error>❌ Failed to load image</span>'">
            <div class="image-info">Loading status will appear here...</div>
            <div class="url-display"><?= getImageUrl('countries/rwanda/assets/images/volucano.png') ?></div>
        </div>

        <div class="image-test">
            <h3>🏢 Logo Image: logo.png</h3>
            <img src="<?= getImageUrl('countries/rwanda/assets/images/logo.png') ?>" 
                 alt="Rwanda Logo" 
                 class="hero-image"
                 onload="this.nextElementSibling.innerHTML = '<span class=success>✅ Image loaded successfully!</span>'"
                 onerror="this.nextElementSibling.innerHTML = '<span class=error>❌ Failed to load image</span>'">
            <div class="image-info">Loading status will appear here...</div>
            <div class="url-display"><?= getImageUrl('countries/rwanda/assets/images/logo.png') ?></div>
        </div>

        <div class="info">
            <h3>🔗 Test Links</h3>
            <p><strong>Direct Access:</strong> <a href="http://localhost/foreveryoungtours/test-rwanda-hero-images.php" target="_blank">http://localhost/foreveryoungtours/test-rwanda-hero-images.php</a></p>
            <p><strong>Rwanda Page Direct:</strong> <a href="http://localhost/foreveryoungtours/countries/rwanda/" target="_blank">http://localhost/foreveryoungtours/countries/rwanda/</a></p>
            <p><strong>Subdomain (if configured):</strong> <a href="http://visit-rw.foreveryoungtours.local/" target="_blank">http://visit-rw.foreveryoungtours.local/</a></p>
        </div>
    </div>
</body>
</html>
