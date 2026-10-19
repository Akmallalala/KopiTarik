-- =========================================================
-- KOPITARIKBN
-- FINAL DATABASE
-- Web Development 1 Assignment
-- =========================================================
--
-- FINAL MERGED VERSION
--
-- Admin authentication
-- Registered customer accounts
-- Guest checkout support
-- Friend's original categories
-- Friend's original 23 products
-- Drink customisation
-- Historical order snapshots
-- 35 dummy orders
-- 85 order-item rows
-- Reviews table
--
-- =========================================================


SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

SET time_zone = "+00:00";

SET NAMES utf8mb4;



-- =========================================================
-- CREATE DATABASE
-- =========================================================

CREATE DATABASE IF NOT EXISTS `coffee_shop`
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;

USE `coffee_shop`;



-- =========================================================
-- DISABLE FOREIGN KEYS
-- =========================================================

SET FOREIGN_KEY_CHECKS = 0;



-- =========================================================
-- DROP OLD TABLES
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
-- =========================================================

CREATE TABLE `admin` (

    `admin_id`
        INT NOT NULL AUTO_INCREMENT,

    `username`
        VARCHAR(50) NOT NULL,

    `email`
        VARCHAR(100) NOT NULL,

    `password`
        VARCHAR(255) NOT NULL,


    PRIMARY KEY (`admin_id`),

    UNIQUE KEY `unique_admin_username`
        (`username`),

    UNIQUE KEY `unique_admin_email`
        (`email`)

)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;



-- =========================================================
-- CUSTOMER
--
-- Registered customers only.
--
-- Guest customers are NOT stored here.
-- Guest information is stored directly in orders.
-- =========================================================

CREATE TABLE `customer` (

    `customer_id`
        INT NOT NULL AUTO_INCREMENT,

    `name`
        VARCHAR(100) NOT NULL,

    `email`
        VARCHAR(100) NOT NULL,

    `phone`
        VARCHAR(20) NOT NULL,

    `password`
        VARCHAR(255) NOT NULL,

    `created_at`
        TIMESTAMP NOT NULL
        DEFAULT CURRENT_TIMESTAMP,


    PRIMARY KEY (`customer_id`),

    UNIQUE KEY `unique_customer_email`
        (`email`)

)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;



-- =========================================================
-- CATEGORY
-- =========================================================

CREATE TABLE `category` (

    `category_id`
        INT NOT NULL AUTO_INCREMENT,

    `category_name`
        VARCHAR(100) NOT NULL,


    PRIMARY KEY (`category_id`),

    UNIQUE KEY `unique_category_name`
        (`category_name`)

)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;



-- =========================================================
-- MENU ITEM / PRODUCT
-- =========================================================

CREATE TABLE `menu_item` (

    `menu_id`
        INT NOT NULL AUTO_INCREMENT,

    `category_id`
        INT NOT NULL,

    `menu_name`
        VARCHAR(100) NOT NULL,

    `description`
        TEXT DEFAULT NULL,

    `price`
        DECIMAL(10,2) NOT NULL,

    `stock_quantity`
        INT NOT NULL DEFAULT 0,

    `availability`
        TINYINT(1) NOT NULL DEFAULT 1,

    `image`
        VARCHAR(255) DEFAULT NULL,

    `created_at`
        TIMESTAMP NOT NULL
        DEFAULT CURRENT_TIMESTAMP,

    `updated_at`
        TIMESTAMP NOT NULL
        DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,


    PRIMARY KEY (`menu_id`),

    KEY `idx_menu_category`
        (`category_id`),


    CONSTRAINT `fk_menu_category`

        FOREIGN KEY (`category_id`)

        REFERENCES `category`
            (`category_id`)

        ON UPDATE CASCADE

        ON DELETE RESTRICT,


    CONSTRAINT `chk_menu_price`

        CHECK (`price` >= 0),


    CONSTRAINT `chk_menu_stock`

        CHECK (`stock_quantity` >= 0),


    CONSTRAINT `chk_menu_availability`

        CHECK (`availability` IN (0, 1))

)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;



-- =========================================================
-- ORDERS
--
-- REGISTERED CUSTOMER
-- customer_id = customer ID
--
-- GUEST
-- customer_id = NULL
--
-- Customer name/email/phone are always stored so the
-- order remains readable even if the account changes.
-- =========================================================

