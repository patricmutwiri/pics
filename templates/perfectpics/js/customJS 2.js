var pics = jQuery.noConflict();
//no conflict jquery above
pics(document).ready(function(){
	pics('.softwaretext').click(function(){
		pics('#overlayBody').fadeIn('fast');
		});
	pics('#overlayBody').click(function(){
		pics(this).fadeOut('fast');
		})
	pics('#overlayBody .closeBar span').click(function(){
		pics('#overlayBody').fadeOut('fast');
		})
	pics('#overlayBody #contentHolder .downloadBar .macDownload a').click(function(){
		pics('#overlayBody').delay(500).fadeOut('fast');
		console.log('some action started');
		})
	pics('#overlayBody #contentHolder .downloadBar .windowsDownload a').click(function(){
		pics('#overlayBody').delay(500).fadeOut('fast');
		console.log('another action started');
		})
	//pics('#overlayBody #contentHolder div.submitbtn').click(function(){
		//pics('#overlayBody #contentHolder div.submitbtn td.loading').css('display','block');
		//})
		//get values from the fields
		var nameField = pics('#nameField');//name
		var emailAddress = pics('#emailField');//email address
		//validate the fields
		nameField.blur(validateName);
		emailAddress.blur(validateEmail);
		//validate functions
		function validateName(){
			if(nameField.val().length < 5){
				pics('span.errorName').text('Your name is too short');
				}
			else if(nameField.val().length >= 5){
				//pics('span.errorName').text('');
				}
			}
		function validateEmail(){
			var a = emailAddress.val();
			var regxp = /^([a-zA-Z0-9_.-])+@(([a-zA-Z0-9-])+.)+([a-zA-Z]{2,4})+$/;
			if(regxp.test(a)){
				pics('span.errorEmail').text('Please enter a valid email');
				}
			else {
				pics('span.errorEmail').text('');
				}
			}
		//
		pics('#overlayBody #contentHolder div.submitbtn td.success').ajaxStart(function(){
			pics('#overlayBody #contentHolder div.submitbtn td.loading').css('display','block');
							pics('#overlayBody #contentHolder div.submitbtn td.textHere').text('Submitting');
		});
		pics('#overlayBody #contentHolder div.submitbtn td.success').ajaxComplete(function(){
			pics('#overlayBody #contentHolder div.submitbtn td.loading').css('display','none');
							pics('#overlayBody #contentHolder div.submitbtn td.textHere').text('Thank you for your submission');
							pics('#overlayBody #contentHolder div.downloadBar').delay(500).fadeIn('normal');
		});
		//submit information on clicking submit
		pics('#overlayBody #contentHolder div.submitbtn').click(function(){
			var nameFieldVal = nameField.val();//name
			var emailAddressVal = emailAddress.val();//email address
			if(nameFieldVal==='' && emailAddressVal===''){
				pics('span.errorName').text('Please enter your name');
				pics('span.errorEmail').text('Please enter your email address');
				}
			else if(nameFieldVal===''){
				pics('span.errorName').text('Please enter your name');
				}
			else if(emailAddressVal===''){
				pics('span.errorEmail').text('Please enter your email address');
				}
			else{
				pics.post('register.php', {
						nameField:nameFieldVal,
						emailField:emailAddressVal
						},
						function(data){
							pics('#overlayBody #contentHolder div.submitbtn td.success').fadeIn('normal');
							}
					);
			}
			})

			// toggle subscribe section
			var subscribe = jQuery.noConflict();
			//no conflict jquery above
				subscribe('.expander').live('click', function () {
					// console.log('clicked');
				subscribe('#TableData').slideToggle();
			});

			// hide inactive products tab
			var products_tab = jQuery.noConflict();
			products_tab(document).ready(function(){
			//no conflict jquery above
				/*products_tab( "#photo-calendars" ).hide();
				products_tab( "#wall-art" ).hide();
				products_tab( "#ebooks" ).hide();*/
			});

})
