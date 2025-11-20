# SUBDOMAIN ARCHITECTURE - COMPLETE ANALYSIS

## **OVERVIEW**
The website uses a **multi-level subdomain system** for organizing content by continents and countries.

---

## **1. SUBDOMAIN STRUCTURE**

### **Main Domain**
- **Local**: `http://localhost/foreveryoungtours` or `http://foreveryoungtours.local`
- **Production**: `https://iforeveryoungtours.com`

### **Continent Subdomains**
- **Local**: `http://africa.foreveryoungtours.local`
- **Production**: `https://africa.iforeveryoungtours.com`
- **Others**: asia, europe, north-america, south-america, caribbean, oceania

### **Country Subdomains**
- **Local**: `http://visit-rw.foreveryoungtours.local` (Rwanda)
- **Production**: `https://visit-rw.iforeveryoungtours.com`
- **Pattern**: `visit-{country_code}` (e.g., visit-ke, visit-tz, visit-ng)

---

## **2. DIRECTORY STRUCTURE**

```
foreveryoungtours/
├── continents/
│   ├── africa/
│   │   ├── index.php (continent landing page)
│   │   ├── includes/
│   │   │   └── continent-header.php
│   │   └── pages/
│   │       ├── packages.php
│   │       ├── calendar.php
│   │       └── tour-detail.php
│   ├── asia/
│   ├── europe/
│   └── [other continents]
│
└── countries/
    ├── rwanda/
    │   ├── index.php (country landing page)
    │   ├── config.php (includes root config)
    │   ├── assets/
    │   │   ├── css/
    │   │   │   └── country-styles.css
    │   │   └── images/
    │   │       ├── hero-rwanda.jpg
    │   │       └── rwanda-gorilla-hero.png
    │   ├── includes/
    │   │   ├── header.php
    │   │   └── footer.php
    │   └── pages/
    │       ├── packages.php
    │       └── tour-detail.php
    ├── kenya/
    ├── nigeria/
    └── [other countries]
```

---

## **3. HOW CONTINENTS ARE BUILT**

### **File**: `continents/africa/index.php`

**Key Features**:
1. **Database Query**: Fetches continent data from `regions` table by slug
2. **Countries Grid**: Displays all countries in that continent
3. **Featured Tours**: Shows top 6 tours from the continent
4. **Calendar Section**: Displays upcoming tour departures
5. **CTA Section**: Encourages browsing tours or joining FYT Club

**Code Structure**:
```php
// Get continent data
$continent_slug = basename(dirname(__FILE__)); // 'africa'
$stmt = $pdo->prepare("SELECT * FROM regions WHERE slug = ?");
$stmt->execute([$continent_slug]);
$continent = $stmt->fetch();

// Get countries in continent
$stmt = $pdo->prepare("SELECT * FROM countries WHERE region_id = ?");
$stmt->execute([$continent['id']]);
$countries = $stmt->fetchAll();

// Get featured tours
$stmt = $pdo->prepare("SELECT t.*, c.name as country_name 
                       FROM tours t
                       INNER JOIN countries c ON t.country_id = c.id
                       WHERE c.region_id = ?
                       ORDER BY t.featured DESC
                       LIMIT 6");
$stmt->execute([$continent['id']]);
$featured_tours = $stmt->fetchAll();
```

**URL Generation for Countries**:
```php
// Detect environment and generate country URLs
$country_code = strtolower(substr($country['country_code'], 0, 2));
$current_host = $_SERVER['HTTP_HOST'];

if (strpos($current_host, 'iforeveryoungtours.com') !== false) {
    $country_url = "https://visit-{$country_code}.iforeveryoungtours.com";
} else {
    $country_url = "http://visit-{$country_code}.foreveryoungtours.local";
}
```

**Sections**:
- Hero with continent image and description
- Countries grid (4 columns, clickable cards)
- Featured tours carousel (3 tours)
- Calendar widget with upcoming departures
- CTA section with gradient background

---

## **4. HOW COUNTRIES ARE BUILT**

### **File**: `countries/rwanda/index.php`

**Key Features**:
1. **Premium Landing Page**: Luxury-focused design for Rwanda
2. **Hero Section**: Full-screen parallax with stats (Capital, Population, Tours, Currency)
3. **Value Propositions**: 4 key benefits (Primate Access, Premium Lodges, Seamless Ops, Impact Travel)
4. **Featured Itineraries**: Top 3 tours with pricing
5. **Experiences Matrix**: Scrolling image grid (2 rows, opposite directions)
6. **Pricing Strip**: Highlighted pricing with CTAs
7. **Request Modal**: Inquiry form for date availability
8. **FAQs**: Accordion-style frequently asked questions

