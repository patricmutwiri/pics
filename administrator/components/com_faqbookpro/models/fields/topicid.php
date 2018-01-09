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

JFormHelper::loadFieldClass('list');

/**
 * Form Field class for the Joomla Framework.
 *
 * @since  1.6
 */
class JFormFieldTopicId extends JFormFieldList
{
	/**
	 * A flexible topic list that respects access controls
	 *
	 * @var        string
	 * @since   1.6
	 */
	public $type = 'TopicId';

	/**
	 * Method to get a list of topics that respects access controls and can be used for
	 * either topic assignment or parent topic assignment in edit screens.
	 * Use the parent element to indicate that the field will be used for assigning parent topics.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   1.6
	 */
	protected function getOptions()
	{
		$options = array();
		$published = $this->element['published'] ? $this->element['published'] : array(0, 1);
		$name = (string) $this->element['name'];

		// Let's get the id for the current topic.
		$jinput = JFactory::getApplication()->input;

		// Load the topic options.

		// For topics the old topic is the topic id or 0 for new topic.
		if ($this->element['parent'])
		{
			$oldCat = $jinput->get('id', 0);
			$oldParent = $this->form->getValue($name, 0);
		}
		else
			// For items the old topic is the topic they are in when opened or 0 if new.
		{
			$oldCat = $this->form->getValue($name, 0);
		}

		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('DISTINCT a.id AS value, a.title AS text, a.level, a.published, a.lft');
		$subQuery = $db->getQuery(true)
			->select('id,title,level,published,parent_id,lft,rgt')
			->from('#__minitek_faqbook_topics');
		$subQuery->where('level > 0');

		if ($this->element['parent'] == true)
		{
			$subQuery->where('parent_id = 0');
		}

		// Filter language
		if (!empty($this->element['language']))
		{
			$subQuery->where('language = ' . $db->quote($this->element['language']));
		}

		// Filter on the published state
		if (is_numeric($published))
		{
			$subQuery->where('published = ' . (int) $published);
		}
		elseif (is_array($published))
		{
			JArrayHelper::toInteger($published);
			$subQuery->where('published IN (' . implode(',', $published) . ')');
		}

		$query->from('(' . $subQuery->__toString() . ') AS a')
			->join('LEFT', $db->quoteName('#__minitek_faqbook_topics') . ' AS b ON a.lft > b.lft AND a.rgt < b.rgt');
		$query->order('a.lft ASC');

		// If parent isn't explicitly stated but we are in com_faqbookpro assume we want parents
		if ($oldCat != 0 && ($this->element['parent'] == true))
		{
			// Prevent parenting to children of this item.
			// To rearrange parents and children move the children up, not the parents down.
			$query->join('LEFT', $db->quoteName('#__minitek_faqbook_topics') . ' AS p ON p.id = ' . (int) $oldCat)
				->where('NOT(a.lft >= p.lft AND a.rgt <= p.rgt)');

			$rowQuery = $db->getQuery(true);
			$rowQuery->select('a.id AS value, a.title AS text, a.level, a.parent_id')
				->from('#__minitek_faqbook_topics AS a')
				->where('a.id = ' . (int) $oldCat);
			$db->setQuery($rowQuery);
			$row = $db->loadObject();
		}

		// Get the options.
		$db->setQuery($query);

		try
		{
			$options = $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			JError::raiseWarning(500, $e->getMessage());
		}

		// Pad the option text with spaces using depth level as a multiplier.
		for ($i = 0, $n = count($options); $i < $n; $i++)
		{
			// Translate ROOT
			if ($this->element['parent'] == true)
			{
				if ($options[$i]->level == 0)
				{
					$options[$i]->text = JText::_('JGLOBAL_ROOT_PARENT');
				}
			}

			if ($options[$i]->published == 1)
			{
				$options[$i]->text = str_repeat('- ', $options[$i]->level) . $options[$i]->text;
			}
			else
			{
				$options[$i]->text = str_repeat('- ', $options[$i]->level) . '[' . $options[$i]->text . ']';
			}
		}

		// Get the current user object.
		$user = JFactory::getUser();

		// For new items we want a list of topics you are allowed to create in.
		if ($oldCat == 0)
		{
			foreach ($options as $i => $option)
			{
				/* To take save or create in a topic you need to have create rights for that topic
				 * unless the item is already in that topic.
				 * Unset the option if the user isn't authorised for it. In this field assets are always topics.
				 */
				if ($user->authorise('core.create', 'com_faqbookpro.topic.' . $option->value) != true && $option->level != 0)
				{
					unset($options[$i]);
				}
			}
		}
		// If you have an existing topic id things are more complex.
		else
		{
			/* If you are only allowed to edit in this topic but not edit.state, you should not get any
			 * option to change the topic parent for a topic or the topic for a content item,
			 * but you should be able to save in that topic.
			 */
			foreach ($options as $i => $option)
			{
				if ($user->authorise('core.edit.state', 'com_faqbookpro.topic.' . $oldCat) != true && !isset($oldParent))
				{
					if ($option->value != $oldCat)
					{
						unset($options[$i]);
					}
				}

				if ($user->authorise('core.edit.state', 'com_faqbookpro.topic.' . $oldCat) != true
					&& (isset($oldParent))
					&& $option->value != $oldParent)
				{
					unset($options[$i]);
				}

				// However, if you can edit.state you can also move this to another topic for which you have
				// create permission and you should also still be able to save in the current topic.
				if (($user->authorise('core.create', 'com_faqbookpro.topic.' . $option->value) != true)
					&& ($option->value != $oldCat && !isset($oldParent)))
				{
					{
						unset($options[$i]);
					}
				}

				if (($user->authorise('core.create', 'com_faqbookpro.topic.' . $option->value) != true)
					&& (isset($oldParent))
					&& $option->value != $oldParent)
				{
					{
						unset($options[$i]);
					}
				}
			}
		}

		if (($this->element['parent'] == true)
			&& (isset($row) && !isset($options[0]))
			&& isset($this->element['show_root']))
		{
			if ($row->parent_id == '1')
			{
				$parent = new stdClass;
				$parent->text = JText::_('JGLOBAL_ROOT_PARENT');
				array_unshift($options, $parent);
			}

			array_unshift($options, JHtml::_('select.option', '0', JText::_('JGLOBAL_ROOT')));
		}

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
