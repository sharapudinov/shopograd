<?
// USAGE: Call www.site.ru/_tools/import/product_prepare.php?id=123&hash=1298721983798217 using AJAX before proceeding user to product detail page
// hash = md5(id.secret)
// Page returns OK or ERROR

// SETTINGS
$arSettings = array(
	"SECRET" => 'taobaoproduct',
	"WEBSERVICE" => "http://otapi.net/OtapiWebService2.asmx/BatchGetItemFullInfo?instanceKey=710057b8-b668-41f9-a009-f60b82462a63&language=ru&sessionId=&blockList=Promotions",
	"WEBSERVICE2" => "http://otapi.net/OtapiWebService2.asmx/GetItemDescription?instanceKey=710057b8-b668-41f9-a009-f60b82462a63&language=ru",
	"PROFILES_IBLOCK_ID" => 15,
	"MAIN_IBLOCK_ID" => 1,
	"OFFERS_IBLOCK_ID" => 2,
	"CACHE_TIME" => 86400, // update information not more often than 1 day
	"PROFILE_PROPERTIES"=> array(
		"PROFIT" => "PROFIT",
		"SKIP_FIRST_PICTURE" => "SKIP_FIRST_PICTURE",
		"DUBLICATE_PICTURES" => "DUBLICATE_PICTURES",
		"IMPORT_ONLY_ONE_PICTURE" => "IMPORT_ONLY_ONE_PICTURE",
		"AUTO_SPECIAL_OFFER" => "AUTO_SPECIAL_OFFER",
		"IMPORT_DETAIL_TEXT" => "IMPORT_DETAIL_TEXT",
		"WEIGHT" => "APPROX_WEIGHT"
	),
	"PRODUCT_PROPERTIES"=> array(
		"PICTURES"=>"PICTURES",
		"PICTURES_LINKS"=>"PICTURES_LINKS",
		"CHARS" => "CHARS",
		"ADD_PICTURES" => "ADD_PICTURES",
		"FORCE_IMPORT_ACTIVITY"=>"FORCE_IMPORT_ACTIVITY",
		"IMPORT_PROFILE_ID"=>"IMPORT_PROFILE_ID",
		"IMPORT_FULL_DESCRIPTION"=>"IMPORT_FULL_DESCRIPTION",
		"IMPORT_PRICES_TIMESTAMP"=>"IMPORT_PRICES_TIMESTAMP",
		"IMPORT_RAW_DATA" => "IMPORT_RAW_DATA",
		"ADD_PICTURES_LINKS" => "ADD_PICTURES_LINKS",
		"SPECIAL_OFFER_TILL" => "SPECIAL_OFFER_TILL",
		"OLD_PRICE" => "OLD_PRICE",
		"FILTER_FIELD_PREFIX" => "FILTER_FIELD_"
	),
	"OFFERS_PROPERTIES"=> array(
		"PICTURES"=>"PICTURES",
		"CODE" => "CODE",
		"CML2_LINK" => "CML2_LINK",
		"PARAMS"=>"PARAMS",
		"PICTURES_LINKS" => "PICTURES_LINKS"
	),
	"SECTION_PROPERTIES"=> array(
		"CURRENCY" => "UF_CURRENCY", // currency to convert prices to
		"FILTER_FIELD_1" => "UF_FILTER_FIELD_1",
		"FILTER_FIELD_2" => "UF_FILTER_FIELD_2",
		"FILTER_FIELD_3" => "UF_FILTER_FIELD_3",
		"FILTER_FIELD_4" => "UF_FILTER_FIELD_4",
		"FILTER_FIELD_5" => "UF_FILTER_FIELD_5",
		"WEIGHT" => "UF_WEIGHT"
	),
	"PRICE_ID" => 1
);

