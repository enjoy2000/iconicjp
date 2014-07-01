(function($){
	$(document).ready(function(){
		function loadBlogs(){
			showLoading();
			$.ajax({
				'url': '/blog/index/ajax',
				'type': 'json',
				'method': 'post',
				'data': $('#ajax-form').serialize()
			}).success(function(data){
				//console.log(data);
				$('.load-more').remove();
				if(data.page == 1 && data.count ==0){
					alert('no result');
					hideLoading();
					return false;
				}
				if(data.count > 0){
					html = '';
					$.each(data.items, function(index, blog){
						html += '<div class="blog-wrapper clearfix">'
							 + '<img class="fll" src="/media/'+ blog.image +'" alt="" width="162" height="125" />'
							 + '<div class="blog-info fll">'
							 + '<h4><a href="'+ blog.url +'" title="'+ blog.title +'">'+ blog.title +'</a></h4>'
							 + '<div class="inline date">'+ blog.date +'</div>'
							 + '<div class="inline author"><img class="author-img" alt="" src="/media/'+ blog.author.image +'" /><a href="'+ blog.author.link +'">'+ blog.author.name +'</a></div>'
							 + '<br class="clear" />';
						$.each(blog.category, function(){
							html += '<div class="inline cat '+ this.class +'">'+ this.name +'</div>';
						});
						
						html += '<br class="clear" />'
							 + '<div class="social">'
							 + '<span class="inline twitter">'+ blog.social.twitter +'</span>'
							 + '<span class="inline fb">'+ blog.social.facebook +'</span>'
							 //+ '<span class="inline google">'+ blog.social.google +'</span>'
							 + '</div>'
							 + '</div>'
							 + '</div>'
							 + '</div>';
					});
					$('#blogs-content').html(html);
					hideLoading();
				}
				pager = '<div class="pager">';
				for(i = 1; i <= data.lastpage; i++){
					if(data.page == i){
						active = 'active';
					}else{
						active = '';
					}
					pager += '<a href="#" class="inline '+ active +'" data-page="'+ i +'">'+ i +'</a>';
				}
				pager += '</div>';
				$('#pager').html(pager);
				$('.pager a').on('click', function(e){
					e.preventDefault();
					$('#ajax-form .page').val($(this).data('page'));
					loadBlogs();
				});
			});
		}
		loadBlogs();
	});
})(jQuery);
