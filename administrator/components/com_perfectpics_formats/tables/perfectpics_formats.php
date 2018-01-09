<?php
/**
 * @author		
 * @copyright	
 * @license		
 */

defined("_JEXEC") or die("Restricted access");

/**
 * PerfectPics_Formats table class.
 *
 * @package     Perfectpics_formats
 * @subpackage  Tables
 */
class Perfectpics_formatsTablePerfectPics_Formats extends JTable
{
	/**
	 * Constructor
	 *
	 * @param   JDatabaseDriver  &$db  A database connector object
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__perfectpics_format', 'id', $db);
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
        return 'com_perfectpics_formats.perfectpics_formats.' . (int) $this->$k;
    }
	
	/**
	 * Define a title for the asset
	 *
	 * @return	string	The asset title
	 */
	protected function _getAssetTitle()
	{
		return $this->formats_icon;
	}
	
	/**
	 * Returns the parent asset's id. If you have a tree structure, retrieve the parent's id using the external key field
     *
     * @see JTable::_getAssetParentId
	 */
	protected function _getAssetParentId(JTable $table = null, $id = null)
	{
		$asset = JTable::getInstance('asset');
		$asset->loadByName('com_perfectpics_formats.category.' . $this->catid);
		return $asset->id;
	}
}
?>