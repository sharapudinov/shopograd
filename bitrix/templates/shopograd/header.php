<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
require_once($_SERVER['DOCUMENT_ROOT'].'/_tools/phpThumb/phpThumb.config.php');
include('helpers/cookies.php');
include('helpers/constants.php');
include('helpers/functions.php');
?>
<!DOCTYPE html>
<html class="no-js">
    <head>
      
        <meta name="viewport" content="width=device-width, initial-scale=0.6" />
        <?$APPLICATION->ShowProperty("delayed_header_meta"); // see helpers/delayed.php ?>
        
        <link rel="icon" type="image/png" href="<?=SITE_TEMPLATE_PATH?>/favicon.png" />
        <link rel="apple-touch-icon" type="image/png" href="<?=SITE_TEMPLATE_PATH?>/favicon57.png" />
        
        <title><?$APPLICATION->ShowTitle()?> &mdash; Шопоград</title>
        
        <link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/foundation.css" />
        <link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/lineicons.css">
        <link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/pushmenu.css">
        
        <script src="<?=SITE_TEMPLATE_PATH?>/js/modernizr.js"></script>
        
        <script src="<?=SITE_TEMPLATE_PATH?>/js/jquery.js"></script>
        
        <script src="<?=SITE_TEMPLATE_PATH?>/js/foundation.js"></script>
        
        <script src="<?=SITE_TEMPLATE_PATH?>/js/fastclick.js"></script>
        <script src="<?=SITE_TEMPLATE_PATH?>/js/jquery.mousewheel.js"></script>
        <script src="<?=SITE_TEMPLATE_PATH?>/js/jquery.bxslider.js"></script>
        <script src="<?=SITE_TEMPLATE_PATH?>/js/jquery.superSimpleTabs.js"></script>
        <script src="<?=SITE_TEMPLATE_PATH?>/js/jquery.customSelect.js"></script>
        <script src="<?=SITE_TEMPLATE_PATH?>/js/jquery.zoom.min.js"></script>
		<script src="<?=SITE_TEMPLATE_PATH?>/js/jquery.cookie.js"></script>
        <script src="<?=SITE_TEMPLATE_PATH?>/js/pushmenu.js"></script>
        <script src="<?=SITE_TEMPLATE_PATH?>/js/cataloguemenu.js"></script>
        <script src="<?=SITE_TEMPLATE_PATH?>/js/pinnedmenu.js"></script>
        
        <script src="<?=SITE_TEMPLATE_PATH?>/js/init.js"></script>
        
        <?$APPLICATION->ShowHead();?>
        
    </head>
    <body>
    <div class="main_container">
        <div class="mp-pusher" id="mp-pusher">
            <nav id="mp-menu" class="mp-menu">
                <div class="mp-level">
                    <h2 class="icon icon-world">Меню</h2>
                    <ul>
                        <li class="icon icon-arrow-left">
                            <a href="/katalog/">Каталог</a>
                            <div class="mp-level">
                                <h2 class="icon icon-arrow-right">Каталог</h2>
                                <?$APPLICATION->IncludeComponent(
                                    "bitrix:catalog.section.list",
                                    "pushmenu",
                                    Array(
                                        "IBLOCK_TYPE" => "catalogues",
                                        "IBLOCK_ID" => "1",
                                        "SECTION_ID" => "",
                                        "SECTION_CODE" => "",
                                        "SECTION_URL" => "",
                                        "COUNT_ELEMENTS" => "N",
                                        "TOP_DEPTH" => "2",
                                        "SECTION_FIELDS" => array(),
                                        "SECTION_USER_FIELDS" => array(),
                                        "ADD_SECTIONS_CHAIN" => "N",
                                        "CACHE_TYPE" => 'A',
                                        "CACHE_TIME" => '86400',
                                        "CACHE_GROUPS" => 'Y'
                                    ),
                                false,
                                array('HIDE_ICONS' => 'Y')
                                );?>
                            </div>
                        </li>
                        <?$APPLICATION->IncludeComponent(
                            "bitrix:menu",
                            "pushmenu",
                            Array(
                                "ROOT_MENU_TYPE" => "top",
                                "MAX_LEVEL" => "1",
                                "CHILD_MENU_TYPE" => "",
                                "USE_EXT" => "N",
                                "DELAY" => "N",
                                "ALLOW_MULTI_SELECT" => "N",
                                "MENU_CACHE_TYPE" => "A",
                                "MENU_CACHE_TIME" => "604800",
                                "MENU_CACHE_USE_GROUPS" => "Y",
                                "MENU_CACHE_GET_VARS" => array()
                            ),
                        false,
                        Array('HIDE_ICONS' => 'Y')
                        );?>
                        <?$APPLICATION->IncludeComponent(
                            "bitrix:menu",
                            "pushmenu",
                            Array(
                                "ROOT_MENU_TYPE" => "top_additional",
                                "MAX_LEVEL" => "1",
                                "CHILD_MENU_TYPE" => "",
                                "USE_EXT" => "N",
                                "DELAY" => "N",
                                "ALLOW_MULTI_SELECT" => "N",
                                "MENU_CACHE_TYPE" => "A",
                                "MENU_CACHE_TIME" => "604800",
                                "MENU_CACHE_USE_GROUPS" => "Y",
                                "MENU_CACHE_GET_VARS" => array()
                            ),
                        false,
                        Array('HIDE_ICONS' => 'Y')
                        );?>
                    </ul>
                </div>
            </nav>
            <div class="scroller" id="content_scroller">
                <div class="scroller-inner" id="content-scroller-inner">
                
                	<?$APPLICATION->ShowPanel();?>
                    
                    <header>
                    	<div class="top_line">
                        	<div class="gap5"></div>
                            <div class="centered_wrapper">
                            	<div class="top_line_inner">
                                    <div class="row collapse">
                                        <div class="large-4 medium-5 small-6 columns">
                                        	<?
											ob_start();
											$APPLICATION->IncludeComponent(
												"bitrix:main.include",
												"",
												Array(
													"AREA_FILE_SHOW" => "file",
													"PATH" => SITE_DIR . "_includes/phone.php",
													"EDIT_TEMPLATE" => ""
												),
											false,
											array("HIDE_ICONS" => "Y")
											);
											$phone = preg_replace('/[^0-9\+]/','',trim(HTMLToTxt(ob_get_clean())));
											?>
                                            <strong><a href="tel:<?=$phone?>" class="tel"><?$APPLICATION->IncludeComponent(
                                                "bitrix:main.include",
                                                "",
                                                Array(
                                                    "AREA_FILE_SHOW" => "file",
                                                    "PATH" => SITE_DIR . "_includes/phone.php",
                                                    "EDIT_TEMPLATE" => ""
                                                ),
                                            false
                                            );?></a></strong> &nbsp;&nbsp;&nbsp; <a href="/o-shopograde/kontaktnaya-informatsiya/" class="contacts"><span class="show-for-large-up">Контактная информация</span><span class="show-for-medium-down">Контакты</span></a>
                                        </div>
                                        <div class="large-5 medium-3 hide-for-small columns">
                                        	<?$APPLICATION->IncludeComponent(
                                                "bitrix:menu",
                                                "top_additional",
                                                Array(
                                                    "ROOT_MENU_TYPE" => "top_additional",
                                                    "MAX_LEVEL" => "1",
                                                    "CHILD_MENU_TYPE" => "",
                                                    "USE_EXT" => "N",
                                                    "DELAY" => "N",
                                                    "ALLOW_MULTI_SELECT" => "N",
                                                    "MENU_CACHE_TYPE" => "A",
                                                    "MENU_CACHE_TIME" => "604800",
                                                    "MENU_CACHE_USE_GROUPS" => "Y",
                                                    "MENU_CACHE_GET_VARS" => array()
                                                ),
                                            	false
                                            );?>
                                            <div class="clear"></div>
                                        </div>
                                        <div class="large-2 medium-3 small-4 columns">
                                        	<?
											$user_name = $USER->GetFirstName();
											?>
                                            <a href="/dlya-zhiteley/" class="auth"><?if($user_name):?><?=$user_name?><?else:?>Для жителей<?endif;?></a>
                                            <div class="clear"></div>
                                        </div>
                                        <div class="large-1 medium-1 small-2 columns small-text-right">
                                        	<a href="<?=$APPLICATION->GetCurPageParam('set_cookie_name=CURRENCY&set_cookie_value=USD&set_cookie_hash=' . md5('CURRENCYUSDsetcookie'), array('set_cookie_name','set_cookie_value','set_cookie_hash'));?>" class="view_currency usd<?if(_CURRENCY=='USD'):?> current<?endif;?>" title="Показывать цены в долларах США"></a>
                                        	<a href="<?=$APPLICATION->GetCurPageParam('set_cookie_name=CURRENCY&set_cookie_value=RUB&set_cookie_hash=' . md5('CURRENCYRUBsetcookie'), array('set_cookie_name','set_cookie_value','set_cookie_hash'));?>" class="view_currency rub<?if(_CURRENCY=='RUB'):?> current<?endif;?>" title="Показывать цены в рублях РФ"></a>
                                            <div class="clear"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="gap5"></div>
                        </div>
                        <div class="centered_wrapper">
                        	<div class="gap5"></div>
                            <div class="gap5"></div>
                            <div class="gap5"></div>
                        	<div class="row collapse">
                            	<div class="large-4 medium-5 small-6 columns">
                                	<a href="/"><img src="<?=SITE_TEMPLATE_PATH?>/img/logo.png" width="225" height="68" alt="Шопоград" title="Шопоград" /></a>
                                </div>
                                <div class="large-8 medium-7 small-6 columns">
                                	<div class="gap20"></div><div class="gap5"></div>
                                    <?$APPLICATION->IncludeComponent(
                                        "custom:catalog.search.line",
                                        "",
                                        Array(
                                            "IBLOCK_TYPE" => "catalogues",
                                            "IBLOCK_ID" => "1",
                                            "SECTION_ID" => "",
                                            "SECTION_CODE" => "",
                                            "SECTION_URL" => "",
                                            "COUNT_ELEMENTS" => "N",
                                            "TOP_DEPTH" => "999",
                                            "SECTION_FIELDS" => array(),
                                            "SECTION_USER_FIELDS" => array(),
                                            "ADD_SECTIONS_CHAIN" => "N",
                                            "CACHE_TYPE" => 'A',
                                            "CACHE_TIME" => '86400',
                                            "CACHE_GROUPS" => 'Y'
                                        ),
                                    false,
                                    array('HIDE_ICONS' => 'Y')
                                    );?>
                                </div>
                            </div>
                        </div>
                        <div class="main_navigation" id="main_navigation">
                            <div class="centered_wrapper">
                                <div class="gap20"></div>
                                    <div class="main_navigation_inner">
                                        <div class="row collapse">
                                        	<div class="large-10 medium-9 small-8 columns">
                                            	<div class="hide-for-small">
                                                    <div class="main_menu" id="main_menu">
                                                        <a href="/katalog/" class="catalogue_menu_toggle" id="catalogue_menu_toggle">Каталог товаров</a>
                                                        <?$APPLICATION->IncludeComponent(
                                                            "bitrix:menu",
                                                            "top",
                                                            Array(
                                                                "ROOT_MENU_TYPE" => "top",
                                                                "MAX_LEVEL" => "1",
                                                                "CHILD_MENU_TYPE" => "",
                                                                "USE_EXT" => "N",
                                                                "DELAY" => "N",
                                                                "ALLOW_MULTI_SELECT" => "N",
                                                                "MENU_CACHE_TYPE" => "A",
                                                                "MENU_CACHE_TIME" => "604800",
                                                                "MENU_CACHE_USE_GROUPS" => "Y",
                                                                "MENU_CACHE_GET_VARS" => array()
                                                            ),
                                                            false
                                                        );?>
                                                        <div class="clear"></div>
                                                    </div>
                                                </div>
                                                <a href="#" class="pushmenu_trigger show-for-small" id="mp-menu-trigger">Навигация по сайту</a>
                                            </div>
                                            <div class="large-2 medium-3 small-4 columns">
                                            	<?$APPLICATION->IncludeComponent(
                                                    "bitrix:sale.basket.basket.line", 
                                                    ".default", 
                                                    array(
                                                        "PATH_TO_BASKET" => "/katalog/oformlenie-zakaza/",
                                                        "PATH_TO_PERSONAL" => "",
                                                        "SHOW_PERSONAL_LINK" => "N",
                                                        "SHOW_NUM_PRODUCTS" => "Y",
                                                        "SHOW_TOTAL_PRICE" => "Y",
                                                        "SHOW_PRODUCTS" => "Y",
                                                        "POSITION_FIXED" => "N",
                                                        "SHOW_EMPTY_VALUES" => "Y",
                                                        "PATH_TO_ORDER" => SITE_DIR."/katalog/oformlenie-zakaza/",
                                                        "SHOW_DELAY" => "Y",
                                                        "SHOW_NOTAVAIL" => "Y",
                                                        "SHOW_SUBSCRIBE" => "Y",
                                                        "SHOW_IMAGE" => "Y",
                                                        "SHOW_PRICE" => "Y",
                                                        "SHOW_SUMMARY" => "Y"
                                                    ),
                                                    false
                                                );?>
                                            </div>
                                        </div>
                                        <div class="catalogue_menu_dropdown" id="catalogue_menu_dropdown">
                                        	<a class="dropdown_header" href="#">
                                            	Каталог товаров
                                                <div class="dropdown_header_lt"></div>
                                                <div class="dropdown_header_t"></div>
                                                <div class="dropdown_header_rt"></div>
                                                <div class="dropdown_header_l"></div>
                                                <div class="dropdown_header_r"></div>
                                                <div class="dropdown_header_c"></div>
                                            </a>
                                            <div class="catalogue_menu_dropdown_t"></div>
                                            <div class="catalogue_menu_dropdown_rt"></div>
                                            <div class="catalogue_menu_dropdown_l"></div>
                                            <div class="catalogue_menu_dropdown_r"></div>
                                            <div class="catalogue_menu_dropdown_lb"></div>
                                            <div class="catalogue_menu_dropdown_b"></div>
                                            <div class="catalogue_menu_dropdown_rb"></div>
                                            
                                            <?$APPLICATION->IncludeComponent(
                                                "bitrix:catalog.section.list",
                                                "catalogue_menu",
                                                Array(
                                                    "IBLOCK_TYPE" => "catalogues",
                                                    "IBLOCK_ID" => "1",
                                                    "SECTION_ID" => "",
                                                    "SECTION_CODE" => "",
                                                    "SECTION_URL" => "",
                                                    "COUNT_ELEMENTS" => "N",
                                                    "TOP_DEPTH" => "2",
                                                    "SECTION_FIELDS" => array(),
                                                    "SECTION_USER_FIELDS" => array(),
                                                    "ADD_SECTIONS_CHAIN" => "N",
                                                    "CACHE_TYPE" => 'A',
                                                    "CACHE_TIME" => '86400',
                                                    "CACHE_GROUPS" => 'Y'
                                                ),
                                            	false
                                            );?>
                                        </div>
                                    </div>
                                <div class="gap20"></div>
                            </div>
                        </div>
                        <div class="main_navigation_gap" id="main_navigation_gap">
                        </div>
                    </header>
                    <main>
                    	<div class="gap5"></div>

                        <?if(!_INDEX_PAGE):?>
                            <div class="centered_wrapper">
                                <h1 class="page_title" id="page_title">
                                	<span class="inner">
                                        <?$APPLICATION->ShowTitle(false)?>
                                    </span>
                                    <?$APPLICATION->IncludeComponent(
                                        "bitrix:breadcrumb",
                                        "",
                                        Array(
                                            "START_FROM" => "0",
                                            "PATH" => "",
                                            "SITE_ID" => "-"
                                        ),
                                    false,
									Array('HIDE_ICONS' => 'Y')
                                    );?>
                                    <div class="fade"></div>
                                    <div class="breadcrumb_replication">
                                    	<?$APPLICATION->IncludeComponent(
                                            "bitrix:breadcrumb",
                                            "",
                                            Array(
                                                "START_FROM" => "0",
                                                "PATH" => "",
                                                "SITE_ID" => "-"
                                            ),
                                        false
                                        );?>
                                    	<div class="fade fade_1"></div>
                                        <div class="fade fade_2"></div>
                                    </div>
                                </h1>
						<?endif;?>
                        <?$APPLICATION->ShowProperty("delayed_content_before"); // see helpers/delayed.php ?>