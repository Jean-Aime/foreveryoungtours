-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 26, 2025 at 09:24 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `forevveryoungtours`
--

-- --------------------------------------------------------

--
-- Table structure for table `advisor_team`
--

CREATE TABLE `advisor_team` (
  `id` int(11) NOT NULL,
  `advisor_id` int(11) NOT NULL,
  `team_member_id` int(11) NOT NULL,
  `level` int(11) NOT NULL COMMENT '1=direct, 2=L2, 3=L3',
  `joined_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `analytics_metrics`
--

CREATE TABLE `analytics_metrics` (
  `id` int(11) NOT NULL,
  `metric_type` varchar(50) NOT NULL,
  `metric_value` decimal(15,2) NOT NULL,
  `metric_date` date NOT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `api_integrations`
--

CREATE TABLE `api_integrations` (
  `id` int(11) NOT NULL,
  `provider` varchar(50) NOT NULL,
  `api_type` enum('flight','hotel','car_rental','activity') NOT NULL,
  `api_key` varchar(255) DEFAULT NULL,
  `api_secret` varchar(255) DEFAULT NULL,
  `endpoint_url` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `config` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`config`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `api_requests`
--

CREATE TABLE `api_requests` (
  `id` int(11) NOT NULL,
  `integration_id` int(11) NOT NULL,
  `request_type` varchar(50) DEFAULT NULL,
  `request_data` text DEFAULT NULL,
  `response_data` text DEFAULT NULL,
  `status_code` int(11) DEFAULT NULL,
  `response_time_ms` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `entity_type` varchar(50) NOT NULL,
  `entity_id` int(11) DEFAULT NULL,
  `old_values` text DEFAULT NULL,
  `new_values` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blog_categories`
--

CREATE TABLE `blog_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `color` varchar(7) DEFAULT '#3B82F6',
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blog_categories`
--

INSERT INTO `blog_categories` (`id`, `name`, `slug`, `description`, `color`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Safari Adventures', 'safari-adventures', 'Wildlife and safari experiences', '#10B981', 'active', '2025-10-23 05:54:06', '2025-10-23 05:54:06'),
(2, 'Cultural Experiences', 'cultural-experiences', 'Local culture and traditions', '#F59E0B', 'active', '2025-10-23 05:54:06', '2025-10-23 05:54:06'),
(3, 'City Explorations', 'city-explorations', 'Urban adventures and city tours', '#3B82F6', 'active', '2025-10-23 05:54:06', '2025-10-23 05:54:06'),
(4, 'Beach & Relaxation', 'beach-relaxation', 'Coastal and beach experiences', '#06B6D4', 'active', '2025-10-23 05:54:06', '2025-10-23 05:54:06'),
(5, 'Adventure Sports', 'adventure-sports', 'Thrilling outdoor activities', '#EF4444', 'active', '2025-10-23 05:54:06', '2025-10-23 05:54:06'),
(6, 'Food & Cuisine', 'food-cuisine', 'Culinary adventures and local food', '#8B5CF6', 'active', '2025-10-23 05:54:06', '2025-10-23 05:54:06'),
(7, 'Photography', 'photography', 'Travel photography and tips', '#EC4899', 'active', '2025-10-23 05:54:06', '2025-10-23 05:54:06'),
(8, 'Travel Tips', 'travel-tips', 'Helpful travel advice and guides', '#84CC16', 'active', '2025-10-23 05:54:06', '2025-10-23 05:54:06');

-- --------------------------------------------------------

--
-- Table structure for table `blog_comments`
--

CREATE TABLE `blog_comments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `author_name` varchar(100) NOT NULL,
  `author_email` varchar(150) NOT NULL,
  `content` text NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blog_likes`
--

CREATE TABLE `blog_likes` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_ip` varchar(45) NOT NULL,
  `user_email` varchar(150) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blog_posts`
--

CREATE TABLE `blog_posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `excerpt` text DEFAULT NULL,
  `content` longtext NOT NULL,
  `featured_image` varchar(500) DEFAULT NULL,
  `gallery` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gallery`)),
  `author_name` varchar(100) DEFAULT NULL,
  `author_email` varchar(150) DEFAULT NULL,
  `author_avatar` varchar(500) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `tour_id` int(11) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `status` enum('draft','pending','published','rejected') DEFAULT 'pending',
  `featured` tinyint(1) DEFAULT 0,
  `views` int(11) DEFAULT 0,
  `likes` int(11) DEFAULT 0,
  `rating` decimal(2,1) DEFAULT 0.0,
  `trip_date` date DEFAULT NULL,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blog_post_tags`
--

CREATE TABLE `blog_post_tags` (
  `post_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blog_tags`
--

CREATE TABLE `blog_tags` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blog_tags`
--

INSERT INTO `blog_tags` (`id`, `name`, `slug`, `created_at`) VALUES
(1, 'Kenya', 'kenya', '2025-10-23 05:54:06'),
(2, 'Tanzania', 'tanzania', '2025-10-23 05:54:06'),
(3, 'South Africa', 'south-africa', '2025-10-23 05:54:06'),
(4, 'Morocco', 'morocco', '2025-10-23 05:54:06'),
(5, 'Egypt', 'egypt', '2025-10-23 05:54:06'),
(6, 'Wildlife', 'wildlife', '2025-10-23 05:54:06'),
(7, 'Safari', 'safari', '2025-10-23 05:54:06'),
(8, 'Culture', 'culture', '2025-10-23 05:54:06'),
(9, 'Adventure', 'adventure', '2025-10-23 05:54:06'),
(10, 'Photography', 'photography', '2025-10-23 05:54:06'),
(11, 'Food', 'food', '2025-10-23 05:54:06'),
(12, 'Beach', 'beach', '2025-10-23 05:54:06'),
(13, 'Mountains', 'mountains', '2025-10-23 05:54:06'),
(14, 'Desert', 'desert', '2025-10-23 05:54:06'),
(15, 'Family Travel', 'family-travel', '2025-10-23 05:54:06'),
(16, 'Solo Travel', 'solo-travel', '2025-10-23 05:54:06'),
(17, 'Luxury', 'luxury', '2025-10-23 05:54:06'),
(18, 'Budget Travel', 'budget-travel', '2025-10-23 05:54:06'),
(19, 'Honeymoon', 'honeymoon', '2025-10-23 05:54:06'),
(20, 'Group Travel', 'group-travel', '2025-10-23 05:54:06');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `booking_reference` varchar(20) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `tour_id` int(11) NOT NULL,
  `advisor_id` int(11) DEFAULT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `customer_phone` varchar(20) DEFAULT NULL,
  `travel_date` date NOT NULL,
  `participants` int(11) NOT NULL DEFAULT 1,
  `total_amount` decimal(10,2) NOT NULL,
  `commission_amount` decimal(10,2) DEFAULT 0.00,
  `status` enum('pending','confirmed','paid','cancelled','completed') DEFAULT 'pending',
  `payment_status` enum('pending','partial','paid','refunded') DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `booking_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `confirmed_date` timestamp NULL DEFAULT NULL,
  `cancelled_date` timestamp NULL DEFAULT NULL,
  `total_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `advisor_reference` varchar(50) DEFAULT NULL COMMENT 'Advisor reference code for tracking',
  `shared_link_id` varchar(100) DEFAULT NULL COMMENT 'Shared link identifier',
  `booking_source` enum('direct','advisor','mca','partner') DEFAULT 'direct',
  `commission_paid` tinyint(1) DEFAULT 0,
  `customer_rating` int(11) DEFAULT NULL CHECK (`customer_rating` >= 1 and `customer_rating` <= 5),
  `customer_review` text DEFAULT NULL,
  `follow_up_sent` tinyint(1) DEFAULT 0,
  `referred_by` int(11) DEFAULT NULL,
  `commission_level` int(11) DEFAULT 1,
  `emergency_contact` varchar(255) DEFAULT NULL,
  `accommodation_type` varchar(50) DEFAULT 'standard',
  `transport_type` varchar(50) DEFAULT 'shared',
  `special_requests` text DEFAULT NULL,
  `payment_intent_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `bookings`
--
DELIMITER $$
CREATE TRIGGER `calculate_commissions` AFTER UPDATE ON `bookings` FOR EACH ROW BEGIN
        -- Only process if advisor_id is NOT NULL
        IF NEW.status = 'confirmed' AND OLD.status != 'confirmed' AND NEW.advisor_id IS NOT NULL THEN
            -- Direct commission to advisor (10%)
            INSERT INTO commissions (booking_id, user_id, commission_type, commission_rate, commission_amount)
            VALUES (NEW.id, NEW.advisor_id, 'direct', 10.00, NEW.total_amount * 0.10);
        END IF;
    END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `calculate_mlm_commissions` AFTER UPDATE ON `bookings` FOR EACH ROW BEGIN
    DECLARE advisor_rank_val VARCHAR(50);
    DECLARE direct_rate DECIMAL(5,2);
    DECLARE l2_advisor INT;
    DECLARE l3_advisor INT;
    DECLARE mca INT;
    
    IF NEW.status = 'confirmed' AND OLD.status != 'confirmed' AND NEW.advisor_id IS NOT NULL THEN
        
        -- Get advisor rank and rate
        SELECT advisor_rank INTO advisor_rank_val FROM users WHERE id = NEW.advisor_id;
        SELECT direct_rate INTO direct_rate FROM commission_settings WHERE rank = advisor_rank_val;
        
        -- Direct commission (L1)
        INSERT INTO commissions (booking_id, user_id, commission_type, commission_rate, commission_amount)
        VALUES (NEW.id, NEW.advisor_id, 'direct', direct_rate, NEW.total_amount * (direct_rate / 100));
        
        -- L2 commission (upline)
        SELECT upline_id INTO l2_advisor FROM users WHERE id = NEW.advisor_id;
        IF l2_advisor IS NOT NULL THEN
            INSERT INTO commissions (booking_id, user_id, commission_type, commission_rate, commission_amount)
            VALUES (NEW.id, l2_advisor, 'level2', 10.00, NEW.total_amount * 0.10);
            
            -- L3 commission (upline's upline)
            SELECT upline_id INTO l3_advisor FROM users WHERE id = l2_advisor;
            IF l3_advisor IS NOT NULL THEN
                INSERT INTO commissions (booking_id, user_id, commission_type, commission_rate, commission_amount)
                VALUES (NEW.id, l3_advisor, 'level3', 5.00, NEW.total_amount * 0.05);
            END IF;
        END IF;
        
        -- MCA override
        SELECT mca_id INTO mca FROM users WHERE id = NEW.advisor_id;
        IF mca IS NOT NULL THEN
            INSERT INTO commissions (booking_id, user_id, commission_type, commission_rate, commission_amount)
            VALUES (NEW.id, mca, 'mca_override', 7.50, NEW.total_amount * 0.075);
        END IF;
        
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `generate_booking_reference` BEFORE INSERT ON `bookings` FOR EACH ROW BEGIN
    DECLARE next_id INT;
    SELECT AUTO_INCREMENT INTO next_id 
    FROM information_schema.TABLES 
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'bookings';
    SET NEW.booking_reference = CONCAT('FYT', LPAD(next_id, 3, '0'));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_shared_link_stats` AFTER INSERT ON `bookings` FOR EACH ROW BEGIN
    IF NEW.shared_link_id IS NOT NULL THEN
        UPDATE shared_links 
        SET conversions = conversions + 1 
        WHERE link_code = NEW.shared_link_id;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_tour_stats_after_booking` AFTER INSERT ON `bookings` FOR EACH ROW BEGIN
    UPDATE tours 
    SET booking_count = booking_count + 1,
        last_booked_date = NOW(),
        popularity_score = popularity_score + 1
    WHERE id = NEW.tour_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `booking_activities`
--

CREATE TABLE `booking_activities` (
  `id` int(11) NOT NULL,
  `activity_name` varchar(255) NOT NULL,
  `activity_image` varchar(500) DEFAULT NULL,
  `location` varchar(255) NOT NULL,
  `duration` varchar(50) NOT NULL,
  `activity_type` varchar(100) NOT NULL,
  `includes` text DEFAULT NULL,
  `price_per_person` decimal(10,2) NOT NULL,
  `rating` decimal(2,1) DEFAULT 0.0,
  `reviews_count` int(11) DEFAULT 0,
  `available_slots` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking_activities`
--

