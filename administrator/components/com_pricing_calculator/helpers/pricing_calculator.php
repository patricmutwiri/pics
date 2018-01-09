<?php
/**
 * @author		
 * @copyright	
 * @license		
 */

defined("_JEXEC") or die("Restricted access");

/**
 * Pricing_calculator helper class.
 *
 * @package     Pricing_calculator
 * @subpackage  Helpers
 */
class Pricing_calculatorHelper
{
	public static function addSubmenu($vName)
	{
		JHtmlSidebar::addEntry(
			JText::_('Pricing'), 
			'index.php?option=com_pricing_calculator&view=pricings', 
			$vName == 'pricings'
		);
		JHtmlSidebar::addEntry(
			JText::_('Paper Types'), 
			'index.php?option=com_pricing_calculator&view=paper_types', 
			$vName == 'paper_types'
		);
		JHtmlSidebar::addEntry(
			JText::_('Product Covers'), 
			'index.php?option=com_pricing_calculator&view=product_covers', 
			$vName == 'product_covers'
		);

		JHtmlSidebar::addEntry(
			JText::_('JCATEGORIES'), 
			'index.php?option=com_categories&extension=com_pricing_calculator', 
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

		$assetName = 'com_pricing_calculator';

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}
	

}