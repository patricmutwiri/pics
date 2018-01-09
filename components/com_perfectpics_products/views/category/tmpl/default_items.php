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

	<!-- header banner -->
		<section class="module hero-banner hero-banner--cinema" data-controller="heroBanner">
			<div class="grid-construct">
				<div id="hero-main" class="hero-banner__container" data-sm-src="http://localhost/perfectpics_v2/images/photo-books-desktop.jpg" data-lg-src="http://localhost/perfectpics_v2/images/photo-books-desktop.jpg" style="height: 512.865px; background-size: 1611px 512.865px;"><div class="hero-banner__inner">
			<div class="hero-banner__content">
				<h1 class="hero-banner__heading">Keep Memories Alive</h1>
				<hr class="hero-banner__hr">
				<p class="hero-banner__text">Photobooks allow you to tell your story your way. Start your personalised book today</p>
				<div class="hero-banner__btns"><a href="price" class="hero-banner__btn hero-banner__btn--cta js-smooth-scroll">Get Started</a>
				</div>
			</div>
		</div>
	</div>
</div>
</section>
	<!-- end banner -->
<div class="grid-construct-x hidden">

<?php if (empty($this->items)) : ?>
	<p><?php echo JText::_('COM_PERFECTPICS_PRODUCTS_NO_PERFECTPICS_PRODUCTS'); ?></p>
<?php else : ?>

<form action="<?php echo htmlspecialchars(JUri::getInstance()->toString()); ?>" method="post" name="adminForm" id="adminForm" class="form-inline">
	<?php if ($this->params->get('show_headings') || $this->params->get('filter_field') != 'hide' || $this->params->get('show_pagination_limit')) :?>
	<fieldset class="filters btn-toolbar clearfix">
		<?php if ($this->params->get('filter_field') != 'hide') :?>
			<div class="btn-group">
				<label class="filter-search-lbl element-invisible" for="filter-search">
					<?php echo JText::_('COM_PERFECTPICS_PRODUCTS_'.$this->params->get('filter_field').'_FILTER_LABEL').'&#160;'; ?>
				</label>
				<input type="text" name="filter-search" id="filter-search" value="<?php echo $this->escape($this->state->get('list.filter')); ?>" class="inputbox" onchange="document.adminForm.submit();" title="<?php echo JText::_('COM_PERFECTPICS_PRODUCTS_FILTER_SEARCH_DESC'); ?>" placeholder="<?php echo JText::_('COM_PERFECTPICS_PRODUCTS_'.$this->params->get('filter_field').'_FILTER_LABEL'); ?>" />
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

	<table class="category table table-striped table-bordered table-hover grid-construct-x">
		<?php if ($this->params->get('show_headings')) : ?>
		<thead>
			<tr>
				<th id="categorylist_header_title">
					<?php echo JHtml::_('grid.sort', 'JGLOBAL_TITLE', 'a.product_name', $listDirn, $listOrder); ?>
				</th>
				<?php if ($this->params->get('list_show_author', 1)) : ?>
					<th id="categorylist_header_author">
						<?php echo JHtml::_('grid.sort', 'JAUTHOR', 'author', $listDirn, $listOrder); ?>
					</th>
				<?php endif; ?>
				<?php if ($this->params->get('list_show_hits', 1)) : ?>
					<th id="categorylist_header_hits">
						<?php echo JHtml::_('grid.sort', 'JGLOBAL_HITS', 'a.hits', $listDirn, $listOrder); ?>
					</th>
				<?php endif; ?>
				<?php if ($this->user->authorise('core.edit') || $this->user->authorise('core.edit.own')) : ?>
					<th id="categorylist_header_edit"><?php echo JText::_('COM_PERFECTPICS_PRODUCTS_EDIT_ITEM'); ?></th>
				<?php endif; ?>
			</tr>
		</thead>
		<?php endif; ?>
		<tbody>
			<?php
			foreach ($this->items as $i => $item) :
			$canEdit	= $this->user->authorise('core.edit',       'com_perfectpics_products'.'.perfectpics_product.'.$item->id);
			$canEditOwn	= $this->user->authorise('core.edit.own',   'com_perfectpics_products'.'.perfectpics_product.'.$item->id) && $item->created_by == $this->user->id;
			?>
				<?php if (isset($this->items[$i]->published) && $this->items[$i]->published == 0) : ?>
				 <tr class="system-unpublished cat-list-row<?php echo $i % 2; ?>">
				<?php else: ?>
				<tr class="cat-list-row<?php echo $i % 2; ?>" >
				<?php endif; ?>
					<td headers="categorylist_header_title" class="list-title">
						<?php if (isset($item->access) && in_array($item->access, $this->user->getAuthorisedViewLevels())) : ?>
							<a href="<?php echo JRoute::_("index.php?option=com_perfectpics_products&view=perfectpics_product&id=" . $item->id); ?>">
								<?php echo $this->escape($item->product_name); ?>
							</a>
						<?php else: ?>
							<?php echo $this->escape($item->product_name); ?>
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
					<?php if ($this->params->get('list_show_hits', 1)) : ?>
					<td headers="categorylist_header_hits" class="list-hits">
						<span class="badge badge-info">
							<?php echo JText::sprintf('JGLOBAL_HITS_COUNT', $item->hits); ?>
						</span>
					</td>
					<?php endif; ?>
					<?php if ($this->user->authorise('core.edit') || $this->user->authorise('core.edit.own')) : ?>
					<td headers="categorylist_header_edit" class="list-edit">
						<?php if ($canEdit || $canEditOwn) : ?>
							<a href="<?php echo JRoute::_("index.php?option=com_perfectpics_products&task=perfectpics_product.edit&id=" . $item->id); ?>"><i class="icon-edit"></i> <?php echo JText::_("JGLOBAL_EDIT"); ?></a>
						<?php endif; ?>
					</td>
					<?php endif; ?>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>

