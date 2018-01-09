<?php
/**
* @title			FAQ Book Pro
* @copyright   		Copyright (C) 2011-2016 Minitek, All rights reserved.
* @license   		GNU General Public License version 3 or later.
* @author url   	https://www.minitek.gr/
* @developers   	Minitek.gr
*/

// no direct access
defined('_JEXEC') or die;

if(!defined('DS')){
	define('DS',DIRECTORY_SEPARATOR);
}

class FAQBookProController extends JControllerLegacy
{
	protected $default_view = 'dashboard';

	public function display($cachable = false, $urlparams = false)
	{	
		$view   = $this->input->get('view', 'articles');
		$layout = $this->input->get('layout', 'articles');
		$id     = $this->input->getInt('id');

		// Check for edit form.
		if ($view == 'article' && $layout == 'edit' && !$this->checkEditId('com_faqbookpro.edit.article', $id))
		{
			// Somehow the person just went to the form - we don't allow that.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_faqbookpro&view=articles', false));

			return false;
		}

		parent::display();

		return $this;
	}
	
	public function purgeImages()
	{
		jimport('joomla.filesystem.folder');
		JSession::checkToken('request') or jexit('Invalid token');		
		$app = JFactory::getApplication();
		$user = JFactory::getUser();
		
		if ($user->authorise('core.manage', 'com_faqbookpro')) 
		{
			$tmppath =  JPATH_SITE.DS.'images'.DS.'faqbookpro'.DS;
			
			if(file_exists($tmppath)) 
			{
				JFolder::delete($tmppath);
				$message = JText::_('COM_FAQBOOKPRO_IMAGES_PURGED');
				$link = JRoute::_('index.php?option=com_faqbookpro');
				$app->redirect(str_replace('&amp;', '&', $link), $message);	
			} 
			else 
			{
				$message = JText::_('COM_FAQBOOKPRO_IMAGES_PURGED_ALREADY');
				$link = JRoute::_('index.php?option=com_faqbookpro');
				$app->redirect(str_replace('&amp;', '&', $link), $message);	
			}
		} 
		else 
		{
			$app->enqueueMessage(JText::_('COM_FAQBOOKPRO_PURGE_NOT_ALLOWED'), 'error');
		}			
	}
	
