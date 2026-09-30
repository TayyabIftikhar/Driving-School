-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 26, 2025 at 08:33 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4
CREATE DATABASE IF NOT EXISTS origin_db;
USE origin_db;
-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 27, 2025 at 09:33 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `origin_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `location` text NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `manager_name` varchar(100) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `name`, `location`, `is_active`, `manager_name`, `contact_number`) VALUES
(3, 'hjbu', 'aaaa', 1, NULL, NULL),
(4, 'aaaaaa', 'a', 0, NULL, NULL),
(5, 'aaaa', 'aaaaaaaaaaaaa', 1, NULL, NULL),
(6, 'aaaa', 'aaaaaaaaaaaaa', 1, NULL, NULL),
(8, 'hjbu', 'helllo', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `num_classes` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `difficulty_level` enum('beginner','intermediate','advanced') DEFAULT 'beginner',
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `name`, `price`, `num_classes`, `description`, `is_active`, `difficulty_level`, `image_url`) VALUES
(1, 'Basic Driving', 500.00, 10, NULL, 0, 'beginner', NULL),
(2, 'hxshuvx', 123.00, 12, 'helllo', 1, 'beginner', NULL),
(3, 'dwywyd', 2000.00, 2, 'ww', 1, 'beginner', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

CREATE TABLE `enrollments` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `status` enum('active','completed','cancelled') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrollments`
--

