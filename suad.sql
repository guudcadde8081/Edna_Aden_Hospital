-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 23, 2025 at 03:52 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `suad`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `appointment_date` datetime DEFAULT NULL,
  `status` enum('pending','approved','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `appointment_time` time NOT NULL,
  `appointment_id` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `patient_id`, `doctor_id`, `appointment_date`, `status`, `created_at`, `appointment_time`, `appointment_id`) VALUES
(13, 1, 11, '2025-02-18 00:00:00', 'pending', '2025-02-17 08:05:37', '09:00:00', 'APPT-C092DFE8'),
(14, 1, 16, '2025-02-18 00:00:00', 'pending', '2025-02-17 17:15:44', '09:00:00', 'APPT-DB320AEF'),
(15, 1, 11, '2025-02-22 00:00:00', 'cancelled', '2025-02-17 17:16:00', '09:00:00', 'APPT-DAF488E2'),
(16, 17, 11, '2025-02-18 00:00:00', 'approved', '2025-02-17 17:29:43', '09:00:00', 'APPT-2D0C062F'),
(17, 1, 16, '2025-03-11 00:00:00', 'approved', '2025-03-11 07:41:55', '09:00:00', 'APPT-CA3BBDC8'),
(18, 18, 11, '2025-05-16 00:00:00', 'pending', '2025-05-15 07:54:52', '09:00:00', 'APPT-7FA07B73'),
(19, 19, 12, '2025-05-20 00:00:00', 'pending', '2025-05-19 16:53:51', '00:00:00', 'APPT-686DAF45'),
(20, 19, 11, '2025-05-28 00:00:00', 'pending', '2025-05-19 16:54:32', '09:00:00', 'APPT-6A641771'),
(21, 14, 11, '2025-05-23 00:00:00', 'approved', '2025-05-21 06:02:34', '09:00:00', 'APPT-07A43A52'),
(22, 1, 11, '2025-05-23 00:00:00', 'cancelled', '2025-05-22 14:44:45', '10:00:00', 'APPT-77EED7BB'),
(23, 14, 12, '2025-05-31 00:00:00', 'cancelled', '2025-05-22 21:14:01', '07:00:00', 'APPT-6EB1457E'),
(24, 21, 11, '2025-05-23 00:00:00', 'approved', '2025-05-22 21:30:25', '01:35:00', 'APPT-0263FCD2'),
(25, 21, 12, '2025-05-24 00:00:00', 'cancelled', '2025-05-22 22:18:08', '13:18:00', 'APPT-2189FDEB'),
(26, 21, 11, '2025-05-31 00:00:00', 'pending', '2025-05-22 22:58:15', '15:00:00', 'APPT-F0C0C56A'),
(27, 21, 11, '2025-05-26 00:00:00', 'cancelled', '2025-05-22 23:42:41', '07:40:00', 'APPT-511DFCDC'),
(28, 21, 11, '2025-05-26 00:00:00', 'approved', '2025-05-22 23:42:55', '07:40:00', 'APPT-80895C70');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_availability`
--

CREATE TABLE `doctor_availability` (
  `id` int(11) NOT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `available_date` date DEFAULT NULL,
  `available_time` time DEFAULT NULL,
  `status` enum('available','booked') DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `ehr_records`
--

CREATE TABLE `ehr_records` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `diagnosis` text NOT NULL,
  `prescription` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `appointment_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ehr_records`
--

INSERT INTO `ehr_records` (`id`, `patient_id`, `doctor_id`, `diagnosis`, `prescription`, `created_at`, `appointment_id`) VALUES
(3, 1, 11, 'Headache', 'Panadol', '2025-02-13 20:11:01', 0),
(4, 1, 11, 'Calool Taag', 'Maraq', '2025-02-15 20:32:21', 0),
(5, 1, 11, 'Calool Taag', 'Maraq', '2025-02-15 20:55:03', 0),
(6, 15, 11, 'Calool Xanun', 'Flagin', '2025-02-17 06:36:08', 12),
(7, 17, 11, 'Madax Xanuun', 'Paracetamol', '2025-02-17 17:35:15', 16),
(8, 1, 16, 'Flue', 'Ammoxicillin', '2025-03-11 07:51:07', 17),
(9, 21, 11, 'Nothing', 'Waxba', '2025-05-22 22:32:59', 24);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `doctor_id`, `patient_id`, `rating`, `comment`, `created_at`) VALUES
(2, 11, 1, 5, 'Such a good doctor', '2025-02-13 20:11:41');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('patient','doctor','admin') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `specialization` varchar(255) DEFAULT NULL,
  `security_question` varchar(255) NOT NULL,
  `security_answer` varchar(255) NOT NULL,
  `qualifications` text DEFAULT NULL,
  `available_days` varchar(255) DEFAULT NULL,
  `available_hours` varchar(255) DEFAULT NULL,
  `image` varchar(255) NOT NULL DEFAULT 'uploads/default.png',
  `dob` date DEFAULT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `experience` int(11) DEFAULT 1,
  `languages` varchar(100) DEFAULT NULL,
  `fee` decimal(6,2) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`, `specialization`, `security_question`, `security_answer`, `qualifications`, `available_days`, `available_hours`, `image`, `dob`, `phone`, `address`, `experience`, `languages`, `fee`, `is_active`) VALUES
