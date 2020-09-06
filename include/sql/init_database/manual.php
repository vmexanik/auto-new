<?
$aInitSql=array(
'CREATE TABLE IF NOT EXISTS `manual_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `_left` int(11) NOT NULL,
  `_right` int(11) NOT NULL,
  `_level` int(11) NOT NULL,
  `child_array` varchar(255) CHARACTER SET utf8 NOT NULL,
  `id_parent` int(11) NOT NULL DEFAULT \'0\',
  `name` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT \'\',
  `code` varchar(3) CHARACTER SET utf8 NOT NULL DEFAULT \'\',
  `level` int(11) NOT NULL DEFAULT \'1\',
  `visible` int(11) NOT NULL DEFAULT \'0\',
  `num` tinyint(4) NOT NULL DEFAULT \'0\',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;
',
'INSERT INTO `manual_category` (`id`, `_left`, `_right`, `_level`, `child_array`, `id_parent`, `name`, `code`, `level`, `visible`, `num`) VALUES
(1, 1, 8, 0, \'\', 0, \'\', \'\', 1, 0, 0),
(2, 4, 5, 1, \'\', 0, \'Пользовательский\', \'CUS\', 1, 1, 0),
(3, 2, 3, 1, \'\', 0, \'Менеджерский\', \'MAN\', 1, 1, 0),
(4, 6, 7, 1, \'\', 0, \'Гостевой\', \'GST\', 1, 1, 0);
',
'CREATE TABLE IF NOT EXISTS `manual` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code_manual_category` varchar(10) NOT NULL,
  `code` varchar(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `short_content` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `visible` int(11) NOT NULL,
  `num` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
'
);
?>