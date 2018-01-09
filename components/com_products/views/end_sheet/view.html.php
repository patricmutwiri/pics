<?php
/**
 * @author		
 * @copyright	
 * @license		
 */

defined("_JEXEC") or die("Restricted access");
 
/**
 * End_sheet item view class.
 *
 * @package     Products
 * @subpackage  Views
 */
class ProductsViewEnd_sheet extends JViewLegacy
{
	protected $item;
	protected $form;
	protected $state;
	
	public function display($tpl = null)
	{
		$this->state 	= $this->get('State');
		$this->item 	= $this->get('Item');
		$this->form 	= $this->get('Form');

		$app = JFactory::getApplication();
		$user = JFactory::getUser();
		$levels = $user->getAuthorisedViewLevels();
		
		// Check if item is empty
		if (empty($this->item))
		{
			$app->redirect(JRoute::_('index.php?option=com_products&view=end_sheets'), JText::_('JERROR_NO_ITEMS_SELECTED'));
		}
		
		// Check item access
		if ($this->item->id && !in_array($this->item->access, $levels))
		{
			throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
		}
			
		// Is the user allowed to create an item?
		if (!$this->item->id && !$user->authorise("core.create", "com_products"))
		{
			throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
		}

		// Get menu params
		$menu = $app->getMenu();
		$active = $menu->getActive();
		
		if (is_object($active))
		{
			$this->state->params = $active->params;
		}

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
			return false;
		}		

		// Increment hits
		$model = $this->getModel();
		$model->hit($this->item->id);##{end_hits}##
		
		parent::display($tpl);
	}
}
?>