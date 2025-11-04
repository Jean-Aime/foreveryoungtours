<?php
require_once 'config/database.php';

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    echo "<h2>Setting up Enhanced Tours System...</h2>";
    
    // Create client_wishlist table
    $sql = "CREATE TABLE IF NOT EXISTS client_wishlist (
        id INT PRIMARY KEY AUTO_INCREMENT,
        client_id INT NOT NULL,
        tour_id INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (client_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (tour_id) REFERENCES tours(id) ON DELETE CASCADE,
        UNIQUE KEY unique_client_tour (client_id, tour_id)
    )";
    $conn->exec($sql);
    echo "✓ Created client_wishlist table<br>";
    
    // Add enhanced columns to tours table if they don't exist
    $columns_to_add = [
        'detailed_description TEXT',
        'highlights JSON',
        'difficulty_level ENUM("easy", "moderate", "challenging", "extreme") DEFAULT "moderate"',
        'best_time_to_visit VARCHAR(100)',
        'what_to_bring TEXT',
        'tour_type ENUM("group", "private", "custom") DEFAULT "group"',
        'languages JSON',
        'age_restriction VARCHAR(50)',
        'accommodation_type VARCHAR(100)',
        'meal_plan VARCHAR(100)',
        'booking_deadline INT DEFAULT 7',
        'video_url VARCHAR(500)',
        'virtual_tour_url VARCHAR(500)',
        'meta_title VARCHAR(200)',
        'meta_description TEXT',
        'tour_tags JSON',
        'advisor_commission_rate DECIMAL(5,2) DEFAULT 10.00',
        'mca_commission_rate DECIMAL(5,2) DEFAULT 5.00',
        'average_rating DECIMAL(3,2) DEFAULT 0.00',
        'review_count INT DEFAULT 0',
        'booking_count INT DEFAULT 0',
        'popularity_score INT DEFAULT 0'
    ];
    
    foreach ($columns_to_add as $column) {
        try {
            $column_name = explode(' ', $column)[0];
            $conn->exec("ALTER TABLE tours ADD COLUMN IF NOT EXISTS $column");
            echo "✓ Added column: $column_name<br>";
        } catch (Exception $e) {
            echo "- Column $column_name already exists or error: " . $e->getMessage() . "<br>";
        }
    }
    
    // Create tour_images table
    $sql = "CREATE TABLE IF NOT EXISTS tour_images (
        id INT PRIMARY KEY AUTO_INCREMENT,
        tour_id INT NOT NULL,
        image_url VARCHAR(500) NOT NULL,
        image_title VARCHAR(200),
        image_description TEXT,
        image_type ENUM('main', 'gallery', 'cover', 'thumbnail') DEFAULT 'gallery',
        sort_order INT DEFAULT 0,
        alt_text VARCHAR(200),
        is_featured BOOLEAN DEFAULT FALSE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (tour_id) REFERENCES tours(id) ON DELETE CASCADE
    )";
    $conn->exec($sql);
    echo "✓ Created tour_images table<br>";
    
    // Create tour_reviews table
    $sql = "CREATE TABLE IF NOT EXISTS tour_reviews (
        id INT PRIMARY KEY AUTO_INCREMENT,
        tour_id INT NOT NULL,
        booking_id INT,
        customer_name VARCHAR(100) NOT NULL,
        customer_email VARCHAR(100),
        rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
        review_title VARCHAR(200),
        review_text TEXT,
        travel_date DATE,
        verified_booking BOOLEAN DEFAULT FALSE,
        status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
        helpful_votes INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (tour_id) REFERENCES tours(id) ON DELETE CASCADE,
        FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE SET NULL
    )";
    $conn->exec($sql);
    echo "✓ Created tour_reviews table<br>";
    
    // Create tour_faqs table
    $sql = "CREATE TABLE IF NOT EXISTS tour_faqs (
        id INT PRIMARY KEY AUTO_INCREMENT,
        tour_id INT NOT NULL,
        question TEXT NOT NULL,
        answer TEXT NOT NULL,
        sort_order INT DEFAULT 0,
        is_active BOOLEAN DEFAULT TRUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (tour_id) REFERENCES tours(id) ON DELETE CASCADE
    )";
    $conn->exec($sql);
    echo "✓ Created tour_faqs table<br>";
    
    // Create shared_links table
    $sql = "CREATE TABLE IF NOT EXISTS shared_links (
        id INT PRIMARY KEY AUTO_INCREMENT,
        tour_id INT NOT NULL,
        user_id INT NOT NULL,
        link_code VARCHAR(50) UNIQUE NOT NULL,
        full_url VARCHAR(500) NOT NULL,
        clicks INT DEFAULT 0,
        conversions INT DEFAULT 0,
        is_active BOOLEAN DEFAULT TRUE,
        expires_at TIMESTAMP NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        last_clicked_at TIMESTAMP NULL,
        FOREIGN KEY (tour_id) REFERENCES tours(id) ON DELETE CASCADE,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )";
    $conn->exec($sql);
    echo "✓ Created shared_links table<br>";
    
    // Add sample enhanced data to existing tours
    $stmt = $conn->query("SELECT id, name, category, price, country_id FROM tours LIMIT 5");
    $tours = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($tours as $tour) {
        // Update with enhanced information
        $highlights = json_encode([
            'Expert local guides',
            'Small group experience',
            'Authentic cultural interactions',
            'Professional photography opportunities',
            'Comfortable accommodations'
        ]);
        
        $languages = json_encode(['English', 'French', 'Local language']);
        $tour_tags = json_encode([$tour['category'], 'africa', 'adventure']);
        
        $difficulty = match($tour['category']) {
            'adventure' => 'challenging',
            'cultural' => 'easy',
            'wildlife' => 'moderate',
            default => 'moderate'
        };
        
        $accommodation = match(true) {
            $tour['price'] > 2000 => 'Luxury lodges and hotels',
            $tour['price'] > 1000 => 'Mid-range hotels and lodges',
            default => 'Comfortable guesthouses and camps'
        };
        
        $stmt = $conn->prepare("UPDATE tours SET 
            detailed_description = CONCAT(description, '\n\nThis comprehensive tour offers an immersive experience into the heart of Africa. Our expert guides will take you on a journey through stunning landscapes, rich cultures, and unforgettable wildlife encounters.'),
            highlights = ?,
            difficulty_level = ?,
            best_time_to_visit = 'Year-round with seasonal variations',
            what_to_bring = 'Comfortable walking shoes, sun hat, sunscreen, camera, light jacket for evenings, personal medications',
            languages = ?,
            age_restriction = '12+ years (children must be accompanied by adults)',
            accommodation_type = ?,
            meal_plan = 'Full board (breakfast, lunch, dinner)',
            tour_tags = ?,
            meta_title = CONCAT(name, ' - African Adventure Tour | iForYoungTours'),
            meta_description = CONCAT('Experience ', name, ' with iForYoungTours. Professional guides, comfortable accommodation, and unforgettable memories await.')
            WHERE id = ?");
        
        $stmt->execute([
            $highlights,
            $difficulty,
            $languages,
            $accommodation,
            $tour_tags,
            $tour['id']
        ]);
    }
    echo "✓ Updated existing tours with enhanced information<br>";
    
    // Add sample FAQs
    $sample_faqs = [
        [
            'question' => 'What is included in the tour price?',
            'answer' => 'The tour price includes accommodation, meals as specified, professional guide, transportation during the tour, and entrance fees to attractions mentioned in the itinerary.'
        ],
        [
            'question' => 'What should I bring on this tour?',
            'answer' => 'Please bring comfortable walking shoes, sun protection, camera, light jacket for evenings, personal medications, and a copy of your passport.'
        ],
        [
            'question' => 'Is this tour suitable for children?',
            'answer' => 'This tour is suitable for children 12 years and above. Children must be accompanied by adults at all times.'
        ]
    ];
    
    foreach ($tours as $tour) {
        foreach ($sample_faqs as $index => $faq) {
            $stmt = $conn->prepare("INSERT IGNORE INTO tour_faqs (tour_id, question, answer, sort_order) VALUES (?, ?, ?, ?)");
            $stmt->execute([$tour['id'], $faq['question'], $faq['answer'], $index + 1]);
        }
    }
    echo "✓ Added sample FAQs to tours<br>";
    
    // Add sample reviews
    $sample_reviews = [
        [
            'customer_name' => 'Sarah Johnson',
            'rating' => 5,
            'review_title' => 'Amazing Experience!',
            'review_text' => 'This was the trip of a lifetime! The guides were knowledgeable, the accommodations were comfortable, and the wildlife viewing was incredible.',
            'status' => 'approved'
        ],
        [
            'customer_name' => 'Michael Chen',
            'rating' => 4,
            'review_title' => 'Great Adventure',
            'review_text' => 'Wonderful tour with excellent organization. The cultural experiences were particularly memorable.',
            'status' => 'approved'
        ]
    ];
    
    foreach ($tours as $tour) {
        foreach ($sample_reviews as $review) {
            $stmt = $conn->prepare("INSERT IGNORE INTO tour_reviews (tour_id, customer_name, rating, review_title, review_text, status, verified_booking) VALUES (?, ?, ?, ?, ?, ?, 1)");
            $stmt->execute([
                $tour['id'],
                $review['customer_name'],
                $review['rating'],
                $review['review_title'],
                $review['review_text'],
                $review['status']
            ]);
        }
    }
    echo "✓ Added sample reviews to tours<br>";
    
    // Update tour statistics
    $conn->exec("UPDATE tours SET 
        average_rating = (SELECT AVG(rating) FROM tour_reviews WHERE tour_id = tours.id AND status = 'approved'),
        review_count = (SELECT COUNT(*) FROM tour_reviews WHERE tour_id = tours.id AND status = 'approved'),
        booking_count = (SELECT COUNT(*) FROM bookings WHERE tour_id = tours.id),
        popularity_score = COALESCE((SELECT COUNT(*) FROM bookings WHERE tour_id = tours.id), 0) * 10 + COALESCE((SELECT COUNT(*) FROM tour_reviews WHERE tour_id = tours.id AND status = 'approved'), 0) * 5
    ");
    echo "✓ Updated tour statistics<br>";
    
    echo "<br><h3>✅ Enhanced Tours System Setup Complete!</h3>";
    echo "<p>You can now:</p>";
    echo "<ul>";
    echo "<li>Use the enhanced admin tours management at: <a href='admin/tours-enhanced.php'>admin/tours-enhanced.php</a></li>";
    echo "<li>View enhanced tour details at: <a href='pages/tour-detail-enhanced.php'>pages/tour-detail-enhanced.php</a></li>";
    echo "<li>MCA tour management at: <a href='mca/tours.php'>mca/tours.php</a></li>";
    echo "<li>Advisor tour sharing at: <a href='advisor/tours.php'>advisor/tours.php</a></li>";
    echo "<li>Client tour browsing at: <a href='client/tours.php'>client/tours.php</a></li>";
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<h3>❌ Error during setup:</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>