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

jimport('joomla.application.component.controller');
 
class FaqBookProControllerTopic extends JControllerLegacy 
{
	function __construct() 
	{		
		parent::__construct();	
	}
	
	public function getContent()
	{
		// Check session token
		JSession::checkToken('request') or jexit('error-token-topic');
		
		// Get input
		$app = JFactory::getApplication();
		$jinput = $app->input;
				
		// Get variables
		$id = $jinput->get('id', '', 'INT');
		$page = $jinput->get('page', '1', 'INT');
		
		// Set variables
		JRequest::setVar( 'view', 'topic' );
		JRequest::setVar( 'id', $id );
	
		// Set layout
		if ($page > 1)
		{
			$layout = 'topic_questions';
		}
		else
		{
			$layout = 'topic';
		}
		JRequest::setVar('layout', 'default_'.$layout);
		
		// Display
		parent::display();	
		jexit();
	}	
}