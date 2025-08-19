

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) DEFAULT NULL,
  `category_status` enum('Active','Inactive') DEFAULT 'Active',
  `category_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO category VALUES('4', 'Pens', 'Active', '2025-03-14 16:52:39');
INSERT INTO category VALUES('5', 'Pencil', 'Active', '2025-03-03 19:10:47');
INSERT INTO category VALUES('6', 'Markers', 'Active', '2025-03-02 16:18:19');
INSERT INTO category VALUES('7', 'Highlighters', 'Active', '2025-03-02 16:18:30');
INSERT INTO category VALUES('8', 'Notebooks', 'Active', '2025-03-02 16:19:07');
INSERT INTO category VALUES('9', 'Diaries', 'Active', '2025-03-02 16:19:21');
INSERT INTO category VALUES('10', 'Sticky Notes', 'Active', '2025-03-02 16:19:39');
INSERT INTO category VALUES('11', 'Printer Paper', 'Active', '2025-03-02 16:19:49');
INSERT INTO category VALUES('12', 'Files', 'Active', '2025-03-02 21:42:35');
INSERT INTO category VALUES('13', 'Folders', 'Active', '2025-03-02 16:20:07');
INSERT INTO category VALUES('14', 'Clips', 'Active', '2025-03-02 16:20:18');
INSERT INTO category VALUES('15', 'Staplers', 'Active', '2025-03-02 16:20:27');
INSERT INTO category VALUES('16', 'Punching Machines', 'Active', '2025-03-02 16:20:36');
INSERT INTO category VALUES('17', 'Cryons', 'Active', '2025-03-02 16:23:51');
INSERT INTO category VALUES('18', 'Paints', 'Active', '2025-03-02 16:25:05');
INSERT INTO category VALUES('19', 'Brushes', 'Active', '2025-03-02 16:25:20');
INSERT INTO category VALUES('20', 'Sketchbooks', 'Active', '2025-03-02 16:25:31');
INSERT INTO category VALUES('21', 'Glue', 'Active', '2025-03-02 16:26:17');
INSERT INTO category VALUES('22', 'Fevicol', 'Active', '2025-03-02 16:26:31');
INSERT INTO category VALUES('23', 'Sellotape', 'Active', '2025-03-02 16:26:43');
INSERT INTO category VALUES('24', 'Double sided Tape', 'Active', '2025-03-02 16:26:58');
INSERT INTO category VALUES('25', 'Erasers', 'Active', '2025-03-02 16:27:17');
INSERT INTO category VALUES('26', 'Sharpeners', 'Active', '2025-03-02 16:27:29');
INSERT INTO category VALUES('27', 'Rulers', 'Active', '2025-03-02 16:27:45');
INSERT INTO category VALUES('28', 'Geometry Box', 'Active', '2025-03-02 16:27:55');
INSERT INTO category VALUES('29', 'Ink Cartridges', 'Active', '2025-03-02 16:28:38');
INSERT INTO category VALUES('30', 'Labels', 'Active', '2025-03-02 16:28:50');
INSERT INTO category VALUES('31', 'Barcode Stickers', 'Active', '2025-03-02 16:29:01');
INSERT INTO category VALUES('32', 'Bottle', 'Active', '2025-03-06 15:47:35');




CREATE TABLE `customer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(255) DEFAULT NULL,
  `customer_address` text DEFAULT NULL,
  `customer_status` enum('Active','Inactive') DEFAULT 'Active',
  `customer_email` varchar(255) DEFAULT NULL,
  `customer_gstin` varchar(50) DEFAULT NULL,
  `customer_balance` decimal(10,2) DEFAULT 0.00,
  `customer_mobileno` varchar(20) DEFAULT NULL,
  `customer_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `customer_email` (`customer_email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO customer VALUES('1', 'Abhijit Harle', 'Latur', 'Active', 'abhijitharale@gmail.com', 'DF438FGJKRETTTH', '1643.10', '9559846585', '2025-03-02 18:16:22');
INSERT INTO customer VALUES('2', 'Sushmita Mane', 'Pune', 'Active', 'sushmita03@gmail.com', '7868GHGHHFFG556', '2780.00', '7876378267', '2025-03-02 21:49:07');
INSERT INTO customer VALUES('3', 'Rahul', 'Pune', 'Active', 'abhijitharale46@gmail.com', 'FR54647647657CG', '655.00', '6786785765', '2025-03-06 15:48:49');




