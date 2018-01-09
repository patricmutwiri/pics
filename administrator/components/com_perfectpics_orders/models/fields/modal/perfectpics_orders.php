<?php
/**
 * @author		
 * @copyright	
 * @license		
 */

defined("_JEXEC") or die("Restricted access");

/**
 * Supports a modal perfectpics_orders picker.
 *
 * @package     Perfectpics_orders
 * @subpackage  Modal fields
 */
class JFormFieldModal_PerfectPics_Orders extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since   1.6
	 */
	protected $type = 'Modal_PerfectPics_Orders';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string	The field input markup.
	 * @since   1.6
	 */
	protected function getInput()
	{
		$allowEdit		= ((string) $this->element['edit'] == 'true') ? true : false;
		$allowClear		= ((string) $this->element['clear'] != 'false') ? true : false;

		// Load language
		JFactory::getLanguage()->load('com_perfectpics_orders', JPATH_ADMINISTRATOR);

		// Load the javascript
		JHtml::_('behavior.framework');
		JHtml::_('behavior.modal', 'a.modal');
		JHtml::_('bootstrap.tooltip');

		// Build the script.
		$script = array();

		// Select button script
		$script[] = '	function jSelectPerfectPics_Orders_'.$this->id.'(id, name, object) {';
		$script[] = '		document.id("'.$this->id.'_id").value = id;';
		$script[] = '		document.id("'.$this->id.'_name").value = name;';

		if ($allowEdit)
		{
			$script[] = '		jQuery("#'.$this->id.'_edit").removeClass("hidden");';
		}

		if ($allowClear)
		{
			$script[] = '		jQuery("#'.$this->id.'_clear").removeClass("hidden");';
		}

		$script[] = '		SqueezeBox.close();';
		$script[] = '	}';

		// Clear button script
		static $scriptClear;

		if ($allowClear && !$scriptClear)
		{
			$scriptClear = true;

			$script[] = '	function jClearPerfectPics_Orders(id) {';
			$script[] = '		document.getElementById(id + "_id").value = "";';
			$script[] = '		document.getElementById(id + "_name").value = "'.htmlspecialchars(JText::_('COM_PERFECTPICS_ORDERS_SELECT_A_PERFECTPICS_ORDERS', true), ENT_COMPAT, 'UTF-8').'";';
			$script[] = '		jQuery("#"+id + "_clear").addClass("hidden");';
			$script[] = '		if (document.getElementById(id + "_edit")) {';
			$script[] = '			jQuery("#"+id + "_edit").addClass("hidden");';
			$script[] = '		}';
			$script[] = '		return false;';
			$script[] = '	}';
		}

		// Add the script to the document head.
		JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

		// Setup variables for display.
		$html	= array();
		$link	= 'index.php?option=com_perfectpics_orders&amp;view=perfectpics_orderss&amp;layout=modal&amp;tmpl=component&amp;function=jSelectPerfectPics_Orders_'.$this->id;

		if (isset($this->element['language']))
		{
			$link .= '&amp;forcedLanguage='.$this->element['language'];
		}

		// Get the title of the linked chart
		if ((int) $this->value > 0)
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select($db->quoteName('customers_name'))
				->from($db->quoteName('#__perfectpics_orders'))
				->where($db->quoteName('id') . ' = ' . (int) $this->value);
			$db->setQuery($query);

			try
			{
				$title = $db->loadResult();
			}
			catch (RuntimeException $e)
			{
				JError::raiseWarning(500, $e->getMessage);
			}
		}

		if (empty($title))
		{
			$title = JText::_('COM_PERFECTPICS_ORDERS_SELECT_A_PERFECTPICS_ORDERS');
		}
		$title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');

		// The active perfectpics_orders id field.
		if (0 == (int) $this->value)
		{
			$value = '';
		}
		else
		{
			$value = (int) $this->value;
		}

		// The current perfectpics_orders display field.
		$html[] = '<span class="input-append">';
		$html[] = '<input type="text" class="input-medium" id="'.$this->id.'_name" value="'.$title.'" disabled="disabled" size="35" />';
		$html[] = '<a class="modal btn hasTooltip" title="'.JHtml::tooltipText('COM_PERFECTPICS_ORDERS_CHANGE_PERFECTPICS_ORDERS_BUTTON').'"  href="'.$link.'&amp;'.JSession::getFormToken().'=1" rel="{handler: \'iframe\', size: {x: 800, y: 450}}"><i class="icon-file"></i> '.JText::_('JSELECT').'</a>';

		// Edit perfectpics_orders button
		if ($allowEdit)
		{
			$html[] = '<a class="btn hasTooltip'.($value ? '' : ' hidden').'" href="index.php?option=com_perfectpics_orders&layout=modal&tmpl=component&task=perfectpics_orders.edit&id=' . $value. '" target="_blank" title="'.JHtml::tooltipText('COM_PERFECTPICS_ORDERS_EDIT_PERFECTPICS_ORDERS').'" ><span class="icon-edit"></span> ' . JText::_('JACTION_EDIT') . '</a>';
		}

		// Clear perfectpics_orders button
		if ($allowClear)
		{
			$html[] = '<button id="'.$this->id.'_clear" class="btn'.($value ? '' : ' hidden').'" onclick="return jClearPerfectPics_Orders(\''.$this->id.'\')"><span class="icon-remove"></span> ' . JText::_('JCLEAR') . '</button>';
		}

		$html[] = '</span>';

		// class='required' for client side validation
		$class = '';

		if ($this->required)
		{
			$class = ' class="required modal-value"';
		}

		$html[] = '<input type="hidden" id="'.$this->id.'_id"'.$class.' name="'.$this->name.'" value="'.$value.'" />';

		return implode("\n", $html);
	}
}
