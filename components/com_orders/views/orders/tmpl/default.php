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

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$user       = JFactory::getUser();
$userId     = $user->get('id');
$userName   = $user->get('username');
$listOrder  = $this->state->get('list.ordering');
$listDirn   = $this->state->get('list.direction');
$canCreate  = $user->authorise('core.create', 'com_orders') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'orderform.xml');
$canEdit    = $user->authorise('core.edit', 'com_orders') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'orderform.xml');
$canCheckin = $user->authorise('core.manage', 'com_orders');
$canChange  = $user->authorise('core.edit.state', 'com_orders');
$canDelete  = $user->authorise('core.delete', 'com_orders');
?>
<div class="grid-construct-x">
<h1>PDF-to-Book Orders</h1>
<hr />
<?php if (!empty($this->items)) {?>
<form action="<?php echo JRoute::_('index.php?option=com_orders&view=orders'); ?>" method="post"
      name="adminForm" id="adminForm">

	<?php //echo JLayoutHelper::render('default_filter', array('view' => $this), dirname(__FILE__)); ?>
	<table class="table table-striped table-condensed" id="orderList">
		<thead>
		<tr>
			<?php if (isset($this->items[0]->state)): ?>
				<th class="hidden" width="5%">
	        <?php echo JHtml::_('grid.sort', 'JPUBLISHED', 'a.state', $listDirn, $listOrder); ?>
        </th>
			<?php endif; ?>

							<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_ORDERS_ORDERS_ID', 'a.id', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_ORDERS_ORDERS_CUSTOMERS_NAME', 'a.customers_name', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_ORDERS_ORDERS_CUSTOMERS_EMAIL', 'a.customers_email', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_ORDERS_ORDERS_CUSTOMERS_PHONE', 'a.customers_phone', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_ORDERS_ORDERS_UPLOAD_PDF', 'a.upload_pdf', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_ORDERS_ORDERS_BOOK_SIZE', 'a.book_size', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_ORDERS_ORDERS_COVER_TYPE', 'a.cover_type', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_ORDERS_ORDERS_PAPER_TYPE', 'a.paper_type', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_ORDERS_ORDERS_BOOK_TITLE', 'a.book_title', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_ORDERS_ORDERS_AUTHOR_NAME', 'a.author_name', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_ORDERS_ORDERS_CATEGORY_TITLE', 'a.category_title', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_ORDERS_ORDERS_PAYMENT_STATUS', 'a.payment_status', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_ORDERS_ORDERS_ORDER_STATUS', 'a.order_status', $listDirn, $listOrder); ?>
				</th>
				<th class='hidden'>
				<?php echo JHtml::_('grid.sort',  'User ID', 'a.created_by', $listDirn, $listOrder); ?>
				</th>
							<?php if ($canEdit || $canDelete): ?>
					<th class="center text-center">
				<?php echo JText::_('COM_ORDERS_ORDERS_ACTIONS'); ?>
				</th>
				<?php endif; ?>
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
		<?php foreach ($this->items as $i => $item) : ?>
			<?php $canEdit = $user->authorise('core.edit', 'com_orders'); ?>

							<?php if (!$canEdit && $user->authorise('core.edit.own', 'com_orders')): ?>
					<?php $canEdit = JFactory::getUser()->id == $item->created_by; ?>
				<?php endif; ?>

			<tr class="row<?php echo $i % 2; ?>">

				<?php if (isset($this->items[0]->state)) : ?>
					<?php $class = ($canChange) ? 'active' : 'disabled'; ?>
					<td class="center hidden">
	<a class="btn btn-micro <?php echo $class; ?>" href="<?php echo ($canChange) ? JRoute::_('index.php?option=com_orders&task=order.publish&id=' . $item->id . '&state=' . (($item->state + 1) % 2), false, 2) : '#'; ?>">
	<?php if ($item->state == 1): ?>
		<i class="icon-publish"></i>
	<?php else: ?>
		<i class="icon-unpublish"></i>
	<?php endif; ?>
	</a>
</td>
				<?php endif; ?>

								<td>

					<?php echo $item->id; ?>
				</td>
				<td>
				<?php if (isset($item->checked_out) && $item->checked_out) : ?>
					<?php echo JHtml::_('jgrid.checkedout', $i, $item->uEditor, $item->checked_out_time, 'orders.', $canCheckin); ?>
				<?php endif; ?>
				<a href="<?php echo JRoute::_('index.php?option=com_orders&view=order&id='.(int) $item->id); ?>">
				<?php echo $this->escape($item->customers_name); ?></a>
				</td>
				<td><?php echo $item->customers_email; ?></td>
				<td><?php echo $item->customers_phone; ?></td>
				<td>
					<?php
						if (!empty($item->upload_pdf)) :
							$upload_pdfArr = (array) explode(',', $item->upload_pdf);
							foreach ($upload_pdfArr as $singleFile) :
								if (!is_array($singleFile)) :
									$uploadPath = 'uploads' . DIRECTORY_SEPARATOR . $singleFile;
									echo '<a href="' . JRoute::_(JUri::root() . $uploadPath, false) . '" class="download_pdf" target="_blank" title="View the PDF">Download</a> ';
								endif;
							endforeach;
						else:
							echo $item->upload_pdf;
						endif; ?>
          </td>
				<td><?php echo $item->book_size; ?></td>
				<td><?php echo $item->cover_type; ?></td>
				<td><?php echo $item->paper_type; ?></td>
				<td><?php echo $item->book_title; ?></td>
				<td><?php echo $item->author_name; ?></td>
				<td><?php echo $item->category_title; ?></td>
				<td><?php echo $item->payment_status; ?></td>
				<td><?php echo $item->order_status; ?></td>

								<?php if ($canEdit || $canDelete): ?>
					<td class="center">
						<?php if ($canEdit): ?>
							<a href="<?php echo JRoute::_('index.php?option=com_orders&task=orderform.edit&id=' . $item->id, false, 2); ?>" class="btn btn-mini" type="button"><i class="icon-edit" ></i></a>
						<?php endif; ?>
						<?php if ($canDelete): ?>
							<a href="<?php echo JRoute::_('index.php?option=com_orders&task=orderform.remove&id=' . $item->id, false, 2); ?>" class="btn btn-mini delete-button" type="button"><i class="icon-trash" ></i></a>
						<?php endif; ?>
					</td>
				<?php endif; ?>

			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>

	<?php if ($canCreate) : ?>
		<a href="<?php echo JRoute::_('index.php?option=com_orders&task=orderform.edit&id=0', false, 2); ?>"
		   class="btn btn-success btn-lg"><i
				class="icon-plus"></i>
			<?php echo JText::_('COM_ORDERS_ADD_ITEM'); ?></a>
	<?php endif; ?>

	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
	<?php echo JHtml::_('form.token'); ?>
</form>

<?php if($canDelete) : ?>
<script type="text/javascript">

	jQuery(document).ready(function () {
		jQuery('.delete-button').click(deleteItem);
	});

	function deleteItem() {

		if (!confirm("<?php echo JText::_('COM_ORDERS_DELETE_MESSAGE'); ?>")) {
			return false;
		}
	}
</script>
<?php endif; ?>
</div>
<?php } else {
  if ($canCreate) : ?>
<p>
  Dear <?php echo ucwords($userName);?>, you do not have existing PDF-book orders. Please upload a PDF to view your orders
</p>
		<a href="<?php echo JRoute::_('index.php?option=com_orders&task=orderform.edit&id=0', false, 2); ?>" class="btn btn-success btn-lg">
      <i class="icon-plus"></i>
			<?php echo JText::_('COM_ORDERS_ADD_ITEM'); ?>
    </a>
	<?php endif;
}?>
