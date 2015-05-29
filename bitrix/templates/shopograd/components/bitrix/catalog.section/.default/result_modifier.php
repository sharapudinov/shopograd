<?
foreach($arResult['ITEMS'] as $k=>$arItem) {
	if(count($arItem['OFFERS'])>0) {
		// rich russian language...
		$offers_amount = count($arItem['OFFERS']);
		$num_lang_transform = "вариантов";
		if (!(($offers_amount>10) and ($offers_amount<20))) {
			preg_match('/\d$/', $offers_amount, $matches);
			if ($matches[0] == 1) {
				$num_lang_transform = "вариант";
			} else {
				if (($matches[0]>1) and ($matches[0]<5)) {
					$num_lang_transform = "варианта";
				}
			}
		}
		$arItem['_OFFERS_AMOUNT'] = $offers_amount . " " . $num_lang_transform;
	} else {
		$arItem['_OFFERS_AMOUNT'] = '1 вариант';	
	}
	$label = false; // sale, new, etc...
	$price = false; // final price to be printed (with currency)
	$price_value = false; // final price number
	$old_price = false; // old price number
	$price_prefix = false; // 'from'
	if(count($arItem['OFFERS'])>0) {
		$min_offer_price = 999999999;
		$max_offer_price = 0;
		foreach($arItem['OFFERS'] as $arOffer) {
			if($arOffer['MIN_PRICE']['DISCOUNT_VALUE']<$min_offer_price) {
				$min_offer_price = $arOffer['MIN_PRICE']['DISCOUNT_VALUE'];
				$price = $arOffer['MIN_PRICE']['PRINT_DISCOUNT_VALUE'];
				$price_value = $arOffer['MIN_PRICE']['DISCOUNT_VALUE'];
				if($arOffer['MIN_PRICE']['VALUE']>$arOffer['MIN_PRICE']['DISCOUNT_VALUE']) {
					$old_price = intval($arOffer['MIN_PRICE']['VALUE']);
					$label = 'цена ниже';
				} else {
					$old_price = false;	
				}
			}
			if($arOffer['MIN_PRICE']['DISCOUNT_VALUE']>$max_offer_price) {
				$max_offer_price = $arOffer['MIN_PRICE']['DISCOUNT_VALUE'];
			}
		}
		$price = print_price($price);  // see {main_template}/helpers/functions.php 
		if(($min_offer_price<$max_offer_price) and !$old_price) {
			$price_prefix = 'от';
		}
	} elseif($arItem['MIN_PRICE']['PRINT_DISCOUNT_VALUE']) {
		$price = print_price($arItem['MIN_PRICE']['PRINT_DISCOUNT_VALUE']);  // see {main_template}/helpers/functions.php 
		$price_value = $arItem['MIN_PRICE']['DISCOUNT_VALUE'];
		if($arItem['MIN_PRICE']['VALUE']>$arItem['MIN_PRICE']['DISCOUNT_VALUE']) {
			$old_price = intval($arItem['MIN_PRICE']['VALUE']);
			$label = 'цена ниже';
		}
	} elseif($arItem['PROPERTIES']['IMPORT_TMP_PRICE']['VALUE'] and $arItem['PROPERTIES']['IMPORT_TMP_PRICE_CURRENCY']['VALUE']) {
		$price = print_price(CurrencyFormat($arItem['PROPERTIES']['IMPORT_TMP_PRICE']['VALUE'], $arItem['PROPERTIES']['IMPORT_TMP_PRICE_CURRENCY']['VALUE']));  // see {main_template}/helpers/functions.php 
		$price_value = $arItem['PROPERTIES']['IMPORT_TMP_PRICE']['VALUE'];
		if($arParams['CURRENCY_ID']) {
			CModule::IncludeModule("currency");
			$price_value = CCurrencyRates::ConvertCurrency($arItem['PROPERTIES']['IMPORT_TMP_PRICE']['VALUE'], $arItem['PROPERTIES']['IMPORT_TMP_PRICE_CURRENCY']['VALUE'], $arParams['CURRENCY_ID']);
			$price = print_price(CurrencyFormat($price_value, $arParams['CURRENCY_ID']));  // see {main_template}/helpers/functions.php 
		}
		$price_prefix = 'от';
		$arItem['_OFFERS_AMOUNT'] = "&nbsp;";
	}
	if(!$old_price and $arItem['DISPLAY_PROPERTIES']['SPECIAL_OFFER_TILL']['VALUE'] and (time()<strtotime($arItem['DISPLAY_PROPERTIES']['SPECIAL_OFFER_TILL']['VALUE'])) and $arItem['DISPLAY_PROPERTIES']['OLD_PRICE']['VALUE']) {
		$old_price = intval($price_value*(100+intval($arItem['DISPLAY_PROPERTIES']['OLD_PRICE']['VALUE']))/100);
		$price_prefix = false;
		$label = 'цена ниже';
	}
	if(!$label and $arItem['DISPLAY_PROPERTIES']['SPECIAL_OFFER_TILL']['VALUE'] and (time()<strtotime($arItem['DISPLAY_PROPERTIES']['SPECIAL_OFFER_TILL']['VALUE']))) {
		$label = 'суперцена';	
	}
	if(!$label and $arItem['DISPLAY_PROPERTIES']['NEW_TILL']['VALUE'] and (time()<strtotime($arItem['DISPLAY_PROPERTIES']['NEW_TILL']['VALUE']))) {
		$label = 'новинка';	
	}
	if($arItem['DISPLAY_PROPERTIES']['CUSTOM_LABEL']['DISPLAY_VALUE'] and $arItem['DISPLAY_PROPERTIES']['CUSTOM_LABEL_TILL']['VALUE'] and (time()<strtotime($arItem['DISPLAY_PROPERTIES']['CUSTOM_LABEL_TILL']['VALUE']))) {
		$label = $arItem['DISPLAY_PROPERTIES']['CUSTOM_LABEL']['DISPLAY_VALUE'];	
	}
	$arItem['_PRICE_PREFIX'] = $price_prefix;
	$arItem['_PRICE'] = $price;	
	$arItem['_OLD_PRICE'] = $old_price;	
	$arItem['_LABEL'] = $label;	
	$arResult['ITEMS'][$k] = $arItem;
}
?>