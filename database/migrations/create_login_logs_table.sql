-- Migration: Create login_logs table
-- Description: Table untuk menyimpan history log login user
-- Date: 2025-01-XX

DROP TABLE IF EXISTS `login_logs`;
CREATE TABLE `login_logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `session_token` varchar(64) NOT NULL COMMENT 'Token untuk tracking session login',
  `ip_address` varchar(45) DEFAULT NULL COMMENT 'IPv4 atau IPv6 address',
  `user_agent` text COMMENT 'Browser/device information',
  `login_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu login',
  `logout_at` timestamp NULL DEFAULT NULL COMMENT 'Waktu logout (NULL jika masih aktif)',
  `status` enum('active','logged_out','expired') DEFAULT 'active' COMMENT 'Status session',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_session_token` (`session_token`),
  KEY `idx_login_at` (`login_at`),
  KEY `idx_status` (`status`),
  KEY `idx_user_status` (`user_id`,`status`),
  CONSTRAINT `login_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

