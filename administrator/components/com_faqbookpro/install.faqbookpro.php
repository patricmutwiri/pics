<?php
/**
* @title			Minitek FAQ Book Pro
* @copyright   		Copyright (C) 2011-2017 Minitek, All rights reserved.
* @license   		GNU General Public License version 3 or later.
* @author url   	https://www.minitek.gr/
* @developers   	Minitek.gr
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

if(!defined('DS')){
	define('DS',DIRECTORY_SEPARATOR);
}
 
class com_faqbookproInstallerScript
{
	/*
	 * $parent is the class calling this method.
	 * $type is the type of change (install, update or discover_install, not uninstall).
	 * preflight runs before anything else and while the extracted files are in the uploaded temp folder.
	 * If preflight returns false, Joomla will abort the update and undo everything already done.
	 */
	function preflight($type, $parent) 
	{
		if (is_object($this->getOldVersion()))
		{
			// Get old version
			$this->old_version = $this->getOldVersion()->version;
	
			// Get new version
			$this->release = $parent->get( 'manifest' )->version;
			
			// Abort if the old release is 1.x
			if ( isset($this->old_version) && $this->old_version && version_compare($this->old_version, '3.0.0', '<') ) 
			{
				Jerror::raiseWarning(null, 'Cannot install version <strong>'.$this->release.'</strong> over version <strong>1.x</strong>. Please uninstall version <strong>1.x</strong> first. All Minitek FAQ Book data will be lost.');
				return false;
			}
						
			// Run migration script if old release is older than 3.3.0
			if ( isset($this->old_version) && $this->old_version && version_compare($this->old_version, '3.3.0', '<') ) 
			{
				self::migrate($parent);
			}
		}
	}
	
	/*
	 * $parent is the class calling this method.
	 * install runs after the database scripts are executed.
	 * If the extension is new, the install method is run.
	 * If install returns false, Joomla will abort the install and undo everything already done.
	 */
	function install($parent) 
	{}
	
	/*
	 * $parent is the class calling this method.
	 * migrate runs if old version is older than 3.3.0.
	 */
	function migrate($parent) 
	{
		$user = JFactory::getUser();
		$userid = $user->id;
		$created = JFactory::getDate();
		$db = JFactory::getDbo();
		
		// Get new version
		$this->release = $parent->get( 'manifest' )->version;
	
		///////////////////////////////////////////////
		// Run migration
		///////////////////////////////////////////////	
				
		// 1. Create sections table
		$query = $db->getQuery(true);
		$query = " CREATE TABLE IF NOT EXISTS `#__minitek_faqbook_sections` (
				 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				 `asset_id` int(10) unsigned NOT NULL DEFAULT '0',
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
				) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8; ";
				
		$db->setQuery($query);
		$db->execute();
		
		// Die on error
		if($db->getErrorMsg()) { die('Error while creating sections database table.'); }
		
		// 2. Create first section (Help center)
		$query = $db->getQuery(true);
		$query->select('id');
		$query->from($db->quoteName('#__minitek_faqbook_sections'));
		$query->where($db->quoteName('id').' = '.$db->quote('1'));
		$db->setQuery($query);
		$first_section = $db->loadObject();
		
		if (!$first_section)
		{
			$query = $db->getQuery(true);
			$section_attribs = '{"leftnav":"1","user_questions":"0","show_popular_topics":"1","popular_topics_count":"6","popular_topics_cols":"3","popular_topics_title":"1","popular_topics_description":"0","popular_topics_description_limit":"15","popular_topics_image":"1","popular_topics_image_width":"300","popular_topics_image_height":"225","show_popular_questions":"1","popular_questions_count":"5","popular_questions_description":"1","popular_questions_description_limit":"15","show_all_topics":"1","all_topics_levels":"4","all_topics_cols":"3"}';
			$columns = array('id', 'title', 'alias', 'state', 'access', 'attribs', 'created_user_id', 'created_time', 'language');
			$values = array($db->quote('1'), $db->quote('Help Center'), $db->quote('help-center'), $db->quote(1), $db->quote(1), $db->quote($section_attribs), $db->quote($userid), $db->quote($created), $db->quote('*'));
			$query
				->insert($db->quoteName('#__minitek_faqbook_sections'))
				->columns($db->quoteName($columns))
				->values(implode(',', $values));
			 
			$db->setQuery($query);
			$db->execute();
			
			// Die on error
			if($db->getErrorMsg()) { die('Error while creating first section.'); }
		}
		
		// 3. Create topics table
		$query = $db->getQuery(true);
		$query = " CREATE TABLE IF NOT EXISTS `#__minitek_faqbook_topics` (
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
				) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8; ";
				
		$db->setQuery($query);
		$db->execute();
		
		// Die on error
		if($db->getErrorMsg()) { die('Error while creating topics database table.'); }
		
		// 4. Create root topic
		$query = $db->getQuery(true);
		$query->select('id');
		$query->from($db->quoteName('#__minitek_faqbook_topics'));
		$query->where($db->quoteName('id').' = '.$db->quote('1'));
		$db->setQuery($query);
		$root_topic = $db->loadObject();
		
		if (!$root_topic)
		{
			$query = $db->getQuery(true);
			$columns = array('id', 'section_id', 'parent_id', 'lft', 'rgt', 'level', 'title', 'alias', 'published', 'access', 'created_user_id', 'created_time', 'language');
			$values = array($db->quote(1), $db->quote(0), $db->quote(0), $db->quote(0), $db->quote(0), $db->quote(0), $db->quote('ROOT'), $db->quote('root'), $db->quote(1), $db->quote(1), $db->quote($userid), $db->quote($created), $db->quote('*'));
			$query
				->insert($db->quoteName('#__minitek_faqbook_topics'))
				->columns($db->quoteName($columns))
				->values(implode(',', $values));
			 
			$db->setQuery($query);
			$db->execute();
			
			// Die on error
			if($db->getErrorMsg()) { die('Error while creating root topic.'); }
		}
		
		// 5. Migrate categories to topics
		$query = $db->getQuery(true);
		$query->select('id');
		$query->from($db->quoteName('#__minitek_faqbook_topics'));
		$query->where($db->quoteName('id').' > 1');
		$db->setQuery($query);
		$existing_topics = $db->loadObjectList();
		
		if (!$existing_topics)
		{
			$query = $db->getQuery(true);
			$query->select('id, parent_id, lft, rgt, level, path, title, alias, description, published, access, metadesc, metakey, metadata, created_user_id, created_time, modified_user_id, modified_time, hits, language');
			$query->from($db->quoteName('#__categories'));
			$query->where($db->quoteName('extension').' = '.$db->quote('com_faqbookpro'));
			$db->setQuery($query);
			$categories = $db->loadObjectList();
			
			if ($categories)
			{
				foreach ($categories as $category)
				{
					$category_id = $category->id;
					$category_section_id = '1';
					$category_parent_id = $category->parent_id;
					$category_lft = $category->lft;	
					$category_rgt =	$category->rgt;
					$category_level = $category->level;	
					$category_path = $category->path;	
					$category_title = $category->title;	
					$category_alias = $category->alias;	
					$category_description =	$category->description;
					$category_published = $category->published;	
					$category_access = $category->access;	
					$category_metadesc = $category->metadesc;	
					$category_metakey =	$category->metakey;
					$category_metadata = $category->metadata;	
					$category_created_user_id =	$category->created_user_id;
					$category_created_time = $category->created_time;	
					$category_modified_user_id = $category->modified_user_id;	
					$category_modified_time = $category->modified_time;	
					$category_hits = $category->hits;	
					$category_language = $category->language;
					
					$query = $db->getQuery(true);
					$topic_params = '{"image":"","image_alt":"","topic_title":"","topic_description":"","topic_image":"","topic_imageSize":"","topic_imageHeight":"","show_subtopics":"","subtopics_title":"","subtopics_description":"","subtopics_image":"","subtopics_imageSize":"","subtopics_imageHeight":"","subtopics_faqs":""}';
					$columns = array('id', 'section_id', 'parent_id', 'lft', 'rgt', 'level', 'path', 'title', 'alias', 'description', 'published', 'access', 'params', 'metadesc', 'metakey', 'metadata', 'created_user_id', 'created_time', 'modified_user_id', 'modified_time', 'hits', 'language');
					$values = array(
						$db->quote($category_id), 
						$db->quote($category_section_id), 
						$db->quote($category_parent_id), 
						$db->quote($category_lft), 
						$db->quote($category_rgt), 
						$db->quote($category_level), 
						$db->quote($category_path), 
						$db->quote($category_title), 
						$db->quote($category_alias), 
						$db->quote($category_description), 
						$db->quote($category_published),
						$db->quote($category_access), 
						$db->quote($topic_params),
						$db->quote($category_metadesc), 
						$db->quote($category_metakey), 
						$db->quote($category_metadata), 
						$db->quote($category_created_user_id), 
						$db->quote($category_created_time), 
						$db->quote($category_modified_user_id), 
						$db->quote($category_modified_time),
						$db->quote($category_hits),
						$db->quote($category_language)
					);
					$query
						->insert($db->quoteName('#__minitek_faqbook_topics'))
						->columns($db->quoteName($columns))
						->values(implode(',', $values));
			 
					$db->setQuery($query);
					$db->execute();	
					
					// Die on error
					if($db->getErrorMsg()) { die('Error while migrating categories.'); }
				}
			}
		}
					
		// 6. Create questions table
		$query = $db->getQuery(true);
		$query = " CREATE TABLE IF NOT EXISTS `#__minitek_faqbook_questions` (
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
				) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8; ";
				
		$db->setQuery($query);
		$db->execute();
		
		// Die on error
		if($db->getErrorMsg()) { die('Error while creating questions database table.'); }
		
		// 7. Migrate articles to questions
		$query = $db->getQuery(true);
		$query->select('id');
		$query->from($db->quoteName('#__minitek_faqbook_questions'));
		$db->setQuery($query);
		$existing_questions = $db->loadObjectList();
		
		if (!$existing_questions)
		{
			$query = $db->getQuery(true);
			$query->select('id, title, alias, introtext, state, answered, catid, created, created_by, created_by_alias, modified, modified_by, publish_up, publish_down, images, version, ordering, metakey, metadesc, access, hits, metadata, featured, language');
			$query->from($db->quoteName('#__faqbookpro_content'));
			$db->setQuery($query);
			$articles = $db->loadObjectList();

			if ($articles)
			{
				foreach ($articles as $article)
				{
					$article_id = $article->id;
					$article_title = $article->title;
					$article_alias = $article->alias;
					$article_introtext = $article->introtext;	
					$article_state = $article->state;	
					$article_answered = $article->answered;	
					$article_topicid = $article->catid;	
					$article_created = $article->created;	
					$article_created_by = $article->created_by;
					$article_created_by_alias = $article->created_by_alias;	
					$article_modified = $article->modified;	
					$article_modified_by = $article->modified_by;	
					$article_publish_up = $article->publish_up;
					$article_publish_down = $article->publish_down;	
					$article_images = $article->images;
					$article_version = $article->version;	
					$article_ordering = $article->ordering;	
					$article_metakey = $article->metakey;	
					$article_metadesc = $article->metadesc;	
					$article_access = $article->access;	
					$article_hits = $article->hits;	
					$article_metadata = $article->metadata;	
					$article_featured = $article->featured;
					$article_language = $article->language;
					
					$query = $db->getQuery(true);
					if (!$article_images)
					{
						$article_images = '{"image_intro":"","image_intro_alt":"","image_fulltext":"","image_fulltext_alt":""}';
					}
					$article_attribs = '{"question_title":"","question_image":"","question_image_width":"","question_image_height":"","question_description":"","question_date":"","question_date_format":"","question_author":"","question_voting":"","question_editlink":""}';
					$columns = array('id', 'title', 'alias', 'introtext', 'state', 'answered', 'topicid', 'created', 'created_by', 'created_by_alias', 'modified', 'modified_by', 'publish_up', 'publish_down', 'images', 'attribs', 'version', 'ordering', 'metakey', 'metadesc', 'access', 'hits', 'metadata', 'featured', 'language');
					$values = array(
						$db->quote($article_id), 
						$db->quote($article_title), 
						$db->quote($article_alias), 
						$db->quote($article_introtext), 
						$db->quote($article_state), 
						$db->quote($article_answered), 
						$db->quote($article_topicid), 
						$db->quote($article_created), 
						$db->quote($article_created_by), 
						$db->quote($article_created_by_alias),
						$db->quote($article_modified), 
						$db->quote($article_modified_by), 
						$db->quote($article_publish_up), 
						$db->quote($article_publish_down), 
						$db->quote($article_images), 
						$db->quote($article_attribs), 
						$db->quote($article_version), 
						$db->quote($article_ordering), 
						$db->quote($article_metakey),
						$db->quote($article_metadesc),
						$db->quote($article_access),
						$db->quote($article_hits), 
						$db->quote($article_metadata),
						$db->quote($article_featured),
						$db->quote($article_language)
					);
					$query
						->insert($db->quoteName('#__minitek_faqbook_questions'))
						->columns($db->quoteName($columns))
						->values(implode(',', $values));
			 
					$db->setQuery($query);
					$db->execute();	
					
					// Die on error
					if($db->getErrorMsg()) { die('Error while migrating articles.'); }
				}
			}
		}
		
		// 8. Create votes table
		$query = $db->getQuery(true);
		$query = " CREATE TABLE IF NOT EXISTS `#__minitek_faqbook_votes` (
				 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				 `question_id` int(11) unsigned NOT NULL,
				 `user_id` int(10) unsigned DEFAULT NULL,
				 `user_ip` varchar(25) DEFAULT NULL,
				 `vote_up` smallint(5) unsigned NOT NULL DEFAULT '0',
				 `vote_down` smallint(5) unsigned NOT NULL DEFAULT '0',
				 `reason` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '1: Incorrect info, 2: Do not like, 3: Confusing, 4: Not an answer, 5: Too much, 6: Other',
				 `creation_date` datetime NOT NULL,
				 PRIMARY KEY (`id`)
				) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8; ";
				
		$db->setQuery($query);
		$db->execute();
		
		// Die on error
		if($db->getErrorMsg()) { die('Error while creating votes database table.'); }
		
		// 9. Migrate votes
		$query = $db->getQuery(true);
		$query->select('id');
		$query->from($db->quoteName('#__minitek_faqbook_votes'));
		$db->setQuery($query);
		$existing_votes = $db->loadObjectList();
		
		if (!$existing_votes)
		{
			$query = $db->getQuery(true);
			$query->select('id, faq_id, user_id, user_ip, vote_up, vote_down, reason, creation_date');
			$query->from($db->quoteName('#__faqbookpro_voting'));
			$db->setQuery($query);
			$votes = $db->loadObjectList();
			
			if ($votes)
			{
				foreach ($votes as $vote)
				{	
					$vote_id = $vote->id;
					$vote_question_id = $vote->faq_id;
					$vote_user_id = $vote->user_id;
					$vote_user_ip = $vote->user_ip;	
					$vote_vote_up = $vote->vote_up;	
					$vote_vote_down = $vote->vote_down;	
					$vote_reason = $vote->reason;	
					$vote_creation_date = $vote->creation_date;
				}
				
				$query = $db->getQuery(true);
					$columns = array('id', 'question_id', 'user_id', 'user_ip', 'vote_up', 'vote_down', 'reason', 'creation_date');
					$values = array(
						$db->quote($vote_id), 
						$db->quote($vote_question_id), 
						$db->quote($vote_user_id), 
						$db->quote($vote_user_ip), 
						$db->quote($vote_vote_up), 
						$db->quote($vote_vote_down), 
						$db->quote($vote_reason), 
						$db->quote($vote_creation_date)
					);
					$query
						->insert($db->quoteName('#__minitek_faqbook_votes'))
						->columns($db->quoteName($columns))
						->values(implode(',', $values));
			 
					$db->setQuery($query);
					$db->execute();	
					
					// Die on error
					if($db->getErrorMsg()) { die('Error while migrating votes.'); }
			}
		}
		
		// 10. Create email templates table
		$query = $db->getQuery(true);
		$query = " CREATE TABLE IF NOT EXISTS `#__minitek_faqbook_email_templates` (
				 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				 `asset_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'FK to the #__assets table.',
				 `template_key` varchar(255) NOT NULL DEFAULT '',
				 `title` varchar(255) NOT NULL DEFAULT '',
				 `description` mediumtext NOT NULL,
				 `subject` varchar(500) NOT NULL DEFAULT '',
				 `content` mediumtext NOT NULL,
				 `state` tinyint(3) NOT NULL DEFAULT '0',
				 `language` char(7) NOT NULL COMMENT 'The language code for the email template.',
				 `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
				 `checked_out` int(10) unsigned NOT NULL DEFAULT '0',
				 `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
				 PRIMARY KEY (`id`)
				) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8; ";
				
		$db->setQuery($query);
		$db->execute();
		
		// Die on error
		if($db->getErrorMsg()) { die('Error while creating email templates database table.'); }
		
		// 11. Create default email templates
		$query = $db->getQuery(true);
		$query->select('id');
		$query->from($db->quoteName('#__minitek_faqbook_email_templates'));
		$db->setQuery($query);
		$existing_templates = $db->loadObjectList();
		
		if (!$existing_templates)
		{
			$query = $db->getQuery(true);
			$columns = array('template_key', 'title', 'subject', 'content', 'state', 'language', 'created');
			$values = array($db->quote('new-question'), $db->quote('New Question'), $db->quote('New Question posted: [QUESTION_TITLE]'), $db->quote('<div style="background-color:#f0f0f0;padding:10px;text-align: left;"> <div style="background-color:#f9f9f9;padding:10px 15px"> Hello [RECIPIENT_NAME],<br><br> A new question has been posted in the topic <a target="_blank" style="font-weight:bold" href="[TOPIC_URL]">[TOPIC_TITLE]</a> by [AUTHOR_NAME].<br><br> <a target="_blank" style="font-weight:bold" href="[QUESTION_URL]">[QUESTION_TITLE]</a><br>	</div> </div>'), $db->quote(1), $db->quote('en-GB'), $db->quote($userid));
			$query
				->insert($db->quoteName('#__minitek_faqbook_email_templates'))
				->columns($db->quoteName($columns))
				->values(implode(',', $values));
			 
			$db->setQuery($query);
			$db->execute();
			
			// Die on error
			if($db->getErrorMsg()) { die('Error while creating default email template 1.'); }
			
			$query = $db->getQuery(true);
			$columns = array('template_key', 'title', 'subject', 'content', 'state', 'language', 'created');
			$values = array($db->quote('new-answer'), $db->quote('Answer'), $db->quote('Answer posted: [QUESTION_TITLE]'), $db->quote('<div style="background-color:#f0f0f0;padding:10px;text-align: left;">	<div style="background-color:#f9f9f9;padding:10px 15px"> Hello [RECIPIENT_NAME],<br><br> An answer has been posted to your question <a target="_blank" style="font-weight:bold" href="[QUESTION_URL]">[QUESTION_TITLE]</a><br> </div> </div>'), $db->quote(1), $db->quote('en-GB'), $db->quote($userid));
			$query
				->insert($db->quoteName('#__minitek_faqbook_email_templates'))
				->columns($db->quoteName($columns))
				->values(implode(',', $values));
			 
			$db->setQuery($query);
			$db->execute();
			
			// Die on error
			if($db->getErrorMsg()) { die('Error while creating default email template 2.'); }
		}
		
		echo '<p>' . JText::sprintf('Old FAQ Book data has been migrated to new version.') . '</p>';
	}
	
	/*
	 * $parent is the class calling this method.
	 * update runs after the database scripts are executed.
	 * If the extension exists, then the update method is run.
	 * If this returns false, Joomla will abort the update and undo everything already done.
	 */
	function update($parent) 
	{
		$user = JFactory::getUser();
		$userid = $user->id;
		$created = JFactory::getDate();
		$db = JFactory::getDbo();
		
		//////////////////////////////////////////////////////////////////
		// Update database tables
		//////////////////////////////////////////////////////////////////
										
		// 1. Add created_by_email column in _minitek_faqbook_questions table
		try
		{
			$query = $db->getQuery(true);
			$query = " ALTER TABLE `#__minitek_faqbook_questions` ADD `created_by_email` varchar(255) NOT NULL DEFAULT '' ";
					
			$db->setQuery($query);
			$db->execute();
		}
		catch (Exception $e)
		{
			//var_dump('sql error 1');
		}
	}
	
	/*
	 * $parent is the class calling this method.
	 * $type is the type of change (install, update or discover_install, not uninstall).
	 * postflight is run after the extension is registered in the database.
	 */
	function postflight($type, $parent) 
	{
	}
	
	/*
	 * $parent is the class calling this method
	 * uninstall runs before any other action is taken (file removal or database processing).
	 */
	function uninstall($parent) 
	{}
			
	private static function getOldVersion()
	{
		$db = JFactory::getDBO();
		$query = 'SELECT manifest_cache FROM '. $db->quoteName( '#__extensions' );
		$query .= ' WHERE ' . $db->quoteName( 'element' ) . ' = '. $db->quote('com_faqbookpro').' ';
		$db->setQuery($query);
		$row = $db->loadObject();
		
		if ($row)
		{
			$manifest_cache = json_decode($row->manifest_cache, false);
			return $manifest_cache;
		}
		else
		{
			return false;
		}
	}
}