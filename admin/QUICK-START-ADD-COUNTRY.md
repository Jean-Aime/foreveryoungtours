# 🚀 Quick Start: Adding a New Country

## Step-by-Step Guide for Admins

---

## ✅ Prerequisites

- Admin access to the system
- Country information ready (name, code, currency, etc.)
- Country images prepared (optional, can add later)

---

## 📋 Step 1: Add Country via Admin Panel

### Option A: Enhanced Country Management (Recommended)

1. **Navigate to:**
   ```
   Admin Panel → Manage Countries
   OR
   http://localhost/foreveryoungtours/admin/enhanced-manage-countries.php
   ```

2. **Click "Add New Country" Button**

3. **Fill in the Form:**

   | Field | Example | Required |
   |-------|---------|----------|
   | **Continent** | Africa | ✅ Yes |
   | **Country Name** | Kenya | ✅ Yes |
   | **Slug** | visit-ke | ✅ Yes |
   | **Country Code** | KEN | ✅ Yes |
   | **Currency** | KES | ✅ Yes |
   | **Language** | English, Swahili | ✅ Yes |
   | **Description** | Brief description | ✅ Yes |
   | **Best Time to Visit** | June-October | ⚪ Optional |
   | **Image URL** | URL to country image | ⚪ Optional |
   | **Status** | Active | ✅ Yes |

4. **Click "Add Country"**

5. **System Automatically:**
   - ✅ Creates country in database
   - ✅ Clones Rwanda design/theme
   - ✅ Customizes for new country
   - ✅ Updates subdomain handler
   - ✅ Creates folder structure
   - ✅ Generates image directories

6. **Success Message:**
   ```
   "Country added successfully! Theme generated and subdomain configured."
   ```

---

## 🎨 Step 2: Verify Theme Generation

### Check Theme Status

1. **Go to Test Page:**
   ```
   http://localhost/foreveryoungtours/admin/test-rwanda-theme-cloning.php
   ```

2. **Find Your Country in the Table**

3. **Verify Status:**
   - ✅ Green badge: "Theme Ready" = Success!
   - ⚠️ Yellow badge: "No Theme" = Need to generate

4. **If No Theme:**
   - Click "Generate Theme" button
   - Wait for confirmation
   - Page will refresh automatically

---

## 🖼️ Step 3: Add Country Images (Optional)

### Upload Country-Specific Images

1. **Navigate to Country Folder:**
   ```
   countries/{country-name}/assets/images/
   ```
   Example: `countries/kenya/assets/images/`

2. **Upload These Images:**

   | Image | Filename | Size | Purpose |
   |-------|----------|------|---------|
   | Hero Image | `hero-kenya.jpg` | 1920x1080px+ | Main banner |
   | Social Media | `kenya-og.jpg` | 1200x630px | Facebook/Twitter |
   | Logo | `logo.png` | Variable | Country logo |

3. **Image Guidelines:**
   - Use JPG for photos
   - Use PNG for logos
   - Optimize for web (compress)
   - High quality, professional images

4. **Fallback:**
   - If no images uploaded, system uses Rwanda images
   - Site still works perfectly with fallback images

---

## 🌐 Step 4: Test the Country Site

### Access the Subdomain

1. **Subdomain Format:**
   ```
   visit-{code}.localhost/foreveryoungtours/
   ```

2. **Examples:**
   ```
   Kenya:     http://visit-ke.localhost/foreveryoungtours/
   Tanzania:  http://visit-tz.localhost/foreveryoungtours/
   Uganda:    http://visit-ug.localhost/foreveryoungtours/
   ```

3. **Test Checklist:**
   - [ ] Homepage loads correctly
   - [ ] Country name displays properly
   - [ ] Currency shows correctly
   - [ ] Tours display (if added)
   - [ ] Booking modal works
   - [ ] Navigation functions
   - [ ] Images display (or fallback works)
   - [ ] WhatsApp links work

---

## 🎯 Step 5: Add Tours (Optional)

### Add Country-Specific Tours

1. **Navigate to:**
   ```
   Admin Panel → Manage Tours
   OR
   http://localhost/foreveryoungtours/admin/tours.php
   ```

2. **Click "Add New Tour"**

3. **Select Your Country** from dropdown

4. **Fill in Tour Details:**
   - Tour name
   - Description
   - Price
   - Duration
   - Difficulty level
   - Images
   - Itinerary

5. **Click "Add Tour"**

6. **Tour Appears on Country Site Automatically**

---

## 🔧 Troubleshooting

### Theme Not Generated?

**Solution:**
1. Go to: `admin/test-rwanda-theme-cloning.php`
2. Find your country
3. Click "Generate Theme"
4. Wait for success message

### Subdomain Not Working?

**Solution:**
1. Check `.htaccess` file exists in root
2. Verify Apache mod_rewrite is enabled
3. Check subdomain-handler.php has country mapping
4. Restart Apache

### Images Not Showing?

**Solution:**
1. Check image filenames match convention
2. Verify images are in correct folder
3. Check file permissions (755 for folders, 644 for files)
4. Fallback images will show if country images missing

### Country Not in Dropdown?

**Solution:**
1. Verify country status is "Active"
2. Check database entry exists
3. Refresh admin page
4. Clear browser cache

---

## 📊 Quick Reference

### Country Code Format

| Country | Slug | Code | Folder Name |
|---------|------|------|-------------|
| Rwanda | visit-rw | RWA | rwanda |
| Kenya | visit-ke | KEN | kenya |
| Tanzania | visit-tz | TZA | tanzania |
| Uganda | visit-ug | UGA | uganda |
| South Africa | visit-za | ZAF | south-africa |
| Egypt | visit-eg | EGY | egypt |
| Morocco | visit-ma | MAR | morocco |

### Important URLs

| Purpose | URL |
|---------|-----|
| Add Country | `/admin/enhanced-manage-countries.php` |
| Test Themes | `/admin/test-rwanda-theme-cloning.php` |
| Batch Generate | `/admin/batch-theme-generator.php` |
| Manage Tours | `/admin/tours.php` |

---

## ✨ That's It!

**Your new country site is ready with the complete Rwanda design!**

The system automatically:
- ✅ Clones the professional Rwanda design
- ✅ Customizes all country-specific content
- ✅ Sets up subdomain routing
- ✅ Creates all necessary files and folders
- ✅ Configures database integration
- ✅ Enables booking functionality

**No coding required! Just add the country and the system does the rest!** 🎉

