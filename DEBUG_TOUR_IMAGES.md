# Tour Images - Debugging Guide

## Current Code Flow (VERIFIED CORRECT)

### Adding a Tour with Images
1. **Insert tour** (without images) → Get tour ID
2. **Upload images** using tour ID → Returns paths like `uploads/tours/123_main_1234567890_5678.jpg`
3. **Update tour** with image paths in database

### Expected Database State After Adding Tour
```
image_url: "uploads/tours/123_main_1234567890_5678.jpg"
cover_image: "uploads/tours/123_cover_1234567890_5679.jpg"
gallery: ["uploads/tours/123_gallery_0_1234567890_5680.jpg", ...]
images: ["uploads/tours/123_main_1234567890_5678.jpg", "uploads/tours/123_cover_1234567890_5679.jpg", ...]
```

## Debugging Steps

### Step 1: Check if Images Are Being Uploaded to Disk
1. Go to `/uploads/tours/` directory
2. Look for files matching pattern: `{tour_id}_main_*.jpg`, `{tour_id}_cover_*.jpg`, etc.
3. If files exist → Upload is working
4. If files don't exist → Issue is in `uploadTourImage()` function

### Step 2: Check Database Values
Run this SQL query:
```sql
SELECT id, name, image_url, cover_image, gallery, images FROM tours ORDER BY id DESC LIMIT 5;
```

Expected output:
- `image_url` should NOT be NULL or empty
- `cover_image` should NOT be NULL or empty
- `gallery` should be JSON array (even if empty: `[]`)
- `images` should be JSON array with paths

If all are NULL/empty → Images are not being saved to database

### Step 3: Check Image Display in Admin
1. Go to Tours management page
2. Look at tour list table - check if thumbnail images show
3. Click Edit on a tour
4. Check "Current Images" section - should show uploaded images

If images don't show:
- Images not in database (Step 2 issue)
- Images not on disk (Step 1 issue)
- Path conversion issue in `getImageUrl()`

### Step 4: Check Frontend Display
1. Go to tour detail page (public site)
2. Check if hero image shows
3. Check if gallery images show
4. Open browser console (F12) → Network tab
5. Look for failed image requests (404 errors)

If images fail to load:
- Check the actual URL being requested
- Verify file exists at that path
- Check `getImageUrl()` function in config.php

## Common Issues & Solutions

### Issue: Images upload but don't show in admin edit form
**Cause**: Image paths stored in database but form not displaying them
**Solution**: Check if `$edit_tour['image_url']` is being populated correctly

### Issue: Images show in admin but not on frontend
**Cause**: `getImageUrl()` not converting paths correctly
**Solution**: Check BASE_URL constant and path conversion logic

### Issue: Images don't upload at all
**Cause**: File upload error in `uploadTourImage()`
**Solution**: Check error logs and file permissions on `/uploads/tours/` directory

### Issue: Only some images upload
**Cause**: File size or type validation failing
**Solution**: Check file size (max 5MB) and type (JPG, PNG, GIF, WebP only)

## Key Files to Check

1. **tours.php** (lines 127-237)
   - ADD case: Inserts tour, uploads images, updates with paths
   - EDIT case: Updates tour including image paths

2. **upload_handler.php**
   - `uploadTourImage()`: Validates and uploads files
   - Returns path relative to web root: `uploads/tours/filename.jpg`

3. **config.php**
   - `getImageUrl()`: Converts relative paths to absolute URLs

4. **tour-detail.php** (lines 50-70)
   - Collects images from: image_url, cover_image, gallery, images columns
   - Displays in gallery section

## Database Columns Required

All these columns must exist in `tours` table:
- `image_url` (VARCHAR, nullable)
- `cover_image` (VARCHAR, nullable)
- `gallery` (JSON or TEXT, nullable)
- `images` (JSON or TEXT, nullable)

Run this to check:
```sql
DESCRIBE tours;
```

Look for these columns in the output.

## Testing Checklist

- [ ] Add new tour with main image only
- [ ] Check if image file exists in `/uploads/tours/`
- [ ] Check if `image_url` is populated in database
- [ ] Check if image shows in admin tour list
- [ ] Check if image shows in admin edit form
- [ ] Check if image shows on frontend tour detail page
- [ ] Add tour with cover image
- [ ] Add tour with gallery images
- [ ] Edit existing tour and change images
- [ ] Verify old images are deleted when replaced