CREATE TABLE `orders` (

    `order_id`
        INT NOT NULL AUTO_INCREMENT,

    `customer_id`
        INT DEFAULT NULL,

    `customer_name`
        VARCHAR(100) NOT NULL,

    `customer_email`
        VARCHAR(100) NOT NULL,

    `customer_phone`
        VARCHAR(20) NOT NULL,

    `order_date`
        TIMESTAMP NOT NULL
        DEFAULT CURRENT_TIMESTAMP,

    `total_amount`
        DECIMAL(10,2) NOT NULL
        DEFAULT 0.00,

    `status`
        ENUM(
            'Pending',
            'Preparing',
            'Ready',
            'Completed',
            'Cancelled'
        )
        NOT NULL
        DEFAULT 'Pending',


    PRIMARY KEY (`order_id`),

    KEY `idx_order_customer`
        (`customer_id`),

    KEY `idx_order_email`
        (`customer_email`),

    KEY `idx_order_date`
        (`order_date`),

    KEY `idx_order_status`
        (`status`),


    CONSTRAINT `fk_order_customer`

        FOREIGN KEY (`customer_id`)

        REFERENCES `customer`
            (`customer_id`)

        ON UPDATE CASCADE

        ON DELETE SET NULL,


    CONSTRAINT `chk_order_total`

        CHECK (`total_amount` >= 0)

)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;



-- =========================================================
-- ORDER ITEMS
--
-- menu_name = historical snapshot
--
-- This means reports and old receipts still work if
-- an admin later edits or deletes a menu item.
--
-- Drink customisation is retained from customer side.
-- =========================================================

CREATE TABLE `order_items` (

    `order_item_id`
        INT NOT NULL AUTO_INCREMENT,

    `order_id`
        INT NOT NULL,

    `menu_id`
        INT DEFAULT NULL,

    `menu_name`
        VARCHAR(100) NOT NULL,

    `quantity`
        INT NOT NULL,

    `unit_price`
        DECIMAL(10,2) NOT NULL,

    `subtotal`
        DECIMAL(10,2) NOT NULL,


    -- DRINK CUSTOMISATION

    `drink_size`
        VARCHAR(20)
        DEFAULT 'Medium',

    `milk_type`
        VARCHAR(50)
        DEFAULT '2% Milk',

    `add_ins`
        VARCHAR(100)
        DEFAULT 'Ice',

    `espresso_shots`
        INT DEFAULT 2,

    `syrup_pumps`
        INT DEFAULT 2,

    `topping`
        VARCHAR(100)
        DEFAULT 'None',

    `barista_note`
        TEXT DEFAULT NULL,


    PRIMARY KEY (`order_item_id`),

    KEY `idx_order_item_order`
        (`order_id`),

    KEY `idx_order_item_menu`
        (`menu_id`),


    CONSTRAINT `fk_order_item_order`

        FOREIGN KEY (`order_id`)

        REFERENCES `orders`
            (`order_id`)

        ON UPDATE CASCADE

        ON DELETE CASCADE,


    CONSTRAINT `fk_order_item_menu`

        FOREIGN KEY (`menu_id`)

        REFERENCES `menu_item`
            (`menu_id`)

        ON UPDATE CASCADE

        ON DELETE SET NULL,


    CONSTRAINT `chk_order_item_quantity`

        CHECK (`quantity` > 0),


    CONSTRAINT `chk_order_item_price`

        CHECK (`unit_price` >= 0),


    CONSTRAINT `chk_order_item_subtotal`

        CHECK (`subtotal` >= 0),


    CONSTRAINT `chk_espresso_shots`

        CHECK (
            `espresso_shots` IS NULL
            OR `espresso_shots` >= 0
        ),


    CONSTRAINT `chk_syrup_pumps`

        CHECK (
            `syrup_pumps` IS NULL
            OR `syrup_pumps` >= 0
        )

)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;



-- =========================================================
-- REVIEWS
--
-- Registered customers only.
-- One customer can review each menu item once.
-- =========================================================

CREATE TABLE `reviews` (

    `review_id`
        INT NOT NULL AUTO_INCREMENT,

    `customer_id`
        INT NOT NULL,

    `menu_id`
        INT NOT NULL,

    `rating`
        INT NOT NULL,

    `comment`
        TEXT DEFAULT NULL,

    `review_date`
        TIMESTAMP NOT NULL
        DEFAULT CURRENT_TIMESTAMP,


    PRIMARY KEY (`review_id`),

    KEY `idx_review_customer`
        (`customer_id`),

    KEY `idx_review_menu`
        (`menu_id`),


    UNIQUE KEY `unique_customer_review`
        (`customer_id`, `menu_id`),


    CONSTRAINT `fk_review_customer`

        FOREIGN KEY (`customer_id`)

        REFERENCES `customer`
            (`customer_id`)

        ON UPDATE CASCADE

        ON DELETE CASCADE,


    CONSTRAINT `fk_review_menu`

        FOREIGN KEY (`menu_id`)

        REFERENCES `menu_item`
            (`menu_id`)

        ON UPDATE CASCADE

        ON DELETE CASCADE,


    CONSTRAINT `chk_review_rating`

        CHECK (
            `rating`
            BETWEEN 1 AND 5
        )

)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;



-- =========================================================
-- ADMIN ACCOUNT
--
-- EMAIL:
-- admin@admin.com
--
-- PASSWORD:
-- password
--
-- Stored using bcrypt.
-- =========================================================

