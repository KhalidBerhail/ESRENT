-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  lun. 06 avr. 2020 à 19:44
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
-- Structure de la table `admin`
--

CREATE TABLE `admin` (
  `CodeAd` int(5) NOT NULL,
  `CIN` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `appartement`
--

CREATE TABLE `appartement` (
  `Codeapp` int(5) NOT NULL,
  `nbrC` int(1) DEFAULT NULL,
  `nbrP` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `eqlo`
--

CREATE TABLE `eqlo` (
  `CodeE` varchar(5) NOT NULL,
  `CodeL` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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

-- --------------------------------------------------------

--
-- Structure de la table `files`
--

CREATE TABLE `files` (
  `CodeF` int(11) NOT NULL,
  `CodeL` int(5) NOT NULL,
  `file` mediumblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `image`
--

CREATE TABLE `image` (
  `CodeImg` int(11) NOT NULL,
  `CodeL` int(5) DEFAULT NULL,
  `image` mediumblob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `logement`
--

CREATE TABLE `logement` (
  `CodeL` int(5) NOT NULL,
  `CodeP` int(5) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `adress` varchar(255) DEFAULT NULL,
  `description` varchar(3000) NOT NULL,
  `reglement` varchar(255) NOT NULL,
  `prix` double NOT NULL,
  `superficie` int(11) NOT NULL,
  `rating` float DEFAULT NULL,
  `SL_adr_nom` varchar(255) NOT NULL,
  `type` varchar(11) NOT NULL,
  `status` varchar(20) NOT NULL,
  `lat` float DEFAULT NULL,
  `lng` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

CREATE TABLE `messages` (
  `idMsg` int(11) NOT NULL,
  `Codesender` int(5) NOT NULL,
  `Codereciever` int(5) NOT NULL,
  `Msg` varchar(500) NOT NULL,
  `vue` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `proprietaire`
--

CREATE TABLE `proprietaire` (
  `CodeP` int(5) NOT NULL,
  `CIN` varchar(40) DEFAULT NULL,
  `adress` varchar(255) DEFAULT NULL,
  `nom` varchar(50) DEFAULT NULL,
  `prenom` varchar(50) DEFAULT NULL,
  `tel` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `ratings`
--

CREATE TABLE `ratings` (
  `CodeL` int(5) NOT NULL,
  `CodeU` int(5) NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `studio`
--

CREATE TABLE `studio` (
  `CodeS` int(5) NOT NULL,
  `nbrP` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `CodeU` int(5) NOT NULL,
  `username` varchar(40) DEFAULT NULL,
  `email` varchar(90) DEFAULT NULL,
  `pass` varchar(40) DEFAULT NULL,
  `type` varchar(20) NOT NULL,
  `Code_confirmation` varchar(40) DEFAULT NULL,
  `imageP` mediumblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`CodeAd`);

--
-- Index pour la table `appartement`
--
ALTER TABLE `appartement`
  ADD PRIMARY KEY (`Codeapp`);

--
-- Index pour la table `eqlo`
--
ALTER TABLE `eqlo`
  ADD PRIMARY KEY (`CodeE`,`CodeL`),
  ADD KEY `eqlo_ibfk_1` (`CodeL`);

--
-- Index pour la table `equipement`
--
ALTER TABLE `equipement`
  ADD PRIMARY KEY (`CodeE`);

--
-- Index pour la table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`CodeF`),
  ADD KEY `fileLog` (`CodeL`);

--
-- Index pour la table `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`CodeImg`),
  ADD KEY `image_ibfk_1` (`CodeL`);

--
-- Index pour la table `logement`
--
ALTER TABLE `logement`
  ADD PRIMARY KEY (`CodeL`),
  ADD UNIQUE KEY `nom` (`nom`),
  ADD KEY `ProLog` (`CodeP`);

--
-- Index pour la table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`idMsg`),
  ADD KEY `msgrecie` (`Codereciever`),
  ADD KEY `msgsend` (`Codesender`);

--
-- Index pour la table `proprietaire`
--
ALTER TABLE `proprietaire`
  ADD PRIMARY KEY (`CodeP`);

--
-- Index pour la table `ratings`
--
ALTER TABLE `ratings`
  ADD KEY `rating-logement-codeL` (`CodeL`),
  ADD KEY `rating-logement-codeU` (`CodeU`);

--
-- Index pour la table `studio`
--
ALTER TABLE `studio`
  ADD PRIMARY KEY (`CodeS`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`CodeU`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `files`
--
ALTER TABLE `files`
  MODIFY `CodeF` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `image`
--
ALTER TABLE `image`
  MODIFY `CodeImg` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `logement`
--
ALTER TABLE `logement`
  MODIFY `CodeL` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
  MODIFY `idMsg` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `CodeU` int(5) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `FkeyAd` FOREIGN KEY (`CodeAd`) REFERENCES `utilisateur` (`CodeU`);

--
-- Contraintes pour la table `appartement`
--
ALTER TABLE `appartement`
  ADD CONSTRAINT `applog` FOREIGN KEY (`Codeapp`) REFERENCES `logement` (`CodeL`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `eqlo`
--
ALTER TABLE `eqlo`
  ADD CONSTRAINT `eqlo_ibfk_2` FOREIGN KEY (`CodeE`) REFERENCES `equipement` (`CodeE`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `eqlo_logem_3` FOREIGN KEY (`CodeL`) REFERENCES `logement` (`CodeL`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `files`
--
ALTER TABLE `files`
  ADD CONSTRAINT `fileLog` FOREIGN KEY (`CodeL`) REFERENCES `logement` (`CodeL`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `image`
--
ALTER TABLE `image`
  ADD CONSTRAINT `imLog` FOREIGN KEY (`CodeL`) REFERENCES `logement` (`CodeL`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `logement`
--
ALTER TABLE `logement`
  ADD CONSTRAINT `ProLog` FOREIGN KEY (`CodeP`) REFERENCES `proprietaire` (`CodeP`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `msgrecie` FOREIGN KEY (`Codereciever`) REFERENCES `utilisateur` (`CodeU`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `msgsend` FOREIGN KEY (`Codesender`) REFERENCES `utilisateur` (`CodeU`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `proprietaire`
--
ALTER TABLE `proprietaire`
  ADD CONSTRAINT `FkeyPro` FOREIGN KEY (`CodeP`) REFERENCES `utilisateur` (`CodeU`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `rating-logement-codeL` FOREIGN KEY (`CodeL`) REFERENCES `logement` (`CodeL`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rating-logement-codeU` FOREIGN KEY (`CodeU`) REFERENCES `utilisateur` (`CodeU`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `studio`
--
ALTER TABLE `studio`
  ADD CONSTRAINT `Stlog` FOREIGN KEY (`CodeS`) REFERENCES `logement` (`CodeL`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
