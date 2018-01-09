<?php
/**
* @title			FAQ Book Pro
* @version   		3.x
* @copyright   		Copyright (C) 2011-2013 Minitek, All rights reserved.
* @license   		GNU General Public License version 3 or later.
* @author url   	http://www.minitek.gr/
* @author email   	info@minitek.gr
* @developers   	Minitek.gr
*/

// no direct access
defined('_JEXEC') or die;

class FAQBookProViewDashboard extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;

	public function display($tpl = null)
	{
		$utilities = new FAQBookProHelperUtilities();
		
		$this->navbar = $utilities->getNavbarHTML();
		$this->sidebar = $utilities->getSideMenuHTML();
				
		$this->addTitle();
		
		$this->checkOldFiles();
		
		parent::display($tpl);
	}
	
	protected function addTitle()
	{
		JToolbarHelper::title(JText::_('COM_FAQBOOKPRO_HOME'), '');
	}
	
	// Check component for leftover files (files older than 3.3.0)
	protected function checkOldFiles()
	{
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');
		
		$this->leftoverFolders = array();
		$this->leftoverFiles = array();
		
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
				$this->leftoverFolders[] = $folder;
				
				$folder_files = JFolder::files($folder, $filter = '.', true, true, '');
			
				foreach ($folder_files as $folder_file)
				{
					$this->leftoverFiles[] = $folder_file;
				}
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
				$this->leftoverFiles[] = $file;				
			}
		}
	}
	
}
