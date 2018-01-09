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
$listOrder 	= $this->state->get('list.ordering');
$listDirn 	= $this->state->get('list.direction');
$archived	= $this->state->get('filter.published') == 2 ? true : false;
$trashed	= $this->state->get('filter.published') == -2 ? true : false;
$user 		= JFactory::getUser();
$categories = $this->categories;
$model = $this->getModel();

?>
<style>
.row2 {
	background-color: #e4e4e4;
}
</style>
<div class="pricing-app">
<div  class="grid-construct">
<h1 class="pricing-heading">Pricing Calculator</h1>
</div>
<!-- tabs -->
	<section class="tabs">
		<div class="bs-example bs-example-tabs" data-example-id="togglable-tabs">
			<ul id="myTabs" role="tablist" class="grid-construct-x nav nav-tabs nav-justified">
				<?php 
				$products = array();
				foreach ($categories as $key => $category) { 
					$params = json_decode($category->params);
					$image = $params->image;
					$alias = $category->alias;
					$title = $category->title;
					$title = strtoupper($title);
					if($image == '') {
						$image = JURI::root().'images/pricing/default.jpg';
					}
				?>
					<li role="presentation" class="">
						<a href="#<?php echo $alias ?>" data-category="<?php echo $category->id ?>" id="86-tab" role="tab" data-toggle="tab" aria-controls="home" aria-expanded="true">
							<img src="<?php echo $image ?>" class="img-responsive center-block" width="226px" height="150px"> <br>
							<span class="formats_title"><?php echo $title ?></span></a>
					</li>
				<?php 
				//get prods under each
				$products[$category->alias] = $model->getProducts($category->id);
				} ?>
			</ul>
	<div class="pricingdivsection grid-construct-x tab-content" id="myTabContent">
		<?php 
		foreach ($categories as $key => $category) { 
			if($category->id == 99 || $category->id == 100) {
			 	$xhidden = ' hidden'; 
			 } else { 
			 	$xhidden = ''; 
			 } 
			if($key == 0) { $active = "active in"; } else { $active=""; } ?>
				<div class="tab-pane fade <?php echo $active; ?>" role="tabpanel" id="<?php echo $category->alias; ?>" aria-labelledby="86-tab">
					<section class="tabs__panel grid-construct-x" role="tabpanel" id="tabpanel-<?php echo $category->alias; ?>">
						<div class="pricing-table pricing-table--<?php echo $category->alias; ?>">
							<div class="pricing-table__desktop">
								<table class="table table-stripedx pricing-table__table pricing-table__table--<?php echo $category->alias; ?>">
									<thead>
										<tr>
											<th id="" class="productid hidden"></th>
											<th>Product Name</th>
											<th>Size</th>
											<th>Price From(Ksh.)</th>
											<th class="additional<?php echo $xhidden; ?>">Additional Cost/Page(Ksh.)</th>
											<th class="minimumpages<?php echo $xhidden; ?>">Minimum Pages</th>
											<th class="maximumpages<?php echo $xhidden; ?>">Maximum Pages</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($products[$category->alias] as $key => $product) { ?>
											<tr id="product<?php echo $key ?>" class="prodname pricing-table__tbody__row pricing-table__tbody__row--<?php echo $category->alias; ?>">
												<td id="<?php echo $product->aid; ?>" class="x_pid hidden"><?php echo $product->aid; ?></td>
												<th class="cover-type productname <?php echo $product->product_name ?>"><?php echo $product->product_name ?></th>
												<td class="sizes range"><?php echo $product->range ?></td>
												<td class="baseprice"><?php echo number_format($product->price_from,2); ?></td>
												<td class="additional<?php echo $xhidden; ?>"><?php echo number_format($product->additional,2) ?></td>
												<td class="minimumpages<?php echo $xhidden; ?>"><?php echo number_format($product->minimumpages); ?></td>
												<td class="maximumpages<?php echo $xhidden; ?>"><?php echo number_format($product->maximumpages); ?></td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					</section>
				</div>
			<?php } ?>
	</div>
	<section class="sidebar">
		<div class="popup-notification"></div>
		<form id="pricecalc" class="calculator" method="post" action="">
			<h4 class="calculator__heading">Pricing Calculator</h4>
			<div class="col-xs-6 xhidden">
				<label class="calculator__label" for="pagecount">Page Count</label>
				<input class="inputcal" type="number" name="pagecount" value="" min="1" />
			</div>
			<div class="col-xs-6">
				<label class="calculator__label" for="quantity">Quantity</label>
				<input class="inputcal" type="number" name="quantity" value="1" min="1" />
			</div>
			<div class="col-xs-12">&nbsp;</div>
			<div class="col-xs-11 summary">
				<p><strong>Product: </strong><span class="prodname">0</span></p>
				<p class="xhidden"><strong>Additional Pages: </strong><span class="adpages">0</span></p>
				<p class="xhidden"><strong>Cost of Additional Pages: (Ksh.)</strong><span class="cadpages">0</span></p>
				<p><strong>Base Price: (Ksh.)</strong><span class="bprice">0</span></p>
				<p><strong>Total Price: (Ksh.)</strong><span class="tprice">0</span></p>
			</div>
			<div class="topost hidden">  
				<input type="hidden" name="prodname"/>
				<input type="hidden" name="prodid"/>
				<input type="hidden" name="baseprice"/>
				<input type="hidden" name="totalprice"/>
			</div>
			<div class="btnlink col-xs-12">
				<button id="getstarted" class="col-xs-12 btn btn-primary btn-flat">Get Started</button>
			</div>
		</form>
	</section>
</div>
