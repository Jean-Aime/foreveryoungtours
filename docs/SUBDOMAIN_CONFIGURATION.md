# 🌐 Subdomain Configuration Guide
## ForeverYoung Tours - Country Subdomains Setup

---

## 📍 **SUBDOMAIN STRUCTURE**

Each country runs on its own subdomain:

```
Main Site:    http://foreveryoungtours.local/
Rwanda:       http://visit-rw.foreveryoungtours.local/
Kenya:        http://visit-ke.foreveryoungtours.local/
Tanzania:     http://visit-tz.foreveryoungtours.local/
Uganda:       http://visit-ug.foreveryoungtours.local/
```

---

## 🔧 **APACHE CONFIGURATION (XAMPP)**

### **1. Edit Windows Hosts File**

Location: `C:\Windows\System32\drivers\etc\hosts`

Add these lines:
```
127.0.0.1    foreveryoungtours.local
127.0.0.1    visit-rw.foreveryoungtours.local
127.0.0.1    visit-ke.foreveryoungtours.local
127.0.0.1    visit-tz.foreveryoungtours.local
127.0.0.1    visit-ug.foreveryoungtours.local
127.0.0.1    visit-eg.foreveryoungtours.local
127.0.0.1    visit-ma.foreveryoungtours.local
127.0.0.1    visit-za.foreveryoungtours.local
# Add more countries as needed
```

### **2. Apache Virtual Host Configuration**

Location: `C:\xampp\apache\conf\extra\httpd-vhosts.conf`

Add this configuration:

```apache
# Main Site
<VirtualHost *:80>
    ServerName foreveryoungtours.local
    DocumentRoot "C:/xampp1/htdocs/ForeverYoungTours"
    
    <Directory "C:/xampp1/htdocs/ForeverYoungTours">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog "logs/foreveryoungtours-error.log"
    CustomLog "logs/foreveryoungtours-access.log" common
</VirtualHost>

# Country Subdomains - Wildcard Configuration
<VirtualHost *:80>
    ServerName visit-rw.foreveryoungtours.local
    ServerAlias visit-*.foreveryoungtours.local
    DocumentRoot "C:/xampp1/htdocs/ForeverYoungTours"
    
    <Directory "C:/xampp1/htdocs/ForeverYoungTours">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    # Rewrite rules for subdomain routing
    RewriteEngine On
    RewriteCond %{HTTP_HOST} ^visit-([a-z]{2})\.foreveryoungtours\.local$ [NC]
    RewriteRule ^(.*)$ /subdomains/visit-%1/index.php [L,QSA]
    
    ErrorLog "logs/country-subdomains-error.log"
    CustomLog "logs/country-subdomains-access.log" common
</VirtualHost>
```

### **3. Enable Apache Modules**

Make sure these modules are enabled in `httpd.conf`:
```apache
LoadModule rewrite_module modules/mod_rewrite.so
LoadModule vhost_alias_module modules/mod_vhost_alias.so
```

### **4. Restart Apache**

```bash
# Stop Apache
C:\xampp\apache\bin\httpd.exe -k stop

# Start Apache
C:\xampp\apache\bin\httpd.exe -k start
```

Or use XAMPP Control Panel to restart Apache.

---

## 🗂️ **DIRECTORY STRUCTURE**

```
ForeverYoungTours/
├── config/
│   └── database.php
├── includes/
│   ├── header.php
│   └── footer.php
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
├── subdomains/
│   ├── visit-rw/
│   │   └── index.php  (Rwanda)
│   ├── visit-ke/
│   │   └── index.php  (Kenya)
│   ├── visit-tz/
│   │   └── index.php  (Tanzania)
│   └── [other countries]/
│       └── index.php
└── index.php (Main site)
```

---

## 📝 **FILE PATH CONFIGURATION**

Each country's `index.php` uses these paths:

```php
<?php
session_start();

// Subdomain structure - adjust path based on your setup
$base_dir = dirname(dirname(__DIR__)); // Go up to main directory
require_once $base_dir . '/config/database.php';

$country_slug = 'visit-rw'; // Change per country

// Base path for subdomain structure
$base_path = '/';
$css_path = 'assets/css/modern-styles.css';

include 'includes/header.php';
?>
```

**Key Points:**
- `$base_dir` points to the main ForeverYoungTours directory
- `$base_path = '/'` for root-level assets on subdomain
- Links to main site use full URL: `http://foreveryoungtours.local/`

---

## 🔗 **LINKING BETWEEN SITES**

### **From Country to Main Site**
```php
<a href="http://foreveryoungtours.local/pages/contact.php">Contact Us</a>
<a href="http://foreveryoungtours.local/">Home</a>
```

### **From Main Site to Country**
```php
<a href="http://visit-rw.foreveryoungtours.local/">Visit Rwanda</a>
<a href="http://visit-ke.foreveryoungtours.local/">Visit Kenya</a>
```

