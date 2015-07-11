<?
require ('include/myClasses/EventsHandler.php');
/************************** HELPERS ********************************/
function getCustomSettings() {
	$arSettings = array(
		"MAIN_CATALOG_IBLOCK_ID" => 1,
		"OFFERS_IBLOCK_ID" => 2,
		"REVIEWS_IBLOCK_ID" => 8,
		"CML2_LINK_PROP_CODE" => "CML2_LINK",
		"REVIEW_TO_PRODUCT_LINK_PROP" => "PRODUCT"
	);
	return $arSettings;
}
function autoCalculateCatalogSectionFields($section_id,$recalc_price,$recalc_filter) {
	// $recalc_price => set prices range for section
	// $recalc_filter => update list of all search criterias
	CModule::IncludeModule("iblock");
	$obIBSection = new CIBlockSection;
	$arSettings = getCustomSettings();

	$arSectionFields = CIBlockSection::GetList(array(), array("IBLOCK_ID"=>$arSettings['MAIN_CATALOG_IBLOCK_ID'], "ID" => $section_id), false, array("ID", "IBLOCK_ID", "UF_*"))->GetNext();
	
	if($recalc_price) {
		$arMinPriceElement = CIBlockElement::GetList(array('PROPERTY_AUTO_CALCULATED_MIN_PRICE'=>'ASC'), array('IBLOCK_ID'=>$arSettings['MAIN_CATALOG_IBLOCK_ID'],'SECTION_ID'=>$section_id,'ACTIVE'=>'Y','ACTIVE_DATE'=>'Y','INCLUDE_SUBSECTIONS'=>'Y'), false, array('nTopCount'=>'1'), array('ID','IBLOCK_ID','PROPERTY_AUTO_CALCULATED_MIN_PRICE'))->GetNext();
		$arMaxPriceElement = CIBlockElement::GetList(array('PROPERTY_AUTO_CALCULATED_MAX_PRICE'=>'DESC'), array('IBLOCK_ID'=>$arSettings['MAIN_CATALOG_IBLOCK_ID'],'SECTION_ID'=>$section_id,'ACTIVE'=>'Y','ACTIVE_DATE'=>'Y','INCLUDE_SUBSECTIONS'=>'Y'), false, array('nTopCount'=>'1'), array('ID','IBLOCK_ID','PROPERTY_AUTO_CALCULATED_MAX_PRICE'))->GetNext();
		if($arMinPriceElement['PROPERTY_AUTO_CALCULATED_MIN_PRICE_VALUE'] and $arMaxPriceElement['PROPERTY_AUTO_CALCULATED_MAX_PRICE_VALUE'] and ((floatval($arSectionFields['UF_MIN_PRICE'])!=floatval($arMinPriceElement['PROPERTY_AUTO_CALCULATED_MIN_PRICE_VALUE'])) or (floatval($arSectionFields['UF_MAX_PRICE'])!=floatval($arMaxPriceElement['PROPERTY_AUTO_CALCULATED_MAX_PRICE_VALUE'])))) {
			$obIBSection->Update($section_id, array('UF_MIN_PRICE'=>$arMinPriceElement['PROPERTY_AUTO_CALCULATED_MIN_PRICE_VALUE'], 'UF_MAX_PRICE'=>$arMaxPriceElement['PROPERTY_AUTO_CALCULATED_MAX_PRICE_VALUE']));	
		}
	}
	
	if($recalc_filter) {
		$arAllFilterValues = array();
		for($i=1;$i<=5;$i++) {
			if($arSectionFields['UF_FILTER_FIELD_'.$i]) {
				$arAllFilterValues['UF_FILTER_FIELD_'.$i.'_V'] = array();
				$obUniqueValues = CIBlockElement::GetList(array(), array("IBLOCK_ID"=>$arSectionFields['IBLOCK_ID'],"SECTION_ID"=>$arSectionFields['ID'],"ACTIVE"=>"Y"), array("PROPERTY_FILTER_FIELD_".$i));
				while($arUniqueValue = $obUniqueValues->Fetch()){
					if(strlen($arUniqueValue['PROPERTY_FILTER_FIELD_' . $i . '_VALUE']) and count($arUniqueValue['CNT'])>0) {
						$arAllFilterValues['UF_FILTER_FIELD_'.$i.'_V'][] = $arUniqueValue['PROPERTY_FILTER_FIELD_' . $i . '_VALUE'];
					}
				}
			}	
		}
		if(count($arAllFilterValues)) {
			$obIBSection->Update($section_id, $arAllFilterValues);	
		}
	}
}
function autoCalculateCatalogProductFields($element_id,$recalc_price,$recalc_reviews,$recalc_search) {
	// $recalc_price => calculate product price range
	// $recalc_reviews => calculate product reviews amount
	// $recalc_other => calculate search string
	CModule::IncludeModule("iblock");
	CModule::IncludeModule("catalog");
	
	$arSettings = getCustomSettings();
	
	$arProductIBFields = CIBlockElement::GetList(array(), array('IBLOCK_ID'=>$arSettings['MAIN_CATALOG_IBLOCK_ID'],'ID'=>$element_id), false, false, array('ID','IBLOCK_ID', 'IBLOCK_SECTION_ID', 'NAME','DETAIL_TEXT','PROPERTY_CODE','PROPERTY_SEARCH_WORDS','PROPERTY_ORIGINAL_LINK','PROPERTY_AUTO_CALCULATED_MIN_PRICE','PROPERTY_AUTO_CALCULATED_MAX_PRICE','PROPERTY_AUTO_CALCULATED_REVIEWS_AMOUNT', 'PROPERTY_AUTO_CALCULATED_SEARCH_WORDS'))->GetNext();
	
	if($arProductIBFields['ID']) {
		if($recalc_price) {
			// price range calculation
			$new_min_price = 999999999999;
			$new_max_price = 0;
			$obProductOffers = CIBlockElement::GetList(array(), array('IBLOCK_ID'=>$arSettings['OFFERS_IBLOCK_ID'],'PROPERTY_'.$arSettings['CML2_LINK_PROP_CODE']=>$arProductIBFields['ID']), false, false, array('ID','IBLOCK_ID'));
			while($arProductOfferIBFields = $obProductOffers->GetNext()) {
				if($arProductOfferIBFields['ID']) {
					//$arProductOfferPrice = CPrice::GetBasePrice($arProductOfferIBFields['ID']);
					$obProductOfferPrice = CPrice::GetList(array(),
						array(
							"PRODUCT_ID" => $arProductOfferIBFields['ID']
						)
					);
					while($arProductOfferPrice = $obProductOfferPrice->GetNext()) {
						if($arProductOfferPrice['PRICE']>0) {
							if($arProductOfferPrice['PRICE']>$new_max_price) {
								$new_max_price = $arProductOfferPrice['PRICE'];	
							}
							if($arProductOfferPrice['PRICE']<$new_min_price) {
								$new_min_price = $arProductOfferPrice['PRICE'];
							}
						}
					}
				}
			}
			if($new_max_price == 0) {
				/*$arProductPrice = CPrice::GetBasePrice($arProductIBFields['ID']);
				if($arProductPrice['PRICE']) {
					$new_max_price = $arProductPrice['PRICE'];
					$new_min_price = $arProductPrice['PRICE'];
				}*/
				$obProductPrice = CPrice::GetList(array(),
					array(
						"PRODUCT_ID" => $arProductIBFields['ID']
					)
				);
				while($arProductPrice = $obProductPrice->GetNext()) {
					if($arProductPrice['PRICE']>0) {
						if($arProductPrice['PRICE']>$new_max_price) {
							$new_max_price = $arProductPrice['PRICE'];	
						}
						if($arProductPrice['PRICE']<$new_min_price) {
							$new_min_price = $arProductPrice['PRICE'];
						}
					}
				}
			}
			if(($new_max_price>0) and ($new_min_price<999999999999)) {
				if(($new_max_price!=$arProductIBFields['PROPERTY_AUTO_CALCULATED_MAX_PRICE_VALUE']) or ($new_min_price!=$arProductIBFields['PROPERTY_AUTO_CALCULATED_MIN_PRICE_VALUE'])) {
					CIBlockElement::SetPropertyValuesEx($arProductIBFields['ID'], $arSettings['MAIN_CATALOG_IBLOCK_ID'], array('AUTO_CALCULATED_MIN_PRICE' => $new_min_price, 'AUTO_CALCULATED_MAX_PRICE' => $new_max_price));
					autoCalculateCatalogSectionFields($arProductIBFields['IBLOCK_SECTION_ID'], true, false);
				}
			}
		}
		if($recalc_reviews) {
			$obProductReviews = CIBlockElement::GetList(false, array('IBLOCK_ID'=>$arSettings["REVIEWS_IBLOCK_ID"],'ACTIVE'=>'Y','PROPERTY_'.$arSettings["REVIEW_TO_PRODUCT_LINK_PROP"]=>$arProductIBFields['ID']), array('IBLOCK_ID'));
			if ($arProductReviews = $obProductReviews->Fetch()) {
				if(intval($arProductReviews['CNT'])>0) {
					CIBlockElement::SetPropertyValuesEx($arProductIBFields['ID'], $arSettings['MAIN_CATALOG_IBLOCK_ID'], array('AUTO_CALCULATED_REVIEWS_AMOUNT' => intval($arProductReviews['CNT'])));
				}	
			}
		}
		if($recalc_search) {
			$search_str = $arProductIBFields['NAME'] . " " . HTMLToTxt($arProductIBFields['DETAIL_TEXT']) . " " . $arProductIBFields['PROPERTY_CODE_VALUE'] . " " . $arProductIBFields['PROPERTY_SEARCH_WORDS_VALUE']['TEXT'] . " " . HTMLToTxt($arProductIBFields['PROPERTY_ORIGINAL_LINK_VALUE']) . " " . str_replace('-',' ',str_replace(array('"','\'',',','.'),'',str_replace(array('<','>','$'),'',HTMLToTxt($arProductIBFields['PROPERTY_ORIGINAL_LINK_VALUE']))));
			if($search_str!=$arProductIBFields['PROPERTY_AUTO_CALCULATED_SEARCH_WORDS_VALUE']) {
				CIBlockElement::SetPropertyValuesEx($arProductIBFields['ID'], $arSettings['MAIN_CATALOG_IBLOCK_ID'], array('AUTO_CALCULATED_SEARCH_WORDS' => array("VALUE"=>array('TYPE'=>'TEXT','TEXT'=>$search_str))));
			}
			autoCalculateCatalogSectionFields($arProductIBFields['IBLOCK_SECTION_ID'], false, true);
		}
	}
}

