-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 30, 2023 at 04:15 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.0.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `palayan`
--

-- --------------------------------------------------------

--
-- Table structure for table `cto_tfoc_other_taxes`
--

CREATE TABLE `cto_tfoc_other_taxes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tfoc_id` int(11) NOT NULL DEFAULT 0 COMMENT 'Ref-Table: cto_tfocs.id',
  `otaxes_gl_id` int(11) NOT NULL DEFAULT 0,
  `otaxes_sl_id` int(11) NOT NULL DEFAULT 0,
  `otaxes_percent` double(8,2) NOT NULL DEFAULT 0.00,
  `tfoc_is_applicable` int(11) NOT NULL DEFAULT 0,
  `otaxes_status` int(11) NOT NULL DEFAULT 0,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cto_tfoc_other_taxes`
--
ALTER TABLE `cto_tfoc_other_taxes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cto_tfoc_other_taxes`
--
ALTER TABLE `cto_tfoc_other_taxes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;



-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 30, 2023 at 04:18 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.0.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `palayan`
--

-- --------------------------------------------------------

--
-- Table structure for table `cto_cashier_details_eng_occupancy`
--

CREATE TABLE `cto_cashier_details_eng_occupancy` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cashier_year` int(11) NOT NULL COMMENT 'Current Year',
  `cashier_month` int(11) NOT NULL COMMENT 'month',
  `cashier_id` int(11) NOT NULL DEFAULT 0 COMMENT 'Ref-Table: cto_cashier.id',
  `cashierd_id` int(11) NOT NULL DEFAULT 0 COMMENT 'Ref-Table: cto_cashier_detail.id',
  `top_transaction_id` int(11) NOT NULL DEFAULT 0 COMMENT 'Ref-Table: cto_top_transactions.id',
  `tfoc_is_applicable` int(11) NOT NULL COMMENT 'Make a default in saving of details in the system. 1=Business Permit, 2=Real Property, 3=Engineering, 4=Occupancy,5=Planning & Devt., 6=Health & Safety, 7=Community Tax, 8=Burial Permit, 9=Miscellaneous',
  `tcoc_id` int(11) NOT NULL DEFAULT 0 COMMENT 'Ref-Table:eng_job_request.tfoc_id',
  `agl_account_id` int(11) NOT NULL DEFAULT 0 COMMENT 'ref-Table:eng_job_request.agl_account_id',
  `sl_id` int(11) NOT NULL DEFAULT 0 COMMENT 'ref-Table:eng_job_request.sl_id',
  `fees_description` varchar(255) NOT NULL COMMENT 'Fee Description',
  `tfc_amount` varchar(255) NOT NULL COMMENT 'Amount',
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cto_cashier_details_eng_occupancy`
--
ALTER TABLE `cto_cashier_details_eng_occupancy`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cto_cashier_details_eng_occupancy`
--
ALTER TABLE `cto_cashier_details_eng_occupancy`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


ALTER TABLE `eng_job_request_fees_details` ADD `is_default` INT(11) NOT NULL DEFAULT '0' AFTER `fees_description`;

ALTER TABLE `eng_occupancy_fees_details` ADD `is_default` INT(11) NOT NULL DEFAULT '0' COMMENT '0=\'default\',1=\'no default\'' AFTER `tax_amount`;