<?php
/**
 * @author
 * @copyright
 * @license
 */

defined("_JEXEC") or die("Restricted access");

/**
 * HTML View class for the Perfectpics_products component
 *
 * @package     Perfectpics_products
 * @subpackage  Views
 */
class Perfectpics_productsViewCategory extends JViewCategory
{
	/**
	 * @var    array  Array of leading items for blog display
	 * @since  3.2
	 */
	protected $lead_items = array();

	/**
	 * @var    array  Array of intro (multicolumn display) items for blog display
	 * @since  3.2
	 */
	protected $intro_items = array();

	/**
	 * @var    array  Array of links in blog display
	 * @since  3.2
	 */
	protected $link_items = array();

	/**
	 * Prepares the output
	 *
	 * @return  void
	 */
	public function display($tpl = null)
	{
		parent::commonCategoryDisplay();

		// Prepare the data
		// Get the metrics for the structural page layout.
		$params		= $this->params;
		$numLeading	= $params->def('num_leading_articles', 1);
		$numIntro	= $params->def('num_intro_articles', 4);
		$numLinks	= $params->def('num_links', 4);

		//var_dump($this->items);
		foreach ($this->items as $item)
		{
			$item->slug	= isset($item->alias) && !empty($item->alias) ? ($item->id.':'.$item->alias) : $item->id;
			$temp = new JRegistry;
			$temp->loadString($item->params);
			$item->params = clone($this->params);
			$item->params->merge($temp);
		}

		/** Blog Layouts
		 *
		 * For blog layouts, preprocess the breakdown of leading, intro and linked articles.
		 * This makes it much easier for the designer to just interrogate the arrays.
		 */
		if (($params->get('layout_type') == 'blog') || ($this->getLayout() == 'blog'))
		{
			//$max = count($this->items);

			foreach ($this->items as $i => $item)
			{
				if ($i < $numLeading)
				{
					$this->lead_items[] = $item;
				}

				elseif ($i >= $numLeading && $i < $numLeading + $numIntro)
				{
					$this->intro_items[] = $item;
				}

				elseif ($i < $numLeading + $numIntro + $numLinks)
				{
					$this->link_items[] = $item;
				}
				else
				{
					continue;
				}

				$item->introtext = $item->product_name;
			}

			$this->columns = max(1, $params->def('num_columns', 1));
			$params->def('multi_column_order', 1);
		}
		return parent::display($tpl);
	}

	/**
	 * Prepares the document
	 *
	 * @return  void
	 */
	protected function prepareDocument()
	{
		parent::prepareDocument();
		$menu = $this->menu;
		$id = (int) @$menu->query['id'];

		if ($menu && ($menu->query['option'] != 'com_perfectpics_products' || $menu->query['view'] == 'perfectpics_product' || $id != $this->category->id))
		{
			$path = array(array('title' => $this->category->title, 'link' => ''));
			$category = $this->category->getParent();

			while (($menu->query['option'] != 'com_perfectpics_products' || $menu->query['view'] == 'perfectpics_product' || $id != $category->id) && $category->id > 1)
			{
				$path[] = array('title' => $category->title, 'link' => Perfectpics_productsHelperRoute::getCategoryRoute($category->id));
				$category = $category->getParent();
			}

			$path = array_reverse($path);

			foreach ($path as $item)
			{
				$this->pathway->addItem($item['title'], $item['link']);
			}
		}

		parent::addFeed();
	}
}
