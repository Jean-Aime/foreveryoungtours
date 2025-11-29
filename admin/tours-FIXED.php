<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$current_page = 'tours';
require_once 'config.php';
require_once '../includes/csrf.php';
require_once '../config/database.php';
require_once '../auth/check_auth.php';
checkAuth('super_admin');
require_once 'upload_handler.php';

$page_title = "Tours & Packages Management";
$page_subtitle = "Manage Tour Offerings";

$success = '';
$error = '';

// Handle tour operations
if ($_POST) {
    requireCsrf();
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'edit':
                // CRITICAL: Validate tour_id exists and is numeric
                if (!isset($_POST['tour_id']) || !is_numeric($_POST['tour_id']) || $_POST['tour_id'] <= 0) {
                    header('Location: tours.php?error=Invalid+tour+ID');
                    exit;
                }
                
                $tour_id = intval($_POST['tour_id']);
                $slug = strtolower(str_replace(' ', '-', $_POST['name']));
                
                // Check for duplicate slug and make it unique
                $original_slug = $slug;
                $counter = 1;
                $check_stmt = $pdo->prepare("SELECT id FROM tours WHERE slug = ? AND id != ?");
                $check_stmt->execute([$slug, $tour_id]);
                while ($check_stmt->fetch()) {
                    $slug = $original_slug . '-' . $counter;
                    $counter++;
                    $check_stmt->execute([$slug, $tour_id]);
                }
                
                // Get country details
                $country_stmt = $pdo->prepare("SELECT name FROM countries WHERE id = ?");
                $country_stmt->execute([$_POST['country_id']]);
                $country = $country_stmt->fetch();
                
                // Prepare itinerary JSON
                $itinerary = [];
                if (isset($_POST['itinerary_day'])) {
                    for ($i = 0; $i < count($_POST['itinerary_day']); $i++) {
                        $itinerary[] = [
                            'day' => $_POST['itinerary_day'][$i],
                            'title' => $_POST['itinerary_title'][$i] ?? '',
                            'activities' => $_POST['itinerary_activities'][$i] ?? ''
                        ];
                    }
                }
                
                // Prepare inclusions and exclusions
                $inclusions = !empty($_POST['inclusions']) ? array_filter(explode("\n", $_POST['inclusions'])) : [];
                $exclusions = !empty($_POST['exclusions']) ? array_filter(explode("\n", $_POST['exclusions'])) : [];
                
                // Handle file uploads
                $image_url = $_POST['current_image_url'] ?? '';
                $cover_image = $_POST['current_cover_image'] ?? '';
                
                try {
                    // Main image upload
                    if (isset($_FILES['main_image']) && $_FILES['main_image']['error'] === UPLOAD_ERR_OK) {
                        if ($image_url) deleteFile($image_url);
                        $image_url = uploadTourImage($_FILES['main_image'], $tour_id, 'main');
                    }
                    
                    // Cover image upload
                    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
                        if ($cover_image) deleteFile($cover_image);
                        $cover_image = uploadTourImage($_FILES['cover_image'], $tour_id, 'cover');
                    }
                    
                    // Simple images array
                    $images = [];
                    if ($image_url) $images[] = $image_url;
                    if ($cover_image && $cover_image !== $image_url) $images[] = $cover_image;
                    
                    // Gallery images (legacy support)
                    $gallery = [];
                    if (!empty($_POST['current_gallery'])) {
                        $current_gallery = json_decode($_POST['current_gallery'], true);
                        if (is_array($current_gallery)) {
                            $gallery = $current_gallery;
                        }
                    }
                    
                    if (isset($_FILES['gallery_images'])) {
                        foreach ($_FILES['gallery_images']['tmp_name'] as $key => $tmp_name) {
                            if ($_FILES['gallery_images']['error'][$key] === UPLOAD_ERR_OK) {
                                $file = [
                                    'name' => $_FILES['gallery_images']['name'][$key],
                                    'type' => $_FILES['gallery_images']['type'][$key],
                                    'tmp_name' => $tmp_name,
                                    'size' => $_FILES['gallery_images']['size'][$key]
                                ];
                                $uploaded_image = uploadTourImage($file, $tour_id, 'gallery_' . $key);
                                $gallery[] = $uploaded_image;
                                $images[] = $uploaded_image;
                            }
                        }
                    }
                } catch (Exception $e) {
                    header('Location: tours.php?error=' . urlencode($e->getMessage()));
                    exit;
                }
                
                $highlights = !empty($_POST['highlights']) ? array_filter(explode("\n", $_POST['highlights'])) : [];
                
                $stmt = $pdo->prepare("UPDATE tours SET name = ?, slug = ?, description = ?, detailed_description = ?, destination = ?, destination_country = ?, country_id = ?, category_id = ?, price = ?, base_price = ?, duration = ?, duration_days = ?, max_participants = ?, min_participants = ?, image_url = ?, cover_image = ?, gallery = ?, images = ?, itinerary = ?, inclusions = ?, exclusions = ?, highlights = ?, requirements = ?, difficulty_level = ?, best_time_to_visit = ?, status = ?, featured = ? WHERE id = ?");
                
                $stmt->execute([
                    $_POST['name'], $slug, $_POST['description'], $_POST['detailed_description'] ?? '',
                    $_POST['destination'], $country['name'], $_POST['country_id'], $_POST['category_id'], 
                    $_POST['price'], $_POST['price'], $_POST['duration_days'] . ' days', $_POST['duration_days'], 
                    $_POST['max_participants'], $_POST['min_participants'] ?? 2,
                    $image_url, $cover_image, json_encode($gallery), json_encode($images), 
                    json_encode($itinerary), json_encode($inclusions), json_encode($exclusions), json_encode($highlights),
                    $_POST['requirements'] ?? '', $_POST['difficulty_level'] ?? 'moderate',
                    $_POST['best_time_to_visit'] ?? '', $_POST['status'] ?? 'active',
                    isset($_POST['featured']) ? 1 : 0, $tour_id
                ]);
                header('Location: tours.php?updated=1');
                exit;
                break;
                
            case 'add':
                // CRITICAL: Validate tour_id does NOT exist for new tours
                if (isset($_POST['tour_id']) && !empty($_POST['tour_id'])) {
                    header('Location: tours.php?error=Invalid+request');
                    exit;
                }
                
                try {
                    $slug = strtolower(str_replace(' ', '-', $_POST['name']));
                    
                    // Check for duplicate slug and make it unique
                    $original_slug = $slug;
                    $counter = 1;
                    $check_stmt = $pdo->prepare("SELECT id FROM tours WHERE slug = ?");
                    $check_stmt->execute([$slug]);
                    while ($check_stmt->fetch()) {
                        $slug = $original_slug . '-' . $counter;
                        $counter++;
                        $check_stmt->execute([$slug]);
                    }
                    
                    // Get country details
                    $country_stmt = $pdo->prepare("SELECT name FROM countries WHERE id = ?");
                    $country_stmt->execute([$_POST['country_id']]);
                    $country = $country_stmt->fetch();
                    
                    // Prepare itinerary JSON
                    $itinerary = [];
                    if (isset($_POST['itinerary_day'])) {
                        for ($i = 0; $i < count($_POST['itinerary_day']); $i++) {
                            $itinerary[] = [
                                'day' => $_POST['itinerary_day'][$i],
                                'title' => $_POST['itinerary_title'][$i] ?? '',
                                'activities' => $_POST['itinerary_activities'][$i] ?? ''
                            ];
                        }
                    }
                    
                    // Prepare inclusions and exclusions
                    $inclusions = !empty($_POST['inclusions']) ? array_filter(explode("\n", $_POST['inclusions'])) : [];
                    $exclusions = !empty($_POST['exclusions']) ? array_filter(explode("\n", $_POST['exclusions'])) : [];
                    $highlights = !empty($_POST['highlights']) ? array_filter(explode("\n", $_POST['highlights'])) : [];
                    
                    // Insert tour first to get ID
                    $stmt = $pdo->prepare("INSERT INTO tours (name, slug, description, detailed_description, destination, destination_country, country_id, category_id, price, base_price, duration, duration_days, max_participants, min_participants, requirements, difficulty_level, best_time_to_visit, status, featured, itinerary, inclusions, exclusions, highlights, image_url, cover_image, gallery, images, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)");
                    
                    $result = $stmt->execute([
                        $_POST['name'], $slug, $_POST['description'], $_POST['detailed_description'] ?? '',
                        $_POST['destination'], $country['name'], $_POST['country_id'], $_POST['category_id'], 
                        $_POST['price'], $_POST['price'], $_POST['duration_days'] . ' days', $_POST['duration_days'], 
                        $_POST['max_participants'] ?? 20, $_POST['min_participants'] ?? 2,
                        $_POST['requirements'] ?? '', $_POST['difficulty_level'] ?? 'moderate',
                        $_POST['best_time_to_visit'] ?? '', $_POST['status'] ?? 'active',
                        isset($_POST['featured']) ? 1 : 0,
                        json_encode($itinerary), json_encode($inclusions), json_encode($exclusions), json_encode($highlights),
                        '', '', json_encode([]), json_encode([])
                    ]);
                    
                    if (!$result) {
                        $errorInfo = $stmt->errorInfo();
                        throw new Exception('Database error: ' . $errorInfo[2]);
                    }
                    
                    $tour_id = $pdo->lastInsertId();
                    
                    if (!$tour_id || $tour_id == 0) {
                        throw new Exception('Failed to get tour ID after insert');
                    }
                    
                    // Handle file uploads AFTER getting tour ID
                    $image_url = '';
                    $cover_image = '';
                    $gallery = [];
                    $images = [];
                    
                    if (isset($_FILES['main_image']) && $_FILES['main_image']['error'] === UPLOAD_ERR_OK) {
                        $image_url = uploadTourImage($_FILES['main_image'], $tour_id, 'main');
                        $images[] = $image_url;
                    }
                    
                    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
                        $cover_image = uploadTourImage($_FILES['cover_image'], $tour_id, 'cover');
                        $images[] = $cover_image;
                    }
                    
                    if (isset($_FILES['gallery_images'])) {
                        foreach ($_FILES['gallery_images']['tmp_name'] as $key => $tmp_name) {
                            if ($_FILES['gallery_images']['error'][$key] === UPLOAD_ERR_OK) {
                                $file = [
                                    'name' => $_FILES['gallery_images']['name'][$key],
                                    'type' => $_FILES['gallery_images']['type'][$key],
                                    'tmp_name' => $tmp_name,
                                    'size' => $_FILES['gallery_images']['size'][$key]
                                ];
                                $uploaded_image = uploadTourImage($file, $tour_id, 'gallery_' . $key);
                                $gallery[] = $uploaded_image;
                                $images[] = $uploaded_image;
                            }
                        }
                    }
                    
                    // Update tour with images
                    if ($image_url || $cover_image || !empty($gallery)) {
                        $stmt = $pdo->prepare("UPDATE tours SET image_url = ?, cover_image = ?, gallery = ?, images = ? WHERE id = ?");
                        $stmt->execute([$image_url, $cover_image, json_encode($gallery), json_encode($images), $tour_id]);
                    }
                    
                    header('Location: tours.php?added=1');
                    exit;
                } catch (Exception $e) {
                    error_log("Add tour error: " . $e->getMessage());
                    header('Location: tours.php?error=' . urlencode($e->getMessage()));
                    exit;
                }
                break;
        }
    }
}

// Rest of the file remains the same...
?>