if($_REQUEST['id'] and $_REQUEST['hash']=md5(trim($_REQUEST['id']).$arSettings['SECRET'])) {

	// INIT
	
	//define("LANG", "ru");
	define("NO_KEEP_STATISTIC", true); 
	define("NOT_CHECK_PERMISSIONS", true); 
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); 
	
	// MAIN LOGICS
	
	$obCache = new CPHPCache;
	$cache_id = "opentao_product_import_" . $_REQUEST['id'];
	if($obCache->InitCache($arSettings['CACHE_TIME'], $cache_id, '/custom_cache/')) {
		// means cache is up to date - so we have to do nothing
		$arStatus = $obCache->GetVars();
		echo $arStatus['STATUS'];
	} else {
		CModule::IncludeModule('iblock');
		$obIblockElement = new CIBlockElement;
		CModule::IncludeModule('catalog');
		CModule::IncludeModule('sale');
		CModule::IncludeModule('currency');
		
		$STATUS = "OK";
		
		$arProduct = CIBlockElement::GetList(array('ID'=>'ASC'), array('IBLOCK_ID'=>$arSettings["MAIN_IBLOCK_ID"], 'ID'=>intval(trim($_REQUEST['id'])), 'ACTIVE'=>"Y", "ACTIVE_DATE"=>"Y", "!PROPERTY_".$arSettings['PRODUCT_PROPERTIES']['IMPORT_PROFILE_ID']=>""), false, array('nTopCount'=>1), array('ID','IBLOCK_ID','NAME','PROPERTY_'.$arSettings['PRODUCT_PROPERTIES']['IMPORT_PROFILE_ID'],'PROPERTY_'.$arSettings['PRODUCT_PROPERTIES']['IMPORT_FULL_DESCRIPTION'],'IBLOCK_SECTION_ID','XML_ID'))->GetNext();
		
		if($arProduct['ID']) {
			
			$curl = curl_init();
			$general_error = false;
			curl_setopt($curl, CURLOPT_URL, $arSettings["WEBSERVICE"] . "&itemId=" . $arProduct['XML_ID']);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_HEADER, 0);
			 
			$result = curl_exec($curl);
			if ($result === FALSE) {
				$general_error = true;
				$STATUS = "ERROR: " . curl_error($curl);
			}
			$xmlObject = simplexml_load_string($result);
			 
			curl_close($curl);
			 
			if ((string)$xmlObject->ErrorCode !== 'Ok') {
				$general_error = true;
				$STATUS = "ERROR: " . $xmlObject->ErrorDescription;
			}
			
			if(!$general_error) {
				if(($arProduct['PROPERTY_'.$arSettings['PRODUCT_PROPERTIES']['FORCE_IMPORT_ACTIVITY'].'_VALUE'] and strlen($xmlObject->Result->Item->Id)) or (((string)$xmlObject->Result->Item->HasError == 'false') and ((string)$xmlObject->Result->Item->ErrorCode == 'Ok'))) { // ($xmlObject->Result->Item->IsSellAllowed == 'true') and 
				
					$arProfile = CIBlockElement::GetList(array('ID'=>'ASC'), array('IBLOCK_ID'=>$arSettings["PROFILES_IBLOCK_ID"], 'ID'=>$arProduct['PROPERTY_'.$arSettings['PRODUCT_PROPERTIES']['IMPORT_PROFILE_ID'].'_VALUE']), false, array('nTopCount'=>1), array('ID','IBLOCK_ID','PROPERTY_'.$arSettings['PROFILE_PROPERTIES']['PROFIT'],'PROPERTY_'.$arSettings['PROFILE_PROPERTIES']['SKIP_FIRST_PICTURE'],'PROPERTY_'.$arSettings['PROFILE_PROPERTIES']['DUBLICATE_PICTURES'],'PROPERTY_'.$arSettings['PROFILE_PROPERTIES']['IMPORT_ONLY_ONE_PICTURE'],'PROPERTY_'.$arSettings['PROFILE_PROPERTIES']['AUTO_SPECIAL_OFFER'],'PROPERTY_'.$arSettings['PROFILE_PROPERTIES']['IMPORT_DETAIL_TEXT'],'PROPERTY_'.$arSettings['PROFILE_PROPERTIES']['WEIGHT']))->GetNext();
					$arSection = CIBlockSection::GetList(array("ID"=>"ASC"), array("IBLOCK_ID"=>$arSettings["MAIN_IBLOCK_ID"], "ID" => $arProduct['IBLOCK_SECTION_ID']), false, array("ID", "IBLOCK_ID", $arSettings['SECTION_PROPERTIES']['CURRENCY'], $arSettings['SECTION_PROPERTIES']['FILTER_FIELD_1'], $arSettings['SECTION_PROPERTIES']['FILTER_FIELD_2'], $arSettings['SECTION_PROPERTIES']['FILTER_FIELD_3'], $arSettings['SECTION_PROPERTIES']['FILTER_FIELD_4'], $arSettings['SECTION_PROPERTIES']['FILTER_FIELD_5'], $arSettings['SECTION_PROPERTIES']['WEIGHT']))->GetNext();
					$rsEnum = CUserFieldEnum::GetList(array(), array("ID"=>$arSection[$arSettings['SECTION_PROPERTIES']['CURRENCY']])); 
					$arEnum = $rsEnum->GetNext();
					$arSection[$arSettings['SECTION_PROPERTIES']['CURRENCY']] = $arEnum["VALUE"];
					$arFilterFieldsNames = array();
					$arProductFilterProps = array();
					for($i=1;$i<=5;$i++) {
						if($arSection[$arSettings['SECTION_PROPERTIES']['FILTER_FIELD_' . $i]])	{
							$arFilterFieldsNames[$arSettings['PRODUCT_PROPERTIES']['FILTER_FIELD_PREFIX'] . $i] = $arSection[$arSettings['SECTION_PROPERTIES']['FILTER_FIELD_' . $i]];
							$arProductFilterProps[$arSettings['PRODUCT_PROPERTIES']['FILTER_FIELD_PREFIX'] . $i] = array();
						}
					}
					
					$product_weight = 0;
					if($arSection[$arSettings['SECTION_PROPERTIES']['WEIGHT']]) {
						$product_weight = $arSection[$arSettings['SECTION_PROPERTIES']['WEIGHT']];
					}
					if($arProfile['PROPERTY_'.$arSettings['PROFILE_PROPERTIES']['WEIGHT'].'_VALUE']) {
						$product_weight = $arProfile['PROPERTY_'.$arSettings['PROFILE_PROPERTIES']['WEIGHT'].'_VALUE'];
					}
					
					$arProductChars = array();
					foreach($xmlObject->Result->Item->Attributes->ItemAttribute as $XMLItemAttribute) {
						$arProductChars[intval($XMLItemAttribute['Pid'])]['NAME'] = (string)$XMLItemAttribute->PropertyName;
						$val = (string)$XMLItemAttribute->Value;
						if((string)$XMLItemAttribute->ValueAlias) {
							$val = $val . ' / ' . (string)$XMLItemAttribute->ValueAlias;	
						}
						$arProductChars[intval($XMLItemAttribute['Pid'])]['VALUES'][intval($XMLItemAttribute['Vid'])]['VALUE'] = $val;
						if((string)$XMLItemAttribute->ImageUrl) {
							$arProductChars[intval($XMLItemAttribute['Pid'])]['VALUES'][intval($XMLItemAttribute['Vid'])]['IMG'] = (string)$XMLItemAttribute->ImageUrl;	
						}
						if(in_array((string)$XMLItemAttribute->PropertyName,$arFilterFieldsNames)) {
							$arProductFilterProps[array_search((string)$XMLItemAttribute->PropertyName,$arFilterFieldsNames)][] = $val;
						}
					}
					
					$product_min_price = floatval($xmlObject->Result->Item->Price->OriginalPrice);
					$product_max_discount = 1;
					$arProductOffersPromotions = array();
					foreach($xmlObject->Result->Item->Promotions->OtapiItemPromotion as $XMLPromotion) {
						if(floatval($XMLPromotion->Price->OriginalPrice) < $product_min_price) {
							$product_min_price = floatval($XMLPromotion->Price->OriginalPrice);
							if((floatval($XMLPromotion->Price->OriginalPrice)/floatval($xmlObject->Result->Item->Price->OriginalPrice))<$product_max_discount) {
								$product_max_discount = floatval($XMLPromotion->Price->OriginalPrice)/floatval($xmlObject->Result->Item->Price->OriginalPrice);	
							}
						}
						foreach($XMLPromotion->ConfiguredItems->Item as $XMLPromotionOffer) {
							if(intval($XMLPromotionOffer->Id) and ((string)$XMLPromotionOffer->ErrorCode=='Ok') and ((string)$XMLPromotionOffer->HasError=='false')) {
								if(!$arProductOffersPromotions[intval($XMLPromotionOffer->Id)] or ($arProductOffersPromotions[intval($XMLPromotionOffer->Id)]>floatval($XMLPromotionOffer->Price->OriginalPrice))) {
									$arProductOffersPromotions[intval($XMLPromotionOffer->Id)] = floatval($XMLPromotionOffer->Price->OriginalPrice);
									if((floatval($XMLPromotionOffer->Price->OriginalPrice)/floatval($xmlObject->Result->Item->Price->OriginalPrice)) < $product_max_discount)  {
										$product_max_discount = floatval($XMLPromotionOffer->Price->OriginalPrice)/floatval($xmlObject->Result->Item->Price->OriginalPrice);		
									}
								}
							}
						}
					}
					$product_final_price = CCurrencyRates::ConvertCurrency($product_min_price*(100+$arProfile['PROPERTY_'.$arSettings['PROFILE_PROPERTIES']['PROFIT'].'_VALUE'])/100, (string)$xmlObject->Result->Item->Price->OriginalCurrencyCode, $arSection[$arSettings['SECTION_PROPERTIES']['CURRENCY']]);

					$arProductOffers = array();
					foreach($xmlObject->Result->Item->ConfiguredItems->OtapiConfiguredItem as $XMLOffer) {
						
						
						$offer_name = TruncateText($arProduct['NAME'],30);
						$arOfferPictures = array();
						$arOfferPicturesLinks = array();
						$arOfferParams = array();
						foreach($XMLOffer->Configurators->ValuedConfigurator as $XMLConf) {
							if(count($arProductChars[intval($XMLConf['Pid'])]['VALUES'])>1) {
								$arOfferParams[] = array("VALUE"=>$arProductChars[intval($XMLConf['Pid'])]['NAME'],"DESCRIPTION"=>$arProductChars[intval($XMLConf['Pid'])]['VALUES'][intval($XMLConf['Vid'])]['VALUE']);
								$offer_name .= " " . $arProductChars[intval($XMLConf['Pid'])]['NAME'] . ": " . $arProductChars[intval($XMLConf['Pid'])]['VALUES'][intval($XMLConf['Vid'])]['VALUE'] . ";";
							}	
							if($arProductChars[intval($XMLConf['Pid'])]['VALUES'][intval($XMLConf['Vid'])]['IMG']) {
								if($arProfile['PROPERTY_'.$arSettings['PROFILE_PROPERTIES']['IMPORT_ONLY_ONE_PICTURE'].'_VALUE']) {
									$arOfferPicturesLinks[] = array("VALUE"=>$arProductChars[intval($XMLConf['Pid'])]['VALUES'][intval($XMLConf['Vid'])]['IMG']);	
								} else {
									$arOfferPictures[] = array("VALUE" => CFile::MakeFileArray($arProductChars[intval($XMLConf['Pid'])]['VALUES'][intval($XMLConf['Vid'])]['IMG']),"DESCRIPTION"=>"");
								}
							}
						}
						$min_price = floatval($XMLOffer->Price->OriginalPrice);
						if($arProductOffersPromotions[intval($XMLOffer->Id)] and ($arProductOffersPromotions[intval($XMLPromotionOffer->Id)]<$min_price)) {
							$min_price = $arProductOffersPromotions[intval($XMLOffer->Id)];	
						}
						$final_price = CCurrencyRates::ConvertCurrency($min_price*(100+$arProfile['PROPERTY_'.$arSettings['PROFILE_PROPERTIES']['PROFIT'].'_VALUE'])/100, (string)$XMLOffer->Price->OriginalCurrencyCode, $arSection[$arSettings['SECTION_PROPERTIES']['CURRENCY']]);
						$arProductOffers[intval($XMLOffer->Id)] = array(
							"NAME" => $offer_name,
							"PROPS" => array(
								$arSettings["OFFERS_PROPERTIES"]["PICTURES"] => $arOfferPictures,
								$arSettings["OFFERS_PROPERTIES"]["PICTURES_LINKS"] => $arOfferPicturesLinks,
								$arSettings["OFFERS_PROPERTIES"]["CODE"] => intval($xmlObject->Result->Item->Id) . "_" . intval($XMLOffer->Id),
								$arSettings["OFFERS_PROPERTIES"]["CML2_LINK"] => $arProduct['ID'],
								$arSettings["OFFERS_PROPERTIES"]["PARAMS"] => $arOfferParams
							),
							"MIN_PRICE" => $min_price, // purchase price (in initial currency)
							"MIN_PRICE_CURRENCY" => (string)$XMLOffer->Price->OriginalCurrencyCode,
							"FINAL_PRICE" => floatval($final_price), // price with profit and in required currency
							"QUANTITY" => intval($XMLOffer->Quantity)
						);
					}
					
					$arProductIBOffers = array();
					$obProductIBOffers = CIBlockElement::GetList(array('ID'=>'ASC'), array('IBLOCK_ID'=>$arSettings["OFFERS_IBLOCK_ID"], 'PROPERTY_'.$arSettings["OFFERS_PROPERTIES"]["CML2_LINK"]=>$arProduct['ID']), false, false, array('ID','IBLOCK_ID','XML_ID'));
					while($arProductIBOffer = $obProductIBOffers->GetNext()) {
						if(!$arProductOffers[intval(preg_replace('/' . $arProduct['XML_ID'] . "_" . '/i','',$arProductIBOffer['XML_ID']))])	{
							$obIblockElement->Update($arProductIBOffer['ID'], array("ACTIVE" => "N"));
						} else {
							$arProductIBOffers[] = array(
								"ID" => $arProductIBOffer['ID'],
								"XML_ID" => $arProductIBOffer['XML_ID'],
								"UPDATE" => $arProductOffers[preg_replace('/' . $arProduct['XML_ID'] . "_" . '/i','',$arProductIBOffer['XML_ID'])]
							);
							unset($arProductOffers[preg_replace('/' . $arProduct['XML_ID'] . "_" . '/i','',$arProductIBOffer['XML_ID'])]);
						}
					}
					
					if(count($arProductOffers)) { // now this array contains only new offers we must add
						foreach($arProductOffers as $arProductOffer) {
							$new_offer_id = $obIblockElement->Add(array('IBLOCK_ID'=>$arSettings["OFFERS_IBLOCK_ID"], 'ACTIVE' => 'Y', 'NAME'=>$arProductOffer['NAME'], 'PROPERTY_VALUES'=>$arProductOffer['PROPS'], 'XML_ID'=>$arProductOffer['PROPS']['CODE']));
							CCatalogProduct::Add(array('ID'=>$new_offer_id,'QUANTITY'=>$arProductOffer['QUANTITY'],'QUANTITY_TRACE'=>'D','CAN_BUY_ZERO'=>'D', 'WEIGHT'=> $product_weight, 'NEGATIVE_AMOUNT_TRACE'=>'D','PURCHASING_PRICE'=>$arProductOffer['MIN_PRICE'],'PURCHASING_CURRENCY'=>$arProductOffer['MIN_PRICE_CURRENCY']));
							CPrice::DeleteByProduct($new_offer_id);
							$arPriceFields = array("PRODUCT_ID"=>$new_offer_id, "CATALOG_GROUP_ID"=>$arSettings['PRICE_ID'], 'WEIGHT'=> $product_weight, "PRICE"=>$arProductOffer['FINAL_PRICE'], "CURRENCY"=>$arSection[$arSettings['SECTION_PROPERTIES']['CURRENCY']], "QUANTITY_FROM"=>false, "QUANTITY_TO"=>false);
							CPrice::Add($arPriceFields);
						}
					}
					if(count($arProductIBOffers)) { // old offers we must update
						foreach($arProductIBOffers as $arProductIBOffer) {
							CCatalogProduct::Update($arProductIBOffer['ID'], array('QUANTITY'=>$arProductIBOffer['UPDATE']['QUANTITY'], 'WEIGHT'=> $product_weight,'PURCHASING_PRICE'=>$arProductIBOffer['UPDATE']['MIN_PRICE'],'PURCHASING_CURRENCY'=>$arProductIBOffer['UPDATE']['MIN_PRICE_CURRENCY']));
							CPrice::DeleteByProduct($arProductIBOffer['ID']);
							$arPriceFields = array("PRODUCT_ID"=>$arProductIBOffer['ID'], "CATALOG_GROUP_ID"=>$arSettings['PRICE_ID'], "PRICE"=>$arProductIBOffer['UPDATE']['FINAL_PRICE'], "CURRENCY"=>$arSection[$arSettings['SECTION_PROPERTIES']['CURRENCY']], "QUANTITY_FROM"=>false, "QUANTITY_TO"=>false);
							CPrice::Add($arPriceFields);
						}
					}
					
					if(!count($arProductOffers) and !count($arProductIBOffers)) { // when product has no offers
						CCatalogProduct::Add(array('ID'=>$arProduct['ID'],'QUANTITY'=>intval($xmlObject->Result->Item->MasterQuantity),'QUANTITY_TRACE'=>'D','CAN_BUY_ZERO'=>'D','NEGATIVE_AMOUNT_TRACE'=>'D', 'WEIGHT'=> $product_weight,'PURCHASING_PRICE'=>$product_min_price,'PURCHASING_CURRENCY'=>(string)$xmlObject->Result->Item->Price->OriginalCurrencyCode));	
						CPrice::SetBasePrice($arProduct['ID'], $product_final_price, $arSection[$arSettings['SECTION_PROPERTIES']['CURRENCY']]);
					}
					
					ob_start();
					var_dump($xmlObject);
					$raw_data = ob_get_clean();
					
					$arProductUpdateProps = array(
						$arSettings['PRODUCT_PROPERTIES']['IMPORT_PRICES_TIMESTAMP'] => date("d.m.Y H:i:s",time()),
						//$arSettings['PRODUCT_PROPERTIES']["IMPORT_RAW_DATA"] => array("VALUE" => array("TEXT"=>$raw_data,"TYPE"=>"HTML"))
					);
					if(!$arProduct['PROPERTY_'.$arSettings['PRODUCT_PROPERTIES']['IMPORT_FULL_DESCRIPTION'].'_VALUE']) {
						
						// loading item detail text
						if($arProfile['PROPERTY_'.$arSettings['PROFILE_PROPERTIES']['IMPORT_DETAIL_TEXT'].'_VALUE']) {
							$curl = curl_init();
							$general_error = false;
							curl_setopt($curl, CURLOPT_URL, $arSettings["WEBSERVICE2"] . "&itemId=" . $arProduct['XML_ID']);
							curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
							curl_setopt($curl, CURLOPT_HEADER, 0);
							 
							$result = curl_exec($curl);
							if ($result === FALSE) {
								$general_error = true;
							}
							$xmlObject2 = simplexml_load_string($result);
							 
							curl_close($curl);
							if ((string)$xmlObject2->ErrorCode !== 'Ok') {
								$general_error = true;
							}
							if(!$general_error) {
								require('../jevix.class.php'); // use typography and correct html layout
								$jevix = new Jevix();
								$jevix->cfgAllowTags(array('a', 'img', 'i', 'b', 'u', 'em', 'strong', 'nobr', 'li', 'ol', 'ul', 'sup', 'abbr', 'pre', 'acronym', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'adabracut', 'br', 'code'));
								$jevix->cfgSetTagShort(array('br','img'));
								$jevix->cfgSetTagCutWithContent(array('script', 'object', 'iframe', 'style'));
								$jevix->cfgAllowTagParams('a', array('title', 'href'));
								$jevix->cfgAllowTagParams('img', array('src', 'alt' => '#text', 'title', 'align' => array('right', 'left', 'center'), 'width' => '#int', 'height' => '#int', 'hspace' => '#int', 'vspace' => '#int'));
								$jevix->cfgSetTagParamsRequired('img', 'src');
								$jevix->cfgSetTagParamsRequired('a', 'href');
								$jevix->cfgSetTagChilds('ul', 'li', true, true);
								$jevix->cfgSetTagParamDefault('a', array('name'=>'rel', 'value' => 'nofollow', 'rewrite' => true));
								$jevix->cfgSetXHTMLMode(true);
								$jevix->cfgSetAutoBrMode(false);
								$jevix->cfgSetAutoLinkMode(false);
								$obIblockElement->Update($arProduct['ID'], array("DETAIL_TEXT" => $jevix->parse((string)$xmlObject2->OtapiItemDescription->ItemDescription), "DETAIL_TEXT_TYPE"=>"html"));
							}
							ob_start();
							var_dump($xmlObject2);
							$raw_data = ob_get_clean();
							$arProductUpdateProps[$arSettings['PRODUCT_PROPERTIES']["IMPORT_RAW_DATA"]]['VALUE']['TEXT'] .= $raw_data;
						}
						
						if(count($arProductChars)) {
							$arCharsReady = array();
							foreach($arProductChars as $arProductChar) {
								$arProductCharValues = array();
								foreach($arProductChar['VALUES'] as $v) {
									$arProductCharValues[] = $v['VALUE'];
								}
								$arCharsReady[]	= array("VALUE"=>$arProductChar['NAME'],"DESCRIPTION"=>implode("; ", $arProductCharValues));
							}
							$arProductUpdateProps[$arSettings['PRODUCT_PROPERTIES']['CHARS']] = $arCharsReady;
						}
						
						$counter = 0;
						$add_counter=0;
						$arProductUpdateProps[$arSettings['PRODUCT_PROPERTIES']['PICTURES']] = array();
						$arProductUpdateProps[$arSettings['PRODUCT_PROPERTIES']['ADD_PICTURES']] = array();
						$arProductUpdateProps[$arSettings['PRODUCT_PROPERTIES']['ADD_PICTURES_LINKS']] = array();
						foreach($xmlObject->Result->Item->Pictures->ItemPicture as $arPicture) {
							$counter++;
							if($arProfile['PROPERTY_'.$arSettings['PROFILE_PROPERTIES']['SKIP_FIRST_PICTURE'].'_VALUE'] and ($counter==1)) {
								// skip first ugly picture
							} else {
								$add_counter++;
								if(($arProfile['PROPERTY_'.$arSettings['PROFILE_PROPERTIES']['IMPORT_ONLY_ONE_PICTURE'].'_VALUE'] and ($add_counter==1)) or (!$arProfile['PROPERTY_'.$arSettings['PROFILE_PROPERTIES']['IMPORT_ONLY_ONE_PICTURE'].'_VALUE'] and ((!$arProfile['PROPERTY_'.$arSettings['PROFILE_PROPERTIES']['SKIP_FIRST_PICTURE'].'_VALUE'] and ($add_counter<=4)) or ($arProfile['PROPERTY_'.$arSettings['PROFILE_PROPERTIES']['SKIP_FIRST_PICTURE'].'_VALUE'] and ($add_counter<=5))))) {
									if($arProfile['PROPERTY_'.$arSettings['PROFILE_PROPERTIES']['IMPORT_ONLY_ONE_PICTURE'].'_VALUE']) {
										$arProductUpdateProps[$arSettings['PRODUCT_PROPERTIES']['PICTURES_LINKS']][] = array("VALUE"=>(string)$arPicture->Url);
									} else {
										$arProductUpdateProps[$arSettings['PRODUCT_PROPERTIES']['PICTURES']][] = array("VALUE"=>CFile::MakeFileArray((string)$arPicture->Url));
									}
									if(strlen($arProfile['PROPERTY_'.$arSettings['PROFILE_PROPERTIES']['DUBLICATE_PICTURES'].'_VALUE'])) {
										if($arProfile['PROPERTY_'.$arSettings['PROFILE_PROPERTIES']['IMPORT_ONLY_ONE_PICTURE'].'_VALUE']) {
											$arProductUpdateProps[$arSettings['PRODUCT_PROPERTIES']['ADD_PICTURES_LINKS']][] = array("VALUE"=>(string)$arPicture->Url);
										} else {
											$arProductUpdateProps[$arSettings['PRODUCT_PROPERTIES']['ADD_PICTURES']][] = array("VALUE"=>CFile::MakeFileArray((string)$arPicture->Url));
										}
									}
								} else {
									if($arProfile['PROPERTY_'.$arSettings['PROFILE_PROPERTIES']['IMPORT_ONLY_ONE_PICTURE'].'_VALUE']) {
										$arProductUpdateProps[$arSettings['PRODUCT_PROPERTIES']['ADD_PICTURES_LINKS']][] = array("VALUE"=>(string)$arPicture->Url);	
									} else {
										$arProductUpdateProps[$arSettings['PRODUCT_PROPERTIES']['ADD_PICTURES']][] = array("VALUE"=>CFile::MakeFileArray((string)$arPicture->Url));	
									}
								}
							}
						}
						$arProductUpdateProps[$arSettings['PRODUCT_PROPERTIES']["IMPORT_FULL_DESCRIPTION"]] = date("d.m.Y H:i:s",time());
					}
					if($arProfile['PROPERTY_'.$arSettings['PROFILE_PROPERTIES']['AUTO_SPECIAL_OFFER'].'_VALUE'] and ($product_max_discount<1)) {
						$arProductUpdateProps[$arSettings['PRODUCT_PROPERTIES']["SPECIAL_OFFER_TILL"]] = date("d.m.Y H:i:s",time()+604800);
						$arProductUpdateProps[$arSettings['PRODUCT_PROPERTIES']["OLD_PRICE"]] = intval((1-$product_max_discount)*100);	
					} elseif($arProfile['PROPERTY_'.$arSettings['PROFILE_PROPERTIES']['AUTO_SPECIAL_OFFER'].'_VALUE'] and ($product_max_discount==1)) {
						$arProductUpdateProps[$arSettings['PRODUCT_PROPERTIES']["SPECIAL_OFFER_TILL"]] = "";
						$arProductUpdateProps[$arSettings['PRODUCT_PROPERTIES']["OLD_PRICE"]] = "";		
					}
					// adding filter properties values
					$arProductUpdateProps = array_merge($arProductFilterProps,$arProductUpdateProps);
					CIBlockElement::SetPropertyValuesEx($arProduct['ID'], $arProduct['IBLOCK_ID'], $arProductUpdateProps);
					autoCalculateCatalogProductFields($arProduct['ID'],true,false,true); // see init.php
					$STATUS = "OK";
				
				} else {
					
					$STATUS = "ERROR: Couldn't import product";
					$obIblockElement->Update($arProduct['ID'], array("ACTIVE" => "N"));
					
				}
					
			}
			
		} else {
			$STATUS = "ERROR: Product not found";	
		}
		
		echo $STATUS;
		
		$obCache->StartDataCache($arSettings['CACHE_TIME'], $cache_id, '/custom_cache/');
		$obCache->EndDataCache(array('STATUS'=>$STATUS));
	}

} else {
	echo "ERROR";	
}
?>