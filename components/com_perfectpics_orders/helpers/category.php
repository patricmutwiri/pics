<?php
/**
 * @author		
 * @copyright	
 * @license		
 */

defined("_JEXEC") or die("Restricted access");

/**
 * Perfectpics_orders Component Category Tree
 *
 * @static
 * @package     Perfectpics_orders
 * @subpackage  Helpers
 */
class Perfectpics_ordersCategories extends JCategories
{
	/**
	 * Constructor
	 */
	public function __construct($options = array())
	{
		$options["extension"] = "com_perfectpics_orders";
		$options["table"] = "#__perfectpics_orders";
		$options["field"] = "catid";
		$options["key"] = "id";
		$options["statefield"] = "published";

		parent::__construct($options);
	}
}