$(document).ready(function(){
	$('#search_line select').change(function(){
		$('#search_line .section_selector span').text($('#search_line select option[value="' + $(this).val() + '"]').attr('data-text'));
		$('#search_line form').attr('action',$(this).val());	
	});	
});