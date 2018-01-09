<?php
/**
 * @author		
 * @copyright	
 * @license		
 */

defined("_JEXEC") or die("Restricted access");

/**
 * Experts item controller class.
 *
 * @package     Perfect_pics_experts
 * @subpackage  Controllers
 */
class Perfect_pics_expertsControllerExperts extends JControllerForm
{
	/**
	 * The URL view item variable.
	 *
	 * @var    string
	 * @since  12.2
	 */
	protected $view_item = 'experts';

	/**
	 * The URL view list variable.
	 *
	 * @var    string
	 * @since  12.2
	 */
	protected $view_list = 'expertss';
	
	/**
	 * Method override to check if you can edit an existing record.
	 *
	 * @param   array   $data  An array of input data.
	 * @param   string  $key   The name of the key for the primary key; default is id.
	 *
	 * @return  boolean
	 *
	 * @since   1.6
	 */
	protected function allowEdit($data = array(), $key = 'id')
	{
		$recordId = (int) isset($data[$key]) ? $data[$key] : 0;
		$user     = JFactory::getUser();
		$userId   = $user->get('id');
		$asset    = 'com_perfect_pics_experts.experts.' . $recordId;

		// Check general edit permission first.
		if ($user->authorise('core.edit', $asset))
		{
			return true;
		}

		// Fallback on edit.own.
		// First test if the permission is available.
		if ($user->authorise('core.edit.own', $asset))
		{
			// Now test the owner is the user.
			$ownerId = (int) isset($data['created_by']) ? $data['created_by'] : 0;
			if (empty($ownerId) && $recordId)
			{
				// Need to do a lookup from the model.
				$record = $this->getModel()->getItem($recordId);

				if (empty($record))
				{
					return false;
				}

				$ownerId = $record->created_by;
			}

			// If the owner matches 'me' then do the test.
			if ($ownerId == $userId)
			{
				return true;
			}
		}
		// If users can`t edit own items, there is no more way to get edit permissions
		else
		{
			return false;
		}

		// Since there is no asset tracking, revert to the component permissions.
		return parent::allowEdit($data, $key);
	}
	
	/**
	 * Increment the hit counter for the newsfeed.
	 *
	 * @param   int  $pk  Optional primary key of the item to increment.
	 *
	 * @return  boolean  True if successful; false otherwise and internal error set.
	 *
	 * @since   3.0
	 */
	public function hit($pk = 0)
	{
		$input = JFactory::getApplication()->input;
		$hitcount = $input->getInt('hitcount', 1);

		if ($hitcount)
		{
			$pk = (!empty($pk)) ? $pk : (int) $this->getState('newsfeed.id');
			$db = $this->getDbo();

			$db->setQuery(
				'UPDATE #__newsfeeds' .
				' SET hits = hits + 1' .
				' WHERE id = '.(int) $pk
			);

			try
			{
				$db->execute();
			}
			catch (RuntimeException $e)
			{
				$this->setError($e->getMessage());
				return false;
			}
		}

		return true;
	}
}
?>