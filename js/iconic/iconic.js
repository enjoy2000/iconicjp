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
	    $('#login-button').click(function(){
	    	$('#ajax-load .content').load('dang-nhap #ajax-content');
	    	$(document).ajaxStop(function(){
	    		$('#ajax-load').addClass('open');
	    	});
	    	return false;
	    });
	});
	
	function centerwide(div){
		div.css("left", Math.max(0, (($(window).width() - $(div).outerWidth()) / 2) + $(window).scrollLeft()) + "px");
	}
})(jQuery);



