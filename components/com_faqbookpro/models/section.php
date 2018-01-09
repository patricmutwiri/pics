<?php
/**
* @title			Minitek FAQ Book
* @copyright   		Copyright (C) 2011-2015 Minitek, All rights reserved.
* @license   		GNU General Public License version 3 or later.
* @author url   	http://www.minitek.gr/
* @developers   	Minitek.gr
*/

// no direct access
defined('_JEXEC') or die('Restricted access');
   
class FaqBookProModelSection extends JModelLegacy
{ 
	var $utilities = null;
	var $navigation = null;
	
	function __construct() 
	{
		$this->utilities = $this->getUtilitiesLib();
		$this->navigation = $this->getNavigationLib();
		
	  	parent::__construct();
	}
	
	public function getUtilitiesLib()
	{
		$utilities = new FAQBookProLibUtilities;
		
		return $utilities;
	}
	
	public function getNavigationLib()
	{
		$navigation = new FAQBookProLibUtilitiesNavigation;
		
		return $navigation;
	}
			
	public static function getSection($id)
	{
		$db = JFactory::getDBO();
		$query = 'SELECT * FROM '. $db->quoteName( '#__minitek_faqbook_sections' );
		$query .= ' WHERE ' . $db->quoteName( 'id' ) . ' = '. $db->quote($id).' ';
		$db->setQuery($query);
		$row = $db->loadObject();
		if ($row)
		{
			return $row;	
		}
		else
		{
			return false;
		}
	}
	
	public static function authorizeSection($id)
	{
		$db = JFactory::getDbo();
		$user = JFactory::getUser();

		$query = $db->getQuery(true);
		
		$query->select('*')
			->from('#__minitek_faqbook_sections');
		$query->where('id = '.$id);
		$query->where('access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')');
		
		$db->setQuery($query);
		
		$row = $db->loadObject();
		if ($row)
		{
			return true;	
		}
		else
		{
			return false;
		}
	}
		
