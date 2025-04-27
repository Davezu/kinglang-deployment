-- Add notes column to payments table
ALTER TABLE `payments` ADD COLUMN `notes` text DEFAULT NULL AFTER `updated_at`; 