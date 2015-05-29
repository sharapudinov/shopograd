<?
// PHPTHUMB PATH (used in some OS, if some images are not shown - try to set empty value)
define(_PHPTHUMB_PATH_PREFIX, $_SERVER['DOCUMENT_ROOT']);

// DETECT INDEX PAGE
if (($APPLICATION->GetCurPage() == SITE_DIR . "index.php") or ($APPLICATION->GetCurPage() == SITE_DIR)) {
	define(_INDEX_PAGE, true);
} else {
	define(_INDEX_PAGE, false);
}

// SET PRICES VIEW CURRENCY
if ($_REQUEST['set_cookie_name'] == "CURRENCY") {
	define(_CURRENCY, $_REQUEST['set_cookie_value']);
} elseif ($APPLICATION->get_cookie("CURRENCY")) {
	define(_CURRENCY, $APPLICATION->get_cookie("CURRENCY"));	
} else {
	define(_CURRENCY, 'RUB');		
}

// PRICE STORAGE DEFAULT CURRENCY
define(_DEFAULT_CURRENCY, 'CHY');
?>