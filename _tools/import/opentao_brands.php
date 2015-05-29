<?
// USAGE: Call www.site.ru/_tools/import/opentao_brands.php?secret=taobao from your browser

// SETTINGS
$arSettings = array(
	"WEBSERVICE1" => "http://otapi.net/OtapiWebService2.asmx/AuthenticateInstanceOperator?instanceKey=710057b8-b668-41f9-a009-f60b82462a63&language=ru&userLogin=root&userPassword=Dhh0OiD1zImi",
	"WEBSERVICE2" => "http://otapi.net/OtapiWebService2.asmx/SearchOriginalBrandsFrame?instanceKey=710057b8-b668-41f9-a009-f60b82462a63&language=ru&framePosition=0&frameSize=999",
	"SECRET" => 'taobao', // secret word to prevent unallowed runs
	"BRANDS_IBLOCK_ID" => 11,
	"BRANDS_NAMES_PROP" => "IMPORT_NAMES",
	"BRANDS_IDS_PROP" => "IMPORT_TAOBAO_BRANDS",
);

if ($_REQUEST['secret'] != $arSettings['SECRET']) {
	exit;	
}

define("LANG", "ru"); 
define("NO_KEEP_STATISTIC", true); 
define("NOT_CHECK_PERMISSIONS", true); 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); 

CModule::IncludeModule('iblock');
$obIblockElement = new CIBlockElement;

// MAIN LOGICS
$otapi_sessid = false;
$curl = curl_init();
$general_error = false;
curl_setopt($curl, CURLOPT_URL, $arSettings["WEBSERVICE1"]);
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
	$otapi_sessid = (string)$xmlObject->SessionId->Value;
}
if($otapi_sessid) {
	$obBrands = CIBlockElement::GetList(array('ID'=>'ASC'), array('IBLOCK_ID'=>$arSettings["BRANDS_IBLOCK_ID"], 'ACTIVE'=>"Y", "ACTIVE_DATE"=>"Y",'!PROPERTY_' . $arSettings["BRANDS_NAMES_PROP"] => false), false, false, array('ID','IBLOCK_ID','NAME','PROPERTY_' . $arSettings["BRANDS_NAMES_PROP"]));
	while($arBrand = $obBrands->GetNext()) {
		?>
        <p><strong>Searching Taobao brands for brand <?=$arBrand['NAME']?>...</strong></p>
        <ul>
        <?
		$arFoundBrands = array();
		foreach($arBrand['PROPERTY_'.$arSettings["BRANDS_NAMES_PROP"]."_VALUE"] as $brand_search_name) {
			$curl = curl_init();
			$general_error = false;
			curl_setopt($curl, CURLOPT_URL, $arSettings["WEBSERVICE2"]."&sessionId=".$otapi_sessid.'&name='.$brand_search_name);
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
				foreach($xmlObject->BrandInfoList->Content->Item as $arItem) {
					if(((string)$arItem->IsHidden=='false') and ((integer)$arItem->Id == (integer)$arItem->ExternalId)) {
						$arFoundBrands[] = array("VALUE"=>(integer)$arItem->Id,"DESCRIPTION"=>(string)$arItem->Name);
						?>
                        <li><?=(string)$arItem->Name;?> [<?=(integer)$arItem->Id?>]</li>
                        <?
					}
				}
			}
		}
		?>
        </ul>
        <?
		if(count($arFoundBrands)) {
			CIBlockElement::SetPropertyValueCode($arBrand["ID"], $arSettings["BRANDS_IDS_PROP"], $arFoundBrands);
		}
	}
}
?>