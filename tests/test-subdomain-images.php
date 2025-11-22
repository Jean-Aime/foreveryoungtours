<!DOCTYPE html>
<html>
<head>
    <title>Subdomain Image Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .test-box { border: 1px solid #ccc; margin: 10px 0; padding: 10px; }
        .success { border-color: green; background: #f0fff0; }
        .error { border-color: red; background: #fff0f0; }
        img { max-width: 200px; height: auto; margin: 10px 0; }
    </style>
</head>
<body>
    <h1>Subdomain Image Test</h1>
    
    <?php
    session_start();
    require_once __DIR__ . '/config.php';
    
    echo "<p><strong>Current Host:</strong> " . $_SERVER['HTTP_HOST'] . "</p>";
    echo "<p><strong>BASE_URL:</strong> " . BASE_URL . "</p>";
    echo "<p><strong>Is Subdomain:</strong> " . (preg_match('/^visit-([a-z]{2,3})\./', $_SERVER['HTTP_HOST']) ? 'YES' : 'NO') . "</p>";
    ?>
    
    <h2>Image Path Tests for Subdomains</h2>
    
    <div class="test-box">
        <p><strong>Main Domain Image (should work):</strong></p>
        <img src="<?= BASE_URL ?>/assets/images/default-tour.jpg" alt="Main Domain Image" 
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
        <p><strong>Uploads Directory Test:</strong></p>
        <img src="<?= getImageUrl('uploads/tours/28_cover_1763207330_5662.jpeg') ?>" alt="Upload Test" 
             onload="this.parentElement.className='test-box success'" 
             onerror="this.parentElement.className='test-box error'; this.src='https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?w=400';">
        <p>URL: <?= getImageUrl('uploads/tours/28_cover_1763207330_5662.jpeg') ?></p>
    </div>
    
    <div class="test-box">
        <p><strong>Fallback Image (Unsplash):</strong></p>
        <img src="https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?w=400" alt="Fallback" 
             onload="this.parentElement.className='test-box success'" 
             onerror="this.parentElement.className='test-box error'; this.alt='FAILED';">
        <p>This should always work (external)</p>
    </div>
    
    <h2>Configuration Debug</h2>
    <pre><?php
    echo "SERVER INFO:\n";
    echo "HTTP_HOST: " . ($_SERVER['HTTP_HOST'] ?? 'NOT SET') . "\n";
    echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'NOT SET') . "\n";
    echo "HTTPS: " . ($_SERVER['HTTPS'] ?? 'NOT SET') . "\n";
    echo "\nPATH INFO:\n";
    echo "BASE_URL: " . BASE_URL . "\n";
    echo "Document Root: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'NOT SET') . "\n";
    echo "Script Name: " . ($_SERVER['SCRIPT_NAME'] ?? 'NOT SET') . "\n";
    ?></pre>
    
    <h2>Instructions for Subdomains</h2>
    <ul>
        <li>Subdomains should point to the main domain for images</li>
        <li>All images are stored on the main domain</li>
        <li>Green boxes = Images loaded successfully</li>
        <li>Red boxes = Images failed to load</li>
    </ul>
    
    <p><strong>Test URLs:</strong></p>
    <ul>
        <li><a href="http://visit-rw.foreveryoungtours.local/test-subdomain-images.php">Rwanda Subdomain (Local)</a></li>
        <li><a href="http://localhost/foreveryoungtours/test-subdomain-images.php">Main Domain (Local)</a></li>
    </ul>
</body>
</html>