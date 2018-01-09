/* patrick
 * custom js
*/
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
	//jQuery('.pricing-app li').first().addClass('active');
	//upon toggle
	jQuery('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
		jQuery('.xhidden').hide();
	  	var target = jQuery(e.target).attr("href"); // activated tab
	  	//cat id
	  	var catidactive = jQuery(e.target).attr('data-category');
	  	if(catidactive == 99 || catidactive == 100) {
	  		jQuery('.xhidden').hide();

	  	} else {
	  		jQuery('.xhidden').show();
	  	}
	  	console.log(target+' active ');
	  	var $this = '', minpages = 1, maxpages = 100;
		var mintext = jQuery('div.tab-pane.active tr#product0 > td.minimumpages').html();
		var maxtext = jQuery('div.tab-pane.active tr#product0 > td.maximumpages').html();
		mintext = toInt(mintext);
		if(mintext) {
			minpages = mintext;
		}
		jQuery('input[name="pagecount"]').attr('value',minpages);
		jQuery('input[name="pagecount"]').attr('min',minpages);
		jQuery('input[name="pagecount"]').attr('min',minpages);

		jQuery('div.tab-pane.active th.productname').on('click',function(){
			$this = jQuery(this);
			var thisparent = $this.parents('tr').attr('id');
			jQuery('div.tab-pane.active tr.black').removeClass('black');
			//$this.addClass('black');
			jQuery('div.tab-pane.active tr#'+thisparent).addClass('black');
			mintext = jQuery('div.tab-pane.active tr#'+thisparent+' > td.minimumpages').html();
			mintext = toInt(mintext);
			if(mintext) {
				minpages = mintext;
			}
			jQuery('input[name="pagecount"]').attr('value',minpages);
			jQuery('input[name="pagecount"]').attr('min',minpages);
			jQuery('input[name="pagecount"]').trigger('change');
		});
		//price
		jQuery('input[name="pagecount"], input[name="quantity"]').on('change',function(){
			var finalprice 	= 0,blacklen = jQuery('div.tab-pane.active tr.black').length,
				additional 	= jQuery('div.tab-pane.active tr.black').find('td.additional').html(),
				minpages 	= jQuery('input[name="pagecount"]').attr('min'),
				maxpages 	= jQuery('input[name="pagecount"]').attr('max'),
				currentpages = jQuery('input[name="pagecount"]').val(),
				addedpages 	= 0,
				addedcost 	= 0, qty = jQuery('input[name="quantity"]').val(),
				total 		= jQuery('div.tab-pane.active tr.black').find('td.baseprice').html();
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
			if(currentpages <= 0) {
				alert('Wacha. Stop it!');
				jQuery('input[name="pagecount"]').attr('min',minpages);
				jQuery('input[name="pagecount"]').attr('value',minpages);
			}
			total = total+addedcost;
			total = total*qty;
			//updates
			var prodid = jQuery('div.tab-pane.active tr.black .x_pid').attr('id'), pname = jQuery('div.tab-pane.active tr.black .productname').html(), psize = jQuery('div.tab-pane.active tr.black .range').html(), prodname = '';
			if (typeof pname != 'undefined') {
				if (typeof psize != 'undefined') {
					prodname = pname+' '+'('+psize+')';
				}
			}
			jQuery('.summary .prodname').text(prodname);
			jQuery('.adpages').text(addedpages);
			jQuery('.cadpages').text(addedcost.toLocaleString(undefined, { minimumFractionDigits: 2 }));
			jQuery('.bprice').text(jQuery('div.tab-pane.active tr.black').find('td.baseprice').html());
			jQuery('.tprice').text(total.toLocaleString(undefined, { minimumFractionDigits: 2 }));
			//to submit
			jQuery('input[name="prodname"]').attr('value',prodname);
			jQuery('input[name="prodid"]').attr('value',prodid);
			jQuery('input[name="baseprice"]').attr('value',jQuery('.bprice').text());
			jQuery('input[name="totalprice"]').attr('value',total);

			console.log(total);
		});
	});
});
