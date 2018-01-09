<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_contact
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.tooltip');
if (isset($this->error)) : ?>
	<div class="contact-error">
		<?php echo $this->error; ?>
	</div>
<?php endif; ?>

<div class="contact-form">
	<form id="contact-form" action="<?php echo JRoute::_('index.php'); ?>" method="post" class="form-validate form-horizontal">
		<fieldset>
			<legend><?php echo JText::_('COM_CONTACT_FORM_LABEL'); ?></legend>
			<div class="form-group">
				<label id="jform_contact_name-lbl" for="jform_contact_name" class="hasTooltip required col-sm-2 control-label" title="&lt;strong&gt;Name&lt;/strong&gt;&lt;br /&gt;Your name.">
	Name<span class="star">&#160;*</span></label>
					<div class="col-sm-9">
					<input type="text" name="jform[contact_name]" id="jform_contact_name" value="" class="required form-control" size="30" required aria-required="true" />
				</div>
			</div>
			<div class="form-group">
				<label id="jform_contact_email-lbl" for="jform_contact_email" class="hasTooltip required col-sm-2 control-label" title="&lt;strong&gt;Email&lt;/strong&gt;&lt;br /&gt;Email Address for contact.">
	Email<span class="star">&#160;*</span></label>				<div class="col-sm-9">
					<input type="email" name="jform[contact_email]" class="validate-email required form-control" id="jform_contact_email" value="" size="30" required aria-required="true" />				</div>
			</div>

			<div class="form-group">
				<label id="jform_contact_phone-lbl" for="jform_contact_phone" class="hasTooltip required col-sm-2 control-label" title="&lt;strong&gt;Phone&lt;/strong&gt;">
	Phone<span class="star">&#160;*</span></label>
	<div class="col-sm-9">
					<input type="text" name="jform[contact_phone]" id="jform_contact_phone" value="" class="required form-control validate-numeric" size="30" required aria-required="true" />
				</div>
			</div>
			<div class="form-group">
				<label id="jform_contact_emailmsg-lbl" for="jform_contact_emailmsg" class="hasTooltip required col-sm-2 control-label" title="&lt;strong&gt;Subject&lt;/strong&gt;&lt;br /&gt;Enter the subject of your message here.">
	Subject<span class="star">&#160;*</span></label>				<div class="col-sm-9">
					<input type="text" name="jform[contact_subject]" id="jform_contact_emailmsg" value="" class="required form-control" size="60" required aria-required="true" />				</div>
			</div>
			<div class="form-group">
				<label id="jform_contact_message-lbl" for="jform_contact_message" class="hasTooltip required col-sm-2 control-label" title="&lt;strong&gt;Message&lt;/strong&gt;&lt;br /&gt;Enter your message here.">
	Message<span class="star">&#160;*</span></label>				<div class="col-sm-9">
					<textarea name="jform[contact_message]" id="jform_contact_message" cols="50" rows="10" class="required form-control" required aria-required="true" ></textarea>				</div>
			</div>
			<?php //Dynamically load any additional fields from plugins. ?>
			<?php foreach ($this->form->getFieldsets() as $fieldset) : ?>
				<?php if ($fieldset->name != 'contact'):?>
					<?php $fields = $this->form->getFieldset($fieldset->name);?>
					<?php foreach ($fields as $field) : ?>
						<div class="control-group">
							<?php if ($field->hidden) : ?>
								<div class="controls">
									<?php echo $field->input;?>
								</div>
							<?php else:?>
								<div class="control-label">
									<?php echo $field->label; ?>
									<?php if (!$field->required && $field->type != "Spacer") : ?>
										<span class="optional"><?php echo JText::_('COM_CONTACT_OPTIONAL');?></span>
									<?php endif; ?>
								</div>
								<div class="controls"><?php echo $field->input;?></div>
							<?php endif;?>
						</div>
					<?php endforeach;?>
				<?php endif ?>
			<?php endforeach;?>
			<div class="form-actions1">
            	<button class="btn btn-success Contact_Button" type="submit"><?php echo JText::_('COM_CONTACT_CONTACT_SEND'); ?></button>
                <button class="btn btn-default Contact_Button" type="reset"><?php echo 'Clear'; ?></button>
				<input type="hidden" name="option" value="com_contact" />
				<input type="hidden" name="task" value="contact.submit" />
				<input type="hidden" name="return" value="<?php echo $this->return_page;?>" />
				<input type="hidden" name="id" value="<?php echo $this->contact->slug; ?>" />
				<?php echo JHtml::_('form.token'); ?>
			</div>
		</fieldset>
	</form>
</div>
