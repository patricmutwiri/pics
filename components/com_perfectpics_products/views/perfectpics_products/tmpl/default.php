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

<?/*<h2><?php echo JText::_('COM_PERFECTPICS_PRODUCTS_PERFECTPICS_PRODUCT_VIEW_PERFECTPICS_PRODUCTS_TITLE'); ?></h2>*/?>
<form action="<?php JRoute::_('index.php?option=com_mythings&view=mythings'); ?>" method="post" name="adminForm" id="adminForm" class="hidden">
	<?php
		// Search tools bar
		echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
	?>
	<div>
		<p>
			<?php if ($user->authorise("core.create", "com_perfectpics_products") || (count($user->getAuthorisedCategories('com_perfectpics_products', 'core.create'))) > 0 ) : ?>
				<button type="button" class="btn btn-success" onclick="Joomla.submitform('perfectpics_product.add')"><?php echo JText::_('JNEW') ?></button>
			<?php endif; ?>
			<?php if (($user->authorise("core.edit", "com_perfectpics_products") || $user->authorise("core.edit.own", "com_perfectpics_products")) && isset($this->items[0])) : ?>
				<button type="button" class="btn" onclick="Joomla.submitform('perfectpics_product.edit')"><?php echo JText::_('JEDIT') ?></button>
			<?php endif; ?>
			<?php if ($user->authorise("core.edit.state", "com_perfectpics_products")) : ?>
				<?php if (isset($this->items[0]->published)) : ?>
					<button type="button" class="btn" onclick="Joomla.submitform('perfectpics_products.publish')"><?php echo JText::_('JPUBLISH') ?></button>
					<button type="button" class="btn" onclick="Joomla.submitform('perfectpics_products.unpublish')"><?php echo JText::_('JUNPUBLISH') ?></button>
					<button type="button" class="btn" onclick="Joomla.submitform('perfectpics_products.archive')"><?php echo JText::_('JARCHIVE') ?></button>
				<?php elseif (isset($this->items[0])) : ?>
					<button type="button" class="btn btn-error" onclick="Joomla.submitform('perfectpics_products.delete')"><?php echo JText::_('JDELETE') ?></button>
				<?php endif; ?>
				<?php if (isset($this->items[0]->published)) : ?>
					<?php if ($this->state->get('filter.published') == -2 && $user->authorise("core.delete", "com_perfectpics_products")) : ?>
						<button type="button" class="btn btn-error" onclick="Joomla.submitform('perfectpics_products.delete')"><?php echo JText::_('JDELETE') ?></button>
					<?php elseif ($this->state->get('filter.published') != -2 && $user->authorise("core.edit.state", "com_perfectpics_products")) : ?>
						<button type="button" class="btn btn-warning" onclick="Joomla.submitform('perfectpics_products.trash')"><?php echo JText::_('JTRASH') ?></button>
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
					<?php echo JHtml::_('grid.sort', 'JGLOBAL_TITLE', 'a.product_name', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap left">
					<?php echo JHtml::_('grid.sort', JText::_('COM_PERFECTPICS_PRODUCTS_PERFECTPICS_PRODUCT_FIELD_PRICE_FROM_LABEL'), 'a.price_from', $listDirn, $listOrder) ?>
				</th>
				<?php if ($this->params->get('list_show_author', 1)) : ?>
				<th id="itemlist_header_author">
					<?php echo JHtml::_('grid.sort', 'JAUTHOR', 'author', $listDirn, $listOrder); ?>
				</th>
				<?php endif; ?>
				<?php if ($this->params->get('list_show_hits', 1)) : ?>
				<th id="itemlist_header_hits">
					<?php echo JHtml::_('grid.sort', 'JGLOBAL_HITS', 'a.hits', $listDirn, $listOrder); ?>
				</th>
				<?php endif; ?>
				<?php if ($this->user->authorise('core.edit') || $this->user->authorise('core.edit.own')) : ?>
				<th id="itemlist_header_edit"><?php echo JText::_('COM_PERFECTPICS_PRODUCTS_EDIT_ITEM'); ?></th>
				<?php endif; ?>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($this->items as $i => $item) :
		$canEdit	= $this->user->authorise('core.edit',       'com_perfectpics_products'.'.perfectpics_product.'.$item->id);
		$canEditOwn	= $this->user->authorise('core.edit.own',   'com_perfectpics_products'.'.perfectpics_product.'.$item->id) && $item->created_by == $this->user->id;
		$canDelete	= $this->user->authorise('core.delete',       'com_perfectpics_products'.'.perfectpics_product.'.$item->id);
		$canCheckin	= $this->user->authorise('core.manage',     'com_checkin') || $item->checked_out == $this->user->id || $item->checked_out == 0;
		$canChange	= $this->user->authorise('core.edit.state', 'com_perfectpics_products'.'.perfectpics_product.'.$item->id) && $canCheckin;
		?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="center"><?php echo JHtml::_('grid.id', $i, $item->id); ?></td>
				<td headers="itemlist_header_title" class="list-title">
					<?php if (isset($item->access) && in_array($item->access, $this->user->getAuthorisedViewLevels())) : ?>
						<a href="<?php echo JRoute::_("index.php?option=com_perfectpics_products&view=perfectpics_product&id=" . $item->id); ?>">
							<?php echo $this->escape($item->product_name); ?>
						</a>
					<?php else: ?>
						<?php echo $this->escape($item->product_name); ?>
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
				<td style="width:50%"><?php echo $this->escape($item->price_from); ?></td>
				<?php if ($this->params->get('list_show_author', 1)) : ?>
				<td class="small hidden-phone">
					<?php echo $this->escape($item->author_name); ?>
				</td>
				<?php endif; ?>
				<?php if ($this->params->get('list_show_hits', 1)) : ?>
				<td headers="itemlist_header_hits" class="list-hits">
					<span class="badge badge-info">
						<?php echo JText::sprintf('JGLOBAL_HITS_COUNT', $item->hits); ?>
					</span>
				</td>
				<?php endif; ?>
				<?php if ($this->user->authorise("core.edit") || $this->user->authorise("core.edit.own")) : ?>
				<td headers="itemlist_header_edit" class="list-edit">
					<?php if ($canEdit || $canEditOwn) : ?>
						<a href="<?php echo JRoute::_("index.php?option=com_perfectpics_products&task=perfectpics_product.edit&id=" . $item->id); ?>"><i class="icon-edit"></i> <?php echo JText::_("JGLOBAL_EDIT"); ?></a>
					<?php endif; ?>
				</td>
				<?php endif; ?>
			</tr>
		<?php endforeach ?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="7"><?php echo $this->pagination->getListFooter(); ?></td>
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
