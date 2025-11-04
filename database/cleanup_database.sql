-- Remove unnecessary tables that are not needed for the continent/country structure

-- These tables are not needed for the basic continent/country/tour structure:
DROP TABLE IF EXISTS `blog_comments`;
DROP TABLE IF EXISTS `blog_likes`;
DROP TABLE IF EXISTS `blog_post_tags`;
DROP TABLE IF EXISTS `blog_posts`;
DROP TABLE IF EXISTS `blog_categories`;
DROP TABLE IF EXISTS `blog_tags`;
DROP TABLE IF EXISTS `booking_activities`;
DROP TABLE IF EXISTS `booking_cars`;
DROP TABLE IF EXISTS `booking_cruises`;
DROP TABLE IF EXISTS `booking_engine_orders`;
DROP TABLE IF EXISTS `booking_flights`;
DROP TABLE IF EXISTS `booking_hotels`;
DROP TABLE IF EXISTS `tour_schedule_bookings`;
DROP TABLE IF EXISTS `tour_schedules`;
DROP TABLE IF EXISTS `training_quiz_attempts`;
DROP TABLE IF EXISTS `training_quiz_questions`;
DROP TABLE IF EXISTS `training_assignments`;
DROP TABLE IF EXISTS `training_certificates`;
DROP TABLE IF EXISTS `training_progress`;
DROP TABLE IF EXISTS `training_modules`;
DROP TABLE IF EXISTS `kyc_documents`;
DROP TABLE IF EXISTS `kyc_status`;

-- Keep these essential tables:
-- regions (continents)
-- countries
-- tours
-- tour_images
-- tour_faqs
-- tour_reviews
-- tour_availability
-- bookings
-- booking_inquiries
-- users
-- commissions
-- payments
-- reviews
-- shared_links
-- notifications
-- mca_assignments
-- mlm_hierarchy
-- system_settings
-- destinations
