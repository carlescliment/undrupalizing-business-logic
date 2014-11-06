DROP TABLE IF EXISTS `better_points`;
DROP TABLE IF EXISTS `bets`;
DROP TABLE IF EXISTS `matches`;
DROP TABLE IF EXISTS `betting_slips`;

CREATE TABLE `better_points` (
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `points` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  KEY `user_points_idx` (`points`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `betting_slips` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` datetime DEFAULT NULL,
  `closed` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `date_idx` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `matches` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `slip_id` int(10) unsigned DEFAULT NULL,
  `name` varchar(7) DEFAULT NULL,
  `result` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `slip_id` (`slip_id`),
  CONSTRAINT `matches_ibfk_1` FOREIGN KEY (`slip_id`) REFERENCES `betting_slips` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `bets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `match_id` int(10) unsigned DEFAULT NULL,
  `prediction` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_uid_mid` (`user_id`,`match_id`),
  KEY `user_match_idx` (`user_id`,`match_id`),
  KEY `match_id` (`match_id`),
  CONSTRAINT `bets_ibfk_1` FOREIGN KEY (`match_id`) REFERENCES `matches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