<?php // Code to add a link to submit an perfectpics_product. ?>
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
<?php  endif; ?>

</div>

<!-- main area -->
<section class="module tab-combo tab-combo--three" data-controller="tabCombo" style="height: 890px; overflow: visible;">
	<!-- Nav tabs -->
		<ul class="nav nav-tabs grid-construct-x js-categories-container" role="tablist">
			<li class="nav-item tab-combo__col js-drawer__header__col">
			<a class="nav-link active" data-toggle="tab" href="#imagewrap" role="tab">
				<div class="tab-combo__content js-drawer__content">
					<img class="tab-combo__option__img center-block" src="http://assets1.blurb.com/pages/website-assets/photo-books/02-formats-imagewrap-227x227-2540dab81ecd751ded10298fc9c85fa4fe0fd8d520356c233a321a14e713d178.jpg" alt="ImageWrap, Hardcover">
					<h3 class="tab-combo__option__head js-tab-combo__option__head">Pepe</h3>
					<h4 class="tab-combo__option__subhead">Soft Cover</h4>
					<p class="tab-combo__option__text">Starts at <span class="book_price imagewrap square">Kshs 870.00</span></p>
				</div>
			</a>
			</li>
			<li class="nav-item tab-combo__col js-drawer__header__col">
			<a class="nav-link" data-toggle="tab" href="#dustjacket" role="tab">
				<div class="tab-combo__content js-drawer__content">
					<img class="tab-combo__option__img center-block" src="http://assets1.blurb.com/pages/website-assets/photo-books/02-formats-dustjacket-227x227-d03b301f1776dc4c73f4eb3ca01ed2344fe8a589a2c86b259fc90a980d3ec5e9.jpg" alt="Dust Jacket, Hardcover">
					<h3 class="tab-combo__option__head js-tab-combo__option__head">Ubora</h3>
					<h4 class="tab-combo__option__subhead">Hardcover</h4>
					<p class="tab-combo__option__text">Starts at <span class="book_price hardcover square">Kshs 3,000.00</span></p>
				</div>
			</a>
			</li>
			<li class="nav-item tab-combo__col js-drawer__header__col">
			<a class="nav-link" data-toggle="tab" href="#softcover" role="tab">
				<div class="tab-combo__content js-drawer__content">
					<img class="tab-combo__option__img center-block" src="http://assets1.blurb.com/pages/website-assets/photo-books/02-formats-softcover-227x227-2e069364a7b140ddddd2ebe822a4947ab7388b971f4d942b446dabc0304b95d7.jpg" alt="Soft Cover">
					<h3 class="tab-combo__option__head js-tab-combo__option__head">Maridadi</h3>
					<h4 class="tab-combo__option__subhead">Hardcover</h4>
					<p class="tab-combo__option__text">Starts at <span class="book_price softcover square">Kshs 1750.00</span></p>
				</div>
			</a>
			</li>
		</ul>
		<hr class="tab-combo__hr">

		<!-- data tabs -->
		<div class="tab-content grid-construct-x js-categories-container">
			<div class="tab-pane active" id="imagewrap" role="tabpanel">

				<div class="col-sm-12">
	<div class="col-xs-3"> <!-- required for floating -->
		<!-- Nav tabs -->
		<ul class="nav nav-tabs tabs-left">
			<li class="active"><a class="accordion__tab__title js-accordion-toggle" href="#cover" data-toggle="tab">Cover</a>
				<ul class="module tab-slider hidden-sm hidden" role="tablist" data-controller="tabSlider">
					<li class="tab-slider__tab"><a class="tab-slider__tab__head js-tab-slider-toggle tab-slider__tab--active" href="#panel-small-square">Small Square <small class="tab-slider__tab__info">7×7 in, 18×18 cm</small></a></li>
					<li class="tab-slider__tab"><a class="tab-slider__tab__head js-tab-slider-toggle" href="#panel-standard-portrait">Standard Portrait <small class="tab-slider__tab__info">8×10 in, 20×25 cm</small></a></li>
					<li class="tab-slider__tab"><a class="tab-slider__tab__head js-tab-slider-toggle" href="#panel-standard-landscape">Standard Landscape <small class="tab-slider__tab__info">10×8 in, 25×20 cm</small></a></li>
					<li class="tab-slider__tab"><a class="tab-slider__tab__head js-tab-slider-toggle" href="#panel-large-square">Large Square <small class="tab-slider__tab__info">12×12 in, 30×30 cm</small></a></li>
					<li class="tab-slider__tab"><a class="tab-slider__tab__head js-tab-slider-toggle" href="#panel-large-landscape">Large Landscape <small class="tab-slider__tab__info">13×11 in, 33×28 cm</small></a></li>
				</ul>
			</li>
			<li class=""><a class="accordion__tab__title js-accordion-toggle" href="#size" data-toggle="tab">Size</a></li>
			<li class=""><a class="accordion__tab__title js-accordion-toggle" href="#paper" data-toggle="tab">Paper</a></li>
			<li class=""><a class="accordion__tab__title js-accordion-toggle" href="#endsheets" data-toggle="tab">End Sheets</a></li>
			<li class=""><a class="accordion__tab__title js-accordion-toggle" href="#logo" data-toggle="tab">PerfectPics Logo</a></li>
		</ul>
	</div>

	<div class="col-xs-9">
		<!-- Tab panes -->
		<div class="tab-content">
			<div class="tab-pane active" id="cover">
				<div class="product-highlight--left"><img src="http://assets1.blurb.com/pages/website-assets/photo-books/02b-PhotoBook-CoverTypes-Hardcover-551x551_v2-3188aca133a19708cc64939d9e9a19a6995c70d69af989d29d1abdee779ca914.jpg" alt="ImageWrap Cover"></div>

				<div class="product-highlight--right">
					<h2 class="headline--medium product-highlight__head">Pepe Hardcover</h2>
					<ul class="product-highlight__list"><li>Cover design is printed directly on the hardcover</li><li>Matte finish on the cover</li><li>Durable library binding</li></ul>
						<div class="product-highlight__cta">
							<a href="price" class="btn btn--border-black js-smooth-scroll">Get Started</a>
						</div>
				</div>
			</div>

			<div class="tab-pane" id="size">
				<div class="product-highlight--left"><img src="http://assets1.blurb.com/pages/website-assets/photo-books/genres_photo-general_assets_sizes_A1_7x7-c4cd60d2d28b136521aa2c2a18cc575b0d5a99f1f1597c24d3615028b2891380.jpg" alt="ImageWrap Cover"></div>

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
				<div class="product-highlight--left"><img src="http://assets1.blurb.com/pages/website-assets/photo-books/02c-tab-combo-paper-01standard-551x551-6a3b1d173f388c982fa8152d9de4cf3ba0a1a991270a1cfadff4f7daef1d4ce4.jpg" alt="Paper Types - Standard"></div>

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
				<div class="product-highlight--left"><img src="http://assets1.blurb.com/pages/website-assets/photo-books/tab-combo-end-midgrey-551x551-bd1146c679eab188aacf3a2d7ef66c59c5403ac77423ecbedef9a3800bc0889b.jpg" alt="End sheets - Standard Mid-Grey"></div>

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

			<div class="tab-pane" id="logo">
				<div class="product-highlight--left"><img src="http://assets1.blurb.com/pages/website-assets/photo-books/02f-PhotoBook-BlurbLogo-HardCover-551x551-b162cabcaa9638d81fa8bebe6b058244849c68ecd5362a9ef501ff21cf1549b4.jpg" alt="Paper Types - Standard"></div>

				<div class="product-highlight--right">
					<h2 class="headline--medium product-highlight__head">PerfectPics Logo</h2>
					<p class="product-highlight__copy">All Blurb books feature a small PerfectPics logo on the back of your book that can easily be removed or replaced with your own graphic.</p>
					<div class="product-highlight__cta">
						<a href="price" class="btn btn--border-black js-smooth-scroll">Get Started</a>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="clearfix"></div>

