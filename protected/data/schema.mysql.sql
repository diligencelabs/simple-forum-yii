-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 04, 2012 at 07:02 PM
-- Server version: 5.5.24-log
-- PHP Version: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `simpleforum`
--

-- --------------------------------------------------------

--
-- Table structure for table `sforums`
--

CREATE TABLE IF NOT EXISTS `sforums` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `image` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `of_posts` mediumint(8) unsigned NOT NULL,
  `of_topics` mediumint(8) unsigned NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_by_name` varchar(100) NOT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_by_name` varchar(100) NOT NULL,
  `created_on` int(11) NOT NULL,
  `modified_on` int(11) NOT NULL,
  `last_post_id` int(11) NOT NULL,
  `last_topic_id` int(11) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `parent_id` int(11) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `ordering` (`ordering`),
  KEY `parent_id` (`parent_id`),
  KEY `type` (`type`),
  KEY `last_post_id` (`last_post_id`),
  KEY `last_topic_id` (`last_topic_id`),
  KEY `status` (`status`),
  KEY `name_2` (`name`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sposts`
--

CREATE TABLE IF NOT EXISTS `sposts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_by` int(11) NOT NULL,
  `created_by_name` varchar(100) NOT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_by_name` varchar(100) NOT NULL,
  `created_on` int(11) NOT NULL,
  `modified_on` int(11) NOT NULL,
  `sforum_id` int(11) NOT NULL,
  `stopic_id` int(11) NOT NULL,
  `body` text,
  `ip` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `has_attachment` tinyint(1) NOT NULL DEFAULT '0',
  `email` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `email` (`email`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  KEY `created_on` (`created_on`),
  KEY `modified_on` (`modified_on`),
  KEY `stopic_id` (`stopic_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stopics`
--

CREATE TABLE IF NOT EXISTS `stopics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_by` int(11) NOT NULL,
  `created_by_name` varchar(100) NOT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_by_name` varchar(100) NOT NULL,
  `created_on` int(11) NOT NULL,
  `modified_on` int(11) NOT NULL,
  `sforum_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `image` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `of_posts` mediumint(8) unsigned NOT NULL,
  `last_post_id` int(11) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `of_views` int(11) NOT NULL,
  `of_replies` int(11) NOT NULL,
  `has_attachment` tinyint(1) NOT NULL DEFAULT '0',
  `first_post_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  KEY `created_on` (`created_on`),
  KEY `modified_on` (`modified_on`),
  KEY `sforum_id` (`sforum_id`),
  KEY `status` (`status`),
  KEY `last_post_id` (`last_post_id`),
  KEY `type` (`type`),
  KEY `first_post_id` (`first_post_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
