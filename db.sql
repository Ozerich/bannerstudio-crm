-- phpMyAdmin SQL Dump
-- version 3.5.8
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Июл 08 2013 г., 20:04
-- Версия сервера: 5.1.68-cll-lve
-- Версия PHP: 5.3.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `ozisby_banners`
--

-- --------------------------------------------------------

--
-- Структура таблицы `projects`
--

CREATE TABLE IF NOT EXISTS `projects` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(1000) NOT NULL,
  `status` varchar(255) NOT NULL,
  `closed` tinyint(1) NOT NULL DEFAULT '0',
  `worker_price` varchar(255) NOT NULL,
  `customer_price` varchar(255) NOT NULL,
  `worker_text` text NOT NULL,
  `customer_text` text NOT NULL,
  `created_time` datetime NOT NULL,
  `out_hash` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `project_comments`
--

CREATE TABLE IF NOT EXISTS `project_comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `text` text NOT NULL,
  `datetime` datetime NOT NULL,
  `mode` enum('worker','customer') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `project_comment_files`
--

CREATE TABLE IF NOT EXISTS `project_comment_files` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `comment_id` int(11) NOT NULL,
  `file` varchar(255) NOT NULL,
  `real_filename` varchar(255) NOT NULL,
  `file_size` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `project_comment_reads`
--

CREATE TABLE IF NOT EXISTS `project_comment_reads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `project_users`
--

CREATE TABLE IF NOT EXISTS `project_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `rights_authassignment`
--

CREATE TABLE IF NOT EXISTS `rights_authassignment` (
  `itemname` varchar(64) NOT NULL,
  `userid` varchar(64) NOT NULL,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`itemname`,`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `rights_authassignment`
--

INSERT INTO `rights_authassignment` (`itemname`, `userid`, `bizrule`, `data`) VALUES
('admin', '1', NULL, 'N;'),
('admin', 'admin', NULL, 'N;'),
('customer', 'customer', NULL, 'N;'),
('worker', 'worker', NULL, 'N;');

-- --------------------------------------------------------

--
-- Структура таблицы `rights_authitem`
--

