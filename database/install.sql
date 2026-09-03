-- Softwebco database schema
-- WARNING: intended for a fresh installation. Existing Softwebco tables are dropped.

SET NAMES utf8mb4;
CREATE DATABASE IF NOT EXISTS `softwebco`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;
USE `softwebco`;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `blog_posts`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `users`;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE `users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `display_name` VARCHAR(100) DEFAULT NULL,
  `profile_image` VARCHAR(255) DEFAULT 'default-avatar.png',
  `bio` TEXT DEFAULT NULL,
  `password` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `account_status` ENUM('active','suspended','deactivated') DEFAULT 'active',
  `is_locked` TINYINT(1) DEFAULT 0,
  `lock_reason` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_users_username` (`username`),
  UNIQUE KEY `uq_users_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `categories` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  `slug` VARCHAR(50) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_categories_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `blog_posts` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `content` LONGTEXT DEFAULT NULL,
  `excerpt` TEXT DEFAULT NULL,
  `category_id` INT(11) DEFAULT NULL,
  `image_url` VARCHAR(500) DEFAULT NULL,
  `author_name` VARCHAR(100) DEFAULT NULL,
  `author_image_url` VARCHAR(500) DEFAULT NULL,
  `read_time` VARCHAR(20) DEFAULT NULL,
  `post_date` DATE DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_blog_category` (`category_id`),
  KEY `idx_blog_post_date` (`post_date`),
  CONSTRAINT `fk_blog_category`
    FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- No administrator or editorial data is seeded. Create the first administrator
-- with database/create-admin.example.sql after replacing every placeholder.
