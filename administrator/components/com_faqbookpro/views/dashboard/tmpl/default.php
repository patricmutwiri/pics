<?php
/**
* @title			FAQ Book Pro
* @copyright   		Copyright (C) 2011-2015 Minitek, All rights reserved.
* @license   		GNU General Public License version 3 or later.
* @author url   	http://www.minitek.gr/
* @author email   	info@minitek.gr
* @developers   	Minitek.gr
*/

// no direct access
defined('_JEXEC') or die;

$token = JSession::getFormToken();
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
			
			<div class="page-header clearfix"> </div>
			
			<div class="page-content mn-dashboard">
    	
				<div class="row-fluid">
					
					<?php if (!empty($this->leftoverFolders) || !empty($this->leftoverFiles)) { ?>
						<div class="alert alert-warning">
							
							<h3>Component maintenance</h3>
							
							<p><?php echo JText::_('COM_FAQBOOKPRO_DASHBOARD_OLD_FILES_WARNING'); ?></p>
							
							<?php if (!empty($this->leftoverFolders)) { ?>
							<p>
								<span class="badge"><?php echo count($this->leftoverFolders); ?></span> <?php echo JText::_('COM_FAQBOOKPRO_DASHBOARD_X_DEPRECATED_FOLDERS_FOUND'); ?>
							</p>
							<div class="well">
								<ul>
								<?php foreach($this->leftoverFolders as $leftoverFolder) { ?>
									<li><?php echo $leftoverFolder; ?></li>
								<?php } ?>
								</ul>
							</div>
							<?php } ?>
							
							<?php if (!empty($this->leftoverFiles)) { ?>
							<p>
								<span class="badge"><?php echo count($this->leftoverFiles); ?></span> <?php echo JText::_('COM_FAQBOOKPRO_DASHBOARD_X_DEPRECATED_FILES_FOUND'); ?>
							</p>
							<div class="well">
								<ul>
								<?php foreach($this->leftoverFiles as $leftoverFile) { ?>
									<li><?php echo $leftoverFile; ?></li>
								<?php } ?>
								</ul>
							</div>
							<?php } ?>
							
							<br />
							<p><a href="<?php echo JRoute::_('index.php?option=com_faqbookpro&task=deleteOldFiles&'.$token.'=1'); ?>" class="btn btn-warning btn-large">
								<i class="fa fa-times-circle"></i>&nbsp;&nbsp;<?php echo JText::_('COM_FAQBOOKPRO_DASHBOARD_DELETE_OLD_FILES'); ?>
								</a></p>
							
						</div>
					<?php } ?>
					
					<div class="thumbnail">
						<a href="<?php echo JRoute::_('index.php?option=com_faqbookpro&view=sections'); ?>">
							<i class="fa fa-list-ul"></i>
							<span class="thumbnail-title">
								<?php echo JText::_('COM_FAQBOOKPRO_SECTIONS'); ?>
							</span>
						</a>
					</div>
					
					<div class="thumbnail">
						<a href="<?php echo JRoute::_('index.php?option=com_faqbookpro&view=topics'); ?>">
							<i class="fa fa-folder-open"></i>
							<span class="thumbnail-title">
								<?php echo JText::_('COM_FAQBOOKPRO_TOPICS'); ?>
							</span>
						</a>
					</div>
										
					<div class="thumbnail">
						<a href="<?php echo JRoute::_('index.php?option=com_faqbookpro&view=questions'); ?>">
							<i class="fa fa-question-circle"></i>
							<span class="thumbnail-title">
								<?php echo JText::_('COM_FAQBOOKPRO_QUESTIONS'); ?>
							</span>
						</a>
					</div>
															
					<div class="thumbnail">
						<a href="<?php echo JRoute::_('index.php?option=com_faqbookpro&task=purgeImages&'.$token.'=1'); ?>">
							<i class="fa fa-times"></i>
							<span class="thumbnail-title">
								<?php echo JText::_('COM_FAQBOOKPRO_PURGE_IMAGES_CACHE'); ?>
							</span>
						</a>
					</div>
					
					<div class="thumbnail">
						<a href="http://www.minitek.gr/support/documentation/joomla-extensions/components/minitek-faq-book" target="_blank">
							<i class="fa fa-book"></i>
							<span class="thumbnail-title">
								<?php echo JText::_('COM_FAQBOOKPRO_DOCUMENTATION'); ?>
							</span>
						</a>
					</div>
					
					<div class="thumbnail">
						<a href="<?php echo JRoute::_('index.php?option=com_faqbookpro&view=about'); ?>">
							<i class="fa fa-info-circle"></i>
							<span class="thumbnail-title">
								<?php echo JText::_('COM_FAQBOOKPRO_ABOUT'); ?>
							</span>
						</a>
					</div>
					
					<div class="thumbnail">
						<a href="<?php echo JRoute::_('index.php?option=com_config&view=component&component=com_faqbookpro&path=&return='.base64_encode(JURI::getInstance()->toString())); ?>">
							<i class="fa fa-gear"></i>
							<span class="thumbnail-title">
								<?php echo JText::_('COM_FAQBOOKPRO_CONFIGURATION'); ?>
							</span>
						</a>
					</div>
					
					
				</div>
								
			</div>
			
		</div>
		
	</div>
	
</div>