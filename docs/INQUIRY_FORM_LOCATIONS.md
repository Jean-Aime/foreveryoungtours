# 📋 Inquiry Form Access Points

## ✅ Where to Find the Inquiry Form

### 1. **Packages Page** (`pages/packages.php`)
**Location:** Below search bar in hero section
```
"Can't find what you're looking for?"
[Request Custom Tour] button
```

### 2. **Tour Detail Page** (`pages/tour-detail.php`)
**Location:** In booking sidebar (right column)
```
[Book This Tour] button (quick booking)
[Custom Inquiry] button (inquiry form)
```

### 3. **Direct Link**
**URL:** `pages/booking-form.php`
- Can be accessed directly
- Can be linked from any page

---

## 🎨 Visual Changes

### Tour Detail Page Background
✅ **Fixed:** Changed from `bg-cream` to `bg-white`
- Now matches other pages
- Clean, professional look

---

## 📊 Form Usage

### Quick Booking (tour-booking.php)
- For specific tours
- Fast checkout
- Saves to `bookings` table

### Custom Inquiry (booking-form.php)
- For custom requests
- Detailed preferences
- Saves to `booking_inquiries` table

---

## 🔗 How It Works

```
User Journey 1: Quick Booking
┌─────────────────┐
│ Packages Page   │
└────────┬────────┘
         ↓
┌─────────────────┐
│ Tour Detail     │
└────────┬────────┘
         ↓
┌─────────────────┐
│ [Book This Tour]│
└────────┬────────┘
         ↓
┌─────────────────┐
│tour-booking.php │
└─────────────────┘
```

```
User Journey 2: Custom Inquiry
┌─────────────────┐
│ Packages Page   │
│ "Can't find?"   │
└────────┬────────┘
         ↓
┌─────────────────┐
│[Request Custom] │
└────────┬────────┘
         ↓
┌─────────────────┐
│booking-form.php │
└─────────────────┘

OR

┌─────────────────┐
│ Tour Detail     │
└────────┬────────┘
         ↓
┌─────────────────┐
│[Custom Inquiry] │
└────────┬────────┘
         ↓
┌─────────────────┐
│booking-form.php │
└─────────────────┘
```

---

## ✅ Summary

**Inquiry Form Now Available On:**
1. ✅ Packages page (hero section)
2. ✅ Tour detail page (sidebar)
3. ✅ Direct URL access

**Background Fixed:**
- ✅ Tour detail page now has white background

---

**Updated:** January 2025
