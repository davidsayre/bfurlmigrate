/* bfurlmigrate */
CREATE TABLE `bfurlmigrate` (
  `bfurl_id` int(11) NOT NULL AUTO_INCREMENT,
  `remote_id` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,  
  PRIMARY KEY (`bfurl_id`),
  UNIQUE KEY `uq_url` (`url`),
  UNIQUE KEY `uq_remote_id` (`remote_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
	