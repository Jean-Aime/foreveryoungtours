# Booking System Flow Diagram

## System Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                     BOOKING SYSTEM OVERVIEW                      │
└─────────────────────────────────────────────────────────────────┘

┌──────────────────┐
│   User/Client    │
└────────┬─────────┘
         │
         ▼
┌─────────────────────────────────────────────────────────────────┐
│                    BOOKING FORM SUBMISSION                       │
│  File: pages/booking-form.php → pages/submit-booking.php       │
└────────┬────────────────────────────────────────────────────────┘
         │
         ▼
┌─────────────────────────────────────────────────────────────────┐
│                   DATABASE: booking_inquiries                    │
│  Stores: Customer info, preferences, travel details, etc.       │
└────┬────────────────────────────────────────────────────────┬───┘
     │                                                        │
     │                                                        │
     ▼                                                        ▼
┌─────────────────────────┐                    ┌──────────────────────────┐
│    ADMIN PANEL VIEW     │                    │   CLIENT PANEL VIEW      │
│  admin/bookings.php     │                    │  client/bookings.php     │
│                         │                    │                          │
│  Shows:                 │                    │  Shows:                  │
│  • All inquiries        │                    │  • User's own bookings   │
│  • Regular bookings     │                    │  • Status updates        │
│  • Combined statistics  │                    │  • Booking details       │
│  • "Inquiry" badges     │                    │  • Cancel option         │
└────────┬────────────────┘                    └──────────────────────────┘
         │
         ▼
┌─────────────────────────────────────────────────────────────────┐
│                    ADMIN ACTIONS                                 │
│  File: admin/booking-actions.php                                │
│                                                                  │
│  Actions:                                                        │
│  • Confirm booking → Updates status in booking_inquiries        │
│  • View details → Opens booking-details.php                     │
│  • Filter/Search → Queries both tables                          │
└──────────────────────────────────────────────────────────────────┘
```

## Data Flow

### 1. Booking Submission Flow
```
User fills form
    ↓
JavaScript validation
    ↓
AJAX POST to submit-booking.php
    ↓
PHP validation
    ↓
INSERT into booking_inquiries table
    ↓
Return JSON success response
    ↓
Show success message to user
```

### 2. Admin Panel Display Flow
```
Admin opens bookings.php
    ↓
Query bookings table (regular bookings)
    ↓
Query booking_inquiries table (form submissions)
    ↓
Merge both result sets
    ↓
Sort by date (newest first)
    ↓
Display in unified table with badges
```

### 3. Booking Confirmation Flow
```
Admin clicks "Confirm" button
    ↓
JavaScript confirmation dialog
    ↓
AJAX POST to booking-actions.php
    ↓
Check source (booking vs inquiry)
    ↓
Update appropriate table
    ↓
Calculate commissions (if applicable)
    ↓
Return success response
    ↓
Reload page to show updated status
```

## Database Schema

### booking_inquiries Table
```
┌─────────────────────┬──────────────┬─────────────────────────┐
│ Field               │ Type         │ Description             │
├─────────────────────┼──────────────┼─────────────────────────┤
│ id                  │ INT          │ Primary key             │
│ tour_id             │ INT          │ Selected tour (optional)│
│ tour_name           │ VARCHAR(255) │ Tour name               │
│ client_name         │ VARCHAR(255) │ Customer name           │
│ email               │ VARCHAR(255) │ Customer email          │
│ phone               │ VARCHAR(50)  │ Customer phone          │
│ adults              │ INT          │ Number of adults        │
│ children            │ VARCHAR(255) │ Children info           │
│ travel_dates        │ VARCHAR(255) │ Flexible date text      │
│ budget              │ VARCHAR(100) │ Budget range            │
│ categories          │ TEXT         │ Tour categories         │
│ destinations        │ TEXT         │ Desired destinations    │
│ activities          │ TEXT         │ Preferred activities    │
│ group_type          │ TEXT         │ Group type              │
│ notes               │ TEXT         │ Special requests        │
│ status              │ VARCHAR(50)  │ pending/confirmed/etc   │
│ created_at          │ TIMESTAMP    │ Submission date         │
└─────────────────────┴──────────────┴─────────────────────────┘
```

### bookings Table
```
┌─────────────────────┬──────────────┬─────────────────────────┐
│ Field               │ Type         │ Description             │
├─────────────────────┼──────────────┼─────────────────────────┤
│ id                  │ INT          │ Primary key             │
│ booking_reference   │ VARCHAR(20)  │ Unique reference        │
│ tour_id             │ INT          │ Tour ID                 │
│ customer_name       │ VARCHAR(255) │ Customer name           │
│ customer_email      │ VARCHAR(255) │ Customer email          │
│ customer_phone      │ VARCHAR(20)  │ Customer phone          │
│ travel_date         │ DATE         │ Specific travel date    │
│ participants        │ INT          │ Number of people        │
│ total_amount        │ DECIMAL      │ Total price             │
│ commission_amount   │ DECIMAL      │ Commission earned       │
│ advisor_id          │ INT          │ Assigned advisor        │
│ status              │ ENUM         │ Booking status          │
│ payment_status      │ ENUM         │ Payment status          │
│ booking_date        │ TIMESTAMP    │ Booking date            │
└─────────────────────┴──────────────┴─────────────────────────┘
```

## Key Differences

| Aspect              | booking_inquiries        | bookings                |
|---------------------|--------------------------|-------------------------|
| **Purpose**         | Initial inquiries        | Confirmed bookings      |
| **Source**          | Booking form             | Admin/Direct booking    |
| **Date Format**     | Text (flexible)          | DATE type (specific)    |
| **Detail Level**    | High (preferences, etc)  | Standard (core info)    |
| **Commission**      | Not tracked              | Tracked & calculated    |
| **Reference**       | INQ-{id}                 | BK{year}{number}        |
| **Advisor Link**    | No                       | Yes                     |

## Status Workflow

```
┌─────────────┐
│   PENDING   │ ← Initial status when submitted
└──────┬──────┘
       │
       ▼
┌─────────────┐
│  CONFIRMED  │ ← Admin confirms the inquiry
└──────┬──────┘
       │
       ▼
┌─────────────┐
│    PAID     │ ← Payment received (bookings only)
└──────┬──────┘
       │
       ▼
┌─────────────┐
│  COMPLETED  │ ← Tour completed
└─────────────┘

       OR
       
┌─────────────┐
│  CANCELLED  │ ← Cancelled by user or admin
└─────────────┘
```

## File Structure

```
foreveryoungtours/
├── pages/
│   ├── booking-form.php        → User-facing booking form
│   └── submit-booking.php      → Form submission handler
├── admin/
│   ├── bookings.php            → Admin booking list (UPDATED)
│   ├── booking-details.php     → Booking details popup (UPDATED)
│   └── booking-actions.php     → Booking actions handler (UPDATED)
├── client/
│   └── bookings.php            → Client booking list
└── config/
    └── database.php            → Database connection
```

## Integration Points

1. **Form → Database**: `submit-booking.php` inserts into `booking_inquiries`
2. **Admin Panel → Database**: `bookings.php` queries both tables
3. **Client Panel → Database**: `bookings.php` queries `booking_inquiries`
4. **Actions → Database**: `booking-actions.php` updates appropriate table

## Future Enhancements

1. **Conversion Feature**: Convert inquiry → confirmed booking
2. **Email Notifications**: Auto-send emails on status changes
3. **Payment Integration**: Link payment gateway
4. **Calendar Integration**: Show available dates
5. **Advisor Assignment**: Auto-assign to available advisors
6. **Quote Generation**: Generate PDF quotes from inquiries
