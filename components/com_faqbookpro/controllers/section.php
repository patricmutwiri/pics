<?php
/**
* @title			Minitek FAQ Book
* @copyright   		Copyright (C) 2011-2016 Minitek, All rights reserved.
* @license   		GNU General Public License version 3 or later.
* @author url   	https://www.minitek.gr/
* @developers   	Minitek.gr
*/

// no direct access
defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.application.component.controller');
 
class FaqBookProControllerSection extends JControllerLegacy
{
  	function __construct() 
	{		
		parent::__construct();	
	}
	
	public function getContent()
	{
		// Check session token
		JSession::checkToken('request') or jexit('error-token');
		
		// Get input
		$app = JFactory::getApplication();
		$jinput = $app->input;
				
		// Get variables
		$sectionId = $jinput->get('id', '', 'INT');
	
		// Set variables
		JRequest::setVar( 'view', 'section' );
		JRequest::setVar( 'id', $sectionId );
		
		JRequest::setVar('layout', 'default_content');
		
		// Display
		parent::display();	
		jexit();
	}	
}