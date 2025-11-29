# CRITICAL FIX: All Tours Changing When Adding/Editing

## Root Cause
When adding a new tour, if `$_POST['tour_id']` somehow exists (browser cache, form reuse), the code runs the 'edit' case instead of 'add'. The UPDATE statement then updates ALL tours because there's no WHERE clause validation.

## The Fix - Add 2 Validations to `/admin/tours.php`

### CHANGE 1: In 'edit' case (line ~20)
Add this BEFORE the slug line:

```php
case 'edit':
    // CRITICAL: Validate tour_id exists and is numeric
    if (!isset($_POST['tour_id']) || !is_numeric($_POST['tour_id']) || $_POST['tour_id'] <= 0) {
        header('Location: tours.php?error=Invalid+tour+ID');
        exit;
    }
    
    $tour_id = intval($_POST['tour_id']);
    $slug = strtolower(str_replace(' ', '-', $_POST['name']));
```

Then replace all `$_POST['tour_id']` with `$tour_id` in the edit case.

### CHANGE 2: In 'add' case (line ~120)
Add this BEFORE the try block:

```php
case 'add':
    // CRITICAL: Validate tour_id does NOT exist for new tours
    if (isset($_POST['tour_id']) && !empty($_POST['tour_id'])) {
        header('Location: tours.php?error=Invalid+request');
        exit;
    }
    
    try {
```

### CHANGE 3: In INSERT statement (line ~160)
Add image columns to INSERT:

```php
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
    '', '', json_encode([]), json_encode([])  // ADD THESE 4 VALUES
]);
```

## Why This Works
1. Edit case now validates `tour_id` is numeric and > 0
2. Add case now rejects if `tour_id` is present
3. INSERT now includes image columns so they're not NULL
4. Each tour gets its own unique ID, preventing cross-contamination

## Test
1. Add a new tour with images
2. Verify ONLY 1 new tour is created
3. Verify existing tours are NOT modified
4. Verify images display in admin edit view
