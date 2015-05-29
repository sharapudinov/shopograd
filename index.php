<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Товары из Китая по супер-ценам");
?>
<div class="index_page_banner_wrapper">
    <div class="centered_wrapper">
        <div class="gap40"></div>
        <div class="row collapse">
            <div class="small-12 medium-5 large-3 columns">
            	<?$APPLICATION->IncludeComponent(
                    "bitrix:catalog.section.list",
                    "main_page_top_sections",
                    Array(
                        "IBLOCK_TYPE" => "catalogues",
                        "IBLOCK_ID" => "1",
                        "SECTION_ID" => "",
                        "SECTION_CODE" => "",
                        "SECTION_URL" => "",
                        "COUNT_ELEMENTS" => "N",
                        "TOP_DEPTH" => "999",
                        "SECTION_FIELDS" => array(),
                        "SECTION_USER_FIELDS" => array("UF_SHOW_ON_MAIN_PAGE"),
                        "ADD_SECTIONS_CHAIN" => "N",
                        "CACHE_TYPE" => 'A',
                        "CACHE_TIME" => '86400',
                        "CACHE_GROUPS" => 'Y'
                    ),
                false
                );?>
            </div>
            <div class="small-1 medium-1 large-1 columns">
                <div class="gap20"></div>
            </div>
            <div class="small-12 medium-6 large-8 columns">
            	<?$APPLICATION->IncludeComponent(
                    "bitrix:news.list",
                    "main_page_banner",
                    Array(
                        "DISPLAY_DATE" => "N",
                        "DISPLAY_NAME" => "N",
                        "DISPLAY_PICTURE" => "N",
                        "DISPLAY_PREVIEW_TEXT" => "N",
                        "AJAX_MODE" => "N",
                        "IBLOCK_TYPE" => "info",
                        "IBLOCK_ID" => "10",
                        "NEWS_COUNT" => "5",
                        "SORT_BY1" => "SORT",
                        "SORT_ORDER1" => "ASC",
                        "SORT_BY2" => "rand",
                        "SORT_ORDER2" => "ASC",
                        "FILTER_NAME" => "",
                        "FIELD_CODE" => array(),
                        "PROPERTY_CODE" => array("LINK","MOBILE_PICTURE"),
                        "CHECK_DATES" => "Y",
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
                        "CACHE_FILTER" => "N",
                        "CACHE_GROUPS" => "Y",
                        "PAGER_TEMPLATE" => "",
                        "DISPLAY_TOP_PAGER" => "N",
                        "DISPLAY_BOTTOM_PAGER" => "N",
                        "PAGER_TITLE" => "",
                        "PAGER_SHOW_ALWAYS" => "N",
                        "PAGER_DESC_NUMBERING" => "N",
                        "PAGER_DESC_NUMBERING_CACHE_TIME" => "",
                        "PAGER_SHOW_ALL" => "N",
                        "AJAX_OPTION_JUMP" => "N",
                        "AJAX_OPTION_STYLE" => "Y",
                        "AJAX_OPTION_HISTORY" => "N"
                    ),
                false
                );?>
            </div>
        </div>
        <div class="gap40"></div>
    </div>
