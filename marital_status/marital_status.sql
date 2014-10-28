/*
Navicat MySQL Data Transfer

Source Server         : com
Source Server Version : 50516
Source Host           : localhost:3306
Source Database       : primebank

Target Server Type    : MYSQL
Target Server Version : 50516
File Encoding         : 65001

Date: 2013-04-24 11:33:56
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `marital_status`
-- ----------------------------
DROP TABLE IF EXISTS `marital_status`;
CREATE TABLE `marital_status` (
  `MARITAL_STATUS_ID` int(11) unsigned NOT NULL,
  `MARITAL_STATUS_NAME` varchar(100) DEFAULT NULL COMMENT 'Marital Status,y,Y,,,20,1',
  `CREATED_DATE` datetime NOT NULL,
  `MODIFY_BY` varchar(50) DEFAULT NULL,
  `MODIFY_DATE` date DEFAULT NULL,
  `CREATED_BY` varchar(50) NOT NULL,
  PRIMARY KEY (`MARITAL_STATUS_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of marital_status
-- ----------------------------
INSERT INTO `marital_status` VALUES ('1', 'Unmarried', '2013-04-23 00:00:00', '2013-04-24', null, 'admin');
INSERT INTO `marital_status` VALUES ('2', 'Married', '2013-04-24 00:00:00', '2013-04-24', null, 'admin');
