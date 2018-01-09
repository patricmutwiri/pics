<?php
/**
 * @author		
 * @copyright	
 * @license		
 */

defined("_JEXEC") or die("Restricted access");

/**
 * Pricings list view class.
 *
 * @package     Pricing_calculator
 * @subpackage  Views
 */
class Pricing_calculatorViewPricings extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
	protected $toolbar;
	protected $categories;

	public function display($tpl = null)
	{
		$app = JFactory::getApplication();
		//print_r($subCategories);

		$this->items 		 = $this->get('Items');
		$this->categories    = $this->get('Categories');
		$this->state 		 = $this->get('State');
		$this->pagination 	 = $this->get('Pagination');
		$this->user		 	 = JFactory::getUser();
		$this->filterForm    = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');
		
		
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
		
		parent::display($tpl);
	}
}
?>