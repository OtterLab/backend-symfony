-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 08, 2021 at 11:11 PM
-- Server version: 8.0.21
-- PHP Version: 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `royalshorelinehotel`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
CREATE TABLE IF NOT EXISTS `bookings` (
  `BookingID` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `RegisterID` int UNSIGNED NOT NULL,
  `RoomID` int UNSIGNED NOT NULL,
  `RoomImage` varchar(255) NOT NULL,
  `RoomType` text NOT NULL,
  `BookingDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `NumberOfAdult` smallint NOT NULL,
  `NumberOfChildren` smallint NOT NULL,
  `CheckInDate` date NOT NULL,
  `CheckOutDate` date NOT NULL,
  PRIMARY KEY (`BookingID`),
  KEY `bookingID_regID_FK` (`RegisterID`),
  KEY `roomID_FK` (`RoomID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

DROP TABLE IF EXISTS `login`;
CREATE TABLE IF NOT EXISTS `login` (
  `LoginID` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(50) NOT NULL,
  `RegisterID` int UNSIGNED NOT NULL,
  PRIMARY KEY (`LoginID`),
  KEY `registerID_FK` (`RegisterID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `register`
--

DROP TABLE IF EXISTS `register`;
CREATE TABLE IF NOT EXISTS `register` (
  `RegisterID` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `Firstname` varchar(50) NOT NULL,
  `Surname` varchar(50) NOT NULL,
  `PhoneNumber` varchar(50) NOT NULL,
  `EmailAddress` varchar(200) NOT NULL,
  PRIMARY KEY (`RegisterID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `registerlog`
--

DROP TABLE IF EXISTS `registerlog`;
CREATE TABLE IF NOT EXISTS `registerlog` (
  `RegisterID` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `URL` varchar(200) NOT NULL,
  `ResponseCode` int UNSIGNED NOT NULL,
  `IPaddress` varchar(200) NOT NULL,
  PRIMARY KEY (`RegisterID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

DROP TABLE IF EXISTS `rooms`;
CREATE TABLE IF NOT EXISTS `rooms` (
  `RoomID` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `RoomImage` varchar(255) NOT NULL,
  `RoomType` varchar(50) NOT NULL,
  `RoomPrice` decimal(10,2) NOT NULL,
  `RoomDescription` mediumtext NOT NULL,
  PRIMARY KEY (`RoomID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookingID_regID_FK` FOREIGN KEY (`RegisterID`) REFERENCES `register` (`RegisterID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `roomID_FK` FOREIGN KEY (`RoomID`) REFERENCES `rooms` (`RoomID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `login`
--
ALTER TABLE `login`
  ADD CONSTRAINT `registerID_FK` FOREIGN KEY (`RegisterID`) REFERENCES `register` (`RegisterID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
