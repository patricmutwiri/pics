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

<?php // Topic Title
if ($displayData['params']->topic_title) 
{ ?>
	<h2><a id="topicPermalink_<?php echo $displayData['topic']->id; ?>" href="<?php echo JRoute::_(FaqBookProHelperRoute::getTopicRoute($displayData['topic']->id)); ?>">
  	<?php echo $displayData['topic']->title; ?></a></h2>
<?php } ?>

<?php // Topic Description
if ($displayData['params']->topic_description && $displayData['topic']->description) 
{ ?>
  	<p><?php echo $displayData['topic']->description; ?></p>
<?php } ?>

<?php // Topic Image
if ($displayData['params']->topic_image && $displayData['topic']->topicImage) 
{ 
	$img = $displayData['utilities']->resizeImage($displayData['params']->topic_image_width, $displayData['params']->topic_image_height, $displayData['topic']->topicImage, $displayData['topic']->title); ?>
  	<div class="fbpContent_topicImage">
    	<img src="<?php echo $img; ?>" alt="<?php echo $displayData['topic']->topicImageAlt; ?>">
	</div>
<?php } ?>

<?php // Topic Questions
if ($displayData['topic']->topicQuestions) { ?>
	
	<div class="topic_section" id="fbpTopic_<?php echo $displayData['topic']->id; ?>">
		<?php
		$data = array();
		$data['params'] = $displayData['params'];
		$data['questions'] = $displayData['topic']->topicQuestions;
		$data['utilities'] = $displayData['utilities'];
		$questions_layout = new JLayoutFile('fbp_questions');
		echo $questions_layout->render($data);
		?>
	</div> 
		 
<?php } ?>

<?php // Sub-topics
if ($displayData['params']->show_subtopics && $displayData['topic']->subTopics) { ?>

  	<?php foreach ($displayData['topic']->subTopics as $subtopic) { ?>
	
	  	<div class="subTopic_section" id="fbpTopic_<?php echo $subtopic->id; ?>">
      		
			<?php if ($displayData['params']->subtopics_title) { ?>
				<h3 class="subTopic_sectionTitle">
					<a id="topicPermalink_<?php echo $subtopic->id; ?>" href="<?php echo JRoute::_(FaqBookProHelperRoute::getTopicRoute($subtopic->id)); ?>"><?php echo $subtopic->title; ?></a>
				</h3>
			<?php } ?>
			
			<?php if ($displayData['params']->subtopics_description) { ?>
				<span class="subTopic_sectionDescription"><?php echo $subtopic->description; ?></span>
			<?php } ?>
			
			<?php if ($displayData['params']->subtopics_image) {
				$path = json_decode($subtopic->params, false)->image;
				if ($path)
				{
					$subtopic_image = $displayData['utilities']->resizeImage($displayData['params']->subtopics_image_width, $displayData['params']->subtopics_image_height, $path, $subtopic->title); 
					$subtopic_image_alt = json_decode($subtopic->params, false)->image_alt; ?>
					<div class="fbpContent_topicImage">
						<img src="<?php echo $subtopic_image; ?>" alt="<?php echo $subtopic_image_alt; ?>" />
					</div>
				<?php } ?> 
			<?php } ?>
		
			<?php if ($displayData['params']->subtopics_faqs) 
			{
				if (count($subtopic->questions)) 
				{
					$data = array();
					$data['params'] = $displayData['params'];
					$data['questions'] = $subtopic->questions;
					$data['utilities'] = $displayData['utilities'];
					$questions_layout = new JLayoutFile('fbp_questions');
					echo $questions_layout->render($data);
				}
			} ?> 
			
		</div> 
		 
  	<?php } ?>
	
<?php } ?>