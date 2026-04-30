-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 27, 2026 at 05:18 PM
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
(1, 'Multimedia University', '111', 'branch_1777178141_2636.jpg', '2026-04-01 16:42:42', '', 'Closed', NULL, '', '', 'Perlis', 'Invisible'),
(2, 'Treble Clef', 'tc', 'branch_1777178833_1263.jpg', '2026-04-01 16:42:33', 'Banda Kaba, Kampung Lapan, 75000, Melaka', 'Opening', '0123456789', '08:00', '17:00', 'Melaka', 'Visible'),
(3, 'GPA 3.78', 'gpa', 'branch_1777178119_3192.jpg', '2026-03-25 21:14:55', 'abc123', 'Opening', NULL, '', '', 'Kelantan', 'Invisible'),
(4, '???', 'tc2121', '', '2026-03-30 15:51:00', 'where is treble clef', 'Deprecated', NULL, '20:00', '22:00', 'Selangor', 'Invisible'),
(5, 'Treble Clef 2', 'tc1121', 'branch_1777178821_7312.jpeg', '2026-03-31 00:42:30', 'Kampung Lapan, Treble Clef, 75000, Melaka', 'Setup', '043838238', '01:00', '18:00', 'Melaka', 'Visible'),
(8, 'testing', '123', 'branch_1777178182_6978.jpg', '2026-03-31 01:26:35', 'i am testing header location', 'Opening', '0106553599', '09:00', '23:00', 'Kedah', 'Visible'),
(9, 'The First Branch', 'tfb', 'branch_1777178799_5187.png', '2026-04-23 13:00:42', 'Multimedia University, Persiaran Multimedia, 63100 Cyberjaya, Selangor, Malaysia', 'Setup', '603 – 8312 5134', '08:00', '23:59', 'Selangor', 'Visible');

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
(4, 'YOSHI', '...', 100000.00, 'Discontinued', 'Invisible', 'menu_1777177992_3550.jpg', '2026-04-19 00:30:12', 9, 2),
(5, 'BBQ', 'this is bbq', 10.50, 'Available', 'Invisible', 'menu_1777177980_1262.jpg', '2026-04-19 21:45:10', 12, 1),
(6, 'Black Chicken', 'this is not a white chicken', 50.00, 'Sold Out', 'Visible', 'menu_1777177969_8078.jpg', '2026-04-20 22:49:25', 15, 3);

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
(6, 'Alcohol ', 'Visible', 2),
(8, 'Normal Drink', 'Visible', 1),
(9, 'no44 mud dust', 'Invisible', 2),
(10, 'Burgers', 'Deprecated', 1),
(11, 'Rice', 'Deprecated', 2),
(12, 'Noodles', 'Invisible', 1),
(13, 'Whisky', 'Visible', 2),
(14, 'Gintini', 'Visible', 3),
(15, 'YEEEEE', 'Visible', 3);

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
(52, 'Spicy Level', 4);

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
(98, 'None1', 10.00, 47),
(99, 'Little', 20.00, 47),
(100, 'Normal', 30.00, 47),
(101, 'Spicy', 40.00, 47);

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

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`orderId`, `userId`, `branchId`, `methodId`, `tableId`, `totalPrice`, `deliveryAddress`, `deliveryState`, `deliveryDistrict`, `deliveryPostalCode`, `extraNote`, `orderStatus`, `paymentStatus`, `createdAt`) VALUES
(4, 1, 2, 2, NULL, 0.00, NULL, NULL, NULL, NULL, NULL, 'Pending', 'Pending', '2026-04-26 17:15:01'),
(5, 1, 2, 2, NULL, 0.00, NULL, NULL, NULL, NULL, NULL, 'Pending', 'Pending', '2026-04-27 12:04:12');

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
(26, 'Take Away', 8, 1),
(27, 'Delivery', 8, 1),
(28, 'Dine In', 9, 1),
(29, 'Take Away', 9, 1),
(30, 'Delivery', 9, 1);

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
(57, 'A30', 4, 4, 'Reserved', 8),
(58, 'A84', 3, 2, 'Available', 8);

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
  ADD UNIQUE KEY `tableId` (`tableId`),
  ADD KEY `methodId` (`methodId`),
  ADD KEY `userId` (`userId`),
  ADD KEY `branchId` (`branchId`);

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
  MODIFY `branchId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `memberId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `food`
--
ALTER TABLE `food`
  MODIFY `foodId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `food_category`
--
ALTER TABLE `food_category`
  MODIFY `categoryId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `food_option_group`
--
ALTER TABLE `food_option_group`
  MODIFY `optionGroupId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `food_option_item`
--
ALTER TABLE `food_option_item`
  MODIFY `optionItemId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `orderId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
  MODIFY `methodId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

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
-- AUTO_INCREMENT for table `seat_table`
--
ALTER TABLE `seat_table`
  MODIFY `tableId` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