INSERT INTO `admin`
(
    `username`,
    `email`,
    `password`
)
VALUES
(
    'admin',
    'admin@admin.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.'
);



-- =========================================================
-- REGISTERED CUSTOMERS
-- Friend's existing customer records
-- =========================================================

INSERT INTO `customer`
(
    `customer_id`,
    `name`,
    `email`,
    `phone`,
    `password`,
    `created_at`
)
VALUES

(
    1,
    'izzati',
    'nzztaq@gmail.com',
    '8902181',
    '$2y$10$qnEDuqNnogVWz./istceyeIZSaJYgl2jFNxA2DDCKiJ0trGRKyfHi',
    '2026-10-17 02:23:53'
),

(
    2,
    'zati aqilah',
    'izzatiaq@gmail.com',
    '8636306',
    '$2y$10$q0bnJ.V0HT0ShiJajAu/zeb1pVOU9LusxUIgKE2QGYUCV3BtPk1d2',
    '2026-10-17 02:24:56'
);



-- =========================================================
-- CATEGORIES
-- Friend's original categories
-- =========================================================

INSERT INTO `category`
(
    `category_id`,
    `category_name`
)
VALUES

(1, 'Iced Coffee'),

(2, 'Hot Coffee'),

(3, 'Frappes'),

(4, 'Matcha'),

(5, 'Refreshing Tea');



-- =========================================================
-- PRODUCTS
-- Friend's original products
--
-- Raspberry Tea has stock = 0,
-- therefore availability has been corrected to 0.
-- =========================================================

INSERT INTO `menu_item`
(
    `menu_id`,
    `category_id`,
    `menu_name`,
    `description`,
    `price`,
    `stock_quantity`,
    `availability`,
    `image`
)
VALUES

(
    1,
    1,
    'Spanish Latte ',
    'Rich espresso blended with creamy milk and sweetened condensed milk for a smooth indulgent taste.',
    5.50,
    40,
    1,
    'spanish-latte.png'
),

(
    2,
    1,
    'Vanilla Latte',
    'Smooth espresso combined with creamy milk and a touch of sweet vanilla.',
    5.00,
    38,
    1,
    'vanilla-latte.png'
),

(
    3,
    1,
    'Americano ',
    'Bold espresso blended with water for a smooth, rich and refreshing coffee experience.',
    4.50,
    40,
    1,
    'americanoo.png'
),

(
    4,
    2,
    'Hot Cappuccino',
    'Rich espresso topped with steamed milk and a thick layer of velvety foam.',
    5.00,
    40,
    1,
    'cappuccino.png'
),

(
    5,
    1,
    'Caramel Vanilla Latte',
    'Smooth espresso blended with creamy milk, sweet caramel and aromatic vanilla.',
    5.50,
    40,
    1,
    'caramel-vanilla.png'
),

(
    6,
    3,
    'Strawberry Cream Frappe',
    'A refreshing ice-blended with sweet strawberry flavour and creamy goodness.',
    6.50,
    40,
    1,
    'strawberry-frappe.png'
),

(
    7,
    1,
    'Caramel Macchiato',
    'Creamy steamed milk layered with rich espresso, vanilla and caramel.',
    5.50,
    40,
    1,
    'caramel-macc.png'
),

(
    8,
    1,
    'Brown Sugar Shaken Espresso',
    'Bold espresso shaken with brown sugar and ice, finished with creamy milk for a smooth, caramel-like sweetness.',
    6.00,
    40,
    1,
    'brown-sugar.png'
),

(
    9,
    2,
    'Hot Mocha',
    'A delicious combination of rich espresso, smooth chocolate and creamy steamed milk.',
    4.50,
    40,
    1,
    'hot-mocha.png'
),

(
    10,
    4,
    'Matcha Latte',
    'Premium matcha blended with creamy milk for a smooth, earthy and refreshing taste.',
    6.00,
    40,
    1,
    'Matcha Latte.png'
),

(
    11,
    4,
    'Dirty Matcha Latte',
    'A perfect fusion of earthy matcha, creamy milk and a bold shot of espresso.',
    6.50,
    39,
    1,
    'dirty-matcha.png'
),

(
    12,
    5,
    'Swedish Berry',
    'A fruity and refreshing berry-inspired drink with a delightful balance of sweetness and tanginess.',
    3.50,
    40,
    1,
    'swedish-berry.png'
),

(
    13,
    3,
    'Cookies & Creme Frappe',
    'A creamy ice-blended treat packed with delicious cookie flavours.',
    6.50,
    40,
    1,
    'cookie-and-cream.png'
),

(
    14,
    2,
    'Hot Flat White',
    'Bold espresso blended with silky steamed milk and delicate microfoam for a rich, smooth finish.',
    4.50,
    40,
    1,
    'flat-white.png'
),

