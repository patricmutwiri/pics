<?php
/**
 * @author		
 * @copyright	
 * @license		
 */

defined("_JEXEC") or die("Restricted access");

/**
 * Categories list view class.
 *
 * @package     Perfectpics_formats
 * @subpackage  Views
 */
class Perfectpics_formatsViewCategories extends JViewCategories
{
	/**
	 * Language key for default page heading
	 *
	 * @var    string
	 * @since  3.2
	 */
	protected $pageHeading = 'COM_PERFECTPICS_FORMATS_DEFAULT_PAGE_TITLE';

	/**
	 * @var    string  The name of the extension for the category
	 * @since  3.2
	 */
	protected $extension = 'com_perfectpics_formats';

	/**
	 * @var    string  The name of the view to link individual items to
	 * @since  3.2
	 */
	protected $viewName = 'perfectpics_formats';

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise a Error object.
	 */
	public function display($tpl = null)
	{
		$state		= $this->get('State');
		$items		= $this->get('Items');
		$parent		= $this->get('Parent');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseWarning(500, implode("\n", $errors));
			return false;
		}

		if ($items === false)
		{
			throw new Exception(JText::_('JGLOBAL_CATEGORY_NOT_FOUND'));
		}

		if ($parent == false)
		{
			throw new Exception(JText::_('JGLOBAL_CATEGORY_NOT_FOUND'));
		}

		$params = &$state->params;

		$items = array($parent->id => $items);

		// Escape strings for HTML output
		$this->pageclass_sfx = htmlspecialchars($params->get('pageclass_sfx'));

		$this->maxLevelcat = $params->get('maxLevelcat', -1);
		$this->params = &$params;
		$this->parent = &$parent;
		$this->items  = &$items;

		return parent::display($tpl);
	}
}