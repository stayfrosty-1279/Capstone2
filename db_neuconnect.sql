-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 10, 2024 at 02:30 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_neuconnect`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_ID` int NOT NULL,
  `firstName` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `middleName` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `lastName` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_general_ci,
  `contact` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `username` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `profileImage` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `org_id` text COLLATE utf8mb4_general_ci COMMENT 'For coordinators',
  `rc_signature` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_ID`, `firstName`, `middleName`, `lastName`, `address`, `contact`, `username`, `password`, `profileImage`, `role`, `org_id`, `rc_signature`) VALUES
(1, 'Admin', 'Of', 'NEUConnect', 'Manila, Philippines', '09162503084', 'admin@neuconnect.com', '81dc9bdb52d04dc20036dbd8313ed055', NULL, 'Super Admin', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `booked`
--

CREATE TABLE `booked` (
  `bid` int NOT NULL,
  `b_event_id` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `b_description` text COLLATE utf8mb4_general_ci,
  `b_status` varchar(100) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Pending' COMMENT 'Pending, Approved and Disapproved',
  `b_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `ev_id` int NOT NULL,
  `ev_name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ev_description` text COLLATE utf8mb4_general_ci,
  `ev_date` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ev_time` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ev_venue` text COLLATE utf8mb4_general_ci,
  `ev_status` varchar(100) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Ongoing' COMMENT 'Ongoing, Cancelled and Completed',
  `ev_org_id` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `f_id` int NOT NULL,
  `f_user_id` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `f_org_id` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `f_file_subject` text COLLATE utf8mb4_general_ci NOT NULL,
  `f_file_details` text COLLATE utf8mb4_general_ci NOT NULL,
  `f_file` text COLLATE utf8mb4_general_ci NOT NULL,
  `f_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `organization`
--

CREATE TABLE `organization` (
  `org_id` int NOT NULL,
  `org_name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `org_description` text COLLATE utf8mb4_general_ci,
  `org_vision` text COLLATE utf8mb4_general_ci,
  `org_mission` text COLLATE utf8mb4_general_ci,
  `org_logo` text COLLATE utf8mb4_general_ci,
  `org_type` text COLLATE utf8mb4_general_ci,
  `org_department` text COLLATE utf8mb4_general_ci,
  `org_status` varchar(100) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Active',
  `org_ismother` varchar(100) COLLATE utf8mb4_general_ci DEFAULT 'No'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `organization`
--

INSERT INTO `organization` (`org_id`, `org_name`, `org_description`, `org_vision`, `org_mission`, `org_logo`, `org_type`, `org_department`, `org_status`, `org_ismother`) VALUES
(1, 'Leaders in Information, Network, and Knowledge System', NULL, NULL, NULL, 'Leaders in Information, Network, and Knowledge System-links.png', 'Academic', 'College of Informatics and Computing Studies', 'Active', 'No'),
(2, 'Computer Studies Student Council', NULL, NULL, NULL, 'Computer Studies Student Council-cssc.png', 'Academic', 'College of Informatics and Computing Studies', 'Active', 'Yes'),
(3, 'Multimedia & Entertainment Technology Association', NULL, NULL, NULL, 'Multimedia & Entertainment Technology Association-1731242504.png', 'Academic', 'College of Informatics and Computing Studies', 'Active', 'No'),
(4, 'Society of Information Technology Students', NULL, NULL, NULL, 'Society of Information Technology Students-1731245476.png', 'Academic', 'College of Informatics and Computing Studies', 'Active', 'No');

-- --------------------------------------------------------

--
-- Table structure for table `participants`
--

CREATE TABLE `participants` (
  `p_id` int NOT NULL,
  `p_student_no` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'student #',
  `p_fullname` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `p_contact` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `p_idimage` text COLLATE utf8mb4_general_ci,
  `p_event_id` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `p_status` varchar(100) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Pending',
  `p_email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sliders`
--

CREATE TABLE `sliders` (
  `s_id` int NOT NULL,
  `s_image` text COLLATE utf8mb4_general_ci,
  `s_org_id` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `firstName` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `middleName` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `lastName` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `dateOfBirth` date DEFAULT NULL,
  `gender` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_general_ci,
  `contactNumber` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `idImage` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `dateRegistered` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `Activation_code` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Status` varchar(10) COLLATE utf8mb4_general_ci DEFAULT 'Inactive',
  `profile_picture` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Position` varchar(100) COLLATE utf8mb4_general_ci DEFAULT 'Officer',
  `org_id` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `admin_approval` varchar(100) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_ID`);

--
-- Indexes for table `booked`
--
ALTER TABLE `booked`
  ADD PRIMARY KEY (`bid`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`ev_id`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`f_id`);

--
-- Indexes for table `organization`
--
ALTER TABLE `organization`
  ADD PRIMARY KEY (`org_id`);

--
-- Indexes for table `participants`
--
ALTER TABLE `participants`
  ADD PRIMARY KEY (`p_id`);

--
-- Indexes for table `sliders`
--
ALTER TABLE `sliders`
  ADD PRIMARY KEY (`s_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `booked`
--
ALTER TABLE `booked`
  MODIFY `bid` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `ev_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `f_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `organization`
--
ALTER TABLE `organization`
  MODIFY `org_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `participants`
--
ALTER TABLE `participants`
  MODIFY `p_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sliders`
--
ALTER TABLE `sliders`
  MODIFY `s_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