CREATE TABLE `invoice_items` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `gst_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `unit_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`item_id`),
  KEY `invoice_id` (`invoice_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `invoice_items_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`invoice_id`),
  CONSTRAINT `invoice_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO invoice_items VALUES('1', '1', '1', '50', '500.00', '0.00', '0.00');
INSERT INTO invoice_items VALUES('2', '2', '1', '1', '10.00', '0.00', '0.00');
INSERT INTO invoice_items VALUES('3', '4', '3', '2', '118.00', '18.00', '50.00');
INSERT INTO invoice_items VALUES('4', '5', '1', '10', '118.00', '18.00', '10.00');
INSERT INTO invoice_items VALUES('5', '5', '3', '2', '118.00', '18.00', '50.00');
INSERT INTO invoice_items VALUES('6', '5', '6', '20', '118.00', '18.00', '5.00');
INSERT INTO invoice_items VALUES('7', '6', '11', '5', '118.00', '18.00', '20.00');
INSERT INTO invoice_items VALUES('8', '6', '14', '10', '590.00', '90.00', '50.00');
INSERT INTO invoice_items VALUES('9', '6', '12', '16', '472.00', '72.00', '25.00');
INSERT INTO invoice_items VALUES('10', '7', '11', '5', '118.00', '18.00', '20.00');
INSERT INTO invoice_items VALUES('11', '7', '14', '10', '590.00', '90.00', '50.00');
INSERT INTO invoice_items VALUES('12', '7', '12', '16', '472.00', '72.00', '25.00');
INSERT INTO invoice_items VALUES('13', '8', '17', '6', '389.40', '59.40', '55.00');
INSERT INTO invoice_items VALUES('14', '8', '2', '1', '17.70', '2.70', '15.00');
INSERT INTO invoice_items VALUES('15', '9', '1', '10', '118.00', '18.00', '10.00');
INSERT INTO invoice_items VALUES('16', '9', '5', '4', '118.00', '18.00', '25.00');




CREATE TABLE `invoices` (
  `invoice_id` int(11) NOT NULL AUTO_INCREMENT,
  `supplier_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `remaining_balance` decimal(10,2) DEFAULT NULL,
  `final_total` decimal(10,2) DEFAULT NULL,
  `invoice_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `gst_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`invoice_id`),
  KEY `supplier_id` (`supplier_id`),
  KEY `customer_id` (`customer_id`),
  CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`supplier_id`),
  CONSTRAINT `invoices_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO invoices VALUES('1', '1', '1', '500.00', '1000.00', '1500.00', '2025-03-03 10:25:49', '0.00');
INSERT INTO invoices VALUES('2', '2', '1', '10.00', '1000.00', '1010.00', '2025-03-03 17:56:48', '0.00');
INSERT INTO invoices VALUES('3', '1', '1', '100.00', '118.00', '118.00', '2025-03-03 18:03:36', '18.00');
INSERT INTO invoices VALUES('4', '1', '1', '100.00', '118.00', '118.00', '2025-03-03 18:05:02', '18.00');
INSERT INTO invoices VALUES('5', '1', '1', '300.00', '354.00', '354.00', '2025-03-03 18:38:03', '54.00');
INSERT INTO invoices VALUES('6', '3', '2', '1000.00', '2780.00', '1180.00', '2025-03-03 18:48:32', '180.00');
INSERT INTO invoices VALUES('7', '3', '2', '1000.00', '2780.00', '1180.00', '2025-03-03 18:48:53', '180.00');
INSERT INTO invoices VALUES('8', '1', '1', '345.00', '1407.10', '407.10', '2025-03-06 15:53:14', '62.10');
INSERT INTO invoices VALUES('9', '1', '1', '200.00', '1643.10', '236.00', '2025-03-14 16:54:52', '36.00');




CREATE TABLE `inward` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) DEFAULT NULL,
  `inward_qty` int(11) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `received_by` varchar(255) DEFAULT NULL,
  `delivered_by` varchar(255) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `inward_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `supplier_id` (`supplier_id`),
  CONSTRAINT `inward_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`),
  CONSTRAINT `inward_ibfk_2` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`supplier_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO inward VALUES('1', '1', '100', '1', 'Sushmita', 'Abhijit', '1180.00', '2025-03-03 03:14:50');
INSERT INTO inward VALUES('2', '2', '100', '1', 'Abhijit', 'Sushmita', '1770.00', '2025-03-03 03:15:20');
INSERT INTO inward VALUES('3', '3', '100', '1', 'Sushmita', 'Abhijit', '5900.00', '2025-03-03 03:16:09');
INSERT INTO inward VALUES('4', '4', '100', '1', 'Sushmita', 'Sneha', '3540.00', '2025-03-03 03:16:46');
INSERT INTO inward VALUES('5', '5', '100', '1', 'Sushmita', 'Sneha', '2950.00', '2025-03-03 03:17:25');
INSERT INTO inward VALUES('6', '6', '100', '1', 'Sushmita', 'Abhijit', '590.00', '2025-03-03 03:18:49');
INSERT INTO inward VALUES('7', '7', '100', '1', 'Sushmita', 'Sneha', '708.00', '2025-03-03 03:19:24');
INSERT INTO inward VALUES('8', '8', '100', '1', 'Sushmita', 'Sneha', '2360.00', '2025-03-03 03:19:52');
INSERT INTO inward VALUES('9', '9', '100', '1', 'Sushmita', 'Sneha', '11800.00', '2025-03-03 03:20:20');
INSERT INTO inward VALUES('10', '10', '100', '1', 'Sushmita', 'Sneha', '1770.00', '2025-03-03 03:20:50');
INSERT INTO inward VALUES('11', '11', '100', '1', 'Sushmita', 'Sneha', '2360.00', '2025-03-03 03:21:19');
INSERT INTO inward VALUES('12', '12', '100', '1', 'Sushmita', 'Sneha', '2950.00', '2025-03-03 03:22:11');
INSERT INTO inward VALUES('13', '13', '100', '1', 'Sushmita', 'Sneha', '1770.00', '2025-03-03 03:22:42');
INSERT INTO inward VALUES('14', '14', '100', '1', 'Sushmita', 'Sneha', '5900.00', '2025-03-03 03:23:11');
INSERT INTO inward VALUES('15', '15', '100', '1', 'Sushmita', 'Sneha', '3540.00', '2025-03-03 03:23:40');
INSERT INTO inward VALUES('16', '16', '100', '1', 'Sushmita', 'Sneha', '1.00', '2025-03-02 21:54:45');




CREATE TABLE `order_items` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `purchase_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`item_id`),
  KEY `purchase_id` (`purchase_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`purchase_id`) REFERENCES `purchase_orders` (`purchase_id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO order_items VALUES('1', '1', '1', '100', '1000.00', '');
INSERT INTO order_items VALUES('2', '1', '2', '100', '1500.00', '');
INSERT INTO order_items VALUES('3', '1', '3', '100', '5000.00', '');
INSERT INTO order_items VALUES('4', '1', '4', '100', '3000.00', '');
INSERT INTO order_items VALUES('5', '1', '5', '100', '2500.00', '');
INSERT INTO order_items VALUES('6', '1', '6', '100', '500.00', '');
INSERT INTO order_items VALUES('7', '1', '7', '100', '600.00', '');
INSERT INTO order_items VALUES('8', '1', '8', '100', '2000.00', '');
INSERT INTO order_items VALUES('9', '1', '9', '100', '10000.00', '');
INSERT INTO order_items VALUES('10', '1', '10', '100', '1500.00', '');
INSERT INTO order_items VALUES('11', '1', '11', '100', '2000.00', '');
INSERT INTO order_items VALUES('12', '1', '12', '100', '2500.00', '');
INSERT INTO order_items VALUES('13', '1', '13', '100', '1500.00', '');
INSERT INTO order_items VALUES('14', '1', '14', '100', '5000.00', '');
INSERT INTO order_items VALUES('15', '1', '15', '100', '3000.00', '');
INSERT INTO order_items VALUES('16', '1', '16', '100', '800.00', '');
INSERT INTO order_items VALUES('17', '2', '16', '5', '47.20', '');
INSERT INTO order_items VALUES('18', '2', '1', '6', '70.80', '');
INSERT INTO order_items VALUES('19', '3', '1', '10', '118.00', '');
INSERT INTO order_items VALUES('20', '4', '12', '20', '590.00', '');
INSERT INTO order_items VALUES('21', '5', '11', '1', '23.60', '');
INSERT INTO order_items VALUES('22', '5', '14', '1', '59.00', '');




CREATE TABLE `outward` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) DEFAULT NULL,
  `outward_qty` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `delivered_by` varchar(255) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `outward_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `customer_id` (`customer_id`),
  CONSTRAINT `outward_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`),
  CONSTRAINT `outward_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO outward VALUES('1', '1', '50', '1', 'Sneha', '500.00', '2025-03-03 10:33:07');




