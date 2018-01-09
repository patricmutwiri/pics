CREATE TABLE IF NOT EXISTS `#__logos` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`asset_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'FK to the #__assets table.',
	`catid` int(11) unsigned NOT NULL DEFAULT '0',
	`title` VARCHAR(255) NOT NULL COMMENT 'title',
	`details` VARCHAR(255) NOT NULL COMMENT 'details',
	`logo_image` VARCHAR(255) NOT NULL COMMENT 'Logo Image',
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


CREATE TABLE IF NOT EXISTS `#__covers` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`asset_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'FK to the #__assets table.',
	`cover_name` VARCHAR(255) NOT NULL COMMENT 'Cover Name',
	`product_highlight` VARCHAR(255) NOT NULL COMMENT 'Product Highlight',
	`categories` VARCHAR(255) NOT NULL COMMENT 'Categories',
	`cover_image` VARCHAR(255) NOT NULL COMMENT 'cover_image',
	`cover_type` VARCHAR(255) NOT NULL COMMENT 'Cover Type',
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


CREATE TABLE IF NOT EXISTS `#__sizes` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`categories` VARCHAR(255) NOT NULL COMMENT 'categories',
	`size_title` VARCHAR(255) NOT NULL COMMENT 'Size Title',
	`size_dimensions` VARCHAR(255) NOT NULL COMMENT 'Dimensions',
	`no_pages` VARCHAR(255) NOT NULL COMMENT 'No of Pages',
	`price` VARCHAR(255) NOT NULL COMMENT 'price',
	`page_range` VARCHAR(255) NOT NULL COMMENT 'Page Range',
	`size_image` VARCHAR(255) NOT NULL COMMENT 'Image',
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


CREATE TABLE IF NOT EXISTS `#__papers` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`asset_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'FK to the #__assets table.',
	`title` VARCHAR(255) NOT NULL COMMENT 'title',
	`description` VARCHAR(255) NOT NULL COMMENT 'description',
	`categories` VARCHAR(255) NOT NULL COMMENT 'categories',
	`product_highlight` VARCHAR(255) NOT NULL COMMENT 'product_highlight',
	`paper_image` VARCHAR(255) NOT NULL COMMENT 'paper_image',
	`paper_price` INT(20) NOT NULL COMMENT 'Paper Price',
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


CREATE TABLE IF NOT EXISTS `#__end_sheets` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`asset_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'FK to the #__assets table.',
	`title` VARCHAR(255) NOT NULL COMMENT 'title',
	`description` VARCHAR(255) NOT NULL COMMENT 'description',
	`price` VARCHAR(255) NOT NULL COMMENT 'price',
	`product_highlight` VARCHAR(255) NOT NULL,
	`categories` VARCHAR(255) NOT NULL COMMENT 'categories',
	`end_sheet_image` VARCHAR(255) NOT NULL COMMENT 'End Sheet Image',
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
