CREATE TABLE IF NOT EXISTS `#__orders_orders` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
`modified_by` INT(11)  NOT NULL ,
`customers_name` VARCHAR(255)  NOT NULL ,
`customers_email` VARCHAR(255)  NOT NULL ,
`customers_phone` VARCHAR(255)  NOT NULL ,
`upload_pdf` TEXT NOT NULL ,
`book_size` VARCHAR(255)  NOT NULL ,
`cover_type` VARCHAR(255)  NOT NULL ,
`paper_type` VARCHAR(255)  NOT NULL ,
`book_title` VARCHAR(255)  NOT NULL ,
`author_name` VARCHAR(255)  NOT NULL ,
`category_title` VARCHAR(255)  NOT NULL ,
`payment_status` VARCHAR(255)  NOT NULL ,
`order_status` VARCHAR(255)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8mb4_unicode_ci;


INSERT INTO `#__content_types` (`type_title`, `type_alias`, `table`, `content_history_options`)
SELECT * FROM ( SELECT 'Order','com_orders.order','{"special":{"dbtable":"#__orders_orders","key":"id","type":"Order","prefix":"RdersTable"}}', '{"formFile":"administrator\/components\/com_orders\/models\/forms\/order.xml", "hideFields":["checked_out","checked_out_time","params","language" ,"upload_pdf"], "ignoreChanges":["modified_by", "modified", "checked_out", "checked_out_time"], "convertToInt":["publish_up", "publish_down"], "displayLookup":[{"sourceColumn":"catid","targetTable":"#__categories","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"group_id","targetTable":"#__usergroups","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"created_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"access","targetTable":"#__viewlevels","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"modified_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"}]}') AS tmp
WHERE NOT EXISTS (
	SELECT type_alias FROM `#__content_types` WHERE (`type_alias` = 'com_orders.order')
) LIMIT 1;
