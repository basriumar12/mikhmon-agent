-- SQL Schema for Professional Gold Package Replicated Features

-- 1. Create table for inventory items
CREATE TABLE IF NOT EXISTS `inventory_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `item_code` VARCHAR(50) NOT NULL UNIQUE,
  `item_name` VARCHAR(100) NOT NULL,
  `category` VARCHAR(50) NOT NULL,
  `total_stock` INT DEFAULT 0,
  `used_stock` INT DEFAULT 0,
  `unit` VARCHAR(20) NOT NULL DEFAULT 'Unit',
  `warehouse` VARCHAR(100) DEFAULT 'Gudang Utama',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Create table for support tickets
CREATE TABLE IF NOT EXISTS `support_tickets` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `ticket_number` VARCHAR(50) NOT NULL UNIQUE,
  `customer_name` VARCHAR(100) NOT NULL,
  `complaint` TEXT NOT NULL,
  `priority` ENUM('low', 'medium', 'high', 'critical') DEFAULT 'medium',
  `assigned_technician` VARCHAR(100) DEFAULT NULL,
  `status` ENUM('open', 'process', 'resolved', 'closed') DEFAULT 'open',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Create table for network nodes (OLT & ODP markers)
CREATE TABLE IF NOT EXISTS `network_nodes` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `node_name` VARCHAR(100) NOT NULL UNIQUE,
  `node_type` ENUM('olt', 'odp') NOT NULL,
  `latitude` DECIMAL(10, 8) NOT NULL,
  `longitude` DECIMAL(11, 8) NOT NULL,
  `capacity` VARCHAR(20) DEFAULT '1:8',
  `used_ports` INT DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Add coordinates to billing_customers if not exists
-- (handled via php check in fix_all_modules.php to avoid database errors if column already exists)

-- 5. Baseline seeding data for inventory_items
INSERT IGNORE INTO `inventory_items` (`item_code`, `item_name`, `category`, `total_stock`, `used_stock`, `unit`, `warehouse`) VALUES
('INV-ONT-ZTE-F660', 'ZTE F660 ONT Wi-Fi Router', 'Modem / ONU', 50, 32, 'Unit', 'Gudang Utama'),
('INV-ONT-HG8245H', 'Huawei HG8245H GPON ONU', 'Modem / ONU', 30, 28, 'Unit', 'Gudang Utama'),
('INV-CAB-FO-1CORE', 'Kabel Drop Core Fiber Optik 3 Seling 1 Core', 'Kabel / Pasif', 10, 8, 'Roll (1km)', 'Gudang Samping'),
('INV-ODP-BOX-8', 'Box ODP Plastik 8 Port Splitter', 'Box ODP / Pasif', 15, 5, 'Unit', 'Gudang Utama'),
('INV-ACC-FASTCONN', 'Fast Connector SC/UPC Biru', 'Aksesoris / Konektor', 500, 120, 'Pcs', 'Gudang Utama');

-- 6. Baseline seeding data for support_tickets
INSERT IGNORE INTO `support_tickets` (`ticket_number`, `customer_name`, `complaint`, `priority`, `assigned_technician`, `status`) VALUES
('TCK-2026-0801', 'Ali Jaya (alijaya)', 'Internet lambat dan sering terputus sejak kemarin malam', 'medium', 'Dedi (Teknisi 1)', 'process'),
('TCK-2026-0802', 'Budi Santoso (budis)', 'Lampu LOS merah berkedip pada router (Kabel putus?)', 'high', 'Ahmad (Teknisi 2)', 'open'),
('TCK-2026-0803', 'Cahaya Net (cahayanet)', 'Request migrasi paket dari 20Mbps ke 50Mbps', 'low', NULL, 'open');

-- 7. Baseline seeding data for network_nodes
INSERT IGNORE INTO `network_nodes` (`node_name`, `node_type`, `latitude`, `longitude`, `capacity`, `used_ports`) VALUES
('OLT Samudra Indah Center', 'olt', -7.348400, 112.723400, '4 Ports', 1),
('ODP-SI-01', 'odp', -7.346500, 112.721500, '1:8', 5),
('ODP-SI-02', 'odp', -7.349500, 112.725500, '1:8', 6),
('ODP-SI-03', 'odp', -7.351500, 112.722500, '1:16', 4),
('ODP-SI-04', 'odp', -7.345500, 112.726500, '1:8', 3);