INSERT INTO `enrollments` (`id`, `student_id`, `course_id`, `start_date`, `status`) VALUES
(1, 2, 1, '2025-10-01', 'active'),
(2, 2, 1, '2025-09-04', 'active'),
(3, 7, 1, '2025-08-31', 'active'),
(4, 7, 1, '2025-08-31', 'active'),
(5, 7, 1, '2025-08-31', 'active'),
(6, 7, 1, '2025-08-31', 'active'),
(7, 8, 2, '1123-11-11', 'active'),
(8, 5, 1, '1111-11-11', 'active'),
(9, 10, 1, '2025-09-27', 'active'),
(10, 10, 1, '2025-09-26', 'active'),
(11, 11, 1, '2025-09-26', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `exams`
--

CREATE TABLE `exams` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `instructor_id` int(11) NOT NULL,
  `result` enum('pass','fail','pending') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `instructors`
--

CREATE TABLE `instructors` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `qualifications` text DEFAULT NULL,
  `availability` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `years_of_experience` int(11) DEFAULT 0,
  `nickname` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `instructors`
--

INSERT INTO `instructors` (`id`, `user_id`, `full_name`, `qualifications`, `availability`, `is_active`, `years_of_experience`, `nickname`) VALUES
(1, 3, 'Instructor One', 'Cert IV', NULL, 0, 0, NULL),
(2, 6, '', 'nigga', '', 0, 0, NULL),
(3, 17, 'Asim Butt', 'kk', NULL, 1, 0, NULL),
(4, 19, 'Asim Butt', 'asss', NULL, 1, 0, NULL),
(5, 20, 'ata', '123', NULL, 1, 0, NULL),
(12, 29, 'Muhammmad Asim', '12', '12', 1, 0, NULL),
(13, 30, 'Muhammmad Asim', '12', '12', 1, 0, NULL),
(15, 34, 'hira', 'theti', 'free', 1, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `due_date` date NOT NULL,
  `status` enum('pending','paid','overdue') DEFAULT 'pending',
  `reference_code` varchar(50) DEFAULT NULL,
  `processed_by` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `student_id`, `amount`, `due_date`, `status`, `reference_code`, `processed_by`) VALUES
(1, 2, 500.00, '2025-10-26', 'paid', NULL, NULL),
(2, 2, 500.00, '2025-10-26', 'paid', NULL, NULL),
(3, 7, 500.00, '2025-10-26', 'paid', NULL, NULL),
(4, 7, 500.00, '2025-10-26', 'paid', NULL, NULL),
(5, 7, 500.00, '2025-10-26', 'pending', NULL, NULL),
(6, 7, 500.00, '2025-10-26', 'paid', NULL, NULL),
(7, 8, 123.00, '2025-10-26', 'pending', NULL, NULL),
(8, 5, 500.00, '2025-10-26', 'pending', NULL, NULL),
(9, 10, 500.00, '2025-10-26', 'paid', NULL, NULL),
(10, 10, 500.00, '2025-10-26', 'paid', NULL, NULL),
(11, 11, 500.00, '2025-10-26', 'pending', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `lessons`
--

CREATE TABLE `lessons` (
  `id` int(11) NOT NULL,
  `enrollment_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `instructor_id` int(11) NOT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `status` enum('scheduled','completed','cancelled') DEFAULT 'scheduled',
  `feedback` text DEFAULT NULL,
  `gps_location` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lessons`
--

INSERT INTO `lessons` (`id`, `enrollment_id`, `date`, `time`, `instructor_id`, `vehicle_id`, `status`, `feedback`, `gps_location`) VALUES
(1, 8, '2025-09-26', '02:06:00', 1, 3, 'scheduled', NULL, NULL),
(2, 8, '2025-09-26', '01:09:00', 15, 2, 'scheduled', NULL, NULL),
(3, 9, '2025-09-26', '22:54:00', 15, 2, 'scheduled', NULL, NULL),
(4, 11, '2025-09-26', '15:57:00', 15, 3, 'scheduled', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) DEFAULT NULL,
  `content` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `type` enum('email','sms','inapp') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `content`, `date`, `type`) VALUES
(6, 1, NULL, 'qassssss', '2025-09-26 05:46:09', 'email'),
(7, 1, NULL, 'asimmmmmmmmmmmm', '2025-09-26 05:47:21', 'email'),
(9, 1, NULL, 'giiio', '2025-09-26 05:50:44', 'email'),
(10, 1, NULL, 'giiio', '2025-09-26 05:51:07', 'email'),
(11, 1, NULL, 'asimmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmm', '2025-09-26 05:51:59', 'email'),
(12, 1, NULL, 'abd here', '2025-09-26 05:55:15', 'email'),
(13, 1, 37, 'hello i am working', '2025-09-26 18:26:54', 'email');

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `id` int(11) NOT NULL,
  `related_id` int(11) NOT NULL,
  `type` enum('student','instructor','staff') NOT NULL,
  `content` text DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notes`
--

INSERT INTO `notes` (`id`, `related_id`, `type`, `content`, `attachment`, `created_at`) VALUES
(1, 7, 'student', 'aaa', '', '2025-09-26 04:46:33'),
(2, 8, 'student', '2e2', '', '2025-09-26 04:51:05'),
(3, 3, 'student', 'wwwwwwww', '', '2025-09-26 04:55:47'),
(4, 6, 'instructor', 'aaaaaaaaaaaaaaaaaaaaaaaaa', '', '2025-09-26 05:00:57'),
(5, 7, 'instructor', 'huuuuuuuuuuuuuuuuuu', '', '2025-09-26 05:01:25'),
(6, 8, 'instructor', 'huuuuuuuuuuuuuuuuuu', '', '2025-09-26 05:01:49'),
(7, 9, 'instructor', 'ssssssssssss', '', '2025-09-26 05:02:00'),
(8, 10, 'instructor', 'qsqsq', '', '2025-09-26 05:02:51'),
(9, 11, 'instructor', 'aaaaaaaaaaa', '', '2025-09-26 05:03:20'),
(10, 12, 'instructor', '12', '', '2025-09-26 05:03:56'),
(11, 13, 'instructor', '12', '', '2025-09-26 05:04:06'),
(12, 2, 'student', 'very bad student', '', '2025-09-26 06:03:59'),
(13, 15, 'instructor', 'free', '', '2025-09-26 06:09:04'),
(14, 9, 'student', 'Nikkah Field', '', '2025-09-26 06:33:06');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date` date NOT NULL,
  `method` enum('cash','card','online') NOT NULL,
  `transaction_reference` varchar(100) DEFAULT NULL,
  `payment_gateway` varchar(50) DEFAULT 'manual'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `invoice_id`, `amount`, `date`, `method`, `transaction_reference`, `payment_gateway`) VALUES
(5, 1, 12000.00, '2025-09-06', 'cash', NULL, 'manual'),
(6, 2, 500.00, '2025-09-01', 'cash', NULL, 'manual'),
(7, 2, 500.00, '2025-09-01', 'cash', NULL, 'manual'),
(8, 3, 200.00, '0011-11-11', 'cash', NULL, 'manual'),
(10, 4, 1200.00, '1111-11-11', 'cash', NULL, 'manual'),
(11, 3, 500.00, '2025-09-26', 'cash', NULL, 'manual'),
(12, 6, 500.00, '2025-09-26', 'cash', NULL, 'manual'),
(13, 6, 500.00, '2025-09-26', 'cash', NULL, 'manual'),
(14, 6, 500.00, '2025-09-26', 'cash', NULL, 'manual'),
(15, 9, 500.00, '2025-09-26', 'cash', NULL, 'manual'),
(16, 10, 500.00, '2025-09-26', 'cash', NULL, 'manual');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `license_status` enum('learner','provisional','full') DEFAULT 'learner',
  `progress` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `favorite_car` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `user_id`, `full_name`, `address`, `phone`, `license_status`, `progress`, `is_active`, `favorite_car`, `notes`) VALUES