(
    15,
    5,
    'Passion Fruit Tea',
    'Refreshing tea infused with tropical passion fruit flavour for a sweet and tangy experience.',
    3.50,
    40,
    1,
    'passion-fruit.png'
),

(
    16,
    3,
    'Nutella Frappe ',
    'A rich and creamy ice-blended treat packed with indulgent Nutella hazelnut chocolate flavour.',
    6.50,
    40,
    1,
    'nutella.png'
),

(
    17,
    3,
    'White Chocolate Frappe ',
    'A smooth and creamy ice-blended drink infused with rich white chocolate.',
    6.50,
    40,
    1,
    'white-choco.png'
),

(
    18,
    4,
    'Pistachio Matcha Latte ',
    'A smooth and creamy blend of earthy matcha, rich pistachio and velvety milk.',
    6.50,
    40,
    1,
    'pistachio-matcha.png'
),

(
    19,
    3,
    'Tiramisu Frappe',
    'A rich and creamy ice-blended treat inspired by classic tiramisu, combining bold coffee, smooth mascarpone flavours.',
    7.00,
    40,
    1,
    'tiramisu.png'
),

(
    20,
    4,
    'Matcha Float',
    'A refreshing blend of earthy matcha and creamy milk, topped with a scoop of velvety vanilla ice cream.',
    7.00,
    40,
    1,
    'matcha-float.png'
),

(
    21,
    1,
    'Vietnamese Iced Coffee',
    'A bold and aromatic blend of rich Vietnamese coffee and sweetened condensed milk.',
    5.00,
    40,
    1,
    'vietnamese.png'
),

(
    22,
    5,
    'Raspberry Tea',
    'A refreshing blend of freshly brewed tea and sweet, tangy raspberry flavour, served over ice for a fruity and refreshing delight.',
    3.50,
    0,
    0,
    'raspberry.png'
),

(
    23,
    1,
    'Biscoff Latte',
    'Rich espresso blended with creamy milk and indulgent Biscoff cookie butter for a sweet, caramelised biscuit flavour.',
    6.50,
    40,
    1,
    'biscoff.png'
);



-- =========================================================
-- DUMMY ORDERS
--
-- 35 TOTAL
--
-- Completed = 33
-- Cancelled = 2
--
-- =========================================================

INSERT INTO `orders`
(
    `order_id`,
    `customer_id`,
    `customer_name`,
    `customer_email`,
    `customer_phone`,
    `order_date`,
    `total_amount`,
    `status`
)
VALUES

(1, 1, 'izzati',
'nzztaq@gmail.com', '8902181',
'2026-10-01 09:10:00', 10.50, 'Completed'),

(2, 2, 'zati aqilah',
'izzatiaq@gmail.com', '8636306',
'2026-10-01 13:25:00', 13.50, 'Completed'),

(3, NULL, 'Sofia Rahman',
'sofia@example.com', '8113344',
'2026-10-02 10:05:00', 13.00, 'Completed'),

(4, 1, 'izzati',
'nzztaq@gmail.com', '8902181',
'2026-10-02 15:45:00', 14.50, 'Completed'),

(5, NULL, 'Amir Hakim',
'amir.guest@example.com', '8224466',
'2026-10-03 11:20:00', 16.50, 'Completed'),

(6, 2, 'zati aqilah',
'izzatiaq@gmail.com', '8636306',
'2026-10-03 16:30:00', 17.00, 'Completed'),

(7, NULL, 'Lina Yusuf',
'lina@example.com', '8335577',
'2026-10-04 09:55:00', 13.00, 'Completed'),

(8, 1, 'izzati',
'nzztaq@gmail.com', '8902181',
'2026-10-04 14:10:00', 14.50, 'Completed'),

(9, NULL, 'Nurul Huda',
'nurul@example.com', '8557799',
'2026-10-05 12:00:00', 13.50, 'Completed'),

(10, 2, 'zati aqilah',
'izzatiaq@gmail.com', '8636306',
'2026-10-05 17:20:00', 20.00, 'Completed'),

(11, NULL, 'Irfan Azmi',
'irfan@example.com', '8221199',
'2026-10-06 10:40:00', 16.50, 'Completed'),

(12, 1, 'izzati',
'nzztaq@gmail.com', '8902181',
'2026-10-06 15:15:00', 14.50, 'Completed'),

(13, 2, 'zati aqilah',
'izzatiaq@gmail.com', '8636306',
'2026-10-07 09:30:00', 16.50, 'Completed'),

(14, NULL, 'Hana Sofea',
'hana@example.com', '8776655',
'2026-10-07 13:50:00', 15.50, 'Completed'),

(15, 1, 'izzati',
'nzztaq@gmail.com', '8902181',
'2026-10-08 11:10:00', 19.50, 'Completed'),

(16, NULL, 'Faris Hadi',
'faris@example.com', '8665544',
'2026-10-08 16:45:00', 15.00, 'Completed'),

