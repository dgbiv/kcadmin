/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50714
Source Host           : localhost:3306
Source Database       : kcshop

Target Server Type    : MYSQL
Target Server Version : 50714
File Encoding         : 65001

Date: 2018-03-14 17:20:44
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `menu`
-- ----------------------------
DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `parent` int(11) DEFAULT NULL,
  `route` varchar(255) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `data` blob,
  PRIMARY KEY (`id`),
  KEY `parent` (`parent`),
  CONSTRAINT `menu_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `menu` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of menu
-- ----------------------------
-- ----------------------------
-- Table structure for `user`
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `auth_key` varchar(32) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `password_reset_token` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `role` smallint(6) NOT NULL DEFAULT '10',
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user
-- ----------------------------

-- ----------------------------
-- Table structure for `auth_assignment`
-- ----------------------------
DROP TABLE IF EXISTS `auth_assignment`;
CREATE TABLE `auth_assignment` (
  `item_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  KEY `auth_assignment_user_id_idx` (`user_id`),
  CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of auth_assignment
-- ----------------------------
/*INSERT INTO `auth_assignment` VALUES ('超级管理员', '1', '1520860768');*/

-- ----------------------------
-- Table structure for `auth_item`
-- ----------------------------
DROP TABLE IF EXISTS `auth_item`;
CREATE TABLE `auth_item` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `type` smallint(6) NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `rule_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` blob,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `type` (`type`),
  CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of auth_item
-- ----------------------------
/*INSERT INTO `auth_item` VALUES ('/admin/*', '2', null, null, null, '1520860444', '1520860444');
INSERT INTO `auth_item` VALUES ('/admin/assignment/*', '2', null, null, null, '1520663943', '1520663943');
INSERT INTO `auth_item` VALUES ('/admin/default/*', '2', null, null, null, '1520663943', '1520663943');
INSERT INTO `auth_item` VALUES ('/admin/menu/*', '2', null, null, null, '1520663943', '1520663943');
INSERT INTO `auth_item` VALUES ('/admin/permission/*', '2', null, null, null, '1520663944', '1520663944');
INSERT INTO `auth_item` VALUES ('/admin/role/*', '2', null, null, null, '1520663944', '1520663944');
INSERT INTO `auth_item` VALUES ('/admin/route/*', '2', null, null, null, '1520663944', '1520663944');
INSERT INTO `auth_item` VALUES ('/admin/rule/*', '2', null, null, null, '1520663944', '1520663944');
INSERT INTO `auth_item` VALUES ('/admin/user/*', '2', null, null, null, '1520663944', '1520663944');
INSERT INTO `auth_item` VALUES ('/adminuser/*', '2', null, null, null, '1520662753', '1520662753');
INSERT INTO `auth_item` VALUES ('/adminuser/create', '2', null, null, null, '1520662752', '1520662752');
INSERT INTO `auth_item` VALUES ('/adminuser/delete', '2', null, null, null, '1520662753', '1520662753');
INSERT INTO `auth_item` VALUES ('/adminuser/index', '2', null, null, null, '1520662751', '1520662751');
INSERT INTO `auth_item` VALUES ('/adminuser/update', '2', null, null, null, '1520662753', '1520662753');
INSERT INTO `auth_item` VALUES ('/adminuser/view', '2', null, null, null, '1520662752', '1520662752');
INSERT INTO `auth_item` VALUES ('/country/*', '2', null, null, null, '1520860445', '1520860445');
INSERT INTO `auth_item` VALUES ('/debug/*', '2', null, null, null, '1520860445', '1520860445');
INSERT INTO `auth_item` VALUES ('/debug/default/*', '2', null, null, null, '1520860445', '1520860445');
INSERT INTO `auth_item` VALUES ('/gii/*', '2', null, null, null, '1520860445', '1520860445');
INSERT INTO `auth_item` VALUES ('/site/*', '2', null, null, null, '1520663736', '1520663736');
INSERT INTO `auth_item` VALUES ('所有操作权限', '2', null, null, null, '1520860674', '1520860674');
INSERT INTO `auth_item` VALUES ('超级管理员', '1', null, null, null, '1520860650', '1520860698');*/

-- ----------------------------
-- Table structure for `auth_item_child`
-- ----------------------------
DROP TABLE IF EXISTS `auth_item_child`;
CREATE TABLE `auth_item_child` (
  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of auth_item_child
-- ----------------------------
/*INSERT INTO `auth_item_child` VALUES ('所有操作权限', '/admin/*');
INSERT INTO `auth_item_child` VALUES ('所有操作权限', '/admin/assignment/*');
INSERT INTO `auth_item_child` VALUES ('所有操作权限', '/admin/default/*');
INSERT INTO `auth_item_child` VALUES ('所有操作权限', '/admin/menu/*');
INSERT INTO `auth_item_child` VALUES ('所有操作权限', '/admin/permission/*');
INSERT INTO `auth_item_child` VALUES ('所有操作权限', '/admin/role/*');
INSERT INTO `auth_item_child` VALUES ('所有操作权限', '/admin/route/*');
INSERT INTO `auth_item_child` VALUES ('所有操作权限', '/admin/rule/*');
INSERT INTO `auth_item_child` VALUES ('所有操作权限', '/admin/user/*');
INSERT INTO `auth_item_child` VALUES ('所有操作权限', '/adminuser/*');
INSERT INTO `auth_item_child` VALUES ('所有操作权限', '/adminuser/create');
INSERT INTO `auth_item_child` VALUES ('所有操作权限', '/adminuser/delete');
INSERT INTO `auth_item_child` VALUES ('所有操作权限', '/adminuser/index');
INSERT INTO `auth_item_child` VALUES ('所有操作权限', '/adminuser/update');
INSERT INTO `auth_item_child` VALUES ('所有操作权限', '/adminuser/view');
INSERT INTO `auth_item_child` VALUES ('所有操作权限', '/country/*');
INSERT INTO `auth_item_child` VALUES ('所有操作权限', '/debug/*');
INSERT INTO `auth_item_child` VALUES ('所有操作权限', '/debug/default/*');
INSERT INTO `auth_item_child` VALUES ('所有操作权限', '/gii/*');
INSERT INTO `auth_item_child` VALUES ('所有操作权限', '/site/*');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '所有操作权限');*/

-- ----------------------------
-- Table structure for `auth_rule`
-- ----------------------------
DROP TABLE IF EXISTS `auth_rule`;
CREATE TABLE `auth_rule` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `data` blob,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of auth_rule
-- ----------------------------

-- ----------------------------
-- Table structure for `auth_user`
-- ----------------------------
DROP TABLE IF EXISTS `auth_user`;
CREATE TABLE `auth_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `auth_key` varchar(32) NOT NULL,
  `introduction` varchar(255) DEFAULT NULL,
  `password_reset_token` varchar(255) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` smallint(6) NOT NULL DEFAULT '1',
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL DEFAULT '10',
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of auth_user
-- ----------------------------
/*INSERT INTO `auth_user` VALUES ('1', 'admin', 'o-hwP0ZxUv3HQ1jCf2f_50skwOue_c9f', '超级管理员', 'XC-vAZH7zL_n1fqwfMxJGA1KyyNHfSdB_1520936298', '$2y$13$dtAlAZT9yo5r49WESigKTO5zxAAcoipyg6TAf0JaqB/QSVyVdAUpW', 'admin@admin.com', '1', '10', '1520320614', '1520936298',0);*/