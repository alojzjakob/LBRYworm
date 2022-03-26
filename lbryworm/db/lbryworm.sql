-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 26, 2022 at 04:45 PM
-- Server version: 5.6.51-cll-lve
-- PHP Version: 7.3.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lbryworm`
--

-- --------------------------------------------------------

--
-- Table structure for table `lw_books`
--

CREATE TABLE `lw_books` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `room_id` bigint(20) NOT NULL,
  `shelf_id` bigint(20) NOT NULL,
  `claim_id` tinytext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `book_data` longtext
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `lw_rooms`
--

CREATE TABLE `lw_rooms` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `room_name` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `room_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `lw_shelves`
--

CREATE TABLE `lw_shelves` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `room_id` bigint(20) NOT NULL,
  `shelf_name` varchar(1024) DEFAULT NULL,
  `shelf_data` longtext
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `lw_books`
--
ALTER TABLE `lw_books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id_index` (`user_id`),
  ADD KEY `user_id_shelf_id_index` (`user_id`,`shelf_id`),
  ADD KEY `user_id_room_id_index` (`user_id`,`room_id`),
  ADD KEY `room_id_index` (`room_id`),
  ADD KEY `shelf_id_index` (`shelf_id`);

--
-- Indexes for table `lw_rooms`
--
ALTER TABLE `lw_rooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_user` (`user_id`);

--
-- Indexes for table `lw_shelves`
--
ALTER TABLE `lw_shelves`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id_index` (`user_id`),
  ADD KEY `user_id_room_id_index` (`user_id`,`room_id`),
  ADD KEY `room_id_index` (`room_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `lw_books`
--
ALTER TABLE `lw_books`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lw_rooms`
--
ALTER TABLE `lw_rooms`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lw_shelves`
--
ALTER TABLE `lw_shelves`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