/************************** HANDLERS INIT **************************/
AddEventHandler("iblock", "OnAfterIBlockElementUpdate", "IBlockElementAfterSaveHandler");
AddEventHandler("iblock", "OnAfterIBlockElementAdd", "IBlockElementAfterSaveHandler");

AddEventHandler("iblock", "OnAfterIBlockSectionUpdate", array('EventHandler', "OnAfterIBlockSectionUpdateHandler"));
AddEventHandler("iblock", "OnAfterIBlockSectionAdd", array('EventHandler', "OnAfterIBlockSectionUpdateHandler"));

AddEventHandler("catalog", "OnPriceAdd", "IBlockElementAfterSaveHandler");
AddEventHandler("catalog", "OnPriceUpdate", "IBlockElementAfterSaveHandler");
AddEventHandler("catalog", "OnProductUpdate", "IBlockElementAfterSaveHandler");

AddEventHandler("sale", "OnSaleStatusOrder", "OrderStatusUpdateHandler");


function IBlockElementAfterSaveHandler($arg1, $arg2 = false)
{
	CModule::IncludeModule("iblock");
	CModule::IncludeModule("catalog");
	
	$arSettings = getCustomSettings();
	
	$ELEMENT_ID = false;
	$ELEMENT_IBLOCK_ID = false;
	$MODE = false;

    // Check for catalog event
    if (is_array($arg2) && $arg2["PRODUCT_ID"] > 0) {
		$MODE = 'catalog';
        $arPriceElement = CIBlockElement::GetList(array(), array("ID" => $arg2["PRODUCT_ID"]), false, false, array("ID", "IBLOCK_ID"))->GetNext();
		$ELEMENT_ID = $arPriceElement['ID'];
		$ELEMENT_IBLOCK_ID = $arPriceElement['IBLOCK_ID'];
    } // Check for iblock event
    elseif (is_array($arg1) && $arg1["ID"] > 0 && $arg1["IBLOCK_ID"] > 0) {
		$MODE = 'iblock';
		$ELEMENT_ID = $arg1["ID"];
		$ELEMENT_IBLOCK_ID = $arg1["IBLOCK_ID"];
    }
	
	// FUNCTIONS FOR CATALOG
	if($ELEMENT_IBLOCK_ID == $arSettings['MAIN_CATALOG_IBLOCK_ID']) {
		if($MODE == 'catalog') {
			autoCalculateCatalogProductFields($ELEMENT_ID,true,false,false);	
		} else {
			autoCalculateCatalogProductFields($ELEMENT_ID,false,false,true);	
		}
	} elseif (($ELEMENT_IBLOCK_ID==$arSettings['OFFERS_IBLOCK_ID']) and ($MODE == 'catalog')) {
		$arOfferElementLink = CCatalogSku::GetProductInfo($ELEMENT_ID);
		if($arOfferElementLink['ID'] and ($arOfferElementLink['IBLOCK_ID'] == $arSettings['MAIN_CATALOG_IBLOCK_ID'])) {
			autoCalculateCatalogProductFields($arOfferElementLink['ID'],true,false,false);	
		}
	}
}

