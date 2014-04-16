(function($){
	$(document).ready(function(){
		$('.centerwide').each(function(){
			centerwide($(this));
		});
	});
	
	function centerwide(div){
		div.css("left", Math.max(0, (($(window).width() - $(div).outerWidth()) / 2) + $(window).scrollLeft()) + "px");
	}
})(jQuery);



