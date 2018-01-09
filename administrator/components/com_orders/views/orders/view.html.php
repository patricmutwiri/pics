<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Orders
 * @author     Michael Buluma <michael@buluma.me.ke>
 * @copyright  2016 Michael Buluma
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of Orders.
 *
 * @since  1.6
 */
class OrdersViewOrders extends JViewLegacy
{
	protected $items;

	protected $pagination;

	protected $state;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  Template name
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function display($tpl = null)
	{
		$this->state = $this->get('State');
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}

		OrdersHelpersOrders::addSubmenu('orders');

		$this->addToolbar();

		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return void
	 *
	 * @since    1.6
	 */
	protected function addToolbar()
	{
		$state = $this->get('State');
		$canDo = OrdersHelpersOrders::getActions();

		JToolBarHelper::title(JText::_('COM_ORDERS_TITLE_ORDERS'), 'orders.png');

		// Check if the form exists before showing the add/edit buttons
		$formPath = JPATH_COMPONENT_ADMINISTRATOR . '/views/order';

		if (file_exists($formPath))
		{
			if ($canDo->get('core.create'))
			{
				JToolBarHelper::addNew('order.add', 'JTOOLBAR_NEW');
				JToolbarHelper::custom('orders.duplicate', 'copy.png', 'copy_f2.png', 'JTOOLBAR_DUPLICATE', true);
			}

			if ($canDo->get('core.edit') && isset($this->items[0]))
			{
				JToolBarHelper::editList('order.edit', 'JTOOLBAR_EDIT');
			}
		}

		if ($canDo->get('core.edit.state'))
		{
			if (isset($this->items[0]->state))
			{
				JToolBarHelper::divider();
				JToolBarHelper::custom('orders.publish', 'publish.png', 'publish_f2.png', 'JTOOLBAR_PUBLISH', true);
				JToolBarHelper::custom('orders.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
			}
			elseif (isset($this->items[0]))
			{
				// If this component does not use state then show a direct delete button as we can not trash
				JToolBarHelper::deleteList('', 'orders.delete', 'JTOOLBAR_DELETE');
			}

			if (isset($this->items[0]->state))
			{
				JToolBarHelper::divider();
				JToolBarHelper::archiveList('orders.archive', 'JTOOLBAR_ARCHIVE');
			}

			if (isset($this->items[0]->checked_out))
			{
				JToolBarHelper::custom('orders.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
			}
		}

		// Show trash and delete for components that uses the state field
		if (isset($this->items[0]->state))
		{
			if ($state->get('filter.state') == -2 && $canDo->get('core.delete'))
			{
				JToolBarHelper::deleteList('', 'orders.delete', 'JTOOLBAR_EMPTY_TRASH');
				JToolBarHelper::divider();
			}
			elseif ($canDo->get('core.edit.state'))
			{
				JToolBarHelper::trash('orders.trash', 'JTOOLBAR_TRASH');
				JToolBarHelper::divider();
			}
		}

		if ($canDo->get('core.admin'))
		{
			JToolBarHelper::preferences('com_orders');
		}

		// Set sidebar action - New in 3.0
		JHtmlSidebar::setAction('index.php?option=com_orders&view=orders');

		$this->extra_sidebar = '';
		JHtmlSidebar::addFilter(

			JText::_('JOPTION_SELECT_PUBLISHED'),

			'filter_published',

			JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), "value", "text", $this->state->get('filter.state'), true)

		);
		//Filter for the field payment_status
		$select_label = JText::sprintf('COM_ORDERS_FILTER_SELECT_LABEL', 'Payment Status');
		$options = array();
		$options[0] = new stdClass();
		$options[0]->value = "0";
		$options[0]->text = "Not Paid";
		$options[1] = new stdClass();
		$options[1]->value = "1";
		$options[1]->text = "Paid";
		JHtmlSidebar::addFilter(
			$select_label,
			'filter_payment_status',
			JHtml::_('select.options', $options , "value", "text", $this->state->get('filter.payment_status'), true)
		);

		//Filter for the field order_status
		$select_label = JText::sprintf('COM_ORDERS_FILTER_SELECT_LABEL', 'Order Status');
		$options = array();
		$options[0] = new stdClass();
		$options[0]->value = "0";
		$options[0]->text = "Pending";
		$options[1] = new stdClass();
		$options[1]->value = "1";
		$options[1]->text = "In Progress";
		$options[2] = new stdClass();
		$options[2]->value = "2";
		$options[2]->text = "Completed";
		$options[3] = new stdClass();
		$options[3]->value = "3";
		$options[3]->text = "Held";
		JHtmlSidebar::addFilter(
			$select_label,
			'filter_order_status',
			JHtml::_('select.options', $options , "value", "text", $this->state->get('filter.order_status'), true)
		);

	}

	/**
	 * Method to order fields 
	 *
	 * @return void 
	 */
	protected function getSortFields()
	{
		return array(
			'a.`id`' => JText::_('JGRID_HEADING_ID'),
			'a.`ordering`' => JText::_('JGRID_HEADING_ORDERING'),
			'a.`state`' => JText::_('JSTATUS'),
			'a.`customers_name`' => JText::_('COM_ORDERS_ORDERS_CUSTOMERS_NAME'),
			'a.`customers_email`' => JText::_('COM_ORDERS_ORDERS_CUSTOMERS_EMAIL'),
			'a.`customers_phone`' => JText::_('COM_ORDERS_ORDERS_CUSTOMERS_PHONE'),
			'a.`upload_pdf`' => JText::_('COM_ORDERS_ORDERS_UPLOAD_PDF'),
			'a.`book_size`' => JText::_('COM_ORDERS_ORDERS_BOOK_SIZE'),
			'a.`cover_type`' => JText::_('COM_ORDERS_ORDERS_COVER_TYPE'),
			'a.`paper_type`' => JText::_('COM_ORDERS_ORDERS_PAPER_TYPE'),
			'a.`book_title`' => JText::_('COM_ORDERS_ORDERS_BOOK_TITLE'),
			'a.`author_name`' => JText::_('COM_ORDERS_ORDERS_AUTHOR_NAME'),
			'a.`category_title`' => JText::_('COM_ORDERS_ORDERS_CATEGORY_TITLE'),
			'a.`payment_status`' => JText::_('COM_ORDERS_ORDERS_PAYMENT_STATUS'),
			'a.`order_status`' => JText::_('COM_ORDERS_ORDERS_ORDER_STATUS'),
		);
	}
}
