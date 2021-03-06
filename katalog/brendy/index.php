<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Бренды");
?>
    <div class="centered_wrapper">
    <div class="page_title" id="page_title">
        <h1 class="inner">
            <? $APPLICATION->ShowTitle(false) ?>
        </h1>
        <? $APPLICATION->IncludeComponent(
            "bitrix:breadcrumb",
            "",
            Array(
                "START_FROM" => "0",
                "PATH" => "",
                "SITE_ID" => "-"
            ),
            false,
            Array('HIDE_ICONS' => 'Y')
        );
        ?>


        <div class="fade"></div>
    </div>
<? $APPLICATION->ShowProperty("delayed_content_before"); // see helpers/delayed.php
?>
<? $APPLICATION->IncludeComponent(
    "bitrix:news",
    "brands",
    Array(
        "SEF_MODE" => "Y",
        "AJAX_MODE" => "N",
        "IBLOCK_TYPE" => "info",
        "IBLOCK_ID" => "11",
        "NEWS_COUNT" => "12",
        "USE_SEARCH" => "N",
        "USE_RSS" => "N",
        "USE_RATING" => "N",
        "USE_CATEGORIES" => "N",
        "USE_FILTER" => "N",
        "SORT_BY1" => "SORT",
        "SORT_ORDER1" => "ASC",
        "SORT_BY2" => "NAME",
        "SORT_ORDER2" => "ASC",
        "CHECK_DATES" => "Y",
        "PREVIEW_TRUNCATE_LEN" => "",
        "LIST_ACTIVE_DATE_FORMAT" => "",
        "LIST_FIELD_CODE" => array(),
        "LIST_PROPERTY_CODE" => array(),
        "HIDE_LINK_WHEN_NO_DETAIL" => "N",
        "DISPLAY_NAME" => "N",
        "META_KEYWORDS" => "-",
        "META_DESCRIPTION" => "-",
        "BROWSER_TITLE" => "-",
        "DETAIL_ACTIVE_DATE_FORMAT" => "",
        "DETAIL_FIELD_CODE" => array(),
        "DETAIL_PROPERTY_CODE" => array(),
        "DETAIL_DISPLAY_TOP_PAGER" => "N",
        "DETAIL_DISPLAY_BOTTOM_PAGER" => "N",
        "DETAIL_PAGER_TITLE" => "",
        "DETAIL_PAGER_TEMPLATE" => "",
        "DETAIL_PAGER_SHOW_ALL" => "N",
        "SET_STATUS_404" => "N",
        "SET_TITLE" => "Y",
        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
        "ADD_SECTIONS_CHAIN" => "N",
        "ADD_ELEMENT_CHAIN" => "N",
        "USE_PERMISSIONS" => "N",
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
        "SEF_FOLDER" => "/katalog/brend/",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "AJAX_OPTION_HISTORY" => "N",
        "SEF_URL_TEMPLATES" => Array(
            "detail" => "#ELEMENT_CODE#/"
        ),
        "VARIABLE_ALIASES" => Array(
            "detail" => Array(),
        )
    )
); ?><? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>