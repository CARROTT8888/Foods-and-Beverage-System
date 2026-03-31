-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 31, 2026 at 05:18 PM
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
  `state` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `branch`
--

INSERT INTO `branch` (`branchId`, `name`, `slug`, `image`, `createdAt`, `address`, `status`, `contactNumber`, `startTime`, `endTime`, `state`) VALUES
(1, 'Multimedia University', '111', NULL, '0000-00-00 00:00:00', NULL, 'Closed', NULL, NULL, NULL, NULL),
(2, 'Treble Clef', 'tc', NULL, '0000-00-00 00:00:00', 'Banda Kaba, Kampung Lapan, 75000, Melaka', 'Setup', '0123456789\n', NULL, NULL, NULL),
(3, 'GPA 3.78', 'gpa', NULL, '2026-03-25 21:14:55', 'abc123', 'Setup', NULL, NULL, NULL, NULL),
(4, '???', 'tc2121', NULL, '2026-03-30 15:51:00', 'where is treble clef', 'Deprecated', NULL, NULL, NULL, NULL),
(5, 'Treble Clef 2', 'tc1121', NULL, '2026-03-31 00:42:30', '', 'Closed', NULL, NULL, NULL, NULL),
(8, 'testing', '123', NULL, '2026-03-31 01:26:35', 'i am testing header location', 'Closed', NULL, NULL, NULL, NULL);

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

-- --------------------------------------------------------

--
-- Table structure for table `food`
--

CREATE TABLE `food` (
  `foodId` int(10) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `basePrice` decimal(10,2) NOT NULL,
  `status` enum('Available','Sold Out','Coming Soon','Limited','Discontinued') NOT NULL,
  `createdAt` datetime NOT NULL,
  `categoryId` int(10) UNSIGNED NOT NULL,
  `branchId` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `food_category`
--

CREATE TABLE `food_category` (
  `categoryId` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `status` enum('Visible','Invisible','Duplicated') NOT NULL,
  `branchId` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `food_option_group`
--

CREATE TABLE `food_option_group` (
  `optionGroupId` int(10) UNSIGNED NOT NULL,
  `groupName` varchar(100) NOT NULL,
  `foodId` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `paymentStatus` enum('Pending','Paid','Cancelled') NOT NULL DEFAULT 'Pending',
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

-- --------------------------------------------------------

--
-- Table structure for table `order_method`
--

CREATE TABLE `order_method` (
  `methodId` int(10) UNSIGNED NOT NULL,
  `methodName` enum('Dine In','Take Away','Delivery') NOT NULL,
  `branchId` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `paymentStatus` enum('Pending','Success','Failed','Cancelled') NOT NULL,
  `orderId` int(10) UNSIGNED NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `seat_table`
--

CREATE TABLE `seat_table` (
  `tableId` int(11) NOT NULL,
  `tableName` text NOT NULL,
  `status` enum('Available','Occupied','Reserved','Blocked','Dirty') NOT NULL,
  `branchId` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1, 'Jane Doe', 'jane.doe@gmail.com', 'a2B+uate', 'employee', '0123456789', NULL, NULL, '2026-03-15 00:16:05'),
(2, 'kieran', 'kieran.zhiming@gmail.com', 'abc123@', 'customer', '01010101010', NULL, NULL, '2026-03-15 00:18:03');

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
  ADD PRIMARY KEY (`foodId`),
  ADD UNIQUE KEY `categoryId` (`categoryId`),
  ADD UNIQUE KEY `branchId` (`branchId`);

--
-- Indexes for table `food_category`
--
ALTER TABLE `food_category`
  ADD PRIMARY KEY (`categoryId`),
  ADD UNIQUE KEY `branchId` (`branchId`);

--
-- Indexes for table `food_option_group`
--
ALTER TABLE `food_option_group`
  ADD PRIMARY KEY (`optionGroupId`),
  ADD UNIQUE KEY `foodId` (`foodId`);

--
-- Indexes for table `food_option_item`
--
ALTER TABLE `food_option_item`
  ADD PRIMARY KEY (`optionItemId`),
  ADD UNIQUE KEY `optionGroupId` (`optionGroupId`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`orderId`),
  ADD UNIQUE KEY `userId` (`userId`),
  ADD UNIQUE KEY `branchId` (`branchId`),
  ADD UNIQUE KEY `methodId` (`methodId`),
  ADD UNIQUE KEY `tableId` (`tableId`);

--
-- Indexes for table `order_item`
--
ALTER TABLE `order_item`
  ADD PRIMARY KEY (`orderItemId`),
  ADD UNIQUE KEY `orderId` (`orderId`),
  ADD UNIQUE KEY `foodId` (`foodId`);

--
-- Indexes for table `order_item_option`
--
ALTER TABLE `order_item_option`
  ADD PRIMARY KEY (`orderItemOptionId`),
  ADD UNIQUE KEY `optionItemId` (`optionItemId`),
  ADD UNIQUE KEY `orderItemId` (`orderItemId`);

--
-- Indexes for table `order_method`
--
ALTER TABLE `order_method`
  ADD PRIMARY KEY (`methodId`),
  ADD UNIQUE KEY `branchId` (`branchId`);

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
  ADD UNIQUE KEY `branchId` (`branchId`);

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
  MODIFY `branchId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `memberId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `food`
--
ALTER TABLE `food`
  MODIFY `foodId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `food_category`
--
ALTER TABLE `food_category`
  MODIFY `categoryId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `food_option_group`
--
ALTER TABLE `food_option_group`
  MODIFY `optionGroupId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `food_option_item`
--
ALTER TABLE `food_option_item`
  MODIFY `optionItemId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `orderId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_item`
--
ALTER TABLE `order_item`
  MODIFY `orderItemId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_item_option`
--
ALTER TABLE `order_item_option`
  MODIFY `orderItemOptionId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_method`
--
ALTER TABLE `order_method`
  MODIFY `methodId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_status_history`
--
ALTER TABLE `order_status_history`
  MODIFY `historyId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `paymentId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
