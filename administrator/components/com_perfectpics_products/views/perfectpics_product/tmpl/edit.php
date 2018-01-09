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
		if (task == 'perfectpics_product.cancel' || document.formvalidator.isValid(document.id('perfectpics_product-form')))
		{
			Joomla.submitform(task, document.getElementById('perfectpics_product-form'));
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_perfectpics_products&id=' . (int)$this->item->id); ?>" method="post" name="adminForm" id="perfectpics_product-form" class="form-validate">
	
	<div class="form-inline form-inline-header">
		<div class="control-group">
			<div class="control-label"><?php echo $this->form->getLabel('product_name'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('product_name'); ?></div>
		</div>
	</div>

	<div class="form-horizontal">
	<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'details')); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'details', 'PerfectPics Product', $this->item->id, true); ?>
		<div class="row-fluid">
			<div class="span9">
				<div class="row-fluid form-horizontal-desktop">			
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('price_from'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('price_from'); ?></div>
					</div>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('price_from2'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('price_from2'); ?></div>
					</div>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('range'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('range'); ?></div>
					</div>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('papertype'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('papertype'); ?></div>
					</div>
					
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('additional'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('additional'); ?></div>
					</div>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('minimumpages'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('minimumpages'); ?></div>
					</div>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('maximumpages'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('maximumpages'); ?></div>
					</div>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('additionalinfo'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('additionalinfo'); ?></div>
					</div>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('paperweight'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('paperweight'); ?></div>
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
		
		<?php echo JLayoutHelper::render('joomla.edit.params', $this); ?>
				
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'acl', 'ACL Configuration', true); ?>		
		<div class="row-fluid">
			<div class="span12">
				<fieldset class="panelform">
					<legend><?php echo $this->item->product_name ?></legend>
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