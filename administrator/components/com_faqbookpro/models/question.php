<?php
/**
* @title			Minitek FAQ Book
* @copyright   		Copyright (C) 2011-2015 Minitek, All rights reserved.
* @license   		GNU General Public License version 3 or later.
* @author url   	http://www.minitek.gr/
* @developers   	Minitek.gr
*/

// no direct access
defined('_JEXEC') or die;

use Joomla\Registry\Registry;

if(!defined('DS'))
{
	define('DS',DIRECTORY_SEPARATOR);
}

require_once (JPATH_SITE.DS.'components'.DS.'com_faqbookpro'.DS.'helpers'.DS.'route.php');

class FAQBookProModelQuestion extends JModelAdmin
{
	/**
	 * @var        string    The prefix to use with controller messages.
	 * @since   1.6
	 */
	protected $text_prefix = 'COM_FAQBOOKPRO';

	/**
	 * The type alias for this content type.
	 *
	 * @var      string
	 * @since    3.2
	 */
	public $typeAlias = 'com_faqbookpro.question';
	
	protected function checkTopicId($topicId)
	{
		if (empty($topicId))
		{
			$this->setError(JText::_('COM_FAQBOOKPRO_ERROR_BATCH_MOVE_TOPIC_NOT_FOUND'));
			return false;
		}

		// Check that the user has create permission for the component
		if (!$this->user->authorise('core.create', 'com_faqbookpro.topic.' . $topicId))
		{
			$this->setError(JText::_('COM_FAQBOOKPRO_ERROR_BATCH_CANNOT_CREATE'));

			return false;
		}

		return true;
	}
	
	protected function generateNewTitle($topic_id, $alias, $title)
	{
		// Alter the title & alias
		$table = $this->getTable();
		while ($table->load(array('alias' => $alias, 'topicid' => $topic_id)))
		{
		$title = JString::increment($title);
		$alias = JString::increment($alias, 'dash');
		}
		
		return array($title, $alias);
	}
	
	/**
	 * Method to perform batch operations on an item or a set of items.
	 *
	 * @param   array  $commands  An array of commands to perform.
	 * @param   array  $pks       An array of item ids.
	 * @param   array  $contexts  An array of item contexts.
	 *
	 * @return  boolean  Returns true on success, false on failure.
	 *
	 * @since   12.2
	 */
	public function batch($commands, $pks, $contexts)
	{
		// Sanitize ids.
		$pks = array_unique($pks);
		JArrayHelper::toInteger($pks);

		// Remove any values of zero.
		if (array_search(0, $pks, true))
		{
			unset($pks[array_search(0, $pks, true)]);
		}

		if (empty($pks))
		{
			$this->setError(JText::_('JGLOBAL_NO_ITEM_SELECTED'));

			return false;
		}

		$done = false;

		// Set some needed variables.
		$this->user = JFactory::getUser();
		$this->table = $this->getTable();
		$this->tableClassName = get_class($this->table);
		$this->contentType = new JUcmType;
		$this->type = $this->contentType->getTypeByTable($this->tableClassName);
		$this->batchSet = true;

		if ($this->type == false)
		{
			$type = new JUcmType;
			$this->type = $type->getTypeByAlias($this->typeAlias);

		}
		if ($this->type === false)
		{
			$type = new JUcmType;
			$this->type = $type->getTypeByAlias($this->typeAlias);
			$typeAlias = $this->type->type_alias;
		}
		else
		{
			$typeAlias = $this->type->type_alias;
		}

		if (!empty($commands['topic_id']))
		{
			$cmd = JArrayHelper::getValue($commands, 'move_copy', 'c');

			if ($cmd == 'c')
			{
				$result = $this->batchCopy($commands['topic_id'], $pks, $contexts);

				if (is_array($result))
				{
					$pks = $result;
				}
				else
				{
					return false;
				}
			}
			elseif ($cmd == 'm' && !$this->batchMove($commands['topic_id'], $pks, $contexts))
			{
				return false;
			}

			$done = true;
		}

		if (!empty($commands['assetgroup_id']))
		{
			if (!$this->batchAccess($commands['assetgroup_id'], $pks, $contexts))
			{
				return false;
			}

			$done = true;
		}

		if (!empty($commands['language_id']))
		{
			if (!$this->batchLanguage($commands['language_id'], $pks, $contexts))
			{
				return false;
			}

			$done = true;
		}

		if (!$done)
		{
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_INSUFFICIENT_BATCH_INFORMATION'));
			return false;
		}

		// Clear the cache
		$this->cleanCache();

		return true;
	}

