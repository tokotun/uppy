CREATE TABLE `files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `file_key` varchar(10) NOT NULL,
  `dateLoad` datetime NOT NULL,
  `size` int(8) NOT NULL,
  `ID3` text COMMENT 'ID3 information in JSON format',
  PRIMARY KEY (`id`),
  UNIQUE KEY `file_key` (`file_key`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_id` int(11) NOT NULL,
  `comment_path` varchar(40) DEFAULT NULL COMMENT 'Description branches for comment. Number of comments and the number of his parents.',
  `message` text NOT NULL,
  `date_comment` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `file_comment` (`file_id`,`comment_path`),
  FOREIGN KEY (`file_id`) REFERENCES `files` (`id`) 
      ON DELETE CASCADE 
      ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;