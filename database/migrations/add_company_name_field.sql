-- Migration: Add company_name field to users table
-- Date: 2025-05-01

-- Add company_name column to users table
ALTER TABLE `users` ADD COLUMN `company_name` VARCHAR(100) NULL AFTER `last_name`;

-- If address field doesn't exist, add it too
ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `address` TEXT NULL AFTER `contact_number`;

-- Grant permissions for the company_name field
-- This might be necessary depending on your database setup
-- GRANT SELECT, INSERT, UPDATE ON `users`.`company_name` TO 'your_db_user'@'localhost'; 