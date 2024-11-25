SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


CREATE TABLE `admin` (
  `admin_ID` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `firstName` varchar(100) DEFAULT NULL,
  `middleName` varchar(50) DEFAULT NULL,
  `lastName` varchar(50) DEFAULT NULL,
  `address` text,
  `contact` varchar(100) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `profileImage` varchar(100) DEFAULT NULL,
  `role` varchar(255) DEFAULT NULL,
  `org_id` text COMMENT 'For coordinators',
  `rc_signature` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `admin` (`admin_ID`, `firstName`, `middleName`, `lastName`, `address`, `contact`, `username`, `password`, `profileImage`, `role`, `org_id`, `rc_signature`) VALUES
(1, 'NEUConnect', NULL, 'Admin', 'Manila, Philippines', '09162503084', 'admin@neuconnect.com', '81dc9bdb52d04dc20036dbd8313ed055', NULL, 'Super Admin', NULL, NULL);

CREATE TABLE `booked` (
  `bid` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `b_event_id` varchar(50) DEFAULT NULL,
  `b_description` text,
  `b_status` varchar(100) NOT NULL DEFAULT 'Pending' COMMENT 'Pending, Approved and Disapproved',
  `b_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `events` (
  `ev_id` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `ev_name` varchar(255) DEFAULT NULL,
  `ev_description` text,
  `ev_date` varchar(100) DEFAULT NULL,
  `ev_time` varchar(100) DEFAULT NULL,
  `ev_venue` text,
  `ev_status` varchar(100) NOT NULL DEFAULT 'Ongoing' COMMENT 'Ongoing, Cancelled and Completed',
  `ev_org_id` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `files` (
  `f_id` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `f_user_id` varchar(50) DEFAULT NULL,
  `f_org_id` varchar(50) NOT NULL,
  `f_file_subject` text NOT NULL,
  `f_file_details` text NOT NULL,
  `f_file` text NOT NULL,
  `f_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `organization` (
  `org_id` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `org_name` varchar(255) DEFAULT NULL,
  `org_description` text,
  `org_vision` text,
  `org_mission` text,
  `org_logo` text,
  `org_type` text,
  `org_department` text,
  `org_status` varchar(100) NOT NULL DEFAULT 'Active',
  `org_ismother` varchar(100) DEFAULT 'No'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `participants` (
  `p_id` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `p_student_no` varchar(100) DEFAULT NULL COMMENT 'student #',
  `p_fullname` varchar(255) DEFAULT NULL,
  `p_contact` varchar(100) DEFAULT NULL,
  `p_idimage` text,
  `p_event_id` varchar(50) DEFAULT NULL,
  `p_status` varchar(100) NOT NULL DEFAULT 'Pending',
  `p_email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `sliders` (
  `s_id` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `s_image` text,
  `s_org_id` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `users` (
  `user_id` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `firstName` varchar(100) DEFAULT NULL,
  `middleName` varchar(50) DEFAULT NULL,
  `lastName` varchar(50) DEFAULT NULL,
  `dateOfBirth` date DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `address` text,
  `contactNumber` varchar(10) DEFAULT NULL,
  `idImage` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `dateRegistered` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `Activation_code` varchar(50) DEFAULT NULL,
  `Status` varchar(10) DEFAULT 'Inactive',
  `profile_picture` varchar(255) DEFAULT NULL,
  `Position` varchar(100) DEFAULT 'Officer',
  `org_id` varchar(100) DEFAULT NULL,
  `admin_approval` varchar(100) NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

COMMIT;