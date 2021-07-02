-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 21, 2020 at 02:26 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pfe`
--

-- --------------------------------------------------------

--
-- Table structure for table `demande`
--

CREATE TABLE `demande` (
  `codeP` int(5) NOT NULL,
  `motiv` varchar(255) NOT NULL,
  `CodeL` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `demande`
--
ALTER TABLE `demande`
  ADD KEY `codeP_dem` (`codeP`),
  ADD KEY `codeL_dem` (`CodeL`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `demande`
--
ALTER TABLE `demande`
  ADD CONSTRAINT `codeL_dem` FOREIGN KEY (`CodeL`) REFERENCES `logement` (`CodeL`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `codeP_dem` FOREIGN KEY (`codeP`) REFERENCES `utilisateur` (`CodeU`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
