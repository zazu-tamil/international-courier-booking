-- International Courier ERP Database Schema
-- Suitable for MySQL 5.7 / MySQL 8

SET FOREIGN_KEY_CHECKS = 0;

-- 1. Roles Table
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(50) NOT NULL UNIQUE,
  `description` TEXT,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Permissions Table
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL UNIQUE,
  `description` TEXT,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Role Permissions Table
DROP TABLE IF EXISTS `role_permissions`;
CREATE TABLE `role_permissions` (
  `role_id` INT NOT NULL,
  `permission_id` INT NOT NULL,
  PRIMARY KEY (`role_id`, `permission_id`),
  FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Branches Table
DROP TABLE IF EXISTS `branches`;
CREATE TABLE `branches` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `branch_code` VARCHAR(20) NOT NULL UNIQUE,
  `address` TEXT,
  `contact_person` VARCHAR(100),
  `mobile` VARCHAR(20),
  `email` VARCHAR(100),
  `status` ENUM('Active', 'Inactive') DEFAULT 'Active',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` DATETIME DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE INDEX idx_branch_code ON branches(branch_code);

-- 5. Users Table
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(100) NOT NULL UNIQUE,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role_id` INT NOT NULL,
  `branch_id` INT DEFAULT NULL,
  `franchise_id` INT DEFAULT NULL,
  `status` ENUM('Active', 'Inactive') DEFAULT 'Active',
  `last_login` DATETIME DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` DATETIME DEFAULT NULL,
  FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`),
  FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE INDEX idx_user_role ON users(role_id);
CREATE INDEX idx_user_email ON users(email);

-- 6. Franchises Table
DROP TABLE IF EXISTS `franchises`;
CREATE TABLE `franchises` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `franchise_code` VARCHAR(20) NOT NULL UNIQUE,
  `user_id` INT DEFAULT NULL,
  `deposit_amount` DECIMAL(12,2) DEFAULT '0.00',
  `agreement_date` DATE DEFAULT NULL,
  `revenue_sharing_percentage` DECIMAL(5,2) DEFAULT '0.00',
  `commission_percentage` DECIMAL(5,2) DEFAULT '0.00',
  `status` ENUM('Active', 'Inactive') DEFAULT 'Active',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` DATETIME DEFAULT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE INDEX idx_franchise_code ON franchises(franchise_code);

-- Link users to franchises
ALTER TABLE `users` ADD FOREIGN KEY (`franchise_id`) REFERENCES `franchises` (`id`) ON DELETE SET NULL;

-- 7. Countries Table
DROP TABLE IF EXISTS `countries`;
CREATE TABLE `countries` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `country_name` VARCHAR(100) NOT NULL UNIQUE,
  `iso_code` VARCHAR(3) NOT NULL UNIQUE,
  `country_code` VARCHAR(10) NOT NULL,
  `currency` VARCHAR(10) DEFAULT 'USD',
  `customs_required` TINYINT(1) DEFAULT '0',
  `restricted_items` TEXT,
  `status` ENUM('Active', 'Inactive') DEFAULT 'Active',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 8. Customers Table
DROP TABLE IF EXISTS `customers`;
CREATE TABLE `customers` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL UNIQUE,
  `customer_type` ENUM('individual', 'business') NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `company_name` VARCHAR(150) DEFAULT NULL,
  `mobile` VARCHAR(20) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `address` TEXT,
  `city` VARCHAR(100),
  `state` VARCHAR(100),
  `country_id` INT NOT NULL,
  `zip_code` VARCHAR(20),
  `credit_limit` DECIMAL(12,2) DEFAULT '0.00',
  `credit_days` INT DEFAULT 0,
  `outstanding_balance` DECIMAL(12,2) DEFAULT '0.00',
  `status` ENUM('Active', 'Inactive') DEFAULT 'Active',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` DATETIME DEFAULT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE INDEX idx_customer_email ON customers(email);

-- 9. Customer KYC Table
DROP TABLE IF EXISTS `customer_kyc`;
CREATE TABLE `customer_kyc` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `customer_id` INT NOT NULL,
  `passport_number` VARCHAR(50) DEFAULT NULL,
  `aadhaar_number` VARCHAR(20) DEFAULT NULL,
  `gst_number` VARCHAR(20) DEFAULT NULL,
  `pan_number` VARCHAR(20) DEFAULT NULL,
  `trade_license` VARCHAR(50) DEFAULT NULL,
  `company_registration_certificate` VARCHAR(50) DEFAULT NULL,
  `authorized_person` TEXT DEFAULT NULL,
  `id_proof_file` VARCHAR(255) DEFAULT NULL,
  `address_proof_file` VARCHAR(255) DEFAULT NULL,
  `status` ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
  `reject_reason` TEXT DEFAULT NULL,
  `approved_by` INT DEFAULT NULL,
  `approved_at` DATETIME DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 10. Courier Partners Table