(2, 5, 'Asim Butt', NULL, '03444029686', 'learner', '0', 0, NULL, NULL),
(5, 10, 'Asim Butt', NULL, '03444029686', 'learner', NULL, 0, NULL, NULL),
(6, 4, 'Asim Butt', NULL, '03444029686', 'learner', '', 0, NULL, NULL),
(7, 4, 'Asim Butt', NULL, '03444029686', 'learner', 'ss', 1, NULL, NULL),
(8, 9, '', NULL, '03444029686', 'learner', 'dd3d3d', 1, NULL, NULL),
(9, 35, 'Areej', NULL, '03444029686', 'learner', 'pta noa', 1, NULL, NULL),
(10, 36, 'tayyab', NULL, '034566', 'learner', NULL, 1, NULL, NULL),
(11, 37, 'tayyab123', NULL, '000000', 'learner', '', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','staff','instructor','student') NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `middle_name` varchar(100) DEFAULT NULL,
  `profile_color` varchar(20) DEFAULT 'blue',
  `last_login_ip` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `email`, `created_at`, `middle_name`, `profile_color`, `last_login_ip`) VALUES
(1, 'admin', '$2y$10$jAMtRio0n3ZHODUQSHWZx.9/b.n0JSTa2rfleY4FBL5gPZ9G2q3YG', 'admin', 'admin@example.com', '2025-09-25 17:20:56', NULL, 'blue', NULL),
(3, 'instructor1', '$2y$10$In57AyhCi/plpBRZXGpU6.doAuE/.bdDnAH8vCZ3EXqzYkwwsQMRu', 'instructor', 'inst@example.com', '2025-09-25 17:20:56', NULL, 'blue', NULL),
(4, 'student1', '$2y$10$zScO54wfuoluF0PXswyK8e9c9OqpqtFVYV76ICHQu1tDeMKzzdSZS', 'student', 'stud@example.com', '2025-09-25 17:20:56', NULL, 'blue', NULL),
(5, 'freak123', '$2y$10$kWJMAzZOK8fghGsq89Y/ce938mBvp3OWE7Y8sxA9uEh3indjrRHdm', 'student', 'asim190205@gmail.com', '2025-09-25 17:36:51', NULL, 'blue', NULL),
(6, 'eren yeager', '$2y$10$5/Nsj3rPXMPluSmgCFhxb.vQCgiLHBw.HsXyyheu1ec6JaG0kfe7G', 'instructor', 'mbutt.bscs22seecs@seecs.edu.pk', '2025-09-25 17:37:30', NULL, 'blue', NULL),
(8, 'freak1234555', '$2y$10$R4yFx76H/Yo4zQLMXLsqrexQasVCnp2HAs3fVkXNdRzCKHhZb9IoK', 'student', 'asim190205@gmail.com12', '2025-09-25 17:40:16', NULL, 'blue', NULL),
(9, 'asim', '$2y$10$KRAfCMq3Eo5YKZMEbdgxxOV.xT2sJY/oTGFppx.rLb5HFv9F5MRuu', 'student', 'm.asim05@yahoo.com', '2025-09-25 17:42:24', NULL, 'blue', NULL),
(10, 'freak', '$2y$10$RrEdJoSzxW/2Kx7569nmw.YormZbUWhb5Pl4CG.2e7IyzfA1o/.RS', 'student', 'asim190205@gmail.com', '2025-09-25 17:44:27', NULL, 'blue', NULL),
(17, 'freak3333333333', '$2y$10$be0EIv7YOJDsUVOpkkQDhOBOpS5uhwiHbB16A7HDSEMhtLa46sjg2', 'instructor', 'asim190205@gmail.com33333333333', '2025-09-25 17:49:19', NULL, 'blue', NULL),
(19, 'asim123', '$2y$10$9A/8NktJvBLNdfGO481djOvjLEUhhHi/Lx1JuAuNbMd1REQPPqRHS', 'instructor', 'asim190205@gmail.com', '2025-09-25 17:49:57', NULL, 'blue', NULL),
(20, 'ata', '$2y$10$0qWLdSsp/9fFNn61J/qByOr9kiAitvNTSadUp3hzUA3isGPKdFhkS', 'instructor', 'ata@gmail.com', '2025-09-25 17:50:51', NULL, 'blue', NULL),
(23, 'instructor_1758862857', '$2y$10$h0ZkYXeMM3AthdHFxxyUp.axx..89xQIc7NDtbrN8rGXkVqPXrfX6', 'instructor', 'instructor_1758862857@example.com', '2025-09-26 05:00:57', NULL, 'blue', NULL),
(24, 'instructor_1758862885', '$2y$10$R9Bdz68fjtq7uFYYJunnee92nU7rRSMPogEOQptGwxFX3HEEzVfeC', 'instructor', 'instructor_1758862885@example.com', '2025-09-26 05:01:25', NULL, 'blue', NULL),
(25, 'instructor_1758862909', '$2y$10$223ylVA/KldpF066gvG/wusUUsQUIljW/JvYNpLy2TTuDsDIoGfZu', 'instructor', 'instructor_1758862909@example.com', '2025-09-26 05:01:49', NULL, 'blue', NULL),
(26, 'instructor_1758862920', '$2y$10$11t3EwKkPLfnQkE3iwrEt.qU4hvr6FvFttTN4cRfcm0emlKtZOTp6', 'instructor', 'instructor_1758862920@example.com', '2025-09-26 05:02:00', NULL, 'blue', NULL),
(27, 'instructor_1758862971', '$2y$10$VH3mzdglNkNn3PowYIvY0u68oIHIv/tDmVLF.hiwQHaU1N4aaIfBW', 'instructor', 'instructor_1758862971@example.com', '2025-09-26 05:02:51', NULL, 'blue', NULL),
(28, 'instructor_1758863000', '$2y$10$75BGmZuwWkfYFCMsgNAZyuGB0xIr7EHKT0jvio2Y.2AaUiqAV770C', 'instructor', 'instructor_1758863000@example.com', '2025-09-26 05:03:20', NULL, 'blue', NULL),
(29, 'instructor_1758863036', '$2y$10$Qk87wvnw33xi6cM9Opw30OTPAVVHCIavCcQKh53L5t.YeA7J.j1N6', 'instructor', 'instructor_1758863036@example.com', '2025-09-26 05:03:56', NULL, 'blue', NULL),
(30, 'instructor_1758863046', '$2y$10$AQTGbPZA8R8U6uHH49U9wexKxrL.WNXario2AxFroHoGWjkRE4DNW', 'instructor', 'instructor_1758863046@example.com', '2025-09-26 05:04:06', NULL, 'blue', NULL),
(34, 'Hira', '$2y$10$1QqoRXpcFnxeHOkqP6qCfejfrL1lGtaN5epVAmlAEopshuNArTxH.', 'instructor', 'hira@gmail.com', '2025-09-26 06:08:31', NULL, 'blue', NULL),
(35, 'Areej', '$2y$10$UhNTgdzOkYnAsdDpdd.qJe47.rbp0m8n63uXxbEERPK8uDHC5z156', 'student', 'areej@gmail.com', '2025-09-26 06:27:18', NULL, 'blue', NULL),
(36, 'tayyab', '$2y$10$y6dt/GwWujdnLaH8xIScauN788L3huEdy32CYTZ2pmew3y1zvJ0GS', 'student', 't@gmail.com', '2025-09-26 17:51:05', NULL, 'blue', NULL),
(37, 'Tayyab1', '$2y$10$LuBJDc6FpUGBFitryACM2ugfW9fQKjM5f1StGY7Vo22IoXbRMzAnK', 'student', 'T@gmail.com', '2025-09-26 17:55:36', NULL, 'blue', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` int(11) NOT NULL,
  `model` varchar(100) NOT NULL,
  `registration` varchar(50) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `schedule` text DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `registration_city` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`id`, `model`, `registration`, `branch_id`, `schedule`, `color`, `registration_city`) VALUES
(4, 'honda', 'SS', 4, 'AS', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `exams`
--
ALTER TABLE `exams`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `instructor_id` (`instructor_id`);

--
-- Indexes for table `instructors`
--
ALTER TABLE `instructors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `lessons`
--
ALTER TABLE `lessons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `no_double_book` (`instructor_id`,`date`,`time`),
  ADD KEY `enrollment_id` (`enrollment_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_id` (`invoice_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `branch_id` (`branch_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `exams`
--
ALTER TABLE `exams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `instructors`
--
ALTER TABLE `instructors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `lessons`
--
ALTER TABLE `lessons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `enrollments_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`),
  ADD CONSTRAINT `enrollments_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`);

