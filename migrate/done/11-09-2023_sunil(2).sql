-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 11, 2023 at 05:38 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `demo`
--

-- --------------------------------------------------------

--
-- Table structure for table `cto_accounts_receivable_details`
--

CREATE TABLE `cto_accounts_receivable_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ar_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'Ref-Table: cto_accounts_receivables.id',
  `top_transaction_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'Ref-Table: cto_top_transactions.id',
  `payee_type` tinyint(4) DEFAULT NULL COMMENT '1=Client(table:clients), 2=Citizen(table:citizens)',
  `taxpayer_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'Taxpayers and Citizen ID reference number',
  `taxpayer_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Taxpayer Name',
  `pcs_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'Ref-Table: cto_payment_cashier_system.id',
  `rp_property_code` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'Ref-Table: rp_properties.rp_property_code',
  `rp_code` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'Ref-Table: rp_properties.id. the original rp_code',
  `pk_id` int(11) DEFAULT NULL COMMENT 'Ref-Table: rpt_property_kinds.id',
  `ar_covered_year` int(11) DEFAULT NULL,
  `sd_mode` int(11) DEFAULT NULL,
  `rp_app_effective_year` varchar(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ref-Table: rpt_properties.rp_app_effectivity_year',
  `rp_assessed_value` decimal(20,3) DEFAULT NULL COMMENT 'Total Assessed Values for both the Land, Building & Properties',
  `rvy_revision_year_id` int(11) DEFAULT NULL COMMENT 'Ref-Table: rpt_revision_year.id',
  `brgy_code_id` int(11) DEFAULT NULL COMMENT 'Ref-Table: barangays.id',
  `trevs_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'Ref-Table: rpt_cto_billing_details.trevs_id',
  `tax_revenue_year` int(11) NOT NULL COMMENT '1=Upcoming Year, 2=Current Year, 3= Previous Years',
  `rp_billing_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'Ref-Table: rpt_cto_billing.id... always the current billing ID until its full paid',
  `transaction_id` int(11) DEFAULT NULL COMMENT 'Ref-Table: cto_top_transactions.id .. when the Tax Order Of Payment has been created by the City Treasurers Office.',
  `transaction_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ref-Table: cto_top_transactions.transaction_no .. when the Tax Order Of Payment has been created by the City Treasurer Office.',
  `cbd_is_paid` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'if its paid or not',
  `basic_tfoc_id` bigint(20) UNSIGNED NOT NULL COMMENT 'Basic: Tax, Fees & Other Charges... Ref-Table: cto_tfocs.id',
  `basic_gl_id` bigint(20) UNSIGNED NOT NULL COMMENT 'Ref-Table: cto_tfocs.agl_account_id',
  `basic_sl_id` bigint(20) UNSIGNED NOT NULL COMMENT 'Ref-Table: cto_tfocs.sl_id',
  `basic_amount` decimal(20,3) NOT NULL,
  `basic_discount_tfoc_id` bigint(20) UNSIGNED NOT NULL COMMENT 'Discount: Tax, Fees & Other Charges... Ref-Table: cto_tfocs.id',
  `basic_discount_gl_id` bigint(20) UNSIGNED NOT NULL COMMENT 'Ref-Table: cto_tfocs.agl_account_id',
  `basic_discount_sl_id` bigint(20) UNSIGNED NOT NULL COMMENT 'Ref-Table: cto_tfocs.sl_id',
  `basic_discount_amount` decimal(20,3) NOT NULL,
  `basic_penalty_tfoc_id` bigint(20) UNSIGNED NOT NULL COMMENT 'Interest/Penalty: Tax, Fees & Other Charges... Ref-Table: cto_tfocs.id',
  `basic_penalty_gl_id` bigint(20) UNSIGNED NOT NULL COMMENT 'Ref-Table: cto_tfocs.agl_account_id',
  `basic_penalty_sl_id` bigint(20) UNSIGNED NOT NULL COMMENT 'Ref-Table: cto_tfocs.sl_id',
  `basic_penalty_amount` decimal(20,3) NOT NULL,
  `sef_amount` decimal(20,3) NOT NULL,
  `sef_tfoc_id` bigint(20) UNSIGNED NOT NULL COMMENT 'SEF: Tax, Fees & Other Charges... Ref-Table: cto_tfocs.id',
  `sef_gl_id` bigint(20) UNSIGNED NOT NULL COMMENT 'Ref-Table: cto_tfocs.agl_account_id',
  `sef_sl_id` bigint(20) UNSIGNED NOT NULL COMMENT 'Ref-Table: cto_tfocs.sl_id',
  `sef_discount_tfoc_id` bigint(20) UNSIGNED NOT NULL COMMENT 'SEF Discount: Tax, Fees & Other Charges... Ref-Table: cto_tfocs.id',
  `sef_discount_gl_id` bigint(20) UNSIGNED NOT NULL COMMENT 'Ref-Table: cto_tfocs.agl_account_id',
  `sef_discount_sl_id` bigint(20) UNSIGNED NOT NULL COMMENT 'Ref-Table: cto_tfocs.sl_id',
  `sef_discount_amount` decimal(20,3) NOT NULL,
  `sef_penalty_tfoc_id` bigint(20) UNSIGNED NOT NULL COMMENT 'SEF Interest/Penalty: Tax, Fees & Other Charges... Ref-Table: cto_tfocs.id',
  `sef_penalty_gl_id` bigint(20) UNSIGNED NOT NULL COMMENT 'Ref-Table: cto_tfocs.agl_account_id',
  `sef_penalty_sl_id` bigint(20) UNSIGNED NOT NULL COMMENT 'Ref-Table: cto_tfocs.sl_id',
  `sef_penalty_amount` decimal(20,3) NOT NULL,
  `sh_amount` decimal(20,3) NOT NULL,
  `sh_tfoc_id` bigint(20) UNSIGNED NOT NULL COMMENT 'SEF: Tax, Fees & Other Charges... Ref-Table: cto_tfocs.id',
  `sh_gl_id` bigint(20) UNSIGNED NOT NULL COMMENT 'Ref-Table: cto_tfocs.agl_account_id',
  `sh_sl_id` bigint(20) UNSIGNED NOT NULL COMMENT 'Ref-Table: cto_tfocs.sl_id',
  `sh_discount_tfoc_id` bigint(20) UNSIGNED NOT NULL COMMENT 'SH Discount: Tax, Fees & Other Charges... Ref-Table: cto_tfocs.id',
  `sh_discount_gl_id` bigint(20) UNSIGNED NOT NULL COMMENT 'Ref-Table: cto_tfocs.agl_account_id',
  `sh_discount_sl_id` bigint(20) UNSIGNED NOT NULL COMMENT 'Ref-Table: cto_tfocs.sl_id',
  `sh_discount_amount` decimal(20,3) NOT NULL,
  `sh_penalty_tfoc_id` bigint(20) UNSIGNED NOT NULL COMMENT 'SH Interest/Penalty: Tax, Fees & Other Charges... Ref-Table: cto_tfocs.id',
  `sh_penalty_gl_id` bigint(20) UNSIGNED NOT NULL COMMENT 'Ref-Table: cto_tfocs.agl_account_id',
  `sh_penalty_sl_id` bigint(20) UNSIGNED NOT NULL COMMENT 'Ref-Table: cto_tfocs.sl_id',
  `sh_penalty_amount` decimal(20,3) NOT NULL,
  `cashier_id` int(11) DEFAULT NULL COMMENT 'Ref-Table: cto_cashier',
  `ortype_id` int(11) DEFAULT NULL COMMENT 'Ref-Table: cto_payment_or_types.id',
  `or_assignment_id` int(11) DEFAULT NULL COMMENT 'Ref-Table: cto_payment_or_assignments.id',
  `or_register_id` int(11) DEFAULT NULL COMMENT 'Ref-Table: cto_payment_or_registers.id',
  `coa_no` int(11) DEFAULT NULL COMMENT 'Ref-Table: cto_payment_or_registers.coa_no',
  `or_no` int(11) DEFAULT NULL COMMENT 'Ref-Table: cto_payment_or_registers.coa_no',
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'when the amounts(like rp_basic_amount, rp_sef_amount and rp_sht_amount) already set to ZERO',
  `created_by` int(11) DEFAULT NULL COMMENT 'reference hr_employee_id of the system who registered the details',
  `updated_by` int(11) DEFAULT NULL COMMENT 'reference hr_employee_id of the system who registered the details',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cto_accounts_receivable_details`
--
ALTER TABLE `cto_accounts_receivable_details`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cto_accounts_receivable_details`
--
ALTER TABLE `cto_accounts_receivable_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