CREATE TABLE `payment` (
  `payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_id` int(11) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `amount_paid` decimal(10,2) DEFAULT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_status` enum('Paid','Pending') DEFAULT 'Pending',
  PRIMARY KEY (`payment_id`),
  KEY `invoice_id` (`invoice_id`),
  KEY `supplier_id` (`supplier_id`),
  KEY `customer_id` (`customer_id`),
  CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`invoice_id`),
  CONSTRAINT `payment_ibfk_2` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`supplier_id`),
  CONSTRAINT `payment_ibfk_3` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;





CREATE TABLE `product` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `product_status` enum('Available','Out of Stock','Active','Inactive') DEFAULT 'Available',
  `product_description` text DEFAULT NULL,
  `product_unit` varchar(50) DEFAULT NULL,
  `product_sprice` decimal(10,2) DEFAULT NULL,
  `product_gst` decimal(5,2) DEFAULT NULL,
  `product_maxprice` decimal(10,2) DEFAULT NULL,
  `product_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`product_id`),
  KEY `category_id` (`category_id`),
  KEY `supplier_id` (`supplier_id`),
  CONSTRAINT `product_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`),
  CONSTRAINT `product_ibfk_2` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`supplier_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO product VALUES('1', '4', '1', 'Ball Pen', 'Available', 'Smooth writing ball pen', 'pcs', '10.00', '18.00', '15.00', '2025-03-02 19:12:35');
INSERT INTO product VALUES('2', '4', '1', 'Gel Pen', 'Available', 'Premium gel ink pen', 'pcs', '15.00', '18.00', '20.00', '2025-03-02 00:00:00');
INSERT INTO product VALUES('3', '4', '3', 'Fountain Pen', 'Available', 'Classic ink pen', 'pcs', '50.00', '18.00', '70.00', '2025-03-02 19:12:35');
INSERT INTO product VALUES('4', '4', '4', 'Styles Pen', 'Available', 'Pen with touch screen stylus', 'pcs', '30.00', '18.00', '40.00', '2025-03-02 21:40:39');
INSERT INTO product VALUES('5', '4', '5', 'Marker Pen', 'Available', 'Permanent marker', 'pcs', '25.00', '18.00', '35.00', '2025-03-02 19:12:35');
INSERT INTO product VALUES('6', '5', '1', 'HB Pencil', 'Available', 'Standard writing pencil', 'pcs', '5.00', '18.00', '10.00', '2025-03-02 19:12:35');
INSERT INTO product VALUES('7', '5', '2', '2B Pencil', 'Available', 'Dark shade pencil', 'pcs', '6.00', '18.00', '12.00', '2025-03-02 19:12:35');
INSERT INTO product VALUES('8', '5', '3', 'Mechanical Pencil', 'Available', 'Refillable lead pencil', 'pcs', '20.00', '18.00', '30.00', '2025-03-02 19:12:35');
INSERT INTO product VALUES('9', '5', '4', 'Colored Pencils Set', 'Available', '12-piece colored pencils', 'set', '100.00', '18.00', '120.00', '2025-03-02 19:12:35');
INSERT INTO product VALUES('10', '5', '5', 'Charcoal Pencil', 'Available', 'For sketching and shading', 'pcs', '15.00', '18.00', '25.00', '2025-03-02 19:12:35');
INSERT INTO product VALUES('11', '6', '1', 'Whiteboard Marker', 'Available', 'For whiteboards', 'pcs', '20.00', '18.00', '30.00', '2025-03-02 19:12:35');
INSERT INTO product VALUES('12', '6', '2', 'Permanent Marker', 'Available', 'Waterproof marker', 'pcs', '25.00', '18.00', '35.00', '2025-03-02 19:12:35');
INSERT INTO product VALUES('13', '6', '3', 'Highlighter Marker', 'Available', 'For highlighting text', 'pcs', '15.00', '18.00', '25.00', '2025-03-02 19:12:35');
INSERT INTO product VALUES('14', '6', '4', 'Paint Marker', 'Available', 'For artistic painting', 'pcs', '50.00', '18.00', '60.00', '2025-03-02 19:12:35');
INSERT INTO product VALUES('15', '6', '5', 'Glass Marker', 'Available', 'Writes on glass', 'pcs', '30.00', '18.00', '40.00', '2025-03-02 19:12:35');
INSERT INTO product VALUES('16', '4', '1', 'Cello Finegrip', 'Available', 'It Is blue pen for better writing', 'Pieces', '8.00', '18.00', '8.00', '2025-03-02 21:50:36');
INSERT INTO product VALUES('17', '8', '2', 'Classmates', 'Available', 'Register', 'Pieces', '55.00', '18.00', '60.00', '2025-03-06 15:51:03');




CREATE TABLE `purchase_orders` (
  `purchase_id` int(11) NOT NULL AUTO_INCREMENT,
  `supplier_id` int(11) DEFAULT NULL,
  `suplier_email` varchar(255) DEFAULT NULL,
  `suplier_name` varchar(255) DEFAULT NULL,
  `suplier_adress` text DEFAULT NULL,
  `suplier_mobileno` varchar(20) DEFAULT NULL,
  `po_total_price` decimal(10,2) DEFAULT NULL,
  `po_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`purchase_id`),
  KEY `purchase_orders_ibfk_1` (`supplier_id`),
  CONSTRAINT `purchase_orders_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`supplier_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO purchase_orders VALUES('1', '1', 'supplierA@example.com', 'Supplier A', 'Address 1', '9876543210', '42400.00', '2025-03-02 22:43:03');
