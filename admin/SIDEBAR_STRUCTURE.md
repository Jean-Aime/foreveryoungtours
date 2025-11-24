# Admin Sidebar - Complete Structure

## 📊 Full Navigation Menu

```
┌─────────────────────────────────────┐
│   iForYoungTours - Admin Panel      │
└─────────────────────────────────────┘

🏠 MAIN
├── Dashboard (index.php)

📋 OPERATIONS
├── Bookings (bookings.php)
├── Inquiries (inquiries.php)
├── Client Packages (client-packages.php)
├── Commissions (commission-management.php)
├── Booking Engine (booking-engine-management.php)
├── Engine Orders (booking-engine-orders.php)
└── Store Management (store-management.php)

📝 CONTENT
├── Tours (tours.php)
├── Featured Tours (featured-tours.php)
├── Continents (manage-continents.php)
├── Countries (manage-countries.php)
├── Destinations (destinations.php)
├── Blog / Experiences (blog-management.php)
└── Client Stories (client-stories.php)

👥 USERS ⭐ (All Well-Designed)
├── All Users (users.php) ✅
├── MCAs (mca-management.php) ✅
│   └── → MCA Dashboard (mca-dashboard.php?id=X) ✅
├── Advisors (advisor-management.php) ✅
│   └── → Advisor Dashboard (advisor-dashboard.php?id=X) ✅
└── Pending Approvals (pending-approvals.php) 🔴 [Badge: Count] ✅

📊 ANALYTICS
├── Analytics (analytics.php)
└── Reports (reports.php)

⚙️ SYSTEM
├── Tour Scheduler (tour-scheduler.php)
├── Partners (partners.php)
├── Training (training-modules.php)
├── Notifications (notifications.php)
└── Settings (settings.php)
```

## 🎯 User Management Section - Detailed

### 1. All Users (users.php)
**Purpose**: Manage all system users across all roles
**Features**:
- View users grouped by role (Super Admin, MCA, Advisor, Client)
- Add new users with role assignment
- Toggle user status (active/inactive)
- Delete users (except super admins)
- View sponsor hierarchy and team counts

**Statistics Displayed**:
- Total Users
- MCAs Count
- Advisors Count
- Clients Count
- Active Users Count

**Actions Available**:
- ➕ Add New User
- ✅ Activate User
- ❌ Deactivate User
- 🗑️ Delete User

---

### 2. MCAs (mca-management.php)
**Purpose**: Manage Master Country Advisors and country assignments
**Features**:
- View all MCAs with assigned country counts
- Assign countries to MCAs
- Remove country assignments
- View MCA performance dashboard
- Regional grouping of assignments

**Statistics Displayed**:
- Total MCAs
- Countries Assigned
- Unassigned Countries

**Actions Available**:
- 👁️ View Dashboard → mca-dashboard.php
- ➕ Assign Country
- ❌ Remove Assignment

**Sub-Page**: MCA Dashboard (mca-dashboard.php?id=X)
- Team Advisors count and performance
- Total Bookings from team
- Team Revenue
- MCA Commissions earned
- List of assigned countries
- Team advisor details with sales

---

### 3. Advisors (advisor-management.php)
**Purpose**: Manage travel advisors, KYC, and ranks
**Features**:
- View all advisors with performance metrics
- Approve/reject KYC for new advisors
- Change advisor ranks (Certified/Senior/Executive)
- Toggle advisor status
- View individual advisor dashboard
- Track team size and sales

**Statistics Displayed**:
- Total Advisors
- Active Advisors
- Pending KYC
- Executive Rank Count

**Advisor Ranks**:
- 🥉 Certified (30% commission)
- 🥈 Senior (35% commission)
- 🥇 Executive (40% commission)

**Actions Available**:
- ✅ Approve KYC (for inactive advisors)
- ❌ Reject KYC (for inactive advisors)
- 👁️ View Dashboard → advisor-dashboard.php
- 🔄 Toggle Status
- 🏅 Change Rank

**Sub-Page**: Advisor Dashboard (advisor-dashboard.php?id=X)
- Total Bookings
- Confirmed Bookings
- Total Sales
- Commissions Earned
- Recent bookings list (last 10)
- Commission history (last 10)

---

### 4. Pending Approvals (pending-approvals.php)
**Purpose**: Approve or reject new advisor/MCA registrations
**Features**:
- View all pending users (status='inactive')
- See recruiter/sponsor information
- Approve to activate account
- Reject to suspend account
- Notification badge shows pending count

