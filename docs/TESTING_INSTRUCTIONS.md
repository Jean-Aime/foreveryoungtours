# 🧪 Testing Instructions for Subdomain Fix

## ✅ **CURRENT STATUS**

- ✅ Tour ID 28 exists in database (name: "test", country: Rwanda)
- ✅ Image files exist on server:
  - `uploads/tours/28_cover_1763207330_5662.jpeg` ✅
  - `assets/images/africa.png` ✅
- ✅ Image path functions fixed in all country tour detail pages
- ✅ Subdomain routing enhanced to handle page requests
- ✅ Query string preservation added to .htaccess

---

## 🔧 **BEFORE TESTING - VERIFY CONFIGURATION**

### **1. Check Hosts File**
Open `C:\Windows\System32\drivers\etc\hosts` as Administrator and ensure:
```
127.0.0.1 foreveryoungtours.local
127.0.0.1 visit-rw.foreveryoungtours.local
127.0.0.1 africa.foreveryoungtours.local
```

### **2. Check Apache Virtual Host**
In your XAMPP `httpd-vhosts.conf`, ensure you have:
```apache
<VirtualHost *:80>
    ServerName foreveryoungtours.local
    ServerAlias *.foreveryoungtours.local
    DocumentRoot "c:/xampp1/htdocs/foreveryoungtours"
    <Directory "c:/xampp1/htdocs/foreveryoungtours">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### **3. Restart Apache**
After any configuration changes, restart Apache in XAMPP.

---

## 🧪 **TESTING SEQUENCE**

### **Step 1: Test Main Domain**
```
✅ http://localhost/foreveryoungtours/test-subdomain-images.php?id=28
```
**Expected:** Should show image paths and confirm files exist

### **Step 2: Test Simple Subdomain Page**
```
🔍 http://visit-rw.foreveryoungtours.local/pages/test-page?id=28
```
**Expected:** Should show "Rwanda Subdomain Test Page" with server info

### **Step 3: Test Tour Detail Page**
```
🎯 http://visit-rw.foreveryoungtours.local/pages/tour-detail?id=28
```
**Expected:** Should show tour detail page with images

### **Step 4: Test Africa Continent**
```
🌍 http://africa.foreveryoungtours.local/
```
**Expected:** Should show featured tours with images

---

## 🚨 **TROUBLESHOOTING**

### **If Step 2 Fails (Subdomain Not Working)**
1. Check hosts file configuration
2. Check Apache virtual host configuration
3. Restart Apache
4. Clear browser cache
5. Try different browser

### **If Step 2 Works But Step 3 Fails**
1. Check if `countries/rwanda/pages/tour-detail.php` exists
2. Check file permissions
3. Check for PHP errors in Apache error log

### **If Images Don't Display**
1. Check browser developer tools for 404 errors
2. Verify image paths in page source
3. Check file permissions on uploads folder

---

## 🔍 **DEBUG TOOLS AVAILABLE**

### **Main Domain Tests:**
- `http://localhost/foreveryoungtours/debug-tour-detail.php?id=28`
- `http://localhost/foreveryoungtours/test-subdomain-images.php?id=28`
- `http://localhost/foreveryoungtours/check-tour-28.php`

### **Subdomain Tests:**
- `http://visit-rw.foreveryoungtours.local/pages/test-page?id=28`
- `http://visit-rw.foreveryoungtours.local/test-subdomain-simple?id=28`

---

## 📊 **EXPECTED RESULTS**

After successful testing:
- ✅ Subdomain routing works for page requests
- ✅ Tour detail page loads with correct data
- ✅ Images display properly (hero, gallery, related tours)
- ✅ Query parameters preserved (?id=28)
- ✅ All country subdomains work the same way

---

## 🎯 **FINAL TEST URLS**

**Primary Target:**
```
http://visit-rw.foreveryoungtours.local/pages/tour-detail?id=28
```

**Secondary Targets:**
```
http://visit-ke.foreveryoungtours.local/pages/tour-detail?id=28
http://visit-tz.foreveryoungtours.local/pages/tour-detail?id=28
http://africa.foreveryoungtours.local/
```

---

**Please test these URLs in sequence and let me know which step fails (if any)!** 🚀
