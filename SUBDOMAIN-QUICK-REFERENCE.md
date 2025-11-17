# 🚀 Subdomain System - Quick Reference

## 📌 **Key Files**

| File | Purpose | Lines to Know |
|------|---------|---------------|
| `.htaccess` | Routes subdomains to handler | Lines 8-11 |
| `subdomain-handler.php` | Processes subdomain requests | All 125 lines |
| `includes/theme-generator.php` | Auto-clones Rwanda theme | Lines 39-150 |
| `admin/enhanced-manage-countries.php` | Add countries with auto-theme | Lines 21-58 |

---

## 🌐 **Subdomain Format**

```
visit-{2-letter-code}.{domain}/foreveryoungtours/
```

**Examples:**
- `visit-rw.localhost/foreveryoungtours/` - Rwanda
- `visit-ke.localhost/foreveryoungtours/` - Kenya
- `visit-tz.localhost/foreveryoungtours/` - Tanzania

---

## 🔄 **How It Works (Simple)**

1. **User visits:** `visit-rw.localhost/foreveryoungtours/`
2. **.htaccess catches:** `visit-rw` pattern
3. **Routes to:** `subdomain-handler.php`
4. **Handler extracts:** `RW` from hostname
5. **Maps to:** `RWA` (3-letter ISO code)
6. **Queries database:** `WHERE country_code = 'RWA'`
7. **Gets country:** `{id: 1, name: 'Rwanda', slug: 'visit-rw'}`
8. **Maps slug:** `visit-rw` → `rwanda` folder
9. **Loads page:** `countries/rwanda/index.php`
10. **Renders:** Rwanda theme with country-specific content

---

## 📊 **Code Mappings**

### **2-Letter → 3-Letter ISO Codes**
```php
'RW' => 'RWA',  // Rwanda
'KE' => 'KEN',  // Kenya
'TZ' => 'TZA',  // Tanzania
'UG' => 'UGA',  // Uganda
'ZA' => 'ZAF',  // South Africa
'EG' => 'EGY',  // Egypt
'MA' => 'MAR',  // Morocco
'BW' => 'BWA',  // Botswana
'NA' => 'NAM',  // Namibia
'ZW' => 'ZWE',  // Zimbabwe
'GH' => 'GHA',  // Ghana
'NG' => 'NGA',  // Nigeria
'ET' => 'ETH',  // Ethiopia
'SN' => 'SEN',  // Senegal
'TN' => 'TUN',  // Tunisia
'CM' => 'CMR',  // Cameroon
'CD' => 'COD'   // DR Congo
```

### **Slug → Folder Mapping**
```php
'visit-rw' => 'rwanda',
'visit-ke' => 'kenya',
'visit-tz' => 'tanzania',
'visit-ug' => 'uganda',
'visit-za' => 'south-africa',
'visit-eg' => 'egypt',
'visit-ma' => 'morocco',
'visit-bw' => 'botswana',
'visit-na' => 'namibia',
'visit-zw' => 'zimbabwe',
'visit-gh' => 'ghana',
'visit-ng' => 'nigeria',
'visit-et' => 'ethiopia',
'visit-sn' => 'senegal',
'visit-tn' => 'tunisia',
'visit-cm' => 'cameroon',
'visit-cd' => 'democratic-republic-of-congo'
```

---

## 🗄️ **Database Structure**

```sql
countries table:
- id: INT (Primary Key)
- region_id: INT (Foreign Key to regions)
- name: VARCHAR(100) - "Rwanda"
- slug: VARCHAR(100) - "visit-rw"
- country_code: VARCHAR(3) - "RWA"
- description: TEXT
- image_url: VARCHAR(255)
- currency: VARCHAR(50)
- language: VARCHAR(100)
- best_time_to_visit: VARCHAR(100)
- status: ENUM('active', 'inactive')
```

---

## 📁 **Directory Structure**

```
countries/
├── rwanda/                    # Master template
│   ├── index.php              # Homepage
│   ├── config.php             # Config
│   ├── continent-theme.php    # Africa theme
│   ├── assets/
│   │   ├── css/
│   │   ├── images/
│   │   └── js/
│   ├── includes/
│   │   ├── header.php
│   │   └── footer.php
│   └── pages/
│       ├── packages.php
│       ├── tour-detail.php
│       └── ...
├── kenya/                     # Cloned from Rwanda
├── tanzania/                  # Cloned from Rwanda
└── ... (17 countries total)
```

