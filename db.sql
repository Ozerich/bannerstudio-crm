/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50525
Source Host           : localhost:3306
Source Database       : banners

Target Server Type    : MYSQL
Target Server Version : 50525
File Encoding         : 65001

Date: 2013-04-16 17:40:52
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `project_comment_files`
-- ----------------------------
DROP TABLE IF EXISTS `project_comment_files`;
CREATE TABLE `project_comment_files` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `comment_id` int(11) NOT NULL,
  `file` varchar(255) NOT NULL,
  `real_filename` varchar(255) NOT NULL,
  `file_size` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of project_comment_files
-- ----------------------------
INSERT INTO `project_comment_files` VALUES ('1', '4', '516c97a4405ae.png', 'bg.png', '9139');
INSERT INTO `project_comment_files` VALUES ('2', '6', '516c998b174f2.png', 'bg.png', '9139');

-- ----------------------------
-- Table structure for `project_comments`
-- ----------------------------
DROP TABLE IF EXISTS `project_comments`;
CREATE TABLE `project_comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `text` text NOT NULL,
  `datetime` datetime NOT NULL,
  `mode` enum('worker','customer') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of project_comments
-- ----------------------------
INSERT INTO `project_comments` VALUES ('1', '6', '1', 'пилим!', '2013-04-16 03:07:19', 'worker');
INSERT INTO `project_comments` VALUES ('2', '1', '1', 'клиент, мы начинаем', '2013-04-16 03:07:40', 'customer');
INSERT INTO `project_comments` VALUES ('3', '1', '1', 'отписал клиенту что начинаем', '2013-04-16 03:07:49', 'worker');
INSERT INTO `project_comments` VALUES ('4', '6', '1', 'ты крот, боссяра', '2013-04-16 03:13:23', 'worker');
INSERT INTO `project_comments` VALUES ('5', '1', '1', '213123 123', '2013-04-16 03:20:17', 'worker');
INSERT INTO `project_comments` VALUES ('6', '1', '1', 'клиент, не лошара ты?', '2013-04-16 03:21:29', 'customer');

-- ----------------------------
-- Table structure for `project_users`
-- ----------------------------
DROP TABLE IF EXISTS `project_users`;
CREATE TABLE `project_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of project_users
-- ----------------------------
INSERT INTO `project_users` VALUES ('27', '6', '1');
INSERT INTO `project_users` VALUES ('28', '5', '1');

-- ----------------------------
-- Table structure for `projects`
-- ----------------------------
DROP TABLE IF EXISTS `projects`;
CREATE TABLE `projects` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(1000) NOT NULL,
  `status` varchar(255) NOT NULL,
  `closed` tinyint(1) NOT NULL DEFAULT '0',
  `worker_price` int(11) NOT NULL,
  `customer_price` int(11) NOT NULL,
  `worker_text` text NOT NULL,
  `customer_text` text NOT NULL,
  `created_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of projects
-- ----------------------------
INSERT INTO `projects` VALUES ('1', 'Проект 111', 'status_2', '0', '2', '1', '2222', '1111', '2013-03-13 02:53:01');
INSERT INTO `projects` VALUES ('2', 'Popandos', 'status_1', '0', '0', '0', '', '', '2013-03-14 00:23:10');
INSERT INTO `projects` VALUES ('3', 'Popandos', 'status_1', '0', '0', '0', '', '', '2013-03-14 00:23:37');
INSERT INTO `projects` VALUES ('4', 'Popandos', 'status_1', '0', '0', '0', '', '', '2013-03-14 00:25:52');
INSERT INTO `projects` VALUES ('5', '12321', 'status_1', '0', '0', '0', '', '', '2013-03-14 00:26:24');
INSERT INTO `projects` VALUES ('6', '12321', 'status_1', '0', '0', '0', '', '', '2013-03-14 00:26:32');
INSERT INTO `projects` VALUES ('7', '12321', 'status_1', '0', '0', '0', '', '', '2013-03-14 00:26:37');
INSERT INTO `projects` VALUES ('8', '12321', 'status_1', '0', '0', '0', '', '', '2013-03-14 00:26:50');
INSERT INTO `projects` VALUES ('9', '12321', 'status_1', '0', '0', '0', '', '', '2013-03-14 00:27:02');
INSERT INTO `projects` VALUES ('10', 'Проект на второй странице', 'status_1', '0', '50000', '10000', '123312 ', '312132213', '2013-03-14 03:12:50');
INSERT INTO `projects` VALUES ('11', 'Проект на второй странице 2', 'status_1', '0', '22', '22', '', '', '2013-03-14 03:13:38');

-- ----------------------------
-- Table structure for `rights_authassignment`
-- ----------------------------
DROP TABLE IF EXISTS `rights_authassignment`;
CREATE TABLE `rights_authassignment` (
  `itemname` varchar(64) NOT NULL,
  `userid` varchar(64) NOT NULL,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`itemname`,`userid`),
  CONSTRAINT `rights_authassignment_ibfk_1` FOREIGN KEY (`itemname`) REFERENCES `rights_authitem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of rights_authassignment
