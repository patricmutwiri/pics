<?php
/**
 * @author
 * @copyright
 * @license
 */

defined("_JEXEC") or die("Restricted access");

/**
 * PerfectPics_Products list view class.
 *
 * @package     Perfectpics_products
 * @subpackage  Views
 */
class Perfectpics_productsViewPerfectPics_Products extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
	protected $toolbar;

	public function display($tpl = null)
	{
		$app = JFactory::getApplication();

		$this->items 		 = $this->get('Items');
		$this->state 		 = $this->get('State');
		$this->pagination 	 = $this->get('Pagination');
		$this->user		 	 = JFactory::getUser();
		$this->filterForm    = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');
		$this->model = $this->getModel();


		$active = $app->getMenu()->getActive();
		if ($active)
		{
			$this->params = $active->params;
		}
		else
		{
			$this->params = new JRegistry();
		}

		// Prepare the data.
		foreach ($this->items as $item)
		{
			$temp = new JRegistry;
			$temp->loadString($item->params);

			$active = $app->getMenu()->getActive();
			$item->params = clone($this->params);
			$item->params->merge($temp);
		}

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
			return false;
		}

		parent::display($tpl);
	}
}
?>
