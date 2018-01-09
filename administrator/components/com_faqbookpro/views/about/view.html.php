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

class FAQBookProViewAbout extends JViewLegacy
{
	public function display($tpl = null)
	{
		$utilities = new FAQBookProHelperUtilities();
		
		$this->navbar = $utilities->getNavbarHTML();
		$this->sidebar = $utilities->getSideMenuHTML();
				
		$this->addTitle();
		
		parent::display($tpl);
	}

	protected function addTitle()
	{
		JToolbarHelper::title(JText::_('COM_FAQBOOKPRO_ABOUT'), '');
	}

}
