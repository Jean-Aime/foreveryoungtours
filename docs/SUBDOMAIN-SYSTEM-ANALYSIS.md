# 🌐 Subdomain System - Complete Analysis

## 📋 **Overview**

The Forever Young Tours website uses a **multi-subdomain architecture** where each country has its own subdomain with a dedicated theme and content. The system automatically routes visitors to country-specific pages based on the subdomain they access.

---

## 🏗️ **Architecture Components**

### **1. Subdomain Format**
```
visit-{country_code}.{domain}
```

**Examples:**
- `visit-rw.localhost/foreveryoungtours/` - Rwanda (local)
- `visit-ke.foreveryoungtours.local` - Kenya (local with .local)
- `visit-tz.iforeveryoungtours.com` - Tanzania (production)

**Country Code Format:**
- **2-letter codes:** RW, KE, TZ, UG, ZA, etc.
- **Mapped to 3-letter ISO codes:** RWA, KEN, TZA, UGA, ZAF, etc.

---

## 🔧 **How It Works**

### **Step 1: DNS/Hosts Resolution**
User types: `visit-rw.localhost/foreveryoungtours/`

**Local Development:**
- Browser resolves via Windows hosts file OR
- Uses `.localhost` which works automatically in modern browsers

**Production:**
- DNS resolves to server IP
- Apache virtual host catches the subdomain

---

### **Step 2: Apache .htaccess Routing**

**File:** `.htaccess` (lines 8-11)

```apache
# Handle country subdomains (2 or 3 letter codes)
RewriteCond %{HTTP_HOST} ^visit-([a-z]{2,3})\.(localhost|iforeveryoungtours\.com)(:[0-9]+)?$ [NC]
RewriteCond %{REQUEST_URI} !^/subdomain-handler\.php$
RewriteRule ^.*$ subdomain-handler.php [L]
```

**What it does:**
1. **Detects subdomain pattern:** `visit-{code}.{domain}`
2. **Extracts country code:** RW, KE, TZ, etc.
3. **Routes ALL requests** to `subdomain-handler.php`
4. **Preserves query strings:** `?id=28` stays intact

---

### **Step 3: Subdomain Handler Processing**

**File:** `subdomain-handler.php`

#### **3.1 Extract Country Code (lines 11-37)**
```php
if (preg_match('/^visit-([a-z]{2,3})\./', $host, $matches)) {
    $extracted_code = strtoupper($matches[1]); // "RW"
    
    // Map 2-letter to 3-letter codes
    $code_mapping = [
        'RW' => 'RWA',  // Rwanda
        'KE' => 'KEN',  // Kenya
        'TZ' => 'TZA',  // Tanzania
        // ... 17 countries total
    ];
    
    $country_code = $code_mapping[$extracted_code] ?? $extracted_code;
}
```

#### **3.2 Database Lookup (lines 40-46)**
```php
$stmt = $pdo->prepare("SELECT * FROM countries WHERE country_code = ? AND status = 'active'");
$stmt->execute([$country_code]); // "RWA"
$country = $stmt->fetch();
```

**Database Structure:**
```
countries table:
- id: 1
- name: "Rwanda"
- slug: "visit-rw"
- country_code: "RWA"
- status: "active"
```

#### **3.3 Set Session & Constants (lines 48-59)**
```php
$_SESSION['subdomain_country_id'] = $country['id'];
$_SESSION['subdomain_country_code'] = $country_code;
$_SESSION['subdomain_country_name'] = $country['name'];
$_SESSION['subdomain_country_slug'] = $country['slug'];

define('COUNTRY_SUBDOMAIN', true);
define('CURRENT_COUNTRY_ID', $country['id']);
define('CURRENT_COUNTRY_CODE', $country_code);
define('CURRENT_COUNTRY_NAME', $country['name']);
```

**Purpose:** Makes country context available throughout the application

