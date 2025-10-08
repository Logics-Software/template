-- Rollback Migration: Remove default role fields from menu_groups table
-- Purpose: Revert changes if needed
-- Date: 2025-10-08

-- Drop indexes first
ALTER TABLE `menu_groups` 
DROP INDEX IF EXISTS `idx_default_admin`,
DROP INDEX IF EXISTS `idx_default_manajemen`,
DROP INDEX IF EXISTS `idx_default_user`,
DROP INDEX IF EXISTS `idx_default_marketing`,
DROP INDEX IF EXISTS `idx_default_customer`;

-- Drop columns
ALTER TABLE `menu_groups` 
DROP COLUMN IF EXISTS `default_admin`,
DROP COLUMN IF EXISTS `default_manajemen`,
DROP COLUMN IF EXISTS `default_user`,
DROP COLUMN IF EXISTS `default_marketing`,
DROP COLUMN IF EXISTS `default_customer`;

