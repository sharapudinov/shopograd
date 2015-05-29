<?
CModule::IncludeModule("iblock");
CModule::IncludeModule("sale");
foreach($arResult['ITEMS']['AnDelCanBuy'] as $k=>$arItem) {
	$arItemMainFields = CIBlockElement::GetByID($arItem['PRODUCT_ID'])->GetNext();
	$arItemFields=CIBlockElement::GetList(array(),array('IBLOCK_ID'=>$arItemMainFields['IBLOCK_ID'],'ID'=>$arItem['PRODUCT_ID']), false, false, array('ID','IBLOCK_ID','PREVIEW_PICTURE', 'PROPERTY_CODE', 'PROPERTY_PICTURES', 'PROPERTY_PICTURES_LINKS', 'PROPERTY_MIN_ORDER', 'PROPERTY_CML2_LINK.PREVIEW_PICTURE', 'PROPERTY_CML2_LINK.PROPERTY_CODE'))->GetNext();
	if($arItemFields['PREVIEW_PICTURE']) {
		$arResult['ITEMS']['AnDelCanBuy'][$k]['_PICTURE'] = CFile::GetPath($arItemFields['PREVIEW_PICTURE']);
	} elseif($arItemFields['PROPERTY_PICTURES_VALUE']) {
		if(is_array($arItemFields['PROPERTY_PICTURES_VALUE'])) {
			$arResult['ITEMS']['AnDelCanBuy'][$k]['_PICTURE'] = CFile::GetPath($arItemFields['PROPERTY_PICTURES_VALUE'][0]);	
		} else {
			$arResult['ITEMS']['AnDelCanBuy'][$k]['_PICTURE'] = CFile::GetPath($arItemFields['PROPERTY_PICTURES_VALUE']);	
		}
	} elseif($arItemFields['PROPERTY_PICTURES_LINKS_VALUE']) {
		if(is_array($arItemFields['PROPERTY_PICTURES_LINKS_VALUE'])) {
			$arResult['ITEMS']['AnDelCanBuy'][$k]['_PICTURE'] = $arItemFields['PROPERTY_PICTURES_LINKS_VALUE'][0];	
		} else {
			$arResult['ITEMS']['AnDelCanBuy'][$k]['_PICTURE'] = $arItemFields['PROPERTY_PICTURES_LINKS_VALUE'];	
		}
	} elseif($arItemFields['PROPERTY_CML2_LINK_PREVIEW_PICTURE']) {
		$arResult['ITEMS']['AnDelCanBuy'][$k]['_PICTURE'] = CFile::GetPath($arItemFields['PROPERTY_CML2_LINK_PREVIEW_PICTURE']);
	}
	if(!$arResult['ITEMS']['AnDelCanBuy'][$k]['_PICTURE']) {
		$arResult['ITEMS']['AnDelCanBuy'][$k]['_PICTURE'] = CFile::GetPath($arItem['PREVIEW_PICTURE']);	
	}
	if($arItemFields['PROPERTY_CODE_VALUE']) {
		$arResult['ITEMS']['AnDelCanBuy'][$k]['_CODE'] = $arItemFields['PROPERTY_CODE_VALUE'];
	} elseif($arItemFields['PROPERTY_CML2_LINK_PROPERTY_CODE_VALUE']) {
		$arResult['ITEMS']['AnDelCanBuy'][$k]['_CODE'] = $arItemFields['PROPERTY_CML2_LINK_PROPERTY_CODE_VALUE'];	
	}
}
?>