<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<?
$arSectionPath = explode('/', $arResult['VARIABLES']['SECTION_CODE_PATH']);
$length = count($arSectionPath);
if ($arSectionPath[$length - 2] == 'brend') {
    $_REQUEST['GLOBAL_SEARCH_TYPE'] = 2;
    $_REQUEST['GLOBAL_SEARCH_CONDITION'] = $arSectionPath[$length - 1];
    if ($length > 2) $arResult['VARIABLES']['SECTION_CODE'] = $arSectionPath[$length - 3];
}


include('global_search.php');
include('filter_and_sorting.php');

$cache = new CPHPCache;

$cacheTTL = (7 * 24 * 60 * 60); // one week
$cacheID = 'CatalogSectionAdditionalDataCache_' . $arResult['VARIABLES']['SECTION_CODE'];
$cacheDir = '/custom_cache/';

if ($cache->InitCache($cacheTTL, $cacheID, $cacheDir)) {
    $arSection = $cache->GetVars();
} else {
    CModule::IncludeModule('iblock');

    $arSection = CIBlockSection::GetList(
        array('ID' => 'ASC'),
        array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'CODE' => $arResult['VARIABLES']['SECTION_CODE']),
        false,
        array('ID', 'IBLOCK_ID', 'IBLOCK_SECTION_ID', 'DESCRIPTION', 'DETAIL_PICTURE', 'UF_*')
    )->GetNext();
    if ($cache->StartDataCache($cacheTTL, $cacheID, $cacheDir)) {
        $cache->EndDataCache($arSection);
    }
}
if ($_REQUEST['GLOBAL_SEARCH_TYPE'] == 2) {
    $cacheTTL = (7 * 24 * 60 * 60); // one week
    $cacheID = 'CatalogBrandAdditionalDataCache_' . $_REQUEST['GLOBAL_SEARCH_CONDITION'];
    $cacheDir = '/custom_cache/';

    if ($cache->InitCache($cacheTTL, $cacheID, $cacheDir)) {
        $arBrand = $cache->GetVars();
    } else {
        CModule::IncludeModule('iblock');
        $arBrand = CIBlockElement::GetList(
            array("SORT" => "ASC"),
            array(
                'IBLOCK_CODE' => 'brands',
                'CODE' => $_REQUEST['GLOBAL_SEARCH_CONDITION']
            ),
            false,
            false,
            array(
                'ID',
                'IBLOCK_ID',
                'DETAIL_TEXT',
                'PREVIEW_PICTURE',
                'NAME'
            )
        )->GetNext();
        if ($cache->StartDataCache($cacheTTL, $cacheID, $cacheDir)) {
            $cache->EndDataCache($arBrand);
        }
    }
    $APPLICATION->AddChainItem("Бренды", "/katalog/brendy/");
    $left_menu_type = 'brands';
} else {
    $left_menu_type = 'sections';
}

$list_template = "";
$subsections_view = "";
$less_columns = "";
global $arProductsFilter;
$arProductsFilter = array_merge($arFilterAndSorting['ITEMS_FILTER'], $GLOBAL_SEARCH_FILTER);
if ($arSection['UF_SUBSECTIONS_VIEW'] and !count($arProductsFilter)) {
    $list_template = "only_set_title_and_nav_chain";
    $subsections_view = "1";
}
if ($arSection['UF_SHOW_LEFT_MENU']) {
    $less_columns = "Y";
    $APPLICATION->SetPageProperty("show_left_column", "Y");
    ob_start();
    if ($left_menu_type == 'sections') {
        $APPLICATION->IncludeComponent(
            "bitrix:catalog.section.list",
            "left_column_menu",
            array(
                "IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
                "IBLOCK_ID" => $arParams['IBLOCK_ID'],
                "SECTION_ID" => $arSection['IBLOCK_SECTION_ID'],
                "TOP_DEPTH" => "1",
                "ADD_SECTIONS_CHAIN" => "N",
                "CACHE_TYPE" => $arParams['CACHE_TYPE'],
                "CACHE_TIME" => $arParams['CACHE_TIME'],
                "CACHE_GROUPS" => $arParams['CACHE_GROUPS'],
                "_CURRENT" => $arSection['ID'],
            ),
            false
        );
    } else {
        $APPLICATION->IncludeComponent(
            "bitrix:menu",
            "left_side",
            array(
                "COMPONENT_TEMPLATE" => "side",
                "ROOT_MENU_TYPE" => "left",
                "MENU_CACHE_TYPE" => "N",
                "MENU_CACHE_TIME" => "3600",
                "MENU_CACHE_USE_GROUPS" => "N",
                "MENU_CACHE_GET_VARS" => array(),
                "MAX_LEVEL" => "1",
                "CHILD_MENU_TYPE" => "left",
                "USE_EXT" => "Y",
                "DELAY" => "N",
                "ALLOW_MULTI_SELECT" => "N",
                "_MOBILE_TITLE" => ""
            ),
            false
        );

    }
    /*$APPLICATION->IncludeComponent(
        "bitrix:catalog.section.list",
        "left_column_menu",
        array(
            "IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
            "IBLOCK_ID" => $arParams['IBLOCK_ID'],
            "SECTION_ID" => $arSection['ID'],
            "TOP_DEPTH" => "1",
            "ADD_SECTIONS_CHAIN" => "N",
            "CACHE_TYPE" => $arParams['CACHE_TYPE'],
            "CACHE_TIME" => $arParams['CACHE_TIME'],
            "CACHE_GROUPS" => $arParams['CACHE_GROUPS']
        ),
        false
    );*/
    $APPLICATION->SetPageProperty("delayed_left_column_content", ob_get_clean());
}
?>

