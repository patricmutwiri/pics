<?php
/**
 * @author		
 * @copyright	
 * @license		
 */

defined("_JEXEC") or die("Restricted access");
?>

<h2>
	<?php echo JText::_('COM_PRODUCTS_LOGOS_VIEW_LOGO_TITLE'); ?>: <i><?php echo $this->item->title; ?></i>
	<span class="pull-right" style="font-weight:300; font-size:15px;">[<a href="<?php echo JRoute::_('index.php?option=com_products&task=logo.edit&id=' . (int) $this->item->id); ?>"><?php echo JText::_('JACTION_EDIT') ?></a>]</span>
</h2>

<table class="table table-striped">
	<tbody>
			<tr>
				<td>Title</td>
				<td><?php echo $this->escape($this->item->title); ?></td>
			</tr>
			<tr>
				<td>Details</td>
				<td><?php echo $this->escape($this->item->details); ?></td>
			</tr>
			<tr>
				<td>Logo_image</td>
				<td><?php echo $this->escape($this->item->logo_image); ?></td>
			</tr>
		<tr>
			<td>ID</td>
			<td><?php echo $this->escape($this->item->id); ?></td>
		</tr>
	</tbody>
</table>
<p><a href="index.php?option=com_products&view=logos"><?php echo JText::_('JPREVIOUS'); ?></a></p>