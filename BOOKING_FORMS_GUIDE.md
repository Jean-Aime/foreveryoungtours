# Booking Forms Guide

## Two Separate Forms

Your booking system now has **two distinct forms** for different purposes:

---

## 1. 📋 Tour Inquiry Form (Custom Requests)

**File:** `pages/booking-form.php` or `pages/inquiry-form.php`

**Purpose:** For customers who want custom itineraries or have specific requirements

**Saves to:** `booking_inquiries` table

**Use when:**
- Customer wants a custom tour
- Flexible dates and preferences
- Group bookings with special requirements
- Need detailed planning and consultation

**Features:**
- 5-step detailed form
- Multiple categories and destinations
- Group information
- Travel preferences
- Hotel and flight preferences
- Referral tracking

**Admin View:** Shows with blue "Inquiry" badge (INQ-XXX)

---

## 2. ✅ Professional Tour Booking Form (Direct Bookings)

**File:** `pages/tour-booking.php`

**Purpose:** For customers booking specific tours with fixed dates

**Saves to:** `bookings` table

**Use when:**
- Customer knows which tour they want
- Ready to book with specific dates
- Quick checkout process
- Immediate confirmation needed

**Features:**
- Single-page form
- Customer information
- Travel date selection
- Participant count
- Accommodation upgrade options
- Transport upgrade options
- Real-time price calculation
- Payment method selection

**Admin View:** Shows as regular booking (BK2025XXXX)

---

## Quick Comparison

| Feature | Inquiry Form | Booking Form |
|---------|-------------|--------------|
| **URL** | `/pages/booking-form.php` | `/pages/tour-booking.php` |
| **Database** | `booking_inquiries` | `bookings` |
| **Reference** | INQ-{id} | BK{year}{number} |
| **Steps** | 5 steps | Single page |
| **Purpose** | Custom planning | Direct booking |
| **Date Type** | Flexible text | Specific date |
| **Price** | Budget range | Exact calculation |
| **Processing** | Manual quote | Immediate |
| **Commission** | Not tracked | Tracked |

---

## Usage Examples

### Inquiry Form - Use For:
```
✓ "I want to visit Kenya and Tanzania in summer"
✓ "Planning a group trip for 20 people"
✓ "Need custom safari with specific hotels"
✓ "Flexible dates, want recommendations"
```

### Booking Form - Use For:
```
✓ "Book Serengeti Safari on June 15, 2025"
✓ "2 people, premium accommodation"
✓ "Ready to pay now"
✓ "Specific tour from catalog"
```

---

## Integration Points

### From Tour Catalog:
```html
<!-- Link to booking form with tour details -->
<a href="pages/tour-booking.php?tour_id=123&tour_name=Serengeti Safari&price=1500">
    Book Now
</a>
```

### From Homepage:
```html
<!-- Link to inquiry form for custom requests -->
<a href="pages/booking-form.php">
    Plan Custom Tour
</a>
```

---

## Admin Panel Display

Both forms show in the same admin panel (`admin/bookings.php`):

**Inquiry:**
```
INQ-45  [Inquiry]  |  Jane Smith  |  Custom Tour  |  Pending
```

**Booking:**
```
BK20250123  |  John Doe  |  Serengeti Safari  |  Confirmed
```

---

## Client Panel Display

Clients see their submissions in `client/bookings.php`:
- Inquiries show with flexible dates
- Bookings show with specific dates
- Both show status updates

---

## Processing Flow

### Inquiry Form:
```
User submits → booking_inquiries table → Admin reviews → 
Manual quote → Convert to booking (optional)
```

### Booking Form:
```
User submits → bookings table → Auto-calculate price → 
Admin confirms → Payment processing
```

---

## File Structure

```
pages/
├── booking-form.php          → Inquiry form (detailed, 5 steps)
├── inquiry-form.php          → Copy of booking-form.php
├── tour-booking.php          → NEW: Professional booking form
├── submit-booking.php        → Processes inquiry form
└── process-booking.php       → NEW: Processes booking form

admin/
├── bookings.php              → Shows BOTH types
├── booking-details.php       → Views BOTH types
└── booking-actions.php       → Handles BOTH types
```

---

## Testing

### Test Inquiry Form:
1. Go to: `http://localhost/foreveryoungtours/pages/booking-form.php`
2. Fill all 5 steps
3. Submit
4. Check admin panel → Should show with "Inquiry" badge

### Test Booking Form:
1. Go to: `http://localhost/foreveryoungtours/pages/tour-booking.php?tour_id=1&tour_name=Safari&price=1500`
2. Fill form
3. Submit
4. Check admin panel → Should show as regular booking

---

## Recommendations

### Navigation Updates:
1. Add both links to main navigation
2. "Book a Tour" → tour-booking.php
3. "Plan Custom Trip" → booking-form.php

### Tour Pages:
- Add "Book Now" button → tour-booking.php with tour details
- Add "Customize This Tour" → booking-form.php with tour pre-selected

### Homepage:
- "Quick Booking" CTA → tour-booking.php
- "Plan Your Dream Trip" CTA → booking-form.php

---

## Benefits

✅ **Clear separation** of inquiry vs booking
✅ **Better user experience** - right form for right purpose
✅ **Easier admin management** - visual distinction
✅ **Flexible pricing** - upgrades and options
✅ **Commission tracking** - only for confirmed bookings
✅ **Faster checkout** - single page for bookings

---

## Next Steps

1. ✅ Test both forms
2. ✅ Update navigation menus
3. ✅ Add links from tour pages
4. ✅ Train staff on differences
5. ✅ Update marketing materials

---

**Questions?** Check the other documentation files for detailed technical information.
