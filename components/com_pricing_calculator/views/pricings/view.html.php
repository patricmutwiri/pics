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
		//getMenu
		$user = JFactory::getUser();
		$menu = $app->getMenu();
		$menuid = $menu->getActive()->id;
		if($menuid == 629) { //photographers page
			if(!$user->id) {
				$redirectUrl 	= 'index.php?option=com_pricing_calculator&view=pricings&Itemid=629';
    			$joomlaLoginUrl = 'index.php?option=com_users&view=login';
    			$redirectUrl 	= '&return='.urlencode(base64_encode($redirectUrl)); 
    			$finalUrl 		= $joomlaLoginUrl . $redirectUrl;
    			$app->redirect($finalUrl,'Login to view this page please');
    		}
			if(!in_array(10, $user->groups)) {
				$app->redirect(JURI::root(),'You need to be registered as a photographer to view Photographers page');	
			}
		}
		$this->items 		 = $this->get('Items');
		//$this->groupedp 	 = $this->get('GroupedProducts');
		$this->categories    = $this->get('Categories');
		$this->sizes 		 = $this->get('Sizes');
		$this->covers    	 = $this->get('Covers');
		$this->papertypes    = $this->get('Papertypes');
		//$this->productsHere  = $this->get('PaperProducts');
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