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

function getcategoryname($id)
{
	$id 	= (int)$id;
	$db 	= JFactory::getDbo();
	$query 	= $db->getQuery(true);
	$query 	= $query->select('title')
				->from('#__categories')
				->where($db->quoteName('extension') .' = '.$db->quote('com_perfectpics_products'))
				->where($db->quoteName('id') .' = '.$db->quote($id));
	$db->setQuery($query);
	$category = $db->loadObject();
	if(!empty($category)) {
		return $category;
	} else {
		return '';
	}
}

function getproduct($id)
{
	$id 	= $id;
	$db 	= JFactory::getDbo();
	$query 	= $db->getQuery(true);
	$query 	= $query->select('`id`,`product_name`,`range` as `prange`')
				->from('#__perfectpics_product')
				->where($db->quoteName('id') .' = '.(int)$id.'');
	$db->setQuery($query);
	$productz = $db->loadObject();
	if(!empty($productz)) {
		return $productz;
	} else {
		return '';
	}
}


if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_perfectpics_products&task=discounts.ordering&tmpl=component';
	JHtml::_('sortablelist.sortable', 'discountList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
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

<form action="<?php echo JRoute::_('index.php?option=com_perfectpics_products&view=discounts'); ?>" method="post" name="adminForm" id="adminForm">

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


	<table class="table table-striped" id="discountList">
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
					<?php echo JHtml::_('searchtools.sort', JText::_('Discount/Coupon Code', 'name'), $listDirn, $listOrder) ?>
				</th>
				<th class="">
					<?php echo JHtml::_('searchtools.sort', JText::_('Type', 'type'), $listDirn, $listOrder) ?>
				</th>
				<th class="">
					<?php echo JHtml::_('searchtools.sort', JText::_('Percentage(%)', 'percentage'), $listDirn, $listOrder) ?>
				</th>
				<th class="">
					<?php echo JHtml::_('searchtools.sort', JText::_('Products', 'products'), $listDirn, $listOrder) ?>
				</th>
				<th class="">
					<?php echo JHtml::_('searchtools.sort', JText::_('Categories', 'categories'), $listDirn, $listOrder) ?>
				</th>
				<th class="">
					<?php echo JHtml::_('searchtools.sort', JText::_('Date From', 'publish_up'), $listDirn, $listOrder) ?>
				</th>
				<th class="nowrap left">
					<?php echo JHtml::_('grid.sort', JText::_('End Date'), 'publish_down', $listDirn, $listOrder) ?>
				</th>
				<th class="nowrap left">
					<?php echo JHtml::_('searchtools.sort', JText::_('COM_PERFECTPICS_PRODUCTS_PERFECTPICS_PRODUCT_FIELD_ID_LABEL'), 'id', $listDirn, $listOrder) ?>
				</th>
			</tr>
		</thead>
				
		<tbody>
		
		<?php foreach ($this->items as $i => $item) :
		$canEdit	= $user->authorise('core.edit',       'com_perfectpics_products.discount.'.$item->id);
		$canCheckin	= $user->authorise('core.manage',     'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
		$canEditOwn	= $user->authorise('core.edit.own',   'com_perfectpics_products.discount.'.$item->id) && $item->created_by == $userId;
		$canChange	= $user->authorise('core.edit.state', 'com_perfectpics_products.discount.'.$item->id) && $canCheckin;
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
						<?php echo JHtml::_('jgrid.published', $item->published, $i, 'discounts.', $canChange, 'cb', $item->publish_up, $item->publish_down); ?>
					</td>
                <?php endif; ?>
				<!-- item main field -->
				<td class="nowrapx has-contextx">
						<div class="pull-left">
							<?php if ($item->checked_out) : ?>
								<?php echo JHtml::_('jgrid.checkedout', $i, null, $item->checked_out_time, 'discounts.', $canCheckin); ?>
							<?php endif; ?>
							<?php if ($canEdit || $canEditOwn) : ?>
								<a href="<?php echo JRoute::_('index.php?option=com_perfectpics_products&task=discount.edit&id='.(int) $item->id); ?>">
								<?php echo $this->escape($item->name); ?></a>
							<?php else : ?>
								<?php echo $this->escape($item->name); ?>
							<?php endif; ?>
						</div>
						<div class="pull-left">
							<?php
								// Create dropdown items
								JHtml::_('dropdown.edit', $item->id, 'discount.');
								if (!isset($this->items[0]->published) || $this->state->get('filter.published') == -2) :
									JHtml::_('dropdown.addCustomItem', JText::_('JTOOLBAR_DELETE'), 'javascript:void(0)', "onclick=\"contextAction('cb$i', 'discounts.delete')\"");
								endif;
								JHtml::_('dropdown.divider');

								if ($item->published != 0) :
									JHtml::_('dropdown.unpublish', 'cb' . $i, 'discounts.');
								endif;
								if ($item->published != 1) :
									JHtml::_('dropdown.publish', 'cb' . $i, 'discounts.');
								endif;

								JHtml::_('dropdown.divider');
								if ($item->published != 2) :
									JHtml::_('dropdown.archive', 'cb' . $i, 'discounts.');
								endif;

								if ($item->checked_out) :
									JHtml::_('dropdown.checkin', 'cb' . $i, 'discounts.');
								endif;

								if ($item->published != -2 && $this->state->get('filter.published') != -2) :
									JHtml::_('dropdown.trash', 'cb' . $i, 'discounts.');
								endif;

								// render dropdown list
								echo JHtml::_('dropdown.render');
							?>
						</div>
				</td>
				<td class="left"><?php if($item->type == 1) { echo 'Discount'; } else { echo 'Coupon'; }; ?></td>
				<td class="left"><?php echo number_format($item->percentage,2); ?></td>
				<td class="left">
				<?php 
				if($item->products != '') {
					$pids = json_decode($item->products);
					echo '<ul>';
					foreach ($pids as $key => $value) {
						$prds = getproduct($value);
						echo '<li><small>'.$prds->product_name.'('.$prds->prange.')</small></li>';
					}
					echo '</ul>';
				}
				//echo $item->products; 
				?></td>
				<td class="left">
				<?php 
					if($item->categories != '') {
						$cats = json_decode($item->categories);
						echo '<ul>';
						foreach ($cats as $key => $value) {
							$cat = getcategoryname($value);
							echo '<li><small>'.$cat->title.'</small></li>';
						}
						echo '</ul>';
					}
					//echo $item->categories; 
				?></td>
				<td class="left"><?php echo $item->publish_up; ?></td>
				<td class="left"><?php echo $item->publish_down; ?></td>
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