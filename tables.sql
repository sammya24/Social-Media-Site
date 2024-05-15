MariaDB [news_site]> SHOW CREATE TABLE comments;
+----------+------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Table    | Create Table                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   |
+----------+------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| comments | CREATE TABLE `comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content` text DEFAULT NULL,
  `user` varchar(50) NOT NULL,
  `story_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user` (`user`),
  KEY `story_id` (`story_id`),
  CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user`) REFERENCES `users` (`username`),
  CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`story_id`) REFERENCES `stories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1 |
+----------+------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
1 row in set (0.00 sec)

MariaDB [news_site]> SHOW CREATE TABLE dislikes;
+----------+-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Table    | Create Table                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    |
+----------+-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| dislikes | CREATE TABLE `dislikes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `unsupportive_friend_username` varchar(50) NOT NULL,
  `story_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `story_id` (`story_id`),
  KEY `unsupportive_friend_username` (`unsupportive_friend_username`),
  CONSTRAINT `dislikes_ibfk_1` FOREIGN KEY (`story_id`) REFERENCES `stories` (`id`),
  CONSTRAINT `dislikes_ibfk_2` FOREIGN KEY (`unsupportive_friend_username`) REFERENCES `users` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1 |
+----------+-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+

MariaDB [news_site]> SHOW CREATE TABLE likes;
+-------+------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Table | Create Table                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   |
+-------+------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| likes | CREATE TABLE `likes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `supportive_friend_username` varchar(50) NOT NULL,
  `story_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `story_id` (`story_id`),
  KEY `supportive_friend_username` (`supportive_friend_username`),
  CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`story_id`) REFERENCES `stories` (`id`),
  CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`supportive_friend_username`) REFERENCES `users` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1 |
+-------+------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
1 row in set (0.00 sec)

MariaDB [news_site]> SHOW CREATE TABLE stories;
+---------+------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Table   | Create Table                                                                                                                                                                                                                                                                                                                                                                                   |
+---------+------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| stories | CREATE TABLE `stories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(300) DEFAULT NULL,
  `body` text DEFAULT NULL,
  `link` text DEFAULT NULL,
  `user` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user` (`user`),
  CONSTRAINT `stories_ibfk_1` FOREIGN KEY (`user`) REFERENCES `users` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1 |
+---------+------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
1 row in set (0.00 sec)

MariaDB [news_site]> SHOW CREATE TABLE users;
+-------+---------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Table | Create Table                                                                                                                                                  |
+-------+---------------------------------------------------------------------------------------------------------------------------------------------------------------+
| users | CREATE TABLE `users` (
  `username` varchar(50) NOT NULL,
  `pass_h` varchar(100) NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 |
+-------+---------------------------------------------------------------------------------------------------------------------------------------------------------------+
1 row in set (0.00 sec)