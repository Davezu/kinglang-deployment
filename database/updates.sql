-- Add notes column to payments table
-- Add terms_agreements table to track user agreement to terms
CREATE TABLE `terms_agreements` (
  `agreement_id` int(11) NOT NULL AUTO_INCREMENT,
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `agreed_terms` tinyint(1) NOT NULL DEFAULT 0,
  `agreed_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_ip` varchar(45) NOT NULL,
  PRIMARY KEY (`agreement_id`),
  KEY `booking_id` (`booking_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Add foreign keys
ALTER TABLE `terms_agreements`
  ADD CONSTRAINT `terms_agreements_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `terms_agreements_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE; 