-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 24, 2024 at 06:19 PM
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
-- Database: `neuconnect_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_ID` int(11) NOT NULL,
  `firstName` varchar(100) DEFAULT NULL,
  `middleName` varchar(50) DEFAULT NULL,
  `lastName` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `contact` varchar(100) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `profileImage` varchar(100) DEFAULT NULL,
  `role` varchar(255) DEFAULT NULL,
  `org_id` text DEFAULT NULL COMMENT 'For coordinators',
  `rc_signature` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_ID`, `firstName`, `middleName`, `lastName`, `address`, `contact`, `username`, `password`, `profileImage`, `role`, `org_id`, `rc_signature`) VALUES
(4, 'Tester1', 'R.', 'neuconnect', 'Candon City #489 San Antonio', '09178680239', 'superadmin@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'profile-1729785265-262352753_1597480717310427_972818494506717400_n.jpg', 'Super Admin', NULL, NULL),
(5, 'Tester2s', 'R.', 'neuconnect', 'Candon City #489 San Antonio', '9178680239', 'coordinator@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'profile-1729784990-sign.png', 'Coordinator', '6', 'https://usesignhouse.com/wp-content/uploads/2024/06/63f625050c1df924c4aac61c_mj-min.png'),
(8, 'Tester3', 'R.', 'neuconnect', 'Candon City #489 San Antonio', '09178680239', 'ricojames.softdev@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'profile-1728849022-empty-.jpg', 'Coordinator', '3', NULL),
(9, 'Tester4', 'R.', 'neuconnect', NULL, NULL, 'test2@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'profile-1728847428-thumbnail (1)-.png', 'Super Admin', NULL, NULL),
(10, 'Tester5', 'R.', 'neuconnect', 'Candon City #489 San Antonio', '09178680239', 'angelica.aglibut.official@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'profile-1729100528-background (1)-.png', 'Coordinator', '3', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `booked`
--

CREATE TABLE `booked` (
  `bid` int(11) NOT NULL,
  `b_event_id` varchar(50) DEFAULT NULL,
  `b_description` text DEFAULT NULL,
  `b_status` varchar(100) NOT NULL DEFAULT 'Pending' COMMENT 'Pending, Approved and Disapproved',
  `b_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booked`
--

INSERT INTO `booked` (`bid`, `b_event_id`, `b_description`, `b_status`, `b_date`) VALUES
(2, '2', '<ol><li>test</li><li>test</li><li>test</li><li>test</li><li>test</li></ol>', 'Approved', '2024-10-09 17:57:54');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `ev_id` int(11) NOT NULL,
  `ev_name` varchar(255) DEFAULT NULL,
  `ev_description` text DEFAULT NULL,
  `ev_date` varchar(100) DEFAULT NULL,
  `ev_time` varchar(100) DEFAULT NULL,
  `ev_venue` text DEFAULT NULL,
  `ev_status` varchar(100) NOT NULL DEFAULT 'Ongoing' COMMENT 'Ongoing, Cancelled and Completed',
  `ev_org_id` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`ev_id`, `ev_name`, `ev_description`, `ev_date`, `ev_time`, `ev_venue`, `ev_status`, `ev_org_id`) VALUES
(1, 'Leadership Workshop 1', 'We will refine your skills on programming and cooking. Come and Join Us!', '2024-10-25', '12:05 AM TO 3:00 AM', 'Venue', 'Completed', '6'),
(2, 'Leadership Workshop 2', 'We will refine your skills on programming and cooking. Come and Join Us!', '2024-10-25', '4:00 AM TO 4:00 AM', 'Venue', 'Ongoing', '6'),
(3, 'Leadership Workshop 3', 'We will refine your skills on programming and cooking. Come and Join Us!', '2024-10-25', '1:00 AM TO 4:35 AM', 'Venue', 'Ongoing', '6'),
(4, 'Leadership Workshop 4', 'Description', '2024-10-25', '1:00 PM TO 2:00 PM', 'Event Hall', 'Cancelled', '6'),
(6, 'Leadership Workshop 5', 'TEST', '2024-10-25', '4:17 PM TO 4:17 PM', 'TEST', 'Completed', '6');

-- --------------------------------------------------------

--
-- Table structure for table `faq`
--

CREATE TABLE `faq` (
  `faq_id` int(11) NOT NULL,
  `question` text DEFAULT NULL,
  `answer` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faq`
--