DROP TABLE IF EXISTS `courier_partners`;
CREATE TABLE `courier_partners` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `partner_name` VARCHAR(100) NOT NULL UNIQUE,
  `api_credentials` TEXT,
  `tracking_url` VARCHAR(255),
  `service_types` TEXT, -- JSON or Comma Separated (e.g. Express, Economy)
  `status` ENUM('Active', 'Inactive') DEFAULT 'Active',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 11. Rate Master Table
DROP TABLE IF EXISTS `rate_master`;
CREATE TABLE `rate_master` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `origin_country_id` INT NOT NULL,
  `destination_country_id` INT NOT NULL,
  `service_type` VARCHAR(50) NOT NULL, -- e.g. Express, Economy
  `weight_slab_start` DECIMAL(8,3) NOT NULL, -- e.g. 0.000
  `weight_slab_end` DECIMAL(8,3) NOT NULL,   -- e.g. 0.500
  `base_rate` DECIMAL(12,2) NOT NULL,
  `fuel_surcharge` DECIMAL(5,2) DEFAULT '0.00', -- Percentage
  `handling_charges` DECIMAL(12,2) DEFAULT '0.00',
  `insurance_charges` DECIMAL(12,2) DEFAULT '0.00',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`origin_country_id`) REFERENCES `countries` (`id`),
  FOREIGN KEY (`destination_country_id`) REFERENCES `countries` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 12. Shipment Master Table
