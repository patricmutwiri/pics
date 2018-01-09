CREATE TABLE IF NOT EXISTS `#__minitek_faqbook_questions` (
 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `asset_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'FK to the #__assets table.',
 `title` varchar(500) NOT NULL DEFAULT '',
 `alias` varchar(500) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
 `introtext` mediumtext NOT NULL,
 `fulltext` mediumtext NOT NULL,
 `state` tinyint(3) NOT NULL DEFAULT '0',
 `answered` tinyint(3) unsigned NOT NULL DEFAULT '0',
 `topicid` int(10) unsigned NOT NULL DEFAULT '0',
 `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
 `created_by` int(10) unsigned NOT NULL DEFAULT '0',
 `created_by_alias` varchar(255) NOT NULL DEFAULT '',
 `created_by_email` varchar(255) NOT NULL DEFAULT '',
 `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
 `modified_by` int(10) unsigned NOT NULL DEFAULT '0',
 `checked_out` int(10) unsigned NOT NULL DEFAULT '0',
 `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
 `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
 `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
 `images` text NOT NULL,
 `urls` text NOT NULL,
 `attribs` varchar(5120) NOT NULL,
 `version` int(10) unsigned NOT NULL DEFAULT '1',
 `ordering` int(11) NOT NULL DEFAULT '0',
 `metakey` text NOT NULL,
 `metadesc` text NOT NULL,
 `access` int(10) unsigned NOT NULL DEFAULT '0',
 `hits` int(10) unsigned NOT NULL DEFAULT '0',
 `metadata` text NOT NULL,
 `featured` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Set if article is featured.',
 `language` char(7) NOT NULL COMMENT 'The language code for the article.',
 `xreference` varchar(50) NOT NULL COMMENT 'A reference to enable linkages to external data sets.',
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__minitek_faqbook_sections` (
 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
 `asset_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'FK to the #__assets table.',
 `title` varchar(255) NOT NULL,
 `alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
 `description` mediumtext NOT NULL,
 `state` tinyint(3) NOT NULL DEFAULT '0',
 `checked_out` int(10) unsigned NOT NULL DEFAULT '0',
 `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
 `access` int(10) unsigned NOT NULL DEFAULT '0',
 `attribs` text NOT NULL,
 `ordering` int(11) NOT NULL DEFAULT '0',
 `metadesc` varchar(1024) NOT NULL,
 `metakey` varchar(1024) NOT NULL,
 `metadata` varchar(2048) NOT NULL,
 `created_user_id` int(10) unsigned NOT NULL DEFAULT '0',
 `created_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
 `hits` int(10) unsigned NOT NULL DEFAULT '0',
 `language` char(7) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__minitek_faqbook_topics` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `asset_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'FK to the #__assets table.',
 `section_id` int(10) unsigned NOT NULL DEFAULT '0',
 `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
 `lft` int(11) NOT NULL DEFAULT '0',
 `rgt` int(11) NOT NULL DEFAULT '0',
 `level` int(10) unsigned NOT NULL DEFAULT '0',
 `path` varchar(255) NOT NULL DEFAULT '',
 `title` varchar(255) NOT NULL,
 `alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
 `description` mediumtext NOT NULL,
 `published` tinyint(1) NOT NULL DEFAULT '0',
 `checked_out` int(11) unsigned NOT NULL DEFAULT '0',
 `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
 `access` int(10) unsigned NOT NULL DEFAULT '0',
 `params` text NOT NULL,
 `metadesc` varchar(1024) NOT NULL COMMENT 'The meta description for the page.',
 `metakey` varchar(1024) NOT NULL COMMENT 'The meta keywords for the page.',
 `metadata` varchar(2048) NOT NULL COMMENT 'JSON encoded metadata properties.',
 `created_user_id` int(10) unsigned NOT NULL DEFAULT '0',
 `created_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
 `modified_user_id` int(10) unsigned NOT NULL DEFAULT '0',
 `modified_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
 `hits` int(10) unsigned NOT NULL DEFAULT '0',
 `language` char(7) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

INSERT INTO `#__minitek_faqbook_topics` (id,section_id,parent_id,lft,rgt,level,title,alias,published,access,language)
VALUES (1,0,0,0,0,0,'ROOT','root',1,1,'*');