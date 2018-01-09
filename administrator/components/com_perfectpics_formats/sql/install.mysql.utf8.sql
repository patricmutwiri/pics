CREATE TABLE IF NOT EXISTS `#__perfectpics_format` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`asset_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'FK to the #__assets table.',
	`catid` int(11) unsigned NOT NULL DEFAULT '0',
	`formats_icon` VARCHAR(255) NOT NULL COMMENT 'Select an Icon for the option',
	`format_title` VARCHAR(255) NOT NULL COMMENT 'Enter a title for the format option',
	`format_size` VARCHAR(255) NOT NULL COMMENT 'Enter a size for the format option',
	`price_from` VARCHAR(255) NOT NULL COMMENT 'Enter lowest price allowed',
	`pages` VARCHAR(255) NOT NULL COMMENT 'Enter lowest number of pages',
	`format_price` INT(100) NOT NULL COMMENT 'Enter Price',
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
	`images` text NOT NULL,
	`version` int(11) unsigned NOT NULL DEFAULT '1',
	`hits` int(11) NOT NULL DEFAULT '0',
	`access` int(11) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (id)
)
CHARACTER SET utf8
COLLATE utf8_general_ci;