DROP TABLE IF EXISTS `shipment_master`;
CREATE TABLE `shipment_master` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `awb_number` VARCHAR(50) NOT NULL UNIQUE,
  `booking_date` DATE NOT NULL,
  `service_type` VARCHAR(50) NOT NULL,
  `shipment_type` ENUM('Documents', 'Non-Documents') DEFAULT 'Non-Documents',
  `origin_country_id` INT NOT NULL,
  `destination_country_id` INT NOT NULL,
  `courier_partner_id` INT NOT NULL,
  `customer_id` INT NOT NULL, -- Sender (logged-in customer links here)
  `total_weight` DECIMAL(10,3) DEFAULT '0.000',
  `total_volumetric_weight` DECIMAL(10,3) DEFAULT '0.000',
  `chargeable_weight` DECIMAL(10,3) DEFAULT '0.000',
  `total_declared_value` DECIMAL(12,2) DEFAULT '0.00',
  `estimated_charges` DECIMAL(12,2) DEFAULT '0.00',
  `status` VARCHAR(50) DEFAULT 'Booking Created',
  `verification_status` ENUM('Pending', 'Completed') DEFAULT 'Pending',
  `declaration_status` ENUM('Pending', 'Accepted') DEFAULT 'Pending',
  `terms_status` ENUM('Pending', 'Accepted') DEFAULT 'Pending',
  `signature_status` ENUM('Pending', 'Completed') DEFAULT 'Pending',
  `otp_verification_status` ENUM('Pending', 'Verified') DEFAULT 'Pending',
  `ready_for_dispatch_status` ENUM('Pending', 'Ready') DEFAULT 'Pending',
  `verification_completed_at` DATETIME DEFAULT NULL,
  `created_by` INT NOT NULL,
  `branch_id` INT DEFAULT NULL,
  `franchise_id` INT DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` DATETIME DEFAULT NULL,
  FOREIGN KEY (`origin_country_id`) REFERENCES `countries` (`id`),
  FOREIGN KEY (`destination_country_id`) REFERENCES `countries` (`id`),
  FOREIGN KEY (`courier_partner_id`) REFERENCES `courier_partners` (`id`),
  FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  FOREIGN KEY (`franchise_id`) REFERENCES `franchises` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE INDEX idx_awb_number ON shipment_master(awb_number);
CREATE INDEX idx_shipment_status ON shipment_master(status);

-- 13. Shipment Sender Table
DROP TABLE IF EXISTS `shipment_sender`;
CREATE TABLE `shipment_sender` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `shipment_id` INT NOT NULL UNIQUE,
  `name` VARCHAR(150) NOT NULL,
  `company_name` VARCHAR(150),
  `mobile` VARCHAR(20) NOT NULL,
  `alternate_mobile` VARCHAR(20),
  `email` VARCHAR(100),
  `address` TEXT NOT NULL,
  `city` VARCHAR(100) NOT NULL,
  `state` VARCHAR(100) NOT NULL,
  `country_id` INT NOT NULL,
  `zip_code` VARCHAR(20) NOT NULL,
  FOREIGN KEY (`shipment_id`) REFERENCES `shipment_master` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 14. Shipment Receiver Table
DROP TABLE IF EXISTS `shipment_receiver`;
CREATE TABLE `shipment_receiver` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `shipment_id` INT NOT NULL UNIQUE,
  `name` VARCHAR(150) NOT NULL,
  `company_name` VARCHAR(150),
  `mobile` VARCHAR(20) NOT NULL,
  `alternate_mobile` VARCHAR(20),
  `email` VARCHAR(100),
  `address` TEXT NOT NULL,
  `city` VARCHAR(100) NOT NULL,
  `state` VARCHAR(100) NOT NULL,
  `country_id` INT NOT NULL,
  `zip_code` VARCHAR(20) NOT NULL,
  FOREIGN KEY (`shipment_id`) REFERENCES `shipment_master` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 15. Shipment Boxes Table
DROP TABLE IF EXISTS `shipment_boxes`;
CREATE TABLE `shipment_boxes` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `shipment_id` INT NOT NULL,
  `box_number` INT NOT NULL,
  `length` DECIMAL(8,2) NOT NULL,
  `width` DECIMAL(8,2) NOT NULL,
  `height` DECIMAL(8,2) NOT NULL,
  `actual_weight` DECIMAL(8,3) NOT NULL,
  `volumetric_weight` DECIMAL(8,3) NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`shipment_id`) REFERENCES `shipment_master` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 16. Shipment Items Table
DROP TABLE IF EXISTS `shipment_items`;
CREATE TABLE `shipment_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `shipment_id` INT NOT NULL,
  `item_description` VARCHAR(255) NOT NULL,
  `hs_code` VARCHAR(30),
  `quantity` INT NOT NULL DEFAULT 1,
  `unit_value` DECIMAL(12,2) NOT NULL DEFAULT '0.00',
  `total_value` DECIMAL(12,2) NOT NULL DEFAULT '0.00',
  `country_of_origin_id` INT DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`shipment_id`) REFERENCES `shipment_master` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`country_of_origin_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 17. Shipment Tracking Table
DROP TABLE IF EXISTS `shipment_tracking`;
CREATE TABLE `shipment_tracking` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `shipment_id` INT NOT NULL,
  `date_time` DATETIME NOT NULL,
  `location` VARCHAR(150) NOT NULL,
  `remarks` TEXT,
  `updated_by` INT NOT NULL,
  `branch_id` INT DEFAULT NULL,
  `courier_partner_id` INT DEFAULT NULL,
  `status` VARCHAR(50) NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`shipment_id`) REFERENCES `shipment_master` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`),
  FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  FOREIGN KEY (`courier_partner_id`) REFERENCES `courier_partners` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE INDEX idx_track_shipment ON shipment_tracking(shipment_id);

-- 18. Shipment Documents Table
DROP TABLE IF EXISTS `shipment_documents`;
CREATE TABLE `shipment_documents` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `shipment_id` INT NOT NULL,
  `doc_type` VARCHAR(100) NOT NULL, -- e.g. Invoice, Packing List, Passport, KYC, Customs Documents
  `file_path` VARCHAR(255) NOT NULL,
  `uploaded_by` INT NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`shipment_id`) REFERENCES `shipment_master` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 19. Pickup Requests Table
DROP TABLE IF EXISTS `pickup_requests`;
CREATE TABLE `pickup_requests` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `shipment_id` INT DEFAULT NULL,
  `pickup_date` DATE NOT NULL,
  `pickup_time` TIME NOT NULL,
  `address` TEXT NOT NULL,
  `contact_person` VARCHAR(100) NOT NULL,
  `mobile` VARCHAR(20) NOT NULL,
  `remarks` TEXT,
  `status` ENUM('Requested', 'Assigned', 'Picked Up', 'Cancelled') DEFAULT 'Requested',
  `assigned_to` INT DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`shipment_id`) REFERENCES `shipment_master` (`id`) ON DELETE SET NULL,
  FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 20. Terms Conditions Master Table
