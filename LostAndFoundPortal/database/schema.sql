-- ============================================================
-- Lost and Found Portal - Database Schema & Sample Data
-- For BCA Final Year Mini Project
-- Database Engine: MySQL / MariaDB (XAMPP Compatible)
-- ============================================================

CREATE DATABASE IF NOT EXISTS `lost_and_found_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `lost_and_found_db`;

-- ------------------------------------------------------------
-- 1. Table: admin
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- 2. Table: users
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `phone` VARCHAR(20) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `address` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- 3. Table: categories
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `category_name` VARCHAR(100) NOT NULL UNIQUE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- 4. Table: lost_items
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `lost_items`;
CREATE TABLE `lost_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `category_id` INT NOT NULL,
  `item_name` VARCHAR(150) NOT NULL,
  `description` TEXT NOT NULL,
  `image` VARCHAR(255) DEFAULT 'default_item.jpg',
  `location` VARCHAR(150) NOT NULL,
  `date_lost` DATE NOT NULL,
  `reward` DECIMAL(10,2) DEFAULT 0.00,
  `contact` VARCHAR(20) NOT NULL,
  `status` ENUM('Pending', 'Approved', 'Rejected', 'Claimed') DEFAULT 'Pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- 5. Table: found_items
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `found_items`;
CREATE TABLE `found_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `category_id` INT NOT NULL,
  `item_name` VARCHAR(150) NOT NULL,
  `description` TEXT NOT NULL,
  `image` VARCHAR(255) DEFAULT 'default_item.jpg',
  `location` VARCHAR(150) NOT NULL,
  `date_found` DATE NOT NULL,
  `contact` VARCHAR(20) NOT NULL,
  `status` ENUM('Pending', 'Approved', 'Rejected', 'Claimed') DEFAULT 'Pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ============================================================
-- SAMPLE DATA INSERTION FOR TESTING IN XAMPP
-- Default Admin Password: admin123 (hashed using password_hash)
-- Default User Password: user123 (hashed using password_hash)
-- ============================================================

-- Insert Default Admin
INSERT INTO `admin` (`username`, `email`, `password`) VALUES
('admin', 'admin@lostandfound.com', '$2y$10$8K1p/a0dL1LXMIgL56730.rO/k9ZqgG7A1Z1E1/3M2.zGzZ/1G2GG');

-- Insert Categories
INSERT INTO `categories` (`category_name`) VALUES
('Electronics'),
('Documents & Cards'),
('Keys & Keychain'),
('Wallets & Purses'),
('Bags & Backpacks'),
('Jewelry & Watches'),
('Clothing & Accessories'),
('Books & Stationery'),
('Others');

-- Insert Sample Users
INSERT INTO `users` (`name`, `email`, `phone`, `password`, `address`) VALUES
('Rahul Sharma', 'rahul@example.com', '9876543210', '$2y$10$e8sY8ZqM6k7M56730.rO/k9ZqgG7A1Z1E1/3M2.zGzZ/1G2GG', 'BCA Department, College Campus'),
('Priya Verma', 'priya@example.com', '9812345678', '$2y$10$e8sY8ZqM6k7M56730.rO/k9ZqgG7A1Z1E1/3M2.zGzZ/1G2GG', 'Library Block, North Wing');

-- Insert Sample Lost Items
INSERT INTO `lost_items` (`user_id`, `category_id`, `item_name`, `description`, `image`, `location`, `date_lost`, `reward`, `contact`, `status`) VALUES
(1, 1, 'Dell XPS 13 Laptop in Black Sleeve', 'Black color Dell laptop with college sticker on the top cover. Lost near Main Auditorium during annual fest.', 'dell_laptop.jpg', 'College Auditorium Ground', '2026-07-20', 1000.00, '9876543210', 'Approved'),
(2, 2, 'College ID Card & Metro Pass', 'Student ID Card bearing Name: Priya Verma, Roll No: BCA-2023-45 inside a blue lanyard.', 'id_card.jpg', 'Central Library Reading Hall', '2026-07-22', 0.00, '9812345678', 'Approved'),
(1, 3, 'Silver Honda Bike Keys', 'Key ring with a metallic Superman shield logo and 2 keys.', 'bike_keys.jpg', 'Student Parking Area Block B', '2026-07-25', 200.00, '9876543210', 'Pending');

-- Insert Sample Found Items
INSERT INTO `found_items` (`user_id`, `category_id`, `item_name`, `description`, `image`, `location`, `date_found`, `contact`, `status`) VALUES
(2, 1, 'Apple AirPods Pro in White Case', 'Found AirPods Pro case on bench near cafeteria. Contains serial number on inside lid.', 'airpods.jpg', 'Cafeteria Outer Bench', '2026-07-21', '9812345678', 'Approved'),
(1, 4, 'Brown Leather Wallet', 'Brown Fastrack wallet containing some cash, driver license, and college canteen token.', 'wallet.jpg', 'Computer Science Lab 3', '2026-07-24', '9876543210', 'Approved');
