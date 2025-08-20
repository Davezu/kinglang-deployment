-- Migration to add PayMongo support to the booking system
-- Add PayMongo payment methods to the payments table

-- First, update the payment_method enum to include PayMongo options
ALTER TABLE `payments` MODIFY COLUMN `payment_method` 
  ENUM('Cash','Bank Transfer','Online Payment','GCash','Maya','PayMongo') 
  NOT NULL DEFAULT 'Cash';

-- Add fields to store PayMongo transaction details
ALTER TABLE `payments` 
  ADD COLUMN `paymongo_checkout_session_id` VARCHAR(255) NULL AFTER `notes`,
  ADD COLUMN `paymongo_payment_intent_id` VARCHAR(255) NULL AFTER `paymongo_checkout_session_id`,
  ADD COLUMN `paymongo_source_id` VARCHAR(255) NULL AFTER `paymongo_payment_intent_id`,
  ADD COLUMN `paymongo_reference_number` VARCHAR(255) NULL AFTER `paymongo_source_id`,
  ADD COLUMN `payment_gateway` ENUM('manual','paymongo') DEFAULT 'manual' AFTER `paymongo_reference_number`,
  ADD COLUMN `gateway_response` TEXT NULL AFTER `payment_gateway`;

-- Create index for PayMongo checkout session lookups
CREATE INDEX `idx_payments_checkout_session` ON `payments` (`paymongo_checkout_session_id`);
CREATE INDEX `idx_payments_payment_intent` ON `payments` (`paymongo_payment_intent_id`);
CREATE INDEX `idx_payments_gateway` ON `payments` (`payment_gateway`);

-- Create a table to store PayMongo webhook events for audit trail
CREATE TABLE `paymongo_webhook_events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` varchar(255) NOT NULL,
  `event_type` varchar(100) NOT NULL,
  `checkout_session_id` varchar(255) NULL,
  `payment_intent_id` varchar(255) NULL,
  `booking_id` int(11) NULL,
  `payment_id` int(11) NULL,
  `raw_payload` TEXT NOT NULL,
  `processed` tinyint(1) DEFAULT 0,
  `processed_at` datetime NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_event_id` (`event_id`),
  KEY `idx_checkout_session` (`checkout_session_id`),
  KEY `idx_payment_intent` (`payment_intent_id`),
  KEY `idx_booking_id` (`booking_id`),
  KEY `idx_processed` (`processed`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
