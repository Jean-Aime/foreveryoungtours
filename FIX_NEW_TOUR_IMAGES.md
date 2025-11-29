# Fix for New Tour Images Not Displaying

## Problem
When adding a NEW tour with images, you get "AN UNEXPECTED ERROR OCCUR" and images don't display in admin edit view.

## Root Cause
The INSERT statement in the 'add' case doesn't include the image columns (`image_url`, `cover_image`, `gallery`, `images`), so they remain NULL even after images are uploaded.

## Solution

### Step 1: Check Database Columns
Run this to verify your tours table has image columns:
```
http://localhost/foreveryoungtours/check_tour_columns.php
```

If columns are missing, add them:
```sql
ALTER TABLE tours ADD COLUMN image_url VARCHAR(255) NULL;
ALTER TABLE tours ADD COLUMN cover_image VARCHAR(255) NULL;
ALTER TABLE tours ADD COLUMN gallery LONGTEXT NULL;
ALTER TABLE tours ADD COLUMN images LONGTEXT NULL;
```

### Step 2: Fix tours.php - Add Case

In `/admin/tours.php`, find the `case 'add':` block (around line 150).

**CHANGE THIS LINE:**
```php
$stmt = $pdo->prepare("INSERT INTO tours (name, slug, description, detailed_description, destination, destination_country, country_id, category_id, price, base_price, duration, duration_days, max_participants, min_participants, requirements, difficulty_level, best_time_to_visit, status, featured, itinerary, inclusions, exclusions, highlights, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)");
```

**TO THIS:**
```php
$stmt = $pdo->prepare("INSERT INTO tours (name, slug, description, detailed_description, destination, destination_country, country_id, category_id, price, base_price, duration, duration_days, max_participants, min_participants, requirements, difficulty_level, best_time_to_visit, status, featured, itinerary, inclusions, exclusions, highlights, image_url, cover_image, gallery, images, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)");
```

**AND ADD THESE VALUES TO THE EXECUTE ARRAY:**
```php
$result = $stmt->execute([
    $_POST['name'], 
    $slug, 
    $_POST['description'], 
    $_POST['detailed_description'] ?? '',
    $_POST['destination'], 
    $country['name'], 
    $_POST['country_id'], 
    $_POST['category_id'], 
    $_POST['price'], 
    $_POST['price'], 
    $_POST['duration_days'] . ' days', 
    $_POST['duration_days'], 
    $_POST['max_participants'] ?? 20,  // CHANGED: Added default value
    $_POST['min_participants'] ?? 2,
    $_POST['requirements'] ?? '',
    $_POST['difficulty_level'] ?? 'moderate',
    $_POST['best_time_to_visit'] ?? '',
    $_POST['status'] ?? 'active',
    isset($_POST['featured']) ? 1 : 0,
    json_encode($itinerary),
    json_encode($inclusions),
    json_encode($exclusions),
    json_encode($highlights),
    '', // image_url - ADD THIS
    '', // cover_image - ADD THIS
    json_encode([]), // gallery - ADD THIS
    json_encode([])  // images - ADD THIS
]);
```

### Step 3: Fix Error Display

Find this section (around line 150):
```php
<?php if (isset($_GET['error'])): ?>
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
    <i class="fas fa-exclamation-triangle mr-2"></i>
    <?php 
    switch($_GET['error']) {
        case 'deactivate_failed':
            echo 'Failed to deactivate tour. Tour may not exist.';
            break;
        // ... more cases ...
        default:
            echo 'An unexpected error occurred.';
    }
    ?>
</div>
<?php endif; ?>
```

**REPLACE WITH:**
```php
<?php if (isset($_GET['error'])): ?>
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
    <i class="fas fa-exclamation-triangle mr-2"></i>
    <strong>Error:</strong> <?php echo htmlspecialchars($_GET['error']); ?>
</div>
<?php endif; ?>
```

## Testing

1. Go to Admin > Tours Management
2. Click "Add New Tour"
3. Fill in all required fields
4. Upload images (main, cover, gallery)
5. Click "Add Tour"
6. You should see success message
7. Edit the tour - images should now display

## Verification Script

Run this to test:
```
http://localhost/foreveryoungtours/admin/debug_add_tour.php
```

Fill in the form and submit to see detailed debug output.