DROP TABLE IF EXISTS `terms_conditions_master`;
CREATE TABLE `terms_conditions_master` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(150) NOT NULL,
  `version_number` VARCHAR(20) NOT NULL,
  `effective_date` DATE NOT NULL,
  `expiry_date` DATE DEFAULT NULL,
  `terms_content` LONGTEXT NOT NULL,
  `status` ENUM('Draft', 'Published', 'Archived') DEFAULT 'Draft',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 21. Terms Acceptance Log Table
DROP TABLE IF EXISTS `terms_acceptance_log`;
CREATE TABLE `terms_acceptance_log` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `customer_id` INT NOT NULL,
  `shipment_id` INT NOT NULL,
  `terms_version_id` INT NOT NULL,
  `acceptance_status` ENUM('Pending', 'Accepted') DEFAULT 'Accepted',
  `acceptance_time` DATETIME NOT NULL,
  `ip_address` VARCHAR(50),
  `browser_details` VARCHAR(255),
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`shipment_id`) REFERENCES `shipment_master` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`terms_version_id`) REFERENCES `terms_conditions_master` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 22. Declaration Acceptance Log Table
DROP TABLE IF EXISTS `declaration_acceptance_log`;
CREATE TABLE `declaration_acceptance_log` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `customer_id` INT NOT NULL,
  `shipment_id` INT NOT NULL,
  `declaration_status` ENUM('Pending', 'Accepted') DEFAULT 'Accepted',
  `acceptance_time` DATETIME NOT NULL,
  `ip_address` VARCHAR(50),
  `browser_details` VARCHAR(255),
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`shipment_id`) REFERENCES `shipment_master` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 23. OTP Verification Log Table
DROP TABLE IF EXISTS `otp_verification_log`;
CREATE TABLE `otp_verification_log` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `customer_id` INT NOT NULL,
  `shipment_id` INT NOT NULL,
  `otp_code` VARCHAR(10) NOT NULL,
  `otp_channel` ENUM('Email', 'SMS', 'WhatsApp') NOT NULL,
  `verification_status` ENUM('Pending', 'Verified') DEFAULT 'Pending',
  `sent_at` DATETIME NOT NULL,
  `verified_at` DATETIME DEFAULT NULL,
  `ip_address` VARCHAR(50),
  `browser_details` VARCHAR(255),
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`shipment_id`) REFERENCES `shipment_master` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 24. Customer Signatures Table
DROP TABLE IF EXISTS `customer_signatures`;
CREATE TABLE `customer_signatures` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `customer_id` INT NOT NULL,
  `shipment_id` INT NOT NULL,
  `signature_image_path` VARCHAR(255) NOT NULL,
  `ip_address` VARCHAR(50),
  `browser_details` VARCHAR(255),
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`shipment_id`) REFERENCES `shipment_master` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 25. Invoices Table
DROP TABLE IF EXISTS `invoices`;
CREATE TABLE `invoices` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `shipment_id` INT NOT NULL,
  `invoice_number` VARCHAR(50) NOT NULL UNIQUE,
  `invoice_date` DATE NOT NULL,
  `total_amount` DECIMAL(12,2) NOT NULL DEFAULT '0.00',
  `tax_amount` DECIMAL(12,2) NOT NULL DEFAULT '0.00',
  `discount_amount` DECIMAL(12,2) NOT NULL DEFAULT '0.00',
  `final_amount` DECIMAL(12,2) NOT NULL DEFAULT '0.00',
  `status` ENUM('Unpaid', 'Paid', 'Cancelled') DEFAULT 'Unpaid',
  `pdf_path` VARCHAR(255) DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`shipment_id`) REFERENCES `shipment_master` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 26. Payments Table
