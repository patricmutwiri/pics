<?php
/**
 * @author		
 * @copyright	
 * @license		
 */

defined("_JEXEC") or die("Restricted access");

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
JHtml::_('behavior.caption');
echo JLayoutHelper::render('joomla.content.categories_default', $this);
echo $this->loadTemplate('items');
?>
</div>
