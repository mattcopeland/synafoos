--
-- Table structure for table `match_log`
--

CREATE TABLE IF NOT EXISTS `match_log` (
  `match_id` int(11) NOT NULL AUTO_INCREMENT,
  `team_1_player_1` tinyint(4) NOT NULL,
  `team_1_player_2` tinyint(4) DEFAULT NULL,
  `team_2_player_1` tinyint(4) NOT NULL,
  `team_2_player_2` tinyint(4) DEFAULT NULL,
  `date_time` int(10) NOT NULL,
  PRIMARY KEY (`match_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=130 ;

-- --------------------------------------------------------

--
-- Table structure for table `players`
--

CREATE TABLE IF NOT EXISTS `players` (
  `player_id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `password` varchar(32) NOT NULL DEFAULT 'not_set',
  `first_name` varchar(32) NOT NULL,
  `last_name` varchar(32) NOT NULL,
  `nickname` varchar(32) DEFAULT NULL,
  `image` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`player_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=27 ;

-- --------------------------------------------------------

--
-- Table structure for table `rankings`
--

CREATE TABLE IF NOT EXISTS `rankings` (
  `rank` tinyint(4) NOT NULL,
  `player_id` tinyint(4) NOT NULL,
  `points` float NOT NULL,
  PRIMARY KEY (`rank`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `series`
--

CREATE TABLE IF NOT EXISTS `series` (
  `series_id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `players` tinyint(1) NOT NULL DEFAULT '2',
  `team_1` tinyint(4) NOT NULL,
  `team_2` tinyint(4) NOT NULL,
  `wins_1` tinyint(4) NOT NULL DEFAULT '0',
  `wins_2` tinyint(4) NOT NULL DEFAULT '0',
  `wins_goal` tinyint(4) NOT NULL DEFAULT '50',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `last_updated` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`series_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=37 ;

-- --------------------------------------------------------

--
-- Table structure for table `series_log`
--

CREATE TABLE IF NOT EXISTS `series_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `series_id` tinyint(4) NOT NULL,
  `winner_id` tinyint(4) NOT NULL,
  `loser_id` tinyint(4) NOT NULL,
  `date_time` int(10) NOT NULL,
  `rescinded` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=785 ;

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE IF NOT EXISTS `teams` (
  `team_id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `nickname` varchar(64) NOT NULL DEFAULT 'Team Name',
  `player_1` tinyint(4) NOT NULL,
  `player_2` tinyint(4) NOT NULL,
  PRIMARY KEY (`team_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
