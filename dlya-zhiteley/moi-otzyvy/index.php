<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Мои отзывы");
?>
<?
global $arUserReviewsFilter;
$arUserReviewsFilter = array('ACTIVE'=>'','PROPERTY_USER'=>$USER->GetID());
?>
<?$APPLICATION->IncludeComponent(
	"bitrix:news.list", 
	"reviews", 
	array(
		"_TITLE" => "",
		"AJAX_MODE" => "N",
		"IBLOCK_TYPE" => "system",
		"IBLOCK_ID" => "8",
		"NEWS_COUNT" => "12",
		"SORT_BY1" => "ID",
		"SORT_ORDER1" => "DESC",
		"SORT_BY2" => "",
		"SORT_ORDER2" => "",
		"FILTER_NAME" => "arUserReviewsFilter",
		"FIELD_CODE" => array(
			0 => "",
			1 => "ACTIVE",
		),
		"PROPERTY_CODE" => array(
			0 => "USER",
			1 => "PRODUCT_NAME",
			2 => "PRODUCT",
			3 => "",
		),
		"CHECK_DATES" => "N",
		"DETAIL_URL" => "",
		"PREVIEW_TRUNCATE_LEN" => "",
		"ACTIVE_DATE_FORMAT" => "",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"INCLUDE_SUBSECTIONS" => "Y",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "86400",
		"CACHE_FILTER" => "Y",
		"CACHE_GROUPS" => "Y",
		"PAGER_TEMPLATE" => "ajax",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "",
		"PAGER_SHOW_ALL" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => ""
	),
	false
);?>
<h2>Как написать отзыв?</h2>
<p>Отзывы о каком-либо товаре или нашем магазине могут писать только пользователи, оформившие заказ. Таким образом, мы пытаемся препятствовать размещению лишней, непроверенной информации.</p><p>После того, как Ваш заказ будет доставлен, Вам поступит e-mail с инструкцией по написанию отзыва (о каждом товаре в заказе и о нашем магазине).</p>
<p>Мы очень ценим мнение каждого Клиента! Спасибо Вам за доверие и обратную связь!</p>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>