(1, 'Abdirahman Ali', 'guudcadde8081@gmail.com', '$2y$10$TVpxDUhYO6ME9vExTSZt6eG4OCYgjpfsOOJqSgPSPA0eFl.p7je/W', 'patient', '2025-02-09 15:53:23', NULL, '', '', NULL, NULL, NULL, 'uploads/default.png', NULL, '', '', 1, NULL, NULL, 0),
(8, 'Abdirahman Ali', 'dirixanto@gmail.com', '$2y$10$TVpxDUhYO6ME9vExTSZt6eG4OCYgjpfsOOJqSgPSPA0eFl.p7je/W', 'admin', '2025-02-09 16:33:00', '', '', '', NULL, NULL, NULL, 'uploads/default.png', NULL, '', '', 1, NULL, NULL, 1),
(11, 'Suad Abdulkadir Adam', 'suad@gmail.com', '$2y$10$8.okL8eA4T0erRxWu5JBJ.z8cE54x3.7XQ5GZMPiIBgRENdTP1Gi2', 'doctor', '2025-02-11 12:48:26', 'Dermatologist', 'What is your mother\'s maiden name?', '$2y$10$kUUcPeopMv5bSffhEP52Euh9M4e91VRd6HlY4vJtIfjiuJUKE2tYe', 'MBBS, Amoud University', 'Monday, Tuesday', '07:00 - 12:30', 'uploads/1739774804_profile-pic.png', NULL, '', '', 10, 'Somali, English', '10.00', 1),
(12, 'Hassan Farah', 'hassan@gmail.com', '$2y$10$TVpxDUhYO6ME9vExTSZt6eG4OCYgjpfsOOJqSgPSPA0eFl.p7je/W', 'doctor', '2025-02-16 07:31:14', '', '', '', NULL, '', '', 'uploads/1739691074_WhatsApp Image 2025-01-21 at 11.16.45 AM.jpeg', NULL, '', '', 1, NULL, NULL, 1),
(14, 'Abdifatah Abdillahi', 'abdifatah@gmail.com', '$2y$10$TVpxDUhYO6ME9vExTSZt6eG4OCYgjpfsOOJqSgPSPA0eFl.p7je/W', 'patient', '2025-02-17 06:25:04', NULL, 'What is your mother\'s maiden name?', '$2y$10$p30RVI2pnV.n/vlH3lm/AOJsnQrvViMKrYd9cYgxjmypQTFKq9bQ2', NULL, NULL, NULL, 'uploads/default.png', NULL, '', '', 1, NULL, NULL, 1),
(15, 'Abdikhaliq Ahmed', 'abdikhalq@gmail.com', '$2y$10$TVpxDUhYO6ME9vExTSZt6eG4OCYgjpfsOOJqSgPSPA0eFl.p7je/W', 'patient', '2025-02-17 06:33:44', NULL, 'What is your mother\'s maiden name?', '$2y$10$1qq5Qw1vrEBXVdW77ZMtkuhuununGJII1PaYV18ydM7crn/TcY/EO', NULL, NULL, NULL, 'uploads/default.png', '2000-02-02', '+252 63 4455454', 'Borama', 1, NULL, NULL, 0),
(16, 'Hassan Ahmed', 'hassan1@gmail.com', '$2y$10$TVpxDUhYO6ME9vExTSZt6eG4OCYgjpfsOOJqSgPSPA0eFl.p7je/W', 'doctor', '2025-02-17 06:22:15', 'Radiologist', 'My Name', '$2y$10$zc0wAWOk7/oBqNH4ROpa1uNn8oTmC4.ex/LOPB27FWcE83HbNn5xe', 'MBBS, Amoud University', 'Monday, Tuesday, Wednesday, Thursday', '9:00 AM - 12:30 PM', 'uploads/1739780535_WhatsApp Image 2025-01-21 at 11.16.45 AM.jpeg', NULL, '', '', 1, NULL, NULL, 1),
(17, 'Hudayfi Hassan', 'hudayfi@gmail.com', '$2y$10$TVpxDUhYO6ME9vExTSZt6eG4OCYgjpfsOOJqSgPSPA0eFl.p7je/W', 'patient', '2025-02-17 17:27:27', NULL, '', '$2y$10$XAeSCdRV3qEaATtWZuJYQOzj3F33Xt0aASQiT.IM8MSnHARywvfC.', NULL, NULL, NULL, 'uploads/default.png', '2003-03-27', '0634555638', 'Shed dheer', 1, NULL, NULL, 0),
(18, 'Edwin', 'edwin@gmail.com', '$2y$10$zo7jy60y8W4qcNa7hGeZquxQ/Rnz2h/8VTJJ3mIuofyVPaZT0b5Ly', 'patient', '2025-05-15 07:54:21', NULL, 'What is your mother\'s maiden name?', '$2y$10$A4OyrWJ1D4MM5AYwwsUrZuvbQwW2vPbhCVyrxQs4BdiSvONd9FiCO', NULL, NULL, NULL, 'uploads/default.png', '2000-06-07', '0634555638', 'Halane', 1, NULL, NULL, 0),
(19, 'Hamse HAssan', 'hamse@gmail.com', '$2y$10$f8APzXdtuzCtFEGJffX4TOqcZlrQZ12SGOaOwGK.m5yE5THoKMwBm', 'patient', '2025-05-19 16:53:33', NULL, 'What is your mother\'s maiden name?', '$2y$10$9W3nasz77Qvv2cgMU9S2vO.hGf42rJY6r.PSXkVuQzQz17WEJuxLa', NULL, NULL, NULL, 'uploads/default.png', '2002-02-20', '4555638', 'Halane', 1, NULL, NULL, 0),
(20, 'Admin', 'admin@gmail.com', '$2y$10$Ya7VV3q40kPYoWMTC6L9/uq6rVh6GbsHp2gNTyoQYixJdoOzw2Geq', 'patient', '2025-05-21 05:54:02', NULL, 'What is your mother\'s maiden name?', '$2y$10$q5W37VYrGAh/7YItC/NMB.bAJho7HEybx8CYT69aWap7YVEpM6gUq', NULL, NULL, NULL, 'uploads/default.png', '2005-02-01', '4343434', 'Borama', 1, NULL, NULL, 0),
(21, 'Afifa Mohamed', 'afifa@gmail.com', '$2y$10$O4EcwdYLJU3UmnYjzdFc6.kq6eAYcVvcwjBTGvdwW87mU3tqWugeW', 'patient', '2025-05-22 21:24:27', NULL, 'What is your mother\'s maiden name?', '$2y$10$TYmyCrm.C1kXEp85IhgcxuSXvXxoHmXMgH4l3JzQTjN3mvClGmNtq', NULL, NULL, NULL, 'uploads/default.png', '1999-12-05', '4380915', 'Shacabka', 1, NULL, NULL, 1),
(22, 'Yasir Ali Dahir', 'yasir@gmail.com', '$2y$10$raW4RC83lCA9ecrpxLkiPOrf6bbeEHpyapJVkoi.S7yIni2roTpRy', 'doctor', '2025-05-23 00:08:59', 'Heart Surgery', '', '', 'MBBS, Amoud University', 'Monday, Tuesday, Wednesday', '08:00 - 11:00', 'uploads/1747959021_WhatsApp Image 2025-05-19 at 6.08.36 PM.jpeg', NULL, '', '', 5, 'English, Arabic, Somali', '5.00', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `appointment_id` (`appointment_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `doctor_availability`
--
ALTER TABLE `doctor_availability`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `ehr_records`
--
ALTER TABLE `ehr_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_id` (`doctor_id`),
  ADD KEY `patient_id` (`patient_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `doctor_availability`
--
ALTER TABLE `doctor_availability`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ehr_records`
--
ALTER TABLE `ehr_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `doctor_availability`
--
ALTER TABLE `doctor_availability`
  ADD CONSTRAINT `doctor_availability_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `ehr_records`
--
ALTER TABLE `ehr_records`
  ADD CONSTRAINT `ehr_records_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ehr_records_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
