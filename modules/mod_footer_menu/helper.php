<?php
/**
 * @author		
 * @copyright	
 * @license		
 */

defined("_JEXEC") or die("Restricted access");

class ModFooter_menuHelper
{
	/**
	 * Get the item
	 *
	 * @return  object	The item.
	 */
	public static function getItem()
	{
		$input = JFactory::getApplication()->input;
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
	}
}
