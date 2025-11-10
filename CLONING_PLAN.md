# 🔄 Cloning Plan for Subdomains

## **Templates to Clone:**

### **1. Country Template (Rwanda → All Countries)**
**Source:** `subdomains/visit-rw/index.php`

**Will be cloned to:**
- `subdomains/visit-ke/index.php` (Kenya)
- `subdomains/visit-tz/index.php` (Tanzania)
- `subdomains/visit-ug/index.php` (Uganda)
- `subdomains/visit-eg/index.php` (Egypt)
- `subdomains/visit-ma/index.php` (Morocco)
- `subdomains/visit-za/index.php` (South Africa)
- ... and all other countries

**What needs to change per country:**
- Line 5: `$country_slug = 'visit-rw'` → Change to respective country slug
- That's it! Everything else is dynamic from database

---

### **2. Continent Template (Africa → All Continents)**
**Source:** `subdomains/africa/index.php`

**Will be cloned to:**
- `subdomains/asia/index.php`
- `subdomains/europe/index.php`
- `subdomains/north-america/index.php`
- `subdomains/south-america/index.php`
- `subdomains/oceania/index.php`

**What needs to change per continent:**
- Line 5: `$continent_slug = 'africa'` → Change to respective continent slug
- That's it! Everything else is dynamic from database

---

## **Missing Files:**

### **Critical: Tour Details Page**
**File:** `subdomains/visit-rw/tour-details.php` ❌ **DOESN'T EXIST**

**This is why "Book Now" doesn't work!**

The tour cards link to `tour-details.php?slug=...` but this file doesn't exist yet.

**Needs to be created with:**
- Full tour information display
- Photo gallery
- Itinerary
- Reviews
- **Booking form** with:
  - Date picker
  - Number of travelers
  - Customer information
  - Submit button
- Inquiry form

---

## **Next Steps:**

1. ✅ **Create tour-details.php** with booking form
2. ✅ **Create booking backend** (`includes/tour-booking-actions.php`)
3. ⏳ Clone Rwanda template to other countries
4. ⏳ Clone Africa template to other continents
5. ⏳ Create client dashboard for order tracking

---

**Priority:** Create tour-details.php first so "Book Now" works!
