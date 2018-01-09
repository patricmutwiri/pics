<?php
/**
* @title			Minitek FAQ Book
* @copyright   		Copyright (C) 2011-2015 Minitek, All rights reserved.
* @license   		GNU General Public License version 3 or later.
* @author url   	http://www.minitek.gr/
* @developers   	Minitek.gr
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\Registry\Registry;

jimport('joomla.application.component.view');
 
class FaqBookProViewSections extends JViewLegacy
{
  	function display($tpl = null) 
  	{
		$document = JFactory::getDocument();
	  	$app = JFactory::getApplication();	
		$this->model = $this->getModel();
		$activeMenu = $app->getMenu()->getActive();	
	
		// Get Params & Attribs
		$utilities = $this->model->utilities;
		$this->assignRef('utilities', $utilities);
		$params = $utilities->getParams('com_faqbookpro');
		$this->assignRef('params', $params);
		
		// Get Sections				
		$this->sections = $this->model->getSections();
		
		// Sections params
		$this->sections_topnav = $params->get('sections_topnav', true);
		$this->sections_page_title = $params->get('sections_page_title', false);	
		$this->sections_page_description = $params->get('sections_page_description', false);	
		$this->sections_cols = $params->get('sections_cols', 3);	
		$this->sections_title = $params->get('sections_title', 1);	
		$this->sections_description = $params->get('sections_description', 1);	
		
		// Set metadata
		$document->setTitle($params->get('page_title'));
		
		if ($params->get('menu-meta_description'))
		{
			$document->setDescription($params->get('menu-meta_description'));
		}
		
		if ($params->get('menu-meta_keywords'))
		{
			$document->setMetadata('keywords', $params->get('menu-meta_keywords'));
		}
		
		if ($params->get('robots'))
		{
			$document->setMetadata('robots', $params->get('robots'));
		}
		
		if (!is_object($params->get('metadata')))
		{
			$metadata = new Registry($params->get('metadata'));
		}
		
		$mdata = $metadata->toArray();

		foreach ($mdata as $k => $v)
		{
			if ($v)
			{
				$document->setMetadata($k, $v);
			}
		}
		
		// Menu page display options
		if ($params->get('page_heading'))
		{
		  	$params->set('page_title', $params->get('page_heading'));
		}
		$params->set('show_page_title', $params->get('show_page_heading'));
																									
		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
			  
		// Display the view
		parent::display($tpl);
					
  	}
}