	/**
	 * Batch access level changes for a group of rows.
	 *
	 * @param   integer  $value     The new value matching an Asset Group ID.
	 * @param   array    $pks       An array of row IDs.
	 * @param   array    $contexts  An array of item contexts.
	 *
	 * @return  boolean  True if successful, false otherwise and internal error is set.
	 *
	 * @since   12.2
	 */
	protected function batchAccess($value, $pks, $contexts)
	{
		if (empty($this->batchSet))
		{
			// Set some needed variables.
			$this->user = JFactory::getUser();
			$this->table = $this->getTable();
			$this->tableClassName = get_class($this->table);
			$this->contentType = new JUcmType;
			$this->type = $this->contentType->getTypeByTable($this->tableClassName);
		}

		foreach ($pks as $pk)
		{
			if ($this->user->authorise('core.edit', $contexts[$pk]))
			{
				$this->table->reset();
				$this->table->load($pk);
				$this->table->access = (int) $value;

				if (!$this->table->store())
				{
					$this->setError($this->table->getError());

					return false;
				}
			}
			else
			{
				$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_CANNOT_EDIT'));

				return false;
			}
		}

		// Clean the cache
		$this->cleanCache();

		return true;
	}

	/**
	 * Batch copy items to a new topic or current.
	 *
	 * @param   integer  $value     The new topic.
	 * @param   array    $pks       An array of row IDs.
	 * @param   array    $contexts  An array of item contexts.
	 *
	 * @return  mixed  An array of new IDs on success, boolean false on failure.
	 *
	 * @since   11.1
	 */
	protected function batchCopy($value, $pks, $contexts)
	{
		$topicId = (int) $value;

		$newIds = array();

		if (!self::checkTopicId($topicId))
		{
			return false;
		}

		// Parent exists so we let's proceed
		while (!empty($pks))
		{
			// Pop the first ID off the stack
			$pk = array_shift($pks);

			$this->table->reset();

			// Check that the row actually exists
			if (!$this->table->load($pk))
			{
				if ($error = $this->table->getError())
				{
					// Fatal error
					$this->setError($error);

					return false;
				}
				else
				{
					// Not fatal error
					$this->setError(JText::sprintf('COM_FAQBOOKPRO_ERROR_BATCH_MOVE_ROW_NOT_FOUND', $pk));
					continue;
				}
			}

			// Alter the title & alias
			$data = $this->generateNewTitle($topicId, $this->table->alias, $this->table->title);
			$this->table->title = $data['0'];
			$this->table->alias = $data['1'];

			// Reset the ID because we are making a copy
			$this->table->id = 0;

			// Reset hits because we are making a copy
			$this->table->hits = 0;

			// Unpublish because we are making a copy
			$this->table->state = 0;

			// New topic ID
			$this->table->topicid = $topicId;

			// TODO: Deal with ordering?
			// $table->ordering = 1;

			// Get the featured state
			$featured = $this->table->featured;

			// Check the row.
			if (!$this->table->check())
			{
				$this->setError($this->table->getError());
				return false;
			}

			// Store the row.
			if (!$this->table->store())
			{
				$this->setError($this->table->getError());
				return false;
			}

			// Get the new item ID
			$newId = $this->table->get('id');

			// Add the new ID to the array
			$newIds[$pk] = $newId;
		}

		// Clean the cache
		$this->cleanCache();

		return $newIds;
	}
	
