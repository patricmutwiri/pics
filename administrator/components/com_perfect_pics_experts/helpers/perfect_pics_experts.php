<?php
/**
 * @author		
 * @copyright	
 * @license		
 */

defined("_JEXEC") or die("Restricted access");

/**
 * Perfect_pics_experts helper class.
 *
 * @package     Perfect_pics_experts
 * @subpackage  Helpers
 */
class Perfect_pics_expertsHelper
{
	public static function addSubmenu($vName)
	{
		JHtmlSidebar::addEntry(
			JText::_('COM_PERFECT_PICS_EXPERTS_SUBMENU_EXPERT'), 
			'index.php?option=com_perfect_pics_experts&view=expertss', 
			$vName == 'expertss'
		);

	}
	
	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return	JObject
	 * @since	1.6
	 */
	public static function getActions()
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		$assetName = 'com_perfect_pics_experts';

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}
	

}