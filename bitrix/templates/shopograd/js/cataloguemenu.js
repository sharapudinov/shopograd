$(document).ready(function(){
	$('html').click(function() {
		$('#catalogue_menu_dropdown').fadeOut('fast');
	});
	$('#catalogue_menu_toggle').click(function(event){
		event.stopPropagation();
		$('#catalogue_menu_dropdown').fadeToggle('fast');
		return false;
	});
	$('#catalogue_menu_dropdown').click(function(event){
		event.stopPropagation();
	});
	$('#catalogue_menu_dropdown a.dropdown_header').click(function(event){
		$('#catalogue_menu_dropdown').fadeOut('fast');
		return false;	
	});
});