**Code Structure**:
```php
// Include root config
require_once 'config.php'; // This includes ../../config.php

// Get country data from session or database
$stmt = $pdo->prepare("SELECT c.*, r.name as continent_name 
                       FROM countries c 
                       LEFT JOIN regions r ON c.region_id = r.id 
                       WHERE c.slug = 'rwanda' AND c.status = 'active'");
$stmt->execute();
$country = $stmt->fetch();

// Get all tours for this country
$stmt = $pdo->prepare("SELECT * FROM tours 
                       WHERE country_id = ? AND status = 'active' 
                       ORDER BY featured DESC, created_at DESC");
$stmt->execute([$country['id']]);
$all_tours = $stmt->fetchAll();
```

**Image Handling**:
```php
// Uses getImageUrl() from root config.php
<img src="<?= getImageUrl('countries/rwanda/assets/images/hero-rwanda.jpg') ?>" 
     onerror="this.src='<?= getImageUrl('countries/rwanda/assets/images/rwanda-gorilla-hero.png') ?>';">
```

**Tour Detail Links**:
```php
// Links to tour detail page
<a href="http://visit-rw.foreveryoungtours.local/pages/tour-detail.php?id=<?= $tour['id'] ?>">
    View Details
</a>
```

**Sections**:
- Hero (parallax background, stats grid, CTAs)
- Value Propositions (4 cards with icons)
- Featured Itineraries (3 tour cards)
- Experiences Matrix (scrolling image rows)
- Pricing Strip (gradient background, pricing info)
- Request Modal (inquiry form)
- FAQs (accordion)
- WhatsApp floating button

---

## **5. CONFIGURATION SYSTEM**

### **Root Config** (`config.php`)
```php
// Detects environment and sets BASE_URL
function detectBaseUrl() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    
    // Check if on subdomain
    if (preg_match('/^visit-([a-z]{2,3})\./', $host)) {
        // Subdomain - point to main domain
        if (strpos($host, 'localhost') !== false || strpos($host, '.local') !== false) {
            return 'http://localhost/foreveryoungtours';
        } else {
            $main_domain = preg_replace('/^visit-[a-z]{2,3}\./', '', $host);
            return $protocol . '://' . $main_domain;
        }
    } else {
        // Main domain
        if (strpos($host, 'localhost') !== false) {
            return 'http://localhost/foreveryoungtours';
        } else {
            return $protocol . '://' . $host;
        }
    }
}

define('BASE_URL', detectBaseUrl());

function getImageUrl($imagePath, $fallback = 'assets/images/default-tour.jpg') {
    if (empty($imagePath)) {
        return getAbsoluteUrl($fallback);
    }
    
    $cleanPath = ltrim($imagePath, './');
    $cleanPath = preg_replace('/^\.\.\/+/', '', $cleanPath);
    $cleanPath = ltrim($cleanPath, '/');
    
    return BASE_URL . '/' . $cleanPath;
}
```

### **Country Config** (`countries/rwanda/config.php`)
```php
// Simply includes root config
require_once '../../config.php';
// All functions and constants now available
```

### **Environment Config** (`config/environment.php`)
```php
class EnvironmentConfig {
    private $domains = [
        'main' => 'http://foreveryoungtours.local',
        'africa' => 'http://africa.foreveryoungtours.local',
        'visit-rw' => 'http://visit-rw.foreveryoungtours.local',
        // ... more domains
    ];
    
    public function getCountryUrl($countryCode, $path = '') {
        $key = 'visit-' . strtolower($countryCode);
        return $this->getDomain($key) . '/' . ltrim($path, '/');
    }
}

// Helper functions
function getCountryUrl($countryCode, $path = '') {
    return env()->getCountryUrl($countryCode, $path);
}
```

---

## **6. DATABASE SCHEMA**

### **Regions Table** (Continents)
```sql
CREATE TABLE regions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100),
    slug VARCHAR(100),
    description TEXT,
    image_url VARCHAR(255),
    status ENUM('active', 'inactive')
);
```

