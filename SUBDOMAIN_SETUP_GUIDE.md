# Multi-Subdomain Tour Booking System - Setup Guide

## 🌍 Overview

Complete multi-subdomain system for continent and country-specific tour bookings.

### **Architecture:**
```
Main Site: foreveryoungtours.local
    ↓
Continent: africa.foreveryoungtours.local
    ↓
Country: visit-rw.foreveryoungtours.local
    ↓
Tour Details: visit-rw.foreveryoungtours.local/tour-details.php
```

---

## 📁 Files Created

### **Database:**
1. ✅ `database/tour_booking_system.sql` - Complete tour booking schema

### **Subdomains:**
1. ✅ `subdomains/africa/index.php` - Africa continent landing page
2. ✅ `subdomains/visit-rw/index.php` - Rwanda country landing page
3. ✅ `subdomains/visit-rw/tour-details.php` - Professional tour details page (in progress)

---

## 🔧 Setup Instructions

### **Step 1: Run Database Setup**

**Important:** Your database already has most tables. Run only the additions:

```bash
# Run the tour booking additions SQL (only missing tables)
mysql -u root -p forevveryoungtours < database/tour_booking_additions.sql
```

**Or via phpMyAdmin:**
1. Go to `http://localhost/phpmyadmin`
2. Select `forevveryoungtours` database
3. Click **SQL** tab
4. Copy contents of `database/tour_booking_additions.sql`
5. Click **Go**

### **Step 2: Configure Subdomains**

**Edit your hosts file:**

Windows: `C:\Windows\System32\drivers\etc\hosts`

Add these lines:
```
127.0.0.1 africa.foreveryoungtours.local
127.0.0.1 visit-rw.foreveryoungtours.local
```

**Configure Apache (httpd-vhosts.conf):**

```apache
# Africa Subdomain
<VirtualHost *:80>
    ServerName africa.foreveryoungtours.local
    DocumentRoot "c:/xampp1/htdocs/ForeverYoungTours/subdomains/africa"
    <Directory "c:/xampp1/htdocs/ForeverYoungTours/subdomains/africa">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>

# Rwanda Subdomain
<VirtualHost *:80>
    ServerName visit-rw.foreveryoungtours.local
    DocumentRoot "c:/xampp1/htdocs/ForeverYoungTours/subdomains/visit-rw"
    <Directory "c:/xampp1/htdocs/ForeverYoungTours/subdomains/visit-rw">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

**Restart Apache**

### **Step 3: Test Subdomains**

1. **Africa:** `http://africa.foreveryoungtours.local`
2. **Rwanda:** `http://visit-rw.foreveryoungtours.local`

---

## 📊 Database Tables Created

1. ✅ **tours** - Tour information with full details
2. ✅ **tour_bookings** - Customer bookings and inquiries
3. ✅ **tour_reviews** - Customer reviews and ratings
4. ✅ **tour_faqs** - Frequently asked questions
5. ✅ **tour_availability** - Date-specific availability
6. ✅ **booking_status_history** - Status tracking
7. ✅ **tour_wishlist** - User wishlists

---

## 🎯 Next Steps

1. Complete tour-details.php with booking form
2. Create booking processing backend
3. Create client dashboard for order tracking
4. Add payment integration
5. Create admin tour management

---

**Status:** In Progress - Subdomain structure created, completing booking system.
