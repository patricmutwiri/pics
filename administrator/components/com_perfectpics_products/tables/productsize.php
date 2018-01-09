<?php
/**
 * @author		
 * @copyright	
 * @license		
 */

defined("_JEXEC") or die("Restricted access");

/**
 * PerfectPics_Product table class.
 *
 * @package     Perfectpics_products
 * @subpackage  Tables
 */
class Perfectpics_productsTableProductsize extends JTable
{
	/**
	 * Constructor
	 *
	 * @param   JDatabaseDriver  &$db  A database connector object
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__productsizes', 'id', $db);
	}

	/**
	 * Method to bind an associative array or object to the JTable instance.  This
	 * method only binds properties that are publicly accessible and optionally
	 * takes an array of properties to ignore when binding.
	 *
	 * @param   array  $array   Named array
	 * @param   mixed  $ignore  An optional array or space separated list of properties
	 *                          to ignore while binding. [optional]
	 *
	 * @return  mixed  Null if operation was satisfactory, otherwise returns an error string
	 *
	 * @since   2.5
	 */
	public function bind($array, $ignore = '')
	{
		/*if (isset($array['products']) && is_array($array['products']))
		{
			$registry = new JRegistry;
			$registry->loadArray($array['products']);
			$array['products'] = (string) $registry;
		}*/

		return parent::bind($array, $ignore);
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
        return 'com_perfectpics_products.productsize.' . (int) $this->$k;
    }
	
	/**
	 * Define a title for the asset
	 *
	 * @return	string	The asset title
	 */
	protected function _getAssetTitle()
	{
		return $this->name;
	}
	
	/**
	 * Returns the parent asset's id. If you have a tree structure, retrieve the parent's id using the external key field
     *
     * @see JTable::_getAssetParentId
	 */
	protected function _getAssetParentId(JTable $table = null, $id = null)
	{
		$asset = JTable::getInstance('asset');
		$asset->loadByName('com_perfectpics_products.category.' . $this->catid);
		return $asset->id;
	}

}
?>