<? $APPLICATION->IncludeComponent(
    "bitrix:catalog.section.list",
    "",
    Array(
        "IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
        "IBLOCK_ID" => $arParams['IBLOCK_ID'],
        "SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
        "SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
        "SECTION_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["section"],
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
        '_FILTER_AND_SORTING' => $arFilterAndSorting,
        "_GLOBAL_SEARCH_FILTER_S" => serialize($GLOBAL_SEARCH_FILTER),
        '_FILTER_AND_SORTING_S' => serialize($arFilterAndSorting),
        "_CURRENCY" => $arParams['CURRENCY_ID'],
        "_VIEW_IMAGES" => $subsections_view,
        "_LESS_COLUMNS" => $less_columns
    ),
    $component
); ?>
<? $APPLICATION->IncludeComponent(
    "bitrix:catalog.section",
    $list_template,
    array(
        "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
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
        "SECTION_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["section"],
        "DETAIL_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["element"],
        'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
        'CURRENCY_ID' => $arParams['CURRENCY_ID'],
        'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],

        "ADD_SECTIONS_CHAIN" => "Y",

        "_LESS_COLUMNS" => $less_columns
    ),
    false
); ?>
<?
if ($_REQUEST['GLOBAL_SEARCH_TYPE'] == 2) {
    $APPLICATION->AddChainItem($arBrand['NAME']);
    $APPLICATION->SetTitle($arBrand['NAME']);

    if ($arSection['UF_BROWSER_TITLE']) {
        $APPLICATION->SetPageProperty("title", $arSectionPath[$length - 1]);
    }
    if ($arSection['UF_DESCRIPTION']) {
        $APPLICATION->SetPageProperty("description", $arSection['UF_DESCRIPTION']);
    }
    if ($arSection['UF_KEYWORDS']) {
        $APPLICATION->SetPageProperty("keywords", $arSection['UF_KEYWORDS']);
    }
    $file = CFile::ResizeImageGet($length == 2 ?$arBrand['PREVIEW_PICTURE']:$arSection['DETAIL_PICTURE'], ['height' => '600px'], BX_RESIZE_IMAGE_PROPORTIONAL_ALT);
    $description = $length == 2 ? $arBrand['DETAIL_TEXT'] : $arSection['DESCRIPTION'];
} else {
    if ($arSection['UF_BROWSER_TITLE']) {
        $APPLICATION->SetPageProperty("title", $arSection['UF_BROWSER_TITLE']);
    }
    if ($arSection['UF_DESCRIPTION']) {
        $APPLICATION->SetPageProperty("description", $arSection['UF_DESCRIPTION']);
    }
    if ($arSection['UF_KEYWORDS']) {
        $APPLICATION->SetPageProperty("keywords", $arSection['UF_KEYWORDS']);
    }
    $file = CFile::ResizeImageGet($arSection['DETAIL_PICTURE'], ['height' => '600px'], BX_RESIZE_IMAGE_PROPORTIONAL_ALT);
    $description = $arSection['DESCRIPTION'];
}

if ($description) {
    ob_start();
    ?>
    <style>
        .description {
            min-height: <?=$file['height']?>px;
        }

        .description img {
            float: right;
            width: 35%;
            padding-left: 2%;
            padding-bottom: 2%;
        }

        .description p {
            text-align: justify;
        }

        .description .border {
            background-color: #f0f0f0;
            height: 20px;
            border-radius: 4px;
            margin-top: 20px;
            margin-bottom: 20px;
        }
    </style>
    <div class='description'>
        <div class="border"></div>
        <img src="<?= $file['src'] ?>"/>

        <p><?= $description ?></p>
    </div>
    <? $APPLICATION->SetPageProperty('SEO_Block', ob_get_clean());
}
