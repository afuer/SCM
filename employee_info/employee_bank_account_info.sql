/*
Navicat MySQL Data Transfer

Source Server         : com
Source Server Version : 50516
Source Host           : localhost:3306
Source Database       : primebank

Target Server Type    : MYSQL
Target Server Version : 50516
File Encoding         : 65001

Date: 2013-04-30 15:40:45
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `employee_bank_account_info`
-- ----------------------------
DROP TABLE IF EXISTS `employee_bank_account_info`;
CREATE TABLE `employee_bank_account_info` (
  `EMPLOYEE_BANK_ACCOUNT_INFO_ID` int(11) NOT NULL,
  `EMPLOYEE_ID` int(11) DEFAULT NULL,
  `ACCOUNT_NUMBER` varchar(100) DEFAULT NULL,
  `ACCOUNT_TYPE_ID` int(11) DEFAULT NULL,
  `BRANCH_ID` int(11) DEFAULT NULL,
  `CREATED_BY` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `CREATED_DATE` datetime DEFAULT NULL,
  `MODIFIED_BY` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `MODIFIED_DATE` datetime DEFAULT NULL,
  PRIMARY KEY (`EMPLOYEE_BANK_ACCOUNT_INFO_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of employee_bank_account_info
-- ----------------------------
INSERT INTO `employee_bank_account_info` VALUES ('1', '3000', '10036', '1', '2', null, null, 'admin', '2013-04-30 01:15:13');
INSERT INTO `employee_bank_account_info` VALUES ('2', '3004', '1003', '1', '1', '', '0000-00-00 00:00:00', 'user_name', '2013-04-29 22:47:18');
INSERT INTO `employee_bank_account_info` VALUES ('3', '0', '', '0', '0', 'admin', '2013-04-29 23:17:03', null, null);
INSERT INTO `employee_bank_account_info` VALUES ('4', '3002', '3', '1', '1', 'admin', '2013-04-29 23:17:24', 'admin', '2013-04-29 23:37:12');

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
INSERT INTO `employee_career_info` VALUES ('1', '3000', 'aaaaaaaa', '18', '1.00', '0000-00-00', '0000-00-00', '0', null, null, null, null);
INSERT INTO `employee_career_info` VALUES ('2', '3000', 'aaa11', '18', '1.00', '2013-04-24', '2013-04-25', '1', 'admin', '2013-04-30 01:02:08', 'admin', '2013-04-30 01:16:07');

-- ----------------------------
-- Table structure for `employee_education_info`
-- ----------------------------
DROP TABLE IF EXISTS `employee_education_info`;
CREATE TABLE `employee_education_info` (
  `EMPLOYEE_EDUCATION_INFO_ID` int(11) NOT NULL,
  `EMPLOYEE_ID` int(11) DEFAULT NULL,
  `QUALIFICATION_TITLE` varchar(200) DEFAULT NULL,
  `MAJOR` varchar(200) DEFAULT NULL,
  `PASSING_YEAR` date DEFAULT NULL,
  `CGPA_PERCENTAGE` decimal(4,2) DEFAULT NULL,
  `INSTITUTE_NAME` varchar(200) DEFAULT NULL,
  `STATUS` varchar(100) DEFAULT NULL,
  `START_DATE` date DEFAULT NULL,
  `END_DATE` date DEFAULT NULL,
  `CAREER_INFO` varchar(100) DEFAULT NULL,
  `CREATED_BY` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `CREATED_DATE` datetime DEFAULT NULL,
  `MODIFIED_BY` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `MODIFIED_DATE` datetime DEFAULT NULL,
  PRIMARY KEY (`EMPLOYEE_EDUCATION_INFO_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of employee_education_info
-- ----------------------------
INSERT INTO `employee_education_info` VALUES ('1', '3003', 'aaaaa111', 'ma1', '2013-04-10', '5.11', 'rrr1', 'well1', '2013-04-10', '2013-04-10', 'rrr1', null, null, 'admin', '2013-04-30 13:08:00');
INSERT INTO `employee_education_info` VALUES ('2', '3002', 'q1', 'm1', '2013-04-01', '0.00', 'i1', 's1', '2013-04-01', '2013-04-01', 'c1', 'admin', '2013-04-30 13:09:06', null, null);

-- ----------------------------
-- Table structure for `employee_family_info`
-- ----------------------------
DROP TABLE IF EXISTS `employee_family_info`;
CREATE TABLE `employee_family_info` (
  `EMPLOYEE_FAMILY_INFO_ID` int(11) NOT NULL DEFAULT '0',
  `EMPLOYEE_ID` int(11) DEFAULT NULL,
  `FAMILY_MEMBER_NAME` varchar(100) DEFAULT NULL,
  `FAMILY_RELATIONSHIP_TYPE` varchar(100) DEFAULT NULL,
  `IS_CBL_EMPLOYEE` int(1) DEFAULT NULL,
  `EMAIL` varchar(100) DEFAULT NULL,
  `CONTACT_PHONE_NO` varchar(100) DEFAULT NULL,
  `PROFESSION` varchar(100) DEFAULT NULL,
  `DATE_OF_BIRTH` date DEFAULT NULL,
  `CREATED_BY` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `CREATED_DATE` datetime DEFAULT NULL,
  `MODIFIED_BY` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `MODIFIED_DATE` datetime DEFAULT NULL,
  PRIMARY KEY (`EMPLOYEE_FAMILY_INFO_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of employee_family_info
-- ----------------------------
INSERT INTO `employee_family_info` VALUES ('1', '3002', 'aaaaaaaa', 'un', '1', '@', '1111', 'tec', '2013-04-17', null, null, null, null);
INSERT INTO `employee_family_info` VALUES ('2', '3000', 'aaa11', 'un', '1', '@', '1111', 'tec', '2013-04-03', 'admin', '2013-04-30 01:02:08', 'admin', '2013-04-30 01:16:07');

-- ----------------------------
-- Table structure for `employee_nominee_info`
-- ----------------------------
DROP TABLE IF EXISTS `employee_nominee_info`;
CREATE TABLE `employee_nominee_info` (
  `EMPLOYEE_NOMINEE_INFO_ID` int(11) NOT NULL,
  `EMPLOYEE_ID` int(11) DEFAULT NULL,
  `IS_FAMILY_MEMBER` int(1) DEFAULT NULL,
  `NOMINEE_TYPE_ID` int(11) DEFAULT NULL,
  `NOMINEE_NAME` varchar(100) DEFAULT NULL,
  `RELATIONSHIP` varchar(100) DEFAULT NULL,
  `DATE_OF_BIRTH` date DEFAULT NULL,
  `NOMINEE_PERCENTAGE` decimal(5,2) DEFAULT NULL,
  `CREATED_BY` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `CREATED_DATE` datetime DEFAULT NULL,
  `MODIFIED_BY` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `MODIFIED_DATE` datetime DEFAULT NULL,
  PRIMARY KEY (`EMPLOYEE_NOMINEE_INFO_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of employee_nominee_info
-- ----------------------------
INSERT INTO `employee_nominee_info` VALUES ('1', '3002', '1', '1', 'NOMINEE12', 'BROTHER', '2013-04-11', '10.50', 'admin', '2013-04-30 11:41:25', null, null);

-- ----------------------------
-- Table structure for `employee_office_info`
-- ----------------------------
DROP TABLE IF EXISTS `employee_office_info`;
CREATE TABLE `employee_office_info` (
  `EMPLOYEE_OFFICE_INFO_ID` int(11) NOT NULL,
  `EMPLOYEE_ID` int(11) DEFAULT NULL,
  `EMPLOYEE_TYPE_ID` int(11) DEFAULT NULL,
  `ORGANIZATION_ID` int(11) DEFAULT NULL,
  `SUPERVISOR_ID` int(11) DEFAULT NULL,
  `JOB` varchar(256) DEFAULT NULL,
  `GRADE_ID` int(11) DEFAULT NULL,
  `OFFICE_TYPE_ID` int(11) DEFAULT NULL,
  `OFFICE_PHONE_NO` varchar(50) DEFAULT NULL,
  `JOINING_DATE` date DEFAULT NULL,
  `ASSIGNMENT_CATEGORY_ID` int(11) DEFAULT NULL,
  `HANDICAP_INFO` varchar(256) DEFAULT NULL,
  `OFFICE_EMAIL` varchar(50) DEFAULT NULL,
  `RETIREMENT_DATE` date DEFAULT NULL,
  `LOCATION` varchar(256) DEFAULT NULL,
  `CREATED_BY` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `CREATED_DATE` datetime DEFAULT NULL,
  `MODIFIED_BY` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `MODIFIED_DATE` datetime DEFAULT NULL,
  PRIMARY KEY (`EMPLOYEE_OFFICE_INFO_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of employee_office_info
-- ----------------------------
INSERT INTO `employee_office_info` VALUES ('1', '3000', '3', '2', '1995056', 'sofy2', '2', '2', '12', '2013-04-01', '0', 'Good2', 'info@2', '2013-03-01', 'Dhaka1232', null, null, 'user_name', '2013-04-28 15:43:47');
INSERT INTO `employee_office_info` VALUES ('2', '0', '1', '0', '0', '', '0', '0', '', '1970-01-01', '0', '', '', '1970-01-01', '', 'admin', '2013-04-29 18:36:09', null, null);
INSERT INTO `employee_office_info` VALUES ('3', '0', '2', '0', '0', '', '0', '0', '', '1970-01-01', '0', '', '', '1970-01-01', '', 'admin', '2013-04-29 18:39:04', null, null);
INSERT INTO `employee_office_info` VALUES ('4', '0', '3', '1', '0', '', '1', '0', '1', '1970-01-01', '0', '', '', '1970-01-01', '', 'admin', '2013-04-29 18:39:39', null, null);
INSERT INTO `employee_office_info` VALUES ('5', '0', '3', '1', '0', '', '1', '0', '1', '1970-01-01', '0', '', '', '1970-01-01', '', 'admin', '2013-04-29 18:43:03', null, null);
INSERT INTO `employee_office_info` VALUES ('6', '0', '2', '0', '0', '', '0', '0', '', '1970-01-01', '0', '', '', '1970-01-01', '', 'admin', '2013-04-29 18:44:30', null, null);
INSERT INTO `employee_office_info` VALUES ('7', '0', '3', '0', '0', '', '0', '0', '', '1970-01-01', '0', '', '', '1970-01-01', '', 'admin', '2013-04-29 18:44:47', null, null);
INSERT INTO `employee_office_info` VALUES ('8', '3002', '1', '1', '2011095', 'sofy', '1', '1', '1', '1970-01-13', '1', 'Good', 'info@', '1970-01-30', 'Dhaka', 'admin', '2013-04-29 18:48:09', 'user_name', '2013-04-29 18:48:49');
