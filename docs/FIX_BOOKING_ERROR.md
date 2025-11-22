# Fix Booking Error: user_id Cannot Be NULL

## Problem
Error: `SQLSTATE[23000]: Integrity constraint violation: 1048 Column 'user_id' cannot be null`

This error occurs because the `bookings` table's `user_id` column doesn't allow NULL values, but we need to support guest bookings (non-logged-in users).

## Solution

### Option 1: Using phpMyAdmin (Recommended)

1. Open phpMyAdmin: http://localhost/phpmyadmin
2. Select database: `forevveryoungtours`
3. Click on `bookings` table
4. Click "Structure" tab
5. Find the `user_id` row
6. Click "Change" (pencil icon)
7. Check the "NULL" checkbox
8. Set Default to "NULL"
9. Click "Save"

### Option 2: Using SQL Query

1. Open phpMyAdmin: http://localhost/phpmyadmin
2. Select database: `forevveryoungtours`
3. Click "SQL" tab
4. Run this query:

```sql
ALTER TABLE `bookings` MODIFY `user_id` INT(11) NULL DEFAULT NULL;
```

### Option 3: Using Command Line

If you have MySQL command line access:

```bash
mysql -u root -p forevveryoungtours
```

Then run:

```sql
ALTER TABLE bookings MODIFY user_id INT(11) NULL DEFAULT NULL;
```

## Verification

After applying the fix, verify by running:

```sql
DESCRIBE bookings;
```

The `user_id` column should show:
- **Null**: YES
- **Default**: NULL

## Why This Fix Is Needed

The booking system supports two types of bookings:

1. **Logged-in Users (Clients)**: `user_id` is set to their user ID
2. **Guest Users**: `user_id` is NULL (they book without logging in)

By allowing NULL values, we can track which bookings are from registered users and which are from guests, while still capturing all necessary customer information (name, email, phone) in the other columns.

## Test After Fix

1. Try booking a tour as a guest (not logged in)
2. Try booking a tour as a logged-in client
3. Both should work without errors

## Additional Notes

- The SQL file `fix_bookings_user_id.sql` has been created in the project root
- You can import this file directly in phpMyAdmin if needed
- This change is backward compatible and won't affect existing bookings
