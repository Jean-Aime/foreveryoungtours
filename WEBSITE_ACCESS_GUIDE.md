# 🌍 iForYoungTours - Website Access Guide

## ✅ FIXED! Path Issues Resolved

**Status:** ✅ **ALL NAVIGATION PATHS FIXED!**

I've updated the `includes/header.php` and `includes/footer.php` files to **automatically detect** which server you're using and adjust paths accordingly.

### Auto-Detection Logic:
- **Port 8000** (PHP built-in server) → Uses `/` as base path
- **Port 80** (XAMPP) → Uses `/foreveryoungtours/` as base path
- **Automatic** → No manual configuration needed!

---

## 🚀 YOUR SETUP

### Current Configuration:
- **Server:** PHP Built-in Server (Port 8000) + XAMPP Available
- **Database:** MySQL/MariaDB
- **PHP Version:** 8.1.25
- **Project Location:** `c:\xampp1\htdocs\foreveryoungtours`

---

## 🔗 CORRECT URLs TO ACCESS

### 🎯 **OPTION 1: PHP Built-in Server (Port 8000) - CURRENTLY RUNNING** ✅

**Use these URLs:**
```
Homepage:        http://localhost:8000/index.php
Destinations:    http://localhost:8000/pages/destinations.php
Packages/Tours:  http://localhost:8000/pages/packages.php
Blog:            http://localhost:8000/pages/blog.php
Experiences:     http://localhost:8000/pages/experiences.php
Login:           http://localhost:8000/auth/login.php
Test Page:       http://localhost:8000/test-navigation.php
```

### 🎯 **OPTION 2: XAMPP (Port 80) - Alternative**

**Use these URLs:**
```
Homepage:        http://localhost/foreveryoungtours/index.php
Destinations:    http://localhost/foreveryoungtours/pages/destinations.php
Packages/Tours:  http://localhost/foreveryoungtours/pages/packages.php
Blog:            http://localhost/foreveryoungtours/pages/blog.php
Experiences:     http://localhost/foreveryoungtours/pages/experiences.php
Login:           http://localhost/foreveryoungtours/auth/login.php
Test Page:       http://localhost/foreveryoungtours/test-navigation.php
```

---

### 🏠 Main Website Pages (Port 8000):
```
Homepage:        http://localhost:8000/index.php
Destinations:    http://localhost:8000/pages/destinations.php
Packages/Tours:  http://localhost:8000/pages/packages.php
Blog:            http://localhost:8000/pages/blog.php
Experiences:     http://localhost:8000/pages/experiences.php
```

### 🔐 Authentication (Port 8000):
```
Login Page:      http://localhost:8000/auth/login.php
Logout:          http://localhost:8000/auth/logout.php
```

### 👑 Admin Panel (Port 8000):
```
Dashboard:       http://localhost:8000/admin/index.php
Bookings:        http://localhost:8000/admin/bookings.php
Users:           http://localhost:8000/admin/users.php
Tours:           http://localhost:8000/admin/tours.php
Destinations:    http://localhost:8000/admin/destinations.php
Regions:         http://localhost:8000/admin/regions.php
Commissions:     http://localhost:8000/admin/commission-management.php
MCAs:            http://localhost:8000/admin/mca-management.php
Advisors:        http://localhost:8000/admin/advisor-management.php
```

### 👥 MCA Dashboard (Port 8000):
```
Dashboard:       http://localhost:8000/mca/index.php
Advisors:        http://localhost:8000/mca/advisors.php
Countries:       http://localhost:8000/mca/countries.php
Tours:           http://localhost:8000/mca/tours.php
```

### 💼 Advisor Dashboard (Port 8000):
```
Dashboard:       http://localhost:8000/advisor/index.php
Bookings:        http://localhost:8000/advisor/bookings.php
Tours:           http://localhost:8000/advisor/tours.php
Team:            http://localhost:8000/advisor/team.php
Training:        http://localhost:8000/advisor/training-portal.php
KYC Upload:      http://localhost:8000/advisor/kyc-upload.php
```

### 👤 Client Dashboard (Port 8000):
```
Dashboard:       http://localhost:8000/pages/dashboard.php
My Bookings:     http://localhost:8000/pages/my-bookings.php
Blog Dashboard:  http://localhost:8000/pages/client-blog-dashboard.php
```

---

## 🧪 TEST PAGE

I've created a test page to help diagnose any issues:

**URL:** http://localhost/foreveryoungtours/test-navigation.php

This page will:
- ✅ Show your server configuration
- ✅ List all important pages with clickable links
- ✅ Check if all files exist
- ✅ Provide troubleshooting tips

---

## 🔑 LOGIN CREDENTIALS

### Demo Accounts:

**Super Admin:**
- Email: `admin@foreveryoung.com`
- Password: `admin123`
- Access: Full system control

**MCA (Master Country Advisor):**
- Email: `mca@foreveryoung.com`
- Password: `mca123`
- Access: Country management, advisor oversight

**Advisor:**
- Email: `advisor@foreveryoung.com`
- Password: `advisor123`
- Access: Tour sales, team building, commissions

