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

class FAQBookProViewQuestion extends JViewLegacy
{
	protected $form;
	protected $item;
	protected $state;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise a Error object.
	 *
	 * @since   1.6
	 */
	public function display($tpl = null)
	{
		$this->form  = $this->get('Form');
		$this->item  = $this->get('Item');
		$this->state = $this->get('State');
		$this->canDo = FAQBookProHelperUtilities::getActions('com_faqbookpro', 'question', $this->item->id);

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

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);
		$user       = JFactory::getUser();
		$userId     = $user->get('id');
		$isNew      = ($this->item->id == 0);
		$checkedOut = !($this->item->checked_out == 0 || $this->item->checked_out == $userId);

		// Built the actions for new and existing records.
		$canDo = $this->canDo;

		$title = JText::_('COM_FAQBOOKRPO_QUESTION_' . ($isNew ? 'ADD' : 'EDIT') . '');
		JToolbarHelper::title(
			$title,
			'question question-add'
		);

		// For new records, check the create permission.
		if ($isNew && (count(FAQBookProHelperUtilities::getAuthorisedTopics('core.create')) > 0))
		{
			JToolbarHelper::apply('question.apply');
			JToolbarHelper::save('question.save');
			JToolbarHelper::save2new('question.save2new');
			JToolbarHelper::cancel('question.cancel');
		}
		else
		{
			// Can't save the record if it's checked out.
			if (!$checkedOut)
			{
				// Since it's an existing record, check the edit permission, or fall back to edit own if the owner.
				if ($canDo->get('core.edit') || ($canDo->get('core.edit.own') && $this->item->created_by == $userId))
				{
					JToolbarHelper::apply('question.apply');
					JToolbarHelper::save('question.save');

					// We can save this record, but check the create permission to see if we can return to make a new one.
					if ($canDo->get('core.create'))
					{
						JToolbarHelper::save2new('question.save2new');
					}
				}
			}

			JToolbarHelper::cancel('question.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}
