<?php
/**
* @title			Minitek FAQ Book
* @copyright   		Copyright (C) 2011-2015 Minitek, All rights reserved.
* @license   		GNU General Public License version 3 or later.
* @author url   	http://www.minitek.gr/
* @developers   	Minitek.gr
*/

// no direct access
defined('_JEXEC') or die;

if(!defined('DS'))
{
	define('DS',DIRECTORY_SEPARATOR);
}

abstract class FaqBookProHelperRoute
{
	protected static $lookup = array();
	
	public static function getSectionsRoute($language = 0)
	{
		$link = 'index.php?option=com_faqbookpro&view=sections';
		
		$needles = array(
			'sections'  => 0
		);
		
		if ($language && $language != "*" && JLanguageMultilang::isEnabled())
		{
			$db		= JFactory::getDbo();
			$query	= $db->getQuery(true)
				->select('a.sef AS sef')
				->select('a.lang_code AS lang_code')
				->from('#__languages AS a');

			$db->setQuery($query);
			$langs = $db->loadObjectList();
			foreach ($langs as $lang)
			{
				if ($language == $lang->lang_code)
				{
					$link .= '&lang='.$lang->sef;
					$needles['language'] = $language;
				}
			}
		}

		if ($item = self::_findItem($needles))
		{
			$link .= '&Itemid='.$item;
		} 

		return $link;
	}
	
	public static function getSectionRoute($sectionid, $language = 0)
	{
		$id = (int) $sectionid;
		
		if ($id < 1)
		{
			$link = '';
		}
		else
		{
			$link = 'index.php?option=com_faqbookpro&view=section&id='.$id;
	
			$needles = array(
				'section' => (int)$id
			);
	
			if ($language && $language != "*" && JLanguageMultilang::isEnabled())
			{
				$db		= JFactory::getDbo();
				$query	= $db->getQuery(true)
					->select('a.sef AS sef')
					->select('a.lang_code AS lang_code')
					->from('#__languages AS a');
	
				$db->setQuery($query);
				$langs = $db->loadObjectList();
				foreach ($langs as $lang)
				{
					if ($language == $lang->lang_code)
					{
						$link .= '&lang='.$lang->sef;
						$needles['language'] = $language;
					}
				}
			}
	
			if ($item = self::_findItem($needles))
			{
				$link .= '&Itemid='.$item;
			}
		}

		return $link;
	}
	
	public static function getTopicRoute($topicid, $language = 0)
	{
		$id = (int) $topicid;

		if ($id < 1)
		{
			$link = '';
		}
		else
		{
			$link = 'index.php?option=com_faqbookpro&view=topic&id='.$id;

			$needles = array(
				'topic' => (int)$id
			);

			if ($language && $language != "*" && JLanguageMultilang::isEnabled())
			{
				$db		= JFactory::getDbo();
				$query	= $db->getQuery(true)
					->select('a.sef AS sef')
					->select('a.lang_code AS lang_code')
					->from('#__languages AS a');

				$db->setQuery($query);
				$langs = $db->loadObjectList();
				foreach ($langs as $lang)
				{
					if ($language == $lang->lang_code)
					{
						$link .= '&lang='.$lang->sef;
						$needles['language'] = $language;
					}
				}
			}

			if ($item = self::_findItem($needles))
			{
				$link .= '&Itemid='.$item;
			}
		}

		return $link;
	}
	
	public static function getQuestionRoute($id, $topicid = 0, $language = 0)
	{
		$link = 'index.php?option=com_faqbookpro&view=question&id='. $id;
		
		$needles = array(
			'question'  => (int)$id
		);
			
		if ($language && $language != "*" && JLanguageMultilang::isEnabled())
		{
			$db		= JFactory::getDbo();
			$query	= $db->getQuery(true)
				->select('a.sef AS sef')
				->select('a.lang_code AS lang_code')
				->from('#__languages AS a');

			$db->setQuery($query);
			$langs = $db->loadObjectList();
			foreach ($langs as $lang)
			{
				if ($language == $lang->lang_code)
				{
					$link .= '&lang='.$lang->sef;
					$needles['language'] = $language;
				}
			}
		}

		if ($item = self::_findItem($needles))
		{
			$link .= '&Itemid='.$item;
		}

		return $link;
	}
	
	public static function getMyQuestionsRoute($sectionid, $language = 0)
	{
		$link = 'index.php?option=com_faqbookpro&view=myquestions&section='.$sectionid;

		$needles = array(
			'myquestions'  => 0,
			'section' => (int)$sectionid
		);
		
		if ($language && $language != "*" && JLanguageMultilang::isEnabled())
		{
			$db		= JFactory::getDbo();
			$query	= $db->getQuery(true)
				->select('a.sef AS sef')
				->select('a.lang_code AS lang_code')
				->from('#__languages AS a');

			$db->setQuery($query);
			$langs = $db->loadObjectList();
			foreach ($langs as $lang)
			{
				if ($language == $lang->lang_code)
				{
					$link .= '&lang='.$lang->sef;
					$needles['language'] = $language;
				}
			}
		}

		if ($item = self::_findItem($needles))
		{
			$link .= '&Itemid='.$item;
		} 

		return $link;
	}
	
