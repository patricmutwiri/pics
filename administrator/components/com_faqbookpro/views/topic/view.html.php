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

class FAQBookProViewTopic extends JViewLegacy
{
	protected $form;
	protected $item;
	protected $state;

	public function display($tpl = null)
	{
		$this->form = $this->get('Form');
		$this->item = $this->get('Item');
		$this->state = $this->get('State');
		$this->canDo = FAQBookProHelperUtilities::getActions('com_faqbookpro', 'topic', $this->item->id);

		$input = JFactory::getApplication()->input;

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));

			return false;
		}
		
		// Get javascript variables
		$document = JFactory::getDocument();
		$document->addScriptDeclaration('window.mfbvars = {
			token: "'.JSession::getFormToken().'",
			site_path: "'.JURI::base().'",
		};');

		$input->set('hidemainmenu', true);
		
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
		$input = JFactory::getApplication()->input;
		$user = JFactory::getUser();
		$userId = $user->get('id');

		$isNew = ($this->item->id == 0);
		$checkedOut = !($this->item->checked_out == 0 || $this->item->checked_out == $userId);

		// Check to see if the type exists
		$ucmType = new JUcmType;
		$this->typeId = $ucmType->getTypeId('com_faqbookpro.topic');

		$componentParams = JComponentHelper::getParams('com_faqbookpro');

		// Need to load the menu language file as mod_menu hasn't been loaded yet.
		$lang = JFactory::getLanguage();
		$lang->load('com_faqbookpro', JPATH_BASE, null, false, true)
		|| $lang->load('com_faqbookpro', JPATH_ADMINISTRATOR . '/components/com_faqbookpro', null, false, true);

		// Load the topics helper.
		//require_once JPATH_COMPONENT . '/helpers/topics.php';

		// Get the results for each action.
		$canDo = $this->canDo;

		$title = JText::_('COM_FAQBOOKRPO_TOPIC_' . ($isNew ? 'ADD' : 'EDIT') . '_TITLE');

		// Prepare the toolbar.
		JToolbarHelper::title(
			$title,
			'folder topic-' . ($isNew ? 'add' : 'edit')
				. ' ' . substr('com_faqbookpro', 4) . '-topic-' . ($isNew ? 'add' : 'edit')
		);

		// For new records, check the create permission.
		if ($isNew && (count(FAQBookProHelperUtilities::getAuthorisedTopics('core.create')) > 0))
		{
			JToolbarHelper::apply('topic.apply');
			JToolbarHelper::save('topic.save');
			JToolbarHelper::save2new('topic.save2new');
		}

		// If not checked out, can save the item.
		elseif (!$checkedOut && ($canDo->get('core.edit') || ($canDo->get('core.edit.own') && $this->item->created_user_id == $userId)))
		{
			JToolbarHelper::apply('topic.apply');
			JToolbarHelper::save('topic.save');

			if ($canDo->get('core.create'))
			{
				JToolbarHelper::save2new('topic.save2new');
			}
		}

		if (empty($this->item->id))
		{
			JToolbarHelper::cancel('topic.cancel');
		}
		else
		{
			if ($componentParams->get('save_history', 0) && $user->authorise('core.edit'))
			{
				$typeAlias = 'com_faqbookpro.topic';
				JToolbarHelper::versions($typeAlias, $this->item->id);
			}

			JToolbarHelper::cancel('topic.cancel', 'JTOOLBAR_CLOSE');
		}

	}
}
