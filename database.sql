-- ============================================
-- Gateway Electronics - Student Feedback DB
-- Run this in cPanel > phpMyAdmin
-- ============================================

CREATE DATABASE IF NOT EXISTS `gateway_feedback`;
USE `gateway_feedback`;

-- Events table
CREATE TABLE IF NOT EXISTS `events` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `event_name` VARCHAR(255) NOT NULL,
  `event_date` DATE,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Feedback table
CREATE TABLE IF NOT EXISTS `feedback` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `event_id` INT NOT NULL,
  `student_name` VARCHAR(255) NOT NULL,
  `student_id` VARCHAR(100) NOT NULL,
  `rating` TINYINT(1) NOT NULL CHECK (`rating` BETWEEN 1 AND 5),
  `submitted_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`event_id`) REFERENCES `events`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Admin table
CREATE TABLE IF NOT EXISTS `admins` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(100) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Default admin: username = admin | password = Admin@1234
INSERT INTO `admins` (`username`, `password_hash`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Sample events
INSERT INTO `events` (`event_name`, `event_date`, `is_active`) VALUES
('Annual Tech Symposium 2025', '2025-03-15', 1),
('Electronics Workshop - PCB Design', '2025-03-22', 1),
('Guest Lecture: IoT & Industry 4.0', '2025-04-05', 1),
('Cultural Fest - Electronica', '2025-04-12', 1);
