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

$this->configFieldsets  = array('editorConfig');
$this->hiddenFieldsets  = array('basic-limited');

// Create shortcut to parameters.
$params = $this->state->get('params');

$app = JFactory::getApplication();
$input = $app->input;

JFactory::getDocument()->addScriptDeclaration('
	Joomla.submitbutton = function(task)
	{
		if (task == "question.cancel" || document.formvalidator.isValid(document.getElementById("item-form")))
		{
			' . $this->form->getField('articletext')->save() . '
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
				
							<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_FAQBOOKPRO_QUESTION_CONTENT', true)); ?>
							<div class="row-fluid">
								<div class="span9">
									<fieldset class="adminform">
										<?php echo $this->form->getInput('articletext'); ?>
									</fieldset>
								</div>
								<div class="span3">
									<fieldset class="form-vertical">
										<div class="control-group">
											<div class="control-label">
												<?php echo $this->form->getLabel('topicid'); ?> 
											</div>
											<div class="controls">
												<?php echo $this->form->getInput('topicid'); ?> 
											</div>
										</div>
										<div class="control-group">
											<div class="control-label">
												<?php echo $this->form->getLabel('state'); ?> 
											</div>
											<div class="controls">
												<?php echo $this->form->getInput('state'); ?> 
											</div>
										</div>
										<div class="control-group">
											<div class="control-label">
												<?php echo $this->form->getLabel('featured'); ?> 
											</div>
											<div class="controls">
												<?php echo $this->form->getInput('featured'); ?> 
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
				
							<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'images', JText::_('COM_FAQBOOKPRO_FIELDSET_IMAGES', true)); ?>
							<div class="row-fluid form-horizontal-desktop">
								<div class="span6">
									<?php echo $this->form->getControlGroup('images'); ?>
									<?php foreach ($this->form->getGroup('images') as $field) : ?>
										<?php echo $field->getControlGroup(); ?>
									<?php endforeach; ?>
								</div>
							</div>
							<?php echo JHtml::_('bootstrap.endTab'); ?>
												
							<?php if ($this->canDo->get('core.admin')) : ?>
								<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'permissions', JText::_('COM_FAQBOOKPRO_FIELDSET_RULES', true)); ?>
									<?php echo $this->form->getInput('rules'); ?>
								<?php echo JHtml::_('bootstrap.endTab'); ?>
							<?php endif; ?>
				
						<?php echo JHtml::_('bootstrap.endTabSet'); ?>
				
						<input type="hidden" name="task" value="" />
						<input type="hidden" name="return" value="<?php echo $input->getCmd('return'); ?>" />
						<?php echo JHtml::_('form.token'); ?>
				
					</div>
						
				</form>
				
			</div><!-- End page-content -->
			
		</div><!-- End main-content -->

	</div><!-- End mcu-main-container -->
	
</div><!-- End Main Container -->