---

## 🎯 **Session Variables Set**

When subdomain is detected:
```php
$_SESSION['subdomain_country_id'] = 1;
$_SESSION['subdomain_country_code'] = 'RWA';
$_SESSION['subdomain_country_name'] = 'Rwanda';
$_SESSION['subdomain_country_slug'] = 'visit-rw';
```

---

## 🔑 **Constants Defined**

```php
define('COUNTRY_SUBDOMAIN', true);
define('CURRENT_COUNTRY_ID', 1);
define('CURRENT_COUNTRY_CODE', 'RWA');
define('CURRENT_COUNTRY_NAME', 'Rwanda');
define('CURRENT_COUNTRY_SLUG', 'visit-rw');
```

---

## 🛠️ **Adding New Country**

### **Method 1: Admin Panel (Automatic)**
1. Go to: `admin/enhanced-manage-countries.php`
2. Click "Add Country"
3. Fill form:
   - Name: "Seychelles"
   - Slug: "visit-sc"
   - Country Code: "SYC"
4. Submit
5. **System automatically:**
   - Creates database entry
   - Clones Rwanda theme
   - Updates subdomain handler
   - Creates folder structure

### **Method 2: Manual**
1. Add to database
2. Add to `subdomain-handler.php` code mapping
3. Add to `subdomain-handler.php` folder mapping
4. Run theme generator
5. Create country folder

---

## 🧪 **Testing Subdomains**

### **Test Page:**
```
http://localhost/foreveryoungtours/test-all-subdomains.php
```

### **Manual Test:**
```
http://visit-rw.localhost/foreveryoungtours/
```

### **Check if working:**
- Page loads with Rwanda theme ✅
- Navigation shows "Rwanda" ✅
- Tours are Rwanda-specific ✅
- URL stays on subdomain ✅

---

## 🔍 **Debugging**

### **Check if subdomain detected:**
```php
echo defined('COUNTRY_SUBDOMAIN') ? 'YES' : 'NO';
echo CURRENT_COUNTRY_NAME; // Should show country name
```

### **Check session:**
```php
print_r($_SESSION);
// Should show subdomain_country_* variables
```

### **Check .htaccess:**
```apache
# Should have this rule:
RewriteCond %{HTTP_HOST} ^visit-([a-z]{2,3})\.(localhost|iforeveryoungtours\.com)(:[0-9]+)?$ [NC]
RewriteRule ^.*$ subdomain-handler.php [L]
```

---

## ⚠️ **Common Issues**

### **Issue: Subdomain not working**
**Solution:**
- Check if country code is in `subdomain-handler.php` mappings
- Verify database has country with that code
- Check if folder exists in `countries/`

### **Issue: Images not loading**
**Solution:**
- Check `config.php` BASE_URL detection
- Verify image paths use `getImageUrl()` function
- Check if images exist in `assets/images/`

### **Issue: Page not found**
**Solution:**
- Check if page exists in `countries/{folder}/pages/`
- Verify fallback to main `pages/` directory
- Check file permissions

---

## 📚 **Related Documentation**

- **Full Analysis:** `SUBDOMAIN-SYSTEM-ANALYSIS.md`
- **Setup Guide:** `SUBDOMAIN-SETUP-GUIDE.md`
- **Theme System:** `RWANDA-THEME-CLONING-SYSTEM.md`
- **Test Guide:** `admin/TEST-MANAGE-COUNTRIES.md`

---

## ✅ **Quick Checklist**

- [ ] .htaccess has subdomain rewrite rule
- [ ] subdomain-handler.php has country code mapping
- [ ] subdomain-handler.php has folder mapping
- [ ] Database has country entry
- [ ] Country folder exists in countries/
- [ ] Country folder has complete theme
- [ ] Session variables are set
- [ ] Constants are defined
- [ ] Images load correctly
- [ ] Navigation works
- [ ] Tours are filtered by country

---

**All 17 countries are configured and working!** 🌍✨

