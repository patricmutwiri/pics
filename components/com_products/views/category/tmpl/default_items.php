<?php
/**
 * @author
 * @copyright
 * @license
 */

defined("_JEXEC") or die("Restricted access");

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.framework');

// Create some shortcuts.
$params		= &$this->item->params;
$n			= count($this->items);
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
?>

<?php /*if (empty($this->items)) : ?>
	<p><?php echo JText::_('COM_PRODUCTS_NO_LOGOS'); ?></p>
<?php else : ?>

<form action="<?php echo htmlspecialchars(JUri::getInstance()->toString()); ?>" method="post" name="adminForm" id="adminForm" class="form-inline">
	<?php if ($this->params->get('show_headings') || $this->params->get('filter_field') != 'hide' || $this->params->get('show_pagination_limit')) :?>
	<fieldset class="filters btn-toolbar clearfix">
		<?php if ($this->params->get('filter_field') != 'hide') :?>
			<div class="btn-group">
				<label class="filter-search-lbl element-invisible" for="filter-search">
					<?php echo JText::_('COM_PRODUCTS_'.$this->params->get('filter_field').'_FILTER_LABEL').'&#160;'; ?>
				</label>
				<input type="text" name="filter-search" id="filter-search" value="<?php echo $this->escape($this->state->get('list.filter')); ?>" class="inputbox" onchange="document.adminForm.submit();" title="<?php echo JText::_('COM_PRODUCTS_FILTER_SEARCH_DESC'); ?>" placeholder="<?php echo JText::_('COM_PRODUCTS_'.$this->params->get('filter_field').'_FILTER_LABEL'); ?>" />
			</div>
		<?php endif; ?>
		<?php if ($this->params->get('show_pagination_limit')) : ?>
			<div class="btn-group pull-right">
				<label for="limit" class="element-invisible">
					<?php echo JText::_('JGLOBAL_DISPLAY_NUM'); ?>
				</label>
				<?php echo $this->pagination->getLimitBox(); ?>
			</div>
		<?php endif; ?>

		<input type="hidden" name="filter_order" value="" />
		<input type="hidden" name="filter_order_Dir" value="" />
		<input type="hidden" name="limitstart" value="" />
		<input type="hidden" name="task" value="" />
	</fieldset>
	<?php endif; ?>

	<table class="category table table-striped table-bordered table-hover">
		<?php if ($this->params->get('show_headings')) : ?>
		<thead>
			<tr>
				<th id="categorylist_header_title">
					<?php echo JHtml::_('grid.sort', 'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder); ?>
				</th>
				<?php if ($this->params->get('list_show_author', 1)) : ?>
					<th id="categorylist_header_author">
						<?php echo JHtml::_('grid.sort', 'JAUTHOR', 'author', $listDirn, $listOrder); ?>
					</th>
				<?php endif; ?>
				<?php if ($this->user->authorise('core.edit') || $this->user->authorise('core.edit.own')) : ?>
					<th id="categorylist_header_edit"><?php echo JText::_('COM_PRODUCTS_EDIT_ITEM'); ?></th>
				<?php endif; ?>
			</tr>
		</thead>
		<?php endif; ?>
		<tbody>
			<?php
			foreach ($this->items as $i => $item) :
			$canEdit	= $this->user->authorise('core.edit',       'com_products'.'.logo.'.$item->id);
			$canEditOwn	= $this->user->authorise('core.edit.own',   'com_products'.'.logo.'.$item->id) && $item->created_by == $this->user->id;
			?>
				<?php if (isset($this->items[$i]->published) && $this->items[$i]->published == 0) : ?>
				 <tr class="system-unpublished cat-list-row<?php echo $i % 2; ?>">
				<?php else: ?>
				<tr class="cat-list-row<?php echo $i % 2; ?>" >
				<?php endif; ?>
					<td headers="categorylist_header_title" class="list-title">
						<?php if (isset($item->access) && in_array($item->access, $this->user->getAuthorisedViewLevels())) : ?>
							<a href="<?php echo JRoute::_("index.php?option=com_products&view=logo&id=" . $item->id); ?>">
								<?php echo $this->escape($item->title); ?>
							</a>
						<?php else: ?>
							<?php echo $this->escape($item->title); ?>
						<?php endif; ?>
						<?php if ($item->published == 0) : ?>
							<span class="list-published label label-warning">
								<?php echo JText::_('JUNPUBLISHED'); ?>
							</span>
						<?php endif; ?>
						<?php if (strtotime($item->publish_up) > strtotime(JFactory::getDate())) : ?>
							<span class="list-published label label-warning">
								<?php echo JText::_('JNOTPUBLISHEDYET'); ?>
							</span>
						<?php endif; ?>
						<?php if ((strtotime($item->publish_down) < strtotime(JFactory::getDate())) && $item->publish_down != '0000-00-00 00:00:00') : ?>
							<span class="list-published label label-warning">
								<?php echo JText::_('JEXPIRED'); ?>
							</span>
						<?php endif; ?>
					</td>
					<?php if ($this->params->get('list_show_author', 1)) : ?>
					<td headers="categorylist_header_author" class="list-author">
						<?php if (!empty($item->author)) : ?>
							<?php $author = $item->author ?>
							<?php echo $author; ?>
						<?php endif; ?>
					</td>
					<?php endif; ?>
					<?php if ($this->user->authorise('core.edit') || $this->user->authorise('core.edit.own')) : ?>
					<td headers="categorylist_header_edit" class="list-edit">
						<?php if ($canEdit || $canEditOwn) : ?>
							<a href="<?php echo JRoute::_("index.php?option=com_products&task=logo.edit&id=" . $item->id); ?>"><i class="icon-edit"></i> <?php echo JText::_("JGLOBAL_EDIT"); ?></a>
						<?php endif; ?>
					</td>
					<?php endif; ?>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>

<?php // Code to add a link to submit an logo. ?>
<?php if ($this->category->getParams()->get('access-create')) : ?>
	<?php echo JHtml::_('icon.create', $this->category, $this->category->params); ?>
<?php  endif; ?>

<?php // Add pagination links ?>
<?php if (!empty($this->items)) : ?>
	<?php if (($this->params->def('show_pagination', 2) == 1  || ($this->params->get('show_pagination') == 2)) && ($this->pagination->pagesTotal > 1)) : ?>
	<div class="pagination">

		<?php if ($this->params->def('show_pagination_results', 1)) : ?>
			<p class="counter pull-right">
				<?php echo $this->pagination->getPagesCounter(); ?>
			</p>
		<?php endif; ?>

		<?php echo $this->pagination->getPagesLinks(); ?>
	</div>
	<?php endif; ?>
</form>
<?php  endif; */?>
<section class="module hero-banner hero-banner--cinema hero-banner--secondary hero-banner--dark-text" data-controller="heroBanner">
	<div class="grid-construct">
		<div id="hero-main" class="hero-banner__containerx" data-sm-src="images/formats-desktop.jpg" data-lg-src="images/formats-desktop.jpg" style="height: 340px; background-size: 1611px 512.865px;">
			<div class="hero-banner__inner">
				<div class="hero-banner__content">
					<h1 class="hero-banner__heading">Choose a format.</h1>
					<h2 class="hero-banner__subheading">Any format.</h2>
					<hr class="hero-banner__hr"><p class="hero-banner__text__black">From print to pixel, we’ve got you covered</p>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- main area -->

