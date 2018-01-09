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
   
class FaqBookProModelSections extends JModelLegacy
{ 
	var $utilities = null;
	
	function __construct() 
	{
		$this->utilities = $this->getUtilitiesLib();
		
	  	parent::__construct();
	}
	
	public function getUtilitiesLib()
	{
		$utilities = new FAQBookProLibUtilities;
		
		return $utilities;
	}
				
	public static function getSections()
	{
		$db = JFactory::getDbo();
		$user = JFactory::getUser();

		$query = $db->getQuery(true);
		
		$query->select('*')
			->from('#__minitek_faqbook_sections');
		$query->where('state = 1');
		$query->where('access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')');
		$query->order('ordering');
		
		$db->setQuery($query);
		
		$rows = $db->loadObjectList();
		if ($rows)
		{
			return $rows;	
		}
		else
		{
			return false;
		}
	}
}