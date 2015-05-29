<?
if(count($arParams['_GLOBAL_SEARCH_FILTER'])) {
	foreach($arResult["SECTIONS"] as $k=>$arSection) {
		$ob = CIBlockElement::GetList(false, array_merge(array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'ACTIVE' => 'Y', 'SECTION_ACTIVE' => 'Y', 'SECTION_GLOBAL_ACTIVE' => 'Y', 'SECTION_ID'=>$arSection['ID'], 'INCLUDE_SUBSECTIONS'=>'Y'),$arParams['_GLOBAL_SEARCH_FILTER']), array('IBLOCK_ID'));
		$el = $ob->Fetch();
		if(intval($el['CNT'])>0) {
			$arResult["SECTIONS"][$k]['_COUNTER'] = intval($el['CNT']);
		} else {
			unset($arResult["SECTIONS"][$k]); // not show section with zero elements
		}
	}	
} else {
	foreach($arResult["SECTIONS"] as $k=>$arSection) {
		$arResult["SECTIONS"][$k]['_COUNTER'] = $arSection['ELEMENT_CNT'];
	}	
}
if($arParams['_GLOBAL_SEARCH_TYPE']) {
	foreach($arResult["SECTIONS"] as $k=>$arSection) {
		$arResult["SECTIONS"][$k]['SECTION_PAGE_URL'] = $arSection['SECTION_PAGE_URL'] . '?GLOBAL_SEARCH_TYPE=' . $arParams['_GLOBAL_SEARCH_TYPE'];
	}
}
if($arParams['_GLOBAL_SEARCH_CONDITION']) {
	foreach($arResult["SECTIONS"] as $k=>$arSection) {
		$arResult["SECTIONS"][$k]['SECTION_PAGE_URL'] = $arSection['SECTION_PAGE_URL'] . '&GLOBAL_SEARCH_CONDITION=' . urlencode($arParams['_GLOBAL_SEARCH_CONDITION']);
	}
}
if(count($arParams['_GLOBAL_SEARCH_FILTER']) and !count($arResult["SECTIONS"])) {
	// calculate correct price range for section (shown in filter)
	$arFilter = array('IBLOCK_ID'=>$arParams['IBLOCK_ID'],'SECTION_ID'=>$arResult["SECTION"]['ID'],'ACTIVE'=>'Y','ACTIVE_DATE'=>'Y','INCLUDE_SUBSECTIONS'=>'Y');
	$arFilter = array_merge($arFilter,$arParams['_GLOBAL_SEARCH_FILTER']);
	$arMinPriceElement = CIBlockElement::GetList(array('PROPERTY_AUTO_CALCULATED_MIN_PRICE'=>'ASC'), $arFilter, false, array('nTopCount'=>'1'), array('ID','IBLOCK_ID','PROPERTY_AUTO_CALCULATED_MIN_PRICE'))->GetNext();
	$arResult['SECTION']['UF_MIN_PRICE'] = $arMinPriceElement['PROPERTY_AUTO_CALCULATED_MIN_PRICE_VALUE'];
	$arMaxPriceElement = CIBlockElement::GetList(array('PROPERTY_AUTO_CALCULATED_MAX_PRICE'=>'DESC'), $arFilter, false, array('nTopCount'=>'1'), array('ID','IBLOCK_ID','PROPERTY_AUTO_CALCULATED_MAX_PRICE'))->GetNext();
	$arResult['SECTION']['UF_MAX_PRICE'] = $arMaxPriceElement['PROPERTY_AUTO_CALCULATED_MAX_PRICE_VALUE'];
}
?>