<?php if (empty($this->items)) : ?>
	<p><?php echo JText::_('No Products Found'); ?></p>
<?php else : ?>
<section class="module tab-combo tab-combo--three" data-controller="tabCombo" style="height: 890px; overflow: visible;">
	<!-- Nav tabs -->

		<ul id="myTabs" role="tablist" class="nav nav-tabs grid-construct-x js-categories-container">
			<?php foreach ( $this->covers as $i => $cover ) {?>
			<li role="presentation" class="nav-item tab-combo__col js-drawer__header__col" >
				<a class="nav-link active" data-id="<?php echo $cover->product_alias;?>" href="#<?php echo $cover->product_alias;?>" id="<?php echo $cover->id;?>-tab" role="tablist" data-toggle="tab" aria-controls="<?php echo $cover->product_alias;?>" aria-expanded="true">

						<?php
						$image = $cover->cover_image;
						{/*
							$params = json_decode($category->params);
							//var_dump($params);
							$image = $params->image;
							$alias = $category->alias;
							$title = $category->title;
							$title = strtoupper($title);
							if($image == '') {
								$image = JURI::root().'images/pricing/default.jpg';
							}
						*/} ;?>
					<div class="tab-combo__content js-drawer__content">
						<img class="tab-combo__option__img center-block" src="<?php echo $image;?>" alt="ImageWrap, Hardcover">
						<h3 class="tab-combo__option__head js-tab-combo__option__head"><?php echo $cover->cover_name;?></h3>
						<h4 class="tab-combo__option__subhead"><?php echo $cover->cover_type;?></h4>
						<p class="tab-combo__option__text">Starts at <span class="book_price imagewrap square">Kshs 870.00</span></p>
					</div>
				</a>
			</li>
			<?php }?>
	</ul>

		<hr class="tab-combo__hr">
