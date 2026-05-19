-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 19, 2026 at 07:15 AM
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
-- Database: `fnbdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `branch`
--

CREATE TABLE `branch` (
  `branchId` int(10) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp(),
  `address` varchar(255) DEFAULT NULL,
  `status` enum('Closed','Setup','Opening','Deprecated') NOT NULL DEFAULT 'Closed',
  `contactNumber` varchar(20) DEFAULT NULL,
  `startTime` varchar(10) DEFAULT NULL,
  `endTime` varchar(10) DEFAULT NULL,
  `state` enum('Johor','Kedah','Kelantan','Melaka','Negeri Sembilan','Pahang','Perak','Perlis','Pulau Pinang','Sabah','Sarawak','Selangor','Terengganu') DEFAULT NULL,
  `visibleStatus` enum('Visible','Invisible') NOT NULL DEFAULT 'Invisible'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `branch`
--

INSERT INTO `branch` (`branchId`, `name`, `slug`, `image`, `createdAt`, `address`, `status`, `contactNumber`, `startTime`, `endTime`, `state`, `visibleStatus`) VALUES
(1, 'ABC Cafe - Johor Bahru', '111', 'branch_1777178141_2636.jpg', '2026-04-01 16:42:42', 'Lot 12, Jalan Sutera, Taman Sutera, 81300 Johor Bahru', 'Opening', '07-556 8899', '08:00', '22:00', 'Selangor', 'Visible'),
(2, 'ABC Cafe - Kuala Lumpur', 'tc', 'branch_1777178833_1263.jpg', '2026-04-01 16:42:33', 'No 88, Jalan Bukit Bintang, 55100 Kuala Lumpur', 'Opening', '03-2144 7788', '09:00', '23:00', 'Negeri Sembilan', 'Visible'),
(3, 'ABC Cafe - Penang', 'gpa', 'branch_1777178119_3192.jpg', '2026-03-25 21:14:55', '25, Lebuh Chulia, George Town, 10200 Penang', 'Opening', '04-262 1998', '10:00', '22:30', 'Pulau Pinang', 'Invisible'),
(4, 'ABC Cafe - Melaka', 'tc2121', 'branch_1777614410_4755.png', '2026-03-30 15:51:00', '11, Jalan Hang Tuah, 75300 Melaka', 'Deprecated', '06-288 1122', '09:00', '21:00', 'Melaka', 'Invisible'),
(5, 'ABC Cafe - Ipoh', 'tc1121', 'branch_1777178821_7312.jpeg', '2026-03-31 00:42:30', '40, Jalan Sultan Iskandar, 30000 Ipoh, Perak', 'Opening', '043838238', '08:30', '22:00', 'Perak', 'Visible'),
(8, 'ABC Cafe - Kuching', '123', 'branch_1777178182_6978.jpg', '2026-03-31 01:26:35', 'Lot 6, Jalan Padungan, 93100 Kuching, Sarawak', 'Opening', '082-555 233', '09:00', '21:30', 'Sarawak', 'Visible'),
(9, 'ABC Cafe - Kota Kinabalu', 'tfb', 'branch_1777178799_5187.png', '2026-04-23 13:00:42', 'Block A, Jalan Gaya, 88000 Kota Kinabalu', 'Setup', '603 – 8312 5134', '10:00', '23:00', 'Sabah', 'Visible'),
(17, 'ABC Cafe - Putrajaya', 'tsb', 'branch_1777622570_9640.png', '2026-04-29 18:55:33', 'Presint 15, Jalan Diplomatik, 62000 Putrajaya', 'Closed', '03-8888 2323', '08:00', '20:00', 'Johor', 'Visible');

--
-- Triggers `branch`
--
DELIMITER $$
CREATE TRIGGER `after_branch_insert` AFTER INSERT ON `branch` FOR EACH ROW BEGIN
    INSERT INTO order_method (methodName, branchId, isEnabled) VALUES 
    ('Dine In', NEW.branchId, 1),
    ('Take Away', NEW.branchId, 1),
    ('Delivery', NEW.branchId, 1);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `memberId` int(10) UNSIGNED NOT NULL,
  `branchId` int(10) UNSIGNED NOT NULL,
  `userId` int(10) UNSIGNED NOT NULL,
  `role` enum('Staff','Branch Manager','Admin') NOT NULL DEFAULT 'Staff',
  `createdAt` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`memberId`, `branchId`, `userId`, `role`, `createdAt`) VALUES
(1, 2, 3, 'Branch Manager', '2026-05-19 12:39:15');

-- --------------------------------------------------------

--
-- Table structure for table `food`
--

CREATE TABLE `food` (
  `foodId` int(10) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `basePrice` decimal(10,2) NOT NULL,
  `status` enum('Available','Sold Out','Discontinued') NOT NULL,
  `visibleStatus` enum('Visible','Invisible') NOT NULL DEFAULT 'Invisible',
  `image` varchar(255) NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp(),
  `categoryId` int(10) UNSIGNED NOT NULL,
  `branchId` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `food`
--

INSERT INTO `food` (`foodId`, `name`, `description`, `basePrice`, `status`, `visibleStatus`, `image`, `createdAt`, `categoryId`, `branchId`) VALUES
(1, 'Burger', 'This is a burger', 12.50, 'Discontinued', 'Visible', 'menu_1777178046_1228.jpg', '2026-04-30 13:47:29', 10, 1),
(2, 'YEEEEYAAAAHAAAAAA', 'YAMAHAAAAAAAAAAAAAAAA', 1000.00, 'Available', 'Invisible', 'menu_1777178015_6948.jpg', '2026-04-18 17:55:39', 14, 3),
(3, 'Pizza', 'Pizzaaaaa', 10000.00, 'Available', 'Visible', 'menu_1777178005_1844.jpg', '2026-04-18 19:01:34', 9, 2),
(4, 'Curry Laksa', 'Spicy coconut curry noodle soup with tofu puffs and egg.', 13.90, 'Available', 'Visible', 'menu_1777177992_3550.jpg', '2026-04-19 00:30:12', 11, 2),
(5, 'BBQ', 'this is bbq', 10.50, 'Available', 'Visible', 'menu_1777177980_1262.jpg', '2026-04-19 21:45:10', 12, 1),
(6, 'Black Chicken', 'this is not a white chicken', 50.00, 'Sold Out', 'Visible', 'menu_1777177969_8078.jpg', '2026-04-20 22:49:25', 15, 3),
(7, 'Vegetarian Food', 'This is not a vegetarian food hahahaha', 32.50, 'Sold Out', 'Visible', 'menu_1777628673_1413.png', '2026-05-01 17:44:33', 9, 2),
(8, 'Www', 'WWWWWWWWW', 1.50, 'Available', 'Visible', 'menu_1777992613_5908.jpg', '2026-05-05 22:50:13', 13, 2);

-- --------------------------------------------------------

--
-- Table structure for table `food_category`
--

CREATE TABLE `food_category` (
  `categoryId` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `status` enum('Visible','Invisible','Deprecated') NOT NULL,
  `branchId` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `food_category`
--

INSERT INTO `food_category` (`categoryId`, `name`, `status`, `branchId`) VALUES
(9, 'Vegetarian', 'Visible', 2),
(10, 'Burgers', 'Deprecated', 1),
(11, 'Rice', 'Visible', 2),
(12, 'Noodles', 'Visible', 1),
(13, 'Western Food', 'Visible', 2),
(14, 'Noodles', 'Visible', 3),
(15, 'Rice', 'Visible', 3);

-- --------------------------------------------------------

--
-- Table structure for table `food_option_group`
--

CREATE TABLE `food_option_group` (
  `optionGroupId` int(10) UNSIGNED NOT NULL,
  `groupName` varchar(100) NOT NULL,
  `foodId` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `food_option_group`
--

INSERT INTO `food_option_group` (`optionGroupId`, `groupName`, `foodId`) VALUES
(45, 'Cucumber', 5),
(46, 'Size', 5),
(47, 'Spicy Level', 6),
(48, 'Cucumber', 6),
(49, 'Size', 6),
(50, 'testing', 6),
(51, 'Sugar Level', 1),
(52, 'Spicy Level', 4),
(53, 'Size', 4);

-- --------------------------------------------------------

--
-- Table structure for table `food_option_item`
--

CREATE TABLE `food_option_item` (
  `optionItemId` int(10) UNSIGNED NOT NULL,
  `itemName` varchar(100) NOT NULL,
  `extraPrice` decimal(10,2) NOT NULL,
  `optionGroupId` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `food_option_item`
--

INSERT INTO `food_option_item` (`optionItemId`, `itemName`, `extraPrice`, `optionGroupId`) VALUES
(57, 'Small', 10.50, 46),
(58, 'Medium', 100.50, 46),
(59, 'Large', 1000.50, 46),
(66, 'Small', 1.30, 49),
(67, 'Medium', 13.00, 49),
(68, 'Large', 130.00, 49),
(69, 'test1', 1.00, 50),
(70, 'test2', 2.00, 50),
(71, 'test3', 3.00, 50),
(72, 'Less Sugar', 10.00, 51),
(73, 'Normal Sugar', 10.00, 51),
(74, 'More Sugar', 10.00, 51),
(75, 'None', 0.50, 52),
(76, 'Normal', 10.50, 52),
(77, 'Spice', 100.50, 52),
(80, 'Yes', 0.00, 45),
(81, 'No', 0.00, 45),
(92, 'Yes', 0.50, 48),
(93, 'No', 0.50, 48),
(102, 'Small', 0.00, 53),
(103, 'Medium', 0.00, 53),
(104, 'Large', 0.00, 53),
(105, 'None1', 10.00, 47),
(106, 'Little', 20.00, 47),
(107, 'Normal', 30.00, 47);

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `orderId` int(10) UNSIGNED NOT NULL,
  `userId` int(10) UNSIGNED NOT NULL,
  `branchId` int(10) UNSIGNED NOT NULL,
  `methodId` int(10) UNSIGNED NOT NULL,
  `tableId` int(10) UNSIGNED DEFAULT NULL,
  `totalPrice` decimal(10,2) NOT NULL,
  `deliveryAddress` varchar(255) DEFAULT NULL,
  `deliveryState` varchar(150) DEFAULT NULL,
  `deliveryDistrict` varchar(150) DEFAULT NULL,
  `deliveryPostalCode` int(5) DEFAULT NULL,
  `extraNote` varchar(255) DEFAULT NULL,
  `orderStatus` enum('Pending','In Progress','Done','Shipping','Delivered','Cancelled') NOT NULL DEFAULT 'Pending',
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`orderId`, `userId`, `branchId`, `methodId`, `tableId`, `totalPrice`, `deliveryAddress`, `deliveryState`, `deliveryDistrict`, `deliveryPostalCode`, `extraNote`, `orderStatus`, `createdAt`) VALUES
(26, 1, 2, 2, NULL, 48.80, NULL, NULL, NULL, NULL, 'extra note.....', 'Pending', '2026-05-17 13:48:19'),
(27, 2, 2, 1, 53, 20000.00, NULL, NULL, NULL, NULL, '', 'Pending', '2026-05-14 05:08:22'),
(28, 1, 1, 14, NULL, 222.00, NULL, NULL, NULL, NULL, '', 'Pending', '2026-05-17 06:31:41'),
(29, 1, 1, 13, 45, 333.00, NULL, NULL, NULL, NULL, 'Please add cucumber', 'In Progress', '2026-05-18 13:56:01'),
(30, 2, 2, 3, NULL, 20077.70, '75450 Ayer Keroh, Malacca, Malaysia', 'Melaka', 'Melaka Tengah', 75450, '', 'Delivered', '2026-05-18 13:38:24'),
(31, 1, 2, 2, NULL, 28.80, NULL, NULL, NULL, NULL, '', 'Cancelled', '2026-05-18 14:06:56');

-- --------------------------------------------------------

--
-- Table structure for table `order_item`
--

CREATE TABLE `order_item` (
  `orderItemId` int(10) UNSIGNED NOT NULL,
  `orderId` int(10) UNSIGNED NOT NULL,
  `foodId` int(10) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `purchasedPrice` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_item`
