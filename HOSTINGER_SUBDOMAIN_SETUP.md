# 🌐 Hostinger Subdomain Configuration Guide
## ForeverYoung Tours - Production Domain Setup

---

## 📍 **DOMAIN STRUCTURE**

Based on your purchased domains:

### **Continents**
- **Africa**: `http://africa.foreveryoungtours.com`
- **Asia**: `http://asia.foreveryoungtours.com`
- **Europe**: `http://europe.foreveryoungtours.com`
- **North America**: `http://north-america.foreveryoungtours.com`
- **South America**: `http://south-america.foreveryoungtours.com`
- **Caribbean**: `http://caribbean.foreveryoungtours.com`

### **Countries**
- **Rwanda**: `http://visit-rw.foreveryoungtours.com`
- **Kenya**: `http://visit-ke.foreveryoungtours.com`
- **Tanzania**: `http://visit-tz.foreveryoungtours.com`
- **Uganda**: `http://visit-ug.foreveryoungtours.com`
- **Egypt**: `http://visit-eg.foreveryoungtours.com`
- **Morocco**: `http://visit-ma.foreveryoungtours.com`
- **South Africa**: `http://visit-za.foreveryoungtours.com`

---

## 🔧 **HOSTINGER CONFIGURATION STEPS**

### **Step 1: DNS Management in Hostinger**

1. **Login to Hostinger Control Panel**
2. **Go to DNS Zone Editor**
3. **Add A Records for each subdomain**

#### **DNS Records to Add:**

```dns
# Main Domain
Type    Name                        Value               TTL
A       @                          YOUR_SERVER_IP      3600
A       www                        YOUR_SERVER_IP      3600

# Continent Subdomains
A       africa                     YOUR_SERVER_IP      3600
A       asia                       YOUR_SERVER_IP      3600
A       europe                     YOUR_SERVER_IP      3600
A       north-america              YOUR_SERVER_IP      3600
A       south-america              YOUR_SERVER_IP      3600
A       caribbean                  YOUR_SERVER_IP      3600

# Country Subdomains
A       visit-rw                   YOUR_SERVER_IP      3600
A       visit-ke                   YOUR_SERVER_IP      3600
A       visit-tz                   YOUR_SERVER_IP      3600
A       visit-ug                   YOUR_SERVER_IP      3600
A       visit-eg                   YOUR_SERVER_IP      3600
A       visit-ma                   YOUR_SERVER_IP      3600
A       visit-za                   YOUR_SERVER_IP      3600
A       visit-bw                   YOUR_SERVER_IP      3600
A       visit-na                   YOUR_SERVER_IP      3600
A       visit-zw                   YOUR_SERVER_IP      3600
```

### **Step 2: Wildcard DNS (Alternative Method)**

For easier management, you can use wildcard DNS:

```dns
Type    Name        Value               TTL
A       *           YOUR_SERVER_IP      3600
```

This will automatically handle all subdomains.

---

## 🗂️ **SERVER DIRECTORY STRUCTURE**

Upload your files to Hostinger with this structure:

```
public_html/
├── foreveryoungtours.com/          # Main domain files
│   ├── index.php
│   ├── config/
│   ├── includes/
│   ├── assets/
│   ├── continents/
│   │   ├── africa/
│   │   │   └── index.php
│   │   ├── asia/
│   │   │   └── index.php
│   │   └── [other continents]/
│   └── countries/
│       ├── rwanda/
│       │   └── index.php
│       ├── kenya/
│       │   └── index.php
│       └── [other countries]/
```

---

## ⚙️ **APACHE VIRTUAL HOST CONFIGURATION**

### **Method 1: Individual Virtual Hosts**

Create `.htaccess` in your main directory:

