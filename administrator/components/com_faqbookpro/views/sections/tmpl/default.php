<?php
/**
* @title			Minitek FAQ Book Pro
* @copyright   		Copyright (C) 2011-2015 Minitek, All rights reserved.
* @license   		GNU General Public License version 3 or later.
* @author url   	http://www.minitek.gr/
* @developers   	Minitek.gr
*/

// no direct access
defined('_JEXEC') or die;

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$app		= JFactory::getApplication();
$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$saveOrder	= $listOrder == 'a.ordering';

if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_faqbookpro&task=sections.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'articleList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
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
			
			<div class="page-header clearfix no-padding"> </div>
			
			<div class="page-content">

				<form action="<?php echo JRoute::_('index.php?option=com_faqbookpro&view=sections'); ?>" method="post" name="adminForm" id="adminForm">
				
					<div id="j-main-container">
				
						<?php
						// Search tools bar
						echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
						?>
						
						<div class="clearfix"> </div>
						
						<?php if (empty($this->items)) : ?>
						
							<div class="alert alert-no-items">
								<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
							</div>
							
						<?php else : ?>
						
						<table class="table table-striped" id="articleList">
							<thead>
								<tr>
								
									<th width="1%" class="nowrap center hidden-phone">
										<?php echo JHtml::_('searchtools.sort', '', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
									</th>
									
									<th width="1%" class="center">
										<?php echo JHtml::_('grid.checkall'); ?>
									</th>
									
									<th width="1%" class="nowrap center">
										<?php echo JHtml::_('searchtools.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
									</th>
									
									<th>
										<?php echo JHtml::_('searchtools.sort', 'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder); ?>
									</th>
																		
									<th width="5%" class="nowrap center hidden-phone">
										<?php echo JHtml::_('searchtools.sort',  'COM_FAQBOOKPRO_HEADING_TOPICS_COUNT', 'topics_count', $listDirn, $listOrder); ?>
									</th>
									
									<th width="10%" class="nowrap center hidden-phone">
										<?php echo JHtml::_('searchtools.sort',  'JGRID_HEADING_ACCESS', 'a.access', $listDirn, $listOrder); ?>
									</th>
																		
									<th width="10%" class="nowrap center hidden-phone">
										<?php echo JHtml::_('searchtools.sort',  'COM_FAQBOOKPRO_HEADING_AUTHOR', 'a.created_user_id', $listDirn, $listOrder); ?>
									</th>
									
									<th width="5%" class="nowrap center hidden-phone">
										<?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_LANGUAGE', 'a.language', $listDirn, $listOrder); ?>
									</th>
																
									<th width="1%" class="nowrap center">
										<?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
									</th>
									
								</tr>
							</thead>
							<tfoot>
								<tr>
									<td colspan="9">
										<?php echo $this->pagination->getListFooter(); ?>
									</td>
								</tr>
							</tfoot>
							<tbody>
							<?php 
							if (count($this->items)) {
							foreach ($this->items as $i => $item) :
								$item->max_ordering = 0; //??
								$ordering   = ($listOrder == 'a.ordering');
								$canCreate  = $user->authorise('core.create');
								$canEdit    = $user->authorise('core.edit',       'com_faqbookpro.section.'.$item->id);
								$canCheckin = $user->authorise('core.manage',     'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
								$canEditOwn = $user->authorise('core.edit.own',   'com_faqbookpro.section.'.$item->id) && $item->created_user_id == $userId;
								$canChange  = $user->authorise('core.edit.state', 'com_faqbookpro.section.'.$item->id) && $canCheckin;
								?>
								<tr class="row<?php echo $i % 2; ?>">
									
									<td class="order nowrap center hidden-phone">
										<?php
										$iconClass = '';
										if (!$canChange)
										{
											$iconClass = ' inactive';
										}
										elseif (!$saveOrder)
										{
											$iconClass = ' inactive tip-top hasTooltip" title="' . JHtml::tooltipText('JORDERINGDISABLED');
										}
										?>
										<span class="sortable-handler<?php echo $iconClass ?>">
											<i class="icon-menu"></i>
										</span>
										<?php if ($canChange && $saveOrder) : ?>
											<input type="text" style="display:none" name="order[]" size="5" value="<?php echo $item->ordering; ?>" class="width-20 text-area-order " />
										<?php endif; ?>
									</td>
									
									<td class="center">
										<?php echo JHtml::_('grid.id', $i, $item->id); ?>
									</td>
									
									<td class="center">
										<div class="btn-group">
											<?php echo JHtml::_('jgrid.published', $item->state, $i, 'sections.', $canChange); ?>
										</div>
									</td>
									
									<td class="nowrap has-context">
										<div class="pull-left">
											<?php if ($item->checked_out) : ?>
												<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'sections.', $canCheckin); ?>
											<?php endif; ?>
											<?php if ($item->language == '*'):?>
												<?php $language = JText::alt('JALL', 'language'); ?>
											<?php else:?>
												<?php $language = $item->language_title ? $this->escape($item->language_title) : JText::_('JUNDEFINED'); ?>
											<?php endif;?>
											<?php if ($canEdit || $canEditOwn) : ?>
												<a href="<?php echo JRoute::_('index.php?option=com_faqbookpro&task=section.edit&id=' . $item->id); ?>" title="<?php echo JText::_('JACTION_EDIT'); ?>">
													<?php echo $this->escape($item->title); ?></a>
											<?php else : ?>
												<?php echo $this->escape($item->title); ?>
											<?php endif; ?>
										</div>
									</td>
									
									<td class="small center hidden-phone">
										<?php echo $item->topics_count; ?>
									</td>
									
									<td class="small center hidden-phone">
										<?php echo $this->escape($item->access_level); ?>
									</td>
									
									<td class="small center hidden-phone">
										<a href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id='.(int) $item->created_user_id); ?>" title="<?php echo JText::_('JAUTHOR'); ?>">
											<?php echo $this->escape($item->author_name); ?>
										</a>
									</td>
																		
									<td class="small center hidden-phone">
										<?php if ($item->language == '*'):?>
											<?php echo JText::alt('JALL', 'language'); ?>
										<?php else:?>
											<?php echo $item->language_title ? $this->escape($item->language_title) : JText::_('JUNDEFINED'); ?>
										<?php endif;?>
									</td>
																		
									<td class="center">
										<?php echo (int) $item->id; ?>
									</td>
								</tr>
							<?php endforeach; 
							} ?>
							</tbody>
						</table>
						
						<?php endif; ?>
										
						<input type="hidden" name="task" value="" />
						<input type="hidden" name="boxchecked" value="0" />
						<?php echo JHtml::_('form.token'); ?>
						
					</div>
					
				</form>
				
			</div><!-- End page-content -->
			
		</div><!-- End main-content -->

	</div><!-- End mcu-main-container -->
	
</div><!-- End Main Container -->
