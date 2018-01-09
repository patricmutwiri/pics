<?php
/**
 * @author
 * @copyright
 * @license
 */

defined("_JEXEC") or die("Restricted access");

/**
 * PerfectPics_Product item view class.
 *
 * @package     Perfectpics_products
 * @subpackage  Views
 */
class Perfectpics_productsViewPerfectPics_Product extends JViewLegacy
{
	protected $item;
	protected $form;
	protected $state;

	public function display($tpl = null)
	{
		$this->state 	= $this->get('State');
		$this->item 	= $this->get('Item');
		$this->form 	= $this->get('Form');
		$this->model = $this->getModel();

		$app = JFactory::getApplication();
		$user = JFactory::getUser();
		$levels = $user->getAuthorisedViewLevels();

		// Check if item is empty
		if (empty($this->item))
		{
			$app->redirect(JRoute::_('index.php?option=com_perfectpics_products&view=perfectpics_products'), JText::_('JERROR_NO_ITEMS_SELECTED'));
		}

		// Check item access
		if ($this->item->id && !in_array($this->item->access, $levels))
		{
			throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
		}

		// Is the user allowed to create an item?
		if (!$this->item->id && !$user->authorise("core.create", "com_perfectpics_products"))
		{
			throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
		}

		// Get menu params
		$menu = $app->getMenu();
		$active = $menu->getActive();

		if (is_object($active))
		{
			$this->state->params = $active->params;
		}

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
			return false;
		}

		// Increment hits
		$model = $this->getModel();
		$model->hit($this->item->id);

		parent::display($tpl);
	}
}
?>
