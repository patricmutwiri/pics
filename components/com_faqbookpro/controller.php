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
 
// import Joomla controller library
jimport('joomla.application.component.controller');
 
// Add libraries prefix
JLoader::registerPrefix('FAQBookProLib', JPATH_SITE .DS. 'components' .DS. 'com_faqbookpro' .DS. 'libraries');

class FaqBookProController extends JControllerLegacy 
{
	function display($cachable = false, $urlparams = false) 
	{
        // Make sure we have a default view
        if( !JRequest::getVar( 'view' )) 
		{
            JRequest::setVar('view', 'section' );
        }
        parent::display();
    }

}