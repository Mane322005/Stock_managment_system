-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 27, 2025 at 05:19 AM
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
-- Database: `sms`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(10) NOT NULL,
  `category_name` varchar(30) NOT NULL,
  `category_status` varchar(10) NOT NULL,
  `category_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category_name`, `category_status`, `category_date`) VALUES
(1, 'Notebooks', 'Active', '2025-02-06'),
(3, 'Drawing Box', 'Active', '2025-02-26'),
(7, 'pen', 'Active', '2025-02-15'),
(8, 'Notebooks', 'Active', '2025-02-19'),
(9, 'Bottles', 'Active', '2025-02-26');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int(10) NOT NULL,
  `customer_name` varchar(20) NOT NULL,
  `customer_address` varchar(30) NOT NULL,
  `customer_status` varchar(30) NOT NULL,
  `customer_email` varchar(10) NOT NULL,
  `customer_gstin` varchar(50) NOT NULL,
  `customer_balance` varchar(10) NOT NULL,
  `customer_mobileno` varchar(20) NOT NULL,
  `customer_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `customer_name`, `customer_address`, `customer_status`, `customer_email`, `customer_gstin`, `customer_balance`, `customer_mobileno`, `customer_date`) VALUES
(6, 'sonu', 'latur', 'yes', 'sanu@gmail', '27mh888888', '4000', '9878987678', '2025-02-19'),
(7, 'abhi', '908', 'Yes', 'abhijithar', '523235235325253', '121', '7867867854', '2025-02-26');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `invoice_id` int(11) NOT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `remaining_balance` decimal(10,2) DEFAULT NULL,
  `final_total` decimal(10,2) DEFAULT NULL,
  `invoice_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`invoice_id`, `supplier_id`, `customer_id`, `total_amount`, `remaining_balance`, `final_total`, `invoice_date`) VALUES
(1, 1, 7, 7.00, 121.00, 128.00, '2025-02-27 01:04:36'),
(2, 1, 7, 7.00, 121.00, 128.00, '2025-02-27 01:08:05'),
(3, 1, 6, 7.00, 4000.00, 4007.00, '2025-02-27 01:26:32'),
(4, 1, 6, 7.00, 4000.00, 4007.00, '2025-02-27 01:27:11');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_items`
--

CREATE TABLE `invoice_items` (
  `item_id` int(11) NOT NULL,
  `invoice_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoice_items`
--

INSERT INTO `invoice_items` (`item_id`, `invoice_id`, `product_id`, `quantity`, `total_price`) VALUES
(1, 1, 3, 1, 7.00),
(2, 2, 3, 1, 7.00),
(3, 3, 3, 1, 7.00),
(4, 4, 3, 1, 7.00);

-- --------------------------------------------------------

--
-- Table structure for table `inward`
--

CREATE TABLE `inward` (
  `product_id` int(10) NOT NULL,
  `product_name` varchar(20) NOT NULL,
  `inward_qty` int(10) NOT NULL,
  `suplier_name` varchar(30) NOT NULL,
  `recivved_by` varchar(50) NOT NULL,
  `delievered_by` varchar(20) NOT NULL,
  `total_price` varchar(20) NOT NULL,
  `inward_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `purchase_id` int(10) NOT NULL,
  `product_id` int(10) NOT NULL,
  `product_name` varchar(30) NOT NULL,
  `suplier_email` varchar(20) NOT NULL,
  `suplier_name` varchar(30) NOT NULL,
  `suplier_adress` varchar(50) NOT NULL,
  `suplier_mobileno` varchar(20) NOT NULL,
  `po_total_price` varchar(20) NOT NULL,
  `po_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `outward`
--

CREATE TABLE `outward` (
  `product_id` int(10) NOT NULL,
  `product_name` varchar(20) NOT NULL,
  `outward_qty` int(10) NOT NULL,
  `customer_name` varchar(30) NOT NULL,
  `customer_no` varchar(50) NOT NULL,
  `delievered_by` varchar(20) NOT NULL,
  `total_price` varchar(20) NOT NULL,
  `inward_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` int(10) NOT NULL,
  `category_id` int(10) NOT NULL,
  `supplier_id` int(255) NOT NULL,
  `product_name` varchar(30) NOT NULL,
  `product_status` varchar(10) NOT NULL,
  `product_description` varchar(50) NOT NULL,
  `product_unit` varchar(10) NOT NULL,
  `product_sprice` varchar(10) NOT NULL,
  `product_gst` varchar(10) NOT NULL,
  `product_maxprice` int(10) NOT NULL,
  `product_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `category_id`, `supplier_id`, `product_name`, `product_status`, `product_description`, `product_unit`, `product_sprice`, `product_gst`, `product_maxprice`, `product_date`) VALUES
(3, 7, 1, 'Cello Finegrip', 'Available', 'It is bllue,red,black pen', 'N', '7', '5', 8, '2025-02-26');

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `supplier_id` int(10) NOT NULL,
  `supplier_email` varchar(20) NOT NULL,
  `supplier_gstin` varchar(20) NOT NULL,
  `supplier_name` varchar(30) NOT NULL,
  `supplier_status` varchar(10) NOT NULL,
  `supplier_address` varchar(50) NOT NULL,
  `supplier_balance` varchar(10) NOT NULL,
  `supplier_brand` varchar(20) NOT NULL,
  `supplier_mobileno` varchar(20) NOT NULL,
  `supplier_data` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`supplier_id`, `supplier_email`, `supplier_gstin`, `supplier_name`, `supplier_status`, `supplier_address`, `supplier_balance`, `supplier_brand`, `supplier_mobileno`, `supplier_data`) VALUES
(1, 'Sneha@gmail.com', '859561521515248', 'Sneha', 'Active', 'Pune', '8546', 'Classmate', '9898989852', '2025-02-10');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(10) NOT NULL,
  `full_name` varchar(50) NOT NULL,
  `username` varchar(20) NOT NULL,
  `user_email` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `mobile_no` varchar(20) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `adress` varchar(255) NOT NULL,
  `reg_date` date NOT NULL,
  `profile_pic` varchar(255) NOT NULL,
  `security_question` varchar(255) NOT NULL,
  `security_answer` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `username`, `user_email`, `password`, `mobile_no`, `company_name`, `adress`, `reg_date`, `profile_pic`, `security_question`, `security_answer`) VALUES
(1, 'Admin', 'Sneha@1Abhi@2Sush@3', 'abhijitharale46@gmai', 'Admin@123', '9561517091', 'Dayanand', 'Pune', '2025-02-26', 'uploads/1740588449_profile.jpg', 'What is your favorite color?', 'Redmi');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`invoice_id`);

--
-- Indexes for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `invoice_id` (`invoice_id`);

--
-- Indexes for table `inward`
--
ALTER TABLE `inward`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`purchase_id`);

--
-- Indexes for table `outward`
--
ALTER TABLE `outward`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`supplier_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `invoice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD CONSTRAINT `invoice_items_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`invoice_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
