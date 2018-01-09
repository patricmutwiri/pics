CREATE TABLE IF NOT EXISTS `#__expert` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`asset_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'FK to the #__assets table.',
	`expert_name` VARCHAR(255) NOT NULL COMMENT 'Experts Name',
	`experts_image` VARCHAR(255) NOT NULL COMMENT 'Experts Image',
	`experts_location` VARCHAR(255) NOT NULL COMMENT 'Experts Location',
	`experts_desc` VARCHAR(255) NOT NULL COMMENT 'Experts Description',
	`experts_email` VARCHAR(100) NOT NULL COMMENT 'Experts Email',
	`experts_phone` VARCHAR(255) NOT NULL COMMENT 'Experts Phone',
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
	`version` int(11) unsigned NOT NULL DEFAULT '1',
	`hits` int(11) NOT NULL DEFAULT '0',
	`access` int(11) unsigned NOT NULL DEFAULT '0',
	`metadata` text NOT NULL,
	`metakey` text NOT NULL,
	`metadesc` text NOT NULL,
	PRIMARY KEY (id)
)
CHARACTER SET utf8
COLLATE utf8_general_ci;
