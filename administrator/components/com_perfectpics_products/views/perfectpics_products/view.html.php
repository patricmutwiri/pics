<?php
/**
 * @author		
 * @copyright	
 * @license		
 */

defined("_JEXEC") or die("Restricted access");

require_once JPATH_COMPONENT.'/helpers/perfectpics_products.php';

/**
 * PerfectPics_Products list view class.
 *
 * @package     Perfectpics_products
 * @subpackage  Views
 */
class Perfectpics_productsViewPerfectPics_Products extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
	
	public function display($tpl = null)
	{
		$this->items		 = $this->getModel()->getItems();
		$this->state		 = $this->getModel()->getState();
		$this->pagination	 = $this->getModel()->getPagination();
		$this->authors		 = $this->getModel()->getAuthors();
		$this->filterForm    = $this->getModel()->getFilterForm();
		$this->activeFilters = $this->getModel()->getActiveFilters();

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
			return false;
		}
		
		Perfectpics_productsHelper::addSubmenu('perfectpics_products');
		
		// We don't need toolbar in the modal window.
		if ($this->getLayout() !== 'modal')
		{
			$this->addToolbar();
			$this->sidebar = JHtmlSidebar::render();
		}
		
		parent::display($tpl);
	}
	
	/**
	 *	Method to add a toolbar
	 */
	protected function addToolbar()
	{
		$state	= $this->get('State');
		$canDo	= Perfectpics_productsHelper::getActions();
		$user	= JFactory::getUser();

		// Get the toolbar object instance
		$bar = JToolBar::getInstance('toolbar');
		
		JToolBarHelper::title(JText::_('COM_PERFECTPICS_PRODUCTS_PERFECTPICS_PRODUCT_VIEW_PERFECTPICS_PRODUCTS_TITLE'));
		
		if ($canDo->get('core.create') || (count($user->getAuthorisedCategories('com_perfectpics_products', 'core.create'))) > 0 )
		{
			JToolBarHelper::addNew('perfectpics_product.add','JTOOLBAR_NEW');
		}

		if (($canDo->get('core.edit') || $canDo->get('core.edit.own')) && isset($this->items[0]))
		{
			JToolBarHelper::editList('perfectpics_product.edit','JTOOLBAR_EDIT');
		}
		
		if ($canDo->get('core.edit.state'))
		{
            if (isset($this->items[0]->published))
			{
			    JToolBarHelper::divider();
				JToolbarHelper::publish('perfectpics_products.publish', 'JTOOLBAR_PUBLISH', true);
				JToolbarHelper::unpublish('perfectpics_products.unpublish', 'JTOOLBAR_UNPUBLISH', true);
            } 
			else if (isset($this->items[0]))
			{
                // Show a direct delete button
                JToolBarHelper::deleteList('', 'perfectpics_products.delete','JTOOLBAR_DELETE');
            }

            if (isset($this->items[0]->published))
			{
			    JToolBarHelper::divider();
			    JToolBarHelper::archiveList('perfectpics_products.archive','JTOOLBAR_ARCHIVE');
            }
            
			if (isset($this->items[0]->checked_out))
			{
				JToolbarHelper::checkin('perfectpics_products.checkin');
            }
		}
		
		// Show trash and delete for components that uses the state field
        if (isset($this->items[0]->published))
		{
		    if ($state->get('filter.published') == -2 && $canDo->get('core.delete'))
			{
			    JToolBarHelper::deleteList('', 'perfectpics_products.delete','JTOOLBAR_EMPTY_TRASH');
			    JToolBarHelper::divider();
		    }
			else if ($state->get('filter.published') != -2 && $canDo->get('core.edit.state'))
			{
			    JToolBarHelper::trash('perfectpics_products.trash','JTOOLBAR_TRASH');
			    JToolBarHelper::divider();
		    }
        }
		
		// Add a batch button
		if (isset($this->items[0]) && $user->authorise('core.create', 'com_contacts') && $user->authorise('core.edit', 'com_contacts') && $user->authorise('core.edit.state', 'com_contacts'))
		{
			JHtml::_('bootstrap.modal', 'collapseModal');
			$title = JText::_('JTOOLBAR_BATCH');

			// Instantiate a new JLayoutFile instance and render the batch button
			$layout = new JLayoutFile('joomla.toolbar.batch');

			$dhtml = $layout->render(array('title' => $title));
			$bar->appendButton('Custom', $dhtml, 'batch');
		}
		
		if ($canDo->get('core.admin'))
		{
			JToolBarHelper::preferences('com_perfectpics_products');
		}
	}
}
?>