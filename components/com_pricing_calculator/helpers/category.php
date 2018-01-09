<?php
/**
 * @author		
 * @copyright	
 * @license		
 */

defined("_JEXEC") or die("Restricted access");

/**
 * Pricing_calculator Component Category Tree
 *
 * @static
 * @package     Pricing_calculator
 * @subpackage  Helpers
 */
class Pricing_calculatorCategories extends JCategories
{
	/**
	 * Constructor
	 */
	public function __construct($options = array())
	{
		$options["extension"] = "com_pricing_calculator";
		$options["table"] = "#__pricing";
		$options["field"] = "catid";
		$options["key"] = "id";
		$options["statefield"] = "published";

		parent::__construct($options);
	}
}