```apache
RewriteEngine On

# Continent subdomains
RewriteCond %{HTTP_HOST} ^africa\.foreveryoungtours\.com$ [NC]
RewriteCond %{REQUEST_URI} !^/continents/africa/
RewriteRule ^(.*)$ /continents/africa/$1 [L]

RewriteCond %{HTTP_HOST} ^asia\.foreveryoungtours\.com$ [NC]
RewriteCond %{REQUEST_URI} !^/continents/asia/
RewriteRule ^(.*)$ /continents/asia/$1 [L]

RewriteCond %{HTTP_HOST} ^europe\.foreveryoungtours\.com$ [NC]
RewriteCond %{REQUEST_URI} !^/continents/europe/
RewriteRule ^(.*)$ /continents/europe/$1 [L]

RewriteCond %{HTTP_HOST} ^north-america\.foreveryoungtours\.com$ [NC]
RewriteCond %{REQUEST_URI} !^/continents/north-america/
RewriteRule ^(.*)$ /continents/north-america/$1 [L]

RewriteCond %{HTTP_HOST} ^south-america\.foreveryoungtours\.com$ [NC]
RewriteCond %{REQUEST_URI} !^/continents/south-america/
RewriteRule ^(.*)$ /continents/south-america/$1 [L]

RewriteCond %{HTTP_HOST} ^caribbean\.foreveryoungtours\.com$ [NC]
RewriteCond %{REQUEST_URI} !^/continents/caribbean/
RewriteRule ^(.*)$ /continents/caribbean/$1 [L]

# Country subdomains
RewriteCond %{HTTP_HOST} ^visit-rw\.foreveryoungtours\.com$ [NC]
RewriteCond %{REQUEST_URI} !^/countries/rwanda/
RewriteRule ^(.*)$ /countries/rwanda/$1 [L]

RewriteCond %{HTTP_HOST} ^visit-ke\.foreveryoungtours\.com$ [NC]
RewriteCond %{REQUEST_URI} !^/countries/kenya/
RewriteRule ^(.*)$ /countries/kenya/$1 [L]

RewriteCond %{HTTP_HOST} ^visit-tz\.foreveryoungtours\.com$ [NC]
RewriteCond %{REQUEST_URI} !^/countries/tanzania/
RewriteRule ^(.*)$ /countries/tanzania/$1 [L]

RewriteCond %{HTTP_HOST} ^visit-ug\.foreveryoungtours\.com$ [NC]
RewriteCond %{REQUEST_URI} !^/countries/uganda/
RewriteRule ^(.*)$ /countries/uganda/$1 [L]

RewriteCond %{HTTP_HOST} ^visit-eg\.foreveryoungtours\.com$ [NC]
RewriteCond %{REQUEST_URI} !^/countries/egypt/
RewriteRule ^(.*)$ /countries/egypt/$1 [L]

RewriteCond %{HTTP_HOST} ^visit-ma\.foreveryoungtours\.com$ [NC]
RewriteCond %{REQUEST_URI} !^/countries/morocco/
RewriteRule ^(.*)$ /countries/morocco/$1 [L]

RewriteCond %{HTTP_HOST} ^visit-za\.foreveryoungtours\.com$ [NC]
RewriteCond %{REQUEST_URI} !^/countries/south-africa/
RewriteRule ^(.*)$ /countries/south-africa/$1 [L]

RewriteCond %{HTTP_HOST} ^visit-bw\.foreveryoungtours\.com$ [NC]
RewriteCond %{REQUEST_URI} !^/countries/botswana/
RewriteRule ^(.*)$ /countries/botswana/$1 [L]

RewriteCond %{HTTP_HOST} ^visit-na\.foreveryoungtours\.com$ [NC]
RewriteCond %{REQUEST_URI} !^/countries/namibia/
RewriteRule ^(.*)$ /countries/namibia/$1 [L]

RewriteCond %{HTTP_HOST} ^visit-zw\.foreveryoungtours\.com$ [NC]
RewriteCond %{REQUEST_URI} !^/countries/zimbabwe/
RewriteRule ^(.*)$ /countries/zimbabwe/$1 [L]
```

### **Method 2: Dynamic Routing (Recommended)**

```apache
RewriteEngine On

# Dynamic continent routing
RewriteCond %{HTTP_HOST} ^(africa|asia|europe|north-america|south-america|caribbean)\.foreveryoungtours\.com$ [NC]
RewriteCond %{REQUEST_URI} !^/continents/
RewriteRule ^(.*)$ /continents/%1/$1 [L]

# Dynamic country routing
RewriteCond %{HTTP_HOST} ^visit-([a-z]{2})\.foreveryoungtours\.com$ [NC]
RewriteCond %{REQUEST_URI} !^/countries/
RewriteRule ^(.*)$ /countries/%1/$1 [L]
```

---

## 🔒 **SSL CERTIFICATE SETUP**

### **Option 1: Hostinger Free SSL**
1. Go to Hostinger Control Panel
2. Navigate to **SSL** section
3. Enable **Free SSL Certificate**
4. Add all subdomains to the certificate

### **Option 2: Wildcard SSL Certificate**
Purchase a wildcard SSL certificate for:
- `*.foreveryoungtours.com`

This covers all subdomains automatically.

### **Force HTTPS Redirect**
Add to your `.htaccess`:

```apache
# Force HTTPS
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

---

## 📝 **UPDATE YOUR PHP FILES**

### **Update Base URLs in Configuration**

Create a production config file:

```php
<?php
// config/production.php