INSERT INTO purchase_orders VALUES('2', '1', 'supplierA@example.com', 'Supplier A', 'Address 1', '9876543210', '118.00', '2025-03-12 19:25:02');
INSERT INTO purchase_orders VALUES('3', '1', 'supplierA@example.com', 'Supplier A', 'Address 1', '9876543210', '118.00', '2025-03-12 19:41:56');
INSERT INTO purchase_orders VALUES('4', '2', 'supplierB@example.com', 'Supplier B', 'Address 2', '9876543220', '590.00', '2025-03-12 19:43:15');
INSERT INTO purchase_orders VALUES('5', '2', 'sneha@example.com', 'Sneha', 'Address 2', '9876543220', '82.60', '2025-03-14 12:26:05');




CREATE TABLE `returns` (
  `return_id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `return_qty` int(11) DEFAULT NULL,
  `return_reason` text DEFAULT NULL,
  `return_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`return_id`),
  KEY `invoice_id` (`invoice_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `returns_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`invoice_id`),
  CONSTRAINT `returns_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;





CREATE TABLE `stock` (
  `stock_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) DEFAULT NULL,
  `available_stock` int(11) DEFAULT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`stock_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `stock_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO stock VALUES('1', '1', '-21', '2025-03-14 16:54:52');
INSERT INTO stock VALUES('2', '2', '99', '2025-03-06 15:53:14');
INSERT INTO stock VALUES('3', '3', '96', '2025-03-03 18:38:03');
INSERT INTO stock VALUES('4', '4', '100', '2025-03-03 03:16:47');
INSERT INTO stock VALUES('5', '5', '96', '2025-03-14 16:54:52');
INSERT INTO stock VALUES('6', '6', '80', '2025-03-03 18:38:03');
INSERT INTO stock VALUES('7', '7', '100', '2025-03-03 03:19:24');
INSERT INTO stock VALUES('8', '8', '100', '2025-03-03 03:19:53');
INSERT INTO stock VALUES('9', '9', '100', '2025-03-03 03:20:20');
INSERT INTO stock VALUES('10', '10', '100', '2025-03-03 03:20:51');
INSERT INTO stock VALUES('11', '11', '90', '2025-03-03 18:48:53');
INSERT INTO stock VALUES('12', '12', '68', '2025-03-03 18:48:53');
INSERT INTO stock VALUES('13', '13', '100', '2025-03-03 03:22:43');
INSERT INTO stock VALUES('14', '14', '80', '2025-03-03 18:48:53');
INSERT INTO stock VALUES('15', '15', '100', '2025-03-03 03:23:41');
INSERT INTO stock VALUES('16', '16', '100', '2025-03-03 03:24:17');




