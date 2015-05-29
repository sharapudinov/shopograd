$(document).ready(function(){
	$('.dropdowns .dropdown_toggle').each(function(){
		$('.dropdown_content#' + $(this).attr('rel')).hide();
	});	
	$('.dropdowns .dropdown_toggle').click(function(){
		$(this).fadeOut('fast',function(){
			$(this).remove();
			$('.dropdown_content#' + $(this).attr('rel')).slideDown('fast');
		});	
		return false;
	});
});