	public static function newQuestionRoute($sectionId, $language = 0)
	{
		$link = 'index.php?option=com_faqbookpro&task=myquestion.add&section='. $sectionId;

		$needles = array(
			'section' => (int)$sectionId
		);
		
		if ($language && $language != "*" && JLanguageMultilang::isEnabled())
		{
			$db		= JFactory::getDbo();
			$query	= $db->getQuery(true)
				->select('a.sef AS sef')
				->select('a.lang_code AS lang_code')
				->from('#__languages AS a');

			$db->setQuery($query);
			$langs = $db->loadObjectList();
			foreach ($langs as $lang)
			{
				if ($language == $lang->lang_code)
				{
					$link .= '&lang='.$lang->sef;
					$needles['language'] = $language;
				}
			}
		}

		if ($item = self::_findItem($needles))
		{
			$link .= '&Itemid='.$item;
		} 

		return $link;
	}
	
	public static function editQuestionRoute($id, $sectionId, $language = 0)
	{
		$link = 'index.php?option=com_faqbookpro&task=myquestion.edit&id='.$id.'&section='. $sectionId;

		$needles = array(
			'section' => (int)$sectionId
		);
		
		if ($language && $language != "*" && JLanguageMultilang::isEnabled())
		{
			$db		= JFactory::getDbo();
			$query	= $db->getQuery(true)
				->select('a.sef AS sef')
				->select('a.lang_code AS lang_code')
				->from('#__languages AS a');

			$db->setQuery($query);
			$langs = $db->loadObjectList();
			foreach ($langs as $lang)
			{
				if ($language == $lang->lang_code)
				{
					$link .= '&lang='.$lang->sef;
					$needles['language'] = $language;
				}
			}
		}

		if ($item = self::_findItem($needles))
		{
			$link .= '&Itemid='.$item;
		} 

		return $link;
	}
	
	private static $tree = null;
	private static $model = null;
	
	public static function _findItem($needles)
	{
		$component = JComponentHelper::getComponent('com_faqbookpro');
		$application = JFactory::getApplication();
		$menus = $application->getMenu('site', array());
		$language = isset($needles['language']) ? $needles['language'] : '*';
		
		$items = $menus->getItems('component_id', $component->id);
		$match = null;
		
		foreach ($needles as $needle => $id)
		{
			if (count($items))
			{
				foreach ($items as $item)
				{
					if ((@$item->query['view'] == $needle) && (@$item->query['id'] == $id))
					{
						$match = $item;
						$match_id = $match->id;
						break;
					}

					if (!is_null($match))
					{
						break;
					}
				}
			}
			if (!is_null($match))
			{
				break;
			}
		
			if (is_null($match))
			{
				// Try to detect any parent topic menu item for children topics without menu items
				if ($needle == 'topic')
				{
					require_once (JPATH_SITE.DS.'components'.DS.'com_faqbookpro'.DS.'models'.DS.'topic.php');
					$model = JModelLegacy::getInstance('Topic', 'FAQBookProModel'); 
					
					if (is_null(self::$tree))
					{
						self::$model = $model;
						self::$tree = $model->getTopicsTree();
					}
					$parents = self::$model->getTreePath(self::$tree, $id);
					
					if (is_array($parents))
					{
						foreach ($parents as $topicID)
						{
							if ($topicID != $id)
							{
								$match = self::_findItem(array('topic' => $topicID));
								if (!is_null($match))
								{
									$match_id = $match;
									break;
								}
							}
						}
					}
					// Try to detect any parent section menu item for topics without menu items
					if (is_null($match))
					{
						require_once (JPATH_SITE.DS.'components'.DS.'com_faqbookpro'.DS.'models'.DS.'topic.php');
						$model = JModelLegacy::getInstance('Topic', 'FAQBookProModel'); 
						$topicSection = $model->getTopic($id)->section_id;
						$match = self::_findItem(array('section' => $topicSection));
						$match_id = $match;
					}
				}
				
				// Try to detect any parent topic menu item for questions without menu items
				if ($needle == 'question')
				{
					$questionTopic = self::getfbpQuestion($id)->topicid;
					$match = self::_findItem(array('topic' => $questionTopic));
					$match_id = $match;
				}			
			}
		
		}
		
		if (isset($match_id)) 
		{
			return $match_id;
		}
		else
		{
			// Check if the active menuitem matches the requested language
			$active = $menus->getActive();
			
			if ($active
				&& $active->component == 'com_faqbookpro'
				&& ($language == '*' || in_array($active->language, array('*', $language)) || !JLanguageMultilang::isEnabled()))
			{
				return $active->id;
			}
	
			// If not found, return language specific home link
			$default = $menus->getDefault($language);
	
			return !empty($default->id) ? $default->id : null;	
		}
	}
	
	public static function getfbpQuestion($id)
	{
		$db = JFactory::getDBO();
		$query = 'SELECT * FROM '. $db->quoteName( '#__minitek_faqbook_questions' );
		$query .= ' WHERE ' . $db->quoteName( 'id' ) . ' = '. $db->quote($id).' ';
		$query .= ' AND ' . $db->quoteName( 'state' ) . ' = '. $db->quote(1).' ';
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

}