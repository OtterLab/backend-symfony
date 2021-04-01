-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 25, 2021 at 11:58 PM
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
-- Database: `testtable`
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
  `BookingDate` date NOT NULL,
  `NumberOfAdult` int NOT NULL,
  `NumberOfChildren` int NOT NULL,
  `CheckInDate` date NOT NULL,
  `CheckOutDate` date NOT NULL,
  PRIMARY KEY (`BookingID`),
  KEY `registerID_FK` (`RegisterID`),
  KEY `roomID_FK` (`RoomID`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`BookingID`, `RegisterID`, `RoomID`, `RoomImage`, `RoomType`, `BookingDate`, `NumberOfAdult`, `NumberOfChildren`, `CheckInDate`, `CheckOutDate`) VALUES
(5, 51, 7, 'hotel_room_queen_bedroom.png', 'King Bedroom Ocean Suite', '2021-03-09', 2, 1, '2021-03-16', '2021-03-20'),
(6, 53, 6, 'hotel_room_King_bedroom.png', 'King Bedroom Standard Suite', '2021-03-19', 2, 0, '2021-03-26', '2021-03-28');

-- --------------------------------------------------------

--
-- Table structure for table `register`
--

DROP TABLE IF EXISTS `register`;
CREATE TABLE IF NOT EXISTS `register` (
  `RegisterID` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Firstname` varchar(50) NOT NULL,
  `Surname` varchar(50) NOT NULL,
  `PhoneNumber` varchar(50) NOT NULL,
  `EmailAddress` varchar(200) NOT NULL,
  PRIMARY KEY (`RegisterID`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `register`
--

INSERT INTO `register` (`RegisterID`, `Username`, `Password`, `Firstname`, `Surname`, `PhoneNumber`, `EmailAddress`) VALUES
(51, 'HelloWorld123', '$2y$10$G8fqQylmsaq4jHFM1jCPq.UsUs0qnnhiYFehho7Zd8eGOnUOCLdPm', 'Hello', 'Ying', '12345678', 'sunworld38@outlook.com'),
(53, 'SayuriK232', '$2y$10$M8wW4kic8RyGaZbv7Kcf5ON8XCaMbv5ElWpaeovupXkq.CkXVFwb6', 'Sayuri', 'Lim', '7327284', 'sayk7@gmail.com');

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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`RoomID`, `RoomImage`, `RoomType`, `RoomPrice`, `RoomDescription`) VALUES
(6, 'hotel_room_King_bedroom.png', 'Update this row', '12.50', 'This Deluxe King Suite features a modern look.'),
(7, 'hotelroomqueenbedroompng', 'Queen Bedroom Ocean Silk', '15.50', 'This Queen Bedroom Ocean features a modern look.');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `registerID_FK` FOREIGN KEY (`RegisterID`) REFERENCES `register` (`RegisterID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `roomID_FK` FOREIGN KEY (`RoomID`) REFERENCES `rooms` (`RoomID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
