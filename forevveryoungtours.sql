-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 04, 2025 at 03:28 PM
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
  `special_requests` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `booking_reference`, `user_id`, `tour_id`, `advisor_id`, `customer_name`, `customer_email`, `customer_phone`, `travel_date`, `participants`, `total_amount`, `commission_amount`, `status`, `payment_status`, `payment_method`, `notes`, `booking_date`, `confirmed_date`, `cancelled_date`, `total_price`, `created_at`, `updated_at`, `advisor_reference`, `shared_link_id`, `booking_source`, `commission_paid`, `customer_rating`, `customer_review`, `follow_up_sent`, `referred_by`, `commission_level`, `emergency_contact`, `accommodation_type`, `transport_type`, `special_requests`) VALUES
(14, 'FYT014', NULL, 9, NULL, 'Jean Aime', 'baraime450@gmail.com', '0781234567', '2025-10-31', 6, 14619.00, 0.00, 'pending', 'pending', 'card', NULL, '2025-10-23 12:14:23', NULL, NULL, 0.00, '2025-10-23 12:14:23', '2025-10-26 19:16:17', NULL, NULL, 'direct', 0, NULL, NULL, 0, NULL, 1, '0787654321', 'premium', 'premium', 'If your device has more than one messaging app, you can make Google Messages your default messaging app. When you make Google Messages your default messaging app, you can review your text message history in Google Messages, and you\'ll only be able to send and receive new text messages in Google Messages.'),
(15, 'FYT015', 22, 2, NULL, 'Client User', 'client@foreveryoung.com', '0788712679', '2025-10-29', 4, 15725.60, 0.00, 'paid', 'pending', 'card', NULL, '2025-10-26 18:49:31', '2025-10-26 18:16:33', NULL, 15725.60, '2025-10-26 18:49:31', '2025-10-27 07:54:23', NULL, NULL, 'direct', 0, NULL, NULL, 0, NULL, 1, '0781234567', 'luxury', 'premium', 'Special Requests or Dietary RequirementsSpecial Requests or Dietary RequirementsSpecial Requests or Dietary RequirementsSpecial Requests or Dietary RequirementsSpecial Requests or Dietary RequirementsSpecial Requests or Dietary RequirementsSpecial Requests or Dietary Requirements');

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
-- Table structure for table `commissions`
--

