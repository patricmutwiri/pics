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
?>

<div>
	
	<?php // Description
	if ($this->showDescription && $this->sectionDescription) 
	{ ?>
		<div class="section-pre-text">
			<?php echo $this->sectionDescription; ?>
		</div>
	<?php } ?>
	
	<?php // Active Topic
	if ($this->active_topic && $this->topic)
	{
		$data = array();
		$data['params'] = $this->params;
		$data['topic'] = $this->topic;
		$data['utilities'] = $this->utilities;
		$layout = new JLayoutFile('fbp_topic');
		echo $layout->render($data);
	} ?>
		
	<?php // Popular Topics
	if ($this->show_popular_topics) 
	{ 	
	  	if (count($this->popular_topics))
		{
			if ($this->popular_topics_cols > 1) 
			{
				$class = 'fbpContent_gridItem';
				$anchor_class = 'clearfix';
			} else {
				$class = 'fbpContent_gridItem onecolgrid';
				$anchor_class = '';
			} ?>
			
			<div id="fbp_popTopics">
		
			<?php if (isset($this->popular_topics_header) && $this->popular_topics_header) { ?>
				<h4 class="popularTopics_title"><?php echo JText::_('COM_FAQBOOKPRO_POPULAR_TOPICS'); ?></h4>
			<?php } ?>
			
			<ul class="fbpContent_grid clearfix">
			
			<?php 
			$i = 0;
		  	foreach ($this->popular_topics as $key=>$item)
			{ ?>
				<li class="<?php echo $class; ?>" style="width:<?php echo number_format(100/$this->popular_topics_cols, 1); ?>%;">
			
					<div class="fbpContent_gridItemContainer">
				
						<?php 
						$topic_params = json_decode($item->params, false);
						$title_class = 'text-left';
						if ($this->popular_topics_image && isset($topic_params->image) && $topic_params->image)
						{
							$title_class = 'text-center';
							$image_path = $topic_params->image;
							$img = $this->utilities->resizeImage($this->popular_topics_image_width, $this->popular_topics_image_height, $image_path, $item->title); ?>
							<a href="<?php echo JRoute::_(FaqBookProHelperRoute::getTopicRoute($item->id)); ?>" class="feat-item-img <?php echo $anchor_class; ?>"><img src="<?php echo $img; ?>" alt=""></a>
						<?php }
						if ($this->popular_topics_title)
						{ ?>
							<h4 class="<?php echo $title_class; ?>">
								<a href="<?php echo JRoute::_(FaqBookProHelperRoute::getTopicRoute($item->id)); ?>" class="feat-item <?php echo $anchor_class; ?>" id="fid<?php echo $item->id; ?>">
									<?php echo $item->title; ?>
								</a>
							</h4>
						<?php }
						if ($this->popular_topics_desc)
						{ ?>
							<div class="index-cat-desc">
								<?php echo $this->utilities->getWordLimit($item->description, $this->popular_topics_desc_limit); ?>
							</div>
						<?php } ?>
						
					</div>
					
				</li>
				<?php
				$i++;
					
				/*if (($i)%$this->popular_topics_cols==0) 
				{ ?>
					<li class="clearfix"></li>
				<?php }*/
			} ?>
			</ul>
			
			</div>
			
		<?php }
	} ?>
			
	<?php // All Topics
	if ($this->show_all_topics) 
	{
		if (count($this->first_level_topics))
		{ ?>
			
			<div id="fbp_allTopics">
			
			<?php if (isset($this->all_topics_header) && $this->all_topics_header) { ?>
				<h4 class="allTopics_title"><?php echo JText::_('COM_FAQBOOKPRO_ALL_TOPICS'); ?></h4>
			<?php } ?>
			
			<ul class="fbpContent_allTopics clearfix">
			
				<?php 
				foreach ($this->first_level_topics as $first_level_topic) 
				{
					$topicsTree = $this->getTopicsTree($first_level_topic, $this->all_topics_levels, $this->all_topics_cols, $level = 1, $this->all_topics_icons);
					$topics_tree[] = $topicsTree;
				}
				
				foreach ($topics_tree as $topic_tree)
				{ 
					echo $topic_tree;
				}	
				?>
				
			</ul>
			
			</div>
			
		<?php }
	} ?>
		
</div>