CREATE TABLE `supplier` (
  `supplier_id` int(11) NOT NULL AUTO_INCREMENT,
  `supplier_name` varchar(255) DEFAULT NULL,
  `supplier_email` varchar(255) DEFAULT NULL,
  `supplier_gstin` varchar(50) DEFAULT NULL,
  `supplier_status` enum('Active','Inactive') DEFAULT 'Active',
  `supplier_address` text DEFAULT NULL,
  `supplier_balance` decimal(10,2) DEFAULT 0.00,
  `supplier_brand` varchar(255) DEFAULT NULL,
  `supplier_mobileno` varchar(20) DEFAULT NULL,
  `supplier_data` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`supplier_id`),
  UNIQUE KEY `supplier_email` (`supplier_email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO supplier VALUES('1', '\r\nSushmita', 'sushmita@example.com', '29ABCDE1234F1Z5', 'Active', 'Address 1', '1000.00', 'Brand A', '9876543210', '2025-03-02 18:55:07');
INSERT INTO supplier VALUES('2', 'Sneha', 'sneha@example.com', '29ABCDE5678F1Z5', 'Active', 'Address 2', '2000.00', 'Brand B', '9876543220', '2025-03-02 18:55:07');
INSERT INTO supplier VALUES('3', 'Aditya', 'aditya@example.com', '29ABCDE9012F1Z5', 'Active', 'Address 3', '3000.00', 'Brand C', '9876543230', '2025-03-02 18:55:07');
INSERT INTO supplier VALUES('4', 'Rahul', 'rahul@example.com', '29ABCDE3456F1Z5', 'Active', 'Address 4', '4000.00', 'Brand D', '9876543240', '2025-03-02 18:55:07');
INSERT INTO supplier VALUES('5', 'Vaishnavi', 'vaishnavi@example.com', '29ABCDE7890F1Z5', 'Active', 'Address 5', '5000.00', 'Brand E', '9876543250', '2025-03-02 18:55:07');




CREATE TABLE `system_logs` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `action` text DEFAULT NULL,
  `log_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`log_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `system_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO system_logs VALUES('1', '1', 'User logged in', '2025-03-03 15:22:13');
INSERT INTO system_logs VALUES('2', '1', 'User logged in', '2025-03-03 19:55:40');
INSERT INTO system_logs VALUES('3', '1', 'User logged in', '2025-03-05 09:05:09');
INSERT INTO system_logs VALUES('4', '1', 'User logged in', '2025-03-06 07:27:43');
INSERT INTO system_logs VALUES('5', '2', 'User logged in', '2025-03-06 15:46:31');
INSERT INTO system_logs VALUES('6', '1', 'User logged in', '2025-03-09 22:03:37');
INSERT INTO system_logs VALUES('7', '2', 'User logged in', '2025-03-11 20:56:07');
INSERT INTO system_logs VALUES('8', '2', 'User logged in', '2025-03-12 22:52:29');
INSERT INTO system_logs VALUES('9', '2', 'User logged in', '2025-03-13 00:09:03');
INSERT INTO system_logs VALUES('10', '2', 'User logged in', '2025-03-13 01:07:48');
INSERT INTO system_logs VALUES('11', '2', 'User logged in', '2025-03-13 08:09:16');
INSERT INTO system_logs VALUES('12', '1', 'User logged in', '2025-03-13 12:07:01');
INSERT INTO system_logs VALUES('13', '2', 'User logged in', '2025-03-14 16:19:57');
INSERT INTO system_logs VALUES('14', '1', 'User logged in', '2025-03-14 16:50:50');




CREATE TABLE `user_roles` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) DEFAULT NULL,
  `permissions` text DEFAULT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;





CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `user_email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `mobile_no` varchar(20) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `adress` text DEFAULT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `profile_pic` varchar(255) DEFAULT NULL,
  `security_question` varchar(255) DEFAULT NULL,
  `security_answer` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `user_email` (`user_email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO users VALUES('1', 'Admin', 'Sneha@1Abhi@2Sush@3', 'abhijitharale46@gmail.com', 'Admin@123', '9561517091', 'Dayla Precision', 'Pune', '2025-03-02 00:00:00', 'uploads/1740923139_profile.jpg', 'What is your favorite color?', 'Pink');
INSERT INTO users VALUES('2', 'Admin', 'Admin@123', 'abhijitharale4@gmail.com', 'Admin@123', '7757848380', 'Dayla Precision', 'Pune', '2025-03-06 00:00:00', 'uploads/1741256165_ahphoto.jpg', 'first_mobile_brand', 'Redmi');




CREATE TABLE `warehouse` (
  `warehouse_id` int(11) NOT NULL AUTO_INCREMENT,
  `warehouse_name` varchar(255) DEFAULT NULL,
  `warehouse_location` text DEFAULT NULL,
  `warehouse_status` enum('Active','Inactive') DEFAULT 'Active',
  PRIMARY KEY (`warehouse_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



