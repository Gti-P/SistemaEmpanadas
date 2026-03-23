-- =============================================================
-- BASE DE DATOS: empanadas_pos
-- Sistema POS para Empanadas y Papas Rellenas
-- Compatible con MySQL 5.7+ / MariaDB 10.3+
-- =============================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
SET NAMES utf8mb4;

-- Crear base de datos
CREATE DATABASE IF NOT EXISTS `empanadas_pos`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `empanadas_pos`;

-- -----------------------------------------------------------
-- TABLA: migrations
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- TABLA: clients
-- -----------------------------------------------------------
DROP TABLE IF EXISTS `clients`;
CREATE TABLE `clients` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `document_type` varchar(10) NOT NULL COMMENT 'CC, CE, NIT, PP, TI',
  `document_number` varchar(30) NOT NULL,
  `name` varchar(150) NOT NULL,
  `address` varchar(200) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `is_counter_client` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `clients_document_number_unique` (`document_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- TABLA: products
-- -----------------------------------------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `category` varchar(50) NOT NULL COMMENT 'empanada, papa_rellena',
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- TABLA: sales
-- -----------------------------------------------------------
DROP TABLE IF EXISTS `sales`;
CREATE TABLE `sales` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `payment_method` varchar(30) NOT NULL DEFAULT 'cash',
  `notes` text DEFAULT NULL,
  `sale_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sales_client_id_foreign` (`client_id`),
  CONSTRAINT `sales_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- TABLA: sale_items
-- -----------------------------------------------------------
DROP TABLE IF EXISTS `sale_items`;
CREATE TABLE `sale_items` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sale_items_sale_id_foreign` (`sale_id`),
  KEY `sale_items_product_id_foreign` (`product_id`),
  CONSTRAINT `sale_items_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sale_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- TABLA: sessions (Laravel session driver)
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================================
-- DATOS INICIALES (SEED)
-- =============================================================

-- Cliente de Mostrador (obligatorio)
INSERT INTO `clients` (`document_type`, `document_number`, `name`, `address`, `city`, `phone`, `is_counter_client`, `created_at`, `updated_at`) VALUES
('CC', '0000000000', 'Cliente de Mostrador', NULL, NULL, NULL, 1, NOW(), NOW());

-- Clientes de ejemplo
INSERT INTO `clients` (`document_type`, `document_number`, `name`, `address`, `city`, `phone`, `is_counter_client`, `created_at`, `updated_at`) VALUES
('CC', '1098765432', 'María García López', 'Cra 10 # 15-20, Centro', 'Bucaramanga', '3001234567', 0, NOW(), NOW()),
('CC', '1090123456', 'Carlos Rueda Pérez', 'Cl 30 # 22-05, La Rosita', 'Floridablanca', '3159876543', 0, NOW(), NOW()),
('CC', '63500001',  'Ana Morales Torres', 'Av 45 # 10-12, San Alonso', 'Girón', '3204567890', 0, NOW(), NOW()),
('CC', '91500200',  'Pedro Ramírez Castro', 'Cra 27 # 45-10', 'Bucaramanga', '3112223344', 0, NOW(), NOW()),
('CE', '987654321', 'Laura Martínez Vega', 'Cl 10 # 8-30', 'Piedecuesta', '3005566778', 0, NOW(), NOW());

-- Productos
INSERT INTO `products` (`name`, `category`, `description`, `price`, `active`, `created_at`, `updated_at`) VALUES
-- Empanadas
('Empanada de Pollo',    'empanada',    'Deliciosa empanada rellena de pollo desmechado con papa y cebolla cabezona', 2500, 1, NOW(), NOW()),
('Empanada de Carne',   'empanada',    'Empanada con carne molida especiada y papa criolla', 2500, 1, NOW(), NOW()),
('Empanada Hawaiana',   'empanada',    'Empanada rellena de jamón, queso fundido y piña caramelizada', 3000, 1, NOW(), NOW()),
('Empanada de Queso',   'empanada',    'Empanada con queso campesino derretido y hogao', 2000, 1, NOW(), NOW()),
('Empanada Mixta',      'empanada',    'Empanada con combinación de carne molida y pollo desmechado', 2800, 1, NOW(), NOW()),
('Empanada Vegetal',    'empanada',    'Empanada con espinaca, zanahoria rallada y queso costeño', 2500, 1, NOW(), NOW()),
-- Papas Rellenas
('Papa Rellena de Pollo',   'papa_rellena', 'Papa criolla rellena con pollo desmechado y ají pique', 3500, 1, NOW(), NOW()),
('Papa Rellena de Carne',   'papa_rellena', 'Papa criolla rellena con carne molida especiada al estilo santandereano', 3500, 1, NOW(), NOW()),
('Papa Mixta',              'papa_rellena', 'Papa criolla rellena con carne molida y pollo desmechado', 4000, 1, NOW(), NOW()),
('Papa Rellena Especial',   'papa_rellena', 'Papa criolla rellena con carne, queso gratinado y hogao casero', 4500, 1, NOW(), NOW());

-- Ventas de ejemplo para que los informes tengan datos
INSERT INTO `sales` (`client_id`, `total`, `payment_method`, `sale_date`, `created_at`, `updated_at`) VALUES
(1, 7500,  'cash',     DATE_SUB(NOW(), INTERVAL 0 DAY),  NOW(), NOW()),
(2, 12000, 'card',     DATE_SUB(NOW(), INTERVAL 1 DAY),  NOW(), NOW()),
(1, 5000,  'cash',     DATE_SUB(NOW(), INTERVAL 1 DAY),  NOW(), NOW()),
(3, 9500,  'transfer', DATE_SUB(NOW(), INTERVAL 2 DAY),  NOW(), NOW()),
(1, 14000, 'cash',     DATE_SUB(NOW(), INTERVAL 3 DAY),  NOW(), NOW()),
(4, 8000,  'card',     DATE_SUB(NOW(), INTERVAL 4 DAY),  NOW(), NOW()),
(1, 6500,  'cash',     DATE_SUB(NOW(), INTERVAL 5 DAY),  NOW(), NOW()),
(5, 11000, 'cash',     DATE_SUB(NOW(), INTERVAL 6 DAY),  NOW(), NOW()),
(2, 7000,  'card',     DATE_SUB(NOW(), INTERVAL 7 DAY),  NOW(), NOW()),
(1, 9000,  'cash',     DATE_SUB(NOW(), INTERVAL 8 DAY),  NOW(), NOW());

INSERT INTO `sale_items` (`sale_id`, `product_id`, `quantity`, `unit_price`, `subtotal`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 2500, 5000, NOW(), NOW()), (1, 4, 1, 2500, 2500, NOW(), NOW()),
(2, 3, 2, 3000, 6000, NOW(), NOW()), (2, 7, 1, 3500, 3500, NOW(), NOW()), (2, 4, 1, 2500, 2500, NOW(), NOW()),
(3, 2, 2, 2500, 5000, NOW(), NOW()),
(4, 9, 1, 4000, 4000, NOW(), NOW()), (4, 1, 1, 2500, 2500, NOW(), NOW()), (4, 5, 1, 2800, 2800, NOW(), NOW()), (4, 4, 1, 2000, 200, NOW(), NOW()),
(5, 10, 2, 4500, 9000, NOW(), NOW()), (5, 3, 1, 3000, 3000, NOW(), NOW()), (5, 2, 1, 2500, 2500, NOW(), NOW()), (5, 4, 1, 2000, 2000, NOW(), NOW()), (5, 6, 1, 2500, 2500, NOW(), NOW()),
(6, 8, 1, 3500, 3500, NOW(), NOW()), (6, 7, 1, 3500, 3500, NOW(), NOW()), (6, 5, 1, 2800, 2800, NOW(), NOW()), (6, 6, 1, 2500, 2500, NOW(), NOW()), (6, 4, 1, 2000, 2000, NOW(), NOW()),
(7, 1, 1, 2500, 2500, NOW(), NOW()), (7, 5, 1, 2800, 2800, NOW(), NOW()), (7, 4, 1, 2000, 2000, NOW(), NOW()),
(8, 9, 1, 4000, 4000, NOW(), NOW()), (8, 10, 1, 4500, 4500, NOW(), NOW()), (8, 1, 1, 2500, 2500, NOW(), NOW()),
(9, 3, 1, 3000, 3000, NOW(), NOW()), (9, 7, 1, 3500, 3500, NOW(), NOW()), (9, 6, 1, 2500, 2500, NOW(), NOW()),
(10, 2, 2, 2500, 5000, NOW(), NOW()), (10, 8, 1, 3500, 3500, NOW(), NOW()), (10, 4, 1, 2000, 2000, NOW(), NOW()), (10, 6, 1, 2500, 2500, NOW(), NOW());
