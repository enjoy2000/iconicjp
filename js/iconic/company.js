(function($){
	$(document).ready(function(){
		$('ul li.home a').hover(
			function(){
				$('ul li.home a.inactive').show();
			}, function(){
				$('ul li.home a.inactive').stop().delay(300).hide();
			}
		);
	});
})(jQuery)
