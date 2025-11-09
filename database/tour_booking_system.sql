-- =====================================================
-- Tour Booking System Database Schema
-- =====================================================

-- Tours Table (Enhanced)
CREATE TABLE IF NOT EXISTS tours (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT,
    long_description LONGTEXT,
    country_id INT,
    region_id INT,
    duration VARCHAR(100),
    duration_days INT,
    price DECIMAL(10, 2) NOT NULL,
    discount_price DECIMAL(10, 2),
    max_group_size INT DEFAULT 20,
    min_age INT DEFAULT 0,
    difficulty_level ENUM('easy', 'moderate', 'challenging', 'extreme') DEFAULT 'moderate',
    tour_type ENUM('adventure', 'cultural', 'wildlife', 'beach', 'city', 'luxury', 'budget', 'family') DEFAULT 'cultural',
    image_url VARCHAR(500),
    gallery_images TEXT, -- JSON array of image URLs
    video_url VARCHAR(500),
    included_services TEXT, -- JSON array
    excluded_services TEXT, -- JSON array
    itinerary TEXT, -- JSON array of day-by-day itinerary
    meeting_point VARCHAR(255),
    departure_time VARCHAR(100),
    return_time VARCHAR(100),
    languages VARCHAR(255), -- JSON array
    highlights TEXT, -- JSON array
    requirements TEXT,
    cancellation_policy TEXT,
    status ENUM('active', 'inactive', 'draft', 'sold_out') DEFAULT 'active',
    featured BOOLEAN DEFAULT FALSE,
    popularity_score INT DEFAULT 0,
    average_rating DECIMAL(3, 2) DEFAULT 0.00,
    total_reviews INT DEFAULT 0,
    total_bookings INT DEFAULT 0,
    available_dates TEXT, -- JSON array of available dates
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (country_id) REFERENCES countries(id) ON DELETE SET NULL,
    FOREIGN KEY (region_id) REFERENCES regions(id) ON DELETE SET NULL,
    INDEX idx_country (country_id),
    INDEX idx_region (region_id),
    INDEX idx_status (status),
    INDEX idx_featured (featured)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tour Bookings Table
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

-- Tour Reviews Table
CREATE TABLE IF NOT EXISTS tour_reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tour_id INT NOT NULL,
    user_id INT,
    booking_id INT,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    title VARCHAR(255),
    review_text TEXT,
    pros TEXT,
    cons TEXT,
    images TEXT, -- JSON array of image URLs
    helpful_count INT DEFAULT 0,
    verified_booking BOOLEAN DEFAULT FALSE,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    admin_response TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (tour_id) REFERENCES tours(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (booking_id) REFERENCES tour_bookings(id) ON DELETE SET NULL,
    INDEX idx_tour (tour_id),
    INDEX idx_rating (rating),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tour FAQs Table
CREATE TABLE IF NOT EXISTS tour_faqs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tour_id INT NOT NULL,
    question TEXT NOT NULL,
    answer TEXT NOT NULL,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (tour_id) REFERENCES tours(id) ON DELETE CASCADE,
    INDEX idx_tour (tour_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tour Availability Calendar
CREATE TABLE IF NOT EXISTS tour_availability (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tour_id INT NOT NULL,
    available_date DATE NOT NULL,
    available_slots INT NOT NULL,
    booked_slots INT DEFAULT 0,
    price_override DECIMAL(10, 2), -- Special pricing for specific dates
    status ENUM('available', 'limited', 'sold_out', 'unavailable') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (tour_id) REFERENCES tours(id) ON DELETE CASCADE,
    UNIQUE KEY unique_tour_date (tour_id, available_date),
    INDEX idx_date (available_date),
    INDEX idx_status (status)
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

-- Continent/Region Information (Enhanced)
ALTER TABLE regions ADD COLUMN IF NOT EXISTS slug VARCHAR(100) UNIQUE;
ALTER TABLE regions ADD COLUMN IF NOT EXISTS hero_image VARCHAR(500);
ALTER TABLE regions ADD COLUMN IF NOT EXISTS about_text LONGTEXT;
ALTER TABLE regions ADD COLUMN IF NOT EXISTS tourism_highlights TEXT; -- JSON array
ALTER TABLE regions ADD COLUMN IF NOT EXISTS popular_activities TEXT; -- JSON array
ALTER TABLE regions ADD COLUMN IF NOT EXISTS best_time_to_visit VARCHAR(255);
ALTER TABLE regions ADD COLUMN IF NOT EXISTS visa_requirements TEXT;
ALTER TABLE regions ADD COLUMN IF NOT EXISTS currency VARCHAR(50);
ALTER TABLE regions ADD COLUMN IF NOT EXISTS languages_spoken TEXT; -- JSON array
ALTER TABLE regions ADD COLUMN IF NOT EXISTS meta_title VARCHAR(255);
ALTER TABLE regions ADD COLUMN IF NOT EXISTS meta_description TEXT;

-- Country Information (Enhanced)
ALTER TABLE countries ADD COLUMN IF NOT EXISTS slug VARCHAR(100) UNIQUE;
ALTER TABLE countries ADD COLUMN IF NOT EXISTS hero_image VARCHAR(500);
ALTER TABLE countries ADD COLUMN IF NOT EXISTS about_text LONGTEXT;
ALTER TABLE countries ADD COLUMN IF NOT EXISTS tourism_highlights TEXT; -- JSON array
ALTER TABLE countries ADD COLUMN IF NOT EXISTS popular_destinations TEXT; -- JSON array
ALTER TABLE countries ADD COLUMN IF NOT EXISTS best_time_to_visit VARCHAR(255);
ALTER TABLE countries ADD COLUMN IF NOT EXISTS visa_requirements TEXT;
ALTER TABLE countries ADD COLUMN IF NOT EXISTS currency VARCHAR(50);
ALTER TABLE countries ADD COLUMN IF NOT EXISTS languages_spoken TEXT; -- JSON array
ALTER TABLE countries ADD COLUMN IF NOT EXISTS capital VARCHAR(100);
ALTER TABLE countries ADD COLUMN IF NOT EXISTS population VARCHAR(50);
ALTER TABLE countries ADD COLUMN IF NOT EXISTS area VARCHAR(50);
ALTER TABLE countries ADD COLUMN IF NOT EXISTS timezone VARCHAR(100);
ALTER TABLE countries ADD COLUMN IF NOT EXISTS calling_code VARCHAR(20);
ALTER TABLE countries ADD COLUMN IF NOT EXISTS meta_title VARCHAR(255);
ALTER TABLE countries ADD COLUMN IF NOT EXISTS meta_description TEXT;

-- Sample Data for Africa Region
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

-- Sample Data for Rwanda
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

-- Insert Sample Tours for Rwanda
INSERT INTO tours (name, slug, description, long_description, country_id, region_id, duration, duration_days, price, discount_price, max_group_size, difficulty_level, tour_type, image_url, gallery_images, included_services, excluded_services, highlights, itinerary, meeting_point, departure_time, languages, status, featured) VALUES
('Gorilla Trekking Adventure', 'gorilla-trekking-adventure', 'Experience the thrill of encountering mountain gorillas in their natural habitat', 
'Embark on an unforgettable journey to meet the majestic mountain gorillas in Volcanoes National Park. This once-in-a-lifetime experience brings you face-to-face with these gentle giants in their natural rainforest habitat. Trek through bamboo forests and volcanic slopes with expert guides, and spend a magical hour observing gorilla families as they play, feed, and interact. This tour includes luxury accommodation, all permits, and professional guidance for an experience you\'ll treasure forever.',
(SELECT id FROM countries WHERE slug = 'visit-rw'),
(SELECT id FROM regions WHERE slug = 'africa'),
'3 Days / 2 Nights', 3, 1500.00, 1350.00, 8, 'moderate', 'wildlife',
'https://images.unsplash.com/photo-1551918120-9739cb430c6d?auto=format&fit=crop&w=1200&q=80',
'["https://images.unsplash.com/photo-1551918120-9739cb430c6d?auto=format&fit=crop&w=1200&q=80", "https://images.unsplash.com/photo-1564760055775-d63b17a55c44?auto=format&fit=crop&w=1200&q=80", "https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=1200&q=80"]',
'["Gorilla trekking permit", "Luxury accommodation", "All meals", "Professional guide", "Park entrance fees", "Airport transfers", "Bottled water"]',
'["International flights", "Travel insurance", "Personal expenses", "Tips and gratuities", "Alcoholic beverages"]',
'["Face-to-face gorilla encounter", "Volcanoes National Park", "Luxury lodge accommodation", "Expert local guides", "Small group experience", "Certificate of participation"]',
'[{"day": 1, "title": "Arrival in Kigali", "description": "Arrive at Kigali International Airport. Meet and greet by our representative. Transfer to Volcanoes National Park (2.5 hours). Check-in at luxury lodge. Evening briefing about gorilla trekking. Dinner and overnight.", "meals": "Dinner", "accommodation": "Luxury Lodge"}, {"day": 2, "title": "Gorilla Trekking Day", "description": "Early breakfast. Transfer to park headquarters for briefing. Begin gorilla trek (2-6 hours depending on gorilla location). Spend magical hour with gorilla family. Return to lodge for lunch. Afternoon visit to Iby\'iwacu Cultural Village. Dinner and overnight.", "meals": "Breakfast, Lunch, Dinner", "accommodation": "Luxury Lodge"}, {"day": 3, "title": "Return to Kigali", "description": "Breakfast at leisure. Optional golden monkey trekking or visit to Dian Fossey tomb. Transfer back to Kigali. City tour including Genocide Memorial. Transfer to airport for departure.", "meals": "Breakfast, Lunch", "accommodation": "N/A"}]',
'Kigali International Airport', '06:00 AM', '["English", "French"]', 'active', TRUE);

-- Create indexes for performance
CREATE INDEX idx_tour_slug ON tours(slug);
CREATE INDEX idx_tour_price ON tours(price);
CREATE INDEX idx_tour_rating ON tours(average_rating);
CREATE INDEX idx_booking_date ON tour_bookings(tour_date);
CREATE INDEX idx_booking_status ON tour_bookings(booking_status);
