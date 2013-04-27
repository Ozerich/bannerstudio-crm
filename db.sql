/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50525
Source Host           : localhost:3306
Source Database       : banners

Target Server Type    : MYSQL
Target Server Version : 50525
File Encoding         : 65001

Date: 2013-04-27 19:25:49
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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of project_comment_files
-- ----------------------------
INSERT INTO `project_comment_files` VALUES ('2', '6', '516c998b174f2.png', 'bg.png', '9139');
INSERT INTO `project_comment_files` VALUES ('3', '7', '516c97a4405ae.png', 'bg.png', '9139');
INSERT INTO `project_comment_files` VALUES ('7', '16', '517911f97d002.png', 'sost_TC-56.png', '662');
INSERT INTO `project_comment_files` VALUES ('8', '17', '51791205e6bfe.png', 'sost_TC-49.png', '525');
INSERT INTO `project_comment_files` VALUES ('9', '18', '5179121921766.png', 'sost_TC-56.png', '662');
INSERT INTO `project_comment_files` VALUES ('10', '19', '51794e54a9b01.png', 'sost_TC-29a (5).png', '874');
INSERT INTO `project_comment_files` VALUES ('11', '6', '517be9ec17dbe.xlsx', 'Book1.xlsx', '14587');
INSERT INTO `project_comment_files` VALUES ('12', '7', '517bea0903fc7.png', 'IMG_18042013_175110.png', '401880');
INSERT INTO `project_comment_files` VALUES ('13', '13', '517bf16a11fb1.jpg', 'newbikes_b_v2_01.jpg', '126689');
INSERT INTO `project_comment_files` VALUES ('14', '13', '517bf16a1aa19.jpg', '2013-04-16 14.13.04.jpg', '1848404');

