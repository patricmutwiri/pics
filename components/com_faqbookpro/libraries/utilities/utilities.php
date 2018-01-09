<?php
/**
* @title			Minitek FAQ Book
* @copyright   		Copyright (C) 2011-2015 Minitek, All rights reserved.
* @license   		GNU General Public License version 3 or later.
* @author url   	http://www.minitek.gr/
* @developers   	Minitek.gr
*/

// no direct access
defined('_JEXEC') or die ;

jimport('joomla.filesystem.folder');

class FaqBookProLibUtilities
{

	public static function getParams($option)
	{
		$application = JFactory::getApplication();
		if ($application->isSite())
		{
		  $params = $application->getParams($option);
		}
		else
		{
		  $params = JComponentHelper::getParams($option);
		}
		
		return $params;

	}
		
	/*public static function getCategoriesTreeString($item)
	{  
	  	$output = '';
		
		if (count($item->getChildren()))
		{			
		  	$subitems = $item->getChildren();
			
			$output .= $item->id.',';	
			foreach ($subitems as $subitem)
			{
			  	$output .= self::getCategoriesTreeString($subitem);
			}
		} 
		else 
		{
		  	$output .= $item->id.',';	
		}
	
		return $output;
	}*/
	
	public static function getWordLimit($text, $limit, $end_char = '&#8230;')
	{
	   if(JString::trim($text) == '')
			return $text;

		// always strip tags for text
		$text = strip_tags($text);

		$find = array(
			"/\r|\n/u",
			"/\t/u",
			"/\s\s+/u"
		);
		$replace = array(
			" ",
			" ",
			" "
		);
		$text = preg_replace($find, $replace, $text);

		preg_match('/\s*(?:\S*\s*){'.(int)$limit.'}/u', $text, $matches);
		if (JString::strlen($matches[0]) == JString::strlen($text))
			$end_char = '';
			
		return JString::rtrim($matches[0]).$end_char;
		
	}
			
	public static function resizeImage($width, $height, $path, $title)
	{
		$imageWidth = (int)$width;
		if (!$height) 
		{
    	 	$imageHeight = round($imageWidth * (3/4));
		} 
		else 
		{
			$imageHeight = $height;
		}
		$img = $path;
	
		// Render new image
		if ($img && $new_image = self::renderImages($img, $imageWidth, $imageHeight, $title)) 
		{
      		$img = $new_image;
    	}
		
		if (isset($img))
		  	$img = $img;
		
		return $img;
	}
	
	public static function makeDir($path)
	{
		$folders = explode ('/',  ($path));
		$tmppath =  JPATH_SITE.DS.'images'.DS.'faqbookpro'.DS;
		
		if(!file_exists($tmppath)) 
		{
			JFolder::create($tmppath, 0755);
		}
		
		for ($i = 0; $i < count ($folders) - 1; $i ++) 
		{
			if (!file_exists($tmppath . $folders [$i]) && ! JFolder::create($tmppath . $folders [$i], 0755)) 
			{
				return false;
			}	
			$tmppath = $tmppath . $folders [$i] . DS;
		}		
		
		return true;
	}
	
	public static function renderImages($path, $width, $height, $title='') 
	{	
	  	$path = str_replace(JURI::base(), '', $path);
		$imgSource = JPATH_SITE.DS. str_replace('/', DS,  $path);
		
		if (file_exists($imgSource)) 
		{
		  	$path =  $width."x".$height.'/'.$path;
			$thumbPath = JPATH_SITE.DS.'images'.DS.'faqbookpro'.DS. str_replace('/', DS,  $path);
			
			if (!file_exists($thumbPath)) 
			{
			  	$thumb = PhpThumbFactory::create($imgSource);  
				
				if (!self::makeDir($path)) 
				{
					return '';
				}		
				$thumb->adaptiveResize($width, $height);
				$thumb->save($thumbPath); 
			}
			$path = JURI::base().'images/faqbookpro/'.$path;
		} 
		
		return $path;
	}
	
	public static function getActions($component = '', $section = '', $id = 0)
	{
		$user    = JFactory::getUser();
		$result    = new JObject;

		$path = JPATH_ADMINISTRATOR . '/components/' . $component . '/access.xml';
		
		if ($section && $id)
		{
			$assetName = $component . '.' . $section . '.' . (int) $id;
		}
		else
		{
			$assetName = $component;
		}

		$actions = JAccess::getActionsFromFile($path, "/access/section[@name='component']/");
		
		foreach ($actions as $action)
		{
			$result->set($action->name, $user->authorise($action->name, $assetName));
		}
		
		return $result;
	}
	
	public static function getAuthorisedTopics($action)
	{
		// Brute force method: get all published topic rows for the component and check each one
		// TODO: Modify the way permissions are stored in the db to allow for faster implementation and better scaling
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('t.id AS id, a.name AS asset_name')
			->from('#__minitek_faqbook_topics AS t')
			->join('INNER', '#__assets AS a ON t.asset_id = a.id')
			->where('t.published = 1');
		$db->setQuery($query);
		$allTopics = $db->loadObjectList('id');
		$allowedTopics = array();

		foreach ($allTopics as $topic)
		{
			if (JFactory::getUser()->authorise($action, $topic->asset_name))
			{
				$allowedTopics[] = (int) $topic->id;
			}
		}

		return $allowedTopics;
	}
	
}
