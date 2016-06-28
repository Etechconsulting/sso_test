-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Mar 28 Juin 2016 à 09:49
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `bd_symfony_ssotest`
--
CREATE DATABASE IF NOT EXISTS `bd_symfony_ssotest` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `bd_symfony_ssotest`;

-- --------------------------------------------------------

--
-- Structure de la table `sso_user`
--

DROP TABLE IF EXISTS `sso_user`;
CREATE TABLE IF NOT EXISTS `sso_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Contenu de la table `sso_user`
--

INSERT INTO `sso_user` (`id`, `login`, `password`) VALUES
(1, 'santevet', 'santevet');

-- --------------------------------------------------------

--
-- Structure de la table `token`
--

DROP TABLE IF EXISTS `token`;
CREATE TABLE IF NOT EXISTS `token` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sso_user_id` int(11) NOT NULL,
  `token_session` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `validity` datetime NOT NULL,
  `ssid_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_5F37A13B743C03ED` (`ssid_id`),
  KEY `IDX_5F37A13B24E71871` (`sso_user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=49 ;

--
-- Contenu de la table `token`
--

INSERT INTO `token` (`id`, `sso_user_id`, `token_session`, `validity`, `ssid_id`) VALUES
(1, 1, '8b0d9c93a3545d0d99f6486208dd3659ec802ee1', '2016-12-25 00:00:00', NULL);

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `token`
--
ALTER TABLE `token`
  ADD CONSTRAINT `FK_5F37A13B743C03ED` FOREIGN KEY (`ssid_id`) REFERENCES `token` (`id`),
  ADD CONSTRAINT `FK_5F37A13B24E71871` FOREIGN KEY (`sso_user_id`) REFERENCES `sso_user` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
