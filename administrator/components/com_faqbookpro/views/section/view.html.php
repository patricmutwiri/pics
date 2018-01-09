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

class FAQBookProViewSection extends JViewLegacy
{
	protected $form;
	protected $item;
	protected $state;

	public function display($tpl = null)
	{
		$this->form		= $this->get('Form');
		$this->item		= $this->get('Item');
		$this->state	= $this->get('State');
		$this->canDo 	= FAQBookProHelperUtilities::getActions('com_faqbookpro', 'section', $this->item->id);

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

		// Get toolbar
		$this->addToolbar();
		
		parent::display($tpl);
	}

	protected function addToolbar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);
		$user		= JFactory::getUser();
		$userId		= $user->get('id');
		$isNew		= ($this->item->id == 0);
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $userId);
		JToolbarHelper::title(JText::_('COM_FAQBOOKPRO_SECTION_'.($checkedOut ? 'VIEW_SECTION' : ($isNew ? 'ADD_SECTION' : 'EDIT_SECTION'))), '');

		// Built the actions for new and existing records.

		// For new records, check the create permission.
		if ($isNew && $this->canDo->get('core.create'))
		{
			JToolbarHelper::apply('section.apply');
			JToolbarHelper::save('section.save');
			JToolbarHelper::save2new('section.save2new');
			JToolbarHelper::cancel('section.cancel');
		}
		else
		{
			// Can't save the record if it's checked out.
			if (!$checkedOut)
			{
				// Since it's an existing record, check the edit permission, or fall back to edit own if the owner.
				if ($this->canDo->get('core.edit') || ($this->canDo->get('core.edit.own') && $this->item->created_user_id == $userId))
				{
					JToolbarHelper::apply('section.apply');
					JToolbarHelper::save('section.save');

					// We can save this record, but check the create permission to see if we can return to make a new one.
					if ($this->canDo->get('core.create'))
					{
						JToolbarHelper::save2new('section.save2new');
					}
				}
			}

			JToolbarHelper::cancel('section.cancel', 'JTOOLBAR_CLOSE');
		}

	}
}
