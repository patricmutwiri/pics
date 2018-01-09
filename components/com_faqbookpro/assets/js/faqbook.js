(function($) {
	$(function(){  
		
		var token = window.fbpvars.token + "=1";
		var site_path = window.fbpvars.site_path;
		var page_view = window.fbpvars.page_view;
		var page_title = window.fbpvars.page_title;		
		var sectionId = window.fbpvars.sectionId;
		var topicId = window.fbpvars.topicId;
		var activeTopic = window.fbpvars.activeTopic;
		var leftnav = window.fbpvars.leftnav;
		var loadAllTopics = window.fbpvars.loadAllTopics;
		var ajax_request;
		var paging_ajax_request;
		var title = window.fbpvars.title;
		var url = window.location.href;
		
		// Text
		var thank_you_up = window.fbpvars.thank_you_up;
		var thank_you_down = window.fbpvars.thank_you_down;
		var already_voted = window.fbpvars.already_voted;
		var why_not = window.fbpvars.why_not;
		var incorrect_info = window.fbpvars.incorrect_info;
		var dont_like = window.fbpvars.dont_like;
		var confusing = window.fbpvars.confusing;
		var not_answer = window.fbpvars.not_answer;
		var too_much = window.fbpvars.too_much;
		var other = window.fbpvars.other;
		var error_voting = window.fbpvars.error_voting;
		
		if (leftnav)
		{
			$(window).load(function () {
			  				
				// Fix leftnav height on window load
				if (page_view == 'topic' || page_view == 'question') 
				{
					// Show left navigation before calculating height
					$('.fbpLeftNavigation_core').removeClass('fbp-hidden');
					
					// Fix left navigation topics height
					if ($('#liid'+topicId).hasClass('NavLeftUL_endpoint'))
					{
						var parent_ul = $('#liid'+topicId).parent();
					}
					else
					{
						var parent_ul = $('#liid'+topicId).find('ul:first');
					}
					
					var vheight = $(parent_ul).height();
					$('.fbpLeftNavigation_root').css({"height":vheight+"px"});
				
					// Hide left navigation
					$('.fbpLeftNavigation_core').addClass('fbp-hidden');
					$('.show_menu').find('a:first').removeClass('btn-fbpactive');
				} 
				else if (page_view == 'section') 
				{
					// Fix left navigation topics height
					$('.fbpLeftNavigation_root').css({"height":"auto"});
				}
			});
		}
					
		function loadHome(event, href)
		{ 
			$('.show_menu').removeClass('fbp-shown');	
				  	
			// Check if there is a pending ajax request
			if(typeof ajax_request !== "undefined")
				ajax_request.abort();
				
			$('.fbpContent_root').html('');
			$('#fbpcontent').find('.fbp_loader').show();

			ajax_request = $.ajax({
				type: "POST",
				url: site_path+"index.php?option=com_faqbookpro&task=section.getContent&id=" + sectionId + "&" + token,
				beforeSend: function() {
					 // Change url dynamically
					window.history.pushState({}, document.title, href);
				},
				success: function(msg) {
					$(".fbpTopNavigation_wrap").removeClass('NavTopULloading');  
					$('#top_liid_home').addClass('NavTopUL_lastChild'); 
					$('#fbpcontent').find('.fbp_loader').hide();
					$(".fbpContent_root").html(msg);
					
					// Change browser title dynamically
					$(document).attr('title', page_title);
										
					// Activate actions in questions
					activateQuestions();
					
					// Show left navigation
					$('.fbpLeftNavigation_core').removeClass('fbp-hidden');
					$('.show_menu').find('a:first').addClass('btn-fbpactive');
					
					// Remove loader from top navigation
					$(".fbpTopNavigation_wrap").removeClass('NavTopULloading');
				}
			});
		}
		
		// Reset left navigation topics
		function resetTopics(event, href)
		{
			// Fix left navigation topics height
			$('.fbpLeftNavigation_root').css({"height":"auto"});
			
			$('#NavLeftUL').addClass('ul_loading');
			
			var li_count = $('.fbpTopNavigation_root li.NavTopUL_parent').length;
			var slide_count = parseInt(li_count);		
			var righty = $('.fbpLeftNavigation_wrap');		
			var move_right = slide_count * 100;
		
			// Remove all li items after home
			$('#top_liid_home').nextAll('li').remove();
					
			// Keep track of left navigation animation to prevent double clicks
			if ($('.fbpLeftNavigation_wrap:animated').length == 0 && !$('.fbpTopNavigation_wrap').hasClass('NavTopULloading'))
			{ 
				// Add loader in top navigation
				$(".fbpTopNavigation_wrap").addClass('NavTopULloading');
				
				righty.animate(
					{left:"+="+move_right+"%"}, 
					{queue: false, complete: function(){ 
						$('#NavLeftUL ul').removeClass('NavLeftUL_expanded');
						$('#top_liid_home').addClass('NavTopUL_lastChild');	
						$('#NavLeftUL').removeClass('ul_loading');
						$('.NavLeftUL_item').removeClass('li_selected'); 
						
						loadHome(event, href);
						
						if (activeTopic > 0)
						{
							$('#liid'+activeTopic).addClass('li_selected');	
						}
					} 
				});
			}
		}
		
		// Add topic in top navigation
		function addTopNavTopic(currentActiveTopic)
		{
			if (currentActiveTopic > 0)
			{
				if (page_view != 'section') 
				{
					// Active topnav li
					var parent_ul_class = $('#liid'+currentActiveTopic).parent('ul').attr('class');
					var ul_level = parent_ul_class.split(" ")[1];
					var ul_level_num = ul_level.substring(ul_level.lastIndexOf('level') + 5);
					var parents_num = parseInt(ul_level_num);
					var first_parent_text = $('#liid'+currentActiveTopic).parent().parent().find('> .NavLeftUL_anchor span.catTitle').text();
					var first_parent_id = $('#liid'+currentActiveTopic).parent('ul').parent('li').attr('id');
					
					$('.fbpTopNavigation_root li.NavTopUL_firstChild').removeClass('NavTopUL_lastChild');
				  
					// Add topnav li's   
					var $id = $('#'+first_parent_id);
					var $li = $('#'+first_parent_id);
					
					function findParents()
					{
						$id = $id.parent().parent();
						$li = $li.parent('ul').parent('li');
						var prev_parent_text = $id.find('> .NavLeftUL_anchor span.catTitle').text();     
						var prev_parent_id = $li.attr('id');
						
						// Add topic to breadcrumbs
						$('<li id="top_'+prev_parent_id+'" class="NavTopUL_item NavTopUL_topic NavTopUL_parent"><i class="fa fa-caret-right"></i>&nbsp;&nbsp;<a class="NavTopUL_link" href="#" onclick="return false;">'+prev_parent_text+'</a></li>').insertAfter('li.NavTopUL_firstChild');
					}
				 
					// Only for level1+ ul's
					if (ul_level_num > 0)
					{
						for (var i = 1; i < parents_num; i++) 
						{
							findParents();  
						}
						
						// Add lastChild parent li in top navigation
						// Endpoint topic - add class NavTopUL_lastChild
						if ($('#liid'+topicId).hasClass('NavLeftUL_endpoint'))
						{
							$('.fbpTopNavigation_root').append($('<li id="top_'+first_parent_id+'" class="NavTopUL_item NavTopUL_topic NavTopUL_parent NavTopUL_lastChild"><i class="fa fa-caret-right"></i>&nbsp;&nbsp;<a class="NavTopUL_link" href="#" onclick="return false;">'+first_parent_text+'</a></li>'));	
						}
						// Not endpoint topic - don't add class NavTopUL_lastChild
						else
						{
							$('.fbpTopNavigation_root').append($('<li id="top_'+first_parent_id+'" class="NavTopUL_item NavTopUL_topic NavTopUL_parent"><i class="fa fa-caret-right"></i>&nbsp;&nbsp;<a class="NavTopUL_link" href="#" onclick="return false;">'+first_parent_text+'</a></li>'));	
						}	
					}
				
					// Add lastChild li in top navigation
					var last_topic_text = $('#liid'+topicId).find('> .NavLeftUL_anchor span.catTitle').text();
					
					// Endpoint topic - don't add class NavTopUL_parent
					if ($('#liid'+topicId).hasClass('NavLeftUL_endpoint'))
					{
						$('.fbpTopNavigation_root').append($('<li id="top_liid'+topicId+'" class="NavTopUL_item NavTopUL_topic NavTopUL_endpoint NavTopUL_lastChild"><i class="fa fa-caret-right"></i>&nbsp;&nbsp;<a class="NavTopUL_link" href="#" onclick="return false;">'+last_topic_text+'</a></li>'));
					}
					// Non endpoint topic - add class NavTopUL_parent
					else
					{
						$('.fbpTopNavigation_root').append($('<li id="top_liid'+topicId+'" class="NavTopUL_item NavTopUL_topic NavTopUL_parent NavTopUL_lastChild"><i class="fa fa-caret-right"></i>&nbsp;&nbsp;<a class="NavTopUL_link" href="#" onclick="return false;">'+last_topic_text+'</a></li>'));
					}
				}
			}
		}
		
		// Highlight topic in left navigation
		function highlightLeftNavTopic(currentActiveTopic)
		{
			if (currentActiveTopic > 0)
			{
				// Active leftnav li
				$('.NavLeftUL_item').removeClass('li_selected');  
				$('#liid'+currentActiveTopic).addClass('li_selected');   
				
				// Active leftnav ul
				$('#liid'+currentActiveTopic).parents('ul.NavLeftUL_sublist').addClass('NavLeftUL_expanded');   
				$('#liid'+currentActiveTopic).find('ul.NavLeftUL_sublist:first').addClass('NavLeftUL_expanded');  
				var parent_ul_class = $('#liid'+currentActiveTopic).parent('ul').attr('class');
				
				if (leftnav > 0) 
				{
					if (page_view != 'section') 
					{
						var ul_level = parent_ul_class.split(" ")[1];
						var ul_level_num = ul_level.substring(ul_level.lastIndexOf('level') + 5);
						
						// Endpoint topic - we don't want to see the children topics
						if ($('#liid'+currentActiveTopic).hasClass('NavLeftUL_endpoint'))
						{
							var move_level_num = parseInt(ul_level_num, 10); 
						}
						// We want to see the chidlren topics of selected topic, so we need one more level
						else
						{
							var move_level_num = parseInt(ul_level_num, 10) + 1; 
						}
				
						var move_ul = parseInt(move_level_num, 10)*100;
						$('.fbpLeftNavigation_wrap').css({"left":"-"+move_ul+"%"});   	
					}
				}
			}
		}
		
		if (page_view == 'topic' || page_view == 'question')
		{	
			highlightLeftNavTopic(topicId);
			addTopNavTopic(topicId);
		}
		else if (page_view == 'section' && activeTopic)
		{
			highlightLeftNavTopic(activeTopic);
			addTopNavTopic(activeTopic);
		}
		
		// Activate actions in questions
		function activateQuestions()
		{
			// Toggle FAQ in Category
			$('#fbpExtended').on('click', '.topic_faqToggleLink', function(event)
			{	
				event.stopImmediatePropagation();
				event.preventDefault();
				
				var this_faqid = $(this).attr('id');
				var faq_id = this_faqid.split("_").pop(0);

				if ($('#faq_'+faq_id).hasClass('faq_open')) {
					$('#faq_'+faq_id).removeClass('faq_open');
				} else {
					$('#faq_'+faq_id).addClass('faq_open');
					
					// Add Hits
					addHit(faq_id, event);
				}
			});

			// FAQ thumb_up voting
			var thumbs_up = $('.thumbs_up').on('click', function(event)
			{	
				event.stopImmediatePropagation();
				event.preventDefault();
				
				var this_faqid = $(this).attr('id');
				var faq_id = this_faqid.split("up_").pop(0);
					
				var thumbs_up_class = $('#thumbs_up_'+faq_id).attr('class');
				var thumbs_down_class = $('#thumbs_down_'+faq_id).attr('class');
				$('#a_w_'+faq_id+' .vote_result_text').remove();
				$('#a_w_'+faq_id+' .vote_exists_text').remove();
				if (thumbs_down_class.indexOf("loading") == -1 && thumbs_up_class.indexOf("loading") == -1) {
					faqThumbsUp(faq_id, event);
				}
			});
		
			// FAQ thumb_down voting
			var thumbs_down = $('.thumbs_down').on('click', function(event)
			{	
				event.stopImmediatePropagation();
				event.preventDefault();
				
				var this_faqid = $(this).attr('id');
				var faq_id = this_faqid.split("down_").pop(0);
				
				var thumbs_up_class = $('#thumbs_up_'+faq_id).attr('class');
				var thumbs_down_class = $('#thumbs_down_'+faq_id).attr('class');
				$('#a_w_'+faq_id+' .vote_result_text').remove();
				$('#a_w_'+faq_id+' .vote_exists_text').remove();
				if (thumbs_up_class.indexOf("loading") == -1 && thumbs_down_class.indexOf("loading") == -1) {
					faqThumbsDown(faq_id, event);
				}
			});
		}
		activateQuestions();
		
		// Load topic endpoint
		function loadEndpoint(id, this_liid, href_link, cat_title)
		{
			$('#NavLeftUL').addClass('ul_loading');
		
			// Check if there is a pending ajax request
			if(typeof ajax_request !== "undefined")
				ajax_request.abort();
			
			$('.NavLeftUL_item').removeClass('li_loading'); 
		
			if (loadAllTopics == 1 || (loadAllTopics == 0 && $('#'+this_liid).hasClass('NavLeftUL_endpoint')))
			{
				if (loadAllTopics == 1 && !$('#'+this_liid).hasClass('NavLeftUL_endpoint'))
				{
					$('.fbpContent_root').html('');
					$('#fbpcontent').find('.fbp_loader').show();
				}
				
				ajax_request = $.ajax({
					type: "POST",
					url: site_path+"index.php?option=com_faqbookpro&task=topic.getContent&id=" + id + "&" + token,
					beforeSend: function() {
						$('#'+this_liid).addClass('li_loading'); 
						window.history.pushState({}, document.title, href_link);
					},
					success: function(msg) {
						// Add 'show menu' button
						$('.show_menu').addClass('fbp-shown');	
						
						$(".fbpTopNavigation_wrap").removeClass('NavTopULloading');  
						$('#NavLeftUL').removeClass('ul_loading');
						$('#'+this_liid).removeClass('li_loading'); 
						$('.NavLeftUL_item').removeClass('li_selected'); 
						$('#fbpcontent').find('.fbp_loader').hide();
						$(".fbpContent_root").html(msg);
						$('#'+this_liid).addClass('li_selected'); 
						
						// Change browser title dynamically
						$(document).attr('title', cat_title);
														
						activateQuestions();
						
						// Hide left navigation
						if ($('#'+this_liid).hasClass('NavLeftUL_endpoint'))
						{
							$('.fbpLeftNavigation_core').addClass('fbp-hidden');
							$('.show_menu').find('a:first').removeClass('btn-fbpactive');
						}
					}
				});
			}
			else
			{
				// Make sure that the loading classes are removed
				$('#fbpcontent').find('.fbp_loader').hide();
				$(".fbpTopNavigation_wrap").removeClass('NavTopULloading');  
				$('#NavLeftUL').removeClass('ul_loading');
				$('#'+this_liid).removeClass('li_loading'); 
			}
		}
		
		// Left navigation topic links
		$('#NavLeftUL li.NavLeftUL_item').on('click', 'a:first', function(event) 
		{
			event.preventDefault();
			
			var this_liid = $(this).parent('li').attr('id');
			var endpoint_liid = $(this).parent('li').attr('id');
			var endpoint_id = endpoint_liid.split("id").pop(1);
			var this_liclass = $(this).parent('li').attr('class');
			var href_link = $(this).attr('href');
			var cat_title = $(this).text();
			
			// Slide menu only if is not endpoint
			if (!$(this).parent('li').hasClass('NavLeftUL_endpoint'))
			{		
				// Keep track of left navigation animation to prevent double clicks
				if ($('.fbpLeftNavigation_wrap:animated').length == 0 && !$('#NavLeftUL').hasClass('ul_loading') && !$('.fbpTopNavigation_wrap').hasClass('NavTopULloading')) 
				{
					// Fix left navigation topics height
					var parent_li = $(this).parent(); 
					var child_ul = $(parent_li).find('ul:first'); 
					var eheight = $(child_ul).height();
					$('.fbpLeftNavigation_root').css({"height":eheight+"px"});

					$('.fbpLeftNavigation_root').find('ul').removeClass('NavLeftUL_expanded');	
					$('#'+this_liid).parents('ul.NavLeftUL_sublist').addClass('NavLeftUL_expanded');   
					$('#'+this_liid).find('ul:first').addClass('NavLeftUL_expanded');	
					
					var lefty = $('.fbpLeftNavigation_wrap');
					lefty.animate(
						{left:"-=100%"},
						{queue: true, complete: function()
						{ 
							// Remove lastchild class
							$('.fbpTopNavigation_root li').removeClass('NavTopUL_lastChild');
							
							// Remove last endpoint
							$('.fbpTopNavigation_root li.NavTopUL_endpoint').remove();
				
							// Add topic to breadcrumbs		
							var this_title = $('#'+this_liid).find('a:first').text();	
							$('.fbpTopNavigation_root').append($('<li id="top_'+this_liid+'" class="NavTopUL_item NavTopUL_topic NavTopUL_parent NavTopUL_lastChild"><i class="fa fa-caret-right"></i>&nbsp;&nbsp;<a class="NavTopUL_link" href="#" onclick="return false;">'+this_title+'</a></li>'));		
						
							// Load endpoint
							$(".fbpTopNavigation_wrap").removeClass('NavTopULloading');  
							loadEndpoint(endpoint_id, this_liid, href_link, cat_title);
						} 
					});
				}
			}
			else
			{
				// Keep track of left navigation animation to prevent double clicks
				if ($('.fbpLeftNavigation_wrap:animated').length == 0 && !$('#NavLeftUL').hasClass('ul_loading') && !$('.fbpTopNavigation_wrap').hasClass('NavTopULloading')) 
				{ 
					var this_title = $('#'+this_liid).find('a:first').text();
					
					// Remove lastchild class from section li
					$('.fbpTopNavigation_root li.NavTopUL_firstChild').removeClass('NavTopUL_lastChild');
					
					// Remove last endpoint
					$('.fbpTopNavigation_root li.NavTopUL_endpoint').remove();
					
					// Add endpoint topic to breadcrumbs			
					$('.fbpTopNavigation_root').append($('<li id="top_'+this_liid+'" class="NavTopUL_item NavTopUL_topic NavTopUL_endpoint NavTopUL_lastChild"><i class="fa fa-caret-right"></i>&nbsp;&nbsp;<a class="NavTopUL_link" href="#" onclick="return false;">'+this_title+'</a></li>'));		
				
					// Load endpoint
					$(".fbpTopNavigation_wrap").removeClass('NavTopULloading');  
					loadEndpoint(endpoint_id, this_liid, href_link, cat_title);
				}
			}
		});
					  
		// Topic back link - Remove class 'expanded' from 1st parent ul / Move wrap to the right
		$('#NavLeftUL li.NavLeftUL_backItem').on('click', 'a:first', function(event) 
		{
			// Keep track of animation to prevent double clicks	
			if ($('.fbpLeftNavigation_wrap:animated').length == 0 && !$('#NavLeftUL').hasClass('ul_loading') && !$('.fbpTopNavigation_wrap').hasClass('NavTopULloading')) 
			{
				var this_backliid = $(this).parent().attr('id');
			
				// Fix left navigation topics height
				var back_child_ul = $(this).parent().parent().parent().parent();
				var wheight = $(back_child_ul).height();
				$('.fbpLeftNavigation_root').css({"height":wheight+"px"});
				
				var righty = $('.fbpLeftNavigation_wrap');
			
				righty.animate(
					{left:"+=100%"}, 
					{queue: false, complete: function(){ 
				  
						$('#'+this_backliid).parent('ul').removeClass('NavLeftUL_expanded'); 		
						$('.fbpTopNavigation_root li.NavTopUL_lastChild').remove();
						$('.fbpTopNavigation_root li:last').addClass('NavTopUL_lastChild');	
					} 
				});
			}
		});
		
		// Top Navigation
		$('.fbpTopNavigation_root').on('click', 'li', function(event, this_liclass) { 
		
			if ($(this).hasClass('NavTopUL_home'))
			{
				return;	
			}
			
			event.preventDefault();
			
			var this_liclass = $(this).attr('class');
			var this_liid = $(this).attr('id');
			var href = $(this).find('.NavTopUL_link').attr('href');
			
			// Topic links		
			if ($(this).hasClass('NavTopUL_parent') && !$(this).hasClass('NavTopUL_lastChild') && !$('#NavLeftUL').hasClass('ul_loading') && !$('.fbpTopNavigation_wrap').hasClass('NavTopULloading'))
			{	
				// Keep track of left navigation animation to prevent double clicks
				if ($('.fbpLeftNavigation_wrap:animated').length == 0 && !$('#NavLeftUL').hasClass('ul_loading') && !$('.fbpTopNavigation_wrap').hasClass('NavTopULloading')) 
				{ 
					// Fix left navigation topics height
					var leftnav_liid = this_liid.split("_").pop(0);
					leftnav_child_ul = $('.fbpLeftNavigation_root li#'+leftnav_liid).find('ul:first');
					var eheight = $(leftnav_child_ul).height();
					$('.fbpLeftNavigation_root').css({"height":eheight+"px"});
					
					var li_count = $('.fbpTopNavigation_root li.NavTopUL_parent').length;
					var li_index = $('.fbpTopNavigation_root li.NavTopUL_parent').index(this);
					var slide_count = parseInt(li_count) - parseInt(li_index) - 1;	
					
					// Remove li's after specific index
					$('.NavTopUL_topic').eq(li_index).nextAll('li').remove();
					$(this).addClass('NavTopUL_lastChild');	
					
					// Move left navigation
					var righty = $('.fbpLeftNavigation_wrap');		
					var move_right = slide_count * 100;
				
					// Add loader in top navigation
					$(".fbpTopNavigation_wrap").addClass('NavTopULloading'); 
							
					righty.animate(
						{left:"+="+move_right+"%"}, 
						{queue: false, complete: function()
						{ 
							if (this_liclass.indexOf("NavTopUL_firstChild") !== -1)
							{
								resetTopics(event, href);
							}
				
							var this_id = this_liid.split("_").pop(0);
							if (this_id === 'home') 
							{
								$('#NavLeftUL ul').removeClass('NavLeftUL_expanded');
							} 
							else 
							{
								$('#'+this_id+' ul ul').removeClass('NavLeftUL_expanded');
							}	
							
							// Load topic content
							var topic_id = this_liid.split("id").pop(1);
							var left_liid = 'liid'+topic_id;
							var href_link = $('#NavLeftUL').find('#'+left_liid+' > .NavLeftUL_anchor').attr('href');
							var cat_title = $('#NavLeftUL').find('#'+left_liid+' > .NavLeftUL_anchor span.catTitle').text();
							
							loadEndpoint(topic_id, left_liid, href_link, cat_title);
						} 
					});
				}
			}
			
			// Home link
			if (leftnav > 0) 
			{
				if ($(this).hasClass('NavTopUL_firstChild') && $('.fbpLeftNavigation_wrap:animated').length == 0 && !$('#NavLeftUL').hasClass('ul_loading') && !$('.fbpTopNavigation_wrap').hasClass('NavTopULloading'))
				{	
					resetTopics(event, href);
				}
			}
			else
			{
				if ($(this).hasClass('NavTopUL_firstChild') && !$('.fbpTopNavigation_wrap').hasClass('NavTopULloading'))
				{	
					resetTopics(event, href);
				}	
			}
		});
		
		function faqThumbsUp(faq_id, event) 
		{
			$.ajax({
				type: "POST",
				url: site_path+"index.php?option=com_faqbookpro&task=question.faqThumbsUp&id=" + faq_id + "&" + token,
				beforeSend: function() {
					$('#thumbs_up_'+faq_id).addClass('loading');
				},
				success: function(msg) {
					$('#thumbs_up_'+faq_id).removeClass('loading');
					if(msg) 
					{
						$('#thumbs_up_'+faq_id+' span').html(msg);	
						$('#a_w_'+faq_id+' .faq_voting').append($('<div class="clearfix"> </div><span class="vote_result_text">'+thank_you_up+'</span>').hide().fadeIn(400));
						$('#a_w_'+faq_id+' .vote_result_text').delay(2000).fadeOut(400);
					} 
					else 
					{
						if ($('#a_w_'+faq_id+' .vote_exists_text')[0])
						{
							$('#a_w_'+faq_id+' .vote_exists_text').hide().fadeIn(400);
							$('#a_w_'+faq_id+' .vote_exists_text').delay(2000).fadeOut(400);
						} 
						else 
						{
							$('#a_w_'+faq_id+' .vote_result_text').remove();
							$('#a_w_'+faq_id+' .faq_voting').append($('<div class="clearfix"> </div><span class="vote_exists_text">'+already_voted+'</span>').hide().fadeIn(400));	
							$('#a_w_'+faq_id+' .vote_exists_text').delay(2000).fadeOut(400);
						}
					}
				}
			});
		}
		
		function faqThumbsDown(faq_id, event) 
		{
			$.ajax({
				type: "POST",
				url: site_path+"index.php?option=com_faqbookpro&task=question.faqThumbsDown&id=" + faq_id + "&" + token,
				beforeSend: function() {
					$('#thumbs_down_'+faq_id).addClass('loading');
				},
				success: function(msg, event) {
					$('#thumbs_down_'+faq_id).removeClass('loading');
					if(msg) 
					{
						$('#thumbs_down_'+faq_id+' span').html(msg);	
						$('#a_w_'+faq_id+' .faq_voting').append($('<div class="clearfix"> </div><div class="vote_reason_links" id="v_r_l_'+faq_id+'"><span class="why_not">'+why_not+'</span><div class="vote_reason"><a href="#" onclick="return false;" id="v_r_1" class="vote_reason_link">'+incorrect_info+'</a></div><div class="vote_reason"><a href="#" onclick="return false;" id="v_r_2" class="vote_reason_link">'+dont_like+'</a></div><div class="vote_reason"><a href="#" onclick="return false;" id="v_r_3" class="vote_reason_link">'+confusing+'</a></div><div class="vote_reason"><a href="#" onclick="return false;" id="v_r_4" class="vote_reason_link">'+not_answer+'</a></div><div class="vote_reason"><a href="#" onclick="return false;" id="v_r_5" class="vote_reason_link">'+too_much+'</a></div><div class="vote_reason"><a href="#" onclick="return false;" id="v_r_6" class="vote_reason_link">'+other+'</a></div></div>').hide().fadeIn(400));	
							
						// Attach voting reason event handlers
						var vote_reason_link = $('.vote_reason_link').on('click', function(event, reason_id) {						
							event.preventDefault();					  	
							var this_reasonid = $(this).attr('id');
							var reason_id = this_reasonid.split("v_r_").pop(0);
							var this_faqid = $(this).parent().parent().attr('id');
							var faq_id = this_faqid.split("v_r_l_").pop(0);
							var vote_reason_class = $('#v_r_l_'+faq_id+' span').attr('class');
								
							if (vote_reason_class.indexOf("loading") == -1) {
								faqVoteReason(reason_id, faq_id, event);
							}
						});
					} 
					else 
					{
						if ($('#a_w_'+faq_id+' .vote_exists_text')[0])
						{
							$('#a_w_'+faq_id+' .vote_exists_text').hide().fadeIn(500);
							$('#a_w_'+faq_id+' .vote_exists_text').delay(2000).fadeOut(400);
						} 
						else 
						{
							$('#a_w_'+faq_id+' .vote_result_text').remove();
							$('#a_w_'+faq_id+' .faq_voting').append($('<span class="vote_exists_text">'+already_voted+'</span>').hide().fadeIn(400));	
							$('#a_w_'+faq_id+' .vote_exists_text').delay(2000).fadeOut(400);
						}
					}	
				}
			});
		}
		
		function faqVoteReason(reason_id, faq_id, event) 
		{
			$.ajax({
				type: "POST",
				url: site_path+"index.php?option=com_faqbookpro&task=question.faqVoteReason&rid=" + reason_id + "&fid=" + faq_id + "&" + token,
				beforeSend: function() {
					$('#v_r_l_'+faq_id+' span').addClass('loading');
				},
				success: function(msg) {
					$('#v_r_l_'+faq_id+' span').removeClass('loading');
					if(msg) 
					{
						$('#a_w_'+faq_id+' .vote_exists_text').remove();
						$('#v_r_l_'+faq_id).remove();
						$('#a_w_'+faq_id+' .faq_voting').append($('<span class="vote_result_text">'+thank_you_down+'</span>').hide().fadeIn(400));
						$('#a_w_'+faq_id+' .vote_result_text').delay(2000).fadeOut(400);
					} 
					else
					{					
						$('#a_w_'+faq_id+' .faq_voting').append($('<span class="vote_result_text">'+error_voting+'</span>').hide().fadeIn(400));
						$('#a_w_'+faq_id+' .vote_result_text').delay(2000).fadeOut(400);					
					}
				}
			});
		}
		
		function addHit(faq_id, event) 
		{
			$.ajax({
				type: "POST",
				url: site_path+"index.php?option=com_faqbookpro&task=question.addHit&id=" + faq_id + "&" + token,
				beforeSend: function() {},
				success: function(msg) {}
			});  
		}
				
		// Hide/Show menu button / Show left navigation
		var show_leftnav = $('.show_menu').on('click', function(event) 
		{			
			event.preventDefault();
			
			$(this).find('a:first').toggleClass('btn-fbpactive');
			$('.fbpLeftNavigation_core').toggleClass('fbp-hidden');
		});
		
		// Ajax pagination - Topic
		$('.fbpContent_core').on('click', 'a.fbpContent_paging_button', function(event) 
		{	
			event.preventDefault();
			
			if (!$(this).hasClass('fbpContent_btn_disabled'))
			{
				$(this).addClass('page_loading');
				$(this).find('.fbpContent_paging_text').hide();
				$(this).find('.fbpContent_paging_loader').css('display', 'inline-block'); 
				$(this).addClass('fbpContent_btn_disabled');
								
				// Get page
				var page = $(this).attr('data-page');
				page = parseInt(page, 10);
									
				// Get topic
				var topic_id = $(this).attr('data-topic');
								
				// Check if there is a pending ajax request
				if(typeof paging_ajax_request !== "undefined")
					paging_ajax_request.abort();
						
				paging_ajax_request = $.ajax({
					type: "POST",
					url: site_path+"index.php?option=com_faqbookpro&task=topic.getContent&id=" + topic_id + "&page=" + page + "&" + token,
					success: function(msg) {
						$('#fbpPaging_'+topic_id).find('.fbpContent_paging_loader').hide();
						
						if (msg)
						{
							$('#fbpTopic_'+topic_id).append(msg);
							$('#fbpPaging_'+topic_id).find('.fbpContent_paging_text').show();
							$('#fbpPaging_'+topic_id+' .fbpContent_paging_button').removeClass('fbpContent_btn_disabled');	
							
							// Increment data-page
							new_page = page + 1;
							$('#fbpPaging_'+topic_id+' .fbpContent_paging_button').attr('data-page', new_page);
							
							// Activate actions in questions
							activateQuestions();
						}
						else
						{
							$('#fbpPaging_'+topic_id).find('.fbpContent_paging_text').hide();
							$('#fbpPaging_'+topic_id).find('.fbpContent_noresults').show();
							$('#fbpPaging_'+topic_id+' .fbpContent_paging_button').addClass('fbpContent_btn_disabled');
						}
						
						$('#fbpPaging_'+topic_id+' .fbpContent_paging_button').removeClass('page_loading');
					}
				});
			}
		});
	
	})
})(jQuery);