-- ----------------------------
-- Table structure for `project_comment_reads`
-- ----------------------------
DROP TABLE IF EXISTS `project_comment_reads`;
CREATE TABLE `project_comment_reads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of project_comment_reads
-- ----------------------------
INSERT INTO `project_comment_reads` VALUES ('1', '1', '4');
INSERT INTO `project_comment_reads` VALUES ('2', '1', '1');
INSERT INTO `project_comment_reads` VALUES ('3', '6', '3');
INSERT INTO `project_comment_reads` VALUES ('4', '1', '15');
INSERT INTO `project_comment_reads` VALUES ('5', '1', '5');
INSERT INTO `project_comment_reads` VALUES ('6', '1', '3');
INSERT INTO `project_comment_reads` VALUES ('7', '1', '2');
INSERT INTO `project_comment_reads` VALUES ('8', '12', '7');
INSERT INTO `project_comment_reads` VALUES ('9', '12', '6');
INSERT INTO `project_comment_reads` VALUES ('10', '12', '5');
INSERT INTO `project_comment_reads` VALUES ('11', '12', '4');
INSERT INTO `project_comment_reads` VALUES ('12', '12', '3');
INSERT INTO `project_comment_reads` VALUES ('13', '12', '2');
INSERT INTO `project_comment_reads` VALUES ('14', '12', '1');
INSERT INTO `project_comment_reads` VALUES ('15', '1', '8');
INSERT INTO `project_comment_reads` VALUES ('16', '1', '9');
INSERT INTO `project_comment_reads` VALUES ('17', '12', '10');
INSERT INTO `project_comment_reads` VALUES ('18', '1', '11');
INSERT INTO `project_comment_reads` VALUES ('19', '1', '12');

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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of project_comments
-- ----------------------------
INSERT INTO `project_comments` VALUES ('1', '1', '1', 'Привет юзер', '2013-04-25 18:51:27', 'worker');
INSERT INTO `project_comments` VALUES ('2', '13', '1', 'Привет админко', '2013-04-25 18:52:06', 'worker');
INSERT INTO `project_comments` VALUES ('3', '13', '1', 'Привет админка', '2013-04-25 18:52:14', 'worker');
INSERT INTO `project_comments` VALUES ('4', '13', '1', '1', '2013-04-25 18:53:48', 'worker');
INSERT INTO `project_comments` VALUES ('5', '13', '1', '1', '2013-04-25 18:53:56', 'worker');
INSERT INTO `project_comments` VALUES ('6', '1', '1', '1231', '2013-04-27 18:08:28', 'worker');
INSERT INTO `project_comments` VALUES ('7', '1', '1', '123123', '2013-04-27 18:08:56', 'worker');
INSERT INTO `project_comments` VALUES ('8', '12', '1', 'че', '2013-04-27 18:20:08', 'worker');
INSERT INTO `project_comments` VALUES ('9', '12', '1', '5656', '2013-04-27 18:21:34', 'worker');
INSERT INTO `project_comments` VALUES ('10', '1', '1', '123', '2013-04-27 18:22:49', 'worker');
INSERT INTO `project_comments` VALUES ('11', '12', '1', '123213', '2013-04-27 18:23:13', 'worker');
INSERT INTO `project_comments` VALUES ('12', '12', '1', '1233123', '2013-04-27 18:24:10', 'worker');
INSERT INTO `project_comments` VALUES ('13', '1', '1', '1', '2013-04-27 18:40:26', 'customer');

-- ----------------------------
-- Table structure for `project_users`
-- ----------------------------
DROP TABLE IF EXISTS `project_users`;
CREATE TABLE `project_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of project_users
-- ----------------------------
INSERT INTO `project_users` VALUES ('29', '12', '1');
INSERT INTO `project_users` VALUES ('30', '13', '1');

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of projects
-- ----------------------------
INSERT INTO `projects` VALUES ('1', '1111', 'customer_created', '0', '2', '1', '2', '1', '2013-04-25 18:51:12');

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
INSERT INTO `rights_authassignment` VALUES ('admin', '10', null, 'N;');
INSERT INTO `rights_authassignment` VALUES ('admin', '9', null, 'N;');
INSERT INTO `rights_authassignment` VALUES ('admin', 'admin', null, 'N;');
INSERT INTO `rights_authassignment` VALUES ('customer', '11', null, 'N;');
INSERT INTO `rights_authassignment` VALUES ('customer', '13', null, 'N;');
INSERT INTO `rights_authassignment` VALUES ('customer', '2', null, 'N;');
INSERT INTO `rights_authassignment` VALUES ('customer', '5', null, 'N;');
INSERT INTO `rights_authassignment` VALUES ('customer', 'customer', null, 'N;');
INSERT INTO `rights_authassignment` VALUES ('worker', '10', null, 'N;');
INSERT INTO `rights_authassignment` VALUES ('worker', '12', null, 'N;');
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
INSERT INTO `rights_authitem` VALUES ('Projects.Admin_Comment', '0', 'Комментарий от админа(\"Заказчику\", \"Сотруднику\")', null, 'N;');
INSERT INTO `rights_authitem` VALUES ('Projects.Comments', '0', 'Просмотр комментариев', null, 'N;');
INSERT INTO `rights_authitem` VALUES ('Projects.Create', '0', 'Создание проектов', null, 'N;');
INSERT INTO `rights_authitem` VALUES ('Projects.Delete', '0', 'Удаление проектов', null, 'N;');
INSERT INTO `rights_authitem` VALUES ('Projects.Delete_Comment', '0', 'Удаление комментариев', null, 'N;');
INSERT INTO `rights_authitem` VALUES ('Projects.Delete_Comment_File', '0', 'Удаление файлов в комментариях', null, 'N;');
INSERT INTO `rights_authitem` VALUES ('Projects.Delete_Files', '0', 'Множественное удаление файлов', null, 'N;');
INSERT INTO `rights_authitem` VALUES ('Projects.Download', '0', 'Скачивание файлов в комментариях', null, 'N;');
INSERT INTO `rights_authitem` VALUES ('Projects.Edit', '0', 'Редактирование проекта', null, 'N;');
INSERT INTO `rights_authitem` VALUES ('Projects.Edit_Comment', '0', 'Редактирование комментария', null, 'N;');
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
INSERT INTO `rights_authitemchild` VALUES ('admin', 'Projects.Admin_Comment');
INSERT INTO `rights_authitemchild` VALUES ('admin', 'Projects.Comments');
INSERT INTO `rights_authitemchild` VALUES ('customer', 'Projects.Comments');
INSERT INTO `rights_authitemchild` VALUES ('worker', 'Projects.Comments');
INSERT INTO `rights_authitemchild` VALUES ('admin', 'Projects.Create');
INSERT INTO `rights_authitemchild` VALUES ('admin', 'Projects.Delete');
INSERT INTO `rights_authitemchild` VALUES ('admin', 'Projects.Delete_Comment');
INSERT INTO `rights_authitemchild` VALUES ('admin', 'Projects.Delete_Comment_File');
INSERT INTO `rights_authitemchild` VALUES ('customer', 'Projects.Delete_Comment_File');
INSERT INTO `rights_authitemchild` VALUES ('worker', 'Projects.Delete_Comment_File');
INSERT INTO `rights_authitemchild` VALUES ('admin', 'Projects.Delete_Files');
INSERT INTO `rights_authitemchild` VALUES ('admin', 'Projects.Download');
INSERT INTO `rights_authitemchild` VALUES ('customer', 'Projects.Download');
INSERT INTO `rights_authitemchild` VALUES ('worker', 'Projects.Download');
INSERT INTO `rights_authitemchild` VALUES ('admin', 'Projects.Edit');
INSERT INTO `rights_authitemchild` VALUES ('admin', 'Projects.Edit_Comment');
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
-- Table structure for `slider_items`
-- ----------------------------
DROP TABLE IF EXISTS `slider_items`;
CREATE TABLE `slider_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `page` int(11) NOT NULL,
  `file` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of slider_items
-- ----------------------------
INSERT INTO `slider_items` VALUES ('2', '1', '1', '517bf16a11fb1.jpg');
INSERT INTO `slider_items` VALUES ('3', '1', '1', '517bf16a1aa19.jpg');
INSERT INTO `slider_items` VALUES ('4', '1', '1', '517bf16a11fb1.jpg');
INSERT INTO `slider_items` VALUES ('5', '1', '1', '517bf16a1aa19.jpg');
INSERT INTO `slider_items` VALUES ('6', '1', '2', '517bf16a11fb1.jpg');
INSERT INTO `slider_items` VALUES ('7', '1', '2', '517bf16a1aa19.jpg');
INSERT INTO `slider_items` VALUES ('8', '1', '2', '517bf16a11fb1.jpg');
INSERT INTO `slider_items` VALUES ('9', '1', '2', '517bf16a1aa19.jpg');
INSERT INTO `slider_items` VALUES ('10', '1', '1', '517bf16a11fb1.jpg');

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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', 'admin', 'ozicoder@gmail.com', 'Пользователь', '$2a$10$0JTq3q9Gr7r99gyU4eQ/Q.n/PRVIaLX0wa1h4dYjn3/7B7HQZoVoW', '$2a$10$0JTq3q9Gr7r99gyU4eQ/QK', '1231233333', '2013-03-11 00:53:03', '2013-04-27 18:08:12', '123321', '513d144460c76.jpg');
INSERT INTO `users` VALUES ('12', 'worker', 'worker@gmail.com', 'worker', '$2a$10$H/qEqV75CFRhN9kTyCDhB.wc0.fU9d3KT8H5KOJUmpslkVoMv1uhm', '$2a$10$H/qEqV75CFRhN9kTyCDhBB', '', '2013-04-25 18:50:45', '2013-04-27 18:17:35', '', null);
INSERT INTO `users` VALUES ('13', 'customer', 'customer@gmail.com', 'customer', '$2a$10$1VD4Rfk.lERisOgEjEoK..momhCHkhS4kCDY1.g8BEe/DNsZ6gs1G', '$2a$10$1VD4Rfk.lERisOgEjEoK./', '', '2013-04-25 18:50:59', '2013-04-25 18:51:56', '', null);
