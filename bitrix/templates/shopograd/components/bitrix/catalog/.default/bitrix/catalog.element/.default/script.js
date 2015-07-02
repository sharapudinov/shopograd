window.product_detail_set_liquid_gaps = function(){
	$('#product_detail .product_main_part .liquid_gap_1').height(0);
	$('#product_detail .product_main_part .liquid_gap_2').height(0);
	if(($(window).width()>900) & ((350-$('#product_detail .product_main_part').height())>0)) {
		var $height_diff = (350-$('#product_detail .product_main_part').height())/2;
		$('#product_detail .product_main_part .liquid_gap_1').height($height_diff);
		$('#product_detail .product_main_part .liquid_gap_2').height($height_diff);
	}
}
window.product_detail_gallery_init = function(){
	$('#product_detail .product_gallery').bxSlider({
		mode: 'fade',
		auto: false,
		controls: false,
		pagerCustom: '#product_detail .gallery_thumbs'
	});
    $('.add_picture_bx_slider').bxSlider({
        mode: 'fade',
        auto: true,
        controls: false,
        pager: false
    });

	$('#product_detail .product_gallery .zoomable').zoom({ on:'click' });	
}
$(document).ready(function(){
	window.product_detail_gallery_init();
	window.product_detail_set_liquid_gaps();
	$('#product_detail .offer_selector select').change(function(){
		var $offer_selection_str = "";
		$('#product_detail .offer_selector select').each(function(){
			$offer_selection_str = $offer_selection_str + "[data-" + $(this).attr('data-param') + "='" + $(this).val() + "']";
		});
		$('#product_detail .offers .offer').removeClass('current');
		$('#product_detail .offers .offer_not_available').removeClass('current');
		if($('#product_detail .offers .offer' + $offer_selection_str).hasClass('offer')) {
			$('#product_detail .offers .offer' + $offer_selection_str).addClass('current');
			if($('#product_detail .offers .offer' + $offer_selection_str).attr('data-ajax-url')) {
				$('#product_detail .product_gallery_part').empty();
				$('#product_detail .product_gallery_part').html('<div class="gallery_loading"></div>');
				$.ajax({
					url: $('#product_detail .offers .offer' + $offer_selection_str).attr('data-ajax-url')
				})
				.done(function(html) {
					$('#product_detail .product_gallery_part').html($(html).find('#product_detail .product_gallery_part').html());
					window.product_detail_gallery_init();
					history.pushState({ foo: "bar" }, false, $('#product_detail .offers .offer' + $offer_selection_str).attr('data-ajax-url'));
				});
			}
		} else {
			$('#product_detail .offers .offer_not_available').addClass('current');
		}
		window.product_detail_set_liquid_gaps();
	});
});
$(window).bind('ready resize', function(){
	window.product_detail_set_liquid_gaps();
	setTimeout('window.product_detail_set_liquid_gaps();',100);
});