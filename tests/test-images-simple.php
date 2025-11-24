<!DOCTYPE html>
<html>
<head>
    <title>Simple Image Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .test-box { border: 1px solid #ccc; margin: 10px 0; padding: 10px; }
        .success { border-color: green; background: #f0fff0; }
        .error { border-color: red; background: #fff0f0; }
        img { max-width: 200px; height: auto; margin: 10px 0; }
    </style>
</head>
<body>
    <h1>Simple Image Test for Online Server</h1>
    
    <?php
    session_start();
    require_once __DIR__ . '/config.php';
    
    echo "<p><strong>BASE_URL:</strong> " . BASE_URL . "</p>";
    echo "<p><strong>Server:</strong> " . $_SERVER['HTTP_HOST'] . "</p>";
    ?>
    
    <h2>Test 1: Direct Image Paths</h2>
    <div class="test-box">
        <p><strong>Default Tour Image:</strong></p>
        <img src="<?= BASE_URL ?>/assets/images/default-tour.jpg" alt="Default Tour" 
             onload="this.parentElement.className='test-box success'" 
             onerror="this.parentElement.className='test-box error'; this.alt='FAILED';">
        <p>URL: <?= BASE_URL ?>/assets/images/default-tour.jpg</p>
    </div>
    
    <div class="test-box">
        <p><strong>Africa Image:</strong></p>
        <img src="<?= BASE_URL ?>/assets/images/africa.png" alt="Africa" 
             onload="this.parentElement.className='test-box success'" 
             onerror="this.parentElement.className='test-box error'; this.alt='FAILED';">
        <p>URL: <?= BASE_URL ?>/assets/images/africa.png</p>
    </div>
    
    <h2>Test 2: Using getImageUrl Function</h2>
    <div class="test-box">
        <p><strong>Function Test:</strong></p>
        <img src="<?= getImageUrl('assets/images/default-tour.jpg') ?>" alt="Function Test" 
             onload="this.parentElement.className='test-box success'" 
             onerror="this.parentElement.className='test-box error'; this.alt='FAILED';">
        <p>URL: <?= getImageUrl('assets/images/default-tour.jpg') ?></p>
    </div>
    
    <h2>Test 3: Fallback Images</h2>
    <div class="test-box">
        <p><strong>Unsplash Fallback:</strong></p>
        <img src="https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?w=400" alt="Unsplash Fallback" 
             onload="this.parentElement.className='test-box success'" 
             onerror="this.parentElement.className='test-box error'; this.alt='FAILED';">
        <p>This should always work (external image)</p>
    </div>
    
    <h2>Test 4: Database Images</h2>
    <?php
    try {
        require_once __DIR__ . '/config/database.php';
        $stmt = $pdo->prepare("SELECT name, image_url, cover_image FROM tours WHERE (image_url IS NOT NULL OR cover_image IS NOT NULL) LIMIT 3");
        $stmt->execute();
        $tours = $stmt->fetchAll();
        
        foreach ($tours as $tour) {
            $image_path = $tour['image_url'] ?: $tour['cover_image'];
            echo '<div class="test-box">';
            echo '<p><strong>Tour:</strong> ' . htmlspecialchars($tour['name']) . '</p>';
            echo '<img src="' . getImageUrl($image_path) . '" alt="Tour Image" 
                       onload="this.parentElement.className=\'test-box success\'" 
                       onerror="this.parentElement.className=\'test-box error\'; this.src=\'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?w=400\';">';
            echo '<p>URL: ' . htmlspecialchars(getImageUrl($image_path)) . '</p>';
            echo '</div>';
        }
    } catch (Exception $e) {
        echo '<div class="test-box error"><p>Database Error: ' . htmlspecialchars($e->getMessage()) . '</p></div>';
    }
    ?>
    
    <h2>Instructions</h2>
    <ul>
        <li>Green boxes = Images loaded successfully</li>
        <li>Red boxes = Images failed to load</li>
        <li>If all tests fail, check your server configuration</li>
        <li>If only database images fail, upload images to uploads/tours/ directory</li>
    </ul>
    
    <p><a href="<?= BASE_URL ?>/continents/africa/">← Back to Africa Page</a></p>
</body>
</html>