-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 31, 2023 at 03:15 PM
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
-- Table structure for table `sign_applications`
--

CREATE TABLE `sign_applications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `menu_group_id` int(11) NOT NULL COMMENT 'Ref-Table: menu_modules.menu_group_id',
  `menu_module_id` int(11) NOT NULL COMMENT 'Ref-Table: menu_modules.id',
  `menu_sub_id` int(11) NULL COMMENT 'Ref-Table: menu_sub_modules.id',
  `var_id` int(11) NOT NULL COMMENT 'Ref-Table: sign_variables.id',
  `var_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ref-Table: sign_variables.var_name',
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `remarks` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Remarks',
  `created_by` int(11) DEFAULT 0,
  `updated_by` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sign_applications`
--

INSERT INTO `sign_applications` (`id`, `menu_group_id`, `menu_module_id`, `menu_sub_id`, `var_id`, `var_name`, `status`, `remarks`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 17, 77, 55, 1, 'test1', 1, 'test', 1, 1, '2023-10-31 15:57:37', '2023-10-31 16:19:25'),
(2, 17, 77, 63, 2, 'test2', 1, 'ttt', 1, 1, '2023-10-31 16:20:55', '2023-10-31 16:20:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sign_applications`
--
ALTER TABLE `sign_applications`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sign_applications`
--
ALTER TABLE `sign_applications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;



-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 31, 2023 at 03:15 PM
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
-- Table structure for table `sign_variables`
--

CREATE TABLE `sign_variables` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `var_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sign_variables`
--

INSERT INTO `sign_variables` (`id`, `var_name`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'test1', 1, 0, '2023-10-31 12:24:40', NULL),
(2, 'test2', 1, 0, '2023-10-31 12:24:44', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sign_variables`
--
ALTER TABLE `sign_variables`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sign_variables`
--
ALTER TABLE `sign_variables`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
