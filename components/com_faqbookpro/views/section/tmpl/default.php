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

<?php if ($this->params->get('show_page_title')): ?>
	<h1 class="componentheading<?php echo $this->params->get('pageclass_sfx')?>">
  		<?php echo $this->escape($this->params->get('page_title')); ?>
	</h1>
<?php endif; ?>

<div id="fbpExtended" class="fbpSectionExtended">
  
  	<?php if ($this->topnavigation) {
		echo $this->topnavigation; 
	} ?>
	
	<?php if ($this->leftnavigation) {
		echo $this->leftnavigation; 
	} ?>
	
	<div id="fbpcontent" class="<?php echo $this->content_class; ?>">
		<div class="fbp_loader">
			<img src="<?php echo JURI::root().'/components/com_faqbookpro/assets/images/loaderbig.gif'; ?>" alt="" />
		</div>

		<div class="fbpContent_root">
			<?php echo $this->loadTemplate('content'); ?>
		</div>
	</div>
	
</div>

<div class="clearfix"> </div>