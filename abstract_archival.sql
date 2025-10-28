-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 10, 2025 at 04:08 PM
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
-- Database: `abstract_archival`
--

-- --------------------------------------------------------

--
-- Table structure for table `abstracts`
--

CREATE TABLE `abstracts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `fullname` varchar(225) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `department` varchar(100) NOT NULL,
  `year` int(4) NOT NULL,
  `file` varchar(255) DEFAULT NULL,
  `original_name` varchar(255) NOT NULL,
  `pdf_hash` varchar(255) NOT NULL,
  `status` enum('Pending Review','Approved','Rejected','Archived') DEFAULT 'Pending Review',
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `abstracts`
--

INSERT INTO `abstracts` (`id`, `user_id`, `fullname`, `title`, `author`, `department`, `year`, `file`, `original_name`, `pdf_hash`, `status`, `uploaded_at`) VALUES
(26, 0, 'jajasjh', 'WEB PORTAL WITH GENERATIVE ARTIFICIAL INTELLIGENCE-POWERED COLLEGE INQUIRY CHATBOT FOR ACI', 'GENEVIEVE C. OCA', 'BSIT', 2025, 'uploads/3. Abstract - Genevieve C. Oca - 2025.pdf', '3. Abstract - Genevieve C. Oca - 2025.pdf', '499f5237ca50559004a0498b042aaa6f', 'Approved', '2025-10-09 17:47:43'),
(27, 0, 'jajasjh', 'ONLINE ADOPTION FORUM SCHEDULER ATTENDANCE AND MONITORING SYSTEM', 'LEO H. GREFALDO, JR.', 'BSECE', 2025, 'uploads/3. Abstract - Leo H. Grefaldo, Jr. - 2025.pdf', '3. Abstract - Leo H. Grefaldo, Jr. - 2025.pdf', 'a39fa28798815ef67befe0a82e869718', 'Approved', '2025-10-09 18:53:25');

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `password`) VALUES
('admin', 'admincutie123');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` varchar(255) NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `message`, `is_read`, `created_at`) VALUES
(1, 0, 'Your thesis has been approved by the admin.', 0, '2025-10-08 19:05:10'),
(2, 0, 'Your thesis has been approved by the admin.', 0, '2025-10-09 17:53:14'),
(3, 0, 'Your abstract has been approved by the admin.', 0, '2025-10-09 18:54:10');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `confirm` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `otp` varchar(6) NOT NULL,
  `verified` tinyint(1) NOT NULL,
  `token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `fullname`, `email`, `password`, `confirm`, `created_at`, `otp`, `verified`, `token`) VALUES
(1, '', '', '$2y$10$0.jB7H.ktlKsbgoJ9QczTe9aoH7WFevy3t8MVjJ3IUWAUPBhBQ/B.', '', '2025-09-11 08:51:28', '', 0, NULL),
(13, 'hello', 'hello@gmail.com', '$2y$10$24oAJbWsq1ZdpgWJNb2EiegysGFd2IoVW18llJ6aIRWqjSKrkWhju', '', '2025-09-12 02:55:07', '', 0, NULL),
(14, 'hi', 'hi@gmail.com', '$2y$10$JS7UKtno1ZrZ4ffgli5hb.V8AszTzMQKhVfXW6lg7LCppq9U1zwSi', '', '2025-09-12 03:20:03', '', 0, NULL),
(15, 'pogi', 'pogi@gmail.com', '$2y$10$RfLrneXe..ns/pRRPB.ChuP/j4gAK.S878oNDzSEKl/qhxbiLoEGC', '', '2025-09-15 13:22:03', '', 0, NULL),
(23, 'jajasjh', 'sachiiiiisan@gmail.com', '$2y$10$QKUxVGuEHVe8vONqK9cCIeU565P5Bn3VSPud2a./6Jt9Kj0tKcdj2', '', '2025-09-16 06:07:19', '', 1, NULL),
(25, 'sabbyyyy', 'isabellefrancisco36@gmail.com', '$2y$10$/D6qlM6oCcOel3IH3HcLm.RuWJPPNvs/x4YwJ7qdxmK4LHiQbarQu', '', '2025-09-19 08:54:45', '', 1, NULL),
(26, 'w2222', 'ee', '$2y$10$M70.eSvgziJw79K55iuztuFl9AO3KyIq/ED8Iw/bAT0C7tF6IJTmG', '', '2025-10-02 14:01:57', '322650', 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `abstracts`
--
ALTER TABLE `abstracts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `abstracts`
--
ALTER TABLE `abstracts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
