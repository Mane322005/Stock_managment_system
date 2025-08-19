

CREATE TABLE `category` (
  `category_id` int(10) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(30) NOT NULL,
  `category_status` varchar(10) NOT NULL,
  `category_date` date NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `category` VALUES('1', 'Notebooks', 'Active', '2025-02-06');
INSERT INTO `category` VALUES('3', 'Drawing Box', 'Active', '2025-02-26');
INSERT INTO `category` VALUES('7', 'Pen', 'Active', '2025-08-17');
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `customer` VALUES('6', 'sonu', 'latur', 'yes', 'sanu@gmail', '27mh888888', '5200.06', '9878987678', '2025-02-19');
INSERT INTO `customer` VALUES('7', 'abhi', '908', 'Yes', 'abhijithar', '523235235325253', '121', '7867867854', '2025-02-26');
INSERT INTO `customer` VALUES('8', 'Neha swami', 'pune', 'Active', 'neha@gmial', 'GSTIN1234RKYUHU', '5000', '9878987898', '2025-08-17');




CREATE TABLE `invoice_items` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `gst_amount` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`item_id`),
  KEY `invoice_id` (`invoice_id`),
  CONSTRAINT `invoice_items_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`invoice_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `invoice_items` VALUES('1', '1', '3', '1', '0.00', '0.00', '7.00');
INSERT INTO `invoice_items` VALUES('2', '2', '3', '1', '0.00', '0.00', '7.00');
INSERT INTO `invoice_items` VALUES('3', '3', '3', '1', '0.00', '0.00', '7.00');
INSERT INTO `invoice_items` VALUES('4', '4', '3', '1', '0.00', '0.00', '7.00');
INSERT INTO `invoice_items` VALUES('5', '7', '11', '1', '1000.00', '180.00', '1180.00');
INSERT INTO `invoice_items` VALUES('6', '7', '8', '1', '10.00', '1.80', '11.80');
INSERT INTO `invoice_items` VALUES('7', '7', '3', '1', '7.00', '1.26', '8.26');