(17, 2, 'zati aqilah',
'izzatiaq@gmail.com', '8636306',
'2026-10-09 10:25:00', 13.50, 'Completed'),

(18, NULL, 'Alya Nabilah',
'alya@example.com', '8992211',
'2026-10-09 14:30:00', 16.50, 'Completed'),

(19, 1, 'izzati',
'nzztaq@gmail.com', '8902181',
'2026-10-10 12:15:00', 16.00, 'Cancelled'),

(20, NULL, 'Zikri Danish',
'zikri@example.com', '8442200',
'2026-10-10 18:05:00', 13.00, 'Completed'),

(21, 2, 'zati aqilah',
'izzatiaq@gmail.com', '8636306',
'2026-10-11 09:40:00', 18.00, 'Completed'),

(22, NULL, 'Mariam Izzati',
'mariam@example.com', '8331100',
'2026-10-11 14:50:00', 15.50, 'Completed'),

(23, 1, 'izzati',
'nzztaq@gmail.com', '8902181',
'2026-10-12 10:20:00', 13.00, 'Completed'),

(24, NULL, 'Haziq Firdaus',
'haziq@example.com', '8556600',
'2026-10-12 16:10:00', 19.50, 'Completed'),

(25, 2, 'zati aqilah',
'izzatiaq@gmail.com', '8636306',
'2026-10-13 11:35:00', 14.50, 'Completed'),

(26, NULL, 'Sarah Aqilah',
'sarah@example.com', '8778800',
'2026-10-13 15:55:00', 13.50, 'Completed'),

(27, 1, 'izzati',
'nzztaq@gmail.com', '8902181',
'2026-10-14 09:15:00', 14.50, 'Completed'),

(28, NULL, 'Adam Syafiq',
'adam@example.com', '8112233',
'2026-10-14 13:45:00', 16.50, 'Completed'),

(29, 2, 'zati aqilah',
'izzatiaq@gmail.com', '8636306',
'2026-10-15 10:50:00', 17.50, 'Completed'),

(30, NULL, 'Aisyah Farhana',
'aisyah@example.com', '8223344',
'2026-10-15 17:30:00', 12.50, 'Cancelled'),

(31, 1, 'izzati',
'nzztaq@gmail.com', '8902181',
'2026-10-16 11:05:00', 16.50, 'Completed'),

(32, NULL, 'Danish Hakeem',
'danish@example.com', '8334455',
'2026-10-17 09:25:00', 14.00, 'Completed'),

(33, 2, 'zati aqilah',
'izzatiaq@gmail.com', '8636306',
'2026-10-17 15:40:00', 18.00, 'Completed'),

(34, NULL, 'Amirah Haziqah',
'amirah@example.com', '8445566',
'2026-10-18 12:30:00', 17.00, 'Completed'),

(35, 1, 'izzati',
'nzztaq@gmail.com', '8902181',
'2026-10-19 10:15:00', 18.50, 'Completed');



-- =========================================================
-- ORDER ITEMS
--
-- 85 rows
-- =========================================================

INSERT INTO `order_items`
(
    `order_id`,
    `menu_id`,
    `menu_name`,
    `quantity`,
    `unit_price`,
    `subtotal`,
    `drink_size`,
    `milk_type`,
    `add_ins`,
    `espresso_shots`,
    `syrup_pumps`,
    `topping`,
    `barista_note`
)
VALUES


-- ORDER 1

(1, 1, 'Spanish Latte',
1, 5.50, 5.50,
'Medium', '2% Milk', 'Ice',
2, 2, 'None', NULL),

(1, 4, 'Hot Cappuccino',
1, 5.00, 5.00,
'Medium', '2% Milk', 'None',
2, 0, 'Cinnamon', NULL),


-- ORDER 2

(2, 2, 'Vanilla Latte',
2, 5.00, 10.00,
'Medium', '2% Milk', 'Ice',
2, 2, 'None', NULL),

(2, 12, 'Swedish Berry',
1, 3.50, 3.50,
'Medium', 'None', 'Ice',
0, 1, 'None', NULL),


-- ORDER 3

(3, 10, 'Matcha Latte',
1, 6.00, 6.00,
'Medium', 'Oat Milk', 'Ice',
0, 1, 'None', NULL),

(3, 19, 'Tiramisu Frappe',
1, 7.00, 7.00,
'Large', 'Whole Milk', 'Ice',
1, 0, 'Cocoa Powder', NULL),


-- ORDER 4

(4, 7, 'Caramel Macchiato',
1, 5.50, 5.50,
'Medium', '2% Milk', 'Ice',
2, 2, 'Caramel Drizzle', NULL),

(4, 3, 'Americano',
2, 4.50, 9.00,
'Medium', 'None', 'Ice',
2, 0, 'None', NULL),


-- ORDER 5

