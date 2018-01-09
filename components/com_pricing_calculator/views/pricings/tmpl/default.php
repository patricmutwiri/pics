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

$controller = JControllerLegacy::getInstance('Pricing_calculator');
// sort ordering and direction
$listOrder 	= $this->state->get('list.ordering');
$listDirn 	= $this->state->get('list.direction');
$archived	= $this->state->get('filter.published') == 2 ? true : false;
$trashed	= $this->state->get('filter.published') == -2 ? true : false;
$user 		= JFactory::getUser();
$categories = $this->categories;
$psizes 	= $this->sizes;

$model = $this->getModel();
$config = JFactory::getConfig();
$app = JFactory::getApplication(); ?>
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
					} ?>
					<li role="presentation" class="">
						<a href="#<?php echo $alias ?>" data-category="<?php echo $category->id ?>" id="86-tab" role="tab" data-toggle="tab" aria-controls="home" aria-expanded="true">
							<img src="<?php echo $image ?>" class="img-responsive center-block" width="226px" height="150px"> <br>
							<span class="formats_title"><?php echo $title ?></span></a>
					</li>
				<?php 
				//get prods under each
				$products[$category->alias] = $model->getProducts($category->id);
				$psizes = $model->getPsizes($category->id);
				} ?>
			</ul>
		</div>
	</section>

	<div class="pricingdivsection grid-construct-x tab-content" id="myTabContent">
		<?php 
		foreach ($categories as $key => $category) { 
		$xhidden = ' hidden'; 
			if($key == 0) { $active = "active in"; } else { $active=""; } ?>
				<div class="tab-pane fade <?php echo $active; ?>" role="tabpanel" id="<?php echo $category->alias; ?>" aria-labelledby="86-tab">
					<section class="tabs__panel grid-construct-x" role="tabpanel" id="tabpanel-<?php echo $category->alias; ?>">
						<div class="pricing-table pricing-table--<?php echo $category->alias; ?>">
							<div class="table-responsive pricing-table__desktop">
								<table class="perfecTTable table-responsive table table-bordered pricing-table__table pricing-table__table--<?php echo $category->alias; ?>">
									<thead>
										<tr class="HeaderRow">	
											<th class="HeaderRowTH sizesth" data-sizeid="">&nbsp;</th>
										<?php 
										$groupedp 	= $model->getGroupedProducts($category->id);
										foreach ($groupedp as $key => $thisproduct) { ?>
											<th class="HeaderRowTH sizesth size<?php echo $thisproduct->id ?>">
												<span class="col-xs-12"></span><?php echo ucwords($thisproduct->product_name); ?> </span>
												<span class="hidden col-xs-12">
													<img class="img-responsive" src="<?php echo $thisproduct->image ?>">
												</span>
												<span class="hidden col-xs-12 sizetext">(<?php echo $size->size ?>)</span>
											</th>
										<?php } ?>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($this->papertypes as $key => $papertype) { 
										$psizes = $model->getSizes($papertype->id); ?>
										<tr class="papertypesTR">
											<td class="col-xs-12" colspan="<?php echo count($this->sizes)+1; ?>">
												<span class="col-xs-12"><?php echo strtoupper($papertype->paper_name); ?></span>
												<small class="col-xs-12"><?php echo ucwords($papertype->paper_description); ?></small>
											</td> 
										</tr>
										<?php if(!empty($psizes)) {
										foreach ($psizes as $key => $thissize) { ?>
										<tr class="productsInPaper" id="prodPaper<?php echo $papertype->id ?>">
											<td class="productnamecol prodname prod<?php echo $thissize->range ?>">
												<?php echo trim(str_replace('x', ' x ',ucwords($thissize->range))) ?>
											</td>
											<?php 
											foreach ($groupedp as $key => $thisproduct) { 
											$products = $model->getPaperProducts($papertype->id,$category->id,$thissize->range, $thisproduct->product_name); 
											if(!empty($products)) {
												foreach ($products as $key => $product) {
													$price = $product->price_from; 
													$price2 = $product->price_from2; 
													$niceprice = number_format($product->price_from,2); 
													$niceprice2 = number_format($product->price_from2,2); 
													if(in_array(10, $user->groups)) { 
														$price = $product->price_from2; 
														$niceprice = number_format($product->price_from2,2); 
													} ?>
													<td 
													data-productid="<?php echo $product->id ?>"
													data-productname="<?php echo trim($product->product_name) ?>" 
													data-productsize="<?php echo $product->range ?>" 
													data-categoryid="<?php echo $category->id ?>"
													data-categoryname="<?php echo trim($category->title) ?>"
													data-sizeid="<?php echo $product->range ?>"
													data-papertypeid="<?php echo $papertype->id ?>"
													data-papername="<?php echo trim($papertype->paper_name) ?>"
													data-minimumpages="<?php echo $product->minimumpages ?>"
													data-maximumpages="<?php echo $product->maximumpages ?>"
													data-additionalpp="<?php echo $product->additional ?>"
													data-baseprice="<?php echo $price ?>"
													data-baseprice2="<?php echo $price2 ?>"
													class="baseprice" 
													id="prod<?php echo $product->id.'size'.$thissize->range.'cat'.$category->id ?>">
														<?php echo $niceprice; ?>
													</td>
													<?php
												}
											} else { ?>
												<td 
													data-productname="<?php echo trim($thisproduct->product_name) ?>" 
													data-productsize="<?php echo $thissize->range ?>" 
													data-categoryid="<?php echo $category->id ?>"
													data-categoryname="<?php echo trim($category->title) ?>"
													data-size="<?php echo $thissize->range ?>"
													data-papertypeid="<?php echo $papertype->id ?>"
													data-papername="<?php echo trim($papertype->paper_name) ?>"
													data-minimumpages="<?php echo $thisproduct->minimumpages ?>"
													data-maximumpages="<?php echo $thisproduct->maximumpages ?>"
													data-additionalpp="<?php echo $thisproduct->additional ?>"
													data-baseprice="0" data-baseprice2="0" class="baseprice">
														NA
												</td>
											<?php } } ?>
										</tr>
										<?php } } ?>
									<?php } ?>
									</tbody>
								</table>
								<p class="col-xs-12">
									<small><sup>* </sup>Price value in Kenya Shillings</small><br/>
									<small><sup>* </sup>NA means the product is not available in that size</small>
								</p>
							</div>
						</div>
					</section>
				</div>
			<?php } ?>
	</div>
	<section class="sidebar">
		<div class="popup-notification red"></div>

		<form id="pricecalc" class="calculator" method="post" action="index.php?option=com_pricing_calculator&view=pricings">
			<h4 class="calculator__heading">Pricing Calculator</h4>
			<div class="col-xs-6 xhidden">
				<label class="calculator__label" for="pagecount">Page Count</label>
				<input class="inputcal" required type="number" name="pagecount" value="" min="1" />
			</div>
			<div class="col-xs-6">
				<label class="calculator__label" for="quantity">Quantity</label>
				<input class="inputcal" required type="number" name="quantity" value="1" min="1" max="200" />
			</div>
			<div class="col-xs-12">&nbsp;</div>
			<div class="popup-notificationx red col-xs-12 summary">
				<p>For 100+ Items, <a href="<?php echo JRoute::_('index.php?option=com_contact&view=contact&id=1'); ?>"><strong>contact</strong></a> our Client Services team for full support.</p>
			</div>
			<div class="col-xs-12">&nbsp;</div>
			<div class="col-xs-11 summary">
				<p><strong>Product: </strong><span class="prodname"></span></p>
				<p class="xhidden"><strong>Additional Pages: </strong><span class="adpages">0.00</span></p>
				<p class="xhidden"><strong>Cost of Additional Pages: (Ksh.)</strong><span class="cadpages">0.00</span></p>
				<p><strong>Select Cover</strong></p>
				<?php 
				foreach ($this->covers as $key => $cover) { ?>
					<p class="pcovers">
						<input onchange="document.getElementById('selectedcover').value = '<?php echo $cover->cover_name; ?>'" type="radio" required name="cover" value="<?php echo $cover->cover_name; ?>" />
						<?php echo $cover->cover_name ?> (Ksh. <span class="dbprice"><?php echo number_format($cover->price,2); ?></span>)
					</p>
				<?php } ?>
				<p class="hidden"><strong>Base Price: (Ksh.)</strong><span class="bprice">0.00</span></p>
				<p class="hidden"><strong>Cover Price: (Ksh.)</strong><span class="coverprice">0.00</span></p>
				<p><strong>Total Price: (Ksh.)</strong><span class="tprice">0.00</span></p>
				<p class="p_dprice"><strong>Normal Discount Off: (Ksh.)</strong><span class="dprice">0.00</span></p>
				<?php if(in_array(10, $user->groups)) { ?>
				<p class="p_photoprice"><strong>Photographers Discount Off: (Ksh.)</strong><span class="photoprice">0.00</span></p>
				<?php } ?>
				<p class="p_tenmore"><strong>More than 10 Discount Off: (Ksh.)</strong><span class="tenmore">0.00</span></p>
				<p class="p_couponoff"><strong>Coupon Code Discount Off: (Ksh.)</strong><span class="couponoff">0.00</span></p>
				<p>
					<span style="padding-left: 0; padding-bottom: 4px" class="col-xs-12"><strong>Got a Coupon Code?</strong></span>
					<span class="col-xs-12" style="padding: 0;text-transform: uppercase;">
						<input style="text-transform: uppercase;" type="text" name="couponcode" class="col-xs-12"/>
					</span>
				</p>
				<p>&nbsp;</p>
			</div>
			<div class="topost hidden">  
				<input type="hidden" name="prodname"/>
				<input type="hidden" name="prodid"/>
				<input type="hidden" name="catid"/>
				<input type="hidden" name="baseprice"/>
				<input type="hidden" name="totalprice"/>
				<input type="hidden" name="prodsize"/>
					<?php 
					$phone = 0;
					if($user->id) { 
						$userProfile = JUserHelper::getProfile( $user->id );
						$phone = $userProfile->profile['phone']; 
					} ?>
				<input type="hidden" name="userid" value="<?php echo @$user->id ?>" />
				<input type="hidden" name="email" value="<?php echo @$user->email ?>" />	
				<input type="hidden" name="userphone" value="<?php echo @$phone ?>" />	
				<input type="hidden" name="selectedcover" id="selectedcover"/>
				<input type="hidden" name="task" value="saveorder" />	
			</div>

			<div class="btnlink col-xs-12">
				<button type="submit" id="getstarted" class="col-xs-12 btn btn-primary btn-flat">Place an Order</button>
			</div>
		</form>
	</section>
</div>