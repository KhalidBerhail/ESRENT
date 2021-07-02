-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  jeu. 12 mars 2020 à 13:52
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
-- Structure de la table `equipement`
--

CREATE TABLE `equipement` (
  `CodeE` varchar(5) NOT NULL,
  `nom` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `equipement`
--

INSERT INTO `equipement` (`CodeE`, `nom`) VALUES
('01', 'serviettes,draps,savon,papier toilette, oreillers'),
('02', 'Climatisation'),
('03', 'Chauffage'),
('04', 'Seche-cheveux'),
('05', 'Penderie/Commode'),
('06', 'Fer a repasser'),
('07', 'Television'),
('08', 'Cheminée'),
('09', 'Entrée privée'),
('10', 'Shampoing'),
('11', 'Wi-Fi'),
('12', 'Bureau/Espace de travail'),
('13', 'Petit dejeuner,café,thé'),
('14', 'Extincteur'),
('15', 'Detecteur de monoxyde de carbon'),
('16', 'Detecteur de fumée'),
('17', 'Kit de premiers secours');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `equipement`
--
ALTER TABLE `equipement`
  ADD PRIMARY KEY (`CodeE`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
