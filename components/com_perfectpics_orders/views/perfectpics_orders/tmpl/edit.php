<?php
/**
 * @author		
 * @copyright	
 * @license		
 */

defined("_JEXEC") or die("Restricted access");

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.calendar');
JHtml::_('formbehavior.chosen', 'select');
?>

<h2><?php echo JText::_('COM_PERFECTPICS_ORDERS_PERFECTPICS_ORDERS_VIEW_PERFECTPICS_ORDERS_TITLE'); ?>: <i><?php echo $this->item->customers_name; ?></i></h2>

<form action="<?php echo JRoute::_('index.php?option=com_perfectpics_orders&id=' . (int)$this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate form-horizontal">
	
	<div>
		<button type="button" class="btn btn-primary" onclick="Joomla.submitform('perfectpics_orders.apply')"><?php echo JText::_('JAPPLY') ?></button>
		<button type="button" class="btn btn-primary" onclick="Joomla.submitform('perfectpics_orders.save')"><?php echo JText::_('JSAVE') ?></button>
		<button type="button" class="btn btn-primary" onclick="Joomla.submitform('perfectpics_orders.save2new')"><?php echo JText::_('JTOOLBAR_SAVE_AND_NEW') ?></button>
		<button type="button" class="btn btn-primary" onclick="Joomla.submitform('perfectpics_orders.save2copy')"><?php echo JText::_('JTOOLBAR_SAVE_AS_COPY') ?></button>
		<button type="button" class="btn btn-danger" onclick="Joomla.submitform('perfectpics_orders.cancel')"><?php echo JText::_('JCANCEL') ?></button>
	</div>
	
	<br>
	
	<div class="form-horizontal">
	<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'details')); ?>
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'details', 'PerfectPics_Orders', $this->item->id, true); ?>

		<div class="row-fluid">
			<div class="span12">
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('customers_name'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('customers_name'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('customers_email'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('customers_email'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('customers_phone'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('customers_phone'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('upload_pdf'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('upload_pdf'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('book_size'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('book_size'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('cover_type'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('cover_type'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('paper_type'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('paper_type'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('book_title'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('book_title'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('author_name'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('author_name'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('id'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('id'); ?></div>
				</div>
			</div>
		</div>
		
		<?php echo JHtml::_('bootstrap.endTab'); ?>
		
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'publishing', JText::_('JGLOBAL_FIELDSET_PUBLISHING', true), true); ?>

		<div class="row-fluid">
			<div class="span12">
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('catid'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('catid'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('published'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('published'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('access'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('access'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('created'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('created'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('created_by'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('created_by'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('modified'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('modified'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('modified_by'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('modified_by'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('publish_up'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('publish_up'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('publish_down'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('publish_down'); ?></div>
				</div>
			</div>
		</div>
		
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JLayoutHelper::render('joomla.edit.params', $this); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'acl', 'ACL Configuration', true); ?>		

		<div class="row-fluid">
			<div class="span12">
				<fieldset class="panelform">
					<legend><?php echo $this->item->customers_name ?></legend>
					<?php echo JHtml::_('sliders.start', 'permissions-sliders-'.$this->item->id, array('useCookie'=>1)); ?>
					<?php echo JHtml::_('sliders.panel', JText::_('ACL Configuration'), 'access-rules'); ?>
					<?php echo $this->form->getInput('rules'); ?>
					<?php echo JHtml::_('sliders.end'); ?>
				</fieldset>
			</div>
		</div>

		<?php echo JHtml::_('bootstrap.endTab'); ?>
	<?php echo JHtml::_('bootstrap.endTabSet'); ?>
	</div>
	
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>