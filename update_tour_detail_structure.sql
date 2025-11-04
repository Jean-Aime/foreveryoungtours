-- Add accommodation and media fields to tours table
ALTER TABLE tours 
ADD COLUMN IF NOT EXISTS accommodation_gallery JSON COMMENT 'Hotel/lodge images and details',
ADD COLUMN IF NOT EXISTS media_gallery JSON COMMENT 'Photos and videos',
ADD COLUMN IF NOT EXISTS video_gallery JSON COMMENT 'Video URLs',
ADD COLUMN IF NOT EXISTS drone_footage_url VARCHAR(500) COMMENT '360° or drone footage URL',
ADD COLUMN IF NOT EXISTS related_tours JSON COMMENT 'Suggested similar tour IDs';

-- Create accommodation table for detailed hotel/lodge info
CREATE TABLE IF NOT EXISTS tour_accommodations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tour_id INT NOT NULL,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    star_rating INT DEFAULT 3,
    image_url VARCHAR(500),
    gallery JSON COMMENT 'Multiple images',
    amenities JSON COMMENT 'Hotel amenities list',
    location VARCHAR(200),
    sort_order INT DEFAULT 0,
    FOREIGN KEY (tour_id) REFERENCES tours(id) ON DELETE CASCADE
);

-- Insert sample accommodations for existing tours
INSERT INTO tour_accommodations (tour_id, name, description, star_rating, image_url, amenities, location) 
SELECT 
    id,
    CASE 
        WHEN price > 2000 THEN 'Luxury Safari Lodge'
        WHEN price > 1000 THEN 'Comfort Safari Camp'
        ELSE 'Standard Lodge'
    END,
    CASE 
        WHEN price > 2000 THEN 'Luxury accommodation with panoramic views and premium amenities'
        WHEN price > 1000 THEN 'Comfortable mid-range accommodation with essential amenities'
        ELSE 'Clean and comfortable standard accommodation'
    END,
    CASE 
        WHEN price > 2000 THEN 5
        WHEN price > 1000 THEN 4
        ELSE 3
    END,
    'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=800&q=80',
    JSON_ARRAY('WiFi', 'Restaurant', 'Pool', 'Spa', 'Room Service'),
    destination
FROM tours 
WHERE id <= 10;

-- Update existing tours with sample media galleries
UPDATE tours SET 
    accommodation_gallery = JSON_ARRAY(
        'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=800&q=80',
        'https://images.unsplash.com/photo-1571896349842-33c89424de2d?auto=format&fit=crop&w=800&q=80',
        'https://images.unsplash.com/photo-1578774204375-8f04c3e8b6b5?auto=format&fit=crop&w=800&q=80'
    ),
    media_gallery = JSON_ARRAY(
        'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=800&q=80',
        'https://images.unsplash.com/photo-1489392191049-fc10c97e64b6?auto=format&fit=crop&w=800&q=80',
        'https://images.unsplash.com/photo-1578662996442-48f60103fc96?auto=format&fit=crop&w=800&q=80'
    ),
    video_gallery = JSON_ARRAY(
        'https://www.youtube.com/embed/dQw4w9WgXcQ',
        'https://www.youtube.com/embed/dQw4w9WgXcQ'
    ),
    drone_footage_url = 'https://www.youtube.com/embed/dQw4w9WgXcQ'
WHERE id <= 10;

-- Add more sample FAQs
INSERT INTO tour_faqs (tour_id, question, answer, sort_order) 
SELECT 
    id,
    'Is travel insurance included?',
    'Travel insurance is not included in the tour price. We highly recommend purchasing comprehensive travel insurance before your trip.',
    4
FROM tours WHERE id <= 5;

INSERT INTO tour_faqs (tour_id, question, answer, sort_order) 
SELECT 
    id,
    'Can I pay in installments?',
    'Yes, we offer flexible payment plans. You can pay a 30% deposit to secure your booking and pay the balance 30 days before departure.',
    5
FROM tours WHERE id <= 5;

INSERT INTO tour_faqs (tour_id, question, answer, sort_order) 
SELECT 
    id,
    'What if I need to cancel my booking?',
    'Cancellation policies vary by tour. Generally, cancellations made 60+ days before departure receive a full refund minus processing fees.',
    6
FROM tours WHERE id <= 5;

-- Add more sample reviews
INSERT INTO tour_reviews (tour_id, customer_name, rating, review_title, review_text, status, verified_booking, travel_date) 
SELECT 
    id,
    'Emma Thompson',
    5,
    'Absolutely incredible experience!',
    'This tour exceeded all my expectations. The guides were knowledgeable, the accommodations were comfortable, and seeing the wildlife up close was a dream come true. Highly recommended!',
    'approved',
    1,
    DATE_SUB(NOW(), INTERVAL FLOOR(RAND() * 365) DAY)
FROM tours WHERE id <= 5;

INSERT INTO tour_reviews (tour_id, customer_name, rating, review_title, review_text, status, verified_booking, travel_date) 
SELECT 
    id,
    'James Wilson',
    4,
    'Great value for money',
    'Well organized tour with excellent value. The itinerary was well-planned and we saw everything promised. Only minor issue was some delays in transportation.',
    'approved',
    1,
    DATE_SUB(NOW(), INTERVAL FLOOR(RAND() * 365) DAY)
FROM tours WHERE id <= 5;

-- Update related tours (suggest tours in same category or country)
UPDATE tours t1 SET 
    related_tours = (
        SELECT JSON_ARRAYAGG(t2.id)
        FROM tours t2 
        WHERE t2.id != t1.id 
        AND (t2.category = t1.category OR t2.country_id = t1.country_id)
        AND t2.status = 'active'
        LIMIT 4
    )
WHERE t1.id <= 10;