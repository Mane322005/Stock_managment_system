

CREATE TABLE `category` (
  `category_id` int(10) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(30) NOT NULL,
  `category_status` varchar(10) NOT NULL,
  `category_date` date NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `category` VALUES('1', 'Notebooks', 'Active', '2025-02-06');
INSERT INTO `category` VALUES('3', 'Drawing Box', 'Active', '2025-02-26');
INSERT INTO `category` VALUES('7', 'pen', 'Active', '2025-02-15');
INSERT INTO `category` VALUES('8', 'Notebooks', 'Active', '2025-02-19');
INSERT INTO `category` VALUES('9', 'Bottles', 'Active', '2025-02-26');
INSERT INTO `category` VALUES('10', 'Electronics', 'Active', '2025-08-15');




CREATE TABLE `customer` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(20) NOT NULL,
  `customer_address` varchar(30) NOT NULL,
  `customer_status` varchar(30) NOT NULL,
  `customer_email` varchar(10) NOT NULL,
  `customer_gstin` varchar(50) NOT NULL,
  `customer_balance` varchar(10) NOT NULL,
  `customer_mobileno` varchar(20) NOT NULL,
  `customer_date` date NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `customer` VALUES('6', 'sonu', 'latur', 'yes', 'sanu@gmail', '27mh888888', '4000', '9878987678', '2025-02-19');
INSERT INTO `customer` VALUES('7', 'abhi', '908', 'Yes', 'abhijithar', '523235235325253', '121', '7867867854', '2025-02-26');




CREATE TABLE `invoice_items` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`item_id`),
  KEY `invoice_id` (`invoice_id`),
  CONSTRAINT `invoice_items_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`invoice_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `invoice_items` VALUES('1', '1', '3', '1', '7.00');
INSERT INTO `invoice_items` VALUES('2', '2', '3', '1', '7.00');
INSERT INTO `invoice_items` VALUES('3', '3', '3', '1', '7.00');
INSERT INTO `invoice_items` VALUES('4', '4', '3', '1', '7.00');




