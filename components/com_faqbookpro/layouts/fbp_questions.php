<?php
/**
* @title			Minitek FAQ Book
* @copyright   		Copyright (C) 2011-2016 Minitek, All rights reserved.
* @license   		GNU General Public License version 3 or later.
* @author url   	https://www.minitek.gr/
* @developers   	Minitek.gr
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

foreach ($displayData['questions'] as $question)
{ 
	// Permission to edit
	$user = JFactory::getUser();
	$userId = $user->get('id');
	$canDo = $displayData['utilities']->getActions('com_faqbookpro', 'question', $question->id);
	$faq_open = '';
	if ($displayData['params']->questions_opened)
	{
		$faq_open = 'faq_open';	
	}
	?>
	
	<div id="faq_<?php echo $question->id; ?>" class="topic_faqBlock <?php echo $faq_open; ?>"> 	
		
		<div class="topic_faqPresentation">
			
			<a href="#" id="faqLink_<?php echo $question->id; ?>" class="topic_faqToggleLink" onclick="return false;">
				<span class="topic_faqToggleQuestion">
					<?php echo $question->title; ?>
				</span>
				<span class="topic_faqExpanderIcon"></span>
				<?php if ($question->featured) { ?>
					<span class="topic_faqFeatured"><?php echo JText::_('COM_FAQBOOKPRO_FEATURED_QUESTION'); ?></span>
				<?php }
				// Question pre-text
				if ($displayData['params']->get('questions_text', '1')) { ?>
					<span class="topic_faqAnswerWrapper_preview">
						<span>
							<?php echo $question->pretext; ?>
						</span>
					</span>
				<?php } ?>
			</a>
			
		</div>
		
		<div id="a_w_<?php echo $question->id; ?>" class="topic_faqAnswerWrapper">
			
			<div class="topic_faqAnswerWrapper_inner">
				
				<?php if ($displayData['params']->get('questions_image', '0') && $question->images['image_intro']) { 
					$question_image = $displayData['utilities']->resizeImage($displayData['params']->get('questions_image_width', '300'), $displayData['params']->get('questions_image_height', '200'), $question->images['image_intro'], $question->title);
					$question_image_alt = $question->images['image_intro_alt']; ?>
					<img src="<?php echo $question_image; ?>" alt="<?php echo $question_image_alt; ?>" />
				<?php } ?>
				
				<div class="faq_text clearfix">
					<?php echo $question->finaltext; ?>
				</div>
				
				<?php // Question date & author
				if ($displayData['params']->get('questions_date', '1') || $displayData['params']->get('questions_author', '1')) { ?>
					<div class="faq_extra">
						<?php if ($displayData['params']->get('questions_date', '1')) { ?>
							<span class="faq_date">
								<?php echo JText::_('COM_FAQBOOKPRO_ON'); ?> 
								<?php echo JHTML::_('date', $question->created, $displayData['params']->get('questions_date_format', 'l F d')); ?>
							</span>
						<?php } 
						if ($displayData['params']->get('questions_author', '1')) { ?>
							<span class="faq_author">
								<?php echo JText::_('COM_FAQBOOKPRO_BY'); ?> 
								<?php echo JFactory::getUser($question->created_by)->name; ?>
							</span>
						<?php } ?>
					</div> 
				<?php } ?>
								
			</div>
			
		</div>
		
	</div>
<?php }