INSERT INTO `faq` (`faq_id`, `question`, `answer`) VALUES
(10, 'What is Lorem Ipsum?', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. '),
(11, 'What is Lorem Ipsum?', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. '),
(13, 'What is Lorem Ipsum?', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. '),
(15, 'What is Lorem Ipsum?', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. '),
(17, 'What is Lorem Ipsum?', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. '),
(19, 'What is Lorem Ipsum?', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. '),
(20, 'What is Lorem Ipsum?', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. '),
(22, 'What is Lorem Ipsum?', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. '),
(23, 'What is Lorem Ipsum?', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. '),
(24, 'What is Lorem Ipsum?', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. '),
(25, 'What is Lorem Ipsum?', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. '),
(26, 'What is Lorem Ipsum?', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. '),
(27, 'What is Lorem Ipsum?', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. '),
(28, 'What is Lorem Ipsum?', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. ');

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `f_id` int(11) NOT NULL,
  `f_user_id` varchar(50) DEFAULT NULL,
  `f_org_id` varchar(50) NOT NULL,
  `f_file_subject` text NOT NULL,
  `f_file_details` text NOT NULL,
  `f_file` text NOT NULL,
  `f_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `files`
--

INSERT INTO `files` (`f_id`, `f_user_id`, `f_org_id`, `f_file_subject`, `f_file_details`, `f_file`, `f_date`) VALUES
(4, '1', '6', 'TEST', 'test', '1728325980-Task 7.zip', '2024-10-07 18:33:00'),
(5, '1', '6', 'TEST', 'test', '1729100259-paraprobinsya.sql', '2024-10-16 17:37:39');

-- --------------------------------------------------------

--
-- Table structure for table `organization`
--

CREATE TABLE `organization` (
  `org_id` int(11) NOT NULL,
  `org_name` varchar(255) DEFAULT NULL,
  `org_description` text DEFAULT NULL,
  `org_vision` text DEFAULT NULL,
  `org_mission` text DEFAULT NULL,
  `org_logo` text DEFAULT NULL,
  `org_type` text DEFAULT NULL,
  `org_department` text DEFAULT NULL,
  `org_status` varchar(100) NOT NULL DEFAULT 'Active',
  `org_ismother` varchar(100) DEFAULT 'No'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `organization`
--

INSERT INTO `organization` (`org_id`, `org_name`, `org_description`, `org_vision`, `org_mission`, `org_logo`, `org_type`, `org_department`, `org_status`, `org_ismother`) VALUES
(1, 'ACSS', 'Our student organization is dedicated to fostering leadership, academic excellence, and community engagement among students.', 'To empower students with the skills, knowledge, and resources necessary to become successful leaders in their academic and professional lives.', 'We envision a campus community where every student has the opportunity to develop their full potential and make a positive impact on society.', 'ACSS.png', 'TYPE', 'DEPARTMENT', 'Active', 'No'),
(2, 'CSSC', 'Our student organization is dedicated to fostering leadership, academic excellence, and community engagement among students.', 'To empower students with the skills, knowledge, and resources necessary to become successful leaders in their academic and professional lives.', 'We envision a campus community where every student has the opportunity to develop their full potential and make a positive impact on society.', 'CSSC.png', 'TYPE', 'DEPARTMENT', 'Active', 'No'),
(3, 'LINKS', 'Our student organization is dedicated to fostering leadership, academic excellence, and community engagement among students.', 'To empower students with the skills, knowledge, and resources necessary to become successful leaders in their academic and professional lives.', 'We envision a campus community where every student has the opportunity to develop their full potential and make a positive impact on society.', 'LINKS.png', 'TYPE', 'DEPARTMENT', 'Active', 'No'),
(4, 'META', 'Our student organization is dedicated to fostering leadership, academic excellence, and community engagement among students.', 'To empower students with the skills, knowledge, and resources necessary to become successful leaders in their academic and professional lives.', 'We envision a campus community where every student has the opportunity to develop their full potential and make a positive impact on society.', 'META.png', 'TYPE', 'DEPARTMENT', 'Active', 'No'),
(5, 'PDG', 'Our student organization is dedicated to fostering leadership, academic excellence, and community engagement among students.', 'To empower students with the skills, knowledge, and resources necessary to become successful leaders in their academic and professional lives.', 'We envision a campus community where every student has the opportunity to develop their full potential and make a positive impact on society.', 'PDG.png', 'TYPE', 'DEPARTMENT', 'Active', 'No'),
(6, 'SITES', 'Our student organization is dedicated to fostering leadership, academic excellence, and community engagement among students.', 'To empower students with the skills, knowledge, and resources necessary to become successful leaders in their academic and professional lives.', 'We envision a campus community where every student has the opportunity to develop their full potential and make a positive impact on society.', 'SITES.png', 'TYPE 1', 'DEPARTMENT', 'Active', 'No'),
(7, 'Hello Kitty', 'Lahat ng pa bebe dito naka sali', 'Vision yes its my vision', 'Mission yes its my mission', 'Hello Kitty-download.jfif', 'TYPE 1', 'DEPARTMENT 1', 'Active', 'No'),
(8, 'Bus Nation', '', '', '', 'Bus Nation-thumbnail (1).png', 'Academic', 'DEPARTMENT OF COMPUTER SCIENCE', 'Active', 'Yes'),
(9, 'LEAF ORG', NULL, NULL, NULL, 'LEAF ORG-logo.ico', 'Academic', 'DEPARTMENT OF INFORMATION TECHNOLOGY', 'Active', 'No');

-- --------------------------------------------------------

--
-- Table structure for table `participants`
--

CREATE TABLE `participants` (
  `p_id` int(11) NOT NULL,
  `p_student_no` varchar(100) DEFAULT NULL COMMENT 'student #',
  `p_fullname` varchar(255) DEFAULT NULL,
  `p_contact` varchar(100) DEFAULT NULL,
  `p_idimage` text DEFAULT NULL,
  `p_event_id` varchar(50) DEFAULT NULL,
  `p_status` varchar(100) NOT NULL DEFAULT 'Pending',
  `p_email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `participants`
--

INSERT INTO `participants` (`p_id`, `p_student_no`, `p_fullname`, `p_contact`, `p_idimage`, `p_event_id`, `p_status`, `p_email`) VALUES
(5, '1110111', 'Engr Rico James Quirante', '09178680239', 'id-1729786211-262352753_1597480717310427_972818494506717400_n-.jpg', '1', 'Approved', 'ricojames.softdev@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `sliders`
--

CREATE TABLE `sliders` (
  `s_id` int(11) NOT NULL,
  `s_image` text DEFAULT NULL,
  `s_org_id` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sliders`
--

INSERT INTO `sliders` (`s_id`, `s_image`, `s_org_id`) VALUES
(1, 'T1.jpg', '6'),
(2, 'T2.jpg', '6'),
(3, 'T3.jpg', '6');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `firstName` varchar(100) DEFAULT NULL,
  `middleName` varchar(50) DEFAULT NULL,
  `lastName` varchar(50) DEFAULT NULL,
  `dateOfBirth` date DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `contactNumber` varchar(10) DEFAULT NULL,
  `idImage` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `dateRegistered` timestamp NULL DEFAULT current_timestamp(),
  `Activation_code` varchar(50) DEFAULT NULL,
  `Status` varchar(10) DEFAULT 'Inactive',
  `profile_picture` varchar(255) DEFAULT NULL,
  `Position` varchar(100) DEFAULT 'Officer',
  `org_id` varchar(100) DEFAULT NULL,
  `admin_approval` varchar(100) NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `firstName`, `middleName`, `lastName`, `dateOfBirth`, `gender`, `address`, `contactNumber`, `idImage`, `email`, `password`, `dateRegistered`, `Activation_code`, `Status`, `profile_picture`, `Position`, `org_id`, `admin_approval`) VALUES
(1, 'Testss123123', 'tests123', 'tests123s', '0000-00-00', 'Female', 'Addresss232', '9178680237', 'Schoolid-1719765209-361061480_235990992595233_4334306529649941218_n-.jpg', 'test@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', '2024-06-30 16:33:29', '1719765209', 'Active', 'Profile-1729403781-262352753_1597480717310427_972818494506717400_n-.jpg', 'English', '6', 'Approved'),
(36, 'Engrs', 'RJss', 'Quirantes', '2024-10-25', 'Female', 'test123', '9178680239', 'Schoolid-1729402956-262352753_1597480717310427_972818494506717400_n-.jpg', 'rjq.software.tester@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', '2024-10-20 05:42:36', '1729402956', 'Active', 'Profile-1729402956-262352753_1597480717310427_972818494506717400_n-.jpg', 'Officer', '6', 'Approved');

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
-- Indexes for table `faq`
--
ALTER TABLE `faq`
  ADD PRIMARY KEY (`faq_id`);

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
  MODIFY `admin_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `booked`
--
ALTER TABLE `booked`
  MODIFY `bid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `ev_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `faq`
--
ALTER TABLE `faq`
  MODIFY `faq_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `f_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `organization`
--
ALTER TABLE `organization`
  MODIFY `org_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `participants`
--
ALTER TABLE `participants`
  MODIFY `p_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `sliders`
--
ALTER TABLE `sliders`
  MODIFY `s_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
