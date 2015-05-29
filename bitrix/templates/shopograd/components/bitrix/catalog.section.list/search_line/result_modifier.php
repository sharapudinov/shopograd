<?
$arNewResult = array();
foreach($arResult["SECTIONS"] as $k=>$arSection) {
	if($arSection['UF_SHOW_IN_SEARCH']) {
		$arNewResult[] = $arSection;
	}
}
$arResult["SECTIONS"] = $arNewResult;
?>