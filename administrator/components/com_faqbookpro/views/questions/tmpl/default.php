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

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$app       = JFactory::getApplication();
$user      = JFactory::getUser();
$userId    = $user->get('id');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
$archived  = $this->state->get('filter.published') == 2 ? true : false;
$trashed   = $this->state->get('filter.published') == -2 ? true : false;
$saveOrder = $listOrder == 'a.ordering';
$columns   = 12;

if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_faqbookpro&task=questions.saveOrderAjax&tmpl=component';
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

				<form action="<?php echo JRoute::_('index.php?option=com_faqbookpro&view=questions'); ?>" method="post" name="adminForm" id="adminForm">
				
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
										<th width="1%" style="min-width:55px" class="nowrap center">
											<?php echo JHtml::_('searchtools.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
										</th>
										<th>
											<?php echo JHtml::_('searchtools.sort', 'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder); ?>
										</th>
										<th width="10%" class="nowrap center hidden-phone">
											<?php echo JHtml::_('searchtools.sort',  'JGRID_HEADING_ACCESS', 'a.access', $listDirn, $listOrder); ?>
										</th>
										<th width="10%" class="nowrap center hidden-phone">
											<?php echo JHtml::_('searchtools.sort',  'JAUTHOR', 'a.created_by', $listDirn, $listOrder); ?>
										</th>
										<th width="5%" class="nowrap center hidden-phone">
											<?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_LANGUAGE', 'language', $listDirn, $listOrder); ?>
										</th>
										<th width="10%" class="nowrap center hidden-phone">
											<?php echo JHtml::_('searchtools.sort', 'JDATE', 'a.created', $listDirn, $listOrder); ?>
										</th>
										<th width="1%" class="nowrap center hidden-phone">
											<?php echo JHtml::_('searchtools.sort', 'JGLOBAL_HITS', 'a.hits', $listDirn, $listOrder); ?>
										</th>
										<th width="1%" class="nowrap center hidden-phone">
											<?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
										</th>
									</tr>
								</thead>
								<tfoot>
									<tr>
										<td colspan="<?php echo $columns; ?>">
										</td>
									</tr>
								</tfoot>
								<tbody>
								<?php foreach ($this->items as $i => $item) :
									$item->max_ordering = 0;
									$ordering   = ($listOrder == 'a.ordering');
									$canCreate  = $user->authorise('core.create',     'com_faqbookpro.topic.' . $item->topicid);
									$canEdit    = $user->authorise('core.edit',       'com_faqbookpro.question.' . $item->id);
									$canCheckin = $user->authorise('core.manage',     'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
									$canEditOwn = $user->authorise('core.edit.own',   'com_faqbookpro.question.' . $item->id) && $item->created_by == $userId;
									$canChange  = $user->authorise('core.edit.state', 'com_faqbookpro.question.' . $item->id) && $canCheckin;
									?>
									<tr class="row<?php echo $i % 2; ?>" sortable-group-id="<?php echo $item->topicid; ?>">
										
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
												<input type="text" style="display:none" name="order[]" size="5" value="<?php echo $item->ordering; ?>" class="width-20 text-area-order " />
											<?php endif; ?>
										</td>
										
										<td class="center">
											<?php echo JHtml::_('grid.id', $i, $item->id); ?>
										</td>
										
										<td class="center">
											<div class="btn-group">
												<?php echo JHtml::_('jgrid.published', $item->state, $i, 'questions.', $canChange, 'cb', $item->publish_up, $item->publish_down); ?>
												<?php echo JHtml::_('contentadministrator.featured', $item->featured, $i, $canChange); ?>
												<?php
												// Create dropdown items
												$action = $archived ? 'unarchive' : 'archive';
												JHtml::_('actionsdropdown.' . $action, 'cb' . $i, 'questions');
				
												$action = $trashed ? 'untrash' : 'trash';
												JHtml::_('actionsdropdown.' . $action, 'cb' . $i, 'questions');
				
												// Render dropdown list
												echo JHtml::_('actionsdropdown.render', $this->escape($item->title));
												?>
											</div>
										</td>
										
										<td class="has-context">
											<div class="pull-left break-word">
												<?php if ($item->checked_out) : ?>
													<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'questions.', $canCheckin); ?>
												<?php endif; ?>
												<?php if ($item->language == '*'):?>
													<?php $language = JText::alt('JALL', 'language'); ?>
												<?php else:?>
													<?php $language = $item->language_title ? $this->escape($item->language_title) : JText::_('JUNDEFINED'); ?>
												<?php endif;?>
												<?php if ($canEdit || $canEditOwn) : ?>
													<a class="hasTooltip" href="<?php echo JRoute::_('index.php?option=com_faqbookpro&task=question.edit&id=' . $item->id); ?>" title="<?php echo JText::_('COM_FAQBOOKPRO_EDIT_QUESTION'); ?>">
														<?php echo $this->escape($item->title); ?></a>
												<?php else : ?>
													<span><?php echo $this->escape($item->title); ?></span>
												<?php endif; ?>												
												<div class="small">
													<?php echo JText::_('COM_FAQBOOKPRO_TOPIC').": "; ?>
													<a class="hasTooltip" href="<?php echo JRoute::_('index.php?option=com_faqbookpro&task=topic.edit&id=' . $item->topicid); ?>" title="<?php echo JText::_('COM_FAQBOOKPRO_EDIT_TOPIC'); ?>">
														<?php echo $this->escape($item->topic_title); ?>
													</a>
												</div>
											</div>
										</td>
																				
										<td class="small center hidden-phone">
											<?php echo $this->escape($item->access_level); ?>
										</td>
										
										<td class="small center hidden-phone">
											<?php if ($item->created_by_alias) : ?>
												<a class="hasTooltip" href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id=' . (int) $item->created_by); ?>" title="<?php echo JText::_('JAUTHOR'); ?>">
												<?php echo $this->escape($item->author_name); ?></a>
												<p class="smallsub"> <?php echo JText::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->created_by_alias)); ?></p>
											<?php else : ?>
												<?php if ($item->created_by) { ?>
													<a class="hasTooltip" href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id=' . (int) $item->created_by); ?>" title="<?php echo JText::_('JAUTHOR'); ?>">
														<?php echo $this->escape($item->author_name); ?>
													</a>
												<?php } else { ?>
													<?php echo JText::_('COM_FAQBOOKPRO_GUEST'); ?>
												<?php } ?>
											<?php endif; ?>
										</td>
																				
										<td class="small center hidden-phone">
											<?php if ($item->language == '*'):?>
												<?php echo JText::alt('JALL', 'language'); ?>
											<?php else:?>
												<?php echo $item->language_title ? $this->escape($item->language_title) : JText::_('JUNDEFINED'); ?>
											<?php endif;?>
										</td>
										
										<td class="nowrap small center hidden-phone">
											<?php echo JHtml::_('date', $item->created, JText::_('DATE_FORMAT_LC4')); ?>
										</td>
										
										<td class="center hidden-phone">
											<?php echo (int) $item->hits; ?>
										</td>
										
										<td class="center hidden-phone">
											<?php echo (int) $item->id; ?>
										</td>
										
									</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
							<?php // Load the batch processing form. ?>
							<?php if ($user->authorise('core.create', 'com_faqbookpro')
								&& $user->authorise('core.edit', 'com_faqbookpro')
								&& $user->authorise('core.edit.state', 'com_faqbookpro')) : ?>
								<?php echo JHtml::_(
									'bootstrap.renderModal',
									'collapseModal',
									array(
										'title' => JText::_('COM_FAQBOOKPRO_BATCH_OPTIONS'),
										'footer' => $this->loadTemplate('batch_footer')
									),
									$this->loadTemplate('batch_body')
								); ?>
							<?php endif; ?>
						<?php endif;?>
				
						<?php echo $this->pagination->getListFooter(); ?>
				
						<input type="hidden" name="task" value="" />
						<input type="hidden" name="boxchecked" value="0" />
						<?php echo JHtml::_('form.token'); ?>
					</div>
				</form>

			</div><!-- End page-content -->
			
		</div><!-- End main-content -->

	</div><!-- End mcu-main-container -->
	
</div><!-- End Main Container -->
