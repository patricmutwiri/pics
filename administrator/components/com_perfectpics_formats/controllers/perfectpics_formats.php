<?php
/**
 * @author		
 * @copyright	
 * @license		
 */

defined("_JEXEC") or die("Restricted access");

/**
 * PerfectPics_Formats item controller class.
 *
 * @package     Perfectpics_formats
 * @subpackage  Controllers
 */
class Perfectpics_formatsControllerPerfectPics_Formats extends JControllerForm
{
	/**
	 * The URL view item variable.
	 *
	 * @var    string
	 * @since  12.2
	 */
	protected $view_item = 'perfectpics_formats';

	/**
	 * The URL view list variable.
	 *
	 * @var    string
	 * @since  12.2
	 */
	protected $view_list = 'perfectpics_formatss';

	/**
	 * Method override to check if you can add a new record.
	 *
	 * @param   array  $data  An array of input data.
	 *
	 * @return  boolean
	 *
	 * @since   1.6
	 */
	protected function allowAdd($data = array())
	{
		$user       = JFactory::getUser();
		$categoryId = JArrayHelper::getValue($data, 'catid', $this->input->getInt('catid'), 'int');

		if ($categoryId)
		{
			// If the category has been passed in the data or URL check it.
			return $user->authorise('core.create', 'com_perfectpics_formats.category.'.$categoryId);
		}

		return parent::allowAdd();
	}
	
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
		$asset    = 'com_perfectpics_formats.perfectpics_formats.' . $recordId;

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
	 * Method to run batch operations.
	 *
	 * @param   object  $model  The model.
	 *
	 * @return  boolean   True if successful, false otherwise and internal error is set.
	 *
	 * @since   2.5
	 */
	public function batch($model = null)
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Set the model
		$model = $this->getModel('PerfectPics_Formats', 'Perfectpics_formatsModel', array());

		// Preset the redirect
		$this->setRedirect(JRoute::_('index.php?option=com_perfectpics_formats&view=perfectpics_formatss' . $this->getRedirectToListAppend(), false));

		return parent::batch($model);
	}
}
?>