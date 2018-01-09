<?php
/**
* @title			Minitek FAQ Book
* @copyright   		Copyright (C) 2011-2015 Minitek, All rights reserved.
* @license   		GNU General Public License version 3 or later.
* @author url   	http://www.minitek.gr/
* @developers   	Minitek.gr
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

$user = JFactory::getUser();
?>

<div class="fbpContent_sections">
	
	<?php if ($this->params->get('show_page_title')): ?>
		<h2 class="fbpContent_sections_title">
			<?php echo $this->escape($this->params->get('page_title')); ?>
		</h2>
	<?php endif; ?>
	
	<?php if ($this->sections_page_description && $this->params->get('menu-meta_description')) { ?>
		<div class="fbpContent_sections_desc">
			<?php echo $this->params->get('menu-meta_description'); ?>
		</div>
	<?php } ?>
	
	<div class="fbpContent_sections_content">
	
		<?php if ($this->sections) 
		{
			if ($this->sections_cols > 1) 
			{
				$class = 'fbpContent_gridItem';
				$anchor_class = 'clearfix';
			} else {
				$class = 'fbpContent_gridItem onecolgrid';
				$anchor_class = '';
			} 
			?>
			
			<ul class="fbpContent_grid clearfix"> 
			
				<?php
				$i = 0;
				foreach ($this->sections as $key=>$section)
				{ ?>
					<li class="<?php echo $class; ?>" style="width:<?php echo number_format(100/$this->sections_cols, 1); ?>%;">
					
						<div class="fbpContent_gridItemContainer clearfix">
						
							<?php 
							if ($this->sections_title)
							{ ?>
								<h4>
									<a href="<?php echo JRoute::_(FaqBookProHelperRoute::getSectionRoute($section->id)); ?>" class="feat-item <?php echo $anchor_class; ?>" id="fid<?php echo $section->id; ?>">
										<?php echo $section->title; ?>
									</a>
								</h4>
								
							<?php }
							if ($this->sections_description)
							{ ?>
								<div class="index-cat-desc">
									<?php echo $section->description; ?>
								</div>
							<?php }
							
							if ($this->params->get('user_questions', true) && $this->params->get('sections_question_button', false) && $user->authorise('core.create', 'com_faqbookpro'))
							{ ?>
								<a href="<?php echo JRoute::_(FaqBookProHelperRoute::newQuestionRoute($section->id)); ?>" class="fbpSections_askQuestion btn btn-default"><i class="fa fa-edit"></i>&nbsp;&nbsp;<?php echo JText::_('COM_FAQBOOKPRO_ASK_A_QUESTION'); ?></a>
							<?php } ?>
						
						</div>
					
					</li>
					<?php
					$i++;
						
					/*if (($i)%$this->sections_cols==0) 
					{ ?>
						<li class="clearfix"></li>
					<?php }*/
				
				} ?>
				
			</ul>
		<?php } ?>
	
	</div>

</div>