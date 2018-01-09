<?php
/**
* @title			Minitek FAQ Book
* @copyright   		Copyright (C) 2011-2013 Minitek, All rights reserved.
* @license   		GNU General Public License version 3 or later.
* @author url   	http://www.minitek.gr/
* @developers   	Minitek.gr
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class FaqBookProModelTopic extends JModelLegacy
{ 
	var $utilities = null;
	var $navigation = null;
	protected $_nodes;
	
	public function __construct() 
	{
		$this->utilities = $this->getUtilitiesLib();
		$this->navigation = $this->getNavigationLib();
		
		parent::__construct();
	}
	
	public function getUtilitiesLib()
	{
		require_once( JPATH_SITE.DS.'components'.DS.'com_faqbookpro'.DS.'libraries'.DS.'utilities'.DS.'utilities.php' );
		$utilities = new FAQBookProLibUtilities;
		
		return $utilities;
	}
	
	public function getNavigationLib()
	{
		require_once( JPATH_SITE.DS.'components'.DS.'com_faqbookpro'.DS.'libraries'.DS.'utilities'.DS.'navigation.php' );
		$navigation = new FAQBookProLibUtilitiesNavigation;
		
		return $navigation;
	}
		
	public static function getTopic($id)
	{
		$db = JFactory::getDBO();
		$query = 'SELECT * FROM '. $db->quoteName( '#__minitek_faqbook_topics' );
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
	
	public static function authorizeTopic($id)
	{
		$db = JFactory::getDbo();
		$user = JFactory::getUser();

		$query = $db->getQuery(true);
		
		$query->select('*')
			->from('#__minitek_faqbook_topics');
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
		
	public function getTopicQuestions($topicId, $ordering, $ordering_dir, $page)
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
						
		$groups = implode(',', $user->getAuthorisedViewLevels());
		$query->where('a.access IN (' . $groups . ')')
			->where('c.access IN (' . $groups . ')');
			
		$query->where('a.state = 1')
			->where('c.published = 1');
		
		$query->where('a.topicid = ' . $db->quote($topicId));
		$query->order('a.'.$ordering.' '.$ordering_dir);
		
		// Page limit
		$db->setQuery($query);
		
		$questions = $db->loadObjectList();
		
		return $questions;
	}
				
	public static function addHit($id)
	{	
	    $db = JFactory::getDBO();
		$query = " UPDATE `#__minitek_faqbook_topics` "
			." SET hits = hits + 1 "
			." WHERE id = ".$db->Quote($id)." ";
    	$db->setQuery($query);
    	$db->query();			
	}
	
	public function getTopicsTree()
	{
		$mainframe = JFactory::getApplication();
		$clientID = $mainframe->getClientId();
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$aid = (int)$user->get('aid');

		$query = "SELECT id, title,  parent_id	FROM #__minitek_faqbook_topics";
		if ($mainframe->isSite())
		{
			$query .= " WHERE published=1 AND level>0 ";
			$query .= " AND access IN(".implode(',', $user->getAuthorisedViewLevels()).")";
			if ($mainframe->getLanguageFilter())
			{
				$query .= " AND language IN(".$db->Quote(JFactory::getLanguage()->getTag()).", ".$db->Quote('*').")";
			}
		}
		$query .= " ORDER BY parent_id ";
		$db->setQuery($query);
		
		$topics = $db->loadObjectList();
		$tree = array();
	
		return $this->buildTree($topics);
	}
	
	public function buildTree(array &$topics, $parent = 1)
	{
		$branch = array();

		foreach ($topics as &$topic)
		{
			if ($topic->parent_id == $parent)
			{
				$children = $this->buildTree($topics, $topic->id);
				if ($children)
				{
					$topic->children = $children;
				}
				$branch[$topic->id] = $topic;
			}
		}
		
		return $branch;
	}
	
	public function getTreePath($tree, $id)
	{
		if (array_key_exists($id, $tree))
		{
			return array($id);
		}
		else
		{
			foreach ($tree as $key => $root)
			{
				if (isset($root->children) && is_array($root->children))
				{
					$retry = $this->getTreePath($root->children, $id);

					if ($retry)
					{
						$retry[] = $key;
						return $retry;
					}
				}
			}
		}

		return null;
	}
	
}