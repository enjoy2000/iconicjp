(function($){
	$(document).ready(function(){
		$('.centerwide').each(function(){
			centerwide($(this));
		});
		//fixed menu
		var nav = $('#fixed-menu');
	    $(window).scroll(function () {
	        if ($(this).scrollTop() > 130) {
	            nav.addClass("f-nav");
	        } else {
	            nav.removeClass("f-nav");
	        }
	    });
	    $('#login-button, .btn-login').click(function(event){
	    	event.preventDefault();
	    	showLogin();
	    });
	    $('#header-landing .background').cycle({fx:'scrollHorz',next: '.ui-buttonNextSlide',prev: '.ui-buttonPrevSlide' ,delay: -4000});
	});
	
})(jQuery);


function centerwide(div){
	div.css("left", Math.max(0, ((jQuery(window).width() - jQuery(div).outerWidth()) / 2) + jQuery(window).scrollLeft()) + "px");
}
function showLogin(){
	jQuery('#ajax-load .content').load('/customer/account/login', function(){
		scrollToTop();
    	jQuery('#ajax-load').addClass('open');
    	loginAjax();
    	showForgotPass();
	});
}
function showForgotPass(){
	jQuery('#forgot-password').click(function(e){
		e.preventDefault();
		jQuery('#ajax-load .content').load('/customer/account/forgotpassword', function(){
			scrollToTop();
	    	jQuery('#ajax-load').addClass('open');
	    	forgotpassAjax();
		});
	});
}
function loginAjax(){
	jQuery('#login-form').submit(function(ev){
		ev.preventDefault();
		var request = jQuery.ajax({
			url: '/job/index/ajaxloginPost',
			type: 'POST',
			data: jQuery(this).serialize(),
			dataType: 'json',
		});
		request.done(function(msg){
			if(msg.status == true){ //logged in
				jQuery('#ajax-load').removeClass('open');
				window.location.replace(msg.message);
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
			url: '/job/index/ajaxforgotPassword',
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