CREATE TABLE `commissions` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `commission_type` enum('direct','level1','level2','level3','override') NOT NULL,
  `commission_rate` decimal(5,2) NOT NULL,
  `commission_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','approved','paid') DEFAULT 'pending',
  `paid_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `tourism_description` text DEFAULT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `cover_image` varchar(500) DEFAULT NULL,
  `gallery` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gallery`)),
  `highlights` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`highlights`)),
  `best_time_to_visit` varchar(200) DEFAULT NULL,
  `currency` varchar(50) DEFAULT NULL,
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

INSERT INTO `countries` (`id`, `region_id`, `name`, `slug`, `country_code`, `description`, `tourism_description`, `image_url`, `cover_image`, `gallery`, `highlights`, `best_time_to_visit`, `currency`, `language`, `featured`, `status`, `created_at`, `updated_at`, `visa_requirements`, `climate_info`, `safety_info`, `health_requirements`, `transportation_info`, `cuisine_info`, `cultural_info`, `attractions`, `activities`, `accommodation_info`) VALUES
(1, 6, 'Egypt', 'egypt', 'EGY', 'Land of the Pharaohs with ancient pyramids and the Nile River.', 'Explore ancient wonders including the Pyramids of Giza, Valley of the Kings, and cruise the legendary Nile River.', 'https://images.unsplash.com/photo-1539650116574-75c0c6d73f6e?auto=format&fit=crop&w=800&q=80', NULL, NULL, NULL, 'October to April', 'Egyptian Pound (EGP)', 'Arabic', 1, 'inactive', '2025-10-22 18:58:55', '2025-11-03 20:47:49', 'Most visitors require visa. Visa on arrival available for some nationalities.', 'Desert climate with hot, dry summers and mild winters. Best visited October-April.', 'Generally safe for tourists. Follow standard travel precautions and local guidelines.', 'Standard vaccinations recommended. Consult travel health clinic before departure.', 'Well-developed transport network including airports, roads, and public transport.', 'Traditional Egyptian cuisine featuring ful medames, koshari, molokhia, and fresh seafood.', 'Diverse cultural heritage with warm, welcoming people and unique traditions.', 'Pyramids of Giza, Valley of the Kings, Karnak Temple, Abu Simbel, Nile River cruises.', 'Nile cruises, pyramid tours, desert safaris, Red Sea diving, cultural site visits.', 'Various accommodation options from budget to luxury available.'),
(2, 6, 'Morocco', 'morocco', 'MAR', 'Imperial cities, Sahara Desert, and Atlas Mountains.', 'Discover the magic of Morocco with its bustling souks, stunning architecture, and diverse landscapes.', 'https://images.unsplash.com/photo-1489749798305-4fea3ae436d3?auto=format&fit=crop&w=800&q=80', NULL, NULL, NULL, 'March to May, September to November', 'Moroccan Dirham (MAD)', 'Arabic, French', 1, 'inactive', '2025-10-22 18:58:55', '2025-11-03 20:48:12', 'Visa required for most visitors. Available on arrival or online for many countries.', 'Mediterranean coast, Atlas Mountains, and Sahara Desert create diverse climates.', 'Generally safe for tourists. Follow standard travel precautions and local guidelines.', 'Standard vaccinations recommended. Consult travel health clinic before departure.', 'Well-developed transport network including airports, roads, and public transport.', 'Moroccan tagines, couscous, pastilla, mint tea, and diverse Berber and Arab dishes.', 'Diverse cultural heritage with warm, welcoming people and unique traditions.', 'Marrakech medina, Fez old city, Sahara Desert, Atlas Mountains, Casablanca.', 'Desert camping, Atlas trekking, medina tours, cooking classes, hammam experiences.', 'Various accommodation options from budget to luxury available.'),
(3, 6, 'Tunisia', 'tunisia', 'TUN', 'Mediterranean beaches and ancient Carthage ruins.', 'Experience Tunisia\'s rich history from ancient Carthage to Islamic architecture.', 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?auto=format&fit=crop&w=800&q=80', NULL, NULL, NULL, 'March to May, September to November', 'Tunisian Dinar (TND)', 'Arabic, French', 0, 'active', '2025-10-22 18:58:55', '2025-11-02 15:17:49', 'Visa requirements vary by nationality. Check with embassy or consulate.', 'Diverse climate with seasonal variations. Check weather patterns for your travel dates.', 'Generally safe for tourists. Follow standard travel precautions and local guidelines.', 'Standard vaccinations recommended. Consult travel health clinic before departure.', 'Well-developed transport network including airports, roads, and public transport.', 'Rich culinary traditions featuring local ingredients and traditional cooking methods.', 'Diverse cultural heritage with warm, welcoming people and unique traditions.', 'Numerous natural and cultural attractions suitable for all types of travelers.', 'Wide range of activities from adventure sports to cultural experiences.', 'Various accommodation options from budget to luxury available.'),
(4, 6, 'Nigeria', 'nigeria', 'NGA', 'Most populous African country with vibrant culture.', 'Experience Nigeria\'s diverse cultures, from Nollywood entertainment to traditional festivals.', 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?auto=format&fit=crop&w=800&q=80', NULL, NULL, NULL, 'November to March', 'Nigerian Naira (NGN)', 'English', 1, 'active', '2025-10-22 18:58:55', '2025-11-02 15:17:49', 'Visa requirements vary by nationality. Check with embassy or consulate.', 'Diverse climate with seasonal variations. Check weather patterns for your travel dates.', 'Generally safe for tourists. Follow standard travel precautions and local guidelines.', 'Standard vaccinations recommended. Consult travel health clinic before departure.', 'Well-developed transport network including airports, roads, and public transport.', 'Rich culinary traditions featuring local ingredients and traditional cooking methods.', 'Diverse cultural heritage with warm, welcoming people and unique traditions.', 'Numerous natural and cultural attractions suitable for all types of travelers.', 'Wide range of activities from adventure sports to cultural experiences.', 'Various accommodation options from budget to luxury available.'),
(5, 6, 'Ghana', 'ghana', 'GHA', 'Gateway to West Africa with rich history.', 'Discover Ghana\'s role in African history, from ancient kingdoms to colonial forts.', 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?auto=format&fit=crop&w=800&q=80', NULL, NULL, NULL, 'November to March', 'Ghanaian Cedi (GHS)', 'English', 1, 'inactive', '2025-10-22 18:58:55', '2025-11-03 20:48:01', 'Visa requirements vary by nationality. Check with embassy or consulate.', 'Diverse climate with seasonal variations. Check weather patterns for your travel dates.', 'Generally safe for tourists. Follow standard travel precautions and local guidelines.', 'Standard vaccinations recommended. Consult travel health clinic before departure.', 'Well-developed transport network including airports, roads, and public transport.', 'Rich culinary traditions featuring local ingredients and traditional cooking methods.', 'Diverse cultural heritage with warm, welcoming people and unique traditions.', 'Numerous natural and cultural attractions suitable for all types of travelers.', 'Wide range of activities from adventure sports to cultural experiences.', 'Various accommodation options from budget to luxury available.'),
(6, 6, 'Senegal', 'senegal', 'SEN', 'French colonial heritage and vibrant music scene.', 'Experience Senegal\'s rich musical heritage and colonial architecture in Dakar.', 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?auto=format&fit=crop&w=800&q=80', NULL, NULL, NULL, 'November to May', 'West African CFA Franc (XOF)', 'French', 0, 'active', '2025-10-22 18:58:55', '2025-11-02 15:17:49', 'Visa requirements vary by nationality. Check with embassy or consulate.', 'Diverse climate with seasonal variations. Check weather patterns for your travel dates.', 'Generally safe for tourists. Follow standard travel precautions and local guidelines.', 'Standard vaccinations recommended. Consult travel health clinic before departure.', 'Well-developed transport network including airports, roads, and public transport.', 'Rich culinary traditions featuring local ingredients and traditional cooking methods.', 'Diverse cultural heritage with warm, welcoming people and unique traditions.', 'Numerous natural and cultural attractions suitable for all types of travelers.', 'Wide range of activities from adventure sports to cultural experiences.', 'Various accommodation options from budget to luxury available.'),
(7, 6, 'Kenya', 'kenya', 'KEN', 'Home to the Great Migration and Maasai culture.', 'Witness the spectacular Great Migration in Maasai Mara and experience rich Maasai culture.', 'https://images.unsplash.com/photo-1489392191049-fc10c97e64b6?auto=format&fit=crop&w=800&q=80', NULL, NULL, NULL, 'July to October, December to March', 'Kenyan Shilling (KES)', 'English, Swahili', 1, 'inactive', '2025-10-22 18:58:55', '2025-11-03 20:48:04', 'Visa required for most visitors. Available on arrival for some nationalities.', 'Tropical climate with dry and wet seasons. Best visited June-October and December-March.', 'Generally safe for tourists. Follow standard travel precautions and local guidelines.', 'Standard vaccinations recommended. Consult travel health clinic before departure.', 'Well-developed transport network including airports, roads, and public transport.', 'Nyama choma, ugali, sukuma wiki, pilau rice, and fresh tropical fruits.', 'Diverse cultural heritage with warm, welcoming people and unique traditions.', 'Maasai Mara, Amboseli, Tsavo, Mount Kenya, Lamu Island, Great Rift Valley.', 'Safari game drives, mountain climbing, beach relaxation, cultural village visits.', 'Various accommodation options from budget to luxury available.'),
(8, 6, 'Tanzania', 'tanzania', 'TZA', 'Serengeti, Kilimanjaro, and Zanzibar.', 'Experience the Serengeti\'s wildlife, climb Mount Kilimanjaro, and relax on Zanzibar beaches.', 'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=800&q=80', NULL, NULL, NULL, 'June to October, December to March', 'Tanzanian Shilling (TZS)', 'English, Swahili', 1, 'inactive', '2025-10-22 18:58:55', '2025-11-03 20:48:17', 'Visa required. Available on arrival or online for most nationalities.', 'Tropical climate with dry and wet seasons. Best visited June-October and December-March.', 'Generally safe for tourists. Follow standard travel precautions and local guidelines.', 'Standard vaccinations recommended. Consult travel health clinic before departure.', 'Well-developed transport network including airports, roads, and public transport.', 'Ugali, nyama choma, pilau, fresh seafood, tropical fruits, and Zanzibari spices.', 'Diverse cultural heritage with warm, welcoming people and unique traditions.', 'Serengeti, Ngorongoro Crater, Mount Kilimanjaro, Zanzibar, Tarangire, Lake Manyara.', 'Safari tours, Kilimanjaro climbing, Zanzibar beaches, cultural tours, spice tours.', 'Various accommodation options from budget to luxury available.'),
(9, 6, 'Uganda', 'uganda', 'UGA', 'Pearl of Africa with mountain gorillas.', 'Track mountain gorillas in Bwindi Forest and explore the source of the Nile.', 'https://images.unsplash.com/photo-1547036967-23d11aacaee0?auto=format&fit=crop&w=800&q=80', NULL, NULL, NULL, 'June to August, December to February', 'Ugandan Shilling (UGX)', 'English', 1, 'inactive', '2025-10-22 18:58:55', '2025-11-03 20:48:21', 'Visa requirements vary by nationality. Check with embassy or consulate.', 'Diverse climate with seasonal variations. Check weather patterns for your travel dates.', 'Generally safe for tourists. Follow standard travel precautions and local guidelines.', 'Standard vaccinations recommended. Consult travel health clinic before departure.', 'Well-developed transport network including airports, roads, and public transport.', 'Rich culinary traditions featuring local ingredients and traditional cooking methods.', 'Diverse cultural heritage with warm, welcoming people and unique traditions.', 'Numerous natural and cultural attractions suitable for all types of travelers.', 'Wide range of activities from adventure sports to cultural experiences.', 'Various accommodation options from budget to luxury available.'),
(10, 6, 'Rwanda', 'rwanda', 'RWA', 'Land of a thousand hills and gorillas.', 'Experience Rwanda\'s remarkable recovery and track mountain gorillas.', 'https://images.unsplash.com/photo-1547036967-23d11aacaee0?auto=format&fit=crop&w=800&q=80', NULL, NULL, NULL, 'June to September, December to February', 'Rwandan Franc (RWF)', 'English, French, Kinyarwanda', 1, 'active', '2025-10-22 18:58:55', '2025-11-02 15:17:49', 'Visa requirements vary by nationality. Check with embassy or consulate.', 'Diverse climate with seasonal variations. Check weather patterns for your travel dates.', 'Generally safe for tourists. Follow standard travel precautions and local guidelines.', 'Standard vaccinations recommended. Consult travel health clinic before departure.', 'Well-developed transport network including airports, roads, and public transport.', 'Rich culinary traditions featuring local ingredients and traditional cooking methods.', 'Diverse cultural heritage with warm, welcoming people and unique traditions.', 'Numerous natural and cultural attractions suitable for all types of travelers.', 'Wide range of activities from adventure sports to cultural experiences.', 'Various accommodation options from budget to luxury available.'),
(11, 6, 'Ethiopia', 'ethiopia', 'ETH', 'Cradle of humanity with ancient history.', 'Discover the birthplace of coffee and ancient rock churches of Lalibela.', 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?auto=format&fit=crop&w=800&q=80', NULL, NULL, NULL, 'October to March', 'Ethiopian Birr (ETB)', 'Amharic', 0, 'inactive', '2025-10-22 18:58:55', '2025-11-03 20:47:58', 'Visa requirements vary by nationality. Check with embassy or consulate.', 'Diverse climate with seasonal variations. Check weather patterns for your travel dates.', 'Generally safe for tourists. Follow standard travel precautions and local guidelines.', 'Standard vaccinations recommended. Consult travel health clinic before departure.', 'Well-developed transport network including airports, roads, and public transport.', 'Rich culinary traditions featuring local ingredients and traditional cooking methods.', 'Diverse cultural heritage with warm, welcoming people and unique traditions.', 'Numerous natural and cultural attractions suitable for all types of travelers.', 'Wide range of activities from adventure sports to cultural experiences.', 'Various accommodation options from budget to luxury available.'),
(12, 6, 'Democratic Republic of Congo', 'democratic-republic-of-congo', 'COD', 'Heart of Africa with vast rainforests.', 'Explore the Congo Basin rainforest and encounter unique wildlife including bonobos.', 'https://images.unsplash.com/photo-1547036967-23d11aacaee0?auto=format&fit=crop&w=800&q=80', NULL, NULL, NULL, 'May to September', 'Congolese Franc (CDF)', 'French', 0, 'inactive', '2025-10-22 18:58:55', '2025-11-03 20:47:47', 'Visa requirements vary by nationality. Check with embassy or consulate.', 'Diverse climate with seasonal variations. Check weather patterns for your travel dates.', 'Generally safe for tourists. Follow standard travel precautions and local guidelines.', 'Standard vaccinations recommended. Consult travel health clinic before departure.', 'Well-developed transport network including airports, roads, and public transport.', 'Rich culinary traditions featuring local ingredients and traditional cooking methods.', 'Diverse cultural heritage with warm, welcoming people and unique traditions.', 'Numerous natural and cultural attractions suitable for all types of travelers.', 'Wide range of activities from adventure sports to cultural experiences.', 'Various accommodation options from budget to luxury available.'),
(13, 6, 'Cameroon', 'cameroon', 'CMR', 'Africa in miniature with diverse landscapes.', 'Experience Africa in miniature with diverse landscapes from rainforests to savannas.', 'https://images.unsplash.com/photo-1547036967-23d11aacaee0?auto=format&fit=crop&w=800&q=80', NULL, NULL, NULL, 'November to February', 'Central African CFA Franc (XAF)', 'French, English', 0, 'inactive', '2025-10-22 18:58:55', '2025-11-03 20:47:42', 'Visa requirements vary by nationality. Check with embassy or consulate.', 'Diverse climate with seasonal variations. Check weather patterns for your travel dates.', 'Generally safe for tourists. Follow standard travel precautions and local guidelines.', 'Standard vaccinations recommended. Consult travel health clinic before departure.', 'Well-developed transport network including airports, roads, and public transport.', 'Rich culinary traditions featuring local ingredients and traditional cooking methods.', 'Diverse cultural heritage with warm, welcoming people and unique traditions.', 'Numerous natural and cultural attractions suitable for all types of travelers.', 'Wide range of activities from adventure sports to cultural experiences.', 'Various accommodation options from budget to luxury available.'),
(14, 6, 'South Africa', 'south-africa', 'ZAF', 'Rainbow nation with diverse attractions.', 'Experience Cape Town\'s beauty, Kruger\'s wildlife, and wine regions.', 'https://images.unsplash.com/photo-1484318571209-661cf29a69ea?auto=format&fit=crop&w=800&q=80', NULL, NULL, NULL, 'April to May, September to November', 'South African Rand (ZAR)', 'English, Afrikaans, Zulu', 1, 'active', '2025-10-22 18:58:55', '2025-11-02 15:17:49', 'No visa required for stays up to 90 days for most countries.', 'Mediterranean climate with mild, wet winters and warm, dry summers.', 'Generally safe for tourists. Follow standard travel precautions and local guidelines.', 'Standard vaccinations recommended. Consult travel health clinic before departure.', 'Well-developed transport network including airports, roads, and public transport.', 'Braai (BBQ), bobotie, biltong, boerewors, Cape Malay curry, and world-class wines.', 'Diverse cultural heritage with warm, welcoming people and unique traditions.', 'Table Mountain, Cape Winelands, Kruger National Park, Garden Route, Robben Island.', 'Wine tours, safari drives, shark cage diving, township tours, mountain hiking.', 'Various accommodation options from budget to luxury available.'),
(15, 6, 'Zimbabwe', 'zimbabwe', 'ZWE', 'Victoria Falls and ancient ruins.', 'Marvel at Victoria Falls and explore Great Zimbabwe ruins.', 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?auto=format&fit=crop&w=800&q=80', NULL, NULL, NULL, 'April to October', 'US Dollar (USD)', 'English', 1, 'inactive', '2025-10-22 18:58:55', '2025-11-03 20:48:08', 'Visa requirements vary by nationality. Check with embassy or consulate.', 'Diverse climate with seasonal variations. Check weather patterns for your travel dates.', 'Generally safe for tourists. Follow standard travel precautions and local guidelines.', 'Standard vaccinations recommended. Consult travel health clinic before departure.', 'Well-developed transport network including airports, roads, and public transport.', 'Rich culinary traditions featuring local ingredients and traditional cooking methods.', 'Diverse cultural heritage with warm, welcoming people and unique traditions.', 'Numerous natural and cultural attractions suitable for all types of travelers.', 'Wide range of activities from adventure sports to cultural experiences.', 'Various accommodation options from budget to luxury available.'),
(16, 6, 'Botswana', 'botswana', 'BWA', 'Okavango Delta and pristine wilderness.', 'Discover the Okavango Delta\'s unique ecosystem and excellent wildlife viewing.', 'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=800&q=80', NULL, NULL, NULL, 'May to September', 'Botswana Pula (BWP)', 'English, Setswana', 1, 'inactive', '2025-10-22 18:58:55', '2025-11-03 20:43:52', 'Visa requirements vary by nationality. Check with embassy or consulate.', 'Diverse climate with seasonal variations. Check weather patterns for your travel dates.', 'Generally safe for tourists. Follow standard travel precautions and local guidelines.', 'Standard vaccinations recommended. Consult travel health clinic before departure.', 'Well-developed transport network including airports, roads, and public transport.', 'Rich culinary traditions featuring local ingredients and traditional cooking methods.', 'Diverse cultural heritage with warm, welcoming people and unique traditions.', 'Numerous natural and cultural attractions suitable for all types of travelers.', 'Wide range of activities from adventure sports to cultural experiences.', 'Various accommodation options from budget to luxury available.'),
(17, 6, 'Namibia', 'namibia', 'NAM', 'Sossusvlei dunes and Skeleton Coast.', 'Discover the world\'s highest sand dunes and unique desert-adapted wildlife.', 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?auto=format&fit=crop&w=800&q=80', NULL, NULL, NULL, 'May to October', 'Namibian Dollar (NAD)', 'English', 1, 'inactive', '2025-10-22 18:58:55', '2025-11-03 20:48:10', 'Visa requirements vary by nationality. Check with embassy or consulate.', 'Diverse climate with seasonal variations. Check weather patterns for your travel dates.', 'Generally safe for tourists. Follow standard travel precautions and local guidelines.', 'Standard vaccinations recommended. Consult travel health clinic before departure.', 'Well-developed transport network including airports, roads, and public transport.', 'Rich culinary traditions featuring local ingredients and traditional cooking methods.', 'Diverse cultural heritage with warm, welcoming people and unique traditions.', 'Numerous natural and cultural attractions suitable for all types of travelers.', 'Wide range of activities from adventure sports to cultural experiences.', 'Various accommodation options from budget to luxury available.');

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
-- Table structure for table `regions`
--

CREATE TABLE `regions` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `featured` tinyint(1) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `regions`
--

INSERT INTO `regions` (`id`, `name`, `slug`, `description`, `image_url`, `featured`, `status`, `created_at`, `updated_at`) VALUES
(1, 'North Africa', 'north-africa', 'North Africa offers unparalleled adventure — from the ancient pyramids of Egypt to the bustling souks of Morocco — where every journey is crafted for luxury and authenticity.', 'https://images.unsplash.com/photo-1539650116574-75c0c6d73f6e?auto=format&fit=crop&w=1200&q=80', 1, 'inactive', '2025-10-22 18:58:55', '2025-11-02 10:17:43'),
(2, 'West Africa', 'west-africa', 'West Africa captivates with vibrant cultures, rich history, and warm hospitality — from the golden beaches of Ghana to the musical heritage of Senegal — creating unforgettable memories.', 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?auto=format&fit=crop&w=1200&q=80', 1, 'inactive', '2025-10-22 18:58:55', '2025-11-02 10:17:43'),
(3, 'East Africa', 'east-africa', 'East Africa delivers the ultimate safari experience — from the Great Migration in Kenya to mountain gorillas in Rwanda — where wildlife encounters and pristine landscapes create once-in-a-lifetime adventures.', 'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=1200&q=80', 1, 'inactive', '2025-10-22 18:58:55', '2025-11-02 10:17:43'),
(4, 'Central Africa', 'central-africa', 'Central Africa unveils untouched wilderness and rare wildlife — from the Congo Basin rainforests to unique primate encounters — offering authentic adventures for the intrepid traveler.', 'https://images.unsplash.com/photo-1547036967-23d11aacaee0?auto=format&fit=crop&w=1200&q=80', 0, 'inactive', '2025-10-22 18:58:55', '2025-11-02 10:17:43'),
(5, 'Southern Africa', 'southern-africa', 'Southern Africa combines dramatic landscapes with world-class experiences — from Victoria Falls to Cape Town winelands — where luxury meets adventure in the most developed tourism region.', 'https://images.unsplash.com/photo-1484318571209-661cf29a69ea?auto=format&fit=crop&w=1200&q=80', 1, 'inactive', '2025-10-22 18:58:55', '2025-11-02 10:17:43'),
(6, 'Africa', 'africa', 'Africa offers unparalleled adventure from the savannas of Kenya to the beaches of Zanzibar where every journey is crafted for luxury and authenticity.', 'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=1200&q=80', 1, 'active', '2025-11-02 09:53:59', '2025-11-02 15:08:04'),
(7, 'Europe', 'europe', 'Europe enchants with timeless elegance — from the romantic streets of Paris to the ancient ruins of Rome — where history and culture create unforgettable experiences.', 'https://images.unsplash.com/photo-1467269204594-9661b134dd2b?auto=format&fit=crop&w=1200&q=80', 1, 'active', '2025-11-02 09:53:59', '2025-11-02 09:53:59'),
(8, 'Asia', 'asia', 'Asia mesmerizes with diverse wonders — from the temples of Thailand to the Great Wall of China — where ancient traditions meet modern luxury.', 'https://images.unsplash.com/photo-1480714378408-67cf0d13bc1b?auto=format&fit=crop&w=1200&q=80', 1, 'active', '2025-11-02 09:53:59', '2025-11-02 09:53:59'),
(9, 'North America', 'north-america', 'North America delivers epic adventures — from the Grand Canyon to Niagara Falls — where natural wonders and vibrant cities create memorable journeys.', 'https://images.unsplash.com/photo-1501594907352-04cda38ebc29?auto=format&fit=crop&w=1200&q=80', 1, 'active', '2025-11-02 09:53:59', '2025-11-02 09:53:59'),
(10, 'South America', 'south-america', 'South America captivates with breathtaking landscapes — from Machu Picchu to the Amazon rainforest — where adventure and culture blend perfectly.', 'https://images.unsplash.com/photo-1483729558449-99ef09a8c325?auto=format&fit=crop&w=1200&q=80', 1, 'active', '2025-11-02 09:53:59', '2025-11-02 09:53:59'),
(11, 'Australia & Oceania', 'oceania', 'Oceania amazes with pristine beauty — from the Great Barrier Reef to New Zealand fjords — where nature and adventure create paradise experiences.', 'https://images.unsplash.com/photo-1523482580672-f109ba8cb9be?auto=format&fit=crop&w=1200&q=80', 1, 'inactive', '2025-11-02 09:53:59', '2025-11-02 10:04:28'),
(23, 'Caribbean', 'caribbean', 'Caribbean paradise awaits — from pristine beaches to vibrant cultures — where crystal-clear waters and tropical luxury create unforgettable island escapes.', 'https://images.unsplash.com/photo-1559827260-dc66d52bef19?auto=format&fit=crop&w=1200&q=80', 1, 'active', '2025-11-02 10:04:29', '2025-11-02 10:04:29');

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
  `destination` varchar(255) NOT NULL,
  `destination_country` varchar(100) NOT NULL,
  `country_id` int(11) DEFAULT NULL,
  `category` enum('cultural','adventure','wildlife','city','sports','agro','conference') DEFAULT 'cultural',
  `price` decimal(10,2) NOT NULL,
  `base_price` decimal(10,2) NOT NULL,
  `duration` varchar(50) NOT NULL,
  `duration_days` int(11) NOT NULL,
  `max_participants` int(11) DEFAULT 20,
  `min_participants` int(11) DEFAULT 2,
  `image_url` varchar(500) DEFAULT NULL,
  `images` text DEFAULT NULL,
  `cover_image` varchar(500) DEFAULT NULL,
  `gallery` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gallery`)),
  `itinerary` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`itinerary`)),
  `inclusions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`inclusions`)),
  `exclusions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`exclusions`)),
  `requirements` text DEFAULT NULL,
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

