-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 20, 2026 at 12:01 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kopitarikdatabase`
--

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
(1, 'demo_admin', 'admin@kopitarik.test', '$2y$10$xgxoMKCxfN8XuplH1pDVtu8zD42sVAyuwdTQ0Ghe4hWeA.wiCyQ/a');

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
(3, 'Frappes'),
(2, 'Hot Coffee'),
(1, 'Iced Coffee'),
(4, 'Matcha'),
(5, 'Refreshing Tea');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_id`, `name`, `email`, `phone`, `password`, `created_at`) VALUES
(1, 'Aiman Demo', 'customer01@kopitarik.test', '+673 000 0101', '$2y$10$jZ0SHm0aSc3yPxX1BHICvOqRJbM1oPLiBRC7CnwireL0yfTpSF7tW', '2026-07-01 01:00:00'),
(2, 'Nur Demo', 'customer02@kopitarik.test', '+673 000 0102', '$2y$10$zeGJ0yxW5oLyoeMfJA/uUe.zKeXgQzXvEFxz/OVVE055O3Bffst.i', '2026-07-02 01:00:00'),
(3, 'Hafiz Demo', 'customer03@kopitarik.test', '+673 000 0103', '$2y$10$vSV5eJJCX1MXNtPSW666e.BeLUNtYnTPaD2Rv97Uc0WNZxOlLThDy', '2026-07-03 01:00:00'),
(4, 'Sofia Demo', 'customer04@kopitarik.test', '+673 000 0104', '$2y$10$jvQndiHF/OyT7UU.cVC6je4MW4qOegWVh3dfeO1PjfcfalGVTi79u', '2026-07-04 01:00:00'),
(5, 'Amir Demo', 'customer05@kopitarik.test', '+673 000 0105', '$2y$10$FZuwGbhbqzciVXRQTdMAH.VZxhapsWPNij.hub9oIj0no04XWls7S', '2026-07-05 01:00:00'),
(6, 'Alya Demo', 'customer06@kopitarik.test', '+673 000 0106', '$2y$10$dA617Qn/vZKDGeMscf3a5O8vbWUWscQbr62joofATIIFzX9Kbe6gO', '2026-07-06 01:00:00'),
(7, 'Danish Demo', 'customer07@kopitarik.test', '+673 000 0107', '$2y$10$mX6d4wNiAGRhSkc9Zt9/KegKLgfvZ2KrbZu/hhWgCxbH/tYvyabTa', '2026-07-07 01:00:00'),
(8, 'Hana Demo', 'customer08@kopitarik.test', '+673 000 0108', '$2y$10$1/vw8HpMw5oHHhmgLkpRD.WjTRXA3HaPeeWM4Xy7UIqwvO.tOsNXe', '2026-07-08 01:00:00'),
(9, 'Faris Demo', 'customer09@kopitarik.test', '+673 000 0109', '$2y$10$CFnD7gf5AlLVkSO6FW2MBeC45Sbc5AS5ytlslZhGU9woKQyMwHgXS', '2026-07-09 01:00:00'),
(10, 'Iman Demo', 'customer10@kopitarik.test', '+673 000 0110', '$2y$10$KYjxegIImIdLx1MGFB6ununYtVHwqq6wEuKz9w7xFKusQDGlUTIvC', '2026-07-10 01:00:00'),
(11, 'Hakim Demo', 'customer11@kopitarik.test', '+673 000 0111', '$2y$10$0ns7PKqR5BWKBmQICqU31ejjWdBQXn8L5nemv/LVyGJO7RUWwSF.G', '2026-07-11 01:00:00'),
(12, 'Nadia Demo', 'customer12@kopitarik.test', '+673 000 0112', '$2y$10$LDHS/6OiAuaONI1zXhLYE.zvI5uzDQL.DJ1JFdRBPedakaDM5QQU.', '2026-07-12 01:00:00'),
(13, 'Irfan Demo', 'customer13@kopitarik.test', '+673 000 0113', '$2y$10$RtiEAo2MNjQjXjqZDHlRjOpW0mdmvgAgluYjrqQB.RGZKNyRcNqPC', '2026-07-13 01:00:00'),
(14, 'Sara Demo', 'customer14@kopitarik.test', '+673 000 0114', '$2y$10$tJRxPxFcEoYwV.VKEwPCOeobt3T2l.HWTYczxKoyzL7UUgiLai48O', '2026-07-14 01:00:00'),
(15, 'Rafi Demo', 'customer15@kopitarik.test', '+673 000 0115', '$2y$10$snLgSHjKZ7BINcHvvOi1ue1g5DmhGX0Ut00jr65H5E6wZYxIJ10f.', '2026-07-15 01:00:00'),
(16, 'Zara Demo', 'customer16@kopitarik.test', '+673 000 0116', '$2y$10$Hpw3/Y0h3H4vL3DiS7/knOb9eZt4VX4qN48jyoyb.A/hy/i3DQjgi', '2026-07-16 01:00:00'),
(17, 'Adam Demo', 'customer17@kopitarik.test', '+673 000 0117', '$2y$10$4ieYcte8X1pmd8kVYkNPW.FzFD67EZzxZI9ZFz2jST8oKTFgRrF5q', '2026-07-17 01:00:00'),
(18, 'Maya Demo', 'customer18@kopitarik.test', '+673 000 0118', '$2y$10$NaKnDfN9xuT9dxaNZO9Lgeei/XE8mLxp.WGj3BFEowppsrmwIPOUu', '2026-07-18 01:00:00'),
(19, 'Zaid Demo', 'customer19@kopitarik.test', '+673 000 0119', '$2y$10$oUQF4ghGg4nkRAgw/Ppq3ekxPYLZ1Y4tIAx2uKdywjzmEigw6eKS.', '2026-07-19 01:00:00'),
(20, 'Lina Demo', 'customer20@kopitarik.test', '+673 000 0120', '$2y$10$bBe8vF/zMcrHDHJjWzMltedEihU8qpKCw0Wor.JSInnb8yN79/Gqu', '2026-07-20 01:00:00'),
(21, 'Rayyan Demo', 'customer21@kopitarik.test', '+673 000 0121', '$2y$10$7JLzt7sCCVDbJBtOnx.Isud26A5hS89VYkCdCuPVkYav6MyfS9.Ke', '2026-07-21 01:00:00'),
(22, 'Dina Demo', 'customer22@kopitarik.test', '+673 000 0122', '$2y$10$2BrlJWLUXMSvnA/78/.kwOCV9vOusbQPNdmw9gmGJOWDtxrqj86Gm', '2026-07-22 01:00:00'),
(23, 'Arif Demo', 'customer23@kopitarik.test', '+673 000 0123', '$2y$10$5BtJ.35DPDOeU.033LosH.Q4tdO.DzEpdlRoLCywrH7NyRxP5g6F.', '2026-07-23 01:00:00'),
(24, 'Yasmin Demo', 'customer24@kopitarik.test', '+673 000 0124', '$2y$10$3Y1ifM816pebvjS4WLR2IeObnTyczBUPlUC1orvZn2S3FCApBdcZS', '2026-07-24 01:00:00');

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
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ;

--
-- Dumping data for table `menu_item`
--

