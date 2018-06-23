/*
Navicat MySQL Data Transfer

Source Server         : xl
Source Server Version : 50710
Source Host           : localhost:3306
Source Database       : forum

Target Server Type    : MYSQL
Target Server Version : 50710
File Encoding         : 65001

Date: 2018-06-23 21:05:14
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `tb_floor`
-- ----------------------------
DROP TABLE IF EXISTS `tb_floor`;
CREATE TABLE `tb_floor` (
  `floor_id` int(11) NOT NULL AUTO_INCREMENT,
  `postings_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `value` text NOT NULL,
  `create_time` int(11) NOT NULL,
  `is_ban` int(11) NOT NULL DEFAULT '0',
  `floor_no` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`floor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tb_floor
-- ----------------------------
INSERT INTO `tb_floor` VALUES ('1', '1', '1', '[{\"tag\":\"p\",\"children\":[\"你好!\",{\"tag\":\"br\"}]}]', '1528807221', '0', '1');
INSERT INTO `tb_floor` VALUES ('2', '1', '1', '[{\"tag\":\"p\",\"children\":[\"回复自己\",{\"tag\":\"br\"}]}]', '1528807322', '0', '2');
INSERT INTO `tb_floor` VALUES ('3', '2', '1', '[{\"tag\":\"p\",\"children\":[\"1\",{\"tag\":\"br\"}]}]', '1528808656', '0', '1');
INSERT INTO `tb_floor` VALUES ('4', '3', '1', '[{\"tag\":\"p\",\"children\":[\"2\",{\"tag\":\"br\"}]}]', '1528808691', '0', '1');
INSERT INTO `tb_floor` VALUES ('5', '4', '1', '[{\"tag\":\"p\",\"children\":[\"3\",{\"tag\":\"br\"}]}]', '1528808730', '0', '1');
INSERT INTO `tb_floor` VALUES ('6', '5', '1', '[{\"tag\":\"p\",\"children\":[\"4\",{\"tag\":\"br\"}]}]', '1528808739', '0', '1');
INSERT INTO `tb_floor` VALUES ('7', '6', '1', '[{\"tag\":\"p\",\"children\":[\"5\",{\"tag\":\"br\"}]}]', '1528808745', '0', '1');
INSERT INTO `tb_floor` VALUES ('8', '7', '1', '[{\"tag\":\"p\",\"children\":[\"6\",{\"tag\":\"br\"}]}]', '1528808750', '0', '1');
INSERT INTO `tb_floor` VALUES ('9', '8', '1', '[{\"tag\":\"p\",\"children\":[\"7\",{\"tag\":\"br\"}]}]', '1528808754', '0', '1');
INSERT INTO `tb_floor` VALUES ('10', '9', '1', '[{\"tag\":\"p\",\"children\":[\"8\",{\"tag\":\"br\"}]}]', '1528808758', '0', '1');
INSERT INTO `tb_floor` VALUES ('11', '10', '1', '[{\"tag\":\"p\",\"children\":[\"9\",{\"tag\":\"br\"}]}]', '1528808763', '0', '1');
INSERT INTO `tb_floor` VALUES ('12', '11', '1', '[{\"tag\":\"p\",\"children\":[\"10\",{\"tag\":\"br\"}]}]', '1528808769', '0', '1');
INSERT INTO `tb_floor` VALUES ('13', '12', '1', '[{\"tag\":\"p\",\"children\":[\"11\",{\"tag\":\"br\"}]}]', '1528808774', '0', '1');
INSERT INTO `tb_floor` VALUES ('14', '13', '1', '[{\"tag\":\"p\",\"children\":[\"12\",{\"tag\":\"br\"}]}]', '1528808778', '0', '1');
INSERT INTO `tb_floor` VALUES ('15', '14', '1', '[{\"tag\":\"p\",\"children\":[\"13\",{\"tag\":\"br\"}]}]', '1528808782', '0', '1');
INSERT INTO `tb_floor` VALUES ('16', '15', '1', '[{\"tag\":\"p\",\"children\":[\"14\",{\"tag\":\"br\"}]}]', '1528808787', '0', '1');
INSERT INTO `tb_floor` VALUES ('17', '16', '1', '[{\"tag\":\"p\",\"children\":[\"15\",{\"tag\":\"br\"}]}]', '1528808791', '0', '1');
INSERT INTO `tb_floor` VALUES ('18', '17', '1', '[{\"tag\":\"p\",\"children\":[\"16\",{\"tag\":\"br\"}]}]', '1528808795', '0', '1');
INSERT INTO `tb_floor` VALUES ('19', '18', '1', '[{\"tag\":\"p\",\"children\":[\"17\",{\"tag\":\"br\"}]}]', '1528808799', '0', '1');
INSERT INTO `tb_floor` VALUES ('20', '19', '1', '[{\"tag\":\"p\",\"children\":[\"18\",{\"tag\":\"br\"}]}]', '1528808804', '0', '1');
INSERT INTO `tb_floor` VALUES ('21', '20', '1', '[{\"tag\":\"p\",\"children\":[\"19\",{\"tag\":\"br\"}]}]', '1528808808', '0', '1');
INSERT INTO `tb_floor` VALUES ('22', '21', '1', '[{\"tag\":\"p\",\"children\":[\"20\",{\"tag\":\"br\"}]}]', '1528808815', '0', '1');
INSERT INTO `tb_floor` VALUES ('23', '1', '1', '[{\"tag\":\"p\",\"children\":[\"回复一下\",{\"tag\":\"br\"}]}]', '1528809500', '1', '3');
INSERT INTO `tb_floor` VALUES ('24', '15', '1', '[{\"tag\":\"p\",\"children\":[\"突然回复\",{\"tag\":\"br\"}]}]', '1528811816', '0', '2');
INSERT INTO `tb_floor` VALUES ('25', '1', '2', '[{\"tag\":\"p\",\"children\":[\"我是色魔2号\",{\"tag\":\"br\"}]}]', '1528811969', '0', '4');
INSERT INTO `tb_floor` VALUES ('26', '8', '2', '[{\"tag\":\"p\",\"children\":[\"777\",{\"tag\":\"br\"}]}]', '1528812500', '0', '2');
INSERT INTO `tb_floor` VALUES ('27', '1', '2', '[{\"tag\":\"p\",\"children\":[\"楼层分页1\"]}]', '1528819629', '0', '5');
INSERT INTO `tb_floor` VALUES ('28', '1', '2', '[{\"tag\":\"p\",\"children\":[\"楼层分页2 \",{\"tag\":\"br\"}]}]', '1528819640', '0', '6');
INSERT INTO `tb_floor` VALUES ('29', '1', '2', '[{\"tag\":\"p\",\"children\":[\"楼层分页3 \",{\"tag\":\"br\"}]}]', '1528819652', '0', '7');
INSERT INTO `tb_floor` VALUES ('30', '1', '2', '[{\"tag\":\"p\",\"children\":[\"楼层分页4 \",{\"tag\":\"br\"}]}]', '1528819663', '0', '8');
INSERT INTO `tb_floor` VALUES ('31', '1', '2', '[{\"tag\":\"p\",\"children\":[\"楼层分页5 \",{\"tag\":\"br\"}]}]', '1528819674', '0', '9');
INSERT INTO `tb_floor` VALUES ('32', '1', '2', '[{\"tag\":\"p\",\"children\":[\"楼层分页6 \",{\"tag\":\"br\"}]}]', '1528819683', '0', '10');
INSERT INTO `tb_floor` VALUES ('33', '1', '2', '[{\"tag\":\"p\",\"children\":[\"楼层分页7 \",{\"tag\":\"br\"}]}]', '1528819691', '0', '11');
INSERT INTO `tb_floor` VALUES ('34', '1', '2', '[{\"tag\":\"p\",\"children\":[\"楼层分页8 \",{\"tag\":\"br\"}]}]', '1528819700', '0', '12');
INSERT INTO `tb_floor` VALUES ('35', '1', '2', '[{\"tag\":\"p\",\"children\":[\"楼层分页9 \",{\"tag\":\"br\"}]}]', '1528819709', '0', '13');
INSERT INTO `tb_floor` VALUES ('36', '1', '2', '[{\"tag\":\"p\",\"children\":[\"楼层分页10 \",{\"tag\":\"br\"}]}]', '1528819718', '0', '14');
INSERT INTO `tb_floor` VALUES ('37', '1', '2', '[{\"tag\":\"p\",\"children\":[\"楼层分页11 \",{\"tag\":\"br\"}]}]', '1528819726', '0', '15');
INSERT INTO `tb_floor` VALUES ('38', '1', '2', '[{\"tag\":\"p\",\"children\":[\"楼层分页12 \",{\"tag\":\"br\"}]}]', '1528819735', '0', '16');
INSERT INTO `tb_floor` VALUES ('39', '1', '1', '[{\"tag\":\"p\",\"children\":[\"端午\"]}]', '1528980981', '0', '17');
INSERT INTO `tb_floor` VALUES ('40', '22', '1', '[{\"tag\":\"p\",\"children\":[\"ddd\",{\"tag\":\"br\"}]}]', '1529400447', '0', '1');

-- ----------------------------
-- Table structure for `tb_img`
-- ----------------------------
DROP TABLE IF EXISTS `tb_img`;
CREATE TABLE `tb_img` (
  `img_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `src` varchar(70) NOT NULL,
  PRIMARY KEY (`img_id`)
) ENGINE=InnoDB AUTO_INCREMENT=96 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tb_img
-- ----------------------------
INSERT INTO `tb_img` VALUES ('1', '1', 'http://forum.thxyfreenet.cn/resources/1528635078.jpeg');
INSERT INTO `tb_img` VALUES ('2', '1', 'http://forum.thxyfreenet.cn/resources/1528635098.jpg');
INSERT INTO `tb_img` VALUES ('3', '1', 'http://forum.thxyfreenet.cn/resources/1528635118.jpg');
INSERT INTO `tb_img` VALUES ('4', '1', 'http://forum.thxyfreenet.cn/resources/1528635252.jpeg');
INSERT INTO `tb_img` VALUES ('5', '1', 'http://forum.thxyfreenet.cn/resources/1528635782.jpeg');
INSERT INTO `tb_img` VALUES ('6', '1', 'http://forum.thxyfreenet.cn/resources/1528635904.jpeg');
INSERT INTO `tb_img` VALUES ('7', '2', 'http://forum.thxyfreenet.cn/resources/1528635929.jpg');
INSERT INTO `tb_img` VALUES ('8', '1', 'http://forum.thxyfreenet.cn/resources/1528636012.jpeg');
INSERT INTO `tb_img` VALUES ('9', '1', 'http://forum.thxyfreenet.cn/resources/1528636111.jpeg');
INSERT INTO `tb_img` VALUES ('10', '2', 'http://forum.thxyfreenet.cn/resources/1528637755.jpeg');
INSERT INTO `tb_img` VALUES ('11', '2', 'http://forum.thxyfreenet.cn/resources/1528638321.jpeg');
INSERT INTO `tb_img` VALUES ('12', '2', 'http://forum.thxyfreenet.cn/resources/1528638554.jpeg');
INSERT INTO `tb_img` VALUES ('13', '2', 'http://forum.thxyfreenet.cn/resources/1528638627.jpeg');
INSERT INTO `tb_img` VALUES ('14', '2', 'http://forum.thxyfreenet.cn/resources/1528638807.jpg');
INSERT INTO `tb_img` VALUES ('15', '2', 'http://forum.thxyfreenet.cn/resources/1528638875.jpeg');
INSERT INTO `tb_img` VALUES ('16', '2', 'http://forum.thxyfreenet.cn/resources/1528639010.jpeg');
INSERT INTO `tb_img` VALUES ('17', '2', 'http://forum.thxyfreenet.cn/resources/1528639052.jpg');
INSERT INTO `tb_img` VALUES ('18', '2', 'http://forum.thxyfreenet.cn/resources/1528639090.jpeg');
INSERT INTO `tb_img` VALUES ('19', '2', 'http://forum.thxyfreenet.cn/resources/1528639272.jpeg');
INSERT INTO `tb_img` VALUES ('20', '1', 'http://forum.thxyfreenet.cn/resources/1528639347.jpg');
INSERT INTO `tb_img` VALUES ('21', '2', 'http://forum.thxyfreenet.cn/resources/1528639498.jpeg');
INSERT INTO `tb_img` VALUES ('22', '2', 'http://forum.thxyfreenet.cn/resources/1528640896.jpeg');
INSERT INTO `tb_img` VALUES ('23', '2', 'http://forum.thxyfreenet.cn/resources/1528640921.jpg');
INSERT INTO `tb_img` VALUES ('24', '1', 'http://forum.thxyfreenet.cn/resources/1528641266.jpg');
INSERT INTO `tb_img` VALUES ('25', '1', 'http://forum.thxyfreenet.cn/resources/1528641395.jpeg');
INSERT INTO `tb_img` VALUES ('26', '1', 'http://forum.thxyfreenet.cn/resources/1528641408.jpg');
INSERT INTO `tb_img` VALUES ('27', '1', 'http://forum.thxyfreenet.cn/resources/1528646530.jpg');
INSERT INTO `tb_img` VALUES ('28', '1', 'http://forum.thxyfreenet.cn/resources/1528646674.jpg');
INSERT INTO `tb_img` VALUES ('29', '1', 'http://forum.thxyfreenet.cn/resources/1528646786.png');
INSERT INTO `tb_img` VALUES ('30', '1', 'http://forum.thxyfreenet.cn/resources/1528646934.jpg');
INSERT INTO `tb_img` VALUES ('31', '1', 'http://forum.thxyfreenet.cn/resources/1528646946.jpg');
INSERT INTO `tb_img` VALUES ('32', '1', 'http://forum.thxyfreenet.cn/resources/1528646966.jpg');
INSERT INTO `tb_img` VALUES ('33', '1', 'http://forum.thxyfreenet.cn/resources/1528646996.jpg');
INSERT INTO `tb_img` VALUES ('34', '1', 'http://forum.thxyfreenet.cn/resources/1528647109.jpg');
INSERT INTO `tb_img` VALUES ('35', '1', 'http://forum.thxyfreenet.cn/resources/1528647276.jpg');
INSERT INTO `tb_img` VALUES ('36', '1', 'http://forum.thxyfreenet.cn/resources/1528647617.jpg');
INSERT INTO `tb_img` VALUES ('37', '1', 'http://forum.thxyfreenet.cn/resources/1528648200.jpg');
INSERT INTO `tb_img` VALUES ('38', '1', 'http://forum.thxyfreenet.cn/resources/1528648244.jpg');
INSERT INTO `tb_img` VALUES ('39', '1', 'http://forum.thxyfreenet.cn/resources/1528648353.jpg');
INSERT INTO `tb_img` VALUES ('40', '1', 'http://forum.thxyfreenet.cn/resources/1528648744.jpg');
INSERT INTO `tb_img` VALUES ('41', '1', 'http://forum.thxyfreenet.cn/resources/1528648826.jpeg');
INSERT INTO `tb_img` VALUES ('42', '1', 'http://forum.thxyfreenet.cn/resources/1528693075.jpg');
INSERT INTO `tb_img` VALUES ('43', '1', 'http://forum.thxyfreenet.cn/resources/1528693082.jpg');
INSERT INTO `tb_img` VALUES ('44', '2', 'http://forum.thxyfreenet.cn/resources/1528717948.jpg');
INSERT INTO `tb_img` VALUES ('45', '1', 'http://forum.thxyfreenet.cn/resources/1528717992.jpg');
INSERT INTO `tb_img` VALUES ('46', '1', 'http://forum.thxyfreenet.cn/resources/1528718059.jpg');
INSERT INTO `tb_img` VALUES ('47', '1', 'http://forum.thxyfreenet.cn/resources/1528718075.jpg');
INSERT INTO `tb_img` VALUES ('48', '1', 'http://forum.thxyfreenet.cn/resources/1528718083.jpg');
INSERT INTO `tb_img` VALUES ('49', '1', 'http://forum.thxyfreenet.cn/resources/1528718099.jpg');
INSERT INTO `tb_img` VALUES ('50', '2', 'http://forum.thxyfreenet.cn/resources/1528718183.jpg');
INSERT INTO `tb_img` VALUES ('51', '2', 'http://forum.thxyfreenet.cn/resources/1528718648.jpg');
INSERT INTO `tb_img` VALUES ('52', '2', 'http://forum.thxyfreenet.cn/resources/1528718762.jpg');
INSERT INTO `tb_img` VALUES ('53', '2', 'http://forum.thxyfreenet.cn/resources/1528718811.jpg');
INSERT INTO `tb_img` VALUES ('54', '1', 'http://forum.thxyfreenet.cn/resources/1528718867.jpg');
INSERT INTO `tb_img` VALUES ('55', '1', 'http://forum.thxyfreenet.cn/resources/1528718915.jpg');
INSERT INTO `tb_img` VALUES ('56', '2', 'http://forum.thxyfreenet.cn/resources/1528719116.jpg');
INSERT INTO `tb_img` VALUES ('57', '1', 'http://forum.thxyfreenet.cn/resources/1528719139.jpg');
INSERT INTO `tb_img` VALUES ('58', '2', 'http://forum.thxyfreenet.cn/resources/1528719331.jpg');
INSERT INTO `tb_img` VALUES ('59', '1', 'http://forum.thxyfreenet.cn/resources/1528719403.jpg');
INSERT INTO `tb_img` VALUES ('60', '2', 'http://forum.thxyfreenet.cn/resources/1528719449.jpg');
INSERT INTO `tb_img` VALUES ('61', '2', 'http://forum.thxyfreenet.cn/resources/1528719694.jpg');
INSERT INTO `tb_img` VALUES ('62', '1', 'http://forum.thxyfreenet.cn/resources/1528719767.jpg');
INSERT INTO `tb_img` VALUES ('63', '1', 'http://forum.thxyfreenet.cn/resources/1528719822.jpg');
INSERT INTO `tb_img` VALUES ('64', '1', 'http://forum.thxyfreenet.cn/resources/1528719904.jpg');
INSERT INTO `tb_img` VALUES ('65', '1', 'http://forum.thxyfreenet.cn/resources/1528719971.jpg');
INSERT INTO `tb_img` VALUES ('66', '1', 'http://forum.thxyfreenet.cn/resources/1528720037.jpg');
INSERT INTO `tb_img` VALUES ('67', '1', 'http://forum.thxyfreenet.cn/resources/1528720077.jpg');
INSERT INTO `tb_img` VALUES ('68', '1', 'http://forum.thxyfreenet.cn/resources/1528720153.jpg');
INSERT INTO `tb_img` VALUES ('69', '2', 'http://forum.thxyfreenet.cn/resources/1528720268.jpg');
INSERT INTO `tb_img` VALUES ('70', '1', 'http://forum.thxyfreenet.cn/resources/1528720310.jpg');
INSERT INTO `tb_img` VALUES ('71', '1', 'http://forum.thxyfreenet.cn/resources/1528720372.jpg');
INSERT INTO `tb_img` VALUES ('72', '1', 'http://forum.thxyfreenet.cn/resources/1528720491.jpg');
INSERT INTO `tb_img` VALUES ('73', '1', 'http://forum.thxyfreenet.cn/resources/1528720803.jpg');
INSERT INTO `tb_img` VALUES ('74', '2', 'http://forum.thxyfreenet.cn/resources/1528720897.jpg');
INSERT INTO `tb_img` VALUES ('75', '1', 'http://forum.thxyfreenet.cn/resources/1528720917.jpg');
INSERT INTO `tb_img` VALUES ('76', '1', 'http://forum.thxyfreenet.cn/resources/1528720946.jpg');
INSERT INTO `tb_img` VALUES ('77', '1', 'http://forum.thxyfreenet.cn/resources/1528720957.jpg');
INSERT INTO `tb_img` VALUES ('78', '2', 'http://forum.thxyfreenet.cn/resources/1528721121.jpg');
INSERT INTO `tb_img` VALUES ('79', '1', 'http://forum.thxyfreenet.cn/resources/1528721152.jpg');
INSERT INTO `tb_img` VALUES ('80', '1', 'http://forum.thxyfreenet.cn/resources/1528721462_1.jpg');
INSERT INTO `tb_img` VALUES ('81', '1', 'http://forum.thxyfreenet.cn/resources/1528722761_1.jpg');
INSERT INTO `tb_img` VALUES ('82', '1', 'http://forum.thxyfreenet.cn/resources/1528722816_1.png');
INSERT INTO `tb_img` VALUES ('83', '1', 'http://forum.thxyfreenet.cn/resources/1528978765_1.jpg');
INSERT INTO `tb_img` VALUES ('84', '1', 'http://forum.thxyfreenet.cn/resources/1528978815_1.jpg');
INSERT INTO `tb_img` VALUES ('85', '1', 'http://forum.thxyfreenet.cn/resources/1528978830.jpg');
INSERT INTO `tb_img` VALUES ('86', '1', 'http://forum.thxyfreenet.cn/resources/1529416624_1.jpg');
INSERT INTO `tb_img` VALUES ('87', '1', 'http://forum.thxyfreenet.cn/resources/1529416649.jpg');
INSERT INTO `tb_img` VALUES ('88', '26', 'http://localhost/resources/1529742400_26.jpg');
INSERT INTO `tb_img` VALUES ('89', '26', 'http://localhost/resources/1529742441.jpg');
INSERT INTO `tb_img` VALUES ('90', '26', 'http://localhost/resources/1529744535_26.jpg');
INSERT INTO `tb_img` VALUES ('91', '26', 'http://localhost/resources/1529744550_26.jpg');
INSERT INTO `tb_img` VALUES ('92', '26', 'http://localhost/resources/1529744844_26.jpg');
INSERT INTO `tb_img` VALUES ('93', '26', 'http://localhost/resources/1529745057_26.jpg');
INSERT INTO `tb_img` VALUES ('94', '26', 'http://localhost/resources/1529745088_26.jpg');
INSERT INTO `tb_img` VALUES ('95', '26', 'http://localhost/resources/1529745280_26.jpg');

-- ----------------------------
-- Table structure for `tb_keep`
-- ----------------------------
DROP TABLE IF EXISTS `tb_keep`;
CREATE TABLE `tb_keep` (
  `keep_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `postings_id` int(11) NOT NULL,
  PRIMARY KEY (`keep_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tb_keep
-- ----------------------------
INSERT INTO `tb_keep` VALUES ('1', '1', '1');
INSERT INTO `tb_keep` VALUES ('2', '1', '21');
INSERT INTO `tb_keep` VALUES ('3', '1', '20');
INSERT INTO `tb_keep` VALUES ('4', '1', '19');
INSERT INTO `tb_keep` VALUES ('5', '1', '18');
INSERT INTO `tb_keep` VALUES ('6', '1', '17');
INSERT INTO `tb_keep` VALUES ('7', '1', '16');
INSERT INTO `tb_keep` VALUES ('8', '1', '15');
INSERT INTO `tb_keep` VALUES ('9', '1', '14');
INSERT INTO `tb_keep` VALUES ('10', '2', '1');
INSERT INTO `tb_keep` VALUES ('11', '25', '1');

-- ----------------------------
-- Table structure for `tb_postings`
-- ----------------------------
DROP TABLE IF EXISTS `tb_postings`;
CREATE TABLE `tb_postings` (
  `postings_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(30) NOT NULL,
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `is_ban` int(11) NOT NULL DEFAULT '0',
  `reply_count` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`postings_id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tb_postings
-- ----------------------------
INSERT INTO `tb_postings` VALUES ('1', '1', 'Hello World', '1528807221', '1529739801', '0', '31');
INSERT INTO `tb_postings` VALUES ('2', '1', '分页1', '1528808656', '1528808656', '0', '1');
INSERT INTO `tb_postings` VALUES ('3', '1', '分页2', '1528808691', '1528808691', '0', '1');
INSERT INTO `tb_postings` VALUES ('4', '1', '分页3', '1528808730', '1528808730', '0', '1');
INSERT INTO `tb_postings` VALUES ('5', '1', '分页4', '1528808739', '1528808739', '0', '1');
INSERT INTO `tb_postings` VALUES ('6', '1', '分页5', '1528808745', '1528808745', '0', '1');
INSERT INTO `tb_postings` VALUES ('7', '1', '分页6', '1528808750', '1528808750', '0', '1');
INSERT INTO `tb_postings` VALUES ('8', '1', '分页7', '1528808754', '1528812843', '0', '3');
INSERT INTO `tb_postings` VALUES ('9', '1', '分页8', '1528808758', '1528808758', '0', '1');
INSERT INTO `tb_postings` VALUES ('10', '1', '分页9', '1528808763', '1528808763', '0', '1');
INSERT INTO `tb_postings` VALUES ('11', '1', '分页10', '1528808769', '1528808769', '0', '1');
INSERT INTO `tb_postings` VALUES ('12', '1', '分页11', '1528808774', '1528808774', '0', '1');
INSERT INTO `tb_postings` VALUES ('13', '1', '分页12', '1528808778', '1528808778', '0', '1');
INSERT INTO `tb_postings` VALUES ('14', '1', '分页13', '1528808782', '1528808782', '0', '1');
INSERT INTO `tb_postings` VALUES ('15', '1', '分页14', '1528808787', '1528819428', '0', '3');
INSERT INTO `tb_postings` VALUES ('16', '1', '分页15', '1528808791', '1528808791', '0', '1');
INSERT INTO `tb_postings` VALUES ('17', '1', '分页16', '1528808795', '1528808795', '0', '1');
INSERT INTO `tb_postings` VALUES ('18', '1', '分页17', '1528808799', '1528808799', '0', '1');
INSERT INTO `tb_postings` VALUES ('19', '1', '分页18', '1528808804', '1528808804', '0', '1');
INSERT INTO `tb_postings` VALUES ('20', '1', '分页19', '1528808808', '1528808808', '0', '1');
INSERT INTO `tb_postings` VALUES ('21', '1', '分页20', '1528808815', '1528808815', '0', '1');
INSERT INTO `tb_postings` VALUES ('22', '1', 'ddd', '1529400447', '1529400447', '1', '1');

-- ----------------------------
-- Table structure for `tb_reply`
-- ----------------------------
DROP TABLE IF EXISTS `tb_reply`;
CREATE TABLE `tb_reply` (
  `reply_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `floor_id` int(11) NOT NULL,
  `postings_id` int(11) NOT NULL DEFAULT '0',
  `value` text NOT NULL,
  `create_time` int(11) NOT NULL,
  `is_ban` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`reply_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tb_reply
-- ----------------------------
INSERT INTO `tb_reply` VALUES ('1', '1', '2', '1', '[{\"tag\":\"p\",\"children\":[\"再次回复自己\",{\"tag\":\"br\"}]}]', '1528807376', '0');
INSERT INTO `tb_reply` VALUES ('2', '2', '26', '8', '[{\"tag\":\"p\",\"children\":[\"7的意志\",{\"tag\":\"br\"}]}]', '1528812843', '0');
INSERT INTO `tb_reply` VALUES ('3', '2', '24', '15', '[{\"tag\":\"p\",\"children\":[\"在回复\",{\"tag\":\"img\",\"attrs\":[{\"name\":\"src\",\"value\":\"http://img.t.sinajs.cn/t4/appstyle/expression/ext/normal/50/pcmoren_huaixiao_org.png\"},{\"name\":\"alt\",\"value\":\"[坏笑]\"},{\"name\":\"data-w-e\",\"value\":\"1\"},{\"name\":\"style\",\"value\":\"font-size: 14.0625px;\"}]}]}]', '1528819428', '0');
INSERT INTO `tb_reply` VALUES ('4', '2', '38', '1', '[{\"tag\":\"p\",\"children\":[\"回复一个吧\",{\"tag\":\"img\",\"attrs\":[{\"name\":\"src\",\"value\":\"http://img.t.sinajs.cn/t4/appstyle/expression/ext/normal/3c/pcmoren_wu_org.png\"},{\"name\":\"alt\",\"value\":\"[污]\"},{\"name\":\"data-w-e\",\"value\":\"1\"},{\"name\":\"style\",\"value\":\"font-size: 14.0625px;\"}]}]}]', '1528819783', '0');
INSERT INTO `tb_reply` VALUES ('5', '1', '25', '1', '[{\"tag\":\"p\",\"children\":[\"我是fzl\",{\"tag\":\"br\"}]}]', '1528878014', '0');
INSERT INTO `tb_reply` VALUES ('6', '1', '39', '1', '[{\"tag\":\"p\",\"children\":[{\"tag\":\"img\",\"attrs\":[{\"name\":\"src\",\"value\":\"http://img.t.sinajs.cn/t4/appstyle/expression/ext/normal/50/pcmoren_huaixiao_org.png\"},{\"name\":\"alt\",\"value\":\"[坏笑]\"},{\"name\":\"data-w-e\",\"value\":\"1\"}]},{\"tag\":\"br\"}]}]', '1528981029', '0');
INSERT INTO `tb_reply` VALUES ('7', '1', '2', '1', '[{\"tag\":\"p\",\"children\":[\"123\"]},{\"tag\":\"p\",\"children\":[\"232323298\",{\"tag\":\"br\"}]}]', '1529403057', '0');
INSERT INTO `tb_reply` VALUES ('8', '1', '2', '1', '[{\"tag\":\"p\",\"children\":[\"asdfjalskdjfklasdjfaslkdfj\"]},{\"tag\":\"p\",\"children\":[\"asdfasdfasdf\"]},{\"tag\":\"p\",\"children\":[\"sadf\"]},{\"tag\":\"p\",\"children\":[\"asdf\"]},{\"tag\":\"p\",\"children\":[\"sda\"]},{\"tag\":\"p\",\"children\":[\"f\"]},{\"tag\":\"p\",\"children\":[\"sadf\"]},{\"tag\":\"p\",\"children\":[\"sadf\"]},{\"tag\":\"p\",\"children\":[\"sadfsadfasdfs\"]},{\"tag\":\"p\",\"children\":[\"adfsadfasdfsd\",{\"tag\":\"br\"}]}]', '1529403077', '0');
INSERT INTO `tb_reply` VALUES ('9', '2', '2', '1', '[{\"tag\":\"p\",\"children\":[\"dsfsdfsdaf\"]},{\"tag\":\"p\",\"children\":[\"sadfsdafsad\"]},{\"tag\":\"p\",\"children\":[\"sadfasdadsf\"]}]', '1529404770', '0');
INSERT INTO `tb_reply` VALUES ('10', '2', '2', '1', '[{\"tag\":\"p\",\"children\":[\"回复换行\"]},{\"tag\":\"p\",\"children\":[\"我觉得很蠢\"]}]', '1529404797', '0');

-- ----------------------------
-- Table structure for `tb_user`
-- ----------------------------
DROP TABLE IF EXISTS `tb_user`;
CREATE TABLE `tb_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `account` varchar(30) NOT NULL,
  `password` varchar(50) NOT NULL,
  `name` varchar(20) DEFAULT NULL,
  `sign_time` int(11) NOT NULL DEFAULT '0',
  `is_sign` int(1) NOT NULL DEFAULT '0',
  `email` varchar(30) NOT NULL,
  `token_time` int(11) NOT NULL DEFAULT '0',
  `status` int(1) NOT NULL DEFAULT '0',
  `sex` int(1) NOT NULL DEFAULT '0',
  `head_img` varchar(90) DEFAULT '',
  `credits` int(11) NOT NULL DEFAULT '0',
  `level` int(1) NOT NULL DEFAULT '1',
  `token` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `account` (`account`),
  UNIQUE KEY `email` (`email`) USING BTREE,
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tb_user
-- ----------------------------
INSERT INTO `tb_user` VALUES ('1', '505911050@qq.com', '9099e3d26ce489a3b7d6b7c3124773c9', 'fzl', '1529666642', '1', '505911050@qq.com', '1529746098', '1', '1', 'http://forum.thxyfreenet.cn/resources/1529416649.jpg', '193', '3', '172f9f9928e6d5ea7a7106ca0e450241');
INSERT INTO `tb_user` VALUES ('2', '10010@qq.com', '98ead8f38eacfc128b97593a413fb465', '色魔2号', '1529402017', '0', '10010@qq.com', '0', '1', '1', 'http://forum.thxyfreenet.cn/resources/1528719116.jpg', '85', '2', null);
INSERT INTO `tb_user` VALUES ('26', '2281588099@qq.com', '25f9e794323b453885f5181f1b624d0b', 'xxl', '1529738909', '1', '2281588099@qq.com', '1529745970', '1', '1', 'http://localhost/resources/1529742441.jpg', '5', '1', '046cf90c0daf5aebce73cddb63835864');