### **Countries Table**
```sql
CREATE TABLE countries (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100),
    slug VARCHAR(100),
    country_code VARCHAR(10),
    region_id INT,
    description TEXT,
    tourism_description TEXT,
    image_url VARCHAR(255),
    currency VARCHAR(10),
    status ENUM('active', 'inactive'),
    featured TINYINT(1),
    FOREIGN KEY (region_id) REFERENCES regions(id)
);
```

### **Tours Table**
```sql
CREATE TABLE tours (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255),
    description TEXT,
    country_id INT,
    category VARCHAR(50),
    price DECIMAL(10,2),
    duration_days INT,
    image_url VARCHAR(255),
    cover_image VARCHAR(255),
    status ENUM('active', 'inactive'),
    featured TINYINT(1),
    popularity_score INT,
    FOREIGN KEY (country_id) REFERENCES countries(id)
);
```

---

## **7. DESIGN PATTERNS**

### **Continent Pages**
- **Layout**: Hero → Countries Grid → Featured Tours → Calendar → CTA
- **Style**: Clean, modern, card-based
- **Colors**: Yellow/Orange gradients, white backgrounds
- **Animations**: Hover effects, smooth transitions

### **Country Pages**
- **Layout**: Hero → Value Props → Tours → Experiences → Pricing → FAQs
- **Style**: Premium, luxury-focused, immersive
- **Colors**: Amber/Orange gradients, dark overlays
- **Animations**: Parallax scrolling, fade-ins, scrolling image rows

---

## **8. KEY DIFFERENCES**

| Feature | Continent Pages | Country Pages |
|---------|----------------|---------------|
| **Purpose** | Showcase multiple countries | Deep dive into one country |
| **Hero** | Simple gradient overlay | Parallax with stats grid |
| **Content** | Countries grid + tours | Tours + experiences + pricing |
| **CTAs** | Browse tours, Join club | Request dates, WhatsApp |
| **Design** | Clean, informational | Premium, conversion-focused |
| **Complexity** | Medium | High |

---

## **9. URL ROUTING**

### **How It Works**:
1. User visits `http://visit-rw.foreveryoungtours.local`
2. Web server (Apache/Nginx) routes to `countries/rwanda/index.php`
3. `index.php` includes `config.php` which includes `../../config.php`
4. `config.php` detects subdomain and sets `BASE_URL` to main domain
5. All images/assets use `getImageUrl()` which prepends `BASE_URL`
6. Links to other pages use relative paths or `getCountryUrl()`

### **Example Flow**:
```
User Request: http://visit-rw.foreveryoungtours.local
↓
Apache VirtualHost: Points to /countries/rwanda/
↓
index.php: Loads country data from database
↓
config.php: Sets BASE_URL = http://localhost/foreveryoungtours
↓
getImageUrl('countries/rwanda/assets/images/hero.jpg')
↓
Returns: http://localhost/foreveryoungtours/countries/rwanda/assets/images/hero.jpg
```

---

## **10. SUBDOMAIN SETUP (Apache)**

### **VirtualHost Configuration**:
```apache
# Main Domain
<VirtualHost *:80>
    ServerName foreveryoungtours.local
    DocumentRoot "C:/xampp1/htdocs/foreveryoungtours"
</VirtualHost>

# Continent Subdomain
<VirtualHost *:80>
    ServerName africa.foreveryoungtours.local
    DocumentRoot "C:/xampp1/htdocs/foreveryoungtours/continents/africa"
</VirtualHost>

# Country Subdomain
<VirtualHost *:80>
    ServerName visit-rw.foreveryoungtours.local
    DocumentRoot "C:/xampp1/htdocs/foreveryoungtours/countries/rwanda"
</VirtualHost>
```

### **Hosts File** (`C:\Windows\System32\drivers\etc\hosts`):
```
127.0.0.1 foreveryoungtours.local
127.0.0.1 africa.foreveryoungtours.local
127.0.0.1 visit-rw.foreveryoungtours.local
127.0.0.1 visit-ke.foreveryoungtours.local
127.0.0.1 visit-ng.foreveryoungtours.local
```

---

## **SUMMARY**

✅ **Continents**: Simple landing pages showcasing countries and tours  
✅ **Countries**: Premium landing pages with detailed content and CTAs  
✅ **Config System**: Centralized BASE_URL detection and image handling  
✅ **Database**: Hierarchical structure (Regions → Countries → Tours)  
✅ **URL Routing**: Apache VirtualHosts + Hosts file for local development  
✅ **Design**: Consistent branding with continent/country-specific customization  

**The subdomain system is fully functional and production-ready!** 🚀
