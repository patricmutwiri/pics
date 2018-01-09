<?php
/**
 * @author		
 * @copyright	
 * @license		
 */

defined("_JEXEC") or die("Restricted access");

/**
 * Perfectpics_orders category Model
 *
 * @package     Perfectpics_orders
 * @subpackage  Models
 */
class Perfectpics_ordersModelCategory extends JModelList
{
	protected $_item = null;
	protected $_children = null;
	protected $_parent = null;

	/**
	 * Constructor.
	 *
	 * @param   array  An optional associative array of configuration settings.
	 * @see     JController
	 * @since   1.6
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'title', 'a.title',
				'ordering', 'a.ordering',
			);
		}

		parent::__construct($config);
	}

	/**
	 * The category that applies.
	 *
	 * @access    protected
	 * @var        object
	 */
	protected $_category = null;

	/**
	 * The list of other perfectpics_orders categories.
	 *
	 * @access    protected
	 * @var        array
	 */
	protected $_categories = null;

	/**
	 * Method to get a list of items.
	 *
	 * @return  mixed  An array of objects on success, false on failure.
	 */
	public function getItems()
	{
		// Invoke the parent getItems method to get the main list
		$items = parent::getItems();

		// Convert the params field into an object, saving original in _params
		foreach ($items as $item)
		{
			if (!isset($this->_params))
			{
				$params = new JRegistry;
				$params->loadString($item->params);
				$item->params = $params;
			}
		}

		return $items;
	}

	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return  string    An SQL query
	 * @since   1.6
	 */
	protected function getListQuery()
	{
		$user = JFactory::getUser();
		$groups = implode(',', $user->getAuthorisedViewLevels());

		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// Select required fields from the categories.
		$query->select($this->getState('list.select', 'a.*'))
			->from($db->quoteName('#__perfectpics_orders') . ' AS a');
		$query->where('a.access IN (' . $groups . ')');

		// Filter by category.
		if ($categoryId = $this->getState('category.id'))
		{
			$query->where('a.catid = ' . (int) $categoryId)
				->join('LEFT', '#__categories AS c ON c.id = a.catid')
				->where('c.access IN (' . $groups . ')');

			//Filter by published category
			$cpublished = $this->getState('filter.c.published');
			if (is_numeric($cpublished))
			{
				$query->where('c.published = ' . (int) $cpublished);
			}
		}

		// Join over the users for the author and modified_by names.
		
		$query->select("ua.email AS author_email")
			->join('LEFT', '#__users AS ua ON ua.id = a.created_by');
		
		$query->select("ua.name AS author")
			->join('LEFT', '#__users AS uam ON uam.id = a.modified_by');

		// Filter by state
		$published = $this->getState('filter.published');
		if (is_numeric($published))
		{
			$query->where('a.published = ' . (int) $published);
		}
		// do not show trashed links on the front-end
		$query->where('a.published != -2');

		// Filter by start and end dates.
		$nullDate = $db->quote($db->getNullDate());
		$date = JFactory::getDate();
		$nowDate = $db->quote($date->toSql());

		if ($this->getState('filter.publish_date'))
		{
			$query->where('(a.publish_up = ' . $nullDate . ' OR a.publish_up <= ' . $nowDate . ')');
			$query->where('(a.publish_down = ' . $nullDate . ' OR a.publish_down >= ' . $nowDate . ')');
		}

		// Filter by search in title
		$search = $this->getState('list.filter');
		if (!empty($search))
		{
			$search = $db->quote('%' . $db->escape($search, true) . '%');
			$query->where('(a.customers_name LIKE ' . $search . ')');
		}

		// Add the list ordering clause.
		$query->order($db->escape($this->getState('list.ordering', 'a.customers_name')) . ' ' . $db->escape($this->getState('list.direction', 'ASC')));
		return $query;
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since   1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$app = JFactory::getApplication();
		$params = JComponentHelper::getParams('com_perfectpics_orders');

		// List state information
		$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'uint');
		$this->setState('list.limit', $limit);

		$limitstart = $app->input->get('limitstart', 0, 'uint');
		$this->setState('list.start', $limitstart);

