<?php
/**
 * @author		
 * @copyright	
 * @license		
 */

defined("_JEXEC") or die("Restricted access");
?>

<h2>
	<?php echo JText::_('COM_PRODUCTS_PAPERS_VIEW_PAPER_TITLE'); ?>: <i><?php echo $this->item->title; ?></i>
	<span class="pull-right" style="font-weight:300; font-size:15px;">[<a href="<?php echo JRoute::_('index.php?option=com_products&task=paper.edit&id=' . (int) $this->item->id); ?>"><?php echo JText::_('JACTION_EDIT') ?></a>]</span>
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
				<td>Categories</td>
				<td><?php echo $this->escape($this->item->categories); ?></td>
			</tr>
			<tr>
				<td>Product_highlight</td>
				<td><?php echo $this->escape($this->item->product_highlight); ?></td>
			</tr>
			<tr>
				<td>Paper_image</td>
				<td><?php echo $this->escape($this->item->paper_image); ?></td>
			</tr>
			<tr>
				<td>Paper_price</td>
				<td><?php echo $this->escape($this->item->paper_price); ?></td>
			</tr>
		<tr>
			<td>ID</td>
			<td><?php echo $this->escape($this->item->id); ?></td>
		</tr>
	</tbody>
</table>
<p><a href="index.php?option=com_products&view=papers"><?php echo JText::_('JPREVIOUS'); ?></a></p>