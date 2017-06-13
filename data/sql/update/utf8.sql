# 新增推广管理奖励表 字段 推广类型 用来区别是用户推广（B2C）还是城市联盟推广（B2B） zhangc
ALTER TABLE `zlin_extension_manageaward` ADD `extend_type`  tinyint(1) NULL DEFAULT 0 COMMENT '推广类型：0会员推广；1城市联盟';