</div>

				</div>
			<div class="tab-pane" id="dustjacket" role="tabpanel">
				<div class="col-sm-12">
	<div class="col-xs-3"> <!-- required for floating -->
		<!-- Nav tabs -->
		<ul class="nav nav-tabs tabs-left">
			<li class="active"><a class="accordion__tab__title js-accordion-toggle" href="#coverx" data-toggle="tab">Cover</a></li>
			<li class=""><a class="accordion__tab__title js-accordion-toggle" href="#sizex" data-toggle="tab">Size</a></li>
			<li class=""><a class="accordion__tab__title js-accordion-toggle" href="#paperx" data-toggle="tab">Paper</a></li>
			<li class=""><a class="accordion__tab__title js-accordion-toggle" href="#endsheetsx" data-toggle="tab">End Sheets</a></li>
			<li class=""><a class="accordion__tab__title js-accordion-toggle" href="#logox" data-toggle="tab">PerfectPics Logo</a></li>
		</ul>
	</div>

	<div class="col-xs-9">
		<!-- Tab panes -->
		<div class="tab-content">
			<div class="tab-pane active" id="coverx">
				<div class="product-highlight--left"><img src="http://assets1.blurb.com/pages/website-assets/photo-books/02b-PhotoBook-CoverTypes-DustJacket-551x551-ebf634cbb1dc603cda14da555ba1f7c4e8c70bc687a09bf8d8068940c88416ad.jpg" alt="ImageWrap Cover"></div>

				<div class="product-highlight--right">
					<h2 class="headline--medium product-highlight__head">Ubora Hardcover</h2>
					<ul class="product-highlight__list"><li>Cover design is printed directly on the hardcover</li><li>Matte finish on the cover</li><li>Durable library binding</li></ul>
						<div class="product-highlight__cta">
							<a href="price" class="btn btn--border-black js-smooth-scroll">Get Started</a>
						</div>
				</div>
			</div>

			<div class="tab-pane" id="sizex">
				<div class="product-highlight--left"><img src="http://assets1.blurb.com/pages/website-assets/photo-books/genres_photo-general_assets_sizes_A1_7x7-c4cd60d2d28b136521aa2c2a18cc575b0d5a99f1f1597c24d3615028b2891380.jpg" alt="ImageWrap Cover"></div>

				<div class="product-highlight--right">
					<h2 class="headline--medium product-highlight__head">Ubora Large Square</h2>
					<p class="product-highlight__details--sm">20–100 pages</p>
					<p class="product-highlight__details--lg">Starts at <a href="price">
						<span class="book_price imagewrap square">Kshs 18,000.00</span></a> for 20 pages</p>
						<div class="product-highlight__cta">
							<a href="create" class="btn btn--border-black js-smooth-scroll">Get Started</a>
						</div>
				</div>
			</div>

			<div class="tab-pane" id="paperx">
				<div class="product-highlight--left"><img src="http://assets1.blurb.com/pages/website-assets/photo-books/02c-tab-combo-paper-01standard-551x551-6a3b1d173f388c982fa8152d9de4cf3ba0a1a991270a1cfadff4f7daef1d4ce4.jpg" alt="Paper Types - Standard"></div>

				<div class="product-highlight--right"><h2 class="headline--medium product-highlight__head">Standard Paper</h2>
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

			<div class="tab-pane" id="endsheetsx">
				<div class="product-highlight--left"><img src="http://assets1.blurb.com/pages/website-assets/photo-books/tab-combo-end-midgrey-551x551-bd1146c679eab188aacf3a2d7ef66c59c5403ac77423ecbedef9a3800bc0889b.jpg" alt="End sheets - Standard Mid-Grey"></div>

				<div class="product-highlight--right">
					<h2 class="headline--medium product-highlight__head">Ubora</h2>
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

			<div class="tab-pane" id="logox">
				<div class="product-highlight--left"><img src="http://assets1.blurb.com/pages/website-assets/photo-books/02f-PhotoBook-BlurbLogo-HardCover-551x551-b162cabcaa9638d81fa8bebe6b058244849c68ecd5362a9ef501ff21cf1549b4.jpg" alt="Paper Types - Standard"></div>

				<div class="product-highlight--right">
					<h2 class="headline--medium product-highlight__head">PerfectPics Logo</h2>
					<p class="product-highlight__copy">All PerfectPics books feature a small logo on the back of it book that can easily be removed or replaced with your own graphic.</p>
					<div class="product-highlight__cta">
						<a href="price" class="btn btn--border-black js-smooth-scroll">Get Started</a>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="clearfix"></div>

