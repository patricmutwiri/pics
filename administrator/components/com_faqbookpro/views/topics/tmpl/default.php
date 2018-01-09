<?php
/**
* @title			Minitek FAQ Book
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

$app       = JFactory::getApplication();
$user      = JFactory::getUser();
$userId    = $user->get('id');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
$ordering  = ($listOrder == 'a.lft');
$saveOrder = ($listOrder == 'a.lft' && strtolower($listDirn) == 'asc');

if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_faqbookpro&task=topics.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'categoryList', 'adminForm', strtolower($listDirn), $saveOrderingUrl, false, true);
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

				<form action="<?php echo JRoute::_('index.php?option=com_faqbookpro&view=topics'); ?>" method="post" name="adminForm" id="adminForm">
										
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
						
							<table class="table table-striped" id="categoryList">
								<thead>
									<tr>
										<th width="1%" class="nowrap center hidden-phone">
											<?php echo JHtml::_('searchtools.sort', '', 'a.lft', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
										</th>
										<th width="1%" class="center">
											<?php echo JHtml::_('grid.checkall'); ?>
										</th>
										<th width="1%" class="nowrap center">
											<?php echo JHtml::_('searchtools.sort', 'JSTATUS', 'a.published', $listDirn, $listOrder); ?>
										</th>
										<th>
											<?php echo JHtml::_('searchtools.sort', 'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder); ?>
										</th>
										<th width="5%" class="nowrap center hidden-phone">
											<?php echo JHtml::_('searchtools.sort', 'COM_FAQBOOKPRO_HEADING_QUESTIONS_COUNT', 'questions_count', $listDirn, $listOrder); ?>
										</th>
										<th width="10%" class="nowrap center hidden-phone">
											<?php echo JHtml::_('searchtools.sort', 'COM_FAQBOOKPRO_HEADING_SECTION_TITLE', 'section_title', $listDirn, $listOrder); ?>
										</th>
										<th width="10%" class="nowrap center hidden-phone">
											<?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_ACCESS', 'a.access', $listDirn, $listOrder); ?>
										</th>
										<th width="5%" class="nowrap center hidden-phone">
											<?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_LANGUAGE', 'a.language', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
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
									<?php foreach ($this->items as $i => $item) : ?>
										<?php
										$orderkey   = array_search($item->id, $this->ordering[$item->parent_id]);
										$canEdit    = $user->authorise('core.edit',       'com_faqbookpro.topic.' . $item->id);
										$canCheckin = $user->authorise('core.admin',      'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
										$canEditOwn = $user->authorise('core.edit.own',   'com_faqbookpro.topic.' . $item->id) && $item->created_user_id == $userId;
										$canChange  = $user->authorise('core.edit.state', 'com_faqbookpro.topic.' . $item->id) && $canCheckin;
				
										// Get the parents of item for sorting
										if ($item->level > 1)
										{
											$parentsStr = "";
											$_currentParentId = $item->parent_id;
											$parentsStr = " " . $_currentParentId;
											for ($i2 = 0; $i2 < $item->level; $i2++)
											{
												foreach ($this->ordering as $k => $v)
												{
													$v = implode("-", $v);
													$v = "-" . $v . "-";
													if (strpos($v, "-" . $_currentParentId . "-") !== false)
													{
														$parentsStr .= " " . $k;
														$_currentParentId = $k;
														break;
													}
												}
											}
										}
										else
										{
											$parentsStr = "";
										}
										?>
										<tr class="row<?php echo $i % 2; ?>" sortable-group-id="<?php echo $item->parent_id; ?>" item-id="<?php echo $item->id ?>" parents="<?php echo $parentsStr ?>" level="<?php echo $item->level ?>">
											
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
													<span class="icon-menu"></span>
												</span>
												<?php if ($canChange && $saveOrder) : ?>
													<input type="text" style="display:none" name="order[]" size="5" value="<?php echo $orderkey + 1; ?>" />
												<?php endif; ?>
											</td>
											
											<td class="center">
												<?php echo JHtml::_('grid.id', $i, $item->id); ?>
											</td>
											
											<td class="center">
												<?php echo JHtml::_('jgrid.published', $item->published, $i, 'topics.', $canChange); ?>
											</td>
											
											<td class="nowrap has-context">
												<?php echo str_repeat('<span class="gi">&mdash;</span>', $item->level - 1) ?>
												<?php if ($item->checked_out) : ?>
													<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'topics.', $canCheckin); ?>
												<?php endif; ?>
												<?php if ($canEdit || $canEditOwn) : ?>
													<a href="<?php echo JRoute::_('index.php?option=com_faqbookpro&task=topic.edit&id=' . $item->id); ?>">
														<?php echo $this->escape($item->title); ?></a>
												<?php else : ?>
													<?php echo $this->escape($item->title); ?>
												<?php endif; ?>
											</td>
											
											<td class="small center hidden-phone">
												<?php echo $item->questions_count; ?>
											</td>
											
											<td class="small center">
												<a class="hasTooltip" href="<?php echo JRoute::_('index.php?option=com_faqbookpro&task=section.edit&id=' . $item->section_id); ?>" title="<?php echo JText::_('COM_FAQBOOKPRO_EDIT_SECTION'); ?>">
													<?php echo $this->escape($item->section_title); ?>
												</a>
											</td>
											
											<td class="small center hidden-phone">
												<?php echo $this->escape($item->access_level); ?>
											</td>
											
											<td class="small center hidden-phone">
												<?php if ($item->language == '*') : ?>
													<?php echo JText::alt('JALL', 'language'); ?>
												<?php else: ?>
													<?php echo $item->language_title ? $this->escape($item->language_title) : JText::_('JUNDEFINED'); ?>
												<?php endif; ?>
											</td>
											
											<td class="center">
												<span title="<?php echo sprintf('%d-%d', $item->lft, $item->rgt); ?>">
													<?php echo (int) $item->id; ?>
												</span>
											</td>
											
										</tr>
									<?php endforeach; ?>
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