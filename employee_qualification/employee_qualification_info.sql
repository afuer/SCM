/*
Navicat MySQL Data Transfer

Source Server         : com
Source Server Version : 50516
Source Host           : localhost:3306
Source Database       : primebank

Target Server Type    : MYSQL
Target Server Version : 50516
File Encoding         : 65001

Date: 2013-05-01 10:36:44
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `employee_career_info`
-- ----------------------------
DROP TABLE IF EXISTS `employee_career_info`;
CREATE TABLE `employee_career_info` (
  `EMPLOYEE_CAREER_INFO_ID` int(11) NOT NULL DEFAULT '0',
  `EMPLOYEE_ID` int(11) DEFAULT NULL,
  `ORGANIZATION_NAME` varchar(100) DEFAULT NULL,
  `DESIGNATION_ID` int(11) DEFAULT NULL,
  `YEAR_OF_EXPERIENCE` decimal(4,2) DEFAULT NULL,
  `CAREER_START_DATE` date DEFAULT NULL,
  `CAREER_END_DATE` date DEFAULT NULL,
  `STATUS` int(1) DEFAULT NULL,
  `CREATED_BY` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `CREATED_DATE` datetime DEFAULT NULL,
  `MODIFIED_BY` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `MODIFIED_DATE` datetime DEFAULT NULL,
  PRIMARY KEY (`EMPLOYEE_CAREER_INFO_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of employee_career_info
-- ----------------------------
INSERT INTO `employee_career_info` VALUES ('2', '3000', 'apsis', '18', '12.00', '2013-04-03', '2013-04-02', '1', 'admin', '2013-04-30 01:02:08', 'admin', '2013-05-01 10:22:53');
INSERT INTO `employee_career_info` VALUES ('5', '3000', 'ics', '18', '3.00', '2013-04-02', '2013-04-01', '0', 'admin', '2013-04-30 18:19:14', 'admin', '2013-05-01 10:15:30');
INSERT INTO `employee_career_info` VALUES ('6', '3002', 'rokib', '18', '6.00', '2013-05-09', '2013-05-08', '1', 'admin', '2013-05-01 09:59:07', null, null);
INSERT INTO `employee_career_info` VALUES ('7', '2027', '3000org1', '18', '5.00', '2013-05-16', '2013-05-07', '1', 'admin', '2013-05-01 10:26:08', 'admin', '2013-05-01 10:26:15');
