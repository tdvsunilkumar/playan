-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 16, 2023 at 05:50 AM
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
-- Table structure for table `cpdo_development_inspection_reports`
--

CREATE TABLE `cpdo_development_inspection_reports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cir_date` date NOT NULL COMMENT 'Format: [yyyy-mm-dd].',
  `caf_id` int(11) NOT NULL COMMENT 'ref-Table : cpdo_application_form.caf_id',
  `cir_zoning_class` varchar(255) NOT NULL COMMENT 'Residencial/Commercial/Institutional',
  `cir_use_res` varchar(255) NOT NULL COMMENT 'A.1 Existing land Use',
  `cit_id` int(11) NOT NULL COMMENT 'ref-table: cpdo_inspection_terrain. Cit_id Required at least 1 or If others will be choosing other input text will be visable',
  `citother` varchar(255) DEFAULT NULL,
  `cir_north` varchar(255) NOT NULL COMMENT 'North',
  `cir_south` varchar(255) NOT NULL COMMENT 'South',
  `cir_east` varchar(255) NOT NULL COMMENT 'East',
  `cir_west` varchar(255) NOT NULL COMMENT 'West',
  `cir_long_we_degree` int(11) NOT NULL,
  `cir_long_we_minutes` int(11) NOT NULL,
  `cir_long_we_seconds` int(11) NOT NULL,
  `cir_lat_ns_degree` int(11) NOT NULL,
  `cir_lat_ns_minutes` int(11) NOT NULL,
  `cir_lat_ns_seconds` int(11) NOT NULL,
  `cir_long` varchar(255) NOT NULL COMMENT 'Long',
  `cir_lat` varchar(255) NOT NULL COMMENT 'Lat',
  `cir_water_supply` varchar(255) NOT NULL COMMENT 'Water Supply',
  `cir_decs` text NOT NULL,
  `cir_power_supply` varchar(255) NOT NULL COMMENT 'Power Supply',
  `cir_drainage` varchar(255) NOT NULL COMMENT 'Drainage',
  `cir_other` varchar(255) DEFAULT NULL COMMENT 'Other (specify)',
  `cir_remark` varchar(255) DEFAULT NULL COMMENT 'remark',
  `cir_approved_by` varchar(255) NOT NULL COMMENT 'Approved By',
  `cir_noted_by` int(11) DEFAULT NULL,
  `cir_approved_date` date NOT NULL COMMENT 'date',
  `cir_isapprove` int(11) NOT NULL DEFAULT 0,
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cpdo_development_inspection_reports`
--

INSERT INTO `cpdo_development_inspection_reports` (`id`, `cir_date`, `caf_id`, `cir_zoning_class`, `cir_use_res`, `cit_id`, `citother`, `cir_north`, `cir_south`, `cir_east`, `cir_west`, `cir_long_we_degree`, `cir_long_we_minutes`, `cir_long_we_seconds`, `cir_lat_ns_degree`, `cir_lat_ns_minutes`, `cir_lat_ns_seconds`, `cir_long`, `cir_lat`, `cir_water_supply`, `cir_decs`, `cir_power_supply`, `cir_drainage`, `cir_other`, `cir_remark`, `cir_approved_by`, `cir_noted_by`, `cir_approved_date`, `cir_isapprove`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(5, '2023-05-13', 7, 'residencial', 'asa', 3, NULL, '123', '123', '123', '123', 0, 0, 0, 0, 0, 0, '123', '32', 'eefew', 'qwer', 'ewfwe', 'fwe', 'wer', 'werw', '2', 5, '2023-05-15', 1, 1, 1, '2023-05-13 12:53:11', '2023-05-13 12:53:11'),
(6, '2023-05-15', 8, 'Commercial', 'Final', 3, NULL, '123.122', '12311', '1212', '1212', 0, 0, 0, 0, 0, 0, '1212.123', '12121', '2', 'asdjasdn', 'eqwe', 'adas', 'asdas', 'asdasd', '5', 36, '2023-05-18', 1, 1, 1, '2023-05-15 17:19:43', '2023-05-15 17:19:43'),
(7, '2023-05-16', 9, 'residencial', 'asa', 1, NULL, '123', 'wrew', '123', '123', 0, 0, 0, 0, 0, 0, '123', 'ewr', 'eefew', 'qwer', 'sdfsdf', 'wre', 'sdfsdf', 'werw', '5', 7, '2023-05-17', 1, 1, 1, '2023-05-16 14:23:06', '2023-05-16 14:23:06'),
(8, '2023-05-18', 10, 'residencial', 'asa', 1, NULL, '123', '123', '123', '123', 0, 0, 0, 0, 0, 0, '123', '32', 'sfsdfs', 'qwer', 'ewrfwr', 'sdfdsf', 'ewr', 'werw', '1', 12, '2023-05-19', 1, 1, 1, '2023-05-18 09:45:54', '2023-05-18 09:45:54'),
(9, '2023-05-22', 11, 'gdgdfdf', 'N', 3, NULL, 'N', 'S', 'E', 'W', 0, 0, 0, 0, 0, 0, 'Lo', '500', '500', 'By waya Sanali', '2000', '5000', '6000', 'Test Remarks', '2', 3, '2023-05-16', 1, 1, 1, '2023-05-22 11:47:21', '2023-05-22 11:55:03'),
(10, '2023-06-01', 13, 'residencial', 'asa', 3, NULL, '123', '123', '123', '123', 0, 0, 0, 0, 0, 0, '123', '32', 'eefew', 'adsfewf', 'ewfwe', 'fwe', 'wer', 'werw', '1', 36, '2023-06-02', 1, 2, 2, '2023-06-01 09:23:53', '2023-06-01 09:23:53'),
(11, '2023-06-01', 12, 'Commercial', 'rtte', 2, NULL, '123', '123', '123', '123', 0, 0, 0, 0, 0, 0, '123', '32', 'eefew', 'qwer', 'ewfwe', 'wre', 'sdfsdf', 'grkjn f', '22', 7, '2023-06-21', 0, 1, 1, '2023-06-01 11:19:04', '2023-06-01 11:19:04'),
(12, '2023-06-01', 14, 'IBC', 'N', 3, NULL, 'N', 'S', 'E', 'W', 0, 0, 0, 0, 0, 0, '40', '3000', '3000', 'Waya Borivali', '5000', '7000', '10000', 'Hard Strata', '2', 3, '2023-06-01', 0, 1, 1, '2023-06-01 19:04:50', '2023-06-01 19:23:02'),
(13, '2023-06-20', 15, 'Test Zonning', '650', 1, NULL, 'N', 'S', 'E', 'W', 0, 0, 0, 0, 0, 0, '13', '40', '1000', 'Mira Road', '1000', '1000', '2000', 'Test', '2', 3, '0000-00-00', 0, 1, 1, '2023-06-20 18:37:30', '2023-06-20 18:37:30');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cpdo_development_inspection_reports`
--
ALTER TABLE `cpdo_development_inspection_reports`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cpdo_development_inspection_reports`
--
ALTER TABLE `cpdo_development_inspection_reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
