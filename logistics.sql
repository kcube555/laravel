-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 31, 2020 at 05:24 PM
-- Server version: 10.1.28-MariaDB
-- PHP Version: 7.1.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `logistics`
--

-- --------------------------------------------------------

--
-- Table structure for table `bills`
--

CREATE TABLE `bills` (
  `id` int(11) NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `sub_type` varchar(255) DEFAULT NULL,
  `number` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `party_id` int(11) DEFAULT NULL,
  `validity` date DEFAULT NULL,
  `invoice_value` decimal(15,2) NOT NULL DEFAULT '0.00',
  `terms` text,
  `remarks` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bills`
--

INSERT INTO `bills` (`id`, `type`, `sub_type`, `number`, `date`, `party_id`, `validity`, `invoice_value`, `terms`, `remarks`, `created_at`, `updated_at`, `user_id`) VALUES
(6, 'Invoice', NULL, 'INV/20-21/0006', '2021-01-02', 1, '2021-01-03', '11800.00', 'the invoice generate\nas per government rules.\nin incase payment is fail\nthen company us not responsible for it.', 'test remarks for the invoice\n4 qty missing that will be generate\nin credit notes', '2020-12-28 12:24:27', '2020-12-28 12:36:39', 1),
(7, 'Invoice', NULL, 'Generate After Save', '2021-01-01', 1, '2006-06-20', '0.00', NULL, NULL, '2020-12-29 10:54:14', '2020-12-29 10:54:49', 1),
(8, 'Invoice', NULL, 'INV/20-21/0008', '2020-12-25', 3, '2006-06-29', '0.00', 'AA', 'AA', '2020-12-29 10:55:01', '2020-12-31 10:14:27', 1);

-- --------------------------------------------------------

--
-- Table structure for table `bill_items`
--

CREATE TABLE `bill_items` (
  `id` int(11) NOT NULL,
  `bill_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `quentity` decimal(15,2) NOT NULL DEFAULT '0.00',
  `gst_per` decimal(15,2) NOT NULL DEFAULT '0.00',
  `gst_amt` decimal(15,2) NOT NULL DEFAULT '0.00',
  `disc_per` decimal(15,2) NOT NULL DEFAULT '0.00',
  `disc_amt` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(15,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bill_items`
--

INSERT INTO `bill_items` (`id`, `bill_id`, `item_id`, `price`, `quentity`, `gst_per`, `gst_amt`, `disc_per`, `disc_amt`, `total_amount`) VALUES
(61, 5, 3, '1000.00', '15.00', '0.00', '0.00', '0.00', '0.00', '15000.00'),
(62, 6, 3, '1000.00', '10.00', '18.00', '1800.00', '0.00', '0.00', '11800.00'),
(63, 8, 4, '400.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00');

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` int(11) NOT NULL,
  `code` char(3) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile` varchar(10) NOT NULL,
  `pan` varchar(255) NOT NULL,
  `gstin` varchar(255) NOT NULL,
  `address` text,
  `state_id` int(11) NOT NULL,
  `logo` text,
  `signature` text,
  `letterhead` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `code`, `name`, `email`, `mobile`, `pan`, `gstin`, `address`, `state_id`, `logo`, `signature`, `letterhead`, `created_at`, `updated_at`) VALUES
(1, 'PR', 'perfect', 'kanjikangad63@gmail.com', '9879782739', '123456879', '12345789', 'Kutch (Gandhidham)', 2, '5fedf90c2aed8.png', '5feb6144eef2a.png', '5fe8ac8cedf18.sql', '2020-12-20 13:56:58', '2020-12-31 10:45:08'),
(2, 'AA', 'kanji', 'aaa@ga.vc', '0', '13', 'jhk', 'aaa', 1, NULL, NULL, NULL, '2020-12-20 11:13:53', '2020-12-20 11:13:53');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `rate` decimal(15,2) NOT NULL,
  `gst` decimal(15,2) NOT NULL DEFAULT '0.00',
  `hsn_code` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `name`, `category`, `rate`, `gst`, `hsn_code`, `created_at`, `updated_at`) VALUES
(3, 'Test', 'test', '1000.00', '0.00', '50000', '2020-12-13 00:20:43', '2020-12-13 00:37:38'),
(4, 'Test 2', 'Wood', '400.00', '18.00', '500100', '2020-12-13 00:39:48', '2020-12-20 11:35:22');

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ordered` int(11) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `parent_id`, `title`, `url`, `icon`, `ordered`, `created_at`, `updated_at`) VALUES
(1, 0, 'Dashboard', 'dashboard', 'fa fa-tachometer', 1, '2020-11-07 12:09:59', '2020-11-07 12:09:59'),
(2, 0, 'Master', NULL, 'fa fa-arrows-h', 2, '2020-11-07 12:29:12', '2020-11-07 12:29:12'),
(3, 2, 'Party', 'Master/Parties', 'fa fa-user-circle-o', 3, '2020-11-07 12:31:43', '2020-11-07 12:31:43'),
(4, 2, 'Vehicle', 'Master/Vehicles', 'fa fa-truck', 4, '2020-11-07 12:31:43', '2020-11-07 12:31:43'),
(5, 2, 'Item', 'Master/Items', 'fa fa-object-group', 5, '2020-11-07 12:31:43', '2020-11-07 12:31:43'),
(6, 0, 'Account', NULL, 'fa fa-arrows-h', 2, '2020-11-07 12:29:12', '2020-11-07 12:29:12'),
(7, 6, 'Bills', 'Account/Bills', 'fa fa-inr', 5, '2020-11-07 12:31:43', '2020-11-07 12:31:43'),
(8, 2, 'Companies', 'Master/Companies', 'fa fa-object-group', 5, '2020-11-07 12:31:43', '2020-11-07 12:31:43');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(5, '2020_11_07_143536_add_icon_to_menus_table', 3),
(6, '2020_11_07_144128_alter_munus_table_add_icon', 3),
(8, '2019_08_19_000000_create_failed_jobs_table', 4),
(9, '2020_11_07_141842_create_menus_table', 4);

-- --------------------------------------------------------

--
-- Table structure for table `parties`
--

CREATE TABLE `parties` (
  `id` int(11) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `address` text,
  `city` varchar(45) DEFAULT NULL,
  `pincode` int(6) DEFAULT NULL,
  `mobile` varchar(10) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `created_at` varchar(45) DEFAULT NULL,
  `updated_at` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `parties`
--

INSERT INTO `parties` (`id`, `code`, `name`, `address`, `city`, `pincode`, `mobile`, `email`, `created_at`, `updated_at`) VALUES
(1, '100', 'Kanji', 'Valadiya\nTest test\nAAA', 'Anjar', 370110, '9879782739', 'kanjikangad63@gmail.com', '2020-11-12 16:31:11', '2020-12-27 06:02:23'),
(2, '200', 'Shanti', 'Opp Khodiyar Temple,\nKangad Faliyu\nNagavaladiya\nAnjar 370110 Kutch\nBHUJ', 'Anjar', 370110, '7016187149', 'kanjikangad63@gmail.com', '', '2020-12-26 19:40:49'),
(3, '501010', 'Kanji Kangag', 'Valadiya assssssssssssssssssssssssssssssssssssssssssssssssssss', 'Anjar', 370110, '9879782739', 'kanjikangad63@gmail.com', '2020-11-12 16:31:11', '2020-12-27 06:01:46');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'kanji', 'kanjikangad63@gmail.com', NULL, '$2y$10$b.hDDKa02FcbxqURJvclo.StPS5sUynSqPMX4ujmeAEIzUgbyhmXK', NULL, NULL, '2020-11-05 07:09:59'),
(2, 'shanti', 'shanti555@gmail.com', NULL, '$2y$10$fD8NLckeQoupAE8T4Vu2DOHNZeNkTDaE28wP.iBtWbSkyqo6LXHAC', NULL, NULL, '2020-11-07 08:50:32');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bills`
--
ALTER TABLE `bills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `party_fk_1` (`party_id`);

--
-- Indexes for table `bill_items`
--
ALTER TABLE `bill_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bill_fk_1` (`bill_id`),
  ADD KEY `item_fk_2` (`item_id`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `parties`
--
ALTER TABLE `parties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`(191));

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bills`
--
ALTER TABLE `bills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `bill_items`
--
ALTER TABLE `bill_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `parties`
--
ALTER TABLE `parties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
