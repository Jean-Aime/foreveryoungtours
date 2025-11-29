<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'config.php';
require_once '../config/database.php';
require_once 'upload_handler.php';

echo "<h1>Tour Add Debug</h1>";

if ($_POST && isset($_POST['action']) && $_POST['action'] === 'add') {
    try {
        echo "<p>Starting tour creation...</p>";
        
        $slug = strtolower(str_replace(' ', '-', $_POST['name']));
        echo "<p>Slug: " . htmlspecialchars($slug) . "</p>";
        
        $country_stmt = $pdo->prepare("SELECT name FROM countries WHERE id = ?");
        $country_stmt->execute([$_POST['country_id']]);
        $country = $country_stmt->fetch();
        
        if (!$country) {
            throw new Exception('Country not found with ID: ' . $_POST['country_id']);
        }
        
        echo "<p>Country: " . htmlspecialchars($country['name']) . "</p>";
        
        $stmt = $pdo->prepare("INSERT INTO tours (name, slug, description, destination, destination_country, country_id, category_id, price, base_price, duration, duration_days, max_participants, min_participants, status, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)");
        
        $result = $stmt->execute([
            $_POST['name'],
            $slug,
            $_POST['description'] ?? '',
            $_POST['destination'],
            $country['name'],
            $_POST['country_id'],
            $_POST['category_id'],
            $_POST['price'],
            $_POST['price'],
            $_POST['duration_days'] . ' days',
            $_POST['duration_days'],
            $_POST['max_participants'] ?? 20,
            $_POST['min_participants'] ?? 2,
            'active'
        ]);
        
        if (!$result) {
            $errorInfo = $stmt->errorInfo();
            throw new Exception('Database error: ' . $errorInfo[2]);
        }
        
        $tour_id = $pdo->lastInsertId();
        echo "<p style='color: green;'>✓ Tour created with ID: " . $tour_id . "</p>";
        
        if (isset($_FILES['main_image']) && $_FILES['main_image']['error'] === UPLOAD_ERR_OK) {
            echo "<p>Uploading main image...</p>";
            $image_url = uploadTourImage($_FILES['main_image'], $tour_id, 'main');
            echo "<p style='color: green;'>✓ Main image uploaded: " . htmlspecialchars($image_url) . "</p>";
        }
        
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            echo "<p>Uploading cover image...</p>";
            $cover_image = uploadTourImage($_FILES['cover_image'], $tour_id, 'cover');
            echo "<p style='color: green;'>✓ Cover image uploaded: " . htmlspecialchars($cover_image) . "</p>";
        }
        
        echo "<p style='color: green;'><strong>✓ Tour created successfully!</strong></p>";
        
    } catch (Exception $e) {
        echo "<p style='color: red;'><strong>✗ Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    }
} else {
    ?>
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="add">
        
        <label>Tour Name:</label>
        <input type="text" name="name" required><br><br>
        
        <label>Country ID:</label>
        <input type="number" name="country_id" required><br><br>
        
        <label>Destination:</label>
        <input type="text" name="destination" required><br><br>
        
        <label>Category ID:</label>
        <input type="number" name="category_id" required><br><br>
        
        <label>Price:</label>
        <input type="number" name="price" step="0.01" required><br><br>
        
        <label>Duration (days):</label>
        <input type="number" name="duration_days" required><br><br>
        
        <label>Description:</label>
        <textarea name="description"></textarea><br><br>
        
        <label>Main Image:</label>
        <input type="file" name="main_image" accept="image/*"><br><br>
        
        <label>Cover Image:</label>
        <input type="file" name="cover_image" accept="image/*"><br><br>
        
        <button type="submit">Test Add Tour</button>
    </form>
    <?php
}
?>
