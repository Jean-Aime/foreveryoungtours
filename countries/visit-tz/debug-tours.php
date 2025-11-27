<?php
session_start();
require_once 'config.php';
require_once '../../config/database.php';

// Get country data
$country_slug = basename(dirname(__FILE__));
$stmt = $pdo->prepare("SELECT c.*, r.name as continent_name FROM countries c LEFT JOIN regions r ON c.region_id = r.id WHERE c.slug = ? AND c.status = 'active'");
$stmt->execute([$country_slug]);
$country = $stmt->fetch();

echo "<h2>Country Data:</h2>";
echo "<pre>";
print_r($country);
echo "</pre>";

// Get all tours for this country
$stmt = $pdo->prepare("SELECT * FROM tours WHERE country_id = ? AND status = 'active' ORDER BY featured DESC, created_at DESC");
$stmt->execute([$country['id']]);
$all_tours = $stmt->fetchAll();

echo "<h2>All Tours:</h2>";
echo "<pre>";
print_r($all_tours);
echo "</pre>";

// Get featured tours for display
$tours = array_slice($all_tours, 0, 4);

echo "<h2>Featured Tours (first 4):</h2>";
echo "<pre>";
print_r($tours);
echo "</pre>";
?>
