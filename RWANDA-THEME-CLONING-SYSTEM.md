# 🎨 Rwanda Theme Cloning System

## Overview

The Forever Young Tours platform uses **Rwanda as the master design template**. When an admin adds a new country, the system automatically clones the complete Rwanda design (layout, styling, structure) and customizes it for the new country.

---

## 🏗️ Architecture

### Master Template: Rwanda
- **Location:** `countries/rwanda/`
- **Purpose:** Master design template for all country subdomains
- **Status:** Fully designed and production-ready

### Automatic Cloning Process

```
Admin Adds New Country
        ↓
System Clones Rwanda Design
        ↓
Customizes Country-Specific Data
        ↓
Updates Subdomain Handler
        ↓
New Country Site Ready!
```

---

## 📁 What Gets Cloned

### 1. **Complete File Structure**
```
countries/{new-country}/
├── index.php                    # Main landing page
├── config.php                   # Configuration
├── continent-theme.php          # Continent inheritance
├── assets/
│   ├── css/                     # All stylesheets
│   ├── images/                  # Image assets
│   └── js/                      # JavaScript files
├── includes/
│   ├── header.php               # Navigation header
│   └── footer.php               # Footer template
└── pages/
    ├── packages.php             # Tour packages page
    ├── tour-detail.php          # Tour detail page
    ├── enhanced-booking-modal.php
    ├── inquiry-modal.php
    └── config.php
```

### 2. **Design Elements**
- ✅ Complete HTML structure
- ✅ All CSS styling (Tailwind + custom)
- ✅ JavaScript functionality
- ✅ Responsive design
- ✅ Hero sections
- ✅ Tour cards layout
- ✅ Booking modals
- ✅ Navigation menus
- ✅ Footer design

### 3. **Functional Components**
- ✅ Database integration
- ✅ Tour filtering
- ✅ Booking system
- ✅ Inquiry forms
- ✅ WhatsApp integration
- ✅ Image handling
- ✅ SEO meta tags
- ✅ Social media integration

---

## 🔧 How to Use

### Method 1: Automatic (When Adding Country)

1. **Go to Admin Panel**
   ```
   http://localhost/foreveryoungtours/admin/enhanced-manage-countries.php
   ```

2. **Click "Add New Country"**

3. **Fill in Country Details:**
   - Name (e.g., "Kenya")
   - Slug (e.g., "visit-ke")
   - Country Code (e.g., "KEN")
   - Currency (e.g., "KES")
   - Description
   - Other details

4. **Click "Add Country"**

5. **System Automatically:**
   - ✅ Creates country in database
   - ✅ Clones Rwanda design
   - ✅ Customizes for new country
   - ✅ Updates subdomain handler
   - ✅ Creates image directories
   - ✅ Generates README files

### Method 2: Manual Regeneration

1. **Go to Test Page**
   ```
   http://localhost/foreveryoungtours/admin/test-rwanda-theme-cloning.php
   ```

2. **Find Country Without Theme**

3. **Click "Generate Theme" Button**

4. **System Clones Rwanda Design**

### Method 3: Batch Generation

1. **Go to Batch Generator**
   ```
   http://localhost/foreveryoungtours/admin/batch-theme-generator.php
   ```

2. **Click "Generate All Themes"**

3. **System Generates Themes for All Countries**

---

## 🎯 Customization Process

### Automatic Replacements

The system automatically replaces Rwanda-specific content:

| Rwanda Content | Replaced With |
|----------------|---------------|
| "Rwanda" | New country name |
| "visit-rw" | New country slug |
| "RWA" | New country code |
| "Kigali" | New country capital |
| "RWF" | New country currency |
| Rwanda descriptions | Country-specific descriptions |
| Rwanda images | Country-specific images (with fallback) |

### Country-Specific Data

The system includes pre-configured data for major countries:
- Kenya (KEN)
- Tanzania (TZA)
- Uganda (UGA)
- South Africa (ZAF)
- Egypt (EGY)
- Morocco (MAR)
- And more...

For new countries not in the list, it uses intelligent defaults.

---

## 🖼️ Image Management

### Required Images

Each country should have these images in `countries/{country}/assets/images/`:

1. **hero-{country}.jpg** - Main hero image
2. **{country}-og.jpg** - Social media image (1200x630px)
3. **logo.png** - Country logo (optional)

### Fallback System

If country-specific images don't exist:
1. Uses Rwanda images as fallback
2. Uses generic Africa images
3. Uses default placeholder images

### Adding Images

Simply upload images to the country's `assets/images/` directory with the correct naming convention.

---

## 🌐 Subdomain Configuration

### Automatic Subdomain Setup

When a country is added, the system automatically:

1. **Updates subdomain-handler.php**
   - Adds country code mapping
   - Adds folder mapping

2. **Creates Subdomain URL**
   ```
   Format: visit-{code}.iforeveryoungtours.com
   Example: visit-ke.iforeveryoungtours.com (Kenya)
   ```

3. **Configures Routing**
   - Maps subdomain to country folder
   - Handles all requests correctly

---

## ✅ Verification

### Check Theme Status

Visit the test page to see all countries and their theme status:
```
http://localhost/foreveryoungtours/admin/test-rwanda-theme-cloning.php
```

### Test Country Site

After theme generation, test the country site:
```
http://visit-{code}.localhost/foreveryoungtours/
```

Example:
```
http://visit-ke.localhost/foreveryoungtours/  (Kenya)
http://visit-tz.localhost/foreveryoungtours/  (Tanzania)
```

---

## 🔍 Technical Details

### Theme Generator Functions

Located in: `includes/theme-generator.php`

**Key Functions:**
- `generateCountryTheme()` - Main generation function
- `copyRwandaThemeStructure()` - Copies all files
- `customizeCountryTheme()` - Customizes content
- `verifyThemeIntegrity()` - Verifies completeness
- `updateSubdomainHandler()` - Updates routing

### Database Integration

The system reads country data from the `countries` table and automatically generates themes based on that data.

---

## 🎉 Benefits

1. **Consistency** - All country sites have the same professional design
2. **Speed** - New country sites ready in seconds
3. **Maintainability** - Update Rwanda, regenerate all countries
4. **Scalability** - Easy to add unlimited countries
5. **Quality** - Rwanda's proven design for all countries

---

## 📝 Maintenance

### Updating All Country Themes

If you update the Rwanda design and want to apply changes to all countries:

1. Make changes to `countries/rwanda/`
2. Go to batch generator
3. Click "Regenerate All Themes"
4. All country sites updated with new design

### Adding New Features

1. Add feature to Rwanda theme
2. Test thoroughly
3. Regenerate themes for other countries
4. New feature available everywhere

---

## 🚀 Next Steps

1. ✅ Rwanda theme is complete and ready
2. ✅ Theme cloning system is active
3. ✅ Add new countries via admin panel
4. ✅ System automatically clones Rwanda design
5. ✅ Upload country-specific images
6. ✅ Test subdomain access
7. ✅ Launch country site!

---

**The Rwanda theme cloning system ensures every country gets the same high-quality, professional design automatically!** 🎨✨

