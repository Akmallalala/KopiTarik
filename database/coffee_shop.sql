-- =========================================================
-- COFFEE SHOP DATABASE
-- Web Development 1 Assignment
-- Updated Schema
-- =========================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

SET NAMES utf8mb4;

CREATE DATABASE IF NOT EXISTS `coffee_shop`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

USE `coffee_shop`;

SET FOREIGN_KEY_CHECKS = 0;

-- =========================================================
-- DROP EXISTING TABLES
-- Order matters because of foreign keys
-- =========================================================

DROP TABLE IF EXISTS `reviews`;
DROP TABLE IF EXISTS `order_items`;
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `menu_item`;
DROP TABLE IF EXISTS `category`;
DROP TABLE IF EXISTS `customer`;
DROP TABLE IF EXISTS `admin`;


-- =========================================================
-- ADMIN
-- Used only for administrator authentication
-- =========================================================

CREATE TABLE `admin` (
    `admin_id` INT NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(50) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `password` VARCHAR(255) NOT NULL,

    PRIMARY KEY (`admin_id`),
    UNIQUE KEY `unique_admin_username` (`username`),
    UNIQUE KEY `unique_admin_email` (`email`)
) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;


-- =========================================================
-- CUSTOMER
-- Only registered customers are stored here.
-- Guest customers are NOT inserted into this table.
-- =========================================================

CREATE TABLE `customer` (
    `customer_id` INT NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `phone` VARCHAR(20) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (`customer_id`),
    UNIQUE KEY `unique_customer_email` (`email`)
) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;


-- =========================================================
-- CATEGORY
-- Examples:
-- Coffee Beans
-- Ground Coffee
-- Equipment
-- Accessories
-- =========================================================

CREATE TABLE `category` (
    `category_id` INT NOT NULL AUTO_INCREMENT,
    `category_name` VARCHAR(100) NOT NULL,

    PRIMARY KEY (`category_id`),
    UNIQUE KEY `unique_category_name` (`category_name`)
) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;


-- =========================================================
-- MENU ITEM / PRODUCT
-- =========================================================

CREATE TABLE `menu_item` (
    `menu_id` INT NOT NULL AUTO_INCREMENT,
    `category_id` INT NOT NULL,
    `menu_name` VARCHAR(100) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `price` DECIMAL(10,2) NOT NULL,
    `stock_quantity` INT NOT NULL DEFAULT 0,
    `availability` TINYINT(1) NOT NULL DEFAULT 1,
    `image` VARCHAR(255) DEFAULT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL
        DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (`menu_id`),

    KEY `idx_menu_category` (`category_id`),

    CONSTRAINT `fk_menu_category`
        FOREIGN KEY (`category_id`)
        REFERENCES `category` (`category_id`)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT `chk_menu_price`
        CHECK (`price` >= 0),

    CONSTRAINT `chk_menu_stock`
        CHECK (`stock_quantity` >= 0)

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;


-- =========================================================
-- ORDERS
--
-- customer_id:
--   Registered customer = their ID
--   Guest customer      = NULL
--
-- customer_name/email/phone are stored here so the order
-- keeps the customer's information at the time of purchase.
-- =========================================================

CREATE TABLE `orders` (
    `order_id` INT NOT NULL AUTO_INCREMENT,

    `customer_id` INT DEFAULT NULL,

    `customer_name` VARCHAR(100) NOT NULL,
    `customer_email` VARCHAR(100) NOT NULL,
    `customer_phone` VARCHAR(20) NOT NULL,

    `order_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    `total_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,

    `status` ENUM(
        'Pending',
        'Preparing',
        'Ready',
        'Completed',
        'Cancelled'
    ) NOT NULL DEFAULT 'Pending',

    PRIMARY KEY (`order_id`),

    KEY `idx_order_customer` (`customer_id`),
    KEY `idx_order_date` (`order_date`),
    KEY `idx_order_status` (`status`),

    CONSTRAINT `fk_order_customer`
        FOREIGN KEY (`customer_id`)
        REFERENCES `customer` (`customer_id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL,

    CONSTRAINT `chk_order_total`
        CHECK (`total_amount` >= 0)

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;


-- =========================================================
-- ORDER ITEMS
--
-- menu_name and unit_price are saved as snapshots.
-- This preserves old order information even if a product's
-- name or price changes later.
-- =========================================================

CREATE TABLE `order_items` (
    `order_item_id` INT NOT NULL AUTO_INCREMENT,
    `order_id` INT NOT NULL,

    `menu_id` INT DEFAULT NULL,

    `menu_name` VARCHAR(100) NOT NULL,

    `quantity` INT NOT NULL,
    `unit_price` DECIMAL(10,2) NOT NULL,
    `subtotal` DECIMAL(10,2) NOT NULL,

    PRIMARY KEY (`order_item_id`),

    KEY `idx_order_item_order` (`order_id`),
    KEY `idx_order_item_menu` (`menu_id`),

    CONSTRAINT `fk_order_item_order`
        FOREIGN KEY (`order_id`)
        REFERENCES `orders` (`order_id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    CONSTRAINT `fk_order_item_menu`
        FOREIGN KEY (`menu_id`)
        REFERENCES `menu_item` (`menu_id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL,

    CONSTRAINT `chk_order_item_quantity`
        CHECK (`quantity` > 0),

    CONSTRAINT `chk_order_item_price`
        CHECK (`unit_price` >= 0),

    CONSTRAINT `chk_order_item_subtotal`
        CHECK (`subtotal` >= 0)

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;


-- =========================================================
-- REVIEWS
--
-- Only registered customers can review products.
-- One customer can review each product only once.
-- =========================================================

CREATE TABLE `reviews` (
    `review_id` INT NOT NULL AUTO_INCREMENT,
    `customer_id` INT NOT NULL,
    `menu_id` INT NOT NULL,
    `rating` INT NOT NULL,
    `comment` TEXT DEFAULT NULL,
    `review_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (`review_id`),

    KEY `idx_review_customer` (`customer_id`),
    KEY `idx_review_menu` (`menu_id`),

    UNIQUE KEY `unique_customer_review`
        (`customer_id`, `menu_id`),

    CONSTRAINT `fk_review_customer`
        FOREIGN KEY (`customer_id`)
        REFERENCES `customer` (`customer_id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    CONSTRAINT `fk_review_menu`
        FOREIGN KEY (`menu_id`)
        REFERENCES `menu_item` (`menu_id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    CONSTRAINT `chk_review_rating`
        CHECK (`rating` BETWEEN 1 AND 5)

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;

SET FOREIGN_KEY_CHECKS = 1;