		// Optional filter text
		$this->setState('list.filter', $app->input->getString('filter-search'));

		$orderCol = $app->input->get('filter_order', 'ordering');
		if (!in_array($orderCol, $this->filter_fields))
		{
			$orderCol = 'ordering';
		}
		$this->setState('list.ordering', $orderCol);

		$listOrder = $app->input->get('filter_order_Dir', 'ASC');
		if (!in_array(strtoupper($listOrder), array('ASC', 'DESC', '')))
		{
			$listOrder = 'ASC';
		}
		$this->setState('list.direction', $listOrder);

		$id = $app->input->get('id', 0, 'int');
		$this->setState('category.id', $id);

		$user = JFactory::getUser();
		if ((!$user->authorise('core.edit.state', 'com_perfectpics_orders')) && (!$user->authorise('core.edit', 'com_perfectpics_orders')))
		{
			// limit to published for people who can't edit or edit.state.
			$this->setState('filter.published', 1);

			// Filter by start and end dates.
			$this->setState('filter.publish_date', true);
		}

		// Load the parameters.
		$this->setState('params', $params);
	}

	/**
	 * Method to get category data for the current category
	 *
	 * @param   integer  An optional ID
	 *
	 * @return  object
	 * @since   1.5
	 */
	public function getCategory()
	{
		if (!is_object($this->_item))
		{
			$app = JFactory::getApplication();
			$menu = $app->getMenu();
			$active = $menu->getActive();
			$params = new JRegistry;

			if ($active)
			{
				$params->loadString($active->params);
			}

			$options = array();
			$options['countItems'] = $params->get('show_cat_num_links_cat', 1) || $params->get('show_empty_categories', 0);
			$categories = JCategories::getInstance('Perfectpics_orders', $options);
			$this->_item = $categories->get($this->getState('category.id', 'root'));

			if (is_object($this->_item))
			{
				$this->_children = $this->_item->getChildren();
				$this->_parent = false;
				if ($this->_item->getParent())
				{
					$this->_parent = $this->_item->getParent();
				}
				$this->_rightsibling = $this->_item->getSibling();
				$this->_leftsibling = $this->_item->getSibling(false);
			}
			else
			{
				$this->_children = false;
				$this->_parent = false;
			}
		}

		return $this->_item;
	}

	/**
	 * Get the parent category
	 *
	 * @param   integer  An optional category id. If not supplied, the model state 'category.id' will be used.
	 *
	 * @return  mixed  An array of categories or false if an error occurs.
	 */
	public function getParent()
	{
		if (!is_object($this->_item))
		{
			$this->getCategory();
		}

		return $this->_parent;
	}

	/**
	 * Get the sibling (adjacent) categories.
	 *
	 * @return  mixed  An array of categories or false if an error occurs.
	 */
	function &getLeftSibling()
	{
		if (!is_object($this->_item))
		{
			$this->getCategory();
		}

		return $this->_leftsibling;
	}

	function &getRightSibling()
	{
		if (!is_object($this->_item))
		{
			$this->getCategory();
		}

		return $this->_rightsibling;
	}

	/**
	 * Get the child categories.
	 *
	 * @param   integer  An optional category id. If not supplied, the model state 'category.id' will be used.
	 *
	 * @return  mixed  An array of categories or false if an error occurs.
	 */
	function &getChildren()
	{
		if (!is_object($this->_item))
		{
			$this->getCategory();
		}

		return $this->_children;
	}

	/**
	 * Increment the hit counter for the category.
	 *
	 * @param   int  $pk  Optional primary key of the category to increment.
	 *
	 * @return  boolean True if successful; false otherwise and internal error set.
	 *
	 * @since   3.2
	 */
	public function hit($pk = 0)
	{
		$input = JFactory::getApplication()->input;
		$hitcount = $input->getInt('hitcount', 1);

		if ($hitcount)
		{
			$pk = (!empty($pk)) ? $pk : (int) $this->getState('category.id');

			$table = JTable::getInstance('Category', 'JTable');
			$table->load($pk);
			$table->hit($pk);
		}

		return true;
	}
}