#### **3.4 Map Slug to Folder (lines 63-83)**
```php
$folder_mapping = [
    'visit-rw' => 'rwanda',
    'visit-ke' => 'kenya',
    'visit-tz' => 'tanzania',
    // ... all 17 countries
];

$folder_name = $folder_mapping[$country['slug']] ?? $country['slug'];
// Result: "rwanda"
```

#### **3.5 Route to Correct Page (lines 85-118)**

**For specific pages** (e.g., `/pages/tour-detail?id=28`):
```php
if (preg_match('/^\/pages\/(.+)$/', $path, $matches)) {
    $page_name = $matches[1]; // "tour-detail"
    
    // Try country-specific page first
    $country_page_file = "countries/{$folder_name}/pages/{$page_name}.php";
    if (file_exists($country_page_file)) {
        require_once $country_page_file;
        exit;
    }
    
    // Fallback to main pages
    $main_page_file = "pages/{$page_name}.php";
    if (file_exists($main_page_file)) {
        require_once $main_page_file;
        exit;
    }
}
```

**For homepage** (e.g., `/` or `/index.php`):
```php
$country_page = "countries/{$folder_name}/index.php";
if (file_exists($country_page)) {
    require_once $country_page;
} else {
    require_once 'index.php'; // Fallback
}
exit;
```

---

## 📁 **Directory Structure**

```
foreveryoungtours/
├── .htaccess                          # Routes subdomains
├── subdomain-handler.php              # Processes subdomain requests
├── config/
│   └── database.php                   # Database connection
├── countries/
│   ├── rwanda/                        # Master template
│   │   ├── index.php                  # Country homepage
│   │   ├── config.php                 # Country config
│   │   ├── continent-theme.php        # Africa theme
│   │   ├── assets/
│   │   │   ├── css/                   # Stylesheets
│   │   │   ├── images/                # Country images
│   │   │   └── js/                    # JavaScript
│   │   ├── includes/
│   │   │   ├── header.php             # Navigation
│   │   │   └── footer.php             # Footer
│   │   └── pages/
│   │       ├── packages.php           # Tour packages
│   │       ├── tour-detail.php        # Tour details
│   │       └── ...                    # Other pages
│   ├── kenya/                         # Cloned from Rwanda
│   ├── tanzania/                      # Cloned from Rwanda
│   └── ... (17 countries total)
└── includes/
    └── theme-generator.php            # Auto-clones Rwanda theme
```

---

## 🎨 **Theme Cloning System**

### **How New Countries Get Themes**

**File:** `includes/theme-generator.php`

**Function:** `generateCountryTheme($country_data)`

**Process:**
1. **Copy Rwanda structure** - All files and folders
2. **Customize content** - Replace "Rwanda" with new country name
3. **Update paths** - Fix image and asset paths
4. **Create image directory** - With README guide
5. **Verify integrity** - Ensure all required files exist

**Triggered by:**
- Adding country in `admin/enhanced-manage-countries.php`
- Manual regeneration via admin panel

---

## 🔄 **Request Flow Example**

### **User visits:** `http://visit-rw.localhost/foreveryoungtours/pages/tour-detail?id=28`

**Step-by-step:**

1. **Browser** → Resolves `visit-rw.localhost` to `127.0.0.1`

2. **Apache** → Receives request, checks `.htaccess`

3. **.htaccess** → Matches pattern `visit-rw`, routes to `subdomain-handler.php`

4. **subdomain-handler.php:**
   - Extracts: `RW` from hostname
   - Maps: `RW` → `RWA`
   - Queries database: `WHERE country_code = 'RWA'`
   - Gets: `{id: 1, name: 'Rwanda', slug: 'visit-rw'}`
   - Sets session: `$_SESSION['subdomain_country_id'] = 1`
   - Maps slug: `visit-rw` → `rwanda` folder
   - Parses URI: `/pages/tour-detail?id=28`
   - Checks: `countries/rwanda/pages/tour-detail.php` ✅ exists
   - Loads: `countries/rwanda/pages/tour-detail.php`

