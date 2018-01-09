<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Orders
 * @author     Michael Buluma <michael@buluma.me.ke>
 * @copyright  2016 Michael Buluma
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root() . 'administrator/components/com_orders/assets/css/orders.css');
$document->addStyleSheet(JUri::root() . 'media/com_orders/css/list.css');

$user      = JFactory::getUser();
$userId    = $user->get('id');
$listOrder = $this->state->get('list.ordering');
$listDirn  = $this->state->get('list.direction');
$canOrder  = $user->authorise('core.edit.state', 'com_orders');
$saveOrder = $listOrder == 'a.`ordering`';

if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_orders&task=orders.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'orderList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}

$sortFields = $this->getSortFields();
?>
<script type="text/javascript">
	Joomla.orderTable = function () {
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
		if (order != '<?php echo $listOrder; ?>') {
			dirn = 'asc';
		} else {
			dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn, '');
	};

	jQuery(document).ready(function () {
		jQuery('#clear-search-button').on('click', function () {
			jQuery('#filter_search').val('');
			jQuery('#adminForm').submit();
		});
	});

	window.toggleField = function (id, task, field) {

		var f = document.adminForm,
			i = 0, cbx,
			cb = f[ id ];

		if (!cb) return false;

		while (true) {
			cbx = f[ 'cb' + i ];

			if (!cbx) break;

			cbx.checked = false;
			i++;
		}

		var inputField   = document.createElement('input');
		inputField.type  = 'hidden';
		inputField.name  = 'field';
		inputField.value = field;
		f.appendChild(inputField);

		cb.checked = true;
		f.boxchecked.value = 1;
		window.submitform(task);

		return false;
	};

</script>

<?php

// Joomla Component Creator code to allow adding non select list filters
if (!empty($this->extra_sidebar))
{
	$this->sidebar .= $this->extra_sidebar;
}

?>

<form action="<?php echo JRoute::_('index.php?option=com_orders&view=orders'); ?>" method="post"
	  name="adminForm" id="adminForm">
	<?php if (!empty($this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
		<?php else : ?>
		<div id="j-main-container">
			<?php endif; ?>

			<div id="filter-bar" class="btn-toolbar">
				<div class="filter-search btn-group pull-left">
					<label for="filter_search"
						   class="element-invisible">
						<?php echo JText::_('JSEARCH_FILTER'); ?>
					</label>
					<input type="text" name="filter_search" id="filter_search"
						   placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>"
						   value="<?php echo $this->escape($this->state->get('filter.search')); ?>"
						   title="<?php echo JText::_('JSEARCH_FILTER'); ?>"/>
				</div>
				<div class="btn-group pull-left">
					<button class="btn hasTooltip" type="submit"
							title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>">
						<i class="icon-search"></i></button>
					<button class="btn hasTooltip" id="clear-search-button" type="button"
							title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>">
						<i class="icon-remove"></i></button>
				</div>
				<div class="btn-group pull-right hidden-phone">
					<label for="limit"
						   class="element-invisible">
						<?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC'); ?>
					</label>
					<?php echo $this->pagination->getLimitBox(); ?>
				</div>
				<div class="btn-group pull-right hidden-phone">
					<label for="directionTable"
						   class="element-invisible">
						<?php echo JText::_('JFIELD_ORDERING_DESC'); ?>
					</label>
					<select name="directionTable" id="directionTable" class="input-medium"
							onchange="Joomla.orderTable()">
						<option value=""><?php echo JText::_('JFIELD_ORDERING_DESC'); ?></option>
						<option value="asc" <?php echo $listDirn == 'asc' ? 'selected="selected"' : ''; ?>>
							<?php echo JText::_('JGLOBAL_ORDER_ASCENDING'); ?>
						</option>
						<option value="desc" <?php echo $listDirn == 'desc' ? 'selected="selected"' : ''; ?>>
							<?php echo JText::_('JGLOBAL_ORDER_DESCENDING'); ?>
						</option>
					</select>
				</div>
				<div class="btn-group pull-right">
					<label for="sortTable" class="element-invisible"><?php echo JText::_('JGLOBAL_SORT_BY'); ?></label>
					<select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
						<option value=""><?php echo JText::_('JGLOBAL_SORT_BY'); ?></option>
						<?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $listOrder); ?>
					</select>
				</div>
			</div>
			<div class="clearfix"></div>
			<table class="table table-striped" id="orderList">
				<thead>
				<tr>
					<?php if (isset($this->items[0]->ordering)): ?>
						<th width="1%" class="nowrap center hidden-phone">
							<?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'a.`ordering`', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
						</th>
					<?php endif; ?>
					<th width="1%" class="hidden-phone">
						<input type="checkbox" name="checkall-toggle" value=""
							   title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)"/>
					</th>
					<?php if (isset($this->items[0]->state)): ?>
						<th width="1%" class="nowrap center">
								<?php echo JHtml::_('grid.sort', 'JSTATUS', 'a.`state`', $listDirn, $listOrder); ?>
