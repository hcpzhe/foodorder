/*
Navicat MySQL Data Transfer

Source Server         : 127.0.0.1
Source Server Version : 50524
Source Host           : localhost:3306
Source Database       : foodorder

Target Server Type    : MYSQL
Target Server Version : 50524
File Encoding         : 65001

Date: 2014-09-23 18:04:11
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `fdo_attr`
-- ----------------------------
DROP TABLE IF EXISTS `fdo_attr`;
CREATE TABLE `fdo_attr` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `attr_name` varchar(255) NOT NULL COMMENT '属性名称',
  `sort` smallint(5) unsigned NOT NULL DEFAULT '255',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '-1删除 0禁用 1正常',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='筛选属性表';

-- ----------------------------
-- Records of fdo_attr
-- ----------------------------

-- ----------------------------
-- Table structure for `fdo_attr_val`
-- ----------------------------
DROP TABLE IF EXISTS `fdo_attr_val`;
CREATE TABLE `fdo_attr_val` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '属性值ID',
  `attr_id` int(10) unsigned NOT NULL COMMENT '所属属性ID',
  `attr_val` varchar(255) NOT NULL COMMENT '属性值',
  `sort` smallint(5) unsigned NOT NULL DEFAULT '255' COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '-1删除 0禁用 1正常',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='筛选属性可选值表;  `筛选属性表`一对多的关系';

-- ----------------------------
-- Records of fdo_attr_val
-- ----------------------------

-- ----------------------------
-- Table structure for `fdo_cart`
-- ----------------------------
DROP TABLE IF EXISTS `fdo_cart`;
CREATE TABLE `fdo_cart` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `member_id` char(32) NOT NULL COMMENT '用户ID',
  `store_id` int(10) unsigned NOT NULL COMMENT '店铺ID 标识哪个店铺的订单',
  `goods_id` int(10) unsigned NOT NULL COMMENT '商品ID',
  `goods_name` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '单价',
  `quantity` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '数量',
  PRIMARY KEY (`id`),
  KEY `member_id` (`member_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of fdo_cart
-- ----------------------------
INSERT INTO `fdo_cart` VALUES ('1', '3', '2', '20', '小脚牛仔铅笔裤', '129.00', '1');
INSERT INTO `fdo_cart` VALUES ('2', '0', '2', '17', '韩E族百搭修身紧腰休闲长裤【灰色】', '90.00', '1');
INSERT INTO `fdo_cart` VALUES ('3', '0', '2', '19', '罗衣OL气质真丝雪纺百褶裙针织背心裙', '170.00', '1');

-- ----------------------------
-- Table structure for `fdo_category`
-- ----------------------------
DROP TABLE IF EXISTS `fdo_category`;
CREATE TABLE `fdo_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cate_name` varchar(128) NOT NULL COMMENT '分类名称',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父ID',
  `store_id` int(10) unsigned NOT NULL COMMENT '所属店铺ID',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '255',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='店铺内商品分类';

-- ----------------------------
-- Records of fdo_category
-- ----------------------------

-- ----------------------------
-- Table structure for `fdo_config`
-- ----------------------------
DROP TABLE IF EXISTS `fdo_config`;
CREATE TABLE `fdo_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '配置ID',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '配置名称',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配置类型',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '配置说明',
  `group` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配置分组',
  `extra` varchar(255) NOT NULL DEFAULT '' COMMENT '配置值',
  `remark` varchar(100) NOT NULL COMMENT '配置说明',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  `value` text NOT NULL COMMENT '配置值',
  `sort` smallint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name` (`name`),
  KEY `type` (`type`),
  KEY `group` (`group`)
) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of fdo_config
-- ----------------------------
INSERT INTO `fdo_config` VALUES ('1', 'WEB_SITE_TITLE', '1', '网站标题', '1', '', '网站标题前台显示标题', '1378898976', '1379235274', '1', 'OneThink内容管理框架', '0');
INSERT INTO `fdo_config` VALUES ('2', 'WEB_SITE_DESCRIPTION', '2', '网站描述', '1', '', '网站搜索引擎描述', '1378898976', '1379235841', '1', 'OneThink内容管理框架', '1');
INSERT INTO `fdo_config` VALUES ('3', 'WEB_SITE_KEYWORD', '2', '网站关键字', '1', '', '网站搜索引擎关键字', '1378898976', '1381390100', '1', 'ThinkPHP,OneThink', '8');
INSERT INTO `fdo_config` VALUES ('4', 'WEB_SITE_CLOSE', '4', '关闭站点', '1', '0:关闭,1:开启', '站点关闭后其他用户不能访问，管理员可以正常访问', '1378898976', '1379235296', '1', '1', '1');
INSERT INTO `fdo_config` VALUES ('9', 'CONFIG_TYPE_LIST', '3', '配置类型列表', '4', '', '主要用于数据解析和页面表单的生成', '1378898976', '1379235348', '1', '0:数字\r\n1:字符\r\n2:文本\r\n3:数组\r\n4:枚举', '2');
INSERT INTO `fdo_config` VALUES ('10', 'WEB_SITE_ICP', '1', '网站备案号', '1', '', '设置在网站底部显示的备案号，如“沪ICP备12007941号-2', '1378900335', '1379235859', '1', '', '9');
INSERT INTO `fdo_config` VALUES ('11', 'DOCUMENT_POSITION', '3', '文档推荐位', '2', '', '文档推荐位，推荐到多个位置KEY值相加即可', '1379053380', '1379235329', '1', '1:列表页推荐\r\n2:频道页推荐\r\n4:网站首页推荐', '3');
INSERT INTO `fdo_config` VALUES ('12', 'DOCUMENT_DISPLAY', '3', '文档可见性', '2', '', '文章可见性仅影响前台显示，后台不收影响', '1379056370', '1379235322', '1', '0:所有人可见\r\n1:仅注册会员可见\r\n2:仅管理员可见', '4');
INSERT INTO `fdo_config` VALUES ('13', 'COLOR_STYLE', '4', '后台色系', '1', 'default_color:默认\r\nblue_color:紫罗兰', '后台颜色风格', '1379122533', '1379235904', '1', 'default_color', '10');
INSERT INTO `fdo_config` VALUES ('20', 'CONFIG_GROUP_LIST', '3', '配置分组', '4', '', '配置分组', '1379228036', '1384418383', '1', '1:基本\r\n2:内容\r\n3:用户\r\n4:系统', '4');
INSERT INTO `fdo_config` VALUES ('21', 'HOOKS_TYPE', '3', '钩子的类型', '4', '', '类型 1-用于扩展显示内容，2-用于扩展业务处理', '1379313397', '1379313407', '1', '1:视图\r\n2:控制器', '6');
INSERT INTO `fdo_config` VALUES ('23', 'OPEN_DRAFTBOX', '4', '是否开启草稿功能', '2', '0:关闭草稿功能\r\n1:开启草稿功能\r\n', '新增文章时的草稿功能配置', '1379484332', '1379484591', '1', '1', '1');
INSERT INTO `fdo_config` VALUES ('24', 'DRAFT_AOTOSAVE_INTERVAL', '0', '自动保存草稿时间', '2', '', '自动保存草稿的时间间隔，单位：秒', '1379484574', '1386143323', '1', '60', '2');
INSERT INTO `fdo_config` VALUES ('25', 'LIST_ROWS', '0', '后台每页记录数', '2', '', '后台数据每页显示记录数', '1379503896', '1380427745', '1', '10', '10');
INSERT INTO `fdo_config` VALUES ('26', 'USER_ALLOW_REGISTER', '4', '是否允许用户注册', '3', '0:关闭注册\r\n1:允许注册', '是否开放用户注册', '1379504487', '1379504580', '1', '1', '3');
INSERT INTO `fdo_config` VALUES ('27', 'CODEMIRROR_THEME', '4', '预览插件的CodeMirror主题', '4', '3024-day:3024 day\r\n3024-night:3024 night\r\nambiance:ambiance\r\nbase16-dark:base16 dark\r\nbase16-light:base16 light\r\nblackboard:blackboard\r\ncobalt:cobalt\r\neclipse:eclipse\r\nelegant:elegant\r\nerlang-dark:erlang-dark\r\nlesser-dark:lesser-dark\r\nmidnight:midnight', '详情见CodeMirror官网', '1379814385', '1384740813', '1', 'ambiance', '3');
INSERT INTO `fdo_config` VALUES ('28', 'DATA_BACKUP_PATH', '1', '数据库备份根路径', '4', '', '路径必须以 / 结尾', '1381482411', '1381482411', '1', './Data/', '5');
INSERT INTO `fdo_config` VALUES ('29', 'DATA_BACKUP_PART_SIZE', '0', '数据库备份卷大小', '4', '', '该值用于限制压缩后的分卷最大长度。单位：B；建议设置20M', '1381482488', '1381729564', '1', '20971520', '7');
INSERT INTO `fdo_config` VALUES ('30', 'DATA_BACKUP_COMPRESS', '4', '数据库备份文件是否启用压缩', '4', '0:不压缩\r\n1:启用压缩', '压缩备份文件需要PHP环境支持gzopen,gzwrite函数', '1381713345', '1381729544', '1', '1', '9');
INSERT INTO `fdo_config` VALUES ('31', 'DATA_BACKUP_COMPRESS_LEVEL', '4', '数据库备份文件压缩级别', '4', '1:普通\r\n4:一般\r\n9:最高', '数据库备份文件的压缩级别，该配置在开启压缩时生效', '1381713408', '1381713408', '1', '9', '10');
INSERT INTO `fdo_config` VALUES ('32', 'DEVELOP_MODE', '4', '开启开发者模式', '4', '0:关闭\r\n1:开启', '是否开启开发者模式', '1383105995', '1383291877', '1', '1', '11');
INSERT INTO `fdo_config` VALUES ('33', 'ALLOW_VISIT', '3', '不受限控制器方法', '0', '', '', '1386644047', '1386644741', '1', '0:article/draftbox\r\n1:article/mydocument\r\n2:Category/tree\r\n3:Index/verify\r\n4:file/upload\r\n5:file/download\r\n6:user/updatePassword\r\n7:user/updateNickname\r\n8:user/submitPassword\r\n9:user/submitNickname\r\n10:file/uploadpicture', '0');
INSERT INTO `fdo_config` VALUES ('34', 'DENY_VISIT', '3', '超管专限控制器方法', '0', '', '仅超级管理员可访问的控制器方法', '1386644141', '1386644659', '1', '0:Addons/addhook\r\n1:Addons/edithook\r\n2:Addons/delhook\r\n3:Addons/updateHook\r\n4:Admin/getMenus\r\n5:Admin/recordList\r\n6:AuthManager/updateRules\r\n7:AuthManager/tree', '0');
INSERT INTO `fdo_config` VALUES ('35', 'REPLY_LIST_ROWS', '0', '回复列表每页条数', '2', '', '', '1386645376', '1387178083', '1', '10', '0');
INSERT INTO `fdo_config` VALUES ('36', 'ADMIN_ALLOW_IP', '2', '后台允许访问IP', '4', '', '多个用逗号分隔，如果不配置表示不限制IP访问', '1387165454', '1387165553', '1', '', '12');
INSERT INTO `fdo_config` VALUES ('37', 'SHOW_PAGE_TRACE', '4', '是否显示页面Trace', '4', '0:关闭\r\n1:开启', '是否显示页面Trace信息', '1387165685', '1387165685', '1', '1', '1');

-- ----------------------------
-- Table structure for `fdo_goods`
-- ----------------------------
DROP TABLE IF EXISTS `fdo_goods`;
CREATE TABLE `fdo_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cate_id` int(10) unsigned NOT NULL COMMENT '所属分类ID',
  `goods_name` varchar(128) NOT NULL COMMENT '商品名称',
  `image` varchar(255) DEFAULT NULL COMMENT '商品图片',
  `price` decimal(10,0) unsigned NOT NULL DEFAULT '0' COMMENT '单价',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '255' COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '-1删除 0-禁用 1-正常',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品表';

-- ----------------------------
-- Records of fdo_goods
-- ----------------------------

-- ----------------------------
-- Table structure for `fdo_member`
-- ----------------------------
DROP TABLE IF EXISTS `fdo_member`;
CREATE TABLE `fdo_member` (
  `id` char(32) NOT NULL COMMENT '使用unqid函数生成,md5处理',
  `account` varchar(32) DEFAULT NULL COMMENT '帐号',
  `password` char(32) DEFAULT NULL COMMENT '密码',
  `reg_time` int(10) unsigned DEFAULT '0' COMMENT '注册时间',
  `last_login` int(10) unsigned DEFAULT '0' COMMENT '上次登录时间',
  `last_ip` varchar(32) DEFAULT NULL COMMENT '上次登录IP',
  `logins` int(10) unsigned DEFAULT '0' COMMENT '登录次数',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '-1删除 0禁用 1正常',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='普通会员表; 与localstorge同步';

-- ----------------------------
-- Records of fdo_member
-- ----------------------------

-- ----------------------------
-- Table structure for `fdo_order`
-- ----------------------------
DROP TABLE IF EXISTS `fdo_order`;
CREATE TABLE `fdo_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '订单ID',
  `order_sn` varchar(32) NOT NULL DEFAULT '' COMMENT '订单编号',
  `sotre_id` int(10) unsigned NOT NULL COMMENT '所属店铺ID',
  `member_id` char(32) NOT NULL COMMENT '购买用户编号',
  `buyer_name` varchar(32) NOT NULL COMMENT '购买者姓名',
  `address` varchar(255) NOT NULL COMMENT '地址',
  `phone` varchar(32) NOT NULL COMMENT '电话',
  `order_note` text COMMENT '用户订单留言',
  `amount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '合计总金额',
  `payment_id` int(10) unsigned NOT NULL COMMENT '支付方式ID',
  `add_time` int(10) unsigned NOT NULL COMMENT '订单创建时间',
  `update_time` int(10) unsigned NOT NULL COMMENT '订单更新时间',
  `pay_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '付款时间',
  `pay_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '支付状态 0-否 1-是',
  `phone_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '手机验证状态 0-否 1-是',
  `store_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '店铺接受状态 0-否 1-是',
  `ship_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '配送状态 0-未开始 1-配送中',
  `confirm` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '客户收货确认 0-否 1-是',
  `ship_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '发货时间',
  `confirm_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '客户收货确认时间',
  `confirm_expired` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '确认收货截止时间',
  `unreceived` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '用户未收货申请1-是,0-否;必须在收货截止时间内才能申请',
  `finish_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '订单完成时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '-1删除 0禁用 1正常',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单表';

-- ----------------------------
-- Records of fdo_order
-- ----------------------------

-- ----------------------------
-- Table structure for `fdo_order_goods`
-- ----------------------------
DROP TABLE IF EXISTS `fdo_order_goods`;
CREATE TABLE `fdo_order_goods` (
  `order_id` int(10) unsigned NOT NULL COMMENT '所属订单ID',
  `goods_id` int(10) unsigned NOT NULL COMMENT '所属商品ID',
  `quantity` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '数量',
  `price` decimal(10,2) NOT NULL COMMENT '单价',
  `amount` decimal(10,2) NOT NULL COMMENT '总价',
  PRIMARY KEY (`order_id`,`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of fdo_order_goods
-- ----------------------------

-- ----------------------------
-- Table structure for `fdo_payment`
-- ----------------------------
DROP TABLE IF EXISTS `fdo_payment`;
CREATE TABLE `fdo_payment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'payment_id',
  `pay_code` varchar(20) NOT NULL COMMENT '支付代码; 用于连接后台API',
  `pay_name` varchar(255) NOT NULL COMMENT '支付方式名称',
  `pay_desc` text COMMENT '支付简介',
  `pay_config` text COMMENT '支付配置信息',
  `is_phone` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要验证手机号码 0-否 1-是',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '255' COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '-1删除 0禁用 1正常',
  PRIMARY KEY (`id`),
  UNIQUE KEY `pay_code` (`pay_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='支付方式';

-- ----------------------------
-- Records of fdo_payment
-- ----------------------------

-- ----------------------------
-- Table structure for `fdo_phonecode`
-- ----------------------------
DROP TABLE IF EXISTS `fdo_phonecode`;
CREATE TABLE `fdo_phonecode` (
  `phone` varchar(255) NOT NULL COMMENT '手机号码',
  `code` char(4) NOT NULL COMMENT '验证码',
  `send_time` int(10) unsigned NOT NULL COMMENT '发送时间',
  `expired_time` int(10) unsigned NOT NULL COMMENT '验证码过期时间',
  `class` varchar(12) NOT NULL COMMENT '验证码类型 order;register;'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='手机验证码记录表';

-- ----------------------------
-- Records of fdo_phonecode
-- ----------------------------

-- ----------------------------
-- Table structure for `fdo_store`
-- ----------------------------
DROP TABLE IF EXISTS `fdo_store`;
CREATE TABLE `fdo_store` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `account` varchar(32) NOT NULL COMMENT '帐号',
  `password` char(32) NOT NULL COMMENT '密码',
  `store_name` varchar(100) NOT NULL COMMENT '店铺名',
  `owner_name` varchar(60) DEFAULT NULL COMMENT '店主姓名',
  `owner_card` varchar(60) DEFAULT NULL COMMENT '身份证号码',
  `address` varchar(255) DEFAULT NULL COMMENT '地铺地址',
  `tel` varchar(60) DEFAULT NULL COMMENT '联系电话',
  `balance` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '店铺余额',
  `rating` decimal(5,1) unsigned NOT NULL DEFAULT '0.0' COMMENT '评价分数; 1-5分; 小于1为无评价',
  `min_send` decimal(10,0) unsigned NOT NULL DEFAULT '0' COMMENT '每单最低起送价',
  `is_recom` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '推荐 1-是  0-否',
  `store_logo` varchar(255) DEFAULT NULL COMMENT '店铺LOGO',
  `description` text COMMENT '店铺简介',
  `im_qq` varchar(60) DEFAULT NULL COMMENT 'QQ',
  `im_ww` varchar(60) DEFAULT NULL COMMENT '旺旺',
  `last_login` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上次登录时间',
  `last_ip` varchar(32) DEFAULT NULL COMMENT '上次登录IP',
  `logins` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '登录次数',
  `sort` smallint(5) unsigned NOT NULL DEFAULT '255' COMMENT '排序',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '入驻时间',
  `end_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '到期时间 0-永不到期',
  `is_close` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否暂停营业 0-否 1-是',
  `close_reason` varchar(255) NOT NULL DEFAULT '' COMMENT '暂停营业原因',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '-1删除 0-禁用 1-正常',
  PRIMARY KEY (`id`),
  KEY `store_name` (`store_name`),
  KEY `account` (`account`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of fdo_store
-- ----------------------------
INSERT INTO `fdo_store` VALUES ('2', '', '', '演示店铺', '张老板', '123456789012345678', '人民大街16号', '010-88886666-8866', '6.00', '85.7', '0', '1', null, '', '', '', '0', null, '0', '0', '1249543819', '0', '0', '', '1');

-- ----------------------------
-- Table structure for `fdo_store_attr`
-- ----------------------------
DROP TABLE IF EXISTS `fdo_store_attr`;
CREATE TABLE `fdo_store_attr` (
  `store_id` int(10) unsigned NOT NULL COMMENT '店铺ID',
  `attr_val_id` int(10) unsigned NOT NULL COMMENT '属性值ID',
  PRIMARY KEY (`store_id`,`attr_val_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='店铺 属性值 关系表; 用于店铺列表筛选;';

-- ----------------------------
-- Records of fdo_store_attr
-- ----------------------------
INSERT INTO `fdo_store_attr` VALUES ('1', '1');
INSERT INTO `fdo_store_attr` VALUES ('1', '2');
INSERT INTO `fdo_store_attr` VALUES ('2', '2');
