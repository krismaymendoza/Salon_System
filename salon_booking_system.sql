-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 09, 2026 at 06:25 AM
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
-- Database: `salon_booking_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `booking_custom_id` varchar(50) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `status` enum('Pending','Approved','Completed','Cancelled') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `booking_custom_id`, `user_id`, `service_id`, `employee_id`, `appointment_date`, `appointment_time`, `status`, `created_at`) VALUES
(5, NULL, 4, 1, 1, '2026-05-09', '11:30:00', 'Approved', '2026-05-08 04:09:10'),
(6, 'bk-2026-06', 4, 4, 2, '2026-05-16', '11:30:00', 'Approved', '2026-05-08 16:15:57');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `specialization` varchar(150) DEFAULT NULL,
  `availability_status` enum('Available','Unavailable') DEFAULT 'Available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `user_id`, `specialization`, `availability_status`) VALUES
(1, 1, 'Hair Styling', 'Available'),
(2, 3, 'Hair Styling', 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `status` enum('Unread','Read') DEFAULT 'Unread',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `service_name` varchar(150) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `duration` varchar(50) NOT NULL,
  `specialization` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `service_name`, `image`, `price`, `duration`, `specialization`) VALUES
(1, 'Haircut', NULL, 150.00, '30 mins', NULL),
(2, 'Hair Coloring', NULL, 1200.00, '2 hours', NULL),
(4, 'Pedicure', NULL, 300.00, '1 hour', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `system_logs`
--

CREATE TABLE `system_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `role` enum('admin','employee','customer') DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_logs`
--

INSERT INTO `system_logs` (`id`, `user_id`, `role`, `action`, `created_at`) VALUES
(1, 2, 'admin', 'Logged in', '2026-05-07 08:46:21'),
(2, 2, 'admin', 'Logged in', '2026-05-07 14:12:54'),
(3, 2, 'admin', 'Logged in', '2026-05-07 14:18:58'),
(4, 2, 'admin', 'Logged in', '2026-05-07 14:38:31'),
(5, 2, 'admin', 'Logged in', '2026-05-07 14:58:32'),
(6, 2, 'admin', 'Logged in', '2026-05-07 15:04:28'),
(7, 2, 'admin', 'Deleted service: Manicure', '2026-05-07 15:19:29'),
(8, 4, 'customer', 'Logged in', '2026-05-07 15:44:18'),
(9, 3, 'employee', 'Logged in', '2026-05-07 15:52:54'),
(10, 4, 'customer', 'Logged in', '2026-05-07 15:53:14'),
(11, 4, 'customer', 'Booked appointment: Haircut', '2026-05-07 15:53:36'),
(12, 2, 'admin', 'Logged in', '2026-05-07 15:53:45'),
(13, 2, 'admin', 'Approved appointment for Juan (Haircut)', '2026-05-07 15:53:51'),
(14, 3, 'employee', 'Logged in', '2026-05-07 16:10:12'),
(15, 2, 'admin', 'Logged in', '2026-05-07 16:11:48'),
(16, 4, 'customer', 'Logged in', '2026-05-07 16:12:13'),
(17, 4, 'customer', 'Requested cancellation for appointment ID: 1', '2026-05-07 16:23:11'),
(18, 2, 'admin', 'Logged in', '2026-05-07 16:23:19'),
(19, 4, 'customer', 'Logged in', '2026-05-07 16:34:16'),
(20, 4, 'customer', 'Booked appointment: Haircut', '2026-05-07 16:34:33'),
(21, 2, 'admin', 'Logged in', '2026-05-07 16:34:44'),
(22, 2, 'admin', 'Admin approved cancellation for appointment ID: 2', '2026-05-07 16:35:52'),
(23, 4, 'customer', 'Logged in', '2026-05-07 16:36:07'),
(24, 4, 'customer', 'Booked appointment: Haircut', '2026-05-07 16:36:21'),
(25, 2, 'admin', 'Logged in', '2026-05-07 16:36:28'),
(26, 2, 'admin', 'Approved & assigned Juan to Employee ID: 1', '2026-05-07 16:41:26'),
(27, 2, 'admin', 'Marked appointment as Completed: Juan - Haircut', '2026-05-07 16:41:34'),
(28, 4, 'customer', 'Logged in', '2026-05-08 03:48:58'),
(29, 4, 'customer', 'Booked appointment: Haircut', '2026-05-08 03:49:14'),
(30, 2, 'admin', 'Logged in', '2026-05-08 03:49:21'),
(31, 2, 'admin', 'Approved & assigned Juan to Employee ID: 1', '2026-05-08 03:51:20'),
(32, 4, 'customer', 'Logged in', '2026-05-08 04:08:59'),
(33, 4, 'customer', 'Booked appointment: Haircut', '2026-05-08 04:09:10'),
(34, 2, 'admin', 'Logged in', '2026-05-08 04:09:17'),
(35, 2, 'admin', 'Approved & assigned Juan to Employee ID: 1', '2026-05-08 04:09:26'),
(36, 1, 'admin', 'Logged in', '2026-05-08 04:10:40'),
(37, 1, 'employee', 'Logged in', '2026-05-08 04:11:27'),
(38, 3, 'employee', 'Logged in', '2026-05-08 04:11:47'),
(39, 2, 'admin', 'Logged in', '2026-05-08 04:24:49'),
(40, 4, 'customer', 'Logged in', '2026-05-08 16:15:41'),
(41, 4, 'customer', 'Created booking: bk-2026-06 for Pedicure', '2026-05-08 16:15:57'),
(42, 2, 'admin', 'Logged in', '2026-05-08 16:18:44'),
(43, 2, 'admin', 'Approved & assigned Juan to Employee ID: 2', '2026-05-08 16:19:05'),
(44, 3, 'employee', 'Logged in', '2026-05-08 16:19:22');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `custom_id` varchar(50) DEFAULT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('customer','employee','admin') DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `custom_id`, `first_name`, `last_name`, `contact_number`, `email`, `password`, `role`, `created_at`, `reset_token`, `reset_expires`) VALUES
(1, NULL, 'Anna', 'Santos', '09123456789', 'anna@salon.com', '$2y$10$SeQMQpPT36oYcomSw0MwUO9/QnKWQQhxrkhtazxi/nk6P6WHIGXyy', 'employee', '2026-05-07 08:07:41', NULL, NULL),
(2, NULL, 'System', 'Admin', '09111111111', 'admin@salon.com', '$2y$10$PbKtgfzQWq90.JHsmzstLeIROyAheyRvSZ7o8Uge8ClaX1uoxpwde', 'admin', '2026-05-07 08:35:05', NULL, NULL),
(3, NULL, 'Maria', 'Santos', '09222222222', 'employee@salon.com', '$2y$10$SeQMQpPT36oYcomSw0MwUO9/QnKWQQhxrkhtazxi/nk6P6WHIGXyy', 'employee', '2026-05-07 08:35:05', NULL, NULL),
(4, 'cstmr-2026-0001', 'Juan', 'Dela Cruz', '09333333333', 'customer@salon.com', '$2y$10$tHtRj/NxKmRHjYwN0bKPOe4UWfrNZi.Ies.GhB0idZfPtv3p8yuey', 'customer', '2026-05-07 08:35:05', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_logs`
--
ALTER TABLE `system_logs`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `system_logs`
--
ALTER TABLE `system_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
