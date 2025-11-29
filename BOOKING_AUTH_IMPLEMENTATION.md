# Booking Authentication Implementation

## Overview
When users click "Book This Tour" on any tour detail page, they must be logged into the **client portal** to proceed with booking.

## Implementation Details

### Files Modified
1. `/pages/tour-detail.php` - Main site tour detail page
2. `/countries/visit-rw/pages/tour-detail.php` - Country subdomain tour detail page

### Authentication Flow

#### For Non-Logged-In Users
1. User clicks "Book This Tour" button
2. System checks: `isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'client'`
3. If NOT logged in → Shows auth modal with:
   - Tour image
   - Tour name
   - Tour description
   - "Login" button → redirects to `/auth/login.php?redirect=[current_url]`
   - "Create Account" button → redirects to `/auth/register.php?redirect=[current_url]`
4. Modal can be closed by clicking X button

#### For Logged-In Client Portal Users
1. User clicks "Book This Tour" button
2. System checks: `isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'client'`
3. If logged in → Opens booking modal directly
4. User proceeds with 4-step booking form

### Session Variables Required
- `$_SESSION['user_id']` - User ID
- `$_SESSION['user_role']` - Must be 'client' for client portal users

### Auth Modal Features
- **ID**: `authModal`
- **Z-index**: `z-50` (high priority)
- **Close button**: Top-right corner (X button)
- **Redirect handling**: Preserves current URL for post-login redirect
- **Responsive**: Works on mobile and desktop

### Login/Register Redirect
After user logs in or registers, they are redirected back to the tour detail page via the `redirect` parameter in the URL.

Example:
```
/auth/login.php?redirect=http://localhost/ForeverYoungTours/tour/5-days-memorable-rwanda-safari
```

## Testing

### Test Case 1: Non-Logged-In User
1. Open tour detail page (not logged in)
2. Click "Book This Tour"
3. Auth modal should appear with tour details
4. Click "Login" → redirects to login page
5. After login, redirects back to tour page
6. Click "Book This Tour" again → booking modal opens

### Test Case 2: Logged-In Client User
1. Login to client portal
2. Open tour detail page
3. Click "Book This Tour"
4. Booking modal opens directly (no auth modal)

### Test Case 3: Non-Client User (Admin/Advisor)
1. Login as admin/advisor
2. Open tour detail page
3. Click "Book This Tour"
4. Auth modal appears (because role is not 'client')
5. Must login as client to proceed

## Code Changes

### Main Change
```javascript
// Before
const isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;

// After
const isLoggedIn = <?php echo (isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'client') ? 'true' : 'false'; ?>;
```

This ensures only client portal users can access the booking modal directly.

## Related Files
- `/auth/login.php` - Login page
- `/auth/register.php` - Registration page
- `/client/index.php` - Client portal dashboard
- `/pages/enhanced-booking-modal.php` - Booking form modal
