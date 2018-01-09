<?php
/**
 * @author
 * @copyright
 * @license
 */

defined("_JEXEC") or die("Restricted access");

//var_dump($this->item);
//error_reporting(0)
?>
<div class="hidden">

<h2>
	<?php echo JText::_('COM_PERFECTPICS_PRODUCTS_PERFECTPICS_PRODUCT_VIEW_PERFECTPICS_PRODUCT_TITLE'); ?>: <i><?php echo $this->item->product_name; ?></i>
	<span class="pull-right" style="font-weight:300; font-size:15px;">[<a href="<?php echo JRoute::_('index.php?option=com_perfectpics_products&task=perfectpics_product.edit&id=' . (int) $this->item->id); ?>"><?php echo JText::_('JACTION_EDIT') ?></a>]</span>
</h2>

<table class="table table-striped hidden">
	<tbody>
			<tr>
				<td>Product_name</td>
				<td><?php echo $this->escape($this->item->product_name); ?></td>
			</tr>
			<tr>
				<td>Price_from</td>
				<td><?php echo $this->escape($this->item->price_from); ?></td>
			</tr>
		<tr>
			<td>ID</td>
			<td><?php echo $this->escape($this->item->id); ?></td>
		</tr>
	</tbody>
</table>
<p><a href="index.php?option=com_perfectpics_products&view=perfectpics_products"><?php echo JText::_('JPREVIOUS'); ?></a></p>

</div>

<div class="">
	<!-- header banner -->
		<section class="module hero-banner hero-banner--cinema" data-controller="heroBanner">
			<div class="grid-construct">
				<div id="hero-main" class="hero-banner__container" data-sm-src="images/photo-books-desktop.jpg" data-lg-src="images/photo-books-desktop.jpg" style="height: 512.865px; background-size: 1611px 512.865px;"><div class="hero-banner__inner">
			<div class="hero-banner__content">
				<h1 class="hero-banner__heading">Make a photo book</h1>
				<hr class="hero-banner__hr">
				<p class="hero-banner__text">Pictures tell a story words never could. Tell yours with a professional-quality photo book.</p>
				<div class="hero-banner__btns"><a href="#tools" class="hero-banner__btn hero-banner__btn--cta js-smooth-scroll">Get Started</a>
				</div>
			</div>
		</div>
	</div>
</div>
</section>
	<!-- end banner -->

	<!-- main area -->
	<section class="module tab-combo tab-combo--three" data-controller="tabCombo" style="height: 890px; overflow: visible;">

			<!-- Nav tabs -->
				<ul class="nav nav-tabs grid-construct-x js-drawer-container" role="tablist">
					<li class="nav-item tab-combo__col js-drawer__header__col">
					<a class="nav-link active" data-toggle="tab" href="#dust" role="tab">
						<div class="tab-combo__content js-drawer__content">
							<img class="tab-combo__option__img center-block" src="http://assets1.blurb.com/pages/website-assets/photo-books/02-formats-imagewrap-227x227-2540dab81ecd751ded10298fc9c85fa4fe0fd8d520356c233a321a14e713d178.jpg" alt="ImageWrap, Hardcover">
							<h3 class="tab-combo__option__head js-tab-combo__option__head">ImageWrap</h3>
							<h4 class="tab-combo__option__subhead">Hardcover</h4>
							<p class="tab-combo__option__text">Starts at <span class="book_price imagewrap square">US $29.99</span></p>
						</div>
					</a>
					</li>
					<li class="nav-item tab-combo__col js-drawer__header__col">
					<a class="nav-link" data-toggle="tab" href="#profile" role="tab">
						<div class="tab-combo__content js-drawer__content">
							<img class="tab-combo__option__img center-block" src="http://assets1.blurb.com/pages/website-assets/photo-books/02-formats-dustjacket-227x227-d03b301f1776dc4c73f4eb3ca01ed2344fe8a589a2c86b259fc90a980d3ec5e9.jpg" alt="Dust Jacket, Hardcover">
							<h3 class="tab-combo__option__head js-tab-combo__option__head">Dust Jacket</h3>
							<h4 class="tab-combo__option__subhead">Hardcover</h4>
							<p class="tab-combo__option__text">Starts at <span class="book_price hardcover square">US $28.99</span></p>
						</div>
					</a>
					</li>
					<li class="nav-item tab-combo__col js-drawer__header__col">
					<a class="nav-link" data-toggle="tab" href="#messages" role="tab">
						<div class="tab-combo__content js-drawer__content">
							<img class="tab-combo__option__img center-block" src="http://assets1.blurb.com/pages/website-assets/photo-books/02-formats-softcover-227x227-2e069364a7b140ddddd2ebe822a4947ab7388b971f4d942b446dabc0304b95d7.jpg" alt="Soft Cover">
							<h3 class="tab-combo__option__head js-tab-combo__option__head">Soft Cover</h3>
							<h4 class="tab-combo__option__subhead">Soft Cover</h4>
							<p class="tab-combo__option__text">Starts at <span class="book_price softcover square">US $14.99</span></p>
						</div>
					</a>
					</li>
				</ul>
				<hr class="tab-combo__hr">
				<!-- Tab panes -->
				<div class="tab-content grid-construct-x js-drawer-container">
				<div class="tab-pane active" id="dust" role="tabpanel">
					<div class="container">
  <h2>Accordion Example</h2>
  <p><strong>Note:</strong> The <strong>data-parent</strong> attribute makes sure that all collapsible elements under the specified parent will be closed when one of the collapsible item is shown.</p>
  <div class="panel-group" id="accordion">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">Collapsible Group 1</a>
        </h4>
      </div>
      <div id="collapse1" class="panel-collapse collapse in">
        <div class="panel-body">Lorem ipsum dolor sit amet, consectetur adipisicing elit,
        sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</div>
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">Collapsible Group 2</a>
        </h4>
      </div>
      <div id="collapse2" class="panel-collapse collapse">
        <div class="panel-body">Lorem ipsum dolor sit amet, consectetur adipisicing elit,
        sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</div>
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse3">Collapsible Group 3</a>
        </h4>
      </div>
      <div id="collapse3" class="panel-collapse collapse">
        <div class="panel-body">Lorem ipsum dolor sit amet, consectetur adipisicing elit,
        sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</div>
      </div>
    </div>
  </div>
