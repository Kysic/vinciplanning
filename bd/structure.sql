-- phpMyAdmin SQL Dump
-- version 3.5.7
-- http://www.phpmyadmin.net
--
-- Client: sql31.free-h.org:3306
-- GÃ©nÃ©rÃ© le: Jeu 09 Mai 2013 Ã  12:40
-- Version du serveur: 5.5.30-MariaDB-log
-- Version de PHP: 5.3.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
/*SET time_zone = "+02:00";*/


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de donnÃ©es: `pl12350-freeh_vinciplanning`
--
-- --------------------------------------------------------

--
-- Structure de la table `groupes`
--

CREATE TABLE IF NOT EXISTS `groupes` (
  `groupeId` int(11) NOT NULL AUTO_INCREMENT,
  `groupe` varchar(50) CHARACTER SET utf8 NOT NULL,
  `droits` int(11) NOT NULL,
  PRIMARY KEY (`groupeId`),
  UNIQUE KEY `nom` (`groupe`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Contenu de la table `groupes`
--

INSERT INTO `groupes` (`groupeId`, `groupe`, `droits`) VALUES
(1, 'inactif', 0),
(2, 'nouveauCoequipier', 3),
(3, 'coequipierRegulier', 4),
(4, 'tuteur', 5),
(5, 'validateur', 6),
(6, 'admin', 7);

-- --------------------------------------------------------

--
-- Structure de la table `maraudes`
--

CREATE TABLE IF NOT EXISTS `maraudes` (
  `maraudeId` int(11) NOT NULL AUTO_INCREMENT,
  `dateMaraude` date NOT NULL,
  `compteRendu` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`maraudeId`),
  UNIQUE KEY `date` (`dateMaraude`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=51 ;

--
-- Contenu de la table `maraudes`
--

INSERT INTO `maraudes` (`maraudeId`, `dateMaraude`, `compteRendu`) VALUES
(9, '2013-05-09', NULL),
(10, '2013-05-17', NULL),
(11, '2013-05-21', NULL),
(12, '2013-05-23', NULL),
(13, '2013-05-30', NULL),
(14, '2013-05-14', NULL),
(15, '2013-05-10', NULL),
(19, '2013-05-18', NULL),
(21, '2013-05-26', NULL),
(22, '2013-05-27', NULL),
(23, '2013-05-11', NULL),
(30, '2013-05-16', NULL),
(32, '2013-05-12', NULL),
(39, '2013-05-13', NULL),
(40, '2013-05-15', NULL),
(41, '2013-05-22', NULL),
(45, '2013-05-19', NULL),
(47, '2013-04-20', NULL),
(48, '2013-06-08', NULL),
(49, '2013-05-03', NULL),
(50, '2013-05-06', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `maraudes_membres`
--

CREATE TABLE IF NOT EXISTS `maraudes_membres` (
  `participationId` int(11) NOT NULL AUTO_INCREMENT,
  `maraudeId` int(11) NOT NULL,
  `membreId` int(11) NOT NULL,
  `typeParticipation` enum('tuteur','coequipier') NOT NULL,
  `statutDemande` enum('nonTraite','valide','refuse','annule') NOT NULL DEFAULT 'nonTraite',
  `dateDemande` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dateModifStatutDemande` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`participationId`),
  UNIQUE KEY `membreId` (`membreId`,`maraudeId`),
  UNIQUE KEY `maraudeId` (`maraudeId`,`membreId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=57 ;

--
-- Contenu de la table `maraudes_membres`
--

INSERT INTO `maraudes_membres` (`participationId`, `maraudeId`, `membreId`, `typeParticipation`, `statutDemande`, `dateDemande`, `dateModifStatutDemande`) VALUES
(12, 9, 33, 'tuteur', 'valide', '2013-05-09 08:37:27', '2013-05-09 09:57:34'),
(13, 10, 33, 'tuteur', 'refuse', '2013-05-09 08:39:57', '2013-05-09 08:44:20'),
(14, 11, 33, 'tuteur', 'valide', '2013-05-09 08:40:03', '2013-05-09 08:43:52'),
(15, 12, 33, 'tuteur', 'nonTraite', '2013-05-09 08:40:06', '0000-00-00 00:00:00'),
(16, 13, 33, 'tuteur', 'valide', '2013-05-09 08:40:10', '2013-05-09 08:44:18'),
(17, 14, 33, 'tuteur', 'valide', '2013-05-09 08:40:14', '2013-05-09 08:43:49'),
(18, 15, 33, 'tuteur', 'valide', '2013-05-09 08:40:18', '2013-05-09 08:43:45'),
(20, 15, 34, 'coequipier', 'valide', '2013-05-09 08:45:30', '2013-05-09 08:46:38'),
(21, 14, 34, 'coequipier', 'valide', '2013-05-09 08:45:34', '2013-05-09 08:46:47'),
(22, 19, 34, 'coequipier', 'valide', '2013-05-09 08:45:37', '2013-05-09 08:46:23'),
(23, 12, 34, 'coequipier', 'valide', '2013-05-09 08:45:40', '2013-05-09 08:50:56'),
(24, 21, 34, 'coequipier', 'valide', '2013-05-09 08:45:42', '2013-05-09 08:47:13'),
(25, 22, 34, 'coequipier', 'nonTraite', '2013-05-09 08:45:48', '0000-00-00 00:00:00'),
(26, 23, 32, 'tuteur', 'valide', '2013-05-09 08:46:12', '2013-05-09 08:46:33'),
(27, 19, 32, 'tuteur', 'valide', '2013-05-09 08:46:15', '2013-05-09 08:46:25'),
(28, 10, 32, 'tuteur', 'valide', '2013-05-09 08:47:16', '2013-05-09 08:51:17'),
(29, 9, 32, 'tuteur', 'valide', '2013-05-09 08:48:08', '2013-05-09 09:57:32'),
(30, 19, 31, 'coequipier', 'valide', '2013-05-09 08:48:43', '2013-05-09 08:50:29'),
(31, 14, 31, 'coequipier', 'valide', '2013-05-09 08:48:51', '2013-05-09 08:50:37'),
(32, 11, 31, 'coequipier', 'valide', '2013-05-09 08:48:53', '2013-05-09 08:54:38'),
(33, 30, 31, 'coequipier', 'annule', '2013-05-09 08:49:00', '2013-05-09 08:53:59'),
(34, 21, 31, 'coequipier', 'valide', '2013-05-09 08:49:14', '2013-05-09 08:50:16'),
(35, 32, 31, 'coequipier', 'valide', '2013-05-09 08:49:23', '2013-05-09 08:50:11'),
(36, 32, 34, 'coequipier', 'valide', '2013-05-09 08:49:38', '2013-05-09 08:50:13'),
(37, 30, 34, 'coequipier', 'valide', '2013-05-09 08:49:42', '2013-05-09 08:50:51'),
(38, 10, 31, 'coequipier', 'valide', '2013-05-09 08:51:43', '2013-05-09 08:52:15'),
(39, 10, 34, 'coequipier', 'valide', '2013-05-09 08:51:52', '2013-05-09 08:52:09'),
(40, 30, 28, 'coequipier', 'refuse', '2013-05-09 08:53:19', '2013-05-09 08:56:39'),
(41, 30, 33, 'tuteur', 'valide', '2013-05-09 08:53:51', '2013-05-09 08:54:15'),
(42, 39, 32, 'tuteur', 'valide', '2013-05-09 08:55:09', '2013-05-09 08:55:18'),
(43, 40, 32, 'tuteur', 'valide', '2013-05-09 08:55:11', '2013-05-09 08:55:19'),
(44, 41, 32, 'tuteur', 'valide', '2013-05-09 08:55:14', '2013-05-09 08:55:21'),
(45, 39, 31, 'coequipier', 'valide', '2013-05-09 08:55:39', '2013-05-09 08:56:02'),
(46, 40, 34, 'coequipier', 'valide', '2013-05-09 08:55:51', '2013-05-09 08:56:04'),
(47, 41, 34, 'coequipier', 'valide', '2013-05-09 08:55:53', '2013-05-09 08:56:06'),
(48, 45, 32, 'tuteur', 'valide', '2013-05-09 08:56:59', '2013-05-09 08:57:02'),
(49, 45, 33, 'tuteur', 'valide', '2013-05-09 08:57:12', '2013-05-09 08:57:19'),
(50, 48, 34, 'coequipier', 'valide', '2013-05-09 08:45:42', '2013-05-09 08:47:13'),
(51, 47, 33, 'tuteur', 'valide', '2013-05-09 08:40:14', '2013-05-09 08:43:49'),
(52, 47, 31, 'coequipier', 'valide', '2013-05-09 08:48:51', '2013-05-09 08:50:37'),
(53, 49, 33, 'tuteur', 'valide', '2013-05-09 08:40:14', '2013-05-09 08:43:49'),
(54, 49, 34, 'coequipier', 'valide', '2013-05-09 08:45:34', '2013-05-09 08:46:47'),
(55, 49, 31, 'coequipier', 'valide', '2013-05-09 08:48:51', '2013-05-09 08:50:37'),
(56, 50, 32, 'tuteur', 'valide', '2013-05-09 08:46:12', '2013-05-09 08:46:33');

-- --------------------------------------------------------

--
-- Structure de la table `membres`
--

CREATE TABLE IF NOT EXISTS `membres` (
  `membreId` int(11) NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(50) CHARACTER SET utf8 COLLATE utf8_roman_ci NOT NULL,
  `mdpSalt` binary(16) NOT NULL,
  `mdpHash` binary(32) NOT NULL,
  `dateInscription` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `email` varchar(150) CHARACTER SET utf8 COLLATE utf8_roman_ci NOT NULL,
  `nom` varchar(50) CHARACTER SET utf8 COLLATE utf8_roman_ci NOT NULL,
  `prenom` varchar(50) CHARACTER SET utf8 COLLATE utf8_roman_ci NOT NULL,
  `telephone` varchar(20) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `groupeId` int(11) DEFAULT NULL,
  PRIMARY KEY (`membreId`),
  UNIQUE KEY `login` (`pseudo`),
  KEY `groupeId` (`groupeId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=35 ;

--
-- Contenu de la table `membres`
--

INSERT INTO `membres` (`membreId`, `pseudo`, `mdpSalt`, `mdpHash`, `dateInscription`, `email`, `nom`, `prenom`, `telephone`, `groupeId`) VALUES
(27, 'admin', '7…÷à}9œúCV¹\n', 'K–/†}ÓD®õ°b¹wL03zÊ^·İ5)³', '2013-05-09 08:23:23', 'admin@noHost.org', 'NomAdmin', 'PrenomAdmin', '0000000000', 6),
(28, 'coequipier', '[N5ã%ò#u&TìÈÄ÷', 'V\n¿şM+tÁ„‘Š"îç–kùtÔ,~®>ÒéoÀ™*', '2013-05-09 08:26:40', 'Prenom1.Nom1@nohost.fr', 'Nom', 'PrÃƒÂ©nom', '', 3),
(29, 'tuteur', 'H—D<êŠä ÕÎ/Ì', 'Ï8»#ÊÛÍ%&Ãg*ì}ÿ±Î¹ú™µï¿/Ô|\n®(', '2013-05-09 08:31:07', 'NomTuteur@nohost.org', 'NomTuteur', 'PrÃƒÂ©nomTuteur', '', 4),
(30, 'validateur', 'hıƒcÜ/àºˆx¡Å`Ir"', '9¾#8j„¦SÕêíû—_O.6vú/(³±Wˆt', '2013-05-09 08:31:41', 'validateur@nohost.org', 'NomValidateur', 'PrenomValidateur', '', 5),
(31, 'Jean', 'Æwdhø“àİMôœ×o', 'RãBA†}Ïª™étèa8ù[T	g„á»»×	i', '2013-05-09 08:32:44', 'Jean.Hubert@nohost.org', 'Hubert', 'Jean', '', 2),
(32, 'julie', 'ë`ÊãÂ cÂ‰ÇJî×W¡,', 'p\ZVÿş2¨V6sZ_Mƒ–MÃü.fTçŒqi)›KDT', '2013-05-09 08:34:21', 'julie.durand@nohost.org', 'Durand', 'Julie', '', 5),
(33, 'Paul', '“ùëA\râ~k\Zqì6÷"{', '!İÔxæ]¹4ìq’ßìLLô¶&~OíÌãx8', '2013-05-09 08:34:59', 'paul.thomas@nohost.org', 'Thomas', 'Paul', '', 4),
(34, 'Claire', 'Mûœn…üìM8+@bçy', 'èÉ`®œPµ5.(wF½è!èÔz©Ãä¿]¬$', '2013-05-09 08:35:48', 'claire.leroy@nohost.org', 'Leroy', 'Claire', '', 3);

--
-- Contraintes pour les tables exportÃ©es
--

--
-- Contraintes pour la table `maraudes_membres`
--
ALTER TABLE `maraudes_membres`
  ADD CONSTRAINT `maraudes_membres_ibfk_1` FOREIGN KEY (`maraudeId`) REFERENCES `maraudes` (`maraudeId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `maraudes_membres_ibfk_2` FOREIGN KEY (`membreId`) REFERENCES `membres` (`membreId`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `membres`
--
ALTER TABLE `membres`
  ADD CONSTRAINT `membres_ibfk_1` FOREIGN KEY (`groupeId`) REFERENCES `groupes` (`groupeId`) ON DELETE SET NULL ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
