-- SQL Schema for SaaS Multi-Owner System

-- 1. Create table for owners (SaaS Tenants)
CREATE TABLE IF NOT EXISTS `owners` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `phone` VARCHAR(20) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `status` ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
  `level` ENUM('bronze', 'silver', 'gold', 'platinum') DEFAULT 'bronze',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Create table for router sessions belonging to owners
CREATE TABLE IF NOT EXISTS `router_sessions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `owner_id` INT NOT NULL,
  `session_name` VARCHAR(100) NOT NULL,
  `ip_address` VARCHAR(100) NOT NULL,
  `username` VARCHAR(100) NOT NULL,
  `password` VARCHAR(100) NOT NULL,
  `hotspot_name` VARCHAR(100) DEFAULT NULL,
  `dns_name` VARCHAR(100) DEFAULT NULL,
  `currency` VARCHAR(20) DEFAULT 'Rp',
  `auto_reload` VARCHAR(20) DEFAULT '10',
  `interface` VARCHAR(50) DEFAULT NULL,
  `info_limit` VARCHAR(20) DEFAULT '2',
  `idle_timeout` VARCHAR(20) DEFAULT NULL,
  `live_report` ENUM('enable', 'disable') DEFAULT 'enable',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `unique_owner_session` (`owner_id`, `session_name`),
  CONSTRAINT `fk_router_sessions_owner` FOREIGN KEY (`owner_id`) REFERENCES `owners` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
