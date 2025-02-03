-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 19, 2024 at 06:10 PM
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
-- Database: `ebiz`
--

-- --------------------------------------------------------

--
-- Table structure for table `cartdetail`
--

CREATE TABLE `cartdetail` (
  `cartID` int(5) DEFAULT NULL,
  `barangID` char(5) NOT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `custID` int(5) NOT NULL,
  `custName` varchar(25) DEFAULT NULL,
  `custEmail` varchar(25) DEFAULT NULL,
  `custAlamat` varchar(100) DEFAULT NULL,
  `custPhone` varchar(25) DEFAULT NULL,
  `custPass` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `msbarang`
--

CREATE TABLE `msbarang` (
  `barangID` char(5) NOT NULL,
  `barangName` varchar(25) DEFAULT NULL,
  `barangPrice` int(11) DEFAULT NULL,
  `weight` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `msbarang`
--

INSERT INTO `msbarang` (`barangID`, `barangName`, `barangPrice`, `weight`) VALUES
('1', 'Parsely', 10000, '250 gr'),
('2', 'Cabai', 80000, '1 kg'),
('3', 'Kentang', 12000, '1 kg'),
('4', 'Tomat', 25000, '1 kg'),
('5', 'Paprika', 18000, '250 gr'),
('6', 'Brokoli', 12000, '1 kg');

-- --------------------------------------------------------

--
-- Table structure for table `mscart`
--

CREATE TABLE `mscart` (
  `cartID` int(5) NOT NULL,
  `custID` int(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cartdetail`
--
ALTER TABLE `cartdetail`
  ADD KEY `barangID` (`barangID`),
  ADD KEY `cartID` (`cartID`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`custID`);

--
-- Indexes for table `msbarang`
--
ALTER TABLE `msbarang`
  ADD PRIMARY KEY (`barangID`);

--
-- Indexes for table `mscart`
--
ALTER TABLE `mscart`
  ADD PRIMARY KEY (`cartID`),
  ADD KEY `custID` (`custID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `custID` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mscart`
--
ALTER TABLE `mscart`
  MODIFY `cartID` int(5) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cartdetail`
--
ALTER TABLE `cartdetail`
  ADD CONSTRAINT `cartdetail_ibfk_1` FOREIGN KEY (`barangID`) REFERENCES `msbarang` (`barangID`),
  ADD CONSTRAINT `cartdetail_ibfk_2` FOREIGN KEY (`cartID`) REFERENCES `mscart` (`cartID`);

--
-- Constraints for table `mscart`
--
ALTER TABLE `mscart`
  ADD CONSTRAINT `mscart_ibfk_1` FOREIGN KEY (`custID`) REFERENCES `customer` (`custID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
