-- Migration: Create users_menu table
-- Purpose: Mengatur hak akses users terhadap group menu yang bisa digunakan
-- Date: 2025-10-08

CREATE TABLE IF NOT EXISTS `users_menu` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `group_id` INT(11) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_group_id` (`group_id`),
  UNIQUE KEY `unique_user_group` (`user_id`, `group_id`),
  CONSTRAINT `fk_users_menu_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_users_menu_group` FOREIGN KEY (`group_id`) REFERENCES `menu_groups` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add comments to columns
ALTER TABLE `users_menu`
  MODIFY COLUMN `user_id` INT(11) NOT NULL COMMENT 'Foreign key to users table',
  MODIFY COLUMN `group_id` INT(11) NOT NULL COMMENT 'Foreign key to menu_groups table';

