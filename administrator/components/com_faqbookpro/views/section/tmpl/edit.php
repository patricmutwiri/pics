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

JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');

// Create shortcut to parameters.
//$params = $this->state->get('params');
//$params = $params->toArray();

// This checks if the config options have ever been saved. If they haven't they will fall back to the original settings.
//$editoroptions = isset($params['show_publishing_options']);

$app = JFactory::getApplication();
$input = $app->input;

/*if (!$editoroptions)
{
	$params['show_publishing_options'] = '1';
	$params['show_article_options'] = '1';
}*/

// Check if the article uses configuration settings besides global. If so, use them.
/*if (!empty($this->item->attribs['show_publishing_options']))
{
	$params['show_publishing_options'] = $this->item->attribs['show_publishing_options'];
}

if (!empty($this->item->attribs['show_article_options']))
{
	$params['show_article_options'] = $this->item->attribs['show_article_options'];
}*/
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'section.cancel' || document.formvalidator.isValid(document.id('item-form')))
		{
			<?php echo $this->form->getField('description')->save(); ?>
			Joomla.submitform(task, document.getElementById('item-form'));
		}
	}
</script>

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
				
				<form action="<?php echo JRoute::_('index.php?option=com_faqbookpro&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="item-form" class="form-validate">
					
					<?php echo JLayoutHelper::render('joomla.edit.title_alias', $this); ?>
					
					<div class="row-fluid">
						<!-- Begin Content -->
						<div class="span12 form-horizontal">
							<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>
				
								<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_FAQBOOKPRO_FIELDSET_SECTION', true)); ?>
									
									<div class="row-fluid">
										<div class="span9">
											<?php echo $this->form->getLabel('description'); ?>
											<?php echo $this->form->getInput('description'); ?>
										</div>
										<div class="span3">
											<?php echo JLayoutHelper::render('joomla.edit.global', $this); ?>
										</div>
									</div>
										
								<?php echo JHtml::_('bootstrap.endTab'); ?>
				
								<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'publishing', JText::_('COM_FAQBOOKPRO_FIELDSET_PUBLISHING', true)); ?>
									<div class="row-fluid">
										
										<div class="span6">
											
											<?php echo JLayoutHelper::render('joomla.edit.publishingdata', $this); ?>
																							
										</div>
										
										<div class="span6">
											<?php echo JLayoutHelper::render('joomla.edit.metadata', $this); ?>
										</div>
							
									</div>
								<?php echo JHtml::_('bootstrap.endTab'); ?>
								
								<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'attribs', JText::_('COM_FAQBOOKPRO_FIELDSET_OPTIONS', true)); ?>
									<div class="row-fluid form-horizontal-desktop">
										<div class="span12">
											<?php foreach ($this->form->getGroup('attribs') as $field) : ?>
												<?php echo $field->getControlGroup(); ?>
											<?php endforeach; ?>
										</div>
									</div>
								<?php echo JHtml::_('bootstrap.endTab'); ?>
								
								<?php if ($this->canDo->get('core.admin')) : ?>
									<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'permissions', JText::_('COM_FAQBOOKPRO_FIELDSET_RULES', true)); ?>
										<fieldset>
											<?php echo $this->form->getInput('rules'); ?>
										</fieldset>
									<?php echo JHtml::_('bootstrap.endTab'); ?>
								<?php endif; ?>
				
							<?php echo JHtml::_('bootstrap.endTabSet'); ?>
				
							<input type="hidden" name="task" value="" />
							<input type="hidden" name="return" value="<?php echo $input->getCmd('return');?>" />
							<?php echo JHtml::_('form.token'); ?>
						</div>
						<!-- End Content -->
												
					</div>
				</form>
				
			</div><!-- End page-content -->
			
		</div><!-- End main-content -->

	</div><!-- End mcu-main-container -->
	
</div><!-- End Main Container -->

