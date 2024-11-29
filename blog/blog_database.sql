/*
 Navicat Premium Data Transfer

 Source Server         : root
 Source Server Type    : MySQL
 Source Server Version : 80031 (8.0.31)
 Source Host           : localhost:3306
 Source Schema         : blog_database

 Target Server Type    : MySQL
 Target Server Version : 80031 (8.0.31)
 File Encoding         : 65001

 Date: 29/11/2024 16:06:28
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for blog_content
-- ----------------------------
DROP TABLE IF EXISTS `blog_content`;
CREATE TABLE `blog_content`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `time` datetime NULL DEFAULT NULL,
  `user_id` int UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fk_user_id`(`user_id` ASC) USING BTREE,
  CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 24 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of blog_content
-- ----------------------------
INSERT INTO `blog_content` VALUES (12, 'ascojabak\r\n', '2024-11-23 09:37:19', NULL);
INSERT INTO `blog_content` VALUES (13, 'asbxjnkanxcbnma', '2024-11-23 09:37:25', NULL);
INSERT INTO `blog_content` VALUES (16, '啊啊啊啊啊啊啊啊', '2024-11-25 09:39:59', NULL);
INSERT INTO `blog_content` VALUES (17, '啵啵啵啵啵啵啵啵啵宝宝', '2024-11-25 09:40:06', NULL);
INSERT INTO `blog_content` VALUES (18, '啊啊啊啊啊啊啊啊11111111', '2024-11-25 09:59:38', NULL);
INSERT INTO `blog_content` VALUES (19, '5555555555555555555555555555555555', '2024-11-25 10:00:06', NULL);
INSERT INTO `blog_content` VALUES (20, 'iniu99999', '2024-11-25 15:29:54', NULL);
INSERT INTO `blog_content` VALUES (21, '牛逼，苟子', '2024-11-25 15:32:18', NULL);
INSERT INTO `blog_content` VALUES (22, 'aaaaaaa', '2024-11-25 16:42:47', 30);
INSERT INTO `blog_content` VALUES (23, '234563442', '2024-11-29 15:32:06', 30);

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '这是自动增长的主键',
  `username` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `loginTime` datetime NULL DEFAULT NULL,
  `security_question` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `security_answer` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 36 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES (30, 'admin', '123', '2024-11-29 15:20:44', '你出生的城市是哪个？', '宝鸡');
INSERT INTO `user` VALUES (31, 'nzy', 'Bz20011020.', '2024-11-25 16:46:09', 'first_pet_name', 'a');
INSERT INTO `user` VALUES (32, 'nb', 'BZZZ111222.', '2024-11-25 16:49:00', 'mother_maiden_name', 'bai');
INSERT INTO `user` VALUES (33, 'nbb', 'Bz20011020.', '2024-11-25 16:49:37', 'mother_maiden_name', 'bai');
INSERT INTO `user` VALUES (34, 'admin_', 'Bz20011020.', '2024-11-25 19:24:07', 'birth_city', 'baoji');
INSERT INTO `user` VALUES (35, 'iniu', 'Bz20011020.', '2024-11-29 15:33:46', 'mother_maiden_name', 'bai');

SET FOREIGN_KEY_CHECKS = 1;
