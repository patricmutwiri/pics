<?php
/**
 * @author		
 * @copyright	
 * @license		
 */

defined("_JEXEC") or die("Restricted access");
?>

<h2>
	<?php echo JText::_('COM_PRODUCTS_SIZES_VIEW_SIZE_TITLE'); ?>: <i><?php echo $this->item->categories; ?></i>
	<span class="pull-right" style="font-weight:300; font-size:15px;">[<a href="<?php echo JRoute::_('index.php?option=com_products&task=size.edit&id=' . (int) $this->item->id); ?>"><?php echo JText::_('JACTION_EDIT') ?></a>]</span>
</h2>

<table class="table table-striped">
	<tbody>
			<tr>
				<td>Categories</td>
				<td><?php echo $this->escape($this->item->categories); ?></td>
			</tr>
			<tr>
				<td>Size_title</td>
				<td><?php echo $this->escape($this->item->size_title); ?></td>
			</tr>
			<tr>
				<td>Size_dimensions</td>
				<td><?php echo $this->escape($this->item->size_dimensions); ?></td>
			</tr>
			<tr>
				<td>No_pages</td>
				<td><?php echo $this->escape($this->item->no_pages); ?></td>
			</tr>
			<tr>
				<td>Price</td>
				<td><?php echo $this->escape($this->item->price); ?></td>
			</tr>
			<tr>
				<td>Page_range</td>
				<td><?php echo $this->escape($this->item->page_range); ?></td>
			</tr>
			<tr>
				<td>Size_image</td>
				<td><?php echo $this->escape($this->item->size_image); ?></td>
			</tr>
		<tr>
			<td>ID</td>
			<td><?php echo $this->escape($this->item->id); ?></td>
		</tr>
	</tbody>
</table>
<p><a href="index.php?option=com_products&view=sizes"><?php echo JText::_('JPREVIOUS'); ?></a></p>