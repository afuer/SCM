/*
Navicat MySQL Data Transfer

Source Server         : com
Source Server Version : 50516
Source Host           : localhost:3306
Source Database       : primebank

Target Server Type    : MYSQL
Target Server Version : 50516
File Encoding         : 65001

Date: 2013-04-24 10:56:50
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `zone`
-- ----------------------------
DROP TABLE IF EXISTS `zone`;
CREATE TABLE `zone` (
  `ZONE_ID` int(10) unsigned NOT NULL,
  `ZONE_NAME` varchar(100) DEFAULT NULL COMMENT 'Zone Name,y,Y,,,20,1',
  `CREATED_DATE` datetime NOT NULL,
  `MODIFY_BY` varchar(50) DEFAULT NULL,
  `MODIFY_DATE` date DEFAULT NULL,
  `CREATED_BY` varchar(50) NOT NULL,
  PRIMARY KEY (`ZONE_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zone
-- ----------------------------
INSERT INTO `zone` VALUES ('1', 'Jossor', '2013-04-23 00:00:00', '2013-04-24', null, 'admin');
INSERT INTO `zone` VALUES ('3', 'Dhaka', '2013-04-24 00:00:00', null, null, 'admin');
INSERT INTO `zone` VALUES ('4', 'Khulna', '2013-04-24 00:00:00', null, null, 'admin');
