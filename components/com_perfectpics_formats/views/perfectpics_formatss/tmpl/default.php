<?php
/**
 * @author
 * @copyright
 * @license
 */

defined("_JEXEC") or die("Restricted access");

// necessary libraries
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');

// sort ordering and direction
$listOrder = $this->state->get('list.ordering');
$listDirn = $this->state->get('list.direction');
$archived	= $this->state->get('filter.published') == 2 ? true : false;
$trashed	= $this->state->get('filter.published') == -2 ? true : false;
$user = JFactory::getUser();

//var_dump($this->prodz);
?>
<style>
.row2 {
	background-color: #e4e4e4;
}
</style>
<script>
	/*jQuery(document).ready(function(){
		jQuery('a[data-toggle="tab"]').on('shown.bs.tab',function(e){
			var tabid = jQuery(e.target).data('id');
			console.log(e);
			console.log(tabid);
		});
	});*/
</script>
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('ul#myTabs li:first-child').addClass('active');
        });
    </script>
<?/*<h2><?php echo JText::_('COM_PERFECTPICS_FORMATS_PERFECTPICS_FORMAT_VIEW_PERFECTPICS_FORMATSS_TITLE'); ?></h2>*/?>


<form action="<?php JRoute::_('index.php?option=com_mythings&view=mythings'); ?>" method="post" name="adminForm" id="adminForm" class="hidden">
	<?php
		// Search tools bar
		echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
	?>
	<div>
		<p>
			<?php if ($user->authorise("core.create", "com_perfectpics_formats") || (count($user->getAuthorisedCategories('com_perfectpics_formats', 'core.create'))) > 0 ) : ?>
				<button type="button" class="btn btn-success" onclick="Joomla.submitform('perfectpics_formats.add')"><?php echo JText::_('JNEW') ?></button>
			<?php endif; ?>
			<?php if (($user->authorise("core.edit", "com_perfectpics_formats") || $user->authorise("core.edit.own", "com_perfectpics_formats")) && isset($this->items[0])) : ?>
				<button type="button" class="btn" onclick="Joomla.submitform('perfectpics_formats.edit')"><?php echo JText::_('JEDIT') ?></button>
			<?php endif; ?>
			<?php if ($user->authorise("core.edit.state", "com_perfectpics_formats")) : ?>
				<?php if (isset($this->items[0]->published)) : ?>
					<button type="button" class="btn" onclick="Joomla.submitform('perfectpics_formatss.publish')"><?php echo JText::_('JPUBLISH') ?></button>
					<button type="button" class="btn" onclick="Joomla.submitform('perfectpics_formatss.unpublish')"><?php echo JText::_('JUNPUBLISH') ?></button>
					<button type="button" class="btn" onclick="Joomla.submitform('perfectpics_formatss.archive')"><?php echo JText::_('JARCHIVE') ?></button>
				<?php elseif (isset($this->items[0])) : ?>
					<button type="button" class="btn btn-error" onclick="Joomla.submitform('perfectpics_formatss.delete')"><?php echo JText::_('JDELETE') ?></button>
				<?php endif; ?>
				<?php if (isset($this->items[0]->published)) : ?>
					<?php if ($this->state->get('filter.published') == -2 && $user->authorise("core.delete", "com_perfectpics_formats")) : ?>
						<button type="button" class="btn btn-error" onclick="Joomla.submitform('perfectpics_formatss.delete')"><?php echo JText::_('JDELETE') ?></button>
					<?php elseif ($this->state->get('filter.published') != -2 && $user->authorise("core.edit.state", "com_perfectpics_formats")) : ?>
						<button type="button" class="btn btn-warning" onclick="Joomla.submitform('perfectpics_formatss.trash')"><?php echo JText::_('JTRASH') ?></button>
					<?php endif; ?>
				<?php endif; ?>
			<?php endif; ?>
		</p>
	</div>
	<table class="category table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th width="1%" class="hidden-phone">
					<?php echo JHtml::_('grid.checkall'); ?>
				</th>
				<th id="itemlist_header_title">
					<?php echo JHtml::_('grid.sort', 'JGLOBAL_TITLE', 'a.formats_icon', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap left">
					<?php echo JHtml::_('grid.sort', JText::_('COM_PERFECTPICS_FORMATS_PERFECTPICS_FORMAT_FIELD_FORMAT_TITLE_LABEL'), 'a.format_title', $listDirn, $listOrder) ?>
				</th>
				<th class="nowrap left">
					<?php echo JHtml::_('grid.sort', JText::_('COM_PERFECTPICS_FORMATS_PERFECTPICS_FORMAT_FIELD_FORMAT_SIZE_LABEL'), 'a.format_size', $listDirn, $listOrder) ?>
				</th>
				<th class="nowrap left">
					<?php echo JHtml::_('grid.sort', JText::_('COM_PERFECTPICS_FORMATS_PERFECTPICS_FORMAT_FIELD_PRICE_FROM_LABEL'), 'a.price_from', $listDirn, $listOrder) ?>
				</th>
				<th class="nowrap left">
					<?php echo JHtml::_('grid.sort', JText::_('COM_PERFECTPICS_FORMATS_PERFECTPICS_FORMAT_FIELD_PAGES_LABEL'), 'a.pages', $listDirn, $listOrder) ?>
				</th>
				<th class="nowrap left">
					<?php echo JHtml::_('grid.sort', JText::_('COM_PERFECTPICS_FORMATS_PERFECTPICS_FORMAT_FIELD_FORMAT_PRICE_LABEL'), 'a.format_price', $listDirn, $listOrder) ?>
				</th>
				<?php if ($this->params->get('list_show_author', 1)) : ?>
				<th id="itemlist_header_author">
					<?php echo JHtml::_('grid.sort', 'JAUTHOR', 'author', $listDirn, $listOrder); ?>
				</th>
				<?php endif; ?>
				<?php if ($this->params->get('list_show_hits', 1)) : ?>
				<th id="itemlist_header_hits">
					<?php echo JHtml::_('grid.sort', 'JGLOBAL_HITS', 'a.hits', $listDirn, $listOrder); ?>
				</th>
				<?php endif; ?>
				<?php if ($this->user->authorise('core.edit') || $this->user->authorise('core.edit.own')) : ?>
				<th id="itemlist_header_edit"><?php echo JText::_('COM_PERFECTPICS_FORMATS_EDIT_ITEM'); ?></th>
				<?php endif; ?>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($this->items as $i => $item) :
		$canEdit	= $this->user->authorise('core.edit',       'com_perfectpics_formats'.'.perfectpics_formats.'.$item->id);
		$canEditOwn	= $this->user->authorise('core.edit.own',   'com_perfectpics_formats'.'.perfectpics_formats.'.$item->id) && $item->created_by == $this->user->id;
		$canDelete	= $this->user->authorise('core.delete',       'com_perfectpics_formats'.'.perfectpics_formats.'.$item->id);
		$canCheckin	= $this->user->authorise('core.manage',     'com_checkin') || $item->checked_out == $this->user->id || $item->checked_out == 0;
		$canChange	= $this->user->authorise('core.edit.state', 'com_perfectpics_formats'.'.perfectpics_formats.'.$item->id) && $canCheckin;
		?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="center"><?php echo JHtml::_('grid.id', $i, $item->id); ?></td>
				<td headers="itemlist_header_title" class="list-title">
					<?php if (isset($item->access) && in_array($item->access, $this->user->getAuthorisedViewLevels())) : ?>
						<a href="<?php echo JRoute::_("index.php?option=com_perfectpics_formats&view=perfectpics_formats&id=" . $item->id); ?>">
							<?php echo $this->escape($item->formats_icon); ?>
						</a>
					<?php else: ?>
						<?php echo $this->escape($item->formats_icon); ?>
					<?php endif; ?>
					<?php if ($item->published == 0) : ?>
						<span class="list-published label label-warning">
							<?php echo JText::_('JUNPUBLISHED'); ?>
						</span>
					<?php endif; ?>
					<?php if ($item->published == 2) : ?>
						<span class="list-published label label-info">
							<?php echo JText::_('JARCHIVED'); ?>
						</span>
					<?php endif; ?>
					<?php if ($item->published == -2) : ?>
						<span class="list-published label">
							<?php echo JText::_('JTRASHED'); ?>
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
				<td style="width:50%"><?php echo $this->escape($item->format_title); ?></td>
				<td style="width:50%"><?php echo $this->escape($item->format_size); ?></td>
				<td style="width:50%"><?php echo $this->escape($item->price_from); ?></td>
				<td style="width:50%"><?php echo $this->escape($item->pages); ?></td>
				<td style="width:50%"><?php echo $this->escape($item->format_price); ?></td>
				<?php if ($this->params->get('list_show_author', 1)) : ?>
				<td class="small hidden-phone">
					<?php echo $this->escape($item->author_name); ?>
				</td>
				<?php endif; ?>
				<?php if ($this->params->get('list_show_hits', 1)) : ?>
				<td headers="itemlist_header_hits" class="list-hits">
					<span class="badge badge-info">
						<?php echo JText::sprintf('JGLOBAL_HITS_COUNT', $item->hits); ?>
					</span>
				</td>
				<?php endif; ?>
				<?php if ($this->user->authorise("core.edit") || $this->user->authorise("core.edit.own")) : ?>
				<td headers="itemlist_header_edit" class="list-edit">
					<?php if ($canEdit || $canEditOwn) : ?>
						<a href="<?php echo JRoute::_("index.php?option=com_perfectpics_formats&task=perfectpics_formats.edit&id=" . $item->id); ?>"><i class="icon-edit"></i> <?php echo JText::_("JGLOBAL_EDIT"); ?></a>
					<?php endif; ?>
				</td>
				<?php endif; ?>
			</tr>
		<?php endforeach ?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="7"><?php echo $this->pagination->getListFooter(); ?></td>
			</tr>
		</tfoot>
	</table>
	<div>
		<input type="hidden" name="task" value=" " />
		<input type="hidden" name="boxchecked" value="0" />
		<!-- Sortierkriterien -->
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>

<!-- Custom view -->
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
<div class="grid-construct">

</div>
	<div class="bs-example bs-example-tabs" data-example-id="togglable-tabs">
		<ul id="myTabs" role="tablist" class="grid-construct nav nav-tabs nav-justified">
			<?php foreach ( $this->categories as $i => $category ) {?>
			<li role="presentation" class="" >
				<a data-id="<?php echo $category->alias;?>" href="#<?php echo $category->alias;?>" id="<?php echo $category->id;?>-tab" role="tab" data-toggle="tab" aria-controls="home" aria-expanded="true">

						<?php
						{
							$params = json_decode($category->params);
							//var_dump($params);
							$image = $params->image;
							$alias = $category->alias;
							$title = $category->title;
							$title = strtoupper($title);
							if($image == '') {
								$image = JURI::root().'images/pricing/default.jpg';
							}
						} ;?>
					<img src="<?php echo $image;?>" class="img=responsive center-block" width="226px" height="150px"> <br/>
					<span class="formats_title"><?php echo $category->title;?></span></a>
			</li>
			<?php }?>
	</ul>



		<?php
			$array = array();
			foreach ($this->prodz as $product) {
				$array[$product->alias][] = $product;
			}
			//echo '<pre>';
			//print_r($array);
			//echo '</pre>';
?>

			<?/*<div class="hidden col-xs-6 left">
			<ul class="nav nav-tabs tabs-left">
				<?php $i = 0; ?>
				<?php while( have_rows('features') ): the_row(); $i++; ?>
						<li class="<?php if( $i ==1 ){ echo "active"; } ?>"><a href="#<?php echo the_sub_field('link_id');?>" data-toggle="tab"><?php the_sub_field('feature-title'); ?></a></li>
				<?php endwhile; ?>
			</ul>
			</div>

			<div class="col-xs-6">
			<div class="tab-content">
				<?php $i = 0; ?>
				<?php while( have_rows('features') ): the_row(); $i++; ?>
						<div id="<?php echo the_sub_field('link_id');?>" class="tab-pane fade in <?php if( $i ==1 ){ echo "active"; } ?>"><?php echo the_sub_field('feature-description'); ?></div>
				<?php endwhile; ?>
			</div>
			</div>*/?>


			<div class="grid-construct tab-content" id="myTabContent">

			<?php foreach ($array as $key => $prodz) {
				//$row = 0;
				//var_dump($key);
				if($key == 'photo-books') { $active = "active in"; } else { $active=""; }
				?>
				<div class="tab-pane fade <?php echo $active; ?>" role="tabpanel" id="<?php echo $key;?>" >
					<ul class="drawer__options">
						<? foreach ($prodz as $product) { ?>
						<li class="drawer__option" data-id="<?php echo $key;?>">
							<div class="images_con"><img src="<?php echo $product->formats_icon;?>"></div>
						<div class="drawer__option__content">
								<div class="drawer__option__title"><?php echo $product->format_title;?></div>
								<p class="drawer__option__text"><?php echo $product->format_size;?></p>
								<div class="drawer__option__text drawer__option__description">From <span class="book_price softcover square">Kshs <?php echo $product->price_from;?></span>
									<?php if (!empty($product->pages)) {
										?>
									<br>for <?php echo $product->pages;?> pages <?php }; ?> <?php if (!empty($product->format_price)) {
									?><span class="drawer__option__price">
									<span class="book_price additional_pages square_standard_paper">Kshs <?php echo $product->format_price;?></span> each extra page</span><?php };?>
								</div>
							</div>
						</li>
				<?php	}?>
					</ul>


						<?php //echo $category->description;?>
					<?php /*foreach ( $this->categories as $i => $category ) {?>
					<div class="module center-col">
						<div class="grid-construct">
							<div class="center-col__inner">
								<?php echo $category->description;?>
								<p><a class="btn btn--border-black" href="formats/<?php echo $category->alias;?>">Learn More</a></p>
							</div>
						</div>
					</div>
					<?php } */?>
				</div>
			<?php
			}
		 ?>
	 </div>
		<?/* <div class="grid-construct tab-content hidden" id="myTabContent">
		<?php foreach ( $this->categories as $i => $category ) {?>
			<?php $row = 1; // number rows
			//echo $row; ?>
					<div class="tab-pane fade in <?php if ($row == 1) {echo 'active';}?>" role="tabpanel" id="<?php echo $category->alias;?>" aria-labelledby="<?php echo $category->id;?>-tab">
						<ul class="drawer__options">
							<? foreach ($this->prodz as $product) { ?>
							<li class="drawer__option" data-id="<?php echo $category->alias;?>">
								<i class="icon small-square"></i>
								<div class="drawer__option__content">
									<div class="drawer__option__title"><?php echo $product->format_title;?>.<?php echo $product->catid;?></div>
									<p class="drawer__option__text"><?php echo $product->format_size;?></p>
									<div class="drawer__option__text drawer__option__description">From <span class="book_price softcover square">Kshs <?php echo $product->price_from;?></span><br>for <?php echo $product->pages;?> pages <span class="drawer__option__price"><span class="book_price additional_pages square_standard_paper">Kshs <?php echo $product->format_price;?></span> each extra page</span>
									</div>
								</div>
							</li>
					<?php	}?>
						</ul>

						<div class="module center-col">
							<div class="grid-construct">
								<div class="center-col__inner">
									<?php echo $category->description;?>
									<p><a class="btn btn--border-black" href="formats/<?php echo $category->alias;?>">Learn More</a></p>
								</div>
							</div>
						</div>
					</div>
					<?php }?>
</div> */?>

<section class="module promo-banner" data-controller="promoBanner">
	<div class="grid-construct-x promo-banner__container">
		<div class="promo-banner__images" style="background-image: url('images/swatchkit-2-desktop.jpg');">
		</div>
		<div class="promo-banner__content">
			<p>How will it look? Order our Swatch Kit to find the perfect paper to display your design.</p>
		</div>
			<div class="promo-banner__btns">
				<a href="/swatch-kit" class="promo-banner__btn">Learn More</a>
			</div>
		</div>
	</section>

	<div class="module center-col">
		<div class="grid-construct">
			<div class="center-col__inner">
				<p>Want to go digital? In need of a single book? Need a thousand or more? No problem. We’ve got you covered.</p>
			</div>
		</div>
	</div>


	<div class="module grid grid--three" data-controller="grid">
		<ul class="grid-construct">
			<li class="grid__column grid__column--center">
				<div class="grid__content">
					<h5 class="grid__header">Print On Demand</h5>
					<div class="grid__text">Print on demand gives you fast and economical printing for small print runs, with <a href="/printing-options">volume order discounts</a> starting at 10 books.</div>
				</div>
			</li>
			<li class="grid__column grid__column--center">
				<div class="grid__content">
					<h5 class="grid__header">Offset</h5>
					<div class="grid__text">Thinking bigger? <a href="/volume-printing">Offset printing</a> saves you money and gives you even more printing options for large print runs.
					</div>
				</div>
			</li>
			<li class="grid__column grid__column--center">
				<div class="grid__content">
					<h5 class="grid__header">Digital</h5>
					<div class="grid__text">Want a cost-effective way to share and sell your book? An <a href="/Others">ebook</a> is affordable, portable, and can be viewed on both the iPad and the Kindle.
					</div>
				</div>
			</li>
		</ul>
	</div>


	<div class="module center-col hidden">
		<div class="grid-construct">
			<div id="compare" class="center-col__inner">
				<h4>Compare All Formats</h4>
			</div>
		</div>
	</div>

	<div class="module compare-table compare-table--colored  hidden">
		<div class="grid-construct">
			<table class="compare-table__table">
				<tbody>
					<tr class="compare-table__tr compare-table__head-row">
						<th class="compare-table__th">Cover Options</th>
						<th class="compare-table__th">Photo Books</th>
						<th class="compare-table__th">Wall Art</th>
						<th class="compare-table__th">Photo Calendars</th>
						<th class="compare-table__th">Others</th>
					</tr>
					<tr class="compare-table__tr">
						<td class="compare-table__td compare-table__row-title">Hard Cover</td>
						<td class="compare-table__td">
							<div class="compare-table__cell-title">
								<a href="photo-books">Photo Books</a>
							</div>
							<div class="compare-table__cell-answer">
								<i class="icon dot--big--black"></i>
							</div>
						</td>
						<td class="compare-table__td">
							<span class="compare-table__cell-title">
								<a href="trade-books">WALL ART</a></span>
							<div class="compare-table__cell-answer">
								<i class="icon dot--big--black"></i>
							</div>
						</td>
						<td class="compare-table__td">
							<span class="compare-table__cell-title">
								<a href="magazine">PHOTO CALENDARS</a></span>
								<div class="compare-table__cell-answer">n/a</div>
						</td>
						<td class="compare-table__td">
							<span class="compare-table__cell-title">
								<a href="ebook">Others</a></span>
								<div class="compare-table__cell-answer">n/a</div>
							</td>
						</tr>
						<tr class="compare-table__tr">
							<td class="compare-table__td compare-table__row-title">Softcover</td>
							<td class="compare-table__td">
								<div class="compare-table__cell-title">
									<a href="photo-books">Photo Books</a>
								</div>
									<div class="compare-table__cell-answer">
										<i class="icon dot--big--black"></i>
									</div>
								</td>
								<td class="compare-table__td">
									<span class="compare-table__cell-title">
										<a href="trade-books">WALL ART</a></span>
										<div class="compare-table__cell-answer">
											<i class="icon dot--big--black"></i>
										</div>
									</td>
									<td class="compare-table__td">
										<span class="compare-table__cell-title"><a href="magazine">PHOTO CALENDARS</a>
										</span></td>
										<td class="compare-table__td">
											<span class="compare-table__cell-title">
												<a href="ebook">Others</a></span>
												<div class="compare-table__cell-answer">n/a</div>
											</td>
										</tr>

															<tr class="compare-table__tr compare-table__head-row">
																<th class="compare-table__th">Paper Quality</th>
																<th class="compare-table__th">Photo Books</th>
																<th class="compare-table__th">WALL ART</th>
																<th class="compare-table__th">PHOTO CALENDARS</th>
																<th class="compare-table__th">Others</th>
															</tr>

															<tr class="compare-table__tr">
																<td class="compare-table__td compare-table__row-title">Art Gloss Paper</td>
																<td class="compare-table__td">
																	<div class="compare-table__cell-title">
																		<a href="photo-books">Photo Books</a></div>
																		<div class="compare-table__cell-answer">
																			<i class="icon dot--big--black"></i>
																	</div>
																</td>
																	<td class="compare-table__td">
																		<span class="compare-table__cell-title">
																		<a href="trade-books">WALL ART</a></span>
																	</td>
																		<td class="compare-table__td">
																			<span class="compare-table__cell-title">
																		<a href="magazine">PHOTO CALENDARS</a></span>
																		<div class="compare-table__cell-answer">
																			<i class="icon dot--big--black"></i>
																		</div>
																		</td>
																	<td class="compare-table__td">
																		<span class="compare-table__cell-title">
																			<a href="ebook">Others</a></span>
																			<div class="compare-table__cell-answer">n/a</div>
																		</td>
																</tr>

															<tr class="compare-table__tr">
																<td class="compare-table__td compare-table__row-title">Art Matt Paper</td>
																<td class="compare-table__td">
																	<div class="compare-table__cell-title">
																		<a href="photo-books">Photo Books</a></div>
																		<div class="compare-table__cell-answer">
																			<i class="icon dot--big--black"></i>
																	</div>
																	</td>
																<td class="compare-table__td">
																	<span class="compare-table__cell-title"><a href="trade-books">Wall Art</a></span>
																</td>
																<td class="compare-table__td">
																	<span class="compare-table__cell-title"><a href="magazine">Photo Calendars</a></span>
																</td>
																<td class="compare-table__td">
																	<span class="compare-table__cell-title"><a href="ebook">Others</a></span>
																	<div class="compare-table__cell-answer">n/a</div></td>
															</tr>
															<tr class="compare-table__tr"><td class="compare-table__td compare-table__row-title">Photo Paper</td><td class="compare-table__td"><div class="compare-table__cell-title"><a href="photo-books">Photo Books</a></div></td><td class="compare-table__td"><span class="compare-table__cell-title"><a href="trade-books">WALL ART</a></span></td>
																<td class="compare-table__td"><span class="compare-table__cell-title"><a href="magazine">PHOTO CALENDARS</a></span><div class="compare-table__cell-answer"><i class="icon dot--big--black"></i></div></td>
																<td class="compare-table__td"><span class="compare-table__cell-title"><a href="ebook">Others</a></span><div class="compare-table__cell-answer">n/a</div>
																</td>
															</tr>
															<tr class="compare-table__tr">
																<td class="compare-table__td compare-table__row-title">Luster Paper</td>
																<td class="compare-table__td"><div class="compare-table__cell-title">
																	<a href="photo-books">Photo Books</a></div>
																	<div class="compare-table__cell-answer"><i class="icon dot--big--black"></i></div>
																</td>
																	<td class="compare-table__td"><span class="compare-table__cell-title">
																		<a href="trade-books">WALL ART</a></span>
																	</td>
																<td class="compare-table__td"><span class="compare-table__cell-title">
																	<a href="magazine">PHOTO CALENDARS</a></span>
																</td>
																<td class="compare-table__td">
																	<span class="compare-table__cell-title"><a href="ebook">Others</a></span>
																	<div class="compare-table__cell-answer">n/a</div>
																</td>
															</tr>
															<tr class="compare-table__tr compare-table__head-row">
																<th class="compare-table__th">Print Quality</th>
																<th class="compare-table__th">Photo Books</th>
																<th class="compare-table__th">Wall Art Books</th>
																<th class="compare-table__th">Photo Calendars</th>
																<th class="compare-table__th">Others</th>
															</tr>

															<tr class="compare-table__tr">
																<td class="compare-table__td compare-table__row-title">Premium Printing</td>
																<td class="compare-table__td"><div class="compare-table__cell-title"><a href="photo-books">Photo Books</a></div><div class="compare-table__cell-answer"><i class="icon dot--big--black"></i></div></td>
																<td class="compare-table__td"><span class="compare-table__cell-title"><a href="trade-books">WALL ART</a></span><div class="compare-table__cell-answer"><i class="icon dot--big--black"></i></div></td>
																<td class="compare-table__td"><span class="compare-table__cell-title"><a href="magazine">PHOTO CALENDARS</a></span><div class="compare-table__cell-answer"><i class="icon dot--big--black"></i></div></td>
																<td class="compare-table__td"><span class="compare-table__cell-title"><a href="ebook">Others</a></span><div class="compare-table__cell-answer">n/a</div></td>
															</tr>



																	<tr class="compare-table__tr compare-table__head-row">
																		<th class="compare-table__th">Creation Tools</th>
																		<th class="compare-table__th">Photo Books</th>
																		<th class="compare-table__th">WALL ART</th>
																		<th class="compare-table__th">PHOTO CALENDARS</th>
																		<th class="compare-table__th">Others</th>
																	</tr>
																		<tr class="compare-table__tr">
																			<td class="compare-table__td"><span class="compare-table__row-title">
																				<a href="create/download">PerfectPics Software</a></span><br>Free downloadable software</td>
																			<td class="compare-table__td"><div class="compare-table__cell-title"><a href="photo-books">Photo Books</a></div><div class="compare-table__cell-answer"><i class="icon dot--big--black"></i></div></td><td class="compare-table__td"><span class="compare-table__cell-title"><a href="trade-books">WALL ART</a></span>
																				<div class="compare-table__cell-answer"><i class="icon dot--big--black"></i></div>
																			</td>
																			<td class="compare-table__td"><span class="compare-table__cell-title">
																				<a href="magazine">PHOTO CALENDARS</a></span>
																				<div class="compare-table__cell-answer"><i class="icon dot--big--black"></i></div></td>
																			<td class="compare-table__td">
																				<span class="compare-table__cell-title"><a href="ebook">Others</a></span>

																			<div class="compare-table__cell-answer"><i class="icon dot--big--black"></i></div>
																		</td>
																	</tr>
																			<tr class="compare-table__tr">
																				<td class="compare-table__td"><span class="compare-table__row-title">
																					<a href="create/upload">PDF Uploader</a></span><br>Upload your PDF</td>
																				<td class="compare-table__td"><div class="compare-table__cell-title">
																					<a href="photo-books">Photo Books</a></div><div class="compare-table__cell-answer"><i class="icon dot--big--black"></i></div></td>
																				<td class="compare-table__td"><span class="compare-table__cell-title">
																					<a href="trade-books">WALL ART</a></span><div class="compare-table__cell-answer"><i class="icon dot--big--black"></i></div></td>
																				<td class="compare-table__td"><span class="compare-table__cell-title">
																					<a href="magazine">PHOTO CALENDARS</a></span><div class="compare-table__cell-answer"><i class="icon dot--big--black"></i></div></td>
																				<td class="compare-table__td"><span class="compare-table__cell-title"><a href="ebook">Others</a></span><div class="compare-table__cell-answer"><i class="icon dot--big--black"></i></div></td>
																			</tr>

																			</tbody></table></div>
																		</div>


																		<div class="module grid grid--five hidden-sm hidden">
																			<ul class="grid-construct buttons-grid-construct">
																				<li class="grid__column grid__column--center" style="width: 360px;">
																				</li>
																				<li class="grid__column grid__column--center">
																					<div class="grid__btns">
																						<a href="products/photobooks" class="btn btn--border-black">Learn More</a>
																					</div>
																				</li>
																				<li class="grid__column grid__column--center">
																					<div class="grid__btns">
																						<a href="products/wall-art" class="btn btn--border-black">Learn More</a>
																					</div>
																				</li>
																				<li class="grid__column grid__column--center">
																					<div class="grid__btns">
																						<a href="products/calendars" class="btn btn--border-black">Learn More</a>
																					</div>
																				</li>
																				<li class="grid__column grid__column--center">
																					<div class="grid__btns">
																						<a href="products/others" class="btn btn--border-black">Learn More</a>
																					</div>
																				</li>
																			</ul>
																		</div>


<!-- end custom view -->
<script type="text/javacsript">
jQuery(document).ready(function($){
// standard on load code goes here with $ prefix
// note: the $ is setup inside the anonymous function of the ready command
//$('.tab-pane').first().addClass('active');
//$('#tabid').tabs("option", "active", index);
// Getter
//var active = $( ".selector" ).tabs( "option", "active" );

// Setter
//$( ".selector" ).tabs( "option", "active", 1 );
jQuery( "#myTabs" ).tabs({ active: 1 });
console.log('it didnt work');
</script>
