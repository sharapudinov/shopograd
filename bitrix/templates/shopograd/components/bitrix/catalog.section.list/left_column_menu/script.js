$(document).ready(function(){
	$('select#side_menu').change(function(){
		if($(this).val()) {
			window.location.href = $(this).val();	
		}
	});
});