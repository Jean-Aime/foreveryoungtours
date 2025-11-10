-- =====================================================
-- Tour Booking System - Additional Tables Only
-- (Adds missing tables to existing database)
-- =====================================================

-- Tour Bookings Table (Enhanced version)
CREATE TABLE IF NOT EXISTS tour_bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_number VARCHAR(50) UNIQUE NOT NULL,
    tour_id INT NOT NULL,
    user_id INT,
    booking_type ENUM('booking', 'inquiry') DEFAULT 'booking',
    
    -- Customer Information
    customer_name VARCHAR(255) NOT NULL,
    customer_email VARCHAR(255) NOT NULL,
    customer_phone VARCHAR(50) NOT NULL,
    customer_country VARCHAR(100),
    
    -- Booking Details
    tour_date DATE NOT NULL,
    number_of_travelers INT NOT NULL DEFAULT 1,
    adults INT DEFAULT 1,
    children INT DEFAULT 0,
    infants INT DEFAULT 0,
    
    -- Traveler Details
    travelers_info TEXT, -- JSON array of traveler details
    
    -- Pricing
    price_per_person DECIMAL(10, 2) NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    discount_amount DECIMAL(10, 2) DEFAULT 0,
    paid_amount DECIMAL(10, 2) DEFAULT 0,
    
    -- Special Requests
    special_requests TEXT,
    dietary_requirements TEXT,
    accommodation_preference VARCHAR(255),
    
    -- Payment Information
    payment_status ENUM('pending', 'partial', 'paid', 'refunded', 'failed') DEFAULT 'pending',
    payment_method VARCHAR(50),
    payment_reference VARCHAR(255),
    
    -- Booking Status
    booking_status ENUM('pending', 'confirmed', 'cancelled', 'completed', 'no_show') DEFAULT 'pending',
    
    -- Communication
    inquiry_message TEXT,
    admin_notes TEXT,
    
    -- Timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    confirmed_at TIMESTAMP NULL,
    cancelled_at TIMESTAMP NULL,
    
    FOREIGN KEY (tour_id) REFERENCES tours(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_booking_number (booking_number),
    INDEX idx_tour (tour_id),
    INDEX idx_user (user_id),
    INDEX idx_status (booking_status),
    INDEX idx_payment_status (payment_status),
    INDEX idx_tour_date (tour_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Booking Status History
CREATE TABLE IF NOT EXISTS booking_status_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    old_status VARCHAR(50),
    new_status VARCHAR(50) NOT NULL,
    changed_by INT,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES tour_bookings(id) ON DELETE CASCADE,
    INDEX idx_booking (booking_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tour Wishlist
CREATE TABLE IF NOT EXISTS tour_wishlist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    tour_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (tour_id) REFERENCES tours(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_tour (user_id, tour_id),
    INDEX idx_user (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add missing columns to regions table
ALTER TABLE regions 
ADD COLUMN IF NOT EXISTS slug VARCHAR(100) UNIQUE AFTER name,
ADD COLUMN IF NOT EXISTS hero_image VARCHAR(500) AFTER description,
ADD COLUMN IF NOT EXISTS about_text LONGTEXT AFTER hero_image,
ADD COLUMN IF NOT EXISTS tourism_highlights TEXT AFTER about_text, -- JSON array
ADD COLUMN IF NOT EXISTS popular_activities TEXT AFTER tourism_highlights, -- JSON array
ADD COLUMN IF NOT EXISTS best_time_to_visit VARCHAR(255) AFTER popular_activities,
ADD COLUMN IF NOT EXISTS visa_requirements TEXT AFTER best_time_to_visit,
ADD COLUMN IF NOT EXISTS currency VARCHAR(50) AFTER visa_requirements,
ADD COLUMN IF NOT EXISTS languages_spoken TEXT AFTER currency, -- JSON array
ADD COLUMN IF NOT EXISTS meta_title VARCHAR(255) AFTER languages_spoken,
ADD COLUMN IF NOT EXISTS meta_description TEXT AFTER meta_title;

-- Add missing columns to countries table
ALTER TABLE countries 
ADD COLUMN IF NOT EXISTS slug VARCHAR(100) UNIQUE AFTER name,
ADD COLUMN IF NOT EXISTS hero_image VARCHAR(500) AFTER description,
ADD COLUMN IF NOT EXISTS about_text LONGTEXT AFTER hero_image,
ADD COLUMN IF NOT EXISTS tourism_highlights TEXT AFTER about_text, -- JSON array
ADD COLUMN IF NOT EXISTS popular_destinations TEXT AFTER tourism_highlights, -- JSON array
ADD COLUMN IF NOT EXISTS best_time_to_visit VARCHAR(255) AFTER popular_destinations,
ADD COLUMN IF NOT EXISTS visa_requirements TEXT AFTER best_time_to_visit,
ADD COLUMN IF NOT EXISTS currency VARCHAR(50) AFTER visa_requirements,
ADD COLUMN IF NOT EXISTS languages_spoken TEXT AFTER currency, -- JSON array
ADD COLUMN IF NOT EXISTS capital VARCHAR(100) AFTER languages_spoken,
ADD COLUMN IF NOT EXISTS population VARCHAR(50) AFTER capital,
ADD COLUMN IF NOT EXISTS area VARCHAR(50) AFTER population,
ADD COLUMN IF NOT EXISTS timezone VARCHAR(100) AFTER area,
ADD COLUMN IF NOT EXISTS calling_code VARCHAR(20) AFTER timezone,
ADD COLUMN IF NOT EXISTS meta_title VARCHAR(255) AFTER calling_code,
ADD COLUMN IF NOT EXISTS meta_description TEXT AFTER meta_title;

-- Add missing columns to tours table (if they don't exist)
ALTER TABLE tours 
ADD COLUMN IF NOT EXISTS region_id INT AFTER country_id,
ADD COLUMN IF NOT EXISTS long_description LONGTEXT AFTER description,
ADD COLUMN IF NOT EXISTS gallery_images TEXT AFTER image_url, -- JSON array
ADD COLUMN IF NOT EXISTS video_url VARCHAR(500) AFTER gallery_images,
ADD COLUMN IF NOT EXISTS included_services TEXT AFTER itinerary, -- JSON array
ADD COLUMN IF NOT EXISTS excluded_services TEXT AFTER included_services, -- JSON array
ADD COLUMN IF NOT EXISTS meeting_point VARCHAR(255) AFTER excluded_services,
ADD COLUMN IF NOT EXISTS departure_time VARCHAR(100) AFTER meeting_point,
ADD COLUMN IF NOT EXISTS return_time VARCHAR(100) AFTER departure_time,
ADD COLUMN IF NOT EXISTS min_age INT DEFAULT 0 AFTER max_participants,
ADD COLUMN IF NOT EXISTS tour_type ENUM('adventure', 'cultural', 'wildlife', 'beach', 'city', 'luxury', 'budget', 'family') DEFAULT 'cultural' AFTER category,
ADD COLUMN IF NOT EXISTS available_dates TEXT AFTER requirements; -- JSON array

-- Add foreign key for region_id if it doesn't exist
-- Note: This will only work if the column was just added
SET @fk_exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS 
    WHERE CONSTRAINT_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'tours' 
    AND CONSTRAINT_NAME = 'tours_ibfk_region');

SET @sql = IF(@fk_exists = 0,
    'ALTER TABLE tours ADD FOREIGN KEY (region_id) REFERENCES regions(id) ON DELETE SET NULL',
    'SELECT "Foreign key already exists"');

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Update Africa region with tourism information
UPDATE regions SET 
    slug = 'africa',
    hero_image = 'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=2000&q=80',
    about_text = 'Africa, the world\'s second-largest continent, is a land of extraordinary diversity, breathtaking landscapes, and rich cultural heritage. From the vast Sahara Desert to the lush rainforests of the Congo, from the snow-capped peaks of Kilimanjaro to the pristine beaches of Zanzibar, Africa offers unparalleled adventure and discovery.',
    tourism_highlights = '["Wildlife Safaris", "Ancient Pyramids", "Victoria Falls", "Serengeti Migration", "Table Mountain", "Sahara Desert", "Nile River Cruises", "Maasai Culture"]',
    popular_activities = '["Safari Game Drives", "Mountain Climbing", "Beach Relaxation", "Cultural Tours", "Desert Adventures", "River Rafting", "Bird Watching", "Photography Tours"]',
    best_time_to_visit = 'June to October (Dry Season)',
    visa_requirements = 'Varies by country. Many African countries offer visa on arrival or e-visa options.',
    currency = 'Various (USD widely accepted)',
    languages_spoken = '["English", "French", "Arabic", "Swahili", "Portuguese"]',
    meta_title = 'Explore Africa - Luxury Tours & Safari Adventures',
    meta_description = 'Discover the magic of Africa with Forever Young Tours. Experience world-class safaris, cultural immersion, and breathtaking landscapes across the African continent.'
WHERE name = 'Africa';

-- Update Rwanda with detailed tourism information
UPDATE countries SET 
    slug = 'visit-rw',
    hero_image = 'https://images.unsplash.com/photo-1609198092357-8e51c4b1d9f9?auto=format&fit=crop&w=2000&q=80',
    about_text = 'Rwanda, the "Land of a Thousand Hills," is a remarkable East African nation known for its stunning scenery, rare mountain gorillas, and inspiring recovery story. This small but vibrant country offers world-class wildlife experiences, rich cultural heritage, and warm hospitality.',
    tourism_highlights = '["Mountain Gorilla Trekking", "Volcanoes National Park", "Nyungwe Forest", "Lake Kivu", "Kigali Genocide Memorial", "Akagera National Park", "Cultural Villages", "Coffee Plantations"]',
    popular_destinations = '["Volcanoes National Park", "Kigali City", "Lake Kivu", "Nyungwe National Park", "Akagera National Park"]',
    best_time_to_visit = 'June to September (Dry Season)',
    visa_requirements = 'Visa on arrival or e-visa available for most nationalities',
    currency = 'Rwandan Franc (RWF)',
    languages_spoken = '["Kinyarwanda", "English", "French", "Swahili"]',
    capital = 'Kigali',
    population = '13 million',
    area = '26,338 km²',
    timezone = 'CAT (UTC+2)',
    calling_code = '+250',
    meta_title = 'Visit Rwanda - Gorilla Trekking & Luxury Tours',
    meta_description = 'Experience the beauty of Rwanda with Forever Young Tours. Trek with mountain gorillas, explore pristine national parks, and discover the warmth of Rwandan hospitality.'
WHERE name = 'Rwanda';

-- Create indexes for performance
CREATE INDEX IF NOT EXISTS idx_tour_slug ON tours(slug);
CREATE INDEX IF NOT EXISTS idx_region_slug ON regions(slug);
CREATE INDEX IF NOT EXISTS idx_country_slug ON countries(slug);
CREATE INDEX IF NOT EXISTS idx_booking_date ON tour_bookings(tour_date);
CREATE INDEX IF NOT EXISTS idx_booking_status ON tour_bookings(booking_status);

-- Success message
SELECT 'Tour booking system tables created successfully!' AS message;
