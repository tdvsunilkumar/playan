-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 26, 2023 at 11:10 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.28

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
-- Table structure for table `hr_pagibig_table`
--

CREATE TABLE `hr_pagibig_table` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hrpit_description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hrpit_amount_from` double(10,2) NOT NULL,
  `hrpit_amount_to` double(10,2) NOT NULL,
  `hrpit_percentage` double(10,2) NOT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hr_pagibig_table`
--

INSERT INTO `hr_pagibig_table` (`id`, `hrpit_description`, `hrpit_amount_from`, `hrpit_amount_to`, `hrpit_percentage`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'sdcsd', 1111.00, 1111.00, 1111.00, 1, 1, '2023-07-26 09:08:26', '2023-07-26 09:09:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `hr_pagibig_table`
--
ALTER TABLE `hr_pagibig_table`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `hr_pagibig_table`
--
ALTER TABLE `hr_pagibig_table`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
