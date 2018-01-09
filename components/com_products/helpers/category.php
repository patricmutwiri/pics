<?php
/**
 * @author		
 * @copyright	
 * @license		
 */

defined("_JEXEC") or die("Restricted access");

/**
 * Products Component Category Tree
 *
 * @static
 * @package     Products
 * @subpackage  Helpers
 */
class ProductsCategories extends JCategories
{
	/**
	 * Constructor
	 */
	public function __construct($options = array())
	{
		$options["extension"] = "com_products";
		$options["table"] = "#__logos";
		$options["field"] = "catid";
		$options["key"] = "id";
		$options["statefield"] = "published";

		parent::__construct($options);
	}
}