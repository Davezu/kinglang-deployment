-- Add Google authentication fields to users table
ALTER TABLE `users` 
ADD COLUMN `google_id` VARCHAR(100) NULL AFTER `company_name`,
ADD COLUMN `profile_picture` VARCHAR(255) NULL AFTER `google_id`;

-- Add index for faster Google ID lookups
ALTER TABLE `users` ADD INDEX `google_id_index` (`google_id`);  