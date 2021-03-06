/*
Navicat MySQL Data Transfer

Source Server         : 127.0.0.1
Source Server Version : 50524
Source Host           : localhost:3306
Source Database       : foodorder

Target Server Type    : MYSQL
Target Server Version : 50524
File Encoding         : 65001

Date: 2015-01-09 17:23:40
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='筛选属性表';

-- ----------------------------
-- Records of fdo_attr
-- ----------------------------
INSERT INTO `fdo_attr` VALUES ('1', '美食分类', '255', '1');
INSERT INTO `fdo_attr` VALUES ('2', '区域', '255', '1');
INSERT INTO `fdo_attr` VALUES ('3', '测试类别', '255', '1');

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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='筛选属性可选值表;  `筛选属性表`一对多的关系';

-- ----------------------------
-- Records of fdo_attr_val
-- ----------------------------
INSERT INTO `fdo_attr_val` VALUES ('1', '1', '面条米线', '255', '1');
INSERT INTO `fdo_attr_val` VALUES ('2', '1', '鲜果', '255', '1');
INSERT INTO `fdo_attr_val` VALUES ('3', '1', '快餐', '255', '1');
INSERT INTO `fdo_attr_val` VALUES ('4', '1', '休闲零食', '255', '1');
INSERT INTO `fdo_attr_val` VALUES ('5', '1', '蛋糕甜点', '255', '1');
INSERT INTO `fdo_attr_val` VALUES ('6', '2', '涧西', '255', '1');
INSERT INTO `fdo_attr_val` VALUES ('7', '2', '西工', '255', '1');
INSERT INTO `fdo_attr_val` VALUES ('8', '2', '老城', '255', '1');
INSERT INTO `fdo_attr_val` VALUES ('9', '2', '洛龙区', '255', '0');
INSERT INTO `fdo_attr_val` VALUES ('10', '3', 'A', '255', '1');
INSERT INTO `fdo_attr_val` VALUES ('11', '3', 'B', '255', '1');
INSERT INTO `fdo_attr_val` VALUES ('12', '1', '饮品', '255', '1');
INSERT INTO `fdo_attr_val` VALUES ('13', '1', '养生粥', '255', '1');

-- ----------------------------
-- Table structure for `fdo_auth_group`
-- ----------------------------
DROP TABLE IF EXISTS `fdo_auth_group`;
CREATE TABLE `fdo_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户组id,自增主键',
  `module` varchar(20) NOT NULL COMMENT '用户组所属模块',
  `type` tinyint(4) NOT NULL COMMENT '组类型',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '用户组中文名称',
  `description` varchar(80) NOT NULL DEFAULT '' COMMENT '描述信息',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '用户组状态：为1正常，为0禁用,-1为删除',
  `rules` varchar(500) NOT NULL DEFAULT '' COMMENT '用户组拥有的规则id，多个规则 , 隔开',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of fdo_auth_group
-- ----------------------------
INSERT INTO `fdo_auth_group` VALUES ('1', 'admin', '1', '默认用户组', '', '1', '1,2,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,79,80,81,82,83,84,86,87,88,89,90,91,92,93,94,95,96,97,100,102,103,105,106');
INSERT INTO `fdo_auth_group` VALUES ('2', 'admin', '1', '测试用户', '测试用户', '1', '1,2,5,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,79,80,82,83,84,88,89,90,91,92,93,96,97,100,102,103,195');

-- ----------------------------
-- Table structure for `fdo_auth_group_access`
-- ----------------------------
DROP TABLE IF EXISTS `fdo_auth_group_access`;
CREATE TABLE `fdo_auth_group_access` (
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `group_id` mediumint(8) unsigned NOT NULL COMMENT '用户组id',
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of fdo_auth_group_access
-- ----------------------------
INSERT INTO `fdo_auth_group_access` VALUES ('2', '1');

-- ----------------------------
-- Table structure for `fdo_auth_rule`
-- ----------------------------
DROP TABLE IF EXISTS `fdo_auth_rule`;
CREATE TABLE `fdo_auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '规则id,自增主键',
  `module` varchar(20) NOT NULL COMMENT '规则所属module',
  `type` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1-url;2-主菜单',
  `name` char(80) NOT NULL DEFAULT '' COMMENT '规则唯一英文标识',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '规则中文描述',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否有效(0:无效,1:有效)',
  `condition` varchar(300) NOT NULL DEFAULT '' COMMENT '规则附加条件',
  PRIMARY KEY (`id`),
  KEY `module` (`module`,`status`,`type`)
) ENGINE=MyISAM AUTO_INCREMENT=217 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of fdo_auth_rule
-- ----------------------------
INSERT INTO `fdo_auth_rule` VALUES ('1', 'admin', '2', 'Admin/Index/index', '首页', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('2', 'admin', '2', 'Admin/Article/mydocument', '内容', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('3', 'admin', '2', 'Admin/User/index', '用户', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('4', 'admin', '2', 'Admin/Addons/index', '扩展', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('5', 'admin', '2', 'Admin/Config/group', '系统', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('7', 'admin', '1', 'Admin/article/add', '新增', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('8', 'admin', '1', 'Admin/article/edit', '编辑', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('9', 'admin', '1', 'Admin/article/setStatus', '改变状态', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('10', 'admin', '1', 'Admin/article/update', '保存', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('11', 'admin', '1', 'Admin/article/autoSave', '保存草稿', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('12', 'admin', '1', 'Admin/article/move', '移动', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('13', 'admin', '1', 'Admin/article/copy', '复制', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('14', 'admin', '1', 'Admin/article/paste', '粘贴', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('15', 'admin', '1', 'Admin/article/permit', '还原', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('16', 'admin', '1', 'Admin/article/clear', '清空', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('17', 'admin', '1', 'Admin/article/index', '文档列表', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('18', 'admin', '1', 'Admin/article/recycle', '回收站', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('19', 'admin', '1', 'Admin/User/addaction', '新增用户行为', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('20', 'admin', '1', 'Admin/User/editaction', '编辑用户行为', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('21', 'admin', '1', 'Admin/User/saveAction', '保存用户行为', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('22', 'admin', '1', 'Admin/User/setStatus', '变更行为状态', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('23', 'admin', '1', 'Admin/User/changeStatus?method=forbidUser', '禁用会员', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('24', 'admin', '1', 'Admin/User/changeStatus?method=resumeUser', '启用会员', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('25', 'admin', '1', 'Admin/User/changeStatus?method=deleteUser', '删除会员', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('26', 'admin', '1', 'Admin/User/index', '用户信息', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('27', 'admin', '1', 'Admin/User/action', '用户行为', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('28', 'admin', '1', 'Admin/AuthManager/changeStatus?method=deleteGroup', '删除', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('29', 'admin', '1', 'Admin/AuthManager/changeStatus?method=forbidGroup', '禁用', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('30', 'admin', '1', 'Admin/AuthManager/changeStatus?method=resumeGroup', '恢复', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('31', 'admin', '1', 'Admin/AuthManager/createGroup', '新增', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('32', 'admin', '1', 'Admin/AuthManager/editGroup', '编辑', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('33', 'admin', '1', 'Admin/AuthManager/writeGroup', '保存用户组', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('34', 'admin', '1', 'Admin/AuthManager/group', '授权', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('35', 'admin', '1', 'Admin/AuthManager/access', '访问授权', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('36', 'admin', '1', 'Admin/AuthManager/user', '成员授权', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('37', 'admin', '1', 'Admin/AuthManager/removeFromGroup', '解除授权', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('38', 'admin', '1', 'Admin/AuthManager/addToGroup', '保存成员授权', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('39', 'admin', '1', 'Admin/AuthManager/category', '分类授权', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('40', 'admin', '1', 'Admin/AuthManager/addToCategory', '保存分类授权', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('41', 'admin', '1', 'Admin/AuthManager/index', '权限管理', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('42', 'admin', '1', 'Admin/Addons/create', '创建', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('43', 'admin', '1', 'Admin/Addons/checkForm', '检测创建', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('44', 'admin', '1', 'Admin/Addons/preview', '预览', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('45', 'admin', '1', 'Admin/Addons/build', '快速生成插件', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('46', 'admin', '1', 'Admin/Addons/config', '设置', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('47', 'admin', '1', 'Admin/Addons/disable', '禁用', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('48', 'admin', '1', 'Admin/Addons/enable', '启用', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('49', 'admin', '1', 'Admin/Addons/install', '安装', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('50', 'admin', '1', 'Admin/Addons/uninstall', '卸载', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('51', 'admin', '1', 'Admin/Addons/saveconfig', '更新配置', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('52', 'admin', '1', 'Admin/Addons/adminList', '插件后台列表', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('53', 'admin', '1', 'Admin/Addons/execute', 'URL方式访问插件', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('54', 'admin', '1', 'Admin/Addons/index', '插件管理', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('55', 'admin', '1', 'Admin/Addons/hooks', '钩子管理', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('56', 'admin', '1', 'Admin/model/add', '新增', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('57', 'admin', '1', 'Admin/model/edit', '编辑', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('58', 'admin', '1', 'Admin/model/setStatus', '改变状态', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('59', 'admin', '1', 'Admin/model/update', '保存数据', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('60', 'admin', '1', 'Admin/Model/index', '模型管理', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('61', 'admin', '1', 'Admin/Config/edit', '编辑', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('62', 'admin', '1', 'Admin/Config/del', '删除', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('63', 'admin', '1', 'Admin/Config/add', '新增', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('64', 'admin', '1', 'Admin/Config/save', '保存', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('65', 'admin', '1', 'Admin/Config/group', '网站设置', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('66', 'admin', '1', 'Admin/Config/index', '配置管理', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('67', 'admin', '1', 'Admin/Channel/add', '新增', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('68', 'admin', '1', 'Admin/Channel/edit', '编辑', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('69', 'admin', '1', 'Admin/Channel/del', '删除', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('70', 'admin', '1', 'Admin/Channel/index', '导航管理', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('71', 'admin', '1', 'Admin/Category/edit', '编辑', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('72', 'admin', '1', 'Admin/Category/add', '新增', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('73', 'admin', '1', 'Admin/Category/remove', '删除', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('74', 'admin', '1', 'Admin/Category/index', '分类管理', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('75', 'admin', '1', 'Admin/file/upload', '上传控件', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('76', 'admin', '1', 'Admin/file/uploadPicture', '上传图片', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('77', 'admin', '1', 'Admin/file/download', '下载', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('94', 'admin', '1', 'Admin/AuthManager/modelauth', '模型授权', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('79', 'admin', '1', 'Admin/article/batchOperate', '导入', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('80', 'admin', '1', 'Admin/Database/index?type=export', '备份数据库', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('81', 'admin', '1', 'Admin/Database/index?type=import', '还原数据库', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('82', 'admin', '1', 'Admin/Database/export', '备份', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('83', 'admin', '1', 'Admin/Database/optimize', '优化表', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('84', 'admin', '1', 'Admin/Database/repair', '修复表', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('86', 'admin', '1', 'Admin/Database/import', '恢复', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('87', 'admin', '1', 'Admin/Database/del', '删除', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('88', 'admin', '1', 'Admin/User/add', '新增用户', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('89', 'admin', '1', 'Admin/Attribute/index', '属性管理', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('90', 'admin', '1', 'Admin/Attribute/add', '新增', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('91', 'admin', '1', 'Admin/Attribute/edit', '编辑', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('92', 'admin', '1', 'Admin/Attribute/setStatus', '改变状态', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('93', 'admin', '1', 'Admin/Attribute/update', '保存数据', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('95', 'admin', '1', 'Admin/AuthManager/addToModel', '保存模型授权', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('96', 'admin', '1', 'Admin/Category/move', '移动', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('97', 'admin', '1', 'Admin/Category/merge', '合并', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('98', 'admin', '1', 'Admin/Config/menu', '后台菜单管理', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('99', 'admin', '1', 'Admin/Article/mydocument', '内容', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('100', 'admin', '1', 'Admin/Menu/index', '菜单管理', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('101', 'admin', '1', 'Admin/other', '其他', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('102', 'admin', '1', 'Admin/Menu/add', '新增', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('103', 'admin', '1', 'Admin/Menu/edit', '编辑', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('104', 'admin', '1', 'Admin/Think/lists?model=article', '文章管理', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('105', 'admin', '1', 'Admin/Think/lists?model=download', '下载管理', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('106', 'admin', '1', 'Admin/Think/lists?model=config', '配置管理', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('107', 'admin', '1', 'Admin/Action/actionlog', '行为日志', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('108', 'admin', '1', 'Admin/User/updatePassword', '修改密码', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('109', 'admin', '1', 'Admin/User/updateNickname', '修改昵称', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('110', 'admin', '1', 'Admin/action/edit', '查看行为日志', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('205', 'admin', '1', 'Admin/think/add', '新增数据', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('111', 'admin', '2', 'Admin/article/index', '文档列表', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('112', 'admin', '2', 'Admin/article/add', '新增', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('113', 'admin', '2', 'Admin/article/edit', '编辑', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('114', 'admin', '2', 'Admin/article/setStatus', '改变状态', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('115', 'admin', '2', 'Admin/article/update', '保存', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('116', 'admin', '2', 'Admin/article/autoSave', '保存草稿', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('117', 'admin', '2', 'Admin/article/move', '移动', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('118', 'admin', '2', 'Admin/article/copy', '复制', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('119', 'admin', '2', 'Admin/article/paste', '粘贴', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('120', 'admin', '2', 'Admin/article/batchOperate', '导入', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('121', 'admin', '2', 'Admin/article/recycle', '回收站', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('122', 'admin', '2', 'Admin/article/permit', '还原', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('123', 'admin', '2', 'Admin/article/clear', '清空', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('124', 'admin', '2', 'Admin/User/add', '新增用户', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('125', 'admin', '2', 'Admin/User/action', '用户行为', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('126', 'admin', '2', 'Admin/User/addAction', '新增用户行为', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('127', 'admin', '2', 'Admin/User/editAction', '编辑用户行为', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('128', 'admin', '2', 'Admin/User/saveAction', '保存用户行为', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('129', 'admin', '2', 'Admin/User/setStatus', '变更行为状态', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('130', 'admin', '2', 'Admin/User/changeStatus?method=forbidUser', '禁用会员', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('131', 'admin', '2', 'Admin/User/changeStatus?method=resumeUser', '启用会员', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('132', 'admin', '2', 'Admin/User/changeStatus?method=deleteUser', '删除会员', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('133', 'admin', '2', 'Admin/AuthManager/index', '权限管理', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('134', 'admin', '2', 'Admin/AuthManager/changeStatus?method=deleteGroup', '删除', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('135', 'admin', '2', 'Admin/AuthManager/changeStatus?method=forbidGroup', '禁用', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('136', 'admin', '2', 'Admin/AuthManager/changeStatus?method=resumeGroup', '恢复', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('137', 'admin', '2', 'Admin/AuthManager/createGroup', '新增', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('138', 'admin', '2', 'Admin/AuthManager/editGroup', '编辑', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('139', 'admin', '2', 'Admin/AuthManager/writeGroup', '保存用户组', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('140', 'admin', '2', 'Admin/AuthManager/group', '授权', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('141', 'admin', '2', 'Admin/AuthManager/access', '访问授权', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('142', 'admin', '2', 'Admin/AuthManager/user', '成员授权', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('143', 'admin', '2', 'Admin/AuthManager/removeFromGroup', '解除授权', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('144', 'admin', '2', 'Admin/AuthManager/addToGroup', '保存成员授权', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('145', 'admin', '2', 'Admin/AuthManager/category', '分类授权', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('146', 'admin', '2', 'Admin/AuthManager/addToCategory', '保存分类授权', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('147', 'admin', '2', 'Admin/AuthManager/modelauth', '模型授权', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('148', 'admin', '2', 'Admin/AuthManager/addToModel', '保存模型授权', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('149', 'admin', '2', 'Admin/Addons/create', '创建', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('150', 'admin', '2', 'Admin/Addons/checkForm', '检测创建', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('151', 'admin', '2', 'Admin/Addons/preview', '预览', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('152', 'admin', '2', 'Admin/Addons/build', '快速生成插件', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('153', 'admin', '2', 'Admin/Addons/config', '设置', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('154', 'admin', '2', 'Admin/Addons/disable', '禁用', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('155', 'admin', '2', 'Admin/Addons/enable', '启用', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('156', 'admin', '2', 'Admin/Addons/install', '安装', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('157', 'admin', '2', 'Admin/Addons/uninstall', '卸载', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('158', 'admin', '2', 'Admin/Addons/saveconfig', '更新配置', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('159', 'admin', '2', 'Admin/Addons/adminList', '插件后台列表', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('160', 'admin', '2', 'Admin/Addons/execute', 'URL方式访问插件', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('161', 'admin', '2', 'Admin/Addons/hooks', '钩子管理', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('162', 'admin', '2', 'Admin/Model/index', '模型管理', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('163', 'admin', '2', 'Admin/model/add', '新增', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('164', 'admin', '2', 'Admin/model/edit', '编辑', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('165', 'admin', '2', 'Admin/model/setStatus', '改变状态', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('166', 'admin', '2', 'Admin/model/update', '保存数据', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('167', 'admin', '2', 'Admin/Attribute/index', '属性管理', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('168', 'admin', '2', 'Admin/Attribute/add', '新增', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('169', 'admin', '2', 'Admin/Attribute/edit', '编辑', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('170', 'admin', '2', 'Admin/Attribute/setStatus', '改变状态', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('171', 'admin', '2', 'Admin/Attribute/update', '保存数据', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('172', 'admin', '2', 'Admin/Config/index', '配置管理', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('173', 'admin', '2', 'Admin/Config/edit', '编辑', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('174', 'admin', '2', 'Admin/Config/del', '删除', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('175', 'admin', '2', 'Admin/Config/add', '新增', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('176', 'admin', '2', 'Admin/Config/save', '保存', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('177', 'admin', '2', 'Admin/Menu/index', '菜单管理', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('178', 'admin', '2', 'Admin/Channel/index', '导航管理', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('179', 'admin', '2', 'Admin/Channel/add', '新增', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('180', 'admin', '2', 'Admin/Channel/edit', '编辑', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('181', 'admin', '2', 'Admin/Channel/del', '删除', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('182', 'admin', '2', 'Admin/Category/index', '分类管理', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('183', 'admin', '2', 'Admin/Category/edit', '编辑', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('184', 'admin', '2', 'Admin/Category/add', '新增', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('185', 'admin', '2', 'Admin/Category/remove', '删除', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('186', 'admin', '2', 'Admin/Category/move', '移动', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('187', 'admin', '2', 'Admin/Category/merge', '合并', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('188', 'admin', '2', 'Admin/Database/index?type=export', '备份数据库', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('189', 'admin', '2', 'Admin/Database/export', '备份', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('190', 'admin', '2', 'Admin/Database/optimize', '优化表', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('191', 'admin', '2', 'Admin/Database/repair', '修复表', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('192', 'admin', '2', 'Admin/Database/index?type=import', '还原数据库', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('193', 'admin', '2', 'Admin/Database/import', '恢复', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('194', 'admin', '2', 'Admin/Database/del', '删除', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('195', 'admin', '2', 'Admin/other', '其他', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('196', 'admin', '2', 'Admin/Menu/add', '新增', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('197', 'admin', '2', 'Admin/Menu/edit', '编辑', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('198', 'admin', '2', 'Admin/Think/lists?model=article', '应用', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('199', 'admin', '2', 'Admin/Think/lists?model=download', '下载管理', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('200', 'admin', '2', 'Admin/Think/lists?model=config', '应用', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('201', 'admin', '2', 'Admin/Action/actionlog', '行为日志', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('202', 'admin', '2', 'Admin/User/updatePassword', '修改密码', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('203', 'admin', '2', 'Admin/User/updateNickname', '修改昵称', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('204', 'admin', '2', 'Admin/action/edit', '查看行为日志', '-1', '');
INSERT INTO `fdo_auth_rule` VALUES ('206', 'admin', '1', 'Admin/think/edit', '编辑数据', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('207', 'admin', '1', 'Admin/Menu/import', '导入', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('208', 'admin', '1', 'Admin/Model/generate', '生成', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('209', 'admin', '1', 'Admin/Addons/addHook', '新增钩子', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('210', 'admin', '1', 'Admin/Addons/edithook', '编辑钩子', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('211', 'admin', '1', 'Admin/Article/sort', '文档排序', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('212', 'admin', '1', 'Admin/Config/sort', '排序', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('213', 'admin', '1', 'Admin/Menu/sort', '排序', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('214', 'admin', '1', 'Admin/Channel/sort', '排序', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('215', 'admin', '1', 'Admin/Category/operate/type/move', '移动', '1', '');
INSERT INTO `fdo_auth_rule` VALUES ('216', 'admin', '1', 'Admin/Category/operate/type/merge', '合并', '1', '');

-- ----------------------------
-- Table structure for `fdo_cart`
-- ----------------------------
DROP TABLE IF EXISTS `fdo_cart`;
CREATE TABLE `fdo_cart` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `member_id` char(40) NOT NULL COMMENT '用户ID',
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
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '-1删除 0-禁用 1-正常',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='店铺内商品分类';

-- ----------------------------
-- Records of fdo_category
-- ----------------------------
INSERT INTO `fdo_category` VALUES ('1', 'haha', '0', '3', '255', '1');
INSERT INTO `fdo_category` VALUES ('2', '测试1', '0', '3', '255', '1');
INSERT INTO `fdo_category` VALUES ('3', '测试1_1', '2', '3', '255', '1');
INSERT INTO `fdo_category` VALUES ('4', 'haha_1', '1', '3', '255', '1');
INSERT INTO `fdo_category` VALUES ('5', '饮料', '0', '3', '255', '1');
INSERT INTO `fdo_category` VALUES ('6', '中式', '0', '5', '255', '1');
INSERT INTO `fdo_category` VALUES ('7', '西餐', '0', '5', '255', '1');
INSERT INTO `fdo_category` VALUES ('8', '阿斯a', '0', '2', '255', '-1');
INSERT INTO `fdo_category` VALUES ('9', '111', '0', '2', '255', '1');
INSERT INTO `fdo_category` VALUES ('10', '222', '9', '2', '255', '1');
INSERT INTO `fdo_category` VALUES ('11', 'a1', '0', '2', '255', '1');
INSERT INTO `fdo_category` VALUES ('12', 'bbb', '0', '2', '255', '1');

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
INSERT INTO `fdo_config` VALUES ('1', 'WEB_SITE_TITLE', '1', '网站标题', '1', '', '网站标题前台显示标题', '1378898976', '1379235274', '1', '快点餐订餐平台', '0');
INSERT INTO `fdo_config` VALUES ('2', 'WEB_SITE_DESCRIPTION', '2', '网站描述', '1', '', '网站搜索引擎描述', '1378898976', '1379235841', '1', '快点餐订餐平台', '1');
INSERT INTO `fdo_config` VALUES ('3', 'WEB_SITE_KEYWORD', '2', '网站关键字', '1', '', '网站搜索引擎关键字', '1378898976', '1381390100', '1', '快点餐,订餐平台', '8');
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
INSERT INTO `fdo_config` VALUES ('25', 'LIST_ROWS', '0', '后台每页记录数', '2', '', '后台数据每页显示记录数', '1379503896', '1380427745', '1', '20', '10');
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
INSERT INTO `fdo_config` VALUES ('37', 'SHOW_PAGE_TRACE', '4', '是否显示页面Trace', '4', '0:关闭\r\n1:开启', '是否显示页面Trace信息', '1387165685', '1387165685', '1', '0', '1');

-- ----------------------------
-- Table structure for `fdo_goods`
-- ----------------------------
DROP TABLE IF EXISTS `fdo_goods`;
CREATE TABLE `fdo_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(10) unsigned NOT NULL COMMENT '所属店铺ID',
  `cate_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属分类ID; 0-无分类',
  `goods_name` varchar(128) NOT NULL DEFAULT '' COMMENT '商品名称',
  `image` varchar(255) DEFAULT NULL COMMENT '商品图片',
  `price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '单价',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '255' COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '-1删除 0-禁用 1-正常',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='商品表';

-- ----------------------------
-- Records of fdo_goods
-- ----------------------------
INSERT INTO `fdo_goods` VALUES ('1', '2', '0', '测试商品12', '/Uploads/Picture/2014-11-17/5469abf324d28.jpg', '10.00', '255', '1');
INSERT INTO `fdo_goods` VALUES ('2', '4', '0', '测试商品', null, '10.00', '255', '1');
INSERT INTO `fdo_goods` VALUES ('3', '5', '0', '炒面', null, '8.00', '255', '-1');
INSERT INTO `fdo_goods` VALUES ('4', '5', '6', '快餐（二荤一素）', null, '10.00', '255', '1');
INSERT INTO `fdo_goods` VALUES ('5', '2', '0', '123', null, '255.00', '255', '1');
INSERT INTO `fdo_goods` VALUES ('6', '2', '0', '11', '', '255.00', '255', '-1');
INSERT INTO `fdo_goods` VALUES ('7', '2', '0', '22', '/Uploads/Picture/2014-11-16/546786d356f7b.jpg', '22.00', '255', '1');
INSERT INTO `fdo_goods` VALUES ('8', '2', '0', '111', '', '255.00', '255', '-1');
INSERT INTO `fdo_goods` VALUES ('9', '2', '0', '132423', '', '5.00', '255', '-1');
INSERT INTO `fdo_goods` VALUES ('10', '2', '0', 'asd2', '/Uploads/Picture/2014-11-16/546786a38a999.jpg', '11.00', '255', '1');
INSERT INTO `fdo_goods` VALUES ('11', '2', '0', 'ccc', '/Uploads/Picture/2014-11-16/546786ee5d46f.jpg', '11.00', '255', '1');
INSERT INTO `fdo_goods` VALUES ('12', '2', '8', 'fff', '/Uploads/Picture/2014-11-16/54678796a0846.jpg', '11.00', '255', '1');

-- ----------------------------
-- Table structure for `fdo_member`
-- ----------------------------
DROP TABLE IF EXISTS `fdo_member`;
CREATE TABLE `fdo_member` (
  `id` char(40) NOT NULL COMMENT '使用unqid函数生成,md5处理',
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
INSERT INTO `fdo_member` VALUES ('', null, null, '1420618498', '0', '3232235845', '1', '1');
INSERT INTO `fdo_member` VALUES ('09a2cc5be89311542f6f966c6221d9ba2af9d879', null, null, '1416818170', '0', '2130706433', '1', '1');
INSERT INTO `fdo_member` VALUES ('0a3d52730609c7e870e293fff30dd4060703bd7e', null, null, '1416818177', '0', '2130706433', '1', '1');
INSERT INTO `fdo_member` VALUES ('0a8591f1eeb702f08a9440f5eeb3853029e86a12', null, null, '1416818203', '0', '2130706433', '1', '1');
INSERT INTO `fdo_member` VALUES ('0e2e819b9874f6b51f4d9638dffb3f88ac55da5a', null, null, '1416818166', '0', '2130706433', '1', '1');
INSERT INTO `fdo_member` VALUES ('13e48fe20a63947a6ce82ba73e7146098970da12', null, null, '1416818168', '0', '2130706433', '1', '1');
INSERT INTO `fdo_member` VALUES ('15d55bb2d631e5af9076fe38b9b12987441a8be2', null, null, '1416818193', '0', '2130706433', '1', '1');
INSERT INTO `fdo_member` VALUES ('1cc0b6fec8894c4ea4007cc9bef99bdf266d8545', null, null, '1416818169', '0', '2130706433', '1', '1');
INSERT INTO `fdo_member` VALUES ('23cba171df280508877314605757f82cb3253216', null, null, '1416818167', '0', '2130706433', '1', '1');
INSERT INTO `fdo_member` VALUES ('268f7d9f28b89bce2643f024a4ebba833b1154d9', 'aaaa', 'a04bbc6ca0d14e50cd9e9b6258b6842a', '1415691576', '0', null, '0', '1');
INSERT INTO `fdo_member` VALUES ('28c5aea86dc2cc77c86bae525913c673ff80e76c', null, null, '1416818184', '0', '2130706433', '1', '1');
INSERT INTO `fdo_member` VALUES ('2eb9eb2c2d295abdd0ba9f957f28bff862514025', null, null, '1416818165', '0', '2130706433', '1', '1');
INSERT INTO `fdo_member` VALUES ('2ed535c597054035fa09dfab6abf7ce4b2762d10', null, null, '1416818244', '0', '2130706433', '1', '1');
INSERT INTO `fdo_member` VALUES ('3068f2fe7e74f55239dd3e52d6eebabbf91d22bf', null, null, '1416818164', '0', '2130706433', '1', '1');
INSERT INTO `fdo_member` VALUES ('333d1caded9b69502a93190f4b1968570ed40b11', null, null, '1416818180', '0', '2130706433', '1', '1');
INSERT INTO `fdo_member` VALUES ('3a3e46f3cd2c9eabfe2bb1bb7ebbea7b8eec426b', null, null, '1416818188', '0', '2130706433', '1', '1');
INSERT INTO `fdo_member` VALUES ('3d629a2a04a474ddfd28e6a10e1b47b044f650da', null, null, '1416818205', '0', '2130706433', '1', '1');
INSERT INTO `fdo_member` VALUES ('3ddde2d752a637ba8dc7b469b6f438b974a94bd1', null, null, '1420781773', '0', '2130706433', '1', '1');
INSERT INTO `fdo_member` VALUES ('3e5fbc50be6d93a3573f3f3fb13c39379f51c1af', null, null, '1416818171', '0', '2130706433', '1', '1');
INSERT INTO `fdo_member` VALUES ('4730e42ed85548332d6367e38a7de8db17aafe88', null, null, '1420620265', '0', '3232235785', '1', '1');
INSERT INTO `fdo_member` VALUES ('484d81475fde736840dba63c8e004ed8efbd3372', null, null, '1416818191', '0', '2130706433', '1', '1');
INSERT INTO `fdo_member` VALUES ('488e90e59f1d8211046744e26e91a542777c1f0c', null, null, '1416818185', '0', '2130706433', '1', '1');
INSERT INTO `fdo_member` VALUES ('4ee626a7e06883ab66a1312fe501d123e6b78825', null, null, '1416818194', '0', '2130706433', '1', '1');
INSERT INTO `fdo_member` VALUES ('4f2274a5b9fbcefe954b3a17b951618997fe0338', null, null, '1416818178', '0', '2130706433', '1', '1');
INSERT INTO `fdo_member` VALUES ('52efba2ffdb06495bd6b9544057e7e32f4b8dfc8', null, null, '1417073064', '0', '3232235834', '1', '1');
INSERT INTO `fdo_member` VALUES ('550426ed8d49b8083f336c09e19e9addae7858e6', null, null, '1416818181', '0', '2130706433', '1', '1');
INSERT INTO `fdo_member` VALUES ('5a3062b198f059e4d66613ffc7b848785d496e5c', null, null, '1420620222', '0', '3232235845', '1', '1');
INSERT INTO `fdo_member` VALUES ('5a95f2eae29698352ab712541f4eee80564fc78a', null, null, '1416818201', '0', '2130706433', '1', '1');
INSERT INTO `fdo_member` VALUES ('5c00826514893b1e1b3d6bcaa60b54bf5a734299', null, null, '1416818163', '0', '2130706433', '1', '1');
INSERT INTO `fdo_member` VALUES ('5f82bb2a9d0fb3028d7541f6e7e69750c62b39a9', null, null, '1420621440', '0', '3232235845', '1', '1');
INSERT INTO `fdo_member` VALUES ('6dbeb5b9cb1b601985938d82f132504b3645b779', null, null, '1416818165', '0', '2130706433', '1', '1');
INSERT INTO `fdo_member` VALUES ('6e40106d05a16a99bc668480d7423ee62c54ffa1', null, null, '1416818173', '0', '2130706433', '1', '1');
INSERT INTO `fdo_member` VALUES ('70945e25778025b0c8382465a99da67539aeccde', null, null, '1416818198', '0', '2130706433', '1', '1');
INSERT INTO `fdo_member` VALUES ('7ab1e825ecb4afd8c1fd9a33cf99da7fb5efcbf7', null, null, '1416818183', '0', '2130706433', '1', '1');
INSERT INTO `fdo_member` VALUES ('82356acf53d8dac7b80a1568506a083d20b8d00d', null, null, '1417501626', '0', '3232235783', '1', '1');
INSERT INTO `fdo_member` VALUES ('83605fad6e65fc20407b70a5f12d8c7684ed2e53', null, null, '1416818200', '0', '2130706433', '1', '1');
INSERT INTO `fdo_member` VALUES ('87683c0cdff442f331d92c03a02f8bfc50ae9ddd', null, null, '1416818189', '0', '2130706433', '1', '1');
INSERT INTO `fdo_member` VALUES ('92902bf33785d7bf120f8614bd8fd9f4ffaf3b52', null, null, '1416818162', '0', '2130706433', '1', '1');
INSERT INTO `fdo_member` VALUES ('9bc757f1b509fe31a2485a28d7bdac242a5aaab3', null, null, '1416818176', '0', '2130706433', '1', '1');
INSERT INTO `fdo_member` VALUES ('9d13d14aa6d313ed41399966205a308a31d162c6', null, null, '1416818108', '0', '2130706433', '1', '1');
INSERT INTO `fdo_member` VALUES ('a71658c04c8accd6f2d11294828fcaa151fac4e0', null, null, '1416818161', '0', '2130706433', '1', '1');
INSERT INTO `fdo_member` VALUES ('a9918b4f9e64d6cf89db34b73620f64f48602b81', null, null, '1416818204', '0', '2130706433', '1', '1');
INSERT INTO `fdo_member` VALUES ('abf9ea5e612bc3b885f837ae5e0cb152d3d64373', null, null, '1416818199', '0', '2130706433', '1', '1');
INSERT INTO `fdo_member` VALUES ('ac37226d49775a132f8c787e68119665da89d971', null, null, '1416818192', '0', '2130706433', '1', '1');
INSERT INTO `fdo_member` VALUES ('afd090a252592f4d41f353d35aa3819005e6c255', null, null, '1416818164', '0', '2130706433', '1', '1');
INSERT INTO `fdo_member` VALUES ('b928bd55efbf543f4eecaff8ba2e954f111d2e48', null, null, '1416818175', '0', '2130706433', '1', '1');
INSERT INTO `fdo_member` VALUES ('bab399c741c2e42129dcb99ff5770512b8ce8607', null, null, '1416818174', '0', '2130706433', '1', '1');
INSERT INTO `fdo_member` VALUES ('bed94f9a925d693d7cff778624c6e00582a2db5f', null, null, '1416818182', '0', '2130706433', '1', '1');
INSERT INTO `fdo_member` VALUES ('c638838e15b2cc4d9508cd94a73643e22fe0ad13', null, null, '1416818163', '0', '2130706433', '1', '1');
INSERT INTO `fdo_member` VALUES ('d44c2a8ca2e5258609b71c4a4da8ed9560cded99', null, null, '1416818080', '0', '2130706433', '1', '1');
INSERT INTO `fdo_member` VALUES ('d8aacf23dbc8cdca798a9417a96e89a8e76ce32a', null, null, '1418615747', '0', '3232235779', '1', '1');
INSERT INTO `fdo_member` VALUES ('dc47d4b0566368697f665f3f7fafaf407a59e700', null, null, '1416818186', '0', '2130706433', '1', '1');
INSERT INTO `fdo_member` VALUES ('df1c3b26a4e743a47b8913eb7085904a77fd4fc9', null, null, '1416818197', '0', '2130706433', '1', '1');
INSERT INTO `fdo_member` VALUES ('e60bb2fd72d625aebc47b56bc60af04ddb47f723', null, null, '1416818190', '0', '2130706433', '1', '1');
INSERT INTO `fdo_member` VALUES ('e9bc5e955b9fd766c7c6b302f347600bcd3d4ceb', null, null, '1417073793', '0', '3232235780', '1', '1');
INSERT INTO `fdo_member` VALUES ('ee2cf0bc36d06d62034a42abe38e11338a88f0b3', null, null, '1416818196', '0', '2130706433', '1', '1');
INSERT INTO `fdo_member` VALUES ('faf92e32f3823cc15a971e0b04db4f77faac3a6d', null, null, '1416818160', '0', '2130706433', '1', '1');

-- ----------------------------
-- Table structure for `fdo_menu`
-- ----------------------------
DROP TABLE IF EXISTS `fdo_menu`;
CREATE TABLE `fdo_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '标题',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID',
  `sort` int(10) unsigned NOT NULL DEFAULT '255' COMMENT '排序（同级有效）',
  `url` char(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `hide` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否隐藏',
  `tip` varchar(255) NOT NULL DEFAULT '' COMMENT '提示',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of fdo_menu
-- ----------------------------
INSERT INTO `fdo_menu` VALUES ('1', '首页', '0', '255', 'Index/index', '0', '');
INSERT INTO `fdo_menu` VALUES ('2', '会员', '0', '255', '', '0', '');
INSERT INTO `fdo_menu` VALUES ('3', '会员管理', '2', '255', 'Member/lists', '0', '');
INSERT INTO `fdo_menu` VALUES ('4', '新增会员', '2', '255', 'Member/add', '0', '');
INSERT INTO `fdo_menu` VALUES ('5', '店铺', '0', '255', '', '0', '');
INSERT INTO `fdo_menu` VALUES ('6', '店铺管理', '5', '255', 'Store/lists', '0', '');
INSERT INTO `fdo_menu` VALUES ('7', '新增店铺', '5', '255', 'Store/add', '0', '');
INSERT INTO `fdo_menu` VALUES ('8', '订单管理', '0', '255', 'Order/lists', '0', '');
INSERT INTO `fdo_menu` VALUES ('9', '店铺类别', '0', '255', '', '0', '');
INSERT INTO `fdo_menu` VALUES ('10', '类别管理', '9', '255', 'Attr/lists', '0', '');
INSERT INTO `fdo_menu` VALUES ('11', '新增类别', '9', '255', 'Attr/add', '0', '');
INSERT INTO `fdo_menu` VALUES ('12', '新建属性值', '9', '255', 'Attr/valAdd', '0', '');
INSERT INTO `fdo_menu` VALUES ('13', '网站设置', '0', '255', 'Config/group', '0', '');

-- ----------------------------
-- Table structure for `fdo_order`
-- ----------------------------
DROP TABLE IF EXISTS `fdo_order`;
CREATE TABLE `fdo_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '订单ID',
  `order_sn` varchar(32) NOT NULL DEFAULT '' COMMENT '订单编号',
  `store_id` int(10) unsigned NOT NULL COMMENT '所属店铺ID',
  `member_id` char(40) NOT NULL COMMENT '购买用户编号',
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
  `store_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '店铺接收订单状态 0-否 1-是',
  `store_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '店铺接收时间',
  `ship_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '配送状态 0-未开始 1-配送中',
  `confirm` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '客户收货确认 0-否 1-是',
  `ship_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '发货时间',
  `confirm_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '客户收货确认时间',
  `confirm_expired` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '确认收货截止时间',
  `unreceived` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '用户未收货申请1-是,0-否;必须在收货截止时间内才能申请',
  `finish_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '订单完成时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '-1删除 0禁用 1正常',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='订单表';

-- ----------------------------
-- Records of fdo_order
-- ----------------------------
INSERT INTO `fdo_order` VALUES ('1', '12332543', '2', '85fce697249c4be2c1f86418da3b4842683afc65', '哈哈', '洛阳西工区紫金城', '13333333333', null, '31.00', '1', '0', '1416211831', '0', '0', '0', '1', '1416211796', '1', '0', '1416211831', '0', '0', '0', '0', '1');
INSERT INTO `fdo_order` VALUES ('2', '1243795881', '2', '9d13d14aa6d313ed41399966205a308a31d162c6', '123123', '人民大街16号', '15888888888', '', '52.00', '1', '1418871387', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1');
INSERT INTO `fdo_order` VALUES ('3', '1218581070', '2', '9d13d14aa6d313ed41399966205a308a31d162c6', '123123', '人民大街16号', '15888888888', '', '52.00', '1', '1418961496', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1');
INSERT INTO `fdo_order` VALUES ('4', '1208070377', '2', '9d13d14aa6d313ed41399966205a308a31d162c6', '123123', '人民大街16号', '15888888888', '', '32.00', '1', '1419404529', '1420705128', '0', '0', '0', '1', '0', '1', '0', '1420705128', '0', '0', '0', '0', '1');
INSERT INTO `fdo_order` VALUES ('5', '1107779055', '2', '5f82bb2a9d0fb3028d7541f6e7e69750c62b39a9', 'hhhh', 'ggv#hjjhgbbhh', '1333333333', '', '22.00', '1', '1420621480', '1420705213', '0', '0', '0', '1', '0', '1', '0', '1420705213', '0', '0', '0', '0', '1');

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
INSERT INTO `fdo_order_goods` VALUES ('1', '1', '3', '10.00', '30.00');
INSERT INTO `fdo_order_goods` VALUES ('2', '1', '3', '10.00', '30.00');
INSERT INTO `fdo_order_goods` VALUES ('2', '10', '2', '11.00', '22.00');
INSERT INTO `fdo_order_goods` VALUES ('3', '1', '3', '10.00', '30.00');
INSERT INTO `fdo_order_goods` VALUES ('3', '10', '2', '11.00', '22.00');
INSERT INTO `fdo_order_goods` VALUES ('4', '1', '1', '10.00', '10.00');
INSERT INTO `fdo_order_goods` VALUES ('4', '7', '1', '22.00', '22.00');
INSERT INTO `fdo_order_goods` VALUES ('5', '10', '1', '11.00', '11.00');
INSERT INTO `fdo_order_goods` VALUES ('5', '11', '1', '11.00', '11.00');

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='支付方式';

-- ----------------------------
-- Records of fdo_payment
-- ----------------------------
INSERT INTO `fdo_payment` VALUES ('1', 'cod', '货到付款', '', null, '0', '255', '0');

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
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of fdo_store
-- ----------------------------
INSERT INTO `fdo_store` VALUES ('2', 'aaa', 'fa157449ce7b9bbfcb47e4cb70b2ee22', '演示店铺', '张老板', '123456789012345678', '人民大街16号', '010-88886666-8866', '6.00', '2.7', '10', '1', '', '', '', '', '0', null, '0', '22', '1249543819', '0', '0', '', '1');
INSERT INTO `fdo_store` VALUES ('3', 'aaaa', '2b02f38867b04a7120e65394c6d6cba4', '某某快餐店', '李哲', '', '紫金城', '', '0.00', '3.0', '0', '0', '/Uploads/Picture/2014-11-11/54617cce202cc.jpg', null, null, null, '0', null, '0', '255', '1414482653', '0', '0', '', '1');
INSERT INTO `fdo_store` VALUES ('4', 'ceshi', 'c8dbcf6665483e2cc9f2fbc79c1ef875', 'ceshi', 'ceshi', '', '', '', '0.00', '3.0', '0', '0', null, null, null, null, '0', null, '0', '255', '1415674766', '0', '0', '', '1');
INSERT INTO `fdo_store` VALUES ('5', 'ceshi2', '9c97893f2d530f93c5b76841af9af5b6', 'ceshi2', 'ceshi2', '', '', '', '0.00', '3.0', '0', '0', '/Uploads/Picture/2014-11-11/54617db6360be.jpg', null, null, null, '0', null, '0', '255', '1415675318', '0', '0', '', '1');

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
INSERT INTO `fdo_store_attr` VALUES ('2', '8');
INSERT INTO `fdo_store_attr` VALUES ('3', '2');
INSERT INTO `fdo_store_attr` VALUES ('3', '3');
INSERT INTO `fdo_store_attr` VALUES ('3', '4');
INSERT INTO `fdo_store_attr` VALUES ('3', '5');
INSERT INTO `fdo_store_attr` VALUES ('3', '8');

-- ----------------------------
-- Table structure for `fdo_user`
-- ----------------------------
DROP TABLE IF EXISTS `fdo_user`;
CREATE TABLE `fdo_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `account` varchar(32) NOT NULL COMMENT '帐号',
  `password` char(32) NOT NULL,
  `depart_id` int(10) unsigned NOT NULL COMMENT '所属部门ID',
  `realname` varchar(32) NOT NULL COMMENT '真实姓名',
  `sex` enum('0','1') NOT NULL DEFAULT '1' COMMENT '1男 0女',
  `birthday` varchar(32) DEFAULT '0' COMMENT '生日',
  `logins` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '登录次数',
  `last_login` varchar(32) NOT NULL DEFAULT '0' COMMENT '上次登录时间',
  `last_ip` varchar(32) NOT NULL DEFAULT '0' COMMENT '上次登录IP',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '-1:删除 0:禁用 1:正常',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='员工表';

-- ----------------------------
-- Records of fdo_user
-- ----------------------------
INSERT INTO `fdo_user` VALUES ('1', 'admin', 'de14566c080c81c80ffb5eacf68793a9', '0', '超管', '1', '0', '7', '1415689341', '3232235819', '1');
INSERT INTO `fdo_user` VALUES ('2', 'administrator', 'de14566c080c81c80ffb5eacf68793a9', '0', '超管', '1', '0', '0', '0', '0', '1');