**Badge Display**: 🔴 Shows count of pending approvals in sidebar

**Actions Available**:
- ✅ Approve (sets status='active')
- ❌ Reject (sets status='suspended')

---

## 🎨 Design Elements

### Status Badges:
- 🟢 **Active** - Green badge
- 🟡 **Inactive/Pending** - Yellow badge
- 🔴 **Suspended** - Red badge

### Rank Badges:
- 🔵 **Certified** - Blue badge
- 🟦 **Senior** - Info badge
- 🟢 **Executive** - Green badge

### Action Buttons:
- 👁️ **View** - Info button (blue)
- ✅ **Approve** - Success button (green)
- ❌ **Reject/Remove** - Danger button (red)
- 🔄 **Toggle** - Secondary button (gray)
- ➕ **Add/Assign** - Primary button (gold)
- 🏅 **Change Rank** - Primary outline button

### Icons Used:
- 👥 `fa-users` - All Users
- 👑 `fa-user-crown` - MCAs
- 👔 `fa-user-tie` - Advisors
- ⏰ `fa-user-clock` - Pending Approvals
- 📊 `fa-chart-bar` - Statistics
- 💰 `fa-dollar-sign` - Revenue/Sales
- 🪙 `fa-coins` - Commissions
- ✅ `fa-check-circle` - Confirmed/Active
- 📅 `fa-calendar-check` - Bookings
- 🌍 `fa-globe` - Countries

---

## 🔗 Navigation Paths

### From Sidebar:
```
Users Section
│
├─ All Users → users.php
│  └─ Add User Modal
│
├─ MCAs → mca-management.php
│  ├─ View Dashboard → mca-dashboard.php?id=X
│  └─ Assign Country Modal
│
├─ Advisors → advisor-management.php
│  ├─ View Dashboard → advisor-dashboard.php?id=X
│  └─ Change Rank Modal
│
└─ Pending Approvals → pending-approvals.php
   └─ Approve/Reject Actions
```

### Breadcrumb Examples:
- `Admin > Users > All Users`
- `Admin > Users > MCAs > MCA Dashboard`
- `Admin > Users > Advisors > Advisor Dashboard`
- `Admin > Users > Pending Approvals`

---

## 📱 Responsive Behavior

### Desktop (≥768px):
- Sidebar always visible on left
- Full width tables
- 4-column statistics cards

### Tablet (768px - 1024px):
- Collapsible sidebar
- 2-column statistics cards
- Scrollable tables

### Mobile (<768px):
- Hamburger menu
- Overlay sidebar
- 1-column statistics cards
- Horizontal scroll tables

---

## 🔐 Access Control

All user management pages require:
- ✅ Active session
- ✅ Role: `super_admin`
- ✅ Authentication check via `checkAuth('super_admin')`

Unauthorized access redirects to login page.

---

## 📊 Database Queries

### Common Queries Used:

**Get All Users by Role**:
```sql
SELECT * FROM users WHERE role = 'advisor' ORDER BY created_at DESC
```

**Get User with Team Stats**:
```sql
SELECT u.*, 
       COUNT(DISTINCT t.id) as team_count,
       COUNT(DISTINCT b.id) as booking_count,
       SUM(b.total_amount) as total_sales
FROM users u
LEFT JOIN users t ON t.sponsor_id = u.id
LEFT JOIN bookings b ON b.advisor_id = u.id
WHERE u.id = ?
```

**Get Pending Approvals**:
```sql
SELECT * FROM users 
WHERE status = 'inactive' AND role IN ('advisor','mca')
ORDER BY created_at DESC
```

**Get MCA Assignments**:
```sql
SELECT ma.*, c.name as country_name, u.first_name, u.last_name
FROM mca_assignments ma
JOIN countries c ON ma.country_id = c.id
JOIN users u ON ma.mca_id = u.id
WHERE ma.status = 'active'
```

---

## ✅ Completion Status

| Page | Design | Functionality | Sidebar Link | Dashboard Link |
|------|--------|---------------|--------------|----------------|
| users.php | ✅ | ✅ | ✅ | N/A |
| mca-management.php | ✅ | ✅ | ✅ | ✅ |
| mca-dashboard.php | ✅ | ✅ | N/A | ✅ |
| advisor-management.php | ✅ | ✅ | ✅ | ✅ |
| advisor-dashboard.php | ✅ | ✅ | N/A | ✅ |
| pending-approvals.php | ✅ | ✅ | ✅ | N/A |

**All user management pages are complete and properly integrated!** ✨

---

**Last Updated**: January 2025