	/**
	 * Batch language changes for a group of rows.
	 *
	 * @param   string  $value     The new value matching a language.
	 * @param   array   $pks       An array of row IDs.
	 * @param   array   $contexts  An array of item contexts.
	 *
	 * @return  boolean  True if successful, false otherwise and internal error is set.
	 *
	 * @since   11.3
	 */
	protected function batchLanguage($value, $pks, $contexts)
	{
		if (empty($this->batchSet))
		{
			// Set some needed variables.
			$this->user = JFactory::getUser();
			$this->table = $this->getTable();
			$this->tableClassName = get_class($this->table);
			$this->contentType = new JUcmType;
			$this->type = $this->contentType->getTypeByTable($this->tableClassName);
		}

		foreach ($pks as $pk)
		{
			if ($this->user->authorise('core.edit', $contexts[$pk]))
			{
				$this->table->reset();
				$this->table->load($pk);
				$this->table->language = $value;

				if (!$this->table->store())
				{
					$this->setError($this->table->getError());

					return false;
				}
			}
			else
			{
				$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_CANNOT_EDIT'));

				return false;
			}
		}

		// Clean the cache
		$this->cleanCache();

		return true;
	}

	/**
	 * Batch move items to a new topic
	 *
	 * @param   integer  $value     The new topic ID.
	 * @param   array    $pks       An array of row IDs.
	 * @param   array    $contexts  An array of item contexts.
	 *
	 * @return  boolean  True if successful, false otherwise and internal error is set.
	 *
	 * @since	12.2
	 */
	protected function batchMove($value, $pks, $contexts)
	{
		if (empty($this->batchSet))
		{
			// Set some needed variables.
			$this->user = JFactory::getUser();
			$this->table = $this->getTable();
			$this->tableClassName = get_class($this->table);
			$this->contentType = new JUcmType;
			$this->type = $this->contentType->getTypeByTable($this->tableClassName);
		}

		$topicId = (int) $value;

		if (!static::checkTopicId($topicId))
		{
			return false;
		}

		// Parent exists so we proceed
		foreach ($pks as $pk)
		{
			if (!$this->user->authorise('core.edit', $contexts[$pk]))
			{
				$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_CANNOT_EDIT'));

				return false;
			}

			// Check that the row actually exists
			if (!$this->table->load($pk))
			{
				if ($error = $this->table->getError())
				{
					// Fatal error
					$this->setError($error);

					return false;
				}
				else
				{
					// Not fatal error
					$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_BATCH_MOVE_ROW_NOT_FOUND', $pk));
					continue;
				}
			}

			// Set the new topic ID
			$this->table->topicid = $topicId;

			// Check the row.
			if (!$this->table->check())
			{
				$this->setError($this->table->getError());

				return false;
			}

			// Store the row.
			if (!$this->table->store())
			{
				$this->setError($this->table->getError());

				return false;
			}
		}

		// Clean the cache
		$this->cleanCache();

		return true;
	}

	/**
	 * Method to test whether a record can be deleted.
	 *
	 * @param   object  $record  A record object.
	 *
	 * @return  boolean  True if allowed to delete the record. Defaults to the permission set in the component.
	 *
	 * @since   1.6
	 */
	protected function canDelete($record)
	{
		if (!empty($record->id))
		{
			if ($record->state != -2)
			{
				return false;
			}
			$user = JFactory::getUser();

			return $user->authorise('core.delete', 'com_faqbookpro.question.' . (int) $record->id);
		}

		return false;
	}

	/**
	 * Method to test whether a record can have its state edited.
	 *
	 * @param   object  $record  A record object.
	 *
	 * @return  boolean  True if allowed to change the state of the record. Defaults to the permission set in the component.
	 *
	 * @since   1.6
	 */
	protected function canEditState($record)
	{
		$user = JFactory::getUser();

		// Check for existing question.
		if (!empty($record->id))
		{
			return $user->authorise('core.edit.state', 'com_faqbookpro.question.' . (int) $record->id);
		}
		// New question, so check against the topic.
		elseif (!empty($record->topicid))
		{
			return $user->authorise('core.edit.state', 'com_faqbookpro.topic.' . (int) $record->topicid);
		}
		// Default to component settings if neither question nor topic known.
		else
		{
			return parent::canEditState('com_faqbookpro');
		}
	}

