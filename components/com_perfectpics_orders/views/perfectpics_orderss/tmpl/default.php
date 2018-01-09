<?php
/**
 * @author
 * @copyright
 * @license
 */

defined("_JEXEC") or die("Restricted access");

// necessary libraries
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');

// sort ordering and direction
$listOrder = $this->state->get('list.ordering');
$listDirn = $this->state->get('list.direction');
$archived	= $this->state->get('filter.published') == 2 ? true : false;
$trashed	= $this->state->get('filter.published') == -2 ? true : false;
$user = JFactory::getUser();
?>
<style>
.row2 {
	background-color: #e4e4e4;
}
</style>

<h2><?php echo JText::_('COM_PERFECTPICS_ORDERS_PERFECTPICS_ORDERS_VIEW_PERFECTPICS_ORDERSS_TITLE'); ?></h2>
<form action="<?php JRoute::_('index.php?option=com_mythings&view=mythings'); ?>" method="post" name="adminForm" id="adminForm">
	<?php
		// Search tools bar
		echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
	?>
	<div>
		<p>
			<?php if ($user->authorise("core.create", "com_perfectpics_orders") || (count($user->getAuthorisedCategories('com_perfectpics_orders', 'core.create'))) > 0 ) : ?>
				<button type="button" class="btn btn-success" onclick="Joomla.submitform('perfectpics_orders.add')"><?php echo JText::_('JNEW') ?></button>
			<?php endif; ?>
			<?php if (($user->authorise("core.edit", "com_perfectpics_orders") || $user->authorise("core.edit.own", "com_perfectpics_orders")) && isset($this->items[0])) : ?>
				<button type="button" class="btn" onclick="Joomla.submitform('perfectpics_orders.edit')"><?php echo JText::_('JEDIT') ?></button>
			<?php endif; ?>
			<?php if ($user->authorise("core.edit.state", "com_perfectpics_orders")) : ?>
				<?php if (isset($this->items[0]->published)) : ?>
					<button type="button" class="btn" onclick="Joomla.submitform('perfectpics_orderss.publish')"><?php echo JText::_('JPUBLISH') ?></button>
					<button type="button" class="btn" onclick="Joomla.submitform('perfectpics_orderss.unpublish')"><?php echo JText::_('JUNPUBLISH') ?></button>
					<button type="button" class="btn" onclick="Joomla.submitform('perfectpics_orderss.archive')"><?php echo JText::_('JARCHIVE') ?></button>
				<?php elseif (isset($this->items[0])) : ?>
					<button type="button" class="btn btn-error" onclick="Joomla.submitform('perfectpics_orderss.delete')"><?php echo JText::_('JDELETE') ?></button>
				<?php endif; ?>
				<?php if (isset($this->items[0]->published)) : ?>
					<?php if ($this->state->get('filter.published') == -2 && $user->authorise("core.delete", "com_perfectpics_orders")) : ?>
						<button type="button" class="btn btn-error" onclick="Joomla.submitform('perfectpics_orderss.delete')"><?php echo JText::_('JDELETE') ?></button>
					<?php elseif ($this->state->get('filter.published') != -2 && $user->authorise("core.edit.state", "com_perfectpics_orders")) : ?>
						<button type="button" class="btn btn-warning" onclick="Joomla.submitform('perfectpics_orderss.trash')"><?php echo JText::_('JTRASH') ?></button>
					<?php endif; ?>
				<?php endif; ?>
			<?php endif; ?>
		</p>
	</div>
	<table class="category table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th width="1%" class="hidden-phone">
					<?php echo JHtml::_('grid.checkall'); ?>
				</th>
				<th id="itemlist_header_title">
					<?php echo JHtml::_('grid.sort', 'JGLOBAL_TITLE', 'a.customers_name', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap left">
					<?php echo JHtml::_('grid.sort', JText::_('COM_PERFECTPICS_ORDERS_PERFECTPICS_ORDERS_FIELD_CUSTOMERS_EMAIL_LABEL'), 'a.customers_email', $listDirn, $listOrder) ?>
				</th>
				<th class="nowrap left">
					<?php echo JHtml::_('grid.sort', JText::_('COM_PERFECTPICS_ORDERS_PERFECTPICS_ORDERS_FIELD_CUSTOMERS_PHONE_LABEL'), 'a.customers_phone', $listDirn, $listOrder) ?>
				</th>
				<th class="nowrap left">
					<?php echo JHtml::_('grid.sort', JText::_('COM_PERFECTPICS_ORDERS_PERFECTPICS_ORDERS_FIELD_UPLOAD_PDF_LABEL'), 'a.upload_pdf', $listDirn, $listOrder) ?>
				</th>
				<th class="nowrap left">
					<?php echo JHtml::_('grid.sort', JText::_('COM_PERFECTPICS_ORDERS_PERFECTPICS_ORDERS_FIELD_BOOK_SIZE_LABEL'), 'a.book_size', $listDirn, $listOrder) ?>
				</th>
				<th class="nowrap left">
					<?php echo JHtml::_('grid.sort', JText::_('COM_PERFECTPICS_ORDERS_PERFECTPICS_ORDERS_FIELD_COVER_TYPE_LABEL'), 'a.cover_type', $listDirn, $listOrder) ?>
				</th>
				<th class="nowrap left">
					<?php echo JHtml::_('grid.sort', JText::_('COM_PERFECTPICS_ORDERS_PERFECTPICS_ORDERS_FIELD_PAPER_TYPE_LABEL'), 'a.paper_type', $listDirn, $listOrder) ?>
				</th>
				<th class="nowrap left">
					<?php echo JHtml::_('grid.sort', JText::_('COM_PERFECTPICS_ORDERS_PERFECTPICS_ORDERS_FIELD_BOOK_TITLE_LABEL'), 'a.book_title', $listDirn, $listOrder) ?>
				</th>
				<th class="nowrap left">
					<?php echo JHtml::_('grid.sort', JText::_('COM_PERFECTPICS_ORDERS_PERFECTPICS_ORDERS_FIELD_AUTHOR_NAME_LABEL'), 'a.author_name', $listDirn, $listOrder) ?>
				</th>
				<?php if ($this->params->get('list_show_author', 1)) : ?>
				<th id="itemlist_header_author">
					<?php echo JHtml::_('grid.sort', 'JAUTHOR', 'author', $listDirn, $listOrder); ?>
				</th>
				<?php endif; ?>
				<?php if ($this->user->authorise('core.edit') || $this->user->authorise('core.edit.own')) : ?>
				<th id="itemlist_header_edit"><?php echo JText::_('COM_PERFECTPICS_ORDERS_EDIT_ITEM'); ?></th>
				<?php endif; ?>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($this->items as $i => $item) :
		$canEdit	= $this->user->authorise('core.edit',       'com_perfectpics_orders'.'.perfectpics_orders.'.$item->id);
		$canEditOwn	= $this->user->authorise('core.edit.own',   'com_perfectpics_orders'.'.perfectpics_orders.'.$item->id) && $item->created_by == $this->user->id;
		$canDelete	= $this->user->authorise('core.delete',       'com_perfectpics_orders'.'.perfectpics_orders.'.$item->id);
		$canCheckin	= $this->user->authorise('core.manage',     'com_checkin') || $item->checked_out == $this->user->id || $item->checked_out == 0;
		$canChange	= $this->user->authorise('core.edit.state', 'com_perfectpics_orders'.'.perfectpics_orders.'.$item->id) && $canCheckin;
		?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="center"><?php echo JHtml::_('grid.id', $i, $item->id); ?></td>
				<td headers="itemlist_header_title" class="list-title">
					<?php if (isset($item->access) && in_array($item->access, $this->user->getAuthorisedViewLevels())) : ?>
						<a href="<?php echo JRoute::_("index.php?option=com_perfectpics_orders&view=perfectpics_orders&id=" . $item->id); ?>">
							<?php echo $this->escape($item->customers_name); ?>
						</a>
					<?php else: ?>
						<?php echo $this->escape($item->customers_name); ?>
					<?php endif; ?>
					<?php if ($item->published == 0) : ?>
						<span class="list-published label label-warning">
							<?php echo JText::_('JUNPUBLISHED'); ?>
						</span>
					<?php endif; ?>
					<?php if ($item->published == 2) : ?>
						<span class="list-published label label-info">
							<?php echo JText::_('JARCHIVED'); ?>
						</span>
					<?php endif; ?>
					<?php if ($item->published == -2) : ?>
						<span class="list-published label">
							<?php echo JText::_('JTRASHED'); ?>
						</span>
					<?php endif; ?>
					<?php if (strtotime($item->publish_up) > strtotime(JFactory::getDate())) : ?>
						<span class="list-published label label-warning">
							<?php echo JText::_('JNOTPUBLISHEDYET'); ?>
						</span>
					<?php endif; ?>
					<?php if ((strtotime($item->publish_down) < strtotime(JFactory::getDate())) && $item->publish_down != '0000-00-00 00:00:00') : ?>
						<span class="list-published label label-warning">
							<?php echo JText::_('JEXPIRED'); ?>
						</span>
					<?php endif; ?>
				</td>
				<td style="width:50%"><?php echo $this->escape($item->customers_email); ?></td>
				<td style="width:50%"><?php echo $this->escape($item->customers_phone); ?></td>
				<td style="width:50%"><?php echo $this->escape($item->upload_pdf); ?></td>
				<td style="width:50%"><?php echo $this->escape($item->book_size); ?></td>
				<td style="width:50%"><?php echo $this->escape($item->cover_type); ?></td>
				<td style="width:50%"><?php echo $this->escape($item->paper_type); ?></td>
				<td style="width:50%"><?php echo $this->escape($item->book_title); ?></td>
				<td style="width:50%"><?php echo $this->escape($item->author_name); ?></td>
				<?php if ($this->params->get('list_show_author', 1)) : ?>
				<td class="small hidden-phone">
					<?php echo $this->escape($item->author_name); ?>
				</td>
				<?php endif; ?>
				<?php if ($this->user->authorise("core.edit") || $this->user->authorise("core.edit.own")) : ?>
				<td headers="itemlist_header_edit" class="list-edit">
					<?php if ($canEdit || $canEditOwn) : ?>
						<a href="<?php echo JRoute::_("index.php?option=com_perfectpics_orders&task=perfectpics_orders.edit&id=" . $item->id); ?>"><i class="icon-edit"></i> <?php echo JText::_("JGLOBAL_EDIT"); ?></a>
					<?php endif; ?>
				</td>
				<?php endif; ?>
			</tr>
		<?php endforeach ?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="11"><?php echo $this->pagination->getListFooter(); ?></td>
			</tr>
		</tfoot>
	</table>
	<div>
		<input type="hidden" name="task" value=" " />
		<input type="hidden" name="boxchecked" value="0" />
		<!-- Sortierkriterien -->
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
