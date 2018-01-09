CREATE TABLE IF NOT EXISTS `#__pricing` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`asset_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'FK to the #__assets table.',
	`catid` int(11) unsigned NOT NULL DEFAULT '0',
	`item_name` VARCHAR(255) NOT NULL COMMENT 'Item Name',
	`item_image` VARCHAR(255) NOT NULL COMMENT 'Item Image',
	`item_dimensions` VARCHAR(255) NOT NULL COMMENT 'Item Dimensions',
	`size` VARCHAR(255) NOT NULL COMMENT 'Size',
	`cover` VARCHAR(255) NOT NULL COMMENT 'Cover',
	`paper` VARCHAR(255) NOT NULL COMMENT 'Paper',
	`no_pages` INT(255) NOT NULL COMMENT 'No of Pages',
	`price` INT(100) NOT NULL COMMENT 'Price',
	`published` tinyint(3) NOT NULL DEFAULT '0',
	`checked_out` int(11) unsigned NOT NULL DEFAULT '0',
	`checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`created_by` int(11) unsigned NOT NULL DEFAULT '0',
	`modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`modified_by` int(11) unsigned NOT NULL DEFAULT '0',
	`publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`access` int(11) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (id)
)
CHARACTER SET utf8
COLLATE utf8_general_ci;


CREATE TABLE IF NOT EXISTS `#__paper_types` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`asset_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'FK to the #__assets table.',
	`paper_name` VARCHAR(255) NOT NULL COMMENT 'Paper Name',
	`paper_description` VARCHAR(255) NOT NULL COMMENT 'Paper Description',
	`ordering` int(11) NOT NULL DEFAULT '0',
	`published` tinyint(3) NOT NULL DEFAULT '0',
	`checked_out` int(11) unsigned NOT NULL DEFAULT '0',
	`checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`created_by` int(11) unsigned NOT NULL DEFAULT '0',
	`modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`modified_by` int(11) unsigned NOT NULL DEFAULT '0',
	`publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`access` int(11) unsigned NOT NULL DEFAULT '0',
	`params` text NOT NULL,
	PRIMARY KEY (id)
)
CHARACTER SET utf8
COLLATE utf8_general_ci;


CREATE TABLE IF NOT EXISTS `#__product_covers` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`asset_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'FK to the #__assets table.',
	`cover_name` VARCHAR(255) NOT NULL COMMENT 'Cover Name',
	`ordering` int(11) NOT NULL DEFAULT '0',
	`published` tinyint(3) NOT NULL DEFAULT '0',
	`checked_out` int(11) unsigned NOT NULL DEFAULT '0',
	`checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`created_by` int(11) unsigned NOT NULL DEFAULT '0',
	`modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`modified_by` int(11) unsigned NOT NULL DEFAULT '0',
	`publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`access` int(11) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (id)
)
CHARACTER SET utf8
COLLATE utf8_general_ci;
