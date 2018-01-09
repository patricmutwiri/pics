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

$canEdit = JFactory::getUser()->authorise('core.edit', 'com_orders.' . $this->item->id);

if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_orders' . $this->item->id))
{
	$canEdit = JFactory::getUser()->id == $this->item->created_by;
}
?>

<div class="grid-construct-x item_fields">

	<table class="table">


		<tr>
			<th><?php echo JText::_('COM_ORDERS_FORM_LBL_ORDER_STATE'); ?></th>
			<td>
			<i class="icon-<?php echo ($this->item->state == 1) ? 'publish' : 'unpublish'; ?>"></i></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_ORDERS_FORM_LBL_ORDER_CREATED_BY'); ?></th>
			<td><?php echo $this->item->created_by_name; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_ORDERS_FORM_LBL_ORDER_MODIFIED_BY'); ?></th>
			<td><?php echo $this->item->modified_by_name; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_ORDERS_FORM_LBL_ORDER_CUSTOMERS_NAME'); ?></th>
			<td><?php echo $this->item->customers_name; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_ORDERS_FORM_LBL_ORDER_CUSTOMERS_EMAIL'); ?></th>
			<td><?php echo $this->item->customers_email; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_ORDERS_FORM_LBL_ORDER_CUSTOMERS_PHONE'); ?></th>
			<td><?php echo $this->item->customers_phone; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_ORDERS_FORM_LBL_ORDER_UPLOAD_PDF'); ?></th>
			<td>
			<?php
			foreach ((array) $this->item->upload_pdf as $singleFile) :
				if (!is_array($singleFile)) :
					$uploadPath = 'uploads' . DIRECTORY_SEPARATOR . $singleFile;
					 echo '<a href="' . JRoute::_(JUri::root() . $uploadPath, false) . '" target="_blank">' . $singleFile . '</a> ';
				endif;
			endforeach;
		?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_ORDERS_FORM_LBL_ORDER_BOOK_SIZE'); ?></th>
			<td><?php echo $this->item->book_size; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_ORDERS_FORM_LBL_ORDER_COVER_TYPE'); ?></th>
			<td><?php echo $this->item->cover_type; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_ORDERS_FORM_LBL_ORDER_PAPER_TYPE'); ?></th>
			<td><?php echo $this->item->paper_type; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_ORDERS_FORM_LBL_ORDER_BOOK_TITLE'); ?></th>
			<td><?php echo $this->item->book_title; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_ORDERS_FORM_LBL_ORDER_AUTHOR_NAME'); ?></th>
			<td><?php echo $this->item->author_name; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_ORDERS_FORM_LBL_ORDER_CATEGORY_TITLE'); ?></th>
			<td><?php echo $this->item->category_title; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_ORDERS_FORM_LBL_ORDER_PAYMENT_STATUS'); ?></th>
			<td><?php echo $this->item->payment_status; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_ORDERS_FORM_LBL_ORDER_ORDER_STATUS'); ?></th>
			<td><?php echo $this->item->order_status; ?></td>
		</tr>

	</table>

</div>

<div class="grid-construct-x">

<?php if($canEdit && $this->item->checked_out == 0): ?>

	<a class="btn" href="<?php echo JRoute::_('index.php?option=com_orders&task=order.edit&id='.$this->item->id); ?>"><?php echo JText::_("COM_ORDERS_EDIT_ITEM"); ?></a>

<?php endif; ?>

<?php if (JFactory::getUser()->authorise('core.delete','com_orders.order.'.$this->item->id)) : ?>

	<a class="btn" href="<?php echo JRoute::_('index.php?option=com_orders&task=order.remove&id=' . $this->item->id, false, 2); ?>"><?php echo JText::_("COM_ORDERS_DELETE_ITEM"); ?></a>

<?php endif; ?>

</div>