</th>
					<?php endif; ?>

									<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_ORDERS_ORDERS_ID', 'a.`id`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_ORDERS_ORDERS_CUSTOMERS_NAME', 'a.`customers_name`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_ORDERS_ORDERS_CUSTOMERS_EMAIL', 'a.`customers_email`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_ORDERS_ORDERS_CUSTOMERS_PHONE', 'a.`customers_phone`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_ORDERS_ORDERS_UPLOAD_PDF', 'a.`upload_pdf`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_ORDERS_ORDERS_BOOK_SIZE', 'a.`book_size`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_ORDERS_ORDERS_COVER_TYPE', 'a.`cover_type`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_ORDERS_ORDERS_PAPER_TYPE', 'a.`paper_type`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_ORDERS_ORDERS_BOOK_TITLE', 'a.`book_title`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_ORDERS_ORDERS_AUTHOR_NAME', 'a.`author_name`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_ORDERS_ORDERS_CATEGORY_TITLE', 'a.`category_title`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_ORDERS_ORDERS_PAYMENT_STATUS', 'a.`payment_status`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_ORDERS_ORDERS_ORDER_STATUS', 'a.`order_status`', $listDirn, $listOrder); ?>
				</th>

					
				</tr>
				</thead>
				<tfoot>
				<tr>
					<td colspan="<?php echo isset($this->items[0]) ? count(get_object_vars($this->items[0])) : 10; ?>">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
				</tfoot>
				<tbody>
				<?php foreach ($this->items as $i => $item) :
					$ordering   = ($listOrder == 'a.ordering');
					$canCreate  = $user->authorise('core.create', 'com_orders');
					$canEdit    = $user->authorise('core.edit', 'com_orders');
					$canCheckin = $user->authorise('core.manage', 'com_orders');
					$canChange  = $user->authorise('core.edit.state', 'com_orders');
					?>
					<tr class="row<?php echo $i % 2; ?>">

						<?php if (isset($this->items[0]->ordering)) : ?>
							<td class="order nowrap center hidden-phone">
								<?php if ($canChange) :
									$disableClassName = '';
									$disabledLabel    = '';

									if (!$saveOrder) :
										$disabledLabel    = JText::_('JORDERINGDISABLED');
										$disableClassName = 'inactive tip-top';
									endif; ?>
									<span class="sortable-handler hasTooltip <?php echo $disableClassName ?>"
										  title="<?php echo $disabledLabel ?>">
							<i class="icon-menu"></i>
						</span>
									<input type="text" style="display:none" name="order[]" size="5"
										   value="<?php echo $item->ordering; ?>" class="width-20 text-area-order "/>
								<?php else : ?>
									<span class="sortable-handler inactive">
							<i class="icon-menu"></i>
						</span>
								<?php endif; ?>
							</td>
						<?php endif; ?>
						<td class="hidden-phone">
							<?php echo JHtml::_('grid.id', $i, $item->id); ?>
						</td>
						<?php if (isset($this->items[0]->state)): ?>
							<td class="center">
								<?php echo JHtml::_('jgrid.published', $item->state, $i, 'orders.', $canChange, 'cb'); ?>
</td>
						<?php endif; ?>

										<td>

					<?php echo $item->id; ?>
				</td>				<td>
				<?php if (isset($item->checked_out) && $item->checked_out && ($canEdit || $canChange)) : ?>
					<?php echo JHtml::_('jgrid.checkedout', $i, $item->uEditor, $item->checked_out_time, 'orders.', $canCheckin); ?>
				<?php endif; ?>
				<?php if ($canEdit) : ?>
					<a href="<?php echo JRoute::_('index.php?option=com_orders&task=order.edit&id='.(int) $item->id); ?>">
					<?php echo $this->escape($item->customers_name); ?></a>
				<?php else : ?>
					<?php echo $this->escape($item->customers_name); ?>
				<?php endif; ?>

				</td>				<td>

					<?php echo $item->customers_email; ?>
				</td>				<td>

					<?php echo $item->customers_phone; ?>
				</td>				<td>

					<?php
						if (!empty($item->upload_pdf)) :
							$upload_pdfArr = explode(',', $item->upload_pdf);
							foreach ($upload_pdfArr as $fileSingle) :
								if (!is_array($fileSingle)) :
									$uploadPath = 'uploads' .DIRECTORY_SEPARATOR . $fileSingle;
									echo '<a href="' . JRoute::_(JUri::root() . $uploadPath, false) . '" target="_blank" title="See the upload_pdf">' . $fileSingle . '</a> | ';
								endif;
							endforeach;
						else:
							echo $item->upload_pdf;
						endif; ?>
				</td>				<td>

					<?php echo $item->book_size; ?>
				</td>				<td>

					<?php echo $item->cover_type; ?>
				</td>				<td>

					<?php echo $item->paper_type; ?>
				</td>				<td>

					<?php echo $item->book_title; ?>
				</td>				<td>

					<?php echo $item->author_name; ?>
				</td>				<td>

					<?php echo $item->category_title; ?>
				</td>				<td>

					<?php echo $item->payment_status; ?>
				</td>				<td>

					<?php echo $item->order_status; ?>
				</td>

					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>

			<input type="hidden" name="task" value=""/>
			<input type="hidden" name="boxchecked" value="0"/>
			<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
			<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
			<?php echo JHtml::_('form.token'); ?>
		</div>
</form>        