<?php
	$array = array();
	foreach ($this->covers as $product) {
		$array[$product->product_alias][] = $product;
	}
?>
		<!-- data tabs -->
		<div class="tab-content grid-construct-x js-categories-container">
			<?php
				///foreach ($this->items as $product){

					foreach ($array as $key => $prodz) {
					// foreach ( $this->covers as $i => $cover ) {
						// var_dump($key);
				if($i == $product->product_alias) { $active = "active in"; } else { $active=""; }
			 ?>
			<div class="tab-pane fade <?php echo $active; ?>" role="tabpanel" id="<?php echo $key;?>" >

				<div class="col-sm-12">
	<div class="col-xs-3"> <!-- required for floating -->
		<!-- Nav tabs -->
		<ul class="grid-constuct-x accordion__box">
			<li class="accordion__tab active"><a class="accordion__tab__title js-accordion-toggle" href="#cover" data-toggle="tab">Cover</a>
				<ul class="module tab-slider hidden-sm" role="tablist" data-controller="tabSlider">
					<li class="tab-slider__tab"><a class="tab-slider__tab__head js-tab-slider-toggle tab-slider__tab--active" href="#panel-small-square"><?php echo $cover->cover_name;?> <small class="tab-slider__tab__info"><?php echo $cover->cover_type;?></small></a></li>
				</ul>
			</li>
			<li class="accordion__tab"><a class="accordion__tab__title js-accordion-toggle" href="#size" data-toggle="tab">Size</a></li>
			<li class="accordion__tab"><a class="accordion__tab__title js-accordion-toggle" href="#paper" data-toggle="tab">Paper</a></li>
			<li class="accordion__tab"><a class="accordion__tab__title js-accordion-toggle" href="#endsheets" data-toggle="tab">End Sheets</a></li>
			<li class="accordion__tab"><a class="accordion__tab__title js-accordion-toggle" href="#<?php echo $product->alias;?>logo" data-toggle="tab">PerfectPics Logo</a></li>
		</ul>
	</div>

	<div class="col-xs-9">
		<!-- Tab panes -->
		<div class="tab-content">
			<div class="tab-pane active" id="cover">
				<div class="product-highlight--left"><img src="http://localhost/perfectpics_v21/images/the-science-of-social-d957fa898cfc03a6216a56d8c234520d63157f95036ad53c4630680986ff6bb8.jpg" alt="ImageWrap Cover"></div>

				<div class="product-highlight--right">
					<h2 class="headline--medium product-highlight__head"><?php echo $cover->cover_name;?></h2>
					<ul class="product-highlight__list"><li>Cover design is printed directly on the hardcover</li><li>Matte finish on the cover</li><li>Durable library binding</li></ul>
						<div class="product-highlight__cta">
							<a href="price" class="btn btn--border-black js-smooth-scroll">Get Started</a>
						</div>
				</div>
			</div>

			<div class="tab-pane" id="size">
				<div class="product-highlight--left"><img src="http://localhost/perfectpics_v21/images/the-science-of-social-d957fa898cfc03a6216a56d8c234520d63157f95036ad53c4630680986ff6bb8.jpg" alt="ImageWrap Cover"></div>

				<div class="product-highlight--right">
					<h2 class="headline--medium product-highlight__head">A5 Landscape</h2>
					<p class="product-highlight__details--sm">16–200 pages</p>
					<p class="product-highlight__details--lg">Starts at <a href="price">
						<span class="book_price imagewrap square">Kshs 870.00</span></a> for 20 pages</p>
						<div class="product-highlight__cta">
							<a href="price" class="btn btn--border-black js-smooth-scroll">Get Started</a>
						</div>
				</div>
			</div>

			<div class="tab-pane" id="paper">
				<div class="product-highlight--left"><img src="http://localhost/perfectpics_v21/images/the-science-of-social-d957fa898cfc03a6216a56d8c234520d63157f95036ad53c4630680986ff6bb8.jpg" alt="Paper Types - Standard"></div>

				<div class="product-highlight--right"><h2 class="headline--medium product-highlight__head">Standard</h2>
					<p class="product-highlight__details--sm">Semi-matte, 80 lb. (118 gsm)</p>
					<p class="product-highlight__details--lg">Starts at <a href="/pricing">
						<span class="book_price additional_pages square_standard_paper">US $0.20</span></a> per additional page</p>
						<ul class="product-highlight__list">
							<li>Smooth semi-matte finish</li>
							<li>Manufactured by NewPage</li>
							<li>Up to 440 pages</li>
						</ul>
						<div class="product-highlight__cta">
							<a href="price" class="btn btn--border-black js-smooth-scroll">Get Started</a>
						</div>
					</div>
			</div>

			<div class="tab-pane" id="endsheets">
				<div class="product-highlight--left"><img src="http://localhost/perfectpics_v21/images/the-science-of-social-d957fa898cfc03a6216a56d8c234520d63157f95036ad53c4630680986ff6bb8.jpg" alt="End sheets - Standard Mid-Grey"></div>

				<div class="product-highlight--right">
					<h2 class="headline--medium product-highlight__head">Standard Mid-Grey</h2>
					<p class="product-highlight__details--sm">End Sheet</p>
					<ul class="product-highlight__list">
						<li>Hardcover books only</li>
						<li>An end sheet is adhered to the inside surface of your book’s cover and connects the cover to the pages.</li>
					</ul>
					<div class="product-highlight__cta">
						<a href="price" class="btn btn--border-black js-smooth-scroll">Get Started</a>
					</div>
				</div>
			</div>
			<?php //var_dump($product);?>
			<div class="tab-pane" id="<?php echo $product->alias;?>logo">
				<div class="product-highlight--left"><img src="<?php //echo $product->logo_image;?>" alt="<?php echo $product->title;?>"></div>

				<div class="product-highlight--right">
					<h2 class="headline--medium product-highlight__head"><?php echo $product->title;?></h2>
					<p class="product-highlight__copy"><?php /*echo $product->details;*/?></p>
					<div class="product-highlight__cta">
						<a href="price" class="btn btn--border-black js-smooth-scroll">Get Started</a>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="clearfix"></div>

		</div> <!-- end col-sm-12 -->
		</div> <!-- end active -->
		<?php } ?>
		</div> <!-- end tab-content grid-construct-x js-categories-container -->
