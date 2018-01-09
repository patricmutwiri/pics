<?php
/**
 * @author		
 * @copyright	
 * @license		
 */

defined("_JEXEC") or die("Restricted access");

/**
 * Perfectpics_products Component Category Tree
 *
 * @static
 * @package     Perfectpics_products
 * @subpackage  Helpers
 */
class Perfectpics_productsCategories extends JCategories
{
	/**
	 * Constructor
	 */
	public function __construct($options = array())
	{
		$options["extension"] = "com_perfectpics_products";
		$options["table"] = "#__perfectpics_product";
		$options["field"] = "catid";
		$options["key"] = "id";
		$options["statefield"] = "published";

		parent::__construct($options);
	}
}