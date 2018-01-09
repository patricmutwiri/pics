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
JHtml::_('behavior.formvalidation');
?>
<div class="grid-construct-x">
	<section class="grid-construct reg-and-login reg-and-login--reg">
<div class="reg-and-login__col">
<h1 class="reg-and-login__head">Sign up</h1>
		<div class="registration<?php echo $this->pageclass_sfx?>">
			<?php if ($this->params->get('show_page_heading')) : ?>
				<h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
			<?php endif; ?>
<p class="form__description getting_started__description" id="form-register-description">Create an account to print your books and ebooks</p>
			<form id="member-registration" action="<?php echo JRoute::_('index.php?option=com_users&task=registration.register'); ?>" method="post" class="form-validate" enctype="multipart/form-data">

				<?php foreach ($this->form->getFieldsets() as $fieldset): // Iterate through the form fieldsets and display each one.?>
					<?php $fields = $this->form->getFieldset($fieldset->name);?>
					<?php if (count($fields)):?>
						<?php foreach ($fields as $field) :// Iterate through the fields in the set and display them.?>
							<?php if ($field->hidden):// If the field is hidden, just display the input.?>
								<?php echo $field->input;?>
							<?php else:?>
								<div class="form__group form__group--has-messaging">
									<?php //echo $field->label; ?>
									<?php if (!$field->required && $field->type != 'Spacer') : ?>
										<span class="optional"><?php echo JText::_('COM_USERS_OPTIONAL');?></span>
									<?php endif; ?>

									<div class="group-control">
										<?php echo $field->input;?>
									</div>
								</div>
							<?php endif;?>
						<?php endforeach;?>
					<?php endif;?>
				<?php endforeach;?>

				<div class="form__group form__group--centered form__group--spaced">
					<button type="submit" id="reg_submit" class="btn"><?php echo JText::_('SIGN UP');?></button>
					<!--<a class="btn btn-danger btn-lg" href="<?php echo JRoute::_('');?>" title="<?php echo JText::_('JCANCEL');?>"><?php echo JText::_('JCANCEL');?></a>-->
					<input type="hidden" name="option" value="com_users" />
					<input type="hidden" name="task" value="registration.register" />
				</div>
				<?php echo JHtml::_('form.token');?>
			</form>
		</div>
	</div>
	<div class="reg-and-login__col">
<h1 class="reg-and-login__subhead">Have an account?</h1>
<p class="reg-and-login__copy"><a href="index.php?option=com_users&view=login" id="lnk-login">Log In</a></p>
</div>
</div>
