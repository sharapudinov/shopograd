<?
// if you want this pager work - wrap paged items into a div with class .ajax_paged

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if(!$arResult["NavShowAlways"])
{
	if ($arResult["NavRecordCount"] == 0 || ($arResult["NavPageCount"] == 1 && $arResult["NavShowAll"] == false))
		return;
}
?>
<div id="ajax_pager">
<?
$strNavQueryString = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"]."&amp;" : "");
if($arResult["bDescPageNumbering"] === true)
{
	if ($arResult["NavPageNomer"] > 1)
	{
		?>
        <a href="<?=$arResult["sUrlPath"].'?'.$strNavQueryString.'PAGEN_'.$arResult["NavNum"].'='.($arResult["NavPageNomer"]-1);?>"></a>
        <?
	}
}
else
{
	if ($arResult["NavPageNomer"] < $arResult["NavPageCount"])
	{
		?>
        <a href="<?=$arResult["sUrlPath"].'?'.$strNavQueryString.'PAGEN_'.$arResult["NavNum"].'='.($arResult["NavPageNomer"]+1);?>"></a>
        <?
	}
}
?>
</div>
<script type="text/javascript">
	window.checkAjaxPager = function() {
		if (typeof $('#ajax_pager a').attr('href') !== 'undefined') {
		if($('#ajax_pager a').attr('href').length>0) {
			if((($('#content_scroller').scrollTop() + $('#content_scroller').height())>$('#ajax_pager').offset().top) || (($(window).scrollTop() + $(window).height())>$('#ajax_pager').offset().top)) {
				$(".ajax_paged").append($(".ajax_paged > :last").clone().addClass('ajax_paging_loading').append('<div class="loading"></div>').wrap('<p>').parent().html()); // adding loading animation as 'next element' in our paging content list
				$(".ajax_paged .ajax_paging_loading").animate({opacity:1},300);
				$.ajax({
					url: $('#ajax_pager a').attr('href'),
					cache: false
				})
				.done(function(html) {
					$(".ajax_paged .ajax_paging_loading").remove(); // remove loading animation
					$(".ajax_paged").append($(html).find('.ajax_paged').html());
					$('#ajax_pager a').attr('href',$(html).find('#ajax_pager a').attr('href'));
					window.checkAjaxPager();
				});
				$('#ajax_pager a').removeAttr('href');
			}
		}
		}
	}
	$(document).ready(function(){
		window.checkAjaxPager();
	});
	$(window).bind('scroll load resize', function(){
		window.checkAjaxPager();
	});
	$('#content_scroller').bind('scroll', function(){
		window.checkAjaxPager();
	});
</script>