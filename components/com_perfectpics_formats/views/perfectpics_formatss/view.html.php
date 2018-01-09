<?php
/**
 * @author
 * @copyright
 * @license
 */

defined("_JEXEC") or die("Restricted access");

/**
 * PerfectPics_Formatss list view class.
 *
 * @package     Perfectpics_formats
 * @subpackage  Views
 */
class Perfectpics_formatsViewPerfectPics_Formatss extends JViewLegacy
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
		$this->categories 		 = $this->get('Cats');
		$this->prodz 		 = $this->get('Products');


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

			$active = $app->getMenu()->getActive();
			$item->params = clone($this->params);
		}

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
			return false;
		}
		// var_dump($this->categories);
		// $componentParams= $this->categories;
		// var_dump($componentParams);
		//$foo = $componentParams->get('show_title');
		// var_dump($this->categories);
		parent::display($tpl);
	}
}
?>
