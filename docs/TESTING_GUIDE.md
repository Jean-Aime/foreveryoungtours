# Booking System Testing Guide

## Quick Test Steps

### 1. Test Booking Submission

**URL:** `http://localhost/foreveryoungtours/pages/booking-form.php`

**Steps:**
1. Fill out the booking form with test data:
   - Client Name: Test User
   - Email: test@example.com
   - Phone: +1234567890
   - Number of Adults: 2
   - Travel Dates: June 2025
   - Budget: $5000
2. Complete all 5 steps
3. Submit the form
4. You should see success message

### 2. Verify Admin Panel Display

**URL:** `http://localhost/foreveryoungtours/admin/bookings.php`

**Expected Results:**
- Your test booking should appear in the list
- It should have a blue "Inquiry" badge next to the reference number
- Reference format: `INQ-{number}`
- Status should be "Pending"
- All booking details should be visible

**Actions to Test:**
- Click "View" button → Should open booking details popup
- Click "Confirm" button → Should change status to "Confirmed"
- Use filters to filter by status

### 3. Verify Client Panel Display

**URL:** `http://localhost/foreveryoungtours/client/bookings.php`

**Prerequisites:**
- Must be logged in as a client
- Use the same email address as the booking

**Expected Results:**
- Your booking should appear in the client dashboard
- Statistics should show correct counts
- Booking details should be complete
- Can cancel pending bookings

### 4. Test Booking Details View

**From Admin Panel:**
1. Click "View" on any booking inquiry
2. Popup should open showing:
   - Customer information
   - Tour information (if selected)
   - Travel dates
   - Participants count
   - Budget
   - Inquiry details (categories, destinations, activities)
   - Special requests/notes
   - Status badges

### 5. Test Booking Confirmation

**From Admin Panel:**
1. Find a pending booking inquiry
2. Click "Confirm" button
3. Confirm the action in the popup
4. Page should reload
5. Status should change to "Confirmed"
6. Green badge should appear

## Common Issues & Solutions

### Issue: Bookings not appearing in admin panel
**Solution:** 
- Check database connection in `config/database.php`
- Verify database name is `forevveryoungtours` (note: 3 v's)
- Ensure `booking_inquiries` table exists

### Issue: Form submission fails
**Solution:**
- Check browser console for JavaScript errors
- Verify `pages/submit-booking.php` file exists
- Check PHP error logs in XAMPP

### Issue: "Booking not found" error
**Solution:**
- Ensure you're passing the correct `source` parameter
- Check if the booking ID exists in the database

### Issue: Date display shows raw text
**Solution:**
- This is normal for inquiries (they use flexible date text)
- Regular bookings show formatted dates

## Database Verification

### Check booking_inquiries table:
```sql
SELECT * FROM booking_inquiries ORDER BY created_at DESC LIMIT 10;
```

### Check bookings table:
```sql
SELECT * FROM bookings ORDER BY booking_date DESC LIMIT 10;
```

### Count total records:
```sql
SELECT 
    (SELECT COUNT(*) FROM bookings) as total_bookings,
    (SELECT COUNT(*) FROM booking_inquiries) as total_inquiries;
```

## Test Data Examples

### Minimal Test Booking:
- Client Name: John Doe
- Email: john@test.com
- Phone: +1234567890
- Adults: 2
- Travel Dates: Summer 2025
- Budget: $3000

### Complete Test Booking:
- Fill all fields in all 5 steps
- Select multiple categories
- Select multiple destinations
- Add special requests
- Specify group details

## Expected Behavior Summary

| Action | Expected Result |
|--------|----------------|
| Submit booking form | Success message + redirect |
| View in admin panel | Shows with "Inquiry" badge |
| View in client panel | Shows in bookings list |
| Click "View" | Opens details popup |
| Click "Confirm" | Status changes to confirmed |
| Filter by status | Shows filtered results |

## Browser Console Checks

Open browser console (F12) and check for:
- ✅ No JavaScript errors
- ✅ Successful AJAX responses
- ✅ Proper form data submission

## PHP Error Log Location

**Windows XAMPP:**
- `C:\xampp\apache\logs\error.log`
- `C:\xampp\php\logs\php_error_log`

Check these files if you encounter issues.

## Support

If issues persist:
1. Check all file paths are correct
2. Verify database credentials in `config/database.php`
3. Ensure XAMPP Apache and MySQL are running
4. Clear browser cache
5. Check PHP version (should be 7.4+)
