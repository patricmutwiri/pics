<?php
/**
 * @author		
 * @copyright	
 * @license		
 */

defined("_JEXEC") or die("Restricted access");

/**
 * Size table class.
 *
 * @package     Products
 * @subpackage  Tables
 */
class ProductsTableSize extends JTable
{
	/**
	 * Constructor
	 *
	 * @param   JDatabaseDriver  &$db  A database connector object
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__sizes', 'id', $db);
	}

	/**
     * Overloaded check function
     */
    public function check()
	{
        //If there is an ordering column and this is a new row then get the next ordering value
        if (property_exists($this, 'ordering') && $this->id == 0) {
            $this->ordering = self::getNextOrder();
        }

		
		return parent::store($updateNulls);
	}
}
?>