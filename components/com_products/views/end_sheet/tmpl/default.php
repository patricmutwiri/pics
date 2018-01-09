<?php
/**
 * @author		
 * @copyright	
 * @license		
 */

defined("_JEXEC") or die("Restricted access");
?>

<h2>
	<?php echo JText::_('COM_PRODUCTS_END_SHEETS_VIEW_END_SHEET_TITLE'); ?>: <i><?php echo $this->item->title; ?></i>
	<span class="pull-right" style="font-weight:300; font-size:15px;">[<a href="<?php echo JRoute::_('index.php?option=com_products&task=end_sheet.edit&id=' . (int) $this->item->id); ?>"><?php echo JText::_('JACTION_EDIT') ?></a>]</span>
</h2>

<table class="table table-striped">
	<tbody>
			<tr>
				<td>Title</td>
				<td><?php echo $this->escape($this->item->title); ?></td>
			</tr>
			<tr>
				<td>Description</td>
				<td><?php echo $this->escape($this->item->description); ?></td>
			</tr>
			<tr>
				<td>Price</td>
				<td><?php echo $this->escape($this->item->price); ?></td>
			</tr>
			<tr>
				<td>Product_highlight</td>
				<td><?php echo $this->escape($this->item->product_highlight); ?></td>
			</tr>
			<tr>
				<td>Categories</td>
				<td><?php echo $this->escape($this->item->categories); ?></td>
			</tr>
			<tr>
				<td>End_sheet_image</td>
				<td><?php echo $this->escape($this->item->end_sheet_image); ?></td>
			</tr>
		<tr>
			<td>ID</td>
			<td><?php echo $this->escape($this->item->id); ?></td>
		</tr>
	</tbody>
</table>
<p><a href="index.php?option=com_products&view=end_sheets"><?php echo JText::_('JPREVIOUS'); ?></a></p>