--

INSERT INTO `order_item` (`orderItemId`, `orderId`, `foodId`, `quantity`, `purchasedPrice`) VALUES
(89, 26, 4, 2, 48.80),
(90, 27, 3, 2, 20000.00),
(91, 28, 5, 2, 222.00),
(92, 29, 5, 3, 333.00),
(93, 30, 3, 2, 20000.00),
(94, 30, 4, 1, 24.40),
(95, 30, 8, 3, 4.50),
(96, 30, 4, 2, 48.80),
(97, 31, 4, 2, 28.80);

-- --------------------------------------------------------

--
-- Table structure for table `order_item_option`
--

CREATE TABLE `order_item_option` (
  `orderItemOptionId` int(10) UNSIGNED NOT NULL,
  `optionItemId` int(10) UNSIGNED NOT NULL,
  `orderItemId` int(10) UNSIGNED NOT NULL,
  `purchasedPrice` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_item_option`
--

INSERT INTO `order_item_option` (`orderItemOptionId`, `optionItemId`, `orderItemId`, `purchasedPrice`) VALUES
(88, 76, 85, 10.50),
(89, 103, 85, 0.00),
(94, 102, 88, 0.00),
(95, 76, 88, 10.50),
(96, 76, 89, 10.50),
(97, 104, 89, 0.00),
(98, 58, 91, 100.50),
(99, 80, 91, 0.00),
(100, 58, 92, 100.50),
(101, 80, 92, 0.00),
(102, 76, 94, 10.50),
(103, 103, 94, 0.00),
(104, 76, 96, 10.50),
(105, 102, 96, 0.00),
(106, 75, 97, 0.50),
(107, 104, 97, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `order_method`
--

CREATE TABLE `order_method` (
  `methodId` int(10) UNSIGNED NOT NULL,
  `methodName` enum('Dine In','Take Away','Delivery') NOT NULL,
  `branchId` int(11) NOT NULL,
  `isEnabled` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_method`
--

INSERT INTO `order_method` (`methodId`, `methodName`, `branchId`, `isEnabled`) VALUES
(1, 'Dine In', 2, 1),
(2, 'Take Away', 2, 1),
(3, 'Delivery', 2, 1),
(13, 'Dine In', 1, 1),
(14, 'Take Away', 1, 1),
(15, 'Delivery', 1, 1),
(16, 'Dine In', 3, 1),
(17, 'Take Away', 3, 1),
(18, 'Delivery', 3, 1),
(19, 'Dine In', 4, 1),
(20, 'Take Away', 4, 1),
(21, 'Delivery', 4, 1),
(22, 'Dine In', 5, 1),
(23, 'Take Away', 5, 1),
(24, 'Delivery', 5, 1),
(25, 'Dine In', 8, 1),
(26, 'Take Away', 8, 0),
(27, 'Delivery', 8, 0),
(28, 'Dine In', 9, 1),
(29, 'Take Away', 9, 1),
(30, 'Delivery', 9, 1),
(34, 'Dine In', 17, 0),
(35, 'Take Away', 17, 1),
(36, 'Delivery', 17, 1);

-- --------------------------------------------------------

--
-- Table structure for table `order_status_history`
--

CREATE TABLE `order_status_history` (
  `historyId` int(10) UNSIGNED NOT NULL,
  `orderId` int(10) UNSIGNED NOT NULL,
  `memberId` int(10) UNSIGNED NOT NULL,
  `status` enum('Pending','In Progress','Done','Shipping','Delivered','Cancelled') NOT NULL DEFAULT 'Pending',
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updatedAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `paymentId` int(10) UNSIGNED NOT NULL,
  `paymentMethod` enum('Cash','Online Payment') NOT NULL,
  `paymentStatus` enum('Pending','Success','Cancelled') NOT NULL,
  `orderId` int(10) UNSIGNED NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`paymentId`, `paymentMethod`, `paymentStatus`, `orderId`, `createdAt`) VALUES
(1, 'Online Payment', 'Success', 26, '2026-05-14 09:55:56'),
(4, 'Cash', 'Pending', 27, '2026-05-17 14:08:29'),
(5, 'Cash', 'Pending', 29, '2026-05-17 14:11:32'),
(6, 'Online Payment', 'Success', 30, '2026-05-18 06:32:31'),
(7, 'Cash', 'Pending', 31, '2026-05-18 06:38:00');

-- --------------------------------------------------------

--
-- Table structure for table `seat_table`
--

CREATE TABLE `seat_table` (
  `tableId` int(11) UNSIGNED NOT NULL,
  `tableName` text NOT NULL,
  `totalSeat` int(11) NOT NULL,
  `availableSeat` int(11) NOT NULL,
  `status` enum('Available','Occupied','Reserved','Blocked','Dirty') NOT NULL DEFAULT 'Available',
  `branchId` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seat_table`
--

INSERT INTO `seat_table` (`tableId`, `tableName`, `totalSeat`, `availableSeat`, `status`, `branchId`) VALUES
(31, 'T30', 3, 2, 'Available', 2),
(32, 'T30', 4, 4, 'Reserved', 3),
(33, 'T31', 3, 3, 'Occupied', 3),
(34, 'T32', 3, 3, 'Available', 4),
(35, 'T3A', 4, 4, 'Occupied', 3),
(37, 'T3A', 3, 3, 'Available', 2),
(38, 'T3B', 4, 4, 'Blocked', 3),
(39, 'T32', 3, 3, 'Available', 1),
(40, 'D3W', 2, 2, 'Available', 2),
(41, 'D22', 3, 3, 'Available', 1),
(42, 'D20', 3, 3, 'Available', 1),
(43, 'D23', 3, 3, 'Available', 1),
(44, 'D24', 3, 2, 'Dirty', 1),
(45, 'D25', 3, 3, 'Available', 1),
(46, 'D24', 2, 2, 'Reserved', 4),
(47, 'D2A', 2, 2, 'Occupied', 4),
(48, 'D2A', 3, 2, 'Occupied', 1),
(49, 'D2B', 3, 1, 'Dirty', 2),
(50, 'T30', 3, 3, 'Available', 0),
(51, '2', 0, 0, 'Available', 0),
(52, 'D2C', 3, 3, 'Available', 2),
(53, 'T3H', 2, 1, 'Available', 2),
(54, 'T3A', 2, 2, 'Available', 8),
(55, 'D2Z', 2, 2, 'Occupied', 4),
(56, 'D2D', 3, 3, 'Dirty', 8),
(62, 'A32', 4, 4, 'Reserved', 8);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userId` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('customer','employee') NOT NULL DEFAULT 'customer',
  `contactNumber` varchar(20) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userId`, `name`, `email`, `password`, `role`, `contactNumber`, `address`, `image`, `createdAt`) VALUES
(1, 'Jane Doe', 'jane.doe@gmail.com', 'a2B+uate', 'employee', '0123456789', 'Kampung Lapan, Banda Kaba, 65000, Melaka', 'uploads/profile_6a07f242c012c4.97970091.png', '2026-05-16 14:21:00'),
(2, 'kierano', 'kieran.zhiming@gmail.com', 'abc123@', 'customer', '01010101010', '', 'uploads/profile_6a07eb750f0b10.60942962.jpg', '2026-05-16 19:57:47'),
(3, 'A', 'a@a.com', 'aaaaaa123@', 'employee', '0106552192', NULL, NULL, '2026-05-19 12:39:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `branch`
--
ALTER TABLE `branch`
  ADD PRIMARY KEY (`branchId`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`memberId`),
  ADD UNIQUE KEY `branchId` (`branchId`),
  ADD UNIQUE KEY `userId` (`userId`);

--
-- Indexes for table `food`
--
ALTER TABLE `food`
  ADD PRIMARY KEY (`foodId`);

--
-- Indexes for table `food_category`
--
ALTER TABLE `food_category`
  ADD PRIMARY KEY (`categoryId`);

--
-- Indexes for table `food_option_group`
--
ALTER TABLE `food_option_group`
  ADD PRIMARY KEY (`optionGroupId`),
  ADD KEY `foodId` (`foodId`);

--
-- Indexes for table `food_option_item`
--
ALTER TABLE `food_option_item`
  ADD PRIMARY KEY (`optionItemId`),
  ADD KEY `optionGroupId` (`optionGroupId`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`orderId`),
  ADD KEY `methodId` (`methodId`),
  ADD KEY `userId` (`userId`),
  ADD KEY `branchId` (`branchId`),
  ADD KEY `tableId` (`tableId`);

--
-- Indexes for table `order_item`
--
ALTER TABLE `order_item`
  ADD PRIMARY KEY (`orderItemId`),
  ADD KEY `orderId` (`orderId`),
  ADD KEY `foodId` (`foodId`);

--
-- Indexes for table `order_item_option`
--
ALTER TABLE `order_item_option`
  ADD PRIMARY KEY (`orderItemOptionId`),
  ADD KEY `optionItemId` (`optionItemId`),
  ADD KEY `orderItemId` (`orderItemId`);

--
-- Indexes for table `order_method`
--
ALTER TABLE `order_method`
  ADD PRIMARY KEY (`methodId`),
  ADD KEY `branchId` (`branchId`);

--
-- Indexes for table `order_status_history`
--
ALTER TABLE `order_status_history`
  ADD PRIMARY KEY (`historyId`),
  ADD UNIQUE KEY `orderId` (`orderId`),
  ADD UNIQUE KEY `memberId` (`memberId`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`paymentId`),
  ADD UNIQUE KEY `orderId` (`orderId`);

--
-- Indexes for table `seat_table`
--
ALTER TABLE `seat_table`
  ADD PRIMARY KEY (`tableId`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `branch`
--
ALTER TABLE `branch`
  MODIFY `branchId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `memberId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `food`
--
ALTER TABLE `food`
  MODIFY `foodId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `food_category`
--
ALTER TABLE `food_category`
  MODIFY `categoryId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `food_option_group`
--
ALTER TABLE `food_option_group`
  MODIFY `optionGroupId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `food_option_item`
--
ALTER TABLE `food_option_item`
  MODIFY `optionItemId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `orderId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `order_item`
--
ALTER TABLE `order_item`
  MODIFY `orderItemId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT for table `order_item_option`
--
ALTER TABLE `order_item_option`
  MODIFY `orderItemOptionId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT for table `order_method`
--
ALTER TABLE `order_method`
  MODIFY `methodId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `order_status_history`
--
ALTER TABLE `order_status_history`
  MODIFY `historyId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `paymentId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `seat_table`
--
ALTER TABLE `seat_table`
  MODIFY `tableId` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
