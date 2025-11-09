# Shopping Cart & Payment System - Implementation Guide

## ✅ Overview

Complete e-commerce system with shopping cart, wishlist, checkout, and payment integration for both store products and tour bookings.

---

## 📁 Files Created

### **Database:**
1. ✅ `database/cart_payment_setup.sql` - Complete database schema

### **Backend:**
1. ✅ `includes/cart-actions.php` - Cart & wishlist API endpoints

### **Modified:**
1. ✅ `pages/store.php` - Integrated cart & wishlist functionality

---

## 🗄️ Database Schema

### **Shopping Cart Table:**
```sql
shopping_cart
- id, user_id, product_id, quantity
- created_at, updated_at
- UNIQUE(user_id, product_id)
```

### **Wishlist Table:**
```sql
wishlist
- id, user_id, product_id
- created_at
- UNIQUE(user_id, product_id)
```

### **Orders Table:**
```sql
orders
- id, order_number, user_id, order_type (store/tour)
- customer_name, email, phone
- shipping_address (full address fields)
- subtotal, tax, shipping_fee, discount, total_amount
- payment_method, payment_status, payment_id
- order_status, notes
- timestamps (created, updated, paid, shipped, delivered)
```

### **Order Items Table:**
```sql
order_items
- id, order_id, product_id
- product_name, product_sku, quantity
- unit_price, total_price
```

### **Tour Order Details:**
```sql
tour_order_details
- id, order_id, tour_id, tour_name
- booking_date, number_of_travelers
- traveler_details (JSON), special_requests
```

### **Payment Transactions:**
```sql
payment_transactions
- id, order_id, transaction_id
- payment_gateway, amount, currency
- status, gateway_response (JSON)
```

### **User Addresses:**
```sql
user_addresses
- id, user_id, address_type
- full_name, phone, address fields
- is_default
```

### **Discount Coupons:**
```sql
discount_coupons
- id, code, description
- discount_type (percentage/fixed), discount_value
- min_purchase, max_discount
- usage_limit, used_count
- valid_from, valid_until, is_active
```

---

## 🔧 API Endpoints

### **Cart Actions** (`includes/cart-actions.php`)

#### **Add to Cart:**
```javascript
POST /includes/cart-actions.php
action=add_to_cart
product_id=123
quantity=1

Response:
{
    "success": true,
    "message": "Product added to cart",
    "cart_count": 3
}
```

#### **Update Cart:**
```javascript
POST /includes/cart-actions.php
action=update_cart
cart_id=456
quantity=2
```

#### **Remove from Cart:**
```javascript
POST /includes/cart-actions.php
action=remove_from_cart
cart_id=456
```

#### **Get Cart:**
```javascript
POST /includes/cart-actions.php
action=get_cart

Response:
{
    "success": true,
    "items": [...],
    "total": 299.99,
    "count": 3
}
```

#### **Get Cart Count:**
```javascript
POST /includes/cart-actions.php
action=get_cart_count

Response:
{
    "success": true,
    "count": 3
}
```

### **Wishlist Actions:**

#### **Add to Wishlist:**
```javascript
POST /includes/cart-actions.php
action=add_to_wishlist
product_id=123
```

#### **Remove from Wishlist:**
```javascript
POST /includes/cart-actions.php
action=remove_from_wishlist
product_id=123
```

#### **Get Wishlist:**
```javascript
POST /includes/cart-actions.php
action=get_wishlist

Response:
{
    "success": true,
    "items": [...],
    "count": 5
}
```

---

## 🛒 Shopping Cart Features

### **Functionality:**
- ✅ Add products to cart
- ✅ Update quantities
- ✅ Remove items
- ✅ Stock validation
- ✅ Duplicate prevention
- ✅ User authentication required
- ✅ Real-time cart count updates

### **Validation:**
- Product exists and is active
- Sufficient stock available
- User is logged in
- Quantity is valid

### **User Experience:**
- Loading state while adding
- Success confirmation
- Error messages
- Cart count badge updates
- Smooth animations

---

## ❤️ Wishlist Features

### **Functionality:**
- ✅ Add/remove products
- ✅ Toggle heart icon
- ✅ Duplicate prevention
- ✅ User authentication required

### **Visual Feedback:**
- Heart fills when added
- Heart empties when removed
- Color changes (gray → red)
- Smooth transitions

---

## 💳 Payment Integration (Next Steps)

### **Supported Payment Methods:**
1. **Credit Card** (Stripe)
2. **PayPal**
3. **Bank Transfer**
4. **Cash on Delivery**

### **Payment Flow:**
```
Cart → Checkout → Payment → Order Confirmation
```

### **Required Pages (To Be Created):**
1. `pages/cart.php` - Shopping cart page
2. `pages/checkout.php` - Checkout form
3. `pages/payment.php` - Payment processing
4. `pages/order-confirmation.php` - Success page
5. `pages/my-orders.php` - Order history

---

## 👤 Client Account Panel (To Be Created)

### **Dashboard Features:**
1. **Overview**
   - Recent orders
   - Wishlist count
   - Account balance

2. **Orders**
   - Order history
   - Order tracking
   - Download invoices

3. **Wishlist**
   - Saved products
   - Move to cart
   - Remove items

4. **Addresses**
   - Saved addresses
   - Add/edit/delete
   - Set default

5. **Profile**
   - Personal information
   - Change password
   - Email preferences

6. **Tour Bookings**
   - Upcoming tours
   - Past tours
   - Booking details

---

## 🎯 Tour Booking Integration (To Be Created)