-- ----------------------------
INSERT INTO `rights_authassignment` VALUES ('admin', '1', null, 'N;');
INSERT INTO `rights_authassignment` VALUES ('admin', 'admin', null, 'N;');
INSERT INTO `rights_authassignment` VALUES ('customer', '2', null, 'N;');
INSERT INTO `rights_authassignment` VALUES ('customer', '5', null, 'N;');
INSERT INTO `rights_authassignment` VALUES ('customer', 'customer', null, 'N;');
INSERT INTO `rights_authassignment` VALUES ('worker', '6', null, 'N;');
INSERT INTO `rights_authassignment` VALUES ('worker', 'worker', null, 'N;');

-- ----------------------------
-- Table structure for `rights_authitem`
-- ----------------------------
DROP TABLE IF EXISTS `rights_authitem`;
CREATE TABLE `rights_authitem` (
  `name` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of rights_authitem
-- ----------------------------
INSERT INTO `rights_authitem` VALUES ('admin', '2', 'Администратор', null, 'N;');
INSERT INTO `rights_authitem` VALUES ('customer', '2', 'Клиент', null, 'N;');
INSERT INTO `rights_authitem` VALUES ('Projects.Add_Comment', '0', 'Добавление комментариев', null, 'N;');
INSERT INTO `rights_authitem` VALUES ('Projects.Comments', '0', 'Просмотр комментариев', null, 'N;');
INSERT INTO `rights_authitem` VALUES ('Projects.Create', '0', 'Создание проектов', null, 'N;');
INSERT INTO `rights_authitem` VALUES ('Projects.Delete', '0', 'Удаление проектов', null, 'N;');
INSERT INTO `rights_authitem` VALUES ('Projects.Delete_Comment_File', '0', 'Удаление файлов в комментариях', null, 'N;');
INSERT INTO `rights_authitem` VALUES ('Projects.Download', '0', 'Скачивание файлов в комментариях', null, 'N;');
INSERT INTO `rights_authitem` VALUES ('Projects.Edit', '0', 'Редактирование проекта', null, 'N;');
INSERT INTO `rights_authitem` VALUES ('Projects.Index', '0', 'Просмотр списка проектов', null, 'N;');
INSERT INTO `rights_authitem` VALUES ('Projects.Upload_Comment_File', '0', 'Добавление файлов к коментариям', null, 'N;');
INSERT INTO `rights_authitem` VALUES ('Projects.View', '0', 'Просмотр проекта', null, 'N;');
INSERT INTO `rights_authitem` VALUES ('Users.*', '1', 'Управление пользователями', null, 'N;');
INSERT INTO `rights_authitem` VALUES ('Users.Create', '0', 'Создание нового пользователя', null, 'N;');
INSERT INTO `rights_authitem` VALUES ('Users.Delete', '0', 'Удаление пользователей', null, 'N;');
INSERT INTO `rights_authitem` VALUES ('Users.Edit', '0', 'Редактирование пользователей', null, 'N;');
INSERT INTO `rights_authitem` VALUES ('Users.Index', '0', 'Просмотр списка пользователей', null, 'N;');
INSERT INTO `rights_authitem` VALUES ('worker', '2', 'Сотрудник', null, 'N;');

-- ----------------------------
-- Table structure for `rights_authitemchild`
-- ----------------------------
DROP TABLE IF EXISTS `rights_authitemchild`;
CREATE TABLE `rights_authitemchild` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `rights_authitemchild_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `rights_authitem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `rights_authitemchild_ibfk_2` FOREIGN KEY (`child`) REFERENCES `rights_authitem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of rights_authitemchild
-- ----------------------------
INSERT INTO `rights_authitemchild` VALUES ('admin', 'Projects.Add_Comment');
INSERT INTO `rights_authitemchild` VALUES ('customer', 'Projects.Add_Comment');
INSERT INTO `rights_authitemchild` VALUES ('worker', 'Projects.Add_Comment');
INSERT INTO `rights_authitemchild` VALUES ('admin', 'Projects.Comments');
INSERT INTO `rights_authitemchild` VALUES ('customer', 'Projects.Comments');
INSERT INTO `rights_authitemchild` VALUES ('worker', 'Projects.Comments');
INSERT INTO `rights_authitemchild` VALUES ('admin', 'Projects.Create');
INSERT INTO `rights_authitemchild` VALUES ('admin', 'Projects.Delete');
INSERT INTO `rights_authitemchild` VALUES ('admin', 'Projects.Delete_Comment_File');
INSERT INTO `rights_authitemchild` VALUES ('customer', 'Projects.Delete_Comment_File');
INSERT INTO `rights_authitemchild` VALUES ('worker', 'Projects.Delete_Comment_File');
INSERT INTO `rights_authitemchild` VALUES ('admin', 'Projects.Download');
INSERT INTO `rights_authitemchild` VALUES ('customer', 'Projects.Download');
INSERT INTO `rights_authitemchild` VALUES ('worker', 'Projects.Download');
INSERT INTO `rights_authitemchild` VALUES ('admin', 'Projects.Edit');
INSERT INTO `rights_authitemchild` VALUES ('admin', 'Projects.Index');
INSERT INTO `rights_authitemchild` VALUES ('admin', 'Projects.Upload_Comment_File');
INSERT INTO `rights_authitemchild` VALUES ('customer', 'Projects.Upload_Comment_File');
INSERT INTO `rights_authitemchild` VALUES ('worker', 'Projects.Upload_Comment_File');
INSERT INTO `rights_authitemchild` VALUES ('admin', 'Projects.View');
INSERT INTO `rights_authitemchild` VALUES ('customer', 'Projects.View');
INSERT INTO `rights_authitemchild` VALUES ('worker', 'Projects.View');
INSERT INTO `rights_authitemchild` VALUES ('admin', 'Users.*');
INSERT INTO `rights_authitemchild` VALUES ('Users.*', 'Users.Create');
INSERT INTO `rights_authitemchild` VALUES ('Users.*', 'Users.Delete');
INSERT INTO `rights_authitemchild` VALUES ('Users.*', 'Users.Edit');
INSERT INTO `rights_authitemchild` VALUES ('Users.*', 'Users.Index');

-- ----------------------------
-- Table structure for `rights_rights`
-- ----------------------------
DROP TABLE IF EXISTS `rights_rights`;
CREATE TABLE `rights_rights` (
  `itemname` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `weight` int(11) NOT NULL,
  PRIMARY KEY (`itemname`),
  CONSTRAINT `rights_rights_ibfk_1` FOREIGN KEY (`itemname`) REFERENCES `rights_authitem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of rights_rights
-- ----------------------------

-- ----------------------------
-- Table structure for `users`
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', 'admin', 'ozicoder@gmail.com', 'Пользователь', '$2a$10$0JTq3q9Gr7r99gyU4eQ/Q.n/PRVIaLX0wa1h4dYjn3/7B7HQZoVoW', '$2a$10$0JTq3q9Gr7r99gyU4eQ/QK', '1231233333', '2013-03-11 00:53:03', '2013-04-16 16:42:24', '123321', '513d144460c76.jpg');
INSERT INTO `users` VALUES ('5', 'customer', 'customer@gmail.com', 'customer', '$2a$10$U6mqV1v6fLOhd02aJaFrTuvQ1xGRfxdg2jlxpkly5URTieikg.vLG', '$2a$10$U6mqV1v6fLOhd02aJaFrTx', '', '2013-04-16 02:57:41', '0000-00-00 00:00:00', '', null);
INSERT INTO `users` VALUES ('6', 'worker', 'worker@gmail.com', 'worker', '$2a$10$wAEUCSjPGwz6V4xTX/77HuR5EIMLL1zklyfhtdVc38.xkw8nLnaUK', '$2a$10$wAEUCSjPGwz6V4xTX/77H2', '', '2013-04-16 02:58:02', '2013-04-16 02:58:54', '', null);