CREATE TABLE `invoices` (
  `invoice_id` int(11) NOT NULL AUTO_INCREMENT,
  `supplier_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `remaining_balance` decimal(10,2) DEFAULT NULL,
  `final_total` decimal(10,2) DEFAULT NULL,
  `invoice_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`invoice_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `invoices` VALUES('1', '1', '7', '7.00', '121.00', '128.00', '2025-02-27 06:34:36');
INSERT INTO `invoices` VALUES('2', '1', '7', '7.00', '121.00', '128.00', '2025-02-27 06:38:05');
INSERT INTO `invoices` VALUES('3', '1', '6', '7.00', '4000.00', '4007.00', '2025-02-27 06:56:32');
INSERT INTO `invoices` VALUES('4', '1', '6', '7.00', '4000.00', '4007.00', '2025-02-27 06:57:11');




CREATE TABLE `inward` (
  `product_id` int(10) NOT NULL,
  `product_name` varchar(20) NOT NULL,
  `inward_qty` int(10) NOT NULL,
  `suplier_name` varchar(30) NOT NULL,
  `recivved_by` varchar(50) NOT NULL,
  `delievered_by` varchar(20) NOT NULL,
  `total_price` varchar(20) NOT NULL,
  `inward_date` date NOT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;





CREATE TABLE `order` (
  `purchase_id` int(10) NOT NULL,
  `product_id` int(10) NOT NULL,
  `product_name` varchar(30) NOT NULL,
  `suplier_email` varchar(20) NOT NULL,
  `suplier_name` varchar(30) NOT NULL,
  `suplier_adress` varchar(50) NOT NULL,
  `suplier_mobileno` varchar(20) NOT NULL,
  `po_total_price` varchar(20) NOT NULL,
  `po_date` date NOT NULL,
  PRIMARY KEY (`purchase_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;





CREATE TABLE `order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `purchase_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `total_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`),
  KEY `purchase_id` (`purchase_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;





CREATE TABLE `outward` (
  `product_id` int(10) NOT NULL,
  `product_name` varchar(20) NOT NULL,
  `outward_qty` int(10) NOT NULL,
  `customer_name` varchar(30) NOT NULL,
  `customer_no` varchar(50) NOT NULL,
  `delievered_by` varchar(20) NOT NULL,
  `total_price` varchar(20) NOT NULL,
  `inward_date` date NOT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;





CREATE TABLE `product` (
  `product_id` int(10) NOT NULL AUTO_INCREMENT,
  `category_id` int(10) NOT NULL,
  `supplier_id` int(255) NOT NULL,
  `product_name` varchar(30) NOT NULL,
  `product_status` varchar(10) NOT NULL,
  `product_description` varchar(50) NOT NULL,
  `product_unit` varchar(10) NOT NULL,
  `product_sprice` varchar(10) NOT NULL,
  `product_gst` varchar(10) NOT NULL,
  `product_maxprice` int(10) NOT NULL,
  `product_date` date NOT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `product` VALUES('3', '7', '1', 'Cello Finegrip', 'Available', 'It is bllue,red,black pen', 'N', '7', '5', '8', '2025-02-26');
INSERT INTO `product` VALUES('4', '1', '1', 'Classmate Ruled Notebook', 'Active', '200 pages ruled notebook', 'pcs', '50.00', '12', '60', '2025-08-15');
INSERT INTO `product` VALUES('5', '1', '1', 'A4 Spiral Notebook', 'Active', 'A4 size spiral bound notebook', 'pcs', '80.00', '12', '100', '2025-08-15');
INSERT INTO `product` VALUES('6', '8', '1', 'Hardbound Diary', 'Active', 'Daily use hardbound diary', 'pcs', '150.00', '12', '180', '2025-08-15');
INSERT INTO `product` VALUES('7', '3', '1', 'Faber-Castell Drawing Kit', 'Active', 'Complete drawing kit', 'set', '250.00', '18', '300', '2025-08-15');
INSERT INTO `product` VALUES('8', '7', '1', 'Reynolds Ball Pen', 'Active', 'Blue ink ball pen', 'pcs', '10.00', '5', '15', '2025-08-15');
INSERT INTO `product` VALUES('9', '9', '1', 'Milton Water Bottle 1L', 'Active', 'Plastic water bottle 1 litre', 'pcs', '120.00', '12', '150', '2025-08-15');
INSERT INTO `product` VALUES('10', '10', '1', 'USB Keyboard', 'Active', 'Standard USB keyboard', 'pcs', '450.00', '18', '550', '2025-08-15');




CREATE TABLE `purchase_orders` (
  `purchase_id` int(11) NOT NULL AUTO_INCREMENT,
  `supplier_id` int(11) NOT NULL,
  `suplier_name` varchar(150) NOT NULL,
  `suplier_email` varchar(150) NOT NULL,
  `suplier_adress` varchar(255) NOT NULL,
  `suplier_mobileno` varchar(20) NOT NULL,
  `po_total_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `po_date` datetime NOT NULL,
  PRIMARY KEY (`purchase_id`),
  KEY `supplier_id` (`supplier_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `purchase_orders` VALUES('1', '1', 'Sneha', 'sneha@example.com', '123 Main Street, Pune', '9898989852', '2500.00', '2025-08-15 00:00:00');
INSERT INTO `purchase_orders` VALUES('2', '2', 'Ravi Kumar', 'ravi@example.com', '45 Market Road, Mumbai', '9876543210', '1800.50', '2025-08-15 00:00:00');
INSERT INTO `purchase_orders` VALUES('3', '3', 'Priya Sharma', 'priya@example.com', '78 College Road, Nashik', '9823456789', '3500.75', '2025-08-15 00:00:00');




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
  `supplier_data` date NOT NULL,
  PRIMARY KEY (`supplier_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `supplier` VALUES('0', 'ravi@example.com', 'GSTIN1234RKYUHB', 'Ravi kumar', 'Active', '45 Market Road, Mumbai', '50000', 'Stationery', '8976879876', '2025-08-15');
INSERT INTO `supplier` VALUES('1', 'Sneha@gmail.com', '859561521515248', 'Sneha', 'Active', 'Pune', '8546', 'Classmate', '9898989852', '2025-02-10');




CREATE TABLE `system_logs` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `log_date` datetime NOT NULL,
  PRIMARY KEY (`log_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `system_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `system_logs` VALUES('1', '22', 'User logged in', '2025-08-15 16:31:33');
INSERT INTO `system_logs` VALUES('2', '22', 'User logged in', '2025-08-16 10:25:02');




CREATE TABLE `users` (
  `user_id` int(10) NOT NULL AUTO_INCREMENT,
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
  `security_answer` varchar(255) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `users` VALUES('1', 'Admin', 'Sneha@1Abhi@2Sush@3', 'abhijitharale46@gmai', 'Admin@123', '9561517091', 'Dayanand', 'Pune', '2025-02-26', 'uploads/1740588449_profile.jpg', 'What is your favorite color?', 'Redmi');
INSERT INTO `users` VALUES('22', 'Mane Sushmita', 'Sushmita@2005', 'sushamitamane@gmail.', 'Sush@2005', '8010248434', 'Amarnath pvt', 'Latur', '2025-08-15', 'uploads/default.jpg', 'imaginary_friend', 'amar');


