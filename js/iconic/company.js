(function($){
	$(document).ready(function(){
		//check input
		$('form input, form select').each(function(){
			if($(this).val() != ''){
				$(this).addClass('after-input');
			}
		});
		//hover home button
		$('ul li.has-child').hover(
			function(){
				$('ul.inactive', this).stop(true,true).slideDown();
			}, function(){
				$('ul.inactive', this).stop().delay(200).slideUp();
			}
		);
		
		$('.centerwide').each(function(){
			centerwide($(this));
		});
		
		//fixed menu
		var nav = $('#fixed-menu');
	    $(window).scroll(function () {
	        if ($(this).scrollTop() > 80) {
	            nav.addClass("f-nav");
	        } else {
	            nav.removeClass("f-nav");
	        }
	    });
	    $('#login-button, .btn-login').click(function(event){
	    	event.preventDefault();
	    	showLogin();
	    });
	    $('a#close-button').click(function(event){
	    	event.preventDefault();
	    	$('#ajax-load').removeClass('open');
    		$('#ajax-load').removeClass('open-1');
    		$('#ajax-load .content').html('');
	    });
	    //$('#header-landing .background').cycle({fx:'fadeZoom',next: '.ui-buttonNextSlide',prev: '.ui-buttonPrevSlide' ,delay: -4000});
		
		//input effect
		$('input, select, textarea').on('change blur', function(){
			if($(this).val() != ''){
				$(this).removeClass('error');
				$(this).addClass('after-input');
			}else{
				if($(this).attr('required')){
					$(this).addClass('error');
				}
				$(this).removeClass('after-input');
			}
		});
		
		// link rel box effect
		$('a[rel=box]').click(function(e){
			e.preventDefault();
			
			var maxHeight = 650, maxWidth = 780;
			var mainDiv = $('#'+$(this).data('box'));
			//show overlay background
			mainDiv.after('<div id="box-overlay"></div>');
			var overlay = $('#box-overlay');
			//css for overlay
			overlay.css('width', $(window).width())
					.css('height', $(window).height());
			
			//css for main div
			mainDiv.css('max-height', maxHeight).css('max-width', maxWidth);
		    // create close button
		    mainDiv.prepend('<a href="#" class="close-btn">X</a>');
		    //create div content outside main Div
		    if(!mainDiv.find('.inner-box').length){
		    	mainDiv.wrapInner('<div class="inner-box"></div>');
		    }
			// center box content
			mainDiv.css({
		        left: ($(window).width() - mainDiv.outerWidth())/2,
		        top: ($(window).height() - mainDiv.outerHeight())/2
		    });
		    //show box content
			mainDiv.fadeIn(200);
			
			/*
			 * CLOSE EVENT
			 */
			$(document).mouseup(function (e)
				{
				    var container = $('.box-content');
				
				    if (!container.is(e.target) // if the target of the click isn't the container...
				        && container.has(e.target).length === 0) // ... nor a descendant of the container
				    {
				        container.fadeOut(300);
				        overlay.remove();
				    }
				});
			//close btn
			$('.box-content a.close-btn').click(function(e){
				e.preventDefault();
				
				$(this).parents('.box-content').fadeOut(300);
				overlay.remove();
			});
			
			// Adjust position when resize window
			$(window).resize(function(){
				//css for overlay
				overlay.css('width', $(window).width())
					.css('height', $(window).height());
				// center box content
				mainDiv.css({
			        left: ($(window).width() - mainDiv.outerWidth())/2,
			        top: ($(window).height() - mainDiv.outerHeight())/2
			    });
			});
		});
		
		// toggle button
		$('a.toggle').click(function(e){
			e.preventDefault();
			$(this).next('.toggle-content').toggle();
			$(this).toggleClass('active');
		});
		//scroll
		$('.ch-item').click(function(e){
			e.preventDefault();
			
			$('html,body').animate({
		      scrollTop: $('#'+$(this).data('scroll')).offset().top - $('#header-logo').outerHeight()
		    }, 1000);
		});
	});
	
	
})(jQuery);


function centerwide(div){
	div.css("left", Math.max(0, ((jQuery(window).width() - jQuery(div).outerWidth()) / 2) + jQuery(window).scrollLeft()) + "px");
}
function showLogin(){
	jQuery('#ajax-load .content').load('/company/customer/account/login', function(){
		scrollToTop();
    	jQuery('#ajax-load').addClass('open');
    	loginAjax();
    	showForgotPass();
	});
}
function showForgotPass(){
	jQuery('#forgot-password').click(function(e){
		e.preventDefault();
		jQuery('#ajax-load .content').load('/company/customer/account/forgotpassword', function(){
			scrollToTop();
	    	jQuery('#ajax-load').addClass('open');
	    	forgotpassAjax();
		});
	});
}
function showForgotPage(){
	jQuery('#ajax-load .content').load('/company/customer/account/forgotpassword', function(){
		scrollToTop();
    	jQuery('#ajax-load').addClass('open');
    	forgotpassAjax();
	});
}
function loginAjax(){
	jQuery('#login-form').submit(function(ev){
		ev.preventDefault();
		var request = jQuery.ajax({
			url: '/company/job/index/ajaxloginPost',
			type: 'POST',
			data: jQuery(this).serialize(),
			dataType: 'json',
		});
		request.done(function(msg){
			if(msg.status == true){ //logged in
				jQuery('#ajax-load').removeClass('open');
				if(msg.message){
					//console.log(msg);
					window.location.replace(msg.message);
				}else{
					location.reload();
				}
			}else{
				jQuery('#ajax-load #response').html(msg.message);
				jQuery('#ajax-load #response').show();
				jQuery('input#pass').val('');
			}
		});
	});
}
function forgotpassAjax(){
	jQuery('#form-forgot-password').submit(function(ev){
		ev.preventDefault();
		var request = jQuery.ajax({
			url: '/company/job/index/ajaxforgotPassword',
			type: 'POST',
			data: jQuery(this).serialize(),
			dataType: 'json',
		});
		request.done(function(msg){
			console.log(msg);
			if(msg.status == true){ 
				jQuery('#ajax-load').addClass('open-1');
				jQuery('#ajax-load .content').html('<p class="success">'+ msg.message +'</p>');
			}else{
				jQuery('#ajax-load #response').html(msg.message);
				jQuery('#ajax-load #response').show();
				jQuery(this.input).val('');
			}
		});
	});
}
function scrollToTop(){
	jQuery('html,body').animate({
      scrollTop: 0
    }, 1000);
}
function resetForm(){
	jQuery(':input', this.form)
	.not(':button, :submit, :reset, :hidden')
	.val('')
	.removeAttr('checked')
	.removeAttr('selected')
	.removeClass('after-input')
	.removeClass('error');
	return false;
}
function showLoading(){
	jQuery('#ajax-loading').css('height', jQuery(window).height()).css('width', jQuery(window).width());
	jQuery('#ajax-loading').show();
	jQuery('#ajax-loading').click(function(e){
		jQuery(this).hide();
	});
}
function hideLoading(){
	jQuery('#ajax-loading').hide();
}
