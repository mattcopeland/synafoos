-- phpMyAdmin SQL Dump
-- version 2.11.11.3
-- http://www.phpmyadmin.net
--
-- Host: 72.167.233.32
-- Generation Time: Oct 23, 2012 at 07:47 AM
-- Server version: 5.0.92
-- PHP Version: 5.1.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `themaestro`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(4) NOT NULL auto_increment,
  `player_id` int(4) NOT NULL,
  `password` varchar(16) NOT NULL,
  PRIMARY KEY  (`admin_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `challenges`
--

CREATE TABLE `challenges` (
  `challenge_id` int(6) NOT NULL auto_increment,
  `player_1` int(4) NOT NULL,
  `player_2` int(4) NOT NULL,
  PRIMARY KEY  (`challenge_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE `games` (
  `game_id` int(6) NOT NULL auto_increment,
  `player1_id` int(4) NOT NULL,
  `player2_id` int(4) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  PRIMARY KEY  (`game_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `players`
--

CREATE TABLE `players` (
  `player_id` tinyint(4) NOT NULL auto_increment,
  `email` varchar(100) NOT NULL,
  `password` varchar(32) NOT NULL default 'not_set',
  `first_name` varchar(32) NOT NULL,
  `last_name` varchar(32) NOT NULL,
  `nickname` varchar(32) default NULL,
  `image` varchar(32) default NULL,
  `auth_token` varchar(32) default NULL,
  `valid` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`player_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Table structure for table `series`
--

CREATE TABLE `series` (
  `series_id` tinyint(4) NOT NULL auto_increment,
  `players` tinyint(1) NOT NULL default '2',
  `team_1` tinyint(4) NOT NULL,
  `team_2` tinyint(4) NOT NULL,
  `wins_1` tinyint(4) NOT NULL default '0',
  `wins_2` tinyint(4) NOT NULL default '0',
  `wins_goal` tinyint(4) NOT NULL default '50',
  `active` tinyint(1) NOT NULL default '1',
  `password` varchar(32) NOT NULL default 'syn@f00s',
  `last_updated` int(10) NOT NULL default '0',
  PRIMARY KEY  (`series_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Table structure for table `series_log`
--

CREATE TABLE `series_log` (
  `log_id` int(11) NOT NULL auto_increment,
  `series_id` tinyint(4) NOT NULL,
  `winner_id` tinyint(4) NOT NULL,
  `loser_id` tinyint(4) NOT NULL,
  `date_time` int(10) NOT NULL,
  `rescinded` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`log_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=67 ;

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE `teams` (
  `team_id` tinyint(4) NOT NULL auto_increment,
  `nickname` varchar(64) NOT NULL default 'Team Name',
  `player_1` tinyint(4) NOT NULL,
  `player_2` tinyint(4) NOT NULL,
  PRIMARY KEY  (`team_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;
