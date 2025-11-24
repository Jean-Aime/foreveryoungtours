# iForYoungTours Authentication & Booking System

## 🔐 Authentication Flow

### User Registration Process
1. **Email Verification Required**: Users must verify their email before registration
2. **Registration Form**: `auth/register.php`
   - Collects: First name, last name, email, phone, country, city, password
   - Validates password strength (minimum 6 characters)
   - Sends verification code to email
3. **Email Verification**: `auth/send-verification-code.php` & `auth/verify-email-code.php`
   - 6-digit verification code sent to user's email
   - Test mode available when email not configured
4. **Account Creation**: Upon successful verification, user account is created with role 'client'

### User Login Process
1. **Login Form**: `auth/login.php`
2. **Role-Based Redirects**:
   - `super_admin/admin` → `admin/index.php`
   - `mca` → `mca/index.php`
   - `advisor` → `advisor/index.php`
   - `client` → `client/index.php`
3. **Session Management**: User data stored in `$_SESSION`

### Session Variables
```php
$_SESSION['user_id']     // User ID
$_SESSION['user_name']   // Full name
$_SESSION['user_email']  // Email address
$_SESSION['user_role']   // User role (client, advisor, mca, admin)
```

## 🎫 Booking System Integration

### Authentication Requirements
- **Login Required**: Users MUST be logged in to book tours
- **Registration Flow**: New users must register → verify email → login → book

### Booking Process
1. **Tour Selection**: User browses tours on any page
2. **Book Button Click**: Triggers authentication check
3. **Authentication Check**:
   - If logged in: Opens booking modal
   - If not logged in: Shows login required modal with options to login/register
4. **Booking Form**: Multi-step form with personal, travel, and payment details
5. **Booking Submission**: Creates record in `bookings` table with `user_id`

### Database Integration
```sql
-- Bookings table includes user authentication
CREATE TABLE bookings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,                    -- Links to authenticated user
    tour_id INT NOT NULL,
    customer_name VARCHAR(255),
    customer_email VARCHAR(255),
    -- ... other booking fields
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

## 🔧 Technical Implementation

### Files Modified for Authentication
1. **`pages/tour-detail.php`**: Added `session_start()`
2. **`booking-handler.php`**: Added authentication check and user_id integration
3. **`pages/enhanced-booking-modal.php`**: Added login requirement check
4. **`config.php`**: Added session management

### Authentication Checks
```php
// In booking-handler.php
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false, 
        'message' => 'Please login to book a tour', 
        'redirect' => 'auth/login.php'
    ]);
    exit;
}
```

### JavaScript Integration
```javascript
// In enhanced-booking-modal.php
function openBookingModal(tourId, tourName, tourPrice, sharedLink) {
    // PHP session check embedded in JavaScript
    <?php if (!isset($_SESSION['user_id'])): ?>
    showLoginRequiredModal();
    return;
    <?php endif; ?>
    
    // Continue with booking modal...
}
```

## 🎯 User Experience Flow

### For New Users
1. Click "Book Tour" → Login Required Modal appears
2. Click "Register" → Registration form with email verification
3. Verify email → Account created → Redirected to client dashboard
4. Return to tour → Click "Book Tour" → Booking modal opens

### For Existing Users
1. Click "Book Tour" → Booking modal opens immediately
2. Complete booking form → Booking submitted with user association

### For Logged-in Users
- Navigation shows "My Dashboard" instead of "Login"
- Booking buttons work immediately without authentication prompts
- User data pre-populated where possible

## 🛡️ Security Features

### Session Security
- Sessions auto-start in `config.php`
- Session data validated on each request
- Proper session cleanup on logout

### Authentication Validation
- Password hashing with `password_hash()`
- Email verification required for registration
- SQL injection protection with prepared statements
- XSS protection with `htmlspecialchars()`

### Booking Security
- User ID validation before booking creation
- Booking records linked to authenticated users
- Payment information handled securely

## 📊 Database Schema

### Users Table
```sql
users (
    id, email, password, role, first_name, last_name, 
    phone, country, city, status, email_verified, 
    created_at, updated_at
)
```

### Bookings Table
```sql
bookings (
    id, user_id, tour_id, customer_name, customer_email,
    travel_date, participants, total_amount, status,
    payment_status, created_at, updated_at
)
```

## 🔄 Integration Points

### Header Navigation
- Shows login/register for guests
- Shows dashboard link for authenticated users
- Role-based dashboard redirects

### Booking Buttons
- All tour pages check authentication
- Consistent user experience across:
  - Main tour pages
  - Country subdomains
  - Continent pages
  - Tour detail pages

### Error Handling
- Graceful authentication failures
- User-friendly error messages
- Automatic redirects to login/register

## 🚀 Testing the System

### Test User Accounts
```
Email: client@foreveryoung.com
Password: password
Role: client

Email: admin@foreveryoung.com  
Password: password
Role: super_admin
```

### Test Flow
1. Visit any tour page (logged out)
2. Click "Book Tour" → Should show login required modal
3. Register new account → Should require email verification
4. Login with test account → Should redirect to appropriate dashboard
5. Return to tour page → Click "Book Tour" → Should open booking modal
6. Complete booking → Should create record with user_id

## 📝 Notes

- **Email Configuration**: Set up SMTP in `config/email-config.php` for production
- **Test Mode**: Email verification works in test mode for development
- **Session Management**: Sessions persist across page reloads and navigation
- **Role-Based Access**: Different user roles have different dashboard access
- **Booking History**: Users can view their bookings in their dashboard
- **Security**: All user inputs are sanitized and validated

The system ensures that only authenticated users can make bookings while providing a smooth user experience for both new and returning customers.