INSERT INTO `menu_item` (`menu_id`, `category_id`, `menu_name`, `description`, `price`, `stock_quantity`, `availability`, `image`, `created_at`, `updated_at`) VALUES
(1, 1, 'Spanish Latte', 'Rich espresso blended with creamy milk and sweetened condensed milk for a smooth indulgent taste.', 5.50, 40, 1, 'spanish-latte.png', '2026-07-01 00:00:00', '2026-10-20 08:50:00'),
(2, 1, 'Vanilla Latte', 'Smooth espresso combined with creamy milk and a touch of sweet vanilla.', 5.00, 38, 1, 'vanilla-latte.png', '2026-07-01 00:00:00', '2026-10-20 08:50:00'),
(3, 1, 'Americano', 'Bold espresso blended with water for a smooth, rich and refreshing coffee experience.', 4.50, 40, 1, 'americanoo.png', '2026-07-01 00:00:00', '2026-10-20 08:50:00'),
(4, 2, 'Hot Cappuccino', 'Rich espresso topped with steamed milk and a thick layer of velvety foam.', 5.00, 40, 1, 'cappuccino.png', '2026-07-01 00:00:00', '2026-10-20 08:50:00'),
(5, 1, 'Caramel Vanilla Latte', 'Smooth espresso blended with creamy milk, sweet caramel and aromatic vanilla.', 5.50, 40, 1, 'caramel-vanilla.png', '2026-07-01 00:00:00', '2026-10-20 08:50:00'),
(6, 3, 'Strawberry Cream Frappe', 'A refreshing ice-blended with sweet strawberry flavour and creamy goodness.', 6.50, 40, 1, 'strawberry-frappe.png', '2026-07-01 00:00:00', '2026-10-20 08:50:00'),
(7, 1, 'Caramel Macchiato', 'Creamy steamed milk layered with rich espresso, vanilla and caramel.', 5.50, 40, 1, 'caramel-macc.png', '2026-07-01 00:00:00', '2026-10-20 08:50:00'),
(8, 1, 'Brown Sugar Shaken Espresso', 'Bold espresso shaken with brown sugar and ice, finished with creamy milk for a smooth, caramel-like sweetness.', 6.00, 40, 1, 'brown-sugar.png', '2026-07-01 00:00:00', '2026-10-20 08:50:00'),
(9, 2, 'Hot Mocha', 'A delicious combination of rich espresso, smooth chocolate and creamy steamed milk.', 4.50, 40, 1, 'hot-mocha.png', '2026-07-01 00:00:00', '2026-10-20 08:50:00'),
(10, 4, 'Matcha Latte', 'Premium matcha blended with creamy milk for a smooth, earthy and refreshing taste.', 6.00, 40, 1, 'Matcha Latte.png', '2026-07-01 00:00:00', '2026-10-20 08:50:00'),
(11, 4, 'Dirty Matcha Latte', 'A perfect fusion of earthy matcha, creamy milk and a bold shot of espresso.', 6.50, 39, 1, 'dirty-matcha.png', '2026-07-01 00:00:00', '2026-10-20 08:50:00'),
(12, 5, 'Swedish Berry', 'A fruity and refreshing berry-inspired drink with a delightful balance of sweetness and tanginess.', 3.50, 40, 1, 'swedish-berry.png', '2026-07-01 00:00:00', '2026-10-20 08:50:00'),
(13, 3, 'Cookies & Creme Frappe', 'A creamy ice-blended treat packed with delicious cookie flavours.', 6.50, 40, 1, 'cookie-and-cream.png', '2026-07-01 00:00:00', '2026-10-20 08:50:00'),
(14, 2, 'Hot Flat White', 'Bold espresso blended with silky steamed milk and delicate microfoam for a rich, smooth finish.', 4.50, 40, 1, 'flat-white.png', '2026-07-01 00:00:00', '2026-10-20 08:50:00'),
(15, 5, 'Passion Fruit Tea', 'Refreshing tea infused with tropical passion fruit flavour for a sweet and tangy experience.', 3.50, 40, 1, 'passion-fruit.png', '2026-07-01 00:00:00', '2026-10-20 08:50:00'),
(16, 3, 'Nutella Frappe', 'A rich and creamy ice-blended treat packed with indulgent Nutella hazelnut chocolate flavour.', 6.50, 40, 1, 'nutella.png', '2026-07-01 00:00:00', '2026-10-20 08:50:00'),
(17, 3, 'White Chocolate Frappe', 'A smooth and creamy ice-blended drink infused with rich white chocolate.', 6.50, 40, 1, 'white-choco.png', '2026-07-01 00:00:00', '2026-10-20 08:50:00'),
(18, 4, 'Pistachio Matcha Latte', 'A smooth and creamy blend of earthy matcha, rich pistachio and velvety milk.', 6.50, 40, 1, 'pistachio-matcha.png', '2026-07-01 00:00:00', '2026-10-20 08:50:00'),
(19, 3, 'Tiramisu Frappe', 'A rich and creamy ice-blended treat inspired by classic tiramisu, combining bold coffee, smooth mascarpone flavours.', 7.00, 40, 1, 'tiramisu.png', '2026-07-01 00:00:00', '2026-10-20 08:50:00'),
(20, 4, 'Matcha Float', 'A refreshing blend of earthy matcha and creamy milk, topped with a scoop of velvety vanilla ice cream.', 7.00, 40, 1, 'matcha-float.png', '2026-07-01 00:00:00', '2026-10-20 08:50:00'),
(21, 1, 'Vietnamese Iced Coffee', 'A bold and aromatic blend of rich Vietnamese coffee and sweetened condensed milk.', 5.00, 40, 1, 'vietnamese.png', '2026-07-01 00:00:00', '2026-10-20 08:50:00'),
(22, 5, 'Raspberry Tea', 'A refreshing blend of freshly brewed tea and sweet, tangy raspberry flavour, served over ice for a fruity and refreshing delight.', 3.50, 0, 0, 'raspberry.png', '2026-07-01 00:00:00', '2026-10-20 08:50:00'),
(23, 1, 'Biscoff Latte', 'Rich espresso blended with creamy milk and indulgent Biscoff cookie butter for a sweet, caramelised biscuit flavour.', 6.50, 40, 1, 'biscoff.png', '2026-07-01 00:00:00', '2026-10-20 08:50:00');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `customer_email` varchar(100) NOT NULL,
  `customer_phone` varchar(20) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('Pending','Preparing','Ready','Completed','Cancelled') NOT NULL DEFAULT 'Pending'
) ;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `customer_id`, `customer_name`, `customer_email`, `customer_phone`, `order_date`, `total_amount`, `status`) VALUES
(1, 1, 'Aiman Demo', 'customer01@kopitarik.test', '+673 000 0101', '2026-08-01 02:11:00', 27.50, 'Completed'),
(2, 8, 'Hana Demo', 'customer08@kopitarik.test', '+673 000 0108', '2026-08-01 03:22:00', 28.60, 'Completed'),
(3, 15, 'Rafi Demo', 'customer15@kopitarik.test', '+673 000 0115', '2026-08-02 04:33:00', 42.60, 'Completed'),
(4, 4, 'Sofia Demo', 'customer04@kopitarik.test', '+673 000 0104', '2026-08-03 05:44:00', 14.00, 'Completed'),
(5, 22, 'Dina Demo', 'customer22@kopitarik.test', '+673 000 0122', '2026-08-04 06:55:00', 16.11, 'Completed'),
(6, 5, 'Amir Demo', 'customer05@kopitarik.test', '+673 000 0105', '2026-08-05 07:06:00', 34.20, 'Completed'),
(7, 12, 'Nadia Demo', 'customer12@kopitarik.test', '+673 000 0112', '2026-08-05 08:17:00', 46.30, 'Completed'),
(8, 8, 'Hana Demo', 'customer08@kopitarik.test', '+673 000 0108', '2026-08-06 09:28:00', 6.00, 'Completed'),
(9, 19, 'Zaid Demo', 'customer19@kopitarik.test', '+673 000 0119', '2026-08-07 10:39:00', 18.90, 'Completed'),
(10, 2, 'Nur Demo', 'customer02@kopitarik.test', '+673 000 0102', '2026-08-08 01:50:00', 34.83, 'Completed'),
(11, 9, 'Faris Demo', 'customer09@kopitarik.test', '+673 000 0109', '2026-08-09 02:01:00', 51.50, 'Completed'),
(12, 12, 'Nadia Demo', 'customer12@kopitarik.test', '+673 000 0112', '2026-08-10 03:12:00', 12.00, 'Completed'),
(13, 16, 'Zara Demo', 'customer16@kopitarik.test', '+673 000 0116', '2026-08-10 04:23:00', 21.90, 'Completed'),
(14, 23, 'Arif Demo', 'customer23@kopitarik.test', '+673 000 0123', '2026-08-11 05:34:00', 23.00, 'Completed'),
(15, 6, 'Alya Demo', 'customer06@kopitarik.test', '+673 000 0106', '2026-08-12 06:45:00', 47.70, 'Completed'),
(16, 16, 'Zara Demo', 'customer16@kopitarik.test', '+673 000 0116', '2026-08-13 07:56:00', 7.30, 'Completed'),
(17, 13, 'Irfan Demo', 'customer13@kopitarik.test', '+673 000 0113', '2026-08-14 08:07:00', 25.20, 'Completed'),
(18, 20, 'Lina Demo', 'customer20@kopitarik.test', '+673 000 0120', '2026-08-14 09:18:00', 26.80, 'Completed'),
(19, 3, 'Hafiz Demo', 'customer03@kopitarik.test', '+673 000 0103', '2026-08-15 10:29:00', 43.70, 'Completed'),
(20, 20, 'Lina Demo', 'customer20@kopitarik.test', '+673 000 0120', '2026-08-16 01:40:00', 7.47, 'Completed'),
(21, 10, 'Iman Demo', 'customer10@kopitarik.test', '+673 000 0110', '2026-08-17 02:51:00', 22.40, 'Completed'),
(22, 17, 'Adam Demo', 'customer17@kopitarik.test', '+673 000 0117', '2026-08-18 03:02:00', 20.50, 'Completed'),
(23, 24, 'Yasmin Demo', 'customer24@kopitarik.test', '+673 000 0124', '2026-08-19 04:13:00', 41.30, 'Completed'),
(24, 24, 'Yasmin Demo', 'customer24@kopitarik.test', '+673 000 0124', '2026-08-19 05:24:00', 10.60, 'Completed'),
(25, 7, 'Danish Demo', 'customer07@kopitarik.test', '+673 000 0107', '2026-08-20 06:35:00', 14.04, 'Completed'),
(26, 14, 'Sara Demo', 'customer14@kopitarik.test', '+673 000 0114', '2026-08-21 07:46:00', 30.90, 'Completed'),
(27, 21, 'Rayyan Demo', 'customer21@kopitarik.test', '+673 000 0121', '2026-08-22 08:57:00', 53.50, 'Completed'),
(28, 4, 'Sofia Demo', 'customer04@kopitarik.test', '+673 000 0104', '2026-08-23 09:08:00', 6.50, 'Completed'),
(29, 4, 'Sofia Demo', 'customer04@kopitarik.test', '+673 000 0104', '2026-08-24 10:19:00', 19.60, 'Completed'),
(30, 11, 'Hakim Demo', 'customer11@kopitarik.test', '+673 000 0111', '2026-08-24 01:30:00', 36.72, 'Completed'),
(31, 18, 'Maya Demo', 'customer18@kopitarik.test', '+673 000 0118', '2026-08-25 02:41:00', 43.70, 'Completed'),
(32, 8, 'Hana Demo', 'customer08@kopitarik.test', '+673 000 0108', '2026-08-26 03:52:00', 15.60, 'Completed'),
(33, 1, 'Aiman Demo', 'customer01@kopitarik.test', '+673 000 0101', '2026-08-27 04:03:00', 26.10, 'Cancelled'),
(34, 8, 'Hana Demo', 'customer08@kopitarik.test', '+673 000 0108', '2026-08-28 05:14:00', 22.80, 'Completed'),
(35, 15, 'Rafi Demo', 'customer15@kopitarik.test', '+673 000 0115', '2026-08-28 06:25:00', 35.91, 'Completed'),
(36, 12, 'Nadia Demo', 'customer12@kopitarik.test', '+673 000 0112', '2026-08-29 07:36:00', 15.90, 'Completed'),
(37, 22, 'Dina Demo', 'customer22@kopitarik.test', '+673 000 0122', '2026-08-30 08:47:00', 28.90, 'Completed'),
(38, 5, 'Amir Demo', 'customer05@kopitarik.test', '+673 000 0105', '2026-08-31 09:58:00', 25.90, 'Cancelled'),
(39, 12, 'Nadia Demo', 'customer12@kopitarik.test', '+673 000 0112', '2026-09-01 10:09:00', 44.60, 'Completed'),
(40, 16, 'Zara Demo', 'customer16@kopitarik.test', '+673 000 0116', '2026-09-02 01:20:00', 5.67, 'Cancelled'),
(41, 19, 'Zaid Demo', 'customer19@kopitarik.test', '+673 000 0119', '2026-09-02 02:31:00', 25.60, 'Completed'),
(42, 2, 'Nur Demo', 'customer02@kopitarik.test', '+673 000 0102', '2026-09-03 03:42:00', 44.00, 'Completed'),
(43, 9, 'Faris Demo', 'customer09@kopitarik.test', '+673 000 0109', '2026-09-04 04:53:00', 30.70, 'Completed'),
(44, 20, 'Lina Demo', 'customer20@kopitarik.test', '+673 000 0120', '2026-09-05 05:04:00', 22.50, 'Completed'),
(45, 16, 'Zara Demo', 'customer16@kopitarik.test', '+673 000 0116', '2026-09-06 06:15:00', 18.00, 'Completed'),
(46, 23, 'Arif Demo', 'customer23@kopitarik.test', '+673 000 0123', '2026-09-07 07:26:00', 28.40, 'Completed'),
(47, 6, 'Alya Demo', 'customer06@kopitarik.test', '+673 000 0106', '2026-09-07 08:37:00', 36.40, 'Completed'),
(48, 24, 'Yasmin Demo', 'customer24@kopitarik.test', '+673 000 0124', '2026-09-08 09:48:00', 6.00, 'Completed'),
(49, 13, 'Irfan Demo', 'customer13@kopitarik.test', '+673 000 0113', '2026-09-09 10:59:00', 19.30, 'Completed'),
(50, 20, 'Lina Demo', 'customer20@kopitarik.test', '+673 000 0120', '2026-09-10 01:10:00', 21.24, 'Completed'),
(51, 3, 'Hafiz Demo', 'customer03@kopitarik.test', '+673 000 0103', '2026-09-11 02:21:00', 42.00, 'Completed'),
(52, 4, 'Sofia Demo', 'customer04@kopitarik.test', '+673 000 0104', '2026-09-11 03:32:00', 11.60, 'Completed'),
(53, 10, 'Iman Demo', 'customer10@kopitarik.test', '+673 000 0110', '2026-09-12 04:43:00', 24.30, 'Completed'),
(54, 17, 'Adam Demo', 'customer17@kopitarik.test', '+673 000 0117', '2026-09-13 05:54:00', 47.90, 'Cancelled'),
(55, 24, 'Yasmin Demo', 'customer24@kopitarik.test', '+673 000 0124', '2026-09-14 06:05:00', 41.58, 'Completed'),
(56, 8, 'Hana Demo', 'customer08@kopitarik.test', '+673 000 0108', '2026-09-15 07:16:00', 7.50, 'Completed'),
(57, 7, 'Danish Demo', 'customer07@kopitarik.test', '+673 000 0107', '2026-09-16 08:27:00', 11.80, 'Completed'),
(58, 14, 'Sara Demo', 'customer14@kopitarik.test', '+673 000 0114', '2026-09-16 09:38:00', 36.20, 'Completed'),
(59, 21, 'Rayyan Demo', 'customer21@kopitarik.test', '+673 000 0121', '2026-09-17 10:49:00', 47.00, 'Completed'),
(60, 12, 'Nadia Demo', 'customer12@kopitarik.test', '+673 000 0112', '2026-09-18 01:00:00', 24.93, 'Completed'),
(61, 4, 'Sofia Demo', 'customer04@kopitarik.test', '+673 000 0104', '2026-09-19 02:11:00', 9.30, 'Completed'),
(62, 11, 'Hakim Demo', 'customer11@kopitarik.test', '+673 000 0111', '2026-09-20 03:22:00', 29.40, 'Completed'),
(63, 18, 'Maya Demo', 'customer18@kopitarik.test', '+673 000 0118', '2026-09-21 04:33:00', 41.50, 'Completed'),
(64, 16, 'Zara Demo', 'customer16@kopitarik.test', '+673 000 0116', '2026-09-21 05:44:00', 15.60, 'Completed'),
(65, 1, 'Aiman Demo', 'customer01@kopitarik.test', '+673 000 0101', '2026-09-22 06:55:00', 17.10, 'Completed'),
(66, 8, 'Hana Demo', 'customer08@kopitarik.test', '+673 000 0108', '2026-09-23 07:06:00', 24.90, 'Completed'),
(67, 15, 'Rafi Demo', 'customer15@kopitarik.test', '+673 000 0115', '2026-09-24 08:17:00', 38.00, 'Completed'),
(68, 20, 'Lina Demo', 'customer20@kopitarik.test', '+673 000 0120', '2026-09-25 09:28:00', 16.60, 'Completed'),
(69, 22, 'Dina Demo', 'customer22@kopitarik.test', '+673 000 0122', '2026-09-25 10:39:00', 42.30, 'Cancelled'),
(70, 5, 'Amir Demo', 'customer05@kopitarik.test', '+673 000 0105', '2026-09-26 01:50:00', 32.40, 'Cancelled'),
(71, 12, 'Nadia Demo', 'customer12@kopitarik.test', '+673 000 0112', '2026-09-27 02:01:00', 31.10, 'Completed'),
(72, 24, 'Yasmin Demo', 'customer24@kopitarik.test', '+673 000 0124', '2026-09-28 03:12:00', 3.00, 'Completed'),
(73, 19, 'Zaid Demo', 'customer19@kopitarik.test', '+673 000 0119', '2026-09-29 04:23:00', 22.60, 'Completed'),
(74, 2, 'Nur Demo', 'customer02@kopitarik.test', '+673 000 0102', '2026-09-30 05:34:00', 32.60, 'Completed'),
(75, 9, 'Faris Demo', 'customer09@kopitarik.test', '+673 000 0109', '2026-09-30 06:45:00', 48.15, 'Completed'),
(76, 4, 'Sofia Demo', 'customer04@kopitarik.test', '+673 000 0104', '2026-10-01 07:56:00', 14.60, 'Completed'),
(77, 16, 'Zara Demo', 'customer16@kopitarik.test', '+673 000 0116', '2026-10-02 08:07:00', 12.30, 'Completed'),
(78, 23, 'Arif Demo', 'customer23@kopitarik.test', '+673 000 0123', '2026-10-03 09:18:00', 18.10, 'Completed'),
(79, 6, 'Alya Demo', 'customer06@kopitarik.test', '+673 000 0106', '2026-10-04 10:29:00', 33.30, 'Cancelled'),
(80, 8, 'Hana Demo', 'customer08@kopitarik.test', '+673 000 0108', '2026-10-05 01:40:00', 6.12, 'Completed'),
(81, 13, 'Irfan Demo', 'customer13@kopitarik.test', '+673 000 0113', '2026-10-05 02:51:00', 20.90, 'Cancelled'),
(82, 20, 'Lina Demo', 'customer20@kopitarik.test', '+673 000 0120', '2026-10-06 03:02:00', 19.40, 'Completed'),
(83, 3, 'Hafiz Demo', 'customer03@kopitarik.test', '+673 000 0103', '2026-10-07 04:13:00', 60.30, 'Completed'),
(84, 12, 'Nadia Demo', 'customer12@kopitarik.test', '+673 000 0112', '2026-10-08 05:24:00', 8.00, 'Completed'),
(85, 10, 'Iman Demo', 'customer10@kopitarik.test', '+673 000 0110', '2026-10-09 06:35:00', 32.04, 'Completed'),
(86, 17, 'Adam Demo', 'customer17@kopitarik.test', '+673 000 0117', '2026-10-09 07:46:00', 20.30, 'Completed'),
(87, 24, 'Yasmin Demo', 'customer24@kopitarik.test', '+673 000 0124', '2026-10-10 08:57:00', 39.20, 'Cancelled'),
(88, 16, 'Zara Demo', 'customer16@kopitarik.test', '+673 000 0116', '2026-10-11 09:08:00', 7.80, 'Cancelled'),
(89, 7, 'Danish Demo', 'customer07@kopitarik.test', '+673 000 0107', '2026-10-12 10:19:00', 12.30, 'Completed'),
(90, 14, 'Sara Demo', 'customer14@kopitarik.test', '+673 000 0114', '2026-10-13 01:30:00', 41.85, 'Completed'),
(91, 21, 'Rayyan Demo', 'customer21@kopitarik.test', '+673 000 0121', '2026-10-14 02:41:00', 32.90, 'Completed'),
(92, 20, 'Lina Demo', 'customer20@kopitarik.test', '+673 000 0120', '2026-10-14 03:52:00', 7.80, 'Completed'),
(93, 4, 'Sofia Demo', 'customer04@kopitarik.test', '+673 000 0104', '2026-10-15 04:03:00', 17.80, 'Cancelled'),
(94, 11, 'Hakim Demo', 'customer11@kopitarik.test', '+673 000 0111', '2026-10-16 05:14:00', 24.40, 'Completed'),
(95, 18, 'Maya Demo', 'customer18@kopitarik.test', '+673 000 0118', '2026-10-17 06:25:00', 27.81, 'Completed'),
(96, 24, 'Yasmin Demo', 'customer24@kopitarik.test', '+673 000 0124', '2026-10-18 07:36:00', 11.00, 'Cancelled'),
(97, 1, 'Aiman Demo', 'customer01@kopitarik.test', '+673 000 0101', '2026-10-20 06:00:00', 28.20, 'Pending'),
(98, 8, 'Hana Demo', 'customer08@kopitarik.test', '+673 000 0108', '2026-10-20 06:07:00', 51.10, 'Pending'),
(99, 15, 'Rafi Demo', 'customer15@kopitarik.test', '+673 000 0115', '2026-10-20 06:14:00', 49.80, 'Pending'),
(100, 4, 'Sofia Demo', 'customer04@kopitarik.test', '+673 000 0104', '2026-10-20 06:21:00', 10.80, 'Pending'),
(101, 22, 'Dina Demo', 'customer22@kopitarik.test', '+673 000 0122', '2026-10-20 06:28:00', 34.90, 'Pending'),
(102, 5, 'Amir Demo', 'customer05@kopitarik.test', '+673 000 0105', '2026-10-20 06:35:00', 23.20, 'Pending'),
(103, 12, 'Nadia Demo', 'customer12@kopitarik.test', '+673 000 0112', '2026-10-20 06:42:00', 35.40, 'Pending'),
(104, 8, 'Hana Demo', 'customer08@kopitarik.test', '+673 000 0108', '2026-10-20 06:49:00', 7.80, 'Pending'),
(105, 19, 'Zaid Demo', 'customer19@kopitarik.test', '+673 000 0119', '2026-10-20 06:56:00', 32.94, 'Preparing'),
(106, 2, 'Nur Demo', 'customer02@kopitarik.test', '+673 000 0102', '2026-10-20 07:03:00', 21.10, 'Preparing'),
(107, 9, 'Faris Demo', 'customer09@kopitarik.test', '+673 000 0109', '2026-10-20 07:10:00', 35.10, 'Preparing'),
(108, 12, 'Nadia Demo', 'customer12@kopitarik.test', '+673 000 0112', '2026-10-20 07:17:00', 17.40, 'Preparing'),
(109, 16, 'Zara Demo', 'customer16@kopitarik.test', '+673 000 0116', '2026-10-20 07:24:00', 26.90, 'Preparing'),
(110, 23, 'Arif Demo', 'customer23@kopitarik.test', '+673 000 0123', '2026-10-20 07:31:00', 31.32, 'Preparing'),
(111, 6, 'Alya Demo', 'customer06@kopitarik.test', '+673 000 0106', '2026-10-20 07:38:00', 47.70, 'Preparing'),
(112, 16, 'Zara Demo', 'customer16@kopitarik.test', '+673 000 0116', '2026-10-20 07:45:00', 10.50, 'Preparing'),
(113, 13, 'Irfan Demo', 'customer13@kopitarik.test', '+673 000 0113', '2026-10-20 07:52:00', 33.00, 'Ready'),
(114, 20, 'Lina Demo', 'customer20@kopitarik.test', '+673 000 0120', '2026-10-20 07:59:00', 21.40, 'Ready'),
(115, 3, 'Hafiz Demo', 'customer03@kopitarik.test', '+673 000 0103', '2026-10-20 08:06:00', 38.52, 'Ready'),
(116, 20, 'Lina Demo', 'customer20@kopitarik.test', '+673 000 0120', '2026-10-20 08:13:00', 7.00, 'Ready'),
(117, 10, 'Iman Demo', 'customer10@kopitarik.test', '+673 000 0110', '2026-10-20 08:20:00', 18.60, 'Ready'),
(118, 17, 'Adam Demo', 'customer17@kopitarik.test', '+673 000 0117', '2026-10-20 08:27:00', 25.20, 'Ready'),
(119, 24, 'Yasmin Demo', 'customer24@kopitarik.test', '+673 000 0124', '2026-10-20 08:34:00', 45.50, 'Ready'),
(120, 24, 'Yasmin Demo', 'customer24@kopitarik.test', '+673 000 0124', '2026-10-20 08:41:00', 22.77, 'Ready');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `menu_id` int(11) DEFAULT NULL,
  `menu_name` varchar(100) NOT NULL,
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
) ;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `menu_id`, `menu_name`, `quantity`, `unit_price`, `subtotal`, `drink_size`, `milk_type`, `add_ins`, `espresso_shots`, `syrup_pumps`, `topping`, `barista_note`) VALUES
(1, 1, 13, 'Cookies & Creme Frappe', 1, 6.50, 6.50, 'Medium', '2% Milk', 'No ice', 3, 3, 'Whipped cream', 'Less sweet please.'),
(2, 1, 23, 'Biscoff Latte', 3, 7.00, 21.00, 'Large', 'Whole Milk', 'Extra ice', 3, 1, 'None', 'Regular sweetness.'),
(3, 2, 16, 'Nutella Frappe', 2, 7.80, 15.60, 'Large', 'Almond Milk', 'Extra ice', 2, 1, 'None', ''),
(4, 2, 12, 'Swedish Berry', 2, 3.00, 6.00, 'Small', 'No milk', 'Ice', 0, 0, 'None', 'No extra syrup please.'),
(5, 2, 15, 'Passion Fruit Tea', 2, 3.50, 7.00, 'Medium', 'No milk', 'Ice', 0, 2, 'None', 'Less sweet please.'),
(6, 3, 2, 'Vanilla Latte', 2, 5.30, 10.60, 'Small', 'Soy Milk', 'Extra ice', 2, 2, 'None', 'Regular sweetness.'),
(7, 3, 4, 'Hot Cappuccino', 1, 5.80, 5.80, 'Medium', 'Oat Milk', 'None', 3, 3, 'Whipped cream', 'Less sweet please.'),
(8, 3, 21, 'Vietnamese Iced Coffee', 2, 6.30, 12.60, 'Large', 'Almond Milk', 'Extra ice', 2, 3, 'None', 'Regular sweetness.'),
(9, 3, 6, 'Strawberry Cream Frappe', 2, 6.80, 13.60, 'Small', 'Almond Milk', 'Ice', 1, 3, 'Whipped cream', 'Please prepare for pickup.'),
(10, 4, 19, 'Tiramisu Frappe', 2, 7.00, 14.00, 'Medium', '2% Milk', 'Ice', 3, 1, 'Whipped cream', ''),
(11, 5, 14, 'Hot Flat White', 2, 5.22, 10.44, 'Large', 'Almond Milk', 'None', 3, 0, 'Chocolate sauce', 'Regular sweetness.'),
(12, 5, 10, 'Matcha Latte', 1, 5.67, 5.67, 'Small', 'Soy Milk', 'Extra ice', 0, 3, 'Whipped cream', 'No extra syrup please.'),
(13, 6, 16, 'Nutella Frappe', 1, 6.00, 6.00, 'Small', 'Whole Milk', 'No ice', 1, 1, 'None', 'Regular sweetness.'),
(14, 6, 20, 'Matcha Float', 1, 7.80, 7.80, 'Medium', 'Soy Milk', 'Ice', 0, 0, 'Whipped cream', 'Regular sweetness.'),
(15, 6, 5, 'Caramel Vanilla Latte', 3, 6.80, 20.40, 'Large', 'Almond Milk', 'Ice', 2, 2, 'None', 'Less sweet please.'),
(16, 7, 10, 'Matcha Latte', 1, 6.80, 6.80, 'Medium', 'Almond Milk', 'No ice', 0, 2, 'None', 'Less sweet please.'),
(17, 7, 21, 'Vietnamese Iced Coffee', 3, 6.30, 18.90, 'Large', 'Almond Milk', 'Extra ice', 2, 0, 'Chocolate sauce', 'Regular sweetness.'),
(18, 7, 15, 'Passion Fruit Tea', 2, 3.00, 6.00, 'Small', 'No milk', 'Extra ice', 0, 3, 'None', ''),
(19, 7, 6, 'Strawberry Cream Frappe', 2, 7.30, 14.60, 'Medium', 'Oat Milk', 'No ice', 2, 3, 'Chocolate sauce', 'Less sweet please.'),
(20, 8, 5, 'Caramel Vanilla Latte', 1, 6.00, 6.00, 'Large', '2% Milk', 'No ice', 1, 0, 'None', 'No extra syrup please.'),
(21, 9, 8, 'Brown Sugar Shaken Espresso', 2, 6.30, 12.60, 'Small', 'Soy Milk', 'Extra ice', 2, 0, 'None', 'Regular sweetness.'),
(22, 9, 5, 'Caramel Vanilla Latte', 1, 6.30, 6.30, 'Medium', 'Oat Milk', 'Ice', 2, 0, 'Chocolate sauce', ''),
(23, 10, 22, 'Raspberry Tea', 3, 3.15, 9.45, 'Medium', 'No milk', 'No ice', 0, 0, 'None', 'Less sweet please.'),
(24, 10, 23, 'Biscoff Latte', 1, 7.02, 7.02, 'Large', 'Oat Milk', 'Ice', 3, 2, 'None', 'Please prepare for pickup.'),
(25, 10, 11, 'Dirty Matcha Latte', 3, 6.12, 18.36, 'Small', 'Almond Milk', 'Ice', 3, 1, 'None', ''),
(26, 11, 12, 'Swedish Berry', 1, 4.00, 4.00, 'Large', 'No milk', 'No ice', 0, 2, 'None', 'Please prepare for pickup.'),
(27, 11, 6, 'Strawberry Cream Frappe', 2, 6.80, 13.60, 'Small', 'Almond Milk', 'Ice', 1, 2, 'Caramel drizzle', 'No extra syrup please.'),
(28, 11, 9, 'Hot Mocha', 3, 4.50, 13.50, 'Medium', 'Whole Milk', 'None', 1, 0, 'Caramel drizzle', 'Regular sweetness.'),
(29, 11, 1, 'Spanish Latte', 3, 6.80, 20.40, 'Large', 'Oat Milk', 'Ice', 2, 1, 'None', 'Please prepare for pickup.'),
(30, 12, 18, 'Pistachio Matcha Latte', 2, 6.00, 12.00, 'Small', '2% Milk', 'No ice', 0, 2, 'Caramel drizzle', 'No extra syrup please.'),
(31, 13, 10, 'Matcha Latte', 2, 6.80, 13.60, 'Medium', 'Oat Milk', 'No ice', 0, 2, 'Chocolate sauce', 'Less sweet please.'),
(32, 13, 19, 'Tiramisu Frappe', 1, 8.30, 8.30, 'Large', 'Soy Milk', 'Ice', 2, 3, 'None', 'No extra syrup please.'),
(33, 14, 10, 'Matcha Latte', 2, 6.50, 13.00, 'Large', 'Whole Milk', 'No ice', 0, 2, 'None', 'Regular sweetness.'),
(34, 14, 12, 'Swedish Berry', 1, 3.00, 3.00, 'Small', 'No milk', 'Extra ice', 0, 1, 'None', 'Regular sweetness.'),
(35, 14, 22, 'Raspberry Tea', 2, 3.50, 7.00, 'Medium', 'No milk', 'Extra ice', 0, 0, 'None', 'Regular sweetness.'),
(36, 15, 8, 'Brown Sugar Shaken Espresso', 3, 4.95, 14.85, 'Small', 'Whole Milk', 'Ice', 3, 2, 'None', ''),
(37, 15, 20, 'Matcha Float', 2, 7.02, 14.04, 'Medium', 'Oat Milk', 'Extra ice', 0, 3, 'Whipped cream', 'Regular sweetness.'),
(38, 15, 11, 'Dirty Matcha Latte', 1, 7.02, 7.02, 'Large', 'Almond Milk', 'No ice', 1, 1, 'None', 'Please prepare for pickup.'),
(39, 15, 10, 'Matcha Latte', 1, 5.67, 5.67, 'Small', 'Almond Milk', 'Extra ice', 0, 3, 'None', 'No extra syrup please.'),
(40, 15, 8, 'Brown Sugar Shaken Espresso', 1, 6.12, 6.12, 'Medium', 'Soy Milk', 'Extra ice', 2, 0, 'Caramel drizzle', ''),
(41, 16, 16, 'Nutella Frappe', 1, 7.30, 7.30, 'Medium', 'Almond Milk', 'No ice', 3, 2, 'Chocolate sauce', 'Regular sweetness.'),
(42, 17, 18, 'Pistachio Matcha Latte', 2, 7.80, 15.60, 'Large', 'Soy Milk', 'Extra ice', 0, 1, 'Chocolate sauce', 'Please prepare for pickup.'),
(43, 17, 3, 'Americano', 2, 4.80, 9.60, 'Small', 'Almond Milk', 'Extra ice', 1, 1, 'Whipped cream', 'Less sweet please.'),
(44, 18, 9, 'Hot Mocha', 1, 4.80, 4.80, 'Small', 'Almond Milk', 'None', 2, 3, 'None', ''),
(45, 18, 7, 'Caramel Macchiato', 1, 5.50, 5.50, 'Medium', 'Whole Milk', 'No ice', 3, 2, 'None', 'Please prepare for pickup.'),
(46, 18, 21, 'Vietnamese Iced Coffee', 3, 5.50, 16.50, 'Large', 'Whole Milk', 'No ice', 2, 2, 'Whipped cream', ''),
(47, 19, 8, 'Brown Sugar Shaken Espresso', 1, 6.80, 6.80, 'Medium', 'Soy Milk', 'No ice', 2, 0, 'None', 'Less sweet please.'),
(48, 19, 14, 'Hot Flat White', 3, 5.80, 17.40, 'Large', 'Soy Milk', 'None', 2, 0, 'Caramel drizzle', 'No extra syrup please.'),
(49, 19, 19, 'Tiramisu Frappe', 1, 6.50, 6.50, 'Small', '2% Milk', 'Ice', 2, 1, 'None', 'Less sweet please.'),
(50, 19, 11, 'Dirty Matcha Latte', 2, 6.50, 13.00, 'Medium', '2% Milk', 'Ice', 3, 0, 'Caramel drizzle', ''),
(51, 20, 19, 'Tiramisu Frappe', 1, 7.47, 7.47, 'Large', 'Oat Milk', 'No ice', 1, 2, 'Caramel drizzle', 'No extra syrup please.'),
(52, 21, 2, 'Vanilla Latte', 3, 5.30, 15.90, 'Small', 'Soy Milk', 'No ice', 1, 3, 'None', 'Less sweet please.'),
(53, 21, 13, 'Cookies & Creme Frappe', 1, 6.50, 6.50, 'Medium', '2% Milk', 'Ice', 2, 3, 'Chocolate sauce', ''),
(54, 22, 12, 'Swedish Berry', 1, 3.50, 3.50, 'Medium', 'No milk', 'No ice', 0, 0, 'None', 'Less sweet please.'),
(55, 22, 5, 'Caramel Vanilla Latte', 2, 6.00, 12.00, 'Large', '2% Milk', 'Ice', 2, 0, 'None', 'Less sweet please.'),
(56, 22, 1, 'Spanish Latte', 1, 5.00, 5.00, 'Small', '2% Milk', 'Ice', 2, 0, 'None', 'No extra syrup please.'),
(57, 23, 1, 'Spanish Latte', 2, 6.80, 13.60, 'Large', 'Almond Milk', 'Ice', 3, 2, 'Chocolate sauce', 'Regular sweetness.'),
(58, 23, 10, 'Matcha Latte', 2, 6.30, 12.60, 'Small', 'Almond Milk', 'No ice', 0, 1, 'Whipped cream', 'Please prepare for pickup.'),
(59, 23, 16, 'Nutella Frappe', 1, 7.30, 7.30, 'Medium', 'Almond Milk', 'Ice', 2, 3, 'None', 'Less sweet please.'),
(60, 23, 23, 'Biscoff Latte', 1, 7.80, 7.80, 'Large', 'Almond Milk', 'No ice', 2, 0, 'Whipped cream', 'Less sweet please.'),
(61, 24, 2, 'Vanilla Latte', 2, 5.30, 10.60, 'Small', 'Soy Milk', 'No ice', 3, 3, 'None', 'Please prepare for pickup.'),
(62, 25, 4, 'Hot Cappuccino', 2, 5.22, 10.44, 'Medium', 'Oat Milk', 'None', 2, 2, 'None', 'Regular sweetness.'),
(63, 25, 22, 'Raspberry Tea', 1, 3.60, 3.60, 'Large', 'No milk', 'No ice', 0, 2, 'None', 'Regular sweetness.'),
(64, 26, 1, 'Spanish Latte', 3, 6.80, 20.40, 'Large', 'Oat Milk', 'No ice', 1, 1, 'None', ''),
(65, 26, 9, 'Hot Mocha', 1, 4.00, 4.00, 'Small', '2% Milk', 'None', 3, 3, 'None', 'No extra syrup please.'),
(66, 26, 11, 'Dirty Matcha Latte', 1, 6.50, 6.50, 'Medium', '2% Milk', 'Extra ice', 2, 0, 'None', ''),
(67, 27, 5, 'Caramel Vanilla Latte', 1, 5.00, 5.00, 'Small', '2% Milk', 'Extra ice', 2, 1, 'Chocolate sauce', 'No extra syrup please.'),
(68, 27, 16, 'Nutella Frappe', 3, 7.30, 21.90, 'Medium', 'Soy Milk', 'Extra ice', 2, 1, 'None', 'Less sweet please.'),
(69, 27, 17, 'White Chocolate Frappe', 2, 7.80, 15.60, 'Large', 'Almond Milk', 'Extra ice', 3, 3, 'None', 'Regular sweetness.'),
(70, 27, 8, 'Brown Sugar Shaken Espresso', 2, 5.50, 11.00, 'Small', '2% Milk', 'No ice', 1, 2, 'Whipped cream', 'No extra syrup please.'),
(71, 28, 11, 'Dirty Matcha Latte', 1, 6.50, 6.50, 'Medium', 'Whole Milk', 'Extra ice', 2, 0, 'None', 'Please prepare for pickup.'),
(72, 29, 1, 'Spanish Latte', 2, 6.80, 13.60, 'Large', 'Oat Milk', 'Ice', 1, 0, 'Chocolate sauce', 'Please prepare for pickup.'),
(73, 29, 17, 'White Chocolate Frappe', 1, 6.00, 6.00, 'Small', 'Whole Milk', 'Extra ice', 1, 2, 'None', ''),
(74, 30, 17, 'White Chocolate Frappe', 1, 6.12, 6.12, 'Small', 'Almond Milk', 'No ice', 3, 0, 'None', 'No extra syrup please.'),
(75, 30, 5, 'Caramel Vanilla Latte', 2, 5.67, 11.34, 'Medium', 'Soy Milk', 'No ice', 2, 3, 'None', 'No extra syrup please.'),
(76, 30, 13, 'Cookies & Creme Frappe', 1, 7.02, 7.02, 'Large', 'Soy Milk', 'No ice', 2, 2, 'Chocolate sauce', 'Regular sweetness.'),
(77, 30, 17, 'White Chocolate Frappe', 2, 6.12, 12.24, 'Small', 'Soy Milk', 'Ice', 1, 3, 'Caramel drizzle', ''),
(78, 31, 14, 'Hot Flat White', 2, 4.50, 9.00, 'Medium', '2% Milk', 'None', 2, 0, 'Whipped cream', 'No extra syrup please.'),
(79, 31, 18, 'Pistachio Matcha Latte', 1, 7.00, 7.00, 'Large', '2% Milk', 'Extra ice', 0, 1, 'None', 'No extra syrup please.'),
(80, 31, 1, 'Spanish Latte', 1, 5.80, 5.80, 'Small', 'Soy Milk', 'Extra ice', 1, 3, 'None', 'Please prepare for pickup.'),
(81, 31, 6, 'Strawberry Cream Frappe', 3, 7.30, 21.90, 'Medium', 'Oat Milk', 'Ice', 2, 2, 'Whipped cream', 'Regular sweetness.'),
(82, 32, 13, 'Cookies & Creme Frappe', 2, 7.80, 15.60, 'Large', 'Oat Milk', 'Extra ice', 3, 3, 'None', ''),
(83, 33, 3, 'Americano', 2, 4.80, 9.60, 'Small', 'Almond Milk', 'Ice', 1, 0, 'None', 'No extra syrup please.'),
(84, 33, 5, 'Caramel Vanilla Latte', 3, 5.50, 16.50, 'Medium', '2% Milk', 'Ice', 2, 0, 'Chocolate sauce', 'Less sweet please.'),
(85, 34, 12, 'Swedish Berry', 3, 3.50, 10.50, 'Medium', 'No milk', 'No ice', 0, 2, 'None', 'Regular sweetness.'),
(86, 34, 10, 'Matcha Latte', 1, 6.50, 6.50, 'Large', 'Whole Milk', 'Extra ice', 0, 3, 'None', 'Less sweet please.'),
(87, 34, 7, 'Caramel Macchiato', 1, 5.80, 5.80, 'Small', 'Oat Milk', 'Ice', 2, 0, 'Chocolate sauce', 'Less sweet please.'),
(88, 35, 3, 'Americano', 2, 4.50, 9.00, 'Large', 'Whole Milk', 'Ice', 2, 1, 'Chocolate sauce', 'Less sweet please.'),
(89, 35, 12, 'Swedish Berry', 1, 2.70, 2.70, 'Small', 'No milk', 'No ice', 0, 0, 'None', ''),
(90, 35, 22, 'Raspberry Tea', 1, 3.15, 3.15, 'Medium', 'No milk', 'Ice', 0, 0, 'None', ''),
(91, 35, 17, 'White Chocolate Frappe', 3, 7.02, 21.06, 'Large', 'Almond Milk', 'No ice', 2, 2, 'None', 'Regular sweetness.'),
(92, 36, 2, 'Vanilla Latte', 3, 5.30, 15.90, 'Small', 'Almond Milk', 'No ice', 2, 2, 'None', 'No extra syrup please.'),
(93, 37, 1, 'Spanish Latte', 1, 5.50, 5.50, 'Medium', '2% Milk', 'Ice', 2, 2, 'Caramel drizzle', 'No extra syrup please.'),
(94, 37, 16, 'Nutella Frappe', 3, 7.80, 23.40, 'Large', 'Oat Milk', 'No ice', 2, 1, 'None', 'Please prepare for pickup.'),
(95, 38, 16, 'Nutella Frappe', 2, 7.80, 15.60, 'Large', 'Oat Milk', 'Extra ice', 1, 1, 'Chocolate sauce', 'Regular sweetness.'),
(96, 38, 12, 'Swedish Berry', 1, 3.00, 3.00, 'Small', 'No milk', 'No ice', 0, 3, 'None', 'Less sweet please.'),
(97, 38, 11, 'Dirty Matcha Latte', 1, 7.30, 7.30, 'Medium', 'Almond Milk', 'Extra ice', 1, 3, 'Chocolate sauce', 'No extra syrup please.'),
(98, 39, 17, 'White Chocolate Frappe', 2, 6.80, 13.60, 'Small', 'Almond Milk', 'No ice', 1, 3, 'Caramel drizzle', 'No extra syrup please.'),
(99, 39, 7, 'Caramel Macchiato', 1, 5.50, 5.50, 'Medium', '2% Milk', 'Extra ice', 2, 0, 'Chocolate sauce', 'No extra syrup please.'),
(100, 39, 8, 'Brown Sugar Shaken Espresso', 3, 6.50, 19.50, 'Large', '2% Milk', 'Extra ice', 2, 1, 'None', ''),
(101, 39, 23, 'Biscoff Latte', 1, 6.00, 6.00, 'Small', 'Whole Milk', 'No ice', 1, 0, 'Whipped cream', 'Less sweet please.'),
(102, 40, 1, 'Spanish Latte', 1, 5.67, 5.67, 'Medium', 'Almond Milk', 'Ice', 1, 2, 'Chocolate sauce', 'Please prepare for pickup.'),
(103, 41, 5, 'Caramel Vanilla Latte', 2, 6.00, 12.00, 'Large', '2% Milk', 'No ice', 1, 1, 'Caramel drizzle', 'Regular sweetness.'),
(104, 41, 16, 'Nutella Frappe', 2, 6.80, 13.60, 'Small', 'Soy Milk', 'Extra ice', 1, 3, 'Caramel drizzle', 'No extra syrup please.'),
(105, 42, 10, 'Matcha Latte', 2, 5.50, 11.00, 'Small', 'Whole Milk', 'Extra ice', 0, 3, 'None', 'Please prepare for pickup.'),
(106, 42, 20, 'Matcha Float', 3, 7.00, 21.00, 'Medium', 'Whole Milk', 'Extra ice', 0, 2, 'Whipped cream', ''),
(107, 42, 1, 'Spanish Latte', 2, 6.00, 12.00, 'Large', '2% Milk', 'No ice', 3, 2, 'Chocolate sauce', 'No extra syrup please.'),
(108, 43, 3, 'Americano', 2, 5.30, 10.60, 'Medium', 'Oat Milk', 'Ice', 1, 0, 'Caramel drizzle', ''),
(109, 43, 14, 'Hot Flat White', 1, 5.80, 5.80, 'Large', 'Soy Milk', 'None', 2, 0, 'None', 'Please prepare for pickup.'),
(110, 43, 22, 'Raspberry Tea', 3, 3.00, 9.00, 'Small', 'No milk', 'Extra ice', 0, 3, 'None', 'No extra syrup please.'),
(111, 43, 9, 'Hot Mocha', 1, 5.30, 5.30, 'Medium', 'Almond Milk', 'None', 2, 3, 'None', 'No extra syrup please.'),
(112, 44, 20, 'Matcha Float', 3, 7.50, 22.50, 'Large', '2% Milk', 'Extra ice', 0, 3, 'Whipped cream', 'Please prepare for pickup.'),
(113, 45, 22, 'Raspberry Tea', 1, 2.70, 2.70, 'Small', 'No milk', 'Extra ice', 0, 2, 'None', 'Please prepare for pickup.'),
(114, 45, 13, 'Cookies & Creme Frappe', 2, 5.85, 11.70, 'Medium', 'Whole Milk', 'Ice', 1, 1, 'Caramel drizzle', 'No extra syrup please.'),
(115, 45, 22, 'Raspberry Tea', 1, 3.60, 3.60, 'Large', 'No milk', 'Extra ice', 0, 2, 'None', 'Please prepare for pickup.'),
(116, 46, 5, 'Caramel Vanilla Latte', 2, 6.30, 12.60, 'Medium', 'Almond Milk', 'Ice', 2, 1, 'Chocolate sauce', 'No extra syrup please.'),
(117, 46, 7, 'Caramel Macchiato', 1, 6.80, 6.80, 'Large', 'Oat Milk', 'Ice', 1, 3, 'Caramel drizzle', 'No extra syrup please.'),
(118, 46, 22, 'Raspberry Tea', 3, 3.00, 9.00, 'Small', 'No milk', 'Extra ice', 0, 3, 'None', 'Less sweet please.'),
(119, 47, 22, 'Raspberry Tea', 3, 4.00, 12.00, 'Large', 'No milk', 'Ice', 0, 3, 'None', 'Please prepare for pickup.'),
(120, 47, 7, 'Caramel Macchiato', 2, 5.80, 11.60, 'Small', 'Soy Milk', 'No ice', 2, 3, 'None', 'No extra syrup please.'),
(121, 47, 18, 'Pistachio Matcha Latte', 1, 6.50, 6.50, 'Medium', '2% Milk', 'Extra ice', 0, 1, 'None', 'Regular sweetness.'),
(122, 47, 4, 'Hot Cappuccino', 1, 6.30, 6.30, 'Large', 'Oat Milk', 'None', 2, 3, 'None', 'Regular sweetness.'),
(123, 48, 12, 'Swedish Berry', 2, 3.00, 6.00, 'Small', 'No milk', 'No ice', 0, 1, 'None', ''),
(124, 49, 14, 'Hot Flat White', 3, 4.50, 13.50, 'Medium', '2% Milk', 'None', 1, 1, 'Whipped cream', ''),
(125, 49, 9, 'Hot Mocha', 1, 5.80, 5.80, 'Large', 'Soy Milk', 'None', 3, 2, 'Chocolate sauce', ''),
(126, 50, 10, 'Matcha Latte', 1, 5.85, 5.85, 'Large', '2% Milk', 'Ice', 0, 2, 'Chocolate sauce', 'Less sweet please.'),
(127, 50, 21, 'Vietnamese Iced Coffee', 2, 4.77, 9.54, 'Small', 'Oat Milk', 'Ice', 1, 1, 'Caramel drizzle', 'Less sweet please.'),
(128, 50, 13, 'Cookies & Creme Frappe', 1, 5.85, 5.85, 'Medium', 'Whole Milk', 'No ice', 2, 0, 'Whipped cream', 'Regular sweetness.'),
(129, 51, 13, 'Cookies & Creme Frappe', 1, 6.00, 6.00, 'Small', '2% Milk', 'Extra ice', 2, 3, 'Chocolate sauce', 'Less sweet please.'),
(130, 51, 21, 'Vietnamese Iced Coffee', 1, 5.80, 5.80, 'Medium', 'Oat Milk', 'No ice', 3, 1, 'Whipped cream', 'Less sweet please.'),
(131, 51, 20, 'Matcha Float', 2, 8.30, 16.60, 'Large', 'Almond Milk', 'Extra ice', 0, 2, 'None', 'Please prepare for pickup.'),
(132, 51, 16, 'Nutella Frappe', 2, 6.80, 13.60, 'Small', 'Oat Milk', 'Extra ice', 1, 2, 'Chocolate sauce', 'Regular sweetness.'),
(133, 52, 4, 'Hot Cappuccino', 2, 5.80, 11.60, 'Medium', 'Almond Milk', 'None', 3, 0, 'Whipped cream', 'No extra syrup please.'),
(134, 53, 10, 'Matcha Latte', 3, 6.50, 19.50, 'Large', '2% Milk', 'No ice', 0, 0, 'Chocolate sauce', 'Regular sweetness.'),
(135, 53, 3, 'Americano', 1, 4.80, 4.80, 'Small', 'Oat Milk', 'Extra ice', 3, 3, 'Caramel drizzle', 'No extra syrup please.'),
(136, 54, 13, 'Cookies & Creme Frappe', 2, 6.00, 12.00, 'Small', 'Whole Milk', 'Extra ice', 2, 0, 'None', ''),
(137, 54, 18, 'Pistachio Matcha Latte', 3, 7.30, 21.90, 'Medium', 'Soy Milk', 'No ice', 0, 0, 'Whipped cream', 'Less sweet please.'),
(138, 54, 23, 'Biscoff Latte', 2, 7.00, 14.00, 'Large', 'Whole Milk', 'Extra ice', 2, 0, 'None', 'Less sweet please.'),
(139, 55, 23, 'Biscoff Latte', 2, 6.57, 13.14, 'Medium', 'Oat Milk', 'Ice', 2, 0, 'None', ''),
(140, 55, 4, 'Hot Cappuccino', 2, 4.95, 9.90, 'Large', 'Whole Milk', 'None', 1, 0, 'None', 'No extra syrup please.'),
(141, 55, 6, 'Strawberry Cream Frappe', 2, 6.12, 12.24, 'Small', 'Almond Milk', 'Extra ice', 1, 3, 'Chocolate sauce', ''),
(142, 55, 22, 'Raspberry Tea', 2, 3.15, 6.30, 'Medium', 'No milk', 'No ice', 0, 2, 'None', 'Less sweet please.'),
(143, 56, 19, 'Tiramisu Frappe', 1, 7.50, 7.50, 'Large', 'Whole Milk', 'No ice', 2, 2, 'None', ''),
(144, 57, 17, 'White Chocolate Frappe', 1, 6.00, 6.00, 'Small', 'Whole Milk', 'No ice', 2, 1, 'None', 'Less sweet please.'),
(145, 57, 4, 'Hot Cappuccino', 1, 5.80, 5.80, 'Medium', 'Oat Milk', 'None', 2, 3, 'Caramel drizzle', 'No extra syrup please.'),
(146, 58, 10, 'Matcha Latte', 1, 6.80, 6.80, 'Medium', 'Soy Milk', 'No ice', 0, 0, 'None', ''),
(147, 58, 6, 'Strawberry Cream Frappe', 3, 7.80, 23.40, 'Large', 'Soy Milk', 'Ice', 2, 3, 'None', ''),
(148, 58, 15, 'Passion Fruit Tea', 2, 3.00, 6.00, 'Small', 'No milk', 'Ice', 0, 1, 'None', ''),
(149, 59, 8, 'Brown Sugar Shaken Espresso', 3, 7.30, 21.90, 'Large', 'Oat Milk', 'No ice', 2, 0, 'None', 'Less sweet please.'),
(150, 59, 15, 'Passion Fruit Tea', 3, 3.00, 9.00, 'Small', 'No milk', 'Ice', 0, 2, 'None', 'Regular sweetness.'),
(151, 59, 3, 'Americano', 1, 4.50, 4.50, 'Medium', '2% Milk', 'Ice', 2, 3, 'None', 'Regular sweetness.'),
(152, 59, 9, 'Hot Mocha', 2, 5.80, 11.60, 'Large', 'Oat Milk', 'None', 2, 2, 'None', 'Please prepare for pickup.'),
(153, 60, 16, 'Nutella Frappe', 3, 6.12, 18.36, 'Small', 'Oat Milk', 'Ice', 2, 0, 'Whipped cream', 'No extra syrup please.'),
(154, 60, 16, 'Nutella Frappe', 1, 6.57, 6.57, 'Medium', 'Almond Milk', 'Ice', 2, 0, 'None', 'Please prepare for pickup.'),
(155, 61, 22, 'Raspberry Tea', 1, 3.50, 3.50, 'Medium', 'No milk', 'No ice', 0, 0, 'None', ''),
(156, 61, 14, 'Hot Flat White', 1, 5.80, 5.80, 'Large', 'Almond Milk', 'None', 1, 2, 'None', 'No extra syrup please.'),
(157, 62, 17, 'White Chocolate Frappe', 1, 7.80, 7.80, 'Large', 'Oat Milk', 'Ice', 2, 0, 'Caramel drizzle', 'No extra syrup please.'),
(158, 62, 22, 'Raspberry Tea', 3, 3.00, 9.00, 'Small', 'No milk', 'Extra ice', 0, 0, 'None', 'Regular sweetness.'),
(159, 62, 5, 'Caramel Vanilla Latte', 2, 6.30, 12.60, 'Medium', 'Almond Milk', 'No ice', 1, 0, 'Caramel drizzle', 'Less sweet please.'),
(160, 63, 17, 'White Chocolate Frappe', 2, 6.80, 13.60, 'Small', 'Soy Milk', 'Extra ice', 2, 0, 'Whipped cream', 'Less sweet please.'),
(161, 63, 7, 'Caramel Macchiato', 1, 5.50, 5.50, 'Medium', '2% Milk', 'Ice', 2, 3, 'Chocolate sauce', 'Please prepare for pickup.'),
(162, 63, 12, 'Swedish Berry', 2, 4.00, 8.00, 'Large', 'No milk', 'Extra ice', 0, 1, 'None', 'Regular sweetness.'),
(163, 63, 9, 'Hot Mocha', 3, 4.80, 14.40, 'Small', 'Oat Milk', 'None', 2, 0, 'Whipped cream', 'No extra syrup please.'),
(164, 64, 19, 'Tiramisu Frappe', 2, 7.80, 15.60, 'Medium', 'Almond Milk', 'Ice', 3, 0, 'None', 'Less sweet please.'),
(165, 65, 16, 'Nutella Frappe', 1, 6.30, 6.30, 'Large', '2% Milk', 'No ice', 3, 1, 'Chocolate sauce', 'Less sweet please.'),
(166, 65, 17, 'White Chocolate Frappe', 2, 5.40, 10.80, 'Small', '2% Milk', 'Extra ice', 2, 0, 'Chocolate sauce', 'No extra syrup please.'),
(167, 66, 1, 'Spanish Latte', 1, 5.80, 5.80, 'Small', 'Almond Milk', 'Extra ice', 1, 1, 'Chocolate sauce', 'Please prepare for pickup.'),
(168, 66, 12, 'Swedish Berry', 1, 3.50, 3.50, 'Medium', 'No milk', 'Ice', 0, 1, 'None', 'Please prepare for pickup.'),
(169, 66, 13, 'Cookies & Creme Frappe', 2, 7.80, 15.60, 'Large', 'Oat Milk', 'No ice', 3, 1, 'None', 'No extra syrup please.'),
(170, 67, 3, 'Americano', 2, 4.50, 9.00, 'Medium', 'Whole Milk', 'No ice', 2, 2, 'None', ''),
(171, 67, 15, 'Passion Fruit Tea', 1, 4.00, 4.00, 'Large', 'No milk', 'Extra ice', 0, 2, 'None', 'No extra syrup please.'),
(172, 67, 17, 'White Chocolate Frappe', 2, 6.00, 12.00, 'Small', '2% Milk', 'Ice', 2, 3, 'Whipped cream', 'No extra syrup please.'),
(173, 67, 18, 'Pistachio Matcha Latte', 2, 6.50, 13.00, 'Medium', '2% Milk', 'No ice', 0, 2, 'Whipped cream', 'Please prepare for pickup.'),
(174, 68, 20, 'Matcha Float', 2, 8.30, 16.60, 'Large', 'Soy Milk', 'Ice', 0, 2, 'Caramel drizzle', 'Please prepare for pickup.'),
(175, 69, 13, 'Cookies & Creme Frappe', 3, 6.80, 20.40, 'Small', 'Soy Milk', 'Ice', 2, 1, 'Whipped cream', 'Please prepare for pickup.'),
(176, 69, 18, 'Pistachio Matcha Latte', 3, 7.30, 21.90, 'Medium', 'Soy Milk', 'Extra ice', 0, 3, 'Caramel drizzle', 'Less sweet please.'),
(177, 70, 4, 'Hot Cappuccino', 1, 4.50, 4.50, 'Medium', 'Whole Milk', 'None', 2, 2, 'None', 'No extra syrup please.'),
(178, 70, 14, 'Hot Flat White', 3, 5.22, 15.66, 'Large', 'Oat Milk', 'None', 2, 3, 'Caramel drizzle', 'Less sweet please.'),
(179, 70, 13, 'Cookies & Creme Frappe', 2, 6.12, 12.24, 'Small', 'Almond Milk', 'Extra ice', 2, 1, 'None', ''),
(180, 71, 11, 'Dirty Matcha Latte', 1, 7.80, 7.80, 'Large', 'Almond Milk', 'Ice', 2, 0, 'Chocolate sauce', 'Regular sweetness.'),
(181, 71, 8, 'Brown Sugar Shaken Espresso', 1, 5.50, 5.50, 'Small', 'Whole Milk', 'No ice', 1, 2, 'None', ''),
(182, 71, 22, 'Raspberry Tea', 3, 3.50, 10.50, 'Medium', 'No milk', 'Ice', 0, 3, 'None', 'Less sweet please.'),
(183, 71, 10, 'Matcha Latte', 1, 7.30, 7.30, 'Large', 'Almond Milk', 'Ice', 0, 0, 'Whipped cream', 'Regular sweetness.'),
(184, 72, 22, 'Raspberry Tea', 1, 3.00, 3.00, 'Small', 'No milk', 'Ice', 0, 0, 'None', 'Less sweet please.'),
(185, 73, 12, 'Swedish Berry', 2, 3.50, 7.00, 'Medium', 'No milk', 'Extra ice', 0, 2, 'None', 'Please prepare for pickup.'),
(186, 73, 6, 'Strawberry Cream Frappe', 2, 7.80, 15.60, 'Large', 'Almond Milk', 'Extra ice', 2, 3, 'Caramel drizzle', 'No extra syrup please.'),
(187, 74, 1, 'Spanish Latte', 2, 6.80, 13.60, 'Large', 'Oat Milk', 'No ice', 3, 0, 'None', 'Regular sweetness.'),
(188, 74, 23, 'Biscoff Latte', 1, 6.00, 6.00, 'Small', 'Whole Milk', 'No ice', 2, 2, 'None', 'No extra syrup please.'),
(189, 74, 11, 'Dirty Matcha Latte', 2, 6.50, 13.00, 'Medium', '2% Milk', 'No ice', 3, 1, 'None', 'No extra syrup please.'),
(190, 75, 13, 'Cookies & Creme Frappe', 3, 5.40, 16.20, 'Small', '2% Milk', 'Ice', 2, 0, 'Whipped cream', 'Less sweet please.'),
(191, 75, 7, 'Caramel Macchiato', 2, 5.67, 11.34, 'Medium', 'Oat Milk', 'Extra ice', 1, 3, 'None', 'No extra syrup please.'),
(192, 75, 22, 'Raspberry Tea', 1, 3.60, 3.60, 'Large', 'No milk', 'Ice', 0, 0, 'None', 'Regular sweetness.'),
(193, 75, 5, 'Caramel Vanilla Latte', 2, 5.22, 10.44, 'Small', 'Soy Milk', 'No ice', 2, 3, 'None', 'Less sweet please.'),
(194, 75, 13, 'Cookies & Creme Frappe', 1, 6.57, 6.57, 'Medium', 'Soy Milk', 'Extra ice', 1, 2, 'None', ''),
(195, 76, 17, 'White Chocolate Frappe', 2, 7.30, 14.60, 'Medium', 'Almond Milk', 'No ice', 2, 3, 'None', 'No extra syrup please.'),
(196, 77, 10, 'Matcha Latte', 1, 6.50, 6.50, 'Large', 'Whole Milk', 'No ice', 0, 3, 'None', 'Please prepare for pickup.'),
(197, 77, 5, 'Caramel Vanilla Latte', 1, 5.80, 5.80, 'Small', 'Oat Milk', 'Ice', 1, 2, 'None', 'Regular sweetness.'),
(198, 78, 12, 'Swedish Berry', 1, 3.00, 3.00, 'Small', 'No milk', 'No ice', 0, 2, 'None', 'Regular sweetness.'),
(199, 78, 23, 'Biscoff Latte', 1, 7.30, 7.30, 'Medium', 'Soy Milk', 'Ice', 2, 0, 'None', 'Less sweet please.'),
(200, 78, 18, 'Pistachio Matcha Latte', 1, 7.80, 7.80, 'Large', 'Almond Milk', 'No ice', 0, 1, 'None', 'No extra syrup please.'),
(201, 79, 2, 'Vanilla Latte', 2, 5.00, 10.00, 'Medium', '2% Milk', 'Extra ice', 1, 2, 'Whipped cream', 'Regular sweetness.'),
(202, 79, 12, 'Swedish Berry', 1, 4.00, 4.00, 'Large', 'No milk', 'Extra ice', 0, 0, 'None', 'No extra syrup please.'),
(203, 79, 6, 'Strawberry Cream Frappe', 2, 6.00, 12.00, 'Small', '2% Milk', 'Ice', 2, 1, 'None', 'Please prepare for pickup.'),
(204, 79, 16, 'Nutella Frappe', 1, 7.30, 7.30, 'Medium', 'Almond Milk', 'Ice', 2, 2, 'Caramel drizzle', 'Please prepare for pickup.'),
(205, 80, 7, 'Caramel Macchiato', 1, 6.12, 6.12, 'Large', 'Oat Milk', 'No ice', 3, 3, 'Whipped cream', 'Regular sweetness.'),
(206, 81, 11, 'Dirty Matcha Latte', 2, 6.80, 13.60, 'Small', 'Soy Milk', 'No ice', 1, 3, 'Whipped cream', 'Please prepare for pickup.'),
(207, 81, 13, 'Cookies & Creme Frappe', 1, 7.30, 7.30, 'Medium', 'Soy Milk', 'No ice', 3, 1, 'None', 'Regular sweetness.'),
(208, 82, 13, 'Cookies & Creme Frappe', 1, 7.30, 7.30, 'Medium', 'Soy Milk', 'Ice', 1, 3, 'Caramel drizzle', 'No extra syrup please.'),
(209, 82, 5, 'Caramel Vanilla Latte', 1, 6.80, 6.80, 'Large', 'Oat Milk', 'Extra ice', 3, 1, 'Whipped cream', ''),
(210, 82, 2, 'Vanilla Latte', 1, 5.30, 5.30, 'Small', 'Soy Milk', 'Extra ice', 2, 0, 'None', ''),
(211, 83, 21, 'Vietnamese Iced Coffee', 2, 6.30, 12.60, 'Large', 'Almond Milk', 'No ice', 2, 0, 'None', 'Less sweet please.'),
(212, 83, 18, 'Pistachio Matcha Latte', 3, 6.00, 18.00, 'Small', 'Whole Milk', 'Extra ice', 0, 1, 'None', ''),
(213, 83, 13, 'Cookies & Creme Frappe', 3, 7.30, 21.90, 'Medium', 'Oat Milk', 'Ice', 3, 2, 'Whipped cream', 'No extra syrup please.'),
(214, 83, 17, 'White Chocolate Frappe', 1, 7.80, 7.80, 'Large', 'Oat Milk', 'Ice', 2, 3, 'None', 'Regular sweetness.'),
(215, 84, 14, 'Hot Flat White', 2, 4.00, 8.00, 'Small', 'Whole Milk', 'None', 3, 3, 'None', ''),
(216, 85, 18, 'Pistachio Matcha Latte', 2, 6.57, 13.14, 'Medium', 'Almond Milk', 'No ice', 0, 0, 'Whipped cream', 'No extra syrup please.'),
(217, 85, 6, 'Strawberry Cream Frappe', 3, 6.30, 18.90, 'Large', '2% Milk', 'Ice', 1, 3, 'Caramel drizzle', ''),
(218, 86, 23, 'Biscoff Latte', 1, 7.00, 7.00, 'Large', '2% Milk', 'No ice', 2, 0, 'None', ''),
(219, 86, 11, 'Dirty Matcha Latte', 1, 6.80, 6.80, 'Small', 'Oat Milk', 'Ice', 2, 0, 'Chocolate sauce', 'Less sweet please.'),
(220, 86, 18, 'Pistachio Matcha Latte', 1, 6.50, 6.50, 'Medium', 'Whole Milk', 'Extra ice', 0, 0, 'None', 'Regular sweetness.'),
(221, 87, 3, 'Americano', 1, 4.00, 4.00, 'Small', '2% Milk', 'No ice', 3, 2, 'Caramel drizzle', 'Less sweet please.'),
(222, 87, 11, 'Dirty Matcha Latte', 1, 6.50, 6.50, 'Medium', 'Whole Milk', 'No ice', 1, 3, 'None', 'Less sweet please.'),
(223, 87, 17, 'White Chocolate Frappe', 3, 7.80, 23.40, 'Large', 'Almond Milk', 'Ice', 3, 0, 'Caramel drizzle', 'Regular sweetness.'),
(224, 87, 21, 'Vietnamese Iced Coffee', 1, 5.30, 5.30, 'Small', 'Soy Milk', 'Extra ice', 2, 1, 'Caramel drizzle', ''),
(225, 88, 20, 'Matcha Float', 1, 7.80, 7.80, 'Medium', 'Almond Milk', 'Ice', 0, 0, 'Caramel drizzle', 'Less sweet please.'),
(226, 89, 19, 'Tiramisu Frappe', 1, 7.50, 7.50, 'Large', '2% Milk', 'No ice', 3, 2, 'Caramel drizzle', 'No extra syrup please.'),
(227, 89, 3, 'Americano', 1, 4.80, 4.80, 'Small', 'Oat Milk', 'Extra ice', 1, 0, 'Chocolate sauce', 'Less sweet please.'),
(228, 90, 9, 'Hot Mocha', 1, 4.32, 4.32, 'Small', 'Oat Milk', 'None', 2, 3, 'Caramel drizzle', ''),
(229, 90, 5, 'Caramel Vanilla Latte', 1, 5.67, 5.67, 'Medium', 'Oat Milk', 'No ice', 1, 0, 'None', 'Please prepare for pickup.'),
(230, 90, 6, 'Strawberry Cream Frappe', 3, 7.02, 21.06, 'Large', 'Soy Milk', 'No ice', 1, 3, 'None', 'No extra syrup please.'),
(231, 90, 9, 'Hot Mocha', 3, 3.60, 10.80, 'Small', 'Whole Milk', 'None', 2, 3, 'None', 'Regular sweetness.'),
(232, 91, 9, 'Hot Mocha', 1, 5.30, 5.30, 'Medium', 'Oat Milk', 'None', 2, 2, 'None', ''),
(233, 91, 16, 'Nutella Frappe', 1, 7.00, 7.00, 'Large', 'Whole Milk', 'Extra ice', 2, 0, 'Whipped cream', ''),
(234, 91, 23, 'Biscoff Latte', 2, 6.80, 13.60, 'Small', 'Soy Milk', 'Extra ice', 3, 0, 'Caramel drizzle', 'Please prepare for pickup.'),
(235, 91, 19, 'Tiramisu Frappe', 1, 7.00, 7.00, 'Medium', 'Whole Milk', 'Extra ice', 2, 3, 'Caramel drizzle', ''),
(236, 92, 16, 'Nutella Frappe', 1, 7.80, 7.80, 'Large', 'Oat Milk', 'Ice', 2, 2, 'Whipped cream', 'No extra syrup please.'),
(237, 93, 11, 'Dirty Matcha Latte', 2, 6.00, 12.00, 'Small', 'Whole Milk', 'No ice', 2, 1, 'None', 'Please prepare for pickup.'),
(238, 93, 4, 'Hot Cappuccino', 1, 5.80, 5.80, 'Medium', 'Almond Milk', 'None', 1, 1, 'None', 'Less sweet please.'),
(239, 94, 2, 'Vanilla Latte', 1, 5.80, 5.80, 'Medium', 'Oat Milk', 'Ice', 2, 0, 'None', 'Less sweet please.'),
(240, 94, 18, 'Pistachio Matcha Latte', 2, 7.80, 15.60, 'Large', 'Soy Milk', 'Extra ice', 0, 0, 'None', ''),
(241, 94, 12, 'Swedish Berry', 1, 3.00, 3.00, 'Small', 'No milk', 'Ice', 0, 0, 'None', 'Please prepare for pickup.'),
(242, 95, 17, 'White Chocolate Frappe', 1, 6.30, 6.30, 'Large', 'Whole Milk', 'No ice', 2, 0, 'Whipped cream', 'Regular sweetness.'),
(243, 95, 10, 'Matcha Latte', 2, 5.67, 11.34, 'Small', 'Oat Milk', 'No ice', 0, 2, 'Caramel drizzle', 'No extra syrup please.'),
(244, 95, 22, 'Raspberry Tea', 1, 3.15, 3.15, 'Medium', 'No milk', 'Ice', 0, 3, 'None', ''),
(245, 95, 18, 'Pistachio Matcha Latte', 1, 7.02, 7.02, 'Large', 'Oat Milk', 'Extra ice', 0, 1, 'None', 'No extra syrup please.'),
(246, 96, 10, 'Matcha Latte', 2, 5.50, 11.00, 'Small', '2% Milk', 'Ice', 0, 2, 'Caramel drizzle', 'No extra syrup please.'),
(247, 97, 21, 'Vietnamese Iced Coffee', 2, 5.80, 11.60, 'Medium', 'Oat Milk', 'Extra ice', 3, 0, 'Chocolate sauce', 'Regular sweetness.'),
(248, 97, 19, 'Tiramisu Frappe', 2, 8.30, 16.60, 'Large', 'Oat Milk', 'No ice', 2, 0, 'None', 'Regular sweetness.'),
(249, 98, 11, 'Dirty Matcha Latte', 3, 7.80, 23.40, 'Large', 'Oat Milk', 'Extra ice', 2, 0, 'Caramel drizzle', 'Less sweet please.'),
(250, 98, 6, 'Strawberry Cream Frappe', 3, 6.80, 20.40, 'Small', 'Soy Milk', 'Ice', 1, 1, 'None', 'Less sweet please.'),
(251, 98, 23, 'Biscoff Latte', 1, 7.30, 7.30, 'Medium', 'Soy Milk', 'Extra ice', 3, 2, 'Whipped cream', ''),
(252, 99, 5, 'Caramel Vanilla Latte', 3, 5.80, 17.40, 'Small', 'Soy Milk', 'No ice', 2, 1, 'Whipped cream', 'Please prepare for pickup.'),
(253, 99, 6, 'Strawberry Cream Frappe', 2, 7.30, 14.60, 'Medium', 'Soy Milk', 'Extra ice', 2, 2, 'Chocolate sauce', 'Regular sweetness.'),
(254, 99, 3, 'Americano', 1, 5.80, 5.80, 'Large', 'Oat Milk', 'Extra ice', 2, 2, 'Chocolate sauce', ''),
(255, 99, 13, 'Cookies & Creme Frappe', 2, 6.00, 12.00, 'Small', 'Whole Milk', 'No ice', 3, 0, 'None', 'Regular sweetness.'),
(256, 100, 10, 'Matcha Latte', 2, 5.40, 10.80, 'Medium', 'Whole Milk', 'Ice', 0, 1, 'None', 'Regular sweetness.'),
(257, 101, 10, 'Matcha Latte', 2, 6.50, 13.00, 'Large', 'Whole Milk', 'Ice', 0, 0, 'Caramel drizzle', 'Regular sweetness.'),
(258, 101, 19, 'Tiramisu Frappe', 3, 7.30, 21.90, 'Small', 'Oat Milk', 'No ice', 1, 0, 'None', 'No extra syrup please.'),
(259, 102, 3, 'Americano', 2, 4.80, 9.60, 'Small', 'Almond Milk', 'No ice', 3, 1, 'Caramel drizzle', ''),
(260, 102, 1, 'Spanish Latte', 1, 6.30, 6.30, 'Medium', 'Oat Milk', 'Extra ice', 3, 0, 'Caramel drizzle', 'Regular sweetness.'),
(261, 102, 10, 'Matcha Latte', 1, 7.30, 7.30, 'Large', 'Soy Milk', 'No ice', 0, 0, 'None', ''),
(262, 103, 18, 'Pistachio Matcha Latte', 1, 7.30, 7.30, 'Medium', 'Almond Milk', 'Ice', 0, 0, 'Chocolate sauce', ''),
(263, 103, 4, 'Hot Cappuccino', 1, 6.30, 6.30, 'Large', 'Soy Milk', 'None', 2, 3, 'Whipped cream', 'Regular sweetness.'),
(264, 103, 8, 'Brown Sugar Shaken Espresso', 3, 5.50, 16.50, 'Small', '2% Milk', 'No ice', 3, 1, 'Whipped cream', 'Less sweet please.'),
(265, 103, 3, 'Americano', 1, 5.30, 5.30, 'Medium', 'Oat Milk', 'Ice', 3, 0, 'Whipped cream', ''),
(266, 104, 11, 'Dirty Matcha Latte', 1, 7.80, 7.80, 'Large', 'Soy Milk', 'Ice', 3, 0, 'Whipped cream', 'Less sweet please.'),
(267, 105, 12, 'Swedish Berry', 3, 2.70, 8.10, 'Small', 'No milk', 'Extra ice', 0, 3, 'None', 'Please prepare for pickup.'),
(268, 105, 19, 'Tiramisu Frappe', 2, 7.02, 14.04, 'Medium', 'Soy Milk', 'Extra ice', 1, 2, 'Caramel drizzle', 'Please prepare for pickup.'),
(269, 105, 12, 'Swedish Berry', 3, 3.60, 10.80, 'Large', 'No milk', 'Ice', 0, 2, 'None', 'Regular sweetness.'),
(270, 106, 18, 'Pistachio Matcha Latte', 1, 7.30, 7.30, 'Medium', 'Almond Milk', 'Ice', 0, 0, 'None', 'Regular sweetness.'),
(271, 106, 11, 'Dirty Matcha Latte', 1, 7.80, 7.80, 'Large', 'Soy Milk', 'Extra ice', 2, 2, 'None', 'Less sweet please.'),
(272, 106, 23, 'Biscoff Latte', 1, 6.00, 6.00, 'Small', '2% Milk', 'Ice', 3, 2, 'None', 'Regular sweetness.'),
(273, 107, 11, 'Dirty Matcha Latte', 2, 7.00, 14.00, 'Large', '2% Milk', 'Ice', 3, 0, 'Whipped cream', 'Please prepare for pickup.'),
(274, 107, 10, 'Matcha Latte', 1, 6.30, 6.30, 'Small', 'Oat Milk', 'Extra ice', 0, 3, 'Whipped cream', ''),
(275, 107, 19, 'Tiramisu Frappe', 1, 7.00, 7.00, 'Medium', '2% Milk', 'No ice', 3, 2, 'None', 'Please prepare for pickup.'),
(276, 107, 23, 'Biscoff Latte', 1, 7.80, 7.80, 'Large', 'Oat Milk', 'No ice', 3, 1, 'Caramel drizzle', 'Regular sweetness.'),
(277, 108, 1, 'Spanish Latte', 3, 5.80, 17.40, 'Small', 'Almond Milk', 'Ice', 2, 1, 'Chocolate sauce', 'Less sweet please.'),
(278, 109, 15, 'Passion Fruit Tea', 1, 3.50, 3.50, 'Medium', 'No milk', 'No ice', 0, 3, 'None', 'Regular sweetness.'),
(279, 109, 13, 'Cookies & Creme Frappe', 3, 7.80, 23.40, 'Large', 'Oat Milk', 'Ice', 1, 1, 'Whipped cream', 'No extra syrup please.'),
(280, 110, 11, 'Dirty Matcha Latte', 3, 6.30, 18.90, 'Large', 'Whole Milk', 'Ice', 2, 1, 'None', 'Please prepare for pickup.'),
(281, 110, 14, 'Hot Flat White', 2, 3.60, 7.20, 'Small', 'Whole Milk', 'None', 2, 2, 'None', 'No extra syrup please.'),
(282, 110, 21, 'Vietnamese Iced Coffee', 1, 5.22, 5.22, 'Medium', 'Almond Milk', 'Extra ice', 2, 2, 'Whipped cream', ''),
(283, 111, 14, 'Hot Flat White', 1, 4.80, 4.80, 'Small', 'Almond Milk', 'None', 3, 3, 'Whipped cream', 'Less sweet please.'),
(284, 111, 6, 'Strawberry Cream Frappe', 2, 6.50, 13.00, 'Medium', 'Whole Milk', 'No ice', 1, 2, 'Caramel drizzle', 'Less sweet please.'),
(285, 111, 21, 'Vietnamese Iced Coffee', 3, 6.30, 18.90, 'Large', 'Oat Milk', 'Extra ice', 1, 2, 'None', 'Regular sweetness.'),
(286, 111, 8, 'Brown Sugar Shaken Espresso', 2, 5.50, 11.00, 'Small', '2% Milk', 'Extra ice', 2, 2, 'Caramel drizzle', ''),
(287, 112, 12, 'Swedish Berry', 3, 3.50, 10.50, 'Medium', 'No milk', 'No ice', 0, 0, 'None', 'Less sweet please.'),
(288, 113, 4, 'Hot Cappuccino', 2, 6.30, 12.60, 'Large', 'Almond Milk', 'None', 2, 1, 'Caramel drizzle', 'No extra syrup please.'),
(289, 113, 16, 'Nutella Frappe', 3, 6.80, 20.40, 'Small', 'Soy Milk', 'No ice', 2, 2, 'Whipped cream', 'No extra syrup please.'),
(290, 114, 14, 'Hot Flat White', 1, 4.00, 4.00, 'Small', '2% Milk', 'None', 3, 2, 'None', 'Please prepare for pickup.'),
(291, 114, 9, 'Hot Mocha', 2, 5.30, 10.60, 'Medium', 'Almond Milk', 'None', 1, 2, 'None', 'No extra syrup please.'),
(292, 114, 5, 'Caramel Vanilla Latte', 1, 6.80, 6.80, 'Large', 'Soy Milk', 'Ice', 2, 3, 'Caramel drizzle', ''),
(293, 115, 18, 'Pistachio Matcha Latte', 1, 6.57, 6.57, 'Medium', 'Oat Milk', 'No ice', 0, 1, 'None', ''),
(294, 115, 7, 'Caramel Macchiato', 2, 5.40, 10.80, 'Large', 'Whole Milk', 'No ice', 2, 0, 'None', 'Please prepare for pickup.'),
(295, 115, 23, 'Biscoff Latte', 3, 5.40, 16.20, 'Small', 'Whole Milk', 'Ice', 2, 0, 'None', 'Less sweet please.'),
(296, 115, 5, 'Caramel Vanilla Latte', 1, 4.95, 4.95, 'Medium', '2% Milk', 'No ice', 2, 2, 'None', 'No extra syrup please.'),
(297, 116, 13, 'Cookies & Creme Frappe', 1, 7.00, 7.00, 'Large', 'Whole Milk', 'Extra ice', 2, 0, 'Whipped cream', 'Less sweet please.'),
(298, 117, 15, 'Passion Fruit Tea', 2, 3.00, 6.00, 'Small', 'No milk', 'Extra ice', 0, 1, 'None', 'Please prepare for pickup.'),
(299, 117, 7, 'Caramel Macchiato', 2, 6.30, 12.60, 'Medium', 'Almond Milk', 'Ice', 3, 2, 'Caramel drizzle', 'Please prepare for pickup.'),
(300, 118, 16, 'Nutella Frappe', 1, 7.30, 7.30, 'Medium', 'Oat Milk', 'Extra ice', 2, 1, 'Caramel drizzle', 'Regular sweetness.'),
(301, 118, 2, 'Vanilla Latte', 1, 6.30, 6.30, 'Large', 'Almond Milk', 'Ice', 1, 1, 'Chocolate sauce', 'Please prepare for pickup.'),
(302, 118, 1, 'Spanish Latte', 2, 5.80, 11.60, 'Small', 'Soy Milk', 'Ice', 2, 0, 'Caramel drizzle', 'Regular sweetness.'),
(303, 119, 7, 'Caramel Macchiato', 2, 6.00, 12.00, 'Large', 'Whole Milk', 'Ice', 2, 3, 'None', 'No extra syrup please.'),
(304, 119, 2, 'Vanilla Latte', 3, 5.30, 15.90, 'Small', 'Almond Milk', 'Extra ice', 3, 1, 'Chocolate sauce', 'Please prepare for pickup.'),
(305, 119, 9, 'Hot Mocha', 2, 5.30, 10.60, 'Medium', 'Oat Milk', 'None', 2, 2, 'Whipped cream', 'No extra syrup please.'),
(306, 119, 17, 'White Chocolate Frappe', 1, 7.00, 7.00, 'Large', '2% Milk', 'Ice', 1, 3, 'None', 'Regular sweetness.'),
(307, 120, 16, 'Nutella Frappe', 3, 5.40, 16.20, 'Small', 'Whole Milk', 'No ice', 2, 3, 'None', 'Regular sweetness.'),
(308, 120, 16, 'Nutella Frappe', 1, 6.57, 6.57, 'Medium', 'Almond Milk', 'Ice', 3, 0, 'None', '');

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
) ;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `customer_id`, `menu_id`, `rating`, `comment`, `review_date`) VALUES
(1, 1, 13, 4, 'Prepared just how I requested.', '2026-08-01 04:11:00'),
(2, 1, 23, 5, 'A good drink for a quick pickup.', '2026-08-01 04:11:00'),
(3, 8, 16, 3, 'Enjoyed the flavour; I would order it again.', '2026-08-01 05:22:00'),
(4, 8, 12, 4, 'Nice balance of sweetness and coffee.', '2026-08-01 05:22:00'),
(5, 8, 15, 5, 'Smooth flavour and a lovely afternoon treat.', '2026-08-01 05:22:00'),
(6, 15, 2, 4, 'Prepared just how I requested.', '2026-08-02 06:33:00'),
(7, 15, 4, 5, 'A good drink for a quick pickup.', '2026-08-02 06:33:00'),
(8, 15, 21, 3, 'Enjoyed the flavour; I would order it again.', '2026-08-02 06:33:00'),
(9, 15, 6, 4, 'Nice balance of sweetness and coffee.', '2026-08-02 06:33:00'),
(10, 22, 14, 5, 'Smooth flavour and a lovely afternoon treat.', '2026-08-04 08:55:00'),
(11, 22, 10, 4, 'Prepared just how I requested.', '2026-08-04 08:55:00'),
(12, 5, 16, 5, 'A good drink for a quick pickup.', '2026-08-05 09:06:00'),
(13, 5, 20, 3, 'Enjoyed the flavour; I would order it again.', '2026-08-05 09:06:00'),
(14, 5, 5, 4, 'Nice balance of sweetness and coffee.', '2026-08-05 09:06:00'),
(15, 12, 10, 5, 'Smooth flavour and a lovely afternoon treat.', '2026-08-05 10:17:00'),
(16, 12, 21, 4, 'Prepared just how I requested.', '2026-08-05 10:17:00'),
(17, 12, 15, 5, 'A good drink for a quick pickup.', '2026-08-05 10:17:00'),
(18, 12, 6, 3, 'Enjoyed the flavour; I would order it again.', '2026-08-05 10:17:00'),
(19, 19, 8, 4, 'Nice balance of sweetness and coffee.', '2026-08-07 12:39:00'),
(20, 19, 5, 5, 'Smooth flavour and a lovely afternoon treat.', '2026-08-07 12:39:00'),
(21, 2, 22, 4, 'Prepared just how I requested.', '2026-08-08 03:50:00'),
(22, 2, 23, 5, 'A good drink for a quick pickup.', '2026-08-08 03:50:00'),
(23, 2, 11, 3, 'Enjoyed the flavour; I would order it again.', '2026-08-08 03:50:00'),
(24, 9, 12, 4, 'Nice balance of sweetness and coffee.', '2026-08-09 04:01:00'),
(25, 9, 6, 5, 'Smooth flavour and a lovely afternoon treat.', '2026-08-09 04:01:00'),
(26, 9, 9, 4, 'Prepared just how I requested.', '2026-08-09 04:01:00'),
(27, 9, 1, 5, 'A good drink for a quick pickup.', '2026-08-09 04:01:00'),
(28, 16, 10, 3, 'Enjoyed the flavour; I would order it again.', '2026-08-10 06:23:00'),
(29, 16, 19, 4, 'Nice balance of sweetness and coffee.', '2026-08-10 06:23:00'),
(30, 23, 10, 5, 'Smooth flavour and a lovely afternoon treat.', '2026-08-11 07:34:00'),
(31, 23, 12, 4, 'Prepared just how I requested.', '2026-08-11 07:34:00'),
(32, 23, 22, 5, 'A good drink for a quick pickup.', '2026-08-11 07:34:00'),
(33, 6, 8, 3, 'Enjoyed the flavour; I would order it again.', '2026-08-12 08:45:00'),
(34, 6, 20, 4, 'Nice balance of sweetness and coffee.', '2026-08-12 08:45:00'),
(35, 6, 11, 5, 'Smooth flavour and a lovely afternoon treat.', '2026-08-12 08:45:00'),
(36, 6, 10, 4, 'Prepared just how I requested.', '2026-08-12 08:45:00'),
(37, 13, 18, 5, 'A good drink for a quick pickup.', '2026-08-14 10:07:00'),
(38, 13, 3, 3, 'Enjoyed the flavour; I would order it again.', '2026-08-14 10:07:00'),
(39, 20, 9, 4, 'Nice balance of sweetness and coffee.', '2026-08-14 11:18:00'),
(40, 20, 7, 5, 'Smooth flavour and a lovely afternoon treat.', '2026-08-14 11:18:00'),
(41, 20, 21, 4, 'Prepared just how I requested.', '2026-08-14 11:18:00'),
(42, 3, 8, 5, 'A good drink for a quick pickup.', '2026-08-15 12:29:00'),
(43, 3, 14, 3, 'Enjoyed the flavour; I would order it again.', '2026-08-15 12:29:00'),
(44, 3, 19, 4, 'Nice balance of sweetness and coffee.', '2026-08-15 12:29:00'),
(45, 3, 11, 5, 'Smooth flavour and a lovely afternoon treat.', '2026-08-15 12:29:00'),
(46, 10, 2, 4, 'Prepared just how I requested.', '2026-08-17 04:51:00'),
(47, 10, 13, 5, 'A good drink for a quick pickup.', '2026-08-17 04:51:00'),
(48, 17, 12, 3, 'Enjoyed the flavour; I would order it again.', '2026-08-18 05:02:00'),
(49, 17, 5, 4, 'Nice balance of sweetness and coffee.', '2026-08-18 05:02:00'),
(50, 17, 1, 5, 'Smooth flavour and a lovely afternoon treat.', '2026-08-18 05:02:00'),
(51, 24, 1, 4, 'Prepared just how I requested.', '2026-08-19 06:13:00'),
(52, 24, 10, 5, 'A good drink for a quick pickup.', '2026-08-19 06:13:00'),
(53, 24, 16, 3, 'Enjoyed the flavour; I would order it again.', '2026-08-19 06:13:00'),
(54, 24, 23, 4, 'Nice balance of sweetness and coffee.', '2026-08-19 06:13:00'),
(55, 7, 4, 5, 'Smooth flavour and a lovely afternoon treat.', '2026-08-20 08:35:00'),
(56, 7, 22, 4, 'Prepared just how I requested.', '2026-08-20 08:35:00'),
(57, 14, 1, 5, 'A good drink for a quick pickup.', '2026-08-21 09:46:00'),
(58, 14, 9, 3, 'Enjoyed the flavour; I would order it again.', '2026-08-21 09:46:00'),
(59, 14, 11, 4, 'Nice balance of sweetness and coffee.', '2026-08-21 09:46:00'),
(60, 21, 5, 5, 'Smooth flavour and a lovely afternoon treat.', '2026-08-22 10:57:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `unique_admin_username` (`username`),
  ADD UNIQUE KEY `unique_admin_email` (`email`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `unique_category_name` (`category_name`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`),
  ADD UNIQUE KEY `unique_customer_email` (`email`);

--
-- Indexes for table `menu_item`
--
ALTER TABLE `menu_item`
  ADD PRIMARY KEY (`menu_id`),
  ADD KEY `idx_menu_category` (`category_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `idx_order_customer` (`customer_id`),
  ADD KEY `idx_order_email` (`customer_email`),
  ADD KEY `idx_order_date` (`order_date`),
  ADD KEY `idx_order_status` (`status`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `idx_order_item_order` (`order_id`),
  ADD KEY `idx_order_item_menu` (`menu_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD UNIQUE KEY `unique_customer_review` (`customer_id`,`menu_id`),
  ADD KEY `idx_review_customer` (`customer_id`),
  ADD KEY `idx_review_menu` (`menu_id`);

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
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `menu_item`
--
ALTER TABLE `menu_item`
  MODIFY `menu_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT;

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
  ADD CONSTRAINT `fk_menu_category` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`) ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_order_customer` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON UPDATE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_order_item_menu` FOREIGN KEY (`menu_id`) REFERENCES `menu_item` (`menu_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_order_item_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_review_customer` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_review_menu` FOREIGN KEY (`menu_id`) REFERENCES `menu_item` (`menu_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