INSERT INTO `tours` (`id`, `name`, `slug`, `description`, `destination`, `destination_country`, `country_id`, `category`, `price`, `base_price`, `duration`, `duration_days`, `max_participants`, `min_participants`, `image_url`, `images`, `cover_image`, `gallery`, `itinerary`, `inclusions`, `exclusions`, `requirements`, `status`, `featured`, `created_by`, `created_at`, `updated_at`, `detailed_description`, `highlights`, `difficulty_level`, `best_time_to_visit`, `what_to_bring`, `cancellation_policy`, `tour_type`, `languages`, `age_restriction`, `physical_requirements`, `accommodation_type`, `meal_plan`, `transportation_details`, `booking_deadline`, `refund_policy`, `emergency_contact`, `tour_guide_info`, `special_offers`, `seasonal_pricing`, `group_discounts`, `addon_services`, `safety_measures`, `insurance_info`, `cultural_notes`, `weather_info`, `local_customs`, `photography_policy`, `sustainability_info`, `accessibility_info`, `tour_tags`, `meta_title`, `meta_description`, `share_url`, `advisor_commission_rate`, `mca_commission_rate`, `booking_count`, `average_rating`, `review_count`, `last_booked_date`, `popularity_score`, `video_url`, `virtual_tour_url`, `brochure_url`, `terms_conditions`) VALUES
(1, 'Maasai Mara Safari Adventure', 'maasai-mara-safari-adventure', 'Experience the Great Migration and Big Five in Kenya\'s most famous game reserve.', 'Maasai Mara', 'Kenya', 6, 'wildlife', 2899.00, 2899.00, '7 days', 7, 12, 2, 'https://images.unsplash.com/photo-1489392191049-fc10c97e64b6?auto=format&fit=crop&w=800&q=80', '[]', 'https://images.unsplash.com/photo-1489392191049-fc10c97e64b6?auto=format&fit=crop&w=1200&q=80', NULL, '[{\"day\":1,\"title\":\"Arrival in Nairobi\",\"activities\":\"Airport pickup, city tour\"},{\"day\":2,\"title\":\"Nairobi to Maasai Mara\",\"activities\":\"Drive to Maasai Mara, afternoon game drive\"},{\"day\":3,\"title\":\"Full Day Game Drive\",\"activities\":\"Morning and afternoon game drives\"}]', '[\"Safari lodges\",\"All meals\",\"Professional guide\",\"Game drives\",\"Park fees\"]', '[\"International flights\",\"Visa fees\",\"Personal expenses\",\"Tips\"]', 'Valid passport, Yellow fever certificate', 'active', 1, 1, '2025-10-22 18:58:55', '2025-10-23 10:06:00', 'Experience the Great Migration and Big Five in Kenya\'s most famous game reserve.\n\nThis comprehensive tour offers an immersive experience into the heart of Africa. Our expert guides will take you on a journey through stunning landscapes, rich cultures, and unforgettable wildlife encounters. Every detail has been carefully planned to ensure your comfort and safety while maximizing your adventure.', '[\"Expert local guides\", \"Small group experience\", \"Authentic cultural interactions\", \"Professional photography opportunities\", \"Comfortable accommodations\"]', 'moderate', 'June to October', 'Comfortable walking shoes, sun hat, sunscreen, camera, light jacket for evenings, personal medications, passport copy', NULL, 'group', '[\"English\", \"French\", \"Local language\"]', '12+ years (children must be accompanied by adults)', NULL, 'Luxury lodges and hotels', 'Full board (breakfast, lunch, dinner)', NULL, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '[\"wildlife\", \"Kenya\", \"7-day\"]', 'Maasai Mara Safari Adventure - Kenya Tour | iForYoungTours', 'Experience Maasai Mara Safari Adventure in Kenya. Experience the Great Migration and Big Five in Kenya\'s most famous game reserve....', 'https://foreveryoungtours.com/tour/maasai-mara-safari-adventure?ref=', 10.00, 5.00, 0, 0.00, 0, NULL, 0, NULL, NULL, NULL, NULL),
(2, 'Serengeti Migration Safari', 'serengeti-migration-safari', 'Witness the greatest wildlife spectacle on Earth in Tanzania\'s Serengeti.', 'Serengeti', 'Tanzania', 7, 'wildlife', 3299.00, 3299.00, '8 days', 8, 10, 2, 'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=800&q=80', '[]', 'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=1200&q=80', NULL, '[{\"day\":1,\"title\":\"Arrival in Arusha\",\"activities\":\"Airport pickup, briefing\"},{\"day\":2,\"title\":\"Arusha to Serengeti\",\"activities\":\"Drive to Serengeti\"},{\"day\":3,\"title\":\"Central Serengeti\",\"activities\":\"Full day game drives\"}]', '[\"Luxury safari lodges\",\"All meals\",\"Professional guide\",\"Game drives\",\"Park fees\"]', '[\"International flights\",\"Visa fees\",\"Personal expenses\",\"Tips\"]', 'Valid passport, Yellow fever certificate', 'active', 1, 1, '2025-10-22 18:58:55', '2025-10-26 18:49:31', 'Witness the greatest wildlife spectacle on Earth in Tanzania\'s Serengeti.\n\nThis comprehensive tour offers an immersive experience into the heart of Africa. Our expert guides will take you on a journey through stunning landscapes, rich cultures, and unforgettable wildlife encounters. Every detail has been carefully planned to ensure your comfort and safety while maximizing your adventure.', '[\"Expert local guides\", \"Small group experience\", \"Authentic cultural interactions\", \"Professional photography opportunities\", \"Comfortable accommodations\"]', 'moderate', 'June to October', 'Comfortable walking shoes, sun hat, sunscreen, camera, light jacket for evenings, personal medications, passport copy', NULL, 'group', '[\"English\", \"French\", \"Local language\"]', '12+ years (children must be accompanied by adults)', NULL, 'Luxury lodges and hotels', 'Full board (breakfast, lunch, dinner)', NULL, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '[\"wildlife\", \"Tanzania\", \"8-day\"]', 'Serengeti Migration Safari - Tanzania Tour | iForYoungTours', 'Experience Serengeti Migration Safari in Tanzania. Witness the greatest wildlife spectacle on Earth in Tanzania\'s Serengeti....', 'https://foreveryoungtours.com/tour/serengeti-migration-safari?ref=', 10.00, 5.00, 1, 0.00, 0, '2025-10-26 18:49:31', 1, NULL, NULL, NULL, NULL),
(3, 'Imperial Cities of Morocco', 'imperial-cities-of-morocco', 'Explore the four imperial cities: Marrakech, Fez, Meknes, and Rabat.', 'Marrakech', 'Morocco', 2, 'cultural', 1799.00, 1799.00, '10 days', 10, 16, 2, 'uploads/tours/3_main_1761216861_5938.jpg', '[\"uploads\\/tours\\/3_main_1761216861_5938.jpg\",\"uploads\\/tours\\/3_cover_1761216861_5058.jpg\",\"uploads\\/tours\\/3_gallery_0_1761216861_8271.jpg\",\"uploads\\/tours\\/3_gallery_1_1761216861_8649.jpg\",\"uploads\\/tours\\/3_gallery_2_1761216861_8507.jpg\",\"uploads\\/tours\\/3_gallery_3_1761216861_3298.jpg\",\"uploads\\/tours\\/3_gallery_4_1761216861_1408.jpg\"]', 'uploads/tours/3_cover_1761216861_5058.jpg', '[\"uploads\\/tours\\/3_gallery_0_1761216861_8271.jpg\",\"uploads\\/tours\\/3_gallery_1_1761216861_8649.jpg\",\"uploads\\/tours\\/3_gallery_2_1761216861_8507.jpg\",\"uploads\\/tours\\/3_gallery_3_1761216861_3298.jpg\",\"uploads\\/tours\\/3_gallery_4_1761216861_1408.jpg\"]', '[{\"day\":\"1\",\"title\":\"\",\"activities\":\"\"}]', '[\"Accommodation in riads\\r\",\"Daily breakfast\\r\",\"Professional guide\\r\",\"Transportation\"]', '[\"Lunches and dinners\\r\",\"Personal expenses\\r\",\"Tips\"]', '', 'active', 1, 1, '2025-10-22 18:58:55', '2025-10-23 10:54:21', 'Explore the four imperial cities: Marrakech, Fez, Meknes, and Rabat.\r\n\r\nThis comprehensive tour offers an immersive experience into the heart of Africa. Our expert guides will take you on a journey through stunning landscapes, rich cultures, and unforgettable wildlife encounters. Every detail has been carefully planned to ensure your comfort and safety while maximizing your adventure.', '[\"Expert local guides\\r\",\"Small group experience\\r\",\"Authentic cultural interactions\\r\",\"Professional photography opportunities\\r\",\"Comfortable accommodations\"]', 'easy', 'October to April', 'Comfortable walking shoes, sun hat, sunscreen, camera, light jacket for evenings, personal medications, passport copy', NULL, 'group', '[\"English\", \"French\", \"Local language\"]', '12+ years (children must be accompanied by adults)', NULL, 'Mid-range hotels and lodges', 'Full board (breakfast, lunch, dinner)', NULL, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '[\"cultural\", \"Morocco\", \"10-day\"]', 'Imperial Cities of Morocco - Morocco Tour | iForYoungTours', 'Experience Imperial Cities of Morocco in Morocco. Explore the four imperial cities: Marrakech, Fez, Meknes, and Rabat....', 'https://foreveryoungtours.com/tour/imperial-cities-of-morocco?ref=', 10.00, 5.00, 0, 0.00, 0, NULL, 0, NULL, NULL, NULL, NULL),
(4, 'Cape Town and Wine Country', 'cape-town-and-wine-country', 'Explore the Mother City and world-renowned wine regions.', 'Cape Town', 'Cameroon', 13, 'city', 1599.00, 1599.00, '7 days', 7, 14, 2, 'uploads/tours/4_main_1761214761_6431.jpg', '[\"uploads\\/tours\\/4_main_1761214761_6431.jpg\",\"uploads\\/tours\\/4_cover_1761214761_9247.jpg\",\"uploads\\/tours\\/4_gallery_0_1761214761_7098.jpg\",\"uploads\\/tours\\/4_gallery_1_1761214761_2892.jpg\",\"uploads\\/tours\\/4_gallery_2_1761214761_2543.jpg\",\"uploads\\/tours\\/4_gallery_3_1761214761_7143.jpg\"]', 'uploads/tours/4_cover_1761214761_9247.jpg', '[\"uploads\\/tours\\/4_gallery_0_1761214459_2628.jpg\",\"uploads\\/tours\\/4_gallery_1_1761214459_9816.jpg\",\"uploads\\/tours\\/4_gallery_2_1761214459_7160.jpg\",\"uploads\\/tours\\/4_gallery_3_1761214459_2970.jpg\",\"uploads\\/tours\\/4_gallery_0_1761214761_7098.jpg\",\"uploads\\/tours\\/4_gallery_1_1761214761_2892.jpg\",\"uploads\\/tours\\/4_gallery_2_1761214761_2543.jpg\",\"uploads\\/tours\\/4_gallery_3_1761214761_7143.jpg\"]', '[{\"day\":\"1\",\"title\":\"\",\"activities\":\"\"}]', '[\"Boutique accommodation\\r\",\"Daily breakfast\\r\",\"Professional guide\\r\",\"Wine tastings\"]', '[\"Lunches and dinners\\r\",\"Personal expenses\\r\",\"Tips\"]', '', 'active', 1, 1, '2025-10-22 18:58:55', '2025-10-23 10:19:21', 'Explore the Mother City and world-renowned wine regions.\r\n\r\nThis comprehensive tour offers an immersive experience into the heart of Africa. Our expert guides will take you on a journey through stunning landscapes, rich cultures, and unforgettable wildlife encounters. Every detail has been carefully planned to ensure your comfort and safety while maximizing your adventure.', '[\"Expert local guides\\r\",\"Small group experience\\r\",\"Authentic cultural interactions\\r\",\"Professional photography opportunities\\r\",\"Comfortable accommodations\"]', 'moderate', 'Year-round', 'Comfortable walking shoes, sun hat, sunscreen, camera, light jacket for evenings, personal medications, passport copy', NULL, 'group', '[\"English\", \"French\", \"Local language\"]', '12+ years (children must be accompanied by adults)', NULL, 'Mid-range hotels and lodges', 'Full board (breakfast, lunch, dinner)', NULL, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '[\"city\", \"South Africa\", \"7-day\"]', 'Cape Town and Wine Country - South Africa Tour | iForYoungTours', 'Experience Cape Town and Wine Country in South Africa. Explore the Mother City and world-renowned wine regions....', 'https://foreveryoungtours.com/tour/cape-town-and-wine-country?ref=', 10.00, 5.00, 0, 0.00, 0, NULL, 0, NULL, NULL, NULL, NULL),
(5, 'Gorilla Trekking Adventure', 'gorilla-trekking-adventure', 'Track mountain gorillas in Bwindi Impenetrable Forest.', 'Bwindi', 'Uganda', 8, 'wildlife', 2299.00, 2299.00, '5 days', 5, 8, 2, 'https://images.unsplash.com/photo-1547036967-23d11aacaee0?auto=format&fit=crop&w=800&q=80', '[]', 'https://images.unsplash.com/photo-1547036967-23d11aacaee0?auto=format&fit=crop&w=1200&q=80', NULL, '[{\"day\":1,\"title\":\"Arrival in Entebbe\",\"activities\":\"Airport pickup\"},{\"day\":2,\"title\":\"Entebbe to Bwindi\",\"activities\":\"Scenic drive\"},{\"day\":3,\"title\":\"Gorilla Trekking\",\"activities\":\"Gorilla tracking experience\"}]', '[\"Accommodation\",\"All meals\",\"Gorilla permits\",\"Professional guide\"]', '[\"International flights\",\"Visa fees\",\"Personal expenses\",\"Tips\"]', 'Valid passport, Yellow fever certificate', 'active', 1, 1, '2025-10-22 18:58:55', '2025-10-23 10:06:00', 'Track mountain gorillas in Bwindi Impenetrable Forest.\n\nThis comprehensive tour offers an immersive experience into the heart of Africa. Our expert guides will take you on a journey through stunning landscapes, rich cultures, and unforgettable wildlife encounters. Every detail has been carefully planned to ensure your comfort and safety while maximizing your adventure.', '[\"Expert local guides\", \"Small group experience\", \"Authentic cultural interactions\", \"Professional photography opportunities\", \"Comfortable accommodations\"]', 'moderate', 'June to October', 'Comfortable walking shoes, sun hat, sunscreen, camera, light jacket for evenings, personal medications, passport copy', NULL, 'group', '[\"English\", \"French\", \"Local language\"]', '12+ years (children must be accompanied by adults)', NULL, 'Luxury lodges and hotels', 'Full board (breakfast, lunch, dinner)', NULL, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '[\"wildlife\", \"Uganda\", \"5-day\"]', 'Gorilla Trekking Adventure - Uganda Tour | iForYoungTours', 'Experience Gorilla Trekking Adventure in Uganda. Track mountain gorillas in Bwindi Impenetrable Forest....', 'https://foreveryoungtours.com/tour/gorilla-trekking-adventure?ref=', 10.00, 5.00, 0, 0.00, 0, NULL, 0, NULL, NULL, NULL, NULL),
(6, 'Coffee Farm Experience Kenya', 'coffee-farm-experience-kenya', 'Learn about coffee production from bean to cup in Kenya\'s highlands.', 'Nyeri', 'Kenya', 6, 'agro', 799.00, 799.00, '3 days', 3, 12, 2, 'https://images.unsplash.com/photo-1447933601403-0c6688de566e?auto=format&fit=crop&w=800&q=80', '[]', 'https://images.unsplash.com/photo-1447933601403-0c6688de566e?auto=format&fit=crop&w=1200&q=80', NULL, '[{\"day\":1,\"title\":\"Arrival at Coffee Farm\",\"activities\":\"Farm tour, processing demo\"},{\"day\":2,\"title\":\"Hands-on Experience\",\"activities\":\"Picking, processing coffee\"},{\"day\":3,\"title\":\"Tasting and Departure\",\"activities\":\"Cupping session\"}]', '[\"Farm accommodation\",\"All meals\",\"Coffee experiences\",\"Professional guide\"]', '[\"Personal expenses\",\"Tips\",\"Additional purchases\"]', 'Interest in agriculture', 'active', 0, 1, '2025-10-22 18:58:55', '2025-10-23 10:06:00', 'Learn about coffee production from bean to cup in Kenya\'s highlands.\n\nThis comprehensive tour offers an immersive experience into the heart of Africa. Our expert guides will take you on a journey through stunning landscapes, rich cultures, and unforgettable wildlife encounters. Every detail has been carefully planned to ensure your comfort and safety while maximizing your adventure.', '[\"Expert local guides\", \"Small group experience\", \"Authentic cultural interactions\", \"Professional photography opportunities\", \"Comfortable accommodations\"]', 'moderate', 'June to October', 'Comfortable walking shoes, sun hat, sunscreen, camera, light jacket for evenings, personal medications, passport copy', NULL, 'group', '[\"English\", \"French\", \"Local language\"]', '12+ years (children must be accompanied by adults)', NULL, 'Comfortable guesthouses and camps', 'Full board (breakfast, lunch, dinner)', NULL, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '[\"agro\", \"Kenya\", \"3-day\"]', 'Coffee Farm Experience Kenya - Kenya Tour | iForYoungTours', 'Experience Coffee Farm Experience Kenya in Kenya. Learn about coffee production from bean to cup in Kenya\'s highlands....', 'https://foreveryoungtours.com/tour/coffee-farm-experience-kenya?ref=', 10.00, 5.00, 0, 0.00, 0, NULL, 0, NULL, NULL, NULL, NULL),
(7, 'Kilimanjaro Marathon', 'kilimanjaro-marathon', 'Run the Kilimanjaro Marathon with stunning mountain views.', 'Moshi', 'Tanzania', 7, 'sports', 1499.00, 1499.00, '5 days', 5, 20, 2, 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?auto=format&fit=crop&w=800&q=80', '[]', 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?auto=format&fit=crop&w=1200&q=80', NULL, '[{\"day\":1,\"title\":\"Arrival in Moshi\",\"activities\":\"Registration, race briefing\"},{\"day\":2,\"title\":\"Training Run\",\"activities\":\"Acclimatization run\"},{\"day\":3,\"title\":\"Marathon Day\",\"activities\":\"42km race\"}]', '[\"Accommodation\",\"All meals\",\"Race registration\",\"Professional support\"]', '[\"International flights\",\"Personal expenses\",\"Tips\"]', 'Marathon experience, Medical clearance', 'active', 0, 1, '2025-10-22 18:58:55', '2025-10-23 10:06:00', 'Run the Kilimanjaro Marathon with stunning mountain views.\n\nThis comprehensive tour offers an immersive experience into the heart of Africa. Our expert guides will take you on a journey through stunning landscapes, rich cultures, and unforgettable wildlife encounters. Every detail has been carefully planned to ensure your comfort and safety while maximizing your adventure.', '[\"Expert local guides\", \"Small group experience\", \"Authentic cultural interactions\", \"Professional photography opportunities\", \"Comfortable accommodations\"]', 'moderate', 'June to October', 'Comfortable walking shoes, sun hat, sunscreen, camera, light jacket for evenings, personal medications, passport copy', NULL, 'group', '[\"English\", \"French\", \"Local language\"]', '12+ years (children must be accompanied by adults)', NULL, 'Mid-range hotels and lodges', 'Full board (breakfast, lunch, dinner)', NULL, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '[\"sports\", \"Tanzania\", \"5-day\"]', 'Kilimanjaro Marathon - Tanzania Tour | iForYoungTours', 'Experience Kilimanjaro Marathon in Tanzania. Run the Kilimanjaro Marathon with stunning mountain views....', 'https://foreveryoungtours.com/tour/kilimanjaro-marathon?ref=', 10.00, 5.00, 0, 0.00, 0, NULL, 0, NULL, NULL, NULL, NULL),
(8, 'African Business Summit', 'african-business-summit', 'Attend premier business conference while exploring Cape Town.', 'Cape Town', 'South Africa', 13, 'conference', 2299.00, 2299.00, '5 days', 5, 50, 2, 'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=800&q=80', '[]', 'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=1200&q=80', NULL, '[{\"day\":1,\"title\":\"Arrival and Registration\",\"activities\":\"Conference registration\"},{\"day\":2,\"title\":\"Summit Day 1\",\"activities\":\"Keynote speeches\"},{\"day\":3,\"title\":\"Summit Day 2\",\"activities\":\"Panel discussions\"}]', '[\"Conference accommodation\",\"Conference registration\",\"Welcome reception\"]', '[\"Most meals\",\"Personal expenses\",\"Tips\"]', 'Business professional, Valid passport', 'active', 0, 1, '2025-10-22 18:58:55', '2025-10-23 10:06:00', 'Attend premier business conference while exploring Cape Town.\n\nThis comprehensive tour offers an immersive experience into the heart of Africa. Our expert guides will take you on a journey through stunning landscapes, rich cultures, and unforgettable wildlife encounters. Every detail has been carefully planned to ensure your comfort and safety while maximizing your adventure.', '[\"Expert local guides\", \"Small group experience\", \"Authentic cultural interactions\", \"Professional photography opportunities\", \"Comfortable accommodations\"]', 'moderate', 'Year-round', 'Comfortable walking shoes, sun hat, sunscreen, camera, light jacket for evenings, personal medications, passport copy', NULL, 'group', '[\"English\", \"French\", \"Local language\"]', '12+ years (children must be accompanied by adults)', NULL, 'Luxury lodges and hotels', 'Full board (breakfast, lunch, dinner)', NULL, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '[\"conference\", \"South Africa\", \"5-day\"]', 'African Business Summit - South Africa Tour | iForYoungTours', 'Experience African Business Summit in South Africa. Attend premier business conference while exploring Cape Town....', 'https://foreveryoungtours.com/tour/african-business-summit?ref=', 10.00, 5.00, 0, 0.00, 0, NULL, 0, NULL, NULL, NULL, NULL),
(9, 'Golden Monkey Tracking', 'golden-monkey-tracking', 'Track endangered golden monkeys in their natural bamboo forest habitat', 'Musanze', 'Rwanda', 10, 'wildlife', 2040.00, 2040.00, '7 days', 7, 45, 10, 'https://kimi-web-img.moonshot.cn/img/c8.alamy.com/1fc8e7a4a49108a8de5eb1b815f57bbc2eae90e6.jpg', '[]', 'https://kimi-web-img.moonshot.cn/img/c8.alamy.com/1fc8e7a4a49108a8de5eb1b815f57bbc2eae90e6.jpg', '[\"https:\\/\\/kimi-web-img.moonshot.cn\\/img\\/c8.alamy.com\\/1fc8e7a4a49108a8de5eb1b815f57bbc2eae90e6.jpg\\r\",\"https:\\/\\/kimi-web-img.moonshot.cn\\/img\\/www.passportandpixels.com\\/a89fbe0793079ee0d94fe06caceeeebfbf2e2d51.jpg\\r\",\"https:\\/\\/kimi-web-img.moonshot.cn\\/img\\/media-cdn.tripadvisor.com\\/ca773a577d2002b03e34b2478d8ae7aa1a932f84.jpg\"]', '[{\"day\":\"1\",\"title\":\"Day 1:Arrival in Kigali\",\"activities\":\"Arrive at Kigali International Airport where you\'ll be met by our representative. Transfer to your hotel for overnight stay and welcome dinner.\\r\\n\\r\\n\\ud83c\\udfe8 Hotel des Mille Collines\\r\\n\\ud83c\\udf7d\\ufe0f Welcome Dinner\"},{\"day\":\"2\",\"title\":\"Day2 :Transfer to Volcanoes National Park\",\"activities\":\"After breakfast, drive to Volcanoes National Park. En route, visit the Kigali Genocide Memorial. Check into your lodge near the park.\\r\\n\\r\\n\\ud83c\\udfe8 Mountain Gorilla View Lodge\\r\\n\\ud83d\\ude97 2.5 hour drive\"},{\"day\":\"3\",\"title\":\"Day3 :First Gorilla Trek\",\"activities\":\"Early morning briefing at park headquarters. Begin your first gorilla trek with expert guides. Spend magical hour with gorilla family.\\r\\n\\r\\n\\ud83e\\udd8d Gorilla Trekking\\r\\n\\u23f0 1 hour with gorillas\"}]', '[\"Accommodation\\r\",\"meals\\r\",\"Tour guiding\"]', '[\"International flights\\r\",\"Visa fees\"]', 'Valid Passport\r\nTraveling jackets\r\nYellow fever fitness', 'active', 0, 1, '2025-10-22 23:19:46', '2025-10-23 12:14:23', 'Track endangered golden monkeys in their natural bamboo forest habitat\n\nThis comprehensive tour offers an immersive experience into the heart of Africa. Our expert guides will take you on a journey through stunning landscapes, rich cultures, and unforgettable wildlife encounters. Every detail has been carefully planned to ensure your comfort and safety while maximizing your adventure.', '[\"Expert local guides\", \"Small group experience\", \"Authentic cultural interactions\", \"Professional photography opportunities\", \"Comfortable accommodations\"]', 'moderate', 'June to October', 'Comfortable walking shoes, sun hat, sunscreen, camera, light jacket for evenings, personal medications, passport copy', NULL, 'group', '[\"English\", \"French\", \"Local language\"]', '12+ years (children must be accompanied by adults)', NULL, 'Luxury lodges and hotels', 'Full board (breakfast, lunch, dinner)', NULL, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '[\"wildlife\", \"Rwanda\", \"7-day\"]', 'Golden Monkey Tracking - Rwanda Tour | iForYoungTours', 'Experience Golden Monkey Tracking in Rwanda. Track endangered golden monkeys in their natural bamboo forest habitat...', 'https://foreveryoungtours.com/tour/golden-monkey-tracking?ref=', 10.00, 5.00, 1, 0.00, 0, '2025-10-23 12:14:23', 1, NULL, NULL, NULL, NULL),
(11, 'Golden Monkey Tracking', 'golden-monkey-tracking-1', 'on a memorable 4-day tour in Rwanda that will offer you a unique adventure and wildlife experience. featuring canoeing in Mukungwa River', 'Musanze', 'Rwanda', 10, 'wildlife', 3500.00, 3500.00, '6 days', 6, 33, 10, 'uploads/tours/11_main_1762208655_3014.jpg', '[\"uploads\\/tours\\/11_main_1762208655_3014.jpg\",\"uploads\\/tours\\/11_cover_1762208655_2404.jpeg\",\"uploads\\/tours\\/11_gallery_0_1762208655_5151.png\"]', 'uploads/tours/11_cover_1762208655_2404.jpeg', '[\"uploads\\/tours\\/11_gallery_0_1761212403_6152.jpg\",\"uploads\\/tours\\/11_gallery_1_1761212403_1254.jpg\",\"uploads\\/tours\\/11_gallery_2_1761212403_1018.jpg\",\"uploads\\/tours\\/11_gallery_3_1761212403_9840.jpg\",\"uploads\\/tours\\/11_gallery_0_1762208655_5151.png\"]', '[{\"day\":\"1\",\"title\":\"\",\"activities\":\"\"}]', '[\"Accomodation\\r\",\"Meals\\r\",\"Guides\"]', '[\"Internationa flights\\r\",\"Visa Fees\"]', '', 'inactive', 0, 1, '2025-10-23 09:40:03', '2025-11-03 22:45:42', 'on a memorable 4-day tour in Rwanda that will offer you a unique adventure and wildlife experience. featuring canoeing in Mukungwa River\r\n\r\nThis comprehensive tour offers an immersive experience into the heart of Africa. Our expert guides will take you on a journey through stunning landscapes, rich cultures, and unforgettable wildlife encounters. Every detail has been carefully planned to ensure your comfort and safety while maximizing your adventure.', '[\"Expert local guides\\r\",\"Small group experience\\r\",\"Authentic cultural interactions\\r\",\"Professional photography opportunities\\r\",\"Comfortable accommodations\"]', 'moderate', 'June to October', 'Comfortable walking shoes, sun hat, sunscreen, camera, light jacket for evenings, personal medications, passport copy', NULL, 'group', '[\"English\", \"French\", \"Local language\"]', '12+ years (children must be accompanied by adults)', NULL, 'Luxury lodges and hotels', 'Full board (breakfast, lunch, dinner)', NULL, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '[\"wildlife\", \"Rwanda\", \"6-day\"]', 'Golden Monkey Tracking - Rwanda Tour | iForYoungTours', 'Experience Golden Monkey Tracking in Rwanda. on a memorable 4-day tour in Rwanda that will offer you a unique adventure and wildlife experience. featuring canoeing i...', 'https://foreveryoungtours.com/tour/golden-monkey-tracking-1?ref=', 10.00, 5.00, 0, 0.00, 0, NULL, 0, NULL, NULL, NULL, NULL),
(12, 'Akagera Natinal PArk', 'akagera-natinal-park', 'Travellers come from far and wide to catch a glimpse of the magnificent gorillas, yet there is so much more to see and experience.', 'Southern of Rwanda', 'Rwanda', 10, 'wildlife', 4000.00, 4000.00, '4 days', 4, 16, 25, 'uploads/tours/12_main_1762211160_7332.jpeg', '[\"uploads\\/tours\\/12_main_1762211160_7332.jpeg\",\"uploads\\/tours\\/12_cover_1762211160_2944.png\",\"uploads\\/tours\\/12_gallery_0_1762211160_5773.jpeg\",\"uploads\\/tours\\/12_gallery_1_1762211160_9756.jpeg\",\"uploads\\/tours\\/12_gallery_2_1762211160_4366.jpeg\",\"uploads\\/tours\\/12_gallery_3_1762211160_6287.jpeg\",\"uploads\\/tours\\/12_gallery_4_1762211160_9955.jpg\"]', 'uploads/tours/12_cover_1762211160_2944.png', '[\"uploads\\/tours\\/12_gallery_0_1762211160_5773.jpeg\",\"uploads\\/tours\\/12_gallery_1_1762211160_9756.jpeg\",\"uploads\\/tours\\/12_gallery_2_1762211160_4366.jpeg\",\"uploads\\/tours\\/12_gallery_3_1762211160_6287.jpeg\",\"uploads\\/tours\\/12_gallery_4_1762211160_9955.jpg\"]', '[{\"day\":\"1\",\"title\":\"\",\"activities\":\"\"}]', '[]', '[]', '', 'active', 0, 1, '2025-11-03 22:49:33', '2025-11-03 23:06:00', 'Travellers come from far and wide to catch a glimpse of the magnificent gorillas, yet there is so much more to see and experience.Travellers come from far and wide to catch a glimpse of the magnificent gorillas, yet there is so much more to see and experience.Travellers come from far and wide to catch a glimpse of the magnificent gorillas, yet there is so much more to see and experience.Travellers come from far and wide to catch a glimpse of the magnificent gorillas, yet there is so much more to see and experience.', '[]', 'challenging', 'November to February', NULL, NULL, 'group', NULL, NULL, NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 10.00, 5.00, 0, 0.00, 0, NULL, 0, NULL, NULL, NULL, NULL);

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

