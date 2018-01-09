<?php
/**
 * @author		
 * @copyright	
 * @license		
 */

defined("_JEXEC") or die("Restricted access");

/**
 * Perfectpics_formats Component Category Tree
 *
 * @static
 * @package     Perfectpics_formats
 * @subpackage  Helpers
 */
class Perfectpics_formatsCategories extends JCategories
{
	/**
	 * Constructor
	 */
	public function __construct($options = array())
	{
		$options["extension"] = "com_perfectpics_formats";
		$options["table"] = "#__perfectpics_format";
		$options["field"] = "catid";
		$options["key"] = "id";
		$options["statefield"] = "published";

		parent::__construct($options);
	}
}