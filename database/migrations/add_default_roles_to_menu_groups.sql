-- Migration: Add default role fields to menu_groups table
-- Purpose: Menentukan default menu group untuk setiap role user
-- Date: 2025-10-08

-- Add default role columns before created_at
ALTER TABLE `menu_groups` 
ADD COLUMN `default_admin` TINYINT(1) NOT NULL DEFAULT 0 AFTER `is_collapsible`,
ADD COLUMN `default_manajemen` TINYINT(1) NOT NULL DEFAULT 0 AFTER `default_admin`,
ADD COLUMN `default_user` TINYINT(1) NOT NULL DEFAULT 0 AFTER `default_manajemen`,
ADD COLUMN `default_marketing` TINYINT(1) NOT NULL DEFAULT 0 AFTER `default_user`,
ADD COLUMN `default_customer` TINYINT(1) NOT NULL DEFAULT 0 AFTER `default_marketing`;

-- Add index for better query performance
ALTER TABLE `menu_groups` 
ADD INDEX `idx_default_admin` (`default_admin`),
ADD INDEX `idx_default_manajemen` (`default_manajemen`),
ADD INDEX `idx_default_user` (`default_user`),
ADD INDEX `idx_default_marketing` (`default_marketing`),
ADD INDEX `idx_default_customer` (`default_customer`);

-- Comment
ALTER TABLE `menu_groups` 
MODIFY COLUMN `default_admin` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Default menu group untuk role Admin',
MODIFY COLUMN `default_manajemen` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Default menu group untuk role Manajemen',
MODIFY COLUMN `default_user` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Default menu group untuk role User',
MODIFY COLUMN `default_marketing` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Default menu group untuk role Marketing',
MODIFY COLUMN `default_customer` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Default menu group untuk role Customer';

