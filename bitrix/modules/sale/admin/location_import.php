<?
use Bitrix\Main;
use Bitrix\Main\Localization\Loc;
use Bitrix\Sale\Location\Admin\Helper;

define("NO_AGENT_CHECK", true);
define("NO_KEEP_STATISTIC", true);

$initialTime = time();
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sale/prolog.php");

Loc::loadMessages(__FILE__);

$APPLICATION->SetTitle(Loc::getMessage('SALE_LOCATION_IMPORT_TITLE'));

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

$APPLICATION->IncludeComponent(
	'bitrix:sale.location.import',
	'admin',
	array(
		'PATH_TO_IMPORT' => Helper::getImportUrl(),
		'INITIAL_TIME' => $initialTime
	),
	false
);
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>