(5, 6, 'Strawberry Cream Frappe',
2, 6.50, 13.00,
'Large', 'Whole Milk', 'Ice',
0, 0, 'Whipped Cream', NULL),

(5, 15, 'Passion Fruit Tea',
1, 3.50, 3.50,
'Medium', 'None', 'Ice',
0, 1, 'None', NULL),


-- ORDER 6

(6, 8, 'Brown Sugar Shaken Espresso',
1, 6.00, 6.00,
'Large', 'Oat Milk', 'Ice',
3, 2, 'Cinnamon', NULL),

(6, 11, 'Dirty Matcha Latte',
1, 6.50, 6.50,
'Medium', 'Oat Milk', 'Ice',
1, 1, 'None', NULL),

(6, 14, 'Hot Flat White',
1, 4.50, 4.50,
'Medium', 'Whole Milk', 'None',
2, 0, 'None', NULL),


-- ORDER 7

(7, 13, 'Cookies & Creme Frappe',
1, 6.50, 6.50,
'Large', 'Whole Milk', 'Ice',
0, 0, 'Whipped Cream', NULL),

(7, 23, 'Biscoff Latte',
1, 6.50, 6.50,
'Medium', '2% Milk', 'Ice',
2, 2, 'Biscoff Crumbs', NULL),


-- ORDER 8

(8, 21, 'Vietnamese Iced Coffee',
2, 5.00, 10.00,
'Medium', 'Whole Milk', 'Ice',
2, 0, 'None', NULL),

(8, 9, 'Hot Mocha',
1, 4.50, 4.50,
'Medium', '2% Milk', 'None',
2, 2, 'Whipped Cream', NULL),


-- ORDER 9

(9, 18, 'Pistachio Matcha Latte',
1, 6.50, 6.50,
'Medium', 'Oat Milk', 'Ice',
0, 1, 'Pistachio Crumbs', NULL),

(9, 20, 'Matcha Float',
1, 7.00, 7.00,
'Medium', 'Whole Milk', 'Ice',
0, 1, 'Vanilla Ice Cream', NULL),


-- ORDER 10

(10, 16, 'Nutella Frappe',
1, 6.50, 6.50,
'Large', 'Whole Milk', 'Ice',
0, 0, 'Whipped Cream', NULL),

(10, 17, 'White Chocolate Frappe',
1, 6.50, 6.50,
'Large', 'Whole Milk', 'Ice',
0, 0, 'Whipped Cream', NULL),

(10, 12, 'Swedish Berry',
2, 3.50, 7.00,
'Medium', 'None', 'Ice',
0, 1, 'None', NULL),


-- ORDER 11

(11, 1, 'Spanish Latte',
2, 5.50, 11.00,
'Large', '2% Milk', 'Ice',
2, 2, 'None', NULL),

(11, 5, 'Caramel Vanilla Latte',
1, 5.50, 5.50,
'Medium', 'Oat Milk', 'Ice',
2, 3, 'Caramel Drizzle', NULL),


-- ORDER 12

(12, 2, 'Vanilla Latte',
1, 5.00, 5.00,
'Medium', '2% Milk', 'Ice',
2, 2, 'None', NULL),

(12, 10, 'Matcha Latte',
1, 6.00, 6.00,
'Medium', 'Oat Milk', 'Ice',
0, 1, 'None', NULL),

(12, 15, 'Passion Fruit Tea',
1, 3.50, 3.50,
'Medium', 'None', 'Ice',
0, 1, 'None', NULL),


-- ORDER 13

(13, 3, 'Americano',
1, 4.50, 4.50,
'Medium', 'None', 'Ice',
2, 0, 'None', NULL),

(13, 4, 'Hot Cappuccino',
1, 5.00, 5.00,
'Medium', '2% Milk', 'None',
2, 0, 'Cinnamon', NULL),

(13, 19, 'Tiramisu Frappe',
1, 7.00, 7.00,
'Large', 'Whole Milk', 'Ice',
1, 0, 'Cocoa Powder', NULL),


-- ORDER 14

(14, 8, 'Brown Sugar Shaken Espresso',
2, 6.00, 12.00,
'Large', 'Oat Milk', 'Ice',
3, 2, 'Cinnamon', NULL),

(14, 22, 'Raspberry Tea',
1, 3.50, 3.50,
'Medium', 'None', 'Ice',
0, 1, 'None', NULL),


-- ORDER 15

(15, 11, 'Dirty Matcha Latte',
1, 6.50, 6.50,
'Medium', 'Oat Milk', 'Ice',
1, 1, 'None', NULL),

(15, 23, 'Biscoff Latte',
2, 6.50, 13.00,
'Medium', '2% Milk', 'Ice',
2, 2, 'Biscoff Crumbs', NULL),


-- ORDER 16

(16, 7, 'Caramel Macchiato',
1, 5.50, 5.50,
'Medium', '2% Milk', 'Ice',
2, 2, 'Caramel Drizzle', NULL),