### **Tour Booking Flow:**
```
Select Tour → Choose Date → Add Travelers → Checkout → Payment
```

### **Database Structure:**
- Uses same `orders` table with `order_type='tour'`
- `tour_order_details` table for tour-specific data
- Traveler information stored as JSON

### **Features:**
- Multiple travelers per booking
- Special requests
- Date selection
- Price calculation
- Payment integration

---

## 🔐 Security Features

### **Authentication:**
- ✅ User must be logged in
- ✅ Session validation
- ✅ User ID verification

### **Data Protection:**
- ✅ PDO prepared statements
- ✅ SQL injection prevention
- ✅ XSS protection (htmlspecialchars)
- ✅ CSRF protection (to be added)

### **Validation:**
- ✅ Stock availability
- ✅ Product status check
- ✅ Quantity limits
- ✅ Duplicate prevention

---

## 📊 Order Status Flow

### **Store Orders:**
```
pending → confirmed → processing → shipped → delivered
                                  ↓
                              cancelled
```

### **Tour Bookings:**
```
pending → confirmed → completed
                  ↓
              cancelled
```

### **Payment Status:**
```
pending → processing → completed
                   ↓
               failed/refunded
```

---

## 🎨 Frontend Integration

### **Store Page Updates:**

#### **Add to Cart Button:**
```javascript
- Click button
- Check if logged in (redirect if not)
- Show loading state
- AJAX request to add_to_cart
- Update cart count
- Show success message
- Reset button state
```

#### **Wishlist Button:**
```javascript
- Click heart icon
- Check if logged in (redirect if not)
- Toggle wishlist state
- AJAX request to add/remove
- Update icon (fill/unfill)
- Change color (gray/red)
```

---

## 📱 Responsive Design

### **Cart Badge:**
- Shows item count
- Updates in real-time
- Hidden when empty
- Visible on all devices

### **Buttons:**
- Touch-friendly on mobile
- Clear visual feedback
- Loading states
- Error handling

---

## 🚀 Next Implementation Steps

### **Phase 1: Cart Page** ✅ Database Ready
- [ ] Create `pages/cart.php`
- [ ] Display cart items
- [ ] Update quantities
- [ ] Remove items
- [ ] Calculate totals
- [ ] Apply coupons

### **Phase 2: Checkout** ✅ Database Ready
- [ ] Create `pages/checkout.php`
- [ ] Shipping address form
- [ ] Billing information
- [ ] Order summary
- [ ] Payment method selection

### **Phase 3: Payment Integration**
- [ ] Stripe integration
- [ ] PayPal integration
- [ ] Payment processing
- [ ] Order creation
- [ ] Email notifications

### **Phase 4: Client Dashboard**
- [ ] Create `pages/client-dashboard.php`
- [ ] Order history
- [ ] Wishlist management
- [ ] Address book
- [ ] Profile settings

### **Phase 5: Tour Booking**
- [ ] Update tour pages
- [ ] Add booking form
- [ ] Integrate with orders
- [ ] Payment processing
- [ ] Confirmation emails

---

## 💾 Sample Data

### **Discount Coupons:**
```sql
WELCOME10  - 10% off first order (min $50)
SUMMER25   - 25% off (min $100)
SAVE50     - $50 off orders over $200
FREESHIP   - Free shipping
```

---

## 🔄 Data Flow

### **Add to Cart:**
```
User clicks "Add to Cart"
    ↓
Check authentication
    ↓
Validate product & stock
    ↓
Check if already in cart
    ↓
Update quantity OR Insert new
    ↓
Return success + cart count
    ↓
Update UI
```

### **Checkout:**
```
User proceeds to checkout
    ↓
Load cart items
    ↓
Enter shipping info
    ↓
Select payment method
    ↓
Process payment
    ↓
Create order record
    ↓
Create order items
    ↓
Clear cart
    ↓
Send confirmation email
    ↓
Redirect to success page
```

---

## ✅ Current Status

### **Completed:**
- ✅ Database schema created
- ✅ Cart API endpoints
- ✅ Wishlist API endpoints
- ✅ Store page integration
- ✅ Add to cart functionality
- ✅ Wishlist functionality
- ✅ Stock validation
- ✅ User authentication checks
- ✅ Real-time updates

### **In Progress:**
- 🔄 Cart page
- 🔄 Checkout page
- 🔄 Payment integration
- 🔄 Client dashboard
- 🔄 Tour booking integration

### **Pending:**
- ⏳ Email notifications
- ⏳ Order tracking
- ⏳ Invoice generation
- ⏳ Admin order management
- ⏳ Analytics & reporting

---

## 📝 Usage Instructions

### **For Users:**

1. **Browse Products** on store page
2. **Click "Add to Cart"** (login required)
3. **Click Heart Icon** to add to wishlist
4. **View Cart** (cart icon in header)
5. **Proceed to Checkout**
6. **Enter Shipping Info**
7. **Select Payment Method**
8. **Complete Payment**
9. **Receive Confirmation**

### **For Developers:**

1. **Run SQL Script:**
   ```sql
   source database/cart_payment_setup.sql
   ```

2. **Test Cart API:**
   ```javascript
   fetch('/includes/cart-actions.php', {
       method: 'POST',
       body: 'action=add_to_cart&product_id=1&quantity=1'
   })
   ```

3. **Check Database:**
   ```sql
   SELECT * FROM shopping_cart;
   SELECT * FROM wishlist;
   ```

---

**Status:** ✅ PHASE 1 COMPLETE - Cart & Wishlist functionality fully integrated!

**Next:** Create cart page, checkout flow, and payment integration.
