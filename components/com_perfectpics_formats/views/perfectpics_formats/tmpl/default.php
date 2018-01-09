<?php
/**
 * @author		
 * @copyright	
 * @license		
 */

defined("_JEXEC") or die("Restricted access");
?>

<h2>
	<?php echo JText::_('COM_PERFECTPICS_FORMATS_PERFECTPICS_FORMAT_VIEW_PERFECTPICS_FORMATS_TITLE'); ?>: <i><?php echo $this->item->formats_icon; ?></i>
	<span class="pull-right" style="font-weight:300; font-size:15px;">[<a href="<?php echo JRoute::_('index.php?option=com_perfectpics_formats&task=perfectpics_formats.edit&id=' . (int) $this->item->id); ?>"><?php echo JText::_('JACTION_EDIT') ?></a>]</span>
</h2>

<table class="table table-striped">
	<tbody>
			<tr>
				<td>Formats_icon</td>
				<td><?php echo $this->escape($this->item->formats_icon); ?></td>
			</tr>
			<tr>
				<td>Format_title</td>
				<td><?php echo $this->escape($this->item->format_title); ?></td>
			</tr>
			<tr>
				<td>Format_size</td>
				<td><?php echo $this->escape($this->item->format_size); ?></td>
			</tr>
			<tr>
				<td>Price_from</td>
				<td><?php echo $this->escape($this->item->price_from); ?></td>
			</tr>
			<tr>
				<td>Pages</td>
				<td><?php echo $this->escape($this->item->pages); ?></td>
			</tr>
			<tr>
				<td>Format_price</td>
				<td><?php echo $this->escape($this->item->format_price); ?></td>
			</tr>
		<tr>
			<td>ID</td>
			<td><?php echo $this->escape($this->item->id); ?></td>
		</tr>
	</tbody>
</table>
<p><a href="index.php?option=com_perfectpics_formats&view=perfectpics_formatss"><?php echo JText::_('JPREVIOUS'); ?></a></p>