--
-- Constraints for table `exams`
--
ALTER TABLE `exams`
  ADD CONSTRAINT `exams_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`),
  ADD CONSTRAINT `exams_ibfk_2` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`id`);

--
-- Constraints for table `instructors`
--
ALTER TABLE `instructors`
  ADD CONSTRAINT `instructors_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`);

--
-- Constraints for table `lessons`
--
ALTER TABLE `lessons`
  ADD CONSTRAINT `lessons_ibfk_1` FOREIGN KEY (`enrollment_id`) REFERENCES `enrollments` (`id`),
  ADD CONSTRAINT `lessons_ibfk_2` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`id`);

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`);

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD CONSTRAINT `vehicles_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`);

DELIMITER $$
--
-- Events
--
CREATE DEFINER=`root`@`localhost` EVENT `lesson_reminder_event` ON SCHEDULE EVERY 1 MINUTE STARTS '2025-09-27 11:02:38' ON COMPLETION NOT PRESERVE ENABLE DO INSERT INTO messages (sender_id, receiver_id, content, type, date)
  SELECT 
    0, -- system/admin as sender
    u.id, -- student id (assuming enrollment → user)
    CONCAT('Hello ', u.username, ', your driving lesson starts in 5 minutes!'),
    'inapp', -- you can change to 'email' or 'sms'
    NOW()
  FROM lessons l
  JOIN enrollments e ON l.enrollment_id = e.id
  JOIN users u ON e.student_id = u.id
  WHERE TIMESTAMP(l.date, l.time) = DATE_ADD(NOW(), INTERVAL 5 MINUTE)
    AND l.status = 'scheduled'
    AND NOT EXISTS (
      SELECT 1 FROM messages m 
      WHERE m.receiver_id = u.id
        AND m.content LIKE '%lesson starts in 5 minutes%'
        AND DATE(m.date) = CURDATE()
    )$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