</div>
<div class="gap40"></div>
<div class="centered_wrapper">
	<?
	global $arMainPageProductsFilter;
	$arMainPageProductsFilter = array(">PROPERTY_SHOW_ON_MAIN_PAGE_TILL" => date("Y-m-d H:i:s"));
	?>
	<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.section", 
	".default", 
	array(
		"AJAX_MODE" => "N",
		"IBLOCK_TYPE" => "catalogues",
		"IBLOCK_ID" => "1",
		"SECTION_ID" => "",
		"SECTION_CODE" => "",
		"SECTION_USER_FIELDS" => array(
			0 => "",
			1 => "",
		),
		"ELEMENT_SORT_FIELD" => "sort",
		"ELEMENT_SORT_ORDER" => "asc",
		"ELEMENT_SORT_FIELD2" => "rand",
		"ELEMENT_SORT_ORDER2" => "desc",
		"FILTER_NAME" => "arMainPageProductsFilter",
		"INCLUDE_SUBSECTIONS" => "A",
		"SHOW_ALL_WO_SECTION" => "Y",
		"SECTION_URL" => "",
		"DETAIL_URL" => "",
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"SET_META_KEYWORDS" => "N",
		"META_KEYWORDS" => "-",
		"SET_META_DESCRIPTION" => "N",
		"META_DESCRIPTION" => "-",
		"BROWSER_TITLE" => "-",
		"ADD_SECTIONS_CHAIN" => "N",
		"DISPLAY_COMPARE" => "N",
		"SET_TITLE" => "N",
		"SET_STATUS_404" => "N",
		"PAGE_ELEMENT_COUNT" => "15",
		"LINE_ELEMENT_COUNT" => "",
		"PROPERTY_CODE" => array(
			0 => "OLD_PRICE",
			1 => "CUSTOM_LABEL_TILL",
			2 => "AUTO_CALCULATED_REVIEWS_AMOUNT",
			3 => "CUSTOM_LABEL",
			4 => "NEW_TILL",
			5 => "SPECIAL_OFFER_TILL",
			6 => "OLD_PRICE",
			7 => "",
		),
		"OFFERS_FIELD_CODE" => array(
			0 => "ID",
			1 => "",
		),
		"OFFERS_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"OFFERS_SORT_FIELD" => "sort",
		"OFFERS_SORT_ORDER" => "asc",
		"OFFERS_SORT_FIELD2" => "id",
		"OFFERS_SORT_ORDER2" => "desc",
		"OFFERS_LIMIT" => "999",
		"PRICE_CODE" => array(
			0 => "RETAIL",
		),
		"USE_PRICE_COUNT" => "N",
		"SHOW_PRICE_COUNT" => "1",
		"PRICE_VAT_INCLUDE" => "Y",
		"BASKET_URL" => "",
		"ACTION_VARIABLE" => "",
		"PRODUCT_ID_VARIABLE" => "",
		"USE_PRODUCT_QUANTITY" => "N",
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"ADD_PROPERTIES_TO_BASKET" => "N",
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"PRODUCT_PROPERTIES" => array(
		),
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "86400",
		"CACHE_FILTER" => "Y",
		"CACHE_GROUPS" => "Y",
		"PAGER_TEMPLATE" => "",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"PAGER_TITLE" => "",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "",
		"PAGER_SHOW_ALL" => "N",
		"HIDE_NOT_AVAILABLE" => "N",
		"CONVERT_CURRENCY" => "Y",
		"CURRENCY_ID" => _CURRENCY,
		"OFFERS_CART_PROPERTIES" => array(
		),
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"SET_BROWSER_TITLE" => "Y"
	),
	false
);?>
</div>
<div class="gap20"></div>
<div class="reviews_wrapper">
	<div class="gap20"></div><div class="gap5"></div><div class="gap5"></div>
	<div class="centered_wrapper">
    	<?$APPLICATION->IncludeComponent(
            "bitrix:news.list",
            "index_page_reviews",
            Array(
                "AJAX_MODE" => "N",
                "IBLOCK_TYPE" => "system",
                "IBLOCK_ID" => "8",
                "NEWS_COUNT" => "4",
                "SORT_BY1" => "ID",
                "SORT_ORDER1" => "DESC",
                "SORT_BY2" => "rand",
                "SORT_ORDER2" => "ASC",
                "FILTER_NAME" => "",
                "FIELD_CODE" => array(),
                "PROPERTY_CODE" => array("USER","PRODUCT","PRODUCT_NAME"),
                "CHECK_DATES" => "Y",
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
                "CACHE_TIME" => "3600",
                "CACHE_FILTER" => "N",
                "CACHE_GROUPS" => "Y",
                "PAGER_TEMPLATE" => "",
                "DISPLAY_TOP_PAGER" => "N",
                "DISPLAY_BOTTOM_PAGER" => "N",
                "PAGER_TITLE" => "",
                "PAGER_SHOW_ALWAYS" => "N",
                "PAGER_DESC_NUMBERING" => "N",
                "PAGER_DESC_NUMBERING_CACHE_TIME" => "",
                "PAGER_SHOW_ALL" => "N",
                "AJAX_OPTION_JUMP" => "N",
                "AJAX_OPTION_STYLE" => "Y",
                "AJAX_OPTION_HISTORY" => "N"
            ),
        false
        );?>
    	<div class="gap5"></div>
    </div>
</div>
<div class="gap20"></div>
<div class="centered_wrapper">
    <div class="row collapse">
        <div class="small-12 medium-7 large-8 columns">
        	<?$APPLICATION->IncludeComponent(
                "bitrix:main.include",
                "",
                Array(
                    "AREA_FILE_SHOW" => "file",
                    "PATH" => "/_includes/main_page_seo_text.php",
                    "EDIT_TEMPLATE" => ""
                ),
            false
            );?>
        </div>
        <div class="small-1 columns">
            <div class="gap5"></div>
        </div>
        <div class="small-12 medium-4 large-3 columns">
            <h1>Что нового?</h1>
            <?$APPLICATION->IncludeComponent(
	"bitrix:news.list", 
	"index_page_news", 
	array(
		"AJAX_MODE" => "N",
		"IBLOCK_TYPE" => "info",
		"IBLOCK_ID" => "9",
		"NEWS_COUNT" => "3",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_ORDER1" => "DESC",
		"SORT_BY2" => "rand",
		"SORT_ORDER2" => "ASC",
		"FILTER_NAME" => "",
		"FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"CHECK_DATES" => "Y",
		"DETAIL_URL" => "",
		"PREVIEW_TRUNCATE_LEN" => "",
		"ACTIVE_DATE_FORMAT" => "j F Y",
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
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"PAGER_TEMPLATE" => "",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "N",
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
        </div>
    </div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>