-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  ven. 17 avr. 2020 à 00:31
-- Version du serveur :  10.4.11-MariaDB
-- Version de PHP :  7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `pfe`
--

-- --------------------------------------------------------

--
-- Structure de la table `saves`
--

CREATE TABLE `saves` (
  `CodeL` int(5) NOT NULL,
  `CodeU` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `saves`
--
ALTER TABLE `saves`
  ADD KEY `SAV1` (`CodeL`),
  ADD KEY `SAV2` (`CodeU`);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `saves`
--
ALTER TABLE `saves`
  ADD CONSTRAINT `SAV1` FOREIGN KEY (`CodeL`) REFERENCES `logement` (`CodeL`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `SAV2` FOREIGN KEY (`CodeU`) REFERENCES `utilisateur` (`CodeU`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