(16, 21, 'Vietnamese Iced Coffee',
1, 5.00, 5.00,
'Medium', 'Whole Milk', 'Ice',
2, 0, 'None', NULL),

(16, 14, 'Hot Flat White',
1, 4.50, 4.50,
'Medium', 'Whole Milk', 'None',
2, 0, 'None', NULL),


-- ORDER 17

(17, 20, 'Matcha Float',
1, 7.00, 7.00,
'Medium', 'Whole Milk', 'Ice',
0, 1, 'Vanilla Ice Cream', NULL),

(17, 16, 'Nutella Frappe',
1, 6.50, 6.50,
'Large', 'Whole Milk', 'Ice',
0, 0, 'Whipped Cream', NULL),


-- ORDER 18

(18, 6, 'Strawberry Cream Frappe',
1, 6.50, 6.50,
'Large', 'Whole Milk', 'Ice',
0, 0, 'Whipped Cream', NULL),

(18, 13, 'Cookies & Creme Frappe',
1, 6.50, 6.50,
'Large', 'Whole Milk', 'Ice',
0, 0, 'Whipped Cream', NULL),

(18, 12, 'Swedish Berry',
1, 3.50, 3.50,
'Medium', 'None', 'Ice',
0, 1, 'None', NULL),


-- ORDER 19 - CANCELLED

(19, 9, 'Hot Mocha',
2, 4.50, 9.00,
'Medium', '2% Milk', 'None',
2, 2, 'Whipped Cream', NULL),

(19, 15, 'Passion Fruit Tea',
2, 3.50, 7.00,
'Medium', 'None', 'Ice',
0, 1, 'None', NULL),


-- ORDER 20

(20, 18, 'Pistachio Matcha Latte',
1, 6.50, 6.50,
'Medium', 'Oat Milk', 'Ice',
0, 1, 'Pistachio Crumbs', NULL),

(20, 17, 'White Chocolate Frappe',
1, 6.50, 6.50,
'Large', 'Whole Milk', 'Ice',
0, 0, 'Whipped Cream', NULL),


-- ORDER 21

(21, 1, 'Spanish Latte',
1, 5.50, 5.50,
'Medium', '2% Milk', 'Ice',
2, 2, 'None', NULL),

(21, 8, 'Brown Sugar Shaken Espresso',
1, 6.00, 6.00,
'Large', 'Oat Milk', 'Ice',
3, 2, 'Cinnamon', NULL),

(21, 23, 'Biscoff Latte',
1, 6.50, 6.50,
'Medium', '2% Milk', 'Ice',
2, 2, 'Biscoff Crumbs', NULL),


-- ORDER 22

(22, 2, 'Vanilla Latte',
2, 5.00, 10.00,
'Medium', '2% Milk', 'Ice',
2, 2, 'None', NULL),

(22, 5, 'Caramel Vanilla Latte',
1, 5.50, 5.50,
'Large', 'Oat Milk', 'Ice',
2, 3, 'Caramel Drizzle', NULL),


-- ORDER 23

(23, 3, 'Americano',
1, 4.50, 4.50,
'Medium', 'None', 'Ice',
2, 0, 'None', NULL),

(23, 21, 'Vietnamese Iced Coffee',
1, 5.00, 5.00,
'Medium', 'Whole Milk', 'Ice',
2, 0, 'None', NULL),

(23, 12, 'Swedish Berry',
1, 3.50, 3.50,
'Medium', 'None', 'Ice',
0, 1, 'None', NULL),


-- ORDER 24

(24, 10, 'Matcha Latte',
1, 6.00, 6.00,
'Medium', 'Oat Milk', 'Ice',
0, 1, 'None', NULL),

(24, 11, 'Dirty Matcha Latte',
1, 6.50, 6.50,
'Medium', 'Oat Milk', 'Ice',
1, 1, 'None', NULL),

(24, 20, 'Matcha Float',
1, 7.00, 7.00,
'Medium', 'Whole Milk', 'Ice',
0, 1, 'Vanilla Ice Cream', NULL),


-- ORDER 25

(25, 4, 'Hot Cappuccino',
2, 5.00, 10.00,
'Medium', '2% Milk', 'None',
2, 0, 'Cinnamon', NULL),

(25, 14, 'Hot Flat White',
1, 4.50, 4.50,
'Medium', 'Whole Milk', 'None',
2, 0, 'None', NULL),


-- ORDER 26

(26, 19, 'Tiramisu Frappe',
1, 7.00, 7.00,
'Large', 'Whole Milk', 'Ice',
1, 0, 'Cocoa Powder', NULL),

(26, 13, 'Cookies & Creme Frappe',
1, 6.50, 6.50,
'Large', 'Whole Milk', 'Ice',
0, 0, 'Whipped Cream', NULL),


-- ORDER 27

