/*
Navicat MySQL Data Transfer

Source Server         : MYSQL
Source Server Version : 50709
Source Host           : localhost:3306
Source Database       : phalcon_base_app

Target Server Type    : MYSQL
Target Server Version : 50709
File Encoding         : 65001

Date: 2020-11-09 14:52:48
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for admins
-- ----------------------------
DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins` (
  `admin_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `alias` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` char(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `activated_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `blocked_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `remember_session` char(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `session_expiration` int(10) unsigned DEFAULT NULL,
  `permission_id` int(10) unsigned NOT NULL,
  `remaining_emails` int(10) unsigned NOT NULL,
  `block_emails_until` datetime DEFAULT NULL,
  PRIMARY KEY (`admin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of admins
-- ----------------------------
INSERT INTO `admins` VALUES ('1', 'admin', 'admin@email.app', '$2y$12$Z0x1U040dVg0SFVIRU40N.oTFfWgS1VZpLyvHQ3Lf/JbJjbl68.cG', '2020-09-10 20:16:08', '2020-09-10 20:18:08', '2020-10-29 21:08:43', null, null, null, null, '1', '5', null);

-- ----------------------------
-- Table structure for languages
-- ----------------------------
DROP TABLE IF EXISTS `languages`;
CREATE TABLE `languages` (
  `lang_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`lang_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of languages
-- ----------------------------
INSERT INTO `languages` VALUES ('1', 'english', 'en', '2020-08-18 00:29:03', null);
INSERT INTO `languages` VALUES ('2', 'spanish', 'es', '2020-08-18 00:29:20', null);

-- ----------------------------
-- Table structure for operations
-- ----------------------------
DROP TABLE IF EXISTS `operations`;
CREATE TABLE `operations` (
  `operation_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `operation_type_id` int(10) unsigned NOT NULL,
  `admin_id` int(10) unsigned NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` char(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip` varchar(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `browser` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` datetime NOT NULL,
  PRIMARY KEY (`operation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of operations
-- ----------------------------
INSERT INTO `operations` VALUES ('1', '1', '2', 'prueba1@prueba.app', 'd5c780dc6e8ddc711a8a2073822ca84721eabb85c5c9aae4', '::1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', '2020-10-30 18:45:09');

-- ----------------------------
-- Table structure for operation_types
-- ----------------------------
DROP TABLE IF EXISTS `operation_types`;
CREATE TABLE `operation_types` (
  `operation_type_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`operation_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of operation_types
-- ----------------------------
INSERT INTO `operation_types` VALUES ('1', 'ACC_ACTIVATION');
INSERT INTO `operation_types` VALUES ('2', 'ACC_UPDATE_EMAIL');
INSERT INTO `operation_types` VALUES ('3', 'ACC_UPDATE_PASSWORD');
INSERT INTO `operation_types` VALUES ('4', 'ACC_DELETE');
INSERT INTO `operation_types` VALUES ('5', 'ACC_RECOVER');

-- ----------------------------
-- Table structure for permissions
-- ----------------------------
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
  `permission_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`permission_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of permissions
-- ----------------------------
INSERT INTO `permissions` VALUES ('1', 'ADMIN', '2020-05-29 14:13:28');
INSERT INTO `permissions` VALUES ('2', 'LIMITED', '2020-05-29 18:24:46');
INSERT INTO `permissions` VALUES ('3', 'VISITOR', '2020-05-29 18:25:15');
INSERT INTO `permissions` VALUES ('4', 'BLOCKED', '2020-07-15 15:17:17');
