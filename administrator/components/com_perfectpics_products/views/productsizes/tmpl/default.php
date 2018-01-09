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
$user	= JFactory::getUser();
$userId	= $user->get('id');
$listOrder = $this->state->get('list.ordering');
$listDirn = $this->state->get('list.direction');
$archived	= $this->state->get('filter.published') == 2 ? true : false;
$trashed	= $this->state->get('filter.published') == -2 ? true : false;
$canOrder	= ($user->authorise('core.edit.state', 'com_test') && isset($this->items[0]->ordering));
$saveOrder = ($listOrder == 'ordering' && isset($this->items[0]->ordering));

if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_perfectpics_products&task=productsizes.ordering&tmpl=component';
	JHtml::_('sortablelist.sortable', 'productsizeList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
?>

<script type="text/javascript">
	Joomla.orderTable = function()
	{
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
		if (order != '<?php echo $listOrder; ?>')
		{
			dirn = 'asc';
		}
		else
		{
			dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn, '');
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_perfectpics_products&view=productsizes'); ?>" method="post" name="adminForm" id="adminForm">

<?php if (!empty( $this->sidebar)) : ?>
	<!-- sidebar -->
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<!-- end sidebar -->
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>

	<?php
		// Search tools bar
		echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
	?>
	<?php if (empty($this->items)) : ?>
		<div class="alert alert-no-items">
			<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
		</div>
	<?php else : ?>


	<table class="table table-striped" id="productsizeList">
		<thead>
			<tr>
				
				<!-- item ordering -->
				<?php if (isset($this->items[0]->ordering)): ?>				
					<th width="1%" class="nowrap center hidden-phone">
						<?php echo JHtml::_('searchtools.sort', '', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
					</th>
				<?php endif; ?>
				<!-- item checkbox -->
				<th width="1%">
					<?php echo JHtml::_('grid.checkall'); ?>
				</th>
				<!-- item state -->
				<?php if (isset($this->items[0]->published)): ?>
					<th width="1%" class="nowrap center">
						<?php echo JHtml::_('searchtools.sort', 'JSTATUS', 'published', $listDirn, $listOrder); ?>
					</th>
                <?php endif; ?>
				<th class="">
					<?php echo JHtml::_('searchtools.sort', JText::_('Size', 'size'), $listDirn, $listOrder) ?>
				</th>
				<th class="">
					<?php echo JHtml::_('searchtools.sort', JText::_('Image', 'image'), $listDirn, $listOrder) ?>
				</th>
				
				<th class="">
					<?php echo JHtml::_('searchtools.sort', JText::_('Publish On', 'publish_up'), $listDirn, $listOrder) ?>
				</th>
				<th class="nowrap left">
					<?php echo JHtml::_('grid.sort', JText::_('UnPublish On'), 'publish_down', $listDirn, $listOrder) ?>
				</th>
				<th class="nowrap left">
					<?php echo JHtml::_('searchtools.sort', JText::_('COM_PERFECTPICS_PRODUCTS_PERFECTPICS_PRODUCT_FIELD_ID_LABEL'), 'id', $listDirn, $listOrder) ?>
				</th>
			</tr>
		</thead>
				
		<tbody>
		
		<?php foreach ($this->items as $i => $item) :
		$canEdit	= $user->authorise('core.edit',       'com_perfectpics_products.productsize.'.$item->id);
		$canCheckin	= $user->authorise('core.manage',     'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
		$canEditOwn	= $user->authorise('core.edit.own',   'com_perfectpics_products.productsize.'.$item->id) && $item->created_by == $userId;
		$canChange	= $user->authorise('core.edit.state', 'com_perfectpics_products.productsize.'.$item->id) && $canCheckin;
		?>
		
			<tr class="row<?php echo $i % 2; ?>">
				
				<!-- item ordering -->
				<?php if (isset($this->items[0]->ordering)): ?>
					<td class="order nowrap center hidden-phone">
					<?php if ($canChange) :
						$disableClassName = '';
						$disabledLabel	  = '';
						if (!$saveOrder) :
							$disabledLabel    = JText::_('JORDERINGDISABLED');
							$disableClassName = 'inactive tip-top';
						endif; ?>
						<span class="sortable-handler hasTooltip <?php echo $disableClassName?>" title="<?php echo $disabledLabel?>">
							<i class="icon-menu"></i>
						</span>
						<input type="text" style="display:none" name="order[]" size="5" value="<?php echo $item->ordering;?>" class="width-20 text-area-order " />
					<?php else : ?>
						<span class="sortable-handler inactive" >
							<i class="icon-menu"></i>
						</span>
					<?php endif; ?>
					</td>
                <?php endif; ?>
				
				<!-- item checkbox -->
				<td class="center"><?php echo JHtml::_('grid.id', $i, $item->id); ?></td>
				<!-- item state -->
				<?php if (isset($this->items[0]->published)): ?>
					<td class="center">
						<?php echo JHtml::_('jgrid.published', $item->published, $i, 'productsizes.', $canChange, 'cb', $item->publish_up, $item->publish_down); ?>
					</td>
                <?php endif; ?>
				<!-- item main field -->
				<td class="nowrapx has-contextx">
						<div class="pull-left">
							<?php if ($item->checked_out) : ?>
								<?php echo JHtml::_('jgrid.checkedout', $i, null, $item->checked_out_time, 'productsizes.', $canCheckin); ?>
							<?php endif; ?>
							<?php if ($canEdit || $canEditOwn) : ?>
								<a href="<?php echo JRoute::_('index.php?option=com_perfectpics_products&task=productsize.edit&id='.(int) $item->id); ?>">
								<?php echo $this->escape($item->size); ?></a>
							<?php else : ?>
								<?php echo $this->escape($item->size); ?>
							<?php endif; ?>
						</div>
						<div class="pull-left">
							<?php
								// Create dropdown items
								JHtml::_('dropdown.edit', $item->id, 'productsize.');
								if (!isset($this->items[0]->published) || $this->state->get('filter.published') == -2) :
									JHtml::_('dropdown.addCustomItem', JText::_('JTOOLBAR_DELETE'), 'javascript:void(0)', "onclick=\"contextAction('cb$i', 'productsizes.delete')\"");
								endif;
								JHtml::_('dropdown.divider');

								if ($item->published != 0) :
									JHtml::_('dropdown.unpublish', 'cb' . $i, 'productsizes.');
								endif;
								if ($item->published != 1) :
									JHtml::_('dropdown.publish', 'cb' . $i, 'productsizes.');
								endif;

								JHtml::_('dropdown.divider');
								if ($item->published != 2) :
									JHtml::_('dropdown.archive', 'cb' . $i, 'productsizes.');
								endif;

								if ($item->checked_out) :
									JHtml::_('dropdown.checkin', 'cb' . $i, 'productsizes.');
								endif;

								if ($item->published != -2 && $this->state->get('filter.published') != -2) :
									JHtml::_('dropdown.trash', 'cb' . $i, 'productsizes.');
								endif;

								// render dropdown list
								echo JHtml::_('dropdown.render');
							?>
						</div>
				</td>
				<td class="left"><img src="<?php JURI::root().$item->image ?>"/></td>
				<td class="left"><?php echo $item->publish_up; ?></td>
				<td class="left"><?php echo $item->publish_up; ?></td>
				<td class="left"><?php echo $this->escape($item->id); ?></td>
			</tr>
		<?php endforeach ?>
		</tbody>	
	</table>
	<?php endif; ?>
	<?php echo $this->pagination->getListFooter(); ?>
	<?php //Load the batch processing form. ?>
	<?php echo $this->loadTemplate('batch'); ?>
	
	<div>
		<input type="hidden" name="task" value=" " />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token'); ?>
	</div>

	</form>
</div>