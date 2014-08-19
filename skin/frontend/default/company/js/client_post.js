(function($){
	$(document).ready(function(){
		
		//ckeditor for info text textarea
		CKEDITOR.replace('info').config.extraPlugins = 'confighelper';
		CKEDITOR.instances.info.on('blur', function(e) {
			var content = e.editor.getData();
			if(content.length > 3){
				jQuery('#cke_info').addClass('after-input');
			}else{
				jQuery('#cke_info').removeClass('after-input');
			}
		});
		CKEDITOR.instances.info.on('instanceReady', function(e) {
			var content = e.editor.getData();
			if(content.length > 3){
				jQuery('#cke_info').addClass('after-input');
			}else{
				jQuery('#cke_info').removeClass('after-input');
			}
		});
		
		//ckeditor for requirement textarea
		CKEDITOR.replace('requirement').config.extraPlugins = 'confighelper';
		CKEDITOR.instances.requirement.on('blur', function(e) {
			var content = e.editor.getData();
			if(content.length > 3){
				jQuery('#cke_requirement').addClass('after-input');
			}else{
				jQuery('#cke_requirement').removeClass('after-input');
			}
		});
		CKEDITOR.instances.requirement.on('instanceReady', function(e) {
			var content = e.editor.getData();
			if(content.length > 3){
				jQuery('#cke_requirement').addClass('after-input');
			}else{
				jQuery('#cke_requirement').removeClass('after-input');
			}
		});
		
		//select-box js trigger after check
		$('.select-box').each(function(){
			if($('input:checked', this).length > 0){
				$(this).parent().find('a').addClass('after-input');
			}
		});
		 
		$('a.select-button').on('click', function(e){
			e.preventDefault();
			var $this = $(this);
			$(this).addClass('focus');
			var div = $('#'+$(this).data('box'));
			div.show();
			$(document).mouseup(function (e){
			    if (!div.is(e.target) // if the target of the click isn't the container...
			        && div.has(e.target).length === 0) // ... nor a descendant of the container
			    {
			        div.hide();
			        $this.removeClass('focus');
			        var count = $('input:checked', div).length;
			        var defaultText = $this.text();
			        if(count > 0){
			        	$this.addClass('after-input');
			        	$this.removeClass('error');
			        	$this.text('入力済み'); // already checked text
			        }else{
			        	$this.removeClass('after-input');
			        	$this.addClass('error');
			        	$this.text(defaultText);
			        }
			    }
			});
		});
	});
})(jQuery);