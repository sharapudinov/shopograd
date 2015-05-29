window.update_additional_menu = function(){
	$('.additional_menu .selector select').empty();
	$('.additional_menu .selector select').append('<option value="">···</option>');
	$('.additional_menu a').show();
	var links_total_width = 0;
	$('.additional_menu a').each(function(){
		links_total_width = links_total_width + $(this).width() + 22; // 20 = margin
		if(links_total_width>($('.additional_menu').width() - 20)) { // 20 = selector width
			$('.additional_menu .selector select').append('<option value="' + $(this).attr('href') + '">' + $(this).text() + '</option>');
			$(this).hide();
		}
	});
	if(links_total_width<=($('.additional_menu').width() - 20)) {
		$('.additional_menu .selector').hide();
	} else {
		$('.additional_menu .selector').show();	
	}
}
$(document).ready(function(){
	window.update_additional_menu();
	$('.additional_menu .selector select').change(function(){
		if($(this).val()) {
			window.location.href = $(this).val();	
		}
	});
});
$(window).resize(function(){
	window.update_additional_menu();
});