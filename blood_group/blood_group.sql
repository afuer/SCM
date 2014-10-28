/*
Navicat MySQL Data Transfer

Source Server         : com
Source Server Version : 50516
Source Host           : localhost:3306
Source Database       : primebank

Target Server Type    : MYSQL
Target Server Version : 50516
File Encoding         : 65001

Date: 2013-04-23 17:40:10
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `blood_group`
-- ----------------------------
DROP TABLE IF EXISTS `blood_group`;
CREATE TABLE `blood_group` (
  `BLOOD_GROUP_ID` int(10) unsigned NOT NULL,
  `BLOOD_GROUP_NAME` varchar(10) DEFAULT NULL COMMENT 'Blood Group Name,y,Y,,,20,1',
  `CREATED_DATE` datetime NOT NULL,
  `MODIFY_BY` varchar(50) DEFAULT NULL,
  `MODIFY_DATE` date DEFAULT NULL,
  `CREATED_BY` varchar(50) NOT NULL,
  PRIMARY KEY (`BLOOD_GROUP_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of blood_group
-- ----------------------------
INSERT INTO `blood_group` VALUES ('1', 'B+', '2013-04-23 00:00:00', '2013-04-23', null, 'admin');
INSERT INTO `blood_group` VALUES ('2', 'A+', '2013-04-23 00:00:00', null, null, 'admin');
