/*
 * Breadcrumb Box: http://photoboxone.com/
 */

;jQuery(document).ready(function($){
	
	$('.breadcrumb_box_tabmenu li').each(function(i){
		var li = $(this);
		
		li.click(function(e){
			e.preventDefault();
			
			$('.breadcrumb_box_tabmenu li').removeClass('active').eq(i).addClass('active');
			$('.breadcrumb_box_tabitem').removeClass('active').eq(i).addClass('active');
		});
	});
		
} );