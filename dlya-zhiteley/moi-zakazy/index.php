<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Мои заказы");
?><?$APPLICATION->IncludeComponent(
	"bitrix:sale.personal.order", 
	".default", 
	array(
		"STATUS_COLOR_N" => "gray",
		"STATUS_COLOR_C" => "green",
		"STATUS_COLOR_P" => "yellow",
		"STATUS_COLOR_D" => "red",
		"STATUS_COLOR_F" => "gray",
		"STATUS_COLOR_PSEUDO_CANCELLED" => "gray",
		"SEF_MODE" => "N",
		"ORDERS_PER_PAGE" => "5",
		"PATH_TO_PAYMENT" => "/katalog/oformlenie-zakaza/oplata/",
		"PATH_TO_BASKET" => "/katalog/oformlenie-zakaza/",
		"SET_TITLE" => "Y",
		"SAVE_IN_SESSION" => "Y",
		"NAV_TEMPLATE" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "86400",
		"CACHE_GROUPS" => "Y",
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"CUSTOM_SELECT_PROPS" => array(
		),
		"PROP_1" => array(
		),
		"HISTORIC_STATUSES" => array(
			0 => "F",
		),
		"SEF_FOLDER" => "/dlya-zhiteley/moi-zakazy/"
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>