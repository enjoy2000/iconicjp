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
	    $('#login-button').click(function(event){
	    	event.preventDefault();
	    	showLogin();
	    });
	    $('#header-landing .background').cycle({fx:'scrollHorz',next: '.ui-buttonNextSlide',prev: '.ui-buttonPrevSlide' ,delay: -4000});
	});
	
	function centerwide(div){
		div.css("left", Math.max(0, (($(window).width() - $(div).outerWidth()) / 2) + $(window).scrollLeft()) + "px");
	}
	function showLogin(){
		$('#ajax-load .content').load('/customer/account/login', function(){
			scrollToTop();
	    	$('#ajax-load').addClass('open');
	    	loginAjax();
	    	showForgotPass();
		});
	}
	function showForgotPass(){
		$('#forgot-password').click(function(e){
			e.preventDefault();
			$('#ajax-load .content').load('/customer/account/forgotpassword', function(){
				scrollToTop();
		    	$('#ajax-load').addClass('open');
		    	forgotpassAjax();
			});
		});
	}
	function loginAjax(){
		$('#login-form').submit(function(ev){
			ev.preventDefault();
			var request = $.ajax({
				url: '/job/index/ajaxloginPost',
				type: 'POST',
				data: $(this).serialize(),
			});
			request.done(function(msg){
				if(msg == 1){ //logged in
					$('#ajax-load').removeClass('open');
					location.reload();
				}else{
					$('#ajax-load #response').html(msg);
					$('#ajax-load #response').show();
					$('input#pass').val('');
				}
			});
		});
	}
	function forgotpassAjax(){
		$('#form-forgot-password').submit(function(ev){
			ev.preventDefault();
			var request = $.ajax({
				url: '/job/index/ajaxforgotPassword',
				type: 'POST',
				data: $(this).serialize(),
				dataType: 'json',
			});
			request.done(function(msg){
				console.log(msg);
				if(msg.status == true){ 
					$('#ajax-load').addClass('open-1');
					$('#ajax-load .content').html('<p class="success">'+ msg.message +'</p>');
				}else{
					$('#ajax-load #response').html(msg.message);
					$('#ajax-load #response').show();
					$(this.input).val('');
				}
			});
		});
	}
	function scrollToTop(){
		$('html,body').animate({
          scrollTop: 0
        }, 1000);
	}
})(jQuery);



