# 🔒 CLIENT OWNERSHIP PROTECTION - SETUP GUIDE

## ✅ QUICK SETUP (5 Minutes)

### **Step 1: Run Database Migration**

Open phpMyAdmin or MySQL command line:

```bash
mysql -u root -p foreveryoungtours < database/client_portal_system.sql
```

Or in phpMyAdmin:
1. Select your database
2. Click "Import"
3. Choose `database/client_portal_system.sql`
4. Click "Go"

### **Step 2: Test the System**

**As Admin:**
1. Login: `admin@foreveryoungtours.com`
2. Go to: `admin/create-client-portal.php`
3. Create test portal:
   - Name: Test Client
   - Email: test@example.com
   - Phone: +250788000000
4. Copy the portal link
5. Open link in new tab - should work!

**As Advisor:**
1. Login as advisor
2. Go to: `advisor/create-client-portal.php`
3. Create portal for your client
4. Client is now LOCKED to you 🔒

### **Step 3: Test Protection**

1. Create portal as Advisor
2. Logout
3. Login as Admin
4. Try to create portal with same email
5. System should BLOCK and show: "Client belongs to [Advisor Name]"
6. ✅ Protection working!

---

## 🎯 HOW TO USE

### **For Advisors:**

**When you get a new client:**

1. Go to `advisor/create-client-portal.php`
2. Enter client details (name, email, phone)
3. Click "Check Client" first
4. If not found, fill form and select tours
5. Click "Create Portal & Lock Client"
6. Copy portal link
7. Send to client via WhatsApp/SMS

**Your client is now PROTECTED!** Even if they contact the company directly, you keep the commission.

### **For Admin:**

**When client contacts on Instagram/Facebook:**

1. Get client email/phone
2. Go to `admin/create-client-portal.php`
3. Click "Check Client"
4. If exists → System shows owner advisor
5. If not exists → Create portal and assign to advisor

---

## 🔐 PROTECTION RULES

1. **First Touch Wins** - Whoever creates portal first owns client
2. **Email/Phone Check** - System checks both identifiers
3. **Blocked Duplicates** - Cannot create duplicate portals
4. **30-Day Expiry** - Inactive portals expire
5. **Admin Override** - Only super admin can transfer

---

## 📱 CLIENT EXPERIENCE

Client receives link: `foreveryoungtours.com/portal.php?code=JD-2024-001`

They see:
- Welcome message
- Their advisor details
- Selected tours
- Booking buttons
- Chat with advisor

---

## ✅ VERIFICATION CHECKLIST

- [ ] Database tables created
- [ ] Admin can create portals
- [ ] Advisor can create portals
- [ ] Portal links work
- [ ] Duplicate detection works
- [ ] Protection blocks duplicates
- [ ] "My Clients" page shows clients

---

## 🚀 YOU'RE DONE!

The system is now protecting your advisors' commissions automatically!

**Test it thoroughly before going live.**
