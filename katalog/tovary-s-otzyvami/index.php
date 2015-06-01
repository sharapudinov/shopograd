<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Товары с отзывами");
?>
<?$APPLICATION->IncludeComponent(
	"bitrix:catalog", 
	".default", 
	array(
		"IBLOCK_TYPE" => "catalogues",
		"IBLOCK_ID" => "1",
		"HIDE_NOT_AVAILABLE" => "N",
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"SEF_MODE" => "Y",
		"SEF_FOLDER" => "/katalog/tovary-s-otzyvami/",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "86400",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "Y",
		"ADD_SECTIONS_CHAIN" => "Y",
		"ADD_ELEMENT_CHAIN" => "N",
		"USE_ELEMENT_COUNTER" => "N",
		"USE_FILTER" => "N",
		"USE_COMPARE" => "N",
		"PRICE_CODE" => array(
			0 => "RETAIL",
		),
		"USE_PRICE_COUNT" => "N",
		"SHOW_PRICE_COUNT" => "1",
		"PRICE_VAT_INCLUDE" => "Y",
		"PRICE_VAT_SHOW_VALUE" => "N",
		"CONVERT_CURRENCY" => "Y",
		"CURRENCY_ID" => _CURRENCY,
		"BASKET_URL" => "/katalog/oformlenie-zakaza/",
		"ACTION_VARIABLE" => "action",
		"PRODUCT_ID_VARIABLE" => "id",
		"USE_PRODUCT_QUANTITY" => "N",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PARTIAL_PRODUCT_PROPERTIES" => "Y",
		"PRODUCT_PROPERTIES" => array(
		),
		"OFFERS_CART_PROPERTIES" => array(
			0 => "CODE",
		),
		"SHOW_TOP_ELEMENTS" => "N",
		"SECTION_COUNT_ELEMENTS" => "Y",
		"SECTION_TOP_DEPTH" => "1",
		"PAGE_ELEMENT_COUNT" => "30",
		"LINE_ELEMENT_COUNT" => "6",
		"ELEMENT_SORT_FIELD" => "sort",
		"ELEMENT_SORT_ORDER" => "asc",
		"ELEMENT_SORT_FIELD2" => "id",
		"ELEMENT_SORT_ORDER2" => "desc",
		"LIST_PROPERTY_CODE" => array(
			0 => "AUTO_CALCULATED_REVIEWS_AMOUNT",
			1 => "NEW_TILL",
            2 => "SPECIAL_OFFER_TILL",
            3 => "OLD_PRICE",
            4 => "CUSTOM_LABEL",
            5 => "CUSTOM_LABEL_TILL"
		),
		"INCLUDE_SUBSECTIONS" => "N",
		"LIST_META_KEYWORDS" => "-",
		"LIST_META_DESCRIPTION" => "-",
		"LIST_BROWSER_TITLE" => "-",
		"LIST_OFFERS_FIELD_CODE" => array(
			0 => "NAME",
			1 => "",
		),
		"LIST_OFFERS_PROPERTY_CODE" => array(
			0 => "",
		),
		"LIST_OFFERS_LIMIT" => "0",
		"DETAIL_PROPERTY_CODE" => array(
			0 => "CODE",
			1 => "FREE_DELIVERY_TILL",
			2 => "BRAND",
			3 => "OLD_PRICE",
			4 => "OFFERS_VIEW",
			5 => "CUSTOM_LABEL_TILL",
			6 => "SHOW_SIZES_TABLE",
			7 => "ADD_PICTURES_LINKS",
			8 => "AUTO_CALCULATED_REVIEWS_AMOUNT",
			9 => "CUSTOM_LABEL",
			10 => "ALSO_BUY",
			11 => "CHARS",
			12 => "NEW_TILL",
			13 => "SPECIAL_OFFER_TILL",
			14 => "PICTURES",
			15 => "ALSO_BUY",
			16 => "ADD_PICTURES",
			17 => "PICTURES_LINKS",
		),
		"DETAIL_META_KEYWORDS" => "-",
		"DETAIL_META_DESCRIPTION" => "-",
		"DETAIL_BROWSER_TITLE" => "-",
		"DETAIL_OFFERS_FIELD_CODE" => array(
			0 => "NAME",
			1 => "",
		),
		"DETAIL_OFFERS_PROPERTY_CODE" => array(
			0 => "CODE",
			1 => "PICTURES",
			2 => "PARAMS",
			3 => "PICTURES_LINKS",
		),
		"LINK_IBLOCK_TYPE" => "",
		"LINK_IBLOCK_ID" => "",
		"LINK_PROPERTY_SID" => "",
		"LINK_ELEMENTS_URL" => "",
		"USE_ALSO_BUY" => "N",
		"USE_STORE" => "N",
		"OFFERS_SORT_FIELD" => "sort",
		"OFFERS_SORT_ORDER" => "asc",
		"OFFERS_SORT_FIELD2" => "CATALOG_AVAILABLE",
		"OFFERS_SORT_ORDER2" => "desc",
		"PAGER_TEMPLATE" => "",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "",
		"PAGER_SHOW_ALL" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"_GLOBAL_SEARCH_TYPE" => "5",
		"SEF_URL_TEMPLATES" => array(
			"sections" => "",
			"section" => "#SECTION_CODE_PATH#/",
			"element" => "#SECTION_CODE_PATH#/#ELEMENT_CODE#/",
			"compare" => "",
		)
	),
	false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>