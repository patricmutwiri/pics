<?php
/**
 * @author
 * @copyright
 * @license
 */

defined("_JEXEC") or die("Restricted access");
?>
<div class="grid-construct-x">
<h2>
	<?php echo JText::_('COM_PERFECT_PICS_EXPERTS_EXPERT_VIEW_EXPERTS_TITLE'); ?>: <i><?php echo $this->item->expert_name; ?></i>
	<span class="pull-right" style="font-weight:300; font-size:15px;">[<a href="<?php echo JRoute::_('index.php?option=com_perfect_pics_experts&task=experts.edit&id=' . (int) $this->item->id); ?>"><?php echo JText::_('JACTION_EDIT') ?></a>]</span>
</h2>

<table class="table table-striped">
	<tbody>
			<tr>
				<td>Expert_name</td>
				<td><?php echo $this->escape($this->item->expert_name); ?></td>
			</tr>
			<tr>
				<td>Experts_image</td>
				<td><?php echo $this->escape($this->item->experts_image); ?></td>
			</tr>
			<tr>
				<td>Experts_location</td>
				<td><?php echo $this->escape($this->item->experts_location); ?></td>
			</tr>
			<tr>
				<td>Experts_desc</td>
				<td><?php echo $this->escape($this->item->experts_desc); ?></td>
			</tr>
			<tr>
				<td>Experts_email</td>
				<td><?php echo $this->escape($this->item->experts_email); ?></td>
			</tr>
			<tr>
				<td>Experts_phone</td>
				<td><?php echo $this->escape($this->item->experts_phone); ?></td>
			</tr>
		<tr>
			<td>ID</td>
			<td><?php echo $this->escape($this->item->id); ?></td>
		</tr>
	</tbody>
</table>
<p><a href="index.php?option=com_perfect_pics_experts&view=expertss"><?php echo JText::_('JPREVIOUS'); ?></a></p>
</div>