	public function deleteOldFiles()
	{
		JSession::checkToken('request') or jexit('Invalid token');		
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');
		$app = JFactory::getApplication();
		
		$leftoverFolders = array();
		$leftoverFiles = array();
		
		$folders = array();		
		$folders[] = JPATH_ROOT.''.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_faqbookpro'.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'article';
		$folders[] = JPATH_ROOT.''.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_faqbookpro'.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'articles';
		$folders[] = JPATH_ROOT.''.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_faqbookpro'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'phpthumb';
		$folders[] = JPATH_ROOT.''.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_faqbookpro'.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'article';
		$folders[] = JPATH_ROOT.''.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_faqbookpro'.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'category';
		$folders[] = JPATH_ROOT.''.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_faqbookpro'.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'faqbook';
		$folders[] = JPATH_ROOT.''.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_faqbookpro'.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'item';
		$folders[] = JPATH_ROOT.''.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_faqbookpro'.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'search';

		foreach ($folders as $folder)
		{
			if (JFolder::exists($folder))
			{
				$leftoverFolders[] = $folder;
				
				$folder_files = JFolder::files($folder, $filter = '.', true, true, '');
			
				foreach ($folder_files as $folder_file)
				{
					$leftoverFiles[] = $folder_file;
				}
			}
		}
		
		$totalFolders = count($leftoverFolders);
		 
		// Delete folders
		foreach ($leftoverFolders as $leftoverFolder)
		{
			if (!JFolder::delete($leftoverFolder))
			{
				$message = JText::_('COM_FAQBOOKPRO_ADMIN_ACTION_FOLDERS_NOT_DELETED_CHECK_PERMISSIONS');
				$link = JRoute::_('index.php?option=com_faqbookpro');
				$app->redirect(str_replace('&amp;', '&', $link), $message);	
			}
		}

		$files = array();
		$files[] = JPATH_ROOT.''.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_faqbookpro'.DIRECTORY_SEPARATOR.'controllers'.DIRECTORY_SEPARATOR.'article.php';
		$files[] = JPATH_ROOT.''.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_faqbookpro'.DIRECTORY_SEPARATOR.'controllers'.DIRECTORY_SEPARATOR.'articles.php';
		$files[] = JPATH_ROOT.''.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_faqbookpro'.DIRECTORY_SEPARATOR.'controllers'.DIRECTORY_SEPARATOR.'vote.php';
		$files[] = JPATH_ROOT.''.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_faqbookpro'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'faqbookpro.php';
		$files[] = JPATH_ROOT.''.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_faqbookpro'.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'article.php';
		$files[] = JPATH_ROOT.''.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_faqbookpro'.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'articles.php';
		$files[] = JPATH_ROOT.''.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_faqbookpro'.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'vote.php';
		$files[] = JPATH_ROOT.''.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_faqbookpro'.DIRECTORY_SEPARATOR.'tables'.DIRECTORY_SEPARATOR.'content.php';
		$files[] = JPATH_ROOT.''.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_faqbookpro'.DIRECTORY_SEPARATOR.'tables'.DIRECTORY_SEPARATOR.'vote.php';
		$files[] = JPATH_ROOT.''.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_faqbookpro'.DIRECTORY_SEPARATOR.'controllers'.DIRECTORY_SEPARATOR.'article.php';
		$files[] = JPATH_ROOT.''.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_faqbookpro'.DIRECTORY_SEPARATOR.'controllers'.DIRECTORY_SEPARATOR.'category.php';
		$files[] = JPATH_ROOT.''.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_faqbookpro'.DIRECTORY_SEPARATOR.'controllers'.DIRECTORY_SEPARATOR.'faqbook.php';
		$files[] = JPATH_ROOT.''.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_faqbookpro'.DIRECTORY_SEPARATOR.'controllers'.DIRECTORY_SEPARATOR.'item.php';
		$files[] = JPATH_ROOT.''.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_faqbookpro'.DIRECTORY_SEPARATOR.'controllers'.DIRECTORY_SEPARATOR.'search.php';
		$files[] = JPATH_ROOT.''.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_faqbookpro'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'category.php';
		$files[] = JPATH_ROOT.''.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_faqbookpro'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'navigation.php';
		$files[] = JPATH_ROOT.''.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_faqbookpro'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'utilities.php';
		$files[] = JPATH_ROOT.''.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_faqbookpro'.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'article.php';
		$files[] = JPATH_ROOT.''.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_faqbookpro'.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'category.php';
		$files[] = JPATH_ROOT.''.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_faqbookpro'.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'faqbook.php';
		$files[] = JPATH_ROOT.''.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_faqbookpro'.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'item.php';
		$files[] = JPATH_ROOT.''.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_faqbookpro'.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'search.php';
		
		foreach ($files as $file)
		{
			if (file_exists($file))
			{
				$leftoverFiles[] = $file;				
			}
		}
		
		$totalFiles = count($leftoverFiles);

		// Delete files
		foreach ($leftoverFiles as $leftoverFile)
		{
			if (!JFile::delete($leftoverFile))
			{
				$message = JText::_('COM_FAQBOOKPRO_ADMIN_ACTION_FILES_NOT_DELETED_CHECK_PERMISSIONS');
				$link = JRoute::_('index.php?option=com_faqbookpro');
				$app->redirect(str_replace('&amp;', '&', $link), $message);	
			}
		}
		
		// Show success message
		$message = JText::sprintf('COM_FAQBOOKPRO_ADMIN_ACTION_N_FILES_N_FOLDERS_DELETED', $totalFolders, $totalFiles);
		$link = JRoute::_('index.php?option=com_faqbookpro');
		$app->redirect(str_replace('&amp;', '&', $link), $message);	
	}
}
