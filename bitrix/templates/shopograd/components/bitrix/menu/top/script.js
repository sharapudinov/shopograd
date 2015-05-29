window.update_main_menu = function(){
	$('.main_menu .selector select').empty();
	$('.main_menu .selector select').append('<option value="">···</option>');
	$('.main_menu a').show();
	var links_total_width = 0;
	$('.main_menu a').each(function(){
		links_total_width = links_total_width + $(this).width() + 50; // 40 = margin + padding
		if(links_total_width>($('.main_menu').width() - 40)) { // 30+10 = selector width + margin
			$('.main_menu .selector select').append('<option value="' + $(this).attr('href') + '">' + $(this).text() + '</option>');
			$(this).hide();
		}
	});
	if(links_total_width<=($('.main_menu').width() - 40)) {
		$('.main_menu .selector').hide();
	} else {
		$('.main_menu .selector').show();	
	}
}
$(document).ready(function(){
	window.update_main_menu();
	$('.main_menu .selector select').change(function(){
		if($(this).val()) {
			window.location.href = $(this).val();	
		}
	});
});
$(window).resize(function(){
	window.update_main_menu();
});