</div>
				</div>
			<div class="tab-pane" id="softcover" role="tabpanel">
				<div class="col-sm-12">
	<div class="col-xs-3"> <!-- required for floating -->
		<!-- Nav tabs -->
		<ul class="nav nav-tabs tabs-left">
			<li class="active"><a class="accordion__tab__title js-accordion-toggle" href="#coverxx" data-toggle="tab">Cover</a></li>
			<li class=""><a class="accordion__tab__title js-accordion-toggle" href="#sizexx" data-toggle="tab">Size</a></li>
			<li class=""><a class="accordion__tab__title js-accordion-toggle" class="accordion__tab__title js-accordion-toggle" href="#paperxx" data-toggle="tab">Paper</a></li>
			<li class=""><a class="accordion__tab__title js-accordion-toggle" href="#endsheetsxx" data-toggle="tab">End Sheets</a></li>
			<li class=""><a class="accordion__tab__title js-accordion-toggle" href="#logoxx" data-toggle="tab">PerfectPics Logo</a></li>
		</ul>
	</div>

	<div class="col-xs-9">
		<!-- Tab panes -->
		<div class="tab-content">
			<div class="tab-pane active" id="coverxx">
				<div class="product-highlight--left"><img src="http://assets1.blurb.com/pages/website-assets/photo-books/02b-PhotoBook-CoverTypes-DustJacket-551x551-ebf634cbb1dc603cda14da555ba1f7c4e8c70bc687a09bf8d8068940c88416ad.jpg" alt="ImageWrap Cover"></div>

				<div class="product-highlight--right">
					<h2 class="headline--medium product-highlight__head">Maridadi Hardcover</h2>
					<ul class="product-highlight__list"><li>Cover design is printed directly on the hardcover</li><li>Matte finish on the cover</li><li>Durable library binding</li></ul>
						<div class="product-highlight__cta">
							<a href="price" class="btn btn--border-black js-smooth-scroll">Get Started</a>
						</div>
				</div>
			</div>

			<div class="tab-pane" id="sizexx">
				<div class="product-highlight--left"><img src="http://assets1.blurb.com/pages/website-assets/photo-books/genres_photo-general_assets_sizes_A1_7x7-c4cd60d2d28b136521aa2c2a18cc575b0d5a99f1f1597c24d3615028b2891380.jpg" alt="ImageWrap Cover"></div>

				<div class="product-highlight--right">
					<h2 class="headline--medium product-highlight__head">Small Square:Small Square: 20x20cm</h2>
					<p class="product-highlight__details--sm">20–200 pages</p>
					<p class="product-highlight__details--lg">Starts at <a href="/pricing">
						<span class="book_price imagewrap square">Kshs 1750.00</span></a> for 20 pages</p>
						<div class="product-highlight__cta">
							<a href="create" class="btn btn--border-black js-smooth-scroll">Get Started</a>
						</div>
				</div>
			</div>

			<div class="tab-pane" id="paperxx">
				<div class="product-highlight--left"><img src="http://assets1.blurb.com/pages/website-assets/photo-books/02c-tab-combo-paper-01standard-551x551-6a3b1d173f388c982fa8152d9de4cf3ba0a1a991270a1cfadff4f7daef1d4ce4.jpg" alt="Paper Types - Standard"></div>

				<div class="product-highlight--right"><h2 class="headline--medium product-highlight__head">Art Gloss paper</h2>
					<p class="product-highlight__details--sm">170gsm</p>
					<p class="product-highlight__details--lg">Starts at <a href="/pricing">
						<span class="book_price additional_pages square_standard_paper">Kshs 65.00</span></a> per additional page</p>
						<ul class="product-highlight__list">
							<li>Standard finish is a art gloss 150gsm  paper</li>
							<li> If you want an alternative option, please get in touch when ordering your book</li>
						</ul>
						<div class="product-highlight__cta">
							<a href="price" class="btn btn--border-black js-smooth-scroll">Get Started</a>
						</div>
					</div>
			</div>

			<div class="tab-pane" id="endsheetsxx">
				<div class="product-highlight--left"><img src="http://assets1.blurb.com/pages/website-assets/photo-books/tab-combo-end-midgrey-551x551-bd1146c679eab188aacf3a2d7ef66c59c5403ac77423ecbedef9a3800bc0889b.jpg" alt="End sheets - Standard Mid-Grey"></div>

				<div class="product-highlight--right">
					<h2 class="headline--medium product-highlight__head">Maridadi</h2>
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

			<div class="tab-pane" id="logoxx">
				<div class="product-highlight--left"><img src="http://assets1.blurb.com/pages/website-assets/photo-books/02f-PhotoBook-BlurbLogo-HardCover-551x551-b162cabcaa9638d81fa8bebe6b058244849c68ecd5362a9ef501ff21cf1549b4.jpg" alt="Paper Types - Standard"></div>

				<div class="product-highlight--right">
					<h2 class="headline--medium product-highlight__head">PerfectPics Logo</h2>
					<p class="product-highlight__copy">All PerfectPics books feature a small PerfectPics logo on the back of your book that can easily be removed or replaced with your own graphic.</p>
					<div class="product-highlight__cta">
						<a href="price" class="btn btn--border-black js-smooth-scroll">Get Started</a>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="clearfix"></div>

