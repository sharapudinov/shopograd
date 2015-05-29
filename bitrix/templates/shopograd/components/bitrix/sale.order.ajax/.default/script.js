$(document).ready(function(){
	setInterval('$("#shown_ajax_total_sum").html($("#hidden_ajax_total_sum").html()); $("select.custom").not(".hasCustomSelect").customSelect(); $("form.custom select").not(".hasCustomSelect").customSelect();',100);	
});
$(window).load(function(){
	submitForm();
});