-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 06, 2025 at 02:55 AM
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
-- Database: `salologan`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointment_backup`
--

CREATE TABLE `appointment_backup` (
  `aid` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `appointment_start` varchar(100) DEFAULT NULL,
  `appointment_end` varchar(50) DEFAULT NULL,
  `service_type` varchar(50) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointment_backup`
--

INSERT INTO `appointment_backup` (`aid`, `user_id`, `appointment_start`, `appointment_end`, `service_type`, `note`, `status`) VALUES
(3, 3, '09:00', '10:00', 'teethCleaning', '', '1'),
(4, 3, '09:00', '10:00', 'teethCleaning', '', '1'),
(5, 3, '09:00', '10:00', 'teethCleaning', '', '1'),
(6, 3, '09:00', '10:00', 'teethCleaning', '', '1'),
(7, 3, '15:00', '16:00', 'teethCleaning', '', '1'),
(8, 3, '09:00', '10:00', 'teethCleaning', '', '1'),
(9, 3, '10:00', '11:00', 'teethCleaning', '', '1'),
(10, 26, '09:00', '10:00', 'teethCleaning', 'test note', '1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointment_backup`
--
ALTER TABLE `appointment_backup`
  ADD PRIMARY KEY (`aid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointment_backup`
--
ALTER TABLE `appointment_backup`
  MODIFY `aid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