--
-- Dumping data for table `tour_faqs`
--

INSERT INTO `tour_faqs` (`id`, `tour_id`, `question`, `answer`, `sort_order`, `is_active`, `created_at`) VALUES
(1, 3, 'What is included in the tour price?', 'The tour price includes accommodation, meals as specified, professional guide, transportation during the tour, and entrance fees to attractions mentioned in the itinerary.', 1, 1, '2025-10-22 23:51:33'),
(2, 1, 'What is included in the tour price?', 'The tour price includes accommodation, meals as specified, professional guide, transportation during the tour, and entrance fees to attractions mentioned in the itinerary.', 1, 1, '2025-10-22 23:51:33'),
(3, 2, 'What is included in the tour price?', 'The tour price includes accommodation, meals as specified, professional guide, transportation during the tour, and entrance fees to attractions mentioned in the itinerary.', 1, 1, '2025-10-22 23:51:33'),
(4, 5, 'What is included in the tour price?', 'The tour price includes accommodation, meals as specified, professional guide, transportation during the tour, and entrance fees to attractions mentioned in the itinerary.', 1, 1, '2025-10-22 23:51:33'),
(5, 4, 'What is included in the tour price?', 'The tour price includes accommodation, meals as specified, professional guide, transportation during the tour, and entrance fees to attractions mentioned in the itinerary.', 1, 1, '2025-10-22 23:51:33'),
(8, 3, 'What should I bring on this tour?', 'Please bring comfortable walking shoes, sun protection, camera, light jacket for evenings, personal medications, and a copy of your passport.', 2, 1, '2025-10-22 23:51:33'),
(9, 1, 'What should I bring on this tour?', 'Please bring comfortable walking shoes, sun protection, camera, light jacket for evenings, personal medications, and a copy of your passport.', 2, 1, '2025-10-22 23:51:33'),
(10, 2, 'What should I bring on this tour?', 'Please bring comfortable walking shoes, sun protection, camera, light jacket for evenings, personal medications, and a copy of your passport.', 2, 1, '2025-10-22 23:51:33'),
(11, 5, 'What should I bring on this tour?', 'Please bring comfortable walking shoes, sun protection, camera, light jacket for evenings, personal medications, and a copy of your passport.', 2, 1, '2025-10-22 23:51:33'),
(12, 4, 'What should I bring on this tour?', 'Please bring comfortable walking shoes, sun protection, camera, light jacket for evenings, personal medications, and a copy of your passport.', 2, 1, '2025-10-22 23:51:33'),
(15, 3, 'Is this tour suitable for children?', 'This tour is suitable for children 12 years and above. Children must be accompanied by adults at all times.', 3, 1, '2025-10-22 23:51:33'),
(16, 1, 'Is this tour suitable for children?', 'This tour is suitable for children 12 years and above. Children must be accompanied by adults at all times.', 3, 1, '2025-10-22 23:51:33'),
(17, 2, 'Is this tour suitable for children?', 'This tour is suitable for children 12 years and above. Children must be accompanied by adults at all times.', 3, 1, '2025-10-22 23:51:33'),
(18, 5, 'Is this tour suitable for children?', 'This tour is suitable for children 12 years and above. Children must be accompanied by adults at all times.', 3, 1, '2025-10-22 23:51:33'),
(19, 4, 'Is this tour suitable for children?', 'This tour is suitable for children 12 years and above. Children must be accompanied by adults at all times.', 3, 1, '2025-10-22 23:51:33'),
(22, 3, 'What is included in the tour price?', 'The tour price includes accommodation, meals as specified, professional guide, transportation during the tour, and entrance fees to attractions mentioned in the itinerary.', 1, 1, '2025-10-23 10:05:11'),
(23, 1, 'What is included in the tour price?', 'The tour price includes accommodation, meals as specified, professional guide, transportation during the tour, and entrance fees to attractions mentioned in the itinerary.', 1, 1, '2025-10-23 10:05:11'),
(24, 2, 'What is included in the tour price?', 'The tour price includes accommodation, meals as specified, professional guide, transportation during the tour, and entrance fees to attractions mentioned in the itinerary.', 1, 1, '2025-10-23 10:05:11'),
(25, 5, 'What is included in the tour price?', 'The tour price includes accommodation, meals as specified, professional guide, transportation during the tour, and entrance fees to attractions mentioned in the itinerary.', 1, 1, '2025-10-23 10:05:11'),
(26, 4, 'What is included in the tour price?', 'The tour price includes accommodation, meals as specified, professional guide, transportation during the tour, and entrance fees to attractions mentioned in the itinerary.', 1, 1, '2025-10-23 10:05:11'),
(29, 3, 'What should I bring on this tour?', 'Please bring comfortable walking shoes, sun protection, camera, light jacket for evenings, personal medications, and a copy of your passport.', 2, 1, '2025-10-23 10:05:11'),
(30, 1, 'What should I bring on this tour?', 'Please bring comfortable walking shoes, sun protection, camera, light jacket for evenings, personal medications, and a copy of your passport.', 2, 1, '2025-10-23 10:05:11'),
(31, 2, 'What should I bring on this tour?', 'Please bring comfortable walking shoes, sun protection, camera, light jacket for evenings, personal medications, and a copy of your passport.', 2, 1, '2025-10-23 10:05:11'),
(32, 5, 'What should I bring on this tour?', 'Please bring comfortable walking shoes, sun protection, camera, light jacket for evenings, personal medications, and a copy of your passport.', 2, 1, '2025-10-23 10:05:11'),
(33, 4, 'What should I bring on this tour?', 'Please bring comfortable walking shoes, sun protection, camera, light jacket for evenings, personal medications, and a copy of your passport.', 2, 1, '2025-10-23 10:05:11'),
(36, 3, 'Is this tour suitable for children?', 'This tour is suitable for children 12 years and above. Children must be accompanied by adults at all times.', 3, 1, '2025-10-23 10:05:11'),
(37, 1, 'Is this tour suitable for children?', 'This tour is suitable for children 12 years and above. Children must be accompanied by adults at all times.', 3, 1, '2025-10-23 10:05:11'),
(38, 2, 'Is this tour suitable for children?', 'This tour is suitable for children 12 years and above. Children must be accompanied by adults at all times.', 3, 1, '2025-10-23 10:05:11'),
(39, 5, 'Is this tour suitable for children?', 'This tour is suitable for children 12 years and above. Children must be accompanied by adults at all times.', 3, 1, '2025-10-23 10:05:11'),
(40, 4, 'Is this tour suitable for children?', 'This tour is suitable for children 12 years and above. Children must be accompanied by adults at all times.', 3, 1, '2025-10-23 10:05:11'),
(43, 3, 'What is included in the tour price?', 'The tour price includes accommodation, meals as specified, professional guide, transportation during the tour, and entrance fees to attractions mentioned in the itinerary.', 1, 1, '2025-10-23 10:07:05'),
(44, 1, 'What is included in the tour price?', 'The tour price includes accommodation, meals as specified, professional guide, transportation during the tour, and entrance fees to attractions mentioned in the itinerary.', 1, 1, '2025-10-23 10:07:05'),
(45, 2, 'What is included in the tour price?', 'The tour price includes accommodation, meals as specified, professional guide, transportation during the tour, and entrance fees to attractions mentioned in the itinerary.', 1, 1, '2025-10-23 10:07:05'),
(46, 5, 'What is included in the tour price?', 'The tour price includes accommodation, meals as specified, professional guide, transportation during the tour, and entrance fees to attractions mentioned in the itinerary.', 1, 1, '2025-10-23 10:07:05'),
(47, 4, 'What is included in the tour price?', 'The tour price includes accommodation, meals as specified, professional guide, transportation during the tour, and entrance fees to attractions mentioned in the itinerary.', 1, 1, '2025-10-23 10:07:05'),
(50, 3, 'What should I bring on this tour?', 'Please bring comfortable walking shoes, sun protection, camera, light jacket for evenings, personal medications, and a copy of your passport.', 2, 1, '2025-10-23 10:07:05'),
(51, 1, 'What should I bring on this tour?', 'Please bring comfortable walking shoes, sun protection, camera, light jacket for evenings, personal medications, and a copy of your passport.', 2, 1, '2025-10-23 10:07:05'),
(52, 2, 'What should I bring on this tour?', 'Please bring comfortable walking shoes, sun protection, camera, light jacket for evenings, personal medications, and a copy of your passport.', 2, 1, '2025-10-23 10:07:05'),
(53, 5, 'What should I bring on this tour?', 'Please bring comfortable walking shoes, sun protection, camera, light jacket for evenings, personal medications, and a copy of your passport.', 2, 1, '2025-10-23 10:07:05'),
(54, 4, 'What should I bring on this tour?', 'Please bring comfortable walking shoes, sun protection, camera, light jacket for evenings, personal medications, and a copy of your passport.', 2, 1, '2025-10-23 10:07:05'),
(57, 3, 'Is this tour suitable for children?', 'This tour is suitable for children 12 years and above. Children must be accompanied by adults at all times.', 3, 1, '2025-10-23 10:07:05'),
(58, 1, 'Is this tour suitable for children?', 'This tour is suitable for children 12 years and above. Children must be accompanied by adults at all times.', 3, 1, '2025-10-23 10:07:05'),
(59, 2, 'Is this tour suitable for children?', 'This tour is suitable for children 12 years and above. Children must be accompanied by adults at all times.', 3, 1, '2025-10-23 10:07:05'),
(60, 5, 'Is this tour suitable for children?', 'This tour is suitable for children 12 years and above. Children must be accompanied by adults at all times.', 3, 1, '2025-10-23 10:07:05'),
(61, 4, 'Is this tour suitable for children?', 'This tour is suitable for children 12 years and above. Children must be accompanied by adults at all times.', 3, 1, '2025-10-23 10:07:05');

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

