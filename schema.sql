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

Linux x86_64; rv:140.0) Gecko/20100101 Firefox/140.0', '2025-09-20 22:04:06'),
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

--
-- Triggers `backup_requests`
--
DELIMITER $$
CREATE TRIGGER `backup_requests_after_insert` AFTER INSERT ON `backup_requests` FOR EACH ROW BEGIN
    END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `backup_requests_after_update` AFTER UPDATE ON `backup_requests` FOR EACH ROW BEGIN
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
                
                END IF;
            
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

--
-- Triggers `settings`
--
DELIMITER $$
CREATE TRIGGER `settings_after_insert` AFTER INSERT ON `settings` FOR EACH ROW BEGIN
    END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `settings_after_update` AFTER UPDATE ON `settings` FOR EACH ROW BEGIN
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

--
-- Triggers `users`
--
DELIMITER $$
CREATE TRIGGER `users_after_insert` AFTER INSERT ON `users` FOR EACH ROW BEGIN
    END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `users_after_update` AFTER UPDATE ON `users` FOR EACH ROW BEGIN
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

a 123, Madrid, Espa&ntilde;a', 0, '2025-06-25 19:33:25', '2025-06-25 19:40:23'),
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

fono inteligente con c&aacute;mara de 50MP y 256GB almacenamiento', 1, 899.00, 21.00, 'unidad', 10, 1, '2025-06-25 19:33:25', '2025-06-25 19:42:38'),
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

n para renovaci&oacute;n de equipos inform&aacute;ticos. Incluye instalaci&oacute;n y configuraci&oacute;n.', 5.00, 439.50, 81.71, 519.32, 3, '2025-06-25 19:33:25', '2025-06-25 19:50:47'),
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

', 16.00, 'IVA', 'light', 'd/m/Y', 'smtp.gmail.com', 587, 'itbkup24@gmail.com', 'lsyw vjsr qold fpfn ', 'tls', 'itbkup24@gmail.com', 'Mi empresa CRM', '2025-06-25 17:31:55', '2025-06-26 21:34:43');

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

Linux x86_64; rv:140.0) Gecko/20100101 Firefox/140.0', '2025-07-30 09:19:35'),
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

--
-- Triggers `categories`
--
DELIMITER $$
CREATE TRIGGER `categories_after_delete` AFTER DELETE ON `categories` FOR EACH ROW BEGIN
    END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `categories_after_insert` AFTER INSERT ON `categories` FOR EACH ROW BEGIN
    END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `categories_after_update` AFTER UPDATE ON `categories` FOR EACH ROW BEGIN
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

--
-- Triggers `orders`
--
DELIMITER $$
CREATE TRIGGER `create_shipment_on_paid_order` AFTER INSERT ON `orders` FOR EACH ROW BEGIN
    IF NEW.status = 'paid' THEN
        END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `orders_after_delete` AFTER DELETE ON `orders` FOR EACH ROW BEGIN
    END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `orders_after_insert` AFTER INSERT ON `orders` FOR EACH ROW BEGIN
    END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `orders_after_update` AFTER UPDATE ON `orders` FOR EACH ROW BEGIN
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

--
-- Triggers `order_items`
--
DELIMITER $$
CREATE TRIGGER `order_items_after_delete` AFTER DELETE ON `order_items` FOR EACH ROW BEGIN
    END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `order_items_after_insert` AFTER INSERT ON `order_items` FOR EACH ROW BEGIN
    END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `order_items_after_update` AFTER UPDATE ON `order_items` FOR EACH ROW BEGIN
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

--
-- Triggers `products`
--
DELIMITER $$
CREATE TRIGGER `products_after_delete` AFTER DELETE ON `products` FOR EACH ROW BEGIN
    END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `products_after_insert` AFTER INSERT ON `products` FOR EACH ROW BEGIN
    END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `products_after_update` AFTER UPDATE ON `products` FOR EACH ROW BEGIN
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

--
-- Triggers `users`
--
DELIMITER $$
CREATE TRIGGER `users_after_delete` AFTER DELETE ON `users` FOR EACH ROW BEGIN
    END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `users_after_insert` AFTER INSERT ON `users` FOR EACH ROW BEGIN
    END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `users_after_update` AFTER UPDATE ON `users` FOR EACH ROW BEGIN
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
