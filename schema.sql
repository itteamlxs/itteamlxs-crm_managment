-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 04, 2025 at 09:31 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `crm_db`
--
CREATE DATABASE IF NOT EXISTS `crm_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `crm_db`;

-- --------------------------------------------------------

--
-- Table structure for table `access_requests`
--

CREATE TABLE `access_requests` (
  `request_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('PENDING','APPROVED','DENIED') NOT NULL DEFAULT 'PENDING',
  `requested_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reviewed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `comments` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `audit_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `entity_type` varchar(50) NOT NULL,
  `entity_id` bigint(20) UNSIGNED NOT NULL,
  `old_value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_value`)),
  `new_value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_value`)),
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`audit_id`, `user_id`, `action`, `entity_type`, `entity_id`, `old_value`, `new_value`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, NULL, 'INSERT', 'SETTINGS', 1, NULL, '{\"setting_key\": \"company_display_name\", \"setting_value\": \"Company Name\"}', '38', NULL, '2025-08-18 23:31:48'),
(2, NULL, 'INSERT', 'SETTINGS', 2, NULL, '{\"setting_key\": \"default_tax_rate\", \"setting_value\": \"0.00\"}', '38', NULL, '2025-08-18 23:31:48'),
(3, NULL, 'INSERT', 'SETTINGS', 3, NULL, '{\"setting_key\": \"quote_expiry_days\", \"setting_value\": \"7\"}', '38', NULL, '2025-08-18 23:31:48'),
(4, NULL, 'INSERT', 'SETTINGS', 4, NULL, '{\"setting_key\": \"quote_expiry_notification_days\", \"setting_value\": \"3\"}', '38', NULL, '2025-08-18 23:31:48'),
(5, NULL, 'INSERT', 'SETTINGS', 5, NULL, '{\"setting_key\": \"low_stock_threshold\", \"setting_value\": \"10\"}', '38', NULL, '2025-08-18 23:31:48'),
(6, NULL, 'INSERT', 'SETTINGS', 6, NULL, '{\"setting_key\": \"timezone\", \"setting_value\": \"America/New_York\"}', '38', NULL, '2025-08-18 23:31:48'),
(7, NULL, 'INSERT', 'SETTINGS', 7, NULL, '{\"setting_key\": \"available_languages\", \"setting_value\": \"[\\\"es\\\", \\\"en\\\", \\\"fr\\\", \\\"zh\\\"]\"}', '38', NULL, '2025-08-18 23:31:48'),
(8, NULL, 'INSERT', 'SETTINGS', 8, NULL, '{\"setting_key\": \"smtp_host\", \"setting_value\": \"smtp.example.com\"}', '38', NULL, '2025-08-18 23:31:48'),
(9, NULL, 'INSERT', 'SETTINGS', 9, NULL, '{\"setting_key\": \"smtp_port\", \"setting_value\": \"587\"}', '38', NULL, '2025-08-18 23:31:48'),
(10, NULL, 'INSERT', 'SETTINGS', 10, NULL, '{\"setting_key\": \"smtp_username\", \"setting_value\": \"user@example.com\"}', '38', NULL, '2025-08-18 23:31:48'),
(11, NULL, 'INSERT', 'SETTINGS', 11, NULL, '{\"setting_key\": \"smtp_password\", \"setting_value\": \"encrypted_password\"}', '38', NULL, '2025-08-18 23:31:48'),
(12, NULL, 'INSERT', 'SETTINGS', 12, NULL, '{\"setting_key\": \"smtp_encryption\", \"setting_value\": \"TLS\"}', '38', NULL, '2025-08-18 23:31:48'),
(13, NULL, 'INSERT', 'SETTINGS', 13, NULL, '{\"setting_key\": \"from_email\", \"setting_value\": \"no-reply@example.com\"}', '38', NULL, '2025-08-18 23:31:48'),
(14, NULL, 'INSERT', 'SETTINGS', 14, NULL, '{\"setting_key\": \"from_name\", \"setting_value\": \"Company Name\"}', '38', NULL, '2025-08-18 23:31:48'),
(15, NULL, 'INSERT', 'SETTINGS', 15, NULL, '{\"setting_key\": \"backup_time\", \"setting_value\": \"02:00:00\"}', '38', NULL, '2025-08-18 23:31:48'),
(16, NULL, 'INSERT', 'BACKUP_REQUEST', 1, NULL, '{\"status\": \"PENDING\", \"requested_at\": \"2025-08-19 04:18:17\"}', '69', NULL, '2025-08-19 10:18:17'),
(17, NULL, 'INSERT', 'USER', 1, NULL, '{\"username\": \"root\", \"email\": \"itbkup24@gmail.com\"}', '77', NULL, '2025-08-19 10:44:21'),
(18, NULL, 'UPDATE', 'USER', 1, '{\"username\": \"root\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"root\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '22', NULL, '2025-08-19 21:52:30'),
(19, NULL, 'UPDATE', 'USER', 1, '{\"username\": \"root\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"root\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '23', NULL, '2025-08-19 21:52:38'),
(20, NULL, 'UPDATE', 'USER', 1, '{\"username\": \"root\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"root\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '24', NULL, '2025-08-19 21:52:47'),
(21, NULL, 'UPDATE', 'USER', 1, '{\"username\": \"root\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"root\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '25', NULL, '2025-08-19 21:52:58'),
(22, NULL, 'UPDATE', 'USER', 1, '{\"username\": \"root\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"root\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '64', NULL, '2025-08-19 21:54:40'),
(23, NULL, 'UPDATE', 'USER', 1, '{\"username\": \"root\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"root\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '72', NULL, '2025-08-19 21:57:43'),
(24, NULL, 'UPDATE', 'USER', 1, '{\"username\": \"root\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"root\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '73', NULL, '2025-08-19 21:57:51'),
(25, NULL, 'UPDATE', 'USER', 1, '{\"username\": \"root\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"root\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '11', NULL, '2025-08-19 22:17:15'),
(26, 2, 'INSERT', 'USER', 2, NULL, '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\"}', '57', NULL, '2025-08-19 22:18:19'),
(27, 2, 'UPDATE', 'USER', 2, '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '63', NULL, '2025-08-19 22:18:39'),
(28, 2, 'UPDATE', 'USER', 2, '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '63', NULL, '2025-08-19 22:18:39'),
(29, 2, 'UPDATE', 'USER', 2, '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '9', NULL, '2025-08-20 08:38:25'),
(30, 2, 'UPDATE', 'USER', 2, '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '9', NULL, '2025-08-20 08:38:25'),
(31, 2, 'UPDATE', 'USER', 2, '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '59', NULL, '2025-08-20 08:58:15'),
(32, 2, 'UPDATE', 'USER', 2, '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '59', NULL, '2025-08-20 08:58:15'),
(33, 2, 'UPDATE', 'USER', 2, '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '62', NULL, '2025-08-20 09:14:36'),
(34, 2, 'UPDATE', 'USER', 2, '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '62', NULL, '2025-08-20 09:14:36'),
(35, 2, 'UPDATE', 'USER', 2, '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '107', NULL, '2025-08-20 22:03:58'),
(36, 2, 'UPDATE', 'USER', 2, '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '107', NULL, '2025-08-20 22:03:58'),
(37, 3, 'INSERT', 'USER', 3, NULL, '{\"username\": \"anderson\", \"email\": \"anderson@local.com\"}', '110', NULL, '2025-08-20 22:16:27'),
(38, 2, 'UPDATE', 'USER', 2, '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '10', NULL, '2025-08-21 07:46:08'),
(39, 2, 'UPDATE', 'USER', 2, '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '10', NULL, '2025-08-21 07:46:08'),
(40, 3, 'UPDATE', 'USER', 3, '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '29', NULL, '2025-08-21 08:06:16'),
(41, 3, 'UPDATE', 'USER', 3, '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '29', NULL, '2025-08-21 08:06:16'),
(42, 3, 'UPDATE', 'USER', 3, '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"en\"}', '32', NULL, '2025-08-21 08:06:28'),
(43, 3, 'UPDATE', 'USER', 3, '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"en\"}', '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"en\"}', '34', NULL, '2025-08-21 08:06:43'),
(44, 3, 'UPDATE', 'USER', 3, '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"en\"}', '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"en\"}', '10', NULL, '2025-08-21 08:09:47'),
(45, 3, 'UPDATE', 'USER', 3, '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"en\"}', '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"en\"}', '11', NULL, '2025-08-21 08:09:54'),
(46, 3, 'UPDATE', 'USER', 3, '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"en\"}', '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"en\"}', '11', NULL, '2025-08-21 08:09:54'),
(47, 2, 'UPDATE', 'USER', 2, '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '13', NULL, '2025-08-21 08:11:15'),
(48, 2, 'UPDATE', 'USER', 2, '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '13', NULL, '2025-08-21 08:11:15'),
(49, 3, 'UPDATE', 'USER', 3, '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"en\"}', '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"en\"}', '16', NULL, '2025-08-21 08:11:36'),
(50, 3, 'UPDATE', 'USER', 3, '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"en\"}', '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"en\"}', '17', NULL, '2025-08-21 08:11:42'),
(51, 3, 'UPDATE', 'USER', 3, '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"en\"}', '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"en\"}', '17', NULL, '2025-08-21 08:11:42'),
(52, 3, 'UPDATE', 'USER', 3, '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"en\"}', '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '53', NULL, '2025-08-21 08:12:55'),
(53, 3, 'UPDATE', 'USER', 3, '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '55', NULL, '2025-08-21 08:13:33'),
(54, 3, 'UPDATE', 'USER', 3, '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '55', NULL, '2025-08-21 08:13:33'),
(55, 2, 'UPDATE', 'USER', 2, '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '63', NULL, '2025-08-21 08:14:05'),
(56, 2, 'UPDATE', 'USER', 2, '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '64', NULL, '2025-08-21 08:14:05'),
(57, 2, 'UPDATE', 'USER', 2, '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '65', NULL, '2025-08-21 08:14:11'),
(58, 2, 'UPDATE', 'USER', 2, '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '65', NULL, '2025-08-21 08:14:11'),
(59, 2, 'UPDATE', 'USER', 2, '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '67', NULL, '2025-08-21 08:18:07'),
(60, 2, 'UPDATE', 'USER', 2, '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '67', NULL, '2025-08-21 08:18:07'),
(61, 2, 'UPDATE', 'USER', 2, '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '10', NULL, '2025-08-22 01:07:10'),
(62, 2, 'UPDATE', 'USER', 2, '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '10', NULL, '2025-08-22 01:07:10'),
(63, 3, 'UPDATE', 'USER', 3, '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '13', NULL, '2025-08-22 01:07:32'),
(64, 2, 'UPDATE', 'USER', 2, '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '54', NULL, '2025-08-22 01:09:16'),
(65, 2, 'UPDATE', 'USER', 2, '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '55', NULL, '2025-08-22 01:09:28'),
(66, 3, 'UPDATE', 'USER', 3, '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '83', NULL, '2025-08-22 01:10:30'),
(67, 2, 'UPDATE', 'USER', 2, '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '106', NULL, '2025-08-22 01:11:56'),
(68, 2, 'UPDATE', 'USER', 2, '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '10', NULL, '2025-08-22 22:52:06'),
(69, 2, 'UPDATE', 'USER', 2, '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '11', NULL, '2025-08-22 22:52:13'),
(70, 2, 'UPDATE', 'USER', 2, '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '11', NULL, '2025-08-22 22:52:13'),
(71, 3, 'UPDATE', 'USER', 3, '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '30', NULL, '2025-08-22 23:05:32'),
(72, 3, 'UPDATE', 'USER', 3, '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '30', NULL, '2025-08-22 23:05:32'),
(73, NULL, 'UPDATE', 'SETTINGS', 1, '{\"setting_key\": \"company_display_name\", \"setting_value\": \"Company Name\"}', '{\"setting_key\": \"company_display_name\", \"setting_value\": \"Entropic Networks\"}', '115', NULL, '2025-08-22 23:26:34'),
(74, NULL, 'UPDATE', 'SETTINGS', 2, '{\"setting_key\": \"default_tax_rate\", \"setting_value\": \"0.00\"}', '{\"setting_key\": \"default_tax_rate\", \"setting_value\": \"0.00\"}', '115', NULL, '2025-08-22 23:26:34'),
(75, NULL, 'UPDATE', 'SETTINGS', 3, '{\"setting_key\": \"quote_expiry_days\", \"setting_value\": \"7\"}', '{\"setting_key\": \"quote_expiry_days\", \"setting_value\": \"7\"}', '115', NULL, '2025-08-22 23:26:34'),
(76, NULL, 'UPDATE', 'SETTINGS', 4, '{\"setting_key\": \"quote_expiry_notification_days\", \"setting_value\": \"3\"}', '{\"setting_key\": \"quote_expiry_notification_days\", \"setting_value\": \"3\"}', '115', NULL, '2025-08-22 23:26:34'),
(77, NULL, 'UPDATE', 'SETTINGS', 5, '{\"setting_key\": \"low_stock_threshold\", \"setting_value\": \"10\"}', '{\"setting_key\": \"low_stock_threshold\", \"setting_value\": \"10\"}', '115', NULL, '2025-08-22 23:26:34'),
(78, NULL, 'UPDATE', 'SETTINGS', 6, '{\"setting_key\": \"timezone\", \"setting_value\": \"America/New_York\"}', '{\"setting_key\": \"timezone\", \"setting_value\": \"America/New_York\"}', '115', NULL, '2025-08-22 23:26:34'),
(79, NULL, 'UPDATE', 'SETTINGS', 7, '{\"setting_key\": \"available_languages\", \"setting_value\": \"[\\\"es\\\", \\\"en\\\", \\\"fr\\\", \\\"zh\\\"]\"}', '{\"setting_key\": \"available_languages\", \"setting_value\": \"[\\\"es\\\"]\"}', '115', NULL, '2025-08-22 23:26:34'),
(80, NULL, 'UPDATE', 'SETTINGS', 8, '{\"setting_key\": \"smtp_host\", \"setting_value\": \"smtp.example.com\"}', '{\"setting_key\": \"smtp_host\", \"setting_value\": \"\"}', '115', NULL, '2025-08-22 23:26:34'),
(81, NULL, 'UPDATE', 'SETTINGS', 9, '{\"setting_key\": \"smtp_port\", \"setting_value\": \"587\"}', '{\"setting_key\": \"smtp_port\", \"setting_value\": \"587\"}', '115', NULL, '2025-08-22 23:26:34'),
(82, NULL, 'UPDATE', 'SETTINGS', 10, '{\"setting_key\": \"smtp_username\", \"setting_value\": \"user@example.com\"}', '{\"setting_key\": \"smtp_username\", \"setting_value\": \"\"}', '115', NULL, '2025-08-22 23:26:34'),
(83, NULL, 'UPDATE', 'SETTINGS', 12, '{\"setting_key\": \"smtp_encryption\", \"setting_value\": \"TLS\"}', '{\"setting_key\": \"smtp_encryption\", \"setting_value\": \"TLS\"}', '115', NULL, '2025-08-22 23:26:34'),
(84, NULL, 'UPDATE', 'SETTINGS', 13, '{\"setting_key\": \"from_email\", \"setting_value\": \"no-reply@example.com\"}', '{\"setting_key\": \"from_email\", \"setting_value\": \"\"}', '115', NULL, '2025-08-22 23:26:34'),
(85, NULL, 'UPDATE', 'SETTINGS', 14, '{\"setting_key\": \"from_name\", \"setting_value\": \"Company Name\"}', '{\"setting_key\": \"from_name\", \"setting_value\": \"\"}', '115', NULL, '2025-08-22 23:26:34'),
(86, NULL, 'UPDATE', 'SETTINGS', 15, '{\"setting_key\": \"backup_time\", \"setting_value\": \"02:00:00\"}', '{\"setting_key\": \"backup_time\", \"setting_value\": \"02:00:00\"}', '115', NULL, '2025-08-22 23:26:34'),
(87, NULL, 'UPDATE', 'SETTINGS', 1, '{\"setting_key\": \"company_display_name\", \"setting_value\": \"Entropic Networks\"}', '{\"setting_key\": \"company_display_name\", \"setting_value\": \"Entropic Networks\"}', '116', NULL, '2025-08-22 23:26:42'),
(88, NULL, 'UPDATE', 'SETTINGS', 2, '{\"setting_key\": \"default_tax_rate\", \"setting_value\": \"0.00\"}', '{\"setting_key\": \"default_tax_rate\", \"setting_value\": \"0.00\"}', '116', NULL, '2025-08-22 23:26:42'),
(89, NULL, 'UPDATE', 'SETTINGS', 3, '{\"setting_key\": \"quote_expiry_days\", \"setting_value\": \"7\"}', '{\"setting_key\": \"quote_expiry_days\", \"setting_value\": \"7\"}', '116', NULL, '2025-08-22 23:26:42'),
(90, NULL, 'UPDATE', 'SETTINGS', 4, '{\"setting_key\": \"quote_expiry_notification_days\", \"setting_value\": \"3\"}', '{\"setting_key\": \"quote_expiry_notification_days\", \"setting_value\": \"3\"}', '116', NULL, '2025-08-22 23:26:42'),
(91, NULL, 'UPDATE', 'SETTINGS', 5, '{\"setting_key\": \"low_stock_threshold\", \"setting_value\": \"10\"}', '{\"setting_key\": \"low_stock_threshold\", \"setting_value\": \"10\"}', '116', NULL, '2025-08-22 23:26:42'),
(92, NULL, 'UPDATE', 'SETTINGS', 6, '{\"setting_key\": \"timezone\", \"setting_value\": \"America/New_York\"}', '{\"setting_key\": \"timezone\", \"setting_value\": \"America/New_York\"}', '116', NULL, '2025-08-22 23:26:42'),
(93, NULL, 'UPDATE', 'SETTINGS', 7, '{\"setting_key\": \"available_languages\", \"setting_value\": \"[\\\"es\\\"]\"}', '{\"setting_key\": \"available_languages\", \"setting_value\": \"[\\\"es\\\"]\"}', '116', NULL, '2025-08-22 23:26:42'),
(94, NULL, 'UPDATE', 'SETTINGS', 8, '{\"setting_key\": \"smtp_host\", \"setting_value\": \"\"}', '{\"setting_key\": \"smtp_host\", \"setting_value\": \"\"}', '116', NULL, '2025-08-22 23:26:42'),
(95, NULL, 'UPDATE', 'SETTINGS', 9, '{\"setting_key\": \"smtp_port\", \"setting_value\": \"587\"}', '{\"setting_key\": \"smtp_port\", \"setting_value\": \"587\"}', '116', NULL, '2025-08-22 23:26:42'),
(96, NULL, 'UPDATE', 'SETTINGS', 10, '{\"setting_key\": \"smtp_username\", \"setting_value\": \"\"}', '{\"setting_key\": \"smtp_username\", \"setting_value\": \"\"}', '116', NULL, '2025-08-22 23:26:42'),
(97, NULL, 'UPDATE', 'SETTINGS', 12, '{\"setting_key\": \"smtp_encryption\", \"setting_value\": \"TLS\"}', '{\"setting_key\": \"smtp_encryption\", \"setting_value\": \"TLS\"}', '116', NULL, '2025-08-22 23:26:42'),
(98, NULL, 'UPDATE', 'SETTINGS', 13, '{\"setting_key\": \"from_email\", \"setting_value\": \"\"}', '{\"setting_key\": \"from_email\", \"setting_value\": \"\"}', '116', NULL, '2025-08-22 23:26:42'),
(99, NULL, 'UPDATE', 'SETTINGS', 14, '{\"setting_key\": \"from_name\", \"setting_value\": \"\"}', '{\"setting_key\": \"from_name\", \"setting_value\": \"\"}', '116', NULL, '2025-08-22 23:26:42'),
(100, NULL, 'UPDATE', 'SETTINGS', 15, '{\"setting_key\": \"backup_time\", \"setting_value\": \"02:00:00\"}', '{\"setting_key\": \"backup_time\", \"setting_value\": \"02:00:00\"}', '116', NULL, '2025-08-22 23:26:42'),
(101, NULL, 'UPDATE', 'SETTINGS', 1, '{\"setting_key\": \"company_display_name\", \"setting_value\": \"Entropic Networks\"}', '{\"setting_key\": \"company_display_name\", \"setting_value\": \"LAstoBatalion\"}', '122', NULL, '2025-08-22 23:28:41'),
(102, NULL, 'UPDATE', 'SETTINGS', 2, '{\"setting_key\": \"default_tax_rate\", \"setting_value\": \"0.00\"}', '{\"setting_key\": \"default_tax_rate\", \"setting_value\": \"0.00\"}', '122', NULL, '2025-08-22 23:28:41'),
(103, NULL, 'UPDATE', 'SETTINGS', 3, '{\"setting_key\": \"quote_expiry_days\", \"setting_value\": \"7\"}', '{\"setting_key\": \"quote_expiry_days\", \"setting_value\": \"7\"}', '122', NULL, '2025-08-22 23:28:41'),
(104, NULL, 'UPDATE', 'SETTINGS', 4, '{\"setting_key\": \"quote_expiry_notification_days\", \"setting_value\": \"3\"}', '{\"setting_key\": \"quote_expiry_notification_days\", \"setting_value\": \"3\"}', '122', NULL, '2025-08-22 23:28:41'),
(105, NULL, 'UPDATE', 'SETTINGS', 5, '{\"setting_key\": \"low_stock_threshold\", \"setting_value\": \"10\"}', '{\"setting_key\": \"low_stock_threshold\", \"setting_value\": \"10\"}', '122', NULL, '2025-08-22 23:28:41'),
(106, NULL, 'UPDATE', 'SETTINGS', 6, '{\"setting_key\": \"timezone\", \"setting_value\": \"America/New_York\"}', '{\"setting_key\": \"timezone\", \"setting_value\": \"America/New_York\"}', '122', NULL, '2025-08-22 23:28:41'),
(107, NULL, 'UPDATE', 'SETTINGS', 7, '{\"setting_key\": \"available_languages\", \"setting_value\": \"[\\\"es\\\"]\"}', '{\"setting_key\": \"available_languages\", \"setting_value\": \"[\\\"es\\\"]\"}', '122', NULL, '2025-08-22 23:28:41'),
(108, NULL, 'UPDATE', 'SETTINGS', 8, '{\"setting_key\": \"smtp_host\", \"setting_value\": \"\"}', '{\"setting_key\": \"smtp_host\", \"setting_value\": \"\"}', '122', NULL, '2025-08-22 23:28:41'),
(109, NULL, 'UPDATE', 'SETTINGS', 9, '{\"setting_key\": \"smtp_port\", \"setting_value\": \"587\"}', '{\"setting_key\": \"smtp_port\", \"setting_value\": \"587\"}', '122', NULL, '2025-08-22 23:28:41'),
(110, NULL, 'UPDATE', 'SETTINGS', 10, '{\"setting_key\": \"smtp_username\", \"setting_value\": \"\"}', '{\"setting_key\": \"smtp_username\", \"setting_value\": \"\"}', '122', NULL, '2025-08-22 23:28:41'),
(111, NULL, 'UPDATE', 'SETTINGS', 12, '{\"setting_key\": \"smtp_encryption\", \"setting_value\": \"TLS\"}', '{\"setting_key\": \"smtp_encryption\", \"setting_value\": \"TLS\"}', '122', NULL, '2025-08-22 23:28:42'),
(112, NULL, 'UPDATE', 'SETTINGS', 13, '{\"setting_key\": \"from_email\", \"setting_value\": \"\"}', '{\"setting_key\": \"from_email\", \"setting_value\": \"\"}', '122', NULL, '2025-08-22 23:28:42'),
(113, NULL, 'UPDATE', 'SETTINGS', 14, '{\"setting_key\": \"from_name\", \"setting_value\": \"\"}', '{\"setting_key\": \"from_name\", \"setting_value\": \"\"}', '122', NULL, '2025-08-22 23:28:42'),
(114, NULL, 'UPDATE', 'SETTINGS', 15, '{\"setting_key\": \"backup_time\", \"setting_value\": \"02:00:00\"}', '{\"setting_key\": \"backup_time\", \"setting_value\": \"02:00:00\"}', '122', NULL, '2025-08-22 23:28:42'),
(115, NULL, 'UPDATE', 'SETTINGS', 1, '{\"setting_key\": \"company_display_name\", \"setting_value\": \"LAstoBatalion\"}', '{\"setting_key\": \"company_display_name\", \"setting_value\": \"Entropic Networks\"}', '135', NULL, '2025-08-22 23:28:59'),
(116, NULL, 'UPDATE', 'SETTINGS', 2, '{\"setting_key\": \"default_tax_rate\", \"setting_value\": \"0.00\"}', '{\"setting_key\": \"default_tax_rate\", \"setting_value\": \"0.00\"}', '135', NULL, '2025-08-22 23:28:59'),
(117, NULL, 'UPDATE', 'SETTINGS', 3, '{\"setting_key\": \"quote_expiry_days\", \"setting_value\": \"7\"}', '{\"setting_key\": \"quote_expiry_days\", \"setting_value\": \"7\"}', '135', NULL, '2025-08-22 23:28:59'),
(118, NULL, 'UPDATE', 'SETTINGS', 4, '{\"setting_key\": \"quote_expiry_notification_days\", \"setting_value\": \"3\"}', '{\"setting_key\": \"quote_expiry_notification_days\", \"setting_value\": \"3\"}', '135', NULL, '2025-08-22 23:28:59'),
(119, NULL, 'UPDATE', 'SETTINGS', 5, '{\"setting_key\": \"low_stock_threshold\", \"setting_value\": \"10\"}', '{\"setting_key\": \"low_stock_threshold\", \"setting_value\": \"10\"}', '135', NULL, '2025-08-22 23:28:59'),
(120, NULL, 'UPDATE', 'SETTINGS', 6, '{\"setting_key\": \"timezone\", \"setting_value\": \"America/New_York\"}', '{\"setting_key\": \"timezone\", \"setting_value\": \"America/New_York\"}', '135', NULL, '2025-08-22 23:28:59'),
(121, NULL, 'UPDATE', 'SETTINGS', 7, '{\"setting_key\": \"available_languages\", \"setting_value\": \"[\\\"es\\\"]\"}', '{\"setting_key\": \"available_languages\", \"setting_value\": \"[\\\"es\\\"]\"}', '135', NULL, '2025-08-22 23:28:59'),
(122, NULL, 'UPDATE', 'SETTINGS', 8, '{\"setting_key\": \"smtp_host\", \"setting_value\": \"\"}', '{\"setting_key\": \"smtp_host\", \"setting_value\": \"\"}', '135', NULL, '2025-08-22 23:28:59'),
(123, NULL, 'UPDATE', 'SETTINGS', 9, '{\"setting_key\": \"smtp_port\", \"setting_value\": \"587\"}', '{\"setting_key\": \"smtp_port\", \"setting_value\": \"587\"}', '135', NULL, '2025-08-22 23:28:59'),
(124, NULL, 'UPDATE', 'SETTINGS', 10, '{\"setting_key\": \"smtp_username\", \"setting_value\": \"\"}', '{\"setting_key\": \"smtp_username\", \"setting_value\": \"\"}', '135', NULL, '2025-08-22 23:28:59'),
(125, NULL, 'UPDATE', 'SETTINGS', 12, '{\"setting_key\": \"smtp_encryption\", \"setting_value\": \"TLS\"}', '{\"setting_key\": \"smtp_encryption\", \"setting_value\": \"TLS\"}', '135', NULL, '2025-08-22 23:28:59'),
(126, NULL, 'UPDATE', 'SETTINGS', 13, '{\"setting_key\": \"from_email\", \"setting_value\": \"\"}', '{\"setting_key\": \"from_email\", \"setting_value\": \"\"}', '135', NULL, '2025-08-22 23:28:59'),
(127, NULL, 'UPDATE', 'SETTINGS', 14, '{\"setting_key\": \"from_name\", \"setting_value\": \"\"}', '{\"setting_key\": \"from_name\", \"setting_value\": \"\"}', '135', NULL, '2025-08-22 23:28:59'),
(128, NULL, 'UPDATE', 'SETTINGS', 15, '{\"setting_key\": \"backup_time\", \"setting_value\": \"02:00:00\"}', '{\"setting_key\": \"backup_time\", \"setting_value\": \"02:00:00\"}', '135', NULL, '2025-08-22 23:28:59'),
(129, NULL, 'UPDATE', 'SETTINGS', 1, '{\"setting_key\": \"company_display_name\", \"setting_value\": \"Entropic Networks\"}', '{\"setting_key\": \"company_display_name\", \"setting_value\": \"Entropic Networks\"}', '183', NULL, '2025-08-22 23:41:40'),
(130, NULL, 'UPDATE', 'SETTINGS', 2, '{\"setting_key\": \"default_tax_rate\", \"setting_value\": \"0.00\"}', '{\"setting_key\": \"default_tax_rate\", \"setting_value\": \"0.00\"}', '183', NULL, '2025-08-22 23:41:40'),
(131, NULL, 'UPDATE', 'SETTINGS', 3, '{\"setting_key\": \"quote_expiry_days\", \"setting_value\": \"7\"}', '{\"setting_key\": \"quote_expiry_days\", \"setting_value\": \"7\"}', '183', NULL, '2025-08-22 23:41:40'),
(132, NULL, 'UPDATE', 'SETTINGS', 4, '{\"setting_key\": \"quote_expiry_notification_days\", \"setting_value\": \"3\"}', '{\"setting_key\": \"quote_expiry_notification_days\", \"setting_value\": \"3\"}', '183', NULL, '2025-08-22 23:41:40'),
(133, NULL, 'UPDATE', 'SETTINGS', 5, '{\"setting_key\": \"low_stock_threshold\", \"setting_value\": \"10\"}', '{\"setting_key\": \"low_stock_threshold\", \"setting_value\": \"10\"}', '183', NULL, '2025-08-22 23:41:40'),
(134, NULL, 'UPDATE', 'SETTINGS', 6, '{\"setting_key\": \"timezone\", \"setting_value\": \"America/New_York\"}', '{\"setting_key\": \"timezone\", \"setting_value\": \"America/New_York\"}', '183', NULL, '2025-08-22 23:41:40'),
(135, NULL, 'UPDATE', 'SETTINGS', 7, '{\"setting_key\": \"available_languages\", \"setting_value\": \"[\\\"es\\\"]\"}', '{\"setting_key\": \"available_languages\", \"setting_value\": \"[\\\"es\\\"]\"}', '183', NULL, '2025-08-22 23:41:40'),
(136, NULL, 'UPDATE', 'SETTINGS', 8, '{\"setting_key\": \"smtp_host\", \"setting_value\": \"\"}', '{\"setting_key\": \"smtp_host\", \"setting_value\": \"\"}', '183', NULL, '2025-08-22 23:41:40'),
(137, NULL, 'UPDATE', 'SETTINGS', 9, '{\"setting_key\": \"smtp_port\", \"setting_value\": \"587\"}', '{\"setting_key\": \"smtp_port\", \"setting_value\": \"587\"}', '183', NULL, '2025-08-22 23:41:40'),
(138, NULL, 'UPDATE', 'SETTINGS', 10, '{\"setting_key\": \"smtp_username\", \"setting_value\": \"\"}', '{\"setting_key\": \"smtp_username\", \"setting_value\": \"\"}', '183', NULL, '2025-08-22 23:41:40'),
(139, NULL, 'UPDATE', 'SETTINGS', 12, '{\"setting_key\": \"smtp_encryption\", \"setting_value\": \"TLS\"}', '{\"setting_key\": \"smtp_encryption\", \"setting_value\": \"TLS\"}', '183', NULL, '2025-08-22 23:41:40'),
(140, NULL, 'UPDATE', 'SETTINGS', 13, '{\"setting_key\": \"from_email\", \"setting_value\": \"\"}', '{\"setting_key\": \"from_email\", \"setting_value\": \"\"}', '183', NULL, '2025-08-22 23:41:40'),
(141, NULL, 'UPDATE', 'SETTINGS', 14, '{\"setting_key\": \"from_name\", \"setting_value\": \"\"}', '{\"setting_key\": \"from_name\", \"setting_value\": \"\"}', '183', NULL, '2025-08-22 23:41:40'),
(142, NULL, 'UPDATE', 'SETTINGS', 15, '{\"setting_key\": \"backup_time\", \"setting_value\": \"02:00:00\"}', '{\"setting_key\": \"backup_time\", \"setting_value\": \"02:00:00\"}', '183', NULL, '2025-08-22 23:41:40'),
(143, NULL, 'UPDATE', 'SETTINGS', 1, '{\"setting_key\": \"company_display_name\", \"setting_value\": \"Entropic Networks\"}', '{\"setting_key\": \"company_display_name\", \"setting_value\": \"Entropic Networks\"}', '202', NULL, '2025-08-22 23:47:50'),
(144, NULL, 'UPDATE', 'SETTINGS', 2, '{\"setting_key\": \"default_tax_rate\", \"setting_value\": \"0.00\"}', '{\"setting_key\": \"default_tax_rate\", \"setting_value\": \"0.00\"}', '202', NULL, '2025-08-22 23:47:50'),
(145, NULL, 'UPDATE', 'SETTINGS', 3, '{\"setting_key\": \"quote_expiry_days\", \"setting_value\": \"7\"}', '{\"setting_key\": \"quote_expiry_days\", \"setting_value\": \"7\"}', '202', NULL, '2025-08-22 23:47:50'),
(146, NULL, 'UPDATE', 'SETTINGS', 4, '{\"setting_key\": \"quote_expiry_notification_days\", \"setting_value\": \"3\"}', '{\"setting_key\": \"quote_expiry_notification_days\", \"setting_value\": \"3\"}', '202', NULL, '2025-08-22 23:47:50'),
(147, NULL, 'UPDATE', 'SETTINGS', 5, '{\"setting_key\": \"low_stock_threshold\", \"setting_value\": \"10\"}', '{\"setting_key\": \"low_stock_threshold\", \"setting_value\": \"10\"}', '202', NULL, '2025-08-22 23:47:50'),
(148, NULL, 'UPDATE', 'SETTINGS', 6, '{\"setting_key\": \"timezone\", \"setting_value\": \"America/New_York\"}', '{\"setting_key\": \"timezone\", \"setting_value\": \"America/New_York\"}', '202', NULL, '2025-08-22 23:47:50'),
(149, NULL, 'UPDATE', 'SETTINGS', 7, '{\"setting_key\": \"available_languages\", \"setting_value\": \"[\\\"es\\\"]\"}', '{\"setting_key\": \"available_languages\", \"setting_value\": \"[\\\"es\\\"]\"}', '202', NULL, '2025-08-22 23:47:50'),
(150, NULL, 'UPDATE', 'SETTINGS', 8, '{\"setting_key\": \"smtp_host\", \"setting_value\": \"\"}', '{\"setting_key\": \"smtp_host\", \"setting_value\": \"\"}', '202', NULL, '2025-08-22 23:47:50'),
(151, NULL, 'UPDATE', 'SETTINGS', 9, '{\"setting_key\": \"smtp_port\", \"setting_value\": \"587\"}', '{\"setting_key\": \"smtp_port\", \"setting_value\": \"587\"}', '202', NULL, '2025-08-22 23:47:50'),
(152, NULL, 'UPDATE', 'SETTINGS', 10, '{\"setting_key\": \"smtp_username\", \"setting_value\": \"\"}', '{\"setting_key\": \"smtp_username\", \"setting_value\": \"\"}', '202', NULL, '2025-08-22 23:47:50'),
(153, NULL, 'UPDATE', 'SETTINGS', 12, '{\"setting_key\": \"smtp_encryption\", \"setting_value\": \"TLS\"}', '{\"setting_key\": \"smtp_encryption\", \"setting_value\": \"TLS\"}', '202', NULL, '2025-08-22 23:47:50'),
(154, NULL, 'UPDATE', 'SETTINGS', 13, '{\"setting_key\": \"from_email\", \"setting_value\": \"\"}', '{\"setting_key\": \"from_email\", \"setting_value\": \"\"}', '202', NULL, '2025-08-22 23:47:50'),
(155, NULL, 'UPDATE', 'SETTINGS', 14, '{\"setting_key\": \"from_name\", \"setting_value\": \"\"}', '{\"setting_key\": \"from_name\", \"setting_value\": \"\"}', '202', NULL, '2025-08-22 23:47:50'),
(156, NULL, 'UPDATE', 'SETTINGS', 15, '{\"setting_key\": \"backup_time\", \"setting_value\": \"02:00:00\"}', '{\"setting_key\": \"backup_time\", \"setting_value\": \"02:00:00\"}', '202', NULL, '2025-08-22 23:47:51'),
(157, NULL, 'UPDATE', 'SETTINGS', 1, '{\"setting_key\": \"company_display_name\", \"setting_value\": \"Entropic Networks\"}', '{\"setting_key\": \"company_display_name\", \"setting_value\": \"Entropic Networks Essentials\"}', '203', NULL, '2025-08-22 23:48:49'),
(158, NULL, 'UPDATE', 'SETTINGS', 2, '{\"setting_key\": \"default_tax_rate\", \"setting_value\": \"0.00\"}', '{\"setting_key\": \"default_tax_rate\", \"setting_value\": \"0.00\"}', '203', NULL, '2025-08-22 23:48:49'),
(159, NULL, 'UPDATE', 'SETTINGS', 3, '{\"setting_key\": \"quote_expiry_days\", \"setting_value\": \"7\"}', '{\"setting_key\": \"quote_expiry_days\", \"setting_value\": \"7\"}', '203', NULL, '2025-08-22 23:48:49'),
(160, NULL, 'UPDATE', 'SETTINGS', 4, '{\"setting_key\": \"quote_expiry_notification_days\", \"setting_value\": \"3\"}', '{\"setting_key\": \"quote_expiry_notification_days\", \"setting_value\": \"3\"}', '203', NULL, '2025-08-22 23:48:49'),
(161, NULL, 'UPDATE', 'SETTINGS', 5, '{\"setting_key\": \"low_stock_threshold\", \"setting_value\": \"10\"}', '{\"setting_key\": \"low_stock_threshold\", \"setting_value\": \"10\"}', '203', NULL, '2025-08-22 23:48:49'),
(162, NULL, 'UPDATE', 'SETTINGS', 6, '{\"setting_key\": \"timezone\", \"setting_value\": \"America/New_York\"}', '{\"setting_key\": \"timezone\", \"setting_value\": \"America/New_York\"}', '203', NULL, '2025-08-22 23:48:49'),
(163, NULL, 'UPDATE', 'SETTINGS', 7, '{\"setting_key\": \"available_languages\", \"setting_value\": \"[\\\"es\\\"]\"}', '{\"setting_key\": \"available_languages\", \"setting_value\": \"[\\\"es\\\"]\"}', '203', NULL, '2025-08-22 23:48:49'),
(164, NULL, 'UPDATE', 'SETTINGS', 8, '{\"setting_key\": \"smtp_host\", \"setting_value\": \"\"}', '{\"setting_key\": \"smtp_host\", \"setting_value\": \"\"}', '203', NULL, '2025-08-22 23:48:49'),
(165, NULL, 'UPDATE', 'SETTINGS', 9, '{\"setting_key\": \"smtp_port\", \"setting_value\": \"587\"}', '{\"setting_key\": \"smtp_port\", \"setting_value\": \"587\"}', '203', NULL, '2025-08-22 23:48:49'),
(166, NULL, 'UPDATE', 'SETTINGS', 10, '{\"setting_key\": \"smtp_username\", \"setting_value\": \"\"}', '{\"setting_key\": \"smtp_username\", \"setting_value\": \"\"}', '203', NULL, '2025-08-22 23:48:49'),
(167, NULL, 'UPDATE', 'SETTINGS', 12, '{\"setting_key\": \"smtp_encryption\", \"setting_value\": \"TLS\"}', '{\"setting_key\": \"smtp_encryption\", \"setting_value\": \"TLS\"}', '203', NULL, '2025-08-22 23:48:49'),
(168, NULL, 'UPDATE', 'SETTINGS', 13, '{\"setting_key\": \"from_email\", \"setting_value\": \"\"}', '{\"setting_key\": \"from_email\", \"setting_value\": \"\"}', '203', NULL, '2025-08-22 23:48:49'),
(169, NULL, 'UPDATE', 'SETTINGS', 14, '{\"setting_key\": \"from_name\", \"setting_value\": \"\"}', '{\"setting_key\": \"from_name\", \"setting_value\": \"\"}', '203', NULL, '2025-08-22 23:48:49'),
(170, NULL, 'UPDATE', 'SETTINGS', 15, '{\"setting_key\": \"backup_time\", \"setting_value\": \"02:00:00\"}', '{\"setting_key\": \"backup_time\", \"setting_value\": \"02:00:00\"}', '203', NULL, '2025-08-22 23:48:49'),
(171, NULL, 'UPDATE', 'SETTINGS', 1, '{\"setting_key\": \"company_display_name\", \"setting_value\": \"Entropic Networks Essentials\"}', '{\"setting_key\": \"company_display_name\", \"setting_value\": \"Entropic Networks Essentials\"}', '204', NULL, '2025-08-22 23:48:56'),
(172, NULL, 'UPDATE', 'SETTINGS', 2, '{\"setting_key\": \"default_tax_rate\", \"setting_value\": \"0.00\"}', '{\"setting_key\": \"default_tax_rate\", \"setting_value\": \"0.00\"}', '204', NULL, '2025-08-22 23:48:56'),
(173, NULL, 'UPDATE', 'SETTINGS', 3, '{\"setting_key\": \"quote_expiry_days\", \"setting_value\": \"7\"}', '{\"setting_key\": \"quote_expiry_days\", \"setting_value\": \"7\"}', '204', NULL, '2025-08-22 23:48:56'),
(174, NULL, 'UPDATE', 'SETTINGS', 4, '{\"setting_key\": \"quote_expiry_notification_days\", \"setting_value\": \"3\"}', '{\"setting_key\": \"quote_expiry_notification_days\", \"setting_value\": \"3\"}', '204', NULL, '2025-08-22 23:48:56'),
(175, NULL, 'UPDATE', 'SETTINGS', 5, '{\"setting_key\": \"low_stock_threshold\", \"setting_value\": \"10\"}', '{\"setting_key\": \"low_stock_threshold\", \"setting_value\": \"10\"}', '204', NULL, '2025-08-22 23:48:56'),
(176, NULL, 'UPDATE', 'SETTINGS', 6, '{\"setting_key\": \"timezone\", \"setting_value\": \"America/New_York\"}', '{\"setting_key\": \"timezone\", \"setting_value\": \"America/New_York\"}', '204', NULL, '2025-08-22 23:48:56'),
(177, NULL, 'UPDATE', 'SETTINGS', 7, '{\"setting_key\": \"available_languages\", \"setting_value\": \"[\\\"es\\\"]\"}', '{\"setting_key\": \"available_languages\", \"setting_value\": \"[\\\"es\\\"]\"}', '204', NULL, '2025-08-22 23:48:56'),
(178, NULL, 'UPDATE', 'SETTINGS', 8, '{\"setting_key\": \"smtp_host\", \"setting_value\": \"\"}', '{\"setting_key\": \"smtp_host\", \"setting_value\": \"\"}', '204', NULL, '2025-08-22 23:48:56'),
(179, NULL, 'UPDATE', 'SETTINGS', 9, '{\"setting_key\": \"smtp_port\", \"setting_value\": \"587\"}', '{\"setting_key\": \"smtp_port\", \"setting_value\": \"587\"}', '204', NULL, '2025-08-22 23:48:56'),
(180, NULL, 'UPDATE', 'SETTINGS', 10, '{\"setting_key\": \"smtp_username\", \"setting_value\": \"\"}', '{\"setting_key\": \"smtp_username\", \"setting_value\": \"\"}', '204', NULL, '2025-08-22 23:48:56'),
(181, NULL, 'UPDATE', 'SETTINGS', 12, '{\"setting_key\": \"smtp_encryption\", \"setting_value\": \"TLS\"}', '{\"setting_key\": \"smtp_encryption\", \"setting_value\": \"TLS\"}', '204', NULL, '2025-08-22 23:48:56'),
(182, NULL, 'UPDATE', 'SETTINGS', 13, '{\"setting_key\": \"from_email\", \"setting_value\": \"\"}', '{\"setting_key\": \"from_email\", \"setting_value\": \"\"}', '204', NULL, '2025-08-22 23:48:56'),
(183, NULL, 'UPDATE', 'SETTINGS', 14, '{\"setting_key\": \"from_name\", \"setting_value\": \"\"}', '{\"setting_key\": \"from_name\", \"setting_value\": \"\"}', '204', NULL, '2025-08-22 23:48:56'),
(184, NULL, 'UPDATE', 'SETTINGS', 15, '{\"setting_key\": \"backup_time\", \"setting_value\": \"02:00:00\"}', '{\"setting_key\": \"backup_time\", \"setting_value\": \"02:00:00\"}', '204', NULL, '2025-08-22 23:48:56'),
(185, 2, 'UPDATE', 'USER', 2, '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '300', NULL, '2025-08-23 00:36:36'),
(186, 2, 'UPDATE', 'USER', 2, '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '300', NULL, '2025-08-23 00:36:36'),
(187, 2, 'UPDATE', 'USER', 2, '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '24', NULL, '2025-08-23 11:13:49'),
(188, 2, 'UPDATE', 'USER', 2, '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '25', NULL, '2025-08-23 11:13:54'),
(189, 2, 'UPDATE', 'USER', 2, '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '25', NULL, '2025-08-23 11:13:54'),
(190, 2, 'UPDATE', 'USER', 2, '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '137', NULL, '2025-08-23 20:18:19'),
(191, 2, 'UPDATE', 'USER', 2, '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '138', NULL, '2025-08-23 20:18:24'),
(192, 2, 'UPDATE', 'USER', 2, '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '138', NULL, '2025-08-23 20:18:24'),
(193, 2, 'UPDATE', 'USER', 2, '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '167', NULL, '2025-08-23 21:15:33'),
(194, 2, 'UPDATE', 'USER', 2, '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '167', NULL, '2025-08-23 21:15:33'),
(195, 3, 'UPDATE', 'USER', 3, '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '169', NULL, '2025-08-23 21:15:42'),
(196, 3, 'UPDATE', 'USER', 3, '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '171', NULL, '2025-08-23 21:15:57'),
(197, 3, 'UPDATE', 'USER', 3, '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '173', NULL, '2025-08-23 21:16:10'),
(198, 3, 'UPDATE', 'USER', 3, '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '178', NULL, '2025-08-23 21:16:38'),
(199, 3, 'UPDATE', 'USER', 3, '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '180', NULL, '2025-08-23 21:16:53'),
(200, 3, 'UPDATE', 'USER', 3, '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '182', NULL, '2025-08-23 21:17:10'),
(201, 2, 'UPDATE', 'USER', 2, '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '183', NULL, '2025-08-23 21:17:18'),
(202, 2, 'UPDATE', 'USER', 2, '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '184', NULL, '2025-08-23 21:17:25'),
(203, 2, 'UPDATE', 'USER', 2, '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '184', NULL, '2025-08-23 21:17:25'),
(204, 3, 'UPDATE', 'USER', 3, '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '186', NULL, '2025-08-23 21:17:35'),
(205, 3, 'UPDATE', 'USER', 3, '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '188', NULL, '2025-08-23 21:17:48'),
(206, 3, 'UPDATE', 'USER', 3, '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '189', NULL, '2025-08-23 21:17:52'),
(207, 2, 'UPDATE', 'USER', 2, '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '191', NULL, '2025-08-23 21:18:07'),
(208, 2, 'UPDATE', 'USER', 2, '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '191', NULL, '2025-08-23 21:18:07'),
(209, 3, 'UPDATE', 'USER', 3, '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '196', NULL, '2025-08-23 21:18:32'),
(210, 3, 'UPDATE', 'USER', 3, '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '197', NULL, '2025-08-23 21:18:42'),
(211, 3, 'UPDATE', 'USER', 3, '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '199', NULL, '2025-08-23 21:19:06'),
(212, 3, 'UPDATE', 'USER', 3, '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '199', NULL, '2025-08-23 21:19:06'),
(213, 3, 'UPDATE', 'USER', 3, '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '202', NULL, '2025-08-23 21:19:23'),
(214, 2, 'UPDATE', 'USER', 2, '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '212', NULL, '2025-08-23 21:19:44'),
(215, 2, 'UPDATE', 'USER', 2, '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '212', NULL, '2025-08-23 21:19:44'),
(216, 4, 'INSERT', 'USER', 4, NULL, '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\"}', '215', NULL, '2025-08-23 21:20:25'),
(217, 3, 'UPDATE', 'USER', 3, '{\"username\": \"anderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '219', NULL, '2025-08-23 21:20:46'),
(218, 2, 'UPDATE', 'USER', 2, '{\"username\": \"leon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '222', NULL, '2025-08-23 21:20:59'),
(219, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '225', NULL, '2025-08-23 21:21:14'),
(220, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '225', NULL, '2025-08-23 21:21:14'),
(221, 4, 'UPDATE', 'USER', 4, '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '227', NULL, '2025-08-23 21:21:24'),
(222, 4, 'UPDATE', 'USER', 4, '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '227', NULL, '2025-08-23 21:21:24'),
(223, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '232', NULL, '2025-08-23 21:21:42'),
(224, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '232', NULL, '2025-08-23 21:21:42'),
(225, NULL, 'UPDATE', 'SETTINGS', 1, '{\"setting_key\": \"company_display_name\", \"setting_value\": \"Entropic Networks Essentials\"}', '{\"setting_key\": \"company_display_name\", \"setting_value\": \"Entropic Networks\"}', '269', NULL, '2025-08-23 21:53:40'),
(226, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '366', NULL, '2025-08-24 11:22:07'),
(227, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '9', NULL, '2025-08-24 11:22:53');
INSERT INTO `audit_logs` (`audit_id`, `user_id`, `action`, `entity_type`, `entity_id`, `old_value`, `new_value`, `ip_address`, `user_agent`, `created_at`) VALUES
(228, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '9', NULL, '2025-08-24 11:22:53'),
(229, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '298', NULL, '2025-08-24 20:36:17'),
(230, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '298', NULL, '2025-08-24 20:36:17'),
(231, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '362', NULL, '2025-08-25 08:50:21'),
(232, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '363', NULL, '2025-08-25 08:50:27'),
(233, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '363', NULL, '2025-08-25 08:50:27'),
(234, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '366', NULL, '2025-08-25 08:50:53'),
(235, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '367', NULL, '2025-08-25 08:50:57'),
(236, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '368', NULL, '2025-08-25 08:51:07'),
(237, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '370', NULL, '2025-08-25 08:51:17'),
(238, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '371', NULL, '2025-08-25 08:51:26'),
(239, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '371', NULL, '2025-08-25 08:51:26'),
(240, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '428', NULL, '2025-08-25 09:08:28'),
(241, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '10', NULL, '2025-08-26 22:40:47'),
(242, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '10', NULL, '2025-08-26 22:40:47'),
(243, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '21', NULL, '2025-08-26 22:49:52'),
(244, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '25', NULL, '2025-08-26 22:50:08'),
(245, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '25', NULL, '2025-08-26 22:50:08'),
(246, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '29', NULL, '2025-08-26 22:50:42'),
(247, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '30', NULL, '2025-08-26 22:50:47'),
(248, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '31', NULL, '2025-08-26 22:50:56'),
(249, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '43', NULL, '2025-08-26 22:51:50'),
(250, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '45', NULL, '2025-08-26 22:52:12'),
(251, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '45', NULL, '2025-08-26 22:52:12'),
(252, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '48', NULL, '2025-08-26 22:52:27'),
(253, 4, 'UPDATE', 'USER', 4, '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '57', NULL, '2025-08-26 22:52:57'),
(254, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '60', NULL, '2025-08-26 22:53:15'),
(255, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '62', NULL, '2025-08-26 22:53:38'),
(256, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '63', NULL, '2025-08-26 22:53:45'),
(257, 4, 'UPDATE', 'USER', 4, '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '66', NULL, '2025-08-26 22:54:03'),
(258, 4, 'UPDATE', 'USER', 4, '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '68', NULL, '2025-08-26 22:54:12'),
(259, 4, 'UPDATE', 'USER', 4, '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '69', NULL, '2025-08-26 22:55:13'),
(260, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '74', NULL, '2025-08-26 22:57:26'),
(261, 4, 'UPDATE', 'USER', 4, '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '80', NULL, '2025-08-26 23:03:41'),
(262, 4, 'UPDATE', 'USER', 4, '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '82', NULL, '2025-08-26 23:03:48'),
(263, 4, 'UPDATE', 'USER', 4, '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '87', NULL, '2025-08-26 23:04:24'),
(264, 4, 'UPDATE', 'USER', 4, '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '88', NULL, '2025-08-26 23:04:30'),
(265, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '116', NULL, '2025-08-26 23:17:32'),
(266, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '116', NULL, '2025-08-26 23:17:32'),
(267, 4, 'UPDATE', 'USER', 4, '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '146', NULL, '2025-08-26 23:20:32'),
(268, 4, 'UPDATE', 'USER', 4, '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '147', NULL, '2025-08-26 23:20:35'),
(269, 4, 'UPDATE', 'USER', 4, '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '219', NULL, '2025-08-26 23:32:49'),
(270, 5, 'INSERT', 'USER', 5, NULL, '{\"username\": \"fluna\", \"email\": \"lunaleon@local.com\"}', '221', NULL, '2025-08-26 23:33:48'),
(271, 5, 'UPDATE', 'USER', 5, '{\"username\": \"fluna\", \"email\": \"lunaleon@local.com\", \"language\": \"es\"}', '{\"username\": \"fluna\", \"email\": \"lunaleon@local.com\", \"language\": \"es\"}', '223', NULL, '2025-08-26 23:33:55'),
(272, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '306', NULL, '2025-08-27 09:25:23'),
(273, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '307', NULL, '2025-08-27 09:25:29'),
(274, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '308', NULL, '2025-08-27 09:25:37'),
(275, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '308', NULL, '2025-08-27 09:25:37'),
(276, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '352', NULL, '2025-08-27 11:20:41'),
(277, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '352', NULL, '2025-08-27 11:20:41'),
(278, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '360', NULL, '2025-08-27 22:05:39'),
(279, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '361', NULL, '2025-08-27 22:05:44'),
(280, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '361', NULL, '2025-08-27 22:05:44'),
(281, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '399', NULL, '2025-08-28 23:07:36'),
(282, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '400', NULL, '2025-08-28 23:07:41'),
(283, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '400', NULL, '2025-08-28 23:07:41'),
(284, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '510', NULL, '2025-08-29 00:08:28'),
(285, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '511', NULL, '2025-08-29 00:08:39'),
(286, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '511', NULL, '2025-08-29 00:08:39'),
(287, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '514', NULL, '2025-08-29 00:08:54'),
(288, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '515', NULL, '2025-08-29 00:09:02'),
(289, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '580', NULL, '2025-08-29 08:43:35'),
(290, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '580', NULL, '2025-08-29 08:43:35'),
(291, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '594', NULL, '2025-08-29 08:49:13'),
(292, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '595', NULL, '2025-08-29 08:49:20'),
(293, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '595', NULL, '2025-08-29 08:49:20'),
(294, 4, 'UPDATE', 'USER', 4, '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '622', NULL, '2025-08-29 09:02:32'),
(295, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '629', NULL, '2025-08-29 09:03:24'),
(296, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '630', NULL, '2025-08-29 09:03:30'),
(297, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '630', NULL, '2025-08-29 09:03:30'),
(298, 4, 'UPDATE', 'USER', 4, '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '637', NULL, '2025-08-29 09:03:58'),
(299, 4, 'UPDATE', 'USER', 4, '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '637', NULL, '2025-08-29 09:03:58'),
(300, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '659', NULL, '2025-08-29 13:17:19'),
(301, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '659', NULL, '2025-08-29 13:17:19'),
(302, NULL, 'UPDATE', 'SETTINGS', 13, '{\"setting_key\": \"from_email\", \"setting_value\": \"\"}', '{\"setting_key\": \"from_email\", \"setting_value\": \"itbkup24@gmail.com\"}', '671', NULL, '2025-08-29 13:23:55'),
(303, NULL, 'UPDATE', 'SETTINGS', 14, '{\"setting_key\": \"from_name\", \"setting_value\": \"\"}', '{\"setting_key\": \"from_name\", \"setting_value\": \"CRM System\"}', '671', NULL, '2025-08-29 13:23:55'),
(304, NULL, 'UPDATE', 'SETTINGS', 8, '{\"setting_key\": \"smtp_host\", \"setting_value\": \"\"}', '{\"setting_key\": \"smtp_host\", \"setting_value\": \"smtp.gmail.com\"}', '671', NULL, '2025-08-29 13:23:55'),
(305, NULL, 'UPDATE', 'SETTINGS', 11, '{\"setting_key\": \"smtp_password\", \"setting_value\": \"encrypted_password\"}', '{\"setting_key\": \"smtp_password\", \"setting_value\": \"rgti ikam yrvi bpjy\"}', '671', NULL, '2025-08-29 13:23:55'),
(306, NULL, 'UPDATE', 'SETTINGS', 10, '{\"setting_key\": \"smtp_username\", \"setting_value\": \"\"}', '{\"setting_key\": \"smtp_username\", \"setting_value\": \"itbkup24@gmail.com\"}', '671', NULL, '2025-08-29 13:23:55'),
(307, 2, 'UPDATE', 'QUOTE', 2, '{\"status\": \"DRAFT\", \"parent_quote_id\": null}', '{\"status\": \"SENT\", \"parent_quote_id\": null}', '694', NULL, '2025-08-29 13:26:05'),
(308, 2, 'UPDATE', 'QUOTE', 3, '{\"status\": \"DRAFT\", \"parent_quote_id\": null}', '{\"status\": \"DRAFT\", \"parent_quote_id\": null}', '701', NULL, '2025-08-29 13:27:38'),
(309, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '10', NULL, '2025-08-29 13:44:21'),
(310, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '11', NULL, '2025-08-29 13:44:26'),
(311, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '11', NULL, '2025-08-29 13:44:26'),
(312, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '23', NULL, '2025-08-29 13:46:35'),
(313, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '26', NULL, '2025-08-29 20:32:04'),
(314, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '26', NULL, '2025-08-29 20:32:04'),
(315, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '48', NULL, '2025-08-29 20:57:14'),
(316, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '48', NULL, '2025-08-29 20:57:14'),
(317, 2, 'APPROVE', 'QUOTE', 2, '{\"status\": \"SENT\"}', '{\"status\": \"APPROVED\", \"stock_updated\": true}', '127.0.0.1', NULL, '2025-08-29 21:02:59'),
(318, 2, 'UPDATE', 'QUOTE', 4, '{\"status\": \"DRAFT\", \"parent_quote_id\": 2}', '{\"status\": \"SENT\", \"parent_quote_id\": 2}', '59', NULL, '2025-08-29 21:05:06'),
(319, 2, 'APPROVE', 'QUOTE', 4, '{\"status\": \"SENT\"}', '{\"status\": \"APPROVED\", \"stock_updated\": true}', '127.0.0.1', NULL, '2025-08-29 21:05:28'),
(320, 2, 'UPDATE', 'QUOTE', 3, '{\"status\": \"DRAFT\", \"parent_quote_id\": null}', '{\"status\": \"SENT\", \"parent_quote_id\": null}', '126', NULL, '2025-08-29 21:38:31'),
(321, 2, 'UPDATE', 'QUOTE', 5, '{\"status\": \"DRAFT\", \"parent_quote_id\": null}', '{\"status\": \"SENT\", \"parent_quote_id\": null}', '141', NULL, '2025-08-29 21:50:41'),
(322, 2, 'UPDATE', 'QUOTE', 1, '{\"status\": \"DRAFT\", \"parent_quote_id\": null}', '{\"status\": \"SENT\", \"parent_quote_id\": null}', '185', NULL, '2025-08-29 22:13:52'),
(323, 2, 'UPDATE', 'QUOTE', 1, '{\"status\": \"SENT\", \"parent_quote_id\": null}', '{\"status\": \"REJECTED\", \"parent_quote_id\": null}', '187', NULL, '2025-08-29 22:14:00'),
(324, 2, 'UPDATE', 'QUOTE', 3, '{\"status\": \"SENT\", \"parent_quote_id\": null}', '{\"status\": \"REJECTED\", \"parent_quote_id\": null}', '214', NULL, '2025-08-29 22:28:30'),
(325, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '10', NULL, '2025-09-04 20:55:17'),
(326, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '11', NULL, '2025-09-04 20:55:24'),
(327, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '12', NULL, '2025-09-04 20:55:29'),
(328, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '12', NULL, '2025-09-04 20:55:29'),
(329, 2, 'UPDATE', 'QUOTE', 10, '{\"status\": \"DRAFT\", \"parent_quote_id\": null}', '{\"status\": \"SENT\", \"parent_quote_id\": null}', '46', NULL, '2025-09-04 21:10:35'),
(330, 2, 'UPDATE', 'QUOTE', 5, '{\"status\": \"SENT\", \"parent_quote_id\": null}', '{\"status\": \"REJECTED\", \"parent_quote_id\": null}', '54', NULL, '2025-09-04 21:11:35'),
(331, 2, 'UPDATE', 'QUOTE', 11, '{\"status\": \"DRAFT\", \"parent_quote_id\": null}', '{\"status\": \"SENT\", \"parent_quote_id\": null}', '61', NULL, '2025-09-04 21:12:14'),
(332, 2, 'UPDATE', 'QUOTE', 12, '{\"status\": \"DRAFT\", \"parent_quote_id\": null}', '{\"status\": \"DRAFT\", \"parent_quote_id\": null}', '99', NULL, '2025-09-04 21:26:30'),
(333, 2, 'UPDATE', 'QUOTE', 12, '{\"status\": \"DRAFT\", \"parent_quote_id\": null}', '{\"status\": \"DRAFT\", \"parent_quote_id\": null}', '102', NULL, '2025-09-04 21:26:39'),
(334, 2, 'UPDATE', 'QUOTE', 12, '{\"status\": \"DRAFT\", \"parent_quote_id\": null}', '{\"status\": \"DRAFT\", \"parent_quote_id\": null}', '111', NULL, '2025-09-04 21:31:14'),
(335, 2, 'UPDATE', 'QUOTE', 11, '{\"status\": \"SENT\", \"parent_quote_id\": null}', '{\"status\": \"APPROVED\", \"parent_quote_id\": null}', '120', NULL, '2025-09-04 21:33:01'),
(336, 2, 'APPROVE_QUOTE', 'QUOTE', 11, '{\"status\":\"SENT\"}', '{\"status\":\"APPROVED\",\"stock_updated\":true}', '127.0.0.1', NULL, '2025-09-04 21:33:01'),
(337, 2, 'UPDATE', 'QUOTE', 10, '{\"status\": \"SENT\", \"parent_quote_id\": null}', '{\"status\": \"APPROVED\", \"parent_quote_id\": null}', '126', NULL, '2025-09-04 21:33:18'),
(338, 2, 'APPROVE_QUOTE', 'QUOTE', 10, '{\"status\":\"SENT\"}', '{\"status\":\"APPROVED\",\"stock_updated\":true}', '127.0.0.1', NULL, '2025-09-04 21:33:18'),
(339, 2, 'UPDATE', 'QUOTE', 12, '{\"status\": \"DRAFT\", \"parent_quote_id\": null}', '{\"status\": \"DRAFT\", \"parent_quote_id\": null}', '136', NULL, '2025-09-04 21:36:56'),
(340, 2, 'UPDATE', 'QUOTE', 12, '{\"status\": \"DRAFT\", \"parent_quote_id\": null}', '{\"status\": \"SENT\", \"parent_quote_id\": null}', '141', NULL, '2025-09-04 21:37:50'),
(341, 2, 'UPDATE', 'QUOTE', 12, '{\"status\": \"SENT\", \"parent_quote_id\": null}', '{\"status\": \"REJECTED\", \"parent_quote_id\": null}', '145', NULL, '2025-09-04 21:38:11'),
(342, 2, 'UPDATE', 'QUOTE', 15, '{\"status\": \"DRAFT\", \"parent_quote_id\": null}', '{\"status\": \"SENT\", \"parent_quote_id\": null}', '193', NULL, '2025-09-04 22:04:17'),
(343, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '229', NULL, '2025-09-04 22:14:30'),
(344, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '229', NULL, '2025-09-04 22:14:30'),
(345, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '232', NULL, '2025-09-04 22:14:49'),
(346, 2, 'UPDATE', 'QUOTE', 14, '{\"status\": \"DRAFT\", \"parent_quote_id\": null}', '{\"status\": \"SENT\", \"parent_quote_id\": null}', '318', NULL, '2025-09-04 22:42:18'),
(347, 2, 'UPDATE', 'QUOTE', 14, '{\"status\": \"SENT\", \"parent_quote_id\": null}', '{\"status\": \"APPROVED\", \"parent_quote_id\": null}', '320', NULL, '2025-09-04 22:42:23'),
(348, 2, 'APPROVE_QUOTE', 'QUOTE', 14, '{\"status\":\"SENT\"}', '{\"status\":\"APPROVED\",\"stock_updated\":true}', '127.0.0.1', NULL, '2025-09-04 22:42:23'),
(349, 2, 'UPDATE', 'QUOTE', 13, '{\"status\": \"DRAFT\", \"parent_quote_id\": 12}', '{\"status\": \"SENT\", \"parent_quote_id\": 12}', '326', NULL, '2025-09-04 22:42:34'),
(350, 2, 'UPDATE', 'QUOTE', 13, '{\"status\": \"SENT\", \"parent_quote_id\": 12}', '{\"status\": \"APPROVED\", \"parent_quote_id\": 12}', '328', NULL, '2025-09-04 22:42:39'),
(351, 2, 'APPROVE_QUOTE', 'QUOTE', 13, '{\"status\":\"SENT\"}', '{\"status\":\"APPROVED\",\"stock_updated\":true}', '127.0.0.1', NULL, '2025-09-04 22:42:39'),
(352, 5, 'UPDATE', 'USER', 5, '{\"username\": \"fluna\", \"email\": \"lunaleon@local.com\", \"language\": \"es\"}', '{\"username\": \"fluna\", \"email\": \"lunaleon@local.com\", \"language\": \"es\"}', '480', NULL, '2025-09-04 23:07:53'),
(353, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '14', NULL, '2025-09-05 10:11:34'),
(354, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '14', NULL, '2025-09-05 10:11:34'),
(355, 5, 'UPDATE', 'USER', 5, '{\"username\": \"fluna\", \"email\": \"lunaleon@local.com\", \"language\": \"es\"}', '{\"username\": \"fluna\", \"email\": \"lunaleon@local.com\", \"language\": \"es\"}', '133', NULL, '2025-09-05 11:11:44'),
(356, 5, 'UPDATE', 'USER', 5, '{\"username\": \"fluna\", \"email\": \"lunaleon@local.com\", \"language\": \"es\"}', '{\"username\": \"fluna\", \"email\": \"lunaleon@local.com\", \"language\": \"es\"}', '142', NULL, '2025-09-05 11:15:15'),
(357, 5, 'UPDATE', 'USER', 5, '{\"username\": \"fluna\", \"email\": \"lunaleon@local.com\", \"language\": \"es\"}', '{\"username\": \"fluna\", \"email\": \"lunaleon@local.com\", \"language\": \"es\"}', '143', NULL, '2025-09-05 11:15:29'),
(358, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '151', NULL, '2025-09-05 11:16:36'),
(359, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '152', NULL, '2025-09-05 11:16:41'),
(360, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '153', NULL, '2025-09-05 11:16:46'),
(361, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '153', NULL, '2025-09-05 11:16:46'),
(362, 5, 'UPDATE', 'USER', 5, '{\"username\": \"fluna\", \"email\": \"lunaleon@local.com\", \"language\": \"es\"}', '{\"username\": \"fluna\", \"email\": \"lunaleon@local.com\", \"language\": \"es\"}', '164', NULL, '2025-09-05 11:21:55'),
(363, 5, 'UPDATE', 'USER', 5, '{\"username\": \"fluna\", \"email\": \"lunaleon@local.com\", \"language\": \"es\"}', '{\"username\": \"fluna\", \"email\": \"lunaleon@local.com\", \"language\": \"es\"}', '165', NULL, '2025-09-05 11:26:32'),
(364, 5, 'UPDATE', 'USER', 5, '{\"username\": \"fluna\", \"email\": \"lunaleon@local.com\", \"language\": \"es\"}', '{\"username\": \"fluna\", \"email\": \"lunaleon@local.com\", \"language\": \"es\"}', '166', NULL, '2025-09-05 11:26:36'),
(365, 5, 'UPDATE', 'USER', 5, '{\"username\": \"fluna\", \"email\": \"lunaleon@local.com\", \"language\": \"es\"}', '{\"username\": \"fluna\", \"email\": \"lunaleon@local.com\", \"language\": \"es\"}', '167', NULL, '2025-09-05 11:26:40'),
(366, 5, 'UPDATE', 'USER', 5, '{\"username\": \"fluna\", \"email\": \"lunaleon@local.com\", \"language\": \"es\"}', '{\"username\": \"fluna\", \"email\": \"lunaleon@local.com\", \"language\": \"es\"}', '208', NULL, '2025-09-05 11:33:43'),
(367, 5, 'UPDATE', 'USER', 5, '{\"username\": \"fluna\", \"email\": \"lunaleon@local.com\", \"language\": \"es\"}', '{\"username\": \"fluna\", \"email\": \"lunaleon@local.com\", \"language\": \"es\"}', '216', NULL, '2025-09-05 11:56:30'),
(368, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '232', NULL, '2025-09-05 13:32:24'),
(369, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '232', NULL, '2025-09-05 13:32:24'),
(370, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '319', NULL, '2025-09-05 21:50:10'),
(371, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '321', NULL, '2025-09-05 21:50:21'),
(372, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '321', NULL, '2025-09-05 21:50:21'),
(373, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '329', NULL, '2025-09-05 21:50:57'),
(374, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '329', NULL, '2025-09-05 21:50:57'),
(375, 5, 'UPDATE', 'USER', 5, '{\"username\": \"fluna\", \"email\": \"lunaleon@local.com\", \"language\": \"es\"}', '{\"username\": \"fluna\", \"email\": \"lunaleon@local.com\", \"language\": \"es\"}', '460', NULL, '2025-09-05 22:12:24'),
(376, 5, 'UPDATE', 'USER', 5, '{\"username\": \"fluna\", \"email\": \"lunaleon@local.com\", \"language\": \"es\"}', '{\"username\": \"fluna\", \"email\": \"lunaleon@local.com\", \"language\": \"es\"}', '472', NULL, '2025-09-05 22:13:33'),
(377, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '474', NULL, '2025-09-05 22:13:42'),
(378, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '602', NULL, '2025-09-06 22:53:30'),
(379, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '602', NULL, '2025-09-06 22:53:30'),
(380, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '15', NULL, '2025-09-06 23:01:01'),
(381, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '15', NULL, '2025-09-06 23:01:01'),
(382, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '16', NULL, '2025-09-06 23:05:20'),
(383, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '16', NULL, '2025-09-06 23:05:20'),
(384, 5, 'UPDATE', 'USER', 5, '{\"username\": \"fluna\", \"email\": \"lunaleon@local.com\", \"language\": \"es\"}', '{\"username\": \"fluna\", \"email\": \"lunaleon@local.com\", \"language\": \"es\"}', '318', NULL, '2025-09-06 23:50:05'),
(385, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '422', NULL, '2025-09-07 00:10:18'),
(386, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '454', NULL, '2025-09-07 00:12:41'),
(387, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '457', NULL, '2025-09-07 00:13:06'),
(388, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '459', NULL, '2025-09-07 00:13:17'),
(389, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '459', NULL, '2025-09-07 00:13:17'),
(390, 4, 'UPDATE', 'USER', 4, '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '475', NULL, '2025-09-07 00:20:16'),
(391, 4, 'UPDATE', 'USER', 4, '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '475', NULL, '2025-09-07 00:20:16'),
(392, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '484', NULL, '2025-09-07 00:25:08'),
(393, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '484', NULL, '2025-09-07 00:25:08'),
(394, 3, 'UPDATE', 'QUOTE', 18, '{\"status\": \"DRAFT\", \"parent_quote_id\": null}', '{\"status\": \"SENT\", \"parent_quote_id\": null}', '495', NULL, '2025-09-07 00:27:18'),
(395, 3, 'UPDATE', 'QUOTE', 18, '{\"status\": \"SENT\", \"parent_quote_id\": null}', '{\"status\": \"APPROVED\", \"parent_quote_id\": null}', '502', NULL, '2025-09-07 00:28:32'),
(396, 3, 'APPROVE_QUOTE', 'QUOTE', 18, '{\"status\":\"SENT\"}', '{\"status\":\"APPROVED\",\"stock_updated\":true}', '127.0.0.1', NULL, '2025-09-07 00:28:32'),
(397, 4, 'UPDATE', 'USER', 4, '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '543', NULL, '2025-09-07 00:42:17'),
(398, 4, 'UPDATE', 'USER', 4, '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '543', NULL, '2025-09-07 00:42:17'),
(399, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '598', NULL, '2025-09-07 00:53:21'),
(400, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '598', NULL, '2025-09-07 00:53:21'),
(401, 4, 'UPDATE', 'USER', 4, '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '673', NULL, '2025-09-07 01:06:03'),
(402, 4, 'UPDATE', 'USER', 4, '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '673', NULL, '2025-09-07 01:06:03'),
(403, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '678', NULL, '2025-09-07 01:06:24'),
(404, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '678', NULL, '2025-09-07 01:06:24'),
(405, 4, 'UPDATE', 'USER', 4, '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '686', NULL, '2025-09-07 01:06:54'),
(406, 4, 'UPDATE', 'USER', 4, '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '686', NULL, '2025-09-07 01:06:54'),
(407, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '692', NULL, '2025-09-07 01:07:33'),
(408, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '693', NULL, '2025-09-07 01:07:38'),
(409, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '693', NULL, '2025-09-07 01:07:38'),
(410, 5, 'UPDATE', 'USER', 5, '{\"username\": \"fluna\", \"email\": \"lunaleon@local.com\", \"language\": \"es\"}', '{\"username\": \"fluna\", \"email\": \"lunaleon@local.com\", \"language\": \"es\"}', '696', NULL, '2025-09-07 01:07:46'),
(411, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '710', NULL, '2025-09-07 10:20:36'),
(412, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '711', NULL, '2025-09-07 10:20:41'),
(413, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '711', NULL, '2025-09-07 10:20:41'),
(414, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '12', NULL, '2025-09-07 10:25:54'),
(415, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '13', NULL, '2025-09-07 10:26:00'),
(416, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '13', NULL, '2025-09-07 10:26:00'),
(417, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '35', NULL, '2025-09-07 10:29:50'),
(418, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '35', NULL, '2025-09-07 10:29:50'),
(419, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '55', NULL, '2025-09-07 10:35:02'),
(420, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '55', NULL, '2025-09-07 10:35:02'),
(421, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '71', NULL, '2025-09-07 10:49:08'),
(422, 4, 'UPDATE', 'USER', 4, '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '78', NULL, '2025-09-07 10:54:33'),
(423, 4, 'UPDATE', 'USER', 4, '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '79', NULL, '2025-09-07 10:54:40'),
(424, 4, 'UPDATE', 'USER', 4, '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '79', NULL, '2025-09-07 10:54:40'),
(425, 4, 'UPDATE', 'USER', 4, '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '83', NULL, '2025-09-07 10:57:33'),
(426, 4, 'UPDATE', 'USER', 4, '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '86', NULL, '2025-09-07 10:57:55'),
(427, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '89', NULL, '2025-09-07 10:58:10'),
(428, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '89', NULL, '2025-09-07 10:58:10'),
(429, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '92', NULL, '2025-09-07 10:58:18'),
(430, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '96', NULL, '2025-09-07 10:58:43'),
(431, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '96', NULL, '2025-09-07 10:58:43'),
(432, 5, 'UPDATE', 'USER', 5, '{\"username\": \"fluna\", \"email\": \"lunaleon@local.com\", \"language\": \"es\"}', '{\"username\": \"fluna\", \"email\": \"lunaleon@local.com\", \"language\": \"es\"}', '100', NULL, '2025-09-07 10:59:01'),
(433, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '513', NULL, '2025-09-07 13:24:56'),
(434, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '513', NULL, '2025-09-07 13:24:56'),
(435, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '518', NULL, '2025-09-07 13:29:00'),
(436, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '518', NULL, '2025-09-07 13:29:00'),
(437, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '532', NULL, '2025-09-07 13:35:19'),
(438, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '532', NULL, '2025-09-07 13:35:19'),
(439, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '597', NULL, '2025-09-07 13:58:08'),
(440, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '599', NULL, '2025-09-07 13:58:13'),
(441, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '599', NULL, '2025-09-07 13:58:13'),
(442, NULL, 'UPDATE', 'SETTINGS', 14, '{\"setting_key\": \"from_name\", \"setting_value\": \"CRM System\"}', '{\"setting_key\": \"from_name\", \"setting_value\": \"Athena CRM\"}', '602', NULL, '2025-09-07 13:58:26'),
(443, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '20', NULL, '2025-09-07 14:18:19'),
(444, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '20', NULL, '2025-09-07 14:18:19'),
(445, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '27', NULL, '2025-09-07 14:19:04');
INSERT INTO `audit_logs` (`audit_id`, `user_id`, `action`, `entity_type`, `entity_id`, `old_value`, `new_value`, `ip_address`, `user_agent`, `created_at`) VALUES
(446, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '29', NULL, '2025-09-07 14:19:12'),
(447, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '29', NULL, '2025-09-07 14:19:12'),
(448, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '38', NULL, '2025-09-07 14:32:24'),
(449, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '38', NULL, '2025-09-07 14:32:24'),
(450, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '46', NULL, '2025-09-07 14:37:39'),
(451, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '46', NULL, '2025-09-07 14:37:39'),
(452, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '57', NULL, '2025-09-07 14:40:20'),
(453, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '57', NULL, '2025-09-07 14:40:20'),
(454, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '62', NULL, '2025-09-07 14:40:41'),
(455, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '63', NULL, '2025-09-07 14:40:46'),
(456, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '63', NULL, '2025-09-07 14:40:46'),
(457, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '16', NULL, '2025-09-07 15:04:55'),
(458, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '17', NULL, '2025-09-07 15:05:01'),
(459, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '17', NULL, '2025-09-07 15:05:01'),
(460, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '20', NULL, '2025-09-07 15:05:39'),
(461, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '20', NULL, '2025-09-07 15:05:39'),
(462, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '26', NULL, '2025-09-07 15:07:19'),
(463, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '26', NULL, '2025-09-07 15:07:19'),
(464, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '52', NULL, '2025-09-07 15:36:01'),
(465, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '53', NULL, '2025-09-07 15:36:13'),
(466, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '53', NULL, '2025-09-07 15:36:13'),
(467, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '113', NULL, '2025-09-07 15:54:29'),
(468, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '113', NULL, '2025-09-07 15:54:29'),
(469, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"Leon\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '116', NULL, '2025-09-07 15:56:27'),
(470, 3, 'UPDATE', 'USER', 3, '{\"username\": \"Leon\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '117', NULL, '2025-09-07 15:56:35'),
(471, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '128', NULL, '2025-09-07 15:59:14'),
(472, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '129', NULL, '2025-09-07 15:59:24'),
(473, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '129', NULL, '2025-09-07 15:59:24'),
(474, NULL, 'UPDATE', 'SETTINGS', 1, '{\"setting_key\": \"company_display_name\", \"setting_value\": \"Entropic Networks\"}', '{\"setting_key\": \"company_display_name\", \"setting_value\": \"PAPITWO\"}', '132', NULL, '2025-09-07 16:00:13'),
(475, 3, 'UPDATE', 'QUOTE', 19, '{\"status\": \"DRAFT\", \"parent_quote_id\": 18}', '{\"status\": \"SENT\", \"parent_quote_id\": 18}', '144', NULL, '2025-09-07 16:02:43'),
(476, 3, 'UPDATE', 'QUOTE', 19, '{\"status\": \"SENT\", \"parent_quote_id\": 18}', '{\"status\": \"REJECTED\", \"parent_quote_id\": 18}', '148', NULL, '2025-09-07 16:03:09'),
(477, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '157', NULL, '2025-09-07 19:23:24'),
(478, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '158', NULL, '2025-09-07 19:23:29'),
(479, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '158', NULL, '2025-09-07 19:23:29'),
(480, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '37', NULL, '2025-09-07 20:51:44'),
(481, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '37', NULL, '2025-09-07 20:51:44'),
(482, 4, 'UPDATE', 'USER', 4, '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '40', NULL, '2025-09-07 20:52:44'),
(483, 4, 'UPDATE', 'USER', 4, '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '41', NULL, '2025-09-07 20:52:49'),
(484, 4, 'UPDATE', 'USER', 4, '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '41', NULL, '2025-09-07 20:52:49'),
(485, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '44', NULL, '2025-09-07 20:53:10'),
(486, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '45', NULL, '2025-09-07 20:53:15'),
(487, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '45', NULL, '2025-09-07 20:53:15'),
(488, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '78', NULL, '2025-09-08 19:09:18'),
(489, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '79', NULL, '2025-09-08 19:09:38'),
(490, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '100', NULL, '2025-09-09 22:24:26'),
(491, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '100', NULL, '2025-09-09 22:24:26'),
(492, NULL, 'UPDATE', 'SETTINGS', 1, '{\"setting_key\": \"company_display_name\", \"setting_value\": \"PAPITWO\"}', '{\"setting_key\": \"company_display_name\", \"setting_value\": \"Entropic Networks\"}', '103', NULL, '2025-09-09 22:24:43'),
(493, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '115', NULL, '2025-09-09 22:34:41'),
(494, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '118', NULL, '2025-09-09 22:34:46'),
(495, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '121', NULL, '2025-09-09 22:34:52'),
(496, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '124', NULL, '2025-09-09 22:35:04'),
(497, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '124', NULL, '2025-09-09 22:35:04'),
(498, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '139', NULL, '2025-09-09 22:43:16'),
(499, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '140', NULL, '2025-09-09 22:43:22'),
(500, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '140', NULL, '2025-09-09 22:43:22'),
(501, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '176', NULL, '2025-09-09 23:06:52'),
(502, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '176', NULL, '2025-09-09 23:06:52'),
(503, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '181', NULL, '2025-09-09 23:09:15'),
(504, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '182', NULL, '2025-09-09 23:09:19'),
(505, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '182', NULL, '2025-09-09 23:09:19'),
(506, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '197', NULL, '2025-09-09 23:19:38'),
(507, 4, 'UPDATE', 'USER', 4, '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '210', NULL, '2025-09-09 23:34:32'),
(508, 4, 'UPDATE', 'USER', 4, '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '212', NULL, '2025-09-09 23:34:37'),
(509, 4, 'UPDATE', 'USER', 4, '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '219', NULL, '2025-09-09 23:36:09'),
(510, 4, 'UPDATE', 'USER', 4, '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '295', NULL, '2025-09-10 00:06:04'),
(511, 4, 'UPDATE', 'USER', 4, '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '295', NULL, '2025-09-10 00:06:04'),
(512, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '305', NULL, '2025-09-10 09:49:32'),
(513, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '305', NULL, '2025-09-10 09:49:32'),
(514, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '313', NULL, '2025-09-10 15:59:45'),
(515, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '313', NULL, '2025-09-10 15:59:45'),
(516, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '317', NULL, '2025-09-10 16:05:31'),
(517, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '318', NULL, '2025-09-10 16:05:37'),
(518, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '318', NULL, '2025-09-10 16:05:37'),
(519, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '330', NULL, '2025-09-10 21:20:51'),
(520, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '330', NULL, '2025-09-10 21:20:51'),
(521, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '358', NULL, '2025-09-10 21:32:36'),
(522, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '359', NULL, '2025-09-10 21:32:42'),
(523, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '359', NULL, '2025-09-10 21:32:42'),
(524, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '385', NULL, '2025-09-10 21:36:01'),
(525, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '386', NULL, '2025-09-10 21:36:11'),
(526, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '387', NULL, '2025-09-10 21:36:16'),
(527, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '387', NULL, '2025-09-10 21:36:16'),
(528, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '498', NULL, '2025-09-10 21:58:20'),
(529, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '498', NULL, '2025-09-10 21:58:20'),
(530, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '508', NULL, '2025-09-10 22:04:15'),
(531, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '508', NULL, '2025-09-10 22:04:15'),
(532, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '573', NULL, '2025-09-10 22:20:44'),
(533, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '574', NULL, '2025-09-10 22:20:50'),
(534, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '574', NULL, '2025-09-10 22:20:50'),
(535, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '10', NULL, '2025-09-11 08:52:38'),
(536, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '10', NULL, '2025-09-11 08:52:38'),
(537, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '153', NULL, '2025-09-11 09:44:23'),
(538, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '153', NULL, '2025-09-11 09:44:23'),
(539, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '10', NULL, '2025-09-11 15:10:34'),
(540, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '10', NULL, '2025-09-11 15:10:34'),
(541, 2, 'UPDATE', 'QUOTE', 16, '{\"status\": \"DRAFT\", \"parent_quote_id\": null}', '{\"status\": \"DRAFT\", \"parent_quote_id\": null}', '15', NULL, '2025-09-11 15:10:50'),
(542, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '119', NULL, '2025-09-11 15:53:12'),
(543, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '120', NULL, '2025-09-11 15:53:18'),
(544, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '120', NULL, '2025-09-11 15:53:18'),
(545, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '130', NULL, '2025-09-11 15:55:13'),
(546, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '131', NULL, '2025-09-11 15:55:18'),
(547, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '131', NULL, '2025-09-11 15:55:18'),
(548, 5, 'UPDATE', 'USER', 5, '{\"username\": \"fluna\", \"email\": \"lunaleon@local.com\", \"language\": \"es\"}', '{\"username\": \"fluna\", \"email\": \"lunaleon@local.com\", \"language\": \"es\"}', '147', NULL, '2025-09-11 16:06:56'),
(549, 5, 'UPDATE', 'USER', 5, '{\"username\": \"fluna\", \"email\": \"lunaleon@local.com\", \"language\": \"es\"}', '{\"username\": \"fluna\", \"email\": \"lunaleon@local.com\", \"language\": \"es\"}', '148', NULL, '2025-09-11 16:07:00'),
(550, 5, 'UPDATE', 'USER', 5, '{\"username\": \"fluna\", \"email\": \"lunaleon@local.com\", \"language\": \"es\"}', '{\"username\": \"fluna\", \"email\": \"lunaleon@local.com\", \"language\": \"es\"}', '150', NULL, '2025-09-11 16:07:12'),
(551, 5, 'UPDATE', 'USER', 5, '{\"username\": \"fluna\", \"email\": \"lunaleon@local.com\", \"language\": \"es\"}', '{\"username\": \"fluna\", \"email\": \"lunaleon@local.com\", \"language\": \"es\"}', '151', NULL, '2025-09-11 16:07:24'),
(552, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '152', NULL, '2025-09-11 16:07:29'),
(553, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '152', NULL, '2025-09-11 16:07:29'),
(554, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '158', NULL, '2025-09-11 16:07:49'),
(555, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '159', NULL, '2025-09-11 16:07:54'),
(556, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '159', NULL, '2025-09-11 16:07:54'),
(557, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '164', NULL, '2025-09-11 16:08:18'),
(558, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '166', NULL, '2025-09-11 16:08:24'),
(559, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '166', NULL, '2025-09-11 16:08:24'),
(560, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '180', NULL, '2025-09-11 16:09:22'),
(561, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '181', NULL, '2025-09-11 16:09:27'),
(562, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '182', NULL, '2025-09-11 16:09:34'),
(563, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '182', NULL, '2025-09-11 16:09:34'),
(564, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '194', NULL, '2025-09-11 16:11:02'),
(565, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '194', NULL, '2025-09-11 16:11:02'),
(566, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '207', NULL, '2025-09-11 16:14:38'),
(567, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '208', NULL, '2025-09-11 16:14:42'),
(568, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '209', NULL, '2025-09-11 16:14:47'),
(569, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '209', NULL, '2025-09-11 16:14:47'),
(570, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '216', NULL, '2025-09-11 16:15:27'),
(571, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '218', NULL, '2025-09-11 16:15:33'),
(572, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '218', NULL, '2025-09-11 16:15:33'),
(573, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '225', NULL, '2025-09-11 19:30:41'),
(574, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '226', NULL, '2025-09-11 19:30:46'),
(575, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '226', NULL, '2025-09-11 19:30:46'),
(576, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '281', NULL, '2025-09-11 20:44:12'),
(577, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '281', NULL, '2025-09-11 20:44:12'),
(578, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '309', NULL, '2025-09-11 21:09:59'),
(579, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '309', NULL, '2025-09-11 21:09:59'),
(580, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '317', NULL, '2025-09-11 21:11:34'),
(581, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '318', NULL, '2025-09-11 21:11:39'),
(582, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '318', NULL, '2025-09-11 21:11:39'),
(583, NULL, 'UPDATE', 'SETTINGS', 6, '{\"setting_key\": \"timezone\", \"setting_value\": \"America/New_York\"}', '{\"setting_key\": \"timezone\", \"setting_value\": \"Europe/Madrid\"}', '386', NULL, '2025-09-11 21:31:18'),
(584, NULL, 'UPDATE', 'SETTINGS', 7, '{\"setting_key\": \"available_languages\", \"setting_value\": \"[\\\"es\\\"]\"}', '{\"setting_key\": \"available_languages\", \"setting_value\": \"[\\\"en\\\"]\"}', '387', NULL, '2025-09-11 21:31:33'),
(585, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '427', NULL, '2025-09-11 21:48:36'),
(586, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '427', NULL, '2025-09-11 21:48:36'),
(587, NULL, 'UPDATE', 'SETTINGS', 7, '{\"setting_key\": \"available_languages\", \"setting_value\": \"[\\\"en\\\"]\"}', '{\"setting_key\": \"available_languages\", \"setting_value\": \"[\\\"es\\\"]\"}', '431', NULL, '2025-09-11 21:53:03'),
(588, NULL, 'UPDATE', 'SETTINGS', 5, '{\"setting_key\": \"low_stock_threshold\", \"setting_value\": \"10\"}', '{\"setting_key\": \"low_stock_threshold\", \"setting_value\": \"12\"}', '444', NULL, '2025-09-11 22:03:56'),
(589, NULL, 'UPDATE', 'SETTINGS', 1, '{\"setting_key\": \"company_display_name\", \"setting_value\": \"Entropic Networks\"}', '{\"setting_key\": \"company_display_name\", \"setting_value\": \"Entropic Networks 2\"}', '446', NULL, '2025-09-11 22:06:06'),
(590, NULL, 'UPDATE', 'SETTINGS', 1, '{\"setting_key\": \"company_display_name\", \"setting_value\": \"Entropic Networks 2\"}', '{\"setting_key\": \"company_display_name\", \"setting_value\": \"Entropic Networks\"}', '447', NULL, '2025-09-11 22:06:14'),
(591, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '487', NULL, '2025-09-11 22:25:02'),
(592, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '488', NULL, '2025-09-11 22:25:07'),
(593, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '488', NULL, '2025-09-11 22:25:07'),
(594, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '512', NULL, '2025-09-11 22:34:54'),
(595, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '512', NULL, '2025-09-11 22:34:54'),
(596, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '521', NULL, '2025-09-11 22:36:41'),
(597, 4, 'UPDATE', 'USER', 4, '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '522', NULL, '2025-09-11 22:36:49'),
(598, 4, 'UPDATE', 'USER', 4, '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '523', NULL, '2025-09-11 22:36:54'),
(599, 4, 'UPDATE', 'USER', 4, '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '523', NULL, '2025-09-11 22:36:54'),
(600, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '561', NULL, '2025-09-11 22:46:00'),
(601, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '561', NULL, '2025-09-11 22:46:00'),
(602, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '585', NULL, '2025-09-12 13:49:06'),
(603, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '586', NULL, '2025-09-12 13:49:16'),
(604, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '586', NULL, '2025-09-12 13:49:16'),
(605, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '625', NULL, '2025-09-12 14:58:27'),
(606, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '625', NULL, '2025-09-12 14:58:27'),
(607, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '628', NULL, '2025-09-12 14:59:00'),
(608, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '628', NULL, '2025-09-12 14:59:00'),
(609, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '10', NULL, '2025-09-15 18:12:58'),
(610, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '10', NULL, '2025-09-15 18:12:58'),
(611, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '35', NULL, '2025-09-16 17:46:42'),
(612, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '36', NULL, '2025-09-16 17:46:47'),
(613, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '36', NULL, '2025-09-16 17:46:47'),
(614, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '40', NULL, '2025-09-16 17:57:15'),
(615, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '40', NULL, '2025-09-16 17:57:15'),
(616, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '44', NULL, '2025-09-16 18:02:00'),
(617, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '44', NULL, '2025-09-16 18:02:00'),
(618, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '173', NULL, '2025-09-16 19:00:14'),
(619, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '173', NULL, '2025-09-16 19:00:14'),
(620, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '187', NULL, '2025-09-16 19:19:38'),
(621, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '187', NULL, '2025-09-16 19:19:38'),
(622, 5, 'UPDATE', 'USER', 5, '{\"username\": \"fluna\", \"email\": \"lunaleon@local.com\", \"language\": \"es\"}', '{\"username\": \"fluna\", \"email\": \"lunaleon@local.com\", \"language\": \"es\"}', '241', NULL, '2025-09-16 19:54:35'),
(623, 6, 'INSERT', 'USER', 6, NULL, '{\"username\": \"jleon\", \"email\": \"jleon@local.com\"}', '348', NULL, '2025-09-16 21:29:17'),
(624, 6, 'UPDATE', 'USER', 6, '{\"username\": \"jleon\", \"email\": \"jleon@local.com\", \"language\": \"es\"}', '{\"username\": \"jleon\", \"email\": \"jleon@local.com\", \"language\": \"es\"}', '352', NULL, '2025-09-16 21:29:37'),
(625, 6, 'UPDATE', 'USER', 6, '{\"username\": \"jleon\", \"email\": \"jleon@local.com\", \"language\": \"es\"}', '{\"username\": \"jleon\", \"email\": \"jleon@local.com\", \"language\": \"es\"}', '352', NULL, '2025-09-16 21:29:37'),
(626, 4, 'UPDATE', 'USER', 4, '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '392', NULL, '2025-09-16 21:34:40'),
(627, 5, 'UPDATE', 'USER', 5, '{\"username\": \"fluna\", \"email\": \"lunaleon@local.com\", \"language\": \"es\"}', '{\"username\": \"fluna\", \"email\": \"lunaleon@local.com\", \"language\": \"es\"}', '394', NULL, '2025-09-16 21:34:44'),
(628, 6, 'UPDATE', 'USER', 6, '{\"username\": \"jleon\", \"email\": \"jleon@local.com\", \"language\": \"es\"}', '{\"username\": \"jleon\", \"email\": \"jleon@local.com\", \"language\": \"es\"}', '398', NULL, '2025-09-16 21:34:52'),
(629, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '425', NULL, '2025-09-16 21:37:34'),
(630, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '425', NULL, '2025-09-16 21:37:34'),
(631, 5, 'UPDATE', 'USER', 5, '{\"username\": \"fluna\", \"email\": \"lunaleon@local.com\", \"language\": \"es\"}', '{\"username\": \"fluna\", \"email\": \"lunaleon@local.com\", \"language\": \"es\"}', '436', NULL, '2025-09-16 21:45:55'),
(632, 5, 'UPDATE', 'USER', 5, '{\"username\": \"fluna\", \"email\": \"lunaleon@local.com\", \"language\": \"es\"}', '{\"username\": \"fluna\", \"email\": \"lunaleon@local.com\", \"language\": \"es\"}', '440', NULL, '2025-09-16 21:46:57'),
(633, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '501', NULL, '2025-09-16 23:24:24'),
(634, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '502', NULL, '2025-09-16 23:24:33'),
(635, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '503', NULL, '2025-09-16 23:24:38'),
(636, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '503', NULL, '2025-09-16 23:24:38'),
(637, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '10', NULL, '2025-09-18 19:26:12'),
(638, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '10', NULL, '2025-09-18 19:26:12'),
(639, NULL, 'INSERT', 'SETTINGS', 16, NULL, '{\"setting_key\": \"company_logo\", \"setting_value\": \"\"}', '166', NULL, '2025-09-18 20:32:54'),
(640, NULL, 'UPDATE', 'SETTINGS', 16, '{\"setting_key\": \"company_logo\", \"setting_value\": \"\"}', '{\"setting_key\": \"company_logo\", \"setting_value\": \"/crm-project/public/uploads/company_logo_1758227866.png\"}', '187', NULL, '2025-09-18 20:37:46'),
(641, NULL, 'INSERT', 'SETTINGS', 18, NULL, '{\"setting_key\": \"company_slogan\", \"setting_value\": \"\"}', '218', NULL, '2025-09-18 20:40:08'),
(642, NULL, 'UPDATE', 'SETTINGS', 18, '{\"setting_key\": \"company_slogan\", \"setting_value\": \"\"}', '{\"setting_key\": \"company_slogan\", \"setting_value\": \"La mejor y la que la tiene mas larga\"}', '228', NULL, '2025-09-18 20:40:52'),
(643, NULL, 'UPDATE', 'SETTINGS', 18, '{\"setting_key\": \"company_slogan\", \"setting_value\": \"La mejor y la que la tiene mas larga\"}', '{\"setting_key\": \"company_slogan\", \"setting_value\": \"La entropía es caos. Entropic Network es control.\"}', '272', NULL, '2025-09-18 21:12:10'),
(644, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '286', NULL, '2025-09-18 21:15:00'),
(645, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '286', NULL, '2025-09-18 21:15:00'),
(646, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '292', NULL, '2025-09-18 21:15:23'),
(647, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '292', NULL, '2025-09-18 21:15:23'),
(648, 6, 'UPDATE', 'USER', 6, '{\"username\": \"jleon\", \"email\": \"jleon@local.com\", \"language\": \"es\"}', '{\"username\": \"jleon\", \"email\": \"jleon@local.com\", \"language\": \"es\"}', '303', NULL, '2025-09-18 21:16:21'),
(649, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '306', NULL, '2025-09-18 21:16:44'),
(650, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '308', NULL, '2025-09-18 21:16:51'),
(651, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '308', NULL, '2025-09-18 21:16:51'),
(652, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '319', NULL, '2025-09-18 21:21:53'),
(653, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '319', NULL, '2025-09-18 21:21:53'),
(654, 4, 'UPDATE', 'USER', 4, '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '367', NULL, '2025-09-18 21:48:27'),
(655, 4, 'UPDATE', 'USER', 4, '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '368', NULL, '2025-09-18 21:48:33');
INSERT INTO `audit_logs` (`audit_id`, `user_id`, `action`, `entity_type`, `entity_id`, `old_value`, `new_value`, `ip_address`, `user_agent`, `created_at`) VALUES
(656, 4, 'UPDATE', 'USER', 4, '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '368', NULL, '2025-09-18 21:48:33'),
(657, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '373', NULL, '2025-09-18 21:49:55'),
(658, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '373', NULL, '2025-09-18 21:49:55'),
(659, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '382', NULL, '2025-09-18 21:54:34'),
(660, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '383', NULL, '2025-09-18 21:54:39'),
(661, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '383', NULL, '2025-09-18 21:54:39'),
(662, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '391', NULL, '2025-09-18 21:55:14'),
(663, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '393', NULL, '2025-09-18 21:55:33'),
(664, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '393', NULL, '2025-09-18 21:55:33'),
(665, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '400', NULL, '2025-09-18 21:59:29'),
(666, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '400', NULL, '2025-09-18 21:59:29'),
(667, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '405', NULL, '2025-09-18 21:59:48'),
(668, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '405', NULL, '2025-09-18 21:59:48'),
(669, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '417', NULL, '2025-09-18 22:01:22'),
(670, 5, 'UPDATE', 'USER', 5, '{\"username\": \"fluna\", \"email\": \"lunaleon@local.com\", \"language\": \"es\"}', '{\"username\": \"fluna\", \"email\": \"lunaleon@local.com\", \"language\": \"es\"}', '423', NULL, '2025-09-18 22:01:53'),
(671, 6, 'UPDATE', 'USER', 6, '{\"username\": \"jleon\", \"email\": \"jleon@local.com\", \"language\": \"es\"}', '{\"username\": \"jleon\", \"email\": \"jleon@local.com\", \"language\": \"es\"}', '427', NULL, '2025-09-18 22:02:11'),
(672, 6, 'UPDATE', 'USER', 6, '{\"username\": \"jleon\", \"email\": \"jleon@local.com\", \"language\": \"es\"}', '{\"username\": \"jleon\", \"email\": \"jleon@local.com\", \"language\": \"es\"}', '459', NULL, '2025-09-18 22:05:17'),
(673, 4, 'UPDATE', 'USER', 4, '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '{\"username\": \"ggaleano\", \"email\": \"galeano@local.com\", \"language\": \"es\"}', '460', NULL, '2025-09-18 22:05:20'),
(674, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '461', NULL, '2025-09-18 22:05:23'),
(675, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '512', NULL, '2025-09-18 22:27:34'),
(676, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '512', NULL, '2025-09-18 22:27:34'),
(677, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '548', NULL, '2025-09-19 23:33:54'),
(678, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '548', NULL, '2025-09-19 23:33:54'),
(679, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '695', NULL, '2025-09-20 01:52:15'),
(680, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '695', NULL, '2025-09-20 01:52:15'),
(681, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '750', NULL, '2025-09-20 02:32:20'),
(682, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '750', NULL, '2025-09-20 02:32:20'),
(683, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '763', NULL, '2025-09-20 02:44:53'),
(684, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '763', NULL, '2025-09-20 02:44:53'),
(685, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '767', NULL, '2025-09-20 02:48:44'),
(686, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '767', NULL, '2025-09-20 02:48:44'),
(687, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '770', NULL, '2025-09-20 02:49:30'),
(688, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '771', NULL, '2025-09-20 02:49:36'),
(689, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '771', NULL, '2025-09-20 02:49:36'),
(690, 2, 'UPDATE', 'QUOTE', 20, '{\"status\": \"DRAFT\", \"parent_quote_id\": null}', '{\"status\": \"SENT\", \"parent_quote_id\": null}', '28', NULL, '2025-09-20 03:12:39'),
(691, 2, 'UPDATE', 'QUOTE', 20, '{\"status\": \"SENT\", \"parent_quote_id\": null}', '{\"status\": \"APPROVED\", \"parent_quote_id\": null}', '31', NULL, '2025-09-20 03:12:44'),
(692, 2, 'APPROVE_QUOTE', 'QUOTE', 20, '{\"status\":\"SENT\"}', '{\"status\":\"APPROVED\",\"stock_updated\":true}', '127.0.0.1', NULL, '2025-09-20 03:12:44'),
(693, 2, 'UPDATE', 'QUOTE', 15, '{\"status\": \"SENT\", \"parent_quote_id\": null}', '{\"status\": \"APPROVED\", \"parent_quote_id\": null}', '42', NULL, '2025-09-20 03:16:09'),
(694, 2, 'APPROVE_QUOTE', 'QUOTE', 15, '{\"status\":\"SENT\"}', '{\"status\":\"APPROVED\",\"stock_updated\":true}', '127.0.0.1', NULL, '2025-09-20 03:16:09'),
(695, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '10', NULL, '2025-09-20 19:10:39'),
(696, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '11', NULL, '2025-09-20 19:10:47'),
(697, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '11', NULL, '2025-09-20 19:10:47'),
(698, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '59', NULL, '2025-09-20 19:21:29'),
(699, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '149', NULL, '2025-09-20 19:37:35'),
(700, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '150', NULL, '2025-09-20 19:37:45'),
(701, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '150', NULL, '2025-09-20 19:37:45'),
(702, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '169', NULL, '2025-09-20 19:39:32'),
(703, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '174', NULL, '2025-09-20 19:40:07'),
(704, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '175', NULL, '2025-09-20 19:40:14'),
(705, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '175', NULL, '2025-09-20 19:40:14'),
(706, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '179', NULL, '2025-09-20 19:40:41'),
(707, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '182', NULL, '2025-09-20 19:40:48'),
(708, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '182', NULL, '2025-09-20 19:40:48'),
(709, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '185', NULL, '2025-09-20 19:43:03'),
(710, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '185', NULL, '2025-09-20 19:43:03'),
(711, 5, 'UPDATE', 'USER', 5, '{\"username\": \"fluna\", \"email\": \"lunaleon@local.com\", \"language\": \"es\"}', '{\"username\": \"fluna\", \"email\": \"lunaleon@local.com\", \"language\": \"es\"}', '190', NULL, '2025-09-20 19:43:23'),
(712, 5, 'UPDATE', 'USER', 5, '{\"username\": \"fluna\", \"email\": \"lunaleon@local.com\", \"language\": \"es\"}', '{\"username\": \"fluna\", \"email\": \"lunaleon@local.com\", \"language\": \"es\"}', '191', NULL, '2025-09-20 19:43:43'),
(713, 5, 'UPDATE', 'USER', 5, '{\"username\": \"fluna\", \"email\": \"lunaleon@local.com\", \"language\": \"es\"}', '{\"username\": \"fluna\", \"email\": \"lunaleon@local.com\", \"language\": \"es\"}', '193', NULL, '2025-09-20 19:43:52'),
(714, 5, 'UPDATE', 'USER', 5, '{\"username\": \"fluna\", \"email\": \"lunaleon@local.com\", \"language\": \"es\"}', '{\"username\": \"fluna\", \"email\": \"lunaleon@local.com\", \"language\": \"es\"}', '193', NULL, '2025-09-20 19:43:52'),
(715, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '196', NULL, '2025-09-20 19:44:12'),
(716, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '196', NULL, '2025-09-20 19:44:12'),
(717, 7, 'INSERT', 'USER', 7, NULL, '{\"username\": \"lleon\", \"email\": \"lleon@local.com\"}', '200', NULL, '2025-09-20 19:45:06'),
(718, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '216', NULL, '2025-09-20 19:45:31'),
(719, 7, 'UPDATE', 'USER', 7, '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '218', NULL, '2025-09-20 19:45:49'),
(720, 7, 'UPDATE', 'USER', 7, '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '218', NULL, '2025-09-20 19:45:49'),
(721, 7, 'UPDATE', 'USER', 7, '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '238', NULL, '2025-09-20 19:48:52'),
(722, 7, 'UPDATE', 'USER', 7, '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '239', NULL, '2025-09-20 19:49:01'),
(723, 7, 'UPDATE', 'USER', 7, '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '239', NULL, '2025-09-20 19:49:01'),
(724, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '14', NULL, '2025-09-20 21:23:57'),
(725, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '14', NULL, '2025-09-20 21:23:57'),
(726, 7, 'UPDATE', 'USER', 7, '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '51', NULL, '2025-09-20 21:31:09'),
(727, 7, 'UPDATE', 'USER', 7, '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '53', NULL, '2025-09-20 21:31:15'),
(728, 7, 'UPDATE', 'USER', 7, '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '55', NULL, '2025-09-20 21:31:36'),
(729, 7, 'UPDATE', 'USER', 7, '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '77', NULL, '2025-09-20 21:36:43'),
(730, 7, 'UPDATE', 'USER', 7, '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '78', NULL, '2025-09-20 21:36:50'),
(731, 7, 'UPDATE', 'USER', 7, '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '78', NULL, '2025-09-20 21:36:50'),
(732, 7, 'UPDATE', 'USER', 7, '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '87', NULL, '2025-09-20 21:58:04'),
(733, 7, 'UPDATE', 'USER', 7, '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '88', NULL, '2025-09-20 21:58:10'),
(734, 7, 'UPDATE', 'USER', 7, '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '88', NULL, '2025-09-20 21:58:10'),
(735, 7, 'UPDATE', 'USER', 7, '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '91', NULL, '2025-09-20 22:04:06'),
(736, 7, 'FORCE_PASSWORD_CHANGE', 'USER', 7, '{\"force_password_change\": true}', '{\"force_password_change\": false}', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:140.0) Gecko/20100101 Firefox/140.0', '2025-09-20 22:04:06'),
(737, 7, 'UPDATE', 'USER', 7, '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '12', NULL, '2025-09-20 22:05:33'),
(738, 7, 'UPDATE', 'USER', 7, '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '13', NULL, '2025-09-20 22:05:40'),
(739, 7, 'UPDATE', 'USER', 7, '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '14', NULL, '2025-09-20 22:05:47'),
(740, 7, 'UPDATE', 'USER', 7, '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '16', NULL, '2025-09-20 22:06:06'),
(741, 7, 'UPDATE', 'USER', 7, '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '51', NULL, '2025-09-20 22:06:41'),
(742, 7, 'UPDATE', 'USER', 7, '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '52', NULL, '2025-09-20 22:06:48'),
(743, 7, 'UPDATE', 'USER', 7, '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '52', NULL, '2025-09-20 22:06:48'),
(744, 7, 'UPDATE', 'USER', 7, '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '54', NULL, '2025-09-20 22:07:06'),
(745, 7, 'FORCE_PASSWORD_CHANGE', 'USER', 7, '{\"force_password_change\": true}', '{\"force_password_change\": false}', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:140.0) Gecko/20100101 Firefox/140.0', '2025-09-20 22:07:06'),
(746, 7, 'UPDATE', 'USER', 7, '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '70', NULL, '2025-09-20 22:07:37'),
(747, 7, 'UPDATE', 'USER', 7, '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '72', NULL, '2025-09-20 22:08:50'),
(748, 7, 'FORCE_PASSWORD_CHANGE', 'USER', 7, '{\"force_password_change\": true}', '{\"force_password_change\": false}', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:140.0) Gecko/20100101 Firefox/140.0', '2025-09-20 22:08:50'),
(749, 7, 'UPDATE', 'USER', 7, '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '88', NULL, '2025-09-20 22:11:05'),
(750, 7, 'UPDATE', 'USER', 7, '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '92', NULL, '2025-09-20 22:11:35'),
(751, 7, 'FORCE_PASSWORD_CHANGE', 'USER', 7, '{\"force_password_change\": true}', '{\"force_password_change\": false}', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:140.0) Gecko/20100101 Firefox/140.0', '2025-09-20 22:11:35'),
(752, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '144', NULL, '2025-09-20 23:57:38'),
(753, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '144', NULL, '2025-09-20 23:57:38'),
(754, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '152', NULL, '2025-09-21 00:01:32'),
(755, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '153', NULL, '2025-09-21 00:01:37'),
(756, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '153', NULL, '2025-09-21 00:01:37'),
(757, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '297', NULL, '2025-09-21 00:25:13'),
(758, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '301', NULL, '2025-09-21 00:26:31'),
(759, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '313', NULL, '2025-09-21 00:28:50'),
(760, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '316', NULL, '2025-09-21 00:29:17'),
(761, 2, 'FORCE_PASSWORD_CHANGE', 'USER', 2, '{\"force_password_change\": true}', '{\"force_password_change\": false}', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:140.0) Gecko/20100101 Firefox/140.0', '2025-09-21 00:29:17'),
(762, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '339', NULL, '2025-09-21 00:41:40'),
(763, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '342', NULL, '2025-09-21 00:45:47'),
(764, 2, 'FORCE_PASSWORD_CHANGE', 'USER', 2, '{\"force_password_change\": true}', '{\"force_password_change\": false}', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:140.0) Gecko/20100101 Firefox/140.0', '2025-09-21 00:45:47'),
(765, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '360', NULL, '2025-09-21 00:49:46'),
(766, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '362', NULL, '2025-09-21 00:51:24'),
(767, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '362', NULL, '2025-09-21 00:51:24'),
(768, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '366', NULL, '2025-09-21 00:56:13'),
(769, 2, 'FORCE_PASSWORD_CHANGE', 'USER', 2, '{\"force_password_change\": true}', '{\"force_password_change\": false}', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:140.0) Gecko/20100101 Firefox/140.0', '2025-09-21 00:56:13'),
(770, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '369', NULL, '2025-09-21 00:56:40'),
(771, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '369', NULL, '2025-09-21 00:56:40'),
(772, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '384', NULL, '2025-09-21 00:56:53'),
(773, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '386', NULL, '2025-09-21 00:57:10'),
(774, 2, 'FORCE_PASSWORD_CHANGE', 'USER', 2, '{\"force_password_change\": true}', '{\"force_password_change\": false}', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:140.0) Gecko/20100101 Firefox/140.0', '2025-09-21 00:57:10'),
(775, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '415', NULL, '2025-09-21 09:45:55'),
(776, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '415', NULL, '2025-09-21 09:45:55'),
(777, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '465', NULL, '2025-09-21 10:11:09'),
(778, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '468', NULL, '2025-09-21 10:16:11'),
(779, 2, 'FORCE_PASSWORD_CHANGE', 'USER', 2, '{\"force_password_change\": true}', '{\"force_password_change\": false}', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:140.0) Gecko/20100101 Firefox/140.0', '2025-09-21 10:16:11'),
(780, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '10', NULL, '2025-09-21 12:54:45'),
(781, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '11', NULL, '2025-09-21 12:54:50'),
(782, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '12', NULL, '2025-09-21 12:54:59'),
(783, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '13', NULL, '2025-09-21 12:55:06'),
(784, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '14', NULL, '2025-09-21 12:55:12'),
(785, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '14', NULL, '2025-09-21 12:55:12'),
(786, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '10', NULL, '2025-09-21 13:14:34'),
(787, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '11', NULL, '2025-09-21 13:14:39'),
(788, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '11', NULL, '2025-09-21 13:14:39'),
(789, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '76', NULL, '2025-09-21 13:54:38'),
(790, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '97', NULL, '2025-09-21 14:26:24'),
(791, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '110', NULL, '2025-09-21 14:46:07'),
(792, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '119', NULL, '2025-09-21 14:50:45'),
(793, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '123', NULL, '2025-09-21 14:51:28'),
(794, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '126', NULL, '2025-09-21 14:51:36'),
(795, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '135', NULL, '2025-09-21 14:52:38'),
(796, 7, 'UPDATE', 'USER', 7, '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '142', NULL, '2025-09-21 14:53:36'),
(797, 7, 'UPDATE', 'USER', 7, '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '143', NULL, '2025-09-21 14:53:43'),
(798, 7, 'UPDATE', 'USER', 7, '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '{\"username\": \"lleon\", \"email\": \"lleon@local.com\", \"language\": \"es\"}', '144', NULL, '2025-09-21 14:53:48'),
(799, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '145', NULL, '2025-09-21 14:54:00'),
(800, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '146', NULL, '2025-09-21 14:54:09'),
(801, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '152', NULL, '2025-09-21 14:56:29'),
(802, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '152', NULL, '2025-09-21 14:56:29'),
(803, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '191', NULL, '2025-09-21 14:56:58'),
(804, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '210', NULL, '2025-09-21 14:57:56'),
(805, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '210', NULL, '2025-09-21 14:57:56'),
(806, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '213', NULL, '2025-09-21 14:58:23'),
(807, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '219', NULL, '2025-09-21 14:58:57'),
(808, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '222', NULL, '2025-09-21 14:59:21'),
(809, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '222', NULL, '2025-09-21 14:59:21'),
(810, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '224', NULL, '2025-09-21 14:59:44'),
(811, 2, 'FORCE_PASSWORD_CHANGE', 'USER', 2, '{\"force_password_change\": true}', '{\"force_password_change\": false}', '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:140.0) Gecko/20100101 Firefox/140.0', '2025-09-21 14:59:44'),
(812, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '228', NULL, '2025-09-21 15:00:05'),
(813, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '228', NULL, '2025-09-21 15:00:05'),
(814, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '232', NULL, '2025-09-21 15:02:56'),
(815, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '233', NULL, '2025-09-21 15:03:01'),
(816, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '233', NULL, '2025-09-21 15:03:01'),
(817, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '245', NULL, '2025-09-21 15:26:19'),
(818, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '245', NULL, '2025-09-21 15:26:19'),
(819, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '259', NULL, '2025-09-22 21:07:50'),
(820, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '259', NULL, '2025-09-22 21:07:50'),
(821, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '262', NULL, '2025-09-22 21:22:51'),
(822, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '262', NULL, '2025-09-22 21:22:51'),
(823, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '267', NULL, '2025-09-22 21:44:32'),
(824, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '267', NULL, '2025-09-22 21:44:32'),
(825, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '16', NULL, '2025-09-22 22:49:38'),
(826, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '16', NULL, '2025-09-22 22:49:38'),
(827, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '41', NULL, '2025-09-22 22:51:51'),
(828, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '41', NULL, '2025-09-22 22:51:51'),
(829, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '44', NULL, '2025-09-22 22:52:25'),
(830, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '44', NULL, '2025-09-22 22:52:25'),
(831, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '10', NULL, '2025-09-23 17:27:59'),
(832, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '10', NULL, '2025-09-23 17:27:59'),
(833, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '14', NULL, '2025-09-23 17:28:19'),
(834, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '14', NULL, '2025-09-23 17:28:19'),
(835, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '29', NULL, '2025-09-23 17:31:33'),
(836, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '29', NULL, '2025-09-23 17:31:33'),
(837, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '10', NULL, '2025-09-23 23:11:42'),
(838, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '10', NULL, '2025-09-23 23:11:42'),
(839, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '13', NULL, '2025-09-23 23:12:07'),
(840, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '13', NULL, '2025-09-23 23:12:07'),
(841, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '134', NULL, '2025-09-24 00:20:59'),
(842, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '158', NULL, '2025-09-24 00:42:07'),
(843, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '11', NULL, '2025-09-26 01:54:10'),
(844, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '11', NULL, '2025-09-26 01:54:10'),
(845, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '76', NULL, '2025-09-26 02:12:35'),
(846, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '77', NULL, '2025-09-26 02:12:57'),
(847, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '77', NULL, '2025-09-26 02:12:57'),
(848, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '100', NULL, '2025-09-26 02:18:39'),
(849, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '100', NULL, '2025-09-26 02:18:39'),
(850, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '10', NULL, '2025-09-27 17:21:14'),
(851, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '10', NULL, '2025-09-27 17:21:14'),
(852, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '10', NULL, '2025-09-27 21:07:40'),
(853, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '10', NULL, '2025-09-27 21:07:40'),
(854, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '13', NULL, '2025-09-27 21:12:25'),
(855, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '13', NULL, '2025-09-27 21:12:25'),
(856, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '26', NULL, '2025-09-27 21:16:14'),
(857, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '26', NULL, '2025-09-27 21:16:14'),
(858, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '33', NULL, '2025-09-27 21:19:59'),
(859, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '33', NULL, '2025-09-27 21:19:59'),
(860, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '10', NULL, '2025-09-29 20:10:11'),
(861, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '10', NULL, '2025-09-29 20:10:11'),
(862, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '10', NULL, '2025-10-04 19:26:46'),
(863, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '10', NULL, '2025-10-04 19:26:46'),
(864, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '15', NULL, '2025-10-04 19:27:23'),
(865, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '16', NULL, '2025-10-04 19:27:31'),
(866, 2, 'UPDATE', 'USER', 2, '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '{\"username\": \"aleon\", \"email\": \"itbkup24@gmail.com\", \"language\": \"es\"}', '16', NULL, '2025-10-04 19:27:31');
INSERT INTO `audit_logs` (`audit_id`, `user_id`, `action`, `entity_type`, `entity_id`, `old_value`, `new_value`, `ip_address`, `user_agent`, `created_at`) VALUES
(867, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '25', NULL, '2025-10-04 19:29:28'),
(868, 3, 'UPDATE', 'USER', 3, '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '{\"username\": \"janderson\", \"email\": \"anderson@local.com\", \"language\": \"es\"}', '25', NULL, '2025-10-04 19:29:28');

-- --------------------------------------------------------

--
-- Table structure for table `backup_requests`
--

CREATE TABLE `backup_requests` (
  `backup_id` bigint(20) UNSIGNED NOT NULL,
  `requested_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('PENDING','COMPLETED','FAILED') NOT NULL DEFAULT 'PENDING',
  `created_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `backup_requests`
--

INSERT INTO `backup_requests` (`backup_id`, `requested_at`, `status`, `created_by`) VALUES
(1, '2025-08-19 10:18:17', 'PENDING', NULL);

--
-- Triggers `backup_requests`
--
DELIMITER $$
CREATE TRIGGER `backup_requests_after_insert` AFTER INSERT ON `backup_requests` FOR EACH ROW BEGIN
    INSERT INTO audit_logs (user_id, action, entity_type, entity_id, new_value, ip_address, user_agent, created_at)
    VALUES (NEW.created_by, 'INSERT', 'BACKUP_REQUEST', NEW.backup_id,
            JSON_OBJECT('status', NEW.status, 'requested_at', NEW.requested_at),
            CONNECTION_ID(), NULL, NOW());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `backup_requests_after_update` AFTER UPDATE ON `backup_requests` FOR EACH ROW BEGIN
    INSERT INTO audit_logs (user_id, action, entity_type, entity_id, old_value, new_value, ip_address, user_agent, created_at)
    VALUES (NEW.created_by, 'UPDATE', 'BACKUP_REQUEST', NEW.backup_id,
            JSON_OBJECT('status', OLD.status),
            JSON_OBJECT('status', NEW.status),
            CONNECTION_ID(), NULL, NOW());
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `contact_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `tax_id` varchar(50) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`client_id`, `company_name`, `contact_name`, `email`, `phone`, `address`, `tax_id`, `created_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Entropic Networks', 'Yaixa Cano', 'cano@entropic.com', '504 99665423', 'Modificar St para que sea automatico, pero en el futuro', '15', 2, '2025-08-22 23:18:41', '2025-08-22 23:18:41', NULL),
(2, 'Lionixx entertaiment', 'Luna Leon', 'itbkup24@gmail.com', '62354676543', 'Modificar St para que sea automatico, pero en el futuro', '15', 2, '2025-08-27 22:33:39', '2025-08-29 13:25:27', NULL),
(3, 'Trasnportes Galeano', 'Julio La Verdura de la casa', 'fabigaleon26@gmail.com', '65423879086', 'San pedro jula cerca de cerrocigalpa', '12', 2, '2025-09-04 21:09:45', '2025-09-04 21:09:45', NULL),
(4, 'JYP', 'Im Naeyon', 'nayeon@twice.com', '65423879086', 'Seul, South Korea, St. 23, Dongsehn building', '15', 2, '2025-09-11 15:20:29', '2025-09-11 15:20:29', NULL),
(5, 'Firm', 'Thess Smith', 'thess@local.com', '65432128974', 'Madrid, Barrio 5 central', '12', 2, '2025-09-27 17:23:00', '2025-09-27 17:23:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `client_activities`
--

CREATE TABLE `client_activities` (
  `activity_id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `quote_id` bigint(20) UNSIGNED DEFAULT NULL,
  `activity_type` enum('QUOTE_CREATED','QUOTE_APPROVED','CONTACT') NOT NULL DEFAULT 'QUOTE_CREATED',
  `activity_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`details`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `client_activities`
--

INSERT INTO `client_activities` (`activity_id`, `client_id`, `quote_id`, `activity_type`, `activity_date`, `details`) VALUES
(1, 1, 1, 'QUOTE_CREATED', '2025-08-29 00:37:00', '{\"total_amount\":2553.125}'),
(2, 2, 2, 'QUOTE_CREATED', '2025-08-29 00:41:08', '{\"total_amount\":3413.125}'),
(3, 2, 2, 'QUOTE_APPROVED', '2025-08-29 21:02:59', '{\"approved_amount\":\"3413.13\",\"approved_by\":2}'),
(4, 2, 4, 'QUOTE_APPROVED', '2025-08-29 21:05:28', '{\"approved_amount\":\"3413.13\",\"approved_by\":2}'),
(5, 3, 11, 'QUOTE_APPROVED', '2025-09-04 21:33:01', '{\"total_amount\":\"1248.30\"}'),
(6, 1, 10, 'QUOTE_APPROVED', '2025-09-04 21:33:18', '{\"total_amount\":\"131.40\"}'),
(7, 3, 14, 'QUOTE_APPROVED', '2025-09-04 22:42:23', '{\"total_amount\":\"1612.50\"}'),
(8, 3, 13, 'QUOTE_APPROVED', '2025-09-04 22:42:39', '{\"total_amount\":\"1612.50\"}'),
(9, 2, 18, 'QUOTE_APPROVED', '2025-09-07 00:28:32', '{\"total_amount\":\"643.86\"}'),
(10, 4, 20, 'QUOTE_APPROVED', '2025-09-20 03:12:44', '{\"total_amount\":\"537.50\"}'),
(11, 1, 15, 'QUOTE_APPROVED', '2025-09-20 03:16:09', '{\"total_amount\":\"2553.13\"}');

-- --------------------------------------------------------

--
-- Table structure for table `materialized_client_purchase_patterns`
--

CREATE TABLE `materialized_client_purchase_patterns` (
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `total_spend` decimal(10,2) NOT NULL,
  `purchase_count` bigint(20) NOT NULL,
  `last_purchase_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `materialized_sales_performance`
--

CREATE TABLE `materialized_sales_performance` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `total_quotes` bigint(20) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `conversion_rate` decimal(5,2) NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `materialized_sales_trends`
--

CREATE TABLE `materialized_sales_trends` (
  `month` varchar(7) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `total_quotes` bigint(20) NOT NULL,
  `average_discount` decimal(5,2) NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `permission_name` varchar(100) NOT NULL,
  `module` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`permission_id`, `permission_name`, `module`, `description`, `created_at`) VALUES
(1, 'edit_own_profile', 'users', 'Edit own user profile', '2025-08-18 23:31:48'),
(2, 'reset_user_password', 'users', 'Reset user passwords', '2025-08-18 23:31:48'),
(3, 'view_sales_reports', 'reports', 'View sales performance and trends', '2025-08-18 23:31:48'),
(4, 'view_client_reports', 'reports', 'View client activity and patterns', '2025-08-18 23:31:48'),
(5, 'view_product_reports', 'reports', 'View product performance and categories', '2025-08-18 23:31:48'),
(6, 'view_compliance_reports', 'reports', 'View audit logs and security posture', '2025-08-18 23:31:48'),
(7, 'request_access', 'access', 'Request additional permissions', '2025-08-18 23:31:48'),
(8, 'manage_access_requests', 'access', 'Review access requests', '2025-08-18 23:31:48'),
(9, 'manage_settings', 'settings', 'Manage company settings', '2025-08-18 23:31:48'),
(10, 'renew_quotes', 'quotes', 'Renew existing quotes', '2025-08-18 23:31:48'),
(11, 'manage_backups', 'backups', 'Manage backup requests', '2025-08-18 23:31:48'),
(12, 'view_clients', 'clients', 'View client details', '2025-08-18 23:31:48'),
(13, 'create_quotes', 'quotes', 'Create new quotes', '2025-08-18 23:31:48'),
(14, 'add_clients', 'clients', 'Add new clients', '2025-09-27 21:25:45'),
(15, 'edit_clients', 'clients', 'Edit existing clients', '2025-09-27 21:25:45'),
(16, 'delete_clients', 'clients', 'Delete clients', '2025-09-27 21:25:45'),
(17, 'add_products', 'products', 'Add new products', '2025-09-27 21:28:46'),
(18, 'edit_products', 'products', 'Edit existing products', '2025-09-27 21:28:46'),
(19, 'delete_products', 'products', 'Delete products', '2025-09-27 21:28:46'),
(20, 'view_products', 'products', 'View products list', '2025-09-27 21:28:46');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `sku` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `tax_rate` decimal(5,2) NOT NULL,
  `stock_quantity` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `category_id`, `product_name`, `sku`, `price`, `tax_rate`, `stock_quantity`, `created_at`, `updated_at`) VALUES
(1, 1, 'Dell G15 Ryzen 7 2 nucleos', 'inv-tech-002', 2000.00, 7.50, 200, '2025-08-23 00:11:04', '2025-08-23 00:12:14'),
(2, 1, 'Dell G15 Intel Core I7', 'INV-TECH-001', 2500.00, 7.50, 80, '2025-08-23 00:13:41', '2025-09-21 15:03:22'),
(4, 2, 'Silla gamer reclinable pro', 'INV-MOB-001', 1000.00, 7.50, 99, '2025-08-23 00:14:38', '2025-09-20 03:12:44'),
(5, 1, 'Honor 200 Pro', 'inv-tech-003', 1200.00, 9.50, 199, '2025-08-28 23:17:13', '2025-09-04 21:33:01'),
(6, 2, 'Libro La song de resistance', 'INV-LIT-001', 50.00, 9.50, 136, '2025-08-28 23:54:17', '2025-09-11 09:24:47');

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

CREATE TABLE `product_categories` (
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`category_id`, `category_name`, `description`, `created_at`) VALUES
(1, 'Tecnologia', 'Productos y aparatos electronicos de tecnologia personal', '2025-08-23 00:08:25'),
(2, 'Aparatos de Oficina', 'Tecnologia para oficina', '2025-08-23 00:12:58');

-- --------------------------------------------------------

--
-- Table structure for table `quotes`
--

CREATE TABLE `quotes` (
  `quote_id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `parent_quote_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quote_number` varchar(50) NOT NULL,
  `status` enum('DRAFT','SENT','APPROVED','REJECTED') NOT NULL DEFAULT 'DRAFT',
  `stock_updated` tinyint(1) NOT NULL DEFAULT 0,
  `total_amount` decimal(10,2) NOT NULL,
  `issue_date` date NOT NULL,
  `expiry_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quotes`
--

INSERT INTO `quotes` (`quote_id`, `client_id`, `user_id`, `parent_quote_id`, `quote_number`, `status`, `stock_updated`, `total_amount`, `issue_date`, `expiry_date`, `created_at`, `updated_at`) VALUES
(1, 1, 2, NULL, 'Q20250001', 'REJECTED', 0, 2553.13, '2025-08-28', '2025-09-04', '2025-08-29 00:37:00', '2025-08-29 22:14:00'),
(2, 2, 2, NULL, 'Q20250002', 'APPROVED', 1, 3413.13, '2025-08-28', '2025-09-04', '2025-08-29 00:41:08', '2025-08-29 21:02:59'),
(3, 2, 2, NULL, 'Q20250003', 'REJECTED', 0, 3397.00, '2025-08-29', '2025-09-05', '2025-08-29 13:27:19', '2025-08-29 22:28:30'),
(4, 2, 2, 2, 'Q20250004', 'APPROVED', 1, 3413.13, '2025-08-29', '2025-09-05', '2025-08-29 13:27:55', '2025-08-29 21:05:28'),
(5, 2, 2, NULL, 'QT2025-0001', 'REJECTED', 0, 54.75, '2025-08-29', '2025-09-05', '2025-08-29 21:26:30', '2025-09-04 21:11:35'),
(10, 1, 2, NULL, 'QT2025-0002', 'APPROVED', 1, 131.40, '2025-08-29', '2025-09-05', '2025-08-29 22:39:04', '2025-09-04 21:33:18'),
(11, 3, 2, NULL, 'QT2025-0003', 'APPROVED', 1, 1248.30, '2025-09-04', '2025-09-11', '2025-09-04 21:12:01', '2025-09-04 21:33:01'),
(12, 3, 2, NULL, 'QT2025-0004', 'REJECTED', 0, 1612.50, '2025-09-04', '2025-09-11', '2025-09-04 21:24:29', '2025-09-04 21:38:11'),
(13, 3, 2, 12, 'QT2025-0005', 'APPROVED', 1, 1612.50, '2025-09-04', '2025-09-11', '2025-09-04 21:50:16', '2025-09-04 22:42:39'),
(14, 3, 2, NULL, 'QT2025-0006', 'APPROVED', 1, 1612.50, '2025-09-04', '2025-09-11', '2025-09-04 21:54:36', '2025-09-04 22:42:23'),
(15, 1, 2, NULL, 'QT2025-0007', 'APPROVED', 1, 2553.13, '2025-09-04', '2025-09-11', '2025-09-04 21:55:02', '2025-09-20 03:16:09'),
(16, 2, 2, NULL, 'QT2025-0008', 'DRAFT', 0, 3413.13, '2025-09-04', '2025-09-11', '2025-09-04 21:57:48', '2025-09-11 15:10:50'),
(17, 2, 2, NULL, 'QT2025-0009', 'DRAFT', 0, 967.50, '2025-09-04', '2025-09-11', '2025-09-04 22:03:48', '2025-09-04 22:03:48'),
(18, 2, 3, NULL, 'QT2025-0010', 'APPROVED', 1, 643.86, '2025-09-06', '2025-09-13', '2025-09-07 00:27:12', '2025-09-07 00:28:32'),
(19, 2, 3, 18, 'QT2025-0011', 'REJECTED', 0, 643.86, '2025-09-07', '2025-09-14', '2025-09-07 16:02:18', '2025-09-07 16:03:09'),
(20, 4, 2, NULL, 'QT2025-0012', 'APPROVED', 1, 537.50, '2025-09-11', '2025-09-18', '2025-09-11 15:21:12', '2025-09-20 03:12:44');

--
-- Triggers `quotes`
--
DELIMITER $$
CREATE TRIGGER `quotes_after_update` AFTER UPDATE ON `quotes` FOR EACH ROW BEGIN
            IF NEW.status = 'APPROVED' AND OLD.status != 'APPROVED' AND NEW.stock_updated = FALSE THEN
                UPDATE products p
                JOIN quote_items qi ON p.product_id = qi.product_id
                SET p.stock_quantity = p.stock_quantity - qi.quantity
                WHERE qi.quote_id = NEW.quote_id
                AND p.stock_quantity >= qi.quantity;
                
                UPDATE quotes
                SET stock_updated = TRUE
                WHERE quote_id = NEW.quote_id;
                
                INSERT INTO client_activities (client_id, quote_id, activity_type, activity_date, details)
                VALUES (NEW.client_id, NEW.quote_id, 'QUOTE_APPROVED', NOW(),
                        JSON_OBJECT('total_amount', NEW.total_amount));
                        
                INSERT INTO audit_logs (user_id, action, entity_type, entity_id, old_value, new_value, ip_address, user_agent, created_at)
                VALUES (NEW.user_id, 'STOCK_UPDATE', 'QUOTE', NEW.quote_id,
                        JSON_OBJECT('status', OLD.status),
                        JSON_OBJECT('status', NEW.status, 'stock_updated', NEW.stock_updated),
                        CONNECTION_ID(), NULL, NOW());
            END IF;
            
            INSERT INTO audit_logs (user_id, action, entity_type, entity_id, old_value, new_value, ip_address, user_agent, created_at)
            VALUES (NEW.user_id, 'UPDATE', 'QUOTE', NEW.quote_id,
                    JSON_OBJECT('status', OLD.status, 'parent_quote_id', OLD.parent_quote_id),
                    JSON_OBJECT('status', NEW.status, 'parent_quote_id', NEW.parent_quote_id),
                    CONNECTION_ID(), NULL, NOW());
        END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `quote_items`
--

CREATE TABLE `quote_items` (
  `quote_item_id` bigint(20) UNSIGNED NOT NULL,
  `quote_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `discount` decimal(5,2) NOT NULL,
  `tax_amount` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quote_items`
--

INSERT INTO `quote_items` (`quote_item_id`, `quote_id`, `product_id`, `quantity`, `unit_price`, `discount`, `tax_amount`, `subtotal`, `created_at`) VALUES
(1, 1, 2, 1, 2500.00, 5.00, 178.13, 2553.13, '2025-08-29 00:37:00'),
(2, 2, 4, 1, 1000.00, 10.00, 67.50, 967.50, '2025-08-29 00:41:08'),
(3, 2, 2, 1, 2500.00, 9.00, 170.63, 2445.63, '2025-08-29 00:41:08'),
(7, 3, 2, 1, 2500.00, 10.00, 168.75, 2418.75, '2025-08-29 13:27:38'),
(8, 3, 4, 1, 1000.00, 9.00, 68.25, 978.25, '2025-08-29 13:27:38'),
(9, 4, 4, 1, 1000.00, 10.00, 67.50, 967.50, '2025-08-29 13:27:55'),
(10, 4, 2, 1, 2500.00, 9.00, 170.63, 2445.63, '2025-08-29 13:27:55'),
(11, 5, 6, 1, 50.00, 0.00, 4.75, 54.75, '2025-08-29 21:26:30'),
(16, 10, 6, 3, 50.00, 20.00, 11.40, 131.40, '2025-08-29 22:39:04'),
(17, 11, 5, 1, 1200.00, 5.00, 108.30, 1248.30, '2025-09-04 21:12:01'),
(22, 12, 2, 2, 1000.00, 25.00, 112.50, 1612.50, '2025-09-04 21:36:56'),
(23, 13, 2, 2, 1000.00, 25.00, 112.50, 1612.50, '2025-09-04 21:50:16'),
(24, 14, 2, 2, 1000.00, 25.00, 112.50, 1612.50, '2025-09-04 21:54:36'),
(25, 15, 2, 1, 2500.00, 5.00, 178.13, 2553.13, '2025-09-04 21:55:02'),
(28, 17, 4, 1, 1000.00, 10.00, 67.50, 967.50, '2025-09-04 22:03:48'),
(29, 18, 6, 12, 50.00, 2.00, 55.86, 643.86, '2025-09-07 00:27:12'),
(30, 19, 6, 12, 50.00, 2.00, 55.86, 643.86, '2025-09-07 16:02:18'),
(31, 16, 4, 1, 1000.00, 10.00, 67.50, 967.50, '2025-09-11 15:10:50'),
(32, 16, 2, 1, 2500.00, 9.00, 170.63, 2445.63, '2025-09-11 15:10:50'),
(33, 20, 4, 1, 1000.00, 50.00, 37.50, 537.50, '2025-09-11 15:21:12');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `role_name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`, `description`, `created_at`) VALUES
(1, 'Admin', 'Full access to all modules and settings', '2025-08-18 23:31:48'),
(2, 'Seller', 'Access to sales-related modules and reports', '2025-08-18 23:31:48'),
(11, 'Auditor', 'Role access to reports and event logs of platform', '2025-08-29 09:00:49');

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_permissions`
--

INSERT INTO `role_permissions` (`role_id`, `permission_id`, `created_at`) VALUES
(1, 1, '2025-09-11 22:55:14'),
(1, 2, '2025-09-11 22:55:14'),
(1, 3, '2025-09-11 22:55:14'),
(1, 4, '2025-09-11 22:55:14'),
(1, 5, '2025-09-11 22:55:14'),
(1, 6, '2025-09-11 22:55:14'),
(1, 7, '2025-09-11 22:55:14'),
(1, 8, '2025-09-11 22:55:14'),
(1, 9, '2025-09-11 22:55:14'),
(1, 10, '2025-09-11 22:55:14'),
(1, 11, '2025-09-11 22:55:14'),
(1, 12, '2025-09-11 22:55:14'),
(1, 13, '2025-09-11 22:55:14'),
(2, 1, '2025-09-27 21:31:36'),
(2, 3, '2025-09-27 21:31:36'),
(2, 4, '2025-09-27 21:31:36'),
(2, 10, '2025-09-27 21:31:36'),
(2, 12, '2025-09-27 21:31:36'),
(2, 13, '2025-09-27 21:31:36'),
(2, 17, '2025-09-27 21:31:36'),
(2, 18, '2025-09-27 21:31:36'),
(2, 20, '2025-09-27 21:31:36'),
(11, 1, '2025-09-07 20:54:39'),
(11, 6, '2025-09-07 20:54:39'),
(11, 7, '2025-09-18 21:54:44'),
(11, 12, '2025-09-07 20:54:39');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `setting_id` bigint(20) UNSIGNED NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`setting_id`, `setting_key`, `setting_value`, `created_at`, `updated_at`) VALUES
(1, 'company_display_name', 'Entropic Networks', '2025-08-18 23:31:48', '2025-09-11 22:06:14'),
(2, 'default_tax_rate', '0.00', '2025-08-18 23:31:48', '2025-08-22 23:48:56'),
(3, 'quote_expiry_days', '7', '2025-08-18 23:31:48', '2025-08-22 23:48:56'),
(4, 'quote_expiry_notification_days', '3', '2025-08-18 23:31:48', '2025-08-22 23:48:56'),
(5, 'low_stock_threshold', '12', '2025-08-18 23:31:48', '2025-09-11 22:03:56'),
(6, 'timezone', 'Europe/Madrid', '2025-08-18 23:31:48', '2025-09-11 21:31:18'),
(7, 'available_languages', '[\"es\"]', '2025-08-18 23:31:48', '2025-09-11 21:53:03'),
(8, 'smtp_host', 'smtp.gmail.com', '2025-08-18 23:31:48', '2025-08-29 13:23:55'),
(9, 'smtp_port', '587', '2025-08-18 23:31:48', '2025-08-22 23:48:56'),
(10, 'smtp_username', 'itbkup24@gmail.com', '2025-08-18 23:31:48', '2025-08-29 13:23:55'),
(11, 'smtp_password', 'rgti ikam yrvi bpjy', '2025-08-18 23:31:48', '2025-08-29 13:23:55'),
(12, 'smtp_encryption', 'TLS', '2025-08-18 23:31:48', '2025-08-22 23:48:56'),
(13, 'from_email', 'itbkup24@gmail.com', '2025-08-18 23:31:48', '2025-08-29 13:23:55'),
(14, 'from_name', 'Athena CRM', '2025-08-18 23:31:48', '2025-09-07 13:58:26'),
(15, 'backup_time', '02:00:00', '2025-08-18 23:31:48', '2025-08-22 23:48:56'),
(16, 'company_logo', '/crm-project/public/uploads/company_logo_1758227866.png', '2025-09-18 20:32:54', '2025-09-18 20:37:46'),
(18, 'company_slogan', 'La entropía es caos. Entropic Network es control.', '2025-09-18 20:40:08', '2025-09-18 21:12:10');

--
-- Triggers `settings`
--
DELIMITER $$
CREATE TRIGGER `settings_after_insert` AFTER INSERT ON `settings` FOR EACH ROW BEGIN
    INSERT INTO audit_logs (user_id, action, entity_type, entity_id, new_value, ip_address, user_agent, created_at)
    VALUES (NULL, 'INSERT', 'SETTINGS', NEW.setting_id,
            JSON_OBJECT('setting_key', NEW.setting_key, 'setting_value', NEW.setting_value),
            CONNECTION_ID(), NULL, NOW());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `settings_after_update` AFTER UPDATE ON `settings` FOR EACH ROW BEGIN
    INSERT INTO audit_logs (user_id, action, entity_type, entity_id, old_value, new_value, ip_address, user_agent, created_at)
    VALUES (NULL, 'UPDATE', 'SETTINGS', NEW.setting_id,
            JSON_OBJECT('setting_key', OLD.setting_key, 'setting_value', OLD.setting_value),
            JSON_OBJECT('setting_key', NEW.setting_key, 'setting_value', NEW.setting_value),
            CONNECTION_ID(), NULL, NOW());
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `display_name` varchar(100) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `language` varchar(10) NOT NULL DEFAULT 'es',
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `failed_login_attempts` int(11) NOT NULL DEFAULT 0,
  `locked_until` timestamp NULL DEFAULT NULL,
  `force_password_change` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_login_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password_hash`, `display_name`, `profile_picture`, `language`, `role_id`, `is_admin`, `is_active`, `failed_login_attempts`, `locked_until`, `force_password_change`, `created_at`, `updated_at`, `last_login_at`) VALUES
(2, 'aleon', 'itbkup24@gmail.com', '$2y$12$WTBuzcuB5IECDERkdJH7QONhv1JyagxOmvShZVOH0ZzY6fkt0XGzS', 'Leon Jassiel', 'crm-project/public/uploads/68bd632418510_1757242148.png', 'es', 1, 1, 1, 0, NULL, 0, '2025-08-19 22:18:19', '2025-10-04 19:27:31', '2025-10-04 19:27:31'),
(3, 'janderson', 'anderson@local.com', '$2y$12$RSUq.gRazxjanpZ7iL.CsuTjuEvNfSJDm3nkwJA7WviuEMKk0z3iS', 'Anderson Zelaya', 'crm-project/public/uploads/68bd654a1617d_1757242698.png', 'es', 2, 0, 1, 0, NULL, 0, '2025-08-20 22:16:27', '2025-10-04 19:29:28', '2025-10-04 19:29:28'),
(4, 'ggaleano', 'galeano@local.com', '$2y$12$Ic7gXg4vWfvnV4lHaaaBmu7Cl8nAQq2uRt/q.v8EETqiUT8NgjHSe', 'Fabiola Galeano', 'crm-project/public/uploads/68bd65338cc22_1757242675.png', 'es', 11, 0, 0, 0, NULL, 0, '2025-08-23 21:20:25', '2025-09-18 22:05:20', '2025-09-18 21:48:33'),
(5, 'fluna', 'lunaleon@local.com', '$2y$12$eEoTsikubUXQl0eQPf9zluoE1NJVheoML1G9qmrnpKtRUXJPkTOEm', 'Luna Fabiola', 'crm-project/public/uploads/68bd6575b5599_1757242741.png', 'es', 11, 0, 1, 0, NULL, 0, '2025-08-26 23:33:48', '2025-09-20 19:43:52', '2025-09-20 19:43:52'),
(6, 'jleon', 'jleon@local.com', '$2y$12$BDCoCI.XChlSEycWWH/Le.S3Hr3jpJrODpT/B9UvFdOCh/pRHCjim', 'Mateo Jassiel Leon Galeano', NULL, 'es', 11, 0, 0, 0, NULL, 0, '2025-09-16 21:29:17', '2025-09-18 22:05:17', '2025-09-16 21:29:37'),
(7, 'lleon', 'lleon@local.com', '$2y$12$/hL8VoxfgJmfYFwZ4obGwepEGZ4G5fJgz1hQi40/4deP8zTV6HRxC', 'Luna Aurora Leon', NULL, 'es', 1, 0, 1, 3, '2025-09-21 15:23:48', 0, '2025-09-20 19:45:06', '2025-09-21 14:53:48', '2025-09-20 22:06:48');

--
-- Triggers `users`
--
DELIMITER $$
CREATE TRIGGER `users_after_insert` AFTER INSERT ON `users` FOR EACH ROW BEGIN
    INSERT INTO audit_logs (user_id, action, entity_type, entity_id, new_value, ip_address, user_agent, created_at)
    VALUES (NEW.user_id, 'INSERT', 'USER', NEW.user_id, JSON_OBJECT('username', NEW.username, 'email', NEW.email), CONNECTION_ID(), NULL, NOW());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `users_after_update` AFTER UPDATE ON `users` FOR EACH ROW BEGIN
    INSERT INTO audit_logs (user_id, action, entity_type, entity_id, old_value, new_value, ip_address, user_agent, created_at)
    VALUES (NEW.user_id, 'UPDATE', 'USER', NEW.user_id,
            JSON_OBJECT('username', OLD.username, 'email', OLD.email, 'language', OLD.language),
            JSON_OBJECT('username', NEW.username, 'email', NEW.email, 'language', NEW.language),
            CONNECTION_ID(), NULL, NOW());
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_audit_logs`
-- (See below for the actual view)
--
CREATE TABLE `vw_audit_logs` (
`audit_id` bigint(20) unsigned
,`user_id` bigint(20) unsigned
,`action` varchar(100)
,`entity_type` varchar(50)
,`entity_id` bigint(20) unsigned
,`old_value` longtext
,`new_value` longtext
,`ip_address` varchar(45)
,`user_agent` text
,`created_at` timestamp
,`username` varchar(50)
,`display_name` varchar(100)
,`role_name` varchar(50)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_category_summary`
-- (See below for the actual view)
--
CREATE TABLE `vw_category_summary` (
`category_id` bigint(20) unsigned
,`category_name` varchar(100)
,`product_count` bigint(21)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_clients`
-- (See below for the actual view)
--
CREATE TABLE `vw_clients` (
`client_id` bigint(20) unsigned
,`company_name` varchar(255)
,`contact_name` varchar(100)
,`email` varchar(255)
,`phone` varchar(20)
,`created_at` timestamp
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_client_activity`
-- (See below for the actual view)
--
CREATE TABLE `vw_client_activity` (
`client_id` bigint(20) unsigned
,`company_name` varchar(255)
,`last_quote_date` timestamp
,`total_quotes` bigint(21)
,`total_amount` decimal(32,2)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_client_product_preferences`
-- (See below for the actual view)
--
CREATE TABLE `vw_client_product_preferences` (
`client_id` bigint(20) unsigned
,`company_name` varchar(255)
,`product_id` bigint(20) unsigned
,`product_name` varchar(255)
,`total_quantity` decimal(32,0)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_client_purchase_patterns`
-- (See below for the actual view)
--
CREATE TABLE `vw_client_purchase_patterns` (
`client_id` bigint(20) unsigned
,`company_name` varchar(255)
,`total_spend` decimal(32,2)
,`purchase_count` bigint(21)
,`last_purchase_date` timestamp
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_expiring_quotes`
-- (See below for the actual view)
--
CREATE TABLE `vw_expiring_quotes` (
`quote_id` bigint(20) unsigned
,`quote_number` varchar(50)
,`client_id` bigint(20) unsigned
,`client_name` varchar(255)
,`expiry_date` date
,`days_until_expiry` int(7)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_low_stock_products`
-- (See below for the actual view)
--
CREATE TABLE `vw_low_stock_products` (
`product_id` bigint(20) unsigned
,`product_name` varchar(255)
,`sku` varchar(50)
,`stock_quantity` int(11)
,`category_name` varchar(100)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_products`
-- (See below for the actual view)
--
CREATE TABLE `vw_products` (
`product_id` bigint(20) unsigned
,`product_name` varchar(255)
,`sku` varchar(50)
,`price` decimal(10,2)
,`tax_rate` decimal(5,2)
,`stock_quantity` int(11)
,`category_name` varchar(100)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_product_performance`
-- (See below for the actual view)
--
CREATE TABLE `vw_product_performance` (
`product_id` bigint(20) unsigned
,`product_name` varchar(255)
,`sku` varchar(50)
,`total_sold` decimal(32,0)
,`stock_quantity` int(11)
,`category_name` varchar(100)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_quotes`
-- (See below for the actual view)
--
CREATE TABLE `vw_quotes` (
`quote_id` bigint(20) unsigned
,`quote_number` varchar(50)
,`status` enum('DRAFT','SENT','APPROVED','REJECTED')
,`total_amount` decimal(10,2)
,`issue_date` date
,`expiry_date` date
,`client_name` varchar(255)
,`username` varchar(50)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_quote_items`
-- (See below for the actual view)
--
CREATE TABLE `vw_quote_items` (
`quote_item_id` bigint(20) unsigned
,`quote_id` bigint(20) unsigned
,`quantity` int(11)
,`unit_price` decimal(10,2)
,`discount` decimal(5,2)
,`tax_amount` decimal(10,2)
,`subtotal` decimal(10,2)
,`product_name` varchar(255)
,`sku` varchar(50)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_sales_performance`
-- (See below for the actual view)
--
CREATE TABLE `vw_sales_performance` (
`user_id` bigint(20) unsigned
,`username` varchar(50)
,`total_quotes` bigint(21)
,`total_amount` decimal(32,2)
,`conversion_rate` decimal(6,5)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_sales_trends`
-- (See below for the actual view)
--
CREATE TABLE `vw_sales_trends` (
`month` varchar(7)
,`total_amount` decimal(32,2)
,`total_quotes` bigint(21)
,`average_discount` decimal(9,6)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_security_metrics`
-- (See below for the actual view)
--
CREATE TABLE `vw_security_metrics` (
`failed_login_count` decimal(32,0)
,`locked_accounts` bigint(21)
,`inactive_accounts` bigint(21)
,`permission_changes` bigint(21)
,`audit_log_count` bigint(21)
,`last_security_event` timestamp
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_security_posture`
-- (See below for the actual view)
--
CREATE TABLE `vw_security_posture` (
`failed_login_count` decimal(32,0)
,`locked_accounts` decimal(22,0)
,`inactive_accounts` decimal(22,0)
,`permission_changes` bigint(21)
,`audit_log_count` bigint(21)
,`last_security_event` timestamp
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_settings`
-- (See below for the actual view)
--
CREATE TABLE `vw_settings` (
`setting_id` bigint(20) unsigned
,`setting_key` varchar(100)
,`setting_value` text
,`created_at` timestamp
,`updated_at` timestamp
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_top_clients`
-- (See below for the actual view)
--
CREATE TABLE `vw_top_clients` (
`client_id` bigint(20) unsigned
,`company_name` varchar(255)
,`total_spend` decimal(32,2)
,`purchase_count` bigint(21)
,`rank` bigint(21)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_users`
-- (See below for the actual view)
--
CREATE TABLE `vw_users` (
`user_id` bigint(20) unsigned
,`username` varchar(50)
,`email` varchar(255)
,`display_name` varchar(100)
,`profile_picture` varchar(255)
,`language` varchar(10)
,`role_id` bigint(20) unsigned
,`is_admin` tinyint(1)
,`is_active` tinyint(1)
,`created_at` timestamp
,`updated_at` timestamp
,`last_login_at` timestamp
,`role_name` varchar(50)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_user_profile`
-- (See below for the actual view)
--
CREATE TABLE `vw_user_profile` (
`user_id` bigint(20) unsigned
,`username` varchar(50)
,`email` varchar(255)
,`display_name` varchar(100)
,`profile_picture` varchar(255)
,`language` varchar(10)
,`role_id` bigint(20) unsigned
,`is_admin` tinyint(1)
,`is_active` tinyint(1)
,`force_password_change` tinyint(1)
,`created_at` timestamp
,`updated_at` timestamp
,`last_login_at` timestamp
,`failed_login_attempts` int(11)
,`locked_until` timestamp
,`role_name` varchar(50)
,`role_description` text
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_user_roles`
-- (See below for the actual view)
--
CREATE TABLE `vw_user_roles` (
`role_id` bigint(20) unsigned
,`role_name` varchar(50)
,`description` text
,`created_at` timestamp
);

-- --------------------------------------------------------

--
-- Structure for view `vw_audit_logs`
--
DROP TABLE IF EXISTS `vw_audit_logs`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_audit_logs`  AS SELECT `a`.`audit_id` AS `audit_id`, `a`.`user_id` AS `user_id`, `a`.`action` AS `action`, `a`.`entity_type` AS `entity_type`, `a`.`entity_id` AS `entity_id`, `a`.`old_value` AS `old_value`, `a`.`new_value` AS `new_value`, `a`.`ip_address` AS `ip_address`, `a`.`user_agent` AS `user_agent`, `a`.`created_at` AS `created_at`, coalesce(`u`.`username`,'SYSTEM') AS `username`, `u`.`display_name` AS `display_name`, `r`.`role_name` AS `role_name` FROM ((`audit_logs` `a` left join `users` `u` on(`a`.`user_id` = `u`.`user_id`)) left join `roles` `r` on(`u`.`role_id` = `r`.`role_id`)) ORDER BY `a`.`created_at` DESC ;

-- --------------------------------------------------------

--
-- Structure for view `vw_category_summary`
--
DROP TABLE IF EXISTS `vw_category_summary`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_category_summary`  AS SELECT `pc`.`category_id` AS `category_id`, `pc`.`category_name` AS `category_name`, count(`p`.`product_id`) AS `product_count` FROM (`product_categories` `pc` left join `products` `p` on(`pc`.`category_id` = `p`.`category_id`)) GROUP BY `pc`.`category_id`, `pc`.`category_name` ;

-- --------------------------------------------------------

--
-- Structure for view `vw_clients`
--
DROP TABLE IF EXISTS `vw_clients`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_clients`  AS SELECT `clients`.`client_id` AS `client_id`, `clients`.`company_name` AS `company_name`, `clients`.`contact_name` AS `contact_name`, `clients`.`email` AS `email`, `clients`.`phone` AS `phone`, `clients`.`created_at` AS `created_at` FROM `clients` WHERE `clients`.`deleted_at` is null ;

-- --------------------------------------------------------

--
-- Structure for view `vw_client_activity`
--
DROP TABLE IF EXISTS `vw_client_activity`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_client_activity`  AS SELECT `c`.`client_id` AS `client_id`, `c`.`company_name` AS `company_name`, max(`q`.`created_at`) AS `last_quote_date`, count(`q`.`quote_id`) AS `total_quotes`, sum(`q`.`total_amount`) AS `total_amount` FROM (`clients` `c` left join `quotes` `q` on(`c`.`client_id` = `q`.`client_id`)) GROUP BY `c`.`client_id`, `c`.`company_name` ;

-- --------------------------------------------------------

--
-- Structure for view `vw_client_product_preferences`
--
DROP TABLE IF EXISTS `vw_client_product_preferences`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_client_product_preferences`  AS SELECT `c`.`client_id` AS `client_id`, `c`.`company_name` AS `company_name`, `p`.`product_id` AS `product_id`, `p`.`product_name` AS `product_name`, sum(`qi`.`quantity`) AS `total_quantity` FROM (((`clients` `c` join `quotes` `q` on(`c`.`client_id` = `q`.`client_id`)) join `quote_items` `qi` on(`q`.`quote_id` = `qi`.`quote_id`)) join `products` `p` on(`qi`.`product_id` = `p`.`product_id`)) WHERE `q`.`status` = 'APPROVED' GROUP BY `c`.`client_id`, `c`.`company_name`, `p`.`product_id`, `p`.`product_name` ;

-- --------------------------------------------------------

--
-- Structure for view `vw_client_purchase_patterns`
--
DROP TABLE IF EXISTS `vw_client_purchase_patterns`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_client_purchase_patterns`  AS SELECT `c`.`client_id` AS `client_id`, `c`.`company_name` AS `company_name`, sum(`q`.`total_amount`) AS `total_spend`, count(`q`.`quote_id`) AS `purchase_count`, max(`q`.`created_at`) AS `last_purchase_date` FROM (`clients` `c` left join `quotes` `q` on(`c`.`client_id` = `q`.`client_id`)) WHERE `q`.`status` = 'APPROVED' GROUP BY `c`.`client_id`, `c`.`company_name` ;

-- --------------------------------------------------------

--
-- Structure for view `vw_expiring_quotes`
--
DROP TABLE IF EXISTS `vw_expiring_quotes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_expiring_quotes`  AS SELECT `q`.`quote_id` AS `quote_id`, `q`.`quote_number` AS `quote_number`, `q`.`client_id` AS `client_id`, `c`.`company_name` AS `client_name`, `q`.`expiry_date` AS `expiry_date`, to_days(`q`.`expiry_date`) - to_days(curdate()) AS `days_until_expiry` FROM ((`quotes` `q` join `clients` `c` on(`q`.`client_id` = `c`.`client_id`)) join `settings` `s` on(`s`.`setting_key` = 'quote_expiry_notification_days')) WHERE `q`.`status` = 'SENT' AND `q`.`expiry_date` <= curdate() + interval cast(`s`.`setting_value` as unsigned) day ;

-- --------------------------------------------------------

--
-- Structure for view `vw_low_stock_products`
--
DROP TABLE IF EXISTS `vw_low_stock_products`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_low_stock_products`  AS SELECT `p`.`product_id` AS `product_id`, `p`.`product_name` AS `product_name`, `p`.`sku` AS `sku`, `p`.`stock_quantity` AS `stock_quantity`, `pc`.`category_name` AS `category_name` FROM ((`products` `p` join `product_categories` `pc` on(`p`.`category_id` = `pc`.`category_id`)) join `settings` `s` on(`s`.`setting_key` = 'low_stock_threshold')) WHERE `p`.`stock_quantity` < cast(`s`.`setting_value` as unsigned) ;

-- --------------------------------------------------------

--
-- Structure for view `vw_products`
--
DROP TABLE IF EXISTS `vw_products`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_products`  AS SELECT `p`.`product_id` AS `product_id`, `p`.`product_name` AS `product_name`, `p`.`sku` AS `sku`, `p`.`price` AS `price`, `p`.`tax_rate` AS `tax_rate`, `p`.`stock_quantity` AS `stock_quantity`, `pc`.`category_name` AS `category_name` FROM (`products` `p` join `product_categories` `pc` on(`p`.`category_id` = `pc`.`category_id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `vw_product_performance`
--
DROP TABLE IF EXISTS `vw_product_performance`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_product_performance`  AS SELECT `p`.`product_id` AS `product_id`, `p`.`product_name` AS `product_name`, `p`.`sku` AS `sku`, sum(`qi`.`quantity`) AS `total_sold`, `p`.`stock_quantity` AS `stock_quantity`, `pc`.`category_name` AS `category_name` FROM ((`products` `p` join `product_categories` `pc` on(`p`.`category_id` = `pc`.`category_id`)) left join `quote_items` `qi` on(`p`.`product_id` = `qi`.`product_id`)) GROUP BY `p`.`product_id`, `p`.`product_name`, `p`.`sku`, `p`.`stock_quantity`, `pc`.`category_name` ;

-- --------------------------------------------------------

--
-- Structure for view `vw_quotes`
--
DROP TABLE IF EXISTS `vw_quotes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_quotes`  AS SELECT `q`.`quote_id` AS `quote_id`, `q`.`quote_number` AS `quote_number`, `q`.`status` AS `status`, `q`.`total_amount` AS `total_amount`, `q`.`issue_date` AS `issue_date`, `q`.`expiry_date` AS `expiry_date`, `c`.`company_name` AS `client_name`, `u`.`username` AS `username` FROM ((`quotes` `q` join `clients` `c` on(`q`.`client_id` = `c`.`client_id`)) join `users` `u` on(`q`.`user_id` = `u`.`user_id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `vw_quote_items`
--
DROP TABLE IF EXISTS `vw_quote_items`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_quote_items`  AS SELECT `qi`.`quote_item_id` AS `quote_item_id`, `qi`.`quote_id` AS `quote_id`, `qi`.`quantity` AS `quantity`, `qi`.`unit_price` AS `unit_price`, `qi`.`discount` AS `discount`, `qi`.`tax_amount` AS `tax_amount`, `qi`.`subtotal` AS `subtotal`, `p`.`product_name` AS `product_name`, `p`.`sku` AS `sku` FROM ((`quote_items` `qi` join `quotes` `q` on(`qi`.`quote_id` = `q`.`quote_id`)) join `products` `p` on(`qi`.`product_id` = `p`.`product_id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `vw_sales_performance`
--
DROP TABLE IF EXISTS `vw_sales_performance`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_sales_performance`  AS SELECT `u`.`user_id` AS `user_id`, `u`.`username` AS `username`, count(`q`.`quote_id`) AS `total_quotes`, sum(`q`.`total_amount`) AS `total_amount`, avg(case when `q`.`status` = 'APPROVED' then 1.0 else 0.0 end) AS `conversion_rate` FROM (`users` `u` left join `quotes` `q` on(`u`.`user_id` = `q`.`user_id`)) GROUP BY `u`.`user_id`, `u`.`username` ;

-- --------------------------------------------------------

--
-- Structure for view `vw_sales_trends`
--
DROP TABLE IF EXISTS `vw_sales_trends`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_sales_trends`  AS SELECT date_format(`q`.`issue_date`,'%Y-%m') AS `month`, sum(`q`.`total_amount`) AS `total_amount`, count(`q`.`quote_id`) AS `total_quotes`, avg(`qi`.`discount`) AS `average_discount` FROM (`quotes` `q` join `quote_items` `qi` on(`q`.`quote_id` = `qi`.`quote_id`)) GROUP BY date_format(`q`.`issue_date`,'%Y-%m') ;

-- --------------------------------------------------------

--
-- Structure for view `vw_security_metrics`
--
DROP TABLE IF EXISTS `vw_security_metrics`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_security_metrics`  AS SELECT sum(`u`.`failed_login_attempts`) AS `failed_login_count`, count(case when `u`.`locked_until` is not null and `u`.`locked_until` > current_timestamp() then 1 end) AS `locked_accounts`, count(case when `u`.`is_active` = 0 then 1 end) AS `inactive_accounts`, (select count(0) from `audit_logs` where `audit_logs`.`entity_type` = 'ROLE_PERMISSIONS') AS `permission_changes`, (select count(0) from `audit_logs`) AS `audit_log_count`, (select max(`audit_logs`.`created_at`) from `audit_logs`) AS `last_security_event` FROM `users` AS `u` ;

-- --------------------------------------------------------

--
-- Structure for view `vw_security_posture`
--
DROP TABLE IF EXISTS `vw_security_posture`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_security_posture`  AS SELECT sum(`u`.`failed_login_attempts`) AS `failed_login_count`, sum(case when `u`.`locked_until` is not null and `u`.`locked_until` > current_timestamp() then 1 else 0 end) AS `locked_accounts`, sum(case when `u`.`is_active` = 0 then 1 else 0 end) AS `inactive_accounts`, (select count(0) from `audit_logs` where `audit_logs`.`action` in ('UPDATE','INSERT','DELETE') and `audit_logs`.`entity_type` = 'ROLE_PERMISSIONS') AS `permission_changes`, (select count(0) from `audit_logs`) AS `audit_log_count`, (select max(`audit_logs`.`created_at`) from `audit_logs`) AS `last_security_event` FROM `users` AS `u` ;

-- --------------------------------------------------------

--
-- Structure for view `vw_settings`
--
DROP TABLE IF EXISTS `vw_settings`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_settings`  AS SELECT `s`.`setting_id` AS `setting_id`, `s`.`setting_key` AS `setting_key`, `s`.`setting_value` AS `setting_value`, `s`.`created_at` AS `created_at`, `s`.`updated_at` AS `updated_at` FROM `settings` AS `s` ORDER BY `s`.`setting_key` ASC ;

-- --------------------------------------------------------

--
-- Structure for view `vw_top_clients`
--
DROP TABLE IF EXISTS `vw_top_clients`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_top_clients`  AS SELECT `c`.`client_id` AS `client_id`, `c`.`company_name` AS `company_name`, sum(`q`.`total_amount`) AS `total_spend`, count(`q`.`quote_id`) AS `purchase_count`, rank() over ( order by sum(`q`.`total_amount`) desc) AS `rank` FROM (`clients` `c` join `quotes` `q` on(`c`.`client_id` = `q`.`client_id`)) WHERE `q`.`status` = 'APPROVED' GROUP BY `c`.`client_id`, `c`.`company_name` ;

-- --------------------------------------------------------

--
-- Structure for view `vw_users`
--
DROP TABLE IF EXISTS `vw_users`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_users`  AS SELECT `u`.`user_id` AS `user_id`, `u`.`username` AS `username`, `u`.`email` AS `email`, `u`.`display_name` AS `display_name`, `u`.`profile_picture` AS `profile_picture`, `u`.`language` AS `language`, `u`.`role_id` AS `role_id`, `u`.`is_admin` AS `is_admin`, `u`.`is_active` AS `is_active`, `u`.`created_at` AS `created_at`, `u`.`updated_at` AS `updated_at`, `u`.`last_login_at` AS `last_login_at`, `r`.`role_name` AS `role_name` FROM (`users` `u` join `roles` `r` on(`u`.`role_id` = `r`.`role_id`)) ORDER BY `u`.`created_at` DESC ;

-- --------------------------------------------------------

--
-- Structure for view `vw_user_profile`
--
DROP TABLE IF EXISTS `vw_user_profile`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_user_profile`  AS SELECT `u`.`user_id` AS `user_id`, `u`.`username` AS `username`, `u`.`email` AS `email`, `u`.`display_name` AS `display_name`, `u`.`profile_picture` AS `profile_picture`, `u`.`language` AS `language`, `u`.`role_id` AS `role_id`, `u`.`is_admin` AS `is_admin`, `u`.`is_active` AS `is_active`, `u`.`force_password_change` AS `force_password_change`, `u`.`created_at` AS `created_at`, `u`.`updated_at` AS `updated_at`, `u`.`last_login_at` AS `last_login_at`, `u`.`failed_login_attempts` AS `failed_login_attempts`, `u`.`locked_until` AS `locked_until`, `r`.`role_name` AS `role_name`, `r`.`description` AS `role_description` FROM (`users` `u` join `roles` `r` on(`u`.`role_id` = `r`.`role_id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `vw_user_roles`
--
DROP TABLE IF EXISTS `vw_user_roles`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_user_roles`  AS SELECT `roles`.`role_id` AS `role_id`, `roles`.`role_name` AS `role_name`, `roles`.`description` AS `description`, `roles`.`created_at` AS `created_at` FROM `roles` ORDER BY `roles`.`role_name` ASC ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `access_requests`
--
ALTER TABLE `access_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `reviewed_by` (`reviewed_by`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_permission_id` (`permission_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`audit_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_entity_type` (`entity_type`),
  ADD KEY `idx_entity_id` (`entity_id`),
  ADD KEY `idx_action` (`action`),
  ADD KEY `idx_created_at` (`created_at`),
  ADD KEY `idx_audit_logs_compliance` (`created_at`,`action`,`entity_type`),
  ADD KEY `idx_audit_logs_date_range` (`created_at`,`action`),
  ADD KEY `idx_audit_logs_entity_action` (`entity_type`,`action`,`created_at`);

--
-- Indexes for table `backup_requests`
--
ALTER TABLE `backup_requests`
  ADD PRIMARY KEY (`backup_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `idx_requested_at` (`requested_at`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`client_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_created_by` (`created_by`);

--
-- Indexes for table `client_activities`
--
ALTER TABLE `client_activities`
  ADD PRIMARY KEY (`activity_id`),
  ADD KEY `idx_client_id` (`client_id`),
  ADD KEY `idx_quote_id` (`quote_id`),
  ADD KEY `idx_activity_date` (`activity_date`);

--
-- Indexes for table `materialized_client_purchase_patterns`
--
ALTER TABLE `materialized_client_purchase_patterns`
  ADD PRIMARY KEY (`client_id`);

--
-- Indexes for table `materialized_sales_performance`
--
ALTER TABLE `materialized_sales_performance`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `materialized_sales_trends`
--
ALTER TABLE `materialized_sales_trends`
  ADD PRIMARY KEY (`month`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`permission_id`),
  ADD UNIQUE KEY `permission_name` (`permission_name`),
  ADD KEY `idx_module` (`module`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD KEY `idx_sku` (`sku`),
  ADD KEY `idx_category_id` (`category_id`);

--
-- Indexes for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `category_name` (`category_name`),
  ADD KEY `idx_category_name` (`category_name`);

--
-- Indexes for table `quotes`
--
ALTER TABLE `quotes`
  ADD PRIMARY KEY (`quote_id`),
  ADD UNIQUE KEY `quote_number` (`quote_number`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_quote_number` (`quote_number`),
  ADD KEY `idx_client_id` (`client_id`),
  ADD KEY `idx_parent_quote_id` (`parent_quote_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_issue_date` (`issue_date`);

--
-- Indexes for table `quote_items`
--
ALTER TABLE `quote_items`
  ADD PRIMARY KEY (`quote_item_id`),
  ADD KEY `idx_quote_id` (`quote_id`),
  ADD KEY `idx_product_id` (`product_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`),
  ADD UNIQUE KEY `role_name` (`role_name`),
  ADD KEY `idx_role_name` (`role_name`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`role_id`,`permission_id`),
  ADD KEY `permission_id` (`permission_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`setting_id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`),
  ADD KEY `idx_setting_key` (`setting_key`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `idx_username` (`username`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_language` (`language`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `access_requests`
--
ALTER TABLE `access_requests`
  MODIFY `request_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `audit_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=869;

--
-- AUTO_INCREMENT for table `backup_requests`
--
ALTER TABLE `backup_requests`
  MODIFY `backup_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `client_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `client_activities`
--
ALTER TABLE `client_activities`
  MODIFY `activity_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `permission_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `category_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `quotes`
--
ALTER TABLE `quotes`
  MODIFY `quote_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `quote_items`
--
ALTER TABLE `quote_items`
  MODIFY `quote_item_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `setting_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `access_requests`
--
ALTER TABLE `access_requests`
  ADD CONSTRAINT `access_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `access_requests_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`permission_id`),
  ADD CONSTRAINT `access_requests_ibfk_3` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `backup_requests`
--
ALTER TABLE `backup_requests`
  ADD CONSTRAINT `backup_requests_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `clients`
--
ALTER TABLE `clients`
  ADD CONSTRAINT `clients_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `client_activities`
--
ALTER TABLE `client_activities`
  ADD CONSTRAINT `client_activities_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`client_id`),
  ADD CONSTRAINT `client_activities_ibfk_2` FOREIGN KEY (`quote_id`) REFERENCES `quotes` (`quote_id`) ON DELETE SET NULL;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `product_categories` (`category_id`);

--
-- Constraints for table `quotes`
--
ALTER TABLE `quotes`
  ADD CONSTRAINT `quotes_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`client_id`),
  ADD CONSTRAINT `quotes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `quotes_ibfk_3` FOREIGN KEY (`parent_quote_id`) REFERENCES `quotes` (`quote_id`) ON DELETE SET NULL;

--
-- Constraints for table `quote_items`
--
ALTER TABLE `quote_items`
  ADD CONSTRAINT `quote_items_ibfk_1` FOREIGN KEY (`quote_id`) REFERENCES `quotes` (`quote_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quote_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`permission_id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`);
--
-- Database: `crm_managment`
--
CREATE DATABASE IF NOT EXISTS `crm_managment` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `crm_managment`;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT 'Estado: 1=Activa, 0=Inactiva',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Categorías para organizar productos';

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Electrónicos', 1, '2025-06-25 19:33:25', NULL),
(2, 'Ropa y Accesorios', 1, '2025-06-25 19:33:25', NULL),
(3, 'Hogar y Jardín', 1, '2025-06-25 19:33:25', NULL),
(4, 'Servicios Profesionales', 1, '2025-06-25 19:33:25', NULL),
(5, 'Oficina y Papelería', 1, '2025-06-25 19:33:25', NULL),
(6, 'Salud y Belleza', 1, '2025-06-25 19:33:25', NULL),
(7, 'Deportes y Fitness', 1, '2025-06-25 19:33:25', NULL),
(8, 'Automotriz', 1, '2025-06-25 19:33:25', NULL),
(9, 'Alimentación', 1, '2025-06-25 19:33:25', NULL),
(10, 'Construcción', 1, '2025-06-25 19:33:25', NULL),
(11, 'Consultoria IT', 1, '2025-07-02 20:35:15', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT 'Estado: 1=Activo, 0=Inactivo, 2=Eliminado',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Clientes del CRM con información de contacto';

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `name`, `email`, `phone`, `address`, `status`, `created_at`, `updated_at`) VALUES
(1, 'TechCorp Solutions S.A.', 'contacto@techcorp.com', '+34 911 234 567', 'Calle Tecnolog&iacute;a 123, Madrid, Espa&ntilde;a', 0, '2025-06-25 19:33:25', '2025-06-25 19:40:23'),
(2, 'María Elena Rodríguez', 'maria.rodriguez@email.com', '+34 655 987 654', 'Avenida Principal 456, Barcelona, España', 1, '2025-06-25 19:33:25', NULL),
(3, 'Innovación Digital Ltd.', 'info@innovaciondigital.com', '+34 913 456 789', 'Plaza de la Innovación 789, Valencia, España', 1, '2025-06-25 19:33:25', NULL),
(4, 'Carlos Mendoza García', 'carlos.mendoza@personal.com', '+34 666 123 987', 'Calle Comercial 321, Sevilla, España', 1, '2025-06-25 19:33:25', NULL),
(5, 'Grupo Empresarial ABC', 'ventas@grupoabc.com', '+34 912 567 890', 'Polígono Industrial 45, Bilbao, España', 1, '2025-06-25 19:33:25', NULL),
(6, 'Ana Patricia V&aacute;zquez', 'ana.vazquez@gmail.com', '+34 677 234 567', 'Urbanizaci&oacute;n Los Pinos 12, M&aacute;laga, Espa&ntilde;a', 0, '2025-06-25 19:33:25', '2025-06-25 19:40:29'),
(7, 'Servicios Integrales XYZ', 'administracion@serviciosxyz.com', '+34 914 678 123', 'Centro Empresarial Torre Norte, Zaragoza, Espa&ntilde;a 24', 1, '2025-06-25 19:33:25', '2025-06-25 19:40:50'),
(8, 'Roberto Silva Martinez', 'roberto.silva@hotmail.com', '+34 688 345 678', 'Residencial San Miguel 67, Murcia, España', 1, '2025-06-25 19:33:25', NULL),
(9, 'Construcciones del Sur', 'proyectos@construccionesdelsur.com', '+34 915 789 234', 'Zona Industrial Este 89, Granada, España', 1, '2025-06-25 19:33:25', NULL),
(10, 'Lucía Fernández Gómez', 'lucia.fernandez@outlook.com', '+34 699 456 789', 'Barrio Nuevo 34, Salamanca, España', 1, '2025-06-25 19:33:25', NULL),
(11, 'Tech Corp International', 'akishori@teccorp.com', '+16 123 45 67 89', 'Calle de la independencia, Sector 2, Edificio 1, Oficina 23', 1, '2025-06-25 19:42:20', NULL),
(12, 'Abish Kishori Serrano', 'itbkup24@gmail.com', '+16 123 465 2345', 'Calle los alcaldes St.23 #b-12', 1, '2025-06-26 21:08:49', '2025-07-02 20:22:40'),
(13, 'Fabrizzio Michaelo Angelo Paloma', 'dijilog67890@kimdyn.com', '+16 123 45 67 89', 'Edificio Michaelo, Colony El pasto, St 23', 1, '2025-07-02 16:10:27', '2025-07-02 20:29:14'),
(14, 'Juan Andres Perez Ciguenza', 'dijilog678@kimdyn.com', '+16 123 45 67 89', 'Torre ciguenza St. 24, apartado 2.', 1, '2025-07-02 20:21:40', '2025-07-02 20:29:21');

-- --------------------------------------------------------

--
-- Table structure for table `email_logs`
--

CREATE TABLE `email_logs` (
  `id` int(11) NOT NULL,
  `quote_id` int(11) DEFAULT NULL,
  `recipient_email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('sent','failed') DEFAULT 'sent',
  `error_message` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `email_logs`
--

INSERT INTO `email_logs` (`id`, `quote_id`, `recipient_email`, `subject`, `sent_at`, `status`, `error_message`) VALUES
(1, 11, 'itbkup24@gmail.com', 'Cotización COT-2025-0022 - Envios Inc.', '2025-06-26 21:39:41', 'sent', NULL),
(2, 12, 'dijilog678@kimdyn.com', 'Cotización COT-2025-0023 - Envios Inc.', '2025-07-02 16:12:12', 'sent', NULL),
(3, 13, 'dijilog678@kimdyn.com', 'Cotización COT-2025-0024 - Envios Inc.', '2025-07-02 20:30:32', 'sent', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `base_price` decimal(10,2) NOT NULL,
  `tax_rate` decimal(5,2) NOT NULL DEFAULT 0.00,
  `unit` varchar(20) NOT NULL,
  `stock` int(11) DEFAULT NULL COMMENT 'Stock disponible (NULL = no se maneja inventario)',
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT 'Estado: 1=Activo, 0=Inactivo',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Productos y servicios del catálogo';

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `category_id`, `base_price`, `tax_rate`, `unit`, `stock`, `status`, `created_at`, `updated_at`) VALUES
(1, 'MacBook Pro 14\"', 'Laptop profesional Apple con chip M3, 16GB RAM, 512GB SSD', 1, 2299.00, 21.00, 'unidad', 24, 1, '2025-06-25 19:33:25', '2025-07-02 20:32:35'),
(2, 'Smartphone Samsung Galaxy S24', 'Tel&eacute;fono inteligente con c&aacute;mara de 50MP y 256GB almacenamiento', 1, 899.00, 21.00, 'unidad', 10, 1, '2025-06-25 19:33:25', '2025-06-25 19:42:38'),
(3, 'Consultoría en Desarrollo Web', 'Servicio de consultoría especializada en desarrollo web y e-commerce', 4, 85.00, 21.00, 'hora', NULL, 1, '2025-06-25 19:33:25', NULL),
(4, 'Silla Ergonómica Premium', 'Silla de oficina ergonómica con soporte lumbar y reposabrazos ajustables', 5, 345.00, 21.00, 'unidad', 13, 1, '2025-06-25 19:33:25', '2025-06-26 21:14:34'),
(5, 'Impresora Multifunci&oacute;n HP', 'Impresora l&aacute;ser a color con scanner, copiadora y fax integrado', 1, 425.00, 21.00, 'unidad', 10, 1, '2025-06-25 19:33:25', '2025-06-25 19:42:46'),
(6, 'Kit de Herramientas Profesional', 'Set completo de herramientas para construcción y reparaciones', 10, 159.99, 21.00, 'kit', 30, 1, '2025-06-25 19:33:25', NULL),
(7, 'Bicicleta de Montaña Trek', 'Bicicleta MTB con suspensión completa y cambios Shimano', 7, 1250.00, 21.00, 'unidad', 8, 1, '2025-06-25 19:33:25', NULL),
(8, 'Crema Hidratante Facial', 'Crema anti-edad con ácido hialurónico y vitamina E', 6, 45.50, 21.00, 'unidad', 100, 1, '2025-06-25 19:33:25', NULL),
(9, 'Aceite de Motor Sintético', 'Aceite lubricante sintético 5W-30 para motores de alto rendimiento', 8, 35.75, 21.00, 'litro', 200, 1, '2025-06-25 19:33:25', NULL),
(10, 'Café Premium Gourmet', 'Café de origen único, tostado artesanal, 500g', 9, 18.90, 10.00, 'paquete', 70, 1, '2025-06-25 19:33:25', '2025-06-25 19:50:47'),
(11, 'Dell Modelo G15, Procesador Core i9', 'Laptop portatil Dell g15 Core i9 16 GB RAM', 1, 2500.00, 5.00, 'pieza', 100, 1, '2025-06-26 21:09:51', '2025-06-26 21:10:30'),
(12, 'Consultoria de ciberseguridad', 'Se realiza un estudio completo de la seguridad informatica de la empresa en general y encuentra oportunidades de mejora.', 11, 1200.00, 5.00, 'hora', NULL, 1, '2025-07-02 20:37:19', '2025-07-02 20:38:30');

-- --------------------------------------------------------

--
-- Table structure for table `quotes`
--

CREATE TABLE `quotes` (
  `id` int(11) NOT NULL,
  `quote_number` varchar(50) NOT NULL,
  `client_id` int(11) NOT NULL,
  `quote_date` date NOT NULL,
  `valid_until` date NOT NULL,
  `notes` text DEFAULT NULL,
  `discount_percent` decimal(5,2) DEFAULT 0.00,
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT 'Estado: 1=Borrador, 2=Enviada, 3=Aprobada, 4=Rechazada, 5=Vencida, 6=Cancelada',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Cotizaciones enviadas a clientes';

--
-- Dumping data for table `quotes`
--

INSERT INTO `quotes` (`id`, `quote_number`, `client_id`, `quote_date`, `valid_until`, `notes`, `discount_percent`, `subtotal`, `tax_amount`, `total_amount`, `status`, `created_at`, `updated_at`) VALUES
(1, 'COT-2025-0012', 2, '2025-06-25', '2025-06-26', 'Cotizaci&oacute;n para renovaci&oacute;n de equipos inform&aacute;ticos. Incluye instalaci&oacute;n y configuraci&oacute;n.', 5.00, 439.50, 81.71, 519.32, 3, '2025-06-25 19:33:25', '2025-06-25 19:50:47'),
(2, 'COT-2025-0013', 2, '2025-06-25', '2025-06-26', 'Propuesta para equipamiento personal de oficina en casa.', 0.00, 345.00, 72.45, 417.45, 3, '2025-06-25 19:33:25', '2025-06-26 21:14:34'),
(3, 'COT-2025-0014', 3, '2025-06-25', '2025-06-30', 'Servicios de consultor&iacute;a para desarrollo de plataforma web corporativa.', 10.00, 1250.00, 262.50, 1512.50, 5, '2025-06-25 19:33:25', '2025-07-02 16:04:04'),
(4, 'COT-2025-0015', 4, '2025-06-25', '2025-07-25', 'Equipamiento deportivo para gimnasio personal.', 0.00, 0.00, 0.00, 150.00, 3, '2025-06-25 19:33:25', '2025-06-25 19:51:29'),
(5, 'COT-2025-0016', 5, '2025-06-25', '2025-07-03', 'Suministro de productos para oficinas corporativas. Entrega en 15 d&iacute;as h&aacute;biles.', 8.00, 2299.00, 482.79, 2781.79, 5, '2025-06-25 19:33:25', '2025-07-27 13:14:15'),
(6, 'COT-2025-0017', 4, '2025-06-25', '2025-07-25', 'Productos de cuidado personal y belleza para spa.', 0.00, 345.00, 72.45, 417.45, 5, '2025-06-25 19:33:25', '2025-07-27 13:14:15'),
(7, 'COT-2025-0018', 7, '2025-06-25', '2025-07-25', 'Herramientas profesionales para proyecto de construcción.', 12.00, 0.00, 0.00, 0.00, 4, '2025-06-25 19:33:25', NULL),
(8, 'COT-2025-0019', 8, '2025-06-25', '2025-07-03', 'Mantenimiento automotriz y suministros para flota vehicular.', 5.00, 345.00, 72.45, 417.45, 5, '2025-06-25 19:33:25', '2025-07-27 13:14:15'),
(9, 'COT-2025-0020', 9, '2025-06-25', '2025-07-25', 'Suministro de materiales de construcción para obra nueva.', 0.00, 0.00, 0.00, 350.00, 3, '2025-06-25 19:33:25', '2025-06-25 19:51:45'),
(10, 'COT-2025-0021', 10, '2025-06-25', '2025-06-27', 'Productos gourmet para cafeter&iacute;a especializada.', 15.00, 2384.00, 500.64, 2884.64, 5, '2025-06-25 19:33:25', '2025-06-29 16:35:55'),
(11, 'COT-2025-0022', 12, '2025-06-26', '2025-07-27', 'Se envia producto a su edificio en calle los alcaldes', 0.00, 2500.00, 125.00, 2625.00, 6, '2025-06-26 21:11:26', '2025-07-02 20:32:57'),
(12, 'COT-2025-0023', 13, '2025-07-02', '2025-08-04', 'No condiciones especiales.', 0.00, 159.99, 31.92, 183.91, 2, '2025-07-02 16:11:20', '2025-07-02 16:12:12'),
(13, 'COT-2025-0024', 14, '2025-07-02', '2025-08-04', 'No hay condiciones especificas por cumplir.', 0.00, 2384.00, 500.64, 2884.64, 3, '2025-07-02 20:28:11', '2025-07-02 20:32:35');

-- --------------------------------------------------------

--
-- Table structure for table `quote_details`
--

CREATE TABLE `quote_details` (
  `id` int(11) NOT NULL,
  `quote_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `discount_percent` decimal(5,2) DEFAULT 0.00,
  `line_subtotal` decimal(10,2) NOT NULL,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `line_total` decimal(10,2) NOT NULL,
  `tax_rate` decimal(5,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `line_total_with_tax` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Detalles/items de cada cotización';

--
-- Dumping data for table `quote_details`
--

INSERT INTO `quote_details` (`id`, `quote_id`, `product_id`, `product_name`, `quantity`, `unit_price`, `discount_percent`, `line_subtotal`, `discount_amount`, `line_total`, `tax_rate`, `tax_amount`, `line_total_with_tax`) VALUES
(11, 1, 10, 'Café Premium Gourmet', 5, 18.90, 2.00, 94.50, 1.89, 92.61, 10.00, 9.26, 101.87),
(12, 1, 4, 'Silla Ergonómica Premium', 1, 345.00, 0.00, 345.00, 0.00, 345.00, 21.00, 72.45, 417.45),
(18, 6, 4, 'Silla Ergonómica Premium', 1, 345.00, 0.00, 345.00, 0.00, 345.00, 21.00, 72.45, 417.45),
(19, 8, 4, 'Silla Ergonómica Premium', 1, 345.00, 0.00, 345.00, 0.00, 345.00, 21.00, 72.45, 417.45),
(21, 10, 1, 'MacBook Pro 14\"', 1, 2299.00, 0.00, 2299.00, 0.00, 2299.00, 21.00, 482.79, 2781.79),
(22, 10, 3, 'Consultoría en Desarrollo Web', 1, 85.00, 0.00, 85.00, 0.00, 85.00, 21.00, 17.85, 102.85),
(23, 5, 9, 'Aceite de Motor Sintético', 1, 2299.00, 0.00, 2299.00, 0.00, 2299.00, 21.00, 482.79, 2781.79),
(24, 3, 7, 'Bicicleta de Montaña Trek', 1, 1250.00, 0.00, 1250.00, 0.00, 1250.00, 21.00, 262.50, 1512.50),
(25, 2, 4, 'Silla Ergonómica Premium', 1, 345.00, 0.00, 345.00, 0.00, 345.00, 21.00, 72.45, 417.45),
(26, 11, 11, 'Dell Modelo G15, Procesador Core i9', 1, 2500.00, 0.00, 2500.00, 0.00, 2500.00, 5.00, 125.00, 2625.00),
(27, 12, 6, 'Kit de Herramientas Profesional', 1, 159.99, 5.00, 159.99, 8.00, 151.99, 21.00, 31.92, 183.91),
(29, 13, 3, 'Consultoría en Desarrollo Web', 1, 85.00, 0.00, 85.00, 0.00, 85.00, 21.00, 17.85, 102.85),
(30, 13, 1, 'MacBook Pro 14\"', 1, 2299.00, 0.00, 2299.00, 0.00, 2299.00, 21.00, 482.79, 2781.79);

--
-- Triggers `quote_details`
--
DELIMITER $$
CREATE TRIGGER `update_quote_totals_after_detail_change` AFTER INSERT ON `quote_details` FOR EACH ROW BEGIN
    UPDATE quotes q
    SET 
        subtotal = (
            SELECT COALESCE(SUM(line_subtotal), 0) 
            FROM quote_details 
            WHERE quote_id = NEW.quote_id
        ),
        tax_amount = (
            SELECT COALESCE(SUM(tax_amount), 0) 
            FROM quote_details 
            WHERE quote_id = NEW.quote_id
        ),
        total_amount = (
            SELECT COALESCE(SUM(line_total_with_tax), 0) 
            FROM quote_details 
            WHERE quote_id = NEW.quote_id
        ),
        updated_at = NOW()
    WHERE q.id = NEW.quote_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `company_name` varchar(255) NOT NULL DEFAULT 'Mi Empresa CRM',
  `company_slogan` text DEFAULT NULL,
  `company_address` text DEFAULT NULL,
  `company_phone` varchar(50) DEFAULT NULL,
  `company_email` varchar(255) DEFAULT NULL,
  `company_website` varchar(255) DEFAULT NULL,
  `company_logo` varchar(255) DEFAULT NULL,
  `language` varchar(5) NOT NULL DEFAULT 'es',
  `timezone` varchar(100) NOT NULL DEFAULT 'America/Mexico_City',
  `currency_code` varchar(5) NOT NULL DEFAULT 'USD',
  `currency_symbol` varchar(10) NOT NULL DEFAULT '$',
  `tax_rate` decimal(5,2) NOT NULL DEFAULT 16.00,
  `tax_name` varchar(50) NOT NULL DEFAULT 'IVA',
  `theme` varchar(20) NOT NULL DEFAULT 'light',
  `date_format` varchar(20) NOT NULL DEFAULT 'd/m/Y',
  `smtp_host` varchar(255) DEFAULT NULL,
  `smtp_port` int(11) DEFAULT 587,
  `smtp_username` varchar(255) DEFAULT NULL,
  `smtp_password` varchar(255) DEFAULT NULL,
  `smtp_security` varchar(10) DEFAULT 'tls',
  `smtp_from_email` varchar(255) DEFAULT NULL,
  `smtp_from_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `company_name`, `company_slogan`, `company_address`, `company_phone`, `company_email`, `company_website`, `company_logo`, `language`, `timezone`, `currency_code`, `currency_symbol`, `tax_rate`, `tax_name`, `theme`, `date_format`, `smtp_host`, `smtp_port`, `smtp_username`, `smtp_password`, `smtp_security`, `smtp_from_email`, `smtp_from_name`, `created_at`, `updated_at`) VALUES
(1, 'Envios Inc.', 'Envios confiables a todo el mundo', '', '', '', '', 'assets/images/logos/685db99978f82.jpg', 'es', 'Europe/Madrid', 'USD', '&euro;', 16.00, 'IVA', 'light', 'd/m/Y', 'smtp.gmail.com', 587, 'itbkup24@gmail.com', 'lsyw vjsr qold fpfn ', 'tls', 'itbkup24@gmail.com', 'Mi empresa CRM', '2025-06-25 17:31:55', '2025-06-26 21:34:43');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `role` tinyint(4) NOT NULL DEFAULT 2 COMMENT 'Rol: 1=Admin, 2=Seller',
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT 'Estado: 1=Activo, 0=Inactivo',
  `last_login` timestamp NULL DEFAULT NULL,
  `failed_login_attempts` int(11) DEFAULT 0 COMMENT 'Intentos fallidos de login consecutivos',
  `locked_until` timestamp NULL DEFAULT NULL COMMENT 'Bloqueado hasta esta fecha/hora',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Usuarios del sistema CRM con roles y autenticación';

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `full_name`, `role`, `status`, `last_login`, `failed_login_attempts`, `locked_until`, `created_at`, `updated_at`) VALUES
(1, 'root', 'root@sysadmin.com', '$2y$10$XaQhK6Aoj.JvThtybuHkYei5DFQT/1JqfFUSdz5LIRQdM5Bttutpm', 'root sysadmin', 1, 1, '2025-08-04 10:27:40', 5, NULL, '2025-06-20 00:16:43', '2025-08-18 13:56:09'),
(10, 'User', 'user@sysuser.com', '$2y$10$CWX2yMqGe8Yh5pZgQaTV4.gAGd0Fs983pQ3GShgKM//xZ1KO5QoYq', 'Juan Manolo Casal', 2, 1, '2025-08-04 10:16:54', 4, NULL, '2025-06-26 21:13:22', '2025-08-18 13:56:24'),
(11, 'jgarcia', 'jandres@localhost.com', '$2y$10$6bR3B.aSVIqFEg0gKqE7Z.2Z1p3tTy1Dn9/JjP.b1FThTD8PvSaIO', 'Juan Andres Perez Ciguenza', 2, 0, '2025-07-02 20:43:17', 2, NULL, '2025-07-02 20:41:39', '2025-08-18 13:55:38');

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_client_quote_summary`
-- (See below for the actual view)
--
CREATE TABLE `view_client_quote_summary` (
`client_id` int(11)
,`client_name` varchar(100)
,`client_email` varchar(255)
,`total_quotes` bigint(21)
,`draft_quotes` decimal(22,0)
,`sent_quotes` decimal(22,0)
,`approved_quotes` decimal(22,0)
,`rejected_quotes` decimal(22,0)
,`total_quoted_value` decimal(32,2)
,`approved_value` decimal(32,2)
,`last_quote_date` timestamp
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_products_with_category`
-- (See below for the actual view)
--
CREATE TABLE `view_products_with_category` (
`id` int(11)
,`product_name` varchar(100)
,`description` text
,`category_name` varchar(50)
,`base_price` decimal(10,2)
,`tax_rate` decimal(5,2)
,`final_price` decimal(15,2)
,`unit` varchar(20)
,`stock` int(11)
,`status` tinyint(4)
,`created_at` timestamp
,`updated_at` timestamp
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_quotes_with_client`
-- (See below for the actual view)
--
CREATE TABLE `view_quotes_with_client` (
`id` int(11)
,`quote_number` varchar(50)
,`quote_date` date
,`valid_until` date
,`client_name` varchar(100)
,`client_email` varchar(255)
,`client_phone` varchar(20)
,`subtotal` decimal(10,2)
,`discount_percent` decimal(5,2)
,`tax_amount` decimal(10,2)
,`total_amount` decimal(10,2)
,`status` tinyint(4)
,`status_name` varchar(11)
,`notes` text
,`created_at` timestamp
,`updated_at` timestamp
);

-- --------------------------------------------------------

--
-- Structure for view `view_client_quote_summary`
--
DROP TABLE IF EXISTS `view_client_quote_summary`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_client_quote_summary`  AS SELECT `c`.`id` AS `client_id`, `c`.`name` AS `client_name`, `c`.`email` AS `client_email`, count(`q`.`id`) AS `total_quotes`, sum(case when `q`.`status` = 1 then 1 else 0 end) AS `draft_quotes`, sum(case when `q`.`status` = 2 then 1 else 0 end) AS `sent_quotes`, sum(case when `q`.`status` = 3 then 1 else 0 end) AS `approved_quotes`, sum(case when `q`.`status` = 4 then 1 else 0 end) AS `rejected_quotes`, coalesce(sum(`q`.`total_amount`),0) AS `total_quoted_value`, coalesce(sum(case when `q`.`status` = 3 then `q`.`total_amount` else 0 end),0) AS `approved_value`, max(`q`.`created_at`) AS `last_quote_date` FROM (`clients` `c` left join `quotes` `q` on(`c`.`id` = `q`.`client_id`)) WHERE `c`.`status` = 1 GROUP BY `c`.`id`, `c`.`name`, `c`.`email` ;

-- --------------------------------------------------------

--
-- Structure for view `view_products_with_category`
--
DROP TABLE IF EXISTS `view_products_with_category`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_products_with_category`  AS SELECT `p`.`id` AS `id`, `p`.`name` AS `product_name`, `p`.`description` AS `description`, `c`.`name` AS `category_name`, `p`.`base_price` AS `base_price`, `p`.`tax_rate` AS `tax_rate`, round(`p`.`base_price` + `p`.`base_price` * `p`.`tax_rate` / 100,2) AS `final_price`, `p`.`unit` AS `unit`, `p`.`stock` AS `stock`, `p`.`status` AS `status`, `p`.`created_at` AS `created_at`, `p`.`updated_at` AS `updated_at` FROM (`products` `p` left join `categories` `c` on(`p`.`category_id` = `c`.`id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `view_quotes_with_client`
--
DROP TABLE IF EXISTS `view_quotes_with_client`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_quotes_with_client`  AS SELECT `q`.`id` AS `id`, `q`.`quote_number` AS `quote_number`, `q`.`quote_date` AS `quote_date`, `q`.`valid_until` AS `valid_until`, `c`.`name` AS `client_name`, `c`.`email` AS `client_email`, `c`.`phone` AS `client_phone`, `q`.`subtotal` AS `subtotal`, `q`.`discount_percent` AS `discount_percent`, `q`.`tax_amount` AS `tax_amount`, `q`.`total_amount` AS `total_amount`, `q`.`status` AS `status`, CASE `q`.`status` WHEN 1 THEN 'Borrador' WHEN 2 THEN 'Enviada' WHEN 3 THEN 'Aprobada' WHEN 4 THEN 'Rechazada' WHEN 5 THEN 'Vencida' WHEN 6 THEN 'Cancelada' ELSE 'Desconocido' END AS `status_name`, `q`.`notes` AS `notes`, `q`.`created_at` AS `created_at`, `q`.`updated_at` AS `updated_at` FROM (`quotes` `q` left join `clients` `c` on(`q`.`client_id` = `c`.`id`)) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `idx_name` (`name`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_name` (`name`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_phone` (`phone`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_created_at` (`created_at`),
  ADD KEY `idx_clients_name_email` (`name`,`email`);

--
-- Indexes for table `email_logs`
--
ALTER TABLE `email_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_quote_id` (`quote_id`),
  ADD KEY `idx_sent_at` (`sent_at`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_name` (`name`),
  ADD KEY `idx_category_id` (`category_id`),
  ADD KEY `idx_base_price` (`base_price`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_stock` (`stock`),
  ADD KEY `idx_created_at` (`created_at`),
  ADD KEY `idx_products_category_status` (`category_id`,`status`);

--
-- Indexes for table `quotes`
--
ALTER TABLE `quotes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `quote_number` (`quote_number`),
  ADD KEY `idx_quote_number` (`quote_number`),
  ADD KEY `idx_client_id` (`client_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_quote_date` (`quote_date`),
  ADD KEY `idx_valid_until` (`valid_until`),
  ADD KEY `idx_total_amount` (`total_amount`),
  ADD KEY `idx_created_at` (`created_at`),
  ADD KEY `idx_quotes_client_status` (`client_id`,`status`),
  ADD KEY `idx_quotes_date_range` (`quote_date`,`valid_until`);

--
-- Indexes for table `quote_details`
--
ALTER TABLE `quote_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_quote_id` (`quote_id`),
  ADD KEY `idx_product_id` (`product_id`),
  ADD KEY `idx_line_total_with_tax` (`line_total_with_tax`),
  ADD KEY `idx_quote_details_quote_product` (`quote_id`,`product_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_language` (`language`),
  ADD KEY `idx_timezone` (`timezone`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_username` (`username`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_role` (`role`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_last_login` (`last_login`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `email_logs`
--
ALTER TABLE `email_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `quotes`
--
ALTER TABLE `quotes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `quote_details`
--
ALTER TABLE `quote_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `quotes`
--
ALTER TABLE `quotes`
  ADD CONSTRAINT `quotes_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `quote_details`
--
ALTER TABLE `quote_details`
  ADD CONSTRAINT `quote_details_ibfk_1` FOREIGN KEY (`quote_id`) REFERENCES `quotes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `quote_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON UPDATE CASCADE;
--
-- Database: `disaster_report`
--
CREATE DATABASE IF NOT EXISTS `disaster_report` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `disaster_report`;

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `request_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `user_id`, `action`, `request_id`, `created_at`) VALUES
(32, 1, 'approve', 11, '2025-06-18 12:56:52'),
(33, 1, 'approve', 10, '2025-06-18 12:57:19'),
(34, 1, 'approve', 12, '2025-06-19 08:19:06'),
(35, 1, 'approve', 13, '2025-06-19 08:51:08');

-- --------------------------------------------------------

--
-- Table structure for table `people`
--

CREATE TABLE `people` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` varchar(100) NOT NULL,
  `refuge_id` int(11) NOT NULL,
  `entry_date` date NOT NULL,
  `entry_time` time NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `people`
--

INSERT INTO `people` (`id`, `name`, `status`, `refuge_id`, `entry_date`, `entry_time`, `created_at`) VALUES
(256, 'Ricardo Torres Ballesteros', 'Albergado', 11, '2025-06-03', '20:24:00', '2025-06-18 12:56:52'),
(257, 'Rocío Romero Sandoval', 'Dado de alta', 11, '2025-06-13', '00:16:00', '2025-06-18 12:56:52'),
(258, 'Nancy Blanco Escamilla', 'Albergado', 11, '2025-05-22', '16:16:00', '2025-06-18 12:56:52'),
(259, 'Zeferino Gollum Páez', 'Dado de alta', 11, '2025-05-24', '08:37:00', '2025-06-18 12:56:52'),
(260, 'Ricardo Rosario Arenas', 'Pendiente', 11, '2025-05-22', '21:24:00', '2025-06-18 12:56:52'),
(261, 'Ing. Octavio Mata', 'Albergado', 11, '2025-05-31', '21:21:00', '2025-06-18 12:56:52'),
(262, 'Evelio Gabino Ortiz Ceja', 'Dado de alta', 11, '2025-06-02', '09:04:00', '2025-06-18 12:56:52'),
(263, 'Antonia Abril Reynoso Valladares', 'En tránsito', 11, '2025-05-23', '21:43:00', '2025-06-18 12:56:52'),
(264, 'Dr. Natividad Jaimes', 'Dado de alta', 11, '2025-05-25', '15:52:00', '2025-06-18 12:56:52'),
(265, 'Felipe Negrón Linares', 'En tránsito', 11, '2025-05-26', '03:16:00', '2025-06-18 12:56:52'),
(266, 'Mtro. Sara Tafoya', 'Albergado', 11, '2025-05-19', '19:55:00', '2025-06-18 12:56:52'),
(267, 'Mtro. Wilfrido Cano', 'En tránsito', 11, '2025-05-25', '08:04:00', '2025-06-18 12:56:52'),
(268, 'Bernabé Uribe Cortés', 'Pendiente', 11, '2025-06-01', '02:09:00', '2025-06-18 12:56:52'),
(269, 'Dr. Jacobo Figueroa', 'En tránsito', 11, '2025-05-31', '00:14:00', '2025-06-18 12:56:52'),
(270, 'Fidel Ávalos', 'En tránsito', 11, '2025-05-23', '05:24:00', '2025-06-18 12:56:52'),
(271, 'Gerónimo Armendáriz', 'Albergado', 11, '2025-05-19', '14:13:00', '2025-06-18 12:56:52'),
(272, 'Teodoro Frida Cuellar', 'Dado de alta', 11, '2025-06-09', '02:11:00', '2025-06-18 12:56:52'),
(273, 'Ramiro Aguirre', 'Albergado', 11, '2025-05-24', '04:42:00', '2025-06-18 12:56:52'),
(274, 'Patricio Alejandro Negrón', 'Pendiente', 11, '2025-06-16', '02:03:00', '2025-06-18 12:56:52'),
(275, 'Luis Manuel Carolina Arguello', 'En tránsito', 11, '2025-06-02', '11:05:00', '2025-06-18 12:56:52'),
(276, 'Jorge Gaitán Haro', 'Albergado', 11, '2025-05-23', '17:31:00', '2025-06-18 12:56:52'),
(277, 'Lic. Silvano Correa', 'Dado de alta', 11, '2025-05-30', '00:04:00', '2025-06-18 12:56:52'),
(278, 'Liliana Alberto Duarte', 'Pendiente', 11, '2025-06-04', '07:43:00', '2025-06-18 12:56:52'),
(279, 'Yolanda Guzmán', 'Pendiente', 11, '2025-05-31', '21:12:00', '2025-06-18 12:56:52'),
(280, 'Juana Arellano Rodrígez', 'Dado de alta', 11, '2025-05-29', '09:49:00', '2025-06-18 12:56:52'),
(281, 'Jerónimo Magaña', 'En tránsito', 11, '2025-05-30', '19:04:00', '2025-06-18 12:56:52'),
(282, 'Victoria Espinoza Ozuna', 'En tránsito', 11, '2025-06-03', '17:11:00', '2025-06-18 12:56:52'),
(283, 'Germán Montoya Saucedo', 'Dado de alta', 11, '2025-06-07', '12:20:00', '2025-06-18 12:56:52'),
(284, 'Mtro. Alicia Carrillo', 'Dado de alta', 11, '2025-05-31', '07:51:00', '2025-06-18 12:56:52'),
(285, 'Mtro. Alfonso Cardona', 'En tránsito', 11, '2025-06-16', '03:22:00', '2025-06-18 12:56:52'),
(286, 'Humberto Marco Antonio Zelaya Segura', 'En tránsito', 11, '2025-05-24', '02:01:00', '2025-06-18 12:56:52'),
(287, 'Dr. Florencia Armendáriz', 'Pendiente', 11, '2025-06-09', '00:53:00', '2025-06-18 12:56:52'),
(288, 'Ing. Yuridia Estrada', 'En tránsito', 11, '2025-06-05', '22:55:00', '2025-06-18 12:56:52'),
(289, 'Wendolin Menéndez Martínez', 'Albergado', 11, '2025-05-25', '14:08:00', '2025-06-18 12:56:52'),
(290, 'Natividad Horacio Paredes Mena', 'En tránsito', 11, '2025-06-07', '05:01:00', '2025-06-18 12:56:52'),
(291, 'Perla Carolina Espinosa Peres', 'Pendiente', 11, '2025-06-06', '19:53:00', '2025-06-18 12:56:52'),
(292, 'Sergio Rebeca Madera', 'Albergado', 11, '2025-06-07', '01:16:00', '2025-06-18 12:56:52'),
(293, 'Hernán Llamas Gaitán', 'Dado de alta', 11, '2025-06-08', '03:23:00', '2025-06-18 12:56:52'),
(294, 'Ing. Vanesa Medina', 'En tránsito', 11, '2025-06-10', '15:37:00', '2025-06-18 12:56:52'),
(295, 'Zeferino Gallardo', 'Pendiente', 11, '2025-06-12', '02:33:00', '2025-06-18 12:56:52'),
(296, 'Victoria Arturo Limón', 'Pendiente', 11, '2025-06-13', '04:14:00', '2025-06-18 12:56:52'),
(297, 'Mtro. Aurelio Gollum', 'Albergado', 11, '2025-06-03', '11:22:00', '2025-06-18 12:56:52'),
(298, 'Concepción Alberto Arredondo', 'En tránsito', 11, '2025-05-30', '13:52:00', '2025-06-18 12:56:52'),
(299, 'Sr(a). María Eugenia Pedroza', 'Albergado', 11, '2025-06-15', '17:08:00', '2025-06-18 12:56:52'),
(300, 'Miguel Ángel Pabón', 'Albergado', 11, '2025-05-30', '21:44:00', '2025-06-18 12:56:52'),
(301, 'Bernabé Pizarro Gaona', 'Dado de alta', 11, '2025-05-27', '06:12:00', '2025-06-18 12:56:52'),
(302, 'Lic. Amador Valdés', 'En tránsito', 11, '2025-05-31', '09:13:00', '2025-06-18 12:56:52'),
(303, 'Cecilia Rivera', 'Albergado', 11, '2025-06-13', '03:55:00', '2025-06-18 12:56:52'),
(304, 'Mtro. Margarita Santana', 'En tránsito', 11, '2025-06-08', '10:21:00', '2025-06-18 12:56:52'),
(305, 'Cristobal Roldán', 'Dado de alta', 11, '2025-06-09', '05:46:00', '2025-06-18 12:56:52'),
(306, 'Alberto Laureano Guevara', 'Albergado', 10, '2025-05-29', '05:36:00', '2025-06-18 12:57:19'),
(307, 'Zoé Trejo Brito', 'Dado de alta', 10, '2025-06-16', '22:10:00', '2025-06-18 12:57:19'),
(308, 'Alta  Gracia Cabán Gamez', 'Pendiente', 10, '2025-05-30', '16:10:00', '2025-06-18 12:57:19'),
(309, 'Sr(a). Ramiro Farías', 'En tránsito', 10, '2025-06-14', '05:19:00', '2025-06-18 12:57:19'),
(310, 'Marisol Rolando Ledesma', 'En tránsito', 10, '2025-05-19', '06:13:00', '2025-06-18 12:57:19'),
(311, 'Luisa Jacinto Ledesma', 'Pendiente', 10, '2025-05-26', '15:39:00', '2025-06-18 12:57:19'),
(312, 'Benjamín Aurelio Olivares Calderón', 'Albergado', 10, '2025-06-05', '01:34:00', '2025-06-18 12:57:19'),
(313, 'Mtro. Alvaro Rico', 'Albergado', 10, '2025-06-14', '13:42:00', '2025-06-18 12:57:19'),
(314, 'Arcelia Urías', 'Albergado', 10, '2025-06-02', '22:14:00', '2025-06-18 12:57:19'),
(315, 'Mtro. Reynaldo Espinosa', 'Albergado', 10, '2025-05-22', '23:18:00', '2025-06-18 12:57:19'),
(316, 'Gregorio Arcelia Gonzales', 'Dado de alta', 10, '2025-05-23', '02:07:00', '2025-06-18 12:57:19'),
(317, 'María Elena Teresa Nava Curiel', 'Dado de alta', 10, '2025-06-05', '07:13:00', '2025-06-18 12:57:19'),
(318, 'Yuridia Gabriela Becerra', 'Albergado', 10, '2025-06-17', '11:18:00', '2025-06-18 12:57:19'),
(319, 'Oswaldo Felipe Dávila', 'Albergado', 10, '2025-06-10', '09:59:00', '2025-06-18 12:57:19'),
(320, 'Timoteo Porfirio Gaona Meléndez', 'Albergado', 10, '2025-05-21', '00:29:00', '2025-06-18 12:57:19'),
(321, 'Ignacio Pacheco Jasso', 'Albergado', 10, '2025-05-22', '00:09:00', '2025-06-18 12:57:19'),
(322, 'Mtro. Eric Sanabria', 'Albergado', 10, '2025-06-07', '04:04:00', '2025-06-18 12:57:19'),
(323, 'Claudio Jaimes', 'Pendiente', 10, '2025-05-25', '23:02:00', '2025-06-18 12:57:19'),
(324, 'Ing. Frida Viera', 'En tránsito', 10, '2025-06-08', '18:56:00', '2025-06-18 12:57:19'),
(325, 'Jos Gaona', 'En tránsito', 10, '2025-05-25', '08:16:00', '2025-06-18 12:57:19'),
(326, 'Aurora Arreola', 'En tránsito', 10, '2025-05-24', '21:23:00', '2025-06-18 12:57:19'),
(327, 'Luisa Rubén Barela', 'Dado de alta', 10, '2025-06-06', '04:46:00', '2025-06-18 12:57:19'),
(328, 'Lic. Óliver Heredia', 'Pendiente', 10, '2025-06-14', '08:41:00', '2025-06-18 12:57:19'),
(329, 'Omar Jaramillo Molina', 'En tránsito', 10, '2025-05-24', '23:38:00', '2025-06-18 12:57:19'),
(330, 'Cristina Rendón', 'Pendiente', 10, '2025-05-19', '16:41:00', '2025-06-18 12:57:19'),
(331, 'Mónica Diego Sarabia Caraballo', 'Pendiente', 10, '2025-06-02', '04:13:00', '2025-06-18 12:57:19'),
(332, 'Ing. Elvia Corral', 'Dado de alta', 10, '2025-06-06', '07:24:00', '2025-06-18 12:57:19'),
(333, 'Paola Olga Abrego', 'En tránsito', 10, '2025-06-17', '22:37:00', '2025-06-18 12:57:19'),
(334, 'Aida Carrasco', 'Pendiente', 10, '2025-06-05', '11:55:00', '2025-06-18 12:57:19'),
(335, 'Evelio Ana Luisa Guajardo Valverde', 'Dado de alta', 10, '2025-05-22', '22:16:00', '2025-06-18 12:57:19'),
(336, 'Ángela Tejada', 'En tránsito', 10, '2025-05-25', '04:29:00', '2025-06-18 12:57:19'),
(337, 'Paulina de Jesús', 'Dado de alta', 10, '2025-05-31', '09:40:00', '2025-06-18 12:57:19'),
(338, 'Lic. Eric Tirado', 'En tránsito', 10, '2025-05-28', '10:37:00', '2025-06-18 12:57:19'),
(339, 'Leonor Yuridia Niño Quezada', 'Dado de alta', 10, '2025-06-13', '17:58:00', '2025-06-18 12:57:19'),
(340, 'Fernando Garza Barela', 'En tránsito', 10, '2025-06-04', '19:25:00', '2025-06-18 12:57:19'),
(341, 'Ing. Estela Villalobos', 'Albergado', 10, '2025-05-29', '17:30:00', '2025-06-18 12:57:19'),
(342, 'Susana Bahena Girón', 'Dado de alta', 10, '2025-05-22', '18:33:00', '2025-06-18 12:57:19'),
(343, 'Angélica Noelia Granados Leiva', 'Dado de alta', 10, '2025-06-11', '21:14:00', '2025-06-18 12:57:19'),
(344, 'Dr. Reina Velázquez', 'En tránsito', 10, '2025-06-10', '17:29:00', '2025-06-18 12:57:19'),
(345, 'Lorenzo Espinal', 'Pendiente', 10, '2025-05-23', '05:28:00', '2025-06-18 12:57:19'),
(346, 'Joaquín Piña', 'En tránsito', 10, '2025-06-07', '23:03:00', '2025-06-18 12:57:19'),
(347, 'Zacarías Calvillo Ballesteros', 'Dado de alta', 10, '2025-06-07', '20:19:00', '2025-06-18 12:57:19'),
(348, 'Bianca Felix Espinosa Espinal', 'Albergado', 10, '2025-06-14', '08:24:00', '2025-06-18 12:57:19'),
(349, 'Luisa Dulce Leiva', 'Pendiente', 10, '2025-06-10', '23:21:00', '2025-06-18 12:57:19'),
(350, 'Margarita Urbina Calvillo', 'Albergado', 10, '2025-05-21', '03:34:00', '2025-06-18 12:57:19'),
(351, 'Sr(a). Aurelio Granado', 'Dado de alta', 10, '2025-05-27', '11:25:00', '2025-06-18 12:57:19'),
(352, 'Mauricio Gallardo', 'Dado de alta', 10, '2025-06-07', '09:22:00', '2025-06-18 12:57:19'),
(353, 'Juan Abelardo Orellana', 'Pendiente', 10, '2025-05-31', '04:19:00', '2025-06-18 12:57:19'),
(354, 'Gonzalo Piña', 'En tránsito', 10, '2025-06-14', '21:36:00', '2025-06-18 12:57:19'),
(355, 'Cristal Espinal', 'Pendiente', 10, '2025-06-02', '23:38:00', '2025-06-18 12:57:19'),
(356, 'Alberto Laureano Guevara', 'Albergado', 12, '2025-05-30', '05:36:00', '2025-06-19 08:19:06'),
(357, 'Zoé Trejo Brito', 'Albergado', 12, '2025-06-17', '22:10:00', '2025-06-19 08:19:06'),
(358, 'Alta  Gracia Cabán Gamez', 'Albergado', 12, '2025-05-31', '16:10:00', '2025-06-19 08:19:06'),
(359, 'Sr(a). Ramiro Farías', 'Albergado', 12, '2025-06-15', '05:19:00', '2025-06-19 08:19:06'),
(360, 'Marisol Rolando Ledesma', 'Albergado', 12, '2025-05-20', '06:13:00', '2025-06-19 08:19:06'),
(361, 'Luisa Jacinto Ledesma', 'Pendiente', 12, '2025-05-27', '15:39:00', '2025-06-19 08:19:06'),
(362, 'Benjamín Aurelio Olivares Calderón', 'Albergado', 12, '2025-06-06', '01:34:00', '2025-06-19 08:19:06'),
(363, 'Mtro. Alvaro Rico', 'Dado de alta', 12, '2025-06-15', '13:42:00', '2025-06-19 08:19:06'),
(364, 'Arcelia Urías', 'Albergado', 12, '2025-06-03', '22:14:00', '2025-06-19 08:19:06'),
(365, 'Mtro. Reynaldo Espinosa', 'Dado de alta', 12, '2025-05-23', '23:18:00', '2025-06-19 08:19:06'),
(366, 'Gregorio Arcelia Gonzales', 'Albergado', 12, '2025-05-24', '02:07:00', '2025-06-19 08:19:06'),
(367, 'María Elena Teresa Nava Curiel', 'Pendiente', 12, '2025-06-06', '07:13:00', '2025-06-19 08:19:06'),
(368, 'Yuridia Gabriela Becerra', 'En tránsito', 12, '2025-06-18', '11:18:00', '2025-06-19 08:19:06'),
(369, 'Oswaldo Felipe Dávila', 'Pendiente', 12, '2025-06-11', '09:59:00', '2025-06-19 08:19:06'),
(370, 'Timoteo Porfirio Gaona Meléndez', 'En tránsito', 12, '2025-05-22', '00:29:00', '2025-06-19 08:19:06'),
(371, 'Ignacio Pacheco Jasso', 'Dado de alta', 12, '2025-05-23', '00:09:00', '2025-06-19 08:19:06'),
(372, 'Mtro. Eric Sanabria', 'Albergado', 12, '2025-06-08', '04:04:00', '2025-06-19 08:19:06'),
(373, 'Claudio Jaimes', 'Pendiente', 12, '2025-05-26', '23:02:00', '2025-06-19 08:19:06'),
(374, 'Ing. Frida Viera', 'Albergado', 12, '2025-06-09', '18:56:00', '2025-06-19 08:19:06'),
(375, 'Jos Gaona', 'Pendiente', 12, '2025-05-26', '08:16:00', '2025-06-19 08:19:06'),
(376, 'Aurora Arreola', 'Albergado', 12, '2025-05-25', '21:23:00', '2025-06-19 08:19:06'),
(377, 'Luisa Rubén Barela', 'Pendiente', 12, '2025-06-07', '04:46:00', '2025-06-19 08:19:06'),
(378, 'Lic. Óliver Heredia', 'En tránsito', 12, '2025-06-15', '08:41:00', '2025-06-19 08:19:06'),
(379, 'Omar Jaramillo Molina', 'Dado de alta', 12, '2025-05-25', '23:38:00', '2025-06-19 08:19:06'),
(380, 'Cristina Rendón', 'En tránsito', 12, '2025-05-20', '16:41:00', '2025-06-19 08:19:06'),
(381, 'Mónica Diego Sarabia Caraballo', 'Albergado', 12, '2025-06-03', '04:13:00', '2025-06-19 08:19:06'),
(382, 'Ing. Elvia Corral', 'Albergado', 12, '2025-06-07', '07:24:00', '2025-06-19 08:19:06'),
(383, 'Paola Olga Abrego', 'Pendiente', 12, '2025-06-18', '22:37:00', '2025-06-19 08:19:06'),
(384, 'Aida Carrasco', 'Dado de alta', 12, '2025-06-06', '11:55:00', '2025-06-19 08:19:06'),
(385, 'Evelio Ana Luisa Guajardo Valverde', 'Dado de alta', 12, '2025-05-23', '22:16:00', '2025-06-19 08:19:06'),
(386, 'Ángela Tejada', 'Albergado', 12, '2025-05-26', '04:29:00', '2025-06-19 08:19:06'),
(387, 'Paulina de Jesús', 'Pendiente', 12, '2025-06-01', '09:40:00', '2025-06-19 08:19:06'),
(388, 'Lic. Eric Tirado', 'Albergado', 12, '2025-05-29', '10:37:00', '2025-06-19 08:19:06'),
(389, 'Leonor Yuridia Niño Quezada', 'En tránsito', 12, '2025-06-14', '17:58:00', '2025-06-19 08:19:06'),
(390, 'Fernando Garza Barela', 'Albergado', 12, '2025-06-05', '19:25:00', '2025-06-19 08:19:06'),
(391, 'Ing. Estela Villalobos', 'Pendiente', 12, '2025-05-30', '17:30:00', '2025-06-19 08:19:06'),
(392, 'Susana Bahena Girón', 'En tránsito', 12, '2025-05-23', '18:33:00', '2025-06-19 08:19:06'),
(393, 'Angélica Noelia Granados Leiva', 'Pendiente', 12, '2025-06-12', '21:14:00', '2025-06-19 08:19:06'),
(394, 'Dr. Reina Velázquez', 'Dado de alta', 12, '2025-06-11', '17:29:00', '2025-06-19 08:19:06'),
(395, 'Lorenzo Espinal', 'En tránsito', 12, '2025-05-24', '05:28:00', '2025-06-19 08:19:06'),
(396, 'Joaquín Piña', 'Dado de alta', 12, '2025-06-08', '23:03:00', '2025-06-19 08:19:06'),
(397, 'Zacarías Calvillo Ballesteros', 'Pendiente', 12, '2025-06-08', '20:19:00', '2025-06-19 08:19:06'),
(398, 'Bianca Felix Espinosa Espinal', 'En tránsito', 12, '2025-06-15', '08:24:00', '2025-06-19 08:19:06'),
(399, 'Luisa Dulce Leiva', 'Pendiente', 12, '2025-06-11', '23:21:00', '2025-06-19 08:19:06'),
(400, 'Margarita Urbina Calvillo', 'Dado de alta', 12, '2025-05-22', '03:34:00', '2025-06-19 08:19:06'),
(401, 'Sr(a). Aurelio Granado', 'Albergado', 12, '2025-05-28', '11:25:00', '2025-06-19 08:19:06'),
(402, 'Mauricio Gallardo', 'Pendiente', 12, '2025-06-08', '09:22:00', '2025-06-19 08:19:06'),
(403, 'Juan Abelardo Orellana', 'En tránsito', 12, '2025-06-01', '04:19:00', '2025-06-19 08:19:06'),
(404, 'Gonzalo Piña', 'Dado de alta', 12, '2025-06-15', '21:36:00', '2025-06-19 08:19:06'),
(405, 'Cristal Espinal', 'Albergado', 12, '2025-06-03', '23:38:00', '2025-06-19 08:19:06'),
(406, 'Ricardo Torres Ballesteros', 'Albergado', 13, '2025-06-04', '20:24:00', '2025-06-19 08:51:08'),
(407, 'Rocío Romero Sandoval', 'Dado de alta', 13, '2025-06-14', '00:16:00', '2025-06-19 08:51:08'),
(408, 'Nancy Blanco Escamilla', 'Albergado', 13, '2025-05-23', '16:16:00', '2025-06-19 08:51:08'),
(409, 'Zeferino Gollum Páez', 'Dado de alta', 13, '2025-05-25', '08:37:00', '2025-06-19 08:51:08'),
(410, 'Ricardo Rosario Arenas', 'Albergado', 13, '2025-05-23', '21:24:00', '2025-06-19 08:51:08'),
(411, 'Ing. Octavio Mata', 'Albergado', 13, '2025-06-01', '21:21:00', '2025-06-19 08:51:08'),
(412, 'Evelio Gabino Ortiz Ceja', 'Albergado', 13, '2025-06-03', '09:04:00', '2025-06-19 08:51:08'),
(413, 'Antonia Abril Reynoso Valladares', 'En tránsito', 13, '2025-05-24', '21:43:00', '2025-06-19 08:51:08'),
(414, 'Dr. Natividad Jaimes', 'En tránsito', 13, '2025-05-26', '15:52:00', '2025-06-19 08:51:08'),
(415, 'Felipe Negrón Linares', 'Pendiente', 13, '2025-05-27', '03:16:00', '2025-06-19 08:51:08'),
(416, 'Mtro. Sara Tafoya', 'Albergado', 13, '2025-05-20', '19:55:00', '2025-06-19 08:51:08'),
(417, 'Mtro. Wilfrido Cano', 'Pendiente', 13, '2025-05-26', '08:04:00', '2025-06-19 08:51:08'),
(418, 'Bernabé Uribe Cortés', 'Dado de alta', 13, '2025-06-02', '02:09:00', '2025-06-19 08:51:08'),
(419, 'Dr. Jacobo Figueroa', 'Albergado', 13, '2025-06-01', '00:14:00', '2025-06-19 08:51:08'),
(420, 'Fidel Ávalos', 'En tránsito', 13, '2025-05-24', '05:24:00', '2025-06-19 08:51:08'),
(421, 'Gerónimo Armendáriz', 'Albergado', 13, '2025-05-20', '14:13:00', '2025-06-19 08:51:08'),
(422, 'Teodoro Frida Cuellar', 'Pendiente', 13, '2025-06-10', '02:11:00', '2025-06-19 08:51:08'),
(423, 'Ramiro Aguirre', 'Dado de alta', 13, '2025-05-25', '04:42:00', '2025-06-19 08:51:08'),
(424, 'Patricio Alejandro Negrón', 'En tránsito', 13, '2025-06-17', '02:03:00', '2025-06-19 08:51:08'),
(425, 'Luis Manuel Carolina Arguello', 'Pendiente', 13, '2025-06-03', '11:05:00', '2025-06-19 08:51:08'),
(426, 'Jorge Gaitán Haro', 'Albergado', 13, '2025-05-24', '17:31:00', '2025-06-19 08:51:08'),
(427, 'Lic. Silvano Correa', 'En tránsito', 13, '2025-05-31', '00:04:00', '2025-06-19 08:51:08'),
(428, 'Liliana Alberto Duarte', 'Dado de alta', 13, '2025-06-05', '07:43:00', '2025-06-19 08:51:08'),
(429, 'Yolanda Guzmán', 'Albergado', 13, '2025-06-01', '21:12:00', '2025-06-19 08:51:08'),
(430, 'Juana Arellano Rodrígez', 'En tránsito', 13, '2025-05-30', '09:49:00', '2025-06-19 08:51:08'),
(431, 'Jerónimo Magaña', 'Dado de alta', 13, '2025-05-31', '19:04:00', '2025-06-19 08:51:08'),
(432, 'Victoria Espinoza Ozuna', 'Pendiente', 13, '2025-06-04', '17:11:00', '2025-06-19 08:51:08'),
(433, 'Germán Montoya Saucedo', 'Dado de alta', 13, '2025-06-08', '12:20:00', '2025-06-19 08:51:08'),
(434, 'Mtro. Alicia Carrillo', 'Albergado', 13, '2025-06-01', '07:51:00', '2025-06-19 08:51:08'),
(435, 'Mtro. Alfonso Cardona', 'Albergado', 13, '2025-06-17', '03:22:00', '2025-06-19 08:51:08'),
(436, 'Humberto Marco Antonio Zelaya Segura', 'En tránsito', 13, '2025-05-25', '02:01:00', '2025-06-19 08:51:08'),
(437, 'Dr. Florencia Armendáriz', 'Dado de alta', 13, '2025-06-10', '00:53:00', '2025-06-19 08:51:08'),
(438, 'Ing. Yuridia Estrada', 'Pendiente', 13, '2025-06-06', '22:55:00', '2025-06-19 08:51:08'),
(439, 'Wendolin Menéndez Martínez', 'En tránsito', 13, '2025-05-26', '14:08:00', '2025-06-19 08:51:08'),
(440, 'Natividad Horacio Paredes Mena', 'Albergado', 13, '2025-06-08', '05:01:00', '2025-06-19 08:51:08'),
(441, 'Perla Carolina Espinosa Peres', 'Albergado', 13, '2025-06-07', '19:53:00', '2025-06-19 08:51:08'),
(442, 'Sergio Rebeca Madera', 'En tránsito', 13, '2025-06-08', '01:16:00', '2025-06-19 08:51:08'),
(443, 'Hernán Llamas Gaitán', 'En tránsito', 13, '2025-06-09', '03:23:00', '2025-06-19 08:51:08'),
(444, 'Ing. Vanesa Medina', 'Pendiente', 13, '2025-06-11', '15:37:00', '2025-06-19 08:51:08'),
(445, 'Zeferino Gallardo', 'Dado de alta', 13, '2025-06-13', '02:33:00', '2025-06-19 08:51:08'),
(446, 'Victoria Arturo Limón', 'Dado de alta', 13, '2025-06-14', '04:14:00', '2025-06-19 08:51:08'),
(447, 'Mtro. Aurelio Gollum', 'Albergado', 13, '2025-06-04', '11:22:00', '2025-06-19 08:51:08'),
(448, 'Concepción Alberto Arredondo', 'En tránsito', 13, '2025-05-31', '13:52:00', '2025-06-19 08:51:08'),
(449, 'Sr(a). María Eugenia Pedroza', 'Pendiente', 13, '2025-06-16', '17:08:00', '2025-06-19 08:51:08'),
(450, 'Miguel Ángel Pabón', 'Pendiente', 13, '2025-05-31', '21:44:00', '2025-06-19 08:51:08'),
(451, 'Bernabé Pizarro Gaona', 'En tránsito', 13, '2025-05-28', '06:12:00', '2025-06-19 08:51:08'),
(452, 'Lic. Amador Valdés', 'Pendiente', 13, '2025-06-01', '09:13:00', '2025-06-19 08:51:08'),
(453, 'Cecilia Rivera', 'En tránsito', 13, '2025-06-14', '03:55:00', '2025-06-19 08:51:08'),
(454, 'Mtro. Margarita Santana', 'Pendiente', 13, '2025-06-09', '10:21:00', '2025-06-19 08:51:08'),
(455, 'Cristobal Roldán', 'Albergado', 13, '2025-06-10', '05:46:00', '2025-06-19 08:51:08');

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `refuge_name` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `ip` varchar(45) NOT NULL,
  `csv_path` varchar(255) NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `requests`
--

INSERT INTO `requests` (`id`, `user_id`, `refuge_name`, `location`, `ip`, `csv_path`, `status`, `created_at`) VALUES
(10, 2, 'Manos que ayudan', 'San Jose, Atocha #25', '127.0.0.1', 'uploads/csv_6852b748a3545.csv', 'approved', '2025-06-18 12:55:36'),
(11, 2, 'Manuel Becerra', 'Manuel Becerra, Plaza central, #15', '127.0.0.1', 'uploads/csv_6852b77e6fd8d.csv', 'approved', '2025-06-18 12:56:30'),
(12, 2, 'Vbox del prueblo', 'Diego de leon edificio 29', '127.0.0.1', 'uploads/csv_6853c7ce57931.csv', 'approved', '2025-06-19 08:18:22'),
(13, 2, 'Centro comunitario de madrid', 'Calle de la Regalada, Retiro, Madrid, Comunidad de Madrid', '127.0.0.1', 'uploads/csv_6853cbffa5744.csv', 'approved', '2025-06-19 08:36:15');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','refuge_user') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `login_attempts` int(11) DEFAULT 0,
  `last_attempt` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password_hash`, `role`, `created_at`, `login_attempts`, `last_attempt`) VALUES
(1, 'admin@localhost.com', '$2y$10$96xg.9nYredq9qsgihfp8eTae1t6KQsUtk4CCWgVKWW6P4GaRxM0S', 'admin', '2025-06-17 17:15:00', 0, NULL),
(2, 'user@localhost.com', '$2y$10$LbJrDciahMC7jA2UyR57ruasCuJqJGtBcPXCZXi39I81hogJvuW5O', 'refuge_user', '2025-06-17 17:51:58', 5, '2025-07-24 00:36:46');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `request_id` (`request_id`);

--
-- Indexes for table `people`
--
ALTER TABLE `people`
  ADD PRIMARY KEY (`id`),
  ADD KEY `refuge_id` (`refuge_id`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `people`
--
ALTER TABLE `people`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=456;

--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `logs_ibfk_2` FOREIGN KEY (`request_id`) REFERENCES `requests` (`id`);

--
-- Constraints for table `people`
--
ALTER TABLE `people`
  ADD CONSTRAINT `people_ibfk_1` FOREIGN KEY (`refuge_id`) REFERENCES `requests` (`id`);

--
-- Constraints for table `requests`
--
ALTER TABLE `requests`
  ADD CONSTRAINT `requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
--
-- Database: `modules_store`
--
CREATE DATABASE IF NOT EXISTS `modules_store` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `modules_store`;

-- --------------------------------------------------------

--
-- Table structure for table `audit_log`
--

CREATE TABLE `audit_log` (
  `id` bigint(20) NOT NULL,
  `table_name` varchar(50) DEFAULT NULL,
  `record_id` int(11) DEFAULT NULL,
  `action` enum('INSERT','UPDATE','DELETE','LOGIN','LOGOUT') DEFAULT NULL,
  `old_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_values`)),
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_values`)),
  `user_id` int(11) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_log`
--

INSERT INTO `audit_log` (`id`, `table_name`, `record_id`, `action`, `old_values`, `new_values`, `user_id`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, NULL, NULL, 'LOGIN', NULL, NULL, 2, '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:140.0) Gecko/20100101 Firefox/140.0', '2025-07-30 09:19:35'),
(2, 'orders', 35, 'UPDATE', '{\"id\": 35, \"user_id\": null, \"stripe_id\": \"pi_3RqLslQwgcR8epNA1b1mgGcT\", \"total\": 1.95, \"status\": \"paid\", \"shipping_name\": \"Lilian Leon\", \"shipping_email\": \"itbkup24@gmail.com\"}', '{\"id\": 35, \"user_id\": null, \"stripe_id\": \"pi_3RqLslQwgcR8epNA1b1mgGcT\", \"total\": 1.95, \"status\": \"cancelled\", \"shipping_name\": \"Lilian Leon\", \"shipping_email\": \"itbkup24@gmail.com\"}', 2, NULL, NULL, '2025-07-30 09:20:10'),
(3, 'orders', 26, 'UPDATE', '{\"id\": 26, \"user_id\": null, \"stripe_id\": \"pi_3RoWN2QwgcR8epNA0XCk0VRo\", \"total\": 49.98, \"status\": \"paid\", \"shipping_name\": \"Anderson Leon\", \"shipping_email\": \"itbkup24@gmail.com\"}', '{\"id\": 26, \"user_id\": null, \"stripe_id\": \"pi_3RoWN2QwgcR8epNA0XCk0VRo\", \"total\": 49.98, \"status\": \"cancelled\", \"shipping_name\": \"Anderson Leon\", \"shipping_email\": \"itbkup24@gmail.com\"}', 2, NULL, NULL, '2025-07-30 09:20:16'),
(4, 'orders', 15, 'UPDATE', '{\"id\": 15, \"user_id\": null, \"stripe_id\": \"pi_3RoKR3QwgcR8epNA0faeWSmm\", \"total\": 9.90, \"status\": \"paid\", \"shipping_name\": \"Anderson Leon\", \"shipping_email\": \"itbkup24@gmail.com\"}', '{\"id\": 15, \"user_id\": null, \"stripe_id\": \"pi_3RoKR3QwgcR8epNA0faeWSmm\", \"total\": 9.90, \"status\": \"cancelled\", \"shipping_name\": \"Anderson Leon\", \"shipping_email\": \"itbkup24@gmail.com\"}', 2, NULL, NULL, '2025-07-30 09:20:21'),
(5, 'products', 29, 'UPDATE', '{\"id\": 29, \"category_id\": 1, \"name\": \"Altavoz Portátil con Subwoofer\", \"price\": 45.00, \"stock\": 20, \"image_url\": \"https://placehold.co/600x400\"}', '{\"id\": 29, \"category_id\": 1, \"name\": \"Altavoz Portátil con Subwoofer\", \"price\": 45.00, \"stock\": 19, \"image_url\": \"https://placehold.co/600x400\"}', 2, NULL, NULL, '2025-07-30 09:20:37'),
(6, 'users', 3, 'UPDATE', '{\"id\": 3, \"email\": \"localhost@admin.com\", \"is_admin\": 1}', '{\"id\": 3, \"email\": \"localhost@admin.com\", \"is_admin\": 1}', 2, NULL, NULL, '2025-07-30 09:21:03'),
(7, NULL, NULL, 'LOGOUT', NULL, NULL, 2, '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:140.0) Gecko/20100101 Firefox/140.0', '2025-07-30 09:28:53'),
(8, NULL, NULL, 'LOGIN', NULL, NULL, 3, '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:140.0) Gecko/20100101 Firefox/140.0', '2025-08-07 08:16:52'),
(9, 'orders', 36, 'INSERT', NULL, '{\"id\": 36, \"user_id\": null, \"stripe_id\": \"pi_3RtP1bQwgcR8epNA1pmUSMCB\", \"total\": 59.96, \"status\": \"paid\", \"shipping_name\": \"localhost\", \"shipping_email\": \"itbkup24@gmail.com\"}', NULL, NULL, NULL, '2025-08-07 08:19:46'),
(10, 'order_items', 33, 'INSERT', NULL, '{\"id\": 33, \"order_id\": 36, \"product_id\": 30, \"quantity\": 4, \"price_each\": 14.99}', NULL, NULL, NULL, '2025-08-07 08:19:46');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`) VALUES
(1, 'Electronics', 'Gadgets & devices'),
(2, 'Books', 'Fiction & non-fiction');

--
-- Triggers `categories`
--
DELIMITER $$
CREATE TRIGGER `categories_after_delete` AFTER DELETE ON `categories` FOR EACH ROW BEGIN
    INSERT INTO audit_log (table_name, record_id, action, old_values)
    VALUES ('categories', OLD.id, 'DELETE',
        JSON_OBJECT(
            'id', OLD.id,
            'name', OLD.name,
            'description', OLD.description
        )
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `categories_after_insert` AFTER INSERT ON `categories` FOR EACH ROW BEGIN
    INSERT INTO audit_log (table_name, record_id, action, new_values)
    VALUES ('categories', NEW.id, 'INSERT', 
        JSON_OBJECT(
            'id', NEW.id,
            'name', NEW.name,
            'description', NEW.description
        )
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `categories_after_update` AFTER UPDATE ON `categories` FOR EACH ROW BEGIN
    INSERT INTO audit_log (table_name, record_id, action, old_values, new_values)
    VALUES ('categories', NEW.id, 'UPDATE',
        JSON_OBJECT(
            'id', OLD.id,
            'name', OLD.name,
            'description', OLD.description
        ),
        JSON_OBJECT(
            'id', NEW.id,
            'name', NEW.name,
            'description', NEW.description
        )
    );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `stripe_id` varchar(255) DEFAULT NULL,
  `total` decimal(10,2) NOT NULL CHECK (`total` >= 0),
  `status` enum('pending','paid','shipped','cancelled') DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp(),
  `shipping_name` varchar(120) NOT NULL,
  `shipping_email` varchar(255) NOT NULL,
  `shipping_address` text NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `card_last4` char(4) DEFAULT NULL,
  `card_brand` varchar(20) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `stripe_id`, `total`, `status`, `created_at`, `shipping_name`, `shipping_email`, `shipping_address`, `phone`, `card_last4`, `card_brand`, `ip_address`, `latitude`, `longitude`) VALUES
(1, NULL, 'pi_3RoCP0QwgcR8epNA0grYifNQ', 34.89, 'paid', '2025-07-23 17:49:49', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL),
(2, NULL, 'pi_3RoCS3QwgcR8epNA10UgpsuB', 39.50, 'paid', '2025-07-23 17:52:35', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL),
(3, NULL, 'pi_3RoD1JQwgcR8epNA1N32mm9t', 24.99, 'paid', '2025-07-23 18:29:20', 'luppo', 'luppo@lu.com', 'calle local, 127 piso 0, apart 1', '789098765', '4242', 'visa', '::1', NULL, NULL),
(4, NULL, 'pi_3RoD4gQwgcR8epNA0C72Wx6C', 9.90, 'paid', '2025-07-23 18:34:03', 'luppo', 'luppo@luxop.com', 'localhost. 12.0.0.1', '6543213678', '4242', 'visa', '::1', 40.41200000, -3.68710000),
(5, NULL, 'pi_3RoDDlQwgcR8epNA0HUHmh8n', 9.90, 'paid', '2025-07-23 18:42:03', 'luppo', 'luppo@lupito.com', 'Bosque del Recuerdo, Calle de Alfonso XII, Jerónimos, Retiro, Madrid, Community of Madrid, 28014, Spain', '765432980', '4242', 'visa', '::1', 40.41200000, -3.68710000),
(6, NULL, 'pi_3RoDKDQwgcR8epNA0Miqy5PS', 108.70, 'paid', '2025-07-23 18:48:36', 'lupoolio', 'polio@poli.com', 'Bosque del Recuerdo, Calle de Alfonso XII, Jerónimos, Retiro, Madrid, Community of Madrid, 28014, Spain', '63576890972', '4242', 'visa', '::1', 40.41200000, -3.68710000),
(7, NULL, 'pi_3RoJQTQwgcR8epNA0Eo5ounO', 24.99, 'paid', '2025-07-24 01:19:52', 'bandoleira', 'bandit@localhost.com', '32, Calle de Sicilia, Numancia, Vallecas, Madrid, Community of Madrid, 28038, Spain', '635456789', '4242', 'visa', '127.0.0.1', 40.40231550, -3.66550270),
(8, NULL, 'pi_3RoJnGQwgcR8epNA0n8mVgev', 64.49, 'paid', '2025-07-24 01:43:20', 'Sailor Venus ', 'sailor@vn.com', 'Bosque del Recuerdo, Calle de Alfonso XII, Jerónimos, Retiro, Madrid, Community of Madrid, 28014, Spain', '345678901', '4242', 'visa', '::1', 40.41200000, -3.68710000),
(9, NULL, 'pi_3RoK6UQwgcR8epNA08swDKm3', 9.90, 'paid', '2025-07-24 02:02:56', 'Anderson Leon', 'itbkup24@gmail.com', 'Bosque del Recuerdo, Calle de Alfonso XII, Jerónimos, Retiro, Madrid, Community of Madrid, 28014, Spain', '635345678', '4242', 'visa', '::1', 40.41200000, -3.68710000),
(10, NULL, 'pi_3RoK9yQwgcR8epNA1EmHzaOo', 19.80, 'paid', '2025-07-24 02:06:20', 'Anderson Leon', 'itbkup24@gmail.com', 'Bosque del Recuerdo, Calle de Alfonso XII, Jerónimos, Retiro, Madrid, Community of Madrid, 28014, Spain', '6543234678', '4242', 'visa', '::1', 40.41200000, -3.68710000),
(12, NULL, 'pi_3RoKAzQwgcR8epNA1rdZFxNF', 9.90, 'paid', '2025-07-24 02:07:19', 'Anderson Leon', 'itbkup24@gmail.com', 'Bosque del Recuerdo, Calle de Alfonso XII, Jerónimos, Retiro, Madrid, Community of Madrid, 28014, Spain', '6354227890886', '4242', 'visa', '::1', 40.41200000, -3.68710000),
(13, NULL, 'pi_3RoKD3QwgcR8epNA0kCMjzrT', 9.90, 'paid', '2025-07-24 02:09:23', 'Anderson Leon', 'itbkup24@gmail.com', 'Bosque del Recuerdo, Calle de Alfonso XII, Jerónimos, Retiro, Madrid, Community of Madrid, 28014, Spain', '6543234678', '4242', 'visa', '::1', 40.41200000, -3.68710000),
(14, NULL, 'pi_3RoKGNQwgcR8epNA0k37yGPW', 39.50, 'paid', '2025-07-24 02:12:51', 'Anderson Leon', 'itbkup24@gmail.com', 'Bosque del Recuerdo, Calle de Alfonso XII, Jerónimos, Retiro, Madrid, Community of Madrid, 28014, Spain', '6543234678', '4242', 'visa', '::1', 40.41200000, -3.68710000),
(15, NULL, 'pi_3RoKR3QwgcR8epNA0faeWSmm', 9.90, 'cancelled', '2025-07-24 02:24:00', 'Anderson Leon', 'itbkup24@gmail.com', 'Bosque del Recuerdo, Calle de Alfonso XII, Jerónimos, Retiro, Madrid, Community of Madrid, 28014, Spain', '6543234678', '4242', 'visa', '::1', 40.41200000, -3.68710000),
(17, NULL, 'pi_3RoKUaQwgcR8epNA0ygIIr1N', 9.90, 'paid', '2025-07-24 02:27:53', 'bandoleira', 'itbkup24@gmail.com', 'Bosque del Recuerdo, Calle de Alfonso XII, Jerónimos, Retiro, Madrid, Community of Madrid, 28014, Spain', '6543234678', '4242', 'visa', '::1', 40.41200000, -3.68710000),
(18, NULL, 'pi_3RoKefQwgcR8epNA06cFs5K0', 9.90, 'paid', '2025-07-24 02:37:58', 'Anderson Leon', 'itbkup24@gmail.com', 'Bosque del Recuerdo, Calle de Alfonso XII, Jerónimos, Retiro, Madrid, Community of Madrid, 28014, Spain', '6543234678', '4242', 'visa', '::1', 40.41200000, -3.68710000),
(19, NULL, 'pi_3RoKggQwgcR8epNA1QcaxsXf', 9.90, 'paid', '2025-07-24 02:40:09', 'deCloudeSoner', 'itbkup24@gmail.com', 'Bosque del Recuerdo, Calle de Alfonso XII, Jerónimos, Retiro, Madrid, Community of Madrid, 28014, Spain', '6543234678', '4242', 'visa', '::1', 40.41200000, -3.68710000),
(20, NULL, 'pi_3RoKxZQwgcR8epNA1MpOC4ar', 9.90, 'paid', '2025-07-24 02:57:31', 'Anderson Leon', 'itbkup24@gmail.com', 'Bosque del Recuerdo, Calle de Alfonso XII, Jerónimos, Retiro, Madrid, Community of Madrid, 28014, Spain', '6543234678', '4242', 'visa', '::1', 40.41200000, -3.68710000),
(21, NULL, 'pi_3RoKzRQwgcR8epNA1TO0Bj7T', 9.90, 'paid', '2025-07-24 02:59:28', 'Anderson Leon cludsoner', 'itbkup24@gmail.com', 'Bosque del Recuerdo, Calle de Alfonso XII, Jerónimos, Retiro, Madrid, Community of Madrid, 28014, Spain', '6543234678', '4242', 'visa', '::1', 40.41200000, -3.68710000),
(22, NULL, 'pi_3RoMXdQwgcR8epNA0JmICx7d', 9.90, 'paid', '2025-07-24 04:38:54', 'Anderson Leon', 'itbkup24@gmail.com', 'Bosque del Recuerdo, Calle de Alfonso XII, Jerónimos, Retiro, Madrid, Community of Madrid, 28014, Spain', '6543234678', '4242', 'visa', '::1', 40.41200000, -3.68710000),
(23, NULL, 'pi_3RoNxEQwgcR8epNA0SlJxPTG', 79.00, 'paid', '2025-07-24 06:09:19', 'Anderson Leon', 'itbkup24@gmail.com', 'Bosque del Recuerdo, Calle de Alfonso XII, Jerónimos, Retiro, Madrid, Community of Madrid, 28014, Spain', '6543234678', '4242', 'visa', '::1', 40.41200000, -3.68710000),
(26, NULL, 'pi_3RoWN2QwgcR8epNA0XCk0VRo', 49.98, 'cancelled', '2025-07-24 15:08:34', 'Anderson Leon', 'itbkup24@gmail.com', 'Bosque del Recuerdo, Calle de Alfonso XII, Jerónimos, Retiro, Madrid, Community of Madrid, 28014, Spain', '6543234678', '4242', 'visa', '::1', 40.41200000, -3.68710000),
(28, NULL, 'pi_3RpHXvQwgcR8epNA1w68CrPn', 9.90, 'paid', '2025-07-26 17:31:17', 'Carlos Roman ', 'itbkup24@gmail.com', 'Biblioteca Elena Fortún, 189, Calle del Doctor Esquerdo, Adelfas, Retiro, Madrid, Community of Madrid, 28007, Spain', '645322345', '4242', 'visa', '127.0.0.1', 40.40366570, -3.67247480),
(30, NULL, 'pi_3RpUGPQwgcR8epNA1HRbGqiL', 39.50, 'paid', '2025-07-27 07:06:07', 'PErales torres', 'leon.jass@outlook.com', 'Biblioteca Elena Fortún, 189, Calle del Doctor Esquerdo, Adelfas, Retiro, Madrid, Community of Madrid, 28007, Spain', '64523456789', '4242', 'visa', '127.0.0.1', 40.40366570, -3.67247480),
(31, NULL, 'pi_3RqL7ZQwgcR8epNA01uZ0zAp', 22.00, 'paid', '2025-07-29 15:32:49', 'Santos Romeo', 'itbkup24@gmail.com', 'Biblioteca Elena Fortún, 189, Calle del Doctor Esquerdo, Adelfas, Retiro, Madrid, Community of Madrid, 28007, Spain', '645322345', '4242', 'visa', '127.0.0.1', 40.40366570, -3.67247480),
(32, NULL, 'pi_3RqLGEQwgcR8epNA199h7wrA', 22.00, 'paid', '2025-07-29 15:41:12', 'Lilian Maldonado', 'itbkup24@gmail.com', 'Biblioteca Elena Fortún, 189, Calle del Doctor Esquerdo, Adelfas, Retiro, Madrid, Community of Madrid, 28007, Spain', '645322345', '4242', 'visa', '127.0.0.1', 40.40366570, -3.67247480),
(33, NULL, 'pi_3RqLKkQwgcR8epNA1SoMmguL', 9.90, 'paid', '2025-07-29 15:45:48', 'Lilian Maldonado', 'itbkup24@gmail.com', 'Biblioteca Elena Fortún, 189, Calle del Doctor Esquerdo, Adelfas, Retiro, Madrid, Community of Madrid, 28007, Spain', '674523456789', '4242', 'visa', '127.0.0.1', 40.40366570, -3.67247480),
(34, NULL, 'pi_3RqLkuQwgcR8epNA1RJOgAWL', 9.90, 'paid', '2025-07-29 16:12:54', 'Lilian Pastrana', 'itbkup24@gmail.com', 'Biblioteca Elena Fortún, 189, Calle del Doctor Esquerdo, Adelfas, Retiro, Madrid, Community of Madrid, 28007, Spain', '674523456789', '4242', 'visa', '127.0.0.1', 40.40366570, -3.67247480),
(35, NULL, 'pi_3RqLslQwgcR8epNA1b1mgGcT', 1.95, 'cancelled', '2025-07-29 16:21:45', 'Lilian Leon', 'itbkup24@gmail.com', 'Biblioteca Elena Fortún, 189, Calle del Doctor Esquerdo, Adelfas, Retiro, Madrid, Community of Madrid, 28007, Spain', '674523456789', '4242', 'visa', '127.0.0.1', 40.40366570, -3.67247480),
(36, NULL, 'pi_3RtP1bQwgcR8epNA1pmUSMCB', 59.96, 'paid', '2025-08-07 02:19:46', 'localhost', 'itbkup24@gmail.com', 'Avenida de la Ciudad de Barcelona, Pacífico, Retiro, Madrid, Community of Madrid, 28007, Spain', '645322345', '4242', 'visa', '127.0.0.1', 40.40622080, -3.68640000);

--
-- Triggers `orders`
--
DELIMITER $$
CREATE TRIGGER `create_shipment_on_paid_order` AFTER INSERT ON `orders` FOR EACH ROW BEGIN
    IF NEW.status = 'paid' THEN
        INSERT INTO shipments (order_id, status, created_at, updated_at)
        VALUES (NEW.id, 'pending', NOW(), NOW());
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `orders_after_delete` AFTER DELETE ON `orders` FOR EACH ROW BEGIN
    INSERT INTO audit_log (table_name, record_id, action, old_values)
    VALUES ('orders', OLD.id, 'DELETE',
        JSON_OBJECT(
            'id', OLD.id,
            'user_id', OLD.user_id,
            'stripe_id', OLD.stripe_id,
            'total', OLD.total,
            'status', OLD.status,
            'shipping_name', OLD.shipping_name,
            'shipping_email', OLD.shipping_email
        )
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `orders_after_insert` AFTER INSERT ON `orders` FOR EACH ROW BEGIN
    INSERT INTO audit_log (table_name, record_id, action, new_values)
    VALUES ('orders', NEW.id, 'INSERT', 
        JSON_OBJECT(
            'id', NEW.id,
            'user_id', NEW.user_id,
            'stripe_id', NEW.stripe_id,
            'total', NEW.total,
            'status', NEW.status,
            'shipping_name', NEW.shipping_name,
            'shipping_email', NEW.shipping_email
        )
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `orders_after_update` AFTER UPDATE ON `orders` FOR EACH ROW BEGIN
    INSERT INTO audit_log (table_name, record_id, action, old_values, new_values)
    VALUES ('orders', NEW.id, 'UPDATE',
        JSON_OBJECT(
            'id', OLD.id,
            'user_id', OLD.user_id,
            'stripe_id', OLD.stripe_id,
            'total', OLD.total,
            'status', OLD.status,
            'shipping_name', OLD.shipping_name,
            'shipping_email', OLD.shipping_email
        ),
        JSON_OBJECT(
            'id', NEW.id,
            'user_id', NEW.user_id,
            'stripe_id', NEW.stripe_id,
            'total', NEW.total,
            'status', NEW.status,
            'shipping_name', NEW.shipping_name,
            'shipping_email', NEW.shipping_email
        )
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_shipment_on_order_change` AFTER UPDATE ON `orders` FOR EACH ROW BEGIN
    IF NEW.status = 'paid' AND OLD.status != 'paid' THEN
        INSERT IGNORE INTO shipments (order_id, status, created_at, updated_at)
        VALUES (NEW.id, 'pending', NOW(), NOW());
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL CHECK (`quantity` > 0),
  `price_each` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price_each`) VALUES
(1, 1, 1, 1, 24.99),
(2, 1, 2, 1, 9.90),
(3, 2, 3, 1, 39.50),
(4, 3, 1, 1, 24.99),
(5, 4, 2, 1, 9.90),
(6, 5, 2, 1, 9.90),
(7, 6, 3, 2, 39.50),
(8, 6, 2, 3, 9.90),
(9, 7, 1, 1, 24.99),
(10, 8, 3, 1, 39.50),
(11, 8, 1, 1, 24.99),
(12, 9, 2, 1, 9.90),
(13, 10, 2, 2, 9.90),
(14, 12, 2, 1, 9.90),
(15, 13, 2, 1, 9.90),
(16, 14, 3, 1, 39.50),
(17, 15, 2, 1, 9.90),
(18, 17, 2, 1, 9.90),
(19, 18, 2, 1, 9.90),
(20, 19, 2, 1, 9.90),
(21, 20, 2, 1, 9.90),
(22, 21, 2, 1, 9.90),
(23, 22, 2, 1, 9.90),
(24, 23, 3, 2, 39.50),
(25, 26, 1, 2, 24.99),
(26, 28, 2, 1, 9.90),
(27, 30, 3, 1, 39.50),
(28, 31, 28, 1, 22.00),
(29, 32, 28, 1, 22.00),
(30, 33, 2, 1, 9.90),
(31, 34, 2, 1, 9.90),
(32, 35, 4, 1, 1.95),
(33, 36, 30, 4, 14.99);

--
-- Triggers `order_items`
--
DELIMITER $$
CREATE TRIGGER `order_items_after_delete` AFTER DELETE ON `order_items` FOR EACH ROW BEGIN
    INSERT INTO audit_log (table_name, record_id, action, old_values)
    VALUES ('order_items', OLD.id, 'DELETE',
        JSON_OBJECT(
            'id', OLD.id,
            'order_id', OLD.order_id,
            'product_id', OLD.product_id,
            'quantity', OLD.quantity,
            'price_each', OLD.price_each
        )
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `order_items_after_insert` AFTER INSERT ON `order_items` FOR EACH ROW BEGIN
    INSERT INTO audit_log (table_name, record_id, action, new_values)
    VALUES ('order_items', NEW.id, 'INSERT', 
        JSON_OBJECT(
            'id', NEW.id,
            'order_id', NEW.order_id,
            'product_id', NEW.product_id,
            'quantity', NEW.quantity,
            'price_each', NEW.price_each
        )
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `order_items_after_update` AFTER UPDATE ON `order_items` FOR EACH ROW BEGIN
    INSERT INTO audit_log (table_name, record_id, action, old_values, new_values)
    VALUES ('order_items', NEW.id, 'UPDATE',
        JSON_OBJECT(
            'id', OLD.id,
            'order_id', OLD.order_id,
            'product_id', OLD.product_id,
            'quantity', OLD.quantity,
            'price_each', OLD.price_each
        ),
        JSON_OBJECT(
            'id', NEW.id,
            'order_id', NEW.order_id,
            'product_id', NEW.product_id,
            'quantity', NEW.quantity,
            'price_each', NEW.price_each
        )
    );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `price` decimal(10,2) NOT NULL CHECK (`price` >= 0),
  `stock` int(11) NOT NULL CHECK (`stock` >= 0),
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `price`, `stock`, `image_url`, `created_at`, `updated_at`) VALUES
(1, 1, 'Wireless Mouse', 24.99, 50, 'https://placehold.co/600x400', '2025-07-23 17:19:46', '2025-07-29 17:57:00'),
(2, 1, 'USB-C Cable', 9.90, 120, 'https://placehold.co/600x400', '2025-07-23 17:19:46', '2025-07-23 17:20:20'),
(3, 2, 'Clean Code', 39.50, 30, 'https://placehold.co/600x400', '2025-07-23 17:19:46', '2025-07-23 17:20:26'),
(4, 1, 'Iphone 98', 1.95, 100, 'https://placehold.co/600x400', '2025-07-26 17:36:00', '2025-07-26 17:36:00'),
(25, 1, 'Auriculares Bluetooth Noise Cancelling', 59.99, 25, 'https://placehold.co/600x400', '2025-07-29 04:01:38', '2025-07-29 04:03:04'),
(26, 1, 'Smartwatch Fitness Pro', 99.90, 15, 'https://placehold.co/600x400', '2025-07-29 04:01:38', '2025-07-29 04:03:04'),
(27, 1, 'Power Bank Carga Rápida 20000mAh', 39.90, 12, 'https://placehold.co/600x400', '2025-07-29 04:01:38', '2025-07-29 04:03:04'),
(28, 1, 'Cargador Inalámbrico Magnético', 22.00, 18, 'https://placehold.co/600x400', '2025-07-29 04:01:38', '2025-07-29 04:03:04'),
(29, 1, 'Altavoz Portátil con Subwoofer', 45.00, 19, 'https://placehold.co/600x400', '2025-07-29 04:01:38', '2025-07-30 03:20:37'),
(30, 2, 'Libro: “Hackea Tu Cerebro”', 14.99, 40, 'https://placehold.co/600x400', '2025-07-29 04:01:38', '2025-07-29 04:03:04'),
(31, 2, 'Guía de Fotografía Digital', 24.95, 30, 'https://placehold.co/600x400', '2025-07-29 04:01:38', '2025-07-29 04:03:04'),
(33, 2, 'Novela Gráfica “Sombra de Acero”', 29.99, 22, 'https://placehold.co/600x400', '2025-07-29 04:01:38', '2025-07-29 04:03:04'),
(34, 2, 'Curso de Escritura Creativa (Libro)', 19.80, 35, 'https://placehold.co/600x400', '2025-07-29 04:01:38', '2025-07-29 04:03:04');

--
-- Triggers `products`
--
DELIMITER $$
CREATE TRIGGER `products_after_delete` AFTER DELETE ON `products` FOR EACH ROW BEGIN
    INSERT INTO audit_log (table_name, record_id, action, old_values)
    VALUES ('products', OLD.id, 'DELETE',
        JSON_OBJECT(
            'id', OLD.id,
            'category_id', OLD.category_id,
            'name', OLD.name,
            'price', OLD.price,
            'stock', OLD.stock,
            'image_url', OLD.image_url
        )
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `products_after_insert` AFTER INSERT ON `products` FOR EACH ROW BEGIN
    INSERT INTO audit_log (table_name, record_id, action, new_values)
    VALUES ('products', NEW.id, 'INSERT', 
        JSON_OBJECT(
            'id', NEW.id,
            'category_id', NEW.category_id,
            'name', NEW.name,
            'price', NEW.price,
            'stock', NEW.stock,
            'image_url', NEW.image_url
        )
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `products_after_update` AFTER UPDATE ON `products` FOR EACH ROW BEGIN
    INSERT INTO audit_log (table_name, record_id, action, old_values, new_values)
    VALUES ('products', NEW.id, 'UPDATE',
        JSON_OBJECT(
            'id', OLD.id,
            'category_id', OLD.category_id,
            'name', OLD.name,
            'price', OLD.price,
            'stock', OLD.stock,
            'image_url', OLD.image_url
        ),
        JSON_OBJECT(
            'id', NEW.id,
            'category_id', NEW.category_id,
            'name', NEW.name,
            'price', NEW.price,
            'stock', NEW.stock,
            'image_url', NEW.image_url
        )
    );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `shipments`
--

CREATE TABLE `shipments` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `status` enum('pending','shipped','cancelled','returned') DEFAULT 'pending',
  `tracking_number` varchar(100) DEFAULT NULL,
  `shipped_at` datetime DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shipments`
--

INSERT INTO `shipments` (`id`, `order_id`, `status`, `tracking_number`, `shipped_at`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 'shipped', '', '2025-07-27 13:56:16', NULL, '2025-07-23 17:49:49', '2025-07-27 05:56:16'),
(2, 2, 'shipped', NULL, NULL, NULL, '2025-07-23 17:52:35', '2025-07-27 06:01:27'),
(3, 3, 'shipped', NULL, NULL, NULL, '2025-07-23 18:29:20', '2025-07-27 06:01:27'),
(4, 4, 'shipped', '', '2025-07-27 13:56:19', NULL, '2025-07-23 18:34:03', '2025-07-27 05:56:19'),
(5, 5, 'shipped', NULL, NULL, NULL, '2025-07-23 18:42:03', '2025-07-27 06:01:27'),
(6, 6, 'shipped', NULL, NULL, NULL, '2025-07-23 18:48:36', '2025-07-27 06:01:27'),
(7, 7, 'shipped', '', '2025-07-27 13:56:26', NULL, '2025-07-24 01:19:52', '2025-07-27 05:56:26'),
(8, 8, 'shipped', NULL, NULL, NULL, '2025-07-24 01:43:20', '2025-07-27 06:01:27'),
(9, 9, 'shipped', '', '2025-07-27 13:56:22', NULL, '2025-07-24 02:02:56', '2025-07-27 05:56:22'),
(10, 10, 'shipped', NULL, NULL, NULL, '2025-07-24 02:06:20', '2025-07-27 06:01:27'),
(11, 12, 'shipped', '', '2025-07-27 13:56:24', NULL, '2025-07-24 02:07:19', '2025-07-27 05:56:24'),
(12, 13, 'returned', '', NULL, '', '2025-07-24 02:09:23', '2025-07-27 06:05:17'),
(13, 14, 'cancelled', '', NULL, '', '2025-07-24 02:12:51', '2025-07-27 06:06:26'),
(14, 15, 'shipped', 'TRK202507270015952', '2025-07-27 14:07:16', '', '2025-07-24 02:24:00', '2025-07-27 06:07:16'),
(15, 17, 'shipped', 'TRK202507290017855', '2025-07-29 11:07:16', '', '2025-07-24 02:27:53', '2025-07-29 03:07:16'),
(16, 18, 'shipped', 'TRK202507270018669', '2025-07-27 14:07:18', '', '2025-07-24 02:37:58', '2025-07-27 06:07:18'),
(17, 19, 'shipped', 'TRK202507290019167', '2025-07-29 11:11:00', '', '2025-07-24 02:40:09', '2025-07-29 03:11:00'),
(18, 20, 'shipped', 'TRK202507270020598', '2025-07-27 14:07:19', '', '2025-07-24 02:57:31', '2025-07-27 06:07:19'),
(19, 21, 'shipped', 'TRK202507300021650', '2025-07-30 00:23:56', '', '2025-07-24 02:59:28', '2025-07-29 16:23:56'),
(20, 22, 'shipped', 'TRK202507270022708', '2025-07-27 14:07:19', '', '2025-07-24 04:38:54', '2025-07-27 06:07:19'),
(21, 23, 'cancelled', '', NULL, '', '2025-07-24 06:09:19', '2025-07-27 06:06:22'),
(22, 26, 'shipped', 'TRK202507270026916', '2025-07-27 14:07:21', '', '2025-07-24 15:08:34', '2025-07-27 06:07:21'),
(23, 28, 'shipped', 'TRK202507270028107', '2025-07-27 14:07:21', '', '2025-07-26 17:31:17', '2025-07-27 06:07:21'),
(33, 30, 'returned', '', NULL, '', '2025-07-27 07:06:07', '2025-07-29 16:23:53'),
(34, 31, 'cancelled', '', NULL, '', '2025-07-29 15:32:49', '2025-07-29 16:23:52'),
(35, 32, 'shipped', 'TRK202507300032747', '2025-07-30 00:23:50', '', '2025-07-29 15:41:12', '2025-07-29 16:23:50'),
(36, 33, 'shipped', 'TRK202507300033464', '2025-07-30 00:23:48', '', '2025-07-29 15:45:48', '2025-07-29 16:23:48'),
(40, 34, 'cancelled', '', NULL, '', '2025-07-29 16:12:54', '2025-07-29 16:23:49'),
(41, 35, 'returned', '', NULL, '', '2025-07-29 16:21:45', '2025-07-29 16:23:46'),
(43, 36, 'pending', NULL, NULL, NULL, '2025-08-07 02:19:46', '2025-08-07 02:19:46');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `is_admin` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `password_hash` char(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `is_admin`, `created_at`, `password_hash`) VALUES
(2, 'admin24@store.com', 1, '2025-07-24 05:30:39', '$2y$10$.FnXRvhUDrl.7Z./SzWNveL4tQUsxio.IGoVXIzsK0RyDkT7Tp/QC'),
(3, 'localhost@admin.com', 1, '2025-07-26 17:35:03', '$2y$10$5Qkwpm59g7/kaKoRXxaCL.2d.ZXE4iI2i/HSwWYr.zF6fFBOAQRgS');

--
-- Triggers `users`
--
DELIMITER $$
CREATE TRIGGER `users_after_delete` AFTER DELETE ON `users` FOR EACH ROW BEGIN
    INSERT INTO audit_log (table_name, record_id, action, old_values)
    VALUES ('users', OLD.id, 'DELETE',
        JSON_OBJECT(
            'id', OLD.id,
            'email', OLD.email,
            'is_admin', OLD.is_admin
        )
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `users_after_insert` AFTER INSERT ON `users` FOR EACH ROW BEGIN
    INSERT INTO audit_log (table_name, record_id, action, new_values)
    VALUES ('users', NEW.id, 'INSERT', 
        JSON_OBJECT(
            'id', NEW.id,
            'email', NEW.email,
            'is_admin', NEW.is_admin
            -- Intencionalmente no guardamos password_hash por seguridad
        )
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `users_after_update` AFTER UPDATE ON `users` FOR EACH ROW BEGIN
    INSERT INTO audit_log (table_name, record_id, action, old_values, new_values)
    VALUES ('users', NEW.id, 'UPDATE',
        JSON_OBJECT(
            'id', OLD.id,
            'email', OLD.email,
            'is_admin', OLD.is_admin
        ),
        JSON_OBJECT(
            'id', NEW.id,
            'email', NEW.email,
            'is_admin', NEW.is_admin
        )
    );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_admin_orders`
-- (See below for the actual view)
--
CREATE TABLE `v_admin_orders` (
`id` int(11)
,`user_id` int(11)
,`stripe_id` varchar(255)
,`total` decimal(10,2)
,`status` enum('pending','paid','shipped','cancelled')
,`created_at` datetime
,`shipping_name` varchar(120)
,`shipping_email` varchar(255)
,`shipping_address` text
,`phone` varchar(30)
,`card_last4` char(4)
,`card_brand` varchar(20)
,`item_count` bigint(21)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_admin_products`
-- (See below for the actual view)
--
CREATE TABLE `v_admin_products` (
`id` int(11)
,`name` varchar(150)
,`price` decimal(10,2)
,`stock` int(11)
,`image_url` varchar(255)
,`created_at` datetime
,`updated_at` datetime
,`category_id` int(11)
,`category` varchar(100)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_admin_users`
-- (See below for the actual view)
--
CREATE TABLE `v_admin_users` (
`id` int(11)
,`email` varchar(255)
,`password_hash` char(255)
,`is_admin` tinyint(1)
,`created_at` datetime
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_categories`
-- (See below for the actual view)
--
CREATE TABLE `v_categories` (
`id` int(11)
,`name` varchar(100)
,`description` text
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_orders`
-- (See below for the actual view)
--
CREATE TABLE `v_orders` (
`id` int(11)
,`user_id` int(11)
,`stripe_id` varchar(255)
,`total` decimal(10,2)
,`status` enum('pending','paid','shipped','cancelled')
,`created_at` datetime
,`items` mediumtext
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_products`
-- (See below for the actual view)
--
CREATE TABLE `v_products` (
`id` int(11)
,`name` varchar(150)
,`price` decimal(10,2)
,`stock` int(11)
,`image_url` varchar(255)
,`category` varchar(100)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_reports_detailed`
-- (See below for the actual view)
--
CREATE TABLE `v_reports_detailed` (
`order_id` int(11)
,`stripe_id` varchar(255)
,`customer_name` varchar(120)
,`customer_email` varchar(255)
,`shipping_address` text
,`phone` varchar(30)
,`order_total` decimal(10,2)
,`order_status` enum('pending','paid','shipped','cancelled')
,`order_date` datetime
,`card_last4` char(4)
,`card_brand` varchar(20)
,`ip_address` varchar(45)
,`latitude` decimal(10,8)
,`longitude` decimal(11,8)
,`product_name` varchar(150)
,`category_name` varchar(100)
,`quantity` int(11)
,`price_each` decimal(10,2)
,`item_subtotal` decimal(20,2)
,`shipment_status` enum('pending','shipped','cancelled','returned')
,`tracking_number` varchar(100)
,`shipped_at` datetime
,`shipment_notes` text
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_reports_sales`
-- (See below for the actual view)
--
CREATE TABLE `v_reports_sales` (
`order_id` int(11)
,`stripe_id` varchar(255)
,`customer_name` varchar(120)
,`customer_email` varchar(255)
,`order_total` decimal(10,2)
,`order_status` enum('pending','paid','shipped','cancelled')
,`order_date` datetime
,`card_last4` char(4)
,`card_brand` varchar(20)
,`total_items` bigint(21)
,`products` mediumtext
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_reports_shipments`
-- (See below for the actual view)
--
CREATE TABLE `v_reports_shipments` (
`shipment_id` int(11)
,`order_id` int(11)
,`customer_name` varchar(120)
,`customer_email` varchar(255)
,`shipping_address` text
,`phone` varchar(30)
,`order_total` decimal(10,2)
,`shipment_status` enum('pending','shipped','cancelled','returned')
,`tracking_number` varchar(100)
,`shipped_at` datetime
,`notes` text
,`order_date` datetime
,`shipment_created` datetime
,`shipment_updated` datetime
,`total_items` bigint(21)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_shipments`
-- (See below for the actual view)
--
CREATE TABLE `v_shipments` (
`id` int(11)
,`order_id` int(11)
,`shipment_status` enum('pending','shipped','cancelled','returned')
,`tracking_number` varchar(100)
,`shipped_at` datetime
,`notes` text
,`shipping_name` varchar(120)
,`shipping_email` varchar(255)
,`shipping_address` text
,`total` decimal(10,2)
,`order_date` datetime
,`item_count` bigint(21)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_users`
-- (See below for the actual view)
--
CREATE TABLE `v_users` (
`id` int(11)
,`email` varchar(255)
,`is_admin` tinyint(1)
,`created_at` datetime
);

-- --------------------------------------------------------

--
-- Structure for view `v_admin_orders`
--
DROP TABLE IF EXISTS `v_admin_orders`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_admin_orders`  AS SELECT `o`.`id` AS `id`, `o`.`user_id` AS `user_id`, `o`.`stripe_id` AS `stripe_id`, `o`.`total` AS `total`, `o`.`status` AS `status`, `o`.`created_at` AS `created_at`, `o`.`shipping_name` AS `shipping_name`, `o`.`shipping_email` AS `shipping_email`, `o`.`shipping_address` AS `shipping_address`, `o`.`phone` AS `phone`, `o`.`card_last4` AS `card_last4`, `o`.`card_brand` AS `card_brand`, count(`oi`.`id`) AS `item_count` FROM (`orders` `o` left join `order_items` `oi` on(`oi`.`order_id` = `o`.`id`)) GROUP BY `o`.`id` ORDER BY `o`.`created_at` DESC ;

-- --------------------------------------------------------

--
-- Structure for view `v_admin_products`
--
DROP TABLE IF EXISTS `v_admin_products`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_admin_products`  AS SELECT `p`.`id` AS `id`, `p`.`name` AS `name`, `p`.`price` AS `price`, `p`.`stock` AS `stock`, `p`.`image_url` AS `image_url`, `p`.`created_at` AS `created_at`, `p`.`updated_at` AS `updated_at`, `c`.`id` AS `category_id`, `c`.`name` AS `category` FROM (`products` `p` join `categories` `c` on(`p`.`category_id` = `c`.`id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `v_admin_users`
--
DROP TABLE IF EXISTS `v_admin_users`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_admin_users`  AS SELECT `users`.`id` AS `id`, `users`.`email` AS `email`, `users`.`password_hash` AS `password_hash`, `users`.`is_admin` AS `is_admin`, `users`.`created_at` AS `created_at` FROM `users` ORDER BY `users`.`created_at` DESC ;

-- --------------------------------------------------------

--
-- Structure for view `v_categories`
--
DROP TABLE IF EXISTS `v_categories`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_categories`  AS SELECT `categories`.`id` AS `id`, `categories`.`name` AS `name`, `categories`.`description` AS `description` FROM `categories` ORDER BY `categories`.`name` ASC ;

-- --------------------------------------------------------

--
-- Structure for view `v_orders`
--
DROP TABLE IF EXISTS `v_orders`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_orders`  AS SELECT `o`.`id` AS `id`, `o`.`user_id` AS `user_id`, `o`.`stripe_id` AS `stripe_id`, `o`.`total` AS `total`, `o`.`status` AS `status`, `o`.`created_at` AS `created_at`, concat('[',group_concat(concat('{"product_id":',`oi`.`product_id`,',"product_name":"',replace(`p`.`name`,'"','\\"'),'","quantity":',`oi`.`quantity`,',"price_each":',`oi`.`price_each`,'}') separator ','),']') AS `items` FROM ((`orders` `o` left join `order_items` `oi` on(`oi`.`order_id` = `o`.`id`)) left join `products` `p` on(`p`.`id` = `oi`.`product_id`)) GROUP BY `o`.`id` ;

-- --------------------------------------------------------

--
-- Structure for view `v_products`
--
DROP TABLE IF EXISTS `v_products`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_products`  AS SELECT `p`.`id` AS `id`, `p`.`name` AS `name`, `p`.`price` AS `price`, `p`.`stock` AS `stock`, `p`.`image_url` AS `image_url`, `c`.`name` AS `category` FROM (`products` `p` join `categories` `c` on(`p`.`category_id` = `c`.`id`)) WHERE `p`.`stock` > 0 ;

-- --------------------------------------------------------

--
-- Structure for view `v_reports_detailed`
--
DROP TABLE IF EXISTS `v_reports_detailed`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_reports_detailed`  AS SELECT `o`.`id` AS `order_id`, `o`.`stripe_id` AS `stripe_id`, `o`.`shipping_name` AS `customer_name`, `o`.`shipping_email` AS `customer_email`, `o`.`shipping_address` AS `shipping_address`, `o`.`phone` AS `phone`, `o`.`total` AS `order_total`, `o`.`status` AS `order_status`, `o`.`created_at` AS `order_date`, `o`.`card_last4` AS `card_last4`, `o`.`card_brand` AS `card_brand`, `o`.`ip_address` AS `ip_address`, `o`.`latitude` AS `latitude`, `o`.`longitude` AS `longitude`, `p`.`name` AS `product_name`, `c`.`name` AS `category_name`, `oi`.`quantity` AS `quantity`, `oi`.`price_each` AS `price_each`, `oi`.`quantity`* `oi`.`price_each` AS `item_subtotal`, `s`.`status` AS `shipment_status`, `s`.`tracking_number` AS `tracking_number`, `s`.`shipped_at` AS `shipped_at`, `s`.`notes` AS `shipment_notes` FROM ((((`orders` `o` left join `order_items` `oi` on(`oi`.`order_id` = `o`.`id`)) left join `products` `p` on(`p`.`id` = `oi`.`product_id`)) left join `categories` `c` on(`c`.`id` = `p`.`category_id`)) left join `shipments` `s` on(`s`.`order_id` = `o`.`id`)) ORDER BY `o`.`created_at` DESC, `oi`.`id` ASC ;

-- --------------------------------------------------------

--
-- Structure for view `v_reports_sales`
--
DROP TABLE IF EXISTS `v_reports_sales`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_reports_sales`  AS SELECT `o`.`id` AS `order_id`, `o`.`stripe_id` AS `stripe_id`, `o`.`shipping_name` AS `customer_name`, `o`.`shipping_email` AS `customer_email`, `o`.`total` AS `order_total`, `o`.`status` AS `order_status`, `o`.`created_at` AS `order_date`, `o`.`card_last4` AS `card_last4`, `o`.`card_brand` AS `card_brand`, count(`oi`.`id`) AS `total_items`, group_concat(concat(`p`.`name`,' (',`oi`.`quantity`,'x $',`oi`.`price_each`,')') separator ', ') AS `products` FROM ((`orders` `o` left join `order_items` `oi` on(`oi`.`order_id` = `o`.`id`)) left join `products` `p` on(`p`.`id` = `oi`.`product_id`)) GROUP BY `o`.`id` ;

-- --------------------------------------------------------

--
-- Structure for view `v_reports_shipments`
--
DROP TABLE IF EXISTS `v_reports_shipments`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_reports_shipments`  AS SELECT `s`.`id` AS `shipment_id`, `s`.`order_id` AS `order_id`, `o`.`shipping_name` AS `customer_name`, `o`.`shipping_email` AS `customer_email`, `o`.`shipping_address` AS `shipping_address`, `o`.`phone` AS `phone`, `o`.`total` AS `order_total`, `s`.`status` AS `shipment_status`, `s`.`tracking_number` AS `tracking_number`, `s`.`shipped_at` AS `shipped_at`, `s`.`notes` AS `notes`, `o`.`created_at` AS `order_date`, `s`.`created_at` AS `shipment_created`, `s`.`updated_at` AS `shipment_updated`, count(`oi`.`id`) AS `total_items` FROM ((`shipments` `s` join `orders` `o` on(`o`.`id` = `s`.`order_id`)) left join `order_items` `oi` on(`oi`.`order_id` = `o`.`id`)) GROUP BY `s`.`id` ;

-- --------------------------------------------------------

--
-- Structure for view `v_shipments`
--
DROP TABLE IF EXISTS `v_shipments`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_shipments`  AS SELECT `s`.`id` AS `id`, `s`.`order_id` AS `order_id`, `s`.`status` AS `shipment_status`, `s`.`tracking_number` AS `tracking_number`, `s`.`shipped_at` AS `shipped_at`, `s`.`notes` AS `notes`, `o`.`shipping_name` AS `shipping_name`, `o`.`shipping_email` AS `shipping_email`, `o`.`shipping_address` AS `shipping_address`, `o`.`total` AS `total`, `o`.`created_at` AS `order_date`, count(`oi`.`id`) AS `item_count` FROM ((`shipments` `s` join `orders` `o` on(`o`.`id` = `s`.`order_id`)) left join `order_items` `oi` on(`oi`.`order_id` = `o`.`id`)) WHERE `o`.`status` = 'paid' GROUP BY `s`.`id` ORDER BY `s`.`status` ASC, `s`.`created_at` ASC ;

-- --------------------------------------------------------

--
-- Structure for view `v_users`
--
DROP TABLE IF EXISTS `v_users`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_users`  AS SELECT `users`.`id` AS `id`, `users`.`email` AS `email`, `users`.`is_admin` AS `is_admin`, `users`.`created_at` AS `created_at` FROM `users` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_log`
--
ALTER TABLE `audit_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_table_record` (`table_name`,`record_id`),
  ADD KEY `idx_user_action` (`user_id`,`action`),
  ADD KEY `idx_created` (`created_at`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `stripe_id` (`stripe_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `shipments`
--
ALTER TABLE `shipments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_order_shipment` (`order_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_log`
--
ALTER TABLE `audit_log`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `shipments`
--
ALTER TABLE `shipments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `shipments`
--
ALTER TABLE `shipments`
  ADD CONSTRAINT `shipments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;
--
-- Database: `phpmyadmin`
--
CREATE DATABASE IF NOT EXISTS `phpmyadmin` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `phpmyadmin`;

-- --------------------------------------------------------

--
-- Table structure for table `pma__bookmark`
--

CREATE TABLE `pma__bookmark` (
  `id` int(11) NOT NULL,
  `dbase` varchar(255) NOT NULL DEFAULT '',
  `user` varchar(255) NOT NULL DEFAULT '',
  `label` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `query` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Bookmarks';

-- --------------------------------------------------------

--
-- Table structure for table `pma__central_columns`
--

CREATE TABLE `pma__central_columns` (
  `db_name` varchar(64) NOT NULL,
  `col_name` varchar(64) NOT NULL,
  `col_type` varchar(64) NOT NULL,
  `col_length` text DEFAULT NULL,
  `col_collation` varchar(64) NOT NULL,
  `col_isNull` tinyint(1) NOT NULL,
  `col_extra` varchar(255) DEFAULT '',
  `col_default` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Central list of columns';

-- --------------------------------------------------------

--
-- Table structure for table `pma__column_info`
--

CREATE TABLE `pma__column_info` (
  `id` int(5) UNSIGNED NOT NULL,
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `column_name` varchar(64) NOT NULL DEFAULT '',
  `comment` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `mimetype` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `transformation` varchar(255) NOT NULL DEFAULT '',
  `transformation_options` varchar(255) NOT NULL DEFAULT '',
  `input_transformation` varchar(255) NOT NULL DEFAULT '',
  `input_transformation_options` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Column information for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__designer_settings`
--

CREATE TABLE `pma__designer_settings` (
  `username` varchar(64) NOT NULL,
  `settings_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Settings related to Designer';

-- --------------------------------------------------------

--
-- Table structure for table `pma__export_templates`
--

CREATE TABLE `pma__export_templates` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL,
  `export_type` varchar(10) NOT NULL,
  `template_name` varchar(64) NOT NULL,
  `template_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Saved export templates';

-- --------------------------------------------------------

--
-- Table structure for table `pma__favorite`
--

CREATE TABLE `pma__favorite` (
  `username` varchar(64) NOT NULL,
  `tables` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Favorite tables';

-- --------------------------------------------------------

--
-- Table structure for table `pma__history`
--

CREATE TABLE `pma__history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `db` varchar(64) NOT NULL DEFAULT '',
  `table` varchar(64) NOT NULL DEFAULT '',
  `timevalue` timestamp NOT NULL DEFAULT current_timestamp(),
  `sqlquery` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='SQL history for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__navigationhiding`
--

CREATE TABLE `pma__navigationhiding` (
  `username` varchar(64) NOT NULL,
  `item_name` varchar(64) NOT NULL,
  `item_type` varchar(64) NOT NULL,
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Hidden items of navigation tree';

-- --------------------------------------------------------

--
-- Table structure for table `pma__pdf_pages`
--

CREATE TABLE `pma__pdf_pages` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `page_nr` int(10) UNSIGNED NOT NULL,
  `page_descr` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='PDF relation pages for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__recent`
--

CREATE TABLE `pma__recent` (
  `username` varchar(64) NOT NULL,
  `tables` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Recently accessed tables';

--
-- Dumping data for table `pma__recent`
--

INSERT INTO `pma__recent` (`username`, `tables`) VALUES
('root', '[{\"db\":\"crm_db\",\"table\":\"permissions\"},{\"db\":\"crm_db\",\"table\":\"users\"},{\"db\":\"crm_db\",\"table\":\"quotes\"},{\"db\":\"crm_db\",\"table\":\"materialized_sales_trends\"},{\"db\":\"crm_db\",\"table\":\"materialized_sales_performance\"},{\"db\":\"crm_db\",\"table\":\"access_requests\"},{\"db\":\"crm_db\",\"table\":\"products\"},{\"db\":\"crm_db\",\"table\":\"materialized_client_purchase_patterns\"},{\"db\":\"crm_db\",\"table\":\"audit_logs\"},{\"db\":\"crm_db\",\"table\":\"vw_audit_logs\"}]');

-- --------------------------------------------------------

--
-- Table structure for table `pma__relation`
--

CREATE TABLE `pma__relation` (
  `master_db` varchar(64) NOT NULL DEFAULT '',
  `master_table` varchar(64) NOT NULL DEFAULT '',
  `master_field` varchar(64) NOT NULL DEFAULT '',
  `foreign_db` varchar(64) NOT NULL DEFAULT '',
  `foreign_table` varchar(64) NOT NULL DEFAULT '',
  `foreign_field` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Relation table';

-- --------------------------------------------------------

--
-- Table structure for table `pma__savedsearches`
--

CREATE TABLE `pma__savedsearches` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `search_name` varchar(64) NOT NULL DEFAULT '',
  `search_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Saved searches';

-- --------------------------------------------------------

--
-- Table structure for table `pma__table_coords`
--

CREATE TABLE `pma__table_coords` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `pdf_page_number` int(11) NOT NULL DEFAULT 0,
  `x` float UNSIGNED NOT NULL DEFAULT 0,
  `y` float UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table coordinates for phpMyAdmin PDF output';

-- --------------------------------------------------------

--
-- Table structure for table `pma__table_info`
--

CREATE TABLE `pma__table_info` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `display_field` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table information for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__table_uiprefs`
--

CREATE TABLE `pma__table_uiprefs` (
  `username` varchar(64) NOT NULL,
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `prefs` text NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Tables'' UI preferences';

--
-- Dumping data for table `pma__table_uiprefs`
--

INSERT INTO `pma__table_uiprefs` (`username`, `db_name`, `table_name`, `prefs`, `last_update`) VALUES
('root', 'crm_db', 'users', '{\"sorted_col\":\"`users`.`force_password_change` DESC\"}', '2025-09-20 19:21:38');

-- --------------------------------------------------------

--
-- Table structure for table `pma__tracking`
--

CREATE TABLE `pma__tracking` (
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `version` int(10) UNSIGNED NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `schema_snapshot` text NOT NULL,
  `schema_sql` text DEFAULT NULL,
  `data_sql` longtext DEFAULT NULL,
  `tracking` set('UPDATE','REPLACE','INSERT','DELETE','TRUNCATE','CREATE DATABASE','ALTER DATABASE','DROP DATABASE','CREATE TABLE','ALTER TABLE','RENAME TABLE','DROP TABLE','CREATE INDEX','DROP INDEX','CREATE VIEW','ALTER VIEW','DROP VIEW') DEFAULT NULL,
  `tracking_active` int(1) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Database changes tracking for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__userconfig`
--

CREATE TABLE `pma__userconfig` (
  `username` varchar(64) NOT NULL,
  `timevalue` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `config_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='User preferences storage for phpMyAdmin';

--
-- Dumping data for table `pma__userconfig`
--

INSERT INTO `pma__userconfig` (`username`, `timevalue`, `config_data`) VALUES
('root', '2025-10-04 19:31:47', '{\"Console\\/Mode\":\"collapse\",\"NavigationWidth\":2}');

-- --------------------------------------------------------

--
-- Table structure for table `pma__usergroups`
--

CREATE TABLE `pma__usergroups` (
  `usergroup` varchar(64) NOT NULL,
  `tab` varchar(64) NOT NULL,
  `allowed` enum('Y','N') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='User groups with configured menu items';

-- --------------------------------------------------------

--
-- Table structure for table `pma__users`
--

CREATE TABLE `pma__users` (
  `username` varchar(64) NOT NULL,
  `usergroup` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Users and their assignments to user groups';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pma__central_columns`
--
ALTER TABLE `pma__central_columns`
  ADD PRIMARY KEY (`db_name`,`col_name`);

--
-- Indexes for table `pma__column_info`
--
ALTER TABLE `pma__column_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `db_name` (`db_name`,`table_name`,`column_name`);

--
-- Indexes for table `pma__designer_settings`
--
ALTER TABLE `pma__designer_settings`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_user_type_template` (`username`,`export_type`,`template_name`);

--
-- Indexes for table `pma__favorite`
--
ALTER TABLE `pma__favorite`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__history`
--
ALTER TABLE `pma__history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`,`db`,`table`,`timevalue`);

--
-- Indexes for table `pma__navigationhiding`
--
ALTER TABLE `pma__navigationhiding`
  ADD PRIMARY KEY (`username`,`item_name`,`item_type`,`db_name`,`table_name`);

--
-- Indexes for table `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  ADD PRIMARY KEY (`page_nr`),
  ADD KEY `db_name` (`db_name`);

--
-- Indexes for table `pma__recent`
--
ALTER TABLE `pma__recent`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__relation`
--
ALTER TABLE `pma__relation`
  ADD PRIMARY KEY (`master_db`,`master_table`,`master_field`),
  ADD KEY `foreign_field` (`foreign_db`,`foreign_table`);

--
-- Indexes for table `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_savedsearches_username_dbname` (`username`,`db_name`,`search_name`);

--
-- Indexes for table `pma__table_coords`
--
ALTER TABLE `pma__table_coords`
  ADD PRIMARY KEY (`db_name`,`table_name`,`pdf_page_number`);

--
-- Indexes for table `pma__table_info`
--
ALTER TABLE `pma__table_info`
  ADD PRIMARY KEY (`db_name`,`table_name`);

--
-- Indexes for table `pma__table_uiprefs`
--
ALTER TABLE `pma__table_uiprefs`
  ADD PRIMARY KEY (`username`,`db_name`,`table_name`);

--
-- Indexes for table `pma__tracking`
--
ALTER TABLE `pma__tracking`
  ADD PRIMARY KEY (`db_name`,`table_name`,`version`);

--
-- Indexes for table `pma__userconfig`
--
ALTER TABLE `pma__userconfig`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__usergroups`
--
ALTER TABLE `pma__usergroups`
  ADD PRIMARY KEY (`usergroup`,`tab`,`allowed`);

--
-- Indexes for table `pma__users`
--
ALTER TABLE `pma__users`
  ADD PRIMARY KEY (`username`,`usergroup`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__column_info`
--
ALTER TABLE `pma__column_info`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__history`
--
ALTER TABLE `pma__history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  MODIFY `page_nr` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