5. **tour-detail.php:**
   - Reads: `$_GET['id'] = 28`
   - Queries: `SELECT * FROM tours WHERE id = 28 AND country_id = 1`
   - Displays: Rwanda-specific tour #28

6. **Response** → Sent back to browser with Rwanda theme

---

## 🗄️ **Database Integration**

### **Countries Table**
```sql
CREATE TABLE countries (
    id INT PRIMARY KEY AUTO_INCREMENT,
    region_id INT,
    name VARCHAR(100),
    slug VARCHAR(100),              -- "visit-rw"
    country_code VARCHAR(3),        -- "RWA" (ISO 3166-1 alpha-3)
    description TEXT,
    image_url VARCHAR(255),
    currency VARCHAR(50),
    language VARCHAR(100),
    best_time_to_visit VARCHAR(100),
    status ENUM('active', 'inactive')
);
```

### **Current Countries (17 total)**
| ID | Name | Slug | Code | Folder |
|----|------|------|------|--------|
| 1 | Rwanda | visit-rw | RWA | rwanda |
| 2 | Kenya | visit-ke | KEN | kenya |
| 3 | Tanzania | visit-tz | TZA | tanzania |
| 4 | Uganda | visit-ug | UGA | uganda |
| 5 | South Africa | visit-za | ZAF | south-africa |
| ... | ... | ... | ... | ... |

---

## 🌍 **All Supported Subdomains**

```
http://visit-rw.localhost/foreveryoungtours/  - Rwanda
http://visit-ke.localhost/foreveryoungtours/  - Kenya
http://visit-tz.localhost/foreveryoungtours/  - Tanzania
http://visit-ug.localhost/foreveryoungtours/  - Uganda
http://visit-za.localhost/foreveryoungtours/  - South Africa
http://visit-eg.localhost/foreveryoungtours/  - Egypt
http://visit-ma.localhost/foreveryoungtours/  - Morocco
http://visit-bw.localhost/foreveryoungtours/  - Botswana
http://visit-na.localhost/foreveryoungtours/  - Namibia
http://visit-zw.localhost/foreveryoungtours/  - Zimbabwe
http://visit-gh.localhost/foreveryoungtours/  - Ghana
http://visit-ng.localhost/foreveryoungtours/  - Nigeria
http://visit-et.localhost/foreveryoungtours/  - Ethiopia
http://visit-sn.localhost/foreveryoungtours/  - Senegal
http://visit-tn.localhost/foreveryoungtours/  - Tunisia
http://visit-cm.localhost/foreveryoungtours/  - Cameroon
http://visit-cd.localhost/foreveryoungtours/  - DR Congo
```

---

## 🔑 **Key Features**

### **1. Automatic Country Context**
- All queries filtered by country
- Tours, destinations, content specific to country
- Session maintains country throughout visit

### **2. Fallback System**
- Country-specific page → Main page → 404
- Country images → Rwanda images → Generic images

### **3. Theme Inheritance**
- All countries inherit Rwanda's design
- Consistent look and feel
- Easy to update all countries by updating Rwanda

### **4. SEO Friendly**
- Each country has unique subdomain
- Separate meta tags and OG images
- Country-specific URLs

### **5. Scalable**
- Add new country = auto-generate theme
- Update subdomain handler automatically
- No manual file creation needed

---

## 📝 **Summary**

The subdomain system creates a **multi-tenant architecture** where:

✅ Each country has its own subdomain  
✅ Each subdomain has its own theme (cloned from Rwanda)  
✅ All content is automatically filtered by country  
✅ URLs are clean and SEO-friendly  
✅ System is scalable and maintainable  
✅ Fallbacks ensure nothing breaks  
✅ Admin can add countries with one click  

**The system is production-ready and fully functional!** 🚀

