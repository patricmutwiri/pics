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

if(!defined('DS'))
{
	define('DS',DIRECTORY_SEPARATOR);
}

JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');

// Require the base controller
require_once JPATH_COMPONENT.DS.'controller.php';
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'route.php' );

// Register helpers
JLoader::register('FaqBookProHelperUtilities', JPATH_COMPONENT.DS.'helpers'.DS.'utilities.php');
JLoader::register('FaqBookProHelperNavigation', JPATH_COMPONENT.DS.'helpers'.DS.'navigation.php');
jimport('joomla.filesystem.file');
jimport( 'joomla.application.component.helper' );
$params  = JComponentHelper::getParams('com_faqbookpro');

// Add stylesheets
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::base().'components/com_faqbookpro/assets/css/faqbook.css?v=3.5.1');
$document->addStyleSheet(JURI::base().'media/jui/css/icomoon.css');
if ($params->get('load_fontawesome', 1)) 
{
	$document->addStyleSheet('https://netdna.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.css');
}

// Load jQuery
if ($params->get('load_jquery', 1))
{
	JHtml::_('jquery.framework');
}

// Add scripts
$app = JFactory::getApplication();	
$view = $app->input->get('view');
if ($view == 'section' || $view == 'topic' || $view == 'question')
{
	$document->addScript(JURI::base().'components/com_faqbookpro/assets/js/faqbook.js?v=3.5.1');
}

// Add PHP Thumb Library
if ($params->get('load_phpthumb', 1))
{
	if( !defined('PhpThumbFactoryLoaded') ) 
	{
		require_once( JPATH_COMPONENT.DS.'libraries'.DS.'utilities'.DS.'phpthumb'.DS.'ThumbLib.inc.php' );
		define('PhpThumbFactoryLoaded',1);
	}
}

// Add controller
$controller	= JControllerLegacy::getInstance('FaqBookPro');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();