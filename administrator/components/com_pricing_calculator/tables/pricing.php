<?php
/**
 * @author		
 * @copyright	
 * @license		
 */

defined("_JEXEC") or die("Restricted access");

/**
 * Pricing table class.
 *
 * @package     Pricing_calculator
 * @subpackage  Tables
 */
class Pricing_calculatorTablePricing extends JTable
{
	/**
	 * Constructor
	 *
	 * @param   JDatabaseDriver  &$db  A database connector object
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__pricing', 'id', $db);
	}

	/**
     * Overloaded check function
     */
    public function check()
	{
		
		return parent::store($updateNulls);
	}

	/**
     * Define a namespaced asset name for inclusion in the #__assets table
	 *
     * @return	string	The asset name 
     *
     * @see JTable::_getAssetName 
     */
    protected function _getAssetName()
	{
        $k = $this->_tbl_key;
        return 'com_pricing_calculator.pricing.' . (int) $this->$k;
    }
	
	/**
	 * Define a title for the asset
	 *
	 * @return	string	The asset title
	 */
	protected function _getAssetTitle()
	{
		return $this->item_name;
	}
	
	/**
	 * Returns the parent asset's id. If you have a tree structure, retrieve the parent's id using the external key field
     *
     * @see JTable::_getAssetParentId
	 */
	protected function _getAssetParentId(JTable $table = null, $id = null)
	{
		$asset = JTable::getInstance('asset');
		$asset->loadByName('com_pricing_calculator.category.' . $this->catid);
		return $asset->id;
	}
}
?>