<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');

?>
<div class="grid-construct-x">
	<section class="grid-construct reg-and-login">
<div class="reg-and-login__col">
<h1 class="reg-and-login__head">Log In</h1>
		<div class="page-login-form login<?php echo $this->pageclass_sfx?>">
			<?php if ($this->params->get('show_page_heading')) : ?>
				<h1>
					<?php echo $this->escape($this->params->get('page_heading')); ?>
				</h1>
			<?php endif; ?>

			<?php if (($this->params->get('logindescription_show') == 1 && str_replace(' ', '', $this->params->get('login_description')) != '') || $this->params->get('login_image') != '') : ?>
			<div class="login-description text-center">
			<?php endif; ?>

				<?php if ($this->params->get('logindescription_show') == 1) : ?>
					<?php echo $this->params->get('login_description'); ?>
				<?php endif; ?>

				<?php if (($this->params->get('login_image') != '')) :?>
					<img src="<?php echo $this->escape($this->params->get('login_image')); ?>" class="login-image" alt="<?php echo JTEXT::_('COM_USERS_LOGIN_IMAGE_ALT')?>"/>
				<?php endif; ?>

			<?php if (($this->params->get('logindescription_show') == 1 && str_replace(' ', '', $this->params->get('login_description')) != '') || $this->params->get('login_image') != '') : ?>
			</div>
			<?php endif; ?>

			<form action="<?php echo JRoute::_('index.php?option=com_users&task=user.login'); ?>" method="post" class="form-validate">

				<?php /* Set placeholder for username, password and secretekey */
					$this->form->setFieldAttribute( 'username', 'hint', JText::_('COM_USERS_LOGIN_USERNAME_LABEL') );
					$this->form->setFieldAttribute( 'password', 'hint', JText::_('JGLOBAL_PASSWORD') );
					$this->form->setFieldAttribute( 'secretkey', 'hint', JText::_('JGLOBAL_SECRETKEY') );
				?>

				<?php foreach ($this->form->getFieldset('credentials') as $field) : ?>
					<?php if (!$field->hidden) : ?>
						<div class="form-group">
							<div class="group-control">
								<?php echo $field->input; ?>
							</div>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>

				<?php if ($this->tfa): ?>
					<div class="form-group">
						<div class="group-control">
							<?php echo $this->form->getField('secretkey')->input; ?>
						</div>
					</div>
				<?php endif; ?>

				<?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
					<label class="form__label--checkbox" for="remember_me">
						<input class="form__control--checkbox" id="remember" name="remember_me" value="true" type="checkbox">
						Remember Me
					</label>
				<?php endif; ?>

				<div class="form__group form__group--centered form__group--spaced-extra">
					<button type="submit" id="sign-in-button" class="btn">
						<?php echo JText::_('LOGIN'); ?>
					</button>
				</div>

				<input type="hidden" name="return" value="<?php echo base64_encode($this->params->get('login_redirect_url', $this->form->getValue('return'))); ?>" />
				<?php echo JHtml::_('form.token'); ?>

			</form>
		</div>

		<div class="form-links">
			<ul>
				<li>
					<a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>">
					<?php echo JText::_('COM_USERS_LOGIN_RESET'); ?></a>
				</li>
				<li>
					<a href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>">
					<?php echo JText::_('COM_USERS_LOGIN_REMIND'); ?></a>
				</li>
				<?php
				$usersConfig = JComponentHelper::getParams('com_users');
				if ($usersConfig->get('allowUserRegistration')) : ?>
				<?/*<li class="hidden">
					<a href="<?php echo JRoute::_('index.php?option=com_users&view=registration'); ?>">
						<?php echo JText::_('COM_USERS_LOGIN_REGISTER'); ?></a>
				</li>*/?>
				<?php endif; ?>
			</ul>
		</div>

</div>
<div class="reg-and-login__col">
<h2 class="reg-and-login__subhead">Need an account?</h2>
<p class="reg-and-login__copy"><a href="<?php echo JRoute::_('index.php?option=com_users&view=registration'); ?>" id="lnk-register">Sign up</a></p>
</div>
</div>
</section>
