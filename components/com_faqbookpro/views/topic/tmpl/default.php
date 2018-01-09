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

<div id="fbpExtended" class="fbpTopicExtended">
  
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
			<?php echo $this->loadTemplate('topic'); ?>
		</div>
	</div>
		
</div>

<div class="clearfix"> </div>