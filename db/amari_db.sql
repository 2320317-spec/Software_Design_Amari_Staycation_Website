-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 07, 2026 at 06:56 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `amari_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','staff') DEFAULT 'admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin_amari', 'Amari2026!', 'admin'),
(5, 'admin', 'amari123', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `guest_title` varchar(50) DEFAULT NULL,
  `guest_name` varchar(255) NOT NULL,
  `guest_email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `check_in` date NOT NULL,
  `check_out` date NOT NULL,
  `special_requests` text DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `status` enum('pending','approved','declined') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `unit_id`, `guest_title`, `guest_name`, `guest_email`, `phone`, `check_in`, `check_out`, `special_requests`, `total_price`, `status`, `created_at`) VALUES
(5, 1, 'Mr.', 'awdawdawdawd', 'wadawdwad@gmail.com', NULL, '2026-04-06', '2026-04-07', NULL, NULL, 'approved', '2026-04-03 19:40:27'),
(7, 1, 'Mr.', 'wdaada', 'dawadw@gmail.com', NULL, '2026-04-04', '2026-04-05', NULL, NULL, 'approved', '2026-04-03 19:42:34'),
(8, 1, 'Mr.', 'wdaad', 'wdaad@gmail.com', NULL, '2026-05-01', '2026-05-03', NULL, NULL, 'declined', '2026-04-29 16:19:30'),
(9, 1, 'Mr.', 'testGuest', 'testGuest@gmail.com', NULL, '2026-05-01', '2026-05-02', NULL, NULL, 'approved', '2026-04-29 16:42:58'),
(10, 1, 'Mr.', 'Myke Marundan', 'lowelmarundan018@gmail.com', NULL, '2026-05-05', '2026-05-06', NULL, NULL, 'approved', '2026-05-03 17:25:57'),
(11, 1, 'Mr.', 'Myke Marundan', 'lowelmarundan018@gmail.com', NULL, '2026-05-04', '2026-05-05', NULL, NULL, 'declined', '2026-05-03 17:37:02'),
(12, 3, NULL, 'Myke Lhowelle', 'lowelmarundan018@gmail.com', '09270357554', '2026-05-09', '2026-05-10', 'design the room with valentines ', NULL, 'approved', '2026-05-07 16:17:44'),
(13, 2, NULL, 'Myke Lhowelle', 'lowelmarundan018@gmail.com', '09270357554', '2026-05-08', '2026-05-09', '', NULL, 'approved', '2026-05-07 16:19:07');

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `id` int(11) NOT NULL,
  `unit_name` varchar(100) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price_per_night` decimal(10,2) NOT NULL,
  `reservation_fee` decimal(10,2) DEFAULT 0.00,
  `amenities` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `status` enum('available','maintenance') DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id`, `unit_name`, `title`, `description`, `price_per_night`, `reservation_fee`, `amenities`, `image_path`, `status`) VALUES
(1, '', 'Ara 3', 'Relax in our spacious modern & peaceful condo with a refreshing pool view here at The Levels - Filinvest City Alabang. Just in-front of One Trium building and easy access to vibrant street life where running events happens.  \r\n\r\nThis cozy minimalist condo is designed for comfort, convenience, and unforgettable staycations.', 3199.00, 500.00, 'Pool, Gym', 'unit1.jpg', 'available'),
(2, '', 'Mariah 2', 'Perfect place to stay during your visit in the ever-busy, ever-vibrant Alabang in Muntinlupa City. Situated very near malls, restaurants, parks and night bars. Amari Staycation - Alabang is the best choice for those coming from Manila or North, as this is very near from Skyway, and from the South, as very near from SLEX.\r\n\r\nExperience your most cozy and convenient staycation ever! Book now!', 2199.00, 500.00, 'WiFi, Swimming Pool, City View, Game Room, Gym', 'unit2.jpg', 'available'),
(3, '', 'Amari 1', 'Relax in our cozy minimalist condo with a refreshing city view at Amari Staycation – Filinvest City, Alabang. Perfect for short stays, business trips, exams, and city errands—this thoughtfully designed space offers comfort, functionality, and an unbeatable location.\r\n\r\nExperience your most sulit and cozy staycation ever! Book now!', 2199.00, 500.00, 'WiFi, Swimming Pool, City View, Game Room, Gym', 'unit3.jpg', 'available');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `unit_id` (`unit_id`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
