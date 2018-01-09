<?php
/**
* @title			Minitek FAQ Book
* @copyright   		Copyright (C) 2011-2015 Minitek, All rights reserved.
* @license   		GNU General Public License version 3 or later.
* @author url   	http://www.minitek.gr/
* @developers   	Minitek.gr
*/

// no direct access
defined('_JEXEC') or die;

class JFormFieldModal_Question extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since   1.6
	 */
	protected $type = 'Modal_Question';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string	The field input markup.
	 *
	 * @since   1.6
	 */
	protected function getInput()
	{
		$allowClear = ((string) $this->element['clear'] != 'false') ? true : false;

		// Load language
		JFactory::getLanguage()->load('com_faqbookpro', JPATH_ADMINISTRATOR);

		// Build the script.
		$script = array();

		// Select button script
		$script[] = '	function jSelectQuestion_' . $this->id . '(id, title, topicid, object) {';
		$script[] = '		document.getElementById("' . $this->id . '_id").value = id;';
		$script[] = '		document.getElementById("' . $this->id . '_name").value = title;';

		if ($allowClear)
		{
			$script[] = '		jQuery("#' . $this->id . '_clear").removeClass("hidden");';
		}

		$script[] = '		jQuery("#modalQuestion' . $this->id . '").modal("hide");';

		if ($this->required)
		{
			$script[] = '		document.formvalidator.validate(document.getElementById("' . $this->id . '_id"));';
			$script[] = '		document.formvalidator.validate(document.getElementById("' . $this->id . '_name"));';
		}

		$script[] = '	}';

		// Clear button script
		static $scriptClear;

		if ($allowClear && !$scriptClear)
		{
			$scriptClear = true;

			$script[] = '	function jClearQuestion(id) {';
			$script[] = '		document.getElementById(id + "_id").value = "";';
			$script[] = '		document.getElementById(id + "_name").value = "' .
				htmlspecialchars(JText::_('COM_FAQBOOKPRO_SELECT_A_QUESTION', true), ENT_COMPAT, 'UTF-8') . '";';
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
		$html = array();
		$link = 'index.php?option=com_faqbookpro&amp;view=questions&amp;layout=modal&amp;tmpl=component&amp;function=jSelectQuestion_' . $this->id;

		if (isset($this->element['language']))
		{
			$link .= '&amp;forcedLanguage=' . $this->element['language'];
		}

		if ((int) $this->value > 0)
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select($db->quoteName('title'))
				->from($db->quoteName('#__minitek_faqbook_questions'))
				->where($db->quoteName('id') . ' = ' . (int) $this->value);
			$db->setQuery($query);

			try
			{
				$title = $db->loadResult();
			}
			catch (RuntimeException $e)
			{
				JError::raiseWarning(500, $e->getMessage());
			}
		}

		if (empty($title))
		{
			$title = JText::_('COM_FAQBOOKPRO_SELECT_A_QUESTION');
		}
		$title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');

		// The active question id field.
		if (0 == (int) $this->value)
		{
			$value = '';
		}
		else
		{
			$value = (int) $this->value;
		}

		$url = $link . '&amp;' . JSession::getFormToken() . '=1';

		// The current question display field.
		$html[] = '<span class="input-append">';
		$html[] = '<input type="text" class="input-medium" id="' . $this->id . '_name" value="' . $title . '" disabled="disabled" size="35" />';
		$html[] = '<a href="#modalQuestion' . $this->id . '" class="btn hasTooltip" role="button"  data-toggle="modal" title="'
			. JHtml::tooltipText('COM_FAQBOOKPRO_CHANGE_QUESTION') . '">'
			. '<span class="icon-file"></span> '
			. JText::_('JSELECT') . '</a>';

		// Clear question button
		if ($allowClear)
		{
			$html[] = '<button id="' . $this->id . '_clear" class="btn' . ($value ? '' : ' hidden') . '" onclick="return jClearQuestion(\'' .
				$this->id . '\')"><span class="icon-remove"></span>' . JText::_('JCLEAR') . '</button>';
		}

		$html[] = '</span>';

		// The class='required' for client side validation
		$class = '';

		if ($this->required)
		{
			$class = ' class="required modal-value"';
		}

		$html[] = '<input type="hidden" id="' . $this->id . '_id"' . $class . ' name="' . $this->name . '" value="' . $value . '" />';

		$html[] = JHtml::_(
			'bootstrap.renderModal',
			'modalQuestion' . $this->id,
			array(
				'url' => $url,
				'title' => JText::_('COM_FAQBOOKPRO_CHANGE_QUESTION'),
				'width' => '800px',
				'height' => '300px',
				'footer' => '<button class="btn" data-dismiss="modal" aria-hidden="true">'
					. JText::_("JLIB_HTML_BEHAVIOR_CLOSE") . '</button>'
			)
		);
		return implode("\n", $html);
	}

	/**
	 * Method to get the field label markup.
	 *
	 * @return  string  The field label markup.
	 *
	 * @since   3.4
	 */
	protected function getLabel()
	{
		return str_replace($this->id, $this->id . '_id', parent::getLabel());
	}
}
