# Forever Young Tours - Store Management System Setup Guide

## Overview
This document provides complete instructions for setting up and managing the Travel Store system with database integration, category filtering, and admin management.

---

## 📋 Table of Contents
1. [Database Setup](#database-setup)
2. [Admin Access](#admin-access)
3. [Managing Products](#managing-products)
4. [Managing Categories](#managing-categories)
5. [Features](#features)
6. [SQL Queries Reference](#sql-queries-reference)

---

## 🗄️ Database Setup

### Step 1: Run the Database Setup Script

1. **Open phpMyAdmin**
   - Navigate to: `http://localhost/phpmyadmin`
   - Select your database: `forevveryoungtours`

2. **Import the SQL File**
   - Click on the **SQL** tab
   - Open the file: `database/store_setup.sql`
   - Copy all contents and paste into the SQL query box
   - Click **Go** to execute

3. **Verify Tables Created**
   The following tables should now exist:
   - `store_categories` - Product categories
   - `store_products` - All products
   - `store_orders` - Customer orders
   - `store_order_items` - Order line items
   - `store_reviews` - Product reviews
   - `store_wishlist` - Customer wishlists
   - `store_settings` - Store configuration

### Step 2: Verify Sample Data

After running the script, you should have:
- **6 Categories**: Camping, Hiking, Accessories, Safety, Clothing, Electronics
- **8 Sample Products**: Tent, Boots, Bottle, Backpack, Pillow, First Aid Kit, Stove, Trekking Poles

---

## 👤 Admin Access

### Accessing the Admin Panel

1. **Login as Super Admin**
   - URL: `http://localhost:8000/auth/login.php`
   - Use your super admin credentials

2. **Navigate to Store Management**
   - URL: `http://localhost:8000/admin/store-management.php`
   - Or find it in the admin navigation menu

### Admin Dashboard Features

The admin panel provides:
- **Statistics Dashboard**: Total products, active products, categories, low stock alerts
- **Product Management**: Add, edit, delete products
- **Category Management**: Manage product categories
- **Search & Filter**: Find products quickly
- **Bulk Actions**: Manage multiple products

---

## 🛍️ Managing Products

### Adding a New Product

1. Click **"Add Product"** button in the admin panel
2. Fill in the required fields:
   - **Product Name*** (required)
   - **Category*** (select from dropdown)
   - **SKU*** (unique identifier)
   - **Price*** (selling price)
   - **Original Price** (for showing discounts)
   - **Stock Quantity*** (number of units)
   - **Stock Status*** (in_stock/low_stock/out_of_stock)
   - **Short Description** (brief product summary)
   - **Full Description** (detailed information)
   - **Image URL*** (full URL to product image)
   - **Status*** (active/inactive/draft)
   - **Featured Product** (checkbox)
   - **On Sale** (checkbox)

3. Click **"Add Product"** to save

### Editing a Product

1. Find the product in the products table
2. Click the **Edit** icon (pencil)
3. Update the fields as needed
4. Click **"Update Product"** to save changes

### Deleting a Product

1. Find the product in the products table
2. Click the **Delete** icon (trash)
3. Confirm the deletion

### Product Fields Explained

| Field | Description | Required |
|-------|-------------|----------|
| Name | Product display name | Yes |
| Category | Product category | Yes |
| SKU | Stock Keeping Unit (unique) | Yes |
| Price | Current selling price | Yes |
| Original Price | Pre-discount price | No |
| Stock Quantity | Number of units available | Yes |
| Stock Status | Availability status | Yes |
| Short Description | Brief summary (max 500 chars) | No |
| Description | Full product details | No |
| Image URL | Product image URL | Yes |
| Status | Publication status | Yes |
| Featured | Show in featured section | No |
| On Sale | Display sale badge | No |

---

## 🏷️ Managing Categories

### Adding a Category

```sql
INSERT INTO store_categories (name, slug, description, color, display_order)
VALUES ('Category Name', 'category-slug', 'Description', '#3B82F6', 1);
```

### Editing a Category

```sql
UPDATE store_categories 
SET name = 'New Name', description = 'New Description'
WHERE id = 1;
```

### Deleting a Category

**Note**: Cannot delete categories with existing products

```sql
DELETE FROM store_categories WHERE id = 1;
```

---

## ✨ Features

### Customer-Facing Features

1. **Category Filtering**
   - Click category buttons to filter products
   - "All Products" shows everything
   - Smooth animations on filter

2. **Product Sorting**
   - Sort by: Featured, Price (Low/High), Rating, Newest
   - Real-time sorting without page reload

3. **Product Display**
   - 4 products per row on desktop
   - Responsive grid (1 col mobile, 2 col tablet, 4 col desktop)
   - Product cards show:
     - Product image
     - Category badge
     - Star rating
     - Price (with strikethrough for discounts)
     - Discount percentage badge
     - Stock status badge
     - Add to Cart button
     - Quick View button
     - Wishlist button

4. **Interactive Elements**
   - Hover effects on product cards
   - Add to Cart with loading animation
   - Wishlist toggle (heart icon)
   - Quick view modal

### Admin Features

1. **Dashboard Statistics**
   - Total products count
   - Active products count
   - Total categories
   - Low stock alerts

2. **Product Management**
   - Add/Edit/Delete products
   - Search products by name/SKU
   - Filter by status (active/inactive/draft)
   - View product details in table

3. **Category Management**
   - Create custom categories
   - Set category colors
   - Define display order
   - Manage category status

---

## 📊 SQL Queries Reference

### Useful Queries for Management

#### View All Products with Categories
```sql
SELECT p.*, c.name as category_name 
FROM store_products p 
LEFT JOIN store_categories c ON p.category_id = c.id 
ORDER BY p.created_at DESC;
```

#### Find Low Stock Products
```sql
SELECT name, stock_quantity, sku 
FROM store_products 
WHERE stock_quantity < 10 AND status = 'active';
```

#### Get Products by Category
```sql
SELECT p.* 
FROM store_products p
JOIN store_categories c ON p.category_id = c.id
WHERE c.slug = 'camping' AND p.status = 'active';
```

#### Update Product Stock
```sql
UPDATE store_products 
SET stock_quantity = 100, stock_status = 'in_stock'
WHERE id = 1;
```

#### Set Product as Featured
```sql
UPDATE store_products 
SET is_featured = 1 
WHERE id = 1;
```

#### Get Featured Products
```sql
SELECT * FROM store_products 
WHERE is_featured = 1 AND status = 'active'
ORDER BY created_at DESC;
```

#### Get Products on Sale
```sql
SELECT * FROM store_products 
WHERE is_on_sale = 1 AND status = 'active';
```

#### Update Multiple Products Status
```sql
UPDATE store_products 
SET status = 'active' 
WHERE category_id = 1;
```

#### Get Product Statistics
```sql
SELECT 
    COUNT(*) as total_products,
    SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_products,
    SUM(CASE WHEN stock_quantity < 10 THEN 1 ELSE 0 END) as low_stock_products,
    SUM(CASE WHEN is_featured = 1 THEN 1 ELSE 0 END) as featured_products
FROM store_products;
```

#### Get Category with Product Count
```sql
SELECT c.*, COUNT(p.id) as product_count
FROM store_categories c
LEFT JOIN store_products p ON c.id = p.category_id
GROUP BY c.id
ORDER BY c.display_order ASC;
```

---

## 🔧 Troubleshooting

### Products Not Showing

1. **Check Database Connection**
   ```php
   // In store.php, check if $products array has data
   var_dump($products);
   ```

2. **Verify Product Status**
   ```sql
   SELECT COUNT(*) FROM store_products WHERE status = 'active';
   ```

3. **Check Category Assignment**
   ```sql
   SELECT p.name, c.name as category 
   FROM store_products p 
   LEFT JOIN store_categories c ON p.category_id = c.id;
   ```

### Filtering Not Working

1. **Check JavaScript Console** for errors
2. **Verify data attributes** on product cards
3. **Ensure category slugs match** between database and buttons

### Images Not Loading

1. **Check image URLs** are valid and accessible
2. **Use full URLs** (e.g., `https://images.unsplash.com/...`)
3. **Verify image paths** for local images

---

## 📱 Testing the Store

### Test Checklist

- [ ] Database tables created successfully
- [ ] Sample products visible on store page
- [ ] Category filtering works correctly
- [ ] Product sorting functions properly
- [ ] Add to Cart button shows animation
- [ ] Wishlist toggle works
- [ ] Admin panel accessible
- [ ] Can add new products
- [ ] Can edit existing products
- [ ] Can delete products
- [ ] Search functionality works
- [ ] Status filter works
- [ ] Responsive design on mobile

---

## 🚀 Next Steps

1. **Customize Products**
   - Add your own products through admin panel
   - Upload product images
   - Set appropriate prices

2. **Configure Categories**
   - Add/remove categories as needed
   - Set category colors
   - Define display order

3. **Implement Shopping Cart**
   - Create cart session management
   - Add checkout process
   - Integrate payment gateway

4. **Add Order Management**
   - Create order processing system
   - Email notifications
   - Order tracking

5. **Enhance Features**
   - Product reviews system
   - Wishlist persistence
   - Product recommendations
   - Inventory management

---

## 📞 Support

For issues or questions:
- Check the troubleshooting section
- Review SQL queries for data verification
- Ensure all files are in correct locations
- Verify database connection settings

---

## 📄 File Locations

- **Store Page**: `/pages/store.php`
- **Admin Panel**: `/admin/store-management.php`
- **Actions Handler**: `/admin/store-actions.php`
- **Database Setup**: `/database/store_setup.sql`
- **Database Config**: `/config/database.php`

---

**Last Updated**: November 7, 2025
**Version**: 1.0.0