### **Between Countries**
```php
<a href="http://visit-tz.foreveryoungtours.local/">Visit Tanzania</a>
```

---

## 🧪 **TESTING**

### **1. Test Main Site**
```
http://foreveryoungtours.local/
```
Should load the main homepage.

### **2. Test Country Subdomain**
```
http://visit-rw.foreveryoungtours.local/
```
Should load Rwanda's country page.

### **3. Test Subdomain Routing**
```
http://visit-ke.foreveryoungtours.local/
http://visit-tz.foreveryoungtours.local/
```
Should load respective country pages.

### **4. Check Apache Logs**
If issues occur, check:
```
C:\xampp\apache\logs\error.log
C:\xampp\apache\logs\country-subdomains-error.log
```

---

## 🐛 **TROUBLESHOOTING**

### **Issue: Subdomain not loading**
**Solutions:**
1. Check hosts file has the subdomain entry
2. Verify Apache virtual host configuration
3. Restart Apache
4. Clear browser cache
5. Check DocumentRoot path is correct

### **Issue: 404 Not Found**
**Solutions:**
1. Verify subdomain folder exists: `subdomains/visit-rw/`
2. Check index.php exists in the folder
3. Verify RewriteRule in virtual host
4. Enable mod_rewrite in Apache

### **Issue: CSS/JS not loading**
**Solutions:**
1. Check `$base_path` is set to `/`
2. Verify asset paths in header.php
3. Check file permissions
4. Clear browser cache

### **Issue: Database connection fails**
**Solutions:**
1. Verify `$base_dir` path is correct
2. Check database.php path
3. Ensure database credentials are correct
4. Check MySQL is running

---

## 📊 **SUBDOMAIN LIST**

### **Africa**
```
visit-rw.foreveryoungtours.local  → Rwanda
visit-ke.foreveryoungtours.local  → Kenya
visit-tz.foreveryoungtours.local  → Tanzania
visit-ug.foreveryoungtours.local  → Uganda
visit-eg.foreveryoungtours.local  → Egypt
visit-ma.foreveryoungtours.local  → Morocco
visit-za.foreveryoungtours.local  → South Africa
visit-gh.foreveryoungtours.local  → Ghana
visit-ng.foreveryoungtours.local  → Nigeria
visit-et.foreveryoungtours.local  → Ethiopia
```

### **Asia**
```
visit-th.foreveryoungtours.local  → Thailand
visit-jp.foreveryoungtours.local  → Japan
visit-cn.foreveryoungtours.local  → China
visit-in.foreveryoungtours.local  → India
visit-ae.foreveryoungtours.local  → UAE
```

### **Europe**
```
visit-fr.foreveryoungtours.local  → France
visit-it.foreveryoungtours.local  → Italy
visit-es.foreveryoungtours.local  → Spain
visit-uk.foreveryoungtours.local  → United Kingdom
visit-de.foreveryoungtours.local  → Germany
```

### **Americas**
```
visit-us.foreveryoungtours.local  → United States
visit-br.foreveryoungtours.local  → Brazil
visit-mx.foreveryoungtours.local  → Mexico
visit-ca.foreveryoungtours.local  → Canada
```

---

## 🚀 **PRODUCTION DEPLOYMENT**

For production (live server), replace `.local` with your actual domain:

```
Main Site:    https://foreveryoungtours.com/
Rwanda:       https://visit-rw.foreveryoungtours.com/
Kenya:        https://visit-ke.foreveryoungtours.com/
```

### **DNS Configuration**
Add A records or CNAME records for each subdomain:
```
Type    Name        Value
A       @           YOUR_SERVER_IP
A       visit-rw    YOUR_SERVER_IP
A       visit-ke    YOUR_SERVER_IP
CNAME   visit-*     foreveryoungtours.com
```

### **SSL Certificates**
Use wildcard SSL certificate:
```
*.foreveryoungtours.com
```
This covers all subdomains.

---

## ✅ **QUICK SETUP CHECKLIST**

- [ ] Edit Windows hosts file
- [ ] Add all country subdomains to hosts
- [ ] Configure Apache virtual hosts
- [ ] Enable mod_rewrite
- [ ] Restart Apache
- [ ] Test main site loads
- [ ] Test Rwanda subdomain loads
- [ ] Clone country pages
- [ ] Test all country subdomains
- [ ] Verify database connections
- [ ] Check CSS/JS loading
- [ ] Test cross-site links

---

## 📚 **ADDITIONAL RESOURCES**

- Apache Virtual Hosts: https://httpd.apache.org/docs/2.4/vhosts/
- XAMPP Documentation: https://www.apachefriends.org/docs/
- Windows Hosts File: https://docs.microsoft.com/en-us/windows/hosts

---

**Status:** ✅ Ready for subdomain deployment
**Last Updated:** November 2024
