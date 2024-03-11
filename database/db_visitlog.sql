-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 11, 2024 at 11:08 AM
-- Server version: 8.2.0
-- PHP Version: 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_visitlog`
--
CREATE DATABASE IF NOT EXISTS `db_visitlog` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `db_visitlog`;

-- --------------------------------------------------------

--
-- Table structure for table `notif`
--

DROP TABLE IF EXISTS `notif`;
CREATE TABLE IF NOT EXISTS `notif` (
  `id` int NOT NULL AUTO_INCREMENT,
  `role_dept` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `visitor_id` int NOT NULL,
  `activity_id` int NOT NULL,
  `type` int NOT NULL,
  `is_read` int NOT NULL COMMENT '0:false, 1:true',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_account`
--

DROP TABLE IF EXISTS `user_account`;
CREATE TABLE IF NOT EXISTS `user_account` (
  `id` int NOT NULL AUTO_INCREMENT,
  `active` int NOT NULL DEFAULT '1' COMMENT '1:true, 0:false',
  `role` int NOT NULL,
  `department` int NOT NULL,
  `firstname` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `lastname` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `image` text COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `user_account`
--

TRUNCATE TABLE `user_account`;
--
-- Dumping data for table `user_account`
--

INSERT INTO `user_account` (`id`, `active`, `role`, `department`, `firstname`, `lastname`, `email`, `image`, `username`, `password`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 0, 'Admin', 'Account', 'admin@visitlog.com', '', 'admin', '$2y$10$x4SkweniwHr7x4vjQgkgCuYJziUI9GUw2cFniIxo11esZTgWlOXOa', '2024-01-15 04:20:53', '2024-02-21 19:23:51'),
(3, 1, 3, 4, 'Deacon', 'Carney', 'selesirujo@mailinator.com', '', 'zytuk', '$2y$10$OF8IaolnGB8Zx9MQ4i7qo.UZhlibcV12CGYxAVByu4dlTWJ9ENQG2', '2024-01-18 11:57:57', '2024-03-07 17:23:41'),
(5, 1, 3, 3, 'Angelica', 'Mcclain', 'pufikug@mailinator.com', '', 'cunoqiqyw', '$2y$10$LnZOMSJA22uJr9JG.KeK2uBvYbBY50wCNclUYz13ESehVKUyTf.Ga', '2024-01-19 06:05:19', '2024-03-02 10:46:53'),
(6, 1, 3, 2, 'Medge', 'Mathis', 'gigisykavi@mailinator.com', '', 'fesamyfu', '$2y$10$MojfIbH.KjEl.JVJuyYnw.9kRC7BXljaeaCxxrutLGd1mFLaMeg6u', '2024-01-19 06:10:57', '2024-03-07 17:24:32'),
(7, 1, 2, 0, 'Gretchen', 'Hodges', 'kykaqo@mailinator.com', '', 'kocelep', '$2y$10$SD43RBnaDeINqBSnLIjDM.L39igY7tpZFUj8kHRekCVzah1j.jYVC', '2024-01-19 06:11:17', '2024-03-09 15:42:56'),
(8, 1, 3, 1, 'Neve', 'Haley', 'qitel@mailinator.com', '', 'qifanur', '$2y$10$uQwWta0.BTQBbMouEVUA5.VOg7pw94W6Bq/8xTGyUKLea7cq4Z1cS', '2024-01-20 05:54:04', '2024-03-04 18:10:33'),
(9, 1, 2, 0, 'Quon', 'Schroeder', 'vunoti@mailinator.com', '', 'lopydod', '$2y$10$9oDVDe0x5R7EQ62VfyCxKeXk/DhQXFHWpPyeaFiaEoGgNwoOh7H/.', '2024-01-20 06:00:32', '2024-03-06 17:26:16'),
(10, 0, 3, 1, 'Aphrodite', 'Orr', 'fovecom@mailinator.com', '', 'dolewy', '$2y$10$tO/R2lyN2ncm7vFftpcIaegkXpwohNEbYd7Wig.rHbPFr3SHplEkG', '2024-02-12 10:04:41', '2024-02-17 15:37:46'),
(11, 1, 3, 5, 'Nissim', 'Mccormick', 'hyxicali@mailinator.com', '', 'guard', '$2y$10$EYqrdPTSQCJyn.6dc6RigObzkz0GbnhAlv4wXo11gHskFXMI/8hee', '2024-02-14 11:45:02', '2024-03-04 18:10:41');

-- --------------------------------------------------------

--
-- Table structure for table `visitors`
--

DROP TABLE IF EXISTS `visitors`;
CREATE TABLE IF NOT EXISTS `visitors` (
  `id` int NOT NULL AUTO_INCREMENT,
  `rfid` text COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `age` int NOT NULL,
  `gender` int NOT NULL COMMENT '0:female, 1:male',
  `birthday` date NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'image path',
  `valid_id` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `purpose` text COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `visitors_activity`
--

DROP TABLE IF EXISTS `visitors_activity`;
CREATE TABLE IF NOT EXISTS `visitors_activity` (
  `id` int NOT NULL AUTO_INCREMENT,
  `visitor_id` int NOT NULL,
  `department` int NOT NULL,
  `purpose` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `time_in` datetime NOT NULL,
  `time_out` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
