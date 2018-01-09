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
?>

<div id="fbpExtended" class="fbpSectionExtended">

  	<?php if ($this->sections_topnav) { ?>
  	<div class="fbpTopNavigation_core_outer">

		<div class="fbpTopNavigation_core">

			<div class="fbpTopNavigation_wrap col-md-offset-1">

				<ul class="fbpTopNavigation_root">
					<li class="NavTopUL_home">
						<a href="<?php echo JRoute::_(FaqBookProHelperRoute::getSectionsRoute()); ?>" class="NavTopUL_link">
							<i class="fa fa-home NavTopUL_homeIcon"></i>&nbsp;&nbsp;<?php echo JText::_('COM_FAQBOOKPRO_HOME_LINK'); ?>
						</a>
					</li>
				</ul>

			</div>

		</div>

		<div class="clearfix"> </div>

	</div>
	<?php } ?>

	<div id="fbpcontent" class="fbpContent_core noleftnav grid-construct-x">
		<div class="fbpContent_root">
			<?php include JPATH_SITE.'/components/com_faqbookpro/views/sections/tmpl/default_content.php'; ?>
		</div>
	</div>

</div>

<div class="clearfix"> </div>
