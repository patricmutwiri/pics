<?php
/**
 * @author		
 * @copyright	
 * @license		
 */

defined("_JEXEC") or die("Restricted access");

$params = $this->item->params;
$blockPosition = $params->get('info_block_position', 0);
$position = $params->get("position");
?>
<dl class="article-info  muted">
<?php if ($position == 'above' && ($blockPosition == 0 || $blockPosition == 2) || $position == 'below' && ($blockPosition == 1)) : ?>
	<dt class="article-info-term">
		<?php // TODO: implement info_block_show_title param to hide article info title ?>
		<?php if ($params->get('info_block_show_title', 1)) : ?>
			<?php echo JText::_('COM_PRODUCTS_LOGO_INFO'); ?>
		<?php endif; ?>
	</dt>
	<?php if ($params->get('show_author', 1) && !empty($this->item->author )) : ?>
	<dd class="createdby" itemprop="author" itemscope itemtype="http://schema.org/Person">
		<?php $author = $this->item->author; ?>
		<?php $author = '<span itemprop="name">' . $author . '</span>'; ?>
	</dd>
	<?php endif; ?>
	<?php if ($params->get('show_parent_category', 1) && !empty($this->item->parent_slug)) : ?>
	<dd class="parent-category-name">
		<?php $title = $this->escape($this->item->parent_title); ?>
		<?php if ($params->get('link_parent_category', 1) && !empty($this->item->parent_slug)) : ?>
			<?php $url = '<a href="' . JRoute::_(ProductsHelperRoute::getCategoryRoute($this->item->parent_slug)) . '" itemprop="genre">' . $title . '</a>'; ?>
			<?php echo JText::_('COM_PRODUCTS_PARENT') . ": " . $url; ?>
		<?php else : ?>
			<?php echo JText::_('COM_PRODUCTS_PARENT') . ": " . '<span itemprop="genre">' . $title . '</span>'; ?>
		<?php endif; ?>
	</dd>
	<?php endif; ?><?php /*
	<?php if ($params->get('show_category', 1)) : ?>
	<dd class="category-name">
		<?php $title = $this->escape($this->item->category_title); ?>
		<?php if ($params->get('link_category', 1) && $this->item->catslug) : ?>
			<?php $url = '<a href="' . JRoute::_(ProductsHelperRoute::getCategoryRoute($this->item->catslug)) . '" itemprop="genre">' . $title . '</a>'; ?>
			<?php echo JText::_('COM_PRODUCTS_CATEGORY') . ": " . $url; ?>
		<?php else : ?>
			<?php echo JText::_('COM_PRODUCTS_CATEGORY') . ": " . '<span itemprop="genre">' . $title . '</span>'; ?>
		<?php endif; ?>
	</dd>
	<?php endif; ?> */ ?>
	<?php if ($params->get('show_publish_date', 1)) : ?>
	<dd class="published">
		<span class="icon-calendar"></span>
		<time datetime="<?php echo JHtml::_('date', $this->item->publish_up, 'c'); ?>" itemprop="datePublished">
			<?php echo JText::_('COM_PRODUCTS_PUBLISHED_DATE_ON') . ": " . JHtml::_('date', $this->item->publish_up, JText::_('DATE_FORMAT_LC3')); ?>
		</time>
	</dd>
	<?php endif; ?>
<?php endif; ?>
<?php if ($position == 'above' && ($blockPosition == 0) || $position == 'below' && ($blockPosition == 1 || $blockPosition == 2)) : ?>
	<?php if ($params->get('show_create_date', 1)) : ?>
	<dd class="create">
		<span class="icon-calendar"></span>
		<time datetime="<?php echo JHtml::_('date', $this->item->created, 'c'); ?>" itemprop="dateCreated">
			<?php echo JText::_('COM_PRODUCTS_CREATED_DATE_ON') . ": " . JHtml::_('date', $this->item->created, JText::_('DATE_FORMAT_LC3')); ?>
		</time>
	</dd>
	<?php endif; ?>
	<?php if ($params->get('show_modify_date', 1)) : ?>
	<dd class="modified">
		<span class="icon-calendar"></span>
		<time datetime="<?php echo JHtml::_('date', $this->item->modified, 'c'); ?>" itemprop="dateModified">
			<?php echo JText::_('COM_PRODUCTS_LAST_UPDATED') . ": " . JHtml::_('date', $this->item->modified, JText::_('DATE_FORMAT_LC3')); ?>
		</time>
	</dd>
	<?php endif; ?>
<?php endif; ?>
</dl>