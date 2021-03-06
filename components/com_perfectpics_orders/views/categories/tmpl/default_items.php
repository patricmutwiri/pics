<?php
/**
 * @author		
 * @copyright	
 * @license		
 */

defined("_JEXEC") or die("Restricted access");

JHtml::_('bootstrap.tooltip');

$class = ' class="first"';

if (count($this->items[$this->parent->id]) > 0 && $this->maxLevelcat != 0) :
?>
	<?php foreach($this->items[$this->parent->id] as $id => $item) : ?>
		<?php
		if ($this->params->get('show_empty_categories_cat') || $item->numitems || count($item->getChildren())) :
			if (!isset($this->items[$this->parent->id][$id + 1]))
			{
				$class = ' class="last"';
			}
			?>
			<div <?php echo $class; ?> >
			<?php $class = ''; ?>
				<h3 class="page-header item-title">
				<a href="<?php echo JRoute::_(Perfectpics_ordersHelperRoute::getCategoryRoute($item->id));?>">
					<?php echo $this->escape($item->title); ?></a>
					<?php if ($this->params->get('show_cat_items_cat') == 1) :?>
						<span class="badge badge-info tip hasTooltip" title="<?php echo JHtml::tooltipText('COM_PERFECTPICS_ORDERS_NUM_ITEMS'); ?>">
							<?php echo $item->numitems; ?>
						</span>
					<?php endif; ?>
					<?php if (count($item->getChildren()) > 0) : ?>
						<a href="#category-<?php echo $item->id;?>" data-toggle="collapse" data-toggle="button" class="btn btn-mini pull-right"><span class="icon-plus"></span></a>
					<?php endif;?>
				</h3>
				<?php if ($this->params->get('show_subcat_desc_cat') == 1) :?>
					<?php if ($item->description) : ?>
						<div class="category-desc">
							<?php echo JHtml::_('content.prepare', $item->description, '', 'com_perfectpics_orders.categories'); ?>
						</div>
					<?php endif; ?>
				<?php endif; ?>

				<?php if (count($item->getChildren()) > 0) :?>
					<div class="collapse fade" id="category-<?php echo $item->id;?>">
					<?php
					$this->items[$item->id] = $item->getChildren();
					$this->parent = $item;
					$this->maxLevelcat--;
					echo $this->loadTemplate('items');
					$this->parent = $item->getParent();
					$this->maxLevelcat++;
					?>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	<?php endforeach; ?>
<?php endif; ?>
