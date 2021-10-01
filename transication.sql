CREATE TABLE IF NOT EXISTS `transication` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `gid` int(11) NOT NULL DEFAULT '0',
  `status` enum('0','1') NOT NULL,
  `time` int(11) NOT NULL,
  `server` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;