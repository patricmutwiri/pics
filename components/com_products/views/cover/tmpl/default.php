<?php
/**
 * @author		
 * @copyright	
 * @license		
 */

defined("_JEXEC") or die("Restricted access");
?>

<h2>
	<?php echo JText::_('COM_PRODUCTS_COVERS_VIEW_COVER_TITLE'); ?>: <i><?php echo $this->item->cover_name; ?></i>
	<span class="pull-right" style="font-weight:300; font-size:15px;">[<a href="<?php echo JRoute::_('index.php?option=com_products&task=cover.edit&id=' . (int) $this->item->id); ?>"><?php echo JText::_('JACTION_EDIT') ?></a>]</span>
</h2>

<table class="table table-striped">
	<tbody>
			<tr>
				<td>Cover_name</td>
				<td><?php echo $this->escape($this->item->cover_name); ?></td>
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
				<td>Cover_image</td>
				<td><?php echo $this->escape($this->item->cover_image); ?></td>
			</tr>
			<tr>
				<td>Cover_type</td>
				<td><?php echo $this->escape($this->item->cover_type); ?></td>
			</tr>
		<tr>
			<td>ID</td>
			<td><?php echo $this->escape($this->item->id); ?></td>
		</tr>
	</tbody>
</table>
<p><a href="index.php?option=com_products&view=covers"><?php echo JText::_('JPREVIOUS'); ?></a></p>