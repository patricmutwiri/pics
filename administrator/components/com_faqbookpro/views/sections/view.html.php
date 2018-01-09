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

class FAQBookProViewSections extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;

	public function display($tpl = null)
	{
		$items = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->state = $this->get('State');
		$this->filterForm = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');
		$model = $this->getModel();
		
		// Extra item fields
		foreach ($items as $key=>$item)
		{
			//$item->topics_count = $model->getTopicsCount($item->id);
			
			$this->items[] = $item;
		}
				
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		// Get Navbar & Sidebar
		$utilities = new FAQBookProHelperUtilities();
		$this->navbar = $utilities->getNavbarHTML();
		$this->sidebar = $utilities->getSideMenuHTML();
		
		// Get Toolbar
		$this->addToolbar();

		parent::display($tpl);
	}

	protected function addToolbar()
	{
		$sectionId = $this->state->get('filter.section_id');
		$canDo = FAQBookProHelperUtilities::getActions('com_faqbookpro', 'section', $sectionId);
		$user  = JFactory::getUser();

		// Get the toolbar object instance
		$bar = JToolBar::getInstance('toolbar');

		JToolbarHelper::title(JText::_('COM_FAQBOOKPRO_SECTIONS'), '');

		if ($canDo->get('core.create'))
		{
			JToolbarHelper::addNew('section.add');
		}

		if (($canDo->get('core.edit')) || ($canDo->get('core.edit.own')))
		{
			JToolbarHelper::editList('section.edit');
		}
		
		if ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::publish('sections.publish', 'JTOOLBAR_PUBLISH', true);
			JToolbarHelper::unpublish('sections.unpublish', 'JTOOLBAR_UNPUBLISH', true);
			JToolbarHelper::archiveList('sections.archive');
			JToolbarHelper::checkin('sections.checkin');
		}

		if ($this->state->get('filter.published') == -2 && $canDo->get('core.delete'))
		{
			JToolbarHelper::deleteList('', 'sections.delete', 'JTOOLBAR_EMPTY_TRASH');
		}
		elseif ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::trash('sections.trash');
		}

		JHtmlSidebar::setAction('index.php?option=com_faqbookpro&view=sections');

	}
	
	protected function getSortFields()
	{
		return array(
			'a.published' => JText::_('JSTATUS'),
			'a.title' => JText::_('JGLOBAL_TITLE'),
			'a.access' => JText::_('JGRID_HEADING_ACCESS'),
			'language' => JText::_('JGRID_HEADING_LANGUAGE'),
			'a.id' => JText::_('JGRID_HEADING_ID')
		);
	}

}
