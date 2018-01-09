<?php
/**
* @title			Minitek FAQ Book
* @copyright   		Copyright (C) 2011-2015 Minitek, All rights reserved.
* @license   		GNU General Public License version 3 or later.
* @author url   	http://www.minitek.gr/
* @developers   	Minitek.gr
*/

// no direct access
defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('behavior.formvalidator');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');

$app = JFactory::getApplication();
$input = $app->input;

$assoc = JLanguageAssociations::isEnabled();

JFactory::getDocument()->addScriptDeclaration('
	Joomla.submitbutton = function(task)
	{
		if (task == "topic.cancel" || document.formvalidator.isValid(document.getElementById("item-form")))
		{
			' . $this->form->getField("description")->save() . '
			Joomla.submitform(task, document.getElementById("item-form"));
		}
	};
');
?>

<div id="mn-cpanel"><!-- Main Container -->

	<?php echo $this->navbar; ?>
	
	<div id="mn-main-container" class="main-container container-fluid">
		
		<a id="menu-toggler" class="menu-toggler" href="#">
			<span class="menu-text"></span>
		</a>

		<div id="mn-sidebar" class="sidebar">
		
			<?php echo $this->sidebar; ?>
					
		</div>
		
		<div class="main-content">
			
			<div class="page-header clearfix no-padding"> </div>
			
			<div class="page-content">
			
				<form action="<?php echo JRoute::_('index.php?option=com_faqbookpro&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="item-form" class="form-validate">
				
					<?php echo JLayoutHelper::render('joomla.edit.title_alias', $this); ?>
				
					<div class="form-horizontal">
						<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>
				
						<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_FAQBOOKPRO_FIELDSET_TOPIC', true)); ?>
						<div class="row-fluid">
							<div class="span9">
								<?php echo $this->form->getLabel('description'); ?>
								<?php echo $this->form->getInput('description'); ?>
							</div>
							<div class="span3">
								<fieldset class="form-vertical">
									<div class="control-group">
										<div class="control-label">
											<?php echo $this->form->getLabel('parent_id'); ?> 
										</div>
										<div class="controls">
											<?php echo $this->form->getInput('parent_id'); ?> 
										</div>
									</div>
									<div class="control-group">
										<div class="control-label">
											<?php echo $this->form->getLabel('section_id'); ?> 
										</div>
										<div class="controls">
											<?php echo $this->form->getInput('section_id'); ?> 
										</div>
									</div>
									<div class="control-group">
										<div class="control-label">
											<?php echo $this->form->getLabel('published'); ?> 
										</div>
										<div class="controls">
											<?php echo $this->form->getInput('published'); ?> 
										</div>
									</div>
									<div class="control-group">
										<div class="control-label">
											<?php echo $this->form->getLabel('access'); ?> 
										</div>
										<div class="controls">
											<?php echo $this->form->getInput('access'); ?> 
										</div>
									</div>
									<div class="control-group">
										<div class="control-label">
											<?php echo $this->form->getLabel('language'); ?> 
										</div>
										<div class="controls">
											<?php echo $this->form->getInput('language'); ?> 
										</div>
									</div>
								</fieldset>	
							</div>
						</div>
						<?php echo JHtml::_('bootstrap.endTab'); ?>
				
						<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'publishing', JText::_('COM_FAQBOOKPRO_FIELDSET_PUBLISHING', true)); ?>
						<div class="row-fluid form-horizontal-desktop">
							<div class="span6">
								<?php echo JLayoutHelper::render('joomla.edit.publishingdata', $this); ?>
							</div>
							<div class="span6">
								<?php echo JLayoutHelper::render('joomla.edit.metadata', $this); ?>
							</div>
						</div>
						<?php echo JHtml::_('bootstrap.endTab'); ?>
						
						<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'params', JText::_('COM_FAQBOOKPRO_FIELDSET_OPTIONS', true)); ?>
						<div class="row-fluid form-horizontal-desktop">
							<div class="span12">
								<?php foreach ($this->form->getGroup('params') as $field) : ?>
									<?php echo $field->getControlGroup(); ?>
								<?php endforeach; ?>
							</div>
						</div>
						<?php echo JHtml::_('bootstrap.endTab'); ?>
						
						<?php if ($this->canDo->get('core.admin')) : ?>
							<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'rules', JText::_('COM_FAQBOOKPRO_FIELDSET_RULES', true)); ?>
							<?php echo $this->form->getInput('rules'); ?>
							<?php echo JHtml::_('bootstrap.endTab'); ?>
						<?php endif; ?>
				
						<?php echo JHtml::_('bootstrap.endTabSet'); ?>
				
						<input type="hidden" name="task" value="" />
						<?php echo JHtml::_('form.token'); ?>
						
					</div>
					
				</form>

			</div><!-- End page-content -->
			
		</div><!-- End main-content -->

	</div><!-- End mcu-main-container -->
	
</div><!-- End Main Container -->

