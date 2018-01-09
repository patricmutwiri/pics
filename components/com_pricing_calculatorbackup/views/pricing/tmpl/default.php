<?php
/**
 * @author		
 * @copyright	
 * @license		
 */

defined("_JEXEC") or die("Restricted access");
?>

<h2>
	<?php echo JText::_('COM_PRICING_CALCULATOR_PRICING_VIEW_PRICING_TITLE'); ?>: <i><?php echo $this->item->item_name; ?></i>
	<span class="pull-right" style="font-weight:300; font-size:15px;">[<a href="<?php echo JRoute::_('index.php?option=com_pricing_calculator&task=pricing.edit&id=' . (int) $this->item->id); ?>"><?php echo JText::_('JACTION_EDIT') ?></a>]</span>
</h2>

<table class="table table-striped">
	<tbody>
			<tr>
				<td>Item_name</td>
				<td><?php echo $this->escape($this->item->item_name); ?></td>
			</tr>
			<tr>
				<td>Item_image</td>
				<td><?php echo $this->escape($this->item->item_image); ?></td>
			</tr>
			<tr>
				<td>Item_dimensions</td>
				<td><?php echo $this->escape($this->item->item_dimensions); ?></td>
			</tr>
			<tr>
				<td>Size</td>
				<td><?php echo $this->escape($this->item->size); ?></td>
			</tr>
			<tr>
				<td>Cover</td>
				<td><?php echo $this->escape($this->item->cover); ?></td>
			</tr>
			<tr>
				<td>Paper</td>
				<td><?php echo $this->escape($this->item->paper); ?></td>
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
			<td>ID</td>
			<td><?php echo $this->escape($this->item->id); ?></td>
		</tr>
	</tbody>
</table>
<p><a href="index.php?option=com_pricing_calculator&view=pricings"><?php echo JText::_('JPREVIOUS'); ?></a></p>