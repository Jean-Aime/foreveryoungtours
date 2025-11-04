<?php
// Database setup script
$host = 'localhost';
$username = 'root';
$password = '';
$db_name = 'foreveryoungtours';

try {
    // Connect to MySQL server (without database)
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    
    // Connect to the database
    $pdo = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create tables
    $sql = "
    CREATE TABLE IF NOT EXISTS `regions` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(100) NOT NULL,
        `slug` varchar(100) NOT NULL,
        `description` text,
        `status` enum('active','inactive') DEFAULT 'active',
        `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        UNIQUE KEY `slug` (`slug`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    
    CREATE TABLE IF NOT EXISTS `countries` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(100) NOT NULL,
        `slug` varchar(100) NOT NULL,
        `region_id` int(11) NOT NULL,
        `capital` varchar(100),
        `currency` varchar(50),
        `language` varchar(100),
        `tourism_description` text,
        `image_url` varchar(255),
        `featured` tinyint(1) DEFAULT 0,
        `status` enum('active','inactive') DEFAULT 'active',
        `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        UNIQUE KEY `slug` (`slug`),
        KEY `region_id` (`region_id`),
        FOREIGN KEY (`region_id`) REFERENCES `regions`(`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    
    CREATE TABLE IF NOT EXISTS `tours` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(200) NOT NULL,
        `slug` varchar(200) NOT NULL,
        `country_id` int(11) NOT NULL,
        `description` text,
        `duration_days` int(11) NOT NULL,
        `price` decimal(10,2) NOT NULL,
        `image_url` varchar(255),
        `featured` tinyint(1) DEFAULT 0,
        `status` enum('active','inactive') DEFAULT 'active',
        `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        UNIQUE KEY `slug` (`slug`),
        KEY `country_id` (`country_id`),
        FOREIGN KEY (`country_id`) REFERENCES `countries`(`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    
    CREATE TABLE IF NOT EXISTS `users` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `email` varchar(255) NOT NULL,
        `password` varchar(255) NOT NULL,
        `first_name` varchar(100),
        `last_name` varchar(100),
        `role` enum('client','advisor','admin') DEFAULT 'client',
        `status` enum('active','inactive') DEFAULT 'active',
        `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        UNIQUE KEY `email` (`email`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    
    CREATE TABLE IF NOT EXISTS `bookings` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `user_id` int(11) NOT NULL,
        `tour_id` int(11) NOT NULL,
        `booking_date` date NOT NULL,
        `travelers` int(11) DEFAULT 1,
        `total_price` decimal(10,2) NOT NULL,
        `status` enum('pending','confirmed','cancelled') DEFAULT 'pending',
        `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        KEY `user_id` (`user_id`),
        KEY `tour_id` (`tour_id`),
        FOREIGN KEY (`user_id`) REFERENCES `users`(`id`),
        FOREIGN KEY (`tour_id`) REFERENCES `tours`(`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ";
    
    $pdo->exec($sql);
    
    // Insert sample data
    $pdo->exec("
    INSERT IGNORE INTO `regions` (`id`, `name`, `slug`, `description`) VALUES
    (1, 'East Africa', 'east-africa', 'Home to the Great Migration and stunning wildlife'),
    (2, 'West Africa', 'west-africa', 'Rich cultural heritage and vibrant traditions'),
    (3, 'Southern Africa', 'southern-africa', 'Diverse landscapes and adventure activities'),
    (4, 'North Africa', 'north-africa', 'Ancient civilizations and desert adventures'),
    (5, 'Central Africa', 'central-africa', 'Dense rainforests and unique wildlife');
    
    INSERT IGNORE INTO `countries` (`id`, `name`, `slug`, `region_id`, `capital`, `currency`, `language`, `tourism_description`, `featured`) VALUES
    (1, 'Kenya', 'kenya', 1, 'Nairobi', 'KES', 'English, Swahili', 'Experience the Great Migration and Big Five safaris in world-famous national parks.', 1),
    (2, 'Tanzania', 'tanzania', 1, 'Dodoma', 'TZS', 'English, Swahili', 'Home to Serengeti, Kilimanjaro, and pristine beaches of Zanzibar.', 1),
    (3, 'South Africa', 'south-africa', 3, 'Cape Town', 'ZAR', 'English, Afrikaans', 'Diverse landscapes from wine regions to safari parks and vibrant cities.', 1),
    (4, 'Morocco', 'morocco', 4, 'Rabat', 'MAD', 'Arabic, French', 'Imperial cities, Sahara desert, and Atlas Mountains adventures.', 1),
    (5, 'Egypt', 'egypt', 4, 'Cairo', 'EGP', 'Arabic', 'Ancient pyramids, Nile cruises, and Red Sea diving experiences.', 1),
    (6, 'Botswana', 'botswana', 3, 'Gaborone', 'BWP', 'English', 'Pristine wilderness and exclusive safari experiences in the Okavango Delta.', 1);
    
    INSERT IGNORE INTO `tours` (`id`, `name`, `slug`, `country_id`, `description`, `duration_days`, `price`, `featured`) VALUES
    (1, 'Kenya Safari Adventure', 'kenya-safari-adventure', 1, 'Experience the Big Five in Masai Mara and Amboseli National Parks', 7, 2499.00, 1),
    (2, 'Serengeti Migration Safari', 'serengeti-migration-safari', 2, 'Witness the Great Migration in Serengeti and Ngorongoro Crater', 8, 3299.00, 1),
    (3, 'Cape Town & Safari Combo', 'cape-town-safari-combo', 3, 'Explore Cape Town and enjoy a luxury safari experience', 10, 3899.00, 1),
    (4, 'Morocco Imperial Cities', 'morocco-imperial-cities', 4, 'Discover Marrakech, Fez, and the Sahara Desert', 9, 2199.00, 1),
    (5, 'Egypt Nile Cruise', 'egypt-nile-cruise', 5, 'Cruise the Nile and explore ancient temples and pyramids', 8, 2799.00, 1),
    (6, 'Okavango Delta Safari', 'okavango-delta-safari', 6, 'Exclusive water safari in pristine Okavango Delta', 6, 4299.00, 1);
    ");
    
    echo "<h1>Database Setup Complete!</h1>";
    echo "<p>The database and tables have been created successfully.</p>";
    echo "<p><a href='index.php'>Go to Homepage</a></p>";
    
} catch(PDOException $e) {
    echo "Setup failed: " . $e->getMessage();
}
?>