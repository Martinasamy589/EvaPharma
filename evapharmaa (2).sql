-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 23, 2024 at 03:41 AM
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
-- Database: `evapharmaa`
--

-- --------------------------------------------------------

--
-- Table structure for table `dep`
--

CREATE TABLE `dep` (
  `departmentID` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dep`
--

INSERT INTO `dep` (`departmentID`, `name`) VALUES
(2, 'management'),
(7, 'acc');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL,
  `isHiring` enum('yes','no') NOT NULL,
  `isemployee` enum('option1','option2','option3') NOT NULL DEFAULT 'option1',
  `isemployeerr` enum('yes','no') NOT NULL DEFAULT 'yes',
  `isemployeess` enum('yes','no','none') NOT NULL DEFAULT 'yes',
  `besho` enum('yes','no') DEFAULT NULL,
  `martina` enum('yes','no') DEFAULT NULL,
  `tantona` enum('yes , no') DEFAULT NULL,
  `tantonaaa` enum('t','n') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`id`, `name`, `department`, `isHiring`, `isemployee`, `isemployeerr`, `isemployeess`, `besho`, `martina`, `tantona`, `tantonaaa`) VALUES
(1, 'mm', 'management', 'yes', 'option3', 'yes', 'no', 'no', NULL, NULL, NULL),
(2, 'martina', 'acc', 'yes', 'option1', 'yes', 'yes', 'yes', NULL, NULL, NULL),
(4, 'nana', 'accounting', 'yes', 'option1', 'yes', 'yes', NULL, NULL, NULL, NULL),
(7, 'besho l 2amosa', 'besho l 2mosa', 'yes', 'option1', 'yes', 'yes', 'yes', 'no', 'yes , no', 'n'),
(9, 'bb', 'acc', 'yes', 'option1', 'yes', 'yes', NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dep`
--
ALTER TABLE `dep`
  ADD PRIMARY KEY (`departmentID`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dep`
--
ALTER TABLE `dep`
  MODIFY `departmentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
