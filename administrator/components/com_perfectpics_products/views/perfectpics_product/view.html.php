<?php
/**
 * @author		
 * @copyright	
 * @license		
 */

defined("_JEXEC") or die("Restricted access");

require_once JPATH_COMPONENT.'/helpers/perfectpics_products.php';

/**
 * PerfectPics_Product item view class.
 *
 * @package     Perfectpics_products
 * @subpackage  Views
 */
class Perfectpics_productsViewPerfectPics_Product extends JViewLegacy
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
			$this->form->setFieldAttribute('catid', 'readonly', 'true');
		}

		$this->addToolbar();
		parent::display($tpl);
	}
	
	protected function addToolbar()
	{
		$user		= JFactory::getUser();
		$isNew		= ($this->item->id == 0);
		$canDo		= Perfectpics_productsHelper::getActions();
		
		JToolBarHelper::title(JText::_('COM_PERFECTPICS_PRODUCTS_PERFECTPICS_PRODUCT_VIEW_PERFECTPICS_PRODUCT_TITLE'));

		if (isset($this->item->checked_out)) {
		    $checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
        } else {
            $checkedOut = false;
        }
		
		// If not checked out, can save the item.
		if (!$checkedOut && ($canDo->get('core.edit')||($canDo->get('core.create'))))
		{

			JToolBarHelper::apply('perfectpics_product.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('perfectpics_product.save', 'JTOOLBAR_SAVE');
		}
		if (!$checkedOut && ($canDo->get('core.create'))){
			JToolBarHelper::custom('perfectpics_product.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
		}
		// If an existing item, can save to a copy.
		if (!$isNew && $canDo->get('core.create')) {
			JToolBarHelper::custom('perfectpics_product.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
		}
		if (empty($this->item->id)) {
			JToolBarHelper::cancel('perfectpics_product.cancel', 'JTOOLBAR_CANCEL');
		}
		else {
			JToolBarHelper::cancel('perfectpics_product.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}
?>