CREATE TABLE IF NOT EXISTS `rights_authitem` (
  `name` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `rights_authitem`
--

INSERT INTO `rights_authitem` (`name`, `type`, `description`, `bizrule`, `data`) VALUES
('admin', 2, 'Администратор', NULL, 'N;'),
('customer', 2, 'Клиент', NULL, 'N;'),
('Projects.Add_Comment', 0, 'Добавление комментариев', NULL, 'N;'),
('Projects.Admin_Comment', 0, 'Комментарий от админа("Заказчику", "Сотруднику")', NULL, 'N;'),
('Projects.Comments', 0, 'Просмотр комментариев', NULL, 'N;'),
('Projects.Create', 0, 'Создание проектов', NULL, 'N;'),
('Projects.Delete', 0, 'Удаление проектов', NULL, 'N;'),
('Projects.Delete_Comment', 0, 'Удаление комментариев', NULL, 'N;'),
('Projects.Delete_Comment_File', 0, 'Удаление файлов в комментариях', NULL, 'N;'),
('Projects.Delete_Files', 0, 'Множественное удаление файлов', NULL, 'N;'),
('Projects.Download', 0, 'Скачивание файлов в комментариях', NULL, 'N;'),
('Projects.Edit', 0, 'Редактирование проекта', NULL, 'N;'),
('Projects.Edit_Comment', 0, 'Редактирование комментария', NULL, 'N;'),
('Projects.Index', 0, 'Просмотр списка проектов', NULL, 'N;'),
('Projects.Upload_Comment_File', 0, 'Добавление файлов к коментариям', NULL, 'N;'),
('Projects.View', 0, 'Просмотр проекта', NULL, 'N;'),
('Users.*', 1, 'Управление пользователями', NULL, 'N;'),
('Users.Create', 0, 'Создание нового пользователя', NULL, 'N;'),
('Users.Delete', 0, 'Удаление пользователей', NULL, 'N;'),
('Users.Edit', 0, 'Редактирование пользователей', NULL, 'N;'),
('Users.Index', 0, 'Просмотр списка пользователей', NULL, 'N;'),
('worker', 2, 'Сотрудник', NULL, 'N;');

-- --------------------------------------------------------

--
-- Структура таблицы `rights_authitemchild`
--

CREATE TABLE IF NOT EXISTS `rights_authitemchild` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `rights_authitemchild`
--

INSERT INTO `rights_authitemchild` (`parent`, `child`) VALUES
('admin', 'Projects.Add_Comment'),
('customer', 'Projects.Add_Comment'),
('worker', 'Projects.Add_Comment'),
('admin', 'Projects.Admin_Comment'),
('admin', 'Projects.Comments'),
('customer', 'Projects.Comments'),
('worker', 'Projects.Comments'),
('admin', 'Projects.Create'),
('customer', 'Projects.Create'),
('admin', 'Projects.Delete'),
('admin', 'Projects.Delete_Comment'),
('admin', 'Projects.Delete_Comment_File'),
('customer', 'Projects.Delete_Comment_File'),
('worker', 'Projects.Delete_Comment_File'),
('admin', 'Projects.Delete_Files'),
('admin', 'Projects.Download'),
('customer', 'Projects.Download'),
('worker', 'Projects.Download'),
('admin', 'Projects.Edit'),
('admin', 'Projects.Edit_Comment'),
('admin', 'Projects.Index'),
('admin', 'Projects.Upload_Comment_File'),
('customer', 'Projects.Upload_Comment_File'),
('worker', 'Projects.Upload_Comment_File'),
('admin', 'Projects.View'),
('customer', 'Projects.View'),
('worker', 'Projects.View'),
('admin', 'Users.*'),
('Users.*', 'Users.Create'),
('Users.*', 'Users.Delete'),
('Users.*', 'Users.Edit'),
('Users.*', 'Users.Index');

-- --------------------------------------------------------

--
-- Структура таблицы `rights_rights`
--

CREATE TABLE IF NOT EXISTS `rights_rights` (
  `itemname` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `weight` int(11) NOT NULL,
  PRIMARY KEY (`itemname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `slider_items`
--

CREATE TABLE IF NOT EXISTS `slider_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `comment_file_id` int(11) DEFAULT NULL,
  `page_id` int(11) NOT NULL,
  `file` varchar(255) DEFAULT NULL,
  `real_filename` varchar(255) DEFAULT NULL,
  `html` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `slider_pages`
--

CREATE TABLE IF NOT EXISTS `slider_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role` enum('admin','customer','worker') NOT NULL,
  `email` varchar(255) NOT NULL,
  `login` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `salt` varchar(255) NOT NULL,
  `contact` text,
  `time_created` datetime NOT NULL,
  `last_visit` datetime NOT NULL,
  `hide_information` text,
  `avatar` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `role`, `email`, `login`, `password`, `salt`, `contact`, `time_created`, `last_visit`, `hide_information`, `avatar`) VALUES
(1, 'admin', 'ozicoder@gmail.com', 'Администратор', '$2gCXDSrN2DHo', '$2a$10$0JTq3q9Gr7r99gyU4eQ/QK', '1231233333', '2013-03-11 00:53:03', '2013-07-08 19:03:53', '123321', '513d144460c76.jpg');

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `rights_authassignment`
--
ALTER TABLE `rights_authassignment`
  ADD CONSTRAINT `rights_authassignment_ibfk_1` FOREIGN KEY (`itemname`) REFERENCES `rights_authitem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `rights_authitemchild`
--
ALTER TABLE `rights_authitemchild`
  ADD CONSTRAINT `rights_authitemchild_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `rights_authitem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rights_authitemchild_ibfk_2` FOREIGN KEY (`child`) REFERENCES `rights_authitem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `rights_rights`
--
ALTER TABLE `rights_rights`
  ADD CONSTRAINT `rights_rights_ibfk_1` FOREIGN KEY (`itemname`) REFERENCES `rights_authitem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
