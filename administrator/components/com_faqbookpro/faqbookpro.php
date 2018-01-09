<?php
/**
* @title			FAQ Book Pro
* @copyright   		Copyright (C) 2011-2016 Minitek, All rights reserved.
* @license   		GNU General Public License version 3 or later.
* @author url   	https://www.minitek.gr/
* @developers   	Minitek.gr
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// Include dependancies
jimport('joomla.application.component.controller');

// Check component access
if (!JFactory::getUser()->authorise('core.manage', 'com_faqbookpro'))
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// Include basic helper
JLoader::register('FAQBookProHelper', JPATH_COMPONENT_ADMINISTRATOR. '/helpers/faqbookpro.php');
JLoader::register('FAQBookProHelperUtilities', JPATH_COMPONENT_ADMINISTRATOR. '/helpers/utilities.php');

// Add controller
$controller	= JControllerLegacy::getInstance('FAQBookPro');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();

$document = JFactory::getDocument();

// Add font
$font_tag = '<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,500,300italic,400italic,500italic,700,700italic,900" rel="stylesheet" type="text/css">';

// Add assets
if($document->getType() != 'raw') 
{
	$document->addCustomTag($font_tag);
	
	$document->addStyleSheet(JURI::root().'administrator/components/com_faqbookpro/assets/css/style.css?v=3.6.0');
	$document->addStyleSheet('https://netdna.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.css');
	
	// Add js
	JHtml::_('bootstrap.framework');
	$document->addScript(JURI::root().'administrator/components/com_faqbookpro/assets/js/script.js?v=3.6.0');
}

// Check for missing section menu items
$utilities = new FAQBookProHelperUtilities();
$sections = $utilities->getAllSectionsIds();
$app = JFactory::getApplication();
$view = $app->input->get('view', '');
if ($view == '' || $view == 'dashboard' || $view == 'sections' || $view == 'topics' || $view == 'questions' || $view == 'votes')
{
	$missing_sections = array();
	foreach($sections as $section)
	{
		if (!$utilities->getCheckSectionMenuItem($section->id))
		{
			$missing_sections[] = $section->id;
		}
	}
	
	if (count($missing_sections) > 0)
	{
		$menu_items_warning = JText::_('COM_FAQBOOKPRO_SECTION_MENU_ITEMS_MISSING_WARNING');
		$menu_items_warning .= '<br><br><h4>'.JText::_('COM_FAQBOOKPRO_SECTIONS').'</h4>';
		$menu_items_warning .= '<ul>';
		
		foreach ($missing_sections as $sectionId)
		{
			$sectionTitle = $utilities->getSectionTitle($sectionId)->title;
			$menu_items_warning .= '<li><span class="label label-warning">'.$sectionTitle.'</span></li>';
		}
		
		$menu_items_warning .= '</ul>';
		
		JFactory::getApplication()->enqueueMessage($menu_items_warning, 'warning');
	}
}
