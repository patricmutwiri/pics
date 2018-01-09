<?php
/**
 * @author		
 * @copyright	
 * @license		
 */

defined("_JEXEC") or die("Restricted access");

require_once JPATH_COMPONENT.'/helpers/products.php';

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
		JFactory::getApplication()->input->set('hidemainmenu', true);
		
		$this->form  = $this->getModel()->getForm();
		$this->item  = $this->getModel()->getItem();
		$this->state = $this->getModel()->getState();
		
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
			return false;
		}
		
		if ($this->getLayout() == 'modal')
		{
		}

		$this->addToolbar();
		parent::display($tpl);
	}
	
	protected function addToolbar()
	{
		$user		= JFactory::getUser();
		$isNew		= ($this->item->id == 0);
		$canDo		= ProductsHelper::getActions();
		
		JToolBarHelper::title(JText::_('COM_PRODUCTS_END_SHEETS_VIEW_END_SHEET_TITLE'));

		if (isset($this->item->checked_out)) {
		    $checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
        } else {
            $checkedOut = false;
        }
		
		// If not checked out, can save the item.
		if (!$checkedOut && ($canDo->get('core.edit')||($canDo->get('core.create'))))
		{

			JToolBarHelper::apply('end_sheet.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('end_sheet.save', 'JTOOLBAR_SAVE');
		}
		if (!$checkedOut && ($canDo->get('core.create'))){
			JToolBarHelper::custom('end_sheet.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
		}
		// If an existing item, can save to a copy.
		if (!$isNew && $canDo->get('core.create')) {
			JToolBarHelper::custom('end_sheet.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
		}
		if (empty($this->item->id)) {
			JToolBarHelper::cancel('end_sheet.cancel', 'JTOOLBAR_CANCEL');
		}
		else {
			JToolBarHelper::cancel('end_sheet.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}
?>