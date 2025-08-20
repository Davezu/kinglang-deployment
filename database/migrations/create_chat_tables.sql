-- Chat System Database Migration
-- Creates tables for the integrated chat system

-- Table for chat conversations
CREATE TABLE IF NOT EXISTS `conversations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `status` enum('bot','human_requested','human_assigned','ended') NOT NULL DEFAULT 'bot',
  `started_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ended_at` timestamp NULL DEFAULT NULL,
  `ended_by` enum('client','admin','system') DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_client_id` (`client_id`),
  KEY `idx_admin_id` (`admin_id`),
  KEY `idx_status` (`status`),
  KEY `idx_started_at` (`started_at`),
  CONSTRAINT `fk_conversations_client` FOREIGN KEY (`client_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_conversations_admin` FOREIGN KEY (`admin_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table for chat messages
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `conversation_id` int(11) NOT NULL,
  `sender_type` enum('client','admin','bot','system') NOT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `message` text NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_conversation_id` (`conversation_id`),
  KEY `idx_sender_type` (`sender_type`),
  KEY `idx_sender_id` (`sender_id`),
  KEY `idx_sent_at` (`sent_at`),
  CONSTRAINT `fk_messages_conversation` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_messages_sender` FOREIGN KEY (`sender_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table for bot responses (predefined responses for common queries)
CREATE TABLE IF NOT EXISTS `bot_responses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `keyword` varchar(100) NOT NULL,
  `response` text NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_keyword` (`keyword`),
  KEY `idx_category` (`category`),
  KEY `idx_is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert default bot responses for bus rental queries
INSERT INTO `bot_responses` (`keyword`, `response`, `category`) VALUES
('pricing', 'Our bus rental pricing varies based on several factors: bus type, duration, distance, and season. Standard buses start at ₱8,000 per day, luxury coaches at ₱12,000 per day. For accurate pricing based on your specific needs, please provide: pickup/drop-off locations, dates, number of passengers, and trip duration.', 'pricing'),
('rates', 'Our bus rental pricing varies based on several factors: bus type, duration, distance, and season. Standard buses start at ₱8,000 per day, luxury coaches at ₱12,000 per day. For accurate pricing based on your specific needs, please provide: pickup/drop-off locations, dates, number of passengers, and trip duration.', 'pricing'),
('cost', 'Our bus rental pricing varies based on several factors: bus type, duration, distance, and season. Standard buses start at ₱8,000 per day, luxury coaches at ₱12,000 per day. For accurate pricing based on your specific needs, please provide: pickup/drop-off locations, dates, number of passengers, and trip duration.', 'pricing'),
('booking', 'To make a bus rental booking with KingLang: 1) Create an account or log in, 2) Fill out our booking request form with your travel details, 3) Wait for admin approval and pricing, 4) Complete payment to confirm your booking. You can also call us directly for immediate assistance. All bookings require advance notice of at least 24 hours.', 'booking'),
('reserve', 'To make a bus rental booking with KingLang: 1) Create an account or log in, 2) Fill out our booking request form with your travel details, 3) Wait for admin approval and pricing, 4) Complete payment to confirm your booking. You can also call us directly for immediate assistance. All bookings require advance notice of at least 24 hours.', 'booking'),
('book', 'To make a bus rental booking with KingLang: 1) Create an account or log in, 2) Fill out our booking request form with your travel details, 3) Wait for admin approval and pricing, 4) Complete payment to confirm your booking. You can also call us directly for immediate assistance. All bookings require advance notice of at least 24 hours.', 'booking'),
('cancellation', 'Our cancellation policy: Full refund if cancelled 7+ days before departure; 50% refund if cancelled 3-6 days before; 25% refund if cancelled 1-2 days before; No refund for same-day cancellations or no-shows. Refunds are processed within 7-14 business days. Emergency cancellations due to weather or unforeseen circumstances may be handled on a case-by-case basis.', 'policy'),
('cancel', 'Our cancellation policy: Full refund if cancelled 7+ days before departure; 50% refund if cancelled 3-6 days before; 25% refund if cancelled 1-2 days before; No refund for same-day cancellations or no-shows. Refunds are processed within 7-14 business days. Emergency cancellations due to weather or unforeseen circumstances may be handled on a case-by-case basis.', 'policy'),
('refund', 'Our cancellation policy: Full refund if cancelled 7+ days before departure; 50% refund if cancelled 3-6 days before; 25% refund if cancelled 1-2 days before; No refund for same-day cancellations or no-shows. Refunds are processed within 7-14 business days. Emergency cancellations due to weather or unforeseen circumstances may be handled on a case-by-case basis.', 'policy'),
('contact', 'You can reach KingLang Bus Rental through: Phone: [Your phone number], Email: [Your email], Office Hours: Monday-Sunday, 8AM-6PM. For existing bookings, you can also check your booking status and communicate through your account dashboard. For emergencies during travel, we provide 24/7 support.', 'contact'),
('phone', 'You can reach KingLang Bus Rental through: Phone: [Your phone number], Email: [Your email], Office Hours: Monday-Sunday, 8AM-6PM. For existing bookings, you can also check your booking status and communicate through your account dashboard. For emergencies during travel, we provide 24/7 support.', 'contact'),
('email', 'You can reach KingLang Bus Rental through: Phone: [Your phone number], Email: [Your email], Office Hours: Monday-Sunday, 8AM-6PM. For existing bookings, you can also check your booking status and communicate through your account dashboard. For emergencies during travel, we provide 24/7 support.', 'contact'),
('fleet', 'KingLang offers various bus types: Standard buses (45-50 passengers), Luxury coaches (40-45 passengers with AC, reclining seats, entertainment), Mini buses (20-25 passengers), and Coaster buses (15-20 passengers). All vehicles are regularly maintained, insured, and operated by professional licensed drivers. We can accommodate groups of all sizes for various occasions.', 'fleet'),
('buses', 'KingLang offers various bus types: Standard buses (45-50 passengers), Luxury coaches (40-45 passengers with AC, reclining seats, entertainment), Mini buses (20-25 passengers), and Coaster buses (15-20 passengers). All vehicles are regularly maintained, insured, and operated by professional licensed drivers. We can accommodate groups of all sizes for various occasions.', 'fleet'),
('vehicles', 'KingLang offers various bus types: Standard buses (45-50 passengers), Luxury coaches (40-45 passengers with AC, reclining seats, entertainment), Mini buses (20-25 passengers), and Coaster buses (15-20 passengers). All vehicles are regularly maintained, insured, and operated by professional licensed drivers. We can accommodate groups of all sizes for various occasions.', 'fleet'),
('payment', 'We accept various payment methods: Bank transfer, GCash, PayMaya, and cash payments at our office. A 50% down payment is required to secure your booking, with the remaining balance due before departure. Payment receipts and official receipts are provided for all transactions. We use secure payment processing for online transactions.', 'payment'),
('pay', 'We accept various payment methods: Bank transfer, GCash, PayMaya, and cash payments at our office. A 50% down payment is required to secure your booking, with the remaining balance due before departure. Payment receipts and official receipts are provided for all transactions. We use secure payment processing for online transactions.', 'payment'),
('gcash', 'We accept various payment methods: Bank transfer, GCash, PayMaya, and cash payments at our office. A 50% down payment is required to secure your booking, with the remaining balance due before departure. Payment receipts and official receipts are provided for all transactions. We use secure payment processing for online transactions.', 'payment'),
('requirements', 'For bus rental bookings, we require: Valid ID of the primary contact person, Complete passenger manifest (for insurance purposes), Advance payment (50% down payment), Signed rental agreement. For corporate bookings, additional requirements may include company registration documents. All requirements help ensure safe and legal transportation services.', 'requirements'),
('documents', 'For bus rental bookings, we require: Valid ID of the primary contact person, Complete passenger manifest (for insurance purposes), Advance payment (50% down payment), Signed rental agreement. For corporate bookings, additional requirements may include company registration documents. All requirements help ensure safe and legal transportation services.', 'requirements'),
('safety', 'Safety is our top priority at KingLang. All our buses undergo regular maintenance and safety inspections, our drivers are licensed and experienced professionals, vehicles are equipped with safety features like seatbelts and first aid kits, and we maintain comprehensive insurance coverage. We follow all DOTr regulations and safety protocols for passenger transportation.', 'safety'),
('insurance', 'All KingLang vehicles are covered by comprehensive insurance including passenger accident insurance. This covers medical expenses and other benefits in case of accidents during the trip. We are also registered with relevant government agencies and comply with all transportation safety regulations. Your safety and security are our primary concerns.', 'safety'),
('hours', 'KingLang Bus Rental operates Monday through Sunday, 8AM-6PM for bookings and inquiries. Our buses are available for rental 24/7 depending on your needs and driver availability. For after-hours emergencies or urgent bookings, please call our emergency contact number. We recommend booking in advance to ensure vehicle and driver availability.', 'schedule'),
('schedule', 'KingLang Bus Rental operates Monday through Sunday, 8AM-6PM for bookings and inquiries. Our buses are available for rental 24/7 depending on your needs and driver availability. For after-hours emergencies or urgent bookings, please call our emergency contact number. We recommend booking in advance to ensure vehicle and driver availability.', 'schedule'),
('destinations', 'KingLang serves various destinations across Luzon and nearby areas. We handle local city tours, provincial trips, airport transfers, corporate events, school field trips, and special occasions. Popular destinations include Baguio, Bataan, Subic, La Union, and Metro Manila areas. For destinations outside our regular service area, please inquire for special arrangements.', 'destinations'),
('locations', 'KingLang serves various destinations across Luzon and nearby areas. We handle local city tours, provincial trips, airport transfers, corporate events, school field trips, and special occasions. Popular destinations include Baguio, Bataan, Subic, La Union, and Metro Manila areas. For destinations outside our regular service area, please inquire for special arrangements.', 'destinations'),
('driver', 'All KingLang drivers are licensed professionals with clean driving records and extensive experience in passenger transportation. They undergo regular training and health checks. Our drivers are courteous, punctual, and knowledgeable about routes and destinations. Driver assignments are made based on route familiarity and vehicle type. Professional driver service is included in all rentals.', 'drivers'),
('drivers', 'All KingLang drivers are licensed professionals with clean driving records and extensive experience in passenger transportation. They undergo regular training and health checks. Our drivers are courteous, punctual, and knowledgeable about routes and destinations. Driver assignments are made based on route familiarity and vehicle type. Professional driver service is included in all rentals.', 'drivers');

-- Create indexes for better performance
CREATE INDEX idx_conversations_client_status ON conversations(client_id, status);
CREATE INDEX idx_conversations_admin_status ON conversations(admin_id, status);
CREATE INDEX idx_messages_conversation_sent ON messages(conversation_id, sent_at);
CREATE INDEX idx_bot_responses_keyword_active ON bot_responses(keyword, is_active);
