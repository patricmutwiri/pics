/* patrick mutwiri
 * custom js
*/
let $ = jQuery;
function toInt(xnumber){
 	if(!xnumber){
 		xnumber = 0;
 		console.log(' made '+xnumber+' one..sha ');
 	}
	var mixedval = jQuery.trim(xnumber);
	mixedval = mixedval.replace(/\,/g,'');
	mixedval = parseInt(mixedval);
  //console.log(mixedval);
	return mixedval;
}

jQuery(function(){
	//upon toggle
	var activetab = null;
	//hide
	jQuery('.p_dprice').hide();
	jQuery('.p_tenmore').hide();
	jQuery('.p_couponoff').hide();

	jQuery('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
		jQuery('.popup-notificationx p').hide();
		jQuery('.xhidden').hide();
	  	var target = jQuery(e.target).attr("href"),
	  		catidactive = jQuery(e.target).attr('data-category'),
	  		activetab = e.target;
	  	if(catidactive == 99 || catidactive == 100) {
	  		jQuery('.xhidden').hide();
	  	} else {
	  		jQuery('.xhidden').show();
	  	}
	  	console.log(target+' active ');
	  	var $this = '', minpages = 1, maxpages = 100,
	  		mintext = jQuery('div.tab-pane.active > tr.productsInPaper > td.baseprice').attr('data-minimumpages'),
	  		maxtext = jQuery('div.tab-pane.active > tr.productsInPaper > td.baseprice').attr('data-maximumpages');
		mintext = toInt(mintext);
		if(mintext) {
			minpages = mintext;
		}
		jQuery('input[name="pagecount"]').attr('value',minpages);
		jQuery('input[name="pagecount"]').attr('min',minpages);
		jQuery('form#pricecalc input[name="catid"]').attr('value',catidactive);
		
		jQuery('table.perfecTTable td.baseprice').on('click',function(){
			$this = jQuery(this),
			pname = $this.attr('data-productname'),
			psize = $this.attr('data-productsize'), 
			prodname = '';
			if (typeof pname != 'undefined') {
				if (typeof psize != 'undefined') {
					prodname = pname+' '+'('+psize+')';
				}
			}
			jQuery('div.tab-pane.active td.black').removeClass('black');
			$this.addClass('black');
			mintext = $this.attr('data-minimumpages');
			mintext = toInt(mintext);
			if(mintext) {
				minpages = mintext;
			}
			jQuery('input[name="pagecount"]').attr('value',minpages);
			jQuery('input[name="pagecount"]').attr('min',minpages);
			jQuery('input[name="pagecount"]').trigger('change');
		});
		
		//coverchange
		jQuery('input[name=cover]').on('change',function(){
			var coverprice = jQuery(this).next('span.dbprice').text();
			coverprice = toInt(coverprice);
			jQuery('span.coverprice').text(coverprice);
			jQuery('input[name="quantity"]').trigger('change');
		});

		//price
		jQuery('input[name="pagecount"], input[name="quantity"], input[name="couponcode"]').on('change',function(){
			var finalprice 	= 0,blacklen = jQuery('div.tab-pane.active td.black').length,
				additional 	= jQuery('div.tab-pane.active td.black').attr('data-additionalpp'),
				minpages 	= jQuery('input[name="pagecount"]').attr('min'),
				maxpages 	= jQuery('input[name="pagecount"]').attr('max'),
				currentpages = jQuery('input[name="pagecount"]').val(),
				addedpages 	= 0,
				addedcost 	= 0, qty = jQuery('input[name="quantity"]').val(),
				total 		= jQuery('div.tab-pane.active td.black').attr('data-baseprice');
			if(!blacklen) {
				alert('Please select a product first. ');
			}
			additional 		= toInt(additional);
			total 			= toInt(total);
			if(currentpages > minpages) {
				addedpages = currentpages-minpages;
				addedcost = addedpages*additional;
				addedcost = parseFloat(addedcost);
			}
			//onQty change
			qty   = parseInt(qty);
			if(qty <= 0) {
				alert('Wacha. Stop it!');
				jQuery('input[name="quantity"]').attr('min',1);
				jQuery('input[name="quantity"]').attr('value',1);
			}
			if(qty > 99) {
				jQuery('.popup-notificationx p').show();
			} else {
				jQuery('.popup-notificationx p').hide();
			}

			if(currentpages <= 0) { 
				alert('Wacha. Stop it!'); 
				jQuery('input[name="pagecount"]').attr('min',minpages);
				jQuery('input[name="pagecount"]').attr('value',minpages);
			}
			//add cover
			var coverrprice = jQuery('span.coverprice').text();
			coverrprice = toInt(coverrprice);
			var minnuscover = addedcost;
			addedcost = addedcost+coverrprice;
			jQuery('span.coverprice').text(coverrprice.toLocaleString(undefined, { minimumFractionDigits: 2 }));
			total = total+addedcost;
			total = total*qty;
			//updates
			var prodid = jQuery('div.tab-pane.active td.black').attr('data-productid'), 
				pname = jQuery('div.tab-pane.active td.black').attr('data-productname'), 
				psize = jQuery('div.tab-pane.active td.black').attr('data-productsize'), 
				prodname = '';
			if (typeof pname != 'undefined') {
				if (typeof psize != 'undefined') {
					prodname = pname+' '+'('+psize+')';
				}
			}
			var fbaseprice = jQuery('div.tab-pane.active td.black').text();
			fbaseprice = fbaseprice.trim();
			if(fbaseprice == 'NA') fbaseprice = 0; 
			jQuery('.summary .prodname').text(prodname);
			jQuery('.adpages').text(addedpages);
			jQuery('.cadpages').text(minnuscover.toLocaleString(undefined, { minimumFractionDigits: 2 }));
			jQuery('.bprice').text(fbaseprice.toLocaleString(undefined, { minimumFractionDigits: 2 }));
			jQuery('.tprice').text(total.toLocaleString(undefined, { minimumFractionDigits: 2 }));

			//to submit
			jQuery('input[name="prodname"]').attr('value',prodname);
			jQuery('input[name="prodid"]').attr('value',prodid);
			jQuery('input[name="baseprice"]').attr('value',jQuery('div.tab-pane.active td.black').attr('data-baseprice'));
			jQuery('input[name="cover"]').attr('value',0);
			jQuery('input[name="totalprice"]').attr('value',total);
			//console.log(total);

			//show Discounts
			catidactive = jQuery('input[name="catid"]').val();
			//getdiscount
			let durl = 'index.php?option=com_pricing_calculator&view=pricings&task=getdessiondiscount';
			jQuery.post(durl,{ 'jspid':prodid, 'jscid':catidactive },function(data){
				data = jQuery(data).find('#sp-component').text();
				data = data.trim();
				data = parseFloat(data);
				if(!fbaseprice) {
					data = 0;
				}
				var normald = parseFloat((data/100) * total);
				total = total - normald;
				total = parseFloat(total);
				jQuery('.dprice').text(normald.toLocaleString(undefined, { minimumFractionDigits: 2 }));
				if(normald <= 0){
					jQuery('.p_dprice').hide();
				} else {
					jQuery('.p_dprice').show();
				}
				console.log(normald);
			});

			//getcoupon
			var ccode = jQuery('input[name="couponcode"]').val(),ccode = ccode.trim(),qty = jQuery('input[name="quantity"]').val();
			if(ccode != '') {
				durl = 'index.php?option=com_pricing_calculator&view=pricings&task=getsessioncoupon';
				jQuery.get(durl,{ 'jspid':prodid, 'jscid':catidactive,'couponcode':ccode },function(data){
					data = jQuery(data).find('#sp-component').text();
					data = data.trim();
					
					var normalc = parseFloat((data/100) * total);
					total = total - normalc;
					total = parseFloat(total);
					if(qty > 10 && normalc > 0) {
						jQuery('.tenmore').text('0.00');
					}
					if(normalc <= 0){
						jQuery('.p_couponoff').hide();
					} else {
						jQuery('.p_couponoff').show();
					}
					jQuery('.couponoff').text(normalc.toLocaleString(undefined, { minimumFractionDigits: 2 }));
					console.log(data)
				});
			}

			//photographer - more than 10
			durl = 'index.php?option=com_pricing_calculator&view=pricings&task=getSessionDiscounts';
			jQuery.get(durl,{ 'pid':prodid, 'cid':catidactive, 'quan':qty },function(data){
				data = jQuery(data).find('#sp-component').text();
				data = data.trim();
				data = JSON.parse(data);

				var photoprice = parseFloat((data.photographers/100) * total);
				total = total - photoprice;
				total = parseFloat(total);
				
				var tenmore = parseFloat((data.d10/100) * total);
				var normalc = jQuery('.couponoff').text();
				normalc = parseFloat(normalc);
				if(qty > 10 && normalc > 0) {
					tenmore = 0;
				}
				if(qty < 10) {
					tenmore = 0;
				}
				total = total - tenmore;
				total = parseFloat(total);
				if(tenmore <= 0){
					jQuery('.p_tenmore').hide();
				} else {
					jQuery('.p_tenmore').show();
				}
				jQuery('.photoprice').text(photoprice.toLocaleString(undefined, { minimumFractionDigits: 2 }));
				jQuery('.tenmore').text(tenmore.toLocaleString(undefined, { minimumFractionDigits: 2 }));
				//console.log(`${data.d10}   ${data.photographers}`);
			});
		});
	});
	if(activetab == null){
		console.log('No Cops');
		jQuery('ul#myTabs li a').first().trigger('click');
	}

});
