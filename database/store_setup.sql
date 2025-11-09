-- ============================================
-- Forever Young Tours - Store Management System
-- Database Setup Script
-- ============================================

-- Table: store_categories
-- Purpose: Store product categories
CREATE TABLE IF NOT EXISTS `store_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL UNIQUE,
  `description` text DEFAULT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `color` varchar(7) DEFAULT '#3B82F6',
  `status` enum('active','inactive') DEFAULT 'active',
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_slug` (`slug`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert default categories
INSERT INTO `store_categories` (`name`, `slug`, `description`, `icon`, `color`, `display_order`) VALUES
('Camping', 'camping', 'Camping gear and equipment', 'tent', '#10B981', 1),
('Hiking', 'hiking', 'Hiking boots, poles, and accessories', 'mountain', '#F59E0B', 2),
('Accessories', 'accessories', 'Travel accessories and essentials', 'bag', '#3B82F6', 3),
('Safety', 'safety', 'Safety equipment and first aid', 'shield', '#EF4444', 4),
('Clothing', 'clothing', 'Outdoor and travel clothing', 'shirt', '#8B5CF6', 5),
('Electronics', 'electronics', 'Travel electronics and gadgets', 'phone', '#06B6D4', 6);

-- ============================================

-- Table: store_products
-- Purpose: Store all products
CREATE TABLE IF NOT EXISTS `store_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL UNIQUE,
  `description` text DEFAULT NULL,
  `short_description` varchar(500) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `original_price` decimal(10,2) DEFAULT NULL,
  `discount_percentage` int(11) DEFAULT 0,
  `image_url` varchar(500) DEFAULT NULL,
  `gallery_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gallery_images`)),
  `stock_quantity` int(11) DEFAULT 0,
  `stock_status` enum('in_stock','out_of_stock','low_stock') DEFAULT 'in_stock',
  `sku` varchar(100) DEFAULT NULL UNIQUE,
  `rating` decimal(3,2) DEFAULT 0.00,
  `review_count` int(11) DEFAULT 0,
  `is_featured` tinyint(1) DEFAULT 0,
  `is_on_sale` tinyint(1) DEFAULT 0,
  `tags` varchar(500) DEFAULT NULL,
  `specifications` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`specifications`)),
  `status` enum('active','inactive','draft') DEFAULT 'active',
  `views` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_category` (`category_id`),
  KEY `idx_slug` (`slug`),
  KEY `idx_status` (`status`),
  KEY `idx_featured` (`is_featured`),
  KEY `idx_sale` (`is_on_sale`),
  FOREIGN KEY (`category_id`) REFERENCES `store_categories`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert sample products
INSERT INTO `store_products` (`category_id`, `name`, `slug`, `description`, `short_description`, `price`, `original_price`, `discount_percentage`, `image_url`, `stock_quantity`, `stock_status`, `sku`, `rating`, `review_count`, `is_featured`, `is_on_sale`, `tags`) VALUES
(1, 'Premium Camping Tent', 'premium-camping-tent', 'Waterproof 4-person tent with UV protection and easy setup system. Perfect for family camping trips.', 'Waterproof 4-person tent with UV protection', 89.00, 112.00, 20, 'https://images.unsplash.com/photo-1504280390367-361c6d9f38f4?w=400&h=400&fit=crop', 50, 'in_stock', 'TENT-001', 4.80, 124, 1, 1, 'camping,tent,waterproof,family'),
(2, 'Professional Hiking Boots', 'professional-hiking-boots', 'Durable waterproof hiking boots with ankle support and superior grip for all terrains.', 'Durable waterproof hiking boots', 129.00, 160.00, 19, '../assets/images/shoes.jpg', 35, 'in_stock', 'BOOT-001', 4.70, 89, 1, 1, 'hiking,boots,waterproof'),
(3, 'Insulated Travel Bottle', 'insulated-travel-bottle', 'Stainless steel insulated water bottle keeps drinks cold for 24hrs and hot for 12hrs.', 'Insulated water bottle - 24hr cold', 25.00, 35.00, 29, 'https://images.unsplash.com/photo-1602143407151-7111542de6e8?w=400&h=400&fit=crop', 100, 'in_stock', 'BTL-001', 4.90, 256, 1, 1, 'bottle,water,insulated,travel'),
(2, 'Travel Backpack 45L', 'travel-backpack-45l', 'Spacious 45L travel backpack with multiple compartments and ergonomic design.', 'Durable 45L travel backpack', 79.00, 99.00, 20, 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=400&h=400&fit=crop', 45, 'in_stock', 'BAG-001', 4.60, 178, 1, 0, 'backpack,travel,hiking'),
(3, 'Memory Foam Travel Pillow', 'memory-foam-travel-pillow', 'Ergonomic memory foam neck pillow with adjustable strap for maximum comfort.', 'Comfortable neck support pillow', 29.00, 39.00, 26, '../assets/images/travel pillow.jpg', 80, 'in_stock', 'PIL-001', 4.50, 145, 0, 1, 'pillow,travel,comfort'),
(4, 'Complete First Aid Kit', 'complete-first-aid-kit', 'Comprehensive 150-piece first aid kit for travel and outdoor adventures.', 'Essential medical supplies for travel', 45.00, 60.00, 25, '../assets/images/first aid kit.jpg', 60, 'in_stock', 'AID-001', 4.80, 203, 1, 1, 'first-aid,safety,medical'),
(1, 'Portable Camping Stove', 'portable-camping-stove', 'Compact gas camping stove with windscreen and carrying case.', 'Compact portable camping stove', 55.00, 70.00, 21, 'https://images.unsplash.com/photo-1478131143081-80f7f84ca84d?w=400&h=400&fit=crop', 40, 'in_stock', 'STOVE-001', 4.70, 92, 0, 1, 'camping,stove,cooking'),
(2, 'Trekking Poles Set', 'trekking-poles-set', 'Adjustable aluminum trekking poles with anti-shock system and ergonomic grips.', 'Adjustable aluminum trekking poles', 39.00, 55.00, 29, 'https://images.unsplash.com/photo-1551698618-1dfe5d97d256?w=400&h=400&fit=crop', 55, 'in_stock', 'POLE-001', 4.60, 134, 0, 1, 'hiking,poles,trekking');

-- ============================================

-- Table: store_orders
-- Purpose: Track customer orders
CREATE TABLE IF NOT EXISTS `store_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_number` varchar(50) NOT NULL UNIQUE,
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `customer_phone` varchar(50) DEFAULT NULL,
  `shipping_address` text NOT NULL,
  `billing_address` text DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax` decimal(10,2) DEFAULT 0.00,
  `shipping_cost` decimal(10,2) DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_status` enum('pending','paid','failed','refunded') DEFAULT 'pending',
  `order_status` enum('pending','processing','shipped','delivered','cancelled') DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_order_number` (`order_number`),
  KEY `idx_customer_email` (`customer_email`),
  KEY `idx_order_status` (`order_status`),
  KEY `idx_payment_status` (`payment_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================

-- Table: store_order_items
-- Purpose: Track individual items in orders
CREATE TABLE IF NOT EXISTS `store_order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_sku` varchar(100) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_order` (`order_id`),
  KEY `idx_product` (`product_id`),
  FOREIGN KEY (`order_id`) REFERENCES `store_orders`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `store_products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================

-- Table: store_reviews
-- Purpose: Product reviews and ratings
CREATE TABLE IF NOT EXISTS `store_reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` >= 1 AND `rating` <= 5),
  `title` varchar(255) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `helpful_count` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_product` (`product_id`),
  KEY `idx_status` (`status`),
  FOREIGN KEY (`product_id`) REFERENCES `store_products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================

-- Table: store_wishlist
-- Purpose: Customer wishlist items
CREATE TABLE IF NOT EXISTS `store_wishlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_email` varchar(255) NOT NULL,
  `product_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_customer` (`customer_email`),
  KEY `idx_product` (`product_id`),
  FOREIGN KEY (`product_id`) REFERENCES `store_products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================

-- Table: store_settings
-- Purpose: Store configuration and settings
CREATE TABLE IF NOT EXISTS `store_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL UNIQUE,
  `setting_value` text DEFAULT NULL,
  `setting_type` varchar(50) DEFAULT 'text',
  `description` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert default settings
INSERT INTO `store_settings` (`setting_key`, `setting_value`, `setting_type`, `description`) VALUES
('store_enabled', '1', 'boolean', 'Enable/disable the store'),
('free_shipping_threshold', '100', 'number', 'Minimum order value for free shipping'),
('tax_rate', '0', 'number', 'Tax rate percentage'),
('currency', 'USD', 'text', 'Store currency'),
('currency_symbol', '$', 'text', 'Currency symbol'),
('low_stock_threshold', '10', 'number', 'Low stock warning threshold'),
('store_email', 'store@foreveryoungtours.com', 'email', 'Store contact email');

-- ============================================
-- SUCCESS MESSAGE
-- ============================================
SELECT 'Store database tables created successfully!' AS message;
