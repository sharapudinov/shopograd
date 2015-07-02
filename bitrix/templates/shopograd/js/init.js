window.breadcrumb_correction = function(){
	if($('#page_title .inner').width()>($('#page_title').width()-100)) {
		$('#page_title .breadcrumb_replication').show();
	} else {
		$('#page_title .breadcrumb_replication').hide();
	}
}
$(document).ready(function(){
	$(document).foundation();
    FastClick.attach(document.body);
	new mlPushMenu(document.getElementById('mp-menu'), document.getElementById('mp-menu-trigger'));
	$('select.custom').not(".hasCustomSelect").customSelect();
	$('form.custom select').not(".hasCustomSelect").customSelect();
	$('.tabbed_content ul.tabs').each(function(){
		$(this).superSimpleTabs();	
	});
	window.breadcrumb_correction();
	if(location.hash.match(/\#scroll\_/)) {
		$('#content_scroller').scrollTop(location.hash.substring(8));
	}
	$('a').click(function(){
		location.hash = "scroll_" + $('#content_scroller').scrollTop();
	});
});
$(window).bind('load resize', function(){
	window.breadcrumb_correction();
});