/*
Navicat MySQL Data Transfer

Source Server         : 192.168.11.244
Source Server Version : 50528
Source Host           : 192.168.11.244:3306
Source Database       : test

Target Server Type    : MYSQL
Target Server Version : 50528
File Encoding         : 65001

Date: 2017-09-30 15:36:49
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for advertisement
-- ----------------------------
DROP TABLE IF EXISTS `advertisement`;
CREATE TABLE `advertisement` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cover_src` varchar(255) NOT NULL COMMENT '封面图',
  `link` varchar(255) NOT NULL COMMENT '链接',
  `remarks` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `if_del` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1删除',
  `title` varchar(255) NOT NULL,
  `create_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`(250)) USING HASH
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COMMENT='广告表';

-- ----------------------------
-- Table structure for adver_total
-- ----------------------------
DROP TABLE IF EXISTS `adver_total`;
CREATE TABLE `adver_total` (
  `ad_id` int(10) unsigned NOT NULL COMMENT '广告id',
  `click_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '点击数',
  PRIMARY KEY (`ad_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='广告统计';

-- ----------------------------
-- Table structure for article
-- ----------------------------
DROP TABLE IF EXISTS `article`;
CREATE TABLE `article` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(10) unsigned NOT NULL COMMENT '栏目id',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `cover_src` varchar(255) NOT NULL DEFAULT '' COMMENT '封面图',
  `backend_sort` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '后台排序',
  `bind_ad` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '绑定广告id',
  `ding` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0不置顶 1置顶',
  `create_time` datetime NOT NULL,
  `if_del` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COMMENT='文章表';

-- ----------------------------
-- Table structure for article_data
-- ----------------------------
DROP TABLE IF EXISTS `article_data`;
CREATE TABLE `article_data` (
  `article_id` int(10) unsigned NOT NULL,
  `contents` text NOT NULL,
  `create_time` datetime NOT NULL,
  PRIMARY KEY (`article_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='文章内容表';

-- ----------------------------
-- Table structure for article_info
-- ----------------------------
DROP TABLE IF EXISTS `article_info`;
CREATE TABLE `article_info` (
  `article_id` int(10) unsigned NOT NULL COMMENT '文章id',
  `views` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '阅读数',
  `zan` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '点赞数',
  `share_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享数',
  PRIMARY KEY (`article_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for article_recommend
-- ----------------------------
DROP TABLE IF EXISTS `article_recommend`;
CREATE TABLE `article_recommend` (
  `article_id` int(10) unsigned NOT NULL COMMENT '文章id',
  `recommend_time` datetime NOT NULL COMMENT '设置推荐时间',
  PRIMARY KEY (`article_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='推荐文章表';

-- ----------------------------
-- Table structure for article_with_account
-- ----------------------------
DROP TABLE IF EXISTS `article_with_account`;
CREATE TABLE `article_with_account` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `article_id` int(10) unsigned NOT NULL COMMENT '文章id',
  `aname` varchar(40) NOT NULL COMMENT '账号名称',
  `url` varchar(255) NOT NULL COMMENT '关注链接URL',
  `if_del` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1删除',
  PRIMARY KEY (`id`),
  KEY `article_id` (`article_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COMMENT='文章关联账号';

-- ----------------------------
-- Table structure for category
-- ----------------------------
DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父级id',
  `title` char(24) NOT NULL COMMENT '栏目标题',
  `front_sort` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;
