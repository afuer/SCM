/*
Navicat MySQL Data Transfer

Source Server         : com
Source Server Version : 50516
Source Host           : localhost:3306
Source Database       : primebank

Target Server Type    : MYSQL
Target Server Version : 50516
File Encoding         : 65001

Date: 2013-04-28 12:49:25
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `assignment_category`
-- ----------------------------
DROP TABLE IF EXISTS `assignment_category`;
CREATE TABLE `assignment_category` (
  `ASSIGNMENT_CATEGORY_ID` int(10) unsigned NOT NULL,
  `ASSIGNMENT_CATEGORY_NAME` varchar(200) DEFAULT NULL COMMENT 'Assignment Category Name,y,Y,,,20,1',
  `CREATED_DATE` datetime NOT NULL,
  `MODIFY_BY` varchar(50) DEFAULT NULL,
  `MODIFY_DATE` date DEFAULT NULL,
  `CREATED_BY` varchar(50) NOT NULL,
  PRIMARY KEY (`ASSIGNMENT_CATEGORY_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of assignment_category
-- ----------------------------
INSERT INTO `assignment_category` VALUES ('1', 'Software Eng', '2013-04-28 00:00:00', '2013-04-28', null, 'admin');
