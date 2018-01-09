<?php
/**
 * @author		
 * @copyright	
 * @license		
 */

defined("_JEXEC") or die("Restricted access");
?>

<h2>
	<?php echo JText::_('COM_PERFECTPICS_ORDERS_PERFECTPICS_ORDERS_VIEW_PERFECTPICS_ORDERS_TITLE'); ?>: <i><?php echo $this->item->customers_name; ?></i>
	<span class="pull-right" style="font-weight:300; font-size:15px;">[<a href="<?php echo JRoute::_('index.php?option=com_perfectpics_orders&task=perfectpics_orders.edit&id=' . (int) $this->item->id); ?>"><?php echo JText::_('JACTION_EDIT') ?></a>]</span>
</h2>

<table class="table table-striped">
	<tbody>
			<tr>
				<td>Customers_name</td>
				<td><?php echo $this->escape($this->item->customers_name); ?></td>
			</tr>
			<tr>
				<td>Customers_email</td>
				<td><?php echo $this->escape($this->item->customers_email); ?></td>
			</tr>
			<tr>
				<td>Customers_phone</td>
				<td><?php echo $this->escape($this->item->customers_phone); ?></td>
			</tr>
			<tr>
				<td>Upload_pdf</td>
				<td><?php echo $this->escape($this->item->upload_pdf); ?></td>
			</tr>
			<tr>
				<td>Book_size</td>
				<td><?php echo $this->escape($this->item->book_size); ?></td>
			</tr>
			<tr>
				<td>Cover_type</td>
				<td><?php echo $this->escape($this->item->cover_type); ?></td>
			</tr>
			<tr>
				<td>Paper_type</td>
				<td><?php echo $this->escape($this->item->paper_type); ?></td>
			</tr>
			<tr>
				<td>Book_title</td>
				<td><?php echo $this->escape($this->item->book_title); ?></td>
			</tr>
			<tr>
				<td>Author_name</td>
				<td><?php echo $this->escape($this->item->author_name); ?></td>
			</tr>
		<tr>
			<td>ID</td>
			<td><?php echo $this->escape($this->item->id); ?></td>
		</tr>
	</tbody>
</table>
<p><a href="index.php?option=com_perfectpics_orders&view=perfectpics_orderss"><?php echo JText::_('JPREVIOUS'); ?></a></p>