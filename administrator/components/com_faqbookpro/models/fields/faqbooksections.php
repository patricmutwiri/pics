<?php
/**
* @title		  	Minitek FAQ Book
* @copyright   		Copyright (C) 2011-2015 Minitek, All rights reserved.
* @license   		GNU General Public License version 3 or later.
* @author url   	http://www.minitek.gr/
* @developers   	Minitek.gr
*/

// no direct access
defined('_JEXEC') or die ;

JFormHelper::loadFieldClass('list');

class JFormFieldFAQBookSections extends JFormFieldList
{
	protected $type = 'FAQBookSections';
	
    protected function getOptions()
    {
		$options = array();
		
		$topicLevel = $this->form->getValue('level');
		
		if ($topicLevel > 1)
		{
			JFactory::getDocument()->addScriptDeclaration('
				(function($) {
					$(function(){   
						jQuery(\'#jform_section_id_chzn\')
							.addClass(\'section-disabled\')
							.end()
						;
						jQuery(\'#jform_section_id_chzn\').find(\'.chzn-drop\').hide();
					})
				})(jQuery);	
			');
		} 
		
		$db = JFactory::getDBO();
		$query = 'SELECT s.id as value, s.title as text FROM #__minitek_faqbook_sections s WHERE state = 1 ORDER BY s.title';
		$db->setQuery($query);
		$sections = $db->loadObjectList();
	
		foreach ($sections as $section)
		{
			$options[] = JHTML::_('select.option', $section->value, $section->text);
		}
		
		$options = array_merge(parent::getOptions(), $options);
		
		return $options;
    }
	
}

