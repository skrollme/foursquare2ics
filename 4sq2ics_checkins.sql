CREATE TABLE `4sq2ics_checkins` (
  `id` varchar(32) NOT NULL DEFAULT '',
  `timestamp` int(11) DEFAULT NULL,
  `user` int(11) DEFAULT NULL,
  `venue` varchar(255) DEFAULT NULL,
  `lat` varchar(255) DEFAULT NULL,
  `long` varchar(255) DEFAULT NULL,
  `country` varchar(2) DEFAULT NULL,
  `zip` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;