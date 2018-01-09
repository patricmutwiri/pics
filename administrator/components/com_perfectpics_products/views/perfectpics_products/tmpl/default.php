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
	$saveOrderingUrl = 'index.php?option=com_perfectpics_products&task=perfectpics_products.ordering&tmpl=component';
	JHtml::_('sortablelist.sortable', 'perfectpics_productList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
function getpapertype($id)
{
	if(!empty($id)) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query = "select paper_name,paper_description from #__paper_types where id = ".(int)$id;
		$db->setQuery($query);
		$paper = $db->loadObject();
		if(!empty($paper)) {
			return $paper->paper_name;
		} else {
			return 'PaperLess';
		}
	} else {
		return 'PaperLess';
	}

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

<form action="<?php echo JRoute::_('index.php?option=com_perfectpics_products&view=perfectpics_products'); ?>" method="post" name="adminForm" id="adminForm">

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


	<table class="table table-striped" id="perfectpics_productList">
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
						<?php echo JHtml::_('searchtools.sort', 'JSTATUS', 'a.published', $listDirn, $listOrder); ?>
					</th>
                <?php endif; ?>
				<th class="">
					<?php echo JHtml::_('searchtools.sort', JText::_('COM_PERFECTPICS_PRODUCTS_PERFECTPICS_PRODUCT_FIELD_PRODUCT_NAME_LABEL', 'product_name'), $listDirn, $listOrder) ?>
				</th>
				<th class="">
					<?php echo JHtml::_('searchtools.sort', JText::_('Size/Range', 'range'), $listDirn, $listOrder) ?>
				</th>
				<th class="">
					<?php echo JHtml::_('searchtools.sort', JText::_('Minimum Pages', 'minimumpages'), $listDirn, $listOrder) ?>
				</th>
				<th class="">
					<?php echo JHtml::_('searchtools.sort', JText::_('Maximum Pages', 'maximumpages'), $listDirn, $listOrder) ?>
				</th>
				<th class="">
					<?php echo JHtml::_('searchtools.sort', JText::_('Additional Cost per Page', 'additional'), $listDirn, $listOrder) ?>
				</th>

				<th class="nowrap left">
					<?php echo JHtml::_('grid.sort', JText::_('Base Price'), 'a.price_from', $listDirn, $listOrder) ?>
				</th>
				<th class="nowrap left">
					<?php echo JHtml::_('grid.sort', JText::_('Photographers\' Base Price'), 'a.price_from2', $listDirn, $listOrder) ?>
				</th>
				<th class="nowrap left">
					<?php echo JHtml::_('grid.sort', JText::_('Additional Info'), 'a.additionalinfo', $listDirn, $listOrder) ?>
				</th>
				<th class="nowrap left">
					<?php echo JHtml::_('grid.sort', JText::_('Paper Weight'), 'a.paperweight', $listDirn, $listOrder) ?>
				</th>
				<th width="5%" class="nowrap hidden-phone">
					<?php echo JHtml::_('searchtools.sort', 'Paper Type', 'a.papertype', $listDirn, $listOrder); ?>
				</th>
				<th width="10%" class="nowrap hidden xhidden-phone">
					<?php echo JHtml::_('searchtools.sort',  'JAUTHOR', 'a.papertype', $listDirn, $listOrder); ?>
				</th>
				<th width="10%" class="nowrap xhidden-phone">
					<?php echo JHtml::_('searchtools.sort', 'JDATE', 'a.created', $listDirn, $listOrder); ?>
				</th>
				<th width="10%" class="hidden">
					<?php echo JHtml::_('searchtools.sort', 'JGLOBAL_HITS', 'a.hits', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap left">
					<?php echo JHtml::_('searchtools.sort', JText::_('COM_PERFECTPICS_PRODUCTS_PERFECTPICS_PRODUCT_FIELD_ID_LABEL'), 'id', $listDirn, $listOrder) ?>
				</th>
			</tr>
		</thead>
				
		<tbody>
		
		<?php foreach ($this->items as $i => $item) :
		$canEdit	= $user->authorise('core.edit',       'com_perfectpics_products.perfectpics_product.'.$item->id);
		$canCheckin	= $user->authorise('core.manage',     'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
		$canEditOwn	= $user->authorise('core.edit.own',   'com_perfectpics_products.perfectpics_product.'.$item->id) && $item->created_by == $userId;
		$canChange	= $user->authorise('core.edit.state', 'com_perfectpics_products.perfectpics_product.'.$item->id) && $canCheckin;
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
						<?php echo JHtml::_('jgrid.published', $item->published, $i, 'perfectpics_products.', $canChange, 'cb', $item->publish_up, $item->publish_down); ?>
					</td>
                <?php endif; ?>
				<!-- item main field -->
				<td class="nowrapx has-contextx">
						<div class="pull-left">
							<?php if ($item->checked_out) : ?>
								<?php echo JHtml::_('jgrid.checkedout', $i, null, $item->checked_out_time, 'perfectpics_products.', $canCheckin); ?>
							<?php endif; ?>
							<?php if ($canEdit || $canEditOwn) : ?>
								<a href="<?php echo JRoute::_('index.php?option=com_perfectpics_products&task=perfectpics_product.edit&id='.(int) $item->id); ?>">
								<?php echo $this->escape($item->product_name); ?></a>
							<?php else : ?>
								<?php echo $this->escape($item->product_name); ?>
							<?php endif; ?>
							<div class="small">
								<b><?php echo JText::_('JCATEGORY'); ?>: </b><?php echo $this->escape($item->category_title); ?>
							</div>
						</div>
						<div class="pull-left">
							<?php
								// Create dropdown items
								JHtml::_('dropdown.edit', $item->id, 'perfectpics_product.');
								if (!isset($this->items[0]->published) || $this->state->get('filter.published') == -2) :
									JHtml::_('dropdown.addCustomItem', JText::_('JTOOLBAR_DELETE'), 'javascript:void(0)', "onclick=\"contextAction('cb$i', 'perfectpics_products.delete')\"");
								endif;
								JHtml::_('dropdown.divider');

								if ($item->published != 0) :
									JHtml::_('dropdown.unpublish', 'cb' . $i, 'perfectpics_products.');
								endif;
								if ($item->published != 1) :
									JHtml::_('dropdown.publish', 'cb' . $i, 'perfectpics_products.');
								endif;

								JHtml::_('dropdown.divider');
								if ($item->published != 2) :
									JHtml::_('dropdown.archive', 'cb' . $i, 'perfectpics_products.');
								endif;

								if ($item->checked_out) :
									JHtml::_('dropdown.checkin', 'cb' . $i, 'perfectpics_products.');
								endif;

								if ($item->published != -2 && $this->state->get('filter.published') != -2) :
									JHtml::_('dropdown.trash', 'cb' . $i, 'perfectpics_products.');
								endif;

								// render dropdown list
								echo JHtml::_('dropdown.render');
							?>
						</div>
				</td>
				<td class="left"><?php echo $this->escape($item->range); ?></td>
				<td class="left"><?php echo $this->escape($item->minimumpages); ?></td>
				<td class="left"><?php echo $this->escape($item->maximumpages); ?></td>
				<td class="left"><?php echo number_format($item->additional,2); ?></td>
				<td class="left"><?php echo number_format($item->price_from,2); ?></td>
				<td class="left"><?php echo number_format($item->price_from2,2); ?></td>
				<td class="left"><?php echo $this->escape($item->additionalinfo); ?></td>
				<td class="left"><?php echo $this->escape($item->paperweight); ?></td>
				<td class="left">
					<?php echo getpapertype($item->papertype); ?>
				</td>
				<td class="small hidden xhidden-phone">
					<?php if (isset($item->created_by_alias)) : ?>
						<a href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id='.(int) $item->created_by); ?>" title="<?php echo JText::_('JAUTHOR'); ?>">
						<?php echo $this->escape($item->author_name); ?></a>
						<p class="smallsub"> <?php echo JText::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->created_by_alias)); ?></p>
					<?php else : ?>
						<a href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id='.(int) $item->created_by); ?>" title="<?php echo JText::_('JAUTHOR'); ?>">
						<?php echo $this->escape($item->author_name); ?></a>
					<?php endif; ?>
				</td>
				<td class="nowrap small hidden-phone">
					<?php echo JHtml::_('date', $item->created, JText::_('DATE_FORMAT_LC4')); ?>
				</td>
				<td class="hidden center">
					<?php echo (int) $item->hits; ?>
				</td>
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