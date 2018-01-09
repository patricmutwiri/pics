<?php
/**
* @title			FAQ Book Pro
* @version   		3.x
* @copyright   		Copyright (C) 2011-2013 Minitek, All rights reserved.
* @license   		GNU General Public License version 3 or later.
* @author url   	http://www.minitek.gr/
* @author email   	info@minitek.gr
* @developers   	Minitek.gr
*/

// no direct access
defined('_JEXEC') or die();

$user  = JFactory::getUser();
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

				<table cellpadding="4" cellspacing="0" border="0" width="100%">
					
					<tr>
						<td>	
							<h3><?php echo JText::_('COM_FAQBOOKPRO_ABOUT_TITLE'); ?></h3>
							<p>
								<?php echo JText::_('COM_FAQBOOKPRO_ABOUT_DESC'); ?><br />
							</p>
							<p><a class="btn btn-default" href="http://www.minitek.gr/joomla-extensions/minitek-faq-book" target="_blank">Learn more</a></p>
						</td>
					</tr>
					<tr>
						<td>		
							<h3>Version</h3>
							<p>
							<?php 
							$xml = JFactory::getXML(JPATH_ADMINISTRATOR .'/components/com_faqbookpro/faqbookpro.xml');
							$version = (string)$xml->version;
							?>
							<p><?php echo JText::_('COM_FAQBOOKPRO_VERSION_MSG').': '.$version; ?></p>
							</p>
						</td>
					</tr>
					<tr>
						<td>		
							<h3>Copyright</h3>
							<p>
							© 2011 - <?php echo date("Y"); ?> Minitek
							</p>
						</td>
					</tr>
					<tr>
						<td>		
							<h3>Licence</h3>
							<p>
							<a href="http://www.gnu.org/licenses/gpl-3.0.html" target="_blank">GPLv3</a>
							</p>
						</td>
					</tr>
					
				</table>
				
			</div>
		</div>
	</div>
</div>
