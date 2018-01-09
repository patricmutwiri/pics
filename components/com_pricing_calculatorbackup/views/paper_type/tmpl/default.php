<?php
/**
 * @author		
 * @copyright	
 * @license		
 */

defined("_JEXEC") or die("Restricted access");
?>

<h2>
	<?php echo JText::_('COM_PRICING_CALCULATOR_PAPER_TYPES_VIEW_PAPER_TYPE_TITLE'); ?>: <i><?php echo $this->item->paper_name; ?></i>
	<span class="pull-right" style="font-weight:300; font-size:15px;">[<a href="<?php echo JRoute::_('index.php?option=com_pricing_calculator&task=paper_type.edit&id=' . (int) $this->item->id); ?>"><?php echo JText::_('JACTION_EDIT') ?></a>]</span>
</h2>

<table class="table table-striped">
	<tbody>
			<tr>
				<td>Paper_name</td>
				<td><?php echo $this->escape($this->item->paper_name); ?></td>
			</tr>
			<tr>
				<td>Paper_description</td>
				<td><?php echo $this->escape($this->item->paper_description); ?></td>
			</tr>
		<tr>
			<td>ID</td>
			<td><?php echo $this->escape($this->item->id); ?></td>
		</tr>
	</tbody>
</table>
<p><a href="index.php?option=com_pricing_calculator&view=paper_types"><?php echo JText::_('JPREVIOUS'); ?></a></p>