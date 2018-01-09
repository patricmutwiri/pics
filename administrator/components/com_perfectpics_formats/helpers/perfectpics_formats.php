<?php
/**
 * @author
 * @copyright
 * @license
 */

defined("_JEXEC") or die("Restricted access");

/**
 * Perfectpics_formats helper class.
 *
 * @package     Perfectpics_formats
 * @subpackage  Helpers
 */
class Perfectpics_formatsHelper
{
	public static function addSubmenu($vName)
	{
		JHtmlSidebar::addEntry(
			JText::_('COM_PERFECTPICS_FORMATS_SUBMENU_PERFECTPICS_FORMAT'),
			'index.php?option=com_perfectpics_formats&view=perfectpics_formatss',
			$vName == 'perfectpics_formats'
		);

		JHtmlSidebar::addEntry(
			JText::_('JCATEGORIES'),
			'index.php?option=com_categories&extension=com_perfectpics_formats',
			$vName == 'categories'
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

		$assetName = 'com_perfectpics_formats';

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}


}
