
#ALTER TABLE `zhilin_goods_common` MODIFY COLUMN `goods_fenxiao`  float UNSIGNED NOT NULL DEFAULT 0 COMMENT '��������' AFTER `goods_discount`;

ALTER TABLE `zhilin_cart` ADD `up_id` INT( 10 ) NOT NULL ,ADD `up_name` VARCHAR( 100 ) NOT NULL; 
ALTER TABLE `zhilin_goods` ADD `up_id` INT( 10 ) NOT NULL ,ADD `up_name` VARCHAR( 100 ) NOT NULL ,ADD `image_type` char( 3 ) NOT NULL ,ADD `baifen` INT( 10 ) NOT NULL; 
ALTER TABLE `zhilin_goods_common` ADD `baifen` INT( 10 ) NOT NULL;
ALTER TABLE `zhilin_order` ADD `up_id` INT( 10 ) NOT NULL ,ADD `up_name` VARCHAR( 100 ) NOT NULL ,ADD `baifen` INT( 10 ) NOT NULL; 
ALTER TABLE `zhilin_order_goods` ADD `baifen` INT( 10 ) NOT NULL,ADD `ti` INT( 1 ) NOT NULL;
ALTER TABLE `zhilin_store` ADD `fd` INT( 1 ) NOT NULL;
