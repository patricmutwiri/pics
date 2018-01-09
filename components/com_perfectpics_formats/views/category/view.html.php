<?php
/**
 * @author
 * @copyright
 * @license
 */

defined("_JEXEC") or die("Restricted access");

/**
 * HTML View class for the Perfectpics_formats component
 *
 * @package     Perfectpics_formats
 * @subpackage  Views
 */
class Perfectpics_formatsViewCategory extends JViewCategory
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

		foreach ($this->items as $item)
		{
			//var_dump($item);
			$item->slug	= isset($item->alias) && !empty($item->alias) ? ($item->id.':'.$item->alias) : $item->id;
			$temp = new JRegistry;
			$item->params = clone($this->params);
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

				$item->introtext = $item->formats_icon;
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

		if ($menu && ($menu->query['option'] != 'com_perfectpics_formats' || $menu->query['view'] == 'perfectpics_formats' || $id != $this->category->id))
		{
			$path = array(array('title' => $this->category->title, 'link' => ''));
			$category = $this->category->getParent();

			while (($menu->query['option'] != 'com_perfectpics_formats' || $menu->query['view'] == 'perfectpics_formats' || $id != $category->id) && $category->id > 1)
			{
				$path[] = array('title' => $category->title, 'link' => Perfectpics_formatsHelperRoute::getCategoryRoute($category->id));
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
