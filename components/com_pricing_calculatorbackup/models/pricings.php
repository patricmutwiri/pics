<?php
/**
 * @author		
 * @copyright	
 * @license		
 */

defined("_JEXEC") or die("Restricted access");

/**
 * List Model for pricings.
 *
 * @package     Pricing_calculator
 * @subpackage  Models
 */
class Pricing_calculatorModelPricings extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'a.item_name', 'item_name',
				'a.checked_out', 'checked_out',
				'a.checked_out_time', 'checked_out_time',
				'a.catid', 'catid', 'category_id', 'category_title',
				'a.published', 'published',
				'a.access', 'access', 'access_level',
				'a.created', 'created',
				'a.created_by', 'created_by', 'author_id',
				'a.publish_up', 'publish_up',
				'a.publish_down', 'publish_down','state'
			);
		}
		parent::__construct($config);
	}
	
	/**
	 * Method to auto-populate the model state.
	 *
	 * This method should only be called once per instantiation and is designed
	 * to be called on the first call to the getState() method unless the model
	 * configuration flag to ignore the request is set.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 */
	protected function populateState($ordering = 'item_name', $direction = 'ASC')
	{
		// Get the Application
		$app = JFactory::getApplication();
		$menu = $app->getMenu();
		
		// Set filter state for search
        $search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

		// Set filter state for access
		$accessId = $this->getUserStateFromRequest($this->context . '.filter.access', 'filter_access', null, 'int');
		$this->setState('filter.access', $accessId);

		// Set filter state for author
		$authorId = $app->getUserStateFromRequest($this->context . '.filter.author_id', 'filter_author_id');
		$this->setState('filter.author_id', $authorId);

		// Set filter state for publish state
        $published = $app->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '', 'string');
        $this->setState('filter.published', $published);

		// Set filter state for category
		$categoryId = $this->getUserStateFromRequest($this->context . '.filter.category_id', 'filter_category_id');
		$this->setState('filter.category_id', $categoryId);

		// Load the parameters.
		$params = JComponentHelper::getParams('com_pricing_calculator');
		$active = $menu->getActive();
		empty($active) ? null : $params->merge($active->params);
		$this->setState('params', $params);

		// List state information.
		parent::populateState($ordering, $direction);
	}
	
	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return  string  A store id.
	 *
	 * @since   1.6
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.access');
		$id .= ':' . $this->getState('filter.published');
		$id .= ':' . $this->getState('filter.category_id');
		$id .= ':' . $this->getState('filter.author_id');

		return parent::getStoreId($id);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return  JDatabaseQuery
	 */
	protected function getListQuery()
	{
		// Get database object
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('a.*')->from('#__pricing AS a');
		
		// Join over the users for the checked out user.
		$query->select('uc.name AS editor')
			->join('LEFT', '#__users AS uc ON uc.id = a.checked_out');
		
		// Join over the asset groups.
		$query->select('ag.title AS access_level')
			->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');
			
		// Join over the categories.
		$query->select('c.title AS category_title, c.path AS category_route, c.access AS category_access, c.alias AS category_alias')
			->join('LEFT', '#__categories AS c ON c.id = a.catid');

		// Join over the categories to get parent category titles
		$query->select('parent.title as parent_title, parent.id as parent_id, parent.path as parent_route, parent.alias as parent_alias')
			->join('LEFT', '#__categories as parent ON parent.id = c.parent_id');
		
		// Join over the users for the author.
		$query->select('ua.name AS author_name')
			->join('LEFT', '#__users AS ua ON ua.id = a.created_by');

		// Join over the users for the modifier.
		$query->select('um.name AS modifier_name')
			->join('LEFT', '#__users AS um ON um.id = a.modified_by');

		// Filter by search
		$search = $this->getState('filter.search');
		$s = $db->quote('%'.$db->escape($search, true).'%');
		
		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, strlen('id:')));
			}
			elseif (stripos($search, 'item_name:') === 0)
			{
				$search = $db->quote('%' . $db->escape(substr($search, strlen('item_name:')), true) . '%');
				$query->where('(a.item_name LIKE ' . $search);
			}
			elseif (stripos($search, 'author:') === 0)
			{
				$search = $db->quote('%' . $db->escape(substr($search, 7), true) . '%');
				$query->where('(ua.name LIKE ' . $search . ' OR ua.username LIKE ' . $search . ')');
			}
			else
			{
				$search = $db->quote('%' . $db->escape($search, true) . '%');
				
			}
		}
		
		// Filter by published state.
		$published = $this->getState('filter.published');
		if (is_numeric($published))
		{
			$query->where('a.published = ' . (int) $published);
		}
		elseif ($published === '')
		{
			// Only show items with state 'published' / 'unpublished'
			$query->where('(a.published IN (0, 1))');
		}
		
		// Filter by category
		$categoryId = $this->getState('filter.category_id');
		if (is_numeric($categoryId))
		{
			$query->where('a.catid = '.(int) $categoryId);
		}
		
		// Filter by author
		$authorId = $this->getState('filter.author_id');
		if (is_numeric($authorId))
		{
			$type = $this->getState('filter.author_id.include', true) ? '= ' : '<>';
			$query->where('a.created_by ' . $type . (int) $authorId);
		}
		
		// Filter by access level.
		$access = $this->getState('filter.access');
		if (!empty($access))
		{
			$query->where('a.access = ' . (int) $access);
		}
		
		// Implement View Level Access
		$user = JFactory::getUser();
		if (!$user->authorise('core.admin'))
		{
			$groups = implode(',', $user->getAuthorisedViewLevels());
			$query->where('a.access IN (' . $groups . ')');
			$query->where('c.access IN (' . $groups . ')');
		}
		
		// Add list oredring and list direction to SQL query
		$sort = $this->getState('list.ordering', 'item_name');
		$order = $this->getState('list.direction', 'ASC');
		$query->order($db->escape($sort).' '.$db->escape($order));
		
		return $query;
	}
	
	/**
	 * Build a list of categories
	 *
	 * @return  array
	 *
	 * @since   1.6
	 */
	public function getCategories()
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query = $query->select('*')
					->from('#__categories')
					->where($db->quoteName('extension') .' = '.$db->quote('com_perfectpics_products'))
					->where($db->quoteName('parent_id') .' = 1')
					->where($db->quoteName('level') .' = 1')
					->where($db->quoteName('published') .' = 1');
		$db->setQuery($query);
		$categories = $db->loadObjectList();

		return $categories;
	}

	/**
	 * Build a list of products from cats
	 *
	 * @return  array
	 *
	 * @since   1.6
	 */
	public function getProducts($id)
	{
		$id 	= (int)$id;
		$db 	= $this->getDbo();
		$query 	= $db->getQuery(true);
		$query 	= $query->select('*, a.id as aid')
					->from('#__perfectpics_product AS a')
					->where($db->quoteName('a.catid') .' = '.$db->quote($id));
		// Filter by published state.
		$query->select('c.title AS category_title, c.path AS category_route, c.access AS category_access, c.alias AS category_alias')
			->join('LEFT', '#__categories AS c ON c.id = a.catid');

		$published = $this->getState('filter.published');
		if (is_numeric($published))
		{
			$query->where('a.published = ' . (int) $published);
		}
		elseif ($published === '')
		{
			// Only show items with state 'published' / 'unpublished'
			$query->where('(a.published IN (0, 1))');
		}
				
		// Filter by access level.
		$access = 1;
		if (!empty($access))
		{
			$query->where('a.access = ' . (int) $access);
		}
		
		// Implement View Level Access
		$user = JFactory::getUser();
		if (!$user->authorise('core.admin'))
		{
			$groups = implode(',', $user->getAuthorisedViewLevels());
			$query->where('a.access IN (' . $groups . ')');
			$query->where('c.access IN (' . $groups . ')');
		}

		$db->setQuery($query);
		$products = $db->loadObjectList();

		return $products;
	}
	
	/**
	 * Build a list of authors
	 *
	 * @return  array
	 *
	 * @since   1.6
	 */
	public function getAuthors()
	{
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// Construct the query
		$query->select('u.id AS value, u.name AS text')
			->from('#__users AS u')
			->join('INNER', '#__pricing AS a ON a.created_by = u.id')
			->group('u.id, u.name')
			->order('u.name');

		// Setup the query
		$db->setQuery($query);

		// Return the result
		return $db->loadObjectList();
	}
	
	/**
	 * Method to get an array of data items.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
	 *
	 * @since   12.2
	 */
	public function getItems()
	{
		if ($items = parent::getItems()) {
			//Do any procesing on fields here if needed
		}

		return $items;
	}
}
?>