<?
CModule::IncludeModule("iblock");
if($arResult['DISPLAY_PROPERTIES']['PICTURES']['FILE_VALUE']['SRC']) {
	$arResult['DISPLAY_PROPERTIES']['PICTURES']['FILE_VALUE'] = array($arResult['DISPLAY_PROPERTIES']['PICTURES']['FILE_VALUE']);
}
if($arResult['DISPLAY_PROPERTIES']['ADD_PICTURES']['FILE_VALUE']['SRC']) {
	$arResult['DISPLAY_PROPERTIES']['ADD_PICTURES']['FILE_VALUE'] = array($arResult['DISPLAY_PROPERTIES']['ADD_PICTURES']['FILE_VALUE']);
}
$arResult['DISPLAY_PROPERTIES']['PICTURES']['FILE_VALUE'] = array_slice($arResult['DISPLAY_PROPERTIES']['PICTURES']['FILE_VALUE'], 0, 4);
if(count($arResult['OFFERS'])) {
	/*foreach($arResult['OFFERS'] as $k=>$arOffer) {
		if(!$arOffer['CAN_BUY']) {
			unset($arResult['OFFERS'][$k]);
		}	
	}*/
	$arOffersDisplayProperties = array();
	$arOffersProperties = array();
	foreach($arResult['OFFERS'] as $k=>$arOffer) {
		// fix bug when in some cases display properties are not present in offer array
		if(count($arOffer['DISPLAY_PROPERTIES'])) {
			$arOffersDisplayProperties[$arOffer['ID']] = $arOffer['DISPLAY_PROPERTIES'];
			$arOffersProperties[$arOffer['ID']] = $arOffer['PROPERTIES'];
		}
	}
	$arSelectParams = array(); // param_name=>param_values array
	foreach($arResult['OFFERS'] as $k=>$arOffer) {
		
		$arResult['OFFERS'][$k]['DISPLAY_PROPERTIES'] = $arOffersDisplayProperties[$arOffer['ID']];
		$arResult['OFFERS'][$k]['PROPERTIES'] = $arOffersProperties[$arOffer['ID']];
		
		$arOfferIBFields = CIBlockElement::GetByID($arOffer['ID'])->GetNext();
		$arResult['OFFERS'][$k]['_IBLOCK_FIELDS'] = $arOfferIBFields;
		
		foreach($arOffer['DISPLAY_PROPERTIES']['PARAMS']['VALUE'] as $i=>$v) {
			$arSelectParams[$v][] = $arOffer['DISPLAY_PROPERTIES']['PARAMS']['DESCRIPTION'][$i];
		}
		$arResult['OFFERS'][$k]['_PRICE'] = print_price($arOffer['MIN_PRICE']['PRINT_DISCOUNT_VALUE']);  // see {main_template}/helpers/functions.php 
		$price_value = $arOffer['MIN_PRICE']['DISCOUNT_VALUE'];
		if($arParams['_FREE_DELIVERY_FROM'] and ($price_value>$arParams['_FREE_DELIVERY_FROM'])) {
			$arResult['_FREE_DELIVERY']	= true;
		}
		if($arOffer['MIN_PRICE']['VALUE']>$arOffer['MIN_PRICE']['DISCOUNT_VALUE']) {
			$arResult['OFFERS'][$k]['_OLD_PRICE'] = intval($arOffer['MIN_PRICE']['VALUE']);
			$arResult['_LABEL'] = 'цена ниже';
		}
		if(!$arResult['OFFERS'][$k]['_OLD_PRICE'] and $arResult['DISPLAY_PROPERTIES']['SPECIAL_OFFER_TILL']['VALUE'] and (time()<strtotime($arResult['DISPLAY_PROPERTIES']['SPECIAL_OFFER_TILL']['VALUE'])) and $arResult['DISPLAY_PROPERTIES']['OLD_PRICE']['VALUE']) {
			$arResult['OFFERS'][$k]['_OLD_PRICE'] = intval($price_value*(100+intval($arResult['DISPLAY_PROPERTIES']['OLD_PRICE']['VALUE']))/100);
			$arResult['_LABEL'] = 'цена ниже';
		}
	}
	$arFinalSelectParams = array();
	foreach($arSelectParams as $i=>$v) {
		$arFinalSelectParams[$i] = array_unique($v);
	}
	$arResult['_SELECT_PARAMS'] = $arFinalSelectParams;
	if(!$arResult['DISPLAY_PROPERTIES']['OFFERS_VIEW']['VALUE_ENUM_ID']) {
		if(count($arResult['_SELECT_PARAMS'])) {
			$arResult['_OFFERS_VIEW'] = 2;	
		} else {
			$arResult['_OFFERS_VIEW'] = 1;	
		}
	} else {
		$arResult['_OFFERS_VIEW'] = $arResult['DISPLAY_PROPERTIES']['OFFERS_VIEW']['VALUE_ENUM_ID'];	
	}
	if($arParams['_OFFER_ID']) {
		foreach($arResult['OFFERS'] as $arOffer) {
			if($arOffer['ID'] == $arParams['_OFFER_ID']) {
				$arResult['_CURRENT_OFFER'] = $arOffer;
			}	
		}	
	}
	if(!$arResult['_CURRENT_OFFER']['ID']) {
		// set offer with maximum amount in stock as default offer
		/*$offer_max_amount = 0;
		foreach($arResult['OFFERS'] as $arOffer) {
			if($arOffer['CAN_BUY'] and ($arOffer['CATALOG_QUANTITY'] > $offer_max_amount)) {
				$offer_max_amount = $arOffer['CATALOG_QUANTITY'];
				$arResult['_CURRENT_OFFER'] = $arOffer;
			}
		}*/
		// set offer with min price in stock as default offer
		$offer_min_price = 999999999;
		foreach($arResult['OFFERS'] as $arOffer) {
			if($arOffer['CAN_BUY'] and ($arOffer['MIN_PRICE']['DISCOUNT_VALUE'] < $offer_min_price)) {
				$offer_min_price = $arOffer['MIN_PRICE']['DISCOUNT_VALUE'];
				$arResult['_CURRENT_OFFER'] = $arOffer;
			}
		}
		if(!$arResult['_CURRENT_OFFER']['ID']) {
			// if no offers in stock - we select first one
			$arResult['_CURRENT_OFFER'] = $arResult['OFFERS'][0];
		}
	}
	if(count($arResult['_CURRENT_OFFER']['DISPLAY_PROPERTIES']['PICTURES']['FILE_VALUE'])) {
		// set custom pictures for current offer
		if($arResult['_CURRENT_OFFER']['DISPLAY_PROPERTIES']['PICTURES']['FILE_VALUE']['SRC']) {
			$arResult['DISPLAY_PROPERTIES']['PICTURES']['FILE_VALUE'] = array($arResult['_CURRENT_OFFER']['DISPLAY_PROPERTIES']['PICTURES']['FILE_VALUE']);
		} else {
			$arResult['DISPLAY_PROPERTIES']['PICTURES']['FILE_VALUE'] = $arResult['_CURRENT_OFFER']['DISPLAY_PROPERTIES']['PICTURES']['FILE_VALUE'];	
		}
		for($z=1;$z<=4;$z++) {
			$arResult['PROPERTIES']['NOT_CROP_IMG_' . $z] = $arResult['_CURRENT_OFFER']['PROPERTIES']['NOT_CROP_IMG_' . $z];
		}
	}
	if(count($arResult['_CURRENT_OFFER']['DISPLAY_PROPERTIES']['PICTURES_LINKS']['VALUE'])) {
		// set custom pictures links for current offer
		if(!is_array($arResult['_CURRENT_OFFER']['DISPLAY_PROPERTIES']['PICTURES_LINKS']['VALUE'])) {
			$arResult['DISPLAY_PROPERTIES']['PICTURES_LINKS']['VALUE'] = array($arResult['_CURRENT_OFFER']['DISPLAY_PROPERTIES']['PICTURES_LINKS']['VALUE']);
		} else {
			$arResult['DISPLAY_PROPERTIES']['PICTURES_LINKS']['VALUE'] = $arResult['_CURRENT_OFFER']['DISPLAY_PROPERTIES']['PICTURES_LINKS']['VALUE'];	
		}
		for($z=1;$z<=4;$z++) {
			$arResult['PROPERTIES']['NOT_CROP_IMG_' . $z] = $arResult['_CURRENT_OFFER']['PROPERTIES']['NOT_CROP_IMG_' . $z];
		}
	}
} else {
	$arResult['_PRICE'] = print_price($arResult['MIN_PRICE']['PRINT_DISCOUNT_VALUE']);  // see {main_template}/helpers/functions.php 
	$price_value = $arResult['MIN_PRICE']['DISCOUNT_VALUE'];
	if($arParams['_FREE_DELIVERY_FROM'] and ($price_value>$arParams['_FREE_DELIVERY_FROM'])) {
		$arResult['_FREE_DELIVERY']	= true;
	}
	if($arResult['MIN_PRICE']['VALUE']>$arResult['MIN_PRICE']['DISCOUNT_VALUE']) {
		$arResult['_OLD_PRICE'] = intval($arResult['MIN_PRICE']['VALUE']);
		$arResult['_LABEL'] = 'цена ниже';
	}
	if(!$arResult['_OLD_PRICE'] and $arResult['DISPLAY_PROPERTIES']['SPECIAL_OFFER_TILL']['VALUE'] and (time()<strtotime($arResult['DISPLAY_PROPERTIES']['SPECIAL_OFFER_TILL']['VALUE'])) and $arResult['DISPLAY_PROPERTIES']['OLD_PRICE']['VALUE']) {
		$arResult['_OLD_PRICE'] = intval($price_value*(100+intval($arResult['DISPLAY_PROPERTIES']['OLD_PRICE']['VALUE']))/100);
		$arResult['_LABEL'] = 'цена ниже';
	}
	$arResult['_CURRENT_OFFER'] = $arResult;	
}
if(!$arResult['_LABEL'] and $arResult['DISPLAY_PROPERTIES']['SPECIAL_OFFER_TILL']['VALUE'] and (time()<strtotime($arResult['DISPLAY_PROPERTIES']['SPECIAL_OFFER_TILL']['VALUE']))) {
	$arResult['_LABEL'] = 'суперцена';	
}
if(!$arResult['_LABEL'] and $arResult['DISPLAY_PROPERTIES']['NEW_TILL']['VALUE'] and (time()<strtotime($arResult['DISPLAY_PROPERTIES']['NEW_TILL']['VALUE']))) {
	$arResult['_LABEL'] = 'новинка';	
}
if($arResult['DISPLAY_PROPERTIES']['CUSTOM_LABEL']['DISPLAY_VALUE'] and $arResult['DISPLAY_PROPERTIES']['CUSTOM_LABEL_TILL']['VALUE'] and (time()<strtotime($arResult['DISPLAY_PROPERTIES']['CUSTOM_LABEL_TILL']['VALUE']))) {
	$arResult['_LABEL'] = $arResult['DISPLAY_PROPERTIES']['CUSTOM_LABEL']['DISPLAY_VALUE'];	
}
if($arResult['DISPLAY_PROPERTIES']['FREE_DELIVERY_TILL']['VALUE'] and (time()<strtotime($arResult['DISPLAY_PROPERTIES']['FREE_DELIVERY_TILL']['VALUE']))) {
	$arResult['_FREE_DELIVERY']	= true;
}
if(count($arResult['DISPLAY_PROPERTIES']['CHARS']['VALUE'])) {
	$arCharsGroups = array();
	$cur_key = 0;
	$arCharsGroups[0] = array('GROUP_NAME'=>false,'GROUP_ITEMS'=>array());
	foreach($arResult['DISPLAY_PROPERTIES']['CHARS']['VALUE'] as $k=>$v) {
		if(preg_match('/\-{3,}/',$arResult['DISPLAY_PROPERTIES']['CHARS']['DESCRIPTION'][$k])) {
			if(($k == 0) and ($cur_key == 0)) {
			} else {
				$cur_key++;	
			}
			$arCharsGroups[$cur_key] = array('GROUP_NAME'=>$v, 'GROUP_ITEMS'=>array());
		} else {
			$arCharsGroups[$cur_key]['GROUP_ITEMS'][] = array('CHAR_NAME'=>$v, 'CHAR_VALUE'=>$arResult['DISPLAY_PROPERTIES']['CHARS']['DESCRIPTION'][$k]);
		}
	}
	if(count($arCharsGroups)>1) {
		// correctly divide groups in two columns (first must be bigger)
		if(count($arCharsGroups)==2) {
			if(count($arCharsGroups[0]['GROUP_ITEMS'])<count($arCharsGroups[1]['GROUP_ITEMS'])) {
				$arTmp = $arCharsGroups[0];
				$arCharsGroups[0] = $arCharsGroups[1];
				$arCharsGroups[1] = $arTmp;	
			}
		} else {
			$items_counter = 0;
			foreach($arCharsGroups as $k=>$arCharsGroup) {
				if($arCharsGroup['GROUP_NAME']) {
					$items_counter++;
					if($items_counter==ceil(count($arResult['DISPLAY_PROPERTIES']['CHARS']['VALUE'])/2)) {
						$arResult['_CHARS_GROUPS_DIVIDER'] = $k;
					}
				}
				foreach($arCharsGroup['GROUP_ITEMS'] as $char) {
					$items_counter++;
					if($items_counter==ceil(count($arResult['DISPLAY_PROPERTIES']['CHARS']['VALUE'])/2)) {
						$arResult['_CHARS_GROUPS_DIVIDER'] = $k;
					}
				}
			}	
		}
	}
	$arResult['_CHARS'] = $arCharsGroups;
	
}
/*TODO!!! Add also bought products from orders stat!*/
$arResult['_ALSO_BUY'] = $arResult['DISPLAY_PROPERTIES']['ALSO_BUY']['VALUE'];
/*TODO!!! Add also bought products from orders stat!*/
?>