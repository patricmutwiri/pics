(function($) {
	$(function(){   
	
		function getUrlParameter(sParam)
		{
			var sPageURL = window.location.search.substring(1);
			var sURLVariables = sPageURL.split('&');
			for (var i = 0; i < sURLVariables.length; i++) 
			{
				var sParameterName = sURLVariables[i].split('=');
				if (sParameterName[0] == sParam) 
				{
					return sParameterName[1];
				}
			}
		}  
		
		jQuery(function(){
			handle_side_menu();
			handle_toolbar();
			responsiveSidebar();
			
			if (getUrlParameter('view') == 'topic') 
			{
				dynamicSection();
			}
		});
		
		function dynamicSection()
		{
			// Global variables
			var token = window.mfbvars.token + "=1";
			var site_path = window.mfbvars.site_path;
				
			jQuery('select[name="jform\[parent_id\]"]').change(function(){    
				topicId = jQuery(this).val();
								
				jQuery('#jform_section_id_chzn')
					.addClass('section-disabled')
					.end()
				;
				
				jQuery.ajax({
					type: "POST",
					url: site_path+"index.php?option=com_faqbookpro&task=topic.dynamicSection&topicid=" + topicId + "&" + token,
					success: function(data) 
					{
						if (data != 'root')
						{
							var json = jQuery.parseJSON(data);
							jQuery('select[name="jform\[section_id\]"]').val(json.section_id);
							jQuery('#jform_section_id_chzn').find('.chzn-single > span').html(json.section_title);
							jQuery('#jform_section_id_chzn').find('.chzn-drop').hide();
						}
						else
						{							
							jQuery('#jform_section_id_chzn')
								.removeClass('section-disabled')
								.end()
							;
							
							jQuery('#jform_section_id_chzn').find('.chzn-drop').show();
						}
					}
				});
				
			});
		}
			
		function handle_toolbar()
		{
			jQuery('.section-content .page-header').show();
			jQuery('h1.page-title').appendTo('.page-header').addClass('pull-left').find('span').remove();
			jQuery('#toolbar').appendTo('.page-header').addClass('pull-right');
			jQuery('#toolbar-new-module').appendTo('#toolbar').css({"display":"inline-block"});
			jQuery('#toolbar-new-custom').prependTo('#toolbar');
		}
		
		function handle_side_menu()
		{
			jQuery("#menu-toggler").on("click",function(){
				jQuery("#mn-sidebar").toggleClass("display");
				jQuery(this).toggleClass("display");
				return false
			});
			var b=jQuery("#mn-sidebar").hasClass("menu-min");
			jQuery("#sidebar-collapse").on("click",function(){
				jQuery("#mn-sidebar").toggleClass("menu-min");
				jQuery(this).find('[class*="fa-"]:eq(0)').toggleClass("fa-angle-double-right");
				b=jQuery("#mn-sidebar").hasClass("menu-min");
				if(b){
					jQuery(".open > .submenu").removeClass("open")
				}
			});
			var a="ontouchend" in document;
			jQuery(".nav-list").on("click",function(g){
				var f=jQuery(g.target).closest("a");
				if(!f||f.length==0){
					return
				}
				if(!f.hasClass("dropdown-toggle")){
					//if(b&&ace.click_event=="tap"&&f.get(0).parentNode.parentNode==this){
					if(b&&f.get(0).parentNode.parentNode==this){
						var h=f.find(".menu-text").get(0);
						if(g.target!=h&&!jQuery.contains(h,g.target)){
							return false
						}
					}
					return
				}
				var d=f.next().get(0);
				if(!jQuery(d).is(":visible")){
					var c=jQuery(d.parentNode).closest("ul");
					if(b&&c.hasClass("nav-list")){
						return
					}
					c.find("> .open > .submenu").each(function(){
						if(this!=d&&!jQuery(this.parentNode).hasClass("active")){
							jQuery(this).slideUp(200).parent().removeClass("open")
						}
					})
				}else{
				}
				if(b&&jQuery(d.parentNode.parentNode).hasClass("nav-list")){
					return false
				}
				jQuery(d).slideToggle(200).parent().toggleClass("open");
				return false
			});
			if (getUrlParameter('view') == 'dashboard' || !getUrlParameter('view')) {
				jQuery('#mn-sidebar ul li:nth-child(1)').addClass("open");
			}
			if (getUrlParameter('view') == 'sections' || getUrlParameter('view') == 'section') {
				jQuery('#mn-sidebar ul li:nth-child(2)').addClass("open");
			}
			if (getUrlParameter('view') == 'topics' || getUrlParameter('view') == 'topic') {
				jQuery('#mn-sidebar ul li:nth-child(3)').addClass("open");
			}
			if (getUrlParameter('view') == 'questions' || getUrlParameter('view') == 'question') {
				jQuery('#mn-sidebar ul li:nth-child(4)').addClass("open");
			}
			if (getUrlParameter('view') == 'about') {
				jQuery('#mn-sidebar ul li:nth-child(6)').addClass("open");
			}
		}
		
		function responsiveSidebar()
		{
			if (jQuery(window).width() < 690) {
				jQuery('#mn-sidebar').addClass('menu-min');
				jQuery('#sidebar-collapse i.fa').addClass('fa-angle-double-right');
			   
				jQuery(document).mouseup(function (e)
				{ 
					if (jQuery(window).width() < 690) {
						var container = jQuery("#mn-sidebar");
						if (!container.is(e.target) && container.has(e.target).length === 0)
						{
							jQuery('#mn-sidebar').addClass('menu-min');
							jQuery('#sidebar-collapse i.fa').addClass('fa-angle-double-right'); 
						}
					}
				});
			}
			else 
			{
			   jQuery('#mn-sidebar').removeClass('menu-min');
			   jQuery('#sidebar-collapse i.fa').removeClass('fa-angle-double-right');
			}
		}
			
		jQuery(window).resize(function() {
			responsiveSidebar();
		});

	})
})(jQuery);