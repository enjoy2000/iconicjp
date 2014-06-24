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
				console.log(data);
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
							 + '<div class="inline author"><img class="author-img" alt="" src="/media/'+ blog.author.image +'" /><a target="_blank" href="'+ blog.author.link +'">'+ blog.author.name +'</a></div>'
							 + '<br class="clear" />';
						$.each(blog.category, function(){
							html += '<div class="inline cat '+ this.class +'">'+ this.name +'</div>';
						});
						
						html += '<br class="clear" />'
							 + '<div class="social">'
							 + '<span class="inline twitter">'+ blog.social.twitter +'</span>'
							 + '<span class="inline fb">'+ blog.social.facebook +'</span>'
							 + '<span class="inline google">'+ blog.social.google +'</span>'
							 + '</div>'
							 + '</div>'
							 + '</div>'
							 + '</div>';
					});
					$('#blogs-content').append(html);
					$('#ajax-form .page').val(parseInt($('#ajax-form .page').val()) + 1);
					hideLoading();
				}
				if(data.count == 8 && data.nomore != 1){
					var loadmore = '<div class="load-more"><a class="btn" href="#">CLICK HERE TO SEE MORE ARTICLES</a></div>';
					$('#blogs-content').append(loadmore);
				}
				$('.load-more a').on('click', function(e){
					e.preventDefault();
					loadBlogs();
				});
			});
		}
		loadBlogs();
	});
})(jQuery);
