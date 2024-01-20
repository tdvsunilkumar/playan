-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 18, 2023 at 11:15 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 7.4.33

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
-- Table structure for table `ho_request_permit`
--

CREATE TABLE `ho_request_permit` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `requestor_id` int(11) NOT NULL DEFAULT 0 COMMENT 'ref-table:citizens.id get fullname',
  `brgy_id` varchar(11) NOT NULL DEFAULT '0' COMMENT 'ref-table:barangays.id get brgy_name, mun_desc',
  `request_date` date NOT NULL,
  `control_no` varchar(20) NOT NULL COMMENT 'format [year-0001] 2023-0001 incremental and resets every year',
  `request_amount` double(8,3) UNSIGNED NOT NULL DEFAULT 0.000,
  `top_transaction_no` int(11) DEFAULT 0 COMMENT 'ref-table:cto_top_transactions. id...... use top_transaction_no',
  `trans_id` int(11) DEFAULT 0 COMMENT 'ref-table:cto_top_transactions. id',
  `cashierd_id` int(11) DEFAULT NULL COMMENT 'Ref-Table: cto_cashier_details.id',
  `cashier_id` int(11) DEFAULT NULL COMMENT 'Ref-Table:cto_cashier.id',
  `or_no` varchar(100) DEFAULT NULL COMMENT 'Ref-Table: cto_cashier.or_no',
  `or_date` date DEFAULT NULL COMMENT 'Ref-Table: cto_cashier.cashier_or_date',
  `or_amount` double(8,3) UNSIGNED DEFAULT NULL COMMENT 'Ref-Table: cto_cashier_details.tfc_amount',
  `is_free` int(11) NOT NULL DEFAULT 0 COMMENT '0 = not free, 1 = free',
  `is_posted` int(11) NOT NULL DEFAULT 0 COMMENT '0 = saved, 1 = posted',
  `status` int(11) NOT NULL DEFAULT 0 COMMENT '0 = inactive, 1 = active',
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ho_request_permit`
--

INSERT INTO `ho_request_permit` (`id`, `requestor_id`, `brgy_id`, `request_date`, `control_no`, `request_amount`, `top_transaction_no`, `trans_id`, `cashierd_id`, `cashier_id`, `or_no`, `or_date`, `or_amount`, `is_free`, `is_posted`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 39, 'Adams(Pobla', '2023-08-18', '2023-000001', 150.000, 197, 197, NULL, NULL, NULL, NULL, NULL, 1, 1, 1, 1, 1, '2023-08-18 09:39:51', '2023-08-18 09:40:05');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ho_request_permit`
--
ALTER TABLE `ho_request_permit`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ho_request_permit`
--
ALTER TABLE `ho_request_permit`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