	/* Get first-level topics in section */
	public function getSectionTopics($sectionId)
	{
		$db = JFactory::getDbo();
		$user = JFactory::getUser();

		$query = $db->getQuery(true);

		// Right join with c for topic
		$query->select('c.id, c.asset_id, c.access, c.alias, c.checked_out, c.checked_out_time,
			c.created_time, c.created_user_id, c.description, c.hits, c.language, c.level,
			c.lft, c.metadata, c.metadesc, c.metakey, c.modified_time, c.params, c.parent_id, c.section_id,
			c.path, c.published, c.rgt, c.title, c.modified_user_id');
		$case_when = ' CASE WHEN ';
		$case_when .= $query->charLength('c.alias', '!=', '0');
		$case_when .= ' THEN ';
		$c_id = $query->castAsChar('c.id');
		$case_when .= $query->concatenate(array($c_id, 'c.alias'), ':');
		$case_when .= ' ELSE ';
		$case_when .= $c_id . ' END as slug';
		$query->select($case_when)
			->from('#__minitek_faqbook_topics as c');
		$query->where('c.access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')');
		$query->where('c.published = 1');
		$query->where('c.level = 1');
		$query->where('c.section_id=' . (int) $sectionId);

		$query->order('c.lft');

		// Get the results
		$db->setQuery($query);
		$topics = $db->loadObjectList();
		
		return $topics;
	}
	
	/* Get first-level children topics in topic */
	public static function getTopicChildren($topicId)
	{
		$db = JFactory::getDbo();
		$user = JFactory::getUser();

		$query = $db->getQuery(true);

		// Right join with c for topic
		$query->select('c.id, c.asset_id, c.access, c.alias, c.checked_out, c.checked_out_time,
			c.created_time, c.created_user_id, c.description, c.hits, c.language, c.level,
			c.lft, c.metadata, c.metadesc, c.metakey, c.modified_time, c.params, c.parent_id, c.section_id,
			c.path, c.published, c.rgt, c.title, c.modified_user_id');
		$case_when = ' CASE WHEN ';
		$case_when .= $query->charLength('c.alias', '!=', '0');
		$case_when .= ' THEN ';
		$c_id = $query->castAsChar('c.id');
		$case_when .= $query->concatenate(array($c_id, 'c.alias'), ':');
		$case_when .= ' ELSE ';
		$case_when .= $c_id . ' END as slug';
		$query->select($case_when)
			->from('#__minitek_faqbook_topics as c');
		$query->where('c.access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')');
		$query->where('c.published = 1');
		$query->where('c.parent_id=' . (int) $topicId);

		$query->order('c.lft');

		// Get the results
		$db->setQuery($query);
		$topics = $db->loadObjectList('id');
		
		return $topics;
	}
	
	/*public static function getTopicTree($id)
	{	
		$subitems = self::getTopicChildren($id);
	
		$tree = array();
						
		if (count($subitems))
		{
			foreach ($subitems as $subitem)
			{
				$tree[] = $subitem;
				$tree[] = self::getTopicTree($subitem->id);
			}
		}
		
		return $tree;
	}*/
	
	public function getPopularTopics($sectionId, $limit)
	{
		$db = JFactory::getDbo();
		$user = JFactory::getUser();

		$query = $db->getQuery(true);

		// Right join with c for topic
		$query->select('c.id, c.asset_id, c.access, c.alias, c.checked_out, c.checked_out_time,
			c.created_time, c.created_user_id, c.description, c.hits, c.language, c.level,
			c.lft, c.metadata, c.metadesc, c.metakey, c.modified_time, c.params, c.parent_id, c.section_id,
			c.path, c.published, c.rgt, c.title, c.modified_user_id');
		$case_when = ' CASE WHEN ';
		$case_when .= $query->charLength('c.alias', '!=', '0');
		$case_when .= ' THEN ';
		$c_id = $query->castAsChar('c.id');
		$case_when .= $query->concatenate(array($c_id, 'c.alias'), ':');
		$case_when .= ' ELSE ';
		$case_when .= $c_id . ' END as slug';
		$query->select($case_when)
			->from('#__minitek_faqbook_topics as c');
		$query->where('c.access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')');
		$query->where('c.published = 1');
		$query->where('c.section_id=' . (int) $sectionId);
		$query->order('c.hits DESC');
		$query->setLimit($limit);

		// Get the results
		$db->setQuery($query);
		$topics = $db->loadObjectList();
		
		return $topics;
	}
	
	public function getPopularQuestions($sectionIid, $limit)
	{
		$db = JFactory::getDbo();
		$user = JFactory::getUser();
		
		$query = $db->getQuery(true);
		
		$query->select('a.id, a.title, a.alias, a.introtext, a.fulltext, a.checked_out, a.checked_out_time,
			a.topicid, a.created, a.created_by, a.created_by_alias, ' .
			// Use created if modified is 0
			'CASE WHEN a.modified = ' . $db->quote($db->getNullDate()) . ' THEN a.created ELSE a.modified END as modified, ' .
			'a.modified_by,' .
			// Use created if publish_up is 0
			'CASE WHEN a.publish_up = ' . $db->quote($db->getNullDate()) . ' THEN a.created ELSE a.publish_up END as publish_up,' .
			'a.publish_down, a.images, a.urls, a.attribs, a.metadata, a.metakey, a.metadesc, a.access, ' .
			'a.hits, a.xreference, a.featured,' . ' ' . $query->length('a.fulltext') . ' AS readmore'
		);
		$query->from('#__minitek_faqbook_questions AS a');
					
		// Join over the topics
		$query->select('c.title AS topic_title, c.path AS topic_route, c.access AS topic_access, c.alias AS topic_alias')
			->join('LEFT', '#__minitek_faqbook_topics AS c ON c.id = a.topicid');
			
		// Join over the sections
		$query->select('section.title as section_title, section.id as section_id')
			->join('LEFT', '#__minitek_faqbook_sections as section ON section.id = c.section_id');
			
		$groups = implode(',', $user->getAuthorisedViewLevels());
		$query->where('a.access IN (' . $groups . ')')
			->where('c.access IN (' . $groups . ')')
			->where('section.access IN (' . $groups . ')');
			
		$query->where('a.state = 1')
			->where('c.published = 1')
			->where('section.state = 1');
			
		$query->where('section.id = ' . $db->quote($sectionIid));
		$query->order('a.hits DESC');
		$query->setLimit($limit);
		
		// Get the results
		$db->setQuery($query);
		$questions = $db->loadObjectList();
		
		return $questions;
	}
			
}