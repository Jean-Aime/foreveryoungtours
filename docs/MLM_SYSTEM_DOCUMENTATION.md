# 3-Level MLM Commission System - Complete Implementation

## Overview
The Forever Young Tours platform now has a fully functional 3-level Multi-Level Marketing (MLM) commission system for advisors and MCAs.

## Commission Structure

### Advisor Direct Sales (L1)
- **Certified Advisor**: 30% commission
- **Senior Advisor**: 35% commission  
- **Executive Advisor**: 40% commission

### Team Overrides
- **Level 2 (L2)**: 10% override on direct recruits' sales
- **Level 3 (L3)**: 5% override on L2 recruits' sales

### MCA Override
- **MCA Override**: 7.5% on all advisor sales in their assigned countries

### Performance Bonuses (Future Implementation)
- Advisor Performance Bonus Pool: 5%
- MCA Performance Bonus Pool: 5%
- Ambassador Achievement Bonus Pool: 2.5%

## How It Works

### Team Building
1. Each advisor gets a unique referral code (e.g., ADV000001)
2. Advisors share their referral code with potential recruits
3. New advisors join using: `/advisor/join-team.php?ref=ADV000001`
4. System automatically tracks L2 and L3 relationships

### Commission Calculation
When a booking is confirmed:
1. **Direct Commission (L1)**: Advisor who made the sale gets 30-40%
2. **L2 Override**: Their upline gets 10%
3. **L3 Override**: Upline's upline gets 5%
4. **MCA Override**: Assigned MCA gets 7.5%

### Example Scenario
```
Booking: $1,000 tour sale

Advisor A (Executive, made the sale):
- Direct: $400 (40%)

Advisor B (recruited Advisor A):
- L2 Override: $100 (10%)

Advisor C (recruited Advisor B):
- L3 Override: $50 (5%)

MCA (manages the country):
- MCA Override: $75 (7.5%)

Total Commissions: $625 (62.5% of sale)
```

## Database Structure

### New Tables
- `advisor_team`: Tracks team relationships
- `commission_settings`: Stores commission rates by rank

### Updated Tables
- `users`: Added `referral_code`, `referred_by_code`, `advisor_rank`, `team_l2_count`, `team_l3_count`
- `commissions`: Updated types to include `level2`, `level3`, `mca_override`

### Triggers
- `calculate_mlm_commissions`: Automatically calculates all commission levels when booking is confirmed

## Key Features Implemented

### 1. Advisor Team Page (`/advisor/team.php`)
- Display referral code
- Show L2 team members (direct recruits)
- Show L3 team members (indirect recruits)
- Track commission earnings by level
- Copy referral code functionality

### 2. Team Recruitment (`/advisor/join-team.php`)
- Public registration page with referral code
- Validates referral codes
- Automatically assigns upline relationships
- Updates team counts

### 3. Commission Tracking (`/advisor/commissions.php`)
- Breakdown by commission type (Direct, L2, L3)
- Commission history with booking details
- Paid vs Pending status
- Total earnings summary

### 4. MCA Dashboard Updates
- Track total advisors in network
- Display MCA override earnings (7.5%)
- Monitor advisor performance

## Setup Instructions

### 1. Run Database Updates
```
Visit: http://localhost/foreveryoungtours/setup_mlm_system.php
```

This will:
- Add new database columns
- Create commission settings
- Generate referral codes for existing advisors
- Set up commission calculation triggers

### 2. Test the System

#### As Advisor:
1. Login to advisor panel
2. Go to "My Team" page
3. Copy your referral code
4. Share link: `http://localhost/foreveryoungtours/advisor/join-team.php?ref=YOUR_CODE`

#### As New Recruit:
1. Visit the join link with referral code
2. Complete registration
3. Login and start selling
4. Your upline earns L2 override on your sales

#### As MCA:
1. Login to MCA panel
2. View "MCA Override" earnings
3. Monitor advisor network performance

## Commission Payment Schedule
- **Calculation**: Monthly
- **Payment Date**: 20th of each subsequent month
- **Status Flow**: Pending → Approved → Paid

## Rank Advancement (Future)
Advisors can advance ranks based on:
- Personal sales volume
- Team size
- Team sales volume
- Training completion

## API Endpoints (Future)
- `GET /api/advisor/team` - Get team structure
- `GET /api/advisor/commissions` - Get commission data
- `POST /api/advisor/recruit` - Register new team member

## Security Features
- Referral code validation
- Upline verification
- Commission calculation audit trail
- Transaction logging

## Testing Checklist
- [ ] Advisor can view referral code
- [ ] New advisor can join using referral code
- [ ] L2 relationship is created
- [ ] L3 relationship is created (when L2 recruits)
- [ ] Direct commission calculated correctly
- [ ] L2 override calculated (10%)
- [ ] L3 override calculated (5%)
- [ ] MCA override calculated (7.5%)
- [ ] Commission history displays correctly
- [ ] Team counts update automatically

## Troubleshooting

### Referral Code Not Generated
```sql
UPDATE users 
SET referral_code = CONCAT('ADV', LPAD(id, 6, '0'))
WHERE role = 'advisor' AND referral_code IS NULL;
```

### Commission Not Calculating
Check trigger exists:
```sql
SHOW TRIGGERS LIKE 'bookings';
```

### Team Counts Not Updating
Manually recalculate:
```sql
UPDATE users u
SET team_l2_count = (SELECT COUNT(*) FROM users WHERE upline_id = u.id),
    team_l3_count = (SELECT COUNT(*) FROM users u2 WHERE u2.upline_id IN (SELECT id FROM users WHERE upline_id = u.id))
WHERE role = 'advisor';
```

## Future Enhancements
1. Performance bonus pool distribution
2. Rank advancement automation
3. Team genealogy tree visualization
4. Commission withdrawal requests
5. Real-time commission notifications
6. Mobile app for team management
7. Gamification and leaderboards
8. Training completion tracking for rank advancement

## Support
For issues or questions, contact the development team.

---
**Last Updated**: November 2024
**Version**: 1.0
