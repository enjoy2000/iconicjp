(function($){
	$(document).ready(function(){
		/* view job detail function */
		$('a.details').on('click', function(e){
			e.preventDefault();
			if($(this).hasClass('active')){
				$(this).removeClass('active');
				$(this).parents('tr').next().stop(true, true).slideUp();
				$(this).parents('tr').removeClass('active');
			}else{
				$(this).addClass('active');
				$(this).parents('tr').next().stop(true, true).slideDown();
				$(this).parents('tr').addClass('active');
			}
		});
		
		/* Delete job */
		$('a.delete').on('click', function(e){
			e.preventDefault();
			if(confirm('This action will completely delete your job.\nAre you sure to continue?')){
				$.ajax({
					url: '/client/ajax/deletejob',
					data: {id: $(this).data('job')},
					dataType: 'json',
					success: function(msg){
						if(msg.status){
							location.reload();
						}else{
							alert(msg.message);
						}
					}
				});
			}
		});
	});
})(jQuery);