INSERT INTO `booking_activities` (`id`, `activity_name`, `activity_image`, `location`, `duration`, `activity_type`, `includes`, `price_per_person`, `rating`, `reviews_count`, `available_slots`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Akagera Safari Tour', 'https://images.unsplash.com/photo-1516426122078-c23e76319801?w=200', 'Akagera National Park', 'Full Day', 'Wildlife Safari', 'Guide, Transport, Lunch', 120.00, 4.8, 178, 30, 'active', '2025-10-28 09:39:47', '2025-10-28 09:39:47'),
(2, 'Gorilla Trekking Experience', 'https://images.unsplash.com/photo-1551918120-9739cb430c6d?w=200', 'Volcanoes National Park', 'Full Day', 'Wildlife Trekking', 'Permit, Guide, Transport', 1500.00, 5.0, 412, 15, 'active', '2025-10-28 09:39:47', '2025-10-28 09:39:47');

-- --------------------------------------------------------

--
-- Table structure for table `booking_cars`
--

CREATE TABLE `booking_cars` (
  `id` int(11) NOT NULL,
  `car_name` varchar(255) NOT NULL,
  `car_image` varchar(500) DEFAULT NULL,
  `car_type` varchar(100) NOT NULL,
  `seats` int(11) NOT NULL,
  `bags` int(11) NOT NULL,
  `transmission` enum('automatic','manual') DEFAULT 'automatic',
  `price_per_day` decimal(10,2) NOT NULL,
  `rating` decimal(2,1) DEFAULT 0.0,
  `available_units` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking_cars`
--

INSERT INTO `booking_cars` (`id`, `car_name`, `car_image`, `car_type`, `seats`, `bags`, `transmission`, `price_per_day`, `rating`, `available_units`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Toyota Land Cruiser', 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?w=200', 'SUV', 7, 4, 'automatic', 95.00, 4.7, 5, 'active', '2025-10-28 09:39:47', '2025-10-28 09:39:47'),
(2, 'Toyota Camry', 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?w=200', 'Sedan', 5, 3, 'automatic', 65.00, 4.5, 8, 'active', '2025-10-28 09:39:47', '2025-10-28 09:39:47');

-- --------------------------------------------------------

--
-- Table structure for table `booking_cruises`
--

CREATE TABLE `booking_cruises` (
  `id` int(11) NOT NULL,
  `cruise_name` varchar(255) NOT NULL,
  `cruise_image` varchar(500) DEFAULT NULL,
  `destination` varchar(255) NOT NULL,
  `duration_days` int(11) NOT NULL,
  `ports_count` int(11) DEFAULT 0,
  `cruise_type` varchar(100) DEFAULT 'Luxury Cruise',
  `price_per_person` decimal(10,2) NOT NULL,
  `rating` decimal(2,1) DEFAULT 0.0,
  `reviews_count` int(11) DEFAULT 0,
  `available_cabins` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking_cruises`
--

INSERT INTO `booking_cruises` (`id`, `cruise_name`, `cruise_image`, `destination`, `duration_days`, `ports_count`, `cruise_type`, `price_per_person`, `rating`, `reviews_count`, `available_cabins`, `status`, `created_at`, `updated_at`) VALUES
(1, 'East African Coast Explorer', 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=200', 'East African Coast', 7, 5, 'Luxury Cruise', 1450.00, 4.9, 78, 10, 'active', '2025-10-28 09:39:47', '2025-10-28 09:39:47');

-- --------------------------------------------------------

--
-- Stand-in structure for view `booking_details_view`
-- (See below for the actual view)
--
CREATE TABLE `booking_details_view` (
`id` int(11)
,`booking_reference` varchar(20)
,`customer_name` varchar(255)
,`customer_email` varchar(255)
,`travel_date` date
,`participants` int(11)
,`total_amount` decimal(10,2)
,`status` enum('pending','confirmed','paid','cancelled','completed')
,`booking_date` timestamp
,`tour_name` varchar(255)
,`destination` varchar(255)
,`destination_country` varchar(100)
,`client_name` varchar(201)
,`advisor_name` varchar(201)
);

-- --------------------------------------------------------

--
-- Table structure for table `booking_engine_orders`
--

CREATE TABLE `booking_engine_orders` (
  `id` int(11) NOT NULL,
  `booking_reference` varchar(50) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `booking_type` enum('flight','hotel','car','cruise','activity') NOT NULL,
  `item_id` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `customer_phone` varchar(50) DEFAULT NULL,
  `booking_date` date NOT NULL,
  `return_date` date DEFAULT NULL,
  `passengers` int(11) DEFAULT 1,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','confirmed','paid','completed','cancelled') DEFAULT 'pending',
  `payment_status` enum('unpaid','paid','refunded') DEFAULT 'unpaid',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking_flights`
--

CREATE TABLE `booking_flights` (
  `id` int(11) NOT NULL,
  `airline_name` varchar(255) NOT NULL,
  `airline_logo` varchar(500) DEFAULT NULL,
  `from_city` varchar(255) NOT NULL,
  `to_city` varchar(255) NOT NULL,
  `departure_time` time NOT NULL,
  `arrival_time` time NOT NULL,
  `flight_type` enum('non-stop','1-stop','2-stop') DEFAULT 'non-stop',
  `duration` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `rating` decimal(2,1) DEFAULT 0.0,
  `reviews_count` int(11) DEFAULT 0,
  `available_seats` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking_flights`
--

INSERT INTO `booking_flights` (`id`, `airline_name`, `airline_logo`, `from_city`, `to_city`, `departure_time`, `arrival_time`, `flight_type`, `duration`, `price`, `rating`, `reviews_count`, `available_seats`, `status`, `created_at`, `updated_at`) VALUES
(1, 'RwandAir', 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/24/RwandAir_Logo.svg/200px-RwandAir_Logo.svg.png', 'Lahore', 'Kigali', '08:30:00', '11:25:00', 'non-stop', '2h 55m', 245.00, 4.5, 89, 50, 'active', '2025-10-28 09:39:46', '2025-10-28 09:39:46'),
(2, 'Emirates', 'https://upload.wikimedia.org/wikipedia/commons/thumb/d/d0/Emirates_logo.svg/200px-Emirates_logo.svg.png', 'Lahore', 'Kigali', '10:15:00', '14:45:00', '1-stop', '4h 30m', 289.00, 4.7, 156, 30, 'active', '2025-10-28 09:39:46', '2025-10-28 09:39:46'),
(3, 'Qatar Airways', 'https://upload.wikimedia.org/wikipedia/en/thumb/9/9b/Qatar_Airways_Logo.svg/200px-Qatar_Airways_Logo.svg.png', 'Lahore', 'Kigali', '06:00:00', '10:30:00', '1-stop', '4h 30m', 312.00, 4.6, 203, 40, 'active', '2025-10-28 09:39:46', '2025-10-28 09:39:46');

-- --------------------------------------------------------

--
-- Table structure for table `booking_hotels`
--

CREATE TABLE `booking_hotels` (
  `id` int(11) NOT NULL,
  `hotel_name` varchar(255) NOT NULL,
  `hotel_image` varchar(500) DEFAULT NULL,
  `location` varchar(255) NOT NULL,
  `room_type` varchar(100) NOT NULL,
  `amenities` text DEFAULT NULL,
  `price_per_night` decimal(10,2) NOT NULL,
  `rating` decimal(2,1) DEFAULT 0.0,
  `reviews_count` int(11) DEFAULT 0,
  `available_rooms` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking_hotels`
--

INSERT INTO `booking_hotels` (`id`, `hotel_name`, `hotel_image`, `location`, `room_type`, `amenities`, `price_per_night`, `rating`, `reviews_count`, `available_rooms`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Kigali Serena Hotel', 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=200', 'Kigali City Center', 'Deluxe Room', 'Free WiFi, Pool, Spa, Restaurant', 180.00, 4.8, 342, 20, 'active', '2025-10-28 09:39:47', '2025-10-28 09:39:47'),
(2, 'Radisson Blu Hotel', 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=200', 'Kigali Convention Center', 'Superior Room', 'Pool, Spa, Free WiFi, Gym', 165.00, 4.6, 289, 15, 'active', '2025-10-28 09:39:47', '2025-10-28 09:39:47');

-- --------------------------------------------------------

--
-- Table structure for table `booking_inquiries`
--

CREATE TABLE `booking_inquiries` (
  `id` int(11) NOT NULL,
  `tour_id` int(11) DEFAULT NULL,
  `tour_name` varchar(255) DEFAULT NULL,
  `client_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `address` text DEFAULT NULL,
  `adults` int(11) DEFAULT 0,
  `children` varchar(255) DEFAULT NULL,
  `travel_dates` varchar(255) DEFAULT NULL,
  `flexible` varchar(10) DEFAULT NULL,
  `budget` varchar(100) DEFAULT NULL,
  `categories` text DEFAULT NULL,
  `destinations` text DEFAULT NULL,
  `activities` text DEFAULT NULL,
  `group_type` text DEFAULT NULL,
  `group_size` varchar(50) DEFAULT NULL,
  `group_leader` varchar(255) DEFAULT NULL,
  `group_leader_contact` varchar(255) DEFAULT NULL,
  `departure_city` varchar(255) DEFAULT NULL,
  `seat_preference` varchar(50) DEFAULT NULL,
  `airline` varchar(255) DEFAULT NULL,
  `class` varchar(50) DEFAULT NULL,
  `hotel_prefs` text DEFAULT NULL,
  `room_type` text DEFAULT NULL,
  `services` text DEFAULT NULL,
  `referral` text DEFAULT NULL,
  `referral_name` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking_inquiries`
--

INSERT INTO `booking_inquiries` (`id`, `tour_id`, `tour_name`, `client_name`, `email`, `phone`, `address`, `adults`, `children`, `travel_dates`, `flexible`, `budget`, `categories`, `destinations`, `activities`, `group_type`, `group_size`, `group_leader`, `group_leader_contact`, `departure_city`, `seat_preference`, `airline`, `class`, `hotel_prefs`, `room_type`, `services`, `referral`, `referral_name`, `notes`, `status`, `created_at`) VALUES
(1, 1, 'Maasai Mara Safari Adventure', 'Jean Aime', 'baraime450@gmail.com', '+250788712679', 'kicukiro', 3, '2 childrine 1 with 3age and 5 ages', '26 November 2025', 'yes', '$4000', 'Adventure Tours', 'African Tours', 'Sightseeing / History, Culture / Arts, Active / Sports', 'Family', '11-15', 'Your Advisor', '0781234567', 'Musanze', 'Middle', 'Rwanda Air', 'Premium', 'Luxury Resort', 'Garden View', 'Car Rental', 'Facebook', 'Advisor', 'waiting your answer waiting your answerwaiting your answerwaiting your answerwaiting your answerwaiting your answerwaiting your answerwaiting your answerwaiting your answer', 'pending', '2025-10-26 11:23:34'),
(2, 1, 'Maasai Mara Safari Adventure', 'Jean Aime', 'baraime450@gmail.com', '+250788712679', 'kicukiro', 3, '2 childrine 1 with 3age and 5 ages', '26 November 2025', 'yes', '$4000', 'Adventure Tours', 'African Tours', 'Sightseeing / History, Culture / Arts, Active / Sports', 'Family', '11-15', 'Your Advisor', '0781234567', 'Musanze', 'Middle', 'Rwanda Air', 'Premium', 'Luxury Resort', 'Garden View', 'Car Rental', 'Facebook', 'Advisor', 'waiting your answer waiting your answerwaiting your answerwaiting your answerwaiting your answerwaiting your answerwaiting your answerwaiting your answerwaiting your answer', 'pending', '2025-10-26 11:25:47');

-- --------------------------------------------------------

--
-- Table structure for table `booking_modifications`
--

CREATE TABLE `booking_modifications` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `modification_type` enum('date_change','guest_change','package_change','cancellation') NOT NULL,
  `old_data` text DEFAULT NULL,
  `new_data` text DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `requested_by` int(11) NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `processed_by` int(11) DEFAULT NULL,
  `processed_at` timestamp NULL DEFAULT NULL,
  `fee_amount` decimal(10,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking_payments`
--

CREATE TABLE `booking_payments` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) DEFAULT 'stripe',
  `transaction_id` varchar(100) DEFAULT NULL,
  `stripe_payment_intent_id` varchar(100) DEFAULT NULL,
  `stripe_charge_id` varchar(100) DEFAULT NULL,
  `status` enum('pending','completed','failed','refunded') DEFAULT 'pending',
  `payment_date` timestamp NULL DEFAULT NULL,
  `refund_amount` decimal(10,2) DEFAULT 0.00,
  `refund_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking_status_history`
--

CREATE TABLE `booking_status_history` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `old_status` varchar(50) DEFAULT NULL,
  `new_status` varchar(50) NOT NULL,
  `changed_by` int(11) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `client_registry`
--

CREATE TABLE `client_registry` (
  `id` int(11) NOT NULL,
  `client_name` varchar(255) NOT NULL,
  `client_email` varchar(255) NOT NULL,
  `client_phone` varchar(50) NOT NULL,
  `client_country` varchar(100) DEFAULT NULL,
  `client_interest` text DEFAULT NULL,
  `portal_code` varchar(50) NOT NULL,
  `portal_url` varchar(500) DEFAULT NULL,
  `owned_by_user_id` int(11) NOT NULL,
  `owned_by_name` varchar(255) DEFAULT NULL,
  `owned_by_role` enum('admin','advisor','mca') NOT NULL,
  `ownership_status` enum('active','expired','transferred') DEFAULT 'active',
  `expires_at` timestamp NULL DEFAULT NULL,
  `source` varchar(50) DEFAULT NULL,
  `last_activity` timestamp NULL DEFAULT NULL,
  `portal_views` int(11) DEFAULT 0,
  `total_bookings` int(11) DEFAULT 0,
  `total_revenue` decimal(10,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `transferred_to` int(11) DEFAULT NULL,
  `transfer_reason` text DEFAULT NULL,
  `transfer_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `client_registry`
--

INSERT INTO `client_registry` (`id`, `client_name`, `client_email`, `client_phone`, `client_country`, `client_interest`, `portal_code`, `portal_url`, `owned_by_user_id`, `owned_by_name`, `owned_by_role`, `ownership_status`, `expires_at`, `source`, `last_activity`, `portal_views`, `total_bookings`, `total_revenue`, `created_at`, `created_by`, `transferred_to`, `transfer_reason`, `transfer_date`) VALUES
(2, 'Jean Aime', 'baraime450@gmail.com', '+0788712679', 'Rwanda', '5 days Gorilla Trekking', 'JA-2025-033', 'https://localhost/portal.php?code=JA-2025-033', 21, 'Advisor User', 'advisor', 'active', '2025-12-23 22:49:41', 'instagram', '2025-11-25 09:59:47', 5, 0, 0.00, '2025-11-23 23:49:41', 21, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `commissions`
--

CREATE TABLE `commissions` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `commission_type` enum('direct','level2','level3','mca_override','performance_bonus') NOT NULL,
  `commission_rate` decimal(5,2) NOT NULL,
  `commission_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','approved','paid') DEFAULT 'pending',
  `paid_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `commission_settings`
--

CREATE TABLE `commission_settings` (
  `id` int(11) NOT NULL,
  `rank` varchar(50) NOT NULL,
  `direct_rate` decimal(5,2) NOT NULL,
  `level2_rate` decimal(5,2) DEFAULT 10.00,
  `level3_rate` decimal(5,2) DEFAULT 5.00,
  `mca_override_rate` decimal(5,2) DEFAULT 7.50,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `commission_settings`
--

INSERT INTO `commission_settings` (`id`, `rank`, `direct_rate`, `level2_rate`, `level3_rate`, `mca_override_rate`, `updated_at`) VALUES
(1, 'certified', 30.00, 10.00, 5.00, 7.50, '2025-11-23 18:06:52'),
(2, 'senior', 35.00, 10.00, 5.00, 7.50, '2025-11-23 18:06:52'),
(3, 'executive', 40.00, 10.00, 5.00, 7.50, '2025-11-23 18:06:52');

-- --------------------------------------------------------

--
-- Stand-in structure for view `commission_summary_view`
-- (See below for the actual view)
--
CREATE TABLE `commission_summary_view` (
`user_id` int(11)
,`user_name` varchar(201)
,`role` enum('super_admin','mca','advisor','client')
,`total_commissions` bigint(21)
,`total_earned` decimal(32,2)
,`total_paid` decimal(32,2)
,`total_pending` decimal(32,2)
);

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` int(11) NOT NULL,
  `region_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `country_code` varchar(3) NOT NULL,
  `description` text DEFAULT NULL,
  `hero_image` varchar(500) DEFAULT NULL,
  `about_text` longtext DEFAULT NULL,
  `tourism_highlights` text DEFAULT NULL,
  `popular_destinations` text DEFAULT NULL,
  `tourism_description` text DEFAULT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `cover_image` varchar(500) DEFAULT NULL,
  `gallery` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gallery`)),
  `highlights` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`highlights`)),
  `best_time_to_visit` varchar(200) DEFAULT NULL,
  `currency` varchar(50) DEFAULT NULL,
  `languages_spoken` text DEFAULT NULL,
  `capital` varchar(100) DEFAULT NULL,
  `population` varchar(50) DEFAULT NULL,
  `area` varchar(50) DEFAULT NULL,
  `timezone` varchar(100) DEFAULT NULL,
  `calling_code` varchar(20) DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `language` varchar(100) DEFAULT NULL,
  `featured` tinyint(1) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `visa_requirements` text DEFAULT NULL,
  `climate_info` text DEFAULT NULL,
  `safety_info` text DEFAULT NULL,
  `health_requirements` text DEFAULT NULL,
  `transportation_info` text DEFAULT NULL,
  `cuisine_info` text DEFAULT NULL,
  `cultural_info` text DEFAULT NULL,
  `attractions` text DEFAULT NULL,
  `activities` text DEFAULT NULL,
  `accommodation_info` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `region_id`, `name`, `slug`, `country_code`, `description`, `hero_image`, `about_text`, `tourism_highlights`, `popular_destinations`, `tourism_description`, `image_url`, `cover_image`, `gallery`, `highlights`, `best_time_to_visit`, `currency`, `languages_spoken`, `capital`, `population`, `area`, `timezone`, `calling_code`, `meta_title`, `meta_description`, `language`, `featured`, `status`, `created_at`, `updated_at`, `visa_requirements`, `climate_info`, `safety_info`, `health_requirements`, `transportation_info`, `cuisine_info`, `cultural_info`, `attractions`, `activities`, `accommodation_info`) VALUES
(1, 6, 'Egypt', 'visit-eg', 'EGY', 'Land of the Pharaohs with ancient pyramids and the Nile River.', NULL, NULL, NULL, NULL, 'Explore ancient wonders including the Pyramids of Giza, Valley of the Kings, and cruise the legendary Nile River.', 'https://images.unsplash.com/photo-1539650116574-75c0c6d73f6e?auto=format&fit=crop&w=800&q=80', NULL, NULL, NULL, 'October to April', 'Egyptian Pound (EGP)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Arabic', 1, 'inactive', '2025-10-22 18:58:55', '2025-11-15 10:13:21', 'Most visitors require visa. Visa on arrival available for some nationalities.', 'Desert climate with hot, dry summers and mild winters. Best visited October-April.', 'Generally safe for tourists. Follow standard travel precautions and local guidelines.', 'Standard vaccinations recommended. Consult travel health clinic before departure.', 'Well-developed transport network including airports, roads, and public transport.', 'Traditional Egyptian cuisine featuring ful medames, koshari, molokhia, and fresh seafood.', 'Diverse cultural heritage with warm, welcoming people and unique traditions.', 'Pyramids of Giza, Valley of the Kings, Karnak Temple, Abu Simbel, Nile River cruises.', 'Nile cruises, pyramid tours, desert safaris, Red Sea diving, cultural site visits.', 'Various accommodation options from budget to luxury available.'),
(2, 6, 'Morocco', 'visit-ma', 'MAR', 'Imperial cities, Sahara Desert, and Atlas Mountains.', NULL, NULL, NULL, NULL, 'Discover the magic of Morocco with its bustling souks, stunning architecture, and diverse landscapes.', 'https://images.unsplash.com/photo-1489749798305-4fea3ae436d3?auto=format&fit=crop&w=800&q=80', NULL, NULL, NULL, 'March to May, September to November', 'Moroccan Dirham (MAD)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Arabic, French', 1, 'inactive', '2025-10-22 18:58:55', '2025-11-15 10:13:21', 'Visa required for most visitors. Available on arrival or online for many countries.', 'Mediterranean coast, Atlas Mountains, and Sahara Desert create diverse climates.', 'Generally safe for tourists. Follow standard travel precautions and local guidelines.', 'Standard vaccinations recommended. Consult travel health clinic before departure.', 'Well-developed transport network including airports, roads, and public transport.', 'Moroccan tagines, couscous, pastilla, mint tea, and diverse Berber and Arab dishes.', 'Diverse cultural heritage with warm, welcoming people and unique traditions.', 'Marrakech medina, Fez old city, Sahara Desert, Atlas Mountains, Casablanca.', 'Desert camping, Atlas trekking, medina tours, cooking classes, hammam experiences.', 'Various accommodation options from budget to luxury available.'),
(3, 6, 'Tunisia', 'visit-tn', 'TUN', 'Mediterranean beaches and ancient Carthage ruins.', NULL, NULL, NULL, NULL, 'Experience Tunisia\'s rich history from ancient Carthage to Islamic architecture.', 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?auto=format&fit=crop&w=800&q=80', NULL, NULL, NULL, 'March to May, September to November', 'Tunisian Dinar (TND)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Arabic, French', 0, 'active', '2025-10-22 18:58:55', '2025-11-17 07:46:18', 'Visa requirements vary by nationality. Check with embassy or consulate.', 'Diverse climate with seasonal variations. Check weather patterns for your travel dates.', 'Generally safe for tourists. Follow standard travel precautions and local guidelines.', 'Standard vaccinations recommended. Consult travel health clinic before departure.', 'Well-developed transport network including airports, roads, and public transport.', 'Rich culinary traditions featuring local ingredients and traditional cooking methods.', 'Diverse cultural heritage with warm, welcoming people and unique traditions.', 'Numerous natural and cultural attractions suitable for all types of travelers.', 'Wide range of activities from adventure sports to cultural experiences.', 'Various accommodation options from budget to luxury available.'),
(4, 6, 'Nigeria', 'visit-ng', 'NGA', 'Most populous African country with vibrant culture.', NULL, NULL, NULL, NULL, 'Experience Nigeria\'s diverse cultures, from Nollywood entertainment to traditional festivals.', 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?auto=format&fit=crop&w=800&q=80', NULL, NULL, NULL, 'November to March', 'Nigerian Naira (NGN)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'English', 1, 'active', '2025-10-22 18:58:55', '2025-11-15 10:13:21', 'Visa requirements vary by nationality. Check with embassy or consulate.', 'Diverse climate with seasonal variations. Check weather patterns for your travel dates.', 'Generally safe for tourists. Follow standard travel precautions and local guidelines.', 'Standard vaccinations recommended. Consult travel health clinic before departure.', 'Well-developed transport network including airports, roads, and public transport.', 'Rich culinary traditions featuring local ingredients and traditional cooking methods.', 'Diverse cultural heritage with warm, welcoming people and unique traditions.', 'Numerous natural and cultural attractions suitable for all types of travelers.', 'Wide range of activities from adventure sports to cultural experiences.', 'Various accommodation options from budget to luxury available.'),
(5, 6, 'Ghana', 'visit-gh', 'GHA', 'Gateway to West Africa with rich history.', NULL, NULL, NULL, NULL, 'Discover Ghana\'s role in African history, from ancient kingdoms to colonial forts.', 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?auto=format&fit=crop&w=800&q=80', NULL, NULL, NULL, 'November to March', 'Ghanaian Cedi (GHS)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'English', 1, 'inactive', '2025-10-22 18:58:55', '2025-11-15 10:13:21', 'Visa requirements vary by nationality. Check with embassy or consulate.', 'Diverse climate with seasonal variations. Check weather patterns for your travel dates.', 'Generally safe for tourists. Follow standard travel precautions and local guidelines.', 'Standard vaccinations recommended. Consult travel health clinic before departure.', 'Well-developed transport network including airports, roads, and public transport.', 'Rich culinary traditions featuring local ingredients and traditional cooking methods.', 'Diverse cultural heritage with warm, welcoming people and unique traditions.', 'Numerous natural and cultural attractions suitable for all types of travelers.', 'Wide range of activities from adventure sports to cultural experiences.', 'Various accommodation options from budget to luxury available.'),
(6, 6, 'Senegal', 'visit-sn', 'SEN', 'French colonial heritage and vibrant music scene.', NULL, NULL, NULL, NULL, 'Experience Senegal\'s rich musical heritage and colonial architecture in Dakar.', 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?auto=format&fit=crop&w=800&q=80', NULL, NULL, NULL, 'November to May', 'West African CFA Franc (XOF)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'French', 0, 'active', '2025-10-22 18:58:55', '2025-11-15 10:13:21', 'Visa requirements vary by nationality. Check with embassy or consulate.', 'Diverse climate with seasonal variations. Check weather patterns for your travel dates.', 'Generally safe for tourists. Follow standard travel precautions and local guidelines.', 'Standard vaccinations recommended. Consult travel health clinic before departure.', 'Well-developed transport network including airports, roads, and public transport.', 'Rich culinary traditions featuring local ingredients and traditional cooking methods.', 'Diverse cultural heritage with warm, welcoming people and unique traditions.', 'Numerous natural and cultural attractions suitable for all types of travelers.', 'Wide range of activities from adventure sports to cultural experiences.', 'Various accommodation options from budget to luxury available.'),
(7, 6, 'Kenya', 'visit-ke', 'KEN', 'Home to the Great Migration and Maasai culture.', NULL, NULL, NULL, NULL, 'Witness the spectacular Great Migration in Maasai Mara and experience rich Maasai culture.', 'https://images.unsplash.com/photo-1489392191049-fc10c97e64b6?auto=format&fit=crop&w=800&q=80', NULL, NULL, NULL, 'July to October, December to March', 'Kenyan Shilling (KES)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'English, Swahili', 1, 'active', '2025-10-22 18:58:55', '2025-11-17 07:44:21', 'Visa required for most visitors. Available on arrival for some nationalities.', 'Tropical climate with dry and wet seasons. Best visited June-October and December-March.', 'Generally safe for tourists. Follow standard travel precautions and local guidelines.', 'Standard vaccinations recommended. Consult travel health clinic before departure.', 'Well-developed transport network including airports, roads, and public transport.', 'Nyama choma, ugali, sukuma wiki, pilau rice, and fresh tropical fruits.', 'Diverse cultural heritage with warm, welcoming people and unique traditions.', 'Maasai Mara, Amboseli, Tsavo, Mount Kenya, Lamu Island, Great Rift Valley.', 'Safari game drives, mountain climbing, beach relaxation, cultural village visits.', 'Various accommodation options from budget to luxury available.'),
(8, 6, 'Tanzania', 'visit-tz', 'TZA', 'Serengeti, Kilimanjaro, and Zanzibar.', NULL, NULL, NULL, NULL, 'Experience the Serengeti\'s wildlife, climb Mount Kilimanjaro, and relax on Zanzibar beaches.', 'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=800&q=80', NULL, NULL, NULL, 'June to October, December to March', 'Tanzanian Shilling (TZS)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'English, Swahili', 1, 'active', '2025-10-22 18:58:55', '2025-11-17 07:45:46', 'Visa required. Available on arrival or online for most nationalities.', 'Tropical climate with dry and wet seasons. Best visited June-October and December-March.', 'Generally safe for tourists. Follow standard travel precautions and local guidelines.', 'Standard vaccinations recommended. Consult travel health clinic before departure.', 'Well-developed transport network including airports, roads, and public transport.', 'Ugali, nyama choma, pilau, fresh seafood, tropical fruits, and Zanzibari spices.', 'Diverse cultural heritage with warm, welcoming people and unique traditions.', 'Serengeti, Ngorongoro Crater, Mount Kilimanjaro, Zanzibar, Tarangire, Lake Manyara.', 'Safari tours, Kilimanjaro climbing, Zanzibar beaches, cultural tours, spice tours.', 'Various accommodation options from budget to luxury available.'),
(9, 6, 'Uganda', 'visit-ug', 'UGA', 'Pearl of Africa with mountain gorillas.', NULL, NULL, NULL, NULL, 'Track mountain gorillas in Bwindi Forest and explore the source of the Nile.', 'https://images.unsplash.com/photo-1547036967-23d11aacaee0?auto=format&fit=crop&w=800&q=80', NULL, NULL, NULL, 'June to August, December to February', 'Ugandan Shilling (UGX)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'English', 1, 'active', '2025-10-22 18:58:55', '2025-11-17 07:57:38', 'Visa requirements vary by nationality. Check with embassy or consulate.', 'Diverse climate with seasonal variations. Check weather patterns for your travel dates.', 'Generally safe for tourists. Follow standard travel precautions and local guidelines.', 'Standard vaccinations recommended. Consult travel health clinic before departure.', 'Well-developed transport network including airports, roads, and public transport.', 'Rich culinary traditions featuring local ingredients and traditional cooking methods.', 'Diverse cultural heritage with warm, welcoming people and unique traditions.', 'Numerous natural and cultural attractions suitable for all types of travelers.', 'Wide range of activities from adventure sports to cultural experiences.', 'Various accommodation options from budget to luxury available.'),
(10, 6, 'Rwanda', 'visit-rw', 'RWA', 'Land of a thousand hills and gorillas.', 'https://images.unsplash.com/photo-1609198092357-8e51c4b1d9f9?auto=format&fit=crop&w=2000&q=80', 'Rwanda, the \"Land of a Thousand Hills,\" is a remarkable East African nation known for its stunning scenery, rare mountain gorillas, and inspiring recovery story. This small but vibrant country offers world-class wildlife experiences, rich cultural heritage, and warm hospitality.', '[\"Mountain Gorilla Trekking\", \"Volcanoes National Park\", \"Nyungwe Forest\", \"Lake Kivu\", \"Kigali Genocide Memorial\", \"Akagera National Park\", \"Cultural Villages\", \"Coffee Plantations\"]', '[\"Volcanoes National Park\", \"Kigali City\", \"Lake Kivu\", \"Nyungwe National Park\", \"Akagera National Park\"]', 'Experience Rwanda\'s remarkable recovery and track mountain gorillas.', 'https://images.unsplash.com/photo-1547036967-23d11aacaee0?auto=format&fit=crop&w=800&q=80', NULL, NULL, NULL, 'June to September (Dry Season)', 'Rwandan Franc (RWF)', '[\"Kinyarwanda\", \"English\", \"French\", \"Swahili\"]', 'Kigali', '13 million', '26,338 km²', 'CAT (UTC+2)', '+250', 'Visit Rwanda - Gorilla Trekking & Luxury Tours', 'Experience the beauty of Rwanda with Forever Young Tours. Trek with mountain gorillas, explore pristine national parks, and discover the warmth of Rwandan hospitality.', 'English, French, Kinyarwanda', 1, 'active', '2025-10-22 18:58:55', '2025-11-07 17:03:07', 'Visa on arrival or e-visa available for most nationalities', 'Diverse climate with seasonal variations. Check weather patterns for your travel dates.', 'Generally safe for tourists. Follow standard travel precautions and local guidelines.', 'Standard vaccinations recommended. Consult travel health clinic before departure.', 'Well-developed transport network including airports, roads, and public transport.', 'Rich culinary traditions featuring local ingredients and traditional cooking methods.', 'Diverse cultural heritage with warm, welcoming people and unique traditions.', 'Numerous natural and cultural attractions suitable for all types of travelers.', 'Wide range of activities from adventure sports to cultural experiences.', 'Various accommodation options from budget to luxury available.'),
(11, 6, 'Ethiopia', 'visit-et', 'ETH', 'Cradle of humanity with ancient history.', NULL, NULL, NULL, NULL, 'Discover the birthplace of coffee and ancient rock churches of Lalibela.', 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?auto=format&fit=crop&w=800&q=80', NULL, NULL, NULL, 'October to March', 'Ethiopian Birr (ETB)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Amharic', 0, 'inactive', '2025-10-22 18:58:55', '2025-11-15 10:13:21', 'Visa requirements vary by nationality. Check with embassy or consulate.', 'Diverse climate with seasonal variations. Check weather patterns for your travel dates.', 'Generally safe for tourists. Follow standard travel precautions and local guidelines.', 'Standard vaccinations recommended. Consult travel health clinic before departure.', 'Well-developed transport network including airports, roads, and public transport.', 'Rich culinary traditions featuring local ingredients and traditional cooking methods.', 'Diverse cultural heritage with warm, welcoming people and unique traditions.', 'Numerous natural and cultural attractions suitable for all types of travelers.', 'Wide range of activities from adventure sports to cultural experiences.', 'Various accommodation options from budget to luxury available.'),
(12, 6, 'Democratic Republic of Congo', 'visit-cd', 'COD', 'Heart of Africa with vast rainforests.', NULL, NULL, NULL, NULL, 'Explore the Congo Basin rainforest and encounter unique wildlife including bonobos.', 'https://images.unsplash.com/photo-1547036967-23d11aacaee0?auto=format&fit=crop&w=800&q=80', NULL, NULL, NULL, 'May to September', 'Congolese Franc (CDF)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'French', 0, 'inactive', '2025-10-22 18:58:55', '2025-11-15 10:13:21', 'Visa requirements vary by nationality. Check with embassy or consulate.', 'Diverse climate with seasonal variations. Check weather patterns for your travel dates.', 'Generally safe for tourists. Follow standard travel precautions and local guidelines.', 'Standard vaccinations recommended. Consult travel health clinic before departure.', 'Well-developed transport network including airports, roads, and public transport.', 'Rich culinary traditions featuring local ingredients and traditional cooking methods.', 'Diverse cultural heritage with warm, welcoming people and unique traditions.', 'Numerous natural and cultural attractions suitable for all types of travelers.', 'Wide range of activities from adventure sports to cultural experiences.', 'Various accommodation options from budget to luxury available.'),
(13, 6, 'Cameroon', 'visit-cm', 'CMR', 'Africa in miniature with diverse landscapes.', NULL, NULL, NULL, NULL, 'Experience Africa in miniature with diverse landscapes from rainforests to savannas.', 'https://images.unsplash.com/photo-1547036967-23d11aacaee0?auto=format&fit=crop&w=800&q=80', NULL, NULL, NULL, 'November to February', 'Central African CFA Franc (XAF)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'French, English', 0, 'inactive', '2025-10-22 18:58:55', '2025-11-15 10:13:20', 'Visa requirements vary by nationality. Check with embassy or consulate.', 'Diverse climate with seasonal variations. Check weather patterns for your travel dates.', 'Generally safe for tourists. Follow standard travel precautions and local guidelines.', 'Standard vaccinations recommended. Consult travel health clinic before departure.', 'Well-developed transport network including airports, roads, and public transport.', 'Rich culinary traditions featuring local ingredients and traditional cooking methods.', 'Diverse cultural heritage with warm, welcoming people and unique traditions.', 'Numerous natural and cultural attractions suitable for all types of travelers.', 'Wide range of activities from adventure sports to cultural experiences.', 'Various accommodation options from budget to luxury available.'),
(14, 6, 'South Africa', 'visit-za', 'ZAF', 'Rainbow nation with diverse attractions.', NULL, NULL, NULL, NULL, 'Experience Cape Town\'s beauty, Kruger\'s wildlife, and wine regions.', 'https://images.unsplash.com/photo-1484318571209-661cf29a69ea?auto=format&fit=crop&w=800&q=80', NULL, NULL, NULL, 'April to May, September to November', 'South African Rand (ZAR)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'English, Afrikaans, Zulu', 1, 'active', '2025-10-22 18:58:55', '2025-11-17 07:48:24', 'No visa required for stays up to 90 days for most countries.', 'Mediterranean climate with mild, wet winters and warm, dry summers.', 'Generally safe for tourists. Follow standard travel precautions and local guidelines.', 'Standard vaccinations recommended. Consult travel health clinic before departure.', 'Well-developed transport network including airports, roads, and public transport.', 'Braai (BBQ), bobotie, biltong, boerewors, Cape Malay curry, and world-class wines.', 'Diverse cultural heritage with warm, welcoming people and unique traditions.', 'Table Mountain, Cape Winelands, Kruger National Park, Garden Route, Robben Island.', 'Wine tours, safari drives, shark cage diving, township tours, mountain hiking.', 'Various accommodation options from budget to luxury available.'),
(15, 6, 'Zimbabwe', 'visit-zw', 'ZWE', 'Victoria Falls and ancient ruins.', NULL, NULL, NULL, NULL, 'Marvel at Victoria Falls and explore Great Zimbabwe ruins.', 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?auto=format&fit=crop&w=800&q=80', NULL, NULL, NULL, 'April to October', 'US Dollar (USD)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'English', 1, 'inactive', '2025-10-22 18:58:55', '2025-11-15 10:13:22', 'Visa requirements vary by nationality. Check with embassy or consulate.', 'Diverse climate with seasonal variations. Check weather patterns for your travel dates.', 'Generally safe for tourists. Follow standard travel precautions and local guidelines.', 'Standard vaccinations recommended. Consult travel health clinic before departure.', 'Well-developed transport network including airports, roads, and public transport.', 'Rich culinary traditions featuring local ingredients and traditional cooking methods.', 'Diverse cultural heritage with warm, welcoming people and unique traditions.', 'Numerous natural and cultural attractions suitable for all types of travelers.', 'Wide range of activities from adventure sports to cultural experiences.', 'Various accommodation options from budget to luxury available.'),
(16, 6, 'Botswana', 'visit-bw', 'BWA', 'Okavango Delta and pristine wilderness.', NULL, NULL, NULL, NULL, 'Discover the Okavango Delta\'s unique ecosystem and excellent wildlife viewing.', 'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=800&q=80', NULL, NULL, NULL, 'May to September', 'Botswana Pula (BWP)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'English, Setswana', 1, 'inactive', '2025-10-22 18:58:55', '2025-11-15 10:13:19', 'Visa requirements vary by nationality. Check with embassy or consulate.', 'Diverse climate with seasonal variations. Check weather patterns for your travel dates.', 'Generally safe for tourists. Follow standard travel precautions and local guidelines.', 'Standard vaccinations recommended. Consult travel health clinic before departure.', 'Well-developed transport network including airports, roads, and public transport.', 'Rich culinary traditions featuring local ingredients and traditional cooking methods.', 'Diverse cultural heritage with warm, welcoming people and unique traditions.', 'Numerous natural and cultural attractions suitable for all types of travelers.', 'Wide range of activities from adventure sports to cultural experiences.', 'Various accommodation options from budget to luxury available.'),
(17, 6, 'Namibia', 'visit-na', 'NAM', 'Sossusvlei dunes and Skeleton Coast.', NULL, NULL, NULL, NULL, 'Discover the world\'s highest sand dunes and unique desert-adapted wildlife.', 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?auto=format&fit=crop&w=800&q=80', NULL, NULL, NULL, 'May to October', 'Namibian Dollar (NAD)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'English', 1, 'inactive', '2025-10-22 18:58:55', '2025-11-15 10:13:21', 'Visa requirements vary by nationality. Check with embassy or consulate.', 'Diverse climate with seasonal variations. Check weather patterns for your travel dates.', 'Generally safe for tourists. Follow standard travel precautions and local guidelines.', 'Standard vaccinations recommended. Consult travel health clinic before departure.', 'Well-developed transport network including airports, roads, and public transport.', 'Rich culinary traditions featuring local ingredients and traditional cooking methods.', 'Diverse cultural heritage with warm, welcoming people and unique traditions.', 'Numerous natural and cultural attractions suitable for all types of travelers.', 'Wide range of activities from adventure sports to cultural experiences.', 'Various accommodation options from budget to luxury available.'),
(18, 6, 'Malawi', 'visit-ml', 'Ml', 'winner-rwanda-na-vision-fc-bongereye-amasezerano-y-ubufatanye-nk-umuterankunga-mukuru-amafoto-1', NULL, NULL, NULL, NULL, NULL, 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wCEAAkGBwgHBgkIBwgKCgkLDRYPDQwMDRsUFRAWIB0iIiAdHx8kKDQsJCYxJx8fLT0tMTU3Ojo6Iys/RD84QzQ5OjcBCgoKDQwNGg8PGjclHyU3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3N//AABEIAI0AjQMBEQACEQEDEQH/xAAcAAACAwEBAQEAAAAAAAAAAAADBQIEBgEHAAj/xAA7EAACAQMDAQYEBQIFAwUAAAABAgMABBEFEiExBhMiQVFxMmGBkRQjobHBQtEkM1Lh8CXC8RdDYoKS/8QAGwEAAgMBAQEAAAAAAAAAAAAAAQIAAwQFBgf/xAAzEQACAQIEAggGAgIDAAAAAAAAAQIDEQQSITEFQRMiMlFhcdHwI4GRobHBFOFC8RWC4v/aAAwDAQACEQMRA', NULL, NULL, NULL, 'November to February', 'Ml', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'zuru', 0, 'active', '2025-11-16 11:05:16', '2025-11-16 22:35:40', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `coupon_usage`
--

CREATE TABLE `coupon_usage` (
  `id` int(11) NOT NULL,
  `coupon_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `discount_amount` decimal(10,2) NOT NULL,
  `used_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `destinations`
--

CREATE TABLE `destinations` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `country` varchar(100) NOT NULL,
  `region` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `featured` tinyint(1) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `destinations`
--

INSERT INTO `destinations` (`id`, `name`, `country`, `region`, `description`, `image_url`, `featured`, `status`, `created_at`) VALUES
(1, 'Maasai Mara National Reserve', 'Kenya', 'Rift Valley', 'World-famous wildlife reserve known for the Great Migration', NULL, 1, 'active', '2025-10-22 14:29:47'),
(2, 'Serengeti National Park', 'Tanzania', 'Northern Tanzania', 'Endless plains teeming with wildlife', NULL, 1, 'active', '2025-10-22 14:29:47'),
(3, 'Mount Kilimanjaro', 'Tanzania', 'Northern Tanzania', 'Africa\'s highest mountain and world\'s tallest free-standing mountain', NULL, 1, 'active', '2025-10-22 14:29:47'),
(4, 'Zanzibar Archipelago', 'Tanzania', 'Indian Ocean', 'Tropical paradise with pristine beaches and rich culture', NULL, 1, 'active', '2025-10-22 14:29:47'),
(5, 'Amboseli National Park', 'Kenya', 'Kajiado County', 'Famous for large elephant herds and views of Mount Kilimanjaro', NULL, 0, 'active', '2025-10-22 14:29:47');

-- --------------------------------------------------------

--
-- Table structure for table `discount_coupons`
--

CREATE TABLE `discount_coupons` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `discount_type` enum('percentage','fixed') NOT NULL,
  `discount_value` decimal(10,2) NOT NULL,
  `min_purchase` decimal(10,2) DEFAULT 0.00,
  `max_discount` decimal(10,2) DEFAULT NULL,
  `usage_limit` int(11) DEFAULT NULL,
  `used_count` int(11) DEFAULT 0,
  `valid_from` timestamp NOT NULL DEFAULT current_timestamp(),
  `valid_until` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `discount_coupons`
--

INSERT INTO `discount_coupons` (`id`, `code`, `description`, `discount_type`, `discount_value`, `min_purchase`, `max_discount`, `usage_limit`, `used_count`, `valid_from`, `valid_until`, `is_active`, `created_at`) VALUES
(1, 'WELCOME10', 'Welcome discount - 10% off first order', 'percentage', 10.00, 50.00, NULL, NULL, 0, '2025-11-07 16:25:48', '2026-11-07 16:25:48', 1, '2025-11-07 16:25:48'),
(2, 'SUMMER25', 'Summer sale - 25% off', 'percentage', 25.00, 100.00, NULL, NULL, 0, '2025-11-07 16:25:48', '2026-02-07 16:25:48', 1, '2025-11-07 16:25:48'),
(3, 'SAVE50', 'Save $50 on orders over $200', 'fixed', 50.00, 200.00, NULL, NULL, 0, '2025-11-07 16:25:48', '2026-05-07 16:25:48', 1, '2025-11-07 16:25:48'),
(4, 'FREESHIP', 'Free shipping on all orders', 'fixed', 15.00, 0.00, NULL, NULL, 0, '2025-11-07 16:25:48', '2026-11-07 16:25:48', 1, '2025-11-07 16:25:48');

-- --------------------------------------------------------

--
-- Table structure for table `kyc_documents`
--

CREATE TABLE `kyc_documents` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `document_type` enum('national_id','passport','driving_license','utility_bill','bank_statement','business_license','tax_certificate','other') NOT NULL,
  `document_number` varchar(100) DEFAULT NULL,
  `document_url` varchar(500) NOT NULL,
  `expiry_date` date DEFAULT NULL,
  `verification_status` enum('pending','approved','rejected','expired') DEFAULT 'pending',
  `verified_by` int(11) DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kyc_status`
--

CREATE TABLE `kyc_status` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `overall_status` enum('not_started','in_progress','pending_review','approved','rejected','expired') DEFAULT 'not_started',
  `identity_verified` tinyint(1) DEFAULT 0,
  `address_verified` tinyint(1) DEFAULT 0,
  `business_verified` tinyint(1) DEFAULT 0,
  `training_completed` tinyint(1) DEFAULT 0,
  `approved_by` int(11) DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` int(11) NOT NULL,
  `code` varchar(5) NOT NULL,
  `name` varchar(50) NOT NULL,
  `native_name` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `is_default` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `code`, `name`, `native_name`, `is_active`, `is_default`, `created_at`) VALUES
(1, 'en', 'English', 'English', 1, 1, '2025-11-23 21:05:48'),
(2, 'fr', 'French', 'Français', 1, 0, '2025-11-23 21:05:48'),
(3, 'es', 'Spanish', 'Español', 1, 0, '2025-11-23 21:05:48'),
(4, 'pt', 'Portuguese', 'Português', 1, 0, '2025-11-23 21:05:48'),
(5, 'ar', 'Arabic', 'العربية', 1, 0, '2025-11-23 21:05:48');

-- --------------------------------------------------------

--
-- Table structure for table `license_fees`
--

CREATE TABLE `license_fees` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `license_type` enum('advisor','mca') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_status` enum('pending','paid','failed') DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT NULL,
  `stripe_payment_id` varchar(255) DEFAULT NULL,
  `paid_date` datetime DEFAULT NULL,
  `expiry_date` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `license_payments`
--

CREATE TABLE `license_payments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `license_type` enum('basic','premium') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) DEFAULT 'stripe',
  `transaction_id` varchar(100) DEFAULT NULL,
  `stripe_payment_intent_id` varchar(100) DEFAULT NULL,
  `status` enum('pending','completed','failed','refunded') DEFAULT 'pending',
  `payment_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mca_assignments`
--

CREATE TABLE `mca_assignments` (
  `id` int(11) NOT NULL,
  `mca_id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  `assigned_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mca_assignments`
--

INSERT INTO `mca_assignments` (`id`, `mca_id`, `country_id`, `assigned_at`, `status`) VALUES
(1, 11, 1, '2025-10-22 19:43:24', 'active'),
(2, 11, 2, '2025-10-22 19:43:24', 'active'),
(3, 12, 6, '2025-10-22 19:43:24', 'active'),
(4, 12, 7, '2025-10-22 19:43:24', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `membership_tiers`
--

CREATE TABLE `membership_tiers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `duration_months` int(11) NOT NULL,
  `benefits` text DEFAULT NULL,
  `discount_percentage` decimal(5,2) DEFAULT 0.00,
  `priority_booking` tinyint(1) DEFAULT 0,
  `free_cancellations` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `membership_tiers`
--

INSERT INTO `membership_tiers` (`id`, `name`, `slug`, `price`, `duration_months`, `benefits`, `discount_percentage`, `priority_booking`, `free_cancellations`, `status`, `created_at`) VALUES
(1, 'Bronze', 'bronze', 99.00, 12, '[\"5% discount on all tours\", \"Priority customer support\", \"Monthly newsletter\"]', 5.00, 0, 1, 'active', '2025-11-23 14:18:55'),
(2, 'Silver', 'silver', 199.00, 12, '[\"10% discount on all tours\", \"Priority booking\", \"Free cancellation (2 per year)\", \"Exclusive tour access\"]', 10.00, 1, 2, 'active', '2025-11-23 14:18:55'),
(3, 'Gold', 'gold', 399.00, 12, '[\"15% discount on all tours\", \"Priority booking\", \"Free cancellation (5 per year)\", \"VIP lounge access\", \"Personal travel advisor\"]', 15.00, 1, 5, 'active', '2025-11-23 14:18:55'),
(4, 'Platinum', 'platinum', 799.00, 12, '[\"20% discount on all tours\", \"Priority booking\", \"Unlimited free cancellations\", \"VIP lounge access\", \"Dedicated travel concierge\", \"Complimentary upgrades\"]', 20.00, 1, 999, 'active', '2025-11-23 14:18:55');

-- --------------------------------------------------------

--
-- Table structure for table `mlm_hierarchy`
--

CREATE TABLE `mlm_hierarchy` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `level` int(11) NOT NULL DEFAULT 1,
  `path` varchar(500) DEFAULT NULL,
  `left_node` int(11) DEFAULT NULL,
  `right_node` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mlm_hierarchy`
--

INSERT INTO `mlm_hierarchy` (`id`, `user_id`, `parent_id`, `level`, `path`, `left_node`, `right_node`, `created_at`) VALUES
(1, 1, NULL, 0, '1', NULL, NULL, '2025-10-22 14:29:47'),
(2, 2, 1, 1, '1.2', NULL, NULL, '2025-10-22 14:29:47'),
(3, 3, 1, 1, '1.3', NULL, NULL, '2025-10-22 14:29:47'),
(4, 4, 1, 1, '1.4', NULL, NULL, '2025-10-22 14:29:47'),
(5, 5, 2, 2, '1.2.5', NULL, NULL, '2025-10-22 14:29:47'),
(6, 6, 2, 2, '1.2.6', NULL, NULL, '2025-10-22 14:29:47'),
(7, 7, 3, 2, '1.3.7', NULL, NULL, '2025-10-22 14:29:47'),
(8, 8, 5, 3, '1.2.5.8', NULL, NULL, '2025-10-22 14:29:47'),
(9, 9, 5, 3, '1.2.5.9', NULL, NULL, '2025-10-22 14:29:47'),
(10, 10, 6, 3, '1.2.6.10', NULL, NULL, '2025-10-22 14:29:47');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` enum('booking','commission','system','promotion') DEFAULT 'system',
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_number` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_type` enum('store','tour') NOT NULL DEFAULT 'store',
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `customer_phone` varchar(50) DEFAULT NULL,
  `shipping_address` text DEFAULT NULL,
  `shipping_city` varchar(100) DEFAULT NULL,
  `shipping_state` varchar(100) DEFAULT NULL,
  `shipping_country` varchar(100) DEFAULT NULL,
  `shipping_postal_code` varchar(20) DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `tax` decimal(10,2) DEFAULT 0.00,
  `shipping_fee` decimal(10,2) DEFAULT 0.00,
  `discount` decimal(10,2) DEFAULT 0.00,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_method` enum('credit_card','paypal','stripe','bank_transfer','cash') NOT NULL,
  `payment_status` enum('pending','processing','completed','failed','refunded') DEFAULT 'pending',
  `payment_id` varchar(255) DEFAULT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `order_status` enum('pending','confirmed','processing','shipped','delivered','cancelled') DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `admin_notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `paid_at` timestamp NULL DEFAULT NULL,
  `shipped_at` timestamp NULL DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_sku` varchar(100) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ownership_alerts`
--

CREATE TABLE `ownership_alerts` (
  `id` int(11) NOT NULL,
  `portal_code` varchar(50) NOT NULL,
  `alert_type` enum('bypass_attempt','portal_viewed','booking_made','message_received') NOT NULL,
  `advisor_id` int(11) NOT NULL,
  `alert_message` text DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `payment_reference` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` enum('card','bank_transfer','mobile_money','cash') NOT NULL,
  `payment_gateway` varchar(50) DEFAULT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `status` enum('pending','completed','failed','refunded') DEFAULT 'pending',
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_logs`
--

CREATE TABLE `payment_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `payment_type` enum('license','booking','payout') NOT NULL,
  `reference_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `request_data` text DEFAULT NULL,
  `response_data` text DEFAULT NULL,
  `error_message` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_transactions`
--

CREATE TABLE `payment_transactions` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `payment_gateway` enum('stripe','paypal','bank_transfer','cash') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(3) DEFAULT 'USD',
  `status` enum('pending','completed','failed','refunded') DEFAULT 'pending',
  `gateway_response` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gateway_response`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payout_requests`
--

CREATE TABLE `payout_requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` enum('bank_transfer','paypal','stripe','mobile_money') NOT NULL,
  `payment_details` text DEFAULT NULL,
  `status` enum('pending','approved','processing','completed','rejected') DEFAULT 'pending',
  `requested_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `processed_date` datetime DEFAULT NULL,
  `processed_by` int(11) DEFAULT NULL,
  `admin_notes` text DEFAULT NULL,
  `transaction_reference` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `portal_activity`
--

CREATE TABLE `portal_activity` (
  `id` int(11) NOT NULL,
  `portal_code` varchar(50) NOT NULL,
  `activity_type` varchar(50) NOT NULL,
  `activity_data` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `portal_activity`
--

INSERT INTO `portal_activity` (`id`, `portal_code`, `activity_type`, `activity_data`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 'JA-2025-033', 'view', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-23 23:50:42'),
(2, 'JA-2025-033', 'view', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-24 12:36:57'),
(3, 'JA-2025-033', 'view', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-24 12:36:59'),
(4, 'JA-2025-033', 'view', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-25 09:59:46'),
(5, 'JA-2025-033', 'view', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-25 09:59:46');

-- --------------------------------------------------------

--
-- Table structure for table `portal_messages`
--

CREATE TABLE `portal_messages` (
  `id` int(11) NOT NULL,
  `portal_code` varchar(50) NOT NULL,
  `sender_type` enum('client','advisor','admin') NOT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `sender_name` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `portal_tours`
--

CREATE TABLE `portal_tours` (
  `id` int(11) NOT NULL,
  `portal_code` varchar(50) NOT NULL,
  `tour_id` int(11) NOT NULL,
  `display_order` int(11) DEFAULT 0,
  `custom_price` decimal(10,2) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `portal_tours`
--

INSERT INTO `portal_tours` (`id`, `portal_code`, `tour_id`, `display_order`, `custom_price`, `notes`, `added_at`) VALUES
(2, 'JA-2025-033', 30, 1, NULL, NULL, '2025-11-23 23:49:41');

-- --------------------------------------------------------

--
-- Table structure for table `regions`
--

CREATE TABLE `regions` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `hero_image` varchar(500) DEFAULT NULL,
  `about_text` longtext DEFAULT NULL,
  `tourism_highlights` text DEFAULT NULL,
  `popular_activities` text DEFAULT NULL,
  `best_time_to_visit` varchar(255) DEFAULT NULL,
  `visa_requirements` text DEFAULT NULL,
  `currency` varchar(50) DEFAULT NULL,
  `languages_spoken` text DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `featured` tinyint(1) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `regions`
--

INSERT INTO `regions` (`id`, `name`, `slug`, `description`, `hero_image`, `about_text`, `tourism_highlights`, `popular_activities`, `best_time_to_visit`, `visa_requirements`, `currency`, `languages_spoken`, `meta_title`, `meta_description`, `image_url`, `featured`, `status`, `created_at`, `updated_at`) VALUES
(1, 'North Africa', 'north-africa', 'North Africa offers unparalleled adventure — from the ancient pyramids of Egypt to the bustling souks of Morocco — where every journey is crafted for luxury and authenticity.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'https://images.unsplash.com/photo-1539650116574-75c0c6d73f6e?auto=format&fit=crop&w=1200&q=80', 1, 'inactive', '2025-10-22 18:58:55', '2025-11-02 10:17:43'),
(2, 'West Africa', 'west-africa', 'West Africa captivates with vibrant cultures, rich history, and warm hospitality — from the golden beaches of Ghana to the musical heritage of Senegal — creating unforgettable memories.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?auto=format&fit=crop&w=1200&q=80', 1, 'inactive', '2025-10-22 18:58:55', '2025-11-02 10:17:43'),
(3, 'East Africa', 'east-africa', 'East Africa delivers the ultimate safari experience — from the Great Migration in Kenya to mountain gorillas in Rwanda — where wildlife encounters and pristine landscapes create once-in-a-lifetime adventures.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=1200&q=80', 1, 'inactive', '2025-10-22 18:58:55', '2025-11-02 10:17:43'),
(4, 'Central Africa', 'central-africa', 'Central Africa unveils untouched wilderness and rare wildlife — from the Congo Basin rainforests to unique primate encounters — offering authentic adventures for the intrepid traveler.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'https://images.unsplash.com/photo-1547036967-23d11aacaee0?auto=format&fit=crop&w=1200&q=80', 0, 'inactive', '2025-10-22 18:58:55', '2025-11-02 10:17:43'),
(5, 'Southern Africa', 'southern-africa', 'Southern Africa combines dramatic landscapes with world-class experiences — from Victoria Falls to Cape Town winelands — where luxury meets adventure in the most developed tourism region.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'https://images.unsplash.com/photo-1484318571209-661cf29a69ea?auto=format&fit=crop&w=1200&q=80', 1, 'inactive', '2025-10-22 18:58:55', '2025-11-02 10:17:43'),
(6, 'Africa', 'africa', 'Africa offers unparalleled adventure from the savannas of Kenya to the beaches of Zanzibar where every journey is crafted for luxury and authenticity.', 'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=2000&q=80', 'Africa, the world\'s second-largest continent, is a land of extraordinary diversity, breathtaking landscapes, and rich cultural heritage. From the vast Sahara Desert to the lush rainforests of the Congo, from the snow-capped peaks of Kilimanjaro to the pristine beaches of Zanzibar, Africa offers unparalleled adventure and discovery.', '[\"Wildlife Safaris\", \"Ancient Pyramids\", \"Victoria Falls\", \"Serengeti Migration\", \"Table Mountain\", \"Sahara Desert\", \"Nile River Cruises\", \"Maasai Culture\"]', '[\"Safari Game Drives\", \"Mountain Climbing\", \"Beach Relaxation\", \"Cultural Tours\", \"Desert Adventures\", \"River Rafting\", \"Bird Watching\", \"Photography Tours\"]', 'June to October (Dry Season)', 'Varies by country. Many African countries offer visa on arrival or e-visa options.', 'Various (USD widely accepted)', '[\"English\", \"French\", \"Arabic\", \"Swahili\", \"Portuguese\"]', 'Explore Africa - Luxury Tours & Safari Adventures', 'Discover the magic of Africa with Forever Young Tours. Experience world-class safaris, cultural immersion, and breathtaking landscapes across the African continent.', 'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=1200&q=80', 1, 'active', '2025-11-02 09:53:59', '2025-11-07 17:03:07'),
(7, 'Europe', 'europe', 'Europe enchants with timeless elegance — from the romantic streets of Paris to the ancient ruins of Rome — where history and culture create unforgettable experiences.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'https://images.unsplash.com/photo-1467269204594-9661b134dd2b?auto=format&fit=crop&w=1200&q=80', 1, 'inactive', '2025-11-02 09:53:59', '2025-11-07 13:10:50'),
(8, 'Asia', 'asia', 'Asia mesmerizes with diverse wonders — from the temples of Thailand to the Great Wall of China — where ancient traditions meet modern luxury.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'https://images.unsplash.com/photo-1480714378408-67cf0d13bc1b?auto=format&fit=crop&w=1200&q=80', 1, 'inactive', '2025-11-02 09:53:59', '2025-11-07 13:10:41'),
(9, 'North America', 'north-america', 'North America delivers epic adventures — from the Grand Canyon to Niagara Falls — where natural wonders and vibrant cities create memorable journeys.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'https://images.unsplash.com/photo-1501594907352-04cda38ebc29?auto=format&fit=crop&w=1200&q=80', 1, 'active', '2025-11-02 09:53:59', '2025-11-02 09:53:59'),
(10, 'South America', 'south-america', 'South America captivates with breathtaking landscapes — from Machu Picchu to the Amazon rainforest — where adventure and culture blend perfectly.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'https://images.unsplash.com/photo-1483729558449-99ef09a8c325?auto=format&fit=crop&w=1200&q=80', 1, 'active', '2025-11-02 09:53:59', '2025-11-02 09:53:59'),
(11, 'Australia & Oceania', 'oceania', 'Oceania amazes with pristine beauty — from the Great Barrier Reef to New Zealand fjords — where nature and adventure create paradise experiences.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'https://images.unsplash.com/photo-1523482580672-f109ba8cb9be?auto=format&fit=crop&w=1200&q=80', 1, 'inactive', '2025-11-02 09:53:59', '2025-11-02 10:04:28'),
(23, 'Caribbean', 'caribbean', 'Caribbean paradise awaits — from pristine beaches to vibrant cultures — where crystal-clear waters and tropical luxury create unforgettable island escapes.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'https://images.unsplash.com/photo-1559827260-dc66d52bef19?auto=format&fit=crop&w=1200&q=80', 1, 'inactive', '2025-11-02 10:04:29', '2025-11-07 13:10:47');

-- --------------------------------------------------------

--
-- Stand-in structure for view `region_country_tours_view`
-- (See below for the actual view)
--
CREATE TABLE `region_country_tours_view` (
`region_id` int(11)
,`region_name` varchar(100)
,`region_slug` varchar(100)
,`country_id` int(11)
,`country_name` varchar(100)
,`country_slug` varchar(100)
,`country_code` varchar(3)
,`tour_count` bigint(21)
,`min_price` decimal(10,2)
,`max_price` decimal(10,2)
);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `tour_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `title` varchar(255) DEFAULT NULL,
  `review_text` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shared_links`
--

CREATE TABLE `shared_links` (
  `id` int(11) NOT NULL,
  `tour_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `link_code` varchar(50) NOT NULL,
  `full_url` varchar(500) NOT NULL,
  `clicks` int(11) DEFAULT 0,
  `conversions` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_clicked_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shopping_cart`
--

CREATE TABLE `shopping_cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shopping_cart`
--

INSERT INTO `shopping_cart` (`id`, `user_id`, `product_id`, `quantity`, `created_at`, `updated_at`) VALUES
(1, 19, 3, 2, '2025-11-07 16:26:24', '2025-11-07 16:26:28'),
(2, 19, 2, 1, '2025-11-07 16:26:31', '2025-11-07 16:26:31'),
(3, 22, 3, 2, '2025-11-07 16:44:14', '2025-11-25 17:17:52'),
(4, 22, 1, 1, '2025-11-07 16:44:18', '2025-11-07 16:44:18');

-- --------------------------------------------------------

--
-- Table structure for table `store_categories`
--

CREATE TABLE `store_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `color` varchar(7) DEFAULT '#3B82F6',
  `status` enum('active','inactive') DEFAULT 'active',
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `store_categories`
--

INSERT INTO `store_categories` (`id`, `name`, `slug`, `description`, `icon`, `color`, `status`, `display_order`, `created_at`, `updated_at`) VALUES
(1, 'Camping', 'camping', 'Camping gear and equipment', 'tent', '#10B981', 'active', 1, '2025-11-07 14:51:04', '2025-11-07 14:51:04'),
(2, 'Hiking', 'hiking', 'Hiking boots, poles, and accessories', 'mountain', '#F59E0B', 'active', 2, '2025-11-07 14:51:04', '2025-11-07 14:51:04'),
(3, 'Accessories', 'accessories', 'Travel accessories and essentials', 'bag', '#3B82F6', 'active', 3, '2025-11-07 14:51:04', '2025-11-07 14:51:04'),
(4, 'Safety', 'safety', 'Safety equipment and first aid', 'shield', '#EF4444', 'active', 4, '2025-11-07 14:51:04', '2025-11-07 14:51:04'),
(5, 'Clothing', 'clothing', 'Outdoor and travel clothing', 'shirt', '#8B5CF6', 'active', 5, '2025-11-07 14:51:04', '2025-11-07 14:51:04'),
(6, 'Electronics', 'electronics', 'Travel electronics and gadgets', 'phone', '#06B6D4', 'active', 6, '2025-11-07 14:51:04', '2025-11-07 14:51:04');

-- --------------------------------------------------------

--
-- Table structure for table `store_orders`
--

CREATE TABLE `store_orders` (
  `id` int(11) NOT NULL,
  `order_number` varchar(50) NOT NULL,
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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `store_order_items`
--

CREATE TABLE `store_order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_sku` varchar(100) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `store_products`
--

CREATE TABLE `store_products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `short_description` varchar(500) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `original_price` decimal(10,2) DEFAULT NULL,
  `discount_percentage` int(11) DEFAULT 0,
  `image_url` varchar(500) DEFAULT NULL,
  `gallery_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gallery_images`)),
  `stock_quantity` int(11) DEFAULT 0,
  `stock_status` enum('in_stock','out_of_stock','low_stock') DEFAULT 'in_stock',
  `sku` varchar(100) DEFAULT NULL,
  `rating` decimal(3,2) DEFAULT 0.00,
  `review_count` int(11) DEFAULT 0,
  `is_featured` tinyint(1) DEFAULT 0,
  `is_on_sale` tinyint(1) DEFAULT 0,
  `tags` varchar(500) DEFAULT NULL,
  `specifications` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`specifications`)),
  `status` enum('active','inactive','draft') DEFAULT 'active',
  `views` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `store_products`
--

INSERT INTO `store_products` (`id`, `category_id`, `name`, `slug`, `description`, `short_description`, `price`, `original_price`, `discount_percentage`, `image_url`, `gallery_images`, `stock_quantity`, `stock_status`, `sku`, `rating`, `review_count`, `is_featured`, `is_on_sale`, `tags`, `specifications`, `status`, `views`, `created_at`, `updated_at`) VALUES
(1, 1, 'Premium Camping Tent', 'premium-camping-tent', 'Waterproof 4-person tent with UV protection and easy setup system. Perfect for family camping trips.', 'Waterproof 4-person tent with UV protection', 89.00, 112.00, 20, 'https://images.unsplash.com/photo-1504280390367-361c6d9f38f4?w=400&h=400&fit=crop', NULL, 50, 'in_stock', 'TENT-001', 4.80, 124, 1, 1, 'camping,tent,waterproof,family', NULL, 'active', 0, '2025-11-07 14:51:06', '2025-11-07 14:51:06'),
(2, 2, 'Professional Hiking Boots', 'professional-hiking-boots', 'Durable waterproof hiking boots with ankle support and superior grip for all terrains.', 'Durable waterproof hiking boots', 129.00, 160.00, 19, '../assets/images/shoes.jpg', NULL, 35, 'in_stock', 'BOOT-001', 4.70, 89, 1, 1, 'hiking,boots,waterproof', NULL, 'active', 0, '2025-11-07 14:51:06', '2025-11-07 14:51:06'),
(3, 3, 'Insulated Travel Bottle', 'insulated-travel-bottle', 'Stainless steel insulated water bottle keeps drinks cold for 24hrs and hot for 12hrs.', 'Insulated water bottle - 24hr cold', 25.00, 35.00, 29, 'https://images.unsplash.com/photo-1602143407151-7111542de6e8?w=400&h=400&fit=crop', NULL, 100, 'in_stock', 'BTL-001', 4.90, 256, 1, 1, 'bottle,water,insulated,travel', NULL, 'active', 0, '2025-11-07 14:51:06', '2025-11-07 14:51:06'),
(4, 2, 'Travel Backpack 45L', 'travel-backpack-45l', 'Spacious 45L travel backpack with multiple compartments and ergonomic design.', 'Durable 45L travel backpack', 79.00, 99.00, 20, 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=400&h=400&fit=crop', NULL, 45, 'in_stock', 'BAG-001', 4.60, 178, 1, 0, 'backpack,travel,hiking', NULL, 'active', 0, '2025-11-07 14:51:06', '2025-11-07 14:51:06'),
(5, 3, 'Memory Foam Travel Pillow', 'memory-foam-travel-pillow', 'Ergonomic memory foam neck pillow with adjustable strap for maximum comfort.', 'Comfortable neck support pillow', 29.00, 39.00, 26, '../assets/images/travel pillow.jpg', NULL, 80, 'in_stock', 'PIL-001', 4.50, 145, 0, 1, 'pillow,travel,comfort', NULL, 'active', 0, '2025-11-07 14:51:06', '2025-11-07 14:51:06'),
(6, 4, 'Complete First Aid Kit', 'complete-first-aid-kit', 'Comprehensive 150-piece first aid kit for travel and outdoor adventures.', 'Essential medical supplies for travel', 45.00, 60.00, 25, '../assets/images/first aid kit.jpg', NULL, 60, 'in_stock', 'AID-001', 4.80, 203, 1, 1, 'first-aid,safety,medical', NULL, 'active', 0, '2025-11-07 14:51:06', '2025-11-07 14:51:06'),
(7, 1, 'Portable Camping Stove', 'portable-camping-stove', 'Compact gas camping stove with windscreen and carrying case.', 'Compact portable camping stove', 55.00, 70.00, 21, 'https://images.unsplash.com/photo-1478131143081-80f7f84ca84d?w=400&h=400&fit=crop', NULL, 40, 'in_stock', 'STOVE-001', 4.70, 92, 0, 1, 'camping,stove,cooking', NULL, 'active', 0, '2025-11-07 14:51:06', '2025-11-07 14:51:06'),
(8, 2, 'Trekking Poles Set', 'trekking-poles-set', 'Adjustable aluminum trekking poles with anti-shock system and ergonomic grips.', 'Adjustable aluminum trekking poles', 39.00, 55.00, 29, 'https://images.unsplash.com/photo-1551698618-1dfe5d97d256?w=400&h=400&fit=crop', NULL, 55, 'in_stock', 'POLE-001', 4.60, 134, 0, 1, 'hiking,poles,trekking', NULL, 'active', 0, '2025-11-07 14:51:06', '2025-11-07 14:51:06');

-- --------------------------------------------------------

--
-- Table structure for table `store_reviews`
--

CREATE TABLE `store_reviews` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `title` varchar(255) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `helpful_count` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `store_settings`
--

CREATE TABLE `store_settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `setting_type` varchar(50) DEFAULT 'text',
  `description` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `store_settings`
--

INSERT INTO `store_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `description`, `updated_at`) VALUES
(1, 'store_enabled', '1', 'boolean', 'Enable/disable the store', '2025-11-07 14:51:11'),
(2, 'free_shipping_threshold', '100', 'number', 'Minimum order value for free shipping', '2025-11-07 14:51:11'),
(3, 'tax_rate', '0', 'number', 'Tax rate percentage', '2025-11-07 14:51:11'),
(4, 'currency', 'USD', 'text', 'Store currency', '2025-11-07 14:51:11'),
(5, 'currency_symbol', '$', 'text', 'Currency symbol', '2025-11-07 14:51:11'),
(6, 'low_stock_threshold', '10', 'number', 'Low stock warning threshold', '2025-11-07 14:51:11'),
(7, 'store_email', 'store@foreveryoungtours.com', 'email', 'Store contact email', '2025-11-07 14:51:11');

-- --------------------------------------------------------

--
-- Table structure for table `store_wishlist`
--

CREATE TABLE `store_wishlist` (
  `id` int(11) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `product_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `setting_type` enum('string','number','boolean','json') DEFAULT 'string',
  `description` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `description`, `updated_at`) VALUES
(1, 'site_name', 'iForYoungTours', 'string', 'Website name', '2025-10-22 14:29:47'),
(2, 'commission_rate_level1', '10', 'number', 'Level 1 commission percentage', '2025-10-22 14:29:47'),
(3, 'commission_rate_level2', '5', 'number', 'Level 2 commission percentage', '2025-10-22 14:29:47'),
(4, 'commission_rate_level3', '3', 'number', 'Level 3 commission percentage', '2025-10-22 14:29:47'),
(5, 'default_currency', 'USD', 'string', 'Default currency code', '2025-10-22 14:29:47'),
(6, 'booking_confirmation_required', 'true', 'boolean', 'Require admin confirmation for bookings', '2025-10-22 14:29:47'),
(7, 'max_booking_days_advance', '365', 'number', 'Maximum days in advance for bookings', '2025-10-22 14:29:47');

-- --------------------------------------------------------

--
-- Stand-in structure for view `team_hierarchy_view`
-- (See below for the actual view)
--
CREATE TABLE `team_hierarchy_view` (
`advisor_id` int(11)
,`advisor_name` varchar(100)
,`level` int(11)
,`upline_id` int(11)
,`upline_name` varchar(100)
,`mca_id` int(11)
,`mca_name` varchar(100)
,`team_count` bigint(21)
);

-- --------------------------------------------------------

--
-- Table structure for table `tours`
--

CREATE TABLE `tours` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `long_description` longtext DEFAULT NULL,
  `destination` varchar(255) NOT NULL,
  `destination_country` varchar(100) NOT NULL,
  `country_id` int(11) DEFAULT NULL,
  `region_id` int(11) DEFAULT NULL,
  `category` enum('cultural','adventure','wildlife','city','sports','agro','conference') DEFAULT 'cultural',
  `price` decimal(10,2) NOT NULL,
  `base_price` decimal(10,2) NOT NULL,
  `duration` varchar(50) NOT NULL,
  `duration_days` int(11) NOT NULL,
  `max_participants` int(11) DEFAULT 20,
  `min_age` int(11) DEFAULT 0,
  `min_participants` int(11) DEFAULT 2,
  `image_url` varchar(500) DEFAULT NULL,
  `gallery_images` text DEFAULT NULL,
  `images` text DEFAULT NULL,
  `cover_image` varchar(500) DEFAULT NULL,
  `gallery` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gallery`)),
  `itinerary` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`itinerary`)),
  `included_services` text DEFAULT NULL,
  `excluded_services` text DEFAULT NULL,
  `meeting_point` varchar(255) DEFAULT NULL,
  `departure_time` varchar(100) DEFAULT NULL,
  `return_time` varchar(100) DEFAULT NULL,
  `inclusions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`inclusions`)),
  `exclusions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`exclusions`)),
  `requirements` text DEFAULT NULL,
  `available_dates` text DEFAULT NULL,
  `status` enum('active','inactive','draft') DEFAULT 'active',
  `featured` tinyint(1) DEFAULT 0,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `detailed_description` text DEFAULT NULL COMMENT 'Comprehensive tour description',
  `highlights` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Key tour highlights' CHECK (json_valid(`highlights`)),
  `difficulty_level` enum('easy','moderate','challenging','extreme') DEFAULT 'moderate',
  `best_time_to_visit` varchar(100) DEFAULT NULL COMMENT 'Best months to take this tour',
  `what_to_bring` text DEFAULT NULL COMMENT 'What participants should bring',
  `cancellation_policy` text DEFAULT NULL COMMENT 'Tour cancellation policy',
  `tour_type` enum('group','private','custom') DEFAULT 'group',
  `languages` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Available guide languages' CHECK (json_valid(`languages`)),
  `age_restriction` varchar(50) DEFAULT NULL COMMENT 'Age requirements',
  `physical_requirements` text DEFAULT NULL COMMENT 'Physical fitness requirements',
  `accommodation_type` varchar(100) DEFAULT NULL COMMENT 'Type of accommodation included',
  `meal_plan` varchar(100) DEFAULT NULL COMMENT 'Meal plan details',
  `transportation_details` text DEFAULT NULL COMMENT 'Transportation information',
  `booking_deadline` int(11) DEFAULT 7 COMMENT 'Days before tour to book',
  `refund_policy` text DEFAULT NULL COMMENT 'Refund policy details',
  `emergency_contact` varchar(100) DEFAULT NULL COMMENT 'Emergency contact information',
  `tour_guide_info` text DEFAULT NULL COMMENT 'Information about tour guides',
  `special_offers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Special offers and discounts' CHECK (json_valid(`special_offers`)),
  `seasonal_pricing` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Seasonal price variations' CHECK (json_valid(`seasonal_pricing`)),
  `group_discounts` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Group size discounts' CHECK (json_valid(`group_discounts`)),
  `addon_services` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Additional services available' CHECK (json_valid(`addon_services`)),
  `safety_measures` text DEFAULT NULL COMMENT 'Safety protocols and measures',
  `insurance_info` text DEFAULT NULL COMMENT 'Insurance requirements and options',
  `cultural_notes` text DEFAULT NULL COMMENT 'Cultural etiquette and tips',
  `weather_info` text DEFAULT NULL COMMENT 'Weather conditions and clothing advice',
  `local_customs` text DEFAULT NULL COMMENT 'Local customs and traditions',
  `photography_policy` text DEFAULT NULL COMMENT 'Photography guidelines',
  `sustainability_info` text DEFAULT NULL COMMENT 'Eco-friendly and sustainability practices',
  `accessibility_info` text DEFAULT NULL COMMENT 'Accessibility accommodations',
  `tour_tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Tags for filtering and search' CHECK (json_valid(`tour_tags`)),
  `meta_title` varchar(200) DEFAULT NULL COMMENT 'SEO meta title',
  `meta_description` text DEFAULT NULL COMMENT 'SEO meta description',
  `share_url` varchar(500) DEFAULT NULL COMMENT 'Shareable tour URL for advisors',
  `advisor_commission_rate` decimal(5,2) DEFAULT 10.00 COMMENT 'Commission rate for advisors',
  `mca_commission_rate` decimal(5,2) DEFAULT 5.00 COMMENT 'Commission rate for MCAs',
  `booking_count` int(11) DEFAULT 0 COMMENT 'Total number of bookings',
  `average_rating` decimal(3,2) DEFAULT 0.00 COMMENT 'Average customer rating',
  `review_count` int(11) DEFAULT 0 COMMENT 'Number of reviews',
  `last_booked_date` timestamp NULL DEFAULT NULL COMMENT 'Last booking date',
  `popularity_score` int(11) DEFAULT 0 COMMENT 'Calculated popularity score',
  `video_url` varchar(500) DEFAULT NULL COMMENT 'Tour promotional video URL',
  `virtual_tour_url` varchar(500) DEFAULT NULL COMMENT '360° virtual tour URL',
  `brochure_url` varchar(500) DEFAULT NULL COMMENT 'Downloadable brochure URL',
  `terms_conditions` text DEFAULT NULL COMMENT 'Tour specific terms and conditions'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tours`
--

INSERT INTO `tours` (`id`, `name`, `slug`, `description`, `long_description`, `destination`, `destination_country`, `country_id`, `region_id`, `category`, `price`, `base_price`, `duration`, `duration_days`, `max_participants`, `min_age`, `min_participants`, `image_url`, `gallery_images`, `images`, `cover_image`, `gallery`, `itinerary`, `included_services`, `excluded_services`, `meeting_point`, `departure_time`, `return_time`, `inclusions`, `exclusions`, `requirements`, `available_dates`, `status`, `featured`, `created_by`, `created_at`, `updated_at`, `detailed_description`, `highlights`, `difficulty_level`, `best_time_to_visit`, `what_to_bring`, `cancellation_policy`, `tour_type`, `languages`, `age_restriction`, `physical_requirements`, `accommodation_type`, `meal_plan`, `transportation_details`, `booking_deadline`, `refund_policy`, `emergency_contact`, `tour_guide_info`, `special_offers`, `seasonal_pricing`, `group_discounts`, `addon_services`, `safety_measures`, `insurance_info`, `cultural_notes`, `weather_info`, `local_customs`, `photography_policy`, `sustainability_info`, `accessibility_info`, `tour_tags`, `meta_title`, `meta_description`, `share_url`, `advisor_commission_rate`, `mca_commission_rate`, `booking_count`, `average_rating`, `review_count`, `last_booked_date`, `popularity_score`, `video_url`, `virtual_tour_url`, `brochure_url`, `terms_conditions`) VALUES
(37, 'City Tour', 'city-tour', 'A full-day immersion into Rwanda’s powerful history, vibrant culture, and artistic renaissance. This curated experience takes travelers from the Kigali Genocide Memorial to the Kandt House Museum, f...', NULL, 'Kigali', 'Rwanda', 10, NULL, 'cultural', 300.00, 300.00, '1 days', 1, 1, 0, 50, 'uploads/tours/37_main_1764060119_1082.png', NULL, '[\"uploads\\/tours\\/37_main_1764060119_1082.png\",\"uploads\\/tours\\/37_cover_1764060119_9657.png\"]', 'uploads/tours/37_cover_1764060119_9657.png', '[\"uploads\\/tours\\/37_gallery_0_1764060119_4948.jpeg\"]', '[{\"day\":\"1\",\"title\":\"Genocide Memory\",\"activities\":\"urney through the renowned Inema Arts Center. Guests enjoy a premium lunch, explore the colorful Kimironko Market, and capture panoramic views from Mount Kigali. The day concludes with sunset cocktails on a rooftop overlooking the city and an elegant cultural dinner show featuring traditional Rwanda\"},{\"day\":\"2\",\"title\":\"Kimironko Market\",\"activities\":\"urney through the renowned Inema Arts Center. Guests enjoy a premium lunch, explore the colorful Kimironko Market, and capture panoramic views from Mount Kigali. The day concludes with sunset cocktails on a rooftop overlooking the city and an elegant cultural dinner show featuring traditional Rwanda\"}]', NULL, NULL, NULL, NULL, NULL, '[\"Professional FYT Tour Guide (English\\/French)\\r\",\"\\u2022 Air-conditioned transport for the full day\\r\",\"\\u2022 All entry fees:\\r\",\"\\u2022 Kigali Genocide Memorial\\r\",\"\\u2022 Kandt House Museum\\r\",\"\\u2022 Inema Arts Center\\r\",\"\\u2022 Premium lunch (main meal + 1 drink)\\r\",\"\\u2022 Bottled water (minimum 2 per guest)\\r\",\"\\u2022 Visit to Kimironko Market with assistance\\r\",\"\\u2022 Mount Kigali Viewpoint access\\r\",\"\\u2022 Sundowner cocktail or mocktail at a rooftop venue\\r\",\"\\u2022 Dinner + Cultural Show (3-course meal + live performance)\\r\",\"\\u2022 Hotel pick-up and drop-off\\r\",\"\\u2022 Photography assistance throughout the day\\r\",\"\\u2022 FYT onsite support and tour coordination\"]', '[\"Alcoholic beverages not included in the group package\\r\",\"\\u2022 Additional food orders beyond the set lunch\\/dinner menus\\r\",\"\\u2022 Personal shopping at Kimironko Market\\r\",\"\\u2022 Premium or private artist interactions (optional upgrade)\\r\",\"\\u2022 Tips and gratuities\\r\",\"\\u2022 Travel insurance\\r\",\"\\u2022 Any services not specifically listed under Inclusions\"]', 'None', NULL, 'active', 0, 1, '2025-11-25 08:41:59', '2025-11-25 08:43:07', 'A full-day immersion into Rwanda’s powerful history, vibrant culture, and artistic renaissance. This curated experience takes travelers from the Kigali Genocide Memorial to the Kandt House Museum, followed by a creative journey through the renowned Inema Arts Center. Guests enjoy a premium lunch, explore the colorful Kimironko Market, and capture panoramic views from Mount Kigali. The day concludes with sunset cocktails on a rooftop overlooking the city and an elegant cultural dinner show featuring traditional Rwandan dance and music. This is Kigali’s most complete heritage experience—refined, meaningful, and unforgettable.', '[\"Visit the Kigali Genocide Memorial for a powerful historical experience\\r\",\"\\u2022 Explore Rwanda\\u2019s natural and colonial history at the Kandt House Museum\\r\",\"\\u2022 Discover contemporary Rwandan creativity at Inema Arts Center\\r\",\"\\u2022 Enjoy a curated lunch at a premium Kigali restaurant\\r\",\"\\u2022 Shop and interact with locals at the vibrant Kimironko Market\\r\",\"\\u2022 Capture panoramic city views from Mount Kigali Viewpoint\\r\",\"\\u2022 Relax with rooftop sundowner cocktails overlooking the Kigali skyline\\r\",\"\\u2022 Experience a traditional Rwandan cultural dinner show with live performances\"]', 'easy', 'summer time', NULL, NULL, 'group', NULL, NULL, NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 10.00, 5.00, 0, 0.00, 0, NULL, 0, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tour_availability`
--

CREATE TABLE `tour_availability` (
  `id` int(11) NOT NULL,
  `tour_id` int(11) NOT NULL,
  `available_date` date NOT NULL,
  `available_spots` int(11) NOT NULL,
  `price_override` decimal(10,2) DEFAULT NULL,
  `status` enum('available','limited','full','unavailable') DEFAULT 'available',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tour_bookings`
--

CREATE TABLE `tour_bookings` (
  `id` int(11) NOT NULL,
  `booking_number` varchar(50) NOT NULL,
  `tour_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `booking_type` enum('booking','inquiry') DEFAULT 'booking',
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `customer_phone` varchar(50) NOT NULL,
  `customer_country` varchar(100) DEFAULT NULL,
  `tour_date` date NOT NULL,
  `number_of_travelers` int(11) NOT NULL DEFAULT 1,
  `adults` int(11) DEFAULT 1,
  `children` int(11) DEFAULT 0,
  `infants` int(11) DEFAULT 0,
  `travelers_info` text DEFAULT NULL,
  `price_per_person` decimal(10,2) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `discount_amount` decimal(10,2) DEFAULT 0.00,
  `paid_amount` decimal(10,2) DEFAULT 0.00,
  `special_requests` text DEFAULT NULL,
  `dietary_requirements` text DEFAULT NULL,
  `accommodation_preference` varchar(255) DEFAULT NULL,
  `payment_status` enum('pending','partial','paid','refunded','failed') DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_reference` varchar(255) DEFAULT NULL,
  `booking_status` enum('pending','confirmed','cancelled','completed','no_show') DEFAULT 'pending',
  `inquiry_message` text DEFAULT NULL,
  `admin_notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `confirmed_at` timestamp NULL DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tour_faqs`
--

CREATE TABLE `tour_faqs` (
  `id` int(11) NOT NULL,
  `tour_id` int(11) NOT NULL,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tour_images`
--

CREATE TABLE `tour_images` (
  `id` int(11) NOT NULL,
  `tour_id` int(11) NOT NULL,
  `image_url` varchar(500) NOT NULL,
  `image_title` varchar(200) DEFAULT NULL,
  `image_description` text DEFAULT NULL,
  `image_type` enum('main','gallery','cover','thumbnail') DEFAULT 'gallery',
  `sort_order` int(11) DEFAULT 0,
  `alt_text` varchar(200) DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tour_order_details`
--

CREATE TABLE `tour_order_details` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `tour_id` int(11) DEFAULT NULL,
  `tour_name` varchar(255) NOT NULL,
  `booking_date` date NOT NULL,
  `number_of_travelers` int(11) NOT NULL,
  `traveler_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`traveler_details`)),
  `special_requests` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tour_reviews`
--

CREATE TABLE `tour_reviews` (
  `id` int(11) NOT NULL,
  `tour_id` int(11) NOT NULL,
  `booking_id` int(11) DEFAULT NULL,
  `customer_name` varchar(100) NOT NULL,
  `customer_email` varchar(100) DEFAULT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `review_title` varchar(200) DEFAULT NULL,
  `review_text` text DEFAULT NULL,
  `travel_date` date DEFAULT NULL,
  `verified_booking` tinyint(1) DEFAULT 0,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `helpful_votes` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `tour_reviews`
--
DELIMITER $$
CREATE TRIGGER `update_tour_rating_after_review` AFTER INSERT ON `tour_reviews` FOR EACH ROW BEGIN
    UPDATE tours 
    SET average_rating = (
        SELECT AVG(rating) 
        FROM tour_reviews 
        WHERE tour_id = NEW.tour_id AND status = 'approved'
    ),
    review_count = (
        SELECT COUNT(*) 
        FROM tour_reviews 
        WHERE tour_id = NEW.tour_id AND status = 'approved'
    )
    WHERE id = NEW.tour_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tour_schedules`
--

CREATE TABLE `tour_schedules` (
  `id` int(11) NOT NULL,
  `tour_id` int(11) NOT NULL,
  `scheduled_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `available_slots` int(11) NOT NULL DEFAULT 20,
  `booked_slots` int(11) NOT NULL DEFAULT 0,
  `price` decimal(10,2) NOT NULL,
  `status` enum('active','full','cancelled','completed') DEFAULT 'active',
  `notes` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tour_schedule_bookings`
--

CREATE TABLE `tour_schedule_bookings` (
  `id` int(11) NOT NULL,
  `schedule_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `booking_reference` varchar(50) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `customer_phone` varchar(50) DEFAULT NULL,
  `number_of_people` int(11) NOT NULL DEFAULT 1,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','confirmed','paid','cancelled') DEFAULT 'pending',
  `payment_status` enum('unpaid','paid','refunded') DEFAULT 'unpaid',
  `special_requests` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tour_wishlist`
--

CREATE TABLE `tour_wishlist` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tour_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `training_assignments`
--

CREATE TABLE `training_assignments` (
  `id` int(11) NOT NULL,
  `assigned_by` int(11) NOT NULL,
  `assigned_to` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  `due_date` date DEFAULT NULL,
  `priority` enum('low','medium','high') DEFAULT 'medium',
  `notes` text DEFAULT NULL,
  `status` enum('assigned','in_progress','completed','overdue') DEFAULT 'assigned',
  `assigned_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `completed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `training_certificates`
--

CREATE TABLE `training_certificates` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  `certificate_number` varchar(50) DEFAULT NULL,
  `issued_date` date NOT NULL,
  `expiry_date` date DEFAULT NULL,
  `certificate_url` varchar(500) DEFAULT NULL,
  `status` enum('active','expired','revoked') DEFAULT 'active',
  `issued_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `training_modules`
--

CREATE TABLE `training_modules` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `video_url` varchar(500) DEFAULT NULL,
  `duration_minutes` int(11) DEFAULT 0,
  `difficulty_level` enum('beginner','intermediate','advanced') DEFAULT 'beginner',
  `category` enum('product_knowledge','sales_techniques','customer_service','compliance','marketing') DEFAULT 'product_knowledge',
  `is_mandatory` tinyint(1) DEFAULT 0,
  `order_sequence` int(11) DEFAULT 0,
  `status` enum('active','inactive','draft') DEFAULT 'active',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `training_modules`
--

INSERT INTO `training_modules` (`id`, `title`, `description`, `content`, `video_url`, `duration_minutes`, `difficulty_level`, `category`, `is_mandatory`, `order_sequence`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'African Tourism Fundamentals', 'Introduction to African tourism industry, key destinations, and cultural awareness', 'Comprehensive overview of African tourism landscape, major destinations, cultural considerations, and industry trends.', NULL, 45, 'beginner', 'product_knowledge', 1, 1, 'active', NULL, '2025-10-23 00:36:28', '2025-10-23 00:36:28'),
(2, 'Sales Techniques for Tourism', 'Effective sales strategies for tourism products and services', 'Learn proven sales techniques specific to tourism industry, handling objections, and closing deals.', NULL, 60, 'intermediate', 'sales_techniques', 1, 2, 'active', NULL, '2025-10-23 00:36:28', '2025-10-23 00:36:28'),
(3, 'Customer Service Excellence', 'Delivering exceptional customer service in tourism', 'Best practices for customer service, handling complaints, and ensuring customer satisfaction.', NULL, 30, 'beginner', 'customer_service', 1, 3, 'active', NULL, '2025-10-23 00:36:28', '2025-10-23 00:36:28'),
(4, 'Compliance and Legal Requirements', 'Understanding legal and compliance requirements in tourism', 'Important legal considerations, licensing requirements, and compliance standards.', NULL, 40, 'intermediate', 'compliance', 1, 4, 'active', NULL, '2025-10-23 00:36:28', '2025-10-23 00:36:28'),
(5, 'Digital Marketing for Tourism', 'Modern marketing strategies for tourism businesses', 'Social media marketing, content creation, and digital promotion strategies.', NULL, 50, 'intermediate', 'marketing', 0, 5, 'active', NULL, '2025-10-23 00:36:28', '2025-10-23 00:36:28'),
(6, 'Advanced Destination Knowledge', 'In-depth knowledge of specific African destinations', 'Detailed information about popular African destinations, attractions, and experiences.', NULL, 90, 'advanced', 'product_knowledge', 0, 6, 'active', NULL, '2025-10-23 00:36:28', '2025-10-23 00:36:28');

-- --------------------------------------------------------

--
-- Table structure for table `training_progress`
--

CREATE TABLE `training_progress` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  `status` enum('not_started','in_progress','completed','failed') DEFAULT 'not_started',
  `progress_percentage` int(11) DEFAULT 0,
  `score` int(11) DEFAULT 0,
  `attempts` int(11) DEFAULT 0,
  `started_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `last_accessed` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `training_quiz_attempts`
--

CREATE TABLE `training_quiz_attempts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  `attempt_number` int(11) NOT NULL,
  `total_questions` int(11) NOT NULL,
  `correct_answers` int(11) DEFAULT 0,
  `score_percentage` decimal(5,2) DEFAULT 0.00,
  `time_taken_minutes` int(11) DEFAULT 0,
  `status` enum('in_progress','completed','abandoned') DEFAULT 'in_progress',
  `started_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `completed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `training_quiz_questions`
--

CREATE TABLE `training_quiz_questions` (
  `id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  `question` text NOT NULL,
  `question_type` enum('multiple_choice','true_false','text') DEFAULT 'multiple_choice',
  `options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`options`)),
  `correct_answer` text NOT NULL,
  `explanation` text DEFAULT NULL,
  `points` int(11) DEFAULT 1,
  `order_sequence` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `training_quiz_questions`
--

INSERT INTO `training_quiz_questions` (`id`, `module_id`, `question`, `question_type`, `options`, `correct_answer`, `explanation`, `points`, `order_sequence`, `status`, `created_at`) VALUES
(1, 1, 'Which of the following is considered the \"Big Five\" in African wildlife?', 'multiple_choice', '[\"Lion, Elephant, Rhino, Buffalo, Leopard\", \"Lion, Elephant, Giraffe, Zebra, Cheetah\", \"Elephant, Hippo, Crocodile, Lion, Leopard\", \"Buffalo, Antelope, Lion, Elephant, Rhino\"]', 'Lion, Elephant, Rhino, Buffalo, Leopard', 'The Big Five refers to the five most difficult and dangerous animals to hunt on foot in Africa.', 2, 0, 'active', '2025-10-23 00:36:28'),
(2, 1, 'What is the best time to visit East Africa for the Great Migration?', 'multiple_choice', '[\"December-February\", \"June-September\", \"March-May\", \"October-November\"]', 'June-September', 'The Great Migration typically occurs during the dry season when animals move in search of water and fresh grazing.', 2, 0, 'active', '2025-10-23 00:36:28'),
(3, 2, 'What is the most important factor in closing a tourism sale?', 'multiple_choice', '[\"Price\", \"Building trust and rapport\", \"Product features\", \"Competitor comparison\"]', 'Building trust and rapport', 'Trust is the foundation of any successful tourism sale as customers are investing in experiences and memories.', 3, 0, 'active', '2025-10-23 00:36:28'),
(4, 3, 'How should you handle a customer complaint about accommodation?', 'multiple_choice', '[\"Blame the hotel\", \"Listen actively and offer solutions\", \"Ignore the complaint\", \"Offer a discount immediately\"]', 'Listen actively and offer solutions', 'Active listening and solution-focused approach builds customer confidence and loyalty.', 2, 0, 'active', '2025-10-23 00:36:28');

-- --------------------------------------------------------

--
-- Table structure for table `translations`
--

CREATE TABLE `translations` (
  `id` int(11) NOT NULL,
  `language_code` varchar(5) NOT NULL,
  `translation_key` varchar(255) NOT NULL,
  `translation_value` text NOT NULL,
  `category` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `translations`
--

INSERT INTO `translations` (`id`, `language_code`, `translation_key`, `translation_value`, `category`) VALUES
(1, 'en', 'welcome', 'Welcome', 'general'),
(2, 'fr', 'welcome', 'Bienvenue', 'general'),
(3, 'es', 'welcome', 'Bienvenido', 'general'),
(4, 'en', 'dashboard', 'Dashboard', 'navigation'),
(5, 'fr', 'dashboard', 'Tableau de bord', 'navigation'),
(6, 'es', 'dashboard', 'Panel de control', 'navigation');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('super_admin','mca','advisor','client') NOT NULL DEFAULT 'client',
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive','suspended') DEFAULT 'active',
  `email_verified` tinyint(1) DEFAULT 0,
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `sponsor_id` int(11) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `level` int(11) DEFAULT 1,
  `upline_id` int(11) DEFAULT NULL,
  `mca_id` int(11) DEFAULT NULL,
  `team_size` int(11) DEFAULT 0,
  `kyc_status` enum('pending','approved','rejected') DEFAULT 'pending',
  `training_status` enum('not_started','in_progress','completed','expired') DEFAULT 'not_started',
  `training_completion_date` date DEFAULT NULL,
  `kyc_approval_date` date DEFAULT NULL,
  `can_sell` tinyint(1) DEFAULT 0,
  `training_score` int(11) DEFAULT 0,
  `license_status` enum('none','pending','active','expired') DEFAULT 'none',
  `license_expiry_date` datetime DEFAULT NULL,
  `license_paid_amount` decimal(10,2) DEFAULT 0.00,
  `referral_code` varchar(20) DEFAULT NULL,
  `referred_by_code` varchar(20) DEFAULT NULL,
  `advisor_rank` enum('certified','senior','executive') DEFAULT 'certified',
  `total_sales` decimal(10,2) DEFAULT 0.00,
  `team_l2_count` int(11) DEFAULT 0,
  `team_l3_count` int(11) DEFAULT 0,
  `license_type` enum('none','basic','premium') DEFAULT 'none',
  `license_paid_date` date DEFAULT NULL,
  `license_amount` decimal(10,2) DEFAULT 0.00,
  `bio` text DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `emergency_contact_name` varchar(100) DEFAULT NULL,
  `emergency_contact_phone` varchar(20) DEFAULT NULL,
  `preferences` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `role`, `first_name`, `last_name`, `phone`, `country`, `city`, `address`, `profile_image`, `status`, `email_verified`, `last_login`, `created_at`, `updated_at`, `sponsor_id`, `username`, `full_name`, `level`, `upline_id`, `mca_id`, `team_size`, `kyc_status`, `training_status`, `training_completion_date`, `kyc_approval_date`, `can_sell`, `training_score`, `license_status`, `license_expiry_date`, `license_paid_amount`, `referral_code`, `referred_by_code`, `advisor_rank`, `total_sales`, `team_l2_count`, `team_l3_count`, `license_type`, `license_paid_date`, `license_amount`, `bio`, `state`, `postal_code`, `date_of_birth`, `emergency_contact_name`, `emergency_contact_phone`, `preferences`) VALUES
(1, 'admin@foreveryoungtours.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'super_admin', 'Super', 'Admin', '+254700000000', 'Kenya', NULL, NULL, NULL, 'active', 0, NULL, '2025-10-22 14:29:47', '2025-10-22 14:29:47', NULL, NULL, NULL, 1, NULL, NULL, 0, 'pending', 'not_started', NULL, NULL, 0, 0, 'none', NULL, 0.00, NULL, NULL, 'certified', 0.00, 0, 0, 'none', NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'mca.kenya@foreveryoungtours.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'mca', 'John', 'Kamau', '+254701000000', 'Kenya', NULL, NULL, NULL, 'active', 0, NULL, '2025-10-22 14:29:47', '2025-10-22 19:43:24', 1, NULL, NULL, 1, NULL, NULL, 0, 'pending', 'not_started', NULL, NULL, 0, 0, 'none', NULL, 0.00, NULL, NULL, 'certified', 0.00, 0, 0, 'none', NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'mca.tanzania@foreveryoungtours.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'mca', 'Grace', 'Mwangi', '+254702000000', 'Tanzania', NULL, NULL, NULL, 'active', 0, NULL, '2025-10-22 14:29:47', '2025-10-22 19:43:24', 1, NULL, NULL, 1, NULL, NULL, 0, 'pending', 'not_started', NULL, NULL, 0, 0, 'none', NULL, 0.00, NULL, NULL, 'certified', 0.00, 0, 0, 'none', NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 'mca.uganda@foreveryoungtours.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'mca', 'Peter', 'Ochieng', '+254703000000', 'Uganda', NULL, NULL, NULL, 'active', 0, NULL, '2025-10-22 14:29:47', '2025-10-22 19:43:24', 1, NULL, NULL, 1, NULL, NULL, 0, 'pending', 'not_started', NULL, NULL, 0, 0, 'none', NULL, 0.00, NULL, NULL, 'certified', 0.00, 0, 0, 'none', NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 'advisor1@foreveryoungtours.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'Mary', 'Wanjiku', '+254704000000', 'Kenya', NULL, NULL, NULL, 'active', 0, NULL, '2025-10-22 14:29:47', '2025-11-23 18:06:52', 2, NULL, NULL, 1, NULL, NULL, 0, 'pending', 'not_started', NULL, NULL, 0, 0, 'none', NULL, 0.00, 'ADV000005', NULL, 'certified', 0.00, 0, 0, 'none', NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 'advisor2@foreveryoungtours.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'James', 'Mutua', '+254705000000', 'Kenya', NULL, NULL, NULL, 'active', 0, NULL, '2025-10-22 14:29:47', '2025-11-23 18:06:52', 2, NULL, NULL, 1, NULL, NULL, 0, 'pending', 'not_started', NULL, NULL, 0, 0, 'none', NULL, 0.00, 'ADV000006', NULL, 'certified', 0.00, 0, 0, 'none', NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(7, 'advisor3@foreveryoungtours.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'Sarah', 'Njeri', '+254706000000', 'Tanzania', NULL, NULL, NULL, 'active', 0, NULL, '2025-10-22 14:29:47', '2025-11-23 18:06:52', 2, NULL, NULL, 1, NULL, NULL, 0, 'pending', 'not_started', NULL, NULL, 0, 0, 'none', NULL, 0.00, 'ADV000007', NULL, 'certified', 0.00, 0, 0, 'none', NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(8, 'client1@foreveryoungtours.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'client', 'David', 'Kiprotich', '+254707000000', 'Kenya', NULL, NULL, NULL, 'active', 0, NULL, '2025-10-22 14:29:47', '2025-10-22 19:43:24', 5, NULL, NULL, 1, NULL, NULL, 0, 'pending', 'not_started', NULL, NULL, 0, 0, 'none', NULL, 0.00, NULL, NULL, 'certified', 0.00, 0, 0, 'none', NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(9, 'client2@foreveryoungtours.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'client', 'Lucy', 'Akinyi', '+254708000000', 'Kenya', NULL, NULL, NULL, 'active', 0, NULL, '2025-10-22 14:29:47', '2025-10-22 19:43:24', 5, NULL, NULL, 1, NULL, NULL, 0, 'pending', 'not_started', NULL, NULL, 0, 0, 'none', NULL, 0.00, NULL, NULL, 'certified', 0.00, 0, 0, 'none', NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(10, 'client3@foreveryoungtours.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'client', 'Michael', 'Otieno', '+254709000000', 'Uganda', NULL, NULL, NULL, 'active', 0, NULL, '2025-10-22 14:29:47', '2025-10-22 19:43:24', 5, NULL, NULL, 1, NULL, NULL, 0, 'pending', 'not_started', NULL, NULL, 0, 0, 'none', NULL, 0.00, NULL, NULL, 'certified', 0.00, 0, 0, 'none', NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(11, 'mca.africa@foreveryoungtours.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'mca', '', '', NULL, NULL, NULL, NULL, NULL, 'active', 0, NULL, '2025-10-22 19:43:24', '2025-10-22 19:43:24', 1, 'mca_africa', 'John MCA Africa', 1, NULL, NULL, 0, 'pending', 'not_started', NULL, NULL, 0, 0, 'none', NULL, 0.00, NULL, NULL, 'certified', 0.00, 0, 0, 'none', NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(12, 'mca.east@foreveryoungtours.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'mca', '', '', NULL, NULL, NULL, NULL, NULL, 'active', 0, NULL, '2025-10-22 19:43:24', '2025-10-22 19:43:24', 1, 'mca_east', 'Sarah MCA East', 1, NULL, NULL, 0, 'pending', 'not_started', NULL, NULL, 0, 0, 'none', NULL, 0.00, NULL, NULL, 'certified', 0.00, 0, 0, 'none', NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(19, 'admin@foreveryoung.com', '$2y$10$Mr7/RbpcIkueovurt1HzN.rYsAfsGWmZOqeFLILysNgp245F7jn8m', 'super_admin', 'Super', 'Admin', '+1234567890', NULL, NULL, NULL, NULL, 'active', 1, NULL, '2025-10-26 18:00:23', '2025-10-26 18:28:46', NULL, NULL, NULL, 1, NULL, NULL, 0, 'pending', 'not_started', NULL, NULL, 0, 0, 'none', NULL, 0.00, NULL, NULL, 'certified', 0.00, 0, 0, 'none', NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(20, 'mca@foreveryoung.com', '$2y$10$FunnRDzvnojL.qJcYtHhdOWc.MIJoNpaHgg6dfWVAGUHwLb9qZ/Ja', 'mca', 'MCA', 'User', '+1234567891', NULL, NULL, NULL, NULL, 'active', 1, NULL, '2025-10-26 18:00:24', '2025-10-26 18:06:16', NULL, NULL, NULL, 1, NULL, NULL, 0, 'pending', 'not_started', NULL, NULL, 0, 0, 'none', NULL, 0.00, NULL, NULL, 'certified', 0.00, 0, 0, 'none', NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(21, 'advisor@foreveryoung.com', '$2y$10$A6sg7U2tGB33ly7lbx9ZW.zpnyJm7Z0r.vG2UzQSdaFZS27jK6/q.', 'advisor', 'Advisor', 'User', '+1234567892', NULL, NULL, NULL, NULL, 'active', 1, NULL, '2025-10-26 18:00:24', '2025-11-23 18:06:52', NULL, NULL, NULL, 1, NULL, NULL, 0, 'pending', 'not_started', NULL, NULL, 0, 0, 'none', NULL, 0.00, 'ADV000021', NULL, 'certified', 0.00, 0, 0, 'none', NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(22, 'client@foreveryoung.com', '$2y$10$0GLvYk5IIl9codQIW2Byx.1D8luDhTCBTOmRbV5BppQ9dy3Tr5omG', 'client', 'Client', 'User', '+1234567893', NULL, NULL, NULL, NULL, 'active', 1, NULL, '2025-10-26 18:00:24', '2025-10-26 18:28:46', NULL, NULL, NULL, 1, NULL, NULL, 0, 'pending', 'not_started', NULL, NULL, 0, 0, 'none', NULL, 0.00, NULL, NULL, 'certified', 0.00, 0, 0, 'none', NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(23, 'testname450@gmail.com', '$2y$10$oN60lriwsPA3JdA6x/1P2.LlEop0mbVocV0D4EKdwauCJV5x6f84W', 'client', 'Test', 'Name', '+250788712679', NULL, NULL, NULL, NULL, 'active', 0, NULL, '2025-10-30 07:15:37', '2025-10-30 07:15:37', NULL, NULL, NULL, 1, NULL, NULL, 0, 'pending', 'not_started', NULL, NULL, 0, 0, 'none', NULL, 0.00, NULL, NULL, 'certified', 0.00, 0, 0, 'none', NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(25, 'acountexpertbroker@gmail.com', '$2y$10$aaVSKmfNif6BRACxzfLjWeKr5DSl.vrusx7w2nlAkDwJIL9W/LvMe', 'client', 'peter', 'Doe', '+250788712679', 'Rwanda', 'Kigali', NULL, NULL, 'active', 1, NULL, '2025-10-30 12:06:21', '2025-10-30 12:06:21', NULL, NULL, NULL, 1, NULL, NULL, 0, 'pending', 'not_started', NULL, NULL, 0, 0, 'none', NULL, 0.00, NULL, NULL, 'certified', 0.00, 0, 0, 'none', NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_addresses`
--

CREATE TABLE `user_addresses` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `address_type` enum('shipping','billing','both') DEFAULT 'both',
  `full_name` varchar(255) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `address_line1` varchar(255) NOT NULL,
  `address_line2` varchar(255) DEFAULT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) DEFAULT NULL,
  `country` varchar(100) NOT NULL,
  `postal_code` varchar(20) NOT NULL,
  `is_default` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `user_hierarchy_view`
-- (See below for the actual view)
--
CREATE TABLE `user_hierarchy_view` (
`id` int(11)
,`user_id` int(11)
,`user_name` varchar(201)
,`user_email` varchar(255)
,`user_role` enum('super_admin','mca','advisor','client')
,`parent_id` int(11)
,`parent_name` varchar(201)
,`parent_email` varchar(255)
,`level` int(11)
,`path` varchar(500)
);

-- --------------------------------------------------------

--
-- Table structure for table `user_memberships`
--

CREATE TABLE `user_memberships` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tier_id` int(11) NOT NULL,
  `start_date` datetime NOT NULL,
  `expiry_date` datetime NOT NULL,
  `status` enum('active','expired','cancelled') DEFAULT 'active',
  `payment_amount` decimal(10,2) DEFAULT NULL,
  `stripe_payment_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vip_services`
--

CREATE TABLE `vip_services` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `service_type` enum('airport_transfer','concierge','private_tour','meet_greet','lounge_access') NOT NULL,
  `booking_id` int(11) DEFAULT NULL,
  `service_date` datetime DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `status` enum('requested','confirmed','completed','cancelled') DEFAULT 'requested',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `visa_services`
--

CREATE TABLE `visa_services` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `destination_country` varchar(100) NOT NULL,
  `visa_type` varchar(50) DEFAULT NULL,
  `application_status` enum('pending','processing','approved','rejected','delivered') DEFAULT 'pending',
  `passport_number` varchar(50) DEFAULT NULL,
  `passport_expiry` date DEFAULT NULL,
  `travel_date` date DEFAULT NULL,
  `documents` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`documents`)),
  `fee_amount` decimal(10,2) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure for view `booking_details_view`
--
DROP TABLE IF EXISTS `booking_details_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `booking_details_view`  AS SELECT `b`.`id` AS `id`, `b`.`booking_reference` AS `booking_reference`, `b`.`customer_name` AS `customer_name`, `b`.`customer_email` AS `customer_email`, `b`.`travel_date` AS `travel_date`, `b`.`participants` AS `participants`, `b`.`total_amount` AS `total_amount`, `b`.`status` AS `status`, `b`.`booking_date` AS `booking_date`, `t`.`name` AS `tour_name`, `t`.`destination` AS `destination`, `t`.`destination_country` AS `destination_country`, concat(`u`.`first_name`,' ',`u`.`last_name`) AS `client_name`, concat(`a`.`first_name`,' ',`a`.`last_name`) AS `advisor_name` FROM (((`bookings` `b` join `tours` `t` on(`b`.`tour_id` = `t`.`id`)) join `users` `u` on(`b`.`user_id` = `u`.`id`)) join `users` `a` on(`b`.`advisor_id` = `a`.`id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `commission_summary_view`
--
DROP TABLE IF EXISTS `commission_summary_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `commission_summary_view`  AS SELECT `c`.`user_id` AS `user_id`, concat(`u`.`first_name`,' ',`u`.`last_name`) AS `user_name`, `u`.`role` AS `role`, count(`c`.`id`) AS `total_commissions`, sum(`c`.`commission_amount`) AS `total_earned`, sum(case when `c`.`status` = 'paid' then `c`.`commission_amount` else 0 end) AS `total_paid`, sum(case when `c`.`status` = 'pending' then `c`.`commission_amount` else 0 end) AS `total_pending` FROM (`commissions` `c` join `users` `u` on(`c`.`user_id` = `u`.`id`)) GROUP BY `c`.`user_id`, `u`.`first_name`, `u`.`last_name`, `u`.`role` ;

-- --------------------------------------------------------

--
-- Structure for view `region_country_tours_view`
--
DROP TABLE IF EXISTS `region_country_tours_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `region_country_tours_view`  AS SELECT `r`.`id` AS `region_id`, `r`.`name` AS `region_name`, `r`.`slug` AS `region_slug`, `c`.`id` AS `country_id`, `c`.`name` AS `country_name`, `c`.`slug` AS `country_slug`, `c`.`country_code` AS `country_code`, count(`t`.`id`) AS `tour_count`, min(`t`.`price`) AS `min_price`, max(`t`.`price`) AS `max_price` FROM ((`regions` `r` left join `countries` `c` on(`r`.`id` = `c`.`region_id`)) left join `tours` `t` on(`c`.`id` = `t`.`country_id` and `t`.`status` = 'active')) WHERE `r`.`status` = 'active' AND (`c`.`status` = 'active' OR `c`.`status` is null) GROUP BY `r`.`id`, `r`.`name`, `r`.`slug`, `c`.`id`, `c`.`name`, `c`.`slug`, `c`.`country_code` ORDER BY `r`.`name` ASC, `c`.`name` ASC ;

-- --------------------------------------------------------

--
-- Structure for view `team_hierarchy_view`
--
DROP TABLE IF EXISTS `team_hierarchy_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `team_hierarchy_view`  AS SELECT `u1`.`id` AS `advisor_id`, `u1`.`full_name` AS `advisor_name`, `u1`.`level` AS `level`, `u2`.`id` AS `upline_id`, `u2`.`full_name` AS `upline_name`, `u3`.`id` AS `mca_id`, `u3`.`full_name` AS `mca_name`, count(`u4`.`id`) AS `team_count` FROM (((`users` `u1` left join `users` `u2` on(`u1`.`upline_id` = `u2`.`id`)) left join `users` `u3` on(`u1`.`mca_id` = `u3`.`id`)) left join `users` `u4` on(`u4`.`upline_id` = `u1`.`id`)) WHERE `u1`.`role` in ('advisor','client') GROUP BY `u1`.`id` ;

-- --------------------------------------------------------

--
-- Structure for view `user_hierarchy_view`
--
DROP TABLE IF EXISTS `user_hierarchy_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `user_hierarchy_view`  AS SELECT `h`.`id` AS `id`, `h`.`user_id` AS `user_id`, concat(`u`.`first_name`,' ',`u`.`last_name`) AS `user_name`, `u`.`email` AS `user_email`, `u`.`role` AS `user_role`, `h`.`parent_id` AS `parent_id`, concat(`p`.`first_name`,' ',`p`.`last_name`) AS `parent_name`, `p`.`email` AS `parent_email`, `h`.`level` AS `level`, `h`.`path` AS `path` FROM ((`mlm_hierarchy` `h` join `users` `u` on(`h`.`user_id` = `u`.`id`)) left join `users` `p` on(`h`.`parent_id` = `p`.`id`)) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `advisor_team`
--
ALTER TABLE `advisor_team`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_team_member` (`team_member_id`),
  ADD KEY `advisor_id` (`advisor_id`);

--
-- Indexes for table `analytics_metrics`
--
ALTER TABLE `analytics_metrics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_type_date` (`metric_type`,`metric_date`);

--
-- Indexes for table `api_integrations`
--
ALTER TABLE `api_integrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `api_requests`
--
ALTER TABLE `api_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_integration` (`integration_id`),
  ADD KEY `idx_created` (`created_at`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_entity` (`entity_type`,`entity_id`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `blog_categories`
--
ALTER TABLE `blog_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `blog_comments`
--
ALTER TABLE `blog_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`),
  ADD KEY `idx_post` (`post_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `blog_likes`
--
ALTER TABLE `blog_likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_like` (`post_id`,`user_ip`);

--
-- Indexes for table `blog_posts`
--
ALTER TABLE `blog_posts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `tour_id` (`tour_id`),
  ADD KEY `country_id` (`country_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_featured` (`featured`),
  ADD KEY `idx_published` (`published_at`),
  ADD KEY `idx_category` (`category_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `blog_post_tags`
--
ALTER TABLE `blog_post_tags`
  ADD PRIMARY KEY (`post_id`,`tag_id`),
  ADD KEY `tag_id` (`tag_id`);

--
-- Indexes for table `blog_tags`
--
ALTER TABLE `blog_tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `booking_reference` (`booking_reference`),
  ADD KEY `idx_booking_reference` (`booking_reference`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_tour_id` (`tour_id`),
  ADD KEY `idx_advisor_id` (`advisor_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_travel_date` (`travel_date`),
  ADD KEY `idx_bookings_date_range` (`travel_date`,`booking_date`);

--
-- Indexes for table `booking_activities`
--
ALTER TABLE `booking_activities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `booking_cars`
--
ALTER TABLE `booking_cars`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `booking_cruises`
--
ALTER TABLE `booking_cruises`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `booking_engine_orders`
--
ALTER TABLE `booking_engine_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `booking_reference` (`booking_reference`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `booking_type` (`booking_type`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `booking_flights`
--
ALTER TABLE `booking_flights`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `booking_hotels`
--
ALTER TABLE `booking_hotels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `booking_inquiries`
--
ALTER TABLE `booking_inquiries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `booking_modifications`
--
ALTER TABLE `booking_modifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `requested_by` (`requested_by`),
  ADD KEY `processed_by` (`processed_by`),
  ADD KEY `idx_booking_id` (`booking_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `booking_payments`
--
ALTER TABLE `booking_payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transaction_id` (`transaction_id`),
  ADD KEY `idx_booking_id` (`booking_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_transaction_id` (`transaction_id`);

--
-- Indexes for table `booking_status_history`
--
ALTER TABLE `booking_status_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_booking` (`booking_id`);

--
-- Indexes for table `client_registry`
--
ALTER TABLE `client_registry`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `portal_code` (`portal_code`),
  ADD UNIQUE KEY `unique_email` (`client_email`),
  ADD UNIQUE KEY `unique_phone` (`client_phone`),
  ADD UNIQUE KEY `unique_portal_code` (`portal_code`),
  ADD KEY `idx_owner` (`owned_by_user_id`),
  ADD KEY `idx_status` (`ownership_status`),
  ADD KEY `idx_email_phone` (`client_email`,`client_phone`);

--
-- Indexes for table `commissions`
--
ALTER TABLE `commissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_booking_id` (`booking_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_commissions_user_status` (`user_id`,`status`);

--
-- Indexes for table `commission_settings`
--
ALTER TABLE `commission_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_region_id` (`region_id`),
  ADD KEY `idx_slug` (`slug`),
  ADD KEY `idx_country_code` (`country_code`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_featured` (`featured`),
  ADD KEY `idx_country_slug` (`slug`);

--
-- Indexes for table `coupon_usage`
--
ALTER TABLE `coupon_usage`
  ADD PRIMARY KEY (`id`),
  ADD KEY `coupon_id` (`coupon_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `destinations`
--
ALTER TABLE `destinations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_country` (`country`),
  ADD KEY `idx_featured` (`featured`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `discount_coupons`
--
ALTER TABLE `discount_coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `idx_code` (`code`),
  ADD KEY `idx_active` (`is_active`);

--
-- Indexes for table `kyc_documents`
--
ALTER TABLE `kyc_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `verified_by` (`verified_by`);

--
-- Indexes for table `kyc_status`
--
ALTER TABLE `kyc_status`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD KEY `approved_by` (`approved_by`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `license_fees`
--
ALTER TABLE `license_fees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_license` (`user_id`,`license_type`),
  ADD KEY `idx_payment_status` (`payment_status`);

--
-- Indexes for table `license_payments`
--
ALTER TABLE `license_payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transaction_id` (`transaction_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_transaction_id` (`transaction_id`);

--
-- Indexes for table `mca_assignments`
--
ALTER TABLE `mca_assignments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_mca_country` (`mca_id`,`country_id`),
  ADD KEY `country_id` (`country_id`);

--
-- Indexes for table `membership_tiers`
--
ALTER TABLE `membership_tiers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `mlm_hierarchy`
--
ALTER TABLE `mlm_hierarchy`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_parent_id` (`parent_id`),
  ADD KEY `idx_level` (`level`),
  ADD KEY `idx_hierarchy_path` (`path`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_is_read` (`is_read`),
  ADD KEY `idx_type` (`type`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `idx_order_number` (`order_number`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_order_status` (`order_status`),
  ADD KEY `idx_payment_status` (`payment_status`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `ownership_alerts`
--
ALTER TABLE `ownership_alerts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_advisor` (`advisor_id`,`is_read`),
  ADD KEY `idx_portal` (`portal_code`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payment_reference` (`payment_reference`),
  ADD KEY `idx_booking_id` (`booking_id`),
  ADD KEY `idx_payment_reference` (`payment_reference`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `payment_logs`
--
ALTER TABLE `payment_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_payment_type` (`payment_type`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `payment_transactions`
--
ALTER TABLE `payment_transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transaction_id` (`transaction_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `idx_transaction_id` (`transaction_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `payout_requests`
--
ALTER TABLE `payout_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_status` (`user_id`,`status`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `portal_activity`
--
ALTER TABLE `portal_activity`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_portal` (`portal_code`),
  ADD KEY `idx_type` (`activity_type`);

--
-- Indexes for table `portal_messages`
--
ALTER TABLE `portal_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_portal` (`portal_code`),
  ADD KEY `idx_unread` (`portal_code`,`is_read`);

--
-- Indexes for table `portal_tours`
--
ALTER TABLE `portal_tours`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_portal` (`portal_code`),
  ADD KEY `idx_tour` (`tour_id`);

--
-- Indexes for table `regions`
--
ALTER TABLE `regions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_slug` (`slug`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_featured` (`featured`),
  ADD KEY `idx_region_slug` (`slug`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_tour_id` (`tour_id`),
  ADD KEY `idx_rating` (`rating`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `shared_links`
--
ALTER TABLE `shared_links`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `link_code` (`link_code`),
  ADD KEY `idx_shared_links_code` (`link_code`),
  ADD KEY `idx_shared_links_user` (`user_id`),
  ADD KEY `idx_shared_links_tour` (`tour_id`),
  ADD KEY `idx_shared_links_active` (`is_active`);

--
-- Indexes for table `shopping_cart`
--
ALTER TABLE `shopping_cart`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_product` (`user_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `store_categories`
--
ALTER TABLE `store_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_slug` (`slug`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `store_orders`
--
ALTER TABLE `store_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `idx_order_number` (`order_number`),
  ADD KEY `idx_customer_email` (`customer_email`),
  ADD KEY `idx_order_status` (`order_status`),
  ADD KEY `idx_payment_status` (`payment_status`);

--
-- Indexes for table `store_order_items`
--
ALTER TABLE `store_order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_order` (`order_id`),
  ADD KEY `idx_product` (`product_id`);

--
-- Indexes for table `store_products`
--
ALTER TABLE `store_products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD KEY `idx_category` (`category_id`),
  ADD KEY `idx_slug` (`slug`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_featured` (`is_featured`),
  ADD KEY `idx_sale` (`is_on_sale`);

--
-- Indexes for table `store_reviews`
--
ALTER TABLE `store_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_product` (`product_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `store_settings`
--
ALTER TABLE `store_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`),
  ADD KEY `idx_key` (`setting_key`);

--
-- Indexes for table `store_wishlist`
--
ALTER TABLE `store_wishlist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_customer` (`customer_email`),
  ADD KEY `idx_product` (`product_id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`),
  ADD KEY `idx_setting_key` (`setting_key`);

--
-- Indexes for table `tours`
--
ALTER TABLE `tours`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `idx_destination_country` (`destination_country`),
  ADD KEY `idx_category` (`category`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_featured` (`featured`),
  ADD KEY `idx_tours_country_category` (`destination_country`,`category`),
  ADD KEY `idx_country_id` (`country_id`),
  ADD KEY `idx_tours_difficulty` (`difficulty_level`),
  ADD KEY `idx_tours_tour_type` (`tour_type`),
  ADD KEY `idx_tours_popularity` (`popularity_score`),
  ADD KEY `idx_tours_rating` (`average_rating`),
  ADD KEY `idx_tours_booking_count` (`booking_count`),
  ADD KEY `idx_tour_slug` (`slug`),
  ADD KEY `region_id` (`region_id`);

--
-- Indexes for table `tour_availability`
--
ALTER TABLE `tour_availability`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_tour_date` (`tour_id`,`available_date`),
  ADD KEY `idx_tour_availability_date` (`available_date`),
  ADD KEY `idx_tour_availability_status` (`status`);

--
-- Indexes for table `tour_bookings`
--
ALTER TABLE `tour_bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `booking_number` (`booking_number`),
  ADD KEY `idx_booking_number` (`booking_number`),
  ADD KEY `idx_tour` (`tour_id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_status` (`booking_status`),
  ADD KEY `idx_payment_status` (`payment_status`),
  ADD KEY `idx_tour_date` (`tour_date`),
  ADD KEY `idx_booking_date` (`tour_date`),
  ADD KEY `idx_booking_status` (`booking_status`);

--
-- Indexes for table `tour_faqs`
--
ALTER TABLE `tour_faqs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_tour_faqs_tour_id` (`tour_id`),
  ADD KEY `idx_tour_faqs_active` (`is_active`);

--
-- Indexes for table `tour_images`
--
ALTER TABLE `tour_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_tour_images_tour_id` (`tour_id`),
  ADD KEY `idx_tour_images_type` (`image_type`),
  ADD KEY `idx_tour_images_featured` (`is_featured`);

--
-- Indexes for table `tour_order_details`
--
ALTER TABLE `tour_order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `tour_reviews`
--
ALTER TABLE `tour_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `idx_tour_reviews_tour_id` (`tour_id`),
  ADD KEY `idx_tour_reviews_rating` (`rating`),
  ADD KEY `idx_tour_reviews_status` (`status`);

--
-- Indexes for table `tour_schedules`
--
ALTER TABLE `tour_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`),
  ADD KEY `scheduled_date` (`scheduled_date`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `tour_schedule_bookings`
--
ALTER TABLE `tour_schedule_bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `booking_reference` (`booking_reference`),
  ADD KEY `schedule_id` (`schedule_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tour_wishlist`
--
ALTER TABLE `tour_wishlist`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_tour` (`user_id`,`tour_id`),
  ADD KEY `tour_id` (`tour_id`),
  ADD KEY `idx_user` (`user_id`);

--
-- Indexes for table `training_assignments`
--
ALTER TABLE `training_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assigned_by` (`assigned_by`),
  ADD KEY `assigned_to` (`assigned_to`),
  ADD KEY `module_id` (`module_id`);

--
-- Indexes for table `training_certificates`
--
ALTER TABLE `training_certificates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `certificate_number` (`certificate_number`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `module_id` (`module_id`),
  ADD KEY `issued_by` (`issued_by`);

--
-- Indexes for table `training_modules`
--
ALTER TABLE `training_modules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `training_progress`
--
ALTER TABLE `training_progress`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_module` (`user_id`,`module_id`),
  ADD KEY `module_id` (`module_id`);

--
-- Indexes for table `training_quiz_attempts`
--
ALTER TABLE `training_quiz_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `module_id` (`module_id`);

--
-- Indexes for table `training_quiz_questions`
--
ALTER TABLE `training_quiz_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `module_id` (`module_id`);

--
-- Indexes for table `translations`
--
ALTER TABLE `translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_translation` (`language_code`,`translation_key`),
  ADD KEY `idx_key` (`translation_key`),
  ADD KEY `idx_category` (`category`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `referral_code` (`referral_code`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_role` (`role`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `sponsor_id` (`sponsor_id`);

--
-- Indexes for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_memberships`
--
ALTER TABLE `user_memberships`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_status` (`user_id`,`status`);

--
-- Indexes for table `vip_services`
--
ALTER TABLE `vip_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `visa_services`
--
ALTER TABLE `visa_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assigned_to` (`assigned_to`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_status` (`application_status`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_product` (`user_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `advisor_team`
--
ALTER TABLE `advisor_team`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `analytics_metrics`
--
ALTER TABLE `analytics_metrics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `api_integrations`
--
ALTER TABLE `api_integrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `api_requests`
--
ALTER TABLE `api_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blog_categories`
--
ALTER TABLE `blog_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `blog_comments`
--
ALTER TABLE `blog_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blog_likes`
--
ALTER TABLE `blog_likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blog_posts`
--
ALTER TABLE `blog_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blog_tags`
--
ALTER TABLE `blog_tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `booking_activities`
--
ALTER TABLE `booking_activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `booking_cars`
--
ALTER TABLE `booking_cars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `booking_cruises`
--
ALTER TABLE `booking_cruises`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `booking_engine_orders`
--
ALTER TABLE `booking_engine_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking_flights`
--
ALTER TABLE `booking_flights`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `booking_hotels`
--
ALTER TABLE `booking_hotels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `booking_inquiries`
--
ALTER TABLE `booking_inquiries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `booking_modifications`
--
ALTER TABLE `booking_modifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking_payments`
--
ALTER TABLE `booking_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking_status_history`
--
ALTER TABLE `booking_status_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_registry`
--
ALTER TABLE `client_registry`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `commissions`
--
ALTER TABLE `commissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `commission_settings`
--
ALTER TABLE `commission_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `coupon_usage`
--
ALTER TABLE `coupon_usage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `destinations`
--
ALTER TABLE `destinations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `discount_coupons`
--
ALTER TABLE `discount_coupons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `kyc_documents`
--
ALTER TABLE `kyc_documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kyc_status`
--
ALTER TABLE `kyc_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `license_fees`
--
ALTER TABLE `license_fees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `license_payments`
--
ALTER TABLE `license_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mca_assignments`
--
ALTER TABLE `mca_assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `membership_tiers`
--
ALTER TABLE `membership_tiers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `mlm_hierarchy`
--
ALTER TABLE `mlm_hierarchy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ownership_alerts`
--
ALTER TABLE `ownership_alerts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_logs`
--
ALTER TABLE `payment_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_transactions`
--
ALTER TABLE `payment_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payout_requests`
--
ALTER TABLE `payout_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `portal_activity`
--
ALTER TABLE `portal_activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `portal_messages`
--
ALTER TABLE `portal_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `portal_tours`
--
ALTER TABLE `portal_tours`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `regions`
--
ALTER TABLE `regions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shared_links`
--
ALTER TABLE `shared_links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shopping_cart`
--
ALTER TABLE `shopping_cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `store_categories`
--
ALTER TABLE `store_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `store_orders`
--
ALTER TABLE `store_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `store_order_items`
--
ALTER TABLE `store_order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `store_products`
--
ALTER TABLE `store_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `store_reviews`
--
ALTER TABLE `store_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `store_settings`
--
ALTER TABLE `store_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `store_wishlist`
--
ALTER TABLE `store_wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tours`
--
ALTER TABLE `tours`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `tour_availability`
--
ALTER TABLE `tour_availability`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tour_bookings`
--
ALTER TABLE `tour_bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tour_faqs`
--
ALTER TABLE `tour_faqs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `tour_images`
--
ALTER TABLE `tour_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `tour_order_details`
--
ALTER TABLE `tour_order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tour_reviews`
--
ALTER TABLE `tour_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tour_schedules`
--
ALTER TABLE `tour_schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tour_schedule_bookings`
--
ALTER TABLE `tour_schedule_bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tour_wishlist`
--
ALTER TABLE `tour_wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `training_assignments`
--
ALTER TABLE `training_assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `training_certificates`
--
ALTER TABLE `training_certificates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `training_modules`
--
ALTER TABLE `training_modules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `training_progress`
--
ALTER TABLE `training_progress`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `training_quiz_attempts`
--
ALTER TABLE `training_quiz_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `training_quiz_questions`
--
ALTER TABLE `training_quiz_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `translations`
--
ALTER TABLE `translations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `user_addresses`
--
ALTER TABLE `user_addresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_memberships`
--
ALTER TABLE `user_memberships`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vip_services`
--
ALTER TABLE `vip_services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `visa_services`
--
ALTER TABLE `visa_services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `advisor_team`
--
ALTER TABLE `advisor_team`
  ADD CONSTRAINT `advisor_team_ibfk_1` FOREIGN KEY (`advisor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `advisor_team_ibfk_2` FOREIGN KEY (`team_member_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `api_requests`
--
ALTER TABLE `api_requests`
  ADD CONSTRAINT `api_requests_ibfk_1` FOREIGN KEY (`integration_id`) REFERENCES `api_integrations` (`id`);

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `blog_comments`
--
ALTER TABLE `blog_comments`
  ADD CONSTRAINT `blog_comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `blog_posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `blog_comments_ibfk_2` FOREIGN KEY (`parent_id`) REFERENCES `blog_comments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `blog_likes`
--
ALTER TABLE `blog_likes`
  ADD CONSTRAINT `blog_likes_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `blog_posts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `blog_posts`
--
ALTER TABLE `blog_posts`
  ADD CONSTRAINT `blog_posts_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `blog_categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `blog_posts_ibfk_2` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `blog_posts_ibfk_3` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `blog_posts_ibfk_4` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `blog_post_tags`
--
ALTER TABLE `blog_post_tags`
  ADD CONSTRAINT `blog_post_tags_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `blog_posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `blog_post_tags_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `blog_tags` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`);

--
-- Constraints for table `booking_engine_orders`
--
ALTER TABLE `booking_engine_orders`
  ADD CONSTRAINT `fk_booking_engine_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `booking_modifications`
--
ALTER TABLE `booking_modifications`
  ADD CONSTRAINT `booking_modifications_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_modifications_ibfk_2` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `booking_modifications_ibfk_3` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `booking_payments`
--
ALTER TABLE `booking_payments`
  ADD CONSTRAINT `booking_payments_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `booking_status_history`
--
ALTER TABLE `booking_status_history`
  ADD CONSTRAINT `booking_status_history_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `tour_bookings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `commissions`
--
ALTER TABLE `commissions`
  ADD CONSTRAINT `commissions_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `commissions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `countries`
--
ALTER TABLE `countries`
  ADD CONSTRAINT `countries_ibfk_1` FOREIGN KEY (`region_id`) REFERENCES `regions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `coupon_usage`
--
ALTER TABLE `coupon_usage`
  ADD CONSTRAINT `coupon_usage_ibfk_1` FOREIGN KEY (`coupon_id`) REFERENCES `discount_coupons` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `coupon_usage_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `coupon_usage_ibfk_3` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kyc_documents`
--
ALTER TABLE `kyc_documents`
  ADD CONSTRAINT `kyc_documents_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kyc_documents_ibfk_2` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `kyc_status`
--
ALTER TABLE `kyc_status`
  ADD CONSTRAINT `kyc_status_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kyc_status_ibfk_2` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `license_payments`
--
ALTER TABLE `license_payments`
  ADD CONSTRAINT `license_payments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `mca_assignments`
--
ALTER TABLE `mca_assignments`
  ADD CONSTRAINT `mca_assignments_ibfk_1` FOREIGN KEY (`mca_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `mca_assignments_ibfk_2` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`);

--
-- Constraints for table `mlm_hierarchy`
--
ALTER TABLE `mlm_hierarchy`
  ADD CONSTRAINT `mlm_hierarchy_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `mlm_hierarchy_ibfk_2` FOREIGN KEY (`parent_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `store_products` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `ownership_alerts`
--
ALTER TABLE `ownership_alerts`
  ADD CONSTRAINT `ownership_alerts_ibfk_1` FOREIGN KEY (`portal_code`) REFERENCES `client_registry` (`portal_code`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payment_transactions`
--
ALTER TABLE `payment_transactions`
  ADD CONSTRAINT `payment_transactions_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `portal_activity`
--
ALTER TABLE `portal_activity`
  ADD CONSTRAINT `portal_activity_ibfk_1` FOREIGN KEY (`portal_code`) REFERENCES `client_registry` (`portal_code`) ON DELETE CASCADE;

--
-- Constraints for table `portal_messages`
--
ALTER TABLE `portal_messages`
  ADD CONSTRAINT `portal_messages_ibfk_1` FOREIGN KEY (`portal_code`) REFERENCES `client_registry` (`portal_code`) ON DELETE CASCADE;

--
-- Constraints for table `portal_tours`
--
ALTER TABLE `portal_tours`
  ADD CONSTRAINT `portal_tours_ibfk_1` FOREIGN KEY (`portal_code`) REFERENCES `client_registry` (`portal_code`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `shared_links`
--
ALTER TABLE `shared_links`
  ADD CONSTRAINT `shared_links_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `shared_links_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `shopping_cart`
--
ALTER TABLE `shopping_cart`
  ADD CONSTRAINT `shopping_cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `shopping_cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `store_products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `store_order_items`
--
ALTER TABLE `store_order_items`
  ADD CONSTRAINT `store_order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `store_orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `store_order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `store_products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `store_products`
--
ALTER TABLE `store_products`
  ADD CONSTRAINT `store_products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `store_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `store_reviews`
--
ALTER TABLE `store_reviews`
  ADD CONSTRAINT `store_reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `store_products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `store_wishlist`
--
ALTER TABLE `store_wishlist`
  ADD CONSTRAINT `store_wishlist_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `store_products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tours`
--
ALTER TABLE `tours`
  ADD CONSTRAINT `tours_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `tours_ibfk_2` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tours_ibfk_3` FOREIGN KEY (`region_id`) REFERENCES `regions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tours_ibfk_4` FOREIGN KEY (`region_id`) REFERENCES `regions` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `tour_availability`
--
ALTER TABLE `tour_availability`
  ADD CONSTRAINT `tour_availability_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tour_bookings`
--
ALTER TABLE `tour_bookings`
  ADD CONSTRAINT `tour_bookings_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tour_bookings_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `tour_faqs`
--
ALTER TABLE `tour_faqs`
  ADD CONSTRAINT `tour_faqs_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tour_images`
--
ALTER TABLE `tour_images`
  ADD CONSTRAINT `tour_images_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tour_order_details`
--
ALTER TABLE `tour_order_details`
  ADD CONSTRAINT `tour_order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tour_reviews`
--
ALTER TABLE `tour_reviews`
  ADD CONSTRAINT `tour_reviews_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tour_reviews_ibfk_2` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `tour_schedules`
--
ALTER TABLE `tour_schedules`
  ADD CONSTRAINT `fk_schedule_tour` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tour_schedule_bookings`
--
ALTER TABLE `tour_schedule_bookings`
  ADD CONSTRAINT `fk_schedule_booking` FOREIGN KEY (`schedule_id`) REFERENCES `tour_schedules` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_schedule_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tour_wishlist`
--
ALTER TABLE `tour_wishlist`
  ADD CONSTRAINT `tour_wishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tour_wishlist_ibfk_2` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `training_assignments`
--
ALTER TABLE `training_assignments`
  ADD CONSTRAINT `training_assignments_ibfk_1` FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `training_assignments_ibfk_2` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `training_assignments_ibfk_3` FOREIGN KEY (`module_id`) REFERENCES `training_modules` (`id`);

--
-- Constraints for table `training_certificates`
--
ALTER TABLE `training_certificates`
  ADD CONSTRAINT `training_certificates_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `training_certificates_ibfk_2` FOREIGN KEY (`module_id`) REFERENCES `training_modules` (`id`),
  ADD CONSTRAINT `training_certificates_ibfk_3` FOREIGN KEY (`issued_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `training_modules`
--
ALTER TABLE `training_modules`
  ADD CONSTRAINT `training_modules_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `training_progress`
--
ALTER TABLE `training_progress`
  ADD CONSTRAINT `training_progress_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `training_progress_ibfk_2` FOREIGN KEY (`module_id`) REFERENCES `training_modules` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `training_quiz_attempts`
--
ALTER TABLE `training_quiz_attempts`
  ADD CONSTRAINT `training_quiz_attempts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `training_quiz_attempts_ibfk_2` FOREIGN KEY (`module_id`) REFERENCES `training_modules` (`id`);

--
-- Constraints for table `training_quiz_questions`
--
ALTER TABLE `training_quiz_questions`
  ADD CONSTRAINT `training_quiz_questions_ibfk_1` FOREIGN KEY (`module_id`) REFERENCES `training_modules` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `translations`
--
ALTER TABLE `translations`
  ADD CONSTRAINT `translations_ibfk_1` FOREIGN KEY (`language_code`) REFERENCES `languages` (`code`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`sponsor_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD CONSTRAINT `user_addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vip_services`
--
ALTER TABLE `vip_services`
  ADD CONSTRAINT `vip_services_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vip_services_ibfk_2` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`);

--
-- Constraints for table `visa_services`
--
ALTER TABLE `visa_services`
  ADD CONSTRAINT `visa_services_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `visa_services_ibfk_2` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`);

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `store_products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
