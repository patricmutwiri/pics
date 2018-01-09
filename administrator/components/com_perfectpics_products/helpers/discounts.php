<?php
/**
 * @author		
 * @copyright	
 * @license		
 */

defined("_JEXEC") or die("Restricted access");

/**
 * Discounts helper class.
 *
 * @package     Discounts
 * @subpackage  Helpers
 */
class DiscountsHelper
{
	public static function addSubmenu($vName)
	{
		JHtmlSidebar::addEntry(
			JText::_('COM_PERFECTPICS_PRODUCTS_SUBMENU_PERFECTPICS_PRODUCT'), 
			'index.php?option=com_perfectpics_products&view=perfectpics_products', 
			$vName == 'perfectpics_products'
		);

		JHtmlSidebar::addEntry(
			JText::_('JCATEGORIES'), 
			'index.php?option=com_categories&extension=com_perfectpics_products', 
			$vName == 'categories'
		);

		JHtmlSidebar::addEntry(
			JText::_('Discounts & Coupons'), 
			'index.php?option=com_perfectpics_products&view=discounts', 
			$vName == 'discounts'
		);

		JHtmlSidebar::addEntry(
			JText::_('Product Sizes'), 
			'index.php?option=com_perfectpics_products&view=productsizes', 
			$vName == 'productsizes'
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

		$assetName = 'com_perfectpics_products';

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}
	

}