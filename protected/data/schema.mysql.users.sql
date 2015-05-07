CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `username` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  `salt` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `user_type` char(1) DEFAULT 'C' COMMENT 'A=Admin,C=Common User',
  `profile` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `name` (`name`),
  KEY `user_type` (`user_type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO users (name, username, salt, password, email, user_type) VALUES ('Admin', 'admin', 'a02949c881c1d6452a24db8c828b3f9b', '4d46f0e32ea263d7309d0c2bc7babcb5', 'rama@diligencelabs.com', 'A');
INSERT INTO users (name, username, salt, password, email, user_type) VALUES ('Rama', 'rama', 'a02949c881c1d6452a24db8c828b3f9b', '4d46f0e32ea263d7309d0c2bc7babcb5', 'rama@diligencelabs.com', 'C');
INSERT INTO users (name, username, salt, password, email, user_type) VALUES ('Test', 'test', 'a02949c881c1d6452a24db8c828b3f9b', '4d46f0e32ea263d7309d0c2bc7babcb5', 'rama@diligencelabs.com', 'C');


ALTER TABLE  `users` ADD  `external_id` VARCHAR( 128 ), ADD  `external_app` VARCHAR( 32 );

ALTER TABLE  `users` ADD INDEX (  `external_id` ,  `external_app` ) ;