DROP TABLE IF EXISTS `payments`;
CREATE TABLE `payments` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `invoice_id` INT NOT NULL,
  `payment_number` VARCHAR(50) NOT NULL UNIQUE,
  `payment_date` DATE NOT NULL,
  `payment_mode` ENUM('Cash', 'UPI', 'Card', 'Bank Transfer', 'Wallet') NOT NULL,
  `amount` DECIMAL(12,2) NOT NULL,
  `transaction_id` VARCHAR(100) DEFAULT NULL,
  `remarks` TEXT,
  `created_by` INT NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`created_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 27. Customer Wallet Table
DROP TABLE IF EXISTS `customer_wallet`;
CREATE TABLE `customer_wallet` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `customer_id` INT NOT NULL UNIQUE,
  `balance` DECIMAL(12,2) NOT NULL DEFAULT '0.00',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 28. Customer Wallet Transactions Table
DROP TABLE IF EXISTS `customer_wallet_transactions`;
CREATE TABLE `customer_wallet_transactions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `customer_id` INT NOT NULL,
  `transaction_type` ENUM('Credit', 'Debit') NOT NULL,
  `amount` DECIMAL(12,2) NOT NULL,
  `description` TEXT,
  `reference_id` VARCHAR(100) DEFAULT NULL, -- e.g. payment_id, shipment_id
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 29. Customer Ledger Table
DROP TABLE IF EXISTS `customer_ledger`;
CREATE TABLE `customer_ledger` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `customer_id` INT NOT NULL,
  `entry_date` DATE NOT NULL,
  `entry_type` ENUM('Invoice', 'Payment', 'Credit Note', 'Debit Note') NOT NULL,
  `reference_id` VARCHAR(100) DEFAULT NULL,
  `debit_amount` DECIMAL(12,2) DEFAULT '0.00',
  `credit_amount` DECIMAL(12,2) DEFAULT '0.00',
  `running_balance` DECIMAL(12,2) NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 30. Notifications Table
DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT DEFAULT NULL, -- recipient user ID (if registered)
  `recipient_contact` VARCHAR(150) NOT NULL, -- email/mobile number
  `type` ENUM('Email', 'SMS', 'WhatsApp') NOT NULL,
  `subject` VARCHAR(255) DEFAULT NULL,
  `message` TEXT NOT NULL,
  `status` ENUM('Sent', 'Failed', 'Pending') DEFAULT 'Sent',
  `sent_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 31. Audit Logs Table
DROP TABLE IF EXISTS `audit_logs`;
CREATE TABLE `audit_logs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT DEFAULT NULL,
  `action` VARCHAR(255) NOT NULL,
  `ip_address` VARCHAR(50),
  `browser_details` VARCHAR(255),
  `details` TEXT,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- SEED DEFAULT SYSTEM DATA
INSERT INTO `roles` (`id`, `name`, `description`) VALUES
(1, 'Super Admin', 'Full system access and control over all entities'),
(2, 'Branch Admin', 'Manages operations within their designated branch'),
(3, 'Franchise User', 'Registers customers, books shipments, and requests pickups'),
(4, 'Customer', 'Portal access for reviewing bookings, digital signature, and KYC submission');

-- Insert Permissions
INSERT INTO `permissions` (`id`, `name`, `description`) VALUES
(1, 'manage_settings', 'Modify system variables and settings'),
(2, 'manage_users', 'Add, Edit, Delete system users'),
(3, 'manage_branches', 'CRUD operations on branches'),
(4, 'manage_franchises', 'CRUD operations on franchises'),
(5, 'manage_customers', 'CRUD operations on customers'),
(6, 'create_booking', 'Access to book a shipment'),
(7, 'manage_rates', 'Manage country shipping rate matrices'),
(8, 'manage_kyc', 'Approve or reject customer KYC uploads'),
(9, 'update_tracking', 'Perform tracking stage status updates'),
(10, 'manage_payments', 'Receive invoice payments');

-- Link role permissions (All for Super Admin)
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES
(1, 1), (1, 2), (1, 3), (1, 4), (1, 5), (1, 6), (1, 7), (1, 8), (1, 9), (1, 10),
(2, 5), (2, 6), (2, 9), (2, 10),
(3, 5), (3, 6), (3, 9);

-- Insert Default Branch
INSERT INTO `branches` (`id`, `name`, `branch_code`, `address`, `contact_person`, `mobile`, `email`, `status`) VALUES
(1, 'Headquarters Origin Branch', 'HQ-B01', '101 Logistics Boulevard, Sector 5', 'Operation Manager', '9876543210', 'hq@couriersyn.com', 'Active');

-- Insert Default Super Admin (password is admin123 hashed)
INSERT INTO `users` (`id`, `username`, `email`, `password`, `role_id`, `branch_id`, `status`) VALUES
(1, 'superadmin', 'admin@couriersyn.com', '$2y$10$KNiiXnvTVxymg33RxizfS.Nsd9OIG6P8bxdraXQMCel/fmJnX/pr.', 1, 1, 'Active');