(27, 1, 'Spanish Latte',
1, 5.50, 5.50,
'Medium', '2% Milk', 'Ice',
2, 2, 'None', NULL),

(27, 7, 'Caramel Macchiato',
1, 5.50, 5.50,
'Medium', '2% Milk', 'Ice',
2, 2, 'Caramel Drizzle', NULL),

(27, 15, 'Passion Fruit Tea',
1, 3.50, 3.50,
'Medium', 'None', 'Ice',
0, 1, 'None', NULL),


-- ORDER 28

(28, 16, 'Nutella Frappe',
2, 6.50, 13.00,
'Large', 'Whole Milk', 'Ice',
0, 0, 'Whipped Cream', NULL),

(28, 22, 'Raspberry Tea',
1, 3.50, 3.50,
'Medium', 'None', 'Ice',
0, 1, 'None', NULL),


-- ORDER 29

(29, 8, 'Brown Sugar Shaken Espresso',
1, 6.00, 6.00,
'Large', 'Oat Milk', 'Ice',
3, 2, 'Cinnamon', NULL),

(29, 21, 'Vietnamese Iced Coffee',
1, 5.00, 5.00,
'Medium', 'Whole Milk', 'Ice',
2, 0, 'None', NULL),

(29, 23, 'Biscoff Latte',
1, 6.50, 6.50,
'Medium', '2% Milk', 'Ice',
2, 2, 'Biscoff Crumbs', NULL),


-- ORDER 30 - CANCELLED

(30, 10, 'Matcha Latte',
1, 6.00, 6.00,
'Medium', 'Oat Milk', 'Ice',
0, 1, 'None', NULL),

(30, 18, 'Pistachio Matcha Latte',
1, 6.50, 6.50,
'Medium', 'Oat Milk', 'Ice',
0, 1, 'Pistachio Crumbs', NULL),


-- ORDER 31

(31, 6, 'Strawberry Cream Frappe',
1, 6.50, 6.50,
'Large', 'Whole Milk', 'Ice',
0, 0, 'Whipped Cream', NULL),

(31, 17, 'White Chocolate Frappe',
1, 6.50, 6.50,
'Large', 'Whole Milk', 'Ice',
0, 0, 'Whipped Cream', NULL),

(31, 12, 'Swedish Berry',
1, 3.50, 3.50,
'Medium', 'None', 'Ice',
0, 1, 'None', NULL),


-- ORDER 32

(32, 2, 'Vanilla Latte',
1, 5.00, 5.00,
'Medium', '2% Milk', 'Ice',
2, 2, 'None', NULL),

(32, 9, 'Hot Mocha',
1, 4.50, 4.50,
'Medium', '2% Milk', 'None',
2, 2, 'Whipped Cream', NULL),

(32, 14, 'Hot Flat White',
1, 4.50, 4.50,
'Medium', 'Whole Milk', 'None',
2, 0, 'None', NULL),


-- ORDER 33

(33, 5, 'Caramel Vanilla Latte',
2, 5.50, 11.00,
'Large', 'Oat Milk', 'Ice',
2, 3, 'Caramel Drizzle', NULL),

(33, 19, 'Tiramisu Frappe',
1, 7.00, 7.00,
'Large', 'Whole Milk', 'Ice',
1, 0, 'Cocoa Powder', NULL),


-- ORDER 34

(34, 11, 'Dirty Matcha Latte',
1, 6.50, 6.50,
'Medium', 'Oat Milk', 'Ice',
1, 1, 'None', NULL),

(34, 20, 'Matcha Float',
1, 7.00, 7.00,
'Medium', 'Whole Milk', 'Ice',
0, 1, 'Vanilla Ice Cream', NULL),

(34, 15, 'Passion Fruit Tea',
1, 3.50, 3.50,
'Medium', 'None', 'Ice',
0, 1, 'None', NULL),


-- ORDER 35

(35, 1, 'Spanish Latte',
1, 5.50, 5.50,
'Medium', '2% Milk', 'Ice',
2, 2, 'None', NULL),

(35, 23, 'Biscoff Latte',
1, 6.50, 6.50,
'Medium', '2% Milk', 'Ice',
2, 2, 'Biscoff Crumbs', NULL),

(35, 13, 'Cookies & Creme Frappe',
1, 6.50, 6.50,
'Large', 'Whole Milk', 'Ice',
0, 0, 'Whipped Cream', NULL);



-- =========================================================
-- RE-ENABLE FOREIGN KEYS
-- =========================================================

SET FOREIGN_KEY_CHECKS = 1;



-- =========================================================
-- FINAL DATABASE SUMMARY
--
-- Admin:                 1
-- Registered customers:  2
-- Categories:             5
-- Products:              23
-- Orders:                35
-- Completed orders:      33
-- Cancelled orders:       2
-- Order-item rows:       85
-- Completed units sold:  93
-- Completed revenue:   $513.00
--
-- =========================================================