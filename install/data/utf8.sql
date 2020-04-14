CREATE TABLE IF NOT EXISTS `#__user` (
  `uid` int(5) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `username` varchar(15) NOT NULL COMMENT '用户名',
  `password` varchar(35) NOT NULL COMMENT '密码',
  `email` varchar(30) NOT NULL COMMENT '邮箱',
  `Isallow` smallint(2) NOT NULL DEFAULT '0' COMMENT '是否禁止登录, 0正常 1禁止',
  `Isadmin` smallint(2) DEFAULT '0' COMMENT '是否管理员 0不是 1是',
  `pro_id` int(11) DEFAULT '0' COMMENT '所属项目ID,默认为0',
  `role_id` varchar(255) DEFAULT '0' COMMENT '角色ID，控制权限',
  `addtime` int(11) NOT NULL COMMENT '注册时间',
  `utime` int(11) NOT NULL COMMENT '更新时间',
  `salt` varchar(35) NOT NULL COMMENT '盐密码',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='用户表';
CREATE TABLE IF NOT EXISTS `#__account_class` (
  `classid` int(5) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `classname` varchar(20) NOT NULL COMMENT '分类名称',
  `classtype` int(1) NOT NULL COMMENT '类型 1为收入 2为支出',
  `userid` int(8) NOT NULL COMMENT '记账用户ID',
  PRIMARY KEY (`classid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='类型表';
CREATE TABLE IF NOT EXISTS `#__account` (
  `acid` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `acmoney` decimal(10,2) NOT NULL COMMENT '金额',
  `acclassid` int(8) NOT NULL COMMENT '分类ID',
  `actime` int(11) NOT NULL COMMENT '记账时间',
  `acremark` varchar(50) DEFAULT NULL,
  `userid` int(8) NOT NULL COMMENT '记账用户ID',
  `zhifu` int(8) NOT NULL COMMENT '类型 1为收入 2为支出',
  `bankid` int(8) NOT NULL COMMENT '账户ID',
  `proid` int(8) NOT NULL DEFAULT '0' COMMENT '项目ID',
  PRIMARY KEY (`acid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='账目表';
CREATE TABLE IF NOT EXISTS `#__bank` (
  `bankid` int(11) NOT NULL AUTO_INCREMENT COMMENT '账户ID',
  `bankname` varchar(50) NOT NULL DEFAULT '' COMMENT '账户名称',
  `bankaccount` varchar(50) NOT NULL DEFAULT '0' COMMENT '卡号/账号',
  `balancemoney` decimal(10,2) DEFAULT NULL COMMENT '账户余额',
  `userid` int(8) NOT NULL COMMENT '记账用户ID',
  PRIMARY KEY (`bankid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='账户表';
CREATE TABLE `#__program` (
  `proid` int(11) NOT NULL AUTO_INCREMENT COMMENT '项目ID',
  `proname` varchar(50) NOT NULL COMMENT '项目名称',
  `userid` int(8) NOT NULL COMMENT '记账用户ID',
  `orderid` int(8) NOT NULL COMMENT '排序ID',
  PRIMARY KEY (`proid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='项目表';
CREATE TABLE `#__sys_menu` (
  `m_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '菜单ID',
  `m_f_id` int(10) DEFAULT '0' COMMENT '父级ID',
  `m_name` varchar(100) NOT NULL COMMENT '菜单名称',
  `m_type` int(1) NOT NULL COMMENT '类型 1为一级菜单 2为二级菜单 3为操作',
  `m_url` varchar(100) NOT NULL COMMENT '菜单链接',
  `islock` int(1) DEFAULT '0' COMMENT '系统锁定 0不锁定 1锁定',
  `isshow` int(1) DEFAULT '1' COMMENT '是否显示 0隐藏 1显示',
  `orderid` int(5) NOT NULL COMMENT '排序ID 数字越大越靠前',
  `addtime` int(11) NOT NULL COMMENT '创建时间',
  `updatetime` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`m_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统菜单表';
CREATE TABLE `#__sys_role` (
  `role_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '角色ID',
  `role_name` varchar(100) NOT NULL COMMENT '角色名称',
  `role_menu_id` varchar(200) DEFAULT NULL COMMENT '角色权限ID',
  `role_description` varchar(100) DEFAULT NULL COMMENT '角色描述',
  `addtime` int(11) DEFAULT '0' COMMENT '创建时间',
  `updatetime` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统角色表';