-- Insert Countries
INSERT INTO `countries` (`id`, `country_name`, `iso_code`, `country_code`, `currency`, `customs_required`, `restricted_items`, `status`) VALUES
(1, 'India', 'IND', '91', 'INR', 0, 'Liquids, Matches, Batteries', 'Active'),
(2, 'United States', 'USA', '1', 'USD', 1, 'Liquids, Chemicals, Weapons, Seeds', 'Active'),
(3, 'United Kingdom', 'GBR', '44', 'GBP', 1, 'Liquids, Medicines, Plants', 'Active'),
(4, 'Canada', 'CAN', '1', 'CAD', 1, 'Chemicals, Batteries, Tobacco', 'Active'),
(5, 'Germany', 'DEU', '49', 'EUR', 1, 'Weapons, Liquids, Chemicals', 'Active'),
(6, 'United Arab Emirates', 'ARE', '971', 'AED', 1, 'Tobacco, Weapons, Medicines', 'Active');

-- Insert Courier Partners
INSERT INTO `courier_partners` (`id`, `partner_name`, `api_credentials`, `tracking_url`, `service_types`, `status`) VALUES
(1, 'DHL Express', '{\"api_key\":\"dhl_test_key_123\",\"account_no\":\"DHL9988\"}', 'https://www.dhl.com/en/express/tracking.html?AWB=', 'Express,Economy', 'Active'),
(2, 'FedEx', '{\"api_key\":\"fedex_test_key_abc\",\"account_no\":\"FDX1122\"}', 'https://www.fedex.com/apps/fedextrack/?tracknumbers=', 'Priority,Standard', 'Active'),
(3, 'UPS', '{\"api_key\":\"ups_test_key_999\",\"account_no\":\"UPS5566\"}', 'https://www.ups.com/track?loc=en_US&requester=ST/trackdetails', 'Express,Saver', 'Active'),
(4, 'Aramex', '{\"api_key\":\"aramex_test_key_xxx\",\"account_no\":\"ARX7788\"}', 'https://www.aramex.com/track/results?shipmentNumber=', 'Priority,Value', 'Active');

-- Insert Sample Rates (Origin: India (1) to USA (2), UK (3))
INSERT INTO `rate_master` (`origin_country_id`, `destination_country_id`, `service_type`, `weight_slab_start`, `weight_slab_end`, `base_rate`, `fuel_surcharge`, `handling_charges`, `insurance_charges`) VALUES
-- IND to USA rates
(1, 2, 'Express', 0.000, 0.500, 1500.00, 12.50, 150.00, 50.00),
(1, 2, 'Express', 0.501, 1.000, 2200.00, 12.50, 150.00, 50.00),
(1, 2, 'Express', 1.001, 2.000, 3500.00, 12.50, 200.00, 75.00),
(1, 2, 'Express', 2.001, 5.000, 7000.00, 15.00, 250.00, 100.00),
-- IND to UK rates
(1, 3, 'Express', 0.000, 0.500, 1200.00, 10.00, 100.00, 40.00),
(1, 3, 'Express', 0.501, 1.000, 1800.00, 10.00, 100.00, 40.00),
(1, 3, 'Express', 1.001, 2.000, 2900.00, 10.00, 150.00, 60.00),
(1, 3, 'Express', 2.001, 5.000, 5800.00, 12.00, 200.00, 80.00);

-- Insert Default T&C version
INSERT INTO `terms_conditions_master` (`id`, `title`, `version_number`, `effective_date`, `terms_content`, `status`) VALUES
(1, 'Standard Shipping Terms and Conditions v1.0', '1.0', '2026-01-01', '<h4>1. Introduction</h4><p>These terms govern all international carriage shipments booked through our company network.</p><h4>2. Customer Verification and Digital Signature</h4><p>The customer agrees that digital signatures executed through the canvas visual interface constitute binding consent to all shipping contents, customs lists, and rate structures.</p><h4>3. Customs Declarations</h4><p>The exporter/sender warrants that all shipment values, weights, and HS codes correspond to actual package contents. The exporter shall bear all penalties arising from incorrect customs statements.</p><h4>4. Restricted Items</h4><p>Dangerous goods, liquids, tobacco, weapons, uncertified medical products, and lithium batteries are strictly prohibited from transit.</p>', 'Published');

SET FOREIGN_KEY_CHECKS = 1;
