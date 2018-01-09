<?php
/**
 * @author		
 * @copyright	
 * @license		
 */

defined("_JEXEC") or die("Restricted access");

// necessary libraries
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'size.cancel' || document.formvalidator.isValid(document.id('size-form')))
		{
			Joomla.submitform(task, document.getElementById('size-form'));
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_products&id=' . (int)$this->item->id); ?>" method="post" name="adminForm" id="size-form" class="form-validate">
	
	<div class="form-inline form-inline-header">
		<div class="control-group">
			<div class="control-label"><?php echo $this->form->getLabel('categories'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('categories'); ?></div>
		</div>
	</div>

	<div class="form-horizontal">
	<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'details')); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'details', 'Size', $this->item->id, true); ?>
		<div class="row-fluid">
			<div class="span9">
				<div class="row-fluid form-horizontal-desktop">			
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('size_title'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('size_title'); ?></div>
			</div>			
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('size_dimensions'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('size_dimensions'); ?></div>
			</div>			
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('no_pages'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('no_pages'); ?></div>
			</div>			
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('price'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('price'); ?></div>
			</div>			
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('page_range'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('page_range'); ?></div>
			</div>			
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('size_image'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('size_image'); ?></div>
			</div>
				</div>
			</div>
			<div class="span3">
				<?php echo JLayoutHelper::render('joomla.edit.global', $this); ?>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'publishing', JText::_('JGLOBAL_FIELDSET_PUBLISHING', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span6">
				<?php echo JLayoutHelper::render('joomla.edit.publishingdata', $this); ?>
			</div>
			<div class="span6">
				<?php echo JLayoutHelper::render('joomla.edit.metadata', $this); ?>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>
		
	<?php echo JHtml::_('bootstrap.endTabSet'); ?>
	</div>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>