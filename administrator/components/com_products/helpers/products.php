<?php
/**
 * @author		
 * @copyright	
 * @license		
 */

defined("_JEXEC") or die("Restricted access");

/**
 * Products helper class.
 *
 * @package     Products
 * @subpackage  Helpers
 */
class ProductsHelper
{
	public static function addSubmenu($vName)
	{
		JHtmlSidebar::addEntry(
			JText::_('COM_PRODUCTS_SUBMENU_LOGOS'), 
			'index.php?option=com_products&view=logos', 
			$vName == 'logos'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_PRODUCTS_SUBMENU_COVERS'), 
			'index.php?option=com_products&view=covers', 
			$vName == 'covers'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_PRODUCTS_SUBMENU_SIZES'), 
			'index.php?option=com_products&view=sizes', 
			$vName == 'sizes'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_PRODUCTS_SUBMENU_PAPERS'), 
			'index.php?option=com_products&view=papers', 
			$vName == 'papers'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_PRODUCTS_SUBMENU_END_SHEETS'), 
			'index.php?option=com_products&view=end_sheets', 
			$vName == 'end_sheets'
		);

		JHtmlSidebar::addEntry(
			JText::_('JCATEGORIES'), 
			'index.php?option=com_categories&extension=com_products', 
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

		$assetName = 'com_products';

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}
	

}