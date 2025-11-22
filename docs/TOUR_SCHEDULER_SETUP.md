# Tour Scheduler System - Setup Complete ✅

## What Was Fixed

### 1. **Admin Tour Scheduler** (`admin/tour-scheduler.php`)
- Fixed tours query to use `name` instead of `title` column
- Admin can now schedule tours on specific dates
- Shows available slots and booked slots
- Click on scheduled tour to delete it

### 2. **Public Calendar** (`index.php`)
- Changed from reading `bookings` table to `tour_schedules` table
- Now displays tours that admin has scheduled
- Shows available slots remaining
- Green dots indicate dates with scheduled tours

### 3. **API Endpoints**
- **`admin/tour-scheduler-api.php`**: Create and delete tour schedules
- **`admin/get_scheduled_tours.php`**: Fetch scheduled tours for a specific date

## How It Works

### Admin Side:
1. Admin logs into `/admin/tour-scheduler.php`
2. Clicks on any date in the calendar
3. Selects a tour, sets slots, price, and notes
4. Clicks "Schedule Tour"
5. Tour appears on that date in the calendar

### Client Side:
1. Client visits homepage (`index.php`)
2. Sees calendar with green dots on dates with scheduled tours
3. Clicks on a date
4. Sees all available tours for that date with:
   - Tour name
   - Destination
   - Duration
   - Available slots remaining
   - Price
5. Clicks on a tour to open inquiry/booking modal

## Database Table Used

```sql
tour_schedules
├── id
├── tour_id (links to tours table)
├── scheduled_date (the date tour is scheduled)
├── end_date (optional)
├── available_slots (total slots)
├── booked_slots (slots already booked)
├── price (price for this schedule)
├── status (active/full/cancelled/completed)
├── notes
├── created_by
└── created_at
```

## Testing Steps

1. **Login as Admin**
   - Go to `/admin/login.php`
   - Email: `admin@foreveryoungtours.com`
   - Password: `password`

2. **Schedule a Tour**
   - Go to `/admin/tour-scheduler.php`
   - Click on a future date
   - Select a tour (e.g., "Maasai Mara Safari Adventure")
   - Set available slots (e.g., 20)
   - Price will auto-fill from tour
   - Click "Schedule Tour"

3. **View on Homepage**
   - Go to `/index.php`
   - Scroll to "Tour Departure Calendar" section
   - See green dot on the date you scheduled
   - Click the date
   - See the scheduled tour appear below

4. **Book the Tour**
   - Click on the tour card
   - Inquiry modal opens
   - Client can fill form and submit

## Features

✅ Admin schedules tours on specific dates
✅ Tours appear on public calendar
✅ Shows available slots
✅ Prevents overbooking (only shows tours with available slots)
✅ Real-time updates
✅ Delete scheduled tours
✅ Multiple tours can be scheduled on same date

## Next Steps (Optional Enhancements)

- Auto-update `booked_slots` when booking is confirmed
- Send notifications when slots are running low
- Bulk schedule tours for multiple dates
- Recurring schedules (weekly/monthly)
- Price variations by season
