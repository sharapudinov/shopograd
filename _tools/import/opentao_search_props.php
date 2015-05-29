<?
// USAGE: Call www.site.ru/_tools/import/opentao_search_props.php?secret=taobao from your browser

// SETTINGS
$arSettings = array(
	"WEBSERVICE" => "http://otapi.net/OtapiWebService2.asmx/GetCategorySearchProperties?instanceKey=710057b8-b668-41f9-a009-f60b82462a63&language=ru",
	"SECRET" => 'taobao', // secret word to prevent unallowed runs
	"CATALOGUE_IBLOCK_ID" => 1,
	"SECTION_ID_FIELD" => "UF_TAOBAO_ID",
	"IMPORTED_SEARCH_PROPS_FIELD" => "UF_IMPORT_SEARCH",
);

if ($_REQUEST['secret'] != $arSettings['SECRET']) {
	exit;	
}

define("LANG", "ru"); 
define("NO_KEEP_STATISTIC", true); 
define("NOT_CHECK_PERMISSIONS", true); 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); 

CModule::IncludeModule('iblock');
$obIblockSection = new CIBlockSection;

// MAIN LOGICS
$obSections = CIBlockSection::GetList(array('ID'=>'ASC'), array('IBLOCK_ID'=>$arSettings['CATALOGUE_IBLOCK_ID'],"!" . $arSettings["SECTION_ID_FIELD"]=>false), false, array('ID', 'IBLOCK_ID', 'NAME', $arSettings["SECTION_ID_FIELD"]));
while($arSection = $obSections->GetNext()) {
	?>
	<p><strong>Searching Taobao section <?=$arSection[$arSettings["SECTION_ID_FIELD"]]?> search properties for our section <?=$arSection['NAME']?>...</strong></p>
	<ul>
	<?
	$curl = curl_init();
	$general_error = false;
	curl_setopt($curl, CURLOPT_URL, $arSettings["WEBSERVICE"]."&categoryId=".$arSection[$arSettings["SECTION_ID_FIELD"]]);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_HEADER, 0);
	$result = curl_exec($curl);
	if ($result === FALSE) {
		$general_error = true;
	}
	$xmlObject = simplexml_load_string($result);
	curl_close($curl);
	if ((string)$xmlObject->ErrorCode !== 'Ok') {
		$general_error = true;
	}
	if(!$general_error) {
		$arFoundProps = array();
		foreach($xmlObject->SearchPropertyInfoList->Content->Item as $arItem) {
			if(strlen((string)$arItem->Name)) {
				$arFoundProps[] = (string)$arItem->Name;
				?>
				<li><?=(string)$arItem->Name;?> [<?=(integer)$arItem->Id?>]</li>
				<?
			}
		}
		if(count($arFoundProps)) {
			$obIblockSection->Update($arSection['ID'], array($arSettings["IMPORTED_SEARCH_PROPS_FIELD"]=>$arFoundProps));	
		}
	}
	?>
	</ul>
	<?
}
?>