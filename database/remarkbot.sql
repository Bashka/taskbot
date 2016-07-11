CREATE TABLE IF NOT EXISTS `mark` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(512) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`(255))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `user_mark` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user` int(10) unsigned NOT NULL,
  `mark` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user` (`user`),
  KEY `mark` (`mark`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;

ALTER TABLE `user_mark`
  ADD CONSTRAINT `user_mark_ibfk_1` FOREIGN KEY (`mark`) REFERENCES `mark` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

CREATE TABLE IF NOT EXISTS `task` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(1024) NOT NULL,
  `description` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `completed_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `task_mark` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `task` int(10) unsigned NOT NULL,
  `mark` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `task` (`task`),
  KEY `mark` (`mark`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;

ALTER TABLE `task_mark`
  ADD CONSTRAINT `task_mark_ibfk_2` FOREIGN KEY (`mark`) REFERENCES `mark` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `task_mark_ibfk_1` FOREIGN KEY (`task`) REFERENCES `task` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

CREATE TABLE IF NOT EXISTS `subscriber` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `task` int(10) unsigned NOT NULL,
  `user` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `task` (`task`),
  KEY `user` (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;

ALTER TABLE `subscriber`
  ADD CONSTRAINT `subscriber_ibfk_1` FOREIGN KEY (`task`) REFERENCES `task` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
