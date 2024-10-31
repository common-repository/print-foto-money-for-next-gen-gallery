	jQuery(document).ready(function($) {
		/*
		## Print Me Button Settings
		********************************************************/
		var button_text = pm_settings['button_text'];
		var container = pm_settings['container'];
		var page = pm_settings['page'];
		var position = pm_settings['position'];
		var url = "http://www.dotphoto.com/WPLand.asp";
		var returnURL = pm_settings['return_url'];
		var affiliateID = pm_settings['affliateID'];
		var button_bg_color = pm_settings['button_bg_color'];
		var button_text_color = pm_settings['button_text_color'];
		var image_protection_visitors = pm_settings['image_protection_visitors'];
		var image_protection_users = pm_settings['image_protection_users'];
		var user_logged_in = is_user_logged_in.status;
		var content = containerList(container);
		
		/*
		## Image Protection
		********************************************************/
		if ( image_protection_visitors == 1 ) {
			$('img').bind("contextmenu",function(e){
					return false;
			});
			$('img').bind("mousedown",function(e){
				return false;
			});
			
			$('img').bind("click",function(e){
				return false;
			});
		} else if ( user_logged_in == 1 && image_protection_users == 1 ) {
			$('img').bind("contextmenu",function(e){
					return false;
			});
			$('img').bind("mousedown",function(e){
				return false;
			});
			$('img').bind("click",function(e){
					return false;
			});
		}
		
		/*
		## Initialize Print Me Button
		********************************************************/
		$(window).load(function(){
			printme_initialize();
		}); //end of window.load	
		
		// Re-Initialize Printme 
		$('a').each(function(index, element) { 
			$(this).on('click', function(e) { $imgfound = 0;
				$(document).bind('DOMNodeInserted', function(e) {   
					if ( e.target ) {
						if ( $(e.target).is('img') ) { $imgfound = 1;
							addButton($(e.target));
							$('a').each(function(index, element) {
								if ( !$(this).hasClass('.btn-img') ) {
									$(e.target).closest('div').addClass('removerelative');
								}
							});
						}
					}
				});
				if ( $imgfound == 0 ) {
					setTimeout(function(){  addButton($('img').last()); },1000);
				}
			});
		});
		
		/*  
		## Initialize Scripts Function  
		******************************************************/
		function printme_initialize(){
			/* Add Class When Image size below */
			$(content.join()).find('img').each(function(index, element) {	
				var img = $(this);
				// Create new offscreen image to test
				var theImage = new Image();
				theImage.src = full_url(img);
				// Add a reference to the original.
				$(theImage).data("original",this);
				// Get accurate measurements from that.
				$(theImage).load(function(){
				   var fullwidth = theImage.width;
				   var fullheight = theImage.height;
				   
				   if ( fullwidth <= 400 && fullheight <= 400 ) {
						img.addClass('hide-button')
				   } 
				});	
			});
					
			/* Hover Function */
			$(content.join()).find('img').each(function(index, element) {
				if ( !$(this).hasClass('alway-show') ) {
					$(this).hover(function(){
						// Enable Button Above of the Image Size Restriction
						if ( !$(this).hasClass('hide-button') && !$(this).hasClass('alway-show') ) {
							addButton($(this));
						}
					},function(event){
						// Enable Button Above of the Image Size Restriction
						if ( !$(this).hasClass('hide-button') && !$(this).hasClass('alway-show') ) {
							var target = event.relatedTarget;
							if ( !$(target).hasClass('btn-img') ) {
								$('.btn-img').remove();
							} else {
								$('.btn-img').mouseleave(function(){
									$('.btn-img').remove();
								});
							}
						}
					});
				}
			});
			
			$(document).bind('DOMNodeInserted', function(e) {   
				$('.btn-img').click(function(event){
					event.preventDefault();
					
					var currenturl  = window.location.href; 
					var url = $(this).attr('href');
					$(this).text('printing...');
					$.post( click_count.url, { action:'click_count', img_url:url, current_url: currenturl },function(){
						window.open(url, '_self');
					});
				});
			});
		} // end of printme_initialize
		
		
		/*  
		## Helper Functions
		******************************************************/
		/* Return Image Full URL */
		function full_url(img) {
			var fimgsrc = img.attr('src');
			var fullimg = fimgsrc ? fimgsrc.replace(/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i, '') : '';
			var splitparam = fullimg.split('?')
			return splitparam[0];
		}
		
		/* Return All Container */
		function containerList(container) {
			var content = new Array();
			$.each(container,function( index, className ) {
				
				if ( className != 'body' && className != 'html' ) {
					content[index] = '.'+className;
				} else {
					content[index] = className;
				}
			});
			return content; 	
		}
		
		/* Add Button */
		function addButton(img) {
			$('.btn-img').remove();
			var pos = img[0];
			var pmUrl = url+'?returnURL='+returnURL+'&affiliateID='+affiliateID+'&imgURL='+full_url(img);
			
			/* Calculate Image Position */
			// Top Left Default
			var y = pos.offsetTop;
			var x = pos.offsetLeft;
				
			var button = '<a href="'+pmUrl+'" class="btn-img" style="background:'+button_bg_color+'; position:absolute; top:'+y+'px; left:'+x+'px;  "><span style="color:'+button_text_color+' !important;">'+button_text+'</span></a>';
			$(button).insertAfter(img);	
			
			// Top Right
			if ( position == 'top-right' ) {
				x = (x + pos.offsetWidth) - $('.btn-img').innerWidth();
				$('.btn-img').css({'left':x});
			}
			// Bottom Right
			if ( position == 'bottom-rigt' ) {
				x = (x + pos.offsetWidth) - $('.btn-img').innerWidth();
				y = (y + pos.offsetHeight) - $('.btn-img').innerHeight();
				$('.btn-img').css({'left':x,'top':y});
			}
			// Bottom Left
			if ( position == 'bottom-left' ) {
				y = (y + pos.offsetHeight) - $('.btn-img').innerHeight();
				$('.btn-img').css({'top':y});
			}
		}
		
	});
