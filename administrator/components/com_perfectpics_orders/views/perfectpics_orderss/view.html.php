<?php
/**
 * @author		
 * @copyright	
 * @license		
 */

defined("_JEXEC") or die("Restricted access");

require_once JPATH_COMPONENT.'/helpers/perfectpics_orders.php';

/**
 * PerfectPics_Orderss list view class.
 *
 * @package     Perfectpics_orders
 * @subpackage  Views
 */
class Perfectpics_ordersViewPerfectPics_Orderss extends JViewLegacy
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
		
		Perfectpics_ordersHelper::addSubmenu('perfectpics_orderss');
		
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
		$canDo	= Perfectpics_ordersHelper::getActions();
		$user	= JFactory::getUser();

		// Get the toolbar object instance
		$bar = JToolBar::getInstance('toolbar');
		
		JToolBarHelper::title(JText::_('COM_PERFECTPICS_ORDERS_PERFECTPICS_ORDERS_VIEW_PERFECTPICS_ORDERSS_TITLE'));
		
		if ($canDo->get('core.create') || (count($user->getAuthorisedCategories('com_perfectpics_orders', 'core.create'))) > 0 )
		{
			JToolBarHelper::addNew('perfectpics_orders.add','JTOOLBAR_NEW');
		}

		if (($canDo->get('core.edit') || $canDo->get('core.edit.own')) && isset($this->items[0]))
		{
			JToolBarHelper::editList('perfectpics_orders.edit','JTOOLBAR_EDIT');
		}
		
		if ($canDo->get('core.edit.state'))
		{
            if (isset($this->items[0]->published))
			{
			    JToolBarHelper::divider();
				JToolbarHelper::publish('perfectpics_orderss.publish', 'JTOOLBAR_PUBLISH', true);
				JToolbarHelper::unpublish('perfectpics_orderss.unpublish', 'JTOOLBAR_UNPUBLISH', true);
            } 
			else if (isset($this->items[0]))
			{
                // Show a direct delete button
                JToolBarHelper::deleteList('', 'perfectpics_orderss.delete','JTOOLBAR_DELETE');
            }

            if (isset($this->items[0]->published))
			{
			    JToolBarHelper::divider();
			    JToolBarHelper::archiveList('perfectpics_orderss.archive','JTOOLBAR_ARCHIVE');
            }
            
			if (isset($this->items[0]->checked_out))
			{
				JToolbarHelper::checkin('perfectpics_orderss.checkin');
            }
		}
		
		// Show trash and delete for components that uses the state field
        if (isset($this->items[0]->published))
		{
		    if ($state->get('filter.published') == -2 && $canDo->get('core.delete'))
			{
			    JToolBarHelper::deleteList('', 'perfectpics_orderss.delete','JTOOLBAR_EMPTY_TRASH');
			    JToolBarHelper::divider();
		    }
			else if ($state->get('filter.published') != -2 && $canDo->get('core.edit.state'))
			{
			    JToolBarHelper::trash('perfectpics_orderss.trash','JTOOLBAR_TRASH');
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
			JToolBarHelper::preferences('com_perfectpics_orders');
		}
	}
}
?>