	/**
	 * Prepare and sanitise the table data prior to saving.
	 *
	 * @param   JTable  $table  A JTable object.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function prepareTable($table)
	{
		// Set the publish date to now
		$db = $this->getDbo();

		if ($table->state == 1 && (int) $table->publish_up == 0)
		{
			$table->publish_up = JFactory::getDate()->toSql();
		}

		if ($table->state == 1 && intval($table->publish_down) == 0)
		{
			$table->publish_down = $db->getNullDate();
		}

		// Increment the content version number.
		$table->version++;

		// Reorder the questions within the topic so the new question is first
		if (empty($table->id))
		{
			$table->reorder('topicid = ' . (int) $table->topicid . ' AND state >= 0');
		}
	}

	/**
	 * Returns a Table object, always creating it.
	 *
	 * @param   string  $type    The table type to instantiate
	 * @param   string  $prefix  A prefix for the table class name. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  JTable    A database object
	 */
	public function getTable($type = 'Question', $prefix = 'FAQBookProTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get a single record.
	 *
	 * @param   integer  $pk  The id of the primary key.
	 *
	 * @return  mixed  Object on success, false on failure.
	 */
	public function getItem($pk = null)
	{
		if ($item = parent::getItem($pk))
		{
			// Convert the params field to an array.
			$registry = new Registry;
			$registry->loadString($item->attribs);
			$item->attribs = $registry->toArray();

			// Convert the metadata field to an array.
			$registry = new Registry;
			$registry->loadString($item->metadata);
			$item->metadata = $registry->toArray();

			// Convert the images field to an array.
			$registry = new Registry;
			$registry->loadString($item->images);
			$item->images = $registry->toArray();

			// Convert the urls field to an array.
			$registry = new Registry;
			$registry->loadString($item->urls);
			$item->urls = $registry->toArray();

			$item->articletext = trim($item->fulltext) != '' ? $item->introtext . "<hr id=\"system-readmore\" />" . $item->fulltext : $item->introtext;

		}

		return $item;
	}

	/**
	 * Method to get the record form.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  mixed  A JForm object on success, false on failure
	 *
	 * @since   1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_faqbookpro.question', 'question', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form))
		{
			return false;
		}
		$jinput = JFactory::getApplication()->input;

		// The front end calls this model and uses a_id to avoid id clashes so we need to check for that first.
		if ($jinput->get('a_id'))
		{
			$id = $jinput->get('a_id', 0);
		}
		// The back end uses id so we use that the rest of the time and set it to 0 by default.
		else
		{
			$id = $jinput->get('id', 0);
		}
		// Determine correct permissions to check.
		if ($this->getState('question.id'))
		{
			$id = $this->getState('question.id');

			// Existing record. Can only edit in selected topics.
			$form->setFieldAttribute('topicid', 'action', 'core.edit');

			// Existing record. Can only edit own questions in selected topics.
			$form->setFieldAttribute('topicid', 'action', 'core.edit.own');
		}
		else
		{
			// New record. Can only create in selected topics.
			$form->setFieldAttribute('topicid', 'action', 'core.create');
		}

		$user = JFactory::getUser();

		// Check for existing question.
		// Modify the form based on Edit State access controls.
		if ($id != 0 && (!$user->authorise('core.edit.state', 'com_faqbookpro.question.' . (int) $id))
			|| ($id == 0 && !$user->authorise('core.edit.state', 'com_faqbookpro')))
		{
			// Disable fields for display.
			$form->setFieldAttribute('featured', 'disabled', 'true');
			$form->setFieldAttribute('ordering', 'disabled', 'true');
			$form->setFieldAttribute('publish_up', 'disabled', 'true');
			$form->setFieldAttribute('publish_down', 'disabled', 'true');
			$form->setFieldAttribute('state', 'disabled', 'true');

			// Disable fields while saving.
			// The controller has already verified this is a question you can edit.
			$form->setFieldAttribute('featured', 'filter', 'unset');
			$form->setFieldAttribute('ordering', 'filter', 'unset');
			$form->setFieldAttribute('publish_up', 'filter', 'unset');
			$form->setFieldAttribute('publish_down', 'filter', 'unset');
			$form->setFieldAttribute('state', 'filter', 'unset');
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 *
	 * @since   1.6
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$app = JFactory::getApplication();
		$data = $app->getUserState('com_faqbookpro.edit.question.data', array());

		if (empty($data))
		{
			$data = $this->getItem();

			// Pre-select some filters (Status, Topic, Language, Access) in edit form if those have been selected in Question Manager
			if ($this->getState('question.id') == 0)
			{
				$filters = (array) $app->getUserState('com_faqbookpro.questions.filter');
				$data->set(
					'state',
					$app->input->getInt(
						'state',
						((isset($filters['published']) && $filters['published'] !== '') ? $filters['published'] : null)
					)
				);
				$data->set('topicid', $app->input->getInt('topicid', (!empty($filters['topic_id']) ? $filters['topic_id'] : null)));
				$data->set('language', $app->input->getString('language', (!empty($filters['language']) ? $filters['language'] : null)));
				$data->set('access', $app->input->getInt('access', (!empty($filters['access']) ? $filters['access'] : JFactory::getConfig()->get('access'))));
			}
		}

		// If there are params fieldsets in the form it will fail with a registry object
		if (isset($data->params) && $data->params instanceof Registry)
		{
			$data->params = $data->params->toArray();
		}

		$this->preprocessData('com_faqbookpro.question', $data);

		return $data;
	}

	/**
	 * Method to save the form data.
	 *
	 * @param   array  $data  The form data.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   1.6
	 */
	public function save($data)
	{
		$input = JFactory::getApplication()->input;
		$filter  = JFilterInput::getInstance();

		if (isset($data['metadata']) && isset($data['metadata']['author']))
		{
			$data['metadata']['author'] = $filter->clean($data['metadata']['author'], 'TRIM');
		}

		if (isset($data['created_by_alias']))
		{
			$data['created_by_alias'] = $filter->clean($data['created_by_alias'], 'TRIM');
		}

		if (isset($data['images']) && is_array($data['images']))
		{
			$registry = new Registry;
			$registry->loadArray($data['images']);
			$data['images'] = (string) $registry;
		}

		if (isset($data['urls']) && is_array($data['urls']))
		{
			foreach ($data['urls'] as $i => $url)
			{
				if ($url != false && ($i == 'urla' || $i == 'urlb' || $i == 'urlc'))
				{
					$data['urls'][$i] = JStringPunycode::urlToPunycode($url);
				}
			}

			$registry = new Registry;
			$registry->loadArray($data['urls']);
			$data['urls'] = (string) $registry;
		}

		// Alter the title for save as copy
		if ($input->get('task') == 'save2copy')
		{
			$origTable = clone $this->getTable();
			$origTable->load($input->getInt('id'));

			if ($data['title'] == $origTable->title)
			{
				list($title, $alias) = $this->generateNewTitle($data['topicid'], $data['alias'], $data['title']);
				$data['title'] = $title;
				$data['alias'] = $alias;
			}
			else
			{
				if ($data['alias'] == $origTable->alias)
				{
					$data['alias'] = '';
				}
			}

			$data['state'] = 0;
		}

		// Automatic handling of alias for empty fields
		if (in_array($input->get('task'), array('apply', 'save', 'save2new')) && (!isset($data['id']) || (int) $data['id'] == 0))
		{
			if ($data['alias'] == null)
			{
				if (JFactory::getConfig()->get('unicodeslugs') == 1)
				{
					$data['alias'] = JFilterOutput::stringURLUnicodeSlug($data['title']);
				}
				else
				{
					$data['alias'] = JFilterOutput::stringURLSafe($data['title']);
				}

				$table = JTable::getInstance('Question', 'FAQBookProTable');

				if ($table->load(array('alias' => $data['alias'], 'topicid' => $data['topicid'])))
				{
					$msg = JText::_('COM_FAQBOOKPRO_SAVE_WARNING');
				}

				list($title, $alias) = $this->generateNewTitle($data['topicid'], $data['alias'], $data['title']);
				$data['alias'] = $alias;

				if (isset($msg))
				{
					JFactory::getApplication()->enqueueMessage($msg, 'warning');
				}
			}
		}

		if (parent::save($data))
		{

			if (isset($data['featured']))
			{
				$this->featured($this->getState($this->getName() . '.id'), $data['featured']);
			}

			return true;
		}

		return false;
	}

	/**
	 * Method to toggle the featured setting of questions.
	 *
	 * @param   array    $pks    The ids of the items to toggle.
	 * @param   integer  $value  The value to toggle to.
	 *
	 * @return  boolean  True on success.
	 */
	public function featured($pks, $value = 0)
	{
		// Sanitize the ids.
		$pks = (array) $pks;
		JArrayHelper::toInteger($pks);

		if (empty($pks))
		{
			$this->setError(JText::_('COM_FAQBOOKPRO_NO_ITEM_SELECTED'));

			return false;
		}

		//$table = $this->getTable('Featured', 'FAQBookProTable');

		try
		{
			$db = $this->getDbo();
			$query = $db->getQuery(true)
						->update($db->quoteName('#__minitek_faqbook_questions'))
						->set('featured = ' . (int) $value)
						->where('id IN (' . implode(',', $pks) . ')');
			$db->setQuery($query);
			$db->execute();			
		}
		catch (Exception $e)
		{
			$this->setError($e->getMessage());
			return false;
		}

		//$table->reorder();

		$this->cleanCache();

		return true;
	}

	/**
	 * A protected method to get a set of ordering conditions.
	 *
	 * @param   object  $table  A record object.
	 *
	 * @return  array  An array of conditions to add to add to ordering queries.
	 *
	 * @since   1.6
	 */
	protected function getReorderConditions($table)
	{
		$condition = array();
		$condition[] = 'topicid = ' . (int) $table->topicid;

		return $condition;
	}

	/**
	 * Auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   JForm   $form   The form object
	 * @param   array   $data   The data to be merged into the form object
	 * @param   string  $group  The plugin group to be executed
	 *
	 * @return  void
	 *
	 * @since    3.0
	 */
	protected function preprocessForm(JForm $form, $data, $group = 'faqbookpro')
	{
		parent::preprocessForm($form, $data, $group);
	}

	/**
	 * Custom clean the cache of com_faqbookpro and modules
	 *
	 * @param   string   $group      The cache group
	 * @param   integer  $client_id  The ID of the client
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function cleanCache($group = null, $client_id = 0)
	{
		parent::cleanCache('com_faqbookpro');
	}
	
	/**
	 * Sets the question as answered
	 */
	public function questionAnswered($id)
	{
		try
		{
			$db = $this->getDbo();
			$query = $db->getQuery(true)
						->update($db->quoteName('#__minitek_faqbook_questions'))
						->set('answered = 1')
						->where('id = '.$db->quote($id));
			$db->setQuery($query);
			$db->execute();			
		}
		catch (Exception $e)
		{
			$this->setError($e->getMessage());
			return false;
		}
	}
	
	/**
	 * Sets the question as not answered
	 */
	public function questionNotAnswered($id)
	{
		try
		{
			$db = $this->getDbo();
			$query = $db->getQuery(true)
						->update($db->quoteName('#__minitek_faqbook_questions'))
						->set('answered = 0')
						->where('id = '.$db->quote($id));
			$db->setQuery($query);
			$db->execute();			
		}
		catch (Exception $e)
		{
			$this->setError($e->getMessage());
			return false;
		}
	}
}
