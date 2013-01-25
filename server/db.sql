-- phpMyAdmin SQL Dump
-- version 2.11.9.2
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Dim 27 Mars 2011 à 19:24
-- Version du serveur: 5.0.67
-- Version de PHP: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de données: `gifts`
--

-- --------------------------------------------------------

--
-- Structure de la table `gifts`
--

CREATE TABLE IF NOT EXISTS `gifts` (
  `id` int(11) NOT NULL auto_increment,
  `list_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `link` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `list_id` (`list_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Structure de la table `gifts_lists`
--

CREATE TABLE IF NOT EXISTS `gifts_lists` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `event_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL auto_increment,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `name` varchar(50) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `role` enum('user','admin') NOT NULL default 'user',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Structure de la table `users_gifts`
--

CREATE TABLE IF NOT EXISTS `users_gifts` (
  `user_id` int(11) NOT NULL,
  `gift_id` int(11) NOT NULL,
  `state` enum('bought','intend') NOT NULL,
  PRIMARY KEY  (`user_id`,`gift_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `users_lists`
--

CREATE TABLE IF NOT EXISTS `users_lists` (
  `user_id` int(11) NOT NULL,
  `list_id` int(11) NOT NULL,
  PRIMARY KEY  (`user_id`,`list_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