// Production domain configuration
$production_domains = [
    // Continents
    'africa' => 'https://africa.foreveryoungtours.com',
    'asia' => 'https://asia.foreveryoungtours.com',
    'europe' => 'https://europe.foreveryoungtours.com',
    'north-america' => 'https://north-america.foreveryoungtours.com',
    'south-america' => 'https://south-america.foreveryoungtours.com',
    'caribbean' => 'https://caribbean.foreveryoungtours.com',
    
    // Countries
    'visit-rw' => 'https://visit-rw.foreveryoungtours.com',
    'visit-ke' => 'https://visit-ke.foreveryoungtours.com',
    'visit-tz' => 'https://visit-tz.foreveryoungtours.com',
    'visit-ug' => 'https://visit-ug.foreveryoungtours.com',
    'visit-eg' => 'https://visit-eg.foreveryoungtours.com',
    'visit-ma' => 'https://visit-ma.foreveryoungtours.com',
    'visit-za' => 'https://visit-za.foreveryoungtours.com',
];

// Main domain
$main_domain = 'https://foreveryoungtours.com';

// Detect current environment
$is_local = (strpos($_SERVER['HTTP_HOST'], '.local') !== false);
$base_url = $is_local ? 'http://' . $_SERVER['HTTP_HOST'] : 'https://' . $_SERVER['HTTP_HOST'];
?>
```

### **Update Links in Your Templates**

```php
<?php
// Auto-detect environment and set appropriate URLs
$is_local = (strpos($_SERVER['HTTP_HOST'], '.local') !== false);

if ($is_local) {
    // Local development URLs
    $main_url = 'http://foreveryoungtours.local';
    $africa_url = 'http://africa.foreveryoungtours.local';
    $rwanda_url = 'http://visit-rw.foreveryoungtours.local';
} else {
    // Production URLs
    $main_url = 'https://foreveryoungtours.com';
    $africa_url = 'https://africa.foreveryoungtours.com';
    $rwanda_url = 'https://visit-rw.foreveryoungtours.com';
}
?>

<!-- Use in your templates -->
<a href="<?php echo $main_url; ?>">Home</a>
<a href="<?php echo $africa_url; ?>">Explore Africa</a>
<a href="<?php echo $rwanda_url; ?>">Visit Rwanda</a>
```

---

## 🧪 **TESTING CHECKLIST**

### **DNS Propagation**
```bash
# Test DNS resolution
nslookup africa.foreveryoungtours.com
nslookup visit-rw.foreveryoungtours.com
```

### **Subdomain Access**
- [ ] `https://africa.foreveryoungtours.com`
- [ ] `https://visit-rw.foreveryoungtours.com`
- [ ] `https://visit-ke.foreveryoungtours.com`
- [ ] All other subdomains

### **SSL Certificate**
- [ ] HTTPS loads without warnings
- [ ] Certificate covers all subdomains
- [ ] HTTP redirects to HTTPS

### **File Paths**
- [ ] CSS/JS files load correctly
- [ ] Images display properly
- [ ] Database connections work
- [ ] Cross-subdomain links function

---

## 🚀 **DEPLOYMENT STEPS**

### **1. Prepare Files**
```bash
# Compress your local files
zip -r foreveryoungtours-production.zip ForeverYoungTours/
```

### **2. Upload to Hostinger**
- Use File Manager or FTP
- Upload to `public_html/`
- Extract files

### **3. Configure Database**
- Import your database
- Update database credentials in `config/database.php`

### **4. Set Permissions**
```bash
chmod 755 public_html/
chmod 644 public_html/.htaccess
```

### **5. Test Everything**
- Check all subdomains
- Verify SSL certificates
- Test functionality

---

## 🐛 **TROUBLESHOOTING**

### **Subdomain Not Loading**
1. Check DNS propagation (24-48 hours)
2. Verify A records in Hostinger DNS
3. Check `.htaccess` syntax
4. Clear browser cache

### **SSL Issues**
1. Ensure SSL certificate includes subdomain
2. Check mixed content (HTTP resources on HTTPS)
3. Update all internal links to HTTPS

### **File Path Issues**
1. Check case sensitivity (Linux servers)
2. Verify directory structure
3. Update relative paths

### **Database Connection Errors**
1. Update database credentials
2. Check database permissions
3. Verify database exists

---

## 📊 **MONITORING & MAINTENANCE**

### **Regular Checks**
- [ ] SSL certificate expiration
- [ ] DNS record updates
- [ ] Site accessibility
- [ ] Performance monitoring

### **Backup Strategy**
- [ ] Database backups
- [ ] File backups
- [ ] DNS configuration backup

---

## ✅ **PRODUCTION DEPLOYMENT CHECKLIST**

- [ ] Purchase domains on Hostinger
- [ ] Configure DNS A records
- [ ] Upload files to server
- [ ] Set up database
- [ ] Configure `.htaccess` redirects
- [ ] Install SSL certificates
- [ ] Update PHP configuration files
- [ ] Test all subdomains
- [ ] Verify HTTPS redirects
- [ ] Check cross-subdomain links
- [ ] Monitor for 24-48 hours

---

**Status:** 🚀 Ready for production deployment
**Last Updated:** November 2024
**Domain Provider:** Hostinger