--
-- Dumping data for table `tour_images`
--

INSERT INTO `tour_images` (`id`, `tour_id`, `image_url`, `image_title`, `image_description`, `image_type`, `sort_order`, `alt_text`, `is_featured`, `created_at`) VALUES
(1, 1, 'https://images.unsplash.com/photo-1489392191049-fc10c97e64b6?auto=format&fit=crop&w=800&q=80', 'Maasai Mara Safari Adventure - Main Image', NULL, 'main', 1, 'Maasai Mara Safari Adventure in Kenya', 0, '2025-10-22 23:51:33'),
(2, 2, 'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=800&q=80', 'Serengeti Migration Safari - Main Image', NULL, 'main', 1, 'Serengeti Migration Safari in Tanzania', 0, '2025-10-22 23:51:33'),
(3, 3, 'https://images.unsplash.com/photo-1489749798305-4fea3ae436d3?auto=format&fit=crop&w=800&q=80', 'Imperial Cities of Morocco - Main Image', NULL, 'main', 1, 'Imperial Cities of Morocco in Morocco', 0, '2025-10-22 23:51:33'),
(4, 4, 'https://images.unsplash.com/photo-1484318571209-661cf29a69ea?auto=format&fit=crop&w=800&q=80', 'Cape Town and Wine Country - Main Image', NULL, 'main', 1, 'Cape Town and Wine Country in South Africa', 0, '2025-10-22 23:51:33'),
(5, 5, 'https://images.unsplash.com/photo-1547036967-23d11aacaee0?auto=format&fit=crop&w=800&q=80', 'Gorilla Trekking Adventure - Main Image', NULL, 'main', 1, 'Gorilla Trekking Adventure in Uganda', 0, '2025-10-22 23:51:33'),
(6, 6, 'https://images.unsplash.com/photo-1447933601403-0c6688de566e?auto=format&fit=crop&w=800&q=80', 'Coffee Farm Experience Kenya - Main Image', NULL, 'main', 1, 'Coffee Farm Experience Kenya in Kenya', 0, '2025-10-22 23:51:33'),
(7, 7, 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?auto=format&fit=crop&w=800&q=80', 'Kilimanjaro Marathon - Main Image', NULL, 'main', 1, 'Kilimanjaro Marathon in Tanzania', 0, '2025-10-22 23:51:33'),
(8, 8, 'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=800&q=80', 'African Business Summit - Main Image', NULL, 'main', 1, 'African Business Summit in South Africa', 0, '2025-10-22 23:51:33'),
(9, 9, 'https://kimi-web-img.moonshot.cn/img/c8.alamy.com/1fc8e7a4a49108a8de5eb1b815f57bbc2eae90e6.jpg', 'Golden Monkey Tracking - Main Image', NULL, 'main', 1, 'Golden Monkey Tracking in Rwanda', 0, '2025-10-22 23:51:33'),
(16, 1, 'https://images.unsplash.com/photo-1489392191049-fc10c97e64b6?auto=format&fit=crop&w=800&q=80', 'Maasai Mara Safari Adventure - Main Image', NULL, 'main', 1, 'Maasai Mara Safari Adventure in Kenya', 0, '2025-10-23 10:05:11'),
(17, 2, 'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=800&q=80', 'Serengeti Migration Safari - Main Image', NULL, 'main', 1, 'Serengeti Migration Safari in Tanzania', 0, '2025-10-23 10:05:11'),
(18, 3, 'https://images.unsplash.com/photo-1489749798305-4fea3ae436d3?auto=format&fit=crop&w=800&q=80', 'Imperial Cities of Morocco - Main Image', NULL, 'main', 1, 'Imperial Cities of Morocco in Morocco', 0, '2025-10-23 10:05:11'),
(19, 4, 'https://images.unsplash.com/photo-1484318571209-661cf29a69ea?auto=format&fit=crop&w=800&q=80', 'Cape Town and Wine Country - Main Image', NULL, 'main', 1, 'Cape Town and Wine Country in South Africa', 0, '2025-10-23 10:05:11'),
(20, 5, 'https://images.unsplash.com/photo-1547036967-23d11aacaee0?auto=format&fit=crop&w=800&q=80', 'Gorilla Trekking Adventure - Main Image', NULL, 'main', 1, 'Gorilla Trekking Adventure in Uganda', 0, '2025-10-23 10:05:11'),
(21, 6, 'https://images.unsplash.com/photo-1447933601403-0c6688de566e?auto=format&fit=crop&w=800&q=80', 'Coffee Farm Experience Kenya - Main Image', NULL, 'main', 1, 'Coffee Farm Experience Kenya in Kenya', 0, '2025-10-23 10:05:11'),
(22, 7, 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?auto=format&fit=crop&w=800&q=80', 'Kilimanjaro Marathon - Main Image', NULL, 'main', 1, 'Kilimanjaro Marathon in Tanzania', 0, '2025-10-23 10:05:11'),
(23, 8, 'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=800&q=80', 'African Business Summit - Main Image', NULL, 'main', 1, 'African Business Summit in South Africa', 0, '2025-10-23 10:05:11'),
(24, 9, 'https://kimi-web-img.moonshot.cn/img/c8.alamy.com/1fc8e7a4a49108a8de5eb1b815f57bbc2eae90e6.jpg', 'Golden Monkey Tracking - Main Image', NULL, 'main', 1, 'Golden Monkey Tracking in Rwanda', 0, '2025-10-23 10:05:11'),
(25, 11, 'uploads/tours/11_main_1761212403_2106.jpg', 'Golden Monkey Tracking - Main Image', NULL, 'main', 1, 'Golden Monkey Tracking in Rwanda', 0, '2025-10-23 10:05:11'),
(31, 1, 'https://images.unsplash.com/photo-1489392191049-fc10c97e64b6?auto=format&fit=crop&w=800&q=80', 'Maasai Mara Safari Adventure - Main Image', NULL, 'main', 1, 'Maasai Mara Safari Adventure in Kenya', 0, '2025-10-23 10:07:05'),
(32, 2, 'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=800&q=80', 'Serengeti Migration Safari - Main Image', NULL, 'main', 1, 'Serengeti Migration Safari in Tanzania', 0, '2025-10-23 10:07:05'),
(33, 3, 'https://images.unsplash.com/photo-1489749798305-4fea3ae436d3?auto=format&fit=crop&w=800&q=80', 'Imperial Cities of Morocco - Main Image', NULL, 'main', 1, 'Imperial Cities of Morocco in Morocco', 0, '2025-10-23 10:07:05'),
(34, 4, 'https://images.unsplash.com/photo-1484318571209-661cf29a69ea?auto=format&fit=crop&w=800&q=80', 'Cape Town and Wine Country - Main Image', NULL, 'main', 1, 'Cape Town and Wine Country in South Africa', 0, '2025-10-23 10:07:05'),
(35, 5, 'https://images.unsplash.com/photo-1547036967-23d11aacaee0?auto=format&fit=crop&w=800&q=80', 'Gorilla Trekking Adventure - Main Image', NULL, 'main', 1, 'Gorilla Trekking Adventure in Uganda', 0, '2025-10-23 10:07:05'),
(36, 6, 'https://images.unsplash.com/photo-1447933601403-0c6688de566e?auto=format&fit=crop&w=800&q=80', 'Coffee Farm Experience Kenya - Main Image', NULL, 'main', 1, 'Coffee Farm Experience Kenya in Kenya', 0, '2025-10-23 10:07:05'),
(37, 7, 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?auto=format&fit=crop&w=800&q=80', 'Kilimanjaro Marathon - Main Image', NULL, 'main', 1, 'Kilimanjaro Marathon in Tanzania', 0, '2025-10-23 10:07:05'),
(38, 8, 'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=800&q=80', 'African Business Summit - Main Image', NULL, 'main', 1, 'African Business Summit in South Africa', 0, '2025-10-23 10:07:05'),
(39, 9, 'https://kimi-web-img.moonshot.cn/img/c8.alamy.com/1fc8e7a4a49108a8de5eb1b815f57bbc2eae90e6.jpg', 'Golden Monkey Tracking - Main Image', NULL, 'main', 1, 'Golden Monkey Tracking in Rwanda', 0, '2025-10-23 10:07:05'),
(40, 11, 'uploads/tours/11_main_1761212403_2106.jpg', 'Golden Monkey Tracking - Main Image', NULL, 'main', 1, 'Golden Monkey Tracking in Rwanda', 0, '2025-10-23 10:07:05');

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

--
-- Dumping data for table `tour_schedules`
--

INSERT INTO `tour_schedules` (`id`, `tour_id`, `scheduled_date`, `end_date`, `available_slots`, `booked_slots`, `price`, `status`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 9, '2025-10-15', '2025-11-06', 20, 0, 2040.00, 'active', 'Experience the magic of Africa with our expertly curated travel packages. From safaris to cultural immersions, we make your African dreams come true.', 19, '2025-10-29 13:19:54', '2025-10-29 13:19:54');

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
  `training_score` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `role`, `first_name`, `last_name`, `phone`, `country`, `city`, `address`, `profile_image`, `status`, `email_verified`, `last_login`, `created_at`, `updated_at`, `sponsor_id`, `username`, `full_name`, `level`, `upline_id`, `mca_id`, `team_size`, `kyc_status`, `training_status`, `training_completion_date`, `kyc_approval_date`, `can_sell`, `training_score`) VALUES
(1, 'admin@foreveryoungtours.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'super_admin', 'Super', 'Admin', '+254700000000', 'Kenya', NULL, NULL, NULL, 'active', 0, NULL, '2025-10-22 14:29:47', '2025-10-22 14:29:47', NULL, NULL, NULL, 1, NULL, NULL, 0, 'pending', 'not_started', NULL, NULL, 0, 0),
(2, 'mca.kenya@foreveryoungtours.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'mca', 'John', 'Kamau', '+254701000000', 'Kenya', NULL, NULL, NULL, 'active', 0, NULL, '2025-10-22 14:29:47', '2025-10-22 19:43:24', 1, NULL, NULL, 1, NULL, NULL, 0, 'pending', 'not_started', NULL, NULL, 0, 0),
(3, 'mca.tanzania@foreveryoungtours.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'mca', 'Grace', 'Mwangi', '+254702000000', 'Tanzania', NULL, NULL, NULL, 'active', 0, NULL, '2025-10-22 14:29:47', '2025-10-22 19:43:24', 1, NULL, NULL, 1, NULL, NULL, 0, 'pending', 'not_started', NULL, NULL, 0, 0),
(4, 'mca.uganda@foreveryoungtours.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'mca', 'Peter', 'Ochieng', '+254703000000', 'Uganda', NULL, NULL, NULL, 'active', 0, NULL, '2025-10-22 14:29:47', '2025-10-22 19:43:24', 1, NULL, NULL, 1, NULL, NULL, 0, 'pending', 'not_started', NULL, NULL, 0, 0),
(5, 'advisor1@foreveryoungtours.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'Mary', 'Wanjiku', '+254704000000', 'Kenya', NULL, NULL, NULL, 'active', 0, NULL, '2025-10-22 14:29:47', '2025-10-22 19:43:24', 2, NULL, NULL, 1, NULL, NULL, 0, 'pending', 'not_started', NULL, NULL, 0, 0),
(6, 'advisor2@foreveryoungtours.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'James', 'Mutua', '+254705000000', 'Kenya', NULL, NULL, NULL, 'active', 0, NULL, '2025-10-22 14:29:47', '2025-10-22 19:43:24', 2, NULL, NULL, 1, NULL, NULL, 0, 'pending', 'not_started', NULL, NULL, 0, 0),
(7, 'advisor3@foreveryoungtours.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'Sarah', 'Njeri', '+254706000000', 'Tanzania', NULL, NULL, NULL, 'active', 0, NULL, '2025-10-22 14:29:47', '2025-10-22 19:43:24', 2, NULL, NULL, 1, NULL, NULL, 0, 'pending', 'not_started', NULL, NULL, 0, 0),
(8, 'client1@foreveryoungtours.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'client', 'David', 'Kiprotich', '+254707000000', 'Kenya', NULL, NULL, NULL, 'active', 0, NULL, '2025-10-22 14:29:47', '2025-10-22 19:43:24', 5, NULL, NULL, 1, NULL, NULL, 0, 'pending', 'not_started', NULL, NULL, 0, 0),
(9, 'client2@foreveryoungtours.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'client', 'Lucy', 'Akinyi', '+254708000000', 'Kenya', NULL, NULL, NULL, 'active', 0, NULL, '2025-10-22 14:29:47', '2025-10-22 19:43:24', 5, NULL, NULL, 1, NULL, NULL, 0, 'pending', 'not_started', NULL, NULL, 0, 0),
(10, 'client3@foreveryoungtours.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'client', 'Michael', 'Otieno', '+254709000000', 'Uganda', NULL, NULL, NULL, 'active', 0, NULL, '2025-10-22 14:29:47', '2025-10-22 19:43:24', 5, NULL, NULL, 1, NULL, NULL, 0, 'pending', 'not_started', NULL, NULL, 0, 0),
(11, 'mca.africa@foreveryoungtours.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'mca', '', '', NULL, NULL, NULL, NULL, NULL, 'active', 0, NULL, '2025-10-22 19:43:24', '2025-10-22 19:43:24', 1, 'mca_africa', 'John MCA Africa', 1, NULL, NULL, 0, 'pending', 'not_started', NULL, NULL, 0, 0),
(12, 'mca.east@foreveryoungtours.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'mca', '', '', NULL, NULL, NULL, NULL, NULL, 'active', 0, NULL, '2025-10-22 19:43:24', '2025-10-22 19:43:24', 1, 'mca_east', 'Sarah MCA East', 1, NULL, NULL, 0, 'pending', 'not_started', NULL, NULL, 0, 0),
(19, 'admin@foreveryoung.com', '$2y$10$Mr7/RbpcIkueovurt1HzN.rYsAfsGWmZOqeFLILysNgp245F7jn8m', 'super_admin', 'Super', 'Admin', '+1234567890', NULL, NULL, NULL, NULL, 'active', 1, NULL, '2025-10-26 18:00:23', '2025-10-26 18:28:46', NULL, NULL, NULL, 1, NULL, NULL, 0, 'pending', 'not_started', NULL, NULL, 0, 0),
(20, 'mca@foreveryoung.com', '$2y$10$FunnRDzvnojL.qJcYtHhdOWc.MIJoNpaHgg6dfWVAGUHwLb9qZ/Ja', 'mca', 'MCA', 'User', '+1234567891', NULL, NULL, NULL, NULL, 'active', 1, NULL, '2025-10-26 18:00:24', '2025-10-26 18:06:16', NULL, NULL, NULL, 1, NULL, NULL, 0, 'pending', 'not_started', NULL, NULL, 0, 0),
(21, 'advisor@foreveryoung.com', '$2y$10$A6sg7U2tGB33ly7lbx9ZW.zpnyJm7Z0r.vG2UzQSdaFZS27jK6/q.', 'advisor', 'Advisor', 'User', '+1234567892', NULL, NULL, NULL, NULL, 'active', 1, NULL, '2025-10-26 18:00:24', '2025-10-26 18:06:16', NULL, NULL, NULL, 1, NULL, NULL, 0, 'pending', 'not_started', NULL, NULL, 0, 0),
(22, 'client@foreveryoung.com', '$2y$10$0GLvYk5IIl9codQIW2Byx.1D8luDhTCBTOmRbV5BppQ9dy3Tr5omG', 'client', 'Client', 'User', '+1234567893', NULL, NULL, NULL, NULL, 'active', 1, NULL, '2025-10-26 18:00:24', '2025-10-26 18:28:46', NULL, NULL, NULL, 1, NULL, NULL, 0, 'pending', 'not_started', NULL, NULL, 0, 0),
(23, 'testname450@gmail.com', '$2y$10$oN60lriwsPA3JdA6x/1P2.LlEop0mbVocV0D4EKdwauCJV5x6f84W', 'client', 'Test', 'Name', '+250788712679', NULL, NULL, NULL, NULL, 'active', 0, NULL, '2025-10-30 07:15:37', '2025-10-30 07:15:37', NULL, NULL, NULL, 1, NULL, NULL, 0, 'pending', 'not_started', NULL, NULL, 0, 0),
(25, 'acountexpertbroker@gmail.com', '$2y$10$aaVSKmfNif6BRACxzfLjWeKr5DSl.vrusx7w2nlAkDwJIL9W/LvMe', 'client', 'peter', 'Doe', '+250788712679', 'Rwanda', 'Kigali', NULL, NULL, 'active', 1, NULL, '2025-10-30 12:06:21', '2025-10-30 12:06:21', NULL, NULL, NULL, 1, NULL, NULL, 0, 'pending', 'not_started', NULL, NULL, 0, 0);

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
-- Indexes for table `commissions`
--
ALTER TABLE `commissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_booking_id` (`booking_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_commissions_user_status` (`user_id`,`status`);

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
  ADD KEY `idx_featured` (`featured`);

--
-- Indexes for table `destinations`
--
ALTER TABLE `destinations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_country` (`country`),
  ADD KEY `idx_featured` (`featured`),
  ADD KEY `idx_status` (`status`);

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
-- Indexes for table `mca_assignments`
--
ALTER TABLE `mca_assignments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_mca_country` (`mca_id`,`country_id`),
  ADD KEY `country_id` (`country_id`);

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
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payment_reference` (`payment_reference`),
  ADD KEY `idx_booking_id` (`booking_id`),
  ADD KEY `idx_payment_reference` (`payment_reference`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `regions`
--
ALTER TABLE `regions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_slug` (`slug`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_featured` (`featured`);

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
  ADD KEY `idx_tours_booking_count` (`booking_count`);

--
-- Indexes for table `tour_availability`
--
ALTER TABLE `tour_availability`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_tour_date` (`tour_id`,`available_date`),
  ADD KEY `idx_tour_availability_date` (`available_date`),
  ADD KEY `idx_tour_availability_status` (`status`);

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
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_role` (`role`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `sponsor_id` (`sponsor_id`);

--
-- AUTO_INCREMENT for dumped tables
--

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

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
-- AUTO_INCREMENT for table `commissions`
--
ALTER TABLE `commissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `destinations`
--
ALTER TABLE `destinations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
-- AUTO_INCREMENT for table `mca_assignments`
--
ALTER TABLE `mca_assignments`
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
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `regions`
--
ALTER TABLE `regions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

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
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tours`
--
ALTER TABLE `tours`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tour_availability`
--
ALTER TABLE `tour_availability`
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
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

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
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `tours`
--
ALTER TABLE `tours`
  ADD CONSTRAINT `tours_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `tours_ibfk_2` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `tour_availability`
--
ALTER TABLE `tour_availability`
  ADD CONSTRAINT `tour_availability_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`sponsor_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
