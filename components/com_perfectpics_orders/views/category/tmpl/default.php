<?php
/**
 * @author		
 * @copyright	
 * @license		
 */

defined("_JEXEC") or die("Restricted access");

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers');
$this->subtemplatename = 'items';
echo JLayoutHelper::render('joomla.content.category_default', $this);
