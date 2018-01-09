<?php
/**
* @title			Minitek FAQ Book
* @copyright   		Copyright (C) 2011-2015 Minitek, All rights reserved.
* @license   		GNU General Public License version 3 or later.
* @author url   	http://www.minitek.gr/
* @developers   	Minitek.gr
*/

// no direct access
defined('JPATH_PLATFORM') or die;

class FAQBookProTableSection extends JTable
{
	public function __construct($db)
	{
		parent::__construct('#__minitek_faqbook_sections', 'id', $db);
	}

	protected function _getAssetName()
	{
		$k = $this->_tbl_key;
		return 'com_faqbookpro.section.' . (int) $this->$k;
	}

	protected function _getAssetTitle()
	{
		return $this->title;
	}

	protected function _getAssetParentId(JTable $table = null, $id = null)
	{
		$assetId = null;

		// Return the asset id.
		if ($assetId)
		{
			return $assetId;
		}
		else
		{
			return parent::_getAssetParentId($table, $id);
		}
	}

	public function bind($array, $ignore = '')
	{
		// Bind the attribs
		if (isset($array['attribs']) && is_array($array['attribs']))
		{
			$registry = new JRegistry;
			$registry->loadArray($array['attribs']);
			$array['attribs'] = (string) $registry;
		}
		
		// Bind the metadata
		if (isset($array['metadata']) && is_array($array['metadata']))
		{
			$registry = new JRegistry;
			$registry->loadArray($array['metadata']);
			$array['metadata'] = (string) $registry;
		}

		// Bind the rules.
		if (isset($array['rules']) && is_array($array['rules']))
		{
			$rules = new JAccessRules($array['rules']);
			$this->setRules($rules);
		}

		return parent::bind($array, $ignore);
	}

	public function check()
	{
		if (trim($this->title) == '')
		{
			$this->setError(JText::_('COM_FAQBOOKPRO_WARNING_PROVIDE_VALID_NAME'));
			return false;
		}

		if (trim($this->alias) == '')
		{
			$this->alias = $this->title;
		}

		$this->alias = JApplication::stringURLSafe($this->alias);

		if (trim(str_replace('-', '', $this->alias)) == '')
		{
			$this->alias = JFactory::getDate()->format('Y-m-d-H-i-s');
		}

		if (trim(str_replace('&nbsp;', '', $this->description)) == '')
		{
			$this->description = '';
		}

		// Clean up keywords -- eliminate extra spaces between phrases
		// and cr (\r) and lf (\n) characters from string
		if (!empty($this->metakey))
		{
			// Only process if not empty

			// Array of characters to remove
			$bad_characters = array("\n", "\r", "\"", "<", ">");

			// Remove bad characters
			$after_clean = JString::str_ireplace($bad_characters, "", $this->metakey);

			// Create array using commas as delimiter
			$keys = explode(',', $after_clean);

			$clean_keys = array();

			foreach ($keys as $key)
			{
				if (trim($key))
				{
					// Ignore blank keywords
					$clean_keys[] = trim($key);
				}
			}
			// Put array back together delimited by ", "
			$this->metakey = implode(", ", $clean_keys);
		}

		return true;
	}

	public function store($updateNulls = false)
	{
		$date = JFactory::getDate();
		$user = JFactory::getUser();

		if ($this->id)
		{
			// Existing item
		}
		else
		{
			// New article. An article created and created_by field can be set by the user,
			// so we don't touch either of these if they are set.
			if (!(int) $this->created_time)
			{
				$this->created_time = $date->toSql();
			}

			if (empty($this->created_user_id))
			{
				$this->created_user_id = $user->get('id');
			}
		}

		// Verify that the alias is unique
		$table = JTable::getInstance('Section', 'FAQBookProTable');

		if ($table->load(array('alias' => $this->alias)) && ($table->id != $this->id || $this->id == 0))
		{
			$this->setError(JText::_('JLIB_DATABASE_ERROR_ARTICLE_UNIQUE_ALIAS'));

			return false;
		}

		return parent::store($updateNulls);
	}

	public function publish($pks = null, $state = 1, $userId = 0)
	{
		$k = $this->_tbl_key;

		// Sanitize input.
		JArrayHelper::toInteger($pks);
		$userId = (int) $userId;
		$state = (int) $state;

		// If there are no primary keys set check to see if the instance key is set.
		if (empty($pks))
		{
			if ($this->$k)
			{
				$pks = array($this->$k);
			}
			// Nothing to set publishing state on, return false.
			else
			{
				$this->setError(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
				return false;
			}
		}

		// Build the WHERE clause for the primary keys.
		$where = $k . '=' . implode(' OR ' . $k . '=', $pks);

		// Determine if there is checkin support for the table.
		if (!property_exists($this, 'checked_out') || !property_exists($this, 'checked_out_time'))
		{
			$checkin = '';
		}
		else
		{
			$checkin = ' AND (checked_out = 0 OR checked_out = ' . (int) $userId . ')';
		}

		// Update the publishing state for rows with the given primary keys.
		$query = $this->_db->getQuery(true)
			->update($this->_db->quoteName($this->_tbl))
			->set($this->_db->quoteName('state') . ' = ' . (int) $state)
			->where('(' . $where . ')' . $checkin);
		$this->_db->setQuery($query);

		try
		{
			$this->_db->execute();
		}
		catch (RuntimeException $e)
		{
			$this->setError($e->getMessage());
			return false;
		}

		// If checkin is supported and all rows were adjusted, check them in.
		if ($checkin && (count($pks) == $this->_db->getAffectedRows()))
		{
			// Checkin the rows.
			foreach ($pks as $pk)
			{
				$this->checkin($pk);
			}
		}

		// If the JTable instance value is in the list of primary keys that were set, set the instance.
		if (in_array($this->$k, $pks))
		{
			$this->state = $state;
		}

		$this->setError('');

		return true;
	}
}
