# 📋 Updated Booking Workflow

## ✅ Changes Implemented

### 1. Header Navigation
**"Book Now" Button** → Now directs to **Packages Page**

```
Header → Book Now → pages/packages.php
```

### 2. Booking Flow

```
User Journey:
┌─────────────────┐
│  Header/Home    │
│  "Book Now"     │
└────────┬────────┘
         ↓
┌─────────────────┐
│ Packages Page   │
│ Browse Tours    │
└────────┬────────┘
         ↓
┌─────────────────┐
│ Select Tour     │
│ "Book This Tour"│
└────────┬────────┘
         ↓
┌─────────────────┐
│tour-booking.php │
│ Quick Booking   │
└────────┬────────┘
         ↓
┌─────────────────┐
│bookings table   │
│ BK2025XXXX      │
└─────────────────┘
```

### 3. Inquiry Form Usage

**booking-form.php** = Custom Inquiry Form
- Available on all pages (can be linked)
- For custom tour requests
- Saves to `booking_inquiries` table
- Shows as INQ-XXX

### 4. Admin Panel Structure

**Two Separate Pages:**

#### A. admin/bookings.php
- **Purpose:** Manage confirmed bookings
- **Data Source:** `bookings` table only
- **Shows:** BK2025XXXX references
- **Features:**
  - View booking details
  - Confirm pending bookings
  - Track payments
  - Commission management

#### B. admin/inquiries.php (NEW)
- **Purpose:** Manage custom tour inquiries
- **Data Source:** `booking_inquiries` table only
- **Shows:** INQ-XXX references
- **Features:**
  - View inquiry details
  - Confirm inquiries
  - Convert to bookings (manual)
  - Track status

### 5. Client Panel

**client/bookings.php**
- **Shows:** Only confirmed bookings from `bookings` table
- **Data:** User's own bookings (by email)
- **Features:**
  - View booking details
  - Cancel pending bookings
  - Track booking status
  - See payment status

**Does NOT show:** Inquiries (booking_inquiries table)

---

## 📊 Data Flow

### Quick Booking (tour-booking.php)
```
User fills form
    ↓
process-booking.php
    ↓
INSERT into bookings table
    ↓
Shows in:
  ✅ admin/bookings.php
  ✅ client/bookings.php (if user's email matches)
```

### Custom Inquiry (booking-form.php)
```
User fills form
    ↓
submit-booking.php
    ↓
INSERT into booking_inquiries table
    ↓
Shows in:
  ✅ admin/inquiries.php
  ❌ NOT in client/bookings.php
  ❌ NOT in admin/bookings.php
```

---

## 🎯 Usage Guide

### For Customers:

**Want to book a specific tour?**
1. Click "Book Now" in header
2. Browse packages
3. Click "Book This Tour"
4. Fill quick booking form
5. Get booking reference (BK2025XXXX)
6. Track in client panel

**Want a custom tour?**
1. Go to booking-form.php (inquiry form)
2. Fill detailed preferences
3. Submit inquiry
4. Admin will contact you
5. Once confirmed, becomes a booking

### For Admin:

**Managing Bookings:**
- Go to `admin/bookings.php`
- See all confirmed bookings
- Process payments
- Track commissions

**Managing Inquiries:**
- Go to `admin/inquiries.php`
- See all custom requests
- Review details
- Confirm or reject
- Manually create booking if needed

---

## 📁 File Structure

```
pages/
├── packages.php          → Browse tours (Book Now destination)
├── tour-booking.php      → Quick booking form
├── booking-form.php      → Custom inquiry form
└── process-booking.php   → Processes quick bookings

admin/
├── bookings.php          → Manage confirmed bookings
├── inquiries.php         → NEW: Manage inquiries
└── booking-details.php   → View details (both types)

client/
└── bookings.php          → View own bookings only
```

---

## 🔄 Workflow Summary

| Action | Form | Table | Admin View | Client View |
|--------|------|-------|------------|-------------|
| Book specific tour | tour-booking.php | bookings | bookings.php | bookings.php |
| Custom inquiry | booking-form.php | booking_inquiries | inquiries.php | ❌ Not shown |

---

## ✅ Benefits

1. **Clear Separation**
   - Bookings = Confirmed tours
   - Inquiries = Custom requests

2. **Better Organization**
   - Admin has separate pages
   - Client only sees confirmed bookings
   - No confusion between types

3. **Improved Workflow**
   - Quick booking for standard tours
   - Inquiry form for custom needs
   - Easy tracking for both

4. **User Experience**
   - "Book Now" goes to packages (logical)
   - Client panel shows real bookings
   - Admin can manage separately

---

## 🚀 Next Steps

1. **Add Inquiry Link**
   - Add "Custom Tour Request" link on pages
   - Link to booking-form.php

2. **Update Navigation**
   - Consider adding inquiry form to footer
   - Add to resources page

3. **Admin Training**
   - Train staff on two separate pages
   - Explain booking vs inquiry workflow

---

## 📝 Notes

- Inquiries do NOT appear in client panel
- Only confirmed bookings show to clients
- Admin has full visibility of both
- Clear separation prevents confusion

---

**Updated:** January 2025  
**Status:** ✅ Implemented and Working
