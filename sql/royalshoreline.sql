-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 31, 2021 at 01:15 AM
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
-- Database: `royalshoreline`
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
-- Table structure for table `register`
--

DROP TABLE IF EXISTS `register`;
CREATE TABLE IF NOT EXISTS `register` (
  `RegisterID` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `Username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Password` varchar(200) NOT NULL,
  `Firstname` varchar(50) NOT NULL,
  `Surname` varchar(50) NOT NULL,
  `PhoneNumber` varchar(50) NOT NULL,
  `EmailAddress` varchar(200) NOT NULL,
  `accessRights` varchar(50) NOT NULL,
  PRIMARY KEY (`RegisterID`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `register`
--

INSERT INTO `register` (`RegisterID`, `Username`, `Password`, `Firstname`, `Surname`, `PhoneNumber`, `EmailAddress`, `accessRights`) VALUES
(2, 'hellow', '$2y$10$cxpDwcKAhPKW2WFU1TpiNehs27sRyskK3M7NlBc5iS8AKJ2FZlPx2', 'Mickey', 'Mouse', '7877979', 'mickym2@outlook.com', 'admin'),
(6, 'SiaAir3829', '$2y$10$A4Xpi4o5r8p63YWFBXNGg.pt9p10HpNTXYD1WOm0Vk1Q.KTsg8eNe', 'Sammy', 'King', '23232323', 'sam39@outlook.com', 'customer');

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
  `RoomType` varchar(50) NOT NULL,
  `RoomPrice` decimal(10,2) NOT NULL,
  `RoomDescription` mediumtext NOT NULL,
  PRIMARY KEY (`RoomID`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`RoomID`, `RoomType`, `RoomPrice`, `RoomDescription`) VALUES
(1, 'QueenBedroomOceanSilk', '12.50', 'undefined'),
(2, 'KingBedroomPremierSuite', '13.00', 'This King Bedroom features a modern look'),
(3, 'KingBedroomStandard', '12.50', 'This King Bedroom features a modern look'),
(4, 'QueenBedroomSuite', '13.00', 'This Room is Luxury'),
(5, 'King Bedroom Luxury Suite', '13.50', 'This King Bedroom Luxury Suite is the best room'),
(6, 'QueenBedroomSuite', '13.00', 'gdgghhhhh'),
(7, 'KingBedroomLuxurySuite', '13.00', 'This Room is Luxury'),
(8, 'QueenBedroomOceanSilk', '12.50', 'This Queen Bedroom is Silky luxury'),
(9, 'KingBedroomPremierSuite', '13.00', 'This Queen Bedroom is Silky luxury');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookingID_regID_FK` FOREIGN KEY (`RegisterID`) REFERENCES `register` (`RegisterID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `roomID_FK` FOREIGN KEY (`RoomID`) REFERENCES `rooms` (`RoomID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
