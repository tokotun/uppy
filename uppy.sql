--
-- База данных: `uppy`
--

-- --------------------------------------------------------
--
-- Структура таблицы `files`
--

CREATE TABLE `files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `file_key` varchar(10) NOT NULL,
  `dateLoad` datetime NOT NULL,
  `size` int(8) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY (`file_key`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_id` int(11) NOT NULL,
  `comment_path` varchar(40) NOT NULL,
  `message` text NOT NULL,
  `date_comment` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY (`comment_path`),
  FOREIGN KEY (`file_id`) REFERENCES `files` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------


--
-- Структура таблицы `file_info`
--

CREATE TABLE `file_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_id` int(11) NOT NULL,
  `ID3` text,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`file_id`) REFERENCES `files` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