</section>
<?php  endif;?>
<div class="module grid grid--three" data-controller="grid">
	<ul class="grid-construct">
		<li class="grid__column grid__column--center"><div class="grid__content"><div class="grid__icon"><i class="icon icon--paper-types"></i></div><h5 class="subhead-c1">Paper Samples</h5><div class="grid__text">The right paper makes all the difference. Order a <a href="templates">swatch-kit</a> to see for yourself.</div></div></li>
		<li class="grid__column grid__column--center"><div class="grid__content"><div class="grid__icon"><i class="icon pricing"></i></div><h5 class="subhead-c1">Pricing Calculator</h5><div class="grid__text">Want to cost out your project before you begin? Use our handy <a href="price">pricing calculator</a>.</div></div></li>
		<li class="grid__column grid__column--center"><div class="grid__content"><div class="grid__icon"><i class="icon truck-shipping"></i></div><h5 class="subhead-c1">Shipping Calculator</h5><div class="grid__text">Regardless of where you’re shipping your book, you can <a href="price">calculate</a> the cost upfront.</div></div></li>
	</ul>
</div>

<div class="module"><div class="grid-construct"><h4 class="section-header-b2 typ__align-center">From the Blog</h4></div></div>

<div class="module grid grid--three" data-controller="grid">
	<ul class="grid-construct">
	<li class="grid__column grid__column--center"><div class="grid__content" style="max-width: 424px;"><a href="/blog/make-great-photo-book/" target="_blank"><img class="grid__img" src="http://assets1.blurb.com/pages/website-assets/photo-books/blog-howto-photobook-1567705b7922b8bc691eefbb8b9ba39d650d260a4abd13c6d10ae2534e6a2739.jpg" alt="How to Make a Great Photo Book" style="max-width: 424px;"></a>
	<h5 class="subhead-c1 typ__letter-case">How to Make a<br>Great Photo Book</h5><div class="grid__text" style="max-width: 424px;">The book has always been considered the ultimate final statement when it comes to printing ones images. <a href="/blog/make-great-photo-book/" target="_blank">Read more</a></div></div></li>
	<li class="grid__column grid__column--center">
		<div class="grid__content" style="max-width: 424px;"><a href="/blog/how-to-choose-your-paper-type/" target="_blank"><img class="grid__img" src="http://assets1.blurb.com/pages/website-assets/photo-books/blog-paper-choices-1c0a222d1fcc20206fd556c74c829e00eb012f8f8a86e9b1cc8927c6a90bd020.jpg" alt="How to choose your paper type" style="max-width: 424px;"></a><h5 class="subhead-c1 typ__letter-case">How to Choose<br>Your Paper Type</h5><div class="grid__text" style="max-width: 424px;">Ensuring your vision comes to life on the page involves an important choice about paper and cover type. <a href="/blog/how-to-choose-your-paper-type/" target="_blank">Read more</a></div></div>
	</li><li class="grid__column grid__column--center"><div class="grid__content" style="max-width: 424px;"><a href="/blog/curate-print-group-photo-book/" target="_blank"><img class="grid__img" src="http://assets1.blurb.com/pages/website-assets/photo-books/blog-group-photo-book-2267e44f458e997e084d53b5f9a5ce2888ce9ae6c63f97672909c2a0e222b08f.jpg" alt="How to Curate and Print a Group Photo Book" style="max-width: 424px;"></a><h5 class="subhead-c1 typ__letter-case">How to Curate and<br>Print a Group Photo Book</h5><div class="grid__text" style="max-width: 424px;">Curating a group photo book is really enjoyable, but it can also be a little daunting your first time out. <a href="/blog/curate-print-group-photo-book/" target="_blank">Read more</a></div></div></li>
</ul>
</div>
