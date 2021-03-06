-- phpMyAdmin SQL Dump
-- version 3.5.7
-- http://www.phpmyadmin.net
--

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
/*SET time_zone = "+02:00";*/


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
--
-- --------------------------------------------------------

--
-- Structure de la table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `groupId` int(11) NOT NULL AUTO_INCREMENT,
  `groupName` varchar(50) CHARACTER SET utf8 NOT NULL,
  `rights` int(11) NOT NULL,
  PRIMARY KEY (`groupId`),
  UNIQUE KEY `name` (`groupName`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Contenu de la table `groups`
--

INSERT INTO `groups` (`groupId`, `groupName`, `rights`) VALUES
(1, 'inactif', 0),
(2, 'nouveauCoequipier', 3),
(3, 'coequipierRegulier', 4),
(4, 'tuteur', 5),
(5, 'validateur', 6),
(6, 'admin', 7);

-- --------------------------------------------------------

--
-- Structure de la table `roamings`
--

CREATE TABLE IF NOT EXISTS `roamings` (
  `roamingId` int(11) NOT NULL AUTO_INCREMENT,
  `roamingDate` date NOT NULL,
  `report` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`roamingId`),
  UNIQUE KEY `date` (`roamingDate`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=51 ;

--
-- Contenu de la table `roamings`
--

INSERT INTO `roamings` (`roamingId`, `roamingDate`, `report`) VALUES
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
-- Structure de la table `applications`
--

CREATE TABLE IF NOT EXISTS `applications` (
  `participationId` int(11) NOT NULL AUTO_INCREMENT,
  `roamingId` int(11) NOT NULL,
  `memberId` int(11) NOT NULL,
  `participationType` enum('tutor','teamMate') NOT NULL,
  `applicationStatus` enum('notProcessed','validated','refused','cancelled') NOT NULL DEFAULT 'notProcessed',
  `applicationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `applicationStatusModificationDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`participationId`),
  UNIQUE KEY `memberId` (`memberId`,`roamingId`),
  UNIQUE KEY `roamingId` (`roamingId`,`memberId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=57 ;

--
-- Contenu de la table `applications`
--

INSERT INTO `applications` (`participationId`, `roamingId`, `memberId`, `participationType`, `applicationStatus`, `applicationDate`, `applicationStatusModificationDate`) VALUES
(12, 9, 33, 'tutor', 'validated', '2013-05-09 08:37:27', '2013-05-09 09:57:34'),
(13, 10, 33, 'tutor', 'refused', '2013-05-09 08:39:57', '2013-05-09 08:44:20'),
(14, 11, 33, 'tutor', 'validated', '2013-05-09 08:40:03', '2013-05-09 08:43:52'),
(15, 12, 33, 'tutor', 'notProcessed', '2013-05-09 08:40:06', '0000-00-00 00:00:00'),
(16, 13, 33, 'tutor', 'validated', '2013-05-09 08:40:10', '2013-05-09 08:44:18'),
(17, 14, 33, 'tutor', 'validated', '2013-05-09 08:40:14', '2013-05-09 08:43:49'),
(18, 15, 33, 'tutor', 'validated', '2013-05-09 08:40:18', '2013-05-09 08:43:45'),
(20, 15, 34, 'teamMate', 'validated', '2013-05-09 08:45:30', '2013-05-09 08:46:38'),
(21, 14, 34, 'teamMate', 'validated', '2013-05-09 08:45:34', '2013-05-09 08:46:47'),
(22, 19, 34, 'teamMate', 'validated', '2013-05-09 08:45:37', '2013-05-09 08:46:23'),
(23, 12, 34, 'teamMate', 'validated', '2013-05-09 08:45:40', '2013-05-09 08:50:56'),
(24, 21, 34, 'teamMate', 'validated', '2013-05-09 08:45:42', '2013-05-09 08:47:13'),
(25, 22, 34, 'teamMate', 'notProcessed', '2013-05-09 08:45:48', '0000-00-00 00:00:00'),
(26, 23, 32, 'tutor', 'validated', '2013-05-09 08:46:12', '2013-05-09 08:46:33'),
(27, 19, 32, 'tutor', 'validated', '2013-05-09 08:46:15', '2013-05-09 08:46:25'),
(28, 10, 32, 'tutor', 'validated', '2013-05-09 08:47:16', '2013-05-09 08:51:17'),
(29, 9, 32, 'tutor', 'validated', '2013-05-09 08:48:08', '2013-05-09 09:57:32'),
(30, 19, 31, 'teamMate', 'validated', '2013-05-09 08:48:43', '2013-05-09 08:50:29'),
(31, 14, 31, 'teamMate', 'validated', '2013-05-09 08:48:51', '2013-05-09 08:50:37'),
(32, 11, 31, 'teamMate', 'validated', '2013-05-09 08:48:53', '2013-05-09 08:54:38'),
(33, 30, 31, 'teamMate', 'cancelled', '2013-05-09 08:49:00', '2013-05-09 08:53:59'),
(34, 21, 31, 'teamMate', 'validated', '2013-05-09 08:49:14', '2013-05-09 08:50:16'),
(35, 32, 31, 'teamMate', 'validated', '2013-05-09 08:49:23', '2013-05-09 08:50:11'),
(36, 32, 34, 'teamMate', 'validated', '2013-05-09 08:49:38', '2013-05-09 08:50:13'),
(37, 30, 34, 'teamMate', 'validated', '2013-05-09 08:49:42', '2013-05-09 08:50:51'),
(38, 10, 31, 'teamMate', 'validated', '2013-05-09 08:51:43', '2013-05-09 08:52:15'),
(39, 10, 34, 'teamMate', 'validated', '2013-05-09 08:51:52', '2013-05-09 08:52:09'),
(40, 30, 28, 'teamMate', 'refused', '2013-05-09 08:53:19', '2013-05-09 08:56:39'),
(41, 30, 33, 'tutor', 'validated', '2013-05-09 08:53:51', '2013-05-09 08:54:15'),
(42, 39, 32, 'tutor', 'validated', '2013-05-09 08:55:09', '2013-05-09 08:55:18'),
(43, 40, 32, 'tutor', 'validated', '2013-05-09 08:55:11', '2013-05-09 08:55:19'),
(44, 41, 32, 'tutor', 'validated', '2013-05-09 08:55:14', '2013-05-09 08:55:21'),
(45, 39, 31, 'teamMate', 'validated', '2013-05-09 08:55:39', '2013-05-09 08:56:02'),
(46, 40, 34, 'teamMate', 'validated', '2013-05-09 08:55:51', '2013-05-09 08:56:04'),
(47, 41, 34, 'teamMate', 'validated', '2013-05-09 08:55:53', '2013-05-09 08:56:06'),
(48, 45, 32, 'tutor', 'validated', '2013-05-09 08:56:59', '2013-05-09 08:57:02'),
(49, 45, 33, 'tutor', 'validated', '2013-05-09 08:57:12', '2013-05-09 08:57:19'),
(50, 48, 34, 'teamMate', 'validated', '2013-05-09 08:45:42', '2013-05-09 08:47:13'),
(51, 47, 33, 'tutor', 'validated', '2013-05-09 08:40:14', '2013-05-09 08:43:49'),
(52, 47, 31, 'teamMate', 'validated', '2013-05-09 08:48:51', '2013-05-09 08:50:37'),
(53, 49, 33, 'tutor', 'validated', '2013-05-09 08:40:14', '2013-05-09 08:43:49'),
(54, 49, 34, 'teamMate', 'validated', '2013-05-09 08:45:34', '2013-05-09 08:46:47'),
(55, 49, 31, 'teamMate', 'validated', '2013-05-09 08:48:51', '2013-05-09 08:50:37'),
(56, 50, 32, 'tutor', 'validated', '2013-05-09 08:46:12', '2013-05-09 08:46:33');

-- --------------------------------------------------------

--
-- Structure de la table `members`
--

CREATE TABLE IF NOT EXISTS `members` (
  `memberId` int(11) NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(50) CHARACTER SET utf8 COLLATE utf8_roman_ci NOT NULL,
  `passwordSalt` binary(16) NOT NULL,
  `passwordHash` binary(32) NOT NULL,
  `registrationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `email` varchar(150) CHARACTER SET utf8 COLLATE utf8_roman_ci NOT NULL,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_roman_ci NOT NULL,
  `firstName` varchar(50) CHARACTER SET utf8 COLLATE utf8_roman_ci NOT NULL,
  `telephone` varchar(20) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `groupId` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`memberId`),
  UNIQUE KEY `login` (`pseudo`),
  KEY `groupId` (`groupId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=35 ;

--
-- Contenu de la table `members`
--

INSERT INTO `members` (`memberId`, `pseudo`, `passwordSalt`, `passwordHash`, `registrationDate`, `email`, `name`, `firstName`, `telephone`, `groupId`) VALUES
(27, 'admin', UNHEX('c4323154492dbdbe737c2adb624c430a'), UNHEX('30f2aa1e22cb700b7e0517e4f57c0e35d015def7166aa6a3da0650b387293636'), '2013-05-09 08:23:23', 'admin@noHost.org', 'NomAdmin', 'PrenomAdmin', '0000000000', 6),
(28, 'coequipier', UNHEX('c8508bfa80d97617dae2b233d3dc8cab'), UNHEX('a1a4f1d43ea37f7aec19e5a9dff1e0a0ab38ebd6a25f2679a0cb76f25e59f034'), '2013-05-09 08:26:40', 'Prenom1.Nom1@nohost.fr', 'Nom', 'Prénom', '', 3),
(29, 'tuteur',  UNHEX('b311053ff21b7cf6d5858d118610dbdc'), UNHEX('e345e4c0e2d2f6a3bea7d2cdee7d17a925bd73f5c75f8531ae81fdaef09f4426'), '2013-05-09 08:31:07', 'NomTuteur@nohost.org', 'NomTuteur', 'PrénomTuteur', '', 4),
(30, 'validateur', UNHEX('5bd482bccc21f788bcb3c2815c27ef83'), UNHEX('3ef8de31993138db6d479d95663faab35eda00b145e47654f7c9fd75b86a8575'), '2013-05-09 08:31:41', 'validateur@nohost.org', 'NomValidateur', 'PrenomValidateur', '', 5),
(31, 'Jean', UNHEX('5fca35fbb0241872f782004f26e0bd89'), UNHEX('0d4a0f8cdba4edfaadd1ca2e6b244c4181092455c00bcaa63ccc841816f67c89'), '2013-05-09 08:32:44', 'Jean.Hubert@nohost.org', 'Hubert', 'Jean', '', 2),
(32, 'julie', UNHEX('3c2f4cdeed0c2e49a3aad4a8fe20adad'), UNHEX('e2e1850d071cf8d1a5ac80491f208198d129a6aa9026d46523db2688866ff913'), '2013-05-09 08:34:21', 'julie.durand@nohost.org', 'Durand', 'Julie', '', 5),
(33, 'Paul', UNHEX('1f41c34676bba762c49c9ddb12a7ca86'), UNHEX('bb046c61c35d5a03390a8fbddec487aa374555e99b469a39239efa0a74c0dfb6'), '2013-05-09 08:34:59', 'paul.thomas@nohost.org', 'Thomas', 'Paul', '', 4),
(34, 'Claire', UNHEX('d3d7633ac08aecc242c850875af1b711'), UNHEX('40c98bebb105b98797303a95a322e6fdb3e9a567a8eb58107ab11a6975ff9552'), '2013-05-09 08:35:48', 'claire.leroy@nohost.org', 'Leroy', 'Claire', '', 3);

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`roamingId`) REFERENCES `roamings` (`roamingId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `applications_ibfk_2` FOREIGN KEY (`memberId`) REFERENCES `members` (`memberId`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `members`
--
ALTER TABLE `members`
  ADD CONSTRAINT `members_ibfk_1` FOREIGN KEY (`groupId`) REFERENCES `groups` (`groupId`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