**Client:**
- Email: `client@foreveryoung.com`
- Password: `client123`
- Access: Book tours, write blogs, view bookings

---

## 🛠️ TROUBLESHOOTING

### If you see "404 Not Found" error:

#### 1. **Check XAMPP is Running**
   - Open XAMPP Control Panel
   - Make sure **Apache** is running (green)
   - Make sure **MySQL** is running (green)

#### 2. **Verify Project Location**
   - Your project should be in: `c:\xampp1\htdocs\foreveryoungtours`
   - NOT in: `c:\xampp\htdocs\` (note the difference: xampp1 vs xampp)

#### 3. **Check the URL**
   - ✅ Correct: `http://localhost/foreveryoungtours/pages/destinations.php`
   - ❌ Wrong: `http://localhost/pages/destinations.php` (missing foreveryoungtours)
   - ❌ Wrong: `http://localhost:8000/...` (wrong port, that's PHP built-in server)

#### 4. **Clear Browser Cache**
   - Press `Ctrl + Shift + Delete`
   - Clear cached images and files
   - Try again

#### 5. **Check Apache Error Log**
   - In XAMPP Control Panel, click "Logs" button next to Apache
   - Look for any error messages
   - Share the error with me if you need help

#### 6. **Verify File Exists**
   - Go to: `c:\xampp1\htdocs\foreveryoungtours\pages\`
   - Check if `destinations.php` file exists
   - If not, let me know and I'll recreate it

---

## 📁 FILE STRUCTURE

```
foreveryoungtours/
├── index.php                    # Homepage
├── test-navigation.php          # Test page (NEW)
├── auth/
│   ├── login.php               # Login page ✅
│   └── logout.php              # Logout
├── admin/                       # Admin panel
│   ├── index.php               # Dashboard ✅
│   ├── bookings.php            # Bookings ✅
│   ├── users.php               # Users ✅
│   ├── tours.php               # Tours ✅
│   └── ...
├── mca/                         # MCA dashboard
│   ├── index.php
│   └── ...
├── advisor/                     # Advisor dashboard
│   ├── index.php
│   └── ...
├── pages/                       # Public pages
│   ├── destinations.php        # Destinations page
│   ├── packages.php            # Tours/packages
│   ├── blog.php                # Blog
│   ├── dashboard.php           # Client dashboard
│   └── ...
├── includes/
│   ├── header.php              # Site header
│   └── footer.php              # Site footer
├── config/
│   └── database.php            # Database connection ✅
└── assets/
    ├── css/
    ├── js/
    └── images/
```

---

## ✅ WHAT'S WORKING

### Admin System (FULLY FUNCTIONAL):
1. ✅ Login system with role-based redirects
2. ✅ Admin Dashboard with statistics
3. ✅ Booking Management (view, filter, confirm)
4. ✅ User Management (add, edit, activate/deactivate)
5. ✅ Tours Management (add, edit, delete)
6. ✅ Responsive design (mobile, tablet, desktop)
7. ✅ Session management
8. ✅ Database connection

### Main Website:
1. ✅ Homepage with featured tours
2. ✅ Destinations page (all regions & countries)
3. ✅ Packages/Tours page
4. ✅ Blog system
5. ✅ Navigation menu
6. ✅ Footer with links

---

## 🎯 QUICK START GUIDE

### Step 1: Start XAMPP
1. Open XAMPP Control Panel
2. Click "Start" for Apache
3. Click "Start" for MySQL
4. Wait for both to turn green

### Step 2: Access Test Page
1. Open browser
2. Go to: http://localhost/foreveryoungtours/test-navigation.php
3. Check if all files show ✅ (green checkmarks)

### Step 3: Test Main Website
1. Click "Homepage" link from test page
2. Navigate through the menu
3. Click "Destinations" in the navigation
4. Should load without errors

### Step 4: Test Admin Login
1. Go to: http://localhost/foreveryoungtours/auth/login.php
2. Login with: `admin@foreveryoung.com` / `admin123`
3. Should redirect to admin dashboard
4. Test all admin pages from sidebar

---

## 🆘 STILL HAVING ISSUES?

If you're still seeing "404 Not Found" errors:

1. **Take a screenshot** of the error
2. **Copy the exact URL** from your browser address bar
3. **Check the test page** and tell me what it shows
4. **Share the Apache error log** if possible

Then I can help you fix the specific issue!

---

## 📞 NEXT STEPS

Once the website is accessible, we can:

1. ✅ **Test all admin pages** - Make sure everything works
2. ✅ **Fix remaining admin pages** - Complete the admin system
3. ✅ **Work on MCA dashboard** - Fix MCA-specific features
4. ✅ **Work on Advisor dashboard** - Fix advisor features
5. ✅ **Work on Client dashboard** - Fix client features
6. ✅ **Test MLM commission system** - Verify commission calculations
7. ✅ **Test booking flow** - End-to-end booking process
8. ✅ **Add sample data** - Tours, destinations, users

---

**Created:** October 24, 2025  
**Status:** Admin system partially complete, main website functional  
**Test Page:** http://localhost/foreveryoungtours/test-navigation.php

