window.updatebasket = function($quantity_obj){
	$('#basket_loading').fadeIn('fast');
	$.ajax({
		type: $('.basket form').attr('method'),
		url: $('.basket form').attr('action'),
		data: $(".basket form").serialize(),
		success: function(data)
		{
			if(typeof $quantity_obj === 'object') {
				$quantity_obj.val($(data).find('input.quantity[name="' + $quantity_obj.attr('name') + '"]').val());
			}
			$('#basket_items_total').html($(data).find('#basket_items_total').html());
			submitForm();
			$('#basket_loading').fadeOut('fast');
		}
	});	
}
$(document).ready(function(){
	$('.basket input.quantity').change(function(){
		window.updatebasket($('input.quantity[name="' + $(this).attr('name') + '"]'));	
		return false;
	});
	$('.basket a.delete').click(function(){
		if($('.order_items .item').size() > 1) {
			if($('#' + $(this).attr('rel')).prev('.item').size()==0) {
				$('#' + $(this).attr('rel')).next('.item').find('.divider').fadeOut('fast');
			}
			$('#' + $(this).attr('rel')).fadeOut('fast', function(){ $(this).remove(); });
			$.ajax({
				url: $(this).attr('href'),
				success: function(data)
				{
					window.updatebasket();
				}
			});
			return false;
		}
	});
	$('.basket .order_items_summary .coupon_toggle').click(function(){
		$(this).hide();
		$('.basket .order_items_summary .coupon_input').show();
	});	
});