function OrderStatusUpdateHandler($ORDER_ID, $STATUS) {
	if($STATUS == 'F') {
		CModule::IncludeModule("iblock");
		CModule::IncludeModule("catalog");
		CModule::IncludeModule("sale");
		$arSettings = getCustomSettings();
		$obIblockElement = new CIBlockElement;
		
		$arOrder = CSaleOrder::GetByID($ORDER_ID);
		$arUser = CUser::GetByID($arOrder['USER_ID'])->Fetch();
		$user_name = $arUser['NAME'];
		if($arUser['LAST_NAME']) {
			$user_name .= ' ' . $arUser['LAST_NAME'];
		}
		$user_name = trim($user_name);
		
		$arReviews = array();
		
		$obOrderItems = CSaleBasket::GetList(array(), array("ORDER_ID" => $ORDER_ID));
		while ($arOrderItem = $obOrderItems->Fetch()) {
			$arProduct = CIBlockElement::GetByID($arOrderItem["PRODUCT_ID"])->GetNext();
			$product_id = $arProduct['ID'];
			$product_name = $arProduct['NAME'];
			if($arProduct['IBLOCK_ID']==$arSettings["OFFERS_IBLOCK_ID"]) {
				$tmp_id = $arProduct['ID'];
				$arProduct = CIBlockElement::GetList(array(), array('IBLOCK_ID'=>$arSettings['OFFERS_IBLOCK_ID'],'ID'=>$tmp_id), false, false, array('ID','IBLOCK_ID','PROPERTY_'.$arSettings['CML2_LINK_PROP_CODE'], 'PROPERTY_'.$arSettings['CML2_LINK_PROP_CODE'].'.NAME'))->GetNext();
				if($arProduct['PROPERTY_'.$arSettings['CML2_LINK_PROP_CODE'].'_VALUE']) {
					$product_id = $arProduct['PROPERTY_'.$arSettings['CML2_LINK_PROP_CODE'].'_VALUE'];
				}
				if($arProduct['PROPERTY_'.$arSettings['CML2_LINK_PROP_CODE'].'_NAME']) {
					$product_name = $arProduct['PROPERTY_'.$arSettings['CML2_LINK_PROP_CODE'].'_NAME'];
				}
			}
			if($product_id and $product_name) {
				// create inactive review (after it will be filled by user - it will be activated)
				$secret = md5($arOrder['ID'] . $product_id . time()); // used to allow user make a review
				$arProps = array(
					'USER' => $arOrder['USER_ID'],
					'ORDER' => $arOrder['ID'],
					'PRODUCT' => $product_id,
					'PRODUCT_NAME' => $product_name,
					'SECRET' => $secret
				);
				$new_review_id = $obIblockElement->Add(array('IBLOCK_ID'=>$arSettings["REVIEWS_IBLOCK_ID"],"PROPERTY_VALUES"=>$arProps,'NAME'=>$user_name . ' о товаре «' . $product_name . '»', 'ACTIVE'=>'N', 'CREATED_BY'=>$arOrder['USER_ID']));
				$arReviews[] = array(
					'ID'=>$new_review_id,
					'SECRET'=>$secret,
					'PRODUCT' => $product_name
				);
			}
		}
		$mail_text = "";
		if(count($arReviews)) {
			$mail_text = "Просим вас написать свои впечатления от приобретенных товаров:";
			$mail_text .= "\n";
			foreach($arReviews as $arReview) {
				$mail_text .= '- ' . $arReview['PRODUCT'] . ': http://www.shopograd.ru/dlya-zhiteley/moi-otzyvy/add/?ID=' . $arReview['ID'] . '&hash=' . $arReview['SECRET'];
				$mail_text .= "\n";
			}
		}
		// add one more review - aboute whole shop
		$secret = md5($arOrder['ID'] . 'shop' . time());
		$arProps = array(
			'USER' => $arOrder['USER_ID'],
			'ORDER' => $arOrder['ID'],
			'SECRET' => $secret
		);
		$new_review_id = $obIblockElement->Add(array('IBLOCK_ID'=>$arSettings["REVIEWS_IBLOCK_ID"],"PROPERTY_VALUES"=>$arProps,'NAME'=>$user_name . ' о магазине Шопоград', 'ACTIVE'=>'N', 'CREATED_BY'=>$arOrder['USER_ID']));
		if(count($arReviews)) {
			$mail_text .= "\n";
			$mail_text .= "Также мы будем очень признательны за отзыв о работе нашего магазина:";
			$mail_text .= "\n";	
		} else {
			$mail_text .= "Мы будем рады получить ваш отзыв о работе нашего магазина:";
			$mail_text .= "\n";
		}
		$mail_text .= 'http://www.shopograd.ru/dlya-zhiteley/moi-otzyvy/add/?ID=' . $new_review_id . '&hash=' . $secret;
		// send mail
		$arEventFields = array(
			"USER_NAME" => $user_name,
			"USER_EMAIL" => $arUser["EMAIL"],
			"ORDER_ID" => $arOrder['ID'],
			"TEXT" => $mail_text
		);
		CEvent::Send("ASK_FOR_REVIEW", "s1", $arEventFields);
	}
}

function test_dump($arg){
    global $USER;
    if($USER->IsAdmin()){
        echo "<pre>";
        var_dump($arg);
        echo "</pre>";

    }
}
?>