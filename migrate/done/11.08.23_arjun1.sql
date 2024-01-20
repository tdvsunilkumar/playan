-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 12, 2023 at 08:43 AM
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
-- Table structure for table `ho_req_per_details`
--

CREATE TABLE `ho_req_per_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `req_permit_id` int(11) NOT NULL COMMENT 'ref-table:ho_request_permit.id',
  `requestor_id` int(11) NOT NULL DEFAULT 0 COMMENT 'ref-table:citizens.id',
  `service_id` int(11) NOT NULL DEFAULT 0 COMMENT 'ref-table:ho_service.id',
  `tfoc_id` int(11) NOT NULL DEFAULT 0 COMMENT 'ref-table:cto_tfoc.id',
  `agl_account_id` int(11) NOT NULL DEFAULT 0 COMMENT 'ref-Table:cto_tfoc.agl_account_id',
  `sl_id` int(11) NOT NULL DEFAULT 0 COMMENT 'ref-Table:cto_tfoc.sl_id',
  `permit_fee` double(8,3) UNSIGNED NOT NULL DEFAULT 0.000 COMMENT 'ref-form:Fee',
  `is_free` int(11) NOT NULL DEFAULT 0 COMMENT '0=not free,1=free',
  `status` int(11) NOT NULL DEFAULT 0 COMMENT '0=inactive,1=active',
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ho_req_per_details`
--
ALTER TABLE `ho_req_per_details`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ho_req_per_details`
--
ALTER TABLE `ho_req_per_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
