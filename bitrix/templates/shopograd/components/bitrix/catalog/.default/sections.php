<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
include('global_search.php');
include('filter_and_sorting.php');
?>
<?
if(count($arFilterAndSorting['ITEMS_FILTER']) or $GLOBAL_SEARCH_FILTER or $GLOBAL_SEARCH_CONDITION or $GLOBAL_SEARCH_TYPE) {
	$less_columns = "";
	$sections_view = "";
} else {
	$sections_view = "1";
	$less_columns = "Y";
	$APPLICATION->SetPageProperty("show_left_column", "Y");
	ob_start();
	$APPLICATION->IncludeComponent(
		"bitrix:catalog.section.list",
		"left_column_menu",
		array(
			"IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
			"IBLOCK_ID" => $arParams['IBLOCK_ID'],
			"TOP_DEPTH" => "1",
			"ADD_SECTIONS_CHAIN" => "N",
			"CACHE_TYPE" => $arParams['CACHE_TYPE'],
			"CACHE_TIME" => $arParams['CACHE_TIME'],
			"CACHE_GROUPS" => $arParams['CACHE_GROUPS']
		),
		false
	);
	$APPLICATION->SetPageProperty("delayed_left_column_content", ob_get_clean());	
}
?>
<?$APPLICATION->IncludeComponent(
    "bitrix:catalog.section.list",
    "",
    Array(
        "IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
        "IBLOCK_ID" => $arParams['IBLOCK_ID'],
        "SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
        "SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
        "SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
        "COUNT_ELEMENTS" => $arParams['SECTION_COUNT_ELEMENTS'],
        "TOP_DEPTH" => "1",
        "SECTION_FIELDS" => array(),
        "SECTION_USER_FIELDS" => array('UF_FILTER_FIELD_1', 'UF_FILTER_FIELD_1_V', 'UF_FILTER_FIELD_2', 'UF_FILTER_FIELD_2_V', 'UF_FILTER_FIELD_3', 'UF_FILTER_FIELD_3_V', 'UF_FILTER_FIELD_4', 'UF_FILTER_FIELD_4_V', 'UF_FILTER_FIELD_5', 'UF_FILTER_FIELD_5_V', 'UF_FILTER_FIELD_1_A', 'UF_FILTER_FIELD_2_A', 'UF_FILTER_FIELD_3_A', 'UF_FILTER_FIELD_4_A', 'UF_FILTER_FIELD_5_A', 'UF_MIN_PRICE', 'UF_MAX_PRICE', 'UF_CURRENCY', 'UF_NOT_CROP_IMG'),
        "ADD_SECTIONS_CHAIN" => "N",
        "CACHE_TYPE" => $arParams['CACHE_TYPE'],
        "CACHE_TIME" => $arParams['CACHE_TIME'],
        "CACHE_GROUPS" => $arParams['CACHE_GROUPS'],
        "_GLOBAL_SEARCH_TYPE" => $GLOBAL_SEARCH_TYPE,
        "_GLOBAL_SEARCH_CONDITION" => $GLOBAL_SEARCH_CONDITION,
        "_GLOBAL_SEARCH_FILTER" => $GLOBAL_SEARCH_FILTER,
        '_FILTER_AND_SORTING'=>$arFilterAndSorting,
        "_GLOBAL_SEARCH_FILTER_S" => serialize($GLOBAL_SEARCH_FILTER),
        '_FILTER_AND_SORTING_S'=>serialize($arFilterAndSorting),
        "_CURRENCY" => $arParams['CURRENCY_ID'],
        "_VIEW_IMAGES"=>$sections_view,
        "_LESS_COLUMNS" => $less_columns
    ),
    $component
);?>
<?if(!$sections_view):?>
	<?
    global $arProductsFilter;
    $arProductsFilter = array_merge($arFilterAndSorting['ITEMS_FILTER'], $GLOBAL_SEARCH_FILTER);
    ?>
    <?$APPLICATION->IncludeComponent(
        "bitrix:catalog.section",
        "",
        array(
            "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
            "IBLOCK_ID" => $arParams["IBLOCK_ID"],
            "ELEMENT_SORT_FIELD" => $arParams["ELEMENT_SORT_FIELD"],
            "ELEMENT_SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"],
            "ELEMENT_SORT_FIELD" => $arFilterAndSorting['SORT'],
            "ELEMENT_SORT_ORDER" => $arFilterAndSorting['ORDER'],
            "ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
            "ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
            "PROPERTY_CODE" => $arParams["LIST_PROPERTY_CODE"],
            "META_KEYWORDS" => $arParams["LIST_META_KEYWORDS"],
            "META_DESCRIPTION" => $arParams["LIST_META_DESCRIPTION"],
            "BROWSER_TITLE" => $arParams["LIST_BROWSER_TITLE"],
            "INCLUDE_SUBSECTIONS" => "Y",
            "SHOW_ALL_WO_SECTION" => 'Y',
            "BASKET_URL" => $arParams["BASKET_URL"],
            "ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
            "PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
            "SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
            "PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
            "PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
            "FILTER_NAME" => 'arProductsFilter',
            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
            "CACHE_TIME" => $arParams["CACHE_TIME"],
            "CACHE_FILTER" => $arParams["CACHE_FILTER"],
            "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
            "SET_TITLE" => $arParams["SET_TITLE"],
            "SET_STATUS_404" => $arParams["SET_STATUS_404"],
            "DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
            "PAGE_ELEMENT_COUNT" => $arParams["PAGE_ELEMENT_COUNT"],
            "LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
            "PRICE_CODE" => $arParams["PRICE_CODE"],
            "USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
            "SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
    
            "PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
            "USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
            "ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
            "PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
            "PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
    
            "DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
            "DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
            "PAGER_TITLE" => $arParams["PAGER_TITLE"],
            "PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
            "PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
            "PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
            "PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
            "PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
    
            "OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
            "OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
            "OFFERS_PROPERTY_CODE" => $arParams["LIST_OFFERS_PROPERTY_CODE"],
            "OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
            "OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
            "OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
            "OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
            "OFFERS_LIMIT" => $arParams["LIST_OFFERS_LIMIT"],
    
            "SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
            "SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
            "SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
            "DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
            'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
            'CURRENCY_ID' => $arParams['CURRENCY_ID'],
            'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],
            
            "ADD_SECTIONS_CHAIN" => "Y",
        ),
        false
    );
    ?>
<?endif;?>