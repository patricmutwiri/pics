<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_contact
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Controller for single contact view
 *
 * @since  1.5.19
 */
class ContactControllerContact extends JControllerForm
{
	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  JModelLegacy  The model.
	 *
	 * @since   1.6.4
	 */
	public function getModel($name = '', $prefix = '', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, array('ignore_request' => false));
	}

	/**
	 * Method to submit the contact form and send an email.
	 *
	 * @return  boolean  True on success sending the email. False on failure.
	 *
	 * @since   1.5.19
	 */
	public function submit()
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$app    = JFactory::getApplication();
		$model  = $this->getModel('contact');
		$params = JComponentHelper::getParams('com_contact');
		$stub   = $this->input->getString('id');
		$id     = (int) $stub;

		// Get the data from POST
		$data    = $this->input->post->get('jform', array(), 'array');
		$contact = $model->getItem($id);

		$params->merge($contact->params);

		// Check for a valid session cookie
		if ($params->get('validate_session', 0))
		{
			if (JFactory::getSession()->getState() != 'active')
			{
				JError::raiseWarning(403, JText::_('COM_CONTACT_SESSION_INVALID'));

				// Save the data in the session.
				$app->setUserState('com_contact.contact.data', $data);

				// Redirect back to the contact form.
				$this->setRedirect(JRoute::_('index.php?option=com_contact&view=contact&id=' . $stub, false));

				return false;
			}
		}

		// Contact plugins
		JPluginHelper::importPlugin('contact');
		$dispatcher = JEventDispatcher::getInstance();

		// Validate the posted data.
		$form = $model->getForm();

		if (!$form)
		{
			JError::raiseError(500, $model->getError());

			return false;
		}

		$validate = $model->validate($form, $data);

		if ($validate === false)
		{
			// Get the validation messages.
			$errors = $model->getErrors();

			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
			{
				if ($errors[$i] instanceof Exception)
				{
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				}
				else
				{
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}

			// Save the data in the session.
			$app->setUserState('com_contact.contact.data', $data);

			// Redirect back to the contact form.
			$this->setRedirect(JRoute::_('index.php?option=com_contact&view=contact&id=' . $stub, false));

			return false;
		}

		// Validation succeeded, continue with custom handlers
		$results = $dispatcher->trigger('onValidateContact', array(&$contact, &$data));

		foreach ($results as $result)
		{
			if ($result instanceof Exception)
			{
				return false;
			}
		}

		// Passed Validation: Process the contact plugins to integrate with other applications
		$dispatcher->trigger('onSubmitContact', array(&$contact, &$data));

		// Send the email
		$sent = false;

		if (!$params->get('custom_reply'))
		{
			$sent = $this->_sendEmail($data, $contact, $params->get('show_email_copy'));
		}

		// Set the success message if it was a success
		if (!($sent instanceof Exception))
		{
			$msg = JText::_('COM_CONTACT_EMAIL_THANKS');
		}
		else
		{
			$msg = '';
		}

		// Flush the data from the session
		$app->setUserState('com_contact.contact.data', null);

		// Redirect if it is set in the parameters, otherwise redirect back to where we came from
		if ($contact->params->get('redirect'))
		{
			$this->setRedirect($contact->params->get('redirect'), $msg);
		}
		else
		{
			$this->setRedirect(JRoute::_('index.php?option=com_contact&view=contact&id=' . $stub, false), $msg);
		}

		return true;
	}

	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @param   array     $data                  The data to send in the email.
	 * @param   stdClass  $contact               The user information to send the email to
	 * @param   boolean   $copy_email_activated  True to send a copy of the email to the user.
	 *
	 * @return  boolean  True on success sending the email, false on failure.
	 *
	 * @since   1.6.4
	 */
	private function _sendEmail($data, $contact, $copy_email_activated)
	{
			$app = JFactory::getApplication();

			if ($contact->email_to == '' && $contact->user_id != 0)
			{
				$contact_user      = JUser::getInstance($contact->user_id);
				$contact->email_to = $contact_user->get('email');
			}

			$mailfrom = $app->get('mailfrom');
			$fromname = $app->get('fromname'). ' Website Contact us enquiry';
			$sitename = $app->get('sitename');

			$name    = $data['contact_name'];
			$phone    = $data['contact_phone'];
			$email   = JStringPunycode::emailToPunycode($data['contact_email']);
			$subject = $data['contact_subject'];
			//$info = explode('|', $data['contact_department']);
			//$receipient_email = $info[0];
			//$department = $info[1];
			$message    = $data['contact_message'];
			$ip = $_SERVER['REMOTE_ADDR'];
			$main_recepient = $contact->email_to;
			$receipients = explode(',', $contact->email_others);
			//$receipients[] = $receipient_email;
			//add to datatase
			$db = JFactory::getDBO();
			$query = "INSERT INTO #__contact_enquiries (name, telephone, email, subject, message, date, ip) VALUES ('" .$name. "', '" .$phone. "', '" .$email. "', '" .$subject. "', '" .$message. "', NOW(), '" .$ip. "')";
			$db->setQuery($query);
			$db->query();
			//end adding to database
			// Prepare email body
			$prefix = JText::sprintf('COM_CONTACT_ENQUIRY_TEXT', JUri::base());
			$body = '<h2 style="font-family:arial;font-size:13px;padding:5px 0px;border-bottom:solid 1px #777">Contact enquiry</h2>';
			$body .= '<p style="font-family:arial;font-size:12px">You\'ve received an enquiry via the contact us form. Please find the details below.</p>';
			$body .= '<table border="1" cellpadding="4" style="border-collapse:collapse;font-family:arial;font-size:12px">';//end table
			$body .= '<tr><td><strong>Name</strong></td><td>' .$name. '</td></tr>';//name
			$body .= '<tr><td><strong>Phone number</strong></td><td>' .$phone. '</td></tr>';//Phone number
			$body .= '<tr><td><strong>Email</strong></td><td>' .$email. '</td></tr>';//Email
			$body .= '<tr><td><strong>Subject</strong></td><td>' .$subject. '</td></tr>';//Subject
			$body .= '<tr><td colspan="2"><strong>Message</strong></td></tr>';//Message
			$body .= '<tr><td colspan="2">' .stripslashes($message). '</td></tr>';//Message
			$body .= '<tr><td><strong>IP</strong></td><td>' .$ip. '</td></tr>';//IP
			$body .= '</table>';//start table
			//$body	= $prefix . "\n" . $name . ' <' . $email . '>' . "\r\n\r\n" . stripslashes($body);

			$mail = JFactory::getMailer();
			$mail->addRecipient($main_recepient);
			for($i = 0; $i < count($receipients); $i++){
				$mail->addCC($receipients[$i]);
			}
			$mail->addReplyTo(array($email, $name));
			$mail->setSender(array($mailfrom, $fromname));
			$mail->setSubject($subject);
			$mail->setBody($body);
			$mail->isHTML(true);
			$sent = $mail->Send();
			// If we are supposed to copy the sender, do so.

			// Check whether email copy function activated
			if ($copy_email_activated == true && !empty($data['contact_email_copy']))
			{
				$copytext    = JText::sprintf('COM_CONTACT_COPYTEXT_OF', $contact->name, $sitename);
				$copytext    .= "\r\n\r\n" . $body;
				$copysubject = JText::sprintf('COM_CONTACT_COPYSUBJECT_OF', $subject);

				$mail = JFactory::getMailer();
				$mail->addRecipient($email);
				$mail->addReplyTo(array($email, $name));
				$mail->setSender(array($mailfrom, $fromname));
				$mail->setSubject($copysubject);
				$mail->setBody($copytext);
				$sent = $mail->Send();
			}

			return $sent;
	}
}
