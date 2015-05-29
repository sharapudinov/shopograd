<?
$arNewResult = array();
$counter = 0; // max 5 items
foreach($arResult["SECTIONS"] as $k=>$arSection) {
	if($arSection['UF_SHOW_ON_MAIN_PAGE'] and ($counter<5)) {
		$counter++;
		$arNewResult[] = $arSection;
	}
}
$arResult["SECTIONS"] = $arNewResult;
?>