CREATE TABLE `invoices` (
  `invoice_id` int(11) NOT NULL AUTO_INCREMENT,
  `supplier_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `remaining_balance` decimal(10,2) DEFAULT NULL,
  `final_total` decimal(10,2) DEFAULT NULL,
  `invoice_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `gst_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`invoice_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `invoices` VALUES('1', '1', '7', '7.00', '121.00', '128.00', '2025-02-27 06:34:36', '0.00');
INSERT INTO `invoices` VALUES('2', '1', '7', '7.00', '121.00', '128.00', '2025-02-27 06:38:05', '0.00');
INSERT INTO `invoices` VALUES('3', '1', '6', '7.00', '4000.00', '4007.00', '2025-02-27 06:56:32', '0.00');
INSERT INTO `invoices` VALUES('4', '1', '6', '7.00', '4000.00', '4007.00', '2025-02-27 06:57:11', '0.00');
INSERT INTO `invoices` VALUES('5', '2', '6', '1017.00', '5200.06', '1200.06', '2025-08-17 19:06:48', '183.06');
INSERT INTO `invoices` VALUES('6', '2', '6', '1017.00', '5200.06', '1200.06', '2025-08-17 19:13:10', '183.06');
INSERT INTO `invoices` VALUES('7', '2', '6', '1017.00', '5200.06', '1200.06', '2025-08-17 19:16:05', '183.06');




CREATE TABLE `inward` (
  `inward_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(10) NOT NULL,
  `product_name` varchar(20) NOT NULL,
  `inward_qty` int(10) NOT NULL,
  `supplier_id` int(10) DEFAULT NULL,
  `received_by` varchar(100) DEFAULT NULL,
  `delivered_by` varchar(100) DEFAULT NULL,
  `total_price` varchar(20) NOT NULL,
  `inward_date` date NOT NULL,
  PRIMARY KEY (`inward_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `inward` VALUES('1', '3', '', '200', '1', 'auahmita', 'sona', '2000', '2025-08-17');
INSERT INTO `inward` VALUES('2', '4', '', '100', '1', 'auahmita', 'sona', '10000', '2025-08-17');
INSERT INTO `inward` VALUES('3', '9', '', '50', '2', 'Rajeshwari', 'ruturaj', '3940', '2025-08-17');
INSERT INTO `inward` VALUES('4', '5', '', '140', '1', 'Rohan', 'Rekha', '14000', '2025-08-18');




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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `order_items` VALUES('1', '4', '4', '100', '5600.00');
INSERT INTO `order_items` VALUES('2', '4', '10', '12', '6372.00');




CREATE TABLE `outward` (
  `outward_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `outward_qty` int(11) NOT NULL,
  `delivered_by` varchar(100) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `outward_date` datetime NOT NULL,
  PRIMARY KEY (`outward_id`),
  KEY `fk_outward_product` (`product_id`),
  KEY `fk_outward_customer` (`customer_id`),
  CONSTRAINT `fk_outward_customer` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`),
  CONSTRAINT `fk_outward_product` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `outward` VALUES('1', '4', '6', '10', 'Ramesh', '150.00', '2025-08-18 15:12:01');




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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `product` VALUES('3', '7', '1', 'Cello Finegrip', 'Available', 'It is bllue,red,black pen', 'N', '7', '5', '8', '2025-08-17');
INSERT INTO `product` VALUES('4', '1', '1', 'Classmate Ruled Notebook', 'Active', '200 pages ruled notebook', 'pcs', '50.00', '12', '60', '2025-08-15');
INSERT INTO `product` VALUES('5', '1', '1', 'A4 Spiral Notebook', 'Active', 'A4 size spiral bound notebook', 'pcs', '80.00', '12', '100', '2025-08-15');
INSERT INTO `product` VALUES('6', '8', '1', 'Hardbound Diary', 'Active', 'Daily use hardbound diary', 'pcs', '150.00', '12', '180', '2025-08-15');
INSERT INTO `product` VALUES('7', '3', '1', 'Faber-Castell Drawing Kit', 'Active', 'Complete drawing kit', 'set', '250.00', '18', '300', '2025-08-15');
INSERT INTO `product` VALUES('8', '7', '1', 'Reynolds Ball Pen', 'Active', 'Blue ink ball pen', 'pcs', '10.00', '5', '15', '2025-08-15');
INSERT INTO `product` VALUES('9', '9', '1', 'Milton Water Bottle 1L', 'Active', 'Plastic water bottle 1 litre', 'pcs', '120.00', '12', '150', '2025-08-15');
INSERT INTO `product` VALUES('10', '10', '1', 'USB Keyboard', 'Active', 'Standard USB keyboard', 'pcs', '450.00', '18', '550', '2025-08-15');
INSERT INTO `product` VALUES('11', '10', '2', 'multi task mixser', 'Available', 'gives multi task capacity', 'Pieces', '1000', '2', '990', '0000-00-00');




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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `purchase_orders` VALUES('1', '1', 'Sneha', 'sneha@example.com', '123 Main Street, Pune', '9898989852', '2500.00', '2025-08-15 00:00:00');
INSERT INTO `purchase_orders` VALUES('2', '2', 'Ravi Kumar', 'ravi@example.com', '45 Market Road, Mumbai', '9876543210', '1800.50', '2025-08-15 00:00:00');
INSERT INTO `purchase_orders` VALUES('3', '3', 'Priya Sharma', 'priya@example.com', '78 College Road, Nashik', '9823456789', '3500.75', '2025-08-15 00:00:00');
INSERT INTO `purchase_orders` VALUES('4', '0', 'Ravi kumar', 'ravi@example.com', '45 Market Road, Mumbai', '8976879876', '11972.00', '2025-08-16 07:10:10');




CREATE TABLE `stock` (
  `stock_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `available_stock` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`stock_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `stock_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `stock` VALUES('1', '4', '70');
INSERT INTO `stock` VALUES('2', '9', '50');
INSERT INTO `stock` VALUES('3', '5', '140');




CREATE TABLE `supplier` (
  `supplier_id` int(11) NOT NULL AUTO_INCREMENT,
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `supplier` VALUES('1', 'Sneha@gmail.com', '859561521515248', 'Sneha waghmare', 'Active', 'Pune', '8546', 'Classmate', '9898989852', '2025-08-17');
INSERT INTO `supplier` VALUES('2', 'rajaram@gmail.com', 'GSTIN1234RKYUYB', 'Raja ram pawar', 'Active', '50 Market Road, Mumbai', '0', 'electronics', '8976879868', '2025-08-17');




CREATE TABLE `system_logs` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `log_date` datetime NOT NULL,
  PRIMARY KEY (`log_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `system_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `system_logs` VALUES('1', '22', 'User logged in', '2025-08-15 16:31:33');
INSERT INTO `system_logs` VALUES('2', '22', 'User logged in', '2025-08-16 10:25:02');
INSERT INTO `system_logs` VALUES('3', '22', 'User logged in', '2025-08-16 10:39:28');
INSERT INTO `system_logs` VALUES('4', '22', 'User logged in', '2025-08-17 18:13:39');
INSERT INTO `system_logs` VALUES('5', '22', 'User logged in', '2025-08-18 12:06:30');
INSERT INTO `system_logs` VALUES('6', '22', 'User logged in', '2025-08-18 14:54:08');




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


