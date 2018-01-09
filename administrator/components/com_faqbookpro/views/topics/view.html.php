<?php
/**
* @title			Minitek FAQ Book
* @copyright   		Copyright (C) 2011-2015 Minitek, All rights reserved.
* @license   		GNU General Public License version 3 or later.
* @author url   	http://www.minitek.gr/
* @developers   	Minitek.gr
*/

// no direct access
defined('_JEXEC') or die;

class FAQBookProViewTopics extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 */
	public function display($tpl = null)
	{
		$items = $this->get('Items');
		$this->state         = $this->get('State');
		$this->pagination    = $this->get('Pagination');
		$this->filterForm    = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');
		$model = $this->getModel();
		
		// Extra item fields
		foreach ($items as $key=>$item)
		{
			$this->items[] = $item;
		}

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));

			return false;
		}

		// Preprocess the list of items to find ordering divisions.
		if (count($this->items))
		{
			foreach ($this->items as &$item)
			{
				$this->ordering[$item->parent_id][] = $item->id;
			}
		}

		// Levels filter.
		$options   = array();
		$options[] = JHtml::_('select.option', '1', JText::_('J1'));
		$options[] = JHtml::_('select.option', '2', JText::_('J2'));
		$options[] = JHtml::_('select.option', '3', JText::_('J3'));
		$options[] = JHtml::_('select.option', '4', JText::_('J4'));
		$options[] = JHtml::_('select.option', '5', JText::_('J5'));
		$options[] = JHtml::_('select.option', '6', JText::_('J6'));
		$options[] = JHtml::_('select.option', '7', JText::_('J7'));
		$options[] = JHtml::_('select.option', '8', JText::_('J8'));
		$options[] = JHtml::_('select.option', '9', JText::_('J9'));
		$options[] = JHtml::_('select.option', '10', JText::_('J10'));

		$this->f_levels = $options;
		
		// Get Navbar & Sidebar
		$utilities = new FAQBookProHelperUtilities();
		$this->navbar = $utilities->getNavbarHTML();
		$this->sidebar = $utilities->getSideMenuHTML();
		
		// Get Toolbar
		$this->addToolbar();
		
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{
		$canDo = FAQBookProHelperUtilities::getActions('com_faqbookpro', 'topic', $this->state->get('filter.topic_id'));
		$user = JFactory::getUser();

		// Get the toolbar object instance
		$bar = JToolBar::getInstance('toolbar');

		// Need to load the menu language file as mod_menu hasn't been loaded yet.
		$lang = JFactory::getLanguage();
		$lang->load('com_faqbookpro', JPATH_BASE, null, false, true) || $lang->load('com_faqbookpro', JPATH_ADMINISTRATOR . '/components/com_faqbookpro', null, false, true);

		// Prepare the toolbar.
		JToolbarHelper::title(JText::_('COM_FAQBOOKPRO_TOPICS'), '');

		if ($canDo->get('core.create') || (count(FAQBookProHelperUtilities::getAuthorisedTopics('core.create'))) > 0)
		{
			JToolbarHelper::addNew('topic.add');
		}

		if ($canDo->get('core.edit') || $canDo->get('core.edit.own'))
		{
			JToolbarHelper::editList('topic.edit');
		}

		if ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::publish('topics.publish', 'JTOOLBAR_PUBLISH', true);
			JToolbarHelper::unpublish('topics.unpublish', 'JTOOLBAR_UNPUBLISH', true);
			JToolbarHelper::archiveList('topics.archive');
		}

		if (JFactory::getUser()->authorise('core.admin'))
		{
			JToolbarHelper::checkin('topics.checkin');
		}
		
		if ($canDo->get('core.admin'))
		{
			JToolbarHelper::custom('topics.rebuild', 'refresh.png', 'refresh_f2.png', 'JTOOLBAR_REBUILD', false);
			JToolbarHelper::custom('topics.rebuildroot', 'refresh.png', 'refresh_f2.png', 'COM_FAQBOOKPRO_REBUILD_ROOT_TOPIC', false);
		}

		if ($this->state->get('filter.published') == -2 && $canDo->get('core.delete', 'com_faqbookpro'))
		{
			JToolbarHelper::deleteList('', 'topics.delete', 'JTOOLBAR_EMPTY_TRASH');
		}
		elseif ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::trash('topics.trash');
		}

	}

	/**
	 * Returns an array of fields the table can be sorted by
	 *
	 * @return  array  Array containing the field name to sort by as the key and display text as value
	 *
	 * @since   3.0
	 */
	protected function getSortFields()
	{
		return array(
			'a.lft' => JText::_('JGRID_HEADING_ORDERING'),
			'a.published' => JText::_('JSTATUS'),
			'a.title' => JText::_('JGLOBAL_TITLE'),
			'a.access' => JText::_('JGRID_HEADING_ACCESS'),
			'language' => JText::_('JGRID_HEADING_LANGUAGE'),
			'a.id' => JText::_('JGRID_HEADING_ID')
		);
	}
}
