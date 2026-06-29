-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 29, 2026 at 04:00 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pos`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(10) UNSIGNED NOT NULL,
  `business_id` int(11) NOT NULL,
  `name` varchar(191) NOT NULL,
  `account_number` varchar(191) NOT NULL,
  `account_details` text DEFAULT NULL,
  `account_type_id` int(11) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `is_closed` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `account_transactions`
--

CREATE TABLE `account_transactions` (
  `id` int(10) UNSIGNED NOT NULL,
  `account_id` int(11) NOT NULL,
  `type` enum('debit','credit') NOT NULL,
  `sub_type` enum('opening_balance','fund_transfer','deposit') DEFAULT NULL,
  `amount` decimal(22,4) NOT NULL,
  `reff_no` varchar(191) DEFAULT NULL,
  `operation_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `transaction_id` int(11) DEFAULT NULL,
  `transaction_payment_id` int(11) DEFAULT NULL,
  `transfer_transaction_id` int(11) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `account_types`
--

CREATE TABLE `account_types` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `parent_account_type_id` int(11) DEFAULT NULL,
  `business_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `log_name` varchar(191) DEFAULT NULL,
  `description` text NOT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `subject_type` varchar(191) DEFAULT NULL,
  `event` varchar(191) DEFAULT NULL,
  `business_id` int(11) DEFAULT NULL,
  `causer_id` int(11) DEFAULT NULL,
  `causer_type` varchar(191) DEFAULT NULL,
  `properties` text DEFAULT NULL,
  `batch_uuid` char(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `event`, `business_id`, `causer_id`, `causer_type`, `properties`, `batch_uuid`, `created_at`, `updated_at`) VALUES
(1, 'default', 'login', 1, 'App\\User', NULL, 1, 1, 'App\\User', '[]', NULL, '2026-06-27 20:46:15', '2026-06-27 20:46:15'),
(2, 'default', 'login', 1, 'App\\User', NULL, 1, 1, 'App\\User', '[]', NULL, '2026-06-28 12:36:52', '2026-06-28 12:36:52'),
(3, 'default', 'logout', 1, 'App\\User', NULL, 1, 1, 'App\\User', '[]', NULL, '2026-06-28 12:59:59', '2026-06-28 12:59:59'),
(4, 'default', 'login', 1, 'App\\User', NULL, 1, 1, 'App\\User', '[]', NULL, '2026-06-28 13:00:12', '2026-06-28 13:00:12');

-- --------------------------------------------------------

--
-- Table structure for table `barcodes`
--

CREATE TABLE `barcodes` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `width` double(22,4) DEFAULT NULL,
  `height` double(22,4) DEFAULT NULL,
  `paper_width` double(22,4) DEFAULT NULL,
  `paper_height` double(22,4) DEFAULT NULL,
  `top_margin` double(22,4) DEFAULT NULL,
  `left_margin` double(22,4) DEFAULT NULL,
  `row_distance` double(22,4) DEFAULT NULL,
  `col_distance` double(22,4) DEFAULT NULL,
  `stickers_in_one_row` int(11) DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `is_continuous` tinyint(1) NOT NULL DEFAULT 0,
  `stickers_in_one_sheet` int(11) DEFAULT NULL,
  `business_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `barcodes`
--

INSERT INTO `barcodes` (`id`, `name`, `description`, `width`, `height`, `paper_width`, `paper_height`, `top_margin`, `left_margin`, `row_distance`, `col_distance`, `stickers_in_one_row`, `is_default`, `is_continuous`, `stickers_in_one_sheet`, `business_id`, `created_at`, `updated_at`) VALUES
(1, '20 Labels per Sheet', 'Sheet Size: 8.5\" x 11\", Label Size: 4\" x 1\", Labels per sheet: 20', 4.0000, 1.0000, 8.5000, 11.0000, 0.5000, 0.1250, 0.0000, 0.1875, 2, 0, 0, 20, NULL, '2017-12-18 00:13:44', '2017-12-18 00:13:44'),
(2, '30 Labels per sheet', 'Sheet Size: 8.5\" x 11\", Label Size: 2.625\" x 1\", Labels per sheet: 30', 2.6250, 1.0000, 8.5000, 11.0000, 0.5000, 0.1880, 0.0000, 0.1250, 3, 0, 0, 30, NULL, '2017-12-18 00:04:39', '2017-12-18 00:10:40'),
(3, '32 Labels per sheet', 'Sheet Size: 8.5\" x 11\", Label Size: 2\" x 1.25\", Labels per sheet: 32', 2.0000, 1.2500, 8.5000, 11.0000, 0.5000, 0.2500, 0.0000, 0.0000, 4, 0, 0, 32, NULL, '2017-12-17 23:55:40', '2017-12-17 23:55:40'),
(4, '40 Labels per sheet', 'Sheet Size: 8.5\" x 11\", Label Size: 2\" x 1\", Labels per sheet: 40', 2.0000, 1.0000, 8.5000, 11.0000, 0.5000, 0.2500, 0.0000, 0.0000, 4, 0, 0, 40, NULL, '2017-12-17 23:58:40', '2017-12-17 23:58:40'),
(5, '50 Labels per Sheet', 'Sheet Size: 8.5\" x 11\", Label Size: 1.5\" x 1\", Labels per sheet: 50', 1.5000, 1.0000, 8.5000, 11.0000, 0.5000, 0.5000, 0.0000, 0.0000, 5, 0, 0, 50, NULL, '2017-12-17 23:51:10', '2017-12-17 23:51:10'),
(6, 'Continuous Rolls - 31.75mm x 25.4mm', 'Label Size: 31.75mm x 25.4mm, Gap: 3.18mm', 1.2500, 1.0000, 1.2500, 0.0000, 0.1250, 0.0000, 0.1250, 0.0000, 1, 0, 1, NULL, NULL, '2017-12-17 23:51:10', '2017-12-17 23:51:10');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(10) UNSIGNED NOT NULL,
  `contact_id` int(10) UNSIGNED NOT NULL,
  `waiter_id` int(10) UNSIGNED DEFAULT NULL,
  `table_id` int(10) UNSIGNED DEFAULT NULL,
  `correspondent_id` int(11) DEFAULT NULL,
  `business_id` int(10) UNSIGNED NOT NULL,
  `location_id` int(10) UNSIGNED NOT NULL,
  `booking_start` datetime NOT NULL,
  `booking_end` datetime NOT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `booking_status` varchar(191) NOT NULL,
  `booking_note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int(10) UNSIGNED NOT NULL,
  `business_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `business_id`, `name`, `description`, `created_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'Levis', NULL, 1, NULL, '2018-01-03 15:19:47', '2018-01-03 15:19:47'),
(2, 1, 'Espirit', NULL, 1, NULL, '2018-01-03 15:19:58', '2018-01-03 15:19:58'),
(3, 1, 'U.S. Polo Assn.', NULL, 1, NULL, '2018-01-03 15:20:26', '2018-01-03 15:20:26'),
(4, 1, 'Nike', NULL, 1, NULL, '2018-01-03 15:20:34', '2018-01-03 15:20:34'),
(5, 1, 'Puma', NULL, 1, NULL, '2018-01-03 15:20:40', '2018-01-03 15:20:40'),
(6, 1, 'Adidas', NULL, 1, NULL, '2018-01-03 15:20:46', '2018-01-03 15:20:46'),
(7, 1, 'Samsung', NULL, 1, NULL, '2018-01-05 23:40:14', '2018-01-05 23:40:14'),
(8, 1, 'Apple', NULL, 1, NULL, '2018-01-05 23:40:23', '2018-01-05 23:40:23'),
(9, 1, 'Acer', NULL, 1, NULL, '2018-01-06 00:03:37', '2018-01-06 00:03:37'),
(10, 1, 'Bowflex', NULL, 1, NULL, '2018-01-06 00:15:31', '2018-01-06 00:15:31'),
(11, 1, 'Oreo', NULL, 1, NULL, '2018-01-06 00:35:00', '2018-01-06 00:35:00'),
(12, 1, 'Sharewood', NULL, 1, NULL, '2018-01-06 00:40:52', '2018-01-06 00:40:52'),
(13, 1, 'Barilla', NULL, 1, NULL, '2018-01-06 00:44:59', '2018-01-06 00:44:59'),
(14, 1, 'Lipton', NULL, 1, NULL, '2018-01-06 00:48:12', '2018-01-06 00:48:12'),
(15, 2, 'Acino', NULL, 4, NULL, '2018-04-10 02:14:44', '2018-04-10 02:14:44'),
(16, 2, 'Cipla', NULL, 4, NULL, '2018-04-10 02:15:04', '2018-04-10 02:15:04'),
(17, 2, 'BAYER', NULL, 4, NULL, '2018-04-10 02:15:32', '2018-04-10 02:15:32'),
(18, 3, 'Samsung', NULL, 5, NULL, '2018-04-10 04:57:28', '2018-04-10 04:57:28'),
(19, 3, 'LG', NULL, 5, NULL, '2018-04-10 04:57:35', '2018-04-10 04:57:35'),
(20, 3, 'Apple', NULL, 5, NULL, '2018-04-10 04:57:42', '2018-04-10 04:57:42'),
(21, 3, 'Sony', NULL, 5, NULL, '2018-04-10 04:57:49', '2018-04-10 04:57:49'),
(22, 3, 'Panasonic', NULL, 5, NULL, '2018-04-10 04:57:57', '2018-04-10 04:57:57'),
(23, 3, 'HP', NULL, 5, NULL, '2018-04-10 05:07:21', '2018-04-10 05:07:21'),
(24, 4, 'Samsung', NULL, 1, NULL, '2018-01-05 23:40:14', '2018-01-05 23:40:14'),
(25, 4, 'Apple', NULL, 1, NULL, '2018-01-05 23:40:23', '2018-01-05 23:40:23');

-- --------------------------------------------------------

--
-- Table structure for table `business`
--

CREATE TABLE `business` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `currency_id` int(10) UNSIGNED NOT NULL,
  `start_date` date DEFAULT NULL,
  `tax_number_1` varchar(100) DEFAULT NULL,
  `tax_label_1` varchar(10) DEFAULT NULL,
  `tax_number_2` varchar(100) DEFAULT NULL,
  `tax_label_2` varchar(10) DEFAULT NULL,
  `code_label_1` varchar(191) DEFAULT NULL,
  `code_1` varchar(191) DEFAULT NULL,
  `code_label_2` varchar(191) DEFAULT NULL,
  `code_2` varchar(191) DEFAULT NULL,
  `default_sales_tax` int(10) UNSIGNED DEFAULT NULL,
  `default_profit_percent` double(5,2) NOT NULL DEFAULT 0.00,
  `owner_id` int(10) UNSIGNED NOT NULL,
  `time_zone` varchar(191) NOT NULL DEFAULT 'Asia/Kolkata',
  `fy_start_month` tinyint(4) NOT NULL DEFAULT 1,
  `accounting_method` enum('fifo','lifo','avco') NOT NULL DEFAULT 'fifo',
  `default_sales_discount` decimal(5,2) DEFAULT NULL,
  `sell_price_tax` enum('includes','excludes') NOT NULL DEFAULT 'includes',
  `logo` varchar(191) DEFAULT NULL,
  `sku_prefix` varchar(191) DEFAULT NULL,
  `enable_product_expiry` tinyint(1) NOT NULL DEFAULT 0,
  `expiry_type` enum('add_expiry','add_manufacturing') NOT NULL DEFAULT 'add_expiry',
  `on_product_expiry` enum('keep_selling','stop_selling','auto_delete') NOT NULL DEFAULT 'keep_selling',
  `stop_selling_before` int(11) NOT NULL COMMENT 'Stop selling expied item n days before expiry',
  `enable_tooltip` tinyint(1) NOT NULL DEFAULT 1,
  `purchase_in_diff_currency` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Allow purchase to be in different currency then the business currency',
  `purchase_currency_id` int(10) UNSIGNED DEFAULT NULL,
  `p_exchange_rate` decimal(20,3) NOT NULL DEFAULT 1.000,
  `transaction_edit_days` int(10) UNSIGNED NOT NULL DEFAULT 30,
  `stock_expiry_alert_days` int(10) UNSIGNED NOT NULL DEFAULT 30,
  `keyboard_shortcuts` text DEFAULT NULL,
  `pos_settings` text DEFAULT NULL,
  `weighing_scale_setting` text NOT NULL COMMENT 'used to store the configuration of weighing scale',
  `enable_brand` tinyint(1) NOT NULL DEFAULT 1,
  `enable_category` tinyint(1) NOT NULL DEFAULT 1,
  `enable_sub_category` tinyint(1) NOT NULL DEFAULT 1,
  `enable_price_tax` tinyint(1) NOT NULL DEFAULT 1,
  `enable_purchase_status` tinyint(1) DEFAULT 1,
  `enable_lot_number` tinyint(1) NOT NULL DEFAULT 0,
  `default_unit` int(11) DEFAULT NULL,
  `enable_sub_units` tinyint(1) NOT NULL DEFAULT 0,
  `enable_racks` tinyint(1) NOT NULL DEFAULT 0,
  `enable_row` tinyint(1) NOT NULL DEFAULT 0,
  `enable_position` tinyint(1) NOT NULL DEFAULT 0,
  `enable_editing_product_from_purchase` tinyint(1) NOT NULL DEFAULT 1,
  `sales_cmsn_agnt` enum('logged_in_user','user','cmsn_agnt') DEFAULT NULL,
  `item_addition_method` tinyint(1) NOT NULL DEFAULT 1,
  `enable_inline_tax` tinyint(1) NOT NULL DEFAULT 1,
  `currency_symbol_placement` enum('before','after') NOT NULL DEFAULT 'before',
  `enabled_modules` text DEFAULT NULL,
  `date_format` varchar(191) NOT NULL DEFAULT 'm/d/Y',
  `time_format` enum('12','24') NOT NULL DEFAULT '24',
  `currency_precision` tinyint(4) NOT NULL DEFAULT 2,
  `quantity_precision` tinyint(4) NOT NULL DEFAULT 2,
  `ref_no_prefixes` text DEFAULT NULL,
  `theme_color` char(20) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `enable_rp` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'rp is the short form of reward points',
  `rp_name` varchar(191) DEFAULT NULL COMMENT 'rp is the short form of reward points',
  `amount_for_unit_rp` decimal(22,4) NOT NULL DEFAULT 1.0000 COMMENT 'rp is the short form of reward points',
  `min_order_total_for_rp` decimal(22,4) NOT NULL DEFAULT 1.0000 COMMENT 'rp is the short form of reward points',
  `max_rp_per_order` int(11) DEFAULT NULL COMMENT 'rp is the short form of reward points',
  `redeem_amount_per_unit_rp` decimal(22,4) NOT NULL DEFAULT 1.0000 COMMENT 'rp is the short form of reward points',
  `min_order_total_for_redeem` decimal(22,4) NOT NULL DEFAULT 1.0000 COMMENT 'rp is the short form of reward points',
  `min_redeem_point` int(11) DEFAULT NULL COMMENT 'rp is the short form of reward points',
  `max_redeem_point` int(11) DEFAULT NULL COMMENT 'rp is the short form of reward points',
  `rp_expiry_period` int(11) DEFAULT NULL COMMENT 'rp is the short form of reward points',
  `rp_expiry_type` enum('month','year') NOT NULL DEFAULT 'year' COMMENT 'rp is the short form of reward points',
  `email_settings` text DEFAULT NULL,
  `sms_settings` text DEFAULT NULL,
  `custom_labels` text DEFAULT NULL,
  `common_settings` text DEFAULT NULL,
  `productcatalogue_settings` text DEFAULT NULL,
  `repair_settings` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `business`
--

INSERT INTO `business` (`id`, `name`, `currency_id`, `start_date`, `tax_number_1`, `tax_label_1`, `tax_number_2`, `tax_label_2`, `code_label_1`, `code_1`, `code_label_2`, `code_2`, `default_sales_tax`, `default_profit_percent`, `owner_id`, `time_zone`, `fy_start_month`, `accounting_method`, `default_sales_discount`, `sell_price_tax`, `logo`, `sku_prefix`, `enable_product_expiry`, `expiry_type`, `on_product_expiry`, `stop_selling_before`, `enable_tooltip`, `purchase_in_diff_currency`, `purchase_currency_id`, `p_exchange_rate`, `transaction_edit_days`, `stock_expiry_alert_days`, `keyboard_shortcuts`, `pos_settings`, `weighing_scale_setting`, `enable_brand`, `enable_category`, `enable_sub_category`, `enable_price_tax`, `enable_purchase_status`, `enable_lot_number`, `default_unit`, `enable_sub_units`, `enable_racks`, `enable_row`, `enable_position`, `enable_editing_product_from_purchase`, `sales_cmsn_agnt`, `item_addition_method`, `enable_inline_tax`, `currency_symbol_placement`, `enabled_modules`, `date_format`, `time_format`, `currency_precision`, `quantity_precision`, `ref_no_prefixes`, `theme_color`, `created_by`, `enable_rp`, `rp_name`, `amount_for_unit_rp`, `min_order_total_for_rp`, `max_rp_per_order`, `redeem_amount_per_unit_rp`, `min_order_total_for_redeem`, `min_redeem_point`, `max_redeem_point`, `rp_expiry_period`, `rp_expiry_type`, `email_settings`, `sms_settings`, `custom_labels`, `common_settings`, `productcatalogue_settings`, `repair_settings`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Awesome Shop', 2, '2018-01-01', '3412569900', 'GSTIN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 25.00, 1, 'America/Phoenix', 1, 'fifo', 10.00, 'includes', NULL, 'AS', 0, 'add_expiry', 'keep_selling', 0, 1, 0, NULL, 1.000, 30, 30, '{\"pos\":{\"express_checkout\":\"shift+e\",\"pay_n_ckeckout\":\"shift+p\",\"draft\":\"shift+d\",\"cancel\":\"shift+c\",\"edit_discount\":\"shift+i\",\"edit_order_tax\":\"shift+t\",\"add_payment_row\":\"shift+r\",\"finalize_payment\":\"shift+f\",\"recent_product_quantity\":\"f2\",\"add_new_product\":\"f4\"}}', '{\"disable_pay_checkout\":0,\"disable_draft\":0,\"disable_express_checkout\":0,\"hide_product_suggestion\":0,\"hide_recent_trans\":0,\"disable_discount\":0,\"disable_order_tax\":0, \"customer_display_screen\":1, \"display_screen_heading\":\"<h2 style=\\\"text-align: center;\\\">Welcome<\\/h2>\", \"carousel_image_1\":\"1744193481_20662.jpg\",\"carousel_image_2\":\"1744193481_9195778.jpg\",\"carousel_image_4\":\"1744193481_14928.jpg\",\"carousel_image_5\":\"1744193481_14931.jpg\"}', '', 1, 1, 1, 1, 1, 0, NULL, 0, 0, 0, 0, 1, NULL, 1, 1, 'before', '[\"purchases\",\"add_sale\",\"pos_sale\",\"stock_transfers\",\"stock_adjustment\",\"expenses\",\"account\"]', 'm/d/Y', '24', 2, 2, '{\"purchase\":\"PO\",\"stock_transfer\":\"ST\",\"stock_adjustment\":\"SA\",\"sell_return\":\"CN\",\"expense\":\"EP\",\"contacts\":\"CO\",\"purchase_payment\":\"PP\",\"sell_payment\":\"SP\",\"business_location\":\"BL\"}', NULL, NULL, 0, NULL, 1.0000, 1.0000, NULL, 1.0000, 1.0000, NULL, NULL, NULL, 'year', NULL, NULL, NULL, NULL, '{\"enable_whatsapp_ordering\":1,\"order_receiving_whatsapp_number\":\"123456789\"}', NULL, 1, '2018-01-03 20:15:19', '2018-01-03 20:17:08'),
(2, 'Awesome Pharmacy', 2, '2018-04-10', '3412569900', 'VAT', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 25.00, 4, 'America/Chicago', 1, 'fifo', NULL, 'includes', NULL, 'AP', 1, 'add_manufacturing', 'stop_selling', 0, 1, 0, NULL, 1.000, 30, 30, '{\"pos\":{\"express_checkout\":\"shift+e\",\"pay_n_ckeckout\":\"shift+p\",\"draft\":\"shift+d\",\"cancel\":\"shift+c\",\"edit_discount\":\"shift+i\",\"edit_order_tax\":\"shift+t\",\"add_payment_row\":\"shift+r\",\"finalize_payment\":\"shift+f\",\"recent_product_quantity\":\"f2\",\"add_new_product\":\"f4\"}}', NULL, '', 1, 1, 1, 1, 1, 1, 4, 0, 0, 0, 0, 1, NULL, 1, 0, 'before', '[\"purchases\",\"add_sale\",\"pos_sale\",\"stock_transfers\",\"stock_adjustment\",\"expenses\",\"account\"]', 'm/d/Y', '24', 2, 2, '{\"purchase\":\"PO\",\"stock_transfer\":\"ST\",\"stock_adjustment\":\"SA\",\"sell_return\":\"CN\",\"expense\":\"EP\",\"contacts\":\"CO\",\"purchase_payment\":\"PP\",\"sell_payment\":\"SP\",\"business_location\":\"BL\"}', NULL, NULL, 0, NULL, 1.0000, 1.0000, NULL, 1.0000, 1.0000, NULL, NULL, NULL, 'year', NULL, NULL, NULL, NULL, '{\"enable_whatsapp_ordering\":1,\"order_receiving_whatsapp_number\":\"123456789\"}', NULL, 1, '2018-04-10 02:12:40', '2018-04-10 04:21:38'),
(3, 'Ultimate Electronics', 2, '2018-04-10', '12548555003', 'GST', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 25.00, 5, 'America/Chicago', 1, 'fifo', NULL, 'includes', NULL, 'AE', 0, 'add_expiry', 'keep_selling', 0, 1, 0, NULL, 1.000, 30, 30, '{\"pos\":{\"express_checkout\":\"shift+e\",\"pay_n_ckeckout\":\"shift+p\",\"draft\":\"shift+d\",\"cancel\":\"shift+c\",\"edit_discount\":\"shift+i\",\"edit_order_tax\":\"shift+t\",\"add_payment_row\":\"shift+r\",\"finalize_payment\":\"shift+f\",\"recent_product_quantity\":\"f2\",\"add_new_product\":\"f4\"}}', NULL, '', 1, 1, 1, 1, 1, 0, 5, 0, 0, 0, 0, 1, NULL, 1, 0, 'before', '[\"purchases\",\"add_sale\",\"pos_sale\",\"stock_transfers\",\"stock_adjustment\",\"expenses\",\"account\",\"subscription\"]', 'm/d/Y', '24', 2, 2, '{\"purchase\":\"PO\",\"stock_transfer\":\"ST\",\"stock_adjustment\":\"SA\",\"sell_return\":\"CN\",\"expense\":\"EP\",\"contacts\":\"CO\",\"purchase_payment\":\"PP\",\"sell_payment\":\"SP\",\"business_location\":\"BL\"}', NULL, NULL, 0, NULL, 1.0000, 1.0000, NULL, 1.0000, 1.0000, NULL, NULL, NULL, 'year', NULL, NULL, NULL, '{\"enable_product_warranty\":\"1\",\"default_datatable_page_entries\":\"25\"}', '{\"enable_whatsapp_ordering\":1,\"order_receiving_whatsapp_number\":\"123456789\"}', NULL, 1, '2018-04-10 04:46:15', '2018-04-10 05:53:35'),
(4, 'Awesome Services', 124, '2018-03-10', '3412569900', 'GST', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 25.00, 6, 'America/Chicago', 1, 'fifo', NULL, 'includes', NULL, 'AS', 0, 'add_expiry', 'keep_selling', 0, 1, 0, NULL, 1.000, 30, 30, '{\"pos\":{\"express_checkout\":\"shift+e\",\"pay_n_ckeckout\":\"shift+p\",\"draft\":\"shift+d\",\"cancel\":\"shift+c\",\"edit_discount\":\"shift+i\",\"edit_order_tax\":\"shift+t\",\"add_payment_row\":\"shift+r\",\"finalize_payment\":\"shift+f\",\"recent_product_quantity\":\"f2\",\"add_new_product\":\"f4\"}}', NULL, '', 1, 1, 0, 1, 1, 0, NULL, 0, 0, 0, 0, 1, NULL, 1, 0, 'before', '[\"purchases\",\"add_sale\",\"pos_sale\",\"expenses\",\"account\",\"service_staff\"]', 'm/d/Y', '24', 2, 2, '{\"purchase\":\"PO\",\"stock_transfer\":\"ST\",\"stock_adjustment\":\"SA\",\"sell_return\":\"CN\",\"expense\":\"EP\",\"contacts\":\"CO\",\"purchase_payment\":\"PP\",\"sell_payment\":\"SP\",\"business_location\":\"BL\"}', NULL, NULL, 0, NULL, 1.0000, 1.0000, NULL, 1.0000, 1.0000, NULL, NULL, NULL, 'year', NULL, NULL, NULL, NULL, '{\"enable_whatsapp_ordering\":1,\"order_receiving_whatsapp_number\":\"123456789\"}', '{\"repair_tc_condition\":\"<p><strong>30- Day Service Warranty :<\\/strong> If your device encounters the same issue within 30 days of the repair, we will fix the issue at our own cost with no further question asked. You have your peace of mind.&nbsp;<\\/p>\\r\\n<p><strong>Service Disclaimers :<\\/strong> Please make sure that you collect your item within two months of servic-ing, Otherwise, we will not be liable if your item gets lost or damaged.&nbsp;<\\/p>\\r\\n<p><strong>Terms &amp;Conditions:<\\/strong> Please also be aware that we can only service your gadget at your own risk. Even though we do our best in \\\"Awesome Services\\\" to make things better. However, in the worst-case scenario, your gadget may be damaged during repairs. We will not be liable if such things happen. Visit our website to know more our Policy.<\\/p>\",\"job_sheet_prefix\":\"JS\",\"problem_reported_by_customer\":\"No backup, Battery life mediocre, Slow boot and application launch, Too glossy screen, Touchpad requires improvement, Keyboard requires improvement, RAM needs to be expanded, Lacks SD card slot\",\"product_condition\":\"No physical damage, Screen fine, Screen scratches, body broken, Wet in water, colourful display, Great battery life, Excellent audio quality, Lacks SD card slot\",\"product_configuration\":\"2 Gb RAM, 2GB ROM, Cpu,  Core i3 Processor, Core i5 Processor, Core i7 Processor, 16GB RAM, 4 GB RAM, 1TB HDD, 520 SSD, USB Type-C port, USB Type-A prot, 1080p IPS Display\",\"default_status\":\"3\"}', 1, '2018-04-10 06:20:42', '2018-04-10 06:23:40'),
(5, 'Awesome Restaurant', 2, '2018-07-12', '4578220005', 'IGST', '4593220009', 'SGST', NULL, NULL, NULL, NULL, NULL, 25.00, 7, 'America/Chicago', 1, 'fifo', NULL, 'includes', NULL, 'AR', 0, 'add_expiry', 'keep_selling', 0, 1, 0, NULL, 1.000, 30, 30, '{\"pos\":{\"express_checkout\":\"shift+e\",\"pay_n_ckeckout\":\"shift+p\",\"draft\":\"shift+d\",\"cancel\":\"shift+c\",\"edit_discount\":\"shift+i\",\"edit_order_tax\":\"shift+t\",\"add_payment_row\":\"shift+r\",\"finalize_payment\":\"shift+f\",\"recent_product_quantity\":\"f2\",\"add_new_product\":\"f4\"}}', '{\"disable_pay_checkout\":0,\"disable_draft\":0,\"disable_express_checkout\":0,\"hide_product_suggestion\":0,\"hide_recent_trans\":0,\"disable_discount\":0,\"disable_order_tax\":0, \"customer_display_screen\":1, \"display_screen_heading\":\"<h2 style=\\\"text-align: center;\\\">Welcome<\\/h2>\", \"carousel_image_1\":\"1744193481_20662.jpg\",\"carousel_image_2\":\"1744193481_9195778.jpg\",\"carousel_image_4\":\"1744193481_14928.jpg\",\"carousel_image_5\":\"1744193481_14931.jpg\"}', '', 1, 1, 1, 1, 1, 0, NULL, 0, 0, 0, 0, 1, NULL, 1, 0, 'before', '[\"purchases\",\"add_sale\",\"pos_sale\",\"stock_transfers\",\"stock_adjustment\",\"expenses\",\"tables\",\"modifiers\",\"service_staff\",\"kitchen\",\"types_of_service\",\"booking\"]', 'm/d/Y', '24', 2, 2, '{\"purchase\":\"PO\",\"stock_transfer\":\"ST\",\"stock_adjustment\":\"SA\",\"sell_return\":\"CN\",\"expense\":\"EP\",\"contacts\":\"CO\",\"purchase_payment\":\"PP\",\"sell_payment\":\"SP\",\"business_location\":\"BL\"}', NULL, NULL, 0, NULL, 1.0000, 1.0000, NULL, 1.0000, 1.0000, NULL, NULL, NULL, 'year', NULL, NULL, NULL, NULL, '{\"enable_whatsapp_ordering\":1,\"order_receiving_whatsapp_number\":\"123456789\"}', NULL, 1, '2018-07-13 10:42:11', '2018-07-13 01:38:24'),
(6, 'Manufacturers Demo', 124, '2019-08-18', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 25.00, 12, 'Asia/Kolkata', 1, 'fifo', NULL, 'includes', NULL, NULL, 0, 'add_expiry', 'keep_selling', 0, 1, 0, NULL, 1.000, 30, 30, '{\"pos\":{\"express_checkout\":\"shift+e\",\"pay_n_ckeckout\":\"shift+p\",\"draft\":\"shift+d\",\"cancel\":\"shift+c\",\"edit_discount\":\"shift+i\",\"edit_order_tax\":\"shift+t\",\"add_payment_row\":\"shift+r\",\"finalize_payment\":\"shift+f\",\"recent_product_quantity\":\"f2\",\"add_new_product\":\"f4\"}}', '{\"disable_pay_checkout\":0,\"disable_draft\":0,\"disable_express_checkout\":0,\"hide_product_suggestion\":0,\"hide_recent_trans\":0,\"disable_discount\":0,\"disable_order_tax\":0,\"is_pos_subtotal_editable\":0}', '', 1, 1, 1, 1, 1, 0, NULL, 0, 0, 0, 0, 1, NULL, 1, 0, 'before', '[\"purchases\",\"add_sale\",\"pos_sale\",\"stock_transfers\",\"stock_adjustment\",\"expenses\"]', 'm/d/Y', '24', 2, 2, '{\"purchase\":\"PO\",\"stock_transfer\":\"ST\",\"stock_adjustment\":\"SA\",\"sell_return\":\"CN\",\"expense\":\"EP\",\"contacts\":\"CO\",\"purchase_payment\":\"PP\",\"sell_payment\":\"SP\",\"business_location\":\"BL\"}', NULL, NULL, 0, NULL, 1.0000, 1.0000, NULL, 1.0000, 1.0000, NULL, NULL, NULL, 'year', NULL, NULL, NULL, NULL, '{\"enable_whatsapp_ordering\":1,\"order_receiving_whatsapp_number\":\"123456789\"}', NULL, 1, '2019-08-18 12:41:28', '2019-08-18 12:43:22');

-- --------------------------------------------------------

--
-- Table structure for table `business_locations`
--

CREATE TABLE `business_locations` (
  `id` int(10) UNSIGNED NOT NULL,
  `business_id` int(10) UNSIGNED NOT NULL,
  `location_id` varchar(191) DEFAULT NULL,
  `name` varchar(256) NOT NULL,
  `landmark` text DEFAULT NULL,
  `country` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `zip_code` char(7) NOT NULL,
  `invoice_scheme_id` int(10) UNSIGNED NOT NULL,
  `sale_invoice_scheme_id` int(11) DEFAULT NULL,
  `invoice_layout_id` int(10) UNSIGNED NOT NULL,
  `sale_invoice_layout_id` int(11) DEFAULT NULL,
  `selling_price_group_id` int(11) DEFAULT NULL,
  `print_receipt_on_invoice` tinyint(1) DEFAULT 1,
  `receipt_printer_type` enum('browser','printer') NOT NULL DEFAULT 'browser',
  `printer_id` int(11) DEFAULT NULL,
  `mobile` varchar(191) DEFAULT NULL,
  `alternate_number` varchar(191) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `website` varchar(191) DEFAULT NULL,
  `featured_products` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `default_payment_accounts` text DEFAULT NULL,
  `custom_field1` varchar(191) DEFAULT NULL,
  `custom_field2` varchar(191) DEFAULT NULL,
  `custom_field3` varchar(191) DEFAULT NULL,
  `custom_field4` varchar(191) DEFAULT NULL,
  `zatca_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`zatca_details`)),
  `zatca_response` longtext DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `business_locations`
--

INSERT INTO `business_locations` (`id`, `business_id`, `location_id`, `name`, `landmark`, `country`, `state`, `city`, `zip_code`, `invoice_scheme_id`, `sale_invoice_scheme_id`, `invoice_layout_id`, `sale_invoice_layout_id`, `selling_price_group_id`, `print_receipt_on_invoice`, `receipt_printer_type`, `printer_id`, `mobile`, `alternate_number`, `email`, `website`, `featured_products`, `is_active`, `default_payment_accounts`, `custom_field1`, `custom_field2`, `custom_field3`, `custom_field4`, `zatca_details`, `zatca_response`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'Awesome Shop', 'Linking Street', 'USA', 'Arizona', 'Phoenix', '85001', 1, NULL, 1, 1, NULL, 1, 'browser', NULL, NULL, NULL, NULL, NULL, NULL, 1, '{\"cash\":{\"is_enabled\":\"1\",\"account\":null},\"card\":{\"is_enabled\":\"1\",\"account\":null},\"cheque\":{\"is_enabled\":\"1\",\"account\":null},\"bank_transfer\":{\"is_enabled\":\"1\",\"account\":null},\"other\":{\"is_enabled\":\"1\",\"account\":null},\"custom_pay_1\":{\"is_enabled\":\"1\",\"account\":null},\"custom_pay_2\":{\"is_enabled\":\"1\",\"account\":null},\"custom_pay_3\":{\"is_enabled\":\"1\",\"account\":null}}', NULL, NULL, NULL, NULL, '{\"portal_mode\":\"developer-portal\",\"otp\":\"111222\",\"email\":\"email@gmail.com\",\"common_name\":\"TSTCO\",\"country_code\":\"SA\",\"organization_unit_name\":\"TSTCO-SA\",\"organization_name\":\"TSTCO-SA\",\"egs_serial_number\":\"1-SDSA|2-FGDS|3-SDFG\",\"vat_number\":\"300000000000003\",\"vat_name\":\"TSTCO VAT\",\"invoice_type\":\"1100\",\"registered_address\":\"RMRE1234\",\"business_category\":\"Transportations\",\"crn\":\"CRN123456\",\"street_name\":\"Main Street\",\"building_number\":\"123\",\"plot_identification\":\"Plot567\",\"sub_division_name\":\"Zone A\",\"city_name\":\"Riyadh\",\"postal_number\":\"11564\",\"country_name\":\"Saudi Arabia\"}', '{\"success\":true,\"message\":\"ISSUED\",\"data\":{\"complianceCertificate\":\"TUlJQjZUQ0NBWStnQXdJQkFnSUdBWldvbnlUcE1Bb0dDQ3FHU000OUJBTUNNQlV4RXpBUkJnTlZCQU1NQ21WSmJuWnZhV05wYm1jd0hoY05NalV3TXpFNE1Ea3pPVEV4V2hjTk16QXdNekUzTWpFd01EQXdXakJETVE0d0RBWURWUVFEREFWVVUxUkRUekVSTUE4R0ExVUVDd3dJVkZOVVEwOHRVMEV4RVRBUEJnTlZCQW9NQ0ZSVFZFTlBMVk5CTVFzd0NRWURWUVFHRXdKVFFUQldNQkFHQnlxR1NNNDlBZ0VHQlN1QkJBQUtBMElBQkNoQ3VxVkFSZmhicnR3azlFbFRNWkVzMGpsbXdQQmFaSVZYY1pCVVNvUW41WitlU1JRdWVteTliVkp5UndpOXRzcll6OXhMbW5LdWRVaWJwbGlTb2ZHamdaOHdnWnd3REFZRFZSMFRBUUgvQkFJd0FEQ0Jpd1lEVlIwUkJJR0RNSUdBcEg0d2ZERWRNQnNHQTFVRUJBd1VNUzFUUkZOQmZESXRSa2RFVTN3ekxWTkVSa2N4SHpBZEJnb0praWFKay9Jc1pBRUJEQTh6TURBd01EQXdNREF3TURBd01ETXhEVEFMQmdOVkJBd01CREV4TURBeEVUQVBCZ05WQkJvTUNGSk5Va1V4TWpNME1SZ3dGZ1lEVlFRUERBOVVjbUZ1YzNCdmNuUmhkR2x2Ym5Nd0NnWUlLb1pJemowRUF3SURTQUF3UlFJaEFKMmZTOUJSV2N1cVdlbUhlRzA2MjVHUUt4L3hFTE1vd2RwcWV2bXlZMUV2QWlBVXh1MkNQUVhSaEVseUxYa0ViWFQrOUE3bDZ5L09NTFM0cGY2RkRWQmJJUT09\",\"complianceSecret\":\"k7mFPUhn8BpsDt0UM2zwT83LeQm5ENIluKjhLecPBAE=\",\"complianceRequestID\":\"1234567890123\",\"productionCertificate\":\"TUlJRDNqQ0NBNFNnQXdJQkFnSVRFUUFBT0FQRjkwQWpzL3hjWHdBQkFBQTRBekFLQmdncWhrak9QUVFEQWpCaU1SVXdFd1lLQ1pJbWlaUHlMR1FCR1JZRmJHOWpZV3d4RXpBUkJnb0praWFKay9Jc1pBRVpGZ05uYjNZeEZ6QVZCZ29Ka2lhSmsvSXNaQUVaRmdkbGVIUm5ZWHAwTVJzd0dRWURWUVFERXhKUVVscEZTVTVXVDBsRFJWTkRRVFF0UTBFd0hoY05NalF3TVRFeE1Ea3hPVE13V2hjTk1qa3dNVEE1TURreE9UTXdXakIxTVFzd0NRWURWUVFHRXdKVFFURW1NQ1FHQTFVRUNoTWRUV0Y0YVcxMWJTQlRjR1ZsWkNCVVpXTm9JRk4xY0hCc2VTQk1WRVF4RmpBVUJnTlZCQXNURFZKcGVXRmthQ0JDY21GdVkyZ3hKakFrQmdOVkJBTVRIVlJUVkMwNE9EWTBNekV4TkRVdE16azVPVGs1T1RrNU9UQXdNREF6TUZZd0VBWUhLb1pJemowQ0FRWUZLNEVFQUFvRFFnQUVvV0NLYTBTYTlGSUVyVE92MHVBa0MxVklLWHhVOW5QcHgydmxmNHloTWVqeThjMDJYSmJsRHE3dFB5ZG84bXEwYWhPTW1Obzhnd25pN1h0MUtUOVVlS09DQWdjd2dnSURNSUd0QmdOVkhSRUVnYVV3Z2FLa2daOHdnWnd4T3pBNUJnTlZCQVFNTWpFdFZGTlVmREl0VkZOVWZETXRaV1F5TW1ZeFpEZ3RaVFpoTWkweE1URTRMVGxpTlRndFpEbGhPR1l4TVdVME5EVm1NUjh3SFFZS0NaSW1pWlB5TEdRQkFRd1BNems1T1RrNU9UazVPVEF3TURBek1RMHdDd1lEVlFRTURBUXhNVEF3TVJFd0R3WURWUVFhREFoU1VsSkVNamt5T1RFYU1CZ0dBMVVFRHd3UlUzVndjR3g1SUdGamRHbDJhWFJwWlhNd0hRWURWUjBPQkJZRUZFWCtZdm1tdG5Zb0RmOUJHYktvN29jVEtZSzFNQjhHQTFVZEl3UVlNQmFBRkp2S3FxTHRtcXdza0lGelZ2cFAyUHhUKzlObk1Ic0dDQ3NHQVFVRkJ3RUJCRzh3YlRCckJnZ3JCZ0VGQlFjd0FvWmZhSFIwY0RvdkwyRnBZVFF1ZW1GMFkyRXVaMjkyTG5OaEwwTmxjblJGYm5KdmJHd3ZVRkphUlVsdWRtOXBZMlZUUTBFMExtVjRkR2RoZW5RdVoyOTJMbXh2WTJGc1gxQlNXa1ZKVGxaUFNVTkZVME5CTkMxRFFTZ3hLUzVqY25Rd0RnWURWUjBQQVFIL0JBUURBZ2VBTUR3R0NTc0dBUVFCZ2pjVkJ3UXZNQzBHSlNzR0FRUUJnamNWQ0lHR3FCMkUwUHNTaHUyZEpJZk8reG5Ud0ZWbWgvcWxaWVhaaEQ0Q0FXUUNBUkl3SFFZRFZSMGxCQll3RkFZSUt3WUJCUVVIQXdNR0NDc0dBUVVGQndNQ01DY0dDU3NHQVFRQmdqY1ZDZ1FhTUJnd0NnWUlLd1lCQlFVSEF3TXdDZ1lJS3dZQkJRVUhBd0l3Q2dZSUtvWkl6ajBFQXdJRFNBQXdSUUloQUxFL2ljaG1uV1hDVUtVYmNhM3ljaThvcXdhTHZGZEhWalFydmVJOXVxQWJBaUE5aEM0TThqZ01CQURQU3ptZDJ1aVBKQTZnS1IzTEUwM1U3NWVxYkMvclhBPT0=\",\"productionCertificateSecret\":\"CkYsEXfV8c1gFHAtFWoZv73pGMvh\\/Qyo4LzKM2h\\/8Hg=\",\"productionCertificateRequestID\":\"30368\",\"privateKey\":\"LS0tLS1CRUdJTiBQUklWQVRFIEtFWS0tLS0tCk1JR0VBZ0VBTUJBR0J5cUdTTTQ5QWdFR0JTdUJCQUFLQkcwd2F3SUJBUVFnMGlkcmFmaDNPcHFyd012NXhhaW4KWFAwRldvamJWRUw0aFFCbG1hWnRuZXloUkFOQ0FBUW9RcnFsUUVYNFc2N2NKUFJKVXpHUkxOSTVac0R3V21TRgpWM0dRVkVxRUorV2Zua2tVTG5wc3ZXMVNja2NJdmJiSzJNL2NTNXB5cm5WSW02WllrcUh4Ci0tLS0tRU5EIFBSSVZBVEUgS0VZLS0tLS0K\",\"publicKey\":\"LS0tLS1CRUdJTiBQVUJMSUMgS0VZLS0tLS0KTUZZd0VBWUhLb1pJemowQ0FRWUZLNEVFQUFvRFFnQUVLRUs2cFVCRitGdXUzQ1QwU1ZNeGtTelNPV2JBOEZwawpoVmR4a0ZSS2hDZmxuNTVKRkM1NmJMMXRVbkpIQ0wyMnl0alAzRXVhY3E1MVNKdW1XSktoOFE9PQotLS0tLUVORCBQVUJMSUMgS0VZLS0tLS0K\",\"csrKey\":\"LS0tLS1CRUdJTiBDRVJUSUZJQ0FURSBSRVFVRVNULS0tLS0KTUlJQnhqQ0NBV3NDQVFBd1F6RU9NQXdHQTFVRUF3d0ZWRk5VUTA4eEVUQVBCZ05WQkFzTUNGUlRWRU5QTFZOQgpNUkV3RHdZRFZRUUtEQWhVVTFSRFR5MVRRVEVMTUFrR0ExVUVCaE1DVTBFd1ZqQVFCZ2NxaGtqT1BRSUJCZ1VyCmdRUUFDZ05DQUFRb1FycWxRRVg0VzY3Y0pQUkpVekdSTE5JNVpzRHdXbVNGVjNHUVZFcUVKK1dmbmtrVUxucHMKdlcxU2NrY0l2YmJLMk0vY1M1cHlyblZJbTZaWWtxSHhvSUhJTUlIRkJna3Foa2lHOXcwQkNRNHhnYmN3Z2JRdwpKQVlKS3dZQkJBR0NOeFFDQkJjVEZWUlRWRnBCVkVOQkxVTnZaR1V0VTJsbmJtbHVaekNCaXdZRFZSMFJCSUdECk1JR0FwSDR3ZkRFZE1Cc0dBMVVFQkF3VU1TMVRSRk5CZkRJdFJrZEVVM3d6TFZORVJrY3hIekFkQmdvSmtpYUoKay9Jc1pBRUJEQTh6TURBd01EQXdNREF3TURBd01ETXhEVEFMQmdOVkJBd01CREV4TURBeEVUQVBCZ05WQkJvTQpDRkpOVWtVeE1qTTBNUmd3RmdZRFZRUVBEQTlVY21GdWMzQnZjblJoZEdsdmJuTXdDZ1lJS29aSXpqMEVBd0lEClNRQXdSZ0loQVAwMks4L1VkcjF2Z2xQVGx0SHhxVEFCVWxiUVVZekxWQjZaYXlzdDU4WUFBaUVBblZMZWt5R3QKeDhhaVQzZ2Q2T3A5NmsxMkxSb1ZuSVFndVlSQS9md2FOdW89Ci0tLS0tRU5EIENFUlRJRklDQVRFIFJFUVVFU1QtLS0tLQo=\",\"configData\":\"DQogICAgICAgICAgICBvaWRfc2VjdGlvbiA9IE9JRHMNCiAgICAgICAgICAgIFsgT0lEcyBdDQogICAgICAgICAgICBjZXJ0aWZpY2F0ZVRlbXBsYXRlTmFtZT0gMS4zLjYuMS40LjEuMzExLjIwLjINCg0KICAgICAgICAgICAgWyByZXEgXQ0KICAgICAgICAgICAgZGVmYXVsdF9iaXRzIAk9IDIwNDgNCiAgICAgICAgICAgIGVtYWlsQWRkcmVzcyAJPSBlbWFpbEBnbWFpbC5jb20NCiAgICAgICAgICAgIHJlcV9leHRlbnNpb25zCT0gdjNfcmVxDQogICAgICAgICAgICB4NTA5X2V4dGVuc2lvbnMgCT0gdjNfY2ENCiAgICAgICAgICAgIHByb21wdCA9IG5vDQogICAgICAgICAgICBkZWZhdWx0X21kID0gc2hhMjU2DQogICAgICAgICAgICByZXFfZXh0ZW5zaW9ucyA9IHJlcV9leHQNCiAgICAgICAgICAgIGRpc3Rpbmd1aXNoZWRfbmFtZSA9IGRuDQoNCiAgICAgICAgICAgIFsgdjNfcmVxIF0NCiAgICAgICAgICAgIGJhc2ljQ29uc3RyYWludHMgPSBDQTpGQUxTRQ0KICAgICAgICAgICAga2V5VXNhZ2UgPSBkaWdpdGFsU2lnbmF0dXJlLCBub25SZXB1ZGlhdGlvbiwga2V5RW5jaXBoZXJtZW50DQoNCiAgICAgICAgICAgIFtyZXFfZXh0XQ0KICAgICAgICAgICAgY2VydGlmaWNhdGVUZW1wbGF0ZU5hbWUgPSBBU04xOlBSSU5UQUJMRVNUUklORzpUU1RaQVRDQS1Db2RlLVNpZ25pbmcNCiAgICAgICAgICAgIHN1YmplY3RBbHROYW1lID0gZGlyTmFtZTphbHRfbmFtZXMNCg0KICAgICAgICAgICAgWyB2M19jYSBdDQoNCiAgICAgICAgICAgICMgRXh0ZW5zaW9ucyBmb3IgYSB0eXBpY2FsIENBDQoNCiAgICAgICAgICAgICMgUEtJWCByZWNvbW1lbmRhdGlvbi4NCg0KICAgICAgICAgICAgc3ViamVjdEtleUlkZW50aWZpZXIgPSBoYXNoDQoNCiAgICAgICAgICAgIGF1dGhvcml0eUtleUlkZW50aWZpZXIgPSBrZXlpZDphbHdheXMsaXNzdWVyOmFsd2F5cw0KDQogICAgICAgICAgICBbIGRuIF0NCiAgICAgICAgICAgIENOID0gVFNUQ08gIAkJCQkgICAgICAgICAgICAgICAgICAgICMgQ29tbW9uIE5hbWUNCiAgICAgICAgICAgIEMgPSBTQQkJCQkJCQkgICAgICAgICAgICAjIENvdW50cnkgQ29kZSBlLmcgU0ENCiAgICAgICAgICAgIE9VID0gVFNUQ08tU0EJCQkJCQkJIyBPcmdhbml6YXRpb24gVW5pdCBOYW1lDQogICAgICAgICAgICBPID0gVFNUQ08tU0EJCQkJCQkJICAgICAgICAjIE9yZ2FuaXphdGlvbiBOYW1lDQoNCiAgICAgICAgICAgIFthbHRfbmFtZXNdDQogICAgICAgICAgICBTTiA9IDEtU0RTQXwyLUZHRFN8My1TREZHCQkJCSAgICAgICAgICAgICAgICAgICAgIyBFR1MgU2VyaWFsIE51bWJlciAxLUFCQ3wyLVBRUnwzLVhZWg0KICAgICAgICAgICAgVUlEID0gMzAwMDAwMDAwMDAwMDAzCQkJCQkJICAgICAgICAgICAgICAgICMgT3JnYW5pemF0aW9uIElkZW50aWZpZXIgKFZBVCBOdW1iZXIpDQogICAgICAgICAgICB0aXRsZSA9IDExMDAJCQkJCQkJCSAgICAjIEludm9pY2UgVHlwZQ0KICAgICAgICAgICAgcmVnaXN0ZXJlZEFkZHJlc3MgPSBSTVJFMTIzNCAgCSAJCQkjIEFkZHJlc3MNCiAgICAgICAgICAgIGJ1c2luZXNzQ2F0ZWdvcnkgPSBUcmFuc3BvcnRhdGlvbnMJCQkJCSMgQnVzaW5lc3MgQ2F0ZWdvcnkNCiAgICAgICAg\"}}', NULL, '2018-01-03 20:15:20', '2019-12-10 22:53:39'),
(2, 2, NULL, 'Awesome Pharmacy', 'Linking Street', 'USA', 'Arizona', 'Phoenix', '492001', 2, NULL, 2, 2, NULL, 1, 'browser', NULL, NULL, NULL, NULL, NULL, NULL, 1, '{\"cash\":{\"is_enabled\":\"1\"},\"card\":{\"is_enabled\":\"1\"},\"cheque\":{\"is_enabled\":\"1\"},\"bank_transfer\":{\"is_enabled\":\"1\"},\"other\":{\"is_enabled\":\"1\"},\"custom_pay_1\":{\"is_enabled\":\"1\"},\"custom_pay_2\":{\"is_enabled\":\"1\"},\"custom_pay_3\":{\"is_enabled\":\"1\"}}', NULL, NULL, NULL, NULL, '{\"portal_mode\":\"developer-portal\",\"otp\":\"111222\",\"email\":\"email@gmail.com\",\"common_name\":\"TSTCO\",\"country_code\":\"SA\",\"organization_unit_name\":\"TSTCO-SA\",\"organization_name\":\"TSTCO-SA\",\"egs_serial_number\":\"1-SDSA|2-FGDS|3-SDFG\",\"vat_number\":\"300000000000003\",\"vat_name\":\"TSTCO VAT\",\"invoice_type\":\"1100\",\"registered_address\":\"RMRE1234\",\"business_category\":\"Transportations\",\"crn\":\"CRN123456\",\"street_name\":\"Main Street\",\"building_number\":\"123\",\"plot_identification\":\"Plot567\",\"sub_division_name\":\"Zone A\",\"city_name\":\"Riyadh\",\"postal_number\":\"11564\",\"country_name\":\"Saudi Arabia\"}', '{\"success\":true,\"message\":\"ISSUED\",\"data\":{\"complianceCertificate\":\"TUlJQjZUQ0NBWStnQXdJQkFnSUdBWldvbnlUcE1Bb0dDQ3FHU000OUJBTUNNQlV4RXpBUkJnTlZCQU1NQ21WSmJuWnZhV05wYm1jd0hoY05NalV3TXpFNE1Ea3pPVEV4V2hjTk16QXdNekUzTWpFd01EQXdXakJETVE0d0RBWURWUVFEREFWVVUxUkRUekVSTUE4R0ExVUVDd3dJVkZOVVEwOHRVMEV4RVRBUEJnTlZCQW9NQ0ZSVFZFTlBMVk5CTVFzd0NRWURWUVFHRXdKVFFUQldNQkFHQnlxR1NNNDlBZ0VHQlN1QkJBQUtBMElBQkNoQ3VxVkFSZmhicnR3azlFbFRNWkVzMGpsbXdQQmFaSVZYY1pCVVNvUW41WitlU1JRdWVteTliVkp5UndpOXRzcll6OXhMbW5LdWRVaWJwbGlTb2ZHamdaOHdnWnd3REFZRFZSMFRBUUgvQkFJd0FEQ0Jpd1lEVlIwUkJJR0RNSUdBcEg0d2ZERWRNQnNHQTFVRUJBd1VNUzFUUkZOQmZESXRSa2RFVTN3ekxWTkVSa2N4SHpBZEJnb0praWFKay9Jc1pBRUJEQTh6TURBd01EQXdNREF3TURBd01ETXhEVEFMQmdOVkJBd01CREV4TURBeEVUQVBCZ05WQkJvTUNGSk5Va1V4TWpNME1SZ3dGZ1lEVlFRUERBOVVjbUZ1YzNCdmNuUmhkR2x2Ym5Nd0NnWUlLb1pJemowRUF3SURTQUF3UlFJaEFKMmZTOUJSV2N1cVdlbUhlRzA2MjVHUUt4L3hFTE1vd2RwcWV2bXlZMUV2QWlBVXh1MkNQUVhSaEVseUxYa0ViWFQrOUE3bDZ5L09NTFM0cGY2RkRWQmJJUT09\",\"complianceSecret\":\"k7mFPUhn8BpsDt0UM2zwT83LeQm5ENIluKjhLecPBAE=\",\"complianceRequestID\":\"1234567890123\",\"productionCertificate\":\"TUlJRDNqQ0NBNFNnQXdJQkFnSVRFUUFBT0FQRjkwQWpzL3hjWHdBQkFBQTRBekFLQmdncWhrak9QUVFEQWpCaU1SVXdFd1lLQ1pJbWlaUHlMR1FCR1JZRmJHOWpZV3d4RXpBUkJnb0praWFKay9Jc1pBRVpGZ05uYjNZeEZ6QVZCZ29Ka2lhSmsvSXNaQUVaRmdkbGVIUm5ZWHAwTVJzd0dRWURWUVFERXhKUVVscEZTVTVXVDBsRFJWTkRRVFF0UTBFd0hoY05NalF3TVRFeE1Ea3hPVE13V2hjTk1qa3dNVEE1TURreE9UTXdXakIxTVFzd0NRWURWUVFHRXdKVFFURW1NQ1FHQTFVRUNoTWRUV0Y0YVcxMWJTQlRjR1ZsWkNCVVpXTm9JRk4xY0hCc2VTQk1WRVF4RmpBVUJnTlZCQXNURFZKcGVXRmthQ0JDY21GdVkyZ3hKakFrQmdOVkJBTVRIVlJUVkMwNE9EWTBNekV4TkRVdE16azVPVGs1T1RrNU9UQXdNREF6TUZZd0VBWUhLb1pJemowQ0FRWUZLNEVFQUFvRFFnQUVvV0NLYTBTYTlGSUVyVE92MHVBa0MxVklLWHhVOW5QcHgydmxmNHloTWVqeThjMDJYSmJsRHE3dFB5ZG84bXEwYWhPTW1Obzhnd25pN1h0MUtUOVVlS09DQWdjd2dnSURNSUd0QmdOVkhSRUVnYVV3Z2FLa2daOHdnWnd4T3pBNUJnTlZCQVFNTWpFdFZGTlVmREl0VkZOVWZETXRaV1F5TW1ZeFpEZ3RaVFpoTWkweE1URTRMVGxpTlRndFpEbGhPR1l4TVdVME5EVm1NUjh3SFFZS0NaSW1pWlB5TEdRQkFRd1BNems1T1RrNU9UazVPVEF3TURBek1RMHdDd1lEVlFRTURBUXhNVEF3TVJFd0R3WURWUVFhREFoU1VsSkVNamt5T1RFYU1CZ0dBMVVFRHd3UlUzVndjR3g1SUdGamRHbDJhWFJwWlhNd0hRWURWUjBPQkJZRUZFWCtZdm1tdG5Zb0RmOUJHYktvN29jVEtZSzFNQjhHQTFVZEl3UVlNQmFBRkp2S3FxTHRtcXdza0lGelZ2cFAyUHhUKzlObk1Ic0dDQ3NHQVFVRkJ3RUJCRzh3YlRCckJnZ3JCZ0VGQlFjd0FvWmZhSFIwY0RvdkwyRnBZVFF1ZW1GMFkyRXVaMjkyTG5OaEwwTmxjblJGYm5KdmJHd3ZVRkphUlVsdWRtOXBZMlZUUTBFMExtVjRkR2RoZW5RdVoyOTJMbXh2WTJGc1gxQlNXa1ZKVGxaUFNVTkZVME5CTkMxRFFTZ3hLUzVqY25Rd0RnWURWUjBQQVFIL0JBUURBZ2VBTUR3R0NTc0dBUVFCZ2pjVkJ3UXZNQzBHSlNzR0FRUUJnamNWQ0lHR3FCMkUwUHNTaHUyZEpJZk8reG5Ud0ZWbWgvcWxaWVhaaEQ0Q0FXUUNBUkl3SFFZRFZSMGxCQll3RkFZSUt3WUJCUVVIQXdNR0NDc0dBUVVGQndNQ01DY0dDU3NHQVFRQmdqY1ZDZ1FhTUJnd0NnWUlLd1lCQlFVSEF3TXdDZ1lJS3dZQkJRVUhBd0l3Q2dZSUtvWkl6ajBFQXdJRFNBQXdSUUloQUxFL2ljaG1uV1hDVUtVYmNhM3ljaThvcXdhTHZGZEhWalFydmVJOXVxQWJBaUE5aEM0TThqZ01CQURQU3ptZDJ1aVBKQTZnS1IzTEUwM1U3NWVxYkMvclhBPT0=\",\"productionCertificateSecret\":\"CkYsEXfV8c1gFHAtFWoZv73pGMvh\\/Qyo4LzKM2h\\/8Hg=\",\"productionCertificateRequestID\":\"30368\",\"privateKey\":\"LS0tLS1CRUdJTiBQUklWQVRFIEtFWS0tLS0tCk1JR0VBZ0VBTUJBR0J5cUdTTTQ5QWdFR0JTdUJCQUFLQkcwd2F3SUJBUVFnMGlkcmFmaDNPcHFyd012NXhhaW4KWFAwRldvamJWRUw0aFFCbG1hWnRuZXloUkFOQ0FBUW9RcnFsUUVYNFc2N2NKUFJKVXpHUkxOSTVac0R3V21TRgpWM0dRVkVxRUorV2Zua2tVTG5wc3ZXMVNja2NJdmJiSzJNL2NTNXB5cm5WSW02WllrcUh4Ci0tLS0tRU5EIFBSSVZBVEUgS0VZLS0tLS0K\",\"publicKey\":\"LS0tLS1CRUdJTiBQVUJMSUMgS0VZLS0tLS0KTUZZd0VBWUhLb1pJemowQ0FRWUZLNEVFQUFvRFFnQUVLRUs2cFVCRitGdXUzQ1QwU1ZNeGtTelNPV2JBOEZwawpoVmR4a0ZSS2hDZmxuNTVKRkM1NmJMMXRVbkpIQ0wyMnl0alAzRXVhY3E1MVNKdW1XSktoOFE9PQotLS0tLUVORCBQVUJMSUMgS0VZLS0tLS0K\",\"csrKey\":\"LS0tLS1CRUdJTiBDRVJUSUZJQ0FURSBSRVFVRVNULS0tLS0KTUlJQnhqQ0NBV3NDQVFBd1F6RU9NQXdHQTFVRUF3d0ZWRk5VUTA4eEVUQVBCZ05WQkFzTUNGUlRWRU5QTFZOQgpNUkV3RHdZRFZRUUtEQWhVVTFSRFR5MVRRVEVMTUFrR0ExVUVCaE1DVTBFd1ZqQVFCZ2NxaGtqT1BRSUJCZ1VyCmdRUUFDZ05DQUFRb1FycWxRRVg0VzY3Y0pQUkpVekdSTE5JNVpzRHdXbVNGVjNHUVZFcUVKK1dmbmtrVUxucHMKdlcxU2NrY0l2YmJLMk0vY1M1cHlyblZJbTZaWWtxSHhvSUhJTUlIRkJna3Foa2lHOXcwQkNRNHhnYmN3Z2JRdwpKQVlKS3dZQkJBR0NOeFFDQkJjVEZWUlRWRnBCVkVOQkxVTnZaR1V0VTJsbmJtbHVaekNCaXdZRFZSMFJCSUdECk1JR0FwSDR3ZkRFZE1Cc0dBMVVFQkF3VU1TMVRSRk5CZkRJdFJrZEVVM3d6TFZORVJrY3hIekFkQmdvSmtpYUoKay9Jc1pBRUJEQTh6TURBd01EQXdNREF3TURBd01ETXhEVEFMQmdOVkJBd01CREV4TURBeEVUQVBCZ05WQkJvTQpDRkpOVWtVeE1qTTBNUmd3RmdZRFZRUVBEQTlVY21GdWMzQnZjblJoZEdsdmJuTXdDZ1lJS29aSXpqMEVBd0lEClNRQXdSZ0loQVAwMks4L1VkcjF2Z2xQVGx0SHhxVEFCVWxiUVVZekxWQjZaYXlzdDU4WUFBaUVBblZMZWt5R3QKeDhhaVQzZ2Q2T3A5NmsxMkxSb1ZuSVFndVlSQS9md2FOdW89Ci0tLS0tRU5EIENFUlRJRklDQVRFIFJFUVVFU1QtLS0tLQo=\",\"configData\":\"DQogICAgICAgICAgICBvaWRfc2VjdGlvbiA9IE9JRHMNCiAgICAgICAgICAgIFsgT0lEcyBdDQogICAgICAgICAgICBjZXJ0aWZpY2F0ZVRlbXBsYXRlTmFtZT0gMS4zLjYuMS40LjEuMzExLjIwLjINCg0KICAgICAgICAgICAgWyByZXEgXQ0KICAgICAgICAgICAgZGVmYXVsdF9iaXRzIAk9IDIwNDgNCiAgICAgICAgICAgIGVtYWlsQWRkcmVzcyAJPSBlbWFpbEBnbWFpbC5jb20NCiAgICAgICAgICAgIHJlcV9leHRlbnNpb25zCT0gdjNfcmVxDQogICAgICAgICAgICB4NTA5X2V4dGVuc2lvbnMgCT0gdjNfY2ENCiAgICAgICAgICAgIHByb21wdCA9IG5vDQogICAgICAgICAgICBkZWZhdWx0X21kID0gc2hhMjU2DQogICAgICAgICAgICByZXFfZXh0ZW5zaW9ucyA9IHJlcV9leHQNCiAgICAgICAgICAgIGRpc3Rpbmd1aXNoZWRfbmFtZSA9IGRuDQoNCiAgICAgICAgICAgIFsgdjNfcmVxIF0NCiAgICAgICAgICAgIGJhc2ljQ29uc3RyYWludHMgPSBDQTpGQUxTRQ0KICAgICAgICAgICAga2V5VXNhZ2UgPSBkaWdpdGFsU2lnbmF0dXJlLCBub25SZXB1ZGlhdGlvbiwga2V5RW5jaXBoZXJtZW50DQoNCiAgICAgICAgICAgIFtyZXFfZXh0XQ0KICAgICAgICAgICAgY2VydGlmaWNhdGVUZW1wbGF0ZU5hbWUgPSBBU04xOlBSSU5UQUJMRVNUUklORzpUU1RaQVRDQS1Db2RlLVNpZ25pbmcNCiAgICAgICAgICAgIHN1YmplY3RBbHROYW1lID0gZGlyTmFtZTphbHRfbmFtZXMNCg0KICAgICAgICAgICAgWyB2M19jYSBdDQoNCiAgICAgICAgICAgICMgRXh0ZW5zaW9ucyBmb3IgYSB0eXBpY2FsIENBDQoNCiAgICAgICAgICAgICMgUEtJWCByZWNvbW1lbmRhdGlvbi4NCg0KICAgICAgICAgICAgc3ViamVjdEtleUlkZW50aWZpZXIgPSBoYXNoDQoNCiAgICAgICAgICAgIGF1dGhvcml0eUtleUlkZW50aWZpZXIgPSBrZXlpZDphbHdheXMsaXNzdWVyOmFsd2F5cw0KDQogICAgICAgICAgICBbIGRuIF0NCiAgICAgICAgICAgIENOID0gVFNUQ08gIAkJCQkgICAgICAgICAgICAgICAgICAgICMgQ29tbW9uIE5hbWUNCiAgICAgICAgICAgIEMgPSBTQQkJCQkJCQkgICAgICAgICAgICAjIENvdW50cnkgQ29kZSBlLmcgU0ENCiAgICAgICAgICAgIE9VID0gVFNUQ08tU0EJCQkJCQkJIyBPcmdhbml6YXRpb24gVW5pdCBOYW1lDQogICAgICAgICAgICBPID0gVFNUQ08tU0EJCQkJCQkJICAgICAgICAjIE9yZ2FuaXphdGlvbiBOYW1lDQoNCiAgICAgICAgICAgIFthbHRfbmFtZXNdDQogICAgICAgICAgICBTTiA9IDEtU0RTQXwyLUZHRFN8My1TREZHCQkJCSAgICAgICAgICAgICAgICAgICAgIyBFR1MgU2VyaWFsIE51bWJlciAxLUFCQ3wyLVBRUnwzLVhZWg0KICAgICAgICAgICAgVUlEID0gMzAwMDAwMDAwMDAwMDAzCQkJCQkJICAgICAgICAgICAgICAgICMgT3JnYW5pemF0aW9uIElkZW50aWZpZXIgKFZBVCBOdW1iZXIpDQogICAgICAgICAgICB0aXRsZSA9IDExMDAJCQkJCQkJCSAgICAjIEludm9pY2UgVHlwZQ0KICAgICAgICAgICAgcmVnaXN0ZXJlZEFkZHJlc3MgPSBSTVJFMTIzNCAgCSAJCQkjIEFkZHJlc3MNCiAgICAgICAgICAgIGJ1c2luZXNzQ2F0ZWdvcnkgPSBUcmFuc3BvcnRhdGlvbnMJCQkJCSMgQnVzaW5lc3MgQ2F0ZWdvcnkNCiAgICAgICAg\"}}', NULL, '2018-04-10 02:12:40', '2019-12-11 00:00:26'),
(3, 3, NULL, 'Ultimate Electronics', 'Linking Street', 'USA', 'Arizona', 'Phoenix', '492001', 3, NULL, 3, 3, NULL, 1, 'browser', NULL, '', '', '', NULL, NULL, 1, '{\"cash\":{\"is_enabled\":\"1\"},\"card\":{\"is_enabled\":\"1\"},\"cheque\":{\"is_enabled\":\"1\"},\"bank_transfer\":{\"is_enabled\":\"1\"},\"other\":{\"is_enabled\":\"1\"},\"custom_pay_1\":{\"is_enabled\":\"1\"},\"custom_pay_2\":{\"is_enabled\":\"1\"},\"custom_pay_3\":{\"is_enabled\":\"1\"}}', NULL, NULL, NULL, NULL, '{\"portal_mode\":\"developer-portal\",\"otp\":\"111222\",\"email\":\"email@gmail.com\",\"common_name\":\"TSTCO\",\"country_code\":\"SA\",\"organization_unit_name\":\"TSTCO-SA\",\"organization_name\":\"TSTCO-SA\",\"egs_serial_number\":\"1-SDSA|2-FGDS|3-SDFG\",\"vat_number\":\"300000000000003\",\"vat_name\":\"TSTCO VAT\",\"invoice_type\":\"1100\",\"registered_address\":\"RMRE1234\",\"business_category\":\"Transportations\",\"crn\":\"CRN123456\",\"street_name\":\"Main Street\",\"building_number\":\"123\",\"plot_identification\":\"Plot567\",\"sub_division_name\":\"Zone A\",\"city_name\":\"Riyadh\",\"postal_number\":\"11564\",\"country_name\":\"Saudi Arabia\"}', '{\"success\":true,\"message\":\"ISSUED\",\"data\":{\"complianceCertificate\":\"TUlJQjZUQ0NBWStnQXdJQkFnSUdBWldvbnlUcE1Bb0dDQ3FHU000OUJBTUNNQlV4RXpBUkJnTlZCQU1NQ21WSmJuWnZhV05wYm1jd0hoY05NalV3TXpFNE1Ea3pPVEV4V2hjTk16QXdNekUzTWpFd01EQXdXakJETVE0d0RBWURWUVFEREFWVVUxUkRUekVSTUE4R0ExVUVDd3dJVkZOVVEwOHRVMEV4RVRBUEJnTlZCQW9NQ0ZSVFZFTlBMVk5CTVFzd0NRWURWUVFHRXdKVFFUQldNQkFHQnlxR1NNNDlBZ0VHQlN1QkJBQUtBMElBQkNoQ3VxVkFSZmhicnR3azlFbFRNWkVzMGpsbXdQQmFaSVZYY1pCVVNvUW41WitlU1JRdWVteTliVkp5UndpOXRzcll6OXhMbW5LdWRVaWJwbGlTb2ZHamdaOHdnWnd3REFZRFZSMFRBUUgvQkFJd0FEQ0Jpd1lEVlIwUkJJR0RNSUdBcEg0d2ZERWRNQnNHQTFVRUJBd1VNUzFUUkZOQmZESXRSa2RFVTN3ekxWTkVSa2N4SHpBZEJnb0praWFKay9Jc1pBRUJEQTh6TURBd01EQXdNREF3TURBd01ETXhEVEFMQmdOVkJBd01CREV4TURBeEVUQVBCZ05WQkJvTUNGSk5Va1V4TWpNME1SZ3dGZ1lEVlFRUERBOVVjbUZ1YzNCdmNuUmhkR2x2Ym5Nd0NnWUlLb1pJemowRUF3SURTQUF3UlFJaEFKMmZTOUJSV2N1cVdlbUhlRzA2MjVHUUt4L3hFTE1vd2RwcWV2bXlZMUV2QWlBVXh1MkNQUVhSaEVseUxYa0ViWFQrOUE3bDZ5L09NTFM0cGY2RkRWQmJJUT09\",\"complianceSecret\":\"k7mFPUhn8BpsDt0UM2zwT83LeQm5ENIluKjhLecPBAE=\",\"complianceRequestID\":\"1234567890123\",\"productionCertificate\":\"TUlJRDNqQ0NBNFNnQXdJQkFnSVRFUUFBT0FQRjkwQWpzL3hjWHdBQkFBQTRBekFLQmdncWhrak9QUVFEQWpCaU1SVXdFd1lLQ1pJbWlaUHlMR1FCR1JZRmJHOWpZV3d4RXpBUkJnb0praWFKay9Jc1pBRVpGZ05uYjNZeEZ6QVZCZ29Ka2lhSmsvSXNaQUVaRmdkbGVIUm5ZWHAwTVJzd0dRWURWUVFERXhKUVVscEZTVTVXVDBsRFJWTkRRVFF0UTBFd0hoY05NalF3TVRFeE1Ea3hPVE13V2hjTk1qa3dNVEE1TURreE9UTXdXakIxTVFzd0NRWURWUVFHRXdKVFFURW1NQ1FHQTFVRUNoTWRUV0Y0YVcxMWJTQlRjR1ZsWkNCVVpXTm9JRk4xY0hCc2VTQk1WRVF4RmpBVUJnTlZCQXNURFZKcGVXRmthQ0JDY21GdVkyZ3hKakFrQmdOVkJBTVRIVlJUVkMwNE9EWTBNekV4TkRVdE16azVPVGs1T1RrNU9UQXdNREF6TUZZd0VBWUhLb1pJemowQ0FRWUZLNEVFQUFvRFFnQUVvV0NLYTBTYTlGSUVyVE92MHVBa0MxVklLWHhVOW5QcHgydmxmNHloTWVqeThjMDJYSmJsRHE3dFB5ZG84bXEwYWhPTW1Obzhnd25pN1h0MUtUOVVlS09DQWdjd2dnSURNSUd0QmdOVkhSRUVnYVV3Z2FLa2daOHdnWnd4T3pBNUJnTlZCQVFNTWpFdFZGTlVmREl0VkZOVWZETXRaV1F5TW1ZeFpEZ3RaVFpoTWkweE1URTRMVGxpTlRndFpEbGhPR1l4TVdVME5EVm1NUjh3SFFZS0NaSW1pWlB5TEdRQkFRd1BNems1T1RrNU9UazVPVEF3TURBek1RMHdDd1lEVlFRTURBUXhNVEF3TVJFd0R3WURWUVFhREFoU1VsSkVNamt5T1RFYU1CZ0dBMVVFRHd3UlUzVndjR3g1SUdGamRHbDJhWFJwWlhNd0hRWURWUjBPQkJZRUZFWCtZdm1tdG5Zb0RmOUJHYktvN29jVEtZSzFNQjhHQTFVZEl3UVlNQmFBRkp2S3FxTHRtcXdza0lGelZ2cFAyUHhUKzlObk1Ic0dDQ3NHQVFVRkJ3RUJCRzh3YlRCckJnZ3JCZ0VGQlFjd0FvWmZhSFIwY0RvdkwyRnBZVFF1ZW1GMFkyRXVaMjkyTG5OaEwwTmxjblJGYm5KdmJHd3ZVRkphUlVsdWRtOXBZMlZUUTBFMExtVjRkR2RoZW5RdVoyOTJMbXh2WTJGc1gxQlNXa1ZKVGxaUFNVTkZVME5CTkMxRFFTZ3hLUzVqY25Rd0RnWURWUjBQQVFIL0JBUURBZ2VBTUR3R0NTc0dBUVFCZ2pjVkJ3UXZNQzBHSlNzR0FRUUJnamNWQ0lHR3FCMkUwUHNTaHUyZEpJZk8reG5Ud0ZWbWgvcWxaWVhaaEQ0Q0FXUUNBUkl3SFFZRFZSMGxCQll3RkFZSUt3WUJCUVVIQXdNR0NDc0dBUVVGQndNQ01DY0dDU3NHQVFRQmdqY1ZDZ1FhTUJnd0NnWUlLd1lCQlFVSEF3TXdDZ1lJS3dZQkJRVUhBd0l3Q2dZSUtvWkl6ajBFQXdJRFNBQXdSUUloQUxFL2ljaG1uV1hDVUtVYmNhM3ljaThvcXdhTHZGZEhWalFydmVJOXVxQWJBaUE5aEM0TThqZ01CQURQU3ptZDJ1aVBKQTZnS1IzTEUwM1U3NWVxYkMvclhBPT0=\",\"productionCertificateSecret\":\"CkYsEXfV8c1gFHAtFWoZv73pGMvh\\/Qyo4LzKM2h\\/8Hg=\",\"productionCertificateRequestID\":\"30368\",\"privateKey\":\"LS0tLS1CRUdJTiBQUklWQVRFIEtFWS0tLS0tCk1JR0VBZ0VBTUJBR0J5cUdTTTQ5QWdFR0JTdUJCQUFLQkcwd2F3SUJBUVFnMGlkcmFmaDNPcHFyd012NXhhaW4KWFAwRldvamJWRUw0aFFCbG1hWnRuZXloUkFOQ0FBUW9RcnFsUUVYNFc2N2NKUFJKVXpHUkxOSTVac0R3V21TRgpWM0dRVkVxRUorV2Zua2tVTG5wc3ZXMVNja2NJdmJiSzJNL2NTNXB5cm5WSW02WllrcUh4Ci0tLS0tRU5EIFBSSVZBVEUgS0VZLS0tLS0K\",\"publicKey\":\"LS0tLS1CRUdJTiBQVUJMSUMgS0VZLS0tLS0KTUZZd0VBWUhLb1pJemowQ0FRWUZLNEVFQUFvRFFnQUVLRUs2cFVCRitGdXUzQ1QwU1ZNeGtTelNPV2JBOEZwawpoVmR4a0ZSS2hDZmxuNTVKRkM1NmJMMXRVbkpIQ0wyMnl0alAzRXVhY3E1MVNKdW1XSktoOFE9PQotLS0tLUVORCBQVUJMSUMgS0VZLS0tLS0K\",\"csrKey\":\"LS0tLS1CRUdJTiBDRVJUSUZJQ0FURSBSRVFVRVNULS0tLS0KTUlJQnhqQ0NBV3NDQVFBd1F6RU9NQXdHQTFVRUF3d0ZWRk5VUTA4eEVUQVBCZ05WQkFzTUNGUlRWRU5QTFZOQgpNUkV3RHdZRFZRUUtEQWhVVTFSRFR5MVRRVEVMTUFrR0ExVUVCaE1DVTBFd1ZqQVFCZ2NxaGtqT1BRSUJCZ1VyCmdRUUFDZ05DQUFRb1FycWxRRVg0VzY3Y0pQUkpVekdSTE5JNVpzRHdXbVNGVjNHUVZFcUVKK1dmbmtrVUxucHMKdlcxU2NrY0l2YmJLMk0vY1M1cHlyblZJbTZaWWtxSHhvSUhJTUlIRkJna3Foa2lHOXcwQkNRNHhnYmN3Z2JRdwpKQVlKS3dZQkJBR0NOeFFDQkJjVEZWUlRWRnBCVkVOQkxVTnZaR1V0VTJsbmJtbHVaekNCaXdZRFZSMFJCSUdECk1JR0FwSDR3ZkRFZE1Cc0dBMVVFQkF3VU1TMVRSRk5CZkRJdFJrZEVVM3d6TFZORVJrY3hIekFkQmdvSmtpYUoKay9Jc1pBRUJEQTh6TURBd01EQXdNREF3TURBd01ETXhEVEFMQmdOVkJBd01CREV4TURBeEVUQVBCZ05WQkJvTQpDRkpOVWtVeE1qTTBNUmd3RmdZRFZRUVBEQTlVY21GdWMzQnZjblJoZEdsdmJuTXdDZ1lJS29aSXpqMEVBd0lEClNRQXdSZ0loQVAwMks4L1VkcjF2Z2xQVGx0SHhxVEFCVWxiUVVZekxWQjZaYXlzdDU4WUFBaUVBblZMZWt5R3QKeDhhaVQzZ2Q2T3A5NmsxMkxSb1ZuSVFndVlSQS9md2FOdW89Ci0tLS0tRU5EIENFUlRJRklDQVRFIFJFUVVFU1QtLS0tLQo=\",\"configData\":\"DQogICAgICAgICAgICBvaWRfc2VjdGlvbiA9IE9JRHMNCiAgICAgICAgICAgIFsgT0lEcyBdDQogICAgICAgICAgICBjZXJ0aWZpY2F0ZVRlbXBsYXRlTmFtZT0gMS4zLjYuMS40LjEuMzExLjIwLjINCg0KICAgICAgICAgICAgWyByZXEgXQ0KICAgICAgICAgICAgZGVmYXVsdF9iaXRzIAk9IDIwNDgNCiAgICAgICAgICAgIGVtYWlsQWRkcmVzcyAJPSBlbWFpbEBnbWFpbC5jb20NCiAgICAgICAgICAgIHJlcV9leHRlbnNpb25zCT0gdjNfcmVxDQogICAgICAgICAgICB4NTA5X2V4dGVuc2lvbnMgCT0gdjNfY2ENCiAgICAgICAgICAgIHByb21wdCA9IG5vDQogICAgICAgICAgICBkZWZhdWx0X21kID0gc2hhMjU2DQogICAgICAgICAgICByZXFfZXh0ZW5zaW9ucyA9IHJlcV9leHQNCiAgICAgICAgICAgIGRpc3Rpbmd1aXNoZWRfbmFtZSA9IGRuDQoNCiAgICAgICAgICAgIFsgdjNfcmVxIF0NCiAgICAgICAgICAgIGJhc2ljQ29uc3RyYWludHMgPSBDQTpGQUxTRQ0KICAgICAgICAgICAga2V5VXNhZ2UgPSBkaWdpdGFsU2lnbmF0dXJlLCBub25SZXB1ZGlhdGlvbiwga2V5RW5jaXBoZXJtZW50DQoNCiAgICAgICAgICAgIFtyZXFfZXh0XQ0KICAgICAgICAgICAgY2VydGlmaWNhdGVUZW1wbGF0ZU5hbWUgPSBBU04xOlBSSU5UQUJMRVNUUklORzpUU1RaQVRDQS1Db2RlLVNpZ25pbmcNCiAgICAgICAgICAgIHN1YmplY3RBbHROYW1lID0gZGlyTmFtZTphbHRfbmFtZXMNCg0KICAgICAgICAgICAgWyB2M19jYSBdDQoNCiAgICAgICAgICAgICMgRXh0ZW5zaW9ucyBmb3IgYSB0eXBpY2FsIENBDQoNCiAgICAgICAgICAgICMgUEtJWCByZWNvbW1lbmRhdGlvbi4NCg0KICAgICAgICAgICAgc3ViamVjdEtleUlkZW50aWZpZXIgPSBoYXNoDQoNCiAgICAgICAgICAgIGF1dGhvcml0eUtleUlkZW50aWZpZXIgPSBrZXlpZDphbHdheXMsaXNzdWVyOmFsd2F5cw0KDQogICAgICAgICAgICBbIGRuIF0NCiAgICAgICAgICAgIENOID0gVFNUQ08gIAkJCQkgICAgICAgICAgICAgICAgICAgICMgQ29tbW9uIE5hbWUNCiAgICAgICAgICAgIEMgPSBTQQkJCQkJCQkgICAgICAgICAgICAjIENvdW50cnkgQ29kZSBlLmcgU0ENCiAgICAgICAgICAgIE9VID0gVFNUQ08tU0EJCQkJCQkJIyBPcmdhbml6YXRpb24gVW5pdCBOYW1lDQogICAgICAgICAgICBPID0gVFNUQ08tU0EJCQkJCQkJICAgICAgICAjIE9yZ2FuaXphdGlvbiBOYW1lDQoNCiAgICAgICAgICAgIFthbHRfbmFtZXNdDQogICAgICAgICAgICBTTiA9IDEtU0RTQXwyLUZHRFN8My1TREZHCQkJCSAgICAgICAgICAgICAgICAgICAgIyBFR1MgU2VyaWFsIE51bWJlciAxLUFCQ3wyLVBRUnwzLVhZWg0KICAgICAgICAgICAgVUlEID0gMzAwMDAwMDAwMDAwMDAzCQkJCQkJICAgICAgICAgICAgICAgICMgT3JnYW5pemF0aW9uIElkZW50aWZpZXIgKFZBVCBOdW1iZXIpDQogICAgICAgICAgICB0aXRsZSA9IDExMDAJCQkJCQkJCSAgICAjIEludm9pY2UgVHlwZQ0KICAgICAgICAgICAgcmVnaXN0ZXJlZEFkZHJlc3MgPSBSTVJFMTIzNCAgCSAJCQkjIEFkZHJlc3MNCiAgICAgICAgICAgIGJ1c2luZXNzQ2F0ZWdvcnkgPSBUcmFuc3BvcnRhdGlvbnMJCQkJCSMgQnVzaW5lc3MgQ2F0ZWdvcnkNCiAgICAgICAg\"}}', NULL, '2018-04-10 04:46:16', '2018-04-10 04:46:16'),
(4, 4, NULL, 'Awesome Services', 'Linking Street', 'USA', 'Arizona', 'Phoenix', '282001', 4, NULL, 4, 4, NULL, 1, 'browser', NULL, '', '', '', NULL, NULL, 1, '{\"cash\":{\"is_enabled\":\"1\"},\"card\":{\"is_enabled\":\"1\"},\"cheque\":{\"is_enabled\":\"1\"},\"bank_transfer\":{\"is_enabled\":\"1\"},\"other\":{\"is_enabled\":\"1\"},\"custom_pay_1\":{\"is_enabled\":\"1\"},\"custom_pay_2\":{\"is_enabled\":\"1\"},\"custom_pay_3\":{\"is_enabled\":\"1\"}}', NULL, NULL, NULL, NULL, '{\"portal_mode\":\"developer-portal\",\"otp\":\"111222\",\"email\":\"email@gmail.com\",\"common_name\":\"TSTCO\",\"country_code\":\"SA\",\"organization_unit_name\":\"TSTCO-SA\",\"organization_name\":\"TSTCO-SA\",\"egs_serial_number\":\"1-SDSA|2-FGDS|3-SDFG\",\"vat_number\":\"300000000000003\",\"vat_name\":\"TSTCO VAT\",\"invoice_type\":\"1100\",\"registered_address\":\"RMRE1234\",\"business_category\":\"Transportations\",\"crn\":\"CRN123456\",\"street_name\":\"Main Street\",\"building_number\":\"123\",\"plot_identification\":\"Plot567\",\"sub_division_name\":\"Zone A\",\"city_name\":\"Riyadh\",\"postal_number\":\"11564\",\"country_name\":\"Saudi Arabia\"}', '{\"success\":true,\"message\":\"ISSUED\",\"data\":{\"complianceCertificate\":\"TUlJQjZUQ0NBWStnQXdJQkFnSUdBWldvbnlUcE1Bb0dDQ3FHU000OUJBTUNNQlV4RXpBUkJnTlZCQU1NQ21WSmJuWnZhV05wYm1jd0hoY05NalV3TXpFNE1Ea3pPVEV4V2hjTk16QXdNekUzTWpFd01EQXdXakJETVE0d0RBWURWUVFEREFWVVUxUkRUekVSTUE4R0ExVUVDd3dJVkZOVVEwOHRVMEV4RVRBUEJnTlZCQW9NQ0ZSVFZFTlBMVk5CTVFzd0NRWURWUVFHRXdKVFFUQldNQkFHQnlxR1NNNDlBZ0VHQlN1QkJBQUtBMElBQkNoQ3VxVkFSZmhicnR3azlFbFRNWkVzMGpsbXdQQmFaSVZYY1pCVVNvUW41WitlU1JRdWVteTliVkp5UndpOXRzcll6OXhMbW5LdWRVaWJwbGlTb2ZHamdaOHdnWnd3REFZRFZSMFRBUUgvQkFJd0FEQ0Jpd1lEVlIwUkJJR0RNSUdBcEg0d2ZERWRNQnNHQTFVRUJBd1VNUzFUUkZOQmZESXRSa2RFVTN3ekxWTkVSa2N4SHpBZEJnb0praWFKay9Jc1pBRUJEQTh6TURBd01EQXdNREF3TURBd01ETXhEVEFMQmdOVkJBd01CREV4TURBeEVUQVBCZ05WQkJvTUNGSk5Va1V4TWpNME1SZ3dGZ1lEVlFRUERBOVVjbUZ1YzNCdmNuUmhkR2x2Ym5Nd0NnWUlLb1pJemowRUF3SURTQUF3UlFJaEFKMmZTOUJSV2N1cVdlbUhlRzA2MjVHUUt4L3hFTE1vd2RwcWV2bXlZMUV2QWlBVXh1MkNQUVhSaEVseUxYa0ViWFQrOUE3bDZ5L09NTFM0cGY2RkRWQmJJUT09\",\"complianceSecret\":\"k7mFPUhn8BpsDt0UM2zwT83LeQm5ENIluKjhLecPBAE=\",\"complianceRequestID\":\"1234567890123\",\"productionCertificate\":\"TUlJRDNqQ0NBNFNnQXdJQkFnSVRFUUFBT0FQRjkwQWpzL3hjWHdBQkFBQTRBekFLQmdncWhrak9QUVFEQWpCaU1SVXdFd1lLQ1pJbWlaUHlMR1FCR1JZRmJHOWpZV3d4RXpBUkJnb0praWFKay9Jc1pBRVpGZ05uYjNZeEZ6QVZCZ29Ka2lhSmsvSXNaQUVaRmdkbGVIUm5ZWHAwTVJzd0dRWURWUVFERXhKUVVscEZTVTVXVDBsRFJWTkRRVFF0UTBFd0hoY05NalF3TVRFeE1Ea3hPVE13V2hjTk1qa3dNVEE1TURreE9UTXdXakIxTVFzd0NRWURWUVFHRXdKVFFURW1NQ1FHQTFVRUNoTWRUV0Y0YVcxMWJTQlRjR1ZsWkNCVVpXTm9JRk4xY0hCc2VTQk1WRVF4RmpBVUJnTlZCQXNURFZKcGVXRmthQ0JDY21GdVkyZ3hKakFrQmdOVkJBTVRIVlJUVkMwNE9EWTBNekV4TkRVdE16azVPVGs1T1RrNU9UQXdNREF6TUZZd0VBWUhLb1pJemowQ0FRWUZLNEVFQUFvRFFnQUVvV0NLYTBTYTlGSUVyVE92MHVBa0MxVklLWHhVOW5QcHgydmxmNHloTWVqeThjMDJYSmJsRHE3dFB5ZG84bXEwYWhPTW1Obzhnd25pN1h0MUtUOVVlS09DQWdjd2dnSURNSUd0QmdOVkhSRUVnYVV3Z2FLa2daOHdnWnd4T3pBNUJnTlZCQVFNTWpFdFZGTlVmREl0VkZOVWZETXRaV1F5TW1ZeFpEZ3RaVFpoTWkweE1URTRMVGxpTlRndFpEbGhPR1l4TVdVME5EVm1NUjh3SFFZS0NaSW1pWlB5TEdRQkFRd1BNems1T1RrNU9UazVPVEF3TURBek1RMHdDd1lEVlFRTURBUXhNVEF3TVJFd0R3WURWUVFhREFoU1VsSkVNamt5T1RFYU1CZ0dBMVVFRHd3UlUzVndjR3g1SUdGamRHbDJhWFJwWlhNd0hRWURWUjBPQkJZRUZFWCtZdm1tdG5Zb0RmOUJHYktvN29jVEtZSzFNQjhHQTFVZEl3UVlNQmFBRkp2S3FxTHRtcXdza0lGelZ2cFAyUHhUKzlObk1Ic0dDQ3NHQVFVRkJ3RUJCRzh3YlRCckJnZ3JCZ0VGQlFjd0FvWmZhSFIwY0RvdkwyRnBZVFF1ZW1GMFkyRXVaMjkyTG5OaEwwTmxjblJGYm5KdmJHd3ZVRkphUlVsdWRtOXBZMlZUUTBFMExtVjRkR2RoZW5RdVoyOTJMbXh2WTJGc1gxQlNXa1ZKVGxaUFNVTkZVME5CTkMxRFFTZ3hLUzVqY25Rd0RnWURWUjBQQVFIL0JBUURBZ2VBTUR3R0NTc0dBUVFCZ2pjVkJ3UXZNQzBHSlNzR0FRUUJnamNWQ0lHR3FCMkUwUHNTaHUyZEpJZk8reG5Ud0ZWbWgvcWxaWVhaaEQ0Q0FXUUNBUkl3SFFZRFZSMGxCQll3RkFZSUt3WUJCUVVIQXdNR0NDc0dBUVVGQndNQ01DY0dDU3NHQVFRQmdqY1ZDZ1FhTUJnd0NnWUlLd1lCQlFVSEF3TXdDZ1lJS3dZQkJRVUhBd0l3Q2dZSUtvWkl6ajBFQXdJRFNBQXdSUUloQUxFL2ljaG1uV1hDVUtVYmNhM3ljaThvcXdhTHZGZEhWalFydmVJOXVxQWJBaUE5aEM0TThqZ01CQURQU3ptZDJ1aVBKQTZnS1IzTEUwM1U3NWVxYkMvclhBPT0=\",\"productionCertificateSecret\":\"CkYsEXfV8c1gFHAtFWoZv73pGMvh\\/Qyo4LzKM2h\\/8Hg=\",\"productionCertificateRequestID\":\"30368\",\"privateKey\":\"LS0tLS1CRUdJTiBQUklWQVRFIEtFWS0tLS0tCk1JR0VBZ0VBTUJBR0J5cUdTTTQ5QWdFR0JTdUJCQUFLQkcwd2F3SUJBUVFnMGlkcmFmaDNPcHFyd012NXhhaW4KWFAwRldvamJWRUw0aFFCbG1hWnRuZXloUkFOQ0FBUW9RcnFsUUVYNFc2N2NKUFJKVXpHUkxOSTVac0R3V21TRgpWM0dRVkVxRUorV2Zua2tVTG5wc3ZXMVNja2NJdmJiSzJNL2NTNXB5cm5WSW02WllrcUh4Ci0tLS0tRU5EIFBSSVZBVEUgS0VZLS0tLS0K\",\"publicKey\":\"LS0tLS1CRUdJTiBQVUJMSUMgS0VZLS0tLS0KTUZZd0VBWUhLb1pJemowQ0FRWUZLNEVFQUFvRFFnQUVLRUs2cFVCRitGdXUzQ1QwU1ZNeGtTelNPV2JBOEZwawpoVmR4a0ZSS2hDZmxuNTVKRkM1NmJMMXRVbkpIQ0wyMnl0alAzRXVhY3E1MVNKdW1XSktoOFE9PQotLS0tLUVORCBQVUJMSUMgS0VZLS0tLS0K\",\"csrKey\":\"LS0tLS1CRUdJTiBDRVJUSUZJQ0FURSBSRVFVRVNULS0tLS0KTUlJQnhqQ0NBV3NDQVFBd1F6RU9NQXdHQTFVRUF3d0ZWRk5VUTA4eEVUQVBCZ05WQkFzTUNGUlRWRU5QTFZOQgpNUkV3RHdZRFZRUUtEQWhVVTFSRFR5MVRRVEVMTUFrR0ExVUVCaE1DVTBFd1ZqQVFCZ2NxaGtqT1BRSUJCZ1VyCmdRUUFDZ05DQUFRb1FycWxRRVg0VzY3Y0pQUkpVekdSTE5JNVpzRHdXbVNGVjNHUVZFcUVKK1dmbmtrVUxucHMKdlcxU2NrY0l2YmJLMk0vY1M1cHlyblZJbTZaWWtxSHhvSUhJTUlIRkJna3Foa2lHOXcwQkNRNHhnYmN3Z2JRdwpKQVlKS3dZQkJBR0NOeFFDQkJjVEZWUlRWRnBCVkVOQkxVTnZaR1V0VTJsbmJtbHVaekNCaXdZRFZSMFJCSUdECk1JR0FwSDR3ZkRFZE1Cc0dBMVVFQkF3VU1TMVRSRk5CZkRJdFJrZEVVM3d6TFZORVJrY3hIekFkQmdvSmtpYUoKay9Jc1pBRUJEQTh6TURBd01EQXdNREF3TURBd01ETXhEVEFMQmdOVkJBd01CREV4TURBeEVUQVBCZ05WQkJvTQpDRkpOVWtVeE1qTTBNUmd3RmdZRFZRUVBEQTlVY21GdWMzQnZjblJoZEdsdmJuTXdDZ1lJS29aSXpqMEVBd0lEClNRQXdSZ0loQVAwMks4L1VkcjF2Z2xQVGx0SHhxVEFCVWxiUVVZekxWQjZaYXlzdDU4WUFBaUVBblZMZWt5R3QKeDhhaVQzZ2Q2T3A5NmsxMkxSb1ZuSVFndVlSQS9md2FOdW89Ci0tLS0tRU5EIENFUlRJRklDQVRFIFJFUVVFU1QtLS0tLQo=\",\"configData\":\"DQogICAgICAgICAgICBvaWRfc2VjdGlvbiA9IE9JRHMNCiAgICAgICAgICAgIFsgT0lEcyBdDQogICAgICAgICAgICBjZXJ0aWZpY2F0ZVRlbXBsYXRlTmFtZT0gMS4zLjYuMS40LjEuMzExLjIwLjINCg0KICAgICAgICAgICAgWyByZXEgXQ0KICAgICAgICAgICAgZGVmYXVsdF9iaXRzIAk9IDIwNDgNCiAgICAgICAgICAgIGVtYWlsQWRkcmVzcyAJPSBlbWFpbEBnbWFpbC5jb20NCiAgICAgICAgICAgIHJlcV9leHRlbnNpb25zCT0gdjNfcmVxDQogICAgICAgICAgICB4NTA5X2V4dGVuc2lvbnMgCT0gdjNfY2ENCiAgICAgICAgICAgIHByb21wdCA9IG5vDQogICAgICAgICAgICBkZWZhdWx0X21kID0gc2hhMjU2DQogICAgICAgICAgICByZXFfZXh0ZW5zaW9ucyA9IHJlcV9leHQNCiAgICAgICAgICAgIGRpc3Rpbmd1aXNoZWRfbmFtZSA9IGRuDQoNCiAgICAgICAgICAgIFsgdjNfcmVxIF0NCiAgICAgICAgICAgIGJhc2ljQ29uc3RyYWludHMgPSBDQTpGQUxTRQ0KICAgICAgICAgICAga2V5VXNhZ2UgPSBkaWdpdGFsU2lnbmF0dXJlLCBub25SZXB1ZGlhdGlvbiwga2V5RW5jaXBoZXJtZW50DQoNCiAgICAgICAgICAgIFtyZXFfZXh0XQ0KICAgICAgICAgICAgY2VydGlmaWNhdGVUZW1wbGF0ZU5hbWUgPSBBU04xOlBSSU5UQUJMRVNUUklORzpUU1RaQVRDQS1Db2RlLVNpZ25pbmcNCiAgICAgICAgICAgIHN1YmplY3RBbHROYW1lID0gZGlyTmFtZTphbHRfbmFtZXMNCg0KICAgICAgICAgICAgWyB2M19jYSBdDQoNCiAgICAgICAgICAgICMgRXh0ZW5zaW9ucyBmb3IgYSB0eXBpY2FsIENBDQoNCiAgICAgICAgICAgICMgUEtJWCByZWNvbW1lbmRhdGlvbi4NCg0KICAgICAgICAgICAgc3ViamVjdEtleUlkZW50aWZpZXIgPSBoYXNoDQoNCiAgICAgICAgICAgIGF1dGhvcml0eUtleUlkZW50aWZpZXIgPSBrZXlpZDphbHdheXMsaXNzdWVyOmFsd2F5cw0KDQogICAgICAgICAgICBbIGRuIF0NCiAgICAgICAgICAgIENOID0gVFNUQ08gIAkJCQkgICAgICAgICAgICAgICAgICAgICMgQ29tbW9uIE5hbWUNCiAgICAgICAgICAgIEMgPSBTQQkJCQkJCQkgICAgICAgICAgICAjIENvdW50cnkgQ29kZSBlLmcgU0ENCiAgICAgICAgICAgIE9VID0gVFNUQ08tU0EJCQkJCQkJIyBPcmdhbml6YXRpb24gVW5pdCBOYW1lDQogICAgICAgICAgICBPID0gVFNUQ08tU0EJCQkJCQkJICAgICAgICAjIE9yZ2FuaXphdGlvbiBOYW1lDQoNCiAgICAgICAgICAgIFthbHRfbmFtZXNdDQogICAgICAgICAgICBTTiA9IDEtU0RTQXwyLUZHRFN8My1TREZHCQkJCSAgICAgICAgICAgICAgICAgICAgIyBFR1MgU2VyaWFsIE51bWJlciAxLUFCQ3wyLVBRUnwzLVhZWg0KICAgICAgICAgICAgVUlEID0gMzAwMDAwMDAwMDAwMDAzCQkJCQkJICAgICAgICAgICAgICAgICMgT3JnYW5pemF0aW9uIElkZW50aWZpZXIgKFZBVCBOdW1iZXIpDQogICAgICAgICAgICB0aXRsZSA9IDExMDAJCQkJCQkJCSAgICAjIEludm9pY2UgVHlwZQ0KICAgICAgICAgICAgcmVnaXN0ZXJlZEFkZHJlc3MgPSBSTVJFMTIzNCAgCSAJCQkjIEFkZHJlc3MNCiAgICAgICAgICAgIGJ1c2luZXNzQ2F0ZWdvcnkgPSBUcmFuc3BvcnRhdGlvbnMJCQkJCSMgQnVzaW5lc3MgQ2F0ZWdvcnkNCiAgICAgICAg\"}}', NULL, '2018-04-10 06:20:43', '2018-04-10 06:20:43'),
(5, 5, NULL, 'Awesome Restaurant', 'Linking Street', 'USA', 'Arizona', 'Phoenix', '85001', 5, NULL, 5, 5, NULL, 1, 'browser', NULL, '', '', '', NULL, NULL, 1, '{\"cash\":{\"is_enabled\":\"1\"},\"card\":{\"is_enabled\":\"1\"},\"cheque\":{\"is_enabled\":\"1\"},\"bank_transfer\":{\"is_enabled\":\"1\"},\"other\":{\"is_enabled\":\"1\"},\"custom_pay_1\":{\"is_enabled\":\"1\"},\"custom_pay_2\":{\"is_enabled\":\"1\"},\"custom_pay_3\":{\"is_enabled\":\"1\"}}', NULL, NULL, NULL, NULL, '{\"portal_mode\":\"developer-portal\",\"otp\":\"111222\",\"email\":\"email@gmail.com\",\"common_name\":\"TSTCO\",\"country_code\":\"SA\",\"organization_unit_name\":\"TSTCO-SA\",\"organization_name\":\"TSTCO-SA\",\"egs_serial_number\":\"1-SDSA|2-FGDS|3-SDFG\",\"vat_number\":\"300000000000003\",\"vat_name\":\"TSTCO VAT\",\"invoice_type\":\"1100\",\"registered_address\":\"RMRE1234\",\"business_category\":\"Transportations\",\"crn\":\"CRN123456\",\"street_name\":\"Main Street\",\"building_number\":\"123\",\"plot_identification\":\"Plot567\",\"sub_division_name\":\"Zone A\",\"city_name\":\"Riyadh\",\"postal_number\":\"11564\",\"country_name\":\"Saudi Arabia\"}', '{\"success\":true,\"message\":\"ISSUED\",\"data\":{\"complianceCertificate\":\"TUlJQjZUQ0NBWStnQXdJQkFnSUdBWldvbnlUcE1Bb0dDQ3FHU000OUJBTUNNQlV4RXpBUkJnTlZCQU1NQ21WSmJuWnZhV05wYm1jd0hoY05NalV3TXpFNE1Ea3pPVEV4V2hjTk16QXdNekUzTWpFd01EQXdXakJETVE0d0RBWURWUVFEREFWVVUxUkRUekVSTUE4R0ExVUVDd3dJVkZOVVEwOHRVMEV4RVRBUEJnTlZCQW9NQ0ZSVFZFTlBMVk5CTVFzd0NRWURWUVFHRXdKVFFUQldNQkFHQnlxR1NNNDlBZ0VHQlN1QkJBQUtBMElBQkNoQ3VxVkFSZmhicnR3azlFbFRNWkVzMGpsbXdQQmFaSVZYY1pCVVNvUW41WitlU1JRdWVteTliVkp5UndpOXRzcll6OXhMbW5LdWRVaWJwbGlTb2ZHamdaOHdnWnd3REFZRFZSMFRBUUgvQkFJd0FEQ0Jpd1lEVlIwUkJJR0RNSUdBcEg0d2ZERWRNQnNHQTFVRUJBd1VNUzFUUkZOQmZESXRSa2RFVTN3ekxWTkVSa2N4SHpBZEJnb0praWFKay9Jc1pBRUJEQTh6TURBd01EQXdNREF3TURBd01ETXhEVEFMQmdOVkJBd01CREV4TURBeEVUQVBCZ05WQkJvTUNGSk5Va1V4TWpNME1SZ3dGZ1lEVlFRUERBOVVjbUZ1YzNCdmNuUmhkR2x2Ym5Nd0NnWUlLb1pJemowRUF3SURTQUF3UlFJaEFKMmZTOUJSV2N1cVdlbUhlRzA2MjVHUUt4L3hFTE1vd2RwcWV2bXlZMUV2QWlBVXh1MkNQUVhSaEVseUxYa0ViWFQrOUE3bDZ5L09NTFM0cGY2RkRWQmJJUT09\",\"complianceSecret\":\"k7mFPUhn8BpsDt0UM2zwT83LeQm5ENIluKjhLecPBAE=\",\"complianceRequestID\":\"1234567890123\",\"productionCertificate\":\"TUlJRDNqQ0NBNFNnQXdJQkFnSVRFUUFBT0FQRjkwQWpzL3hjWHdBQkFBQTRBekFLQmdncWhrak9QUVFEQWpCaU1SVXdFd1lLQ1pJbWlaUHlMR1FCR1JZRmJHOWpZV3d4RXpBUkJnb0praWFKay9Jc1pBRVpGZ05uYjNZeEZ6QVZCZ29Ka2lhSmsvSXNaQUVaRmdkbGVIUm5ZWHAwTVJzd0dRWURWUVFERXhKUVVscEZTVTVXVDBsRFJWTkRRVFF0UTBFd0hoY05NalF3TVRFeE1Ea3hPVE13V2hjTk1qa3dNVEE1TURreE9UTXdXakIxTVFzd0NRWURWUVFHRXdKVFFURW1NQ1FHQTFVRUNoTWRUV0Y0YVcxMWJTQlRjR1ZsWkNCVVpXTm9JRk4xY0hCc2VTQk1WRVF4RmpBVUJnTlZCQXNURFZKcGVXRmthQ0JDY21GdVkyZ3hKakFrQmdOVkJBTVRIVlJUVkMwNE9EWTBNekV4TkRVdE16azVPVGs1T1RrNU9UQXdNREF6TUZZd0VBWUhLb1pJemowQ0FRWUZLNEVFQUFvRFFnQUVvV0NLYTBTYTlGSUVyVE92MHVBa0MxVklLWHhVOW5QcHgydmxmNHloTWVqeThjMDJYSmJsRHE3dFB5ZG84bXEwYWhPTW1Obzhnd25pN1h0MUtUOVVlS09DQWdjd2dnSURNSUd0QmdOVkhSRUVnYVV3Z2FLa2daOHdnWnd4T3pBNUJnTlZCQVFNTWpFdFZGTlVmREl0VkZOVWZETXRaV1F5TW1ZeFpEZ3RaVFpoTWkweE1URTRMVGxpTlRndFpEbGhPR1l4TVdVME5EVm1NUjh3SFFZS0NaSW1pWlB5TEdRQkFRd1BNems1T1RrNU9UazVPVEF3TURBek1RMHdDd1lEVlFRTURBUXhNVEF3TVJFd0R3WURWUVFhREFoU1VsSkVNamt5T1RFYU1CZ0dBMVVFRHd3UlUzVndjR3g1SUdGamRHbDJhWFJwWlhNd0hRWURWUjBPQkJZRUZFWCtZdm1tdG5Zb0RmOUJHYktvN29jVEtZSzFNQjhHQTFVZEl3UVlNQmFBRkp2S3FxTHRtcXdza0lGelZ2cFAyUHhUKzlObk1Ic0dDQ3NHQVFVRkJ3RUJCRzh3YlRCckJnZ3JCZ0VGQlFjd0FvWmZhSFIwY0RvdkwyRnBZVFF1ZW1GMFkyRXVaMjkyTG5OaEwwTmxjblJGYm5KdmJHd3ZVRkphUlVsdWRtOXBZMlZUUTBFMExtVjRkR2RoZW5RdVoyOTJMbXh2WTJGc1gxQlNXa1ZKVGxaUFNVTkZVME5CTkMxRFFTZ3hLUzVqY25Rd0RnWURWUjBQQVFIL0JBUURBZ2VBTUR3R0NTc0dBUVFCZ2pjVkJ3UXZNQzBHSlNzR0FRUUJnamNWQ0lHR3FCMkUwUHNTaHUyZEpJZk8reG5Ud0ZWbWgvcWxaWVhaaEQ0Q0FXUUNBUkl3SFFZRFZSMGxCQll3RkFZSUt3WUJCUVVIQXdNR0NDc0dBUVVGQndNQ01DY0dDU3NHQVFRQmdqY1ZDZ1FhTUJnd0NnWUlLd1lCQlFVSEF3TXdDZ1lJS3dZQkJRVUhBd0l3Q2dZSUtvWkl6ajBFQXdJRFNBQXdSUUloQUxFL2ljaG1uV1hDVUtVYmNhM3ljaThvcXdhTHZGZEhWalFydmVJOXVxQWJBaUE5aEM0TThqZ01CQURQU3ptZDJ1aVBKQTZnS1IzTEUwM1U3NWVxYkMvclhBPT0=\",\"productionCertificateSecret\":\"CkYsEXfV8c1gFHAtFWoZv73pGMvh\\/Qyo4LzKM2h\\/8Hg=\",\"productionCertificateRequestID\":\"30368\",\"privateKey\":\"LS0tLS1CRUdJTiBQUklWQVRFIEtFWS0tLS0tCk1JR0VBZ0VBTUJBR0J5cUdTTTQ5QWdFR0JTdUJCQUFLQkcwd2F3SUJBUVFnMGlkcmFmaDNPcHFyd012NXhhaW4KWFAwRldvamJWRUw0aFFCbG1hWnRuZXloUkFOQ0FBUW9RcnFsUUVYNFc2N2NKUFJKVXpHUkxOSTVac0R3V21TRgpWM0dRVkVxRUorV2Zua2tVTG5wc3ZXMVNja2NJdmJiSzJNL2NTNXB5cm5WSW02WllrcUh4Ci0tLS0tRU5EIFBSSVZBVEUgS0VZLS0tLS0K\",\"publicKey\":\"LS0tLS1CRUdJTiBQVUJMSUMgS0VZLS0tLS0KTUZZd0VBWUhLb1pJemowQ0FRWUZLNEVFQUFvRFFnQUVLRUs2cFVCRitGdXUzQ1QwU1ZNeGtTelNPV2JBOEZwawpoVmR4a0ZSS2hDZmxuNTVKRkM1NmJMMXRVbkpIQ0wyMnl0alAzRXVhY3E1MVNKdW1XSktoOFE9PQotLS0tLUVORCBQVUJMSUMgS0VZLS0tLS0K\",\"csrKey\":\"LS0tLS1CRUdJTiBDRVJUSUZJQ0FURSBSRVFVRVNULS0tLS0KTUlJQnhqQ0NBV3NDQVFBd1F6RU9NQXdHQTFVRUF3d0ZWRk5VUTA4eEVUQVBCZ05WQkFzTUNGUlRWRU5QTFZOQgpNUkV3RHdZRFZRUUtEQWhVVTFSRFR5MVRRVEVMTUFrR0ExVUVCaE1DVTBFd1ZqQVFCZ2NxaGtqT1BRSUJCZ1VyCmdRUUFDZ05DQUFRb1FycWxRRVg0VzY3Y0pQUkpVekdSTE5JNVpzRHdXbVNGVjNHUVZFcUVKK1dmbmtrVUxucHMKdlcxU2NrY0l2YmJLMk0vY1M1cHlyblZJbTZaWWtxSHhvSUhJTUlIRkJna3Foa2lHOXcwQkNRNHhnYmN3Z2JRdwpKQVlKS3dZQkJBR0NOeFFDQkJjVEZWUlRWRnBCVkVOQkxVTnZaR1V0VTJsbmJtbHVaekNCaXdZRFZSMFJCSUdECk1JR0FwSDR3ZkRFZE1Cc0dBMVVFQkF3VU1TMVRSRk5CZkRJdFJrZEVVM3d6TFZORVJrY3hIekFkQmdvSmtpYUoKay9Jc1pBRUJEQTh6TURBd01EQXdNREF3TURBd01ETXhEVEFMQmdOVkJBd01CREV4TURBeEVUQVBCZ05WQkJvTQpDRkpOVWtVeE1qTTBNUmd3RmdZRFZRUVBEQTlVY21GdWMzQnZjblJoZEdsdmJuTXdDZ1lJS29aSXpqMEVBd0lEClNRQXdSZ0loQVAwMks4L1VkcjF2Z2xQVGx0SHhxVEFCVWxiUVVZekxWQjZaYXlzdDU4WUFBaUVBblZMZWt5R3QKeDhhaVQzZ2Q2T3A5NmsxMkxSb1ZuSVFndVlSQS9md2FOdW89Ci0tLS0tRU5EIENFUlRJRklDQVRFIFJFUVVFU1QtLS0tLQo=\",\"configData\":\"DQogICAgICAgICAgICBvaWRfc2VjdGlvbiA9IE9JRHMNCiAgICAgICAgICAgIFsgT0lEcyBdDQogICAgICAgICAgICBjZXJ0aWZpY2F0ZVRlbXBsYXRlTmFtZT0gMS4zLjYuMS40LjEuMzExLjIwLjINCg0KICAgICAgICAgICAgWyByZXEgXQ0KICAgICAgICAgICAgZGVmYXVsdF9iaXRzIAk9IDIwNDgNCiAgICAgICAgICAgIGVtYWlsQWRkcmVzcyAJPSBlbWFpbEBnbWFpbC5jb20NCiAgICAgICAgICAgIHJlcV9leHRlbnNpb25zCT0gdjNfcmVxDQogICAgICAgICAgICB4NTA5X2V4dGVuc2lvbnMgCT0gdjNfY2ENCiAgICAgICAgICAgIHByb21wdCA9IG5vDQogICAgICAgICAgICBkZWZhdWx0X21kID0gc2hhMjU2DQogICAgICAgICAgICByZXFfZXh0ZW5zaW9ucyA9IHJlcV9leHQNCiAgICAgICAgICAgIGRpc3Rpbmd1aXNoZWRfbmFtZSA9IGRuDQoNCiAgICAgICAgICAgIFsgdjNfcmVxIF0NCiAgICAgICAgICAgIGJhc2ljQ29uc3RyYWludHMgPSBDQTpGQUxTRQ0KICAgICAgICAgICAga2V5VXNhZ2UgPSBkaWdpdGFsU2lnbmF0dXJlLCBub25SZXB1ZGlhdGlvbiwga2V5RW5jaXBoZXJtZW50DQoNCiAgICAgICAgICAgIFtyZXFfZXh0XQ0KICAgICAgICAgICAgY2VydGlmaWNhdGVUZW1wbGF0ZU5hbWUgPSBBU04xOlBSSU5UQUJMRVNUUklORzpUU1RaQVRDQS1Db2RlLVNpZ25pbmcNCiAgICAgICAgICAgIHN1YmplY3RBbHROYW1lID0gZGlyTmFtZTphbHRfbmFtZXMNCg0KICAgICAgICAgICAgWyB2M19jYSBdDQoNCiAgICAgICAgICAgICMgRXh0ZW5zaW9ucyBmb3IgYSB0eXBpY2FsIENBDQoNCiAgICAgICAgICAgICMgUEtJWCByZWNvbW1lbmRhdGlvbi4NCg0KICAgICAgICAgICAgc3ViamVjdEtleUlkZW50aWZpZXIgPSBoYXNoDQoNCiAgICAgICAgICAgIGF1dGhvcml0eUtleUlkZW50aWZpZXIgPSBrZXlpZDphbHdheXMsaXNzdWVyOmFsd2F5cw0KDQogICAgICAgICAgICBbIGRuIF0NCiAgICAgICAgICAgIENOID0gVFNUQ08gIAkJCQkgICAgICAgICAgICAgICAgICAgICMgQ29tbW9uIE5hbWUNCiAgICAgICAgICAgIEMgPSBTQQkJCQkJCQkgICAgICAgICAgICAjIENvdW50cnkgQ29kZSBlLmcgU0ENCiAgICAgICAgICAgIE9VID0gVFNUQ08tU0EJCQkJCQkJIyBPcmdhbml6YXRpb24gVW5pdCBOYW1lDQogICAgICAgICAgICBPID0gVFNUQ08tU0EJCQkJCQkJICAgICAgICAjIE9yZ2FuaXphdGlvbiBOYW1lDQoNCiAgICAgICAgICAgIFthbHRfbmFtZXNdDQogICAgICAgICAgICBTTiA9IDEtU0RTQXwyLUZHRFN8My1TREZHCQkJCSAgICAgICAgICAgICAgICAgICAgIyBFR1MgU2VyaWFsIE51bWJlciAxLUFCQ3wyLVBRUnwzLVhZWg0KICAgICAgICAgICAgVUlEID0gMzAwMDAwMDAwMDAwMDAzCQkJCQkJICAgICAgICAgICAgICAgICMgT3JnYW5pemF0aW9uIElkZW50aWZpZXIgKFZBVCBOdW1iZXIpDQogICAgICAgICAgICB0aXRsZSA9IDExMDAJCQkJCQkJCSAgICAjIEludm9pY2UgVHlwZQ0KICAgICAgICAgICAgcmVnaXN0ZXJlZEFkZHJlc3MgPSBSTVJFMTIzNCAgCSAJCQkjIEFkZHJlc3MNCiAgICAgICAgICAgIGJ1c2luZXNzQ2F0ZWdvcnkgPSBUcmFuc3BvcnRhdGlvbnMJCQkJCSMgQnVzaW5lc3MgQ2F0ZWdvcnkNCiAgICAgICAg\"}}', NULL, '2018-07-13 05:12:11', '2018-07-13 05:12:11'),
(6, 6, NULL, 'Awesome Manufacturers', 'Infront of XYZ', 'USA', 'S', 'C', 'Z', 6, NULL, 6, 6, NULL, 1, 'browser', NULL, NULL, NULL, NULL, NULL, NULL, 1, '{\"cash\":{\"is_enabled\":\"1\"},\"card\":{\"is_enabled\":\"1\"},\"cheque\":{\"is_enabled\":\"1\"},\"bank_transfer\":{\"is_enabled\":\"1\"},\"other\":{\"is_enabled\":\"1\"},\"custom_pay_1\":{\"is_enabled\":\"1\"},\"custom_pay_2\":{\"is_enabled\":\"1\"},\"custom_pay_3\":{\"is_enabled\":\"1\"}}', NULL, NULL, NULL, NULL, '{\"portal_mode\":\"developer-portal\",\"otp\":\"111222\",\"email\":\"email@gmail.com\",\"common_name\":\"TSTCO\",\"country_code\":\"SA\",\"organization_unit_name\":\"TSTCO-SA\",\"organization_name\":\"TSTCO-SA\",\"egs_serial_number\":\"1-SDSA|2-FGDS|3-SDFG\",\"vat_number\":\"300000000000003\",\"vat_name\":\"TSTCO VAT\",\"invoice_type\":\"1100\",\"registered_address\":\"RMRE1234\",\"business_category\":\"Transportations\",\"crn\":\"CRN123456\",\"street_name\":\"Main Street\",\"building_number\":\"123\",\"plot_identification\":\"Plot567\",\"sub_division_name\":\"Zone A\",\"city_name\":\"Riyadh\",\"postal_number\":\"11564\",\"country_name\":\"Saudi Arabia\"}', '{\"success\":true,\"message\":\"ISSUED\",\"data\":{\"complianceCertificate\":\"TUlJQjZUQ0NBWStnQXdJQkFnSUdBWldvbnlUcE1Bb0dDQ3FHU000OUJBTUNNQlV4RXpBUkJnTlZCQU1NQ21WSmJuWnZhV05wYm1jd0hoY05NalV3TXpFNE1Ea3pPVEV4V2hjTk16QXdNekUzTWpFd01EQXdXakJETVE0d0RBWURWUVFEREFWVVUxUkRUekVSTUE4R0ExVUVDd3dJVkZOVVEwOHRVMEV4RVRBUEJnTlZCQW9NQ0ZSVFZFTlBMVk5CTVFzd0NRWURWUVFHRXdKVFFUQldNQkFHQnlxR1NNNDlBZ0VHQlN1QkJBQUtBMElBQkNoQ3VxVkFSZmhicnR3azlFbFRNWkVzMGpsbXdQQmFaSVZYY1pCVVNvUW41WitlU1JRdWVteTliVkp5UndpOXRzcll6OXhMbW5LdWRVaWJwbGlTb2ZHamdaOHdnWnd3REFZRFZSMFRBUUgvQkFJd0FEQ0Jpd1lEVlIwUkJJR0RNSUdBcEg0d2ZERWRNQnNHQTFVRUJBd1VNUzFUUkZOQmZESXRSa2RFVTN3ekxWTkVSa2N4SHpBZEJnb0praWFKay9Jc1pBRUJEQTh6TURBd01EQXdNREF3TURBd01ETXhEVEFMQmdOVkJBd01CREV4TURBeEVUQVBCZ05WQkJvTUNGSk5Va1V4TWpNME1SZ3dGZ1lEVlFRUERBOVVjbUZ1YzNCdmNuUmhkR2x2Ym5Nd0NnWUlLb1pJemowRUF3SURTQUF3UlFJaEFKMmZTOUJSV2N1cVdlbUhlRzA2MjVHUUt4L3hFTE1vd2RwcWV2bXlZMUV2QWlBVXh1MkNQUVhSaEVseUxYa0ViWFQrOUE3bDZ5L09NTFM0cGY2RkRWQmJJUT09\",\"complianceSecret\":\"k7mFPUhn8BpsDt0UM2zwT83LeQm5ENIluKjhLecPBAE=\",\"complianceRequestID\":\"1234567890123\",\"productionCertificate\":\"TUlJRDNqQ0NBNFNnQXdJQkFnSVRFUUFBT0FQRjkwQWpzL3hjWHdBQkFBQTRBekFLQmdncWhrak9QUVFEQWpCaU1SVXdFd1lLQ1pJbWlaUHlMR1FCR1JZRmJHOWpZV3d4RXpBUkJnb0praWFKay9Jc1pBRVpGZ05uYjNZeEZ6QVZCZ29Ka2lhSmsvSXNaQUVaRmdkbGVIUm5ZWHAwTVJzd0dRWURWUVFERXhKUVVscEZTVTVXVDBsRFJWTkRRVFF0UTBFd0hoY05NalF3TVRFeE1Ea3hPVE13V2hjTk1qa3dNVEE1TURreE9UTXdXakIxTVFzd0NRWURWUVFHRXdKVFFURW1NQ1FHQTFVRUNoTWRUV0Y0YVcxMWJTQlRjR1ZsWkNCVVpXTm9JRk4xY0hCc2VTQk1WRVF4RmpBVUJnTlZCQXNURFZKcGVXRmthQ0JDY21GdVkyZ3hKakFrQmdOVkJBTVRIVlJUVkMwNE9EWTBNekV4TkRVdE16azVPVGs1T1RrNU9UQXdNREF6TUZZd0VBWUhLb1pJemowQ0FRWUZLNEVFQUFvRFFnQUVvV0NLYTBTYTlGSUVyVE92MHVBa0MxVklLWHhVOW5QcHgydmxmNHloTWVqeThjMDJYSmJsRHE3dFB5ZG84bXEwYWhPTW1Obzhnd25pN1h0MUtUOVVlS09DQWdjd2dnSURNSUd0QmdOVkhSRUVnYVV3Z2FLa2daOHdnWnd4T3pBNUJnTlZCQVFNTWpFdFZGTlVmREl0VkZOVWZETXRaV1F5TW1ZeFpEZ3RaVFpoTWkweE1URTRMVGxpTlRndFpEbGhPR1l4TVdVME5EVm1NUjh3SFFZS0NaSW1pWlB5TEdRQkFRd1BNems1T1RrNU9UazVPVEF3TURBek1RMHdDd1lEVlFRTURBUXhNVEF3TVJFd0R3WURWUVFhREFoU1VsSkVNamt5T1RFYU1CZ0dBMVVFRHd3UlUzVndjR3g1SUdGamRHbDJhWFJwWlhNd0hRWURWUjBPQkJZRUZFWCtZdm1tdG5Zb0RmOUJHYktvN29jVEtZSzFNQjhHQTFVZEl3UVlNQmFBRkp2S3FxTHRtcXdza0lGelZ2cFAyUHhUKzlObk1Ic0dDQ3NHQVFVRkJ3RUJCRzh3YlRCckJnZ3JCZ0VGQlFjd0FvWmZhSFIwY0RvdkwyRnBZVFF1ZW1GMFkyRXVaMjkyTG5OaEwwTmxjblJGYm5KdmJHd3ZVRkphUlVsdWRtOXBZMlZUUTBFMExtVjRkR2RoZW5RdVoyOTJMbXh2WTJGc1gxQlNXa1ZKVGxaUFNVTkZVME5CTkMxRFFTZ3hLUzVqY25Rd0RnWURWUjBQQVFIL0JBUURBZ2VBTUR3R0NTc0dBUVFCZ2pjVkJ3UXZNQzBHSlNzR0FRUUJnamNWQ0lHR3FCMkUwUHNTaHUyZEpJZk8reG5Ud0ZWbWgvcWxaWVhaaEQ0Q0FXUUNBUkl3SFFZRFZSMGxCQll3RkFZSUt3WUJCUVVIQXdNR0NDc0dBUVVGQndNQ01DY0dDU3NHQVFRQmdqY1ZDZ1FhTUJnd0NnWUlLd1lCQlFVSEF3TXdDZ1lJS3dZQkJRVUhBd0l3Q2dZSUtvWkl6ajBFQXdJRFNBQXdSUUloQUxFL2ljaG1uV1hDVUtVYmNhM3ljaThvcXdhTHZGZEhWalFydmVJOXVxQWJBaUE5aEM0TThqZ01CQURQU3ptZDJ1aVBKQTZnS1IzTEUwM1U3NWVxYkMvclhBPT0=\",\"productionCertificateSecret\":\"CkYsEXfV8c1gFHAtFWoZv73pGMvh\\/Qyo4LzKM2h\\/8Hg=\",\"productionCertificateRequestID\":\"30368\",\"privateKey\":\"LS0tLS1CRUdJTiBQUklWQVRFIEtFWS0tLS0tCk1JR0VBZ0VBTUJBR0J5cUdTTTQ5QWdFR0JTdUJCQUFLQkcwd2F3SUJBUVFnMGlkcmFmaDNPcHFyd012NXhhaW4KWFAwRldvamJWRUw0aFFCbG1hWnRuZXloUkFOQ0FBUW9RcnFsUUVYNFc2N2NKUFJKVXpHUkxOSTVac0R3V21TRgpWM0dRVkVxRUorV2Zua2tVTG5wc3ZXMVNja2NJdmJiSzJNL2NTNXB5cm5WSW02WllrcUh4Ci0tLS0tRU5EIFBSSVZBVEUgS0VZLS0tLS0K\",\"publicKey\":\"LS0tLS1CRUdJTiBQVUJMSUMgS0VZLS0tLS0KTUZZd0VBWUhLb1pJemowQ0FRWUZLNEVFQUFvRFFnQUVLRUs2cFVCRitGdXUzQ1QwU1ZNeGtTelNPV2JBOEZwawpoVmR4a0ZSS2hDZmxuNTVKRkM1NmJMMXRVbkpIQ0wyMnl0alAzRXVhY3E1MVNKdW1XSktoOFE9PQotLS0tLUVORCBQVUJMSUMgS0VZLS0tLS0K\",\"csrKey\":\"LS0tLS1CRUdJTiBDRVJUSUZJQ0FURSBSRVFVRVNULS0tLS0KTUlJQnhqQ0NBV3NDQVFBd1F6RU9NQXdHQTFVRUF3d0ZWRk5VUTA4eEVUQVBCZ05WQkFzTUNGUlRWRU5QTFZOQgpNUkV3RHdZRFZRUUtEQWhVVTFSRFR5MVRRVEVMTUFrR0ExVUVCaE1DVTBFd1ZqQVFCZ2NxaGtqT1BRSUJCZ1VyCmdRUUFDZ05DQUFRb1FycWxRRVg0VzY3Y0pQUkpVekdSTE5JNVpzRHdXbVNGVjNHUVZFcUVKK1dmbmtrVUxucHMKdlcxU2NrY0l2YmJLMk0vY1M1cHlyblZJbTZaWWtxSHhvSUhJTUlIRkJna3Foa2lHOXcwQkNRNHhnYmN3Z2JRdwpKQVlKS3dZQkJBR0NOeFFDQkJjVEZWUlRWRnBCVkVOQkxVTnZaR1V0VTJsbmJtbHVaekNCaXdZRFZSMFJCSUdECk1JR0FwSDR3ZkRFZE1Cc0dBMVVFQkF3VU1TMVRSRk5CZkRJdFJrZEVVM3d6TFZORVJrY3hIekFkQmdvSmtpYUoKay9Jc1pBRUJEQTh6TURBd01EQXdNREF3TURBd01ETXhEVEFMQmdOVkJBd01CREV4TURBeEVUQVBCZ05WQkJvTQpDRkpOVWtVeE1qTTBNUmd3RmdZRFZRUVBEQTlVY21GdWMzQnZjblJoZEdsdmJuTXdDZ1lJS29aSXpqMEVBd0lEClNRQXdSZ0loQVAwMks4L1VkcjF2Z2xQVGx0SHhxVEFCVWxiUVVZekxWQjZaYXlzdDU4WUFBaUVBblZMZWt5R3QKeDhhaVQzZ2Q2T3A5NmsxMkxSb1ZuSVFndVlSQS9md2FOdW89Ci0tLS0tRU5EIENFUlRJRklDQVRFIFJFUVVFU1QtLS0tLQo=\",\"configData\":\"DQogICAgICAgICAgICBvaWRfc2VjdGlvbiA9IE9JRHMNCiAgICAgICAgICAgIFsgT0lEcyBdDQogICAgICAgICAgICBjZXJ0aWZpY2F0ZVRlbXBsYXRlTmFtZT0gMS4zLjYuMS40LjEuMzExLjIwLjINCg0KICAgICAgICAgICAgWyByZXEgXQ0KICAgICAgICAgICAgZGVmYXVsdF9iaXRzIAk9IDIwNDgNCiAgICAgICAgICAgIGVtYWlsQWRkcmVzcyAJPSBlbWFpbEBnbWFpbC5jb20NCiAgICAgICAgICAgIHJlcV9leHRlbnNpb25zCT0gdjNfcmVxDQogICAgICAgICAgICB4NTA5X2V4dGVuc2lvbnMgCT0gdjNfY2ENCiAgICAgICAgICAgIHByb21wdCA9IG5vDQogICAgICAgICAgICBkZWZhdWx0X21kID0gc2hhMjU2DQogICAgICAgICAgICByZXFfZXh0ZW5zaW9ucyA9IHJlcV9leHQNCiAgICAgICAgICAgIGRpc3Rpbmd1aXNoZWRfbmFtZSA9IGRuDQoNCiAgICAgICAgICAgIFsgdjNfcmVxIF0NCiAgICAgICAgICAgIGJhc2ljQ29uc3RyYWludHMgPSBDQTpGQUxTRQ0KICAgICAgICAgICAga2V5VXNhZ2UgPSBkaWdpdGFsU2lnbmF0dXJlLCBub25SZXB1ZGlhdGlvbiwga2V5RW5jaXBoZXJtZW50DQoNCiAgICAgICAgICAgIFtyZXFfZXh0XQ0KICAgICAgICAgICAgY2VydGlmaWNhdGVUZW1wbGF0ZU5hbWUgPSBBU04xOlBSSU5UQUJMRVNUUklORzpUU1RaQVRDQS1Db2RlLVNpZ25pbmcNCiAgICAgICAgICAgIHN1YmplY3RBbHROYW1lID0gZGlyTmFtZTphbHRfbmFtZXMNCg0KICAgICAgICAgICAgWyB2M19jYSBdDQoNCiAgICAgICAgICAgICMgRXh0ZW5zaW9ucyBmb3IgYSB0eXBpY2FsIENBDQoNCiAgICAgICAgICAgICMgUEtJWCByZWNvbW1lbmRhdGlvbi4NCg0KICAgICAgICAgICAgc3ViamVjdEtleUlkZW50aWZpZXIgPSBoYXNoDQoNCiAgICAgICAgICAgIGF1dGhvcml0eUtleUlkZW50aWZpZXIgPSBrZXlpZDphbHdheXMsaXNzdWVyOmFsd2F5cw0KDQogICAgICAgICAgICBbIGRuIF0NCiAgICAgICAgICAgIENOID0gVFNUQ08gIAkJCQkgICAgICAgICAgICAgICAgICAgICMgQ29tbW9uIE5hbWUNCiAgICAgICAgICAgIEMgPSBTQQkJCQkJCQkgICAgICAgICAgICAjIENvdW50cnkgQ29kZSBlLmcgU0ENCiAgICAgICAgICAgIE9VID0gVFNUQ08tU0EJCQkJCQkJIyBPcmdhbml6YXRpb24gVW5pdCBOYW1lDQogICAgICAgICAgICBPID0gVFNUQ08tU0EJCQkJCQkJICAgICAgICAjIE9yZ2FuaXphdGlvbiBOYW1lDQoNCiAgICAgICAgICAgIFthbHRfbmFtZXNdDQogICAgICAgICAgICBTTiA9IDEtU0RTQXwyLUZHRFN8My1TREZHCQkJCSAgICAgICAgICAgICAgICAgICAgIyBFR1MgU2VyaWFsIE51bWJlciAxLUFCQ3wyLVBRUnwzLVhZWg0KICAgICAgICAgICAgVUlEID0gMzAwMDAwMDAwMDAwMDAzCQkJCQkJICAgICAgICAgICAgICAgICMgT3JnYW5pemF0aW9uIElkZW50aWZpZXIgKFZBVCBOdW1iZXIpDQogICAgICAgICAgICB0aXRsZSA9IDExMDAJCQkJCQkJCSAgICAjIEludm9pY2UgVHlwZQ0KICAgICAgICAgICAgcmVnaXN0ZXJlZEFkZHJlc3MgPSBSTVJFMTIzNCAgCSAJCQkjIEFkZHJlc3MNCiAgICAgICAgICAgIGJ1c2luZXNzQ2F0ZWdvcnkgPSBUcmFuc3BvcnRhdGlvbnMJCQkJCSMgQnVzaW5lc3MgQ2F0ZWdvcnkNCiAgICAgICAg\"}}', NULL, '2019-08-18 07:11:28', '2019-12-11 11:36:49');

-- --------------------------------------------------------

--
-- Table structure for table `cash_denominations`
--

CREATE TABLE `cash_denominations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `business_id` int(11) NOT NULL,
  `amount` decimal(22,4) NOT NULL,
  `total_count` int(11) NOT NULL,
  `model_type` varchar(191) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cash_registers`
--

CREATE TABLE `cash_registers` (
  `id` int(10) UNSIGNED NOT NULL,
  `business_id` int(10) UNSIGNED NOT NULL,
  `location_id` int(11) DEFAULT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `status` enum('close','open') NOT NULL DEFAULT 'open',
  `closed_at` datetime DEFAULT NULL,
  `closing_amount` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `total_card_slips` int(11) NOT NULL DEFAULT 0,
  `total_cheques` int(11) NOT NULL DEFAULT 0,
  `denominations` text DEFAULT NULL,
  `closing_note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cash_register_transactions`
--

CREATE TABLE `cash_register_transactions` (
  `id` int(10) UNSIGNED NOT NULL,
  `cash_register_id` int(10) UNSIGNED NOT NULL,
  `amount` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `pay_method` varchar(191) DEFAULT NULL,
  `type` enum('debit','credit') NOT NULL,
  `transaction_type` varchar(191) DEFAULT NULL,
  `transaction_id` int(11) DEFAULT NULL,
  `transaction_payment_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cash_register_transactions`
--

INSERT INTO `cash_register_transactions` (`id`, `cash_register_id`, `amount`, `pay_method`, `type`, `transaction_type`, `transaction_id`, `transaction_payment_id`, `created_at`, `updated_at`) VALUES
(1, 1, 0.0000, 'cash', 'credit', 'initial', NULL, NULL, '2018-07-13 01:39:34', '2018-07-13 01:39:34'),
(2, 1, 42.5000, 'cash', 'credit', 'sell', 41, NULL, '2018-07-13 01:44:40', '2018-07-13 01:44:40'),
(3, 1, 0.0000, 'cash', 'credit', 'sell', 41, NULL, '2018-07-13 01:44:40', '2018-07-13 01:44:40'),
(4, 1, 77.5000, 'cash', 'credit', 'sell', 42, NULL, '2018-07-13 01:45:29', '2018-07-13 01:45:29'),
(5, 1, 0.0000, 'cash', 'credit', 'sell', 42, NULL, '2018-07-13 01:45:29', '2018-07-13 01:45:29'),
(6, 1, 33.7500, 'cash', 'credit', 'sell', 43, NULL, '2018-07-13 01:46:04', '2018-07-13 01:46:04'),
(7, 1, 0.0000, 'cash', 'credit', 'sell', 43, NULL, '2018-07-13 01:46:04', '2018-07-13 01:46:04'),
(8, 1, 71.2500, 'cash', 'credit', 'sell', 44, NULL, '2018-07-13 01:46:36', '2018-07-13 01:46:36'),
(9, 1, 0.0000, 'cash', 'credit', 'sell', 44, NULL, '2018-07-13 01:46:36', '2018-07-13 01:46:36');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `business_id` int(10) UNSIGNED NOT NULL,
  `short_code` varchar(191) DEFAULT NULL,
  `parent_id` int(11) NOT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `category_type` varchar(191) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `slug` varchar(191) DEFAULT NULL,
  `woocommerce_cat_id` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `business_id`, `short_code`, `parent_id`, `created_by`, `category_type`, `description`, `slug`, `woocommerce_cat_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Men\'s', 1, NULL, 0, 1, 'product', NULL, NULL, NULL, NULL, '2018-01-03 15:06:34', '2018-01-03 15:06:34'),
(2, 'Women\'s', 1, NULL, 0, 1, 'product', NULL, NULL, NULL, NULL, '2018-01-03 15:06:46', '2018-01-03 15:06:46'),
(3, 'Accessories', 1, NULL, 0, 1, 'product', NULL, NULL, NULL, NULL, '2018-01-03 15:07:03', '2018-01-03 15:07:03'),
(4, 'Jeans', 1, NULL, 1, 1, 'product', NULL, NULL, NULL, NULL, '2018-01-03 15:07:34', '2018-01-03 15:07:34'),
(5, 'Shirts', 1, NULL, 1, 1, 'product', NULL, NULL, NULL, NULL, '2018-01-03 15:08:18', '2018-01-03 15:08:18'),
(6, 'Belts', 1, NULL, 3, 1, 'product', NULL, NULL, NULL, NULL, '2018-01-03 15:08:41', '2018-01-03 15:08:41'),
(8, 'Shoes', 1, NULL, 3, 1, 'product', NULL, NULL, NULL, NULL, '2018-01-03 15:09:04', '2018-01-03 15:09:04'),
(10, 'Sandal', 1, NULL, 3, 1, 'product', NULL, NULL, NULL, NULL, '2018-01-03 15:09:23', '2018-01-03 15:09:23'),
(11, 'Wallets', 1, NULL, 3, 1, 'product', NULL, NULL, NULL, NULL, '2018-01-03 17:05:50', '2018-01-03 17:05:50'),
(12, 'Electronics', 1, NULL, 0, 1, 'product', NULL, NULL, NULL, NULL, '2018-01-05 23:24:34', '2018-01-05 23:24:34'),
(13, 'Cell Phones', 1, NULL, 12, 1, 'product', NULL, NULL, NULL, NULL, '2018-01-05 23:24:57', '2018-01-05 23:24:57'),
(14, 'Computers', 1, NULL, 12, 1, 'product', NULL, NULL, NULL, NULL, '2018-01-05 23:25:55', '2018-01-05 23:25:55'),
(15, 'Sports', 1, NULL, 0, 1, 'product', NULL, NULL, NULL, NULL, '2018-01-05 23:27:33', '2018-01-05 23:27:33'),
(16, 'Athletic Clothing', 1, NULL, 15, 1, 'product', NULL, NULL, NULL, NULL, '2018-01-05 23:28:40', '2018-01-05 23:28:40'),
(17, 'Exercise & Fitness', 1, NULL, 15, 1, 'product', NULL, NULL, NULL, NULL, '2018-01-05 23:29:19', '2018-01-05 23:29:19'),
(18, 'Books', 1, NULL, 0, 1, 'product', NULL, NULL, NULL, NULL, '2018-01-05 23:29:59', '2018-01-05 23:29:59'),
(19, 'Autobiography', 1, NULL, 18, 1, 'product', NULL, NULL, NULL, NULL, '2018-01-05 23:30:16', '2018-01-05 23:30:16'),
(20, 'Children\'s books', 1, NULL, 18, 1, 'product', NULL, NULL, NULL, NULL, '2018-01-05 23:30:58', '2018-01-05 23:30:58'),
(21, 'Food & Grocery', 1, NULL, 0, 1, 'product', NULL, NULL, NULL, NULL, '2018-01-05 23:31:35', '2018-01-05 23:31:35'),
(22, 'Capsule', 2, NULL, 0, 4, 'product', NULL, NULL, NULL, NULL, '2018-04-10 02:19:58', '2018-04-10 02:20:54'),
(23, 'Bandages', 2, NULL, 0, 4, 'product', NULL, NULL, NULL, NULL, '2018-04-10 02:20:48', '2018-04-10 02:20:48'),
(24, 'Cream', 2, NULL, 0, 4, 'product', NULL, NULL, NULL, NULL, '2018-04-10 02:21:23', '2018-04-10 02:21:23'),
(25, 'Drops', 2, NULL, 0, 4, 'product', NULL, NULL, NULL, NULL, '2018-04-10 02:21:31', '2018-04-10 02:21:31'),
(26, 'Gel', 2, NULL, 0, 4, 'product', NULL, NULL, NULL, NULL, '2018-04-10 02:21:57', '2018-04-10 02:21:57'),
(27, 'Bottle', 2, NULL, 0, 4, 'product', NULL, NULL, NULL, NULL, '2018-04-10 02:22:30', '2018-04-10 02:22:30'),
(28, 'Mobile Phones', 3, NULL, 0, 5, 'product', NULL, NULL, NULL, NULL, '2018-04-10 04:59:49', '2018-04-10 04:59:49'),
(29, 'Tablets', 3, NULL, 0, 5, 'product', NULL, NULL, NULL, NULL, '2018-04-10 05:00:02', '2018-04-10 05:00:02'),
(30, 'Laptops', 3, NULL, 0, 5, 'product', NULL, NULL, NULL, NULL, '2018-04-10 05:00:09', '2018-04-10 05:00:09'),
(31, 'TVs', 3, NULL, 0, 5, 'product', NULL, NULL, NULL, NULL, '2018-04-10 05:00:15', '2018-04-10 05:00:15'),
(32, 'Cameras', 3, NULL, 0, 5, 'product', NULL, NULL, NULL, NULL, '2018-04-10 05:00:29', '2018-04-10 05:00:29'),
(33, 'Accessories', 3, NULL, 0, 5, 'product', NULL, NULL, NULL, NULL, '2018-04-10 05:00:57', '2018-04-10 05:00:57'),
(34, 'Car Services', 4, NULL, 0, 6, 'product', NULL, NULL, NULL, NULL, '2018-04-10 06:25:33', '2018-04-10 06:30:10'),
(35, 'Hair Styling', 4, NULL, 0, 6, 'product', NULL, NULL, NULL, NULL, '2018-04-10 06:28:32', '2018-04-10 06:29:18'),
(36, 'Plumbing', 4, NULL, 0, 6, 'product', NULL, NULL, NULL, NULL, '2018-04-10 06:30:41', '2018-04-10 06:30:41'),
(37, 'Bodycare', 4, NULL, 0, 6, 'product', NULL, NULL, NULL, NULL, '2018-04-10 06:30:52', '2018-04-10 06:30:52'),
(38, 'Spa', 4, NULL, 0, 6, 'product', NULL, NULL, NULL, NULL, '2018-04-10 06:31:01', '2018-04-10 06:31:01'),
(39, 'Starter', 5, NULL, 0, 7, 'product', NULL, NULL, NULL, NULL, '2018-07-12 18:49:08', '2018-07-12 18:49:08'),
(40, 'Main Menu', 5, NULL, 0, 7, 'product', NULL, NULL, NULL, NULL, '2018-07-12 18:49:24', '2018-07-12 18:49:24'),
(41, 'Salad', 5, NULL, 0, 7, 'product', NULL, NULL, NULL, NULL, '2018-07-12 18:50:15', '2018-07-12 18:50:15'),
(42, 'Pizza', 5, NULL, 0, 7, 'product', NULL, NULL, NULL, NULL, '2018-07-12 18:50:29', '2018-07-12 18:50:29'),
(43, 'Desserts', 5, NULL, 0, 7, 'product', NULL, NULL, NULL, NULL, '2018-07-12 18:50:40', '2018-07-12 18:50:40'),
(44, 'Drinks', 5, NULL, 0, 7, 'product', NULL, NULL, NULL, NULL, '2018-07-12 18:50:54', '2018-07-12 18:50:54'),
(45, 'Bakery and Snacks', 5, NULL, 0, 7, 'product', NULL, NULL, NULL, NULL, '2018-07-12 19:58:40', '2018-07-12 19:58:40'),
(46, 'Laptop', 4, NULL, 0, 1, 'device', 'Laptop', NULL, NULL, NULL, '2020-05-07 15:04:38', '2020-05-07 15:04:38'),
(47, 'Desktop', 4, NULL, 0, 1, 'device', 'Desktop', NULL, NULL, NULL, '2020-05-07 15:04:47', '2020-05-07 15:04:47'),
(48, 'Mobile', 4, NULL, 0, 1, 'device', 'Mobile', NULL, NULL, NULL, '2020-05-07 15:05:01', '2020-05-07 15:05:01'),
(49, 'Free WiFi', 1, NULL, 0, 1, 'amenities', 'Complimentary wireless internet access', NULL, NULL, NULL, '2026-06-28 08:37:29', '2026-06-28 08:37:29'),
(50, 'Breakfast Included', 1, NULL, 0, 1, 'amenities', 'Buffet breakfast included with stay', NULL, NULL, NULL, '2026-06-28 08:37:29', '2026-06-28 08:37:29'),
(51, 'Free Parking', 1, NULL, 0, 1, 'amenities', 'On-site parking at no extra cost', NULL, NULL, NULL, '2026-06-28 08:37:29', '2026-06-28 08:37:29');

-- --------------------------------------------------------

--
-- Table structure for table `categorizables`
--

CREATE TABLE `categorizables` (
  `category_id` int(11) NOT NULL,
  `categorizable_type` varchar(191) NOT NULL,
  `categorizable_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(10) UNSIGNED NOT NULL,
  `business_id` int(10) UNSIGNED NOT NULL,
  `type` varchar(191) NOT NULL,
  `contact_type` varchar(191) DEFAULT NULL,
  `land_mark` varchar(191) DEFAULT NULL,
  `street_name` varchar(191) DEFAULT NULL,
  `building_number` varchar(191) DEFAULT NULL,
  `additional_number` varchar(191) DEFAULT NULL,
  `supplier_business_name` varchar(191) DEFAULT NULL,
  `name` varchar(191) DEFAULT NULL,
  `prefix` varchar(191) DEFAULT NULL,
  `first_name` varchar(191) DEFAULT NULL,
  `middle_name` varchar(191) DEFAULT NULL,
  `last_name` varchar(191) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `contact_id` varchar(191) DEFAULT NULL,
  `contact_status` varchar(191) NOT NULL DEFAULT 'active',
  `tax_number` varchar(191) DEFAULT NULL,
  `city` varchar(191) DEFAULT NULL,
  `state` varchar(191) DEFAULT NULL,
  `country` varchar(191) DEFAULT NULL,
  `address_line_1` text DEFAULT NULL,
  `address_line_2` text DEFAULT NULL,
  `zip_code` varchar(191) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `mobile` varchar(191) NOT NULL,
  `landline` varchar(191) DEFAULT NULL,
  `alternate_number` varchar(191) DEFAULT NULL,
  `pay_term_number` int(11) DEFAULT NULL,
  `pay_term_type` enum('days','months') DEFAULT NULL,
  `credit_limit` decimal(22,4) DEFAULT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `balance` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `total_rp` int(11) NOT NULL DEFAULT 0 COMMENT 'rp is the short form of reward points',
  `total_rp_used` int(11) NOT NULL DEFAULT 0 COMMENT 'rp is the short form of reward points',
  `total_rp_expired` int(11) NOT NULL DEFAULT 0 COMMENT 'rp is the short form of reward points',
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `shipping_address` text DEFAULT NULL,
  `shipping_custom_field_details` longtext DEFAULT NULL,
  `is_export` tinyint(1) NOT NULL DEFAULT 0,
  `export_custom_field_1` varchar(191) DEFAULT NULL,
  `export_custom_field_2` varchar(191) DEFAULT NULL,
  `export_custom_field_3` varchar(191) DEFAULT NULL,
  `export_custom_field_4` varchar(191) DEFAULT NULL,
  `export_custom_field_5` varchar(191) DEFAULT NULL,
  `export_custom_field_6` varchar(191) DEFAULT NULL,
  `position` varchar(191) DEFAULT NULL,
  `customer_group_id` int(11) DEFAULT NULL,
  `custom_field1` varchar(191) DEFAULT NULL,
  `custom_field2` varchar(191) DEFAULT NULL,
  `custom_field3` varchar(191) DEFAULT NULL,
  `custom_field4` varchar(191) DEFAULT NULL,
  `custom_field5` varchar(191) DEFAULT NULL,
  `custom_field6` varchar(191) DEFAULT NULL,
  `custom_field7` varchar(191) DEFAULT NULL,
  `custom_field8` varchar(191) DEFAULT NULL,
  `custom_field9` varchar(191) DEFAULT NULL,
  `custom_field10` varchar(191) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `business_id`, `type`, `contact_type`, `land_mark`, `street_name`, `building_number`, `additional_number`, `supplier_business_name`, `name`, `prefix`, `first_name`, `middle_name`, `last_name`, `email`, `contact_id`, `contact_status`, `tax_number`, `city`, `state`, `country`, `address_line_1`, `address_line_2`, `zip_code`, `dob`, `mobile`, `landline`, `alternate_number`, `pay_term_number`, `pay_term_type`, `credit_limit`, `created_by`, `balance`, `total_rp`, `total_rp_used`, `total_rp_expired`, `is_default`, `shipping_address`, `shipping_custom_field_details`, `is_export`, `export_custom_field_1`, `export_custom_field_2`, `export_custom_field_3`, `export_custom_field_4`, `export_custom_field_5`, `export_custom_field_6`, `position`, `customer_group_id`, `custom_field1`, `custom_field2`, `custom_field3`, `custom_field4`, `custom_field5`, `custom_field6`, `custom_field7`, `custom_field8`, `custom_field9`, `custom_field10`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'customer', NULL, NULL, NULL, NULL, NULL, NULL, 'Walk-In Customer', NULL, 'Walk-In Customer', NULL, NULL, NULL, 'CO0005', 'active', NULL, 'Phoenix', 'Arizona', 'USA', 'Linking Street', NULL, NULL, NULL, '(378) 400-1234', NULL, NULL, NULL, NULL, NULL, 1, 0.0000, 0, 0, 0, 1, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-01-03 14:45:20', '2018-06-11 16:22:05'),
(2, 1, 'supplier', NULL, NULL, NULL, NULL, NULL, 'Alpha Clothings', 'Michael', NULL, 'Michael', NULL, NULL, NULL, 'CO0001', 'active', '4590091535', 'Phoenix', 'Arizona', 'USA', 'Linking Street', NULL, NULL, NULL, '(378) 400-1234', NULL, NULL, 15, 'days', NULL, 1, 0.0000, 0, 0, 0, 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-01-03 14:59:38', '2018-06-11 16:21:03'),
(3, 1, 'supplier', NULL, NULL, NULL, NULL, NULL, 'Manhattan Clothing Ltd.', 'Philip', NULL, 'Philip', NULL, NULL, NULL, 'CO0003', 'active', '54869310093', 'Phoenix', 'Arizona', 'USA', 'Linking Street', NULL, NULL, NULL, '(378) 400-1234', NULL, NULL, 15, 'days', NULL, 1, 0.0000, 0, 0, 0, 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-01-03 15:00:55', '2018-06-11 16:21:36'),
(4, 1, 'customer', NULL, NULL, NULL, NULL, NULL, NULL, 'Harry', NULL, 'Harry', NULL, NULL, NULL, 'CO0006', 'active', NULL, 'Phoenix', 'Arizona', 'USA', 'Linking Street', NULL, NULL, NULL, '(378) 400-1234', NULL, NULL, NULL, NULL, NULL, 1, 0.0000, 0, 0, 0, 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-01-03 15:01:40', '2018-06-11 16:22:16'),
(5, 1, 'supplier', NULL, NULL, NULL, NULL, NULL, 'Digital Ocean', 'Mike McCubbin', NULL, 'Mike McCubbin', NULL, NULL, NULL, 'CN0004', 'active', '52965489001', 'Phoenix', 'Arizona', 'USA', 'Linking Street', NULL, NULL, NULL, '(378) 400-1234', NULL, NULL, 30, 'days', NULL, 1, 0.0000, 0, 0, 0, 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-01-06 00:53:22', '2018-06-11 16:21:47'),
(6, 1, 'supplier', NULL, NULL, NULL, NULL, NULL, 'Univer Suppliers', 'Jackson Hill', NULL, 'Jackson Hill', NULL, NULL, NULL, 'CO0002', 'active', '5459000655', 'Phoenix', 'Arizona', 'USA', 'Linking Street', NULL, NULL, NULL, '(378) 400-1234', NULL, NULL, 45, 'days', NULL, 1, 0.0000, 0, 0, 0, 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-01-06 00:55:09', '2018-06-11 16:21:18'),
(7, 2, 'customer', NULL, NULL, NULL, NULL, NULL, NULL, 'Walk-In Customer', NULL, 'Walk-In Customer', NULL, NULL, NULL, NULL, 'active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, 4, 0.0000, 0, 0, 0, 1, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-04-10 02:12:40', '2018-04-10 02:12:40'),
(8, 2, 'supplier', NULL, NULL, NULL, NULL, NULL, 'Jones Pharma', 'Micheal Larson', NULL, 'Micheal Larson', NULL, NULL, NULL, '522500003', 'active', NULL, 'Phoenix', 'Arizona', 'USA', NULL, NULL, NULL, NULL, '(378) 400-1234', NULL, NULL, 30, 'days', NULL, 4, 0.0000, 0, 0, 0, 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-04-10 04:02:52', '2018-04-10 04:02:52'),
(9, 2, 'supplier', NULL, NULL, NULL, NULL, NULL, 'Axolotl Biologix', 'Phillip Jenner', NULL, 'Phillip Jenner', NULL, NULL, NULL, '4585220005', 'active', NULL, 'Phoenix', 'Arizona', 'USA', NULL, NULL, NULL, NULL, '(378) 400-1234', NULL, NULL, 30, 'days', NULL, 4, 0.0000, 0, 0, 0, 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-04-10 04:04:20', '2018-04-10 04:04:20'),
(10, 3, 'customer', NULL, NULL, NULL, NULL, NULL, NULL, 'Walk-In Customer', NULL, 'Walk-In Customer', NULL, NULL, NULL, NULL, 'active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, 5, 0.0000, 0, 0, 0, 1, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-04-10 04:46:16', '2018-04-10 04:46:16'),
(11, 3, 'supplier', NULL, NULL, NULL, NULL, NULL, 'Digital Ocean', 'Micheal Krick', NULL, 'Micheal Krick', NULL, NULL, NULL, '12500079', 'active', NULL, 'Phoenix', 'Arizona', 'USA', NULL, NULL, NULL, NULL, '(378) 400-1234', NULL, NULL, 30, 'days', NULL, 5, 0.0000, 0, 0, 0, 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-04-10 05:36:21', '2018-04-10 05:36:21'),
(12, 3, 'supplier', NULL, NULL, NULL, NULL, NULL, 'Neon Electronics', 'Samuel Williams', NULL, 'Samuel Williams', NULL, NULL, NULL, '525800083', 'active', NULL, 'Phoenix', 'Arizona', 'USA', NULL, NULL, NULL, NULL, '(378) 400-1234', NULL, NULL, 30, 'days', NULL, 5, 0.0000, 0, 0, 0, 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-04-10 05:38:33', '2018-04-10 05:38:33'),
(13, 4, 'customer', NULL, NULL, NULL, NULL, NULL, NULL, 'Walk-In Customer', NULL, 'Walk-In Customer', NULL, NULL, NULL, NULL, 'active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, 6, 0.0000, 0, 0, 0, 1, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-04-10 06:20:43', '2018-04-10 06:20:43'),
(14, 5, 'customer', NULL, NULL, NULL, NULL, NULL, NULL, 'Walk-In Customer', NULL, 'Walk-In Customer', NULL, NULL, NULL, 'CO0001', 'active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, 7, 0.0000, 0, 0, 0, 1, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-07-13 10:42:11', '2018-07-13 10:42:11'),
(15, 5, 'supplier', NULL, NULL, NULL, NULL, NULL, 'Perfect Bakers', 'Thomas Willson', NULL, 'Thomas Willson', NULL, NULL, 'thomas@example.com', 'CO0002', 'active', '45781005', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '87850009358', NULL, NULL, 10, 'months', NULL, 7, 0.0000, 0, 0, 0, 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-07-13 01:33:50', '2018-07-13 01:33:50'),
(16, 6, 'customer', NULL, NULL, NULL, NULL, NULL, NULL, 'Walk-In Customer', NULL, 'Walk-In Customer', NULL, NULL, NULL, NULL, 'active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, 12, 0.0000, 0, 0, 0, 1, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-04-10 02:12:40', '2018-04-10 02:12:40');

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `id` int(10) UNSIGNED NOT NULL,
  `country` varchar(100) NOT NULL,
  `currency` varchar(100) NOT NULL,
  `code` varchar(25) NOT NULL,
  `symbol` varchar(25) NOT NULL,
  `thousand_separator` varchar(10) NOT NULL,
  `decimal_separator` varchar(10) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `country`, `currency`, `code`, `symbol`, `thousand_separator`, `decimal_separator`, `created_at`, `updated_at`) VALUES
(1, 'Albania', 'Leke', 'ALL', 'Lek', ',', '.', NULL, NULL),
(2, 'America', 'Dollars', 'USD', '$', ',', '.', NULL, NULL),
(3, 'Afghanistan', 'Afghanis', 'AF', '؋', ',', '.', NULL, NULL),
(4, 'Argentina', 'Pesos', 'ARS', '$', ',', '.', NULL, NULL),
(5, 'Aruba', 'Guilders', 'AWG', 'ƒ', ',', '.', NULL, NULL),
(6, 'Australia', 'Dollars', 'AUD', '$', ',', '.', NULL, NULL),
(7, 'Azerbaijan', 'New Manats', 'AZ', 'ман', ',', '.', NULL, NULL),
(8, 'Bahamas', 'Dollars', 'BSD', '$', ',', '.', NULL, NULL),
(9, 'Barbados', 'Dollars', 'BBD', '$', ',', '.', NULL, NULL),
(10, 'Belarus', 'Rubles', 'BYR', 'p.', ',', '.', NULL, NULL),
(11, 'Belgium', 'Euro', 'EUR', '€', ',', '.', NULL, NULL),
(12, 'Beliz', 'Dollars', 'BZD', 'BZ$', ',', '.', NULL, NULL),
(13, 'Bermuda', 'Dollars', 'BMD', '$', ',', '.', NULL, NULL),
(14, 'Bolivia', 'Bolivianos', 'BOB', '$b', ',', '.', NULL, NULL),
(15, 'Bosnia and Herzegovina', 'Convertible Marka', 'BAM', 'KM', ',', '.', NULL, NULL),
(16, 'Botswana', 'Pula\'s', 'BWP', 'P', ',', '.', NULL, NULL),
(17, 'Bulgaria', 'Leva', 'BG', 'лв', ',', '.', NULL, NULL),
(18, 'Brazil', 'Reais', 'BRL', 'R$', ',', '.', NULL, NULL),
(19, 'Britain [United Kingdom]', 'Pounds', 'GBP', '£', ',', '.', NULL, NULL),
(20, 'Brunei Darussalam', 'Dollars', 'BND', '$', ',', '.', NULL, NULL),
(21, 'Cambodia', 'Riels', 'KHR', '៛', ',', '.', NULL, NULL),
(22, 'Canada', 'Dollars', 'CAD', '$', ',', '.', NULL, NULL),
(23, 'Cayman Islands', 'Dollars', 'KYD', '$', ',', '.', NULL, NULL),
(24, 'Chile', 'Pesos', 'CLP', '$', ',', '.', NULL, NULL),
(25, 'China', 'Yuan Renminbi', 'CNY', '¥', ',', '.', NULL, NULL),
(26, 'Colombia', 'Pesos', 'COP', '$', ',', '.', NULL, NULL),
(27, 'Costa Rica', 'Colón', 'CRC', '₡', ',', '.', NULL, NULL),
(28, 'Croatia', 'Kuna', 'HRK', 'kn', ',', '.', NULL, NULL),
(29, 'Cuba', 'Pesos', 'CUP', '₱', ',', '.', NULL, NULL),
(30, 'Cyprus', 'Euro', 'EUR', '€', '.', ',', NULL, NULL),
(31, 'Czech Republic', 'Koruny', 'CZK', 'Kč', ',', '.', NULL, NULL),
(32, 'Denmark', 'Kroner', 'DKK', 'kr', ',', '.', NULL, NULL),
(33, 'Dominican Republic', 'Pesos', 'DOP ', 'RD$', ',', '.', NULL, NULL),
(34, 'East Caribbean', 'Dollars', 'XCD', '$', ',', '.', NULL, NULL),
(35, 'Egypt', 'Pounds', 'EGP', '£', ',', '.', NULL, NULL),
(36, 'El Salvador', 'Colones', 'SVC', '$', ',', '.', NULL, NULL),
(37, 'England [United Kingdom]', 'Pounds', 'GBP', '£', ',', '.', NULL, NULL),
(38, 'Euro', 'Euro', 'EUR', '€', '.', ',', NULL, NULL),
(39, 'Falkland Islands', 'Pounds', 'FKP', '£', ',', '.', NULL, NULL),
(40, 'Fiji', 'Dollars', 'FJD', '$', ',', '.', NULL, NULL),
(41, 'France', 'Euro', 'EUR', '€', '.', ',', NULL, NULL),
(42, 'Ghana', 'Cedis', 'GHS', '¢', ',', '.', NULL, NULL),
(43, 'Gibraltar', 'Pounds', 'GIP', '£', ',', '.', NULL, NULL),
(44, 'Greece', 'Euro', 'EUR', '€', '.', ',', NULL, NULL),
(45, 'Guatemala', 'Quetzales', 'GTQ', 'Q', ',', '.', NULL, NULL),
(46, 'Guernsey', 'Pounds', 'GGP', '£', ',', '.', NULL, NULL),
(47, 'Guyana', 'Dollars', 'GYD', '$', ',', '.', NULL, NULL),
(48, 'Holland [Netherlands]', 'Euro', 'EUR', '€', '.', ',', NULL, NULL),
(49, 'Honduras', 'Lempiras', 'HNL', 'L', ',', '.', NULL, NULL),
(50, 'Hong Kong', 'Dollars', 'HKD', '$', ',', '.', NULL, NULL),
(51, 'Hungary', 'Forint', 'HUF', 'Ft', ',', '.', NULL, NULL),
(52, 'Iceland', 'Kronur', 'ISK', 'kr', ',', '.', NULL, NULL),
(53, 'India', 'Rupees', 'INR', '₹', ',', '.', NULL, NULL),
(54, 'Indonesia', 'Rupiahs', 'IDR', 'Rp', ',', '.', NULL, NULL),
(55, 'Iran', 'Rials', 'IRR', '﷼', ',', '.', NULL, NULL),
(56, 'Ireland', 'Euro', 'EUR', '€', '.', ',', NULL, NULL),
(57, 'Isle of Man', 'Pounds', 'IMP', '£', ',', '.', NULL, NULL),
(58, 'Israel', 'New Shekels', 'ILS', '₪', ',', '.', NULL, NULL),
(59, 'Italy', 'Euro', 'EUR', '€', '.', ',', NULL, NULL),
(60, 'Jamaica', 'Dollars', 'JMD', 'J$', ',', '.', NULL, NULL),
(61, 'Japan', 'Yen', 'JPY', '¥', ',', '.', NULL, NULL),
(62, 'Jersey', 'Pounds', 'JEP', '£', ',', '.', NULL, NULL),
(63, 'Kazakhstan', 'Tenge', 'KZT', 'лв', ',', '.', NULL, NULL),
(64, 'Korea [North]', 'Won', 'KPW', '₩', ',', '.', NULL, NULL),
(65, 'Korea [South]', 'Won', 'KRW', '₩', ',', '.', NULL, NULL),
(66, 'Kyrgyzstan', 'Soms', 'KGS', 'лв', ',', '.', NULL, NULL),
(67, 'Laos', 'Kips', 'LAK', '₭', ',', '.', NULL, NULL),
(68, 'Latvia', 'Lati', 'LVL', 'Ls', ',', '.', NULL, NULL),
(69, 'Lebanon', 'Pounds', 'LBP', '£', ',', '.', NULL, NULL),
(70, 'Liberia', 'Dollars', 'LRD', '$', ',', '.', NULL, NULL),
(71, 'Liechtenstein', 'Switzerland Francs', 'CHF', 'CHF', ',', '.', NULL, NULL),
(72, 'Lithuania', 'Litai', 'LTL', 'Lt', ',', '.', NULL, NULL),
(73, 'Luxembourg', 'Euro', 'EUR', '€', '.', ',', NULL, NULL),
(74, 'Macedonia', 'Denars', 'MKD', 'ден', ',', '.', NULL, NULL),
(75, 'Malaysia', 'Ringgits', 'MYR', 'RM', ',', '.', NULL, NULL),
(76, 'Malta', 'Euro', 'EUR', '€', '.', ',', NULL, NULL),
(77, 'Mauritius', 'Rupees', 'MUR', '₨', ',', '.', NULL, NULL),
(78, 'Mexico', 'Pesos', 'MXN', '$', ',', '.', NULL, NULL),
(79, 'Mongolia', 'Tugriks', 'MNT', '₮', ',', '.', NULL, NULL),
(80, 'Mozambique', 'Meticais', 'MZ', 'MT', ',', '.', NULL, NULL),
(81, 'Namibia', 'Dollars', 'NAD', '$', ',', '.', NULL, NULL),
(82, 'Nepal', 'Rupees', 'NPR', '₨', ',', '.', NULL, NULL),
(83, 'Netherlands Antilles', 'Guilders', 'ANG', 'ƒ', ',', '.', NULL, NULL),
(84, 'Netherlands', 'Euro', 'EUR', '€', '.', ',', NULL, NULL),
(85, 'New Zealand', 'Dollars', 'NZD', '$', ',', '.', NULL, NULL),
(86, 'Nicaragua', 'Cordobas', 'NIO', 'C$', ',', '.', NULL, NULL),
(87, 'Nigeria', 'Nairas', 'NGN', '₦', ',', '.', NULL, NULL),
(88, 'North Korea', 'Won', 'KPW', '₩', ',', '.', NULL, NULL),
(89, 'Norway', 'Krone', 'NOK', 'kr', ',', '.', NULL, NULL),
(90, 'Oman', 'Rials', 'OMR', '﷼', ',', '.', NULL, NULL),
(91, 'Pakistan', 'Rupees', 'PKR', '₨', ',', '.', NULL, NULL),
(92, 'Panama', 'Balboa', 'PAB', 'B/.', ',', '.', NULL, NULL),
(93, 'Paraguay', 'Guarani', 'PYG', 'Gs', ',', '.', NULL, NULL),
(94, 'Peru', 'Nuevos Soles', 'PE', 'S/.', ',', '.', NULL, NULL),
(95, 'Philippines', 'Pesos', 'PHP', 'Php', ',', '.', NULL, NULL),
(96, 'Poland', 'Zlotych', 'PL', 'zł', ',', '.', NULL, NULL),
(97, 'Qatar', 'Rials', 'QAR', '﷼', ',', '.', NULL, NULL),
(98, 'Romania', 'New Lei', 'RO', 'lei', ',', '.', NULL, NULL),
(99, 'Russia', 'Rubles', 'RUB', 'руб', ',', '.', NULL, NULL),
(100, 'Saint Helena', 'Pounds', 'SHP', '£', ',', '.', NULL, NULL),
(101, 'Saudi Arabia', 'Riyals', 'SAR', '﷼', ',', '.', NULL, NULL),
(102, 'Serbia', 'Dinars', 'RSD', 'Дин.', ',', '.', NULL, NULL),
(103, 'Seychelles', 'Rupees', 'SCR', '₨', ',', '.', NULL, NULL),
(104, 'Singapore', 'Dollars', 'SGD', '$', ',', '.', NULL, NULL),
(105, 'Slovenia', 'Euro', 'EUR', '€', '.', ',', NULL, NULL),
(106, 'Solomon Islands', 'Dollars', 'SBD', '$', ',', '.', NULL, NULL),
(107, 'Somalia', 'Shillings', 'SOS', 'S', ',', '.', NULL, NULL),
(108, 'South Africa', 'Rand', 'ZAR', 'R', ',', '.', NULL, NULL),
(109, 'South Korea', 'Won', 'KRW', '₩', ',', '.', NULL, NULL),
(110, 'Spain', 'Euro', 'EUR', '€', '.', ',', NULL, NULL),
(111, 'Sri Lanka', 'Rupees', 'LKR', '₨', ',', '.', NULL, NULL),
(112, 'Sweden', 'Kronor', 'SEK', 'kr', ',', '.', NULL, NULL),
(113, 'Switzerland', 'Francs', 'CHF', 'CHF', ',', '.', NULL, NULL),
(114, 'Suriname', 'Dollars', 'SRD', '$', ',', '.', NULL, NULL),
(115, 'Syria', 'Pounds', 'SYP', '£', ',', '.', NULL, NULL),
(116, 'Taiwan', 'New Dollars', 'TWD', 'NT$', ',', '.', NULL, NULL),
(117, 'Thailand', 'Baht', 'THB', '฿', ',', '.', NULL, NULL),
(118, 'Trinidad and Tobago', 'Dollars', 'TTD', 'TT$', ',', '.', NULL, NULL),
(119, 'Turkey', 'Lira', 'TRY', 'TL', ',', '.', NULL, NULL),
(120, 'Turkey', 'Liras', 'TRL', '£', ',', '.', NULL, NULL),
(121, 'Tuvalu', 'Dollars', 'TVD', '$', ',', '.', NULL, NULL),
(122, 'Ukraine', 'Hryvnia', 'UAH', '₴', ',', '.', NULL, NULL),
(123, 'United Kingdom', 'Pounds', 'GBP', '£', ',', '.', NULL, NULL),
(124, 'United States of America', 'Dollars', 'USD', '$', ',', '.', NULL, NULL),
(125, 'Uruguay', 'Pesos', 'UYU', '$U', ',', '.', NULL, NULL),
(126, 'Uzbekistan', 'Sums', 'UZS', 'лв', ',', '.', NULL, NULL),
(127, 'Vatican City', 'Euro', 'EUR', '€', '.', ',', NULL, NULL),
(128, 'Venezuela', 'Bolivares Fuertes', 'VEF', 'Bs', ',', '.', NULL, NULL),
(129, 'Vietnam', 'Dong', 'VND', '₫', ',', '.', NULL, NULL),
(130, 'Yemen', 'Rials', 'YER', '﷼', ',', '.', NULL, NULL),
(131, 'Zimbabwe', 'Zimbabwe Dollars', 'ZWD', 'Z$', ',', '.', NULL, NULL),
(132, 'Iraq', 'Iraqi dinar', 'IQD', 'د.ع', ',', '.', NULL, NULL),
(133, 'Kenya', 'Kenyan shilling', 'KES', 'KSh', ',', '.', NULL, NULL),
(134, 'Bangladesh', 'Taka', 'BDT', '৳', ',', '.', NULL, NULL),
(135, 'Algerie', 'Algerian dinar', 'DZD', 'د.ج', ' ', '.', NULL, NULL),
(136, 'United Arab Emirates', 'United Arab Emirates dirham', 'AED', 'د.إ', ',', '.', NULL, NULL),
(137, 'Uganda', 'Uganda shillings', 'UGX', 'USh', ',', '.', NULL, NULL),
(138, 'Tanzania', 'Tanzanian shilling', 'TZS', 'TSh', ',', '.', NULL, NULL),
(139, 'Angola', 'Kwanza', 'AOA', 'Kz', ',', '.', NULL, NULL),
(140, 'Kuwait', 'Kuwaiti dinar', 'KWD', 'KD', ',', '.', NULL, NULL),
(141, 'Bahrain', 'Bahraini dinar', 'BHD', 'BD', ',', '.', NULL, NULL),
(142, 'Tunisia', 'Tunisian Dinar', 'TND', 'د.ت', ',', '.', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customer_groups`
--

CREATE TABLE `customer_groups` (
  `id` int(10) UNSIGNED NOT NULL,
  `business_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `amount` double(5,2) NOT NULL,
  `price_calculation_type` varchar(191) DEFAULT 'percentage',
  `selling_price_group_id` int(11) DEFAULT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dashboard_configurations`
--

CREATE TABLE `dashboard_configurations` (
  `id` int(10) UNSIGNED NOT NULL,
  `business_id` int(10) UNSIGNED NOT NULL,
  `created_by` int(11) NOT NULL,
  `name` varchar(191) NOT NULL,
  `color` varchar(191) NOT NULL,
  `configuration` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `discounts`
--

CREATE TABLE `discounts` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `business_id` int(11) NOT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `location_id` int(11) DEFAULT NULL,
  `priority` int(11) DEFAULT NULL,
  `discount_type` varchar(191) DEFAULT NULL,
  `discount_amount` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `starts_at` datetime DEFAULT NULL,
  `ends_at` datetime DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `spg` varchar(100) DEFAULT NULL COMMENT 'Applicable in specified selling price group only. Use of applicable_in_spg column is discontinued',
  `applicable_in_cg` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `discount_variations`
--

CREATE TABLE `discount_variations` (
  `discount_id` int(11) NOT NULL,
  `variation_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `document_and_notes`
--

CREATE TABLE `document_and_notes` (
  `id` int(10) UNSIGNED NOT NULL,
  `business_id` int(11) NOT NULL,
  `notable_id` int(11) NOT NULL,
  `notable_type` varchar(191) NOT NULL,
  `heading` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_private` tinyint(1) NOT NULL DEFAULT 0,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `essentials_leave_types`
--

CREATE TABLE `essentials_leave_types` (
  `id` int(10) UNSIGNED NOT NULL,
  `business_id` int(10) UNSIGNED NOT NULL,
  `leave_type` varchar(191) NOT NULL,
  `leave_count_interval` enum('day','week','month','year') DEFAULT NULL,
  `max_leave_count` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `essentials_leave_types`
--

INSERT INTO `essentials_leave_types` (`id`, `business_id`, `leave_type`, `leave_count_interval`, `max_leave_count`, `created_at`, `updated_at`) VALUES
(1, 1, 'Sick Leave', NULL, NULL, '2019-08-06 18:00:36', '2019-08-06 18:00:36'),
(2, 1, 'Vacation Leaves', NULL, NULL, '2019-08-06 18:00:49', '2019-08-06 18:00:49'),
(3, 1, 'Maternity leave', NULL, NULL, '2019-08-06 18:01:11', '2019-08-06 18:01:11'),
(4, 1, 'Others', 'month', 1, '2019-08-06 18:01:34', '2019-08-06 18:01:34');

-- --------------------------------------------------------

--
-- Table structure for table `essentials_shifts`
--

CREATE TABLE `essentials_shifts` (
  `id` int(10) UNSIGNED NOT NULL,
  `business_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `type` enum('fixed_shift','flexible_shift') NOT NULL DEFAULT 'fixed_shift',
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `holidays` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`holidays`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `essentials_shifts`
--

INSERT INTO `essentials_shifts` (`id`, `business_id`, `name`, `type`, `start_time`, `end_time`, `holidays`, `created_at`, `updated_at`) VALUES
(1, 1, 'Morning Shift', 'fixed_shift', '09:00:00', '18:00:00', '[\"sunday\",\"saturday\"]', '2020-05-10 23:26:56', '2020-05-11 15:27:03'),
(2, 1, 'Evening Shift', 'flexible_shift', NULL, NULL, '[\"sunday\",\"saturday\"]', '2020-05-10 23:28:17', '2020-05-10 23:28:17');

-- --------------------------------------------------------

--
-- Table structure for table `essentials_user_shifts`
--

CREATE TABLE `essentials_user_shifts` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `essentials_shift_id` int(10) UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `essentials_user_shifts`
--

INSERT INTO `essentials_user_shifts` (`id`, `user_id`, `essentials_shift_id`, `start_date`, `end_date`, `created_at`, `updated_at`) VALUES
(2, 3, 1, '2026-06-22', '2026-06-28', '2020-05-11 15:27:50', '2020-05-11 15:27:50'),
(3, 9, 1, '2026-06-22', '2026-06-28', '2020-05-11 15:27:50', '2020-05-11 15:27:50'),
(5, 10, 2, '2026-06-22', '2026-06-28', '2020-05-11 15:28:15', '2020-05-11 15:28:15'),
(6, 11, 2, '2026-06-22', '2026-06-28', '2020-05-11 15:28:15', '2020-05-11 15:28:15'),
(7, 1, 2, '2026-06-22', '2026-06-28', '2020-05-11 15:29:16', '2020-05-11 15:29:16'),
(8, 2, 1, '2026-06-22', '2026-06-28', '2020-05-11 15:29:45', '2020-05-11 15:29:45');

-- --------------------------------------------------------

--
-- Table structure for table `expense_categories`
--

CREATE TABLE `expense_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `business_id` int(10) UNSIGNED NOT NULL,
  `code` varchar(191) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `group_sub_taxes`
--

CREATE TABLE `group_sub_taxes` (
  `group_tax_id` int(10) UNSIGNED NOT NULL,
  `tax_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `group_sub_taxes`
--

INSERT INTO `group_sub_taxes` (`group_tax_id`, `tax_id`) VALUES
(4, 2),
(4, 3);

-- --------------------------------------------------------

--
-- Table structure for table `gym_classes`
--

CREATE TABLE `gym_classes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `business_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gym_classes`
--

INSERT INTO `gym_classes` (`id`, `business_id`, `name`, `description`, `start_time`, `end_time`, `created_at`, `updated_at`) VALUES
(1, 1, 'Yoga', 'A relaxing and stretching session', '06:00:00', '07:00:00', '2026-06-28 08:37:33', '2026-06-28 08:37:33'),
(2, 1, 'HIIT', 'High-Intensity Interval Training', '08:00:00', '09:00:00', '2026-06-28 08:37:33', '2026-06-28 08:37:33'),
(3, 1, 'Strength Training', 'Weightlifting and muscle building', '18:00:00', '19:00:00', '2026-06-28 08:37:33', '2026-06-28 08:37:33'),
(4, 2, 'Pilates', 'Core strengthening and flexibility', '07:00:00', '08:00:00', '2026-06-28 08:37:33', '2026-06-28 08:37:33'),
(5, 2, 'CrossFit', 'Functional fitness training', '09:00:00', '10:00:00', '2026-06-28 08:37:33', '2026-06-28 08:37:33'),
(6, 2, 'Zumba', 'Dance-based cardio workout', '19:00:00', '20:00:00', '2026-06-28 08:37:33', '2026-06-28 08:37:33'),
(7, 3, 'Spin Class', 'Cycling for endurance and strength', '06:30:00', '07:30:00', '2026-06-28 08:37:33', '2026-06-28 08:37:33'),
(8, 3, 'Bootcamp', 'Full-body conditioning', '10:00:00', '11:00:00', '2026-06-28 08:37:33', '2026-06-28 08:37:33'),
(9, 3, 'Boxing', 'Boxing and cardio drills', '20:00:00', '21:00:00', '2026-06-28 08:37:33', '2026-06-28 08:37:33'),
(10, 4, 'Aerobics', 'Fun and energetic cardio workout', '07:30:00', '08:30:00', '2026-06-28 08:37:33', '2026-06-28 08:37:33'),
(11, 4, 'Power Yoga', 'Intense yoga flow for strength', '17:00:00', '18:00:00', '2026-06-28 08:37:33', '2026-06-28 08:37:33'),
(12, 4, 'MMA Training', 'Mixed martial arts techniques', '19:30:00', '20:30:00', '2026-06-28 08:37:33', '2026-06-28 08:37:33');

-- --------------------------------------------------------

--
-- Table structure for table `gym_packages`
--

CREATE TABLE `gym_packages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `business_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `duration` enum('monthly','quarterly','half-yearly','yearly','lifetime') NOT NULL,
  `amount` decimal(22,2) NOT NULL DEFAULT 0.00,
  `classes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`classes`)),
  `notes` text DEFAULT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gym_packages`
--

INSERT INTO `gym_packages` (`id`, `business_id`, `name`, `duration`, `amount`, `classes`, `notes`, `enabled`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'Monthly', 'monthly', 100.00, '[]', '1-month package', 1, 1, '2026-06-28 08:37:33', '2026-06-28 08:37:33'),
(2, 1, 'Quarterly', 'quarterly', 250.00, '[]', '3-months package', 1, 1, '2026-06-28 08:37:33', '2026-06-28 08:37:33'),
(3, 1, 'Half-Yearly', 'half-yearly', 450.00, '[]', '6-months package', 1, 1, '2026-06-28 08:37:33', '2026-06-28 08:37:33'),
(4, 1, 'Yearly', 'yearly', 800.00, '[]', '12-months package', 1, 1, '2026-06-28 08:37:33', '2026-06-28 08:37:33'),
(5, 1, 'Lifetime', 'lifetime', 5000.00, '[]', 'Lifetime package', 1, 1, '2026-06-28 08:37:33', '2026-06-28 08:37:33'),
(6, 2, 'Monthly', 'monthly', 100.00, '[]', '1-month package', 1, 1, '2026-06-28 08:37:33', '2026-06-28 08:37:33'),
(7, 2, 'Quarterly', 'quarterly', 250.00, '[]', '3-months package', 1, 1, '2026-06-28 08:37:33', '2026-06-28 08:37:33'),
(8, 2, 'Half-Yearly', 'half-yearly', 450.00, '[]', '6-months package', 1, 1, '2026-06-28 08:37:33', '2026-06-28 08:37:33'),
(9, 2, 'Yearly', 'yearly', 800.00, '[]', '12-months package', 1, 1, '2026-06-28 08:37:33', '2026-06-28 08:37:33'),
(10, 2, 'Lifetime', 'lifetime', 5000.00, '[]', 'Lifetime package', 1, 1, '2026-06-28 08:37:33', '2026-06-28 08:37:33'),
(11, 3, 'Monthly', 'monthly', 100.00, '[]', '1-month package', 1, 1, '2026-06-28 08:37:33', '2026-06-28 08:37:33'),
(12, 3, 'Quarterly', 'quarterly', 250.00, '[]', '3-months package', 1, 1, '2026-06-28 08:37:33', '2026-06-28 08:37:33'),
(13, 3, 'Half-Yearly', 'half-yearly', 450.00, '[]', '6-months package', 1, 1, '2026-06-28 08:37:33', '2026-06-28 08:37:33'),
(14, 3, 'Yearly', 'yearly', 800.00, '[]', '12-months package', 1, 1, '2026-06-28 08:37:33', '2026-06-28 08:37:33'),
(15, 3, 'Lifetime', 'lifetime', 5000.00, '[]', 'Lifetime package', 1, 1, '2026-06-28 08:37:33', '2026-06-28 08:37:33'),
(16, 4, 'Monthly', 'monthly', 100.00, '[]', '1-month package', 1, 1, '2026-06-28 08:37:33', '2026-06-28 08:37:33'),
(17, 4, 'Quarterly', 'quarterly', 250.00, '[]', '3-months package', 1, 1, '2026-06-28 08:37:33', '2026-06-28 08:37:33'),
(18, 4, 'Half-Yearly', 'half-yearly', 450.00, '[]', '6-months package', 1, 1, '2026-06-28 08:37:33', '2026-06-28 08:37:33'),
(19, 4, 'Yearly', 'yearly', 800.00, '[]', '12-months package', 1, 1, '2026-06-28 08:37:33', '2026-06-28 08:37:33'),
(20, 4, 'Lifetime', 'lifetime', 5000.00, '[]', 'Lifetime package', 1, 1, '2026-06-28 08:37:33', '2026-06-28 08:37:33');

-- --------------------------------------------------------

--
-- Table structure for table `hms_booking_extras`
--

CREATE TABLE `hms_booking_extras` (
  `id` int(10) UNSIGNED NOT NULL,
  `transaction_id` int(10) UNSIGNED NOT NULL,
  `hms_extra_id` int(10) UNSIGNED NOT NULL,
  `price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hms_booking_extras`
--

INSERT INTO `hms_booking_extras` (`id`, `transaction_id`, `hms_extra_id`, `price`, `created_at`, `updated_at`) VALUES
(1, 54, 1, 15.00, '2026-06-28 08:37:29', '2026-06-28 08:37:29');

-- --------------------------------------------------------

--
-- Table structure for table `hms_booking_lines`
--

CREATE TABLE `hms_booking_lines` (
  `id` int(10) UNSIGNED NOT NULL,
  `transaction_id` int(10) UNSIGNED NOT NULL,
  `hms_room_type_id` int(10) UNSIGNED NOT NULL,
  `hms_room_id` int(10) UNSIGNED DEFAULT NULL,
  `adults` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `childrens` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hms_booking_lines`
--

INSERT INTO `hms_booking_lines` (`id`, `transaction_id`, `hms_room_type_id`, `hms_room_id`, `adults`, `childrens`, `price`, `total_price`, `created_at`, `updated_at`) VALUES
(1, 54, 1, 1, 2, 0, 100.00, 100.00, '2026-06-28 08:37:29', '2026-06-28 08:37:29');

-- --------------------------------------------------------

--
-- Table structure for table `hms_coupons`
--

CREATE TABLE `hms_coupons` (
  `id` int(10) UNSIGNED NOT NULL,
  `business_id` int(10) UNSIGNED NOT NULL,
  `hms_room_type_id` int(10) UNSIGNED NOT NULL,
  `coupon_code` varchar(191) NOT NULL,
  `discount_type` enum('percentage','fixed') NOT NULL DEFAULT 'percentage',
  `discount` decimal(15,2) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hms_coupons`
--

INSERT INTO `hms_coupons` (`id`, `business_id`, `hms_room_type_id`, `coupon_code`, `discount_type`, `discount`, `start_date`, `end_date`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'STD10', 'percentage', 10.00, '2026-06-21', '2026-07-28', '2026-06-28 08:37:29', '2026-06-28 08:37:29'),
(2, 5, 2, 'DLX15', 'percentage', 15.00, '2026-06-21', '2026-07-28', '2026-06-28 08:37:29', '2026-06-28 08:37:29');

-- --------------------------------------------------------

--
-- Table structure for table `hms_extras`
--

CREATE TABLE `hms_extras` (
  `id` int(10) UNSIGNED NOT NULL,
  `business_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `price_per` enum('per_booking','per_night','per_person','per_room') NOT NULL DEFAULT 'per_booking',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hms_extras`
--

INSERT INTO `hms_extras` (`id`, `business_id`, `name`, `price`, `price_per`, `is_active`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'Breakfast', 15.00, 'per_night', 1, 7, '2026-06-28 08:37:29', '2026-06-28 08:37:29'),
(2, 5, 'Airport Pickup', 40.00, 'per_booking', 1, 7, '2026-06-28 08:37:29', '2026-06-28 08:37:29'),
(3, 5, 'Late Checkout', 25.00, 'per_booking', 1, 7, '2026-06-28 08:37:29', '2026-06-28 08:37:29');

-- --------------------------------------------------------

--
-- Table structure for table `hms_rooms`
--

CREATE TABLE `hms_rooms` (
  `id` int(10) UNSIGNED NOT NULL,
  `hms_room_type_id` int(10) UNSIGNED NOT NULL,
  `room_number` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hms_rooms`
--

INSERT INTO `hms_rooms` (`id`, `hms_room_type_id`, `room_number`, `created_at`, `updated_at`) VALUES
(1, 1, '101', '2026-06-28 08:37:29', '2026-06-28 08:37:29'),
(2, 1, '201', '2026-06-28 08:37:29', '2026-06-28 08:37:29');

-- --------------------------------------------------------

--
-- Table structure for table `hms_room_types`
--

CREATE TABLE `hms_room_types` (
  `id` int(10) UNSIGNED NOT NULL,
  `business_id` int(10) UNSIGNED NOT NULL,
  `type` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `amenities` text DEFAULT NULL,
  `max_occupancy` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `no_of_adult` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `no_of_child` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `created_by` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hms_room_types`
--

INSERT INTO `hms_room_types` (`id`, `business_id`, `type`, `description`, `amenities`, `max_occupancy`, `no_of_adult`, `no_of_child`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'Standard Room', 'Comfortable standard room suitable for small families or business travellers.', 'Free WiFi, Air conditioning, TV', 3, 2, 1, 7, '2026-06-28 08:37:29', '2026-06-28 08:37:29'),
(2, 5, 'Deluxe Room', 'Spacious deluxe room with extra amenities for longer stays.', 'Free WiFi, Air conditioning, TV, Mini fridge, Workspace', 4, 2, 2, 7, '2026-06-28 08:37:29', '2026-06-28 08:37:29'),
(3, 5, 'Suite', 'Premium suite with separate living area for families or VIP guests.', 'Free WiFi, Air conditioning, TV, Living area, Coffee machine', 5, 3, 2, 7, '2026-06-28 08:37:29', '2026-06-28 08:37:29');

-- --------------------------------------------------------

--
-- Table structure for table `hms_room_type_pricings`
--

CREATE TABLE `hms_room_type_pricings` (
  `id` int(10) UNSIGNED NOT NULL,
  `hms_room_type_id` int(10) UNSIGNED NOT NULL,
  `adults` tinyint(3) UNSIGNED DEFAULT NULL,
  `childrens` tinyint(3) UNSIGNED DEFAULT NULL,
  `default_price_per_night` decimal(15,2) NOT NULL,
  `price_monday` decimal(15,2) DEFAULT NULL,
  `price_tuesday` decimal(15,2) DEFAULT NULL,
  `price_wednesday` decimal(15,2) DEFAULT NULL,
  `price_thursday` decimal(15,2) DEFAULT NULL,
  `price_friday` decimal(15,2) DEFAULT NULL,
  `price_saturday` decimal(15,2) DEFAULT NULL,
  `price_sunday` decimal(15,2) DEFAULT NULL,
  `season_type` varchar(191) NOT NULL DEFAULT 'standard',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hms_room_type_pricings`
--

INSERT INTO `hms_room_type_pricings` (`id`, `hms_room_type_id`, `adults`, `childrens`, `default_price_per_night`, `price_monday`, `price_tuesday`, `price_wednesday`, `price_thursday`, `price_friday`, `price_saturday`, `price_sunday`, `season_type`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL, 100.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'standard', '2026-06-28 08:37:29', '2026-06-28 08:37:29'),
(2, 2, NULL, NULL, 150.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'standard', '2026-06-28 08:37:29', '2026-06-28 08:37:29'),
(3, 3, NULL, NULL, 220.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'standard', '2026-06-28 08:37:29', '2026-06-28 08:37:29');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_layouts`
--

CREATE TABLE `invoice_layouts` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `header_text` text DEFAULT NULL,
  `invoice_no_prefix` varchar(191) DEFAULT NULL,
  `quotation_no_prefix` varchar(191) DEFAULT NULL,
  `invoice_heading` varchar(191) DEFAULT NULL,
  `sub_heading_line1` varchar(191) DEFAULT NULL,
  `sub_heading_line2` varchar(191) DEFAULT NULL,
  `sub_heading_line3` varchar(191) DEFAULT NULL,
  `sub_heading_line4` varchar(191) DEFAULT NULL,
  `sub_heading_line5` varchar(191) DEFAULT NULL,
  `invoice_heading_not_paid` varchar(191) DEFAULT NULL,
  `invoice_heading_paid` varchar(191) DEFAULT NULL,
  `quotation_heading` varchar(191) DEFAULT NULL,
  `sub_total_label` varchar(191) DEFAULT NULL,
  `discount_label` varchar(191) DEFAULT NULL,
  `tax_label` varchar(191) DEFAULT NULL,
  `total_label` varchar(191) DEFAULT NULL,
  `round_off_label` varchar(191) DEFAULT NULL,
  `total_due_label` varchar(191) DEFAULT NULL,
  `paid_label` varchar(191) DEFAULT NULL,
  `show_client_id` tinyint(1) NOT NULL DEFAULT 0,
  `client_id_label` varchar(191) DEFAULT NULL,
  `client_tax_label` varchar(191) DEFAULT NULL,
  `date_label` varchar(191) DEFAULT NULL,
  `date_time_format` varchar(191) DEFAULT NULL,
  `show_time` tinyint(1) NOT NULL DEFAULT 1,
  `show_brand` tinyint(1) NOT NULL DEFAULT 0,
  `show_sku` tinyint(1) NOT NULL DEFAULT 1,
  `show_cat_code` tinyint(1) NOT NULL DEFAULT 1,
  `show_expiry` tinyint(1) NOT NULL DEFAULT 0,
  `show_lot` tinyint(1) NOT NULL DEFAULT 0,
  `show_image` tinyint(1) NOT NULL DEFAULT 0,
  `show_sale_description` tinyint(1) NOT NULL DEFAULT 0,
  `sales_person_label` varchar(191) DEFAULT NULL,
  `show_sales_person` tinyint(1) NOT NULL DEFAULT 0,
  `table_product_label` varchar(191) DEFAULT NULL,
  `table_qty_label` varchar(191) DEFAULT NULL,
  `table_unit_price_label` varchar(191) DEFAULT NULL,
  `table_subtotal_label` varchar(191) DEFAULT NULL,
  `cat_code_label` varchar(191) DEFAULT NULL,
  `logo` varchar(191) DEFAULT NULL,
  `show_logo` tinyint(1) NOT NULL DEFAULT 0,
  `show_business_name` tinyint(1) NOT NULL DEFAULT 0,
  `show_location_name` tinyint(1) NOT NULL DEFAULT 1,
  `show_landmark` tinyint(1) NOT NULL DEFAULT 1,
  `show_city` tinyint(1) NOT NULL DEFAULT 1,
  `show_state` tinyint(1) NOT NULL DEFAULT 1,
  `show_zip_code` tinyint(1) NOT NULL DEFAULT 1,
  `show_country` tinyint(1) NOT NULL DEFAULT 1,
  `show_mobile_number` tinyint(1) NOT NULL DEFAULT 1,
  `show_alternate_number` tinyint(1) NOT NULL DEFAULT 0,
  `show_email` tinyint(1) NOT NULL DEFAULT 0,
  `show_tax_1` tinyint(1) NOT NULL DEFAULT 1,
  `show_tax_2` tinyint(1) NOT NULL DEFAULT 0,
  `show_barcode` tinyint(1) NOT NULL DEFAULT 0,
  `show_payments` tinyint(1) NOT NULL DEFAULT 0,
  `show_customer` tinyint(1) NOT NULL DEFAULT 0,
  `customer_label` varchar(191) DEFAULT NULL,
  `commission_agent_label` varchar(191) DEFAULT NULL,
  `show_commission_agent` tinyint(1) NOT NULL DEFAULT 0,
  `show_reward_point` tinyint(1) NOT NULL DEFAULT 0,
  `highlight_color` varchar(10) DEFAULT NULL,
  `footer_text` text DEFAULT NULL,
  `module_info` text DEFAULT NULL,
  `common_settings` text DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `business_id` int(10) UNSIGNED NOT NULL,
  `show_letter_head` tinyint(1) NOT NULL DEFAULT 0,
  `letter_head` varchar(191) DEFAULT NULL,
  `show_qr_code` tinyint(1) NOT NULL DEFAULT 0,
  `qr_code_fields` text DEFAULT NULL,
  `design` varchar(190) DEFAULT 'classic',
  `cn_heading` varchar(191) DEFAULT NULL COMMENT 'cn = credit note',
  `cn_no_label` varchar(191) DEFAULT NULL,
  `cn_amount_label` varchar(191) DEFAULT NULL,
  `table_tax_headings` text DEFAULT NULL,
  `show_previous_bal` tinyint(1) NOT NULL DEFAULT 0,
  `prev_bal_label` varchar(191) DEFAULT NULL,
  `show_previous_balance_due` tinyint(1) NOT NULL DEFAULT 0,
  `previous_balance_due_label` varchar(191) DEFAULT NULL,
  `change_return_label` varchar(191) DEFAULT NULL,
  `product_custom_fields` text DEFAULT NULL,
  `contact_custom_fields` text DEFAULT NULL,
  `location_custom_fields` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoice_layouts`
--

INSERT INTO `invoice_layouts` (`id`, `name`, `header_text`, `invoice_no_prefix`, `quotation_no_prefix`, `invoice_heading`, `sub_heading_line1`, `sub_heading_line2`, `sub_heading_line3`, `sub_heading_line4`, `sub_heading_line5`, `invoice_heading_not_paid`, `invoice_heading_paid`, `quotation_heading`, `sub_total_label`, `discount_label`, `tax_label`, `total_label`, `round_off_label`, `total_due_label`, `paid_label`, `show_client_id`, `client_id_label`, `client_tax_label`, `date_label`, `date_time_format`, `show_time`, `show_brand`, `show_sku`, `show_cat_code`, `show_expiry`, `show_lot`, `show_image`, `show_sale_description`, `sales_person_label`, `show_sales_person`, `table_product_label`, `table_qty_label`, `table_unit_price_label`, `table_subtotal_label`, `cat_code_label`, `logo`, `show_logo`, `show_business_name`, `show_location_name`, `show_landmark`, `show_city`, `show_state`, `show_zip_code`, `show_country`, `show_mobile_number`, `show_alternate_number`, `show_email`, `show_tax_1`, `show_tax_2`, `show_barcode`, `show_payments`, `show_customer`, `customer_label`, `commission_agent_label`, `show_commission_agent`, `show_reward_point`, `highlight_color`, `footer_text`, `module_info`, `common_settings`, `is_default`, `business_id`, `show_letter_head`, `letter_head`, `show_qr_code`, `qr_code_fields`, `design`, `cn_heading`, `cn_no_label`, `cn_amount_label`, `table_tax_headings`, `show_previous_bal`, `prev_bal_label`, `show_previous_balance_due`, `previous_balance_due_label`, `change_return_label`, `product_custom_fields`, `contact_custom_fields`, `location_custom_fields`, `created_at`, `updated_at`) VALUES
(1, 'Default', NULL, 'Invoice No.', NULL, 'Invoice', NULL, NULL, NULL, NULL, NULL, '', '', NULL, 'Subtotal', 'Discount', 'Tax', 'Total', NULL, 'Total Due', 'Total Paid', 0, NULL, NULL, 'Date', NULL, 1, 0, 1, 1, 0, 0, 0, 0, NULL, 0, 'Product', 'Quantity', 'Unit Price', 'Subtotal', NULL, NULL, 0, 0, 1, 1, 1, 1, 1, 1, 1, 0, 0, 1, 0, 0, 1, 1, 'Customer', NULL, 0, 0, '#000000', '', NULL, NULL, 1, 1, 0, NULL, 0, NULL, 'classic', NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, '2018-04-03 17:35:32', '2018-04-03 17:35:32'),
(2, 'Default', NULL, 'Invoice No.', NULL, 'Invoice', NULL, NULL, NULL, NULL, NULL, '', '', NULL, 'Subtotal', 'Discount', 'Tax', 'Total', NULL, 'Total Due', 'Total Paid', 0, NULL, NULL, 'Date', NULL, 1, 0, 1, 1, 0, 0, 0, 0, NULL, 0, 'Product', 'Quantity', 'Unit Price', 'Subtotal', NULL, NULL, 0, 0, 1, 1, 1, 1, 1, 1, 1, 0, 0, 1, 0, 0, 1, 1, 'Customer', NULL, 0, 0, '#000000', '', NULL, NULL, 1, 2, 0, NULL, 0, NULL, 'classic', NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, '2018-04-10 02:12:40', '2018-04-10 02:12:40'),
(3, 'Default', NULL, 'Invoice No.', NULL, 'Invoice', NULL, NULL, NULL, NULL, NULL, '', '', NULL, 'Subtotal', 'Discount', 'Tax', 'Total', NULL, 'Total Due', 'Total Paid', 0, NULL, NULL, 'Date', NULL, 1, 0, 1, 1, 0, 0, 0, 0, NULL, 0, 'Product', 'Quantity', 'Unit Price', 'Subtotal', NULL, NULL, 0, 0, 1, 1, 1, 1, 1, 1, 1, 0, 0, 1, 0, 0, 1, 1, 'Customer', NULL, 0, 0, '#000000', '', NULL, NULL, 1, 3, 0, NULL, 0, NULL, 'classic', NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, '2018-04-10 04:46:16', '2018-04-10 04:46:16'),
(4, 'Default', NULL, 'Invoice No.', NULL, 'Invoice', NULL, NULL, NULL, NULL, NULL, '', '', NULL, 'Subtotal', 'Discount', 'Tax', 'Total', NULL, 'Total Due', 'Total Paid', 0, NULL, NULL, 'Date', NULL, 1, 0, 1, 1, 0, 0, 0, 0, NULL, 0, 'Product', 'Quantity', 'Unit Price', 'Subtotal', NULL, NULL, 0, 0, 1, 1, 1, 1, 1, 1, 1, 0, 0, 1, 0, 0, 1, 1, 'Customer', NULL, 0, 0, '#000000', '', NULL, NULL, 1, 4, 0, NULL, 0, NULL, 'classic', NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, '2018-04-10 06:20:43', '2018-04-10 06:20:43'),
(5, 'Default', NULL, 'Invoice No.', NULL, 'Invoice', NULL, NULL, NULL, NULL, NULL, '', '', NULL, 'Subtotal', 'Discount', 'Tax', 'Total', NULL, 'Total Due', 'Total Paid', 0, NULL, NULL, 'Date', NULL, 1, 0, 1, 1, 0, 0, 0, 0, NULL, 0, 'Product', 'Quantity', 'Unit Price', 'Subtotal', NULL, NULL, 0, 0, 1, 1, 1, 1, 1, 1, 1, 0, 0, 1, 0, 0, 1, 1, 'Customer', NULL, 0, 0, '#000000', '', NULL, NULL, 1, 5, 0, NULL, 0, NULL, 'classic', NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, '2018-07-13 10:42:11', '2018-07-13 10:42:11'),
(6, 'Default', NULL, 'Invoice No.', NULL, 'Invoice', NULL, NULL, NULL, NULL, NULL, '', '', NULL, 'Subtotal', 'Discount', 'Tax', 'Total', NULL, 'Total Due', 'Total Paid', 0, NULL, NULL, 'Date', NULL, 1, 0, 1, 1, 0, 0, 0, 0, NULL, 0, 'Product', 'Quantity', 'Unit Price', 'Subtotal', NULL, NULL, 0, 0, 1, 1, 1, 1, 1, 1, 1, 0, 0, 1, 0, 0, 1, 1, 'Customer', NULL, 0, 0, '#000000', '', NULL, NULL, 1, 6, 0, NULL, 0, NULL, 'classic', NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, '2019-08-18 12:41:28', '2019-08-18 12:41:28');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_schemes`
--

CREATE TABLE `invoice_schemes` (
  `id` int(10) UNSIGNED NOT NULL,
  `business_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `scheme_type` enum('blank','year') NOT NULL,
  `number_type` varchar(100) NOT NULL DEFAULT 'sequential',
  `prefix` varchar(191) DEFAULT NULL,
  `start_number` int(11) DEFAULT NULL,
  `invoice_count` int(11) NOT NULL DEFAULT 0,
  `total_digits` int(11) DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoice_schemes`
--

INSERT INTO `invoice_schemes` (`id`, `business_id`, `name`, `scheme_type`, `number_type`, `prefix`, `start_number`, `invoice_count`, `total_digits`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 1, 'Default', 'blank', 'sequential', 'AS', 1, 5, 4, 1, '2018-01-03 20:15:20', '2018-01-03 20:45:16'),
(2, 2, 'Default', 'blank', 'sequential', 'AP', 1, 3, 4, 1, '2018-04-10 02:12:40', '2018-04-10 04:26:12'),
(3, 3, 'Default', 'blank', 'sequential', 'AE', 1, 5, 4, 1, '2018-04-10 04:46:16', '2018-04-10 05:54:16'),
(4, 4, 'Default', 'blank', 'sequential', '', 1, 8, 4, 1, '2018-04-10 06:20:43', '2018-04-10 07:08:49'),
(5, 5, 'Default', 'blank', 'sequential', '', 1, 4, 4, 1, '2018-07-13 05:12:11', '2018-07-12 20:16:36'),
(6, 6, 'Default', 'blank', 'sequential', '', 1, 4, 4, 1, '2018-07-13 05:12:11', '2018-07-12 20:16:36');

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id` int(10) UNSIGNED NOT NULL,
  `business_id` int(11) NOT NULL,
  `file_name` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `uploaded_by` int(11) DEFAULT NULL,
  `model_type` varchar(191) NOT NULL,
  `model_media_type` varchar(191) DEFAULT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mfg_recipes`
--

CREATE TABLE `mfg_recipes` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `variation_id` int(10) UNSIGNED NOT NULL,
  `sub_unit_id` int(10) UNSIGNED DEFAULT NULL,
  `total_quantity` decimal(22,4) NOT NULL DEFAULT 1.0000,
  `ingredients_cost` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `extra_cost` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `final_price` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `waste_percent` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `instructions` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mfg_recipes`
--

INSERT INTO `mfg_recipes` (`id`, `product_id`, `variation_id`, `sub_unit_id`, `total_quantity`, `ingredients_cost`, `extra_cost`, `final_price`, `waste_percent`, `instructions`, `created_at`, `updated_at`) VALUES
(1, 81, 129, 9, 1.0000, 2660.0000, 10.0000, 2926.0000, 0.0000, '<p>Steps for making pizza can be written here ...</p>', '2019-08-18 13:05:09', '2019-08-18 13:08:12'),
(2, 93, 141, 9, 1.0000, 31.5000, 10.0000, 34.6500, 0.0000, NULL, '2019-08-18 13:22:40', '2019-08-18 13:22:40');

-- --------------------------------------------------------

--
-- Table structure for table `mfg_recipe_ingredients`
--

CREATE TABLE `mfg_recipe_ingredients` (
  `id` int(10) UNSIGNED NOT NULL,
  `mfg_recipe_id` int(10) UNSIGNED NOT NULL,
  `variation_id` int(10) UNSIGNED NOT NULL,
  `sub_unit_id` int(10) UNSIGNED DEFAULT NULL,
  `quantity` decimal(22,4) NOT NULL,
  `waste_percent` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mfg_recipe_ingredients`
--

INSERT INTO `mfg_recipe_ingredients` (`id`, `mfg_recipe_id`, `variation_id`, `sub_unit_id`, `quantity`, `waste_percent`, `created_at`, `updated_at`) VALUES
(1, 1, 130, NULL, 1.0000, 0.0000, '2019-08-18 13:05:09', '2019-08-18 13:08:12'),
(2, 1, 136, NULL, 20.0000, 0.0000, '2019-08-18 13:05:09', '2019-08-18 13:08:12'),
(3, 1, 135, NULL, 100.0000, 0.0000, '2019-08-18 13:05:09', '2019-08-18 13:08:12'),
(4, 1, 132, NULL, 10.0000, 0.0000, '2019-08-18 13:05:09', '2019-08-18 13:08:12'),
(5, 1, 133, NULL, 20.0000, 0.0000, '2019-08-18 13:08:13', '2019-08-18 13:08:13'),
(6, 1, 131, NULL, 100.0000, 0.5000, '2019-08-18 13:08:13', '2019-08-18 13:08:13'),
(7, 2, 139, NULL, 6.0000, 0.0000, '2019-08-18 13:22:41', '2019-08-18 13:22:41'),
(8, 2, 138, NULL, 0.5000, 0.0000, '2019-08-18 13:22:41', '2019-08-18 13:22:41'),
(9, 2, 140, NULL, 1.0000, 0.0000, '2019-08-18 13:22:41', '2019-08-18 13:22:41'),
(10, 2, 137, NULL, 2.5000, 0.5000, '2019-08-18 13:22:41', '2019-08-18 13:22:41');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2016_06_01_000001_create_oauth_auth_codes_table', 1),
(4, '2016_06_01_000002_create_oauth_access_tokens_table', 1),
(5, '2016_06_01_000003_create_oauth_refresh_tokens_table', 1),
(6, '2016_06_01_000004_create_oauth_clients_table', 1),
(7, '2016_06_01_000005_create_oauth_personal_access_clients_table', 1),
(8, '2017_07_05_071953_create_currencies_table', 1),
(9, '2017_07_05_073658_create_business_table', 1),
(10, '2017_07_22_075923_add_business_id_users_table', 1),
(11, '2017_07_23_113209_create_brands_table', 1),
(12, '2017_07_26_083429_create_permission_tables', 1),
(13, '2017_07_26_110000_create_tax_rates_table', 1),
(14, '2017_07_26_122313_create_units_table', 1),
(15, '2017_07_27_075706_create_contacts_table', 1),
(16, '2017_08_04_071038_create_categories_table', 1),
(17, '2017_08_08_115903_create_products_table', 1),
(18, '2017_08_09_061616_create_variation_templates_table', 1),
(19, '2017_08_09_061638_create_variation_value_templates_table', 1),
(20, '2017_08_10_061146_create_product_variations_table', 1),
(21, '2017_08_10_061216_create_variations_table', 1),
(22, '2017_08_19_054827_create_transactions_table', 1),
(23, '2017_08_31_073533_create_purchase_lines_table', 1),
(24, '2017_10_15_064638_create_transaction_payments_table', 1),
(25, '2017_10_31_065621_add_default_sales_tax_to_business_table', 1),
(26, '2017_11_20_051930_create_table_group_sub_taxes', 1),
(27, '2017_11_20_063603_create_transaction_sell_lines', 1),
(28, '2017_11_21_064540_create_barcodes_table', 1),
(29, '2017_11_23_181237_create_invoice_schemes_table', 1),
(30, '2017_12_25_122822_create_business_locations_table', 1),
(31, '2017_12_25_160253_add_location_id_to_transactions_table', 1),
(32, '2017_12_25_163227_create_variation_location_details_table', 1),
(33, '2018_01_04_115627_create_sessions_table', 1),
(34, '2018_01_05_112817_create_invoice_layouts_table', 1),
(35, '2018_01_06_112303_add_invoice_scheme_id_and_invoice_layout_id_to_business_locations', 1),
(36, '2018_01_08_104124_create_expense_categories_table', 1),
(37, '2018_01_08_123327_modify_transactions_table_for_expenses', 1),
(38, '2018_01_09_111005_modify_payment_status_in_transactions_table', 1),
(39, '2018_01_09_111109_add_paid_on_column_to_transaction_payments_table', 1),
(40, '2018_01_25_172439_add_printer_related_fields_to_business_locations_table', 1),
(41, '2018_01_27_184322_create_printers_table', 1),
(42, '2018_01_30_181442_create_cash_registers_table', 1),
(43, '2018_01_31_125836_create_cash_register_transactions_table', 1),
(44, '2018_02_07_173326_modify_business_table', 1),
(45, '2018_02_08_105425_add_enable_product_expiry_column_to_business_table', 1),
(46, '2018_02_08_111027_add_expiry_period_and_expiry_period_type_columns_to_products_table', 1),
(47, '2018_02_08_131118_add_mfg_date_and_exp_date_purchase_lines_table', 1),
(48, '2018_02_08_155348_add_exchange_rate_to_transactions_table', 1),
(49, '2018_02_09_124945_modify_transaction_payments_table_for_contact_payments', 1),
(50, '2018_02_12_113640_create_transaction_sell_lines_purchase_lines_table', 1),
(51, '2018_02_12_114605_add_quantity_sold_in_purchase_lines_table', 1),
(52, '2018_02_13_183323_alter_decimal_fields_size', 1),
(53, '2018_02_14_161928_add_transaction_edit_days_to_business_table', 1),
(54, '2018_02_15_161032_add_document_column_to_transactions_table', 1),
(55, '2018_02_17_124709_add_more_options_to_invoice_layouts', 1),
(56, '2018_02_19_111517_add_keyboard_shortcut_column_to_business_table', 1),
(57, '2018_02_19_121537_stock_adjustment_move_to_transaction_table', 1),
(58, '2018_02_20_165505_add_is_direct_sale_column_to_transactions_table', 1),
(59, '2018_02_21_105329_create_system_table', 1),
(60, '2018_02_23_100549_version_1_2', 1),
(61, '2018_02_23_125648_add_enable_editing_sp_from_purchase_column_to_business_table', 1),
(62, '2018_02_26_103612_add_sales_commission_agent_column_to_business_table', 1),
(63, '2018_02_26_130519_modify_users_table_for_sales_cmmsn_agnt', 1),
(64, '2018_02_26_134500_add_commission_agent_to_transactions_table', 1),
(65, '2018_02_27_121422_add_item_addition_method_to_business_table', 1),
(66, '2018_02_27_170232_modify_transactions_table_for_stock_transfer', 1),
(67, '2018_03_05_153510_add_enable_inline_tax_column_to_business_table', 1),
(68, '2018_03_06_210206_modify_product_barcode_types', 1),
(69, '2018_03_13_181541_add_expiry_type_to_business_table', 1),
(70, '2018_03_16_113446_product_expiry_setting_for_business', 1),
(71, '2018_03_19_113601_add_business_settings_options', 1),
(72, '2018_03_26_125334_add_pos_settings_to_business_table', 1),
(73, '2018_03_26_165350_create_customer_groups_table', 1),
(74, '2018_03_27_122720_customer_group_related_changes_in_tables', 1),
(75, '2018_03_29_110138_change_tax_field_to_nullable_in_business_table', 1),
(76, '2018_03_29_115502_add_changes_for_sr_number_in_products_and_sale_lines_table', 1),
(77, '2018_03_29_134340_add_inline_discount_fields_in_purchase_lines', 1),
(78, '2018_03_31_140921_update_transactions_table_exchange_rate', 1),
(79, '2018_04_03_103037_add_contact_id_to_contacts_table', 1),
(80, '2018_04_03_122709_add_changes_to_invoice_layouts_table', 1),
(81, '2018_04_09_135320_change_exchage_rate_size_in_business_table', 1),
(82, '2018_04_17_123122_add_lot_number_to_business', 1),
(83, '2018_04_17_160845_add_product_racks_table', 1),
(84, '2018_04_20_182015_create_res_tables_table', 1),
(85, '2018_04_24_105246_restaurant_fields_in_transaction_table', 1),
(86, '2018_04_24_114149_add_enabled_modules_business_table', 1),
(87, '2018_04_24_133704_add_modules_fields_in_invoice_layout_table', 1),
(88, '2018_04_27_132653_quotation_related_change', 1),
(89, '2018_05_02_104439_add_date_format_and_time_format_to_business', 1),
(90, '2018_05_02_111939_add_sell_return_to_transaction_payments', 1),
(91, '2018_05_14_114027_add_rows_positions_for_products', 1),
(92, '2018_05_14_125223_add_weight_to_products_table', 1),
(93, '2018_05_14_164754_add_opening_stock_permission', 1),
(94, '2018_05_15_134729_add_design_to_invoice_layouts', 1),
(95, '2018_05_16_183307_add_tax_fields_invoice_layout', 1),
(96, '2018_05_18_191956_add_sell_return_to_transaction_table', 1),
(97, '2018_05_21_131349_add_custom_fileds_to_contacts_table', 1),
(98, '2018_05_21_131607_invoice_layout_fields_for_sell_return', 1),
(99, '2018_05_21_131949_add_custom_fileds_and_website_to_business_locations_table', 1),
(100, '2018_05_22_123527_create_reference_counts_table', 1),
(101, '2018_05_22_154540_add_ref_no_prefixes_column_to_business_table', 1),
(102, '2018_05_24_132620_add_ref_no_column_to_transaction_payments_table', 1),
(103, '2018_05_24_161026_add_location_id_column_to_business_location_table', 1),
(104, '2018_05_25_180603_create_modifiers_related_table', 1),
(105, '2018_05_29_121714_add_purchase_line_id_to_stock_adjustment_line_table', 1),
(106, '2018_05_31_114645_add_res_order_status_column_to_transactions_table', 1),
(107, '2018_06_05_103530_rename_purchase_line_id_in_stock_adjustment_lines_table', 1),
(108, '2018_06_05_111905_modify_products_table_for_modifiers', 1),
(109, '2018_06_06_110524_add_parent_sell_line_id_column_to_transaction_sell_lines_table', 1),
(110, '2018_06_07_152443_add_is_service_staff_to_roles_table', 1),
(111, '2018_06_07_182258_add_image_field_to_products_table', 1),
(112, '2018_06_13_133705_create_bookings_table', 1),
(113, '2018_06_15_173636_add_email_column_to_contacts_table', 1),
(114, '2018_06_27_182835_add_superadmin_related_fields_business', 1),
(115, '2018_07_10_101913_add_custom_fields_to_products_table', 1),
(116, '2018_07_17_103434_add_sales_person_name_label_to_invoice_layouts_table', 1),
(117, '2018_07_17_163920_add_theme_skin_color_column_to_business_table', 1),
(118, '2018_07_24_160319_add_lot_no_line_id_to_transaction_sell_lines_table', 1),
(119, '2018_07_25_110004_add_show_expiry_and_show_lot_colums_to_invoice_layouts_table', 1),
(120, '2018_07_25_172004_add_discount_columns_to_transaction_sell_lines_table', 1),
(121, '2018_07_26_124720_change_design_column_type_in_invoice_layouts_table', 1),
(122, '2018_07_26_170424_add_unit_price_before_discount_column_to_transaction_sell_line_table', 1),
(123, '2018_07_28_103614_add_credit_limit_column_to_contacts_table', 1),
(124, '2018_08_08_110755_add_new_payment_methods_to_transaction_payments_table', 1),
(125, '2018_08_08_122225_modify_cash_register_transactions_table_for_new_payment_methods', 1),
(126, '2018_08_14_104036_add_opening_balance_type_to_transactions_table', 1),
(127, '2018_09_04_155900_create_accounts_table', 1),
(128, '2018_09_06_114438_create_selling_price_groups_table', 1),
(129, '2018_09_06_154057_create_variation_group_prices_table', 1),
(130, '2018_09_07_102413_add_permission_to_access_default_selling_price', 1),
(131, '2018_09_07_134858_add_selling_price_group_id_to_transactions_table', 1),
(132, '2018_09_10_112448_update_product_type_to_single_if_null_in_products_table', 1),
(133, '2018_09_10_152703_create_account_transactions_table', 1),
(134, '2018_09_10_173656_add_account_id_column_to_transaction_payments_table', 1),
(135, '2018_09_19_123914_create_notification_templates_table', 1),
(136, '2018_09_22_110504_add_sms_and_email_settings_columns_to_business_table', 1),
(137, '2018_09_24_134942_add_lot_no_line_id_to_stock_adjustment_lines_table', 1),
(138, '2018_09_26_105557_add_transaction_payments_for_existing_expenses', 1),
(139, '2018_09_27_111609_modify_transactions_table_for_purchase_return', 1),
(140, '2018_09_27_131154_add_quantity_returned_column_to_purchase_lines_table', 1),
(141, '2018_10_02_131401_add_return_quantity_column_to_transaction_sell_lines_table', 1),
(142, '2018_10_03_104918_add_qty_returned_column_to_transaction_sell_lines_purchase_lines_table', 1),
(143, '2018_10_03_185947_add_default_notification_templates_to_database', 1),
(144, '2018_10_09_153105_add_business_id_to_transaction_payments_table', 1),
(145, '2018_10_16_135229_create_permission_for_sells_and_purchase', 1),
(146, '2018_10_22_114441_add_columns_for_variable_product_modifications', 1),
(147, '2018_10_22_134428_modify_variable_product_data', 1),
(148, '2018_10_30_181558_add_table_tax_headings_to_invoice_layout', 1),
(149, '2018_10_31_122619_add_pay_terms_field_transactions_table', 1),
(150, '2018_10_31_161328_add_new_permissions_for_pos_screen', 1),
(151, '2018_10_31_174752_add_access_selected_contacts_only_to_users_table', 1),
(152, '2018_10_31_175627_add_user_contact_access', 1),
(153, '2018_10_31_180559_add_auto_send_sms_column_to_notification_templates_table', 1),
(154, '2018_11_02_171949_change_card_type_column_to_varchar_in_transaction_payments_table', 1),
(155, '2018_11_08_105621_add_role_permissions', 1),
(156, '2018_11_26_114135_add_is_suspend_column_to_transactions_table', 1),
(157, '2018_11_28_104410_modify_units_table_for_multi_unit', 1),
(158, '2018_11_28_170952_add_sub_unit_id_to_purchase_lines_and_sell_lines', 1),
(159, '2018_11_29_115918_add_primary_key_in_system_table', 1),
(160, '2018_12_03_185546_add_product_description_column_to_products_table', 1),
(161, '2018_12_06_114937_modify_system_table_and_users_table', 1),
(162, '2018_12_13_160007_add_custom_fields_display_options_to_invoice_layouts_table', 1),
(163, '2018_12_14_103307_modify_system_table', 1),
(164, '2018_12_18_133837_add_prev_balance_due_columns_to_invoice_layouts_table', 1),
(165, '2018_12_18_170656_add_invoice_token_column_to_transaction_table', 1),
(166, '2018_12_20_133639_add_date_time_format_column_to_invoice_layouts_table', 1),
(167, '2018_12_21_120659_add_recurring_invoice_fields_to_transactions_table', 1),
(168, '2018_12_24_154933_create_notifications_table', 1),
(169, '2019_01_08_112015_add_document_column_to_transaction_payments_table', 1),
(170, '2019_01_10_124645_add_account_permission', 1),
(171, '2019_01_16_125825_add_subscription_no_column_to_transactions_table', 1),
(172, '2019_01_28_111647_add_order_addresses_column_to_transactions_table', 1),
(173, '2019_02_13_173821_add_is_inactive_column_to_products_table', 1),
(174, '2019_02_19_103118_create_discounts_table', 1),
(175, '2019_02_21_120324_add_discount_id_column_to_transaction_sell_lines_table', 1),
(176, '2019_02_21_134324_add_permission_for_discount', 1),
(177, '2019_03_04_170832_add_service_staff_columns_to_transaction_sell_lines_table', 1),
(178, '2019_03_09_102425_add_sub_type_column_to_transactions_table', 1),
(179, '2019_03_09_124457_add_indexing_transaction_sell_lines_purchase_lines_table', 1),
(180, '2019_03_12_120336_create_activity_log_table', 1),
(181, '2019_03_15_132925_create_media_table', 1),
(182, '2019_05_08_130339_add_indexing_to_parent_id_in_transaction_payments_table', 1),
(183, '2019_05_10_132311_add_missing_column_indexing', 1),
(184, '2019_05_14_091812_add_show_image_column_to_invoice_layouts_table', 1),
(185, '2019_05_25_104922_add_view_purchase_price_permission', 1),
(186, '2019_06_17_103515_add_profile_informations_columns_to_users_table', 1),
(187, '2019_06_18_135524_add_permission_to_view_own_sales_only', 1),
(188, '2019_06_19_112058_add_database_changes_for_reward_points', 1),
(189, '2019_06_28_133732_change_type_column_to_string_in_transactions_table', 1),
(190, '2019_07_13_111420_add_is_created_from_api_column_to_transactions_table', 1),
(191, '2019_07_15_165136_add_fields_for_combo_product', 1),
(192, '2019_07_19_103446_add_mfg_quantity_used_column_to_purchase_lines_table', 1),
(193, '2019_07_22_152649_add_not_for_selling_in_product_table', 1),
(194, '2019_07_29_185351_add_show_reward_point_column_to_invoice_layouts_table', 1),
(195, '2019_08_08_162302_add_sub_units_related_fields', 1),
(196, '2019_08_26_133419_update_price_fields_decimal_point', 1),
(197, '2019_09_02_160054_remove_location_permissions_from_roles', 1),
(198, '2019_09_03_185259_add_permission_for_pos_screen', 1),
(199, '2019_09_04_163141_add_location_id_to_cash_registers_table', 1),
(200, '2019_09_04_184008_create_types_of_services_table', 1),
(201, '2019_09_06_131445_add_types_of_service_fields_to_transactions_table', 1),
(202, '2019_09_09_134810_add_default_selling_price_group_id_column_to_business_locations_table', 1),
(203, '2019_09_12_105616_create_product_locations_table', 1),
(204, '2019_09_17_122522_add_custom_labels_column_to_business_table', 1),
(205, '2019_09_18_164319_add_shipping_fields_to_transactions_table', 1),
(206, '2019_09_19_170927_close_all_active_registers', 1),
(207, '2019_09_23_161906_add_media_description_cloumn_to_media_table', 1),
(208, '2019_10_18_155633_create_account_types_table', 1),
(209, '2019_10_22_163335_add_common_settings_column_to_business_table', 1),
(210, '2019_10_29_132521_add_update_purchase_status_permission', 1),
(211, '2019_11_09_110522_add_indexing_to_lot_number', 1),
(212, '2019_11_19_170824_add_is_active_column_to_business_locations_table', 1),
(213, '2019_11_21_162913_change_quantity_field_types_to_decimal', 1),
(214, '2019_11_25_160340_modify_categories_table_for_polymerphic_relationship', 1),
(215, '2019_12_02_105025_create_warranties_table', 1),
(216, '2019_12_03_180342_add_common_settings_field_to_invoice_layouts_table', 1),
(217, '2019_12_05_183955_add_more_fields_to_users_table', 1),
(218, '2019_12_06_174904_add_change_return_label_column_to_invoice_layouts_table', 1),
(219, '2019_12_11_121307_add_draft_and_quotation_list_permissions', 1),
(220, '2019_12_12_180126_copy_expense_total_to_total_before_tax', 1),
(221, '2019_12_19_181412_make_alert_quantity_field_nullable_on_products_table', 1),
(222, '2019_12_25_173413_create_dashboard_configurations_table', 1),
(223, '2020_01_08_133506_create_document_and_notes_table', 1),
(224, '2020_01_09_113252_add_cc_bcc_column_to_notification_templates_table', 1),
(225, '2020_01_16_174818_add_round_off_amount_field_to_transactions_table', 1),
(226, '2020_01_28_162345_add_weighing_scale_settings_in_business_settings_table', 1),
(227, '2020_02_18_172447_add_import_fields_to_transactions_table', 1),
(228, '2020_03_13_135844_add_is_active_column_to_selling_price_groups_table', 1),
(229, '2020_03_16_115449_add_contact_status_field_to_contacts_table', 1),
(230, '2020_03_26_124736_add_allow_login_column_in_users_table', 1),
(231, '2020_04_13_154150_add_feature_products_column_to_business_loactions', 1),
(232, '2020_04_15_151802_add_user_type_to_users_table', 1),
(233, '2020_04_22_153905_add_subscription_repeat_on_column_to_transactions_table', 1),
(234, '2020_04_28_111436_add_shipping_address_to_contacts_table', 1),
(235, '2020_06_01_094654_add_max_sale_discount_column_to_users_table', 1),
(236, '2020_06_12_162245_modify_contacts_table', 1),
(237, '2020_06_22_103104_change_recur_interval_default_to_one', 1),
(238, '2020_07_09_174621_add_balance_field_to_contacts_table', 1),
(239, '2020_07_23_104933_change_status_column_to_varchar_in_transaction_table', 1),
(240, '2020_09_07_171059_change_completed_stock_transfer_status_to_final', 1),
(241, '2020_09_21_123224_modify_booking_status_column_in_bookings_table', 1),
(242, '2020_09_22_121639_create_discount_variations_table', 1),
(243, '2020_10_05_121550_modify_business_location_table_for_invoice_layout', 1),
(244, '2020_10_16_175726_set_status_as_received_for_opening_stock', 1),
(245, '2020_10_23_170823_add_for_group_tax_column_to_tax_rates_table', 1),
(246, '2020_11_04_130940_add_more_custom_fields_to_contacts_table', 1),
(247, '2020_11_10_152841_add_cash_register_permissions', 1),
(248, '2020_11_17_164041_modify_type_column_to_varchar_in_contacts_table', 1),
(249, '2020_12_18_181447_add_shipping_custom_fields_to_transactions_table', 1),
(250, '2020_12_22_164303_add_sub_status_column_to_transactions_table', 1),
(251, '2020_12_24_153050_add_custom_fields_to_transactions_table', 1),
(252, '2020_12_28_105403_add_whatsapp_text_column_to_notification_templates_table', 1),
(253, '2020_12_29_165925_add_model_document_type_to_media_table', 1),
(254, '2021_02_08_175632_add_contact_number_fields_to_users_table', 1),
(255, '2021_02_11_172217_add_indexing_for_multiple_columns', 1),
(256, '2021_02_23_122043_add_more_columns_to_customer_groups_table', 1),
(257, '2021_02_24_175551_add_print_invoice_permission_to_all_roles', 1),
(258, '2021_03_03_162021_add_purchase_order_columns_to_purchase_lines_and_transactions_table', 1),
(259, '2021_03_11_120229_add_sales_order_columns', 1),
(260, '2021_03_16_120705_add_business_id_to_activity_log_table', 1),
(261, '2021_03_16_153427_add_code_columns_to_business_table', 1),
(262, '2021_03_18_173308_add_account_details_column_to_accounts_table', 1),
(263, '2021_03_18_183119_add_prefer_payment_account_columns_to_transactions_table', 1),
(264, '2021_03_22_120810_add_more_types_of_service_custom_fields', 1),
(265, '2021_03_24_183132_add_shipping_export_custom_field_details_to_contacts_table', 1),
(266, '2021_03_25_170715_add_export_custom_fields_info_to_transactions_table', 1),
(267, '2021_04_15_063449_add_denominations_column_to_cash_registers_table', 1),
(268, '2021_05_22_083426_add_indexing_to_account_transactions_table', 1),
(269, '2021_07_08_065808_add_additional_expense_columns_to_transaction_table', 1),
(270, '2021_07_13_082918_add_qr_code_columns_to_invoice_layouts_table', 1),
(271, '2021_07_21_061615_add_fields_to_show_commission_agent_in_invoice_layout', 1),
(272, '2021_08_13_105549_add_crm_contact_id_to_users_table', 1),
(273, '2021_08_25_114932_add_payment_link_fields_to_transaction_payments_table', 1),
(274, '2021_09_01_063110_add_spg_column_to_discounts_table', 1),
(275, '2021_09_03_061528_modify_cash_register_transactions_table', 1),
(276, '2021_10_05_061658_add_source_column_to_transactions_table', 1),
(277, '2021_12_16_121851_add_parent_id_column_to_expense_categories_table', 1),
(278, '2022_04_14_075120_add_payment_type_column_to_transaction_payments_table', 1),
(279, '2022_04_21_083327_create_cash_denominations_table', 1),
(280, '2022_05_10_055307_add_delivery_date_column_to_transactions_table', 1),
(281, '2022_06_13_123135_add_currency_precision_and_quantity_precision_fields_to_business_table', 1),
(282, '2022_06_28_133342_add_secondary_unit_columns_to_products_sell_line_purchase_lines_tables', 1),
(283, '2022_07_13_114307_create_purchase_requisition_related_columns', 1),
(284, '2022_08_25_132707_add_service_staff_timer_fields_to_products_and_users_table', 1),
(285, '2023_01_28_114255_add_letter_head_column_to_invoice_layouts_table', 1),
(286, '2023_02_11_161510_add_event_column_to_activity_log_table', 1),
(287, '2023_02_11_161511_add_batch_uuid_column_to_activity_log_table', 1),
(288, '2023_03_02_170312_add_provider_to_oauth_clients_table', 1),
(289, '2023_03_21_122731_add_sale_invoice_scheme_id_business_table', 1),
(290, '2023_03_21_170446_add_number_type_to_invoice_scheme', 1),
(291, '2023_04_17_155216_add_custom_fields_to_products', 1),
(292, '2023_04_28_130247_add_price_type_to_group_price_table', 1),
(293, '2023_06_21_033923_add_delivery_person_in_transactions', 1),
(294, '2023_09_13_153555_add_service_staff_pin_columns_in_users', 1),
(295, '2023_09_15_154404_add_is_kitchen_order_in_transactions', 1),
(296, '2023_12_06_152840_add_contact_type_in_contacts', 1),
(297, '2024_10_03_151459_modify_transaction_sell_lines_purchase_lines_table', 1),
(298, '2025_03_07_114637_add_more_addresh_column_in_contact', 1),
(299, '2025_09_19_120000_add_previous_balance_due_fields_to_invoice_layouts_table', 1),
(300, '2026_04_03_000001_add_transaction_payment_id_to_cash_register_transactions', 1),
(301, '2026_04_14_000001_add_tunisian_dinar_currency', 1),
(302, '2026_06_12_000001_add_expense_title_and_payment_to_to_transactions', 1),
(303, '2026_06_28_075232_add_productcatalogue_settings_to_business_table', 1),
(304, '2026_06_28_075852_add_repair_settings_to_business_table', 1),
(305, '2026_06_28_080011_add_repair_settings_to_business_table', 1),
(306, '2026_06_28_080731_create_hms_room_types_table', 1),
(307, '2026_06_28_081014_create_hms_rooms_table', 1),
(308, '2026_06_28_081153_create_hms_room_type_pricings_table', 1),
(309, '2026_06_28_081359_create_hms_extras_table', 1),
(310, '2026_06_28_081523_create_hms_coupons_table', 1),
(311, '2026_06_28_081814_add_hms_columns_to_transactions_table', 1),
(312, '2026_06_28_082001_create_hms_booking_lines_table', 1),
(313, '2026_06_28_103037_create_hms_booking_extras_table', 1),
(314, '2026_06_28_103410_add_woocommerce_cat_id_to_categories_table', 1),
(315, '2026_06_28_103552_add_repair_columns_to_products_table', 1),
(316, '2026_06_28_111454_add_manufacturing_columns_to_transactions_table', 1),
(317, '2026_06_28_112000_add_manufacturing_columns_to_transaction_sell_lines_table', 1),
(318, '2026_06_28_113441_create_packages_table', 1),
(319, '2026_06_28_113530_create_subscriptions_table', 1),
(320, '2026_06_28_114039_create_mfg_recipes_table', 1),
(321, '2026_06_28_114355_create_mfg_recipe_ingredients_table', 1),
(322, '2026_06_28_114518_create_essentials_leave_types_table', 1),
(323, '2026_06_28_114744_create_repair_device_models_table', 1),
(324, '2026_06_28_115048_create_repair_statuses_table', 1),
(325, '2026_06_28_120606_create_essentials_shifts_table', 1),
(326, '2026_06_28_120832_create_essentials_user_shifts_table', 1),
(327, '2026_06_28_121036_create_gym_classes_table', 1),
(328, '2026_06_28_121223_create_gym_packages_table', 1),
(329, '2018_06_27_185405_create_packages_table', 2),
(330, '2018_06_28_182803_create_subscriptions_table', 3),
(331, '2018_07_17_182021_add_rows_to_system_table', 3),
(332, '2018_07_19_131721_add_options_to_packages_table', 3),
(333, '2018_08_17_155534_add_min_termination_alert_days', 3),
(334, '2018_08_28_105945_add_business_based_username_settings_to_system_table', 3),
(335, '2018_08_30_105906_add_superadmin_communicator_logs_table', 3),
(336, '2018_11_02_130636_add_custom_permissions_to_packages_table', 3),
(337, '2018_11_05_161848_add_more_fields_to_packages_table', 3),
(338, '2018_12_10_124621_modify_system_table_values_null_default', 3),
(339, '2019_05_10_135434_add_missing_database_column_indexes', 3),
(340, '2019_08_16_115300_create_superadmin_frontend_pages_table', 3),
(341, '2023_06_10_132121_create_coupons_table', 3),
(342, '2023_06_16_141928_add_column_in_subscriptions', 3),
(343, '2023_06_29_183442_add_mark_package_as_popular', 3),
(344, '2023_07_04_151432_add_column_business', 3),
(345, '2023_07_13_191306_add_businesses_column_in_coupons', 3);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` int(10) UNSIGNED NOT NULL,
  `model_type` varchar(191) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_permissions`
--

INSERT INTO `model_has_permissions` (`permission_id`, `model_type`, `model_id`) VALUES
(82, 'App\\User', 2),
(86, 'App\\User', 8),
(88, 'App\\User', 9);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` int(10) UNSIGNED NOT NULL,
  `model_type` varchar(191) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\User', 1),
(1, 'App\\User', 3),
(1, 'App\\User', 9),
(1, 'App\\User', 10),
(1, 'App\\User', 11),
(2, 'App\\User', 2),
(3, 'App\\User', 4),
(5, 'App\\User', 5),
(7, 'App\\User', 6),
(9, 'App\\User', 7),
(11, 'App\\User', 8),
(12, 'App\\User', 12);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(191) NOT NULL,
  `notifiable_type` varchar(191) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notification_templates`
--

CREATE TABLE `notification_templates` (
  `id` int(10) UNSIGNED NOT NULL,
  `business_id` int(11) NOT NULL,
  `template_for` varchar(191) NOT NULL,
  `email_body` text DEFAULT NULL,
  `sms_body` text DEFAULT NULL,
  `whatsapp_text` text DEFAULT NULL,
  `subject` varchar(191) DEFAULT NULL,
  `cc` varchar(191) DEFAULT NULL,
  `bcc` varchar(191) DEFAULT NULL,
  `auto_send` tinyint(1) NOT NULL DEFAULT 0,
  `auto_send_sms` tinyint(1) NOT NULL DEFAULT 0,
  `auto_send_wa_notif` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notification_templates`
--

INSERT INTO `notification_templates` (`id`, `business_id`, `template_for`, `email_body`, `sms_body`, `whatsapp_text`, `subject`, `cc`, `bcc`, `auto_send`, `auto_send_sms`, `auto_send_wa_notif`, `created_at`, `updated_at`) VALUES
(1, 1, 'new_sale', '<p>Dear {contact_name},</p>\n\n                    <p>Your invoice number is {invoice_number}<br />\n                    Total amount: {total_amount}<br />\n                    Paid amount: {received_amount}</p>\n\n                    <p>Thank you for shopping with us.</p>\n\n                    <p>{business_logo}</p>\n\n                    <p>&nbsp;</p>', 'Dear {contact_name}, Thank you for shopping with us. {business_name}', NULL, 'Thank you from {business_name}', NULL, NULL, 0, 0, 0, NULL, NULL),
(2, 1, 'payment_received', '<p>Dear {contact_name},</p>\n\n                <p>We have received a payment of {received_amount}</p>\n\n                <p>{business_logo}</p>', 'Dear {contact_name}, We have received a payment of {received_amount}. {business_name}', NULL, 'Payment Received, from {business_name}', NULL, NULL, 0, 0, 0, NULL, NULL),
(3, 1, 'payment_reminder', '<p>Dear {contact_name},</p>\n\n                    <p>This is to remind you that you have pending payment of {due_amount}. Kindly pay it as soon as possible.</p>\n\n                    <p>{business_logo}</p>', 'Dear {contact_name}, You have pending payment of {due_amount}. Kindly pay it as soon as possible. {business_name}', NULL, 'Payment Reminder, from {business_name}', NULL, NULL, 0, 0, 0, NULL, NULL),
(4, 1, 'new_booking', '<p>Dear {contact_name},</p>\n\n                    <p>Your booking is confirmed</p>\n\n                    <p>Date: {start_time} to {end_time}</p>\n\n                    <p>Table: {table}</p>\n\n                    <p>Location: {location}</p>\n\n                    <p>{business_logo}</p>', 'Dear {contact_name}, Your booking is confirmed. Date: {start_time} to {end_time}, Table: {table}, Location: {location}', NULL, 'Booking Confirmed - {business_name}', NULL, NULL, 0, 0, 0, NULL, NULL),
(5, 1, 'new_order', '<p>Dear {contact_name},</p>\n\n                    <p>We have a new order with reference number {order_ref_number}. Kindly process the products as soon as possible.</p>\n\n                    <p>{business_name}<br />\n                    {business_logo}</p>', 'Dear {contact_name}, We have a new order with reference number {order_ref_number}. Kindly process the products as soon as possible. {business_name}', NULL, 'New Order, from {business_name}', NULL, NULL, 0, 0, 0, NULL, NULL),
(6, 1, 'payment_paid', '<p>Dear {contact_name},</p>\n\n                    <p>We have paid amount {paid_amount} again invoice number {order_ref_number}.<br />\n                    Kindly note it down.</p>\n\n                    <p>{business_name}<br />\n                    {business_logo}</p>', 'We have paid amount {paid_amount} again invoice number {order_ref_number}.\n                    Kindly note it down. {business_name}', NULL, 'Payment Paid, from {business_name}', NULL, NULL, 0, 0, 0, NULL, NULL),
(7, 1, 'items_received', '<p>Dear {contact_name},</p>\n\n                    <p>We have received all items from invoice reference number {order_ref_number}. Thank you for processing it.</p>\n\n                    <p>{business_name}<br />\n                    {business_logo}</p>', 'We have received all items from invoice reference number {order_ref_number}. Thank you for processing it. {business_name}', NULL, 'Items received, from {business_name}', NULL, NULL, 0, 0, 0, NULL, NULL),
(8, 1, 'items_pending', '<p>Dear {contact_name},<br />\n                    This is to remind you that we have not yet received some items from invoice reference number {order_ref_number}. Please process it as soon as possible.</p>\n\n                    <p>{business_name}<br />\n                    {business_logo}</p>', 'This is to remind you that we have not yet received some items from invoice reference number {order_ref_number} . Please process it as soon as possible.{business_name}', NULL, 'Items Pending, from {business_name}', NULL, NULL, 0, 0, 0, NULL, NULL),
(9, 1, 'new_quotation', '<p>Dear {contact_name},</p>\n\n                    <p>Your quotation number is {invoice_number}<br />\n                    Total amount: {total_amount}</p>\n\n                    <p>Thank you for shopping with us.</p>\n\n                    <p>{business_logo}</p>\n\n                    <p>&nbsp;</p>', 'Dear {contact_name}, Thank you for shopping with us. {business_name}', NULL, 'Thank you from {business_name}', NULL, NULL, 0, 0, 0, NULL, NULL),
(10, 1, 'purchase_order', '<p>Dear {contact_name},</p>\n\n                    <p>We have a new purchase order with reference number {order_ref_number}. The respective invoice is attached here with.</p>\n\n                    <p>{business_logo}</p>', 'We have a new purchase order with reference number {order_ref_number}. {business_name}', NULL, 'New Purchase Order, from {business_name}', NULL, NULL, 0, 0, 0, NULL, NULL),
(11, 2, 'new_sale', '<p>Dear {contact_name},</p>\n\n                    <p>Your invoice number is {invoice_number}<br />\n                    Total amount: {total_amount}<br />\n                    Paid amount: {received_amount}</p>\n\n                    <p>Thank you for shopping with us.</p>\n\n                    <p>{business_logo}</p>\n\n                    <p>&nbsp;</p>', 'Dear {contact_name}, Thank you for shopping with us. {business_name}', NULL, 'Thank you from {business_name}', NULL, NULL, 0, 0, 0, NULL, NULL),
(12, 2, 'payment_received', '<p>Dear {contact_name},</p>\n\n                <p>We have received a payment of {received_amount}</p>\n\n                <p>{business_logo}</p>', 'Dear {contact_name}, We have received a payment of {received_amount}. {business_name}', NULL, 'Payment Received, from {business_name}', NULL, NULL, 0, 0, 0, NULL, NULL),
(13, 2, 'payment_reminder', '<p>Dear {contact_name},</p>\n\n                    <p>This is to remind you that you have pending payment of {due_amount}. Kindly pay it as soon as possible.</p>\n\n                    <p>{business_logo}</p>', 'Dear {contact_name}, You have pending payment of {due_amount}. Kindly pay it as soon as possible. {business_name}', NULL, 'Payment Reminder, from {business_name}', NULL, NULL, 0, 0, 0, NULL, NULL),
(14, 2, 'new_booking', '<p>Dear {contact_name},</p>\n\n                    <p>Your booking is confirmed</p>\n\n                    <p>Date: {start_time} to {end_time}</p>\n\n                    <p>Table: {table}</p>\n\n                    <p>Location: {location}</p>\n\n                    <p>{business_logo}</p>', 'Dear {contact_name}, Your booking is confirmed. Date: {start_time} to {end_time}, Table: {table}, Location: {location}', NULL, 'Booking Confirmed - {business_name}', NULL, NULL, 0, 0, 0, NULL, NULL),
(15, 2, 'new_order', '<p>Dear {contact_name},</p>\n\n                    <p>We have a new order with reference number {order_ref_number}. Kindly process the products as soon as possible.</p>\n\n                    <p>{business_name}<br />\n                    {business_logo}</p>', 'Dear {contact_name}, We have a new order with reference number {order_ref_number}. Kindly process the products as soon as possible. {business_name}', NULL, 'New Order, from {business_name}', NULL, NULL, 0, 0, 0, NULL, NULL),
(16, 2, 'payment_paid', '<p>Dear {contact_name},</p>\n\n                    <p>We have paid amount {paid_amount} again invoice number {order_ref_number}.<br />\n                    Kindly note it down.</p>\n\n                    <p>{business_name}<br />\n                    {business_logo}</p>', 'We have paid amount {paid_amount} again invoice number {order_ref_number}.\n                    Kindly note it down. {business_name}', NULL, 'Payment Paid, from {business_name}', NULL, NULL, 0, 0, 0, NULL, NULL),
(17, 2, 'items_received', '<p>Dear {contact_name},</p>\n\n                    <p>We have received all items from invoice reference number {order_ref_number}. Thank you for processing it.</p>\n\n                    <p>{business_name}<br />\n                    {business_logo}</p>', 'We have received all items from invoice reference number {order_ref_number}. Thank you for processing it. {business_name}', NULL, 'Items received, from {business_name}', NULL, NULL, 0, 0, 0, NULL, NULL),
(18, 2, 'items_pending', '<p>Dear {contact_name},<br />\n                    This is to remind you that we have not yet received some items from invoice reference number {order_ref_number}. Please process it as soon as possible.</p>\n\n                    <p>{business_name}<br />\n                    {business_logo}</p>', 'This is to remind you that we have not yet received some items from invoice reference number {order_ref_number} . Please process it as soon as possible.{business_name}', NULL, 'Items Pending, from {business_name}', NULL, NULL, 0, 0, 0, NULL, NULL),
(19, 2, 'new_quotation', '<p>Dear {contact_name},</p>\n\n                    <p>Your quotation number is {invoice_number}<br />\n                    Total amount: {total_amount}</p>\n\n                    <p>Thank you for shopping with us.</p>\n\n                    <p>{business_logo}</p>\n\n                    <p>&nbsp;</p>', 'Dear {contact_name}, Thank you for shopping with us. {business_name}', NULL, 'Thank you from {business_name}', NULL, NULL, 0, 0, 0, NULL, NULL),
(20, 2, 'purchase_order', '<p>Dear {contact_name},</p>\n\n                    <p>We have a new purchase order with reference number {order_ref_number}. The respective invoice is attached here with.</p>\n\n                    <p>{business_logo}</p>', 'We have a new purchase order with reference number {order_ref_number}. {business_name}', NULL, 'New Purchase Order, from {business_name}', NULL, NULL, 0, 0, 0, NULL, NULL),
(21, 3, 'new_sale', '<p>Dear {contact_name},</p>\n\n                    <p>Your invoice number is {invoice_number}<br />\n                    Total amount: {total_amount}<br />\n                    Paid amount: {received_amount}</p>\n\n                    <p>Thank you for shopping with us.</p>\n\n                    <p>{business_logo}</p>\n\n                    <p>&nbsp;</p>', 'Dear {contact_name}, Thank you for shopping with us. {business_name}', NULL, 'Thank you from {business_name}', NULL, NULL, 0, 0, 0, NULL, NULL),
(22, 3, 'payment_received', '<p>Dear {contact_name},</p>\n\n                <p>We have received a payment of {received_amount}</p>\n\n                <p>{business_logo}</p>', 'Dear {contact_name}, We have received a payment of {received_amount}. {business_name}', NULL, 'Payment Received, from {business_name}', NULL, NULL, 0, 0, 0, NULL, NULL),
(23, 3, 'payment_reminder', '<p>Dear {contact_name},</p>\n\n                    <p>This is to remind you that you have pending payment of {due_amount}. Kindly pay it as soon as possible.</p>\n\n                    <p>{business_logo}</p>', 'Dear {contact_name}, You have pending payment of {due_amount}. Kindly pay it as soon as possible. {business_name}', NULL, 'Payment Reminder, from {business_name}', NULL, NULL, 0, 0, 0, NULL, NULL),
(24, 3, 'new_booking', '<p>Dear {contact_name},</p>\n\n                    <p>Your booking is confirmed</p>\n\n                    <p>Date: {start_time} to {end_time}</p>\n\n                    <p>Table: {table}</p>\n\n                    <p>Location: {location}</p>\n\n                    <p>{business_logo}</p>', 'Dear {contact_name}, Your booking is confirmed. Date: {start_time} to {end_time}, Table: {table}, Location: {location}', NULL, 'Booking Confirmed - {business_name}', NULL, NULL, 0, 0, 0, NULL, NULL),
(25, 3, 'new_order', '<p>Dear {contact_name},</p>\n\n                    <p>We have a new order with reference number {order_ref_number}. Kindly process the products as soon as possible.</p>\n\n                    <p>{business_name}<br />\n                    {business_logo}</p>', 'Dear {contact_name}, We have a new order with reference number {order_ref_number}. Kindly process the products as soon as possible. {business_name}', NULL, 'New Order, from {business_name}', NULL, NULL, 0, 0, 0, NULL, NULL),
(26, 3, 'payment_paid', '<p>Dear {contact_name},</p>\n\n                    <p>We have paid amount {paid_amount} again invoice number {order_ref_number}.<br />\n                    Kindly note it down.</p>\n\n                    <p>{business_name}<br />\n                    {business_logo}</p>', 'We have paid amount {paid_amount} again invoice number {order_ref_number}.\n                    Kindly note it down. {business_name}', NULL, 'Payment Paid, from {business_name}', NULL, NULL, 0, 0, 0, NULL, NULL),
(27, 3, 'items_received', '<p>Dear {contact_name},</p>\n\n                    <p>We have received all items from invoice reference number {order_ref_number}. Thank you for processing it.</p>\n\n                    <p>{business_name}<br />\n                    {business_logo}</p>', 'We have received all items from invoice reference number {order_ref_number}. Thank you for processing it. {business_name}', NULL, 'Items received, from {business_name}', NULL, NULL, 0, 0, 0, NULL, NULL),
(28, 3, 'items_pending', '<p>Dear {contact_name},<br />\n                    This is to remind you that we have not yet received some items from invoice reference number {order_ref_number}. Please process it as soon as possible.</p>\n\n                    <p>{business_name}<br />\n                    {business_logo}</p>', 'This is to remind you that we have not yet received some items from invoice reference number {order_ref_number} . Please process it as soon as possible.{business_name}', NULL, 'Items Pending, from {business_name}', NULL, NULL, 0, 0, 0, NULL, NULL),
(29, 3, 'new_quotation', '<p>Dear {contact_name},</p>\n\n                    <p>Your quotation number is {invoice_number}<br />\n                    Total amount: {total_amount}</p>\n\n                    <p>Thank you for shopping with us.</p>\n\n                    <p>{business_logo}</p>\n\n                    <p>&nbsp;</p>', 'Dear {contact_name}, Thank you for shopping with us. {business_name}', NULL, 'Thank you from {business_name}', NULL, NULL, 0, 0, 0, NULL, NULL),
(30, 3, 'purchase_order', '<p>Dear {contact_name},</p>\n\n                    <p>We have a new purchase order with reference number {order_ref_number}. The respective invoice is attached here with.</p>\n\n                    <p>{business_logo}</p>', 'We have a new purchase order with reference number {order_ref_number}. {business_name}', NULL, 'New Purchase Order, from {business_name}', NULL, NULL, 0, 0, 0, NULL, NULL),
(31, 4, 'new_sale', '<p>Dear {contact_name},</p>\n\n                    <p>Your invoice number is {invoice_number}<br />\n                    Total amount: {total_amount}<br />\n                    Paid amount: {received_amount}</p>\n\n                    <p>Thank you for shopping with us.</p>\n\n                    <p>{business_logo}</p>\n\n                    <p>&nbsp;</p>', 'Dear {contact_name}, Thank you for shopping with us. {business_name}', NULL, 'Thank you from {business_name}', NULL, NULL, 0, 0, 0, NULL, NULL),
(32, 4, 'payment_received', '<p>Dear {contact_name},</p>\n\n                <p>We have received a payment of {received_amount}</p>\n\n                <p>{business_logo}</p>', 'Dear {contact_name}, We have received a payment of {received_amount}. {business_name}', NULL, 'Payment Received, from {business_name}', NULL, NULL, 0, 0, 0, NULL, NULL),
(33, 4, 'payment_reminder', '<p>Dear {contact_name},</p>\n\n                    <p>This is to remind you that you have pending payment of {due_amount}. Kindly pay it as soon as possible.</p>\n\n                    <p>{business_logo}</p>', 'Dear {contact_name}, You have pending payment of {due_amount}. Kindly pay it as soon as possible. {business_name}', NULL, 'Payment Reminder, from {business_name}', NULL, NULL, 0, 0, 0, NULL, NULL),
(34, 4, 'new_booking', '<p>Dear {contact_name},</p>\n\n                    <p>Your booking is confirmed</p>\n\n                    <p>Date: {start_time} to {end_time}</p>\n\n                    <p>Table: {table}</p>\n\n                    <p>Location: {location}</p>\n\n                    <p>{business_logo}</p>', 'Dear {contact_name}, Your booking is confirmed. Date: {start_time} to {end_time}, Table: {table}, Location: {location}', NULL, 'Booking Confirmed - {business_name}', NULL, NULL, 0, 0, 0, NULL, NULL),
(35, 4, 'new_order', '<p>Dear {contact_name},</p>\n\n                    <p>We have a new order with reference number {order_ref_number}. Kindly process the products as soon as possible.</p>\n\n                    <p>{business_name}<br />\n                    {business_logo}</p>', 'Dear {contact_name}, We have a new order with reference number {order_ref_number}. Kindly process the products as soon as possible. {business_name}', NULL, 'New Order, from {business_name}', NULL, NULL, 0, 0, 0, NULL, NULL),
(36, 4, 'payment_paid', '<p>Dear {contact_name},</p>\n\n                    <p>We have paid amount {paid_amount} again invoice number {order_ref_number}.<br />\n                    Kindly note it down.</p>\n\n                    <p>{business_name}<br />\n                    {business_logo}</p>', 'We have paid amount {paid_amount} again invoice number {order_ref_number}.\n                    Kindly note it down. {business_name}', NULL, 'Payment Paid, from {business_name}', NULL, NULL, 0, 0, 0, NULL, NULL),
(37, 4, 'items_received', '<p>Dear {contact_name},</p>\n\n                    <p>We have received all items from invoice reference number {order_ref_number}. Thank you for processing it.</p>\n\n                    <p>{business_name}<br />\n                    {business_logo}</p>', 'We have received all items from invoice reference number {order_ref_number}. Thank you for processing it. {business_name}', NULL, 'Items received, from {business_name}', NULL, NULL, 0, 0, 0, NULL, NULL),
(38, 4, 'items_pending', '<p>Dear {contact_name},<br />\n                    This is to remind you that we have not yet received some items from invoice reference number {order_ref_number}. Please process it as soon as possible.</p>\n\n                    <p>{business_name}<br />\n                    {business_logo}</p>', 'This is to remind you that we have not yet received some items from invoice reference number {order_ref_number} . Please process it as soon as possible.{business_name}', NULL, 'Items Pending, from {business_name}', NULL, NULL, 0, 0, 0, NULL, NULL),
(39, 4, 'new_quotation', '<p>Dear {contact_name},</p>\n\n                    <p>Your quotation number is {invoice_number}<br />\n                    Total amount: {total_amount}</p>\n\n                    <p>Thank you for shopping with us.</p>\n\n                    <p>{business_logo}</p>\n\n                    <p>&nbsp;</p>', 'Dear {contact_name}, Thank you for shopping with us. {business_name}', NULL, 'Thank you from {business_name}', NULL, NULL, 0, 0, 0, NULL, NULL),
(40, 4, 'purchase_order', '<p>Dear {contact_name},</p>\n\n                    <p>We have a new purchase order with reference number {order_ref_number}. The respective invoice is attached here with.</p>\n\n                    <p>{business_logo}</p>', 'We have a new purchase order with reference number {order_ref_number}. {business_name}', NULL, 'New Purchase Order, from {business_name}', NULL, NULL, 0, 0, 0, NULL, NULL),
(41, 5, 'new_sale', '<p>Dear {contact_name},</p>\n\n                    <p>Your invoice number is {invoice_number}<br />\n                    Total amount: {total_amount}<br />\n                    Paid amount: {received_amount}</p>\n\n                    <p>Thank you for shopping with us.</p>\n\n                    <p>{business_logo}</p>\n\n                    <p>&nbsp;</p>', 'Dear {contact_name}, Thank you for shopping with us. {business_name}', NULL, 'Thank you from {business_name}', NULL, NULL, 0, 0, 0, NULL, NULL),
(42, 5, 'payment_received', '<p>Dear {contact_name},</p>\n\n                <p>We have received a payment of {received_amount}</p>\n\n                <p>{business_logo}</p>', 'Dear {contact_name}, We have received a payment of {received_amount}. {business_name}', NULL, 'Payment Received, from {business_name}', NULL, NULL, 0, 0, 0, NULL, NULL),
(43, 5, 'payment_reminder', '<p>Dear {contact_name},</p>\n\n                    <p>This is to remind you that you have pending payment of {due_amount}. Kindly pay it as soon as possible.</p>\n\n                    <p>{business_logo}</p>', 'Dear {contact_name}, You have pending payment of {due_amount}. Kindly pay it as soon as possible. {business_name}', NULL, 'Payment Reminder, from {business_name}', NULL, NULL, 0, 0, 0, NULL, NULL),
(44, 5, 'new_booking', '<p>Dear {contact_name},</p>\n\n                    <p>Your booking is confirmed</p>\n\n                    <p>Date: {start_time} to {end_time}</p>\n\n                    <p>Table: {table}</p>\n\n                    <p>Location: {location}</p>\n\n                    <p>{business_logo}</p>', 'Dear {contact_name}, Your booking is confirmed. Date: {start_time} to {end_time}, Table: {table}, Location: {location}', NULL, 'Booking Confirmed - {business_name}', NULL, NULL, 0, 0, 0, NULL, NULL),
(45, 5, 'new_order', '<p>Dear {contact_name},</p>\n\n                    <p>We have a new order with reference number {order_ref_number}. Kindly process the products as soon as possible.</p>\n\n                    <p>{business_name}<br />\n                    {business_logo}</p>', 'Dear {contact_name}, We have a new order with reference number {order_ref_number}. Kindly process the products as soon as possible. {business_name}', NULL, 'New Order, from {business_name}', NULL, NULL, 0, 0, 0, NULL, NULL),
(46, 5, 'payment_paid', '<p>Dear {contact_name},</p>\n\n                    <p>We have paid amount {paid_amount} again invoice number {order_ref_number}.<br />\n                    Kindly note it down.</p>\n\n                    <p>{business_name}<br />\n                    {business_logo}</p>', 'We have paid amount {paid_amount} again invoice number {order_ref_number}.\n                    Kindly note it down. {business_name}', NULL, 'Payment Paid, from {business_name}', NULL, NULL, 0, 0, 0, NULL, NULL),
(47, 5, 'items_received', '<p>Dear {contact_name},</p>\n\n                    <p>We have received all items from invoice reference number {order_ref_number}. Thank you for processing it.</p>\n\n                    <p>{business_name}<br />\n                    {business_logo}</p>', 'We have received all items from invoice reference number {order_ref_number}. Thank you for processing it. {business_name}', NULL, 'Items received, from {business_name}', NULL, NULL, 0, 0, 0, NULL, NULL),
(48, 5, 'items_pending', '<p>Dear {contact_name},<br />\n                    This is to remind you that we have not yet received some items from invoice reference number {order_ref_number}. Please process it as soon as possible.</p>\n\n                    <p>{business_name}<br />\n                    {business_logo}</p>', 'This is to remind you that we have not yet received some items from invoice reference number {order_ref_number} . Please process it as soon as possible.{business_name}', NULL, 'Items Pending, from {business_name}', NULL, NULL, 0, 0, 0, NULL, NULL),
(49, 5, 'new_quotation', '<p>Dear {contact_name},</p>\n\n                    <p>Your quotation number is {invoice_number}<br />\n                    Total amount: {total_amount}</p>\n\n                    <p>Thank you for shopping with us.</p>\n\n                    <p>{business_logo}</p>\n\n                    <p>&nbsp;</p>', 'Dear {contact_name}, Thank you for shopping with us. {business_name}', NULL, 'Thank you from {business_name}', NULL, NULL, 0, 0, 0, NULL, NULL),
(50, 5, 'purchase_order', '<p>Dear {contact_name},</p>\n\n                    <p>We have a new purchase order with reference number {order_ref_number}. The respective invoice is attached here with.</p>\n\n                    <p>{business_logo}</p>', 'We have a new purchase order with reference number {order_ref_number}. {business_name}', NULL, 'New Purchase Order, from {business_name}', NULL, NULL, 0, 0, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `scopes` text DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_auth_codes`
--

CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `scopes` text DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
--

CREATE TABLE `oauth_clients` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `name` varchar(191) NOT NULL,
  `secret` varchar(100) NOT NULL,
  `provider` varchar(191) DEFAULT NULL,
  `redirect` text NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_personal_access_clients`
--

CREATE TABLE `oauth_personal_access_clients` (
  `id` int(10) UNSIGNED NOT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_refresh_tokens`
--

CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) NOT NULL,
  `access_token_id` varchar(100) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `description` text NOT NULL,
  `location_count` int(11) NOT NULL COMMENT 'No. of Business Locations, 0 = infinite option.',
  `user_count` int(11) NOT NULL,
  `product_count` int(11) NOT NULL,
  `bookings` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Enable/Disable bookings',
  `kitchen` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Enable/Disable kitchen',
  `order_screen` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Enable/Disable order_screen',
  `tables` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Enable/Disable tables',
  `invoice_count` int(11) NOT NULL,
  `interval` enum('days','months','years') NOT NULL,
  `interval_count` int(11) NOT NULL,
  `trial_days` int(11) NOT NULL,
  `price` decimal(22,4) NOT NULL,
  `custom_permissions` longtext NOT NULL,
  `created_by` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL,
  `mark_package_as_popular` tinyint(1) NOT NULL,
  `businesses` longtext DEFAULT NULL,
  `is_private` tinyint(1) NOT NULL DEFAULT 0,
  `is_one_time` tinyint(1) NOT NULL DEFAULT 0,
  `enable_custom_link` tinyint(1) NOT NULL DEFAULT 0,
  `custom_link` varchar(191) DEFAULT NULL,
  `custom_link_text` varchar(191) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) NOT NULL,
  `token` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `guard_name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'profit_loss_report.view', 'web', '2026-06-28 08:37:06', NULL),
(2, 'direct_sell.access', 'web', '2026-06-28 08:37:06', NULL),
(3, 'product.opening_stock', 'web', '2026-06-28 08:37:07', '2026-06-28 08:37:07'),
(4, 'crud_all_bookings', 'web', '2026-06-28 08:37:08', '2026-06-28 08:37:08'),
(5, 'crud_own_bookings', 'web', '2026-06-28 08:37:08', '2026-06-28 08:37:08'),
(6, 'access_default_selling_price', 'web', '2026-06-28 08:37:09', '2026-06-28 08:37:09'),
(7, 'purchase.payments', 'web', '2026-06-28 08:37:10', '2026-06-28 08:37:10'),
(8, 'sell.payments', 'web', '2026-06-28 08:37:10', '2026-06-28 08:37:10'),
(9, 'edit_product_price_from_sale_screen', 'web', '2026-06-28 08:37:10', '2026-06-28 08:37:10'),
(10, 'edit_product_discount_from_sale_screen', 'web', '2026-06-28 08:37:10', '2026-06-28 08:37:10'),
(11, 'roles.view', 'web', '2026-06-28 08:37:10', '2026-06-28 08:37:10'),
(12, 'roles.create', 'web', '2026-06-28 08:37:10', '2026-06-28 08:37:10'),
(13, 'roles.update', 'web', '2026-06-28 08:37:10', '2026-06-28 08:37:10'),
(14, 'roles.delete', 'web', '2026-06-28 08:37:10', '2026-06-28 08:37:10'),
(15, 'account.access', 'web', '2026-06-28 08:37:10', '2026-06-28 08:37:10'),
(16, 'discount.access', 'web', '2026-06-28 08:37:11', '2026-06-28 08:37:11'),
(17, 'view_purchase_price', 'web', '2026-06-28 08:37:11', '2026-06-28 08:37:11'),
(18, 'view_own_sell_only', 'web', '2026-06-28 08:37:12', '2026-06-28 08:37:12'),
(19, 'edit_product_discount_from_pos_screen', 'web', '2026-06-28 08:37:12', '2026-06-28 08:37:12'),
(20, 'edit_product_price_from_pos_screen', 'web', '2026-06-28 08:37:12', '2026-06-28 08:37:12'),
(21, 'access_shipping', 'web', '2026-06-28 08:37:13', '2026-06-28 08:37:13'),
(22, 'purchase.update_status', 'web', '2026-06-28 08:37:13', '2026-06-28 08:37:13'),
(23, 'list_drafts', 'web', '2026-06-28 08:37:14', '2026-06-28 08:37:14'),
(24, 'list_quotations', 'web', '2026-06-28 08:37:14', '2026-06-28 08:37:14'),
(25, 'view_cash_register', 'web', '2026-06-28 09:05:37', '2026-06-28 09:05:37'),
(26, 'close_cash_register', 'web', '2026-06-28 09:05:37', '2026-06-28 09:05:37'),
(27, 'print_invoice', 'web', '2026-06-28 09:05:37', '2026-06-28 09:05:37'),
(28, 'user.view', 'web', '2026-06-28 08:37:28', NULL),
(29, 'user.create', 'web', '2026-06-28 08:37:28', NULL),
(30, 'user.update', 'web', '2026-06-28 08:37:28', NULL),
(31, 'user.delete', 'web', '2026-06-28 08:37:28', NULL),
(32, 'supplier.view', 'web', '2026-06-28 08:37:28', NULL),
(33, 'supplier.create', 'web', '2026-06-28 08:37:28', NULL),
(34, 'supplier.update', 'web', '2026-06-28 08:37:28', NULL),
(35, 'supplier.delete', 'web', '2026-06-28 08:37:28', NULL),
(36, 'customer.view', 'web', '2026-06-28 08:37:28', NULL),
(37, 'customer.create', 'web', '2026-06-28 08:37:28', NULL),
(38, 'customer.update', 'web', '2026-06-28 08:37:28', NULL),
(39, 'customer.delete', 'web', '2026-06-28 08:37:28', NULL),
(40, 'product.view', 'web', '2026-06-28 08:37:28', NULL),
(41, 'product.create', 'web', '2026-06-28 08:37:28', NULL),
(42, 'product.update', 'web', '2026-06-28 08:37:28', NULL),
(43, 'product.delete', 'web', '2026-06-28 08:37:28', NULL),
(44, 'purchase.view', 'web', '2026-06-28 08:37:28', NULL),
(45, 'purchase.create', 'web', '2026-06-28 08:37:28', NULL),
(46, 'purchase.update', 'web', '2026-06-28 08:37:28', NULL),
(47, 'purchase.delete', 'web', '2026-06-28 08:37:28', NULL),
(48, 'sell.view', 'web', '2026-06-28 09:05:37', '2026-06-28 09:05:37'),
(49, 'sell.create', 'web', '2026-06-28 09:05:37', '2026-06-28 09:05:37'),
(50, 'sell.update', 'web', '2026-06-28 09:05:37', '2026-06-28 09:05:37'),
(51, 'sell.delete', 'web', '2026-06-28 09:05:37', '2026-06-28 09:05:37'),
(52, 'purchase_n_sell_report.view', 'web', '2026-06-28 08:37:28', NULL),
(53, 'contacts_report.view', 'web', '2026-06-28 08:37:28', NULL),
(54, 'stock_report.view', 'web', '2026-06-28 08:37:28', NULL),
(55, 'tax_report.view', 'web', '2026-06-28 08:37:28', NULL),
(56, 'trending_product_report.view', 'web', '2026-06-28 08:37:28', NULL),
(57, 'register_report.view', 'web', '2026-06-28 08:37:28', NULL),
(58, 'sales_representative.view', 'web', '2026-06-28 08:37:28', NULL),
(59, 'expense_report.view', 'web', '2026-06-28 08:37:28', NULL),
(60, 'business_settings.access', 'web', '2026-06-28 08:37:28', NULL),
(61, 'barcode_settings.access', 'web', '2026-06-28 08:37:28', NULL),
(62, 'invoice_settings.access', 'web', '2026-06-28 08:37:28', NULL),
(63, 'brand.view', 'web', '2026-06-28 08:37:28', NULL),
(64, 'brand.create', 'web', '2026-06-28 08:37:28', NULL),
(65, 'brand.update', 'web', '2026-06-28 08:37:28', NULL),
(66, 'brand.delete', 'web', '2026-06-28 08:37:28', NULL),
(67, 'tax_rate.view', 'web', '2026-06-28 08:37:28', NULL),
(68, 'tax_rate.create', 'web', '2026-06-28 08:37:28', NULL),
(69, 'tax_rate.update', 'web', '2026-06-28 08:37:28', NULL),
(70, 'tax_rate.delete', 'web', '2026-06-28 08:37:28', NULL),
(71, 'unit.view', 'web', '2026-06-28 08:37:28', NULL),
(72, 'unit.create', 'web', '2026-06-28 08:37:28', NULL),
(73, 'unit.update', 'web', '2026-06-28 08:37:28', NULL),
(74, 'unit.delete', 'web', '2026-06-28 08:37:28', NULL),
(75, 'category.view', 'web', '2026-06-28 08:37:28', NULL),
(76, 'category.create', 'web', '2026-06-28 08:37:28', NULL),
(77, 'category.update', 'web', '2026-06-28 08:37:28', NULL),
(78, 'category.delete', 'web', '2026-06-28 08:37:28', NULL),
(79, 'expense.access', 'web', '2026-06-28 08:37:28', NULL),
(80, 'access_all_locations', 'web', '2026-06-28 08:37:28', NULL),
(81, 'dashboard.data', 'web', '2026-06-28 09:05:37', '2026-06-28 09:05:37'),
(82, 'location.1', 'web', '2026-06-28 09:05:37', '2026-06-28 09:05:37'),
(83, 'location.2', 'web', '2026-06-28 09:05:37', '2026-06-28 09:05:37'),
(84, 'location.3', 'web', '2026-06-28 09:05:37', '2026-06-28 09:05:37'),
(85, 'location.4', 'web', '2026-06-28 09:05:37', '2026-06-28 09:05:37'),
(86, 'location.5', 'web', '2026-06-28 09:05:37', '2026-06-28 09:05:37'),
(87, 'location.6', 'web', '2026-06-28 09:05:37', '2026-06-28 09:05:37'),
(88, 'superadmin', 'web', '2026-06-28 09:05:37', '2026-06-28 09:05:37');

-- --------------------------------------------------------

--
-- Table structure for table `printers`
--

CREATE TABLE `printers` (
  `id` int(10) UNSIGNED NOT NULL,
  `business_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `connection_type` enum('network','windows','linux') NOT NULL,
  `capability_profile` enum('default','simple','SP2000','TEP-200M','P822D') NOT NULL DEFAULT 'default',
  `char_per_line` varchar(191) DEFAULT NULL,
  `ip_address` varchar(191) DEFAULT NULL,
  `port` varchar(191) DEFAULT NULL,
  `path` varchar(191) DEFAULT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `business_id` int(10) UNSIGNED NOT NULL,
  `type` enum('single','variable','modifier','combo') DEFAULT NULL,
  `unit_id` int(11) UNSIGNED DEFAULT NULL,
  `secondary_unit_id` int(11) DEFAULT NULL,
  `sub_unit_ids` text DEFAULT NULL,
  `brand_id` int(10) UNSIGNED DEFAULT NULL,
  `category_id` int(10) UNSIGNED DEFAULT NULL,
  `sub_category_id` int(10) UNSIGNED DEFAULT NULL,
  `repair_model_id` int(10) UNSIGNED DEFAULT NULL,
  `woocommerce_disable_sync` tinyint(1) NOT NULL DEFAULT 0,
  `woocommerce_media_id` bigint(20) UNSIGNED DEFAULT NULL,
  `woocommerce_product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tax` int(10) UNSIGNED DEFAULT NULL,
  `tax_type` enum('inclusive','exclusive') NOT NULL,
  `enable_stock` tinyint(1) NOT NULL DEFAULT 0,
  `alert_quantity` decimal(22,4) DEFAULT NULL,
  `sku` varchar(191) NOT NULL,
  `barcode_type` enum('C39','C128','EAN13','EAN8','UPCA','UPCE') DEFAULT 'C128',
  `expiry_period` decimal(4,2) DEFAULT NULL,
  `expiry_period_type` enum('days','months') DEFAULT NULL,
  `enable_sr_no` tinyint(1) NOT NULL DEFAULT 0,
  `weight` varchar(191) DEFAULT NULL,
  `product_custom_field1` varchar(191) DEFAULT NULL,
  `product_custom_field2` varchar(191) DEFAULT NULL,
  `product_custom_field3` varchar(191) DEFAULT NULL,
  `product_custom_field4` varchar(191) DEFAULT NULL,
  `product_custom_field5` varchar(191) DEFAULT NULL,
  `product_custom_field6` varchar(191) DEFAULT NULL,
  `product_custom_field7` varchar(191) DEFAULT NULL,
  `product_custom_field8` varchar(191) DEFAULT NULL,
  `product_custom_field9` varchar(191) DEFAULT NULL,
  `product_custom_field10` varchar(191) DEFAULT NULL,
  `product_custom_field11` varchar(191) DEFAULT NULL,
  `product_custom_field12` varchar(191) DEFAULT NULL,
  `product_custom_field13` varchar(191) DEFAULT NULL,
  `product_custom_field14` varchar(191) DEFAULT NULL,
  `product_custom_field15` varchar(191) DEFAULT NULL,
  `product_custom_field16` varchar(191) DEFAULT NULL,
  `product_custom_field17` varchar(191) DEFAULT NULL,
  `product_custom_field18` varchar(191) DEFAULT NULL,
  `product_custom_field19` varchar(191) DEFAULT NULL,
  `product_custom_field20` varchar(191) DEFAULT NULL,
  `image` varchar(191) DEFAULT NULL,
  `product_description` text DEFAULT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `preparation_time_in_minutes` int(11) DEFAULT NULL,
  `warranty_id` int(11) DEFAULT NULL,
  `is_inactive` tinyint(1) NOT NULL DEFAULT 0,
  `not_for_selling` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `business_id`, `type`, `unit_id`, `secondary_unit_id`, `sub_unit_ids`, `brand_id`, `category_id`, `sub_category_id`, `repair_model_id`, `woocommerce_disable_sync`, `woocommerce_media_id`, `woocommerce_product_id`, `tax`, `tax_type`, `enable_stock`, `alert_quantity`, `sku`, `barcode_type`, `expiry_period`, `expiry_period_type`, `enable_sr_no`, `weight`, `product_custom_field1`, `product_custom_field2`, `product_custom_field3`, `product_custom_field4`, `product_custom_field5`, `product_custom_field6`, `product_custom_field7`, `product_custom_field8`, `product_custom_field9`, `product_custom_field10`, `product_custom_field11`, `product_custom_field12`, `product_custom_field13`, `product_custom_field14`, `product_custom_field15`, `product_custom_field16`, `product_custom_field17`, `product_custom_field18`, `product_custom_field19`, `product_custom_field20`, `image`, `product_description`, `created_by`, `preparation_time_in_minutes`, `warranty_id`, `is_inactive`, `not_for_selling`, `created_at`, `updated_at`) VALUES
(1, 'Men\'s Reverse Fleece Crew', 1, 'single', 1, NULL, NULL, 1, 1, 5, NULL, 0, NULL, NULL, 1, 'exclusive', 1, 5.0000, 'AS0001', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1528728059_fleece_crew.jpg', NULL, 1, NULL, NULL, 0, 0, '2018-01-03 15:29:08', '2018-06-11 01:40:59'),
(2, 'Levis Men\'s Slimmy Fit Jeans', 1, 'variable', 1, NULL, NULL, 1, 1, 4, NULL, 0, NULL, NULL, 1, 'exclusive', 1, 10.0000, 'AS0002', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1528727964_levis_jeans.jpg', NULL, 1, NULL, NULL, 0, 0, '2018-01-03 15:30:35', '2018-06-11 01:39:24'),
(3, 'Men\'s Cozy Hoodie Sweater', 1, 'variable', 1, NULL, NULL, 2, 1, 5, NULL, 0, NULL, NULL, 1, 'exclusive', 1, 10.0000, 'AS0003', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1528728035_cozy_sweater.jpg', NULL, 1, NULL, NULL, 0, 0, '2018-01-03 16:51:52', '2018-06-11 01:40:35'),
(4, 'Puma Brown Sneaker', 1, 'variable', 1, NULL, NULL, 5, 3, 8, NULL, 0, NULL, NULL, 1, 'exclusive', 1, 5.0000, 'AS0004', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1528728147_puma_brown_sneaker.jpg', NULL, 1, NULL, NULL, 0, 0, '2018-01-03 16:54:33', '2018-06-11 01:42:27'),
(8, 'Nike Fashion Sneaker', 1, 'variable', 1, NULL, NULL, 4, 3, 8, NULL, 0, NULL, NULL, 1, 'exclusive', 1, 10.0000, 'AS0008', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1528728076_nike_sneaker.jpg', NULL, 1, NULL, NULL, 0, 0, '2018-01-03 17:10:10', '2018-06-11 01:41:16'),
(9, 'PUMA Men\'s Black Sneaker', 1, 'variable', 1, NULL, NULL, 5, 3, 8, NULL, 0, NULL, NULL, 1, 'exclusive', 1, 10.0000, 'AS0009', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1528728163_puma_brown_sneaker.jpg', NULL, 1, NULL, NULL, 0, 0, '2018-01-03 17:11:57', '2018-06-11 01:42:43'),
(10, 'NIKE Men\'s Running Shoe', 1, 'variable', 1, NULL, NULL, 4, 3, 8, NULL, 0, NULL, NULL, 1, 'exclusive', 1, 10.0000, 'AS0010', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1528728095_nike_running_shoe.jpg', NULL, 1, NULL, NULL, 0, 0, '2018-01-03 17:13:02', '2018-06-11 01:41:35'),
(11, 'U.S. Polo Men\'s Leather Belt', 1, 'single', 1, NULL, NULL, 3, 3, 6, NULL, 0, NULL, NULL, 1, 'exclusive', 1, 15.0000, 'AS0011', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1528727768_belt.jpg', NULL, 1, NULL, NULL, 0, 0, '2018-01-03 17:14:35', '2018-06-11 01:36:08'),
(12, 'Unisex Brown Leather Wallet', 1, 'single', 1, NULL, NULL, 1, 3, 11, NULL, 0, NULL, NULL, 1, 'exclusive', 1, 10.0000, 'AS0012', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1528727750_unisex_brown_wallet.jpg', NULL, 1, NULL, NULL, 0, 0, '2018-01-03 17:15:50', '2018-06-11 01:35:50'),
(13, 'Men Full sleeve T Shirt', 1, 'variable', 1, NULL, NULL, 2, 1, 5, NULL, 0, NULL, NULL, 1, 'exclusive', 1, 15.0000, 'AS0013', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1528728019_mens_tshirt.jpg', NULL, 1, NULL, NULL, 0, 0, '2018-01-03 17:17:59', '2018-06-11 01:40:19'),
(14, 'Samsung Galaxy S8', 1, 'variable', 1, NULL, NULL, 7, 12, 13, NULL, 0, NULL, NULL, 1, 'exclusive', 1, 100.0000, 'AS0014', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1528728193_s8.jpg', NULL, 1, NULL, NULL, 0, 0, '2018-01-05 23:42:19', '2018-06-11 01:43:13'),
(15, 'Apple iPhone 8', 1, 'variable', 1, NULL, NULL, 8, 12, 13, NULL, 0, NULL, NULL, 1, 'exclusive', 1, 100.0000, 'AS0015', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1528727817_iphone8.jpg', NULL, 1, NULL, NULL, 0, 0, '2018-01-05 23:49:51', '2018-06-11 01:36:57'),
(16, 'Samsung Galaxy J7 Pro', 1, 'variable', 1, NULL, NULL, 7, 12, 13, NULL, 0, NULL, NULL, NULL, 'exclusive', 1, 100.0000, 'AS0016', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1528728178_j7_pro.jpg', NULL, 1, NULL, NULL, 0, 0, '2018-01-05 23:54:48', '2018-06-11 01:42:58'),
(17, 'Acer Aspire E 15', 1, 'variable', 1, NULL, NULL, 9, 12, 14, NULL, 0, NULL, NULL, NULL, 'exclusive', 1, 70.0000, 'AS0017', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1528727793_acerE15.jpg', NULL, 1, NULL, NULL, 0, 0, '2018-01-06 00:05:01', '2018-06-11 01:36:33'),
(18, 'Apple MacBook Air', 1, 'variable', 1, NULL, NULL, 8, 12, 14, NULL, 0, NULL, NULL, NULL, 'exclusive', 1, 30.0000, 'AS0018', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1528727849_macbookair.jpg', NULL, 1, NULL, NULL, 0, 0, '2018-01-06 00:07:30', '2018-06-11 01:37:29'),
(19, 'Cushion Crew Socks', 1, 'single', 1, NULL, NULL, 4, 15, 16, NULL, 0, NULL, NULL, NULL, 'exclusive', 1, 100.0000, 'AS0019', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1528727903_socks.jpg', NULL, 1, NULL, NULL, 0, 0, '2018-01-06 00:10:28', '2018-06-11 01:38:23'),
(20, 'Sports Tights Pants', 1, 'variable', 1, NULL, NULL, 6, 15, 16, NULL, 0, NULL, NULL, 1, 'exclusive', 1, 60.0000, 'AS0020', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1528728250_sports_pant.jpg', NULL, 1, NULL, NULL, 0, 0, '2018-01-06 00:13:00', '2018-06-11 01:44:10'),
(21, 'Pair Of Dumbbells', 1, 'single', 1, NULL, NULL, 10, 15, 17, NULL, 0, NULL, NULL, NULL, 'exclusive', 1, 45.0000, 'AS0021', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1528728133_pair_of_dumbell.jpg', NULL, 1, NULL, NULL, 0, 0, '2018-01-06 00:16:35', '2018-06-11 01:42:13'),
(22, 'Diary of a Wimpy Kid', 1, 'single', 1, NULL, NULL, NULL, 18, 20, NULL, 0, NULL, NULL, 1, 'exclusive', 1, 20.0000, 'AS0022', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1528727917_diary_of_whimp_kid.jpeg', NULL, 1, NULL, NULL, 0, 0, '2018-01-06 00:25:09', '2018-06-11 01:38:37'),
(23, 'Sneezy the Snowman', 1, 'single', 1, NULL, NULL, NULL, 18, 20, NULL, 0, NULL, NULL, NULL, 'exclusive', 1, 20.0000, 'AS0023', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1528728231_sneezy.jpg', NULL, 1, NULL, NULL, 0, 0, '2018-01-06 00:26:27', '2018-06-11 01:43:51'),
(24, 'Etched in Sand Autobiography', 1, 'single', 1, NULL, NULL, NULL, 18, 19, NULL, 0, NULL, NULL, 1, 'exclusive', 1, 30.0000, 'AS0024', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1528727933_etched_in_stone.jpg', NULL, 1, NULL, NULL, 0, 0, '2018-01-06 00:31:22', '2018-06-11 01:38:53'),
(25, 'Five Presidents', 1, 'single', 1, NULL, NULL, NULL, 18, 19, NULL, 0, NULL, NULL, NULL, 'exclusive', 1, 30.0000, 'AS0025', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1528727949_five_pesident.jpg', NULL, 1, NULL, NULL, 0, 0, '2018-01-06 00:32:22', '2018-06-11 01:39:09'),
(26, 'Oreo Cookies', 1, 'single', 2, NULL, NULL, 11, 21, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 1, 500.0000, 'AS0026', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1528728111_oreo.jpg', NULL, 1, NULL, NULL, 0, 0, '2018-01-06 00:35:51', '2018-06-11 01:41:51'),
(27, 'Butter Cookies', 1, 'single', 2, NULL, NULL, 12, 21, NULL, NULL, 0, NULL, NULL, 1, 'exclusive', 1, 100.0000, 'AS0027', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1528727881_butter_cookies.jpg', NULL, 1, NULL, NULL, 0, 0, '2018-01-06 00:43:16', '2018-06-11 01:38:01'),
(28, 'Barilla Pasta', 1, 'single', 2, NULL, NULL, 13, 21, NULL, NULL, 0, NULL, NULL, 1, 'exclusive', 1, 50.0000, 'AS0028', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1528727865_barilla_pasta.jpeg', NULL, 1, NULL, NULL, 0, 0, '2018-01-06 00:45:47', '2018-06-11 01:37:45'),
(29, 'Thin Spaghetti', 1, 'single', 2, NULL, NULL, 13, 21, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 1, 100.0000, 'AS0029', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1528728274_thin_spegatti.jpg', NULL, 1, NULL, NULL, 0, 0, '2018-01-06 00:46:53', '2018-06-11 01:44:34'),
(30, 'Lipton Black Tea Bags', 1, 'single', 2, NULL, NULL, 14, 21, NULL, NULL, 0, NULL, NULL, 1, 'exclusive', 1, 50.0000, 'AS0030', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1528727999_lipton_tea.jpg', NULL, 1, NULL, NULL, 0, 0, '2018-01-06 00:48:59', '2018-06-11 01:39:59'),
(31, 'Dolocare 1g paracetol', 2, 'single', 4, NULL, NULL, 16, 27, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 1, 50.0000, 'AP0031', 'C128', 24.00, 'months', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, NULL, NULL, 0, 0, '2018-04-10 04:06:29', '2018-04-10 04:06:29'),
(32, 'Lexin 500mg capsule', 2, 'single', 4, NULL, NULL, 16, 22, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 1, 30.0000, 'AP0032', 'C128', 24.00, 'months', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, NULL, NULL, 0, 0, '2018-04-10 04:07:52', '2018-04-10 04:07:52'),
(33, 'Oflen-75', 2, 'single', 4, NULL, NULL, 15, 22, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 1, 30.0000, 'AP0033', 'C128', 36.00, 'months', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, NULL, NULL, 0, 0, '2018-04-10 04:09:45', '2018-04-10 04:09:45'),
(34, 'Cistiben Forte', 2, 'single', 4, NULL, NULL, 16, 22, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 1, 25.0000, 'AP0034', 'C128', 12.00, 'months', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, NULL, NULL, 0, 0, '2018-04-10 04:10:59', '2018-04-10 04:10:59'),
(35, 'Disflatyl drop 30ml', 2, 'single', 4, NULL, NULL, 17, 25, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 1, 20.0000, 'AP0035', 'C128', 12.00, 'months', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, NULL, NULL, 0, 0, '2018-04-10 04:12:53', '2018-04-10 04:12:53'),
(36, 'Mycoheal 40', 2, 'single', 4, NULL, NULL, 17, 26, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 1, 30.0000, 'AP0036', 'C128', 6.00, 'months', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, NULL, NULL, 0, 0, '2018-04-10 04:14:18', '2018-04-10 04:14:18'),
(37, 'Laboxyl', 2, 'single', 4, NULL, NULL, 16, 24, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 1, 20.0000, 'AP0037', 'C128', 12.00, 'months', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, NULL, NULL, 0, 0, '2018-04-10 04:31:29', '2018-04-10 04:31:29'),
(38, 'Fertilex plus men', 2, 'single', 4, NULL, NULL, 15, 22, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 1, 30.0000, 'AP0038', 'C128', 12.00, 'months', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, NULL, NULL, 0, 0, '2018-04-10 04:32:35', '2018-04-10 04:32:35'),
(39, 'vitamin E AIWA', 2, 'single', 4, NULL, NULL, 15, 22, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 1, 20.0000, 'AP0039', 'C128', 12.00, 'months', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, NULL, NULL, 0, 0, '2018-04-10 04:33:26', '2018-04-10 04:33:26'),
(40, 'Glycerol 4g', 2, 'single', 4, NULL, NULL, 16, 22, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 1, 20.0000, 'AP0040', 'C128', 12.00, 'months', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, NULL, NULL, 0, 0, '2018-04-10 04:34:32', '2018-04-10 04:34:49'),
(41, 'HP 15-AY020TU', 3, 'single', 5, NULL, NULL, 23, 30, NULL, NULL, 0, NULL, NULL, NULL, 'inclusive', 1, 10.0000, 'AE0041', 'C128', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5, NULL, NULL, 0, 0, '2018-04-10 05:08:27', '2018-04-10 05:08:27'),
(42, 'HP Pavilion 15-AU624TX', 3, 'single', 5, NULL, NULL, 23, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 1, 10.0000, 'AE0042', 'C128', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5, NULL, NULL, 0, 0, '2018-04-10 05:15:52', '2018-04-10 05:51:21'),
(43, 'iPhone 6s plus', 3, 'single', 5, NULL, NULL, 20, 28, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 1, 20.0000, 'AE0043', 'C128', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5, NULL, NULL, 0, 0, '2018-04-10 05:18:01', '2018-04-10 05:18:01'),
(44, 'Samsung J7 Pro', 3, 'single', 5, NULL, NULL, 18, 28, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 1, 20.0000, 'AE0044', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5, NULL, NULL, 0, 0, '2018-04-10 05:19:10', '2018-04-10 05:19:10'),
(45, 'Samsung Galaxy S7 Edge', 3, 'single', 5, NULL, NULL, 18, 28, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 1, 20.0000, 'AE0045', 'C128', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5, NULL, NULL, 0, 0, '2018-04-10 05:20:21', '2018-04-10 05:20:21'),
(46, 'LG G6 64 GB', 3, 'single', 5, NULL, NULL, 19, 28, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 1, 10.0000, 'AE0046', 'C128', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5, NULL, NULL, 0, 0, '2018-04-10 05:22:04', '2018-04-10 05:22:04'),
(47, 'Panasonic TH-58D300DX', 3, 'single', 5, NULL, NULL, 22, 31, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 1, 20.0000, 'AE0047', 'C128', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5, NULL, NULL, 0, 0, '2018-04-10 05:23:45', '2018-04-10 05:23:45'),
(48, 'Samsung 50MU6100', 3, 'single', 5, NULL, NULL, 18, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 1, 7.0000, 'AE0048', 'C128', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5, NULL, NULL, 0, 0, '2018-04-10 05:24:42', '2018-04-10 05:52:07'),
(49, 'LG 55E7T', 3, 'single', 5, NULL, NULL, 19, 31, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 1, 5.0000, 'AE0049', 'C128', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5, NULL, NULL, 0, 0, '2018-04-10 05:25:55', '2018-04-10 05:25:55'),
(50, 'SONY ILCE-6000L/B', 3, 'single', 5, NULL, NULL, 21, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 1, 10.0000, 'AE0050', 'C128', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5, NULL, NULL, 0, 0, '2018-04-10 05:34:32', '2018-04-10 05:34:32'),
(51, 'Oil and filter change', 4, 'single', 6, NULL, NULL, NULL, 34, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 0, 0.0000, 'AS0051', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 6, NULL, NULL, 0, 0, '2018-04-10 06:34:57', '2018-04-10 06:34:57'),
(52, 'Removal of wheels and brakes checked', 4, 'single', 6, NULL, NULL, NULL, 34, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 0, 0.0000, 'AS0052', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 6, NULL, NULL, 0, 0, '2018-04-10 06:35:55', '2018-04-10 06:35:55'),
(53, 'Full body wash', 4, 'single', 6, NULL, NULL, NULL, 34, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 0, 0.0000, 'AS0053', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 6, NULL, NULL, 0, 0, '2018-04-10 06:39:15', '2018-04-10 06:39:15'),
(54, 'Hair Cut', 4, 'single', 6, NULL, NULL, NULL, 35, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 0, 0.0000, 'AS0054', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 6, NULL, NULL, 0, 0, '2018-04-10 06:54:16', '2018-04-10 06:54:16'),
(55, 'Wash And Blow Dry', 4, 'single', 6, NULL, NULL, NULL, 35, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 0, 0.0000, 'AS0055', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 6, NULL, NULL, 0, 0, '2018-04-10 06:56:18', '2018-04-10 06:56:18'),
(56, 'Thai massage', 4, 'single', 6, NULL, NULL, NULL, 38, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 0, 0.0000, 'AS0056', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 6, NULL, NULL, 0, 0, '2018-04-10 07:01:59', '2018-04-10 07:01:59'),
(57, 'Toilet Repair', 4, 'single', 6, NULL, NULL, NULL, 36, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 0, 0.0000, 'AS0057', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 6, NULL, NULL, 0, 0, '2018-04-10 07:03:01', '2018-04-10 07:03:01'),
(58, 'Sewer Repair', 4, 'single', 6, NULL, NULL, NULL, 36, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 0, 0.0000, 'AS0058', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 6, NULL, NULL, 0, 0, '2018-04-10 07:03:37', '2018-04-10 07:03:37'),
(59, 'Refrigerator Repair', 4, 'single', 6, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 0, 0.0000, 'AS0059', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 6, NULL, NULL, 0, 0, '2018-04-10 07:06:45', '2018-04-10 07:06:45'),
(60, 'AC Repair', 4, 'single', 6, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 0, 0.0000, 'AS0060', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 6, NULL, NULL, 0, 0, '2018-04-10 07:07:08', '2018-04-10 07:07:08'),
(61, 'Red Wine', 1, 'single', 1, NULL, NULL, NULL, 21, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 1, 10.0000, 'AS0061', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1528779460_wine.jpg', NULL, 1, NULL, NULL, 0, 0, '2018-06-11 15:57:41', '2018-06-11 15:57:41'),
(62, 'Pinot Noir Red Wine', 1, 'single', 1, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 1, 10.0000, 'AS0062', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1528779737_wine2.jpg', NULL, 1, NULL, NULL, 0, 0, '2018-06-11 16:02:17', '2018-06-11 16:02:17'),
(63, 'Banana', 1, 'single', 1, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 1, 100.0000, 'AS0063', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1528780092_banana.jpg', NULL, 1, NULL, NULL, 0, 0, '2018-06-11 16:08:12', '2018-06-11 16:08:12'),
(64, 'Apple - Fuji', 1, 'single', 1, NULL, NULL, NULL, 21, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 1, 50.0000, 'AS0064', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1528780234_apples.jpg', NULL, 1, NULL, NULL, 0, 0, '2018-06-11 16:10:34', '2018-06-11 16:10:34'),
(65, 'Organic Egg', 1, 'single', 1, NULL, NULL, NULL, 21, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 1, 10.0000, 'AS0065', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1528780470_eggs.jpg', NULL, 1, NULL, NULL, 0, 0, '2018-06-11 16:14:00', '2018-06-11 16:14:30'),
(66, 'Honey Roast Chicken', 5, 'single', 7, NULL, NULL, NULL, 39, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 0, 0.0000, 'AR0066', 'C128', NULL, NULL, 0, '300 gm', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1531481431_honey-roast-chicken.jpg', NULL, 7, NULL, NULL, 0, 0, '2018-07-13 00:30:31', '2018-07-13 00:30:31'),
(67, 'Mushroom and Herb Filled Tomatoes', 5, 'single', 7, NULL, NULL, NULL, 39, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 0, 0.0000, 'AR0067', 'C128', NULL, NULL, 0, '150 gm', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1531481687_tomtoes-with-mushroom_med.jpg', NULL, 7, NULL, NULL, 0, 0, '2018-07-13 00:34:47', '2018-07-13 00:34:47'),
(68, 'Pasta Carbonara', 5, 'single', 7, NULL, NULL, NULL, 40, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 0, 0.0000, 'AR0068', 'C128', NULL, NULL, 0, '180 gm', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1531481935_pasta.jpg', NULL, 7, NULL, NULL, 0, 0, '2018-07-13 00:38:55', '2018-07-13 00:38:55'),
(69, 'Chicken Doner Pita with Hummus', 5, 'single', 7, NULL, NULL, NULL, 40, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 0, 0.0000, 'AR0069', 'C128', NULL, NULL, 0, '180 gm', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1531482350_pita.jpg', NULL, 7, NULL, NULL, 0, 0, '2018-07-13 00:45:50', '2018-07-13 00:45:51'),
(70, 'Falafal Doner Salad', 5, 'single', 7, NULL, NULL, NULL, 41, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 0, 0.0000, 'AR0070', 'C128', NULL, NULL, 0, '200 gm', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1531483057_salad-doner.jpg', NULL, 7, NULL, NULL, 0, 0, '2018-07-13 00:57:37', '2018-07-13 00:57:37'),
(71, 'Lamb Doner Salad', 5, 'single', 7, NULL, NULL, NULL, 41, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 0, 0.0000, 'AR0071', 'C128', NULL, NULL, 0, '220 gm', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1531483241_lamb-salad.jpg', NULL, 7, NULL, NULL, 0, 0, '2018-07-13 01:00:41', '2018-07-13 01:00:41'),
(72, 'Chicago-Style Pan Pizza', 5, 'single', 7, NULL, NULL, NULL, 42, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 0, 0.0000, 'AR0072', 'C128', NULL, NULL, 0, '190 gm', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1531483502_pizza1.jpg', NULL, 7, NULL, NULL, 0, 0, '2018-07-13 01:05:02', '2018-07-13 01:05:03'),
(73, 'Pizza Margherita', 5, 'single', 7, NULL, NULL, NULL, 42, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 0, 0.0000, 'AR0073', 'C128', NULL, NULL, 0, '200 gm', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1531483649_margherita-pizza.jpg', NULL, 7, NULL, NULL, 0, 0, '2018-07-13 01:07:29', '2018-07-13 01:07:29'),
(74, 'Blueberry-Ginger Mojito', 5, 'single', 7, NULL, NULL, NULL, 44, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 0, 0.0000, 'AR0074', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1531483843_mojito.jpg', NULL, 7, NULL, NULL, 0, 0, '2018-07-13 01:10:43', '2018-07-13 01:10:44'),
(75, 'Chocolate Brownie', 5, 'single', 7, NULL, NULL, NULL, 43, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 0, 0.0000, 'AR0075', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1531483989_Chocolate-Brownies.jpg', NULL, 7, NULL, NULL, 0, 0, '2018-07-13 01:13:09', '2018-07-13 01:13:09'),
(76, 'Pepsi Soft Drink', 5, 'single', 7, NULL, NULL, NULL, 44, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 1, 50.0000, 'AR0076', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1531484313_pepsi.jpg', NULL, 7, NULL, NULL, 0, 0, '2018-07-13 01:18:33', '2018-07-13 01:18:33'),
(77, 'Brown Bread', 5, 'single', 8, NULL, NULL, NULL, 45, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 1, 50.0000, 'AR0077', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1531484661_brown-bread.jpg', NULL, 7, NULL, NULL, 0, 0, '2018-07-13 01:24:21', '2018-07-13 01:28:56'),
(78, 'Toppings', 5, 'modifier', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'inclusive', 0, 0.0000, 'AR0078', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, NULL, NULL, 0, 0, '2018-07-16 01:58:04', '2018-07-16 01:58:04'),
(79, 'Extra', 5, 'modifier', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'inclusive', 0, 0.0000, 'AR0079', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, NULL, NULL, 0, 0, '2018-07-16 02:05:37', '2018-07-16 02:05:37'),
(81, 'Pizza', 6, 'single', 9, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 1, 10.0000, 'Pizza', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, NULL, NULL, 0, 0, '2019-08-18 12:52:09', '2019-08-18 12:52:09'),
(82, 'Pizza Base Bread', 6, 'single', 9, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 1, 10.0000, '0082', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, NULL, NULL, 0, 0, '2019-08-18 12:54:10', '2019-08-18 12:54:10'),
(83, 'Tomatoes', 6, 'single', 10, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 1, 1000.0000, '0083', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, NULL, NULL, 0, 0, '2019-08-18 12:54:59', '2019-08-18 12:54:59'),
(84, 'Olive Oil', 6, 'single', 10, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 1, 1000.0000, '0084', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, NULL, NULL, 0, 0, '2019-08-18 12:55:28', '2019-08-18 12:55:28'),
(85, 'Salt', 6, 'single', 10, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 1, 1000.0000, '0085', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, NULL, NULL, 0, 0, '2019-08-18 12:55:58', '2019-08-18 12:55:58'),
(86, 'Garlic', 6, 'single', 9, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 1, 50.0000, '0086', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, NULL, NULL, 0, 0, '2019-08-18 12:56:41', '2019-08-18 12:56:41'),
(87, 'Chili Flakes', 6, 'single', 10, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 1, 100.0000, '0087', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, NULL, NULL, 0, 0, '2019-08-18 12:57:35', '2019-08-18 12:57:35'),
(88, 'Cheese', 6, 'single', 10, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 1, 1000.0000, '0088', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, NULL, NULL, 0, 0, '2019-08-18 12:58:43', '2019-08-18 12:58:43'),
(89, 'Silk Fabric', 6, 'single', 13, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 1, 100.0000, '0089', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, NULL, NULL, 0, 0, '2019-08-18 13:15:23', '2019-08-18 13:21:40'),
(90, 'Yarn', 6, 'single', 13, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 1, 5.0000, '0090', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, NULL, NULL, 0, 0, '2019-08-18 13:16:50', '2019-08-18 13:16:50'),
(91, 'Buttons', 6, 'single', 9, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 1, 100.0000, '0091', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, NULL, NULL, 0, 0, '2019-08-18 13:17:36', '2019-08-18 13:17:36'),
(92, 'Zipper', 6, 'single', 9, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 1, 100.0000, '0092', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, NULL, NULL, 0, 0, '2019-08-18 13:18:26', '2019-08-18 13:18:26'),
(93, 'Formal Shirt', 6, 'single', 9, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 1, 100.0000, '0093', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, NULL, NULL, 0, 0, '2019-08-18 13:19:55', '2019-08-18 13:20:39'),
(95, 'Ram', 4, 'single', 6, NULL, NULL, 24, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'exclusive', 1, NULL, 'AS0095', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, 0, '2020-05-07 15:18:57', '2020-05-07 15:18:58'),
(99, 'Display', 4, 'single', 6, NULL, NULL, 7, NULL, NULL, 1, 0, NULL, NULL, 1, 'exclusive', 1, 20.0000, 'AS0099', 'C128', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, 0, '2020-05-11 06:47:54', '2020-05-11 06:47:54'),
(100, 'Mother Board', 4, 'single', 6, NULL, NULL, 24, NULL, NULL, 1, 0, NULL, NULL, NULL, 'exclusive', 1, 52.0000, 'AS0100', 'C128', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, 0, '2020-05-11 06:48:40', '2020-05-11 06:48:41'),
(101, 'Battery Kit', 4, 'single', 6, NULL, NULL, 24, NULL, NULL, 1, 0, NULL, NULL, NULL, 'exclusive', 1, 32.0000, 'AS0101', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, 0, '2020-05-11 06:49:21', '2020-05-11 06:49:21'),
(102, 'Mother Board Repair', 4, 'single', 6, NULL, NULL, 24, NULL, NULL, 2, 0, NULL, NULL, NULL, 'exclusive', 0, 0.0000, 'AS0102', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, 0, '2020-05-11 06:54:22', '2020-05-11 06:54:23'),
(103, 'Device Repair', 4, 'single', 6, NULL, NULL, 24, NULL, NULL, 1, 0, NULL, NULL, NULL, 'exclusive', 0, 0.0000, 'AS0103', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, 0, '2020-05-11 06:55:24', '2020-05-11 06:55:24'),
(104, 'Battery Kit Replacement', 4, 'single', 6, NULL, NULL, 24, NULL, NULL, 1, 0, NULL, NULL, NULL, 'exclusive', 0, 0.0000, 'AS0104', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, 0, '2020-05-11 06:56:45', '2020-05-11 06:56:45'),
(105, 'Display', 4, 'single', 6, NULL, NULL, 24, NULL, NULL, 2, 0, NULL, NULL, NULL, 'exclusive', 1, 55.0000, 'AS0105', 'C128', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, 0, '2020-05-11 06:58:12', '2020-05-11 06:58:12'),
(106, 'Mother Board', 4, 'single', 6, NULL, NULL, 24, NULL, NULL, 2, 0, NULL, NULL, NULL, 'exclusive', 1, 66.0000, 'AS0106', 'C128', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, 0, '2020-05-11 06:58:50', '2020-05-11 06:58:50'),
(107, 'Back Glass', 4, 'single', 6, NULL, NULL, 24, NULL, NULL, 2, 0, NULL, NULL, NULL, 'exclusive', 1, 36.0000, 'AS0107', 'C128', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, 0, '2020-05-11 06:59:22', '2020-05-11 06:59:22'),
(108, 'Mother board repair service', 4, 'single', 6, NULL, NULL, 24, NULL, NULL, 2, 0, NULL, NULL, NULL, 'exclusive', 0, 0.0000, 'AS0108', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, 0, '2020-05-11 07:00:23', '2020-05-11 07:00:23'),
(109, 'Device repair service', 4, 'single', 6, NULL, NULL, 24, NULL, NULL, 2, 0, NULL, NULL, NULL, 'exclusive', 0, 0.0000, 'AS0109', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, 0, '2020-05-11 07:01:11', '2020-05-11 07:01:11'),
(110, 'Battry repair service', 4, 'single', 6, NULL, NULL, 24, NULL, NULL, 2, 0, NULL, NULL, NULL, 'exclusive', 0, 0.0000, 'AS0110', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, 0, '2020-05-11 07:01:56', '2020-05-11 07:01:56'),
(111, 'Mother Board', 4, 'single', 6, NULL, NULL, 24, NULL, NULL, 3, 0, NULL, NULL, NULL, 'exclusive', 1, 10.0000, 'AS0111', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, 0, '2020-05-11 07:10:25', '2020-05-11 07:10:25'),
(112, 'RAM', 4, 'single', 6, NULL, NULL, 24, NULL, NULL, 3, 0, NULL, NULL, NULL, 'exclusive', 1, 23.0000, 'AS0112', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, 0, '2020-05-11 07:10:57', '2020-05-11 07:10:57'),
(113, 'Display', 4, 'single', 6, NULL, NULL, 24, NULL, NULL, 3, 0, NULL, NULL, NULL, 'exclusive', 1, 24.0000, 'AS0113', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, 0, '2020-05-11 07:12:17', '2020-05-11 07:12:17'),
(114, 'Monitor Service', 4, 'single', 6, NULL, NULL, 24, NULL, NULL, 3, 0, NULL, NULL, NULL, 'exclusive', 0, 0.0000, 'AS0114', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, 0, '2020-05-11 07:12:47', '2020-05-11 07:12:47'),
(115, 'CPU Service', 4, 'single', 6, NULL, NULL, 24, NULL, NULL, 3, 0, NULL, NULL, NULL, 'exclusive', 0, 0.0000, 'AS0115', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, 0, '2020-05-11 07:13:30', '2020-05-11 07:13:30'),
(116, 'CPU', 4, 'single', 6, NULL, NULL, 24, NULL, NULL, 4, 0, NULL, NULL, NULL, 'exclusive', 1, 56.0000, 'AS0116', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, 0, '2020-05-11 07:14:18', '2020-05-11 07:14:18'),
(117, 'Hard Drive', 4, 'single', 6, NULL, NULL, 24, NULL, NULL, 3, 0, NULL, NULL, NULL, 'exclusive', 1, 13.0000, 'AS0117', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, 0, '2020-05-11 07:15:05', '2020-05-11 07:15:05'),
(118, 'Optical Drive', 4, 'single', 6, NULL, NULL, 24, NULL, NULL, 4, 0, NULL, NULL, NULL, 'exclusive', 1, 15.0000, 'AS0118', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, 0, '2020-05-11 07:15:45', '2020-05-11 07:15:45'),
(119, 'Video Card', 4, 'single', 6, NULL, NULL, 24, NULL, NULL, 4, 0, NULL, NULL, NULL, 'exclusive', 1, 21.0000, 'AS0119', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, 0, '2020-05-11 07:17:11', '2020-05-11 07:17:11'),
(120, 'CPU Repair', 4, 'single', 6, NULL, NULL, 24, NULL, NULL, 4, 0, NULL, NULL, NULL, 'exclusive', 0, 0.0000, 'AS0120', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, 0, '2020-05-11 07:17:54', '2020-05-11 07:17:54'),
(121, 'Processor Service', 4, 'single', 6, NULL, NULL, 24, NULL, NULL, 4, 0, NULL, NULL, NULL, 'exclusive', 0, 0.0000, 'AS0121', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, 0, '2020-05-11 07:18:38', '2020-05-11 07:18:38'),
(122, 'Key Board', 4, 'single', 6, NULL, NULL, 24, NULL, NULL, 5, 0, NULL, NULL, NULL, 'exclusive', 1, 22.0000, 'AS0122', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, 0, '2020-05-11 07:19:40', '2020-05-11 07:19:40'),
(123, 'Mouse', 4, 'single', 6, NULL, NULL, 24, NULL, NULL, 5, 0, NULL, NULL, NULL, 'exclusive', 1, 56.0000, 'AS0123', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, 0, '2020-05-11 07:20:15', '2020-05-11 07:20:15'),
(124, 'Optical Drive', 4, 'single', 6, NULL, NULL, 24, NULL, NULL, 5, 0, NULL, NULL, NULL, 'exclusive', 1, 5.0000, 'AS0124', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, 0, '2020-05-11 07:20:50', '2020-05-11 07:20:51'),
(125, 'Key Board Repair', 4, 'single', 6, NULL, NULL, 24, NULL, NULL, 5, 0, NULL, NULL, NULL, 'exclusive', 0, 0.0000, 'AS0125', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, 0, '2020-05-11 07:21:42', '2020-05-11 07:21:42'),
(126, 'Video Card Service', 4, 'single', 6, NULL, NULL, 24, NULL, NULL, 5, 0, NULL, NULL, NULL, 'exclusive', 0, 0.0000, 'AS0126', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, 0, '2020-05-11 07:22:43', '2020-05-11 07:22:43'),
(127, 'Tower(Case) Repair', 4, 'single', 6, NULL, NULL, 24, NULL, NULL, 5, 0, NULL, NULL, NULL, 'exclusive', 0, 0.0000, 'AS0127', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, 0, '2020-05-11 07:23:29', '2020-05-11 07:23:29'),
(128, 'Tower (Case)', 4, 'single', 6, NULL, NULL, 24, NULL, NULL, 6, 0, NULL, NULL, NULL, 'exclusive', 1, 56.0000, 'AS0128', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, 0, '2020-05-11 07:24:57', '2020-05-11 07:24:57'),
(129, 'Hard Drive', 4, 'single', 6, NULL, NULL, 24, NULL, NULL, 6, 0, NULL, NULL, NULL, 'exclusive', 1, NULL, 'AS0129', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, 0, '2020-05-11 07:25:41', '2020-05-11 07:25:41'),
(130, 'Battery Kit', 4, 'single', 6, NULL, NULL, 24, NULL, NULL, 6, 0, NULL, NULL, NULL, 'exclusive', 1, NULL, 'AS0130', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, 0, '2020-05-11 07:26:17', '2020-05-11 07:26:17'),
(131, 'Mouse', 4, 'single', 6, NULL, NULL, 24, NULL, NULL, 6, 0, NULL, NULL, NULL, 'exclusive', 1, NULL, 'AS0131', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, 0, '2020-05-11 07:27:51', '2020-05-11 07:27:51'),
(132, 'Touchpad Service', 4, 'single', 6, NULL, NULL, 24, NULL, NULL, 6, 0, NULL, NULL, NULL, 'exclusive', 0, 0.0000, 'AS0132', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, 0, '2020-05-11 07:30:08', '2020-05-11 07:30:09'),
(133, 'Antenna Repair', 4, 'single', 6, NULL, NULL, 24, NULL, NULL, 6, 0, NULL, NULL, NULL, 'exclusive', 0, 0.0000, 'AS0133', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, 0, '2020-05-11 07:31:14', '2020-05-11 07:31:14'),
(134, 'Cooling Fan Repair', 4, 'single', 6, NULL, NULL, 24, NULL, NULL, 6, 0, NULL, NULL, NULL, 'exclusive', 0, 0.0000, 'AS0134', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, 0, '2020-05-11 07:32:01', '2020-05-11 07:32:01'),
(135, 'MacBook Air Cooling Heatsink', 4, 'single', 6, NULL, NULL, 25, NULL, NULL, 7, 0, NULL, NULL, NULL, 'exclusive', 1, 2.0000, 'AS0135', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, 0, '2020-05-11 07:34:38', '2020-05-11 07:34:38'),
(136, 'Loudspeaker internal speaker', 4, 'single', 6, NULL, NULL, 25, NULL, NULL, 7, 0, NULL, NULL, NULL, 'exclusive', 1, 1.0000, 'AS0136', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, 0, '2020-05-11 07:35:40', '2020-05-11 07:35:40'),
(137, 'Macbook IC CHIP X', 4, 'single', 6, NULL, NULL, 25, NULL, NULL, 7, 0, NULL, NULL, NULL, 'exclusive', 1, 2.0000, 'AS0137', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, 0, '2020-05-11 07:36:24', '2020-05-11 07:36:25'),
(138, 'Mac  185W Power Supply', 4, 'single', 6, NULL, NULL, 25, NULL, NULL, 7, 0, NULL, NULL, NULL, 'exclusive', 1, NULL, 'AS0138', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, 0, '2020-05-11 07:37:28', '2020-05-11 07:37:28'),
(139, 'Processor Service', 4, 'single', 6, NULL, NULL, 25, NULL, NULL, 7, 0, NULL, NULL, NULL, 'exclusive', 0, 0.0000, 'AS0139', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, 0, '2020-05-11 07:38:24', '2020-05-11 07:38:25'),
(140, 'Screen Cable Connector Repair', 4, 'single', 6, NULL, NULL, 25, NULL, NULL, 7, 0, NULL, NULL, NULL, 'exclusive', 0, 0.0000, 'AS0140', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, 0, '2020-05-11 07:39:16', '2020-05-11 07:39:16'),
(141, 'Macbook Pro ME664/665 USB board Service', 4, 'single', 6, NULL, NULL, 25, NULL, NULL, 7, 0, NULL, NULL, NULL, 'exclusive', 0, 0.0000, 'AS0141', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, 0, '2020-05-11 07:40:54', '2020-05-11 07:40:55'),
(142, 'iMac Retina 13 Left&Right Speakers', 4, 'single', 6, NULL, NULL, 25, NULL, NULL, 8, 0, NULL, NULL, NULL, 'exclusive', 1, NULL, 'AS0142', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, 0, '2020-05-11 07:42:14', '2020-05-11 07:42:14');
INSERT INTO `products` (`id`, `name`, `business_id`, `type`, `unit_id`, `secondary_unit_id`, `sub_unit_ids`, `brand_id`, `category_id`, `sub_category_id`, `repair_model_id`, `woocommerce_disable_sync`, `woocommerce_media_id`, `woocommerce_product_id`, `tax`, `tax_type`, `enable_stock`, `alert_quantity`, `sku`, `barcode_type`, `expiry_period`, `expiry_period_type`, `enable_sr_no`, `weight`, `product_custom_field1`, `product_custom_field2`, `product_custom_field3`, `product_custom_field4`, `product_custom_field5`, `product_custom_field6`, `product_custom_field7`, `product_custom_field8`, `product_custom_field9`, `product_custom_field10`, `product_custom_field11`, `product_custom_field12`, `product_custom_field13`, `product_custom_field14`, `product_custom_field15`, `product_custom_field16`, `product_custom_field17`, `product_custom_field18`, `product_custom_field19`, `product_custom_field20`, `image`, `product_description`, `created_by`, `preparation_time_in_minutes`, `warranty_id`, `is_inactive`, `not_for_selling`, `created_at`, `updated_at`) VALUES
(143, 'QWERTY Standard  Keyboard No backlight', 4, 'single', 6, NULL, NULL, 25, NULL, NULL, 8, 0, NULL, NULL, NULL, 'exclusive', 1, NULL, 'AS0143', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, 0, '2020-05-11 07:43:25', '2020-05-11 07:43:25'),
(144, 'WIFI bluetooth cable', 4, 'single', 6, NULL, NULL, 25, NULL, NULL, 8, 0, NULL, NULL, NULL, 'exclusive', 1, NULL, 'AS0144', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, 0, '2020-05-11 07:44:10', '2020-05-11 07:44:10'),
(145, 'Wifi Repair', 4, 'single', 6, NULL, NULL, 25, NULL, NULL, 8, 0, NULL, NULL, NULL, 'exclusive', 0, 0.0000, 'AS0145', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, 0, '2020-05-11 07:44:52', '2020-05-11 07:44:53'),
(146, 'iMac Unibody Trackpad Touchpad Repair', 4, 'single', 6, NULL, NULL, 25, NULL, NULL, 8, 0, NULL, NULL, NULL, 'exclusive', 0, 0.0000, 'AS0146', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, 0, '2020-05-11 07:46:18', '2020-05-11 07:46:18'),
(147, 'BIOS chip Repair', 4, 'single', 6, NULL, NULL, 25, NULL, NULL, 8, 0, NULL, NULL, NULL, 'exclusive', 0, 0.0000, 'AS0147', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, 0, '2020-05-11 07:47:32', '2020-05-11 07:47:32'),
(148, 'iPhone X Screen', 4, 'single', 6, NULL, NULL, 25, NULL, NULL, 9, 0, NULL, NULL, NULL, 'exclusive', 1, NULL, 'AS0148', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, 0, '2020-05-11 07:48:51', '2020-05-11 07:48:51'),
(149, 'Lightning to USB Charging Cable', 4, 'single', 6, NULL, NULL, 25, NULL, NULL, 9, 0, NULL, NULL, NULL, 'exclusive', 1, NULL, 'AS0149', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, 0, '2020-05-11 07:49:20', '2020-05-11 07:49:20'),
(150, 'iHold EVO', 4, 'single', 6, NULL, NULL, 25, NULL, NULL, 9, 0, NULL, NULL, NULL, 'exclusive', 1, NULL, 'AS0150', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, 0, '2020-05-11 07:49:45', '2020-05-11 07:49:45'),
(151, 'iPhone X Replacement Battery', 4, 'single', 6, NULL, NULL, 25, NULL, NULL, 9, 0, NULL, NULL, NULL, 'exclusive', 0, 0.0000, 'AS0151', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, 0, '2020-05-11 07:50:12', '2020-05-11 07:50:12'),
(152, 'Lightning Connector Assembly Repair', 4, 'single', 6, NULL, NULL, 25, NULL, NULL, 9, 0, NULL, NULL, NULL, 'exclusive', 0, 0.0000, 'AS0152', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, 0, '2020-05-11 07:51:17', '2020-05-11 07:51:17'),
(153, 'iHold EVO Service', 4, 'single', 6, NULL, NULL, 25, NULL, NULL, 9, 0, NULL, NULL, NULL, 'exclusive', 0, 0.0000, 'AS0153', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, 0, '2020-05-11 07:52:03', '2020-05-11 07:52:03'),
(154, 'Mother board Service', 4, 'single', 6, NULL, NULL, 25, NULL, NULL, 9, 0, NULL, NULL, NULL, 'exclusive', 0, 0.0000, 'AS0154', 'C128', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, 0, '2020-05-11 07:52:47', '2020-05-11 07:52:47');

-- --------------------------------------------------------

--
-- Table structure for table `product_locations`
--

CREATE TABLE `product_locations` (
  `product_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_locations`
--

INSERT INTO `product_locations` (`product_id`, `location_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1),
(29, 1),
(30, 1),
(61, 1),
(62, 1),
(63, 1),
(64, 1),
(65, 1),
(41, 3),
(42, 3),
(43, 3),
(44, 3),
(45, 3),
(46, 3),
(47, 3),
(48, 3),
(49, 3),
(50, 3),
(31, 2),
(32, 2),
(33, 2),
(34, 2),
(35, 2),
(36, 2),
(37, 2),
(38, 2),
(39, 2),
(40, 2),
(51, 4),
(52, 4),
(53, 4),
(54, 4),
(55, 4),
(56, 4),
(57, 4),
(58, 4),
(59, 4),
(60, 4),
(81, 6),
(82, 6),
(83, 6),
(84, 6),
(85, 6),
(86, 6),
(87, 6),
(88, 6),
(89, 6),
(90, 6),
(91, 6),
(92, 6),
(93, 6),
(74, 5),
(77, 5),
(72, 5),
(69, 5),
(75, 5),
(70, 5),
(66, 5),
(71, 5),
(67, 5),
(68, 5),
(76, 5),
(73, 5),
(154, 4),
(95, 4),
(99, 4),
(100, 4),
(101, 4),
(102, 4),
(103, 4),
(104, 4),
(105, 4),
(106, 4),
(107, 4),
(108, 4),
(109, 4),
(110, 4),
(111, 4),
(112, 4),
(113, 4),
(114, 4),
(115, 4),
(116, 4),
(117, 4),
(118, 4),
(119, 4),
(120, 4),
(121, 4),
(122, 4),
(123, 4),
(124, 4),
(125, 4),
(126, 4),
(127, 4),
(128, 4),
(129, 4),
(130, 4),
(131, 4),
(132, 4),
(133, 4),
(134, 4),
(135, 4),
(136, 4),
(137, 4),
(138, 4),
(139, 4),
(140, 4),
(141, 4),
(142, 4),
(143, 4),
(144, 4),
(145, 4),
(146, 4),
(147, 4),
(148, 4),
(149, 4),
(150, 4),
(151, 4),
(152, 4),
(153, 4);

-- --------------------------------------------------------

--
-- Table structure for table `product_racks`
--

CREATE TABLE `product_racks` (
  `id` int(10) UNSIGNED NOT NULL,
  `business_id` int(10) UNSIGNED NOT NULL,
  `location_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `rack` varchar(191) DEFAULT NULL,
  `row` varchar(191) DEFAULT NULL,
  `position` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_variations`
--

CREATE TABLE `product_variations` (
  `id` int(10) UNSIGNED NOT NULL,
  `variation_template_id` int(11) DEFAULT NULL,
  `name` varchar(191) NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `is_dummy` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_variations`
--

INSERT INTO `product_variations` (`id`, `variation_template_id`, `name`, `product_id`, `is_dummy`, `created_at`, `updated_at`) VALUES
(1, NULL, 'DUMMY', 1, 1, '2018-01-03 15:29:08', '2018-01-03 15:29:08'),
(2, 5, 'Waist Size', 2, 0, '2018-01-03 15:30:35', '2020-05-08 03:24:13'),
(3, 6, 'Size', 3, 0, '2018-01-03 16:51:52', '2020-05-08 03:24:14'),
(4, 6, 'Size', 4, 0, '2018-01-03 16:54:34', '2020-05-08 03:24:14'),
(8, 6, 'Size', 8, 0, '2018-01-03 17:10:10', '2020-05-08 03:24:14'),
(9, 6, 'Size', 9, 0, '2018-01-03 17:11:57', '2020-05-08 03:24:14'),
(10, 6, 'Size', 10, 0, '2018-01-03 17:13:02', '2020-05-08 03:24:14'),
(11, NULL, 'DUMMY', 11, 1, '2018-01-03 17:14:35', '2018-01-03 17:14:35'),
(12, NULL, 'DUMMY', 12, 1, '2018-01-03 17:15:50', '2018-01-03 17:15:50'),
(13, 6, 'Size', 13, 0, '2018-01-03 17:17:59', '2020-05-08 03:24:14'),
(14, 4, 'Color', 14, 0, '2018-01-05 23:42:19', '2020-05-08 03:24:14'),
(15, 7, 'Internal Memory', 14, 0, '2018-01-05 23:44:14', '2020-05-08 03:24:14'),
(16, 4, 'Color', 15, 0, '2018-01-05 23:49:51', '2020-05-08 03:24:14'),
(17, 7, 'Internal Memory', 15, 0, '2018-01-05 23:49:51', '2020-05-08 03:24:14'),
(18, 4, 'Color', 16, 0, '2018-01-05 23:54:48', '2020-05-08 03:24:14'),
(19, 4, 'Color', 17, 0, '2018-01-06 00:05:01', '2020-05-08 03:24:14'),
(20, 8, 'Storage', 18, 0, '2018-01-06 00:07:30', '2020-05-08 03:24:14'),
(21, NULL, 'DUMMY', 19, 1, '2018-01-06 00:10:28', '2018-01-06 00:10:28'),
(22, 4, 'Color', 20, 0, '2018-01-06 00:13:00', '2020-05-08 03:24:14'),
(23, NULL, 'DUMMY', 21, 1, '2018-01-06 00:16:35', '2018-01-06 00:16:35'),
(24, NULL, 'DUMMY', 22, 1, '2018-01-06 00:25:09', '2018-01-06 00:25:09'),
(25, NULL, 'DUMMY', 23, 1, '2018-01-06 00:26:27', '2018-01-06 00:26:27'),
(26, NULL, 'DUMMY', 24, 1, '2018-01-06 00:31:22', '2018-01-06 00:31:22'),
(27, NULL, 'DUMMY', 25, 1, '2018-01-06 00:32:23', '2018-01-06 00:32:23'),
(28, NULL, 'DUMMY', 26, 1, '2018-01-06 00:35:51', '2018-01-06 00:35:51'),
(29, NULL, 'DUMMY', 27, 1, '2018-01-06 00:43:16', '2018-01-06 00:43:16'),
(30, NULL, 'DUMMY', 28, 1, '2018-01-06 00:45:47', '2018-01-06 00:45:47'),
(31, NULL, 'DUMMY', 29, 1, '2018-01-06 00:46:53', '2018-01-06 00:46:53'),
(32, NULL, 'DUMMY', 30, 1, '2018-01-06 00:48:59', '2018-01-06 00:48:59'),
(33, NULL, 'DUMMY', 31, 1, '2018-04-10 04:06:29', '2018-04-10 04:06:29'),
(34, NULL, 'DUMMY', 32, 1, '2018-04-10 04:07:52', '2018-04-10 04:07:52'),
(35, NULL, 'DUMMY', 33, 1, '2018-04-10 04:09:45', '2018-04-10 04:09:45'),
(36, NULL, 'DUMMY', 34, 1, '2018-04-10 04:10:59', '2018-04-10 04:10:59'),
(37, NULL, 'DUMMY', 35, 1, '2018-04-10 04:12:53', '2018-04-10 04:12:53'),
(38, NULL, 'DUMMY', 36, 1, '2018-04-10 04:14:18', '2018-04-10 04:14:18'),
(39, NULL, 'DUMMY', 37, 1, '2018-04-10 04:31:29', '2018-04-10 04:31:29'),
(40, NULL, 'DUMMY', 38, 1, '2018-04-10 04:32:35', '2018-04-10 04:32:35'),
(41, NULL, 'DUMMY', 39, 1, '2018-04-10 04:33:26', '2018-04-10 04:33:26'),
(42, NULL, 'DUMMY', 40, 1, '2018-04-10 04:34:32', '2018-04-10 04:34:32'),
(43, NULL, 'DUMMY', 41, 1, '2018-04-10 05:08:27', '2018-04-10 05:08:27'),
(44, NULL, 'DUMMY', 42, 1, '2018-04-10 05:15:52', '2018-04-10 05:15:52'),
(45, NULL, 'DUMMY', 43, 1, '2018-04-10 05:18:01', '2018-04-10 05:18:01'),
(46, NULL, 'DUMMY', 44, 1, '2018-04-10 05:19:10', '2018-04-10 05:19:10'),
(47, NULL, 'DUMMY', 45, 1, '2018-04-10 05:20:22', '2018-04-10 05:20:22'),
(48, NULL, 'DUMMY', 46, 1, '2018-04-10 05:22:04', '2018-04-10 05:22:04'),
(49, NULL, 'DUMMY', 47, 1, '2018-04-10 05:23:45', '2018-04-10 05:23:45'),
(50, NULL, 'DUMMY', 48, 1, '2018-04-10 05:24:42', '2018-04-10 05:24:42'),
(51, NULL, 'DUMMY', 49, 1, '2018-04-10 05:25:55', '2018-04-10 05:25:55'),
(52, NULL, 'DUMMY', 50, 1, '2018-04-10 05:34:32', '2018-04-10 05:34:32'),
(53, NULL, 'DUMMY', 51, 1, '2018-04-10 06:34:57', '2018-04-10 06:34:57'),
(54, NULL, 'DUMMY', 52, 1, '2018-04-10 06:35:55', '2018-04-10 06:35:55'),
(55, NULL, 'DUMMY', 53, 1, '2018-04-10 06:39:15', '2018-04-10 06:39:15'),
(56, NULL, 'DUMMY', 54, 1, '2018-04-10 06:54:16', '2018-04-10 06:54:16'),
(57, NULL, 'DUMMY', 55, 1, '2018-04-10 06:56:18', '2018-04-10 06:56:18'),
(58, NULL, 'DUMMY', 56, 1, '2018-04-10 07:01:59', '2018-04-10 07:01:59'),
(59, NULL, 'DUMMY', 57, 1, '2018-04-10 07:03:01', '2018-04-10 07:03:01'),
(60, NULL, 'DUMMY', 58, 1, '2018-04-10 07:03:37', '2018-04-10 07:03:37'),
(61, NULL, 'DUMMY', 59, 1, '2018-04-10 07:06:45', '2018-04-10 07:06:45'),
(62, NULL, 'DUMMY', 60, 1, '2018-04-10 07:07:08', '2018-04-10 07:07:08'),
(63, NULL, 'DUMMY', 61, 1, '2018-04-10 07:07:08', '2018-04-10 07:07:08'),
(64, NULL, 'DUMMY', 62, 1, '2018-04-10 07:07:08', '2018-04-10 07:07:08'),
(65, NULL, 'DUMMY', 63, 1, '2018-04-10 07:07:08', '2018-04-10 07:07:08'),
(66, NULL, 'DUMMY', 64, 1, '2018-04-10 07:07:08', '2018-04-10 07:07:08'),
(67, NULL, 'DUMMY', 65, 1, '2018-04-10 07:07:08', '2018-04-10 07:07:08'),
(68, NULL, 'DUMMY', 66, 1, '2018-07-12 19:00:31', '2018-07-12 19:00:31'),
(69, NULL, 'DUMMY', 67, 1, '2018-07-12 19:04:47', '2018-07-12 19:04:47'),
(70, NULL, 'DUMMY', 68, 1, '2018-07-12 19:08:55', '2018-07-12 19:08:55'),
(71, NULL, 'DUMMY', 69, 1, '2018-07-12 19:15:51', '2018-07-12 19:15:51'),
(72, NULL, 'DUMMY', 70, 1, '2018-07-12 19:27:37', '2018-07-12 19:27:37'),
(73, NULL, 'DUMMY', 71, 1, '2018-07-12 19:30:41', '2018-07-12 19:30:41'),
(74, NULL, 'DUMMY', 72, 1, '2018-07-12 19:35:03', '2018-07-12 19:35:03'),
(75, NULL, 'DUMMY', 73, 1, '2018-07-12 19:37:29', '2018-07-12 19:37:29'),
(76, NULL, 'DUMMY', 74, 1, '2018-07-12 19:40:44', '2018-07-12 19:40:44'),
(77, NULL, 'DUMMY', 75, 1, '2018-07-12 19:43:09', '2018-07-12 19:43:09'),
(78, NULL, 'DUMMY', 76, 1, '2018-07-12 19:48:33', '2018-07-12 19:48:33'),
(79, NULL, 'DUMMY', 77, 1, '2018-07-12 19:54:22', '2018-07-12 19:54:22'),
(80, NULL, 'DUMMY', 78, 0, '2018-07-16 01:58:04', '2018-07-16 01:58:04'),
(81, NULL, 'DUMMY', 79, 0, '2018-07-16 02:05:37', '2018-07-16 02:05:37'),
(83, NULL, 'DUMMY', 81, 1, '2019-08-18 12:52:09', '2019-08-18 12:52:09'),
(84, NULL, 'DUMMY', 82, 1, '2019-08-18 12:54:10', '2019-08-18 12:54:10'),
(85, NULL, 'DUMMY', 83, 1, '2019-08-18 12:54:59', '2019-08-18 12:54:59'),
(86, NULL, 'DUMMY', 84, 1, '2019-08-18 12:55:28', '2019-08-18 12:55:28'),
(87, NULL, 'DUMMY', 85, 1, '2019-08-18 12:55:58', '2019-08-18 12:55:58'),
(88, NULL, 'DUMMY', 86, 1, '2019-08-18 12:56:41', '2019-08-18 12:56:41'),
(89, NULL, 'DUMMY', 87, 1, '2019-08-18 12:57:35', '2019-08-18 12:57:35'),
(90, NULL, 'DUMMY', 88, 1, '2019-08-18 12:58:43', '2019-08-18 12:58:43'),
(91, NULL, 'DUMMY', 89, 1, '2019-08-18 13:15:23', '2019-08-18 13:15:23'),
(92, NULL, 'DUMMY', 90, 1, '2019-08-18 13:16:50', '2019-08-18 13:16:50'),
(93, NULL, 'DUMMY', 91, 1, '2019-08-18 13:17:36', '2019-08-18 13:17:36'),
(94, NULL, 'DUMMY', 92, 1, '2019-08-18 13:18:26', '2019-08-18 13:18:26'),
(95, NULL, 'DUMMY', 93, 1, '2019-08-18 13:19:55', '2019-08-18 13:19:55'),
(96, NULL, 'DUMMY', 94, 1, '2020-05-07 15:18:14', '2020-05-07 15:18:14'),
(97, NULL, 'DUMMY', 95, 1, '2020-05-07 15:18:58', '2020-05-07 15:18:58'),
(98, NULL, 'DUMMY', 96, 1, '2020-05-07 15:20:36', '2020-05-07 15:20:36'),
(99, NULL, 'DUMMY', 97, 1, '2020-05-07 15:21:08', '2020-05-07 15:21:08'),
(100, NULL, 'DUMMY', 98, 1, '2020-05-07 15:21:48', '2020-05-07 15:21:48'),
(101, NULL, 'DUMMY', 99, 1, '2020-05-11 06:47:54', '2020-05-11 06:47:54'),
(102, NULL, 'DUMMY', 100, 1, '2020-05-11 06:48:41', '2020-05-11 06:48:41'),
(103, NULL, 'DUMMY', 101, 1, '2020-05-11 06:49:21', '2020-05-11 06:49:21'),
(104, NULL, 'DUMMY', 102, 1, '2020-05-11 06:54:23', '2020-05-11 06:54:23'),
(105, NULL, 'DUMMY', 103, 1, '2020-05-11 06:55:24', '2020-05-11 06:55:24'),
(106, NULL, 'DUMMY', 104, 1, '2020-05-11 06:56:45', '2020-05-11 06:56:45'),
(107, NULL, 'DUMMY', 105, 1, '2020-05-11 06:58:12', '2020-05-11 06:58:12'),
(108, NULL, 'DUMMY', 106, 1, '2020-05-11 06:58:50', '2020-05-11 06:58:50'),
(109, NULL, 'DUMMY', 107, 1, '2020-05-11 06:59:22', '2020-05-11 06:59:22'),
(110, NULL, 'DUMMY', 108, 1, '2020-05-11 07:00:23', '2020-05-11 07:00:23'),
(111, NULL, 'DUMMY', 109, 1, '2020-05-11 07:01:11', '2020-05-11 07:01:11'),
(112, NULL, 'DUMMY', 110, 1, '2020-05-11 07:01:56', '2020-05-11 07:01:56'),
(113, NULL, 'DUMMY', 111, 1, '2020-05-11 07:10:25', '2020-05-11 07:10:25'),
(114, NULL, 'DUMMY', 112, 1, '2020-05-11 07:10:57', '2020-05-11 07:10:57'),
(115, NULL, 'DUMMY', 113, 1, '2020-05-11 07:12:17', '2020-05-11 07:12:17'),
(116, NULL, 'DUMMY', 114, 1, '2020-05-11 07:12:47', '2020-05-11 07:12:47'),
(117, NULL, 'DUMMY', 115, 1, '2020-05-11 07:13:30', '2020-05-11 07:13:30'),
(118, NULL, 'DUMMY', 116, 1, '2020-05-11 07:14:18', '2020-05-11 07:14:18'),
(119, NULL, 'DUMMY', 117, 1, '2020-05-11 07:15:05', '2020-05-11 07:15:05'),
(120, NULL, 'DUMMY', 118, 1, '2020-05-11 07:15:45', '2020-05-11 07:15:45'),
(121, NULL, 'DUMMY', 119, 1, '2020-05-11 07:17:11', '2020-05-11 07:17:11'),
(122, NULL, 'DUMMY', 120, 1, '2020-05-11 07:17:54', '2020-05-11 07:17:54'),
(123, NULL, 'DUMMY', 121, 1, '2020-05-11 07:18:38', '2020-05-11 07:18:38'),
(124, NULL, 'DUMMY', 122, 1, '2020-05-11 07:19:40', '2020-05-11 07:19:40'),
(125, NULL, 'DUMMY', 123, 1, '2020-05-11 07:20:15', '2020-05-11 07:20:15'),
(126, NULL, 'DUMMY', 124, 1, '2020-05-11 07:20:50', '2020-05-11 07:20:50'),
(127, NULL, 'DUMMY', 125, 1, '2020-05-11 07:21:42', '2020-05-11 07:21:42'),
(128, NULL, 'DUMMY', 126, 1, '2020-05-11 07:22:43', '2020-05-11 07:22:43'),
(129, NULL, 'DUMMY', 127, 1, '2020-05-11 07:23:29', '2020-05-11 07:23:29'),
(130, NULL, 'DUMMY', 128, 1, '2020-05-11 07:24:57', '2020-05-11 07:24:57'),
(131, NULL, 'DUMMY', 129, 1, '2020-05-11 07:25:41', '2020-05-11 07:25:41'),
(132, NULL, 'DUMMY', 130, 1, '2020-05-11 07:26:17', '2020-05-11 07:26:17'),
(133, NULL, 'DUMMY', 131, 1, '2020-05-11 07:27:51', '2020-05-11 07:27:51'),
(134, NULL, 'DUMMY', 132, 1, '2020-05-11 07:30:09', '2020-05-11 07:30:09'),
(135, NULL, 'DUMMY', 133, 1, '2020-05-11 07:31:14', '2020-05-11 07:31:14'),
(136, NULL, 'DUMMY', 134, 1, '2020-05-11 07:32:01', '2020-05-11 07:32:01'),
(137, NULL, 'DUMMY', 135, 1, '2020-05-11 07:34:38', '2020-05-11 07:34:38'),
(138, NULL, 'DUMMY', 136, 1, '2020-05-11 07:35:40', '2020-05-11 07:35:40'),
(139, NULL, 'DUMMY', 137, 1, '2020-05-11 07:36:25', '2020-05-11 07:36:25'),
(140, NULL, 'DUMMY', 138, 1, '2020-05-11 07:37:28', '2020-05-11 07:37:28'),
(141, NULL, 'DUMMY', 139, 1, '2020-05-11 07:38:24', '2020-05-11 07:38:24'),
(142, NULL, 'DUMMY', 140, 1, '2020-05-11 07:39:16', '2020-05-11 07:39:16'),
(143, NULL, 'DUMMY', 141, 1, '2020-05-11 07:40:55', '2020-05-11 07:40:55'),
(144, NULL, 'DUMMY', 142, 1, '2020-05-11 07:42:14', '2020-05-11 07:42:14'),
(145, NULL, 'DUMMY', 143, 1, '2020-05-11 07:43:25', '2020-05-11 07:43:25'),
(146, NULL, 'DUMMY', 144, 1, '2020-05-11 07:44:10', '2020-05-11 07:44:10'),
(147, NULL, 'DUMMY', 145, 1, '2020-05-11 07:44:53', '2020-05-11 07:44:53'),
(148, NULL, 'DUMMY', 146, 1, '2020-05-11 07:46:18', '2020-05-11 07:46:18'),
(149, NULL, 'DUMMY', 147, 1, '2020-05-11 07:47:32', '2020-05-11 07:47:32'),
(150, NULL, 'DUMMY', 148, 1, '2020-05-11 07:48:51', '2020-05-11 07:48:51'),
(151, NULL, 'DUMMY', 149, 1, '2020-05-11 07:49:20', '2020-05-11 07:49:20'),
(152, NULL, 'DUMMY', 150, 1, '2020-05-11 07:49:45', '2020-05-11 07:49:45'),
(153, NULL, 'DUMMY', 151, 1, '2020-05-11 07:50:12', '2020-05-11 07:50:12'),
(154, NULL, 'DUMMY', 152, 1, '2020-05-11 07:51:17', '2020-05-11 07:51:17'),
(155, NULL, 'DUMMY', 153, 1, '2020-05-11 07:52:03', '2020-05-11 07:52:03'),
(156, NULL, 'DUMMY', 154, 1, '2020-05-11 07:52:47', '2020-05-11 07:52:47');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_lines`
--

CREATE TABLE `purchase_lines` (
  `id` int(10) UNSIGNED NOT NULL,
  `transaction_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `variation_id` int(10) UNSIGNED NOT NULL,
  `quantity` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `secondary_unit_quantity` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `pp_without_discount` decimal(22,4) NOT NULL DEFAULT 0.0000 COMMENT 'Purchase price before inline discounts',
  `discount_percent` decimal(5,2) NOT NULL DEFAULT 0.00 COMMENT 'Inline discount percentage',
  `purchase_price` decimal(22,4) NOT NULL,
  `purchase_price_inc_tax` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `item_tax` decimal(22,4) NOT NULL COMMENT 'Tax for one quantity',
  `tax_id` int(10) UNSIGNED DEFAULT NULL,
  `purchase_requisition_line_id` int(11) DEFAULT NULL,
  `purchase_order_line_id` int(11) DEFAULT NULL,
  `quantity_sold` decimal(22,4) NOT NULL DEFAULT 0.0000 COMMENT 'Quanity sold from this purchase line',
  `quantity_adjusted` decimal(22,4) NOT NULL DEFAULT 0.0000 COMMENT 'Quanity adjusted in stock adjustment from this purchase line',
  `quantity_returned` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `po_quantity_purchased` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `mfg_quantity_used` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `mfg_date` date DEFAULT NULL,
  `exp_date` date DEFAULT NULL,
  `lot_number` varchar(191) DEFAULT NULL,
  `sub_unit_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_lines`
--

INSERT INTO `purchase_lines` (`id`, `transaction_id`, `product_id`, `variation_id`, `quantity`, `secondary_unit_quantity`, `pp_without_discount`, `discount_percent`, `purchase_price`, `purchase_price_inc_tax`, `item_tax`, `tax_id`, `purchase_requisition_line_id`, `purchase_order_line_id`, `quantity_sold`, `quantity_adjusted`, `quantity_returned`, `po_quantity_purchased`, `mfg_quantity_used`, `mfg_date`, `exp_date`, `lot_number`, `sub_unit_id`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 2, 100.0000, 0.0000, 70.0000, 0.00, 70.0000, 77.0000, 7.0000, 1, NULL, NULL, 50.0000, 0.0000, 0.0000, 0.0000, 0.0000, NULL, NULL, NULL, NULL, '2018-01-06 00:57:11', '2018-06-13 05:39:08'),
(2, 1, 2, 3, 150.0000, 0.0000, 70.0000, 0.00, 70.0000, 77.0000, 7.0000, 1, NULL, NULL, 60.0000, 0.0000, 0.0000, 0.0000, 0.0000, NULL, NULL, NULL, NULL, '2018-01-06 00:57:11', '2018-06-13 05:39:08'),
(3, 1, 2, 4, 150.0000, 0.0000, 70.0000, 0.00, 70.0000, 77.0000, 7.0000, 1, NULL, NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, NULL, NULL, NULL, NULL, '2018-01-06 00:57:11', '2018-06-13 05:39:07'),
(4, 1, 2, 5, 150.0000, 0.0000, 72.0000, 0.00, 72.0000, 79.2000, 7.2000, 1, NULL, NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, NULL, NULL, NULL, NULL, '2018-01-06 00:57:11', '2018-06-13 05:39:07'),
(5, 1, 2, 6, 100.0000, 0.0000, 72.0000, 0.00, 72.0000, 79.2000, 7.2000, 1, NULL, NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, NULL, NULL, NULL, NULL, '2018-01-06 00:57:11', '2018-06-13 05:39:07'),
(6, 2, 14, 47, 100.0000, 0.0000, 700.0000, 0.00, 700.0000, 770.0000, 70.0000, 1, NULL, NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, NULL, NULL, NULL, NULL, '2018-01-06 00:58:10', '2018-06-13 05:39:07'),
(7, 3, 28, 71, 500.0000, 0.0000, 10.0000, 0.00, 10.0000, 11.0000, 1.0000, 1, NULL, NULL, 30.0000, 0.0000, 0.0000, 0.0000, 0.0000, NULL, NULL, NULL, NULL, '2018-01-06 01:02:22', '2018-06-13 05:39:08'),
(8, 4, 21, 64, 200.0000, 0.0000, 10.0000, 0.00, 10.0000, 10.0000, 0.0000, NULL, NULL, NULL, 60.0000, 0.0000, 0.0000, 0.0000, 0.0000, NULL, NULL, NULL, NULL, '2018-01-06 01:03:12', '2018-06-13 05:39:08'),
(9, 5, 27, 70, 500.0000, 0.0000, 20.0000, 0.00, 20.0000, 22.0000, 2.0000, 1, NULL, NULL, 30.0000, 0.0000, 0.0000, 0.0000, 0.0000, NULL, NULL, NULL, NULL, '2018-01-06 01:05:26', '2018-06-13 05:39:07'),
(10, 11, 34, 77, 50.0000, 0.0000, 6.0000, 0.00, 6.0000, 6.0000, 0.0000, NULL, NULL, NULL, 10.0000, 0.0000, 0.0000, 0.0000, 0.0000, '2018-04-10', '2027-06-28', NULL, NULL, '2018-04-10 04:18:16', '2018-06-13 05:39:08'),
(11, 12, 32, 75, 100.0000, 0.0000, 12.0000, 0.00, 12.0000, 12.0000, 0.0000, NULL, NULL, NULL, 7.0000, 0.0000, 0.0000, 0.0000, 0.0000, '2018-04-12', '2026-12-28', NULL, NULL, '2018-04-10 04:19:40', '2018-06-13 05:39:08'),
(12, 13, 36, 79, 150.0000, 0.0000, 9.0000, 0.00, 9.0000, 9.0000, 0.0000, NULL, NULL, NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, '2018-03-30', '2027-06-28', NULL, NULL, '2018-04-10 04:20:41', '2018-06-13 05:39:07'),
(13, 14, 33, 76, 180.0000, 0.0000, 12.0000, 0.00, 12.0000, 12.0000, 0.0000, NULL, NULL, NULL, 27.0000, 0.0000, 0.0000, 0.0000, 0.0000, '2018-04-10', '2026-06-26', NULL, NULL, '2018-04-10 04:21:38', '2018-06-13 05:39:08'),
(14, 18, 46, 89, 30.0000, 0.0000, 321.0000, 0.00, 321.0000, 321.0000, 0.0000, NULL, NULL, NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, NULL, NULL, NULL, NULL, '2018-04-10 05:39:54', '2018-06-13 05:39:07'),
(15, 19, 41, 84, 40.0000, 0.0000, 500.0000, 0.00, 500.0000, 500.0000, 0.0000, NULL, NULL, NULL, 26.0000, 0.0000, 0.0000, 0.0000, 0.0000, NULL, NULL, NULL, NULL, '2018-04-10 05:40:51', '2018-06-13 05:39:09'),
(16, 20, 45, 88, 45.0000, 0.0000, 300.0000, 0.00, 300.0000, 300.0000, 0.0000, NULL, NULL, NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, NULL, NULL, NULL, NULL, '2018-04-10 05:41:45', '2018-06-13 05:39:07'),
(17, 21, 44, 87, 100.0000, 0.0000, 195.0000, 0.00, 195.0000, 195.0000, 0.0000, NULL, NULL, NULL, 20.0000, 0.0000, 0.0000, 0.0000, 0.0000, NULL, NULL, NULL, NULL, '2018-04-10 05:42:34', '2018-06-13 05:39:09'),
(18, 22, 43, 86, 30.0000, 0.0000, 400.0000, 0.00, 400.0000, 400.0000, 0.0000, NULL, NULL, NULL, 5.0000, 0.0000, 0.0000, 0.0000, 0.0000, NULL, NULL, NULL, NULL, '2018-04-10 05:43:12', '2018-06-13 05:39:09'),
(19, 27, 42, 85, 50.0000, 0.0000, 520.0000, 0.00, 520.0000, 520.0000, 0.0000, NULL, NULL, NULL, 20.0000, 0.0000, 0.0000, 0.0000, 0.0000, NULL, NULL, NULL, NULL, '2018-04-10 05:53:36', '2018-06-13 05:39:09'),
(20, 37, 61, 104, 20.0000, 0.0000, 34.0000, 0.00, 34.0000, 34.0000, 0.0000, NULL, NULL, NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, NULL, NULL, NULL, NULL, '2018-06-11 10:47:07', '2018-06-13 05:39:07'),
(21, 37, 64, 107, 200.0000, 0.0000, 10.0000, 0.00, 10.0000, 10.0000, 0.0000, NULL, NULL, NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, NULL, NULL, NULL, NULL, '2018-06-11 10:47:07', '2018-06-13 05:39:07'),
(22, 37, 63, 106, 200.0000, 0.0000, 10.0000, 0.00, 10.0000, 10.0000, 0.0000, NULL, NULL, NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, NULL, NULL, NULL, NULL, '2018-06-11 10:47:07', '2018-06-13 05:39:07'),
(23, 37, 65, 108, 50.0000, 0.0000, 10.0000, 0.00, 10.0000, 10.0000, 0.0000, NULL, NULL, NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, NULL, NULL, NULL, NULL, '2018-06-11 10:47:07', '2018-06-13 05:39:07'),
(24, 38, 17, 57, 30.0000, 0.0000, 350.0000, 0.00, 350.0000, 350.0000, 0.0000, NULL, NULL, NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, NULL, NULL, NULL, NULL, '2018-06-12 17:18:04', '2018-06-12 17:18:04'),
(25, 38, 17, 58, 50.0000, 0.0000, 350.0000, 0.00, 350.0000, 350.0000, 0.0000, NULL, NULL, NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, NULL, NULL, NULL, NULL, '2018-06-12 17:18:04', '2018-06-12 17:18:04'),
(26, 38, 64, 107, 100.0000, 0.0000, 10.0000, 0.00, 10.0000, 10.0000, 0.0000, NULL, NULL, NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, NULL, NULL, NULL, NULL, '2018-06-12 17:18:04', '2018-06-12 17:18:04'),
(27, 38, 15, 49, 30.0000, 0.0000, 950.0000, 0.00, 950.0000, 1045.0000, 95.0000, 1, NULL, NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, NULL, NULL, NULL, NULL, '2018-06-12 17:18:04', '2018-06-12 17:18:04'),
(28, 38, 15, 50, 20.0000, 0.0000, 950.0000, 0.00, 950.0000, 1045.0000, 95.0000, 1, NULL, NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, NULL, NULL, NULL, NULL, '2018-06-12 17:18:04', '2018-06-12 17:18:04'),
(29, 38, 15, 51, 30.0000, 0.0000, 950.0000, 0.00, 950.0000, 1045.0000, 95.0000, 1, NULL, NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, NULL, NULL, NULL, NULL, '2018-06-12 17:18:04', '2018-06-12 17:18:04'),
(30, 38, 15, 52, 20.0000, 0.0000, 950.0000, 0.00, 950.0000, 1045.0000, 95.0000, 1, NULL, NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, NULL, NULL, NULL, NULL, '2018-06-12 17:18:04', '2018-06-12 17:18:04'),
(31, 38, 15, 53, 40.0000, 0.0000, 1010.0000, 0.00, 1010.0000, 1111.0000, 101.0000, 1, NULL, NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, NULL, NULL, NULL, NULL, '2018-06-12 17:18:04', '2018-06-12 17:18:04'),
(32, 38, 18, 59, 20.0000, 0.0000, 1350.0000, 0.00, 1350.0000, 1350.0000, 0.0000, NULL, NULL, NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, NULL, NULL, NULL, NULL, '2018-06-12 17:18:04', '2018-06-12 17:18:04'),
(33, 38, 18, 60, 20.0000, 0.0000, 1450.0000, 0.00, 1450.0000, 1450.0000, 0.0000, NULL, NULL, NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, NULL, NULL, NULL, NULL, '2018-06-12 17:18:04', '2018-06-12 17:18:04'),
(34, 38, 63, 106, 100.0000, 0.0000, 10.0000, 0.00, 10.0000, 10.0000, 0.0000, NULL, NULL, NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, NULL, NULL, NULL, NULL, '2018-06-12 17:18:04', '2018-06-12 17:18:04'),
(35, 38, 19, 61, 30.0000, 0.0000, 8.0000, 0.00, 8.0000, 8.0000, 0.0000, NULL, NULL, NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, NULL, NULL, NULL, NULL, '2018-06-12 17:18:04', '2018-06-12 17:18:04'),
(36, 38, 22, 65, 10.0000, 0.0000, 8.0000, 0.00, 8.0000, 8.8000, 0.8000, 1, NULL, NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, NULL, NULL, NULL, NULL, '2018-06-12 17:18:04', '2018-06-12 17:18:04'),
(37, 38, 24, 67, 10.0000, 0.0000, 8.0000, 0.00, 8.0000, 8.8000, 0.8000, 1, NULL, NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, NULL, NULL, NULL, NULL, '2018-06-12 17:18:04', '2018-06-12 17:18:04'),
(38, 38, 25, 68, 20.0000, 0.0000, 15.0000, 0.00, 15.0000, 15.0000, 0.0000, NULL, NULL, NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, NULL, NULL, NULL, NULL, '2018-06-12 17:18:04', '2018-06-12 17:18:04'),
(39, 39, 77, 120, 100.0000, 0.0000, 3.0000, 0.00, 3.0000, 3.0000, 0.0000, NULL, NULL, NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, NULL, NULL, NULL, NULL, '2018-07-13 01:34:45', '2018-07-13 01:34:45'),
(40, 39, 76, 119, 200.0000, 0.0000, 8.0000, 0.00, 8.0000, 8.0000, 0.0000, NULL, NULL, NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, NULL, NULL, NULL, NULL, '2018-07-13 01:34:45', '2018-07-13 01:34:45'),
(41, 40, 76, 119, 100.0000, 0.0000, 8.0000, 0.00, 8.0000, 8.0000, 0.0000, NULL, NULL, NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, NULL, NULL, NULL, NULL, '2018-07-13 01:38:24', '2018-07-13 01:38:24'),
(42, 45, 88, 136, 2000.0000, 0.0000, 0.5000, 0.00, 0.5000, 0.5000, 0.0000, NULL, NULL, NULL, 0.0000, 0.0000, 0.0000, 0.0000, 100.0000, NULL, NULL, NULL, NULL, '2019-08-18 13:01:34', '2019-08-18 13:09:20'),
(43, 46, 87, 135, 2000.0000, 0.0000, 0.2000, 0.00, 0.2000, 0.2000, 0.0000, NULL, NULL, NULL, 0.0000, 0.0000, 0.0000, 0.0000, 500.0000, NULL, NULL, NULL, NULL, '2019-08-18 13:01:49', '2019-08-18 13:09:20'),
(44, 47, 86, 134, 1000.0000, 0.0000, 0.5000, 0.00, 0.5000, 0.5000, 0.0000, NULL, NULL, NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, NULL, NULL, NULL, NULL, '2019-08-18 13:02:07', '2019-08-18 13:02:07'),
(45, 48, 84, 132, 2000.0000, 0.0000, 0.2000, 0.00, 0.2000, 0.2000, 0.0000, NULL, NULL, NULL, 0.0000, 0.0000, 0.0000, 0.0000, 50.0000, NULL, NULL, NULL, NULL, '2019-08-18 13:02:20', '2019-08-18 13:09:20'),
(46, 49, 82, 130, 1000.0000, 0.0000, 20.0000, 0.00, 20.0000, 20.0000, 0.0000, NULL, NULL, NULL, 0.0000, 0.0000, 0.0000, 0.0000, 5.0000, NULL, NULL, NULL, NULL, '2019-08-18 13:02:33', '2019-08-18 13:09:20'),
(47, 50, 85, 133, 100.0000, 0.0000, 0.2000, 0.00, 0.2000, 0.2000, 0.0000, NULL, NULL, NULL, 0.0000, 0.0000, 0.0000, 0.0000, 100.0000, NULL, NULL, NULL, NULL, '2019-08-18 13:02:52', '2019-08-18 13:09:20'),
(48, 51, 83, 131, 2000.0000, 0.0000, 0.1000, 0.00, 0.1000, 0.1000, 0.0000, NULL, NULL, NULL, 0.0000, 0.0000, 0.0000, 0.0000, 502.5000, NULL, NULL, NULL, NULL, '2019-08-18 13:03:10', '2019-08-18 13:09:20'),
(49, 52, 81, 129, 5.0000, 0.0000, 2937.0000, 0.00, 2937.0000, 2937.0000, 0.0000, NULL, NULL, NULL, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, '2019-08-18', NULL, NULL, NULL, '2019-08-18 13:09:19', '2019-08-18 13:09:19');

-- --------------------------------------------------------

--
-- Table structure for table `reference_counts`
--

CREATE TABLE `reference_counts` (
  `id` int(10) UNSIGNED NOT NULL,
  `ref_type` varchar(191) NOT NULL,
  `ref_count` int(11) NOT NULL,
  `business_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reference_counts`
--

INSERT INTO `reference_counts` (`id`, `ref_type`, `ref_count`, `business_id`, `created_at`, `updated_at`) VALUES
(1, 'purchase', 1, 1, '2018-06-11 16:17:07', '2018-06-11 16:17:07'),
(2, 'contacts', 6, 1, '2018-06-11 16:17:07', '2018-06-11 16:17:07'),
(3, 'contacts', 2, 5, '2018-07-13 10:42:11', '2018-07-13 01:33:50'),
(4, 'business_location', 1, 5, '2018-07-13 10:42:11', '2018-07-13 10:42:11'),
(5, 'purchase', 2, 5, '2018-07-13 01:34:45', '2018-07-13 01:38:24'),
(6, 'sell_payment', 4, 5, '2018-07-13 01:44:40', '2018-07-13 01:46:36'),
(7, 'contacts', 1, 6, '2019-08-18 12:41:28', '2019-08-18 12:41:28'),
(8, 'business_location', 1, 6, '2019-08-18 12:41:28', '2019-08-18 12:41:28'),
(9, 'production_purchase', 1, 6, '2019-08-18 13:09:19', '2019-08-18 13:09:19');

-- --------------------------------------------------------

--
-- Table structure for table `repair_device_models`
--

CREATE TABLE `repair_device_models` (
  `id` int(10) UNSIGNED NOT NULL,
  `business_id` int(10) UNSIGNED NOT NULL,
  `device_id` int(10) UNSIGNED NOT NULL,
  `brand_id` int(10) UNSIGNED DEFAULT NULL,
  `name` varchar(191) NOT NULL,
  `repair_checklist` text DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `repair_device_models`
--

INSERT INTO `repair_device_models` (`id`, `business_id`, `device_id`, `brand_id`, `name`, `repair_checklist`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 4, 48, 24, 'Samsung Galaxy M21', '\"MIC|WiFi|Bluetooth|Sound|Camera|Ram\"', 1, '2020-05-07 15:07:24', '2020-05-11 06:35:00'),
(2, 4, 48, 24, 'Samsung Galaxy S20+', '\"MIC|WiFi|Bluetooth|Sound|Camera|Ram\"', 1, '2020-05-07 15:08:11', '2020-05-11 06:34:34'),
(3, 4, 47, 24, 'DB43J 43 Inch Full HD Monitor', '\"RAM|Wifi|keyBoard|Sound Quallity | Battery capacity | Mouse | USB Ports | Graphics\"', 1, '2020-05-07 15:09:15', '2020-05-11 06:33:47'),
(4, 4, 47, 24, 'LS24E510CS 23.5 inch LED Monitor', '\"Keyboard|Ram|Sound Quallity | Battery capacity | Mouse | USB Ports | Graphics\"', 1, '2020-05-07 15:10:11', '2020-05-11 06:33:28'),
(5, 4, 46, 24, 'Samsung ChromeBook XE50', '\"Camera | Windows | KeyBoard | Mouse | Battery capacity | Ram | USB Ports | Graphics\"', 1, '2020-05-07 15:11:44', '2020-05-11 06:30:48'),
(6, 4, 46, 24, 'Samsung Spin 7 NP730QAA', '\"Battery capacity | Ram | Mouse | USB Ports | Graphics\"', 1, '2020-05-07 15:12:48', '2020-05-11 06:30:18'),
(7, 4, 46, 25, 'Apple MacBook Pro MVVK2LL', '\"Camera | Windows | KeyBoard | Mouse | Battery capacity | Ram | USB Ports | Graphics\"', 1, '2020-05-07 15:13:54', '2020-05-11 06:37:44'),
(8, 4, 47, 25, 'iMac (Retina 5K, 27-inch, 2019)', '\"Keyboard|Ram|Sound Quallity | Battery capacity | Mouse | USB Ports | Graphics\"', 1, '2020-05-07 15:14:39', '2020-05-11 06:38:06'),
(9, 4, 48, 25, 'Apple iPhone X', '\"MIC|WiFi|Bluetooth|Sound|Camera|Ram\"', 1, '2020-05-07 15:15:38', '2020-05-11 06:38:48');

-- --------------------------------------------------------

--
-- Table structure for table `repair_statuses`
--

CREATE TABLE `repair_statuses` (
  `id` int(10) UNSIGNED NOT NULL,
  `business_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `color` varchar(20) DEFAULT NULL,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `repair_statuses`
--

INSERT INTO `repair_statuses` (`id`, `business_id`, `name`, `color`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 4, 'Repaired', '#3bd914', 4, '2020-05-07 15:02:40', '2020-05-07 15:02:40'),
(2, 4, 'In Progress', '#e3b81e', 2, '2020-05-07 15:03:14', '2020-05-07 15:03:14'),
(3, 4, 'Not Started', '#e3631b', 1, '2020-05-07 15:03:43', '2020-05-07 15:03:43'),
(4, 4, 'Can\'t be repaired', '#f5164b', 3, '2020-05-07 15:04:10', '2020-05-08 04:04:58');

-- --------------------------------------------------------

--
-- Table structure for table `res_product_modifier_sets`
--

CREATE TABLE `res_product_modifier_sets` (
  `modifier_set_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL COMMENT 'Table use to store the modifier sets applicable for a product'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `res_product_modifier_sets`
--

INSERT INTO `res_product_modifier_sets` (`modifier_set_id`, `product_id`) VALUES
(78, 72),
(78, 73),
(79, 73),
(79, 69),
(79, 72),
(79, 75),
(79, 68);

-- --------------------------------------------------------

--
-- Table structure for table `res_tables`
--

CREATE TABLE `res_tables` (
  `id` int(10) UNSIGNED NOT NULL,
  `business_id` int(10) UNSIGNED NOT NULL,
  `location_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `res_tables`
--

INSERT INTO `res_tables` (`id`, `business_id`, `location_id`, `name`, `description`, `created_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 5, 5, 'Table 1', NULL, 7, NULL, '2018-07-13 01:40:57', '2018-07-13 01:40:57'),
(2, 5, 5, 'Table 2', NULL, 7, NULL, '2018-07-13 01:41:04', '2018-07-13 01:41:04'),
(3, 5, 5, 'Table 3', NULL, 7, NULL, '2018-07-13 01:41:10', '2018-07-13 01:41:10'),
(4, 5, 5, 'Table 4', NULL, 7, NULL, '2018-07-13 01:41:17', '2018-07-13 01:41:17');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `guard_name` varchar(191) NOT NULL,
  `business_id` int(10) UNSIGNED NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `is_service_staff` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `business_id`, `is_default`, `is_service_staff`, `created_at`, `updated_at`) VALUES
(1, 'Admin#1', 'web', 1, 1, 0, '2026-06-28 08:37:30', '2026-06-28 08:37:30'),
(2, 'Cashier#1', 'web', 1, 0, 0, '2026-06-28 08:37:30', '2026-06-28 08:37:30'),
(3, 'Admin#2', 'web', 2, 1, 0, '2026-06-28 08:37:31', '2026-06-28 08:37:31'),
(4, 'Cashier#2', 'web', 2, 0, 0, '2026-06-28 08:37:31', '2026-06-28 08:37:31'),
(5, 'Admin#3', 'web', 3, 1, 0, '2026-06-28 08:37:31', '2026-06-28 09:05:37'),
(6, 'Cashier#3', 'web', 3, 0, 0, '2026-06-28 08:37:31', '2026-06-28 08:37:31'),
(7, 'Admin#4', 'web', 4, 1, 0, '2026-06-28 08:37:32', '2026-06-28 08:37:32'),
(8, 'Cashier#4', 'web', 4, 0, 0, '2026-06-28 08:37:32', '2026-06-28 08:37:32'),
(9, 'Admin#5', 'web', 5, 1, 0, '2026-06-28 08:37:32', '2026-06-28 08:37:32'),
(10, 'Cashier#5', 'web', 5, 0, 0, '2026-06-28 08:37:32', '2026-06-28 08:37:32'),
(11, 'Waiter#5', 'web', 5, 0, 1, '2026-06-28 08:37:32', '2026-06-28 08:37:32'),
(12, 'Admin#6', 'web', 6, 1, 0, '2026-06-28 08:37:32', '2026-06-28 08:37:32'),
(13, 'Cashier#6', 'web', 6, 0, 0, '2026-06-28 09:05:37', '2026-06-28 09:05:37');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(25, 2),
(25, 4),
(25, 6),
(25, 8),
(25, 10),
(25, 13),
(26, 2),
(26, 4),
(26, 6),
(26, 8),
(26, 10),
(26, 13),
(27, 2),
(27, 4),
(27, 6),
(27, 8),
(27, 10),
(27, 13),
(48, 2),
(48, 4),
(48, 6),
(48, 8),
(48, 10),
(48, 13),
(49, 2),
(49, 4),
(49, 6),
(49, 8),
(49, 10),
(49, 13),
(50, 2),
(50, 4),
(50, 6),
(50, 8),
(50, 10),
(50, 13),
(51, 2),
(51, 4),
(51, 6),
(51, 8),
(51, 10),
(51, 13),
(81, 2),
(81, 4),
(81, 6),
(81, 8),
(81, 10),
(81, 11),
(81, 13);

-- --------------------------------------------------------

--
-- Table structure for table `selling_price_groups`
--

CREATE TABLE `selling_price_groups` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `business_id` int(10) UNSIGNED NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sell_line_warranties`
--

CREATE TABLE `sell_line_warranties` (
  `sell_line_id` int(11) NOT NULL,
  `warranty_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(191) NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` text NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_adjustments_temp`
--

CREATE TABLE `stock_adjustments_temp` (
  `id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_adjustment_lines`
--

CREATE TABLE `stock_adjustment_lines` (
  `id` int(10) UNSIGNED NOT NULL,
  `transaction_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `variation_id` int(10) UNSIGNED NOT NULL,
  `quantity` decimal(22,4) NOT NULL,
  `secondary_unit_quantity` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `unit_price` decimal(22,4) DEFAULT NULL COMMENT 'Last purchase unit price',
  `removed_purchase_line` int(11) DEFAULT NULL,
  `lot_no_line_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` int(10) UNSIGNED NOT NULL,
  `business_id` int(10) UNSIGNED NOT NULL,
  `package_id` int(10) UNSIGNED NOT NULL,
  `start_date` date DEFAULT NULL,
  `trial_end_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `package_price` decimal(22,4) NOT NULL,
  `original_price` decimal(22,4) DEFAULT NULL,
  `coupon_code` varchar(191) DEFAULT NULL,
  `package_details` longtext NOT NULL,
  `created_id` int(10) UNSIGNED NOT NULL,
  `paid_via` varchar(191) DEFAULT NULL,
  `payment_transaction_id` varchar(191) DEFAULT NULL,
  `status` enum('approved','waiting','declined') NOT NULL DEFAULT 'waiting',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `superadmin_communicator_logs`
--

CREATE TABLE `superadmin_communicator_logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `business_ids` text DEFAULT NULL,
  `subject` varchar(191) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `superadmin_coupons`
--

CREATE TABLE `superadmin_coupons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `coupon_code` varchar(191) NOT NULL,
  `discount_type` varchar(191) NOT NULL,
  `discount` decimal(8,2) NOT NULL,
  `expiry_date` date DEFAULT NULL,
  `applied_on_packages` varchar(191) DEFAULT NULL,
  `applied_on_business` varchar(191) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `superadmin_frontend_pages`
--

CREATE TABLE `superadmin_frontend_pages` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(191) DEFAULT NULL,
  `slug` varchar(191) NOT NULL,
  `content` longtext NOT NULL,
  `is_shown` tinyint(1) NOT NULL,
  `menu_order` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `system`
--

CREATE TABLE `system` (
  `id` int(10) UNSIGNED NOT NULL,
  `key` varchar(191) NOT NULL,
  `value` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `system`
--

INSERT INTO `system` (`id`, `key`, `value`) VALUES
(1, 'db_version', '7.1'),
(2, 'default_business_active_status', '1'),
(3, 'superadmin_version', '6.2'),
(4, 'app_currency_id', '2'),
(5, 'invoice_business_name', 'Ultimate POS'),
(6, 'invoice_business_landmark', 'Landmark'),
(7, 'invoice_business_zip', 'Zip'),
(8, 'invoice_business_state', 'State'),
(9, 'invoice_business_city', 'City'),
(10, 'invoice_business_country', 'Country'),
(11, 'email', 'superadmin@example.com'),
(12, 'package_expiry_alert_days', '5'),
(13, 'enable_business_based_username', '0');

-- --------------------------------------------------------

--
-- Table structure for table `tax_rates`
--

CREATE TABLE `tax_rates` (
  `id` int(10) UNSIGNED NOT NULL,
  `business_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `amount` double(22,4) NOT NULL,
  `is_tax_group` tinyint(1) NOT NULL DEFAULT 0,
  `for_tax_group` tinyint(1) NOT NULL DEFAULT 0,
  `created_by` int(10) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tax_rates`
--

INSERT INTO `tax_rates` (`id`, `business_id`, `name`, `amount`, `is_tax_group`, `for_tax_group`, `created_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'VAT@10%', 10.0000, 0, 0, 1, NULL, '2018-01-03 20:40:07', '2018-01-03 20:40:07'),
(2, 1, 'CGST@10%', 10.0000, 0, 0, 1, NULL, '2018-01-03 20:40:55', '2018-01-03 20:40:55'),
(3, 1, 'SGST@8%', 8.0000, 0, 0, 1, NULL, '2018-01-03 20:41:13', '2018-01-03 20:41:13'),
(4, 1, 'GST@18%', 18.0000, 1, 0, 1, NULL, '2018-01-03 20:42:19', '2018-01-03 20:42:19');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(10) UNSIGNED NOT NULL,
  `business_id` int(10) UNSIGNED NOT NULL,
  `location_id` int(10) UNSIGNED DEFAULT NULL,
  `is_kitchen_order` tinyint(1) NOT NULL DEFAULT 0,
  `res_table_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'fields to restaurant module',
  `res_waiter_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'fields to restaurant module',
  `res_order_status` enum('received','cooked','served') DEFAULT NULL,
  `type` varchar(191) DEFAULT NULL,
  `sub_type` varchar(20) DEFAULT NULL,
  `status` varchar(191) NOT NULL,
  `sub_status` varchar(191) DEFAULT NULL,
  `is_quotation` tinyint(1) NOT NULL DEFAULT 0,
  `payment_status` enum('paid','due','partial') DEFAULT NULL,
  `adjustment_type` enum('normal','abnormal') DEFAULT NULL,
  `contact_id` int(11) UNSIGNED DEFAULT NULL,
  `customer_group_id` int(11) DEFAULT NULL COMMENT 'used to add customer group while selling',
  `invoice_no` varchar(191) DEFAULT NULL,
  `ref_no` varchar(191) DEFAULT NULL,
  `source` varchar(191) DEFAULT NULL,
  `subscription_no` varchar(191) DEFAULT NULL,
  `subscription_repeat_on` varchar(191) DEFAULT NULL,
  `transaction_date` datetime NOT NULL,
  `hms_booking_arrival_date_time` datetime DEFAULT NULL,
  `total_before_tax` decimal(22,4) NOT NULL DEFAULT 0.0000 COMMENT 'Total before the purchase/invoice tax, this includeds the indivisual product tax',
  `tax_id` int(10) UNSIGNED DEFAULT NULL,
  `tax_amount` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `discount_type` enum('fixed','percentage') DEFAULT NULL,
  `discount_amount` decimal(22,4) DEFAULT 0.0000,
  `rp_redeemed` int(11) NOT NULL DEFAULT 0 COMMENT 'rp is the short form of reward points',
  `rp_redeemed_amount` decimal(22,4) NOT NULL DEFAULT 0.0000 COMMENT 'rp is the short form of reward points',
  `shipping_details` varchar(191) DEFAULT NULL,
  `shipping_address` text DEFAULT NULL,
  `delivery_date` datetime DEFAULT NULL,
  `shipping_status` varchar(191) DEFAULT NULL,
  `delivered_to` varchar(191) DEFAULT NULL,
  `delivery_person` bigint(20) DEFAULT NULL,
  `shipping_charges` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `shipping_custom_field_1` varchar(191) DEFAULT NULL,
  `shipping_custom_field_2` varchar(191) DEFAULT NULL,
  `shipping_custom_field_3` varchar(191) DEFAULT NULL,
  `shipping_custom_field_4` varchar(191) DEFAULT NULL,
  `shipping_custom_field_5` varchar(191) DEFAULT NULL,
  `additional_notes` text DEFAULT NULL,
  `staff_note` text DEFAULT NULL,
  `is_export` tinyint(1) NOT NULL DEFAULT 0,
  `export_custom_fields_info` longtext DEFAULT NULL,
  `round_off_amount` decimal(22,4) NOT NULL DEFAULT 0.0000 COMMENT 'Difference of rounded total and actual total',
  `additional_expense_key_1` varchar(191) DEFAULT NULL,
  `additional_expense_value_1` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `additional_expense_key_2` varchar(191) DEFAULT NULL,
  `additional_expense_value_2` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `additional_expense_key_3` varchar(191) DEFAULT NULL,
  `additional_expense_value_3` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `additional_expense_key_4` varchar(191) DEFAULT NULL,
  `additional_expense_value_4` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `final_total` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `expense_category_id` int(10) UNSIGNED DEFAULT NULL,
  `expense_sub_category_id` int(11) DEFAULT NULL,
  `expense_title` varchar(191) DEFAULT NULL,
  `payment_to` varchar(191) DEFAULT NULL,
  `expense_for` int(10) UNSIGNED DEFAULT NULL,
  `commission_agent` int(11) DEFAULT NULL,
  `document` varchar(191) DEFAULT NULL,
  `is_direct_sale` tinyint(1) NOT NULL DEFAULT 0,
  `is_suspend` tinyint(1) NOT NULL DEFAULT 0,
  `exchange_rate` decimal(20,3) NOT NULL DEFAULT 1.000,
  `total_amount_recovered` decimal(22,4) DEFAULT NULL COMMENT 'Used for stock adjustment.',
  `transfer_parent_id` int(11) DEFAULT NULL,
  `return_parent_id` int(11) DEFAULT NULL,
  `opening_stock_product_id` int(11) DEFAULT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `purchase_requisition_ids` text DEFAULT NULL,
  `prefer_payment_method` varchar(191) DEFAULT NULL,
  `prefer_payment_account` int(11) DEFAULT NULL,
  `sales_order_ids` text DEFAULT NULL,
  `purchase_order_ids` text DEFAULT NULL,
  `custom_field_1` varchar(191) DEFAULT NULL,
  `custom_field_2` varchar(191) DEFAULT NULL,
  `custom_field_3` varchar(191) DEFAULT NULL,
  `custom_field_4` varchar(191) DEFAULT NULL,
  `import_batch` int(11) DEFAULT NULL,
  `import_time` datetime DEFAULT NULL,
  `types_of_service_id` int(11) DEFAULT NULL,
  `packing_charge` decimal(22,4) DEFAULT NULL,
  `packing_charge_type` enum('fixed','percent') DEFAULT NULL,
  `service_custom_field_1` text DEFAULT NULL,
  `service_custom_field_2` text DEFAULT NULL,
  `service_custom_field_3` text DEFAULT NULL,
  `service_custom_field_4` text DEFAULT NULL,
  `service_custom_field_5` text DEFAULT NULL,
  `service_custom_field_6` text DEFAULT NULL,
  `is_created_from_api` tinyint(1) NOT NULL DEFAULT 0,
  `rp_earned` int(11) NOT NULL DEFAULT 0 COMMENT 'rp is the short form of reward points',
  `order_addresses` text DEFAULT NULL,
  `is_recurring` tinyint(1) NOT NULL DEFAULT 0,
  `recur_interval` double(22,4) DEFAULT NULL,
  `recur_interval_type` enum('days','months','years') DEFAULT NULL,
  `recur_repetitions` int(11) DEFAULT NULL,
  `recur_stopped_on` datetime DEFAULT NULL,
  `recur_parent_id` int(11) DEFAULT NULL,
  `invoice_token` varchar(191) DEFAULT NULL,
  `pay_term_number` int(11) DEFAULT NULL,
  `pay_term_type` enum('days','months') DEFAULT NULL,
  `selling_price_group_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `hms_booking_departure_date_time` datetime DEFAULT NULL,
  `hms_coupon_id` int(10) UNSIGNED DEFAULT NULL,
  `hms_place_of_origin` varchar(191) DEFAULT NULL,
  `hms_final_destination` varchar(191) DEFAULT NULL,
  `hms_means_of_transport` varchar(191) DEFAULT NULL,
  `hms_vehicle_registration_number` varchar(191) DEFAULT NULL,
  `hms_reason_for_trip` text DEFAULT NULL,
  `mfg_is_final` tinyint(1) NOT NULL DEFAULT 0,
  `mfg_parent_production_purchase_id` int(10) UNSIGNED DEFAULT NULL,
  `mfg_production_cost` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `mfg_wasted_units` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `mfg_production_note` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `business_id`, `location_id`, `is_kitchen_order`, `res_table_id`, `res_waiter_id`, `res_order_status`, `type`, `sub_type`, `status`, `sub_status`, `is_quotation`, `payment_status`, `adjustment_type`, `contact_id`, `customer_group_id`, `invoice_no`, `ref_no`, `source`, `subscription_no`, `subscription_repeat_on`, `transaction_date`, `hms_booking_arrival_date_time`, `total_before_tax`, `tax_id`, `tax_amount`, `discount_type`, `discount_amount`, `rp_redeemed`, `rp_redeemed_amount`, `shipping_details`, `shipping_address`, `delivery_date`, `shipping_status`, `delivered_to`, `delivery_person`, `shipping_charges`, `shipping_custom_field_1`, `shipping_custom_field_2`, `shipping_custom_field_3`, `shipping_custom_field_4`, `shipping_custom_field_5`, `additional_notes`, `staff_note`, `is_export`, `export_custom_fields_info`, `round_off_amount`, `additional_expense_key_1`, `additional_expense_value_1`, `additional_expense_key_2`, `additional_expense_value_2`, `additional_expense_key_3`, `additional_expense_value_3`, `additional_expense_key_4`, `additional_expense_value_4`, `final_total`, `expense_category_id`, `expense_sub_category_id`, `expense_title`, `payment_to`, `expense_for`, `commission_agent`, `document`, `is_direct_sale`, `is_suspend`, `exchange_rate`, `total_amount_recovered`, `transfer_parent_id`, `return_parent_id`, `opening_stock_product_id`, `created_by`, `purchase_requisition_ids`, `prefer_payment_method`, `prefer_payment_account`, `sales_order_ids`, `purchase_order_ids`, `custom_field_1`, `custom_field_2`, `custom_field_3`, `custom_field_4`, `import_batch`, `import_time`, `types_of_service_id`, `packing_charge`, `packing_charge_type`, `service_custom_field_1`, `service_custom_field_2`, `service_custom_field_3`, `service_custom_field_4`, `service_custom_field_5`, `service_custom_field_6`, `is_created_from_api`, `rp_earned`, `order_addresses`, `is_recurring`, `recur_interval`, `recur_interval_type`, `recur_repetitions`, `recur_stopped_on`, `recur_parent_id`, `invoice_token`, `pay_term_number`, `pay_term_type`, `selling_price_group_id`, `created_at`, `updated_at`, `hms_booking_departure_date_time`, `hms_coupon_id`, `hms_place_of_origin`, `hms_final_destination`, `hms_means_of_transport`, `hms_vehicle_registration_number`, `hms_reason_for_trip`, `mfg_is_final`, `mfg_parent_production_purchase_id`, `mfg_production_cost`, `mfg_wasted_units`, `mfg_production_note`) VALUES
(1, 1, 1, 0, NULL, NULL, NULL, 'purchase', NULL, 'received', NULL, 0, 'paid', NULL, 2, NULL, NULL, '35001BCVX', NULL, NULL, NULL, '2026-06-13 14:37:29', NULL, 50600.0000, 1, 5060.0000, NULL, 0.0000, 0, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, 55660.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1.000, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-01-06 00:57:11', '2018-01-06 00:57:11', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, 0.0000, NULL),
(2, 1, 1, 0, NULL, NULL, NULL, 'purchase', NULL, 'received', NULL, 0, 'paid', NULL, 5, NULL, NULL, '35001BJGN', NULL, NULL, NULL, '2026-06-13 14:37:29', NULL, 77000.0000, 1, 7700.0000, NULL, 0.0000, 0, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, 84700.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1.000, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-01-06 00:58:10', '2018-01-06 00:58:10', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, 0.0000, NULL),
(3, 1, 1, 0, NULL, NULL, NULL, 'purchase', NULL, 'received', NULL, 0, 'partial', NULL, 6, NULL, NULL, '35001BCVJ', NULL, NULL, NULL, '2026-05-29 14:37:29', NULL, 5500.0000, 1, 550.0000, NULL, 0.0000, 0, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, 6050.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1.000, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-01-06 01:02:22', '2018-01-06 01:02:22', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, 0.0000, NULL),
(4, 1, 1, 0, NULL, NULL, NULL, 'purchase', NULL, 'received', NULL, 0, 'paid', NULL, 6, NULL, NULL, '35001BCVK', NULL, NULL, NULL, '2026-05-29 14:37:29', NULL, 2000.0000, NULL, 0.0000, NULL, 0.0000, 0, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, 2000.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1.000, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-01-06 01:03:12', '2018-01-06 01:03:12', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, 0.0000, NULL),
(5, 1, 1, 0, NULL, NULL, NULL, 'purchase', NULL, 'received', NULL, 0, 'due', NULL, 6, NULL, NULL, '35001BCVD', NULL, NULL, NULL, '2026-06-21 14:37:29', NULL, 11000.0000, 1, 1100.0000, NULL, 0.0000, 0, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, 12100.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1.000, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-01-06 01:05:26', '2018-01-06 01:05:26', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, 0.0000, NULL),
(6, 1, 1, 0, NULL, NULL, NULL, 'sell', NULL, 'final', NULL, 0, 'paid', NULL, 4, NULL, 'AS0001', '', NULL, NULL, NULL, '2018-04-10 13:23:21', NULL, 770.0000, NULL, 0.0000, 'percentage', 0.0000, 0, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, 770.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1.000, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-01-06 01:06:11', '2018-01-06 01:06:11', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, 0.0000, NULL),
(7, 1, 1, 0, NULL, NULL, NULL, 'sell', NULL, 'final', NULL, 0, 'paid', NULL, 1, NULL, 'AS0002', '', NULL, NULL, NULL, '2026-06-26 14:37:29', NULL, 825.0000, NULL, 0.0000, 'percentage', 0.0000, 0, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, 825.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1.000, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-01-06 01:06:31', '2018-01-06 01:06:31', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, 0.0000, NULL),
(8, 1, 1, 0, NULL, NULL, NULL, 'sell', NULL, 'final', NULL, 0, 'paid', NULL, 4, NULL, 'AS0003', '', NULL, NULL, NULL, '2026-06-26 14:37:29', NULL, 7700.0000, NULL, 0.0000, 'percentage', 0.0000, 0, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, 7700.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1.000, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-01-06 01:07:22', '2018-01-06 01:07:22', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, 0.0000, NULL),
(9, 1, 1, 0, NULL, NULL, NULL, 'sell', NULL, 'final', NULL, 0, 'paid', NULL, 1, NULL, 'AS0004', '', NULL, NULL, NULL, '2026-06-28 14:37:29', NULL, 750.0000, NULL, 0.0000, 'percentage', 0.0000, 0, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, 750.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1.000, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-01-06 01:07:45', '2018-01-06 01:07:45', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, 0.0000, NULL),
(10, 1, 1, 0, NULL, NULL, NULL, 'sell', NULL, 'final', NULL, 0, 'paid', NULL, 1, NULL, 'AS0005', '', NULL, NULL, NULL, '2026-06-28 14:37:29', NULL, 412.5000, NULL, 0.0000, 'percentage', 0.0000, 0, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, 412.5000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1.000, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-01-06 01:08:03', '2018-01-06 01:08:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, 0.0000, NULL),
(11, 2, 2, 0, NULL, NULL, NULL, 'purchase', NULL, 'received', NULL, 0, 'paid', NULL, 8, NULL, NULL, '56247065', NULL, NULL, NULL, '2026-06-21 14:37:29', NULL, 300.0000, NULL, 0.0000, NULL, 0.0000, 0, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, 300.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1.000, NULL, NULL, NULL, NULL, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-04-10 04:18:16', '2018-04-10 04:18:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, 0.0000, NULL),
(12, 2, 2, 0, NULL, NULL, NULL, 'purchase', NULL, 'received', NULL, 0, 'paid', NULL, 9, NULL, NULL, '547887025', NULL, NULL, NULL, '2026-06-21 14:37:29', NULL, 1200.0000, NULL, 0.0000, NULL, 0.0000, 0, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, 1200.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1.000, NULL, NULL, NULL, NULL, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-04-10 04:19:40', '2018-04-10 04:19:49', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, 0.0000, NULL),
(13, 2, 2, 0, NULL, NULL, NULL, 'purchase', NULL, 'received', NULL, 0, 'paid', NULL, 9, NULL, NULL, '45470025', NULL, NULL, NULL, '2026-05-29 14:37:29', NULL, 1350.0000, NULL, 0.0000, NULL, 0.0000, 0, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, 1350.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1.000, NULL, NULL, NULL, NULL, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-04-10 04:20:41', '2018-04-10 04:20:55', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, 0.0000, NULL),
(14, 2, 2, 0, NULL, NULL, NULL, 'purchase', NULL, 'received', NULL, 0, 'partial', NULL, 9, NULL, NULL, '65589898', NULL, NULL, NULL, '2026-05-29 14:37:29', NULL, 2160.0000, NULL, 0.0000, NULL, 0.0000, 0, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, 2160.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1.000, NULL, NULL, NULL, NULL, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-04-10 04:21:38', '2018-04-10 04:22:10', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, 0.0000, NULL),
(15, 2, 2, 0, NULL, NULL, NULL, 'sell', NULL, 'final', NULL, 0, 'paid', NULL, 7, NULL, '0001', '', NULL, NULL, NULL, '2026-06-26 14:37:29', NULL, 75.0000, NULL, 0.0000, 'percentage', 0.0000, 0, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, 75.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1.000, NULL, NULL, NULL, NULL, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-04-10 04:23:35', '2018-04-10 04:23:35', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, 0.0000, NULL),
(16, 2, 2, 0, NULL, NULL, NULL, 'sell', NULL, 'final', NULL, 0, 'paid', NULL, 7, NULL, '0002', '', NULL, NULL, NULL, '2026-06-26 14:37:29', NULL, 105.0000, NULL, 0.0000, 'percentage', 0.0000, 0, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, 105.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1.000, NULL, NULL, NULL, NULL, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-04-10 04:23:58', '2018-04-10 04:23:58', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, 0.0000, NULL),
(17, 2, 2, 0, NULL, NULL, NULL, 'sell', NULL, 'final', NULL, 0, 'paid', NULL, 7, NULL, '0003', '', NULL, NULL, NULL, '2026-06-28 14:37:29', NULL, 405.0000, NULL, 0.0000, 'percentage', 0.0000, 0, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, 405.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1.000, NULL, NULL, NULL, NULL, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-04-10 04:24:21', '2018-04-10 04:26:26', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, 0.0000, NULL),
(18, 3, 3, 0, NULL, NULL, NULL, 'purchase', NULL, 'received', NULL, 0, 'paid', NULL, 11, NULL, NULL, '548471117', NULL, NULL, NULL, '2026-05-29 14:37:29', NULL, 9630.0000, NULL, 0.0000, NULL, 0.0000, 0, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, 9630.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1.000, NULL, NULL, NULL, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-04-10 05:39:54', '2018-04-10 05:40:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, 0.0000, NULL),
(19, 3, 3, 0, NULL, NULL, NULL, 'purchase', NULL, 'received', NULL, 0, 'paid', NULL, 12, NULL, NULL, '548222220', NULL, NULL, NULL, '2026-06-21 14:37:29', NULL, 20000.0000, NULL, 0.0000, NULL, 0.0000, 0, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, 20000.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1.000, NULL, NULL, NULL, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-04-10 05:40:51', '2018-04-10 05:40:58', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, 0.0000, NULL),
(20, 3, 3, 0, NULL, NULL, NULL, 'purchase', NULL, 'received', NULL, 0, 'paid', NULL, 12, NULL, NULL, '878445524', NULL, NULL, NULL, '2026-06-13 14:37:29', NULL, 13500.0000, NULL, 0.0000, NULL, 0.0000, 0, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, 13500.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1.000, NULL, NULL, NULL, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-04-10 05:41:45', '2018-04-10 05:43:19', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, 0.0000, NULL),
(21, 3, 3, 0, NULL, NULL, NULL, 'purchase', NULL, 'received', NULL, 0, 'paid', NULL, 12, NULL, NULL, '582500058', NULL, NULL, NULL, '2026-06-13 14:37:29', NULL, 19500.0000, NULL, 0.0000, NULL, 0.0000, 0, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, 19500.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1.000, NULL, NULL, NULL, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-04-10 05:42:34', '2018-04-10 05:43:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, 0.0000, NULL),
(22, 3, 3, 0, NULL, NULL, NULL, 'purchase', NULL, 'received', NULL, 0, 'partial', NULL, 11, NULL, NULL, '848485552', NULL, NULL, NULL, '2026-06-21 14:37:29', NULL, 12000.0000, NULL, 0.0000, NULL, 0.0000, 0, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, 12000.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1.000, NULL, NULL, NULL, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-04-10 05:43:12', '2018-04-10 05:43:35', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, 0.0000, NULL),
(23, 3, 3, 0, NULL, NULL, NULL, 'sell', NULL, 'final', NULL, 0, 'paid', NULL, 10, NULL, 'AE0001', '', NULL, NULL, NULL, '2026-06-28 14:37:29', NULL, 2500.0000, NULL, 0.0000, 'percentage', 0.0000, 0, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, 2500.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1.000, NULL, NULL, NULL, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-04-10 05:44:47', '2018-04-10 05:47:10', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, 0.0000, NULL),
(24, 3, 3, 0, NULL, NULL, NULL, 'sell', NULL, 'final', NULL, 0, 'paid', NULL, 10, NULL, 'AE0002', '', NULL, NULL, NULL, '2026-06-26 14:37:29', NULL, 4875.0000, NULL, 0.0000, 'percentage', 0.0000, 0, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, 4875.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1.000, NULL, NULL, NULL, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-04-10 05:45:06', '2018-04-10 05:46:43', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, 0.0000, NULL),
(25, 3, 3, 0, NULL, NULL, NULL, 'sell', NULL, 'final', NULL, 0, 'paid', NULL, 10, NULL, 'AE0003', '', NULL, NULL, NULL, '2026-06-26 14:37:29', NULL, 625.0000, NULL, 0.0000, 'percentage', 0.0000, 0, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, 625.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1.000, NULL, NULL, NULL, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-04-10 05:46:02', '2018-04-10 05:46:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, 0.0000, NULL),
(26, 3, 3, 0, NULL, NULL, NULL, 'sell', NULL, 'final', NULL, 0, 'paid', NULL, 10, NULL, 'AE0004', '', NULL, NULL, NULL, '2026-06-28 14:37:29', NULL, 15625.0000, NULL, 0.0000, 'percentage', 0.0000, 0, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, 15625.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1.000, NULL, NULL, NULL, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-04-10 05:48:48', '2018-04-10 05:48:48', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, 0.0000, NULL),
(27, 3, 3, 0, NULL, NULL, NULL, 'purchase', NULL, 'received', NULL, 0, 'due', NULL, 12, NULL, NULL, '1545477882', NULL, NULL, NULL, '2026-06-13 14:37:29', NULL, 26000.0000, NULL, 0.0000, NULL, 0.0000, 0, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, 26000.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1.000, NULL, NULL, NULL, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-04-10 05:53:36', '2018-04-10 05:53:36', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, 0.0000, NULL),
(28, 3, 3, 0, NULL, NULL, NULL, 'sell', NULL, 'final', NULL, 0, 'paid', NULL, 10, NULL, 'AE0005', '', NULL, NULL, NULL, '2026-06-26 14:37:29', NULL, 13000.0000, NULL, 0.0000, 'percentage', 0.0000, 0, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, 13000.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1.000, NULL, NULL, NULL, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-04-10 05:54:16', '2018-04-10 05:54:16', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, 0.0000, NULL),
(29, 4, 4, 0, NULL, NULL, NULL, 'sell', NULL, 'final', NULL, 0, 'paid', NULL, 13, NULL, '0001', '', NULL, NULL, NULL, '2026-06-28 14:37:29', NULL, 656.2500, NULL, 0.0000, 'percentage', 0.0000, 0, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, 656.2500, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1.000, NULL, NULL, NULL, NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-04-10 07:04:40', '2018-04-10 07:04:40', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, 0.0000, NULL),
(30, 4, 4, 0, NULL, NULL, NULL, 'sell', NULL, 'final', NULL, 0, 'paid', NULL, 13, NULL, '0002', '', NULL, NULL, NULL, '2026-06-26 14:37:29', NULL, 250.0000, NULL, 0.0000, 'percentage', 0.0000, 0, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, 250.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1.000, NULL, NULL, NULL, NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-04-10 07:04:54', '2018-04-10 07:04:54', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, 0.0000, NULL),
(31, 4, 4, 0, NULL, NULL, NULL, 'sell', NULL, 'final', NULL, 0, 'paid', NULL, 13, NULL, '0003', '', NULL, NULL, NULL, '2026-06-28 14:37:29', NULL, 75.0000, NULL, 0.0000, 'percentage', 0.0000, 0, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, 75.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1.000, NULL, NULL, NULL, NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-04-10 07:05:19', '2018-04-10 07:05:19', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, 0.0000, NULL),
(32, 4, 4, 0, NULL, NULL, NULL, 'sell', NULL, 'final', NULL, 0, 'paid', NULL, 13, NULL, '0004', '', NULL, NULL, NULL, '2026-06-28 14:37:29', NULL, 37.5000, NULL, 0.0000, 'percentage', 0.0000, 0, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, 37.5000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1.000, NULL, NULL, NULL, NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-04-10 07:07:50', '2018-04-10 07:07:50', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, 0.0000, NULL),
(33, 4, 4, 0, NULL, NULL, NULL, 'sell', NULL, 'final', NULL, 0, 'paid', NULL, 13, NULL, '0005', '', NULL, NULL, NULL, '2026-06-26 14:37:29', NULL, 375.0000, NULL, 0.0000, 'percentage', 0.0000, 0, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, 375.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1.000, NULL, NULL, NULL, NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-04-10 07:08:01', '2018-04-10 07:08:01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, 0.0000, NULL),
(34, 4, 4, 0, NULL, NULL, NULL, 'sell', NULL, 'final', NULL, 0, 'paid', NULL, 13, NULL, '0006', '', NULL, NULL, NULL, '2026-06-26 14:37:29', NULL, 250.0000, NULL, 0.0000, 'percentage', 0.0000, 0, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, 250.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1.000, NULL, NULL, NULL, NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-04-10 07:08:15', '2018-04-10 07:08:15', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, 0.0000, NULL),
(35, 4, 4, 0, NULL, NULL, NULL, 'sell', NULL, 'final', NULL, 0, 'paid', NULL, 13, NULL, '0007', '', NULL, NULL, NULL, '2026-06-28 14:37:29', NULL, 100.0000, NULL, 0.0000, 'percentage', 0.0000, 0, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, 100.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1.000, NULL, NULL, NULL, NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-04-10 07:08:27', '2018-04-10 07:08:27', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, 0.0000, NULL),
(36, 4, 4, 0, NULL, NULL, NULL, 'sell', NULL, 'final', NULL, 0, 'paid', NULL, 13, NULL, '0008', '', NULL, NULL, NULL, '2026-06-26 14:37:29', NULL, 125.0000, NULL, 0.0000, 'percentage', 0.0000, 0, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, 125.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1.000, NULL, NULL, NULL, NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-04-10 07:08:49', '2018-04-10 07:08:49', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, 0.0000, NULL),
(37, 1, 1, 0, NULL, NULL, NULL, 'purchase', NULL, 'received', NULL, 0, 'due', NULL, 6, NULL, NULL, 'PO2018/0001', NULL, NULL, NULL, '2026-06-26 14:37:29', NULL, 5180.0000, NULL, 0.0000, NULL, 0.0000, 0, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, 5180.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1.000, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-06-11 16:17:07', '2018-06-11 16:17:07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, 0.0000, NULL),
(38, 1, 1, 0, NULL, NULL, NULL, 'purchase', NULL, 'received', NULL, 0, 'due', NULL, 6, NULL, NULL, 'PO2018/0002', NULL, NULL, NULL, '2026-06-28 14:37:29', NULL, 235656.0000, NULL, 0.0000, NULL, 0.0000, 0, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, 235656.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1.000, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-06-12 17:18:04', '2018-06-12 17:18:04', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, 0.0000, NULL),
(39, 5, 5, 0, NULL, NULL, NULL, 'purchase', NULL, 'received', NULL, 0, 'due', NULL, 15, NULL, NULL, 'PO2018/0001', NULL, NULL, NULL, '2026-06-26 14:37:29', NULL, 1900.0000, NULL, 0.0000, NULL, 0.0000, 0, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, 1900.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1.000, NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-07-13 01:34:45', '2018-07-13 01:34:45', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, 0.0000, NULL),
(40, 5, 5, 0, NULL, NULL, NULL, 'purchase', NULL, 'received', NULL, 0, 'due', NULL, 15, NULL, NULL, 'PO2018/0002', NULL, NULL, NULL, '2026-06-26 14:37:29', NULL, 800.0000, NULL, 0.0000, NULL, 0.0000, 0, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, 800.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1.000, NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-07-13 01:38:24', '2018-07-13 01:38:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, 0.0000, NULL),
(41, 5, 5, 0, 1, 8, 'served', 'sell', NULL, 'final', NULL, 0, 'paid', NULL, 14, NULL, '0001', '', NULL, NULL, NULL, '2026-06-28 14:37:29', NULL, 42.5000, NULL, 0.0000, 'percentage', 0.0000, 0, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, 42.5000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1.000, NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-07-13 01:44:40', '2018-07-13 01:48:07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, 0.0000, NULL),
(42, 5, 5, 0, 2, 8, NULL, 'sell', NULL, 'final', NULL, 0, 'paid', NULL, 14, NULL, '0002', '', NULL, NULL, NULL, '2026-06-28 14:37:29', NULL, 77.5000, NULL, 0.0000, 'percentage', 0.0000, 0, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, 77.5000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1.000, NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-07-13 01:45:28', '2018-07-13 01:45:29', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, 0.0000, NULL),
(43, 5, 5, 0, 3, 8, NULL, 'sell', NULL, 'final', NULL, 0, 'paid', NULL, 14, NULL, '0003', '', NULL, NULL, NULL, '2026-06-28 14:37:29', NULL, 33.7500, NULL, 0.0000, 'percentage', 0.0000, 0, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, 33.7500, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1.000, NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-07-13 01:46:04', '2018-07-13 01:46:04', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, 0.0000, NULL),
(44, 5, 5, 0, NULL, NULL, 'cooked', 'sell', NULL, 'final', NULL, 0, 'paid', NULL, 14, NULL, '0004', '', NULL, NULL, NULL, '2026-06-28 14:37:29', NULL, 71.2500, NULL, 0.0000, 'percentage', 0.0000, 0, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, 71.2500, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1.000, NULL, NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2018-07-13 01:46:36', '2018-07-13 01:46:55', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, 0.0000, NULL),
(45, 6, 6, 0, NULL, NULL, NULL, 'opening_stock', NULL, 'received', NULL, 0, 'paid', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-01-01 19:01:33', NULL, 1000.0000, NULL, 0.0000, NULL, NULL, 0, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, 1000.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1.000, NULL, NULL, NULL, 88, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-08-18 13:01:33', '2019-08-18 13:01:33', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, 0.0000, NULL),
(46, 6, 6, 0, NULL, NULL, NULL, 'opening_stock', NULL, 'received', NULL, 0, 'paid', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-01-01 19:01:49', NULL, 400.0000, NULL, 0.0000, NULL, NULL, 0, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, 400.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1.000, NULL, NULL, NULL, 87, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-08-18 13:01:49', '2019-08-18 13:01:49', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, 0.0000, NULL),
(47, 6, 6, 0, NULL, NULL, NULL, 'opening_stock', NULL, 'received', NULL, 0, 'paid', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-01-01 19:02:07', NULL, 500.0000, NULL, 0.0000, NULL, NULL, 0, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, 500.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1.000, NULL, NULL, NULL, 86, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-08-18 13:02:07', '2019-08-18 13:02:07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, 0.0000, NULL),
(48, 6, 6, 0, NULL, NULL, NULL, 'opening_stock', NULL, 'received', NULL, 0, 'paid', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-01-01 19:02:20', NULL, 400.0000, NULL, 0.0000, NULL, NULL, 0, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, 400.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1.000, NULL, NULL, NULL, 84, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-08-18 13:02:20', '2019-08-18 13:02:20', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, 0.0000, NULL),
(49, 6, 6, 0, NULL, NULL, NULL, 'opening_stock', NULL, 'received', NULL, 0, 'paid', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-01-01 19:02:33', NULL, 20000.0000, NULL, 0.0000, NULL, NULL, 0, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, 20000.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1.000, NULL, NULL, NULL, 82, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-08-18 13:02:33', '2019-08-18 13:02:33', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, 0.0000, NULL),
(50, 6, 6, 0, NULL, NULL, NULL, 'opening_stock', NULL, 'received', NULL, 0, 'paid', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-01-01 19:02:52', NULL, 20.0000, NULL, 0.0000, NULL, NULL, 0, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, 20.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1.000, NULL, NULL, NULL, 85, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-08-18 13:02:52', '2019-08-18 13:02:52', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, 0.0000, NULL),
(51, 6, 6, 0, NULL, NULL, NULL, 'opening_stock', NULL, 'received', NULL, 0, 'paid', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-01-01 19:03:10', NULL, 200.0000, NULL, 0.0000, NULL, NULL, 0, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, 200.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1.000, NULL, NULL, NULL, 83, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-08-18 13:03:10', '2019-08-18 13:03:10', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, 0.0000, NULL),
(52, 6, 6, 0, NULL, NULL, NULL, 'production_purchase', NULL, 'received', NULL, 0, 'due', NULL, NULL, NULL, NULL, '2019/0001', NULL, NULL, NULL, '2019-08-18 19:08:00', NULL, 0.0000, NULL, 0.0000, NULL, NULL, 0, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, 14685.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1.000, NULL, NULL, NULL, NULL, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-08-18 13:09:19', '2019-08-18 13:09:19', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 10.0000, 0.0000, NULL),
(53, 6, 6, 0, NULL, NULL, NULL, 'production_sell', NULL, 'final', NULL, 0, 'due', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-08-18 19:08:00', NULL, 0.0000, NULL, 0.0000, NULL, NULL, 0, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, 14685.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1.000, NULL, NULL, NULL, NULL, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-08-18 13:09:19', '2019-08-18 13:09:19', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 52, 0.0000, 0.0000, NULL),
(54, 1, 1, 0, NULL, NULL, NULL, 'hms_booking', NULL, 'confirmed', NULL, 0, 'due', NULL, 1, NULL, NULL, 'HB0001', NULL, NULL, NULL, '2026-06-28 14:37:29', '2026-06-29 14:37:29', 115.0000, NULL, 0.0000, NULL, 0.0000, 0, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, 0.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, NULL, 0.0000, 115.0000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1.000, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-28 08:37:29', '2026-06-28 08:37:29', '2026-07-01 14:37:29', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0.0000, 0.0000, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `transaction_payments`
--

CREATE TABLE `transaction_payments` (
  `id` int(10) UNSIGNED NOT NULL,
  `transaction_id` int(11) UNSIGNED DEFAULT NULL,
  `business_id` int(11) DEFAULT NULL,
  `is_return` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Used during sales to return the change',
  `amount` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `method` varchar(191) DEFAULT NULL,
  `payment_type` varchar(191) DEFAULT NULL,
  `transaction_no` varchar(191) DEFAULT NULL,
  `card_transaction_number` varchar(191) DEFAULT NULL,
  `card_number` varchar(191) DEFAULT NULL,
  `card_type` varchar(191) DEFAULT NULL,
  `card_holder_name` varchar(191) DEFAULT NULL,
  `card_month` varchar(191) DEFAULT NULL,
  `card_year` varchar(191) DEFAULT NULL,
  `card_security` varchar(5) DEFAULT NULL,
  `cheque_number` varchar(191) DEFAULT NULL,
  `bank_account_number` varchar(191) DEFAULT NULL,
  `paid_on` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `paid_through_link` tinyint(1) NOT NULL DEFAULT 0,
  `gateway` varchar(191) DEFAULT NULL,
  `is_advance` tinyint(1) NOT NULL DEFAULT 0,
  `payment_for` int(11) DEFAULT NULL COMMENT 'stores the contact id',
  `parent_id` int(11) DEFAULT NULL,
  `note` varchar(191) DEFAULT NULL,
  `document` varchar(191) DEFAULT NULL,
  `payment_ref_no` varchar(191) DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transaction_payments`
--

INSERT INTO `transaction_payments` (`id`, `transaction_id`, `business_id`, `is_return`, `amount`, `method`, `payment_type`, `transaction_no`, `card_transaction_number`, `card_number`, `card_type`, `card_holder_name`, `card_month`, `card_year`, `card_security`, `cheque_number`, `bank_account_number`, `paid_on`, `created_by`, `paid_through_link`, `gateway`, `is_advance`, `payment_for`, `parent_id`, `note`, `document`, `payment_ref_no`, `account_id`, `created_at`, `updated_at`) VALUES
(1, 6, NULL, 0, 770.0000, 'cash', NULL, NULL, NULL, NULL, 'visa', NULL, NULL, NULL, NULL, NULL, NULL, '2018-01-09 17:30:35', 1, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2018-01-05 19:36:11', '2018-01-05 19:36:11'),
(2, 7, NULL, 0, 825.0000, 'cash', NULL, NULL, NULL, NULL, 'visa', NULL, NULL, NULL, NULL, NULL, NULL, '2018-01-09 17:30:35', 1, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2018-01-05 19:36:31', '2018-01-05 19:36:31'),
(3, 8, NULL, 0, 7700.0000, 'cash', NULL, NULL, NULL, NULL, 'visa', NULL, NULL, NULL, NULL, NULL, NULL, '2018-01-09 17:30:35', 1, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2018-01-05 19:37:23', '2018-01-05 19:37:23'),
(4, 9, NULL, 0, 750.0000, 'cash', NULL, NULL, NULL, NULL, 'visa', NULL, NULL, NULL, NULL, NULL, NULL, '2018-01-09 17:30:35', 1, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2018-01-05 19:37:45', '2018-01-05 19:37:45'),
(5, 10, NULL, 0, 412.5000, 'cash', NULL, NULL, NULL, NULL, 'visa', NULL, NULL, NULL, NULL, NULL, NULL, '2018-01-09 17:30:35', 1, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2018-01-05 19:38:03', '2018-01-05 19:38:03'),
(6, 4, NULL, 0, 2000.0000, 'cash', NULL, NULL, NULL, NULL, 'visa', NULL, NULL, NULL, NULL, NULL, NULL, '2018-01-11 17:32:56', 1, 0, NULL, 0, NULL, NULL, 'Cash Payment', NULL, NULL, NULL, '2018-01-11 00:32:56', '2018-01-11 00:32:56'),
(7, 3, NULL, 0, 3000.0000, 'bank_transfer', NULL, NULL, NULL, NULL, 'visa', NULL, NULL, NULL, NULL, NULL, '502110000631', '2018-01-11 17:34:10', 1, 0, NULL, 0, NULL, NULL, '3000 Paid Via Bank Transfer', NULL, NULL, NULL, '2018-01-11 00:34:10', '2018-01-11 00:34:10'),
(8, 2, NULL, 0, 84700.0000, 'cash', NULL, NULL, NULL, NULL, 'visa', NULL, NULL, NULL, NULL, NULL, NULL, '2018-01-11 17:34:36', 1, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2018-01-11 00:34:36', '2018-01-11 00:34:36'),
(9, 1, NULL, 0, 50000.0000, 'cash', NULL, NULL, NULL, NULL, 'visa', NULL, NULL, NULL, NULL, NULL, NULL, '2018-01-11 17:35:04', 1, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2018-01-11 00:35:04', '2018-01-11 00:35:04'),
(10, 1, NULL, 0, 5660.0000, 'cash', NULL, NULL, NULL, NULL, 'visa', NULL, NULL, NULL, NULL, NULL, NULL, '2018-01-11 17:35:17', 1, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2018-01-11 00:35:17', '2018-01-11 00:35:17'),
(11, 11, NULL, 0, 300.0000, 'cash', NULL, NULL, NULL, NULL, 'visa', NULL, NULL, NULL, NULL, NULL, NULL, '2018-04-10 15:48:47', 4, 0, NULL, 0, 8, NULL, NULL, NULL, NULL, NULL, '2018-04-10 04:18:47', '2018-04-10 04:18:47'),
(12, 12, NULL, 0, 1200.0000, 'cash', NULL, NULL, NULL, NULL, 'visa', NULL, NULL, NULL, NULL, NULL, NULL, '2018-04-10 15:49:49', 4, 0, NULL, 0, 9, NULL, NULL, NULL, NULL, NULL, '2018-04-10 04:19:49', '2018-04-10 04:19:49'),
(13, 13, NULL, 0, 1350.0000, 'cash', NULL, NULL, NULL, NULL, 'visa', NULL, NULL, NULL, NULL, NULL, NULL, '2018-04-10 15:50:55', 4, 0, NULL, 0, 9, NULL, NULL, NULL, NULL, NULL, '2018-04-10 04:20:55', '2018-04-10 04:20:55'),
(14, 14, NULL, 0, 1500.0000, 'cash', NULL, NULL, NULL, NULL, 'visa', NULL, NULL, NULL, NULL, NULL, NULL, '2018-04-10 15:52:10', 4, 0, NULL, 0, 9, NULL, NULL, NULL, NULL, NULL, '2018-04-10 04:22:10', '2018-04-10 04:22:10'),
(15, 15, NULL, 0, 75.0000, 'cash', NULL, NULL, NULL, NULL, 'visa', NULL, NULL, NULL, NULL, NULL, NULL, '2018-04-10 15:53:35', 4, 0, NULL, 0, 7, NULL, NULL, NULL, NULL, NULL, '2018-04-10 04:23:35', '2018-04-10 04:23:35'),
(16, 16, NULL, 0, 105.0000, 'cash', NULL, NULL, NULL, NULL, 'visa', NULL, NULL, NULL, NULL, NULL, NULL, '2018-04-10 15:53:58', 4, 0, NULL, 0, 7, NULL, NULL, NULL, NULL, NULL, '2018-04-10 04:23:58', '2018-04-10 04:23:58'),
(17, 17, NULL, 0, 405.0000, 'cash', NULL, NULL, NULL, NULL, 'visa', NULL, NULL, NULL, NULL, NULL, NULL, '2018-04-10 15:54:21', 4, 0, NULL, 0, 7, NULL, NULL, NULL, NULL, NULL, '2018-04-10 04:24:21', '2018-04-10 04:26:26'),
(18, 18, NULL, 0, 9630.0000, 'cash', NULL, NULL, NULL, NULL, 'visa', NULL, NULL, NULL, NULL, NULL, NULL, '2018-04-10 17:10:02', 5, 0, NULL, 0, 11, NULL, NULL, NULL, NULL, NULL, '2018-04-10 05:40:02', '2018-04-10 05:40:02'),
(19, 19, NULL, 0, 20000.0000, 'cash', NULL, NULL, NULL, NULL, 'visa', NULL, NULL, NULL, NULL, NULL, NULL, '2018-04-10 17:10:58', 5, 0, NULL, 0, 12, NULL, NULL, NULL, NULL, NULL, '2018-04-10 05:40:58', '2018-04-10 05:40:58'),
(20, 20, NULL, 0, 13500.0000, 'cash', NULL, NULL, NULL, NULL, 'visa', NULL, NULL, NULL, NULL, NULL, NULL, '2018-04-10 17:13:19', 5, 0, NULL, 0, 12, NULL, NULL, NULL, NULL, NULL, '2018-04-10 05:43:19', '2018-04-10 05:43:19'),
(21, 21, NULL, 0, 19500.0000, 'cash', NULL, NULL, NULL, NULL, 'visa', NULL, NULL, NULL, NULL, NULL, NULL, '2018-04-10 17:13:24', 5, 0, NULL, 0, 12, NULL, NULL, NULL, NULL, NULL, '2018-04-10 05:43:24', '2018-04-10 05:43:24'),
(22, 22, NULL, 0, 5000.0000, 'cash', NULL, NULL, NULL, NULL, 'visa', NULL, NULL, NULL, NULL, NULL, NULL, '2018-04-10 17:13:35', 5, 0, NULL, 0, 11, NULL, NULL, NULL, NULL, NULL, '2018-04-10 05:43:35', '2018-04-10 05:43:35'),
(23, 23, NULL, 0, 2500.0000, 'cash', NULL, NULL, NULL, NULL, 'visa', NULL, NULL, NULL, NULL, NULL, NULL, '2018-04-10 17:14:47', 5, 0, NULL, 0, 10, NULL, NULL, NULL, NULL, NULL, '2018-04-10 05:44:47', '2018-04-10 05:47:10'),
(24, 24, NULL, 0, 4875.0000, 'cash', NULL, NULL, NULL, NULL, 'visa', NULL, NULL, NULL, NULL, NULL, NULL, '2018-04-10 17:15:06', 5, 0, NULL, 0, 10, NULL, NULL, NULL, NULL, NULL, '2018-04-10 05:45:06', '2018-04-10 05:46:43'),
(25, 25, NULL, 0, 625.0000, 'cash', NULL, NULL, NULL, NULL, 'visa', NULL, NULL, NULL, NULL, NULL, NULL, '2018-04-10 17:16:02', 5, 0, NULL, 0, 10, NULL, NULL, NULL, NULL, NULL, '2018-04-10 05:46:02', '2018-04-10 05:46:02'),
(26, 26, NULL, 0, 15625.0000, 'cash', NULL, NULL, NULL, NULL, 'visa', NULL, NULL, NULL, NULL, NULL, NULL, '2018-04-10 17:18:48', 5, 0, NULL, 0, 10, NULL, NULL, NULL, NULL, NULL, '2018-04-10 05:48:48', '2018-04-10 05:48:48'),
(27, 28, NULL, 0, 13000.0000, 'cash', NULL, NULL, NULL, NULL, 'visa', NULL, NULL, NULL, NULL, NULL, NULL, '2018-04-10 17:24:16', 5, 0, NULL, 0, 10, NULL, NULL, NULL, NULL, NULL, '2018-04-10 05:54:16', '2018-04-10 05:54:16'),
(28, 29, NULL, 0, 656.2500, 'cash', NULL, NULL, NULL, NULL, 'visa', NULL, NULL, NULL, NULL, NULL, NULL, '2018-04-10 18:34:40', 6, 0, NULL, 0, 13, NULL, NULL, NULL, NULL, NULL, '2018-04-10 07:04:40', '2018-04-10 07:04:40'),
(29, 30, NULL, 0, 250.0000, 'cash', NULL, NULL, NULL, NULL, 'visa', NULL, NULL, NULL, NULL, NULL, NULL, '2018-04-10 18:34:54', 6, 0, NULL, 0, 13, NULL, NULL, NULL, NULL, NULL, '2018-04-10 07:04:54', '2018-04-10 07:04:54'),
(30, 31, NULL, 0, 75.0000, 'cash', NULL, NULL, NULL, NULL, 'visa', NULL, NULL, NULL, NULL, NULL, NULL, '2018-04-10 18:35:19', 6, 0, NULL, 0, 13, NULL, NULL, NULL, NULL, NULL, '2018-04-10 07:05:19', '2018-04-10 07:05:19'),
(31, 32, NULL, 0, 37.5000, 'cash', NULL, NULL, NULL, NULL, 'visa', NULL, NULL, NULL, NULL, NULL, NULL, '2018-04-10 18:37:50', 6, 0, NULL, 0, 13, NULL, NULL, NULL, NULL, NULL, '2018-04-10 07:07:50', '2018-04-10 07:07:50'),
(32, 33, NULL, 0, 375.0000, 'cash', NULL, NULL, NULL, NULL, 'visa', NULL, NULL, NULL, NULL, NULL, NULL, '2018-04-10 18:38:01', 6, 0, NULL, 0, 13, NULL, NULL, NULL, NULL, NULL, '2018-04-10 07:08:01', '2018-04-10 07:08:01'),
(33, 34, NULL, 0, 250.0000, 'cash', NULL, NULL, NULL, NULL, 'visa', NULL, NULL, NULL, NULL, NULL, NULL, '2018-04-10 18:38:15', 6, 0, NULL, 0, 13, NULL, NULL, NULL, NULL, NULL, '2018-04-10 07:08:15', '2018-04-10 07:08:15'),
(34, 35, NULL, 0, 100.0000, 'cash', NULL, NULL, NULL, NULL, 'visa', NULL, NULL, NULL, NULL, NULL, NULL, '2018-04-10 18:38:27', 6, 0, NULL, 0, 13, NULL, NULL, NULL, NULL, NULL, '2018-04-10 07:08:27', '2018-04-10 07:08:27'),
(35, 36, NULL, 0, 125.0000, 'cash', NULL, NULL, NULL, NULL, 'visa', NULL, NULL, NULL, NULL, NULL, NULL, '2018-04-10 18:38:49', 6, 0, NULL, 0, 13, NULL, NULL, NULL, NULL, NULL, '2018-04-10 07:08:49', '2018-04-10 07:08:49');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_sell_lines`
--

CREATE TABLE `transaction_sell_lines` (
  `id` int(10) UNSIGNED NOT NULL,
  `transaction_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `variation_id` int(10) UNSIGNED NOT NULL,
  `quantity` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `secondary_unit_quantity` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `quantity_returned` decimal(20,4) NOT NULL DEFAULT 0.0000,
  `unit_price_before_discount` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `unit_price` decimal(22,4) DEFAULT NULL COMMENT 'Sell price excluding tax',
  `line_discount_type` enum('fixed','percentage') DEFAULT NULL,
  `line_discount_amount` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `unit_price_inc_tax` decimal(22,4) DEFAULT NULL COMMENT 'Sell price including tax',
  `item_tax` decimal(22,4) NOT NULL COMMENT 'Tax for one quantity',
  `tax_id` int(10) UNSIGNED DEFAULT NULL,
  `discount_id` int(11) DEFAULT NULL,
  `lot_no_line_id` int(11) DEFAULT NULL,
  `woocommerce_line_items_id` bigint(20) UNSIGNED DEFAULT NULL,
  `mfg_waste_percent` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `sell_line_note` text DEFAULT NULL,
  `so_line_id` int(11) DEFAULT NULL,
  `so_quantity_invoiced` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `res_service_staff_id` int(11) DEFAULT NULL,
  `res_line_order_status` varchar(191) DEFAULT NULL,
  `parent_sell_line_id` int(11) DEFAULT NULL,
  `children_type` varchar(191) NOT NULL DEFAULT '' COMMENT 'Type of children for the parent, like modifier or combo',
  `sub_unit_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transaction_sell_lines`
--

INSERT INTO `transaction_sell_lines` (`id`, `transaction_id`, `product_id`, `variation_id`, `quantity`, `secondary_unit_quantity`, `quantity_returned`, `unit_price_before_discount`, `unit_price`, `line_discount_type`, `line_discount_amount`, `unit_price_inc_tax`, `item_tax`, `tax_id`, `discount_id`, `lot_no_line_id`, `woocommerce_line_items_id`, `mfg_waste_percent`, `sell_line_note`, `so_line_id`, `so_quantity_invoiced`, `res_service_staff_id`, `res_line_order_status`, `parent_sell_line_id`, `children_type`, `sub_unit_id`, `created_at`, `updated_at`) VALUES
(1, 6, 2, 3, 10.0000, 0.0000, 0.0000, 70.0000, 70.0000, NULL, 0.0000, 77.0000, 7.0000, 1, NULL, NULL, NULL, 0.0000, NULL, NULL, 0.0000, NULL, NULL, NULL, '', NULL, '2018-01-06 01:06:11', '2018-01-06 01:06:11'),
(2, 7, 27, 70, 30.0000, 0.0000, 0.0000, 25.0000, 25.0000, NULL, 0.0000, 27.5000, 2.5000, 1, NULL, NULL, NULL, 0.0000, NULL, NULL, 0.0000, NULL, NULL, NULL, '', NULL, '2018-01-06 01:06:31', '2018-01-06 01:06:31'),
(3, 8, 2, 3, 50.0000, 0.0000, 0.0000, 70.0000, 70.0000, NULL, 0.0000, 77.0000, 7.0000, 1, NULL, NULL, NULL, 0.0000, NULL, NULL, 0.0000, NULL, NULL, NULL, '', NULL, '2018-01-06 01:07:23', '2018-01-06 01:07:23'),
(4, 8, 2, 2, 50.0000, 0.0000, 0.0000, 70.0000, 70.0000, NULL, 0.0000, 77.0000, 7.0000, 1, NULL, NULL, NULL, 0.0000, NULL, NULL, 0.0000, NULL, NULL, NULL, '', NULL, '2018-01-06 01:07:23', '2018-01-06 01:07:23'),
(5, 9, 21, 64, 60.0000, 0.0000, 0.0000, 12.5000, 12.5000, NULL, 0.0000, 12.5000, 0.0000, NULL, NULL, NULL, NULL, 0.0000, NULL, NULL, 0.0000, NULL, NULL, NULL, '', NULL, '2018-01-06 01:07:45', '2018-01-06 01:07:45'),
(6, 10, 28, 71, 30.0000, 0.0000, 0.0000, 12.5000, 12.5000, NULL, 0.0000, 13.7500, 1.2500, 1, NULL, NULL, NULL, 0.0000, NULL, NULL, 0.0000, NULL, NULL, NULL, '', NULL, '2018-01-06 01:08:03', '2018-01-06 01:08:03'),
(7, 15, 34, 77, 10.0000, 0.0000, 0.0000, 7.5000, 7.5000, NULL, 0.0000, 7.5000, 0.0000, NULL, NULL, NULL, NULL, 0.0000, '', NULL, 0.0000, NULL, NULL, NULL, '', NULL, '2018-04-10 04:23:35', '2018-04-10 04:23:35'),
(8, 16, 32, 75, 7.0000, 0.0000, 0.0000, 15.0000, 15.0000, NULL, 0.0000, 15.0000, 0.0000, NULL, NULL, NULL, NULL, 0.0000, '', NULL, 0.0000, NULL, NULL, NULL, '', NULL, '2018-04-10 04:23:58', '2018-04-10 04:23:58'),
(9, 17, 33, 76, 27.0000, 0.0000, 0.0000, 15.0000, 15.0000, NULL, 0.0000, 15.0000, 0.0000, NULL, NULL, NULL, NULL, 0.0000, '', NULL, 0.0000, NULL, NULL, NULL, '', NULL, '2018-04-10 04:24:21', '2018-04-10 04:26:26'),
(10, 23, 43, 86, 5.0000, 0.0000, 0.0000, 500.0000, 500.0000, NULL, 0.0000, 500.0000, 0.0000, NULL, NULL, NULL, NULL, 0.0000, 'IHN45822225007', NULL, 0.0000, NULL, NULL, NULL, '', NULL, '2018-04-10 05:44:47', '2018-04-10 05:47:10'),
(11, 24, 44, 87, 20.0000, 0.0000, 0.0000, 243.7500, 243.7500, NULL, 0.0000, 243.7500, 0.0000, NULL, NULL, NULL, NULL, 0.0000, 'SMJ7558455524', NULL, 0.0000, NULL, NULL, NULL, '', NULL, '2018-04-10 05:45:06', '2018-04-10 05:46:43'),
(12, 25, 41, 84, 1.0000, 0.0000, 0.0000, 625.0000, 625.0000, NULL, 0.0000, 625.0000, 0.0000, NULL, NULL, NULL, NULL, 0.0000, 'EXSD1225778855', NULL, 0.0000, NULL, NULL, NULL, '', NULL, '2018-04-10 05:46:02', '2018-04-10 05:46:02'),
(13, 26, 41, 84, 25.0000, 0.0000, 0.0000, 625.0000, 625.0000, NULL, 0.0000, 625.0000, 0.0000, NULL, NULL, NULL, NULL, 0.0000, 'HP020555548444', NULL, 0.0000, NULL, NULL, NULL, '', NULL, '2018-04-10 05:48:48', '2018-04-10 05:48:48'),
(14, 28, 42, 85, 20.0000, 0.0000, 0.0000, 650.0000, 650.0000, NULL, 0.0000, 650.0000, 0.0000, NULL, NULL, NULL, NULL, 0.0000, 'HP68400258F', NULL, 0.0000, NULL, NULL, NULL, '', NULL, '2018-04-10 05:54:16', '2018-04-10 05:54:16'),
(15, 29, 53, 96, 5.0000, 0.0000, 0.0000, 131.2500, 131.2500, NULL, 0.0000, 131.2500, 0.0000, NULL, NULL, NULL, NULL, 0.0000, '', NULL, 0.0000, NULL, NULL, NULL, '', NULL, '2018-04-10 07:04:40', '2018-04-10 07:04:40'),
(16, 30, 51, 94, 2.0000, 0.0000, 0.0000, 125.0000, 125.0000, NULL, 0.0000, 125.0000, 0.0000, NULL, NULL, NULL, NULL, 0.0000, '', NULL, 0.0000, NULL, NULL, NULL, '', NULL, '2018-04-10 07:04:54', '2018-04-10 07:04:54'),
(17, 31, 57, 100, 3.0000, 0.0000, 0.0000, 25.0000, 25.0000, NULL, 0.0000, 25.0000, 0.0000, NULL, NULL, NULL, NULL, 0.0000, '', NULL, 0.0000, NULL, NULL, NULL, '', NULL, '2018-04-10 07:05:19', '2018-04-10 07:05:19'),
(18, 32, 60, 103, 2.0000, 0.0000, 0.0000, 18.7500, 18.7500, NULL, 0.0000, 18.7500, 0.0000, NULL, NULL, NULL, NULL, 0.0000, '', NULL, 0.0000, NULL, NULL, NULL, '', NULL, '2018-04-10 07:07:50', '2018-04-10 07:07:50'),
(19, 33, 56, 99, 2.0000, 0.0000, 0.0000, 187.5000, 187.5000, NULL, 0.0000, 187.5000, 0.0000, NULL, NULL, NULL, NULL, 0.0000, '', NULL, 0.0000, NULL, NULL, NULL, '', NULL, '2018-04-10 07:08:01', '2018-04-10 07:08:01'),
(20, 34, 54, 97, 20.0000, 0.0000, 0.0000, 12.5000, 12.5000, NULL, 0.0000, 12.5000, 0.0000, NULL, NULL, NULL, NULL, 0.0000, '', NULL, 0.0000, NULL, NULL, NULL, '', NULL, '2018-04-10 07:08:15', '2018-04-10 07:08:15'),
(21, 35, 55, 98, 10.0000, 0.0000, 0.0000, 10.0000, 10.0000, NULL, 0.0000, 10.0000, 0.0000, NULL, NULL, NULL, NULL, 0.0000, '', NULL, 0.0000, NULL, NULL, NULL, '', NULL, '2018-04-10 07:08:27', '2018-04-10 07:08:27'),
(22, 36, 57, 100, 5.0000, 0.0000, 0.0000, 25.0000, 25.0000, NULL, 0.0000, 25.0000, 0.0000, NULL, NULL, NULL, NULL, 0.0000, '', NULL, 0.0000, NULL, NULL, NULL, '', NULL, '2018-04-10 07:08:49', '2018-04-10 07:08:49'),
(23, 41, 71, 114, 2.0000, 0.0000, 0.0000, 21.2500, 21.2500, NULL, 0.0000, 21.2500, 0.0000, NULL, NULL, NULL, NULL, 0.0000, '', NULL, 0.0000, NULL, NULL, NULL, '', NULL, '2018-07-13 01:44:40', '2018-07-13 01:44:40'),
(24, 42, 70, 113, 2.0000, 0.0000, 0.0000, 15.0000, 15.0000, NULL, 0.0000, 15.0000, 0.0000, NULL, NULL, NULL, NULL, 0.0000, '', NULL, 0.0000, NULL, NULL, NULL, '', NULL, '2018-07-13 01:45:28', '2018-07-13 01:45:28'),
(25, 42, 67, 110, 2.0000, 0.0000, 0.0000, 10.0000, 10.0000, NULL, 0.0000, 10.0000, 0.0000, NULL, NULL, NULL, NULL, 0.0000, '', NULL, 0.0000, NULL, NULL, NULL, '', NULL, '2018-07-13 01:45:28', '2018-07-13 01:45:28'),
(26, 42, 68, 111, 2.0000, 0.0000, 0.0000, 13.7500, 13.7500, NULL, 0.0000, 13.7500, 0.0000, NULL, NULL, NULL, NULL, 0.0000, '', NULL, 0.0000, NULL, NULL, NULL, '', NULL, '2018-07-13 01:45:28', '2018-07-13 01:45:28'),
(27, 43, 74, 117, 1.0000, 0.0000, 0.0000, 8.7500, 8.7500, NULL, 0.0000, 8.7500, 0.0000, NULL, NULL, NULL, NULL, 0.0000, '', NULL, 0.0000, NULL, NULL, NULL, '', NULL, '2018-07-13 01:46:04', '2018-07-13 01:46:04'),
(28, 43, 73, 116, 1.0000, 0.0000, 0.0000, 25.0000, 25.0000, NULL, 0.0000, 25.0000, 0.0000, NULL, NULL, NULL, NULL, 0.0000, '', NULL, 0.0000, NULL, NULL, NULL, '', NULL, '2018-07-13 01:46:04', '2018-07-13 01:46:04'),
(29, 44, 74, 117, 3.0000, 0.0000, 0.0000, 8.7500, 8.7500, NULL, 0.0000, 8.7500, 0.0000, NULL, NULL, NULL, NULL, 0.0000, '', NULL, 0.0000, NULL, NULL, NULL, '', NULL, '2018-07-13 01:46:36', '2018-07-13 01:46:36'),
(30, 44, 69, 112, 3.0000, 0.0000, 0.0000, 15.0000, 15.0000, NULL, 0.0000, 15.0000, 0.0000, NULL, NULL, NULL, NULL, 0.0000, '', NULL, 0.0000, NULL, NULL, NULL, '', NULL, '2018-07-13 01:46:36', '2018-07-13 01:46:36'),
(31, 53, 82, 130, 5.0000, 0.0000, 0.0000, 20.0000, 20.0000, NULL, 0.0000, 20.0000, 0.0000, NULL, NULL, NULL, NULL, 0.0000, '', NULL, 0.0000, NULL, NULL, NULL, '', NULL, '2019-08-18 13:09:19', '2019-08-18 13:09:19'),
(32, 53, 83, 131, 502.5000, 0.0000, 0.0000, 20.0000, 20.0000, NULL, 0.0000, 20.0000, 0.0000, NULL, NULL, NULL, NULL, 0.5000, '', NULL, 0.0000, NULL, NULL, NULL, '', NULL, '2019-08-18 13:09:19', '2019-08-18 13:09:19'),
(33, 53, 84, 132, 50.0000, 0.0000, 0.0000, 20.0000, 20.0000, NULL, 0.0000, 20.0000, 0.0000, NULL, NULL, NULL, NULL, 0.0000, '', NULL, 0.0000, NULL, NULL, NULL, '', NULL, '2019-08-18 13:09:19', '2019-08-18 13:09:19'),
(34, 53, 85, 133, 100.0000, 0.0000, 0.0000, 2.0000, 2.0000, NULL, 0.0000, 2.0000, 0.0000, NULL, NULL, NULL, NULL, 0.0000, '', NULL, 0.0000, NULL, NULL, NULL, '', NULL, '2019-08-18 13:09:19', '2019-08-18 13:09:19'),
(35, 53, 87, 135, 500.0000, 0.0000, 0.0000, 2.0000, 2.0000, NULL, 0.0000, 2.0000, 0.0000, NULL, NULL, NULL, NULL, 0.0000, '', NULL, 0.0000, NULL, NULL, NULL, '', NULL, '2019-08-18 13:09:19', '2019-08-18 13:09:19'),
(36, 53, 88, 136, 100.0000, 0.0000, 0.0000, 10.0000, 10.0000, NULL, 0.0000, 10.0000, 0.0000, NULL, NULL, NULL, NULL, 0.0000, '', NULL, 0.0000, NULL, NULL, NULL, '', NULL, '2019-08-18 13:09:19', '2019-08-18 13:09:19');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_sell_lines_purchase_lines`
--

CREATE TABLE `transaction_sell_lines_purchase_lines` (
  `id` bigint(20) NOT NULL,
  `sell_line_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'id from transaction_sell_lines',
  `stock_adjustment_line_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'id from stock_adjustment_lines',
  `purchase_line_id` int(10) UNSIGNED NOT NULL COMMENT 'id from purchase_lines',
  `quantity` decimal(22,4) NOT NULL,
  `qty_returned` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `types_of_services`
--

CREATE TABLE `types_of_services` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `business_id` int(11) NOT NULL,
  `location_price_group` text DEFAULT NULL,
  `packing_charge` decimal(22,4) DEFAULT NULL,
  `packing_charge_type` enum('fixed','percent') DEFAULT NULL,
  `enable_custom_fields` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `id` int(10) UNSIGNED NOT NULL,
  `business_id` int(10) UNSIGNED NOT NULL,
  `actual_name` varchar(191) NOT NULL,
  `short_name` varchar(191) NOT NULL,
  `allow_decimal` tinyint(1) NOT NULL,
  `base_unit_id` int(11) DEFAULT NULL,
  `base_unit_multiplier` decimal(20,4) DEFAULT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id`, `business_id`, `actual_name`, `short_name`, `allow_decimal`, `base_unit_id`, `base_unit_multiplier`, `created_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'Pieces', 'Pc(s)', 0, NULL, NULL, 1, NULL, '2018-01-03 09:15:20', '2018-01-03 09:15:20'),
(2, 1, 'Packets', 'packets', 0, NULL, NULL, 1, NULL, '2018-01-05 19:07:01', '2018-01-05 19:08:36'),
(3, 1, 'Grams', 'g', 1, NULL, NULL, 1, NULL, '2018-01-05 19:10:34', '2018-01-05 19:10:34'),
(4, 2, 'Pieces', 'Pc(s)', 0, NULL, NULL, 4, NULL, '2018-04-09 20:42:40', '2018-04-09 20:42:40'),
(5, 3, 'Units', 'units', 0, NULL, NULL, 5, NULL, '2018-04-09 23:16:16', '2018-04-09 23:29:19'),
(6, 4, 'Units', 'units', 0, NULL, NULL, 6, NULL, '2018-04-10 00:50:43', '2018-04-10 01:02:04'),
(7, 5, 'Pieces', 'Pc(s)', 0, NULL, NULL, 7, NULL, '2018-07-12 23:42:11', '2018-07-12 23:42:11'),
(8, 5, 'Packets', 'packets', 0, NULL, NULL, 7, NULL, '2018-07-12 14:27:16', '2018-07-12 14:27:16'),
(9, 6, 'Pieces', 'Pc(s)', 0, NULL, NULL, 12, NULL, '2019-08-18 07:11:28', '2019-08-18 07:11:28'),
(10, 6, 'Grams', 'Gram', 1, NULL, NULL, 12, NULL, '2019-08-18 07:17:54', '2019-08-18 07:17:54'),
(11, 6, 'Kilogram', 'KG', 1, 10, 1000.0000, 12, NULL, '2019-08-18 07:18:17', '2019-08-18 07:18:17'),
(12, 6, '300 grams  Packets', '300 gm pkt', 0, 10, 300.0000, 12, NULL, '2019-08-18 07:19:30', '2019-08-18 07:19:30'),
(13, 6, 'Meters', 'Mtr', 1, NULL, NULL, 12, NULL, '2019-08-18 07:45:01', '2019-08-18 07:45:01');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_type` varchar(191) NOT NULL DEFAULT 'user',
  `surname` char(10) DEFAULT NULL,
  `first_name` varchar(191) NOT NULL,
  `last_name` varchar(191) DEFAULT NULL,
  `username` varchar(191) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `password` varchar(191) DEFAULT NULL,
  `language` char(7) NOT NULL DEFAULT 'en',
  `contact_no` char(15) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `business_id` int(10) UNSIGNED DEFAULT NULL,
  `available_at` datetime DEFAULT NULL COMMENT 'Service staff avilable at. Calculated from product preparation_time_in_minutes',
  `paused_at` datetime DEFAULT NULL COMMENT 'Service staff available time paused at, Will be nulled on resume.',
  `max_sales_discount_percent` decimal(5,2) DEFAULT NULL,
  `allow_login` tinyint(1) NOT NULL DEFAULT 1,
  `status` enum('active','inactive','terminated') NOT NULL DEFAULT 'active',
  `is_enable_service_staff_pin` tinyint(1) NOT NULL DEFAULT 0,
  `service_staff_pin` text DEFAULT NULL,
  `crm_contact_id` int(10) UNSIGNED DEFAULT NULL,
  `is_cmmsn_agnt` tinyint(1) NOT NULL DEFAULT 0,
  `cmmsn_percent` decimal(4,2) NOT NULL DEFAULT 0.00,
  `selected_contacts` tinyint(1) NOT NULL DEFAULT 0,
  `dob` date DEFAULT NULL,
  `gender` varchar(191) DEFAULT NULL,
  `marital_status` enum('married','unmarried','divorced') DEFAULT NULL,
  `blood_group` char(10) DEFAULT NULL,
  `contact_number` char(20) DEFAULT NULL,
  `alt_number` varchar(191) DEFAULT NULL,
  `family_number` varchar(191) DEFAULT NULL,
  `fb_link` varchar(191) DEFAULT NULL,
  `twitter_link` varchar(191) DEFAULT NULL,
  `social_media_1` varchar(191) DEFAULT NULL,
  `social_media_2` varchar(191) DEFAULT NULL,
  `permanent_address` text DEFAULT NULL,
  `current_address` text DEFAULT NULL,
  `guardian_name` varchar(191) DEFAULT NULL,
  `custom_field_1` varchar(191) DEFAULT NULL,
  `custom_field_2` varchar(191) DEFAULT NULL,
  `custom_field_3` varchar(191) DEFAULT NULL,
  `custom_field_4` varchar(191) DEFAULT NULL,
  `bank_details` longtext DEFAULT NULL,
  `id_proof_name` varchar(191) DEFAULT NULL,
  `id_proof_number` varchar(191) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_type`, `surname`, `first_name`, `last_name`, `username`, `email`, `password`, `language`, `contact_no`, `address`, `remember_token`, `business_id`, `available_at`, `paused_at`, `max_sales_discount_percent`, `allow_login`, `status`, `is_enable_service_staff_pin`, `service_staff_pin`, `crm_contact_id`, `is_cmmsn_agnt`, `cmmsn_percent`, `selected_contacts`, `dob`, `gender`, `marital_status`, `blood_group`, `contact_number`, `alt_number`, `family_number`, `fb_link`, `twitter_link`, `social_media_1`, `social_media_2`, `permanent_address`, `current_address`, `guardian_name`, `custom_field_1`, `custom_field_2`, `custom_field_3`, `custom_field_4`, `bank_details`, `id_proof_name`, `id_proof_number`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'user', 'Mr', 'Admin', NULL, 'admin', 'admin@example.com', '$2y$10$r59CptWXU.RaQvBiQv9/COzIZLJCFh3DLX7qnOGynJ7jZ/kQrawJq', 'en', NULL, NULL, 'Gg8emTP4O6ctPs0swwlgfoBS0jZbpTiqcgO9KAbwxQPlRSZxZ7d61CvhkoRM', 1, NULL, NULL, NULL, 1, 'active', 0, NULL, NULL, 0, 0.00, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-28 09:05:37', '2026-06-28 09:05:37'),
(2, 'user', 'Mr', 'Demo', 'Cashier', 'cashier', 'cashier@example.com', '$2y$10$r59CptWXU.RaQvBiQv9/COzIZLJCFh3DLX7qnOGynJ7jZ/kQrawJq', 'en', NULL, NULL, NULL, 1, NULL, NULL, NULL, 1, 'active', 0, NULL, NULL, 0, 0.00, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-28 09:05:37', '2026-06-28 09:05:37'),
(3, 'user', 'Mr.', 'Demo', 'Admin', 'demo-admin', 'demoadmin@example.com', '$2y$10$r59CptWXU.RaQvBiQv9/COzIZLJCFh3DLX7qnOGynJ7jZ/kQrawJq', 'en', NULL, NULL, NULL, 1, NULL, NULL, NULL, 1, 'active', 0, NULL, NULL, 0, 0.00, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-28 09:05:37', '2026-06-28 09:05:37'),
(4, 'user', 'Mr', 'Demo', 'Admin', 'admin-pharmacy', 'admin-pharma@example.com', '$2y$10$r59CptWXU.RaQvBiQv9/COzIZLJCFh3DLX7qnOGynJ7jZ/kQrawJq', 'en', NULL, NULL, 'MJnleh4kefXZtw3FCI2yHKEwr45Rs5nZxyQyGSFRfjIcQn93sJZyw7BAzSUq', 2, NULL, NULL, NULL, 1, 'active', 0, NULL, NULL, 0, 0.00, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-28 09:05:37', '2026-06-28 09:05:37'),
(5, 'user', 'Mr', 'Demo', 'Admin', 'admin-electronics', 'admin-electronics@example.com', '$2y$10$r59CptWXU.RaQvBiQv9/COzIZLJCFh3DLX7qnOGynJ7jZ/kQrawJq', 'en', NULL, NULL, 'YzGFoRAfllUjyB12qIZhowlteUPhvR1cd77YREQuqemSUftHp72aRLZMrzwR', 3, NULL, NULL, NULL, 1, 'active', 0, NULL, NULL, 0, 0.00, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-28 09:05:37', '2026-06-28 09:05:37'),
(6, 'user', 'Mr', 'Demo', 'Admin', 'admin-services', 'admin-services@example.com', '$2y$10$r59CptWXU.RaQvBiQv9/COzIZLJCFh3DLX7qnOGynJ7jZ/kQrawJq', 'en', NULL, NULL, 'CdBNjmTIXVU0nEgcEg85L97w6JEjvuE6CfF7mvhmz7Ad2K7IxJVd9WPLdxcg', 4, NULL, NULL, NULL, 1, 'active', 0, NULL, NULL, 0, 0.00, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-28 09:05:37', '2026-06-28 09:05:37'),
(7, 'user', 'Mr', 'Demo', 'Admin', 'admin-restaurant', 'admin-restaurant@example.com', '$2y$10$r59CptWXU.RaQvBiQv9/COzIZLJCFh3DLX7qnOGynJ7jZ/kQrawJq', 'en', NULL, NULL, 'ZzGDokwQe0vR0YpbBBWwbCWOGAkTgMS4wZlQOzAgWs2yAnovdGFkRwjofy1Y', 5, NULL, NULL, NULL, 1, 'active', 0, NULL, NULL, 0, 0.00, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-28 09:05:37', '2026-06-28 09:05:37'),
(8, 'user', 'Mr', 'Kevin', 'Nicols', 'kevin-nicols', 'kevin@example.com', '$2y$10$r59CptWXU.RaQvBiQv9/COzIZLJCFh3DLX7qnOGynJ7jZ/kQrawJq', 'en', NULL, NULL, '57dRoj3knI8dJ9UPSqFo2EH0CVTco5WJHnDgrmB2CY4lvJR6ZTtmzHi7NdLa', 5, NULL, NULL, NULL, 1, 'active', 0, NULL, NULL, 0, 0.00, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-28 09:05:37', '2026-06-28 09:05:37'),
(9, 'user', 'Mr.', 'Super', 'Admin', 'superadmin', 'superadmin@example.com', '$2y$10$r59CptWXU.RaQvBiQv9/COzIZLJCFh3DLX7qnOGynJ7jZ/kQrawJq', 'en', NULL, NULL, NULL, 1, NULL, NULL, NULL, 1, 'active', 0, NULL, NULL, 0, 0.00, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-28 09:05:37', '2026-06-28 09:05:37'),
(10, 'user', 'Mr.', 'WooCommerce', 'User', 'woocommerce_user', 'woo@example.com', '$2y$10$r59CptWXU.RaQvBiQv9/COzIZLJCFh3DLX7qnOGynJ7jZ/kQrawJq', 'en', NULL, NULL, NULL, 1, NULL, NULL, NULL, 1, 'active', 0, NULL, NULL, 0, 0.00, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-28 09:05:37', '2026-06-28 09:05:37'),
(11, 'user', 'Mr', 'Admin Essential', NULL, 'admin-essentials', 'admin_essentials@example.com', '$2y$10$r59CptWXU.RaQvBiQv9/COzIZLJCFh3DLX7qnOGynJ7jZ/kQrawJq', 'en', NULL, NULL, '6wUbpN3xEjDDyQwCfHiGqO7JkIQgjYoDFeQMxcp09YQXq1Ih1e5EqydddBMz', 1, NULL, NULL, NULL, 1, 'active', 0, NULL, NULL, 0, 0.00, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-28 09:05:37', '2026-06-28 09:05:37'),
(12, 'user', 'Mr.', 'Mike', 'Lee', 'manufacturer-demo', 'manufacturer-demo@demo.com', '$2y$10$r59CptWXU.RaQvBiQv9/COzIZLJCFh3DLX7qnOGynJ7jZ/kQrawJq', 'en', NULL, NULL, NULL, 6, NULL, NULL, NULL, 1, 'active', 0, NULL, NULL, 0, 0.00, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-28 09:05:37', '2026-06-28 09:05:37');

-- --------------------------------------------------------

--
-- Table structure for table `user_contact_access`
--

CREATE TABLE `user_contact_access` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `contact_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `variations`
--

CREATE TABLE `variations` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `sub_sku` varchar(191) DEFAULT NULL,
  `product_variation_id` int(10) UNSIGNED NOT NULL,
  `variation_value_id` int(11) DEFAULT NULL,
  `woocommerce_variation_id` int(10) UNSIGNED NOT NULL,
  `default_purchase_price` decimal(22,4) DEFAULT NULL,
  `dpp_inc_tax` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `profit_percent` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `default_sell_price` decimal(22,4) DEFAULT NULL,
  `sell_price_inc_tax` decimal(22,4) DEFAULT NULL COMMENT 'Sell price including tax',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `combo_variations` text DEFAULT NULL COMMENT 'Contains the combo variation details'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `variations`
--

INSERT INTO `variations` (`id`, `name`, `product_id`, `sub_sku`, `product_variation_id`, `variation_value_id`, `woocommerce_variation_id`, `default_purchase_price`, `dpp_inc_tax`, `profit_percent`, `default_sell_price`, `sell_price_inc_tax`, `created_at`, `updated_at`, `deleted_at`, `combo_variations`) VALUES
(1, 'DUMMY', 1, 'AS0001', 1, NULL, 0, 130.0000, 143.0000, 0.0000, 130.0000, 143.0000, '2018-01-03 15:29:08', '2018-06-11 01:40:59', NULL, NULL),
(2, '28', 2, 'AS0002-1', 2, 21, 0, 70.0000, 77.0000, 0.0000, 70.0000, 77.0000, '2018-01-03 15:30:35', '2020-05-08 03:24:13', NULL, NULL),
(3, '30', 2, 'AS0002-2', 2, 22, 0, 70.0000, 77.0000, 0.0000, 70.0000, 77.0000, '2018-01-03 15:30:35', '2020-05-08 03:24:13', NULL, NULL),
(4, '32', 2, 'AS0002-3', 2, 23, 0, 70.0000, 77.0000, 0.0000, 70.0000, 77.0000, '2018-01-03 15:30:35', '2020-05-08 03:24:13', NULL, NULL),
(5, '34', 2, 'AS0002-4', 2, 24, 0, 72.0000, 79.2000, 0.0000, 72.0000, 79.2000, '2018-01-03 15:30:35', '2020-05-08 03:24:13', NULL, NULL),
(6, '36', 2, 'AS0002-5', 2, 25, 0, 72.0000, 79.2000, 0.0000, 72.0000, 79.2000, '2018-01-03 15:30:35', '2020-05-08 03:24:14', NULL, NULL),
(7, 'S', 3, 'AS0003-1', 3, 26, 0, 190.0000, 209.0000, 0.0000, 190.0000, 209.0000, '2018-01-03 16:51:52', '2020-05-08 03:24:14', NULL, NULL),
(8, 'M', 3, 'AS0003-2', 3, 27, 0, 190.0000, 209.0000, 0.0000, 190.0000, 209.0000, '2018-01-03 16:51:52', '2020-05-08 03:24:14', NULL, NULL),
(9, 'L', 3, 'AS0003-3', 3, 28, 0, 190.0000, 209.0000, 0.0000, 190.0000, 209.0000, '2018-01-03 16:51:52', '2020-05-08 03:24:14', NULL, NULL),
(10, 'XL', 3, 'AS0003-4', 3, 29, 0, 191.0000, 210.1000, 0.0000, 191.0000, 210.1000, '2018-01-03 16:51:52', '2020-05-08 03:24:14', NULL, NULL),
(11, '6', 4, 'AS0004-1', 4, 30, 0, 165.0000, 181.5000, 0.0000, 165.0000, 181.5000, '2018-01-03 16:54:34', '2020-05-08 03:24:14', NULL, NULL),
(12, '7', 4, 'AS0004-2', 4, 31, 0, 165.0000, 181.5000, 0.0000, 165.0000, 181.5000, '2018-01-03 16:54:34', '2020-05-08 03:24:14', NULL, NULL),
(13, '8', 4, 'AS0004-3', 4, 32, 0, 165.0000, 181.5000, 0.0000, 165.0000, 181.5000, '2018-01-03 16:54:34', '2020-05-08 03:24:14', NULL, NULL),
(14, '9', 4, 'AS0004-4', 4, 33, 0, 166.0000, 182.6000, 0.0000, 166.0000, 182.6000, '2018-01-03 16:54:34', '2020-05-08 03:24:14', NULL, NULL),
(27, '6', 8, 'AS0008-1', 8, 30, 0, 110.0000, 121.0000, 0.0000, 110.0000, 121.0000, '2018-01-03 17:10:10', '2020-05-08 03:24:14', NULL, NULL),
(28, '7', 8, 'AS0008-2', 8, 31, 0, 110.0000, 121.0000, 0.0000, 110.0000, 121.0000, '2018-01-03 17:10:10', '2020-05-08 03:24:14', NULL, NULL),
(29, '8', 8, 'AS0008-3', 8, 32, 0, 110.0000, 121.0000, 0.0000, 110.0000, 121.0000, '2018-01-03 17:10:10', '2020-05-08 03:24:14', NULL, NULL),
(30, '9', 8, 'AS0008-4', 8, 33, 0, 110.0000, 121.0000, 0.0000, 110.0000, 121.0000, '2018-01-03 17:10:10', '2020-05-08 03:24:14', NULL, NULL),
(31, '6', 9, 'AS0009-1', 9, 30, 0, 135.0000, 148.5000, 0.0000, 135.0000, 148.5000, '2018-01-03 17:11:57', '2020-05-08 03:24:14', NULL, NULL),
(32, '7', 9, 'AS0009-2', 9, 31, 0, 135.0000, 148.5000, 0.0000, 135.0000, 148.5000, '2018-01-03 17:11:57', '2020-05-08 03:24:14', NULL, NULL),
(33, '8', 9, 'AS0009-3', 9, 32, 0, 135.0000, 148.5000, 0.0000, 135.0000, 148.5000, '2018-01-03 17:11:57', '2020-05-08 03:24:14', NULL, NULL),
(34, '9', 9, 'AS0009-4', 9, 33, 0, 135.0000, 148.5000, 0.0000, 135.0000, 148.5000, '2018-01-03 17:11:57', '2020-05-08 03:24:14', NULL, NULL),
(35, '5', 10, 'AS0010-1', 10, 34, 0, 150.0000, 165.0000, 0.0000, 150.0000, 165.0000, '2018-01-03 17:13:02', '2020-05-08 03:24:14', NULL, NULL),
(36, '6', 10, 'AS0010-2', 10, 30, 0, 150.0000, 165.0000, 0.0000, 150.0000, 165.0000, '2018-01-03 17:13:02', '2020-05-08 03:24:14', NULL, NULL),
(37, '7', 10, 'AS0010-3', 10, 31, 0, 150.0000, 165.0000, 0.0000, 150.0000, 165.0000, '2018-01-03 17:13:02', '2020-05-08 03:24:14', NULL, NULL),
(38, '8', 10, 'AS0010-4', 10, 32, 0, 150.0000, 165.0000, 0.0000, 150.0000, 165.0000, '2018-01-03 17:13:02', '2020-05-08 03:24:14', NULL, NULL),
(39, '9', 10, 'AS0010-5', 10, 33, 0, 150.0000, 165.0000, 0.0000, 150.0000, 165.0000, '2018-01-03 17:13:02', '2020-05-08 03:24:14', NULL, NULL),
(40, 'DUMMY', 11, 'AS0011', 11, NULL, 0, 30.0000, 33.0000, 0.0000, 30.0000, 33.0000, '2018-01-03 17:14:35', '2018-06-11 01:36:08', NULL, NULL),
(41, 'DUMMY', 12, 'AS0012', 12, NULL, 0, 25.0000, 27.5000, 0.0000, 25.0000, 27.5000, '2018-01-03 17:15:50', '2018-06-11 01:35:50', NULL, NULL),
(42, 'M', 13, 'AS0013-1', 13, 27, 0, 60.0000, 66.0000, 0.0000, 60.0000, 66.0000, '2018-01-03 17:17:59', '2020-05-08 03:24:14', NULL, NULL),
(43, 'L', 13, 'AS0013-2', 13, 28, 0, 60.0000, 66.0000, 0.0000, 60.0000, 66.0000, '2018-01-03 17:17:59', '2020-05-08 03:24:14', NULL, NULL),
(44, 'XL', 13, 'AS0013-3', 13, 29, 0, 60.0000, 66.0000, 0.0000, 60.0000, 66.0000, '2018-01-03 17:17:59', '2020-05-08 03:24:14', NULL, NULL),
(45, 'Gray', 14, 'AS0014-1', 14, 35, 0, 700.0000, 770.0000, 25.0000, 875.0000, 962.5000, '2018-01-05 23:42:19', '2020-05-08 03:24:14', NULL, NULL),
(46, 'Black', 14, 'AS0014-2', 14, 16, 0, 700.0000, 770.0000, 25.0000, 875.0000, 962.5000, '2018-01-05 23:42:19', '2020-05-08 03:24:14', NULL, NULL),
(47, '64 GB', 14, 'AS0014-1', 15, 36, 0, 700.0000, 770.0000, 25.0000, 875.0000, 962.5000, '2018-01-05 23:44:14', '2020-05-08 03:24:14', NULL, NULL),
(48, '128 GB', 14, 'AS0014-2', 15, 37, 0, 800.0000, 880.0000, 25.0000, 1000.0000, 1100.0000, '2018-01-05 23:44:14', '2020-05-08 03:24:14', NULL, NULL),
(49, 'White', 15, 'AS0015-1', 16, 38, 0, 950.0000, 1045.0000, 25.0000, 1187.5000, 1306.2500, '2018-01-05 23:49:51', '2020-05-08 03:24:14', NULL, NULL),
(50, 'Gray', 15, 'AS0015-2', 16, 35, 0, 950.0000, 1045.0000, 25.0000, 1187.5000, 1306.2500, '2018-01-05 23:49:51', '2020-05-08 03:24:14', NULL, NULL),
(51, 'Black', 15, 'AS0015-3', 16, 16, 0, 950.0000, 1045.0000, 25.0000, 1187.5000, 1306.2500, '2018-01-05 23:49:51', '2020-05-08 03:24:14', NULL, NULL),
(52, '32 GB', 15, 'AS0015-1', 17, 39, 0, 950.0000, 1045.0000, 25.0000, 1187.5000, 1306.2500, '2018-01-05 23:49:51', '2020-05-08 03:24:14', NULL, NULL),
(53, '64 GB', 15, 'AS0015-2', 17, 36, 0, 1010.0000, 1111.0000, 25.0000, 1262.5000, 1388.7500, '2018-01-05 23:49:51', '2020-05-08 03:24:14', NULL, NULL),
(54, 'Gold', 16, 'AS0016-1', 18, 20, 0, 350.0000, 350.0000, 25.0000, 437.5000, 437.5000, '2018-01-05 23:54:48', '2020-05-08 03:24:14', NULL, NULL),
(55, 'White', 16, 'AS0016-2', 18, 38, 0, 350.0000, 350.0000, 25.0000, 437.5000, 437.5000, '2018-01-05 23:54:48', '2020-05-08 03:24:14', NULL, NULL),
(56, 'Black', 16, 'AS0016-3', 18, 16, 0, 350.0000, 350.0000, 25.0000, 437.5000, 437.5000, '2018-01-05 23:54:48', '2020-05-08 03:24:14', NULL, NULL),
(57, 'Black', 17, 'AS0017-1', 19, 16, 0, 350.0000, 350.0000, 25.0000, 437.5000, 437.5000, '2018-01-06 00:05:01', '2020-05-08 03:24:14', NULL, NULL),
(58, 'White', 17, 'AS0017-2', 19, 38, 0, 350.0000, 350.0000, 25.0000, 437.5000, 437.5000, '2018-01-06 00:05:01', '2020-05-08 03:24:14', NULL, NULL),
(59, '256 GB', 18, 'AS0018-1', 20, 40, 0, 1350.0000, 1350.0000, 25.0000, 1687.5000, 1687.5000, '2018-01-06 00:07:30', '2020-05-08 03:24:14', NULL, NULL),
(60, '500 GB', 18, 'AS0018-2', 20, 41, 0, 1450.0000, 1450.0000, 25.0000, 1812.5000, 1812.5000, '2018-01-06 00:07:30', '2020-05-08 03:24:14', NULL, NULL),
(61, 'DUMMY', 19, 'AS0019', 21, NULL, 0, 8.0000, 8.0000, 25.0000, 10.0000, 10.0000, '2018-01-06 00:10:28', '2018-06-11 01:38:23', NULL, NULL),
(62, 'Gray', 20, 'AS0020-1', 22, 35, 0, 25.0000, 27.5000, 25.0000, 31.2500, 34.3800, '2018-01-06 00:13:00', '2020-05-08 03:24:14', NULL, NULL),
(63, 'Black', 20, 'AS0020-2', 22, 16, 0, 25.0000, 27.5000, 25.0000, 31.2500, 34.3800, '2018-01-06 00:13:00', '2020-05-08 03:24:14', NULL, NULL),
(64, 'DUMMY', 21, 'AS0021', 23, NULL, 0, 10.0000, 10.0000, 25.0000, 12.5000, 12.5000, '2018-01-06 00:16:35', '2018-06-11 01:42:13', NULL, NULL),
(65, 'DUMMY', 22, 'AS0022', 24, NULL, 0, 8.0000, 8.8000, 25.0000, 10.0000, 11.0000, '2018-01-06 00:25:09', '2018-06-11 01:38:37', NULL, NULL),
(66, 'DUMMY', 23, 'AS0023', 25, NULL, 0, 10.0000, 10.0000, 25.0000, 12.5000, 12.5000, '2018-01-06 00:26:27', '2018-06-11 01:43:51', NULL, NULL),
(67, 'DUMMY', 24, 'AS0024', 26, NULL, 0, 8.0000, 8.8000, 25.0000, 10.0000, 11.0000, '2018-01-06 00:31:22', '2018-06-11 01:38:53', NULL, NULL),
(68, 'DUMMY', 25, 'AS0025', 27, NULL, 0, 15.0000, 15.0000, 25.0000, 18.7500, 18.7500, '2018-01-06 00:32:23', '2018-06-11 01:39:09', NULL, NULL),
(69, 'DUMMY', 26, 'AS0026', 28, NULL, 0, 5.0000, 5.0000, 25.0000, 6.2500, 6.2500, '2018-01-06 00:35:51', '2018-06-11 01:41:51', NULL, NULL),
(70, 'DUMMY', 27, 'AS0027', 29, NULL, 0, 20.0000, 22.0000, 25.0000, 25.0000, 27.5000, '2018-01-06 00:43:16', '2018-06-11 01:38:01', NULL, NULL),
(71, 'DUMMY', 28, 'AS0028', 30, NULL, 0, 10.0000, 11.0000, 25.0000, 12.5000, 13.7500, '2018-01-06 00:45:47', '2018-06-11 01:37:45', NULL, NULL),
(72, 'DUMMY', 29, 'AS0029', 31, NULL, 0, 12.0000, 12.0000, 25.0000, 15.0000, 15.0000, '2018-01-06 00:46:53', '2018-06-11 01:44:34', NULL, NULL),
(73, 'DUMMY', 30, 'AS0030', 32, NULL, 0, 40.0000, 44.0000, 25.0000, 50.0000, 55.0000, '2018-01-06 00:48:59', '2018-06-11 01:39:59', NULL, NULL),
(74, 'DUMMY', 31, 'AP0031', 33, NULL, 0, 7.0000, 7.0000, 25.0000, 8.7500, 8.7500, '2018-04-10 04:06:29', '2018-04-10 04:06:29', NULL, NULL),
(75, 'DUMMY', 32, 'AP0032', 34, NULL, 0, 12.0000, 12.0000, 25.0000, 15.0000, 15.0000, '2018-04-10 04:07:52', '2018-04-10 04:07:52', NULL, NULL),
(76, 'DUMMY', 33, 'AP0033', 35, NULL, 0, 12.0000, 12.0000, 25.0000, 15.0000, 15.0000, '2018-04-10 04:09:45', '2018-04-10 04:09:45', NULL, NULL),
(77, 'DUMMY', 34, 'AP0034', 36, NULL, 0, 6.0000, 6.0000, 25.0000, 7.5000, 7.5000, '2018-04-10 04:10:59', '2018-04-10 04:10:59', NULL, NULL),
(78, 'DUMMY', 35, 'AP0035', 37, NULL, 0, 7.0000, 7.0000, 25.0000, 8.7500, 8.7500, '2018-04-10 04:12:53', '2018-04-10 04:12:53', NULL, NULL),
(79, 'DUMMY', 36, 'AP0036', 38, NULL, 0, 9.0000, 9.0000, 25.0000, 11.2500, 11.2500, '2018-04-10 04:14:18', '2018-04-10 04:14:18', NULL, NULL),
(80, 'DUMMY', 37, 'AP0037', 39, NULL, 0, 12.0000, 12.0000, 25.0000, 15.0000, 15.0000, '2018-04-10 04:31:29', '2018-04-10 04:31:29', NULL, NULL),
(81, 'DUMMY', 38, 'AP0038', 40, NULL, 0, 15.0000, 15.0000, 25.0000, 18.7500, 18.7500, '2018-04-10 04:32:35', '2018-04-10 04:32:35', NULL, NULL),
(82, 'DUMMY', 39, 'AP0039', 41, NULL, 0, 12.0000, 12.0000, 25.0000, 15.0000, 15.0000, '2018-04-10 04:33:26', '2018-04-10 04:33:26', NULL, NULL),
(83, 'DUMMY', 40, 'AP0040', 42, NULL, 0, 8.0000, 8.0000, 25.0000, 10.0000, 10.0000, '2018-04-10 04:34:32', '2018-04-10 04:34:49', NULL, NULL),
(84, 'DUMMY', 41, 'AE0041', 43, NULL, 0, 500.0000, 500.0000, 25.0000, 625.0000, 625.0000, '2018-04-10 05:08:27', '2018-04-10 05:08:27', NULL, NULL),
(85, 'DUMMY', 42, 'AE0042', 44, NULL, 0, 520.0000, 520.0000, 25.0000, 650.0000, 650.0000, '2018-04-10 05:15:52', '2018-04-10 05:51:21', NULL, NULL),
(86, 'DUMMY', 43, 'AE0043', 45, NULL, 0, 400.0000, 400.0000, 25.0000, 500.0000, 500.0000, '2018-04-10 05:18:01', '2018-04-10 05:18:01', NULL, NULL),
(87, 'DUMMY', 44, 'AE0044', 46, NULL, 0, 195.0000, 195.0000, 25.0000, 243.7500, 243.7500, '2018-04-10 05:19:10', '2018-04-10 05:19:10', NULL, NULL),
(88, 'DUMMY', 45, 'AE0045', 47, NULL, 0, 300.0000, 300.0000, 25.0000, 375.0000, 375.0000, '2018-04-10 05:20:22', '2018-04-10 05:20:22', NULL, NULL),
(89, 'DUMMY', 46, 'AE0046', 48, NULL, 0, 321.0000, 321.0000, 25.0000, 401.2500, 401.2500, '2018-04-10 05:22:04', '2018-04-10 05:22:04', NULL, NULL),
(90, 'DUMMY', 47, 'AE0047', 49, NULL, 0, 255.0000, 255.0000, 25.0000, 318.7500, 318.7500, '2018-04-10 05:23:45', '2018-04-10 05:23:45', NULL, NULL),
(91, 'DUMMY', 48, 'AE0048', 50, NULL, 0, 850.0000, 850.0000, 25.0000, 1062.5000, 1062.5000, '2018-04-10 05:24:42', '2018-04-10 05:52:07', NULL, NULL),
(92, 'DUMMY', 49, 'AE0049', 51, NULL, 0, 1500.0000, 1500.0000, 25.0000, 1875.0000, 1875.0000, '2018-04-10 05:25:55', '2018-04-10 05:25:55', NULL, NULL),
(93, 'DUMMY', 50, 'AE0050', 52, NULL, 0, 630.0000, 630.0000, 25.0000, 787.5000, 787.5000, '2018-04-10 05:34:32', '2018-04-10 05:34:32', NULL, NULL),
(94, 'DUMMY', 51, 'AS0051', 53, NULL, 0, 100.0000, 100.0000, 25.0000, 125.0000, 125.0000, '2018-04-10 06:34:57', '2018-04-10 06:34:57', NULL, NULL),
(95, 'DUMMY', 52, 'AS0052', 54, NULL, 0, 80.0000, 80.0000, 25.0000, 100.0000, 100.0000, '2018-04-10 06:35:55', '2018-04-10 06:35:55', NULL, NULL),
(96, 'DUMMY', 53, 'AS0053', 55, NULL, 0, 105.0000, 105.0000, 25.0000, 131.2500, 131.2500, '2018-04-10 06:39:15', '2018-04-10 06:39:15', NULL, NULL),
(97, 'DUMMY', 54, 'AS0054', 56, NULL, 0, 10.0000, 10.0000, 25.0000, 12.5000, 12.5000, '2018-04-10 06:54:16', '2018-04-10 06:54:16', NULL, NULL),
(98, 'DUMMY', 55, 'AS0055', 57, NULL, 0, 8.0000, 8.0000, 25.0000, 10.0000, 10.0000, '2018-04-10 06:56:18', '2018-04-10 06:56:18', NULL, NULL),
(99, 'DUMMY', 56, 'AS0056', 58, NULL, 0, 150.0000, 150.0000, 25.0000, 187.5000, 187.5000, '2018-04-10 07:01:59', '2018-04-10 07:01:59', NULL, NULL),
(100, 'DUMMY', 57, 'AS0057', 59, NULL, 0, 20.0000, 20.0000, 25.0000, 25.0000, 25.0000, '2018-04-10 07:03:01', '2018-04-10 07:03:01', NULL, NULL),
(101, 'DUMMY', 58, 'AS0058', 60, NULL, 0, 20.0000, 20.0000, 25.0000, 25.0000, 25.0000, '2018-04-10 07:03:37', '2018-04-10 07:03:37', NULL, NULL),
(102, 'DUMMY', 59, 'AS0059', 61, NULL, 0, 15.0000, 15.0000, 25.0000, 18.7500, 18.7500, '2018-04-10 07:06:45', '2018-04-10 07:06:45', NULL, NULL),
(103, 'DUMMY', 60, 'AS0060', 62, NULL, 0, 15.0000, 15.0000, 25.0000, 18.7500, 18.7500, '2018-04-10 07:07:08', '2018-04-10 07:07:08', NULL, NULL),
(104, 'DUMMY', 61, 'AS0061', 63, NULL, 0, 34.0000, 34.0000, 25.0000, 42.5000, 42.5000, '2018-06-11 15:57:41', '2018-06-11 15:57:41', NULL, NULL),
(105, 'DUMMY', 62, 'AS0062', 64, NULL, 0, 35.0000, 35.0000, 25.0000, 43.7500, 43.7500, '2018-06-11 16:02:17', '2018-06-11 16:02:17', NULL, NULL),
(106, 'DUMMY', 63, 'AS0063', 65, NULL, 0, 10.0000, 10.0000, 25.0000, 12.5000, 12.5000, '2018-06-11 16:08:12', '2018-06-11 16:08:12', NULL, NULL),
(107, 'DUMMY', 64, 'AS0064', 66, NULL, 0, 10.0000, 10.0000, 25.0000, 12.5000, 12.5000, '2018-06-11 16:10:34', '2018-06-11 16:10:34', NULL, NULL),
(108, 'DUMMY', 65, 'AS0065', 67, NULL, 0, 10.0000, 10.0000, 25.0000, 12.5000, 12.5000, '2018-06-11 16:14:00', '2018-06-11 16:14:30', NULL, NULL),
(109, 'DUMMY', 66, 'AR0066', 68, NULL, 0, 15.0000, 15.0000, 25.0000, 18.7500, 18.7500, '2018-07-13 00:30:31', '2018-07-13 00:30:31', NULL, NULL),
(110, 'DUMMY', 67, 'AR0067', 69, NULL, 0, 8.0000, 8.0000, 25.0000, 10.0000, 10.0000, '2018-07-13 00:34:47', '2018-07-13 00:34:47', NULL, NULL),
(111, 'DUMMY', 68, 'AR0068', 70, NULL, 0, 11.0000, 11.0000, 25.0000, 13.7500, 13.7500, '2018-07-13 00:38:55', '2018-07-13 00:38:55', NULL, NULL),
(112, 'DUMMY', 69, 'AR0069', 71, NULL, 0, 12.0000, 12.0000, 25.0000, 15.0000, 15.0000, '2018-07-13 00:45:51', '2018-07-13 00:45:51', NULL, NULL),
(113, 'DUMMY', 70, 'AR0070', 72, NULL, 0, 12.0000, 12.0000, 25.0000, 15.0000, 15.0000, '2018-07-13 00:57:37', '2018-07-13 00:57:37', NULL, NULL),
(114, 'DUMMY', 71, 'AR0071', 73, NULL, 0, 17.0000, 17.0000, 25.0000, 21.2500, 21.2500, '2018-07-13 01:00:41', '2018-07-13 01:00:41', NULL, NULL),
(115, 'DUMMY', 72, 'AR0072', 74, NULL, 0, 18.0000, 18.0000, 25.0000, 22.5000, 22.5000, '2018-07-13 01:05:03', '2018-07-13 01:05:03', NULL, NULL),
(116, 'DUMMY', 73, 'AR0073', 75, NULL, 0, 20.0000, 20.0000, 25.0000, 25.0000, 25.0000, '2018-07-13 01:07:29', '2018-07-13 01:07:29', NULL, NULL),
(117, 'DUMMY', 74, 'AR0074', 76, NULL, 0, 7.0000, 7.0000, 25.0000, 8.7500, 8.7500, '2018-07-13 01:10:45', '2018-07-13 01:10:45', NULL, NULL),
(118, 'DUMMY', 75, 'AR0075', 77, NULL, 0, 8.0000, 8.0000, 25.0000, 10.0000, 10.0000, '2018-07-13 01:13:09', '2018-07-13 01:13:09', NULL, NULL),
(119, 'DUMMY', 76, 'AR0076', 78, NULL, 0, 8.0000, 8.0000, 25.0000, 10.0000, 10.0000, '2018-07-13 01:18:33', '2018-07-13 01:18:33', NULL, NULL),
(120, 'DUMMY', 77, 'AR0077', 79, NULL, 0, 3.0000, 3.0000, 25.0000, 3.7500, 3.7500, '2018-07-13 01:24:22', '2018-07-13 01:28:56', NULL, NULL),
(121, 'Bacon', 78, 'AR0078-1', 80, NULL, 0, 2.0000, 2.0000, 0.0000, 2.0000, 2.0000, '2018-07-16 01:58:04', '2018-07-16 01:58:51', NULL, NULL),
(122, 'Black olives', 78, 'AR0078-2', 80, NULL, 0, 1.0000, 1.0000, 0.0000, 1.0000, 1.0000, '2018-07-16 01:58:33', '2018-07-16 01:58:51', NULL, NULL),
(123, 'Green peppers', 78, 'AR0078-3', 80, NULL, 0, 1.0000, 1.0000, 0.0000, 1.0000, 1.0000, '2018-07-16 01:58:51', '2018-07-16 01:58:51', NULL, NULL),
(124, 'Cheese', 79, 'AR0079-1', 81, NULL, 0, 2.0000, 2.0000, 0.0000, 2.0000, 2.0000, '2018-07-16 02:05:37', '2018-07-16 02:06:52', NULL, NULL),
(125, 'Vegies', 79, 'AR0079-2', 81, NULL, 0, 1.0000, 1.0000, 0.0000, 1.0000, 1.0000, '2018-07-16 02:05:37', '2018-07-16 02:06:52', NULL, NULL),
(126, 'Bacon', 79, 'AR0079-3', 81, NULL, 0, 2.0000, 2.0000, 0.0000, 2.0000, 2.0000, '2018-07-16 02:05:37', '2018-07-16 02:06:52', NULL, NULL),
(127, 'Chocolate', 79, 'AR0079-4', 81, NULL, 0, 2.0000, 2.0000, 0.0000, 2.0000, 2.0000, '2018-07-16 02:06:52', '2018-07-16 02:06:52', NULL, NULL),
(129, 'DUMMY', 81, 'Pizza', 83, NULL, 0, 100.0000, 100.0000, 25.0000, 125.0000, 125.0000, '2019-08-18 12:52:09', '2019-08-18 12:52:09', NULL, NULL),
(130, 'DUMMY', 82, '0082', 84, NULL, 0, 20.0000, 20.0000, 25.0000, 25.0000, 25.0000, '2019-08-18 12:54:10', '2019-08-18 12:54:10', NULL, NULL),
(131, 'DUMMY', 83, '0083', 85, NULL, 0, 20.0000, 20.0000, 25.0000, 25.0000, 25.0000, '2019-08-18 12:54:59', '2019-08-18 12:54:59', NULL, NULL),
(132, 'DUMMY', 84, '0084', 86, NULL, 0, 20.0000, 20.0000, 25.0000, 25.0000, 25.0000, '2019-08-18 12:55:28', '2019-08-18 12:55:28', NULL, NULL),
(133, 'DUMMY', 85, '0085', 87, NULL, 0, 2.0000, 2.0000, 25.0000, 2.5000, 2.5000, '2019-08-18 12:55:58', '2019-08-18 12:55:58', NULL, NULL),
(134, 'DUMMY', 86, '0086', 88, NULL, 0, 2.0000, 2.0000, 25.0000, 2.5000, 2.5000, '2019-08-18 12:56:41', '2019-08-18 12:56:41', NULL, NULL),
(135, 'DUMMY', 87, '0087', 89, NULL, 0, 2.0000, 2.0000, 25.0000, 2.5000, 2.5000, '2019-08-18 12:57:35', '2019-08-18 12:57:35', NULL, NULL),
(136, 'DUMMY', 88, '0088', 90, NULL, 0, 10.0000, 10.0000, 25.0000, 12.5000, 12.5000, '2019-08-18 12:58:43', '2019-08-18 12:58:43', NULL, NULL),
(137, 'DUMMY', 89, '0089', 91, NULL, 0, 10.0000, 10.0000, 25.0000, 12.5000, 12.5000, '2019-08-18 13:15:24', '2019-08-18 13:21:40', NULL, NULL),
(138, 'DUMMY', 90, '0090', 92, NULL, 0, 5.0000, 5.0000, 25.0000, 6.2500, 6.2500, '2019-08-18 13:16:50', '2019-08-18 13:16:50', NULL, NULL),
(139, 'DUMMY', 91, '0091', 93, NULL, 0, 0.5000, 0.5000, 25.0000, 0.6300, 0.6300, '2019-08-18 13:17:36', '2019-08-18 13:17:36', NULL, NULL),
(140, 'DUMMY', 92, '0092', 94, NULL, 0, 1.0000, 1.0000, 25.0000, 1.2500, 1.2500, '2019-08-18 13:18:26', '2019-08-18 13:18:26', NULL, NULL),
(141, 'DUMMY', 93, '0093', 95, NULL, 0, 20.0000, 20.0000, 25.0000, 25.0000, 25.0000, '2019-08-18 13:19:55', '2019-08-18 13:20:39', NULL, NULL),
(142, 'DUMMY', 94, 'AS0094', 96, NULL, 0, 58.0000, 58.0000, 25.0000, 72.5000, 72.5000, '2020-05-07 15:18:14', '2020-05-08 04:07:43', NULL, '[]'),
(143, 'DUMMY', 95, 'AS0095', 97, NULL, 0, 8.0000, 8.0000, 25.0000, 10.0000, 10.0000, '2020-05-07 15:18:58', '2020-05-07 15:18:58', NULL, '[]'),
(144, 'DUMMY', 96, 'AS0096', 98, NULL, 0, 8.0000, 8.0000, 8.0000, 8.6400, 8.6400, '2020-05-07 15:20:36', '2020-05-08 04:08:13', NULL, '[]'),
(145, 'DUMMY', 97, 'AS0097', 99, NULL, 0, 8.0000, 8.0000, 8.0000, 8.6400, 8.6400, '2020-05-07 15:21:08', '2020-05-08 04:08:21', NULL, '[]'),
(146, 'DUMMY', 98, 'AS0098', 100, NULL, 0, 8.0000, 8.0000, 0.0000, 8.0000, 8.0000, '2020-05-07 15:21:48', '2020-05-08 04:08:29', NULL, '[]'),
(147, 'DUMMY', 99, 'AS0099', 101, NULL, 0, 3509.0000, 3859.9000, 25.0000, 4386.2500, 4824.8800, '2020-05-11 06:47:54', '2020-05-11 06:47:54', NULL, '[]'),
(148, 'DUMMY', 100, 'AS0100', 102, NULL, 0, 4513.0000, 4513.0000, 25.0000, 5641.2500, 5641.2500, '2020-05-11 06:48:41', '2020-05-11 06:48:41', NULL, '[]'),
(149, 'DUMMY', 101, 'AS0101', 103, NULL, 0, 783.0000, 783.0000, 25.0000, 978.7500, 978.7500, '2020-05-11 06:49:21', '2020-05-11 06:49:21', NULL, '[]'),
(150, 'DUMMY', 102, 'AS0102', 104, NULL, 0, 525.0000, 525.0000, 25.0000, 656.2500, 656.2500, '2020-05-11 06:54:23', '2020-05-11 06:54:23', NULL, '[]'),
(151, 'DUMMY', 103, 'AS0103', 105, NULL, 0, 266.0000, 266.0000, 25.0000, 332.5000, 332.5000, '2020-05-11 06:55:24', '2020-05-11 06:55:24', NULL, '[]'),
(152, 'DUMMY', 104, 'AS0104', 106, NULL, 0, 555.0000, 555.0000, 25.0000, 693.7500, 693.7500, '2020-05-11 06:56:45', '2020-05-11 06:56:45', NULL, '[]'),
(153, 'DUMMY', 105, 'AS0105', 107, NULL, 0, 555.0000, 555.0000, 25.0000, 693.7500, 693.7500, '2020-05-11 06:58:12', '2020-05-11 06:58:12', NULL, '[]'),
(154, 'DUMMY', 106, 'AS0106', 108, NULL, 0, 555.0000, 555.0000, 25.0000, 693.7500, 693.7500, '2020-05-11 06:58:50', '2020-05-11 06:58:50', NULL, '[]'),
(155, 'DUMMY', 107, 'AS0107', 109, NULL, 0, 881.0000, 881.0000, 25.0000, 1101.2500, 1101.2500, '2020-05-11 06:59:22', '2020-05-11 06:59:22', NULL, '[]'),
(156, 'DUMMY', 108, 'AS0108', 110, NULL, 0, 884.0000, 884.0000, 25.0000, 1105.0000, 1105.0000, '2020-05-11 07:00:23', '2020-05-11 07:00:23', NULL, '[]'),
(157, 'DUMMY', 109, 'AS0109', 111, NULL, 0, 154.0000, 154.0000, 0.0000, 154.0000, 154.0000, '2020-05-11 07:01:11', '2020-05-11 07:01:11', NULL, '[]'),
(158, 'DUMMY', 110, 'AS0110', 112, NULL, 0, 324.0000, 324.0000, 25.0000, 405.0000, 405.0000, '2020-05-11 07:01:56', '2020-05-11 07:01:56', NULL, '[]'),
(159, 'DUMMY', 111, 'AS0111', 113, NULL, 0, 5666.0000, 5666.0000, 25.0000, 7082.5000, 7082.5000, '2020-05-11 07:10:25', '2020-05-11 07:10:25', NULL, '[]'),
(160, 'DUMMY', 112, 'AS0112', 114, NULL, 0, 8978.0000, 8978.0000, 25.0000, 11222.5000, 11222.5000, '2020-05-11 07:10:57', '2020-05-11 07:10:57', NULL, '[]'),
(161, 'DUMMY', 113, 'AS0113', 115, NULL, 0, 97785.0000, 97785.0000, 25.0000, 122231.2500, 122231.2500, '2020-05-11 07:12:17', '2020-05-11 07:12:17', NULL, '[]'),
(162, 'DUMMY', 114, 'AS0114', 116, NULL, 0, 800.0000, 800.0000, 25.0000, 1000.0000, 1000.0000, '2020-05-11 07:12:47', '2020-05-11 07:12:47', NULL, '[]'),
(163, 'DUMMY', 115, 'AS0115', 117, NULL, 0, 899.0000, 899.0000, 25.0000, 1123.7500, 1123.7500, '2020-05-11 07:13:30', '2020-05-11 07:13:30', NULL, '[]'),
(164, 'DUMMY', 116, 'AS0116', 118, NULL, 0, 9789.0000, 9789.0000, 25.0000, 12236.2500, 12236.2500, '2020-05-11 07:14:18', '2020-05-11 07:14:18', NULL, '[]'),
(165, 'DUMMY', 117, 'AS0117', 119, NULL, 0, 89789.0000, 89789.0000, 25.0000, 112236.2500, 112236.2500, '2020-05-11 07:15:05', '2020-05-11 07:15:05', NULL, '[]'),
(166, 'DUMMY', 118, 'AS0118', 120, NULL, 0, 798478.0000, 798478.0000, 25.0000, 998097.5000, 998097.5000, '2020-05-11 07:15:45', '2020-05-11 07:15:45', NULL, '[]'),
(167, 'DUMMY', 119, 'AS0119', 121, NULL, 0, 8978.0000, 8978.0000, 25.0000, 11222.5000, 11222.5000, '2020-05-11 07:17:11', '2020-05-11 07:17:11', NULL, '[]'),
(168, 'DUMMY', 120, 'AS0120', 122, NULL, 0, 2000.0000, 2000.0000, 25.0000, 2500.0000, 2500.0000, '2020-05-11 07:17:54', '2020-05-11 07:17:54', NULL, '[]'),
(169, 'DUMMY', 121, 'AS0121', 123, NULL, 0, 6000.0000, 6000.0000, 25.0000, 7500.0000, 7500.0000, '2020-05-11 07:18:38', '2020-05-11 07:18:38', NULL, '[]'),
(170, 'DUMMY', 122, 'AS0122', 124, NULL, 0, 3000.0000, 3000.0000, 25.0000, 3750.0000, 3750.0000, '2020-05-11 07:19:40', '2020-05-11 07:19:40', NULL, '[]'),
(171, 'DUMMY', 123, 'AS0123', 125, NULL, 0, 5645.0000, 5645.0000, 25.0000, 7056.2500, 7056.2500, '2020-05-11 07:20:15', '2020-05-11 07:20:15', NULL, '[]'),
(172, 'DUMMY', 124, 'AS0124', 126, NULL, 0, 7978.0000, 7978.0000, 25.0000, 9972.5000, 9972.5000, '2020-05-11 07:20:50', '2020-05-11 07:20:50', NULL, '[]'),
(173, 'DUMMY', 125, 'AS0125', 127, NULL, 0, 566.0000, 566.0000, 25.0000, 707.5000, 707.5000, '2020-05-11 07:21:42', '2020-05-11 07:21:42', NULL, '[]'),
(174, 'DUMMY', 126, 'AS0126', 128, NULL, 0, 899.0000, 899.0000, 25.0000, 1123.7500, 1123.7500, '2020-05-11 07:22:43', '2020-05-11 07:22:43', NULL, '[]'),
(175, 'DUMMY', 127, 'AS0127', 129, NULL, 0, 900.0000, 900.0000, 25.0000, 1125.0000, 1125.0000, '2020-05-11 07:23:29', '2020-05-11 07:23:29', NULL, '[]'),
(176, 'DUMMY', 128, 'AS0128', 130, NULL, 0, 897.0000, 897.0000, 9.0000, 977.7300, 977.7300, '2020-05-11 07:24:57', '2020-05-11 07:24:57', NULL, '[]'),
(177, 'DUMMY', 129, 'AS0129', 131, NULL, 0, 8978.0000, 8978.0000, 25.0000, 11222.5000, 11222.5000, '2020-05-11 07:25:41', '2020-05-11 07:25:41', NULL, '[]'),
(178, 'DUMMY', 130, 'AS0130', 132, NULL, 0, 5648.0000, 5648.0000, 25.0000, 7060.0000, 7060.0000, '2020-05-11 07:26:17', '2020-05-11 07:26:17', NULL, '[]'),
(179, 'DUMMY', 131, 'AS0131', 133, NULL, 0, 8978.0000, 8978.0000, 25.0000, 11222.5000, 11222.5000, '2020-05-11 07:27:51', '2020-05-11 07:27:51', NULL, '[]'),
(180, 'DUMMY', 132, 'AS0132', 134, NULL, 0, 5656.0000, 5656.0000, 25.0000, 7070.0000, 7070.0000, '2020-05-11 07:30:09', '2020-05-11 07:30:09', NULL, '[]'),
(181, 'DUMMY', 133, 'AS0133', 135, NULL, 0, 900.0000, 900.0000, 25.0000, 1125.0000, 1125.0000, '2020-05-11 07:31:14', '2020-05-11 07:31:14', NULL, '[]'),
(182, 'DUMMY', 134, 'AS0134', 136, NULL, 0, 897.0000, 897.0000, 25.0000, 1121.2500, 1121.2500, '2020-05-11 07:32:01', '2020-05-11 07:32:01', NULL, '[]'),
(183, 'DUMMY', 135, 'AS0135', 137, NULL, 0, 10000.0000, 10000.0000, 25.0000, 12500.0000, 12500.0000, '2020-05-11 07:34:38', '2020-05-11 07:34:38', NULL, '[]'),
(184, 'DUMMY', 136, 'AS0136', 138, NULL, 0, 56464.0000, 56464.0000, 25.0000, 70580.0000, 70580.0000, '2020-05-11 07:35:40', '2020-05-11 07:35:40', NULL, '[]'),
(185, 'DUMMY', 137, 'AS0137', 139, NULL, 0, 898.0000, 898.0000, 25.0000, 1122.5000, 1122.5000, '2020-05-11 07:36:25', '2020-05-11 07:36:25', NULL, '[]'),
(186, 'DUMMY', 138, 'AS0138', 140, NULL, 0, 8978.0000, 8978.0000, 25.0000, 11222.5000, 11222.5000, '2020-05-11 07:37:28', '2020-05-11 07:37:28', NULL, '[]'),
(187, 'DUMMY', 139, 'AS0139', 141, NULL, 0, 8998.0000, 8998.0000, 25.0000, 11247.5000, 11247.5000, '2020-05-11 07:38:24', '2020-05-11 07:38:24', NULL, '[]'),
(188, 'DUMMY', 140, 'AS0140', 142, NULL, 0, 2000.0000, 2000.0000, 25.0000, 2500.0000, 2500.0000, '2020-05-11 07:39:16', '2020-05-11 07:39:16', NULL, '[]'),
(189, 'DUMMY', 141, 'AS0141', 143, NULL, 0, 89747.0000, 89747.0000, 25.0000, 112183.7500, 112183.7500, '2020-05-11 07:40:55', '2020-05-11 07:40:55', NULL, '[]'),
(190, 'DUMMY', 142, 'AS0142', 144, NULL, 0, 89748.0000, 89748.0000, 25.0000, 112185.0000, 112185.0000, '2020-05-11 07:42:14', '2020-05-11 07:42:14', NULL, '[]'),
(191, 'DUMMY', 143, 'AS0143', 145, NULL, 0, 6000.0000, 6000.0000, 25.0000, 7500.0000, 7500.0000, '2020-05-11 07:43:25', '2020-05-11 07:43:25', NULL, '[]'),
(192, 'DUMMY', 144, 'AS0144', 146, NULL, 0, 9000.0000, 9000.0000, 25.0000, 11250.0000, 11250.0000, '2020-05-11 07:44:10', '2020-05-11 07:44:10', NULL, '[]'),
(193, 'DUMMY', 145, 'AS0145', 147, NULL, 0, 6000.0000, 6000.0000, 25.0000, 7500.0000, 7500.0000, '2020-05-11 07:44:53', '2020-05-11 07:44:53', NULL, '[]'),
(194, 'DUMMY', 146, 'AS0146', 148, NULL, 0, 890000.0000, 890000.0000, 25.0000, 1112500.0000, 1112500.0000, '2020-05-11 07:46:18', '2020-05-11 07:46:18', NULL, '[]'),
(195, 'DUMMY', 147, 'AS0147', 149, NULL, 0, 89000.0000, 89000.0000, 25.0000, 111250.0000, 111250.0000, '2020-05-11 07:47:32', '2020-05-11 07:47:32', NULL, '[]'),
(196, 'DUMMY', 148, 'AS0148', 150, NULL, 0, 5000.0000, 5000.0000, 25.0000, 6250.0000, 6250.0000, '2020-05-11 07:48:51', '2020-05-11 07:48:51', NULL, '[]'),
(197, 'DUMMY', 149, 'AS0149', 151, NULL, 0, 5646.0000, 5646.0000, 25.0000, 7057.5000, 7057.5000, '2020-05-11 07:49:20', '2020-05-11 07:49:20', NULL, '[]'),
(198, 'DUMMY', 150, 'AS0150', 152, NULL, 0, 5646.0000, 5646.0000, 25.0000, 7057.5000, 7057.5000, '2020-05-11 07:49:45', '2020-05-11 07:49:45', NULL, '[]'),
(199, 'DUMMY', 151, 'AS0151', 153, NULL, 0, 500.0000, 500.0000, 25.0000, 625.0000, 625.0000, '2020-05-11 07:50:12', '2020-05-11 07:50:12', NULL, '[]'),
(200, 'DUMMY', 152, 'AS0152', 154, NULL, 0, 7897.0000, 7897.0000, 25.0000, 9871.2500, 9871.2500, '2020-05-11 07:51:17', '2020-05-11 07:51:17', NULL, '[]'),
(201, 'DUMMY', 153, 'AS0153', 155, NULL, 0, 600.0000, 600.0000, 25.0000, 750.0000, 750.0000, '2020-05-11 07:52:03', '2020-05-11 07:52:03', NULL, '[]'),
(202, 'DUMMY', 154, 'AS0154', 156, NULL, 0, 800.0000, 800.0000, 25.0000, 1000.0000, 1000.0000, '2020-05-11 07:52:47', '2020-05-11 07:52:47', NULL, '[]');

-- --------------------------------------------------------

--
-- Table structure for table `variation_group_prices`
--

CREATE TABLE `variation_group_prices` (
  `id` int(10) UNSIGNED NOT NULL,
  `variation_id` int(10) UNSIGNED NOT NULL,
  `price_group_id` int(10) UNSIGNED NOT NULL,
  `price_inc_tax` decimal(22,4) NOT NULL,
  `price_type` varchar(191) NOT NULL DEFAULT 'fixed',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `variation_location_details`
--

CREATE TABLE `variation_location_details` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `product_variation_id` int(10) UNSIGNED NOT NULL COMMENT 'id from product_variations table',
  `variation_id` int(10) UNSIGNED NOT NULL,
  `location_id` int(10) UNSIGNED NOT NULL,
  `qty_available` decimal(22,4) NOT NULL DEFAULT 0.0000,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `variation_location_details`
--

INSERT INTO `variation_location_details` (`id`, `product_id`, `product_variation_id`, `variation_id`, `location_id`, `qty_available`, `created_at`, `updated_at`) VALUES
(1, 2, 2, 2, 1, 50.0000, '2018-01-06 00:57:11', '2018-01-06 01:07:23'),
(2, 2, 2, 3, 1, 90.0000, '2018-01-06 00:57:11', '2018-01-06 01:07:23'),
(3, 2, 2, 4, 1, 150.0000, '2018-01-06 00:57:11', '2018-01-06 00:57:11'),
(4, 2, 2, 5, 1, 150.0000, '2018-01-06 00:57:11', '2018-01-06 00:57:11'),
(5, 2, 2, 6, 1, 100.0000, '2018-01-06 00:57:11', '2018-01-06 00:57:11'),
(6, 14, 15, 47, 1, 100.0000, '2018-01-06 00:58:10', '2018-01-06 00:58:10'),
(7, 28, 30, 71, 1, 470.0000, '2018-01-06 01:02:22', '2018-01-06 01:08:03'),
(8, 21, 23, 64, 1, 140.0000, '2018-01-06 01:03:12', '2018-01-06 01:07:45'),
(9, 27, 29, 70, 1, 470.0000, '2018-01-06 01:05:26', '2018-01-06 01:06:32'),
(10, 34, 36, 77, 2, 40.0000, '2018-04-10 04:18:16', '2018-04-10 04:23:35'),
(11, 32, 34, 75, 2, 93.0000, '2018-04-10 04:19:40', '2018-04-10 04:23:58'),
(12, 36, 38, 79, 2, 150.0000, '2018-04-10 04:20:41', '2018-04-10 04:20:41'),
(13, 33, 35, 76, 2, 153.0000, '2018-04-10 04:21:38', '2018-04-10 04:24:21'),
(14, 46, 48, 89, 3, 30.0000, '2018-04-10 05:39:54', '2018-04-10 05:39:54'),
(15, 41, 43, 84, 3, 14.0000, '2018-04-10 05:40:51', '2018-04-10 05:48:48'),
(16, 45, 47, 88, 3, 45.0000, '2018-04-10 05:41:45', '2018-04-10 05:41:45'),
(17, 44, 46, 87, 3, 80.0000, '2018-04-10 05:42:34', '2018-04-10 05:45:06'),
(18, 43, 45, 86, 3, 25.0000, '2018-04-10 05:43:12', '2018-04-10 05:44:47'),
(19, 42, 44, 85, 3, 30.0000, '2018-04-10 05:53:36', '2018-04-10 05:54:16'),
(20, 61, 63, 104, 1, 20.0000, '2018-06-11 16:17:07', '2018-06-11 16:17:07'),
(21, 64, 66, 107, 1, 300.0000, '2018-06-11 16:17:07', '2018-06-12 17:18:04'),
(22, 63, 65, 106, 1, 300.0000, '2018-06-11 16:17:07', '2018-06-12 17:18:04'),
(23, 65, 67, 108, 1, 50.0000, '2018-06-11 16:17:07', '2018-06-11 16:17:07'),
(24, 17, 19, 57, 1, 30.0000, '2018-06-12 17:18:04', '2018-06-12 17:18:04'),
(25, 17, 19, 58, 1, 50.0000, '2018-06-12 17:18:04', '2018-06-12 17:18:04'),
(26, 15, 16, 49, 1, 30.0000, '2018-06-12 17:18:04', '2018-06-12 17:18:04'),
(27, 15, 16, 50, 1, 20.0000, '2018-06-12 17:18:04', '2018-06-12 17:18:04'),
(28, 15, 16, 51, 1, 30.0000, '2018-06-12 17:18:04', '2018-06-12 17:18:04'),
(29, 15, 17, 52, 1, 20.0000, '2018-06-12 17:18:04', '2018-06-12 17:18:04'),
(30, 15, 17, 53, 1, 40.0000, '2018-06-12 17:18:04', '2018-06-12 17:18:04'),
(31, 18, 20, 59, 1, 20.0000, '2018-06-12 17:18:04', '2018-06-12 17:18:04'),
(32, 18, 20, 60, 1, 20.0000, '2018-06-12 17:18:04', '2018-06-12 17:18:04'),
(33, 19, 21, 61, 1, 30.0000, '2018-06-12 17:18:04', '2018-06-12 17:18:04'),
(34, 22, 24, 65, 1, 10.0000, '2018-06-12 17:18:04', '2018-06-12 17:18:04'),
(35, 24, 26, 67, 1, 10.0000, '2018-06-12 17:18:04', '2018-06-12 17:18:04'),
(36, 25, 27, 68, 1, 20.0000, '2018-06-12 17:18:04', '2018-06-12 17:18:04'),
(37, 77, 79, 120, 5, 100.0000, '2018-07-13 01:34:45', '2018-07-13 01:34:45'),
(38, 76, 78, 119, 5, 300.0000, '2018-07-13 01:34:45', '2018-07-13 01:38:24'),
(39, 88, 90, 136, 6, 1900.0000, '2019-08-18 13:01:33', '2019-08-18 13:09:20'),
(40, 87, 89, 135, 6, 1500.0000, '2019-08-18 13:01:49', '2019-08-18 13:09:20'),
(41, 86, 88, 134, 6, 1000.0000, '2019-08-18 13:02:07', '2019-08-18 13:02:07'),
(42, 84, 86, 132, 6, 1950.0000, '2019-08-18 13:02:20', '2019-08-18 13:09:20'),
(43, 82, 84, 130, 6, 995.0000, '2019-08-18 13:02:33', '2019-08-18 13:09:19'),
(44, 85, 87, 133, 6, 0.0000, '2019-08-18 13:02:52', '2019-08-18 13:09:20'),
(45, 83, 85, 131, 6, 1497.5000, '2019-08-18 13:03:10', '2019-08-18 13:09:20'),
(46, 81, 83, 129, 6, 5.0000, '2019-08-18 13:09:19', '2019-08-18 13:09:19'),
(47, 94, 96, 142, 1, 8.0000, '2020-05-07 15:18:22', '2020-05-07 15:18:22'),
(48, 95, 97, 143, 1, 7.0000, '2020-05-07 15:19:10', '2020-05-09 04:28:58');

-- --------------------------------------------------------

--
-- Table structure for table `variation_templates`
--

CREATE TABLE `variation_templates` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `business_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `variation_templates`
--

INSERT INTO `variation_templates` (`id`, `name`, `business_id`, `created_at`, `updated_at`) VALUES
(1, 'Size (Tshirts)', 1, '2018-01-03 20:52:13', '2018-01-03 20:52:13'),
(2, 'Size (Shoes)', 1, '2018-01-03 20:53:21', '2018-01-03 20:53:21'),
(3, 'Waist Size (Jeans)', 1, '2018-01-03 20:54:34', '2018-01-03 20:54:34'),
(4, 'Color', 1, '2018-01-06 06:42:52', '2018-01-06 06:42:52'),
(5, 'Waist Size', 1, '2026-06-28 08:37:33', '2026-06-28 08:37:33'),
(6, 'Size', 1, '2026-06-28 08:37:33', '2026-06-28 08:37:33'),
(7, 'Internal Memory', 1, '2026-06-28 08:37:33', '2026-06-28 08:37:33'),
(8, 'Storage', 1, '2026-06-28 08:37:33', '2026-06-28 08:37:33');

-- --------------------------------------------------------

--
-- Table structure for table `variation_value_templates`
--

CREATE TABLE `variation_value_templates` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `variation_template_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `variation_value_templates`
--

INSERT INTO `variation_value_templates` (`id`, `name`, `variation_template_id`, `created_at`, `updated_at`) VALUES
(1, 'S', 1, '2018-01-03 20:52:13', '2018-01-03 20:52:13'),
(2, 'M', 1, '2018-01-03 20:52:13', '2018-01-03 20:52:13'),
(3, 'L', 1, '2018-01-03 20:52:13', '2018-01-03 20:52:13'),
(4, 'XL', 1, '2018-01-03 20:52:13', '2018-01-03 20:52:13'),
(5, '5', 2, '2018-01-03 20:53:21', '2018-01-03 20:53:21'),
(6, '6', 2, '2018-01-03 20:53:21', '2018-01-03 20:53:21'),
(7, '7', 2, '2018-01-03 20:53:21', '2018-01-03 20:53:21'),
(8, '8', 2, '2018-01-03 20:53:21', '2018-01-03 20:53:21'),
(9, '9', 2, '2018-01-03 20:53:21', '2018-01-03 20:53:21'),
(10, '28', 3, '2018-01-03 20:54:34', '2018-01-03 20:54:34'),
(11, '30', 3, '2018-01-03 20:54:34', '2018-01-03 20:54:34'),
(12, '32', 3, '2018-01-03 20:54:34', '2018-01-03 20:54:34'),
(13, '34', 3, '2018-01-03 20:54:35', '2018-01-03 20:54:35'),
(14, '36', 3, '2018-01-03 20:54:35', '2018-01-03 20:54:35'),
(16, 'Black', 4, '2018-01-06 06:43:17', '2018-01-06 06:43:17'),
(17, 'Blue', 4, '2018-01-06 06:43:17', '2018-01-06 06:43:17'),
(18, 'Brown', 4, '2018-01-06 06:43:17', '2018-01-06 06:43:17'),
(19, 'Grey', 4, '2018-01-06 06:43:17', '2018-01-06 06:43:17'),
(20, 'Gold', 4, '2018-01-06 06:43:17', '2018-01-06 06:43:17'),
(21, '28', 5, '2026-06-28 08:37:33', '2026-06-28 08:37:33'),
(22, '30', 5, '2026-06-28 08:37:33', '2026-06-28 08:37:33'),
(23, '32', 5, '2026-06-28 08:37:33', '2026-06-28 08:37:33'),
(24, '34', 5, '2026-06-28 08:37:33', '2026-06-28 08:37:33'),
(25, '36', 5, '2026-06-28 08:37:33', '2026-06-28 08:37:33'),
(26, 'S', 6, '2026-06-28 08:37:33', '2026-06-28 08:37:33'),
(27, 'M', 6, '2026-06-28 08:37:33', '2026-06-28 08:37:33'),
(28, 'L', 6, '2026-06-28 08:37:33', '2026-06-28 08:37:33'),
(29, 'XL', 6, '2026-06-28 08:37:33', '2026-06-28 08:37:33'),
(30, '6', 6, '2026-06-28 08:37:33', '2026-06-28 08:37:33'),
(31, '7', 6, '2026-06-28 08:37:33', '2026-06-28 08:37:33'),
(32, '8', 6, '2026-06-28 08:37:33', '2026-06-28 08:37:33'),
(33, '9', 6, '2026-06-28 08:37:33', '2026-06-28 08:37:33'),
(34, '5', 6, '2026-06-28 08:37:33', '2026-06-28 08:37:33'),
(35, 'Gray', 4, '2026-06-28 08:37:33', '2026-06-28 08:37:33'),
(36, '64 GB', 7, '2026-06-28 08:37:33', '2026-06-28 08:37:33'),
(37, '128 GB', 7, '2026-06-28 08:37:33', '2026-06-28 08:37:33'),
(38, 'White', 4, '2026-06-28 08:37:33', '2026-06-28 08:37:33'),
(39, '32 GB', 7, '2026-06-28 08:37:33', '2026-06-28 08:37:33'),
(40, '256 GB', 8, '2026-06-28 08:37:33', '2026-06-28 08:37:33'),
(41, '500 GB', 8, '2026-06-28 08:37:33', '2026-06-28 08:37:33');

-- --------------------------------------------------------

--
-- Table structure for table `warranties`
--

CREATE TABLE `warranties` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `business_id` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `duration` int(11) NOT NULL,
  `duration_type` enum('days','months','years') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `accounts_business_id_index` (`business_id`),
  ADD KEY `accounts_account_type_id_index` (`account_type_id`),
  ADD KEY `accounts_created_by_index` (`created_by`);

--
-- Indexes for table `account_transactions`
--
ALTER TABLE `account_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_transactions_account_id_index` (`account_id`),
  ADD KEY `account_transactions_transaction_id_index` (`transaction_id`),
  ADD KEY `account_transactions_transaction_payment_id_index` (`transaction_payment_id`),
  ADD KEY `account_transactions_transfer_transaction_id_index` (`transfer_transaction_id`),
  ADD KEY `account_transactions_created_by_index` (`created_by`),
  ADD KEY `account_transactions_type_index` (`type`),
  ADD KEY `account_transactions_sub_type_index` (`sub_type`),
  ADD KEY `account_transactions_operation_date_index` (`operation_date`);

--
-- Indexes for table `account_types`
--
ALTER TABLE `account_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_types_parent_account_type_id_index` (`parent_account_type_id`),
  ADD KEY `account_types_business_id_index` (`business_id`);

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_log_log_name_index` (`log_name`);

--
-- Indexes for table `barcodes`
--
ALTER TABLE `barcodes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `barcodes_business_id_foreign` (`business_id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bookings_contact_id_foreign` (`contact_id`),
  ADD KEY `bookings_business_id_foreign` (`business_id`),
  ADD KEY `bookings_created_by_foreign` (`created_by`),
  ADD KEY `bookings_table_id_index` (`table_id`),
  ADD KEY `bookings_waiter_id_index` (`waiter_id`),
  ADD KEY `bookings_location_id_index` (`location_id`),
  ADD KEY `bookings_booking_status_index` (`booking_status`),
  ADD KEY `bookings_correspondent_id_index` (`correspondent_id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD KEY `brands_business_id_foreign` (`business_id`),
  ADD KEY `brands_created_by_foreign` (`created_by`);

--
-- Indexes for table `business`
--
ALTER TABLE `business`
  ADD PRIMARY KEY (`id`),
  ADD KEY `business_owner_id_foreign` (`owner_id`),
  ADD KEY `business_currency_id_foreign` (`currency_id`),
  ADD KEY `business_default_sales_tax_foreign` (`default_sales_tax`);

--
-- Indexes for table `business_locations`
--
ALTER TABLE `business_locations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `business_locations_business_id_index` (`business_id`),
  ADD KEY `business_locations_invoice_scheme_id_foreign` (`invoice_scheme_id`),
  ADD KEY `business_locations_invoice_layout_id_foreign` (`invoice_layout_id`),
  ADD KEY `business_locations_sale_invoice_layout_id_index` (`sale_invoice_layout_id`),
  ADD KEY `business_locations_selling_price_group_id_index` (`selling_price_group_id`),
  ADD KEY `business_locations_receipt_printer_type_index` (`receipt_printer_type`),
  ADD KEY `business_locations_printer_id_index` (`printer_id`);

--
-- Indexes for table `cash_denominations`
--
ALTER TABLE `cash_denominations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cash_denominations_model_type_model_id_index` (`model_type`,`model_id`);

--
-- Indexes for table `cash_registers`
--
ALTER TABLE `cash_registers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cash_registers_business_id_foreign` (`business_id`),
  ADD KEY `cash_registers_user_id_foreign` (`user_id`),
  ADD KEY `cash_registers_location_id_index` (`location_id`);

--
-- Indexes for table `cash_register_transactions`
--
ALTER TABLE `cash_register_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cash_register_transactions_cash_register_id_foreign` (`cash_register_id`),
  ADD KEY `cash_register_transactions_transaction_id_index` (`transaction_id`),
  ADD KEY `cash_register_transactions_type_index` (`type`),
  ADD KEY `cash_register_transactions_transaction_type_index` (`transaction_type`),
  ADD KEY `cash_register_transactions_transaction_payment_id_foreign` (`transaction_payment_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categories_business_id_foreign` (`business_id`),
  ADD KEY `categories_created_by_foreign` (`created_by`),
  ADD KEY `categories_parent_id_index` (`parent_id`);

--
-- Indexes for table `categorizables`
--
ALTER TABLE `categorizables`
  ADD KEY `categorizables_categorizable_type_categorizable_id_index` (`categorizable_type`,`categorizable_id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contacts_business_id_foreign` (`business_id`),
  ADD KEY `contacts_created_by_foreign` (`created_by`),
  ADD KEY `contacts_type_index` (`type`),
  ADD KEY `contacts_contact_status_index` (`contact_status`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_groups`
--
ALTER TABLE `customer_groups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_groups_business_id_foreign` (`business_id`),
  ADD KEY `customer_groups_created_by_index` (`created_by`),
  ADD KEY `customer_groups_price_calculation_type_index` (`price_calculation_type`),
  ADD KEY `customer_groups_selling_price_group_id_index` (`selling_price_group_id`);

--
-- Indexes for table `dashboard_configurations`
--
ALTER TABLE `dashboard_configurations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dashboard_configurations_business_id_foreign` (`business_id`);

--
-- Indexes for table `discounts`
--
ALTER TABLE `discounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `discounts_business_id_index` (`business_id`),
  ADD KEY `discounts_brand_id_index` (`brand_id`),
  ADD KEY `discounts_category_id_index` (`category_id`),
  ADD KEY `discounts_location_id_index` (`location_id`),
  ADD KEY `discounts_priority_index` (`priority`),
  ADD KEY `discounts_spg_index` (`spg`);

--
-- Indexes for table `discount_variations`
--
ALTER TABLE `discount_variations`
  ADD KEY `discount_variations_discount_id_index` (`discount_id`),
  ADD KEY `discount_variations_variation_id_index` (`variation_id`);

--
-- Indexes for table `document_and_notes`
--
ALTER TABLE `document_and_notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `document_and_notes_business_id_index` (`business_id`),
  ADD KEY `document_and_notes_notable_id_index` (`notable_id`),
  ADD KEY `document_and_notes_created_by_index` (`created_by`);

--
-- Indexes for table `essentials_leave_types`
--
ALTER TABLE `essentials_leave_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `essentials_leave_types_business_id_foreign` (`business_id`);

--
-- Indexes for table `essentials_shifts`
--
ALTER TABLE `essentials_shifts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `essentials_shifts_business_id_foreign` (`business_id`);

--
-- Indexes for table `essentials_user_shifts`
--
ALTER TABLE `essentials_user_shifts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `essentials_user_shifts_essentials_shift_id_foreign` (`essentials_shift_id`),
  ADD KEY `essentials_user_shifts_user_id_essentials_shift_id_index` (`user_id`,`essentials_shift_id`);

--
-- Indexes for table `expense_categories`
--
ALTER TABLE `expense_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expense_categories_business_id_foreign` (`business_id`);

--
-- Indexes for table `group_sub_taxes`
--
ALTER TABLE `group_sub_taxes`
  ADD KEY `group_sub_taxes_group_tax_id_foreign` (`group_tax_id`),
  ADD KEY `group_sub_taxes_tax_id_foreign` (`tax_id`);

--
-- Indexes for table `gym_classes`
--
ALTER TABLE `gym_classes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gym_classes_business_id_index` (`business_id`);

--
-- Indexes for table `gym_packages`
--
ALTER TABLE `gym_packages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gym_packages_created_by_foreign` (`created_by`),
  ADD KEY `gym_packages_business_id_index` (`business_id`);

--
-- Indexes for table `hms_booking_extras`
--
ALTER TABLE `hms_booking_extras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hms_booking_extras_transaction_id_foreign` (`transaction_id`),
  ADD KEY `hms_booking_extras_hms_extra_id_foreign` (`hms_extra_id`);

--
-- Indexes for table `hms_booking_lines`
--
ALTER TABLE `hms_booking_lines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hms_booking_lines_transaction_id_foreign` (`transaction_id`),
  ADD KEY `hms_booking_lines_hms_room_type_id_foreign` (`hms_room_type_id`),
  ADD KEY `hms_booking_lines_hms_room_id_foreign` (`hms_room_id`);

--
-- Indexes for table `hms_coupons`
--
ALTER TABLE `hms_coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hms_coupons_coupon_code_unique` (`coupon_code`),
  ADD KEY `hms_coupons_business_id_foreign` (`business_id`),
  ADD KEY `hms_coupons_hms_room_type_id_foreign` (`hms_room_type_id`);

--
-- Indexes for table `hms_extras`
--
ALTER TABLE `hms_extras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hms_extras_business_id_foreign` (`business_id`),
  ADD KEY `hms_extras_created_by_foreign` (`created_by`);

--
-- Indexes for table `hms_rooms`
--
ALTER TABLE `hms_rooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hms_rooms_hms_room_type_id_foreign` (`hms_room_type_id`);

--
-- Indexes for table `hms_room_types`
--
ALTER TABLE `hms_room_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hms_room_types_business_id_foreign` (`business_id`),
  ADD KEY `hms_room_types_created_by_foreign` (`created_by`);

--
-- Indexes for table `hms_room_type_pricings`
--
ALTER TABLE `hms_room_type_pricings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hms_room_type_pricings_hms_room_type_id_foreign` (`hms_room_type_id`);

--
-- Indexes for table `invoice_layouts`
--
ALTER TABLE `invoice_layouts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_layouts_business_id_foreign` (`business_id`);

--
-- Indexes for table `invoice_schemes`
--
ALTER TABLE `invoice_schemes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_schemes_business_id_foreign` (`business_id`),
  ADD KEY `invoice_schemes_scheme_type_index` (`scheme_type`),
  ADD KEY `invoice_schemes_number_type_index` (`number_type`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `media_model_type_model_id_index` (`model_type`,`model_id`),
  ADD KEY `media_business_id_index` (`business_id`),
  ADD KEY `media_uploaded_by_index` (`uploaded_by`);

--
-- Indexes for table `mfg_recipes`
--
ALTER TABLE `mfg_recipes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mfg_recipes_product_id_foreign` (`product_id`),
  ADD KEY `mfg_recipes_variation_id_foreign` (`variation_id`);

--
-- Indexes for table `mfg_recipe_ingredients`
--
ALTER TABLE `mfg_recipe_ingredients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mfg_recipe_ingredients_mfg_recipe_id_foreign` (`mfg_recipe_id`),
  ADD KEY `mfg_recipe_ingredients_variation_id_foreign` (`variation_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_type_model_id_index` (`model_type`,`model_id`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_type_model_id_index` (`model_type`,`model_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `notification_templates`
--
ALTER TABLE `notification_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_access_tokens`
--
ALTER TABLE `oauth_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_access_tokens_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_auth_codes`
--
ALTER TABLE `oauth_auth_codes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_clients_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_personal_access_clients_client_id_index` (`client_id`);

--
-- Indexes for table `oauth_refresh_tokens`
--
ALTER TABLE `oauth_refresh_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `printers`
--
ALTER TABLE `printers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `printers_business_id_foreign` (`business_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_brand_id_foreign` (`brand_id`),
  ADD KEY `products_category_id_foreign` (`category_id`),
  ADD KEY `products_sub_category_id_foreign` (`sub_category_id`),
  ADD KEY `products_tax_foreign` (`tax`),
  ADD KEY `products_name_index` (`name`),
  ADD KEY `products_business_id_index` (`business_id`),
  ADD KEY `products_unit_id_index` (`unit_id`),
  ADD KEY `products_created_by_index` (`created_by`),
  ADD KEY `products_warranty_id_index` (`warranty_id`),
  ADD KEY `products_type_index` (`type`),
  ADD KEY `products_tax_type_index` (`tax_type`),
  ADD KEY `products_barcode_type_index` (`barcode_type`),
  ADD KEY `products_secondary_unit_id_index` (`secondary_unit_id`);

--
-- Indexes for table `product_locations`
--
ALTER TABLE `product_locations`
  ADD KEY `product_locations_product_id_index` (`product_id`),
  ADD KEY `product_locations_location_id_index` (`location_id`);

--
-- Indexes for table `product_racks`
--
ALTER TABLE `product_racks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_racks_business_id_index` (`business_id`),
  ADD KEY `product_racks_location_id_index` (`location_id`),
  ADD KEY `product_racks_product_id_index` (`product_id`);

--
-- Indexes for table `product_variations`
--
ALTER TABLE `product_variations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_variations_name_index` (`name`),
  ADD KEY `product_variations_product_id_index` (`product_id`);

--
-- Indexes for table `purchase_lines`
--
ALTER TABLE `purchase_lines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_lines_transaction_id_foreign` (`transaction_id`),
  ADD KEY `purchase_lines_product_id_foreign` (`product_id`),
  ADD KEY `purchase_lines_variation_id_foreign` (`variation_id`),
  ADD KEY `purchase_lines_tax_id_foreign` (`tax_id`),
  ADD KEY `purchase_lines_sub_unit_id_index` (`sub_unit_id`),
  ADD KEY `purchase_lines_lot_number_index` (`lot_number`);

--
-- Indexes for table `reference_counts`
--
ALTER TABLE `reference_counts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reference_counts_business_id_index` (`business_id`);

--
-- Indexes for table `repair_device_models`
--
ALTER TABLE `repair_device_models`
  ADD PRIMARY KEY (`id`),
  ADD KEY `repair_device_models_business_id_foreign` (`business_id`),
  ADD KEY `repair_device_models_device_id_foreign` (`device_id`),
  ADD KEY `repair_device_models_brand_id_foreign` (`brand_id`),
  ADD KEY `repair_device_models_created_by_foreign` (`created_by`);

--
-- Indexes for table `repair_statuses`
--
ALTER TABLE `repair_statuses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `repair_statuses_business_id_foreign` (`business_id`);

--
-- Indexes for table `res_product_modifier_sets`
--
ALTER TABLE `res_product_modifier_sets`
  ADD KEY `res_product_modifier_sets_modifier_set_id_foreign` (`modifier_set_id`);

--
-- Indexes for table `res_tables`
--
ALTER TABLE `res_tables`
  ADD PRIMARY KEY (`id`),
  ADD KEY `res_tables_business_id_foreign` (`business_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `roles_business_id_foreign` (`business_id`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `selling_price_groups`
--
ALTER TABLE `selling_price_groups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `selling_price_groups_business_id_foreign` (`business_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD UNIQUE KEY `sessions_id_unique` (`id`);

--
-- Indexes for table `stock_adjustment_lines`
--
ALTER TABLE `stock_adjustment_lines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_adjustment_lines_product_id_foreign` (`product_id`),
  ADD KEY `stock_adjustment_lines_variation_id_foreign` (`variation_id`),
  ADD KEY `stock_adjustment_lines_transaction_id_index` (`transaction_id`),
  ADD KEY `stock_adjustment_lines_lot_no_line_id_index` (`lot_no_line_id`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subscriptions_business_id_foreign` (`business_id`),
  ADD KEY `subscriptions_package_id_index` (`package_id`),
  ADD KEY `subscriptions_created_id_index` (`created_id`);

--
-- Indexes for table `superadmin_communicator_logs`
--
ALTER TABLE `superadmin_communicator_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `superadmin_coupons`
--
ALTER TABLE `superadmin_coupons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `superadmin_frontend_pages`
--
ALTER TABLE `superadmin_frontend_pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system`
--
ALTER TABLE `system`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tax_rates`
--
ALTER TABLE `tax_rates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tax_rates_business_id_foreign` (`business_id`),
  ADD KEY `tax_rates_created_by_foreign` (`created_by`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transactions_tax_id_foreign` (`tax_id`),
  ADD KEY `transactions_business_id_index` (`business_id`),
  ADD KEY `transactions_type_index` (`type`),
  ADD KEY `transactions_contact_id_index` (`contact_id`),
  ADD KEY `transactions_transaction_date_index` (`transaction_date`),
  ADD KEY `transactions_created_by_index` (`created_by`),
  ADD KEY `transactions_location_id_index` (`location_id`),
  ADD KEY `transactions_expense_for_foreign` (`expense_for`),
  ADD KEY `transactions_expense_category_id_index` (`expense_category_id`),
  ADD KEY `transactions_sub_type_index` (`sub_type`),
  ADD KEY `transactions_return_parent_id_index` (`return_parent_id`),
  ADD KEY `type` (`type`),
  ADD KEY `transactions_status_index` (`status`),
  ADD KEY `transactions_sub_status_index` (`sub_status`),
  ADD KEY `transactions_res_table_id_index` (`res_table_id`),
  ADD KEY `transactions_res_waiter_id_index` (`res_waiter_id`),
  ADD KEY `transactions_res_order_status_index` (`res_order_status`),
  ADD KEY `transactions_payment_status_index` (`payment_status`),
  ADD KEY `transactions_discount_type_index` (`discount_type`),
  ADD KEY `transactions_commission_agent_index` (`commission_agent`),
  ADD KEY `transactions_transfer_parent_id_index` (`transfer_parent_id`),
  ADD KEY `transactions_types_of_service_id_index` (`types_of_service_id`),
  ADD KEY `transactions_packing_charge_type_index` (`packing_charge_type`),
  ADD KEY `transactions_recur_parent_id_index` (`recur_parent_id`),
  ADD KEY `transactions_selling_price_group_id_index` (`selling_price_group_id`),
  ADD KEY `transactions_delivery_date_index` (`delivery_date`),
  ADD KEY `transactions_delivery_person_index` (`delivery_person`),
  ADD KEY `transactions_hms_coupon_id_foreign` (`hms_coupon_id`);

--
-- Indexes for table `transaction_payments`
--
ALTER TABLE `transaction_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_payments_transaction_id_foreign` (`transaction_id`),
  ADD KEY `transaction_payments_created_by_index` (`created_by`),
  ADD KEY `transaction_payments_parent_id_index` (`parent_id`),
  ADD KEY `transaction_payments_payment_type_index` (`payment_type`);

--
-- Indexes for table `transaction_sell_lines`
--
ALTER TABLE `transaction_sell_lines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_sell_lines_transaction_id_foreign` (`transaction_id`),
  ADD KEY `transaction_sell_lines_product_id_foreign` (`product_id`),
  ADD KEY `transaction_sell_lines_variation_id_foreign` (`variation_id`),
  ADD KEY `transaction_sell_lines_tax_id_foreign` (`tax_id`),
  ADD KEY `transaction_sell_lines_children_type_index` (`children_type`),
  ADD KEY `transaction_sell_lines_parent_sell_line_id_index` (`parent_sell_line_id`),
  ADD KEY `transaction_sell_lines_line_discount_type_index` (`line_discount_type`),
  ADD KEY `transaction_sell_lines_discount_id_index` (`discount_id`),
  ADD KEY `transaction_sell_lines_lot_no_line_id_index` (`lot_no_line_id`),
  ADD KEY `transaction_sell_lines_sub_unit_id_index` (`sub_unit_id`);

--
-- Indexes for table `transaction_sell_lines_purchase_lines`
--
ALTER TABLE `transaction_sell_lines_purchase_lines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sell_line_id` (`sell_line_id`),
  ADD KEY `stock_adjustment_line_id` (`stock_adjustment_line_id`),
  ADD KEY `purchase_line_id` (`purchase_line_id`);

--
-- Indexes for table `types_of_services`
--
ALTER TABLE `types_of_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `types_of_services_business_id_index` (`business_id`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`),
  ADD KEY `units_business_id_foreign` (`business_id`),
  ADD KEY `units_created_by_foreign` (`created_by`),
  ADD KEY `units_base_unit_id_index` (`base_unit_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD KEY `users_business_id_foreign` (`business_id`),
  ADD KEY `users_user_type_index` (`user_type`),
  ADD KEY `users_crm_contact_id_foreign` (`crm_contact_id`);

--
-- Indexes for table `user_contact_access`
--
ALTER TABLE `user_contact_access`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_contact_access_user_id_index` (`user_id`),
  ADD KEY `user_contact_access_contact_id_index` (`contact_id`);

--
-- Indexes for table `variations`
--
ALTER TABLE `variations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `variations_product_id_foreign` (`product_id`),
  ADD KEY `variations_product_variation_id_foreign` (`product_variation_id`),
  ADD KEY `variations_name_index` (`name`),
  ADD KEY `variations_sub_sku_index` (`sub_sku`),
  ADD KEY `variations_variation_value_id_index` (`variation_value_id`);

--
-- Indexes for table `variation_group_prices`
--
ALTER TABLE `variation_group_prices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `variation_group_prices_variation_id_foreign` (`variation_id`),
  ADD KEY `variation_group_prices_price_group_id_foreign` (`price_group_id`);

--
-- Indexes for table `variation_location_details`
--
ALTER TABLE `variation_location_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `variation_location_details_location_id_foreign` (`location_id`),
  ADD KEY `variation_location_details_product_id_index` (`product_id`),
  ADD KEY `variation_location_details_product_variation_id_index` (`product_variation_id`),
  ADD KEY `variation_location_details_variation_id_index` (`variation_id`);

--
-- Indexes for table `variation_templates`
--
ALTER TABLE `variation_templates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `variation_templates_business_id_foreign` (`business_id`);

--
-- Indexes for table `variation_value_templates`
--
ALTER TABLE `variation_value_templates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `variation_value_templates_name_index` (`name`),
  ADD KEY `variation_value_templates_variation_template_id_index` (`variation_template_id`);

--
-- Indexes for table `warranties`
--
ALTER TABLE `warranties`
  ADD PRIMARY KEY (`id`),
  ADD KEY `warranties_business_id_index` (`business_id`),
  ADD KEY `warranties_duration_type_index` (`duration_type`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `account_transactions`
--
ALTER TABLE `account_transactions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `account_types`
--
ALTER TABLE `account_types`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `barcodes`
--
ALTER TABLE `barcodes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `business`
--
ALTER TABLE `business`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `business_locations`
--
ALTER TABLE `business_locations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `cash_denominations`
--
ALTER TABLE `cash_denominations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cash_registers`
--
ALTER TABLE `cash_registers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cash_register_transactions`
--
ALTER TABLE `cash_register_transactions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=143;

--
-- AUTO_INCREMENT for table `customer_groups`
--
ALTER TABLE `customer_groups`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dashboard_configurations`
--
ALTER TABLE `dashboard_configurations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `discounts`
--
ALTER TABLE `discounts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `document_and_notes`
--
ALTER TABLE `document_and_notes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `essentials_leave_types`
--
ALTER TABLE `essentials_leave_types`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `essentials_shifts`
--
ALTER TABLE `essentials_shifts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `essentials_user_shifts`
--
ALTER TABLE `essentials_user_shifts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `expense_categories`
--
ALTER TABLE `expense_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gym_classes`
--
ALTER TABLE `gym_classes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `gym_packages`
--
ALTER TABLE `gym_packages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `hms_booking_extras`
--
ALTER TABLE `hms_booking_extras`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hms_booking_lines`
--
ALTER TABLE `hms_booking_lines`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hms_coupons`
--
ALTER TABLE `hms_coupons`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hms_extras`
--
ALTER TABLE `hms_extras`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `hms_rooms`
--
ALTER TABLE `hms_rooms`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hms_room_types`
--
ALTER TABLE `hms_room_types`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `hms_room_type_pricings`
--
ALTER TABLE `hms_room_type_pricings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `invoice_layouts`
--
ALTER TABLE `invoice_layouts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `invoice_schemes`
--
ALTER TABLE `invoice_schemes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mfg_recipes`
--
ALTER TABLE `mfg_recipes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `mfg_recipe_ingredients`
--
ALTER TABLE `mfg_recipe_ingredients`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=346;

--
-- AUTO_INCREMENT for table `notification_templates`
--
ALTER TABLE `notification_templates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `printers`
--
ALTER TABLE `printers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=155;

--
-- AUTO_INCREMENT for table `product_racks`
--
ALTER TABLE `product_racks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_variations`
--
ALTER TABLE `product_variations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=157;

--
-- AUTO_INCREMENT for table `purchase_lines`
--
ALTER TABLE `purchase_lines`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `reference_counts`
--
ALTER TABLE `reference_counts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `repair_device_models`
--
ALTER TABLE `repair_device_models`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `repair_statuses`
--
ALTER TABLE `repair_statuses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `res_tables`
--
ALTER TABLE `res_tables`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `selling_price_groups`
--
ALTER TABLE `selling_price_groups`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_adjustment_lines`
--
ALTER TABLE `stock_adjustment_lines`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `superadmin_communicator_logs`
--
ALTER TABLE `superadmin_communicator_logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `superadmin_coupons`
--
ALTER TABLE `superadmin_coupons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `superadmin_frontend_pages`
--
ALTER TABLE `superadmin_frontend_pages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `system`
--
ALTER TABLE `system`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tax_rates`
--
ALTER TABLE `tax_rates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `transaction_payments`
--
ALTER TABLE `transaction_payments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `transaction_sell_lines`
--
ALTER TABLE `transaction_sell_lines`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `transaction_sell_lines_purchase_lines`
--
ALTER TABLE `transaction_sell_lines_purchase_lines`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `types_of_services`
--
ALTER TABLE `types_of_services`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `user_contact_access`
--
ALTER TABLE `user_contact_access`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `variations`
--
ALTER TABLE `variations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=203;

--
-- AUTO_INCREMENT for table `variation_group_prices`
--
ALTER TABLE `variation_group_prices`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `variation_location_details`
--
ALTER TABLE `variation_location_details`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `variation_templates`
--
ALTER TABLE `variation_templates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `variation_value_templates`
--
ALTER TABLE `variation_value_templates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `warranties`
--
ALTER TABLE `warranties`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `barcodes`
--
ALTER TABLE `barcodes`
  ADD CONSTRAINT `barcodes_business_id_foreign` FOREIGN KEY (`business_id`) REFERENCES `business` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_business_id_foreign` FOREIGN KEY (`business_id`) REFERENCES `business` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_contact_id_foreign` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `brands`
--
ALTER TABLE `brands`
  ADD CONSTRAINT `brands_business_id_foreign` FOREIGN KEY (`business_id`) REFERENCES `business` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `brands_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `business`
--
ALTER TABLE `business`
  ADD CONSTRAINT `business_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`),
  ADD CONSTRAINT `business_default_sales_tax_foreign` FOREIGN KEY (`default_sales_tax`) REFERENCES `tax_rates` (`id`),
  ADD CONSTRAINT `business_owner_id_foreign` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `business_locations`
--
ALTER TABLE `business_locations`
  ADD CONSTRAINT `business_locations_business_id_foreign` FOREIGN KEY (`business_id`) REFERENCES `business` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `business_locations_invoice_layout_id_foreign` FOREIGN KEY (`invoice_layout_id`) REFERENCES `invoice_layouts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `business_locations_invoice_scheme_id_foreign` FOREIGN KEY (`invoice_scheme_id`) REFERENCES `invoice_schemes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cash_registers`
--
ALTER TABLE `cash_registers`
  ADD CONSTRAINT `cash_registers_business_id_foreign` FOREIGN KEY (`business_id`) REFERENCES `business` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cash_registers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_business_id_foreign` FOREIGN KEY (`business_id`) REFERENCES `business` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `categories_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_business_id_foreign` FOREIGN KEY (`business_id`) REFERENCES `business` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