</div>
				</div>
		</div>
</section>
<div class="tabbable boxed parentTabs hidden">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#set1">Tab 1</a>
        </li>
        <li><a href="#set2">Tab 2</a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade active in" id="set1">
            <div class="tabbable">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#sub11">Tab 1.1</a>
                    </li>
                    <li><a href="#sub12">Tab 1.2</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade active in" id="sub11">
                        <p>Tab 1.1</p>
                    </div>
                    <div class="tab-pane fade" id="sub12">
                        <p>Tab 1.2</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="set2">
            <div class="tabbable">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#sub21">Tab 2.1</a>
                    </li>
                    <li><a href="#sub22">Tab 2.2</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade active in" id="sub21">
                        <p>Tab 2.1</p>
                    </div>
                    <div class="tab-pane fade" id="sub22">
                        <p>Tab 2.2</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="module grid grid--three" data-controller="grid">
	<ul class="grid-construct">
		<li class="grid__column grid__column--center">
			<div class="grid__content">
				<div class="grid__icon"><i class="icon icon--paper-types"></i></div>
				<h5 class="grid__header typ__letter-case">Paper Samples</h5>
				<div class="grid__text">The right paper makes all the difference. Order a <a href="#">swatch-kit</a> to see for yourself.</div>
			</div>
		</li>
		<li class="grid__column grid__column--center">
			<div class="grid__content"><div class="grid__icon"><i class="icon pricing"></i></div>
			<h5 class="grid__header typ__letter-case">Pricing Calculator</h5>
			<div class="grid__text">Want to cost out your project before you begin? Use our handy <a href="price">pricing calculator</a>.</div>
		</div>
		</li>
		<li class="grid__column grid__column--center">
			<div class="grid__content">
			<div class="grid__icon"><i class="icon truck-shipping"></i></div>
			<h5 class="grid__header typ__letter-case">Shipping Calculator</h5>
			<div class="grid__text">Regardless of where you’re shipping your book, you can <a href="#">calculate</a> the cost upfront.</div>
		</div>
		</li>
	</ul>
</div>


<?php /*$doc = JFactory::getDocument();
//JHtml::_('jquery.framework');
JHtml::_('jquery.framework', false);
$doc->addScriptDeclaration('
    JQuery(document).ready(function () {
       JQuery(".text").text("By this");
    });
');*/
?>
<?php
$doc = JFactory::getDocument();

//JHtml::_('jquery.framework');
JHtml::_('bootstrap.framework'); //Force load Bootstrap

//JHtml::_('jquery.framework');
//$document = JFactory::getDocument();
$doc->addScriptDeclaration('
    		$(document).ready(function () {
        alert("An inline JavaScript Declaration");
    });
');
?>
