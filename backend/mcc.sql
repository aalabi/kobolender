-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 03, 2022 at 03:29 PM
-- Server version: 5.7.26
-- PHP Version: 7.3.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mcc`
--

-- --------------------------------------------------------

--
-- Table structure for table `blog_category`
--

DROP TABLE IF EXISTS `blog_category`;
CREATE TABLE IF NOT EXISTS `blog_category` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `created_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `blog_category`
--

INSERT INTO `blog_category` (`id`, `name`, `description`, `created_date`, `updated_date`) VALUES
(6, 'News', '', '2022-03-20 16:40:01', '2022-03-20 16:40:01');

-- --------------------------------------------------------

--
-- Table structure for table `blog_comment`
--

DROP TABLE IF EXISTS `blog_comment`;
CREATE TABLE IF NOT EXISTS `blog_comment` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `blog_post` bigint(20) UNSIGNED NOT NULL,
  `comment` text NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `display` enum('yes','no') NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `blog_post` (`blog_post`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `blog_post`
--

DROP TABLE IF EXISTS `blog_post`;
CREATE TABLE IF NOT EXISTS `blog_post` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` text,
  `content` longtext,
  `image` varchar(255) NOT NULL COMMENT 'filename of blog image',
  `blog_category` bigint(20) UNSIGNED NOT NULL,
  `poster_id` bigint(20) UNSIGNED NOT NULL,
  `display` enum('yes','no') NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `blog_category` (`blog_category`),
  KEY `poster_id` (`poster_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `blog_post`
--

INSERT INTO `blog_post` (`id`, `title`, `content`, `image`, `blog_category`, `poster_id`, `display`, `created_at`, `update_at`) VALUES
(6, 'MC & C MICRO SUPPORT LIMITED PARTNERS COGNETIK TECHNOLOGIES', 'MC & C Micro Support Limited has partnered a Software Development and IT consultancy firm Cognetik technologies to build its website (www.kobolender.com) and incorporate a loan application portal to aid customers in applying for loans from the comfort of their homes and offices. This partnership is meant to build the base technology upon which MC & C Micro Support Limited will trade.', '11647794448jpg', 6, 1, 'yes', '2022-03-20 16:40:48', '2022-03-20 16:40:48');

-- --------------------------------------------------------

--
-- Table structure for table `gateway_log`
--

DROP TABLE IF EXISTS `gateway_log`;
CREATE TABLE IF NOT EXISTS `gateway_log` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `loan_id` bigint(20) UNSIGNED NOT NULL,
  `tx_ref` varchar(255) NOT NULL,
  `amount` bigint(20) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `profile_id` (`loan_id`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gateway_log`
--

INSERT INTO `gateway_log` (`id`, `loan_id`, `tx_ref`, `amount`, `status`, `updated_at`, `created_at`) VALUES
(96, 20, 'D0000085201647639388', NULL, NULL, '2022-03-18 21:36:28', '2022-03-18 21:36:28'),
(97, 20, 'D0000085201648543757', 500000, 'successful', '2022-03-29 09:15:34', '2022-03-29 08:49:17'),
(98, 20, 'D0000085201648545334', NULL, NULL, '2022-03-29 09:15:34', '2022-03-29 09:15:34'),
(99, 20, 'D0000085201648551667', NULL, NULL, '2022-03-29 11:01:07', '2022-03-29 11:01:07');

-- --------------------------------------------------------

--
-- Table structure for table `guarantor`
--

DROP TABLE IF EXISTS `guarantor`;
CREATE TABLE IF NOT EXISTS `guarantor` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `profile_id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `letter` varchar(255) DEFAULT NULL COMMENT 'file of guarantor letter',
  `bank_statment` varchar(255) DEFAULT NULL COMMENT 'file of bank statment',
  `id_card` varchar(255) DEFAULT NULL COMMENT 'file of guarantor id card',
  `id_card_type` varchar(255) DEFAULT NULL,
  `id_card_no` varchar(255) DEFAULT NULL,
  `type` enum('guarantor','promoter') DEFAULT 'guarantor',
  `other_info` longtext,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `guarantor_profile_id_foreign` (`profile_id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `guarantor`
--

INSERT INTO `guarantor` (`id`, `profile_id`, `email`, `phone`, `letter`, `bank_statment`, `id_card`, `id_card_type`, `id_card_no`, `type`, `other_info`, `created_at`, `updated_at`) VALUES
(25, 86, 'akinyemisamuelolaoluwa4glory@gmail.com', '08165620007', 'gl50.pdf', NULL, 'gID50.png', NULL, NULL, 'guarantor', NULL, '2022-03-18 13:27:20', '2022-03-18 13:27:20'),
(26, 88, 'hector@gmail.com', '08037057616', 'gl51.docx', NULL, 'gID51.jpg', NULL, NULL, 'guarantor', NULL, '2022-03-26 13:41:47', '2022-03-26 13:41:47');

-- --------------------------------------------------------

--
-- Table structure for table `individual`
--

DROP TABLE IF EXISTS `individual`;
CREATE TABLE IF NOT EXISTS `individual` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `profile_id` bigint(20) UNSIGNED NOT NULL,
  `guarantor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `company_address` text,
  `employment_letter` varchar(255) DEFAULT NULL COMMENT 'filename of employment letter',
  `pay_slip` varchar(255) DEFAULT NULL COMMENT 'filename of pay slip',
  `id_card` varchar(255) DEFAULT NULL,
  `id_card_doc` varchar(255) DEFAULT NULL COMMENT 'filename of id card',
  `id_card_expiry_date` varchar(255) DEFAULT NULL,
  `bvn` varchar(255) DEFAULT NULL,
  `bank_details` json DEFAULT NULL COMMENT '{"accountNo":"123456789", "bank":"GTBank", "accountType":"saving"}',
  `other_info` longtext,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `individual_profile_id_foreign` (`profile_id`),
  KEY `guarantor` (`guarantor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `individual`
--

INSERT INTO `individual` (`id`, `profile_id`, `guarantor_id`, `type`, `dob`, `company`, `company_address`, `employment_letter`, `pay_slip`, `id_card`, `id_card_doc`, `id_card_expiry_date`, `bvn`, `bank_details`, `other_info`, `created_at`, `updated_at`) VALUES
(16, 85, 25, NULL, '1990-01-21', 'Cognetik Tech', 'Ikeja, Lagos', 'el50.pdf', 'ps50.pdf', 'International Passport', 'idcarddoc50.', '2025-05-18', '1234567890', '{\"bank\": \"Guaranty Trust Bank\", \"accountNo\": \"0140370646\", \"accountType\": \"Saving\"}', NULL, '2022-03-18 13:27:20', '2022-03-18 13:27:20'),
(17, 87, 26, NULL, '1966-12-01', 'Risk Advisors Associate', '95 Ikorodu Road, Palmgroove Lagos', 'el51.pdf', 'ps51.pdf', 'International Passport', 'idcarddoc51.', '2022-04-30', '2241967221', '{\"bank\": \"Union Bank\", \"accountNo\": \"0041118884\", \"accountType\": \"Current\"}', NULL, '2022-03-26 13:41:47', '2022-03-26 13:41:47');

-- --------------------------------------------------------

--
-- Table structure for table `loan`
--

DROP TABLE IF EXISTS `loan`;
CREATE TABLE IF NOT EXISTS `loan` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `profile_id` bigint(20) UNSIGNED NOT NULL,
  `product` enum('1','2','3','4','5','6','7','8','9','10') NOT NULL,
  `amount` decimal(16,2) NOT NULL,
  `repymt_source` varchar(255) DEFAULT NULL,
  `repayment_period` smallint(6) DEFAULT NULL,
  `purpose` text,
  `other_bank_own` varchar(255) DEFAULT NULL,
  `amt_own` decimal(16,2) DEFAULT NULL,
  `mthly_repymt` decimal(16,2) DEFAULT NULL,
  `direct_debit_amt` decimal(16,2) DEFAULT NULL,
  `outstand_obligation` decimal(16,2) DEFAULT NULL,
  `collateral_text` varchar(255) DEFAULT NULL,
  `collateral_file` varchar(255) DEFAULT NULL COMMENT 'filename of collateral uploaded',
  `approved_amount` decimal(16,2) DEFAULT NULL,
  `status` enum('applied','wip','approved','rejected','liquated') NOT NULL,
  `reject_reason` text,
  `staff_profile_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `staff_profile_id` (`staff_profile_id`),
  KEY `profile_id` (`profile_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `loan`
--

INSERT INTO `loan` (`id`, `profile_id`, `product`, `amount`, `repymt_source`, `repayment_period`, `purpose`, `other_bank_own`, `amt_own`, `mthly_repymt`, `direct_debit_amt`, `outstand_obligation`, `collateral_text`, `collateral_file`, `approved_amount`, `status`, `reject_reason`, `staff_profile_id`, `created_at`, `update_at`) VALUES
(20, 85, '6', '500000.00', 'salary', 5000, 'Schooling', 'nil', NULL, NULL, NULL, NULL, NULL, NULL, '500000.00', 'liquated', NULL, 1, '2022-03-18 13:27:20', '2022-03-29 09:15:34'),
(21, 87, '1', '1000000.00', 'salary', -6, 'pay personal bill', 'Providous bank', '2300000.00', '65000.00', NULL, NULL, NULL, NULL, NULL, 'applied', NULL, NULL, '2022-03-26 13:41:47', '2022-03-26 13:41:47');

-- --------------------------------------------------------

--
-- Table structure for table `loan_transaction`
--

DROP TABLE IF EXISTS `loan_transaction`;
CREATE TABLE IF NOT EXISTS `loan_transaction` (
  `id` int(10) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
  `loan_id` bigint(20) UNSIGNED NOT NULL,
  `poster_id` bigint(20) UNSIGNED NOT NULL,
  `description` varchar(255) NOT NULL,
  `amount` decimal(16,2) NOT NULL,
  `balance` decimal(16,2) NOT NULL,
  `type` enum('debit','credit') NOT NULL,
  `ticket` varchar(255) DEFAULT NULL,
  `transaction_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `loan_id` (`loan_id`),
  KEY `poster_id` (`poster_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `loan_transaction`
--

INSERT INTO `loan_transaction` (`id`, `loan_id`, `poster_id`, `description`, `amount`, `balance`, `type`, `ticket`, `transaction_date`, `created_at`, `updated_at`) VALUES
(0000000020, 20, 1, 'Loan booking', '500000.00', '-500000.00', 'debit', NULL, '2022-03-18 13:41:38', '2022-03-18 13:41:38', '2022-03-18 13:41:38'),
(0000000021, 20, 85, 'Loan Repayment 3255516', '500000.00', '0.00', 'credit', NULL, '2022-03-29 09:15:34', '2022-03-29 09:15:34', '2022-03-29 09:15:34');

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

DROP TABLE IF EXISTS `login`;
CREATE TABLE IF NOT EXISTS `login` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_time` datetime DEFAULT NULL,
  `activation_token` varchar(255) NOT NULL,
  `activation_token_time` datetime NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login_user_name_unique` (`username`),
  UNIQUE KEY `login_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`id`, `username`, `email`, `phone`, `password`, `reset_token`, `reset_token_time`, `activation_token`, `activation_token_time`, `status`, `created_at`, `update_at`) VALUES
(1, NULL, 'email@domain.com', NULL, '$2y$10$xeh6PtwXy62JoWJ1QQBA6O8x1zkxkkE7fdag37mqFnrwivjcqGIu.', NULL, NULL, 'qg9DOCyYlAwIBT0r', '2022-01-18 04:16:41', 'active', '2022-01-18 04:16:41', '2022-01-20 21:13:24'),
(49, NULL, 'akinyemisamuelolaoluwa4glory@gmail.com', NULL, '$2y$10$1UDMXnKZ7cbOT5i5HV2i0Oki2IQQGPFse9M2x97qvNEUZUQiyDMPO', NULL, NULL, 'DTHPq84RG2b7NEfx', '2022-03-18 13:18:02', 'active', '2022-03-18 13:18:02', '2022-03-18 13:18:02'),
(50, NULL, 'samtex0942@gmail.com', '08165620007', '$2y$10$vrM9YDC9y3mYK/2BWt2HeeACWer0FeM7HzVEjBysTC1aQe7u5EX5.', NULL, NULL, 'ln7p4vA2eg95ZXWi', '2022-03-18 13:27:20', 'active', '2022-03-18 13:27:20', '2022-03-18 13:27:20'),
(51, NULL, 'mcnnoka@yahoo.com', '07034114625', '$2y$10$rcIxp0f36LxSqT/bY.bw8uln9jmeB0gJVT6aN4PpS5OVyZOfORHrW', NULL, NULL, 'LJx5UI7gyb8jCrHl', '2022-03-26 13:41:47', 'active', '2022-03-26 13:41:47', '2022-03-26 13:41:47');

-- --------------------------------------------------------

--
-- Table structure for table `msme`
--

DROP TABLE IF EXISTS `msme`;
CREATE TABLE IF NOT EXISTS `msme` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `profile_id` bigint(20) UNSIGNED NOT NULL,
  `guarantor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `guarantor_id_others` json DEFAULT NULL,
  `tin` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `dob` date DEFAULT NULL COMMENT 'of this msme logger',
  `income_source_file` varchar(255) DEFAULT NULL COMMENT 'filename of source of income uploaded',
  `income_source_text` longtext COMMENT 'description of source of income',
  `id_card` varchar(255) DEFAULT NULL COMMENT 'filename of id card',
  `id_card_type` varchar(255) DEFAULT NULL,
  `collateral_text` longtext COMMENT 'description of collateral',
  `collateral_file` varchar(255) DEFAULT NULL COMMENT 'filename of collateral uploaded',
  `bank_details` json DEFAULT NULL COMMENT '{"accountName":"Tunde Ltd","accountNo":"123456789", "bank":"GTBank"}',
  `company` varchar(255) DEFAULT NULL,
  `company_address` text,
  `nature_business` varchar(255) DEFAULT NULL,
  `company_phone` varchar(255) DEFAULT NULL,
  `bank_statement` varchar(255) DEFAULT NULL COMMENT 'filename of bank statement',
  `bank_statement2` varchar(255) DEFAULT NULL COMMENT 'filename of bank statement2',
  `financial_statement` varchar(255) DEFAULT NULL COMMENT 'filename of financial statement',
  `company_cac_doc` varchar(255) DEFAULT NULL COMMENT 'filename of cac document',
  `other_info` longtext,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `msme_profile_id_foreign` (`profile_id`),
  KEY `guarantor_id` (`guarantor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `profile`
--

DROP TABLE IF EXISTS `profile`;
CREATE TABLE IF NOT EXISTS `profile` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `login_id` bigint(20) UNSIGNED DEFAULT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `middlename` varchar(255) DEFAULT NULL,
  `surname` varchar(255) DEFAULT NULL,
  `address` text,
  `user_type` bigint(20) UNSIGNED DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `profile_login_id_foreign` (`login_id`),
  KEY `profile_user_type_foreign` (`user_type`)
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `profile`
--

INSERT INTO `profile` (`id`, `login_id`, `firstname`, `middlename`, `surname`, `address`, `user_type`, `profile_image`, `created_at`, `updated_at`) VALUES
(1, 1, 'Buhari', 'A.', 'Jonathan', NULL, 1, 'default-profile.png', '2022-01-18 04:16:41', '2022-01-18 18:26:01'),
(84, 49, 'sam', 'ola', 'Akin', NULL, 1, 'default-profile.png', '2022-03-18 13:18:02', '2022-03-18 13:18:02'),
(85, 50, 'Samuel', 'Olaoluwa', 'Akinyemi', 'Ipaja, Lagos', 2, 'p50.png', '2022-03-18 13:27:20', '2022-03-18 13:27:20'),
(86, NULL, 'Tunde', 'Seun', 'Bakare', 'Ikoyi, Lagos', 4, 'pg50.png', '2022-03-18 13:27:20', '2022-03-18 13:27:20'),
(87, 51, 'Nonye', 'Magnus', 'Nnoka', '115 Kujore Street, Ojota, Lagos', 2, 'p51.jpg', '2022-03-26 13:41:47', '2022-03-26 13:41:47'),
(88, NULL, 'Hector', 'Olukoya', 'Muyiwa', '14 Amodu Ojukutu street victoria island', 4, 'pg51.JPG', '2022-03-26 13:41:47', '2022-03-26 13:41:47');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

DROP TABLE IF EXISTS `staff`;
CREATE TABLE IF NOT EXISTS `staff` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `profile_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('reviewer','approver') DEFAULT NULL,
  `other_info` longtext,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `staff_profile_id_foreign` (`profile_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `profile_id`, `type`, `other_info`, `created_at`, `updated_at`) VALUES
(1, 1, 'approver', NULL, '2022-01-18 04:16:41', '2022-01-30 09:04:50'),
(8, 84, 'reviewer', NULL, '2022-03-18 13:18:02', '2022-03-18 13:18:02');

-- --------------------------------------------------------

--
-- Table structure for table `user_type`
--

DROP TABLE IF EXISTS `user_type`;
CREATE TABLE IF NOT EXISTS `user_type` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type_name` varchar(255) NOT NULL,
  `external_name` varchar(255) NOT NULL,
  `table_name` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_type`
--

INSERT INTO `user_type` (`id`, `type_name`, `external_name`, `table_name`, `created_at`, `updated_at`) VALUES
(1, 'staff', 'staff', 'staff', '2022-01-18 04:16:40', '2022-01-18 04:16:40'),
(2, 'individual', 'individual', 'individual', '2022-01-18 04:16:40', '2022-01-18 04:16:40'),
(3, 'msme', 'msme', 'msme', '2022-01-18 04:16:40', '2022-01-18 04:16:40'),
(4, 'guarantor', 'guarantor', 'guarantor', '2022-01-18 04:16:40', '2022-01-18 04:16:40');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blog_comment`
--
ALTER TABLE `blog_comment`
  ADD CONSTRAINT `blog_comment_ibfk_1` FOREIGN KEY (`blog_post`) REFERENCES `blog_post` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `blog_post`
--
ALTER TABLE `blog_post`
  ADD CONSTRAINT `blog_post_ibfk_1` FOREIGN KEY (`blog_category`) REFERENCES `blog_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `blog_post_ibfk_2` FOREIGN KEY (`poster_id`) REFERENCES `profile` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `gateway_log`
--
ALTER TABLE `gateway_log`
  ADD CONSTRAINT `gateway_log_ibfk_1` FOREIGN KEY (`loan_id`) REFERENCES `loan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `guarantor`
--
ALTER TABLE `guarantor`
  ADD CONSTRAINT `guarantor_profile_id_foreign` FOREIGN KEY (`profile_id`) REFERENCES `profile` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `individual`
--
ALTER TABLE `individual`
  ADD CONSTRAINT `individual_ibfk_1` FOREIGN KEY (`guarantor_id`) REFERENCES `guarantor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `individual_profile_id_foreign` FOREIGN KEY (`profile_id`) REFERENCES `profile` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `loan`
--
ALTER TABLE `loan`
  ADD CONSTRAINT `loan_ibfk_1` FOREIGN KEY (`profile_id`) REFERENCES `profile` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `loan_transaction`
--
ALTER TABLE `loan_transaction`
  ADD CONSTRAINT `loan_transaction_ibfk_1` FOREIGN KEY (`loan_id`) REFERENCES `loan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `loan_transaction_ibfk_2` FOREIGN KEY (`poster_id`) REFERENCES `profile` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `msme`
--
ALTER TABLE `msme`
  ADD CONSTRAINT `msme_ibfk_1` FOREIGN KEY (`guarantor_id`) REFERENCES `guarantor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `msme_profile_id_foreign` FOREIGN KEY (`profile_id`) REFERENCES `profile` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `profile`
--
ALTER TABLE `profile`
  ADD CONSTRAINT `profile_login_id_foreign` FOREIGN KEY (`login_id`) REFERENCES `login` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `profile_user_type_foreign` FOREIGN KEY (`user_type`) REFERENCES `user_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `staff`
--
ALTER TABLE `staff`
  ADD CONSTRAINT `staff_profile_id_foreign` FOREIGN KEY (`profile_id`) REFERENCES `profile` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
