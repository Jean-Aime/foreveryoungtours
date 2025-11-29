# CRITICAL FIX: All Tours Changing When Adding/Editing

## Problem
When you add or edit a tour, ALL existing tours change to the new data.

## Root Cause
The 'add' case is missing validation. When `tour_id` is not set (new tour), the code still tries to execute but without proper INSERT, causing data corruption.

## Solution

In `/admin/tours.php`, find the `case 'add':` block and add this validation at the very beginning:

```php
case 'add':
    // VALIDATE: Ensure tour_id is NOT set for new tours
    if (isset($_POST['tour_id']) && !empty($_POST['tour_id'])) {
        header('Location: tours.php?error=Invalid+request');
        exit;
    }
    
    try {
        // ... rest of add code ...
```

Also, in the `case 'edit':` block, add this validation:

```php
case 'edit':
    // VALIDATE: Ensure tour_id IS set for edits
    if (!isset($_POST['tour_id']) || empty($_POST['tour_id'])) {
        header('Location: tours.php?error=Tour+ID+required');
        exit;
    }
    
    $tour_id = intval($_POST['tour_id']);
    if ($tour_id <= 0) {
        header('Location: tours.php?error=Invalid+tour+ID');
        exit;
    }
    
    // ... rest of edit code ...
```

## Quick Test

1. Go to Admin > Tours
2. Click "Add New Tour"
3. Fill in form
4. Submit
5. Check if only ONE new tour is created (not all tours changed)

## Verification

After applying fix, verify:
- ✓ Adding new tour creates only 1 new tour
- ✓ Existing tours are NOT modified
- ✓ Editing a tour only updates that specific tour
- ✓ Images display correctly for new tours