</div>
				</div>
				<div class="tab-pane" id="profile" role="tabpanel">
					<div class="tab-combo__toggled-content js-drawer__toggled-content">
	    <div class="tab-combo accordion products"> <!-- required for floating -->
	      <!-- Nav tabs -->
	      <ul class="grid-constuct-x accordion__box nav nav-tabs tabs-left">
	        <li class="accordion__tab active"><a href="#size" class="accordion__tab__title js-accordion-toggle" data-toggle="tab">
						<span class="accordion__tab-number js-accordion-selection">1</span>
						<span class="accordion__tab-option">Size</span>
						<span class="accordion__tab-selected-option js-accordion-selection selected-size">Standard Landscape</span>
					</a></li>
	        <li class="accordion__tab"><a href="#cover"  class="accordion__tab__title js-accordion-toggle" data-toggle="tab">
						<span class="accordion__tab-number js-accordion-selection">2</span>
						<span class="accordion__tab-option">Cover</span>
						<span class="accordion__tab-selected-option js-accordion-selection selected-cover">Dust Jacket</span>
					</a></li>
	        <li class="accordion__tab"><a href="#paper"  class="accordion__tab__title js-accordion-toggle" data-toggle="tab">
						<span class="accordion__tab-number js-accordion-selection">3</span>
						<span class="accordion__tab-option">Paper</span>
						<span class="accordion__tab-selected-option js-accordion-selection selected-paper">Premium Lustre</span>
					</a></li>
	        <li class="accordion__tab"><a href="#p-options"  class="accordion__tab__title js-accordion-toggle" data-toggle="tab">
						<span class="accordion__tab-number js-accordion-selection">4</span>
						<span class="accordion__tab-option">Print Options</span>
						<span class="accordion__tab-selected-option js-accordion-selection selected-paper"> Economy B&amp;W</span>
					</a></li>
	      </ul>
	    </div>

	    <div class="tab-slider__panel js-tab-slider-panel is-recommended">
	      <!-- Tab panes -->
	      <div class="tab-content">
	        <div class="tab-pane active" id="size">
						<section class="module product-highlight">
							<div class="product-highlight__wrapper"><div class="product-highlight--left">
							<div class="product-highlight__recommended js-recommended">
								<img class="icon icon--recommended" src="http://assets1.blurb.com/pages/website-assets/img/getting-started/star-badge.svg" alt="Recommended icon"> Recommended</div>
							<img class="product-highlight--left__img" src="http://assets1.blurb.com/pages/website-assets/getting-started/photo-books/tabcombo_photobooks_01_size_01_7x7-420x300-1dc4a17114bd72c54d6d4f3da6c8346e2d7b9c68a444263d95fffc3e4d112e97.jpg" alt="Small Square">
							<p class="product-highlight__details--title">Small Square</p>
							<ul class="product-highlight__list-new">
								<li>7×7 in, 18×18 cm</li>
								<li>All Photo Books can be sold through Blurb and Amazon</li>
								<li>Available for ebook conversion</li>
							</ul>
						</div>
					</div>
				</section>

				<!-- right panel -->
					<div class="product-highlight--right product-highlight--right--summary product-summary">
						<h2 class="headline--medium product-highlight__head">Your Selection:</h2>
						<p class="product-highlight__details--md">Starts at&nbsp;<a class="spotlight" href="/pricing">
							<span class="js-book book_price hardcover standard_landscape_premium_paper">US $38.99</span></a>&nbsp;for 20 pages. Add&nbsp;<a class="additional_pages_price spotlight" href="/pricing">
								<span class="js-pages book_price additional_pages standard_landscape_premium_paper_lustre">US $0.30</span>
							</a>&nbsp;per additional page.</p>
							<ul class="product-highlight__details--list">
								<li class="product-highlight__details--list-item selected-size">Standard Landscape</li>
								<li class="product-highlight__details--list-item selected-cover">Dust Jacket</li>
								<li class="product-highlight__details--list-item selected-paper">Premium Lustre</li>
							</ul>
							<br>
							<h5 class="typ__letter-case">Continue by using our free tool, or by creating and uploading your own PDF.</h5>
							<div class="product-highlight__cta"><a href="/bookify/online" class="btn btn--mint-on-white js-photo-books-tool" data-tool="bookwright">Create Your Book With Blurb</a>
							</div>
							<div class="btn--divider">— OR —</div>
							<div class="product-highlight__cta">
								<a href="/pdf-to-book" class="btn btn--white-on-black">Create and Upload Your PDF</a>
							</div>
						</div>
				<!-- end right panel -->
					</div>
	        <div class="tab-pane" id="cover">
						<section class="module product-highlight"><div class="product-highlight__wrapper">
							<div class="product-highlight--left">
									<img class="product-highlight--left__img" src="http://assets1.blurb.com/pages/website-assets/getting-started/photo-books/tabcombo_photobooks_02_cover_03_softcover-420x300-e6cf009f31eb60d6c68b589fa455fe13cdb649610077b514e520ddd4150a72f5.jpg" alt="Softcover"><p class="product-highlight__details--title">Softcover</p>
									<ul class="product-highlight__list-new">
										<li>Printed on the front cover, back cover, and spine</li>
										<li>Gloss finish</li>
										<li>No cover flaps</li>
									</ul>
								</div>
					</div>
				</section>

				<!-- right panel -->
					<div class="product-highlight--right product-highlight--right--summary product-summary">
						<h2 class="headline--medium product-highlight__head">Your Selection:</h2>
						<p class="product-highlight__details--md">Starts at&nbsp;<a class="spotlight" href="/pricing">
							<span class="js-book book_price softcover square_premium_paper">US $19.99</span></a>&nbsp;for 20 pages. Add&nbsp;<a class="additional_pages_price spotlight" href="/pricing">
								<span class="js-pages book_price additional_pages square_premium_paper_lustre">US $0.25</span>
							</a>&nbsp;per additional page.</p>
							<ul class="product-highlight__details--list">
								<li class="product-highlight__details--list-item selected-size">Small Square</li>
								<li class="product-highlight__details--list-item selected-cover">Softcover</li>
								<li class="product-highlight__details--list-item selected-paper">Premium Lustre</li>
							</ul><br>
							<h5 class="typ__letter-case">Continue by using our free tool, or by creating and uploading your own PDF.</h5>
							<div class="product-highlight__cta">
								<a href="/bookify/online" class="btn btn--mint-on-white js-photo-books-tool" data-tool="bookwright">Create Your Book With Blurb</a>
							</div>
							<div class="btn--divider">— OR —</div>
							<div class="product-highlight__cta">
								<a href="/pdf-to-book" class="btn btn--white-on-black">Create and Upload Your PDF</a>
							</div>
						</div>
				<!-- end right panel -->
					</div>
	        <div class="tab-pane" id="paper">
						<section class="module product-highlight"><div class="product-highlight__wrapper">
							<div class="product-highlight--left">
									<img class="product-highlight--left__img" src="http://assets1.blurb.com/pages/website-assets/getting-started/photo-books/tabcombo_photobooks_02_cover_03_softcover-420x300-e6cf009f31eb60d6c68b589fa455fe13cdb649610077b514e520ddd4150a72f5.jpg" alt="Softcover"><p class="product-highlight__details--title">Softcover</p>
									<ul class="product-highlight__list-new">
										<li>Printed on the front cover, back cover, and spine</li>
										<li>Gloss finish</li>
										<li>No cover flaps</li>
									</ul>
								</div>
					</div>
				</section>

				<!-- right panel -->
					<div class="product-highlight--right product-highlight--right--summary product-summary">
						<h2 class="headline--medium product-highlight__head">Your Selection:</h2>
						<p class="product-highlight__details--md">Starts at&nbsp;<a class="spotlight" href="/pricing">
							<span class="js-book book_price softcover square_premium_paper">US $19.99</span></a>&nbsp;for 20 pages. Add&nbsp;<a class="additional_pages_price spotlight" href="/pricing">
								<span class="js-pages book_price additional_pages square_premium_paper_lustre">US $0.25</span>
							</a>&nbsp;per additional page.</p>
							<ul class="product-highlight__details--list">
								<li class="product-highlight__details--list-item selected-size">Small Square</li>
								<li class="product-highlight__details--list-item selected-cover">Softcover</li>
								<li class="product-highlight__details--list-item selected-paper">Premium Lustre</li>
							</ul><br>
							<h5 class="typ__letter-case">Continue by using our free tool, or by creating and uploading your own PDF.</h5>
							<div class="product-highlight__cta">
								<a href="/bookify/online" class="btn btn--mint-on-white js-photo-books-tool" data-tool="bookwright">Create Your Book With Blurb</a>
							</div>
							<div class="btn--divider">— OR —</div>
							<div class="product-highlight__cta">
								<a href="/pdf-to-book" class="btn btn--white-on-black">Create and Upload Your PDF</a>
							</div>
						</div>
				<!-- end right panel -->
					</div>
	        <div class="tab-pane" id="p-options">Printing Options Tab.</div>
	      </div>
	    </div>

	    <div class="clearfix"></div>

	  </div>
				</div>
				<div class="tab-pane" id="messages" role="tabpanel">
					messages
				</div>
				</div>


	</section>

	<!-- module 3 -->
		<div class="module grid grid--three" data-controller="grid">
			<ul class="grid-construct">
				<li class="grid__column grid__column--center">
					<div class="grid__content">
						<div class="grid__icon"><i class="icon icon--paper-types"></i></div>
						<h5 class="grid__header typ__letter-case">Paper Samples</h5>
						<div class="grid__text">The right paper makes all the difference. Order a <a href="/swatch-kit">swatch-kit</a> to see for yourself.</div>
					</div>
				</li>
				<li class="grid__column grid__column--center">
					<div class="grid__content"><div class="grid__icon"><i class="icon pricing"></i></div>
					<h5 class="grid__header typ__letter-case">Pricing Calculator</h5>
					<div class="grid__text">Want to cost out your project before you begin? Use our handy <a href="/pricing">pricing calculator</a>.
					</div>
				</div>
				</li>
				<li class="grid__column grid__column--center">
					<div class="grid__content">
						<div class="grid__icon"><i class="icon truck-shipping"></i></div>
						<h5 class="grid__header typ__letter-case">Shipping Calculator</h5>
						<div class="grid__text">Regardless of where you’re shipping your book, you can <a href="/shipping">calculate</a> the cost upfront.</div>
					</div>
				</li>
			</ul>
		</div>
	<!-- end module 3 -->

	<!-- product highlight -->
		<section class="module product-highlight hidden" id="tools">
			<div class="grid-construct">
				<div class="module center-col">
					<h4>Get started with one of our free tools</h4>
				</div><div class="product-highlight__wrapper">
					<div class="product-highlight--left">
						<img src="images/bookwright-photobooks.jpg" alt="BookWright">
					</div>
					<div class="product-highlight--right"><br><br><br>
						<h2 class="headline--small">PerfectPics Software</h2>
						<p class="product-highlight__copy">BookWright’s easy-to-use features make it easy for you to create and customize your book.</p>
						<ul>
							<li>Drag and drop your images into layouts</li>
							<li>Choose from a range of pre-designed <a href="/templates">templates</a> or customize your own layouts</li>
							<li>Publish any kind of print book, magazine, or ebook</li>
						</ul>
						<p class="product-highlight__copy">
							<a href="/bookwright">Learn More</a></p>
							<div class="product-highlight__cta">
								<a href="download" class="btn btn--border-black js-mobile-download__btn" data-tool="bookwright">Download Software</a>
							</div>
						</div>
					</div>
				</div>
			</section>
	<!-- end product highlight -->

	<!-- grid two -->
	<div class="module grid grid--two hidden" data-controller="grid">
		<ul class="grid-construct">
			<li class="grid__column">
				<div class="grid__content" style="max-width: 424px;">
					<img class="grid__img" src="http://assets1.blurb.com/pages/website-assets/tools/bookify-2col-e672d9230698171475e12765a5dd9177228b03b175f9c1557d08d57d30e56cd0.jpg" alt="Bookify" style="max-width: 424px;">
					<h5 class="grid__header" style="max-width: 424px;">Make your book online</h5>
					<div class="grid__text" style="max-width: 424px;">
						<ul>
							<li>Make a book quickly and easily without downloading anything</li>
							<li>Perfect for creating a book using your Facebook and Instagram photos, or those saved to your computer</li>
						</ul>
					</div>
					<div class="grid__text" style="max-width: 424px;">
						<a href="/online-photo-books">Learn More</a>
					</div>
				</div>
				<div class="grid__btns" style="max-width: 424px;">
					<a href="/bookify/online" class="btn btn--border-black js-mobile-download__btn" data-tool="bookify">Make a Book Online</a>
				</div>
			</li>
			<li class="grid__column">
				<div class="grid__content" style="max-width: 424px;">
					<img class="grid__img" src="http://assets1.blurb.com/pages/website-assets/tools/pdftobook-2col-f0f85d07e4c451bcbad6148e57e02f775d3de96e8894083700b1e6da09a83da4.jpg" alt="PDF to Book" style="max-width: 424px;">
					<h5 class="grid__header" style="max-width: 424px;">PDF to Book</h5>
					<div class="grid__text" style="max-width: 424px;">
						<ul>
							<li>Upload your PDF to make your book</li>
							<li>Use our <a href="/make/pdf_to_book/booksize_calculator">specifications calculator</a> to fine-tune your PDF to a Blurb-friendly book size</li>
						</ul>
					</div>
					<div class="grid__text" style="max-width: 424px;">
						<a href="/pdf-to-book">Learn More</a>
					</div>
				</div>
				<div class="grid__btns" style="max-width: 424px;"><a href="/pdf-to-book" class="btn btn--border-black">Upload Your PDF</a>
				</div>
			</li>
		</ul>
	</div>
	<!-- end grid two -->

	<!-- ned help -->
		<div class="v-bg-white-smoke ">
			<div class="module center-col">
				<div class="grid-construct">
					<div class="center-col__inner"><br><h4>Need help getting started?</h4></div>
				</div>
			</div>
			<div class="module grid grid--three" data-controller="grid">
				<ul class="grid-construct">
					<li class="grid__column grid__column--center">
						<div class="grid__content">
							<h5 class="grid__header typ__letter-case">Start with a template</h5>
							<div class="grid__text">Use one of our free easy-to-use photo book <a href="/templates">templates</a> to make your next book beautiful and professional.</div>
						</div>
					</li>
					<li class="grid__column grid__column--center">
						<div class="grid__content">
							<h5 class="grid__header typ__letter-case">Hire an expert</h5>
							<div class="grid__text">
								<a href="/dreamteam/collaborators">Blurb-vetted professionals</a> are available at any stage of your project—editing, design, illustration, and more.</div>
							</div>
						</li>
						<li class="grid__column grid__column--center">
							<div class="grid__content">
							<h5 class="grid__header typ__letter-case">Learn the basics at book camp</h5>
							<div class="grid__text">Watch a range of <a href="/book-camp">short videos</a> for tips on how to organize your photos and make your book high on design.</div>
						</div>
					</li>
				</ul>
			</div>
		</div>
	<!-- end need help -->
	<!-- end main area -->
</div>
