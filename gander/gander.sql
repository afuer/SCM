/*
Navicat MySQL Data Transfer

Source Server         : com
Source Server Version : 50516
Source Host           : localhost:3306
Source Database       : primebank

Target Server Type    : MYSQL
Target Server Version : 50516
File Encoding         : 65001

Date: 2013-04-24 11:44:46
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `gander`
-- ----------------------------
DROP TABLE IF EXISTS `gander`;
CREATE TABLE `gander` (
  `GANDER_ID` int(10) unsigned NOT NULL,
  `GANDER_NAME` varchar(100) DEFAULT NULL COMMENT 'Gander,y,Y,,,20,1',
  `CREATED_DATE` datetime NOT NULL,
  `MODIFY_BY` varchar(50) DEFAULT NULL,
  `MODIFY_DATE` date DEFAULT NULL,
  `CREATED_BY` varchar(50) NOT NULL,
  PRIMARY KEY (`GANDER_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of gander
-- ----------------------------
INSERT INTO `gander` VALUES ('2', 'Female', '2013-04-24 00:00:00', null, null, 'admin');
INSERT INTO `gander` VALUES ('1', 'Male', '2013-04-24 00:00:00', '2013-04-24', null, 'admin');
