var hook=true;
(function($){
	$(document).ready(function(){
		//hover home button
		$('ul li.has-child').hover(
			function(){
				$('ul.inactive', this).stop(true, true).slideDown();
			}, function(){
				$('ul.inactive', this).stop().slideUp();
			}
		);
		
		//center function
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
		
		//toggle content
		$('.toggle-content a.toggle').click(function(e){
			e.preventDefault();
			if(!$(this).hasClass('active')){
				$(this).addClass('active');
			}else{
				$(this).removeClass('active');
			}
			$(this).parent().find('.content').toggle();
		});
		
		//download cv
		$('.download-btn').click(function(e){
			e.preventDefault();
			if($(this).hasClass('active')){
				$(this).removeClass('active');
				$(this).parent().css('right',-285);
			}else{
				$(this).parent().css('right',0);
				$(this).addClass('active');
			}
		});
		
		//multi select description
		jQuery('.ms-drop li:first-child').html('最大３つまで選択可能です。');
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
function showForgotPage(){
	jQuery('#ajax-load .content').load('/customer/account/forgotpassword', function(){
		scrollToTop();
    	jQuery('#ajax-load').addClass('open');
    	forgotpassAjax();
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
				if(msg.message){
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
