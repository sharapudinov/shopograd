$(document).ready(function(){
	$('#catalogue_filter .inline_input span').click(function(){
		$(this).parents('.inline_input').find('input').css('display','inline-block').focus();
		$(this).hide();
		return false;	
	});
	$('#catalogue_filter .inline_select select').change(function(){
		$(this).parents('.inline_select').find('span').text($(this).find('option[value="' + $(this).val() + '"]').attr('data-text'));
	});
	
	// this works properly only if no ajax paging used!
	$("#catalogue_filter form").submit(function() {
		window.history.pushState("string", $(document).find("title").text(), window.location.href.substring(0,window.location.href.indexOf("?")) + "?" + $("#catalogue_filter form").serialize());
		$.ajax({
			type: "GET",
			url: $("#catalogue_filter form").attr('action'),
			data: $("#catalogue_filter form").serialize(),
			success: function(data)
			{
				// manipulations with catalog.section component template
				$('#catalogue_items').html($(data).find('#catalogue_items').html());
			}
		});
		return false;
	});
	
	$('#catalogue_filter input, #catalogue_filter select').change(function(){
		$('#catalogue_filter form').submit();
	});		
	$('#catalogue_filter input').blur(function(){
		$('#catalogue_filter form').submit();
	});
});