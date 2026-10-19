-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 19, 2026 at 02:51 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `coffee_shop`
--
CREATE DATABASE IF NOT EXISTS `coffee_shop` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `coffee_shop`;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `username`, `email`, `password`) VALUES
(1, 'admin', 'admin@admin.com', 'admin123');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category_name`) VALUES
(1, 'Iced Coffee'),
(2, 'Hot Coffee'),
(3, 'Frappes'),
(4, 'Matcha'),
(5, 'Refreshing Tea');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_id`, `name`, `email`, `phone`, `password`, `created_at`) VALUES
(1, 'izzati', 'nzztaq@gmail.com', '8902181', '$2y$10$qnEDuqNnogVWz./istceyeIZSaJYgl2jFNxA2DDCKiJ0trGRKyfHi', '2026-10-17 02:23:53'),
(2, 'zati aqilah', 'izzatiaq@gmail.com', '8636306', '$2y$10$q0bnJ.V0HT0ShiJajAu/zeb1pVOU9LusxUIgKE2QGYUCV3BtPk1d2', '2026-10-17 02:24:56');

-- --------------------------------------------------------

--
-- Table structure for table `menu_item`
--

CREATE TABLE `menu_item` (
  `menu_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `menu_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `availability` tinyint(1) NOT NULL DEFAULT 1,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_item`
--

INSERT INTO `menu_item` (`menu_id`, `category_id`, `menu_name`, `description`, `price`, `stock_quantity`, `availability`, `image`) VALUES
(1, 1, 'Spanish Latte ', 'Rich espresso blended with creamy milk and sweetened condensed milk for a smooth indulgent taste.', 5.50, 40, 1, 'spanish-latte.png'),
(2, 1, 'Vanilla Latte', 'Smooth espresso combined with creamy milk and a touch of sweet vanilla.', 5.00, 38, 1, 'vanilla-latte.png'),
(3, 1, 'Americano ', 'Bold espresso blended with water for a smooth, rich and refreshing coffee experience.', 4.50, 40, 1, 'americanoo.png'),
(4, 2, 'Hot Cappuccino', 'Rich espresso topped with steamed milk and a thick layer of velvety foam.', 5.00, 40, 1, 'cappuccino.png'),
(5, 1, 'Caramel Vanilla Latte', 'Smooth espresso blended with creamy milk, sweet caramel and aromatic vanilla.', 5.50, 40, 1, 'caramel-vanilla.png'),
(6, 3, 'Strawberry Cream Frappe', 'A refreshing ice-blended with sweet strawberry flavour and creamy goodness.', 6.50, 40, 1, 'strawberry-frappe.png'),
(7, 1, 'Caramel Macchiato', 'Creamy steamed milk layered with rich espresso, vanilla and caramel.', 5.50, 40, 1, 'caramel-macc.png'),
(8, 1, 'Brown Sugar Shaken Espresso', 'Bold espresso shaken with brown sugar and ice, finished with creamy milk for a smooth, caramel-like sweetness.', 6.00, 40, 1, 'brown-sugar.png'),
(9, 2, 'Hot Mocha', 'A delicious combination of rich espresso, smooth chocolate and creamy steamed milk.', 4.50, 40, 1, 'hot-mocha.png'),
(10, 4, 'Matcha Latte', 'Premium matcha blended with creamy milk for a smooth, earthy and refreshing taste.', 6.00, 40, 1, 'Matcha Latte.png'),
(11, 4, 'Dirty Matcha Latte', 'A perfect fusion of earthy matcha, creamy milk and a bold shot of espresso.', 6.50, 39, 1, 'dirty-matcha.png'),
(12, 5, 'Swedish Berry', 'A fruity and refreshing berry-inspired drink with a delightful balance of sweetness and tanginess.', 3.50, 40, 1, 'swedish-berry.png'),
(13, 3, 'Cookies & Creme Frappe', 'A creamy ice-blended treat packed with delicious cookie flavours.\r\n', 6.50, 40, 1, 'cookie-and-cream.png'),
(14, 2, 'Hot Flat White', 'Bold espresso blended with silky steamed milk and delicate microfoam for a rich, smooth finish.', 4.50, 40, 1, 'flat-white.png'),
(15, 5, 'Passion Fruit Tea', 'Refreshing tea infused with tropical passion fruit flavour for a sweet and tangy experience.', 3.50, 40, 1, 'passion-fruit.png'),
(16, 3, 'Nutella Frappe ', 'A rich and creamy ice-blended treat packed with indulgent Nutella hazelnut chocolate flavour.', 6.50, 40, 1, 'nutella.png'),
(17, 3, 'White Chocolate Frappe ', 'A smooth and creamy ice-blended drink infused with rich white chocolate.', 6.50, 40, 1, 'white-choco.png'),
(18, 4, 'Pistachio Matcha Latte ', 'A smooth and creamy blend of earthy matcha, rich pistachio and velvety milk.', 6.50, 40, 1, 'pistachio-matcha.png'),
(19, 3, 'Tiramisu Frappe', 'A rich and creamy ice-blended treat inspired by classic tiramisu, combining bold coffee, smooth mascarpone flavours.', 7.00, 40, 1, 'tiramisu.png'),
(20, 4, 'Matcha Float', 'A refreshing blend of earthy matcha and creamy milk, topped with a scoop of velvety vanilla ice cream.', 7.00, 40, 1, 'matcha-float.png'),
(21, 1, 'Vietnamese Iced Coffee', 'A bold and aromatic blend of rich Vietnamese coffee and sweetened condensed milk.', 5.00, 40, 1, 'vietnamese.png'),
(22, 5, 'Raspberry Tea', 'A refreshing blend of freshly brewed tea and sweet, tangy raspberry flavour, served over ice for a fruity and refreshing delight.', 3.50, 0, 1, 'raspberry.png'),
(23, 1, 'Biscoff Latte', 'Rich espresso blended with creamy milk and indulgent Biscoff cookie butter for a sweet, caramelised biscuit flavour.', 6.50, 40, 1, 'biscoff.png');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` varchar(30) NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `customer_id`, `order_date`, `total_amount`, `status`) VALUES
(1, 1, '2026-10-17 02:23:53', 10.71, 'Pending'),
(2, 2, '2026-10-17 02:25:41', 21.96, 'complete'),
(3, 2, '2026-10-17 05:10:53', 4.50, 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `drink_size` varchar(20) DEFAULT 'Medium',
  `milk_type` varchar(50) DEFAULT '2% Milk',
  `add_ins` varchar(100) DEFAULT 'Ice',
  `espresso_shots` int(11) DEFAULT 2,
  `syrup_pumps` int(11) DEFAULT 2,
  `topping` varchar(100) DEFAULT 'None',
  `barista_note` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `menu_id`, `quantity`, `unit_price`, `subtotal`, `drink_size`, `milk_type`, `add_ins`, `espresso_shots`, `syrup_pumps`, `topping`, `barista_note`) VALUES
(1, 1, 2, 1, 11.90, 11.90, 'Medium', '2% Milk', 'Ice', 2, 2, 'None', NULL),
(4, 3, 2, 1, 5.00, 5.00, 'Medium', '2% Milk', 'Ice', 2, 2, 'None', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` text DEFAULT NULL,
  `review_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `menu_item`
--
ALTER TABLE `menu_item`
  ADD PRIMARY KEY (`menu_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `menu_id` (`menu_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `menu_id` (`menu_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `menu_item`
--
ALTER TABLE `menu_item`
  MODIFY `menu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `menu_item`
--
ALTER TABLE `menu_item`
  ADD CONSTRAINT `menu_item_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `menu_item` (`menu_id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `menu_item` (`menu_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
